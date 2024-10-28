<?php 
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

/**
 * 
 */
class lineas_cxc_factM
{
	private $db;
	function __construct()
	{
	    $this->db = new db();
	}

	// function insert($tabla,$datos)
	// {
	// 	return insert_generico($tabla,$datos);
	// }

	function Catalogo_Lineas($id=false)
	{
		
		// print_r($cuenta);die();
	   $sql="SELECT * 
        	FROM Catalogo_Lineas 
        	WHERE Item = '".$_SESSION['INGRESO']['item']."' 
        	AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        	AND TL <> 0 ";
        	if($id)
        	{
        		$sql.=" AND ID = '".$id."'"; 
        	}
       	// print_r($sql);die();
       	return $this->db->datos($sql);
	}

	function nivel1()
	{
		$sql= "SELECT DISTINCT Autorizacion FROM Catalogo_Lineas
			WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND  Item = '".$_SESSION['INGRESO']['item']."' 
			AND TL <> 0";
       	return $this->db->datos($sql);

	}

	function nivel2($autorizacion)
	{
		$sql= "SELECT DISTINCT Serie FROM Catalogo_Lineas
		WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND Item = '".$_SESSION['INGRESO']['item']."' 
		AND Autorizacion = '".$autorizacion."'
		AND TL <> 0";
       	return $this->db->datos($sql);

	}

	function nivel3($autorizacion,$serie)
	{
		$sql="SELECT DISTINCT Fact FROM Catalogo_Lineas
			WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND Item = '".$_SESSION['INGRESO']['item']."' 
			AND Autorizacion = '".$autorizacion."'
			AND Serie = '".$serie."'
			AND TL <> 0";
       	return $this->db->datos($sql);
	}

	function nivel4($autorizacion,$serie,$fact)
	{
		$sql="SELECT ID,Concepto FROM Catalogo_Lineas
			WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND Item = '".$_SESSION['INGRESO']['item']."' 
			AND Autorizacion = '".$autorizacion."'
			AND Serie = '".$serie."'
			AND TL <> 0
			AND Fact = '".$fact."'";
       	return $this->db->datos($sql);
	}

	function validar_codigo($codigo)
	{
		$sql="SELECT *
      		FROM Catalogo_Lineas
      		WHERE Codigo = '".$codigo."'
      		AND Item = '".$_SESSION['INGRESO']['item']."' 
      		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
      		AND TL <> 0 ";      		
       	return $this->db->datos($sql);
	}

	function elimina_linea($Codigo)
	{
		$sql= "DELETE 
              FROM Catalogo_Lineas 
              WHERE Codigo = '".$Codigo."' 
              AND Item = '".$_SESSION['INGRESO']['item']."' 
              AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
              AND TL <> 0";
        return $this->db->String_Sql($sql);
	}

	function facturas_formato($Codigo,$TxtNumSerieUno,$TxtNumSerieDos,$TxtNumAutor)
	{
		 $sql = "SELECT * 
         	FROM Facturas_Formatos 
         	WHERE Item = '".$_SESSION['INGRESO']['item']."' 
         	AND  Periodo = '".$_SESSION['INGRESO']['periodo']."' 
         	AND Cod_CxC = '".$Codigo."' 
         	AND Serie = '".$TxtNumSerieUno.$TxtNumSerieDos."' 
         	AND Autorizacion = '".$TxtNumAutor."' ";

       	return $this->db->datos($sql);
	}

	function NC($TxtNumSerieUno,$TxtNumSerieDos)
	{
		 $sql = "SELECT Periodo, Item, 'NC' As TC, Serie_NC As Serie_X, MAX(Secuencial_NC) As TC_No 
                 FROM Trans_Abonos 
                
                 GROUP BY Periodo, Item, Serie_NC 
                 ORDER BY Periodo, Item, Serie_NC ";
                 return $this->db->datos($sql);
	}

	function GR($TxtNumSerieUno,$TxtNumSerieDos)

	{
		  $sql = "SELECT Periodo, Item, 'GR' As TC, Serie_GR As Serie_X, MAX(Remision) As TC_No 
                 FROM Facturas_Auxiliares 
                 WHERE Serie_GR = '".$TxtNumSerieUno.$TxtNumSerieDos."' 
                 GROUP BY Periodo, Item, Serie_GR 
                 ORDER BY Periodo, Item, Serie_GR ";
                 return $this->db->datos($sql);
	}
	function FACTURAS($CTipo,$TxtNumSerieUno,$TxtNumSerieDos)
	{
		 $sql = "SELECT Periodo, Item, TC, Serie As Serie_X, MAX(Factura) As TC_No
                 FROM Facturas 
                 WHERE TC = '".$CTipo."' 
                 AND Serie = '".$TxtNumSerieUno.$TxtNumSerieDos."' 
                 GROUP BY Periodo, Item, TC, Serie 
                 ORDER BY Periodo, Item, TC, Serie ";
                 return $this->db->datos($sql);
	}

	function codigos($periodo,$item,$tc,$Serie_X)
	{
		$sql = "SELECT * 
           		FROM Codigos 
            	WHERE Periodo = '".$periodo."' 
            	AND Item = '".$item."' 
            	AND Concepto = '".$tc."_SERIE_".$Serie_X."'";
        return $this->db->datos($sql);
	}

}
?>