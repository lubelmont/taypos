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

class MLProductsController extends Component
{

    public $pageTitle;
    public $componentName;
    public $categoryid;


    public function mount()
	{
		$this->pageTitle = 'Listado';
		$this->componentName = 'Productos';
		$this->categoryid = 'Elegir';
	}

    public function render()
	{   
        $products = [];

        return view('livewire.mercadolibre.component', [
			'data' => $products
		])
			->extends('layouts.theme.app')
			->section('content');

    }

}