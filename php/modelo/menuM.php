<?php 
require_once(dirname(__DIR__,1)."/db/db1.php");
require_once(dirname(__DIR__,1)."/funciones/funciones.php");
/**
 * 
 */
class menuM
{
	private $db;
	function __construct()
	{
		$this->db = new db();
	}

	function select_menu_mysql($modulo)
	{	 
	  $sql = "SELECT * FROM menu_modulos WHERE codMenu LIKE '".$modulo."%' ORDER BY codMenu ASC";
	  $submenu = $this->db->datos($sql,'MYSQL');

	  // $submenu=$cid->query($sql) or die($cid->error);
	  $array_menu = array();
	  $i = 0;

	  $lista = array();
	  foreach ($submenu as $key => $value) {
	  	$lista[] = array('codMenu'=> $value['codMenu'],
	  					  'descripcionMenu' => $value['descripcionMenu'],
	  					  'accesoRapido'=>$value['accesoRapido'],
	  					  'rutaProceso' => $value['rutaProceso']);
	    
	  }	 
	  return $lista;
	}

	function pagina_acceso_hijos($modulo,$usuario,$entidad,$item)
	{
	  $sql  = "SELECT * 
	           FROM acceso_empresas AE
	           INNER JOIN menu_modulos MM ON AE.Pagina = MM.ID 
	           WHERE ID_Empresa = '".$entidad."' 
	           AND CI_NIC = '".$usuario."' 
	           AND codMenu LIKE '".$modulo."%' 
	           AND Item = '".$item."' 
	           AND Pagina != '.' 
	           AND Pagina != ''
	           ORDER BY CodMenu ASC";

	 // print_r($sql);
	  $submenu = $this->db->datos($sql,'MYSQL');
	 
	  $lista = array();
	  foreach ($submenu as $key => $value) {
	  	$lista[] = array('codMenu'=> $value['codMenu'],
	  					  'descripcionMenu' => $value['descripcionMenu'],
	  					  'accesoRapido'=>$value['accesoRapido'],
	  					  'rutaProceso' => $value['rutaProceso'],
	  					  'Pagina'=> $value['Pagina']);	    
	  }
	 
	  return $lista;
	  
	}


	function generar_menu()
	{

	}

	function menu_restringido()
	{

	}

	function menu_completo()
	{
		
	}
}

?>