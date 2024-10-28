<?php
/** 
 * AUTOR DE RUTINA	: Dallyana Vanegas
 * FECHA CREACION	: 03/01/2024
 * FECHA MODIFICACION: 23/02/2024
 * DESCIPCION : Actualizacion de creacion de directorio para enviar rubros
 */

include(dirname(__DIR__, 2) . '/modelo/facturacion/FRecaudacionBancosCxCM.php');
require_once ('HistorialFacturasC.php');
require_once(dirname(__DIR__, 3) . '/lib/phpmailer/enviar_emails.php');

$controlador = new FRecaudacionBancosCxCC();
if (isset($_GET['Form_Activate'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Form_Activate($parametros));
}

if (isset($_GET['DCEntidad'])) {
    //$parametros = $_POST['parametros'];
    echo json_encode($controlador->DCEntidad());
}

if (isset($_GET['DCGrupoI_DCGrupoF'])) {
    //$parametros = $_POST['parametros'];
    echo json_encode($controlador->DCGrupoI_DCGrupoF());
}

if (isset($_GET['DCBanco'])) {
    //$parametros = $_POST['parametros'];
    echo json_encode($controlador->DCBanco());
}

if (isset($_GET['FechaValida'])) {
    echo json_encode(FechaValida($_POST['fecha']));
}

if (isset($_GET['AdoAux'])) {
    echo json_encode($controlador->AdoAux());
}

if (isset($_GET['AdoProducto'])) {
    echo json_encode($controlador->AdoProducto());
}

if (isset($_GET['LeerCampoEmpresa'])) {
    echo json_encode(Leer_Campo_Empresa($_POST['campo']));
}

if (isset($_GET['MBFechaFLostFocus'])) {
    $fecha = $_POST['fecha'];
    echo json_encode($controlador->MBFechaF_LostFocus($fecha));
}

if (isset($_GET['LeerSeteosCtas'])) {
    echo json_encode(Leer_Seteos_Ctas());
}

if (isset($_GET['EnviarRubros'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Enviar_Rubros($parametros));
}

if (isset($_GET['RecibirAbonos'])) {
    $MBFechaI = $_POST['MBFechaI'];
    $MBFechaF = $_POST['MBFechaF'];
    $TxtOrden = $_POST['TxtOrden'];
    $DCEntidad = $_POST['DCEntidad'];
    $DCBanco = $_POST['DCBanco'];
    $CheqSat = $_POST['CheqSat'];

    $parametros = array(
        'MBFechaI' => $MBFechaI,
        'MBFechaF' => $MBFechaF,
        'TxtOrden' => $TxtOrden,
        'DCEntidad' => $DCEntidad,
        'DCBanco' => $DCBanco,
        'CheqSat' => $CheqSat,
    );

    if (isset($_FILES['archivoBanco']) && $_FILES['archivoBanco']['error'] == UPLOAD_ERR_OK) {
        $archivo = $_FILES['archivoBanco'];
        $carpetaDestino = dirname(__DIR__, 3) . "/TEMP/BANCO/ABONOS/";
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }
        $nombreArchivoDestino = $carpetaDestino . basename($archivo['name']);
        if (move_uploaded_file($archivo['tmp_name'], $nombreArchivoDestino)) {
            $parametros['NombreArchivo'] = $nombreArchivoDestino;
            echo json_encode($controlador->Recibir_Abonos($parametros));
        } else {
            echo json_encode(array("res" => 'Error', "message" => "Error al subir el archivo"));
        }
    }
}

if (isset($_GET['VisualizarArchivo'])) {
    $parametros = array();
    if (isset($_FILES['archivoBanco']) && $_FILES['archivoBanco']['error'] == UPLOAD_ERR_OK) {
        $archivo = $_FILES['archivoBanco'];
        $carpetaDestino = dirname(__DIR__, 3) . "/TEMP/BANCO/ABONOS/";
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }
        $nombreArchivoDestino = $carpetaDestino . basename($archivo['name']);
        if (move_uploaded_file($archivo['tmp_name'], $nombreArchivoDestino)) {
            $parametros['NombreArchivo'] = $nombreArchivoDestino;
            echo json_encode($controlador->Visualizar_Archivo($parametros));
        } else {
            echo json_encode(array("res" => 'Error', "message" => "Error al subir el archivo"));
        }
    }
}

if (isset($_GET['EliminaArchivosTemporales'])) {
    $tempFilePath = $_POST['tempFilePath'];
    echo json_encode($controlador->EliminaArchivosTemporales($tempFilePath));
}

class FRecaudacionBancosCxCC
{
    private $modelo;
    private $email;
    private $historico;

    function __construct()
    {
        $this->modelo = new FRecaudacionBancosCxCM();
        $this->email = new enviar_emails();
        $this->historico = new HistorialFacturasC();
    }

    function DCGrupoI_DCGrupoF()
    {
        $datos = $this->modelo->SelectCombo_DCGrupoI_DCGrupoF();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $value) {
                $list[] = array('Grupo' => $value['Grupo']);
            }
            return $list;
        }
        return $list;
    }

    function DCBanco()
    {
        $DCBancoCombo = $this->modelo->SelectDB_Combo_DCBanco();
        $list = array();
        if (count($DCBancoCombo) > 0) {
            foreach ($DCBancoCombo as $value) {
                $list[] = array(
                    'NomCuenta' => $value['NomCuenta'],
                );
            }
        }
        $AdoBanco = $this->modelo->SelectDB_Combo_DCBanco();
        $Cta_Banco = G_NINGUNO;
        $mensaje = "";
        if (count($AdoBanco) > 0) {
            foreach ($AdoBanco as $value) {
                if ($_SESSION['SETEOS']['Cta_Del_Banco'] == $value['Codigo']) {
                    $DCBanco = $value["Codigo"] . ' ' . $value["NomCuenta"];
                    $Cta_Banco = $_SESSION['SETEOS']['Cta_Del_Banco'];
                    break;
                }
            }

            if ($Cta_Banco == G_NINGUNO) {
                $mensaje = "No existen cuentas asignadas o no están bien establecidas las cuentas contables";
                $DCBanco = $value["Codigo"] . ' ' . $value["NomCuenta"];
                $Cta_Banco = SinEspaciosIzq($DCBanco);
            }
        } else {
            $mensaje = "No existen cuentas asignadas o no están bien establecidas las cuentas contables";
        }
        return array("DCBanco" => $DCBanco, "Cta_Banco" => $Cta_Banco, "mensaje" => $mensaje, "list" => $list);
    }

    function DCEntidad()
    {
        $datos = $this->modelo->SelectDB_Combo_DCEntidad();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $value) {
                $list[] = array(
                    'Descripcion' => $value['Descripcion'],
                    'Abreviado' => $value['Abreviado']
                );
            }
            return $list;
        }
        return $list;
    }

    function AdoAux()
    {
        $datos = $this->modelo->AdoAux();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $key => $value) {
                $list[] = array(
                    'Cta_Cobrar' => $value['CxC'],
                    'CxC_Clientes' => $value['Concepto'],
                    'Individual' => $value['Individual'],
                    'TipoFactura' => $value['Fact'],
                );
            }
            return $list;
        }
        return $list;
    }

    function AdoProducto()
    {
        $datos = $this->modelo->Select_AdoProducto();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $value) {
                $list[] = array('Codigo_Inv' => $value['Codigo_Inv']);
            }
            return $list;
        }
        return $list;
    }

    function MBFechaF_LostFocus($fecha)
    {
        $datos = $this->modelo->MBFechaF_LostFocus();
        if (count($datos) > 0) {
            $this->modelo->MBFechaF_LostFocusUpdate($fecha);
        }
    }

    function Enviar_Rubros($parametros)
    {
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $CheqMatricula = $parametros['CheqMatricula'];
        $CheqPend = $parametros['CheqPend'];
        $DCBanco = $parametros['DCBanco'];
        $Cta_Bancaria = SinEspaciosDer($parametros['DCBanco']);
        $Tipo_Carga = Leer_Campo_Empresa("Tipo_Carga_Banco");
        $Costo_Banco = Leer_Campo_Empresa("Costo_Bancario");

        $Tabulador = '';

        $SumaBancos = 0;
        FechaValida($MBFechaI);
        FechaValida($MBFechaF);
        $TipoDoc = ($CheqMatricula == 1) ? "0" : "1";

        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $Cta_Cobrar = G_NINGUNO;
        $FechaFinal = $MBFechaF;
        $FechaTexto = date('Y-m-d H:i:s');
        $FechaTexto1 = date('m/d/Y', strtotime($MBFechaI));
        $Mifecha = BuscarFecha($MBFechaI);
        $MiMes = sprintf("%02d", date('m', strtotime($MBFechaI)));
        $FechaFin = BuscarFecha($MBFechaF);
        $TextoImprimio = "";

        Eliminar_Nulos_SP("Facturas");

        $this->modelo->Query1EnviarRubros();
        $query2 = $this->modelo->Query2EnviarRubros($parametros);
        $AdoPendiente = array();
        if (count($query2) > 0) {
            foreach ($query2 as $value) {
                $AdoPendiente[] = array(
                    'Grupo' => $value['Grupo'],
                    'Cliente' => $value['Cliente'],
                    'Anio_Mes' => $value['Anio_Mes'],
                    'Serie' => $value['Serie'],
                    'Factura' => $value['Factura'],
                    'CI_RUC' => $value['CI_RUC'],
                    'CodigoC' => $value['CodigoC'],
                    'Actividad' => $value['Actividad'],
                    'Direccion' => $value['Direccion'],
                    'Fecha' => $value['Fecha'],
                    'Saldo_MN' => $value['Saldo_MN'],
                    'Total_MN' => $value['Total_MN']
                );
            }
        }
        //Detalle de las Facturas Emitidas del mes     
        $query3 = $this->modelo->Query3EnviarRubros($parametros);
        $AdoDetalle = array();
        if (count($query3) > 0) {
            foreach ($query3 as $value) {
                $AdoDetalle[] = array(
                    'Fecha' => $value['Fecha'],
                    'Cliente' => $value['Cliente'],
                    'Grupo' => $value['Grupo'],
                    'CI_RUC' => $value['CI_RUC'],
                    'Direccion' => $value['Direccion'],
                    'Item_Banco' => $value['Item_Banco'],
                    'Desc_Item' => $value['Desc_Item'],
                    'CodigoC' => $value['CodigoC'],
                    'Total' => $value['Total'],
                    'Total_Desc' => $value['Total_Desc'],
                    'Total_Desc2' => $value['Total_Desc2'],
                    'Producto' => $value['Producto'],
                    'Mes' => $value['Mes'],
                    'Ticket' => $value['Ticket'],
                    'Mes_No' => $value['Mes_No'],

                );
            }
        }
        //Facturas Emitidas del mes
        $query4 = $this->modelo->Query4EnviarRubros($parametros);
        $AdoFactura = array();
        if (count($query4) > 0) {
            foreach ($query4 as $value) {
                $AdoFactura[] = array(
                    'CodigoC' => $value['CodigoC'],
                    'Actividad' => $value['Actividad'],
                    'Cliente' => $value['Cliente'],
                    'Grupo' => $value['Grupo'],
                    'CI_RUC' => $value['CI_RUC'],
                    'Direccion' => $value['Direccion'],
                    'Saldo_Pend' => $value['Saldo_Pend']
                );
            }
        }
        //Facturas Emitidas del mes
        $query5 = $this->modelo->Query5EnviarRubros($parametros);
        $AdoAux = array();
        if (count($query5) > 0) {
            foreach ($query5 as $value) {
                $AdoAux[] = array(
                    'Fecha' => $value['Fecha'],
                    'Cliente' => $value['Cliente'],
                    'GrupoNo' => $value['Grupo'],
                    'CI_RUC' => $value['CI_RUC'],
                    'Direccion' => $value['Direccion'],
                    'Casilla' => $value['Casilla'],
                    'CodigoC' => $value['CodigoC'],
                    'Saldo_MN' => $value['Saldo_MN'],
                    'Total_MN' => $value['Total_MN']
                );
            }
        }

        switch ($parametros['DCEntidad']) {

            case "PICHINCHA":
                $FechaFin = BuscarFecha(date("Y-m-t", strtotime($MBFechaF)));
                $parametros = array(
                    'MBFechaI' => $MBFechaI,
                    'MBFechaF' => $MBFechaF,
                    'AdoPendiente' => $AdoPendiente,
                    'Cta_Bancaria' => $Cta_Bancaria,
                    'Tipo_Carga' => $Tipo_Carga,
                    'CheqMatricula' => $CheqMatricula
                );
                //print_r($parametros2['Tipo_Carga']);
                $res = $this->Generar_Pichincha($parametros);
                break;
            case "BGR_EC":
                $FechaFin = BuscarFecha(date("Y-m-t", strtotime($MBFechaF)));
                $parametros = array(
                    'MBFechaI' => $MBFechaI,
                    'MBFechaF' => $MBFechaF,
                    'AdoAux' => $AdoAux,
                    'AdoPendiente' => $AdoPendiente,
                    'CheqPend' => $CheqPend,
                    'CheqMatricula' => $CheqMatricula
                );
                $res = $this->Generar_BGR_EC($parametros);
                break;
            case "INTERNACIONAL":
                $FechaFin = BuscarFecha(date("Y-m-t", strtotime($MBFechaF)));
                $parametros = array(
                    'MBFechaI' => $MBFechaI,
                    'MBFechaF' => $MBFechaF,
                    'AdoDetalle' => $AdoDetalle,
                    'AdoFactura' => $AdoFactura,
                    'Cta_Bancaria' => $Cta_Bancaria
                );
                $res = $this->Generar_Internacional($parametros);
                break;
            case "BOLIVARIANO":
                $FechaFin = BuscarFecha(date("Y-m-t", strtotime($MBFechaF)));
                $parametros = array(
                    'CheqMatricula' => $CheqMatricula,
                    'MBFechaF' => $MBFechaF,
                    'AdoAux' => $AdoAux,
                    'AdoPendiente' => $AdoPendiente,
                    'DCBanco' => $DCBanco,
                    'CheqPend' => $CheqPend,
                );
                $res = $this->Generar_Bolivariano($parametros);
                break;
            case "PACIFICO":
                $FechaFin = BuscarFecha(date("Y-m-t", strtotime($MBFechaF)));
                $parametros = array(
                    'MBFechaI' => $MBFechaI,
                    'MBFechaF' => $MBFechaF,
                    'AdoPendiente' => $AdoPendiente,
                    'Costo_Banco' => $Costo_Banco,
                );
                $res = $this->Generar_Pacifico($parametros);
                break;
            case "COOPJEP":
                $FechaFin = BuscarFecha(date("Y-m-t", strtotime($MBFechaF)));
                $parametros = array(
                    'MBFechaI' => $MBFechaI,
                    'MBFechaF' => $MBFechaF,
                    'CheqMatricula' => $CheqMatricula,
                );
                $res = $this->Generar_Coop_Jep($parametros);
                break;
            case "PRODUBANCO":
                $FechaFin = BuscarFecha(date("Y-m-t", strtotime($MBFechaF)));
                $parametros = array(
                    'MBFechaI' => $MBFechaI,
                    'MBFechaF' => $MBFechaF,
                    'AdoDetalle' => $AdoDetalle,
                    'DCBanco' => $DCBanco,
                    'Cta_Bancaria' => $Cta_Bancaria
                );
                $res = $this->Generar_Produbanco($parametros);
                break;
            case "GUAYAQUIL":
                $FechaFin = BuscarFecha(date("Y-m-t", strtotime($MBFechaF)));
                $parametros = array(
                    'MBFechaI' => $MBFechaI,
                    'MBFechaF' => $MBFechaF,
                    'AdoPendiente' => $AdoPendiente,
                    'DCBanco' => $DCBanco,
                    'Costo_Banco' => $Costo_Banco,
                );
                $res = $this->Generar_Guayaquil($parametros);
                break;
            default:
                $res = array('res' => 'Error');
                break;
        }

        // Facturas Emitidas del mes
        // Generacion del Resumen de la facturacion del mes
        $Tabulador = ";";
        $directorioBase = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/";
        if (!is_dir($directorioBase)) {
            mkdir($directorioBase, 0777, true);
        }
        $RutaGeneraFile = $directorioBase . "RESUMEN_MES_" . substr(mesesLetras(date('m')), 0, 3) . "-" . date('Y') . "_" . $Cta_Bancaria . ".csv";
        $NumFileFacturas = fopen($RutaGeneraFile, "w");
        $Contador = 0;
        $FechaTexto = buscarFecha($MBFechaI);

        if (count($AdoPendiente) > 0) {
            fwrite($NumFileFacturas, "No." . $Tabulador);
            fwrite($NumFileFacturas, "GRUPO" . $Tabulador);
            fwrite($NumFileFacturas, "CODIGO" . $Tabulador);
            fwrite($NumFileFacturas, "BENEFICIARIO" . $Tabulador);
            fwrite($NumFileFacturas, "DETALLE" . $Tabulador);
            fwrite($NumFileFacturas, "AÑO-MES" . $Tabulador);
            fwrite($NumFileFacturas, "SERIE" . $Tabulador);
            fwrite($NumFileFacturas, "FACTURA No" . $Tabulador);
            fwrite($NumFileFacturas, "TOTAL FACTURA" . $Tabulador);
            fwrite($NumFileFacturas, "SALDO FACTURA" . $Tabulador);
            fwrite($NumFileFacturas, "\n");

            foreach ($AdoPendiente as $valor) {
                $Contador++;
                $Grupo_No = $valor["Grupo"];
                $Codigo = $valor["CodigoC"];
                $CodigoCli = $valor["CI_RUC"];
                $NombreCliente = Sin_Signos_Especiales($valor["Cliente"]);
                $Codigo1 = Sin_Signos_Especiales($valor["Direccion"]);
                $Codigo2 = $valor["Anio_Mes"];
                $SerieFactura = "'" . $valor["Serie"];
                $Factura_No = $valor["Factura"];
                $Total_Factura = $valor["Total_MN"];
                $Total_Pagar = $valor["Saldo_MN"];
                $Total = $Total_Factura - $Total_Pagar;

                // Empieza la trama por Alumno
                fwrite($NumFileFacturas, $Contador . $Tabulador);
                fwrite($NumFileFacturas, $Grupo_No . $Tabulador);
                fwrite($NumFileFacturas, $CodigoCli . $Tabulador);
                fwrite($NumFileFacturas, $NombreCliente . $Tabulador);
                fwrite($NumFileFacturas, $Codigo1 . $Tabulador);
                fwrite($NumFileFacturas, $Codigo2 . $Tabulador);
                fwrite($NumFileFacturas, $SerieFactura . $Tabulador);
                fwrite($NumFileFacturas, $Factura_No . $Tabulador);
                fwrite($NumFileFacturas, $Total_Factura . $Tabulador);
                fwrite($NumFileFacturas, $Total_Pagar . $Tabulador);
                fwrite($NumFileFacturas, "\n");
            }
        }

        fclose($NumFileFacturas);
        $Nombre3 = "RESUMEN_MES_" . substr(mesesLetras(date('m')), 0, 3) . "-" . date('Y') . "_" . $Cta_Bancaria . ".csv";
        $res['Nombre3'] = $Nombre3;
        return $res;
    }

    function Recibir_Abonos($parametros)
    {
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        //$Archivo = $parametros['Archivo'];
        $TextoBanco = $parametros['DCEntidad'];
        $TxtOrden = $parametros['TxtOrden'];
        $DCBanco = $parametros['DCBanco'];
        $CheqSat = $parametros['CheqSat'];
        $Tipo_Carga = Leer_Campo_Empresa("Tipo_Carga_Banco");
        //$Label4 = G_NINGUNO;
        FechaValida($MBFechaI);
        FechaValida($MBFechaF);

        $NombreArchivo = $parametros['NombreArchivo'];
        $RutaGeneraFile = $NombreArchivo;
        //print_r($RutaGeneraFile);

        $Contador = 0;
        $CantCampos = 0;
        $TotalIngreso = 0;
        $Separador = G_NINGUNO;
        $Orden_Pago = G_NINGUNO;
        $OrdenValida = false;
        $CamposFile = [];

        $NumFile = fopen($RutaGeneraFile, "r");

        if ($NumFile) {
            while (!feof($NumFile)) {
                $Cod_Field = fgets($NumFile);
                if ($Separador == G_NINGUNO) {
                    if (strpos($Cod_Field, "\t") !== false) {
                        $Separador = "\t";
                    }
                }
                if ($Contador == 0) {
                    while (strlen($Cod_Field) > 2) {
                        $No_Hasta = strpos($Cod_Field, $Separador);
                        $CamposFile[$CantCampos]['Campo'] = "C" . sprintf("%02d", $CantCampos);
                        $CampoTemp = trim(substr($Cod_Field, 0, $No_Hasta));
                        switch ($TextoBanco) {
                            case "PICHINCHA":
                                if ($CantCampos == 14 && $TxtOrden == $CampoTemp) {
                                    $Orden_Pago = $CampoTemp;
                                    $OrdenValida = true;
                                }
                                break;
                            default:
                                $OrdenValida = true;
                                break;
                        }
                        $Cod_Field = trim(substr($Cod_Field, $No_Hasta + 1));
                    }
                }
                $Contador++;
            }
            fclose($NumFile);
        }
        //echo('camposfile');
        //print_r($CamposFile);  

        $Total_Alumnos = $Contador; //26
        if (!$OrdenValida) { //false
            $mensaje = "La información del archivo no pertenece a la Orden No. " . $TxtOrden . " registrada del Banco, vuelva a seleccionar el documento correcto.";
            return array('res' => 'Error', 'mensaje' => $mensaje);
        }

        //procedemos a borrar los abonos recibidos
        $NumFile = fopen($RutaGeneraFile, "r");

        while (!feof($NumFile)) {
            $Cod_Field = fgets($NumFile);
            // Colocamos los datos del archivo en un array de texto
            $CantCampos = 0;

            while (strlen($Cod_Field) > 2) {
                $No_Hasta = strpos($Cod_Field, $Separador);
                $CamposFile[$CantCampos]['Valor'] = trim(substr($Cod_Field, 0, $No_Hasta - 1));
                $Cod_Field = trim(substr($Cod_Field, $No_Hasta + 1));
                $CantCampos++;
            }

            // Procedemos a eliminar los abonos que se encuentran en el archivo, por si volvemos a subir
            switch ($TextoBanco) {
                case "PICHINCHA":
                    $TipoDoc = $CamposFile[7]['Valor'];
                    $TipoProc = SinEspaciosDer($TipoDoc);
                    $TA['Serie'] = SinEspaciosDer(substr($TipoDoc, 0, strlen($TipoDoc) - strlen($TipoProc)));
                    $TA['Factura'] = intval($CamposFile[35]['Valor']);
                    $TA['Recibo_No'] = sprintf("%010d", intval($CamposFile[34]['Valor']));

                    $this->modelo->EliminarAbonos($TA);
            }
        }
        fclose($NumFile);

        //print_r(count($CamposFile));
        $FA['Serie'] = G_NINGUNO;
        $FA['TC'] = G_NINGUNO;
        $FA['Factura'] = 0;
        Actualizar_Abonos_Facturas_SP($FA);

        $AbonosAnticipados = 0;
        $Total_Dep_Confirmar = 0;
        $Trans_No = 200;
        BorrarAsientos(true);
        $SubCtaGen = Leer_Seteos_Ctas("Cta_Anticipos_Clientes");
        $Cta_Del_Banco = trim(SinEspaciosIzq($DCBanco));
        $Contrato_No = G_NINGUNO;

        $TxtFile = "";
        $Fecha_Tope = FechaSistema();
        $Total_Costo_Banco = 0;
        $TextoImprimio = "";

        // Alumnos/Clientes que están activados para generar las facturas
        $AdoClientes = $this->modelo->AlumnosClientesActivados();
        $MBFechaI = FechaValida($MBFechaI);
        //$Mifecha = BuscarFecha($MBFechaI);
        $FechaTexto = $MBFechaI;
        $DiarioCaja = ReadSetDataNum('Recibo_No', True, True);

        $RutaGeneraFile = $NombreArchivo;

        if ($RutaGeneraFile !== "") {
            $TotalIngreso = 0;
            $Contador = 0;
            $FileResp = 0;
            //establecemos los campos del archivo plano del banco
            $NumFile = fopen($RutaGeneraFile, "r");
            $Total_Alumnos = 0;
            $FechaTexto = FechaSistema();
            $TxtFile = "";

            while (!feof($NumFile)) {
                $Cod_Field = fgets($NumFile);
                $Cod_Field = str_replace('"', '', $Cod_Field);
                $TxtFile .= $Cod_Field . "\n";

                // Comenzamos la subida de los Abonos
                $CantCampos = 0;
                while (strlen($Cod_Field) > 2) {
                    $No_Hasta = strpos($Cod_Field, $Separador);
                    $CamposFile[$CantCampos]['Valor'] = trim(substr($Cod_Field, 1, $No_Hasta - 1));
                    $Cod_Field = trim(substr($Cod_Field, $No_Hasta + 1));
                    $CantCampos++;
                }

                // Actualizamos de qué alumnos vamos a ingresar el abono
                $TA['Serie'] = G_NINGUNO;
                $TA['Factura'] = 0;
                $TA['Fecha'] = FechaSistema();
                $TA['CodigoC'] = G_NINGUNO;
                $TA['Recibo_No'] = "0000000000";
                $CodigoCli = G_NINGUNO;
                $CodigoP = "0";
                $Proceso_Ok = "PROCESO OK";

                switch ($TextoBanco) {
                    case "PICHINCHA":
                        // Código específico para Pichincha
                        if ($Tipo_Carga == 1) {
                            $CodigoP = trim(strval(intval(substr($Cod_Field, 25, 19))));
                            $FechaTexto = substr($Cod_Field, 205, 2) . "/" . substr($Cod_Field, 207, 2) . "/" . substr($Cod_Field, 209, 4);
                        } else {
                            // Serie de la Factura
                            $TipoDoc = $CamposFile[7]['Valor'];
                            $TipoProc = SinEspaciosDer($TipoDoc);
                            $TipoDoc = trim(substr($TipoDoc, 0, strlen($TipoDoc) - strlen($TipoProc)));

                            $TA['Serie'] = SinEspaciosDer($TipoDoc);
                            $TA['Factura'] = intval($CamposFile[35]['Valor']);
                            $TA['CodigoC'] = $CamposFile[4]['Valor'];
                            $TA['Fecha'] = str_replace(" ", "/", $CamposFile[25]['Valor']);
                            $TA['Recibo_No'] = sprintf("%010d", intval($CamposFile[34]['Valor']));
                            $TA['Abono'] = intval($CamposFile[27]['Valor']);

                            $Proceso_Ok = trim($CamposFile[22]['Valor']);

                            if ($Proceso_Ok === "REVERSO OK") {
                                $CodigoP = str_pad(intval($CodigoP), 13, '0', STR_PAD_LEFT);
                            }
                            $CodigoP = $TA['CodigoC'];

                            // Detalle del Abono
                            if (trim($CamposFile[29]['Valor']) === "EFE") {
                                $TA['Banco'] = "PAGO EN EFECTIVO";
                                $TA['Cheque'] = "VENT.: " . str_replace(" ", "h", substr($CamposFile[26]['Valor'], 12, 5)) . "s";
                            } else {
                                $TA['Banco'] = "TRANS. " . $CamposFile[29]['Valor'] . "|" . $CamposFile[16]['Valor'];
                                $TA['Cheque'] = $CamposFile[18]['Valor'] . "-" . $CamposFile[19]['Valor'] . ": " . str_replace(" ", "h", substr($CamposFile[26]['Valor'], 12, 5)) . "s";
                            }
                        }
                        break;
                    case "BOLIVARIANO":
                        if ($CheqSat == 1) {
                            $CodigoP = substr($Cod_Field, 13, 8);
                        } else {
                            $CodigoP = substr($Cod_Field, 0, 8);
                        }
                        if ($Total_Alumnos == 0) {
                            $FechaTexto = substr($Cod_Field, 11, 2) . "/" . substr($Cod_Field, 9, 2) . "/" . substr($Cod_Field, 5, 4);
                            $CodigoP = G_NINGUNO;
                        }
                        break;
                    case "BGR_EC":
                        if ($Tipo_Carga == 1) {
                            $CodigoP = trim(strval(intval(substr($Cod_Field, 24, 19))));
                            $FechaTexto = substr($Cod_Field, 204, 2) . "/" .
                                substr($Cod_Field, 206, 2) . "/" .
                                substr($Cod_Field, 208, 4);
                        } else {
                            $CodigoP = $CamposFile[10]['Valor'];
                            $FechaTexto = str_replace(" ", "/", $CamposFile[24]['Valor']);
                            $HoraTexto = str_replace(" ", ":", $CamposFile[25]['Valor']);
                            $CodigoB = $CamposFile[28]['Valor'] . ":" . $CamposFile[19]['Valor'] . "-" . str_replace(" ", ":", $CamposFile[25]['Valor']);
                        }
                        break;
                    case "INTERNACIONAL":
                        $CodigoP = trim(strval(intval(substr($Cod_Field, 24, 19))));
                        $FechaTexto = substr($Cod_Field, 204, 2) . "/" .
                            substr($Cod_Field, 206, 2) . "/" .
                            substr($Cod_Field, 208, 4);
                        break;
                    case "PACIFICO":
                        if ($CheqSat) {
                            $CodigoP = $CamposFile[16]['Valor'];
                            $FechaTexto = date('d/m/Y', strtotime($CamposFile[11]['Valor']));

                        } else {
                            if ($Total_Alumnos !== 0) {
                                $CodigoP = $CamposFile[3]['Valor'];
                                $FechaTexto = substr($CamposFile[5]['Valor'], 0, 10);
                            }
                        }
                        break;
                    case "PRODUBANCO":
                        $CodigoP = $CamposFile[6]['Valor'];
                        $FechaTexto = $CamposFile[11]['Valor'];
                        $CodigoB = $CamposFile[13]['Valor'];

                        $NoAnio = intval(substr(trim($CodigoB), 0, 4));
                        if ($NoAnio <= 1900 && strtotime($FechaTexto)) {
                            $NoMeses = date('n', strtotime($FechaTexto));
                            $NoAnio = date('Y', strtotime($FechaTexto));
                            $Mes = MesesLetras($NoMeses);
                        }
                        break;
                    case "INTERMATICO":
                        $CodigoP = $CamposFile[6]['Valor'];
                        $FechaTexto = $CamposFile[0]['Valor'];
                        if (strlen($FechaTexto) > 10) {
                            $FechaTexto = FechaSistema();
                            $CodigoP = G_NINGUNO;
                        }
                        $Mifecha = $FechaTexto;
                        break;
                    case "COOPJEP":
                        $CodigoP = trim($CamposFile[15]['Valor']);
                        $FechaTexto = $CamposFile[0]['Valor'];
                        break;

                    case "CACPE":
                        $CodigoP = strval(intval($CamposFile[5]['Valor']));
                        $FechaTexto = substr($CamposFile[7]['Valor'], 3, 2) . "/" .
                            substr($CamposFile[7]['Valor'], 0, 2) . "/" .
                            substr($CamposFile[7]['Valor'], 6, 4);
                        break;
                    default:
                        $CodigoP = G_NINGUNO;
                        $TipoDoc = $CamposFile[0]['Valor'];
                        $FechaTexto = $CamposFile[1]['Valor'];
                        $SerieFactura = substr($CamposFile[2]['Valor'], 0, 3) . substr($CamposFile[2]['Valor'], 4, 3);
                        $Factura_No = intval(substr($CamposFile[2]['Valor'], 8, 10));
                        $Autorizacion = $CamposFile[3]['Valor'];

                        $AdoFactura = $this->modelo->sqlCaseElse($TipoDoc, $SerieFactura, $Autorizacion, $Factura_No);

                        if (count($AdoFactura) > 0) {
                            foreach ($AdoFactura as $valor) {
                                $CodigoP = $valor["CI_RUC"];
                                $CodigoCli = $valor["CodigoC"];
                            }
                        }
                        break;
                }

                $Si_No = true;
                if (count($AdoClientes) > 0) {
                    foreach ($AdoClientes as $valor) {
                        if (strlen($CodigoP) <= 10 && $Si_No) {
                            if ($valor['CI_RUC'] == $CodigoP) {
                                $TA['CodigoC'] = $valor['Codigo'];
                                $NombreCliente = $valor['Cliente'];
                                $FA['CodigoC'] = $TA['CodigoC'];
                                $FA['Cliente'] = $NombreCliente;
                                $FA['EmailC'] = $valor['Email'];
                                $FA['EmailC2'] = $valor['Email2'];
                                $FA['EmailR'] = $valor['EmailR'];
                                $Si_No = false;
                            } else {
                                $CodigoP = "0" . $CodigoP;
                            }
                        }
                    }
                }
                if (strlen($CodigoP) > 10) {
                    $TA['CodigoC'] = G_NINGUNO;
                }

                if ($TA['CodigoC'] != G_NINGUNO) {

                    $TotalIngreso += $TA['Abono'];
                    $AdoAbono = $this->modelo->sqlIngresarAbonos($TA);
                    $AbonosPar = $NombreCliente . " (" . $TA['CodigoC'] . "): Valor Abono: " . number_format($TA['Abono'], 2, '.', ',');
                    if (count($AdoAbono) > 0) {
                        foreach ($AdoAbono as $valor) {
                            $FA['Fecha'] = $valor['Fecha'];
                            $TA['Cta_CxP'] = $valor['Cta_CxP'];
                            $TA['Autorizacion'] = $valor['Autorizacion'];

                            SetAdoAddNew("Trans_Abonos");
                            SetAdoFields("T", G_CANCELADO);
                            SetAdoFields("TP", "FA");
                            SetAdoFields("CodigoC", $TA['CodigoC']);
                            SetAdoFields("Fecha", $TA['Fecha']);
                            SetAdoFields("Comprobante", "Orden No. " . $Orden_Pago);
                            SetAdoFields("Serie", $TA['Serie']);
                            SetAdoFields("Factura", $TA['Factura']);
                            SetAdoFields("Abono", $TA['Abono']);
                            SetAdoFields("Banco", $TA['Banco']);
                            SetAdoFields("Cheque", $TA['Cheque']);
                            SetAdoFields("Cta", $Cta_Del_Banco);
                            SetAdoFields("Cta_CxP", $TA['Cta_CxP']);
                            SetAdoFields("Autorizacion", $TA['Autorizacion']);
                            SetAdoFields("Recibo_No", $TA['Recibo_No']);
                            SetAdoUpdate();

                            // Enviar por correo electrónico el Abono receptado
                            $FA['TC'] = $TA['TP'];
                            $FA['Serie'] = $TA['Serie'];
                            $FA['Factura'] = $TA['Factura'];
                            $FA['ClaveAcceso'] = $FA['Autorizacion'];
                            $FA['Autorizacion'] = $TA['Autorizacion'];
                            $FA['Fecha_C'] = $TA['Fecha'];
                            $FA['Fecha_V'] = $TA['Fecha'];
                            $FA['Hora_FA'] = $TA['Cheque'];
                            $FA['Cliente'] = $NombreCliente;
                            $FA['Fecha_Aut'] = FechaSistema();
                            $SRI_Autorizacion['Autorizacion'] = $TA['Autorizacion'];

                            $FA['Nota'] = "Tipo de Abono" . "\t" . ": " . $TA['Banco'] . "\n" .
                                "Hora" . "\t" . "\t" . ": " . $TA['Cheque'] . "\n" .
                                "Documento" . "\t" . ": " . $TA['Recibo_No'] . "\n" .
                                "Valor Recibdo USD " . number_format($TA['Abono'], 2, '.', ',') . "\n";
                            $this->historico->EnviarMails('','',$FA, $SRI_Autorizacion, "AB");
                        }
                    }
                }
            }
            fclose($NumFile);
            $FA['Serie'] = G_NINGUNO;
            $FA['TC'] = G_NINGUNO;
            $FA['Factura'] = 0;
            Actualizar_Abonos_Facturas_SP($FA);

            $mensaje = "ARCHIVO DE ABONO DEL DIA: " . $FechaTexto . "\n" .
                "SE ACTUALIZARON: " . $Total_Alumnos . " ESTUDIANTES." . "\n" .
                "EL CIERRE DIARIO DE CAJA ES POR " . $_SESSION['INGRESO']['Moneda'] . " " . number_format($TotalIngreso, 2, '.', ',') . "\n" .
                "EL COSTO BANCARIO ES POR " . $_SESSION['INGRESO']['Moneda'] . " " . number_format($Total_Costo_Banco, 2, '.', ',') . "\n" .
                "OBTENIDO DEL ARCHIVO: " . "\n" . $RutaGeneraFile . "\n";
            return array('res' => 'Ok', 'mensaje' => $mensaje);
        }
        $mensaje = 'No hay archivo seleccionado';
        return array('res' => 'Error', 'mensaje' => $mensaje);
    }

    /*function SRI_Enviar_Mails($TFA, $SRI_Autorizacion, $Tipo_Documento)
    {
        $RutaPDF = "";
        $RutaXML = "";
        $Email = "";
        $posPuntoComa = 0;

        $TMail['TipoDeEnvio'] = "CE";
        $TMail['ListaMail'] = 255;
        $TMail['Destinatario'] = $TFA['Cliente'];
        $TMail['MensajeHTML'] = "";
        $TMail['Adjunto'] = "";

        switch ($Tipo_Documento) {
            case "FA":
                //SRI_Generar_PDF_FA($TFA, false);
                //SRI_Generar_XML_Firmado($TFA['ClaveAcceso']);
                break;
            case "NC":
                //SRI_Generar_PDF_NC($TFA, false);
                //SRI_Generar_XML_Firmado($TFA['ClaveAcceso_LC']);
                break;
            case "LC":
                //SRI_Generar_PDF_LC($TFA, false);
                //SRI_Generar_XML_Firmado($TFA['ClaveAcceso_NC']);
                break;
            case "GR":
                //SRI_Generar_PDF_GR($TFA, false);
                //SRI_Generar_XML_Firmado($TFA['ClaveAcceso_GR']);
                break;
            case "RE":
                //SRI_Generar_PDF_RE($TFA, false);
                //SRI_Generar_XML_Firmado($TFA['ClaveAcceso']);
                break;
            case "AB":
                $RutaPDF = "ninguno.pdf";
                $RutaXML = "ninguno.xml";
                break;
        }
        if (strlen($TFA['ClaveAcceso']) >= 13 || strlen($TFA['ClaveAcceso_GR']) >= 13 || strlen($TFA['ClaveAcceso_LC']) >= 13 || strlen($TFA['ClaveAcceso_NC']) >= 13) {
            $TMail['TipoDeEnvio'] = "CE";
        }

        $RutaSysBases = dirname(__DIR__, 3);

        if (strlen($TFA['PDF_ClaveAcceso']) > 1) {
            $RutaPDF = $RutaSysBases . "/TEMP/" . $TFA['PDF_ClaveAcceso'] . ".pdf";
            if (strpos($TFA['PDF_ClaveAcceso'], "_No_") == 0) {
                $RutaXML = $RutaSysBases . "/TEMP/" . $TFA['PDF_ClaveAcceso'] . ".xml";
            }
        }

        if (substr($TFA['ClaveAcceso'], 8, 2) == "07") {
            $TMail['Mensaje'] = "Cliente: " . $TFA['Cliente'] . "\r\n" .
                "Clave de Acceso: \r\n" . $TFA['ClaveAcceso'] . "\r\n" .
                "Hora de Generacion: " . $SRI_Autorizacion['Hora_Autorizacion'] . "\r\n" .
                "Emision: " . $TFA['Fecha'] . "\r\n" .
                "Vencimiento: " . $SRI_Autorizacion['Fecha_Autorizacion'] . "\r\n" .
                "Autorizacion: \r\n" . $SRI_Autorizacion['Autorizacion'] . "\r\n" .
                "Retencion No. " . $TFA['Serie_R'] . "-" . sprintf("%09d", $TFA['Retencion']) . "\r\n" .
                "Factura No. " . $TFA['Serie'] . "-" . sprintf("%09d", $TFA['Factura']) . "\r\n";

            $TMail['Asunto'] = $TFA['Cliente'] . ", Retencion No. " . $TFA['Serie_R'] . "-" . sprintf("%09d", $TFA['Retencion']);
        } elseif (substr($TFA['ClaveAcceso'], 8, 2) == "03") {
            $TMail['Mensaje'] = "Cliente: " . $TFA['Cliente'] . "\r\n" .
                "Clave de Acceso: \r\n" . $TFA['ClaveAcceso_LC'] . "\r\n" .
                "Hora de Generacion: " . $SRI_Autorizacion['Hora_Autorizacion'] . "\r\n" .
                "Emision: " . $TFA['Fecha'] . "\r\n" .
                "Vencimiento: " . $SRI_Autorizacion['Fecha_Autorizacion'] . "\r\n" .
                "Autorizacion: \r\n" . $SRI_Autorizacion['Autorizacion'] . "\r\n" .
                "Liquidacion de Compras No. " . $TFA['Serie_LC'] . "-" . sprintf("%09d", $TFA['Factura']) . "\r\n";

            $TMail['Asunto'] = $TFA['Cliente'] . ", Liquidacion de Compras No. " . $TFA['Serie_LC'] . "-" . sprintf("%09d", $TFA['Factura']);
        } else {
            $TMail['Mensaje'] = "Cliente: " . $TFA['Cliente'] . "\r\n" .
                "Clave de Acceso: \r\n" . $TFA['ClaveAcceso'] . "\r\n" .
                "Emision: " . $TFA['Fecha'] . "\r\n" .
                "Vencimiento: " . $TFA['Fecha_V'] . "\r\n" .
                "Fecha Autorizado: " . $TFA['Fecha_Aut'] . "\r\n" .
                "Autorizacion: \r\n" . $TFA['Autorizacion'] . "\r\n" .
                "Factura No. " . $TFA['Serie'] . "-" . sprintf("%09d", $TFA['Factura']) . "\r\n";

            if ($Tipo_Documento === "AB") {
                $TMail['Mensaje'] .= "\r\nSU PAGO FUE REGISTRADO CON EXITO\r\nEL " . FechaStrg($TFA['Fecha_C']) . "\r\n" . $TFA['Nota'] . "\r\n";
            } else {
                $TMail['Mensaje'] .= "Hora de Generacion: " . $TFA['Hora_FA'] . "\r\n";
            }
            $TMail['Asunto'] = $TFA['Cliente'] . ", Factura No. " . $TFA['Serie'] . "-" . sprintf("%09d", $TFA['Factura']);
        }
        // Datos del destinatario de mails
        $TMail['para'] = "";
        Insertar_Mail($TMail['para'], $TFA['EmailC']);
        Insertar_Mail($TMail['para'], $TFA['EmailC2']);
        Insertar_Mail($TMail['para'], $TFA['EmailR']);

        $TMail['Mensaje'] .= str_repeat("-", 45) . "\r\n" .
            "Email(s) Destinatario(s):\r\n" .
            str_replace(";", "; ", $TMail['para']) . "\r\n" .
            str_repeat("_", 45) . "\r\n" .
            $_SESSION['INGRESO']['Nombre_Comercial'] . "\r\n" .
            $_SESSION['INGRESO']['Razon_Social'] . "\r\n" .
            $_SESSION['INGRESO']['Telefono1'] . "/" . $_SESSION['INGRESO']['Telefono1'] . "\r\n" .
            "Dir. " . $_SESSION['INGRESO']['Direccion'] . "\r\n" .
            strtoupper($_SESSION['INGRESO']['Ciudad']) . "-" . strtoupper($_SESSION['INGRESO']['NombrePais']) . "\r\n";

        
        // Enviamos lista de mails
        if ($_SESSION['INGRESO']['Email_CE_Copia']) {
            $TMail['Credito_No'] = "X" . sprintf("%09d", $TFA['Factura']);
            Insertar_Mail($TMail['para'], $_SESSION['INGRESO']['Email_Procesos']);
        }

        if (file_exists($RutaPDF)) {
            $TMail['Adjunto'] = $RutaPDF . "; ";
        }

        if (file_exists($RutaXML)) {
            $TMail['Adjunto'] .= $RutaXML;
        }

        // FEnviarCorreos 
        $rps = $this->email->FEnviarCorreos($TMail, null, "");
        if ($rps) {
            $rps = $rps[0];
            if (isset($rps['error'])) {
                $res = $rps['mensaje'];
            } else if (isset($rps['rps'])) {
                if ($rps['rps'] == 1) {
                    $res = "success";
                } else {
                    $res = "Ocurrio un problema en el envio del correo";
                }
            }
        } else {
            $res = "Ocurrio un error al recibir datos de la funcion";
        }

        // Eliminamos los archivos después de enviar el correo
        if (file_exists($RutaPDF)) {
            unlink($RutaPDF);
        }

        if (file_exists($RutaXML)) {
            unlink($RutaXML);
        }

        $TMail['Volver_Envial'] = false;
        return ['res' => $rps];
    }*/

    function Visualizar_Archivo($parametros)
    {
        $NombreArchivo = $parametros['NombreArchivo'];
        $RutaGeneraFile = $NombreArchivo;
        $maxCar = 0;
        $txtFile = '';
        $NumFile = fopen($RutaGeneraFile, "r");

        if ($NumFile) {
            while (!feof($NumFile)) {
                $codField = fgets($NumFile);
                if (strlen($codField) > $maxCar) {
                    $maxCar = strlen($codField);
                }
                $txtFile .= $codField . "\n";
            }

            fclose($NumFile);

            $j = 1;
            $k = 0;
            $cadena = "";
            $cadena1 = "";

            for ($i = 1; $i <= $maxCar; $i++) {
                $cadena .= (string) $j;
                $j++;

                if ($j > 9) {
                    $cadena .= "0";
                    $j = 1;
                    $k++;
                    if ($k <= 10) {
                        $cadena1 .= str_repeat(" ", 9) . (string) $k;
                    } else {
                        $cadena1 .= str_repeat(" ", 8) . (string) $k;
                    }
                }
            }
            $cadena .= "\n";
            $cadena1 .= "\n";
            $txtFile = $cadena1 . $cadena . $txtFile;
            return array('res' => 'Ok', 'contenido' => $txtFile);
        }
    }

    function Generar_Pichincha($parametros)
    {
        //print_r($parametros['AdoPendiente']);
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $AdoPendiente = $parametros['AdoPendiente'];
        $Cta_Bancaria = $parametros['Cta_Bancaria'];
        $Tipo_Carga = $parametros['Tipo_Carga'];
        $CheqMatricula = $parametros['CheqMatricula'];

        $AuxNumEmp = "";
        $DiaV = 0;
        $MesV = 0;
        $AñoV = 0;
        $CamposFile = array();

        $Mifecha = BuscarFecha($MBFechaI);
        $MiMes = sprintf("%02d", date("m", strtotime($MBFechaI)));
        $FechaFin = BuscarFecha(date("Y-m-t", strtotime($MBFechaI)));
        $TextoImprimio = "";
        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $Cta_Cobrar = G_NINGUNO;
        $FechaSistema = date('Y-m-d H:i:s');
        $FechaTexto = $FechaSistema;

        // Comenzamos a generar el archivo: SCRECXX.TXT
        $EsComa = true;
        $Estab = false;
        $Contador = 0;
        $Factura_No = 0;
        $SumaBancos = 0;

        $directorioBase = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/";
        // Verificar si el directorio base existe, si no, crearlo
        if (!is_dir($directorioBase)) {
            mkdir($directorioBase, 0777, true);
        }
        $NumFileFacturas = fopen($directorioBase . "SCREC" . date("m", strtotime($MBFechaI)) . ".TXT", "w");

        if (count($AdoPendiente) > 0) {
            foreach ($AdoPendiente as $valor) {
                $Contador = $Contador + 1;
                $CodigoCli = $valor["CodigoC"];
                $NombreCliente = Sin_Signos_Especiales($valor["Cliente"]);
                $Factura_No = $valor["Factura"];
                $SerieFactura = $valor["Serie"];
                $Total = $valor["Saldo_MN"];
                $Saldo = $valor["Saldo_MN"] * 100;
                $CodigoP = $valor["CI_RUC"];
                $CodigoC = strval(intval($valor['CI_RUC']));
                $CodigoC .= str_pad('', max(0, 4 - strlen($CodigoC)), ' ');
                $DireccionCli = Sin_Signos_Especiales($valor["Direccion"]);
                $Codigo3 = SinEspaciosDer2($DireccionCli);
                $DireccionCli = trim(substr($DireccionCli, 0, strlen($DireccionCli) - strlen($Codigo3)));
                $Codigo3 = trim(SinEspaciosDer2($DireccionCli));
                $Codigo1 = $valor["Anio_Mes"];

                if (strlen($valor["Actividad"]) < 3) {
                    if ($Tipo_Carga == 1) {
                        // Tipo Gualaceo
                        fwrite($NumFileFacturas, "CO\t");
                        fwrite($NumFileFacturas, $CodigoC . "\t");
                        fwrite($NumFileFacturas, "USD\t");
                        fwrite($NumFileFacturas, $Saldo . "\t");
                        fwrite($NumFileFacturas, "REC\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                        if ($CheqMatricula == 1) {
                            $Codigo4 = "MATRICULAS DE " . substr(MesesLetras(date("n", strtotime($MBFechaI))), 0, 3) . "-" . date("Y", strtotime($MBFechaI));
                        } else {
                            $Codigo4 = "PENSION ACUMULADA DE " . substr(MesesLetras(date("n", strtotime($MBFechaI))), 0, 3) . "-" . date("Y", strtotime($MBFechaI));
                        }
                        fwrite($NumFileFacturas, strtoupper($Codigo4) . "\t");
                        fwrite($NumFileFacturas, "N\t");
                        fwrite($NumFileFacturas, sprintf("%010d", $CodigoC) . "\t");
                        fwrite($NumFileFacturas, substr($NombreCliente, 0, 40) . "\t");
                    } else {
                        // Tipo General
                        fwrite($NumFileFacturas, "CO\t");
                        fwrite($NumFileFacturas, $Cta_Bancaria . "\t");
                        fwrite($NumFileFacturas, $Contador . "\t");
                        fwrite($NumFileFacturas, sprintf("%010d", $Factura_No) . "\t");
                        fwrite($NumFileFacturas, $CodigoP . "\t");
                        fwrite($NumFileFacturas, "USD\t");
                        fwrite($NumFileFacturas, sprintf("%013d", $Saldo) . "\t");
                        fwrite($NumFileFacturas, "REC\t");
                        fwrite($NumFileFacturas, "10\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "0\t");
                        fwrite($NumFileFacturas, "R\t");
                        fwrite($NumFileFacturas, $_SESSION['INGRESO']['RUC'] . "\t");
                        fwrite($NumFileFacturas, substr($Codigo1, 5, 2) . " " . substr($NombreCliente, 0, 37) . "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, substr($Codigo1, 5, 2) . "\t");

                        if ($CheqMatricula == 1) {
                            $Codigo4 = $valor["Grupo"] . " Matricula ";
                        } else {
                            $Codigo4 = $valor["Grupo"] . " Pension ";
                        }
                        $Codigo4 = $Codigo4 . str_repeat(" ", 26 - strlen($Codigo4)) . "$SerieFactura $Codigo1";
                        fwrite($NumFileFacturas, $Codigo4 . "\t");
                        fwrite($NumFileFacturas, sprintf("%013d", $Saldo) . "\t");
                        $SumaBancos = $SumaBancos + $valor["Saldo_MN"];
                    }
                }
                fwrite($NumFileFacturas, "\n");
            }
        }
        fclose($NumFileFacturas);
        //SCCOB03.TXT
        //Comenzamos a generar el archivo: SCCOB.TXT
        $mes = date('m', strtotime($MBFechaI));
        $anio = intval(substr(date('Y', strtotime($MBFechaI)), 1, 3));
        $dia = "15";
        $NumFileFacturas = fopen($directorioBase . "SCCOB" . $mes . ".TXT", 'w');
        if (count($AdoPendiente) > 0) {
            foreach ($AdoPendiente as $valor) {
                $Contador = $Contador + 1;
                $CodigoCli = $valor["CodigoC"];
                $NombreCliente = Sin_Signos_Especiales($valor["Cliente"]);
                $Factura_No = $Factura_No + 1;
                $Total = $valor["Saldo_MN"];
                $Saldo = $valor["Saldo_MN"] * 100;
                $CodigoP = $valor["CI_RUC"];
                $DireccionCli = Sin_Signos_Especiales($valor["Direccion"]);
                $Codigo3 = SinEspaciosDer2($DireccionCli);
                $DireccionCli = trim(substr($DireccionCli, 0, strlen($DireccionCli) - strlen($Codigo3)));
                $Codigo3 = trim(SinEspaciosDer2($DireccionCli));
                //$Codigo1 = sprintf("%02d", intval(substr($GrupoNo, 0, 1))); //FALTA VARIABLE

                if (strlen($valor["Actividad"]) >= 3) {
                    fwrite($NumFileFacturas, "CO\t");
                    fwrite($NumFileFacturas, $Cta_Bancaria . "\t");
                    fwrite($NumFileFacturas, $Contador . "\t");
                    fwrite($NumFileFacturas, sprintf("%010d", $Factura_No) . "\t");
                    fwrite($NumFileFacturas, $CodigoP . "\t");
                    fwrite($NumFileFacturas, "USD\t");
                    fwrite($NumFileFacturas, sprintf("%013d", $Saldo) . "\t");
                    fwrite($NumFileFacturas, "CTA\t");
                    fwrite($NumFileFacturas, "10\t");
                    $NumStrg = SinEspaciosIzq($valor["Actividad"]);
                    if (strlen($NumStrg) == 3) {
                        fwrite($NumFileFacturas, SinEspaciosIzq($valor["Actividad"]) . "\t");
                        fwrite($NumFileFacturas, SinEspaciosDer($valor["Actividad"]) . "\t");
                    } else {
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                    }
                    fwrite($NumFileFacturas, "R\t");
                    fwrite($NumFileFacturas, "RUC\t");
                    fwrite($NumFileFacturas, substr(date('m', strtotime($MBFechaI)) . " " . $NombreCliente, 0, 40) . "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, $mes . "\t");
                    fwrite($NumFileFacturas, "Pensión Acumulada\t");
                    fwrite($NumFileFacturas, sprintf("%013d", $Saldo) . "\t");
                }
                fwrite($NumFileFacturas, "\n");
            }
        }
        fclose($NumFileFacturas);
        $mensaje =
            strtoupper("SCREC" . $mes . ".TXT") . " " .
            strtoupper("SCCOB" . $mes . ".TXT") . " " .
            "Valor Total a Recaudar USD " . number_format($SumaBancos, 2, '.', ',');

        return array(
            'res' => 'Ok',
            'mensaje' => $mensaje,
            'textoBanco' => 'PICHINCHA',
            'Nombre1' => "SCREC" . $mes . ".TXT",
            'Nombre2' => "SCCOB" . $mes . ".TXT"
        );
    }

    function Generar_Internacional($parametros)
    {
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $AdoDetalle = $parametros['AdoDetalle'];
        $AdoFactura = $parametros['AdoFactura'];
        $Cta_Bancaria = $parametros['Cta_Bancaria'];
        //$Tipo_Carga = $parametros['Tipo_Carga'];
        //$CheqMatricula = $parametros['CheqMatricula'];
        $Separador = "\t";
        if ($MBFechaI != $MBFechaF) {
            $Traza = str_replace("/", "-", $MBFechaI) . "_al_" . str_replace("/", "-", $MBFechaF);
        } else {
            $Traza = str_replace("/", "-", $MBFechaI);
        }

        $directorioBase = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/";
        // Verificar si el directorio base existe, si no, crearlo
        if (!is_dir($directorioBase)) {
            mkdir($directorioBase, 0777, true);
        }
        $NumFileFacturas = fopen($directorioBase . "CxC_MES_" . $Traza . ".TXT", "w");
        $Traza = "";

        $FechaTexto = FechaSistema();
        $Mifecha = BuscarFecha($MBFechaI);
        $TextoImprimio = "";
        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $Cta_Cobrar = "Ninguno";
        $Contador = 1;
        $Total = 0;
        //Comenzamos a generar el archivo: COALU.TXT
        if (count($AdoDetalle) > 0) {
            foreach ($AdoDetalle as $valor) {
                $CodigoCli = $valor["CodigoC"];
                $TRep = Leer_Datos_Clientes($CodigoCli);
                $NombreCliente = trim(substr($valor["Cliente"], 0, 40));
                $CodigoB = sprintf("%08d", $valor["CI_RUC"]);
                $Abono = $valor["Total"] - $valor["Total_Desc"] - $valor["Total_Desc2"];

                if ($Abono <= 0) {
                    $Abono = 0;
                }

                $DireccionCli = $valor["Direccion"];
                $GrupoNo = $valor["Grupo"];
                $Detalle = $GrupoNo . "-" . $valor["Producto"] . "-" . $valor["Mes"];

                // Empieza la trama
                $Traza = "CO" . $Separador .
                    trim(substr($Cta_Bancaria, 0, 20)) . $Separador .
                    $Contador . $Separador .
                    $Contador . $Separador .
                    $CodigoB . $Separador .
                    "USD" . $Separador .
                    strval($Abono * 100) . $Separador .
                    "REC" . $Separador .
                    "32" . $Separador .
                    $TRep['TD_R'] . $Separador .
                    $TRep['CI_RUC_R'] . $Separador .
                    $NombreCliente . $Separador .
                    $DireccionCli . $Separador . $Separador . $Separador . $Separador . $Separador .
                    $Detalle . str_repeat("\t", 13);

                if ($Abono > 0) {
                    fwrite($NumFileFacturas, $Traza);
                    $Contador = $Contador + 1;
                }
                fwrite($NumFileFacturas, "\n");
            }
        }
        fclose($NumFileFacturas);
        $Separador = "\t";
        if ($MBFechaI != $MBFechaF) {
            $Traza = str_replace("/", "-", $MBFechaI) . "_al_" . str_replace("/", "-", $MBFechaF);
        } else {
            $Traza = str_replace("/", "-", $MBFechaI);
        }

        $directorioBase = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/";
        // Verificar si el directorio base existe, si no, crearlo
        if (!is_dir($directorioBase)) {
            mkdir($directorioBase, 0777, true);
        }

        $NumFileFacturas = fopen($directorioBase . "CxC_Pendiente_" . $Traza . ".TXT", "w");
        $Traza = "";
        $FechaTexto = FechaSistema();
        $Mifecha = BuscarFecha($MBFechaI);
        $TextoImprimio = "";
        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $Cta_Cobrar = "Ninguno";
        $Contador = 1;
        $Total = 0;

        // Comenzamos a generar el archivo: COALU.TXT
        if (count($AdoFactura) > 0) {
            foreach ($AdoFactura as $valor) {

                $CodigoCli = $valor["CodigoC"];
                $TRep = Leer_Datos_Clientes($CodigoCli);
                $NombreCliente = trim(substr($valor["Cliente"], 0, 40));
                $CodigoB = sprintf("%08d", $valor["CI_RUC"]);
                $Abono = $valor["Saldo_Pend"];

                if ($Abono <= 0) {
                    $Abono = 0;
                }

                $DireccionCli = $valor["Direccion"];
                $GrupoNo = $valor["Grupo"];
                $Detalle = $GrupoNo . "-Saldo Pendiente-00";

                // Empieza la trama
                $Traza = "CO" . $Separador .
                    trim(substr($Cta_Bancaria, 0, 20)) . $Separador .
                    $Contador . $Separador .
                    $Contador . $Separador .
                    $CodigoB . $Separador .
                    "USD" . $Separador .
                    strval($Abono * 100) . $Separador .
                    "REC" . $Separador .
                    "32" . $Separador .
                    $Separador . $Separador . $Separador . $Separador .
                    $TRep['TD_R'] . $Separador .
                    $TRep['CI_RUC_R'] . $Separador .
                    $NombreCliente . $Separador . $Separador . $Separador . $Separador . $Separador .
                    $Detalle . str_repeat($Separador, 13);

                if ($Abono > 0) {
                    fwrite($NumFileFacturas, $Traza);
                    $Contador = $Contador + 1;
                }
                fwrite($NumFileFacturas, "\n");
            }
        }
        // Finalizamos los archivos
        fclose($NumFileFacturas);
        $Separador = "\t";
        if ($MBFechaI != $MBFechaF) {
            $Traza = str_replace("/", "-", $MBFechaI) . "_al_" . str_replace("/", "-", $MBFechaF);
        } else {
            $Traza = str_replace("/", "-", $MBFechaI);
        }
        $mensaje =
            strtoupper("CxC_MES_" . $Traza . ".TXT") . " " .
            strtoupper("CxC_Pendiente_" . $Traza . ".TXT");

        return array(
            'res' => 'Ok',
            'mensaje' => $mensaje,
            'textoBanco' => 'INTERNACIONAL',
            'Nombre1' => "CxC_MES_" . $Traza . ".TXT",
            'Nombre2' => "CxC_Pendiente_" . $Traza . ".TXT"
        );
    }

    function Generar_Pacifico($parametros)
    {
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $AdoPendiente = $parametros['AdoPendiente'];
        $Costo_Banco = $parametros['Costo_Banco'];

        $directorioBase = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/";
        if (!is_dir($directorioBase)) {
            mkdir($directorioBase, 0777, true);
        }
        $NumFileFacturas = fopen($directorioBase . "BIZBANK CODIGO DEL" . str_replace("/", "-", $MBFechaI) . "_AL_" . str_replace("/", "-", $MBFechaF) . ".TXT", "w");
        $TipoDoc = "0";
        $Contador = 0;
        $FechaTexto = BuscarFecha($MBFechaI);
        $TxtFile = "";

        $Total = 0;
        $TotalIngreso = 0;
        $Total_Factura = 0;
        $IE = 0;
        $JE = 0;
        $KE = 0;
        $Grupo_No = 0;

        if (count($AdoPendiente) > 0) {
            $TxtFile = "TOTAL NOMINA DE RECAUDACION:\n";
            $Codigo3 = trim(substr($_SESSION['INGRESO']['noempr'], 0, 30));
            $Total = 0;
            $TotalIngreso = 0;
            $Total_Factura = 0;
            $IE = 1;
            $JE = 1;
            $KE = 1;
            $Grupo_No = $AdoPendiente[0]["Grupo"];
            $Codigo = $AdoPendiente[0]["CodigoC"];
            foreach ($AdoPendiente as $valor) {
                $Contador++;
                $CodigoCli = $valor["CI_RUC"];
                $NombreCliente = Sin_Signos_Especiales(trim(substr($valor["Cliente"], 0, 30)));
                $Codigo1 = trim(substr($valor["Direccion"], 0, 30));
                $FechaTexto = " " . substr(MesesLetras(date('m', strtotime($MBFechaI))), 0, 3) . " " . date('Y', strtotime($MBFechaI));
                $Codigo2 = strtoupper($valor["Grupo"] . " " . str_replace("/", " ", $MBFechaF));
                $Total_Factura = 0; //$valor["Saldo_Pend"];

                if ($Costo_Banco > 0) {
                    $Total_Factura += $Costo_Banco;
                }

                $Total += $Total_Factura;
                $TotalIngreso += $Total_Factura;
                $I = (int) $Total_Factura;
                $J = ($Total_Factura - $I) * 100;

                if (strlen($valor['Actividad']) == 1) {
                    // Empieza la trama por Alumno
                    fwrite($NumFileFacturas, "1");
                    fwrite($NumFileFacturas, "OCP");
                    fwrite($NumFileFacturas, "SC");
                    fwrite($NumFileFacturas, "  ");
                    fwrite($NumFileFacturas, str_repeat(" ", 8));
                    fwrite($NumFileFacturas, sprintf("%013d%02d", $I, $J));
                    fwrite($NumFileFacturas, $CodigoCli . str_repeat(" ", 15 - strlen($CodigoCli)));
                    fwrite($NumFileFacturas, $Codigo2 . str_repeat(" ", 20 - strlen($Codigo2)));
                    fwrite($NumFileFacturas, "RE");
                    fwrite($NumFileFacturas, "USD");
                    fwrite($NumFileFacturas, $NombreCliente . str_repeat(" ", 30 - strlen($NombreCliente)));
                    fwrite($NumFileFacturas, str_repeat(" ", 18));
                    fwrite($NumFileFacturas, "0");
                    fwrite($NumFileFacturas, sprintf("%06d", $Contador));
                }
                fwrite($NumFileFacturas, "\n");
            }
        }
        fclose($NumFileFacturas);
        $directorioBase = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/";
        if (!is_dir($directorioBase)) {
            mkdir($directorioBase, 0777, true);
        }
        $NumFileFacturas = fopen($directorioBase . "BIZBANK DEBITO DEL" . str_replace("/", "-", $MBFechaI) . "_AL_" . str_replace("/", "-", $MBFechaF) . ".TXT", "w");
        $TipoDoc = "0";
        $FechaTexto = BuscarFecha($MBFechaI);

        if (count($AdoPendiente) > 0) {
            $TxtFile = "TOTAL NOMINA DE RECAUDACION:\n";
            $Codigo3 = trim(substr($_SESSION['INGRESO']['noempr'], 0, 30));
            $Total = 0;
            $TotalIngreso = 0;
            $Total_Factura = 0;
            $IE = 1;
            $JE = 1;
            $KE = 1;
            $Grupo_No = $AdoPendiente[0]["Grupo"];
            $Codigo = $AdoPendiente[0]["CodigoC"];
            foreach ($AdoPendiente as $valor) {
                if ($Grupo_No != $valor['Grupo']) {
                    $Codigo4 = number_format($Total, 2, '.', ',');
                    $Codigo4 = str_repeat(" ", 13 - strlen($Codigo4)) . number_format($Total, 2, '.', ',');
                    $TxtFile .= "Grupo: " . $Grupo_No . "\t" . "Resumen de Registros por Grupo: " . $JE . "\t" . "Total a Recaudar USD" . "\t" . $Codigo4 . "\n";
                    $JE = 0;
                    $Total = 0;
                    $IE++;
                    $Grupo_No = $valor['Grupo'];
                    $Codigo = $valor['CodigoC'];
                }
                if ($Codigo != $valor['CodigoC']) {
                    $JE++;
                    $KE++;
                    $Codigo = $valor['CodigoC'];
                }
                $Contador++;
                $CodigoCli = $valor['CI_RUC'];
                $NombreCliente = Sin_Signos_Especiales(trim(substr($valor['Cliente'], 0, 30)));
                $Codigo1 = trim(substr($valor['Direccion'], 0, 30));
                $FechaTexto = " " . substr(MesesLetras(date('n', strtotime($MBFechaI))), 0, 3) . " " . date('Y', strtotime($MBFechaI));
                $Codigo2 = strtoupper($valor['Grupo'] . " " . str_replace("/", " ", $MBFechaF));
                $Total_Factura = 0; //$valor['Saldo_Pend'];
                if ($Costo_Banco > 0) {
                    $Total_Factura = $Total_Factura + $Costo_Banco;
                }
                $Total += $Total_Factura;
                $TotalIngreso += $Total_Factura;
                $I = (int) $Total_Factura;
                $J = ($Total_Factura - $I) * 100;

                if (strlen($valor['Actividad']) > 1 && strtoupper(substr($valor['Actividad'], 0, 5)) !== "TRANS") {
                    // Empieza la trama por Alumno
                    fwrite($NumFileFacturas, "1");
                    fwrite($NumFileFacturas, "OCP");
                    fwrite($NumFileFacturas, "SC");
                    fwrite($NumFileFacturas, substr($valor['Actividad'], 0, 10));
                    fwrite($NumFileFacturas, sprintf("%013d%02d", $I, $J));
                    fwrite($NumFileFacturas, $CodigoCli . str_repeat(" ", 15 - strlen($CodigoCli)));
                    fwrite($NumFileFacturas, $Codigo2 . str_repeat(" ", 20 - strlen($Codigo2)));
                    fwrite($NumFileFacturas, "CU");
                    fwrite($NumFileFacturas, "USD");
                    fwrite($NumFileFacturas, $NombreCliente . str_repeat(" ", 30 - strlen($NombreCliente)));
                    fwrite($NumFileFacturas, str_repeat(" ", 18));
                    fwrite($NumFileFacturas, "0");
                    fwrite($NumFileFacturas, sprintf("%06d", $Contador));
                }
                fwrite($NumFileFacturas, "\n");
            }
        }
        fclose($NumFileFacturas);

        $Codigo4 = number_format($Total, 2, '.', ',');
        $Codigo4 = str_repeat(" ", 13 - strlen($Codigo4)) . $Codigo4;
        $TxtFile .= "Grupo: " . $Grupo_No . "\t" . "Resumen de Registros por Grupo: " . $JE . "\t" . "Total a Recaudar USD" . "\t" . $Codigo4 . "\n";

        $Codigo4 = number_format($TotalIngreso, 2, '.', ',');
        $Codigo4 = str_repeat(" ", 13 - strlen($Codigo4)) . $Codigo4;
        $TxtFile .= str_repeat("-", 90) . "\n" .
            "Total Grupos: " . $IE . "\t" . "Total Alumnos: " . $KE . "\t\t" . "Total a Recaudar USD" . "\t" . $Codigo4 . "\n";


        $mensaje =
            strtoupper("BIZBANK CODIGO DEL" . str_replace("/", "-", $MBFechaI) . "_AL_" . str_replace("/", "-", $MBFechaF) . ".TXT") . " " .
            strtoupper("BIZBANK DEBITO DEL" . str_replace("/", "-", $MBFechaI) . "_AL_" . str_replace("/", "-", $MBFechaF) . ".TXT");

        return array(
            'res' => 'Ok',
            'mensaje' => $mensaje,
            'textoBanco' => 'PACIFICO',
            'Nombre1' => "BIZBANK CODIGO DEL" . str_replace("/", "-", $MBFechaI) . "_AL_" . str_replace("/", "-", $MBFechaF) . ".TXT",
            'Nombre2' => "BIZBANK DEBITO DEL" . str_replace("/", "-", $MBFechaI) . "_AL_" . str_replace("/", "-", $MBFechaF) . ".TXT"
        );
    }

    function Generar_BGR_EC($parametros)
    {
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $AdoAux = $parametros['AdoAux'];
        $AdoPendiente = $parametros['AdoPendiente'];
        $CheqPend = $parametros['CheqPend'];
        $CheqMatricula = $parametros['CheqMatricula'];

        $MiFecha = BuscarFecha($MBFechaI);
        $MiMes = date('m', strtotime($MBFechaI));
        $FechaFin = BuscarFecha(date("Y-m-t", strtotime($MBFechaI)));

        $TextoImprimio = "";
        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $Cta_Cobrar = "Ninguno";
        $FechaTexto = FechaSistema();

        // Comenzamos a generar el archivo: BGR_MES_XX
        $EsComa = true;
        $Estab = false;
        $Contador = 0;
        $Factura_No = 0;

        $directorioBase = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/";
        // Verificar si el directorio base existe, si no, crearlo
        if (!is_dir($directorioBase)) {
            mkdir($directorioBase, 0777, true);
        }
        $NumFileFacturas = fopen($directorioBase . "BGR_MES_" . sprintf('%02d', $MiMes) . ".TXT", "w");

        if (count($AdoAux) > 0) {
            foreach ($AdoAux as $valor) {
                $Contador++;
                $CodigoCli = $valor['CodigoC'];
                $NombreCliente = Sin_Signos_Especiales($valor['Cliente']);
                $Factura_No++;
                $SaldoPendiente = 0;

                if (count($AdoPendiente) > 0) {
                    foreach ($AdoPendiente as $pvalor) {
                        if ($pvalor['CodigoC'] == $CodigoCli) {
                            $SaldoPendiente = 0; //$pvalor['Saldo_Pend'];
                            break;
                        }
                    }
                }
                if ($CheqPend == 1) {
                    $Total = $valor['Saldo_MN'];
                } else {
                    $Total = $SaldoPendiente;
                }
                $Saldo = $Total * 100;
                $CodigoP = $valor['CI_RUC'];
                $CodigoC = $valor['CI_RUC'];
                $DireccionCli = Sin_Signos_Especiales($valor['Direccion']);
                $Codigo3 = SinEspaciosDer2($DireccionCli);
                $DireccionCli = trim(substr($DireccionCli, 0, strlen($DireccionCli) - strlen($Codigo3)));
                $Codigo3 = trim($DireccionCli);
                $Codigo1 = sprintf('%02d', substr($valor['GrupoNo'], 0, 1));

                if ($Saldo > 0) {
                    // Tipo trama
                    fwrite($NumFileFacturas, "CO" . "\t");
                    fwrite($NumFileFacturas, $CodigoC . "\t");
                    fwrite($NumFileFacturas, "USD" . "\t");
                    fwrite($NumFileFacturas, $Saldo . "\t");
                    fwrite($NumFileFacturas, "REC" . "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "\t");

                    if ($CheqMatricula == 1) {
                        $Codigo4 = "MATRICULAS DE " . substr(MesesLetras(date('n', strtotime($MBFechaI))), 0, 3) . "-" . date('Y', strtotime($MBFechaI));
                    } else {
                        $Codigo4 = "PENSION DE " . substr(MesesLetras(date('n', strtotime($MBFechaI))), 0, 3) . "-" . date('Y', strtotime($MBFechaI));
                    }

                    fwrite($NumFileFacturas, strtoupper(trim(substr($Codigo4, 0, 40))) . "\t");
                    fwrite($NumFileFacturas, "N" . "\t");
                    fwrite($NumFileFacturas, sprintf('%010d', $CodigoC) . "\t");
                    fwrite($NumFileFacturas, substr($NombreCliente, 0, 40) . "\t");
                }
                fwrite($NumFileFacturas, "\n");
            }
        }
        fclose($NumFileFacturas);

        $mensaje = "BGR_MES_" . sprintf('%02d', $MiMes) . ".TXT";

        return array(
            'res' => 'Ok',
            'mensaje' => $mensaje,
            'textoBanco' => 'BGR_EC',
            'Nombre1' => $mensaje
        );
    }

    function Generar_Bolivariano($parametros)
    {
        $CheqMatricula = $parametros['CheqMatricula'];
        $CheqPend = $parametros['CheqPend'];
        $MBFechaF = $parametros['MBFechaF'];
        $AdoAux = $parametros['AdoAux'];
        $AdoPendiente = $parametros['AdoPendiente'];
        $CodigoDelBanco = $parametros['DCBanco'];

        //$MBFechaI = ;
        $MiFecha = BuscarFecha($MBFechaF);

        $directorioBase = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/";
        // Verificar si el directorio base existe, si no, crearlo
        if (!is_dir($directorioBase)) {
            mkdir($directorioBase, 0777, true);
        }

        $RutaGeneraFile = $directorioBase . "ALUMNOS" . $CodigoDelBanco . ".TXT";
        $NumFileFacturas = fopen($RutaGeneraFile, "w");
        $TipoDoc = "0";

        if ($CheqMatricula == 1) {
            $TipoDoc = "1";
        }

        $Contador = 0;
        $FechaTexto = BuscarFecha(date("Y-m-t", strtotime($MBFechaF)));
        if (count($AdoAux) > 0) {
            fwrite($NumFileFacturas, "999");
            fwrite($NumFileFacturas, $CodigoDelBanco);
            fwrite($NumFileFacturas, $TipoDoc);
            fwrite($NumFileFacturas, str_repeat(" ", 11));
            fwrite($NumFileFacturas, $MiFecha);
            foreach ($AdoAux as $valor) {
                $SaldoPendiente = 0;
                $Total_Factura = 0;
                $Monto_Total = 0;
                $Total = 0;
                $CodigoCli = $valor['CI_RUC'];
                $Codigo = "0";

                for ($i = 0; $i < strlen($valor['CI_RUC']); $i++) {
                    if (is_numeric(substr($valor['CI_RUC'], $i, 1))) {
                        $Codigo .= substr($valor['CI_RUC'], $i, 1);
                    }
                }
                $Codigo = trim(strval(intval($Codigo)));
                $Codigo = $Codigo . str_repeat(" ", 15 - strlen($Codigo));
                $NombreCliente = $this->SetearBlancos(substr($valor['Cliente'], 0, 30), 30, 0, false);
                $Codigo1 = trim(substr(SinEspaciosIzq($valor['Direccion']), 0, 15));
                $Codigo3 = trim(substr(SinEspaciosDer2($valor['Direccion']), 0, 3));
                $Codigo2 = trim(substr($valor['Direccion'], strlen($Codigo1), strlen($valor['Direccion'])));
                $Codigo4 = substr($valor['Casilla'], 0, 10);
                $Saldo_ME = 0;
                $Total_Desc = 0;
                $SaldoPendiente = 0;

                if (count($AdoPendiente) > 0) {
                    foreach ($AdoPendiente as $pvalor) {
                        if (strpos($pvalor['CI_RUC'], $CodigoCli) !== false) {
                            $SaldoPendiente = 0; //$pvalor['Saldo_Pend'];
                            break;
                        }
                    }
                }
                if ($CheqPend == 1) {
                    $SaldoPendiente = $valor['Total_MN'];
                }
                $Total_Factura = $valor['Total_MN'];
                $Monto_Total = $Total_Factura;
                $Total = $SaldoPendiente;
                if ($Codigo1 == "") {
                    $Codigo1 = G_NINGUNO;
                }
                if ($Codigo2 == "") {
                    $Codigo2 = G_NINGUNO;
                }
                if ($Codigo3 == "") {
                    $Codigo3 = G_NINGUNO;
                }

                $Codigo2 = trim(substr($Codigo2, 0, strlen($Codigo2) - strlen(rtrim($Codigo2))));
                $Codigo1 = $this->SetearBlancos($Codigo1, 15, 0, false);
                $Codigo2 = $this->SetearBlancos($Codigo2, 15, 0, false);
                $Codigo3 = $this->SetearBlancos($Codigo3, 3, 0, false);
                $Codigo4 = $this->SetearBlancos($Codigo4, 10, 0, false);

                if (trim($Codigo4) == G_NINGUNO) {
                    $Codigo4 = str_repeat(" ", 10);
                }
                if ($Total < 0) {
                    $Total = 0;
                }

                fwrite($NumFileFacturas, $CodigoDelBanco . "\t");
                fwrite($NumFileFacturas, $Codigo . "\t");
                fwrite($NumFileFacturas, $MiFecha . "\t");
                fwrite($NumFileFacturas, $TipoDoc . " \t");
                fwrite($NumFileFacturas, sprintf("%012.2f", $Total) . "\t");
                fwrite($NumFileFacturas, $FechaTexto . "\t");
                fwrite($NumFileFacturas, "01/01/1900" . "\t");
                fwrite($NumFileFacturas, "N" . "\t");
                fwrite($NumFileFacturas, Sin_Signos_Especiales($NombreCliente) . "\t");
                fwrite($NumFileFacturas, $Codigo2 . "\t");
                fwrite($NumFileFacturas, $Codigo3 . "\t");
                fwrite($NumFileFacturas, $Codigo1 . "\t");
                fwrite($NumFileFacturas, sprintf("%012.2f", $Monto_Total) . "\t");
                fwrite($NumFileFacturas, $Codigo4 . "\t");
                fwrite($NumFileFacturas, "1" . "\t");
                fwrite($NumFileFacturas, sprintf("%012.2f", $Total) . "\t");
                fwrite($NumFileFacturas, sprintf("%012.2f", $Total) . "\t");
                $Contador = $Contador + 1;
                fwrite($NumFileFacturas, "\n");
            }
        }
        fclose($NumFileFacturas);
        $mensaje = "ALUMNOS" . $CodigoDelBanco . ".TXT";

        return array(
            'res' => 'Ok',
            'mensaje' => $mensaje,
            'textoBanco' => 'BOLIVARIANO',
            'Nombre1' => $mensaje
        );
    }

    function Generar_Coop_Jep($parametros)
    {
        $CheqMatricula = $parametros['CheqMatricula'];
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];

        $AdoFactura = $this->modelo->sqlCoopJep($CheqMatricula, $MBFechaI, $MBFechaF);
        $directorioBase = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/";
        // Verificar si el directorio base existe, si no, crearlo
        if (!is_dir($directorioBase)) {
            mkdir($directorioBase, 0777, true);
        }

        $RutaGeneraFile = $directorioBase . "CARGA_JEP_" . str_replace("/", "-", FechaSistema()) . ".TXT";
        $NumFileFacturas = fopen($RutaGeneraFile, 'w');
        $TipoDoc = "0";
        $Contador = 0;
        $FechaTexto = BuscarFecha($MBFechaF);

        if (count($AdoFactura) > 0) {
            fwrite($NumFileFacturas, "CODIGO ALUMNO\t");
            fwrite($NumFileFacturas, "NOMBRE ALUMNO\t");
            fwrite($NumFileFacturas, "CURSO\t");
            fwrite($NumFileFacturas, "MATRICULA\t");
            fwrite($NumFileFacturas, "PENSION\t");
            fwrite($NumFileFacturas, "TRANSPORTE\t");
            fwrite($NumFileFacturas, "REFRIGERIO\t");
            fwrite($NumFileFacturas, "DERECHOS DE EXAMEN\t");
            fwrite($NumFileFacturas, "DEUDA PENDIENTE\t");
            fwrite($NumFileFacturas, "AGENDA\t");
            fwrite($NumFileFacturas, "RECARGOS\t");
            fwrite($NumFileFacturas, "TALLERES SEMINARIOS\t");
            fwrite($NumFileFacturas, "OTROS\t");
            fwrite($NumFileFacturas, "VALOR TOTAL\t");
            fwrite($NumFileFacturas, "BICONIVA\t");
            fwrite($NumFileFacturas, "ICE\t");
            fwrite($NumFileFacturas, "IVA\t");
            fwrite($NumFileFacturas, "BISINIVA\t");
            fwrite($NumFileFacturas, "BI NO OBJETO IVA\t");
            fwrite($NumFileFacturas, "MAIL");
            foreach ($AdoFactura as $valor) {
                $Codigo = $valor['MAIL'];
                if (strlen($Codigo) == 1) {
                    $Codigo = "";
                }
                fwrite($NumFileFacturas, $valor['CODIGO ALUMNO'] . "\t");
                fwrite($NumFileFacturas, $valor['NOMBRE ALUMNO'] . "\t");
                fwrite($NumFileFacturas, $valor['CURSO'] . "\t");
                fwrite($NumFileFacturas, $this->Campo_Blanco($valor['MATRICULA']) . "\t");
                fwrite($NumFileFacturas, $this->Campo_Blanco($valor['PENSION']) . "\t");
                fwrite($NumFileFacturas, $this->Campo_Blanco($valor['TRANSPORTE']) . "\t");
                fwrite($NumFileFacturas, $this->Campo_Blanco($valor['REFRIGERIO']) . "\t");
                fwrite($NumFileFacturas, $this->Campo_Blanco($valor['DERECHOS DE EXAMEN']) . "\t");
                fwrite($NumFileFacturas, $this->Campo_Blanco($valor['DEUDA PENDIENTE']) . "\t");
                fwrite($NumFileFacturas, $this->Campo_Blanco($valor['AGENDA']) . "\t");
                fwrite($NumFileFacturas, $this->Campo_Blanco($valor['RECARGOS']) . "\t");
                fwrite($NumFileFacturas, $this->Campo_Blanco($valor['TALLERES SEMINARIOS']) . "\t");
                fwrite($NumFileFacturas, $this->Campo_Blanco($valor['OTROS']) . "\t");
                fwrite($NumFileFacturas, $this->Campo_Blanco($valor['VALOR TOTAL']) . "\t");
                fwrite($NumFileFacturas, $this->Campo_Blanco($valor['BICONIVA']) . "\t");
                fwrite($NumFileFacturas, $this->Campo_Blanco($valor['ICE']) . "\t");
                fwrite($NumFileFacturas, $this->Campo_Blanco($valor['IVA']) . "\t");
                fwrite($NumFileFacturas, $this->Campo_Blanco($valor['BISINIVA']) . "\t");
                if (intval($valor['BISINIVA']) > 0) {
                    fwrite($NumFileFacturas, $this->Campo_Blanco($valor['BISINIVA']) . "\t");
                } else {
                    fwrite($NumFileFacturas, $this->Campo_Blanco($valor['BICONIVA']) . "\t");
                }
                fwrite($NumFileFacturas, $Codigo);
                $Contador = $Contador + 1;
                fwrite($NumFileFacturas, "\n");
            }
        }
        fclose($NumFileFacturas);
        $mensaje = "CARGA_JEP_" . str_replace("/", "-", FechaSistema()) . ".TXT";

        return array(
            'res' => 'Ok',
            'mensaje' => $mensaje,
            'textoBanco' => 'COOPJEP',
            'Nombre1' => $mensaje
        );
    }

    function Generar_Produbanco($parametros)
    {
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $AdoDetalle = $parametros['AdoDetalle'];
        $DCBanco = $parametros['DCBanco'];
        $Cta_Bancaria = $parametros['Cta_Bancaria'];

        $Total_Banco = 0;
        $Mifecha = BuscarFecha($MBFechaI);
        $MiMes = date('m', strtotime($MBFechaI));
        $FechaFin = BuscarFecha(date("Y-m-t", strtotime($MBFechaI)));
        $TextoImprimio = "";
        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $Cta_Cobrar = G_NINGUNO;
        $FechaTexto = FechaSistema();

        // Comenzamos a generar el archivo: SCRECXX.TXT
        $EsComa = true;
        $Estab = false;
        $Contador = 0;
        $Factura_No = 0;

        $directorioBase = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/";
        // Verificar si el directorio base existe, si no, crearlo
        if (!is_dir($directorioBase)) {
            mkdir($directorioBase, 0777, true);
        }

        $Fecha_Meses = $MBFechaI . " al " . $MBFechaF;
        $Fecha_Meses = str_replace("/", "-", $Fecha_Meses);
        $RutaGeneraFile = $directorioBase . "PRODUBANCO RECAUDACION " . $Fecha_Meses . ".TXT";
        $NumFileFacturas = fopen($RutaGeneraFile, 'w');

        if (count($AdoDetalle) > 0) {
            foreach ($AdoDetalle as $valor) {
                $CodigoCli = $valor['CodigoC'];
                $NombreCliente = trim(substr(Sin_Signos_Especiales($valor['Cliente']), 0, 40));
                $CodigoP = $valor['CI_RUC'];
                $CodigoC = $valor['CI_RUC'];
                $Saldo = $valor['Total'] - $valor['Total_Desc'];
                $Total = $Saldo;
                $DireccionCli = $valor['Direccion'];
                $GrupoNo = $valor['Grupo'];
                $Detalle = $GrupoNo . "-" . $valor['Producto'] . "-" . $valor['Mes'];

                $Contador = $Contador + 1;
                $ValorStr = str_pad((string) ($Saldo * 100), 13, "0", STR_PAD_LEFT);
                $CodigoC = $CodigoC . str_repeat(" ", abs(4 - strlen($CodigoC)));
                $Codigo3 = trim(SinEspaciosDer2($DireccionCli));
                $Codigo1 = str_pad(substr($GrupoNo, 0, 1), 2, "0", STR_PAD_LEFT);

                if (strlen($Cta_Bancaria) < 10) {
                    $Cta_Bancaria = str_pad($Cta_Bancaria, 10, "0", STR_PAD_LEFT);
                }

                $Codigo4 = $valor['Grupo'] . " De: " . $valor['Ticket'] . "-" . mesesLetras($valor['Mes_No']);

                fwrite($NumFileFacturas, "CO" . "\t");
                fwrite($NumFileFacturas, $Cta_Bancaria . "\t");
                fwrite($NumFileFacturas, $Contador . "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, $CodigoP . "\t");
                fwrite($NumFileFacturas, "USD" . "\t");
                fwrite($NumFileFacturas, $ValorStr . "\t");
                fwrite($NumFileFacturas, "REC" . "\t");
                fwrite($NumFileFacturas, $DCBanco . "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, "R" . "\t");
                fwrite($NumFileFacturas, $_SESSION['INGRESO']['RUC'] . "\t");
                fwrite($NumFileFacturas, $NombreCliente . "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, $Codigo4 . "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, "\n");
            }
        }
        fclose($NumFileFacturas);
        $mensaje = "PRODUBANCO RECAUDACION " . $Fecha_Meses . ".TXT";

        return array(
            'res' => 'Ok',
            'mensaje' => $mensaje,
            'textoBanco' => 'PRODUBANCO',
            'Nombre1' => $mensaje
        );
    }

    function Generar_Guayaquil($parametros)
    {
        $CodigoDelBanco = $parametros['DCBanco'];
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $AdoPendiente = $parametros['AdoPendiente'];
        $Costo_Banco = $parametros['Costo_Banco'];

        $directorioBase = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/";
        // Verificar si el directorio base existe, si no, crearlo
        if (!is_dir($directorioBase)) {
            mkdir($directorioBase, 0777, true);
        }

        $RutaGeneraFile = $directorioBase . "RCE_" . date("Ymd", strtotime(FechaSistema())) .
            "_" . str_pad($CodigoDelBanco, 7, "0", STR_PAD_LEFT) . "_01.TXT";

        $NumFileFacturas = fopen($RutaGeneraFile, 'w');
        $TipoDoc = "0";
        $Contador = 0;
        $FechaTexto = BuscarFecha($MBFechaI);
        $TxtFile = "";

        if (count($AdoPendiente) > 0) {
            $TxtFile = "TOTAL NOMINA DE RECAUDACION:" . PHP_EOL;
            $Codigo3 = substr(trim(substr($_SESSION['INGRESO']['noempr'], 1, 30)), 0, 30);
            $Total = 0;
            $TotalIngreso = 0;
            $Total_Factura = 0;
            $IE = 1;
            $JE = 1;
            $KE = 1;
            $Grupo_No = $AdoPendiente[0]["Grupo"];
            $Codigo = $AdoPendiente[0]["CodigoC"];
            foreach ($AdoPendiente as $valor) {
                if ($Grupo_No != $valor['Grupo']) {
                    $Codigo4 = number_format($Total, 2, '.', ',');
                    $Codigo4 = str_pad($Codigo4, 13, " ");
                    $TxtFile .= "Grupo: " . $Grupo_No . "\t" . "Resumen de Registros por Grupo: " . $JE . "\t" . "Total a Recaudar USD" . "\t" . $Codigo4 . PHP_EOL;
                    $JE = 0;
                    $Total = 0;
                    $IE += 1;
                    $Grupo_No = $valor['Grupo'];
                    $Codigo = $valor['CodigoC'];
                }

                if ($Codigo != $valor['CodigoC']) {
                    $JE += 1;
                    $KE += 1;
                    $Codigo = $valor['CodigoC'];
                }
                $Contador += 1;
                $CodigoCli = strval(floatval($valor['CI_RUC']));
                $NombreCliente = Sin_Signos_Especiales(trim(substr($valor['Cliente'], 0, 40)));
                $Codigo1 = trim(substr($valor['Direccion'], 0, 30));
                $FechaTexto = " " . substr(MesesLetras(date('n', strtotime($MBFechaI))), 0, 3) . " " . date('Y', strtotime($MBFechaI));
                $mesLetras = substr(MesesLetras(date('n', strtotime($MBFechaI))), 0, 3);
                $Codigo2 = substr(strtoupper($mesLetras . " " . $valor['Grupo']), 0, 15);

                $Total_Factura = 0; //$valor['Saldo_Pend'];

                if ($Costo_Banco > 0) {
                    $Total_Factura += $Costo_Banco;
                }

                $Total += $Total_Factura;
                $TotalIngreso += $Total_Factura;

                $I = intval($Total_Factura);
                $J = ($Total_Factura - intval($Total_Factura)) * 100;

                fwrite($NumFileFacturas, "CO");
                fwrite($NumFileFacturas, sprintf("%07d", $Contador));
                fwrite($NumFileFacturas, $CodigoCli . str_repeat(" ", 15 - strlen($CodigoCli)));
                fwrite($NumFileFacturas, "USD");
                fwrite($NumFileFacturas, sprintf("%08d%02d", $I, $J));
                fwrite($NumFileFacturas, "REC");
                fwrite($NumFileFacturas, $NombreCliente . str_repeat(" ", 40 - strlen($NombreCliente)));
                fwrite($NumFileFacturas, date('Ym', strtotime($MBFechaI)));
                fwrite($NumFileFacturas, "CU");
                fwrite($NumFileFacturas, "PA");
                fwrite($NumFileFacturas, "ES");
                fwrite($NumFileFacturas, substr($Codigo2, 0, 15) . str_repeat(" ", 15 - strlen(substr($Codigo2, 0, 15))));

                // Registro de Fecha Vencimiento
                fwrite($NumFileFacturas, "RC");
                fwrite($NumFileFacturas, sprintf("%07d", $Contador));
                fwrite($NumFileFacturas, "VM");
                fwrite($NumFileFacturas, date('Ymd', strtotime($MBFechaI)));
                fwrite($NumFileFacturas, date('Ymd', strtotime($MBFechaF)));
                fwrite($NumFileFacturas, "FI");
                fwrite($NumFileFacturas, str_repeat("0", 30));
                fwrite($NumFileFacturas, "\n");
            }
            $Codigo4 = number_format($Total, 2, '.', ',');
            $Codigo4 = str_pad($Codigo4, 13, " ", STR_PAD_LEFT);
            $TxtFile .= "Grupo: " . $Grupo_No . "\t" . "Resumen de Registros por Grupo: " . $JE . "\t" . "Total a Recaudar USD" . "\t" . $Codigo4 . "\n";

            $Codigo4 = number_format($TotalIngreso, 2, '.', ',');
            $Codigo4 = str_pad($Codigo4, 13, " ", STR_PAD_LEFT);
            $TxtFile .= str_repeat("-", 90) . "\n";
            $TxtFile .= "Total Grupos: " . $IE . "\t" . "Total Alumnos: " . $KE . "\t\t" . "Total a Recaudar USD" . "\t" . $Codigo4 . "\n";
        }
        fclose($NumFileFacturas);
        $mensaje = "RCE_" . date("Ymd", strtotime(FechaSistema())) .
            "_" . str_pad($CodigoDelBanco, 7, "0", STR_PAD_LEFT) . "_01.TXT";

        return array(
            'res' => 'Ok',
            'mensaje' => $mensaje,
            'textoBanco' => 'GUAYAQUIL',
            'Nombre1' => $mensaje,
            'contenido' => $TxtFile
        );

    }
    function Campo_Blanco($Dato)
    {
        if (strlen($Dato) > 1 && floatval($Dato) !== 0) {
            return number_format(floatval($Dato), 2, '.', '');
        } else {
            return "";
        }
    }

    function SetearBlancos($strg, $longStrg, $noBlancos, $esNumero, $conLineas = false, $decimales = false)
    {
        if (is_null($strg) || empty($strg)) {
            $strg = "";
        }
        $strg = $this->CompilarString($strg);
        if ($esNumero) {
            if ($decimales) {
                $sinEspacios = number_format(floatval($strg), 2, '.', '');
            } else {
                $sinEspacios = strval(intval($strg));
            }
            if (strlen($sinEspacios) < $longStrg) {
                $sinEspacios = str_pad($sinEspacios, $longStrg, ' ', STR_PAD_LEFT);
            }
        } else {
            if ($longStrg > 0) {
                $sinEspacios = $strg . str_repeat(" ", $longStrg);
                $sinEspacios = substr($sinEspacios, 0, $longStrg);
            } else {
                $sinEspacios = trim($strg);
            }
        }
        if ($noBlancos > 0) {
            $sinEspacios .= str_repeat(" ", $noBlancos);
        }
        if ($conLineas) {
            $sinEspacios .= "|";
        }
        if ($sinEspacios === "") {
            $sinEspacios = " ";
        }
        return $sinEspacios;
    }

    function CompilarString($cadSQL, $lString = 0, $quitarPuntos = false)
    {
        if ($lString > 0) {
            $cadSQL = substr($cadSQL, 0, $lString);
        }
        // Eliminación de caracteres específicos
        $caracteresAEliminar = ['|', "\r", "\n", "'", ",", "$", "#", "&", "'"];
        foreach ($caracteresAEliminar as $char) {
            $cadSQL = str_replace($char, '', $cadSQL);
        }
        // Reducción de espacios múltiples a un solo espacio
        $cadSQL = preg_replace('/\s+/', ' ', $cadSQL);
        // Manejo de cadenas nulas o vacías
        if (is_null($cadSQL) || $cadSQL === '') {
            $cadSQL = '.';
        }
        // Eliminación de puntos al inicio y al final, si es necesario
        if ($quitarPuntos) {
            $cadSQL = trim($cadSQL, '.');
        }
        // Valor por defecto en caso de cadena vacía
        if ($cadSQL === '') {
            $cadSQL = G_NINGUNO; // Asumiendo que Ninguno es un valor por defecto
        }
        return $cadSQL;
    }

    function EliminaArchivosTemporales($tempFilePath)
    {
        $basePath = dirname(__DIR__, 3);
        $fullPath = $basePath . $tempFilePath;
        if (file_exists($fullPath)) {
            if (unlink($fullPath)) {
                return ['res' => 0]; // Éxito al eliminar el archivo
            } else {
                return ['res' => 2]; // Fallo al eliminar el archivo
            }
        } else {
            return ['res' => 1, 'res2' => $fullPath]; // El archivo no existe
        }
    }
}
?>