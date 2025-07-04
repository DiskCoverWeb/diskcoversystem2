<?php
include(dirname(__DIR__,2).'/modelo/inventario/reubicarM.php');

$controlador = new reubicarC();

if (isset($_GET['lista_stock_ubicado'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->lista_stock_ubicado($parametros));
}
if (isset($_GET['cambiar_bodega'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cambiar_bodega($parametros));
}


class reubicarC
{
    private $modelo;
    private $rutas;

    function __construct()
    {
        $this->modelo = new reubicarM();
        $this->rutas = array();
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
		SetAdoAddNew('Trans_Kardex');
		SetAdoFields('CodBodega',$parametros['codigo']);	
		SetAdoFields('T','N');		
		SetAdoFieldsWhere('ID',$parametros['id']);
		return SetAdoUpdateGeneric();
	}

}
?>