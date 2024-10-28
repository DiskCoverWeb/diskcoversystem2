<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
/**
 * 
 */
class cierre_mesM
{	
		
    private $db ;
	function __construct()
	{
	   $this->db = new db();
	}

	function LstMeses()
	{
		$sql="SELECT *
      		FROM Fechas_Balance 
      		WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
      		AND Item = '".$_SESSION['INGRESO']['item']."'
      		AND ISNUMERIC(SUBSTRING(Detalle,1,4)) <> 0
      		ORDER BY Fecha_Inicial ";
       return $this->db->datos($sql);
	}

	function Crear_Cierre_Mes($query)
	{
		$sql = $query;		
       return $this->db->datos($sql);
	}

	function delete_fecha_balance($AnioI)
	{
		$sql ="DELETE
          	FROM Fechas_Balance 
          	WHERE Item = '" .$_SESSION['INGRESO']['item']."' 
          	AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          	AND Detalle <= '".$AnioI."' ";

       return $this->db->String_Sql($sql);
	}

	function LstMeses2($Detalle_Mes)
	{
		$sql="SELECT * 
              FROM Fechas_Balance 
              WHERE Item = '" .$_SESSION['INGRESO']['item']."' 
              AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
              AND Detalle = '".$Detalle_Mes."' ";
       return $this->db->datos($sql);
	}

	function actualizar_fecha_balance($Mes_C,$Anio,$Fecha_Mes)
	{
		$sql= "UPDATE Fechas_Balance
	           SET Cerrado = ".$Mes_C."
	           WHERE SUBSTRING(Detalle,1,4) = '".$Anio."'
	           AND Fecha_Inicial = '".BuscarFecha($Fecha_Mes)."'
	           AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
	           AND Item = '".$_SESSION['INGRESO']['item']."' ";
       return $this->db->String_Sql($sql);

	}

}
?>