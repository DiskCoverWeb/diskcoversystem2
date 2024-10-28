<?php 
include(dirname(__DIR__,2).'/db/variables_globales.php');//
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
/**
 * 
 */
class pacienteM
{
	private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}

	function cargar_paciente($parametros,$pag=false)
	{
		// print_r($parametros);die();
		if($pag==false)
		{
			$pag = 0;
		}
		$cid = $this->conn;
		$sql="SELECT * FROM Clientes WHERE Matricula<>0";

		if($parametros['codigo']!='')
		{
			$sql.=" AND CI_RUC='".$parametros['codigo']."'";
		}
		if($parametros['query']!='')
		{
		   switch ($parametros['tipo']) {
			    case 'N':
				    $sql.=" AND Cliente like '%".$parametros['query']."%'";
				    break;
				case 'N1':
				    $sql.=" AND Cliente = '".$parametros['query']."'";
				    break;
			    case 'C':
				    $sql.=" AND Matricula like '%".$parametros['query']."%'";
				    break;
				case 'C1':
				    $sql.=" AND Matricula='".$parametros['query']."'";
				    break;
			    case 'R':
				    $sql.=" AND CI_RUC like '".$parametros['query']."%'";
				    break;
				case 'R1':
				    $sql.=" AND CI_RUC = '".$parametros['query']."'";
				    break;		
		   }
	    }
		$sql.=" ORDER BY ID OFFSET ".$pag." ROWS FETCH NEXT 25 ROWS ONLY;";
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
		$datos = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
		{
			$datos[]=$row;
		}
		// print_r($datos);die();
		return $datos;
	}

	function cargar_paciente_all($parametros,$pag=false,$sin_con=false)
	{
		// print_r($parametros);die();
		if($pag==false)
		{
			$pag = 0;
		}
		$cid = $this->conn;
		$sql="SELECT * FROM Clientes WHERE T='N'  ";
		if($sin_con)
		{
			$sql.=" AND Matricula<>'0' ";
		}

		if($parametros['codigo']!='')
		{
			$sql.=" AND CI_RUC='".$parametros['codigo']."'";
		}
		if($parametros['query']!='')
		{
		   switch ($parametros['tipo']) {
			    case 'N':
				    $sql.=" AND Cliente like '%".$parametros['query']."%'";
				    break;
				case 'N1':
				    $sql.=" AND Cliente = '".$parametros['query']."'";
				    break;
			    case 'C':
				    $sql.=" AND Matricula like '%".$parametros['query']."%'";
				    break;
				case 'C1':
				    $sql.=" AND Matricula='".$parametros['query']."'";
				    break;
			    case 'R':
				    $sql.=" AND CI_RUC like '".$parametros['query']."%'";
				    break;
				case 'R1':
				    $sql.=" AND CI_RUC = '".$parametros['query']."'";
				    break;		
		   }
	    }
		$sql.=" ORDER BY ID OFFSET ".$pag." ROWS FETCH NEXT 25 ROWS ONLY;";
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
		$datos = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
		{
			$datos[]=$row;
		}
		// print_r($datos);die();
		
		return $datos;
	}


	function cargar_paciente_proveedor($parametros,$pag=false)
	{
		// print_r($parametros);die();
		if($pag==false)
		{
			$pag = 0;
		}
		$cid = $this->conn;
		$sql="SELECT * FROM Clientes WHERE T='N' ";

		
		if($parametros['codigo']!='')
		{
			$sql.=" AND Codigo='".$parametros['codigo']."'";
		}
		if($parametros['query']!='')
		{
		   switch ($parametros['tipo']) {
			    case 'N':
				    $sql.=" AND Cliente like '%".$parametros['query']."%'";
				    break;
				case 'N1':
				    $sql.=" AND Cliente = '".$parametros['query']."'";
				    break;
			    case 'C':
				    $sql.=" AND Matricula like '%".$parametros['query']."%'";
				    break;
				case 'C1':
				    $sql.=" AND Matricula='".$parametros['query']."'";
				    break;
			    case 'R':
				    $sql.=" AND CI_RUC like '".$parametros['query']."%'";
				    break;
				case 'R1':
				    $sql.=" AND CI_RUC = '".$parametros['query']."'";
				    break;		
		   }
	    }
		$sql.=" ORDER BY ID OFFSET ".$pag." ROWS FETCH NEXT 25 ROWS ONLY;";
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
		$datos = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
		{
			$datos[]=$row;
		}
		// print_r($datos);die();
		return $datos;
	}
	
	function eliminar_paciente($id)
	{

		$cid = $this->conn;
		$sql = 'DELETE FROM Clientes WHERE ID='.$id;
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			return -1;			
			die( print_r( sqlsrv_errors(), true));  
		}else
		{
			return 1;
		}
		

	}
	function imprimir_paciente()
	{
		
	}
	function provincias()
	{
		$prov = provincia_todas();
		return $prov;
	}
	
	function ASIGNAR_COD_HISTORIA_CLINICA()
	{

		$cid = $this->conn;
		$sql="SELECT * FROM Codigos WHERE 
		Item='".$_SESSION['INGRESO']['item']."' and 
		Periodo='".$_SESSION['INGRESO']['periodo']."' and Concepto='HISTORIA_CLINICA'";
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
		$datos = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
		{
			$datos[]=$row;
		}
		if(count($datos)==0)
		{
			$res = $this->CREAR_COD_HISTORIA_CLINICA();
			return $res;
		}else
		{

		   return $datos[0]['Numero'];
		}

	}

	function ACTUALIZAR_COD_HISTORIA_CLINICA($datos)
	{
		$campoWhere[0]['campo']='Concepto';
		$campoWhere[0]['valor']='HISTORIA_CLINICA';

		$campoWhere[1]['campo']='Item';
		$campoWhere[1]['valor']=$_SESSION['INGRESO']['item'];

		$campoWhere[2]['campo']='Periodo';
		$campoWhere[2]['valor']=$_SESSION['INGRESO']['periodo'];

		  return update_generico($datos,'Codigos',$campoWhere);
	}

    function COD_HISTORIA_CLINICA_EXISTENTE($num_his)
	{

		$cid = $this->conn;
		$sql="SELECT * FROM Clientes WHERE Matricula='".$num_his."'";
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
		$datos = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
		{
			$datos[]=$row;
		}
		if(count($datos)==0)
		{
			return $num_his;
		}else
		{
		   return -1;
		}

	}

	function CREAR_COD_HISTORIA_CLINICA()
	{

		SetAdoAddNew("Codigo");    
		SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
		SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		SetAdoFields('Concepto','HISTORIA_CLINICA');
		SetAdoFields('Numero',1);

		return SetAdoUpdate();
	}

	function existe_transacciones_subcuenta_abonos_air_compras($cli)
	{
		// print_r($cli);die();
		$existe = false;
		   $cid = $this->conn;
           $sql[1] = "SELECT * FROM Transacciones WHERE Codigo_C = '".$cli."'";
           $sql[2] = "SELECT * FROM Trans_SubCtas WHERE Codigo = '".$cli."'";
           $sql[3] = "SELECT * FROM Trans_Abonos WHERE CodigoC = '".$cli."'";
           $sql[4] = "SELECT * FROM Trans_Air WHERE idProv= '".$cli."'";
           $sql[5] = "SELECT * FROM Trans_Compras WHERE idProv = '".$cli."'";
           $sql[6] = "SELECT * FROM Asiento_K WHERE Codigo_B = '".$cli."'";
           $i=0;
         while ( $existe == false) {
         	$i+=1;
         	$sq = $sql[$i];
         	// print_r($sq);die();
         	$stmt = sqlsrv_query($cid,$sq);
         	if( $stmt === false)
         	  {
         	     echo "Error en consulta PA.\n";  
			     die( print_r( sqlsrv_errors(), true));
			  }
			  $datos = array();
			  while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
			  		{
			  			$datos[]=$row;
			  		}
			  		if(count($datos)==0)
			  			{
			  				if($i>=6)
			  				{
			  					return $existe;
			  				}
			  			}else
			  			{
			  				$existe = true;
			  				return $existe;
			  			}
         }
	}
}

?>