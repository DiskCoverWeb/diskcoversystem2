<?php 
    include(dirname(__DIR__, 2) . '/modelo/inscripciones/voluntarios.php');

    $controlador = new InscVoluntariosC();

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

    class InscVoluntariosC{
        private $modelo;

        function __construct(){
            $this->modelo = new InscVoluntariosM();
        }

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