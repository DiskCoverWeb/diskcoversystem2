<?php 
include(dirname(__DIR__,2).'/modelo/contabilidad/comproM.php');
include(dirname(__DIR__,3).'/lib/fpdf/reporte_comp.php');
/**
 * 
 */
$controlador = new comproC();
if(isset($_GET['ExcelResultadoComprobante']))
{
	echo json_encode($controlador->ExcelResultadoComprobante($_GET));
}else
if(isset($_GET['reporte']))
{
	$parametros = $_GET;
	echo json_encode($controlador->reporte_com($parametros));
}else
if(isset($_GET['anular_comprobante']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->anular_comprobante($parametros));
}else
if(isset($_GET['ActualizarFechaComprobante']))
{
	echo json_encode($controlador->ActualizarFechaComprobante($_POST));
}else
if(isset($_GET['Eliminar_Cuenta']))
{
	echo json_encode($controlador->Eliminar_Cuenta($_POST));
}

class comproC 
{
	private $modelo;
	private $pdf;
	function __construct()
	{
		$this->modelo = new comproM();
		$this->pdf = new PDF();
	}

	function reporte_com($parametro)
	{
		$comprobante= $parametro['comprobante'];
		$tp= $parametro['TP'];
		// print_r($comprobante);die();
		$datos_com = $this->modelo->Listar_el_Comprobante($comprobante,$tp);
		if(count($datos_com)>0)
		{		
			//para ver cheuqes en tipo comprobante CE Y CI
			$datos_cheques = $this->modelo->cheques_debe($comprobante);
			$stmt8_count = count($datos_cheques);
			$datos_cheques_haber = $this->modelo->cheques_haber($comprobante);
			$stmt9_count = count($datos_cheques_haber);

			$TipoComp = $datos_com[0]['TP'];
			$Numero = $datos_com[0]['Numero'];
			// print_r($datos_com);die();
			$consulta = $this->modelo->comprobante_agrupado($Numero,$TipoComp);
			$concepto = 'Ninguno';
			// print_r($consulta);die();
			if(count($consulta)>0)
			{
				$t = $consulta[0]['T'];
				$Fecha = $consulta[0]['Fecha']->format('Y-m-d');
				$codigoB = $consulta[0]['Codigo_B'];
				$beneficiario = $consulta[0]['Cliente'];
				$concepto = $consulta[0]['Concepto'];
				$efectivo = number_format($consulta[0]['Efectivo'],2, ',', '.');
				//$num = NumerosEnLetras::convertir(1988208.99);
				//echo $num;
				//die();
				$est="Normal";
				if($t == 'A')
				{
					$est="Anulado";
				}
				$usuario= $consulta[0]['Nombre_Completo'];


				//Listar las Transacciones
				$transacciones = $this->modelo->Listar_las_Transacciones($Numero,$TipoComp);
				$stmt2_count = count($transacciones);
				

				//Llenar Bancos
				$llenar_ban = $this->modelo->llenar_banco($Numero,$TipoComp);			
				$stmt3_count = count($llenar_ban);
				
				//Listar las Retenciones del IVA
				$retencion = $this->modelo->Retenciones_IVA($Numero,$TipoComp);			
				$stmt4_count = count($retencion);


				//Listar las Retenciones de la Fuente
				$retencion_fuen = $this->modelo->Retenciones_Fuente($Numero,$TipoComp,$Fecha);			
				$stmt5_count = count($retencion_fuen);
				
				//Llenar SubCtas
				$subcta = $this->modelo->llenar_SubCta($Numero,$TipoComp);
				$stmt6_count = count($subcta);

				// print_r($parametro);die();
				//llamamos a los pdf
				if($TipoComp=='CD')
				{
					imprimirCD($datos_com, $transacciones, $retencion, $retencion_fuen, $subcta, $consulta, $Numero,null,null,null,1,$stmt2_count,$stmt4_count,$stmt5_count,$stmt6_count);
				}
				if($TipoComp=='CI')
				{
					imprimirCI($datos_com, $transacciones, $retencion, $retencion_fuen, $subcta, $consulta, $datos_cheques_haber, $Numero,null,null,null,0,$stmt2_count,$stmt4_count,
					$stmt5_count,$stmt6_count,$stmt9_count);	
				}
				if($TipoComp=='CE')
				{
					imprimirCE($datos_com, $transacciones, $retencion, $retencion_fuen, $subcta, $consulta, $datos_cheques, $Numero,null,null,null,1,$stmt2_count,$stmt4_count,
					$stmt5_count,$stmt6_count,$stmt8_count);
				}
				if($TipoComp=='ND')
				{
					imprimirND($datos_com, $transacciones, $retencion, $retencion_fuen, $subcta, $consulta, $Numero,null,null,null,1,$stmt2_count,$stmt4_count,$stmt5_count,$stmt6_count);
				}
				if($TipoComp=='NC')
				{
					imprimirNC($datos_com, $transacciones, $retencion, $retencion_fuen, $subcta, $consulta, $Numero,null,null,null,1,$stmt2_count,$stmt4_count,$stmt5_count,$stmt6_count);
				}
				

			}


		}else
		{
				echo "No existen datos";
		}
		
	}

	function anular_comprobante($parametros)
	{
		$datos_com = $this->modelo->Listar_el_Comprobante($parametros['numero'],$parametros['TP']);
		$Co = $datos_com[0];

		$CompCierre = false;
		// 'Co = ObtenerNumeroDeComp
		$FechaInicial = $Co['Fecha'];
		$FechaFinal = $Co['Fecha'];
		if(strpos($Co['Concepto'], "Cierre de Caja de") !== false){ $CompCierre = true; }

		if ($CompCierre) {
			if (strpos($Co['Concepto'], "Cierre de Caja de Cuentas por Cobrar") !== false) {
				$EsCxC = true;
			} else {
				$EsCxC = false;
			}

			$UnaFecha = true;
			for ($IdF = 1; $IdF <= strlen($Co['Concepto']); $IdF++) {
				$Mifecha = trim(substr($Co['Concepto'], $IdF, 10));
				if (strtotime($Mifecha) !== false && strlen($Mifecha)==10) {
					if (date('Y', strtotime($Mifecha)) >= 1900) {
						if ($UnaFecha) {
							$FechaInicial = $Mifecha;
							$FechaFinal = $Mifecha;
							$UnaFecha = false;
							$IdF = $IdF + 10;
						} else {
							$FechaFinal = $Mifecha;
							$IdF = $IdF + 10;
						}
					}
				}
			}
		}

		$FechaIni = BuscarFecha($FechaInicial);
		$FechaFin = BuscarFecha($FechaFinal);
		$AnularComprobanteDe = "WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND Item = '".$Co['Item']."' 
		AND TP = '".$Co['TP']."' 
		AND Numero = ".$Co['Numero']." ";

		Control_Procesos("A", "Anulo Comprobante de: " .$Co['TP']. " No. " .$Co['Numero']);
		if(strpos($Co['Concepto'], "(ANULADO)") !== false){
			$Contra_Cta = $Co['Concepto'];
		}else{
			$MotivoAnulacion =  $parametros['Motivo_Anular'];
			$Contra_Cta = "(ANULADO) ";
			if($MotivoAnulacion <> ""){
				$Contra_Cta = $Contra_Cta." [MOTIVO: ".$MotivoAnulacion."], ";
				$Contra_Cta = $Contra_Cta.$Co['Concepto'];
			}
		}
		$Contra_Cta = substr($Contra_Cta, 0, 120);
		//'Actualizamos Comprobante
		$this->modelo->Actualizamos_Comprobante($Contra_Cta,$AnularComprobanteDe);
		// 'Actualizar Transacciones
		$this->modelo->Actualizar_Transacciones($AnularComprobanteDe);		     
		// 'Actualizar Trans_SubCtas
		$this->modelo->Actualizar_Trans_SubCtas($AnularComprobanteDe);

		// 'Actualizar Retencion
		$this->modelo->Actualizar_Retencion($AnularComprobanteDe);		     

		//'Eliminamos el Rol de Pagos
		$this->modelo->Rol_de_Pagos($AnularComprobanteDe);
		//'Actualizar Kardex
		if($CompCierre){
			$this->modelo->Trans_Kardex_update_cierre($AnularComprobanteDe,$FechaIni,$FechaFin);
		}else{
			$datos = $this->modelo->Trans_Kardex($AnularComprobanteDe);
			foreach ($datos as $key => $value) {
				$Codigo = $value["Codigo_Inv"];
				$this->modelo->Trans_Kardex_update($Co['Item'],$Codigo);
			}			        		         
			$this->modelo->Trans_Kardex_delete($AnularComprobanteDe);
		}

		//'Actualizar las Ctas a mayoriazar
		$datos = $this->modelo->Transacciones($AnularComprobanteDe);
		foreach ($datos as $key => $value) {
			// 'Determinamos que la cuenta ya fue mayorizada
			$SubCta = $value["Cta"];
			$this->modelo->Transacciones_update($SubCta);	
		}
	}

	function ActualizarFechaComprobante($parametros)
	{
		extract($parametros);

		$_SESSION['Co']['TP'] = $TP;
		$_SESSION['Co']['Numero'] = $Numero;

		Actualiza_Fecha_Tabla("Comprobantes", $MBFecha);
        Actualiza_Fecha_Tabla("Trans_Air", $MBFecha);
        Actualiza_Fecha_Tabla("Trans_Compras", $MBFecha);
        Actualiza_Fecha_Tabla("Trans_Exportaciones", $MBFecha);
        Actualiza_Fecha_Tabla("Trans_Importaciones", $MBFecha);
        Actualiza_Fecha_Tabla("Trans_Kardex", $MBFecha);
        Actualiza_Fecha_Tabla("Trans_Rol_Pagos", $MBFecha);
        Actualiza_Fecha_Tabla("Trans_SubCtas", $MBFecha);
        Actualiza_Fecha_Tabla("Trans_Ventas", $MBFecha);
        Actualiza_Fecha_Tabla("Transacciones", $MBFecha);

        Actualiza_Procesado_Tabla("Transacciones", true);
        Actualiza_Procesado_Tabla("Trans_SubCtas", true);
        Actualiza_Procesado_Tabla("Trans_Kardex", true);
	}

	function Eliminar_Cuenta($parametros)
	{
		extract($parametros);
		$ID_Temp = 0;
		$_SESSION['Co']['TP'] = $TP;
		$_SESSION['Co']['Numero'] = $Numero;
		
		Actualiza_Procesado_Tabla("Transacciones", true);
        Actualiza_Procesado_Tabla("Trans_SubCtas", true);
        Actualiza_Procesado_Tabla("Trans_Kardex", true);
        
        Elimina_Cuenta_Tabla("Transacciones", "Cta", $Cta, $ID_Temp);
        Elimina_Cuenta_Tabla("Trans_SubCtas", "Cta", $Cta);
        Elimina_Cuenta_Tabla("Trans_Air", "Cta_Retencion", $Cta);
        Elimina_Cuenta_Tabla("Trans_Compras", "Cta_Servicio", $Cta);
        Elimina_Cuenta_Tabla("Trans_Compras", "Cta_Bienes", $Cta);
        Elimina_Cuenta_Tabla("Trans_Kardex", "Cta_Inv", $Cta);
        Elimina_Cuenta_Tabla("Trans_Kardex", "Contra_Cta", $Cta);
	}

	function ExcelResultadoComprobante($parametros)
	{
		extract($parametros);
		if (!isset($_SESSION['FListComprobante']['Contabilizacion']) || $_SESSION['FListComprobante']['Contabilizacion'] == "") {
            return 'Sin datos';
        }
        $medidas = array(10, 25, 15, 15, 15, 50, 13, 14, 15, 12, 5, 13, 14, 10);
        return exportar_excel_generico_SQl("Contabilizacion Comprobante No. ".$Numero." de ".$fecha." - ".$concepto, $_SESSION['FListComprobante']['Contabilizacion'],$medidas,[],true);
	}
}
?>