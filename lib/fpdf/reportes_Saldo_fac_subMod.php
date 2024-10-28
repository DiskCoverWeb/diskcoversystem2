<?php
date_default_timezone_set('America/Guayaquil');
//require('html_table.php');
require('cabecera_pdf.php');
include(dirname(__DIR__,2)."/php/modelo/contabilidad/saldo_fac_submoduloM.php");
//include(dirname(__DIR__,2)."/php/db/db.php");
//echo dirname(__DIR__,1);

/**
 * 
 */

if(isset($_GET['Mostrar_pdf']))
{
	$parametros=array(
		    'tipocuenta'=>$_GET['tipocuenta'],
			'ChecksubCta'=>$_GET['ChecksubCta'],
			'OpcP'=>$_GET['OpcP'],
			'CheqCta'=>$_GET['CheqCta'],
			'CheqDet'=>$_GET['CheqDet'],
			'CheqIndiv'=>$_GET['CheqIndiv'],
			'fechaini'=>$_GET['fechaini'],
			'fechafin'=>$_GET['fechafin'],
			'Cta'=>$_GET['Cta'],
			'CodigoCli'=>$_GET['CodigoCli'],
			'DCDet'=>$_GET['DCDet'],
			//'tipo'=>$_GET['tipo'],

	);
	$reporte = new Reporte_subModulo();
	if($_GET['tabla']=='normal')
	{
	    if($_GET['tipocuenta'] == 'C' || $_GET['tipocuenta']=='P')
	    {
		    $reporte->cuentas_x_pagar($parametros);

	    }else
	    {
		    $reporte->egresos_centro_c($parametros);

	    }
   }else
   {
   	 if($_GET['tipocuenta'] == 'C' || $_GET['tipocuenta']=='P')
	    {
	    	$reporte->reporte_temporizado($parametros);

	    }else
	    {
		    $reporte->reporte_temporizado_egreso($parametros);

	    }
   }

}


if(isset($_GET['Mostrar_excel']))
{
	$parametros=array(
		    'tipocuenta'=>$_GET['tipocuenta'],
			'ChecksubCta'=>$_GET['ChecksubCta'],
			'OpcP'=>$_GET['OpcP'],
			'CheqCta'=>$_GET['CheqCta'],
			'CheqDet'=>$_GET['CheqDet'],
			'CheqIndiv'=>$_GET['CheqIndiv'],
			'fechaini'=>$_GET['fechaini'],
			'fechafin'=>$_GET['fechafin'],
			'Cta'=>$_GET['Cta'],
			'CodigoCli'=>$_GET['CodigoCli'],
			'DCDet'=>$_GET['DCDet'],
			//'tipo'=>$_GET['tipo'],

	);
	$reporte = new Reporte_subModulo();
	if($_GET['tabla']=='normal')
	{
	    if($_GET['tipocuenta'] == 'C' || $_GET['tipocuenta']=='P')
	    {
		    $reporte->cuentas_x_pagar_excel($parametros);

	    }else
	    {
		    $reporte->egresos_centro_c_excel($parametros);

	    }
   }else
   {
   	
	  $reporte->reporte_temporizado_excel($parametros);	   
   }
}

if(isset($_GET['excel_submodulo_mes']))
{
	$parametros=array(
		    'tipocuenta'=>$_GET['tipocuenta'],
			'ChecksubCta'=>$_GET['ChecksubCta'],
			'CheqCta'=>$_GET['CheqCta'],
			'fechafin'=>$_GET['fechafin'],
			'fechaini'=>$_GET['fechaini'],
			'Cta'=>$_GET['Cta'],
			//'tipo'=>$_GET['tipo'],
	);
	$reporte = new Reporte_subModulo();
	$reporte->reporte_excel_sub_modulo_mes($parametros);
}


if(isset($_GET['pdf_submodulo_mes']))
{
	$parametros=array(
		    'tipocuenta'=>$_GET['tipocuenta'],
			'ChecksubCta'=>$_GET['ChecksubCta'],
			'CheqCta'=>$_GET['CheqCta'],
			'fechafin'=>$_GET['fechafin'],
			'fechaini'=>$_GET['fechaini'],
			'Cta'=>$_GET['Cta'],
			//'tipo'=>$_GET['tipo'],
	);
	$reporte = new Reporte_subModulo();
	$reporte->reporte_pdf_sub_modulo_mes($parametros);
}

class Reporte_subModulo 
{
	private $pdf;
	private $ModeloSubModulo;
	private $conn;

	function __construct()
	{
		 
	    $this->pdf = new cabecera_pdf();
		$this->ModeloSubModulo = new Saldo_fac_sub_C();
		$this->conn = new  db();
		
	}	


	function reporte_excel_sub_modulo_mes($parametros)
	{
		$resultado = explode(' ',$parametros['Cta']);
		$cta = $resultado[0];
		$fechafin = str_replace("-","",$parametros['fechafin']);
		$fechaini = str_replace("-","",$parametros['fechaini']);
		$datos = $this->ModeloSubModulo->cargar_consulta_x_meses();
		$titulo = str_replace($cta,'', $parametros['Cta']);
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(51,10,25,60);
		$tablaHTML[0]['datos']=array('PROVEEDOR','MES','VALOR POR MES','CATEGORIA');
		$tablaHTML[0]['tipo'] ='C';

		$pos = 1;
		$bene = '';
		$total = 0;
		foreach ($datos as $key => $value) {
			
			if($value['Anio']!='TOTAL')
			{
				if($value['Beneficiario']==$bene){$prove = '';}else{$prove = $value['Beneficiario'];}
				$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
				$tablaHTML[$pos]['datos']=array($prove,$value['Mes'],number_format($value['Valor_x_Mes'],2,'.',''),$value['Categoria']);
				$tablaHTML[$pos]['tipo'] ='N';
			}else
			{
				$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
				$tablaHTML[$pos]['datos']=array('TOTAL','',number_format($value['Valor_x_Mes'],2,'.',''),'');
				$tablaHTML[$pos]['tipo'] ='SUBR';

				$pos=$pos+1;
				$tablaHTML[$pos]['medidas']=array(51);
				$tablaHTML[$pos]['datos']=array('');
				$tablaHTML[$pos]['tipo'] ='N';
				$total+=$value['Valor_x_Mes'];

				// $tablaHTML[$pos]['borde'] = 'B';

			}
			$pos=$pos+1;
			$bene = $value['Beneficiario'];
		}
		$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		$tablaHTML[$pos]['datos']=array($titulo,'',number_format($total,2,'.',''),'');
		$tablaHTML[$pos]['tipo'] ='SUBR';
      excel_generico($titulo,$tablaHTML);  


	}


	function reporte_pdf_sub_modulo_mes($parametros)
	{
		$resultado = explode(' ',$parametros['Cta']);
		$cta = $resultado[0];
		$fechafin = str_replace("-","",$parametros['fechafin']);
		$fechaini = str_replace("-","",$parametros['fechaini']);
		$datos = $this->ModeloSubModulo->cargar_consulta_x_meses();
		$titulo = 'Reporte '.str_replace($cta,'', $parametros['Cta']);
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(80,10,10,25,60);
		$tablaHTML[0]['alineado']=array('L','L','L','R','L');
		$tablaHTML[0]['datos']=array('PROVEEDOR','Anio','MES','VALOR POR MES','CATEGORIA');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = 'B';

		$pos = 1;
		$bene = '';
		$total = 0;
		foreach ($datos as $key => $value) {
			
			if($value['Anio']!='TOTAL')
			{
				if($value['Beneficiario']==$bene){$prove = '';}else{$prove = $value['Beneficiario'];}
				$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
				$tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
				$tablaHTML[$pos]['datos']=array($prove.'<b>',$value['Anio'],$value['Mes'],number_format($value['Valor_x_Mes'],2,'.',''),$value['Categoria']);
				// $tablaHTML[$pos]['estilo']='BI';
				// $tablaHTML[$pos]['borde'] = 0;
			}else
			{
				$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
				$tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
				$tablaHTML[$pos]['datos']=array('','TOTAL','',number_format($value['Valor_x_Mes'],2,'.',''),'');
				$tablaHTML[$pos]['estilo']='B';
				$tablaHTML[$pos]['borde'] = 'TB';

				$total+=$value['Valor_x_Mes'];

				$pos=$pos+1;
				$tablaHTML[$pos]['medidas']=array(185);
				$tablaHTML[$pos]['alineado']=array('L');
				$tablaHTML[$pos]['datos']=array('');
				$tablaHTML[$pos]['borde'] = 'B';

			}
			$pos=$pos+1;
			$bene = $value['Beneficiario'];
		}

		$tablaHTML[$pos]['medidas']=array(90,10,25,60);
		$tablaHTML[$pos]['alineado']=array('L','L','R','L');
		$tablaHTML[$pos]['datos']=array('TOTAL '.$titulo,'',number_format($total,2,'.',''),'');
		$tablaHTML[$pos]['estilo']='B';
		$tablaHTML[$pos]['borde'] = 'TB';



		// print_r($datos);die();

		 $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$imagen=false,$fechaini,$fechafin,6,true);

	}

	function cuentas_x_pagar($parametro) {
		$datos = $this->ModeloSubModulo->consulta_c_p_datos(
			$parametro['tipocuenta'],
			$parametro['ChecksubCta'],
			$parametro['OpcP'],
			$parametro['CheqCta'],
			$parametro['CheqDet'],
			$parametro['CheqIndiv'],
			$parametro['fechaini'],
			$parametro['fechafin'],
			$parametro['Cta'],
			$parametro['CodigoCli'],
			$parametro['DCDet']
		);
	
		$titulo = '';
		if ($parametro['tipocuenta'] == 'P') {
			$titulo = 'SALDO DE CUENTAS POR PAGAR';
		} else {
			$titulo = 'SALDO DE CUENTAS POR COBRAR';
		}
	
		$cuentas = array();
		$clientes = array();
		$totalSaldo = 0; $totalabono = 0; $totalTotal = 0;
	
		foreach ($datos as $key => $value) {
			if (!in_array($value['Cuenta'], $cuentas)) {
				$cuentas[] = $value['Cuenta'];
			}
			if (!in_array($value['Cliente'], $clientes)) {
				$clientes[] = $value['Cliente'];
			}
		}
	
		$abono = 0; $saldo = 0; $total = 0;
		$pos = 1;
		$tablaHTML = array();
		$tablaHTML[0]['medidas'] = array(60, 19, 15, 7, 19, 15, 15, 15, 15, 15);
		$tablaHTML[0]['alineado'] = array('L', 'L', 'L', 'L', 'L', 'L', 'L', 'R', 'R', 'R');
		$tablaHTML[0]['datos'] = array('PERSONAS', 'TELEFONO', 'FACTURA', 'TP', 'NUMERO', 'FECHA', 'FECHA V', 'TOTAL', 'ABONO', 'SALDO');
		$tablaHTML[0]['estilo'] = 'BI';
		$tablaHTML[0]['borde'] = 'B';
	
		$clientesVistos = array();
		foreach ($cuentas as $key => $cuent) {
			$tablaHTML[$pos]['medidas'] = array(195);
			$tablaHTML[$pos]['alineado'] = array('L');
			$tablaHTML[$pos]['datos'] = array(strtoupper($cuent) .':');	
			$tablaHTML[$pos]['borde'] = 'B';
			$pos++;
	
			$clienteTemporal = '';
			$saldoC = 0; $totalC = 0; $abonoC = 0;
	
			foreach ($datos as $key => $value) {
				if ($cuent == $value['Cuenta']) {					
					if ($clienteTemporal != $value['Cliente']) {
						//$clientesVistos[] = $value['Cliente'];
						if ($clienteTemporal != '') {
							
							$tablaHTML[$pos]['medidas'] = array(60,90, 15, 15, 15);
							$tablaHTML[$pos]['alineado'] = array('L','L', 'R', 'R', 'R');
							$tablaHTML[$pos]['datos'] = array('','SUBTOTAL DE ' . $clienteTemporal, $totalC, $abonoC, $saldoC);
							$tablaHTML[$pos]['borde'] = 'BT';
							$pos += 2;
						}
						$clienteTemporal = $value['Cliente'];
						$saldoC = 0; $totalC = 0; $abonoC = 0;
					}
					if (!in_array($clienteTemporal, $clientesVistos)) {
						$clientesVistos[] = $clienteTemporal;
	
						$saldoC += $value['Saldo'];
						$totalC += $value['Total'];
						$abonoC += $value['Abonos'];
						$saldo += $value['Saldo'];
						$total += $value['Total'];
						$abono += $value['Abonos'];
	
						$tablaHTML[$pos]['medidas'] = $tablaHTML[0]['medidas'];
						$tablaHTML[$pos]['alineado'] = $tablaHTML[0]['alineado'];
						$tablaHTML[$pos]['datos'] = array(
							$clienteTemporal,
							$value['Telefono'],
							$value['Factura'],
							$value['TC'],
							$value['Codigo'],
							$value['Fecha_Emi']->format('Y-m-d'),
							$value['Fecha_Ven']->format('Y-m-d'),
							bcdiv($value['Total'], 1, 2),
							bcdiv($value['Abonos'], 1, 2),
							bcdiv($value['Saldo'], 1, 2)
						);
						$tablaHTML[$pos]['borde'] = 'RL';
						$pos++;
					} else {
						$saldoC += $value['Saldo'];
						$totalC += $value['Total'];
						$abonoC += $value['Abonos'];
						$saldo += $value['Saldo'];
						$total += $value['Total'];
						$abono += $value['Abonos'];
	
						$tablaHTML[$pos]['medidas'] = $tablaHTML[0]['medidas'];
						$tablaHTML[$pos]['alineado'] = $tablaHTML[0]['alineado'];
						$tablaHTML[$pos]['datos'] = array(
							'',
							$value['Telefono'],
							$value['Factura'],
							$value['TC'],
							$value['Codigo'],
							$value['Fecha_Emi']->format('Y-m-d'),
							$value['Fecha_Ven']->format('Y-m-d'),
							bcdiv($value['Total'], 1, 2),
							bcdiv($value['Abonos'], 1, 2),
							bcdiv($value['Saldo'], 1, 2)
						);
						$tablaHTML[$pos]['borde'] = 'RL';
						$pos++;
					}	
				}
			}
	
			if ($clienteTemporal != '') {
				$tablaHTML[$pos]['medidas'] = array(60,90, 15, 15, 15);
				$tablaHTML[$pos]['alineado'] = array('L','L', 'R', 'R', 'R');
				$tablaHTML[$pos]['datos'] = array('','SUBTOTAL DE ' . $clienteTemporal, $totalC, $abonoC, $saldoC);
				$tablaHTML[$pos]['borde'] = 'T';
				$pos += 2;
			}
	
			$tablaHTML[$pos]['medidas'] = array(60,90, 15, 15, 15);
			$tablaHTML[$pos]['alineado'] = array('L','L', 'R', 'R', 'R');
			$tablaHTML[$pos]['datos'] = array('','SUBTOTAL ' . strtoupper($cuent), $total, $abono, $saldo);
			$tablaHTML[$pos]['borde'] = 'B';
			$pos = $pos + 2;
	
			$totalSaldo += $saldo;
			$totalabono += $abono;
			$totalTotal += $total;
		}
	
		$tablaHTML[$pos]['medidas']=array(135,20,20,20);
		$tablaHTML[$pos]['alineado']=array('R','R','R','R');
		$tablaHTML[$pos]['datos']=array('TOTAL',$totalTotal,$totalabono,$totalSaldo);
		$tablaHTML[$pos]['borde'] = 'BT';
	
			


	 $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$imagen=false,$parametro['fechaini'],$parametro['fechafin'],6,true);
			
	} 
	
	function egresos_centro_c($parametro)
	{
		date_default_timezone_set('America/Guayaquil');
		$datos = $this->ModeloSubModulo->consulta_ing_egre_datos(
			$parametro['tipocuenta'],
			$parametro['ChecksubCta'],
			$parametro['OpcP'],
			$parametro['CheqCta'],
			$parametro['CheqDet'],
			$parametro['CheqIndiv'],
			$parametro['fechaini'],
			$parametro['fechafin'],
			$parametro['Cta'],
			$parametro['CodigoCli'],
			$parametro['DCDet']);		
		  $titulo='';

		if($parametro['tipocuenta']=='G')
		{
			$titulo = 'SALDO DE EGRESO';
	    }else
	    {
	    	$titulo='SALDO DE INGRESOS';    	
	    }


		 $submodulo = array();
		 $totalTotal=0;
		 foreach ($datos as $key => $value) {

		 	if(!in_array($value['Sub_Modulos'],$submodulo))
		 	{
		 		$submodulo[]=$value['Sub_Modulos'];
		 	}
		 }

		 $total=0;

		$pos=1;
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(40,40,90,20);
		$tablaHTML[0]['alineado']=array('L','L','L','R');
		$tablaHTML[0]['datos']=array('SUBCUENTA','CUENTA','DETALLE','SUBTOTAL');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = 'B';


		foreach ($submodulo as $key => $val) {
			$subcuenta = '';
			foreach ($datos as $key => $value) {
					$total+=$value['Total'];
			  if($value['Sub_Modulos']==$val)
				{
					if($subcuenta == '')
					{
						$subcuenta = $value['Sub_Modulos'];
					}else
					{
					  $subcuenta = '';						
					}
			        $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		            $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		            $tablaHTML[$pos]['datos']=array($subcuenta,$value['Cta'],$value['Cuenta'],$value['Total']);
		            $tablaHTML[$pos]['borde'] = 'R';
                    $pos = $pos+1;

			    $subcuenta = $value['Sub_Modulos'];
			   }

		     }
		            $tablaHTML[$pos]['medidas']=array(170,20);
		            $tablaHTML[$pos]['alineado']=array('R','R');
		            $tablaHTML[$pos]['datos']=array('SUBTOTAL',$total);
		            $tablaHTML[$pos]['borde'] = 'BT';
		            $pos = $pos+1;
		     $totalTotal+=$total;
			}				
				
		             $tablaHTML[$pos]['medidas']=array(170,20);
		            $tablaHTML[$pos]['alineado']=array('R','R');		            
		            $tablaHTML[$pos]['datos']=array('TOTAL',$totalTotal);
		            $tablaHTML[$pos]['borde'] = 'BT';
		            $tablaHTML[$pos]['estilo']='BI';
		            $pos = $pos+1;

		  

	 $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$imagen=false,$parametro['fechaini'],$parametro['fechafin'],7,true);

	}

	function reporte_temporizado($parametro)
	{
		$datos = $this->ModeloSubModulo->tabla_temporizada_datos($parametro['fechafin']);

		
		  $titulo='';

		
		if($parametro['tipocuenta']=='P')
		{
			$titulo='SALDO DE CUENTAS POR PAGAR TEMPORIZADO';
		}else
		{
			$titulo ='SALDO DE CUENTAS POR COBRAR TEMPORIZADO';
		}
		
		 $cuentas = array();
		 foreach ($datos as $key => $value) {
		 	if(!in_array($value['Cuenta'],$cuentas))
		 	{
		 		$cuentas[]=$value['Cuenta'];
		 	}
		 }

		        $total_1=0;$totalTotal_1=0;
		        $total_2=0;$totalTotal_2=0;
		        $total_3=0;$totalTotal_3=0;
		        $total_4=0;$totalTotal_4=0;
		        $total_5=0;$totalTotal_5=0;
		        $total_6=0;$totalTotal_6=0;
		        $total_7=0;$totalTotal_7=0;
		        $pos=1;
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(17,22,17,15,14,16,17,17,18,20,20);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L','L','L','R','R','R');
		$tablaHTML[0]['datos']=array('CUENTA','CLIENTE','FECHA VEN','FACTURA','VEN 1 A 7','VEN 8 A 30','VEN 31 A 60','VEN 61 A 90','VEN 91 A 180','VEN 181 A 360','VEN + DE 360');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = 'B';

		   foreach ($cuentas as $key => $val) {
		   	$cuent='';		   
			foreach ($datos as $key => $value) {
				$total_1+=$value['Ven 1 a 7'];
				$total_2+=$value['Ven 8 a 30'];
				$total_3+=$value['Ven 31 a 60'];
				$total_4+=$value['Ven 61 a 90'];
				$total_5+=$value['Ven 91 a 180'];
				$total_6+=$value['Ven 181 a 360'];
				$total_7+=$value['Ven mas de 360'];

			  if($value['Cuenta']==$val)
			  {
			  	if($cuent == '')
			    {
				    $cuent = $value['Cuenta'];
			    }else
			    {
					$cuent = '';						
			    }

		$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		$tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		$tablaHTML[$pos]['datos']=array($cuent,$value['Cliente'],$value['Fecha_Venc']->format('Y-m-d'),$value['Factura'],bcdiv($value['Ven 1 a 7'],1,2), bcdiv($value['Ven 8 a 30'],1,2),bcdiv($value['Ven 31 a 60'],1,2),bcdiv($value['Ven 61 a 90'],1,2),bcdiv($value['Ven 91 a 180'],1,2),bcdiv($value['Ven 181 a 360'],1,2),bcdiv($value['Ven mas de 360'],1,2));
		$tablaHTML[$pos]['borde'] = 'R';

		   $cuent = $value['Cuenta'];
		   $pos = $pos+1;
		   }
		  }
		     $tablaHTML[$pos]['medidas']=array(71,14,16,17,17,18,20,20);
		     $tablaHTML[$pos]['alineado']=array('R','R','R','R','R','R','R','R');
		     $tablaHTML[$pos]['datos']=array('SUB TOTAL',bcdiv($total_1,1,2),bcdiv($total_2,1,2),bcdiv($total_3,1,2),bcdiv($total_4,1,2),bcdiv($total_5,1,2),bcdiv($total_6,1,2),bcdiv($total_7,1,2));
		     $tablaHTML[$pos]['borde'] = 'BT';
		   $pos = $pos+1;

		   $totalTotal_1+=$total_1;   $totalTotal_2+=$total_2;  $totalTotal_3+=$total_3;  $totalTotal_4+=$total_4;  $totalTotal_5+=$total_5;
		   $totalTotal_6+=$total_6;	   $totalTotal_7+=$total_7;
		}
		     $tablaHTML[$pos]['medidas']=array(71,14,16,17,17,18,20,20);
		     $tablaHTML[$pos]['alineado']=array('R','R','R','R','R','R','R','R');
		     $tablaHTML[$pos]['datos']=array('TOTAL',bcdiv($totalTotal_1,1,2),bcdiv($totalTotal_2,1,2),bcdiv($totalTotal_3,1,2),bcdiv($totalTotal_4,1,2),bcdiv($totalTotal_5,1,2),bcdiv($totalTotal_6,1,2),bcdiv($totalTotal_7,1,2),);
		     $tablaHTML[$pos]['borde'] = 'BT';
		     $tablaHTML[$pos]['estilo']='BI';
		   $pos = $pos+1;


	 $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$imagen=false,$parametro['fechaini'],$parametro['fechafin'],7,true);
	}

	function reporte_temporizado_egreso($parametro)
	{
		$datos = $this->ModeloSubModulo->tabla_temporizada_datos($parametro['fechafin']);
		
		  $titulo='';

		//$this->pdf->AddPage();	
		if($parametro['tipocuenta']=='P')
		{
			$titulo='SALDO DE CUENTAS POR PAGAR TEMPORIZADO';
		}else
		{
			$titulo ='SALDO DE CUENTAS POR COBRAR TEMPORIZADO';
		}

//inicio--------logo superior derecho
     
		//$this->cabecera_reporte($titulo,$parametro['fechaini'],$parametro['fechafin']);

//fin--------logo superior derecho

		 $cuentas = array();
		 foreach ($datos as $key => $value) {
		 	if(!in_array($value['Cliente'],$cuentas))
		 	{
		 		$cuentas[]=$value['Cliente'];
		 	}
		 }

		        $total_1=0;$totalTotal_1=0;
		        $total_2=0;$totalTotal_2=0;
		        $total_3=0;$totalTotal_3=0;
		        $total_4=0;$totalTotal_4=0;
		        $total_5=0;$totalTotal_5=0;
		        $total_6=0;$totalTotal_6=0;
		        $total_7=0;$totalTotal_7=0;

		$pos=1;
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(17,22,17,15,14,16,17,17,18,20,20);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L','L','L','R','R','R');
		$tablaHTML[0]['datos']=array('CUENTA','CLIENTE','FECHA VEN','FACTURA','VEN 1 A 7','VEN 8 A 30','VEN 31 A 60','VEN 61 A 90','VEN 91 A 180','VEN 181 A 360','VEN + DE 360');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = 'B';

		// $html='<table BORDER="BUTTON"><tr>
		// 	<td width="140"><b>CUENTA</b></td>
		// 	<td width="110"><b>CLIENTE</b></td>
		// 	<td width="55"><b>FECHA VEN</b></td>
		// 	<td width="55"><b>FACTURA</b></td>
		// 	<td width="55"><b>VEN 1 A 7</b></td>
		// 	<td width="55"><b>VEN 8 A 30</b></td>
		// 	<td width="55"><b>VEN 31 A 60</b></td>
		// 	<td width="55"><b>VEN 61 A 90</b></td>
		// 	<td width="60"><b>VEN 91 A 180</b></td>
		// 	<td width="65"><b>VEN 181 A 360</b></td>
		// 	<td width="65"><b>VEN + DE 360</b></td>
		// 	</tr></table>';
		   foreach ($cuentas as $key => $val) {
		   	$cuent='';		   
			foreach ($datos as $key => $value) {
				$total_1+=$value['Ven 1 a 7'];
				$total_2+=$value['Ven 8 a 30'];
				$total_3+=$value['Ven 31 a 60'];
				$total_4+=$value['Ven 61 a 90'];
				$total_5+=$value['Ven 91 a 180'];
				$total_6+=$value['Ven 181 a 360'];
				$total_7+=$value['Ven mas de 360'];

			  if($value['Cliente']==$val)
			  {
			  	if($cuent == '')
			    {
				    $cuent = $value['Cliente'];
			    }else
			    {
					$cuent = '';						
			    }
				// if($value['Ven 1 a 7'] < 0)
				// {
				// 	$primero = '<font color="#cb3234">'. bcdiv($value['Ven 1 a 7'],1,2).'</font>';
				// }else
				// {
				// 	$primero =  bcdiv($value['Ven 1 a 7'],1,2);
				// }

				// if($value['Ven 8 a 30'] < 0)
				// {
				// 	$segundo = '<font color="#cb3234">'. bcdiv($value['Ven 8 a 30'],1,2).'</font>';
				// }else
				// {
				// 	$segundo =  bcdiv($value['Ven 8 a 30'],1,2);
				// }
				// if($value['Ven 31 a 60'] < 0)
				// {
				// 	$tercero = '<font color="#cb3234">'. bcdiv($value['Ven 31 a 60'],1,2).'</font>';
				// }else
				// {
				// 	$tercero =  bcdiv($value['Ven 31 a 60'],1,2);
				// }
				// if($value['Ven 61 a 90'] < 0)
				// {
				// 	$cuarto = '<font color="#cb3234">'. bcdiv($value['Ven 61 a 90'],1,2).'</font>';
				// }else
				// {
				// 	$cuarto =  bcdiv($value['Ven 61 a 90'],1,2);
				// }
				// if($value['Ven 91 a 180'] < 0)
				// {
				// 	$quinto = '<font color="#cb3234">'. bcdiv($value['Ven 91 a 180'],1,2).'</font>';
				// }else
				// {
				// 	$quinto =  bcdiv($value['Ven 91 a 180'],1,2);
				// }
				// if($value['Ven 181 a 360'] < 0)
				// {
				// 	$sexto = '<font color="#cb3234">'. bcdiv($value['Ven 181 a 360'],1,2).'</font>';
				// }else
				// {
				// 	$sexto =  bcdiv($value['Ven 181 a 360'],1,2);
				// }
				// if($value['Ven mas de 360'] < 0)
				// {
				// 	$septimo = '<font color="#cb3234">'. bcdiv($value['Ven mas de 360'],1,2).'</font>';
				// }else
				// {
				// 	$septimo =  bcdiv($value['Ven mas de 360'],1,2);
				// }

			// $html.='<table  BORDER="RIGHT">
			// <tr>
			// <td width="140" height="15">'.$cuent.'</td>
			// <td width="110" height="15">'.$value['Cuenta'].'</td>
			// <td width="55" height="15">'.date_format($value['Fecha_Venc'], 'Y-m-d').'</td>
			// <td width="55" height="15">'.$value['Factura'].'</td>
			// <td width="55" height="15" ALIGN="RIGHT">'.$primero.'</td>
			// <td width="55" height="15" ALIGN="RIGHT">'.$segundo.'</td>
			// <td width="55" height="15" ALIGN="RIGHT">'.$tercero.'</td>
			// <td width="55" height="15" ALIGN="RIGHT">'.$cuarto.'</td>
			// <td width="60" height="15" ALIGN="RIGHT">'.$quinto.'</td>
			// <td width="65" height="15" ALIGN="RIGHT">'.$sexto.'</td>
			// <td width="65" height="15" ALIGN="RIGHT">'.$septimo.'</td>				
			// </tr></table>';
			
		 //    $cuent = $value['Cliente'];

		   	$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		    $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		    $tablaHTML[$pos]['datos']=array($cuent,$value['Cliente'],$value['Fecha_Venc']->format('Y-m-d'),$value['Factura'],bcdiv($value['Ven 1 a 7'],1,2), bcdiv($value['Ven 8 a 30'],1,2),bcdiv($value['Ven 31 a 60'],1,2),bcdiv($value['Ven 61 a 90'],1,2),bcdiv($value['Ven 91 a 180'],1,2),bcdiv($value['Ven 181 a 360'],1,2),bcdiv($value['Ven mas de 360'],1,2));
		    $tablaHTML[$pos]['borde'] = 'R';

		   $cuent = $value['Cuenta'];
		   $pos = $pos+1;

		   }
		  }
		   // $html.='<table BORDER="BUTTON-TOP"><tr><td width="355" ALIGN="RIGHT">SUBTOTAL</td><td width="55" ALIGN="RIGHT">'.bcdiv($total_1,1,2).'</td><td width="55" ALIGN="RIGHT">'.bcdiv($total_2,1,2).'</td><td width="55" ALIGN="RIGHT">'.bcdiv($total_3,1,2).'</td><td width="55" ALIGN="RIGHT">'. bcdiv($total_4,1,2).'</td><td width="60" ALIGN="RIGHT">'.bcdiv($total_5,1,2).'</td><td width="65" ALIGN="RIGHT">'. bcdiv($total_6,1,2).'</td><td width="65" ALIGN="RIGHT">'.bcdiv($total_7,1,2).'</td></tr></table>';

		    $tablaHTML[$pos]['medidas']=array(71,14,16,17,17,18,20,20);
		     $tablaHTML[$pos]['alineado']=array('R','R','R','R','R','R','R','R');
		     $tablaHTML[$pos]['datos']=array('SUB TOTAL',bcdiv($total_1,1,2),bcdiv($total_2,1,2),bcdiv($total_3,1,2),bcdiv($total_4,1,2),bcdiv($total_5,1,2),bcdiv($total_6,1,2),bcdiv($total_7,1,2));
		     $tablaHTML[$pos]['borde'] = 'BT';
		   $pos = $pos+1;


		   $totalTotal_1+=$total_1;   $totalTotal_2+=$total_2;  $totalTotal_3+=$total_3;  $totalTotal_4+=$total_4;  $totalTotal_5+=$total_5;
		   $totalTotal_6+=$total_6;	   $totalTotal_7+=$total_7;
		}
		// $html.='<table BORDER="BUTTON-TOP"><tr><td width="355" ALIGN="RIGHT"><b>TOTAL</b></td><td width="55" ALIGN="RIGHT"><b>'.bcdiv($totalTotal_1,1,2).'</b></td><td width="55" ALIGN="RIGHT"><b>'.bcdiv($totalTotal_2,1,2).'</b></td><td width="55" ALIGN="RIGHT"><b>'.bcdiv($totalTotal_3,1,2).'</b></td><td width="55" ALIGN="RIGHT"><b>'. bcdiv($totalTotal_4,1,2).'</b></td><td width="60" ALIGN="RIGHT"><b>'.bcdiv($totalTotal_5,1,2).'</b></td><td width="65" ALIGN="RIGHT"><b>'. bcdiv($totalTotal_6,1,2).'</b></td><td width="65" ALIGN="RIGHT"><b>'.bcdiv($totalTotal_7,1,2).'</b></td></tr></table>';
		 $tablaHTML[$pos]['medidas']=array(71,14,16,17,17,18,20,20);
		     $tablaHTML[$pos]['alineado']=array('R','R','R','R','R','R','R','R');
		     $tablaHTML[$pos]['datos']=array('TOTAL',bcdiv($totalTotal_1,1,2),bcdiv($totalTotal_2,1,2),bcdiv($totalTotal_3,1,2),bcdiv($totalTotal_4,1,2),bcdiv($totalTotal_5,1,2),bcdiv($totalTotal_6,1,2),bcdiv($totalTotal_7,1,2),);
		     $tablaHTML[$pos]['borde'] = 'BT';
		     $tablaHTML[$pos]['estilo']='BI';
		   $pos = $pos+1;
		   
				
			
			


		
	 $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$imagen=false,$parametro['fechaini'],$parametro['fechafin'],7,true);
		//$this->pdf->cabecera_reporte($titulo,$html,$contenido=false,$imagen=false,$parametro['fechaini'],$parametro['fechafin'],7,true);

	}

	function cuentas_x_pagar_excel($parametro)
	{
		if($parametro['tipocuenta']=='P')
		{
			$_SESSION['INGRESO']['ti']='SALDO DE CUENTAS POR PAGAR';
		}else
		{
			$_SESSION['INGRESO']['ti']=='SALDO DE CUENTAS POR COBRAR';
		}

		$datos = $this->ModeloSubModulo->consulta_c_p_datos(
			$parametro['tipocuenta'],
			$parametro['ChecksubCta'],
			$parametro['OpcP'],
			$parametro['CheqCta'],
			$parametro['CheqDet'],
			$parametro['CheqIndiv'],
			$parametro['fechaini'],
			$parametro['fechafin'],
			$parametro['Cta'],
			$parametro['CodigoCli'],
			$parametro['DCDet'],true);

		//exportar_excel_generico($datos,null,null,$b);
	}

	function egresos_centro_c_excel($parametro)
	{
		if($parametro['tipocuenta']=='G')
		{
			$_SESSION['INGRESO']['ti']='SALDO DE EGRESO';
	    }else
	    {
	    	$_SESSION['INGRESO']['ti']='SALDO DE INGRESOS';
	    }
	    $datos = $this->ModeloSubModulo->consulta_ing_egre_datos(
			$parametro['tipocuenta'],
			$parametro['ChecksubCta'],
			$parametro['OpcP'],
			$parametro['CheqCta'],
			$parametro['CheqDet'],
			$parametro['CheqIndiv'],
			$parametro['fechaini'],
			$parametro['fechafin'],
			$parametro['Cta'],
			$parametro['CodigoCli'],
			$parametro['DCDet'],true);

		
	}
	function reporte_temporizado_excel($parametro)
	{
		if($parametro['tipocuenta']=='P')
		{
			$_SESSION['INGRESO']['ti']='SALDO DE CUENTAS POR PAGAR TEMPORIZADO';
		}else if($parametro['tipocuenta']=='C')
		{
			$_SESSION['INGRESO']['ti']=='SALDO DE CUENTAS POR COBRAR TEMPORIZADO';
		}
		else if($parametro['tipocuenta']=='G')
		{
			$_SESSION['INGRESO']['ti']='SALDO DE EGRESO TEMPORIZADO';
	    }else if($parametro['tipocuenta']=='I')
	    {
	    	$_SESSION['INGRESO']['ti']='SALDO DE INGRESOS TEMPORIZADO';
	    }

		$datos = $this->ModeloSubModulo->tabla_temporizada_datos($parametro['fechafin'],true);

	}
	

}


?>
