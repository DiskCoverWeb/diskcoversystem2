<?php
/** 
 * AUTOR DE RUTINA : Dallyana Vanegas
 * FECHA CREACION : 30/01/2024
 * FECHA MODIFICACION : 18/03/2024
 * DESCIPCION : Clase que se encarga de manejar el Historial de Facturas
 */
require_once (dirname(__DIR__, 2) . "/db/db1.php");
require_once (dirname(__DIR__, 2) . "/funciones/funciones.php");
@session_start();

class HistorialFacturasM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function SRI_Generar_XML_Firmado($ClaveDeAcceso)
    {
        $sSQL = "SELECT Documento_Autorizado
                FROM Trans_Documentos 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND Clave_Acceso = '" . $ClaveDeAcceso . "' ";
        return $this->db->datos($sSQL);
    }

    function CheqAbonos_Click()
    {
        $sSQL = "SELECT (TA.Cta + ' - ' + CC.Cuenta) As NomCxC 
                FROM Trans_Abonos As TA, Catalogo_Cuentas As CC 
                WHERE CC.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CC.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND CC.Periodo = TA.Periodo 
                AND CC.Item = TA.Item 
                AND CC.Codigo = TA.Cta 
                GROUP BY Cta,Cuenta  
                ORDER BY Cta ";
        //SelectDB_Combo DCCxC, AdoCxC, sSQL, "NomCxC"
        return $this->db->datos($sSQL);
    }

    function CheqCxC_Click()
    {
        $sSQL = "SELECT (F.Cta_CxP + ' - ' + CC.Cuenta) As NomCxC 
                FROM Facturas As F,Catalogo_Cuentas As CC 
                WHERE CC.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CC.Item = F.Item 
                AND CC.Codigo = F.Cta_CxP 
                GROUP BY F.Cta_CxP,Cuenta 
                ORDER BY F.Cta_CxP ";
        //SelectDB_Combo DCCxC, AdoCxC, sSQL, "NomCxC"
        return $this->db->datos($sSQL);
    }

    function Historico_Facturas($TipoConsulta, $FechaFin)
    {
        $sSQL = "SELECT C.Cliente, F.T, F.Serie, F.Factura, F.Fecha, Fecha_V, F.Total_MN As Total, F.Total_Efectivo, F.Total_Banco,
                F.Total_Ret_Fuente, F.Total_Ret_IVA_B, F.Total_Ret_IVA_S, F.Otros_Abonos, F.Total_Abonos,F.Saldo_Actual, 
                F.Fecha_C As Abonado_El, F.CodigoC, C.CI_RUC, F.TC, F.Autorizacion, C.Grupo, A.Nombre_Completo As Ejecutivo, C.Ciudad, 
                C.Plan_Afiliado As Sectorizacion, F.Cta_CxP, C.EMail, C.EMail2, C.EMailR, C.Representante 
                FROM Facturas As F, Clientes As C, Accesos As A 
                WHERE F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND F.Fecha <= '" . $FechaFin . "' 
                " . $TipoConsulta . "
                AND F.CodigoC = C.Codigo 
                AND F.Cod_Ejec = A.Codigo 
                ORDER BY C.Cliente, F.Serie, F.Factura, F.Fecha";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True

        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'FACTURAS', '', 'HISTORIAL DE FACTURAS', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);

        //return $this->db->datos($sSQL);
    }

    function Ventas_Productos($FechaIni, $FechaFin, $Si_No, $Con_Costeo, $CodigoInv, $tipoConsulta, $tipoConsulta2)
    {
        $sSQL = "SELECT F.T, CL.Cliente, F.TC As Doc, F.Serie, F.Factura, F.Fecha, F.Codigo, F.Producto, F.Mes, F.Cantidad, F.Total, 0 As Total_NC, 
                    (Total_Desc+Total_Desc2) As Descuento, (F.Total-Total_Desc-Total_Desc2) As SubTotal, C.Marca, 
                    C.Desc_Item As Parte, F.Lote_No, F.Fecha_Fab, F.Fecha_Exp, C.Reg_Sanitario, F.Serie_No $Con_Costeo";

        if ($Si_No)
            $sSQL .= ", F.Precio, Valor_Compra As Costos ";

        $sSQL .= " FROM Detalle_Factura As F, Catalogo_Productos As C, Clientes As CL 
                    WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                    AND F.Item = '" . $_SESSION['INGRESO']['item'] . "'  
                    AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                    " . $tipoConsulta . "
                    AND C.INV <> 0
                    AND F.T <> '" . G_ANULADO . "' ";

        if ($CodigoInv != G_NINGUNO)
            $sSQL .= "AND C.Codigo_Inv = '$CodigoInv' ";

        $sSQL .= "AND F.Item = C.Item 
        AND F.Periodo = C.Periodo 
        AND F.Codigo = C.Codigo_Inv 
        AND F.CodigoC = CL.Codigo 
        UNION ALL 
        SELECT F.T, CL.Cliente, F.TP As Doc, F.Serie, F.Factura, F.Fecha, F.Cta As Codigo, (F.Banco + ' - ' + F.Cheque) AS Producto_Aux, F.Mes, 1 As Cantidad, 0 As Total, -F.Abono As Total_NC, 
                0 As Descuento, -F.Abono As SubTotal, '.' As Marca, '.' As Parte, '.' As Lote_No, F.Fecha As Fecha_Fab, F.Fecha As Fecha_Exp, '.' As Reg_Sanitario, '.' As Serie_No ";

        if ($Si_No)
            $sSQL .= ", 0 As Precio, 0 As Costos ";

        $sSQL .= "FROM Trans_Abonos As F, Clientes As CL 
                    WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                    AND F.Item = '" . $_SESSION['INGRESO']['item'] . "'  
                    AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                    AND F.Banco = 'NOTA DE CREDITO' 
                    " . $tipoConsulta2 . "
                    AND F.T <> '" . G_ANULADO . "'
                    AND F.CodigoC = CL.Codigo 
                    ORDER BY Doc, F.Factura, F.Fecha";

        //print_r($sSQL);
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'Trans_Abonos', '', 'HISTORIAL DE FACTURAS Y PRODUCTOS', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);

    }

    function Abonos_Facturas($tipoConsulta, $FechaIni, $FechaFin, $Ret_NC, $SQL_Server = TRUE)
    {
        for ($IDMes = 1; $IDMes <= 12; $IDMes++) {
            if ($SQL_Server) {
                $sSQL = "UPDATE Trans_Abonos 
                    SET Mes = DF.Mes, Mes_No = DF.Mes_No 
                    FROM Trans_Abonos, Detalle_Factura AS DF ";
            } else {
                $sSQL = "UPDATE Trans_Abonos, Detalle_Factura AS DF 
                    SET Trans_Abonos.Mes = DF.Mes, Trans_Abonos.Mes_No = DF.Mes_No ";
            }

            $sSQL .= "WHERE Trans_Abonos.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                AND Trans_Abonos.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Trans_Abonos.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND DF.T <> 'A' 
                AND MONTH(Trans_Abonos.Fecha) = '" . $IDMes . "' 
                AND Trans_Abonos.Item = DF.Item 
                AND Trans_Abonos.Periodo = DF.Periodo 
                AND Trans_Abonos.Factura = DF.Factura 
                AND Trans_Abonos.Serie = DF.Serie 
                AND Trans_Abonos.Autorizacion = DF.Autorizacion 
                AND Trans_Abonos.CodigoC = DF.CodigoC ";

            Ejecutar_SQL_SP($sSQL);
        }

        //$Total = 0;

        if ($Ret_NC) {
            $sSQL = "SELECT F.TP, F.Fecha, C.Cliente, F.Serie, F.Factura, F.Banco, F.Cheque, F.Abono, F.Mes, F.Comprobante, 
                F.Autorizacion, F.Serie_NC, Secuencial_NC, F.Autorizacion_NC, F.Base_Imponible, F.Porc, C.Representante As Razon_Social, 
                F.Cta, F.Cta_CxP 
                FROM Trans_Abonos As F, Clientes C 
                WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                " . $tipoConsulta . "
                AND SUBSTRING(F.Banco, 1, 9) = 'RETENCION' 
                AND F.CodigoC = C.Codigo 
                UNION 
                SELECT F.TP, F.Fecha, C.Cliente, F.Serie, F.Factura, F.Banco, F.Cheque, F.Abono, F.Mes, F.Comprobante, 
                F.Autorizacion, F.Serie_NC, Secuencial_NC, F.Autorizacion_NC, F.Base_Imponible, F.Porc, C.Representante As Razon_Social, 
                F.Cta, F.Cta_CxP 
                FROM Trans_Abonos As F, Clientes C 
                WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                " . $tipoConsulta . "
                AND F.Banco = 'NOTA DE CREDITO' 
                AND F.CodigoC = C.Codigo 
                ORDER BY F.Banco, F.Cheque, C.Cliente, F.Factura, F.Fecha ";
        } else {
            $sSQL = "SELECT F.TP, F.Fecha, C.Cliente, F.Serie, F.Factura, F.Banco, F.Cheque, F.Abono, F.Mes, F.Comprobante, 
                F.Autorizacion, F.Serie_NC, Secuencial_NC, F.Autorizacion_NC, C.Representante As Razon_Social, F.Cta, 
                F.Cta_CxP 
                FROM Trans_Abonos As F, Clientes C 
                WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                " . $tipoConsulta . "
                AND F.CodigoC = C.Codigo 
                ORDER BY C.Cliente, F.Factura, F.Fecha, F.Banco ";
        }

        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'TRANS_ABONOS', '', 'ABONOS DE FACTURAS', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);

        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        //return $this->db->datos($sSQL);
        //$LabelAbonado = number_format($Total, 2, '.', ',');
        //$LabelFacturado = "0.00";
        //$LabelSaldo = "0.00";
        //$DGQueryVisible = true;
    }

    function Recibo_Abonos_Anticipados($FechaIni, $FechaFin, $Co, $tipoConsulta)
    {
        $sSQL = "SELECT C.Cliente, C.Email, C.Email2, C.CI_RUC, TS.Cta, TS.Fecha, TS.TP, TS.Numero, TS.Creditos As Abono, Co.Concepto 
                FROM Trans_SubCtas AS TS, Comprobantes AS Co, Clientes AS C 
                WHERE TS.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                AND TS.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND TS.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND TS.TP = '" . $Co['TP'] . "' 
                AND TS.Numero = '" . $Co['Numero'] . "'
                AND TS.T <> 'A' 
                " . $tipoConsulta . "
                AND TS.Item = Co.Item 
                AND TS.Periodo = Co.Periodo 
                AND TS.TP = Co.TP 
                AND TS.Numero = Co.Numero 
                AND TS.Codigo = C.Codigo 
                ORDER BY C.Cliente, TS.Cta, TS.Fecha, TS.TP, TS.Numero";
        //Select_Adodc AdoFacturas, sSQL
        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'TRANS_SUBCTAS', '', '', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);

    }

    function Abonos_Anticipados($FechaIni, $FechaFin)
    {
        $sSQL = "SELECT TA.TP, F.Serie, F.Autorizacion, F.Fecha, F.Factura, TA.Fecha AS Fecha_Abono, TA.Abono 
            FROM Facturas AS F, Trans_Abonos AS TA 
            WHERE TA.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
            AND F.Item = '" . $_SESSION['INGRESO']['item'] . "'  
            AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
            AND F.Item = TA.Item 
            AND F.Periodo = TA.Periodo 
            AND F.TC = TA.TP 
            AND F.Serie = TA.Serie 
            AND F.Autorizacion = TA.Autorizacion 
            AND F.Factura = TA.Factura 
            AND F.Fecha > TA.Fecha
            ORDER BY TA.Fecha, F.Factura";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True

        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'Facturas', 'mi_tabla_abono', 'ABONOS DE ANTICIPADOS', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);

    }

    function Abonos_Erroneos($FechaIni, $FechaFin, $SQL_Server = true)
    {
        $sSQL = "UPDATE Trans_Abonos 
                SET X = 'E' 
                WHERE Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' ";

        Ejecutar_SQL_SP($sSQL);

        if ($SQL_Server) {
            $sSQL = "UPDATE Trans_Abonos 
                    SET X = '.' 
                    FROM Trans_Abonos AS TA, Facturas AS F ";
        } else {
            $sSQL = "UPDATE Trans_Abonos AS TA, Facturas AS F 
                    SET TA.X = '.' ";
        }

        $sSQL .= "WHERE TA.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND F.Item = TA.Item 
                AND F.Periodo = TA.Periodo 
                AND F.TC = TA.TP 
                AND F.Serie = TA.Serie 
                AND F.Autorizacion = TA.Autorizacion 
                AND F.Factura = TA.Factura 
                AND F.CodigoC = TA.CodigoC";

        Ejecutar_SQL_SP($sSQL);

        $sSQL = "SELECT TP, Serie, Autorizacion, Fecha, Factura, Abono, CodigoC
                FROM Trans_Abonos 
                WHERE Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND X = 'E' 
                AND TP <> 'CB' 
                ORDER BY Fecha, Factura";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'Facturas', '', 'ABONOS MAL PROCESADOS', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);

    }

    function Resumen_Productos($tipoConsulta, $FechaIni, $FechaFin)
    {
        $sSQL = "SELECT C.Cliente, SUM(F.Cantidad) AS Cant_Prod, CP.Producto, F.Codigo, SUM(F.Total_IVA) AS IVA, 
                SUM(F.Total) AS Ventas, SUM(F.Cantidad*CP.Gramaje/1000) AS Kilos, CP.Gramaje 
                FROM Clientes AS C, Detalle_Factura AS F, Catalogo_Productos AS CP 
                WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                " . $tipoConsulta . "
                AND F.CodigoC = C.Codigo 
                AND F.Item = CP.Item 
                AND F.Periodo = CP.Periodo 
                AND F.Codigo = CP.Codigo_Inv 
                GROUP BY C.Cliente, F.Codigo, F.CodigoC, CP.Producto, CP.Gramaje 
                ORDER BY C.Cliente, F.Codigo, F.CodigoC, CP.Producto, CP.Gramaje";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'CLIENTES', '', 'HISTORIAL DE FACTURAS Y PRODUCTOS', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);

    }

    function Ventas_Cliente($FechaIni, $FechaFin, $tipoConsulta)
    {
        $sSQL = "SELECT C.Cliente, F.TC, COUNT(F.CodigoC) AS Cant_Fact, SUM(F.Total) AS Ventas, 
                SUM(F.Total_IVA) AS I_V_A, SUM(F.Total + F.Total_IVA) AS Total_Facturado 
         FROM Detalle_Factura AS F, Clientes AS C 
         WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
           AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
           AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
           " . $tipoConsulta . "
           AND F.CodigoC = C.Codigo 
         GROUP BY C.Cliente, F.TC 
         ORDER BY SUM(F.Total + F.Total_IVA) DESC, C.Cliente";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'CLIENTES', '', 'HISTORIAL DE FACTURAS Y PRODUCTOS', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);

    }

    function Resumen_Prod_Meses($FechaIni, $FechaFin, $PorCantidad, $MBFechaF, $SQL_Server = TRUE)
    {
        //$_SESSION['INGRESO']['Tipo_Base'];

        $sSQL = "UPDATE Catalogo_Productos 
                SET X = '.' 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND TC = 'P' ";
        Ejecutar_SQL_SP($sSQL);

        if ($SQL_Server) {
            $sSQL = "UPDATE Catalogo_Productos
                    SET X = 'X' 
                    FROM Catalogo_Productos As CP, Detalle_Factura As DF ";
        } else {
            $sSQL = "UPDATE Catalogo_Productos As CP, Detalle_Factura As DF
                    SET CP.X = 'X' ";
        }

        $sSQL .= "WHERE DF.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                AND CP.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CP.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND CP.TC = 'P' 
                AND CP.Item = DF.Item 
                AND CP.Periodo = DF.Periodo 
                AND CP.Codigo_Inv = DF.Codigo ";
        Ejecutar_SQL_SP($sSQL);

        $Nom_Mes = [
            1 => "Enero",
            2 => "Febrero",
            3 => "Marzo",
            4 => "Abril",
            5 => "Mayo",
            6 => "Junio",
            7 => "Julio",
            8 => "Agosto",
            9 => "Septiembre",
            10 => "Octubre",
            11 => "Noviembre",
            12 => "Diciembre"
        ];

        $sSQLx = implode(",", $Nom_Mes) . ",Total";

        $sSQL = "DELETE FROM Saldo_Diarios
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
            AND TP = 'RPXM'";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "INSERT INTO Saldo_Diarios (TC, Codigo_Aux, Item, CodigoU, TP, Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, 
                Agosto, Septiembre, Octubre, Noviembre, Diciembre, Total) 
                SELECT TC, Codigo_Inv, '" . $_SESSION['INGRESO']['item'] . "' AS Itemx, '" . $_SESSION['INGRESO']['CodigoU'] . "' AS CodigoUs, 'RPXM' AS TPs,
                0 AS Enerox, 0 AS Febrerox, 0 AS Marzox, 0 AS Abrilx, 0 AS Mayox, 0 AS Juniox, 0 AS Juliox, 
                0 AS Agostox, 0 AS Septiembrex, 0 AS Octubrex, 0 AS Noviembrex, 0 AS Diciembrex, 0 AS Totalx 
                FROM Catalogo_Productos 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND X = 'X'";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "SELECT * 
            FROM Saldo_Diarios
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
            AND TP = 'RPXM'";
        //Select_Adodc AdoQuery1, sSQL
        for ($NoMes = 1; $NoMes <= 12; $NoMes++) {
            $sSQL = "UPDATE Saldo_Diarios ";
            if ($PorCantidad) {
                $sSQL .= "SET " . $Nom_Mes[$NoMes] . " = (SELECT SUM(Cantidad) ";
            } else {
                $sSQL .= "SET " . $Nom_Mes[$NoMes] . " = (SELECT SUM(Total-Total_Desc-Total_Desc2) ";
            }
            $sSQL .= "FROM Detalle_Factura AS F
                    WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                    AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                    AND F.T <> '" . G_ANULADO . "' 
                    AND MONTH(F.Fecha) = " . $NoMes . " 
                    AND F.Codigo = Saldo_Diarios.Codigo_Aux 
                    AND F.Item = Saldo_Diarios.Item) 
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
                    AND TP = 'RPXM'";
            Ejecutar_SQL_SP($sSQL);

            $sSQL = "UPDATE Saldo_Diarios 
                    SET " . $Nom_Mes[$NoMes] . " = 0 
                    WHERE " . $Nom_Mes[$NoMes] . " IS NULL 
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "'";
            Ejecutar_SQL_SP($sSQL);
        }

        $sSQLx = "Total=" . implode("+", $Nom_Mes);

        $sSQL = "UPDATE Saldo_Diarios 
                SET " . $sSQLx . "
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
                AND TP = 'RPXM'";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "DELETE FROM Saldo_Diarios
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
                AND Total = 0 
                AND TP = 'RPXM'";
        Ejecutar_SQL_SP($sSQL);

        $sSQLx = "";
        for ($NoMes = 1; $NoMes <= date("n", strtotime($MBFechaF)); $NoMes++) {
            $sSQLx .= ",SD." . $Nom_Mes[$NoMes];
        }

        $sSQL = "SELECT SD.Codigo_Aux AS Codigos, CP.Producto, CP.Unidad " . $sSQLx . ",SD.Total
            FROM Saldo_Diarios AS SD, Catalogo_Productos AS CP 
            WHERE CP.Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND CP.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
            AND SD.CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
            AND SD.TP = 'RPXM' 
            AND SD.Codigo_Aux = CP.Codigo_Inv 
            AND SD.Item = CP.Item 
            ORDER BY SD.Total DESC, CP.Producto, SD.Codigo_Aux";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'Saldo_Diarios', '', 'RESUMEN DE VENTAS DE PRODUCTOS MENSUALIZADO', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);

    }

    function Ventas_Clientes_Por_Meses($FechaIni, $FechaFin, $FA, $MBFechaF, $SQL_Server = true)
    {
        $sSQL = "UPDATE Clientes
                SET X = '.' 
                WHERE FA <> " . intval(false);
        Ejecutar_SQL_SP($sSQL);

        if ($SQL_Server) {
            $sSQL = "UPDATE C 
                    SET X = 'X', FA = 1
                    FROM Clientes AS C, Facturas AS F ";
        } else {
            $sSQL = "UPDATE Clientes AS C, Facturas AS F
                    SET C.X = 'X' ";
        }

        $sSQL .= "WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND C.FA <> " . intval(false) . " 
                AND C.Codigo = F.CodigoC ";
        Ejecutar_SQL_SP($sSQL);

        $Nom_Mes = [
            1 => "Enero",
            2 => "Febrero",
            3 => "Marzo",
            4 => "Abril",
            5 => "Mayo",
            6 => "Junio",
            7 => "Julio",
            8 => "Agosto",
            9 => "Septiembre",
            10 => "Octubre",
            11 => "Noviembre",
            12 => "Diciembre"
        ];

        $sSQLx = implode(",", $Nom_Mes) . ",Total";

        $sSQL = "DELETE * 
                FROM Saldo_Diarios 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
                AND TP = 'VCXM' ";

        Ejecutar_SQL_SP($sSQL);

        $sSQL = "INSERT INTO Saldo_Diarios (Cta, CodigoC, Item, CodigoU, TP, Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, 
                Agosto, Septiembre, Octubre, Noviembre, Diciembre, Total) 
                SELECT Cod_Ejec, Codigo,'" . $_SESSION['INGRESO']['item'] . "' AS Itemx,'" . $_SESSION['INGRESO']['CodigoU'] . "' AS CodigoUs,'VCXM' AS TPs,
                0 AS Enerox, 0 AS Febrerox, 0 AS Marzox, 0 AS Abrilx, 0 AS Mayox, 0 AS Juniox, 0 AS Juliox, 
                0 AS Agostox, 0 AS Septiembrex, 0 AS Octubrex, 0 AS Noviembrex, 0 AS Diciembrex, 0 AS Totalx 
                FROM Clientes 
                WHERE FA <> 0
                AND X = 'X' ";

        if (strlen($FA['Cod_Ejec']) > 1) {
            $sSQL .= "AND Cod_Ejec = '" . $FA['Cod_Ejec'] . "' ";
        }
        if (strlen($FA['CodigoC']) > 1) {
            $sSQL .= "AND Codigo = '" . $FA['CodigoC'] . "' ";
        }
        if (strlen($FA['Grupo']) > 1) {
            $sSQL .= "AND Grupo = '" . $FA['Grupo'] . "' ";
        }
        Ejecutar_SQL_SP($sSQL);

        $AdoQuery1 = "SELECT * 
                FROM Saldo_Diarios 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                AND TP = 'VCXM'";
        //Select_Adodc AdoQuery1, sSQL

        for ($NoMes = 1; $NoMes <= 12; $NoMes++) {
            $sSQL = "UPDATE Saldo_Diarios 
                    SET " . $Nom_Mes[$NoMes] . " = (SELECT SUM(Total_MN-IVA) 
                    FROM Facturas AS F 
                    WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                    AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                    AND F.T <> '" . G_ANULADO . "' 
                    AND MONTH(F.Fecha) = " . $NoMes . " 
                    AND F.CodigoC = Saldo_Diarios.CodigoC 
                    AND F.Item = Saldo_Diarios.Item) 
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
                    AND TP = 'VCXM'";
            Ejecutar_SQL_SP($sSQL);

            $sSQL = "UPDATE Saldo_Diarios 
                    SET " . $Nom_Mes[$NoMes] . " = 0 
                    WHERE " . $Nom_Mes[$NoMes] . " IS NULL 
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "'";
            Ejecutar_SQL_SP($sSQL);
        }

        $sSQLx = "Total=" . implode("+", $Nom_Mes);

        $sSQL = "UPDATE Saldo_Diarios
                SET " . $sSQLx . "
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
                AND TP = 'VCXM' ";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "UPDATE Saldo_Diarios 
            SET Grupo_No = RP.Cod_Ejec 
            FROM Saldo_Diarios As SD, Catalogo_Rol_Pagos As RP 
            WHERE RP.Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND RP.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
            AND SD.Cta = RP.Codigo 
            AND SD.Item = RP.Item ";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "DELETE * 
            FROM Saldo_Diarios 
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
            AND Total = 0 
            AND TP = 'VCXM' ";
        Ejecutar_SQL_SP($sSQL);

        $AdoQuery1 = "SELECT * 
            FROM Saldo_Diarios 
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
            AND TP = 'VCXM' ";
        //Select_Adodc AdoQuery1, sSQL
        $res = $this->db->datos($AdoQuery1);

        if (count($res) > 0) {
            foreach ($res as $record) {
                $CantProm = 0;
                $Total = 0;
                for ($NoMes = 1; $NoMes <= 12; $NoMes++) {
                    if ($record[$Nom_Mes[$NoMes]] != 0) {
                        $Total += $record[$Nom_Mes[$NoMes]];
                        $CantProm++;
                    }
                }
                if ($CantProm <= 0) {
                    $CantProm = 1;
                }
                $record["Diferencia"] = round($Total / $CantProm, 2);
                $update = "UPDATE Saldo_Diarios 
                SET Diferencia = " . $record["Diferencia"] . " 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                AND TP = 'VCXM'";
                $this->db->datos($update);
            }
        }

        $sSQLx = "";
        for ($NoMes = 1; $NoMes <= date("n", strtotime($MBFechaF)); $NoMes++) {
            $sSQLx .= ",SD." . $Nom_Mes[$NoMes];
        }

        $sSQL = "SELECT SD.Grupo_No AS Ejecutivo, C.Grupo, C.Cliente 
                " . $sSQLx . ", SD.Total, SD.Diferencia AS Promedio 
                FROM Saldo_Diarios AS SD, Clientes AS C 
                WHERE SD.Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND SD.CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' ";

        if (strlen($FA['Cod_Ejec']) > 1) {
            $sSQL .= "AND SD.Cta = '" . $FA['Cod_Ejec'] . "' ";
        }
        if (strlen($FA['CodigoC']) > 1) {
            $sSQL .= "AND SD.Codigo = '" . $FA['CodigoC'] . "' ";
        }

        $sSQL .= "AND SD.TP = 'VCXM'
            AND SD.CodigoC = C.Codigo 
            ORDER BY SD.Total DESC, SD.Grupo_No, C.Grupo, C.Cliente";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True

        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'Saldo_Diarios', '', 'VENTAS POR CLIENTES MENSUALIZADO', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);
    }

    function datos($sSQL, $val = false, $Por_FA = false)
    {
        if ($val) {
            $TiempoSistema = time();
            $Minutos = date('H:i:s');
            $HistorialFacturas = "RESUMEN HISTORICO DE FACTURAS/NOTAS DE VENTA ";
            if ($Por_FA) {
                $HistorialFacturas .= " EMITIDAS ";
            } else {
                $HistorialFacturas .= " POR MESES ";
            }
            $HistorialFacturas .= date('H:i:s', strtotime($Minutos) - strtotime($TiempoSistema));

            $res = $this->db->datos($sSQL);
            $num_filas = count($res);
            $datos = grilla_generica_new($sSQL, 'Saldo_Diarios', '', $HistorialFacturas, false, false, false, 1, 1, 1, 100);
            return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);
        } else {
            return $this->db->datos($sSQL);
        }

    }

    function Resumen_Ventas_Costos($FechaIni, $FechaFin, $Con_Costeo, $Si_No, $DescItem, $tipoConsulta)
    {
        $sSQL = "SELECT * 
                FROM Catalogo_Productos 
                WHERE TC = 'P' 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                ORDER BY Codigo_Inv ";
        //Select_Adodc AdoHistoria, sSQL

        $sSQL = "SELECT * 
                FROM Trans_Kardex 
                WHERE Fecha <= '" . $FechaFin . "'
                AND T <> 'A' 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                RDER BY Codigo_Inv,Fecha,ID ";
        //Select_Adodc AdoQuery, sSQL

        $sSQL = "SELECT F.Codigo, CP.Producto, SUM(F.Cantidad) AS Cant_Prod, SUM(F.Total) AS Ventas, 
                SUM(F.Cantidad*CP.Gramaje/1000) AS Kilos, CP.Desc_Item " . $Con_Costeo;

        if ($Si_No) {
            $sSQL .= ", AVG(F.Precio) AS PVP, Valor_Compra AS Costos ";
        }

        $sSQL .= "FROM Detalle_Factura AS F, Catalogo_Productos AS CP, Clientes AS C
                WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND CP.INV <> 0 
                " . $tipoConsulta . " ";

        if ($DescItem != G_NINGUNO) {
            $sSQL .= "AND CP.Desc_Item = '" . $DescItem . "' ";
        }

        $sSQL .= "AND F.Item = CP.Item 
                AND F.Periodo = CP.Periodo 
                AND F.Codigo = CP.Codigo_Inv 
                AND F.CodigoC = C.Codigo ";

        if ($Si_No) {
            if ($DescItem != G_NINGUNO) {
                $sSQL .= "GROUP BY CP.Desc_Item, F.Codigo, CP.Valor_Compra ";
            } else {
                $sSQL .= "GROUP BY F.Codigo, CP.Valor_Compra, CP.Producto, CP.Desc_Item ";
            }
        } else {
            if ($DescItem != G_NINGUNO) {
                $sSQL .= "GROUP BY CP.Desc_Item, F.Codigo, CP.Producto ";
            } else {
                $sSQL .= "GROUP BY F.Codigo, CP.Producto, CP.Desc_Item ";
            }
        }

        if ($DescItem != G_NINGUNO) {
            $sSQL .= "ORDER BY CP.Desc_Item, F.Codigo, SUM(F.Total) DESC ";
        } else {
            $sSQL .= "ORDER BY F.Codigo, SUM(F.Total) DESC, CP.Producto ";
        }
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True

        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'Saldo_Diarios', '', 'HISTORIAL DE FACTURAS Y PRODUCTOS', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);
    }

    function Resumen_Ventas_Vendedor($FechaIni, $FechaFin, $tipoConsulta)
    {
        $sSQL = "SELECT C.Grupo,C.Cliente, F.Fecha, TA.Fecha As Fecha_A, F.Serie, TA.Factura, CONVERT(Money,TA.Abono/(1+F.Porc_IVA)) As Abonos, 
         DATEDIFF(day,F.Fecha,TA.Fecha) As Dias_T, A.Nombre_Completo 
         FROM Clientes As C, Facturas As F, Trans_Abonos As TA, Accesos As A 
          WHERE TA.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
          AND TA.Item = '" . $_SESSION['INGRESO']['item'] . "' 
          AND TA.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
          AND NOT SUBSTRING(TA.Banco,1,9) IN ('RETENCION','NOTA DE C') 
          " . $tipoConsulta . "
          AND C.Codigo = F.CodigoC 
          AND A.Codigo = F.Cod_Ejec 
          AND F.Item = TA.Item 
          AND F.Periodo = TA.Periodo 
          AND F.TC = TA.TP 
          AND F.Serie = TA.Serie 
          AND F.Autorizacion = TA.Autorizacion 
          AND F.Factura = TA.Factura 
          AND F.CodigoC = TA.CodigoC 
          ORDER BY C.Grupo,C.Cliente,F.Serie,F.Factura ";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        $Opcion = '15';
        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'Clientes', '', '', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res, 'Opcion' => $Opcion);
    }

    function Ventas_Resumidas_x_Vendedor($FechaIni, $FechaFin)
    {
        $sSQL = "UPDATE Accesos 
            SET Cuota_Venta = 1 
            WHERE Cuota_Venta = 0 ";
        Ejecutar_SQL_SP($sSQL);

        $sSQLGrupo = "SELECT A.Cod_Ejec, A.Nombre_Completo AS Nombre_Vendedor, C.Grupo, CC.Cuenta, 
                    SUM(F.SubTotal - F.Descuento - F.Descuento2) AS Cantidad, ' ' AS Cuota 
              FROM Facturas AS F, Catalogo_Cuentas AS CC, Accesos AS A, Clientes AS C 
              WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
              AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
              AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
              AND F.T <> '" . G_ANULADO . "' 
              AND A.Codigo = F.Cod_Ejec 
              AND C.Codigo = F.CodigoC 
              AND F.Item = CC.Item 
              AND F.Periodo = CC.Periodo 
              AND F.Cta_CxP = CC.Codigo 
              GROUP BY C.Grupo, A.Cod_Ejec, A.Nombre_Completo, A.Cuota_Venta, CC.Cuenta ";

        $sSQLSubTotal = "SELECT A.Cod_Ejec, ' ' AS Nombre_Vendedor, ' ' AS Grupo, 'SUBTOTAL VENDEDOR' AS Cuenta, 
                         SUM(F.SubTotal - F.Descuento - F.Descuento2) AS Cantidad, 
                         CONCAT(STR((SUM(F.SubTotal - F.Descuento - F.Descuento2) / A.Cuota_Venta) * 100), '%') AS Cuota
                 FROM Facturas AS F, Catalogo_Cuentas AS CC, Accesos AS A, Clientes AS C 
                 WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                 AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                 AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                 AND F.T <> '" . G_ANULADO . "' 
                 AND A.Codigo = F.Cod_Ejec 
                 AND C.Codigo = F.CodigoC 
                 AND F.Item = CC.Item 
                 AND F.Periodo = CC.Periodo 
                 AND F.Cta_CxP = CC.Codigo 
                 GROUP BY A.Cod_Ejec, A.Cuota_Venta ";

        $sSQL = "$sSQLGrupo 
         UNION 
         $sSQLSubTotal 
         ORDER BY A.Cod_Ejec, A.Nombre_Completo DESC, C.Grupo, CC.Cuenta";

        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True;
        $Opcion = '17';
        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'Clientes', '', '', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res, 'Opcion' => $Opcion);
    }

    function CxC_Tiempo_Credito($Mifecha, $FechaIni, $FechaFin, $FA)
    {
        $sSQL = "UPDATE Facturas 
            SET Venc_0_60=0,Venc_61_90=0,Venc_91_120=0,Venc_121_360=0,Venc_mas_360=0 
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' ";
        Ejecutar_SQL_SP($sSQL);

        $intervalos = [
            ['Venc_0_60', 0, 60],
            ['Venc_61_90', 61, 90],
            ['Venc_91_120', 91, 120],
            ['Venc_121_360', 121, 360],
            ['Venc_mas_360', 361, PHP_INT_MAX]
        ];

        foreach ($intervalos as $intervalo) {
            list($campo, $minDias, $maxDias) = $intervalo;

            $sSQL = "UPDATE Facturas " .
                "SET " . $campo . " = Saldo_MN " .
                "WHERE DATEDIFF(DAY,Fecha, '" . $Mifecha . "') BETWEEN " . $minDias . " and " . $maxDias . " " .
                "AND Item = '" . $_SESSION['INGRESO']['item'] . "' " .
                "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
                "AND T = '" . G_PENDIENTE . "' ";
            Ejecutar_SQL_SP($sSQL);
        }

        $sSQLV = "SELECT A.Nombre_Completo AS Nombre_Vendedor, C.Cliente AS Clientes, YEAR(F.Fecha) AS Año, MONTH(F.Fecha) AS Mes, " .
            "SUM(Venc_0_60) AS _0_60, " .
            "SUM(Venc_61_90) AS _61_90, " .
            "SUM(Venc_91_120) AS _61_120, " .
            "SUM(Venc_121_360) AS _121_360, " .
            "SUM(Venc_mas_360) AS _mas_360, " .
            "SUM(Venc_0_60 + Venc_61_90 + Venc_91_120 + Venc_121_360 + Venc_mas_360) AS Saldo_Total, " .
            "SUM(F.Total_MN) AS Total_Facturado " .
            "FROM Facturas AS F, Accesos AS A, Clientes AS C " .
            "WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
            "AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' " .
            "AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
            "AND F.T = '" . G_PENDIENTE . "' ";
        if (strlen($FA['Cod_Ejec']) > 1) {
            $sSQLV .= "AND C.Cod_Ejec = '" . $FA['Cod_Ejec'] . "' ";
        }
        $sSQLV .= "AND A.Codigo = F.Cod_Ejec " .
            "AND C.Codigo = F.CodigoC " .
            "GROUP BY A.Nombre_Completo, C.Cliente, YEAR(F.Fecha), MONTH(F.Fecha) ";

        $sSQLT = "SELECT A.Nombre_Completo AS Nombre_Vendedor, 'zz" . str_repeat(" ", 40) . "SUBTOTALES' AS Clientes, " . date('Y', strtotime(FechaSistema())) . " AS Año, " . date('n', strtotime(FechaSistema())) . " AS Mes, " .
            "SUM(Venc_0_60) AS T_Venc_0_60, " .
            "SUM(Venc_61_90) AS T_Venc_61_90, " .
            "SUM(Venc_91_120) AS T_Venc_61_90, " .
            "SUM(Venc_121_360) AS T_Venc_121_360, " .
            "SUM(Venc_mas_360) AS T_Venc_mas_360, " .
            "SUM(Venc_0_60 + Venc_61_90 + Venc_91_120 + Venc_121_360 + Venc_mas_360) AS Saldo_Total, " .
            "SUM(F.Total_MN) AS Total_Facturado " .
            "FROM Facturas AS F, Accesos AS A, Clientes AS C " .
            "WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
            "AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' " .
            "AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
            "AND F.T = '" . G_PENDIENTE . "' ";

        if (strlen($FA['Cod_Ejec']) > 1) {
            $sSQLT .= "AND C.Cod_Ejec = '" . $FA['Cod_Ejec'] . "' ";
        }
        $sSQLT .= "AND A.Codigo = F.Cod_Ejec " .
            "AND C.Codigo = F.CodigoC " .
            "GROUP BY A.Nombre_Completo ";

        $sSQL = $sSQLV . "UNION " . $sSQLT . "ORDER BY A.Nombre_Completo, Clientes ";
        //Select_Adodc_Grid($DGQuery, $AdoQuery, $sSQL);
        $Opcion = '18';
        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'FACTURAS', '', '', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res, 'Opcion' => $Opcion);
    }

    function Cheques_Protestados($TipoConsulta, $FechaIni, $FechaFin)
    {
        $sSQL = "SELECT F.TP,F.Fecha,C.Cliente,F.Factura,F.Banco,F.Cheque,F.Abono,F.Comprobante,F.Cta,F.Cta_CxP 
        FROM Trans_Abonos AS F,Clientes C 
        WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
        AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
        AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
        " . $TipoConsulta . "
        AND F.CodigoC = C.Codigo 
        AND F.Protestado <> 0 
        ORDER BY C.Cliente,F.Factura,F.Fecha,F.Banco ";
        //Select_Adodc_Grid($DGQuery, $AdoQuery, $sSQL, null, null, true);

        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'TRANS_ABONOS', '', 'ABONOS DE FACTURAS', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);
    }

    function CheqIngreso_Click()
    {
        $sSQL = "SELECT (DF.Cta_Venta +' - '+ CC.Cuenta) AS NomCxC " .
            "FROM Detalle_Factura AS DF, Catalogo_Cuentas AS CC " .
            "WHERE CC.Item = '" . $_SESSION['INGRESO']['item'] . "' " .
            "AND CC.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
            "AND CC.Periodo = DF.Periodo " .
            "AND CC.Item = DF.Item " .
            "AND CC.Codigo = DF.Cta_Venta " .
            "GROUP BY DF.Cta_Venta, CC.Cuenta " .
            "ORDER BY DF.Cta_Venta";
        //SelectDB_Combo DCCxC, AdoCxC, sSQL, "NomCxC"
    }

    function ListCliente_LostFocus($ListClienteText)
    {
        $NumEmpresa = $_SESSION['INGRESO']['item'];
        $Periodo_Contable = $_SESSION['INGRESO']['periodo'];
        //$CodigoUsuario = $_SESSION['INGRESO']['CodigoU'];
        //$Modulo = $_SESSION['INGRESO']['modulo'];

        switch ($ListClienteText) {
            case "Autorizacion":
                $sSQL = "SELECT Autorizacion 
                        FROM Facturas 
                        WHERE Item = '" . $NumEmpresa . "'
                        AND Periodo = '" . $Periodo_Contable . "' ";
                //if ($Modulo == "EJECUTIVOS") $sSQL .= "AND Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "GROUP BY Autorizacion " .
                    "ORDER BY Autorizacion DESC ";
                $Nombre_Campo = "Autorizacion";
                break;
            case "Serie":
                $sSQL = "SELECT Serie 
                        FROM Facturas 
                        WHERE Item = '" . $NumEmpresa . "'
                        AND Periodo = '" . $Periodo_Contable . "' ";
                //if ($Modulo == "EJECUTIVOS") $sSQL .= "AND Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "GROUP BY Serie " .
                    "ORDER BY Serie ";
                $Nombre_Campo = "Serie";
                break;
            case "Codigo":
                $sSQL = "SELECT Codigo, COUNT(Factura) AS Fact_Proc 
                        FROM Clientes AS C, Facturas AS F 
                        WHERE F.Item = '" . $NumEmpresa . "' 
                        AND F.Periodo = '" . $Periodo_Contable . "' ";
                //if ($Modulo == "EJECUTIVOS") $sSQL .= "AND F.Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = F.CodigoC 
                    GROUP BY Codigo 
                    ORDER BY Codigo ";
                $Nombre_Campo = "Codigo";
                break;
            case "CI_RUC":
                $sSQL = "SELECT CI_RUC, Cliente, Codigo, COUNT(Factura) AS Fact_Proc 
                        FROM Clientes AS C, Facturas AS F 
                        WHERE F.Item = '" . $NumEmpresa . "' 
                        AND F.Periodo = '" . $Periodo_Contable . "' ";
                //if ($Modulo == "EJECUTIVOS") $sSQL .= "AND F.Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = F.CodigoC 
                    GROUP BY CI_RUC, Cliente, Codigo 
                    ORDER BY CI_RUC ";
                $Nombre_Campo = "CI_RUC";
                break;
            case "Cliente":
                $sSQL = "SELECT F.CodigoC, Cliente, COUNT(Factura) AS Fact_Proc 
                        FROM Clientes AS C, Facturas AS F 
                        WHERE F.Item = '" . $NumEmpresa . "' 
                        AND F.Periodo = '" . $Periodo_Contable . "' ";
                //if ($Modulo == "EJECUTIVOS") $sSQL .= "AND F.Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = F.CodigoC
                    GROUP BY F.CodigoC, Cliente 
                    ORDER BY Cliente ";
                $Nombre_Campo = "Cliente";
            case "Grupo/Zona":
                $sSQL = "SELECT C.Grupo, COUNT(Factura) AS Fact_Proc 
                        FROM Clientes AS C, Facturas AS F 
                        WHERE F.Item = '" . $NumEmpresa . "' 
                        AND F.Periodo = '" . $Periodo_Contable . "' ";
                //if ($Modulo == "EJECUTIVOS") $sSQL .= "AND F.Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = F.CodigoC 
                    GROUP BY C.Grupo 
                    ORDER BY C.Grupo ";
                $Nombre_Campo = "Grupo";
                break;
            case "Vendedor":
                $sSQL = "SELECT C.Codigo, C.Cliente, COUNT(CR.Codigo) AS Fact_Proc 
                        FROM Clientes AS C, Catalogo_Rol_Pagos AS CR 
                        WHERE CR.Item = '" . $NumEmpresa . "' 
                        AND CR.Periodo = '" . $Periodo_Contable . "' ";
                //if ($Modulo == "EJECUTIVOS") $sSQL .= "AND CR.Codigo = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = CR.Codigo 
                        GROUP BY C.Codigo, C.Cliente 
                        ORDER BY Cliente ";
                $Nombre_Campo = "Cliente";
                break;
            case "Ciudad":
                $sSQL = "SELECT Ciudad, COUNT(Factura) AS Fact_Proc 
                        FROM Clientes AS C, Facturas AS F 
                        WHERE F.Item = '" . $NumEmpresa . "' 
                        AND F.Periodo = '" . $Periodo_Contable . "' ";
                //if ($Modulo == "EJECUTIVOS") $sSQL .= "AND F.Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = F.CodigoC 
                        GROUP BY Ciudad 
                        ORDER BY Ciudad ";
                $Nombre_Campo = "Ciudad";
                break;
            case "Factura":
                $sSQL = "SELECT (TC + ' ' +  Serie + ' ' + CAST(Factura AS VARCHAR)) AS TipoFactura 
                        FROM Facturas 
                        WHERE Item = '" . $NumEmpresa . "' 
                        AND Periodo = '" . $Periodo_Contable . "' 
                        AND TC NOT IN ('C','P') ";
                //if ($Modulo == "EJECUTIVOS") $sSQL .= "AND Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "GROUP BY TC, Serie, Factura 
                    ORDER BY TC, Serie, Factura DESC ";
                $Nombre_Campo = "TipoFactura";
                break;
            case "Forma_Pago":
                $sSQL = "SELECT Forma_Pago 
                        FROM Facturas 
                        WHERE Item = '" . $NumEmpresa . "' 
                        AND Periodo = '" . $Periodo_Contable . "' 
                        AND TC NOT IN ('C','P') ";
                //if ($Modulo == "EJECUTIVOS") $sSQL .= "AND Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "GROUP BY Forma_Pago 
                    ORDER BY Forma_Pago ";
                $Nombre_Campo = "Forma_Pago";
                break;
            case "Tipo Documento":
                $sSQL = "SELECT TC, COUNT(Factura) AS Fact_Proc 
                        FROM Clientes AS C, Facturas AS F 
                        WHERE F.Item = '" . $NumEmpresa . "' 
                        AND F.Periodo = '" . $Periodo_Contable . "' 
                        AND TC NOT IN ('C','P') ";
                //if ($Modulo == "EJECUTIVOS") $sSQL .= "AND F.Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = F.CodigoC 
                        GROUP BY TC 
                        ORDER BY TC ";
                $Nombre_Campo = "TC";
                break;
            case "Plan_Afiliado":
                $sSQL = "SELECT Plan_Afiliado, COUNT(Factura) AS Fact_Proc 
                        FROM Clientes AS C, Facturas AS F 
                        WHERE F.Item = '" . $NumEmpresa . "' 
                        AND F.Periodo = '" . $Periodo_Contable . "' 
                        AND LENGTH(C.Plan_Afiliado) > 3 
                        AND TC NOT IN ('C','P') ";
                //if ($Modulo == "EJECUTIVOS") $sSQL .= "AND F.Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = F.CodigoC 
                        GROUP BY Plan_Afiliado 
                        ORDER BY Plan_Afiliado ";
                $Nombre_Campo = "Plan_Afiliado";
                break;
            case "Cuenta_No":
                $sSQL = "SELECT Cuenta_No
                        FROM Clientes_Datos_Extras 
                        WHERE Item = '" . $NumEmpresa . "' 
                        GROUP BY Cuenta_No 
                        ORDER BY Cuenta_No ";
                $Nombre_Campo = "Cuenta_No";
                break;
            case "Producto":
                $sSQL = "SELECT (Codigo_Inv + ' - ' + Producto) AS Codigos 
                        FROM Catalogo_Productos 
                        WHERE Item = '" . $NumEmpresa . "' 
                        AND Periodo = '" . $Periodo_Contable . "' 
                        ORDER BY Codigo_Inv ";
                $Nombre_Campo = "Codigos";
                break;
            case "DescItem":
                $sSQL = "SELECT Desc_Item 
                        FROM Catalogo_Productos 
                        WHERE Item = '" . $NumEmpresa . "' 
                        AND Periodo = '" . $Periodo_Contable . "' 
                        AND Desc_Item <> '" . G_NINGUNO . "' 
                        GROUP BY Desc_Item 
                        ORDER BY Desc_Item ";
                $Nombre_Campo = "Desc_Item";
                break;
            case "Marca":
                $sSQL = "SELECT (CodMar + ' - ' + Marca) AS NomMarca 
                        FROM Catalogo_Marcas 
                        WHERE Item = '" . $NumEmpresa . "' 
                        AND Periodo = '" . $Periodo_Contable . "' 
                        AND CodMar <> '" . G_NINGUNO . "' 
                        ORDER BY Marca ";
                $Nombre_Campo = "NomMarca";
                break;
            default:
                $sSQL = "SELECT Codigo, Cliente 
                        FROM Clientes 
                        WHERE Codigo = '-' ";
                $Nombre_Campo = "Cliente";
                break;
        }
        return array(
            "data" => $this->db->datos($sSQL),
            "nombreCampo" => $Nombre_Campo
        );
        //SelectDB_Combo DCCliente, AdoCliente, sSQL, Nombre_Campo

    }

    function Tipo_Consulta_CxC($Tipo, $tipoConsulta, $FechaIni, $FechaFin, $Actualiza_Buses, $TipoFactura, $MBFechaF, $SQL_Server = true)
    {
        if ($Actualiza_Buses) {
            if ($SQL_Server) {
                $sSQL = "UPDATE Facturas " .
                    "SET Forma_Pago = SUBSTRING(DF.Producto, 1, 10) 
                    FROM Facturas AS F, Detalle_Factura AS DF ";
            } else {
                $sSQL = "UPDATE Facturas AS F, Detalle_Factura AS DF
                    SET F.Forma_Pago = SUBSTRING(DF.Producto, 1, 10) ";
            }
            $sSQL .= "WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND F.TC = DF.TC 
                AND F.Serie = DF.Serie 
                AND F.Autorizacion = DF.Autorizacion 
                AND F.CodigoC = DF.CodigoC 
                AND F.Fecha = DF.Fecha 
                AND F.Factura = DF.Factura 
                AND F.Item = DF.Item 
                AND F.Periodo = DF.Periodo ";
            Ejecutar_SQL_SP($sSQL);
        }
        if ($TipoFactura == "") {
            $TipoFactura = G_NINGUNO;
        }

        $sSQL = "SELECT F.T, F.Razon_Social, ";

        if ($_SESSION['INGRESO']['SiUnidadEducativa']) {
            $sSQL .= "C.Cliente, ";
        }

        $sSQL .= "F.Fecha, F.Fecha_V, F.TC, F.Serie, F.Factura, ";

        if ($Tipo == "R") {
            $sSQL .= "F.Con_IVA, F.Sin_IVA, F.SubTotal, F.IVA, (F.Descuento + F.Descuento2) As Total_Descuento, F.Servicio, F.Total_MN, 
                    F.Total_Abonos, F.Saldo_MN, F.Autorizacion, F.Cta_CxP, F.Total_Ret_Fuente, F.Total_Ret_IVA_B, F.Total_Ret_IVA_S, ";
        } else {
            $sSQL .= "F.Total_MN, F.Abonos_MN, F.Saldo_MN, F.Total_ME, F.Saldo_ME, F.Autorizacion, F.RUC_CI As RUC_CI_SRI, ";
        }

        if ($_SESSION['INGRESO']['SiUnidadEducativa']) {
            $sSQL .= "C.CI_RUC, ";
        }

        $sSQL .= "F.Forma_Pago, C.Telefono, C.Celular, C.Ciudad, C.Direccion, C.DireccionT, C.Email, C.Grupo, ";

        if ($SQL_Server) {
            $sSQL .= "DATEDIFF(day, '" . BuscarFecha($MBFechaF) . "', F.Fecha_V) As Dias_De_Mora, ";
        } else {
            $sSQL .= "DATEDIFF('d', #" . BuscarFecha($MBFechaF) . "#, F.Fecha_V) As Dias_De_Mora, ";
        }

        $sSQL .= "A.Nombre_Completo As Ejecutivo, C.Plan_Afiliado As Sectorizacion, A.Cod_Ejec, F.Chq_Posf 
            FROM Facturas As F, Clientes As C, Accesos As A 
            WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
            AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
            " . $tipoConsulta . "
            AND C.Codigo = F.CodigoC 
            AND A.Codigo = F.Cod_Ejec
            AND F.TC NOT IN ('C','P') ";

        if ($Tipo == "V") {
            $Opcion = '13';
            $sSQL .= "ORDER BY A.Nombre_Completo, C.Grupo, ";
            if ($_SESSION['INGRESO']['SiUnidadEducativa']) {
                $sSQL .= "C.Cliente, F.Razon_Social, ";
            } else {
                $sSQL .= "F.Razon_Social, ";
            }
        }
        if ($Tipo == "C") {
            $Opcion = '9';
            if ($_SESSION['INGRESO']['SiUnidadEducativa']) {
                $sSQL .= "ORDER BY C.Cliente, F.Razon_Social, ";
            } else {
                $sSQL .= "ORDER BY F.Razon_Social, ";
            }
        }
        if ($Tipo == "F") {
            $Opcion = '10';
            $sSQL .= "ORDER BY ";
        }
        if ($Tipo == "R") {
            $Opcion = '19';
            if ($_SESSION['INGRESO']['SiUnidadEducativa']) {
                $sSQL .= "ORDER BY C.Cliente, F.Razon_Social, ";
            } else {
                $sSQL .= "ORDER BY F.Razon_Social, ";
            }
        }
        $sSQL .= "F.TC, F.Serie, F.Fecha, F.Factura ";
        //Select_Adodc_Grid($DGQuery, $AdoQuery, $sSQL, "", "", true, "CxC Cartera");
        //print_r($sSQL);
        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'Facturas', '', 'CXC CLIENTES POR VENDEDOR', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res, 'Opcion' => $Opcion);

    }

    function Listado_Tarjetas()
    {
        $sSQL = "SELECT CM.Tipo_Cta,C.Grupo,CM.Representante,CM.Cedula_R,CM.Telefono_R,C.Cliente,C.Direccion,CM.Cta_Numero,CM.Cod_Banco,CM.Caducidad
              FROM Clientes As C,Clientes_Matriculas As CM 
              WHERE C.FA <> 0 
              AND CM.Item = '" . $_SESSION['INGRESO']['item'] . "' 
              AND CM.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
              AND LEN(CM.Tipo_Cta) > 1 
              AND C.Codigo = CM.Codigo 
              ORDER BY CM.Tipo_Cta, C.Grupo, C.Cliente";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True

        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'CLIENTES', '', '', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);
    }

    function Estado_Cuenta_Cliente($MBFechaI, $fechaSistema, $FA)
    {
        $sSQL = "SELECT C.Cliente, RCC.T, RCC.TC, RCC.Serie, RCC.Factura, RCC.Fecha, RCC.Detalle, RCC.Anio, RCC.Mes, RCC.Cargos, RCC.Abonos, RCC.CodigoC,
              C.Email, C.EmailR, C.Direccion 
              FROM Reporte_Cartera_Clientes As RCC, Clientes As C 
              WHERE RCC.Item = '" . $_SESSION['INGRESO']['item'] . "' 
              AND RCC.CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
              AND RCC.T <> 'A' 
              AND RCC.CodigoC = C.Codigo 
              ORDER BY C.Cliente, RCC.TC, RCC.Serie, RCC.Factura, RCC.Anio, RCC.Mes, RCC.ID ";

        $Opcion = '19';
        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'CLIENTES', '', 'REPORTE CARTERA CLIENTES', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res, 'Opcion' => $Opcion);
    }

    function Buscar_Malla()
    {
        $sSQL = "SELECT Codigo, Cliente
              FROM Clientes
              WHERE Codigo = '-' ";
        //SelectDB_Combo DCCliente, AdoCliente, sSQL, "Cliente"
        return $this->db->datos($sSQL);
    }

    function Listado_Facturas_Por_Meses($JE, $MesS) //REV
    {
        $sSQL = "UPDATE Detalle_Factura 
           SET Mes_No = " . $JE . " 
           WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
           AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
           AND Mes = '" . $MesS . "' 
           AND Mes_No = 0 ";
        Ejecutar_SQL_SP($sSQL);
    }

    function Listado_Facturas_Por_Meses2($JE, $MesS, $SQL_Server) //REV
    {
        $sSQL = "UPDATE Clientes 
                SET X = '.' 
                WHERE X <> '.' ";
        Ejecutar_SQL_SP($sSQL);
        $sSQL = "DELETE * 
                FROM Saldo_Diarios 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
                AND TP = 'CXCP' ";
        Ejecutar_SQL_SP($sSQL);

        if ($SQL_Server) {
            $sSQL = "UPDATE Clientes 
            SET X = 'A' 
            FROM Clientes As C,Facturas As F ";
        } else {
            $sSQL = "UPDATE Clientes As C,Facturas As F 
            SET C.X = 'A' ";
        }

        $sSQL .= "WHERE F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                " . Buscar_x_Patron(True) . "
                AND C.Codigo = F.CodigoC ";
        Ejecutar_SQL_SP($sSQL);
    }

    function Catastro_Registro_Datos_Clientes($FechaIni, $FechaFin, $tipoConsulta)
    {
        $sSQL = "SELECT C.Cliente,C.CI_RUC,C.TD,C.Est_Civil,C.Sexo,C.Ciudad,C.Prov,C.Pais,F.T,F.Serie,F.Factura,
                     F.Fecha,F.Fecha_C,F.Fecha_V,F.Total_MN As Total,F.Total_Efectivo,F.Total_Banco,F.Total_Ret_Fuente,
                     F.Total_Ret_IVA_B,F.Total_Ret_IVA_S,F.Otros_Abonos,F.Total_Abonos,F.Saldo_Actual,F.CodigoC,F.TC,F.Autorizacion 
                     FROM Facturas As F,Clientes As C 
                     WHERE F.Item =  '" . $_SESSION['INGRESO']['item'] . "'
                     AND F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                     " . $tipoConsulta . "
                     AND F.CodigoC = C.Codigo 
                     ORDER BY C.Cliente,F.Factura,F.Fecha ";

        return $this->db->datos($sSQL);
    }

    function Ventas_x_Excel($FechaIni, $FechaFin)
    {
        $sSQL = "SELECT T,TC,Fecha,'" . $_SESSION['INGRESO']['Nombre_Comercial'] . "' As Razon_Social,'" . $_SESSION['INGRESO']['RUC'] . "' As RUC,Serie,Autorizacion,
                Factura,Con_IVA,Sin_IVA,SubTotal,IVA,Total_MN,'999999' As Serie_R,'0' As Secuencial_R,
                '" . $_SESSION['INGRESO']['RUC'] . "' As Autorizacion_R,'312' As Cod_Ret,'0' As Total_Retenido, '5' As Cta_Gasto 
                FROM Facturas 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND T <> 'A' 
                ORDER BY TC,Serie,Factura ";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'FACTURAS', '', '', false, false, false, 1, 1, 1, 100);
        //excel_generico('VENTAS POR EXCEL', $res);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);

    }

    function Enviar_Emails_Facturas_Recibos($parametros, $FechaIni, $FechaFin, $TipoEnvio, $tipoConsulta)
    {
        $CheqAbonos = $parametros['CheqAbonos'];
        $DCCxC = $parametros['DCCxC'];
        $DocDesde = $parametros['TxtDocDesde'];
        $DocHasta = $parametros['TxtDocHasta'];

        $NumEmpresa = $_SESSION['INGRESO']['item'];
        $Periodo_Contable = $_SESSION['INGRESO']['periodo'];
        $Cta_Aux_Mail = G_NINGUNO;
        if ($CheqAbonos != 0) {
            $Cta_Aux_Mail = SinEspaciosIzq($DCCxC);

            $sSQL = "UPDATE Facturas 
                    SET X = 'X' 
                    WHERE Item = '" . $NumEmpresa . "' 
                    AND Periodo = '" . $Periodo_Contable . "' 
                    AND Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' ";
            Ejecutar_SQL_SP($sSQL);

            $sSQL = "UPDATE Facturas 
                SET X = '.' 
                FROM Facturas As F, Trans_Abonos As TA 
                WHERE F.Item = '" . $NumEmpresa . "' 
                AND F.Periodo = '" . $Periodo_Contable . "' 
                AND F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND TA.Cta = '" . $Cta_Aux_Mail . "' 
                AND F.Item = TA.Item 
                AND F.Periodo = TA.Periodo 
                AND F.TC = TA.TP 
                AND F.Serie = TA.Serie 
                AND F.Factura = TA.Factura ";
            Ejecutar_SQL_SP($sSQL);
        }

        $sSQL = "SELECT C.Cliente,F.CodigoC,F.Clave_Acceso,F.Estado_SRI,F.TC,F.Fecha,F.Fecha_V,F.Serie,F.Factura,F.Hora_Aut,F.Fecha_Aut,F.Autorizacion,
                F.Saldo_MN,C.Email,C.Email2,C.EmailR,C.CI_RUC 
                FROM Facturas As F,Clientes As C 
                WHERE F.Item = '" . $NumEmpresa . "' 
                AND F.Periodo = '" . $Periodo_Contable . "' 
                AND F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' ";

        if ($DocDesde > 0 && $DocHasta > 0 && $DocDesde <= $DocHasta) {
            $sSQL .= "AND F.Factura BETWEEN " . $DocDesde . " and " . $DocHasta . " ";
        }
        if ($TipoEnvio == "FA") {
            $sSQL .= "AND LEN(F.Autorizacion) >= 13 ";
        }
        if ($Cta_Aux_Mail != G_NINGUNO) {
            $sSQL .= "AND F.X = '.' ";
        }
        $sSQL .= $tipoConsulta . " 
            AND F.TC IN ('FA','NV') 
            AND F.CodigoC = C.Codigo 
            ORDER BY F.Factura ";

        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'FACTURAS', '', '', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res, 'response' => 4, 'tipoEnvio' => $TipoEnvio);
    }

    function Generar_Recibo_PDF($FA)
    {
        $res = 1;
        $Comprobante = "Recibo No " . $FA['Serie'] . "-" . sprintf("%09d", $FA['Factura']);
        $NumEmpresa = $_SESSION['INGRESO']['item'];
        $Periodo_Contable = $_SESSION['INGRESO']['periodo'];

        $sSQL = "SELECT F.*,C.Cliente,C.CI_RUC,C.Telefono,C.TelefonoT,C.Direccion,C.DireccionT," .
            "C.Representante,C.Grupo,C.Codigo,C.Ciudad,C.Email,C.Email2,C.EmailR,C.CI_RUC_R,C.TD,C.TD_R,C.DirNumero " .
            "FROM Facturas AS F,Clientes AS C " .
            "WHERE F.Item = '" . $NumEmpresa . "' " .
            "AND F.Periodo = '" . $Periodo_Contable . "' " .
            "AND F.TC = '" . $FA['TC'] . "' " .
            "AND F.Serie = '" . $FA['Serie'] . "' " .
            "AND F.Autorizacion = '" . $FA['Autorizacion'] . "' " .
            "AND F.CodigoC = '" . $FA['CodigoC'] . "' " .
            "AND F.Factura = " . $FA['Factura'] . " " .
            "AND C.Codigo = F.CodigoC ";
        $AdoDBFac = $this->db->datos($sSQL);

        if (count($AdoDBFac) > 0) {
            foreach ($AdoDBFac as $row) {
                $TFA['Grupo'] = $row['Grupo'];
                $TFA['Autorizacion'] = $row['Autorizacion'];
                $TFA['CodigoC'] = $row['CodigoC'];
                $TFA['DireccionC'] = $row['Direccion'];
                $TFA['Cliente'] = $row['Cliente'];
                $TFA['CI_RUC'] = strlen($row['RUC_CI']) > 1 ? $row['RUC_CI'] : "";
                if (strlen($row['Razon_Social']) > 1 && strlen($row['RUC_CI']) > 1) {
                    $sSQL = "SELECT Codigo,Grupo_No,Representante,Cedula_R,Lugar_Trabajo_R,Telefono_R,Email_R " .
                        "FROM Clientes_Matriculas " .
                        "WHERE Item = '" . $NumEmpresa . "' " .
                        "AND Periodo = '" . $Periodo_Contable . "' " .
                        "AND Codigo = '" . $TFA['CodigoC'] . "' ";
                    $AdoDBDet = $this->db->datos($sSQL);

                    if (count($AdoDBDet) > 0) {
                        foreach ($AdoDBDet as $row) {
                            $TFA['Curso'] = $TFA['DireccionC'];
                            $TFA['EmailR'] = $row['Email_R'];
                            $TFA['DireccionC'] = $row['Lugar_Trabajo_R'];
                            $TFA['Comercial'] = $row['Representante'];
                        }
                    }
                }
            }
        }

        $sSQL = "SELECT CodigoC, SUM(Saldo_MN) AS Pendiente " .
            "FROM Facturas " .
            "WHERE Item = '" . $NumEmpresa . "' " .
            "AND Periodo = '" . $Periodo_Contable . "' " .
            "AND CodigoC = '" . $FA['CodigoC'] . "' " .
            "AND TC = '" . $FA['TC'] . "' " .
            "AND Saldo_MN > 0 " .
            "AND T <> 'A' " .
            "GROUP BY CodigoC ";
        $AdoDBAux = $this->db->datos($sSQL);

        $sSQL = "SELECT * " .
            "FROM Detalle_Factura " .
            "WHERE Item = '" . $NumEmpresa . "' " .
            "AND Periodo = '" . $Periodo_Contable . "' " .
            "AND TC = '" . $FA['TC'] . "' " .
            "AND Serie = '" . $FA['Serie'] . "' " .
            "AND Autorizacion = '" . $FA['Autorizacion'] . "' " .
            "AND Factura = " . $FA['Factura'] . " " .
            "ORDER BY ID,Codigo ";
        $AdoDBDet = $this->db->datos($sSQL);

        //imprimirDocEle_fac($TFA, $AdoDBDet, false, $Comprobante, null, 'factura', null, null, false, false, false);
        $res = imprimir_Generar_Recibo_PDF($TFA, $AdoDBDet);
        if(isset($res['nombre']) && $res['nombre']) {
            return ['nombre' => $res['nombre']];
        }
        
        return 1;

    }

    function Por_Buses($Patron_Busqueda)
    {
        $sSQL = "SELECT Cliente,Telefono,Direccion As Curso,DireccionT As Direccion_Ruta,Contacto As Ruta
                FROM Clientes 
                WHERE Plan_Afiliado = '" . $Patron_Busqueda . "' 
                AND FA <> 0 
                ORDER BY Cliente ";

        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'TRANS_ABONOS', '', 'HISTORIAL DE FACTURAS', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);

    }

    function SMAbonos_Anticipados($FechaIni, $FechaFin, $tipoConsulta)
    {
        $sSQL = "SELECT C.Cliente, C.CI_RUC, TS.Cta, TS.Fecha, TS.TP, TS.Numero, TS.Creditos As Abono
          FROM Trans_SubCtas As TS, Clientes As C 
          WHERE TS.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
          AND TS.Item = '" . $_SESSION['INGRESO']['item'] . "' 
          AND TS.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
          AND TS.T <> 'A' 
          " . $tipoConsulta . "
          AND TS.Codigo = C.Codigo
          ORDER BY C.Cliente, TS.Cta, TS.Fecha, TS.TP, TS.Numero ";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'TRANS_ABONOS', '', 'HISTORIAL DE FACTURAS', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);

    }

    function Contra_Cta_Abonos($FechaIni, $FechaFin, $CheqAbonos, $DCCxC)
    {
        $sSQL = "SELECT CC.Cuenta, C.Cliente, TS.Fecha, TS.TP, TS.Numero, TS.Debitos, TS.Creditos, T.Cta AS Contra_Cta, TS.Cta
                FROM Trans_SubCtas AS TS, Transacciones AS T, Catalogo_Cuentas AS CC, Clientes AS C 
                WHERE TS.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                AND TS.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND TS.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND TS.T <> 'A' ";
        if ($CheqAbonos == 1) {
            $ContraCta = SinEspaciosIzq($DCCxC);
            $sSQL .= "AND TS.Cta = '" . $ContraCta . "' ";
            if (substr($ContraCta, 0, 1) == "1") {
                $sSQL .= "AND TS.Debitos > 0  ";
            }
            if (substr($ContraCta, 0, 1) == "2") {
                $sSQL .= "AND TS.Creditos > 0 ";
            }
        }
        $sSQL .= "AND TS.Periodo = T.Periodo 
                AND TS.Periodo = CC.Periodo 
                AND TS.Item = T.Item 
                AND TS.Item = CC.Item 
                AND TS.TP = T.TP 
                AND TS.Numero = T.Numero 
                AND T.Cta = CC.Codigo 
                AND TS.Codigo = C.Codigo 
                AND TS.Cta <> T.Cta 
                ORDER BY T.Cta, C.Cliente, TS.Fecha, TS.TP, TS.Numero ";
        //Select_Adodc_Grid($DGQuery, $AdoQuery, $sSQL, "", "", true);
        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'TRANS_ABONOS', '', 'ABONOS ANTICIPADOS DE CLIENTES', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);

    }

    function Tipo_Pago_Cliente()  //ERROR CONSULTA
    {
        $sSQL = "SELECT C.Grupo, C.Cliente, C.CI_RUC, CM.Representante, CM.Cedula_R, CM.Telefono_R,
                    CM.Tipo_Cta, CM.Cta_Numero, CM.Caducidad, CM.Cod_Banco, TRSRI.Descripcion As Institucion_Financiera 
                    FROM Clientes As C, Clientes_Matriculas As CM, Tabla_Referenciales_SRI As TRSRI 
                    WHERE CM.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND CM.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                    AND TRSRI.Tipo_Referencia = 'BANCOS Y COOP' 
                    AND C.Codigo = CM.Codigo 
                    AND CM.Cod_Banco = TRSRI.Codigo 
                    ORDER BY CM.Tipo_Cta, C.Grupo, C.Cliente ";

        $res = $this->db->datos($sSQL);
        $num_filas = count($res);

        $datos = grilla_generica_new($sSQL, 'CLIENTES', '', '', false, false, false, 1, 1, 1, 100);
        return array('DGQuery' => $datos, 'num_filas' => $num_filas, 'AdoQuery' => $res);
    }
}

?>