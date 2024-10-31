<?php 
require_once(dirname(__DIR__,1)."/modelo/menuM.php");

/**
 * 
 */
class menuC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new menuM();
	}


	function generar_menu($modulo)
	{
		$menu = $this->modelo->select_menu_mysql($modulo);
       	$accesos_pag = $this->modelo->pagina_acceso_hijos($modulo,$_SESSION['INGRESO']['CodigoU'], $_SESSION['INGRESO']['IDEntidad'], $_SESSION['INGRESO']['item']);
       if (count($accesos_pag) > 0) 
       {
       		$menuHTML = $this->menu_restringido();
       }else
       {
       		array_shift($menu);  //elimina el primer dato
       	  	$menuHTML = $this->menu_completo($menu);
       }

       return $menuHTML;             

	}

	function menu_restringido()
	{

	}

	function filtrarPorCodMenu($array, $prefix) {
	    if (!is_array($array)) {
	        throw new InvalidArgumentException("El primer argumento debe ser un array.");
	    }
	    return array_filter($array, function($item) use ($prefix) {
	        return strpos($item['codMenu'], $prefix) === 0 && $item['codMenu'] !== $prefix;
	    });
	}


	function crearArbol($items) {
	    $tree = [];
	    $partsIni = "";

	    foreach ($items as $item) {
	        $parts = explode('.', $item['codMenu']);
	        $partsIni = $parts[0];
	        $current = &$tree;

	        foreach ($parts as $part) {
	            // Si el nodo ya existe y no es un array, continúa con el siguiente nodo
	            if (!isset($current[$part]) || !is_array($current[$part])) {
	                $current[$part] = [];
	            }
	            $current = &$current[$part];
	        }

	        // Solo agregar la información si aún no está establecida
	        if (!isset($current['info'])) {
	            $current['info'] = $item; // Añadir la información del nodo
	        }
	    }

	    // Devolver el árbol completo a partir del primer nodo principal
	    return $tree[$partsIni];
	}


	function generarHTML($arbol) 
	{
	    $html = "<ul>";
	    foreach ($arbol as $key => $nodo) {
	        if (isset($nodo['info'])) {
	            $descripcion = $nodo['info']['descripcionMenu'];
	            $ruta = $nodo['info']['rutaProceso'] !== '.' ? $nodo['info']['rutaProceso'] : '#';
	            $padre = $nodo['info']['rutaProceso'] !== '.' ? $nodo['info']['rutaProceso'] : 'class="has-arrow"';
	            $padreBool = $nodo['info']['rutaProceso'] !== '.' ? 0 : 1;

	            $html .= "<li><a href='{$ruta}' {$padre}>";
	            			if($padreBool)
	            			{
	            				$html.="<div class='menu-title'>{$descripcion}</div>";
	            			}else
	            			{
	            				$html.="<i class='bx bx-right-arrow-alt'></i> {$descripcion}";
	            			}
	            	$html.="</a>";

	            // Llamada recursiva solo si hay nodos hijos
	            if (count($nodo) > 1) { // Esto verifica que haya otros elementos en el nodo
	                $html .= $this->generarHTML($nodo); // Llamada recursiva para elementos hijos
	            }

	            $html .= "</li>";
	        }
	    }
	    $html .= "</ul>";

	    return $html;
	}



	function menu_completo($menu)
	{
		// print_r($menu);die();
		$arbol = $this->crearArbol($menu);
		// print_r($arbol);die();
		$HTML = $this->generarHTML($arbol);
		// print_r($HTML);die();
		return $HTML;
	}

}

?>