<?php
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class kardexM
{
   	private $db ;
    public $NumEmpresa;
    public $Periodo_Contable;
    function __construct()
    {
       $this->db = new db();
       $this->NumEmpresa = $_SESSION['INGRESO']['item'];
        $this->Periodo_Contable = $_SESSION['INGRESO']['periodo'];
    }



  function ExecuteDB($sSQL)
  {
       return $this->db->String_Sql($sSQL);
  }

  function SelectDB($sSQL)
  {
       return $this->db->datos($sSQL);
  }
  
  public function ListarProductos($tipo,$codigoProducto){
    $Codigo = trim($codigoProducto);
    $sSQL = "SELECT Producto, Codigo_Inv, Unidad, Minimo, Maximo, Codigo_Inv + '  ' + Producto AS NomProd "
        . "FROM Catalogo_Productos "
        . "WHERE Item = '" . $this->NumEmpresa. "' "
        . "AND Periodo = '" . $this->Periodo_Contable . "' "
        . (($Codigo!="")?"AND SUBSTRING(Codigo_Inv, 1, " . strlen($Codigo) . ") = '" . $Codigo . "' ":"")
        . "AND X = 'M' "
        . "AND TC = '$tipo' ";

    switch ($tipo) {
      case "I":
          break;

      case "P":
          $sSQL .= " AND Cta_Inventario <> '.' "
              . "AND Cta_Inventario <> '0' ";
          break;  
    }

    $sSQL .= "ORDER BY Codigo_Inv, Producto ";
    $stmt = $this->db->datos($sSQL);
    return $stmt;
  }

  function Listar_Articulos($SoActivos = false, $ejecutar=false) {
    $sSQL = "SELECT Codigo_Inv as codigo, Producto as nombre "
        . "FROM Catalogo_Productos "
        . "WHERE Item = '" . $this->NumEmpresa . "' "
        . "AND Periodo = '" . $this->Periodo_Contable . "' ";
    
    if ($SoActivos) {
        $sSQL .= "AND T = 'N' ";
    }
    
    $sSQL .= "AND TC = 'P' "
        . "AND INV <> " . intval(0) . " "
        . "AND LEN(Cta_Inventario) > 1 "
        . "AND LEN(Cta_Costo_Venta) > 1 "
        . "ORDER BY Codigo_Inv ";
    
    if($ejecutar){
        return $this->db->datos($sSQL);
    }else{
        return $sSQL;
    }
}


  public function bodegas(){
    $sql="SELECT Bodega, CodBod
          FROM Catalogo_Bodegas 
          WHERE Item = '".$this->NumEmpresa."'
          AND Periodo = '".$this->Periodo_Contable."'
          ORDER BY CodBod";
    return $this->db->datos($sql);
  }

  public function funcionInicio(){
    $sql = "UPDATE Catalogo_Productos 
            SET X = '.' 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'"; 
    $stmt = $this->db->datos($sql);

    $sql = "UPDATE Catalogo_Productos 
            SET X = 'M' 
            FROM Catalogo_Productos As CP, Trans_Kardex As TK 
            WHERE CP.Item = '".$_SESSION['INGRESO']['item']."' 
            AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND CP.Item = TK.Item 
            AND CP.Periodo = TK.Periodo 
            AND CP.Codigo_Inv = TK.Codigo_Inv" ;
    $stmt = $this->db->datos($sql);

    $sql = "SELECT Codigo_Inv 
            FROM Catalogo_Productos 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND TC = 'P' 
            AND X = 'M' 
            GROUP BY Codigo_Inv 
            ORDER BY Codigo_Inv";
    $datos = $this->db->datos($sql);
    $primeravez = true;

    while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
        $Codigo1 =CodigoCuentaSup($value['Codigo_Inv']);
        $Codigo = CodigoCuentaSup($value['Codigo_Inv']);
        $Codigo = CodigoCuentaSup($Codigo);
        if ($Codigo != $Codigo1) {
          while (strlen($Codigo) > 1) {
            $sSQL = "UPDATE Catalogo_Productos 
                      SET X = 'M' 
                      WHERE Item = '".$_SESSION['INGRESO']['item']."' 
                      AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
                      AND X <> 'M' 
                      AND Codigo_Inv = '".$Codigo."'";     
            $stmt = sqlsrv_query( $this->db, $sSQL);
            $Codigo = CodigoCuentaSup($Codigo);
          }
        }
    }

  }

  public function kardex_total($desde,$hasta,$codigo,$CheqBod,$bodega){
    $sql =  "UPDATE Trans_Kardex 
            SET Centro_Costo = SUBSTRING(C.Cliente,1,50) 
            FROM Trans_Kardex As TK, Clientes As C 
            WHERE TK.Item = '".$_SESSION['INGRESO']['item']."' _
            AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND TK.Codigo_P <> '.'
            AND TK.Fecha BETWEEN '".$desde."' AND '".$hasta."'
            AND TK.Codigo_P = C.Codigo ";
    $this->db->datos($sql);
    $sql =  "UPDATE Trans_Kardex 
            SET Centro_Costo = SUBSTRING(CS.Detalle,1,50) 
            FROM Trans_Kardex As TK, Catalogo_SubCtas As CS 
            WHERE TK.Item = '".$_SESSION['INGRESO']['item']."' 
            AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND TK.Codigo_P <> '.' 
            AND TK.Fecha BETWEEN '".$desde."' AND '".$hasta."' 
            AND TK.Item = CS.Item 
            AND TK.Periodo = CS.Periodo 
            AND TK.Codigo_P = CS.Codigo ";
    $this->db->datos($sql);

    $sql =  "UPDATE Trans_Kardex 
            SET Centro_Costo = SUBSTRING(CS.Detalle,1,50) 
            FROM Trans_Kardex As TK, Catalogo_SubCtas As CS 
            WHERE TK.Item = '".$_SESSION['INGRESO']['item']."' 
            AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND TK.CodigoL <> '.' 
            AND TK.Fecha BETWEEN '".$desde."' AND #'".$hasta."' 
            AND TK.Item = CS.Item 
            AND TK.Periodo = CS.Periodo 
            AND TK.CodigoL = CS.Codigo ";
    $this->db->datos($sql);
  
    $sql =  "UPDATE Trans_Kardex 
            SET Centro_Costo = '.' 
            WHERE Centro_Costo IS NULL ";
    $this->db->datos($sql);

    $sql =  "SELECT TK.Codigo_Inv, CP.Producto, TK.CodBodega As Bodega, TK.Fecha,TK.Entrada, TK.Salida, TK.Existencia, 
            TK.Valor_Unitario, TK.Valor_Total, TK.Costo, TK.Total, TK.TP, TK.Numero As Comp_No, TK.TC, TK.Serie, 
            TK.Factura, C.CI_RUC, C.Cliente As Beneficiario, TK.Detalle, TK.Lote_No, CP.Unidad, TK.Cta_Inv, TK.Contra_Cta, TK.Centro_Costo, TK.Codigo_Barra 
            FROM Trans_Kardex As TK, Catalogo_Productos As CP, Clientes As C 
            WHERE TK.Fecha BETWEEN '".$desde."' AND '".$hasta."'
            AND TK.T = '".G_NORMAL."' 
            AND TK.Item = '".$_SESSION['INGRESO']['item']."' 
            AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
    if ($codigo != "*") {
      $sql .= "AND TK.Codigo_Inv LIKE '".$codigo."%' ";
    }
    if ($CheqBod) {
      $sql .= "AND TK.CodBodega = '".$bodega."'";
    }
    $sql .=  "AND TK.Item = CP.Item 
            AND TK.Periodo = CP.Periodo 
            AND TK.Codigo_Inv = CP.Codigo_Inv 
            AND TK.Codigo_P = C.Codigo 
            ORDER BY TK.Codigo_Inv, TK.Fecha, TK.Entrada DESC, TK.Salida, TK.TP, TK.Numero, TK.ID ";
    $stmt = $this->db->datos($sql);
    $tabla = grilla_generica_new($sql,'Trans_Kardex','myTable','',false,false,false,1,1,1,100);
    return $tabla;
  }
} 
?>