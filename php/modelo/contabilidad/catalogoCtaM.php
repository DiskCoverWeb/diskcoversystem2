<?php
include(dirname(__DIR__,2)."/db/db1.php");
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
/**
 * 
 */
class catalogoCtaM
{	
		
    private $conn ;
	function __construct()
	{
	   $this->conn = new db();
	}


	function cargar_datos_cuenta_datos($OpcT,$OpcG,$OpcD,$txt_CtaI,$txt_CtaF,$reporte_Excel=false)
	{
		$cid = $this->conn;
		if($txt_CtaI=='')
		{
			$txt_CtaI=1;
		}
		if($txt_CtaF=='')
		{
			$txt_CtaF=9;
		}

		$sql ="SELECT Clave,TC,ME,DG,Codigo,Cuenta,Presupuesto,Codigo_Ext 
       FROM Catalogo_Cuentas 
       WHERE Cuenta <> 'Ninguno' 
       AND Codigo BETWEEN '".$txt_CtaI."' and '".$txt_CtaF."' 
       AND Item = '".$_SESSION['INGRESO']['item']."'
       AND Periodo =  '".$_SESSION['INGRESO']['periodo']."'"; 
       if($OpcG=='true')
       {
       	 $sql.=" AND DG='G'";
       }
       if($OpcD=='true')
       {
       	 $sql.=" AND DG='D'";
       }
       $sql.='ORDER BY Codigo';

       $result = $this->conn->datos($sql);
	   $result = FormatearNumeros($result, false);
	   if($reporte_Excel==false)
	   {
	   	  return $result;
		  
	   }else
	   {
	   	$tablaHTML =array();
	   	 $tablaHTML[0]['medidas']=array(9,9,9,9,20,50,18,18);
         $tablaHTML[0]['datos']=array('Clave','TC','ME','DG','Codigo','Cuenta','Presupuesto','Codigo_Ext');
         $tablaHTML[0]['tipo'] ='C';
         $pos = 1;
		foreach ($result as $key => $value) {
			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
	         $tablaHTML[$pos]['datos']=array($value['Clave'],$value['TC'],$value['ME'],$value['DG'],$value['Codigo'],$value['Cuenta'],$value['Presupuesto'],$value['Codigo_Ext']);
	         $tablaHTML[$pos]['tipo'] ='N';
	         $pos+=1;
		}
	    excel_generico($titulo='PLAN DE CUENTAS',$tablaHTML);
	   	 // $stmt1 = sqlsrv_query($this->conn, $sql);
	     // exportar_excel_generico($stmt1,null,null,$b);

	   }


	}

	function cargar_datos_cuenta_tabla($OpcT,$OpcG,$OpcD,$txt_CtaI,$txt_CtaF)
	{
		$cid = $this->conn;
		if($txt_CtaI=='')
		{
			$txt_CtaI=1;
		}
		if($txt_CtaF=='')
		{
			$txt_CtaF=9;
		}

		$sql ="SELECT Clave,TC,ME,DG,Codigo,Cuenta,Presupuesto,Codigo_Ext 
       FROM Catalogo_Cuentas 
       WHERE Cuenta <> '".G_NINGUNO."' 
       AND Codigo BETWEEN '".$txt_CtaI."' and '".$txt_CtaF."' 
       AND Item = '".$_SESSION['INGRESO']['item']."'
       AND Periodo =  '".$_SESSION['INGRESO']['periodo']."'"; 
       if($OpcG=='true')
       {
       	 $sql.=" AND DG='G'";
       }
       if($OpcD=='true')
       {
       	 $sql.=" AND DG='D'";
       }
       $sql.='ORDER BY Codigo';
       return $this->conn->datos($sql);
	}

	function buscar_cuenta($cuenta,$item=false)
	{
		$datos =array();
		$sql = "SELECT Codigo,Cuenta from Catalogo_Cuentas WHERE Periodo ='".$_SESSION['INGRESO']['periodo']."' AND Codigo+''+Cuenta LIKE '%".$cuenta."%'";
		if($item)
		{
			$sql.=" and Item ='".$item."'";
		}else
		{
			$sql.=" and Item ='".$_SESSION['INGRESO']['item']."'";
		}
		$datos = $this->conn->datos($sql);
		// print_r($dato);die();
	  return $datos;

	}

}
?>