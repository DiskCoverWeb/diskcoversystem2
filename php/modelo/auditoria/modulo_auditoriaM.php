<?php 
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

/**
 * 
 */
class modulo_auditoria
{
	private $db;	
	function __construct()
	{
	    $this->db = new db();
	}
}


?>