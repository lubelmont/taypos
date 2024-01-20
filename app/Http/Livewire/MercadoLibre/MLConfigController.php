<?php

namespace App\Http\Livewire\MercadoLibre;


use App\Http\Livewire\Scaner;
use Illuminate\Support\Facades\Auth;


use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Traits\CartTrait;
use App\Models\MercadoLibreMessagePosSell;
use App\Models\MercadoLibreUsuario;
use Illuminate\Support\Facades\File;

class MLConfigController extends Component
{

	use WithPagination;
	use WithFileUploads;
	//use CartTrait;

	public $pageTitle;
	public $componentName;
	public $minToBill;
	public $daysAlive;
	public $messageToBill;
	public $metodosDePagoArr = [];
	public $formasDePagoArr = [];

	private $jsonPathFormaDePago;
	private $jsonPathMetodoDePago;
	private $userId;
	
	public function __construct()
    {
		$this->jsonPathFormaDePago = resource_path('cat/formaDePago.json');
		$this->jsonPathMetodoDePago = resource_path('cat/metodoDePago.json');
		$this->userId = Auth::id();
	}

	public function mount()
	{
		$this->pageTitle = 'Configuracion';
		$this->componentName = 'Mercadolibre';
		
		
		$data = MercadoLibreMessagePosSell::where('user_id',$this->userId )->first();
		
		$this->minToBill = 1;
		$this->daysAlive = 1;

		if($data){
			$this->minToBill = $data->position;
			$this->daysAlive = $data->days_alive;
			$this->messageToBill = $data->message_to_bill;
			$this->metodosDePagoArr = explode(",", $data->methods_payments); 
			$this->formasDePagoArr = explode(",", $data->payment_type); 

		}

	}




	public function render()
    {


		$urlMercadoLibre = "";

		$data = MercadoLibreUsuario::where('user_id',$this->userId )->first();
		if(!$data){
			$urlMercadoLibre = $this->getURLtoAuth();
		}

        $metodosDePago = json_decode(File::get($this->jsonPathMetodoDePago), true);
        $formasDePago = json_decode(File::get($this->jsonPathFormaDePago), true);
        return view('livewire.mercadolibre.configuracion',
			[
				'metodosDePago'=>$metodosDePago,
				'formasDePago'=>$formasDePago,
				'userMercadoLibre'=> $data,
				'urlMercadoLibre' => $urlMercadoLibre,
			]
		)
        ->extends('layouts.theme.app')
        ->section('content');
    }


	public function store()
    {

		$idUsuario = Auth::id();

        MercadoLibreMessagePosSell::updateOrcreate(
            ['user_id' => $idUsuario],
			[
				'position' => $this->minToBill,
				'days_alive' => $this->daysAlive,	
				'message_to_bill' => $this->messageToBill,
				'methods_payments' => implode(",", $this->metodosDePagoArr),
				'payment_type'=>implode(",", $this->formasDePagoArr),
			]
        );

		
		$this->emit('global-msg', "Se actualizo el mensaje");

        // Limpiamos los campos después de almacenar
       // $this->reset();
    }

	private function getURLtoAuth()
	{
		$client_id = env('MERCADO_LIBRE_CLIENT_ID');
		//$redirect_uri= 'https://b003-2806-105e-11-91b-90af-a46e-1f03-c2e3.ngrok-free.app/mercadolibre/auth';
    	$redirect_uri = env('APP_URL').'/mercadolibre/auth';;

		
		return 'https://auth.mercadolibre.com.mx/authorization?response_type=code&client_id='.$client_id.'&redirect_uri='.$redirect_uri.'&code_challenge=pWi3fny_hb4tt3TAT8WekPS33xOZwHKsFCGat4Wtqa8&code_challenge_method=S256';

	}

	

  

}
