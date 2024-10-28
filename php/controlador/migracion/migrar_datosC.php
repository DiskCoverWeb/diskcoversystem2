<?php 
/**
 * Autor: JAVIER FARINANGO.
 * Mail:  
 * web:   www.diskcoversystem.com
 */

require_once(dirname(__DIR__,2). '/modelo/migracion/migrar_datosM.php');
$controlador = new migrar_datosC();

if(isset($_GET['generarArchivos']))
{
	echo json_encode($controlador->generarArchivos());
}
if(isset($_GET['generarSP']))
{
	echo json_encode($controlador->generarSP());
}


class migrar_datosC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new migrar_datosM();
	}

	
	function generarArchivos()
	{
		set_time_limit(0);
	    	ini_set('memory_limit', '1024M');
	    	
		$link_remo = '/files/Datos/';
		$link = dirname(__DIR__,3).'/TEMP/Datos_'.$_SESSION['INGRESO']['item'].'/';
		if(!file_exists(dirname(__DIR__,3).'/TEMP/'))
	   	{
	   		mkdir(dirname(__DIR__,3).'/TEMP/',0777,true);
	   	}
	   	if(!file_exists($link))
	   	{
	   		mkdir($link,0777,true);
	   	}	    

		$this->modelo->generarArchivos($link);
		// $this->Enviar_ftp($link,$link_remo);

		$archivo = $link.'Z'.$_SESSION['INGRESO']['Base_Datos'] . '.sql';

		// Verifica si el archivo existe
		if (file_exists($archivo)) {
		    // Establece las cabeceras para la descarga
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="' . basename($archivo) . '"');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($archivo));
		    
		    // Lee el archivo y lo envía al navegador
		    readfile($archivo);
		    exit;
		}

		
	}

	function generarSP()
	{
		$link_remo = '/files/SP/';
		$link = dirname(__DIR__,3).'/TEMP/SP_'.$_SESSION['INGRESO']['item'].'/';
	   	if(!file_exists(dirname(__DIR__,3).'/TEMP/'))
	   	{
	   		mkdir(dirname(__DIR__,3).'/TEMP/',0777,true);
	   	}
	   	if(!file_exists($link))
	   	{
	   		mkdir($link,0777,true);
	   	}	    

		$this->modelo->generarSP($link);
		$this->Enviar_ftp($link,$link_remo);

		// enviatr a ftp

	}

	function leer_carpeta($directory)
	{
		$files = scandir($directory);
		$files = array_diff($files, array('.', '..'));
		$lista = array();
			// Mostrar la lista de archivos
		foreach ($files as $file) {
		   $lista[] = $file;
		}

		return $lista;
	}

	function generarZip($carpetaName,$ruta)
	{
		$zip = new ZipArchive();
		$filename = $carpetaName.".zip";


		if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
		    exit("No se pudo abrir el archivo <$filename>\n");
		}

		// Ruta de la carpeta que deseas añadir al archivo .zip
		$carpeta =  $ruta;

		// Añadir la carpeta y su contenido al archivo .zip
		$this->agregarCarpetaAlZip($zip, $carpeta, basename($carpeta));

		// Cerrar el archivo .zip
		$zip->close();

		// Ofrecer el archivo .zip para su descarga
		if (file_exists($filename)) {
		    header('Content-Type: application/zip');
		    header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
		    header('Content-Length: ' . filesize($filename));
		    flush();
		    readfile($filename);
		    // Elimina el archivo .zip después de la descarga si no quieres guardarlo en el servidor
		    unlink($filename);
		    exit;
		} else {
		    exit("El archivo no existe.");
		}
	}

	

	function Enviar_ftp($link,$link_remo)
	{

		// $this->leer_ftp($link,'142.txt');
		// die();
		$ftp_server = "db.diskcoversystem.com";
		$ftp_user_name = "ftpuser";
		$ftp_user_pass = "ftp2023User";
		$ftp_port = 21; // Cambia al puerto que necesites


		$link_remoto = $link_remo; //'/files/SP/';		
		$conn_id = ftp_connect($ftp_server, $ftp_port);

		// Autenticarse con el usuario y la contraseña
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

		// Comprobar la conexión
		if ((!$conn_id) || (!$login_result)) {
		    die("No se pudo conectar al servidor FTP con los detalles proporcionados.");
		}
		$respuesta = 1;
		$datos = $this->leer_carpeta($link);
		foreach ($datos as $key => $value) {
			$file_local_upload = $link.$value; // Ruta local del archivo a subir
			$file_remote_upload = $link_remoto .$value; // Nombre del archivo remoto

			if (!ftp_put($conn_id, $file_remote_upload, $file_local_upload, FTP_BINARY)) {
			    echo "Hubo un problema al subir el archivo $file_local_upload.\n";
			    $respuesta = -1;
			}
			
		}
		ftp_close($conn_id);
		return $respuesta;
	}

}

?>