<script src="../../dist/js/gestion_social/picking_familias.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script src="../../dist/js/qrCode.min.js"></script>
<script type="text/javascript" src="../../dist/js/asignacion_picking.js"></script>
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
    </div>		
	</div>
</div>

<div class="row">
	<div class="card  mb-2" style="background-color: #ccff99;">
		<div class="card-body">
			<div class="row mb-1">
				<div class="col-lg-4 col-md-6 col-sm-12">
					<div class="d-flex align-items-center input-group-sm">
						<span class="input-group-text"><b>Programa</b></span>
						<select name="ddl_programas" id="ddl_programas" onchange="grupos()" class="form-select form-select-sm"></select>
					</div>					
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12">
					<div class="d-flex align-items-center input-group-sm">
						<span class="input-group-text"><b>Grupo</b></span>
						<select name="ddl_grupos" id="ddl_grupos" class="form-select form-select-sm"></select>
					</div>					
				</div>
				<div class="col-lg-2 col-md-6">
					<div class="row">                    
						<div class="input-group input-group-sm">
							<span class="input-group-text"><b>No. Usuarios:</b></span>						
							<input type="text" name="totalPersAten" id="totalPersAten" class="form-control form-control-sm" readonly>	
						</div>
					</div>
				</div>				
				<div class="col-lg-3 col-md-4 col-sm-12">
					<div class="input-group input-group-sm">
						<span class="input-group-text"><b>Fecha Atención:</b></span>
						<input type="date" name="fechAten" id="fechAten" class="form-control form-control-sm" value="<?php echo date('Y-m-d');?>" readonly>
					</div>
				</div>
			</div>
			<div class="row">
				
				<div class="col-lg-5 col-md-6">
					<div class="row">
						<div class="input-group input-group-sm">
							<span class="input-group-text"><b>CANTIDAD:</b></span>
							<input type="text" class="form-control form-control-sm" id="txt_total" readonly>
							<span class="input-group-text"><b>Dif:</b></span>
							<input type="text" class="form-control form-control-sm" id="txt_total_ing" name="txt_total_ing" readonly>
							<button type="button" class="btn btn-sm btn-info" style="font-size:8pt" onclick="ver_detalle()"><i class="fa fa-eye" style="font-size:8pt;"></i>Ver detalle</button>
						</div>
					</div>
					<div class="row">
						<div class="input-group input-group-sm">
							<span class="input-group-text"><b>Información Nutricional</b></span>
							<textarea name="infoNutr" id="infoNutr" rows="1" class="form-control form-control-sm">
							</textarea>
						</div>	
					</div>
					<div class="row">
						<div class="input-group input-group-sm">
							<span class="input-group-text"><b>Comentario de asignacion</b></span>
							<textarea name="infoNutr" id="infoNutr" rows="1" class="form-control form-control-sm">
							</textarea>
						</div>
					</div>
				</div>
				<div class="col-lg-7 col-md-6 col-sm-12">					       
					<div class="row"> 					
						<div class="col-sm-6">
							<div class="row text-center">
								<div class="col-sm-6">
									<label style="color:green" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion" checked  value="V" > <img src="../../img/png/smile.png"><br> Conforme</label>											
								</div>
								<div class="col-sm-6">
									<label style="color:red" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion" value="R">  <img src="../../img/png/sad.png"><br> Inconforme </label>											
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<b>Responsable de asignacion</b>
							<div class="input-group">
								<input type="text" name="txt_responsable" id="txt_responsable" value="" class="form-control form-control-sm" readonly>
								<button type="button" class="btn btn-warning btn-sm" onclick="nueva_notificacion()"><i class="fa  fa-envelope" style="font-size:8pt;"></i></button>
								
							</div>
						</div>	
					</div>            
					<div class="row mt-2"> 
						<div class="col-sm-12" id="pnl_comentario">
							<div class="input-group">
								<textarea class="form-control" rows="1" style="font-size:16px" id="txt_comentario2" name="txt_comentario2" placeholder="COMENTARIO DE PICKING"></textarea>
								<button type="button" class="btn btn-primary btn-sm" onclick="comentar()"><i class="fa fa-save"></i></button>   
								
							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>		
	</div>	
</div>
<div class="row">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-lg-5 col-md-5 col-sm-12">
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12">
							<button type="button" class="btn btn-outline-secondary w-100" onclick="show_producto();"><img
								src="../../img/png/Grupo_producto.png" /> <br> <b>Grupo producto</b></button>
						</div>
						<div class="col-lg-8 col-md-8 col-sm-12">
							<b>Grupo producto:</b>
							<select name="ddlgrupoProducto" id="ddlgrupoProducto" class="form-select form-select-sm" onchange="cargarProductosGrupo()"></select>
							<b>Codigo</b>
							<div class="input-group input-group-sm">
								<select name="txt_codigo" id="txt_codigo" class="form-select form-select-sm" onchange="validar_codigo()"></select>
								<button type="button" class="btn btn-primary btn-sm" style="font-size:8pt;" title="Escanear QR" onclick="escanear_qr()">
									<i class="fa fa-qrcode" aria-hidden="true" style="font-size:8pt;"></i> Escanear QR
								</button>
							</div>
							<input type="hidden" id="txt_id" name="txt_id">
						</div>
					</div>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-12">
					<div class="row">
						<div class="col-lg-7 col-md-7 col-sm-12">
							<b>Proveedor / Donante</b>
							<input type=""  class="form-control form-control-sm" placeholder="Proveedor / Donante" id="txt_donante" name="txt_donante">
						</div>
						<div class="col-lg-2 col-md-2 col-sm-12">
							<b>Stock:</b>
							<input type="" name="txt_stock" id="txt_stock" class="form-control form-control-sm" placeholder="0" readonly>
						</div>
						<div class="col-lg-3 col-md-4 col-sm-12">
							<b>Fecha expiracion</b>
							<input type="date" name="txt_fecha_exp" id="txt_fecha_exp" class="form-control form-control-sm" readonly>
						</div>
						<div class="col-lg-9 col-md-8 col-sm-12">
							<b>Ubicacion</b>
							<input type="" name="txt_ubicacion" id="txt_ubicacion" class="form-control form-control-sm" placeholder="Proveedor / Donante" readonly>		     							
						</div>
						<div class="col-lg-3 col-md-6 col-sm-6 p-0">
							<br> 
							<button class="btn btn-light align-items-center justify-content-center" type="button" id="btn_alto_stock" style="display:none;">
							<img id="img_alto_stock"  src="../../img/gif/alto_stock_titi.gif" style="width:32px">
							Alto Stock
						</button>
						<button class="btn btn-light align-items-center justify-content-center" type="button" id="btn_expired" style="display:none;">
							<img id="img_por_expirar" src="../../img/gif/expired_titi.gif" style="width:32px">
							<b id="btn_titulo">Por Expirar</b>
						
							
						</button>

						</div>  
					</div>
				</div>
			
			</div>
			<div class="row">
				<div class="col-sm-1 pe-0">
					<button type="button" style="width: initial;" class="btn btn-outline-secondary w-100" onclick="show_cantidad()"
						id="btn_cantidad">
						<img src="../../img/png/kilo.png" style="width: 42px;height: 42px;" />
					</button>
				</div>
				<div class="col-sm-2">
					<b>Cantidad</b>
					<input type="number" name="cant" id="cant" class="form-control form-control-sm">
				</div>
				<div class="offset-sm-6 col-sm-3 g-2 d-flex justify-content-end align-items-end">
					<button class="btn btn-primary btn-sm me-2 px-3" onclick="agregar_picking()" style="width: fit-content; height: fit-content;">Ingreso</button>
					<button class="btn btn-primary btn-sm px-3" onclick="limpiar();" style="width: fit-content; height: fit-content;">Borrar</button>
				</div>
			</div>
		</div>
	</div>		
</div>	
<div class="row">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-sm-12">		
					<div class="row">
						<div class="col-sm-12">
							<table class="table table-hover" id="tbl_picking_os">
								<thead>
									<tr>
										<th width="10%"></th>
										<th>FECHA ATENCION</th>
										<th>FECHA PICKING</th>
										<th>DESCRIPCION</th>
										<th>CODIGO</th>
										<th>USUARIO</th>
										<th>CANTIDAD (KG)</th>
									</tr>
								</thead>
								<tbody id="tbl_body"></tbody>
							</table>
						</div>
					</div>
				</div>	
			</div>
		</div>		
	</div>
	
</div>




<div id="modalDetalleCantidad" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
				<h4 class="modal-title">Ver detalle</h4>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: 300px;"  id="pnl_detalle">
                 
				               
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-success" id="btnGuardarGrupo">Aceptar</button> -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>