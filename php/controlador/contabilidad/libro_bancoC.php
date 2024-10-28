<?php 
include(dirname(__DIR__,2).'/modelo/contabilidad/libro_bancoM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
require(dirname(__DIR__,2).'/modelo/contabilidad/catalogoCtaM.php');

if(isset($_GET['cuentas']))
{
     $controlador = new libro_bancoC();
   
     	echo json_encode($controlador->cuentas());
     
}
if(isset($_GET['consultar']))
{	
   $controlador = new libro_bancoC();
  echo json_encode($controlador->ConsultarBanco($_POST['parametros']));
}
if(isset($_GET['consultar_tot']))
{	
  $controlador = new libro_bancoC();
  echo json_encode($controlador->Totales_banco($_POST['parametros']));
}
if(isset($_GET['imprimir_pdf']))
{
	 $parametros = array(
	 'CheckUsu' =>$_GET['CheckUsu'],
	 'CheckAgencia' =>$_GET['CheckAgencia'],
	 'desde' =>$_GET['desde'],
	 'hasta' =>$_GET['hasta'],
	 'DCAgencia' =>$_GET['DCAgencia'],
	 'DCUsuario' =>$_GET['DCUsuario'],
	 'DCCtas' =>$_GET['DCCtas']
	);
      $controlador = new libro_bancoC();
     $controlador->imprimir_pdf($parametros);
}
if(isset($_GET['imprimir_excel']))
{
	 $parametros = array(
	 'CheckUsu' =>$_GET['CheckUsu'],
	 'CheckAgencia' =>$_GET['CheckAgencia'],
	 'desde' =>$_GET['desde'],
	 'hasta' =>$_GET['hasta'],
	 'DCAgencia' =>$_GET['DCAgencia'],
	 'DCUsuario' =>$_GET['DCUsuario'],
	 'DCCtas' =>$_GET['DCCtas']
	);
      $controlador = new libro_bancoC();
     $controlador->imprimir_excel($parametros,$_GET['submodulo']);
}

class libro_bancoC
{
	private $modelo;
	private $pdf;
	function __construct()
	{
		$this->modelo = new libro_bancoM();
		$this->pdf = new cabecera_pdf();
	}

  function cuentas()
  {
  	$datos = $this->modelo->cuentas_();
  	return $datos;
  }
 

  function ConsultarBanco($parametros)
  {
  	$desde = str_replace('-','',$parametros['desde']);
    $hasta = str_replace('-','',$parametros['hasta']);		
  	return $this->modelo->consultar_banco_($desde,$hasta,$parametros['CheckAgencia'],$parametros['DCAgencia'],$parametros['CheckUsu'],$parametros['DCUsuario'],$parametros['DCCtas']);
  }
  function imprimir_pdf($parametros)
  {
  	$desde = str_replace('-','',$parametros['desde']);
		$hasta = str_replace('-','',$parametros['hasta']);

  	$datos = $this->modelo->consultar_banco_($desde,$hasta,$parametros['CheckAgencia'],$parametros['DCAgencia'],$parametros['CheckUsu'],$parametros['DCUsuario'],$parametros['DCCtas'], true);

		$titulo = 'L I B R O    B A N C O';
		$sizetable =7;
		$mostrar = TRUE;
		$Fechaini = $parametros['desde'];
		$Fechafin = $parametros['hasta'];
		$tablaHTML= array();
		
		$tablaHTML[0]['medidas']=array(40,235);
		$tablaHTML[0]['alineado']=array('L','L');
		$tablaHTML[0]['datos']=array('<b>CUENTA SUPERIOR','BANCO');
		//$tablaHTML[0]['estilo']='BIU';
		$tablaHTML[0]['borde'] =array('LT','RT');

		$tablaHTML[1]['medidas']=array(40,95,40,30,40,30);
		$tablaHTML[1]['alineado']=array('L','L','L');
		$tablaHTML[1]['datos']=array('<b>CUENTA ACTUAL',$parametros['DCCtas'],'<b>Saldo','','<b>Saldo','');
		//$tablaHTML[0]['estilo']='BIU';
		$tablaHTML[1]['borde'] =array('LB','B','B','B','B','RB');

		$tablaHTML[2]['medidas']=array(17,6,17,20,65,70,20,20,20,20);
		$tablaHTML[2]['alineado']=array('L','L','L','L','L','L','L','L');
		$tablaHTML[2]['datos']=array('FECHA','TD','NUMERO','CHEQ/DEP','BENEFICIARIO','CONCEPTO','PARCIAL_ME','DEBE','HABER','SALDO');
		$tablaHTML[2]['estilo']='BIU';
		$tablaHTML[2]['borde'] ='1';
		$pos = 3;
		$mes = 0;
		$fecha ='';
		$debe=0;
		$haber = 0;
		$saldo = 0;
		$ali = array('L','L','L','L','L','L','R','R','R','R');
		$borderow = 'LR';
		foreach ($datos as $key => $value) 
		{
			if($mes == 0)
			{
				$mes = $value['Fecha']->format('n');
				if($fecha == '')
				{
					$fecha = $value['Fecha']->format('Y-m-d');
				}else
				{
					if($fecha == $value['Fecha']->format('Y-m-d'))
					{
						$fecha ='';
					}else
					{
						$fecha = $value['Fecha']->format('Y-m-d');
					}
				}

			    $tablaHTML[$pos]['medidas']=$tablaHTML[2]['medidas'];
		        $tablaHTML[$pos]['alineado']=$ali;
		        $tablaHTML[$pos]['datos']=array($fecha,$value['TP'],$value['Numero'],$value['Cheq_Dep'],$value['Cliente'],$value['Concepto'],$value['Parcial_ME'],number_format($value['Debe'],2,'.',''),number_format($value['Haber'],2,'.',''),$value['Saldo']);
		        $tablaHTML[$pos]['borde'] ='LR';
		        $pos = $pos+1;
		        $fecha = $value['Fecha']->format('Y-m-d');
		        $debe+=$value['Debe']; $haber+=$value['Haber'];$saldo = $value['Saldo'];
		   }else
		   {
		   	  if($mes == $value['Fecha']->format('n'))
		      	{
		      		  if($fecha == '')
				       {
					     $fecha = $value['Fecha']->format('Y-m-d');
				       }else
				       {
					     if($fecha == $value['Fecha']->format('Y-m-d'))
					      {
						   $fecha ='';
					      }else
					      {
						    $fecha = $value['Fecha']->format('Y-m-d');				
					       }
				        }
				    
			    $tablaHTML[$pos]['medidas']=$tablaHTML[2]['medidas'];
		        $tablaHTML[$pos]['alineado']=$ali;
		        $tablaHTML[$pos]['datos']=array($fecha,$value['TP'],$value['Numero'],$value['Cheq_Dep'],$value['Cliente'],$value['Concepto'],$value['Parcial_ME'],number_format($value['Debe'],2,'.',''),number_format($value['Haber'],2,'.',''),$value['Saldo']);
		        $tablaHTML[$pos]['borde'] ='LR';
		         $pos = $pos+1;
		        $fecha = $value['Fecha']->format('Y-m-d');
		        $debe+=$value['Debe']; $haber+=$value['Haber'];$saldo = $value['Saldo'];
		            
		      	}else
		      	{
		      		$tablaHTML[$pos]['medidas']=array(125,70,20,20,20,20);
		            $tablaHTML[$pos]['alineado']=array('L','R','R','R','R','R');
		            $tablaHTML[$pos]['datos']=array('Fin de: '.mes_X_nombre($mes),'TOTALES','',$debe,$haber,$saldo);
		            $tablaHTML[$pos]['borde'] ='T';
		            $tablaHTML[$pos]['estilo']='BI';		
		            $pos = $pos+1;
		            $mes = $value['Fecha']->format('n');

		            $debe=0; $haber=0;$saldo=0;
		            $tablaHTML[$pos]['medidas']=array(125,70,20,20,20,20);
		            $tablaHTML[$pos]['alineado']=array('L','R','R','R','R','R');
		            $tablaHTML[$pos]['datos']=array('Inicio de : '.mes_X_nombre($mes),'','','','','');
		            $tablaHTML[$pos]['borde'] ='TB';
		            $tablaHTML[$pos]['estilo']='BI';
		            $pos = $pos+1;
		              if($fecha == '')
				       {
					     $fecha = $value['Fecha']->format('Y-m-d');
				       }else
				       {
					     if($fecha == $value['Fecha']->format('Y-m-d'))
					      {
						   $fecha ='';
					      }else
					      {
						    $fecha = $value['Fecha']->format('Y-m-d');				
					       }
				        }

		          
			    $tablaHTML[$pos]['medidas']=$tablaHTML[2]['medidas'];
		        $tablaHTML[$pos]['alineado']=$ali;
		        $tablaHTML[$pos]['datos']=array($fecha,$value['TP'],$value['Numero'],$value['Cheq_Dep'],$value['Cliente'],$value['Concepto'],$value['Parcial_ME'],number_format($value['Debe'],2,'.',''),number_format($value['Haber'],2,'.',''),$value['Saldo']);
		        $tablaHTML[$pos]['borde'] ='LR';
		         $pos = $pos+1;
		        $fecha = $value['Fecha']->format('Y-m-d');
		        $debe+=$value['Debe']; $haber+=$value['Haber'];$saldo = $value['Saldo'];


		      	}
		   }		   
		}
		$tablaHTML[$pos]['medidas']=array(125,70,20,20,20,20);
		            $tablaHTML[$pos]['alineado']=array('L','R','R','R','R','R');
		            $tablaHTML[$pos]['datos']=array('Fin de: '.mes_X_nombre($mes),'TOTALES','',$debe,$haber,$saldo);
		            $tablaHTML[$pos]['borde'] ='T';
		            $tablaHTML[$pos]['estilo']='BI';				

		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$parametros['desde'],$parametros['hasta'],$sizetable,$mostrar,25,'L');
  }

  function imprimir_excel($parametros,$sub)
  {
		$this->modelo->imprimir_excel_LibroBanco($parametros,$sub);
  }

  function Totales_banco($parametros)
  {
  	$desde = str_replace('-','',$parametros['desde']);
    $hasta = str_replace('-','',$parametros['hasta']);		
  	$datos = $this->modelo->consultar_banco_datos($desde,$hasta,$parametros['CheckAgencia'],$parametros['DCAgencia'],$parametros['CheckUsu'],$parametros['DCUsuario'],$parametros['DCCtas']);

	  $Debe = 0; $Haber = 0; $Saldo = 0;
	  $Debe_ME = 0;$Haber_ME = 0; $Saldo_ME = 0; $salAnt=0;
	  if(count($datos)>0)
	  {
	  	foreach ($datos as $key => $value) {
	  		 $Debe+= $value["Debe"];
	         $Haber+= $value["Haber"];
	         $Saldo = $value["Saldo"];
	         if($value["Parcial_ME"] >= 0)
	         {
	         	$Debe_ME+= $value["Parcial_ME"];
	         }else
	         {
	           $Haber_ME += $value["Parcial_ME"];
	         }
	       $Saldo_ME = $value["Saldo_ME"];  		
	  	}
	  }

	  $tipoCta = explode('.', $parametros['DCCtas']);
		if($tipoCta[0] == 1 || $tipoCta[0] == 5 || $tipoCta[0] == 7 || $tipoCta[0] == 9)
		{
		  $salAnt = $Saldo - $Debe + $Haber;
		}else
		{
			$vari = round($Haber-$Debe,2);
			$tot = round($Saldo,2);
			// print_r($vari);	
			// print_r('expression');
			// print_r($tot-$vari);
			$salAnt = $tot-$vari;
		 
		}

		$totales = array('Debe'=>round($Debe,2),'Haber'=>round($Haber,2),'Saldo'=>round($Saldo,2),'Debe_ME'=>round($Debe_ME,2),'Haber_ME'=>round($Haber_ME,2),'Saldo_ME'=>round($Saldo_ME,2),'SalAnt'=>round($salAnt,2),'SalAnt_'=>round($Saldo_ME-$Debe_ME+$Haber_ME));
		 return $totales;
   	 
  }
}

?>