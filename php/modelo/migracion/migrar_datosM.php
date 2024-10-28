<?php 
/**
 * Autor: JAVIER FARINANGO.
 * Mail:  
 * web:   www.diskcoversystem.com
 */

require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

/**
 * 
 */
class migrar_datosM
{
	
	private $db;	
	function __construct()
	{
	    $this->db = new db();
	}

	function generarArchivos($link)
	{
			set_time_limit(0);
	    	ini_set('memory_limit', '1024M');
	  	  $respuesta = 1;
	    
	      $usuario = $_SESSION['INGRESO']['Usuario_DB'];
	      $password = $_SESSION['INGRESO']['Password_DB'];  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	      $servidor = $_SESSION['INGRESO']['IP_VPN_RUTA'];     
	      $database = $_SESSION['INGRESO']['Base_Datos'];
	      $puerto = $_SESSION['INGRESO']['Puerto'];
		$sql = "SELECT TABLE_SCHEMA, TABLE_NAME
	           FROM INFORMATION_SCHEMA.TABLES
	           WHERE TABLE_TYPE = 'BASE TABLE' ORDER BY TABLE_NAME ASC";
	    		$tablas_base = $this->db->datos($sql);
	    		// print_r($tablas_base);die();



	    $contenido = '';
	    foreach ($tablas_base as $key => $value) 
	    {
		    	$sql = 'SELECT Count(*) as total FROM '.$value['TABLE_NAME'];
		    	$datos =  $this->db->datos($sql);
		    	$query_select = '';
		    	$informe_cabe = '';
		    	if($datos[0]['total']>0)
		    	{
		    		$contenido.='REPLACE INTO `'.$value['TABLE_NAME'].'` (';
		    		//buscamos las cabeceras de las tablas
		       		$sql2 = "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
		                 FROM INFORMATION_SCHEMA.COLUMNS 
		                 WHERE TABLE_NAME ='" . $value['TABLE_NAME'] . "'"; 
		        	$cabeceras_tabla = $this->db->datos($sql2);

		        	//recore cabeceras
		        	foreach ($cabeceras_tabla as $key2 => $value2) {
		        		$contenido.="`".$value2['COLUMN_NAME']."`,";
		        		 $tipoCampo = $value2['DATA_TYPE'];
		                switch ($tipoCampo) {
		                    case 'char':
		                    case 'nvarchar':
		                    case 'varchar':
		                    case 'datetime':
		                        $query_select.="CONCAT('''',".$value2['COLUMN_NAME'].",'''') +',' ,";        
		                        break;
		                    default:
		                        $query_select.=$value2['COLUMN_NAME'].',';     
		                        break;
		                }
		                $informe_cabe.= '`'.$value2['COLUMN_NAME'].'`,';
		        	}
		        	// $contenido = substr($contenido,0,-1);		        	
		        	$query_select = substr($query_select,0,-1); 	
		        	$informe_cabe = substr($informe_cabe,0,-1);
		        	// $contenido.=") VALUES". PHP_EOL;


		        	$sql2 = "SELECT CONCAT('(',".$query_select.",'),' ) as linea FROM ".$value['TABLE_NAME'];

		        	$comando = 'sqlcmd -S '.$servidor.','.$puerto.' -U '.$usuario.' -P '.$password.' -d '.$database.' -Q "EXEC sp_helptext; SET NOCOUNT ON;SET QUOTED_IDENTIFIER OFF;'.$sql2.';" -o "'.$link.'Z'.$value['TABLE_NAME'].'.sql" -W -s"," -w 7000';

		        	exec($comando, $output, $return_var);

					if ($return_var === 0) {

						$archivoOriginal = $link.'Z' . $value['TABLE_NAME'] . '.sql';
					    // Ruta del nuevo archivo
					    $archivoNuevo = $link.'Z' . $_SESSION['INGRESO']['Base_Datos'] . '.sql';

					    // Texto a agregar al inicio del nuevo archivo
					    $textoNuevo = "REPLACE INTO `" . $value['TABLE_NAME'] . "` (" .$informe_cabe. ") VALUES\n";

					    // Leer el archivo original
					    $lineas = file($archivoOriginal);

					    // Eliminar las primeras 4 líneas
					    $lineasModificadas = array_slice($lineas, 4);

					    // Agregar el nuevo texto al inicio del contenido
					    $contenidoFinal = $textoNuevo . implode('', $lineasModificadas);

					    // Agregar el contenido final al archivo nuevo
					    file_put_contents($archivoNuevo, $contenidoFinal, FILE_APPEND | LOCK_EX);
					    unlink($archivoOriginal);

					    // die();
					} else {
					    echo "Hubo un problema al crear el archivo de respaldo.";
					    print_r($return_var);
					}

		        	// print_r($comando);die();
		        	// print_r($sql2);die();
		        }
		}

	}

	function generarArchivos2($link)
	{
			set_time_limit(0);
	    	ini_set('memory_limit', '1024M');
	  	  $respuesta = 1;
	    
	      $usuario = $_SESSION['INGRESO']['Usuario_DB'];
	      $password = $_SESSION['INGRESO']['Password_DB'];  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	      $servidor = $_SESSION['INGRESO']['IP_VPN_RUTA'];     
	      $database = $_SESSION['INGRESO']['Base_Datos'];
	      $puerto = $_SESSION['INGRESO']['Puerto'];

		  $serverName =$servidor.','.$puerto;
		  $connectionOptions = array(
		      "Database" => $database,
		      "Uid" => $usuario,
		      "PWD" => $password, 
		  );


		  //busca las tablas que donforman la base de datos
	   		$sql = "SELECT TABLE_SCHEMA, TABLE_NAME
	           FROM INFORMATION_SCHEMA.TABLES
	           WHERE TABLE_TYPE = 'BASE TABLE' ORDER BY TABLE_NAME ASC";
	    		$tablas_base = $this->db->datos($sql);
	    		// print_r($tablas_base);die();



	    $contenido = '';
	    foreach ($tablas_base as $key => $value) 
	    {
		    	$sql = 'SELECT Count(*) as total FROM '.$value['TABLE_NAME'];
		    	$datos =  $this->db->datos($sql);
		    	$query_select = '';
		    	if($datos[0]['total']>0)
		    	{
		    		$contenido.='REPLACE INTO `'.$value['TABLE_NAME'].'` (';
		    		//buscamos las cabeceras de las tablas
		       		$sql2 = "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
		                 FROM INFORMATION_SCHEMA.COLUMNS 
		                 WHERE TABLE_NAME ='" . $value['TABLE_NAME'] . "'"; 
		        	$cabeceras_tabla = $this->db->datos($sql2);
		        	foreach ($cabeceras_tabla as $key2 => $value2) {
		        		$contenido.="`".$value2['COLUMN_NAME']."`,";
		        		 $tipoCampo = $value2['DATA_TYPE'];
		                switch ($tipoCampo) {
		                    case 'char':
		                    case 'nvarchar':
		                    case 'varchar':
		                    case 'datetime':
		                        $query_select.="CONCAT('''',".$value2['COLUMN_NAME'].",'''') +',' ,";        
		                        break;
		                    default:
		                        $query_select.=$value2['COLUMN_NAME'].',';     
		                        break;
		                }
		        	}
		        	$contenido = substr($contenido,0,-1);		        	
		        	$query_select = substr($query_select,0,-1);
		        	$contenido.=") VALUES". PHP_EOL;


		        	$sql2 = "SELECT CONCAT( ".$query_select." ) as linea FROM ".$value['TABLE_NAME'];
			        $datos2  = $this->db->datos($sql2);

			        foreach ($datos2 as $key3 => $value3) {
			        	$contenido.="(".$value3['linea']."),". PHP_EOL;;
			        }

			        $contenido = rtrim($contenido, "\r\n");
		        	$contenido = substr($contenido,0,-1);	
		        	$contenido = $contenido.';'. PHP_EOL;


		        	$contenido.= "-- Volcando datos para la tabla ".$_SESSION['INGRESO']['Base_Datos'].".".$value['TABLE_NAME']. PHP_EOL;

		    	}

	    }

	    $outputFile = $link."/Migracion_".$_SESSION['INGRESO']['item'].".sql";
		if (file_put_contents($outputFile, $contenido) !== false) {
		    echo "Archivo creado y datos escritos con éxito.";
		    die();
		} else {
		    echo "Error al escribir en el archivo.";
		}



	    return $respuesta;


	}



	function generarSP($link)
	{
		set_time_limit(0);
	   	ini_set('memory_limit', '1024M');
	   	$resp = 1;	   	
	    $usuario = $_SESSION['INGRESO']['Usuario_DB'];
	    $password = $_SESSION['INGRESO']['Password_DB'];  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	    $servidor = $_SESSION['INGRESO']['IP_VPN_RUTA'];     
	    $database = $_SESSION['INGRESO']['Base_Datos'];
	    $puerto = $_SESSION['INGRESO']['Puerto'];

		$sql = "SELECT p.name AS sp,  m.definition AS Definition
				FROM  ".$database.".sys.procedures p
				INNER JOIN ".$database.".sys.sql_modules m ON p.object_id = m.object_id";
		$datosSP = $this->db->datos($sql);

		foreach ($datosSP as $key => $value) {
			$rutaArchivo = $link.$value['sp'].".txt";
			$contenido = $value['Definition'];

			$archivo = fopen($rutaArchivo, 'w');

			if ($archivo) {
			    // Escribir el contenido en el archivo
			   fwrite($archivo, $contenido);
			    fclose($archivo);

			} else {
				$resp = 0;
			}
		}

		return $resp;
	}
	function Enviar_ftp($link,$archivo)
	{

		// $this->leer_ftp($link,'142.txt');
		// die();
		$ftp_server = "db.diskcoversystem.com";
		$ftp_user_name = "ftpuser";
		$ftp_user_pass = "ftp2023User";
		$ftp_port = 21; // Cambia al puerto que necesites


		$link_remoto = '/files/';
		$file_local_upload = $link; // Ruta local del archivo a subir
		$file_remote_upload = $link_remoto .$archivo; // Nombre del archivo remoto


		// print_r($file_local_upload.'--'.$file_remote_upload);die();

		// Conectar al servidor FTP en el puerto especificado
		$conn_id = ftp_connect($ftp_server, $ftp_port);

		// Autenticarse con el usuario y la contraseña
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

		// Comprobar la conexión
		if ((!$conn_id) || (!$login_result)) {
		    die("No se pudo conectar al servidor FTP con los detalles proporcionados.");
		}
		echo "Conectado a $ftp_server en el puerto $ftp_port, como $ftp_user_name\n";

		// Subir el archivo
		if (ftp_put($conn_id, $file_remote_upload, $file_local_upload, FTP_BINARY)) {
		    echo "El archivo $file_local_upload se ha subido satisfactoriamente como $file_remote_upload.\n";
		} else {
		    echo "Hubo un problema al subir el archivo $file_local_upload.\n";
		}

		// Cerrar la conexión FTP
		ftp_close($conn_id);
	}

	function leer_ftp($link,$archivo)
	{
// Detalles del servidor FTP
$ftp_server = "db.diskcoversystem.com";
$ftp_user_name = "ftpuser";
$ftp_user_pass = "ftp2023User";
$ftp_port = 21; // Cambia al puerto que necesites

$link_remoto = '/home/ftpuser/ftp/files/';


$link_remoto = '/home/ftpuser/ftp/files/';
$file_remote_download = $link_remoto . '142.txt'; // Nombre del archivo remoto
$file_local_download = 'C:\\Users\\usuario\\Desktop\\Payload\\142.txt'; // Nombre del archivo remoto

// Conectar al servidor FTP en el puerto especificado
$conn_id = ftp_connect($ftp_server, $ftp_port);

// Autenticarse con el usuario y la contraseña
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// Comprobar la conexión
if ((!$conn_id) || (!$login_result)) {
    die("No se pudo conectar al servidor FTP con los detalles proporcionados.");
}
echo "Conectado a $ftp_server en el puerto $ftp_port, como $ftp_user_name\n";

// Verificar si la ruta local es escribible
if (!is_writable(dirname($file_local_download))) {
    die("El directorio local no es escribible: " . dirname($file_local_download));
}

// Descargar el archivo
if (ftp_get($conn_id, $file_local_download, $file_remote_download, FTP_BINARY)) {
    echo "El archivo $file_remote_download se ha descargado satisfactoriamente como $file_local_download.\n";
} else {
    echo "Hubo un problema al descargar el archivo $file_remote_download.\n";
}

// Cerrar la conexión FTP
ftp_close($conn_id);



	}

}

?>