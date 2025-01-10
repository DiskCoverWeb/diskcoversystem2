<?php

    if(!isset($_SESSION))
	 		session_start();

?>
<?php
 //verificacion titulo accion
	$_SESSION['INGRESO']['ti']='';
	if(isset($_GET['ti']))
	{
		$_SESSION['INGRESO']['ti']=$_GET['ti'];
	}
	else
	{
		unset( $_SESSION['INGRESO']['ti']);
		$_SESSION['INGRESO']['ti']='BALANCE DE COMPROBACIÓN';
	}
?>
<script src="../../dist/js/Contabilidad/bamup.js"></script>

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
 <div class="row" id='submenu'>
		 <div class="col-12">
			 <div class="box">
			  <div class="box-body">
			  	<div class="row d-flex">
    				<div class="btn-group d-flex align-items-center col-3 col-sm-3 col-md-3 col-lg-3"> 
							<a class="btn btn-outline-secondary btn-sm"  title="Salir del modulo" href="./contabilidad.php?mod=contabilidad#">
								<i><img src="../../img/png/salire.png"></i>
							</a>
							<button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
							<img src="../../img/png/autorizar1.png">
								<span class="caret"></span>
								<ul class="dropdown-menu" role="menu" id="year">
								<li><a class="dropdown-item" href="#" onclick="bamup('ER')" >Estado de Resultado</a></li>
								<li><a class="dropdown-item" href="#" onclick="bamup('ES')" >Estado de Situacion</a></li>
								</ul>
							</button>
								
							<a class="btn btn-outline-secondary btn-sm" title="Imprimir resultados" id="imprimir_pdf">
								<i ><img src="../../img/png/pdf.png"></i>
							</a>
							<a id='imprimir_excel' class="btn btn-outline-secondary btn-sm" title="Exportar Excel" href="#">
								<i ><img src="../../img/png/table_excel.png"></i> 
							</a>
					</div>
					<div class="col-9 col-sm-9 col-md-9 col-lg-9">
						<div class="row">
							<div class="col-8 col-sm-8 col-md-8 col-lg-8 d-flex justify-content-center row">
								<div class="col-5">
									<input type="hidden" name="" id="txt_tipo">		
										<b>Desde: </b>	
											<input type="date" class="form-control form-control-sm" id="desde" placeholder="01/01/2019"
											name="fechai" onKeyPress="return soloNumeros(event)"  maxlength="10" onchange='modificar("NI");' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value='<?php echo date('Y-m-d',strtotime($_SESSION['INGRESO']['Fechai'])) ?>'>	
									
								</div>
								<div class="col-5">
										<b>hasta: </b>
										<input type="date" class="form-control form-control-sm" id="hasta" placeholder="01/01/2019" name="fechaf" onKeyPress="return soloNumeros(event)"  maxlength="10" onchange='modificar("NI");' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value='<?php echo date('Y-m-d',strtotime($_SESSION['INGRESO']['Fechaf'])) ?>'>						
								</div>
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4 col-4 text-center mt-2">
								<b>Tipo Presentación cuentas: </b><br>
								<label class="form-check-label">
									<input class="form-check-input" type="radio" name="optionsRadios" id="optionsRadios1" value="T" checked>
									Todos
								</label>
								<label class="form-check-label">
									<input class="form-check-input" type="radio" name="optionsRadios" id="optionsRadios2" value="G" onclick='modificar("G");'>
									Grupo
								</label>
								<label class="form-check-label">
									<input class="form-check-input" type="radio" name="optionsRadios" id="optionsRadios3" value='D' onclick='modificar("D");' >
									Detalle
								</label>									
							</div>
						</div>
					</div>				 
				</div>
				<div class="row">
					<div class="col-sm-4">
						<label class="form-check-label"><input class="form-check-input" type="checkbox" id="rbl_agencia"> <b> Nombre de Agencia</b></label>
						<select class="form-select form-select-sm text-sm" id="lista_agencia">
							<option value="">Seleccione agencia</option>
						</select>
					</div>
				</div>
				
		 </div>
	  </div>
	</div>
</div>

<?php
	//echo $_SESSION['INGRESO']['Opc'].' ------- '.$_SESSION['INGRESO']['Sucursal'];
	//llamamos spk
	if(isset($_GET['bm']))
	{
		sp_Reporte_Analitico_Mensual($_GET['fechai'],$_GET['fechaf'],$_GET['bm']);

		//verificamos periodo
		if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) )
		{
			if($_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER')
			{
				$periodo=getPeriodoActualSQL();
				//echo $periodo[0]['Fecha_Inicial'];
				$_SESSION['INGRESO']['Fechai']=$periodo[0]['Fecha_Inicial'];
				$_SESSION['INGRESO']['Fechaf']=$periodo[0]['Fecha_Final'];
			}
			else
			{
				//mysql que se valide en controlador
				//echo ' ada '.$_SESSION['INGRESO']['Tipo_Base'];
				$periodo=getPeriodoActualSQL();
				//echo $periodo[0]['Fecha_Inicial'];
				$_SESSION['INGRESO']['Fechai']=$periodo[0]['Fecha_Inicial'];
				$_SESSION['INGRESO']['Fechaf']=$periodo[0]['Fecha_Final'];
			}
		}
	}
			?>

<div class="row">
	<div class="col-sm-12" id="tabla">
		<table class="table text-sm w-100">

		</table>
	</div>
</div>
<script>

</script>
