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

    function listaAsignacion($orden,$T=false,$tipo=false,$fecha=false)
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
                }else{
                    $sql.="AND Fecha = '".date('Y-m-d')."' ";
                }
                if($T)
                {
                    $sql.="AND T = '".$T."'";
                }
                if($tipo)
                {
                    $sql.=" AND No_Hab = '".$tipo."'";
                }                
                // print_r($sql);die();
        try{
            return $this->db->datos($sql);
        }catch(Exception $e){
            throw new Exception($e);
        }
    }

}