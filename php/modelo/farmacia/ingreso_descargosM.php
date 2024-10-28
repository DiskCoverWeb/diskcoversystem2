<?php 
date_default_timezone_set('America/Guayaquil');
if(!class_exists('variables_g'))
{
	include(dirname(__DIR__,2).'/db/variables_globales.php');//
    include(dirname(__DIR__,2).'/funciones/funciones.php');
}
@session_start(); 

/**
 * 
 */
class ingreso_descargosM
{
	
	private $conn1 ;
	function __construct()
	{
	   $this->conn1 = new db(); 
	}


	function buscar_producto($query,$tipo)
		{
			$sql = "SELECT Codigo_Inv,Producto,TC,Valor_Total,Unidad,Stock_Actual,Cta_Inventario,Cta_Costo_Venta,IVA,Unidad,Maximo,Minimo 
			FROM Catalogo_Productos 
			WHERE T<>'I' 
			AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND Item = '".$_SESSION['INGRESO']['item']."' 
			AND LEN(Cta_Inventario)>3 
			AND LEN(Cta_Costo_Venta)>3 AND ";
			if($tipo =='desc')
			{
			 $sql.="Producto LIKE '%".$query."%'";
			}else
			{
				$sql.=" Codigo_Inv LIKE '%".$query."%'";
			}
			$sql.=' ORDER BY ID OFFSET 0 ROWS FETCH NEXT 10 ROWS ONLY;';
     // print_r($sql);die();

			return $this->conn1->datos($sql);
   
	}
	function costo_venta($codigo_inv,$Codbod='01')
	{
		$sql = "SELECT  (SUM(Entrada)-SUM(Salida)) as 'Existencia'
		FROM Trans_Kardex
		WHERE Fecha <= '".date('Y-m-d')."'
		AND Codigo_Inv = '".$codigo_inv."'
		AND Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND T <> 'A'
        AND Numero <> 0
		AND CodBodega ='".$Codbod."' ";
		// print_r($sql);die();

		return $this->conn1->datos($sql);
		

	}


	function costo_producto($Codigo_Inv)
	{

		$sql = "SELECT TOP 1 Codigo_Inv,Costo,Valor_Unitario,Existencia,Total,T 
               FROM Trans_Kardex 
               WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
               AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
               AND Fecha <= '".date('Y-m-d')."' 
               AND Codigo_Inv = '".$Codigo_Inv."' 
               AND T <> 'A' 
               AND Numero <> 0
               ORDER BY Fecha DESC,TP DESC, Numero DESC,ID DESC ";
               // print_r($sql);die();

               return $this->conn1->datos($sql);
  

	}
	function ingresar_asiento_K($datos,$campoWhere=false)
	{
		// print_r($datos);die();
		if ($campoWhere) {
			$resp = update_generico($datos,'Asiento_K',$campoWhere);			
		  return $resp;
			
		}
	}



	//------------------viene de trasnkardex--------------------

	function cargar_pedidos_trans($orden,$SUBCTA,$fecha=false,$diferente=false,$paciente=false)
	{
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT T.*,P.Producto,P.Unidad 
     FROM Trans_Kardex  T ,Catalogo_Productos P
     WHERE Orden_No = '".$orden."' 
     AND T.CodigoL = '".$SUBCTA."'
     AND T.Codigo_P = '".$paciente."'
     AND Numero =0
     AND T.Item = P.Item
     AND T.Periodo = P.Periodo
	 AND T.Codigo_Inv = P.Codigo_Inv";
     if($fecha)
     {
     	$sql.=" AND T.Fecha = '".$fecha."'";
     }
     if($diferente)
     {
     	$diferente = explode(',',$diferente);
     	foreach ($diferente as $key => $value) {
     		$sql.=" AND Codigo_Inv <>'".$value."' ";
     	}
     }  
     $sql.=" ORDER BY T.ID DESC";
     // print_r($sql);die();

     return $this->conn1->datos($sql);
       
	}


	// function cargar_pedidos($orden,$SUBCTA,$fecha=false,$diferente=false)
	// {
 //    // 'LISTA DE CODIGO DE ANEXOS
 //     $sql = "SELECT * 
 //     FROM Asiento_K 
 //     WHERE ORDEN = '".$orden."' 
 //     AND SUBCTA = '".$SUBCTA."'";
 //     if($fecha)
 //     {
 //     	$sql.=" AND Fecha_Fab = '".$fecha."'";
 //     }
 //     if($diferente)
 //     {
 //     	$diferente = explode(',',$diferente);
 //     	foreach ($diferente as $key => $value) {
 //     		$sql.=" AND Codigo_Inv <>'".$value."' ";
 //     	}
 //     }  
 //     $sql.=" ORDER BY A_No DESC";

 //     return $this->conn1->datos($sql);
       
	// }

	function cargar_pedidos_fecha_trans($orden,$SUBCTA)
	{
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT DISTINCT Fecha ,CodigoL as 'SUBCTA',Orden_No as 'ORDEN'  FROM Trans_Kardex WHERE Orden_No = '".$orden."' AND CodigoL = '".$SUBCTA."' AND Numero = 0 ORDER BY Fecha DESC";
     // print_r($sql);die();
     $datos = $this->conn1->datos($sql);
       
       return $datos;
	}

	// function cargar_pedidos_fecha($orden,$SUBCTA)
	// {
 //    // 'LISTA DE CODIGO DE ANEXOS
 //     $sql = "SELECT DISTINCT Fecha_Fab ,SUBCTA,ORDEN  FROM Asiento_K WHERE ORDEN = '".$orden."' AND SUBCTA = '".$SUBCTA."' ORDER BY Fecha_Fab DESC";
 //     $datos = $this->conn1->datos($sql);
       
 //       return $datos;
	// }

	//------------------------fin ----------------------------------


	function buscar_cc($query=false)
	{
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT Codigo,Cuenta,TC 
		     FROM Catalogo_Cuentas 
		     WHERE Item='".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND SUBSTRING(Codigo,1,1) ='5' AND Codigo < '6' AND TC = 'RP' ";
		if($query)
		{
		    $sql.=" AND Cuenta LIKE '%".$query."%'";
		}
    $sql.=" ORDER BY Codigo";
        // print_r($sql);die();

     return $this->conn1->datos($sql);
       
	}

	function asignar_num_pedido_clinica()
	{
		$sql="SELECT * FROM Codigos 
		WHERE Item='".$_SESSION['INGRESO']['item']."' and 
		Periodo='".$_SESSION['INGRESO']['periodo']."' and Concepto='PEDIDO_CLINICA'";
		// print_r($sql);die();
		$datos = $this->conn1->datos($sql);
		
		if(count($datos)==0)
		{
			$res = $this->CREAR_COD_PEDIDO_CLINICA();
			return $res;
		}else
		{

		   return $datos[0]['Numero'];
		}
	}

	function asignar_num_pedido_clinica_bod()
	{
		$sql="SELECT * FROM Codigos 
		WHERE Item='".$_SESSION['INGRESO']['item']."' and 
		Periodo='".$_SESSION['INGRESO']['periodo']."' and Concepto='PEDIDO_BODEGA'";
		// print_r($sql);die();
		$datos = $this->conn1->datos($sql);
		
		if(count($datos)==0)
		{
			$res = $this->CREAR_COD_PEDIDO_CLINICA_BOD();
			return $res;
		}else
		{
		   return $datos[0]['Numero'];
		}
	}

	function ACTUALIZAR_COD_PEDIDO_CLINICA($datos)
	{
		$campoWhere[0]['campo']='Concepto';
		$campoWhere[0]['valor']='PEDIDO_CLINICA';

		$campoWhere[1]['campo']='Item';
		$campoWhere[1]['valor']=$_SESSION['INGRESO']['item'];

		$campoWhere[2]['campo']='Periodo';
		$campoWhere[2]['valor']=$_SESSION['INGRESO']['periodo'];

		  return update_generico($datos,'Codigos',$campoWhere);
	}

	function ACTUALIZAR_COD_PEDIDO_CLINICA_BOD($datos)
	{
		$campoWhere[0]['campo']='Concepto';
		$campoWhere[0]['valor']='PEDIDO_BODEGA';

		$campoWhere[1]['campo']='Item';
		$campoWhere[1]['valor']=$_SESSION['INGRESO']['item'];

		$campoWhere[2]['campo']='Periodo';
		$campoWhere[2]['valor']=$_SESSION['INGRESO']['periodo'];

		  return update_generico($datos,'Codigos',$campoWhere);
	}

    function COD_PEDIDO_CLINICA_EXISTENTE_TRANS($num_his)
	{

		$sql="SELECT * FROM Trans_Kardex 
		WHERE Orden_No='".$num_his."'
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND Item = '".$_SESSION['INGRESO']['item']."' 
		AND Numero = ''";
		// print_r($sql);die();
		$datos = $this->conn1->datos($sql);
		
		if(count($datos)==0)
		{
			return $num_his;
		}else
		{
		   return -1;
		}

	}

	//  function COD_PEDIDO_CLINICA_EXISTENTE($num_his)
	// {

	// 	$sql="SELECT * FROM Asiento_K WHERE ORDEN='".$num_his."'";
	// 	// print_r($sql);die();
	// 	$datos = $this->conn1->datos($sql);
		
	// 	if(count($datos)==0)
	// 	{
	// 		return $num_his;
	// 	}else
	// 	{
	// 	   return -1;
	// 	}

	// }

	function CREAR_COD_PEDIDO_CLINICA()
	{
		SetAdoAddNew("Codigos");
		SetAdoFields('Periodo','.');
		SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		SetAdoFields('Concepto','PEDIDO_CLINICA');
		SetAdoFields('Numero',1);
		return SetAdoUpdate();
	}

	function CREAR_COD_PEDIDO_CLINICA_BOD()
	{

		SetAdoAddNew("Codigos");
		SetAdoFields('Periodo','.');
		SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		SetAdoFields('Concepto','PEDIDO_BODEGA');
		SetAdoFields('Numero',1);

		return SetAdoUpdate();
	}

	function lineas_eli($parametros)
	{
		$sql = "DELETE FROM Trans_Kardex WHERE Orden_No='".$parametros['ped']."' and ID ='".$parametros['lin']."'";
		return $this->conn1->String_Sql($sql);
	}
	// function lineas_edi($datos,$where)
	// {
	// 	$resp = update_generico($datos,'Asiento_K',$where);
	// 	return $resp;
	// }
	function lineas_edi($datos,$where)
	{
		$resp = update_generico($datos,'Trans_Kardex',$where);
		return $resp;
	}

	function datos_asiento_haber_trans($orden,$fecha,$diferente=false)
	{
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT SUM(VALOR_TOTAL) as 'total',Cta_Inv as 'cuenta',Fecha as 'fecha',TC 
             FROM Trans_Kardex  
             WHERE Item = '".$_SESSION['INGRESO']['item']."' 
             AND Orden_No = '".$orden."'  
             AND Fecha = '".$fecha."'";
             if($diferente)
             	{
             		$diferente = explode(',',$diferente);
             		foreach ($diferente as $key => $value) {
             			$sql.=" AND Codigo_Inv <>'".$value."' ";
             			}
             	}   
        $sql.=" GROUP BY Orden_No,Cta_Inv,Fecha,TC";
          // print_r($sql);die();
        return $this->conn1->datos($sql);
  
	}

	//----------------------para asiento K--------------------------//

	// function datos_asiento_haber($orden,$fecha,$diferente=false)
	// {
 //    // 'LISTA DE CODIGO DE ANEXOS
 //     $sql = "SELECT SUM(VALOR_TOTAL) as 'total',CTA_INVENTARIO as 'cuenta',Fecha_Fab as 'fecha',TC 
 //             FROM Asiento_K  
 //             WHERE Item = '".$_SESSION['INGRESO']['item']."' 
 //             AND ORDEN = '".$orden."' 
 //             AND DH = '2' 
 //             AND Fecha_Fab = '".$fecha."'";
 //             if($diferente)
 //             	{
 //             		$diferente = explode(',',$diferente);
 //             		foreach ($diferente as $key => $value) {
 //             			$sql.=" AND Codigo_Inv <>'".$value."' ";
 //             			}
 //             	}   
 //        $sql.=" GROUP BY Codigo_B,ORDEN,CTA_INVENTARIO,Fecha_Fab,TC,SUBCTA";
 //          // print_r($sql);die();
 //        return $this->conn1->datos($sql);
  
	// }

	//---------------------------------------------------------//
	function datos_asiento_debe_trans($orden,$fecha,$diferente=false)
	{
      // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT SUM(VALOR_TOTAL) as 'total',Contra_Cta as 'cuenta',CodigoL as 'SUBCTA',Fecha as 'fecha',TC 
     FROM Trans_Kardex  
     WHERE Item = '".$_SESSION['INGRESO']['item']."' 
     AND Orden_No = '".$orden."' 
     AND Fecha = '".$fecha."'";
     if($diferente)
     {
     	$diferente = explode(',',$diferente);
     	foreach ($diferente as $key => $value) {
     		$sql.=" AND Codigo_Inv <>'".$value."' ";
     	}
     }   
     $sql.=" GROUP BY Orden_No,Contra_Cta,Fecha,TC,CodigoL";
          // print_r($sql);die();
     return $this->conn1->datos($sql);
  
	}

	//----------------------para asiento K--------------------------//

	// function datos_asiento_debe($orden,$fecha,$diferente=false)
	// {
 //      // 'LISTA DE CODIGO DE ANEXOS
 //     $sql = "SELECT SUM(VALOR_TOTAL) as 'total',CONTRA_CTA as 'cuenta',SUBCTA,Fecha_Fab as 'fecha',TC 
 //     FROM Asiento_K  
 //     WHERE Item = '".$_SESSION['INGRESO']['item']."' 
 //     AND DH = '2' and ORDEN = '".$orden."' 
 //     AND Fecha_Fab = '".$fecha."'";
 //     if($diferente)
 //     {
 //     	$diferente = explode(',',$diferente);
 //     	foreach ($diferente as $key => $value) {
 //     		$sql.=" AND Codigo_Inv <>'".$value."' ";
 //     	}
 //     }   
 //     $sql.=" GROUP BY Codigo_B,ORDEN,CONTRA_CTA,Fecha_Fab,TC,SUBCTA";
 //          // print_r($sql);die();
 //     return $this->conn1->datos($sql);
  
	// }

	//------------------------------------------------------------------//

	function ingresar_asientos($parametros)
	{//ingresar asiento 

		// print_r($parametros);
		$va = $parametros['va'];
		$dconcepto1 = $parametros['dconcepto1'];
		$codigo = $parametros['codigo'];
		$cuenta = $parametros['cuenta'];
		if(isset($parametros['t_no']))
		{
			$t_no = $parametros['t_no'];
		}
		else
		{
			$t_no = 1;
		}
		if(isset($parametros['efectivo_as']))
		{
			$efectivo_as = $parametros['efectivo_as'];
		}
		else
		{
			$efectivo_as = '';
		}
		if(isset($parametros['chq_as']))
		{
			$chq_as = $parametros['chq_as'];
		}
		else
		{
			$chq_as = '';
		}
		
		$moneda = $parametros['moneda'];
		$tipo_cue = $parametros['tipo_cue'];
		
		if($efectivo_as=='' or $efectivo_as==null)
		{
			$efectivo_as=$fecha;
		}
		if($chq_as=='' or $chq_as==null)
		{
			$chq_as='.';
		}
		$parcial = 0;
		if($moneda==2)
		{
			$cotizacion = $parametros['cotizacion'];
			$con = $parametros['con'];
			if($tipo_cue==1)
			{
				if($con=='/')
				{
					$debe=$va/$cotizacion;
				}
				else
				{
					$debe=$va*$cotizacion;
				}
				$parcial = $va;
				$haber=0;
			}
			if($tipo_cue==2)
			{
				if($con=='/')
				{
					$haber=$va/$cotizacion;
				}
				else
				{
					$haber=$va*$cotizacion;
				}
				$parcial = $va;
				$debe=0;
			}
		}
		else
		{
			if($tipo_cue==1)
			{
				$debe=$va;
				$haber=0;
			}
			if($tipo_cue==2)
			{
				$debe=0;
				$haber=$va;
			}
		}
		//verificar si ya existe en ese modulo ese registro
		$sql="SELECT CODIGO, CUENTA
		FROM Asiento
		WHERE (CODIGO = '".$codigo."') AND (Item = '".$_SESSION['INGRESO']['item']."') 
		AND (CodigoU = '".$_SESSION['INGRESO']['CodigoU']."') AND (DEBE = '".$va."') 
		AND T_No=".$_SESSION['INGRESO']['modulo_']." 
		ORDER BY A_No ASC ";
		$datos = $this->conn1->datos($sql);
		//print_r($sql);die();
		
		//para contar registro
		$i=0;
		$i=count($datos);
		if($t_no == '60' || $t_no=='03')
		{
			$i=0;
		}
		//echo $i.' -- '.$sql;
		//seleccionamos el valor siguiente
		$sql="SELECT TOP 1 A_No FROM Asiento
		WHERE (Item = '".$_SESSION['INGRESO']['item']."')
		ORDER BY A_No DESC";
		$A_No=0;
		$datos = $this->conn1->datos($sql);
		// $stmt = sqlsrv_query( $cid, $sql);
		if(count($datos)>0)  		
		{
			$ii=0;
			foreach ($datos as $key => $value) {
				$A_No = $value['A_No'];
				$ii++;
			}
			
			
			if($ii==0)
			{
				$A_No++;
			}
			else
			{
				$A_No++;
			}
		}
		//si no existe guardamos
		if($i==0)
		{
			
				$sql="INSERT INTO Asiento
				(CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE,EFECTIVIZAR,CODIGO_C,CODIGO_CC
				,ME,T_No,Item,CodigoU,A_No)
				VALUES
				('".$codigo."','".$cuenta."',".$parcial.",".$debe.",".$haber.",'".$chq_as."','".$dconcepto1."',
				'".$efectivo_as."','.','.',0,".$t_no.",'".$_SESSION['INGRESO']['item']."','".$_SESSION['INGRESO']['CodigoU']."',".$A_No.")";
			
				$this->conn1->String_Sql($sql);
		
				$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
				FROM Asiento
				WHERE 
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
					ORDER BY A_No ASC ";
				$stmt = sqlsrv_query( $this->conn1->conexion(), $sql);
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
		else
		{
			return 'ya existe';
		}
		
	}

	function catalogo_cuentas($cuenta)
	{

		 // $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT * FROM Catalogo_Cuentas  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo = '".$cuenta."'";
          // print_r($sql);
     return $this->conn1->datos($sql);
	}

	function catalogo_subcuentas($cuenta)
	{

		 // $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT * FROM Catalogo_SubCtas   WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo = '".$cuenta."'";
          // print_r($sql);die();
     return $this->conn1->datos($sql);
 
	}


	

	function generar_comprobantes($parametros)
	{
		$_SESSION['INGRESO']['modulo_']='99';
		if(isset($parametros['t_no']))
		{
			$_SESSION['INGRESO']['modulo_']=$parametros['t_no'];
		}
		// $cid = $this->conn;
		if(isset($parametros['cotizacion']))
		{
			if($parametros['cotizacion']=='' or $parametros['cotizacion']==null)
			{
				$parametros['cotizacion']=0;
			}
		}
		else
		{
			$parametros['cotizacion']=0;
		}
		$codigo_b='';
		//echo $_POST['ru'].'<br>';
		if($parametros['ru']=='000000000')
		{
			$codigo_b='.';
		}
		else
		{
			//buscamos codigo
			$sql=" 	SELECT Codigo
					FROM Clientes
					WHERE((CI_RUC = '".$parametros['ru']."')) ";
					// print_r($sql);die();
			
			$datos = $this->conn1->datos($sql);
			if(count($datos)>0)
			{
				$codigo_b=$datos[0]['Codigo'];
			}
			//caso en donde se necesite guardar el codigo de usuario como codigo beneficiario de comprobante
			if($codigo_b =='' or $codigo_b==null)
			{
				$codigo_b =$parametros['ru'];
			}
			//$codigo_b=$_POST['ru'];
		}
		//buscamos total
		if($parametros['tip']=='CE' or $parametros['tip']=='CI')
		{
			$sql="SELECT        SUM( DEBE) AS db, SUM(HABER) AS ha
			FROM            Asiento
			where T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."'  AND CUENTA 
			in (select Cuenta FROM  Catalogo_Cuentas 
			where Catalogo_Cuentas.Cuenta=Asiento.CUENTA AND (Catalogo_Cuentas.TC='CJ' OR Catalogo_Cuentas.TC='BA'))";
			
			// $stmt = sqlsrv_query( $cid, $sql);
			$totald=0;
			$totalh=0;
			
			$datos = $this->conn1->datos($sql);
			if(count($datos)>0)
			{
				$totald=$datos[0]['db'];
				$totalh=$datos[0]['ha'];
			}
			if($parametros['tip']=='CE')
			{
				$parametros['totalh']=$totalh;
			}
			if($parametros['tip']=='CI')
			{
				$parametros['totalh']=$totald;
			}
		}
		if($parametros['concepto']=='')
		{
			$parametros['concepto']='.';
		}
		$num_com = explode("-", $parametros['num_com']);
		//verificamos que no se coloque fecha erronea
		$ot = explode("-",$parametros['fecha1']);
		$num_com1 = explode(".", $num_com[0]);
		$parametros['fecha1']=trim($num_com1[1]).'-'.$ot[1].'-'.$ot[2];
		
		//echo $_POST['fecha1'];
		//die();
		
		$sql="INSERT INTO Comprobantes
           (Periodo ,Item,T ,TP,Numero ,Fecha ,Codigo_B,Presupuesto,Concepto,Cotizacion,Efectivo,Monto_Total
           ,CodigoU ,Autorizado,Si_Existe ,Hora,CEj,X)
		   VALUES
           ('".$_SESSION['INGRESO']['periodo']."'
           ,'".$_SESSION['INGRESO']['item']."'
           ,'N'
           ,'".$parametros['tip']."'
           ,".$num_com[1]."
           ,'".$parametros['fecha1']."'
           ,'".$codigo_b."'
           ,0
           ,'".$parametros['concepto']."'
           ,'".$parametros['cotizacion']."'
           ,0
           ,'".$parametros['totalh']."'
           ,'".$_SESSION['INGRESO']['CodigoU']."'
           ,'.'
           ,0
           ,'".date('h:i:s')."'
           ,'.'
           ,'.')";
		   
           $this->conn1->String_Sql($sql);

		   //consultamos transacciones
		   $sql="SELECT CODIGO,CUENTA,PARCIAL_ME  ,DEBE ,HABER ,CHEQ_DEP ,DETALLE ,EFECTIVIZAR,CODIGO_C,CODIGO_CC
				,ME,T_No,Item,CodigoU ,A_No,TC
				FROM Asiento
				WHERE 
					T_No='".$_SESSION['INGRESO']['modulo_']."' AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			
			$sql=$sql." ORDER BY A_No ";

			// print_r($sql);die();
			$datos = $this->conn1->datos($sql);
			if(count($datos)>0)
			{
				$i=0;
				$ii=0;
				$Result = array();
				$fecha_actual = date("Y-m-d"); 
				
				foreach ($datos as $key => $value) {
					$Result[$i]['CODIGO']=$value['CODIGO'];
					$Result[$i]['CHEQ_DEP']=$value['CHEQ_DEP'];
					$Result[$i]['DEBE']=$value['DEBE'];
					$Result[$i]['HABER']=$value['HABER'];
					$Result[$i]['PARCIAL_ME']=$value['PARCIAL_ME'];
					$Result[$i]['EFECTIVIZAR']=$value['EFECTIVIZAR']->format('Y-m-d');
					$Result[$i]['CODIGO_C']=$value['CODIGO_C'];
					
					$sql=" INSERT INTO Transacciones
				    (Periodo ,T,C ,Cta,Fecha,TP ,Numero,Cheq_Dep,Debe ,Haber,Saldo ,Parcial_ME ,Saldo_ME ,Fecha_Efec ,Item ,X ,Detalle
				    ,Codigo_C,Procesado,Pagar,C_Costo)
					 VALUES
				    ('".$_SESSION['INGRESO']['periodo']."'
				    ,'N'
				    ,0
				    ,'".$Result[$i]['CODIGO']."'
				    ,'".$parametros['fecha1']."'
				    ,'".$parametros['tip']."'
				    ,".$num_com[1]."
				    ,'".$Result[$i]['CHEQ_DEP']."'
				    ,".$Result[$i]['DEBE']."
				    ,".$Result[$i]['HABER']."
				    ,0
				    ,".$Result[$i]['PARCIAL_ME']."
				    ,0
				    ,'".$Result[$i]['EFECTIVIZAR']."'
				    ,'".$_SESSION['INGRESO']['item']."'
				    ,'.'
				    ,'.'
				    ,'".$Result[$i]['CODIGO_C']."'
				    ,0
				    ,0
				    ,'.');";
				   // echo $sql.'<br>';

           // print_r($sql);
					
					$this->conn1->String_Sql($sql);
					$i++;
				}
					
				
				$sql="SELECT  Codigo,Beneficiario,Factura,Prima,DH,Valor ,Valor_ME,Detalle_SubCta,FECHA_V ,TC,Cta,TM
				,T_No,SC_No,Fecha_D,Fecha_H,Bloquear,Item,CodigoU
				FROM Asiento_SC
				WHERE 
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";

					// print_r($sql);die();

				//echo $sql;
				$datos = $this->conn1->datos($sql);
				if(count($datos)>0)
				{
					$i=0;
					$Result = array();
					$fecha_actual = date("Y-m-d"); 
					foreach ($datos as $key => $value) {

					    $Result[$i]['TC']=$value['TC'];
						$Result[$i]['Cta']=$value['Cta'];
						$Result[$i]['FECHA_V']=$value['FECHA_V']->format('Y-m-d');
						$Result[$i]['Codigo']=$value['Codigo'];
						$Result[$i]['Factura']=$value['Factura'];
						$Result[$i]['Prima']=$value['Prima'];
						$Result[$i]['DH']=$value['DH'];
						if($Result[$i]['DH']==1)
						{
							$Result[$i]['DEBITO']=$value['Valor'];
							$Result[$i]['HABER']=0;
						}
						if($Result[$i]['DH']==2)
						{
							$Result[$i]['DEBITO']=0;
							$Result[$i]['HABER']=$value['Valor'];
						}
						$sql="INSERT INTO Trans_SubCtas
							   (Periodo ,T,TC,Cta,Fecha,Fecha_V,Codigo ,TP,Numero ,Factura ,Prima ,Debitos ,Creditos ,Saldo_MN,Parcial_ME
							   ,Saldo_ME,Item,Saldo ,CodigoU,X,Comp_No,Autorizacion,Serie,Detalle_SubCta,Procesado)
						 VALUES
							   ('".$_SESSION['INGRESO']['periodo']."'
							   ,'N'
							   ,'".$Result[$i]['TC']."'
							   ,'".$Result[$i]['Cta']."'
							   ,'".$parametros['fecha1']."'
							   ,'".$Result[$i]['FECHA_V']."'
							   ,'".$Result[$i]['Codigo']."'
							   ,'".$parametros['tip']."'
							   ,".$num_com[1]."
							   ,".$Result[$i]['Factura']."
							   ,".$Result[$i]['Prima']."
							   ,".$Result[$i]['DEBITO']."
							   ,".$Result[$i]['HABER']."
							   ,0
							   ,0
							   ,0
							   ,'".$_SESSION['INGRESO']['item']."'
							   ,0
							   ,'".$_SESSION['INGRESO']['CodigoU']."'
							   ,'.'
							   ,0
							   ,'.'
							   ,'.'
							   ,'.'
							   ,0)";
						//echo $sql.'<br>';
							   $this->conn1->String_Sql($sql);
					}

				// die();
				}
				
				//incrementamos el secuencial
				if($_SESSION['INGRESO']['Num_Meses_CD']==1)
				{
					//para variable en html
					$num1=$num_com[1];
					$num_com[1]=$num_com[1]+1;
					//echo $num_com[1].'<br>'.$_POST['tip'].'<br>';
					if(isset($parametros['fecha1']))
					{
						//echo $_POST['fecha'];
						$fecha_actual = $parametros['fecha1']; 
					}
					else
					{
						$fecha_actual = date("Y-m-d"); 
					}
					$ot = explode("-",$fecha_actual);
					if($parametros['tip']=='CD')
					{
						$sql ="UPDATE Codigos set Numero=".$num_com[1]."
						WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
						AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
						AND (Concepto = '".$ot[1]."Diario')";
					}
					if($parametros['tip']=='CI')
					{
						$sql ="UPDATE Codigos set Numero=".$num_com[1]."
						WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
						AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
						AND (Concepto = '".$ot[1]."Ingresos')";
					}
					if($parametros['tip']=='CE')
					{
						$sql ="UPDATE Codigos set Numero=".$num_com[1]."
						WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
						AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
						AND (Concepto = '".$ot[1]."Egresos')";
					}
					if($parametros['tip']=='ND')
					{
						$sql ="UPDATE Codigos set Numero=".$num_com[1]."
						WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
						AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
						AND (Concepto = '".$ot[1]."NotaDebito')";
					}
					if($parametros['tip']=='NC')
					{
						$sql ="UPDATE Codigos set Numero=".$num_com[1]."
						WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
						AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
						AND (Concepto = '".$ot[1]."NotaCredito')";
					}

					$this->conn1->String_Sql($sql);
					
					//borramos temporales asientos
					$sql="DELETE FROM Asiento
					WHERE 
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
					

					$this->conn1->String_Sql($sql);

					//borramos temporales asientos bancos
					
					$sql="DELETE FROM Asiento_B
					WHERE 
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
				

					$this->conn1->String_Sql($sql);
					//borramos asiento subcuenta
					$sql="DELETE FROM Asiento_SC
					WHERE 
						Item = '".$_SESSION['INGRESO']['item']."' 
						AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
				

					$this->conn1->String_Sql($sql);
					//generamos comprobante
					//reporte_com($num1);
					return $num1;
				}
			}
	}


	function datos_comprobante()
	{
		// $cid = $this->conn;
		$sql="SELECT * FROM Asiento WHERE CodigoU='".$_SESSION['INGRESO']['CodigoU']."' AND Item='".$_SESSION['INGRESO']['item']."' AND T_No = '99'";
		// print_r($sql);die();
		return $this->conn1->datos($sql);
	
	}
	

	function lista_hijos_id($query)
	{
			// $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
      $sql = "SELECT CP.Codigo_Inv,CP.Producto,CP.TC,TK.Costo as 'Valor_Total',CP.Unidad, SUM(Entrada-Salida) As Stock_Actual ,CP.Cta_Inventario
           FROM Catalogo_Productos As CP, Trans_Kardex AS TK 
           WHERE CP.INV = 1 AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' AND CP.Item = '".$_SESSION['INGRESO']['item']."'AND LEN(CP.Cta_Inventario)>3 AND CP.Codigo_Inv LIKE '".$query."' AND TK.T<> 'A' AND CP.Periodo = TK.Periodo AND CP.Item = TK.Item AND CP.Codigo_Inv = TK.Codigo_Inv group by CP.Codigo_Inv,CP.Producto,CP.TC,CP.Valor_Total,CP.Unidad,TK.Costo,CP.Cta_Inventario having SUM(TK.Entrada-TK.Salida) <> 0 
order by CP.Codigo_Inv,CP.Producto,CP.TC,CP.Valor_Total,CP.Unidad,CP.Cta_Inventario";
   

      $row = $this->conn1->datos($sql);
      $datos = array();
      foreach ($row as $key => $value) {
      	$datos[] = array('id'=>$value['Codigo_Inv'].','.$value['Unidad'].','.$value['Stock_Actual'],'text'=>$value['Producto']);
      }

	 
       return $datos;

	}

	function datos_asiento_SC_trans($orden,$fecha,$diferente=false)
	{
     // $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT SUM(VALOR_TOTAL) as 'total',Contra_Cta as 'CONTRA_CTA',CodigoL AS 'SUBCTA',Fecha AS 'Fecha_Fab',TC 
     FROM Trans_Kardex  
     WHERE Item = '".$_SESSION['INGRESO']['item']."' 
     AND Orden_No = '".$orden."' 
     AND Fecha = '".$fecha."'";
     if($diferente)
     {
     	$diferente = explode(',',$diferente);
     	foreach ($diferente as $key => $value) {
     		$sql.=" AND Codigo_Inv <>'".$value."' ";
     	}
     }  
     $sql.="GROUP BY Contra_Cta,Fecha,TC,CodigoL";
          // print_r($sql);die();
      return $this->conn1->datos($sql);
 
	}

	// function datos_asiento_SC($orden,$fecha,$diferente=false)
	// {
 //     // $cid = $this->conn;
 //    // 'LISTA DE CODIGO DE ANEXOS
 //     $sql = "SELECT SUM(VALOR_TOTAL) as 'total',CONTRA_CTA,SUBCTA,Fecha_Fab,TC 
 //     FROM Asiento_K  
 //     WHERE Item = '".$_SESSION['INGRESO']['item']."' 
 //     AND ORDEN = '".$orden."' 
 //     AND Fecha_Fab = '".$fecha."'";
 //     if($diferente)
 //     {
 //     	$diferente = explode(',',$diferente);
 //     	foreach ($diferente as $key => $value) {
 //     		$sql.=" AND Codigo_Inv <>'".$value."' ";
 //     	}
 //     }  
 //     $sql.="GROUP BY CONTRA_CTA,Fecha_Fab,TC,SUBCTA";
 //          // print_r($sql);die();
 //      return $this->conn1->datos($sql);
 
	// }

	function generar_asientos_SC($parametros)
	{
		// $cid = $this->conn;
		if($parametros['t']=='P' OR $parametros['t']=='C')
		{
			$sql=" SELECT codigo FROM clientes WHERE Codigo='".$parametros['sub']."' ";
			$row = $this->conn1->datos($sql);			
			if(count($row)>0)
			{
				$cod = $row[0]['codigo'];
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
		$row = $this->conn1->datos($sql);
		
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
			$sql="INSERT INTO Asiento_SC(Codigo ,Beneficiario,Factura ,Prima,DH,Valor,Valor_ME
           ,Detalle_SubCta,FECHA_V,TC,Cta,TM,T_No,SC_No
           ,Fecha_D ,Fecha_H,Bloquear,Item,CodigoU)
			VALUES
           ('".$cod."'
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
           ,'".$_SESSION['INGRESO']['CodigoU']."')";

           $this->conn1->String_Sql($sql);
		 
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

            $this->conn1->String_Sql($cam2);
			
			//echo $cam2;
		}
			$sql="SELECT Codigo, Beneficiario, Factura, Prima, DH, Valor, Valor_ME, Detalle_SubCta,T_No, SC_No,Item, CodigoU
			FROM Asiento_SC
			WHERE 
				Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			$stmt = sqlsrv_query( $this->conn1->conexion(), $sql);
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
	
    function eliminar_aiseto_K($orden,$fecha,$diferente=false)
	{
		 // $cid=$this->conn;
		$sql = "DELETE Asiento_K 
		WHERE Item='".$_SESSION['INGRESO']['item']."' 
		AND DH='2' 
		AND ORDEN ='".$orden."' 
		AND Fecha_Fab ='".$fecha."'";
		if($diferente)
             	{
             		$diferente = explode(',',$diferente);
             		foreach ($diferente as $key => $value) {
             			$sql.=" AND Codigo_Inv <>'".$value."' ";
             			}
             	}   

		// print_r($sql);die();
         return $this->conn1->String_Sql($sql);
		 

	}

	function misma_fecha($orden,$Codprove)
	{

       // $cid = $this->conn;
		$sql = "SELECT DISTINCT Fecha_Fab FROM Asiento_K WHERE CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' AND Item = '".$_SESSION['INGRESO']['item']."' AND DH = '2'  AND ORDEN ='".$orden."'  AND Codigo_B ='".$Codprove."'"; 

       
		 $datos = $this->conn1->datos($sql);
	   if(count($datos)>1)
	   {
	   	  return -1;
	   }else
	   {
	   	 return 1;
	   }
	}

	function actualizo_trans_kardex($lista)
	{
		// $cid = $this->conn;
		$sql = "UPDATE Trans_Kardex 
		SET Procesado = 0 
		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND Codigo_Inv  in (".$lista.")"; 
		return $this->conn1->String_Sql($sql);
	}

	function buscar_solicitante($query=false)
	{
		$sql = "SELECT * 
				FROM Catalogo_Rol_Pagos 
				WHERE Item = '".$_SESSION['INGRESO']['item']."' 
				AND Periodo='".$_SESSION['INGRESO']['periodo']."' 
				AND T = 'N' ";
		if($query)
		{
			
			$sql.= " AND Ejecutivo like '%".$query."%'";
		}

		// print_r($sql);die();

		 return  $this->conn1->datos($sql);

	}





}

?>