<?php
/** 
 * AUTOR DE RUTINA	: Teddy Moreira
 * MODIFICADO POR : Teddy Moreira
 * FECHA CREACION	: 10/03/2025
 * FECHA MODIFICACION : 11/03/2025
 * DESCIPCION : Clase controlador para Referenciales SRI
 */
include(dirname(__DIR__,2).'/modelo/seteos/referenciales_sriM.php');

$controlador = new referenciales_sriC();

if (isset($_GET['GuardarProducto'])) {
    $parametros = $_POST;
    echo json_encode($controlador->GuardarProducto($parametros));
}

if (isset($_GET['verificarProductos'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->verificarProductos($parametros));
}


if(isset($_GET['cargar_imgs']))
{
	echo json_encode($controlador->cargar_imgs());
}

if (isset($_GET['EliminarProducto'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->EliminarProducto($parametros));
}

if (isset($_GET['EditarProducto'])) {
    $parametros = $_POST;
    
    echo json_encode($controlador->EditarProducto($parametros));
}

if (isset($_GET['ListaEliminar'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ListaEliminar($parametros));
}

if(isset($_GET['ListaReferenciales'])){
    echo json_encode($controlador->ListaReferenciales());

}

if(isset($_GET['SubirImagenTemp'])){
    
    // Verifica si se ha subido un archivo
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $localFilePath  = $_FILES['imagen'];
        $carpetaDestino = dirname(__DIR__, 3) . "/TEMP/catalogo_procesos/";
        $nombreArchivoDestino = $carpetaDestino . basename($localFilePath['name']);//Destino temporal para guardar el archivo

        if (!is_dir($carpetaDestino)) {
            // Intentar crear la carpeta, el 0777 es el modo de permiso más permisivo
            if (!mkdir($carpetaDestino, 0777, true)) { // true permite la creación de estructuras de directorios anidados
                throw new Exception("No se pudo crear la carpeta");
            }
        }

        if (move_uploaded_file($localFilePath['tmp_name'], $nombreArchivoDestino)) {
            $respuesta = 1;
            if(file_exists(dirname(__DIR__, 3) . "/img/png/" . basename($localFilePath['name']))){
                $respuesta = 2;
            }
            $respuesta = array(
                "res" => $respuesta,
                "imagen" => $localFilePath['name']
            );
            echo json_encode($respuesta);
        } else {
            throw new Exception("No se pudo guardar el archivo en el servidor");
        }
    
    } else {
        throw new Exception("Error al subir el archivo");
    }
}

class referenciales_sriC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new referenciales_sriM();
    }

    function GuardarProducto($parametros)
    {    
        try {
            $datos = $this->modelo->GuardarProducto($parametros); 
            Eliminar_Nulos_SP("Tabla_Referenciales_SRI");            
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron agregar los datos.');
        }                     
    }

    function cargar_imgs()
	{
		$opciones = '';
        $directorio = dirname(__DIR__,3).'/img/png';
			$archivos = scandir($directorio);
			foreach ($archivos as $archivo) {
			    $rutaArchivo = $directorio . '/' . $archivo;
			    if (is_file($rutaArchivo) && pathinfo($rutaArchivo, PATHINFO_EXTENSION) === 'png') {
			    	$opciones.='<option value="'.$archivo.'">'.$archivo.'</option>';
			    }
			}   
		return $opciones;             
	}

    function verificarProductos($parametros)
    {
        try {
            $datos = $this->modelo->verificarProductos($parametros['codigo']);
            
            $res = [];
            if(count($datos) > 0){
                if(count($datos)>1){
                    $res = array_filter($datos, function($x) use($parametros) {
                        return $x['Tipo_Referencia'] == $parametros['tp'];
                    });
                }else{
                    $res = $datos;
                }
            }

            return array('status' => '200', 'datos' => $res);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron listar los datos.');
        }                     
    }

    function EliminarProducto($parametros) 
    {        
        try {
            $this->modelo->EliminarProducto($parametros);
            return array('status' => '200', 'msj' => 'Se elimino correctamente');
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron eliminar los datos.');
        }
    }

    function EditarProducto($parametros)
    {
        try {
            $this->modelo->EditarProducto($parametros);            
            return array('status' => '200', 'msj' => 'Se actualizo correctamente');
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron editar los datos.');
        }   
    }

    function ListaEliminar($parametros)
    {    
        try {
            $datos = $this->modelo->ListaEliminar($parametros);             
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron listar los datos.');
        }                     
    }

    function ListaReferenciales(){
        $data = $this->modelo->ListaReferenciales();
        return $data;
    }

    function buscarHijos($data, $codigo){
        $arreglo = [];
        
        foreach($data as $item){
            if($item['Nivel'] == $codigo){
                array_push($arreglo, $item);
            }
        }

        return $arreglo;
    }

}
?>