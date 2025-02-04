<?php date_default_timezone_set('America/Guayaquil'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script type="text/javascript" src="../../dist/js/alimentos_recibidos.js"></script>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?></div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
          <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
          </li>
        </ol>
      </nav>
    </div>          
</div>
<div class="row mb-2">
	<div class="col-sm-6">
		 <div class="btn-group" role="group" aria-label="Basic example">
				<a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
							  print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
					  <img src="../../img/png/salire.png">
				</a>
				<button type="button" class="btn btn-outline-secondary" title="Guardar" id="btn_guardar" onclick="guardar()" >
				  <img src="../../img/png/grabar.png">
				</button>
			<!-- 	<button type="button" class="btn btn-outline-secondary" title="Imprimir QR" onclick="imprimir_pedido()">
					<img src="../../img/png/impresora.png" height="32px">
				</button> -->
				<button type="button" class="btn btn-outline-secondary" title="Imprimir QR PDF" onclick="imprimir_pedido_pdf()">
					<img src="../../img/png/paper.png" height="32px">
				</button>		
			</div>
	</div>
</div>

<form id="form_correos">
<div class="row">
	<div class="card mb-2" style="background-color: antiquewhite;">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-4 col-md-12 col-sm-12">
							<div class="row">
									<div class="col-lg-2 col-md-2 col-sm-2 d-flex align-items-center justify-content-center">
											<button type="button" class="btn btn-light" onclick="show_proveedor();">
												<img src="../../img/png/donacion2.png"/></button>
									</div>
									<div class="col-lg-9 col-md-10 col-sm-10">
									  <b>Detalle de ingreso</b>
									  <div class="d-flex align-items-center input-group-sm">
									    <select class="form-select form-select-sm" name="txt_donante" id="txt_donante" onchange="option_select2()">
									      <option value="">Seleccione</option>
									    </select>
									    <button type="button" class="btn btn-danger btn-sm" onclick="limpiar_donante()">
									      <i class="fa fa-times m-0" style="font-size:8pt;"></i>
									    </button>
									  </div>
									  <input type="text" class="form-control form-control-sm" id="txt_tipo" name="txt_tipo" readonly>
									</div>
							</div>
						</div>	
						<div class="col-lg-4 col-md-6 col-sm-12">
							<div class="row">
			  					<div class="col-lg-2 col-md-4 col-sm-2 d-flex align-items-center justify-content-center">
								  	<button type="button" class="btn btn-light" onclick="show_temperatura()"><img src="../../img/png/temperatura2.png"></button>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-10">
									<b>TEMPERATURA DE RECEPCION:</b>	
									<div class="input-group input-group-sm mb-1">
										<input type="text" class="form-control form-control-sm" id="txt_temperatura" name="txt_temperatura" autocomplete="false" >
										<span class="input-group-text">°C</span>
									</div>
								</div>
							</div>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12">
						<div class="row">
							<label for="txt_fecha" class="col-sm-6 col-form-label"><b>Fecha de Ingreso</b></label>
							<div class="col-sm-6">
								<input type="date" class="form-control form-control-sm" id="txt_fecha" name="txt_fecha" value="<?php echo date('Y-m-d'); ?>" readonly>		
							</div>
						</div>
						<div class="row">
							<label for="txt_codigo" class="col-sm-6 col-form-label"><b>Codigo de Ingreso</b></label>
							<div class="col-sm-6">
								<input type="text" class="form-control form-control-sm px-1" id="txt_codigo" name="txt_codigo" readonly>		
							</div>
						</div>
						<div class="row">	
							<label for="txt_ci" class="col-sm-6 col-form-label"><b>RUC / CI</b></label>
							<div class="col-sm-6">
								<input type="text" class="form-control form-control-sm px-1" id="txt_ci" name="txt_ci" readonly>		
							</div>		
						</div>	
					</div>				
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-6 col-sm-12">
						<div class="row">
							<div class="col-lg-2 col-md-4 col-sm-2 d-flex align-items-center justify-content-center">
								<button type="button" class="btn btn-light" onclick="show_tipo_donacion()">
									<img src="../../img/png/tipo_donacion.png"/></button>
							</div>
							<div class="col-lg-9 col-md-8 col-sm-10">
								<b>ALIMENTO RECIBIDO:</b>
								<div class="d-flex align-items-center input-group-sm">
									<select class="form-select form-select-sm w-100" id="ddl_tipo_alimento" name="ddl_tipo_alimento" onchange="tipo_seleccion()">
										<option value="">Tipo donacion</option>
									</select>
									<button type="button" class="btn btn-danger btn-sm" style="font-size:8pt;" onclick="limpiar_alimento_rec()"><i class="fa fa-times" style="font-size:8pt;"></i></button>
								</div>
								<input type="hidden" class="form-control form-control-sm" id="ddl_alimento_text" name="ddl_alimento_text" readonly>
								<input type="hidden" class="form-control form-control-sm" id="ddl_alimento" name="ddl_alimento" readonly>
							</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6 col-sm-12">
					<div class="row">
						<div class="col-lg-2 col-md-4 col-sm-2 d-flex align-items-center justify-content-center">
							<button type="button" class="btn btn-light" id="btn_cantidad" onclick="show_cantidad()">
									<img src="../../img/png/kilo2.png">
							</button>
						</div>
						<div class="col-lg-10 col-md-8 col-sm-10">
							<b>CANTIDAD:</b>
							<input type="" class="form-control form-control-sm" id="txt_cant" name="txt_cant">	
						</div>
					</div>
						<div class="row">
							<div class="col-sm-6 p-0">
								<div class="row">
									<b>Llegaron en Transporte?</b>								
									<div class="form-check form-check-inline px-0 d-flex justify-content-center gap-1">
										<input type="radio" id="rbx_trasporte_si" value="SI" name="rbx_trasporte" onclick="mostraTransporte()" class="btn-check" autocomplete="off">
										<label class="btn btn-outline-success  btn-sm" for="rbx_trasporte_si">SI</label>

										<input type="radio" id="rbx_trasporte_no" value="NO" name="rbx_trasporte" onclick="mostraTransporte()" class="btn-check" autocomplete="off" checked>
										<label class="btn btn-outline-danger  btn-sm" for="rbx_trasporte_no">NO</label>
									</div>
								</div>
								<div class="row">
									<button type="button" class="btn btn-light border border-1" title="Guardar" style="display:none;" onclick="show_estado_transporte()" id="btn_transporte">
											<img src="../../img/png/camion.png" style="width:22px; height: :92px;">
											<br><b>Estado de Trasporte</b>
									</button>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="row">
									<b>Llegaron en gavetas?</b>
									
									<div class="form-check form-check-inline px-0 d-flex justify-content-center gap-1">
										<input type="radio" id="rbx_gaveta" value="SI" name="rbx_gaveta" onclick="show_gaveta()" class="btn-check" autocomplete="off">
										<label class="btn btn-outline-success btn-sm" for="rbx_gaveta">SI</label>

										<input type="radio" id="rbx_gaveta_no" value="NO" name="rbx_gaveta" onclick="show_gaveta()" class="btn-check" autocomplete="off" checked>
										<label class="btn btn-outline-danger btn-sm" for="rbx_gaveta_no">NO</label>
									</div>
								</div>
								<div class="row">
										<button type="button" style="display:none;" id="btn_gavetas" class="btn btn-light border border-1" title="Guardar" onclick="$('#modal_gavetas').modal('show');">
									<img src="../../img/png/gavetas.png" style="width:45px;">
								</button>				
									
								</div>
									
														
							</div>
						</div>
				</div>
				<div class="col-lg-4 col-md-12 col-sm-12">
					<div class="row" id="pnl_comentario">
						<div class="col-sm-12">
							<b>COMENTARIO GENERAL</b>
							<textarea rows="2" class="form-control form-control-sm"  id="txt_comentario" name="txt_comentario" style="font-size: 16px;" placeholder="Comentario / Observacion de Recepcion"></textarea>	
						</div>
					</div>
				</div>
			</div>		
		</div>
	</div>
</div>
<div class="row mb-1">
	<div class="card mb-2">
		<div class="card-body">
			<div class="row mb-2">
				<div class="col-sm-4" style="display:none;">
					<b>Codigo de orden</b>
					<input type="" name="txt_query" id="txt_query" class="form-control form-control-sm">
				</div>
				<div class="col-sm-2">							
					<b>Fecha Desde</b>
					<input type="date" name="txt_fecha_b" id="txt_fecha_b" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
				</div>
				<div class="col-sm-2">							
					<b>Fecha Hasta</b>
					<input type="date" name="txt_fecha_bh" id="txt_fecha_bh" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
				</div>
				<div class="col-sm-8">							
					<br>
					<button type="button" class="btn-sm btn-primary btn" id="" name="" onclick="cargar_datos();cargar_datos_procesados()"><i class="fa fa-search"></i> Buscar</button>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="nav nav-tabs" id="nav-tab" role="tablist">
						<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Registrados</button>
						<button class="nav-link" id="menu1-tab" data-bs-toggle="tab" data-bs-target="#menu1" type="button" role="tab" aria-controls="menu1" aria-selected="false">En Proceso</button>
					</div>
					<div class="tab-content bg-body" id="nav-tabContent">
						<div id="home" class="tab-pane fade show active" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
							<div class="row p-2">
								<div class="col-sm-12">
									<div class="table-responsive">
											<table class="table table-hover" id="tbl_body">
												<thead class="text-center">														
													<th>Item</th>
													<th>Codigo</th>
													<th>Fecha de ingreso</th>
													<th>Donante / Proveedor</th>
													<th>Alimento Recibido </th>
													<th>Cantidad</th>
													<th>Temperatura de ingreso</th>
													<th style="width: 8%;"></th>
												</thead>
												<tbody >
													<tr>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</div>									    		
								</div>
							</div>
						</div>
						<div id="menu1" class="tab-pane fade" role="tabpanel" aria-labelledby="menu1-tab" tabindex="0">
							<div class="row">
								<br>
								<div class="col-sm-12">
									<div class="table-responsive">
											<table class="table table-hover" id="tbl_body_procesados">
												<thead class="text-center">
													<th>Item</th>
													<th>Codigo</th>
													<th>Fecha de ingreso</th>
													<th>Donante / Proveedor</th>
													<th>Alimento Recibido </th>
													<th>Cantidad</th>
													<th>Temperatura de ingreso</th>
													<th>Proceso</th>
													<th></th>
												</thead>
												<tbody >
														<tr>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</div>									    		
								</div>
							</div>
						</div>							 
					</div>
				</div>
				
			</div>
		</div>		
	</div>
	
</div>	
</form>


<div id="modal_tipo_donacion" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header bg-primary">
			  <h4 class="modal-title text-white">Tipo de donacion</h4>
              <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
          	<div class="row text-center" id="pnl_tipo_alimento">
          		<!-- <div class="col-md-6 col-sm-6">
									<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/canasta.png"></button><br>
									<b>COMPRAS</b>
							</div>	
          		<div class="col-md-6 col-sm-6">
								<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/salvar.png"></button><br>
								<b>RESCATE</b>
							</div>
          		<div class="col-md-6 col-sm-6">
									<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/donacion3.png"></button><br>
									<b>DONACIÓN</b>
								</div>
          			<div class="col-md-6 col-sm-6">
									<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/produccion.png"></button><br>
									<b>RESCATE PRODUCCIÓN</b>
								</div> -->
          	</div>
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <!-- <button type="button" class="btn btn-primary" onclick="cambiar_cantidad()">OK</button> -->
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>


<div id="modal_proveedor" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header bg-primary">
			  <h4 class="modal-title text-white">Proveedor</h4>
              <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
	          <select class=" form-select form-select-sm" id="ddl_ingreso" name="ddl_ingreso" onchange="option_select()">
	           		<option value="">Seleccione</option>
	           </select>   					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <!-- <button type="button" class="btn btn-primary" onclick="cambiar_cantidad()">OK</button> -->
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>



<div id="modal_temperatura" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header bg-primary">
			  <h4 class="modal-title text-white">Temperatura</h4>
              <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
			<b>Temperatura</b>
			<div class="input-group">
				<input type="text" class="form-control" id="txt_temperatura2" name="txt_temperatura2" onblur="cambiar_temperatura()">
				<span class="input-group-text">°C</span>
			</div> 								
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-primary" onclick="cambiar_temperatura()">OK</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>


<div id="modal_cantidad" class="modal fade modal_cantidad" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header bg-primary">
			  <h4 class="modal-title text-white">Cantidad</h4>
              <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
          <b>Cantidad</b>
          <div class="row">
          	<div class="col-sm-12">

		          		<form id="div_cantidad">
	          		<div class="input-group input-group-sm">
			          		 	<input type="text" class=" form-control input-numero" name="txt_cantidad_add" id="txt_cantidad_add" onblur="cambiar_cantidad()" onKeyPress="return soloNumerosDecimales(event)" placeholder="0" class="form-control">

										  <span class="input-group-btn">
											<button type="button" class="btn-info btn-sm" onclick="mas_input()"><i class="fa fa-plus"></i></button>
										</span>									
								</div>	   
									</form>	 
								<input type="text" name="txt_cantidad2" id="txt_cantidad2" class="form-control" readonly>
          	</div>
          </div>   					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-primary" onclick="cerrar_modal_cant()">OK</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>


<div id="modal_editar_pedido" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header bg-primary">
			  <h4 class="modal-title text-white">Editar pedido</h4>
              <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
			  <form id="form_editar">
				<div class="row">
					<div class="col-sm-8">
						<b>Proveedor</b>
	
						<input type="hidden" id="txt_id_edi" name="txt_id_edi" class="form-control form-control-sm">
						<select class="form-select-sm form-select" id="ddl_ingreso_edi" name="ddl_ingreso_edi" onchange="option_select()">
								<option value="">Seleccione</option>
						</select>            			
					</div>
					<div class="col-sm-4">
						<b>Codigo</b>          			
					<input type="text" id="txt_codigo_edi" name="txt_codigo_edi" class="form-control form-control-sm" readonly>
					</div>
					<div class="col-sm-12">
						<b>Alimento Recibido</b> 
					<select class="form-select-sm form-select" id="ddl_tipo_alimento_edi" name="ddl_tipo_alimento_edi" onchange="option_select()">
							<option value="">Seleccione</option>
					</select>            			
					</div>
	
					<div class="col-sm-6">
					<b>Cantidad</b>	
					<input type="text" id="txt_cant_edi" name="txt_cant_edi" class="form-control form-control-sm" onblur="validar_cantidad()">
					<input type="hidden" id="txt_cant_veri" name="txt_cant_veri" class="form-control form-control-sm">
						
					</div>
	
					<div class="col-sm-6">
						<b>Temperatura</b>
						<input type="text" id="txt_temperatura_edi" name="txt_temperatura_edi" class="form-control form-control-sm">          			
					</div>
					<div class="col-sm-12">
						<b>Motivo de edicion</b>
						<textarea class="form-control form-control-sm" rows="3" id="txt_motivo_edit" name="txt_motivo_edit" ></textarea>
					</div>   
				</div>
			  </form>
          					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-primary" onclick="guardar_edicion()">Editar</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>



<script type="text/javascript">
	$(document).ready(function () {
		preguntas_transporte();
	})
	  function preguntas_transporte(){  		
	  	$.ajax({
		    type: "POST",
	      	url:   '../controlador/inventario/alimentos_recibidosC.php?preguntas_transporte=true',
		    // data:{parametros:parametros},
	        dataType:'json',
		    success: function(data)
		    {
		    	$('#lista_preguntas').html(data);		    	
		    }
		});  	
  }

  function cambiar_tipo()
  {

  	var type = $('input[name="rb_op_vehiculo"]:checked').val();  	
  	if(type==1)
  	{
  		$('#rb_furgon_lbl').css('display','none');
  		$('#rb_camion').prop('checked',true);
  		placas_auto(81);
  		$('#ddl_datos_vehiculo').css('display','block');

  	}else
  	{
  		$('#rb_furgon_lbl').css('display','inline-block');
  		$('#ddl_datos_vehiculo').css('display','none');
  	}
  }

  function placas_auto(tipo)
  {
  		var type = $('input[name="rb_op_vehiculo"]:checked').val();  	
  		if(type==0)
  		{
  			return false;
  		}else{
	  		$.ajax({
			    type: "POST",
		      	url:   '../controlador/inventario/alimentos_recibidosC.php?placas_auto=true',
			    data:{tipo:tipo},
		        dataType:'json',
			    success: function(data)
			    {
			    	op = '';
			    	data.forEach(function(item,i){
			    		op+='<option value="'+item.Cmds+'">'+item.Proceso+'</option>';
			    	})
			    	$('#ddl_datos_vehiculo').html(op);		    	
			    }
			});  
		}	
  	
  }

</script>


<div id="modal_estado_transporte" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header bg-primary">
			  <h4 class="modal-title text-white">Estado de trasporte</h4>
              <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
          	<form id="form_estado_transporte" class="">
				<div class="row">
					<div class="col-sm-12 mb-1">
						<div class="row">
							<div class="col-sm-4">
								<b>vehiculo</b>
								<br>
								<div class="form-check form-check-inline px-0 d-flex justify-content-start gap-1">
									<input type="radio" onchange="cambiar_tipo()" name="rb_op_vehiculo" id="rb_op_vehiculo_si" value="1" class="btn-check" autocomplete="off">
									<label class="btn btn-outline-success" for="rb_op_vehiculo_si">Interno</label>

									<input type="radio" onchange="cambiar_tipo()"  name="rb_op_vehiculo" id="rb_op_vehiculo_no" value="0" class="btn-check" autocomplete="off" checked>
									<label class="btn btn-outline-danger" for="rb_op_vehiculo_no">Externo</label>
								</div>
								
								<!-- <label class="label-success btn-sm btn">
									<input type="radio" class="rbl_opciones" onchange="cambiar_tipo()" name="rb_op_vehiculo" id="" value="1">  Interno
								</label>
								<label class="label-danger btn-sm btn">
									<input type="radio" class="rbl_opciones" onchange="cambiar_tipo()"  name="rb_op_vehiculo" id="" value="0" checked>  Externo
								</label>					 -->
							</div>
							<div class="col-sm-8">
									<label class="btn btn-light border border-1 btn-sm" id="rb_furgon_lbl"><img src="../../img/png/furgon.png"><br><input type="radio" name="rb_tipo_vehiculo" value="1" checked />  Furgón</label>
									<label class="btn btn-light border border-1 btn-sm"><img src="../../img/png/camion2.png"><br><input type="radio" id="rb_camion" name="rb_tipo_vehiculo" value="2" onchange="placas_auto('81')" />  Camión</label>
									<label class="btn btn-light border border-1 btn-sm"><img src="../../img/png/livianoAu.png"><br><input type="radio" id="rb_" name="rb_tipo_vehiculo" value="3" onchange="placas_auto('82')" />  Liviano</label>
									<select class="form-select form-select-sm" style="display: none;" id="ddl_datos_vehiculo" name="ddl_datos_vehiculo">
										<option>Seleccione vehiculo</option>
									</select>
								
							</div>
							
						</div>			 		
					</div>	
					<div class="col-sm-12">
							<ul class="list-group list-group-flush" id="lista_preguntas"></ul>		
					</div>
				</div>	
			</form>				
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-secondary" onclick="validar_trasporte_lleno()">Ok</button>
          </div>
      </div>
  </div>
</div>



<div id="modal_gavetas" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
      <div class="modal-content modal-sm" style="background: antiquewhite;">
          <div class="modal-header bg-primary">
			  <h4 class="modal-title text-white">Gavetas Recibidas</h4>
              <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" style="background: antiquewhite;">
          	<div class="row">
          		<div class="col-sm-12">
          			<form id="form_gavetas">
          				<table class="table table-sm">
		          			<thead>
		          				<th>Gavetas</th>
		          				<th>Recibidas</th>
		          			</thead>
		          			<tbody id="tbl_gavetas">
		          				
		          			</tbody>
		          		</table>
		          		</form>
          		</div>  		
          	</div>				
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ok</button>
          </div>
      </div>
  </div>
</div>