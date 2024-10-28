<?php 
include('../modelo/generar_comprobanteM.php');

/**
 * 
 */
$controlador = new generar_comprobantes();
if(isset($_GET['facturar']))
{
	$orden = $_POST['orden'];
    $ruc = $_POST['ruc'];
    $area = $_POST['area'];
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];

	echo json_encode($controlador->generar_factura($orden,$ruc,$area,$nombre,$fecha));
}

class generar_comprobante
{
	private $modelo;
	function __construct(argument)
	{
		$this->modelo = new generar_comprobanteM()
	}

	function generar_factura($orden,$ruc,$area,$nombre,$fecha)
	{
		
		$negativos = '';
		$datos = $this->modelo->cargar_pedidos($orden,$area,$fecha);
		foreach ($datos as $key => $value) 
		{
			$costo =  $this->modelo->costo_venta($value['CODIGO_INV']);
			$nega = 0;
			if(empty($costo))
			{
				$costo[0]['Costo'] = 0;
				$costo[0]['Existencia'] = 0;
			}else
			{
				$exis = number_format($costo[0]['Existencia']-$value['CANTIDAD'],2);
				if($exis<0)
				{
					$negativos.=$value['CODIGO_INV'].',';
				}
			}
		}
		$negativos = substr($negativos,0,-1);
		// if($this->modelo->misma_fecha($orden,$ruc)==-1)
		// {
		// 	return array('resp'=>-3,'com'=>'');
		// }
		$asientos_SC = $this->modelo->datos_asiento_SC($orden,$fecha,$negativos);

		// print_r($asientos_SC);die();

		// print_r($asientos_SC);die();

		$parametros_debe = array();
		$parametros_haber = array();
		// $fecha = date('Y-m-d');

		// print_r($asientos_SC);die();
		foreach ($asientos_SC as $key => $value) {
			 $cuenta = $this->modelo->catalogo_cuentas($value['CONTRA_CTA']);
			 $sub = $this->modelo->catalogo_subcuentas($value['SUBCTA']);
			$parametros = array(
                    'be'=>$cuenta[0]['Cuenta'],
                    'ru'=> '',
                    'co'=> $value['CONTRA_CTA'],// codigo de cuenta cc
                    'tip'=>$cuenta[0]['TC'],//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
                    'tic'=> 1, //debito o credito (1 o 2);
                    'sub'=> $value['SUBCTA'], //Codigo se trae catalogo subcuenta
                    'sub2'=>$cuenta[0]['Cuenta'],//nombre del beneficiario
                    'fecha_sc'=> $value['Fecha_Fab']->format('Y-m-d'), //fecha 
                    'fac2'=>0,
                    'mes'=> 0,
                    'valorn'=> round($value['total'],2),//valor de sub cuenta 
                    'moneda'=> 1, /// moneda 1
                    'Trans'=>$sub[0]['Detalle'],//detalle que se trae del asiento
                    'T_N'=> '99',
                    't'=> $cuenta[0]['TC'],                        
                  );
                  $this->modelo->generar_asientos_SC($parametros);
		}

		// print_r('expression');die();

		//asientos para el debe
		$asiento_debe = $this->modelo->datos_asiento_debe($orden,$fecha,$negativos);
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
                  "t_no" => '99',
			);
				 $this->modelo->ingresar_asientos($parametros_debe);
		}

        // asiento para el haber
		$asiento_haber  = $this->modelo->datos_asiento_haber($orden,$fecha,$negativos);
		foreach ($asiento_haber as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($value['cuenta']);			
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
                  "t_no" => '99',
                );
                $this->modelo->ingresar_asientos($parametros_haber);
		}


		// print_r('expression');die();

		// print_r($fecha.'-'.$nombre.'-'.$ruc);die();
		// $parametros = array('tip'=> 'CD','fecha'=>$fecha);
	 //    $num_comprobante = $this->modelo->numero_comprobante($parametros);

	    $num_comprobante = numero_comprobante1('Diario',true,true,$fecha);
	    $dat_comprobantes = $this->modelo->datos_comprobante();
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
        	        );
				 // print_r($nombre);print_r($ruc);print_r($fecha);
				 // print_r($parametro_comprobante);die();
                $resp = $this->modelo->generar_comprobantes($parametro_comprobante);
                // $cod = explode('-',$num_comprobante);
                // die();
                if($resp==$num_comprobante)
                {
                	if($this->ingresar_trans_kardex_salidas($orden,$num_comprobante,$fecha,$area,$ruc,$nombre,$negativos)==1)
                	{
                		$resp = $this->modelo->eliminar_aiseto_K($orden,$fecha,$negativos);
                		if($resp==1)
                		{
                			mayorizar_inventario_sp();
                			return array('resp'=>1,'com'=>$num_comprobante);
                		}else
                		{
                			return array('resp'=>-1,'com'=>'No se pudo eliminar asiento_K');
                		}
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

	function ingresar_trans_kardex_salidas($orden,$comprobante,$fechaC,$area,$ruc,$nombre,$negativos)
    {
		$datos_K = $this->modelo->cargar_pedidos($orden,$area,$fechaC,$negativos);
		// print_r($datos_K);
		// $comprobante = explode('.',$comprobante);
		// $comprobante = explode('-',trim($comprobante[1]));
		$comprobante = $comprobante;
		$resp = 1;
		$lista = '';
		foreach ($datos_K as $key => $value) 
		{
		   $datos_inv = $this->modelo->lista_hijos_id($value['CODIGO_INV']);
		   // print_r($datos_inv.'-'.$datos_inv[0]['id']);die();
		    $cant[2] = 0;
		   if(count($datos_inv)>0)
		   {
		   	 $cant = explode(',',$datos_inv[0]['id']);
		   }

		   	SetAdoAddNew("Trans_Kardex");
		    SetAdoFields('Codigo_Inv',$value['CODIGO_INV']); 
		    SetAdoFields('Fecha',$fechaC); 
		    SetAdoFields('Numero',$comprobante);  
		    SetAdoFields('T','N'); 
		    SetAdoFields('TP','CD'); 
		    SetAdoFields('Codigo_P',$ruc); 
		    SetAdoFields('Cta_Inv',$value['CTA_INVENTARIO']); 
		    SetAdoFields('Contra_Cta',$value['CONTRA_CTA']); 
		    SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']); 
		    SetAdoFields('Salida',$value['CANTIDAD']); 
		    SetAdoFields('Valor_Unitario',round($value['VALOR_UNIT'],2)); 
		    SetAdoFields('Valor_Total',round($value['VALOR_TOTAL'],2)); 
		    SetAdoFields('Costo',round($value['VALOR_UNIT'],2)); 
		    SetAdoFields('Total',round($value['VALOR_TOTAL'],2));
		    SetAdoFields('Existencia',round(($cant[2]),2)-round(($value['CANTIDAD']),2));
		    SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);
		    SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		    SetAdoFields('CodBodega','01');
		    SetAdoFields('CodigoL',$value['SUBCTA']);
	    	SetAdoFields('Detalle','Salida de inventario para '.$nombre.' con CI: '.$ruc.' el dia '.$fechaC);
		    SetAdoFields('Orden_No',$orden);

		    // print_r($datos);
		     if(SetAdoUpdate()!=1)
		     {
		     	$resp = 0;
		     }
		     $lista.="'".$value['CODIGO_INV']."',"; 
		}
		$lista = substr($lista,0,-1);
		if($this->modelo->actualizo_trans_kardex($lista) == -1)
		{
			$resp = 0;
		}
	    // print_r($resp);die();
		return $resp;

	}
}
?>