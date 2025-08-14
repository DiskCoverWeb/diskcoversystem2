<?php $order = ''; if(isset($_GET['orden'])){$order = $_GET['orden']; } ?>
<script type="text/javascript">
orden = '<?php echo $order; ?>';
$(document).ready(function () {
	if(orden!='')
	{
    	pedidos_contratista(orden);
    }

})
</script>

<script type="text/javascript" src="../../dist/js/inventario/solicitud_material_bodega_detalle.js"></script>
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
		      <button type="button" class="btn btn-outline-secondary" title="Aprobar salida" style="display:none;" id="btn_aprobar" onclick="AprobarSolicitud()">
		        <img src="../../img/png/aprobar.png" >
		      </button>  
		      <button type="button" class="btn btn-outline-secondary" title="Generar comprobante" style="display:none;" id="btn_comprobante" onclick="GenerarComprobante()">
		           <img src="../../img/png/grabar.png" >
		      </button> 
		</div>
	</div>
</div>



	<div class="row mb-2">
		<div class="col-sm-12">
			<h2>Detalle de solicitud de material</h2>
		</div>
	</div>	
	<div class="row mb-2">		
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-sm-4">
						<b>Contratista</b><br>
						<label id="lbl_contratista"></label>
					</div>
					<div class="col-sm-2">
						<b>Numero de Orden</b><br>
						<label id="lbl_orden"></label>
					</div>						
				</div>
			</div>
		</div>
	</div>
	<div class="row mb-2">
		<div class="card">
			<div class="card-body">
				<div class="row">
						<div class="col-sm-12">		
							<table class="table table-hover" id="tbl_lista_detalle">
								<thead>
									<th>Item</th>
									<th>Codigo</th>
									<th>Producto</th>
									<th>Cantidad</th>
									<th>Centro de costos</th>
									<th>Rubro</th>
									<th></th>
								</thead>
								<tbody id="tbl_body">
									
								</tbody>
							</table>
											
						</div>
					</div>
				
			</div>			
		</div>
	</div>