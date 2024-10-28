<?php

require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");

class ISubCtasM
{

    private $db;

    public function __construct()
    {
        $this->db = new db();
    }

    public function ListarSubCtas($parametros)
    {
        $TxtCodigo = $_SESSION['INGRESO']['item'] . "0000000";
        $sql = "SELECT * 
                FROM Catalogo_Cuentas
                WHERE SUBSTRING(Codigo,1,1) IN ('4', '5')
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                ORDER BY Codigo";
        $AdoCatalogo = $this->db->datos($sql);

        $sql = "SELECT Codigo, Detalle, Nivel, Agrupacion, (CAST(Codigo AS NVARCHAR(MAX)) + SPACE(5) + CAST(Detalle AS NVARCHAR(MAX)) + SPACE(54 - LEN(Detalle)) + CAST(Nivel AS NVARCHAR(MAX))) AS Nombre_Cta
                FROM Catalogo_SubCtas
                WHERE TC = '" . $parametros['TipoCta'] . "'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
        if ($parametros['TipoCta'] == 'CC') {
            $sql .= "ORDER BY Codigo, Detalle";
        } else {
            $sql .= "ORDER BY Nivel,Agrupacion DESC,Detalle,Codigo";
        }
        $AdoSubCta = $this->db->datos($sql);
        return array("AdoCatalogo" => $AdoCatalogo, "AdoSubCta" => $AdoSubCta, "TxtCodigo" => $TxtCodigo);
    }

    public function Listar_Detalle_Cta($FCta)
    {
        if ($FCta == "")
            $FCta = G_NINGUNO;

        $sql = "SELECT * 
                FROM Catalogo_Cuentas
                WHERE SUBSTRING(Codigo,1,1) IN ('4', '5')
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND Codigo = '" . $FCta . "'
                ORDER BY Codigo";
        $AdoCatalogo = $this->db->datos($sql);

        if (count($AdoCatalogo) > 0) {
            return $AdoCatalogo[0]['Cuenta'];
        }
        return "";
    }

    public function LlenarCta($parametros)
    {
        $TxtCodigo = $_SESSION['INGRESO']['item'] . "0000000";
        $sSQL = "SELECT * 
                 FROM Catalogo_SubCtas
                 WHERE Codigo = '" . $parametros['CodigoCta'] . "'
                 AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                 AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
        $AdoSubCtal = $this->db->datos($sSQL);
        if (count($AdoSubCtal) > 0) {
            $AdoSubCtal[0]['Cta_Reembolso'] = FormatoCodigoCta($AdoSubCtal[0]['Cta_Reembolso']);
            $AdoSubCtal[0]['Label5'] = " " . $this->Listar_Detalle_Cta($AdoSubCtal[0]['Cta_Reembolso']);
            $AdoSubCtal[0]['Fecha_D'] = $AdoSubCtal[0]['Fecha_D']->format('Y-m-d');
            $AdoSubCtal[0]['Fecha_H'] = $AdoSubCtal[0]['Fecha_H']->format('Y-m-d');
            return array('response' => 1, 'AdoSubCta1' => $AdoSubCtal);
        } else {
            return array('response' => 0, 'TxtCodigo' => $TxtCodigo);
        }
    }

    public function Eliminar($parametros)
    {
        $sql = "SELECT Codigo
                FROM Trans_SubCtas
                WHERE Codigo = '" . $parametros['Cadena'] . "'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
        return $this->db->datos($sql);
    }

    public function EliminarSubCta($parametros)
    {
        $sql = "DELETE * 
                FROM Catalogo_SubCtas
                WHERE Codigo = '" . $parametros['Cadena'] . "'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
        return Ejecutar_SQL_SP($sql);
    }

    public function GrabarCta($parametros)
    {

        if ($parametros['TxtNivel'] == G_NINGUNO)
            $parametros['TxtNivel'] = "00";

        $Codigo = "";
        $Numero = 0;

        $sql = "SELECT * 
                FROM Catalogo_SubCtas
                WHERE Codigo = '" . $parametros['CodigoCta'] . "'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND TC = '" . $parametros['TipoCta'] . "'";

        $AdoSubCtal = $this->db->datos($sql);
        if (count($AdoSubCtal) > 0) {
            $Codigo = $AdoSubCtal[0]['Codigo'];
        } else {
            if ($parametros['TipoCta'] == 'CC') {
                $Codigo = $parametros['CodigoCta'];
            } else {
                $Numero = ReadSetDataNum("SubCtas", True, True);
                $Codigo = FormatoCodigo($parametros['TextSubCta'], $Numero);
            }
        }

        $sql = "DELETE *
                FROM Catalogo_SubCtas
                WHERE Codigo = '" . $Codigo . "'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND TC = '" . $parametros['TipoCta'] . "'";
        Ejecutar_SQL_SP($sql);
        SetAdoAddNew("Catalogo_SubCtas");
        SetAdoFields("TC", $parametros['TipoCta']);
        SetAdoFields("Codigo", $Codigo);
        SetAdoFields("Detalle", $parametros['TextSubCta']);
        SetAdoFields("Presupuesto", (int) $parametros['TextPresupuesto']);
        SetAdoFields("Caja", 0);
        SetAdoFields("Agrupacion", 0);
        SetAdoFields("Bloquear", 0);
        SetAdoFields("Nivel", $parametros['TxtNivel']);
        SetAdoFields("Item", $_SESSION['INGRESO']['item']);
        SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
        SetAdoFields("Cta_Reembolso", CambioCodigoCta($parametros['MBoxCta']));
        SetAdoFields("Reembolso", (int) $parametros['TxtReembolso']);
        SetAdoFields("Fecha_D", $parametros['MBFechaI']);
        SetAdoFields("Fecha_H", $parametros['MBFechaF']);

        if ($parametros['CheqCaja'] == 1)
            SetAdoFields("Caja", 1);
        if ($parametros['CheqNivel'] == 1)
            SetAdoFields("Agrupacion", 1);
        if ($parametros['CheqBloquear'] == 1)
            SetAdoFields("Bloquear", 1);

        return SetAdoUpdate();
    }

}
?>