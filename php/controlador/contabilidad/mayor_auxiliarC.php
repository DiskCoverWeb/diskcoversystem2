<?php 
include(dirname(__DIR__,2).'/modelo/contabilidad/mayor_auxiliarM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
require(dirname(__DIR__,2).'/modelo/contabilidad/catalogoCtaM.php');


if(isset($_GET['cuentas']))
{
     $controlador = new mayor_auxiliarC();
   
     	echo json_encode($controlador->cuentas($_POST['ini'],$_POST['fin']));
     
}
if(isset($_GET['consultar']))
{	
 $controlador = new mayor_auxiliarC();
  echo json_encode($controlador->consultarListarMayoresAux($_POST['parametros']));
}
if(isset($_GET['consultar_tot']))
{	
 $controlador = new mayor_auxiliarC();
  echo json_encode($controlador->Totales_modulo($_POST['parametros']));
}
if(isset($_GET['imprimir_pdf']))
{
	 $parametros = array(
	 'CheckUsu' =>$_GET['CheckUsu'],
	 'CheckAgencia' =>$_GET['CheckAgencia'],
	 'txt_CtaI' =>$_GET['txt_CtaI'],
	 'txt_CtaF' =>$_GET['txt_CtaF'],
	 'desde' =>$_GET['desde'],
	 'hasta' =>$_GET['hasta'],
	 'DCAgencia' =>$_GET['DCAgencia'],
	 'DCUsuario' =>$_GET['DCUsuario'],
	 'DCCtas' =>$_GET['DCCtas'],
	 'OpcUno' =>$_GET['OpcUno'],
	 'PorConceptos' =>$_GET['PorConceptos']
	);
     $controlador = new mayor_auxiliarC();
     $controlador->imprimir_pdf($parametros,$_GET['submodulo']);
}
if(isset($_GET['imprimir_excel']))
{
	 $parametros = array(
	 'CheckUsu' =>$_GET['CheckUsu'],
	 'CheckAgencia' =>$_GET['CheckAgencia'],
	 'txt_CtaI' =>$_GET['txt_CtaI'],
	 'txt_CtaF' =>$_GET['txt_CtaF'],
	 'desde' =>$_GET['desde'],
	 'hasta' =>$_GET['hasta'],
	 'DCAgencia' =>$_GET['DCAgencia'],
	 'DCUsuario' =>$_GET['DCUsuario'],
	 'DCCtas' =>$_GET['DCCtas'],
	 'OpcUno' =>$_GET['OpcUno'],
	 'PorConceptos' =>$_GET['PorConceptos']
	);
     $controlador = new mayor_auxiliarC();
     $controlador->imprimir_excel($parametros,$_GET['submodulo']);
}

class mayor_auxiliarC
{
	private $modelo;
	private $pdf;
	private $cataCta;
	function __construct()
	{
		$this->modelo = new mayor_auxiliarM();
		$this->pdf = new cabecera_pdf();
		$this->cataCta = new catalogoCtaM();
	}

  function cuentas($ini,$fin)
  {
  	return $this->modelo->cuentas_($ini,$fin);
  }
 

  function consultarListarMayoresAux($parametros)
  {
  	$desde = str_replace('-','',$parametros['desde']);
    $hasta = str_replace('-','',$parametros['hasta']);		
  	return $this->modelo->ListarMayoresAux($parametros['OpcUno'],$parametros['PorConceptos'],$parametros['txt_CtaI'],$parametros['txt_CtaF'],$desde,$hasta,$parametros['DCCtas'],$parametros['CheckAgencia'],$parametros['DCAgencia'],$parametros['CheckUsu'],$parametros['DCUsuario']);
  }
  function imprimir_pdf($parametros,$sub='false')
  {
  	    $desde = str_replace('-','',$parametros['desde']);
				$hasta = str_replace('-','',$parametros['hasta']);
	

  		$datos = $this->modelo->ListarMayoresAux($parametros['OpcUno'],$parametros['PorConceptos'],$parametros['txt_CtaI'],$parametros['txt_CtaF'],$desde,$hasta,$parametros['DCCtas'],$parametros['CheckAgencia'],$parametros['DCAgencia'],$parametros['CheckUsu'],$parametros['DCUsuario'], $soloMayorDatos = true);

       if($sub != 'false')
       {       	
  		$submodulo = $this->modelo->consultatr_submodulos($desde,$hasta,$parametros['CheckAgencia'],$parametros['DCAgencia'],$parametros['CheckUsu'],$parametros['DCUsuario']);
  	   }else
  	   {
  	   	$submodulo=array();
  	   }
  		//print_r($submodulo);
  		//print_r($parametros);
  		$cuenta = $this->cataCta->buscar_cuenta($parametros['DCCtas'],$parametros['DCAgencia']);

  		// print_r($cuenta);die();
  		//print_r($cuenta);


		$titulo = 'L I B R O    M A Y O R';
		$sizetable =7;
		$mostrar = TRUE;
		$Fechaini = $parametros['desde'] ;//str_replace('-','',$parametros['Fechaini']);
		$Fechafin = $parametros['hasta']; //str_replace('-','',$parametros['Fechafin']);
		$tablaHTML= array();
		
		$tablaHTML[0]['medidas']=array(20,75,20,75);
		$tablaHTML[0]['alineado']=array('L','L','L','L');
		$tablaHTML[0]['datos']=array('<B>CUENTA',$cuenta[0]['Codigo'].' '.$cuenta[0]['Cuenta'],'<B>GRUPO','EFECTIVO Y EQUIVALENTES DE EFECTIVO');
		//$tablaHTML[0]['estilo']='BIU';
		$tablaHTML[0]['borde'] =array('LT','T','T','RT');

		$tablaHTML[1]['medidas']=array(90,30,70);
		$tablaHTML[1]['alineado']=array('L','L','L');
		$tablaHTML[1]['datos']=array('','<B>Saldo anterior $:','');
		//$tablaHTML[0]['estilo']='BIU';
		$tablaHTML[1]['borde'] =array('LB','B','RB');

		$tablaHTML[2]['medidas']=array(17,6,17,70,20,20,20,20);
		$tablaHTML[2]['alineado']=array('L','L','L','L','L','L','L','L');
		$tablaHTML[2]['datos']=array('FECHA','TD','NUMERO','CONCEPTO','PARCIAL_ME','DEBE','HABER','SALDO');
		$tablaHTML[2]['estilo']='BIU';
		$tablaHTML[2]['borde'] ='1';
		$pos = 3;
		$mes = 0;
		$fecha ='';
		$debe=0;
		$haber = 0;
		$saldo = 0;
		$p='R';			

		$borderow = 'LR';
		foreach ($datos as $key => $value) {
			
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
		        $tablaHTML[$pos]['alineado']=array('L','L','L','L','R','R','R','R');
		        $tablaHTML[$pos]['datos']=array($fecha,$value['TP'],$value['Numero'],'<B>'.$value['Cliente'],'','','','');
		        $tablaHTML[$pos]['borde'] =$borderow;
		        $pos = $pos+1;
		        $tablaHTML[$pos]['medidas']=$tablaHTML[2]['medidas'];
		        $tablaHTML[$pos]['alineado']=array('L','L','L','L','R','R','R','R');
		        $tablaHTML[$pos]['datos']=array('','','',$value['Concepto'],'',number_format($value['Debe'],2,'.',''),number_format($value['Haber'],2,'.',''),$value['Saldo']);
		        $tablaHTML[$pos]['borde'] =$borderow;
		        $pos = $pos+1;
		        $fecha = $value['Fecha']->format('Y-m-d');
		        $debe+=$value['Debe']; $haber+=$value['Haber'];$saldo += $value['Saldo'];
		        foreach ($submodulo as $key => $value1) {
		        	if($value1['Numero'] == $value['Numero'])
		        	{

		        		if($value1['Debitos'] == '.0000')
		        		{
		        			$p='R';
		        			$parcial = $value1['Creditos'];
		        		}else
		        		{
		        			$p='L';
		        			$parcial = $value1['Debitos'];
		        		}
		                 $tablaHTML[$pos]['medidas']=$tablaHTML[2]['medidas'];
		                 $tablaHTML[$pos]['alineado']=array('L','L','L','L',$p,'R','R','R');
		                 $tablaHTML[$pos]['datos']=array('','','','<i>  *'.$value1['Cliente'],$parcial,'','','');
		                 $tablaHTML[$pos]['borde'] =$borderow;
		                 $pos = $pos+1;
		        	}
		        }
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
		            $tablaHTML[$pos]['alineado']=array('L','L','L','L','R','R','R','R');
		            $tablaHTML[$pos]['datos']=array($fecha,$value['TP'],$value['Numero'],'<B>'.$value['Cliente'],'','','','');
		            $tablaHTML[$pos]['borde'] =$borderow;
		            $pos = $pos+1;
		            $tablaHTML[$pos]['medidas']=$tablaHTML[2]['medidas'];
		            $tablaHTML[$pos]['alineado']=array('L','L','L','L','R','R','R','R');
		            $tablaHTML[$pos]['datos']=array('','','',$value['Concepto'],'',number_format($value['Debe'],2,'.',''),number_format($value['Haber'],2,'.',''),$value['Saldo']);
		            $tablaHTML[$pos]['borde'] =$borderow;
		            $pos = $pos+1;
		            $fecha = $value['Fecha']->format('Y-m-d');
		            $debe+=$value['Debe']; $haber+=$value['Haber'];$saldo += $value['Saldo'];
		             foreach ($submodulo as $key => $value1) {
		        	if($value1['Numero'] == $value['Numero'])
		        	{

		        		if($value1['Debitos'] == '.0000')
		        		{
		        			$p='R';
		        			$parcial = $value1['Creditos'];
		        		}else
		        		{
		        			$p='L';
		        			$parcial = $value1['Debitos'];
		        		}
		                 $tablaHTML[$pos]['medidas']=$tablaHTML[2]['medidas'];
		                 $tablaHTML[$pos]['alineado']=array('L','L','L','L',$p,'R','R','R');
		                 $tablaHTML[$pos]['datos']=array('','','','<I>  *'.$value1['Cliente'],$parcial,'','','');
		                 $tablaHTML[$pos]['borde'] =$borderow;
		                 $pos = $pos+1;
		        	}
		        }


		      	}else{
		      	    $tablaHTML[$pos]['medidas']=array(39,70,20,20,20,20);
		            $tablaHTML[$pos]['alineado']=array('L','R','R','R','R','R');
		            $tablaHTML[$pos]['datos']=array('Fin de: '.mes_X_nombre($mes),'TOTALES','',$debe,$haber,$saldo);
		            $tablaHTML[$pos]['borde'] ='T';
		            $tablaHTML[$pos]['estilo']='BI';		
		            $pos = $pos+1;
		            $mes = $value['Fecha']->format('n');

		            $debe=0; $haber=0;$saldo=0;
		            $tablaHTML[$pos]['medidas']=array(39,70,20,20,20,20);
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
		          $tablaHTML[$pos]['alineado']=array('L','L','L','L','R','R','R','R');
		          $tablaHTML[$pos]['datos']=array($fecha,$value['TP'],$value['Numero'],'<B>'.$value['Cliente'],'','','','');
		          $tablaHTML[$pos]['borde'] ='LRT';
		          $pos = $pos+1;
		          $tablaHTML[$pos]['medidas']=$tablaHTML[2]['medidas'];
		          $tablaHTML[$pos]['alineado']=array('L','L','L','L','R','R','R','R');
		          $tablaHTML[$pos]['datos']=array('','','',$value['Concepto'],'',number_format($value['Debe'],2,'.',''),number_format($value['Haber'],2,'.',''),$value['Saldo']);
		          $tablaHTML[$pos]['borde'] =$borderow;		          
		          $pos = $pos+1;
		           foreach ($submodulo as $key => $value1) {
		        	if($value1['Numero'] == $value['Numero'])
		        	{
		        		if($value1['Debitos'] == '.0000')
		        		{
		        			$p='R';
		        			$parcial = $value1['Creditos'];
		        		}else
		        		{
		        			$p='L';
		        			$parcial = $value1['Debitos'];
		        		}
		                 $tablaHTML[$pos]['medidas']=$tablaHTML[2]['medidas'];
		                 $tablaHTML[$pos]['alineado']=array('L','L','L','L',$p,'R','R','R');
		                 $tablaHTML[$pos]['datos']=array('','','','<I>  *'.$value1['Cliente'],$parcial,'','','');
		                 $tablaHTML[$pos]['borde'] =$borderow;
		                 $pos = $pos+1;
		        	}
		        }
		          $pos = $pos+1;  
		          $debe+=$value['Debe']; $haber+=$value['Haber'];$saldo += $value['Saldo'];

		      	}
		      	

		    }

		
		}
		$tablaHTML[$pos]['medidas']=array(39,70,20,20,20,20);
		$tablaHTML[$pos]['alineado']=array('L','R','R','R','R','R');
		$tablaHTML[$pos]['datos']=array('Fin de: '.mes_X_nombre($mes),'TOTALES','',$debe,$haber,$saldo);
		$tablaHTML[$pos]['borde'] ='T';
		$tablaHTML[$pos]['estilo']='BI';		
		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$desde,$hasta,$sizetable,$mostrar,25);
  }

  function imprimir_excel($parametros,$sub)
  {
  	
		$this->modelo->exportar_excel($parametros,$sub);

  }

  function Totales_modulo($parametros)
  {

  	 $Suma_ME = 0;
     $SumaDebe = 0;
     $SumaHaber = 0;
     $Cta = '';
     $SaldoTotal  = 0;
     $salAnt=0;


  	 $desde = str_replace('-','',$parametros['desde']);
	 $hasta = str_replace('-','',$parametros['hasta']);

  	$datos = $this->modelo->consultar_cuentas_datos($parametros['OpcUno'],$parametros['PorConceptos'],$parametros['txt_CtaI'],$parametros['txt_CtaF'],$desde,$hasta,$parametros['DCCtas'],$parametros['CheckAgencia'],$parametros['DCAgencia'],$parametros['CheckUsu'],$parametros['DCUsuario']);
  	$totales = $this->modelo->consulta_totales($parametros['OpcUno'],$parametros['PorConceptos'],$parametros['txt_CtaI'],$parametros['txt_CtaF'],$desde,$hasta,$parametros['DCCtas'],$parametros['CheckAgencia'],$parametros['DCAgencia'],$parametros['CheckUsu'],$parametros['DCUsuario']);

$i = count($datos);
if(count($totales) > 0)
{
	foreach ($totales as $key => $value) {
		  $Cta = $value["Cta"];
          $Suma_ME = $Suma_ME + $value["TParcial_ME"];
          $SumaDebe = $SumaDebe + $value["TDebe"];
          $SumaHaber = $SumaHaber + $value["THaber"];	
	}
//obtener el ultimo saldo de la cta
	if(count($totales) == 1)
	{
		if(count($datos) > 0)
		{
			$SaldoTotal = $datos[$i-1]["Saldo"];
		}

	}
}
$tipoCta = explode('.', $Cta);
//print_r($tipoCta);
if($tipoCta[0] == 1 || $tipoCta[0] == 5 || $tipoCta[0] == 7 || $tipoCta[0] == 9)
{
  $salAnt = $SaldoTotal - $SumaDebe + $SumaHaber;
}else
{
	$vari = round($SumaHaber-$SumaDebe,2);
	$tot = round($SaldoTotal,2);
	// print_r($vari);	
	// print_r('expression');
	// print_r($tot-$vari);
	$salAnt = $tot-$vari;
 
}

$totales = array('Suma_ME'=>$Suma_ME,'SumaDebe'=>$SumaDebe,'SumaHaber'=>$SumaHaber,'SaldoTotal'=>$SaldoTotal,'SalAnt'=>$salAnt);

return $totales;
  }
}

?>