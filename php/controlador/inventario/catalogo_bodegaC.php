<?php
include(dirname(__DIR__,2).'/modelo/inventario/catalogo_bodegaM.php');

$controlador = new catalogo_bodegaC();

if (isset($_GET['GuardarProducto'])) {
    $parametros = $_POST;
    
    // Verifica si se ha subido un archivo
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $localFilePath  = $_FILES['imagen'];
        $carpetaDestino = dirname(__DIR__, 3) . "/img/png/";
        $nombreArchivoDestino = $carpetaDestino . $parametros['picture'] . '.png';//Destino temporal para guardar el archivo

        /*if (!is_dir($carpetaDestino)) {
            // Intentar crear la carpeta, el 0777 es el modo de permiso más permisivo
            if (!mkdir($carpetaDestino, 0777, true)) { // true permite la creación de estructuras de directorios anidados
                throw new Exception("No se pudo crear la carpeta");
            }
        }*/

        if (move_uploaded_file($localFilePath['tmp_name'], $nombreArchivoDestino)) {
            echo json_encode($controlador->GuardarProducto($parametros));
        } else {
            throw new Exception("No se pudo guardar el archivo en el servidor");
        }
    
    } else {
        throw new Exception("Error al subir el archivo");
    }
}

if (isset($_GET['ListaProductos'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ListaProductos($parametros));
}

if (isset($_GET['EliminarProducto'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->EliminarProducto($parametros));
}

if (isset($_GET['EditarProducto'])) {
    $parametros = $_POST;
    
    // Verifica si se ha subido un archivo
    if (isset($_FILES['imagen'])) {
        if($_FILES['imagen']['error'] !== UPLOAD_ERR_OK){
            throw new Exception("Error al subir el archivo");
        }
        $localFilePath  = $_FILES['imagen'];
        $carpetaDestino = dirname(__DIR__, 3) . "/img/png/";
        $nombreArchivoDestino = $carpetaDestino . $parametros['picture'] . '.png';//Destino temporal para guardar el archivo
        /*if (!is_dir($carpetaDestino)) {
            // Intentar crear la carpeta, el 0777 es el modo de permiso más permisivo
            if (!mkdir($carpetaDestino, 0777, true)) { // true permite la creación de estructuras de directorios anidados
                throw new Exception("No se pudo crear la carpeta");
            }
        }*/

        if (move_uploaded_file($localFilePath['tmp_name'], $nombreArchivoDestino)) {
            echo json_encode($controlador->EditarProducto($parametros));
        } else {
            throw new Exception("No se pudo guardar el archivo en el servidor");
        }
    
    } else {
        $ruta = dirname(__DIR__, 3).'/img/png/';
        
        if(rename($ruta . $parametros['srcAnt'], $ruta . $parametros['picture'] . '.png')){
            echo json_encode($controlador->EditarProducto($parametros));
        }else {
            throw new Exception("No se pudo actualizar el archivo");
        }
    }
}

if (isset($_GET['ListaEliminar'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ListaEliminar($parametros));
}

if (isset($_GET['ListaTipo'])) {
    echo json_encode($controlador->ListaTipoProcesosGenerales());
}

if(isset($_GET['ListaTipoProcesosGeneralesAux'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ListaTipoProcesosGeneralesAux($parametros));

}

if(isset($_GET['SubirImagenTemp'])){
    $localFilePath  = $_FILES['imagen'];
    $carpetaDestino = dirname(__DIR__, 3) . "/TEMP/catalogo_procesos/";
    $nombreArchivoDestino = $carpetaDestino . basename($localFilePath['name']);//Destino temporal para guardar el archivo
    
    // Verifica si se ha subido un archivo
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

        if (!is_dir($carpetaDestino)) {
            // Intentar crear la carpeta, el 0777 es el modo de permiso más permisivo
            if (!mkdir($carpetaDestino, 0777, true)) { // true permite la creación de estructuras de directorios anidados
                throw new Exception("No se pudo crear la carpeta");
            }
        }

        if (move_uploaded_file($localFilePath['tmp_name'], $nombreArchivoDestino)) {
            $respuesta = array(
                "res" => 1,
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

class catalogo_bodegaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new catalogo_bodegaM();
    }

    function GuardarProducto($parametros)
    {    
        try {
            if($parametros['nivel'] == '00') $parametros['nivel'] = '0';
            $datos = $this->modelo->GuardarProducto($parametros); 
            Eliminar_Nulos_SP("Catalogo_Proceso");            
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron agregar los datos.');
        }                     
    }

    function ListaProductos($parametros)
    {    
        try {
            if($parametros['nivel'] == '00') $parametros['nivel'] = '0';
            $datos = $this->modelo->ListaProductos($parametros);             
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron listar los datos.');
        }                     
    }

    function EliminarProducto($parametros) 
    {        
        try {
            if($parametros[0]['Nivel'] == '00') $parametros[0]['Nivel'] = '0';
            $this->modelo->EliminarProducto($parametros);
            return array('status' => '200', 'msj' => 'Se elimino correctamente');
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron eliminar los datos.');
        }
    }

    function EditarProducto($parametros)
    {
        try {
            if($parametros['nivel'] == '00') $parametros['nivel'] = '0';
            $this->modelo->EditarProducto($parametros);            
            return array('status' => '200', 'msj' => 'Se actualizo correctamente');
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron editar los datos.');
        }   
    }

    function ListaEliminar($parametros)
    {    
        try {
            if($parametros['nivel'] == '00') $parametros['nivel'] = '0';
            $datos = $this->modelo->ListaEliminar($parametros);             
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron listar los datos.');
        }                     
    }

    function ListaTipoProcesosGenerales(){
        try {
            $datos = $this->modelo->ListaTipoProcesosGenerales();
            $tmp = array('TP'=>'.','Proceso'=>'Seleccione un proceso');  
            array_unshift($datos, $tmp);           
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron listar los datos.');
        }
    }

    function ListaTipoProcesosGeneralesAux($parametros){
        $datos = $this->modelo->ListaTipoProcesosGeneralesAux($parametros);
        return $datos;
    }
}
?>