<?php
 // DESARROLLADOR      : Walter Vaca Prieto
 // FECHA CREACION    : 08/09/2022
 // FECHA MODIFICACION: 08/09/2022 - 15/10/2024
 // DESCIPCION        : Elimina los indices de las tablas


require_once (dirname(__DIR__, 2) . "/db/db1.php");
require_once (dirname(__DIR__, 2) . "/funciones/funciones.php");

class asignacion_familiasM
{

    private $db;

    public function __construct()
    {

        $this->db = new db();
    }

    function listaAsignacion($orden,$T=false,$tipo=false,$tipoVenta=false,$fecha=false)
    {
         $sql = "SELECT ".Full_Fields("Detalle_Factura")."
                FROM Detalle_Factura
                WHERE Item = '".$_SESSION['INGRESO']['item']."' 
                AND Periodo='".$_SESSION['INGRESO']['periodo']."' 
                AND Orden_No = '".$orden."' 
                AND TC = 'OF' ";
                if($fecha)
                {
                    $sql.="AND Fecha = '".$fecha."' ";
                }
                if($T)
                {
                    $sql.="AND T = '".$T."'";
                }
                 if($tipo)
                {
                    $sql.=" AND Codigo = '".$tipo."'";
                }
                if($tipoVenta)
                {
                    $sql.=" AND No_Hab = '".$tipoVenta."'";
                }                
                // print_r($sql);die();
        try{
            return $this->db->datos($sql);
        }catch(Exception $e){
            throw new Exception($e);
        }
    }

    function listaAsignacionUnicos($orden=false,$T=false,$tipo=false,$fecha=false,$grupo = false)
    {
         $sql = "SELECT Orden_No,No_Hab,Fecha,CodigoL,CP.Proceso as 'Programa',CodigoB,CP1.Proceso as 'Grupo'
                FROM Detalle_Factura DF
                INNER JOIN Catalogo_Proceso CP ON DF.CodigoL = CP.Cmds
                INNER JOIN Catalogo_Proceso CP1 ON DF.CodigoB = CP1.Cmds
                WHERE DF.Item = '".$_SESSION['INGRESO']['item']."' 
                AND Periodo='".$_SESSION['INGRESO']['periodo']."' 
                AND TC = 'OF' ";
                if($orden)
                {
                    $sql.=" AND Orden_No = '".$orden."'";
                }
                if($fecha)
                {
                    $sql.="AND Fecha = '".$fecha."' ";
                }
                if($T)
                {
                    $sql.="AND T = '".$T."'";
                }
                if($tipo)
                {
                    $sql.=" AND No_Hab = '".$tipo."'";
                } 
                if($grupo)
                {
                    $sql.=" AND CodigoB = '".$grupo."'";
                }                
                $sql.= ' AND DF.Item = CP.Item
                Group by Orden_No,No_Hab,Fecha,CodigoL,CP.Proceso,CodigoB,CP1.Proceso';
                // print_r($sql);die();
        try{
            return $this->db->datos($sql);
        }catch(Exception $e){
            throw new Exception($e);
        }
    }

    function total_ingresados($orden,$tipo,$tipoventa)
    {
        $sql = "SELECT SUM(TC.Total) as Total
                FROM Trans_Comision TC
                INNER JOIN Accesos A ON TC.CodigoU = A.Codigo
                WHERE Orden_No = '".$orden."'
                AND Codigo_Inv = '".$tipo."'
                AND Cta = '".$tipoventa."'
                AND T = 'P'";

                // print_r($sql);die();
        return $this->db->datos($sql);   
    }

    function cargar_asignacion($bene,$tipo,$T,$fecha=false,$codbarras=false)
    {
        $sql = "SELECT TC.ID,TC.Fecha,TC.Fecha_C,A.Nombre_Completo,TC.Total,TC.CodBodega,T
                FROM Trans_Comision TC
                INNER JOIN Accesos A ON TC.CodigoU = A.Codigo
                WHERE Orden_No = '".$bene."'
                AND Cta = '".$tipo."'
                AND T = '".$T."'";
                if($fecha)
                {
                    //fecha_A es la fecha de asignacion
                    $sql.=" AND Fecha_A = '".$fecha."'";
                }
                if($codbarras)
                {
                    $sql.=" AND TC.Codigo_Barra = '".$codbarras."'";
                }
                // print_r($sql);die();
        return $this->db->datos($sql);    
    }



    function lineasKArdex($codBarras=false,$id=false)
    {
        $sql ="SELECT TK.Codigo_Barra,TK.Fecha,CP.Producto
            FROM trans_kardex TK
            INNER JOIN Catalogo_Productos CP on TK.Codigo_Inv = CP.Codigo_Inv 
            WHERE 1=1 ";
            if($codBarras)
            {
                $sql.=" AND TK.Codigo_Barra = '".$codBarras."'";
            }
            if($id)
            {
                $sql.=" AND TK.ID = '".$id."'";
            }
               // print_r($sql);die();
            

        return $this->db->datos($sql);   
    }


    function tipo_asignacion()
    {
        $sql = "SELECT ".Full_Fields('Catalogo_Proceso')." 
                FROM Catalogo_Proceso 
                WHERE TP = 'TIPOASIG' 
                AND Item='".$_SESSION['INGRESO']['item']."' ";
        return $this->db->datos($sql);    
    }



}