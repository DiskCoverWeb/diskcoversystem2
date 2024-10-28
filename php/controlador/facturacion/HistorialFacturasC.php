<?php
/** 
 * AUTOR DE RUTINA : Dallyana Vanegas
 * FECHA CREACION : 30/01/2024
 * FECHA MODIFICACION : 18/03/2024
 * DESCIPCION : Clase que se encarga de manejar el Historial de Facturas
 */

include (dirname(__DIR__, 2) . '/modelo/facturacion/HistorialFacturasM.php');
require_once (dirname(__DIR__, 2) . '/modelo/facturacion/punto_ventaM.php');
require_once (dirname(__DIR__, 3) . '/lib/phpmailer/enviar_emails.php');
require (dirname(__DIR__, 3) . '/lib/fpdf/cabecera_pdf.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$controlador = new HistorialFacturasC();

if (isset($_GET['CheqAbonos_Click'])) {
    echo json_encode($controlador->CheqAbonos_Click());
}

if (isset($_GET['CheqCxC_Click'])) {
    echo json_encode($controlador->CheqCxC_Click());
}

if (isset($_GET['Form_Activate'])) {
    echo json_encode($controlador->Form_Activate());
}

if (isset($_GET['ToolBarMenu_ButtonClick'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ToolBarMenu_ButtonClick($parametros));
}

if (isset($_GET['ToolbarMenu_ButtonMenuClick'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ToolbarMenu_ButtonMenuClick($parametros));
}

if (isset($_GET['ListCliente_LostFocus'])) {
    $ListClienteText = $_POST['ListClienteText'];
    echo json_encode($controlador->ListCliente_LostFocus($ListClienteText));
}

if (isset($_GET['DCCliente_LostFocus'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->DCCliente_LostFocus($parametros));
}

if (isset($_GET['Imprimir'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Imprimir($parametros));
}

if (isset($_GET['SRI_Enviar_Mails'])) {
    $params = $_POST['parametros'];

    $TFA = $params['FA'];
    $SRI_Autorizacion = $params['SRI_Autorizacion'];
    $Tipo_Documento = $params['Tipo_Documento'];
    echo json_encode($controlador->SRI_Enviar_Mails($TFA, $SRI_Autorizacion, $Tipo_Documento));
}

if (isset($_GET['Recibo_Enviar_Mails'])) {
    $params = $_POST['parametros'];

    $TFA = $params['FA'];
    echo json_encode($controlador->Recibo_Enviar_Mails($TFA));
}

if (isset($_GET['EnviarMails'])) {
    $params = $_POST['parametros'];

    $archivo_pdf = $params['archivo_pdf'];
    $archivo_xml = $params['archivo_xml'];
    $TFA = $params['FA'];
    $SRI_Autorizacion = $params['SRI_Autorizacion'];
    $Tipo_Documento = $params['Tipo_Documento'];
    echo json_encode($controlador->EnviarMails($archivo_pdf, $archivo_xml, $TFA, $SRI_Autorizacion, $Tipo_Documento));
}

if (isset($_GET['EnviarMailRecibo'])) {
    $params = $_POST['parametros'];
    echo json_encode($controlador->EnviarMailRecibo($params));
}

if (isset($_GET['EnviarMailAbono'])) {
    $params = $_POST['parametros'];
    echo json_encode($controlador->EnviarMailAbono($params));
}

class HistorialFacturasC
{
    private $modelo;
    private $email;
    private $pdf;
    private $puntoventa;

    function __construct()
    {
        $this->modelo = new HistorialFacturasM();
        $this->email = new enviar_emails();
        $this->puntoventa = new punto_ventaM();
        $this->pdf = new cabecera_pdf();
    }

    function CheqAbonos_Click()
    {
        return $this->modelo->CheqAbonos_Click();
    }

    function CheqCxC_Click()
    {
        return $this->modelo->CheqCxC_Click();
    }

    function EnviarMails($archivo_pdf, $archivo_xml, $TFA, $SRI_Autorizacion, $Tipo_Documento)
    {
        $RutaPDF = "";
        $RutaXML = "";

        switch ($Tipo_Documento) {
            case "FA":
                $RutaPDF = $archivo_pdf;
                $RutaXML = $archivo_xml;
                break;
            case "NC":
                $RutaPDF = $archivo_pdf;
                $RutaXML = $archivo_xml;
                break;
            case "LC":
                $RutaPDF = $archivo_pdf;
                $RutaXML = $archivo_xml;
                break;
            case "GR":
                $RutaPDF = $archivo_pdf;
                $RutaXML = $archivo_xml;
                break;
            case "RE":
                $RutaPDF = $archivo_pdf;
                $RutaXML = $archivo_xml;
                break;
            case "AB":
                $RutaPDF = "ninguno.pdf";
                $RutaXML = "ninguno.xml";
                break;
        }

        $fecha_actual = date("Y-m-d");
        if (substr($TFA['ClaveAcceso'], 8, 2) == "07") {
            $TMailMensaje = "Cliente: " . $TFA['Cliente'] . "\r\n" .
                "Clave de Acceso: \r\n" . $TFA['ClaveAcceso'] . "\r\n" .
                "Hora de Generacion: " . $SRI_Autorizacion['Hora_Autorizacion'] . "\r\n" .
                "Emision: " . $fecha_actual . "\r\n" .
                "Vencimiento: " . $SRI_Autorizacion['Fecha_Autorizacion'] . "\r\n" .
                "Autorizacion: \r\n" . $SRI_Autorizacion['Autorizacion'] . "\r\n" .
                "Retencion No. " . $TFA['Serie_R'] . "-" . sprintf("%09d", $TFA['Retencion']) . "\r\n" .
                "Factura No. " . $TFA['Serie'] . "-" . sprintf("%09d", $TFA['Factura']) . "\r\n";

            $TMailAsunto = $TFA['Cliente'] . ", Retencion No. " . $TFA['Serie_R'] . "-" . sprintf("%09d", $TFA['Retencion']);
        } elseif (substr($TFA['ClaveAcceso'], 8, 2) == "03") {
            $TMailMensaje = "Cliente: " . $TFA['Cliente'] . "\r\n" .
                "Clave de Acceso: \r\n" . $TFA['ClaveAcceso_LC'] . "\r\n" .
                "Hora de Generacion: " . $SRI_Autorizacion['Hora_Autorizacion'] . "\r\n" .
                "Emision: " . $fecha_actual . "\r\n" .
                "Vencimiento: " . $TFA['Fecha_Autorizacion'] . "\r\n" .
                "Autorizacion: \r\n" . $SRI_Autorizacion['Autorizacion'] . "\r\n" .
                "Liquidacion de Compras No. " . $TFA['Serie_LC'] . "-" . sprintf("%09d", $TFA['Factura']) . "\r\n";

            $TMailAsunto = $TFA['Cliente'] . ", Liquidacion de Compras No. " . $TFA['Serie_LC'] . "-" . sprintf("%09d", $TFA['Factura']);
        } else {
            $TMailMensaje = "Cliente: " . $TFA['Cliente'] . "\r\n" .
                "Clave de Acceso: \r\n" . $TFA['ClaveAcceso'] . "\r\n" .
                "Emision: " . $fecha_actual . "\r\n" .
                "Vencimiento: " . $TFA['Fecha_V']['date'] . "\r\n" .
                "Fecha Autorizado: " . $TFA['Fecha_Aut']['date'] . "\r\n" .
                "Autorizacion: \r\n" . $TFA['Autorizacion'] . "\r\n" .
                "Factura No. " . $TFA['Serie'] . "-" . sprintf("%09d", $TFA['Factura']) . "\r\n";

            if ($Tipo_Documento === "AB") {
                $TMailMensaje .= "\r\nSU PAGO FUE REGISTRADO CON EXITO\r\nEL " . FechaStrg($TFA['Fecha_C']) . "\r\n" . $TFA['Nota'] . "\r\n";
            } else {
                $TMailMensaje .= "Hora de Generacion: " . $TFA['Hora_FA'] . "\r\n";
            }
            $TMailAsunto = $TFA['Cliente'] . ", Factura No. " . $TFA['Serie'] . "-" . sprintf("%09d", $TFA['Factura']);
        }

        if (isset($TFA['EmailC']) && $TFA['EmailC'] !== '.') {
            $TMailPara[] = $TFA['EmailC'];
        }

        if (isset($TFA['EmailR']) && $TFA['EmailR'] !== '.') {
            $TMailPara[] = $TFA['EmailR'];
        }

        $TMailPara = implode(', ', $TMailPara);

        $TMailMensaje .= str_repeat("-", 45) . "\r\n" .
            "Email(s) Destinatario(s):\r\n" .
            $TMailPara . "\r\n" .
            str_repeat("_", 45) . "\r\n" .
            $_SESSION['INGRESO']['Nombre_Comercial'] . "\r\n" .
            $_SESSION['INGRESO']['Razon_Social'] . "\r\n" .
            $_SESSION['INGRESO']['Telefono1'] . "/" . $_SESSION['INGRESO']['Telefono1'] . "\r\n" .
            "Dir. " . $_SESSION['INGRESO']['Direccion'] . "\r\n" .
            strtoupper($_SESSION['INGRESO']['Ciudad']) . "-" . strtoupper($_SESSION['INGRESO']['NombrePais']) . "\r\n";

        $TMailAdjunto = array($RutaPDF, $RutaXML);

        $rps = $this->email->enviar_email($TMailAdjunto, $TMailPara, $TMailMensaje, $TMailAsunto, $HTML = false);
        return $rps;
    }

    function Recibo_Enviar_Mails($TFA)
    {
        $Comprobante = "";

        if (strlen($TFA['Serie']) == 6 && $TFA['Factura'] > 0) {
            //$Comprobante = "Recibo No " . $TFA['Serie'] . "-" . sprintf("%09d", $TFA['Factura']);
            $res = $this->modelo->Generar_Recibo_PDF($TFA);
            //print_r($res['nombre']); die();

            if (isset($res['nombre']) && $res['nombre']) {
                //$TMailAdjunto[] = dirname(__DIR__, 3) . "/TEMP/" . $res['nombre'];
                //$Comprobante = $res['nombre'];
                return $res;
            }
        }
    }

    function EnviarMailRecibo($parametros)
    {
        $TFA = $parametros['FA'];
        $Comprobante = $parametros['archivo'];
        $fecha_actual = date("Y-m-d");

        $TMailMensaje = "Cliente: " . $TFA['Cliente'] . "\n" .
            "Codigo: " . $TFA['CI_RUC'] . "\n" .
            "Emision: " . $fecha_actual . "\n" .
            $Comprobante . "\n";

        $TMailAsunto = $TFA['Cliente'] . ", " . $Comprobante;

        $TMailAdjunto[] = $Comprobante;

        if (isset ($TFA['EmailC']) && $TFA['EmailC'] !== '.') {
            $TMailPara[] = $TFA['EmailC'];
        }

        if (isset ($TFA['EmailR']) && $TFA['EmailR'] !== '.') {
            $TMailPara[] = $TFA['EmailR'];
        }

        $TMailPara = implode(', ', $TMailPara);

        $rps = $this->email->enviar_email($TMailAdjunto, $TMailPara, $TMailMensaje, $TMailAsunto, $HTML = false);
        return $rps;
    }



    function SRI_Generar_XML_Firmado($ClaveDeAcceso)
    {
        $resultados = $this->modelo->SRI_Generar_XML_Firmado($ClaveDeAcceso);
        $RutaSysBases = dirname(__DIR__, 3);
        if (!empty($resultados)) {
            $RutaXMLFirmado = $RutaSysBases . "/TEMP/" . $ClaveDeAcceso . ".xml";
            file_put_contents($RutaXMLFirmado, $resultados[0]['Documento_Autorizado']);
            return 1;
        }
    }

    function SRI_Enviar_Mails($TFA, $SRI_Autorizacion, $Tipo_Documento)
    {
        $Factura = $TFA['Factura'];
        $Serie = $TFA['Serie'];
        $CodigoC = $TFA['CodigoC'];
        $nombre = $TFA['Serie'] . '-' . generaCeros($TFA['Factura'], 7);
        $Clave_Acceso = $TFA['ClaveAcceso'];
        $rep = $this->puntoventa->pdf_factura_elec($Factura, $Serie, $CodigoC, $nombre, $Clave_Acceso, $periodo = false, 0, 1);
        if ($rep == 1) {
            $rep2 = $this->SRI_Generar_XML_Firmado($Clave_Acceso);
            if ($rep2 == 1) {
                return array('res_pdf' => $rep, 'res_xml' => $rep2, 'pdf' => $nombre, 'clave' => $Clave_Acceso);
            }
            return array('res_pdf' => $rep, 'res_xml' => -1, 'pdf' => $nombre);
        } else {
            return array('res_pdf' => -1, 'res_xml' => -1);
        }
    }

    function Historico_Facturas($parametros)
    {
        $Opcion = $parametros['Opcion'];

        $FechaIni = BuscarFecha($parametros['MBFechaI']);
        $FechaFin = BuscarFecha($parametros['MBFechaF']);

        $PorCxC = false;

        if ($parametros['CheqCxC'] == 1) {
            $PorCxC = true;
        }

        $tipoConsulta = $this->Tipo_De_Consulta($parametros);
        $sSQL = $this->modelo->Historico_Facturas($tipoConsulta, $FechaFin);
        $Totales = $this->Totales_CxC_Abonos($sSQL['AdoQuery'], $Opcion);

        return array(
            'DGQuery' => $sSQL['DGQuery'],
            'AdoQuery' => $sSQL['AdoQuery'],
            'num_filas' => $sSQL['num_filas'],
            'Totales' => $Totales,
            'Opcion' => $Opcion
        );
    }

    function Ventas_Productos($parametros, $FechaIni, $FechaFin)
    {
        //$Opcion = 8;
        $Opcion = $parametros['Opcion'];

        $Con_Costeo = $parametros['Con_Costeo'];
        $Si_No = $parametros['Si_No'];
        $CodigoInv = $parametros['CodigoInv'];

        $tipoConsulta = $this->Tipo_De_Consulta($parametros, true);
        $tipoConsulta2 = $this->Tipo_De_Consulta($parametros);

        $sSQL = $this->modelo->Ventas_Productos($FechaIni, $FechaFin, $Si_No, $Con_Costeo, $CodigoInv, $tipoConsulta, $tipoConsulta2);

        $Total = 0;
        $Abono = 0;
        $Saldo = 0;

        if (count($sSQL['AdoQuery']) > 0) {
            foreach ($sSQL["AdoQuery"] as $record) {
                if ($Si_No) {
                    $Saldo += $record["Costos"];
                }
                $Total += $record["Total"];
            }
        }

        $label_facturado = number_format($Total, 2, '.', ',');
        $label_abonado = number_format($Abono, 2, '.', ',');
        $label_saldo = number_format($Saldo, 2, '.', ',');

        return array(
            'DGQuery' => $sSQL['DGQuery'],
            'AdoQuery' => $sSQL['AdoQuery'],
            'num_filas' => $sSQL['num_filas'],
            'Opcion' => $Opcion,
            'label_facturado' => $label_facturado,
            'label_abonado' => $label_abonado,
            'label_saldo' => $label_saldo,
        );
    }

    function ToolBarMenu_ButtonClick($parametros)
    {
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $CheqCxC = $parametros['CheqCxC'];
        $ListCliente = $parametros['ListCliente'];
        $FA = $parametros['FA'];
        $idBtn = $parametros['idBtn'];

        FechaValida($MBFechaI);
        FechaValida($MBFechaF);

        $FechaIni = BuscarFecha($MBFechaI);
        $FechaFin = BuscarFecha($MBFechaF);
        $Mifecha = $FechaIni;
        $FechaTexto = $FechaFin;

        $FA['Fecha_Corte'] = $MBFechaF;
        $FA['Fecha_Desde'] = $MBFechaI;
        $FA['Fecha_Hasta'] = $MBFechaF;

        $PorCxC = false;

        if ($CheqCxC == 1)
            $PorCxC = true;

        if ($ListCliente == "Todos") {
            $FA['TC'] = G_NINGUNO;
            $FA['Serie'] = G_NINGUNO;
            $FA['Factura'] = 0;
        }

        $Total = 0;
        $Abono = 0;
        $res = array();

        $Opcion = $parametros['Opcion'];
        switch ($idBtn) {
            case "Facturas":
                Actualizar_Abonos_Facturas_SP($FA);
                $res = $this->Historico_Facturas($parametros);
                $totales = $res['Totales'];
                $Opcion = $res['Opcion'];
                $Total = $totales['label_facturado'];
                $Abono = $totales['label_abonado'];
                $label_saldo = $totales['label_saldo'];
                break;
            case "Protestado":
                $res = $this->Cheques_Protestados($parametros);
                $totales = $res['Totales'];
                $Opcion = $res['Opcion'];
                $Total = $totales['label_facturado'];
                $Abono = $totales['label_abonado'];
                $label_saldo = $totales['label_saldo'];
                break;
            case "Retenciones_NC":
                $res = $this->Abonos_Facturas(true, $parametros);
                $Opcion = $res['Opcion'];
                $label_facturado = $res['label_facturado'];
                $label_abonado = $res['label_abonado'];
                $label_saldo = $res['label_saldo'];
                break;
            case "Por_Buses":
                //$Opcion = 12;
                if ($CheqCxC == 1)
                    $PorCxC = true;
                $res = $this->modelo->Por_Buses($parametros['DCCliente']);
                break;
            case "Listado_Tarjetas":
                $res = $this->modelo->Listado_Tarjetas();
                break;
            case "CxC_Clientes":
                Actualizar_Abonos_Facturas_SP($FA);
                $res = $this->Listado_Facturas_Por_Meses($parametros, True);
                $Opcion = $res['Opcion'];
                $label_saldo = $res['label_saldo'];
                break;
            case "Listar_Por_Meses":
                $res = $this->Listado_Facturas_Por_Meses($parametros, False);
                $Opcion = $res['Opcion'];
                $label_saldo = $res['label_saldo'];
                break;
            case "Estado_Cuenta_Cliente":
                if ($ListCliente == "Todos") {
                    $FA['CodigoC'] = "Todos";
                }
                $fechaSistema = FechaSistema();
                $fechaSistema = date("d/m/Y", strtotime($fechaSistema));
                Reporte_Cartera_Clientes_SP(PrimerDiaMes($MBFechaI), UltimoDiaMes2(date('d/m/Y'), 'Ymd'), $FA['CodigoC']);
                $res = $this->modelo->Estado_Cuenta_Cliente($MBFechaI, $fechaSistema, $FA);
                if ($res['num_filas']) {
                    $Total = 0;
                    $Abono = 0;
                    foreach ($res['AdoQuery'] as $fila) {
                        $Total += $fila["Cargos"];
                        $Abono += $fila["Abonos"];
                    }
                }
                $Opcion = $res['Opcion'];
                break;
            case "Listados_Medidor":
                break;
            case "Base_Access":
                break;
            case "Base_MySQL":
                break;
            case "Buscar_Malla":
                return array('DCCliente' => $this->modelo->Buscar_Malla(), 'idBtn' => $idBtn);
        }

        $label_facturado = number_format($Total, 2, '.', ',');
        $label_abonado = number_format($Abono, 2, '.', ',');
        $label_saldo = number_format($Total - $Abono, 2, '.', ',');


        return array(
            'label_facturado' => $label_facturado,
            'label_abonado' => $label_abonado,
            'label_saldo' => $label_saldo,
            'tbl' => $res['DGQuery'],
            'AdoQuery' => $res['AdoQuery'],
            'num_filas' => $res['num_filas'],
            'idBtn' => $idBtn,
            'Opcion' => $Opcion,
        );
    }

    function Imprimir($parametros)
    {

        $basepath = dirname(__DIR__, 3) . "/TEMP/IMPRIMIR/";
        if (!is_dir($basepath)) {
            mkdir($basepath, 0777, true);
        }

        $filename = $parametros['MensajeEncabData'] . " " . $parametros['SQLMsg1'] . ".pdf";
        $filename = str_replace(' ', '_', $filename);
        $path = $basepath . $filename;

        $i = 1;
        while (file_exists($path)) {
            $info = pathinfo($path);
            $filename = $info['filename'] . '_' . $i . '.' . $info['extension'];
            $path = $info['dirname'] . '/' . $filename;
            $i++;
        }

        $this->pdf->generarPDFTabla($parametros, $path);

        return [
            'response' => 1,
            'nombre' => $filename,
            'mensaje' => "SE GENERO EL SIGUIENTE ARCHIVO: \n" . $filename
        ];
    }

    function RutaDocumentoPDF($nombre, $CA)
    {
        $basepath = dirname(__DIR__, 3) . "/TEMP/";
        if (!is_dir($basepath)) {
            mkdir($basepath, 0777, true);
        }

        $filename = $nombre . ".pdf";
        $filename = str_replace(' ', '_', $filename);
        $path = $basepath . $filename;

        $i = 1;
        while (file_exists($path)) {
            $info = pathinfo($path);
            $filename = $info['filename'] . '_' . $i . '.' . $info['extension'];
            $path = $info['dirname'] . '/' . $filename;
            $i++;
        }

        $this->pdf->Imprimir_Abono_Anticipado($CA, $path);

        return $filename;
    }

    function Listado_Facturas_Por_Meses($parametros, $Por_FA, $SQL_Server = true)
    {
        $DCClienteVal = $parametros['DCCliente'];
        $Por_Fecha = $parametros['Por_Fecha'];


        $MBFechaI = FechaValida($parametros['MBFechaI']);
        $MBFechaF = FechaValida($parametros['MBFechaF']);

        $Valor_Total = 0;

        $MesIni = date('n', strtotime($parametros['MBFechaI']));
        $MesFin = date('n', strtotime($parametros['MBFechaF']));

        $FechaIni = BuscarFecha($parametros['MBFechaI']);
        $FechaFin = BuscarFecha($parametros['MBFechaF']);

        $AnioI = date('Y', strtotime($parametros['MBFechaI']));
        $AnioF = date('Y', strtotime($parametros['MBFechaF']));

        $AnioFin = date('Y', strtotime($parametros['MBFechaF'])) - 1;
        $AnioIni = $AnioFin - 6;

        $TAnios = array_fill($AnioIni, $AnioFin - $AnioIni + 1, 0);
        $VerAnios = array_fill($AnioIni, $AnioFin - $AnioIni + 1, false);

        $NumEmpresa = $_SESSION['INGRESO']['item'];
        $Periodo_Contable = $_SESSION['INGRESO']['periodo'];
        $CodigoUsuario = $_SESSION['INGRESO']['CodigoU'];

        $Saldo = 0;
        $SaldoAnterior = 0;
        $VetPEN = False;
        $TPEN = 0;

        $Opcion = $parametros['Opcion'];

        for ($JE = $AnioIni; $JE <= $AnioFin; $JE++) {
            $TAnios[$JE] = 0;
            $VerAnios[$JE] = false;
        }

        for ($JE = 1; $JE <= 12; $JE++) {
            $TMeses[$JE] = 0;
            $VerMeses[$JE] = false;
            $CxC_VerMeses[$JE] = false;
            $MesS = MesesLetras($JE);
            $sSQL = "UPDATE Detalle_Factura 
                        SET Mes_No = " . $JE . " 
                        WHERE Item = '" . $NumEmpresa . "' 
                        AND Periodo = '" . $Periodo_Contable . "' 
                        AND Mes = '" . $MesS . "' 
                        AND Mes_No = 0";
            Ejecutar_SQL_SP($sSQL);
        }

        $Si_No = false;
        $NumFacturas = 12;

        //Actualizamos Clientes
        $sSQL = "UPDATE Clientes SET X = '.' WHERE X <> '.' ";
        Ejecutar_SQL_SP($sSQL);

        //Patron de busqueda
        $Patron_Busqueda = $DCClienteVal;
        if ($Patron_Busqueda == "")
            $Patron_Busqueda = G_NINGUNO;

        //Insertamos los clientes que estan en procesos
        $sSQL = "DELETE * 
                    FROM Saldo_Diarios 
                    WHERE Item = '" . $NumEmpresa . "' 
                    AND CodigoU = '" . $CodigoUsuario . "' 
                    AND TP = 'CXCP'";
        Ejecutar_SQL_SP($sSQL);

        //Actualizamos patrones de busqueda Facturado
        if ($SQL_Server) {
            $sSQL = "UPDATE Clientes SET X = 'A' FROM Clientes AS C, Facturas AS F ";
        } else {
            $sSQL = "UPDATE Clientes AS C, Facturas AS F SET C.X = 'A' ";
        }
        $sSQL .= " WHERE F.Item = '" . $NumEmpresa . "' 
                    AND F.Periodo = '" . $Periodo_Contable . "' 
                    " . $this->Buscar_x_Patron($parametros, true) . " 
                    AND C.Codigo = F.CodigoC";
        Ejecutar_SQL_SP($sSQL);

        if ($parametros['CheqPreFa'] != 0) {
            if ($SQL_Server) {
                $sSQL = "UPDATE Clientes 
                         SET X = 'A' 
                         FROM Clientes AS C, Clientes_Facturacion AS F";
            } else {
                $sSQL = "UPDATE Clientes AS C, Clientes_Facturacion AS F 
                         SET C.X = 'A'";
            }

            $sSQL .= " WHERE F.Item = '" . $NumEmpresa . "' 
                      " . $this->Buscar_x_Patron($parametros) . " 
                      AND C.Codigo = F.Codigo";

            Ejecutar_SQL_SP($sSQL);
        }

        $sSQL = "INSERT INTO Saldo_Diarios (CodigoC, Item, CodigoU, TP) 
         SELECT Codigo, '" . $NumEmpresa . "' AS Item, '" . $CodigoUsuario . "' AS CodigoUs, 'CXCP' AS TP 
         FROM Clientes 
         WHERE X = 'A' 
         GROUP BY Codigo";

        Ejecutar_SQL_SP($sSQL);

        Eliminar_Nulos_SP("Saldo_Diarios");

        //Listado de facturas emitidas
        $sSQL = "SELECT F.CodigoC,C.Cliente,C.Grupo,F.Fecha,F.T,";
        if ($Por_FA) {
            $sSQL .= "Total_MN,Saldo_MN ";
            $sSQL .= "FROM Facturas As F,Clientes As C ";
        } else {
            if ($parametros['OpcPend']) {
                $sSQL .= "Mes_No,(Total-Total_Desc-Total_Desc2+Total_IVA) As Saldo_MN,Mes_No,Ticket ";
            } else {
                $sSQL .= "Mes_No,(Total-Total_Desc-Total_Desc2+Total_IVA) As Total_MN,Mes_No,Ticket ";
            }
            $sSQL .= "FROM Detalle_Factura As F,Clientes As C ";
        }
        if ($Por_Fecha) {
            $sSQL .= "WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' ";
        } else {
            $sSQL .= "WHERE F.Fecha <= '" . $FechaFin . "' ";
        }
        $sSQL .= "AND F.Item = '" . $NumEmpresa . "' ";
        $sSQL .= "AND F.Periodo = '" . $Periodo_Contable . "' ";
        $sSQL .= $this->Tipo_De_Consulta($parametros, null, null, true);
        $sSQL .= "AND F.CodigoC = C.Codigo ";
        $sSQL .= "ORDER BY C.Cliente, F.Fecha ";

        $AdoHistoria = $this->modelo->datos($sSQL);

        //Actualizamos valores de los datos consultados
        $K = 0;
        if (count($AdoHistoria) > 0) {
            $CodigoCli = $AdoHistoria[0]['CodigoC'];
            foreach ($AdoHistoria as $historia) {
                if ($CodigoCli != $historia['CodigoC']) {
                    $SQLSubTotal = "";
                    $Total = 0;
                    for ($JE = $AnioIni; $JE <= $AnioFin; $JE++) {

                        $Total += $TAnios[$JE];
                        $SQLSubTotal .= "P_" . strval($JE) . " = " . $TAnios[$JE] . ", ";
                        $TAnios[$JE] = 0;

                    }
                    for ($JE = 1; $JE <= 12; $JE++) {
                        $Total += $TMeses[$JE];
                        $SQLSubTotal .= MesesLetras($JE) . " = " . $TMeses[$JE] . ", ";
                        $TMeses[$JE] = 0;
                    }

                    $SQLSubTotal .= "PEN = " . $TPEN . " ";
                    $Total += $TPEN;
                    $TPEN = 0;
                    if ($Total != 0) {
                        $sSQL = "UPDATE Saldo_Diarios 
                                 SET " . $SQLSubTotal . " 
                                 WHERE Item = '" . $NumEmpresa . "' 
                                 AND CodigoU = '" . $CodigoUsuario . "' 
                                 AND CodigoC = '" . $CodigoCli . "' 
                                 AND TP = 'CXCP'";
                        Ejecutar_SQL_SP($sSQL);
                    }

                    $CodigoCli = $historia['CodigoC'];
                }
                $K++;
                $Total = ($parametros['OpcPend']) ? $historia['Saldo_MN'] : $historia['Total_MN'];
                $Total = round($Total, 2);
                $Valor_Total += $Total;
                $timestamp = $historia['Fecha']->getTimestamp();

                //Seteamos a;os y meses de actualizacion
                if ($Por_FA) {
                    $MesIni = date('n', $timestamp);
                    $AnioAct = date('Y', $timestamp);
                } else {
                    $MesIni = $historia['Mes_No'];
                    $AnioAct = intval($historia['Ticket']);
                }

                if ($MesIni == 0)
                    $MesIni = date('n', $timestamp);

                //Actualizamos los valores en los campos respectivos
                if ($Total != 0) {
                    if ($AnioIni <= $AnioAct && $AnioAct <= $AnioFin) {
                        $TAnios[$AnioAct] += $Total;
                        $VerAnios[$AnioAct] = true;
                    } elseif ($AnioAct < $AnioIni) {
                        $TPEN += $Total;
                        $VerPEN = true;
                    } else {
                        $TMeses[$MesIni] += $Total;
                        $VerMeses[$MesIni] = true;
                    }
                }
            }
            $SQLSubTotal = "";
            $Total = 0;

            for ($JE = $AnioIni; $JE <= $AnioFin; $JE++) {
                $Total += $TAnios[$JE];
                $SQLSubTotal .= "P_" . strval($JE) . " = " . $TAnios[$JE] . ", ";
                $TAnios[$JE] = 0;
            }

            for ($JE = 1; $JE <= 12; $JE++) {
                $Total += $TMeses[$JE];
                $SQLSubTotal .= MesesLetras($JE) . " = " . $TMeses[$JE] . ", ";
                $TMeses[$JE] = 0;
            }

            $SQLSubTotal .= "PEN = " . $TPEN . " ";
            $Total += $TPEN;
            $TPEN = 0;

            if ($Total != 0) {
                $sSQL = "UPDATE Saldo_Diarios 
                         SET " . $SQLSubTotal . " 
                         WHERE Item = '" . $NumEmpresa . "' 
                         AND CodigoU = '" . $CodigoUsuario . "' 
                         AND CodigoC = '" . $CodigoCli . "'
                         AND TP = 'CXCP' ";
                Ejecutar_SQL_SP($sSQL);
            }
        }
        //Listado CxC PreFacturable
        if ($parametros['CheqPreFa'] != 0) {
            $K = 0;
            $sSQL = "SELECT C.Cliente,F.Codigo,C.Grupo,F.Fecha,SUM(F.Valor-F.Descuento) As Total_MN
                    FROM Clientes_Facturacion As F,Clientes As C
                    WHERE F.Item = '" . $NumEmpresa . "'
                    AND F.Fecha <= '" . $FechaFin . "'
                    " . $this->Buscar_x_Patron($parametros, true) . "
                    AND F.Codigo = C.Codigo
                    GROUP BY C.Cliente,F.Codigo,C.Grupo,F.Fecha
                    ORDER BY C.Cliente,F.Codigo,C.Grupo,F.Fecha ";

            $AdoHistoria = $this->modelo->db->datos($sSQL);
            //Actualizamos valores de los datos consultados Pre-Factura
            if (count($AdoHistoria) > 0) {
                $CodigoCli = $AdoHistoria[0]['CodigoC'];
                foreach ($AdoHistoria as $historia) {
                    if ($CodigoCli != $historia['Codigo']) {
                        $Total = 0;
                        $SQLSubTotal = "";
                        for ($JE = 1; $JE <= 12; $JE++) {
                            $Total += $TMeses[$JE];
                            $SQLSubTotal .= "CxC_" . substr(MesesLetras($JE), 0, 3) . " = " . $TMeses[$JE] . ", ";
                            $TMeses[$JE] = 0;
                        }
                        $SQLSubTotal = substr($SQLSubTotal, 0, -2);
                        if ($Total != 0) {
                            $sSQL = "UPDATE Saldo_Diarios 
                                     SET " . $SQLSubTotal . " 
                                     WHERE Item = '" . $NumEmpresa . "'
                                     AND CodigoU = '" . $CodigoUsuario . "'
                                     AND CodigoC = '" . $CodigoCli . "'
                                     AND TP = 'CXCP' ";
                            Ejecutar_SQL_SP($sSQL);
                        }
                        $CodigoCli = $historia['Codigo'];
                    }
                    $K++;
                    $CodigoCli = $historia['Codigo'];
                    $Total = round($historia['Total_MN'], 2);
                    $Valor_Total += $Total;
                    //Seteamos anios y meses de actualizacion
                    $MesIni = date('n', strtotime($historia['Fecha']));
                    $AnioAct = date('Y', strtotime($historia['Fecha']));
                    //Actualizamos los valores en los campos respectivos
                    if ($Total != 0 && $AnioAct >= $AnioIni) {
                        $TMeses[$MesIni] += $Total;
                    }
                }
                $SQLSubTotal = "";
                $Total = 0;
                for ($JE = 1; $JE <= 12; $JE++) {
                    $Total += $TMeses[$JE];
                    $SQLSubTotal .= "CxC_" . substr(MesesLetras($JE), 0, 3) . " = " . $TMeses[$JE] . ", ";
                    $TMeses[$JE] = 0;
                }
                $SQLSubTotal = substr($SQLSubTotal, 0, -2);
                if ($Total != 0) {
                    $sSQL = "UPDATE Saldo_Diarios 
                             SET " . $SQLSubTotal . " 
                             WHERE Item = '" . $NumEmpresa . "'
                             AND CodigoU = '" . $CodigoUsuario . "'
                             AND CodigoC = '" . $CodigoCli . "'
                             AND TP = 'CXCP' ";
                    Ejecutar_SQL_SP($sSQL);
                }
            }
        }

        // Totalizamos los meses y años pendientes
        $SQLSubTotal = "";
        for ($JE = $AnioIni; $JE <= $AnioFin; $JE++) {
            $SQLSubTotal .= "P_" . strval($JE) . " + ";
        }
        for ($JE = 1; $JE <= 12; $JE++) {
            $SQLSubTotal .= MesesLetras($JE) . " + ";
        }
        for ($JE = 1; $JE <= 12; $JE++) {
            $SQLSubTotal .= "CxC_" . substr(MesesLetras($JE), 0, 3) . " + ";
        }
        $SQLSubTotal .= "PEN ";

        $sSQL = "UPDATE Saldo_Diarios
                    SET Total = " . $SQLSubTotal . " 
                    WHERE Item = '" . $NumEmpresa . "'
                    AND CodigoU = '" . $CodigoUsuario . "'
                    AND TP = 'CXCP' ";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "DELETE * 
                    FROM Saldo_Diarios 
                    WHERE Item = '" . $NumEmpresa . "' 
                    AND CodigoU = '" . $CodigoUsuario . "' 
                    AND Total = 0 
                    AND TP = 'CXCP' ";
        Ejecutar_SQL_SP($sSQL);

        $SQLSubTotal = "";
        for ($JE = $AnioIni; $JE <= $AnioFin; $JE++) {
            $SQLSubTotal .= "SUM(P_" . strval($JE) . ") As TP_" . strval($JE) . ",";
        }
        for ($JE = 1; $JE <= 12; $JE++) {
            $SQLSubTotal .= "SUM(" . MesesLetras($JE) . ") As " . "T" . MesesLetras($JE) . ",";
        }
        for ($JE = 1; $JE <= 12; $JE++) {
            $SQLSubTotal .= "SUM(CxC_" . substr(MesesLetras($JE), 0, 3) . ") As " . "TCxC_" . substr(MesesLetras($JE), 0, 3) . ",";
        }
        $SQLSubTotal .= "SUM(PEN) As TPEN ";

        $sSQL = "SELECT TP, " . $SQLSubTotal . " 
                    FROM Saldo_Diarios 
                    WHERE Item = '" . $NumEmpresa . "'
                    AND CodigoU = '" . $CodigoUsuario . "'
                    AND TP = 'CXCP'
                    GROUP BY TP ";

        $AdoQuery1 = $this->modelo->datos($sSQL);

        if ($AdoQuery1) {
            foreach ($AdoQuery1 as $row) {
                if ($row['TPEN'] != 0) {
                    $VetPEN = true;
                }
                for ($JE = $AnioIni; $JE <= $AnioFin; $JE++) {
                    if ($row["TP_" . $JE] != 0) {
                        $VerAnios[$JE] = true;
                    }
                }
                for ($JE = 1; $JE <= 12; $JE++) {
                    if ($row["T" . MesesLetras($JE)] != 0) {
                        $VerMeses[$JE] = true;
                    }
                }
                for ($JE = 1; $JE <= 12; $JE++) {
                    if ($row["TCxC_" . substr(MesesLetras($JE), 0, 3)] != 0) {
                        $CxC_VerMeses[$JE] = true;
                    }
                }
            }
        }

        // Listado de Rubros de pensiones por meses
        $sSQL = "SELECT C.Cliente,";
        if ($VetPEN)
            $sSQL .= "PEN,";
        for ($IE = $AnioIni; $IE <= $AnioFin; $IE++) {
            if ($VerAnios[$IE])
                $sSQL .= "P_" . strval($IE) . ",";
        }
        for ($IE = 1; $IE <= 12; $IE++) {
            if ($VerMeses[$IE])
                $sSQL .= MesesLetras($IE) . ",";
        }
        for ($IE = 1; $IE <= 12; $IE++) {
            if ($CxC_VerMeses[$IE])
                $sSQL .= "CxC_" . substr(MesesLetras($IE), 0, 3) . ",";
        }
        $sSQL .= "SD.Total,C.Direccion,C.Grupo,C.Plan_Afiliado As BUS_No,SD.Ln As No_
                    FROM Saldo_Diarios As SD,Clientes As C 
                    WHERE SD.Item = '" . $NumEmpresa . "' 
                    AND SD.CodigoU = '" . $CodigoUsuario . "' 
                    AND SD.TP = 'CXCP' 
                    AND SD.CodigoC = C.Codigo 
                    ORDER BY C.Grupo,C.Cliente,SD.TC ";

        $DGQuery = $this->modelo->datos($sSQL, true, $Por_FA);
        $AdoQuery = $DGQuery;

        $label_saldo = number_format($Valor_Total, 2, '.', ',');

        $Opcion = 11;

        return array(
            'DGQuery' => $DGQuery['DGQuery'],
            'AdoQuery' => $DGQuery['AdoQuery'],
            'num_filas' => $DGQuery['num_filas'],
            'Opcion' => $Opcion,
            'label_saldo' => $label_saldo
        );

    }

    function Buscar_x_Patron($parametros, $PorFactura = false, $Opcion_TP = false)
    {
        $DCClienteVal = $parametros['DCCliente'];
        $ListCliente = $parametros['ListCliente'];

        $SQL3X = "";
        $Patron_Busqueda = $DCClienteVal;
        if ($Patron_Busqueda == "")
            $Patron_Busqueda = G_NINGUNO;

        switch ($ListCliente) {
            case "Factura":
                if ($PorFactura)
                    $SQL3X .= "AND F.Factura = " . intval($Patron_Busqueda) . " ";
                break;
            case "Forma_Pago":
                if ($PorFactura)
                    $SQL3X .= "AND F.Forma_Pago = '" . $Patron_Busqueda . "' ";
                break;
            case "Tipo Documento":
                if ($PorFactura) {
                    if ($Opcion_TP) {
                        $SQL3X .= "AND F.TP = '" . $Patron_Busqueda . "' ";
                    } else {
                        $SQL3X .= "AND F.TC = '" . $Patron_Busqueda . "' ";
                    }
                    $TipoFactura = $Patron_Busqueda;
                }
                break;
            case "Codigo":
                $SQL3X .= "AND C.Codigo = '" . $Patron_Busqueda . "' ";
                break;
            case "CI_RUC":
                $SQL3X .= "AND C.CI_RUC = '" . $Patron_Busqueda . "' ";
                break;
            case "Cliente":
                $SQL3X .= "AND UPPER(SUBSTRING(C.Cliente, 1, " . strlen($Patron_Busqueda) . ")) = '" . $Patron_Busqueda . "' ";
                break;
            case "Ciudad":
                $SQL3X .= "AND C.Ciudad = '" . $Patron_Busqueda . "' ";
                break;
            case "Grupo":
                $SQL3X .= "AND C.Grupo = '" . $Patron_Busqueda . "' ";
                break;
            case "Plan_Afiliado":
                $SQL3X .= "AND C.Plan_Afiliado = '" . $Patron_Busqueda . "' ";
                break;
            default:
                $SQL3X .= "AND C.Codigo <> ' ' ";
                break;
        }
        return $SQL3X;
    }

    function Abonos_Facturas($Ret_NC, $parametros)
    {
        //$Opcion = 6;
        $Opcion = $parametros['Opcion'];

        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $CheqCxC = $parametros['CheqCxC'];
        $PorCxC = false;
        if ($CheqCxC == 1) {
            $PorCxC = true;
        }

        $tipoConsulta = $this->Tipo_De_Consulta($parametros, true);

        $sSQL = $this->modelo->Abonos_Facturas($tipoConsulta, $MBFechaI, $MBFechaF, $Ret_NC);

        $Total = 0;
        $label_abonado = number_format($Total, 2, '.', ',');
        $label_facturado = "0.00";
        $label_saldo = "0.00";

        return array(
            'DGQuery' => $sSQL['DGQuery'],
            'AdoQuery' => $sSQL['AdoQuery'],
            'num_filas' => $sSQL['num_filas'],
            'Opcion' => $Opcion,
            'label_facturado' => $label_facturado,
            'label_abonado' => $label_abonado,
            'label_saldo' => $label_saldo,
        );
    }

    function Cheques_Protestados($parametros)
    {
        $Total = 0;

        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];

        $tipoConsulta = $this->Tipo_De_Consulta($parametros);
        $sSQL = $this->modelo->Cheques_Protestados($tipoConsulta, $MBFechaI, $MBFechaF);

        $Opcion = 7;
        //$Opcion = $parametros['Opcion'];
        $totales = $this->Totales_CxC_Abonos($sSQL['AdoQuery'], $Opcion);

        return array(
            'DGQuery' => $sSQL['DGQuery'],
            'AdoQuery' => $sSQL['AdoQuery'],
            'num_filas' => $sSQL['num_filas'],
            'Totales' => $totales,
            'Opcion' => $Opcion
        );
    }

    public function Totales_CxC_Abonos($AdoQuery, $Opcion)
    {
        $Total = 0;
        $Abono = 0;
        $Saldo = 0;
        $label_facturado = 0;
        $label_abonado = 0;
        $label_saldo = 0;
        foreach ($AdoQuery as $record) {
            if ($record['T'] != G_ANULADO) {
                switch ($Opcion) {
                    case 1:
                        $Total += $record['Total'];
                        $Abono += $record['Total_Abonos'];
                        break;
                    case 6:
                    case 7:
                        $Total += $record['Abono'];
                        break;
                    case 9:
                    case 10:
                        $Total += $record['Total_MN'];
                        $Saldo += $record['Saldo_MN'];
                        break;
                }
            }
        }
        switch ($Opcion) {
            case 1:
                $Saldo = $Total - $Abono;
                $label_facturado = $Total;
                $label_abonado = $Abono;
                $label_saldo = $Saldo;
                break;
            case 7:
                $label_facturado = $Total;
                $label_abonado = $Abono;
                $label_saldo = $Saldo;
                break;
            case 9:
            case 10:
                $Abono = $Total - $Saldo;
                $label_facturado = $Total;
                $label_abonado = $Abono;
                $label_saldo = $Saldo;
                break;
        }

        return array(
            'label_facturado' => $label_facturado,
            'label_abonado' => $label_abonado,
            'label_saldo' => $label_saldo
        );

    }

    function Listado_Tarjetas()
    {
        $sSQL = $this->modelo->Listado_Tarjetas();
        return array(
            'DGQuery' => $sSQL['DGQuery'],
            'AdoQuery' => $sSQL['AdoQuery'],
            'num_filas' => $sSQL['num_filas'],
        );
    }

    function Form_Activate()
    {
        $CnExterna = 0;
        $HistorialFacturas = "RESUMEN HISTORICO DE FACTURAS/NOTAS DE VENTA";
        Actualizar_Datos_Representantes_SP($_SESSION['INGRESO']['Mas_Grupos']);

        $OpcBusqueda = [
            "Cliente",
            "CI_RUC",
            "Ciudad",
            "Codigo",
            "Plan_Afiliado",
            "Tipo Documento",
            "Autorizacion",
            "Serie",
            "Factura",
            "Forma_Pago",
            "Cuenta_No",
            "Vendedor",
            "Grupo/Zona",
            "Producto",
            "DescItem",
            "Marca"
        ];

        // Ordenar el array alfabéticamente
        sort($OpcBusqueda);

        $ListCliente = array("Todos");

        foreach ($OpcBusqueda as $opcion) {
            $ListCliente[] = $opcion;
        }

        if (empty($TipoFactura)) {
            $TipoFactura = G_NINGUNO;
        }

        $FA = array(
            "Cliente" => G_NINGUNO,
            "CI_RUC" => G_NINGUNO,
            "Factura" => 0,
            "Cod_Ejec" => G_NINGUNO,
            "CodigoC" => G_NINGUNO,
            "Grupo" => G_NINGUNO,
            "CiudadC" => G_NINGUNO,
            "Autorizacion" => G_NINGUNO,
            "Forma_Pago" => G_NINGUNO,
            "TC" => G_NINGUNO,
            "Serie" => G_NINGUNO,
        );

        $CodigoInv = G_NINGUNO;
        $Cod_Marca = G_NINGUNO;
        $DescItem = G_NINGUNO;

        return array(
            'ListCliente' => $ListCliente,
            'FA' => $FA,
            'CodigoInv' => $CodigoInv,
            'Cod_Marca' => $Cod_Marca,
            'DescItem' => $DescItem
        );
    }

    function ListCliente_LostFocus($ListClienteText)
    {
        return $this->modelo->ListCliente_LostFocus($ListClienteText);
    }

    function ToolbarMenu_ButtonMenuClick($parametros)
    {
        $ButtonMenu = $parametros['idBtnMenu'];
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $ListCliente = $parametros['ListCliente'];
        $idBtnMenu = $parametros['idBtnMenu'];


        FechaValida($MBFechaI);
        FechaValida($MBFechaF);
        $FechaIni = BuscarFecha($MBFechaI);
        $FechaFin = BuscarFecha($MBFechaF);
        $CheqCxC = $parametros['CheqCxC'];

        if ($CheqCxC == 1) {
            $PorCxC = true;
        }

        $Mifecha = $FechaIni;
        $FechaTexto = $FechaFin;
        $FA['Fecha_Corte'] = $MBFechaF;
        $FA['Factura'] = 0;
        $FA['Fecha_Desde'] = $MBFechaI;
        $FA['Fecha_Hasta'] = $MBFechaF;
        //$TMail->Volver_Envial = false;

        if ($ListCliente == "Todos") {
            $FA['TC'] = G_NINGUNO;
            $FA['Serie'] = G_NINGUNO;
            $FA['Factura'] = 0;
        }
        //$Opcion = 0;
        $Opcion = $parametros['Opcion'];
        $label_facturado = 0;
        $label_abonado = 0;
        $label_saldo = 0;

        $retorno = array();

        switch ($idBtnMenu) {
            case "Resumen_Prod":
                $res = $this->Resumen_Productos($parametros, $FechaIni, $FechaFin);
                $label_facturado = $res['label_facturado'];
                $label_abonado = $res['label_abonado'];
                $label_saldo = $res['label_saldo'];
                $Opcion = $res['Opcion'];
                break;
            case "Resumen_Prod_Meses":
                //$Opcion = 16;
                $PorCantidad = $parametros['PorCantidad'];
                $res = $this->modelo->Resumen_Prod_Meses($FechaIni, $FechaFin, $PorCantidad, $MBFechaF);
                $Total = 0;
                $Abono = 0;
                if (count($res['AdoQuery']) > 0) {
                    foreach ($res['AdoQuery'] as $fila) {
                        $Total += $fila["Total"];
                    }
                }
                $label_facturado = number_format($Total, 2, ',', '.');
                $label_abonado = number_format($Abono, 2, ',', '.');
                $label_saldo = number_format($Total - $Abono, 2, ',', '.');
                break;
            case "ResumenVentCost":
                $res = $this->Resumen_Ventas_Costos($FechaIni, $FechaFin, $parametros);
                $Opcion = $res['Opcion'];
                break;
            case "Resumen_Ventas_Vendedor":
                $tipoConsulta = $this->Tipo_De_Consulta($parametros);
                $res = $this->modelo->Resumen_Ventas_Vendedor($FechaIni, $FechaFin, $tipoConsulta);
                $Opcion = $res['Opcion'];
                break;
            case "Ventas_x_Cli":
                $res = $this->Ventas_Cliente($parametros, $FechaIni, $FechaFin);
                $label_facturado = $res['label_facturado'];
                $label_abonado = $res['label_abonado'];
                $label_saldo = $res['label_saldo'];
                $Opcion = $res['Opcion'];
                break;
            case "Ventas_Cli_x_Mes":
                $res = $this->Ventas_Clientes_Por_Meses($parametros, $FechaIni, $FechaFin, $parametros['FA'], $MBFechaF);
                $label_facturado = $res['label_facturado'];
                $label_abonado = $res['label_abonado'];
                $label_saldo = $res['label_saldo'];
                $Opcion = $res['Opcion'];
                break;
            case "VentasxProductos":
                $res = $this->Ventas_Productos($parametros, $FechaIni, $FechaFin);
                $Opcion = $res['Opcion'];
                $label_facturado = $res['label_facturado'];
                $label_abonado = $res['label_abonado'];
                $label_saldo = $res['label_saldo'];
                break;
            case "Ventas_ResumidasxVendedor":
                $res = $this->modelo->Ventas_Resumidas_x_Vendedor($FechaIni, $FechaFin);
                $Total = 0;
                if (count($res['AdoQuery']) > 0) {
                    foreach ($res['AdoQuery'] as $fila) {
                        $Total += $fila["Cantidad"];
                    }
                }
                $label_facturado = number_format($Total, 2, ',', '.');
                $Opcion = $res['Opcion'];
                break;
            case "SMAbonos_Anticipados":
                $res = $this->SMAbonos_Anticipados($FechaIni, $FechaFin, $parametros);
                $label_facturado = $res['label_facturado'];
                $label_abonado = $res['label_abonado'];
                $label_saldo = $res['label_saldo'];
                $Opcion = $res['Opcion'];
                break;
            case "Abonos_Ant":
                //$Opcion = 6;
                $res = $this->modelo->Abonos_Anticipados($FechaIni, $FechaFin);
                $label_facturado = "0.00";
                $label_abonado = "0.00";
                $label_saldo = "0.00";
                break;
            case "Abonos_Erroneos":
                //$Opcion = 6;
                $PorCxC = false;
                $res = $this->modelo->Abonos_Erroneos($FechaIni, $FechaFin);
                $label_facturado = "0.00";
                $label_abonado = "0.00";
                break;
            case "Contra_Cta":
                $res = $this->Contra_Cta_Abonos($parametros, $FechaIni, $FechaFin);
                $label_facturado = $res['label_facturado'];
                $label_abonado = $res['label_abonado'];
                $label_saldo = $res['label_saldo'];
                $Opcion = $res['Opcion'];
                break;
            case "Por_Clientes":
                $res = $this->Tipo_Consulta_CxC("C", $FechaIni, $FechaFin, $parametros);
                // $datos = $res['DGQuery'];
                $Opcion = $res['Opcion'];
                $totales = $this->Totales_CxC_Abonos($res['AdoQuery'], $Opcion);
                $label_facturado = $totales['label_facturado'];
                $label_abonado = $totales['label_abonado'];
                $label_saldo = $totales['label_saldo'];
                break;
            case "Por_Facturas":
                $res = $this->Tipo_Consulta_CxC("F", $FechaIni, $FechaFin, $parametros);
                //   $datos = $res['DGQuery'];
                $Opcion = $res['Opcion'];
                $totales = $this->Totales_CxC_Abonos($res['AdoQuery'], $Opcion);
                $label_facturado = $totales['label_facturado'];
                $label_abonado = $totales['label_abonado'];
                $label_saldo = $totales['label_saldo'];
                break;
            case "Resumen_Cartera":
                $res = $this->Tipo_Consulta_CxC("R", $FechaIni, $FechaFin, $parametros);
                //  $datos = $res['DGQuery'];
                $Opcion = $res['Opcion'];
                $totales = $this->Totales_CxC_Abonos($res['AdoQuery'], $Opcion);
                $label_facturado = $totales['label_facturado'];
                $label_abonado = $totales['label_abonado'];
                $label_saldo = $totales['label_saldo'];
                break;
            case "Por_Vendedor":
                $res = $this->Tipo_Consulta_CxC("V", $FechaIni, $FechaFin, $parametros);
                // $datos = $res['DGQuery'];
                $Opcion = $res['Opcion'];
                $totales = $this->Totales_CxC_Abonos($res['AdoQuery'], $Opcion);
                $label_facturado = $totales['label_facturado'];
                $label_abonado = $totales['label_abonado'];
                $label_saldo = $totales['label_saldo'];
                break;
            case "Resumen_Vent_x_Ejec":
                break;
            case "CxC_Tiempo_Credito":
                $Mifecha = BuscarFecha(FechaSistema());
                $res = $this->modelo->CxC_Tiempo_Credito($Mifecha, $FechaIni, $FechaFin, $parametros['FA']);
                $Total = 0;
                $Saldo = 0;
                if (count($res['AdoQuery']) > 0) {
                    foreach ($res['AdoQuery'] as $fila) {
                        $Total += $fila["Total_Facturado"];
                        $Saldo += $fila["Saldo_Total"];
                    }
                }
                $label_facturado = number_format($Total, 2, ',', '.');
                $label_saldo = number_format($Saldo, 2, ',', '.');
                $Opcion = $res['Opcion'];
                break;
            case "Tipo_Pago_Cliente":
                $res = $this->modelo->Tipo_Pago_Cliente();
                break;
            case "Bajar_Excel":
                if (!empty($parametros['AdoQuery'])) {
                    return $this->Bajar_Excel($parametros['AdoQuery']);
                } else {
                    return array('response' => 0);
                }
            case "Reporte_Ventas":
                $res = $this->modelo->Ventas_x_Excel($FechaIni, $FechaFin);
                break;
            case "Reporte_Catastro":
                $tipoConsulta = $this->Tipo_De_Consulta($parametros);
                return $this->Catastro_Registro_Datos_Clientes($FA, $MBFechaI, $MBFechaF, $tipoConsulta);
            case "Enviar_FA_Email":
                $tipoConsulta = $this->Tipo_De_Consulta($parametros, true);
                $res = $this->modelo->Enviar_Emails_Facturas_Recibos($parametros, $FechaIni, $FechaFin, "FA", $tipoConsulta);
                $retorno['response'] = $res['response'];
                $retorno['tipoEnvio'] = $res['tipoEnvio'];
                break;
            case "Enviar_RE_Email":
                $tipoConsulta = $this->Tipo_De_Consulta($parametros, true);
                $res = $this->modelo->Enviar_Emails_Facturas_Recibos($parametros, $FechaIni, $FechaFin, "RE", $tipoConsulta);
                $retorno['response'] = $res['response'];
                $retorno['tipoEnvio'] = $res['tipoEnvio'];
                break;
            case "Recibos_Anticipados":
                $res = $this->Recibo_Abonos_Anticipados($FechaIni, $FechaFin, $parametros);
                $retorno['response'] = $res['response'];
                $retorno['nombre'] = $res['nombreArchivo'];
                $retorno['Co'] = $res['Co'];
                $res = $this->SMAbonos_Anticipados($FechaIni, $FechaFin, $parametros);
                $label_facturado = $res['label_facturado'];
                $label_abonado = $res['label_abonado'];
                $label_saldo = $res['label_saldo'];
                $Opcion = $res['Opcion'];
                break;
            case "Deuda_x_Mail":
                Actualizar_Abonos_Facturas_SP($FA);
                $res = $this->Historico_Facturas($parametros);
                $resU = $this->Deuda_x_Mail($res['AdoQuery']);
                $retorno['result'] = $resU['result'];
                $retorno['correos_error'] = $resU['correos_error'];
                break;
        }

        $retorno += array(
            'label_facturado' => $label_facturado,
            'label_abonado' => $label_abonado,
            'label_saldo' => $label_saldo,
            'tbl' => $res['DGQuery'],
            'AdoQuery' => $res['AdoQuery'],
            'num_filas' => $res['num_filas'],
            'idBtnMenu' => $idBtnMenu,
            'Opcion' => $Opcion,
        );

        return $retorno;
    }

    function Deuda_x_Mail($AdoQuery)
    {
        $si_envia = false;
        $cad_deuda = "";
        $pos_punto_coma = 0;
        $Total = 0;
        $correos_error = array();
        $contador = 0;

        if (count($AdoQuery) > 0) {
            foreach ($AdoQuery as $record) {
                if ($contador >= 3) {
                    break;
                }
                if (isset($record["Cliente"])) {
                    $TBeneficiario["Cliente"] = $record["Cliente"];
                }
                if (isset($record["Representante"])) {
                    $TBeneficiario["Representante"] = $record["Representante"];
                }
                if (isset($record["Grupo"])) {
                    $TBeneficiario["Grupo_No"] = $record["Grupo"];
                }
                if (isset($record["Email"])) {
                    $TBeneficiario["Email1"] = $record["Email"];
                }
                if (isset($record["Email2"])) {
                    $TBeneficiario["Email2"] = $record["Email2"];
                }
                if (isset($record["EmailR"])) {
                    $TBeneficiario["EmailR"] = $record["EmailR"];
                }

                if (strlen($TBeneficiario["Representante"]) <= 1 && strlen($TBeneficiario["Cliente"]) > 1) {
                    $TBeneficiario["Representante"] = $TBeneficiario["Cliente"];
                }

                $CadDeuda = "TC\tFECHA EMIS\tSERIE\t\tDOCUMENTO\tSALDO ACTUTAL\n";
                $contador++;
                $CodigoP = sprintf("%014s", number_format($record["Saldo_Actual"], 2, ".", ","));
                $fecha_formateada = $record["Fecha"]->format('Y-m-d H:i:s');
                $cad_deuda .= $record["TC"] . "\t" . $fecha_formateada . "\t" . $record["Serie"] . "\t" . sprintf("%09d", $record["Factura"]) . "\t\t" . $CodigoP . "\r\n";
                $Total += $record["Saldo_Actual"];

                $CodigoP = sprintf("%020s", number_format($Total, 2, ".", ","));
                $TMailAsunto = "Envio automatizado de su cartera pendiente por USD " . number_format($Total, 2, ".", ",");
                $TMailMensaje = "Estimado(a): " . $TBeneficiario['Representante'] . ", del Grupo " . $TBeneficiario['Grupo_No'] . ".\nUsted tiene los siguientes pendientes por cancelar:\n" .
                    $cad_deuda .
                    str_repeat("_", 60) . "\n" .
                    "TOTAL PENDIENTE POR CANCELAR" . str_repeat(" ", 26) . "USD\t" . $CodigoP . "\n" .
                    "NOTA: En caso de tener inconformidad con los valores detallados en su Estado de Cuenta, comuniquese con atencion al Cliente.\n";

                if (isset($TFA['Email1']) && $TBeneficiario['Email1'] !== '.') {
                    $TMailPara[] = $TBeneficiario['Email1'];
                }

                if (isset($TFA['Email2']) && $TBeneficiario['Email2'] !== '.') {
                    $TMailPara[] = $TBeneficiario['Email2'];
                }

                if (isset($TFA['EmailR']) && $TBeneficiario['EmailR'] !== '.') {
                    $TMailPara[] = $TBeneficiario['EmailR'];
                }

                $TMailPara = implode(', ', $TMailPara);

                $rsp = $this->email->enviar_email(false, $TMailPara, $TMailMensaje, $TMailAsunto, false);
                if ($rsp == -1) {
                    $correos_error[] = $TMailPara;
                }
            }
        }

        if (count($correos_error) > 0) {
            return ['result' => -1, 'correos_error' => $correos_error];
        } else {
            return ['result' => 1, 'correos_error' => $correos_error];
        }
    }

    function Recibo_Abonos_Anticipados($FechaIni, $FechaFin, $parametros)
    {
        $Co = $parametros['Co'];
        $tipoConsulta = $this->Tipo_De_Consulta($parametros);
        $AdoFacturas = $this->modelo->Recibo_Abonos_Anticipados($FechaIni, $FechaFin, $Co, $tipoConsulta);

        if (count($AdoFacturas['AdoQuery']) > 0) {
            foreach ($AdoFacturas['AdoQuery'] as $fila) {
                $Co["Beneficiario"] = $fila["Cliente"];
                $Co["RUC_CI"] = $fila["CI_RUC"];
                $Co["Concepto"] = $fila["Concepto"];
                $Co["Efectivo"] = $fila["Abono"];
                $Co["Email"] = "";
                if (strlen($fila["Email"]) > 3) {
                    $Co["Email"] = $fila["Email"];
                }
                if ($Co["Email"] == "" && strlen($fila["Email2"]) > 3) {
                    $Co["Email"] = $fila["Email2"];
                } elseif (strlen($fila["Email2"]) > 3 && $fila["Email"] != $fila["Email2"]) {
                    $Co["Email"] = $Co["Email"] . ";" . $fila["Email2"];
                }

                $NombreArchivo = "Recibo_No_" . $Co["TP"] . "-" . sprintf("%09d", $Co["Numero"]);
                $RutaDocumentoPDF = $this->RutaDocumentoPDF($NombreArchivo, $Co);

                return ['response' => 3, 'nombreArchivo' => $RutaDocumentoPDF, 'Co' => $Co];
            }
        }
    }

    function EnviarMailAbono($parametros)
    {
        $Co = $parametros['Co'];

        if (strlen($Co["Email"]) > 3) {
            if ($parametros['SiEnviar'] == true) {
                $ano = substr($Co['Fecha'], 0, 4);

                if (isset($TFA['Email']) && $Co["Email"] !== '.') {
                    $TMailPara[] = $Co["Email"];
                }

                $TMailPara = implode(', ', $TMailPara);
                $TMailAsunto = "RECIBO ABONO ANTICIPADO No. " . $ano . "-" . $Co["TP"] . "-" . sprintf("%09d", $Co["Numero"]);
                $TMailAdjunto[] = $parametros['archivo'];
                $TMailMensaje = "Beneficiario: " . $Co["Beneficiario"] . "\n" .
                    "Fecha del Abono: " . $Co["Fecha"] . "\n" .
                    "Abono Anticipado por USD " . number_format($Co["Efectivo"], 2);

                $rsp = $this->email->enviar_email($TMailAdjunto, $TMailPara, $TMailMensaje, $TMailAsunto, false);
                return $rsp;
            }
        }

    }
    function Catastro_Registro_Datos_Clientes($FA, $MBFechaI, $MBFechaF, $tipoConsulta)
    {
        $fechaSistema = FechaSistema();
        $fechaSistema = date("d/m/Y", strtotime($fechaSistema));

        $NFila = 0;
        $RutaGeneraFile = '';
        $Dias_Morosidad = 0;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $path = dirname(__DIR__, 3) . "/TEMP/EXCEL/";
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $filename = "Catastro Cliente del ";

        if ($MBFechaI == $MBFechaF) {
            $filename .= str_replace("/", "-", $MBFechaF) . ".xlsx";
        } else {
            $filename .= str_replace("/", "-", $MBFechaI) . " al " . str_replace("/", "-", $MBFechaF) . ".xlsx";
        }

        $filename = str_replace(' ', '_', $filename);
        $RutaGeneraFile = $path . $filename;

        $FA['Fecha_Corte'] = $MBFechaF;
        Actualizar_Abonos_Facturas_SP($FA);

        $sheet->setCellValue('A1', 'CATASTRO REGISTRO DE DATOS CREDITICIOS');
        $sheet->setCellValue('A2', 'DATOS DEL CLIENTE');
        $sheet->setCellValue('G2', 'CAMPOS SOCIODEMOGRÁFICOS');
        $sheet->setCellValue('M2', 'DATOS DE ENDEUDAMIENTO');
        $sheet->setCellValue('A3', 'Código de la Entidad');
        $sheet->setCellValue('B3', 'Fecha de Datos');
        $sheet->setCellValue('C3', 'Tipo de Identificación del Sujeto');
        $sheet->setCellValue('D3', 'Identificación del Sujeto');
        $sheet->setCellValue('E3', 'Nombres y Apellidos del Sujeto');
        $sheet->setCellValue('F3', 'Clase del Sujeto');
        $sheet->setCellValue('G3', 'Provincia');
        $sheet->setCellValue('H3', 'Cantón');
        $sheet->setCellValue('I3', 'Parroquia');
        $sheet->setCellValue('J3', 'Sexo');
        $sheet->setCellValue('K3', 'Estado Civil');
        $sheet->setCellValue('L3', 'Origen de Ingresos');
        $sheet->setCellValue('M3', 'Número de Operación');
        $sheet->setCellValue('N3', 'Valor de la Operación');
        $sheet->setCellValue('O3', 'Saldo de Operación');
        $sheet->setCellValue('P3', 'Fecha de Concesión');
        $sheet->setCellValue('Q3', 'Fecha de Vencimiento');
        $sheet->setCellValue('R3', 'Fecha que es Exigible');
        $sheet->setCellValue('S3', 'Plazo Operación (días)');
        $sheet->setCellValue('T3', 'Periodicidad de Pago (días)');
        $sheet->setCellValue('U3', 'Días de Morosidad');
        $sheet->setCellValue('V3', 'Monto de Morosidad');
        $sheet->setCellValue('W3', 'Monto de Interés en Mora');
        $sheet->setCellValue('X3', 'Valor por vencer de 1 a 30 días');
        $sheet->setCellValue('Y3', 'Valor por vencer de 31 a 90 días');
        $sheet->setCellValue('Z3', 'Valor por vencer de 91 a 180 días');
        $sheet->setCellValue('AA3', 'Valor por vencer de 181 a 360 días');
        $sheet->setCellValue('AB3', 'Valor por vencer de mas 360 días');
        $sheet->setCellValue('AC3', 'Valor vencido 1 A 30 dias');
        $sheet->setCellValue('AD3', 'Valor vencido de 31 a 90 días');
        $sheet->setCellValue('AE3', 'Valor vencido de 91 a 180 días');
        $sheet->setCellValue('AF3', 'Valor vencido de 181 a 360 días');
        $sheet->setCellValue('AG3', 'Valor vencido de más de 360 días');
        $sheet->setCellValue('AH3', 'Valor en Demanda Judicial');
        $sheet->setCellValue('AI3', 'Cartera Castigada');
        $sheet->setCellValue('AJ3', 'Cuota del Crédito');
        $sheet->setCellValue('AK3', 'Fecha de Cancelación');
        $sheet->setCellValue('AL3', 'Forma de Cancelación');

        $AdoCatastro = $this->modelo->Catastro_Registro_Datos_Clientes(BuscarFecha($MBFechaI), BuscarFecha($MBFechaF), $tipoConsulta);

        if (!empty($AdoCatastro)) {
            foreach ($AdoCatastro as $registro) {
                $Dias_Morosidad = 0;
                // Calcular días de morosidad
                if ($registro["T"] != "C")
                    $Dias_Morosidad = CFechaLong(date('Y-m-d')) - CFechaLong($registro["Fecha_V"]->format('Y-m-d'));

                // Escribir datos en la hoja de cálculo
                $sheet->setCellValue("A" . $NFila, "SP10101");
                $sheet->setCellValue("B" . $NFila, $MBFechaF);
                $sheet->setCellValue("C" . $NFila, $registro["TD"]);
                $sheet->setCellValue("A" . $NFila, "SP10101");
                $sheet->setCellValue("B" . $NFila, $MBFechaF);
                $sheet->setCellValue("C" . $NFila, $registro["TD"]);
                $sheet->setCellValue("D" . $NFila, $registro["CI_RUC"]);
                $sheet->setCellValue("E" . $NFila, $registro["Cliente"]);
                $sheet->setCellValue("F" . $NFila, (substr($registro["CI_RUC"], 2, 1) == "9") ? "J" : "N");
                $sheet->setCellValue("G" . $NFila, $registro["Prov"]);
                $sheet->setCellValue("H" . $NFila, "C" . $registro["Ciudad"]);
                $sheet->setCellValue("I" . $NFila, "P" . $registro["Ciudad"]);
                $sheet->setCellValue("J" . $NFila, $registro["Sexo"]);
                $sheet->setCellValue("K" . $NFila, $registro["Est_Civil"]);
                $sheet->setCellValue("L" . $NFila, "I");
                $sheet->setCellValue("M" . $NFila, "'" . $registro["Serie"] . sprintf("%09d", $registro["Factura"]));
                $sheet->setCellValue("N" . $NFila, number_format($registro["Total"], 2, ".", ""));
                $sheet->setCellValue("O" . $NFila, number_format($registro["Saldo_Actual"], 2, ".", ""));
                $sheet->setCellValue("P" . $NFila, $registro["Fecha"]);
                $sheet->setCellValue("Q" . $NFila, $registro["Fecha_V"]);
                $sheet->setCellValue("R" . $NFila, $registro["Fecha_V"]);
                $sheet->setCellValue("S" . $NFila, " ");
                $sheet->setCellValue("T" . $NFila, " ");
                if ($Dias_Morosidad > 0) {
                    $sheet->setCellValue("U" . $NFila, $Dias_Morosidad);
                }
                if ($registro["T"] != "C") {
                    $sheet->setCellValue("V" . $NFila, number_format($registro["Saldo_Actual"], 2, ".", ""));
                }
                $sheet->setCellValue("W" . $NFila, " ");
                if (-30 <= $Dias_Morosidad && $Dias_Morosidad <= -1) {
                    $sheet->setCellValue("X" . $NFila, number_format($registro["Total"], 2, ".", ""));
                }
                if (-90 <= $Dias_Morosidad && $Dias_Morosidad <= -31) {
                    $sheet->setCellValue("Y" . $NFila, number_format($registro["Total"], 2, ".", ""));
                }
                if (-180 <= $Dias_Morosidad && $Dias_Morosidad <= -91) {
                    $sheet->setCellValue("Z" . $NFila, number_format($registro["Total"], 2, ".", ""));
                }
                if (-360 <= $Dias_Morosidad && $Dias_Morosidad <= -181) {
                    $sheet->setCellValue("AA" . $NFila, number_format($registro["Total"], 2, ".", ""));
                }
                if (-360 <= $Dias_Morosidad) {
                    $sheet->setCellValue("AB" . $NFila, number_format($registro["Total"], 2, ".", ""));
                }
                if (1 <= $Dias_Morosidad && $Dias_Morosidad <= 30) {
                    $sheet->setCellValue("AC" . $NFila, number_format($registro["Saldo_Actual"], 2, ".", ""));
                }
                if (31 <= $Dias_Morosidad && $Dias_Morosidad <= 90) {
                    $sheet->setCellValue("AD" . $NFila, number_format($registro["Saldo_Actual"], 2, ".", ""));
                }
                if (91 <= $Dias_Morosidad && $Dias_Morosidad <= 180) {
                    $sheet->setCellValue("AE" . $NFila, number_format($registro["Saldo_Actual"], 2, ".", ""));
                }
                if (181 <= $Dias_Morosidad && $Dias_Morosidad <= 360) {
                    $sheet->setCellValue("AF" . $NFila, number_format($registro["Saldo_Actual"], 2, ".", ""));
                }
                if ($Dias_Morosidad > 360) {
                    $sheet->setCellValue("AG" . $NFila, number_format($registro["Saldo_Actual"], 2, ".", ""));
                }
                $sheet->setCellValue("AH" . $NFila, " ");
                $sheet->setCellValue("AI" . $NFila, " ");
                $sheet->setCellValue("AJ" . $NFila, " ");
                if ($registro["T"] == "C") {
                    $sheet->setCellValue("AK" . $NFila, $registro["Fecha_V"]);
                    if ($registro["Total_Efectivo"] > 0) {
                        $sheet->setCellValue("AL" . $NFila, "E");
                    } elseif ($registro["Total_Banco"] > 0) {
                        $sheet->setCellValue("AL" . $NFila, "C");
                    } else {
                        $sheet->setCellValue("AL" . $NFila, "T");
                    }
                }

                $NFila++;
            }
            // Guardar archivo Excel
            $writer = new Xlsx($spreadsheet);
            $writer->save($RutaGeneraFile);

            return [
                'response' => 2,
                'nombre' => $filename,
            ];
        } else {
            return ['response' => 0];
        }
    }

    function Bajar_Excel($AdoQuery)
    {
        if (count($AdoQuery) > 0) {
            $path = dirname(__DIR__, 3) . "/TEMP/HISTORICO/";
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $filename = 'Excel_' . date('Ymd_His') . '.xlsx';
            $filePath = $path . $filename;
            Exportar_AdoDB_Excel($AdoQuery, $filePath, "DiskCover System");
            return array('response' => 1, 'nombre' => $filename, 'mensaje' => "SE GENERO EL SIGUIENTE ARCHIVO: \n" . $filename);
        }
    }

    function Tipo_Consulta_CxC($Tipo, $FechaIni, $FechaFin, $parametros)
    {
        $Actualiza_Buses = Leer_Campo_Empresa("Actualizar_Buses");
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $FA = $parametros['FA'];
        $FA['Fecha_Corte'] = $MBFechaF;
        $FA['Fecha_Desde'] = $MBFechaI;
        $FA['Fecha_Hasta'] = $MBFechaF;
        Actualizar_Abonos_Facturas_SP($FA);
        $FechaIni = BuscarFecha($MBFechaI);
        $FechaFin = BuscarFecha($MBFechaF);
        $tipoConsulta = $this->Tipo_De_Consulta($parametros);
        $TipoFactura = $parametros['TipoFactura'];
        $sSQL = $this->modelo->Tipo_Consulta_CxC($Tipo, $tipoConsulta, $FechaIni, $FechaFin, $Actualiza_Buses, $TipoFactura, $MBFechaF);

        $Total = 0;
        $Saldo = 0;
        if (count($sSQL['AdoQuery']) > 0) {
            foreach ($sSQL["AdoQuery"] as $record) {
                $Total += $record["Total_MN"];
                $Saldo += $record["Saldo_MN"];
            }
        }
        $TipoDoc = $Tipo;
        $label_facturado = number_format($Total, 2, '.', ',');
        $label_abonado = number_format($Total - $Saldo, 2, '.', ',');
        $label_saldo = number_format($Saldo, 2, '.', ',');

        return array(
            'DGQuery' => $sSQL['DGQuery'],
            'AdoQuery' => $sSQL['AdoQuery'],
            'num_filas' => $sSQL['num_filas'],
            'Opcion' => $sSQL['Opcion'],
            'label_facturado' => $label_facturado,
            'label_abonado' => $label_abonado,
            'label_saldo' => $label_saldo
        );
    }

    function Contra_Cta_Abonos($parametros, $FechaIni, $FechaFin)
    {
        $CheqAbonos = $parametros['CheqAbonos'];
        $DCCxC = $parametros['DCCxC'];

        //$Opcion = 21;
        $Opcion = $parametros['Opcion'];

        $sSQL = $this->modelo->Contra_Cta_Abonos($FechaIni, $FechaFin, $CheqAbonos, $DCCxC);
        $Total = 0;
        $Abono = 0;
        if (count($sSQL['AdoQuery']) > 0) {
            foreach ($sSQL["AdoQuery"] as $record) {
                $Total += $record["Debitos"];
                $Abono += $record["Creditos"];
            }
        }

        $label_facturado = number_format($Total, 2, '.', ',');
        $label_abonado = number_format($Abono, 2, '.', ',');
        $label_saldo = "0.00";

        return array(
            'DGQuery' => $sSQL['DGQuery'],
            'AdoQuery' => $sSQL['AdoQuery'],
            'num_filas' => $sSQL['num_filas'],
            'Opcion' => $Opcion,
            'label_facturado' => $label_facturado,
            'label_abonado' => $label_abonado,
            'label_saldo' => $label_saldo
        );

    }
    function SMAbonos_Anticipados($FechaIni, $FechaFin, $parametros)
    {
        //$Opcion = 20;
        $Opcion = $parametros['Opcion'];

        $SubCtaGen = Leer_Seteos_Ctas("Cta_Anticipos_Clientes");
        $tipoConsulta = $this->Tipo_De_Consulta($parametros);
        $sSQL = $this->modelo->SMAbonos_Anticipados($FechaIni, $FechaFin, $tipoConsulta);
        $Total = 0;
        $Abono = 0;

        if (count($sSQL['AdoQuery']) > 0) {
            foreach ($sSQL["AdoQuery"] as $record) {
                $Abono += $record["Abono"];
            }
        }

        $label_facturado = number_format($Total, 2, '.', ',');
        $label_abonado = number_format($Abono, 2, '.', ',');
        $label_saldo = "0.00";

        return array(
            'DGQuery' => $sSQL['DGQuery'],
            'AdoQuery' => $sSQL['AdoQuery'],
            'num_filas' => $sSQL['num_filas'],
            'Opcion' => $Opcion,
            'label_facturado' => $label_facturado,
            'label_abonado' => $label_abonado,
            'label_saldo' => $label_saldo
        );

    }

    function Ventas_Clientes_Por_Meses($parametros, $FechaIni, $FechaFin, $FA, $MBFechaF)
    {
        //$Opcion = 14;
        $Opcion = $parametros['Opcion'];

        $sSQL = $this->modelo->Ventas_Clientes_Por_Meses($FechaIni, $FechaFin, $FA, $MBFechaF);
        $Total = 0;
        $Abono = 0;
        if (count($sSQL['AdoQuery']) > 0) {
            foreach ($sSQL['AdoQuery'] as $fila) {
                $Total += $fila["Total"];
            }
        }
        $label_facturado = number_format($Total, 2, '.', ',');
        $label_abonado = number_format($Abono, 2, '.', ',');
        $label_saldo = number_format($Total - $Abono, 2, '.', ',');

        return array(
            'DGQuery' => $sSQL['DGQuery'],
            'AdoQuery' => $sSQL['AdoQuery'],
            'num_filas' => $sSQL['num_filas'],
            'Opcion' => $Opcion,
            'label_facturado' => $label_facturado,
            'label_abonado' => $label_abonado,
            'label_saldo' => $label_saldo
        );
    }

    function Ventas_Cliente($parametros, $FechaIni, $FechaFin)
    {
        //$Opcion = 4
        $Opcion = $parametros['Opcion'];
        $tipoConsulta = $this->Tipo_De_Consulta($parametros, true);
        $sSQL = $this->modelo->Ventas_Cliente($FechaIni, $FechaFin, $tipoConsulta);
        $Total = 0;
        $Abono = 0;
        if (count($sSQL['AdoQuery']) > 0) {
            foreach ($sSQL['AdoQuery'] as $fila) {
                $Total += $fila["Ventas"];
                $Abono += $fila["I_V_A"];
            }
        }
        $label_facturado = number_format($Total, 2, '.', ',');
        $label_abonado = number_format($Abono, 2, '.', ',');
        $label_saldo = number_format($Total - $Abono, 2, '.', ',');

        return array(
            'DGQuery' => $sSQL['DGQuery'],
            'AdoQuery' => $sSQL['AdoQuery'],
            'num_filas' => $sSQL['num_filas'],
            'Opcion' => $Opcion,
            'label_facturado' => $label_facturado,
            'label_abonado' => $label_abonado,
            'label_saldo' => $label_saldo
        );
    }

    function Resumen_Ventas_Costos($FechaIni, $FechaFin, $parametros)
    {
        //$Opcion = 5;
        $Opcion = $parametros['Opcion'];

        $Con_Costeo = $parametros['Con_Costeo'];
        $Si_No = $parametros['Si_No'];
        $DescItem = $parametros['DescItem'];

        $tipoConsulta = $this->Tipo_De_Consulta($parametros, true);
        $sSQL = $this->modelo->Resumen_Ventas_Costos($FechaIni, $FechaFin, $Con_Costeo, $Si_No, $DescItem, $tipoConsulta);

        return array(
            'DGQuery' => $sSQL['DGQuery'],
            'AdoQuery' => $sSQL['AdoQuery'],
            'num_filas' => $sSQL['num_filas'],
            'Opcion' => $Opcion
        );
    }

    function Resumen_Productos($parametros, $FechaIni, $FechaFin)
    {
        //$Opcion = 3;
        $Opcion = $parametros['Opcion'];

        $tipoConsulta = $this->Tipo_De_Consulta($parametros);
        $sSQL = $this->modelo->Resumen_Productos($tipoConsulta, $FechaIni, $FechaFin);
        $Total = 0;
        $Abono = 0;

        $label_facturado = number_format($Total, 2, '.', ',');
        $label_abonado = number_format($Abono, 2, '.', ',');
        $label_saldo = number_format($Total - $Abono, 2, '.', ',');

        return array(
            'DGQuery' => $sSQL['DGQuery'],
            'AdoQuery' => $sSQL['AdoQuery'],
            'num_filas' => $sSQL['num_filas'],
            'Opcion' => $Opcion,
            'label_facturado' => $label_facturado,
            'label_abonado' => $label_abonado,
            'label_saldo' => $label_saldo,
        );
    }

    function Tipo_De_Consulta($paramAdd, $Opcion_TP = false, $Opcion_Email = false, $Opcion_DF = false)
    {
        $ListCliente = $paramAdd['ListCliente'];
        $DCCliente = $paramAdd['DCCliente'];
        $DCCxC = $paramAdd['DCCxC'];
        $OpcPend = $paramAdd['OpcPend'];
        $OpcAnul = $paramAdd['OpcAnul'];
        $OpcCanc = $paramAdd['OpcCanc'];
        $Opcion = $paramAdd['Opcion'];
        $CheqCxC = $paramAdd['CheqCxC'];
        $CheqIngreso = $paramAdd['CheqIngreso'];
        $CheqAbonos = $paramAdd['CheqAbonos'];
        $DescItem = $paramAdd['DescItem'];
        $Cod_Marca = $paramAdd['Cod_Marca'];

        $SQL3X = '';
        $Patron_Busqueda = $DCCliente;

        if ($Patron_Busqueda == '') {
            $Patron_Busqueda = G_NINGUNO;
        }

        $Cta_Cobrar = trim(SinEspaciosIzq($DCCxC));

        if ($OpcPend) {
            if ($Opcion > 0) {
                switch ($Opcion) {
                    case 1:
                        $SQL3X .= "AND F.Saldo_Actual <> 0 AND F.T <> 'A' ";
                        break;
                    case 2:
                        $SQL3X .= "AND F.T = 'P' ";
                        break;
                    case 9:
                    case 10:
                    case 13:
                    case 14:
                        $SQL3X .= "AND F.Saldo_MN <> 0 ";
                        break;
                }
            } else {
                $SQL3X .= "AND F.T = '" . G_PENDIENTE . "' ";
            }
        } elseif ($OpcCanc) {
            $SQL3X .= "AND F.T = '" . G_CANCELADO . "' ";

        } elseif ($OpcAnul) {
            $SQL3X .= "AND F.T = '" . G_ANULADO . "' ";

        }

        switch ($ListCliente) {
            case "Codigo":
                $SQL3X .= "AND C.Codigo = '$Patron_Busqueda' ";
                break;
            case "Grupo/Zona":
                $SQL3X .= "AND C.Grupo = '$Patron_Busqueda' ";
                break;
            case "CI_RUC":
                $SQL3X .= "AND C.CI_RUC = '$Patron_Busqueda' ";
                break;
            case "Cliente":
                $LongStrg = strlen($Patron_Busqueda);
                $SQL3X .= "AND C.Cliente LIKE '$Patron_Busqueda%' ";
                break;
            case "Vendedor":
                $LongStrg = strlen($Patron_Busqueda);
                $SQL3X .= "AND A.Nombre_Completo LIKE '$Patron_Busqueda%' ";
                break;
            case "Ciudad":
                $SQL3X .= "AND C.Ciudad = '$Patron_Busqueda' ";
                break;
            case "Factura":
                $SQL3X .= "AND F.Factura = " . intval($Patron_Busqueda) . " ";
                break;
            case "Serie":
                $SQL3X .= "AND F.Serie = '$Patron_Busqueda' ";
                break;
            case "Autorizacion":
                $SQL3X .= "AND F.Autorizacion = '$Patron_Busqueda' ";
                break;
            case "Forma_Pago":
                $SQL3X .= "AND F.Forma_Pago = '$Patron_Busqueda' ";
                break;
            case "Plan_Afiliado":
                $SQL3X .= "AND C.Plan_Afiliado = '$Patron_Busqueda' ";
                break;
            case "Tipo Documento":
                if ($Opcion_TP) {
                    $SQL3X .= "AND F.TP = '$Patron_Busqueda' ";
                } else {
                    $SQL3X .= "AND F.TC = '$Patron_Busqueda' ";
                }
                $TipoFactura = $Patron_Busqueda;
                break;
        }

        if ($DescItem != G_NINGUNO) {
            //$SQL3X .= "AND MidStrg(F.Codigo,1," . strlen($Codigo) . ") = '$Codigo' ";
        }
        if ($Cod_Marca != G_NINGUNO) {
            $SQL3X .= "AND F.CodMarca = '$Cod_Marca' ";
        }
        if ($CheqCxC) {
            $SQL3X .= "AND F.Cta_CxP = '$Cta_Cobrar' ";
        }
        if ($CheqIngreso && $Opcion_DF) {
            $SQL3X .= "AND F.Cta_Venta = '$Cta_Cobrar' ";
        }
        if ($CheqAbonos) {
            if ($Opcion_Email) {
                $SQL3X .= "AND TA.Cta = '$Cta_Cobrar' ";
            } else {
                $SQL3X .= "AND F.Cta = '$Cta_Cobrar' ";
            }
        }

        return $SQL3X;
    }

    function DCCliente_LostFocus($parametros)
    {
        $FA = $parametros['FA'];
        $DCClienteVal = $parametros['DCClienteVal'];
        $ListClienteVal = $parametros['ListClienteVal'];
        $DCCliente = $parametros['DCCliente'];
        $AdoCliente = $DCCliente;

        $FA['Cod_Ejec'] = G_NINGUNO;
        $FA['CodigoC'] = G_NINGUNO;
        $FA['Cliente'] = G_NINGUNO;
        $FA['CI_RUC'] = G_NINGUNO;
        $FA['Grupo'] = G_NINGUNO;
        $FA['CiudadC'] = G_NINGUNO;
        $FA['Autorizacion'] = G_NINGUNO;
        $FA['Forma_Pago'] = G_NINGUNO;
        $FA['TC'] = G_NINGUNO;
        $FA['Serie'] = G_NINGUNO;
        $FA['Factura'] = 0;
        $CodigoInv = G_NINGUNO;
        $Cod_Marca = G_NINGUNO;
        $DescItem = G_NINGUNO;

        foreach ($AdoCliente as $cliente) {
            switch ($ListClienteVal) {
                case "Codigo":
                    $FA['CodigoC'] = $DCClienteVal;
                    break;
                case "CI_RUC":
                    if ($cliente['CI_RUC'] == $DCClienteVal) {
                        $FA['CodigoC'] = $cliente['Codigo'];
                        $FA['Cliente'] = $cliente['Cliente'];
                        $FA['CI_RUC'] = $cliente['CI_RUC'];
                    }
                    break;
                case "Ciudad":
                    $FA['CiudadC'] = $DCClienteVal;
                    break;
                case "Cliente":
                    if ($cliente['Cliente'] == $DCClienteVal) {
                        $FA['CodigoC'] = $cliente['CodigoC'];
                        $FA['Cliente'] = $cliente['Cliente'];
                    }
                    break;
                case "Vendedor":
                    if ($cliente['Cliente'] == $DCClienteVal) {
                        $FA['Cod_Ejec'] = $cliente['Codigo'];
                    }
                    break;
                case "Grupo":
                    $FA['Grupo'] = $DCClienteVal;
                    break;
                case "Factura":
                    $FA['TC'] = SinEspaciosIzq($DCClienteVal);
                    $FA['Serie'] = MidStrg($DCClienteVal, 4, 6);
                    $FA['Factura'] = intval(SinEspaciosDer($DCClienteVal));
                    break;
                case "Serie":
                    $FA['Serie'] = $DCClienteVal;
                    break;
                case "Autorizacion":
                    $FA['Autorizacion'] = $DCClienteVal;
                    break;
                case "Forma_Pago":
                    $FA['Forma_Pago'] = $DCClienteVal;
                    break;
                case "Plan_Afiliado":
                    //
                    break;
                case "Tipo Documento":
                    $FA['TC'] = $DCClienteVal;
                    break;
                case "Marca":
                    $DescItem = SinEspaciosIzq($DCClienteVal);
                    break;
                case "DescItem":
                    $Cod_Marca = $DCClienteVal;
                    break;
                case "Producto":
                    $CodigoInv = trim(SinEspaciosIzq($DCClienteVal));
                    $Producto = trim(substr($DCClienteVal, strlen($CodigoInv) + 1));
                    break;
            }
        }
        return array('Cod_Marca' => $Cod_Marca, 'DescItem' => $DescItem, 'CodigoInv' => $CodigoInv, 'FA' => $FA);
    }
}

?>