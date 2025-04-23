<?php

require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");
require_once(dirname(__DIR__, 3) . "/lib/fpdf/reporte_de.php");
require_once(dirname(__DIR__, 3) . "/lib/phpmailer/enviar_emails.php");

class facturas_distribucion_famM
{
  private $db;
  private $email;
  private $pdf;

  public function __construct()
  {
    $this->db = new db();
    $this->email = new enviar_emails();
    $this->pdf = new cabecera_pdf();
  }

  function ddl_pedidos_familia($orden=false,$T=false,$tipo=false,$fecha=false)
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
              $sql.= ' AND DF.Item = CP.Item
              Group by Orden_No,No_Hab,Fecha,CodigoL,CP.Proceso,CodigoB,CP1.Proceso';
                // print_r($sql);die();
      return $this->db->datos($sql);
  }

  function cargar_asignacion($orden,$tipo=false,$T=false,$fecha=false)
  {
        $sql = "SELECT CodBodega,Nombre_Completo,Producto,TC.Total as cantidad,PVP,(TC.Total*PVP) as total 
                FROM Trans_Comision TC 
                INNER JOIN Accesos A ON TC.CodigoU = A.Codigo 
                INNER JOIN Catalogo_Productos CP ON TC.Codigo_Inv = CP.Codigo_Inv
                WHERE Orden_No = '".$orden."'
                AND TC.T = '".$T."'
                AND TC.Item = '".$_SESSION['INGRESO']['item']."' 
                AND TC.Periodo='".$_SESSION['INGRESO']['periodo']."' ";
                if($tipo)
                {
                  $sql.=" AND Cta = '".$tipo."'"; 
                }
                if($fecha)
                {
                    //fecha_A es la fecha de asignacion
                    $sql.=" AND Fecha_A = '".$fecha."'";
                }
                // print_r($sql);die();
        return $this->db->datos($sql);    
    }

    function IntegrantesGrupo($grupo)
    {
      $sql = "SELECT ".Full_Fields('Clientes')."
              FROM Clientes
              WHERE Grupo = '".$grupo."'";
        return $this->db->datos($sql);
    }


}

?>