<?php
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2).'/funciones/funciones.php');




class orden_trabajo_constM
{
    private $db;

    public function __construct(){
        $this->db = new db();
    }

    function ddl_cuenta_contable($cuenta=false)
    {

        $sql = "SELECT Codigo as id,Cuenta as text FROM Catalogo_Cuentas  
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND (TC = 'G') 
            AND (DG = 'D') ";
            if($cuenta)
            {
                $sql.=" AND Cuenta like '".$cuenta."%'";
            }

            $sql.=" ORder by Cuenta ";
          // print_r($sql);die();

       $datos =  $this->db->datos($sql);
       return $datos;
    }

    function ddl_Proceso($cuenta=false)
    {

        $sql = "SELECT Cmds as id,Proceso as text 
            FROM Catalogo_Proceso
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND (Cmds LIKE '03.%') ";
            if($cuenta)
            {
                $sql.=" AND Proceso like '".$cuenta."%'";
            }
            $sql.=" ORder by Nivel DESC";

       $datos =  $this->db->datos($sql);
       return $datos;
    }

    function ddl_Grupo($cuenta=false)
    {

        $sql = "SELECT Cmds as id,Proceso as text 
            FROM Catalogo_Proceso
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND (Cmds LIKE '01.%') ";
            if($cuenta)
            {
                $sql.=" AND Proceso like '".$cuenta."%'";
            }
            $sql.=" ORder by Nivel DESC";
          // print_r($sql);die();

       $datos =  $this->db->datos($sql);
       return $datos;
    }

    function ddl_Rubro($cuenta=false)
    {

        $sql = "SELECT Codigo as id,Detalle as text 
            FROM Catalogo_SubCtas
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND (Nivel = 03) 
            AND (TC = 'G') ";
            if($cuenta)
            {
                $sql.=" AND Detalle like '%".$cuenta."%'";
            }
            $sql.=" Order by Detalle"; 
          // print_r($sql);die();

       $datos =  $this->db->datos($sql);
       return $datos;
    }

    function cargar_lista($orden)
    {
        $sql="SELECT TS.Periodo, TS.T, TS.TC, Cta,CC.Cuenta, TS.Fecha, Fecha_V, C.Cliente as Codigo, TS.TP, Numero, Factura, Prima, TS.Debitos, TS.Creditos, Saldo_MN, Parcial_ME, Saldo_ME, TS.Item, Saldo, TS.CodigoU, TS.X, Comp_No, Autorizacion, Serie, Detalle_SubCta, TS.Procesado, TS.ID, Fecha_E,TS.Proceso as CProceso ,CP.Proceso as Proceso,TS.Grupo as GCodigo,  CP1.Proceso as Grupo, Rubro as RCodigo,CS.Detalle as Rubro, UnidadMed, Cantidad, CantidadOrd, TS.Diferencia,Categoria_Contrato,No_Contrato 
            FROM Trans_SubCtas TS,Clientes C,Catalogo_Proceso CP ,Catalogo_Proceso CP1,Catalogo_Cuentas CC,Catalogo_SubCtas CS 
            WHERE TS.Item = '".$_SESSION['INGRESO']['item']."' 
            AND TS.Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND Autorizacion = '".$orden."'
            AND CP.Cmds = TS.Proceso
            AND CP1.Cmds = TS.Grupo
            AND C.Codigo = TS.Codigo
            AND CC.Codigo = TS.Cta
            AND CS.Codigo = TS.Rubro
            AND CP.Item = TS.Item
            AND CC.Item = TS.Item
            AND CC.Periodo = TS.Periodo
            AND CS.Periodo = TS.Periodo
            AND CS.Item = TS.Item
            ORDER BY TS.ID DESC";

            // print_r($sql);die();
        $datos =  $this->db->datos($sql);
       return $datos;
    }

    function eliminar_linea($id)
    {
        $sql = "DELETE FROM Trans_SubCtas WHERE ID = '".$id."'";
        return $this->db->String_Sql($sql);
    }


}