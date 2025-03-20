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
<div class="row mb-2">
	<div class="col-sm-6">
		<div class=" btn-group">
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
<div class="row mb-1">
	<div class="card"  style="background: antiquewhite;">
		<div class="card-body">
			<div class="row row-cols-auto">
				<label class="col-auto px-0 col-form-label"><b>Fecha de Egreso</b></label>
				<div class="col-auto">
					<input type="date" class="form-control form-control-sm" id="txt_fecha" name="txt_fecha" value="<?php echo date('Y-m-d'); ?>" readonly>		
				</div>
			</div>
			<div class="row">
				<input type="hidden" id="item_empresa" value="<?php echo $_SESSION['INGRESO']['item']; ?>">
					<div class="col-lg-2 col-md-12 col-sm-12 d-flex align-items-center justify-content-center">
						<button type="button" style="width: initial;" class="btn btn-light border border-1 w-100 p-2" onclick="modal_areas()">
							<img src="../../img/png/area_egreso.png" style="width: 55px;height: 55px;" />
						</button>
					</div>
					<div class="col-lg-6 col-md-12 col-sm-12">
						 <b>Area de egreso:</b>
						<div class="d-flex align-items-center input-group-sm">
							<select class="form-select form-select-sm" id="ddl_areas" name="ddl_areas" onchange="lista_egreso_checking()">
								<option value="">Seleccione</option>
							</select>
							<button type="button" class="btn-danger btn-xs" onclick="$('#ddl_areas').empty();lista_egreso_checking()"><i class="fa fa-times"></i></button>
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
				<div class="col-sm-12">
					<table class="table-sm table bg-light w-100"  id="tbl_asignados">
						<thead class="text-center bg-primary text-white">
							<th style="width:30px;"><b>Item</b></th>
							<th style="width:100px;"><b>Fecha de Egreso</b></th>
							<th style="width:200px;"><b>Usuario</b></th>
							<th style="width:200px ;"><b>Motivo</b></th>
							<th style="width:100px;"><b>Detalle Egreso</b></th>
							<th style="width:100px;"><b>Archivo adjunto</b></th>
							<th style="width:100px ;"><b>SubModulo gastos</b></th>
							<th style="width:100px ;"><b>Para Contabilizar</b></th>
						</thead>
						<tbody>
							<tr>
								<td></td>
								<td></td>
								<td>
									
								</td>
								<td>
									
								</td>
								<td></td>
								<td>
									
								</td>
								<td>
									
								</td>
								<td>
								</td>
							</tr>
						</tbody>
					</table>
				</div>	
				
			</div>
		</div>
		
	</div>		
</div>
	


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
            	<div class="row">            		
	            	<div class="col-sm-12">
	            		<table class="table bg-light w-100" id="txt_motivo_lista">
	            			<thead class="bg-primary text-white">
								<th></th>
	            				<th>Item</th>
	            				<th>Donante</th>
	            				<th>Producto</th>
	            				<th>Stock</th>
	            				<th>Cant Final(kg)</th>
	            				<th>Precio / Costo</th>
	            				<th>Total</th>
	            				<th>Validado</th>
	            			</thead>
	            			<tbody id="tbl_body_motivo" >
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
	            				</tr>
	            			</tbody>
	            		</table>
	            	</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border border-1" data-bs-dismiss="modal">OK</button>
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