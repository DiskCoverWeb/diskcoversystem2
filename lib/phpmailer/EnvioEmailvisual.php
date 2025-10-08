<?php 

@session_start();
require_once(dirname(__DIR__,2)."/php/funciones/funciones.php");

header("Access-Control-Allow-Origin: *"); // Permite todas las orígenes, puedes restringir a uno específico
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Encabezados permitidos


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';


if(isset($_GET['EnviarVisual']))
{
	// print_r("hola");die();
	$controlor = new EnviarVisual();
	$parametros = $_POST;
	echo json_decode($controlor->EnvioEmailVisual($parametros));
}

if(isset($_GET['EnviarVisualNew']))
{
	// print_r("hola");die();
	$controlor = new EnviarVisual();
	$parametros = $_POST;
	$controlor->EnvioEmailVisualNew($parametros);
	// );
}

if(isset($_GET['PruebaEmail']))
{
	$controlor = new EnviarVisual();
	$parametros = $_GET['to'];
	echo json_decode($controlor->Emaildebug($parametros));
}

class EnviarVisual 
{
	
	function __construct()
	{
		// code...
	}

	function EnvioEmailVisual($parametros)
	{
		// print_r($parametros);die();

		$temp_file = 'ftp_folder_visual/';
		if(trim($parametros['Archivo'])!='')
		{
			$this->descargar_archivos_ftp($parametros['Archivo']);
		}

		$_SESSION['INGRESO']['item'] = $parametros['item'];
  	$_SESSION['INGRESO']['modulo_'] = $parametros['modulo'];
  	$_SESSION['INGRESO']['CodigoU'] = $parametros['CodigoU'];
    $_SESSION['INGRESO']['RUC'] = $parametros['RUCEmpresa'];
    $_SESSION['INGRESO']['periodo'] = '.';
    $_SESSION['INGRESO']['Email_Conexion'] = '.';
    $_SESSION['INGRESO']['Nombre'] = $parametros['Nombre'];


    	$to_correo = trim($parametros['to']);
    	$to_correo = str_replace(';',',',$to_correo);
    	$to = explode(',', $to_correo);

    	$list_delete = array();
    	foreach ($to as $key => $value) 
    	{
     		if ($value != '.' && $value != '') 
     		{
	        	$mail = new PHPMailer(true);
	        	$mail->SMTPOptions = array(
	          	'ssl' => array(
	            	'verify_peer' => false,
	            	'verify_peer_name' => false,
	            	'allow_self_signed' => true
		          	)
		        );

	        try {
		          //Server settings
	        	$mail->isSMTP(); //Send using SMTP
	        	if(isset($parametros['debug']) && $parametros['debug']==1)
	        	{
		          $mail->SMTPDebug = SMTP::DEBUG_SERVER;     
		        }  
		        $mail->Helo = 'imap.diskcoversystem.com';
				    $mail->Host = 'imap.diskcoversystem.com';
				    $mail->SMTPAuth = true;
				    $mail->Username = 'admin';
				    $mail->Password = 'Admin@2023';
				    $mail->SMTPSecure = 'tls';
				    $mail->Port = 587;
	         
			        $from = $parametros['from'];
			        $fromName = $parametros['fromName']; 
			        $reply = $from;
			        if(isset($parametros['reply']) && trim($parametros['reply'])!='')
			        {
			        	$reply = $parametros['reply']; 
			        }
			        $email_to = $value;
			        $replyName = $parametros['replyName']; 
			        $mail->addAddress($value);  
			        $mail->setFrom($from,$fromName );
			        $mail->addReplyTo($reply, $replyName);
			          //$mail->addCC('cc@example.com');
			          //$mail->addBCC('bcc@example.com');

			          // Attachments
			          if (trim($parametros['Archivo'])!='') {

			          	  $archivos = explode(';',$parametros['Archivo']);
			              foreach ($archivos as $key2 => $value2) {
			              	$list_delete[] = $temp_file.$value2;
			                $mail->AddAttachment($temp_file.$value2);              
			            }
			          }
			          //Content
			          if ($parametros['HTML']) {
			            $mail->isHTML(true);
			          } 
			          $mail->Subject = $parametros['subject'];
			          $mail->Body =  $parametros['body'];
			          if(strlen($from)>3)
			          {
			            if ($mail->send()) {
			              $res = 1;
			              // control_procesos("EMW", "Email: ".trim($from)."=>".trim($email_to), "Asunto: ".$parametros['subject']);
			            }
			          }


		        } catch (Exception $e) {
		          // print_r($mail);
		        
			          control_procesos("EMW", "Email: ".trim($from)." => ".trim($email_to), "Asunto(Error): ".$e);
			          return -1;
		        }
	     	}
    	}

			control_procesos("EMW", "Email: ".trim($parametros['from'])." => ".trim($parametros['to']), "Asunto(Error): ");
    	
    	foreach ($list_delete as $key => $value) {
    		if (file_exists($value)) {
	        	unlink($value);
	    	}
    	}

    	return $res;
	}


	function descargar_archivos_ftp($archivos)
	{

		// print_r($archivos);die();
		//proceso para envio de archivo por ftp 
		$ftp_host = "erp.diskcoversystem.com";
		$ftp_user = "ftpuser";
		$ftp_pass = "ftp2023User";
		$ftp_port = 21; // Cambia al puerto que necesites

		$remote_file = '/files/AddAttachment/';
		$temp_file = 'ftp_folder_visual/';
		$remote_path = '/';

		if(!file_exists($temp_file))
		{
			mkdir($temp_file, 0777);
		}

		$ftp_conn = ftp_connect($ftp_host, $ftp_port) or die("No se pudo conectar al servidor FTP.");
		$login = ftp_login($ftp_conn, $ftp_user, $ftp_pass);

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
		foreach ($archi as $key => $value) {
			ftp_get($ftp_conn, $temp_file.$value, $remote_file.$value, FTP_BINARY);			
		}
		ftp_close($ftp_conn);



	}


	function Emaildebug($value)
	{
	    
	  $mail = new PHPMailer(true);
  	$mail->SMTPOptions = array(
    	'ssl' => array(
      	'verify_peer' => false,
      	'verify_peer_name' => false,
      	'allow_self_signed' => true
      	)
    );
	  try {
		          //Server settings
	        $mail->isSMTP(); //Send using SMTP
	        $mail->SMTPDebug = SMTP::DEBUG_SERVER;     
	        $mail->Helo = 'imap.diskcoversystem.com';
			    $mail->Host = 'imap.diskcoversystem.com';
			    $mail->SMTPAuth = true;
			    $mail->Username = 'admin';
			    $mail->Password = 'Admin@2023';
			    $mail->SMTPSecure = 'tls';
			    $mail->Port = 587; 
			   
	        $mail->setFrom('admin@imap.diskcoversystem.com','Mailer');
	        $mail->addAddress($value);  
			   
			    $mail->isHTML(true);                                  //Set email format to HTML
			    $mail->Subject = 'Here is the subject';
			    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
   
			       
          if ($mail->send()) {
            $res = 1;
          }
      


	        } catch (Exception $e) {
	          // print_r($mail);
	          // print_r($e);
	          // die();
	          // control_procesos("EMW", "Email: ".trim($from)." => ".trim($email_to), "Asunto(Error): ".$e);

	          print_r("=====================================parametos ===================================================\n");
	          print_r($mail);
	          // return -1;
	        }   

	}


	function EnvioEmailVisualNew($parametros)
	{

		$ftp_server = "erp.diskcoversystem.com";
		$ftp_user = "ftpuser";
		$ftp_pass = "ftp2023User";
		$ftp_port = 21; // Cambia al puerto que necesites

		$remote_file = '/files/AddAttachment/';
		$temp_file = 'ftp_folder_visual/';
		$remote_path = '/';

		$dataArchivos = explode(";",$parametros['Archivo']);
		if(count($dataArchivos)>0)
		{
			$archivosAdjuntos = array();
			foreach ($dataArchivos as $key => $value) {
					$tipo_archivo = explode(".", $value);
			    $extension = strtolower(trim($tipo_archivo[1]));

			    if ($extension == 'pdf') {
			        $archivosAdjuntos["/files/AddAttachment/ANEXO_1.pdf"] = '';
			    } elseif ($extension == 'xml') {
			        $archivosAdjuntos["/files/ComprobantesElectronicos/$value"] = $value;
			    }
			}
		}


		// Conectar al servidor FTP
		$conn_id = ftp_connect($ftp_server,$ftp_port);
		$login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);

		if (!$conn_id || !$login_result) {
		    die("❌ No se pudo conectar al servidor FTP");
		}

		// Crear PHPMailer
		$mail = new PHPMailer(true);
  	$mail->SMTPOptions = array(
    	'ssl' => array(
      	'verify_peer' => false,
      	'verify_peer_name' => false,
      	'allow_self_signed' => true
      	)
    );



		try {
    // Configurar servidor SMTP
			$to_correo = trim($parametros['to']);
    	$to_correo = str_replace(';',',',$to_correo);
    	$to = explode(',', $to_correo);
			foreach ($to as $key => $value) 
    	{
						$mail->isSMTP(); //Send using SMTP
	        	if(isset($parametros['debug']) && $parametros['debug']==1)
	        	{
		          $mail->SMTPDebug = SMTP::DEBUG_SERVER;     
		        }  

          	$mail->SMTPDebug = SMTP::DEBUG_SERVER; 
		        $mail->Helo = 'imap.diskcoversystem.com';
				    $mail->Host = 'imap.diskcoversystem.com';
				    $mail->SMTPAuth = true;
				    $mail->Username = 'admin';
				    $mail->Password = 'Admin@2023';
				    $mail->SMTPSecure = 'tls';
				    $mail->Port = 587;

  					$from = $parametros['from'];
			      $fromName = $parametros['fromName']; 
			      $reply = $from;
			      if(isset($parametros['reply']) && trim($parametros['reply'])!='')
			        {
			        	$reply = $parametros['reply']; 
			        }
			      
			      $email_to = $value;
			      $replyName = $parametros['replyName']; 
			      $mail->addAddress($value);  
			      $mail->setFrom($from,$fromName );
			      $mail->addReplyTo($reply, $replyName);
			      
			      if ($parametros['HTML']) 
			      {
			          $mail->isHTML(true);
			      } 
			      $mail->Subject = $parametros['subject'];
			      $mail->Body =  $parametros['body'];


				    // Descargar y adjuntar archivos
				    foreach ($archivosAdjuntos as $ruta_remota => $nombre_local) {
				        $tempHandle = fopen('php://temp', 'r+');
				        if (ftp_fget($conn_id, $tempHandle, $ruta_remota, FTP_BINARY, 0)) {
				            rewind($tempHandle);
				            $contenido = stream_get_contents($tempHandle);
				            fclose($tempHandle);

				            // Detectar MIME según extensión
				            $mime = $this->mime_content_type_from_extension($nombre_local);

				            $mail->addStringAttachment($contenido, $nombre_local, 'base64', $mime);
				        } else {
				            fclose($tempHandle);
				            echo "⚠️ No se pudo leer: $ruta_remota<br>";
				        }
				    }

				    ftp_close($conn_id);

				    // print_r($mail);die();

		    // Enviar correo
		    $mail->send();
		  }
		} catch (Exception $e) {
		    echo " Error al enviar: {$mail->ErrorInfo}";
		}
}

// // Función auxiliar para tipo MIME
function mime_content_type_from_extension($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return match ($ext) {
        'pdf' => 'application/pdf',
        'xml' => 'application/xml',
        'txt' => 'text/plain',
        'jpg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        default => 'application/octet-stream',
    };
 }




// 	function enviaremail()   funcion para enviarlo por javascript
  // { 


  //         const xhr = new XMLHttpRequest();
  //         const url =  'https://erp.diskcoversystem.com/~diskcover/lib/phpmailer/EnvioEmailvisual.php?EnviarVisual';

  //         xhr.open('POST', url, true);
  //         xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  //         xhr.onreadystatechange = function () {
  //           if (xhr.readyState === 4 && xhr.status === 200) {
  //             console.log('Respuesta:', xhr.responseText);
  //           }
  //         };

  // //          const params = `from=admin@imap.diskcoversystem.com
  //         								&fromName=CORREO DESDE 192.168.20.3 RELAYHOST IMAP <admin@imap.diskcoversystem.com>
  //                         &to=javier.farinango92@gmail.com;diskcoversystem@msn.com
  //                         &body=juan@ejemplo.com
  //                         &subject=hola email como estas
  //                         &HTML=1
  //                         &Archivo=
  //                         &reply=
  //                         &replyName=
  //                         &debug=0`;

  //         xhr.send(params);
  // }
}
?>