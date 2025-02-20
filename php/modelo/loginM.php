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

	function getAccesoEmpresasMYSQL()
	{
		$usuario=array();
		$sql="SELECT * 
		FROM acceso_empresas 
		WHERE CI_NIC='".$_SESSION['INGRESO']['CodigoU']."' AND Item='".$_SESSION['INGRESO']['item']."'
		 ";
		// echo $sql;die();
		$consulta=$this->db->query($sql);
		if($consulta)
		{
          $i=0;
			while($filas=$consulta->fetch_assoc()) 
			{
				//echo "entro 1";
				$usuario[$i]['Modulo']=$filas['Modulo'];	
				//echo " mmm ".$permiso[$i]['Modulo'].' ind= '.$i.'<br>';
				$usuario[$i]['Item']=$filas['Item'];					
				//echo $empresa[$i]['Opc'].' '.$empresa[$i]['Sucursal'];
				//die(); 			
				//echo $empresa[$i]['Fecha_Inicial'];
				$i++;
			}
			//no existe
			if($i==0)
			{
				//echo "entro 2";
				$usuario[$i]['Modulo']='TODOS';
				$usuario[$i]['Item']='TODOS';
				$_SESSION['INGRESO']['accesoe']='TODOS';
				$_SESSION['INGRESO']['modulo'][$i]='TODOS';
			}
			else
			{
				//hacemos ciclo para buscar si puede acceder a la empresa y que modulos
				$j=0;
				for($i=0;$i<count($usuario);$i++)
				{
					if($usuario[$i]['Item']==$_SESSION['INGRESO']['item'])
					{
						//echo $permiso[$i]['Item']." ".$_SESSION['INGRESO']['item']."<br>";
						$_SESSION['INGRESO']['accesoe']='1';
						$_SESSION['INGRESO']['modulo'][$j]=$usuario[$i]['Modulo'];
						//echo ' per '.$permiso[$i]['Modulo'].' '.$_SESSION['INGRESO']['modulo'][$j].' ind= '.$i.'<br>';
						$j++;
					}
				}
			}
        } 

        return $usuario;
	}

	function getAccesoEmpresasSQL()
	{
		// print_r($_SESSION['INGRESO']);die();
		$permiso=array();
		$_SESSION['INGRESO']['modulo']=array();
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
		if(count($datos)>0)
		{
			foreach ($datos as $key => $value) {
				// print_r($value);die();
			  $_SESSION['INGRESO']['accesoe']='1';
			  $_SESSION['INGRESO']['modulo'][$key]=$value['Modulo'];
		    }

		}else
		{
			$_SESSION['INGRESO']['accesoe']='TODOS';
			$_SESSION['INGRESO']['modulo'][0]='TODOS';
		}
		

	}

	function getAccesoEmpresasSQL1()
	{
		// $permiso=array();
		// $_SESSION['INGRESO']['modulo']=array();
		// $sql="SELECT    * FROM Acceso_Empresa 
		// 		WHERE  Codigo='".$_SESSION['INGRESO']['CodigoU']."' ";
		// $stmt = false;
		// if($this->dbs!='')
		// {
		// 	$stmt = sqlsrv_query( $this->dbs, $sql);
		// }
		if( $stmt === false)  
		{  
			 // //echo "Error en consulta PA.\n";  
			 // echo "<script>
				// 			/*Swal.fire({
				// 				type: 'error',
				// 				title: 'Fallo',
				// 				text: 'Error en consulta PA.',
				// 				footer: ''
				// 			})*/
				// 			alert('Error en consulta');
				// 	</script>";
			 // if($_SESSION['INGRESO']['ERROR']==1)
			 // {
				// die( print_r( sqlsrv_errors(), true)); 
			 // }
			 // die();
		} 
		else
		{
			$i=0;
			while( $obj = sqlsrv_fetch_object( $stmt)) 
			{
				//echo "entro 1";
				$permiso[$i]['Modulo']=$obj->Modulo;	
				//echo " mmm ".$permiso[$i]['Modulo'].' ind= '.$i.'<br>';
				$permiso[$i]['Item']=$obj->Item;					
				//echo $empresa[$i]['Opc'].' '.$empresa[$i]['Sucursal'];
				//die(); 			
				//echo $empresa[$i]['Fecha_Inicial'];
				$i++;
			}
			//no existe
			if($i==0)
			{
				//echo "entro 2";
				$permiso[$i]['Modulo']='TODOS';
				$permiso[$i]['Item']='TODOS';
				$_SESSION['INGRESO']['accesoe']='TODOS';
				$_SESSION['INGRESO']['modulo'][$i]='TODOS';
			}
			else
			{
				//hacemos ciclo para buscar si puede acceder a la empresa y que modulos
				$j=0;
				for($i=0;$i<count($permiso);$i++)
				{
					if($permiso[$i]['Item']==$_SESSION['INGRESO']['item'])
					{
						//echo $permiso[$i]['Item']." ".$_SESSION['INGRESO']['item']."<br>";
						$_SESSION['INGRESO']['accesoe']='1';
						$_SESSION['INGRESO']['modulo'][$j]=$permiso[$i]['Modulo'];
						//echo ' per '.$permiso[$i]['Modulo'].' '.$_SESSION['INGRESO']['modulo'][$j].' ind= '.$i.'<br>';
						$j++;
					}
				}
			}
			sqlsrv_close( $this->dbs );
		}
		//die();
        return $permiso;
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

	function IngClaveCredenciales($usuario){
		$sql = "SELECT Nombre_Completo
		FROM Accesos
		WHERE Usuario = '".$usuario."';";
		$datos = $this->db->datos($sql);
		return $datos;
	}


	function IngClave($parametros)
	{
    $ClaveGeneral = '';
	  $IngClaves_Caption  = '';
		$sql = "SELECT * 
		FROM Accesos
		WHERE Usuario = '".$parametros['tipo']."' ";
		
		$datos = $this->db->datos($sql);
		if(count($datos)>0)
		{
			 $ClaveGeneral = $datos[0]["Clave"];
	   	 $IngClaves_Caption = $datos[0]["Nombre_Completo"];
		}
	   return array('clave'=>$ClaveGeneral,'nombre'=>$IngClaves_Caption);
	}

	function IngClave_MYSQL($parametros)
	{

    $ClaveGeneral = '';
	  $IngClaves_Caption  = '';		
		// print_r($parametros);die();
		if($parametros['buscaren']=='MYSQL'){
				$sql = "SELECT * 
				FROM acceso_usuarios
				WHERE Usuario = '".$parametros['tipo']."' ";
				// print_r($sql);die();
				$datos = $this->db->datos($sql,'MY SQL');
		}else
		{
				$sql = "SELECT * 
				FROM Accesos
				WHERE Usuario = '".$parametros['tipo']."' ";
				// print_r($sql);die();
				$datos = $this->db->datos($sql);
		}
		if(count($datos)>0)
		{
			if($parametros['buscaren']=='MYSQL')
			{
			 	$ClaveGeneral = $datos[0]["Clave"];
				$IngClaves_Caption = $datos[0]["Nombre_Usuario"];
			}else
			{
				$ClaveGeneral = $datos[0]["Clave"];
				$IngClaves_Caption = $datos[0]["Usuario"];
			}
		}

		// print_r($datos);die();
	   return array('clave'=>$ClaveGeneral,'nombre'=>$IngClaves_Caption);
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

	function datos_usuario_mysql($usuario=false,$entidad=false,$email=false){

			$sql = "SELECT * 
		          FROM acceso_usuarios AU
		          INNER JOIN acceso_empresas AE ON AU.CI_NIC = AE.CI_NIC 
		          WHERE 1=1 ";
		          if($usuario)
		          {
		          	$sql.=" AND AU.Usuario = '".$usuario."' ";
		          }
		          if($entidad)
		          {
		          	$sql.=" AND AE.ID_Empresa = '".$entidad."' ";
		          }
		          if($email)
		          {
		          	$sql.=" AND AU.Email = '".$email."'";
		          }
		          $sql.=" GROUP BY Item";
		 // print_r($sql);die();
	    $datos = $this->db->datos($sql,$tipo='MY SQL');
	    return $datos;
	    // print_r($datos);die();
	}

	function Empresa_data($ci_ruc)
   {  
   	 //datos de empresa en mysql
      $dataEmp = $this->empresa_cartera($ci_ruc,$ID_Entidad=false); 
      //busca las credenciales SMTP de la empresa 
      $sql = "SELECT * FROM Empresas where Item='".$dataEmp[0]['Item']."'";
      $datos = $this->db->consulta_datos_db_sql_terceros($sql,$dataEmp[0]['IP_VPN_RUTA'],$dataEmp[0]['Usuario_DB'],$dataEmp[0]['Contrasena_DB'],$dataEmp[0]['Base_Datos'],$dataEmp[0]['Puerto']);
	   return $datos;
   }


  function entidades_usuario($ci_nic)
	{
		$sql ="SELECT AU.Nombre_Usuario,AU.Usuario,AU.Clave, AU.Email, E.Nombre_Entidad, E.RUC_CI_NIC As Codigo_Entidad
				FROM acceso_empresas AS AE,acceso_usuarios AS AU, entidad AS E
				WHERE AU.CI_NIC ='".$ci_nic."'
				AND AE.ID_Empresa = E.ID_Empresa 
				AND AE.CI_NIC = AU.CI_NIC
				GROUP BY AU.Nombre_Usuario,AU.Email, E.Nombre_Entidad, E.RUC_CI_NIC,AU.Usuario,AU.Clave
				ORDER BY E.Nombre_Entidad ";
		$resp=$this->db->datos($sql,'MY SQL');
		$datos=array();
		foreach ($resp as $key => $value) {
		
				$datos[]=array('id'=>$value['Codigo_Entidad'],'text'=>$value['Nombre_Entidad'],'RUC'=>$value['Codigo_Entidad'],'Usuario'=>$value['Usuario'],'Clave'=>$value['Clave'],'Email'=>$value['Email'],'Nombre_Usuario'=>$value['Nombre_Usuario']);				
		}		
	    return $datos;
	}

	function editar_foto($img)
	{
		$_SESSION['INGRESO']['Foto'] = $img;
		$sql = "UPDATE acceso_usuarios SET Foto='".$img."' WHERE CI_NIC='".$_SESSION['INGRESO']['CodigoU']."'";
		return $this->db->String_Sql($sql, 'MY SQL');
	}
}

?>