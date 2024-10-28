<?php

require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class listar_AnularM
{
  private $db;
  public function __construct(){
    //base de datos
    $this->db = new db();
  }

  function  DCTipo()
  {
    $sql = "SELECT TC 
            FROM Facturas 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND TC <> '.'    
            GROUP BY TC 
            ORDER BY TC; ";

    return $this->db->datos($sql);
  }

  function DCSerie($TC)
  {
    $sql = "SELECT Serie 
          FROM Facturas 
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' 
          AND TC = '".$TC."' 
          GROUP BY Serie 
          ORDER BY Serie ";          
          return $this->db->datos($sql);
  }
  function Listar_Factura_NotaVentas($tc,$serie,$factura=false,$Autorizacion=false)
  {
    $sql = "SELECT Factura, Autorizacion
          FROM Facturas
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND TC = '".$tc."'
          AND Serie = '".$serie."' ";
          if($factura)
          {
            $sql.=" AND Factura ='".$factura."' " ;
          }
          if($Autorizacion)
          {
            $sql.=" AND Autorizacion ='".$Autorizacion."' " ;
          }
          if($_SESSION['INGRESO']['modulo_'] == "EJECUTIVOS")
            {
              $sql.=" AND Cod_Ejec = '".$_SESSION['INGRESO']['CodigoU']."' ";
            }
    $sql.=" ORDER BY Factura ";          
    return $this->db->datos($sql);
  }

  function Listar_Factura_NotaVentas_all($tc,$serie,$factura=false,$Autorizacion=false)
  {
    $sql = "SELECT *
          FROM Facturas
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND TC = '".$tc."'
          AND Serie = '".$serie."' ";
          if($factura)
          {
            $sql.=" AND Factura ='".$factura."' " ;
          }
          if($Autorizacion)
          {
            $sql.=" AND Autorizacion ='".$Autorizacion."' " ;
          }
          if($_SESSION['INGRESO']['modulo_'] == "EJECUTIVOS")
            {
              $sql.=" AND Cod_Ejec = '".$_SESSION['INGRESO']['CodigoU']."' ";
            }
    $sql.=" ORDER BY Factura ";          

    // print_r($sql);die();
    return $this->db->datos($sql);
  }

  function detalle_factura($FA,$tabla=false)
  {
      $sql = "SELECT DF.Codigo,DF.Producto,DF.Cantidad,DF.Precio,DF.Total,DF.Total_Desc,DF.Total_Desc2,DF.Total_IVA,
            ROUND(((DF.Total-(DF.Total_Desc+DF.Total_Desc2))+DF.Total_IVA),2,0) As Valor_Total,DF.Mes,DF.Ticket,
            DF.Serie,DF.Factura,DF.Autorizacion,CP.Detalle,CP.Cta_Ventas,CP.Reg_Sanitario,CP.Marca,Lote_No, DF.Modelo, 
            DF.Procedencia, DF.Serie_No, DF.CodigoC, Cantidad_NC, SubTotal_NC,DF.CodMarca,DF.CodBodega,
            DF.Tonelaje,Total_Desc_NC,Total_IVA_NC,DF.Periodo,DF.Codigo_Barra,DF.ID 
            FROM Detalle_Factura As DF,Catalogo_Productos As CP 
            WHERE DF.Item = '".$_SESSION['INGRESO']['item']."' 
            AND DF.Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND DF.TC = '".$FA['TC']."' 
            AND DF.Serie = '".$FA['Serie']."' 
            AND DF.Autorizacion = '".$FA['Autorizacion']."' 
            AND DF.Factura = ".$FA['Factura']." 
            AND DF.Periodo = CP.Periodo 
            AND DF.Item = CP.Item 
            AND DF.Codigo = CP.Codigo_Inv 
            ORDER BY CP.Cta_Ventas,DF.Codigo,DF.ID ";

      if($tabla)
      {
        $medida = 100;
        $tbl = grilla_generica_new($sql,'Detalle_Factura As DF,Catalogo_Productos As CP','tbl_lineas',false,$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,$medida);
       return $tbl;
     }else
     {   // print_r($sql);die();
         return $this->db->datos($sql);
     }
  }

  function abonos_factura($FA,$tabla=false)
  {
    $sql = "SELECT C,T,Fecha,Banco,Cheque,Abono,Serie,Factura,Autorizacion,Protestado,CodigoC,Cta_CxP,Cta,Tipo_Cta,Fecha_Aut_NC,Serie_NC,
          Secuencial_NC,Autorizacion_NC,Clave_Acceso_NC,TP,Recibo_No,Comprobante,Estado_SRI_NC,Hora_Aut_NC,Periodo,Item,CodigoU,Cod_Ejec,ID
          FROM Trans_Abonos 
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
          AND TP IN ('".$FA['TC']."','TJ') 
          AND Serie = '".$FA['Serie']."' 
          AND Factura = ".$FA['Factura']." 
          ORDER BY TP,Fecha,Cta,Cta_CxP,Abono,Banco,Cheque ";
      if($tabla)
      {
        $medida = 100;
        $tbl = grilla_generica_new($sql,'Trans_Abonos ','tbl_abonos',false,$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,$medida);
       return $tbl;
     }else
     {   // print_r($sql);die();
         return $this->db->datos($sql);
     }
  }
  function guias($FA,$tabla=false)
  {
    $sql= "SELECT Serie_GR, Remision, Clave_Acceso_GR, Autorizacion_GR, Fecha, CodigoC, Comercial, CIRUC_Comercial, 
          Entrega, CIRUC_Entrega, CiudadGRI, CiudadGRF, Placa_Vehiculo, FechaGRE, FechaGRI, FechaGRF, Pedido, Zona, 
          Hora_Aut_GR, Estado_SRI_GR, Error_FA_SRI, Fecha_Aut_GR, TC, Serie, Factura, Autorizacion, Lugar_Entrega, 
          Periodo, Item 
          FROM Facturas_Auxiliares 
          WHERE Item =  '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
          AND Remision > 0 
          AND TC = '".$FA['TC']."' 
          AND Serie = '".$FA['Serie']."' 
          AND Factura = ".$FA['Factura']." 
          AND Autorizacion = '".$FA['Autorizacion']."' ";
      if($tabla)
      {
        $medida = 100;
        $tbl = grilla_generica_new($sql,'Trans_Abonos ','tbl_abonos',false,$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,$medida);
       return $tbl;
     }else
     {   // print_r($sql);die();
         return $this->db->datos($sql);
     }

  }

  function eliminar_asiento()
  {
    $sql = "DELETE 
            FROM Asiento 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND T_No IN(253,254) 
            AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'  ";
     return $this->db->String_Sql($sql);
  }
  function contabilizacion($FA)
  {
     $sql = "SELECT Cta_Venta,SUM(Total) As TTotal,SUM(Total_Desc) As TTotal_Desc,SUM(Total_Desc2) As TTotal_Desc2,SUM(Total_IVA) As TTotal_IVA 
              FROM Detalle_Factura 
              WHERE Item = '".$_SESSION['INGRESO']['item']."' 
              AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
              AND TC = '".$FA['TC']."' 
              AND Serie = '".$FA['Serie']."' 
              AND Factura = ".$FA['Factura']." 
              AND Autorizacion = '".$FA['Autorizacion']."' 
              GROUP BY Cta_Venta 
              ORDER BY Cta_Venta ";

      return $this->db->datos($sql);

  }
  function AdoAuxDB($FA)
  {
    $sql = "SELECT Cta, Cta_CxP, SUM(Abono) As TAbono 
            FROM Trans_Abonos 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND TP = '".$FA['TC']."' 
            AND Serie = '".$FA['Serie']."' 
            AND Factura = ".$FA['Factura']." 
            AND Autorizacion = '".$FA['Autorizacion']."' 
            GROUP BY Cta, Cta_CxP ";
      return $this->db->datos($sql);

  }
  function DGDetalle($tabla=false)
  {
    $sql = "SELECT * 
          FROM Asiento 
          WHERE Item ='".$_SESSION['INGRESO']['item']."' 
          AND T_No IN(253,254) 
          AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
          ORDER BY T_No, A_No, DEBE DESC,HABER ";
      if($tabla)
      {
        $medida = 100;
        $tbl = grilla_generica_new($sql,'Asiento ','tbl_asientos',false,$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,$medida);
       return $tbl;
     }else
     {   // print_r($sql);die();
         return $this->db->datos($sql);
     }
  }
  function anular_factura($FA)
  {
     $sql = "SELECT T
            FROM Facturas
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND TC = '".$FA['TC']."'
            AND Serie = '".$FA['Serie']."'
            AND Autorizacion = '".$FA['Autorizacion']."'
            AND Factura = ".$FA['Factura']." ";
      return $this->db->datos($sql);
  }

  function delete_factura($FA)
  {
    $sql="DELETE 
        FROM Facturas
        WHERE Item = '".$_SESSION['INGRESO']['item']."' 
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
        AND TC = '".$FA['TC']."'
        AND Serie = '".$FA['Serie']."'
        AND Factura = '".$FA['Factura']."'
        AND Autorizacion = '".$FA['Autorizacion']."' ";
    return $this->db->datos($sql);
  }

  function delete_detalle_factura($FA)
  {
      $sql= "DELETE           
           FROM Detalle_Factura
           WHERE Item = '".$_SESSION['INGRESO']['item']."' 
           AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
           AND TC = '".$FA['TC']."'
           AND Serie = '".$FA['Serie']."'
           AND Factura = '".$FA['Factura']."'
           AND Autorizacion = '".$FA['Autorizacion']."' ";

    return $this->db->datos($sql);
  }
  function delete_trans_abonos($FA)
  {
      $sql = "DELETE 
             FROM Trans_Abonos
             WHERE Factura = ".$FA['Factura']."
             AND TP = '".$FA['TC']."'
             AND Serie = '".$FA['Serie']."'
             AND Autorizacion = '".$FA['Autorizacion']."'
             AND Item = '".$_SESSION['INGRESO']['item']."'
             AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
      return $this->db->String_Sql($sql);
  }
  function delete_Trans_kardex($FA)
  {
     $sql = "DELETE 
            FROM Trans_Kardex 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND TC = '".$FA['TC']."' 
            AND Serie = '".$FA['Serie']."' 
            AND Factura = ".$FA['Factura']." ";
      return $this->db->String_Sql($sql);
  }
  function actualizar_factura($FA,$fecha,$ConceptoComp,$Total_Saldos_ME)
  {
     $sql = "UPDATE Facturas 
             SET T = 'A', Nota = '".$ConceptoComp."',
             Fecha_C = '".BuscarFecha($fecha) ."',
             Saldo_MN = ".$Total_Saldos_ME." 
             WHERE Factura = ".$FA['Factura']." 
             AND TC = '".$FA['TC']."' 
             AND Serie = '".$FA['Serie']."' 
             AND Autorizacion = '".$FA['Autorizacion']."' 
             AND Item = '".$_SESSION['INGRESO']['item']."' 
             AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
      return $this->db->String_Sql($sql);
  }
  function actualizar_detalle_factura($FA)
  {
    $sql = "UPDATE Detalle_Factura 
            SET T = 'A' 
            WHERE Factura = " .$FA['Factura']." 
            AND TC = '" .$FA['TC']."' 
            AND Serie = '" .$FA['Serie']."' 
            AND Autorizacion = '" .$FA['Autorizacion']."' 
            AND Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
      return $this->db->String_Sql($sql);
  }

  function delete_trans_kardex2($FA)
  {
     $sql ="DELETE 
            FROM Trans_Kardex 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND TC = '" .$FA['TC']."' 
            AND Serie = '" .$FA['Serie']."' 
            AND Factura =  " .$FA['Factura']." 
            AND SUBSTRING(Detalle, 1, 3) = 'FA:' " ;
      return $this->db->String_Sql($sql);
  }

  function Catalogo_Recetas($Codigo)
  {
    $sql = "SELECT Codigo_Receta, Cantidad, Costo, ID 
            FROM Catalogo_Recetas 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND Codigo_PP = '".$Codigo."' 
            AND TC = 'P'
            ORDER BY Codigo_Receta ";
    return $this->db->datos($sql);
  }

}

?>