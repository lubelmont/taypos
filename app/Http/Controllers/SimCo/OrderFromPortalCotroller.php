<?php

namespace App\Http\Controllers\SimCo;

use App\Helpers\MercadoLibre\CallTokenSesion;
use App\Models\MercadoLibreTokenSesion;
use App\Models\MercadoLibreUsuario;
use App\Models\MercadoLibreOrder;
use App\Models\MercadoLibreOrderItem;
use App\Models\ESimGoOrder;
use App\Models\ESimGoOrderAssignments;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use App\Helpers\SimCo\SimCoApiToImpl;

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
        
        
        $orders = ESimGoOrder::where('order_from', $orderChain)->get();
        
        if(!$orders){
            return response()->json(['error' => 'No se encontró la orden.'], 401);
        }

        $parts = explode('-', $orderChain);
        $orderId = end($parts);

        $simCoApiToImpl = new SimCoApiToImpl();

        $orderSimCo = $simCoApiToImpl->getOrderById($orderId);
        $orderSimCo = json_decode($orderSimCo);
        $itemsToSell = $orderSimCo->line_items;
        $billing = $orderSimCo->billing;
        
        
        
        foreach($orders as $key => $item){
            $esimOrderAssignments  = ESimGoOrderAssignments::where('orderReference', $item['orderReference'])->get();
            
            $esimOrderAssignmentsToShow = [];
            $orderItemsToShow = []; 
            $sku = $item['item'];


            foreach($esimOrderAssignments as $key => $subItem){
                $esimOrderAssignmentsToShow[]=[
                    'id' => $subItem->id,
                    'iccid' => $subItem->iccid,
                    'matchingId' => $subItem->matchingId,
                    'rspUrl' => $subItem->rspUrl,
                    'qr' => $this->getQRImage64($subItem->matchingId,$subItem->rspUrl),
                ];
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


        Log::info($idOrderCrypted);
        $idOrder = Crypt::decrypt($idOrderCrypted);
        Log::info($idOrder);

        $order = MercadoLibreOrder::where('id', $idOrder)->first();
        $orderItems = MercadoLibreOrderItem::where('order_id', $idOrder)->get();
        $orderItemsToShow = [];
        
        foreach ($orderItems as $key => $item) {
            $esimOrder = ESimGoOrder::where('order_from', 'ML-'.$idOrder.'-'.$item->item_id)->first();
            $esimOrderAssignments = ESimGoOrderAssignments::where('orderReference', $esimOrder->orderReference)->get();
            
            $esimOrderAssignmentsToShow = [];
            foreach($esimOrderAssignments as $key => $subItem){
                $esimOrderAssignmentsToShow[]=[
                    'id' => $subItem->id,
                    'iccid' => $subItem->iccid,
                    'matchingId' => $subItem->matchingId,
                    'rspUrl' => $subItem->rspUrl,
                    'qr' => $this->getQRImage64($subItem->matchingId,$subItem->rspUrl),
                ];
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



        Log::info($orderItemsToShow);
        
        //$esimOrderAssignments = $esimOrder->assingnment()->get();


        $buyer= [
            'id' => $order->buyer_id,
            'nickname' => $order->buyer_nickname,
            'first_name' => $order->buyer_first_name,
            'last_name' => $order->buyer_last_name,
        ];
        
        return response()->json(['buyer' => $buyer,'orderItemsToShow'=>$orderItemsToShow]);
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

        Log::info($qrCode);
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