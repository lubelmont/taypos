<?php

namespace App\Http\Controllers\SimCo;


use App\Models\MercadoLibreOrder;
use App\Models\MercadoLibreOrderItem;
use App\Models\ESimGoOrder;
use App\Models\ESimGoOrderAssignments;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use App\Services\SimCo\SimCoPortalApiService;

class OrderFromPortalCotroller extends Controller
{
   
    public function getQRFromSimCoPortal(Request $request)
    {

        if (!$request->has('orderId')) {
            return response()->json(['error' => 'Parámetro "code" no proporcionado.'], 401);
        }

        $orderId = $request->query('orderId');


        if (empty($orderId)) {
            return response()->json(['error' => 'Parámetro "code" no proporcionado.'], 401);
        }

        if (strpos($orderId, 'wc_order_') === 0) {
            return $this->getQRFromSimCoStore($request);
        }else{
           return $this->getQRFromML($request);
        }


    
    }

    private function getQRFromSimCoStore(Request $request)
    {

        $orderChain = $request->query('orderId');


        if (empty($orderChain)) {
            return response()->json(['error' => 'Parámetro "code" no proporcionado.'], 401);
        }
        
        $parts = explode('-', $orderChain);
        $orderChain = $parts[0];
        $orderId = end($parts);
        // SIMCO-16769-wc_order_WAhdqRQCOp6mw
        
        $orderFrom = 'SIMCO-'.$orderId.'-'.$orderChain;

        $orders = ESimGoOrder::where('order_from', $orderFrom)->get();
       
        
        if(!$orders){
            return response()->json(['error' => 'No se encontró la orden.'], 401);
        }


        $simCoApiToImpl = new SimCoPortalApiService();
        $orderSimCo = $simCoApiToImpl->getOrderById($orderId);
        Log::debug('orderSimCo -----------------');
        Log::debug($orderSimCo);
        Log::debug('orderSimCo-----------------');
        $orderSimCo = json_decode($orderSimCo);

        $itemsToSell = $orderSimCo->line_items;
        $billing = $orderSimCo->billing;
        //dd($orders, $orderSimCo, $itemsToSell, $billing);
        
        
        $orderItemsToShow = []; 
        foreach($orders as $key => $order){
            $esimOrderAssignments  = ESimGoOrderAssignments::where('orderReference', $order['orderReference'])->get();
            
            $esimOrderAssignmentsToShow = [];
            $sku = $order['item'];
            //dd($esimOrderAssignments);


            foreach($esimOrderAssignments as $key => $subItem){
                $esimOrderAssignmentsToShow[] = $this->fillEsimOrderAssignmentsToShow($subItem);
            }

            $filteredItems = array_filter($itemsToSell, function($item) use ($sku) {
                return $item->sku == $sku;
            });
            
            $desiredItem = reset($filteredItems);

            
            $orderItemsToShow[] = [
                'id' => $desiredItem->id,
                'title' => $desiredItem->name,
                'quantity' => $desiredItem->quantity,
                'unit_price' => $desiredItem->subtotal,
                'total' => $desiredItem->subtotal,
                'esimOrderAssignmentsToShow' => $esimOrderAssignmentsToShow,
            ];
        }

       


        Log::info($orderItemsToShow);
        
        //$esimOrderAssignments = $esimOrder->assingnment()->get();


        $buyer= [
            'id' => '',
            'nickname' => $billing->email,
            'first_name' => $billing->first_name,
            'last_name' => $billing->last_name,
        ];
        
        
        return response()->json(['buyer' => $buyer,'orderItemsToShow'=>$orderItemsToShow]);
    }

    private function getQRFromML(Request $request)
    {

        $idOrderCrypted = $request->query('orderId');

        if (empty($idOrderCrypted)) {
            return response()->json(['error' => 'Parámetro "code" no proporcionado.'], 401);
        }

        $idOrder = Crypt::decrypt($idOrderCrypted);
        
        $order = MercadoLibreOrder::where('order_id', $idOrder)->first();
        $orderItems = MercadoLibreOrderItem::where('order_id', $idOrder)->get();
        $orderItemsToShow = [];
        
        foreach ($orderItems as $key => $item) {
            $esimOrder = ESimGoOrder::where('order_from', 'ML-'.$idOrder.'-'.$item->item_id)->first();
            $esimOrderAssignments = ESimGoOrderAssignments::where('orderReference', $esimOrder->orderReference)->get();
            
            $esimOrderAssignmentsToShow = [];
            foreach($esimOrderAssignments as $key => $subItem){
                $esimOrderAssignmentsToShow[] = $this->fillEsimOrderAssignmentsToShow($subItem);
            }

            $orderItemsToShow[] = [
                'id' => $item->id,
                'title' => $item->title,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->quantity * $item->unit_price,
                'esimOrderAssignmentsToShow' => $esimOrderAssignmentsToShow,
            ];
        }



        Log::debug($orderItemsToShow);
        
        //$esimOrderAssignments = $esimOrder->assingnment()->get();


        $buyer= [
            'id' => $order->buyer_id,
            'nickname' => $order->buyer_nickname,
            'first_name' => $order->buyer_first_name,
            'last_name' => $order->buyer_last_name,
        ];
        
        return response()->json(['buyer' => $buyer,'orderItemsToShow'=>$orderItemsToShow]);
    }

    private function fillEsimOrderAssignmentsToShow($esimOrderAssignment){

        if (empty($esimOrderAssignment->qr_svg)) {

            $qrImage = $this->getQRImage64($esimOrderAssignment->id,$esimOrderAssignment->matchingId,$esimOrderAssignment->rspUrl);
            $orderAssignment = ESimGoOrderAssignments::findOrFail($esimOrderAssignment->id);
            $orderAssignment->update(['qr_svg' => $qrImage]);

        } else {
            $qrImage = $esimOrderAssignment->qr_svg;
        }

        return [
            'id' => $esimOrderAssignment->id,
            'iccid' => $esimOrderAssignment->iccid,
            'matchingId' => $esimOrderAssignment->matchingId,
            'rspUrl' => $esimOrderAssignment->rspUrl,
            'qr' => $qrImage,
        ];

    }


    private function getQRImage64($matchingId,$rspUrl)
    {
        $data = 'LPA:1$'.$rspUrl.'$'.$matchingId;
        $qrCode = QrCode::format('svg')->generate($data);
        $qrCode = QrCode::generate($data);
        // replace text in svg
        $qrCode = str_replace('width="100"', 'width="200"', $qrCode);
        $qrCode = str_replace('height="100"', 'height="200"', $qrCode);
        //$qrCode = str_replace('fill="#000000"', 'fill="#FF0000"', $qrCode);

        return base64_encode($qrCode);
    }

    private function getZipFile($matchingId,$rspUrl)
    {
        
        $client = new Client(['base_uri' => env('ESIM_GO_URL')]);
        $uri = 
        $headers = [
          'X-API-Key' => '',
          'Accept' => 'application/zip'
        ];
        $headers = [
            'X-API-Key' => '',
            'Accept' => 'application/zip'
        ];
        $request = new GuzzleRequest('GET', 'https://api.esim-go.com/v2.2/esims/assignments/00de4daf-168e-40d2-9c59-bef33b788db1', $headers);
        $res = $client->sendAsync($request)->wait();
        echo $res->getBody();
    }

}