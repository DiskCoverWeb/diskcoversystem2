<?php 

require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");
/**
 * 
 */
class lista_comprasM
{
	private $conn ;
	
	function __construct()
	{
  			$this->conn = new db();
	}

	function pedidos_compra_contratista($orden=false,$id=false,$fecha=false,$contratista=false)
	{
		$sql = "SELECT  TP.Fecha,Orden_No,SUM(Total) as Total,Cliente
				FROM Trans_Pedidos TP
				inner Join Clientes C on TP.CodigoU = C.Codigo
				WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND TC = 'B' 
				AND Item='".$_SESSION['INGRESO']['item']."' ";
				if($orden)
				{
					$sql.=" AND Orden_No = '".$orden."' ";
				}
				if($fecha)
				{
					$sql.=" AND TP.Fecha = '".$fecha."' ";
				}	
				if($contratista)
				{
					$sql.=" AND Cliente like '%".$contratista."%' ";
				}		

				$sql.=" Group by TP.Fecha,Orden_No,Cliente ORDER BY FECHA";
				// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function pedidos_compra_solicitados($orden=false,$id=false,$fecha=false,$contratista=false)
	{
		$sql = "SELECT  TP.Fecha,TP.Fecha_Ent,Orden_No,SUM(Total) as Total,Cliente
				FROM Trans_Pedidos TP
				inner Join Clientes C on TP.CodigoU = C.Codigo
				WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND TC = 'B' 
				AND Item='".$_SESSION['INGRESO']['item']."' ";
				if($orden)
				{
					$sql.=" AND Orden_No = '".$orden."' ";
				}
				if($fecha)
				{
					$sql.=" AND TP.Fecha = '".$fecha."' ";
				}	
				if($contratista)
				{
					$sql.=" AND Cliente like '%".$contratista."%' ";
				}		

				$sql.=" Group by TP.Fecha,TP.Fecha_Ent,Orden_No,Cliente";
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function lineas_compras_solicitados($orden=false,$id=false,$codigoC=false)
	{
		$sql = "SELECT TP.Periodo, TP.Fecha, Codigo_Inv, Hora, Producto, Cantidad, Precio, Total, Total_IVA, No_Hab, Cta_Venta, TP.Item, TP.CodigoU, Orden_No, Cta_Venta_0, TC, Factura, Autorizacion, Serie, Codigo_Sup, CodigoC, Opc1, Opc2, Opc3, TP.Estado, HABIT, TP.X, TP.ID, Fecha_Ent, CodMarca, Comentario,Marca,C.Cliente as 'proveedor',Total_Original,Costo_Original 
		FROM Trans_Pedidos TP
		inner join Catalogo_Marcas CM on TP.CodMarca = CM.CodMar
		inner join Clientes C on TP.CodigoC = C.Codigo

		WHERE  CM.Item = TP.Item
		AND CM.Periodo = TP.Periodo
		AND TP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND TC = 'B' 
		AND TP.Item='".$_SESSION['INGRESO']['item']."' ";
		if($orden)
		{
			$sql.=" AND Orden_No = '".$orden."' ";
		}
				
		if($id)
		{
			$sql.=" AND TP.ID = '".$id."' ";
		}

		if($codigoC)
		{
			$sql.=" AND TP.CodigoC = '".$codigoC."' ";
		}

		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function lineas_compras_solicitados_proveedores($orden=false,$id=false,$codigoC=false)
	{
		$sql = "SELECT CodigoC,Cliente,SUM(Total_Original) as Total
		FROM Trans_Pedidos TP
		inner join Clientes C on TP.CodigoC = C.Codigo
		WHERE TP.Periodo =  '".$_SESSION['INGRESO']['periodo']."'
		AND TC = 'B' 
		AND TP.Item='".$_SESSION['INGRESO']['item']."' ";
		if($orden)
		{
			$sql.=" AND Orden_No = '".$orden."' ";
		}
				
		if($id)
		{
			$sql.=" AND TP.ID = '".$id."' ";
		}

		if($codigoC)
		{
			$sql.=" AND TP.CodigoC = '".$codigoC."' ";
		}

		$sql.=" Group by CodigoC,Cliente";

		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function buscar_familia($query=false,$pag=false)
	{
		if($pag==false)
		{
			$pag = 0;
		}

		$cid = $this->conn;
		$sql = "SELECT ID,Codigo_Inv,Producto,TC,Minimo,Maximo,Cta_Inventario,Unidad,Ubicacion,IVA,Reg_Sanitario 
		FROM Catalogo_Productos 
		 WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		 AND item='".$_SESSION['INGRESO']['item']."' 
		 AND TC='I' ";
		if($query) 
		{
			$sql.=" AND Codigo_Inv = '".$query."'";
		}
		$sql.=" ORDER BY ID OFFSET ".$pag." ROWS FETCH NEXT 25 ROWS ONLY;";

		// print_r($sql);
		// die();
		
		$datos = $this->conn->datos($sql);
       return $datos;
	}

	function pedidos_compra_x_proveedor($orden=false,$id=false,$fecha=false,$contratista=false)
	{
		$sql = "SELECT  TP.Fecha,TP.Fecha_Ent,Orden_No,SUM(Total) as Total,C.Cliente,C2.Cliente as 'proveedor',TP.CodigoC
				FROM Trans_Pedidos TP
				inner Join Clientes C on TP.CodigoU = C.Codigo
				inner Join Clientes C2 on TP.CodigoC = C2.Codigo
				WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND TC = 'B' 
				AND Item='".$_SESSION['INGRESO']['item']."' ";
				if($orden)
				{
					$sql.=" AND Orden_No = '".$orden."' ";
				}
				if($fecha)
				{
					$sql.=" AND TP.Fecha = '".$fecha."' ";
				}	
				if($contratista)
				{
					$sql.=" AND Cliente like '%".$contratista."%' ";
				}		

				$sql.=" Group by TP.Fecha,TP.Fecha_Ent,Orden_No,C.Cliente,C2.Cliente,TP.CodigoC ";

				// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       	return $datos;
	}

	function catalogo_CxCxP($codigo=false)
	{

		$sql = "SELECT ".Full_Fields("Catalogo_CxCxP")."
				FROM Catalogo_CxCxP
				WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
				AND Item='".$_SESSION['INGRESO']['item']."' 
				AND (Codigo = '".$codigo."')";
		$datos = $this->conn->datos($sql);
       	return $datos;
	}



	function generar_asientos_SC($parametros)
	{
		// $cid = $this->conn;
		$serie = '001001';
		if(isset($parametros['serie']))
		{
			$serie = $parametros['serie'];
		}
		if($parametros['t']=='P' OR $parametros['t']=='C')
		{
			$sql=" SELECT codigo FROM clientes WHERE CI_RUC='".$parametros['sub']."' ";
			$row = $this->conn->datos($sql);			
			// print_r($row);die();
			if(count($row)>0)
			{
				$cod = $row[0]['codigo'];
			}else
			{
				$cod=$parametros['sub'];
			}
			$row_count=0;
			$i=0;
			$Result = array();
			
		}
		else
		{
			//echo ' nnnn ';
			$cod=$parametros['sub'];
		}
		//verificamos valor
		$SC_No=0;
		$sql=" SELECT MAX(SC_No) AS Expr1 FROM  Asiento_SC 
		where CodigoU ='".$_SESSION['INGRESO']['CodigoU']."' 
		AND item='".$_SESSION['INGRESO']['item']."'";
		$row = $this->conn->datos($sql);
		
		$row_count=0;
		$i=0;
		$Result = array();
		if(count($row)>0)
		{
			$SC_No = $row[0]['Expr1'];
		}
		
		if($SC_No==null)
		{
			$SC_No=1;
		}
		else
		{
			$SC_No++;
		}
		$fecha_actual=$parametros['fecha_sc'];
		if($parametros['fac2']==0)
		{
			$ot = explode("-",$fecha_actual);
			$fact2=$ot[0].$ot[1].$ot[2];
			
		}
		else
		{
			$fact2=$parametros['fac2'];
			
		}
		if($parametros['mes']==0)
		{

			SetAdoAddNew("Asiento_SC");
	        SetAdoFields("Codigo",$cod);
	        SetAdoFields("Beneficiario",substr($parametros['sub2'],0,50));
	        SetAdoFields("Factura",$fact2);
	        SetAdoFields("Serie",$serie);
	        SetAdoFields("Prima",'0');
	        SetAdoFields("DH",$parametros['tic']);
	        SetAdoFields("Valor",$parametros['valorn']);
	        SetAdoFields("Valor_ME",0);
	        SetAdoFields("Detalle_SubCta",$parametros['Trans']);
	        SetAdoFields("FECHA_V",$fecha_actual);
	        SetAdoFields("TC",$parametros['t']);
	        SetAdoFields("Cta",$parametros['co']);
	        SetAdoFields("TM",$parametros['moneda']);
	        SetAdoFields("T_No",$parametros['T_N']);
	        SetAdoFields("SC_No",$SC_No);
	        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
	        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
			SetAdoUpdate();

		// 	$sql="INSERT INTO Asiento_SC(Codigo ,Beneficiario,Factura ,Prima,DH,Valor,Valor_ME
           // ,Detalle_SubCta,FECHA_V,TC,Cta,TM,T_No,SC_No
           // ,Fecha_D ,Fecha_H,Bloquear,Item,CodigoU)
		// 	VALUES
           // ('".$cod."'
           // ,'".$parametros['sub2']."'
           // ,'".$fact2."'
           // ,0
           // ,'".$parametros['tic']."'
           // ,".$parametros['valorn']."
           // ,0
           // ,'".$parametros['Trans']."'
           // ,'".$fecha_actual."'
           // ,'".$parametros['t']."'
           // ,'".$parametros['co']."'
           // ,".$parametros['moneda']."
           // ,".$parametros['T_N']."
           // ,".$SC_No."
           // ,null
           // ,null
           // ,0
           // ,'".$_SESSION['INGRESO']['item']."'
           // ,'".$_SESSION['INGRESO']['CodigoU']."')";

           // // print_r($sql);die();

           // $this->conn->String_Sql($sql);
		 
		}
		else
		{
			$sql="INSERT INTO Asiento_SC(Codigo ,Beneficiario,Factura ,Prima,DH,Valor,Valor_ME
			,Detalle_SubCta,FECHA_V,TC,Cta,TM,T_No,SC_No
			,Fecha_D ,Fecha_H,Bloquear,Item,CodigoU)
			VALUES
			";
			$dia=0;
			for ($i=0;$i<$parametros['mes'];$i++)
			{
				$sql=$sql."('".$cod."'
			   ,'".$parametros['sub2']."'
			   ,'".$fact2."'
			   ,0
			   ,'".$parametros['tic']."'
			   ,".$parametros['valorn']."
			   ,0
			   ,'".$parametros['Trans']."'
			   ,'".$fecha_actual."'
			   ,'".$parametros['t']."'
			   ,'".$parametros['co']."'
			   ,".$parametros['moneda']."
			   ,".$parametros['T_N']."
			   ,".$SC_No."
			   ,null
			   ,null
			   ,0
			   ,'".$_SESSION['INGRESO']['item']."'
			   ,'".$_SESSION['INGRESO']['CodigoU']."'),";
			   $SC_No++;
			   $ot = explode("-",$fecha_actual);
			   if($ot[1]=='01')
			   {
				    if($ot[2]>=28)
				    {
					   $dia=$ot[2];
					    $year=esBisiesto_ajax($ot[0]);
						if($year==1)
						{
							$fecha_actual = date("Y-m-d",strtotime($ot[0].'-02-29')); 
							if($parametros['fac2']==0)
							{
								$fact2 = date("Ymd",strtotime($ot[0].'0229')); 
							}
							//$fact2 = $ot[0].'0229'; 
						}
						else
						{
							$fecha_actual = date("Y-m-d",strtotime($ot[0].'-02-28')); 
							 if($parametros['fac2']==0)
							{
								$fact2 = date("Ymd",strtotime($ot[0].'0228')); 
							}
						}
				    }
					else
					{
						$fecha_actual = date("Y-m-d",strtotime($fecha_actual."+ 1 month")); 
					   if($parametros['fac2']==0)
						{
							$fact2 = date("Ymd",strtotime($fact2."+ 1 month")); 
						}
					}
				   
			   }
			   else
			   {
						
						if( $dia>=28)
						{
							$ot = explode("-",$fecha_actual);
							if($ot[1]=='02')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-03-31')); 
								if($parametros['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'0331')); 
								}
							}
							if($ot[1]=='03')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-04-30')); 
								if($parametros['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'0430')); 
								}
							}
							if($ot[1]=='04')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-05-31')); 
								if($parametros['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'0531')); 
								}
							}
							if($ot[1]=='05')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-06-30')); 
								if($parametros['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'0630')); 
								}
							}
							if($ot[1]=='06')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-07-31')); 
								if($parametros['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'0731')); 
								}
							}
							if($ot[1]=='07')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-08-31')); 
								if($parametros['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'0831')); 
								}
							}
							if($ot[1]=='08')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-09-30')); 
								if($parametros['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'0930')); 
								}
							}
							if($ot[1]=='09')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-10-31')); 
								if($parametros['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'1031')); 
								}
							}
							if($ot[1]=='10')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-11-30')); 
								if($parametros['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'1130')); 
								}
							}
							if($ot[1]=='11')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-12-31')); 
								if($parametros['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'1231')); 
								}
							}
						}
						else
						{
							$fecha_actual = date("Y-m-d",strtotime($fecha_actual."+ 1 month")); 
							if($parametros['fac2']==0)
							{
								$fact2 = date("Ymd",strtotime($fact2."+ 1 month")); 
							}
						}
					
			    }
			}
			//reemplazo una parte de la cadena por otra
			$longitud_cad = strlen($sql); 
			$cam2 = substr_replace($sql,"",$longitud_cad-1,1); 

            $this->conn->String_Sql($cam2);
			
			//echo $cam2;
		}
			$sql="SELECT Codigo, Beneficiario, Factura, Prima, DH, Valor, Valor_ME, Detalle_SubCta,T_No, SC_No,Item, CodigoU
			FROM Asiento_SC
			WHERE 
				Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			$stmt = sqlsrv_query( $this->conn->conexion(), $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				return 1;
			}
	
	}

	function datos_asiento_debe_trans($orden,$codigo)
	{
      	// 'LISTA DE CODIGO DE ANEXOS
	     $sql = "SELECT SUM(Total_Original) as 'total','' as 'cuenta',CodigoC as 'SUBCTA',Fecha as 'fecha',TC 
	     FROM Trans_Pedidos  
	     WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	     AND Orden_No = '".$orden."' 
	     AND CodigoC = '".$codigo."' ";     
	     $sql.=" GROUP BY Orden_No,CodigoC,Fecha,TC";
	          // print_r($sql);die();
	     return $this->conn->datos($sql);
	}

	function datos_asiento_haber_trans($orden,$codigo)
	{
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT SUM(Total_Original) as 'total',Cta_Venta_0 as 'cuenta',Fecha as 'fecha',TC 
             FROM Trans_Pedidos  
             WHERE Item = '".$_SESSION['INGRESO']['item']."' 
             AND Orden_No = '".$orden."'
             AND CodigoC = '".$codigo."' ";              
        $sql.=" GROUP BY Orden_No,Cta_Venta_0,Fecha,TC";
          // print_r($sql);die();
        return $this->conn->datos($sql);
  
	}

	function catalogo_cuentas($cuenta)
	{
     $sql = "SELECT * FROM Catalogo_Cuentas  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo = '".$cuenta."'";
          // print_r($sql);
     return $this->conn->datos($sql);
	}

	function catalogo_cuentas_cta_inv($cuenta)
	{
	     $sql = "SELECT ".Full_Fields("Catalogo_Productos")." 
	     FROM Catalogo_Productos 
	     WHERE Codigo_Inv = '".$cuenta."' 
	     AND Item = '".$_SESSION['INGRESO']['item']."' 
	     AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
	          // print_r($sql);
	     return $this->conn->datos($sql);
	}
	function datos_comprobante($t_no=99)
	{
		// $cid = $this->conn;
		$sql="SELECT  ".Full_Fields("Asiento")."  
		FROM Asiento 
		WHERE CodigoU='".$_SESSION['INGRESO']['CodigoU']."' 
		AND Item='".$_SESSION['INGRESO']['item']."' 
		AND T_No = '".$t_no."'";
		// print_r($sql);die();
		return $this->conn->datos($sql);
	
	}

	function update_asiento_K($orden,$CodigoPrv,$numero)
	{
		$sql = "UPDATE Trans_Pedidos  SET Fecha_Ent = '".date('Y-m-d')."' ,TC='N',Numero = '".$numero."'
				WHERE Item = '".$_SESSION['INGRESO']['item']."' 
				AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
	     		AND Orden_No = '".$orden."' 
	     		AND CodigoC = '".$CodigoPrv."' 
	     		AND TC = 'B'";  
	    return $this->conn->String_Sql($sql);

	}
	function eliminar_asiento($T_no)
	{
		 $cid=$this->conn;
		$sql = "DELETE Asiento 
		WHERE Item='".$_SESSION['INGRESO']['item']."' 
		AND CodigoU='".$_SESSION['INGRESO']['CodigoU']."' 
		AND T_No ='".$T_no."'";
		// print_r($sql);die();
		
		return $this->conn->String_Sql($sql);

	}

	function eliminar_asiento_sc($orden)
	{
		 $cid=$this->conn;
		$sql = "DELETE Asiento_SC WHERE Item='".$_SESSION['INGRESO']['item']."' AND CodigoU='".$_SESSION['INGRESO']['CodigoU']."' AND Factura ='".$orden."' ";
		return $this->conn->String_Sql($sql);

	}


	function numeroFactura($fecha)
	{
		$sql = "SELECT count(*) as num
				FROM Trans_SubCtas
				WHERE Item = '".$_SESSION['INGRESO']['item']."' 
				AND Periodo='".$_SESSION['INGRESO']['periodo']."' 
				AND Fecha = '".$fecha."'";
		return $this->conn->datos($sql);
	}


}


?>