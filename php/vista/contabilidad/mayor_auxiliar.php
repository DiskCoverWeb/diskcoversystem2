<script src="../../dist/js/mayor_auxiliar.js"></script>
<div class="overflow-auto p-2 ">
	<div class="">
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<div class="breadcrumb-title pe-3">
				<?php echo $NombreModulo; ?>
			</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
						<li class="breadcrumb-item active" aria-current="page">Mayores Auxiliares</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
	<div class="row row-cols-auto d-flex align-items-center w-auto ps-2 pb-2">
		<div class="row row-cols-auto btn-group" role="group">
			<a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" data-toggle="tooltip" title="Salir de modulo" class="btn btn-outline-secondary">
				<img src="../../img/png/salire.png">
			</a>
			<button title="Consultar un Mayor Auxiliar"  data-toggle="tooltip" class="btn btn-outline-secondary" onclick="consultar_datos(true,Individual);">
				<img src="../../img/png/consultar.png" >
			</button>
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="dropdown" title="Descargar PDF">
				<img src="../../img/png/pdf.png">
			</button>
			<ul class="dropdown-menu">
				<li><a href="#" id="imprimir_pdf">Impresion normal</a></li>
				<li><a href="#" id="imprimir_pdf_2">Por Sub Modulo / Centro de costos</a></li>
			</ul>
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="dropdown"  title="Descargar Excel">
			<img src="../../img/png/table_excel.png">
			</button>
			<ul class="dropdown-menu">
			<li><a href="#" id="imprimir_excel">Impresion normal</a></li>
			<li><a href="#" id="imprimir_excel_2">Por Sub Modulo / Centro de costos</a></li>
			</ul>            	
			<button title="Consultar Varios Mayor Auxiliar"  class="btn btn-outline-secondary" data-toggle="tooltip" onclick="consultar_datos(false,Individual);">
				<img src="../../img/png/es.png" >
			</button>
		</div> 
	</div>
	<div class="row row-cols-auto">          
		<div class="col-3 p-0 m-0 ps-3"><br>
			<div>	
				<b class="">Cuenta inicial:</b>
				<br>
				<input type="text" name="txt_CtaI" id="txt_CtaI" class="form-control form-control-sm h-50 col-7 border border-2" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
			</div>
			<div> 
				<b>Cuenta final:</b>
				<br>
				<input type="text" name="txt_CtaF" id="txt_CtaF" onblur="llenar_combobox_cuentas()" class="form-control form-control-sm h-50 col-7 border border-2" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" > 
			</div>
		</div>
		<div class="col-4 pt-4 p-0 m-0"><br>
			<div class="col-10 row row-cols-auto p-0 m-0">
				<b class="w-25">Desde:</b>
				<input type="date" name="desde" id="desde" class="form-control form-control-sm border rounded w-75" style="font-size: 0.8rem"  value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);">
			</div>
			<div class="col-10 row row-cols-auto p-0 m-0">
				<label class="fw-bold w-25">Hasta:</label>
				<input type="date" name="hasta" id="hasta"  class="form-control form-control-sm border rounded w-75" style="font-size: 0.8rem" value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);">  	              	
			</div>
		</div>

		<div class="col-5 pt-4 pe-5">
			<div class="row row-cols-auto">
				<label style="margin:0px"><input type="checkbox" name="CheckUsu" id="CheckUsu"><b class=""> Por usuario</b></label>
				<select class="form-select form-control-sm h-25 " style="font-size: 0.83rem;" id="DCUsuario"  onchange="consultar_datos(true,Individual);">
					<option value="" >Seleccione usuario</option>
				</select>
				<b>Por cuenta</b>
				<select class="form-select form-control-sm h-25 " style="font-size: 0.83rem" id="DCCtas" onchange="consultar_datos(true,Individual);">
					<option value="">Seleccione cuenta</option>
				</select>
			</div>
			<div class="col-6">
				<label id="lblAgencia"><input type="checkbox" name="CheckAgencia" id="CheckAgencia"><b class=""> Agencia</b></label>
				<select class="form-control" class="" style="font-size: 0.83rem; height: 26%" id="DCAgencia" onchange="consultar_datos(true,Individual);">
					<option value="">Seleccione agencia</option>
				</select>
			</div>             
		</div>
		<div class="col-3 pt-4">
				<b>Saldo anterior MN:</b> 
				<input type="text" name="OpcP" id="LabelTotSaldoAnt" class="border border-1">          
		</div>		
	</div>
	<!--seccion de panel-->
	<div class="row pt-3">
		<input type="input" name="OpcU" id="OpcU" value="true" hidden="">
		<div class="col-sm-12">
			<ul class="nav nav-tabs">
				<li class="active p-2 fw-normal">
				<h6 id="tit">Mayores auxiliares</h6></li>
			</ul>
		<div class="table-responsive">
				<table class="table text-sm w-100" id="tbl_Mayor_Auxiliar">
					<thead>
						<tr>
							<th class="text-center">Cta</th>
							<th class="text-center">Fecha</th>
							<th class="text-center">TP</th>
							<th class="text-center">Numero</th>
							<th class="text-center">Cheq_Dep</th>
							<th class="text-center">Cliente</th>
							<th class="text-center">Concepto</th>
							<th class="text-center">Debe</th>
							<th class="text-center">Haber</th>
							<th class="text-center">Saldo</th>
							<th class="text-center">Parcial_ME</th>
							<th class="text-center">Saldo_ME</th>
							<th class="text-center">T</th>
							<th class="text-center">Item</th>
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
	<div class="row">
		<div class="col-sm-2 pt-2">
			<b>DEBE:</b>
		</div>
		<div class="col-sm-2 pt-2">
			<input type="text" class="form-control form-control-sm" style="font-size: 0.70rem" name="debe" id="debe" value="0.00" readonly>	  		
		</div>
		<div class="col-sm-2 pt-2">
			<b>HABER:</b>
		</div>
		<div class="col-sm-2 pt-2">	  		
			<input type="text" class="form-control form-control-sm" style="font-size: 0.70rem" name="haber" id="haber" value="0.00" readonly>
		</div>
		<div class="col-sm-2 pt-2">
			<b>SALDO MN:</b>
		</div>
		<div class="col-sm-2 pt-2">	  		
			<input type="text" class="form-control form-control-sm" style="font-size: 0.70rem" name="saldo" id="saldo" value="0.00" readonly>
		</div>
	</div>
</div>