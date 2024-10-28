<?php
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

/*
    AUTOR DE RUTINA	: Leonardo Súñiga
    FECHA CREACION	: 03/01/2024
    FECHA MODIFICACION: 17/01/2024
    DESCIPCION : Clase que se encarga de manejar la lógica de la pantalla de recaudacion de bancos
*/
require_once(dirname(__DIR__, 2) . "/modelo/facturacion/FRecaudacionBancosPreFaM.php");
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
$controlador = new FRecaudacionBancosPreFaC();

if (isset($_GET['DCLinea'])) {

    echo json_encode($controlador->DCLinea());
}

if (isset($_GET['DCBanco'])) {

    echo json_encode($controlador->DCBanco());
}

if (isset($_GET['DCGrupos'])) {

    echo json_encode($controlador->DCGrupos());
}

if (isset($_GET['DCEntidadBancaria'])) {

    echo json_encode($controlador->DCEntidadBancaria());
}

if (isset($_GET['MBFechaI_LostFocus'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->MBFechaI_LostFocus($parametros));
}

if (isset($_GET['Existe_Factura'])) {
    $parametros = $_POST['parametros'];
    echo json_encode(Existe_Factura($parametros));
}

if (isset($_GET['ReadSetDataNum'])) {
    $parametros = $_POST['parametros'];
    echo json_encode(ReadSetDataNum($parametros['SQLs'], $parametros['ParaEmpresa'], $parametros['Incrementar'], $parametros['Fecha']));

}

if (isset($_GET['TextFacturaNo_LostFocus'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->TextFacturaNo_LostFocus($parametros));

}

if (isset($_GET['DCLinea_LostFocus'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->DCLinea_LostFocus($parametros));

}

if (isset($_GET['Command1_Click'])) {
    $FA = json_decode($_POST['FA'], true);
    $Factura_No = $_POST['Factura_No'];
    $MBFechaI = $_POST['MBFechaI'];
    $TextoBanco = $_POST['TextoBanco'];
    $parametros = array('FA' => $FA, 'Factura_No' => $Factura_No, 'MBFechaI' => $MBFechaI, 'TextoBanco' => $TextoBanco);

    if (isset($_FILES['archivoBanco']) && $_FILES['archivoBanco']['error'] == UPLOAD_ERR_OK) {
        $archivo = $_FILES['archivoBanco'];
        $carpetaDestino = dirname(__DIR__, 3) . "/TEMP/BANCO/ABONOS/";

        //Verifica si la carpeta existe
        if (!is_dir($carpetaDestino)) {
            // Intentar crear la carpeta, el 0777 es el modo de permiso más permisivo
            if (!mkdir($carpetaDestino, 0777, true)) { // true permite la creación de estructuras de directorios anidados
                echo json_encode(array("response" => 0, "message" => "Error al subir el archivo"));
            }
        }

        $nombreArchivoDestino = $carpetaDestino . basename($archivo['name']);
        $tmp = basename($archivo['name']);
        if (move_uploaded_file($archivo['tmp_name'], $nombreArchivoDestino)) {
            $parametros['NombreArchivo'] = $nombreArchivoDestino;
            $parametros['NombreArchivoTmp'] = $tmp;
            echo json_encode($controlador->Command1_Click($parametros));
        } else {
            echo json_encode(array("response" => 0, "message" => "Error al subir el archivo"));
        }
    }
}

if (isset($_GET['DatosBanco'])) {
    echo json_encode($controlador->DatosBanco());
}

if (isset($_GET['Command4_Click'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Command4_Click($parametros));
}

if (isset($_GET['Command6_Click'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Command6_Click($parametros));
}

if(isset($_GET['Command7_Click'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Command7_Click($parametros));
}

if(isset($_GET['Command5_Click'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Command5_Click($parametros));
}

if(isset($_GET['Command3_Click'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Command3_Click($parametros));
}



class FRecaudacionBancosPreFaC
{

    private $modelo;
    private $pdf;

    public function __construct()
    {
        $this->modelo = new FRecaudacionBancosPreFaM();
        $this->pdf = new cabecera_pdf();
    }

    public function DCLinea()
    {
        return $this->modelo->DCLinea();
    }

    public function DCBanco()
    {
        return $this->modelo->DCBanco();
    }

    public function DCGrupos()
    {

        return $this->modelo->DCGrupos();
    }

    public function DCEntidadBancaria()
    {
        return $this->modelo->DCEntidadBancaria();
    }

    public function MBFechaI_LostFocus($parametros)
    {
        return $this->modelo->MBFechaI_LostFocus($parametros);
    }

    public function TextFacturaNo_LostFocus($parametros)
    {
        if (Existe_Factura($parametros['FA'])) {
            return array('response' => 1, 'Factura' => '');
        } else {
            return array(
                'response' => 0,
                'Factura' => ReadSetDataNum(
                    $parametros['FA']['TC'] . "_SERIE_" . $parametros['FA']['Serie'],
                    true,
                    false
                )
            );
        }
    }

    public function DCLinea_LostFocus($parametros)
    {
        $FA = variables_tipo_factura();
        if (isset($parametros['FA']) && is_array($parametros['FA'])) {
            // Fusionar los valores de $FA con los valores de $parametros['FA']
            $FA = array_merge($FA, $parametros['FA']);
        }
        $tmp = Lineas_De_CxC($FA);
        $tmp['TFA']['Factura'] = ReadSetDataNum($tmp['TFA']['TC'] . "_SERIE_" . $tmp['TFA']['Serie'], true, false);
        return $tmp;
    }

    public function Command1_Click($parametros)
    {
        $FA = $parametros['FA'];
        $FA = Lineas_De_CxC($FA)['TFA'];
        $FA['Nuevo_Doc'] = true;
        $TextoImprimio = "";
        $NumeroTarjeta = "";
        $CodigoEncontrado = "'.'";
        $CostoTarjeta = 0;
        if ($FA['Nuevo_Doc']) {
            $FA['Factura'] = ReadSetDataNum($FA['TC'] . "_SERIE_" . $FA['Serie'], true, false);
        }
        $parametros['Factura_No'] = $FA['Factura'];
        $this->modelo->Command1_Click_Delete_AsientoF();
        $this->modelo->Command1_Click_Delete_TablaTemporal();
        $this->modelo->Command1_Click_Update_ClientesFacturacion();
        $Tipo_Carga = Leer_Campo_Empresa("Tipo_Carga_Banco");
        $Separador = ",";
        $Mifecha = BuscarFecha($parametros['MBFechaI']);
        $FechaTexto = $parametros['MBFechaI'];
        $DiarioCaja = ReadSetDataNum("Recibo_No", true, true);
        $NombreArchivo = $parametros['NombreArchivo'];
        if ($NombreArchivo <> "") {
            Actualizar_Datos_Representantes_SP();
        }
        $RutaGeneraFile = $NombreArchivo;
        $TotalIngreso = 0;
        $Contador = 0;
        $FileResp = 0;
        $Total_Alumnos = 0;
        $FechaTexto = FechaSistema();
        $TxtFile = "";

        /*
        Determinamos cuantos registro vamos a actualizar y
        cuantos campos tiene el archivo del Banco
        */
        $ContTAB = 0;

        $file = fopen($RutaGeneraFile, "r"); //Abrimos el archivo
        while (!feof($file)) { // Loop a travez de cada linea del archivo
            $Cod_Field = fgets($file); //Lee la linea
            $Cod_Field = str_replace('"', '', $Cod_Field); //Elimina las comillas dobles
            if ($Contador === 0) {
                $length = strlen($Cod_Field); //Determina la longitud de la linea
                for ($i = 0; $i < $length; $i++) {
                    if ($Cod_Field[$i] === "\t") {
                        $ContTAB++;
                        $Separador = "\t";
                    }
                }
            }
            $Contador++;
        }
        fclose($file); //Cierra el archivo

        $CamposFile = [];
        $FechaTexto = FechaSistema();
        for ($i = 0; $i <= $ContTAB; $i++) {
            $CamposFile[$i] = [];
            $CamposFile[$i]['Campo'] = "C" . str_pad($i, 2, "0", STR_PAD_LEFT);
        }

        //Empieza la subida
        $Contador = 0;

        $file = fopen($RutaGeneraFile, "r"); //Abrimos el archivo
        while (!feof($file)) {
            //Leemos la trama donde esta toda la informacion del archivo de recaudaciones
            $Cod_Field = fgets($file);
            $TxtFile .= $Cod_Field . "\n";
            //Comenzamos la subida de los Abonos
            for ($i = 0; $i <= $ContTAB; $i++) {
                $CamposFile[$i]['Valor'] = "";
            }
            $Cadena = $Cod_Field;
            $i = 0;
            $No_Desde = 1;
            $No_Hasta = 0;
            $Total_Reg = strlen($Cadena);
            while (strlen($Cadena) > 0) {
                $No_Hasta++;
                if (substr($Cadena, $No_Hasta - 1, 1) === $Separador) {
                    $CamposFile[$i]['Valor'] = substr($Cadena, $No_Desde - 1, $No_Hasta - 1);
                    $Cadena = substr($Cadena, $No_Hasta, strlen($Cadena));
                    $No_Desde = 1;
                    $No_Hasta = 0;
                    $i++;
                }
                if ($No_Hasta >= $Total_Reg) {
                    $CamposFile[$i]['Valor'] = $Cadena;
                    $Cadena = "";
                }
            }
            //Actualizamos de que alumnos vamos a ingresar el abono
            $NoMeses = 0;
            $NoAnio = 0;
            $Mes = G_NINGUNO;
            $CodigoInv = G_NINGUNO;
            $CodigoCli = G_NINGUNO;
            $Codigo1 = G_NINGUNO;
            $Codigo3 = G_NINGUNO;
            $Codigo4 = G_NINGUNO;
            $CodigoP = "0";

            switch ($parametros['TextoBanco']) {
                case "PICHINCHA":
                    if ($Tipo_Carga >= 1) {
                        //Los substr de php y MidStrg de VB tienen los mismos parametros pero el de VB comienza la indexacion en 1 y el de php en 0.
                        $CodigoCli = trim(strval(floatval(substr($Cod_Field, 24, 19))));
                        $FechaTexto = substr($Cod_Field, 204, 2) . "/" . substr($Cod_Field, 206, 2) . "/" . substr($Cod_Field, 208, 4);
                        $Total = floatval(substr($Cod_Field, 47, 13)) / 100;
                        $NoMeses = intval(substr($Cod_Field, 197, 2));
                        $NoAnio = intval(substr($Cod_Field, 200, 4));
                        if (intval($NoMeses) <= 0) {
                            $NoMeses = intval(substr($Cod_Field, 206, 2));
                            $NoAnio = intval(substr($Cod_Field, 208, 4));
                        }
                        $Mes = MesesLetras($NoMeses);
                        $NombreCliente = trim(substr($Cod_Field, 124, 40));
                        $CodigoInv = SinEspaciosIzq(trim(substr($Cod_Field, 164, 33)));
                        $Producto = trim(substr($Cod_Field, 164, 33));
                        $Producto = trim(substr($Producto, strlen($CodigoInv), strlen($Producto)));
                        $Cantidad = 1;
                        $Codigo3 = substr($Cod_Field, 73, 3);
                        if ($Codigo3 === "EFE") {
                            $NombreBanco = "EFECTIVO POR VENTANILLA";
                        } else {
                            $Codigo3 = substr($Cod_Field, 73, 3) . ". " . substr($Cod_Field, 80, 3) . ". ";
                            $Codigo4 = substr($Cod_Field, 91, 12);
                            $NombreBanco = $Codigo3 . "No. " . substr($Cod_Field, 91, 12);
                        }
                    } else {
                        if ($ContTAB > 40) {
                            $FechaTexto = str_replace(" ", "/", $CamposFile[25]['Valor']);
                            if (IsDate($FechaTexto)) {
                                $CodigoCli = trim($CamposFile[4]['Valor']);
                                $NombreCliente = $CamposFile[5]['Valor'];
                                $Codigo3 = $CamposFile[16]['Valor'];
                                $Codigo4 = $CamposFile[26]['Valor'];
                                $SubTotal = $CamposFile[8]['Valor'];
                                $Total = $CamposFile[8]['Valor'];
                                $Total_Letras = $CamposFile[7]['Valor'];
                                $CodigoInv = SinEspaciosIzq($Total_Letras);
                                if (is_numeric($CodigoInv)) {
                                    $NoMeses = intval(substr($Total_Letras, strlen($Total_Letras) - 7, 2));
                                    $NoAnio = SinEspaciosIzq($Total_Letras);
                                    $Mes = MesesLetras($NoMeses);
                                    $Producto = trim(substr($Total_Letras, strlen($Total_Letras), 28));
                                } else {
                                    $CodigoInv = G_NINGUNO;
                                    $NoAnio = date("Y", strtotime($FechaTexto));
                                    if (is_numeric(substr($NombreCliente, 0, 2))) {
                                        $NoAnio = 0;
                                        $NoMeses = intval(substr($NombreCliente, 0, 2));
                                        $Mes = MesesLetras($NoMeses);
                                        $NombreCliente = substr($NombreCliente, 2, strlen($NombreCliente));
                                    } else {
                                        $NoMeses = date("m", strtotime($CamposFile[25]['Valor']));
                                        $Mes = MesesLetras($NoMeses);
                                        $NombreCliente = substr($CamposFile[5]['Valor'], strlen($Mes) - 1, strlen($CamposFile[5]['Valor']) - 1);
                                    }
                                }
                                $Cantidad = 1;
                            }
                        } else {
                            $FechaTexto = $CamposFile[12]['Valor'];
                            $NoAnio = date("Y", strtotime($FechaTexto));
                            $Codigo3 = $CamposFile[16]['Valor'];
                            $Codigo4 = $CamposFile[17]['Valor'];
                            $CodigoCli = trim($CamposFile[7]['Valor']);
                            $CodigoB = $CamposFile[7]['Valor'];
                            if (is_numeric(substr($CamposFile[8]['Valor'], 0, 2))) {
                                $NoAnio = 0;
                                $NoMeses = intval(substr($CamposFile[8]['Valor'], 0, 2));
                                $Mes = MesesLetras($NoMeses);
                                $NombreCliente = substr($CamposFile[8]['Valor'], 2, strlen($CamposFile[8]['Valor']));
                            } else {
                                $NoMeses = intval($CamposFile[14]['Valor']);
                                $Mes = MesesLetras($NoMeses);
                                $NombreCliente = substr($CamposFile[8]['Valor'], strlen($Mes) - 1, strlen($CamposFile[8]['Valor']) - 1);
                            }

                            $Producto = "PENSION DE: " . $Mes;
                            $FechaTexto = $CamposFile[12]['Valor'];
                            $SubTotal = $CamposFile[9]['Valor'] / 100;
                            $Total = $CamposFile[9]['Valor'] / 100;
                            if (strlen($Codigo4) <= 1) {
                                $Codigo4 = G_NINGUNO;
                            }
                            $Cantidad = 1;
                        }
                    }
                    break;
                case "INTERNACIONAL":
                case "OTROSBANCOS":
                    if ($Separador === "\t") {
                        switch ($ContTAB) {
                            case 18:
                            case 21:
                                if (is_numeric(substr($CamposFile[16]['Valor'], 0, 4))) {
                                    $CodigoB = $CamposFile[6]['Valor'];
                                    $CodigoCli = $CamposFile[6]['Valor'];
                                    $FechaTexto = substr($CamposFile[7]['Valor'], 0, 10);
                                    $HoraTexto = trim(substr($CamposFile[7]['Valor'], 10, 5));
                                    $CodigoP = $CamposFile[10]['Valor'];
                                    $Codigo3 = $CamposFile[10]['Valor'];
                                    $Codigo4 = $CamposFile[8]['Valor'];
                                    $ListaFacturas = G_NINGUNO;
                                    $NoAnio = intval(substr($CamposFile[16]['Valor'], 0, 4));
                                    $NoMeses = intval(substr($CamposFile[16]['Valor'], 5, 2));
                                    $CodigoInv = trim(substr($CamposFile[16]['Valor'], 8, 18));
                                    $Mes = MesesLetras($NoMeses);
                                    $SubTotal = intval($CamposFile[13]);
                                    $Total = $SubTotal;
                                    if (strlen($Codigo4) <= 1) {
                                        $Codigo4 = G_NINGUNO;
                                    }
                                }
                                break;
                            default:
                                if (is_numeric(substr($CamposFile[12]['Valor'], 0, 4))) {
                                    $CodigoB = $CamposFile[3]['Valor'];
                                    $CodigoCli = $CamposFile[3]['Valor'];
                                    $FechaTexto = substr($CamposFile[16]['Valor'], 0, 10);
                                    $HoraTexto = trim(substr($CamposFile[16]['Valor'], 10, 5));
                                    $CodigoP = $CamposFile[18]['Valor'];
                                    $Codigo3 = $CamposFile[18]['Valor'];
                                    $Codigo4 = trim(substr(substr($CamposFile[7]['Valor'], 0, 4) . ". " . str_replace("AGENCIA", "AGE.", $CamposFile[20]['Valor']), 0, 30));
                                    $ListaFacturas = G_NINGUNO;
                                    $NoAnio = intval(substr($CamposFile[12]['Valor'], 0, 4));
                                    $NoMeses = intval(substr($CamposFile[12]['Valor'], 5, 2));
                                    $CodigoInv = trim(substr($CamposFile[12]['Valor'], 8, 18));
                                    $Mes = MesesLetras($NoMeses);
                                    $SubTotal = floatval($CamposFile[10]);
                                    $Total = $SubTotal;
                                    if (strlen($Codigo4) <= 1) {
                                        $Codigo4 = G_NINGUNO;
                                    }
                                }
                                break;
                        }
                        if ($CodigoCli === G_NINGUNO) {
                            for ($i = 0; $i <= $ContTAB; $i++) {
                                $CamposFile[$i]['Campo'] = $CamposFile[$i]['Valor'];
                            }
                            $FechaTexto = FechaSistema();
                            $NoAnio = "2000";
                            $NoMeses = 12;
                            $Mes = MesesLetras($NoMeses);
                            $CodigoP = G_NINGUNO;
                        }
                        $Cantidad = 1;
                    } else {
                        $Cod_Field = str_replace('\t', ' ', $Cod_Field);
                        $CodigoCli = trim(intval(substr($Cod_Field, 24, 19)));
                        $Mifecha = substr($Cod_Field, 204, 2) . "/" . substr($Cod_Field, 206, 2) . "/" . substr($Cod_Field, 208, 4);
                        $Total = floatval(substr($Cod_Field, 47, 13)) / 100;
                        $Cadena = trim(substr($Cod_Field, 164, 40));
                        $i = strpos($Cadena, "-");
                        if ($i > 1) {
                            $Cadena = trim(substr($Cadena, $i, strlen($Cadena)));
                            $J = strpos($Cadena, "-");
                            if ($J > 1) {
                                $Cadena = trim(substr($Cadena, $J, strlen($Cadena)));
                            }
                            if ($i > 1 && $J > 1) {
                                $Producto = trim(substr($Producto, $i, $J - 1));
                            }
                            $NoMeses = $this->LetrasMeses($CodigoB);
                            $Mes = MesesLetras($NoMeses);
                            $NoAnio = date("Y", strtotime($Mifecha));
                        }
                        $FechaTexto = $Mifecha;
                    }
                    break;
                case "PRODUBANCO":
                    $CodigoP = $CamposFile[7]['Valor'];
                    $CodigoCli = $CamposFile[7]['Valor'];
                    $NombreCliente = $CamposFile[8]['Valor'];
                    $SubTotal = floatval($CamposFile[9]['Valor']);
                    $Total = floatval($CamposFile[9]['Valor']);
                    $FechaTexto = $CamposFile[12]['Valor'];
                    $Producto = $CamposFile[14]['Valor'];
                    $CodigoB = $CamposFile[14]['Valor'];

                    $NoAnio = intval(substr(trim(SinEspaciosDer2($CodigoB)), 0, 4));
                    $CodigoB = strtolower(trim(substr(trim(SinEspaciosDer2($CodigoB)), 5, 3)));
                    $NoMeses = $this->LetrasMeses($CodigoB);
                    $Codigo3 = $CamposFile[27]['Valor'];
                    $Codigo4 = $CamposFile[31]['Valor'];
                    $Codigo2 = $CamposFile[31]['Valor'];
                    if ($NoAnio <= "1900" && IsDate($FechaTexto)) {
                        $NoMeses = date("m", strtotime($FechaTexto));
                        $NoAnio = date("Y", strtotime($FechaTexto));
                        $Mes = MesesLetras($NoMeses);
                    }
                    break;
                case "BOLIVARIANO":
                    if (substr($Cod_Field, 11, 2) == "00") {
                        $CodigoCli = substr($Cod_Field, 13, 8);
                        $CodigoP = substr($Cod_Field, 13, 8);
                    } else {
                        $CodigoCli = substr($Cod_Field, 11, 10);
                        $CodigoP = substr($Cod_Field, 11, 10);
                    }
                    if ($Total_Alumnos == 0) {
                        $FechaTexto = substr($Cod_Field, 11, 2) . "/" .
                            substr($Cod_Field, 9, 2) . "/" .
                            substr($Cod_Field, 5, 4);
                        $CodigoP = G_NINGUNO;
                    }
                    $Producto = "PENSION DE: " . MesesLetras(intval(substr($Cod_Field, 4, 2)));
                    $Mes = MesesLetras(intval(substr($Cod_Field, 4, 2)));
                    $NoMeses = intval(substr($Cod_Field, 4, 2));
                    $NoAnio = intval(substr($Cod_Field, 0, 4));
                    $Cantidad = 1;
                    $Total = floatval(substr($Cod_Field, 21, 11)) / 100;
                    $SubTotal = $Total;
                    break;
                case "GUAYAQUIL":
                    if (substr($Cod_Field, 2, 3) == "RER") {
                        $CodigoCli = G_NINGUNO;
                        $CodigoP = G_NINGUNO;
                        $Producto = G_NINGUNO;
                        $Mes = G_NINGUNO;
                        $NoMeses = 0;
                        $NoAnio = 0;
                        $Cantidad = 1;
                        $CodigoP = G_NINGUNO;
                        $Total = 0;
                        $SubTotal = $Total;
                    } else {
                        $Mes = G_NINGUNO;
                        $NoMeses = 0;
                        $NoAnio = "2000";
                        $CodigoCli = substr($Cod_Field, 4, 8);
                        $CodigoP = substr($Cod_Field, 4, 8);
                        $FechaTexto = substr($Cod_Field, 90, 2) . "/" .
                            substr($Cod_Field, 88, 2) . "/" .
                            substr($Cod_Field, 84, 4);
                        $Producto = "PENSION DE: " . substr($Cod_Field, 59, 3);
                        $Mes = substr($Cod_Field, 59, 3);
                        $NoMeses = $this->LetrasMeses(substr($Cod_Field, 59, 3));
                        $NoAnio = substr($Cod_Field, 84, 4);
                        $Cantidad = 1;
                        $Total = floatval(substr($Cod_Field, 74, 10)) / 100;
                        $SubTotal = $Total;
                    }
                    break;
                case "PACIFICO":
                    $CodigoCli = trim(substr($Cod_Field, 301, 14));
                    $CodigoP = trim(intval(substr($Cod_Field, 301, 14)));
                    $Producto = trim(substr($Cod_Field, 251, 40));
                    $Grupo_No = trim(SinEspaciosIzq($Producto));
                    $Producto = trim(substr($Producto, strlen($Grupo_No), strlen($Producto)));
                    $Mes = trim(SinEspaciosIzq($Producto));
                    $NoMeses = $this->LetrasMeses($Mes);
                    if (is_numeric(trim(SinEspaciosDer2($Producto)))) {
                        $NoAnio = intval(trim(SinEspaciosDer2($Producto)));
                        if ($NoAnio < 1900) {
                            $NoAnio = intval(trim(substr($Producto, 3, 5)));
                        }
                    } else {
                        $NoAnio = date("Y", strtotime(FechaSistema()));
                    }
                    if ($NoAnio < 1900) {
                        $NoAnio = date("Y", strtotime(FechaSistema()));
                    }
                    $Cantidad = 1;
                    $SubTotal = floatval(substr($Cod_Field, 31, 15)) / 100;
                    $Total = floatval(substr($Cod_Field, 31, 15)) / 100;
                    $NombreCliente = trim(substr($Cod_Field, 315, 40));
                    $FechaTexto = substr($Cod_Field, 26, 2) . "/" .
                        substr($Cod_Field, 24, 2) . "/" .
                        substr($Cod_Field, 20, 4);
                    $SubCta = trim(substr($Cod_Field, 291, 10));
                    break;
                case "POREXCEL":
                    $CodigoCli = $CamposFile[0]['Valor'];
                    $FechaTexto = $CamposFile[1]['Valor'];
                    $NoMeses = intval($CamposFile[2]['Valor']);
                    $NoAnio = intval($CamposFile[3]['Valor']);
                    $SubTotal = floatval($CamposFile[4]['Valor']);
                    $Total = $SubTotal;
                    $Mes = MesesLetras($NoMeses);
                    $Producto = "PENSION DE: " . $Mes;
                    $Cantidad = 1;
                    break;
                case "TARJETAS":
                    $Cantidad = 1;
                    $CodigoCli = $CamposFile[3]['Valor'];
                    $NombreCliente = G_NINGUNO;
                    $NumeroTarjeta = $CamposFile[4]['Valor'];
                    $CostoTarjeta = floatval($CamposFile[10]['Valor']);
                    $SubTotal = floatval($CamposFile[15]['Valor']);
                    $Total = floatval($CamposFile[15]['Valor']);
                    $FechaTexto = date('d/m/Y', strtotime(substr($CamposFile[0]['Valor'], 0, 10)));
                    $CodigoB = $FechaTexto;
                    $tmp = Leer_RUC_CI_Tarjeta($NumeroTarjeta, $Total, $CodigoCli);
                    if(count($tmp) > 2){
                        $CodigoCli = $tmp['CodigoCli'];
                        $NombreCliente = $tmp['NombreCliente'];
                        $TarjetaNo = $tmp['TarjetaNo'];
                        $NoAnio = $tmp['NoAnio'];
                        $NoMeses = $tmp['NoMeses'];
                        $Mes = $tmp['Mes'];
                        $CodigoInv = $tmp['CodigoInv'];
                        $CodigoP = $tmp['Leer_RUC_CI_TARJETA'];
                    }
                    $CodigoCli = $CodigoP;
                    $Producto = "TARJETA DE: " . $NumeroTarjeta . " - " . $CamposFile[5]['Valor'];
                    $CodigoB = strtolower(trim(substr(trim(SinEspaciosDer2($CodigoB)), 5, 3)));
                    $Codigo3 = $CamposFile[1]['Valor'];
                    $Codigo4 = $CamposFile[2]['Valor'];
                    $Codigo2 = $CamposFile[3]['Valor'];
                    
                    break;
                default:
                    $CodigoCli = $CamposFile[0]['Valor'];
                    $FechaTexto = $CamposFile[1]['Valor'];
                    $NoMeses = intval($CamposFile[2]['Valor']);
                    $NoAnio = intval($CamposFile[3]['Valor']);
                    $SubTotal = floatval($CamposFile[4]['Valor']);
                    $Total = $SubTotal;
                    $Mes = MesesLetras($NoMeses);
                    $Producto = "PENSION DE: " . $Mes;
                    $Cantidad = 1;
                    break;
            }
            $Si_No = true;
            if (strlen($CodigoCli) > 5) {
                while (strlen($CodigoCli) <= 10) {
                    $AdoCliDB = $this->modelo->AdoCliDB($CodigoCli);
                    if (count($AdoCliDB) > 0) {
                        $CodigoCli = $AdoCliDB[0]['Codigo'];
                        $NombreCliente = $AdoCliDB[0]['Cliente'];
                        $Grupo_No = $AdoCliDB[0]['Grupo'];
                        $Si_No = false;
                    } else {
                        $CodigoCli = "0" . $CodigoCli;
                    }
                }
                //Progreso_Esperar
            }

            if (strlen($CodigoCli) > 10) {
                $CodigoCli = G_NINGUNO;
            }

            $Producto = "PENSION DE: ";
            if ($CodigoInv <> G_NINGUNO) {
                $AdoAux = $this->modelo->AdoAuxProducto($CodigoInv);
                if (count($AdoAux) > 0) {
                    $Producto = $AdoAux[0]['Producto'];
                }
            }

            $FechaTope = date("Y-m-t", strtotime(FechaSistema()));

            //Verificamos la primera deuda antgua que tenga el Cliente de ese mes
            if (IsDate($FechaTexto)) {
                $AdoAux = $this->modelo->AdoAuxClientes_Facturacion($CodigoCli, $NoMeses, $NoAnio, $CodigoInv, $FechaTope);
                if (count($AdoAux) > 0 && ($CodigoCli <> G_NINGUNO)) {
                    $Sumatoria = $Total;
                    foreach ($AdoAux as $key => $value) {
                        $NoAnio = $value['Periodo'];
                        $NoMeses = $value['Num_Mes'];
                        $Mes = MesesLetras($NoMeses);
                        $CodigoInv = $value['Codigo_Inv'];
                        $Total = $value['Valor'] - ($value['Descuento'] + $value['Descuento2']);
                        //Progreso_Esperar true
                        if ($Total <= $Sumatoria) {
                            SetAdoAddNew("Asiento_F");
                            SetAdoFields("CODIGO", $CodigoInv);
                            SetAdoFields("CANT", $Cantidad);
                            SetAdoFields("PRODUCTO", $Producto);
                            SetAdoFields("PRECIO", $Total);
                            SetAdoFields("TOTAL", $Total);
                            SetAdoFields("Mes", $Mes);
                            SetAdoFields("A_No", $NoMeses);
                            SetAdoFields("HABIT", $NoAnio);
                            SetAdoFields("FECHA", $FechaTexto);
                            SetAdoFields("RUTA", $NombreCliente);
                            SetAdoFields("Codigo_Cliente", $CodigoCli);
                            SetAdoFields("Cod_Ejec", $CodigoP);
                            SetAdoFields("Cta", $Grupo_No);
                            SetAdoFields("Numero", $parametros['Factura_No']);
                            SetAdoFields("Serie", $parametros['FA']['Serie']);
                            SetAdoFields("Autorizacion", $parametros['FA']['Autorizacion']);
                            SetAdoFields("CODIGO_L", $Codigo3);
                            SetAdoFields("TICKET", $Codigo4);
                            if ($CostoTarjeta > 0) {
                                SetAdoFields("COSTO", $CostoTarjeta);
                            }
                            SetAdoUpdate();
                            $parametros['Factura_No'] = $parametros['Factura_No'] + 1;
                            $Sumatoria = $Sumatoria - $Total;
                            $this->modelo->AdoAuxClientes_FacturacionUpdate($CodigoCli, $NoMeses, $NoAnio, $CodigoInv, $FechaTope);
                        }
                    }
                } else {
                    if ($Total_Alumnos <> 0) {
                        $Total_Letras = number_format($Total, 2, '.', ',');
                        $Total_Letras = str_pad($Total_Letras, 12, " ", STR_PAD_LEFT);
                        $Cadena = "Codigo: " . $CodigoCli . " \t" . "Cedula: " . $CodigoP . " \t" . $FechaTexto .
                            " \t" . $Mes . "/" . $NoAnio . ", USD " . $Total_Letras . " \t" . $NombreCliente;
                        if (strlen($NumeroTarjeta) > 1) {
                            $Cadena .= " \t" . $NumeroTarjeta;
                        }
                        //TODO: Insertar_Texto_Temporal_SP Cadena
                        $TextoImprimio .= $Cadena . "\n";
                    }
                }
                $Total_Alumnos++;
            }
        }
        fclose($file);

        $Numero = 0;
        $AdoFactura = $this->modelo->AdoFactura();
        if (count($AdoFactura) > 0) {
            foreach ($AdoFactura as $key => $value) {
                //Progreso ESPERAR
                $AdoAux = $this->modelo->AdoAuxClientesFacturacion2(
                    $value['Codigo_Cliente'],
                    $value['CODIGO'],
                    $value['A_No'],
                    $value['HABIT']
                );
                if (count($AdoAux) <= 0) {
                    $this->modelo->AdoFacturaUpdate();
                }
            }
        }

        $this->modelo->DeleteAsientoF();

        $AdoFactura = $this->modelo->AdoFactura2();
        if (count($AdoFactura) > 0) {
            $Mifecha = $AdoFactura[0]['FECHA'];
            $Numero = $parametros['Factura_No'];
            $CodigoC = $AdoFactura[0]['Codigo_Cliente'];
            foreach ($AdoFactura as $key => $value) {
                if ($CodigoC <> $value['Codigo_Cliente'] || strtotime($Mifecha) <> strtotime($value['FECHA'])) {
                    $CodigoC = $value['Codigo_Cliente'];
                    $Mifecha = $value['FECHA'];
                    $parametros['Factura_No'] = $parametros['Factura_No'] + 1;
                }
                $this->modelo->AdoFacturaUpdate2($parametros['Factura_No']);
            }
        }

        $DGFactura = $this->modelo->DGFactura();
        $TotalIngreso = 0;
        if (count($DGFactura['AdoAsientoF']) > 0) {
            foreach ($DGFactura['AdoAsientoF'] as $key => $value) {
                $TotalIngreso += $value['TOTAL'];
            }
        }
        return array('tbl' => $DGFactura['datos'], 'TotalIngreso' => $TotalIngreso, 'TxtFile' => $TxtFile, 'NoMeses' => $NoMeses, 'NoAnio' => $NoAnio, 'RutaGeneraFile' => $parametros['NombreArchivoTmp']);
    }

    public function DatosBanco()
    {
        $Tipo_Carga = Leer_Campo_Empresa("Tipo_Carga_Banco");
        $Costo_Banco = Leer_Campo_Empresa("Costo_Bancario");
        $Cta_Bancaria = Leer_Campo_Empresa("Cta_Banco");
        $Cta_Gasto_Banco = Leer_Seteos_Ctas("Cta_Gasto_Bancario");
        return array(
            'Tipo_Carga' => $Tipo_Carga,
            'Costo_Banco' => $Costo_Banco,
            'Cta_Bancaria' => $Cta_Bancaria,
            'Cta_Gasto_Banco' => $Cta_Gasto_Banco
        );
    }

    public function Command4_Click($parametros)
    {
        Actualizar_Datos_Representantes_SP();
        $Cta_Banco = trim(strtoupper(SinEspaciosDer2($parametros['DCBanco'])));
        if (strlen($Cta_Banco) <= 1) {
            $Cta_Banco = "0000000000";
        }
        $parametros['Cta_Banco'] = $Cta_Banco;
        $TipoDoc = "";
        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $FechaFinal = $parametros['MBFechaF'];
        $FechaTexto = FechaSistema();
        $FechaTexto1 = date("m/d/y", strtotime($parametros['MBFechaI']));
        $MiFecha = BuscarFecha($parametros['MBFechaI']);
        $MiMes = date("m", strtotime($parametros['MBFechaI']));
        $Contador = 0;
        //Consultamos la deuda Pendiente
        $this->modelo->UpdateClientesFacturacion();
        $this->modelo->UpdateClientesFacturacion2($parametros['MBFechaI']);
        $this->modelo->UpdateClientesFacturacion3();

        $AdoFactura = $this->modelo->Tipo_Carga($parametros);

        //Verifica si la carpeta FACTURAS existe
        if (!file_exists(dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS")) {
            mkdir(dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS", 0777, true);
        }

        //$RutaDestino = "../../../TEMP/FRecaudacionBancosPreFa/DEUDAESTUDIANTE.csv";
        $Codigo = strtoupper(MesesLetras(date("m", strtotime($parametros['MBFechaI']))) . "-" . date("d", strtotime($parametros['MBFechaI'])) . "-" . date("Y", strtotime($parametros['MBFechaI'])));
        $RutaDestino = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/" . $Codigo . ".txt";
        $NombreArchivoZip = $RutaDestino;
        $FechaFin = BuscarFecha($parametros['MBFechaF']);
        $TextoImprimio = "";
        $FechaFin = BuscarFecha(date("y-m-t", strtotime($parametros['MBFechaF'])));
        switch ($parametros['TextoBanco']) {
            case "PACIFICO":
                return $this->Genera_Pacifico($parametros, $RutaDestino, $AdoFactura);
            case "PICHINCHA":
                return $this->Generar_Pichincha($parametros, $AdoFactura);
            case "BOLIVARIANO":
                return $this->Generar_Bolivariano($parametros, $AdoFactura, $RutaDestino);
            case "GUAYAQUIL":
                return $this->Generar_Guayaquil($parametros, $AdoFactura);
            case "PRODUBANCO":
                return $this->Generar_Produbanco($parametros, $AdoFactura);
            case "TARJETAS":
                return $this->Generar_Tarjetas($parametros, $AdoFactura);
            case "INTERNACIONAL":
                return $this->Genera_Internacional($parametros, $AdoFactura);
            default:
                return $this->Generar_Otros_Bancos($parametros, $AdoFactura);
                
        }
    }

    public function Generar_Otros_Bancos($parametros, $AdoFactura)
    {
        $Total_Banco = 0;
        $Mifecha = BuscarFecha($parametros['MBFechaI']);
        $MiMes = date("m", strtotime($parametros['MBFechaI']));
        $FechaFin = BuscarFecha(date("y-m-t", strtotime($parametros['MBFechaI'])));
        $TextoImprimio = "";
        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $Cta_Cobrar = G_NINGUNO;
        $FechaTexto = FechaSistema();
        $EsComa = true;
        $Estab = false;
        $Contador = 0;
        $Factura_No = 0;
        $Fecha_Meses = $parametros['MBFechaI'] . " al " . $parametros['MBFechaF'];
        $Fecha_Meses = str_replace("/", "-", $Fecha_Meses);
        $Mes = date("m", strtotime($parametros['MBFechaI']));
        $Anio = intval(substr(date("Y", strtotime($parametros['MBFechaI'])), 1, 3));
        $Dia = "15";

        $RutaGeneraFile = dirname(__DIR__, 3) . '/TEMP/BANCO/FACTURAS/Otros Bancos Debitos ' . $Fecha_Meses . '.TXT';
        $RutaGeneraFileExcel = dirname(__DIR__, 3) . '/TEMP/BANCO/FACTURAS/Otros Bancos Debitos ' . $Fecha_Meses . '.XLSX';
        $datosExcel = [];
        $tmpContador = 1;
        $NumFileFacturas = fopen($RutaGeneraFile, "w");
        if (count($AdoFactura) > 0) {
            foreach ($AdoFactura as $key => $value) {
                $Codigo4 = "";
                $CodigoCli = $value['Codigo'];
                $GrupoNo = $value['Grupo'];
                $NombreCliente = Sin_Signos_Especiales($value['Cliente']);
                $Producto = Sin_Signos_Especiales($value['Producto']);
                $CodigoInv = $value['Codigo_Inv'];
                $NoMes = $value['Num_Mes'];
                $Mes = sprintf("%02d", $NoMes);
                $Periodo = $value['Periodo'];
                $Codigo2 = $Periodo . " " . $Mes . " " . $CodigoInv . str_repeat(" ", 20 - strlen($CodigoInv)) . " " . $GrupoNo . str_repeat(" ", 10 - strlen($GrupoNo)) . " " . $Producto;
                $TipoCta = G_NINGUNO;
                switch ($value['Tipo_Cta']) {
                    case "AHORROS":
                        $TipoCta = "AHO";
                        break;
                    case "CORRIENTE":
                        $TipoCta = "CTE";
                        break;
                }
                if ($value['Cod_Banco'] > 0 && strlen($value['Cta_Numero']) > 1 && $TipoCta <> G_NINGUNO) {
                    if ($value['Cod_Banco'] <> intval($parametros['TxtCodBanco'])) {
                        $Contador++;
                        $Factura_No = intval($value['Periodo']) . sprintf("%02d", $value['Num_Mes']);
                        $Total = $value['Valor_Cobro'];
                        $Saldo = $value['Valor_Cobro'] * 100;
                        $CodigoP = $value['CI_RUC'];
                        $DireccionCli = Sin_Signos_Especiales($value['Direccion']);
                        $Codigo3 = SinEspaciosDer2($DireccionCli);
                        $DireccionCli = trim(substr($DireccionCli, 0, strlen($DireccionCli) - strlen($Codigo3)));
                        $Codigo3 = trim(SinEspaciosDer2($DireccionCli));
                        $Codigo1 = sprintf("%02d", substr($GrupoNo, 0, 1));

                        if ($parametros['Tipo_Carga'] == 2) {
                            $Codigo4 = "DEBITOS AUTOMATICOS";
                        } else {
                            if ($parametros['CheqMatricula']) {
                                $Codigo4 = "MATRICULAS Y PENSION DE " . substr(MesesLetras(date("m", strtotime($parametros['MBFechaI']))), 0, 3) . "-" . date("Y", strtotime($parametros['MBFechaI']));
                            } else {
                                if ($parametros['Tipo_Carga'] == 3) {
                                    $Codigo4 = "PENSION ACUMULADA";
                                } else {
                                    $Codigo4 = "PENSION ACUMULADA DE " . substr(MesesLetras(date("m", strtotime($parametros['MBFechaI']))), 0, 3) . "-" . date("Y", strtotime($parametros['MBFechaI']));
                                }
                            }
                        }
                        if (strlen($value['Email2']) > 3) {
                            $Codigo4 = $Codigo4 . "|" . $value['Email2'] . ";";
                        }

                        fwrite($NumFileFacturas, "CO" . "\t");
                        fwrite($NumFileFacturas, $parametros['Cta_Banco'] . "\t");
                        fwrite($NumFileFacturas, $Contador . "\t");
                        fwrite($NumFileFacturas, sprintf("%013d", $Factura_No) . "\t");
                        fwrite($NumFileFacturas, $CodigoP . "\t");
                        fwrite($NumFileFacturas, "USD" . "\t");
                        fwrite($NumFileFacturas, sprintf("%013d", $Saldo) . "\t");
                        fwrite($NumFileFacturas, "CTA" . "\t");
                        fwrite($NumFileFacturas, $value['Cod_Banco'] . "\t");
                        fwrite($NumFileFacturas, $TipoCta . "\t");
                        fwrite($NumFileFacturas, $value['Cta_Numero'] . "\t");
                        fwrite($NumFileFacturas, $value['TD_R'] . "\t");
                        fwrite($NumFileFacturas, sprintf("%010d", $value['CI_RUC_R']) . "\t");
                        fwrite($NumFileFacturas, substr(date("m", $value['Fecha']->getTimestamp()) . " " . $NombreCliente, 0, 40) . "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, $Codigo2 . "\t");
                        fwrite($NumFileFacturas, $Codigo4 . "\t");
                        fwrite($NumFileFacturas, sprintf("%013d", $Saldo) . "\t");
                        fwrite($NumFileFacturas, "\n");

                        $datosExcel[$tmpContador] = [
                            "CO", 
                            $parametros['Cta_Banco'],
                            $Contador,
                            "'" . sprintf("%013d", $Factura_No),
                            "'" . $CodigoP,
                            "USD",
                            "'" . sprintf("%013d", $Saldo),
                            "CTA",
                            $value['Cod_Banco'],
                            $TipoCta,
                            "'" . $value['Cta_Numero'],
                            $value['TD_R'],
                            "'" . sprintf("%010d", $value['CI_RUC_R']),
                            substr(date("m", $value['Fecha']->getTimestamp()) . " " . $NombreCliente, 0, 40),
                            "0",
                            "0",
                            "0",
                            "0",
                            $Codigo2,
                            $Codigo4,
                            "'" . sprintf("%013d", $Saldo)
                        ];
                        $tmpContador++;

                        $Total_Banco = $Total_Banco + $Total;
                    }

                }

            }
        }
        fclose($NumFileFacturas);
        excel_simple($datosExcel, $RutaGeneraFileExcel, "Otros Bancos Debitos");
        return array(
            'LabelAbonos' => sprintf("%02d", $Total_Banco),
            'Mensaje' => "SE HA GENERADO EL SIGUIENTE ARCHIVO \n" . basename($RutaGeneraFile),
            'CantArchivos' => 2,
            'TxtFile' => " ",
            'Nombre1' => basename($RutaGeneraFile),
            'Nombre2' => basename($RutaGeneraFileExcel)
        );
    }

    public function Generar_Tarjetas($parametros, $AdoFactura)
    {
        $Total_Banco = 0;
        $TextoImprimio = "";
        $Fecha_Meses = $parametros['MBFechaI'] . " al " . $parametros['MBFechaF'];
        $Fecha_Meses = str_replace("/", "-", $Fecha_Meses);
        $ContadorTarjeta = 0;
        $RutaGeneraFile = dirname(__DIR__, 3) . '/TEMP/BANCO/FACTURAS/Tarjetas ' . $Fecha_Meses . '.TXT';
        $NombreArchivos = $RutaGeneraFile;
        $NumFileFacturas = fopen($RutaGeneraFile, "w"); //Abre el archivo
        if (count($AdoFactura) > 0) {
            foreach ($AdoFactura as $key => $value) {
                $CodigoCli = $value['Codigo'];
                $CodigoP = sprintf("%017d", $value['CI_RUC']);
                $TRep = Leer_Datos_Clientes($CodigoCli);
                if ($value['Tipo_Cta'] == "TARJETA" && strlen($TRep['Cta_Numero']) >= 14) {
                    $ContadorTarjeta++;
                    $Total = $value['Valor_Cobro'];
                    $Saldo = $value['Valor_Cobro'] * 100;
                    $TRep['Cta_Numero'] = substr($TRep['Cta_Numero'], 0, 16);
                    $TRep['Cta_Numero'] = str_repeat("0", 16 - strlen($TRep['Cta_Numero'])) . $TRep['Cta_Numero'];

                    fwrite($NumFileFacturas, $TRep['Cta_Numero']);
                    fwrite($NumFileFacturas, sprintf("%08d", intval($parametros['TxtCodBanco'])));
                    fwrite($NumFileFacturas, date("Ymd", FechaSistema()));
                    fwrite($NumFileFacturas, sprintf("%017d", $Saldo));
                    fwrite($NumFileFacturas, "00000000000000000");
                    fwrite($NumFileFacturas, "202");
                    fwrite($NumFileFacturas, "000000");
                    fwrite($NumFileFacturas, "00");
                    fwrite($NumFileFacturas, substr($TRep['CI_RUC'], 6, 4));
                    fwrite($NumFileFacturas, sprintf("%02d", $ContadorTarjeta));
                    fwrite($NumFileFacturas, "439473");
                    fwrite($NumFileFacturas, date("Ym", $TRep['Fecha_Cad']));
                    fwrite($NumFileFacturas, "00000000000000000");
                    fwrite($NumFileFacturas, "00D00000");
                    fwrite($NumFileFacturas, "000000000000000");
                    fwrite($NumFileFacturas, sprintf("%017d", $Saldo));
                    fwrite($NumFileFacturas, "\n");
                    $Total_Banco = $Total_Banco + $Total;
                }
            }
        }
        fclose($NumFileFacturas);
        return array(
            'LabelAbonos' => sprintf("%02d", $Total_Banco),
            'Mensaje' => "SE HA GENERADO EL SIGUIENTE ARCHIVO \n" . basename($RutaGeneraFile),
            'CantArchivos' => 1,
            'TxtFile' => " ",
            'Nombre1' => basename($RutaGeneraFile)
        );
    }

    public function Generar_Produbanco($parametros, $AdoFactura)
    {
        //$AdoAux = $this->modelo->AdoAux($parametros);
        $Total_Banco = 0;
        $Mifecha = BuscarFecha($parametros['MBFechaI']);
        $MiMes = date("m", strtotime($parametros['MBFechaI']));
        $FechaFin = BuscarFecha(date("y-m-t", strtotime($parametros['MBFechaI'])));
        $TextoImprimio = "";
        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $Cta_Cobrar = G_NINGUNO;
        $FechaTexto = FechaSistema();
        $EsComa = true;
        $Estab = false;
        $Contador = 0;
        $Factura_No = 0;
        $Fecha_Meses = $parametros['MBFechaI'] . " al " . $parametros['MBFechaF'];
        $Fecha_Meses = str_replace("/", "-", $Fecha_Meses);
        $RutaGeneraFile = dirname(__DIR__, 3) . '/TEMP/BANCO/FACTURAS/RECAUDACION ' . $Fecha_Meses . '.TXT';
        $NumFileFacturas = fopen($RutaGeneraFile, "w"); //Abre el archivo
        if (count($AdoFactura) > 0) {
            foreach ($AdoFactura as $key => $value) {
                $Contador++;
                $GrupoNo = $value['Grupo'];
                $CodigoCli = $value['Codigo'];
                $NombreCliente = trim(substr(Sin_Signos_Especiales($value['Cliente']), 0, 40));
                $Factura_No++;
                $Total = $value['Valor_Cobro'];
                $Saldo = $value['Valor_Cobro'] * 100;
                $ValorStr = (string) $Saldo;
                $ValorStr = str_repeat("0", 13 - strlen($ValorStr)) . $ValorStr;
                $CodigoP = $value['CI_RUC'];
                $CodigoC = $value['CI_RUC'];
                $CodigoC = $CodigoC . str_repeat(" ", max(4 - strlen($CodigoC), 0));
                $DireccionCli = Sin_Signos_Especiales($value['Direccion']);
                $Codigo3 = SinEspaciosDer2($DireccionCli);
                $DireccionCli = trim(substr($DireccionCli, 0, strlen($DireccionCli) - strlen($Codigo3)));
                $Codigo3 = trim(SinEspaciosDer2($DireccionCli));
                $Codigo1 = sprintf("%02d", substr($GrupoNo, 0, 1));
                $Codigo4 = "";
                if (strlen($parametros['Cta_Bancaria']) < 11) {
                    $parametros['Cta_Bancaria'] = str_repeat("0", 11 - strlen($parametros['Cta_Bancaria'])) . $parametros['Cta_Bancaria'];
                }
                if ($parametros['Tipo_Carga'] == 3) {
                    $Codigo4 = "Transporte " . $value['Grupo'];
                } else {
                    $Codigo4 = "Transporte " . $value['Grupo'] . " De: " . $value['Periodo'] . "-" . MesesLetras($value['Num_Mes']);
                }
                fwrite($NumFileFacturas, "CO" . "\t");
                fwrite($NumFileFacturas, $parametros['Cta_Bancaria'] . "\t");
                fwrite($NumFileFacturas, $Contador . "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, $CodigoP . "\t");
                fwrite($NumFileFacturas, "USD" . "\t");
                fwrite($NumFileFacturas, $ValorStr . "\t");
                fwrite($NumFileFacturas, "REC" . "\t");
                fwrite($NumFileFacturas, $_SESSION['INGRESO']['CodigoDelBanco'] . "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, "C" . "\t");
                fwrite($NumFileFacturas, $CodigoP . "\t");
                fwrite($NumFileFacturas, $NombreCliente . "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, $Codigo4 . "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, "\t");
                fwrite($NumFileFacturas, "\n");
            }
        }
        fclose($NumFileFacturas);
        return array(
            'LabelAbonos' => sprintf("%02d", $Total_Banco),
            'Mensaje' => "SE HA GENERADO EL SIGUIENTE ARCHIVO \n" . basename($RutaGeneraFile),
            'CantArchivos' => 1,
            'TxtFile' => " ",
            'Nombre1' => basename($RutaGeneraFile)
        );
    }

    public function Generar_Guayaquil($parametros, $AdoFactura)
    {
        $RutaGeneraFile = dirname(__DIR__, 3) . '/TEMP/BANCO/FACTURAS/RCE_' . FechaSistema() . '_' . sprintf("%07d", intval($_SESSION['INGRESO']['CodigoDelBanco'])) . '_01.txt';
        $TipoDoc = "0";
        $Contador = 0;
        $FechaTexto = BuscarFecha($parametros['MBFechaF']);
        $TxtFile = "";
        $NumFileFacturas = fopen($RutaGeneraFile, "w"); //Abre el archivo
        if (count($AdoFactura) > 0) {
            $TxtFile = "TOTAL NOMINA DE RECAUDACION:" . "\n";
            $Codigo3 = trim(substr($_SESSION['INGRESO']['item'], 0, 30));
            $Total = 0;
            $TotalIngreso = 0;
            $Total_Factura = 0;
            $IE = 1;
            $JE = 1;
            $KE = 1;
            $Grupo_No = $AdoFactura[0]['Grupo'];
            $Codigo = $AdoFactura[0]['Codigo'];
            foreach ($AdoFactura as $key => $value) {
                if ($Grupo_No <> $value['Grupo']) {
                    $Codigo4 = number_format($Total, 2, '.', ',');
                    $Codigo4 = str_repeat(" ", 13 - strlen($Codigo4)) . number_format($Total, 2, '.', ',');
                    $TxtFile .= "Grupo: " . $Grupo_No . "\t" . "Resumen de Registros por Grupo: " . $JE . "\t" . "Total a Recaudar USD" . "\t" . $Codigo4 . "\n";
                    $JE = 0;
                    $Total = 0;
                    $IE++;
                    $Grupo_No = $value['Grupo'];
                    $Codigo = $value['Codigo'];
                }
                if ($Codigo <> $value['Codigo']) {
                    $JE++;
                    $KE++;
                    $Codigo = $value['Codigo'];
                }
                $Contador++;
                $CodigoCli = $value['CI_RUC'];
                $NombreCliente = Sin_Signos_Especiales(trim(substr($value['Cliente'], 0, 40)));
                $Codigo1 = trim(substr($value['Direccion'], 0, 30));
                $FechaTexto = " " . substr(MesesLetras(date("m", strtotime($parametros['MBFechaI']))), 0, 3) . " " . date("Y", strtotime($parametros['MBFechaI']));
                $Codigo2 = substr(strtoupper(substr(MesesLetras(date("m", $value['Fecha']->getTimestamp())), 0, 3)) . " " . $value['Grupo'], 0, 15);
                $Total_Factura = $value['Valor_Cobro'];
                if ($parametros['Costo_Banco'] > 0) {
                    $Total_Factura = $Total_Factura + $parametros['Costo_Banco'];
                }
                $Total = $Total + $Total_Factura;
                $TotalIngreso = $TotalIngreso + $Total_Factura;
                $I = intval($Total_Factura);
                $J = ($Total_Factura - intval($Total_Factura)) * 100;

                fwrite($NumFileFacturas, "CO");
                fwrite($NumFileFacturas, sprintf("%07d", $Contador));
                fwrite($NumFileFacturas, $CodigoCli . str_repeat(" ", 15 - strlen($CodigoCli)));
                fwrite($NumFileFacturas, "USD");
                fwrite($NumFileFacturas, sprintf("%08d", $I) . sprintf("%02d", $J));
                fwrite($NumFileFacturas, "REC");
                fwrite($NumFileFacturas, $NombreCliente . str_repeat(" ", 40 - strlen($NombreCliente)));
                fwrite($NumFileFacturas, date("Ym", $value['Fecha']->getTimestamp()));
                fwrite($NumFileFacturas, "CU");
                fwrite($NumFileFacturas, "PA");
                fwrite($NumFileFacturas, "ES");
                fwrite($NumFileFacturas, $Codigo2 . str_repeat(" ", 15 - strlen($Codigo2)));
                fwrite($NumFileFacturas, "\n");

                fwrite($NumFileFacturas, "RC");
                fwrite($NumFileFacturas, sprintf("%07d", $Contador));
                fwrite($NumFileFacturas, "VM");
                fwrite($NumFileFacturas, date("Ymd", strtotime($parametros['MBFechaI'])));
                fwrite($NumFileFacturas, date("Ymd", strtotime($parametros['MBFechaV'])));
                fwrite($NumFileFacturas, "FI");
                fwrite($NumFileFacturas, str_repeat("0", 30));
                fwrite($NumFileFacturas, "\n");
            }
            $Codigo4 = number_format($Total, 2, '.', ',');
            $Codigo4 = str_repeat(" ", 13 - strlen($Codigo4)) . number_format($Total, 2, '.', ',');
            $TxtFile .= "Grupo: " . $Grupo_No . "\t" . "Resumen de Registros por Grupo: " . $JE . "\t" . "Total a Recaudar USD" . "\t" . $Codigo4 . "\n";
            $Codigo4 = number_format($TotalIngreso, 2, '.', ',');
            $Codigo4 = str_repeat(" ", 13 - strlen($Codigo4)) . number_format($TotalIngreso, 2, '.', ',');
            $TxtFile .= "Total Grupos: " . $IE . "\t" . "Total Alumnos:" . $KE . "\t" . "\t" . "Total a Recuadar USD" . "\t" . $Codigo4 . "\n";

        }
        fclose($NumFileFacturas);
        return array(
            'CantArchivos' => 1,
            'Mensaje' => "Fin del proceso se generó el siguiente registro: " . basename($RutaGeneraFile),
            'TxtFile' => $TxtFile,
            'Nombre1' => basename($RutaGeneraFile)
        );
    }

    public function Generar_Bolivariano($parametros, $AdoFactura, $RutaDelArchivo)
    {
        $RutaGeneraFile = $RutaDelArchivo;
        $TipoDoc = "01";
        $Contador = 0;
        $FechaTexto = BuscarFecha($parametros['MBFechaF']);
        $FechaTexto1 = date("m/d/y", strtotime($parametros['MBFechaV']));
        $NumFileFacturas = fopen($RutaGeneraFile, "w"); //Abre el archivo

        if (count($AdoFactura) > 0) {
            fwrite($NumFileFacturas, "999");
            fwrite($NumFileFacturas, $_SESSION['INGRESO']['CodigoDelBanco']);
            fwrite($NumFileFacturas, $TipoDoc);
            fwrite($NumFileFacturas, str_repeat(" ", 11));
            fwrite($NumFileFacturas, $FechaTexto1);
            fwrite($NumFileFacturas, "\n");
            foreach ($AdoFactura as $key => $value) {
                $SaldoPendiente = 0;
                $Total_Factura = 0;
                $Monto_Total = 0;
                $Total = 0;
                $CodigoCli = $value['CI_RUC'];
                $Codigo = "0";
                for ($i = 1; $i <= strlen($value['CI_RUC']); $i++) {
                    if (is_numeric(substr($value['CI_RUC'], $i - 1, 1))) {
                        $Codigo .= substr($value['CI_RUC'], $i - 1, 1);
                    }
                }
                $Codigo = trim((string) (intval($Codigo)));
                $Codigo .= str_repeat(" ", max(15 - strlen($Codigo), 0));
                $NombreCliente = $this->SetearBlancos(substr($value['Cliente'], 0, 30), 30, 0, false);
                $Codigo1 = trim(substr(SinEspaciosIzq($value['Direccion']), 0, 15));
                $Codigo3 = trim(substr(SinEspaciosDer2($value['Direccion']), 0, 3));
                $Codigo2 = trim(substr($value['Direccion'], strlen($Codigo1), strlen($value['Direccion'])));
                $Codigo4 = substr($value['Casilla'], 0, 10);
                $Saldo_Me = 0;
                $Total_Desc = 0;
                $SaldoPendiente = 0;
                $Total_Factura = $value['Valor_Cobro'];
                $Monto_Total = $Total_Factura;
                $SaldoPendiente = $Total_Factura;
                $Total = $Total_Factura;
                if ($Codigo1 == "") {
                    $Codigo1 = G_NINGUNO;
                }
                if ($Codigo2 == "") {
                    $Codigo2 = G_NINGUNO;
                }
                if ($Codigo3 == "") {
                    $Codigo3 = G_NINGUNO;
                }
                $Codigo2 = trim(substr($Codigo2, 0, strlen($Codigo2) - strlen(SinEspaciosDer2($Codigo2))));
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
                fwrite($NumFileFacturas, $_SESSION['INGRESO']['CodigoDelBanco']);
                fwrite($NumFileFacturas, $Codigo);
                fwrite($NumFileFacturas, date("m/d/y", strtotime($parametros['MBFechaI'])));
                fwrite($NumFileFacturas, $TipoDoc . "  ");
                fwrite($NumFileFacturas, sprintf("%010.2f", $Total));
                fwrite($NumFileFacturas, date("m/d/y", strtotime($parametros['MBFechaV'])));
                fwrite($NumFileFacturas, "01/01/1900");
                fwrite($NumFileFacturas, "N");
                fwrite($NumFileFacturas, rtrim(ltrim(Sin_Signos_Especiales($NombreCliente))));
                fwrite($NumFileFacturas, $Codigo2);
                fwrite($NumFileFacturas, $Codigo3);
                fwrite($NumFileFacturas, $Codigo1);
                fwrite($NumFileFacturas, sprintf("%010.2f", $Monto_Total));
                fwrite($NumFileFacturas, $Codigo4);
                fwrite($NumFileFacturas, "1");
                fwrite($NumFileFacturas, sprintf("%010.2f", $Total));
                fwrite($NumFileFacturas, sprintf("%010.2f", $Total));
                fwrite($NumFileFacturas, "\n");
                $Contador = $Contador + 1;
            }
        }
        fclose($NumFileFacturas);

        return array(
            'CantArchivos' => 1,
            'TxtFile' => " ",
            'Mensaje' => "Fin del proceso se generó el siguiente archivo: \n" . basename($RutaGeneraFile),
            'Nombre1' => basename($RutaGeneraFile)
        );
    }

    public function Genera_Internacional($parametros, $AdoFactura)
    {

        $Total_Banco = 0;
        $Mifecha = BuscarFecha($parametros['MBFechaI']);
        $MiMes = date("m", strtotime($parametros['MBFechaI']));
        $FechaFin = BuscarFecha(date("y-m-t", strtotime($parametros['MBFechaI'])));
        $TextoImprimio = "";
        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $Cta_Cobrar = G_NINGUNO;
        $FechaTexto = FechaSistema();
        //Comenzamos a generar el archivo: Internacional Recaudaciones.TXT
        $EsComa = true;
        $Estab = false;
        $Contador = 0;
        $Factura_No = 0;
        $Fecha_Meses = $parametros['MBFechaI'] . " al " . $parametros['MBFechaF'];
        $Fecha_Meses = str_replace("/", "-", $Fecha_Meses);

        $ArchivoTexto = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/Internacional Recaudaciones " . $Fecha_Meses . ".txt";
        $ArchivoExcel = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/Internacional Recaudaciones " . $Fecha_Meses . ".xls";
        $NombreArchivos = $ArchivoTexto . "\n" . "\n";
        $tmp = $ArchivoTexto;

        $datosExcel = [];
        $tmpContador = 1;

        $NumFileFacturas = fopen($ArchivoTexto, "w"); //Abre el archivo

        if (count($AdoFactura) > 0) {
            foreach ($AdoFactura as $key => $value) {
                $CodigoCli = $value['Codigo'];
                $Grupo_No = $value['Grupo'];
                $NombreCliente = Sin_Signos_Especiales($value['Cliente']);
                $Producto = Sin_Signos_Especiales($value['Producto']);
                $CodigoInv = $value['Codigo_Inv'];
                $NoMes = $value['Num_Mes'];
                $Mes = sprintf("%02d", $NoMes);
                $Periodo = $value['Periodo'];
                $Codigo2 = $Periodo . " " . $Mes . " " . $CodigoInv . str_repeat(" ", max(20 - strlen($CodigoInv), 0)) . " " . $Grupo_No . str_repeat(" ", max(10 - strlen($Grupo_No), 0)) . " " . $Producto;

                $Factura_No = intval($value['Periodo'] . sprintf("%02d", $value['Num_Mes']));
                $Total = $value['Valor_Cobro'];
                $Saldo = $value['Valor_Cobro'] * 100;
                $CodigoP = $value['CI_RUC'];
                $CodigoC = $value['CI_RUC'];
                $CodigoC = $CodigoC . str_repeat(" ", max(4 - strlen($CodigoC), 0));
                $DireccionCli = Sin_Signos_Especiales($value['Direccion']);
                $Codigo3 = SinEspaciosDer2($DireccionCli);
                $DireccionCli = trim(substr($DireccionCli, 0, strlen($DireccionCli) - strlen($Codigo3)));
                $Codigo3 = trim(SinEspaciosDer2($DireccionCli));
                $Codigo1 = sprintf("%02d", substr($Grupo_No, 0, 1));
                $Codigo4 = "";

                $CadAux = "Pensión Acumulada";
                if (strlen($value['Email2']) > 3) {
                    $CadAux .= ";" . $value['Email2'];
                }
                if ($parametros['Tipo_Carga'] == 2) {
                    $Codigo4 = "RECAUDACIONES";
                } else {
                    if ($parametros['CheqMatricula']) {
                        $Codigo4 = "MATRICULAS Y PENSION DE " . substr(MesesLetras(date("m", strtotime($parametros['MBFechaI']))), 0, 3) . "-" . date("Y", strtotime($parametros['MBFechaI']));
                    } else {
                        if ($parametros['Tipo_Carga'] == 3) {
                            $Codigo4 = "PENSION ACUMULADA";
                        } else {
                            $Codigo4 = "PENSION ACUMULADA DE " . substr(MesesLetras(date("m", $value['Fecha']->getTimestamp())), 0, 3) . "-" . date("Y", $value['Fecha']->getTimestamp());
                        }
                    }
                }
                if (strlen($value['Email2']) > 3) {
                    $Codigo4 .= "|" . $value['Email2'] . ";";
                }
                if (strlen($value['Actividad']) < 3) {
                    $Contador = $Contador + 1;
                    fwrite($NumFileFacturas, "CO" . "\t");
                    fwrite($NumFileFacturas, $parametros['Cta_Banco'] . "\t");
                    fwrite($NumFileFacturas, $Contador . "\t");
                    fwrite($NumFileFacturas, sprintf("%010d", $Factura_No) . "\t");
                    fwrite($NumFileFacturas, $CodigoP . "\t");
                    fwrite($NumFileFacturas, "USD" . "\t");
                    fwrite($NumFileFacturas, sprintf("%013d", $Saldo) . "\t");
                    fwrite($NumFileFacturas, "REC" . "\t");
                    fwrite($NumFileFacturas, "32" . "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "0" . "\t");
                    fwrite($NumFileFacturas, $value['TD_R'] . "\t");
                    fwrite($NumFileFacturas, $value['CI_RUC_R'] . "\t");
                    fwrite($NumFileFacturas, substr(date("m", $value['Fecha']->getTimestamp()) . " " . $NombreCliente, 0, 40) . "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, $Codigo2 . "\t");
                    fwrite($NumFileFacturas, $Codigo4 . "\t");
                    fwrite($NumFileFacturas, "\n");

                    $datosExcel[$tmpContador] = [
                        "CO", 
                        $parametros['Cta_Banco'],
                        $Contador,
                        sprintf("%010d", $Factura_No),
                        $CodigoP,
                        "USD",
                        sprintf("%013d", $Saldo),
                        "REC",
                        "32",
                        "0",
                        "0",
                        $value['TD_R'],
                        $value['CI_RUC_R'],
                        substr(date("m", $value['Fecha']->getTimestamp()) . " " . $NombreCliente, 0, 40),
                        "0",
                        "0",
                        "0",
                        "0",
                        $Codigo2,
                        $Codigo4
                    ];
                    $tmpContador++;

                }
            }
        }
        fclose($NumFileFacturas);
        excel_simple($datosExcel, $ArchivoExcel, "Internacional Recaudaciones");

        $ArchivoTexto = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/Internacional Debitos " . $Fecha_Meses . ".txt";
        $ArchivoExcel2 = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/Internacional Debitos " . $Fecha_Meses . ".xls";
        $NombreArchivos .= $ArchivoTexto . "\n" . "\n";
        $tmp2 = $ArchivoTexto;

        $datosExcel = [];
        $tmpContador = 1;

        $NumFileFacturas = fopen($ArchivoTexto, "w");
        $Contador = 0;
        $Mes = date("m", strtotime($parametros['MBFechaI']));
        $Anio = intval(substr(date("Y", strtotime($parametros['MBFechaI'])), 1, 3));
        $Dia = "15";

        if (count($AdoFactura) > 0) {
            foreach ($AdoFactura as $key => $value) {
                $CodigoCli = $value['Codigo'];
                $GrupoNo = $value['Grupo'];
                $NombreCliente = Sin_Signos_Especiales($value['Cliente']);
                $Producto = Sin_Signos_Especiales($value['Producto']);
                $CodigoInv = $value['Codigo_Inv'];
                $NoMes = $value['Num_Mes'];
                $Mes = sprintf("%02d", $NoMes);
                $Periodo = $value['Periodo'];
                $Codigo2 = $Periodo . " " . $Mes . " " . $CodigoInv . str_repeat(" ", max(20 - strlen($CodigoInv), 0)) . " " . $GrupoNo . str_repeat(" ", max(10 - strlen($GrupoNo), 0)) . " " . $Producto;
                $TipoCta = G_NINGUNO;
                switch ($value['Tipo_Cta']) {
                    case "AHORROS":
                        $TipoCta = "AHO";
                        break;
                    case "CORRIENTE":
                        $TipoCta = "CTE";
                        break;
                }
                if ($value['Cod_Banco'] > 0 && strlen($value['Cta_Numero']) > 1 && $value['Cod_Banco'] == intval($parametros['TxtCodBanco']) && $TipoCta <> G_NINGUNO) {
                    $Contador = $Contador + 1;
                    $Factura_No = intval($value['Periodo'] . sprintf("%02d", $value['Num_Mes']));
                    $Total = $value['Valor_Cobro'];
                    $Saldo = $value['Valor_Cobro'] * 100;
                    $CodigoP = $value['CI_RUC'];
                    $DireccionCli = Sin_Signos_Especiales($value['Direccion']);
                    $Codigo3 = SinEspaciosDer2($DireccionCli);
                    $DireccionCli = trim(substr($DireccionCli, 0, strlen($DireccionCli) - strlen($Codigo3)));
                    $Codigo3 = trim(SinEspaciosDer2($DireccionCli));
                    $Codigo1 = sprintf("%02d", substr($GrupoNo, 0, 1));
                    $Codigo4 = "";
                    if ($parametros['Tipo_Carga'] == 2) {
                        $Codigo4 = "DEBITOS AUTOMATICOS";
                    } else {
                        if ($parametros['CheqMatricula']) {
                            $Codigo4 = "MATRICULAS Y PENSION DE " . substr(MesesLetras(date("m", strtotime($parametros['MBFechaI']))), 0, 3) . "-" . date("Y", strtotime($parametros['MBFechaI']));
                        } else {
                            if ($parametros['Tipo_Carga'] == 3) {
                                $Codigo4 = "PENSION ACUMULADA";
                            } else {
                                $Codigo4 = "PENSION ACUMULADA DE " . substr(MesesLetras(date("m", strtotime($value['Fecha']))), 0, 3) . "-" . date("Y", strtotime($value['Fecha']));
                            }
                        }
                    }
                    if (strlen($value['Email2']) > 3) {
                        $Codigo4 .= "|" . $value['Email2'] . ";";
                    }

                    fwrite($NumFileFacturas, "CO" . "\t");
                    fwrite($NumFileFacturas, $parametros['Cta_Banco'] . "\t");
                    fwrite($NumFileFacturas, $Contador . "\t");
                    fwrite($NumFileFacturas, sprintf("%013d", $Factura_No) . "\t");
                    fwrite($NumFileFacturas, $CodigoP . "\t");
                    fwrite($NumFileFacturas, "USD" . "\t");
                    fwrite($NumFileFacturas, sprintf("%013d", $Saldo) . "\t");
                    fwrite($NumFileFacturas, "CTA" . "\t");
                    fwrite($NumFileFacturas, $value['Cod_Banco'] . "\t");
                    fwrite($NumFileFacturas, $TipoCta . "\t");
                    fwrite($NumFileFacturas, $value['Cta_Numero'] . "\t");
                    fwrite($NumFileFacturas, $value['TD_R'] . "\t");
                    fwrite($NumFileFacturas, $value['CI_RUC_R'] . "\t");
                    fwrite($NumFileFacturas, substr(date("m", strtotime($value['Fecha'])) . " " . $NombreCliente, 0, 40) . "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, $Codigo2 . "\t");
                    fwrite($NumFileFacturas, $Codigo4 . "\t");
                    fwrite($NumFileFacturas, sprintf("%013d", $Saldo) . "\t");
                    fwrite($NumFileFacturas, "0" . "\t");
                    fwrite($NumFileFacturas, "0" . "\t");
                    fwrite($NumFileFacturas, "0" . "\t");
                    fwrite($NumFileFacturas, "0" . "\t");
                    fwrite($NumFileFacturas, "0" . "\t");
                    fwrite($NumFileFacturas, "0" . "\t");
                    fwrite($NumFileFacturas, "\n");

                    $datosExcel[$tmpContador] = [
                        "CO", 
                        $parametros['Cta_Banco'],
                        $Contador,
                        sprintf("%013d", $Factura_No),
                        $CodigoP,
                        "USD",
                        sprintf("%013d", $Saldo),
                        "CTA",
                        $value['Cod_Banco'],
                        $TipoCta,
                        $value['Cta_Numero'],
                        $value['TD_R'],
                        $value['CI_RUC_R'],
                        substr(date("m", strtotime($value['Fecha'])) . " " . $NombreCliente, 0, 40),
                        "0",
                        "0",
                        "0",
                        "0",
                        $Codigo2,
                        $Codigo4,
                        sprintf("%013d", $Saldo),
                        "0",
                        "0",
                        "0",
                        "0",
                        "0",
                        "0"
                    ];

                }
            }
        }
        
        fclose($NumFileFacturas);
        excel_simple($datosExcel, $ArchivoExcel2, "Internacional Debitos");
        return array(
            'LabelAbonos' => sprintf("%02d", $Total_Banco),
            'CantArchivos' => 4,
            'TxtFile' => " ",
            'Mensaje' => "SE GENERARON LOS SIGUIENTES ARCHIVOS: \n" . basename($tmp) . "\n" . basename($tmp2),
            'Nombre1' => basename($tmp),
            'Nombre2' => basename($tmp2),
            'Nombre3' => basename($ArchivoExcel),
            'Nombre4' => basename($ArchivoExcel2),
        );
    }

    public function Genera_Pacifico($parametros, $RutaDelArchivo, $AdoFactura)
    {
        $RutaGeneraFile = $RutaDelArchivo;
        $TipoDoc = "0";
        $Contador = 0;
        $FechaTexto = BuscarFecha($parametros['MBFechaI']);
        $TxtFile = "";
        $NumFileFacturas = fopen($RutaGeneraFile, "w"); //Abre el archivo
        if (count($AdoFactura) > 0) {
            $TxtFile .= "TOTAL NOMINA DE RECAUDACION: " . "\n";
            $Codigo3 = trim(substr($_SESSION['INGRESO']['noempr'], 0, 3));
            $Total = 0;
            $TotalIngreso = 0;
            $Total_Factura = 0;
            $IE = 1;
            $JE = 1;
            $KE = 1;
            $Grupo_No = $AdoFactura[0]['Grupo'];
            $Codigo = $AdoFactura[0]['Codigo'];
            foreach ($AdoFactura as $key => $value) {
                if ($Grupo_No <> $value['Grupo']) {
                    $Codigo4 = number_format($Total, 2, '.', ',');
                    $Codigo4 = str_repeat(" ", max(13 - strlen($Codigo4), 0)) . number_format($Total, 2, '.', ',');
                    $TxtFile .= "Grupo: " . $Grupo_No . "\t" . "Resumen de Registros por Grupo: " . $JE . "\t" .
                        "Total a Recaudar USD" . "\t" . $Codigo4 . "\n";
                    $JE = 0;
                    $Total = 0;
                    $IE = $IE + 1;
                    $Grupo_No = $value['Grupo'];
                    $Codigo = $value['Codigo'];
                }
                if ($Codigo <> $value['Codigo']) {
                    $JE = $JE + 1;
                    $KE = $KE + 1;
                    $Codigo = $value['Codigo'];
                }
                $Contador = $Contador + 1;
                $CodigoCli = $value['CI_RUC'];
                $NombreCliente = trim(substr($value['Cliente'], 0, 30));
                $Codigo1 = trim(substr($value['Direccion'], 0, 30));
                $FechaTexto = " " . strtoupper(substr(MesesLetras($value['Num_Mes']), 0, 3)) . " " . substr($value['Periodo'], 2, 2);
                $Codigo2 = strtoupper($value['Grupo']) . " " . strtoupper(substr(MesesLetras($value['Num_Mes']), 0, 3)) . " " . $value['Periodo'];
                $Total_Factura = $value['Valor_Cobro'];
                $Total = $Total + $Total_Factura;
                $I = intval($Total_Factura);
                $J = ($Total_Factura - intval($Total_Factura)) * 100;
                fwrite($NumFileFacturas, "1"); // Localidad
                fwrite($NumFileFacturas, "OCP"); // Transsaccion
                fwrite($NumFileFacturas, "OC"); // Codigo de Servicio
                fwrite($NumFileFacturas, "  "); // Tipo de Cuenta
                fwrite($NumFileFacturas, str_repeat(" ", 8)); // Numero de Cuenta
                fwrite($NumFileFacturas, sprintf("%013d", $I) . sprintf("%02d", $J)); // Valor
                fwrite($NumFileFacturas, $CodigoCli . str_repeat(" ", max(15 - strlen($CodigoCli), 0))); // Codigo del Alumno : FechaTexto
                fwrite($NumFileFacturas, $Codigo2 . str_repeat(" ", 20 - strlen($Codigo2))); // Referencia
                fwrite($NumFileFacturas, "RE"); // Forma de Pago
                fwrite($NumFileFacturas, "USD"); // Moneda
                fwrite($NumFileFacturas, $NombreCliente . str_repeat(" ", max(30 - strlen($NombreCliente), 0))); // Nombre del Alumno
                fwrite($NumFileFacturas, "01"); // Localidad
                fwrite($NumFileFacturas, str_repeat(" ", 2)); // Agencia de Retiro
                fwrite($NumFileFacturas, " "); // Tipo NUC
                fwrite($NumFileFacturas, $CodigoCli . str_repeat(" ", max(14 - strlen($CodigoCli), 0))); // Numero Unico del Beneficiario
                fwrite($NumFileFacturas, str_repeat(" ", 10)); // Telefono
                fwrite($NumFileFacturas, " "); // NUC Ordenante
                fwrite($NumFileFacturas, str_repeat(" ", 14)); // Numero Unico del Ordenante
                fwrite($NumFileFacturas, $Codigo3 . str_repeat(" ", max(30 - strlen($Codigo3), 0))); // Nombre Colegio
                fwrite($NumFileFacturas, sprintf("%06d", $Contador)); // Secuencial
                fwrite($NumFileFacturas, "30"); // Banco del Pacifico
                fwrite($NumFileFacturas, str_repeat(" ", 20)); // Numero de cuenta
                fwrite($NumFileFacturas, "\n");
            }
            $Codigo4 = number_format($Total, 2, '.', ',');
            $Codigo4 = str_repeat(" ", max(13 - strlen($Codigo4), 0)) . number_format($Total, 2, '.', ',');
            $TxtFile .= "Grupo: " . $Grupo_No . "\t" . "Resumen de Registros por Grupo: " . $JE . "\t" .
                "Total a Recaudar USD" . "\t" . $Codigo4 . "\n";
            $Codigo4 = number_format($TotalIngreso, 2, '.', ',');
            $Codigo4 = str_repeat(" ", max(13 - strlen($Codigo4), 0)) . number_format($TotalIngreso, 2, '.', ',');
            $TxtFile .= str_repeat("-", 90) . "\n" .
                "Total Grupos: " . $IE . "\t" . "Total Alumnos: " . $KE . "\t" . "\t" . "Total a Recaudar USD" . "\t" . $Codigo4 . "\n";
        }
        fclose($NumFileFacturas);
        return array(
            'TxtFile' => $TxtFile,
            'Mensaje' => "Fin del proceso se generó el siguiente archivo: \n" . basename($RutaGeneraFile),
            'CantArchivos' => 1,
            'Nombre1' => basename($RutaGeneraFile)
        );

    }

    public function Generar_Pichincha($parametros, $AdoFactura)
    {
        $Total_Banco = 0;
        $Mifecha = BuscarFecha($parametros['MBFechaI']);
        $MiMes = date("m", strtotime($parametros['MBFechaI']));
        $FechaFin = BuscarFecha(date('Y-m-t', strtotime($parametros['MBFechaI'])));
        $TextoImprimio = "";
        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $Cta_Cobrar = G_NINGUNO;
        $FechaTexto = FechaSistema();
        //Comenzamos a generar el archivo: SCRECXX.TXT
        $EsComa = true;
        $Estab = false;
        $Contador = 0;
        $Factura_No = 0;
        $Fecha_Meses = $parametros['MBFechaI'] . " al " . $parametros['MBFechaF'];
        $Fecha_Meses = str_replace("/", "-", $Fecha_Meses);

        $Mes = date("m", strtotime($parametros['MBFechaI']));
        $Anio = intval(substr(date("Y", strtotime($parametros['MBFechaI'])), 1, 3));
        $Dia = "15";

        $RutaGeneraFile = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/SCREC " . $Fecha_Meses . ".TXT";
        $NombreFile = "SCREC " . $Fecha_Meses . ".TXT";
        $NumFileFacturas1 = fopen($RutaGeneraFile, "w"); //Abre el archivo

        $RutaGeneraFile2 = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/SCCOB " . $Fecha_Meses . ".TXT";
        $NombreFile2 = "SCCOB " . $Fecha_Meses . ".TXT";
        $NumFileFacturas2 = fopen($RutaGeneraFile2, "w"); //Abre el archivo

        if (count($AdoFactura) > 0) {
            foreach ($AdoFactura as $key => $value) {
                $Contador++;
                $CodigoCli = $value['Codigo'];
                $GrupoNo = $value['Grupo'];
                $NombreCliente = Sin_Signos_Especiales($value['Cliente']);
                $Factura_No = $Factura_No + 1;
                $Total = $value['Valor_Cobro'];
                $Saldo = $value['Valor_Cobro'] * 100;
                $CodigoP = $value['CI_RUC'];
                $CodigoC = intval($value['CI_RUC']);
                $CodigoC = $CodigoC . str_repeat(" ", max(0, 4 - strlen($CodigoC)));
                $DireccionCli = Sin_Signos_Especiales($value['Direccion']);
                $Codigo3 = trim(SinEspaciosDer2($DireccionCli));
                $DireccionCli = trim(substr($DireccionCli, 0, strlen($DireccionCli) - strlen($Codigo3)));
                $Codigo3 = trim(SinEspaciosDer2($DireccionCli));
                $Codigo1 = sprintf("%02d", substr($GrupoNo, 0, 1));
                $Codigo4 = substr($value['Codigo_Inv'] . " " . $value['Producto'], 0, 33);
                $Codigo4 = $Codigo4 . str_repeat(" ", max(0, 33 - strlen($Codigo4)))
                    . sprintf("%02d", $value["Num_Mes"]) . " "
                    . $value["Periodo"];

                $Tabulador = "\t";

                if (strlen($value['Actividad']) >= 3) {
                    fwrite($NumFileFacturas2, "CO" . $Tabulador);
                    fwrite($NumFileFacturas2, $parametros['Cta_Bancaria'] . $Tabulador);
                    fwrite($NumFileFacturas2, $Contador . $Tabulador);
                    fwrite($NumFileFacturas2, sprintf("%010d", $Factura_No) . $Tabulador);
                    fwrite($NumFileFacturas2, $CodigoP . $Tabulador);
                    fwrite($NumFileFacturas2, "USD" . $Tabulador);
                    fwrite($NumFileFacturas2, sprintf("%013d", $Saldo) . $Tabulador);
                    fwrite($NumFileFacturas2, "CTA" . $Tabulador);
                    fwrite($NumFileFacturas2, "10" . $Tabulador);
                    $NumStrg = SinEspaciosIzq($value['Actividad']);
                    if (strlen($NumStrg) === 3) {
                        fwrite($NumFileFacturas2, SinEspaciosIzq($value['Actividad']) . $Tabulador);
                        fwrite($NumFileFacturas2, SinEspaciosDer2($value['Actividad']) . $Tabulador);
                    } else {
                        fwrite($NumFileFacturas2, $Tabulador);
                        fwrite($NumFileFacturas2, $Tabulador);
                    }
                    fwrite($NumFileFacturas2, "R" . $Tabulador);
                    fwrite($NumFileFacturas2, $_SESSION['INGRESO']['RUC'] . $Tabulador);
                    fwrite($NumFileFacturas2, sprintf("%02d", $value['Num_Mes'] . " " . substr($NombreCliente, 0, 37)) . $Tabulador);
                    fwrite($NumFileFacturas2, $Tabulador);
                    fwrite($NumFileFacturas2, $Tabulador);
                    fwrite($NumFileFacturas2, $Tabulador);
                    fwrite($NumFileFacturas2, $Tabulador);
                    fwrite($NumFileFacturas2, date("m", $value['Fecha']->getTimestamp()) . $Tabulador);
                    fwrite($NumFileFacturas2, strtoupper($Codigo4) . $Tabulador);
                    fwrite($NumFileFacturas2, sprintf("%013d", $Saldo) . $Tabulador);
                    fwrite($NumFileFacturas2, "\n");
                } else {
                    if ($parametros['Tipo_Carga'] >= 1) {
                        //Tipo Gualaceo
                        fwrite($NumFileFacturas1, "CO" . $Tabulador);
                        fwrite($NumFileFacturas1, sprintf("%010d", $CodigoC) . $Tabulador);
                        fwrite($NumFileFacturas1, "USD" . $Tabulador);
                        fwrite($NumFileFacturas1, $Saldo . $Tabulador);
                        fwrite($NumFileFacturas1, "REC" . $Tabulador);
                        fwrite($NumFileFacturas1, $Tabulador);
                        fwrite($NumFileFacturas1, $Tabulador);
                        fwrite($NumFileFacturas1, strtoupper($Codigo4) . $Tabulador);
                        fwrite($NumFileFacturas1, "N" . $Tabulador);
                        fwrite($NumFileFacturas1, sprintf("%010d", intval($CodigoC)) . $Tabulador);
                        fwrite($NumFileFacturas1, sprintf("%02d", $value['Num_Mes']) . " " . substr($NombreCliente, 0, 37) . $Tabulador);
                        fwrite($NumFileFacturas1, "\n");
                    } else {
                        //Tipo General
                        fwrite($NumFileFacturas1, "CO" . $Tabulador);
                        fwrite($NumFileFacturas1, $parametros['Cta_Bancaria'] . $Tabulador);
                        fwrite($NumFileFacturas1, $Contador . $Tabulador);
                        fwrite($NumFileFacturas1, sprintf("%010d", $Factura_No) . $Tabulador);
                        fwrite($NumFileFacturas1, $CodigoP . $Tabulador);
                        fwrite($NumFileFacturas1, "USD" . $Tabulador);
                        fwrite($NumFileFacturas1, sprintf("%013d", $Saldo) . $Tabulador);
                        fwrite($NumFileFacturas1, "REC" . $Tabulador);
                        fwrite($NumFileFacturas1, "10" . $Tabulador);
                        fwrite($NumFileFacturas1, $Tabulador);
                        fwrite($NumFileFacturas1, "0" . $Tabulador);
                        fwrite($NumFileFacturas1, "R" . $Tabulador);
                        fwrite($NumFileFacturas1, $_SESSION['INGRESO']['RUC'] . $Tabulador);
                        fwrite($NumFileFacturas1, sprintf("%02d", $value['Num_Mes']) . " " . substr($NombreCliente, 0, 37) . $Tabulador);
                        fwrite($NumFileFacturas1, $Tabulador);
                        fwrite($NumFileFacturas1, $Tabulador);
                        fwrite($NumFileFacturas1, $Tabulador);
                        fwrite($NumFileFacturas1, $Tabulador);
                        fwrite($NumFileFacturas1, date("m", $value['Fecha']->getTimestamp()) . $Tabulador);
                        fwrite($NumFileFacturas1, strtoupper($Codigo4) . $Tabulador);
                        fwrite($NumFileFacturas1, sprintf("%013d", $Saldo) . $Tabulador);
                        fwrite($NumFileFacturas1, "\n");
                    }
                }

            }
        }
        fclose($NumFileFacturas1);
        fclose($NumFileFacturas2);
        return array(
            'LabelAbonos' => sprintf("%02d", $Total_Banco),
            'Mensaje' => "SE GENERARON LOS SIGUIENTES ARCHIVOS: \n" . $NombreFile . "\n" . $NombreFile2,
            'CantArchivos' => 2,
            'TxtFile' => " ",
            'Archivo1' => $RutaGeneraFile,
            'Archivo2' => $RutaGeneraFile2,
            'Nombre1' => $NombreFile,
            'Nombre2' => $NombreFile2
        );

    }

    function Command6_Click($parametros)
    {
        try {
            $DatInv = [];
            $Tipo_Pago = "01";
            $Total_Alumnos = 0;
            $Total_Ingreso = 0;
            $Factura_Desde = "0";
            $Factura_Hasta = "0";
            $FechaTexto = ".";
            $Cta_Banco = trim(strtoupper(SinEspaciosIzq($parametros['DCBanco'])));
            if (strlen($Cta_Banco) <= 1) {
                $Cta_Banco = $_SESSION['SETEOS']['Cta_Del_Banco'];
            }
            $AdoBanco = $this->modelo->DCBanco();
            if (count($AdoBanco) > 0) {
                foreach ($AdoBanco as $key => $value) {
                    if ($value['Codigo'] === $Cta_Banco) {
                        $Tipo_Pago = $value['Tipo_Pago'];
                        break;
                    }
                }
            }
            $parametros['FA']['Cod_CxC'] = $parametros['DCLinea'];
            $parametros['FA']['Tipo_PRN'] = "FM";
            $tmp = Lineas_De_CxC($parametros['FA']);
            $parametros['FA'] = $tmp['TFA'];
            $Contador = 0;
            $AdoFactura = $this->modelo->AdoFactura3();
            if (count($AdoFactura) > 0) {
                $Total_Alumnos = count($AdoFactura);
                $parametros['FA']['Imp_Mes'] = true;
                $Factura_Desde = ReadSetDataNum($parametros['FA']['TC'] . "_SERIE_" . $parametros['FA']['Serie'], true, false);
                $Factura_Hasta = $Factura_Desde;
                foreach ($AdoFactura as $key => $value) {
                    $Contador++;
                    $Total_Sin_IVA = 0;
                    $Total_Con_IVA = 0;
                    $Total_Desc = 0;
                    $Total_IVA = 0;
                    $Total_Servicio = 0;
                    //Forma de pago
                    $Codigo3 = G_NINGUNO;
                    $Codigo4 = G_NINGUNO;
                    $TotalAbonos = $value['Total_Abonos'];
                    $FechaTexto = $value['FECHA'];

                    $parametros['FA']['Factura'] = ReadSetDataNum($parametros['FA']['TC'] . "_SERIE_" . $parametros['FA']['Serie'], true, true);
                    $parametros['Factura_No'] = $parametros['FA']['Factura'];
                    $Factura_Hasta = $parametros['FA']['Factura'];

                    $CodigoCli = $value['Codigo_Cliente'];
                    $TBenef = Leer_Datos_Clientes($CodigoCli);

                    $NombreCliente = $TBenef['Cliente'];
                    Validar_Porc_IVA($FechaTexto);
                    $parametros['FA']['Porc_IVA'] = $parametros['PorcIva'];

                    $this->modelo->ClientesFacturacion($CodigoCli);

                    //Generar_Consulta_SQL "Facturacion Bancos " & CodigoCli, sSQL
                    //Detalle de la Factura
                    $AdoProducto = $this->modelo->AdoProducto2($CodigoCli);
                    if (count($AdoProducto) > 0) {
                        $Codigo2 = $AdoProducto[0]['Periodo'];
                        $SubCta = $AdoProducto[0]['TICKET'];
                        $Codigo3 = $AdoProducto[0]['CODIGO_L'];
                        $Codigo4 = $AdoProducto[0]['PRODUCTO'];
                        foreach ($AdoProducto as $key2 => $value2) {
                            $CodigoInv = $value2['Codigo_Inv'];
                            $Cod_Ok = Leer_Codigo_Inv($CodigoInv, FechaSistema());
                            $DatInv = $Cod_Ok['datos'];
                            $Abono = $value2['Valor'];
                            $Total_Desc_ME = $value2['Descuento'] + $value2['Descuento2'];
                            $Producto = $DatInv['Producto'];
                            if ($TotalAbonos >= ($Abono - $Total_Desc_ME)) {
                                SetAdoAddNew("Detalle_Factura");
                                SetAdoFields("T", "C"); //Cancelado
                                SetAdoFields("TC", $parametros['FA']['TC']);
                                SetAdoFields("Porc_IVA", $parametros['FA']['Porc_IVA']);
                                SetAdoFields("CodigoL", $parametros['FA']['Cod_CxC']);
                                SetAdoFields("CodigoC", $CodigoCli);
                                SetAdoFields("Factura", $parametros['Factura_No']);
                                SetAdoFields("Cantidad", 1);
                                SetAdoFields("Fecha", $FechaTexto);
                                SetAdoFields("Codigo", $CodigoInv);
                                SetAdoFields("Producto", $Producto);
                                SetAdoFields("Precio", $Abono);
                                SetAdoFields("Total_Desc", $Total_Desc_ME);
                                SetAdoFields("Total", $Abono);
                                SetAdoFields("CodigoU", $_SESSION['INGRESO']['CodigoU']);
                                SetAdoFields("Mes", $value2['Mes']);
                                SetAdoFields("Mes_No", $value2['Num_Mes']);
                                SetAdoFields("Ticket", $value2['Periodo']);
                                SetAdoFields("Ruta", $value2['GrupoNo']);
                                SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
                                SetAdoFields("Item", $_SESSION['INGRESO']['item']);
                                $SubTotal_IVA = 0;
                                if ($parametros['FA']['TC'] === "NV") {
                                    $Total_Sin_IVA = $Total_Sin_IVA + $Abono;
                                    SetAdoFields("Cta_Venta", 0); //DatInv.Cta_Ventas_0
                                } else {
                                    if($DatInv['IVA']){
                                        $SubTotal_IVA = round($Abono * floatval($parametros['PorcIva'] / 100));
                                        $Total_Con_IVA = $Total_Con_IVA + $Abono;
                                    }else{
                                        $Total_Sin_IVA = $Total_Sin_IVA + $Abono;
                                    }
                                    SetAdoFields("Cta_Venta", $DatInv['Cta_Ventas']);
                                }
                                $Total_IVA = $Total_IVA + $SubTotal_IVA;
                                $Total_Desc = $Total_Desc + $Total_Desc_ME;
                                SetAdoFields("Total_IVA", $SubTotal_IVA);
                                SetAdoFields("Autorizacion", $parametros['FA']['Autorizacion']);
                                SetAdoFields("Serie", $parametros['FA']['Serie']);
                                SetAdoUpdate();
                                //Activamos el item a borrar
                                $this->modelo->ClientesFacturacionUpdate($CodigoCli, $value2['Codigo_Inv'], $value2['Num_Mes'], $value2['Periodo']);
                                $TotalAbonos = $TotalAbonos - ($Abono - $Total_Desc_ME);

                            }

                        }
                        $Total_Factura = $Total_Sin_IVA + $Total_Con_IVA + $Total_IVA - $Total_Desc;
                        $Total_Ingreso = $Total_Ingreso + $Total_Factura;
                        //Grabamos las Facturas
                        SetAdoAddNew("Facturas");
                        SetAdoFields("T", "C"); //Cancelado
                        SetAdoFields("TC", $parametros['FA']['TC']);
                        SetAdoFields("Porc_IVA", $parametros['FA']['Porc_IVA']);
                        SetAdoFields("Factura", $parametros['Factura_No']);
                        SetAdoFields("Fecha", $FechaTexto);
                        SetAdoFields("Fecha_C", $FechaTexto);
                        SetAdoFields("Fecha_V", $FechaTexto);
                        SetAdoFields("CodigoC", $CodigoCli);
                        SetAdoFields("Forma_Pago", "DEPOSITO BANCO");
                        SetAdoFields("Sin_IVA", $Total_Sin_IVA);
                        SetAdoFields("Con_IVA", $Total_Con_IVA);
                        SetAdoFields("SubTotal", $Total_Sin_IVA + $Total_Con_IVA);
                        SetAdoFields("IVA", $Total_IVA);
                        SetAdoFields("Descuento", $Total_Desc);
                        SetAdoFields("Total_MN", $Total_Factura);
                        SetAdoFields("Saldo_MN", 0);
                        SetAdoFields("Nota", "Facturas de " . MesesLetras($parametros['NoMeses']) . ", Doc. " . $SubCta);
                        SetAdoFields("Cod_CxC", $parametros['FA']['Cod_CxC']);
                        SetAdoFields("Cod_CxP", $parametros['FA']['Cod_CxP']);
                        SetAdoFields("Cta_Venta", $parametros['FA']['Cta_Venta']);
                        SetAdoFields("CodigoU", $_SESSION['INGRESO']['CodigoU']);
                        SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
                        SetAdoFields("Item", $_SESSION['INGRESO']['item']);
                        SetAdoFields("Hora", date('h:i:s a', time()));
                        SetAdoFields("Vencimiento", $parametros['FA']['Vencimiento']->format('Y-m-d'));
                        SetAdoFields("Autorizacion", $parametros['FA']['Autorizacion']);
                        SetAdoFields("Serie", $parametros['FA']['Serie']);
                        SetAdoFields("Imp_Mes", $parametros['FA']['Imp_Mes']);
                        SetAdoFields("Tipo_Pago", $Tipo_Pago);
                        if (strlen($TBenef['Representante']) > 1 && strlen($TBenef['RUC_CI_Rep']) > 1) {
                            SetAdoFields("Razon_Social", $TBenef['Representante']);
                            SetAdoFields("RUC_CI", $TBenef['RUC_CI_Rep']);
                            SetAdoFields("TB", $TBenef['TD_Rep']);
                        } else {
                            switch ($TBenef['TD']) {
                                case "C":
                                case "R":
                                case "P":
                                    SetAdoFields("Razon_Social", $TBenef['Cliente']);
                                    SetAdoFields("RUC_CI", $TBenef['RUC_CI']);
                                    SetAdoFields("TB", $TBenef['TD']);
                                    break;
                                default:
                                    SetAdoFields("Razon_Social", "CONSUMIDOR FINAL");
                                    SetAdoFields("RUC_CI", "9999999999999");
                                    SetAdoFields("TB", "R");
                                    break;
                            }
                        }

                        if (is_numeric($Codigo2) && intval($Codigo2) <> date("y", strtotime($FechaTexto))) {
                            $parametros['FA']['Cta_CxP'] = $parametros['FA']['Cta_CxP_Anterior'];
                            $Cta_Cobrar = $parametros['FA']['Cta_CxP_Anterior'];
                        }
                        SetAdoUpdate();

                        //Abono de la Factura
                        SetAdoAddNew("Trans_Abonos");
                        SetAdoFields("T", "C");
                        SetAdoFields("TP", $parametros['FA']['TC']);
                        SetAdoFields("CodigoC", $CodigoCli);
                        SetAdoFields("Fecha", $FechaTexto);
                        SetAdoFields("Factura", $parametros['Factura_No']);
                        SetAdoFields("Abono", $Total_Factura);
                        SetAdoFields("Recibo_No", trim($SubCta));
                        SetAdoFields("Cheque", $TBenef['Grupo_No']);
                        SetAdoFields("Cta", $Cta_Banco);
                        SetAdoFields("Cta_CxP", $parametros['FA']['Cta_CxP']);
                        SetAdoFields("Serie", $parametros['FA']['Serie']);
                        SetAdoFields("Autorizacion", $parametros['FA']['Autorizacion']);
                        $TipoDeAbono = "";
                        switch ($parametros['TextoBanco']) {
                            case "PACIFICO":
                                $TipoDeAbono = "DEPOSITO EN EL BANCO ";
                                break;
                            case "PICHINCHA":
                                if (intval($parametros['Tipo_Carga']) >= 1) {
                                    $TipoDeAbono = "DEPOSITO EN EL BANCO ";
                                } else {
                                    $TipoDeAbono = "DEP. " . $Codigo3;
                                    if ($SubCta <> G_NINGUNO) {
                                        $TipoDeAbono .= ". " . $SubCta;
                                    }
                                }
                                break;
                            case "INTERNACIONAL":
                            case "OTROSBANCOS":
                                $TipoDeAbono = "DEBITO BANCARIO ";
                                break;
                            case "BOLIVARIANO":
                                $TipoDeAbono = "DEPOSITO EN EL BANCO ";
                                break;
                            case "GUAYAQUIL":
                                $TipoDeAbono = "DEPOSITO EN EL BANCO ";
                                break;
                            case "COOPJEP":
                                $TipoDeAbono = "DEPOSITO EN EL BANCO ";
                                break;
                            case "PRODUBANCO":
                                $TipoDeAbono = "DEPOSITO EN EL BANCO ";
                                break;
                            case "TARJETAS":
                                $TipoDeAbono = "CANCELACION TARJETA ";
                                break;
                            default:
                                $TipoDeAbono = "DEPOSITO EN EL BANCO ";
                                break;
                        }
                        if (strlen($Codigo3) > 1) {
                            $TipoDeAbono .= $Codigo3;
                        }
                        SetAdoFields("Banco", trim($TipoDeAbono));
                        if (strlen($Codigo4) > 1) {
                            SetAdoFields("Comprobante", $Codigo4);
                        } else {
                            SetAdoFields("Comprobante", $SubCta);
                        }
                        SetAdoUpdate();
                        $this->modelo->ClientesFacturacionDelete($CodigoCli);
                    }
                }
            }
            $parametros['FA']['Fecha_Corte'] = FechaSistema();
            //Por pedido de Walter Vaca en vez de utilizar el metodo Procesar_Saldo_De_Facturas, se va a utilizar el sp;
            sp_Actualizar_Saldos_Facturas($parametros['FA']['TC'], $parametros['FA']['Serie'], $parametros['FA']['Factura']);
            $this->modelo->DetalleFacturaUpdate($parametros['FA']['TC'], $parametros['FA']['Serie'], $Factura_Desde, $Factura_Hasta);
            $Mensaje = "GENERACION DE FACTURAS DEL: " . $FechaTexto . " \n
                        SE GRABARON: " . $Total_Alumnos . " ALUMNOS." . " \n
                        DESDE LA " . $parametros['FA']['TC'] . ": " . $Factura_Desde . " HASTA LA " . $Factura_Hasta . " \n
                        EL CIERRE DIARIO DE CAJA ES POR " . $_SESSION['INGRESO']['S_M'] . " " . sprintf("%02d", $Total_Ingreso) . " \n
                        OBTENIDO DEL ARCHIVO: \n" . $parametros['RutaGeneraFile'];
            $this->modelo->DeleteAsientoF2();
            $DGFactura = $this->modelo->DGFactura2();
            return array('response' => 1, 'Mensaje' => $Mensaje, 'tbl' => $DGFactura['datos']);
        } catch (Exception $e) {
            return array('response' => 0, 'error' => $e->getMessage());
        }

    }

    public function Command7_Click($parametros){
        $Contador = 1;
        $AdoClientes = $this->modelo->AdoClientes($parametros);
        if(count($AdoClientes) > 0){
            foreach($AdoClientes as $key => $value){
                $this->modelo->UpdateAdoCliente($parametros, $Contador);
                $Contador++;
            }
        }
        $AdoClientes = $this->modelo->AdoClientes2($parametros);
        //Verificamos si las carpetas existen 
        if(!file_exists(dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS")){
            mkdir(dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS", 0777, true);
        }

        $path = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/Clientes.XLSX";
        Exportar_AdoDB_Excel($AdoClientes, $path, "DiskCover System");
        return array('response' => 1, 'Nombre1' => basename($path), 'Mensaje' => "SE GENERO EL SIGUIENTE ARCHIVO: \n" . basename($path));
    }

    public function Command3_Click($parametros){
        $Contador = 0;
        $AdoClientes = $this->modelo->AdoClientes3();
        if(count($AdoClientes) > 0){
            foreach($AdoClientes as $key => $value){
                $Contador++;
                $this->modelo->UpdateAdoClientes3($Contador);
            }
        }
        $Contador = 0;
        $AdoClientes = $this->modelo->AdoClientes4();
        if(count($AdoClientes) > 0){
            foreach($AdoClientes as $key => $value){
                $Contador++;
                $this->modelo->UpdateAdoClientes4($Contador);
            }
        }
        $Contador = 0;
        $AdoClientes = $this->modelo->AdoClientes5($parametros);
        if(count($AdoClientes) > 0){
            foreach($AdoClientes as $key => $value){
                $Contador++;
                if($parametros['CheqNumCodigos'] <> 0){
                    $param = $_SESSION['INGRESO']['item'] . sprintf("%05d", $Contador);
                    $this->modelo->UpdateAdoClientes5($parametros, $param);
                }else{
                    $param = sprintf("%08d", $Contador);
                    $this->modelo->UpdateAdoClientes5($parametros, $param);
                }
            }
        }
        return array('response' => 1, 'Mensaje' => "PROCESO DE RENUMERACION TERMINADO");
    }

    public function Command5_Click($parametros){
        $AdoClientes = $this->modelo->AdoClientes5($parametros);
        //Verificamos si las carpetas existen
        if(!file_exists(dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS")){
            mkdir(dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS", 0777, true);
        }
        $path = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/Clientes.pdf";
        $this->pdf->Imprimir_Codigo_Banco($AdoClientes, $path);
        return(array('response' => 1,
         'Nombre1' => basename($path),
          'Mensaje' => "SE GENERO EL SIGUIENTE ARCHIVO: \n" . basename($path)));
    }

    public function LetrasMeses($Mes)
    {
        $SMes = "";
        if ($Mes === "") {
            $Mes = "ene";
        }
        switch (strtolower(substr($Mes, 0, 3))) {
            case "ene";
                $SMes = 1;
                break;
            case "feb";
                $SMes = 2;
                break;
            case "mar";
                $SMes = 3;
                break;
            case "abr";
                $SMes = 4;
                break;
            case "may";
                $SMes = 5;
                break;
            case "jun";
                $SMes = 6;
                break;
            case "jul";
                $SMes = 7;
                break;
            case "ago";
                $SMes = 8;
                break;
            case "sep";
                $SMes = 9;
                break;
            case "oct";
                $SMes = 10;
                break;
            case "nov";
                $SMes = 11;
                break;
            case "dic";
                $SMes = 12;
                break;
            default:
                $SMes = 1;
                break;
        }
        return $SMes;
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
}
