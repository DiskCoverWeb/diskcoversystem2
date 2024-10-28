<?php 
include(dirname(__DIR__,2).'/db/variables_globales.php');//
include(dirname(__DIR__,2).'/db/db1.php');//
include(dirname(__DIR__,2).'/funciones/funciones.php');


/**
 * 
 */
class registro_esM
{
	
	private $conn ;
	private $db;
	function __construct()
	{
	   $this->conn = cone_ajax();
	   $this->db = new db();
	}

	function stock_actual_inventario($parametros){
		$sql = "";
		if(isset($parametros['Por_Bodega'])){
			$sql = "SELECT Codigo_Inv,AVG(Costo) As TCosto,SUM(Entrada-Salida) As Stock,(AVG(Costo)*SUM(Entrada-Salida)) As Saldo_Inv 
					FROM Trans_Kardex 
					WHERE Fecha <= '".BuscarFecha($parametros['Fecha_Inv'])."' 
					AND Codigo_Inv = '".$parametros['Codigo_Inventario']."'
					AND CodBodega = '".$parametros['Por_Bodega']."' 
					AND Item = '".$_SESSION['INGRESO']['item']."' 
					AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
					AND T <> 'A' 
					AND TP <> '.'
					AND Numero > 0 
					GROUP BY Codigo_Inv ";
		}else{
			$sql = "SELECT TOP 1 Codigo_Inv,Costo As TCosto,Existencia As Stock,Total As Saldo_Inv 
					FROM Trans_Kardex 
					WHERE Fecha <= '".BuscarFecha($parametros['Fecha_Inv'])."' 
					AND Codigo_Inv = '".$parametros['Codigo_Inventario']."' 
					AND Item = '".$_SESSION['INGRESO']['item']."' 
					AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
					AND T <> 'A' 
					AND TP <> '.' 
					AND Numero > 0 
					ORDER BY Fecha DESC,TP DESC, Numero DESC,ID DESC";
		}
		return $this->db->datos($sql);
	}

	function grid_kardex($parametros):array{
		$sql = "SELECT * 
				FROM Asiento_K 
				WHERE Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
				AND T_No = '".$parametros['Trans_No']. "'";
		$datos = $this->db->datos($sql);
		$grilla = grilla_generica_new($sql, 'Asiento_K', '', '', false, false, false, 1, 1, 1, 100);
		return array('tabla'=>$grilla, 'datos'=>$datos);
	}
	function familias($query='')
	{
		 $cid = $this->conn;
	  $sql = "SELECT Codigo_Inv,Codigo_Inv+'  '+ Producto As NomProd 
      FROM Catalogo_Productos 
      WHERE Item = '".$_SESSION['INGRESO']['item']."' 
      AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
      AND TC = 'I' ";
      if($query != '')
      {
      	$sql.= " AND Producto LIKE '%".$query."%' ";
      }
      $sql.=" ORDER BY Codigo_Inv ";
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
		$datos[]=['id'=>$row['Codigo_Inv'],'text'=>mb_convert_encoding($row['NomProd'], 'UTF-8')];	
	   }
       return $datos;

	}
	function Producto($fami,$query='',$opcion="")
	{
		 $cid = $this->conn;
		 //CodigoInv = SinEspaciosIzq(DCTInv.Text)
		 $sql = "SELECT *,(Codigo_Inv + '  ' + Producto) As NomProd
		 FROM Catalogo_Productos
		 WHERE Item = '".$_SESSION['INGRESO']['item']."'
		 AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		 AND SUBSTRING(Codigo_Inv,1,".strval(strlen($fami)).") = '".$fami."'
		 AND LEN(Cta_Inventario) > 1 
		 AND TC = 'P' ";
		  if($query != '')
      {
      	$sql.= " AND Producto LIKE '%".$query."%' ";
      }
      if($opcion==1)
      {
      	$sql.= ' ORDER BY Producto';
      }else
      {
      	$sql.=" ORDER BY Codigo_Inv ";
      }	 

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
		$datos[]=['id'=>$row['Codigo_Inv'],'text'=>mb_convert_encoding($row['NomProd'], 'UTF-8')];	
	   }
       return $datos;

	}

		function producto_detalle($fami,$query='',$codBarra='',$CodInv='',$CodInv_='',$opcion='')
	{
		 $cid = $this->conn;
		 //CodigoInv = SinEspaciosIzq(DCTInv.Text)
		 $sql = "SELECT *,(Codigo_Inv + '  ' + Producto) As NomProd
		 FROM Catalogo_Productos
		 WHERE Item = '".$_SESSION['INGRESO']['item']."'
		 AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		 AND SUBSTRING(Codigo_Inv,1,".strval(strlen($fami)).") = '".$fami."'
		 AND LEN(Cta_Inventario) > 1 
		 AND TC = 'P' ";
		  if($query != '')
            {
      	      $sql.= " AND Producto LIKE '%".$query."%' ";
            }
            if($codBarra != '')
            {
      	      $sql.= " AND Codigo_Barra LIKE '%".$codBarra."%' ";
            }
             if($CodInv != '')
            {
      	      $sql.= " AND Codigo_Inv LIKE '%".$CodInv."%' ";
            }

            if($CodInv_ != '')
            {
      	      $sql.= " AND Codigo_Inv =  '".$CodInv_."' ";
            }

          if($opcion==1)
          {
      	    $sql.= ' ORDER BY Producto';
          }else
          {
      	    $sql.=" ORDER BY Codigo_Inv ";
          }	 

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
		$datos= $row;	
	   }
       return $datos;

	}

	function Tabla_Por_ICE_IVA()
	{
		$cid = $this->conn;
	    $sql = "SELECT * 
                FROM Tabla_Por_ICE_IVA 
                WHERE IVA <> 'FALSE'
                AND Fecha_Inicio <= '".date('Y-m-d'). "' 
                AND Fecha_Final >= '".date('Y-m-d'). "' 
                ORDER BY Porc ";
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

	function dato_empresa()
	{
		$cid = $this->conn;
	    $sql= "SELECT * FROM Empresas WHERE Item = '".$_SESSION['INGRESO']['item']."'";
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

	function Trans_Kardex()
	{
		$cid = $this->conn;
	    $sql= "SELECT Numero
               FROM Trans_Kardex
               WHERE Item = '".$_SESSION['INGRESO']['item']."'
               AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
               AND TP = 'CD'
               AND Entrada > 0
               GROUP BY Numero
               ORDER BY Numero DESC ";
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


	function bodega()
	{
		$cid = $this->conn;
	    $sql= "SELECT *
               FROM Catalogo_Bodegas
               WHERE Item = '".$_SESSION['INGRESO']['item']."'
               AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
               ORDER BY Bodega ";
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

	function marca()
	{
		$cid = $this->conn;
	    $sql= "SELECT *
               FROM Catalogo_Marcas
               WHERE Item = '".$_SESSION['INGRESO']['item']."'
               AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
               ORDER BY Marca ";
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

	function contracuenta($query)
	{
		$cid = $this->conn;
	    $sql="SELECT Codigo,Cuenta + SPACE(67-LEN(Cuenta)) + Codigo As Nomb_Cta
             FROM Catalogo_Cuentas
             WHERE Item = '".$_SESSION['INGRESO']['item']."'
             AND Periodo ='".$_SESSION['INGRESO']['periodo']."'
             AND TC IN ('RP','C','P','HC','I','G')
             AND DG = 'D' ";
             if($query!='')
             {
             	$sql.=" AND Cuenta LIKE '%".$query."%' ";
             }
             $sql.= "ORDER BY Codigo,Cuenta";
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
		//$datos[]=$row;			
		$datos[]=['id'=>$row['Codigo'],'text'=>mb_convert_encoding($row['Nomb_Cta'], 'UTF-8')];	
		//$datos[]=['id'=>$row['Codigo'],'text'=>$row['Nomb_Cta']];	
	   }
       return $datos;
	}


	function LeerCta($CodigoCta)
	{

		$cid = $this->conn;
		$Cuenta = G_NINGUNO;
		$Codigo = G_NINGUNO;
		$TipoCta = "G";
		$SubCta = "N";
		$TipoPago = "01";
		$Moneda_US = False;
		$datos= array();
		if (strlen(substr($CodigoCta, 1, 1)) >= 1){
			$sql = "SELECT Codigo, Cuenta, TC, ME, DG, Tipo_Pago
              FROM Catalogo_Cuentas 
              WHERE Codigo = '" .$CodigoCta. "'
              AND Item = '".$_SESSION['INGRESO']['item']."' 
              AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
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
               		//$datos=['id'=>$row['Codigo'],'text'=>mb_convert_encoding($row['Nomb_Cta'], 'UTF-8')];	
               		//$datos[]=['id'=>$row['Codigo'],'text'=>$row['Nomb_Cta']];	
               	}
       }

       return $datos;
    }

  function ListarProveedorUsuario($TipoSubCta,$Contra_Cta,$query)
	{
		$cid = $this->conn;
		switch ($TipoSubCta) {
			case 'RP':
				   $sql = "SELECT C.Cliente,CR.Codigo,C.CI_RUC,C.Direccion,C.Telefono,C.TD,'.' As Cta,0 As Importaciones,C.CI_RUC,C.Grupo,'P' As TipoBenef
                   FROM Clientes As C,Catalogo_Rol_Pagos As CR
                   WHERE CR.Item = '".$_SESSION['INGRESO']['item']."' 
                   AND CR.Periodo = '".$_SESSION['INGRESO']['periodo']."'
                   AND C.Codigo = CR.Codigo ";
                    if($query)
                      {
                  	    $sql.=" AND Cliente LIKE '%".$query."%' ";
                      }

                   $sql.=" ORDER BY C.Cliente ";
				break;
			case 'P':
			case 'C':				
				    $sql= "SELECT C.Cliente,C.Codigo,C.CI_RUC,C.Direccion,C.Telefono,C.TD,CP.Cta,CP.Importaciones,C.CI_RUC,C.Grupo,'P' As TipoBenef
                    FROM Clientes As C,Catalogo_CxCxP As CP
                    WHERE CP.TC = '" .$TipoSubCta."'
                    AND CP.Item = '".$_SESSION['INGRESO']['item']."' 
                    AND CP.Periodo ='".$_SESSION['INGRESO']['periodo']."'
                    AND CP.Cta = '".$Contra_Cta."'
                    AND C.Codigo = CP.Codigo ";
                    if($query)
                      {
                  	    $sql.=" AND Cliente LIKE '%".$query."%' ";
                      }
                    $sql.=" GROUP BY C.Cliente,C.Codigo,C.CI_RUC,C.Direccion,C.Telefono,C.TD,CP.Cta,CP.Importaciones,C.CI_RUC,C.Grupo
                    ORDER BY C.Cliente ";
				break;
			case 'G':
			case 'I':
				 //$OpcX.value = 1
                   $sql = "SELECT CS.Detalle As Cliente,CS.*,'O' As TD,'.' As Cta,0 As Importaciones,'9999999999999' As CI_RUC,'999999' As Grupo,'X' As TipoBenef
                   FROM Catalogo_SubCtas As CS
                   WHERE CS.Item ='".$_SESSION['INGRESO']['item']."' 
                   AND CS.Periodo = '".$_SESSION['INGRESO']['periodo']."'
                   AND CS.TC = '".$TipoSubCta."' ";
                    if($query)
                      {
                  	    $sql.=" AND Cliente LIKE '%".$query."%' ";
                      }

                   $sql.=" ORDER BY Detalle ";
				break;
			default:
			      $sql = "SELECT Cliente,Codigo,CI_RUC,Grupo,Direccion,Telefono,TD,'.' As Cta,0 As Importaciones,'X' As TipoBenef
                  FROM Clientes
                  WHERE  T='N'";
                   if($query)
                      {
                  	    $sql.=" AND Cliente LIKE '%".$query."%' ";
                      }

                  $sql.=" ORDER BY Cliente ";
				break;
		}

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
               		//$datos[]=$row;
               		$datos[]=['id'=>$row['Codigo'],'text'=>mb_convert_encoding($row['Cliente'], 'UTF-8'),'CICLIENTE'=>mb_convert_encoding($row['CI_RUC'], 'UTF-8'),'grupo_no'=>$row['Grupo'],'tipodoc'=>$row['TD'],'TipoBenef'=>$row['TD'],'cod_benef'=>$row['TipoBenef'],'InvImp'=>$row['Importaciones']];	
               		//$datos[]=['id'=>$row['Codigo'],'text'=>$row['Nomb_Cta']];	
               	}

       return $datos;
    }





	function borrar_asientos($Trans_No,$B_Asiento=false)
	{
		$sql = '';
		if($Trans_No <=0)
		{
			$Trans_No = 1;
		}
		 if($B_Asiento)
		 {
		 	$sql.= "DELETE   FROM Asiento WHERE Item = '".$_SESSION['INGRESO']['item']."'  AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'
          AND T_No = ".$Trans_No;
		 }

	   $sql.= "DELETE FROM Asiento_SC WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "' AND T_No = ".$Trans_No.';';

       $sql.= "DELETE FROM Asiento_B WHERE Item = '".$_SESSION['INGRESO']['item']."'  AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "' AND T_No = ".$Trans_No.';';

       $sql.= "DELETE FROM Asiento_R WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

       $sql.= "DELETE FROM Asiento_RP WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

       $sql.= "DELETE FROM Asiento_K WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

       $sql.= "DELETE FROM Asiento_P WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

         $sql.= "DELETE FROM Asiento_Air WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

         $sql.= "DELETE FROM Asiento_Compras WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

         $sql.= "DELETE FROM Asiento_Exportaciones  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

         $sql.= "DELETE FROM Asiento_Importaciones  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

         $sql.= "DELETE FROM Asiento_Ventas  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

          $cid=$this->conn;
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return -1;
		     die( print_r( sqlsrv_errors(), true));  
	      }
	      else{
	      	return 1;
	      }     

	}

	function codigo_proveedor($ruc)
	{

     $cid = $this->conn;
     $sql= "SELECT Codigo,Cliente FROM Clientes WHERE CI_RUC = '".$ruc."'";
       $stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
        // print_r($sql);die();
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

function dtaAsiento_sc($Trans_No){

     $cid = $this->conn;
	$sql= "SELECT * 
       FROM Asiento_SC 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento_b($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento_B 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento_air($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento_Air 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento_compras($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento_Compras 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento_ventas($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento_Ventas 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento_impo($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento_Importaciones 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento_expo($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento_Exportaciones 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento_k($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento_K 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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

   function codigos($Concepto)
   {
   	 $cid = $this->conn;
  $sql= "SELECT Numero, ID 
             FROM Codigos 
             WHERE Concepto = '".$Concepto."' 
             AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
             AND Item = '".$_SESSION['INGRESO']['item']."'";
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

   function ingresar_codigo($NumEmpA,$sql,$NumCodigo)
   {
   	  
		 $cid=$this->conn;
		$sql = "INSERT INTO Codigos (Periodo,Item,Concepto,Numero) VALUES ('".$_SESSION['INGRESO']['periodo']."' ,'".$NumEmpA."','".$sql."',".$NumCodigo.") ";
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return -1;
		     die( print_r( sqlsrv_errors(), true));  
	      }
	      else{
	      	return 1;
	      }      
   }

   function actualizar_codigo($NumEmpA,$sql)
   {

		 $cid=$this->conn;
   	$sql  = "UPDATE Codigos SET Numero = Numero + 1 WHERE Concepto = '".$sql."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$NumEmpA."' ";
	
		$stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return -1;
		     die( print_r( sqlsrv_errors(), true));  
	      }
	      else{
	      	return 1;
	      }      
   }

   function DCRetIBienes()
   {
   	 $cid=$this->conn;
   	 $sql = "SELECT Codigo, (Codigo+ ' - '+ Cuenta) As Cuentas 
         FROM Catalogo_Cuentas
         WHERE Item = '".$_SESSION['INGRESO']['item']."' 
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
         AND TC = 'RB'
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
   	 $sql = "SELECT Codigo, (Codigo+ ' - '+ Cuenta) As Cuentas 
         FROM Catalogo_Cuentas
         WHERE Item = '".$_SESSION['INGRESO']['item']."' 
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
         AND TC = 'RI'
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
		// $datos[]=['Codigo'=>$row['Codigo'],'Cuentas'=>$row['Cuentas']];	
		// $datos[] = $row;
	   }
       return $datos;
   }

    function DCSustento($fecha,$CargarTC=false)
   {
   	 $cid=$this->conn;
   	   $sql = "SELECT (Credito_Tributario+' - '+ Descripcion) As Sustento,* 
       FROM Tipo_Tributario 
       WHERE Credito_Tributario <> '.' 
       AND Fecha_Inicio <= '".$fecha."' 
       AND Fecha_Final >= '".$fecha."'";
       if($CargarTC)
       {
       	 $sql.= " AND Credito_Tributario = '".$CargarTC."'";
       }
       $sql.="ORDER BY Credito_Tributario ";
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

    function DCDctoModif()
   {
   	 $cid=$this->conn;
   	  $sql= "SELECT Tipo_Comprobante_Codigo as 'Codigo', Descripcion
           FROM Tipo_Comprobante
           WHERE TC = 'TDC'
           ORDER BY Descripcion ";
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

    function DCTipoPago()
   {
   	 $cid=$this->conn;
   	  $sql = "SELECT Codigo,(Codigo + ' ' + Descripcion) As CTipoPago
         FROM Tabla_Referenciales_SRI
         WHERE Tipo_Referencia = 'FORMA DE PAGO'
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
		$datos[]=['Codigo'=>$row['Codigo'],'CTipoPago'=>mb_convert_encoding($row['CTipoPago'],'UTF-8')];	
		 // $datos[] = $row;
	   }
       return $datos;
   }
     function DCRetFuente()
   {
   	 $cid=$this->conn;
   	  $sql = "SELECT Codigo,(Codigo+ ' - '+ Cuenta) As Cuentas 
         FROM Catalogo_Cuentas
         WHERE Item = '".$_SESSION['INGRESO']['item']."' 
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
         AND TC = 'RF'
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
   function DCPais()
   {
   	 $cid=$this->conn;
   	 $sql = "SELECT *
         FROM Tabla_Naciones
         WHERE TR = 'N'
         ORDER BY Descripcion_Rubro ";
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


    function DCTipoComprobante($Cadena,$TipoBenef)
   {
   	if ($Cadena =='') {
   		$Cadena = G_NINGUNO;
   	}
   	 $cid=$this->conn;
   	  $sql ="SELECT TC,Tipo_Comprobante_Codigo as 'Tipo',Descripcion,R,C,E,ID
         FROM Tipo_Comprobante
         WHERE Tipo_Comprobante_Codigo IN (".$Cadena.")
         AND TC = 'TDC' ";
          if($TipoBenef=='R')
          {
          	$sql.= "AND R <> 'FALSE' ";
          }else
          {
          	$sql.= "AND C <> 'FALSE' ";
          }
          $sql.="ORDER BY Tipo_Comprobante_Codigo ";
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
		//$datos[]=['Codigo'=>$row['Codigo'],'Detalle_Conceptos'=>mb_convert_encoding($row['Detalle_Conceptos'], 'UTF-8')];	
		  $datos[] = $row;
	   }
       return $datos;
   }

     function DCBenef_Data($Cadena,$TipoBenef)
   {
   	
   	 $cid=$this->conn;
   	 $sql="";
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
		//$datos[]=['Codigo'=>$row['Codigo'],'Detalle_Conceptos'=>mb_convert_encoding($row['Detalle_Conceptos'], 'UTF-8')];	
		  $datos[] = $row;
	   }
       return $datos;
   }


    function DCTipoComprobante2($Cadena,$TipoBenef)
   {
   	if ($Cadena =='') {
   		$Cadena = G_NINGUNO;
   	}
   	 $cid=$this->conn;
   	 $sql= "SELECT *
         FROM Tipo_Comprobante
         WHERE Tipo_Comprobante_Codigo IN (".$Cadena.")
         AND TC = 'TDC' ";
          if($TipoBenef=='R')
          {
          	$sql.= "AND R <> 'FALSE' ";
          }else
          {
          	$sql.= "AND C <> 'FALSE' ";
          }
          $sql.="ORDER BY Tipo_Comprobante_Codigo ";
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
		//$datos[]=['Codigo'=>$row['Codigo'],'Detalle_Conceptos'=>mb_convert_encoding($row['Detalle_Conceptos'], 'UTF-8')];	
		  $datos[] = $row;
	   }
       return $datos;
   }

   function Maximo_De($Tabla,$Campo)
   {

   	 $cid=$this->conn;
   	 $RegMaximo = 0;
   	 if($Campo <> "ID")
   	 {
   	 	$RegMaximo = 1;
   	 	$sql = "SELECT MAX(".$Campo.") As Maximo 
        FROM ".$Tabla." 
        WHERE ".$Campo." <> 0 ";
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
		//$datos[]=['Codigo'=>$row['Codigo'],'Detalle_Conceptos'=>mb_convert_encoding($row['Detalle_Conceptos'], 'UTF-8')];	
		  $datos[] = $row;
	   }
	   if(count($datos)>0)
	   {
	   	if($datos[0]['Maximo']!= null)
	   	{
	   		$RegMaximo= $datos[0]['Maximo']+1;
	   	}
	   }
     }

       return $RegMaximo;
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
            AND Tipo_Trans = 'C'
            ORDER BY CodRet ";
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
		//$datos[]=['Codigo'=>$row['Codigo'],'Detalle_Conceptos'=>mb_convert_encoding($row['Detalle_Conceptos'], 'UTF-8')];	
		  $datos[] = $row;
	   }
	   $botones[0] = array('boton'=>'eliminar linea Retencion', 'icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'A_No,CodRet' );
       $tbl = grilla_generica_new($sql,'Asiento_Air',$id_tabla = 'tbl_airV',$titulo=false,$botones,$check=false,$imagen=false,1,1,1,70);
	   return array('datos'=>$datos,'tbl'=>$tbl);
   }


   function Carga_RetencionIvaBienes_Servicios()
   {
   	 $cid=$this->conn;
   	 $sql = "SELECT * FROM Tabla_Por_IVA  WHERE Bienes <> 0  ORDER BY Porc ";
   	 $sql2 = "SELECT * FROM Tabla_Por_IVA WHERE Servicios <> 0 ORDER BY Porc ";
            // print_r($sql);die();
       $stmt = sqlsrv_query($cid, $sql);
       $stmt2 = sqlsrv_query($cid, $sql2);
       $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		//$datos[]=['Codigo'=>$row['Codigo'],'Detalle_Conceptos'=>mb_convert_encoding($row['Detalle_Conceptos'], 'UTF-8')];	
		  $datos[] = $row;
	   }

       $datos2 =  array();
	   if( $stmt2 === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) 
	   {
		//$datos[]=['Codigo'=>$row['Codigo'],'Detalle_Conceptos'=>mb_convert_encoding($row['Detalle_Conceptos'], 'UTF-8')];	
		  $datos2[] = $row2;
	   }

	   $resultado = array('bienes'=>$datos,'servicios'=>$datos2);
	   // print_r($resultado);die();
	   return $resultado;

   }

   function Ult_fact_Prove($codCli)
   {
   	// print_r($codCli);die();
   	 $cid=$this->conn;
   	 $sql = "SELECT TOP 1 * 
          FROM Trans_Compras 
          WHERE IdProv = '".$codCli."' 
          AND Item = '".$_SESSION['INGRESO']['item']."' 
          ORDER BY Fecha DESC,Secuencial DESC ";
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
		//$datos[]=['Codigo'=>$row['Codigo'],'Detalle_Conceptos'=>mb_convert_encoding($row['Detalle_Conceptos'], 'UTF-8')];	
		  $datos[] = $row;
	   }
	   return $datos;

   }

   function cancelar($Trans_No)
   {

   	//bora asientos de compra
		 $cid=$this->conn;
		$sql = "DELETE FROM Asiento_Compras WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' AND T_No = ".$Trans_No."; ";
		$sql.= "DELETE FROM Asiento_Air WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
         AND Tipo_Trans = 'C' AND T_No = ".$Trans_No." ";
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


function Documento_Modificado($codUsuario)
{
	 $cid=$this->conn;
	 $sql = "SELECT *
            FROM Trans_Compras
            WHERE Item = '".$_SESSION['INGRESO']['item']."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND IdProv = '".$codUsuario."'
            ORDER BY Secuencial ";
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
		//$datos[]=['Codigo'=>$row['Codigo'],'Detalle_Conceptos'=>mb_convert_encoding($row['Detalle_Conceptos'], 'UTF-8')];	
		  $datos[] = $row;
	   }
	   return $datos;
}


function cuentas_todos($query)
	{
		$cid = $this->conn;
		$sql="SELECT Codigo+Space(19-LEN(Codigo))+' -- '+TC+Space(3-LEN(TC))+' -- '+cast( Clave as varchar(5))+' '
					+Space(5-LEN(cast( Clave as varchar(5))))+' -- '+
					Cuenta As Nombre_Cuenta,Codigo,Cuenta,TC 
			   FROM Catalogo_Cuentas 
			   WHERE DG = 'D' 
			   AND Cuenta <> '".$_SESSION['INGRESO']['ninguno']."' AND Item = '".$_SESSION['INGRESO']['item']."'
			   AND Periodo = '".$_SESSION['INGRESO']['periodo']."'  ";
			   if($query)
			   	{
			   		$sql.="AND Codigo+Space(19-LEN(Codigo))+' '+TC+Space(3-LEN(TC))+' '+cast( Clave as varchar(5))+' '
					+Space(5-LEN(cast( Clave as varchar(5))))+' '+Cuenta LIKE '%".$query."%'";
			   	}
			   	$sql.="ORDER BY Codigo";
		// print_r($sql);die();
		 $stmt = sqlsrv_query($cid, $sql);
		 $result = array();
		 while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 	{
		 		$result[] = $row;
		 	}
		return $result;
	}

	function existe_numero($numUno,$numDos,$retencion)
	{
		$cid = $this->conn;
		$sql= "SELECT * 
            FROM Trans_Air 
            WHERE Tipo_Trans = 'C' 
            AND Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND EstabRetencion = '".$numUno."' 
            AND PtoEmiRetencion = '".$numDos."' 
            AND SecRetencion = ".$retencion." ";
		// print_r($sql);die();
		 $stmt = sqlsrv_query($cid, $sql);
		 $result = array();
		 while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 	{
		 		$result[] = $row;
		 	}
		return $result;

	}
	function ultima_factura_proveedor($CodigoCliente)
   {
   		$cid = $this->conn;
   	   $sql = "SELECT TOP 1 * 
            FROM Trans_Compras 
            WHERE IdProv = '".$CodigoCliente."' 
            AND Item = '".$_SESSION['INGRESO']['item']."' 
            ORDER BY Fecha DESC,Secuencial DESC ";
               // print_r($sql);
       $result = array();
       $stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 {
		 	$result[] = $row;
		 }
		  return $result;
   }

   function numero_autorizacion($serie1,$serie2,$fechaReg)
   {
   		$cid = $this->conn;
   	    $sql = "SELECT TOP 1 AutRetencion 
        FROM Trans_Air 
        WHERE Tipo_Trans = 'C' 
        AND Item = '".$_SESSION['INGRESO']['item']."' 
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND Fecha <= '".$fechaReg."' 
        AND EstabRetencion = '".$serie1."' 
        AND PtoEmiRetencion = '".$serie2."' 
        ORDER BY SecRetencion DESC, Fecha DESC, AutRetencion DESC ";

        // print_r($sql);die();
         $result = array();
       $stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 {
		 	$result[] = $row;
		 }
		  return $result;
   }

 function  numero_autorizacion_tc($serie1,$serie2,$fechaReg,$cod=false)
 {    $res = '';
    if($cod == 4)
    {
        $sql = "SELECT TOP 1 * 
        FROM Trans_Compras 
        WHERE TipoComprobante = ".$cod." 
        AND Establecimiento = '".$serie1."' 
        AND PuntoEmision = '".$serie2."' 
        AND Item = '".$_SESSION['INGRESO']['item']. "' 
        ORDER BY Fecha DESC,Secuencial DESC ";

     $res =  $this->db->datos($sql);
    }
    return $res;
}

   function validar_factura($CodigoCliente,$uno,$dos,$tres,$auto)
   {
   	
   		$cid = $this->conn;
   	 $sql = "SELECT TOP 1 * 
         FROM Trans_Compras 
         WHERE IdProv = '".$CodigoCliente."' 
         AND Item = '".$_SESSION['INGRESO']['item']."'
         AND Establecimiento = '".$uno."' 
         AND PuntoEmision = '".$dos."' 
         AND Secuencial = ".intval($tres)." 
         AND Autorizacion = '".$auto."' 
         ORDER BY Fecha DESC, Secuencial DESC ";
             // print_r($sql);die();
         $result = array();
       $stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 {
		 	$result[] = $row;
		 }
		  return $result;

   }
  
     function insertar_aseinto($codigo,$cuenta,$parcial,$debe,$haber,$chq_as,$dconcepto1,$efectivo_as,$t_no,$A_No,$TC)
     {
        $cid=$this->conn;
     	$sql="INSERT INTO Asiento
			(CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE,EFECTIVIZAR,CODIGO_C,CODIGO_CC,ME,T_No,Item,CodigoU,A_No,TC)
				VALUES
			('".$codigo."','".$cuenta."',".$parcial.",".$debe.",".$haber.",'".$chq_as."','".$dconcepto1."',
				'".$efectivo_as."','.','.',0,".$t_no.",'".$_SESSION['INGRESO']['item']."','".$_SESSION['INGRESO']['CodigoU']."',".$A_No.",'".$TC."')";
		 $stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			return -1;
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
		return 1;

     }


     function buscar_cta($cta)
     {
     	$cid = $this->conn;
     	$sql = "SELECT * 
     			FROM Ctas_Proceso 
     			WHERE Detalle = '".$cta."' 
     			AND Periodo = '".$_SESSION['INGRESO']["periodo"]."' 
     			AND Item = '".$_SESSION['INGRESO']['item']."'";
     	$result = array();
       $stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 {
		 	$result[] = $row;
		 }
		  return $result;

     }

     function eliminar_air($a_no,$cod)
     {

     	$sql = "DELETE From Asiento_Air WHERE CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' AND A_No = '".$a_no."' AND CodRet = '".$cod."'";
     	return $this->db->String_Sql($sql);
     }

     function cambiar_codigo_sec($num,$SQLs)
     {
     	$sql = "UPDATE Codigos 
                SET Numero = $num 
                WHERE Concepto = '".$SQLs."'
                AND Periodo = '" .$_SESSION['INGRESO']['periodo']."' 
                AND Item = '".$_SESSION['INGRESO']['item']. "' ";
                // print_r($sql);
       	return $this->db->String_Sql($sql);
     }

	 function seleccionar_comprobante($parametros){
		try{
			$sql = "DELETE * 
					FROM Trans_Kardex 
					WHERE TP = 'CD' 
					AND Numero = '".$parametros['Numero']."' 
					AND Item = '".$_SESSION['INGRESO']['item']."' 
					AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
			Ejecutar_SQL_SP($sql);
			$sql = "SELECT * 
					FROM Asiento_K 
					WHERE Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
					AND T_No = '".$parametros['Trans_No']."'";
			$AdoKardex = $this->db->datos($sql);
			if(count($AdoKardex) > 0){
				foreach($AdoKardex as $row){
					SetAdoAddNew("Trans_Kardex");
					SetAdoFields("T", G_NORMAL);
					SetAdoFields("TP", $parametros['CLTP']);
					SetAdoFields("Numero", $parametros['Numero']);
					SetAdoFields("Fecha", $parametros['MBFechaI']);
					SetAdoFields("Codigo_Inv", $row['CODIGO_INV']);
					SetAdoFields("Codigo_P", $row['Codigo_B']);
					SetAdoFields("Descuento", $row['P_DESC']);
					SetAdoFields("Descuento1", $row['P_DESC1']);
					SetAdoFields("Valor_Total", $row['VALOR_TOTAL']);
					SetAdoFields("Existencia", $row['CANTIDAD']);
					SetAdoFields("Valor_Unitario", $row['VALOR_UNIT']);
					SetAdoFields("Total", $row['SALDO']);
					SetAdoFields("Cta_Inv", $row['CTA_INVENTARIO']);
					SetAdoFields("Contra_Cta", $row['CONTRA_CTA']);
					SetAdoFields("Orden_No", $row['ORDEN']);
					SetAdoFields("CodBodega", $row['CodBod']);
					SetAdoFields("CodMar", $row['CodMar']);
					SetAdoFields("Codigo_Barra", $row['COD_BAR']);
					SetAdoFields("Costo", $row['VALOR_UNIT']);
					SetAdoFields("PVP", $row['PVP']);
					if(isset($_SESSION['SETEOS']['Inv_Promedio'])){
						$Cantidad = $row['CANTIDAD'] <= 0 ? 1 : $row['CANTIDAD'];
						$Saldo = $row['SALDO'];
						SetAdoFields("Costo", $Saldo / $Cantidad);
					}
					if($row['DH'] == 1){
						SetAdoFields("Entrada", $row['CANT_ES']);
					}else{
						SetAdoFields("Salida", $row['CANT_ES']);
					}
					SetAdoFields("CodigoU", $_SESSION['INGRESO']['CodigoU']);
					SetAdoFields("Item", $_SESSION['INGRESO']['item']);
					SetAdoUpdate();
				}
				$this->borrar_asientos($parametros['Trans_No']);
				return array('res' => 1, 'msg' => 'Proceso exitoso');
			}else{
				throw new Exception("No se encontraron datos en Asiento_K");
			}
		}catch(Exception $e){
			throw new Exception($e->getMessage());
		}
	 }

	 function delete_asientos($parametros){
		$sql = "DELETE 
				FROM Asiento_SC 
				WHERE Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
				AND T_No  = '".$parametros['Trans_No']."'";
		Ejecutar_SQL_SP($sql);
		$sql = "DELETE 
				FROM Asiento 
				WHERE Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
				AND T_No  = '".$parametros['Trans_No']."'";
		Ejecutar_SQL_SP($sql);
	 }

	 function select_asientoK($parametros){
		$sql = "SELECT * 
				FROM Asiento_K 
				WHERE Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
				AND T_No = '".$parametros['Trans_No']."' 
				ORDER BY CTA_INVENTARIO, CONTRA_CTA";
		return $this->db->datos($sql);
	 }

	 function select_asiento_compras($parametros){
		$sql = "SELECT * 
				FROM Asiento_Compras 
				WHERE Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
				AND T_No = '".$parametros['Trans_No']."'";
		return $this->db->datos($sql);
	 }

	 function select_asiento_air($parametros){
		$sql = "SELECT *
				FROM Asiento_Air 
				WHERE Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
				AND T_No = '".$parametros['Trans_No']."' 
				AND Tipo_Trans = 'C' 
				ORDER BY Cta_Retencion, A_No, ValRet ";
		return $this->db->datos($sql);
	 }

	 function select_asiento_k_contra_cta($parametros){
		$sql = "SELECT * 
				FROM Asiento_K 
				WHERE Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
				AND T_No = '".$parametros['Trans_No']."' 
				ORDER BY CONTRA_CTA";
		return $this->db->datos($sql);
	 }

	 function select_asientos($parametros){
		$sql = "SELECT * 
				FROM Asiento 
				WHERE Item = '".$_SESSION['INGRESO']['item']."'
				AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
				AND T_No = '".$parametros['Trans_No']."' ";
		return $this->db->datos($sql);
	 }


}
?>