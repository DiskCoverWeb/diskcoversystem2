<?php
//Llamada al modelo
require_once(dirname(__DIR__,2)."/modelo/contabilidad/contabilidad_model.php");
require_once(dirname(__DIR__,2)."/modelo/usuario_model.php");
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');

if(isset($_POST['submitlog'])) 
{
	login('', '', '');
}

if(isset($_GET['agencias'])) 
{
	// $parametros = $_POST['parametros'];
	echo json_encode(agencias());
}

if(isset($_GET['listar_comprobante'])) 
{
	$parametros = $_POST['parametros'];
	echo json_encode(listar_comprobantes($parametros));
}
if(isset($_GET['comprobantes'])) 
{
	$parametros = $_POST['parametros'];
	echo json_encode(comprobantes_procesados($parametros));
}
if(isset($_GET['tipo_balance']))
{
	echo json_encode(tipo_balance());
}

if(isset($_GET['reporte_pdf']))
{
	$parametros = array(
		'desde'=>$_GET['desde'],
	    'hasta'=>$_GET['hasta'],
	    'Tipo'=>$_GET['Tipo']);
	reporte_pdf($parametros);

}

if(isset($_GET['datos_balance']))
{
	$parametros = $_POST['parametros'];
	echo json_encode(sp_proceso_balance($parametros));
	// echo json_decode($l='ss');
}

if(isset($_GET['datos_balance_excel']))
{
	$parametros = array(
		'desde' => $_GET['desde'],
		'hasta' => $_GET['hasta'],
		'ext' => $_GET['ext'],
		'check' => $_GET['check'],
		'tipo_p' => $_GET['tipo_p'],
		'tipo_b' => $_GET['tipo_b'],
		'coop' => $_GET['coop'],
		'sucur' => $_GET['sucur'],
		'balMes' => $_GET['balMes'],
		'nom' => $_GET['nom'],
		'imp' => $_GET['imp']);

	// print_r($parametros);die();
	$res = sp_proceso_balance($parametros);
	echo json_decode(1);
	// echo json_decode($l='ss');
}

if(isset($_GET['reporte_pdf_bacsg']))
{
	$parametros = array(
		'desde' => $_GET['desde'],
		'hasta' => $_GET['hasta'],
		'ext' => $_GET['ext'],
		'check' => $_GET['check'],
		'tipo_p' => $_GET['tipo_p'],
		'tipo_b' => $_GET['tipo_b'],
		'coop' => $_GET['coop'],
		'sucur' => $_GET['sucur'],
		'balMes' => $_GET['balMes'],
		'nom' => $_GET['nom'],
		'imp' => $_GET['imp']);
		if($_GET['tipo_b']=='1' || $_GET['tipo_b']=='2' || $_GET['tipo_b']=='4' )
		{
			echo json_decode(sp_proceso_balance_pdf($parametros));
		}else
		{
			echo json_decode(sp_proceso_balance_pdf_situacion($parametros));
		}
}


if(isset($_GET['datos_tabla']))
{
	$modelo = new contabilidad_model();
	$tabla = $modelo->listar_tipo_balanceSQl(false,1,'BALANCE DE COMPROBACION');	
	echo json_encode($tabla);
   
	
}

if(isset($_GET['consultar']))
{
	$modal = new contabilidad_model();
	//$controlador = new libro_bancoC();
	//echo json_decode($controlador->consultar_banco($_POST['parametros']));
	$parametros=$_POST['parametros'];
	$desde =$parametros['desde'];
    $hasta = $parametros['hasta'];
    $reporte = null;
    if(isset($parametros['repor']))
    {
      $reporte = $parametros['repor'];
    }

	$balance=$modal->ListarEmpresasSQL('Analisis de vencimiento',null,null,null,null,$reporte,null,$desde,$hasta);
	echo json_encode($balance);

	// print_r($balance);die();
	// return $balance;
}

if(isset($_GET['consultar_reporte']))
{
	$modal = new contabilidad_model();
	//$controlador = new libro_bancoC();
	//echo json_decode($controlador->consultar_banco($_POST['parametros']));
	$desde =$_GET['desde'];
    $hasta = $_GET['hasta'];
    $reporte = $_GET['repor'];
	$balance=$modal->ListarEmpresasSQL('Analisis de vencimiento',null,null,null,null,$reporte,null,$desde,$hasta);
	echo json_encode($balance);

	// print_r($balance);die();
	// return $balance;
}

//==========================
//comprobamos si la variable enviada por get llega al controlador 
if(isset($_GET['balance']))
{
	//recuperamos los parametros enviados por post en una variable 
	// $parametros sera un array el nombre de $_POST['parametros'] sera en mismo de la vista
	// del jquery    data:  {parametros:parametros}
	$parametros = $_POST['parametros'];
	//llamamops a la funcion a ejecutar
     $tabla = reporte_analitico_mensual($parametros);
     //para devolverla a la vista la retornaremos como json
     echo json_encode($tabla);
     //este proseso se puede simplificar colocando  "echo json_decode(reporte_analitico_mensual($parametros());"
}

if(isset($_GET['balance_excel']))
{
	//recuperamos los parametros enviados por post en una variable 
	// $parametros sera un array el nombre de $_POST['parametros'] sera en mismo de la vista
	// del jquery    data:  {parametros:parametros}
	$parametros = array(
		'desde'=>$_GET['desde'],
	    'hasta'=>$_GET['hasta'],
	    'TipoBa'=>$_GET['TipoBa'],
	    'TipoPre'=>$_GET['TipoPre'],
	    'Tipo'=>$_GET['Tipo'],
	    'Imp'=>$_GET['Imp'],);
	//llamamops a la funcion a ejecutar
     $tabla = reporte_analitico_mensual($parametros);
     //para devolverla a la vista la retornaremos como json
     echo json_decode($tabla);
     //este proseso se puede simplificar colocando  "echo json_decode(reporte_analitico_mensual($parametros());"
}

//===============================

//funcion que recive los parametros
function reporte_analitico_mensual($parametros)
{
	
	$item = $_SESSION['INGRESO']['item'];
	if(isset($parametros['Agencia']) && isset($parametros['AgenciaVal']) && $parametros['Agencia']=='true')
	{
		$item = $parametros['AgenciaVal'];
	}


	control_procesos('N', "(".$parametros['Tipo'].") Analitico Mensual del ".$parametros['desde']."  al ".$parametros['hasta'],' Consulta');
	//creamos el objeto 
	$modelo = new contabilidad_model();
	//enviamos los datos al modelo
	$respuesta = $modelo->sp_Reporte_Analitico_Mensual($parametros['Tipo'],$parametros['desde'],$parametros['hasta']);
	// print_r($respuesta);die();

	//verificamos si todo salio bien  1 ejecutado -1 fallo al ejecutar (respuestas que personalmente las coloco)
	if($respuesta['respuesta'] == 1)
	{
		if($parametros['Imp']=='false')
		{
		   $tabla = $modelo->Reporte_Analitico_Mensual_gilla($parametros['Tipo'],$respuesta['query'],false,$item);
		   return $tabla;
		//retoprnamos la tabla este retorno se realizara a la primera duncion donde se llamar "don de esta isset($_GET['balance']"
		   // return $tabla;
	    }else
	    {
	     $tabla = $modelo->Reporte_Analitico_Mensual_gilla($parametros['Tipo'],$respuesta['query'],'true',$item);
	    }
	}else
	{
		return -1;
	}
}


//lista balance funcion sql server
function ListarTipoDeBalanceSQL($ti=null,$Opcb=null,$Opcem=null,$OpcDG=null,$b=null,$OpcCE=null)
{
	//echo ' dd '.$id_empresa;
	$per=new contabilidad_model();
	
	if($Opcb==NULL)
	{
		//echo " entrooo ";
		if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
		{
			//hacemos conexion en sql
			$per->conexionSQL();
			// echo ' dd '.$OpcDG;
			$balance=$per->ListarTipoDeBalanceSQL($ti,$Opcb,$Opcem,$OpcDG,$b,null,$OpcCE);
			
		}
		if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
		{
			$balance=$per->ListarTipoDeBalanceMYSQL($ti,$Opcb,$Opcem,$OpcDG,$b,null,$OpcCE);
		}
	}
	else
	{
		//verificamos el periodo si es general o mensual
		// echo " entrooo 2 ";
		//echo ' dd '.$OpcDG;
		$per->conexionSQL();
		$per1=new usuario_model();
		$per1->conexionSQL();
		$empresa=$per1->getPeriodoActualSQL($Opcem);
		if($empresa)
		{
			$_SESSION['INGRESO']['Fechai']=$empresa[0]['Fecha_Inicial'];
			$_SESSION['INGRESO']['Fechaf']=$empresa[0]['Fecha_Final'];
		}
		else
		{
			$per1->conexionSQL();
			$empresa=$per1->getPeriodoActualSQL();
		}
		//hacemos conexion en sql
		//$per->conexionSQL();
		$balance=$per->ListarTipoDeBalanceSQL($ti,$Opcb,$Opcem,$OpcDG,$b,null,$OpcCE);
	}
	$per->cerrarSQLSERVER();
	return $balance;
}
 //listar asiento
function ListarAsiento($ti=null,$Opcb=null,$Opcem=null,$OpcDG=null,$b=null)
{
	$per=new contabilidad_model();
	//echo "ListarAsientoSQL";
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		//hacemos conexion en sql
		$per->conexionSQL();
		//echo ' dd '.$OpcDG;
		$balance=$per->ListarAsientoSQL($ti,$Opcb,$Opcem,$OpcDG,$b);
		$per->cerrarSQLSERVER();
	}
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		$balance=$per->ListarAsientoMYSQL($ti,$Opcb,$Opcem,$OpcDG,$b);
	}
	
}
//listar asiento temporales
function ListarAsientoTem($ti=null,$Opcb=null,$b=null,$ch=null)
{
	$per=new contabilidad_model();
	//echo "ListarAsientoSQL";
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		//hacemos conexion en sql
		$per->conexionSQL();
		//echo ' dd '.$OpcDG;
		$balance=$per->ListarAsientoTemSQL($ti,$Opcb,$b,$ch);
		$per->cerrarSQLSERVER();
	}
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		$balance=$per->ListarAsientoTemSQLMYSQL($ti,$Opcb,$b,$ch);
	}
	
}
//listar asiento temporales
function ListarAsientoSc($ti=null,$Opcb=null,$b=null,$ch=null)
{
	$per=new contabilidad_model();
	//echo "ListarAsientoSQL";
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		//hacemos conexion en sql
		$per->conexionSQL();
		//echo ' dd '.$OpcDG;
		$balance=$per->ListarAsientoScSQL($ti,$Opcb,$b,$ch);
		$per->cerrarSQLSERVER();
	}
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		$balance=$per->ListarAsientoScSQLMYSQL($ti,$Opcb,$b,$ch);
	}
	
}
//listar totales temporales
function ListarTotalesTem($ti=null,$Opcb=null,$b=null,$ch=null)
{
	$per=new contabilidad_model();
	//echo "ListarAsientoSQL";
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		//hacemos conexion en sql
		$per->conexionSQL();
		//echo ' dd '.$OpcDG;
		$balance=$per->ListarTotalesTemSQL($ti,$Opcb,$b,$ch);
		$per->cerrarSQLSERVER();
	}
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		$balance=$per->ListarTotalesTemSQLMYSQL($ti,$Opcb,$b,$ch);
	}
	
}
 //exportar excel $base=base de datos,$generico= si es una consulta generica,$proceso= que proceso generico llamo,$arr= datos
function exportarExcel($ti=null,$Opcb=null,$Opcem=null,$OpcDG=null,$b=null,$OpcCE=null,$base=null,$generico=null,$proceso=null,$arr=null)
{
	//echo ' dd '.$id_empresa;
	$per=new contabilidad_model();
	if($base==null or $base=='SQL SERVER')
	{
		if($Opcb==NULL)
		{
			//echo " entrooo ";
			if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
			{
				//hacemos conexion en sql
				$per->conexionSQL();
				//echo ' dd '.$OpcDG;
				$balance=$per->ListarTipoDeBalanceSQL($ti,$Opcb,$Opcem,$OpcDG,$b,'2',$OpcCE);
				$per->cerrarSQLSERVER();
			}
			if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
			{
				$balance=$per->ListarTipoDeBalanceMYSQL($ti,$Opcb,$Opcem,$OpcDG,$b,null,$OpcCE);
			}
		}
		else
		{
			//verificamos el periodo si es general o mensual
			//echo " entrooo 2 ";
			//echo ' dd '.$OpcDG;
			$per->conexionSQL();
			$per1=new usuario_model();
			$per1->conexionSQL();
			$empresa=$per1->getPeriodoActualSQL($Opcem);
			if($empresa)
			{
				$_SESSION['INGRESO']['Fechai']=$empresa[0]['Fecha_Inicial'];
				$_SESSION['INGRESO']['Fechaf']=$empresa[0]['Fecha_Final'];
			}
			else
			{
				$per1->conexionSQL();
				$empresa=$per1->getPeriodoActualSQL();
			}
			//hacemos conexion en sql
			//$per->conexionSQL();
			//$balance=$per->exportarExcelSQL($ti,$Opcb,$Opcem,$OpcDG,$b);
			$balance=$per->ListarTipoDeBalanceSQL($ti,$Opcb,$Opcem,$OpcDG,$b,'2',$OpcCE);
		}
		$per->cerrarSQLSERVER();	
	}
	else
	{
		if($base=='MYSQL')
		{
			if($generico=='1')
			{
				//echo " entroooo ".$base;
				//$cid = Conectar::conexion('MYSQL');
				$usuario=$per->ExportarExcelUsuario($ti,$Opcb,$Opcem,$OpcDG,$b,'2',$OpcCE,$base,$generico,$proceso,$arr);
			}
		}
	}
	
}
 //listar documento electronico opcr=opcion reporte 2=excel, $ch = ver check box en grilla
 //$start_from inicio en caso de paginador $record_per_page fin en caso de paginador
function ListarDocEletronico($ti=null,$Opcb=null,$Opcem=null,$OpcDG=null,$b=null,$opcr=null,
$ch=null,$start_from=null, $record_per_page=null, $filtro=null)
{
	$per=new contabilidad_model();
	//echo "ListarAsientoSQL";
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		//hacemos conexion en sql
		$per->conexionSQL();
		//echo ' dd '.$OpcDG;
		$balance=$per->ListarDocEletronicoSQL($ti,$Opcb,$Opcem,$OpcDG,$b,$opcr,$ch,$start_from, $record_per_page,$filtro);
		$per->cerrarSQLSERVER();
	}
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		$balance=$per->ListarDocEletronicoMYSQL($ti,$Opcb,$Opcem,$OpcDG,$b,$opcr,$ch,$start_from, $record_per_page,$filtro);
	}
	
}
 //listar facturacion opcr=opcion reporte 2=excel, $ch = ver check box en grilla
 //$start_from inicio en caso de paginador $record_per_page fin en caso de paginador
 //$filtros filtros de la consulta $ord ordenar campos 0,1,2,3 etc
 //$like para filtrar like por campos
function ListarFacturacion($ti=null,$Opcb=null,$Opcem=null,$OpcDG=null,$b=null,$opcr=null,
$ch=null,$start_from=null, $record_per_page=null, $filtro=null,$ord=null,$like=null)
{
	$per=new contabilidad_model();
	//echo "ListarAsientoSQL";
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		//hacemos conexion en sql
		$per->conexionSQL();
		//echo ' dd '.$OpcDG;
		$balance=$per->ListarFacturacionSQL($ti,$Opcb,$Opcem,$OpcDG,$b,$opcr,$ch,$start_from, $record_per_page,$filtro,$ord,$like);
		$per->cerrarSQLSERVER();
	}
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		$balance=$per->ListarFacturacionMYSQL($ti,$Opcb,$Opcem,$OpcDG,$b,$opcr,$ch,$start_from, $record_per_page,$filtro,$ord,$like);
	}
	
}
//imprimir xml documento electronico
//$imp para que se descargue o no
function ImprimirDocEletronico($id,$formato=null,$tabla=null,$campo=null,$campob=null,$imp=null)
{
	$per=new contabilidad_model();
	//echo "ListarAsientoSQL";
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		//hacemos conexion en sql
		$per->conexionSQL();
		//echo ' dd '.$OpcDG;
		//echo ' cc '.$imp;
		$balance=$per->ImprimirDocEletronicoSQL($id,$formato,$tabla,$campo,$campob,$imp);
		$per->cerrarSQLSERVER();
	}
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		$balance=$per->ImprimirDocEletronicoMYSQL($id,$formato,$tabla,$campo,$campob,$imp);
	}
}
//para saber la cantidad de registro
function cantidaREG($tabla,$filtro=null)
{
	$per=new contabilidad_model();
	//echo "ListarAsientoSQL";
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		//hacemos conexion en sql
		$per->conexionSQL();
		//echo ' dd '.$OpcDG;
		$balance=$per->cantidaREGSQL($tabla,$filtro);
		$per->cerrarSQLSERVER();
	}
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		$balance=$per->cantidaREGMYSQL($tabla,$filtro);
	}
	return $balance;
}
//para buscar correo en base al doc. Electronico
function buscarCorreoDoc($id)
{
	$per=new contabilidad_model();
	//echo "ListarAsientoSQL";
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		//hacemos conexion en sql
		$per->conexionSQL();
		//echo ' dd '.$OpcDG;
		$balance=$per->buscarCorreoDocSQL($id);
		$per->cerrarSQLSERVER();
	}
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		$balance=$per->buscarCorreoDocMYSQL($id);
	}
	return $balance;
}
//ejecutar spk mayorizar
//para buscar correo en base al doc. Electronico
function sp_Mayorizar_Cuentas($opc,$sucursal,$item,$periodo,$codigo=null)
{
	$per=new contabilidad_model();
	//echo "ListarAsientoSQL";
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		//hacemos conexion en sql
		$per->conexionSQL();
		//echo ' dd '.$OpcDG;
	
		$balance=$per->sp_Mayorizar_CuentasSQL($opc,$sucursal,$item,$periodo,$codigo);
		
		$per->cerrarSQLSERVER();
	}
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		$balance=$per->sp_Mayorizar_CuentasMYSQL($opc,$sucursal,$item,$periodo,$codigo);
	}
	return $balance;
}
//para buscar correo en base al doc. Electronico
function sp_errores($item,$modulo,$id)
{
	// $db = new db();
	$per=new contabilidad_model();
	//echo "ListarAsientoSQL";
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		//hacemos conexion en sql
		// $per->conexionSQL();
		//echo ' dd '.$OpcDG;
		 return $balance=$per->sp_erroresSQL($item,$modulo,$id);
		// $per->cerrarSQLSERVER();
	}
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		$balance=$per->sp_erroresMYSQL($item,$modulo,$id);
	}
	return $balance;
}

function sp_proceso_balance($parametros)
{
	// print_r($parametros);die();
	$modelo = new contabilidad_model();
	$fechaini = str_replace('-','',$parametros['desde']);
	$fechafin = str_replace('-','',$parametros['hasta']);
	if($parametros['balMes']==1)
	{
		$fe = explode('-',$parametros['desde']);
		$fe2 = explode('-',$parametros['hasta']);
		$fechaini =$fe[0].$fe[1].'01';
		$fechafin =str_replace('-','',date("Y-m-t", strtotime($parametros['desde']))); 
		// print_r($fechaini.'-');print_r($fechafin);die();
	}
	// print_r($parametros);die();
	if($parametros['check']=='false'){
		 $balance=$modelo->sp_procesar_balance_SQL($fechaini,$fechafin,$parametros['coop'],$parametros['sucur'],$parametros['balMes'],$parametros['ext']);

		 // print_r('sss'.$balance);die();
    }else
    {
    	$balance=$modelo->sp_procesar_balance_ext();
    }
    // print_r($balance);
    // print_r($parametros);die();
	if($balance == 1)
	{
		if($parametros['check']=='false')
		{
			 if($parametros['imp']=='false')
			 {
			  $tabla = $modelo->listar_tipo_balanceSQl($parametros['balMes'],$parametros['tipo_b'],$parametros['tipo_p'],false,$parametros['nom']);
			  return $tabla;
			 }else
			 {
			 	return $modelo->listar_tipo_balanceSQl($parametros['balMes'],$parametros['tipo_b'],$parametros['tipo_p'],true,$parametros['nom']);
			 }
		}else
		{
			// $parametros['ext'] = '00';
			if($parametros['imp']=='false')
			{
			$tabla = $modelo->ListarTipoDeBalance_Ext($parametros['balMes'],'BC',$parametros['ext']);
			return $tabla;
		    }else
		    {
		    	return $modelo->ListarTipoDeBalance_Ext($parametros['balMes'],'BC',$parametros['ext'],true);
		    }

		}
	}
	// print_r($balance);die();
}

function sp_proceso_balance_pdf($parametros)
{
	$modelo = new contabilidad_model();
	$fechaini = str_replace('-','',$parametros['desde']);
	$fechafin = str_replace('-','',$parametros['hasta']);
	if($parametros['balMes']==1)
	{
		$fe = explode('-',$parametros['desde']);
		$fe2 = explode('-',$parametros['hasta']);
		$fechaini =$fe[0].$fe[1].'01';
		$fechafin =str_replace('-','',date("Y-m-t", strtotime($parametros['desde']))); 
		// print_r($fechaini.'-');print_r($fechafin);die();
	}
	// print_r($parametros);die();
	if($parametros['check']=='false'){
		$balance=$modelo->sp_procesar_balance_SQL($fechaini,$fechafin,$parametros['coop'],$parametros['sucur'],$parametros['balMes'],$parametros['ext']);
    }else
    {
    	$balance=$modelo->sp_procesar_balance_ext();
    }
	if($balance == 1)
	{
		if($parametros['check']=='false')
		{
			// $pdf++
			 
			$datos = $modelo->listar_tipo_balanceSQl_pdf($parametros['balMes'],$parametros['tipo_b'],$parametros['tipo_p'],true);
			$campos = str_replace(array('TC','DG'),array('',''), $datos['campos']);
			$campos = explode(',',trim($campos));
			$campos = array_values(array_filter($campos));
			$ali =array();
			$medi =array();
			foreach ($campos as $key => $value) {

				switch ($value) {
					case 'Cuenta':
							$ali[$key] = 'L';
							$medi[$key] =55;
						break;
					case 'Codigo':
							$ali[$key] = 'L';
							$medi[$key] =36;
						break;
					
					default:
							$val =  strlen(trim($value));
							if($val != 2){
							$ali[$key] = 'L';
							$medi[$key] = $val*2.5;
						    }else
						    {
						    	$ali[$key] = 'L';
							    $medi[$key] = $val*4;
						    }

						break;
				}
			}
			$pdf = new cabecera_pdf();	

// print_r($campos);die();
	        $titulo = $parametros['nom'];
	        $mostrar = true;
	        $sizetable =9;
	        $tablaHTML = array();
		    $tablaHTML[0]['medidas']=$medi;
		    $tablaHTML[0]['alineado']=$ali;
		    $tablaHTML[0]['datos']=$campos;
		    $tablaHTML[0]['estilo']='BI';
		    $tablaHTML[0]['borde'] = '1';
		    $pos = 1;

		    // print_r($datos);die();

		    	$totalD = 0;
		    	$totalH = 0;
		    foreach ($datos['datos'] as $key => $value) {
		    	$datos = array();
		    	$alineado = array();
		    	// print_r($value);die();
		    	foreach($value as $key1 => $valu)
		    	{
		    		// print_r($value);die();
		    		if($key1!='DG' && $key1!='TC')
		    		{
		    			if(is_numeric($valu))
		    			{

		    				array_push($datos, number_format($valu,2,'.',''));
		    				array_push($alineado,'R');
		    			}else
		    			{
		    				array_push($datos, $valu);		    	
		    				array_push($alineado,'L');		
		    			}
		    		}
		    	}

		    	// print_r($alineado);die();
			    	$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		        $tablaHTML[$pos]['alineado']= $alineado;
		        $tablaHTML[$pos]['datos']=$datos;
		        $tablaHTML[$pos]['estilo']='I';
		        $tablaHTML[$pos]['borde'] = 'RL';
		        $pos = $pos+1;

		        $totalD = $totalD + $datos[3];
		    		$totalH = $totalH + $datos[4];
		    }
		    		$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		        $tablaHTML[$pos]['alineado']=$alineado;
		        $tablaHTML[$pos]['datos']=array('','','',$totalD,$totalH,'');
		        $tablaHTML[$pos]['estilo']='I';
		        $tablaHTML[$pos]['borde'] = 'BT';
		        $pos = $pos+1;
	        $pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$parametros['desde'],$parametros['hasta'],$sizetable,$mostrar);


		
		}else
		{
			
			print_r('expression');die();
			$datos = $modelo->ListarTipoDeBalance_Ext_pdf($parametros['balMes'],'BC',$parametros['ext']);
			$campos = explode(',',trim($datos['campos']));
			$ali =array();
			$medi =array();
			foreach ($campos as $key => $value) {
				if($value == ' Cuenta' or $value == 'Cuenta')
				{
					$ali[$key] = 'L';
					$medi[$key] =35;

				}else
				{
					$val =  strlen(trim($value));
					if($val != 2){
					$ali[$key] = 'L';
					$medi[$key] = $val*2.5;
				    }else
				    {
				    	$ali[$key] = 'L';
					    $medi[$key] = $val*3;
				    }
					// array_push($medi, 10);
				}
				if($value == ' Codigo' or $value == 'Codigo')
				{
					$ali[$key] = 'L';
					$medi[$key] =25;
				}
			}
			$pdf = new cabecera_pdf();	


	        $titulo = $parametros['nom'];
	        $mostrar = true;
	        $sizetable =8;
	        $tablaHTML = array();
		    $tablaHTML[0]['medidas']=$medi;
		    $tablaHTML[0]['alineado']=$ali;
		    $tablaHTML[0]['datos']=$campos;
		    $tablaHTML[0]['estilo']='BI';
		    $tablaHTML[0]['borde'] = '1';
		    $pos = 1;
		    foreach ($datos['datos'] as $key => $value) {
		    	$datos = array();
		    	foreach($value as $key1 => $valu)
		    	{
		    		if(is_int($valu))
		    		{  
		    			if(is_numeric($valu))
		    			{
		    				array_push($datos, number_format($valu,2,'.',''));
		    			}else
		    			{
		    				array_push($datos, $valu);	
		    			}
		    		}else
		    		{
		    			array_push($datos, $valu);		    			
		    		}
		    	}

		    	// print_r($datos);die();
			    $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		        $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		        $tablaHTML[$pos]['datos']=$datos;
		        $tablaHTML[$pos]['estilo']='I';
		        $tablaHTML[$pos]['borde'] = '1';
		        $pos = $pos+1;
		    }
	        $pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$parametros['desde'],$parametros['hasta'],$sizetable,$mostrar);



		}
	}
	// print_r($balance);die();
}


function sp_proceso_balance_pdf_situacion($parametros)
{
	$modelo = new contabilidad_model();
	$fechaini = str_replace('-','',$parametros['desde']);
	$fechafin = str_replace('-','',$parametros['hasta']);
	if($parametros['balMes']==1)
	{
		$fe = explode('-',$parametros['desde']);
		$fe2 = explode('-',$parametros['hasta']);
		$fechaini =$fe[0].$fe[1].'01';
		$fechafin =str_replace('-','',date("Y-m-t", strtotime($parametros['desde']))); 
		// print_r($fechaini.'-');print_r($fechafin);die();
	}
	// print_r($parametros);die();
	if($parametros['check']=='false'){
		$balance=$modelo->sp_procesar_balance_SQL($fechaini,$fechafin,$parametros['coop'],$parametros['sucur'],$parametros['balMes'],$parametros['ext']);
    }else
    {
    	$balance=$modelo->sp_procesar_balance_ext();
    }

		if($parametros['check']=='false')
		{
			// $pdf++
			 
			$datos = $modelo->listar_tipo_balanceSQl_pdf($parametros['balMes'],$parametros['tipo_b'],$parametros['tipo_p'],true);
			$campos = explode(',',trim($datos['campos']));
				$ali =array();
			$medi =array();
			
			$pdf = new cabecera_pdf();	

	        $titulo = $parametros['nom'];
	        $mostrar = true;
	        $sizetable =9;
	        $tablaHTML = array();
		    $tablaHTML[0]['medidas']=array(35,65,30,30,30);
		    $tablaHTML[0]['alineado']=array('L','L','R','R','R');
		    $tablaHTML[0]['datos']=array('CODIGO','CUENTA','ANALITICO','PARCIAL','TOTAL');
		    $tablaHTML[0]['estilo']='BI';
		    $tablaHTML[0]['borde'] = '1';
		    $pos = 1;

		    // print_r($datos);die();

		    	$totalD = 0;
		    	$totalH = 0;
		    foreach ($datos['datos'] as $key => $value) {
		    
		    	// print_r($alineado);die();
			    	$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		        $tablaHTML[$pos]['alineado']= $tablaHTML[0]['alineado'];
		        $tablaHTML[$pos]['datos']= array($value['Codigo'],$value['Cuenta'],'','','');
		        $tablaHTML[$pos]['estilo']='I';
		        $tablaHTML[$pos]['borde'] = 'RL';
		        $pos = $pos+1;
		    }
		    		$tablaHTML[$pos]['medidas']=array(190);
		        $tablaHTML[$pos]['alineado']=array('L');
		        $tablaHTML[$pos]['datos']=array('');
		        $tablaHTML[$pos]['estilo']='I';
		        $tablaHTML[$pos]['borde'] = 'BT';
		        $pos = $pos+1;
	        $pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$parametros['desde'],$parametros['hasta'],$sizetable,$mostrar);


		
		
	}
	// print_r($balance);die();
}




//ejecutar proceso de balance
function sp_Procesar_Balance($opc,$sucursal,$item,$periodo,$fechai,$fechaf,$bm,$cc=null)
{

	$per=new contabilidad_model();
	//echo "ListarAsientoSQL";
	//formateamos fechas
	if($fechai!='' AND $fechaf!='')
	{
		$fei = explode("/", $fechai);
		$fef = explode("/", $fechaf);
		//echo $fechai.' ';
		if( count($fei)>1)
		{
			if(strlen($fei[2])==2 AND strlen($fef[2])==2)
			{
				$feci=$fei[0].$fei[1].$fei[2];
				$_SESSION['INGRESO']['Fechai']=$fei[0].'-'.$fei[1].'-'.$fei[2];
				$fecf=$fef[0].$fef[1].$fef[2];
				$_SESSION['INGRESO']['Fechaf']=$fef[0].'-'.$fef[1].'-'.$fef[2];
			}
			else
			{
				$feci=$fei[2].$fei[0].$fei[1];
				$_SESSION['INGRESO']['Fechai']=$fei[2].'-'.$fei[0].'-'.$fei[1];
				$fecf=$fef[2].$fef[0].$fef[1];
				$_SESSION['INGRESO']['Fechaf']=$fef[2].'-'.$fef[0].'-'.$fef[1];
			}
		}
		else
		{
			$fei = explode("-", $fechai);
			$fef = explode("-", $fechaf);
			if(strlen($fei[2])==2 AND strlen($fef[2])==2)
			{
				$feci=$fei[0].$fei[1].$fei[2];
				$_SESSION['INGRESO']['Fechai']=$fei[0].'-'.$fei[1].'-'.$fei[2];
				$fecf=$fef[0].$fef[1].$fef[2];
				$_SESSION['INGRESO']['Fechaf']=$fef[0].'-'.$fef[1].'-'.$fef[2];
			}
			else
			{
				if(strlen($fei[2])==2 AND strlen($fef[2])==2)
				{
					$feci=$fei[0].$fei[1].$fei[2];
					$_SESSION['INGRESO']['Fechai']=$fei[0].'-'.$fei[1].'-'.$fei[2];
					$fecf=$fef[0].$fef[1].$fef[2];
					$_SESSION['INGRESO']['Fechaf']=$fef[0].'-'.$fef[1].'-'.$fef[2];
				}
				else
				{
					$feci=$fei[2].$fei[0].$fei[1];
					$_SESSION['INGRESO']['Fechai']=$fei[2].'-'.$fei[0].'-'.$fei[1];
					$fecf=$fef[2].$fef[0].$fef[1];
					$_SESSION['INGRESO']['Fechaf']=$fef[2].'-'.$fef[0].'-'.$fef[1];
					//echo ' sss '.$feci.' al '.$fecf;
				}
				
			}
		}
		
		//echo $_SESSION['INGRESO']['Fechai'].' al '.$_SESSION['INGRESO']['Fechaf'];
		if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
		{
			//hacemos conexion en sql
			$per->conexionSQL();
			//echo ' dd '.$OpcDG;
			
			$balance=$per->sp_Procesar_BalanceSQL($opc,$sucursal,$item,$periodo,$feci,$fecf,$bm,$cc);
			$per->cerrarSQLSERVER();
		}
		if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
		{
			$balance=$per->sp_Procesar_BalanceMYSQL($opc,$sucursal,$item,$periodo,$feci,$fecf,$bm,$cc);
		}
		
		
	}
	else
	{
		
	}
	return $balance;
}

function tipo_balance()
{
	$modal = new contabilidad_model();
	// print_r('expression');
	$datos = $modal->tipo_balance_();
	// print_r($datos);die();
	return $datos;
}


function Tabla_Dias_Meses()
{
	$modelo = new contabilidad_model();
	$datos = $modelo->Tabla_Dias_Meses();
	$op='';
	foreach ($datos as $key => $value) {
		$op.='<option value="'.$value['No_D_M'].'">'.$value['Dia_Mes'].'</option>';
	}
	return $op;
	// print_r($datos);die();
}


function reporte_pdf($parametros)
{
	$modal = new contabilidad_model();
	$pdf = new cabecera_pdf();	

	$datos =  $modal->pdf_reporte($parametros['Tipo']);
	$titulo = ' Resumen Analitico Mensual De Utilidades/Perdidas';
	$mostrar = true;
	$sizetable =7;
	// $parametros['Fechaini']='2020-02-03';
	// $parametros['Fechafin']='2020-01-01';
	$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(20,35,10,12,10,10,10,10,10,12,18,13,16,16,15,18,18,10,10);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		$tablaHTML[0]['datos']=array('Cta','Detalle_Cuenta','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre','Total','Presupuesto','Diferencia','DG','TC');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = '1';
		$pos = 1;
		foreach ($datos as $key => $value) {
			$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		    $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		    $tablaHTML[$pos]['datos']=array($value['Cta'],$value['Detalle_Cuenta'],$value['Enero'],$value['Febrero'],$value['Marzo'],$value['Abril'],$value['Mayo'],$value['Junio'],$value['Julio'],$value['Agosto'],$value['Septiembre'],$value['Octubre'],$value['Noviembre'],$value['Diciembre'],$value['Total'],$value['Presupuesto'],$value['Diferencia'],$value['DG'],$value['TC']);
		    $tablaHTML[$pos]['estilo']='I';
		    $tablaHTML[$pos]['borde'] = '1';
		    $pos = $pos+1;
		}
	$pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$parametros['desde'],$parametros['hasta'],$sizetable,$mostrar,30,'L');
}

function agencias()
{
	$modelo = new contabilidad_model();
	return $modelo->agencias();
}

function listar_comprobantes($parametros)
{
	// print_r($parametros);die();
	$modelo = new contabilidad_model();
	$datos = $modelo->existe_comprobante($parametros['numero'],$parametros['TP'],$parametros['item']);
	// print_r($datos);die();
	// variables Co.
	   $T = '';
       $Fecha = '';
       $CodigoB = '';
       $Beneficiario = '';
       $Concepto = '';
       $Efectivo = '';
       $Cheque = '';
       $Cta_Banco='';
       // variables FA.
       $FA_ClaveAcceso = '';
       $FA_Estado_SRI = '';
       $FA_Autorizacion_R ='';
			 $Nombre_Completo = "";

	if(count($datos)>0)
	{
		 // LabelEst.Caption = "Normal"
       $T = $datos[0]["T"];
       $Fecha = $datos[0]["Fecha"]->format('Y-m-d');
       $CodigoB = $datos[0]["Codigo_B"];
       $Beneficiario = $datos[0]["Cliente"];
       $Concepto = $datos[0]["Concepto"];
       $Efectivo = $datos[0]["Efectivo"];
   		 $Nombre_Completo = $datos[0]["Nombre_Completo"];
       if($T == 'Anulado'){ $titulo = "Anulado";}
       // LabelFecha.Caption = Format(Co.Fecha, FormatoFechas)
       // LabelRecibi.Caption = Co.Beneficiario
       // LabelConcepto.Caption = Co.Concepto
       // LabelFormaPago.Caption = Co.Efectivo
       // LabelUsuario.Caption = " " & .Fields("Nombre_Completo")
       $ExisteComp = true;
	}else
	{
		return 2;
	}

	if($ExisteComp)
	{
		// 'Llenar Cuentas de Transacciones
		$tr = $modelo->transacciones_comprobante($parametros['TP'],$parametros['numero'],$parametros['item']);
        $SumaDebe = 0;
        $SumaHaber = 0;
        // print_r($tr);die();

		foreach ($tr['datos'] as $key => $value) {
             $SumaDebe = $SumaDebe + round($value["Debe"],2);
             $SumaHaber =$SumaHaber + round($value["Haber"],2);	
              if(is_numeric($value["Cheq_Dep"]) &&  $value["Haber"] > 0){
                $Cheque = $value["Cheq_Dep"];
                $Cta_Banco = $value["Cta"];
             }		
		}
	    // 'Llenar Inventario
	    $Total = 0;
        $Saldo = 0;
		$in = $modelo->inventario_comprobante($parametros['TP'],$parametros['numero'],$parametros['item']);
		foreach ($in['datos'] as $key => $value) {
              $Total = $Total + round($value["Valor_Total"], 2);
              $Saldo = $Saldo + round($value["Total"], 2);
           }
         $tbl3 = $modelo->subcuentas_comprobante($parametros['TP'],$parametros['numero'],$parametros['item']);

         // 'Listar las Retenciones
         $tbl2 = $modelo->retenciones_comprobantes($parametros['TP'],$parametros['numero'],$parametros['item']);
         //compras
         $co = $modelo->compras_comprobantes($parametros['TP'],$parametros['numero'],$parametros['item'],$Fecha);
         foreach ($co['datos'] as $key => $value) {
             $FA_ClaveAcceso = $value["Clave_Acceso"];
             $FA_Estado_SRI = $value["Estado_SRI"];
             $FA_Autorizacion_R =$value['AutRetencion'];
           }

           $tbl2_1 = $co['tbl'];
          // 'ventas           
         $tbl2_2 = $modelo->ventas_comprobantes($parametros['TP'],$parametros['numero'],$parametros['item'],$Fecha);

         $Co =array('T'=>$T,'fecha'=>$Fecha,'CodigoB'=>$CodigoB,'beneficiario'=>$Beneficiario,'Concepto'=>$Concepto,'Efectivo'=>$Efectivo,'Cheque'=>$Cheque,'Cta_Banco'=>$Cta_Banco,'TP'=>$parametros['TP'],'Numero'=>$parametros['numero'],'Item'=>$parametros['item']);
         // $Co = serialize($Co);
         // $Co = base64_encode($Co);
         // $Co = urlencode($Co);
		return array('tbl1'=>$tr['tbl'],'tbl4'=>$in['tbl'],'tbl3'=>$tbl3,'tbl2'=>$tbl2,'tbl2_1'=>$tbl2_1,'tbl2_2'=>$tbl2_2,'Debe'=>number_format($SumaDebe,2),'haber'=>number_format($SumaHaber,2),'total'=>number_format($Total,2),'saldo'=>number_format($Saldo,2),'beneficiario'=>$Beneficiario,'Co'=>$Co, 'Nombre_Completo' => $Nombre_Completo);	
	}
}
function comprobantes_procesados($parametros)
{
	// print_r($parametros);die();
	$modelo = new contabilidad_model();
	$datos = $modelo->comprobantes_procesados($parametros);
	// print_r($datos);die();
	$op = '<option value="">Seleccione</option>';
	if(count($datos)>0)
	{
	foreach ($datos as $key => $value) {
		$op.='<option value="'.$value['Numero'].'">'.$value['Numero'].'</option>';
	}
    }else
    {

		$op.='<option value="">No existen datos</option>';

    }

	return $op;

}
?>
