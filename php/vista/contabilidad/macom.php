<?php @session_start(); ?>
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
		$_SESSION['INGRESO']['ti']='MAYORIZACIÓN';
	}

	if(function_exists('curl_init')) // Comprobamos si hay soporte para cURL
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"contabilidad.php?mod=contabilidad&acc=macom&acc1=Mayorización&b=1&asi=1");
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			$resultado = curl_exec ($ch);
			
			print_r($resultado);
		}
		else
		{
			echo "No hay soporte para cURL";
		}

		$texto = sp_errores($_SESSION['INGRESO']['item'],$_SESSION['INGRESO']['modulo_'],$_SESSION['INGRESO']['Id']);
			if(count($texto)>0)
		{
			//para el overflow
			if(count($texto)<=8)
			{
				$ove=220;
			}
			if(count($texto)>8 and count($texto)<=14)
			{
				$ove=300;
			}
			if(count($texto)>14 and count($texto)<=21)
			{
				$ove=350;
			}
			if(count($texto)>21)
			{
				$ove=400;
			}
		}else
		{
			echo "<script>
			
			 Swal.fire({
			  title: 'Terminado!',
			  text: 'Proceso de mayorización listo.',
			  
			  animation: false
			}).then((result) => {
					  if (
						result.value
					  ) {
						console.log('I was closed by the timer');
						location.href ='inicio.php?mod=01&er=1';
					  }
					});
		</script>";
		}

?>
<div class="container-lg">
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
	<div class="row row-cols-auto">
		<div class="btn-group">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-outline-secondary btn-sm">
              <img src="../../img/png/salire.png">
            </a>
            <a id='l1' class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Procesar balance de Comprobación"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1">
							<img src="../../img/png/pbc.png"> 
						</a>
           	<a id='l2' class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Procesar balance mensual"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Procesar balance mensual&ti=BALANCE MENSUAL&Opcb=2&Opcen=1&b=1">
							<img src="../../img/png/pbm.png"> 
						</a>
            <a id='l3' class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Procesar balance consolidado de varias sucursales">
							<img src="../../img/png/pbcs.png" >
						</a>
            <a id='l4' class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Presenta balance de Comprobación"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta balance de Comprobación&ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1">
							<img src="../../img/png/vbc.png" >
						</a>
           	<a id='l5' class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Presenta estado de situación (general: activo, pasivo y patrimonio)"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta estado de situación (general)&ti=ESTADO SITUACIÓN&Opcb=5&Opcen=1&b=0"
						>
							<img src="../../img/png/bc.png" >
						</a>
            <a id='l6' class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Presenta estado de resultado (ingreso y egresos)"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta estado de resultado&ti=ESTADO RESULTADO&Opcb=6&Opcen=0&b=0">
							<img src="../../img/png/up.png" > 
						</a>
           <a class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Presenta balance mensual por semana">
							<img src="../../img/png/pbms.png"> 
						</a>						
           <a class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="SBS B11">
							<img src="../../img/png/books.png"> 
						</a>
           <a class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Imprimir resultados">
							<img src="../../img/png/impresora.png"> 
						</a>
            	<a id='l7' class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Exportar Excel"
						href="descarga.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
						&Opcb=6&Opcen=0&b=0&ex=1" onclick='modificar1();' target="_blank">
							<img src="../../img/png/table_excel.png"> 
						</a>
		</div>
 	</div>
 	<div class="row">
 		
 	</div>
</div>

<div class="modal fade" id="myModal" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog">
    	    <div class="modal-content">
    				<div class="modal-header">
				  		<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
				  		<h4 class="modal-title"><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg"> Listado de errores</h4>
					</div>
						    <div class="modal-body" style="height:<?php echo $ove; ?>px;overflow-y: scroll;">
								<div class="form-group">
									<p align='left' id='texto'>
										<?php
											for($i=0;$i<count($texto);$i++)
											{
												echo ''.$texto[$i].'<br>';														
											}
										?>	
									</p>
								</div>
						    </div>
						<div class="modal-footer">
							<div id="alerta" class="alert invisible"></div>
							<p  align='left'><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg">
							En caso de dudas, comuniquese al centro de atención al cliente, a los telefonos:<br> 
							+593-2-321-0051 / +593-9-8035-5483</p>
							<a id='l5' class="btn btn-default"  title="Exportar PDF" href="descarga.php?mod=contabilidad&acc=macom&acc1=Errores de mayorización&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>&Opcb=6&Opcen=0&b=0&ex=pdf&cl=0" target="_blank" >
								<i ><img src="../../img/png/pdf.png" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:100%;'></i> 
							</a>
							<button id="btnCopiar" class="btn btn-primary" onclick='copiar();'>Copiar</button>
						  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
						</div>
		</div>			  
	</div>
</div>
	

<!--  <div class="row">
		 <div class="col-xs-12">
			 <div class="box" style='margin-bottom: 5px;'>
			  <div class="box-header">
					<h4 class="box-title">
							<a class="btn btn-default"  data-toggle="tooltip" title="Salir del modulo" href="contabilidad.php?mod=contabilidad">
							<i ><img src="../../img/png/salir.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a id='l1' class="btn btn-default"  data-toggle="tooltip" title="Procesar balance de Comprobación"
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
						<a id='l5' class="btn btn-default"  data-toggle="tooltip" title="Presenta estado de situación (general: activo, pasivo y patrimonio)"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta estado de situación (general)&ti=ESTADO SITUACIÓN&Opcb=5&Opcen=1&b=0"
						>
							<i ><img src="../../img/png/bc.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a id='l6' class="btn btn-default"  data-toggle="tooltip" title="Presenta estado de resultado (ingreso y egresos)"
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
						</a>
						<a class="btn btn-default"  data-toggle="tooltip" title="Imprimir resultados">
							<i ><img src="../../img/png/impresora.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						
						<a id='l7' class="btn btn-default"  data-toggle="tooltip" title="Exportar Excel"
						href="descarga.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
						&Opcb=6&Opcen=0&b=0&ex=1" onclick='modificar1();' target="_blank">
							<i ><img src="../../img/png/table_excel.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						
						
					</h4>
			  </div>
			 </div>
		 </div>
	  </div> -->

<script src="../../dist/js/Contabilidad/macom.js"></script>