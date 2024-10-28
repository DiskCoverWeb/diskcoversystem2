<?php
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class reubicarM
{
    private $db ;
    public $NumEmpresa;
    public $Periodo_Contable;

    function __construct()
    {
        $this->db = new db();
    }

    function lista_stock_ubicado($bodega=false,$cod_barras  =false,$grupo=false)
    {
        $sql="select TK.*,Producto
            FROM Trans_Kardex TK
            INNER JOIN Catalogo_Productos CP on TK.Codigo_Inv = CP.Codigo_Inv
            where TK.Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND TK.Item = '".$_SESSION['INGRESO']['item']."'
            AND TK.Item = CP.Item
            AND TK.Orden_No <> '0'
            AND TK.Orden_No <> '.'
            AND TK.Orden_No <> '0.'
            AND TK.CodBodega <> '-1'
            AND TK.T = 'E'";
            if($bodega)
            {
                $sql.=" AND CodBodega = '".$bodega."'";
            }
            if($cod_barras)
            {
                $sql.=" AND  TK.Codigo_Barra = '".$cod_barras."'";
            }
            if($grupo)
            {
                $sql.=" AND CP.Codigo_Inv = '".$grupo."' ";

            }

            // print_r($sql);die();
        return $this->db->datos($sql);
    }

    function ruta_bodega_select($ruta)
    {
        $sql = "SELECT TC, CodBod, Bodega, Item, Periodo, X, ID
        FROM Catalogo_Bodegas
        WHERE Item = '".$_SESSION['INGRESO']['item']."' 
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
        AND CodBod in (".$ruta.")
        order by CodBod ASC ";

        return $this->db->datos($sql);
    }


} 
?>