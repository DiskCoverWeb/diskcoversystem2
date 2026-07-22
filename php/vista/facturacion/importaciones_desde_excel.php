<?php  $nombre_modulo = $_SESSION['INGRESO'][''] ?>
<meta charset="UTF-8">
<script src="../../dist/js/xlsx.full.min.js"></script>
<script>
	console.log(ModuloActual);
	console.log(ModuloActualNombre);
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
	<div class="col-sm-2">
		<b>Fecha</b>
		<input type="date" name="" id="" class="form-control form-control-sm">
	</div>
	<div class="col-sm-2 d-none" id="pnl_cta_activos">
		<b>Cta Activos</b>
		<input type="" name="txt_Cta_Inv" id="txt_Cta_Inv" class="form-control form-control-sm">
	</div>
	<div class="col-sm-2 d-none" id="pnl_cta_patri">
		<b>Cta Patrim.</b>
		<input type="" name="txt_Cta_Pat" id="txt_Cta_Pat" class="form-control form-control-sm">
	</div>
	<div class="col-sm-3 d-none" id="pnl_dclineas">
		<b id="lbl_label2">Linea de facturacion</b>
		<select class="form-select form-select-sm" id="ddl_DCLinea">
			<option>Seleccione</option>
		</select>
	</div>
	<div class="col-sm-1">
		<b>Tipo comp.</b>		
		<select class="form-select form-select-sm" id="CTP" name="CTP">
			<option value="CD">CD</option>
		  <option value="CE">CE</option>
		  <option value="CI">CI</option>
		</select>
	</div>
	<div class="col-sm-2">
		<div class="btn-group" role="group" aria-label="Basic example">
			<button title="IMPORTAR DESDE EXCEL / CSV" id="btn_importar" class="btn btn-outline-secondary">
				<img src="../../img/png/laptop_upload.png" height="32px">
			</button>
			<button class="btn btn-outline-secondary"  data-toggle="tooltip" title="SUBIR AL SISTEMA" onclick="guardarINV()">  <img src="../../img/png/grabar.png">
			</button>
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
				<input type="file" id="fileInput" style="display:none;">
				<input type="hidden" name="txt_tipo_carga" id="txt_tipo_carga" value="1">
				<div class="col-sm-12">
					<div class="table-responsive"  id="pnl_contenido_excel">
						<table>
							
						</table>						
					</div>					
				</div>				
			</div>
			<div class="row">
				<div class="col-sm-12">
				 	<p id="fileName" style="margin-top: 20px;"></p>
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
						<table>
							
						</table>						
					</div>					
				</div>				
			</div>
		</div>
		
	</div>
</div>
<script src="../../dist/js/facturacion/importaciones_desde_excel.js"></script>
