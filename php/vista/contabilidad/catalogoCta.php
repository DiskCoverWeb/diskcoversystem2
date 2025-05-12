<script src="../../dist/js/contabilidad/catalogoCta.js"></script>
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
<div class="row">
    <div class="col-lg-5 col-md-6 col-sm-12">
      <div class="btn-group" role="group" aria-label="Basic example">
	      	<a href="inicio.php?mod=<?php echo $_SESSION['INGRESO']['modulo_']; ?>" data-bs-toggle="tooltip" title="Salir de modulo" class="btn btn-sm btn-outline-secondary">
					<img src="../../img/png/salire.png">
				</a>
				<a href="#" class="btn btn-sm btn-outline-secondary" id='imprimir_pdf'  data-bs-toggle="tooltip" title="Descargar PDF">
					<img src="../../img/png/pdf.png">
				</a>
				<a href="#"  class="btn btn-sm btn-outline-secondary"  data-bs-toggle="tooltip" title="Descargar excel" id='imprimir_excel'>
					<img src="../../img/png/table_excel.png">
				</a>
				<button title="Consultar Catalogo de cuentas"  data-bs-toggle="tooltip" class="btn btn-sm btn-outline-secondary" onclick="consultar_datos();">
					<img src="../../img/png/consultar.png" >
				</button>  	      
      </div>
    </div>
    <div class="col-sm-12 col-ms-6 col-lg-2">
			<b>Cuenta inicial:</b>
			<input type="text" name="txt_CtaI" id="txt_CtaI" class="form-control form-control-sm" placeholder="<?php 
				echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
		</div>
		<div class="col-sm-12 col-ms-6 col-lg-2">
			<b> Cuenta final:</b>
			<input type="text" name="txt_CtaF" id="txt_CtaF" class="form-control form-control-sm" placeholder="<?php 
				echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>"> 
		</div>   

  	<div class="col-sm-3">
  		<br>
  		<label class="radio-inline"><input type="radio" name="OpcP" id="OpcT" checked="" onclick="consultar_datos();"><b>Todos</b></label>
      <label class="radio-inline"><input type="radio" name="OpcP" id="OpcG" onclick="consultar_datos();"><b>De grupo</b></label>            
      <label class="radio-inline"><input type="radio" name="OpcP" id="OpcD" onclick="consultar_datos();"><b>De Detalles</b></label>     	
  	</div>
</div>
  <div class="card">
  	<div class="card-body">  		
				<div class="row">
					<h6>PLAN DE CUENTAS</h6>
					<div class="col-lg-12">
						<div class="table-responsive">
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
					</div>					
				</div>  		
  	</div>  	
  </div>