<?php 
//include(dirname(__DIR__).'/funciones/funciones.php');
include(dirname(__DIR__,2).'/modelo/contabilidad/saldo_fac_submoduloM.php');
/**
 * 
 */
$controlador =  new Saldo_fac_sub_M();
if(isset($_GET['cargar']))
{
	$ctc = $_POST['select'];
	echo json_encode($controlador->cargar_datos($ctc), JSON_UNESCAPED_UNICODE);
}

if(isset($_GET['consultar']))
{
	$parametros = $_POST['parametros'];
	$parametros['tabla'] = '1';
	$tabla = $controlador->cargar_consulta($parametros);
	echo json_encode($tabla['tabla']);
}

if(isset($_GET['consultar_x_meses']))
{
	$parametros = $_POST['parametros'];
	$parametros['tabla'] = '1';
	$tabla = $controlador->cargar_consulta_x_meses($parametros);
	echo json_encode($tabla);
}

if(isset($_GET['consultar_totales']))
{
	$parametros = $_POST['parametros'];
	$tabla = $controlador->cargar_consulta_totales($parametros);
	// print_r($tabla);die();
	echo json_encode($tabla);
}
if(isset($_GET['consultar_tempo']))
{
	$parametros = $_POST['parametros'];
	$tabla = $controlador->cargar_consulta($parametros);
	$res = $controlador->tabla_temporizado($tabla['datos'],$parametros);
	// print_r($res);die();
  echo json_encode($controlador->consultar_tabla_temp($parametros['fechafin']));
}

class Saldo_fac_sub_M 
{
	private $modelo;
	
	function __construct()
	{
	   $this->modelo = new  Saldo_fac_sub_C();
	}

	function cargar_datos($datos)
	{
	    //$datos = $this->modelo->mensaje();
		$titulo = '';
		$cxc = array();
		$det = array();
		$beneficiario = array();
		
		if($datos == 'C')
		{
			$titulo = 'SALDO DE CUENTAS POR COBRAR' ;
			$cta = $this->modelo->select_cta($datos);
			$det = $this->modelo->select_det($datos);
			$beneficiario = $this->modelo->select_beneficiario($datos);

		}else if($datos == 'P')
		{
			$titulo = 'SALDO DE CUENTAS POR PAGAR' ;
			$cta = $this->modelo->select_cta($datos);
			$det = $this->modelo->select_det($datos);
			$beneficiario = $this->modelo->select_beneficiario($datos);

		}else if($datos == 'I')
		{
			$titulo = 'SALDO DE INGRESOS' ;
			$cta = $this->modelo->select_cta($datos);
			$det = $this->modelo->select_det($datos);
			$beneficiario = $this->modelo->select_beneficiario($datos);

		}else if($datos == 'G')
		{
			$titulo = 'SALDO DE EGRESOS' ;
			$cta = $this->modelo->select_cta($datos);
			$det = $this->modelo->select_det($datos);
			$beneficiario = $this->modelo->select_beneficiario($datos);
			//echo $beneficiario;

		}else if($datos== 'CC')
		{
			$titulo = 'SALDO DE COSTOS' ;
			$cta = $this->modelo->select_cta($datos);
			$det = $this->modelo->select_det($datos);
			$beneficiario = $this->modelo->select_beneficiario($datos);

		}

		$lista = array('titulo'=>$titulo,'cta'=>$cta,'det'=>$det,'beneficiario'=>$beneficiario);
		return $lista;
	}

	function cargar_consulta($parametros)
	{
		// print_r($parametros);die();

		$resultado = explode(' ',$parametros['Cta']);
		$cta = $resultado[0];
		$fechaini = str_replace("-","",$parametros['fechaini']);
		$fechafin = str_replace("-","",$parametros['fechafin']);
			$Total = 0;$Saldo = 0;
		if($parametros['tipocuenta']=='C' || $parametros['tipocuenta']=='P')
		{
			// print_r($parametros);die();
			$datos_ = $this->modelo->consulta_c_p_datos(
					$parametros['tipocuenta'],
					$parametros['ChecksubCta'],
					$parametros['OpcP'],
					$parametros['CheqCta'],
					$parametros['CheqDet'],
					$parametros['CheqIndiv'],
					$fechaini,
					$fechafin,
					$cta,
					$parametros['CodigoCli'],
					$parametros['DCDet']
				);
			foreach ($datos_ as $key => $value) {
				 $Total = $Total + $value["Total"];
                 $Saldo = $Saldo + $value["Saldo"];
			}
			$totales_  = array('Total'=>$Total,'Saldo'=>$Saldo);

			// print_r($totales_);die();
			if(isset($parametros['tabla']))
			{
		  return $valores = array('tabla'=>$this->modelo->consulta_c_p_tabla(
						$parametros['tipocuenta'],
						$parametros['ChecksubCta'],
						$parametros['OpcP'],
						$parametros['CheqCta'],
						$parametros['CheqDet'],
						$parametros['CheqIndiv'],
						$fechaini,
						$fechafin,
						$cta,
						$parametros['CodigoCli'],
						$parametros['DCDet']
					),
				   'datos'=> $datos_,
				   'totales'=>$totales_
				 );
			}else
			{
				 return $valores = array('datos'=> $datos_,'totales'=>$totales_);
			}

		}else if($parametros['tipocuenta']=='I' || $parametros['tipocuenta']=='G')
		{
			$datos_=$this->modelo->consulta_ing_egre_datos(
					$parametros['tipocuenta'],
					$parametros['ChecksubCta'],
					$parametros['OpcP'],
					$parametros['CheqCta'],
					$parametros['CheqDet'],
					$parametros['CheqIndiv'],
					$fechaini,
					$fechafin,
					$cta,
					$parametros['CodigoCli'],
					$parametros['DCDet']
				);
					foreach ($datos_ as $key => $value) {
						$Total = $Total + $value["Total"];
						$saldo = 0;
					}
					$totales_  = array('Total'=>$Total,'Saldo'=>$Saldo);
					if(isset($parametros['tabla']))
						{		   
							   return $valores = array('tabla'=>$this->modelo->consulta_ing_egre_tabla(
									$parametros['tipocuenta'],
									$parametros['ChecksubCta'],
									$parametros['OpcP'],
									$parametros['CheqCta'],
									$parametros['CheqDet'],
									$parametros['CheqIndiv'],
									$fechaini,
									$fechafin,
									$cta,
									$parametros['CodigoCli'],
									$parametros['DCDet']
								),
						   'datos'=> $datos_,
						   'totales'=>$totales_
						 );
						}else
						{
							 return $valores = array('datos'=> $datos_,'totales'=>$totales_);
						}
		}

	}

	function cargar_consulta_x_meses($parametros)
	{
		// print_r($parametros);die();

		$resultado = explode(' ',$parametros['Cta']);
		$cta = $resultado[0];
		$fechaini = str_replace("-","",$parametros['fechaini']);
		$fechafin = str_replace("-","",$parametros['fechafin']);
		sp_Reporte_CxCxP_x_Meses($cta,$fechafin);

		$tabla = $this->modelo->cargar_consulta_x_meses('tabla');

		// print_r($tabla);die();
		$Total = 0;$Saldo = 0;
		
		return $tabla;
	}

	function cargar_consulta_totales($parametros)
	{

		$resultado = explode(' ',$parametros['Cta']);
		$cta = $resultado[0];
		$fechaini = str_replace("-","",$parametros['fechaini']);
		$fechafin = str_replace("-","",$parametros['fechafin']);
			$Total = 0;$Saldo = 0;
		if($parametros['tipocuenta']=='C' || $parametros['tipocuenta']=='P')
		{
			// print_r($parametros);die();
			$datos_ = $this->modelo->consulta_c_p_datos(
			$parametros['tipocuenta'],
			$parametros['ChecksubCta'],
			$parametros['OpcP'],
			$parametros['CheqCta'],
			$parametros['CheqDet'],
			$parametros['CheqIndiv'],
			$fechaini,
			$fechafin,
			$cta,
			$parametros['CodigoCli'],
			$parametros['DCDet']);
			foreach ($datos_ as $key => $value) {
				 $Total = $Total + $value["Total"];
                 $Saldo = $Saldo + $value["Saldo"];
			}
			$totales_  =  array('Total'=>number_format($Total,2,'.',''),'Saldo'=>number_format($Saldo,2,'.',''));
		  return $totales_ ;
		}else if($parametros['tipocuenta']=='I' || $parametros['tipocuenta']=='G')
		{
			$datos_=$this->modelo->consulta_ing_egre_datos(
			$parametros['tipocuenta'],
			$parametros['ChecksubCta'],
			$parametros['OpcP'],
			$parametros['CheqCta'],
			$parametros['CheqDet'],
			$parametros['CheqIndiv'],
			$fechaini,
			$fechafin,
			$cta,
			$parametros['CodigoCli'],
			$parametros['DCDet']);
			foreach ($datos_ as $key => $value) {
				$Total = $Total + $value["Total"];
				$saldo = 0;
			}
			$totales_  = array('Total'=>number_format($Total,2,'.',''),'Saldo'=>number_format($Saldo,2,'.',''));
		   return $totales_;

		}

	}


  function tabla_temporizado($datos,$parametros)
  {
  	    if($this->modelo->eliminar_saldo_diario()==1)
  	    {
  	    	if($parametros['tipocuenta']== 'C' || $parametros['tipocuenta']== 'P')
  	    	{
  	    		foreach ($datos as $key => $value) 
  	    		{  	    			

                    $date1 = new DateTime($parametros['fechaini']);
                    $date2 = new DateTime($parametros['fechafin']);
                    $fechaini = str_replace("-","",$parametros['fechaini']);
		           			$fechafin = str_replace("-","",$parametros['fechafin']);		
		
  	    	        //$dias = $parametros['fechafin']-$parametros['fechaini'];
  	    	        $dias = $date1->diff($date2)->days;
  	    	        SetAdoAddNew("Saldo_Diarios");       
 									SetAdoFields('Fecha_Venc',$fechafin);
					        SetAdoFields('Numero',$value['Factura']);
					        SetAdoFields('Comprobante',$value['Cliente']);
					        SetAdoFields('T','N');
					        SetAdoFields('Fecha',$fechaini);
					        SetAdoFields('Dato_Aux1',$value['Cuenta']);
					        SetAdoFields('Total',$value['Saldo']);
					        SetAdoFields('Saldo_Actual',$value['Saldo']);
					        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
					        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
					        SetAdoFields("TP","CCXP");
					       // print_r($dias);
					        if($dias > 0 && $dias < 8)
					        {
						        SetAdoFields('Ven_1_a_7',$value['Saldo']);

					        }else if($dias > 8 && $dias < 31)
					        {
						        SetAdoFields('Ven_8_a_30',$value['Saldo']);

					        }else if($dias >30 && $dias < 61)
					        {
						        SetAdoFields('Ven_31_a_60',$value['Saldo']);

					        }else if($dias >60 && $dias < 91)
					        {
						        SetAdoFields('Ven_61_a_90',$value['Saldo']);

					        }else if($dias > 90 && $dias < 181)
					        {
						        SetAdoFields('Ven_91_a_180',$value['Saldo']);

					        }else if($dias >180 && $dias < 361)
					        {
						        SetAdoFields('Ven_181_a_360',$value['Saldo']);

					        }else if($dias > 360)
					        {
						        SetAdoFields('Ven_mas_de_360',$value['Saldo']);
					        }
			        		SetAdoUpdate(); 
  	    	        

// print_r($key.'-');
  	    	  }

// print_r('acabo');die();
  	      }else{

  	        	$saldo=0;
  	        	if($datos=='')
  	        	{
  	        		$datos=array();
  	        	}  	        	
  	        	foreach ($datos as $key => $value) {
  	        		
  	        	

                    $anio = date("Y");
  	        	    $date1 = new DateTime($anio.'01-01');
                    $date2 = new DateTime($parametros['fechaini']);
                    $fechaini = str_replace("-","",$parametros['fechaini']);
		            $fechafin = str_replace("-","",$parametros['fechafin']);		
		
  	    	        //$dias = $parametros['fechafin']-$parametros['fechaini'];
  	    	        $dias = $date1->diff($date2)->days;

  	    	        SetAdoAddNew("Saldo_Diarios");       
  	    	        SetAdoFields('Fecha_Venc',$fechaini);			        
					        SetAdoFields('Comprobante',$value['Sub_Modulos']);
					        SetAdoFields('T','N');
					        SetAdoFields('Fecha',$fechaini);
					        SetAdoFields('Dato_Aux1',$value['Cuenta']);
					        SetAdoFields('Total',$value['Total']);
					        SetAdoFields('Saldo_Actual', $saldo+$value['Total']);
					        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
					        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
					        SetAdoFields("TP","CCXP");
				       // print_r($dias);
				        if($dias > 0 && $dias < 8)
				        {
					        SetAdoFields('Ven_1_a_7',$value['Total']+$saldo);

				        }else if($dias > 8 && $dias < 31)
				        {
					        SetAdoFields('Ven_8_a_30',$value['Total']+$saldo);

				        }else if($dias >30 && $dias < 61)
				        {
					        SetAdoFields('Ven_31_a_60',$value['Total']+$saldo);

				        }else if($dias >60 && $dias < 91)
				        {
					        SetAdoFields('Ven_61_a_90',$value['Total']+$saldo);

				        }else if($dias > 90 && $dias < 181)
				        {
					        SetAdoFields('Ven_91_a_180',$value['Total']+$saldo);

				        }else if($dias >180 && $dias < 361)
				        {
					        SetAdoFields('Ven_181_a_360',$value['Total']+$saldo);

				        }else if($dias > 360)
				        {
					        SetAdoFields('Ven_mas_de_360',$value['Total']+$saldo);
				        }
				       SetAdoUpdate(); 

  	        }
  	      }

  	      return 1;
  	    						
  	    }
  }

  function consultar_tabla_temp($fechafin)
  {
  	return $this->modelo->tabla_temporizada($fechafin);
  }		
}

?>
