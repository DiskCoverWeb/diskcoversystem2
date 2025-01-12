<script src="../../dist/js/Contabilidad/catalogoCta.js"></script>
<div>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?>
    </div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
          <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
          </li>
        </ol>
      </nav>
    </div>          
  </div>

<div class="row row-cols-auto d-flex align-items-center w-auto pb-2">          
	<div class="row row-cols-auto btn-group" role="group" aria-label="Basic example"> 
			<a href="inicio.php?mod=<?php echo $_SESSION['INGRESO']['modulo_']; ?>" data-toggle="tooltip" title="Salir de modulo" class="btn btn-outline-secondary">
				<img src="../../img/png/salire.png">
			</a>
			<a href="#" class="btn btn-outline-secondary" id='imprimir_pdf'  data-toggle="tooltip"title="Descargar PDF">
				<img src="../../img/png/pdf.png">
			</a>
			<a href="#"  class="btn btn-outline-secondary"  data-toggle="tooltip"title="Descargar excel" id='imprimir_excel'>
				<img src="../../img/png/table_excel.png">
			</a>
			<button title="Consultar Catalogo de cuentas"  data-toggle="tooltip" class="btn btn-outline-secondary" onclick="consultar_datos();">
				<img src="../../img/png/consultar.png" >
			</button>
	</div>
	<div class="row row-cols-auto ms-auto">
		<div class="col-12 col-sm-12 col-ms-6 col-lg-6">
			<b>Cuenta inicial:</b>
			<br>
			<input type="text" name="txt_CtaI" id="txt_CtaI" class="form-control form-control-sm h-50" placeholder="<?php 
				echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
		</div>
		<div class="col-12 col-sm-12 col-ms-6 col-lg-6">
			<b> Cuenta final:</b>
		<br>
			<input type="text" name="txt_CtaF" id="txt_CtaF" class="form-control form-control-sm h-50" placeholder="<?php 
				echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>"> 
		</div>       	
	</div>
</div>             		 
	  <!--seccion de panel-->
	  <div class="row w-100">
	  	<div class="card">
			<div class="card-body">
				<ul class="nav nav-pills mb-3" role="tablist">
					<li class="nav-item" role="presentation">
						<a class="nav-link active" id="OpcT" data-bs-toggle="pill" href="#primary-pills-all" role="tab" aria-selected="true">
							<div class="d-flex align-items-center">
								<div class="tab-title">TODOS</div>
							</div>
						</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link" id="OpcG" data-bs-toggle="pill" href="#primary-pills-grupo" role="tab" aria-selected="false" tabindex="-1">
							<div class="d-flex align-items-center">
								<div class="tab-title">DE GRUPO</div>
							</div>
						</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link" id="OpcD" data-bs-toggle="pill" href="#primary-pills-detalle" role="tab" aria-selected="false" tabindex="-1">
							<div class="d-flex align-items-center">
								<div class="tab-title">DE DETALLES</div>
							</div>
						</a>
					</li>
				</ul>
				<h6>PLAN DE CUENTAS</h6>
				<div class="tab-content">
					<div id="home" class="">
						<div class="tab-content" id="cta-tabContent">
							<div class="tab-pane fade active show" id="primary-pills-all" role="tabpanel" >
								<table class="table text-sm w-100" id="tbl_tablaCta">
									<thead>
										<tr>
											<th class="text-center">Clave</th>
											<th class="text-center">TC</th>
											<th class="text-center">ME</th>
											<th class="text-center">DG</th>
											<th class="text-center">Codigo</th>
											<th class="text-center">Cuenta</th>
											<th class="text-center">Presupuesto</th>
											<th class="text-center">Codigo_Ext</th>
										</tr>
									</thead>
									<tbody>
										<tr>
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
							<div class="tab-pane fade" id="primary-pills-grupo" role="tabpanel">
								<table class="table text-sm w-100" id="tbl_tablaCtaGrupos">
									<thead>
										<th class="text-center">Clave</th>
										<th class="text-center">TC</th>
										<th class="text-center">ME</th>
										<th class="text-center">DG</th>
										<th class="text-center">Codigo</th>
										<th class="text-center">Cuenta</th>
										<th class="text-center">Presupuesto</th>
										<th class="text-center">Codigo_Ext</th>
									</thead>
									<tbody>
										<tr>
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
							<div class="tab-pane fade" id="primary-pills-detalle">
								<table class="table text-sm w-100" id="tbl_tablaCtaDetalles">
									<thead>
										<th class="text-center">Clave</th>
										<th class="text-center">TC</th>
										<th class="text-center">ME</th>
										<th class="text-center">DG</th>
										<th class="text-center">Codigo</th>
										<th class="text-center">Cuenta</th>
										<th class="text-center">Presupuesto</th>
										<th class="text-center">Codigo_Ext</th>
									</thead>
									<tbody>
										<tr>
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
				</div>
			</div>
	  	</div>
	  </div>
   <br><br>
</div>