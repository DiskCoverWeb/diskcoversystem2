<?php

/** 
 * AUTOR DE RUTINA	: Dallyana Vanegas
 * MODIFICADO POR : Teddy Moreira
 * FECHA CREACION	: 16/02/2024
 * FECHA MODIFICACION : 02/07/2024
 * DESCIPCION : Clase controlador para Agencia
 */

include (dirname(__DIR__, 2) . '/modelo/inventario/registro_beneficiarioM.php');

$controlador = new registro_beneficiarioC();

if (isset($_GET['EliminaArchivosTemporales'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->EliminaArchivosTemporales($parametros));
}

if (isset($_GET['LlenarSelect'])) {
    $valores = $_POST['valores'];
    echo json_encode($controlador->LlenarSelect($valores));
}

if (isset($_GET['ObtenerColor'])) {
    $valor = $_POST['valor'];
    echo json_encode($controlador->ObtenerColor($valor));
}

if (isset($_GET['actualizarSelectDonacion'])) {
    $valor = $_POST['valor'];
    echo json_encode($controlador->actualizarSelectDonacion($valor));
}

if(isset($_GET['LlenarEstadoCivil'])) {
    echo json_encode($controlador->llenarEstadoCivil($valor));
}

if (isset($_GET['LlenarCalendario'])) {
    $valor = $_POST['valor'];
    echo json_encode($controlador->LlenarCalendario($valor));
}

if (isset($_GET['LlenarTblPoblacion'])) {
    echo json_encode($controlador->LlenarTblPoblacion());
}

if (isset($_GET['descargarArchivo'])) {
    $valor = $_POST['valor'];
    echo json_encode($controlador->descargarArchivo($valor));
}

if (isset($_GET['LlenarSelectSexo'])) {
    echo json_encode($controlador->LlenarSelectSexo());
}

if (isset($_GET['LlenarSelectDiaEntrega'])) {
    echo json_encode($controlador->LlenarSelectDiaEntrega());
}

if (isset($_GET['LlenarSelectRucCliente'])) {
    $query = '';
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    echo json_encode($controlador->LlenarSelectRucCliente($query));
}

if (isset($_GET['LlenarTipoDonacion'])) {
    $query = '';
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    echo json_encode($controlador->LlenarTipoDonacion($query));
}

if (isset($_GET['LlenarSelects_Val'])) {
    $query = '';
    $valor = isset($_GET['valor']) ? $_GET['valor'] : 0;
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    $valor2 = isset($_GET['valor2']) ? $_GET['valor2'] : false;
    echo json_encode($controlador->LlenarSelects_Val($query, $valor, $valor2));
}

if (isset($_GET['llenarCamposInfo'])) {
    $valor = $_POST['valor'];
    echo json_encode($controlador->llenarCamposInfo($valor));
}
if (isset($_GET['llenarCamposInfoAdd'])) {
    $valor = $_POST['valor'];
    echo json_encode($controlador->llenarCamposInfoAdd($valor));
}

if (isset($_GET['llenarCamposPoblacion'])) {
    $valor = $_POST['valor'];
    echo json_encode($controlador->llenarCamposPoblacion($valor));
}

if (isset($_GET['guardarAsignacion'])) {
    // Crear un array con los datos del formulario
    $params = array(
        'Cliente' => $_POST['Cliente'],
        'CI_RUC' => $_POST['CI_RUC'],
        'Codigo' => $_POST['Codigo'],
        'TB' => $_POST['TB'],
        'Calificacion' => $_POST['Calificacion'],
        'CodigoA' => $_POST['CodigoA'],
        'Representante' => $_POST['Representante'],
        'CI_RUC_R' => $_POST['CI_RUC_R'],
        'Telefono_R' => $_POST['Telefono_R'],
        'Contacto' => $_POST['Contacto'],
        'Profesion' => $_POST['Profesion'],
        'Dia_Ent' => $_POST['Dia_Ent'],
        'Hora_Ent' => $_POST['Hora_Ent'],
        'Sexo' => $_POST['Sexo'],
        'Email' => $_POST['Email'],
        'Email2' => $_POST['Email2'],
        'Provincia' => $_POST['Provincia'],
        'Ciudad' => $_POST['Ciudad'],
        'Canton' => $_POST['Canton'],
        'Parroquia' => $_POST['Parroquia'],
        'Barrio' => $_POST['Barrio'],
        'CalleP' => $_POST['CalleP'],
        'CalleS' => $_POST['CalleS'],
        'Referencia' => $_POST['Referencia'],
        'Telefono' => $_POST['Telefono'],
        'TelefonoT' => $_POST['TelefonoT'],
        //datos extra
        'CodigoA2' => $_POST['CodigoA2'],
        'Dia_Ent2' => $_POST['Dia_Ent2'],
        'Hora_Registro' => $_POST['Hora_Registro'],
        'Envio_No' => $_POST['Envio_No'],
        'Comentario' => $_POST['Comentario'],
        'No_Soc' => $_POST['No_Soc'],
        //'Area' => $_POST['Area'],
        'TipoPoblacion' => $_POST['TipoPoblacion'],
        'Acreditacion' => $_POST['Acreditacion'],
        'Tipo_Dato' => $_POST['Tipo_Dato'],
        'Cod_Fam' => $_POST['Cod_Fam'],
        'Observaciones' => $_POST['Observaciones']
    );


    // Verificar si se han cargado archivos
    if (isset($_FILES['Evidencias']) && $_FILES['Evidencias']['error'][0] == UPLOAD_ERR_OK) {
        $nombresArchivos = array();
        $filePathBase = dirname(__DIR__, 2) . "/comprobantes/sustentos/EVIDENCIA_" . $_SESSION['INGRESO']['Entidad'] . "/EVIDENCIA_" . $_SESSION['INGRESO']['item'] . "/";
        $filePathBase = str_replace(' ', '_', $filePathBase);

        // Crear el directorio si no existe
        if (!is_dir($filePathBase)) {
            mkdir($filePathBase, 0777, true);
        }

        // Iterar sobre cada archivo cargado
        foreach ($_FILES['Evidencias']['name'] as $indice => $nombre) {
            $filename = pathinfo($nombre, PATHINFO_FILENAME);
            $filename = str_replace(' ', '_', $filename);
            $ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));
            $filepath = $filePathBase . $filename . '.' . $ext;

            switch ($ext) {
                case 'doc':
                case 'docx':
                    $letra_ext = 'W';
                    break;
                case 'pdf':
                    $letra_ext = 'D';
                    break;
                case 'jpg':
                case 'png':
                    $letra_ext = 'P';
                    break;
                default:
                    $letra_ext = 'O';
            }

            $codigo = $params['Codigo'];

            $filename = $filename . '_' . $letra_ext . $codigo;
            $filepath = $filePathBase . $filename . '.' . $ext;

            /*$contador = 1;
            while (file_exists($filepath)) {
                $filename = $filename . '_' . $contador;
                $filepath = $filePathBase . $filename . '.' . $ext;
                $contador++;
            }*/

            if (move_uploaded_file($_FILES['Evidencias']['tmp_name'][$indice], $filepath)) {
                $nombresArchivos[] = $filename;
            } else {
                echo json_encode(["res" => '0', "mensaje" => "No se ha cargado el archivo: " . $nombre]);
                return;
            }
        }

        foreach ($nombresArchivos as &$nombreArchivo) {
            $nombreArchivo = $nombreArchivo . ',';
        }
        unset($nombreArchivo);
        $params['NombreArchivo'] = implode('', $nombresArchivos);

        if (strlen($params['NombreArchivo']) > 90) {
            echo json_encode(["res" => '0', "mensaje" => "El nombre del archivo supera el máximo de caracteres", "datos" => $params['NombreArchivo']]);
        } 
    } else{
        $params['NombreArchivo'] = '';
    }
    echo json_encode($controlador->guardarAsignacion($params));
    /*else {
        echo json_encode(["res" => '0', "mensaje" => "No se ha cargado ningún archivo"]);
    }*/

}


if (isset($_GET['provincias'])) {
    $pais = '';
    if (isset($_POST['pais'])) {
        $pais = $_POST['pais'];
    }
    echo json_encode(provincia_todas($pais));
}

if (isset($_GET['ciudad'])) {
    echo json_encode(todas_ciudad($_POST['idpro']));
}

//Agregado por: Teddy Moreira

if(isset($_GET['CatalogoForm'])){
    echo json_encode($controlador->getCatalogo());
}
if(isset($_GET['ConsultarCliente'])){
    $cedula = $_POST['cedula'];
    echo json_encode($controlador->consultarCliente($cedula));
}
if(isset($_GET['EnviarInscripcion']) && isset($_GET['ModoEnviar'])){
    $parametros = $_POST;
    $modo = $_GET['ModoEnviar'];
    $nombresArchivos = array();
    if(count($_FILES) > 0){
        foreach($_FILES as $key => $file){
            $filePathBase = dirname(__DIR__, 2) . "/comprobantes/inscripciones/";
            
            if (!is_dir($filePathBase)) {
                mkdir($filePathBase, 0777, true);
            }

            if(isset($_FILES[$key]) && $_FILES[$key]['error'] == UPLOAD_ERR_OK){
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $newFileName = '';

                switch($key){
                    case 'Archivo_CI_RUC_PAS':
                        $newFileName = 'cedula';
                        break;
                    case 'Archivo_Record_Policial':
                        $newFileName = 'record';
                        break;
                    case 'Archivo_Planilla':
                        $newFileName = 'plansb';
                        break;
                    case 'Archivo_Carta_Recom':
                        $newFileName = 'carta';
                        break;
                    case 'Archivo_Certificado_Medico':
                        $newFileName = 'medico';
                        break;
                    case 'Archivo_VIH':
                        $newFileName = 'vih';
                        break;
                    case 'Archivo_Reglamento':
                        $newFileName = 'regbaq';
                        break;
                }

                $filename = $parametros['CI_RUC'] . $newFileName . '.' . $ext;
                $filepath = $filePathBase . $filename;

                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    $parametros[$key] = $filename;
                } else {
                    echo json_encode(["codigo" => 0, "respuesta" => "No se ha cargado el archivo: " . $file['name']]);
                    return;
                }
            }
        }
    }
    echo json_encode($controlador->enviarInscripcion($parametros, $modo));
}

class registro_beneficiarioC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new registro_beneficiarioM();
    }

    function LlenarTblPoblacion()
    {
        $datos = $this->modelo->LlenarTblPoblacion();
        if (empty($datos)) {
            $datos = 0;
        }
        return $datos;
    }

    function LlenarCalendario($valor)
    {
        $datos = $this->modelo->LlenarCalendario($valor);
        if (empty($datos)) {
            $datos = 0;
        }
        return $datos;
    }

    function llenarCamposInfo($valor)
    {
        $datos = $this->modelo->llenarCamposInfo($valor);
        if (empty($datos)) {
            $datos = 0;
        }
        return $datos;

    }
    function llenarCamposInfoAdd($valor)
    {
        $datos = $this->modelo->llenarCamposInfoAdd($valor);
        if (empty($datos)) {
            $datos = 0;
        }
        return $datos;

    }

    function llenarCamposPoblacion($parametros)
    {
        $datos = $this->modelo->llenarCamposPoblacion($parametros);
        if (empty($datos)) {
            $datos = 0;
        }
        return $datos;

    }

    

    function LlenarSelectSexo()
    {
        $datos = $this->modelo->LlenarSelectSexo();
        if (empty($datos)) {
            $datos = 0;
        }
        return $datos;
    }

    function LlenarSelectDiaEntrega()
    {
        $datos = $this->modelo->LlenarSelectDiaEntrega();
        if (empty($datos)) {
            $datos = 0;
        }
        return $datos;
    }

    function ObtenerColor($valor)
    {
        $datos = $this->modelo->ObtenerColor($valor);
        if (!isset($datos['Color'])) {
            $datos['Color'] = '#fffacd';
        }
        if (empty($datos)) {
            $datos = 0;
        }
        return $datos;
    }

    function llenarEstadoCivil()
    {
        $datos = $this->modelo->llenarEstadoCivil();
        if (empty($datos)) {
            return ["res" => 0, "mensaje" => "No se encontraron datos para mostrar"];
        }else{
            return ["res" => 1, "mensaje" => $datos];
        }
    }

    function EliminaArchivosTemporales($parametros)
    {
        $basePath = dirname(__DIR__, 2);
        $tempFilePath = $parametros['ruta'] . $parametros['nombre'];
        $fullPath = $basePath . $tempFilePath;
        //print_r($parametros['nombre']);die();
        $filename = pathinfo($parametros['nombre'], PATHINFO_FILENAME);
        $result = $this->modelo->EliminarEvidencias($filename, $parametros['codigo']);

        //print_r($fullPath); die();
        if (file_exists($fullPath)) {
            if (unlink($fullPath)) {
                return ['res' => 0, 'res2' => $result]; // Éxito al eliminar el archivo
            } else {
                return ['res' => 2, 'res2' => $result]; // Fallo al eliminar el archivo
            }
        } else {
            if ($result != 1) {
                return ['res' => 0, 'res2' => $result]; // Éxito al eliminar el archivo
            }
        }
    }

    function descargarArchivo($valores)
    {
        $base = dirname(__DIR__, 2);
        $directorio = "/comprobantes/sustentos/EVIDENCIA_" . $_SESSION['INGRESO']['Entidad'] . "/EVIDENCIA_" . $_SESSION['INGRESO']['item'] . "/";
        $directorio = str_replace(' ', '_', $directorio);
        $carpetaDestino = $base . $directorio;
        $carpetaDestino = str_replace(' ', '_', $carpetaDestino);

        $archivos = explode(',', $valores);
        $archivos = array_filter($archivos);
        $archivosEncontrados = array();
        $archivosNo = array();
        $archivosEnCarpeta = scandir($carpetaDestino);

        foreach ($archivos as $valor) {
            $found = false;
            foreach ($archivosEnCarpeta as $archivo) {
                $nombreArchivo = pathinfo($archivo, PATHINFO_FILENAME);
                if ($valor === $nombreArchivo) {
                    $archivosEncontrados[] = $archivo;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $archivosNo[] = $valor;
            }
        }
        return ["archivos" => $archivosEncontrados, "archivosNo" => $archivosNo, "dir" => $directorio];
    }



    function obtenerCamposComunes($valor)
    {
        return [
            'id' => $valor['Codigo'],
            'Cliente' => $valor['Cliente'],
            'CI_RUC' => $valor['CI_RUC'],
        ];
    }

    function LlenarSelectRucCliente($query): array
    {
        try {
            $datos = $this->modelo->LlenarSelectRucCliente($query);
            if (count($datos) == 0) {
                throw new Exception('No se encontraron datos');
            }
            $clientes = [];
            $rucs = [];

            foreach ($datos as $valor) {
                $clienteFields = $this->obtenerCamposComunes($valor);
                $clienteFields['text'] = $valor['Cliente'];
                $clientes[] = $clienteFields;

                $rucFields = $this->obtenerCamposComunes($valor);
                $rucFields['text'] = $valor['CI_RUC'];
                $rucs[] = $rucFields;
            }

            return ['clientes' => $clientes, 'rucs' => $rucs];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    function LlenarTipoDonacion($query): array
    {
        try {
            $datos = $this->modelo->LlenarTipoDonacion($query);
            if (count($datos) == 0) {
                throw new Exception('No se encontraron datos');
            }
            foreach ($datos as $valor) {
                $tipoDonacion[] = [
                    'id' => $valor['Codigo'],
                    'text' => $valor['Concepto'],
                ];
            }
            return ['respuesta' => $tipoDonacion];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    function LlenarSelects_Val($query, $valor, $valor2): array
    {
        try {
            $datos = $this->modelo->LlenarSelects_Val($query, $valor, $valor2);

            if (empty($datos)) {
                throw new Exception('No se encontraron datos');
            }

            $respuesta = [];
            foreach ($datos as $dato) { {
                    if (!isset($dato['Color'])) {
                        $dato['Color'] = '#fffacd';
                    }
                    if ($valor2) {
                        $id = substr($dato['Codigo'], -3);
                        $respuesta[] = [
                            'id' => $id,
                            'text' => $dato['Concepto'],
                            'picture' => $dato['Picture']
                        ];
                    } else {

                        $respuesta[] = [
                            'id' => $dato['Cmds'],
                            'text' => $dato['Proceso'],
                            'color' => $dato['Color'],
                            'picture' => $dato['Picture'],
                        ];

                    }
                }
            }
            $val = $valor2 ? 1 : 2;
            return ['val' => $val, 'respuesta' => $respuesta];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    function guardarAsignacion($parametros)
    {
        $datos = $this->modelo->guardarAsignacion($parametros);
        return array("res" => '1', "mensaje" => "Se registro correctamente", "datos" => $datos);
    }

    //Agregado por: Teddy Moreira
    function getCatalogo(){
            $datos = $this->modelo->getCatalogoForm();
            $catalogo = array();
            if(count($datos) > 0){
                foreach($datos as $key => $value){
                    $catalogo[] = array(
                        'DG' => $value['DG'],
                        'Tipo' => $value['Tipo'],
                        'Codigo' => $value['Codigo'],
                        'Cuenta' => $value['Cuenta'],
                        'Comentario' => $value['Comentario'],
                        'Imagen' => $value['Imagen']
                    );
                }
                return $catalogo;
            }else{
                $catalogo[] = array(
                    'error' => 'No hay catalogo'
                );
                return $catalogo;
            }
        }

    function consultarCliente($cedula){
        $voluntario = $this->modelo->consultarCliente($cedula);
        if(count($voluntario) > 0){
            $datos = array();
            $archivos = array();
            foreach($voluntario[0] as $key => $value){
                if(str_contains($key, "Archivo_")){
                    $archivos[$key] = $value;
                }else{
                    $datos[$key] = $value;
                }
            }
            $respuesta = array(
                'res' => 1,
                'datos' => array(
                    'datos' => $datos,
                    'archivos' => $archivos
                )
            );
            return $respuesta;
        }else{
            $respuesta = array(
                'res' => 0,
                'datos' => 'No hay cedula registrada'
            );
            return $respuesta;
        }
    }

    function enviarInscripcion($parametros, $modo){
        $respuesta = 0;
        if($modo=="CrearVoluntario"){
            $respuesta = $this->modelo->crearInscripcion($parametros);
        }else{
            $respuesta = $this->modelo->actualizarInscripcion($parametros);
        }

        if($respuesta == 1){
            return array(
                "codigo" => 1,
                "respuesta" => "Se enviaron los datos correctamente"
            );
        }
        return array(
            "codigo" => 0,
            "respuesta" => "Hubo un error al enviar los datos"
        );
    }

}
?>