<?php 
header("Access-Control-Allow-Origin: *"); // Permite todas las orígenes, puedes restringir a uno específico
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Encabezados permitidos
header('Content-Type: application/json');

require_once(dirname(__DIR__,2)."/db/db1.php");

date_default_timezone_set('America/Guayaquil');


$controlador = new autoriza_sri();
if(isset($_GET['AutorizarXMLOnline']))
{
	$parametros = $_POST;

	// print_r($parametros);die();
     echo json_encode($controlador->AutorizarXMLOnline($parametros));
}
if(isset($_GET['subirftp']))
{
	$xml = '';
	if(isset($_GET['XML']))
	{
		$xml = $_GET['XML'];
	}
	echo json_decode($controlador->subirftp($xml));
}

/**
 * 
 */
class autoriza_sri
{
	private $clave;
	//Metodo de encriptación
	private $method;
	private $iv;
	private $conn;
	private $db;
	// Puedes generar una diferente usando la funcion $getIV()
	public $linkSriAutorizacion;
	public $linkSriRecepcion;
	private $rutaJava8;
	function __construct()
	{
		$this->clave = 'Una cadena, muy, muy larga para mejorar la encriptacion';
		$this->method = 'aes-256-cbc';
		$this->iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
		// $this->conn = new Conectar();
		$this->db = new db();
		$this->rutaJava8  = "";
		// $this->rutaJava8  = escapeshellarg("C:\\Program Files\\Java\\jdk-1.8\\bin\\");
	}



//esta funcion entra aun ftp donde se encontrara el archivo xml firmado de antemano
	function AutorizarXMLOnline($parametros)
	{
		// print_r($parametros);die();
		$respuesta = 1;
		$temp_file = 'ftp_folder_xmls/';
		if(trim($parametros['XML'])!='')
		{
			$msj = $this->descargar_archivos_ftp($parametros['XML']);
			if($msj!="")
			{
				// $this->deleteFolder($temp_file);
				return $msj;
			}
		}
		$xml = $parametros['XML'];
		// print_r($xml);die();
		 if (trim($xml)!='') {
          	  $archivos = explode(';',$xml);
          	  // print_r($archivos);die();
              foreach ($archivos as $key => $value) {
              	$xml = substr($value,0,-4);
              	$ambiente = substr(substr($xml,0,24),-1,1);
              	// print_r($ambiente);die();
              	$this->link_ambientes($ambiente);

              	// print_r($this->linkSriRecepcion);die();
              	// print_r($ambiente);die();
              	// print_r($xml);die();

              	 $validar_autorizado = $this->comprobar_xml_sri($xml,$this->linkSriAutorizacion);
              	 // print_r($validar_autorizado);die();
              	 if($validar_autorizado[0] == 1)
		   	 		{
		   	 			$this->subirftp($xml);
		   	 			$this->borrar_xml_file($xml);
			   		 	return  "Autorizado";
		   	 		}


              	if($parametros['RUTA']!='' && $parametros['PASS']!='')
              	{
              		$firma = $this->firmar_documento($xml,$parametros['PASS'],$parametros['RUTA']);
              		if($firma!=1)
              		{
              			return $firma;
              		}else
              		{
              			$this->subirftpFirmado($xml);
              		}
              	}

              	// print_r("hola");die();
		   	 	// print_r($validar_autorizado);die();
		   	 	// if($validar_autorizado == -1)
		   	 	// 	{
				   	 	$enviar_sri = $this->enviar_xml_sri($xml,$this->linkSriRecepcion);
				   	 	// print_r($enviar_sri);die();
				   		if($enviar_sri[0]==1)
				   		{
				   		 	//una vez enviado comprobamos el estado de la factura
			   		 		sleep(3);
			   		 		// print_r("expression");die();
			   		 		$resp =  $this->comprobar_xml_sri($xml,$this->linkSriAutorizacion);
			   		 		// print_r($resp);die();
			   		 		if($resp[0]==1)
			   		 		{
			   		 			// $this->deleteFolder($temp_file);
			   		 			if($this->subirftp($xml))
			   		 			{
			   		 				$this->borrar_xml_file($xml);
			   		 				return  "Autorizado";
			   		 			}else
			   		 			{
			   		 				return "No se subio al ftp";
			   		 			}
			   		 		}else
			   		 		{
			   		 			$mensaje = $this->validar_existencia($xml);
			   		 			$this->subirftp($xml);
				   				return $mensaje;
			   		 		}
				   		 		// print_r($resp);die();
				   		}else
				   		{
				   			$mensaje = $this->validar_existencia($xml);
			   				// $this->deleteFolder($temp_file);
			   				// print_r($xml);die();
			   				$this->subirftp($xml);
				   			return $mensaje;
				   		}

			   		// }else 
			   		// {	
			   		// 	// print_r($xml);die();		
			   		// 	if($validar_autorizado==1)
			   		// 	{
			   		// 		// $this->deleteFolder($temp_file);
			   		// 		$this->subirftp($xml);
			   		// 		return "Autorizado";
			   		// 	}else{   		 
			   		// 		$this->subirftp($xml);
			   		// 		return $validar_autorizado;
			   		// 	}
			   		// }
            }
          }

				

	}

	function descargar_archivos_ftp($archivos)
	{
		$archivos = str_replace(" ","", $archivos);
		// print_r($archivos);die();
		//proceso para envio de archivo por ftp 
		$ftp_host = "erp.diskcoversystem.com";
		$ftp_user = "ftpuser";
		$ftp_pass = "ftp2023User";
		$ftp_port = 21; // Cambia al puerto que necesites

		$remote_file = '/files/ComprobantesElectronicos/';
		$temp_file = 'ftp_folder_xmls/';
		$remote_path = '/';

		if(!file_exists($temp_file)){mkdir($temp_file, 0777);}
		$temp_file = 'ftp_folder_xmls/Autorizados/';
		if(!file_exists($temp_file)){mkdir($temp_file, 0777);}
		$temp_file = 'ftp_folder_xmls/Enviados/';
		if(!file_exists($temp_file)){mkdir($temp_file, 0777);}
		$temp_file = 'ftp_folder_xmls/Firmados/';
		if(!file_exists($temp_file)){mkdir($temp_file, 0777);}
		$temp_file = 'ftp_folder_xmls/Rechazados/';
		if(!file_exists($temp_file)){mkdir($temp_file, 0777);}
		$temp_file = 'ftp_folder_xmls/No_autorizados/';
		if(!file_exists($temp_file)){mkdir($temp_file, 0777);}
		$temp_file = 'ftp_folder_xmls/Enviados/';
		if(!file_exists($temp_file)){mkdir($temp_file, 0777);}
		$temp_fileF = 'ftp_folder_xmls/Generados/';
		if(!file_exists($temp_fileF)){mkdir($temp_fileF, 0777);}


		$ftp_conn = ftp_connect($ftp_host, $ftp_port) or die("No se pudo conectar al servidor FTP.");
		$login = ftp_login($ftp_conn, $ftp_user, $ftp_pass);

		
		ftp_pasv($ftp_conn, true);

		// $archivos = ftp_nlist($ftp_conn, $remote_path);

		// if (ftp_chdir($ftp_conn, "files")) {
		//     echo "\nCambiado al directorio: $directorio\n";

		//     // Listar archivos en el nuevo directorio
		//     $archivos = ftp_nlist($ftp_conn, '.');
		//     if ($archivos) {
		//         foreach ($archivos as $archivo) {
		//             echo $archivo . "\n";
		//         }
		//     } else {
		//         echo "El directorio está vacío o no se pudo listar los archivos.\n";
		//     }
		// } 
		// print_r($archivos);die();

		$archi  = explode(';',$archivos);
		$msj = '';
		foreach ($archi as $key => $value) {
			// @ quita el warning
			if(!ftp_get($ftp_conn, $temp_fileF.$value, $remote_file.$value, FTP_BINARY))
			{
				$msj.='no existe en ftp: '.$value.'\n';
			}			
			
		}
		ftp_close($ftp_conn);
		return $msj;

	}

	function deleteFolder($folderPath) {
    // Verifica si el folder existe
    if (!is_dir($folderPath)) {
        return false;
    }

    // Itera sobre cada elemento dentro del folder
    $files = scandir($folderPath);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;

        // Si es una carpeta, llama a la función de nuevo de forma recursiva
        if (is_dir($filePath)) {
            $this->deleteFolder($filePath);
        } else {
            // Si es un archivo, lo elimina
            unlink($filePath);
        }
    }

    // Finalmente elimina el folder principal una vez vacío
    return rmdir($folderPath);
}



	function link_ambientes($ambiente)
	{
		if($ambiente==1)
		{

	 	   // para pruebas
	       $this->linkSriAutorizacion = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';
	 	   $this->linkSriRecepcion = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl';
		}else
		{
			 $this->linkSriAutorizacion = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl'; 
 	   		$this->linkSriRecepcion = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl';
		}

	}
	

  function firmar_documento($nom_doc,$pass,$p12)
    {	

 	    $firmador = dirname(__DIR__).'/SRI/firmar/firmador.jar';
    	$url_generados=dirname(__DIR__).'/SRI/ftp_folder_xmls/Generados/';
    	$url_firmados=dirname(__DIR__).'/SRI/ftp_folder_xmls/Firmados/';
    	$url_rechazado=dirname(__DIR__).'/SRI/ftp_folder_xmls/Rechazados/';
 	    $certificado_1 = dirname(__DIR__).'/certificados/';
 	    if(file_exists($certificado_1.$p12))
	       {
	       	
	       	if(file_exists($url_generados.$nom_doc.".xml"))
	       	{
	       		// print_r("java -jar ".$firmador." ".$nom_doc.".xml ".$url_generados." ".$url_firmados." ".$certificado_1." ".$p12." ".$pass);die();

	        	exec($this->rutaJava8."java -jar ".$firmador." ".$nom_doc.".xml ".$url_generados." ".$url_firmados." ".$certificado_1." ".$p12." ".$pass, $f);

	        	// print_r($f);die();
	        	if(count($f)<6 && !empty($f))
		 		{
		 			return 1;		 		
		 		}else
		 		{		 			
		 			$respuesta = 'Error al generar XML o al firmar';
		 			return $respuesta;          
		        }
		    }else
		    {
		    	$respuesta = 'XML generado no encontrado';
	 			return $respuesta;
		    }
	 	   }else
	 	   {
	 	   		$respuesta = 'No se han encontrado Certificados';
	 			return $respuesta;
	 	   }

 		// $quijoteCliente =  dirname(__DIR__).'/SRI/firmar/QuijoteLuiClient-1.2.1.jar';
 	 //    $url_No_autorizados =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/No_autorizados/';
 	 //    $url_autorizado =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Autorizados/';

 	 //    $linkSriAutorizacion = $_SESSION['INGRESO']['Web_SRI_Autorizado'];
 	 //    $linkSriRecepcion = $_SESSION['INGRESO']['Web_SRI_Recepcion'];
 	   
 		
    }

    //comprueba si el xml ya se envio al sri
    // 1 para autorizados
    //-1 para no autorizados
    // 2 para devueltas
    function comprobar_xml_sri($clave_acceso,$link_autorizacion)
    {
    	$comprobar_sri = dirname(__DIR__).'\\SRI\\firmar\\JavClientSri.jar';
    	$url_autorizado= dirname(__DIR__).'\\SRI\\ftp_folder_xmls\\Autorizados/';
 	    $url_No_autorizados = dirname(__DIR__).'\\SRI\\ftp_folder_xmls\\No_autorizados/';

 	    $comprobado = true;
 	    $output = '';
 	    $veces_envio = 1;
 	    while ($comprobado) {
 	    	$command = $this->rutaJava8."java -jar ".$comprobar_sri." 2 ".$clave_acceso." ".$url_autorizado." ".$url_No_autorizados." ".$link_autorizacion; 
	   		$output = shell_exec($command);   
	   		// print_r($output);die();		
	   		$output = mb_convert_encoding($output, 'UTF-8', 'ISO-8859-1');
			$output = json_decode($output,true); // <== para que la respuesta se haga un array
			if( isset($output[2]) && $output[2]=='AUTORIZADO' || $veces_envio>=3)
			{
				$comprobado = false;
			}	
			$veces_envio = $veces_envio+1;
 	    }  		 
   		
   		return $output;
    }

    //envia el xml asia el sri
    function enviar_xml_sri($clave_acceso,$url_recepcion)
    {

    	$ruta_firmados=dirname(__DIR__).'/SRI/ftp_folder_xmls/Firmados/';
    	$ruta_enviados=dirname(__DIR__).'/SRI/ftp_folder_xmls/Enviados/';
 	    $ruta_rechazados =dirname(__DIR__).'/SRI/ftp_folder_xmls/Rechazados/';
    	$enviar_sri = dirname(__DIR__).'/SRI/firmar/JavClientSri.jar';

    	if(!file_exists($ruta_firmados.$clave_acceso.'.xml'))
    	{
    		$respuesta = ' XML firmado no encontrado';
	 		return $respuesta;
    	}

    	if(!file_exists($ruta_firmados.$clave_acceso.'.xml'))
    	{
    		$respuesta = ' XML firmado no encontrado';
	 		return $respuesta;
    	}
		
		$command = $this->rutaJava8."java -jar ".$enviar_sri." 1 ".$clave_acceso." ".$ruta_firmados." ".$ruta_enviados." ".$ruta_rechazados." ".$url_recepcion; 
   		 // print_r($command);die();

   		$output = shell_exec($command);
   		if($output!=null && $output!='')
   		{
   			$output = mb_convert_encoding($output, 'UTF-8', 'ISO-8859-1');
   		}
   		$output = json_decode($output,true);
   		// print_r($output);die();
   		return $output;
    }


    function validar_existencia($xml)
    {    
		$clave = $xml.'.xml';

		$carpeta_comprobantes = dirname(__DIR__).'/SRI/ftp_folder_xmls';
		$carpeta_no_autori = $carpeta_comprobantes . "/No_autorizados";
		$carpeta_rechazados = $carpeta_comprobantes . "/Rechazados";
		$carpeta_enviados = $carpeta_comprobantes . "/Enviados";



		$ruta1 = $carpeta_no_autori . '/' . $clave;
		$ruta2 = $carpeta_rechazados . '/' . $clave;

		$ruta3 = $carpeta_enviados . '/' . $clave;

		// print_r($ruta1);print_r($ruta2);die();

		if (file_exists($ruta1)) {

			// print_r($ruta1);die();
			$xml = simplexml_load_file($ruta1);
			$codigo = $xml->mensajes->mensaje->mensaje->identificador;
			$mensaje = $xml->mensajes->mensaje->mensaje->mensaje;
			$adicional = $xml->mensajes->mensaje->mensaje->informacionAdicional;
			$estado = $xml->estado;
			$fecha = $xml->fechaAutorizacion;
			// print_r($mensaje);die();
			// print_r($xml);die();
			return strval($adicional);
			// array('estado' => $estado, 'codigo' => $codigo, 'mensaje' => $mensaje, 'adicional' => $adicional, 'fecha' => $fecha);
		}

		if (file_exists($ruta2)) {
			// print_r($ruta2);die();
			$fp = fopen($ruta2, "r");
			$linea = '';
			while (!feof($fp)) {
				$linea .= fgets($fp);
			}
			fclose($fp);
			$linea = str_replace('ns2:', '', $linea);
			$xml = simplexml_load_string($linea);

			$codigo = $xml->respuestaSolicitud->comprobantes->comprobante->mensajes->mensaje->identificador;
			$mensaje = $xml->respuestaSolicitud->comprobantes->comprobante->mensajes->mensaje->mensaje;
			$adicional = $xml->respuestaSolicitud->comprobantes->comprobante->mensajes->mensaje->informacionAdicional;
			$estado = $xml->respuestaSolicitud->estado;
			$fecha = '';
			// print_r($mensaje);die();
			return strval($mensaje[0]);

			// array('estado' => $estado, 'codigo' => $codigo, 'mensaje' => $mensaje, 'adicional' => $adicional, 'fecha' => $fecha);

		}

		if(!file_exists($ruta3))
		{
			// no se pudo enviar por algun motivo
			return 'false';
		}
	}


	function subirftp($nombre_file)
	{
		$archivo = $nombre_file.'.xml';		
		$archivo_remoto = '/files/ComprobantesElectronicos/Autorizados/'.$archivo;

		// print_r($nombre_file);die();

		$nombre_file = dirname(__DIR__)."/SRI/ftp_folder_xmls/Autorizados/".$archivo; 
		// print_r($nombre_file);die();
		if(!file_exists($nombre_file))
		{
			$archivo_remoto = '/files/ComprobantesElectronicos/No_Autorizados/'.$archivo;
			$nombre_file = dirname(__DIR__)."/SRI/ftp_folder_xmls/Rechazados/".$archivo; 
			if(!file_exists($nombre_file))
			{
				$nombre_file = dirname(__DIR__)."/SRI/ftp_folder_xmls/No_autorizados/".$archivo; 
				if(!file_exists($nombre_file))
				{
					echo "No subido";
				}
			}

		}

		// print_r($archivo_remoto."--".$archivo_local);die();
		// Datos de conexión
		// print_r($nombre_file);die();
		$ftp_host = "erp.diskcoversystem.com";
		$ftp_user = "ftpuser";
		$ftp_pass = "ftp2023User";
		$ftp_port = 21; // Cambia al puerto que necesites
// print_r($nombre_file);die();
		$archivo_local = $nombre_file;    // Cómo se llamará en el servidor FTP

		// Conectar al servidor FTP
		// print_r($archivo_remoto."--".$archivo_local);die();
		$conn_id = ftp_connect($ftp_host);

		if ($conn_id) {
		    // Autenticarse
		    if (ftp_login($conn_id, $ftp_user, $ftp_pass)) {
		        ftp_pasv($conn_id, true); // Modo pasivo recomendado

		        // Subir archivo
		        if (ftp_put($conn_id, $archivo_remoto, $archivo_local, FTP_BINARY)) {
		           return 1;
		        } else {
		        	return -1;
		        }

		        // Cerrar conexión
		        ftp_close($conn_id);
		    } else {
		        // echo "Error al iniciar sesión FTP.";
		        return -2;
		    }
		} else {
		    // echo "No se pudo conectar al servidor FTP.";
		    return -3;
		}

	}

	function subirftpFirmado($nombre_file)
	{
		$archivo = $nombre_file.'.xml';		
		$archivo_remoto = '/files/ComprobantesElectronicos/Firmados/'.$archivo;

		// print_r($nombre_file);die();

		$nombre_file = dirname(__DIR__)."/SRI/ftp_folder_xmls/Firmados/".$archivo; 
		// print_r($nombre_file);die();
		if(file_exists($nombre_file))
		{
		
			// print_r($archivo_remoto."--".$archivo_local);die();
			// Datos de conexión
			// print_r($nombre_file);die();
			$ftp_host = "erp.diskcoversystem.com";
			$ftp_user = "ftpuser";
			$ftp_pass = "ftp2023User";
			$ftp_port = 21; // Cambia al puerto que necesites

			$archivo_local = $nombre_file;    // Cómo se llamará en el servidor FTP

			// Conectar al servidor FTP
			// print_r($archivo_remoto."--".$archivo_local);die();
			$conn_id = ftp_connect($ftp_host);

			if ($conn_id) {
			    // Autenticarse
			    if (ftp_login($conn_id, $ftp_user, $ftp_pass)) {
			        ftp_pasv($conn_id, true); // Modo pasivo recomendado

			        // Subir archivo
			        if (ftp_put($conn_id, $archivo_remoto, $archivo_local, FTP_BINARY)) {
			           return 1;
			        } else {
			        	return -1;
			        }

			        // Cerrar conexión
			        ftp_close($conn_id);
			    } else {
			        // echo "Error al iniciar sesión FTP.";
			        return -2;
			    }
			} else {
			    // echo "No se pudo conectar al servidor FTP.";
			    return -3;
			}
		}

	}

	function borrar_xml_file($xml)
	{
		$temp_file = 'ftp_folder_xmls/Enviados/'.$xml;
		unlink($temp_file);
		$temp_file = 'ftp_folder_xmls/Firmados/'.$xml;
		unlink($temp_file);
		$temp_file = 'ftp_folder_xmls/Rechazados/'.$xml;
		unlink($temp_file);
		$temp_file = 'ftp_folder_xmls/No_autorizados/'.$xml;
		unlink($temp_file);
		$temp_fileF = 'ftp_folder_xmls/Generados/'.$xml;
		unlink($temp_file);

	}



 // function enviaremail()   //funcion para enviarlo por javascript
 //  { 


 //          const xhr = new XMLHttpRequest();
 //          // const url =  'https://erp.diskcoversystem.com/~diskcover/lib/phpmailer/EnvioEmailvisual.php?EnviarVisual';
 //            const url =  '../../php/comprobantes/SRI/autorizar_sri_visual.php?AutorizarXMLOnline=true';


 //          xhr.open('POST', url, true);
 //          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

 //          xhr.onreadystatechange = function () {
 //            if (xhr.readyState === 4 && xhr.status === 200) {
 //              console.log('Respuesta:', xhr.responseText);
 //            }
 //          };

 //           const params = `XML=0704202503070216417900110010030000000061234567811.xml&RUTA=walter_jalil_vaca_prieto_natural_2020_09_10.p12&PASS=Dlcjvl1210`;


 //          xhr.send(params);
 //  }



} 
?>