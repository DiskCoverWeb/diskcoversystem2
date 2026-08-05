<?php
include(dirname(__DIR__,2).'/modelo/inventario/reubicarM.php');
include(dirname(__DIR__,2).'/modelo/farmacia/ingreso_descargosM.php');

$controlador = new reubicarC();

if (isset($_GET['lista_stock_ubicado'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->lista_stock_ubicado($parametros));
}
if (isset($_GET['cambiar_bodega'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cambiar_bodega($parametros));
}
if (isset($_GET['numero_comprobante'])) {
    $parametros = $_POST['parametros'];
    $fecha = date('Y-m-d');
    If($parametros['tip']=='CD'){ $NumComp = ReadSetDataNum("Diario", True, false,$fecha);}
    If($parametros['tip']=='CI'){ $NumComp = ReadSetDataNum("Ingresos", True, false,$fecha);}
    If($parametros['tip']=='CE'){ $NumComp = ReadSetDataNum("Egresos", True, false,$fecha);}
    If($parametros['tip']=='ND'){ $NumComp = ReadSetDataNum("NotaDebito", True, false,$fecha);}
    If($parametros['tip']=='NC'){ $NumComp = ReadSetDataNum("NotaCredito", True, false,$fecha);}
     
    echo json_encode($NumComp);
}


class reubicarC
{
    private $modelo;
    private $rutas;
    private $ing_des;

    function __construct()
    {
        $this->modelo = new reubicarM();
        $this->rutas = array();
        $this->ing_des = new ingreso_descargosM();
    }

    function lista_stock_ubicado($parametros)
    {
    	$bodega = $parametros['bodegas'];
    	$cod_art = $parametros['cod_articulo'];
    	$datos = $this->modelo->lista_stock_ubicado($bodega,$cod_art);
    	$tr = '';

    	
    	foreach ($datos as $key => $value) {

    		// // busca en el listado de rutas
    		// $rutas_txt = '';
    		// foreach ($this->rutas as $key => $item) {
		   	//  	if ($item["codigoBod"]== $value['CodBodega']) {
		    //     	$rutas_txt = $item["ruta"];
		    //     	// print_r($rutas_txt);die();
		    //     	break;
		    // 	}
			// }
			// if($rutas_txt=='')
			// {
				$rutas_txt = $this->ruta_bodega($value['CodBodega']);
			// }

    		$stock = 0;
    		$datos_inv = Leer_Codigo_Inv($value['Codigo_Inv'],date('Y-m-d'));
    		$datos[$key]['Stock'] = 0;
    		if($datos_inv['respueta']==1)
    		{
    			$stock = $datos_inv['datos']['Stock'].' '.$datos_inv['datos']['Unidad'];
    			$datos[$key]['Stock'] = $stock;
    		}
    		$datos[$key]['Ruta'] = $rutas_txt;
    	}
    	// print_r($this->rutas);die();
    	return $datos;
    	// print_r($datos);die();
    }

    function ruta_bodega($padre)
	{
		$datos = explode('.',$padre);
		$camino = '';
		$buscar = '';
		foreach ($datos as $key => $value) {
			$camino.= $value.'.';
			$buscar.= "'".substr($camino, 0,-1)."',";
		}

		$buscar = substr($buscar, 0,-1);
		$pasos = $this->modelo->ruta_bodega_select($buscar);
		$ruta = '';
		foreach ($pasos as $key => $value) {
			$ruta.=$value['Bodega'].'/';			
		}
		$ruta = substr($ruta,0,-1);		
		array_push($this->rutas, array('codigoBod'=>$padre,'ruta'=>$ruta));
		return $ruta;
	}

	function cambiar_bodega($parametros)
	{
		// print_r($parametros);die();
		// $Codigo_barra = $this->
		$data_ubi = $this->modelo->lista_stock_ubicado(false,$parametros['codBarra']);
		$data = $this->modelo->kardexAReubicar($parametros['codBarra']);
		$inv = $this->modelo->Leer_Codigo_Inv_SP($data[0]['Codigo_Inv']);
		$data[0]['newBodega'] = $parametros['newBodega'];
		$data_inv = $inv['datos'];

		if(trim($parametros['newBodega'])== trim($data_ubi[0]['CodBodega']))
		{
			return array('resp'=>-2,'com'=>"Seleccione una bodega diferente");
		}

		// print_r($data_ubi);
		// print_r($data_inv);die();


		// die();


		// $cuenta = $this->ing_des->catalogo_cuentas($data_ubi[0]['Contra_Cta']);
		// $sub = $this->ing_des->catalogo_subcuentas($data_ubi[0]['Codigo_P']);
		// $parametros = array(
        //     'be'=>$cuenta[0]['Cuenta'],
        //     'ru'=> '',
        //     'co'=> $data_ubi[0]['Contra_Cta'],// codigo de cuenta cc
        //     'tip'=>$cuenta[0]['TC'],//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
        //     'tic'=> 1, //debito o credito (1 o 2);
        //     'sub'=> $data_ubi[0]['Codigo_P'], //Codigo se trae catalogo subcuenta
        //     'sub2'=>$cuenta[0]['Cuenta'],//nombre del beneficiario
        //     'fecha_sc'=> date('Y-m-d'), //fecha 
        //     'fac2'=>0,
        //     'mes'=> 0,
        //     'valorn'=> number_format(($data[0]['Diff']*$data_inv['Valor_Unit']),2,'.',''),//valor de sub cuenta 
        //     'moneda'=> 1, /// moneda 1
        //     'Trans'=>"",//detalle que se trae del asiento
        //     'T_N'=> '99',
        //     't'=> "",                        
        //   );
        //   $this->ing_des->generar_asientos_SC($parametros);

		// debe
		$cuenta = $this->ing_des->catalogo_cuentas($data_inv['Cta_Inventario']);
      	$parametros_debe = array(
			 "va" =>number_format(($data[0]['Diff']*$data_inv['Valor_Unit']),2,'.',''),//valor que se trae del otal sumado
              "dconcepto1" =>$cuenta[0]['Cuenta'],
              "codigo" => $data_inv['Cta_Inventario'], // cuenta de codigo de 
              "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
              "efectivo_as" =>date('Y-m-d'), // observacion si TC de catalogo de cuenta
              "chq_as" => 0,
              "moneda" => 1,
              "tipo_cue" => 1,
              "cotizacion" => 0,
              "con" => 0,// depende de moneda
              "t_no" => '99',
		);				 
		$this->ing_des->ingresar_asientos($parametros_debe);

		// haber
		$parametros_haber = array(
              "va" =>number_format(($data[0]['Diff']*$data_inv['Valor_Unit']),2,'.',''),//valor que se trae del otal sumado
              "dconcepto1" =>$cuenta[0]['Cuenta'],
              "codigo" =>$data_inv['Cta_Inventario'], // cuenta de codigo de 
              "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
              "efectivo_as" =>date('Y-m-d'), // observacion si TC de catalogo de cuenta
              "chq_as" => 0,
              "moneda" => 1,
              "tipo_cue" => 2,
              "cotizacion" => 0,
              "con" => 0,// depende de moneda
              "t_no" => '99',
            );
         $this->ing_des->ingresar_asientos($parametros_haber);


        // generado comprobate
        $num_comprobante = numero_comprobante1('Diario',true,true,date('Y-m-d'));
	    $dat_comprobantes = $this->ing_des->datos_comprobante();
	    $debe = 0;
		$haber = 0;
		foreach ($dat_comprobantes as $key => $value) {
			$debe+=$value['DEBE'];
			$haber+=$value['HABER'];
		}
		$fecha = date('Y-m-d');
		if(strval($debe)==strval($haber))
		{
			if($debe !=0 && $haber!=0)
			{
				 $parametro_comprobante = array(
        	        'ru'=> '000000000', //codigo del cliente que sale co el ruc del beneficiario codigo
        	        'tip'=>'CD',//tipo de cuenta contable cd, etc
        	        "fecha1"=> $fecha,// fecha actual 2020-09-21
        	        'concepto'=>'Reubicacion de inventario para : '.$data[0]['CodBodega'].' el dia '.$fecha, //detalle de la transaccion realida
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
                	if($this->ingresar_trans_kardex_salidas($num_comprobante,$data_ubi,$data,$fecha)==1)
                	{
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
			$this->ing_des->eliminar_asieto();
			$this->ing_des->eliminar_aiseto_sc(date('Y-m-d'));
			return array('resp'=>-1,'com'=>'No coinciden','debe'=>$debe,'haber'=>$haber);

		}
	}

	function ingresar_trans_kardex_salidas($num_comprobante,$data_ubi,$data,$fecha)
	{

		$data = $data[0];
		// Salida
	    SetAdoAddNew("Trans_Kardex"); 	
	   	SetAdoFields('T','N'); 	
	   	SetAdoFields('TP','CD'); 		
	   	SetAdoFields('Numero',$num_comprobante); 	
	   	SetAdoFields('Costo',round($data_ubi[0]['Valor_Unitario'],2)); 	
	   	SetAdoFields('Total',number_format(($data['Diff']*$data_ubi[0]['Valor_Unitario']),2,'.','')); 	
	   	SetAdoFields('Existencia',number_format($data['Diff'],2,'.','')); 
	   	SetAdoFields('CodBodega',$data['CodBodega']); 	
	   	SetAdoFields('Codigo_Barra',$data['Codigo_Barra']); 
	   	SetAdoFields('Detalle','Movimeinto de bodega '.$data['CodBodega'].' el dia '.$fecha); 	
	   	SetAdoFields('Procesado',0); 	
	   	SetAdoFields('Salida',number_format($data['Diff'],2,'.','')); 	
	   	SetAdoFields('Cta_Inv',$data_ubi[0]['Cta_Inv']); 		
	   	SetAdoFields('Contra_Cta',$data_ubi[0]['Cta_Inv']); 		
	   	SetAdoFields('Orden_No',$data_ubi[0]['Orden_No']); 		
	   	SetAdoFields('Cmds',$data_ubi[0]['Cmds']); 		
	   	SetAdoFields('Tipo_Empaque',$data_ubi[0]['Tipo_Empaque']); 		
	   	SetAdoFields('Codigo_P',$data_ubi[0]['Codigo_P']); 			   		
	   	SetAdoFields('Codigo_Inv',$data_ubi[0]['Codigo_Inv']); 		
	   	SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']); 		
	   	SetAdoFields('Item',$_SESSION['INGRESO']['item']); 				
	   	SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']); 
	   	SetAdoUpdate();


	   	// Entrada
	    SetAdoAddNew("Trans_Kardex"); 	
	   	SetAdoFields('T','N'); 	
	   	SetAdoFields('TP','CD'); 		
	   	SetAdoFields('Numero',$num_comprobante); 	
	   	SetAdoFields('Costo',round($data_ubi[0]['Valor_Unitario'],2)); 	
	   	SetAdoFields('Total',number_format(($data['Diff']*$data_ubi[0]['Valor_Unitario']),2,'.','')); 	
	   	SetAdoFields('Existencia',number_format($data['Diff'],2,'.','')); 
	   	SetAdoFields('CodBodega',$data['newBodega']); 	
	   	SetAdoFields('Codigo_Barra',$data['Codigo_Barra']); 	
	   	SetAdoFields('Detalle','Movimeinto de bodega '.$data['CodBodega'].' el dia '.$fecha); 	
	   	SetAdoFields('Procesado',0); 	
	   	SetAdoFields('Entrada',number_format($data['Diff'],2,'.','')); 	
	   	SetAdoFields('Cta_Inv',$data_ubi[0]['Cta_Inv']); 		
	   	SetAdoFields('Contra_Cta',$data_ubi[0]['Cta_Inv']); 		
	   	SetAdoFields('Orden_No',$data_ubi[0]['Orden_No']); 		
	   	SetAdoFields('Cmds',$data_ubi[0]['Cmds']); 		
	   	SetAdoFields('Tipo_Empaque',$data_ubi[0]['Tipo_Empaque']); 		
	   	SetAdoFields('Codigo_P',$data_ubi[0]['Codigo_P']); 			   		
	   	SetAdoFields('Codigo_Inv',$data_ubi[0]['Codigo_Inv']); 
	   	SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']); 		
	   	SetAdoFields('Item',$_SESSION['INGRESO']['item']); 				
	   	SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']); 
	   	return SetAdoUpdate();

	}

}
?>