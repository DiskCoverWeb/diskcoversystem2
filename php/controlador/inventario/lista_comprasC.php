<?php 

require_once(dirname(__DIR__,2).'/modelo/inventario/lista_comprasM.php');
require_once(dirname(__DIR__,2).'/modelo/farmacia/ingreso_descargosM.php');

require_once(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');

$controlador = new lista_comprasC();


if(isset($_GET['pedidos_compra_contratista']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->pedidos_compra_contratista($parametros));
}


if(isset($_GET['pedidos_compra_solicitados']))
{
	$query = $_POST['parametros'];
	
	echo json_encode($controlador->pedidos_compra_solicitados($query));
}

if(isset($_GET['lineas_compras_solicitados']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_compras_solicitados($parametros));
}
if(isset($_GET['imprimir_pdf']))
{
	$orden = '';
	if(isset($_GET['orden_pdf']))
	{
		$orden = $_GET['orden_pdf'];
	}
	echo json_encode($controlador->imprimir_pdf($orden));
}
if(isset($_GET['imprimir_excel']))
{
	$orden = '';
	if(isset($_GET['orden_pdf']))
	{
		$orden = $_GET['orden_pdf'];
	}
	echo json_encode($controlador->imprimir_excel($orden));
}

if(isset($_GET['grabar_kardex']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->grabar_kardex($parametros));
}

/**
 * 
 */
class lista_comprasC
{
    private $modelo;	
    private $ingDescargos;
	function __construct()
	{

        $this->modelo = new lista_comprasM();
     	$this->ingDescargos = new  ingreso_descargosM;

	}

	function pedidos_compra_contratista($parametros)
	{
		$datos = $this->modelo->pedidos_compra_contratista(false,false,$parametros['fecha'],$parametros['query']);
		$tr = '';
		$total = 0;
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td><a href="inicio.php?mod='.$_SESSION['INGRESO']['modulo_'].'&acc=detalle_compra&orden='.$value['Orden_No'].'">'.$value['Cliente'].'</a></td>
					<td>'.$value['Orden_No'].'</td>					
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>'.$value['Fecha_Ent']->format('Y-m-d').'</td>
					<td>'.$value['Total'].'</td>					
					<td>
						<button type="button" class="btn btn-sm btn-default" onclick="imprimir_pdf(\''.$value['Orden_No'].'\')" ><i class="fa fa-file-pdf-o"></i></butto>
						<button type="button" class="btn btn-sm btn-default" onclick="imprimir_excel(\''.$value['Orden_No'].'\')" ><i class="fa fa-file-excel-o"></i></butto>
					</td>					
				</tr>';
				$total+=$value['Total'];
		}
		return $tr;
	}

	function pedidos_compra_solicitados($parametros)
	{
		$datos = $this->modelo->pedidos_compra_solicitados($parametros['orden']);
		return $datos;
		// print_r($datos);die();
	}
	function  lineas_compras_solicitados($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->lineas_compras_solicitados_proveedores($parametros['orden']);
		$tr = '';
		foreach ($datos as $key => $value) {
			$tr.='<tr><td colspan="9"><b>'.$value['Cliente'].'</b></td></tr>';
			$lineas = $this->modelo->lineas_compras_solicitados($parametros['orden'],false,$value['CodigoC']);
			$total_prov = 0;
			foreach ($lineas as $key2 => $value2) {
				$total_prov = $total_prov+number_format($value2['Total'],2,'.','');
				$tr.='<tr>
					<td>'.($key2+1).'</td>
					<td>'.$value2['Codigo_Inv'].'</td>
					<td>'.$value2['Producto'].'</td>
					<td width="20px">'.$value2['Cantidad'].'</td>
					<td>'.number_format($value2['Precio'],$_SESSION['INGRESO']['Dec_PVP'],'.','').'</td>
					<td>'.$value2['Fecha']->format('Y-m-d').'</td>
					<td>'.$value2['Fecha_Ent']->format('Y-m-d').'</td>					
					<td>'.number_format($value2['Total'],2,'.','').'</td>				
					<td>'.$value2['proveedor'].'</td>					
				</tr>';
			}			
			$tr.='<tr><td colspan="6"></td><td>TOTAL</td><td><b>'.$total_prov.'</b></td><td></td></tr>';			
		}
		return $tr;
	}

	function imprimir_pdf($orden)
	{


		$pdf = new cabecera_pdf();  
		$titulo = "O R D E N  D E   C O M P R A ";
		$mostrar = true;
		$sizetable =6;
		$tablaHTML = array();
		$datos_pedido = $this->modelo->pedidos_compra_x_proveedor($orden);
		$pos = 0;
		foreach ($datos_pedido as $key => $value) {

			$ali =array("L","L");
			$medi =array(40,130,40,100);
			$tablaHTML[$pos+0]['medidas']=$medi;
			$tablaHTML[$pos+0]['alineado']=$ali;
			$tablaHTML[$pos+0]['datos']= array('<b>Numero:',$value['Orden_No'],'<b>Dias desde la aprobacion',2);
			$tablaHTML[$pos+0]['newpag'] = 1;

			$tablaHTML[$pos+1]['medidas']=$medi;
			$tablaHTML[$pos+1]['alineado']=$ali;
			$tablaHTML[$pos+1]['datos']= array('<b>Contratista:',$value['Cliente'],'<b>Ahorro:','0.0');

			$tablaHTML[$pos+2]['medidas']=$medi;
			$tablaHTML[$pos+2]['alineado']=$ali;
			$tablaHTML[$pos+2]['datos']= array('<b>Precio Referencial Total:',$value['Total']);

			$tablaHTML[$pos+3]['medidas']=$medi;
			$tablaHTML[$pos+3]['alineado']=$ali;
			$tablaHTML[$pos+3]['datos']= array('<b>Proveedor:',$value['proveedor']);
			


			$datos_lineas = $this->modelo->lineas_compras_solicitados($orden,false,$value['CodigoC']);
			$tablaHTML[$pos+4]['medidas']=array(8,35,20,50,25,13,13,13,13,13,13,15,15,15,15);
			$tablaHTML[$pos+4]['alineado']=array('C','L','L','L','L','L','L','R','R','R','R','R','R','R');
			$tablaHTML[$pos+4]['datos']=array('ITEM','FAMILIA','CODIGO','ITEM','MARCAS','CANT','T0%','T5%','T15%','IVA 5%','IVA 15%','TOTAL','TOTAL GLOBAL','PRECIO REFERENCIAL','DIFERENCIA');
			$tablaHTML[$pos+4]['estilo']='B';
			$tablaHTML[$pos+4]['borde'] = '1';
			$tablaHTML[$pos+4]['size'] = 5;

			$cabecera_medi = $pos+4;
			// print_r($datos_lineas);die();

			$pos = $pos+5;
			foreach ($datos_lineas as $key => $value) {
				// print_r($value);die();
				$codigo = $value['Codigo_Inv'];
				$resp = true;
				while ($resp) {
					$posicion = strrpos($codigo, '.');
					// Verificar que el punto fue encontrado
					if ($posicion !== false) {
					    $códigoModificado = substr($codigo, 0, $posicion);
					    $fami = $this->modelo->buscar_familia($códigoModificado);
					    if(count($fami)==0){$codigo = $códigoModificado; }else{$resp = false;}
					}
				}
				
				// print_r($fami);die();
			  $tablaHTML[$pos]['medidas']=$tablaHTML[$cabecera_medi]['medidas'];
			  $tablaHTML[$pos]['alineado']=$tablaHTML[$cabecera_medi]['alineado'];
			  $tablaHTML[$pos]['datos']=array($key+1,$fami[0]['Producto'],$value['Codigo_Inv'],$value['Producto'],$value['Marca'],$value['Cantidad'],'','','','','',$value['Precio'],$value['Cantidad'],$value['Total'],"");
			  $tablaHTML[$pos]['estilo']='I';
			  $tablaHTML[$pos]['borde'] = '1';
			  $tablaHTML[$pos]['size'] = 5;
			  $pos = $pos+1;
			}
			
		}
		$pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,"","",$sizetable,$mostrar,15,'L',$download = true, $repetirCabecera=null, $mostrar_cero=1,$nuevaPagina=1);

	}

	function imprimir_excel($orden)
	{

		$ali =array("L","L");
		$medi =array(10,40,130,10);
		$mostrar = true;
		$sizetable =7;
		$tablaHTML = array();
		$titulo = "O R D E N  D E   C O M P R A ";
		$datos_pedido = $this->modelo->pedidos_compra_x_proveedor($orden);
		$pos = 0;


		foreach ($datos_pedido as $key => $value) {

		$tablaHTML[$pos+0]['medidas']=$medi;
		$tablaHTML[$pos+0]['datos']= array('Numero:','',$value['Orden_No'],'');
		$tablaHTML[$pos+0]['tipo']= 'B';	
		$tablaHTML[$pos+0]['unir']= array('AB','CDEFGHI');
		$tablaHTML[$pos+0]['col-total'] = 16;

		$tablaHTML[$pos+1]['medidas']=$medi;
		$tablaHTML[$pos+1]['datos']= array('Contratista:','',$value['Cliente'],'');
		$tablaHTML[$pos+1]['tipo']= 'B';	
		$tablaHTML[$pos+1]['unir']= array('AB','CDEFGHI');

		$tablaHTML[$pos+2]['medidas']=$medi;
		$tablaHTML[$pos+2]['datos']= array('Precio Referencial Total:','',$value['Total'],'');
		$tablaHTML[$pos+2]['tipo']= 'B';	
		$tablaHTML[$pos+2]['unir']= array('AB','CDEFGHI');

		$tablaHTML[$pos+3]['medidas']=$medi;
		$tablaHTML[$pos+3]['datos']= array('Proveedor:','',$value['proveedor'],'');
		$tablaHTML[$pos+3]['tipo']= 'B';	
		$tablaHTML[$pos+3]['unir']= array('AB','CDEFGHI');		


		$datos_lineas = $this->modelo->lineas_compras_solicitados($orden,false,$value['CodigoC']);
		$tablaHTML[$pos+4]['medidas']=array(8,40,20,80,25,10,15,10,15,15,20,15,15,15,15,15);
		$tablaHTML[$pos+4]['datos']=array('ITEM','FAMILIA','CODIGO','ITEM','MARCAS','CANT','T0%','T5%','T15%','IVA 5%','IVA 15%','TOTAL','TOTAL GLOBAL','PRECIO REFERENCIAL','DIFERENCIA');
		$tablaHTML[$pos+4]['tipo']= 'SUB';	

		$pos_head = $pos+4;
		// print_r($datos_lineas);die();

		$pos = $pos+5;
			foreach ($datos_lineas as $key => $value) {

				// print_r($value);die();

				$codigo = $value['Codigo_Inv'];
				$resp = true;
				while ($resp) {
					$posicion = strrpos($codigo, '.');
					// Verificar que el punto fue encontrado
					if ($posicion !== false) {
					    $códigoModificado = substr($codigo, 0, $posicion);
					    $fami = $this->modelo->buscar_familia($códigoModificado);
					    if(count($fami)==0){$codigo = $códigoModificado; }else{$resp = false;}
					}
				}
				
				// print_r($fami);die();
			  $tablaHTML[$pos]['medidas']=$tablaHTML[$pos_head]['medidas'];
			  $tablaHTML[$pos]['datos']=array($key+1,$fami[0]['Producto'],$value['Codigo_Inv'],$value['Producto'],$value['Marca'],$value['Cantidad'],'','','','','',$value['Precio'],'',number_format($value['Total'],2,'.',''),'','');
			  $pos = $pos+1;
			}

			$tablaHTML[$pos]['medidas']=$medi;
			$tablaHTML[$pos]['datos']= array('');
			$tablaHTML[$pos]['tipo']= 'B';	
			$tablaHTML[$pos]['unir']= array('ABCDEFGHI');
			$pos = $pos+1;


		}

	    excel_generico($titulo,$tablaHTML);

	}

	function grabar_kardex($parametros)
	{
		
		$cta = LeerCta('2.1.05.01.12');
		$msj = '';
		if(count($cta)==0)
		{
			SetAdoAddNew("Catalogo_Cuentas");
		    SetAdoFields("TC","P");
		    SetAdoFields("Cta","2.1.05.01.12");
		    SetAdoFields("Cuenta","PROVISION COMPRAS INVENTARIO");
		    SetAdoFields("Item",$_SESSION['INGRESO']['item']);
		    SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
			SetAdoUpdate();
			$cta[0]['Codigo'] = "2.1.05.01.12";
			$cta[0]['Cuenta'] = "PROVISION COMPRAS INVENTARIO";
			$cta[0]['SubCta'] = "P";
		}

		$orden = $parametros['orden'];
		$provedor = $this->modelo->lineas_compras_solicitados_proveedores($parametros['orden']);
		foreach ($provedor as $key => $value) 
		{
			$nombre = $value['Cliente'];
			$ruc = $value['CodigoC'];
			$parametros_debe = array();
			$parametros_haber = array();

			//ingreso en trans kardex
					$sub = $this->modelo->catalogo_CxCxP($value['CodigoC']);
					// print_r($sub);die();	 // print_r($cta);die();
					$parametros_SC = array(
		                    'be'=>$cta[0]['Cuenta'],
		                    'ru'=> '',
		                    'co'=> $cta[0]['Codigo'],// codigo de cuenta cc
		                    'tip'=>$cta[0]['SubCta'],//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
		                    'tic'=> 1, //debito o credito (1 o 2);
		                    'sub'=> $value['CodigoC'], //Codigo se trae catalogo subcuenta
		                    'sub2'=>$cta[0]['Cuenta'],//nombre del beneficiario
		                    'fecha_sc'=> date('Y-m-d'), //fecha 
		                    'fac2'=>0,
		                    'mes'=> 0,
		                    'valorn'=> round($value['Total'],2),//valor de sub cuenta 
		                    'moneda'=> 1, /// moneda 1
		                    'Trans'=>$value['Cliente'],//detalle que se trae del asiento
		                    'T_N'=> '99',
		                    't'=> $sub[0]['TC'],                        
		                  );
						// print_r($parametros);die();
		             $this->modelo->generar_asientos_SC($parametros_SC);



		   	//ingreso asiento debe
					$asiento_debe = $this->modelo->datos_asiento_debe_trans($parametros['orden'],$value['CodigoC']);
					// print_r($asiento_debe);die();
					$fecha = $asiento_debe[0]['fecha']->format('Y-m-d');		
					foreach ($asiento_debe as $key2 => $value2) 
					{
						// print_r($value);die();
						$cuenta = $this->modelo->catalogo_cuentas($cta[0]['Codigo']);		
							$parametros_debe = array(
							  "va" =>round($value2['total'],2),//valor que se trae del otal sumado
			                  "dconcepto1" =>$cuenta[0]['Cuenta'],
			                  "codigo" => $cuenta[0]['Codigo'], // cuenta de codigo de 
			                  "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
			                  "efectivo_as" =>$value2['fecha']->format('Y-m-d'), // observacion si TC de catalogo de cuenta
			                  "chq_as" => 0,
			                  "moneda" => 1,
			                  "tipo_cue" => 1,
			                  "cotizacion" => 0,
			                  "con" => 0,// depende de moneda
			                  "t_no" => '99',
						);
						 ingresar_asientos($parametros_debe);
					}

	        // asiento para el haber
					$asiento_haber  = $this->modelo->datos_asiento_haber_trans($parametros['orden'],$value['CodigoC']);
					// print_r($asiento_haber);die();
					foreach ($asiento_haber as $key2 => $value2) {
						$inv = $this->modelo->catalogo_cuentas_cta_inv($value2['cuenta']);	
						$cuenta = $this->modelo->catalogo_cuentas($inv[0]['Cta_Inventario']);		
						// print_r($cuenta);die();	
							$parametros_haber = array(
			                  "va" =>round($value2['total'],2),//valor que se trae del otal sumado
			                  "dconcepto1" =>$cuenta[0]['Cuenta'],
			                  "codigo" => $inv[0]['Cta_Inventario'], // cuenta de codigo de 
			                  "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
			                  "efectivo_as" =>$value2['fecha']->format('Y-m-d'), // observacion si TC de catalogo de cuenta
			                  "chq_as" => 0,
			                  "moneda" => 1,
			                  "tipo_cue" => 2,
			                  "cotizacion" => 0,
			                  "con" => 0,// depende de moneda
			                  "t_no" => '99',
			                );

			                // print_r($parametros_haber);die();
			             ingresar_asientos($parametros_haber);
					}

				// Ingreso de comprobante
					// print_r($fecha);die();
						$num_comprobante = numero_comprobante1('Diario',true,true,$fecha);
						// print_r($num_comprobante);die();
					    $dat_comprobantes = $this->modelo->datos_comprobante();
					    $debe = 0;
						$haber = 0;
						foreach ($dat_comprobantes as $key => $value3) {
							$debe+=$value3['DEBE'];
							$haber+=$value3['HABER'];
						}
						// print_r($debe.'-'.$haber);die();
						if(strval($debe)==strval($haber))
						{
							if($debe !=0 && $haber!=0)
							{
								 $parametro_comprobante = array(
				        	        'ru'=> $ruc, //codigo del cliente que sale co el ruc del beneficiario codigo
				        	        'tip'=>'CD',//tipo de cuenta contable cd, etc
				        	        "fecha1"=> $fecha,// fecha actual 2020-09-21
				        	        'concepto'=>'Entrada de inventario  '.$nombre.' con CI: '.$ruc.' el dia '.$fecha, //detalle de la transaccion realida
				        	        'totalh'=> round($haber,2), //total del haber
				        	        'num_com'=> '.'.date('Y', strtotime($fecha)).'-'.$num_comprobante, // codigo de comprobante de esta forma 2019-9000002
				        	        );
								 // print_r($parametro_comprobante);die();
				               	$resp = $this->ingDescargos->generar_comprobantes($parametro_comprobante);
				                // $cod = explode('-',$num_comprobante);
				                // die();
				                if($resp==$num_comprobante)
                				{
				                	if($this->ingresar_trans_kardex_entrada($orden,$num_comprobante,$fecha,$ruc,$nombre)==1)
				                	{
				                		// hasta aqui 
				                		// print_r('ingreso kardex'.$num_comprobante);die();
				                		$resp = $this->modelo->eliminar_asiento_K($parametros['orden'],$value['CodigoC']);
				                		if($resp==1)
				                		{
				                			$this->modelo->eliminar_asiento();
				                			// $this->ingDescargos->eliminar_aiseto_sc($orden);                			
				                			mayorizar_inventario_sp();
				                			// return array('resp'=>1,'com'=>$num_comprobante);
				                			$msj.= 'comprobante '.$num_comprobante.' Generado <br>';
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

				                			$this->modelo->eliminar_asiento();
							     // $this->modelo->eliminar_aiseto_sc($orden);
				        	        return array('resp'=>-1,'com'=>$resp);
				                }
							}else
							{
				                			$this->modelo->eliminar_asiento();
								// $this->modelo->eliminar_aiseto_sc($fecha);
								return array('resp'=>-1,'com'=>'No coinciden','debe'=>$debe,'haber'=>$haber);
							}
						}else
						{
								// print_r($debe."-".$haber); 
								 return array('resp'=>-1,'com'=>'Los resultados son 0');
						}
						
					// print_r($value);die();
				}

				return array('resp'=>1,'com'=>$msj);

	}

	function ingresar_trans_kardex_entrada($orden,$comprobante,$fechaC,$CodigoPrv,$nombre)
    {
		$datos_K = $this->modelo->lineas_compras_solicitados($orden,false,$CodigoPrv);
		$cta = LeerCta('2.1.05.01.12');
		// $comprobante = explode('.',$comprobante);
		// $comprobante = explode('-',trim($comprobante[1]));
		$comprobante = $comprobante;
		$resp = 1;
		foreach ($datos_K as $key => $value) {
			   $datos_inv = Leer_Codigo_Inv($value['Codigo_Inv'],$fechaC);
			   $stock = 0;
			   $Cta_Inventario = '.';

			   if($datos_inv['respueta']==1)
			   {
			   	// print_r('expression');
			   		$stock = $datos_inv['datos']['Stock'];
			   		$Cta_Inventario = $datos_inv['datos']['Cta_Inventario'];
			   }
			   	// print_r($Cta_Inventario);die();
			    SetAdoAddNew("Trans_Kardex"); 		
			    SetAdoFields('Codigo_Inv',$value['Codigo_Inv']); 
			    SetAdoFields('Fecha',$fechaC); 
			    SetAdoFields('Numero',$comprobante);  
			    SetAdoFields('T','N'); 
			    SetAdoFields('TP','CD'); 
			    SetAdoFields('Codigo_P',$_SESSION['INGRESO']['CodigoU']); 
			    SetAdoFields('Cta_Inv',$Cta_Inventario); 
			    SetAdoFields('Contra_Cta',$cta[0]['Codigo']); 
			    SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']); 
			    SetAdoFields('Entrada',$value['Cantidad']); 
			    SetAdoFields('Valor_Unitario',number_format($value['Precio'],$_SESSION['INGRESO']['Dec_PVP'],'.','')); 
			    SetAdoFields('Valor_Total',number_format($value['Total'],2)); 
			    SetAdoFields('Costo',number_format($value['Precio'],2)); 
			    SetAdoFields('Total',number_format($value['Total'],2));
			    if($stock>0)
			    {
			    	SetAdoFields('Existencia',number_format($stock,2)+floatval($value['Cantidad']));
			    	// print_r($cant[2]);
			    }else
			    {
			    	SetAdoFields('Existencia',floatval($value['Cantidad']));
			    }
			    SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);
			    SetAdoFields('Item',$_SESSION['INGRESO']['item']);
			    SetAdoFields('CodBodega','01');
			    // SetAdoFields('CodigoL',$value['SUBCTA']);
			    SetAdoFields('Detalle','Entrada de inventario por '.$nombre.' de la factura '.$orden.' el dia '.$fechaC);
			    SetAdoFields('Fecha_Exp',$value['Fecha_Ent']->format('Y-m-d'));
			    SetAdoFields('Fecha_Fab',$value['Fecha_Ent']->format('Y-m-d'));


			     if(SetAdoUpdate()!=1)
			     {
			     	$resp = 0;
			     } 
		}
		return $resp;

	}
}

?>