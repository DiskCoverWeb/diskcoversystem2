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

    function detalleContrato($contrato=false,$T = '.')
    {
        $sql = "select TC.ID,C.Cliente as 'Cliente',CP.Proceso,CP1.Proceso as proyecto,TC.Fecha,TC.Fecha_V,TC.No_Contrato,TC.Proyecto as ProyectoID  
        FROM Trans_Contratistas TC
        INNER JOIN Clientes C ON TC.Codigo = C.Codigo
        INNER JOIN Catalogo_Proceso CP ON TC.Proceso = CP.Cmds
        INNER JOIN Catalogo_Proceso CP1 ON TC.Proyecto = CP1.ID
        where TC.T ='".$T."'
        AND TC.Item = CP.Item
        AND TC.Item = CP1.Item
        AND TC.Item = '".$_SESSION['INGRESO']['item']."'
        AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."'";
        if($contrato)
        {
            $sql.=" AND No_Contrato = '".$contrato."'";
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
               INNER JOIN Catalogo_Proceso C ON TCR.Etapa = C.Cta_Debe 
               INNER JOIN Catalogo_SubCtas SC ON TCR.Centro_Costos = SC.Codigo
               INNER JOIN Catalogo_Cuentas CC ON  TCR.Cta = CC.Codigo 
                WHERE C.Item = TCR.Item
                AND CC.Item = TCR.Item
                AND CC.Periodo = TCR.Periodo
                AND SC.Item = TCR.Item
                AND SC.Periodo = TCR.Periodo
                AND TCR.Item = '".$_SESSION['INGRESO']['item']."'
                AND TCR.Periodo = '".$_SESSION['INGRESO']['periodo']."'
                AND TCR.Orden_Trabajo = '".$contrato."' ";
                if($rubro)
                {
                    $sql.=" AND TCR.Cta = '".$rubro."'";
                }

                // print_r($sql);die();
        return $this->db->datos($sql);
    }


    function proyecto($query,$id=false)
    {
        $sql= "SELECT Item, Nivel, TP, Proceso as text, DC, Cheque, Mi_Cta, Cmds, Cta_Debe, Cta_Haber,ID as id, Picture, Color, Cta_Costo
               FROM Catalogo_Proceso 
               WHERE Item = '".$_SESSION['INGRESO']['item']."' 
               AND (Nivel = 0)
               AND LEN(Cmds) = 2 ";
                if($query)
                {
                    $sql.=" AND Proceso like'%".$query."%'";
                }
                if($id)
                {
                    $sql.=" AND ID = '".$id."'";
                }
        $sql.=" ORDER by Proceso";

        // print_r($sql);die();
        return $this->db->datos($sql);
    }

    function contratistas($query)
    {
        $sql="SELECT C.Cliente as text, C.CI_RUC, C.Codigo as  id, CXP.Cta
            FROM Clientes As C, Ctas_Proceso As CP, Catalogo_CxCxP As CXP
            WHERE CXP.Item ='".$_SESSION['INGRESO']['item']."' 
                and CXP.Periodo ='".$_SESSION['INGRESO']['periodo']."' 
                AND LEN(C.Cliente)>1
                AND CP.Detalle ='Cta_Proveedores'
                and CXP.Item = CP.Item
                and CXP.periodo = CP.Periodo 
                and CP.Codigo=CXP.Cta
                AND C.Codigo = CXP.Codigo";
                 if($query)
                {
                    $sql.=" AND  C.Cliente like'%".$query."%'";
                }
            $sql.="    ORDER BY C.Cliente;";
        // $sql= "SELECT Codigo as id, Nombre_Completo as text
        //        FROM Accesos WHERE 1=1 ";
        //         if($query)
        //         {
        //             $sql.=" AND Nombre_Completo like'%".$query."%'";
        //         }
        // $sql.=" ORDER by Nombre_Completo";

        // print_r($sql);die();
        return $this->db->datos($sql);
    }

    function personal($query)
    {
        $sql= "SELECT top 25 Codigo,Cliente,Actividad,Fecha_N,CI_RUC
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

    function lista_etapas($proyecto,$query=false)
    {
        $sql = "SELECT TP, Proceso,Cmds, Cta_Debe, Cta_Haber, ID
                FROM Catalogo_Proceso
                WHERE Item = '".$_SESSION['INGRESO']['item']."'
                AND Cmds LIKE '".$proyecto.".04.%'";
                if($query)
                {
                    $sql.= " AND Proceso like '%".$query."%'";
                }

// print_r($sql);die();
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

    function ddl_Proceso($cuenta=false,$cmds=false)
    {

        $sql = "SELECT Cmds as id,Proceso as text 
            FROM Catalogo_Proceso
            WHERE Item = '".$_SESSION['INGRESO']['item']."' ";
            if($cuenta)
            {
                $sql.=" AND Proceso like '".$cuenta."%'";
            }
            if($cmds)
            {
                $sql.=" AND Cmds like '".$cmds."%'";
            }
            $sql.=" ORder by Nivel DESC";

            // print_r($sql);die();

       $datos =  $this->db->datos($sql);
       return $datos;
    }


    function ddl_Rubro($query='',$proyectos=false)
    {
        // 'LISTA DE CODIGO DE ANEXOS
         $sql = "SELECT Codigo,Cuenta 
         FROM Catalogo_Cuentas 
         WHERE DG='D' 
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
         AND Item = '".$_SESSION['INGRESO']['item']."' ";
         if($query !='')
         {
            $sql .=" AND Cuenta+' '+Codigo LIKE '%".$query."%'"; 
         }
          if($proyectos)
         {
            $sql .=" AND Codigo LIKE '".$proyectos."%'"; 
         }
            // print_r($sql);die();
             $datos1 =  $this->db->datos($sql);

             $datos = array();
             foreach ($datos1 as $key => $value) {
                $datos[]=array('id'=>$value['Codigo'],'text'=>$value['Cuenta']);        
             }
             return $datos;

    }

    function listar_cc($cuenta=false)
    {

        $sql = "SELECT Codigo as id,Detalle as text 
            FROM Catalogo_SubCtas
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND (Nivel = 99) 
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


    function grabar_orden_trabajo($orden)
    {
        $sql = "UPDATE Trans_Contratistas 
                SET T = 'A' 
                WHERE No_Contrato = '".$orden."' ";
        return $this->db->String_Sql($sql);

    }

    function Trans_Contratistas($id=false,$contrato=false)
    {
        $sql = "SELECT *
                FROM Trans_Contratistas_Rubros CR 
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

        return $this->db->datos($sql);
    }




} 
?>