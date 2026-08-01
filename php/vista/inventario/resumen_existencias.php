<?php date_default_timezone_set('America/Guayaquil'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script type="text/javascript" src="../../dist/js/inventario/resumen_existencias.js"></script>
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
	<div class="col-sm-5">
		 <div class="btn-group" role="group" aria-label="Basic example">
				<a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
							  print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
					  <img src="../../img/png/salire.png">
				</a>
				<button type="button" class="btn btn-outline-secondary" title="Guardar" id="btn_guardar" onclick="Stock(true)" >
				  <img src="../../img/png/list.png" height="32px">
				</button>
					<button type="button" class="btn btn-outline-secondary" title="Resumen de existencia agrupado" onclick="imprimir_pedido()">
					<img src="../../img/png/archivero2.png" height="32px">
				</button>
			 	<button type="button" class="btn btn-outline-secondary" title="Resumen de existencias por lotes" onclick="Resumen_Lote()">
					<img src="../../img/png/es.png" height="32px">
				</button>
					<button type="button" class="btn btn-outline-secondary" title="Resumen en codigo de barras" onclick="Resumen_Barras()">
					<img src="../../img/png/barcode.png" height="32px">
				</button>
				<button type="button" class="btn btn-outline-secondary" title="Imprimir QR PDF" onclick="imprimir_pedido_pdf()">
					<img src="../../img/png/paper.png" height="32px">
				</button>			
				<button type="button" class="btn btn-outline-secondary" title="Resumen en codigo QR" onclick="Resumen_QR()">
					<img src="../../img/png/qr_code.png" height="32px">
				</button>
				<button type="button" class="btn btn-outline-secondary" title="Imprimir QR PDF" onclick="imprimir_pedido_pdf()">
					<img src="../../img/png/excel2.png" height="32px">
				</button>
			</div>
	</div>
	<div class="col-sm-7">
		
	</div>
</div>
<div class="row">
	<div class="card">
			<div class="card-body">
				<div class="row">
						<div class="col-sm-2">
						
						</div>
						<div class="col-sm-10">
							<div class="row">

								<div class="col-sm-3">
									<div class="input-group input-group-sm mb-3"> <span class="input-group-text" id="basic-addon3">Fecha Inicial</span>
										<input type="date" class="form-control form-control-sm" id="txt_inicial" value="<?php echo(date('Y-m-d')); ?>" aria-describedby="basic-addon3">
									</div>	
								</div>
								<div class="col-sm-3">
									<div class="input-group input-group-sm mb-3"> <span class="input-group-text" id="basic-addon3">Fecha Final</span>
										<input type="date" class="form-control form-control-sm" id="txt_final" value="<?php echo(date('Y-m-d')); ?>" aria-describedby="basic-addon3">
									</div>	
								</div>
								<div class="col-sm-3">						
									<div class="input-group input-group-sm mb-3"> <span class="input-group-text" id="basic-addon3"><label>
										<input type="checkbox" name="CheqMonto" id="CheqMonto"> MONTE</label></span>
										<input type="text" name="TxtMonto" id="TxtMonto" class="form-control form-control-sm">
									</div>	
								</div>
								<div class="col-sm-3">
									<label><input type="checkbox" name="CheqExist" id="CheqExist"> Lista Catalogo Completo</label>						
								</div>
								
							</div>
						</div>							
				</div>
				<div class="row">
				 	<div class="col-sm-2">
				 		<label><input type="checkbox" name="CheqBod" id="CheqBod"> BODEGA</label>
				 	</div>
				 	<div class="col-lg-4">
					 	 		<select class="form-select form-select-sm" id="DCBodega" name="DCBodega">
					 	 			<option value="">seleccione</option>
					 	 		</select>
				 	</div>			 
				 	<div class="col-sm-2">
				 		<label><input type="checkbox" name="CheqGrupo" id="CheqGrupo"> TIPO GRUPO</label>
				 	</div>
				 	<div class="col-lg-4">
					 	 		<select class="form-select form-select-sm" id="DCTInv" name="DCTInv">
					 	 			<option value="">seleccione</option>
					 	 		</select>
				 	 </div>			 	
			 </div>		
			 <div class="row">
			 		<div class="col-sm-2">
				 		<label><input type="checkbox" name="CheqProducto" id="CheqProducto"> PRODUCTO</label>
				 	</div>
			 	 	<div class="col-lg-10">
			 	 		<div class="row">
			 	 			 <div class="col-sm-5">
									<label class="p-1"><input type="radio" onchange="DCTipoBusqueda()" name="rbx_producto" value="4" id="rbx_producto" checked> Productos</label>
									<label class="p-1"><input type="radio" onchange="DCTipoBusqueda()" name="rbx_producto" value="2" id="rbx_barras"> Codigo Barras</label>
									<label class="p-1"><input type="radio" onchange="DCTipoBusqueda()" name="rbx_producto" value="1" id="rbx_marca"> Marca</label>
									<label class="p-1"><input type="radio" onchange="DCTipoBusqueda()" name="rbx_producto" value="3" id="rbx_lote"> Lote</label>
			 	 			 </div>			 	 			
			 	 			 <div class="col-sm-7">
			 	 			 	<select class="form-select form-select-sm" id="DCTipoBusqueda" name="DCTipoBusqueda">
					 	 			<option value="">seleccione</option>
					 	 		</select>				 	 			 	
			 	 			 </div>
			 	 		</div>
			 	 	</div>			 	
			 </div>		
			 <div class="row">
			 	<div class="col-sm-2">
				 		<label><input type="checkbox" name=""> TIPO DE CTA</label>
				 	</div>
			 	 <div class="col-lg-8">
			 	 		<div class="row">
			 	 				<div class="col-sm-4">
										<label class="p-1"><input type="radio" value="1" name="rbx_tipo_cta" id="rbx_inventario" onchange="DCCtaInv()" checked> Inventario</label>
										<label class="p-1"><input type="radio" value="0" name="rbx_tipo_cta" id="rbx_costo" onchange="DCCtaInv()"> Costo</label>
			 	 				</div>
			 	 				<div class="col-sm-8">
			 	 					<select class="form-select form-select-sm" id="DCCtaInv"  name="DCCtaInv">
						 	 			<option value="">seleccione</option>
						 	 		</select>	
			 	 				</div>
			 	 			
			 	 		</div>
			 	 </div>			 	
			 </div>		
			 <div class="row">
			 	<div class="col-sm-2">
				 		<label><input type="checkbox" name=""> POR SUBMODULO</label>
				</div>
			 	<div class="col-lg-7">
			 			<div class="row">
			 					<div class="col-sm-6">
			 							<label class="p-1"><input type="radio" value="1" name="rbx_subModulo" id="rbx_cc" onchange="DCSubModulo()" checked> Centro de costos</label>
			 							<label class="p-1"><input type="radio" value="0" name="rbx_subModulo" id="rbx_prove" onchange="DCSubModulo()"> CxP / Proveedores</label>
			 					</div>			 				
			 					<div class="col-sm-6">
										<select class="form-select form-select-sm" id="DCSubModulo" name="DCSubModulo">
							 	 			<option value="">seleccione</option>
							 	 		</select>	
			 					</div>
			 			</div>
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
			 		 		<table class="table table-hover" id="tbl_existencias">
			 		 			<thead>
			 		 				<th>TC</th>
			 		 				<th>Codigo_Inv</th>
			 		 				<th>Producto</th>
			 		 				<th>Unidad</th>
			 		 				<th>Stock_Anterior</th>
			 		 				<th>Entradas</th>
			 		 				<th>Salidas</th>
			 		 				<th>Stock_Actual</th>
			 		 				<th>Promedio</th>
			 		 				<th>PVP</th>
			 		 				<th>Valor_Total</th>
			 		 			</thead>
			 		 		</table>			 		 		
			 		 	</div>			 		 	
			 		 </div>			 		
			 	</div>
		</div>		
	</div>	
</div>

