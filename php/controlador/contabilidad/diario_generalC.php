<?php 
include(dirname(__DIR__,2).'/modelo/contabilidad/diario_generalM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */
if(isset($_GET['sucu_exi']))
{
	$controlador = new diario_generalC();
	echo json_encode($controlador->existente());

}
if(isset($_GET['drop']))
{
	$controlador = new diario_generalC();
	echo json_encode($controlador->cargar_drop());

}
if(isset($_GET['consultar_libro']))
{
	$parametros = $_POST['parametros'];
	$controlador = new diario_generalC();
	echo json_encode($controlador->cargar_consulta_libroD($parametros));
}
if(isset($_GET['consultar_submodulo']))
{
	$parametros = $_POST['parametros'];
	$controlador = new diario_generalC();
	echo json_encode($controlador->cargar_submodulo($parametros));
}
if(isset($_GET['consultar_libro_1']))
{
	$parametros = $_POST['parametros'];
	$controlador = new diario_generalC();
	echo json_encode($controlador->cargar_saldos($parametros));
}

if(isset($_GET['reporte_libro_1']))
{
	$parametros = array(
	        'Fechaini'=>$_GET['Fechaini'],
	        'Fechafin'=>$_GET['Fechafin'],
			'DCAgencia'=>$_GET['DCAgencia'],
			'DCUsuario'=>$_GET['DCUsuario'],
			'TextNumNo'=>$_GET['TextNumNo'],
			'TextNumNo1'=>$_GET['TextNumNo1'],
			'OpcCI'=>$_GET['OpcCI'],
			'OpcCE'=>$_GET['OpcCE'],
			'OpcCD'=>$_GET['OpcCD'],
			'OpcND'=>$_GET['OpcND'],
			'OpcNC'=>$_GET['OpcNC'],
			'OpcA'=>$_GET['OpcA'],
			'CheckAgencia'=>$_GET['CheckAgencia'],
			'CheckUsuario'=>$_GET['CheckUsuario'],
			'CheckNum'=>$_GET['CheckNum']);

	$controlador = new diario_generalC();
	echo json_encode($controlador->reporte_libro_1($parametros));
}

if(isset($_GET['reporte_libro_2']))
{
	$parametros = array(
	        'Fechaini'=>$_GET['Fechaini'],
	        'Fechafin'=>$_GET['Fechafin'],
			'DCAgencia'=>$_GET['DCAgencia'],
			'DCUsuario'=>$_GET['DCUsuario'],
			'TextNumNo'=>$_GET['TextNumNo'],
			'TextNumNo1'=>$_GET['TextNumNo1'],
			'OpcCI'=>$_GET['OpcCI'],
			'OpcCE'=>$_GET['OpcCE'],
			'OpcCD'=>$_GET['OpcCD'],
			'OpcND'=>$_GET['OpcND'],
			'OpcNC'=>$_GET['OpcNC'],
			'OpcA'=>$_GET['OpcA'],
			'CheckAgencia'=>$_GET['CheckAgencia'],
			'CheckUsuario'=>$_GET['CheckUsuario'],
			'CheckNum'=>$_GET['CheckNum']);

	$controlador = new diario_generalC();
	echo json_encode($controlador->reporte_libro_2($parametros));
}
if(isset($_GET['reporte_libro_1_excel']))
{
	$parametros = array(
	        'Fechaini'=>$_GET['Fechaini'],
	        'Fechafin'=>$_GET['Fechafin'],
			'DCAgencia'=>$_GET['DCAgencia'],
			'DCUsuario'=>$_GET['DCUsuario'],
			'TextNumNo'=>$_GET['TextNumNo'],
			'TextNumNo1'=>$_GET['TextNumNo1'],
			'OpcCI'=>$_GET['OpcCI'],
			'OpcCE'=>$_GET['OpcCE'],
			'OpcCD'=>$_GET['OpcCD'],
			'OpcND'=>$_GET['OpcND'],
			'OpcNC'=>$_GET['OpcNC'],
			'OpcA'=>$_GET['OpcA'],
			'CheckAgencia'=>$_GET['CheckAgencia'],
			'CheckUsuario'=>$_GET['CheckUsuario'],
			'CheckNum'=>$_GET['CheckNum']);

	$controlador = new diario_generalC();
	echo json_encode($controlador->excel_diario($parametros));
}
if(isset($_GET['reporte_libro_2_excel']))
{
	$parametros = array(
	        'Fechaini'=>$_GET['Fechaini'],
	        'Fechafin'=>$_GET['Fechafin'],
			'DCAgencia'=>$_GET['DCAgencia'],
			'DCUsuario'=>$_GET['DCUsuario'],
			'TextNumNo'=>$_GET['TextNumNo'],
			'TextNumNo1'=>$_GET['TextNumNo1'],
			'OpcCI'=>$_GET['OpcCI'],
			'OpcCE'=>$_GET['OpcCE'],
			'OpcCD'=>$_GET['OpcCD'],
			'OpcND'=>$_GET['OpcND'],
			'OpcNC'=>$_GET['OpcNC'],
			'OpcA'=>$_GET['OpcA'],
			'CheckAgencia'=>$_GET['CheckAgencia'],
			'CheckUsuario'=>$_GET['CheckUsuario'],
			'CheckNum'=>$_GET['CheckNum']);

	$controlador = new diario_generalC();
	echo json_encode($controlador->excel_libro($parametros));
}

if(isset($_GET['EliminarComprobantesIncompletos']))
{
	$controlador = new diario_generalC();
	echo json_encode($controlador->EliminarComprobantesIncompletos());
}


class diario_generalC
{
	private $modelo;
	private $pdf;
	private $pdftable;
	
	function __construct()
	{
		$this->modelo = new diario_generalM();	
		$this->pdf = new cabecera_pdf();	
		$this->pdftable = new PDF_MC_Table();	
		
	}

	function existente()
	{
		$exi = existe_sucursales();
		return $exi;
	}

	function cargar_drop()
	{
		$agencia = $this->modelo->llenar_agencia();
		$usuario = $this->modelo->llenar_usuario();
		// echo json_encode($usuario);
		// print_r("entra");
		$datos = array('agencia'=>$agencia,'usuario'=>$usuario);
		return $datos;
		// echo json_encode($datos);
		// exit();
	}


	function cargar_consulta_libroD($parametros)
	{
		$Fechaini = str_replace('-','',$parametros['Fechaini']);
		$Fechafin = str_replace('-','',$parametros['Fechafin']);
		
		return $this->modelo->cargar_consulta_libro_tabla(
			$Fechaini,
			$Fechafin,
			$parametros['DCAgencia'],
			$parametros['DCUsuario'],
			$parametros['TextNumNo'],
			$parametros['TextNumNo1'],
			$parametros['OpcCI'],
			$parametros['OpcCE'],
			$parametros['OpcCD'],
			$parametros['OpcND'],
			$parametros['OpcNC'],
			$parametros['OpcA'],
			$parametros['CheckAgencia'],
			$parametros['CheckUsuario'],
			$parametros['CheckNum']);
	}
	function cargar_submodulo($parametros)
	{
		//print_r($parametros);
		$Fechaini = str_replace('-','',$parametros['Fechaini']);
		$Fechafin = str_replace('-','',$parametros['Fechafin']);
		
		$datos = $this->modelo-> cargar_consulta_submodulo(
			$Fechaini,
			$Fechafin,
			$parametros['DCAgencia'],
			$parametros['DCUsuario'],
			$parametros['TextNumNo'],
			$parametros['TextNumNo1'],
			$parametros['OpcCI'],
			$parametros['OpcCE'],
			$parametros['OpcCD'],
			$parametros['OpcND'],
			$parametros['OpcNC'],
			$parametros['OpcA'],
			$parametros['CheckAgencia'],
			$parametros['CheckUsuario'],
			$parametros['CheckNum']);
		
		return $datos;
	}
	function cargar_saldos($parametros)
	{
		
		$Fechaini = str_replace('-','',$parametros['Fechaini']);
		$Fechafin = str_replace('-','',$parametros['Fechafin']);
		
		$datos = $this->modelo->cargar_consulta_libro(
			$Fechaini,
			$Fechafin,
			$parametros['DCAgencia'],
			$parametros['DCUsuario'],
			$parametros['TextNumNo'],
			$parametros['TextNumNo1'],
			$parametros['OpcCI'],
			$parametros['OpcCE'],
			$parametros['OpcCD'],
			$parametros['OpcND'],
			$parametros['OpcNC'],
			$parametros['OpcA'],
			$parametros['CheckAgencia'],
			$parametros['CheckUsuario'],
			$parametros['CheckNum']);
		 $debe =0;
		 $haber =0;
		 $saldo =0;
		 $debe_ME =0;
		 $haber_ME =0;
		 $saldo_ME =0;
		foreach ($datos as $key => $value) {
			//print_r($value);
			$debe += $value['Debe'];
			$haber += $value['Haber'];
			if($value['Parcial_ME'] > 0)
			{
				$debe_ME += $value['Parcial_ME'];
			}else
			{
				$haber_ME -= $value['Parcial_ME'];
			}
				//print_r($value['Debe']);			
		}

        $saldo = $debe - $haber;
        $saldo_ME = $debe_ME - $haber_ME;

		$calculos = array('debe'=>$debe,'haber'=>$haber,'saldo'=>$saldo,'debe_me'=>$debe_ME,'haber_me'=>$haber_ME,'saldo_me'=>$saldo_ME);
        //print_r($calculos);
        return $calculos;
	}

	function reporte_libro_1($parametros)
	{
		$titulo = 'D I A R I O   G E N E R A L ';
		$sizetable =7;
		$mostrar = TRUE;
		$Fechaini = str_replace('-','',$parametros['Fechaini']);
		$Fechafin = str_replace('-','',$parametros['Fechafin']);
		
		$datos = $this->modelo->cargar_consulta_libro(
			$Fechaini,
			$Fechafin,
			$parametros['DCAgencia'],
			$parametros['DCUsuario'],
			$parametros['TextNumNo'],
			$parametros['TextNumNo1'],
			$parametros['OpcCI'],
			$parametros['OpcCE'],
			$parametros['OpcCD'],
			$parametros['OpcND'],
			$parametros['OpcNC'],
			$parametros['OpcA'],
			$parametros['CheckAgencia'],
			$parametros['CheckUsuario'],
			$parametros['CheckNum']);
	
		$temp='';
		$total_debe= 0;
		$total_haber = 0;	
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(20,49,35,50,18,18);
		$tablaHTML[0]['alineado']=array('L','L','L','L','R','R');
		$tablaHTML[0]['datos']=array('COMPROB','CONCEPTO','CODIGO','CUENTA','DEBE','HABER');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = '1';

		$comprobantes = array();
		foreach ($datos as $key => $value) {
			$comprobantes[]=$value['Numero'];
		}

		$comprob_unicos = array_unique($comprobantes);
		$concepto='';
		$cta='';
		$cuenta='';
		$debe='';
		$haber='';
		$concep='';
		$pos =0;

		foreach ($comprob_unicos as $key1 => $value1) {
			if($pos==0)
			{
				$pos = $key1+1;
			}

			$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		    $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		    // $tablaHTML[$pos]['borde'] = '1';

			foreach ($datos as $key => $value) {
				if($value['Numero'] == $value1)
				{
					$comp =  $value['Fecha']->format('Y-m-d').' '.$value['TP'].'-'.$value['Numero'];	
					if($concep == '')
					{
						$concep = $value['Concepto'];
						$concepto.= $value['Concepto']."\n";						
						//print_r($concepto);
					}else
					{
						if($concep == $value['Concepto'])
						{
							$concepto.= ''."\n";
						}else
						{
							$concep = $value['Concepto'];							
						    $concepto.= $value['Concepto']."\n";
						}
					  //print_r($concepto);
					}			
	               // $concepto.= $value['Concepto']."\n";
					$cta.=$value['Cta'].'      ';
					$cuenta.=$value['Cuenta']."\n";
					$debe.=round($value['Debe'],2)."\n";
					$haber.=round($value['Haber'],2)."\n";
					$total_debe+=round($value['Debe'],2);
			        $total_haber+=round($value['Haber'],2);
				
				}


			}
					//print_r($concepto);

			//print_r(array($comp,$concepto,$cta,$cuenta,$debe,$haber));
			$tablaHTML[$pos]['datos']=array($comp,$concepto,$cta,$cuenta,$debe,$haber);
			$pos = $pos+1;
			$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		    $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];	
			$tablaHTML[$pos]['datos']=array('','','','','','');	
			$concepto='';
			$cta='';
			$cuenta='';
			$debe='';
			$haber='';
			$comp='';
			$concep='';
			$pos = $pos+1;	
	
		}		
		    $total = count($tablaHTML);
			$tablaHTML[$total+1]['medidas']=array(105,60,15,15);
		    $tablaHTML[$total+1]['alineado']=array('R','L','R','R');
		    $tablaHTML[$total+1]['estilo']='B';
            $tablaHTML[$total+1]['datos']=array('','TOTAL',$total_debe,$total_haber);

//print_r($tablaHTML);

		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$parametros['Fechaini'],$parametros['Fechafin'],$sizetable,$mostrar,25);
	}

	function reporte_libro_2($parametros)
	{
		$titulo = 'L I B R O   D I A R I O';
		$sizetable = 8;
		$mostrar = TRUE;
		$Fechaini = str_replace('-','',$parametros['Fechaini']);
		$Fechafin = str_replace('-','',$parametros['Fechafin']);
		$datossub = $this->modelo->cargar_consulta_submodulo_datos(
		    $Fechaini,
			$Fechafin,
			$parametros['DCAgencia'],
			$parametros['DCUsuario'],
			$parametros['TextNumNo'],
			$parametros['TextNumNo1'],
			$parametros['OpcCI'],
			$parametros['OpcCE'],
			$parametros['OpcCD'],
			$parametros['OpcND'],
			$parametros['OpcNC'],
			$parametros['OpcA'],
			$parametros['CheckAgencia'],
			$parametros['CheckUsuario'],
			$parametros['CheckNum']);

		//print_r($datossub);		
		$datos = $this->modelo->cargar_consulta_libro(
			$Fechaini,
			$Fechafin,
			$parametros['DCAgencia'],
			$parametros['DCUsuario'],
			$parametros['TextNumNo'],
			$parametros['TextNumNo1'],
			$parametros['OpcCI'],
			$parametros['OpcCE'],
			$parametros['OpcCD'],
			$parametros['OpcND'],
			$parametros['OpcNC'],
			$parametros['OpcA'],
			$parametros['CheckAgencia'],
			$parametros['CheckUsuario'],
			$parametros['CheckNum']);

		   		$tablaHTML=''; 

		$temp='';
		$total_debe= 0;
		$total_haber = 0;
		 foreach ($datos as $key => $value){
		 	if(round($value['Debe'],2) == 0)
		 	{
		 		$deb = '&nbsp;';
		 	}else
		 	{
		 		$deb = round($value['Debe'],2);
		 	}
			
			if(round($value['Haber'],2) == 0)
		 	{
		 		$hab = '&nbsp;';
		 	}else
		 	{
		 		$hab = round($value['Haber'],2);
		 	}

		 	if(round($value['Parcial_ME'],2) == 0)
		 	{
		 		$par_me = '&nbsp;';
		 	}else
		 	{
		 		$par_me = round($value['Parcial_ME'],2);
		 	}
			
			if($temp=='')
			{

			$temp = $value['Numero'];
		   		$tablaHTML.= '<table  BORDER="TOP"><tr><td width="70" BORDER1="LEFT-TOP"><b>Fecha:</b></td><td width="90"><i>'.$value['Fecha']->format('Y-m-d').'</i></td><td width="90"><b>Elaborado:</b></td><td width="160"><i>'.$value['CodigoU'].'</i></td><td width="30"><b>TP:</b></td><td width="50"><i>'.$value['TP'].'</i></td><td width="100"><b>Numero No:</b></td><td  BORDER1="RIGHT-TOP"><i>'.$value['Numero'].'</i></td></tr></table><table BORDER="BUTTON"><tr><td width="90"  BORDER1="LEFT-BUTTON"><b>Concepto:</b></td><td width="660" BORDER1="RIGHT-BUTTON"><i>'.$value['Concepto'].'</i></td></tr></table><table BORDER="1"><tr><td width="100"><b>Codigo</b></td><td width="380"><b>Cuenta</b></td><td width="90"><b>Parcial_ME</b></td><td width="90"><b>DEBE</b></td><td width="90"><b>HABER</b></td></tr></table><table BORDER="LEFT-RIGHT"><tr><td width="100"><i>'.$value['Cta'].'</i></td><td width="380"><i>'.$value['Cuenta']."\n".'</i></td><td width="90" ALIGN="RIGHT"><i>'.$par_me.'</i></td><td width="90" ALIGN="RIGHT"><i>'.$deb.'</i></td><td width="90" ALIGN="RIGHT"><i>'.$hab.'</i></td></tr></table>';
		   		if($value['Detalle'] != '.')
		   		{
		   			$tablaHTML.= '<table BORDER="LEFT-RIGHT"><tr><td width="100"><i>&nbsp;</i></td><td width="380" SIZE="7"><i>'.$value['Detalle'].'</i></td><td width="90" ALIGN="RIGHT"><i>&nbsp;</i></td><td width="90" ALIGN="RIGHT"><i>&nbsp;</i></td><td width="90" ALIGN="RIGHT"><i>&nbsp;</i></td></tr></table>';
		   		}
		   		foreach ($datossub as $key2 => $value2) {
		   			if($value['Cta'] == $value2['Cta'] && $value['Numero'] == $value2['Numero'])
		   			{
		   				if(round($value2['Debitos'],2) == 0)
		   				{
		   					$valor = '<td width="90" ALIGN="RIGHT"><i>'.round($value2['Creditos'],2).'</i></td>';
		   				}else
		   				{
		   					$valor = '<td width="90" ALIGN="LEFT"><i>'.round($value2['Debitos'],2).'</i></td>';
		   				}
		   				$tablaHTML.= '<table BORDER="LEFT-RIGHT"><tr><td width="100"><i>&nbsp;</i></td><td width="380" SIZE="7"><i>  '.$value2['Cliente'].'</i></td>'.$valor.'<td width="90" ALIGN="RIGHT"><i>&nbsp;</i></td><td width="90" ALIGN="RIGHT"><i>&nbsp;</i></td></tr></table>';

		   			}
		   		}
		   		$total_debe+=$value['Debe'];
			    $total_haber+=$value['Haber'];

		   }else
		   {
		   	if($temp == $value['Numero'])
		   	{
		   		$tablaHTML.= '<table  BORDER="LEFT-RIGHT"><tr><td width="100"><i>'.$value['Cta'].'</i></td><td width="380"><i>'.$value['Cuenta'].'</i></td><td width="90" ALIGN="RIGHT"><i>'.$par_me.'</i></td><td width="90" ALIGN="RIGHT"><i>'.$deb.'</i></td><td width="90" ALIGN="RIGHT"><i>'.$hab.'</i></td></tr></table>';
		   		if($value['Detalle'] != '.')
		   		{
		   			$tablaHTML.= '<table BORDER="LEFT-RIGHT"><tr><td width="100"><i>&nbsp;</i></td><td width="380" SIZE="7"><i>'.$value['Detalle'].'</i></td><td width="90" ALIGN="RIGHT"><i>&nbsp;</i></td><td width="90" ALIGN="RIGHT"><i>&nbsp;</i></td><td width="90" ALIGN="RIGHT"><i>&nbsp;</i></td></tr></table>';
		   		}

		   		foreach ($datossub as $key2 => $value2) {
		   			if($value['Cta'] == $value2['Cta'] && $value['Numero'] == $value2['Numero'])
		   			{
		   				if(round($value2['Debitos'],2) == 0)
		   				{
		   					$valor = '<td width="90" ALIGN="RIGHT"><i>'.round($value2['Creditos'],2).'</i></td>';
		   				}else
		   				{
		   					$valor = '<td width="90" ALIGN="LEFT"><i>'.round($value2['Debitos'],2).'</i></td>';
		   				}
		   				$tablaHTML.= '<table BORDER="LEFT-RIGHT"><tr><td width="100"><i>&nbsp;</i></td><td width="380" SIZE="7"><i>  '.$value2['Cliente'].'</i></td>'.$valor.'<td width="90" ALIGN="RIGHT"><i>&nbsp;</i></td><td width="90" ALIGN="RIGHT"><i>&nbsp;</i></td></tr></table>';

		   			}
		   		}

		   		$total_debe+=$value['Debe'];
			    $total_haber+=$value['Haber'];

		   	}else
		   	{
		   		$temp = $value['Numero'];
		   		$tablaHTML.='</table><table BORDER="TOP"><tr><td width="40"><b>'.$_SESSION['INGRESO']['item'].'</b></td><td width="530" ALIGN="RIGHT"><b>TOTALES</b></td><td width="90" ALIGN="RIGHT">'.$total_haber.'</td><td width="90" ALIGN="RIGHT">'.$total_haber.'</td></tr></table>'; 
		   		//break;
		   		$total_debe=00;
			    $total_haber=0;
		   			$tablaHTML.= '<table  BORDER="TOP"><tr><td width="70" BORDER1="LEFT-TOP"><b>Fecha:</b></td><td width="90"><i>'.$value['Fecha']->format('Y-m-d').'</i></td><td width="90"><b>Elaborado:</b></td><td width="160"><i>'.$value['CodigoU'].'</i></td><td width="30"><b>TP:</b></td><td width="50"><i>'.$value['TP'].'</i></td><td width="100"><b>Numero No:</b></td><td  BORDER1="RIGHT-TOP"><i>'.$value['Numero'].'</i></td></tr></table><table BORDER="BUTTON"><tr><td width="90"  BORDER1="LEFT-BUTTON"><b>Concepto:</b></td><td width="660" BORDER1="RIGHT-BUTTON"><i>'.$value['Concepto'].'</i></td></tr></table><table BORDER="1"><tr><td width="100"><b>Codigo</b></td><td width="380"><b>Cuenta</b></td><td width="90"><b>Parcial_ME</b></td><td width="90"><b>DEBE</b></td><td width="90"><b>HABER</b></td></tr></table><table BORDER="LEFT-RIGHT"><tr><td width="100"><i>'.$value['Cta'].'</i></td><td width="380"><i>'.$value['Cuenta']."\n".'</i></td><td width="90" ALIGN="RIGHT"><i>'.$par_me.'</i></td><td width="90" ALIGN="RIGHT"><i>'.$deb.'</i></td><td width="90" ALIGN="RIGHT"><i>'.$hab.'</i></td></tr></table>';
		   			if($value['Detalle'] != '.')
		   		{
		   			$tablaHTML.= '<table BORDER="LEFT-RIGHT"><tr><td width="100"><i>&nbsp;</i></td><td width="380" SIZE="7"><i>'.$value['Detalle'].'</i></td><td width="90" ALIGN="RIGHT"><i>&nbsp;</i></td><td width="90" ALIGN="RIGHT"><i>&nbsp;</i></td><td width="90" ALIGN="RIGHT"><i>&nbsp;</i></td></tr></table>';
		   		}
		   			
		   			foreach ($datossub as $key2 => $value2) {
		   			if($value['Cta'] == $value2['Cta'] && $value['Numero'] == $value2['Numero'])
		   			{
		   				if(round($value2['Debitos'],2) == 0)
		   				{
		   					$valor = '<td width="90" ALIGN="RIGHT"><i>'.round($value2['Creditos'],2).'</i></td>';
		   				}else
		   				{
		   					$valor = '<td width="90" ALIGN="LEFT"><i>'.round($value2['Debitos'],2).'</i></td>';
		   				}
		   				$tablaHTML.= '<table BORDER="LEFT-RIGHT"><tr><td width="100"><i>&nbsp;</i></td><td width="380" SIZE="7"><i>  '.$value2['Cliente'].'</i></td>'.$valor.'<td width="90" ALIGN="RIGHT"><i>&nbsp;</i></td><td width="90" ALIGN="RIGHT"><i>&nbsp;</i></td></tr></table>';

		   			}
		   		}
		   	}
		   }
			
		 }

		$this->pdf->cabecera_reporte($titulo,$tablaHTML,$contenido=false,$image=false,$parametros['Fechaini'],$parametros['Fechafin'],$sizetable,$mostrar,25);
	}

	function excel_diario($parametros)
	{
		$Fechaini = str_replace('-','',$parametros['Fechaini']);
		$Fechafin = str_replace('-','',$parametros['Fechafin']);
		$this->modelo->exportar_excel_diario($Fechaini,
			$Fechafin,
			$parametros['DCAgencia'],
			$parametros['DCUsuario'],
			$parametros['TextNumNo'],
			$parametros['TextNumNo1'],
			$parametros['OpcCI'],
			$parametros['OpcCE'],
			$parametros['OpcCD'],
			$parametros['OpcND'],
			$parametros['OpcNC'],
			$parametros['OpcA'],
			$parametros['CheckAgencia'],
			$parametros['CheckUsuario'],
			$parametros['CheckNum']);
	}
	function excel_libro($parametros)
	{
		$Fechaini = str_replace('-','',$parametros['Fechaini']);
		$Fechafin = str_replace('-','',$parametros['Fechafin']);
		$this->modelo->exportar_excel_libro( $Fechaini,
			$Fechafin,
			$parametros['DCAgencia'],
			$parametros['DCUsuario'],
			$parametros['TextNumNo'],
			$parametros['TextNumNo1'],
			$parametros['OpcCI'],
			$parametros['OpcCE'],
			$parametros['OpcCD'],
			$parametros['OpcND'],
			$parametros['OpcNC'],
			$parametros['OpcA'],
			$parametros['CheckAgencia'],
			$parametros['CheckUsuario'],
			$parametros['CheckNum']);
	}

	function EliminarComprobantesIncompletos()
	{
		Actualiza_Comprobantes_Incompletos("Trans_Kardex");
	    Actualiza_Comprobantes_Incompletos("Trans_SubCtas");
	    Actualiza_Comprobantes_Incompletos("Transacciones");
	    return true;
	}
}
?>