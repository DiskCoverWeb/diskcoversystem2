<?php date_default_timezone_set('America/Guayaquil'); ?>
  <link rel="stylesheet" href="../../dist/css/arbol_bodegas/reset.min.css">  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
  <link rel="stylesheet" href="../../dist/css/arbol_bodegas/arbol_bodega.css">
  <script src="../../dist/js/arbol_bodegas/prefixfree.min.js"></script>
  <script src="../../dist/js/qrCode.min.js"></script>
  <script type="text/javascript" src="../../dist/js/almacenamiento_bodega.js"></script>

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
			<!-- <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Guardar" onclick="guardar()">
				<img src="../../img/png/grabar.png">
			</button> -->
  	</div>
  </div>
</div>
<form id="form_correos" class="mb-2">
	<div class="row">
		<div class="card" style="background-color: antiquewhite;">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">					
						<b>Fecha de Ingreso:</b>
						<input type="hidden" name="txt_id" id="txt_id">
						<input type="date" class="form-control form-control-sm" id="txt_fecha" name="txt_fecha" readonly>		
					</div>						
					<div class="col-lg-3 col-md-9 col-sm-6 col-xs-12">
						<b>Codigo de Ingreso:</b>
						<input type="hidden" class="form-control form-control-sm" id="txt_codigo_p" name="txt_codigo_p" readonly>
						<div class="d-flex align-items-center input-group-sm">
							<select class="form-select form-select-sm" id="txt_codigo" name="txt_codigo">
								<option value="">Seleccione</option>
							</select>
							<button type="button" class="btn btn-primary btn-sm" style="font-size:8pt;" title="Escanear QR" onclick="escanear_qr('ingreso')">
								<i class="fa fa-qrcode" aria-hidden="true" style="font-size:8pt;"></i>
							</button>
						</div>
					</div>
					<div class="col-lg-3 col-md-6 col-sm-7">
						<b>PROVEEDOR / DONANTE</b>								
						<input type="" class="form-control form-control-sm" id="txt_donante" name="txt_donante" readonly>
					</div>
					<div class="col-lg-2 col-md-6 col-sm-5 text-right">
						<b>CANTIDAD:</b>
						<input type="" class="form-control form-control-sm" id="txt_cant" name="txt_cant" readonly>	
					</div>
					<div class="col-lg-2 col-md-6 col-sm-6 text-right">
						<b>FECHA EXPIRACION:</b>
						<input type="date" class="form-control form-control-sm" id="txt_fecha_exp" name="txt_fecha_exp" readonly>	
					</div>						
				</div>
				<div class="row">
					<div class="col-lg-5 col-md-6 col-sm-6">
						<b>Tipo de Empaque</b>
						<select class="form-select form-select-sm" id="txt_paquetes" name="txt_paquetes" disabled>
							<option value="">Seleccione Empaque</option>
						</select>
					</div>
					<div class="col-sm-7 text-end" id="pnl_alertas">
						<button class="btn btn-light border border-1" type="button" id="btn_alto_stock" style="display:none;">
							<img id="img_alto_stock"  src="../../img/gif/alto_stock_titi.gif" style="width:48px">
							<br>
							Alto Stock
						</button>
						<button class="btn btn-light border border-1" type="button" id="btn_expired" style="display:none;">
							<b id="btn_titulo">Por Expirar</b><br>
							<img id="img_por_expirar" src="../../img/gif/expired_titi.gif" style="width:48px">
							
						</button>
					</div>
				</div>				
			</div>			
		</div>
	</div>
	<div class="row">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-12 border-top border-3 border-primary">
							<h3 class="fs-3">Articulos de pedido</h3>
							<div class="direct-chat-messages">	
								<ul class="list-group list-group-flush" id="lista_pedido"></ul>											
							</div>			
					</div>
					<div class="col-lg-7 col-md-7 col-sm-12 border-top border-3 border-success">
						<div class="row">									
							<div class="col-sm-9">
								<b>Codigo de lugar</b>
								<div class="d-flex align-items-center input-group-sm">
									<input type="" class="form-control form-control-sm" id="txt_cod_lugar" name="txt_cod_lugar" onblur="buscar_ruta();productos_asignados()">	
									<button type="button" class="btn btn-info btn-sm" style="font-size:8pt;" onclick="abrir_modal_bodegas()"><i class="fa fa-sitemap" style="font-size:8pt;"></i></button>
									<button type="button" class="btn btn-primary btn-sm" style="font-size:8pt;" title="Escanear QR" onclick="escanear_qr('lugar')">
										<i class="fa fa-qrcode" style="font-size:8pt;" aria-hidden="true"></i>
									</button>									
								</div>
							</div>
							<div class="col-sm-3 d-flex align-items-end justify-content-end">
								<br>
								<button class="btn btn-primary" type="button" onclick="asignar_bodega()"><i class="fa fa-map-marker"></i>  Asignar</button>											
							</div>							
						</div>
						<div class="row">
							<h3 class="box-title fs-3" id="txt_bodega_title">Ruta: </h3>
							<input type="hidden" class="form-control input-xs" id="txt_cod_bodega" name="txt_cod_bodega" readonly>
						</div>
						<div class="row mt-3">
							<div class="col-sm-12">
								<b>Contenido de bodega</b>
								<table class="table-sm table-hover table bg-light">
									<thead class="text-center bg-primary text-white">
										<th><b>Producto</b></th>
										<th><b>Stock</b></th>
										<th><b>Ruta</b></th>
										<th></th>
									</thead>
									<tbody id="tbl_asignados">
										<tr>
											<td colspan="3">Productos asignados</td>
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
</form>

<div id="modal_qr_escaner" class="modal fade"  role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header bg-primary">
			  <h4 class="modal-title text-white">Escanear QR</h4>
              <button type="button" class="btn-close" aria-label="Close" onclick="cerrarCamara()"></button>
          </div>
          <div class="modal-body">
          	<div class="row">
          		<div class="col-sm-12">
          			<select class="form-select" id="ddl_camaras" name="ddl_camaras" onchange="cambiarCamara()">
          				<option value="0">Camara 1</option>          				
          				<option value="1">Camara 2</option>           				
          			</select>          			
          		</div>          		
          	</div>
            <div id="qrescaner_carga">
              <div style="height: 100%;width: 100%;display:flex;justify-content:center;align-items:center;"><img src="../../img/gif/loader4.1.gif" width="20%"></div>
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

<div id="myModal_arbol_bodegas" class="modal fade myModalNuevoCliente" role="dialog"  data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" style="background: antiquewhite;">
            <div class="modal-header bg-primary">
				<h4 class="modal-title text-white">Seleccion manual de bodegas</h4>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenido_prov">
            		<ul class="tree_bod" id="arbol_bodegas">
								</ul>               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="cargar_bodegas();" data-bs-dismiss="modal">OK</button>
            </div> 
        </div>
    </div>
  </div>

 <script src="../../dist/js/arbol_bodegas/arbol_bodega.js"></script>
