<?php
    if(!isset($_SESSION)) 
	 		session_start()
	
?>
<!--<h2>Balance de Comprobacion/Situación/General</h2>-->
<div class="panel box box-primary">
  <div class="box-header with-border">
	<h4 class="box-title">
		<a class="btn btn-default"  data-toggle="tooltip" title="Salir del modulo" href="panel.php">
			<i ><img src="../../img/png/salir.png" class="user-image" alt="User Image"
			style='font-size:20px; display:block; height:100%; width:100%;'></i> 
		</a>
		<!--<a id='l1' class="btn btn-default"  data-toggle="tooltip" title="Procesar balance de Comprobación"
		href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1">
			<i ><img src="../../img/png/pbc.png" class="user-image" alt="User Image"
			style='font-size:20px; display:block; height:100%; width:100%;'></i> 
		</a>
		<a id='l2' class="btn btn-default"  data-toggle="tooltip" title="Procesar balance mensual"
		href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Procesar balance mensual&ti=BALANCE MENSUAL&Opcb=2&Opcen=1&b=1">
			<i ><img src="../../img/png/pbm.png" class="user-image" alt="User Image"
			style='font-size:20px; display:block; height:100%; width:100%;'></i> 
		</a>
		<a id='l3' class="btn btn-default"  data-toggle="tooltip" title="Procesar balance consolidado de varias sucursales">
			<i ><img src="../../img/png/pbcs.png" class="user-image" alt="User Image"
			style='font-size:20px; display:block; height:100%; width:100%;'></i> 
		</a>
		<a id='l4' class="btn btn-default"  data-toggle="tooltip" title="Presenta balance de Comprobación"
		href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta balance de Comprobación&ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1">
			<i ><img src="../../img/png/vbc.png" class="user-image" alt="User Image"
			style='font-size:20px; display:block; height:100%; width:100%;'></i> 
		</a>
		<a id='l5' class="btn btn-default"  data-toggle="tooltip" title="Presenta estado de situación (general)"
		href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta estado de situación (general)&ti=ESTADO SITUACIÓN&Opcb=5&Opcen=1&b=0"
		>
			<i ><img src="../../img/png/bc.png" class="user-image" alt="User Image"
			style='font-size:20px; display:block; height:100%; width:100%;'></i> 
		</a>
		<a id='l6' class="btn btn-default"  data-toggle="tooltip" title="Presenta estado de resultado"
		href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta estado de resultado&ti=ESTADO RESULTADO&Opcb=6&Opcen=0&b=0">
			<i ><img src="../../img/png/up.png" class="user-image" alt="User Image"
			style='font-size:20px; display:block; height:100%; width:100%;'></i> 
		</a>
		<a class="btn btn-default"  data-toggle="tooltip" title="Presenta balance mensual por semana">
			<i ><img src="../../img/png/pbms.png" class="user-image" alt="User Image"
			style='font-size:20px; display:block; height:100%; width:100%;'></i> 
		</a>
		<a class="btn btn-default"  data-toggle="tooltip" title="SBS B11">
			<i ><img src="../../img/png/books.png" class="user-image" alt="User Image"
			style='font-size:20px; display:block; height:100%; width:100%;'></i> 
		</a>-->
		<a class="btn btn-default"  data-toggle="tooltip" title="Imprimir resultados">
			<i ><img src="../../img/png/impresora.png" class="user-image" alt="User Image"
			style='font-size:20px; display:block; height:100%; width:100%;'></i> 
		</a>
		
		<a class="btn btn-default"  data-toggle="tooltip" title="Exportar Excel">
			<i ><img src="../../img/png/table_excel.png" class="user-image" alt="User Image"
			style='font-size:20px; display:block; height:100%; width:100%;'></i> 
		</a>
		
		
	  <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
		Collapsible Group Item #1
	  </a> -->
	</h4>
  </div>
  <div id="collapseOne" class="panel-collapse collapse in">
	<div class="box-body">
		<div class="box">
            <div class="box-header">
              <!--<h3 class="box-title">Striped Full Width Table</h3>
				<div class="form-group">
					<label for="fechai" class="col-sm-1 control-label">Desde: </label>
					<div class="col-md-2">
						<div class="input-group date">
							
							  <div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							  </div>
							  <input type="text" class="form-control pull-right" id="desde" placeholder="01/01/2019"
							  value='<?php echo $_SESSION['INGRESO']['Fechai']; ?>' name="fechai">
						</div>
					</div>
				
					<label for="fechaf" class="col-sm-1 control-label">hasta: </label>
					<div class="col-md-2">
						<div class="input-group date">
							
							  <div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							  </div>
							  <input type="text" class="form-control pull-right" id="hasta" placeholder="01/01/2019"
					  value='<?php echo $_SESSION['INGRESO']['Fechaf']; ?>' name="fechaf">
						</div>
					</div>
					<div class="col-md-2">
						<label for="tipo" class="control-label">Tipo Presentación cuentas: </label>
					</div>
						<label>
						  <input type="radio" name="optionsRadios" id="optionsRadios1" value="" checked>
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
				</div>-->
				
			    
            </div>
             <!--  /.box-header -->
			<?php
			//verificamos sesion sql
			if(isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
			{
				$database=$_SESSION['INGRESO']['Base_Datos'];
				$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
				$user=$_SESSION['INGRESO']['Usuario_DB'];
				$password=$_SESSION['INGRESO']['Password_DB'];
			}
			$OpcDG=null;
			//border
			$b=null;
			//si escogio una opcion de radio buton
			if(isset($_GET['OpcDG'])) 
			{
				$OpcDG=$_GET['OpcDG'];
			}
			//border
			if(isset($_GET['b'])) 
			{
				$b=$_GET['b'];
			}
			//llamamos a la funcion para mostrar la grilla
			if(isset($_GET['Opcb'])) 
			{
				
				$balance=ListarAsiento($_GET['ti'],$_GET['Opcb'],$_GET['Opcen'],$OpcDG,$b);
				
			}
			else
			{
				$balance=ListarAsiento('Conexión Oracle',null,null,$OpcDG,$b);
			}
			?>
             
			 <div class="box-footer clearfix">
              <ul class="pagination pagination-sm no-margin pull-right">
                <li><a href="#">&laquo;</a></li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">&raquo;</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
	</div>
  </div>
</div>
<script src="/../../dist/js/hco.js"></script>
