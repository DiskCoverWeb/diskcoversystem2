<?php date_default_timezone_set('America/Guayaquil'); ?> 
<script src="../../dist/js/egreso_alimento2.js">
</script>
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
	<div class="row row-cols-auto btn-group">
		<a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
			print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
			<img src="../../img/png/salire.png">
		</a>
		<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Guardar" onclick="guardar()">
			<img src="../../img/png/grabar.png">
		</button>
		
		
	</div>
</div>

<form id="form_correos">
	<input type="hidden" id="item_empresa" value="<?php echo $_SESSION['INGRESO']['item']; ?>">
<div class="row p-2 border-top border-3 border-secondary-subtle" style="background: antiquewhite;">					
	<div class="row border-1 mb-1 ms-2">
		<div class="row row-cols-auto">
			<label class="col-auto px-0 col-form-label"><b>Fecha de Egreso</b></label>
			<div class="col-auto">
				<input type="date" class="form-control form-control-sm" id="txt_fecha" name="txt_fecha" value="<?php echo date('Y-m-d'); ?>" readonly>		
			</div>
		</div>
		<!-- <div class="col-sm-3 col-md-3">
			<div class="form-group">
					<label class="col-sm-6 control-label" style="padding-left: 0px;">Fecha de Egreso</label>
					<div class="col-sm-6" style="padding: 0px;">
						<input type="date" class="form-control input-xs" id="txt_fecha" name="txt_fecha" value="<?php echo date('Y-m-d'); ?>" readonly>		
					</div>
			</div>		
		</div> -->
	</div>
	<div class="row border-1">
		<div class="col-sm-1 pe-0">
			<button type="button" style="width: initial;" class="btn btn-light border border-1 w-100 p-2" onclick="modal_areas()">
				<img src="../../img/png/area_egreso.png" style="width: 55px;height: 55px;" />
			</button>
		</div>
		<div class="col-sm-4">
			<b>Area de egreso:</b>
			<div class="input-group input-group-sm">
				<select class="form-select form-select-sm" id="ddl_areas" name="ddl_areas" onchange="lista_egreso_checking()">
					<option value="">Seleccione</option>
				</select>
				<span class="input-group-btn">
					<button type="button" class="btn-info btn-xs" onclick="$('#ddl_areas').empty();lista_egreso_checking()"><i class="fa fa-trash"></i></button>
				</span>
			</div>
		</div>
		<!-- <div class="col-sm-5">
			<div class="input-group">
				<div class="input-group-btn" style="padding-right:5px">
					<button type="button" class="btn btn-default btn-sm" onclick="modal_areas()">
						<img src="../../img/png/area_egreso.png" style="width: 60px;height: 60px;">
					</button>
				</div>
				<br>
				<b>Area de egreso:</b>
				<div class="input-group input-group-sm">
					<select class="form-control" id="ddl_areas" name="ddl_areas" onchange="lista_egreso_checking()">
						   <option value="">Seleccione</option>
					</select>
					<span class="input-group-btn">
						<button type="button" class="btn-info btn-xs" onclick="$('#ddl_areas').empty();lista_egreso_checking()"><i class="fa fa-trash"></i></button>
					</span>
				</div>							
			</div>				        
		</div> -->
	</div>
	<hr class="border-2 my-2">
	<div class="row">		
		<div class="table-responsive">
			<table class="table-sm table-hover table bg-light">
				<thead class="text-center bg-primary text-white">
					<th><b>Item</b></th>
					<th><b>Fecha de Egreso</b></th>
					<th><b>Usuario</b></th>
					<th><b>Motivo</b></th>
					<th><b>Detalle Egreso</b></th>
					<th><b>Archivo adjunto</b></th>
					<th><b>SubModulo gastos</b></th>
					<th><b>Para Contabilizar</b></th>
				</thead>
				<tbody id="tbl_asignados">
					<tr>
						<td>1</td>
						<td>
							2023-10-12
						</td>
						<td>
							<div class="input-group input-group-sm">
								DIEGO C
								<span class="input-group-btn">
								<button type="button" class="btn btn-light border border-1 btn-sm" onclick="modal_mensaje()">
									<img src="../../img/png/user.png" style="width:20px">
								</button>
								</span>
							</div>
						</td>
						<td>
							<div class="input-group input-group-sm">
								Refrigerio
								<span class="input-group-btn">
								<button type="button" class="btn btn-light border border-1 btn-sm" onclick="modal_motivo()">
									<img src="../../img/png/transporte_caja.png" style="width:20px">
								</button>
								</span>
							</div>
						</td>
						<td>REFRIGERIO VOLUNTARIO LUNES</td>
						<td>
							<button type="button" class="btn btn-light border border-1 btn-sm" onclick="$('#file_doc').click()">
								<img src="../../img/png/clip.png" style="width:20px">
							</button>
							<input type="file" id="file_doc" name="" style="display: none;">
						</td>
						<td>
							<select class="form-select form-select-sm">
								<option value="">Seleccione modulo</option>
							</select>
						</td>
						<td>
							<input type="radio" name="">
						</td>
					</tr>
				</tbody>
			</table>
		</div>	
	</div>
	
</div>
</form>
<br><br>



<div id="myModal_notificar_usuario" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="background: antiquewhite;">
            <div class="modal-header bg-primary">
                <h4 class="modal-title text-white">Notificacion</h4>
            </div>
            <div class="modal-body">
              <input type="hidden" name="txt_codigo" id="txt_codigo">
                <textarea class="form-control form-control-sm" rows="3" id="txt_texto" name="txt_texto" placeholder="Detalle de notificacion"></textarea>
            </div>
             <div class="modal-footer">             	
                <button type="button" class="btn btn-light border border-1" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="notificar('usuario');cambiar_a_reportado()">Notificar</button>
            </div>
        </div>
    </div>
  </div>
 <div id="modal_documento" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" style="background: antiquewhite;">
            <div class="modal-header bg-primary">
                <h4 class="modal-title text-white">Foto Evidencia</h4>
            </div>
            <div class="modal-body">
            	<div class="row">
            		<div class="col-sm-12 text-center">
            			<img src="" id="img_documento" name="img_documento" width="50%">
            		</div>
            	</div>
            </div>
             <div class="modal-footer">             	
                <button type="button" class="btn btn-light border border-1" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
  </div>


<div id="myModal_motivo" class="modal fade myModalNuevoCliente" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: antiquewhite;">
            <div class="modal-header bg-primary">
				<h4 class="modal-title text-white">Motivo</h4>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
            	<div class="col-sm-12">
            		<table class="table-sm table-hover table bg-light">
            			<thead class="text-center bg-primary text-white">
            				<th>Item</th>
            				<th>Donante</th>
            				<th>Producto</th>
            				<th>Stock</th>
            				<th>Cant Final(kg)</th>
            				<th>Precio / Costo</th>
            				<th>Total</th>
            				<th>Contabilizar</th>
            			</thead>
            			<tbody id="txt_motivo_lista">
            				<tr>
            					<td>1</td>
            					<td>Corporacion la favororita</td>
            					<td>Lacteos</td>
            					<td></td>
            					<td>10</td>
            					<td>0.14</td>
            					<td>1.40</td>
            					<td>
            						<input type="radio" name="">
            					</td>
            				</tr>
            			</tbody>
            		</table>
            	</div>           
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border border-1" data-bs-dismiss="modal">Cerrar</button>
            </div> 
        </div>
    </div>
</div>

 <script src="../../dist/js/arbol_bodegas/arbol_bodega.js"></script>

<!-- Modal -->
<div id="myModal_opciones" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="background: antiquewhite;">
      <div class="modal-header bg-primary">
		  <h4 class="modal-title text-white" id="lbl_titulo_modal"></h4>
		  <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        	<div class="row text-center" id="pnl_opciones">
          </div> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light border border-1" data-bs-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

