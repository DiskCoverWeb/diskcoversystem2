<?php
require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");
@session_start();

/**
 * 
 */
class registro_GFNM
{	
		
    private $conn ;
    private $db;
	function __construct()
	{
	   $this->db = new db();
	}


	function ddl_indicador_gestion_grupo($query=false)
	{

		$sql = "SELECT ".Full_Fields("Catalogo_APIs")." 
				FROM Catalogo_APIs 
				WHERE Item = '".$_SESSION['INGRESO']['item']."' 
				AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
				AND LEN(Codigo)=2 ";
				if($query)
				{
					$sql.=" AND Descripcion like '%".$query."%'";
				}
		$sql.=" ORDER BY Descripcion";

		return $this->db->datos($sql);
	}

	function ddl_indicador_gestion($grupo,$query=false)
	{

		$sql = "SELECT ".Full_Fields("Catalogo_APIs")." 
				FROM Catalogo_APIs 
				WHERE Item = '".$_SESSION['INGRESO']['item']."' 
				AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
				AND Codigo like '".$grupo.".%'";
				if($query)
				{
					$sql.=" AND Descripcion like '%".$query."%'";
				}
		$sql.=" ORDER BY Descripcion";
		// print_r($sql);die();

		return $this->db->datos($sql);
	}

	function ddl_indicador_gestion_search($grupo,$query=false)
	{

		$sql = "SELECT ".Full_Fields("Catalogo_APIs")." 
				FROM Catalogo_APIs 
				WHERE Item = '".$_SESSION['INGRESO']['item']."' 
				AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
				AND Codigo = '".$grupo."'";
				if($query)
				{
					$sql.=" AND Descripcion like '%".$query."%'";
				}
		$sql.=" ORDER BY Descripcion";
		// print_r($sql);die();

		return $this->db->datos($sql);
	}

}
?>