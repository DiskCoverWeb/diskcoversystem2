<?php date_default_timezone_set('America/Guayaquil'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script type="text/javascript" src="../../dist/js/inventario/lista_envio_solicitud_proveedor.js"></script>
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
			<!--	<button type="button" class="btn btn-outline-secondary" title="Guardar" id="btn_guardar" onclick="grabar_solicitud()" >
				  <img src="../../img/png/grabar.png">
				</button>
			 	<button type="button" class="btn btn-outline-secondary" title="Imprimir QR" onclick="imprimir_pedido()">
					<img src="../../img/png/impresora.png" height="32px">
				</button> -->
				<!-- <button type="button" class="btn btn-outline-secondary" title="Imprimir QR PDF" onclick="imprimir_pedido_pdf()">
					<img src="../../img/png/paper.png" height="32px">
				</button>	 -->	
			</div>
	</div>
</div>
<div class="row mb-2">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-sm-4">
					<b>Contratista</b>
					<input type="text" class="form-control input-sm" name="txt_query" id="txt_query">
				</div>
				<div class="col-sm-2">
					<b>Fecha</b>
					<input type="date" class="form-control input-sm" name="txt_fecha" id="txt_fecha">
				</div>
				<div class="col-sm-2">
					<br>
					<button type="button" onclick="pedidos_contratista()" class="btn btn-primary btn-sm"><i class="fa fa-search"></i>Buscar</button>
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
					<table class="table table-hover" id="tbl_lista_solicitud">
						<thead>
							<th>Item</th>
							<th>Contratista</th>
							<th>Orden</th>
							<th>Fecha Solicitud</th>
							<th>Presupuesto</th>
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
