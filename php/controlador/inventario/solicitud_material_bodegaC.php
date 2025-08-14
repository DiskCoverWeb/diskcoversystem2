<?php
require_once (dirname(__DIR__,2).'/modelo/inventario/solicitud_material_bodegaM.php');
require_once(dirname(__DIR__,2).'/modelo/farmacia/ingreso_descargosM.php');
require_once(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
$_SESSION['INGRESO']['modulo_']='60';

$controlador = new inventario_onlineC();
if (isset($_GET['guardar'])) {
	
	echo json_encode($controlador->guardar_entrega($_POST['parametros']));
}
if (isset($_GET['entrega'])) {
	
	echo json_encode($controlador->lista_entrega());
}
if (isset($_GET['generar_comprobante'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->generar_factura($parametro));
}
if (isset($_GET['pedidos_contratista'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->pedidos_contratista($parametro));
}
if (isset($_GET['pedidos_contratista_detalle'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->pedidos_contratista_detalle($parametro));
}
if (isset($_GET['pedidos_contratista_detalle_check'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->pedidos_contratista_detalle_check($parametro));
}

if (isset($_GET['listar_rubro'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->listar_rubro($parametro));
}

if (isset($_GET['editarCCRubro'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->editarCCRubro($parametro));
}

if (isset($_GET['eliminarLinea'])) {
	$parametro = $_POST['id'];
	echo json_encode($controlador->eliminarLinea($parametro));
}

if (isset($_GET['AprobarSolicitud'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->AprobarSolicitud($parametro));
}

if (isset($_GET['AprobarEntrega'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->AprobarEntrega($parametro));
}

if (isset($_GET['pedidos_contratistaCheck'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->pedidos_contratistaCheck($parametro));
}

if (isset($_GET['GenerarComprobante'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->GenerarComprobante($parametro));
}



class inventario_onlineC
{
	private $modelo;
	private $pdf;
	private $ing_des;
	
	function __construct()
	{
		$this->modelo = new solicitud_material_bodegaM();		
		$this->pdf = new cabecera_pdf();	
		$this->ing_des = new ingreso_descargosM();
		// $this->pdftable = new PDF_MC_Table();			
	}

	function guardar_entrega($parametro)
	{
		// print_r($parametro);die();
		
		$id =count($this->lista_entrega())+1;
		if($parametro['id']=='')
		{
		   SetAdoAddNew("Asiento_K");
		   SetAdoFields('CODIGO_INV',$parametro['codigo']);
		   SetAdoFields('PRODUCTO',$parametro['producto']);
		   SetAdoFields('UNIDAD',$parametro['uni']);
		   SetAdoFields('CANT_ES',$parametro['cant']);
		   SetAdoFields('CTA_INVENTARIO',$parametro['cta_pro']);
		   SetAdoFields('SUBCTA',$parametro['rubro']);		   
		   SetAdoFields('CodigoU',$_SESSION['INGRESO']['Id']);   
		   SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		   SetAdoFields('Procedencia',$parametro['observacion']);
		   SetAdoFields('A_No',$id);
		   SetAdoFields('Fecha_Fab',date('Y-m-d',strtotime($parametro['fecha'])));
		   SetAdoFields('Codigo_Dr',$parametro['bajas_por']);
		   SetAdoFields('TC',$parametro['TC']);
		   SetAdoFields('VALOR_TOTAL',number_format($parametro['total'],2,'.',''));
		   SetAdoFields('CANTIDAD',$parametro['cant']);
		   SetAdoFields('VALOR_UNIT',number_format($parametro['valor'],$_SESSION['INGRESO']['Dec_PVP'],'.',''));
		   SetAdoFields('DH',2);
		   SetAdoFields('CONTRA_CTA',$parametro['cc']);
	   	 SetAdoFields('CodMar',$parametro['codma']);
		   // print_r($datos);die();
		 		  $resp = SetAdoUpdate();

       //actualiza presupuesto
		  		 $presu =$this->modelo->actualizar_trans_presupuesto($parametro);
				   if(count($presu)>0){
						 $consu = $presu[0]['Total'];

						SetAdoAddNew("Trans_Presupuestos");
						SetAdoFields('Total',$consu+$parametro['cant']);

						SetAdoFieldsWhere('ID', $presu[0]['ID']);
						return SetAdoUpdateGeneric();
				   }	
		 	 return $resp;
	    }else
	    {
	    	// print_r($parametro);die();
	     SetAdoAddNew("Asiento_K");
	     SetAdoFields('CODIGO_INV',strval($parametro['codigo']));
		   SetAdoFields('PRODUCTO',$parametro['producto']);
		   SetAdoFields('UNIDAD',$parametro['uni']);
		   SetAdoFields('CANT_ES',$parametro['cant']);
		   SetAdoFields('CTA_INVENTARIO',$parametro['cta_pro']);
		   SetAdoFields('SUBCTA',$parametro['rubro']);
		   SetAdoFields('Consumos',number_format($parametro['bajas'],2,'.',''));
		   SetAdoFields('Procedencia',$parametro['observacion']);
		   SetAdoFields('Fecha_Fab',date('Y-m-d'));
		   SetAdoFields('Codigo_Dr',$parametro['bajas_por']);
		   SetAdoFields('TC',$parametro['TC']);
		   SetAdoFields('VALOR_TOTAL',number_format($parametro['total'],2,'.',''));
		   SetAdoFields('CANTIDAD',$parametro['cant']);
		   SetAdoFields('VALOR_UNIT',number_format($parametro['valor'],$_SESSION['INGRESO']['Dec_PVP'],'.',''));
		   SetAdoFields('CONTRA_CTA',$parametro['cc']);

		   SetAdoFieldsWhere('CODIGO_INV',strval($parametro['ante']));
		   SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
		   SetAdoFieldsWhere('A_No',$id-1);
		   // print_r($datos);die();

		   SetAdoFieldsWhere('ID', $presu[0]['ID']);
			 return SetAdoUpdateGeneric();
	    }
	    // print_r($resp);die();
	}

	function lista_entrega()
	{
		$resp = $this->modelo->lista_entrega();
		// $resp= array_map(array($this, 'encode'), $resp);
		// print_r($resp);die();
		return $resp;
	}

	function generar_factura($parametro)
	{
		try
		{
			$resp = $this->modelo->lista_entrega();
			$codigo = ReadSetDataNum("PS_SERIE_001001", false, True);
			// print_r($codigo);die();
			$orden = 'PS_SERIE_001001_'.$codigo;
			// print_r($orden);die();

			foreach ($resp as $key => $value) {
				// print_r($value);die();
				SetAdoAddNew("Trans_Kardex");
				SetAdoFields('Orden_No',$orden);
				SetAdoFields('T','S');
				SetAdoFields('TP','CD');
			  	SetAdoFields('CodigoU', $value['CodigoU']);
		   		SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		   		SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
		   		SetAdoFields('Codigo_Inv',$value['CODIGO_INV']);
		   		SetAdoFields('Salida',$value['CANT_ES']);
		   		SetAdoFields('Valor_Unitario',$value['VALOR_UNIT']);
		   		SetAdoFields('Valor_Total',$value['VALOR_TOTAL']);
		   		SetAdoFields('Cta_Inv',$value['CTA_INVENTARIO']);
		   		SetAdoFields('Contra_Cta',$value['CONTRA_CTA']);		   		
		   		SetAdoFields('CodigoL',$value['SUBCTA']);		   		
		   		SetAdoFields('Codigo_P',$_SESSION['INGRESO']['CodigoU']);
				SetAdoUpdate();

				$this->modelo->eliminarAsientoK('P','.',$value['CODIGO_INV']);
			}
			return 1;
		}catch(Exception $e)
		{
			return -1;
		}

	}

	function pedidos_contratista($parametro)
	{
		$datos = $this->modelo->pedidos_contratista($parametro['query'],$parametro['fecha']);
		$tr = '';
		return $datos;
		// foreach ($datos as $key => $value) {
		// 	$tr.='<tr>
		// 			<td>'.($key+1).'</td>
		// 			<td><a href="../vista/inicio.php?mod=03&acc=DetalleSolicitudesBodega&orden='.$value['Orden_No'].'">'.$value['Cliente'].'</a></td>
		// 			<td>'.$value['Orden_No'].'</td>
		// 			<td>'.$value['Fecha']->format('Y-m-d').'</td>
		// 			<td>';
		// 			if($value['TC']=='.')
		// 			{
		// 				$tr.='<span class="label label-danger">Para Revision</span>';
		// 			}else
		// 			{
		// 				$tr.='<span class="label label-primary">Para Genera comprobante</span>';
		// 			}
		// 			$tr.='</td>
		// 		</tr>';
		// }

		return $tr;
		// print_r($parametro);die();

	}

	function pedidos_contratistaCheck($parametro)
	{
		$datos = $this->modelo->pedidos_contratista_check($parametro['query'],$parametro['fecha']);
		$tr = '';
		return $datos;
		// foreach ($datos as $key => $value) {
		// 	$tr.='<tr>
		// 			<td>'.($key+1).'</td>
		// 			<td><a href="../vista/inicio.php?mod=03&acc=DetalleSolicitudesBodegaCheck&orden='.$value['Orden_No'].'">'.$value['Cliente'].'</a></td>
		// 			<td>'.$value['Orden_No'].'</td>
		// 			<td>'.$value['Fecha']->format('Y-m-d').'</td>
		// 			<td>';
		// 			if($value['TC']=='.')
		// 			{
		// 				$tr.='<span class="label label-danger">Para Revision</span>';
		// 			}else
		// 			{
		// 				$tr.='<span class="label label-warning">Para Checking</span>';
		// 			}
		// 			$tr.='</td>
		// 		</tr>';
		// }

		return $tr;
		// print_r($parametro);die();

	}

	function pedidos_contratista_detalle($parametro)
	{
		$datos = $this->modelo->lista_entrega_salida(false,$parametro['order']);
		// print_r($datos);die();
		$tr = '';
		$estado = '.';
		foreach ($datos as $key => $value) {
			$estado =$datos[0]['TC'];
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Codigo_Inv'].'</a></td>
					<td>'.$value['Producto'].'</td>';
					if($estado=='GC')
					{
						$tr.='<td>'.$value['Salida'].'</td>';
					}else{
						$tr.='<td><input class="form-control input-xs"  id="txt_salida_'.$value['ID'].'" name ="txt_salida_'.$value['ID'].'"value="'.$value['Salida'].'"></td>';
					}

					$tr.='<td>';
					if($estado=='GC')
					{
						$tr.=$value['Cuenta'];

					}else{
						$tr.='<select id="ddl_linea_cc_'.$value['ID'].'" class="form-select" onchange="cargar_rubro_linea('.$value['ID'].',this.value)">';
							$centroC = $this->modelo->listar_cc_info();

							foreach ($centroC as $key2 => $value2) {
								$select = '';
								if($value2['id'] == $value['Contra_Cta']){$select = 'selected';}
								$tr.='<option value="'.$value2['id'].'" '.$select.'>'.$value2['text'].'</option>';
							}
						$tr.='</select>';
					}
					$tr.='</td>
					<td>';
					if($estado=='GC')
					{
						$tr.=$value['Detalle'];

					}else{
					$tr.='<select class="form-select" id="ddl_linea_rubro_'.$value['ID'].'">';
							$rubro = $this->modelo->listar_rubro(false,$value['Contra_Cta']);
							foreach ($rubro as $key2 => $value2) {
								$select = '';
								if($value2['id'] == $value['CodigoL']){$select = 'selected';}
								$tr.='<option value="'.$value2['id'].'" '.$select.'>'.$value2['text'].'</option>';
							}
						$tr.='</select>';
					}
					$tr.='</td>
					<td>';
					if($estado!='GC')
					{
						$tr.='<button class="btn btn-sm btn-primary" title="Editar linea" onclick="guardar_linea('.$value['ID'].')"><i class="fa fa-save"></i></button>
						<button class="btn btn-sm btn-danger" title="Eliminar linea" onclick="eliminar_linea('.$value['ID'].')"><i class="fa fa-trash"></i></button>';

					}
						
					$tr.='</td>
				</tr>';
		}



		return array('tabla'=>$tr,'datos'=>$datos,'estado'=>$estado);
		// print_r($parametro);die();

	}

	function pedidos_contratista_detalle_check($parametro)
	{
		// print_r($parametro);die();
		$datos = $this->modelo->lista_entrega_salida_check(false,$parametro['order']);
		// print_r($datos);die();
		$tr = '';
		$estado = '.';
		foreach ($datos as $key => $value) {
			$estado =$datos[0]['TC'];
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Codigo_Inv'].'</a></td>
					<td>'.$value['Producto'].'</td>
					<td>'.$value['Salida'].'</td>
					<td>'.$value['Cuenta'].'</td>
					<td>'.$value['Detalle'].'</td>
					<td>
						<input type="checkbox" id="rbl_'.$value['ID'].'" name="rbl_'.$value['ID'].'">						
					</td>
				</tr>';
		}



		return array('tabla'=>$tr,'datos'=>$datos,'estado'=>$estado);
		// print_r($parametro);die();

	}


	function listar_rubro($parametros)
	{
		return $this->modelo->listar_rubro($query='',$parametros['cc']);
	}

	function editarCCRubro($parametro)
	{
		// print_r($parametro);die();
		SetAdoAddNew("Trans_Kardex");
		SetAdoFields('CodigoL',$parametro['rubro']);
		SetAdoFields('Contra_Cta',$parametro['cc']);
		SetAdoFields('Salida',$parametro['salida']);

		SetAdoFieldsWhere('ID', $parametro['ID']);
		return SetAdoUpdateGeneric();
	}

	function eliminarLinea($parametro)
	{
		return $this->modelo->eliminar_linea($parametro);
	}

	function AprobarSolicitud($parametro)
	{
		$datos = $this->modelo->lista_entrega_salida(false,$parametro['order']);
		foreach ($datos as $key => $value) {
			SetAdoAddNew("Trans_Kardex");
			SetAdoFields('TC','K');

			SetAdoFieldsWhere('ID', $value['ID']);
			SetAdoUpdateGeneric();		
		}
		return 1;
	}
	function AprobarEntrega($parametro)
	{
		$datos = $this->modelo->lista_entrega_salida_check(false,$parametro['orden']);
		foreach ($datos as $key => $value) {
			SetAdoAddNew("Trans_Kardex");
			SetAdoFields('TC','GC');

			SetAdoFieldsWhere('ID', $value['ID']);
			SetAdoUpdateGeneric();		
		}
		return 1;
	}

	function GenerarComprobante($parametro)
	{
		$t_no = $_SESSION['INGRESO']['modulo_'];
		if(isset($parametro['T_No']))
		{
			$t_no = $parametro['T_No'];
		}
		// $datos = $this->modelo->pedidos_contratista_comprobante($parametro['order']);
		// print_r($datos);die();
		// $cliente = Leer_Datos_Clientes($Codigo_CIRUC_Cliente,$Por_Codigo=true,$Por_CIRUC=false,$Por_Cliente=false)

		$orden = $parametro['order'];

		$datos = $this->modelo->pedidos_contratista_comprobante($orden);
		$nombre = $datos[0]['Cliente'];
		$ruc = $datos[0]['CI_RUC'];
		$asientos_SC = $this->modelo->datos_asiento_SC($orden);

		// print_r($asientos_SC);die();
		$parametros_debe = array();
		$parametros_haber = array();
		// $fecha = date('Y-m-d');

		// print_r($asientos_SC);die();
		foreach ($asientos_SC as $key => $value) {
			 $cuenta = $this->modelo->catalogo_cuentas($value['CONTRA_CTA']);
			 $sub = $this->modelo->catalogo_subcuentas($value['CodigoL']);

			 // print_r($cuenta);
			 // print_r($sub);die();
			$parametros = array(
                    'be'=>$cuenta[0]['Cuenta'],
                    'ru'=> '',
                    'co'=> $value['CONTRA_CTA'],// codigo de cuenta cc
                    'tip'=>$cuenta[0]['TC'],//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
                    'tic'=> 1, //debito o credito (1 o 2);
                    'sub'=> $value['CodigoL'], //Codigo se trae catalogo subcuenta
                    'sub2'=>$cuenta[0]['Cuenta'],//nombre del beneficiario
                    'fecha_sc'=> $value['Fecha']->format('Y-m-d'), //fecha 
                    'fac2'=>0,
                    'mes'=> 0,
                    'valorn'=> round($value['total'],2),//valor de sub cuenta 
                    'moneda'=> 1, /// moneda 1
                    'Trans'=>$sub[0]['Detalle'],//detalle que se trae del asiento
                    'T_N'=> $t_no,
                    't'=> $cuenta[0]['TC'],                        
                  );

			// print_r($parametros);die();
      		$this->ing_des->generar_asientos_SC($parametros);
		}
			
		$asiento_debe = $this->modelo->datos_asiento_debe($orden);
		// print_r($asiento_debe);die();
		$fecha = $asiento_debe[0]['fecha']->format('Y-m-d');
		
		foreach ($asiento_debe as $key => $value) 
		{
			// print_r($value);die();
			$cuenta = $this->modelo->catalogo_cuentas($value['cuenta']);		
				$parametros_debe = array(
				 "va" =>round($value['total'],2),//valor que se trae del otal sumado
                  "dconcepto1" =>$cuenta[0]['Cuenta'],
                  "codigo" => $value['cuenta'], // cuenta de codigo de 
                  "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                  "efectivo_as" =>$value['fecha']->format('Y-m-d'), // observacion si TC de catalogo de cuenta
                  "chq_as" => 0,
                  "moneda" => 1,
                  "tipo_cue" => 1,
                  "cotizacion" => 0,
                  "con" => 0,// depende de moneda
                  "t_no" => $t_no,
			);

			// print_r($cuenta);die();
			$this->ing_des->ingresar_asientos($parametros_debe);
		}
		// print_r('expresion');die();

        // asiento para el haber
		$asiento_haber  = $this->modelo->datos_asiento_haber($orden);

		// print_r($asiento_haber);die();
		foreach ($asiento_haber as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($value['cuenta']);		
			// print_r($cuenta);die();	
				$parametros_haber = array(
                  "va" =>round($value['total'],2),//valor que se trae del otal sumado
                  "dconcepto1" =>$cuenta[0]['Cuenta'],
                  "codigo" => $value['cuenta'], // cuenta de codigo de 
                  "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                  "efectivo_as" =>$value['fecha']->format('Y-m-d'), // observacion si TC de catalogo de cuenta
                  "chq_as" => 0,
                  "moneda" => 1,
                  "tipo_cue" => 2,
                  "cotizacion" => 0,
                  "con" => 0,// depende de moneda
                  "t_no" => $t_no,
                );
                $this->ing_des->ingresar_asientos($parametros_haber);
		}


		// print_r('expression');die();

		// print_r($fecha.'-'.$nombre.'-'.$ruc);die();
		// $parametros = array('tip'=> 'CD','fecha'=>$fecha);
	 //    $num_comprobante = $this->modelo->numero_comprobante($parametros);

	    $num_comprobante = numero_comprobante1('Diario',true,true,$fecha);
	    $dat_comprobantes = $this->modelo->datos_comprobante($t_no);
	    $debe = 0;
		$haber = 0;
		foreach ($dat_comprobantes as $key => $value) {
			$debe+=$value['DEBE'];
			$haber+=$value['HABER'];
		}
		if(strval($debe)==strval($haber))
		{
			if($debe !=0 && $haber!=0)
			{
				 $parametro_comprobante = array(
        	        'ru'=> $ruc, //codigo del cliente que sale co el ruc del beneficiario codigo
        	        'tip'=>'CD',//tipo de cuenta contable cd, etc
        	        "fecha1"=> $fecha,// fecha actual 2020-09-21
        	        'concepto'=>'Salida de inventario para '.$nombre.' con CI: '.$ruc.' el dia '.$fecha, //detalle de la transaccion realida
        	        'totalh'=> round($haber,2), //total del haber
        	        'num_com'=> '.'.date('Y', strtotime($fecha)).'-'.$num_comprobante, // codigo de comprobante de esta forma 2019-9000002
        	        't_no'=>$t_no,
        	        );
				 // print_r($nombre);print_r($ruc);print_r($fecha);
				 // print_r($parametro_comprobante);die();
                $resp = $this->ing_des->generar_comprobantes($parametro_comprobante);
                // $cod = explode('-',$num_comprobante);
                // die();
                if($resp==$num_comprobante)
                {
                	if($this->ingresar_trans_kardex_salidas($orden,$num_comprobante)==1)
                	{
                		 	// mayorizar_inventario_sp();
                			return array('resp'=>1,'com'=>$num_comprobante);
                		
                	}else
                	{
                		return array('resp'=>-1,'com'=>'Uno o todos No se pudo registrar en Trans_Kardex');
                	}
                }else
                {
        	        return array('resp'=>-1,'com'=>$resp);
                }

			}else
			{
				// print_r($debe."-".$haber); 
				 return array('resp'=>-1,'com'=>'Los resultados son 0');

			}
		}else
		{
			$this->modelo->eliminar_asieto();
			$this->modelo->eliminar_aiseto_sc($fecha);
			return array('resp'=>-1,'com'=>'No coinciden','debe'=>$debe,'haber'=>$haber);

		}
	}

	function ingresar_trans_kardex_salidas($orden,$comprobante)
	{
		$datos = $this->modelo->pedidos_contratista_comprobante($orden);
		foreach ($datos as $key => $value) {
			SetAdoAddNew("Trans_Kardex");
			SetAdoFields('TC','.');
			SetAdoFields('Numero',$comprobante);
			SetAdoFields('T','N');

			SetAdoFieldsWhere('ID', $value['ID']);
			SetAdoUpdateGeneric();		
		}

		return 1;
	}

}
?>