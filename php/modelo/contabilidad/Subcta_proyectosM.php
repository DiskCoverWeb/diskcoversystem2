<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
/**
 * 
 */
class Subcta_proyectosM
{	
		
    private $conn ;
	function __construct()
	{
	   $this->conn = new db();
	}

	function DGCostos($query=false,$TodasCtas=false,$CodSubCta=false,$SubCta=false)
	{

		$sql = "SELECT TP.Cta, CC.Cuenta, CS.Detalle, CS.Codigo, TP.ID 
       	FROM Catalogo_Cuentas As CC, Catalogo_SubCtas As CS, Trans_Presupuestos As TP 
       	WHERE CC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       	AND CC.Item = '".$_SESSION['INGRESO']['item']."'";

       	if($TodasCtas=='true'){$sql.=" AND TP.Cta LIKE '".$CodSubCta."%' "; }else{ $sql.=" AND TP.Cta = '".$SubCta."' "; }
       	$sql.=" AND TP.MesNo = 0 
	       AND CC.Periodo = TP.Periodo 
	       AND CC.Item = TP.Item 
	       AND CC.Periodo = CS.Periodo 
	       AND CC.Item = CS.Item 
	       AND CC.Codigo = TP.Cta 
	       AND CS.Codigo = TP.Codigo 
	       ORDER BY TP.Cta, CS.Codigo ";

	       // print_r($sql);die();
	    $datos = $this->conn->datos($sql);

        $medida = medida_pantalla($_SESSION['INGRESO']['Height_pantalla'])-46;  //el numero es el alto de los demas conponenetes sumados
		$button[0] = array('boton'=>'eliminar', 'icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'ID' );
	    $tabla = grilla_generica_new($sql,'Catalogo_Cuentas As CC, Catalogo_SubCtas As CS, Trans_Presupuestos As TP ',$id_tabla=false,$titulo='',$button,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,$medida,$num_decimales=2);

	      return array('tbl'=>$tabla,'datos'=>$datos);

	}

	function DCProyecto()
	{

	   $sql = "SELECT Codigo, Cuenta 
         FROM Catalogo_Cuentas 
         WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
         AND Item = '".$_SESSION['INGRESO']['item']."' 
         AND TC = 'CC' 
         AND DG = 'G' 
         ORDER BY Codigo ";
    	// print_r($sql);die();
	   return  $this->conn->datos($sql);
    }

    function DCSubModulos()
    {
 
        $sql= "SELECT Codigo, Detalle
        FROM Catalogo_SubCtas
        WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND Item = '".$_SESSION['INGRESO']['item']."' 
        AND TC IN ('G','CC')
        ORDER BY Codigo ";            
        	// print_r($sql);die();
	    return $this->conn->datos($sql);
   }

   function DCCtasProyecto($CodSubCta)
   {
   	    $sql = "SELECT Codigo, Cuenta
        FROM Catalogo_Cuentas
        WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND Item = '".$_SESSION['INGRESO']['item']."' 
        AND Codigo LIKE '".$CodSubCta."%' 
        AND DG = 'D' 
        ORDER BY Codigo ";
        return $this->conn->datos($sql);
   }

   function eliminar($id)
   {
   	$sql = 'DELETE FROM Trans_Presupuestos WHERE ID='.$id;
   	return $this->conn->String_Sql($sql);
   }	

   function existe($SubCta,$CodigoBenef)
   {
   	 $sql = " SELECT Cta, Codigo 
            FROM Trans_Presupuestos 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo ='".$_SESSION['INGRESO']['periodo']."' 
            AND Cta = '".$SubCta."' 
            AND Codigo = '".$CodigoBenef."' ";
       return $this->conn->existe_registro($sql);
   }

}
?>