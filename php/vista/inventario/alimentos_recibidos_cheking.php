<?php date_default_timezone_set('America/Guayaquil'); ?>
<link rel="stylesheet" href="../../dist/css/style_calendar.css">
<script src="../../dist/js/qrCode.min.js"></script>
<script src="../../dist/js/alimentos_recibidos_cheking.js"></script>
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
			<div class="btn-group">
				<a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
								print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
					<img src="../../img/png/salire.png">
				</a>
				<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Guardar" onclick="guardar()">
					<img src="../../img/png/grabar.png">
				</button>
				<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Guardar checks temporalmente" onclick="guardar_check()">
					<img src="../../img/png/check.png">
				</button>
				 <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Imprimir etiquetas seleccionadas" onclick="imprimir_etiquetas_pdf()">
			        <img src="../../img/png/paper.png" height="32px">
			      </button>
			       <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Historial de Checking" onclick="historia_checking()">
			        <img src="../../img/png/ats.png" height="32px">
			      </button>
			</div>
    </div>
</div>

<form id="form_correos">
	<div class="row">
		<div class="card" style="background-color: antiquewhite;">
			<div class="card-body">
				<div class="row">
					<div class="col-sm-4">
						<div class="row">
							<label for="txt_fecha" class="col-sm-6 px-0 col-form-label text-end"><b>Fecha de Ingreso:</b></label>
							<div class="col-sm-6">
								<input type="hidden" name="txt_id" id="txt_id">
								<input type="date" readonly class="form-control form-control-sm" id="txt_fecha" name="txt_fecha" onblur="generar_codigo()" readonly>
							</div>
						</div>
						<div class="row">
							<label for="txt_codigo" class="col-sm-6 px-0 col-form-label text-end"><b>Codigo de Ingreso:</b></label>
							<div class="col-sm-6">
								<input type="hidden" id="txt_codigo_p" name="txt_codigo_p" readonly>
								<div class="input-group">
									<select class="form-select form-select-sm" id="txt_codigo" name="txt_codigo" onchange="cargar_pedido()">
										<option value="">Seleccione</option>
									</select>
									<button type="button" class="btn btn-primary btn-sm" style="font-size:8pt;" title="Escanear QR" onclick="escanear_qr()">
										<i class="fa fa-qrcode" aria-hidden="true" style="font-size:8pt;"></i>
									</button>
								</div>								
							</div>
						</div>				
						<div class="row">
							<label for="txt_ci" class="col-sm-6 px-0 col-form-label text-end"><b>RUC / CI</b></label>
							<div class="col-sm-6">
								<input type="" class="form-control form-control-sm" id="txt_ci" name="txt_ci" readonly>
							</div>							
						</div>
						<div class="row">
							<label for="txt_donante" class="col-sm-6 px-0 col-form-label text-end"><b>PROVEEDOR / DONANTE</b></label>
							<div class="col-sm-6">
								<input type="" class="form-control form-control-sm" id="txt_donante" name="txt_donante" readonly>
							</div>
						</div>
						<div class="row">
							<label for="txt_tipo" class="col-sm-6 px-0 col-form-label text-end"><b>TIPO DONANTE:</b></label>
							<div class="col-sm-6">
								<input type="" class="form-control form-control-sm" id="txt_tipo" name="txt_tipo" readonly>
							</div>
						</div>
						<div class="row">
							<label for="ddl_alimento" class="col-sm-6 px-0 col-form-label text-end"><b>ALIMENTO RECIBIDO:</b></label>
							<div class="col-sm-6">
								<select class="form-select form-select-sm" id="ddl_alimento" name="ddl_alimento" disabled>
									<option value="">Seleccione Alimento</option>
								</select>	
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<b>Responsable Recepcion:</b>				
								<div class="input-group input-group-sm">
									<input type="text" name="txt_responsable" id="txt_responsable" value="" class="form-control form-control-sm" readonly>
									<button type="button" class="btn btn-warning btn-sm" style="font-size:8pt;" onclick="nueva_notificacion()"><i class="fa  fa-envelope" aria-hidden="true" style="font-size:8pt;"></i></button>									
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="row">
							<label for="txt_cant" class="col-sm-6 px-0 col-form-label text-end"><b>CANTIDAD:</b></label>
							<div class="col-sm-6">
								<input type="number" class="form-control form-control-sm" id="txt_cant" name="txt_cant" readonly>	
							</div>
						</div>
						<div class="row">
							<label for="txt_comentario" class="col-sm-6 px-0 col-form-label text-end"><b>COMENTARIO DE RECEPCION:</b></label>
							<div class="col-sm-6">
								<textarea class="form-control form-control-sm" id="txt_comentario" name="txt_comentario" readonly rows="1">
								</textarea>
							</div>
						</div>
						<div class="row">
							<label for="txt_comentario_clas" class="col-sm-6 px-0 col-form-label text-end"><b>COMENTARIO DE CLASIFICACION:</b></label>
							<div class="col-sm-6">
								<textarea class="form-control form-control-sm" id="txt_comentario_clas" name="txt_comentario_clas" readonly rows="1">
								</textarea>
							</div>
						</div>
						<div class="row" id="panel_serie">
							<label for="txt_temperatura" class="col-sm-6 px-0 col-form-label text-end"><b>TEMPERATURA DE RECEPCION °C</b></label>
							<div class="col-sm-6">
								<input type="text" name="txt_temperatura" id="txt_temperatura" class="form-control form-control-sm"  readonly>
							</div>
						</div>
						<div class="row" id="panel_serie">
							<label for="btn_estado_trasporte" class="col-sm-6 px-0 col-form-label text-end"><b>ESTADO DE TRANSPORTE</b></label>
							<div class="col-sm-6 text-center">
								<button type="button" style="display: none;" id="btn_estado_trasporte" class="btn btn-primary btn-sm btn-block" onclick="ver_detalle_trasorte()"> Ver detalle <i class="fa fa-eye"></i></button>
							</div>
						</div>
						<div class="row" id="panel_serie">
							<label for="btn_estado_trasporte" class="col-sm-6 px-0 col-form-label text-end"><b>Gavetas</b></label>
							<div class="col-sm-6 text-center">
								<button type="button" style="display: none;" id="btn_estado_gavetas" class="btn btn-primary btn-sm btn-block" onclick="ver_detalle_gavetas()"> Ver detalle <i class="fa fa-eye"></i></button>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<b>Precio / Costo :</b>				
								<div class="input-group input-group-sm">
									<input type="text" name="txt_costo_all" id="txt_costo_all" class="form-control form-control-sm" value="0">
									<button type="button" class="btn btn-primary btn-sm" style="font-size:8pt;" onclick="guardar_costo_new()"> Asignar a todos</button>									
								</div>
							</div>						
						</div>
					</div>
					<div class="col-sm-3">
						<div class="row">							
							<div class="col-sm-12">
								<div class="row">
									<div class="col-sm-6">
										<label style="color:green" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion"  value="V" checked > <img src="../../img/png/smile.png"><br> Conforme </label>											
									</div>
									<div class="col-sm-6">
										<label style="color:red" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion" value="R" >  <img src="../../img/png/sad.png"><br> Inconforme</label>											
									</div>
									
								</div>
									<!-- <b>Evaluacion</b><br> -->
										
														
							</div>
							<div class="col-sm-12" id="pnl_comentario">
								<b>COMENTARIO DE CHECKING</b>									
								<textarea class="form-control form-control-sm" rows="3" id="txt_comentario2" name="txt_comentario2" style="font-size: 16px;"></textarea>
								<div class="text-end">
									<button type="button" class="btn btn-primary btn-sm" onclick="guardar_comentario_check()">Guardar</button>
								</div>								
							</div>
							<div class="col-sm-12">
								<b>Concepto comprobante</b>
								<input type="" name="txt_concepto_comp" id="txt_concepto_comp" class="form-control form-control-sm" value="">
								
							</div>
						</div>
					</div>





				</div>
			</div>
		</div>
	</div>
		<div class="row mb-2" id="pnl_factura" style="display:none;">
			
			<div class="col-sm-2">
				<label for="inputEmail3"><b>Gavetas</b></label>
				<input type="text" class="form-control form-control-sm" id="txt_serie" name="txt_serie">	
			</div>			
			<div class="col-sm-2">
				<label><b>Serie</b></label>
				<input type="text" class="form-control form-control-sm" id="txt_serie" name="txt_serie" value=".">
			</div>
			<div class="col-sm-2">
				<label><b>Factura</b></label>
				<input type="text" class="form-control form-control-sm" id="txt_factura" name="txt_factura" value=".">
			</div>
		</div>		
    
</form>

<div class="row" id="panel_add_articulos">
	<div class="card">
		<div class="card-body">
				<div class="row" style="overflow-x:scroll;"> 
					  	<table class="table table-sm"  id="tbl_body">
			        <thead>
			          <th width="10px">ITEM</th>
			          <th style="width: 40px;">FECHA DE CLASIFICACION</th>
			          <th style="width: 40px;">FECHA DE EXPIRACION</th>
			          <th width="200px">DESCRIPCION</th>
			          <th width="50px">CANTIDAD</th>
			          <th width="50px">PRECIO O COSTO</th>
			          <th width="50px">COSTO TOTAL</th>
			          <th width="200px">USUARIO</th>
			          <th width="20px">PARA CONTABILIZAR</th>				          
			          <th width="10px"></th>
			        </thead>
			        <tbody>
			        	<tr>
			        		<td></td>
			        		<td></td>
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

<div id="myModal_trans_pedido" class="modal fade myModalNuevoCliente" role="dialog">
    <div class="modal-dialog" style="background: antiquewhite;">
        <div class="modal-content">
            <div class="modal-header">
				<h4 class="modal-title" id="lbl_titulo"></h4>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
            	<div class="direct-chat-messages">	
					<ul class="list-group" id="lista_pedido"></ul>											
				</div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary" onclick="datos_cliente()">Usar Cliente</button> -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
  </div>
<br><br>


<script type="text/javascript">
	// $( document ).ready(function() {
	// 	pedidos();
  // })
	 
// 	function cargar_pedido()
//   {
//     var parametros=
//     {
//       'num_ped':$('#txt_codigo').val(),
//     }
//      $.ajax({
//       data:  {parametros:parametros},
//       url:   '../controlador/inventario/alimentos_recibidosC.php?pedido_checking=true',
//       type:  'post',
//       dataType: 'json',
//       success:  function (response) {
//         console.log(response);
//         $('#tbl_body').html(response.tabla);
//         $('#txt_cant_total').val(response.cant_total);
       
//       }
//     });
//   }

//  function calculos()
//    {
//      let cant = parseFloat($('#txt_canti').val());
//      let pre = parseFloat($('#txt_precio').val());
//      let des = 0; //parseFloat($('#txt_descto').val());
//      if($('#rbl_si').prop('checked'))
//      {
//        let subtotal = pre*cant;
//        let dscto = (subtotal*des)/100;
//        let total = (subtotal-dscto)*1.12;

//        let iva = parseFloat($('#txt_iva').val()); 
//        $('#txt_subtotal').val(subtotal-dscto);
//        $('#txt_total').val(total);
//        $('#txt_iva').val(total-(subtotal-dscto));

//      }else
//      {
//       $('#txt_iva').val(0);
//        let iva = parseFloat($('#txt_iva').val());       
//        let sub = (pre*cant);
//        let dscto = (sub*des)/100;

//        let total = (sub-dscto);
//        $('#txt_subtotal').val(sub-dscto);
//        $('#txt_total').val(total);
//      }
//    }

//    function limpiar_nuevo_producto()
//    {
//      $('#ddl_cta_inv').empty();
//      $('#ddl_cta_CV').empty();
//      $('#ddl_cta_venta').empty();
//      $('#ddl_cta_ventas_0').empty();
//      $('#ddl_cta_vnt_anti').empty();
//      $('#ddl_familia_modal').empty();
//      $('#txt_ref').val('');
//      $('#txt_nombre').val('');
//      $('#txt_max').val('');
//      $('#txt_min').val('');
//      $('#txt_reg_sanitario').val('');
//      $('#txt_cod_barras').val('');
//    }

//   function contabilizar()
//   { 
//     var parametros2 = $("#form_correos").serialize();
//        $.ajax({
//          data:  parametros2,
//          url:   '../controlador/inventario/alimentos_recibidosC.php?contabilizar=true',
//          type:  'post',
//          dataType: 'json',
//            success:  function (response) { 

//             // console.log(response);
//            if(response==1)
//            {
//               Swal.fire({
//                 type:'success',
//                 title: 'Pedido contabilizado',
//                 text :'',
//               }).then( function() {
//               			location.reload();
//                 });
//            }else
//            {
//             Swal.fire('','Algo extraño a pasado.','error');
//            }           
//          }
//        });    
//   }


//   function limpiar()
//   {
//       $("#ddl_familia").empty();
//       $("#ddl_descripcion").empty();
//       $("#ddl_pro").empty();
//   }
//   function autocoplet_pro(){
// 	  $('#ddl_producto').select2({
// 	    placeholder: 'Seleccione una producto',
// 	    ajax: {
// 	      url:   '../controlador/inventario/alimentos_recibidosC.php?autocom_pro=true',
// 	      dataType: 'json',
// 	      delay: 250,
// 	      processResults: function (data) {
// 	        // console.log(data);
// 	          return {
// 	            results: data
// 	          };
// 	      },
// 	      cache: true
// 	    }
// 	  });
//   }

// function eliminar_lin(num)
//   {
//     var ruc = $('#txt_ruc').val();
//     var cli = $('#ddl_paciente').text();
//     // console.log(cli);
//     Swal.fire({
//       title: 'Quiere eliminar este registro?',
//       text: "Esta seguro de eliminar este registro!",
//       type: 'warning',
//       showCancelButton: true,
//       confirmButtonColor: '#3085d6',
//       cancelButtonColor: '#d33',
//       confirmButtonText: 'Si'
//     }).then((result) => {
//         if (result.value) {
//             var parametros=
//             {
//               'lin':num,
//             }
//              $.ajax({
//               data:  {parametros:parametros},
//               url:   '../controlador/inventario/alimentos_recibidosC.php?lin_eli=true',
//               type:  'post',
//               dataType: 'json',
//               success:  function (response) { 
//                 if(response==1)
//                 {
//                   cargar_pedido();
//                 }
//               }
//             });
//         }
//       });
//   }

//  function notificar(usuario = false)
//  {
//  		var mensaje = $('#txt_notificar').val();
//  		var para_proceso = 1;
//  		var encargado = '';
//  		if(usuario=='usuario')
//  		{
//  			mensaje = $('#txt_texto').val();
//  			encargado = $('#txt_codigo_usu').val();

//  			para_proceso = 2;
//  		}
   
//     var parametros = {
//         'notificar':mensaje,
//         'id':$('#txt_id').val(),
//         'asunto':'De Checking a Clasificacion',
//         'pedido':$('#txt_codigo').val(),
//         'de_proceso':3,
//         'pa_proceso':para_proceso,
//         'encargado':encargado,
//     }

//     // var parametros = {
//     //     'notificar':$('#txt_texto').val(),
//     //     'asunto':'De Checking a Clasificacion',
//     //     'pedido':$('#txt_codigo').val(),
//     // }
//      $.ajax({
//       data:  {parametros,parametros},
//       url:   '../controlador/inventario/alimentos_recibidosC.php?notificar_clasificacion=true',
//       type:  'post',
//       dataType: 'json',
//       success:  function (response) { 
//         if(response==1)
//         {

//         	if(usuario=='usuario')
// 			 		{		 		
//           	cambiar_a_clasificacion('C');
// 			 		}else{
//           	cambiar_a_clasificacion();
// 			 		}
        	
//           	Swal.fire("","Notificacion enviada","success").then(function(){
//           	$('#myModal_notificar_usuario').modal('hide'); 
//           	$('#txt_texto').val('');   
//           	$('#txt_codigo_usu').val('') 
//           });
//         }
//         console.log(response);
        
//       }, 
//       error: function(xhr, textStatus, error){
//         $('#myModal_espera').modal('hide');           
//       }
//     });
//  }

//  function cambiar_a_clasificacion(T = 'R')
//  {   
//  		var t = T;
//     var parametros = {
//         'pedido':$('#txt_codigo').val(),
//         'T':t,
//     }
//      $.ajax({
//       data:  {parametros,parametros},
//       url:   '../controlador/inventario/alimentos_recibidosC.php?cambiar_a_clasificacion=true',
//       type:  'post',
//       dataType: 'json',
//       success:  function (response) { 
//         if(response==1)
//         {
//           location.reload();
//         }
//         console.log(response);
        
//       }, 
//       error: function(xhr, textStatus, error){
//         $('#myModal_espera').modal('hide');           
//       }
//     });
//  }


</script>


<div id="myModal_notificar_usuario" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="background: antiquewhite;">
            <div class="modal-header bg-primary">
                <h4 class="modal-title text-white">Notificacion</h4>
            </div>
            <div class="modal-body">
              <input type="hidden" name="txt_codigo_usu" id="txt_codigo_usu">
                <textarea class="form-control form-control-sm" rows="3" id="txt_texto" name="txt_texto" placeholder="Detalle de notificacion"></textarea>
            </div>
             <div class="modal-footer">             	
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="notificar('usuario')">Notificar</button>
            </div>
        </div>
    </div>
  </div>



<div id="modal_notificar" class="modal fade myModalNuevoCliente"  role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header bg-primary">
			  <h4 class="modal-title text-white">Notificar</h4>
              <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <textarea class="form-control form-control-sm" rows="3" style="font-size:16px" id="txt_notificar" name="txt_notificar" placeholder="Detalle de notificacion"></textarea>          
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-primary" onclick="notificar()">Notificar</button>
          </div>
      </div>
  </div>
</div>

<div id="modal_estado_transporte" class="modal fade myModalNuevoCliente"  role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header bg-primary">
			  <h4 class="modal-title text-white">Estado de trasporte</h4>
              <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
          	<div class="row">
          		<form id="form_estado_transporte">
          			<div class="col-sm-12">
          				<div class="direct-chat-messages">	
							<ul class="list-group list-group-flush" id="lista_preguntas">
								
							</ul>											
						</div>
          			</div>
          		</form>
          	</div>
          					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>

<div id="modal_estado_gavetas" class="modal fade myModalNuevoCliente"  role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header bg-primary">
			  <h4 class="modal-title text-white">Gavetas</h4>
			  <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" style="background: antiquewhite;">
          	<div class="row">
          		<form id="form_estado_transporte">
          			<div class="col-sm-12">
          				<div class="direct-chat-messages">	
							<ul class="list-group list-group-flush" id="lista_gavetas">
								
							</ul>											
						</div>
          			</div>
          		</form>
          	</div>
          					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>


<div id="modal_historial_check" class="modal fade myModalNuevoCliente"  role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header bg-primary">
			  <h4 class="modal-title text-white">Historial checking</h4>
			  <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" style="background: antiquewhite;">
          	<div class="row">
          		<div class="col-sm-12" style="overflow-y:scroll; height: 350px;">
          			<table class="table" id="tbl_historial">
	          			<thead>
	          				<th>Beneficiario</th>
	          				<th>Codigo</th>
	          				<th></th>
	          			</thead>
	          			<tbody id="tbl_historial_checking">
	          				
	          			</tbody>          			
	          		</table>          			
          		</div>
          	</div>
          					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>
