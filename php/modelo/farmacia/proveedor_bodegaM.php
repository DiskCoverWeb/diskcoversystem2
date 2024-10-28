<?php 
include(dirname(__DIR__,2).'/db/variables_globales.php');//
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
/**
 * 
 */
class proveedor_bodegaM
{
	private $db ;
	function __construct()
	{
	   $this->db = new db();
	}

	
	function lista_clientes($query=false,$ci=false,$id=false)
	{

       $sql= "SELECT TOP 100 *
        FROM Clientes WHERE T='N' ";

		// 02 es facturacion
		if($_SESSION['INGRESO']['modulo_']=='02')
		{
			$sql.= " AND FA <> 0 ";
		}else
		{
			$sql.=" AND Codigo <> '.' ";
		}
		if($query)
		{
			$sql.=" AND Cliente+' '+CI_RUC LIKE '%".$query."%'";
		}
		if($ci)
		{
			$sql.=" AND CI_RUC = '".$ci."' ";
		}

		if($id)
		{
			$sql.=" AND ID = '".$id."' ";
		}
		$sql.=" ORDER BY ID DESC";

		// print_r($sql);die();
		return $this->db->datos($sql);

	}

	function delete_clientes($id)
	{

       $sql= "DELETE FROM Clientes WHERE ID = '".$id."'";
		// print_r($sql);die();
		return $this->db->String_Sql($sql);

	}

	function Catalogo_CxCxP($Cta_Aux,$codigoCliente,$SubCta)
	{
		 $sql = "SELECT * 
		    FROM Catalogo_CxCxP 
		    WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		    AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		    AND Cta = '".$Cta_Aux."' 
		    AND Codigo = '".$codigoCliente."' 
		    AND TC = '".$SubCta."' ";
		return $this->db->datos($sql);

	}

	function cliente_proveedor($query=false)
	{
		$sql = "SELECT TOP 100 CI_RUC,* 
		      FROM Clientes ";
		      //02 codigo que  representa a facturacion
		      if($_SESSION['INGRESO']['modulo_'] == "02")
		      	{
		      		$sql.=" WHERE FA <> 0 ";
		      	}else
		      	{
		      	    $sql.="WHERE Codigo <> '.' ";
		      	}
		      	if($query)
		      	{
		      		if(!is_numeric($query))
		      		{
		      			$sql.="AND Cliente LIKE '%".$query."%'";
		      		}else
		      		{
		      			$sql.="AND CI_RUC LIKE '%".$query."%'";
		      		}
		      	}
		$sql.= " ORDER BY Cliente ";

		// print_r($sql);die();
		return $this->db->datos($sql);
	} 

	function historial_direcciones($codigo)
	{
		$sql = "SELECT Tipo_Dato, Fecha_Registro, Direccion, Telefono, Ciudad, Descuento, Cuenta_No 
          	FROM Clientes_Datos_Extras 
          	WHERE Codigo = '".$codigo."' 
          	AND Item = '".$_SESSION['INGRESO']['item']."' 
          	AND Tipo_Dato IN ('DIRECCION','LIBRETAS') 
          	ORDER BY Tipo_Dato, Fecha_Registro DESC, Cuenta_No ";
		return $this->db->datos($sql);
	}

	function buscar_historial($codigo)
	{
	 	$sql = "SELECT TOP 1 *
       		FROM Clientes_Datos_Extras
       		WHERE Codigo = '".$codigo."'
       		AND Item = '".$_SESSION['INGRESO']['item']."'
       		AND Tipo_Dato = 'DIRECCION'
       		ORDER BY Fecha_Registro DESC ";
       	return $this->db->datos($sql);
	}


	function buscar_cliente($ci=false,$nombre=false,$exacto=false)
	{
		$sql="SELECT Cliente AS nombre, CI_RUC as id, email,Direccion,Telefono,Codigo,Grupo,Ciudad,Prov,DirNumero,ID,FA
		    FROM Clientes  C
		    WHERE T <> '.' ";
		    if($exacto)
		    {
			    if($nombre)
			    {
			    	$sql.=" AND  Cliente = '".$nombre."' ";
			    }
			    if($ci)
			    {
			    	$sql.=" AND CI_RUC = '".$ci."' ";
			    }
			}else
			{
			    if($nombre)
			    {
			    	$sql.=" AND  Cliente LIKE '%".$nombre."%' ";
			    }
			    if($ci)
			    {
			    	$sql.=" AND CI_RUC LIKE '".$ci."%' ";
			    }
			}	
		$sql.=" ORDER BY Cliente OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}



}

?>