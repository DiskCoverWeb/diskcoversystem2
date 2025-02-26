<?php $MascaraCodigoK = (isset($_SESSION['INGRESO']['Formato_Inventario']))?$_SESSION['INGRESO']['Formato_Inventario']:MascaraCodigoK;
?>
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
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Descargar PDF" id="imprimir_pdf">
				<img src="../../img/png/pdf.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Descargar excel" id="imprimir_excel">
				<img src="../../img/png/table_excel.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Consultar" onclick="ListarCatalogoInventarioJS();">
				<img src="../../img/png/consultar.png">
			</button>
			
		</div>
	</div>  
</div>	

<div class="row mb-2 div_filtro">
	<form id="FormCatalogoCtas">
		<div class="row">
			<div class="col-8 col-lg-5">
				<div class="row">
					<div class="col-6">
						<b>Cuenta inicial:</b>
						<br>
						<input type="text" name="MBoxCtaI" id="MBoxCtaI" class="form-control form-control-sm" placeholder="<?php 
						echo $MascaraCodigoK ?>">
					</div>
					<div class="col-6">
						<b> Cuenta final:</b>
						<br>
						<input type="text" name="MBoxCtaF" id="MBoxCtaF" class="form-control form-control-sm" placeholder="<?php 
						echo $MascaraCodigoK ?>"> 
					</div>       	
				</div>             	
			</div>
			<div class="col-4">
				<br>
				<div class="form-check">
					<input class="form-check-input" type="checkbox" name="CheqPM" id="CheqPM" value="1" onchange="">
					<label class="form-check-label" for="CheqPM">
						<b>Solo Productos de Movimiento</b>
					</label>
				</div>
				<input type="hidden" id="heightDisponible" name="heightDisponible" value="100">            			
				<!-- <div class="row">
					<div class="col-sm-12">
						<label class="radio-inline"><input type="checkbox" name="CheqPM" id="CheqPM" value="1" onchange=""></label>  
					</div>             		          		
				</div> -->
			</div>
		</div>
	</form>
</div>

<div class="row">
	<div class="col-sm-12">
		<table id="tablaProductoCatalogo" class="table table-sm table-striped table-hover">
			<thead>
				<tr>
					<th>TC</th>
					<th>Codigo_Inv</th>
					<th>Producto</th>
					<th>PVP</th>
					<th>Codigo_Barra</th>
					<th>Cta_Inventario</th>
					<th>Unidad</th>
					<th>Cantidad</th>
					<th>Cta_Costo_Venta</th>
					<th>Cta_Ventas</th>
					<th>Cta_Ventas_0</th>
					<th>Cta_Ventas_Ant</th>
					<th>Cta_Venta_Anticipada</th>
					<th>IVA</th>
					<th>INV</th>
					<th>Codigo_IESS</th>
					<th>Codigo_RES</th>
					<th>Marca</th>
					<th>Reg_Sanitario</th>
					<th>Ayuda</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
<br><br>

<script type="text/javascript" src="../../dist/js/inventario/Catalogo.js">
	
</script>
