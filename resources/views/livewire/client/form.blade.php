@include('common.modalHead')


<div class="row">


	<div class="col-sm-12 col-md-8">
		<div class="form-group">
			<label >Nombre / Razón Social</label>
			<input type="text" wire:model.lazy="taxpayer_name" 
			class="form-control product-name" placeholder="ej: Pedro Guerra" autofocus >
			@error('taxpayer_name') <span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>

	<div class="col-sm-12 col-md-4">
		<div class="form-group">
			<label >RFC</label>
			<input type="text" wire:model.lazy="taxpayer_id" 
			class="form-control product-name" placeholder="ej: XAXX010101000" >
			@error('taxpayer_id') <span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>

	
	<div class="col-sm-12 col-md-8">

		<div class="form-group mr-5">
			<select wire:model ="taxpayer_regfiscal" class="form-control">
				<option value="Elegir" selected>== Regimen Fiscal ==</option>
				
				@foreach($regimenesFiscales as $regimenFiscal)
					<option value="{{$regimenFiscal->c_RegimenFiscal}}" >{{$regimenFiscal->c_RegimenFiscal}} - {{$regimenFiscal->descripcion}}</option>
				@endforeach
			</select>
	</div>

	<div class="col-sm-12 col-md-8">
		<div class="form-group">
			<label >Correo electronico</label>
			<input type="text" wire:model.lazy="email" 
			class="form-control product-name" placeholder="ej: nombre@dominio.com" >
			@error('email') <span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>

	<div class="col-sm-12 col-md-4">
		<div class="form-group">
			<label >Código Postal</label>
			<input type="text" wire:model.lazy="zip" 
			class="form-control product-name" placeholder="ej: 52975" >
			@error('zip') <span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>


	




</div>



@include('common.modalFooter')