<?php 
if(!class_exists('variables_g'))
{
 include(dirname(__DIR__,2).'/db/variables_globales.php');//
 include(dirname(__DIR__,2).'/funciones/funciones.php');
}
@session_start(); 

/**
 * 
 */
class articulosM
{
	
	private $conn ;
	function __construct()
	{
	   $this->conn = new db();
	}

	function cargar_productos($query=false,$pag=false)
	{
		if($pag==false)
		{
			$pag = 0;
		}

		$cid = $this->conn;
		$sql = "SELECT ID,Codigo_Inv,Producto,TC,Minimo,Maximo,Cta_Inventario,Unidad,Ubicacion,IVA,Reg_Sanitario FROM Catalogo_Productos  WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' AND item='".$_SESSION['INGRESO']['item']."'  AND TC='P' AND LEN(Cta_Inventario)>3 AND LEN(Cta_Costo_Venta)>3 ";
		if($query) 
		{
			$sql.=" AND Codigo_Inv+' '+Producto LIKE '%".$query."%'";
		}
		$sql.=" ORDER BY ID OFFSET ".$pag." ROWS FETCH NEXT 25 ROWS ONLY;";
		
		$datos = $this->conn->datos($sql);
       return $datos;
	}

	function cargar_productos_pedido($orden=false,$prove=false,$fecha=false)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT * FROM Asiento_K WHERE CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' AND Item = '".$_SESSION['INGRESO']['item']."' AND DH = '1' ";
     if($orden)
     {
     	$sql.=" AND ORDEN ='".$orden."' ";
     }
     if($prove)
     {
     	$sql.=" AND SUBCTA ='".$prove."' ";
     }
     if($fecha)
     {
     	$sql.=" AND Fecha_DUI ='".$fecha."' ";
     }
     $sql.=' ORDER BY A_No DESC';
     
       return $datos =  $this->conn->datos($sql);;
	}

	function cargar_productos_pedido_TAB()
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT DISTINCT ORDEN,SUBCTA FROM Asiento_K WHERE CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' AND Item = '".$_SESSION['INGRESO']['item']."' AND DH = '1'";
       
    		 return $this->conn->datos($sql);
	}


	function familia_pro($Codigo=false,$query= false,$exacto=false)
	{
		$cid = $this->conn;
		$sql = "SELECT ID,Codigo_Inv,Producto,TC,Minimo,Maximo,Cta_Inventario 
		        FROM Catalogo_Productos  
		        WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		        AND item='".$_SESSION['INGRESO']['item']."'  
		        AND TC='I' 
		        AND INV='1'";
		if($Codigo)
		{
			$sql.="	 AND Codigo_Inv ='".$Codigo."'"; 
		}
		if($query)
		{
			if($exacto)
			{
				$sql.= " and Producto = '".$query."'";
			}else
			{
				$sql.= " and Producto LIKE '%".$query."%'";
			}
		}
		$sql.= " ORDER BY Producto";
		// print_r($sql);die();
		return $this->conn->datos($sql);

	}

	function catalogo_cuentas($Codigo=false,$query=false)
	{
		$sql = "SELECT Codigo,Cuenta  FROM Catalogo_Cuentas WHERE  TC='RP' AND DG='D' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND item='".$_SESSION['INGRESO']['item']."' ";
		if($Codigo)
		{
			$sql.="	 AND Codigo ='".$Codigo."'"; 
		}
		if($query)
		{
			$sql.= " and Producto = '%".$query."%'";
		}
		

		return $this->conn->datos($sql);

	}

	function datos_asiento_haber($orden,$proveedor)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT SUM(VALOR_TOTAL) as 'total',CTA_INVENTARIO as 'cuenta',Fecha_DUI as 'fecha',TC,IVA FROM Asiento_K  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND ORDEN = '".$orden."'  AND SUBCTA = '".$proveedor."' GROUP BY Codigo_B,ORDEN,CONTRA_CTA,CTA_INVENTARIO,Fecha_DUI,TC,IVA";
         
       return $datos = $this->conn->datos($sql);
	}

	function datos_asiento_haber_CON_IVA($orden,$proveedor)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT ROUND(SUM(CANTIDAD*VALOR_UNIT-P_DESC),2) as 'sub',CTA_INVENTARIO as 'cuenta',Fecha_DUI as 'fecha',TC FROM Asiento_K  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND ORDEN = '".$orden."'  AND SUBCTA = '".$proveedor."' GROUP BY Codigo_B,ORDEN,CONTRA_CTA,CTA_INVENTARIO,Fecha_DUI,TC";
        
       return $datos = $this->conn->datos($sql);
	}

	function proveedores($query=false,$Codigo=false)
	{
		$cta = $this->buscar_cta_proveedor();
		$sql ="SELECT CI_RUC,Cliente,CP.Cta,CP.Codigo as 'Codigo'
		FROM Clientes C
		INNER JOIN Catalogo_CxCxP CP ON C.Codigo = CP.Codigo
		WHERE CP.Item = '".$_SESSION['INGRESO']['item']."' AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' AND LEN(Cliente)>1 AND CP.TC  ='P' AND Cta = '".$cta."'";
		if($query)
		{
			$sql.=" AND Cliente LIKE '%".$query."%'";
		}
		if($Codigo)
		{
			$sql.=" AND C.Codigo='".$Codigo."'";
		}
		$sql.=" ORDER BY C.Cliente OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";

		// print_r($sql); die();

		if($cta!=-1)
		{
		   
			return $this->conn->datos($sql);
       }else
       {
       	return -1;
       }
	}

	function buscar_cta_proveedor()
	{
		$cid = $this->conn;
		$sql = "SELECT * FROM Ctas_Proceso WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item='".$_SESSION['INGRESO']['item']."' AND Detalle = 'Cta_Proveedores'";
		
            $datos =$this->conn->datos($sql);
	   if(count($datos)>0)
	   {
	   	 return $datos[0]['Codigo'];
	   }else
	   {
	   	 return -1;
	   }

	}

	function buscar_cta_iva_inventario()
	{
		$cid = $this->conn;
		$sql = "SELECT * FROM Ctas_Proceso WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item='".$_SESSION['INGRESO']['item']."' AND Detalle = 'Cta_Iva_Inventario'";
		
		$datos = $this->conn->datos($sql);
	   if(count($datos)>0)
	   {
	   	 return $datos[0]['Codigo'];
	   }else
	   {
	   	 return -1;
	   }

	}

	function update($tabla,$datos,$campoWhere=false)
	{
			$resp = update_generico($datos,$tabla,$campoWhere);			
		  return $resp;
		
	}

	function ingresar_asiento_K($datos,$campoWhere=false)
	{
		// print_r($datos);die();
		if ($campoWhere) {
			$resp = update_generico($datos,'Asiento_K',$campoWhere);			
		  return $resp;
			
		}
	}


	function lineas_eli($parametros)
	{
		$cid = $this->conn;
		$sql = "DELETE FROM Asiento_K WHERE A_No ='".$parametros['lin']."' AND SUBCTA ='".$parametros['pro']."' AND ORDEN ='".$parametros['ord']."' AND CodigoU='".$_SESSION['INGRESO']['CodigoU']."'";
		
		return $this->conn->String_Sql($sql);
	}

	function iva_comprobante($orden,$proveedor)
	{
		$cid = $this->conn;
		$sql = "SELECT ROUND(SUM(IVA),4,0) as 'IVA' FROM Asiento_K  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND ORDEN = '".$orden."'  AND SUBCTA = '".$proveedor."' AND DH = '1' ";

		// print_r($sql);die();
		
	  $datos = $this->conn->datos($sql);
       return $datos;

	}

	function datos_asiento_debe($orden,$proveedor)
	{
      $cid = $this->conn;
      // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT ROUND(SUM(VALOR_TOTAL),2,0) as 'total',CONTRA_CTA as 'cuenta',SUBCTA,Fecha_DUI as 'fecha',TC FROM Asiento_K  WHERE Item = '".$_SESSION['INGRESO']['item']."' and ORDEN = '".$orden."' AND DH = '1' AND SUBCTA='".$proveedor."' GROUP BY Codigo_B,ORDEN,CONTRA_CTA,Fecha_DUI,TC,SUBCTA";
         
	  $datos = $this->conn->datos($sql);
       return $datos;
	}

	function cargar_pedidos($orden,$proveedor)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT * FROM Asiento_K WHERE ORDEN = '".$orden."' AND SUBCTA='".$proveedor."'  AND DH='1' ORDER BY A_No DESC";
       
	  $datos = $this->conn->datos($sql);
       return $datos;
	}

	function datos_asiento_SC($orden,$proveedor)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT SUM(VALOR_TOTAL) as 'total',CONTRA_CTA,SUBCTA,Fecha_DUI,TC 
     FROM Asiento_K  
     WHERE Item = '".$_SESSION['INGRESO']['item']."' 
     AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
     AND ORDEN = '".$orden."' 
     AND SUBCTA='".$proveedor."' AND 
     DH='1' 
     GROUP BY CONTRA_CTA,Fecha_DUI,TC,SUBCTA";
       

		$datos = $this->conn->datos($sql);
       return $datos;
	}

	function eliminar_aiseto_K($orden,$proveedor)
	{
		 $cid=$this->conn;
		$sql = "DELETE Asiento_K WHERE Item='".$_SESSION['INGRESO']['item']."' AND CodigoU='".$_SESSION['INGRESO']['CodigoU']."' AND DH='1' AND ORDEN ='".$orden."' AND SUBCTA='".$proveedor."' ";
		

		return $this->conn->String_Sql($sql);

	}

	function eliminar_aiseto()
	{
		 $cid=$this->conn;
		$sql = "DELETE Asiento WHERE Item='".$_SESSION['INGRESO']['item']."' AND CodigoU='".$_SESSION['INGRESO']['Id']."' AND T_No ='".$_SESSION['INGRESO']['modulo_']."'";
		
		return $this->conn->String_Sql($sql);

	}

	function eliminar_aiseto_sc($orden)
	{
		 $cid=$this->conn;
		$sql = "DELETE Asiento_SC WHERE Item='".$_SESSION['INGRESO']['item']."' AND CodigoU='".$_SESSION['INGRESO']['CodigoU']."' AND Factura ='".$orden."' ";
		
	return $this->conn->String_Sql($sql);

	}

	function cuentas_asignar($tipo,$query = false)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     //tipo = a tipo de cuenta 1 activos 2 pasivo 3 patrimonio 4 ingreso 5 egresos
     
     $sql = "SELECT Codigo,Codigo+'- '+Cuenta as 'Cuenta'
     FROM Catalogo_Cuentas
     WHERE Item ='".$_SESSION['INGRESO']['item']."'
     AND Periodo ='".$_SESSION['INGRESO']['periodo']."'
     AND TC = 'RP'
     AND DG ='D'";
     if($query)
     {
     	$sql.=" AND Cuenta like '%".$query."%' ";
     }
     $sql.="AND SUBSTRING(codigo,1,1) in (".$tipo.")
     ORDER BY Codigo,Cuenta  OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";

        
      $datos = $this->conn->datos($sql);
       return $datos;
	}

	function buscar_ultimo($cta)
	{
		// print_r($_SESSION);die();
       $cid = $this->conn;
		$sql="SELECT Codigo_Inv FROM Catalogo_Productos WHERE Codigo_Inv like '".$cta.".%' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'  AND TC='P' ORDER BY ID DESC";  
		

		$datos = $this->conn->datos($sql);
       return $datos;
	}

	function buscar_cod_existente($Codigo_Inv)
	{
		// print_r($_SESSION);die();
       $cid = $this->conn;
		$sql="SELECT Codigo_Inv FROM Catalogo_Productos WHERE Codigo_Inv  = '".$Codigo_Inv."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'  AND TC='P' ";  
		
		$datos = $this->conn->datos($sql);
       return $datos;
	}


	function familia_con_productos($codigo)
	{
       $cid = $this->conn;
		$sql="SELECT COUNT(ID) as 'cant' FROM Catalogo_Productos  WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' AND item='".$_SESSION['INGRESO']['item']."' AND TC='P' AND INV='1' AND LEN(Cta_Inventario)>3 AND LEN(Cta_Costo_Venta)>3 AND  T ='N' AND Codigo_Inv like '".$codigo.".%'";
		
      $datos = $this->conn->datos($sql);
       return $datos;

	}

	function misma_fecha($orden,$Codprove)
	{

       $cid = $this->conn;
		$sql = "SELECT DISTINCT Fecha_DUI FROM Asiento_K WHERE CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' AND Item = '".$_SESSION['INGRESO']['item']."' AND DH = '1'  AND ORDEN ='".$orden."'  AND SUBCTA ='".$Codprove."'"; 
		
	  $datos = $this->conn->datos($sql);

	   if(count($datos)>1)
	   {
	   	  return -1;
	   }else
	   {
	   	 return 1;
	   }


	}

	function clientes_all($query=false,$codigo = false,$abreviado=false,$ruc=false,$ci=false)
	{
		$cid = $this->conn;
		$sql="SELECT  *
		FROM Clientes C
		WHERE  LEN(Cliente)>1  ";
		if($ruc)
		{
			$sql.= " AND C.CI_RUC like '".$ruc."%'";
		}
		if($query)
		{
			$sql.="AND Cliente LIKE '%".$query."%'";
		}
		if($codigo)
		{
			$sql.="AND Codigo ='".$codigo."'";
		}
		if($abreviado)
		{
			$sql.="AND Cod_Ejec LIKE'%".$abreviado."%'";
		}
		if($ci)
		{
			$sql.="AND ID = '".$ci."'";
		}
			$sql.= "ORDER BY C.Cliente OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";
		
		// print_r($sql);die();
	 $datos = $this->conn->datos($sql);
       return $datos;
	}


	function catalogo_Cxcxp($Codigo)
	{
		$cid = $this->conn;
		$sql="SELECT * FROM Catalogo_CxCxP WHERE Codigo = '".$Codigo."' AND TC='P' AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
		
	   $datos = $this->conn->datos($sql);
       return $datos;

	}
	function eliminar_articulos($id)
	{
		$sql = "DELETE  FROM Catalogo_Productos WHERE ID='".$id."'";
		return $this->conn->String_Sql($sql);
	}

	function articulos($id)
	{
		$sql = "SELECT *
			FROM Catalogo_Productos CP 
			WHERE CP.Item = '".$_SESSION['INGRESO']['item']."'
			AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND CP.ID = '".$id."'";

			// print_r($sql);die();		       
		   $datos = $this->conn->datos($sql);
       return $datos;
	}

	function buscar_si_existe($codigo)
	{
		$sql="SELECT Codigo FROM Clientes WHERE Codigo ='".$codigo."'
					union
					SELECT Codigo FROM Clientes_Facturacion WHERE Codigo ='".$codigo."'
					union
					SELECT Codigo FROM Clientes_Matriculas WHERE Codigo ='".$codigo."'
					union
					SELECT Codigo FROM Trans_SubCtas WHERE Codigo ='".$codigo."'
					union
					SELECT Codigo_C FROM Transacciones WHERE Codigo_C ='".$codigo."'
					union
					SELECT CodigoC FROM Facturas WHERE CodigoC ='".$codigo."'
					union
					SELECT CodigoC FROM Trans_Abonos WHERE CodigoC ='".$codigo."'
					union
					SELECT IdProv FROM Trans_Air WHERE IdProv ='".$codigo."'
					union
					SELECT IdProv FROM Trans_Compras WHERE IdProv ='".$codigo."'
					union
					SELECT Codigo_P FROM Trans_Kardex WHERE Codigo_P ='".$codigo."'";
					return $this->conn->datos($sql);
	}

	function eliminar_proveedor($id)
	{
		$sql = "DELETE  FROM Clientes WHERE ID='".$id."'";
		return $this->conn->String_Sql($sql);
	}


}