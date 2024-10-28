<?php
include_once(dirname(__DIR__,2).'/funciones/funciones.php');
include(dirname(__DIR__,3).'/lib/fpdf/reporte_de.php');
@session_start();





class lista_retencionesM
{
	private $conn;	
	private $db;
	function __construct()
	{
		$this->db = new db();
	}

	function retenciones_emitidas_tabla($codigo,$desde=false,$hasta=false,$serie=false)
	{
		$sql ="SELECT TC.ID,IdProv,C.Cliente,TC.T,TP,Serie_Retencion,TC.AutRetencion,SecRetencion,TC.Fecha,C.TD,C.CI_RUC,Numero,BaseImponible 
		FROM Trans_Compras TC
		INNER JOIN Clientes C ON TC.IdProv = C.Codigo
		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";    
		if($codigo!='T')
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND IdProv ='".$codigo."'";
		} 
		if($serie)
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND Serie_Retencion ='".$serie."'";
		} 
        if($desde!='' && $hasta!='')
	    {
	     	$sql.= " AND TC.Fecha BETWEEN   '".$desde."' AND '".$hasta."' ";
	    }

       $sql.="ORDER BY Serie_R,SecRetencion DESC "; 
		$sql.=" OFFSET ".$_SESSION['INGRESO']['paginacionIni']." ROWS FETCH NEXT ".$_SESSION['INGRESO']['numreg']." ROWS ONLY;";   
	    // // print_r($_SESSION['INGRESO']);
		// print_r($sql);die();    
		return $this->db->datos($sql);

	       // return $datos;
	}

	function retenciones_buscar($serie,$numero,$fecha)
	{
        $sql = "SELECT C.Cliente,C.CI_RUC,C.TD,C.Direccion,C.Telefono,C.Email,TC.* 
        FROM Trans_Compras As TC, Clientes As C 
        WHERE TC.Item = '".$_SESSION['INGRESO']['item']."' 
        AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND TC.Serie_Retencion = ".$serie." 
        AND TC.SecRetencion = '".$numero."'         
        AND TC.Fecha = '".$fecha."' 
        AND LEN(TC.AutRetencion) = 13 
        AND TC.IdProv = C.Codigo 
        ORDER BY Serie_Retencion,SecRetencion ";
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