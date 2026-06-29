<?php

/** 
 * AUTOR DE RUTINA : Javier farinango
 * FECHA CREACION : 12/06/2026
 * FECHA MODIFICACION : 12/06/2026
 * DESCIPCION : Clase que se encarga de manejar impostacion de datos desde excel
 */
require_once (dirname(__DIR__, 2) . "/db/db1.php");
require_once (dirname(__DIR__, 2) . "/funciones/funciones.php");


class importar_desde_excelM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();
	}

	function DCLineas($modulo,$query=false)
	{
		switch ($modulo) {
			case "INVENTARIO":
				$sql="	SELECT Codigo, Cuenta 
              			FROM Catalogo_Cuentas 
              			WHERE Item = '".$_SESSION['INGRESO']['item']."' 
              			AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
              			AND TC = 'P' ";
              			if($query)
              			{
              				$sql.=" AND Cuenta like '%".$query."%'";
              			}
              			$sql.="	ORDER BY Cuenta ";
              		return $this->db->datos($sql);
				break;
			case "FACTURACION":
				$sql = "SELECT *
              			FROM Catalogo_Lineas
              			WHERE TL <> 0
              			AND Item = '".$_SESSION['INGRESO']['item']."'
              			AND Fact IN ('FA','NV')
              			AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
              			if($query)
              			{
              				$sql.=" AND Concepto like '%".$query."%'";
              			}
              			$sql.="	ORDER BY Serie, Codigo ";
              		return $this->db->datos($sql);
				break;
			case "EDUCATIVO":
				 $sql = "SELECT * 
	              		FROM Catalogo_Cursos 
	              		WHERE Item = '".$_SESSION['INGRESO']['item']."'
	              		AND Periodo =  '".$_SESSION['INGRESO']['periodo']."'";
	              		if($query)
	              		{
	              			$sql.=" AND Descripcion like '%".$query."%'";
	              		}
	              		$sql.="	AND LEN(Curso)>4 
	              		ORDER BY Curso ";

              		return $this->db->datos($sql);
				break;
			
			default:
				// code...
				break;
		}
	}
}
?>