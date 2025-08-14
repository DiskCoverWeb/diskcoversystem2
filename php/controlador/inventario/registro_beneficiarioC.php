<?php

/** 
 * AUTOR DE RUTINA	: Dallyana Vanegas
 * MODIFICADO POR : Teddy Moreira
 * FECHA CREACION	: 16/02/2024
 * FECHA MODIFICACION : 02/07/2024
 * DESCIPCION : Clase controlador para Agencia
 */

require_once (dirname(__DIR__, 2) . '/modelo/inventario/registro_beneficiarioM.php');
require_once (dirname(__DIR__,2).'/modelo/farmacia/articulosM.php');

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
    // print_r($_GET);die();
    $query = '';
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    echo json_encode($controlador->LlenarSelectRucCliente($query));
}

if (isset($_GET['validar_registro_ci'])) {
    // print_r($_GET);die();
    $query = '';
    if (isset($_POST['parametros'])) {
        $query = $_POST['parametros'];
    }
    echo json_encode($controlador->validar_registro_ci($query));
}

if (isset($_GET['LlenarTipoDonacion'])) {
    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    echo json_encode($controlador->LlenarTipoDonacion($query));
}
if (isset($_GET['ddl_estados'])) {
    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    echo json_encode($controlador->ddl_estados($query));
}
if (isset($_GET['Tipo_Entrega'])) {
    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    echo json_encode($controlador->Tipo_Entrega($query));
}
if (isset($_GET['Frecuencia'])) {
    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    echo json_encode($controlador->Frecuencia($query));
}
if (isset($_GET['Accion_Social'])) {
    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    echo json_encode($controlador->Accion_Social($query));
}
if (isset($_GET['Vulnerabilidad'])) {
    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    echo json_encode($controlador->Vulnerabilidad($query));
}
if (isset($_GET['Tipo_Atencion'])) {
    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    echo json_encode($controlador->Tipo_Atencion($query));
}

if (isset($_GET['programas'])) {
    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    echo json_encode($controlador->Programas($query));
}


if (isset($_GET['Tipo_Beneficiario'])) {
    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    echo json_encode($controlador->Tipo_Beneficiario($query));
}

if (isset($_GET['LlenarSelects_Val'])) {
    $query = '';
    $valor = isset($_GET['valor']) ? $_GET['valor'] : 0;
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    $valor2 = isset($_GET['valor2']) ? $_GET['valor2'] : false;
    echo json_encode($controlador->LlenarSelects_Val($query, $valor, $valor2));
}

if (isset($_GET['ddl_grupos'])) {
    $query = '';
    $programa = 0;
    if (isset($_GET['programa'])) {
        $programa = $_GET['programa'];
    }
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    echo json_encode($controlador->ddl_grupos($programa,$query));
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
if (isset($_GET['llenarCamposExtructuraFami'])) {
    $valor = $_POST['valor'];
    echo json_encode($controlador->llenarCamposExtructuraFami($valor));
}

if (isset($_GET['datosClienteaAll'])) {
    $valor = $_POST['valor'];
    echo json_encode($controlador->datosClienteaAll($valor));
}
if (isset($_GET['guardarAsignacion1'])){
    $data = $_POST['parametros'];    
    echo json_encode($controlador->guardarAsignacion1($data));

}


 // funciones para familias drops

    if(isset($_GET['parentesco']))
    {
        $query = '';
        if(isset($_GET['q'])){$query = $_GET['q'];}
        echo json_encode($controlador->parentesco($query));

    }
    if(isset($_GET['sexo']))
    {
        $query = '';
        if(isset($_GET['q'])){$query = $_GET['q'];}
        echo json_encode($controlador->sexo($query));

    }
    if(isset($_GET['rango_edades']))
    {
        $query = '';
        if(isset($_GET['q'])){$query = $_GET['q'];}
        echo json_encode($controlador->rango_edades($query));

    }
    if(isset($_GET['estado_civil']))
    {
        $query = '';
        if(isset($_GET['q'])){$query = $_GET['q'];}
        echo json_encode($controlador->estado_civil($query));

    }
    if(isset($_GET['nivel_escolaridad']))
    {
        $query = '';
        if(isset($_GET['q'])){$query = $_GET['q'];}
        echo json_encode($controlador->nivel_escolaridad($query));

    }
    if(isset($_GET['tipo_institucion']))
    {
        $query = '';
        if(isset($_GET['q'])){$query = $_GET['q'];}
        echo json_encode($controlador->tipo_institucion($query));

    }
    if(isset($_GET['vulnerabilidadFam']))
    {
        $query = '';
        if(isset($_GET['q'])){$query = $_GET['q'];}
        echo json_encode($controlador->vulnerabilidadFam($query));

    }
    if(isset($_GET['tipo_enfermedad']))
    {
        $query = '';
        if(isset($_GET['q'])){$query = $_GET['q'];}
        echo json_encode($controlador->tipo_enfermedad($query));

     }
    if(isset($_GET['tipo_discapacidad']))
    {
       
       $query = '';
       if(isset($_GET['q'])){$query = $_GET['q'];}
       echo json_encode($controlador->tipo_discapacidad($query));     

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
    private $sri;
    private $articulos;

    function __construct()
    {
        $this->modelo = new registro_beneficiarioM();
        $this->sri = new autorizacion_sri();
        $this->articulos = new articulosM();
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

    function llenarCamposExtructuraFami($parametros)
    {
        $datos = $this->modelo->Trans_Parroquias($parametros);
        $estructura = array();
        foreach ($datos as $key => $value) {

            $genero = '';
            $parentesco = '';
            $rango_edad = '';
            $est_civil = '';
            $nivel_es = '';
            $tipo_ins = '';
            $vulnera = '';
            $genero2 = $this->modelo->sexo($value['Cedula_P']);
            if(count($genero2)>0){  $genero = $genero2[0]['Descripcion'];  }

            $parentesco2 = $this->modelo->parentesco($value['Cedula_M']);
            if(count($parentesco2)>0){  $parentesco = $parentesco2[0]['Descripcion'];  }

            $rango_edad2 = $this->modelo->rango_edades($value['Madre']);
            if(count($rango_edad2)>0){  $rango_edad = $rango_edad2[0]['Descripcion'];  }

            $est_civil2 = $this->modelo->estado_civil($value['Ministro']);
            if(count($est_civil2)>0){  $est_civil = $est_civil2[0]['Descripcion'];  }

            $nivel_es2 = $this->modelo->nivel_escolaridad($value['Nota_Marginal']);
            if(count($nivel_es2)>0){  $nivel_es = $nivel_es2[0]['Descripcion'];  }

            $tipo_ins2 = $this->modelo->tipo_institucion($value['Ciudad_Nacimiento']);
            if(count($tipo_ins2)>0){  $tipo_ins = $tipo_ins2[0]['Descripcion'];  }

            $vulnera2 = $this->modelo->vulnerabilidadFam($value['Ciudad_B_C_M']);
            if(count($vulnera2)>0){  $vulnera = $vulnera2[0]['Descripcion'];  }

            $estructura[$key] = array(
                'id'=>$key,
                'nombre'=>$value['Beneficiario'],
                'generoid'=>$value['Cedula_P'],
                'genero'=>$genero,
                'parentescoid'=>$value['Cedula_M'],
                'parentesco'=>$parentesco,
                'rangoEdadid'=>$value['Madre'], 
                'rangoEdad'=>$rango_edad, 
                'ocupacion'=>$value['Padre'],
                'estadoCivilid'=>$value['Ministro'],
                'estadoCivil'=>$est_civil,
                'nivelEscolaridadid'=>$value['Nota_Marginal'],
                'nivelEscolaridad'=>$nivel_es,
                'nombreInstitucion'=>$value['Padrinos'],
                'tipoInstitucionid'=>$value['Ciudad_Nacimiento'], 
                'tipoInstitucion'=>$tipo_ins,
                'vulnerabilidadid'=>$value['Ciudad_B_C_M'], 
                'vulnerabilidad'=>$vulnera, 
            );
            if($value['Tipo_Certificado']!='.'){ $estructura[$key]['tipoEnfermedadid'] = $value['Tipo_Certificado'];
                $tipo_enfer = $this->modelo->tipo_enfermedad($value['Tipo_Certificado']);
                if(count($tipo_enfer)>0){
                    $estructura[$key]['tipoEnfermedad'] = $tipo_enfer[0]['Descripcion'];
                }
            }
            if($value['Certificado_Valido']!='.'){ $estructura[$key]['nomEnfermedad'] =$value['Certificado_Valido'];}
            if($value['Tomo']!='.'){$estructura[$key]['porcediscapacidad'] =$value['Tomo'];}
            if($value['Nombre_Discapacidad']!='.'){ $estructura[$key]['nomdiscapacidad'] =$value['Nombre_Discapacidad'];}
            if($value['Tipo_Discapacidad']!='.'){ $estructura[$key]['tipodiscapacidadid'] = $value['Tipo_Discapacidad'];
             $tipo_disc = $this->modelo->tipo_discapacidad($value['Tipo_Certificado']);
                if(count($tipo_disc)>0){
                    $estructura[$key]['tipodiscapacidad'] = $tipo_disc[0]['Descripcion'];
                }
            }
                

        }
        // print_r($estructura);die();

        return $estructura;
        

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

    function LlenarSelectRucCliente($query)
    {      
            $datos = $this->modelo->LlenarSelectRucCliente($query);
            return $datos;
    }

    function validar_registro_ci($parametros)
    {    
        // print_r($parametros);die();
        $dataAll = array();
        $ci = $parametros['ci'];
        $datos = $this->modelo->LlenarSelectRucCliente(false,false,$ci);
        // print_r($datos);die();
        if(count($datos)>0)
        {
            $dataAll = $this->datosClienteaAll($datos[0]['Codigo']);
        }
        return $dataAll;
    }

    function LlenarTipoDonacion($query)
    {
        $datos = $this->modelo->LlenarTipoDonacion($query);
        // print_r($datos);die();
        foreach ($datos as $valor) {
            $tipoDonacion[] = [
                'id' => $valor['Fact'],
                'text' => $valor['Concepto'],
                'data'=>$valor
            ];
        }
        return $tipoDonacion;
    }

    function ddl_estados($query)
    {
        $datos = $this->modelo->Estado_Beneficiario($query);
        // print_r($datos);die();
        foreach ($datos as $valor) {
            $tipoDonacion[] = [
                'id' => $valor['Cmds'],
                'text' => $valor['Proceso'],
                'data'=>$valor
            ];
        }
        return $tipoDonacion;
    }

    function Tipo_Entrega($query)
    {
        $datos = $this->modelo->Tipo_Entrega($query);
        // print_r($datos);die();
        foreach ($datos as $valor) {
            $tipoDonacion[] = [
                'id' => $valor['Cmds'],
                'text' => $valor['Proceso'],
                'data'=>$valor
            ];
        }
        return $tipoDonacion;
    }

    function Frecuencia($query)
    {
        $datos = $this->modelo->Frecuencia($query);
        // print_r($datos);die();
        foreach ($datos as $valor) {
            $tipoDonacion[] = [
                'id' => $valor['Cmds'],
                'text' => $valor['Proceso'],
                'data'=>$valor
            ];
        }
        return $tipoDonacion;
    }

    function Accion_Social($query)
    {
        $datos = $this->modelo->Accion_Social($query);
        // print_r($datos);die();
        foreach ($datos as $valor) {
            $tipoDonacion[] = [
                'id' => $valor['Cmds'],
                'text' => $valor['Proceso'],
                'data'=>$valor
            ];
        }
        return $tipoDonacion;
    }

    function Vulnerabilidad($query)
    {
        $datos = $this->modelo->Vulnerabilidad($query);
        // print_r($datos);die();
        foreach ($datos as $valor) {
            $tipoDonacion[] = [
                'id' => $valor['Cmds'],
                'text' => $valor['Proceso'],
                'data'=>$valor
            ];
        }
        return $tipoDonacion;
    }

    function Tipo_Atencion($query)
    {
        $datos = $this->modelo->Tipo_Atencion($query);
        // print_r($datos);die();
        foreach ($datos as $valor) {
            $tipoDonacion[] = [
                'id' => $valor['Cmds'],
                'text' => $valor['Proceso'],
                'data'=>$valor
            ];
        }
        return $tipoDonacion;
    }

    function Programas($query)
    {
        $datos = $this->modelo->Programas($query);
        // print_r($datos);die();
        foreach ($datos as $valor) {
            $tipoDonacion[] = [
                'id' => $valor['Cmds'],
                'text' => $valor['Proceso'],
                'data'=>$valor
            ];
        }
        return $tipoDonacion;
    }



    function Tipo_Beneficiario($query)
    {
         $datos = $this->modelo->Tipo_Beneficiario($query);
         $lista = array();
         foreach ($datos as $key => $value) {
             $lista[] = array('id'=>$value['Cmds'],'text'=>$value['Proceso'],'data'=>$value);
         }
         return $lista;
    }

    function Tipo_Donacion($query)
    {
         $datos = $this->modelo->Tipo_Beneficiario($query);
         $lista = array();
         foreach ($datos as $key => $value) {
             $lista[] = array('id'=>$value['Cmds'],'text'=>$value['Proceso'],'data'=>$value);
         }
         return $lista;
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

    function ddl_grupos($programas,$query)
    {
        $lista = array();
        $datos = $this->modelo->ddl_grupos($programas,$query);
        foreach ($datos as $key => $value) {
            $lista[] = array('id'=>$value['Cmds'],'text'=>$value['Proceso'],'data'=>$value);
        }
        return $lista;
    }

    function guardarAsignacion($parametros)
    {
        $datos = $this->modelo->guardarAsignacion($parametros);
        return array("res" => '1', "mensaje" => "Se registro correctamente", "datos" => $datos);
    }

    function guardarAsignacion1($parametros)
    {
        // print_r($parametros);die();
        $data = array();
        $direccion = array();
        $data_info_user = array();
        parse_str($parametros['data'],$data);
        parse_str($parametros['dataD'],$direccion);

        $respuesta = 1;
        switch ($data['select_93']) {
            case '93.01':
            $poblacion = $parametros['poblacion'];

                $respuesta = $this->editarAccionSocial($data,$direccion);
                if($respuesta!='-4')
                {
                    $this->editarAccionSocialExtras($data);
                    $this->editarTipoPoblacion($data,$poblacion);
                }
                break;
             case '93.02':

                parse_str($parametros['data_info'],$data_info_user);
                $this->editarAccionSocial($data,$direccion);
                $this->editarAccionSocialExtras($data,$data_info_user);
                if(isset($parametros['estru_fam']))
                {
                    $this->editarEstructuraFamiliar($parametros['estru_fam'],$data);
                }

                // print_r($data);
                // print_r($direccion);
                // print_r($data_info_user);die();           
                break;   
            case '93.04':

                // parse_str($parametros['data_info'],$data_info_user);
                $this->editarAccionSocial($data,$direccion);
                $this->editarAccionSocialExtras($data,$data_info_user);
                // if(isset($parametros['estru_fam']))
                // {
                //     $this->editarEstructuraFamiliar($parametros['estru_fam'],$data);
                // }

                // print_r($data);
                // print_r($direccion);
                // die();
                // print_r($data_info_user);die();           
                break;              
            default:
                // code...
                break;
        }

        return $respuesta;
    }


    function editarAccionSocial($dato,$datosdir)
    {

        // print_r($dato);die();
        // print_r($datosdir);die();

        $CI_RUC = $this->sri->quitar_carac($dato['txt_ci']);
        $codigos = Digito_verificador($CI_RUC);
        $existe = $this->articulos->clientes_all(false,false,false,$dato['txt_ci']);
        $fa = false;
        // print_r($existe);die();
        if(count($existe)==0)
        {
            $existe = $this->articulos->clientes_all($dato['cliente']);
            if(count($existe)>0)
            {
                return -4;
            }
        }
        if($dato['txt_id']=='' && count($existe)>0)
        {
            $dato['txt_id']=$existe[0]['ID'];
        }

        // print_r($codigos);die();
        SetAdoAddNew("Clientes");
        SetAdoFields('CI_RUC', $dato['txt_ci']);        
        SetAdoFields('TD', $dato['txt_td']);      
        SetAdoFields('Codigo',$codigos['Codigo_RUC_CI']);
        SetAdoFields('T','N');

        SetAdoFields('Actividad', $dato['select_93']);
        SetAdoFields('TB', $dato['select_93']);
        SetAdoFields('Calificacion', $dato['select_CxC']);
        SetAdoFields('CodigoA', $dato['select_87']);
        if(isset($datosdir['select_prov']))
        {
            SetAdoFields('Prov', $datosdir['select_prov']);
        }
        if(isset($datosdir['select_ciud']))
        {
            SetAdoFields('Ciudad', $datosdir['select_ciud']);
        }
        SetAdoFields('Canton', $datosdir['Canton']);
        SetAdoFields('Parroquia', $datosdir['Parroquia']);
        SetAdoFields('Barrio', $datosdir['Barrio']);
        SetAdoFields('Direccion', $datosdir['CalleP']);
        SetAdoFields('DireccionT', $datosdir['CalleS']);
        SetAdoFields('Referencia', $datosdir['Referencia']);
        if(isset($parametros['rbl_facturar']))
        {
            $fa = true;
        }

        SetAdoFields('FA',$fa);        

        if($dato['fechaDesviculacion']!='')
        {
            SetAdoFields('Fecha_Bloq', $dato['fechaDesviculacion']);
        }else
        {
            SetAdoFields('Fecha_Bloq','.');
        }

        if($dato["select_93"]=="93.01")
        {
         // para organizacion social datos unicos   
            SetAdoFields('Cliente', strtoupper($dato['cliente']));
            SetAdoFields('Contacto', $dato['contacto']);
            SetAdoFields('Profesion', $dato['cargo']);
            SetAdoFields('Email', $dato['email']);
            SetAdoFields('Telefono', $dato['telefono']);       
            SetAdoFields('TelefonoT', $dato['telefono2']);
            SetAdoFields('Email2', $dato['email2']);
            SetAdoFields('Hora_Ent',$dato['horaEntrega']);
            if(isset($dato['diaEntrega']))
            {
                SetAdoFields('Dia_Ent',  $dato['diaEntrega']);
            }
            SetAdoFields('Representante',strtoupper($dato['nombreRepre']));
            SetAdoFields('CI_RUC_R', $dato['ciRepre']);
            SetAdoFields('Telefono_R', $dato['telfRepre']);
         }
         if($dato["select_93"]=="93.02")
         {
            // para familias dato unico
            SetAdoFields('Cliente', $dato['nombres'].' '. $dato['apellidos']);
            SetAdoFields('Telefono', $dato['telefonoFam']); 
            SetAdoFields('Profesion', $dato['ocupacion']);
            SetAdoFields('Casilla', $dato['nivelEscolar']);
            SetAdoFields('Est_Civil', $dato['estadoCivil']);
            SetAdoFields('Dosis', $dato['edad']);
            SetAdoFields('Grupo', $dato['grupo']);
            SetAdoFields('Prog_Cmds', $dato['select_85']);  
            SetAdoFields('Email', $dato['emailFam']);
            // SetAdoFields('Sexo', $dato['sexo']);
        }

        if($dato["select_93"]=="93.04")
         {
            // para productores aliados
            SetAdoFields('Hora_Ent',$dato['horaEntregaAli']);
            if(isset($dato['diaEntregaAli']))
            {
                SetAdoFields('Dia_Ent',  $dato['diaEntregaAli']);
            }
            SetAdoFields('Telefono', $dato['txt_telefonoAli']); 
            SetAdoFields('Email', $dato['txt_emailAli']);
            // SetAdoFields('Sexo', $dato['sexo']);
        }

        if($dato['txt_id']!='')
        {
            SetAdoFieldsWhere('ID', $dato['txt_id']);
            return SetAdoUpdateGeneric();
        }else
        {
           return SetAdoUpdate();  
        }
    }

    function editarAccionSocialExtras($datos,$user_info = false)
    {
        $cliente = $this->modelo->LlenarSelectRucCliente($query=false,$codigo=false,$datos['txt_ci']);
        $datosExtra = $this->modelo->llenarCamposInfoAdd($cliente[0]['Codigo'],$datos['select_93']);
        // print_r($datos);die();
        // print_r($datosExtra);
        // print_r($cliente);die();

        if(count($cliente)>0){
            SetAdoAddNew("Clientes_Datos_Extras");
            SetAdoFields('Codigo', $cliente[0]['Codigo']);
            SetAdoFields('Item', $_SESSION['INGRESO']['item']);
            
            if($datos['select_93']=='93.01')
            {
                if(isset($datos['select_88']))
                {
                    SetAdoFields('CodigoA', $datos['select_88']);
                }

                if(isset($datos['diaEntregac'])){
                    SetAdoFields('Dia_Ent', $datos['diaEntregac']);
                }

                if(isset($datos['horaEntregac'])){
                    SetAdoFields('Hora_Ent', $datos['horaEntregac']);
                }
                if(isset($datos['select_86']))
                {
                    SetAdoFields('Envio_No', $datos['select_86']);
                }
                SetAdoFields('Etapa_Procesal', $datos['comentario']);
                SetAdoFields('No_Soc', $datos['totalPersonas']);
                if(isset($datos['select_92']))
                {
                    SetAdoFields('Acreditacion', $datos['select_92']);
                }
                if(isset($datos['select_90']))
                {
                    SetAdoFields('Tipo_Dato', $datos['select_90']);
                }
                if(isset($datos['select_89']))
                {
                    SetAdoFields('Cod_Fam', $datos['select_89']);
                }
                SetAdoFields('Observaciones', $datos['infoNut']);
                if(isset($datos['NombreArchivo'])  && $datos['NombreArchivo']!='') {

                    SetAdoFields('Evidencias',"Evidencias, '" . $datos['NombreArchivo'] . "'");
                }
            }

            //para familias
            if($datos['select_93']=='93.02')
            {
                // print_r($user_info);die();
                SetAdoFields('CodigoB', $datos['select_85']);
                SetAdoFields('Observaciones', $datos['pregunta']);

                SetAdoFields('Num',$user_info['numHijosI']);
                SetAdoFields('Credito_No',$user_info['numHijosMayores']);
                SetAdoFields('Cuenta_No',$user_info['numHijosMenores']);
                SetAdoFields('Etapa_Procesal',$user_info['numPersonas']);

                SetAdoFields('No_Juicio',$user_info['comentarioAct']);                
                SetAdoFields('Causa',$user_info['comentarioConyugeAct']);

                if(isset($user_info['modalidadSelect'])){
                    SetAdoFields('Sujeto_Procesal',$user_info['modalidadSelect']);      
                }
                if(isset($user_info['modalidadConyugeSelect']))
                {       
                    SetAdoFields('Agente_Fiscal',$user_info['modalidadConyugeSelect']);        
                    SetAdoFields('Instruccion_Fiscal',$user_info['modalidadConyugeSelect']);
                }

            
            }

            //para productores aliados
             if($datos['select_93']=='93.04')
            {
                
                // SetAdoFields('Dia_Ent', $datos['diaEntregaAli']);
                // SetAdoFields('Hora_Ent', $datos['horaEntregaAli']);
                if(isset($datos['ddl_frecuenciaAli']))
                {
                    SetAdoFields('Envio_No', $datos['ddl_frecuenciaAli']);
                }
                if(isset($datos['NombreArchivo'])  && $datos['NombreArchivo']!='') {

                    SetAdoFields('Evidencias',"Evidencias, '" . $datos['NombreArchivo'] . "'");
                }
            }




            if(count($datosExtra)==0)
            {
                SetAdoUpdate();                
            }else
            {
                //SetAdoFieldsWhere('Item', $_SESSION['INGRESO']['item']);
                SetAdoFieldsWhere('ID',$datosExtra[0]['ID']);
                SetAdoUpdateGeneric();
            }
        }else
        {
            return -1;
        }
    }

    function editarEstructuraFamiliar($dataFami,$data)
    {
         $cliente = $this->modelo->LlenarSelectRucCliente($query=false,$codigo=false,$data['txt_ci']);
        // print_r($cliente);
        $data = $this->modelo->Trans_Parroquias($cliente[0]['Codigo']);
        
        if(count($dataFami)>0)
        {
            if(count($data)>0)
            {
                $this->modelo->DeleteEstructuraFamiliares($cliente[0]['Codigo']);
            }
            foreach ($dataFami as $key => $value) 
            {             
                SetAdoAddNew("Trans_Parroquias");
                SetAdoFields('Cedula', $cliente[0]['Codigo']);
                SetAdoFields('Beneficiario', $value['nombre']);
                SetAdoFields('Padre', $value['ocupacion']);
                SetAdoFields('Madre', $value['rangoEdadid']);
                SetAdoFields('Padrinos', $value['nombreInstitucion']);
                SetAdoFields('Cedula_P', $value['generoid']);
                SetAdoFields('Cedula_M', $value['parentescoid']);
                SetAdoFields('Ministro', $value['estadoCivilid']);
                SetAdoFields('Nota_Marginal', $value['nivelEscolaridadid']);
                SetAdoFields('Ciudad_Nacimiento', $value['tipoInstitucionid']);
                SetAdoFields('Ciudad_B_C_M', $value['vulnerabilidadid']);
                if(isset($value['tipoEnfermedadid'])){ SetAdoFields('Tipo_Certificado', $value['tipoEnfermedadid']);}
                if(isset($value['nomEnfermedad'])){ SetAdoFields('Certificado_Valido', $value['nomEnfermedad']);}
                if(isset($value['porcediscapacidad'])){SetAdoFields('Tomo', $value['porcediscapacidad']);}
                if(isset($value['nomdiscapacidad'])){ SetAdoFields('Nombre_Discapacidad', $value['nomdiscapacidad']);}
                if(isset($value['tipodiscapacidadid'])){ SetAdoFields('Tipo_Discapacidad', $value['tipodiscapacidadid']);}
                SetAdoFields('Pagina',count($value));
                SetAdoFields('T','N');
                SetAdoFields('Item',$_SESSION['INGRESO']['item']);
                SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
                SetAdoUpdate();   
            }
        }

    }

    function editarTipoPoblacion($datos,$poblacion)
    {
        $cliente = $this->modelo->LlenarSelectRucCliente($query=false,$codigo=false,$datos['txt_ci']);
        $poblacionReg = $this->modelo->llenarCamposPoblacion($cliente[0]['Codigo']);
        $poblacion = json_decode($poblacion, true);
        if(count($poblacionReg)>0)    
        {
            $this->modelo->deletePoblacion($cliente[0]['Codigo']);
        }

        foreach ($poblacion as $key => $value) {            
            SetAdoAddNew("Trans_Tipo_Poblacion");
            SetAdoFields('Fecha', date('Y-m-d H:i:s') );
            SetAdoFields('FechaM', date('Y-m-d H:i:s') );
            SetAdoFields('CodigoC',$cliente[0]['Codigo']);
            SetAdoFields('Cmds', $value['valueData']);
            SetAdoFields('Hombres', $value['hombres']);
            SetAdoFields('Mujeres', $value['mujeres']);
            SetAdoFields('Total', $value['total']);
            SetAdoFields('CodigoU', $_SESSION['INGRESO']['CodigoU']);
            SetAdoFields('Item', $_SESSION['INGRESO']['item']);
            SetAdoFields('Periodo', $_SESSION['INGRESO']['periodo']);
            SetAdoUpdate();                
        }



        // print_r($datos);
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

    function datosClienteaAll($codigo)
    {
        // print_r($codigo);die();
        $Tipo_Beneficiario = array();
        $tipoDonacion = array();
        $Estado_Beneficiario = array();
        $Tipo_Entrega = array();
        $Frecuencia = array();
        $Accion_Social = array();
        $Vulnerabilidad = array();
        $Tipo_Atencion = array();
        $Programas = array();
        $Grupo = array();
        $EstadoCivil = array();
        $EscolaridadPrincipal = array();
        $cliente = $this->modelo->LlenarSelectRucCliente(false,$codigo);
        $cliente_datos_extra = $this->modelo->llenarCamposInfoAdd($codigo,$cliente[0]['Actividad']);
        // print_r($cliente);die();
         // print_r($cliente_datos_extra);
        if(count($cliente)>0)
        {
            $cliente =  $cliente[0];
            if($cliente['TB']!='.' && $cliente['TB']!=''){
                $Tipo_Beneficiario = $this->modelo->Tipo_Beneficiario($cliente['TB']);
                if(count($Tipo_Beneficiario)>0)
                {
                    $Tipo_Beneficiario = $Tipo_Beneficiario[0];
                 // print_r($Tipo_Beneficiario);die();
                }
            }
            if($cliente['Calificacion']!='.' && $cliente['Calificacion']!='')
            {
                $tipoDonacion = $this->modelo->LlenarTipoDonacion(false,$cliente['Calificacion']);
                if(count($tipoDonacion)>0)
                {
                    $tipoDonacion = $tipoDonacion[0];
                 // print_r($tipoDonacion);
                }
            }
            if($cliente['CodigoA']!='.' && $cliente['CodigoA']!=''){
                $Estado_Beneficiario = $this->modelo->Estado_Beneficiario($cliente['CodigoA']);
                if(count($Estado_Beneficiario)>0)
                {
                    $Estado_Beneficiario = $Estado_Beneficiario[0];
                 // print_r($Estado_Beneficiario);die();
                }
            }
            if($cliente['Grupo']!='.' && $cliente['Grupo']!=''){
                $Grupo = $this->modelo->ddl_grupos(false,$cliente['Grupo']);
                if(count($Grupo)>0)
                {
                    $Grupo = $Grupo[0];
                 // print_r($Estado_Beneficiario);die();
                }
            }
            if($cliente['Est_Civil']!='.' && $cliente['Est_Civil']!=''){
                $EstadoCivil = $this->modelo->estado_civil($cliente['Est_Civil']);
                if(count($EstadoCivil)>0)
                {
                    $EstadoCivil = $EstadoCivil[0];
                 // print_r($Estado_Beneficiario);die();
                }
            }

             if($cliente['Casilla']!='.' && $cliente['Casilla']!=''){
                $EscolaridadPrincipal = $this->modelo->nivel_escolaridad($cliente['Casilla']);
                if(count($EscolaridadPrincipal)>0)
                {
                    $EscolaridadPrincipal = $EscolaridadPrincipal[0];
                 // print_r($Estado_Beneficiario);die();
                }
            }
        }
        // print_r($cliente_datos_extra);die();
        if(count($cliente_datos_extra)!=0)
        {
            $cliente_datos_extra = $cliente_datos_extra[0];

            if($cliente_datos_extra['CodigoA2']!='.' && $cliente_datos_extra['CodigoA2']!=''){
                $Tipo_Entrega = $this->modelo->Tipo_Entrega($cliente_datos_extra['CodigoA2']);
                if(count($Tipo_Entrega)>0)
                {
                    $Tipo_Entrega = $Tipo_Entrega[0];
                 // print_r($Tipo_Entrega);die();
                }
            }
            if($cliente_datos_extra['Envio_No']!='.' && $cliente_datos_extra['Envio_No']!=''){
                $Frecuencia = $this->modelo->Frecuencia($cliente_datos_extra['Envio_No']);
                if(count($Frecuencia)>0)
                {
                    $Frecuencia = $Frecuencia[0];
                 // print_r($Frecuencia);die();
                }
            }
            if($cliente_datos_extra['Acreditacion']!='.' && $cliente_datos_extra['Acreditacion']!=''){
                $Accion_Social = $this->modelo->Accion_Social($cliente_datos_extra['Acreditacion']);
                if(count($Accion_Social)>0)
                {
                    $Accion_Social = $Accion_Social[0];
                 // print_r($Accion_Social);die();
                }
            }
            if($cliente_datos_extra['Tipo_Dato']!='.' && $cliente_datos_extra['Tipo_Dato']!=''){
                $Vulnerabilidad = $this->modelo->Vulnerabilidad($cliente_datos_extra['Tipo_Dato']);
                if(count($Vulnerabilidad)>0)
                {
                    $Vulnerabilidad = $Vulnerabilidad[0];
                 // print_r($Vulnerabilidad);die();
                }
            }
             if($cliente_datos_extra['Cod_Fam']!='.' && $cliente_datos_extra['Cod_Fam']!=''){
                $Tipo_Atencion = $this->modelo->Tipo_Atencion($cliente_datos_extra['Cod_Fam']);
                if(count($Tipo_Atencion)>0)
                {
                    $Tipo_Atencion = $Tipo_Atencion[0];
                 // print_r($Vulnerabilidad);die();
                }
            }
            if($cliente_datos_extra['CodigoB']!='.' && $cliente_datos_extra['CodigoB']!=''){
                $Programas = $this->modelo->Programas($cliente_datos_extra['CodigoB']);
                if(count($Programas)>0)
                {
                    $Programas = $Programas[0];
                }
            }

        }

        $data = array('cliente'=>$cliente,
                    'cliente_datos_extra'=>$cliente_datos_extra,
                    'Tipo_Beneficiario'=>$Tipo_Beneficiario,
                    'tipoDonacion'=>$tipoDonacion,
                    'Estado_Beneficiario'=>$Estado_Beneficiario,
                    'Tipo_Entrega'=>$Tipo_Entrega,
                    'Frecuencia'=>$Frecuencia,
                    'Accion_Social'=>$Accion_Social,
                    'Vulnerabilidad'=>$Vulnerabilidad,
                    'Tipo_Atencion'=>$Tipo_Atencion,
                    'Programas'=>$Programas,
                    'Grupo'=>$Grupo,
                    'EstadoCivil'=>$EstadoCivil,
                    'Escolaridad'=>$EscolaridadPrincipal,
                );

        return $data;

    }

    // =========================================================== familias ==========================================

    function parentesco($query)
    {
        $lista = array();
        $data = $this->modelo->parentesco();
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Codigo'],'text'=>$value['Descripcion'],'data'=>$value);
            // code...
        }
        return $lista;

    }
    function sexo($query)
    {
        $lista = array();
        $data = $this->modelo->sexo();
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Codigo'],'text'=>$value['Descripcion'],'data'=>$value);
            // code...
        }
        return $lista;

    }
    function rango_edades($query)
    {
        $lista = array();
        $data = $this->modelo->rango_edades();
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Codigo'],'text'=>$value['Descripcion'],'data'=>$value);
            // code...
        }
        return $lista;

    }
    function estado_civil($query)
    {
        $lista = array();
        $data = $this->modelo->estado_civil();
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Codigo'],'text'=>$value['Descripcion'],'data'=>$value);
            // code...
        }
        return $lista;

    }
    function nivel_escolaridad($query)
    {
        $lista = array();
        $data = $this->modelo->nivel_escolaridad();
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Codigo'],'text'=>$value['Descripcion'],'data'=>$value);
            // code...
        }
        return $lista;

    }
    function tipo_institucion($query)
    {
        $lista = array();
        $data = $this->modelo->tipo_institucion();
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Codigo'],'text'=>$value['Descripcion'],'data'=>$value);
            // code...
        }
        return $lista;

    }
    function vulnerabilidadFam($query)
    {
        $lista = array();
        $data = $this->modelo->vulnerabilidadFam();
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Codigo'],'text'=>$value['Descripcion'],'data'=>$value);
            // code...
        }
        return $lista;

    }
    function  tipo_enfermedad($query)
    {
        $lista = array();
        $data = $this->modelo->tipo_enfermedad();
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Codigo'],'text'=>$value['Descripcion'],'data'=>$value);
            // code...
        }
        return $lista;

     }
    function tipo_discapacidad($query)
    {
        $lista = array();
        $data = $this->modelo->tipo_discapacidad();
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Codigo'],'text'=>$value['Descripcion'],'data'=>$value);
            // code...
        }
        return $lista;

    }

}
?>