<?php
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class contrato_trabajo_detalle_constM
{
    private $db ;
    public $NumEmpresa;
    public $Periodo_Contable;

    function __construct()
    {
        $this->db = new db();
    }

    function detalleContrato($contrato=false)
    {
        $sql = "SELECT TC.ID,TC.Fecha,Fecha_V,TC.Codigo,C.Cliente,TC.Proceso as categoriaId, CP.Proceso as categoria,TC.Categoria_Contrato  as cuenta_contableId,CC.Cuenta as cuenta_contable,TC.Cta as tipo_costoId,CC1.Cuenta as tipo_costo,Cargo_Mat,
        Mas_Persona,Nombre_Contrato,Proyecto as proyectoId,CC2.Cuenta as proyecto,Autorizacion 
        FROM Trans_Contratistas TC
        INNER JOIN Clientes C ON TC.Codigo = C.Codigo
        INNER JOIN Catalogo_Proceso CP ON TC.Proceso = CP.Cmds
        INNER JOIN Catalogo_Cuentas CC ON TC.Categoria_Contrato = CC.Codigo 
        INNER JOIN Catalogo_Cuentas CC1 ON TC.Cta = CC1.Codigo         
        INNER JOIN Catalogo_Cuentas CC2 ON TC.Proyecto = CC2.Codigo 
        where TC.Item = CP.Item
        AND TC.Item = CC.Item
        AND TC.Item = CC1.Item
        AND TC.Item = CC2.Item
        AND TC.Periodo = CC.Periodo
        AND TC.Periodo = CC1.Periodo
        AND TC.Periodo = CC2.Periodo
        AND TC.Item = '".$_SESSION['INGRESO']['item']."'
        AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."'";
        if($contrato)
        {
            $sql.=" AND Autorizacion = '".$contrato."'";
        }

        // print_r($sql);die();

        return $this->db->datos($sql);
    }

    function eliminar_contrato($id)
    {
        $sql = "DELETE FROM Trans_Contratistas WHERE ID = '".$id."'";
        return $this->db->String_Sql($sql);
    }


    function lista_rubros_unicos($contrato)
    {
        $sql = "SELECT DISTINCT CR.Cta, SC.Detalle
                FROM Trans_Contratistas_Rubros CR INNER JOIN
                Catalogo_SubCtas AS SC ON CR.Cta = SC.Codigo
                WHERE  CR.Item = SC.Item
                AND CR.Periodo = SC.Periodo
                AND CR.Item = '".$_SESSION['INGRESO']['item']."'
                AND CR.Periodo = '".$_SESSION['INGRESO']['periodo']."'
                AND Orden_Trabajo = '".$contrato."' ";

        return $this->db->datos($sql);
    }

    function lista_solicitud_rubro($contrato=false,$rubro = false)
    {
        $sql = "SELECT  TCR.ID,Cta as IdRubro,Detalle,Etapa as IdEtapa ,C.Proceso as Etapa,Centro_Costos,CC.Cuenta,Cantidad,Costo_Unit,TCR.Total,TCR.Codigo
                FROM Trans_Contratistas_Rubros TCR 
                INNER JOIN Catalogo_Proceso C ON TCR.Etapa = C.Cmds
                INNER JOIN Catalogo_SubCtas SC ON TCR.Cta = SC.Codigo
                INNER JOIN Catalogo_Cuentas CC ON TCR.Centro_Costos = CC.Codigo
                WHERE C.Item = TCR.Item
                AND CC.Item = TCR.Item
                AND CC.Periodo = TCR.Periodo
                AND SC.Item = TCR.Item
                AND SC.Periodo = TCR.Periodo
                AND TCR.Item = '".$_SESSION['INGRESO']['item']."'
                AND TCR.Periodo = '".$_SESSION['INGRESO']['periodo']."'
                AND TCR.Orden_Trabajo = '".$contrato."'
                AND TCR.Cta = '".$rubro."'";

                // print_r($sql);die();
        return $this->db->datos($sql);
    }

    function contratistas($query)
    {
        $sql= "SELECT Codigo as id, Nombre_Completo as text
               FROM Accesos WHERE 1=1 ";
                if($query)
                {
                    $sql.=" AND Nombre_Completo like'%".$query."%'";
                }
        $sql.=" ORDER by Nombre_Completo";

        // print_r($sql);die();
        return $this->db->datos($sql);
    }

    function personal($query)
    {
        $sql= "SELECT top 25 Codigo as id, Cliente as text
               FROM Clientes 
                WHERE 1=1 ";
                if($query)
                {
                    $sql.=" AND Cliente like'%".$query."%'";
                }
        $sql.=" ORDER by Cliente";

        // print_r($sql);die();
        return $this->db->datos($sql);
    }

    function lista_etapas($query=false)
    {
        $sql = "SELECT TP, Proceso,Cmds, Cta_Debe, Cta_Haber, ID
                FROM Catalogo_Proceso
                WHERE Item = '".$_SESSION['INGRESO']['item']."'
                AND Cmds LIKE '04.%'";
                if($query)
                {
                    $sql.= " AND Proceso like '%".$query."%'";
                }

        return $this->db->datos($sql);

    }

    function lista_personal_contrato($orden)
    {

        $sql = "SELECT E.ID,E.Codigo,Cliente,CI_RUC,Actividad,Fecha_N,Fecha,Fecha_Cad
                FROM Entidad_CxP_Contrato E
                INNER JOIN Clientes C ON E.Codigo = C.Codigo
                WHERE Item = '".$_SESSION['INGRESO']['item']."'
                AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
                if($orden)
                {
                    $sql.= " AND No_Contrato = '".$orden."'";
                }

                // print_r($sql);die();

        return $this->db->datos($sql);

    }

    function delete_personal($id)
    {
        $sql = "DELETE FROM Entidad_CxP_Contrato WHERE ID = '".$id."'";
        return $this->db->String_Sql($sql);
    }

    function eliminar_rubros($id)
    {
        $sql = "DELETE FROM Trans_Contratistas_Rubros WHERE ID = '".$id."'";
        return $this->db->String_Sql($sql);
    }



} 
?>