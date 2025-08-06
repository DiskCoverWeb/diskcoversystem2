<!--
    AUTOR DE RUTINA : Javier farinango
    MODIFICADO POR : Javier Farinango
    FECHA CREACION : 16/02/2024
    FECHA MODIFICACION :10/02/2025
    DESCIPCION : Interfaz de modulo Gestion Social/Registro Beneficiario
 -->
<script src="../../dist/js/inventario/reporte_constructora_tiempos.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		orden();
		meses();
		Calcularsemanas()
  	});	
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
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);	print_r($ruta[0]); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
                <img src="../../img/png/salire.png">
            </a>
            <button type="button" class="btn btn-outline-secondary" id="imprimir_pdf" title="Descargar PDF" onclick="imprimir_pdf()">
	            <img src="../../img/png/pdf.png">
	          </button>      
            <button type="button" class="btn btn-outline-secondary" id="imprimir_excel" title="Descargar Excel" onclick="imprimir_excel()">
            <img src="../../img/png/table_excel.png">
          </button>  
        </div>
    </div>
</div>

<div class="row mb-2">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-sm-3">
					<b>Orden</b>
					<div class="d-flex align-items-center">
						<select class="form-control input-sm" id="ddl_orden">
							<option value="">Selecciones</option>
						</select>
						<span class="input-group-btn">
							<button class="btn btn-danger btn-sm" onclick="limpiar_orden()"><i class="bx bx-x me-0"></i></button>
						</span>
					</div>
				</div>
				<div class="col-sm-2">
					<b>Meses</b>
					<div class="input-group input-group-sm">
						<select class="form-select form-select-sm" id="ddl_meses" onchange="Calcularsemanas();">
							<option value="">Selecciones</option>
						</select>
						<button class="btn btn-danger btn-flat p-0" onclick="$('#ddl_meses').val('');Calcularsemanas()"><i class="bx bx-x me-0"></i></button>
					</div>
				</div>
				<div class="col-sm-2">
					<b>Semana</b>
					<div class="input-group input-group-sm">
						<select class="form-select form-select-sm" id="ddl_semanas">
							<option value="">Selecciones</option>
						</select>
							<button class="btn btn-danger btn-flat p-0" onclick="$('#ddl_semanas').val('')"><i class="bx bx-x me-0"></i></button>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="row">
						<div class="col-sm-6">
							Desde
							<div class="input-group input-group-sm">
								<input type="date" name="txt_fecha" id="txt_fecha" class="form-control form-control-sm">
								<button class="btn btn-danger btn-flat p-0" onclick="$('#txt_fecha').val('')"><i class="bx bx-x me-0"></i></button>
							</div>
						</div>
						<div class="col-sm-6">
								Hasta
							<div class="input-group input-group-sm">
								<input type="date" name="txt_fecha" id="txt_fecha_hasta" class="form-control form-control-sm">
								<button class="btn btn-danger btn-flat p-0" onclick="$('#txt_fecha_hasta').val('')"><i class="bx bx-x me-0"></i></button>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-1 text-end">
					<br>
					<button class="btn btn-primary btn-sm" onclick="cargar_datos()"><i class="fa fa-search"></i>Buscar</button>		
				</div>
				
			</div>
		</div>		
	</div>	
</div>
<div class="row text-end">
	<div class="col-sm-12">
		<b># Ordenes:</b> <span id="lbl_num_ord">0</span>
	</div>
	<div class="col-sm-12">
		<b>Resumen:</b> <span id="lbl_resumen">0</span>
	</div>	
</div>

<div class="row mb-2">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-sm-12">
					<table class="table table-sm" id="tbl_data">
						<thead>
							<th>ORDEN</th>
							<th>SOLICITUD</th>
							<th>APROBACION</th>
							<th>DIAS</th>
							<th>PROVEEDOR</th>
							<th>DIAS APRO-PROV</th>
							<th>LISTA DE COMPRAS</th>
							<th>DIAS</th>
						</thead>
						<tbody id="tbl_body">
						</tbody>
					</table>
				</div>	
				
			</div>
		</div>		
	</div>
</div>