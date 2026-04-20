<?php
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");
/**
 */
class orden_ejecucionM
{
    private $db;
    public function __construct()
    {
        $this->db = new db();
    }

    function lista_orden_ejecucion($query=false,$contratistaCod=false){
       
        $sql="Select Orden_Trabajo,TCR.Cta,CC.Cuenta 
            From Trans_Contratistas_Rubros TCR
            INNER JOIN Trans_Contratistas TC on TCR.Orden_Trabajo = TC.No_Contrato
            INNER JOIN Catalogo_Cuentas CC ON  TCR.Cta = CC.Codigo 
            where TCR.Item = TC.Item
            AND TCR.Periodo = TC.Periodo
            AND TC.Item = '".$_SESSION['INGRESO']['item']."'
            AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND TC.T = 'E'";
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

     function contratistas($cliente=false)
    {
        $sql = "SELECT distinct C.Nombre_Completo AS text,TC.Codigo as id 
        FROM Trans_Contratistas TC
        INNER JOIN Accesos C on TC.Codigo = C.Codigo 
        WHERE Item = '".$_SESSION['INGRESO']['item']."'
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND TC.T = 'E'";
        if($cliente)
        {
            $sql.=" AND C.Cliente like '%".$cliente."%'";
        }
        $sql.=" ORDER BY ID DESC";

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
            AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND TC.T = 'E'";
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

}
?>