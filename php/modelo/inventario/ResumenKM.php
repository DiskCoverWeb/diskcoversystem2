<?php
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class ResumenKM
{
    private $db ;
    public $NumEmpresa;
    public $Periodo_Contable;

    function __construct()
    {
        $this->db = new db();
        $this->NumEmpresa = $_SESSION['INGRESO']['item'];
        $this->Periodo_Contable = $_SESSION['INGRESO']['periodo'];
    }

    function ExecuteDB($sSQL)
    {
        return $this->db->String_Sql($sSQL);
    }

    public function bodegas($query=false){
        $sql="SELECT Bodega, CodBod
                FROM Catalogo_Bodegas 
                WHERE Item = '".$this->NumEmpresa."'
                AND Periodo = '".$this->Periodo_Contable."' ";
        if($query){
            $sql .= "AND Bodega LIKE '%".$query."%' ";
        }
        $sql .= "ORDER BY CodBod";
        return $this->db->datos($sql);
    }

    function Listar_Por_Producto($ProductoPor, $DCTInv, $query=false) {
        if ($ProductoPor == 'OpcMarca') {
            $sSQL = "SELECT CodMar As codigo, Marca As nombre " .
                    "FROM Catalogo_Marcas " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND Periodo = '".$this->Periodo_Contable."' ";

            if($query){
                $sSQL .= "AND Marca LIKE '%".$query."%' ";
            }

            $sSQL .= "AND CodMar <> '.' " .
                    "ORDER BY Marca";
        } elseif ($ProductoPor == 'OpcBarra') {
            $sSQL = "SELECT Codigo_Barra As codigo, Codigo_Barra As nombre " .
                    "FROM Trans_Kardex " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND Periodo = '".$this->Periodo_Contable."' ";
            if($query){
                $sSQL .= "AND Codigo_Barra LIKE '%".$query."%' ";
            }
            $sSQL .= "GROUP BY Codigo_Barra " .
                    "ORDER BY Codigo_Barra";
        } elseif ($ProductoPor == 'OpcLote') {
            $sSQL = "SELECT Lote_No As codigo, Lote_No As nombre " .
                    "FROM Trans_Kardex " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND Periodo = '".$this->Periodo_Contable."' ";
            if($query){
                $sSQL .= "AND Lote_No LIKE '%".$query."%' ";
            }
            $sSQL .= "GROUP BY Lote_No " .
                    "ORDER BY Lote_No";
        } else {
            $sSQL = "SELECT Codigo_Inv As codigo, Producto As nombre " .
                    "FROM Catalogo_Productos " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND Periodo = '".$this->Periodo_Contable."' ";
            if($query){
                $sSQL .= "AND Producto LIKE '%".$query."%' ";
            }
            $sSQL .= "AND LEN(Cta_Inventario) > 2 " .
                    "AND Codigo_Inv LIKE '$DCTInv%' " .
                    "AND TC = 'P' " .
                    "ORDER BY Codigo_Inv";
        }
        //print_r($sSQL);die();
        return $this->db->datos($sSQL);
    }

    function Form_Activate(){
        $sSQL = "SELECT TC, Codigo_Inv, Producto,Unidad,Stock_Anterior,Entradas,Salidas,Stock_Actual,Promedio,PVP,Valor_Total " .
            "FROM Catalogo_Productos " .
            "WHERE Item = '" . $this->NumEmpresa . "' " .
            "AND Periodo = '" . $this->Periodo_Contable . "' " .
            "AND TC = 'I' " .
            "AND INV <> 0 " .
            "ORDER BY Codigo_Inv";
        return grilla_generica_new($sSQL);
    }

    function Listar_Por_Tipo_Cta($TipoCuentaDe, $query=false) {
        if ($TipoCuentaDe == "OpcInv") {
            $sSQL = "SELECT CC.Cuenta as nombre, TK.Cta_Inv as codigo " .
                    "FROM Catalogo_Cuentas AS CC, Trans_Kardex AS TK " .
                    "WHERE CC.Item = '" . $this->NumEmpresa . "' " .
                    "AND CC.Periodo = '" . $this->Periodo_Contable . "' ";

            if($query){
                $sSQL .= "AND TK.Cuenta LIKE '%".$query."%' ";
            }
            $sSQL .= "AND LEN(TK.Cta_Inv) > 1 " .
                    "AND CC.Codigo = TK.Cta_Inv " .
                    "AND CC.Item = TK.Item " .
                    "AND CC.Periodo = TK.Periodo " .
                    "GROUP BY CC.Cuenta, TK.Cta_Inv " .
                    "ORDER BY CC.Cuenta, TK.Cta_Inv";
        } else {
            $sSQL = "SELECT CC.Cuenta as nombre, TK.Contra_Cta as codigo " .
                    "FROM Catalogo_Cuentas AS CC, Trans_Kardex AS TK " .
                    "WHERE CC.Item = '" . $this->NumEmpresa . "' " .
                    "AND CC.Periodo = '" . $this->Periodo_Contable . "' ";
            if($query){
                $sSQL .= "AND TK.Cuenta LIKE '%".$query."%' ";
            }
            $sSQL .= "AND LEN(TK.Contra_Cta) > 1 " .
                    "AND CC.Codigo = TK.Contra_Cta " .
                    "AND CC.Item = TK.Item " .
                    "AND CC.Periodo = TK.Periodo " .
                    "GROUP BY CC.Cuenta, TK.Contra_Cta " .
                    "ORDER BY CC.Cuenta, TK.Contra_Cta";
        }
        return $this->db->datos($sSQL);
    }

    
    function Listar_Por_Tipo_SubModulo($SuModeloDe, $query=false) {
        if ($SuModeloDe == 'OpcGasto') {
            $sSQL = "SELECT TC, Codigo as codigo, Detalle AS nombre " .
                    "FROM Catalogo_SubCtas " .
                    "WHERE Item = '" . $this->NumEmpresa . "' " .
                    "AND Periodo = '" . $this->Periodo_Contable . "' ";
            if($query){
                $sSQL .= "AND Detalle LIKE '%".$query."%' ";
            }
            $sSQL .= "AND Detalle <> '" . G_NINGUNO . "' " .
                    "ORDER BY TC, Detalle";
        } else {
            $sSQL = "SELECT CP.TC, CP.Codigo as codigo, CP.Cta, (C.Cliente + REPLICATE(' ', 60 - LEN(C.Cliente)) + CP.Cta) AS nombre " .
                    "FROM Catalogo_CxCxP AS CP, Clientes AS C " .
                    "WHERE CP.Item = '" . $this->NumEmpresa . "' " .
                    "AND CP.Periodo = '" . $this->Periodo_Contable . "' ";
            if($query){
                $sSQL .= "AND Detalle LIKE '%".$query."%' ";
            }
            $sSQL .= "AND C.Cliente <> '" . G_NINGUNO . "' " .
                    "AND CP.TC = 'P' " .
                    "AND CP.Codigo = C.Codigo " .
                    "ORDER BY C.Cliente, CP.Cta";
        }
        return $this->db->datos($sSQL);
    }

    public function ListarProductosResumenK($query=false){
        $sSQL = "SELECT Codigo_Inv, Producto " .
            "FROM Catalogo_Productos " .
            "WHERE Item = '" . $this->NumEmpresa . "' " .
            "AND Periodo = '" . $this->Periodo_Contable . "' ";
        if($query){
            $sSQL .= "AND Producto LIKE '%".$query."%' ";
        }
        $sSQL .= "AND TC = 'I' " .
            "AND INV <> 0 " .
            "ORDER BY Codigo_Inv";
        return $this->db->datos($sSQL);
    }

    function SelectDB($sSQL)
    {
        return $this->db->datos($sSQL);
    }

} 
?>