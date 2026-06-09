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

     function contratistas($cliente=false,$codigo=false,$TP=false)
    {
        $sql = "SELECT distinct C.Cliente AS text,TC.Codigo as id 
        FROM Trans_Contratistas TC        
        INNER JOIN Entidad_Rubro_Contratista ER ON TC.No_Contrato = ER.No_Contrato AND TC.Codigo = ER.Contratista 
        INNER JOIN Clientes C on TC.Codigo = C.Codigo 
        WHERE TC.Item = '".$_SESSION['INGRESO']['item']."'
        AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND ER.TC = 'E'";
        if($cliente)
        {
            $sql.=" AND C.Cliente like '%".$cliente."%'";
        }
        if($codigo)
        {
            $sql.=" AND C.Codigo = '".$codigo."'";
        }
        if($TP)
        {
            $sql.=" AND TC.TP = '".$TP."'";
        }
        $sql.=" ORDER BY ID DESC";

            // print_r($sql);die();
        return $this->db->datos($sql);
    }

    function rubrosXcontratista($query,$contratistaCod=false,$contrato=false)
    {
        $sql="Select Orden_Trabajo,TCR.Cta,CC.Cuenta 
            From Trans_Contratistas_Rubros TCR
            INNER JOIN Trans_Contratistas TC on TCR.Orden_Trabajo = TC.No_Contrato
            INNER JOIN Entidad_Rubro_Contratista ER ON TC.No_Contrato = ER.No_Contrato AND TC.Codigo = ER.Contratista 
            INNER JOIN Catalogo_Cuentas CC ON  TCR.Cta = CC.Codigo 
            where TCR.Item = TC.Item
            AND TCR.Periodo = TC.Periodo
            AND TC.Item = '".$_SESSION['INGRESO']['item']."'
            AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND ER.TC = 'E'";
            if($query)
            {
                $sql.=" AND CC.Cuenta like '%".$query."%'";

            }
            if($contratistaCod)
            {
                $sql.=" AND TC.Codigo = '".$contratistaCod."'";
            }

            if($contrato)
            {
                $sql.=" AND TC.No_Contrato = '".$contrato."'";
            }
            $sql.=" group by  Orden_Trabajo,TCR.Cta,CC.Cuenta ";

            // print_r($sql);die();

        return $this->db->datos($sql);

    }

    function rubrosXcontratistaAll($query=false,$contratistaCod=false,$contrato=false,$Cta=false,$Nomes=false)
    {
        $sql = "SELECT ERC.Item,ERC.Periodo,ERC.Rubro AS CodRubro,CC.Cuenta as Rubro,ERC.Sub_Rubro,Contratista,
                No_Contrato,Centro_Costo as CodCentroCosto,CS.Detalle as CentroCosto,ERC.Cantidad,Unidad,PVP,ERC.Total,Cant_Ejec,
                ERC.Diferencia,Costo_Unit_Ejec,Costo_Total_Ejec,CST.Detalle as SubRubro,TCR.Etapa,CP.Proceso
                FROM Entidad_Rubro_Contratista ERC
                INNER JOIN Trans_Contratistas_Rubros TCR ON ERC.Centro_Costo = TCR.Centro_Costos AND ERC.Rubro = TCR.Cta 
                            AND ERC.No_Contrato = TCR.Orden_Trabajo AND ERC.Item = TCR.Item AND ERC.Periodo = TCR.Periodo
                INNER JOIN Catalogo_SubCtas CST ON CST.ID = ERC.Sub_Rubro AND ERC.Item = CST.Item AND  ERC.Periodo = CST.Periodo
                INNER JOIN Catalogo_SubCtas CS ON CS.Codigo = ERC.Centro_Costo AND ERC.Item = CS.Item AND  ERC.Periodo = CS.Periodo
                INNER JOIN Catalogo_Cuentas CC ON ERC.Rubro = CC.Codigo  AND ERC.Item = CC.Item AND  ERC.Periodo = CC.Periodo
                INNER JOIN Catalogo_Proceso CP ON  TCR.Etapa = CP.Cta_Debe  AND TCR.Item = CP.Item

                WHERE  ERC.Item = '".$_SESSION['INGRESO']['item']."'
                AND ERC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                AND No_Contrato = '".$contrato."'
                AND Contratista = '".$contratistaCod."'
                AND Rubro = '".$Cta."'
                AND MONTH(Fecha_Inicio_Ejec) = '".$Nomes."'";

                // print_r($sql);die();



        return $this->db->datos($sql);

    }

    function lista_orden_ejecucion_finalizada($query=false,$contratistaCod=false){
       
        $sql="SELECT No_Contrato 
              FROM Trans_Contratistas 
              WHERE Item = '".$_SESSION['INGRESO']['item']."'
              AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
              AND T = 'E' AND TP= 'N'";
            if($query)
            {
                $sql.=" AND No_Contrato like '%".$query."%'";

            }
            if($contratistaCod)
            {
                $sql.=" AND Codigo = '".$contratistaCod."'";
            }
            $sql.=" ORDER BY ID";

            // print_r($sql);die();

        return $this->db->datos($sql);

    }

}
?>