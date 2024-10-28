<?php
if(!class_exists('db'))
{
include(dirname(__DIR__,2).'/funciones/funciones.php');
}//require(dirname(__DIR__,2).'/lib/fpdf/cabecera_pdf.php');
include(dirname(__DIR__,3).'/lib/fpdf/reporte_de.php');
@session_start(); 
/**
 * 
 */
class detalle_estudianteM
{
	private $conn;	
	private $pdf;	
	function __construct()
	{
		$this->conn = new db();
		$this->pdf = new FPDF();
	}


	function cargar_cursos()
	{
	   $cid=$this->conn;
       $sql = "SELECT * FROM Catalogo_Cursos WHERE 
       Item='".$_SESSION['INGRESO']['item']."' AND 
       Periodo = '".$_SESSION['INGRESO']['periodo']."' AND 
       LEN(Curso)>4 ORDER bY Curso;";

      //echo $sql;
     //  die();
       
 //    $stmt = sqlsrv_query($cid, $sql);
	// if( $stmt === false)  
	// {  
	// 	 echo "Error en consulta PA.\n";  
	// 	 return '';
	// 	 die( print_r( sqlsrv_errors(), true));  
	// }

	// $result = array();	
	// while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	// {
	// 	//$result[] = array('Curso'=>$row['Curso'],'Descripcion'=>utf8_encode($row['Descripcion']));
	// 	$result[] = $row;
	// 	//echo $row[0];
	// }

       $result[] = $this->conn->datos($sql);

       //print_r($result);
	return $result;
 //print_r($result);
	}
	function usuario_registrado($ci)
	{
	   // $cid=$this->conn;
       $sql = "SELECT * FROM  Clientes WHERE CI_RUC = '".$ci."';";
       $result = $this->conn->datos($sql);

   //    print_r($sql);
 //    $stmt = sqlsrv_query($cid, $sql);
	// if( $stmt === false)  
	// {  
	// 	 echo "Error en consulta PA.\n";  
	// 	 return '';
	// 	 die( print_r( sqlsrv_errors(), true));  
	// }
	// $result = array();
	//  while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
	//       {
	//     	$result[] = $row;
	//       }
	return $result;
 //print_r($result);


	}
	function usuario_registrado_clave($ci,$cla)
	{
	   // $cid=$this->conn;
       $sql = "SELECT * FROM  Clientes WHERE CI_RUC = '".$ci."' AND  Clave = '".$cla."'";

       
 //    $stmt = sqlsrv_query($cid, $sql);
	// if( $stmt === false)  
	// {  
	// 	 echo "Error en consulta PA.\n";  
	// 	 return '';
	// 	 die( print_r( sqlsrv_errors(), true));  
	// }
	// $result = array();
	//  while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
	//       {
	//     	$result[] = $row;
	//       }
   $result = $this->conn->datos($sql);
	return $result;
 //print_r($result);


	}

	function login($usu,$pass,$nuevo)
	{
		if($nuevo=='false')
		{
			$sql = "SELECT C.ID,C.Grupo,Archivo_Foto,CI_RUC,CI_P,Ocupacion_M,Ocupacion_P,C.Codigo,Cliente,C.Direccion,Sexo,Email,Procedencia,Matricula
			,Representante_Alumno,Nacionalidad,C.Prov,Seccion,Curso_Superior,C.Fecha_N,Fecha_M,Fecha,CI_RUC,C.Celular,Especialidad,C.Telefono,CM.Observaciones,Nombre_Padre,CI_P,
			Nacionalidad_P,Lugar_Trabajo_P,Telefono_Trabajo_P,Celular_P,Profesion_P,Email_P,Nombre_Madre,CI_M,Nacionalidad_M,
			Lugar_Trabajo_M,Telefono_Trabajo_M,Celular_M,Profesion_M,Email_M,Representante_Alumno,CI_R,Profesion_R,Ocupacion_R,
			C.Telefono_R,Telefono_RS,Lugar_Trabajo_R,Email_R,Email_R,Matricula_No,Folio_No,C.Ciudad,C.DireccionT,Email2,CDE.Evidencias
			FROM Clientes as C
			INNER JOIN Clientes_Matriculas CM ON C.CI_RUC = CM.Codigo		
			INNER JOIN Catalogo_Cursos ON c.Grupo = Catalogo_Cursos.Curso
			LEFT JOIN Clientes_Datos_Extras CDE on C.Codigo = CDE.Codigo
			WHERE FA = 'TRUE' 
			AND CI_RUC = '".$usu."' 
			AND Clave='".$pass."'";
		}else
		{
			$sql = "SELECT * FROM Clientes
			INNER JOIN Clientes_Matriculas ON Clientes.CI_RUC = Clientes_Matriculas.Codigo	
			WHERE FA = 'TRUE' 
			AND CI_RUC = '".$usu."' 
			AND Clave='".$pass."'";
		}
// print_r($sql);die();
		$result = $this->conn->datos($sql);
		if(count($result)==0 && $nuevo=='false')
		{
			$sql = "SELECT * FROM Clientes
				INNER JOIN Clientes_Matriculas ON Clientes.CI_RUC = Clientes_Matriculas.Codigo	
				WHERE FA = 'TRUE' 
				AND CI_RUC = '".$usu."' 
				AND Clave='".$pass."'";
			$result = $this->conn->datos($sql);
		}
			
		return $result;	
	}


	function img_guardar($name,$codigo)
	{
		 $cid=$this->conn;
		$sql = "UPDATE Clientes SET Archivo_foto = '".$name."' WHERE CI_RUC='".$codigo."'";
		//echo $sql;
		// $stmt = sqlsrv_query($cid, $sql);
	 //    if( $stmt === false)  
	 //      {  
		//      echo "Error en consulta PA.\n";  
		//      return -1;
		//      die( print_r( sqlsrv_errors(), true));  
	 //      }
	 //      else{
	 //      	return 1;
	 //      }      

		return $this->conn->String_Sql($sql);

	}

   function actualizar_datos($datos,$tabla,$campoWhere,$valorwhere)
   { 

   	 $campos_db = dimenciones_tabla($tabla);
   	 // print_r($campos_db);die();
   	$sql = 'UPDATE '.$tabla.' SET '; 
   	 $set='';
   	foreach ($datos as $key => $value) {
   		foreach ($campos_db as $key => $value1) 
   		{
   			if($value1['COLUMN_NAME']==$value['campo'])
   				{
   					if($value1['CHARACTER_MAXIMUM_LENGTH'] != '' && $value1['CHARACTER_MAXIMUM_LENGTH'] != null)
   						{
   							$set .=$value['campo']."='".substr($value['dato'],0,$value1['CHARACTER_MAXIMUM_LENGTH'])."',";
   				        }else
   				        {
   				        	$set .=$value['campo']."='".$value['dato']."',";
   				        }
   	            }

   		}
   		//print_r($value['campo']);
   	}
   	$set = substr($set,0,-1);
   	$where = "WHERE ".$campoWhere."='".$valorwhere."'";
   	$sql = $sql.$set.$where;
   //	print_r($sql);	

   	return $this->conn->String_Sql($sql);
   	// $stmt = sqlsrv_query($cid, $sql);
	   //  if( $stmt === false)  
	   //    {  
		  //    echo "Error en consulta PA.\n";  
		  //    return -1;
		  //    die( print_r( sqlsrv_errors(), true));  
	   //    }
	   //    else{
	   //    	return 1;
	   //    }  

   }

   function codigo_matricula($curso)
   {

	   // $cid=$this->conn;
       $sql = "SELECT  COUNT(T) as num  FROM Clientes_Matriculas WHERE Grupo_No = '".$curso."';";

      //echo $sql;
     //  die();
       
 //    $stmt = sqlsrv_query($cid, $sql);
	// if( $stmt === false)  
	// {  
	// 	 echo "Error en consulta PA.\n";  
	// 	 return '';
	// 	 die( print_r( sqlsrv_errors(), true));  
	// }
	// $result = array();

	//  $row = sqlsrv_num_rows($stmt);
	//  while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
	//       {
	//     	$result[] = $row;
	//       }
       // print_r($sql);die();
   $result = $this->conn->String_Sql($sql);
   // print_r($result);die();
   // if(count($result)>0)
   // {
			$row = $result+1;
		// }else{ $row=1;}
	if($row<10)
	{
		$row = '00'.$row;
	}else if($row>9 && $row < 100)
	{
	  $row = '0'.$row;
	}
	//print_r($row);

	return $row;
 //print_r($result);

   }

   function institucion_data()
   {
	   $sql = "SELECT * from Catalogo_Periodo_Lectivo where Item='".$_SESSION['INGRESO']['item']."'";
	   $result = $this->conn->datos($sql);
	   return $result;
   }

  function Empresa_data()
   {
   	$cid=$this->conn;
	   $sql = "SELECT * FROM Empresas where Item='".$_SESSION['INGRESO']['item']."'";
	   $result = $this->conn->datos($sql);
	   return $result;
   }

   function facturas_emitidas_excel($codigo,$reporte_Excel=false,$periodo=false,$tc='FA')
   {
   	$cid = $this->conn;
		
		$sql ="SELECT T,TC,Serie,Autorizacion,Factura,Fecha,SubTotal,Con_IVA,IVA,Descuento+Descuento2 as Descuentos,Total_MN as Total,Saldo_MN as Saldo,RUC_CI,TB,Razon_Social  FROM Facturas 
       WHERE TC = '".$tc."' ";
       if($codigo!='T')
       {
       	 $sql.=" and  CodigoC ='".$codigo."'";
      
       }
       $sql.=" AND Item = '".$_SESSION['INGRESO']['item']."'";
       if($periodo && $periodo!='.')
       {
       	 $sql.=" AND Periodo BETWEEN '01/01/".$periodo."' AND '31/12/".$periodo."'";
       }else
       {
       	$sql.=" AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ";
       }
       $sql.=" ORDER BY Fecha DESC"; 

        ///print_r($sql);die();

       $result = $this->conn->datos($sql);
      
	   if($reporte_Excel==false)
	   {
	   	  return $result;
	   }else
	   {

	   	 $b = 1;
	   	 $titulo='F A C T U R A S   E M I T I D A S';
	   	 if($tc=='LC'){
	   	 	$titulo='L I Q U I D A C I O N   D E  C O M P R A S   E M I T I D A S';
	   	 }
	     $tablaHTML =array();
	     $tablaHTML[0]['medidas']=array(6,6,13,25,15,23,18,12,12,12,12,12,12,6,20);
	     $tablaHTML[0]['datos']=array('T','TC','Serie','Autorizacion','Factura','Fecha','SubTotal','Con_IVA','IVA','Descuentos','Total','Saldo','RUC_CI','TB','Razon_Social');
	     $tablaHTML[0]['tipo'] ='C';
	     $pos = 1;
	     $compro1='';
	    foreach ($result as $key => $value) {
	          $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
	          $tablaHTML[$pos]['datos']=array($value['T'],$value['TC'],$value['Serie'],$value['Autorizacion'],$value['Factura'],$value['Fecha']->format('Y-m-d'),$value['SubTotal'],$value['Con_IVA'],$value['IVA'],$value['Descuentos'],$value['Total'],$value['Saldo'],$value['RUC_CI'],$value['TB'],$value['Razon_Social']);
	          $tablaHTML[$pos]['tipo'] ='N';          
	          $pos+=1;
	    }
      excel_generico($titulo,$tablaHTML);  


	   }

   }

    function facturas_emitidas_tabla($codigo)
   {
   	$cid = $this->conn;
		
		$sql ="SELECT T,TC,Serie,Autorizacion,Factura,Fecha,SubTotal,Con_IVA,IVA,Descuento+Descuento2 as Descuentos,Total_MN as Total,Saldo_MN as Saldo,RUC_CI,TB,Razon_Social,CodigoC,Periodo FROM Facturas 
		WHERE CodigoC ='".$codigo."'
		AND Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ORDER BY Fecha DESC"; 

		$botones[0] = array('boton'=>'Ver factura','icono'=>'<i class="fa fa-eye"></i>', 'tipo'=>'default', 'id'=>'Factura,Serie,CodigoC,Periodo,Autorizacion');
		$tabla = grilla_generica_new($sql,'Facturas',$id_tabla=false,$titulo=false,$botones,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,$tamaño_tabla=300,$num_decimales=2,$num_reg=false,$paginacion_view= false,$estilo=1);

       return $tabla;
   }

  function pdf_factura($cod,$ser,$ci)
  {
   	$id='factura_'.$ci;
   	$sql="SELECT * 
   	FROM Facturas 
   	WHERE Serie='".$ser."' 
   	AND Factura='".$cod."'
   	AND Item = '".$_SESSION['INGRESO']['item']."' 
   	AND CodigoC = '".$ci."'
		AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ";
	  $datos_fac = $this->conn->datos($sql);	 
	  $datos_fac[0] = $TFA = Leer_Datos_FA_NV($datos_fac[0]);

   	$sSQL = "SELECT DF.*, CP.Reg_Sanitario, CP.Marca, CP.Desc_Item, CP.Codigo_Barra As Cod_Barras
         FROM Detalle_Factura DF, Catalogo_Productos CP 
         WHERE DF.Item = '" . $_SESSION['INGRESO']['item'] . "' 
         AND DF.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
         AND DF.TC = '" . $TFA['TC'] . "' 
         AND DF.Serie = '" . $TFA['Serie'] . "' 
         AND DF.Autorizacion = '" . $TFA['Autorizacion'] . "' 
         AND DF.Factura = " . $TFA['Factura'] . " 
         AND DF.Item = CP.Item 
         AND DF.Periodo = CP.Periodo 
         AND DF.Codigo = CP.Codigo_Inv 
         ORDER BY DF.ID, DF.Codigo";
	  $detalle_fac= $this->conn->datos($sSQL);
	   
    $datos_cli_edu=$this->cliente_matri($ci);
    $sql2="SELECT * 
	    FROM Trans_Abonos 
	    WHERE Factura = '".$cod."' 
	    AND CodigoC='".$ci."' 
	    AND Item = '".$_SESSION['INGRESO']['item']."'
	    AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' "; 
	  $detalle_abonos = $this->conn->datos($sql2);

	   if( count( $datos_cli_edu)>0)
	   {
	   		$matri=1;
	   }else
	   {
        $datos_cli_edu=$this->Cliente($ci);
     		$matri=0;
	   }
     
     imprimirDocEle_fac($datos_fac,$detalle_fac,$datos_cli_edu,$matri,$id,null,'factura',null,null,$detalle_abonos);
  }

  function Cliente($cod)
   {
   	// $cid=$this->conn;
	   $sql = "SELECT * from Clientes WHERE  Codigo= '".$cod."'";

	   $result = $this->conn->datos($sql);
	   if(count($result)==0)
	   {
	   	 $sql = "SELECT * from Clientes WHERE  CI_RUC= '".$cod."'";
		   $result = $this->conn->datos($sql);
	   }
	      return $result;
   }

  function cliente_matri($codigo)
   {
   	$cid=$this->conn;
	   $sql = "SELECT * FROM Clientes_Matriculas WHERE Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' and Codigo = '".$codigo."'";
	   // $stmt = sqlsrv_query($cid, $sql);
	   //  if( $stmt === false)  
	   //    {  
		  //    echo "Error en consulta PA.\n";  
		  //    return '';
		  //    die( print_r( sqlsrv_errors(), true));  
	   //    }

	   //  $result = array();	
	   //  while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
	   //    {
	   //  	$result[] = $row;
		  //   //echo $row[0];
	   //    }

	     // $result =  encode($result);
	      // print_r($result);
	      return $result = $this->conn->datos($sql);
   }

   function cliente_proveedor($cod)
   {
   	// $cid=$this->conn;
	   $sql = "SELECT * from Clientes WHERE  Codigo= '".$cod."' AND FA='TRUE'";

	   $result = $this->conn->datos($sql);
	   // $stmt = sqlsrv_query($cid, $sql);
	   //  if( $stmt === false)  
	   //    {  
		  //    echo "Error en consulta PA.\n";  
		  //    return '';
		  //    die( print_r( sqlsrv_errors(), true));  
	   //    }

	   //  $result = array();	
	   //  while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
	   //    {
	   //  	$result[] = $row;
		  //   //echo $row[0];
	   //    }

	     // $result =  encode($result);
	      // print_r($result);
	      return $result;
   }







}
?>