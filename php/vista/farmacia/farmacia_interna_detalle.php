<?php $reporte = ''; $facturar='';$numero=''; if(isset($_GET['reporte'])){$reporte=$_GET['reporte'];}if(isset($_GET['factura'])){$factura=$_GET['factura'];} if(isset($_GET['comprobante'])){$numero=$_GET['comprobante'];}?>
<script type="text/javascript">
  var reporte = '<?php echo $reporte; ?>';      
  var factura = '<?php echo $factura; ?>';    
  var numero = '<?php echo $numero; ?>';  
</script>
<script src="../../dist/js/farmacia/farmacia_interna_detalle.js"></script>
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

<div class="row row-cols-auto  mb-2">
  <div class="btn-group">
    <a  href="../vista/inicio.php?mod=28&acc=farmacia_interna" class="btn btn-outline-secondary" title="Regresar">
        <img src="../../img/png/back.png">
    </a>
    <button type="button" class="btn btn-outline-secondary" title="Generar pdf" onclick="reporte_excel()"><img src="../../img/png/table_excel.png"></button>
 	</div>
</div>
<h2 id="titulo"></h2>
<div class="row mb-2">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-sm-4">
					<!-- <div class=""> -->
						<b id="tipo_cli">Proveedor:</b>
						<p id="proveedor"></p>
					<!-- </div>					 -->
				</div>
				<div class="col-sm-3">
					<!-- <div class=""> -->
						<b>Fecha:</b>
						<p id="lbl_fecha"></p>
					<!-- </div>					 -->
				</div>
				<div class="col-sm-3">
					<!-- <div class=""> -->
						<b id="ti">Numero de Factura:</b>
						<p id="lbl_fac"></p>
					<!-- </div>					 -->
				</div>
				<div class="col-sm-2">
					<!-- <div class=""> -->
						<b id="ti_to">Total Factura:</b>
						<p id="lbl_total"></p>
					<!-- </div>					 -->
				</div>
			</div>			
		</div>
  </div>
</div>
<div class="row">
		<div class="card">
      <div class="card-body">
      <div class="col-sm-12">
				<table class="table">
					<thead>
						<th>Fecha</th>
						<th>Producto</th>
						<th>Cantidad</th>
						<th> PVP</th>
						<th>Total</th>
					</thead>
					<tbody id="detalle_ingresos">
						
					</tbody>
				</table>				
			</div>
		</div>
	</div>
</div>