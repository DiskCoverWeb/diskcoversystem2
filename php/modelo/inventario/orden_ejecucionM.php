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
        FROM Entidad_Rubro_Contratista  ER        
        INNER JOIN  Trans_Contratistas TC ON TC.No_Contrato = ER.No_Contrato AND TC.Codigo = ER.Contratista 
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
        $sql = "SELECT No_Contrato,Rubro,CC.Cuenta 
        from Entidad_Rubro_Contratista ERC
        INNER JOIN Catalogo_Cuentas CC ON  ERC.Rubro = CC.Codigo 
        where ERC.Item = '".$_SESSION['INGRESO']['item']."'
        AND ERC.Periodo ='".$_SESSION['INGRESO']['periodo']."'
        AND ERC.TC = 'E' ";

        if($query)
        {
            $sql.=" AND CC.Cuenta like '%".$query."%'";

        }
        if($contratistaCod)
        {
            $sql.=" AND Contratista = '".$contratistaCod."'";
        }

        if($contrato)
        {
            $sql.=" AND No_Contrato = '".$contrato."'";
        }
        $sql.=" GROUP BY No_Contrato,Rubro,CC.Cuenta  ";

            // print_r($sql);die();

        return $this->db->datos($sql);

    }

    function rubrosXcontratistaAvances($query,$contratistaCod=false,$contrato=false)
    {

        $sql="SELECT Orden_Trabajo,Cta,CC.Cuenta 
        from Trans_Contratistas_Rubros TCR
        INNER JOIN Catalogo_Cuentas CC ON  TCR.Cta = CC.Codigo AND TCR.Item = CC.Item AND TCR.Periodo = CC.Periodo ";

        if($query)
        {
            $sql.=" AND CC.Cuenta like '%".$query."%'";

        }
        if($contratistaCod)
        {
            $sql.=" AND Contratista = '".$contratistaCod."'";
        }

        if($contrato)
        {
            $sql.=" AND Orden_Trabajo = '".$contrato."'";
        }
        $sql.=" GROUP BY Orden_Trabajo,Cta,CC.Cuenta";

            // print_r($sql);die();

        return $this->db->datos($sql);

    }

    function rubrosXcontratistaAll($query=false,$contratistaCod=false,$contrato=false,$Cta=false,$Nomes=false)
    {
        $sql = "SELECT ERC.Item,ERC.Periodo,ERC.Rubro AS CodRubro,CC.Cuenta as Rubro,ERC.Sub_Rubro,Contratista,
                No_Contrato,Centro_Costo as CodCentroCosto,CS.Detalle as CentroCosto,ERC.Cantidad,Unidad,PVP,ERC.Total,Cant_Ejec,
                ERC.Diferencia,Costo_Unit_Ejec,Costo_Total_Ejec,CST.Detalle as SubRubro,TCR.Etapa,CP.Proceso,Semana 
                FROM Entidad_Rubro_Contratista ERC
                INNER JOIN Trans_Contratistas_Rubros TCR ON ERC.Centro_Costo = TCR.Centro_Costos AND ERC.Rubro = TCR.Cta 
                            AND ERC.No_Contrato = TCR.Orden_Trabajo AND ERC.Item = TCR.Item AND ERC.Periodo = TCR.Periodo
                INNER JOIN Catalogo_SubCtas CST ON CST.ID = ERC.Sub_Rubro AND ERC.Item = CST.Item AND  ERC.Periodo = CST.Periodo
                INNER JOIN Catalogo_SubCtas CS ON CS.Codigo = ERC.Centro_Costo AND ERC.Item = CS.Item AND  ERC.Periodo = CS.Periodo
                INNER JOIN Catalogo_Cuentas CC ON ERC.Rubro = CC.Codigo  AND ERC.Item = CC.Item AND  ERC.Periodo = CC.Periodo
                INNER JOIN Catalogo_Proceso CP ON  TCR.Etapa = CP.Cta_Debe  AND TCR.Item = CP.Item

                WHERE  ERC.Item = '".$_SESSION['INGRESO']['item']."'
                AND ERC.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
                if($contrato){
                    $sql.=" AND No_Contrato = '".$contrato."'";
                }
                if($contratistaCod){
                    $sql.=" AND Contratista = '".$contratistaCod."'";
                }
                if($Cta){
                    $sql.=" AND Rubro = '".$Cta."'";
                }
                if($Nomes){
                    $sql.=" AND MONTH(Fecha_Inicio_Ejec) = '".$Nomes."'";
                }

                // print_r($sql);die();



        return $this->db->datos($sql);

    }


    function rubros_contrato_general($query=false,$contratistaCod=false,$contrato=false,$Cta=false,$Nomes=false)
    {
        
        $sql="SELECT Orden_Trabajo,TCR.Cta,CC.Cuenta,TCR.Cantidad  
        From Trans_Contratistas_Rubros TCR 
        INNER JOIN Catalogo_Cuentas CC ON TCR.Cta = CC.Codigo AND TCR.Item = CC.Item AND TCR.Periodo = CC.Periodo 
        INNER JOIN Trans_Contratistas TC ON TCR.Orden_Trabajo = TC.No_Contrato AND TCR.Item = TC.Item AND TCR.Periodo = TC.Periodo 
        WHERE TC.Item = '".$_SESSION['INGRESO']['item']."'
        AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."'";

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
            $sql.=" AND Orden_Trabajo = '".$contrato."'";
        }
         if($Cta)
         {
            $sql.=" AND TCR.Cta = '".$Cta."'";
         }
        $sql.=" GROUP BY Orden_Trabajo,TCR.Cta,CC.Cuenta,TCR.Cantidad";


            // print_r($sql);die();

        return $this->db->datos($sql);
    }

    function lista_orden_ejecucion_finalizada($query=false,$contratistaCod=false){
       
        $sql="SELECT TC.No_Contrato 
        FROM Trans_Contratistas TC
        INNER JOIN  Entidad_Rubro_Contratista ER ON TC.No_Contrato = ER.No_Contrato AND TC.Codigo = ER.Contratista 
        WHERE TC.Item = '".$_SESSION['INGRESO']['item']."'
        AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND TC.Item = ER.Item
        AND TC.Periodo = ER.Periodo";
            if($query)
            {
                $sql.=" AND No_Contrato like '%".$query."%'";

            }
            if($contratistaCod)
            {
                $sql.=" AND Codigo = '".$contratistaCod."'";
            }
            $sql.=" group by TC.No_Contrato ";

            // print_r($sql);die();

        return $this->db->datos($sql);

    }

    function detalleContrato_ejecucion($contrato=false,$T = '.')
    {
        $sql = "SELECT ERC.Semana,ERC.Cantidad,ERC.Cant_Ejec as ejecutado,ERC.No_Contrato,C.Cliente as 'Cliente',C.Codigo,CS.Detalle as 'Centro Costo',CC.Cuenta as 'Detalle rubro',(SUM(Cant_Ejec)*100/SUM(ERC.Cantidad)) as porcentaje ,SR.Detalle as subRubro
                FROM Entidad_Rubro_Contratista ERC
                INNER JOIN Clientes C ON ERC.Contratista = C.Codigo 
                INNER JOIN Catalogo_SubCtas SR ON ERC.Sub_Rubro  = SR.ID AND ERC.Item = SR.Item AND ERC.Periodo = SR.Periodo 
                INNER JOIN Catalogo_SubCtas CS ON ERC.Centro_Costo = CS.Codigo AND ERC.Item = CS.Item AND ERC.Periodo = CS.Periodo
                INNER JOIN Catalogo_Cuentas CC ON ERC.Rubro = CC.Codigo AND ERC.Item = CC.Item AND ERC.Periodo = CS.Periodo
                WHERE ERC.TC ='E' OR ERC.TC = 'A' 
                AND ERC.Item = '".$_SESSION['INGRESO']['item']."'
                AND ERC.Periodo = '".$_SESSION['INGRESO']['periodo']."'
                GROUP BY ERC.Semana,ERC.Cantidad,ERC.Cant_Ejec,ERC.No_Contrato,C.Cliente,C.Codigo,CS.Detalle,CC.Cuenta,Cant_Ejec,ERC.Cantidad,SR.Detalle";
                // print_r($sql);die();

        return $this->db->datos($sql);
    }

    function centrosCostocXRubro($contratos=false,$rubro=false,$semana =false)
    {
       

            $sql = "SELECT Centro_Costo,SC.Detalle,Observacion
            FROM Entidad_Rubro_Contratista ERC
            INNER JOIN Catalogo_SubCtas SC ON ERC.Centro_Costo = SC.Codigo AND ERC.Periodo = SC.Periodo and  ERC.Item = SC.Item
            where ERC.Item =  '".$_SESSION['INGRESO']['item']."'
            AND ERC.Periodo ='".$_SESSION['INGRESO']['periodo']."'
            AND ERC.TC = 'E' ";
            if($rubro)
            {
                $sql.=" AND Rubro = '".$rubro."' ";
            }
            if($contratos)
            {
                $sql.=" AND Orden_Trabajo = '".$contratos."'";
            }            
            if($semana)
            {
                $sql.=" AND ERC.Semana = '".$semana."'";
            }
            $sql.=" group by Centro_Costo,SC.Detalle,Observacion";

                // print_r($sql);die();
        return $this->db->datos($sql);
    }

    function Trans_Contratistas_meses($id=false,$contrato=false,$rubro=false)
    {
        $sql = "SELECT MONTH(Fecha_Inicio_Ejec) AS Mes
                FROM Entidad_Rubro_Contratista CR 
                WHERE CR.Item = '".$_SESSION['INGRESO']['item']."'
                AND CR.Periodo = '".$_SESSION['INGRESO']['periodo']."'";
                if($contrato)
                {
                    $sql.=" AND Orden_Trabajo = '".$contrato."' ";
                }
                if($id)
                {                    
                    $sql.=" AND ID = '".$id."' ";
                }
                if($rubro)
                {                    
                    $sql.=" AND CR.Rubro = '".$rubro."' ";
                }

                $sql.=" GROUP BY  MONTH(Fecha_Inicio_Ejec)";

                // print_r($sql);die();

        return $this->db->datos($sql);
    }



    function detalleContratoAll($contrato=false)
    {
        $sql = "select TC.ID,C.Cliente as 'Cliente',CP.Proceso,CP1.Proceso as proyecto,TC.Fecha,TC.Fecha_V,TC.No_Contrato,TC.Proyecto as ProyectoID,TC.T 
        FROM Trans_Contratistas TC
        INNER JOIN Clientes C ON TC.Codigo = C.Codigo
        INNER JOIN Catalogo_Proceso CP ON TC.Proceso = CP.Cmds
        INNER JOIN Catalogo_Proceso CP1 ON TC.Proyecto = CP1.ID
        where TC.Item = CP.Item
        AND TC.Item = CP1.Item
        AND TC.Item = '".$_SESSION['INGRESO']['item']."'
        AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."'
         AND TC.T = 'A'";
        if($contrato)
        {
            $sql.=" AND No_Contrato = '".$contrato."'";
        }
        $sql.=" ORDER BY TC.ID DESC";

        // print_r($sql);die();

        return $this->db->datos($sql);
    }




}
?>