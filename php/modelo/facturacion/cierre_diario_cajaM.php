<?php
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class cierre_diario_cajaM
{
	private $db;

	public function __construct(){
    //base de datos
    $this->db = new db();
  }

  function consultar_reporte_cartera($tabla=false){
    $sql="SELECT C.Cliente, RCC.T, RCC.TC, RCC.Serie, RCC.Factura, RCC.Fecha, RCC.Detalle, RCC.Anio, RCC.Mes, RCC.Cargos, RCC.Abonos, RCC.CodigoC,
        C.Email, C.EmailR, C.Direccion
        FROM Reporte_Cartera_Clientes As RCC, Clientes As C
        WHERE RCC.Item = '".$_SESSION['INGRESO']['item']."' 
        AND RCC.CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
        AND RCC.T <> 'A'
        AND RCC.CodigoC = C.Codigo
        ORDER BY C.Cliente, RCC.TC, RCC.Serie, RCC.Factura, RCC.Anio, RCC.Mes, RCC.ID ";
          // print_r($sql);die();
      
    $stmt = $this->db->datos($sql);
    if($tabla)
    {    
   		$num_reg = array('0','500','consultar_datos');
	    $medida = medida_pantalla($_SESSION['INGRESO']['Height_pantalla'])-50;
	    $tbl = grilla_generica_new($sql,'Reporte_Cartera_Clientes As RCC, Clientes As C','',$titulo=false,$botones=false,$check=false,$imagen=false,1,1,1,$medida,$decimales=2,$num_reg,$paginacion=0);
			 // print_r($tbl);die();
		return $tbl;
	}

    return $stmt;
  }
}

?>