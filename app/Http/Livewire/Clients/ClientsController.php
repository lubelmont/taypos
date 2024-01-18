<?php

namespace App\Http\Livewire\Clients;

use Livewire\Component;
use App\Models\Clients;

class ClientsController extends Component
{

    public  $taxpayer_id, $taxpayer_name, $taxpayer_regfiscal, $zip, $email, $selected_id, $pageTitle, $componentName;

	public $regimenesFiscales=[];

    //public $street,$num_ext,$num_int,$col,$locality,$municipality,$state,$country,$payment_default,$payment_method_default,$cfdi_use_default;
    
    private $pagination =5;
	

	
	public function mount()
	{
		$this->pageTitle = 'Listado';
		$this->componentName = 'Clientes';
				
	}



    public function render()
	{
        /*
		if(strlen($this->search) > 0)
			$data = Category::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);
		else
        $data = Category::orderBy('id','desc')->paginate($this->pagination);
        
        */

		if(!empty($this->taxpayer_id)){
			$this->regimenesFiscales=$this->updatedRRRTaxpayerId();
		}

        $data = Clients::all();
		



		return view('livewire.client.clients', ['clients' => $data])
		->extends('layouts.theme.app')
		->section('content');
	}


	public function updatedRRRTaxpayerId()
	{
		$jsonPath = storage_path('app/facCats/c_RegimenFiscal.json');
		$jsonObject = (object)[];

		if (file_exists($jsonPath)) {
			$jsonString = file_get_contents($jsonPath);
			$jsonObject = json_decode($jsonString);
		} 


		//dd($jsonObject);

		if ($this->esPersonaMoral($this->taxpayer_id)) {
			$fisicas = array_filter($jsonObject , function($element) {
				return str_contains($element->tipo_persona, 'Moral');
			});

			//dd($fisicas, $this->taxpayer_id);
			//$this->regimenesFiscales=$fisicas;
			
			return $fisicas;
		} else {
			$fisicas = array_filter($jsonObject , function($element) {
				return str_contains($element->tipo_persona, 'Física');
			});
			//dd($fisicas, $this->taxpayer_id);
			//$this->regimenesFiscales=$fisicas;
			
			return $fisicas;
		}
	}

	    
    public function Edit($id)
    {
    	$record = Clients::find($id, ['id','taxpayer_id','taxpayer_name','taxpayer_regfiscal','zip','email']);
		$this->taxpayer_id = $record->taxpayer_id; 
		$this->taxpayer_name = $record->taxpayer_name; 
		$this->taxpayer_regfiscal = $record->taxpayer_regfiscal; 
		$this->zip = $record->zip; 
		$this->email = $record->email; 
		$this->selected_id = $record->id; 
		$this->pageTitle = $record->pageTitle; 
		$this->componentName = $record->componentName;
    	// $this->type = $record->type;
    	// $this->value = $record->value;
    	// $this->selected_id = $record->id;
    	// $this->image = null;

    	$this->emit('show-modal', 'show modal!');
    }

	public function Update()
    {
		$rules  = [
			// 'email' => 'required|unique:clients|min:3',
			'email' => 'required|min:5',
			'taxpayer_id' => 'required|min:11',
			'taxpayer_name' => 'required',
			'taxpayer_regfiscal' => 'required',
			'zip' => 'required|min:5',

		];

		$messages = [
			'email.required' => 'Correo electrónico requerido',
			//'email.unique' => 'Ya existe el correo electronico del producto',
			'name.min' => 'El nombre del producto debe tener al menos 3 caracteres',
			'taxpayer_id.required' => 'RFC es requerido',
			'taxpayer_name.required' => 'Nombre o razon socila es requerido',
			'taxpayer_regfiscal.required' => 'Regimen de razon social es requerido',
			'zip.required' => 'Codigo Postal requerido',
		];




		$this->validate($rules, $messages);
		$product = Clients::find($this->selected_id);

		$product->update([
			'email'=>$this->email,
			'taxpayer_id'=>$this->taxpayer_id,
			'taxpayer_name'=>$this->taxpayer_name,
			'taxpayer_regfiscal'=>$this->taxpayer_regfiscal,
			'zip'=>$this->zip
		]);


	
	}


	public function resetUI() 
	{
		$this->email='';
		$this->taxpayer_id ='';
		$this->taxpayer_name=''; 
		$this->taxpayer_regfiscal=''; 
		$this->zip=''; 
		$this->regimenesFiscales=[];
		
	}

	public function Store()
	{
		$rules  = [
			//'email' => 'required|unique:clients|min:3',
			'email' => 'required|min:5',
			'taxpayer_id' => 'required|min:11',
			'taxpayer_name' => 'required',
			'taxpayer_regfiscal' => 'required',
			'zip' => 'required|min:5',

		];

		$messages = [
			'email.required' => 'Correo electrónico requerido',
			//'email.unique' => 'Ya existe el correo electronico del producto',
			'name.min' => 'El nombre del producto debe tener al menos 3 caracteres',
			'taxpayer_id.required' => 'RFC es requerido',
			'taxpayer_name.required' => 'Nombre o razon socila es requerido',
			'taxpayer_regfiscal.required' => 'Regimen de razon social es requerido',
			'zip.required' => 'Codigo Postal requerido',
		];

		$this->validate($rules, $messages);

		$client = Clients::create([
			'email'=>$this->email,
			'taxpayer_id'=>$this->taxpayer_id,
			'taxpayer_name'=>$this->taxpayer_name,
			'taxpayer_regfiscal'=>$this->taxpayer_regfiscal,
			'zip'=>$this->zip
		]);


		$this->resetUI();
		$this->emit('client-added', 'Cliente Registrado');
	}


	function esPersonaMoral($rfc)
	{
		// Verificar la longitud del RFC
		if (strlen($rfc) === 12) {
			// Los primeros dos caracteres del RFC para una persona moral son letras
			if (ctype_alpha(substr($rfc, 0, 2))) {
				return true;
			}
		}
		
		return false;
	}


	protected $listeners =['deleteRow' => 'Destroy'];

	public function Destroy(Clients $client)
	{   	

		$client->delete();

		$this->resetUI();
		$this->emit('client-deleted', 'Cliente se Elimino');

	}
}
