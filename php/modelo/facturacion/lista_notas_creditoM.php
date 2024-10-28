<?php
include_once(dirname(__DIR__,2).'/funciones/funciones.php');
include(dirname(__DIR__,3).'/lib/fpdf/reporte_de.php');
@session_start();





class lista_notas_creditoM
{
	private $conn;	
	private $db;
	function __construct()
	{
		$this->db = new db();
	}

	function notas_credito_emitidas_tabla($codigo=false,$desde=false,$hasta=false,$serie=false,$secuencia_NC=false)
	{
		$sql ="SELECT TA.T,TC,Cliente,C.Codigo,C.CI_RUC,TA.Fecha,F.Fecha as 'FechaF',Serie_NC,TA.Clave_Acceso_NC,TA.Autorizacion_NC,Secuencial_NC,F.Factura,F.Serie,F.Autorizacion,F.Total_MN,F.Descuento,F.Descuento2,Nota,IVA,F.Porc_IVA,TA.Autorizacion_NC,TA.Clave_Acceso_NC,TA.Cod_Ejec,Tipo_Pago,Cod_CxC,TA.CodigoU,TB
			FROM Trans_Abonos TA 
			INNER JOIN Facturas F ON TA.Factura = F.Factura
			INNER JOIN Clientes C ON F.CodigoC = C.Codigo
			WHERE TA.Item = '".$_SESSION['INGRESO']['item']."' 
			AND TA.Periodo ='".$_SESSION['INGRESO']['periodo']."'
			AND TA.Serie =  F.Serie
			AND TA.Item = F.Item
			AND TA.Periodo = F.Periodo 
			AND Secuencial_NC<>0";    
		if($codigo!='T' && $codigo!='')
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND F.Codigo ='".$codigo."'";
		} 
		if($serie)
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND Serie_NC ='".$serie."'";
		} 
        if($desde!='' && $hasta!='')
	    {
	     	$sql.= " AND TA.Fecha BETWEEN   '".$desde."' AND '".$hasta."' ";
	    }
	    if($secuencia_NC)
	    {
	    	$sql.=" AND Secuencial_NC = '".$secuencia_NC."'";
	    }
	    $sql.=" GROUP BY TA.T,TC,Cliente,C.Codigo,C.CI_RUC,TA.Fecha,F.Fecha,Serie_NC,TA.Clave_Acceso_NC,TA.Autorizacion_NC,Secuencial_NC,F.Factura,F.Serie,F.Autorizacion,F.Total_MN,F.Descuento,F.Descuento2,Nota,IVA,F.Porc_IVA,TA.Autorizacion_NC,TA.Clave_Acceso_NC,TA.Cod_Ejec,Tipo_Pago,Cod_CxC,TA.CodigoU,TB";
       $sql.=" ORDER BY Serie_NC,Secuencial_NC DESC "; 
		$sql.=" OFFSET ".$_SESSION['INGRESO']['paginacionIni']." ROWS FETCH NEXT ".$_SESSION['INGRESO']['numreg']." ROWS ONLY;";   
	    // // print_r($_SESSION['INGRESO']);
		// print_r($sql);die();    
		return $this->db->datos($sql);

	       // return $datos;
	}

	function lineas_nota_credito($serie,$numero)
	{
        $sql = "SELECT * FROM Detalle_Factura WHERE 
        Periodo = '".$_SESSION['INGRESO']['item']."'
		AND Item = '".$_SESSION['INGRESO']['periodo']."'
        AND Factura = '".$numero."' AND Serie = '".$serie."' ";
        // print_r($sql);die();
         $result = $this->db->datos($sql);
	     return $result;
	}

	function trans_documentos($clave)
	  {
	  	$sql = "SELECT * 
	  	FROM Trans_Documentos 
	  	WHERE Clave_Acceso = '".$clave."'";
		return $this->db->datos($sql);
	  }

}




?>