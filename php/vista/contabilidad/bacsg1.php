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
<script src="/../../dist/js/bacsg1.js"></script>


 <div class="row" id='submenu'>
		 <div class="col-xs-12">
			 <div class="box" style='margin-bottom: 5px;'>
			  <div class="box-header">
						<a class="btn btn-default" title="Salir del modulo" href="./contabilidad.php?mod=contabilidad#">
							<i ><img src="../../img/png/salire.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a class="btn btn-default" title="Imprimir resultados" id='imprimir_pdf'>
							<i ><img src="../../img/png/pdf.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						
						<a id='imprimir_excel' class="btn btn-default" title="Exportar Excel" href="#">
							<i ><img src="../../img/png/table_excel.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>


						<button class="btn btn-default" title="Procesar balance de Comprobación" onclick=" cargar_datos('1','BALANCE DE COMPROBACION')">
							<img src="../../img/png/pbc.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'>
						</button>

						<!-- 
						<a id='l1' class="btn btn-default"  data-toggle="tooltip" title="Procesar balance de Comprobación"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&
						ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1&bm=0&fechai=<?php echo date('Y-m-d',strtotime($_SESSION['INGRESO']['Fechai'])) ?>
						&fechaf=<?php echo date('Y-m-d',strtotime($_SESSION['INGRESO']['Fechaf'])) ?>">
							<i ><img src="../../img/png/pbc.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a> -->

						<button class="btn btn-default"  title="Procesar balance mensual" onclick=" cargar_datos('2','BALANCE DE COMPROBACION MENSUAL')">
							<img src="../../img/png/pbm.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'>
						</button>


						<!-- <a id='l2' class="btn btn-default"  data-toggle="tooltip" title="Procesar balance mensual"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Procesar balance mensual&
						ti=BALANCE MENSUAL&Opcb=2&Opcen=1&b=1&bm=1&fechai=<?php echo $_SESSION['INGRESO']['Fechai']; ?>
						&fechaf=<?php echo $_SESSION['INGRESO']['Fechaf']; ?>">
							<i ><img src="../../img/png/pbm.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a> -->

						<button class="btn btn-default" title="Procesar balance consolidado de varias sucursales" disabled >
							<img src="../../img/png/pbcs.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'>
						</button>


						<!-- <a id='l3' class="btn btn-default"  data-toggle="tooltip" title="Procesar balance consolidado de varias sucursales">
							<i ><img src="../../img/png/pbcs.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a> -->

						<button class="btn btn-default" title="Presenta balance de Comprobación" onclick=" cargar_datos('4','BALANCE DE COMPROBACION')">
							<img src="../../img/png/vbc.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'>
						</button>


						<!-- <a id='l4' class="btn btn-default"  data-toggle="tooltip" title="Presenta balance de Comprobación"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta balance de Comprobación&ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1">
							<i ><img src="../../img/png/vbc.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a> -->


						<button class="btn btn-default" title="Presenta estado de situación (general)" onclick=" cargar_datos('5','ESTADO DE SITUACION')">
							<img src="../../img/png/bc.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'>
						</button>



						<!-- <a id='l5' class="btn btn-default"  data-toggle="tooltip" title="Presenta estado de situación (general: activo, pasivo y patrimonio)"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta estado de situación (general)&ti=ESTADO SITUACIÓN&Opcb=5&Opcen=1&b=0"
						>
							<i ><img src="../../img/png/bc.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a> -->

						<button class="btn btn-default" title="Presenta estado de resultado (ingreso y egresos)" onclick="cargar_datos('6','ESTADO DE RESULTADOS')">
							<img src="../../img/png/up.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'>
						</button>

<!-- 
						<a id='l6' class="btn btn-default"  data-toggle="tooltip" title="Presenta estado de resultado (ingreso y egresos)"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta estado de resultado&ti=ESTADO RESULTADO&Opcb=6&Opcen=0&b=0">
							<i ><img src="../../img/png/up.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a> -->



						<button class="btn btn-default" title="Presenta balance mensual por semana" disabled>
							<i ><img src="../../img/png/pbms.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</button>
						<button class="btn btn-default" title="SBS B11" disabled>
							<i ><img src="../../img/png/books.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</button>
					
			  </div>
			  <div class="box-body">
			  	<div class="row">
			  		<div class="col-sm-2">
			  			<input type="hidden" name="" id="txt_item" value="1">
			  			<b>Desde:</b>
			  			 <input type="date" class="form-control pull-right input-sm" id="desde" name="fechai" value='<?php echo date('Y-m-d',strtotime($_SESSION['INGRESO']['Fechai'])) ?>' onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">		  			
			  		</div>
			  		<div class="col-sm-2">
			  			<b>Hasta:</b>
			  			<input type="date" class="form-control pull-right input-sm" id="hasta" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)" name="fechaf" value='<?php echo date('Y-m-d',strtotime($_SESSION['INGRESO']['Fechaf'])) ?>' >			  			
			  		</div>
			  		<div class="col-sm-4">
			  			<b>Tipo de Presentacion de cuentas</b> <br>
						<label class="radio-inline"><input type="radio" name="optionsRadios" id="optionsRadios1" value="" checked> Todos </label>
						<label class="radio-inline"><input type="radio" name="optionsRadios" id="optionsRadios2" value="G">Grupo </label>
						<label class="radio-inline"><input type="radio" name="optionsRadios" id="optionsRadios3" value='D'> Detalle </label>
			  		</div>
			  		<div class="col-sm-3">
			  			<label class="radio-inline"><input type="checkbox" name="optionsRadios" id="tbalan" onclick="mostrar_select()"><b> Balance externo</b>  </label>
						<select class="form-control" id="balance_ext" style="display: none">
							<option value="00">Selecione Tipo</option>
						</select>			  			
			  		</div>
			  		
			  	</div>
			  </div>
			 </div>
		 </div>
	  </div>
	  <div class="row">
	  	<div class="table-responsive">
	  		<div class="col-sm-12" id="tabla">
	  			
	  		</div>
	  		
	  	</div>
	  </div>