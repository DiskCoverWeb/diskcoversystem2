<?php date_default_timezone_set('America/Guayaquil'); ?>
  <link rel="stylesheet" href="../../dist/css/style_calendar.css">
  <script src="../../dist/js/qrCode.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
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
<div class="row mb-2">
  <div class="col-sm-6">
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
  </div>  
</div>

<form id="form_correos">
<div class="row mb-1">
  <div class="card" style="background-color: antiquewhite;">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12">
           <div class="row">
            <label for="txt_fecha" class="col-lg-6 col-sm-4 px-0 col-form-label text-end"><b>Fecha de Ingreso:</b></label>
            <div class="col-lg-6 col-sm-8">
              <input type="hidden" name="txt_id" id="txt_id">
              <input type="date" readonly class="form-control form-control-sm" id="txt_fecha" name="txt_fecha" onblur="generar_codigo()" readonly>
            </div>
          </div>
          <div class="row">            
            <label for="txt_codigo" class="col-lg-6 col-sm-4 px-0 col-form-label text-end"><b>Codigo de Ingreso:</b></label>
            <div class="col-lg-6 col-sm-8">
              <input type="hidden" id="txt_codigo_p" name="txt_codigo_p" readonly>
               <div class="d-flex align-items-center input-group-sm">
                  <select class="form-select form-select-sm" id="txt_codigo" name="txt_codigo">
                    <option>Seleccione</option>
                  </select>
                   <button type="button" class="btn btn-primary btn-sm" style="font-size:8pt;" title="Escanear QR" onclick="escanear_qr()">
                  <i class="fa fa-qrcode m-0" aria-hidden="true" style="font-size:8pt;"></i>
                </button>
               </div>
            </div>
          </div>
          <div class="row">
            <label for="txt_ci" class="col-lg-6 col-sm-4  px-0 col-form-label text-end"><b>RUC / CI</b></label>
            <div class="col-lg-6 col-sm-8">
              <input type="" class="form-control form-control-sm" id="txt_ci" name="txt_ci" readonly>
            </div>
            
          </div>
          <div class="row">
            <label for="txt_donante" class="col-lg-6 col-sm-4  px-0 col-form-label text-end"><b>PROVEEDOR / DONANTE</b></label>
            <div class="col-lg-6 col-sm-8">
              <input type="" class="form-control form-control-sm" id="txt_donante" name="txt_donante" readonly>
            </div>
          </div>
          <div class="row">
            <label for="txt_tipo" class="col-lg-6 col-sm-4 px-0 col-form-label text-end"><b>TIPO DONANTE:</b></label>
            <div class="col-lg-6 col-sm-8">
              <input type="" class="form-control form-control-sm" id="txt_tipo" name="txt_tipo" readonly>
            </div>
          </div>
          <div class="row">
            <label for="txt_fecha_cla" class="col-lg-6 col-sm-4 px-0 col-form-label text-end"><b>FECHA CLASIFICACION</b></label>
            <div class="col-lg-6 col-sm-8">
              <input type="date" name="txt_fecha_cla" id="txt_fecha_cla" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm" readonly>
            </div>
          </div>          
        </div>  
        <div class="col-lg-5 col-md-6">
            <div class="row">
              <label for="ddl_alimento" class="col-lg-6 col-sm-4 px-0 col-form-label text-end"><b>ALIMENTO RECIBIDO:</b></label>
              <div class="col-lg-6 col-sm-8">
                <select class="form-select form-select-sm" id="ddl_alimento" name="ddl_alimento" disabled>
                  <option value="">Seleccione Alimento</option>
                </select> 
              </div>
            </div>
            <div class="row">
              <label for="txt_cant" class="col-lg-6 col-sm-4 px-0 col-form-label text-end"><b>CANTIDAD:</b></label>
              <div class="col-lg-6 col-sm-8">
                <div class="input-group">
                  <input type="" class="form-control" id="txt_cant" style="font-size:20px; padding: 3px;" name="txt_cant" readonly value="0">
                  <span class="input-group-text"><b>Dif</b></span>
                  <input type="" class="form-control" id="txt_faltante" style="font-size:20px; padding: 3px;" name="txt_faltante" readonly>
                </div>
              </div>
            </div>
            <div class="row" id="panel_serie">
              <label for="txt_temperatura" class="col-lg-6 col-sm-4 px-0 col-form-label text-end"><b>TEMPERATURA RECEPCION </b></label>
              <div class="col-lg-6 col-sm-8">
                
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
              <label for="txt_comentario" class="col-lg-6 col-sm-4 px-0 col-form-label text-end"><b>COMENTARIO RECEPCION:</b></label>
              <div class="col-lg-6 col-sm-8 text-center">
                <textarea id="txt_comentario" name="txt_comentario" rows="4" disabled class="form-control form-control-sm"></textarea>
              </div>
            </div>      
        </div>  
        <div class="col-lg-3">
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
          <div class="row text-center">            
              <div class="col-lg-6 col-md-6 col-sm-6">
                <label style="color:green" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion" checked  value="V" > <img src="../../img/png/smile.png"><br> Conforme</label>                     
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6">
                <label style="color:red" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion" value="R">  <img src="../../img/png/sad.png"><br> Inconforme </label>                     
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
      <div class="row">
        <div class="col-lg-5">
          <div class="row">
              <div class=" col-lg-3 col-sm-3 align-self-center">
                <button type="button" class="btn btn-light" onclick="show_producto();"><img
                  src="../../img/png/Grupo_producto.png" /> <br> <b>Grupo producto</b></button>
              </div>
              <div class=" col-lg-9 col-sm-9">
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
          </div>
        </div>
         <div class="col-lg-4">
          <div class="row">
             <div class="col-lg-3 col-md-3 col-sm-12">
              <button type="button" class="btn btn-light" onclick="show_calendar();"><img
                src="../../img/png/expiracion.png" width="45px" height="45px" /> <br></button>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-12">
              <b>Fecha Expiracion</b>
              <input type="date" name="txt_fecha_exp" id="txt_fecha_exp" class="form-control form-control-sm">
            </div>
          </div>
        </div>
         <div class="col-lg-3 col-sm-3">
           <div class="row">
              <div class="col-lg-4 col-sm-3">
                <button type="button" class="btn btn-light border border-1 w-100" onclick="show_empaque();"><img
                  src="../../img/png/empaque.png" width="45px" height="45px" /> <br></button>
                
              </div>
              <div class="col-lg-8 col-sm-9">
                <b>Tipo Empaque</b>
                <select class="form-select form-select-sm" id="txt_paquetes" name="txt_paquetes">
                  <option value="">Empaques</option>
                </select>  
              </div>
           </div>          
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="row mb-2">
            <div class="col-sm-1">
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
            <div class="col-sm-3 g-2 text-end">
              <button type="button" class="btn btn-primary btn-sm m-1" onclick="show_panel()" > AGREGAR</button>
                  <button type="button" class="btn btn-primary btn-sm m-1" onclick=" limpiar()" >Limpiar</button>
                  <input type="hidden" id="A_No" name ="A_No" value="0">
            </div>
          </div>
        </div>
      </div> 
    </div>    
  </div>
</div>
</form>

<div class="row">
  <div class="card">
    <div class="card-body">
      <div class="row" style="overflow-x:scroll;">
       <!--  <div class="col-sm-12">
          <div class=""> -->
                    
            <table class="table" id="tbl_body">
              <thead>
                <th style="width:7%;">ITEM</th>
                <th style="width:150px;">FECHA DE CLASIFICACION</th>
                <th style="width:150px;">FECHA DE EXPIRACION</th>
                <th>DESCRIPCION</th>
                <th>CANTIDAD</th>
                <th style="width:250px;">CODIGO USUARIO</th>
                <th style="width:200px;">CODIGO DE BARRAS</th>
                <th>SUCURSAL</th>
                <th style="width:100px;">QR</th>
                <th width="8%"></th>
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
        <!-- </div>
      </div>   -->    
    </div>    
  </div>  
</div>

   



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


</script>

<div id="modal_producto" class="modal fade myModalNuevoCliente" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
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
			        	 	 <table class="table table-hover">
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
              <div style="height: 100%;width: 100%;display:flex;justify-content:center;align-items:center;">
                <img src="../../img/gif/loader4.1.gif" width="20%"></div>
            </div>
           <div id="reader" style="height: 100%;width: 100%;"></div>
            <p><strong>QR Detectado:</strong> <span id="resultado"></span></p>

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

<script type="text/javascript">
 

</script>
