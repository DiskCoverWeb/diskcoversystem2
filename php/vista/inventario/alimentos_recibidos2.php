<?php date_default_timezone_set('America/Guayaquil'); ?>
  <link rel="stylesheet" href="../../dist/css/style_calendar.css">
  <script src="../../dist/js/qrCode.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script type="text/javascript" src="../../dist/js/alimentos_recibidos2.js"></script>
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
<div class="row row-cols-auto gx-3 pb-2 d-flex align-items-center ps-2">
  <div class="row row-cols-auto">
    <div class="btn-group">
      <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
          print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
            <img src="../../img/png/salire.png">
      </a>
      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Guardar" onclick="guardar()" id="btn_guardar">
        <img src="../../img/png/grabar.png">
      </button>
      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Generar PDF" onclick="reporte_pdf()">
        <img src="../../img/png/pdf.png" height="32px">
      </button>
      
      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Imprimir etiquetas" onclick="imprimir_etiquetas()">
        <img src="../../img/png/paper.png" height="32px">
      </button>
      
      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Imprimir etiquetas PDF" onclick="imprimir_etiquetas_pdf()">
        <img src="../../img/png/impresora.png" height="32px">
      </button>
    </div>
    <div class="col-2 col-md-2 col-sm-2" style="display:none;" id="pnl_notificacion">
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">

					<li class="dropdown messages-menu">
						<button class="btn btn-danger dropdown-toggle" title="Guardar"  data-bs-toggle="dropdown" aria-expanded="false">
								<img src="../../img/gif/notificacion.gif" style="width:32px;height: 32px;">
						</button>  	
						<ul class="dropdown-menu">
							<li class="header">tienes <b id="cant_mensajes">0</b> mensajes</li>
							<li>
								<ul class="menu" id="pnl_mensajes">
									
								</ul>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
  </div>
</div>
<form id="form_correos" class="mb-2">
    <div class="row p-2 border-top border-3 border-secondary-subtle" style="background-color: antiquewhite;">
      <div class="row border-1">
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
                <select class="form-select form-select-sm" id="txt_codigo" name="txt_codigo">
                  <option>Seleccione</option>
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
            <label for="txt_fecha_cla" class="col-sm-6 px-0 col-form-label text-end"><b>FECHA CLASIFICACION</b></label>
            <div class="col-sm-6">
              <input type="date" name="txt_fecha_cla" id="txt_fecha_cla" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm" readonly>
            </div>

            
          </div>
        </div>
        <div class="col-sm-5">
          <div class="row">
            <label for="ddl_alimento" class="col-sm-6 px-0 col-form-label text-end"><b>ALIMENTO RECIBIDO:</b></label>
            <div class="col-sm-6">
              <select class="form-select form-select-sm" id="ddl_alimento" name="ddl_alimento" disabled>
                <option value="">Seleccione Alimento</option>
              </select>	
            </div>
          </div>
          <div class="row">
            <label for="txt_cant" class="col-sm-6 px-0 col-form-label text-end"><b>CANTIDAD:</b></label>
            <div class="col-sm-6">
              <div class="input-group">
                <input type="" class="form-control" id="txt_cant" style="font-size:20px; padding: 3px;" name="txt_cant" readonly value="0">
                <span class="input-group-text"><b>Dif</b></span>
                <input type="" class="form-control" id="txt_faltante" style="font-size:20px; padding: 3px;" name="txt_faltante" readonly>
              </div>
            </div>

            
          </div>
          <div class="row" id="panel_serie">
            <label for="txt_temperatura" class="col-sm-6 px-0 col-form-label text-end"><b>TEMPERATURA RECEPCION </b></label>
            <div class="col-sm-6">
              
              <div class="input-group input-group-sm">
                <input type="text" name="txt_temperatura" id="txt_temperatura" class="form-control form-control-sm"  readonly>
                <span class="input-group-text">°C</span>                  
              </div>
            </div>
          </div>
          <div class="row" id="panel_serie" style="display:none;">
            <div class="col-sm-6 text-end">
								<b>ESTADO TRANSPORTE</b>
            </div>
            <div class="col-sm-6 text-center">
              <img src="" id="img_estado">
            </div>
          </div>
          <div class="row">
            <label for="txt_comentario" class="col-sm-6 px-0 col-form-label text-end"><b>COMENTARIO RECEPCION:</b></label>
            <div class="col-sm-6 text-center">
              <textarea id="txt_comentario" name="txt_comentario" rows="4" disabled class="form-control form-control-sm"></textarea>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="row">
            <div class="col-sm-12">
                <b>Responsable recepcion</b>
                <div class="input-group input-group-sm">
                    <input type="text" name="txt_responsable" id="txt_responsable" value="" class="form-control form-control-sm" readonly>
                    <button type="button" class="btn btn-warning btn-sm" style="font-size:8pt;" onclick="nueva_notificacion()"><i class="fa  fa-envelope" aria-hidden="true" style="font-size:8pt;"></i></button>
                    
                </div>
            </div>
            
          </div>
          <hr class="my-1">
          <div class="row">
            <div class="col-sm-12">
              <div class="row text-center">
                <div class="col-sm-6">
                  <label style="color:green" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion" checked  value="V" > <img src="../../img/png/smile.png"><br> Conforme</label>											
                </div>
                <div class="col-sm-6">
                  <label style="color:red" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion" value="R">  <img src="../../img/png/sad.png"><br> Inconforme </label>											
                </div>
                
              </div>
            </div>
          </div>
          <div class="row"> 
            <div class="col-sm-12" id="pnl_comentario">
              <b style="font-size: 10pt;">COMENTARIO DE CLASIFICACION</b>
              <div class="input-group">
                <textarea class="form-control form-control-sm" id="txt_comentario2" name="txt_comentario2" style="font-size:16px" readonly rows="3" placeholder="comentario general de clasificacion...">
                </textarea>
                <button type="button" class="btn btn-primary btn-sm" onclick="comentar()"><i class="fa fa-save" style="font-size:8pt;"></i></button>   
              </div>
              
            </div>
          </div>
        </div>
		</div>
		<hr class="border-2 my-2">
		<div class="row border-1">
      <div class="col-sm-2 pe-0 align-self-center">
        <button type="button" class="btn btn-light border border-1 w-100" onclick="show_producto();"><img
          src="../../img/png/Grupo_producto.png" /> <br> <b>Grupo producto</b></button>
        
      </div>
      <div class="col-sm-3">
        <b>Producto</b>
        <div class="input-group input-group-sm mb-1">
          <select class="form-select form-select-sm" name="txt_producto" id="txt_producto">
            <option value="">Seleccione producto</option>
          </select>
          <button type="button" class="btn btn-danger btn-sm" style="font-size:8pt;" onclick="limpiar_reciclaje()"><i class="fa fa-times" style="font-size:8pt;"></i></button>
        </div>
        <div class="row">
          <label for="txt_grupo" class="col-sm-6 px-0 col-form-label text-end"><b>Grupo</b></label>
          <div class="col-sm-6">
            <input type="text" name="txt_grupo" id="txt_grupo" class="form-control form-control-sm" readonly>
            <input type="hidden" name="txt_TipoSubMod" id="txt_TipoSubMod" class="form-control" readonly>
            <input type="hidden" name="txt_primera_vez" id="txt_primera_vez" class="form-control" readonly value="0">
          </div>
        </div>
        
		  </div>
      <!-- <div class="col-sm-5 col-md-5">
        <div class="row">
          <div class="col-sm-6 col-md-8">
            <b>Producto</b>
            <div class="input-group" style="display:flex;" id="pnl_normal">
                <select class="form-control input-xs" name="txt_producto" id="txt_producto">
                  <option value="">Seleccione producto</option>
                </select>
                <span class="input-group-btn">
                  <button type="button" class="btn btn-default btn-xs btn-flat" onclick="limpiar_reciclaje()"><i class="fa fa-close"></i></button>
                </span>
              </div>
              <div class="row">
                <div class="col-sm-6 text-right">
                    <b>Grupo</b>
                </div>
                  <div class="col-sm-6">
                    <input type="text" name="txt_grupo" id="txt_grupo" class="form-control input-xs" readonly>
                    <input type="hidden" name="txt_TipoSubMod" id="txt_TipoSubMod" class="form-control" readonly>
                    <input type="hidden" name="txt_primera_vez" id="txt_primera_vez" class="form-control" readonly value="0">
                </div>                   
              </div>
          </div>              
        </div>
      </div>           -->
      <div class="col-sm-1 pe-0 align-self-center">
        <button type="button" class="btn btn-light border border-1 w-100" onclick="show_calendar();"><img
          src="../../img/png/expiracion.png" width="45px" height="45px" /> <br></button>
        
      </div>
      <div class="col-sm-2 align-self-center">
        <b>Fecha Expiracion</b>
        <input type="date" name="txt_fecha_exp" id="txt_fecha_exp" class="form-control form-control-sm">
      </div>
      <!-- <div class="col-sm-3 col-md-3">
        <div class="row">
          <div class="col-sm-4 col-md-4">
              <button type="button" style="width: initial;" class="btn btn-default" onclick="show_calendar()"><img src="../../img/png/expiracion.png" width="45px"; height="45px" />
                <br>

              </button> 
          </div>
          <div class="col-sm-8 col-md-8">
            <b>Fecha Expiracion</b>
            <input type="date" name="txt_fecha_exp" id="txt_fecha_exp" class="form-control input-xs">
          </div>              
        </div>
      </div> -->
      <div class="col-sm-1 pe-0 align-self-center">
        <button type="button" class="btn btn-light border border-1 w-100" onclick="show_empaque();"><img
          src="../../img/png/empaque.png" width="45px" height="45px" /> <br></button>
        
      </div>
      <div class="col-sm-3 align-self-center">
        <b>Tipo Empaque</b>
        <select class="form-select form-select-sm" id="txt_paquetes" name="txt_paquetes">
          <option value="">Seleccione Empaque</option>
        </select>  
      </div>
      <!-- <div class="col-sm-4">
        <div class="col-sm-3">
          <button type="button" class="btn btn-default" onclick="show_empaque()">
            <img src="../../img/png/empaque.png" width="45px" height="45px">
            <br>
          </button>              
        </div>
        <div class="col-sm-9">
            <b>Tipo Empaque</b>
          <select class="form-control input-xs" id="txt_paquetes" name="txt_paquetes">
            <option value="">Seleccione Empaque</option>
          </select>              
        </div>
      </div> -->
		</div>
    <div class="row mb-2 border-1">
      <div class="col-sm-1 pe-0">
        <button type="button" style="width: initial;" class="btn btn-light border border-1 w-100" onclick="show_cantidad()"
          id="btn_cantidad">
          <img src="../../img/png/kilo.png" style="width: 42px;height: 42px;" />
        </button>
      </div>
      <div class="col-sm-2">
        <b>Cantidad</b>
        <input type="" name="txt_cantidad" id="txt_cantidad" class="form-control form-control-sm" value="0">	
        <input type="hidden" name="txt_costo" id="txt_costo" readonly class="form-control form-control-sm">	
        <input type="hidden" name="txt_cta_inv" id="txt_cta_inv" readonly class="form-control form-control-sm">	
        <input type="hidden" name="txt_contra_cta" id="txt_contra_cta" readonly class="form-control form-control-sm">										
      </div>
      <div class="col-sm-2">
        <b>Unidad</b>
        <input type="" name="txt_unidad" id="txt_unidad" readonly class="form-control form-control-sm">	
        <input type="hidden" id="txt_cant_total" name ="txt_cant_total" value="0">
      </div>
      <div class="col-sm-4">
        <div class="row" style="display: none;" id="pnl_sucursal">
          <div class="col-sm-3">
            <button type="button" class="btn btn-light border border-1" onclick="show_sucursal()"><img src="../../img/png/sucursal.png" />
            </button>
          </div>
          <div class="col-sm-9">
            <b>SUCURSAL</b>
            <select class="form-select form-select-sm" id="ddl_sucursales" name="ddl_sucursales">
              <option value="">Seleccione sucursal</option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-sm-3 g-2 d-flex justify-content-end align-items-center">
        <button class="btn btn-primary btn-sm me-2 px-3" onclick="agregar_picking()" style="width: fit-content; height: fit-content;">Ingreso</button>
        <button class="btn btn-primary btn-sm px-3" onclick="limpiar();" style="width: fit-content; height: fit-content;">Borrar</button>
      </div>
    </div>			
  </div>
</form>



<script type="text/javascript">
	$( document ).ready(function() {
		// cargar_pedido();
    // cargar_productos();
    autocoplet_pro();
    autocoplet_producto();
    autocoplet_pro2();
  })

  function valida_cantidad_ingreso()
  {

  }

	function cargar_pedido()
  {
    var parametros=
    {
      'num_ped':$('#txt_codigo').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?pedido=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {
        console.log(response);
        $('#tbl_body').html(response.tabla);
        var diff = parseFloat(response.cant_total)-parseFloat(response.reciclaje);
        if(diff < 0)
        {
        	diff = diff*(-1);
        }
        $('#txt_primera_vez').val(response.primera_vez);

        var ingresados_en_pedidos =  $('#txt_cant_total_pedido').val();
        var ingresados_en_kardex =  $('#txt_cant_total').val(diff);
        var total_pedido = $('#txt_cant').val();
        var faltantes = parseFloat(total_pedido)-parseFloat(response.cant_total);


        $('#txt_faltante').val(faltantes.toFixed(2));
      }
    });
  }

  function cargar_pedido2()
  {
    var parametros=
    {
      'num_ped':$('#txt_codigo').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?pedido_trans=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {
        console.log(response);
        $('#tbl_body_pedido').html(response.tabla);
        $('#txt_cant_total_pedido').val(response.cant_total);   
        $('#txt_total_lin_pedido').val(response.num_lin);       
      }
    });
  }

 function calculos()
   {
     let cant = parseFloat($('#txt_canti').val());
     let pre = parseFloat($('#txt_precio').val());
     let des = 0; //parseFloat($('#txt_descto').val());
     if($('#rbl_si').prop('checked'))
     {
       let subtotal = pre*cant;
       let dscto = (subtotal*des)/100;
       let total = (subtotal-dscto)*1.12;

       let iva = parseFloat($('#txt_iva').val()); 
       $('#txt_subtotal').val(subtotal-dscto);
       $('#txt_total').val(total);
       $('#txt_iva').val(total-(subtotal-dscto));

     }else
     {
      $('#txt_iva').val(0);
       let iva = parseFloat($('#txt_iva').val());       
       let sub = (pre*cant);
       let dscto = (sub*des)/100;

       let total = (sub-dscto);
       $('#txt_subtotal').val(sub-dscto);
       $('#txt_total').val(total);
     }
   }

   function limpiar_nuevo_producto()
   {
     $('#ddl_cta_inv').empty();
     $('#ddl_cta_CV').empty();
     $('#ddl_cta_venta').empty();
     $('#ddl_cta_ventas_0').empty();
     $('#ddl_cta_vnt_anti').empty();
     $('#ddl_familia_modal').empty();
     $('#txt_ref').val('');
     $('#txt_nombre').val('');
     $('#txt_max').val('');
     $('#txt_min').val('');
     $('#txt_reg_sanitario').val('');
     $('#txt_cod_barras').val('');
   }

  function agregar()
  {
  	var reci = $('#txt_TipoSubMod').val();
  	
  	var parametros = $("#form_add_producto").serialize();    
    var parametros2 = $("#form_correos").serialize();
       $.ajax({
         data:  parametros2+'&txt_referencia='+$('#txt_referencia').val()+'&txt_referencia2='+$('#txt_referencia2').val(),
         url:   '../controlador/inventario/alimentos_recibidosC.php?guardar_recibido=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 

            // console.log(response);
           if(response.resp==1)
           {
            $('#txt_pedido').val(response.ped);
              Swal.fire({
                type:'success',
                title: 'Agregado a pedido',
                text :'',
              }).then( function() {              		
                   cargar_pedido(); 
                   $('#txt_paquetes').val('');
                   $('#ddl_sucursales').val('');             		
              });

            // Swal.fire('','Agregado a pedido.','success');
            limpiar();
            // location.reload();
           }else
           {
            Swal.fire('','Algo extraño a pasado.','error');
           }           
         }
       });    

    
  }


  function limpiar()
  {
  	 var tpd = $('#txt_TipoSubMod').val();
  	  $("#txt_cantidad").val('');
      $("#txt_unidad").val('');
      $("#txt_grupo").val('');
      $("#txt_fecha_exp").val('');


  	 if(tpd=='R')
  	 {

      $("#txt_referencia2").val('');
      $("#txt_producto2").val(null).trigger('change');
      $("#ddl_producto2").val(null).trigger('change');
      $("#txt_producto2").prop('disabled',false);

      $("#txt_producto").prop('disabled',false);      
      $("#txt_producto").val(null).trigger('change');

  	 }else{
      $("#txt_producto").val('');
    
      $("#txt_cantidad2").val('');
      $("#txt_referencia").val('');
      $("#ddl_producto").val(null).trigger('change');
      $("#txt_producto").prop('disabled',false);      
      $("#txt_producto").val(null).trigger('change');
      
    }
  }


  function autocoplet_pro(){
	  $('#ddl_producto').select2({
	    placeholder: 'Seleccione una producto',
	    ajax: {
	      url:   '../controlador/inventario/alimentos_recibidosC.php?autocom_pro=true',
	      dataType: 'json',
	      delay: 250,
	      processResults: function (data) {
	        // console.log(data);
	          return {
	            results: data
	          };
	      },
	      cache: true
	    }
	  });
  }

   function autocoplet_producto(){
	  $('#txt_producto').select2({
	    placeholder: 'Seleccione una producto',
	    ajax: {
	      url:   '../controlador/inventario/alimentos_recibidosC.php?autocom_pro=true',
	      dataType: 'json',
	      delay: 250,
	      processResults: function (data) {
	        // console.log(data);
	          return {
	            results: data
	          };
	      },
	      cache: true
	    }
	  });
  }

  function autocoplet_pro2(){
	  $('#ddl_producto2').select2({
	    placeholder: 'Seleccione una producto',
	    ajax: {
	      url:   '../controlador/inventario/alimentos_recibidosC.php?autocom_pro2=true',
	      dataType: 'json',
	      delay: 250,
	      processResults: function (data) {
	        // console.log(data);
	          return {
	            results: data
	          };
	      },
	      cache: true
	    }
	  });
  }

function eliminar_lin(num,tipo)
{
  pedido = $('#txt_codigo').val();
  // console.log(cli);

	  if(tipo=='R')
	  {
		  Swal.fire({
		    title: 'Quiere eliminar este registro?',
		    text: "Al eliminar este registro se borrara tambien los productos ligados a este item!",
		    type: 'warning',
		    showCancelButton: true,
		    confirmButtonColor: '#3085d6',
		    cancelButtonColor: '#d33',
		    confirmButtonText: 'Si'
		  }).then((result) => {
		      if (result.value) {
		      	eliminar_all_pedido(pedido);
		      	eliminar_linea_trans(num,'1');
		      	$('#txt_TipoSubMod').val('.');		
		      	$('#btn_cantidad').prop('disabled',false);		
		      	limpiar();
		      	limpiar_reciclaje();         
		      }
		    });
		}else{
		eliminar_linea_trans(num);
	}
}

function eliminar_linea_trans(num,tpd=0)
{
	 var parametros=
    {
      'lin':num,
      'TPD':tpd,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?lin_eli=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
          cargar_pedido();
          cargar_pedido2();
        }
      }
    });
}


function eliminar_all_pedido(pedido)
{
		var parametros=
      {
        'pedido':pedido,
      }
       $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/inventario/alimentos_recibidosC.php?eli_all_pedido=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
          if(response==1)
          {
            cargar_pedido2();
          }
        }
      });

}

  function eliminar_lin_pedido(num)
  {
    var ruc = $('#txt_ruc').val();
    var cli = $('#ddl_paciente').text();
    // console.log(cli);
    Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
            var parametros=
            {
              'lin':num,
            }
             $.ajax({
              data:  {parametros:parametros},
              url:   '../controlador/inventario/alimentos_recibidosC.php?lin_eli_pedido=true',
              type:  'post',
              dataType: 'json',
              success:  function (response) { 
                if(response==1)
                {
                  cargar_pedido2();
                }
              }
            });
        }
      });
  }

  function terminar_pedido()
  {
  	pro = $('#txt_producto option:selected').text();
  	var id = $('#txt_id_linea_pedido').val(); 
  	
  	if($('#txt_cant_total_pedido').val()==0 || $('#txt_cant_total_pedido').val()=='')
  	{
  		Swal.fire('Agrege productos para terminar','','info')
  		return false;
  	}

  	primera_vez = $('#txt_primera_vez').val();

  	if(primera_vez==0 || primera_vez==''){
  		$('#txt_cantidad').val($('#txt_cant_total_pedido').val());
  		// $('#btn_cantidad').prop('disabled',true);
  		if($('#txt_cantidad').val()==0 || $('#txt_cantidad').val()=='')
	  	{
	  		Swal.fire('No se olvide de agregar: '+pro,'','info')
	  		return false;
	  	}
	  	show_panel();
	  	limpiar();

  			 $('#modal_producto_2').modal('hide');
  	}else{

  			 $('#modal_producto_2').modal('hide');
  			  Swal.fire('Cantidad Modificada automaticamente','','success');

  	 total = $('#txt_cant_total_pedido').val();
  	
  	  var parametros=
        {
        	'txt_codigo':$('#txt_codigo').val(),
          'total_cantidad':$('#txt_cant_total_pedido').val(),
          'id':id,
          'producto': $('#txt_producto option:selected').text(),
        }
         $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/inventario/alimentos_recibidosC.php?actualizar_trans_kardex=true',
          type:  'post',
          dataType: 'json',
          success:  function (response) { 
            if(response==1)
            {
              cargar_pedido();
              cargar_pedido2();
            }
          }
        });

  	 	    limpiar();
  	}






  }

</script>



<div class="row" id="panel_add_articulos">
	<div class="col-sm-12">
		<div class="box">
			<div class="card_body" style="background:antiquewhite;">
					<div class="row"> 
						  <div  class="col-sm-12">
						  	<table class="table table-hover" style="width:100%">
				        <thead class="text-center">
				          <th style="width:7%;">ITEM</th>
				          <th>FECHA DE CLASIFICACION</th>
				          <th>FECHA DE EXPIRACION</th>
				          <th>DESCRIPCION</th>
				          <th>CANTIDAD</th>
				          <th>CODIGO USUARIO</th>
                  <th>CODIGO DE BARRAS</th>
                  <th>SUCURSAL</th>
				          <th>QR</th>
				          <th width="8%"></th>
				        </thead>
				        <tbody id="tbl_body"></tbody>
				      </table>

						  </div>
						</div>

			</div> 			
		</div>
	</div>
</div>
<br><br>
<div id="modal_producto" class="modal fade myModalNuevoCliente" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header">
            <h4 class="modal-title">Producto</h4>
            <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
          	<div class="row">
		           <div class="col-md-3">
		              <b>Referencia:</b>
		              <input type="text" name="txt_referencia" id="txt_referencia" class="form-control input-sm" readonly="">
		           </div>
		           <div class="col-sm-9">
		              <b>Producto:</b><br>
		              <select class="form-control" id="ddl_producto" name="ddl_producto"style="width: 100%;">
		                <option value="">Seleccione una producto</option>
		              </select>
		           </div>        
		        </div>  
					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <!-- <button type="button" class="btn btn-primary" onclick="datos_cliente()">Usar Cliente</button> -->
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>


<div id="modal_producto_2" class="modal fade myModalNuevoCliente" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header">
            <h4 class="modal-title" id="txt_titulo_mod"></h4>
            <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
          	<div class="row">
		          <!--  <div class="col-sm-3">
		              <b>Referencia:</b>
		              <input type="text" name="txt_referencia2" id="txt_referencia2" class="form-control input-sm" readonly="">
		           </div> -->
		           <div class="col-sm-9">
		              <b>Producto:</b><br>
		              <select class="form-control" id="ddl_producto2" name="ddl_producto2"style="width: 100%;">
		                <option value="">Seleccione una producto</option>
		              </select>
		           </div>
		           <div class="col-sm-3">
                <b>Cantidad</b>
                <div class="input-group">
                    <input type="text" name="txt_cantidad_pedido" id="txt_cantidad_pedido" class="form-control input-sm" />
                    <input type="hidden" name="txt_id_linea_pedido" id="txt_id_linea_pedido">
                    <span class="input-group-addon" id="lbl_unidad">-</span>
                </div>

			           
		           </div> 
		         </div>
		         <div class="row">
		           
		           <div class="col-sm-12 text-right">
		           	<br>
		           		<button type="button" class="btn btn-primary btn-sm" onclick="guardar_pedido()"><i class="bx bx-plus"></i>Agregar</button>
		           </div>
		         </div>
		         <div class="row">
		           <div class="col-sm-12">
		           	<br>
		           	 <input type="hidden" id="txt_cant_total_pedido" name ="txt_cant_total_pedido" value="0">
                 <input type="hidden" id="txt_total_lin_pedido" name ="txt_total_lin_pedido" value="0">
			        	 <div class="table-responsive">
			        	 	 <table class="table">
			        	 	 	<thead>		        	 	 		
					        	 	 	<th>N°</th>
					        	 	 	<th>Producto</th>
					        	 	 	<th>Cantidad</th>
					        	 	 	<th></th>
			        	 	 	</thead>
			        	 	 	<tbody id="tbl_body_pedido">
			        	 	 		<tr><td colspan="4">Sin registros</td></tr>			        	 	 		
			        	 	 	</tbody>
			        	 	 </table>
			        	 </div>
		           </div>       
		        </div>					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <!-- <button type="button" class="btn btn-primary" onclick="datos_cliente()">Usar Cliente</button> -->
              <button type="button" class="btn btn-primary" onclick="terminar_pedido()">Terminar</button>
              <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>

<div id="modal_cantidad" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header">
            <h4 class="modal-title">Cantidad</h4>
            <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
          <b>Cantidad</b>
          <input type="" name="txt_cantidad2" id="txt_cantidad2" class="form-control" placeholder="0" onblur="cambiar_cantidad()">        					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-primary" onclick="cambiar_cantidad()">OK</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>

<div id="modal_sucursal" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header">
            <h4 class="modal-title">Sucursal</h4>
            <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
          <b>Sucursal</b>
	         <select class="form-control input-sm" id="ddl_sucursales2" name="ddl_sucursales2" onchange="cambiar_sucursal()">
	         		<option value="">Seleccione Sucursal</option>
	         </select>        					
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-primary" onclick="cambiar_sucursal()">OK</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>

<div id="modal_empaque" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header">
            <h4 class="modal-title">Tipo empaque</h4>
            <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row text-center" id="pnl_tipo_empaque">
            </div>                       
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-primary" onclick="cambiar_empaque()">OK</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>

<div id="modal_qr_escaner" class="modal fade"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header">
            <h4 class="modal-title">Escanear QR</h4>
            <button type="button" class="btn-close" aria-label="Close" onclick="cerrarCamara()"></button>
          </div>
          <div class="modal-body">
            <div id="qrescaner_carga">
              <div style="height: 100%;width: 100%;display:flex;justify-content:center;align-items:center;"><img src="../../img/gif/loader4.1.gif" width="20%"></div>
            </div>
		  	    <canvas hidden="" id="qr-canvas" class="img-fluid" style="height: 100%;width: 100%;"></canvas>
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-danger" onclick="cerrarCamara()">Cerrar</button>
          </div>
      </div>
  </div>
</div>

<div id="modal_calendar" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" style="background: antiquewhite;">
            <div class="modal-header">
              <h4 class="modal-title">Fecha de vencimiento</h4>
              <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">


            	<div id="app">
							  <div class="container">
							    <div class="controls">
							      <button @click="date = prevMonth">{{ prevMonth.toLocaleString({ month: 'long' }) }}</button>
							      <input type="date" v-model="dateString">
							      <button @click="date = nextMonth">{{ nextMonth.toLocaleString({ month: 'long' }) }}</button>
							    </div>
							    <calendar v-model="date"></calendar>
							  </div>
							</div>

							<script type="text/x-template" id="calendar-template">
							  <div class="calendar">
							    <table>
							      <tr>
							        <th v-for="day in days">{{ day }}</th>
							      </tr>
							      <tr v-for="(weekDays, week) in calendar">
							        <td v-for="(date, dayInWeek) in weekDays" :class="classes(date)" @click="select(date)">
							          {{ date.day }}
							        </td>
							      </tr>
							    </table>
							  </div>
							</script>   
							<script src='../../dist/js/vue.js'></script>
							<script src='../../dist/js/luxon.js'></script>   

            </div>
            <div class="modal-footer" style="background-color:antiquewhite;">
                <!-- <button type="button" class="btn btn-primary" onclick="datos_cliente()">Usar Cliente</button> -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
  </div>

<script src="../../dist/js/script_calendar.js"></script>


<div id="modal_notificar" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header">
            <h4 class="modal-title">Notificar</h4>
            <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <textarea class="form-control form-control-sm" rows="3" style="font-size:16px" id="txt_notificar" name="txt_notificar" placeholder="Notificacion"></textarea>          
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-primary" onclick="notificar()">Notificar</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>