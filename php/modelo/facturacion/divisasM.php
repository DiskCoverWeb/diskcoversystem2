<?php

require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class divisasM
{
	private $db;

	public function __construct(){
    //base de datos
    $this->db = new db();
  }

  public function getCatalogoLineas($FechaProceso){
    $sql="SELECT * 
          FROM Catalogo_Lineas 
          WHERE TL <> 0 
          AND Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND Fecha <= '".$FechaProceso."' 
          AND Vencimiento >= '".$FechaProceso."' 
          ORDER BY Fact,Codigo";
          //print_r($sql);die();
    $stmt = $this->db->datos($sql);
    return $stmt;
  }

    public function getCatalogoLineas_lc($FechaProceso){
    $sql="SELECT * 
          FROM Catalogo_Lineas 
          WHERE TL <> 0 
          AND Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND Fecha <= '".$FechaProceso."' 
          AND Vencimiento >= '".$FechaProceso."' 
          AND Fact = 'LC'
          ORDER BY Fact,Codigo";
          // print_r($sql);die();
    $stmt = $this->db->datos($sql);
    return $stmt;
  }

  public function getProductos($tipoConsulta){
    if ($tipoConsulta=='FA') {
      $Tipo ='03.01';
    } 
    else {
      $Tipo='03.02';
    }
    $sql="SELECT Producto, Codigo_Inv, Codigo_Barra, PVP, Div
          FROM Catalogo_Productos 
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND TC = 'P' 
          AND SUBSTRING(Codigo_Inv,1,5) = '".$Tipo."'
          ORDER BY Producto,Codigo_Inv ";
          // print_r($sql);die();
    $stmt = $this->db->datos($sql);
    return $stmt;
  }

  public function getProductos_normales($Grupo_Inv,$TipoFactura,$query)
  {
     $sql = "SELECT Producto,Codigo_Inv,Codigo_Barra 
        FROM Catalogo_Productos 
        WHERE Item = '".$_SESSION['INGRESO']['item']."'
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
        AND TC = 'P' ";
    if(strlen($Grupo_Inv) > 1){ $sql.="AND MidStrg(Codigo_Inv,1,2) = '".$Grupo_Inv."' ";}
    // if($TipoFactura == "CP"){
    //    $sql.=" AND Cta_Inventario = '0' ";
    // }else{
    //    $sql.=" AND LEN(Cta_Inventario) > 1 ";
    // }
    if($query)
    {
      $sql.=" AND Producto like '%".$query."%'";
    }
    $sql.=" ORDER BY Producto,Codigo_Inv "; 

    // print_r($sql);die();
    return $this->db->datos($sql);
  }




   function getProductos_datos($codigo){

    $sql="SELECT Producto, Codigo_Inv, Codigo_Barra, PVP, Div
          FROM Catalogo_Productos 
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND TC = 'P' 
          AND  Codigo_Inv = '".$codigo."'
          ORDER BY Producto,Codigo_Inv ";
          // print_r($sql);die();
    $stmt = $this->db->datos($sql);
    return $stmt;
  }


  public function deleteAsiento($codigoCliente){
    $sql = "DELETE
          FROM Asiento_F
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND Codigo_Cliente = '".$codigoCliente."' 
          AND CodigoU = '". $_SESSION['INGRESO']['CodigoU'] ."' ";
    $stmt = $this->db->String_Sql($sql);
    return $stmt;
  }

  public function updateClientesFacturacion($Valor,$Anio1,$Codigo1,$Codigo,$Codigo3,$Codigo2){
    $sql = "UPDATE Clientes_Facturacion 
            SET Valor = Valor - ".$Valor." 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$Anio1."' 
            AND Codigo_Inv = '".$Codigo1."' 
            AND Codigo = '".$Codigo."' 
            AND Credito_No = '".$Codigo3."' 
            AND Mes = '".$Codigo2."' ";
    $stmt = $this->db->String_Sql($sql);
    return $stmt;
  }

  public function getAsiento(){
    $sql = "SELECT * 
            FROM Asiento_F
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
            ORDER BY A_No ";
    $stmt = $this->db->datos($sql);
    return $stmt;
  }



  public function getClientes($query){
    $sql="SELECT C.Email,C.T,C.Codigo,C.Cliente,C.Direccion,C.Grupo,C.Telefono,C.CI_RUC,C.TD 
          FROM Clientes As C
          WHERE T='N' ";
          if(!is_numeric($query))
          {
            $sql.=" AND Cliente LIKE '%".$query."%' ";
          }else
          {
            $sql.=" AND CI_RUC LIKE '".$query."%'";
          } 
          $sql.="GROUP BY C.Email, C.T,C.Codigo,C.Cliente,C.Direccion,C.Grupo,C.Telefono,C.CI_RUC,C.TD 
          ORDER BY C.Cliente 
          OFFSET 0 ROWS FETCH NEXT 10 ROWS ONLY";
          // print_r($sql);die();
    $stmt = $this->db->datos($sql);
    return $stmt;
  }

  function datos_factura($parametros)
  {
    // print_r($parametros);die();
    //datos cliente
    $sql="  SELECT * 
            FROM  Clientes  
            WHERE  (CI_RUC = '".$parametros['ci']."')";
    $datos_cli = $this->db->datos($sql);
    if(count($datos_cli)==0)
    {
      $sql="  SELECT * 
            FROM  Clientes  
            WHERE  (Codigo = '".$parametros['ci']."')";
      $datos_cli = $this->db->datos($sql);
    }
    //detalle factura
    $sql="SELECT  *
          FROM     Detalle_Factura
          WHERE    (Item = '".$_SESSION['INGRESO']['item']."') AND (Serie = '".$parametros['serie']."') 
          AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') AND (Factura = '".$parametros['factura']."') AND TC = '".$parametros['TC']."'";
    $stmt = $this->db->datos($sql);
    $lineas=array();
    $preciot=0;
    $iva=0;
    $tota=0;
    foreach ($stmt as $row) {
      $lineas[]=$row;
      $preciot+=($row['Precio']*$row['Cantidad']);
      $iva+=$row['Total_IVA'];
      $tota+=$row['Total']+$row['Total_IVA']; 
    }
    $factura = array('cliente'=>$datos_cli[0],'lineas'=>$lineas,'preciot'=>$preciot,'iva'=>$iva,'tota'=>$tota);
    return $factura;
  }

  function datos_empresa(){
    // require_once("../../db/db.php");
    // $cid = Conectar::conexion('MYSQL');
    $sql = "SELECT * FROM lista_tipo_contribuyente WHERE RUC ='".$_SESSION['INGRESO']['RUC']."'";
      // print_r($sql);die();
    $datos = $this->db->datos($sql,'MySQL');
    // print_r($datos);die();
    return $datos;
  }

  public function limpiarGrid($cod=false){
    $sql = "DELETE
          FROM Asiento_F
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND CodigoU = '". $_SESSION['INGRESO']['CodigoU'] ."' ";
          if($cod)
          {
            $sql.=" AND CODIGO = '".$cod."'";
          }
    $stmt = $this->db->String_Sql($sql);
    return $stmt;
  }

    public function cargarLineas(){
    $sql = "SELECT CODIGO,CANT as 'CANTIDAD',PRODUCTO,PRECIO2 AS 'PRECIO',TOTAL, Total_IVA
            FROM Asiento_F
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
            ORDER BY A_No ";
            $botones[0] = array('boton'=>'Eliminar', 'icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'CODIGO' );
           $datos = $this->db->datos($sql);
           $stmt =  grilla_generica_new($sql,'Asiento_F','tbl_lineas',false,$botones,false,false,1,1,0,$tamaño_tabla=250,4);
     return array('tbl'=>$stmt,'datos'=>$datos);
  }

  function lista_facturas($factura=false,$query=false)
  {
    $sql="SELECT TC,Serie,CodigoC as 'CI',Razon_Social as 'Cliente',Factura
    FROM Facturas F
    INNER JOIN Clientes ON CodigoC = Codigo 
    WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
    AND Item = '".$_SESSION['INGRESO']['item']."'";
    if($query)
    {
      $sql.=" AND Razon_Social+' '+CodigoC like '%".$query."%'";
    }

    if($factura)
    {
      $sql.=" AND Factura like '%".$factura."%'";
    }

    $sql.="ORDER BY F.ID DESC  OFFSET 0 ROWS FETCH NEXT 100 ROWS ONLY";

    $botones[0] = array('boton'=>'Re imprimir', 'icono'=>'<i class="fa fa-print"></i>', 'tipo'=>'info', 'id'=>'Factura,Serie,CI,TC');
    $stmt =  grilla_generica_new($sql,'Facturas F','tbl_facturas',false,$botones,false,false,1,1,0,$tamaño_tabla=250,2);
    $datos = $this->db->datos($sql);
    return $stmt;
  }
}

?>