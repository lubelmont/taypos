<div class="row sales layout-top-spacing">

	<div class="col-sm-12">
		<div class="widget widget-chart-one">
			<div class="widget-heading">
				<h4 class="card-title">
					<b>{{$componentName}} | {{$pageTitle}}</b>
				</h4>
				<ul class="tabs">
					
					<li >
						<a href="/mercadolibre/products" class="btn  mb-2 mr-2 {{ request()->is('mercadolibre/products') ? 'btn-primary' : 'bg-dark' }}"  ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg> Productos </a>
					</li>
                    <li >
						<a href="/mercadolibre/config" class="btn  mb-2 mr-2 {{ request()->is('mercadolibre/config') ? 'btn-primary' : 'bg-dark' }}" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> Configurar </a>
					</li>
					
				</ul>
			</div>
			
			<div class="widget-content">

                {{-- ACCESO A MERCADO LIBRE --}}
                <div class="col-lg-12 layout-spacing">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-header">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <h4>Accede a Mercado libre</h4>
                                </div>                 
                            </div>
                        </div>
                        @if(isset($userMercadoLibre))

                        <div class="widget-content widget-content-area">
                            <div class="w-50 mx-auto">
                                <div class="user-profile layout-spacing">
                                    <div class="widget-content widget-content-area">
                                       
                                        <div class="text-center user-info">
                                            <p class="">{{ $userMercadoLibre->first_name }} {{ $userMercadoLibre->last_name }}</p>
                                        </div>
                                        <div class="user-info-list">
        
                                            <div class="">
                                                <ul class="contacts-block list-unstyled">
                                                    <li class="contacts-block__item">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg> {{ $userMercadoLibre->nickname }}
                                                    </li>
                                                    
                                                    <li class="contacts-block__item">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>{{ $userMercadoLibre->country_id }}
                                                    </li>
                                                    <li class="contacts-block__item">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>{{ $userMercadoLibre->email }}
                                                    </li>
                                                    
                                                </ul>
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>    

                        @else
                            <div class="widget-content widget-content-area">
                                <div class="w-50 mx-auto">
                                    <a href="{{ $urlMercadoLibre }}" class="btn btn-primary mb-4 mr-2 btn-lg" role="button">Autorizar</a>
                                </div>
                            </div>
                        

                        @endif
                    </div>
                </div>
                {{-- END ACCESO A MERCADO LIBRE --}}
                
                
                {{-- CONFIGURACION FACTURA --}}
                <div class="col-lg-12 layout-spacing">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-header">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <h4>Mensaje de la compra</h4>
                                </div>                 
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">

                            <div class="row">

                                
                                <div class="col-lg-6 col-12 mx-auto">
                                    <form wire:submit.prevent="store">
                                        <div class="form-group mb-4">
                                            <label for="range-min-to-billed">Minimo a Facturar: ${{ number_format($minToBill, 2, '.', ',') }} </label>
                                            <input type="range" wire:model="minToBill" min="100" max="1000" step="100" id="range-min-to-billed" class="form-control">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="range-days-alive">Dias para facturar: {{ $daysAlive }} </label>
                                            <input type="range" wire:model="daysAlive" min="0" max="50" step="10" id="range-days-alive" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-8">
                                                    <label for="message-to-billt">Mensaje al comprador</label>
                                                    <textarea class="form-control" wire:model="messageToBill" id="message-to-bill" rows="3"></textarea>
                                                </div>
                                                <div class="col-4 ">
                                                    <p>Etiquetas</p>
                                                    <p><code>{URL}</code>Liga de facturacion</p>
                                                    <p><code>{PEDIDO}</code>Numero de pedido</p>
                                                    <p><code>{DIAS}</code>Numero de pedido</p>
                                                </div>
                                            </div>
                                                
                                            
                                        </div>
                                        <div class="form-group">
                                            <label for="message-to-billt">Metodos de pagos aceptadas </label>
                                            @foreach($metodosDePago as $item)
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" wire:model="metodosDePagoArr" id="mp_{{ $item['clave'] }}" name="mpago[]" value="{{ $item['clave'] }}">
                                                    <label class="custom-control-label"  for="mp_{{ $item['clave'] }}">{{ $item['clave'] }} - {{ $item['valor'] }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                        {{-- <p>selected: {{ var_export($metodosDePagoArr) }}</p> --}}


                                        <div class="form-group">
                                            <label for="message-to-billt">Forma de pagos aceptadas </label>
                                            @foreach($formasDePago as $item)
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" wire:model="formasDePagoArr" id="fp_{{ $item['clave'] }}" name="fpago[]" value="{{ $item['clave'] }}">
                                                    <label class="custom-control-label"  for="fp_{{ $item['clave'] }}">{{ $item['clave'] }} - {{ $item['valor'] }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                        {{-- <p>selected: {{ var_export($formasDePagoArr) }}</p> --}}


                                        <input type="submit" name="time" class="btn btn-primary" value="Guardar">
                                    </form>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>
                {{-- END CONFIGURACION FACTURA --}}
                


			</div>


		</div>


	</div>

</div>


<script>
	document.addEventListener('DOMContentLoaded', function() {

		window.livewire.on('product-added', msg => {
			$('#theModal').modal('hide')
		});
		window.livewire.on('product-updated', msg => {
			$('#theModal').modal('hide')
		});
		window.livewire.on('product-deleted', msg => {
			// noty
		});
		window.livewire.on('modal-show', msg => {
			$('#theModal').modal('show')
		});
		window.livewire.on('modal-hide', msg => {
			$('#theModal').modal('hide')
		});
		window.livewire.on('hidden.bs.modal', msg => {
			$('.er').css('display', 'none')			
		});
		$('#theModal').on('hidden.bs.modal', function(e) {
			$('.er').css('display', 'none')
		})
		$('#theModal').on('shown.bs.modal', function(e) {
			$('.product-name').focus()
		})



	});

	function Confirm(id) {

		swal({
			title: 'CONFIRMAR',
			text: '¿CONFIRMAS ELIMINAR EL REGISTRO?',
			type: 'warning',
			showCancelButton: true,
			cancelButtonText: 'Cerrar',
			cancelButtonColor: '#fff',
			confirmButtonColor: '#3B3F5C',
			confirmButtonText: 'Aceptar'
		}).then(function(result) {
			if (result.value) {
				window.livewire.emit('deleteRow', id)
				swal.close()
			}

		})
	}
</script>

@section('styles-from-view')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/users/user-profile.css') }}">
@endsection

