<?php

namespace App\Helpers\MercadoLibre;

use App\Models\MercadoLibreMessagePosSell;
use App\Models\MercadoLibreUsuario;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Services\MercadoLibre\MLSendMessages;

class CallMessages
{

    public function sendMensajeCompleOrder($order)
    {
        $id_mercadolibre = $order["seller_id"];

        $user = MercadoLibreUsuario::where('id_mercadolibre', $id_mercadolibre)->first();

        $messagePosSell = MercadoLibreMessagePosSell::where('user_id', $user->user_id)->first();

        $orderIDCrypted =  Crypt::encrypt($order->order_id);

        $uri = env('SIMCO_URL').'/ml-orders/?orderId='.$orderIDCrypted ;

        $message = $messagePosSell->message_to_bill;
        $message = str_replace("{URL}", '<a href="'.$uri.'"> Descarga tus codigos </a>', $message);
        $message = str_replace("{DIAS}", $messagePosSell->days_alive, $message);
       

        $sendMessageService = new MLSendMessages();
        return $sendMessageService->sendMessageToBuyer( $id_mercadolibre, $order->order_id,$order->seller_id,$order->buyer_id,$message);

    }

      
}
