<?php
include(dirname(__DIR__,2).'/modelo/contabilidad/resumen_retencionesM.php');
// require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */

class resumen_retencionesC
{
	
	private $modelo;
	// private $pdf;
	
	function __construct()
	{
	   $this->modelo = new  resumen_retencionesM();	   
	   // $this->pdf = new cabecera_pdf();
	}


	
}
?>