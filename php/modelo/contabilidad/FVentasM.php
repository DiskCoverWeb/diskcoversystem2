<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
/**
 * 
 */
class FVentasM
{	
		
    private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}

	 function DCRetIBienes()
   {
   	 $cid=$this->conn;
   	 $sql="SELECT Codigo, (Codigo + ' - ' + Cuenta) As Cuentas 
           FROM Catalogo_Cuentas
           WHERE Item = '".$_SESSION['INGRESO']['item']."' 
           AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
           AND TC = 'CI'
           AND DG = 'D' 
           ORDER BY Codigo ";
         // print_r($sql);die();
         $stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$datos[]=$row;	
	   }
       return $datos;
   }

    function DCRetISer()
   {
   	 $cid=$this->conn;
   	 $sql = "SELECT Codigo, (Codigo + ' - '+ Cuenta) As Cuentas  
             FROM Catalogo_Cuentas 
             WHERE Item = '".$_SESSION['INGRESO']['item']."' 
             AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
             AND TC = 'CI' 
             AND DG = 'D' 
             ORDER BY Codigo ";

   	
         // print_r($sql);die();
         $stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$datos[]=['Codigo'=>$row['Codigo'],'Cuentas'=>mb_convert_encoding($row['Cuentas'], 'UTF-8')];	
		// $datos[] = $row;
	   }
       return $datos;
   }

    function DCPorcenIva($fecha)
   {
   	 $cid=$this->conn;
   	  $sql = "SELECT * 
       FROM Tabla_Por_ICE_IVA 
       WHERE IVA <> 'FALSE' 
       AND Fecha_Inicio <= '".$fecha."' 
       AND Fecha_Final >= '".$fecha."' 
       ORDER BY Porc ";
         // print_r($sql);die();
         $stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		//$datos[]=['Codigo'=>$row['Codigo'],'Cuentas'=>mb_convert_encoding($row['Cuentas'], 'UTF-8')];	
		 $datos[] = $row;
	   }
       return $datos;
   }


    function DCPorcenIce($fecha)
   {
   	 $cid=$this->conn;
   	  $sql = "SELECT * 
       FROM Tabla_Por_ICE_IVA 
       WHERE ICE <> 'FALSE' 
       AND Fecha_Inicio <= '".$fecha."' 
       AND Fecha_Final >= '".$fecha."' 
       ORDER BY Porc ";
       // print_r($sql);die();
         $stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		//$datos[]=['Codigo'=>$row['Codigo'],'Cuentas'=>mb_convert_encoding($row['Cuentas'], 'UTF-8')];	
		 $datos[] = $row;
	   }
       return $datos;
   }

     function DCRetFuente()
   {
   	 $cid=$this->conn;

   	 $sql = "SELECT Codigo,(Codigo + ' - ' + Cuenta) As Cuentas 
            FROM Catalogo_Cuentas
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND TC = 'CF' 
            AND DG = 'D' 
            ORDER BY Codigo ";
         // print_r($sql);die();
         $stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$datos[]=['Codigo'=>$row['Codigo'],'Cuentas'=>mb_convert_encoding($row['Cuentas'], 'UTF-8')];	
		  // $datos[] = $row;
	   }
       return $datos;
   }

    function DCConceptoRet($fecha)
   {
   	 $cid=$this->conn;
   	  $sql = "SELECT (Codigo + ' - ' + Concepto) As Detalle_Conceptos,* 
           FROM Tipo_Concepto_Retencion 
           WHERE Codigo <> '.' 
           AND Fecha_Inicio <= '".$fecha."' 
           AND Fecha_Final >= '".$fecha."' 
           ORDER BY Codigo ";
         // print_r($sql);die();
         $stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$datos[]=['Codigo'=>$row['Codigo'],'Detalle_Conceptos'=>mb_convert_encoding($row['Detalle_Conceptos'], 'UTF-8'),'Porc'=>$row['Porcentaje']];	
		 // $datos[] = $row;
	   }
       return $datos;
   }

    function DCTipoPago()
   {
   	 $cid=$this->conn;
   	 $sql = "SELECT Codigo,(Codigo + ' ' + Descripcion) As CTipoPago 
             FROM Tabla_Referenciales_SRI 
             WHERE Tipo_Referencia = 'FORMA DE PAGO' 
             AND Codigo IN ('01','16','17','18','19','20','21') 
             ORDER BY Codigo ";
         // print_r($sql);die();
         $stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$datos[]=['Codigo'=>$row['Codigo'],'CTipoPago'=>mb_convert_encoding($row['CTipoPago'], 'UTF-8')];	
		 // $datos[] = $row;
	   }
       return $datos;
   }

    function Cargar_DataGrid($Trans_No)
   {
   	 $cid=$this->conn;
   	 $sql = "SELECT *
            FROM Asiento_Air
            WHERE CodRet <> '.'
            AND Item = '".$_SESSION['INGRESO']['item']."'
            AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
            AND T_No = ".$Trans_No."
            AND Tipo_Trans = 'V'
            ORDER BY CodRet ";
            // print_r($sql);die();
       $tbl = grilla_generica_new($sql,'Asiento_Air',$id_tabla = 'tbl_air',$titulo=false,$botones=false,$check=false,$imagen=false,1,1,1,100);
     return $tbl;
   }

   function delete_asiento_venta($Trans_No)
   {
		 $cid=$this->conn;
   	 $sql = "DELETE
         FROM Asiento_Ventas 
         WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
         AND T_No = ".$Trans_No. " ";
         $stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     // return -1;
		     die( print_r( sqlsrv_errors(), true));  
	      }
	      else{
	      	return 1;
	      }     

   }
    function delete_asiento_air($Trans_No)
   {

   	//bora asientos de compra
		 $cid=$this->conn;
		$sql = "DELETE FROM Asiento_Ventas WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' AND T_No = ".$Trans_No."; ";
		$sql.= "DELETE FROM Asiento_Air WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
         AND Tipo_Trans = 'V' AND T_No = ".$Trans_No." ";
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     // return -1;
		     die( print_r( sqlsrv_errors(), true));  
	      }
	      else{
	      	return 1;
	      }      
   }


}
?>