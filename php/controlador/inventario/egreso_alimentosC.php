<?php
//Llamada al modelo
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("../../modelo/inventario/egreso_alimentosM.php");
require_once(dirname(__DIR__,2).'/modelo/farmacia/ingreso_descargosM.php');
require_once(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');

$controlador = new egreso_alimentosC();
 
if(isset($_GET['areas']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->ddl_areas($query));
} 
if(isset($_GET['areas_checking']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->areas_checking($query));
} 
if(isset($_GET['motivos']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->ddl_motivo($query));
}
if(isset($_GET['buscar_producto']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->buscar_producto($parametros));
}
if(isset($_GET['add_egresos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->add_egresos($parametros));
}
if(isset($_GET['listar_egresos']))
{
	echo json_encode($controlador->listar_egresos());
}
if(isset($_GET['lista_egreso_checking']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_egreso_checking($parametros));
}
if(isset($_GET['lista_egreso_checking_reportados']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_egreso_checking_reportados($parametros));
}
if(isset($_GET['eliminar_egreso']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->eliminar_egreso($id));
}
if(isset($_GET['eliminar_egreso_all']))
{
	echo json_encode($controlador->eliminar_egreso_all());
}
if(isset($_GET['guardar_egreso']))
{
	echo json_encode($controlador->guardar_egreso($_FILES,$_POST));
}
if(isset($_GET['generar_comprobante']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->generar_comprobante($parametros));
}
if(isset($_GET['cargar_motivo_lista']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_motivo_lista($parametros));
}
if(isset($_GET['cambiar_estado']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cambiar_estado($parametros));
}
if(isset($_GET['catalog_cuentas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->catalog_cuentas($parametros));
}
if(isset($_GET['cambiar_a_reportado']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cambiar_a_reportado($parametros));
}
if(isset($_GET['guardar_linea']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_linea($parametros));
}

/**
 * 
 */
class egreso_alimentosC
{
	private $modelo;
	private $pdf;
	private $ing_des;

	function __construct()
	{
		$this->modelo = new egreso_alimentosM();
		$this->pdf = new cabecera_pdf();
		$this->ing_des = new ingreso_descargosM();

	}

	function ddl_areas($query)
	{
		$datos = $this->modelo->areas($query);
		$op=array();
		foreach ($datos as $key => $value) {
			$op[] = array('id'=>$value['Cmds'],'text'=>trim($value['Proceso']),'data'=>$value);			
		}

		return $op;

	}

	function areas_checking($query)
	{
		$datos = $this->modelo->areas_checking($query);
		$op=array();
		foreach ($datos as $key => $value) {
			$op[] = array('id'=>$value['Cmds'],'text'=>trim($value['Proceso']),'data'=>$value);			
		}

		return $op;

	}

	function ddl_motivo($query)
	{
		$datos = $this->modelo->motivo_egreso($query);
		$op=array();
		foreach ($datos as $key => $value) {
			$op[] = array('id'=>$value['Cmds'],'text'=>$value['Proceso'],'data'=>$value);			
		}
		return $op;
	}

	function buscar_producto($parametros)
	{
		$datos = $this->modelo->buscar_producto($parametros['codigo']);
		return $datos;
	}

	function add_egresos($parametros)
	{
		$producto = $this->modelo->buscar_producto(false,$parametros['id']);
		$data = $producto[0];

		SetAdoAddNew('Trans_Kardex');
	    SetAdoFields('T','S');
	    SetAdoFields('Salida',$parametros['cantidad']);
	    SetAdoFields('CodBodega',$data['CodBodega']);
	    SetAdoFields('Codigo_Barra',$data['Codigo_Barra']);
	    SetAdoFields('Cta_Inv',$data['Cta_Inventario']);	
	    SetAdoFields('Codigo_Inv',$data['Codigo_Inv']);	
	    SetAdoFields('Fecha',$parametros['fecha']);	
	    SetAdoFields('Codigo_P',$data['Codigo_P']);	
	    SetAdoFields('CodigoU',$data['CodigoU']);	
	    SetAdoFields('Valor_Unitario',number_format($data['Valor_Unitario'],2,'.',''));	

	    SetAdoFields('Codigo_Tra',$parametros['area']);	
	    SetAdoFields('Modelo',$parametros['motivo']);	
	    SetAdoFields('Detalle',$parametros['detalle']);	

	    SetAdoFields('Valor_Total',number_format(floatval($data['Valor_Unitario'])*floatval($parametros['cantidad']),2,'.',''));	
	     SetAdoFields('Total',number_format(floatval($data['Valor_Unitario'])*floatval($parametros['cantidad']),2,'.',''));	
	    SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);	
	    SetAdoFields('Item',$_SESSION['INGRESO']['item']);			
	    SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);		
	   return  SetAdoUpdate();		

	}	

	function listar_egresos()
	{
		$tr = '';
		$datos = $this->modelo->buscar_producto_egreso();
		/*foreach ($datos as $key => $value) {
			$tr.='<tr>			
			<td>'.($key+1).'</td>
			<td>'.$value['Fecha']->format('Y-m-d').'</td>
			<td>'.$value['Producto'].'</td>
			<td>'.$value['Salida'].' '.$value['Unidad'].'</td>
			<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminar_egreso('.$value['ID'].')"><i class="fa fa-trash"></i></button></td>
			</tr>';
		}*/
		return $datos;
	}

	function eliminar_egreso($id)
	{
		return $this->modelo->eliminar($id);
		// print_r($id);die();
	}
	function eliminar_egreso_all()
	{
		return $this->modelo->eliminar_all();
		// print_r($id);die();
	}

	function guardar_egreso($file,$post)
	{
		
		if(isset($file['archivo']) && $this->validar_formato_img($file)!=1)
		{
			return -2;
		}

		$datos  = $this->modelo->buscar_producto_egreso();
		if(count($datos)==0)
		{
			return -3;
		}
		
		// para el cheing de egreso se colocara la G
		$dia = date('Ymd');
		$numero_secuencial = numero_comprobante1("Egreso_".$dia,$_SESSION['INGRESO']['item'],1,date('Y-m-d'));
		$registro = generaCeros(intval($numero_secuencial),3);
		$orden = str_replace('-','', date('Y-m-d')).'-'.$registro;

		// print_r($orden);die();

		$ruta = dirname(__DIR__,2).'/comprobantes/sustentos/entidad_'.$_SESSION['INGRESO']['Entidad_No'].'/empresa_'.$_SESSION['INGRESO']['item'].'/';
		if(!file_exists($ruta))
		{
			$ruta1 = dirname(__DIR__,2).'/comprobantes/sustentos/';
			if(!file_exists($ruta1))
			{
				mkdir($ruta1,0777);
			}
			$ruta1 = dirname(__DIR__,2).'/comprobantes/sustentos/entidad_'.$_SESSION['INGRESO']['Entidad_No'].'/';
			if(!file_exists($ruta1))
			{
				mkdir($ruta1,0777);
			}
			$ruta1 = dirname(__DIR__,2).'/comprobantes/sustentos/entidad_'.$_SESSION['INGRESO']['Entidad_No'].'/empresa_'.$_SESSION['INGRESO']['item'].'/';
			if(!file_exists($ruta1))
			{
				mkdir($ruta1,0777);
			}
		}

		$nombre = "";
		$nuevo_nom = "";
		if(isset($post['foto'])){
			// Extraer la imagen Base64
			$base64Image = $post["foto"];

			// Decodificar la imagen (eliminar el encabezado "data:image/png;base64,")
			$image_parts = explode(";base64,", $base64Image);
			$image_type = explode("image/", $image_parts[0])[1]; // Obtener el tipo (png, jpg, etc.)
			$image_base64 = base64_decode($image_parts[1]);
		
			// Crear un nombre único para la imagen
			$nombre = $orden . '.' . $image_type;
		
			// Especificar la carpeta donde se guardará
			$nuevo_nom = $ruta . $nombre;
		
			file_put_contents($nuevo_nom, $image_base64);

		}else if(isset($file['archivo'])){
			$uploadfile_temporal=$file['archivo']['tmp_name'];
			$tipo = explode('/', $file['archivo']['type']);
			$nombre = str_replace('-','_',$orden).'.'.$tipo[1];
		   
			$nuevo_nom=$ruta.$nombre;
			if (is_uploaded_file($uploadfile_temporal))
			{
				move_uploaded_file($uploadfile_temporal,$nuevo_nom);
			}
		}

		SetAdoAddNew('Trans_Kardex');
	    SetAdoFields('T','G');	    	    	    
	    SetAdoFields('Orden_No',$orden);	 	    
	    SetAdoFields('Procedencia',$nombre);	

	    SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']);	
	    SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);	
	    SetAdoFieldsWhere('CodigoU',$_SESSION['INGRESO']['CodigoU']);	
	    SetAdoFieldsWhere('T','S');				
	    SetAdoFieldsWhere('T','S');			

	   return  SetAdoUpdateGeneric();
	}

	 function validar_formato_img($file)
	  {

	  	// print_r($file);die();
	    switch ($file['archivo']['type']) {
	      case 'image/jpeg':
	      case 'image/pjpeg':
	      case 'image/gif':
	      case 'image/png':
	      case 'image/jpg':
	         return 1;
	        break;      
	      default:
	        return -1;
	        break;
	    }

	  }


	function lista_egreso_checking($parametros)
	{
		$area = false;
		if(isset($parametros['areas']))
		{
			$area = $parametros['areas'];
		}
		// print_r($parametros);die();
		$tr = '';
		$datos = $this->modelo->lista_egreso_checking(false,false,$area);
		// print_r($datos);die();
		foreach ($datos as $key => $value) {
			$datos[$key]['listo'] = 1;
			$op='<select class="form-select form-select-sm w-100" id="ddl_subcta_'.$value['Orden_No'].'" name="ddl_subcta_'.$value['Orden_No'].'">
							<option value="">Seleccione modulo</option>
							'.$this->catalog_cuentas($value['motivoid']).'
						</select>
					</td>
				</tr>';
				$datos[$key]['SubModulo'] = $op ;
			$lineas = $this->modelo->cargar_motivo_lista(false,false,$value['Orden_No']);
			// print_r($lineas);
			foreach ($lineas as $key2 => $value2) {
				if($value2['Solicitud']=="0")
				{
					$datos[$key]['listo'] = "0";
					break;
				}
			}
		}

		// print_r($datos);die();

		return $datos;
	}

	function lista_egreso_checking_reportados($parametros)
	{
		$area = false;
		if(isset($parametros['areas']))
		{
			$area = $parametros['areas'];
		}
		// print_r($parametros);die();
		$tr = '';
		$datos = $this->modelo->lista_egreso_checking_devuelto(false,false,$area);
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>
						<div class="input-group input-group-sm">
							'.$value['usuario'].'							
						</div>
					</td>
					<td>
						<div class="input-group input-group-sm">
							'.$value['area'].'							
						</div>
					</td>					
					<td>'.$value['Detalle'].'</td>
					<td>
						<div class="input-group input-group-sm">
							'.$value['Motivo'].'							
						</div>
					</td>
					
					<td>
						
					</td>
					<td>
					<button class="btn btn-primary"><i class="fa fa-save"></i></button>
					<button class="btn btn-primary"><i class="fa fa-trash"></i></button>
					<button class="btn btn-primary"><i class="fa fa-pen"></i></button>
					</td>
				</tr>';
			// $tr.='<tr>			
			
			// <td>'.$value['Producto'].'</td>
			// <td>'.$value['Salida'].'</td>
			// <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminar_egreso('.$value['ID'].')"><i class="fa fa-trash"></i></button></td>
			// </tr>';
		}
		return $datos;
	}


	function cargar_motivo_lista($parametros)
	{
		$tr='';
		$datos = $this->modelo->cargar_motivo_lista(false,false,$parametros['orden']);
		foreach ($datos as $key => $value) {
			// print_r($value);die();
			$stock = 0;
			$datos_stock = Leer_Codigo_Inv($value['Codigo_Inv'],date('Y-m-d'));
			$datos[$key]['Stock'] = 0;
			if($datos_stock['respueta']==1)
			{
				$datos[$key]['Stock'] = $datos_stock['datos']['Stock'];
			}
		}

		return $datos;
		// print_r($parametros);
	}

	function cambiar_estado($parametros)
	{
		SetAdoAddNew("Trans_Kardex"); 		
	   	SetAdoFields('Solicitud',$parametros['estado']);
	   	SetAdoFieldsWhere('ID',$parametros['id']);
	  	return  SetAdoUpdateGeneric();

		// print_r($parametros);die();
	}

	function catalog_cuentas($motivo)
	{
		$tr='';				
		$motivo = $this->modelo->catalogo_procesos(false,$motivo);
		if(count($motivo)>0)
		{
			$cuenta = LeerCta($motivo[0]['Cta_Debe']);
			if(count($cuenta)>0)
			{
				// print_r($cuenta);die();
				if($cuenta[0]['SubCta']=='P' || $cuenta[0]['SubCta']=='C')
				{
					$datos = $this->modelo->Catalogo_CxCxP($cuenta[0]['Codigo']);
				}else				{

					$datos = $this->modelo->Catalogo_SubCtas($cuenta[0]['SubCta']);
				}
				foreach ($datos as $key => $value) {
					$tr.='<option value="'.$value['Cta'].'-'.$value['Codigo'].'">'.$value['Detalle'].'</option>';
				}
			}
		}

		return $tr;
	}

	function cambiar_a_reportado($parametros)
	{
	   SetAdoAddNew("Trans_Kardex"); 		
	   SetAdoFields('T','R');
	   SetAdoFieldsWhere('Orden_No',$parametros['orden']);
	  return  SetAdoUpdateGeneric();

	}

	function generar_comprobante($parametros)
	{
		// print_r($parametros);die();

		$datasubCuenta = explode('-',$parametros['submodulo']);
		$cta_inv = $datasubCuenta[0];
		$CodigoL = $datasubCuenta[1];
		$ruc = $CodigoL;
		$tipo = '';
		$orden = $this->modelo->lista_egreso_checking(false,false,false,$parametros['orden']);

		$detalle = "Egreso ".$orden[0]['area'].' - '.$orden[0]['Motivo'].' - '.$orden[0]['Detalle'];
		$detalle_sc = $orden[0]['Detalle'];

		$fecha = $orden[0]['Fecha']->format('Y-m-d');
		if(count($orden)==0){ return -1; }
		$motivo = $this->modelo->catalogo_procesos(false,$orden[0]['motivoid']);
		$cuenta = LeerCta($motivo[0]['Cta_Debe']);
		if(count($cuenta)>0)
		{
			$tipo = $cuenta[0]['SubCta'];
			// print_r($cuenta);die();
		}

		SetAdoAddNew("Trans_Kardex"); 		
	   	SetAdoFields('Contra_Cta',$motivo[0]['Cta_Debe']);
	   	SetAdoFields('CodigoL',$CodigoL);
	   	SetAdoFieldsWhere('Orden_No',$parametros['orden']);
	   	SetAdoFieldsWhere('T','G');
	  	SetAdoUpdateGeneric();



		// print_r($asientos_SC); die();
		$parametros_debe = array();
		$parametros_haber = array();
		// $fecha = date('Y-m-d');

		// print_r($asientos_SC);die();
		// print_r($tipo);die();

		$asientos_SC = $this->modelo->datos_asiento_SC_trans($parametros['orden']);

		foreach ($asientos_SC as $key => $value) {
			 $cuenta = $this->modelo->catalogo_cuentas($value['CONTRA_CTA']);
			 // print_r($tipo);die();
			 if($tipo=='C' || $tipo=='P')
			 {
			 	$sub = $this->modelo->Catalogo_CxCxP($value['CONTRA_CTA'],$value['CodigoL']);
			 }else
			 {			 	
			 	$sub = $this->modelo->Catalogo_SubCtas($tipo,$value['CodigoL']);
			 }

			 // print_r($sub);die();
			$dataSub = array(
                    'be'=>$cuenta[0]['Cuenta'],
                    'ru'=> '',
                    'co'=> $value['CONTRA_CTA'],// codigo de cuenta cc
                    'tip'=>$cuenta[0]['TC'],//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
                    'tic'=> 1, //debito o credito (1 o 2);
                    'sub'=> $value['CodigoL'], //Codigo se trae catalogo subcuenta
                    'sub2'=>$cuenta[0]['Cuenta'],//nombre del beneficiario
                    'fecha_sc'=> $value['Fecha_Fab']->format('Y-m-d'), //fecha 
                    'fac2'=>0,
                    'mes'=> 0,
                    'valorn'=> round($value['total'],2),//valor de sub cuenta 
                    'moneda'=> 1, /// moneda 1
                    'Trans'=>$detalle_sc,//detalle que se trae del asiento
                    'T_N'=> '99',
                    't'=> $sub[0]['TC'],                        
                  );
                  $this->ing_des->generar_asientos_SC($dataSub);
		}

		// print_r('expression');die();

		//asientos para el debe
		$asiento_debe = $this->ing_des->datos_asiento_debe_trans($parametros['orden'],$fecha);
		// print_r($asiento_debe);		
		foreach ($asiento_debe as $key => $value) 
		{
			// print_r($value);die();
			$cuenta = $this->modelo->catalogo_cuentas($value['cuenta']);		
				$parametros_debe = array(
				 "va" =>round($value['total'],2),//valor que se trae del otal sumado
                  "dconcepto1" =>$detalle_sc,
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
				 $this->ing_des->ingresar_asientos($parametros_debe);
		}
		// print_r('expresion');die();

        // asiento para el haber
		$asiento_haber  = $this->ing_des->datos_asiento_haber_trans($parametros['orden'],$fecha);
		// print_r($asiento_haber);die();

		foreach ($asiento_haber as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($value['cuenta']);		
			// print_r($cuenta);die();	
				$parametros_haber = array(
                  "va" =>round($value['total'],2),//valor que se trae del otal sumado
                  "dconcepto1" =>$detalle_sc,
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
             $re =   $this->ing_des->ingresar_asientos($parametros_haber);
             // print_r($re);
		}


		// print_r('expression');die();

		// print_r($fecha.'-'.$nombre.'-'.$ruc);die();
		// $parametros = array('tip'=> 'CD','fecha'=>$fecha);
	 //    $num_comprobante = $this->modelo->numero_comprobante($parametros);

	    $num_comprobante = numero_comprobante1('Diario',true,true,$fecha);
	    $dat_comprobantes = $this->ing_des->datos_comprobante();
	    $debe = 0;
		$haber = 0;
		foreach ($dat_comprobantes as $key => $value) {
			$debe+=$value['DEBE'];
			$haber+=$value['HABER'];
		}
		// print_r($debe)
		if(strval($debe)==strval($haber))
		{
			if($debe !=0 && $haber!=0)
			{
				 $parametro_comprobante = array(
        	        'ru'=> '000000000', //codigo del cliente que sale co el ruc del beneficiario codigo
        	        'tip'=>'CD',//tipo de cuenta contable cd, etc
        	        "fecha1"=> $fecha,// fecha actual 2020-09-21
        	        'concepto'=>$detalle, //detalle de la transaccion realida
        	        'totalh'=> round($haber,2), //total del haber
        	        'num_com'=> '.'.date('Y', strtotime($fecha)).'-'.$num_comprobante, // codigo de comprobante de esta forma 2019-9000002
        	        );
				 // print_r($nombre);print_r($ruc);print_r($fecha);
				 // print_r($parametro_comprobante);die();
                $resp = $this->ing_des->generar_comprobantes($parametro_comprobante);
                // $cod = explode('-',$num_comprobante);
                // die();
                if($resp==$num_comprobante)
                {
                	if($this->ingresar_trans_kardex_salidas($parametros['orden'],$num_comprobante)==1)
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
			$this->modelo->eliminar_asiento('99');
			$this->modelo->eliminar_asieto_sc($CodigoL,'99');
			return array('resp'=>-1,'com'=>'No coinciden','debe'=>$debe,'haber'=>$haber);

		}


	}
	function ingresar_trans_kardex_salidas($orden,$comprobante,$nombre='')
    {
		$datos_K = $this->modelo->cargar_motivo_lista(false,false,$orden);
		// print_r($datos_K);die();
		// $comprobante = explode('.',$comprobante);
		// $comprobante = explode('-',trim($comprobante[1]));
		$comprobante = $comprobante;
		$resp = 1;
		$lista = '';
		foreach ($datos_K as $key => $value) {

			// print_r($value);die();
			SetAdoAddNew("Trans_Kardex"); 		
		   	SetAdoFields('T','N');
		   	SetAdoFields('Numero',$comprobante);
		   	// SetAdoFields('Detalle','Salida inventario prueba');
		   	SetAdoFields('TP','CD');
		   	SetAdoFieldsWhere('ID',$value['ID']);
		  	SetAdoUpdateGeneric();
		}
		                		// print_r($resp);die();
		return $resp;

	}

	function guardar_linea($parametros)
	{
		$lineas = $this->modelo->cargar_motivo_lista(false,$parametros['id'],false);
		$total = number_format($lineas[0]['Salida']*$parametros['precio'],2,'.','');
		SetAdoAddNew("Trans_Kardex"); 		
	   	SetAdoFields('Valor_Total',$total);
	   	SetAdoFields('Valor_Unitario',number_format($parametros['precio'],2,'.',''));
	   	SetAdoFields('Total',$total);
	   	SetAdoFieldsWhere('ID',$parametros['id']);
	  	return SetAdoUpdateGeneric();

	}


}


?>