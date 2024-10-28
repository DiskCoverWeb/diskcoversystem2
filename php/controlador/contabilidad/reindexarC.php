<?php 
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
include(dirname(__DIR__,2).'/modelo/contabilidad/reindexarM.php');

$controlador = new reindexarC();
if(isset($_GET['reindexarT']))
{
	echo json_encode($controlador->reindexarT());
}
if(isset($_GET['imprimir_excel']))
{
	echo json_encode($controlador->imprimir_excel());
}
if(isset($_GET['imprimir_pdf']))
{
	echo json_encode($controlador->imprimir_pdf());
}






class reindexarC
{

	private $modelo;	
  	private $pdf;

	function __construct()
	{
		$this->modelo = new reindexarM(); 
	   $this->pdf = new cabecera_pdf();
	}

	function reindexarT()
	{

		// print_r($_SESSION['INGRESO']);die();
		try {
			Reindexar_Periodo_sp(); 
			Mayorizar_Cuentas_SP();
			Presenta_Errores_Contabilidad_SP();	
			$datos = $this->modelo->infoError();
			$tr = '';
			foreach ($datos as $key => $value) {
				$tr.='<tr><td>'.$value['Texto'].'</td></tr>';
			}
			return array('resp'=>1,'tr'=>$tr);
		} catch (Exception $e) {
			return  array('resp'=>-1,'tr'=>'');
		}
	}

	function Eliminar_Tabla_Temporal()
	{
		$this->modelo->Eliminar_Tabla_Temporal();
	}

	function imprimir_excel()
	{
		$datos = $this->modelo->infoError();
		$pos=0;
		if(count($datos)>0)
		{
			foreach ($datos as $key => $value) {
				$tablaHTML[$pos]['medidas']=array(192);
		        $tablaHTML[$pos]['datos']=array($value['Texto']);
		        $tablaHTML[$pos]['tipo'] ='N';
		        $pos = $pos+1;
			}
		}
		$this->Eliminar_Tabla_Temporal();		
	    excel_generico($titulo='INFOMRACION DE ERRORES',$tablaHTML);
	}

	function imprimir_pdf()
	{		
		$pos=0;

		$datos = $this->modelo->infoError();
		if(count($datos)>0)
		{
			foreach ($datos as $key => $value) {
				if($key==0)
				{
					$tablaHTML[$pos]['medidas']=array(192);
			        $tablaHTML[$pos]['alineado']=array('C');
			        $tablaHTML[$pos]['datos']=array($value['Texto']);
			        $tablaHTML[$pos]['borde'] = 'B';
			       $pos = $pos+1;
				}else
				{

					$tablaHTML[$pos]['medidas']=array(192);
			        $tablaHTML[$pos]['alineado']=array('L');
			        $tablaHTML[$pos]['datos']=array($value['Texto']);
			        $tablaHTML[$pos]['borde'] = '0';
			        $pos = $pos+1;
				}
			}

			$this->Eliminar_Tabla_Temporal();
			// $this->pdf->cabecera_reporte_MC('PLAN DE CUENTAS',$tablaHTML,$contenido=false,$image=false,'','',9,true,25);
			$this->pdf->cabecera_reporte_MC($titulo=false,$tablaHTML,$contenido=false,$image=false,$Fechaini=false,$Fechafin=false,9,true,15,'P',true,null,1);
		}

	}


}

?>