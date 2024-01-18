<div class="row sales layout-top-spacing">
	
	<div class="col-sm-12">
		<div class="widget widget-chart-one">
			<div class="widget-heading">
				<h4 class="card-title">
					<b>{{$componentName}} | {{$pageTitle}}</b>
				</h4>
				<ul class="tabs tab-pills">
					<ul class="tabs tab-pills">		
						@can('Clients_Create')	
						<li>
							<a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal" 
							>Agregar</a>
						</li>	
						@endcan
						<!--TODO: quit  -->
						<li>
							<a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal" 
							>Agregar</a>
						</li>	
	
					</ul>
				</ul>
			</div>
			@can('Client_Search')	
				@include('common.searchbox')
			@endcan
				<!--TODO: quit  -->
				@include('common.searchbox')

			<div class="widget-content">
				
				<div class="table-responsive">
					<table class="table table-bordered table striped mt-1">
						<thead class="text-white" style="background: #3B3F5C">
							<tr>
								<th class="table-th text-white">NOMBRE/RAZÓN SOCIAL</th>
								<th class="table-th text-white">E-MAIL</th>
								<th class="table-th text-white">RFC</th>
								<th class="table-th text-white text-center">ACTIONS</th>
							</tr>
						</thead>
						<tbody>
							@foreach($clients as $client)
							<tr>
								<td><h6>{{$client->taxpayer_name}}</h6></td>
								<td><h6>{{$client->email}}</h6></td>
								<td><h6>{{$client->taxpayer_id}}</h6></td>

								<td class="text-center">
									
										<a href="javascript:void(0)" 
										wire:click="Edit({{$client->id}})"
										class="btn btn-dark mtmobile" title="Edit">
										<i class="fas fa-edit"></i>
										</a>
								
										

														
									
										<a href="javascript:void(0)"
										onclick="Confirm('{{$client->id}}')" 
										class="btn btn-dark" title="Delete">
										<i class="fas fa-trash"></i>
										</a>
									
								

						</td>
					</tr>
					@endforeach
							
						</tbody>
					</table>
					Pagination
				</div>

			</div>


		</div>


	</div>

	@include('livewire.client.form')
</div>

<script>
	document.addEventListener('DOMContentLoaded', function(){

		window.livewire.on('show-modal', msg =>{
			$('#theModal').modal('show')
		});
		window.livewire.on('client-added', msg =>{
			$('#theModal').modal('hide')
		});
		window.livewire.on('client-updated', msg =>{
			$('#theModal').modal('hide')
		});


	});



	function Confirm(id)
	{	

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
			if(result.value){
				window.livewire.emit('deleteRow', id)
				swal.close()
			}

		})
	}


</script>