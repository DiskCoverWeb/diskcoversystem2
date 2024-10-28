<?php
//Llamada al modelo
require_once("../modelo/facturacion/facturacion_model.php");
require_once("../modelo/usuario_model.php");

if(isset($_POST['submitlog'])) 
{
	login('', '', '');
}

if(isset($_GET['autorizar'])==true) 
{
	autorizar();
}

//para autorizar facturas
function autorizar()
{
	echo " Entro ";
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
			//echo ' dd '.$OpcDG;
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
	$per=new contabilidad_model();
	//echo "ListarAsientoSQL";
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		//hacemos conexion en sql
		$per->conexionSQL();
		//echo ' dd '.$OpcDG;
		$balance=$per->sp_erroresSQL($item,$modulo,$id);
		$per->cerrarSQLSERVER();
	}
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		$balance=$per->sp_erroresMYSQL($item,$modulo,$id);
	}
	return $balance;
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
?>
