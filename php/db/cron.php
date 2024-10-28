<?php 

	
	$cn = new crom();	
	$funciones;
	$fecha = '';
	if(isset($_SESSION['INGRESO']['Fecha_Actualizacion']))
	{
		$fecha = $_SESSION['INGRESO']['Fecha_Actualizacion'];
	}
	$now = date('Y-m-d');

	if($fecha!=$now && isset($_SESSION['INGRESO']['RUCEnt']) )
	{
		// print_r($_SESSION['INGRESO']);die();
		$ci = $_SESSION['INGRESO']['RUCEnt'];
		switch ($ci) {
			case '1791921429001':
			$cn->actualizar_estado_benediciario_BAQ();
				break;
			
			default:
				// code...
				break;
		}

	}


	/**
	 * 
	 */
	class crom 
	{
		
		function __construct()
		{
			// code...
		}

		function actualizar_estado_benediciario_BAQ()
		{
			$conn = new db();
        	$diaActual =  BuscardiasSemana(date('w'));
        	$diaActual = $diaActual[1]+1;
        	if($diaActual>6)
	        {
	            $diaActual = 0;
	        }
   			$dia =  BuscardiasSemana($diaActual);
   			$dia =  substr($dia[0],0,3);
   			// print_r($dia);die();
   			$sql = "UPDATE Clientes 
   					SET Estado = 0;
   					UPDATE Clientes 
   					SET Estado = 1
   					WHERE Dia_Ent = '".$dia."';";
   					// print_r($sql);die();
   			$_SESSION['INGRESO']['Fecha_Actualizacion'] = date('Y-m-d');
   			//return 'hola';
   			$conn->datos($sql);

		}
	}
?>