<?php
@session_start();

if(!isset($_SESSION['INGRESO']['IDEntidad']) || !isset($_SESSION['INGRESO']['item']))
{
	echo "<script type='text/javascript'>window.location='".((isset($tipo)&&$tipo==2)?"../":"")."../vista/login.php'</script>";
	die();
}


// else
// {
// 	print_r($_SESSION['INGRESO']['modulo_']);die();
// 	if($_SESSION['INGRESO']['modulo_']!='')
// 	{
// 		$db = new db();
// 		$sql = "SELECT modulo, aplicacion, icono, link FROM modulos WHERE modulo = '".$_GET['mod']."'";
// 		// print_r($sql);die();
// 		$consulta=$db->datos($sql,'MYSQL');
// 		if(count($consulta)>0)
// 		{
// 		 	$NombreModulo = $consulta[0]['aplicacion'];
// 		 	$modulo_logo =  $consulta[0]['icono'];
// 		}else{
// 		 	$_SESSION['INGRESO']['NombreModulo'] = "";
// 		 	$_SESSION['INGRESO']['modulo_'] = "";
// 		 	echo "<script type='text/javascript'>
// 		 			alert('Modulo no encontrado');
// 		 			window.location='../vista/modulos.php';	 			
// 		 	</script>";
// 			// die();
// 		}
// 	}
// }




// if(!isset($_SESSION)) 
// 	{ 		
// 			session_start();
// 			if (isset($_SESSION['autentificado']) != "VERDADERO") {
// 				if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
// 								$uri = 'https://';
// 							}else{
// 								$uri = 'http://';
// 							}
// 							$uri .= $_SERVER['HTTP_HOST'];
							
// 							//echo $uri;
// 					echo "<script type='text/javascript'>window.location='../vista/login.php'</script>";
			
// 			exit(); 
// 		}
// 		else
// 		{
// 			//variables basicas
// 			if (!isset($_SESSION['INGRESO']['url'])) {
// 				if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
// 								$uri = 'https://';
// 							}else{
// 								$uri = 'http://';
// 							}
// 							$uri .= $_SERVER['HTTP_HOST'];
// 				$_SESSION['INGRESO']['url']=$uri;
// 			}
// 		}
			
// 	}
// 	else
// 	{
// 			if (isset($_SESSION['autentificado']) != "VERDADERO") { 
// 				if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
// 								$uri = 'https://';
// 							}else{
// 								$uri = 'http://';
// 							}
// 							$uri .= $_SERVER['HTTP_HOST'];
// 							//echo $uri;
// 					echo "<script type='text/javascript'>window.location='../vista/login.php'</script>";
// 			exit(); 
// 		}
// 		else
// 		{
// 			//variables basicas
// 			if (!isset($_SESSION['INGRESO']['url'])) {
// 				if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
// 								$uri = 'https://';
// 							}else{
// 								$uri = 'http://';
// 							}
// 							$uri .= $_SERVER['HTTP_HOST'];
// 				$_SESSION['INGRESO']['url']=$uri;
// 			}
// 		}		
// 	} 

?>
