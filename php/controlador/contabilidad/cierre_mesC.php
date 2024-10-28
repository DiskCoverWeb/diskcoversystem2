<?php
include(dirname(__DIR__,2).'/modelo/contabilidad/cierre_mesM.php');
/**
 * 
 */

$controlador = new cierre_mesC();
if(isset($_GET['lista']))
{
	echo json_encode($controlador->LstMeses());
}

if(isset($_GET['cierre_mes']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->Crear_Cierre_Mes($parametros));
}


if(isset($_GET['grabar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->Grabar($parametros));
}


class cierre_mesC
{
	
	private $modelo;
	private $pdf;
	
	function __construct()
	{
	   $this->modelo = new  cierre_mesM();	   
	}

	function LstMeses()
	{
		$datos = $this->modelo->LstMeses();
		$op='';
		$chec ='';
		foreach ($datos as $key => $value) {
			if($value['Cerrado']==1)
			{
				$chec ='checked';
			}
			$op.='<label><input type="checkbox" id="'.str_replace(' ','_',$value['Detalle']).'" value="'.$value['Detalle'].'" '.$chec.' onclick="validar(\''.str_replace(' ','_',$value['Detalle']).'\');"> '.$value['Detalle'].'</label><br>';
			$chec ='';
		}
		return $op;
		// print_r($datos);die();
	}


	function Crear_Cierre_Mes($parametro)
	{
		$AnioNuevo = $parametro['year'];

		$AnioI = date('Y');
    	$AnioF = date('Y');
    	if($AnioNuevo == "")
    	{
    		$FechaFin = BuscarFecha($AnioF.'/12/31');
    		// print_r($FechaFin);die();    	
	       if($_SESSION['INGRESO']['Tipo_Base']=='SQLSERVER' || $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER')
	       {
	          $sql= "SELECT YEAR(Fecha) As Anio ";
	       }else{
	          $sql = "SELECT DATEPART('yyyy',Fecha) As Anio ";
	       }
	       $sql.="FROM Comprobantes 
	              WHERE Item = '" .$_SESSION['INGRESO']['item']. "' 
	              AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
	              AND Fecha <= '".$FechaFin."' 
	              AND T <> 'A' ";
	       if($_SESSION['INGRESO']['Tipo_Base']=='SQLSERVER' || $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER')
	       {
	          $sql.="GROUP BY YEAR(Fecha)
	                ORDER BY YEAR(Fecha) ";
	       }else{
	          $sql.="GROUP BY DATEPART('yyyy',Fecha)
	                ORDER BY DATEPART('yyyy',Fecha) ";
	       }

	       // print_r($sql);die();
	       $datos = $this->modelo->Crear_Cierre_Mes($sql);
	       if(count($datos)>0)
	       {	       	 
	       	$AnioI = $datos[0]["Anio"];
	         $AnioF = $datos[count($datos)-1]["Anio"];
	         if($AnioI < 2000){$AnioI = 2000;}
	    	}
   
	    }else{
	        if(strlen($AnioNuevo) == 4){
	           $AnioI =$AnioNuevo;
	           $AnioF = $AnioNuevo;
	        }
	    }
	    $this->modelo->delete_fecha_balance($AnioI);

	    $lista = array();
	    $C = 0;
	    for ($i=$AnioI; $i<=$AnioF ; $i++) { 
	    	$anio = $i;	    	
	    	for ($j=1; $j <=12 ; $j++) { 
	    		$FechaIni = '01/'.generaCeros($j,2).'/'.$anio;
	    		$FechaFin = UltimoDiaMes($FechaIni);
	    		$Mes = MesesLetras($j);
            $Detalle_Mes = $anio." ".$Mes;
            if($_SESSION['INGRESO']['periodo']== G_NINGUNO){
               if($j >= date('m') && $i == date('Y')){$C = 0;}
            }else{
               $C = 1;
            }

            $datos1 = $this->modelo->LstMeses2($Detalle_Mes);
            if(count($datos1)<=0)
            {
            		$lista[] = array("Item"=>$_SESSION['INGRESO']['item'],"Periodo"=>$_SESSION['INGRESO']['periodo'],"Detalle"=>$Detalle_Mes,"Fecha_Inicial"=>$FechaIni,"Fecha_Final"=>$FechaFin,"Cerrado"=>$C,'');

            }	    		
	    	}
	    }


	   $op='';
		$chec ='';

	    foreach ($lista as $key => $value) {
			if($value['Cerrado']==1)
				{
					$chec ='checked';
				}
				$op.='<label><input type="checkbox" id="'.str_replace(' ','_',$value['Detalle']).'" value="'.$value['Detalle'].'" '.$chec.' onclick="validar(\''.str_replace(' ','_',$value['Detalle']).'\')" value="'.$value['Detalle'].'"> '.$value['Detalle'].'</label><br>';
				$chec ='';
		    	
		    }
		    if($AnioNuevo=='')
		    {
		    	// print_r($this->LstMeses());die();
		    	$opt = $this->LstMeses();
		    	$op =  $op.'<br>'.$opt;
		    }

		    // print_r($op);die();
			 return $op;    
	}

function grabar($parametro)
{
	$false = array();
	$true = array();
	if(isset($parametro['chekFalse'])){	$false = $parametro['chekFalse'];}
	if(isset($parametro['chekTrue'])){$true = $parametro['chekTrue'];}
	$resp = 1;

	
		foreach ($false as $key => $value) {
			$anio = explode(' ', $value);
		   $Fecha_Mes = $anio[0].'/'.nombre_X_mes($anio[1])."/01";

			$r = $this->modelo->actualizar_fecha_balance(0,$anio[0],$Fecha_Mes);
			if($r==-1)
			{
				$resp=-1;
			}
		}
	// print_r($true);die();

		foreach ($true as $key => $value) {
			$anio = explode(' ', $value);
		   $Fecha_Mes = $anio[0].'/'.nombre_X_mes($anio[1])."/01";
			$r = $this->modelo->actualizar_fecha_balance(1,$anio[0],$Fecha_Mes);
			if($r==-1)
			{
				$resp=-1;
			}
		}

	return $resp;

}


}
?>