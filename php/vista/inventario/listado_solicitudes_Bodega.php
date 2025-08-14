<script type="text/javascript" src="../../dist/js/inventario/listado_solicitudes_Bodega.js"></script>
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
					<b>Fecha Solicitud</b>
					<input type="date" class="form-control input-sm" name="txt_fecha" id="txt_fecha">
				</div>
				<div class="col-sm-2">
					<br>
					<button class="btn btn-primary btn-sm" onclick="pedidos_contratista()"><i class="fa fa-search"></i>Buscar</button>
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
				<div class="table-responsive">
					
					<table class="table" id="tbl_listado">
						<thead>
							<th>Item</th>
							<th>Contratista</th>
							<th>Orden</th>
							<th>Fecha Solicitud</th>
							<th>Estado</th>
						</thead>
						<tbody id="tbl_body">
							
						</tbody>
					</table>
				</div>		
				</div>
			</div>
		</div>		
	</div>
</div>
