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

  function cargar_asignacion($orden,$tipo=false,$T=false,$fecha=false,$codigo_inv=false)
  {
        $sql = "SELECT CodBodega,Nombre_Completo,Producto,TC.Total as cantidad,PVP,(TC.Total*PVP) as total,TC.Codigo_Inv,TC.Codigo_Barra,Porc  
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

                if($codigo_inv)
                {
                    //fecha_A es la fecha de asignacion
                    $sql.=" AND TC.Codigo_Inv = '".$codigo_inv."'";
                }
                // print_r($sql);die();
        return $this->db->datos($sql);    
    }

    function ProductosSisponible($orden,$T)
    {
        $sql = "UPDATE Trans_Comision SET Porc = Total  
                WHERE Orden_No = '".$orden."'
                AND T = '".$T."'
                AND Item = '".$_SESSION['INGRESO']['item']."' 
                AND Periodo='".$_SESSION['INGRESO']['periodo']."' ";

        return $this->db->String_Sql($sql);   
    }

    function ACtualizarDisponibilidad($orden,$T,$codbarras,$cantidad)
    {
        $sql = "UPDATE Trans_Comision SET Porc = '".$cantidad."'  
                WHERE Orden_No = '".$orden."'
                AND T = '".$T."'
                AND CodBodega = '".$codbarras."'
                AND Item = '".$_SESSION['INGRESO']['item']."' 
                AND Periodo='".$_SESSION['INGRESO']['periodo']."' ";

                // print_r($sql);die();

        return $this->db->String_Sql($sql);   
    }


    function IntegrantesGrupo($grupo)
    {
        // 87.02 -> codigo de viculado
      $sql = "SELECT ".Full_Fields('Clientes')."
              FROM Clientes
              WHERE Grupo = '".$grupo."'
              AND CodigoA = '87.02'
              ORDER BY Cliente ASC";

              // print_r($sql);die();
        return $this->db->datos($sql);
    }
    function getSerieUsuario($codigoU)
    {
         $sql = "SELECT * FROM Accesos WHERE Codigo = '" . $codigoU . "'";
        // print_r($sql);die();
        $stmt = $this->db->datos($sql);
        return $stmt;
    }
    function getCatalogoLineas13($fecha, $vencimiento,$TC)
    {
        $sql = "  SELECT * FROM Catalogo_Lineas 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND Fact = '".$TC."'
                AND CONVERT(DATE,Fecha) <= '" . $fecha . "'
                AND CONVERT(DATE,Vencimiento) >= '" . $vencimiento . "'
                AND len(Autorizacion)>=13
                ORDER BY Codigo";

                // print_r($sql);die();
        $stmt = $this->db->datos($sql);
        return $stmt;
    }

    function catalogo_lineas($TC, $SerieFactura, $emision, $vencimiento, $electronico = false)
    {
        $sql = "SELECT *
             FROM Catalogo_Lineas
             WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
             AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
             AND Fact = '" . $TC . "'
             AND Serie = '" . $SerieFactura . "'
             AND TL <> 0
             AND CONVERT(DATE,Fecha) <= '" . $emision . "'
             AND CONVERT(DATE,Vencimiento) >= '" . $vencimiento . "'";
        if ($electronico) {
          $sql .= " AND len(Autorizacion)=13";
        }
        $sql .= " ORDER BY Codigo ";
        // print_r($sql);die();
        return $this->db->datos($sql);
    }

    function DCEfectivo($query)
    {
         $sql = "SELECT Codigo +Space(2)+Cuenta As NomCuenta,Codigo 
       FROM Catalogo_Cuentas 
       WHERE TC IN ('BA','CJ','TJ') 
       AND DG = 'D' 
       AND Item = '" . $_SESSION['INGRESO']['item'] . "'
       AND Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "' ";
        if ($query) {
          $sql .= " AND Cuenta LIKE '%" . $query . "%'";
        }
        $sql .= " ORDER BY Codigo ";
        return $this->db->datos($sql);

    }

    function AdoLinea($parametros){
        $sql = "SELECT CxC, Codigo, Autorizacion, Fact, Serie
                FROM Catalogo_Lineas
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND Fact = '" . $parametros['TipoFactura'] . /*"'
                AND Serie = '" . $parametros['SerieFactura'] . */"'
                AND TL <> 0
                ORDER BY Codigo";
                // print_r($sql);die();
        return $this->db->datos($sql);
    }

    
  function ActualizarCodigoFactura($SQLs,$num)
  {
        $Strgs = "UPDATE Codigos 
                SET Numero = $num+1
                WHERE Concepto = '".$SQLs."'
                AND Periodo = '" .$_SESSION['INGRESO']['periodo']."' 
                AND Item = '".$_SESSION['INGRESO']['item']. "' ";
                // print_r($Strgs);
      return $this->db->String_Sql($Strgs);
  }

   function asignacion_familias($orden,$tipo=false,$T=false,$fecha=false,$codigo_inv=false)
  {
        $sql = "SELECT Periodo, T, TC, Cta, Fecha, Fecha_C, CodigoC, TP, Numero, Factura, Codigo_Inv, Total, Porc, Valor_Pagar, Item, CodigoU, X, CodBodega, ID, Orden_No, Fecha_A, Codigo_Barra, Cmds
                FROM Trans_Comision
                WHERE Orden_No = '".$orden."'
                AND T = '".$T."'
                AND Item = '".$_SESSION['INGRESO']['item']."' 
                AND Periodo='".$_SESSION['INGRESO']['periodo']."' ";
                if($tipo)
                {
                  $sql.=" AND Cta = '".$tipo."'"; 
                }
                if($fecha)
                {
                    //fecha_A es la fecha de asignacion
                    $sql.=" AND Fecha_A = '".$fecha."'";
                }

                if($codigo_inv)
                {
                    //fecha_A es la fecha de asignacion
                    $sql.=" AND Codigo_Inv = '".$codigo_inv."'";
                }
                // print_r($sql);die();
        return $this->db->datos($sql);    
    }

    function DeleteAsientoF($pedido)
    {
      $sql = "DELETE FROM Asiento_F
              WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
              AND Orden_No = '".$pedido."'";
              // print_r($sql);die();
      return $this->db->String_Sql($sql);
    }
}

?>