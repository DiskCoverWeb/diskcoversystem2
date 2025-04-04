<?php
include(dirname(__DIR__,2).'/modelo/inventario/catalogo_bodegaM.php');

$controlador = new catalogo_bodegaC();

if (isset($_GET['GuardarProducto'])) {
    $parametros = $_POST;
    /*print_r($_POST);
    print_r($_FILES);
    die();*/
    
    // Verifica si se ha subido un archivo
    if (isset($_FILES['imagen'])) {
        if($_FILES['imagen']['error'] === UPLOAD_ERR_OK){
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
    
    } else {
        echo json_encode($controlador->GuardarProducto($parametros));
    }
}

if (isset($_GET['verificarProductos'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->verificarProductos($parametros));
}

if (isset($_GET['ListaProductos'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ListaProductos($parametros));
}

if(isset($_GET['cargar_imgs']))
{
	echo json_encode($controlador->cargar_imgs());
}

if (isset($_GET['EliminarProducto'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->EliminarProducto($parametros));
}

if (isset($_GET['ListaCatalogoLineas'])) {
    //$parametros = $_POST['parametros'];
    echo json_encode($controlador->ListaCatalogoLineas());
}

if (isset($_GET['EditarProducto'])) {
    //print_r($_POST);die();
    $parametros = $_POST;
    // print_r($_POST);
    // print_r($_FILES);
    // die();
    
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
    
    } else if($parametros['srcAnt'] != '') {
        $ruta = dirname(__DIR__, 3).'/img/png/';
        
        if(rename($ruta . $parametros['srcAnt'], $ruta . $parametros['picture'] . '.png')){
            echo json_encode($controlador->EditarProducto($parametros));
        }else {
            throw new Exception("No se pudo actualizar el archivo");
        }
    }else{
        echo json_encode($controlador->EditarProducto($parametros));
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

if(isset($_GET['ListaTipoProcesosGeneralesCompleto'])){
    //$parametros = $_POST['parametros'];
    echo json_encode($controlador->ListaTipoProcesosGeneralesCompleto());

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

    function cargar_imgs()
	{
		$opciones = '';
        $directorio = dirname(__DIR__,3).'/img/png'; 
            // print_r($directorio);die();
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
        //$parametros['tp'] = explode(',', $parametros['tp']);
        //print_r($parametros['tp']); die();
        try {
            //if($parametros['nivel'] == '00') $parametros['nivel'] = '0';
            $datos = $this->modelo->verificarProductos($parametros['codigo']);
            
            $res = [];
            if(count($datos) > 0){
                if(count($datos)>1){
                    $res = array_filter($datos, function($x) use($parametros) {
                        return $x['TP'] == $parametros['tp'];
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

    function ListaProductos($parametros)
    {    
        $parametros['tp'] = explode(',', $parametros['tp']);
        //print_r($parametros['tp']); die();
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
        //$parametros['tp'] = explode(',', $parametros['tp']);

        try {
            //if($parametros['nivel'] == '00') $parametros['nivel'] = '0';
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

    function ListaTipoProcesosGeneralesCompleto(){
        $data = $this->modelo->ListaTipoProcesosGeneralesCompleto();
        /*$datos = $data;
        //$json = '...'; // Tu JSON aquí
        //$data = json_decode($datos, true);

        $tree = [];
        //$lookup = [];
        
        $raiz = array_shift($data);
        $raiz['children'] = [];
        
        // Primero, organizamos los elementos en un mapa de búsqueda por Cmds
        foreach ($data as $item) {
            if($item['Nivel'] == 0){
                //= [];
                $item['children'] = $this->buscarHijos($data, $item['Cmds']);
                array_push($raiz['children'], $item);
            }
            //$lookup[$item['Cmds']] = $item;
        }
        array_push($tree, $raiz);*/
        
        //print_r($tree);die();
        // Luego, construimos la estructura del árbol
        /*foreach ($lookup as $cmds => &$node) {
            if ($node['Nivel'] == 0) {
                $tree[$cmds] = &$node;
            } else {
                $parentCmds = (string) $node['Nivel'];
                if (isset($lookup[$parentCmds])) {
                    $lookup[$parentCmds]['children'][] = &$node;
                }
            }
        }*/
        //return array('arbol'=>$tree, 'datos'=>$datos);
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

    function ListaCatalogoLineas(){
        $datos = $this->modelo->ListaCatalogoLineas();
        $lineas = array('D', 'C');

        foreach($lineas as $item){
            array_push($datos, array('Fact' => $item));
        }

        return $datos;
    }
}
?>