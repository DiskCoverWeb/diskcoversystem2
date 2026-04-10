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
            FROM Trans_Contratistas TS,Clientes C,Catalogo_Proceso CP ,Catalogo_Proceso CP1,Catalogo_Cuentas CC,Catalogo_SubCtas CS 
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

    function aprob_cargar_lista($orden)
    {
        $sql="SELECT Periodo, TC, Codigo, Detalle, Presupuesto, Caja, Item, X, Reembolso, Cta_Reembolso, Nivel, Agrupacion, Fecha_D, Fecha_H, Total, Porc, Bloquear, ID
            FROM            Catalogo_SubCtas
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND TC = 'OT'
            ORDER BY ID DESC";

            // print_r($sql);die();
        $datos =  $this->db->datos($sql);
       return $datos;
    }

    function eliminar_linea($id)
    {
        $sql = "DELETE FROM Trans_Contratistas WHERE ID = '".$id."'";
        return $this->db->String_Sql($sql);
    }

    function contratistas($cliente=false)
    {
        $sql = "SELECT distinct C.Cliente AS text,TC.Codigo as id 
        FROM Trans_Contratistas TC
        INNER JOIN Clientes C on TC.Codigo = C.Codigo 
        WHERE Item = '".$_SESSION['INGRESO']['item']."'";
        if($cliente)
        {
            $sql.=" AND C.Cliente like '%".$cliente."%'";
        }
        $sql.=" ORDER BY ID DESC";

        return $this->db->datos($sql);
    }

    function contratos($contratista,$contrato=false)
    {
        $sql = "SELECT No_Contrato as id,No_Contrato as text
        FROM Trans_Contratistas TC
        WHERE Item = '".$_SESSION['INGRESO']['item']."'
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
        AND Codigo = '".$contratista."'";
        if($contrato)
        {
            $sql.=" AND TC.No_Contrato like '%".$contrato."%'";
        }
        $sql.=" ORDER BY ID DESC";

        // print_r($sql);die();

        return $this->db->datos($sql);
    }

    function rubrosXcontratista($query,$contratistaCod=false)
    {
        $sql="Select Orden_Trabajo,TCR.Cta,CC.Cuenta 
            From Trans_Contratistas_Rubros TCR
            INNER JOIN Trans_Contratistas TC on TCR.Orden_Trabajo = TC.No_Contrato
            INNER JOIN Catalogo_Cuentas CC ON  TCR.Cta = CC.Codigo 
            where TCR.Item = TC.Item
            AND TCR.Periodo = TC.Periodo
            AND TC.Item = '".$_SESSION['INGRESO']['item']."'
            AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."'";
            if($query)
            {
                $sql.=" AND CC.Cuenta like '%".$query."%'";

            }
            if($contratistaCod)
            {
                $sql.=" AND TC.Codigo = '".$contratistaCod."'";
            }
            $sql.=" group by  Orden_Trabajo,TCR.Cta,CC.Cuenta ";

            // print_r($sql);die();

        return $this->db->datos($sql);

    }

    function centrosCostocXRubro($proyecto=false,$rubro=false)
    {
        $sql ="Select TCR.Centro_Costos,Detalle
                From Trans_Contratistas_Rubros TCR
                INNER JOIN Catalogo_SubCtas SC ON TCR.Centro_Costos = SC.Codigo
                where SC.Item = TCR.Item
                AND SC.Periodo = TCR.Periodo
                AND TCR.Item =  '".$_SESSION['INGRESO']['item']."'
                AND TCR.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
                if($rubro)
                {
                    $sql.=" AND Cta = '".$rubro."' ";
                }
                if($proyecto)
                {
                    $sql.=" AND Orden_Trabajo = '".$proyecto."'";
                }
        
        return $this->db->datos($sql);
    }

    function subrubro($rubro,$query=false)
    {
        $sql = "SELECT  Periodo, TC, Codigo, Detalle, Presupuesto, Caja, Item, X, Reembolso, Cta_Reembolso, Cta_Proyecto, Nivel, Agrupacion, Fecha_D, Fecha_H, Total, Porc, Bloquear, ID
                FROM Catalogo_SubCtas
                WHERE TC = 'SR'
                AND Item =  '".$_SESSION['INGRESO']['item']."'
                AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
                AND Cta_Proyecto = '".$rubro."' ";
                if($query)
                {
                    $sql.=" AND Detalle like '%".$query."%'";
                }

        return $this->db->datos($sql);
    }

    function cargar_lista_subrubros($contrato,$rubro,$subrubro=false,$centrocostos=false,$contratista=false)
    {
        $sql = "SELECT ERC.Item, ERC.Periodo, Rubro, Sub_Rubro,CS.Detalle, Contratista, No_Contrato, CodigoU, ERC.X, ERC.ID,Cantidad,PVP,ERC.Total
                FROM Entidad_Rubro_Contratista ERC
                INNER JOIN Catalogo_SubCtas CS on ERC.Sub_Rubro = CS.ID
                WHERE ERC.Item =  '".$_SESSION['INGRESO']['item']."'
                AND ERC.Periodo = '".$_SESSION['INGRESO']['periodo']."'";
                if($contrato)
                {
                    $sql.=" AND No_Contrato = '".$contrato."'";
                }
                // if($rubro)
                // {
                //     $sql.=" AND Rubro='".$rubro."'";
                // }
                if($subrubro)
                {
                    $sql.=" AND Sub_Rubro='".$subrubro."'";
                }
                if($centrocostos)
                {
                    $sql.=" AND Centro_Costo = '".$centrocostos."'";
                }
                if($contratista)
                {
                    $sql.=" AND Contratista = '".$contratista."'";
                }

                // print_r($sql);die();
        return $this->db->datos($sql);
                
    }

    function delete_subrubro($id)
    {
        $sql = "DELETE FROM Entidad_Rubro_Contratista WHERE ID = '".$id."'";

        return $this->db->String_Sql($sql);
    }


}