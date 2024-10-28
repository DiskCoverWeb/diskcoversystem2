<?php 
include(dirname(__DIR__,2).'/modelo/contabilidad/mayores_sub_cuentaM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');

$controlador = new mayores_sub_cuentaC();
if(isset($_GET['consultar']))
{	
  echo json_encode($controlador->consultar_banco($_POST['parametros']));
}
if(isset($_GET['reporte_pdf']))
{
	$parametros = array(
			'tipoM'=>$_GET["rbl_subcta"],
			'DCCtas'=>$_GET["DCCtas"],
			'DLCtas'=>$_GET["DLCtas"],
			'estado'=>$_GET['rbl_estado'],
			'unoTodos'=>$_GET['rbl_opc'],
			'checkusu'=>isset($_GET["check_usu"]),
			'usuario'=>$_GET['DCUsuario'],
			'desde'=>$_GET['txt_desde'],
			'hasta'=>$_GET['txt_hasta'],	
			'checkagencia'=>isset($_GET['check_agencia']),
			'agencia'=>$_GET['DCAgencia']);
  echo json_encode($controlador->reporte_pdf($parametros));
}
if(isset($_GET['reporte_excel']))
{
	$parametros = array(
			'tipoM'=>$_GET["rbl_subcta"],
			'DCCtas'=>$_GET["DCCtas"],
			'DLCtas'=>$_GET["DLCtas"],
			'estado'=>$_GET['rbl_estado'],
			'unoTodos'=>$_GET['rbl_opc'],
			'checkusu'=>isset($_GET["check_usu"]),
			'usuario'=>$_GET['DCUsuario'],
			'desde'=>$_GET['txt_desde'],
			'hasta'=>$_GET['txt_hasta'],	
			'checkagencia'=>isset($_GET['check_agencia']),
			'agencia'=>$_GET['DCAgencia']);
  echo json_encode($controlador->reporte_excel($parametros));
}

if(isset($_GET['drop']))
{	
  echo json_encode($controlador->cargar_usuario_sucuarsal());
}
if(isset($_GET['DLCtas']))
{	
	$parametros = $_POST['parametros'];
  echo json_encode($controlador->DLCtas($parametros));
}
if(isset($_GET['DCCtas']))
{	
	$parametros = $_POST['parametros'];
  echo json_encode($controlador->DCCtas($parametros));
}
if(isset($_GET['Consultar_Un_Submodulo']))
{	
	$parametros = $_POST['parametros'];
  echo json_encode($controlador->Consultar_Un_Submodulo($parametros));
}

class mayores_sub_cuentaC
{
	private $modelo;
	private $pdf;
	function __construct()
	{
		$this->modelo = new mayores_sub_cuentaM();
		$this->pdf = new cabecera_pdf();
	}

	function cargar_usuario_sucuarsal()
	{
		$usuario = $this->modelo->usuario();
		$sucursal = $this->modelo->sucursal();
		$datos = array('usuario'=>$usuario,'agencia'=>$sucursal);
		return $datos;

	}
	function DCCtas($parametros)
	{
		 $DCCtas = $this->modelo->Ctas_SubMod($parametros['tipoMod']);	
		 // print_r($DCCtas);die();
		 return $DCCtas;
	}
	function DLCtas($parametros)
	{
		// print_r($parametros);die();
		 $DLCtas = $this->modelo->lista_cuentas($parametros['tipoMod'],$parametros['DCCta']);
		 return $DLCtas;
	}

	function Consultar_Un_Submodulo($parametro)
	{
    return $this->modelo->Consultar_Un_Submodulo($parametro);
	}

	

	function reporte_pdf($parametros)
	{
			$datos = $this->modelo->Consultar_Un_Submodulo_datos($parametros);
			$datos1 = $datos;

			$temp = '';
			$datos_uni = array();
			foreach ($datos['datos'] as $key => $value) {
				if($value['Codigo']!=$temp)
				{
					$datos_uni[] = $value;
					$temp = $value['Codigo'];
				}				
			}

		  $desde = str_replace('-','',$parametros['desde']);
			$hasta = str_replace('-','',$parametros['hasta']);	

			// $buscar = array_search("0160000084", $datos['Codigo']);

			// $buscar = $this->getOption ($datos,"0160000084");

  		// print_r($datos);
			// print_r($buscar);die();

			// print_r(array_key_exists(610,$datos['datos']));die();
     

		$titulo = 'MODULOS DE SUBCUENTA DE BLOQUE';
		$sizetable =6.5;
		$mostrar = TRUE;
		$Fechaini = $parametros['desde'] ;//str_replace('-','',$parametros['Fechaini']);
		$Fechafin = $parametros['hasta']; //str_replace('-','',$parametros['Fechafin']);
		$tablaHTML= array();
		$pos = 0;
		$tipo = '';

		switch ($parametros['tipoM']) {
			case 'C':
				$tipo = 'Cta. por Cobrar';
				break;
			  case "P": 
			  $tipo = "Ctas. por Pagar";
			  break;
			  case "I": 
			  $tipo = "Ctas. de Ingresos";
			  break;
			  case "G": 
			  $tipo = "Ctas. de Gastos";
			  break;
			  case "PM":
			  $tipo =  "Valores de Primas";
			  break;
			}


			// print_r($datos_uni);die();
			$deb = 0;
			$sal = 0;
			$cre = 0;
		
		foreach ($datos_uni as $key => $value) {

			// print_r($value);die();
			$cta = $this->modelo->Ctas_SubMod($parametros['tipoM'],$value['Cta']);

			// print_r($cta);die();

			
				$tablaHTML[$pos]['medidas']=array(18,72,18,82);
				$tablaHTML[$pos]['alineado']=array('L','L','L','L');
				$tablaHTML[$pos]['datos']=array('<b>CUENTA:',$cta[0]['Nombre_Cta'],'<b>GRUPO:',$value['Codigo'].'-'.$value['Cliente']); 
				$tablaHTML[$pos]['borde'] = array('LT','T','T','RT');
				$pos+=1;
				$tablaHTML[$pos]['medidas']=array(30,70,35,55);
				$tablaHTML[$pos]['alineado']=array('L','L','L','L');
				$tablaHTML[$pos]['datos']=array('Mayor de Submódulo:',$tipo,'Saldo Anterior S/','');
				$tablaHTML[$pos]['borde'] =array('LB','B','B','RB');
				$pos+=1;
				$tablaHTML[$pos]['medidas']=array(15,15,9,17,69,20,15,15,15);
				$tablaHTML[$pos]['alineado']=array('L','R','L','L','L','R','R','R','R');
				$tablaHTML[$pos]['datos']=array('FECHA','FACTURA','TD','NUMERO','CO N C E P T O','PARCIAL M/E','DEBITOS','CREDITOS','SALDO');
				$tablaHTML[$pos]['estilo']='BU';
				$tablaHTML[$pos]['borde'] =array('LTB','TB','TB','TB','TB','TB','TB','TB','RTB');
				$pos+=1;
			
				//CUERPO			

				foreach ($datos1['datos'] as $key2 => $value2) {

					if($value2['Codigo']==$value['Codigo'])
					{
						$tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
						$tablaHTML[$pos]['alineado']=$tablaHTML[$pos-1]['alineado'];
						$tablaHTML[$pos]['datos']=array($value2['Fecha']->format('Y-m-d'),$value2['Factura'],$value2['TP'],$value2['Numero'],$value2['Concepto'],$value2['Parcial_ME'],$value2['Debitos'],$value2['Creditos'],$value2['Saldo_MN']);
						$tablaHTML[$pos]['borde'] ='LR';
						$deb+=$value2['Debitos'];
						$sal+=$value2['Saldo_MN'];
						$cre+=$value2['Creditos'];
						unset($datos1['datos'][$key2]);
						$pos+=1;
					}
					
				}
				

				$tablaHTML[$pos]['medidas']=array(125,20,15,15,15);
				$tablaHTML[$pos]['alineado']=array('L','R','R','R','R');
				$tablaHTML[$pos]['datos']=array('','T o t a l e s',$deb,$cre,$sal);
				$tablaHTML[$pos]['estilo']='B';
				$tablaHTML[$pos]['borde'] ='T';
				$pos+=1;	
			
				$tablaHTML[$pos]['medidas']=array(190);
				$tablaHTML[$pos]['alineado']=array('L');
				$tablaHTML[$pos]['datos']=array('');
				$tablaHTML[$pos]['estilo']='BU';
				$pos+=1;	
				$deb = 0;
				$sal = 0;


		}
	
		
		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$parametros['desde'],$parametros['hasta'],$sizetable,$mostrar,25,'P');
	}


	function reporte_excel($parametros)
	{
			$datos = $this->modelo->Consultar_Un_Submodulo_datos($parametros);

		  $desde = str_replace('-','',$parametros['desde']);
			$hasta = str_replace('-','',$parametros['hasta']);	

			$datos1 = $datos;

			$temp = '';
			$datos_uni = array();
			foreach ($datos['datos'] as $key => $value) {
				if($value['Codigo']!=$temp)
				{
					$datos_uni[] = $value;
					$temp = $value['Codigo'];
				}				
			}

			// print_r($datos);die();
  		//print_r($datos);
     

		$titulo = 'MODULOS DE SUBCUENTA DE BLOQUE';
		$sizetable =6.5;
		$mostrar = TRUE;
		$Fechaini = $parametros['desde'] ;//str_replace('-','',$parametros['Fechaini']);
		$Fechafin = $parametros['hasta']; //str_replace('-','',$parametros['Fechafin']);
		$tablaHTML= array();
		$pos = 0;
		$tipo = '';

		switch ($parametros['tipoM']) {
			case 'C':
				$tipo = 'Cta. por Cobrar';
				break;
			  case "P": 
			  $tipo = "Ctas. por Pagar";
			  break;
			  case "I": 
			  $tipo = "Ctas. de Ingresos";
			  break;
			  case "G": 
			  $tipo = "Ctas. de Gastos";
			  break;
			  case "PM":
			  $tipo =  "Valores de Primas";
			  break;
			}

				$deb=0;
				$sal=0;
				$cre = 0;

		
		foreach ($datos_uni as $key => $value) {

			// print_r($value);die();
			$cta = $this->modelo->Ctas_SubMod($parametros['tipoM'],$value['Cta']);

			// print_r($cta);die();

				$tablaHTML[$pos]['medidas']=array(15,15,8,15,69,20,15,15,15);
				$tablaHTML[$pos]['datos']=array('','CUENTA:'.$cta[0]['Nombre_Cta'],'','','GRUPO:'.$value['Codigo'].'-'.$value['Cliente'],'','','','');
				$tablaHTML[$pos]['tipo'] ='C';
				$tablaHTML[$pos]['unir'] = array('BCD','EFGHI');
				$tablaHTML[$pos]['col-total'] = 9;
				$pos+=1;
				$tablaHTML[$pos]['medidas']=array(15,15,8,15,69,20,15,15,15);
				$tablaHTML[$pos]['datos']=array('','Mayor de Submódulo:'.$tipo,'','','Saldo Anterior S/','','','','');
				$tablaHTML[$pos]['unir'] = array('BD','EI');
				$tablaHTML[$pos]['tipo'] = 'SUB';
				$pos+=1;
				$tablaHTML[$pos]['medidas']=array(15,15,8,15,69,20,15,15,15);
				$tablaHTML[$pos]['datos']=array('FECHA','FACTURA','TD','NUMERO','CO N C E P T O','PARCIAL M/E','DEBITOS','CREDITOS','SALDO');
				$tablaHTML[$pos]['tipo'] ='C';
				$pos+=1;

				//CUERPO
					foreach ($datos1['datos'] as $key2 => $value2) {
						if($value2['Codigo']==$value['Codigo'])
						{
							$tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
							$tablaHTML[$pos]['datos']=array($value2['Fecha']->format('Y-m-d'),$value2['Factura'],$value2['TP'],$value2['Numero'],$value2['Concepto'],$value2['Parcial_ME'],$value2['Debitos'],$value2['Creditos'],$value2['Saldo_MN']);
							$tablaHTML[$pos]['tipo'] ='N';
							$deb+=$value2['Debitos'];
							$sal+=$value2['Saldo_MN'];
							$cre+=$value2['Creditos'];
						  unset($datos1['datos'][$key2]);
							$pos+=1;
						}					
				}


				$tablaHTML[$pos]['medidas']=array(15,15,8,15,69,20,15,15,15);
				$tablaHTML[$pos]['datos']=array('','','','','','T o t a l e s',$deb,$cre,$sal);
				// $tablaHTML[$pos]['unir'] = array('AF');
				$tablaHTML[$pos]['tipo']='BR';
				$pos+=1;		$pos+=1;	
				$deb= 0 ; 				
				$sal= 0 ; 
				$cre= 0 ; 
				

		}	
      excel_generico($titulo,$tablaHTML);  
	}
}

?>