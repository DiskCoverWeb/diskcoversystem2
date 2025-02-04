<?php date_default_timezone_set('America/Guayaquil'); ?>
  <link rel="stylesheet" href="../../dist/css/arbol_bodegas/reset.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
  <link rel="stylesheet" href="../../dist/css/arbol_bodegas/arbol_bodega.css">
  <script src="../../dist/js/arbol_bodegas/prefixfree.min.js"></script>
  <script src="../../dist/js/qrCode.min.js"></script>
 
<script type="text/javascript" src="../../dist/js/reubicar.js">
  
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

<form id="form_correos">
	<div class="row mb-2">
		<div class="card" style="background: antiquewhite;">
			<div class="card-body">
				<div class="row">
					<div class="col-sm-4">
						<b class="fw-semibold">Buscar Bodega</b>
						
						<div class="input-group input-group-sm">
							<input type="" name="" class="form-control form-control-sm" id="txt_bodega" name="txt_bodega" placeholder="Buscar Bodega" onblur="lista_stock_ubicado()">
							<button type="button" class="btn btn-info btn-sm" style="font-size: 8pt;" onclick="abrir_modal_bodegas()"><i class="fa fa-sitemap" style="font-size: 8pt;"></i></button>
							<button type="button" class="btn btn-primary btn-sm" style="font-size: 8pt;" title="Escanear QR" onclick="escanear_qr()"><i class="fa fa-qrcode" style="font-size: 8pt;"></i></button>
						</div>
					</div>
					<div class="col-sm-4">
						<b class="fw-semibold">Buscar Articulo</b>
						<input type="" name="" class="form-control form-control-sm" id="txt_cod_barras" name="txt_cod_barras" placeholder="Buscar" onblur="lista_stock_ubicado()">
					</div>
					<div class="col-sm-4 text-end">
						<br>
						<button type="button" class="btn btn-primary btn-sm" onclick="lista_stock_ubicado()"><i class="fa fa-search" style="font-size:8pt;"></i> Buscar</button>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<h3 class="h3" id="txt_bodega_title">Ruta: </h3>
					</div>					
				</div>				
			</div>			
		</div>		
	</div>	
	<div class="row mb-2">
		<div class="card">
			<div class="card-body">
				<div class="col-sm-12">
					<b class="fw-semibold">Contenido De bodega</b>
					<table class="table table-sm " id="tbl_asignados">
						<thead class="text-center bg-primary text-white">
							<th>Codigo</th>
							<th><b>Producto</b></th>
							<th><b>Stock</b></th>
							<th>Codigo bodega</th>
							<th><b>Ruta</b></th>
							<th></th>
						</thead>
						<tbody>
							<tr>
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
</form>



<div id="myModal_cambiar_bodegas" class="modal fade myModalNuevoCliente" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" style="background: antiquewhite;">
            <div class="modal-header bg-primary">
				<h4 class="modal-title text-white">Seleccion manual de bodegas</h4>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenido_prov">
        		<div class="row">
        			<div class="col-sm-12">
        				<div class="input-group input-group-sm mb-2">
							<input type="hidden" name="txt_id_inv" id="txt_id_inv">
        					<input type="text" id="txt_cod_lugar" name="txt_cod_lugar" class="form-control form-control-sm" placeholder="Nueva ruta" onblur="buscar_ruta()">        				
							<button type="button" class="btn btn-info btn-sm" onclick="abrir_modal_bodegas(2)" style="font-size:8pt"><i class="fa fa-sitemap" style="font-size:8pt"></i></button>
						</div>						
        				<label id="txt_bodega_title2">Ruta:</label>

        			</div>
        		</div>             
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border border-1" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="Guardar_bodega()">Guardar</button>
            </div> 
        </div>
    </div>
  </div>




<div id="myModal_arbol_bodegas" class="modal fade myModalNuevoCliente" role="dialog" data-keyboard="false" data-backdrop="static">
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
                <button type="button" class="btn btn-success" onclick="cargar_bodegas();lista_stock_ubicado();" data-bs-dismiss="modal">OK</button>
            </div> 
        </div>
    </div>
  </div>

  <div id="myModal_arbol_bodegas2" class="modal fade myModalNuevoCliente" role="dialog"  data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" style="background: antiquewhite;">
            <div class="modal-header bg-primary">
				<h4 class="modal-title text-white">Seleccion manual de bodegas</h4>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenido_prov">
            		<ul class="tree_bod" id="arbol_bodegas2">
								</ul>               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="cargar_bodegas();lista_stock_ubicado();" data-bs-dismiss="modal">OK</button>
            </div> 
        </div>
    </div>
  </div>

  <div id="modal_qr_escaner" class="modal fade"  role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header bg-primary">
			  <h4 class="modal-title text-white">Escanear QR</h4>
              <button type="button" class="btn-close" aria-label="Close" onclick="cerrarCamara()"></button>
          </div>
          <div class="modal-body">
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

 <script src="../../dist/js/arbol_bodegas/arbol_bodega.js"></script>

