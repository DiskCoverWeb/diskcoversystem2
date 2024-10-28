<?php
/** 
 * AUTOR DE RUTINA	: Dallyana Vanegas
 * FECHA CREACION	: 03/01/2024
 * FECHA MODIFICACION: 29/01/2024
 * DESCIPCION : Clase que se encarga de manejar la interfaz de la pantalla de recaudacion de bancos CxC   
 */
require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");
@session_start();

class FRecaudacionBancosCxCM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function SelectCombo_DCGrupoI_DCGrupoF()
    {
        $sql = "SELECT Grupo, COUNT(Grupo) AS Cantidad 
                FROM Clientes 
                WHERE FA != 0";

        if ($_SESSION['INGRESO']['Mas_Grupos']) {
            $sql .= " AND Item = '" . $_SESSION['INGRESO']['item'] . "'";
        }

        $sql .= "GROUP BY Grupo ORDER BY Grupo ";

        return $this->db->datos($sql);
    }

    function AdoAux()
    {
        $sql = "SELECT * 
                FROM Catalogo_Lineas 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND TL != 0
                ORDER BY Codigo, CxC";

        return $this->db->datos($sql);
    }
    function Select_AdoProducto()
    {
        $sql = "SELECT * 
                FROM Catalogo_Productos
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "'
                AND TC = 'P'
                ORDER BY Codigo_Inv";

        return $this->db->datos($sql);
    }

    function SelectDB_Combo_DCBanco()
    {
        $sql = "SELECT Codigo + '  ' + Cuenta AS NomCuenta, Codigo
                FROM Catalogo_Cuentas 
                WHERE TC = 'BA' 
                AND DG = 'D' 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "'
                ORDER BY Codigo";

        return $this->db->datos($sql);
    }

    function SelectDB_Combo_DCEntidad()
    {
        $sql = "SELECT Descripcion, Abreviado, ID
                FROM Tabla_Referenciales_SRI
                WHERE Tipo_Referencia = 'BANCOS Y COOP'
                AND Abreviado != '.' 
                AND TFA != 'False'
                ORDER BY Descripcion";

        return $this->db->datos($sql);
    }

    function MBFechaF_LostFocus()
    {
        $sql = "SELECT *
                FROM Fechas_Balance
                WHERE Detalle = 'Deuda Pendiente'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "' ";

        return $this->db->datos($sql);
    }

    function MBFechaF_LostFocusUpdate($fecha)
    {
        $sql = "UPDATE Fechas_Balance
                SET Fecha_Inicial = '" . $fecha . "', Fecha_Final = '" . $fecha . "'
                WHERE Detalle = 'Deuda Pendiente'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "' ";

        return $this->db->datos($sql);
    }

    function Query1EnviarRubros()
    {
        $sSQL = "UPDATE Facturas 
                SET Anio_Mes = SUBSTRING(DF.Ticket,1,4) + '-' + RIGHT(REPLICATE('0', 2 - LEN(CAST(DF.Mes_No As VARCHAR))), 2) + CAST(DF.Mes_No As VARCHAR)
                FROM Facturas As F, Detalle_Factura As DF 
                WHERE F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND LEN(Anio_Mes) = 1 
                AND LEN(DF.Ticket) = 4 
                AND F.Periodo = DF.Periodo 
                AND F.Item = DF.Item  
                AND F.TC = DF.TC  
                AND F.Serie = DF.Serie  
                AND F.Factura = DF.Factura";
        return Ejecutar_SQL_SP($sSQL);
    }

    function Query2EnviarRubros($parametros)
    {
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $DCGrupoI = $parametros['DCGrupoI'];
        $DCGrupoF = $parametros['DCGrupoF'];
        $CheqRangos = $parametros['CheqRangos'];

        $sSQL = "SELECT F.CodigoC, C.Actividad, C.Cliente, C.CI_RUC, C.Direccion, C.Grupo, F.Fecha, F.Serie, F.Factura, F.Total_MN, F.Saldo_MN, F.Anio_Mes
                FROM Facturas AS F, Clientes AS C 
                WHERE F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND F.Fecha BETWEEN '" . BuscarFecha($MBFechaI) . "' AND '" . BuscarFecha($MBFechaF) . "' 
                AND F.T = 'P' 
                AND F.Saldo_MN > 0 
                AND NOT F.TC IN ('C','P') ";

        if ($CheqRangos != 0) {
            $sSQL .= "AND C.Grupo BETWEEN '" . $DCGrupoI . "' AND '" . $DCGrupoF . "' ";
        }

        $sSQL .= "AND F.CodigoC = C.Codigo " .
            "ORDER BY C.Grupo, C.Cliente, F.Anio_Mes, F.Serie, F.Factura, CI_RUC, F.CodigoC, C.Actividad, C.Direccion, F.Fecha, F.Saldo_MN ";

        return $this->db->datos($sSQL);
        //Select_Adodc AdoPendiente, sSQL
    }


    function Query3EnviarRubros($parametros)
    {
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];

        $sSQL = "SELECT DF.*,C.Cliente,C.Grupo,C.CI_RUC,C.Direccion,CP.Item_Banco,CP.Desc_Item 
                FROM Detalle_Factura As DF,Clientes As C,Catalogo_Productos As CP 
                WHERE DF.Fecha BETWEEN  '" . BuscarFecha($MBFechaI) . "' AND '" . BuscarFecha($MBFechaF) . "'
                AND DF.Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND DF.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND DF.T = 'P' 
                AND DF.CodigoC = C.Codigo 
                AND DF.Item = CP.Item 
                AND DF.Periodo = CP.Periodo 
                AND DF.Codigo = CP.Codigo_Inv 
                ORDER BY C.Grupo,C.Cliente, DF.Fecha";

        return $this->db->datos($sSQL);
        //Select_Adodc AdoDetalle, sSQL
    }

    function Query4EnviarRubros($parametros)
    {
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];

        $sSQL = "SELECT F.CodigoC,C.Actividad,C.Cliente,C.Grupo,CI_RUC,C.Direccion,SUM(Saldo_MN) As Saldo_Pend
                FROM Facturas As F,Clientes As C 
                WHERE F.Fecha BETWEEN '" . BuscarFecha($MBFechaI) . "' AND '" . BuscarFecha($MBFechaF) . "'
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'  
                AND F.T = 'P' 
                AND NOT F.TC IN ('C','P') 
                AND F.CodigoC = C.Codigo 
                GROUP BY C.Grupo,C.Cliente,CI_RUC,F.CodigoC,C.Actividad,C.Direccion 
                HAVING SUM(Saldo_MN) > 0 
                ORDER BY C.Grupo,C.Cliente,CI_RUC";

        return $this->db->datos($sSQL);
        //Select_Adodc AdoFactura, sSQL

    }

    function Query5EnviarRubros($parametros)
    {
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];

        $sSQL = "SELECT F.*,C.Cliente,C.Grupo,C.CI_RUC,C.Direccion,C.Casilla 
                FROM Facturas As F,Clientes As C 
                WHERE F.Fecha BETWEEN '" . BuscarFecha($MBFechaI) . "' AND '" . BuscarFecha($MBFechaF) . "'
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND F.T != 'A' 
                AND F.CodigoC = C.Codigo 
                ORDER BY C.CI_RUC,C.Grupo,F.Fecha";

        return $this->db->datos($sSQL);
        //Select_Adodc AdoAux, sSQL
    }

    function EliminarAbonos($TA)
    {
        $sSQL = "DELETE FROM Trans_Abonos 
                WHERE Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND TP = 'FA' 
                AND Serie = '" . $TA['Serie'] . "' 
                AND Factura = " . $TA['Factura'] . " 
                AND Recibo_No = '" . $TA['Serie'] . "'";
        Ejecutar_SQL_SP($sSQL);
    }

    function AlumnosClientesActivados()
    {
        $sSQL = "SELECT Codigo, Cliente, CI_RUC, Direccion, Grupo, Email, Email2, EmailR 
                FROM Clientes 
                WHERE Codigo != '.' 
                ORDER BY CI_RUC";
        return $this->db->datos($sSQL);
    }

    function sqlCaseElse($TipoDoc, $SerieFactura, $Autorizacion, $Factura_No)
    {
        $sSQL = "SELECT F.*,C.CI_RUC 
            FROM Facturas As F, Clientes As C 
            WHERE F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
            AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND F.TC = '" . $TipoDoc . "' 
            AND F.Serie = '" . $SerieFactura . "' 
            AND F.Autorizacion = '" . $Autorizacion . "' 
            AND F.Factura = " . $Factura_No . " 
            AND F.CodigoC = C.Codigo ";

        return $this->db->datos($sSQL);

    }

    function sqlIngresarAbonos($TA)
    {
        $sSQL = "SELECT CodigoC, Cta_CxP, TC, Vencimiento, Autorizacion, Saldo_MN, Fecha 
            FROM Facturas 
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
            AND T <> 'A' 
            AND TC = 'FA' 
            AND CodigoC = '" . $TA['CodigoC'] . "' 
            AND Serie = '" . $TA['Serie'] . "' 
            AND Factura = " . $TA['Factura'] . " 
            AND Saldo_MN > 0 ";

        return $this->db->datos($sSQL);

    }

    function sqlCoopJep($CheqMatricula, $MBFechaI, $MBFechaF)
    {
        $sSQL = "SELECT CI_RUC AS 'CODIGO ALUMNO', C.Cliente AS 'NOMBRE ALUMNO', C.Direccion AS 'CURSO', ";

        if ($CheqMatricula) {
            $sSQL .= "SUM(Saldo_MN) AS 'MATRICULA', '0' AS 'PENSION', ";
        } else {
            $sSQL .= "'0' AS 'MATRICULA', SUM(Saldo_MN) AS 'PENSION', ";
        }

        $sSQL .= "'' AS 'TRANSPORTE', '' AS 'REFRIGERIO', '' AS 'DERECHOS DE EXAMEN', 
                  '' AS 'DEUDA PENDIENTE', '' AS 'AGENDA', '' AS 'RECARGOS', '' AS 'TALLERES SEMINARIOS', '' AS 'OTROS', 
                    SUM(Saldo_MN) AS 'VALOR TOTAL', SUM(Con_IVA) AS 'BICONIVA', '' AS 'ICE', SUM(IVA) AS 'IVA',
                    SUM(Sin_IVA) AS 'BISINIVA', '' AS 'BI NO OBJETO IVA', C.Email AS 'MAIL' 
                    FROM Facturas AS F, Clientes AS C, Clientes_Matriculas AS CM 
                    WHERE F.Fecha BETWEEN '" . BuscarFecha($MBFechaI) . "' AND '" . BuscarFecha($MBFechaF) . "' 
                    AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                    AND F.T = 'P' 
                    AND NOT F.TC IN ('C', 'P') 
                    AND F.CodigoC = C.Codigo 
                    AND F.CodigoC = CM.Codigo 
                    AND F.Periodo = CM.Periodo 
                    AND F.Item = CM.Item 
                    GROUP BY C.CI_RUC, C.Cliente, C.Direccion, CM.TD, CM.Cedula_R, CM.Representante, C.Celular, C.Email 
                    HAVING SUM(Saldo_MN) > 0 
                    ORDER BY C.Direccion, C.Cliente, C.CI_RUC";

        return $this->db->datos($sSQL);
    }

}
?>