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
		$sql = " SELECT TOP 300 FA.Periodo,FA.Item,F.T,F.TP, F.Fecha, C.Cliente, F.Serie, F.Factura, F.Banco, F.Cheque, F.Abono, F.Mes, F.Comprobante, F.Autorizacion, F.Serie_NC,Secuencial_NC, F.Autorizacion_NC, F.Base_Imponible, F.Porc, C.Representante As Razon_Social, F.Cta,F.Cta_CxP,FA.Descuento2,C.CI_RUC,C.Email,C.Codigo,C.EmailR,C.Email2,F.Cod_Ejec,Tipo_Pago,F.CodigoU,FA.TB,FA.Total_MN,FA.Descuento,FA.Porc_IVA ,FA.Cod_CxC,FA.Fecha as FechaF,FA.TC,FA.IVA,FA.Nota   
           FROM Trans_Abonos As F,Clientes C,Facturas FA 
           WHERE F.Item = FA.Item
			AND F.Periodo = FA.Periodo
			AND F.Factura = FA.Factura
			AND F.Item = '".$_SESSION['INGRESO']['item']."' 
            AND F.Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND F.Banco = 'NOTA DE CREDITO' 
            AND F.CodigoC = C.Codigo ";
		
		if($codigo!='T' && $codigo!='')
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND C.Codigo ='".$codigo."'";
		} 
		if($serie)
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND F.Serie_NC ='".$serie."'";
		} 
        if($desde!='' && $hasta!='')
	    {
	     	$sql.= " AND F.Fecha BETWEEN '".$desde."' and '".$hasta."' ";
	    }
	    if($secuencia_NC)
	    {
	    	$sql.=" AND Secuencial_NC = '".$secuencia_NC."'";
	    }
	    $sql.=" group by FA.Periodo,FA.Item, F.T,F.TP, F.Fecha, C.Cliente, F.Serie, F.Factura, F.Banco, F.Cheque, F.Abono, F.Mes, F.Comprobante, F.Autorizacion, F.Serie_NC,Secuencial_NC,F.Autorizacion_NC,F.Base_Imponible,F.Porc,C.Representante,F.Cta,F.Cta_CxP,FA.Descuento2,C.CI_RUC,C.Email,C.Codigo,C.EmailR,C.Email2,F.Cod_Ejec,F.CodigoU,Tipo_Pago,FA.TB,FA.Total_MN,FA.Descuento,FA.Porc_IVA,FA.Cod_CxC,FA.Fecha,FA.TC,FA.IVA,FA.Nota     ";
	    $sql.=" ORDER BY F.Banco, F.Cheque, C.Cliente, F.Serie, F.Factura, F.Fecha ";
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