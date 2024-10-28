<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
/**
 * 
 */
class campeM
{	
		
    private $conn ;
    private $db;
	function __construct()
	{
	   $this->db = new db();
	}

	function peridos()
	{
		// list_option('Catalogo_Periodo_Lectivo','Periodo','Periodo',"Periodo <> '.' AND Item = '".$_SESSION['INGRESO']['item']."' GROUP BY Periodo ORDER BY Periodo "); 

		$sql = "SELECT Periodo 
				FROM Catalogo_Periodo_Lectivo 
				WHERE Periodo <> '.' 
				AND Item = '".$_SESSION['INGRESO']['item']."' 
				GROUP BY Periodo ORDER BY Periodo";
		return $this->db->datos($sql);
	}

	function facturas_periodo()
	{

		// list_option('Facturas','Periodo','Periodo',"Periodo <> '.' AND Item = '".$_SESSION['INGRESO']['item']."' GROUP BY Periodo ORDER BY Periodo "); 

		$sql = "SELECT Periodo 
				FROM Facturas 
				WHERE Periodo <> '.' 
				AND Item = '".$_SESSION['INGRESO']['item']."' 
				GROUP BY Periodo ORDER BY Periodo";
		return $this->db->datos($sql);
	}

	function catalogoCta_periodo()
	{
		// list_option('Catalogo_Cuentas','Periodo','Periodo',"Periodo <> '.' AND Item = '".$_SESSION['INGRESO']['item']."' GROUP BY Periodo ORDER BY Periodo "); 
		$sql = "SELECT Periodo 
				FROM Catalogo_Cuentas 
				WHERE Periodo <> '.' 
				AND Item = '".$_SESSION['INGRESO']['item']."' 
				GROUP BY Periodo ORDER BY Periodo";
		return $this->db->datos($sql);
	}
}
?>