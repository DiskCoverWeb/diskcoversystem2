<?php
include_once(dirname(__DIR__,2).'/funciones/funciones.php');
include(dirname(__DIR__,3).'/lib/fpdf/reporte_de.php');
@session_start();





class lista_guia_remisionM
{
	private $conn;	
	private $db;
	function __construct()
	{
		$this->db = new db();
	}

	function guia_remision_emitidas_tabla($codigo=false,$desde=false,$hasta=false,$serie=false,$factura=false,$Autorizacion=false,
$Autorizacion_GR=false,$remision=false,$serie_gr=false)
	{
		$sql ="SELECT * FROM Facturas_Auxiliares
				WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND Item = '".$_SESSION['INGRESO']['item']."'";    
			if($codigo!='T' && $codigo!='')
			{
				// si el codigo es T se refiere a todos
			   $sql.=" AND CodigoC ='".$codigo."'";
			} 
			if($serie)
			{
				// si el codigo es T se refiere a todos
			   $sql.=" AND Serie ='".$serie."'";
			} 
			if($serie_gr)
			{
				// si el codigo es T se refiere a todos
			   $sql.=" AND Serie_GR ='".$serie_gr."'";
			} 
			if($Autorizacion)
			{
				// si el codigo es T se refiere a todos
			   $sql.=" AND Autorizacion ='".$Autorizacion."'";
			} 
			if($Autorizacion_GR)
			{
				// si el codigo es T se refiere a todos
			   $sql.=" AND Autorizacion_GR ='".$Autorizacion_GR."'";
			} 
      if($desde!='' && $hasta!='')
	    {
	     	$sql.= " AND FechaGRE BETWEEN   '".$desde."' AND '".$hasta."' ";
	    }
	    if($factura)
	    {
	    	$sql.=" AND Factura = '".$factura."'";
	    }
	    if($remision)
	    {
	    	$sql.=" AND Remision = '".$remision."'";
	    }
	   $sql.=" ORDER BY Remision DESC"; 
		$sql.=" OFFSET ".$_SESSION['INGRESO']['paginacionIni']." ROWS FETCH NEXT ".$_SESSION['INGRESO']['numreg']." ROWS ONLY;";   
	    // // print_r($_SESSION['INGRESO']);
		// print_r($sql);die();    
		return $this->db->datos($sql);

	       // return $datos;
	}


	function guia_remision_existente($codigo=false,$desde=false,$hasta=false,$serie=false,$factura=false)
	{
		$sql ="SELECT * FROM Facturas_Auxiliares
				WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND Item = '".$_SESSION['INGRESO']['item']."'";    
		if($codigo!='T' && $codigo!='')
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND CodigoC ='".$codigo."'";
		} 
		if($serie)
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND Serie_GR ='".$serie."'";
		} 
        if($desde!='' && $hasta!='')
	    {
	     	$sql.= " AND FechaGRE BETWEEN   '".$desde."' AND '".$hasta."' ";
	    }
	    if($factura)
	    {
	    	$sql.=" AND Remision = '".$factura."'";
	    }
	   $sql.=" ORDER BY Remision DESC"; 
	   // // print_r($_SESSION['INGRESO']);
		// print_r($sql);die();    
		return $this->db->datos($sql);

	       // return $datos;
	}



	function factura($factura=false,$serie=false,$Autorizacion=false)
	{
		$sql="SELECT * 
		    FROM Facturas 
		    WHERE Item = '".$_SESSION['INGRESO']['item']."'
		    AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
		    if($serie)
		    { 
		    	$sql.=" AND Serie='".$serie."'";
		    } 
		    if($factura)
		    {
		    	$sql.=" AND Factura='".$factura."'";
		    } 
		    if($Autorizacion)
		    {
		    	$sql.=" AND Autorizacion='".$Autorizacion."' ";
			}

		return $this->db->datos($sql);
		   
	}

	function lineas_guia_remision($serie,$numero)
	{
        $sql = "SELECT * FROM Detalle_Factura WHERE 
        Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND Item = '".$_SESSION['INGRESO']['item']."'
				AND TC = 'GR'
        AND Factura = '".$numero."' 
        AND Serie = '".$serie."' ";
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

	function getAsiento(){
    	$sql = "SELECT * 
            FROM Asiento_F
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
            ORDER BY A_No ";
    $stmt = $this->db->datos($sql);
    return $stmt;
  }
   function getProductos_datos($codigo){

    $sql="SELECT *
          FROM Catalogo_Productos 
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND TC = 'P' 
          AND  Codigo_Inv = '".$codigo."'
          ORDER BY Producto,Codigo_Inv ";
          // print_r($sql);die();
    $stmt = $this->db->datos($sql);
    return $stmt;
  }

	function cargarLineas($guia,$codigoL=false){
    $sql = "SELECT Codigo,Cantidad as 'CANTIDAD',Producto,Precio AS 'PRECIO',Total,ID, Total_IVA
            FROM Detalle_Factura
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
            AND TC = 'GR'
            AND Factura = '".$guia."'";
            if($codigoL)
            {
            	$sql.=" AND CodigoL = '".$codigoL."'";
            }
            $sql.=" ORDER BY ID Desc ";
            // print_r($sql);die();
            $botones[0] = array('boton'=>'Eliminar', 'icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'ID' );
           $datos = $this->db->datos($sql);
           $stmt =  grilla_generica_new($sql,'Asiento_F','tbl_lineas',false,$botones,false,false,1,1,0,$tamaño_tabla=250,4);
     return array('tbl'=>$stmt,'datos'=>$datos);
  }

  function limpiarGrid($cod=false,$factura=false,$codigoL=false,$auto=false){
    $sql = "DELETE
          FROM Detalle_Factura
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND CodigoU = '". $_SESSION['INGRESO']['CodigoU'] ."' 
          AND TC = 'GR'
          AND LEN(Autorizacion)<=13 ";
          if($cod)
          {
            $sql.=" AND ID = '".$cod."'";
          }
          if($factura)
          {
          	$sql.="AND Factura = '".$factura."'";
          }
          if($codigoL)
          {
          	$sql.="AND CodigoL = '".$codigoL."'";
          }
          if($auto)
          {
          	$sql.="AND Autorizacion = '".$auto."'";
          }
    $stmt = $this->db->String_Sql($sql);
    return $stmt;
  }

  function AdoPersonas($query)
  {             
      $sql = "SELECT Cliente,CI_RUC,TD,Direccion,Codigo 
          FROM Clientes 
          WHERE TD IN ('C','R') 
          AND LEN(CI_RUC)>=13";
         if($query)
          {
            $sql.=" and Cliente like '%".$query."%'";
          } 
         $sql.="ORDER BY Cliente OFFSET 0 ROWS FETCH NEXT 30 ROWS ONLY;";
     $respuest  = $this->db->datos($sql);
     return $respuest;
     
  }

}




?>