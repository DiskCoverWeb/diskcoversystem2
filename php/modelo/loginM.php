<?php 
require_once(dirname(__DIR__,1)."/db/db1.php");
require_once(dirname(__DIR__,1)."/funciones/funciones.php");

/**
 * 
 */
class loginM
{
	private $db;
	function __construct()
	{
		$this->db = new db();
	}

	function empresa_cartera($entidad,$ID_Entidad=false){		
		$sql = "SELECT *
				  FROM lista_empresas
				  WHERE RUC_CI_NIC = '".$entidad."'
				  AND IP_VPN_RUTA <> '.'";
				  if($ID_Entidad)
				  {
				  	$sql.=" AND ID_Empresa='".$ID_Entidad."'";
				  }
				  // print_r($sql);die();
		$datos = $this->db->datos($sql,$tipo='MY SQL');
		return $datos;		
	}

	function ValidarUser1($usuario,$entidad,$item)
	{

		$query = "SELECT * 
		          FROM acceso_usuarios AU
		          INNER JOIN acceso_empresas AE ON AU.CI_NIC = AE.CI_NIC 
		          WHERE AU.Usuario = '".$usuario."' 
		          and AE.ID_Empresa = '".$entidad."' 
		          AND AE.Item = '".$item."'
		          LIMIT 1";
		 // print_r($query);die();
	    $datos = $this->db->datos($query,$tipo='MY SQL');
	    if(count($datos)>0)
	    {
	    	if($datos[0]['TODOS']==1)
	    	{
	    		return array('respuesta'=>1);
	    	}else
	    	{
	    		return array('respuesta'=>-2);
	    	}
	    }else
	    {
	    	return array('respuesta'=>-1);
	    }
	    // print_r($datos);die();
	}
	
	function Ingresar($usuario,$pass,$entidad,$item){
		$query = "SELECT * 
		          FROM acceso_usuarios AU
		          INNER JOIN acceso_empresas AE ON AU.CI_NIC = AE.CI_NIC 
		          WHERE AU.Usuario = '".$usuario."' 
		          and AE.ID_Empresa = '".$entidad."' 
		          and AE.Item = '".$item."' 
		          and AU.Clave = '".$pass."'
		          LIMIT 1";

		          // print_r($query);die();
		$datos = $this->db->datos($query,$tipo='MY SQL');
		return $datos;	
	}

	function getEmpresas($entidad,$item){

		    $sql = "SELECT * ,L.ID as 'IDEm'
					FROM lista_empresas L
					INNER JOIN entidad E ON L.ID_Empresa = E.ID_Empresa
					WHERE IP_VPN_RUTA<>'.' 
					AND Base_Datos<>'.' 
					AND Usuario_DB<>'.' 
					AND Contrasena_DB<>'.' 
					AND Tipo_Base<>'.' 
					AND Puerto<>'0' 
					AND L.ID_Empresa = '".$entidad."' AND L.Item = '".$item."' ";
					// print_r($sql);die();
		$datos = $this->db->datos($sql,$tipo='MY SQL');
		return $datos;
	}


    function SeteoCta()
    {
    	  $sql= "SELECT *
            FROM Ctas_Proceso 
            WHERE Item = '".$_SESSION['INGRESO']['item']."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            ORDER BY T_No ";
        return  $this->db->datos($sql);
    }

    function getPeriodoActualSQL($Opcem=false)
	{
		if($Opcem)
		{
			$sql="SELECT * 
			FROM Fechas_Balance 
			where detalle='Balance' 
			and Item='".$_SESSION['INGRESO']['item']."'  
			AND periodo='.'";
			 If ($Opcem=='1')
			   {
			   $sql= $sql." AND Detalle = 'Balance Mes' ";
			   }else
				{
					$sql= $sql." AND Detalle = 'Balance' ";
				}
		}else
		{			
			$sql="SELECT * 
			FROM Fechas_Balance 
			where detalle='Balance' 
			and Item='".$_SESSION['INGRESO']['item']."' 
			AND periodo='.' ";
		}

		if( $_SESSION['INGRESO']['Tipo_Base']=='MY SQL')
		{
			return $datos = $this->db->datos($sql,$tipo='MY SQL');
		}else
		{
			return $datos = $this->db->datos($sql);

		}
	}

	function getAccesoEmpresas()
	{
		// print_r($_SESSION['INGRESO']);die();
		$sql="SELECT * 
		FROM Acceso_Empresa 
		WHERE  Codigo='".$_SESSION['INGRESO']['CodigoU']."' ";
			// print_r($sql);die();
		if($_SESSION['INGRESO']['Tipo_Base']!='MY SQL')
		{
			$datos = $this->db->datos($sql);
		}else
		{
			$datos = $this->db->datos($sql,$tipo='MY SQL');
		}
		// print_r($datos);die();
		return $datos;
	}

	function getEmpresasId_sin_sqlserver($entidad,$item){
		    $sql = "SELECT * 
		    FROM lista_empresas E
		    LEFT JOIN entidad L ON E.ID_Empresa = L.ID_Empresa 
		    WHERE E.ID_Empresa = '".$entidad."' AND E.Item = '".$item."' ";
			// print_r($sql);die();
			$datos = $this->db->datos($sql,$tipo='MY SQL');
		    return $datos;
	}

	function getEmpresasDE($item,$nombre)
	{
		$sql="SELECT * 
			FROM Empresas 
			where Item='".$_SESSION['INGRESO']['item']."' ";
		if( $_SESSION['INGRESO']['Tipo_Base']=='MY SQL')
		{			
			return $datos = $this->db->datos($sql,$tipo='MY SQL');
		}else
		{			
		    $datos = $this->db->datos($sql);
		    $sql2 = "select * from Acceso_Sucursales where Sucursal<>'.' ";
		    //las fechas estaban quemadas desde un inicio de sql3

		   //  $sql3="SELECT ROUND((Porc/100), 2) AS porc FROM Tabla_Por_ICE_IVA WHERE IVA <> '0' 
				 // AND Fecha_Inicio <= '20200408' AND Fecha_Final >= '20200408'
				 // ORDER BY Porc ";
		   $sql3 = "SELECT ROUND((Porc/100), 2) AS porc
                FROM Tabla_Por_ICE_IVA 
                WHERE IVA <> 0 
                AND Fecha_Inicio <= '".date('Y-m-d')."' 
                AND Fecha_Final >= '".date('Y-m-d')."'
                ORDER BY Porc DESC ";

		    $suc = $this->db->datos($sql2);
		    $porc = $this->db->datos($sql3);

		    // print_r($porc);die();
		    $datos[0]['porc'] = 0;
		    $datos[0]['Sucursal'] = 0;
		    if(count($suc)>0)
		    {
		    	$datos[0]['Sucursal'] = 1;
		    }
		    if(count($porc))
		    {
		       $datos[0]['porc'] = $porc[0]['porc'];
		    }

			return $datos;
		}

	}
	function modulos_habiliatados()
	{
		$usuario=array();
		$sql="SELECT DISTINCT M.modulo,A.Modulo as 'modulo',M.Aplicacion as 'apli',M.link as 'link',M.icono as 'icono' 
		FROM acceso_empresas A 
		JOIN modulos M on A.Modulo = M.modulo 
		WHERE CI_NIC='".$_SESSION['INGRESO']['Id']."' 
		AND Item='".$_SESSION['INGRESO']['item']."' 
		AND ID_Empresa='".$_SESSION['INGRESO']['IDEntidad']."' 
		 AND link<>'.' AND icono<>'.' ORDER BY aplicacion ASC ";
		// print_r($sql);die();
		$datos = $this->db->datos($sql,$tipo='MY SQL');
		return $datos;
	}
}

?>