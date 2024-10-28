<?php
include(dirname(__DIR__, 2) . '/modelo/contabilidad/FAbonosAnticipadoM.php');
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");
require_once(dirname(__DIR__,3).'/lib/phpmailer/enviar_emails.php');

$controlador = new FAbonoAnticipadoC();

if (isset($_GET['DCClientes'])) {
    $grupo = G_NINGUNO;
    //if (isset($_GET['grupo']) != '') {
    if (isset($_GET['grupo']) && $_GET['grupo'] != '') {
        $grupo = $_GET['grupo'];
    }
    echo json_encode($controlador->DCCliente($grupo));
}

if (isset($_GET['DCBanco'])) {
    echo json_encode($controlador->DCBanco());
}

if (isset($_GET['DCCtaAnt'])) {
    echo json_encode($controlador->DCCtaAnt());
}

if (isset($_GET['AdoIngCaja_Asiento_SC'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->AdoIngCaja_Asiento_SC($parametros));
}

if (isset($_GET['AdoIngCaja_Asiento'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->AdoIngCaja_Asiento($parametros));
}

if (isset($_GET['AdoIngCaja_Catalogo_CxCxP'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->AdoIngCaja_Catalogo_CxCxP($parametros['codigo_cliente'], $parametros['sub_cta_gen']));
}
if (isset($_GET['ReadSetDataNum'])) {
    $SQLs = $_POST['SQLs'];
    $ParaEmpresa = $_POST['ParaEmpresa'];
    $Incrementar = $_POST['Incrementar'];
    echo json_encode($controlador->ReadSetDataNum($SQLs, $ParaEmpresa, $Incrementar));
}

if (isset($_GET['DCTipo'])) {
    $faFactura = $_POST['fafactura'];
    echo json_encode($controlador->DCTipo($faFactura));
}

if (isset($_GET['DCFactura'])) {
    $TipoFactura = $_POST['TipoFactura'];
    $faFactura = $_POST['fafactura'];
    echo json_encode($controlador->DCFactura($TipoFactura, $faFactura));
}

if (isset($_GET['GrabarComprobante'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->GrabarComprobante($parametros));
}

if (isset($_GET['EnviarEmail'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->EnviarEmail($parametros));
}

if (isset($_GET['EnviarEmailAccept'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->EnviarEmailAccept($parametros));
}

class FAbonoAnticipadoC
{
    private $modelo;
    private $email;

    function __construct()
    {
        $this->modelo = new FAbonosAnticipadoM();
        $this->email = new enviar_emails();
    }

    /*
    Conexión del controlador con el modelo para la consulta del select con id "DCCliente"
    -Se implemento la variable "status" cuando no hay datos para el manejo en la vista
    */
    function DCCliente($grupo)
    {
        $datos = $this->modelo->SelectDB_Combo_DCClientes($grupo);
        $list = array();
        if (count($datos) > 0) { //Caso cuando encuentre datos, se añadio el "status" para manejar los datos en la vista
            foreach ($datos as $key => $value) {
                $list[] = array(
                    'Codigo' => $value['Codigo'],
                    'Grupo' => $value['Grupo'],
                    'Cliente' => $value['Cliente'],
                    'Email' => $value['Email'],
                    'Email2' => $value['Email2']
                );
            }
            return $list;
        }
        return $list; //Caso de que no encuentre datos
    }

    /*
    Conexión del controlador con el modelo para la consulta del select con id"DCBanco"
    -Se implemento la variable "status" cuando no hay datos para el manejo en la vista
    */
    function DCBanco()
    {
        $datos = $this->modelo->SelectDB_Combo_DCBanco();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $key => $value) {
                $list[] = array('NomCuenta' => $value['NomCuenta']);
            }
            return $list;
        }
        return $list; //Caso de que no encuentre datos
    }

    /*
    Conexión del controlador con el modelo para la consulta del select con id "DCCtaAnt"
    -Se implemento la variable "status" cuando no hay datos para el manejo en la vista
    */
    function DCCtaAnt()
    {
        $SubCtaGen = Leer_Seteos_Ctas('Cta_Anticipos_Clientes');
        $datos = $this->modelo->SelectDB_Combo_DCCtaAnt();
        //print_r($datos);die();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $key => $value) {
                //If Not .EOF Then DCCtaAnt.Text = .fields("NomCuenta")
                /*if (strpos($value['NomCuenta'], $SubCtaGen) !== false) {
                }*/
                $list[] = array('NomCuenta' => $value['NomCuenta']);
            }
            return $list;
        }
        return $list; //Caso de que no encuentre datos
    }


    /*
    Metodos que se utilizan al momento de grabar algun abono anticipado
    */
    function AdoIngCaja_Asiento_SC($parametros)
    {
        $res = $this->modelo->Select_Adodc_AdoIngCaja_Asiento_SC($parametros);
        if ($res == 1) {
            Eliminar_Nulos_SP("Asiento_SC");
        }
        return $res; //retorna 1 o -1
    }

    function AdoIngCaja_Asiento($parametros)
    {
        $res = $this->modelo->InsertarAsientos(
            $parametros['CodCta'],
            $parametros['Parcial_MEs'],
            $parametros['Debes'],
            $parametros['Habers'],
            $parametros['CodigoCli']
        );
        if ($res == 1) {
            Eliminar_Nulos_SP('Asiento');
        }
        return $res;
    }

    function AdoIngCaja_Catalogo_CxCxP($codigo_cliente, $sub_cta_gen)
    {
        $datos = $this->modelo->Select_Adodc_AdoIngCaja_Catalogo_CxCxp($codigo_cliente, $sub_cta_gen);
        if ($datos == 1) {
            return 1;
        }
        return $datos; //Caso de que no encuentre datos
    }

    function DCTipo($fa_factura)
    {
        $datos = $this->modelo->SelectDB_Combo_DCTipo($fa_factura);
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $key => $value) {
                $list[] = array('TC' => $value['TC']);
            }
            return $list;
        }
        return $list; //Caso de que no encuentre datos
    }

    function ReadSetDataNum($SQLs, $ParaEmpresa, $Incrementar)
    {
        return ReadSetDataNum($SQLs, $ParaEmpresa, $Incrementar);
    }

    function DCFactura($TipoFactura, $fa_factura)
    {
        $datos = $this->modelo->SelectDB_Combo_DCFactura_AdoFactura($TipoFactura, $fa_factura);
        if (count($datos) > 0) {
            return $datos;
        }
        return $datos;
    }

    function GrabarComprobante($parametros)
    {
        $NumComp = ReadSetDataNum("Ingresos", True, True);
        $Co = datos_Co();
        $Co['TP'] = G_COMPINGRESO;
        $Co['T'] = G_NORMAL;
        $Co['Fecha'] = $parametros['Fecha'];
        $Co['Numero'] = $NumComp;
        $Co['Monto_Total'] = $parametros['Total'];
        if ($parametros['TipoFactura'] == "OP") {
            $Co['Concepto'] = "Abotono Anticipado de
             " . strtoupper($parametros['NombreC']) . ", Orden No. " . $parametros['Factura'];
        } else {
            $Co['Concepto'] = "Abono Anticipado de 
            " . strtoupper($parametros['NombreC']);
            if (strlen($parametros['Grupo']) > 1)
                $Co['Concepto'] = $Co['Concepto'] . ", Grupo: " . $parametros['Grupo'];
        }
        if (strlen($parametros['TxtConcepto']) > 1) {
            $Co['Concepto'] = $Co['Concepto'] . ", " . $parametros['TxtConcepto'];
        }
        $Co['CodigoB'] = $parametros['CodigoCli'];
        $Co['Efectivo'] = $parametros['Total'];
        $Co['Cotizacion'] = 0;
        $Co['Item'] = $_SESSION['INGRESO']['item'];
        $Co['Usuario'] = $_SESSION['INGRESO']['CodigoU'];
        $Co['T_No'] = $parametros['Trans_No'];
        GrabarComprobante($Co);
        return $Co;
    }

    function EnviarEmail($parametros)
    {
        $datos = Leer_Datos_Clientes($parametros['CodigoB']);
        if (strlen($datos['Email']) > 3) {
            $Titulo = "Envio de Email";
            $Mensaje = "¿Enviar por email el recibo?";
            $MailAsunto = "RECIBO ABONO ANTICIPADO No. " . date("Y", strtotime($parametros['Fecha'])) . "-" .
                $parametros['TP'] . "-" . sprintf(
                "%09d",
                $parametros['Numero']
            );
            $MailMensaje = "Beneficiario: " . $datos['Cliente']
                . " Fecha del Abono: " . $parametros['Fecha']
                . " Abono Anticipado por USD " . number_format($parametros['Efectivo'], 2, '.', ',');
            return array(
                'res' => 1,
                'Titulo' => $Titulo,
                'Email' => 'lrsunigagarcia@gmail.com',//Cambiar por $datos['Email']
                'Mensaje' => $Mensaje,
                'MailAsunto' => $MailAsunto,
                'MailMensaje' => $MailMensaje
            );
        }
        return array('res' => -1);
    }

    function EnviarEmailAccept($parametros){
        return $this->email->enviar_email(false,$parametros['Email'],$parametros['MailMensaje'],$parametros['MailAsunto']);
    }
















}
?>