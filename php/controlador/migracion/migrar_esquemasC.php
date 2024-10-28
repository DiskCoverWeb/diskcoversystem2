<?php 

    if(isset($_GET['subir_archivo'])){
        $ftp_server = "db.diskcoversystem.com";
        $ftp_username = "ftpuser";
        $ftp_password = "ftp2023User";

        //FTP Connection
		$connId = ftp_connect($ftp_server);

		//Login to FTP
		$ftpLogin = ftp_login($connId, $ftp_username, $ftp_password);

		ftp_pasv($connId, true);

        $localFilePath  = $_FILES['archivo'];
		$carpetaDestino = dirname(__DIR__, 3) . "/TEMP/";
		$nombreArchivoDestino = $carpetaDestino . basename($localFilePath['name']);//Destino temporal para guardar el archivo
		$remoteFilePath = '/files/' . $localFilePath['name'];//Path donde se va a guardar el archivo
        
        // Verifica si se ha subido un archivo
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {

            if (!is_dir($carpetaDestino)) {
                // Intentar crear la carpeta, el 0777 es el modo de permiso más permisivo
                if (!mkdir($carpetaDestino, 0777, true)) { // true permite la creación de estructuras de directorios anidados
					ftp_close($connId);
                    throw new Exception("No se pudo crear la carpeta");
                }
            }

            if (move_uploaded_file($localFilePath['tmp_name'], $nombreArchivoDestino)) {
				if(ftp_put($connId, $remoteFilePath, $nombreArchivoDestino, FTP_BINARY)){
					ftp_close($connId);
					echo json_encode(array("res" => 1, "mensaje" => "El archivo se ha subido correctamente a la ruta '".$remoteFilePath."'."));
				}else{
					ftp_close($connId);
					throw new Exception("No se pudo guardar el archivo en el servidor FTP");
				}
				ftp_close($connId);
            } else {
				ftp_close($connId);
                throw new Exception("No se pudo guardar el archivo en el servidor");
            }
        
        /*ftp_chdir($connId, '/files');
        $file_list = ftp_nlist($connId, ".");

        print_r($file_list);die();*/

        } else {
            ftp_close($connId);
			throw new Exception("Error al subir el archivo");
        }
    }
?>