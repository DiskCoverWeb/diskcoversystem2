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
<script src="/../../dist/js/bamup.js"></script>

 <div class="row" id='submenu'>
		 <div class="col-xs-12">
			 <div class="box" style='margin-bottom: 5px;'>
			  <div class="box-body">
			  	<div class="row">

    				<div class="col-lg-4 col-sm-10 col-md-8 col-xs-12"> 
				  		<div class="col-xs-1 col-md-1 col-sm-1 col-lg-2">
							<a class="btn btn-default"  title="Salir del modulo" href="./contabilidad.php?mod=contabilidad#">
								<i><img src="../../img/png/salire.png"></i>
							</a>
						</div>
						<div class="col-xs-1 col-md-1 col-sm-1 col-lg-2">
						       <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						        <img src="../../img/png/autorizar1.png">
						         <span class="caret"></span>
						      </button>
						    <ul class="dropdown-menu" role="menu" id="year">
						      <li><a href="#" onclick="bamup('ER')" >Estado de Resultado</a></li>
						      <li><a href="#" onclick="bamup('ES')" >Estado de Situacion</a></li>
					    	</ul>
					    </div>
						<div class="col-xs-1 col-md-1 col-sm-1 col-lg-2">
							<a class="btn btn-default" title="Imprimir resultados" id="imprimir_pdf">
								<i ><img src="../../img/png/pdf.png"></i>
							</a>
						</div>
						<div class="col-xs-1 col-md-1 col-sm-1 col-lg-2">
							<a id='imprimir_excel' class="btn btn-default" title="Exportar Excel" href="#">
								<i ><img src="../../img/png/table_excel.png"></i> 
							</a>
						</div>	
					</div>
					<div class="col-lg-8 col-sm-10 col-md-8 col-xs-12">
						<div class="row">
								<div class="col-sm-3">
			              			<input type="hidden" name="" id="txt_tipo">		
										<b>Desde: </b>	
										 <input type="date" class="form-control pull-right input-sm" id="desde" placeholder="01/01/2019"
										 name="fechai" onKeyPress="return soloNumeros(event)"  maxlength="10" onchange='modificar("NI");' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value='<?php echo date('Y-m-d',strtotime($_SESSION['INGRESO']['Fechai'])) ?>'>	
									
								</div>
								<div class="col-sm-3">
										<b>hasta: </b>
										<input type="date" class="form-control pull-right input-sm" id="hasta" placeholder="01/01/2019" name="fechaf" onKeyPress="return soloNumeros(event)"  maxlength="10" onchange='modificar("NI");' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value='<?php echo date('Y-m-d',strtotime($_SESSION['INGRESO']['Fechaf'])) ?>'>						
								</div>
								<div class="col-sm-6">
									<b>Tipo Presentación cuentas: </b><br>
									<label>
									  <input type="radio" name="optionsRadios" id="optionsRadios1" value="T" checked>
									  Todos
									</label>
									<label>
									  <input type="radio" name="optionsRadios" id="optionsRadios2" value="G" onclick='modificar("G");'>
									  Grupo
									</label>
									<label>
									  <input type="radio" name="optionsRadios" id="optionsRadios3" value='D' onclick='modificar("D");' >
									  Detalle
									</label>									
								</div>
						 </div>
					</div>				 
				</div>
				<div class="row">
					<div class="col-sm-4">
						<label><input type="checkbox" id="rbl_agencia"> <b> Nombre de Agencia</b></label>
						<select class="form-control input-xs" id="lista_agencia">
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
		
	</div>
</div>
<script>

</script>
