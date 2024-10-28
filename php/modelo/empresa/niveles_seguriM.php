<?php 
include(dirname(__DIR__,2).'/db/variables_globales.php');//
include(dirname(__DIR__,2).'/funciones/funciones.php');
// require_once(dirname(__DIR__)."/db/db.php");
require_once(dirname(__DIR__,2)."/db/db1.php");
/**
 * 
 */
class niveles_seguriM
{
	private $conn ;
	private $db;
	function __construct()
	{
	   $this->conn = cone_ajax();
	   $this->db = new db();
	   // $this->dbs=Conectar::conexionSQL();
	}

	function modulos_todo()
	{
		$sql="SELECT * 
		    FROM modulos 
		    WHERE modulo <> '".G_NINGUNO."' and modulo <> 'VS'
		    AND link<>'.' AND icono<>'.'
		    ORDER BY aplicacion "; 
		    $datos=[];
		 $fila = $this->db->datos($sql,'MY SQL');
		 // print_r($sql);die();
		 foreach ($fila as $key => $value) {
		 	// print_r($value);die();
				$datos[]=['modulo'=>$value['modulo'],'aplicacion'=>$value['aplicacion']];				
			}

	      return $datos;
	}

	function entidades($valor)
	{
		$sql ="SELECT Nombre_Entidad,ID_Empresa,RUC_CI_NIC FROM entidad  WHERE RUC_CI_NIC <> '.' AND Nombre_Entidad LIKE '%".$valor."%' 
		    ORDER BY Nombre_Entidad";
		$enti=$this->db->datos($sql,'MY SQL');

		// print_r($enti);die();
		$datos[] = array();
		foreach ($enti as $key => $value) {
			$datos[]=['id'=>$value['ID_Empresa'],'text'=>$value['Nombre_Entidad'],'RUC'=>$value['RUC_CI_NIC'],'data'=>$value];				
		}		
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

	function entidades_usuarios($ci_nic)
	{
		$sql ="SELECT AU.Nombre_Usuario,AU.Usuario,AU.Clave, AU.CI_NIC ,AU.Email, E.Nombre_Entidad, E.RUC_CI_NIC As Codigo_Entidad
				FROM acceso_empresas AS AE,acceso_usuarios AS AU, entidad AS E
				WHERE AE.ID_Empresa ='".$ci_nic."'
				AND AE.ID_Empresa = E.ID_Empresa 
				AND AE.CI_NIC = AU.CI_NIC
				GROUP BY AU.Nombre_Usuario,AU.Email, E.Nombre_Entidad, E.RUC_CI_NIC,AU.Usuario,AU.Clave
				ORDER BY E.Nombre_Entidad ";
		$datos=array();		
		$resp=$this->db->datos($sql,'MY SQL');
		foreach ($resp as $key => $value) {
				$datos[]=array('id'=>$value['Codigo_Entidad'],'text'=>$value['Nombre_Entidad'],'RUC'=>$value['Codigo_Entidad'],'Usuario'=>$value['Usuario'],'Clave'=>$value['Clave'],'Email'=>$value['Email'],'CI_NIC'=>$value['CI_NIC'], 'Nombre_Usuario'=>$value['Nombre_Usuario']);			
		}
	    return $datos;
	}

	function empresas($entidad)
	{
		$sql="SELECT  ID,Empresa,Item,IP_VPN_RUTA,Base_Datos,Usuario_DB,Contrasena_DB,Tipo_Base,Puerto,RUC_CI_NIC,ID_Empresa  FROM lista_empresas WHERE ID_empresa = ".$entidad." AND Item <> '".G_NINGUNO."' ORDER BY Empresa ASC";
		// print_r($sql);die();
		$resp = $this->db->datos($sql,'MY SQL');
		  $datos=array();
		foreach ($resp as $key => $value) {
			$server = 1;
			if($value['IP_VPN_RUTA']=='.'){	$server = 0;}
			// else{
			// $re = $this->db->modulos_sql_server($value['IP_VPN_RUTA'],$value['Usuario_DB'],$value['Contrasena_DB'],$value['Base_Datos'],$value['Puerto']);
			// 	if($re==-1)
			// 	{
			// 		$server = 2;
			// 	}
			// }
				//$datos[]=['id'=>utf8_encode($filas['Item']),'text'=>utf8_encode($filas['Empresa'])];
				$datos[]=array('id'=>$value['Item'],'text'=>$value['Empresa'],'dbSQLSERVER'=>$server,'CI_RUC'=>$value['RUC_CI_NIC'],'ID_Empresa'=>$value['ID_Empresa']);			
		 }
	      return $datos;
	}
	function empresas_datos($entidad,$Item)
	{
		$sql="SELECT  ID,Empresa,Item,IP_VPN_RUTA,Base_Datos,Usuario_DB,Contrasena_DB,Tipo_Base,Puerto,RUC_CI_NIC   FROM lista_empresas WHERE ID_Empresa=".$entidad." AND Item = '".$Item."' AND Item <> '".G_NINGUNO."' ORDER BY Empresa";
		// print_r($sql);die();
		$resp = $this->db->datos($sql,'MY SQL');
		// print_r($sql);die();
		  $datos=[];
		foreach ($resp as $key => $value) {
		
					$datos[]=['id'=>$value['ID'],'text'=>$value['Empresa'],'host'=>$value['IP_VPN_RUTA'],'usu'=>$value['Usuario_DB'],'pass'=>$value['Contrasena_DB'],'base'=>$value['Base_Datos'],'Puerto'=>$value['Puerto'],'Item'=>$value['Item'],'RUC'=>$value['RUC_CI_NIC']];				
		 }

	      return $datos;
	}
	function usuarios($entidad,$query)
	{
		$sql = "SELECT  ID,CI_NIC,Nombre_Usuario,Usuario,Clave,Email 
		FROM acceso_usuarios 
		WHERE SUBSTRING(CI_NIC,1,6)  <> 'ACCESO' 
		AND  (CI_NIC LIKE '%".$query."%' OR Nombre_Usuario LIKE '%".$query."%' )";
		if($entidad)
		{
			$sql.="AND ID_Empresa='".$entidad."'";
		}
		 $datos[]=array('id'=>'0','text'=>'TODOS','CI'=>'0','usuario'=>'TODOS','clave'=>'0');
		 // print_r($sql);die();
		 $resp = $this->db->datos($sql,'MY SQL');
		foreach ($resp as $key => $value) {
		
			$datos[]=array('id'=>$value['CI_NIC'],'text'=>$value['Nombre_Usuario'],'CI'=>$value['CI_NIC'],'usuario'=>$value['Usuario'],'clave'=>$value['Clave'],$value['Email'],'data'=>$value);					
		 }

	      return $datos;


	}

	function acceso_empresas($entidad,$empresas,$usuario)
	{
		$sql = "SELECT * FROM acceso_empresas WHERE  ID_Empresa = ".$entidad." AND Item='".$empresas."' AND CI_NIC = '".$usuario."'";
		$resp = $this->db->datos($sql,'MY SQL');
		 $datos=[];
		foreach ($resp as $key => $value) {
				$datos[]=array('id'=>$value['ID'],'Modulo'=>$value['Modulo'],'item'=>$value['Item']);				
		 }
	      return $datos;

	}
	function acceso_empresas_($entidad,$empresas,$usuario,$modulo=false)
	{
		$sql = "SELECT * FROM acceso_empresas WHERE  ID_Empresa = ".$entidad." AND Item='".$empresas."' AND CI_NIC = '".$usuario."'";
		if($modulo)
		{
			$sql.=" AND Modulo='".$modulo."'";
		}
		 $datos=[];
		 // print_r($sql);
		 $resp = $this->db->datos($sql,'MY SQL');
		 foreach ($resp as $key => $value) {
		 		$datos[]=array('id'=>$value['ID'],'Modulo'=>$value['Modulo'],'item'=>$value['Item']);				
		 }
	      return $datos;

	}
	function datos_usuario($entidad,$usuario)
	{
		$sql = "SELECT CI_NIC,Usuario,Clave,Nivel_1 as 'n1',Nivel_2 as 'n2',Nivel_3 as 'n3',Nivel_4 as 'n4',Nivel_5 as 'n5',Nivel_6 as 'n6',Nivel_7 as 'n7',Supervisor,Cod_Ejec,Email,Serie_FA FROM acceso_usuarios WHERE  CI_NIC = '".$usuario."'";
        $resp = $this->db->datos($sql,'MY SQL');
		// // print_r($sql);die();
		//  $datos=array();
		//  if($cid)
		//  {
		//  	$consulta=$cid->query($sql) or die($cid->error);
		//  	while($filas=$consulta->fetch_assoc())
		// 	{
		// 		$datos =$filas;			
		// 	}
		//  }
		 // print_r($datos);die();
	      return $resp;

	}

	function actualizar_correo($correo,$ci_nic){
		$sql = "UPDATE acceso_usuarios set Email = '".$correo ."' WHERE CI_NIC = '".$ci_nic."'";
		$resp = $this->db->String_Sql($sql,'MY SQL');
	}

	function actualizar_datos_basicos($id,$parametros){
		$sql = "UPDATE acceso_usuarios SET  Usuario = '".$parametros['usu']."',Clave = '".$parametros['cla']."',Email='".$parametros['ema']."',Nombre_Usuario='".$parametros['nom']."',CI_NIC = '".$parametros['ced']."' WHERE ID = '".$id."';";
		$resp = $this->db->String_Sql($sql,'MY SQL');
	}

	function guardar_acceso_empresa($modulos,$entidad,$empresas,$usuario)
	{	
	    // $delet = $this->delete_modulos($entidad,$empresas,$usuario);
	    // if($delet==1)
	    // {
	    $regis = $this->acceso_empresas_($entidad,$empresas,$usuario,$modulos);
	    $modulo = explode(',',$modulos);
	    $valor = '';
	   	$valor_sql = '';
	    $existe = 0;
	    if(count($regis)==0)
	    {
	  		$sql = "INSERT INTO acceso_empresas (ID_Empresa,CI_NIC,Modulo,item,Pagina) VALUES ('".$entidad."','".$usuario."','".$modulos."','".$empresas."','.')";
	  		$r = $this->db->String_Sql($sql,'MY SQL');
	  	   return $r;
	    }
	// }
	}

	function guardar_acceso_empresa_mysql($modulos,$entidad,$empresas,$usuario)
	{	
	    
	  	$sql = "INSERT INTO acceso_empresas (ID_Empresa,CI_NIC,Modulo,item,Pagina) VALUES ('".$entidad."','".$usuario."','".$modulos."','".$empresas."','.')";
	  		$r = $this->db->String_Sql($sql,'MYSQL');
	  	   return $r;
	}

	function update_acceso_usuario($niveles,$usuario,$clave,$entidad,$CI_NIC,$email,$serie)
	{
		// print_r($niveles);die();
	   $sql = "UPDATE acceso_usuarios SET TODOS = 1, Nivel_1 =".$niveles['1'].", Nivel_2 =".$niveles['2'].", Nivel_3 =".$niveles['3'].", Nivel_4 =".$niveles['4'].",Nivel_5 =".$niveles['5'].", Nivel_6=".$niveles['6'].", Nivel_7=".$niveles['7'].", Supervisor = ".$niveles['super'].", Usuario = '".$usuario."',Clave = '".$clave."',Email='".$email."',Serie_FA='".$serie."',TODOS=1 WHERE CI_NIC = '".$CI_NIC."';";
	  return $this->db->String_Sql($sql,'MY SQL');

	}
	function delete_modulos($entidad,$empresas=false,$usuario="",$modulo=false)
	{
		$sql = "DELETE FROM acceso_empresas WHERE  ID_Empresa = ".$entidad." ";
		$sql2 = "DELETE FROM Acceso_Empresa WHERE X='.' ";
		if($empresas)
		{
			$sql.=" AND Item='".$empresas."'";
			$sql2.=" AND Item='".$empresas."'";
		}
			$sql.=" AND CI_NIC = '".$usuario."'";
			$sql2.=" AND Codigo = '".$usuario."'";
		if($modulo)
		{
			 $sql.=" AND Modulo='".$modulo."'";
			 $sql2.=" AND Modulo='".$modulo."'";
		}
		// print_r($sql);die();
		$r =  $this->db->String_Sql($sql,'MY SQL');

		$conn_sql = $this->empresas_datos($entidad,$empresas);
  		if(count($conn_sql)>0)
  		{
  			$conn = $this->db->modulos_sql_server($conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
  			if($conn!=-1)
  			{  				
  				// print_r($sql2);die();
  				$re = $this->db->ejecutar_sql_terceros($sql2,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
  				// print_r($re);die();
  			}
  			
  		}


		return $r;

	}


	function delete_modulos_mysql($entidad,$empresas=false,$usuario="",$modulo=false)
	{
		$sql = "DELETE FROM acceso_empresas WHERE  ID_Empresa = ".$entidad." AND Pagina='.' ";
		if($empresas)
		{
			$sql.=" AND Item='".$empresas."'";
		}
		if($modulo)
		{
			 $sql.=" AND Modulo='".$modulo."'";
		}
		if($usuario)
		{
			 $sql.=" AND CI_NIC='".$usuario."'";
		}
		// print_r($sql);
		// die();
		$r =  $this->db->String_Sql($sql,'MY SQL');
		return $r;

	}


	function bloquear_usuario($entidad,$CI_NIC)
	{
	   $sql = "UPDATE acceso_usuarios SET TODOS=0 WHERE CI_NIC = '".$CI_NIC."';";
	   $parametros['ent'] = $entidad;
	   $parametros['ci'] = $CI_NIC;

	   // print_r($sql);die();
	   $r = $this->bloquear_cliente_SQLSERVER($parametros);
	   if($r==1)
	   {
	   	return  $this->db->String_Sql($sql,'MY SQL');
	   }else
	   {
	   	return $r;
	   }
	}

	function desbloquear_usuario($entidad,$CI_NIC)
	{
	   $sql = "UPDATE acceso_usuarios SET TODOS=1 WHERE CI_NIC = '".$CI_NIC."';"; 
	   $parametros['ent'] = $entidad;
	   $parametros['ci'] = $CI_NIC;
	   $this->desbloquear_cliente_SQLSERVER($parametros);
	  
	   return  $this->db->String_Sql($sql,'MY SQL');
	}

	function nuevo_usuario($parametros)
	{
	   $sql = "INSERT INTO acceso_usuarios (TODOS,Clave,Usuario,CI_NIC,ID_Empresa,Nombre_Usuario,Email) VALUES (1,'".$parametros['cla']."','".$parametros['usu']."','".$parametros['ced']."','".$parametros['ent']."','".$parametros['nom']."','".$parametros['ema']."')";
	   	// print_r($sql);die();
	   return $this->db->String_Sql($sql,'MY SQL');
	}

	function bloquear_cliente_SQLSERVER($parametros)
	{
		$registrado = true;
		$sql= "SELECT DISTINCT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto  FROM lista_empresas WHERE ID_Empresa = '".$parametros['ent']."' AND Base_Datos <>'.'";
		 $datos = $this->db->datos($sql,'MY SQL');
		 $insertado = false;
		// print_r($datos);die();
		 foreach ($datos as $key => $value) {
		 	if($value['Usuario_DB']=='sa')
		 	{

		 	// print_r($value);die();
		 	     $cid2 = $this->db->modulos_sql_server($value['IP_VPN_RUTA'],$value['Usuario_DB'],$value['Contrasena_DB'],$value['Base_Datos'],$value['Puerto']);

		 	     // print_r($value['IP_VPN_RUTA'].'-'.$value['Usuario_DB'].'-'.$value['Contrasena_DB'].'-'.$value['Base_Datos'].'-'.$value['Puerto']);
		 	     if($cid2!=-1)
		 	     {

		 	     $sql="UPDATE Accesos SET TODOS =0 WHERE Codigo = '".$parametros['ci']."'";
		 	     // print_r($sql);die();
		 	    $stmt = sqlsrv_query($cid2, $sql);
	            if($stmt === false)  
	        	    {  
	        	    	// print_r('fallo');die();
	        		    // echo "Error en consulta PA.\n";
	        		    // print_r($sql);die();
	        		    return -1;
		               die( print_r( sqlsrv_errors(), true));  
	                }else
	                {

	        	    	// print_r('si');die();
	            	    cerrarSQLSERVERFUN($cid2);
	            	    $insertado = true;
	                }     
	            }
	        }     
		 }
		 if($insertado == true)
		 {
		 	return 1;
		 }else
		 {
		 	return -1;
		 }

	}


	function desbloquear_cliente_SQLSERVER($parametros)
	{
		$registrado = true;
		$sql= "SELECT DISTINCT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto  FROM lista_empresas WHERE ID_Empresa = '".$parametros['ent']."' AND Base_Datos <>'.'";
		 $datos = $this->db->datos($sql,'MY SQL');
		 $insertado = false;
		// print_r($datos);die();
		 foreach ($datos as $key => $value) {
		 	if($value['Usuario_DB']=='sa')
		 	{

		 	// print_r($value);die();
		 	     $cid2 = $this->db->modulos_sql_server($value['IP_VPN_RUTA'],$value['Usuario_DB'],$value['Contrasena_DB'],$value['Base_Datos'],$value['Puerto']);

		 	     // print_r($value['IP_VPN_RUTA'].'-'.$value['Usuario_DB'].'-'.$value['Contrasena_DB'].'-'.$value['Base_Datos'].'-'.$value['Puerto']);die();
		 	     if($cid2!=-1)
		 	     {

		 	     $sql="UPDATE Accesos SET TODOS= 1 WHERE Codigo = '".$parametros['ci']."'";
		 	     // print_r($sql);die();
		 	    $stmt = sqlsrv_query($cid2, $sql);
	            if($stmt === false)  
	        	    {  
	        	    	// print_r('fallo');die();
	        		    // echo "Error en consulta PA.\n";
	        		    // print_r($sql);die();
	        		    return -1;
		               die( print_r( sqlsrv_errors(), true));  
	                }else
	                {

	        	    	// print_r('si');die();
	            	    cerrarSQLSERVERFUN($cid2);
	            	    $insertado = true;
	                }     
	            }
	        }     
		 }
		 if($insertado == true)
		 {
		 	return 1;
		 }else
		 {
		 	return -1;
		 }

	}


	function crear_como_cliente_SQLSERVER($parametros)
	{
		$registrado = true;
		$sql= "SELECT DISTINCT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto  FROM lista_empresas WHERE ID_Empresa = '".$parametros['ent']."' AND Base_Datos <>'.'";
		 // if($cid)
		 // {
		 // 	$consulta=$cid->query($sql) or die($cid->error);
		 // 	while($filas=$consulta->fetch_assoc())
			// {
			// 	$datos[] =$filas;			
			// }
		 // }
		 $datos = $this->db->datos($sql,'MY SQL');
		 $insertado = false;
		// print_r($datos);die();
		 foreach ($datos as $key => $value) {
		 	if($value['Usuario_DB']=='sa')
		 	{

		 	// print_r($value);die();
		 	     $cid2 = $this->db->modulos_sql_server($value['IP_VPN_RUTA'],$value['Usuario_DB'],$value['Contrasena_DB'],$value['Base_Datos'],$value['Puerto']);

		 	     // print_r($value['IP_VPN_RUTA'].'-'.$value['Usuario_DB'].'-'.$value['Contrasena_DB'].'-'.$value['Base_Datos'].'-'.$value['Puerto']);die();
		 	     if($cid2!=-1)
		 	     {

		 	     $sql = "INSERT INTO Clientes(T,FA,Codigo,Fecha,Cliente,TD,CI_RUC,FactM,Descuento,RISE,Especial)VALUES('N',0,'".$parametros['ced']."','".date('Y-m-d')."','".$parametros['nom']."','C','".$parametros['ced']."',0,0,0,0);";

		 	     $sql.="INSERT INTO Accesos (TODOS,Clave,Usuario,Codigo,Nombre_Completo,Nivel_1,Nivel_2,Nivel_3,Nivel_4,Nivel_5,Nivel_6,Nivel_7,Supervisor,EmailUsuario,Serie_FA) VALUES (1,'".$parametros['cla']."','".$parametros['usu']."','".$parametros['ced']."','".$parametros['nom']."','".$parametros['n1']."','".$parametros['n2']."','".$parametros['n3']."','".$parametros['n4']."','".$parametros['n5']."','".$parametros['n6']."','".$parametros['n7']."','".$parametros['super']."','".$parametros['email']."','".$parametros['serie']."')";
		 	     // print_r($sql);die();
		 	    $stmt = sqlsrv_query($cid2, $sql);
	            if($stmt === false)  
	        	    {  
	        	    	// print_r('fallo');die();
	        		    // echo "Error en consulta PA.\n";
	        		    // print_r($sql);die();
	        		    return -1;
		               die( print_r( sqlsrv_errors(), true));  
	                }else
	                {

	        	    	// print_r('si');die();
	            	    cerrarSQLSERVERFUN($cid2);
	            	    $insertado = true;
	                }     
	            }
	        }     
		 }
		 if($insertado == true)
		 {
		 	return 1;
		 }else
		 {
		 	return -1;
		 }

	}


	function existe_en_SQLSERVER($entidad,$id_empresa,$CI_usuario)
	{
		set_time_limit(0);
		$mensaje='';
		$sql= "SELECT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto,Empresa  FROM lista_empresas WHERE ID_Empresa = '".$entidad."' AND Item = '".$id_empresa."'";
		$datos = $this->db->datos($sql,'MY SQL');
		if(count($datos)>0)
		{
			// print_r($datos);die();
			$sql = "SELECT * FROM Accesos WHERE Codigo = '".$CI_usuario."'";
			// print_r($sql);die();
			$res = $this->db->consulta_datos_db_sql_terceros($sql,$datos[0]['IP_VPN_RUTA'],$datos[0]['Usuario_DB'],$datos[0]['Contrasena_DB'],$datos[0]['Base_Datos'],$datos[0]['Puerto']);
			if($res==-1)
			{
				//mensaje cuando las credencioales estan mal configuradas
				$mensaje.= 'Base de datos o credenciales SQL SERVER no correctas para:'.$datos[0]['Empresa'];
				return array('resp'=>-1,'mensaje'=>$mensaje);
			}else
			{
				// si las conexiones estan bien configuradas y ejecuta el sql ingresa aqui
				if(count($res)>0)
				{
					// si la consulta encuentra datos actualiza en sql server
					return array('resp'=>1,'mensaje'=>$mensaje);
		 	     	 	 

				}else
				{
					// si la consulta no encuentra datos lo crea en sql server
					return array('resp'=>-2,'mensaje'=>$mensaje);
				}
			}
		}

	}

	function existe_en_SQLSERVER_cliente($entidad,$id_empresa,$CI_usuario)
	{
		set_time_limit(0);
		$mensaje='';
		$sql= "SELECT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto,Empresa  FROM lista_empresas WHERE ID_Empresa = '".$entidad."' AND Item = '".$id_empresa."'";
		$datos = $this->db->datos($sql,'MY SQL');
		if(count($datos)>0)
		{
			// print_r($datos);die();
			$sql = "SELECT * FROM Clientes WHERE CI_RUC = '".$CI_usuario."'";
			$res = $this->db->consulta_datos_db_sql_terceros($sql,$datos[0]['IP_VPN_RUTA'],$datos[0]['Usuario_DB'],$datos[0]['Contrasena_DB'],$datos[0]['Base_Datos'],$datos[0]['Puerto']);
			if($res==-1)
			{
				//mensaje cuando las credencioales estan mal configuradas
				$mensaje.= 'Base de datos o credenciales SQL SERVER no correctas para:'.$datos[0]['Empresa'];
				return array('resp'=>-1,'mensaje'=>$mensaje);
			}else
			{
				// si las conexiones estan bien configuradas y ejecuta el sql ingresa aqui
				if(count($res)>0)
				{
					// si la consulta encuentra datos actualiza en sql server
					return array('resp'=>1,'mensaje'=>$mensaje);
		 	     	 	 

				}else
				{
					// si la consulta no encuentra datos lo crea en sql server
					return array('resp'=>-2,'mensaje'=>$mensaje);
				}
			}
		}

	}

	function eliminar_en_SQLSERVER_acceso_empresa($entidad,$id_empresa,$CI_usuario)
	{
		set_time_limit(0);
		$mensaje='';
		$sql= "SELECT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto,Empresa  FROM lista_empresas WHERE ID_Empresa = '".$entidad."' AND Item = '".$id_empresa."'";
		$datos = $this->db->datos($sql,'MY SQL');
		if(count($datos)>0)
		{
			$sql = "DELETE FROM Acceso_Empresa WHERE Codigo = '".$CI_usuario."' AND Item='".$id_empresa."'";
			// print_r($sql);die();
			$res = $this->db->consulta_datos_db_sql_terceros($sql,$datos[0]['IP_VPN_RUTA'],$datos[0]['Usuario_DB'],$datos[0]['Contrasena_DB'],$datos[0]['Base_Datos'],$datos[0]['Puerto']);
		}

	}

	function existe_en_SQLSERVER_acceso_empresa($entidad,$id_empresa,$CI_usuario,$modulo)
	{
		set_time_limit(0);
		$mensaje='';
		$sql= "SELECT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto,Empresa  FROM lista_empresas WHERE ID_Empresa = '".$entidad."' AND Item = '".$id_empresa."'";
		$datos = $this->db->datos($sql,'MY SQL');
		if(count($datos)>0)
		{
			// print_r($datos);die();
			$sql = "SELECT * FROM Acceso_Empresa WHERE Codigo = '".$CI_usuario."' AND Modulo = '".$modulo."' AND Item='".$id_empresa."'";

			// print_r($sql);die();
			$res = $this->db->consulta_datos_db_sql_terceros($sql,$datos[0]['IP_VPN_RUTA'],$datos[0]['Usuario_DB'],$datos[0]['Contrasena_DB'],$datos[0]['Base_Datos'],$datos[0]['Puerto']);
			if($res==-1)
			{
				//mensaje cuando las credencioales estan mal configuradas
				$mensaje.= 'Base de datos o credenciales SQL SERVER no correctas para:'.$datos[0]['Empresa'];
				return array('resp'=>-1,'mensaje'=>$mensaje);
			}else
			{
				// si las conexiones estan bien configuradas y ejecuta el sql ingresa aqui
				if(count($res)>0)
				{
					// si la consulta encuentra datos actualiza en sql server
					return array('resp'=>1,'mensaje'=>$mensaje);
		 	     	 	 

				}else
				{
					// si la consulta no encuentra datos lo crea en sql server
					return array('resp'=>-2,'mensaje'=>$mensaje);
				}
			}
		}

	}


	function actualizar_en_sql_tercero($entidad,$id_empresa,$CI_usuario,$parametros)
	{
		set_time_limit(0);
		$mensaje='';
		$sql= "SELECT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto,Empresa  FROM lista_empresas WHERE ID_Empresa = '".$entidad."' AND Item = '".$id_empresa."'";
		$datos = $this->db->datos($sql,'MY SQL');
		if(count($datos)>0)
		{
			$sql = "UPDATE Accesos SET Nivel_1 =".$parametros['n1'].", Nivel_2 =".$parametros['n2'].", Nivel_3 =".$parametros['n3'].", Nivel_4 =".$parametros['n4'].",Nivel_5 =".$parametros['n5'].", Nivel_6=".$parametros['n6'].", Nivel_7=".$parametros['n7'].", Supervisor = ".$parametros['super'].",Nombre_Completo='".$parametros['nombre']."', Usuario = '".$parametros['usuario']."',Clave = '".$parametros['pass']."',EmailUsuario='".$parametros['email']."',Serie_FA = '".$parametros['serie']."',TODOS=1 WHERE Codigo = '".$parametros['CI_usuario']."';";
		 	  $sql = str_replace('false',0, $sql);
		 	  $sql = str_replace('true',1, $sql);
		 	  // print_r($sql);die();
		 	  $r = $this->db->ejecutar_sql_terceros($sql,$datos[0]['IP_VPN_RUTA'],$datos[0]['Usuario_DB'],$datos[0]['Contrasena_DB'],$datos[0]['Base_Datos'],$datos[0]['Puerto']);
		 	  return $r;
		}else
		{
			return -2;
		}

	}

	function actualizar_cliente_en_sql_tercero($entidad,$id_empresa,$CI_usuario,$parametros)
	{
		set_time_limit(0);
		$mensaje='';
		$sql= "SELECT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto,Empresa  FROM lista_empresas WHERE ID_Empresa = '".$entidad."' AND Item = '".$id_empresa."'";
		$datos = $this->db->datos($sql,'MY SQL');
		if(count($datos)>0)
		{

			// ver que se puede actualizar 
			$r = 1;
			// $sql = "UPDATE Clientes SET T,FA,Codigo,Fecha,Cliente,TD,CI_RUC,FactM,Descuento,RISE,Especial WHERE CI_RUC = '".$parametros['CI_usuario']."';";
		 	//   $sql = str_replace('false',0, $sql);
		 	//   $sql = str_replace('true',1, $sql);

		 	//    $sql = "INSERT INTO Clientes(T,FA,Codigo,Fecha,Cliente,TD,CI_RUC,FactM,Descuento,RISE,Especial)VALUES('N',0,'".$parametros['CI_usuario']."','".date('Y-m-d')."','".$parametros['nombre']."','C','".$parametros['CI_usuario']."',0,0,0,0);";

		 	//   // print_r($sql);die();
		 	//   $r = $this->db->ejecutar_sql_terceros($sql,$datos[0]['IP_VPN_RUTA'],$datos[0]['Usuario_DB'],$datos[0]['Contrasena_DB'],$datos[0]['Base_Datos'],$datos[0]['Puerto']);
		 	  return $r;
		}else
		{
			return -2;
		}

	}


	function actualizar_acceso_empresa_en_sql_tercero($entidad,$id_empresa,$CI_usuario,$parametros)
	{
		set_time_limit(0);
		$mensaje='';
		$sql= "SELECT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto,Empresa  FROM lista_empresas WHERE ID_Empresa = '".$entidad."' AND Item = '".$id_empresa."'";
		$datos = $this->db->datos($sql,'MY SQL');
		if(count($datos)>0)
		{

			// ver que se puede actualizar 
			 $sql = "UPDATE Acceso_Empresa SET Codigo,Usuario,Clave,Nombre_Completo,T,FA,Codigo,Fecha,Cliente,TD,CI_RUC,FactM,Descuento,RISE,Especial WHERE CI_RUC = '".$parametros['CI_usuario']."';";
		 	//   $sql = str_replace('false',0, $sql);
		 	//   $sql = str_replace('true',1, $sql);

		 	
		 	 // print_r($sql);die();
		 	$r = $this->db->ejecutar_sql_terceros($sql,$datos[0]['IP_VPN_RUTA'],$datos[0]['Usuario_DB'],$datos[0]['Contrasena_DB'],$datos[0]['Base_Datos'],$datos[0]['Puerto']);
		 	  return $r;
		}else
		{
			return -2;
		}

	}

	function insertat_en_sql_terceros($entidad,$id_empresa,$CI_usuario,$parametros)
	{
		// print_r($parametros);die();
		set_time_limit(0);
		$mensaje='';
		$sql= "SELECT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto,Empresa  FROM lista_empresas WHERE ID_Empresa = '".$entidad."' AND Item = '".$id_empresa."'";
		$datos = $this->db->datos($sql,'MY SQL');
		if(count($datos)>0)
		{
			
			$sql="INSERT INTO Accesos (TODOS,Clave,Usuario,Codigo,Nombre_Completo,Nivel_1,Nivel_2,Nivel_3,Nivel_4,Nivel_5,Nivel_6,Nivel_7,Supervisor,EmailUsuario,Serie_FA,P_Com,OK,X,Primaria,Secundaria,Bachillerato,Impresora_Defecto,Papel_Impresora,Impresora_Defecto_2,Papel_Impresora_2,CodBod,Cod_Ejec,Cuota_Venta) VALUES (1,'".$parametros['pass']."','".$parametros['usuario']."','".$parametros['CI_usuario']."','".$parametros['nombre']."','".$parametros['n1']."','".$parametros['n2']."','".$parametros['n3']."','".$parametros['n4']."','".$parametros['n5']."','".$parametros['n6']."','".$parametros['n7']."','".$parametros['super']."','".$parametros['email']."','".$parametros['serie']."',0,0,'.',0,0,0,'.','.','.','.','.','.','.')";

				// print_r($sql);die();
			$r = $this->db->ejecutar_sql_terceros($sql,$datos[0]['IP_VPN_RUTA'],$datos[0]['Usuario_DB'],$datos[0]['Contrasena_DB'],$datos[0]['Base_Datos'],$datos[0]['Puerto']);
		 	  return $r;
		}else
		{
			return -2;
		}

	}

	function insertar_cliente_en_sql_terceros($entidad,$id_empresa,$CI_usuario,$parametros)
	{
		// print_r($parametros);die();
		set_time_limit(0);
		$mensaje='';
		$sql= "SELECT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto,Empresa  FROM lista_empresas WHERE ID_Empresa = '".$entidad."' AND Item = '".$id_empresa."'";
		$datos = $this->db->datos($sql,'MY SQL');
		if(count($datos)>0)
		{
			 $sql = "INSERT INTO Clientes(T,FA,Codigo,Fecha,Cliente,TD,CI_RUC,FactM,Descuento,RISE,Especial)VALUES('N',0,'".$parametros['CI_usuario']."','".date('Y-m-d')."','".$parametros['nombre']."','C','".$parametros['CI_usuario']."',0,0,0,0);";

				// print_r($sql);die();
			$r = $this->db->ejecutar_sql_terceros($sql,$datos[0]['IP_VPN_RUTA'],$datos[0]['Usuario_DB'],$datos[0]['Contrasena_DB'],$datos[0]['Base_Datos'],$datos[0]['Puerto']);
		 	  return $r;
		}else
		{
			return -2;
		}

	}

	function insertar_acceso_en_sql_terceros($entidad,$id_empresa,$CI_usuario,$modulo)
	{
		// print_r($parametros);die();
		set_time_limit(0);
		$mensaje='';
		$sql= "SELECT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto,Empresa  FROM lista_empresas WHERE ID_Empresa = '".$entidad."' AND Item = '".$id_empresa."'";
		$datos = $this->db->datos($sql,'MY SQL');
		if(count($datos)>0)
		{
			 $sql = "INSERT INTO Acceso_Empresa (Modulo,Item,Codigo,X)VALUES('".$modulo."','".$id_empresa."','".$CI_usuario."','.');";
				// print_r($sql);die();
			$r = $this->db->ejecutar_sql_terceros($sql,$datos[0]['IP_VPN_RUTA'],$datos[0]['Usuario_DB'],$datos[0]['Contrasena_DB'],$datos[0]['Base_Datos'],$datos[0]['Puerto']);
		 	  return $r;
		}else
		{
			return -2;
		}

	}




	//************** se piensa borrar *********** // 
	function existe_en_SQLSERVER2($parametros)
	{
        $registrado = true;
		$sql= "SELECT DISTINCT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto,Empresa  FROM lista_empresas WHERE ID_Empresa = '".$parametros['entidad']."' AND Base_Datos <>'.'";
		 // if($cid)
		 // {
		 // 	$consulta=$cid->query($sql) or die($cid->error);
		 // 	while($filas=$consulta->fetch_assoc())
			// {
			// 	$datos[] =$filas;			
			// }
		 // }
		 $datos = $this->db->datos($sql,'MY SQL');
		 $insertado = -1;
		// print_r($datos);die();
		 $mensaje = '';
		 foreach ($datos as $key => $value) {
		 	if($value['Usuario_DB']=='sa')
		 	{

		 	// print_r($value);die();
		 	     $cid2 = $this->db->modulos_sql_server($value['IP_VPN_RUTA'],$value['Usuario_DB'],$value['Contrasena_DB'],$value['Base_Datos'],$value['Puerto']);
		 	     // print_r($cid2);
		 	     //die();
		 	     if($cid2!=-1)
		 	     {

		 	     $sql = "SELECT * FROM Accesos WHERE Codigo = '".$parametros['CI_usuario']."'";
		 	     // print_r($sql);die();
		 	     $stmt = sqlsrv_query($cid2, $sql);
		 	     $result = array();	
		 	     if($stmt===false)
		 	     {
		 	     	// print_r('fallo');die();
		 	     	return -2;
		 	     }else{

		 	     	//print_r('consulto');
		 	     	//die();
		 	        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
		 	     	   {
		 	     		   $result[] = $row;
		 	     	   }

		 	     	// print_r($result);die();
		 	     	 if(count($result)>0)
		 	     	 {

		 	     	// print_r('existe');die();
		 	     	 	$sql = "UPDATE Accesos SET Nivel_1 =".$parametros['n1'].", Nivel_2 =".$parametros['n2'].", Nivel_3 =".$parametros['n3'].", Nivel_4 =".$parametros['n4'].",Nivel_5 =".$parametros['n5'].", Nivel_6=".$parametros['n6'].", Nivel_7=".$parametros['n7'].", Supervisor = ".$parametros['super'].", Usuario = '".$parametros['usuario']."',Clave = '".$parametros['pass']."',EmailUsuario='".$parametros['email']."',Serie_FA = '".$parametros['serie']."',TODOS=1 WHERE Codigo = '".$parametros['CI_usuario']."';";

		 	     	 	  $sql = str_replace('false',0, $sql);
		 	     	 	  $sql = str_replace('true',1, $sql);
		 	     	 	  // print_r($sql);die();
		 	     	 	  //se repite la conexion a terceros por que al utilizarla una vez no se puede utilizar la misma conexion
		 	     	 	  // print_r('expression');die();
		 	     	 	  $r = $this->db->ejecutar_sql_terceros($sql,$value['IP_VPN_RUTA'],$value['Usuario_DB'],$value['Contrasena_DB'],$value['Base_Datos'],$value['Puerto']);

		 	     	 	  // print_r($r);die();

		 	     	 	  if($r==1)
		 	     	 	  {
		 	     	 	  	// print_r('s');die();
		 	     	 	  	$insertado = 1;
		 	     	 	  }else
		 	     	 	  {
		 	     	 	  	// echo "No se ueo actualizar en la base de terceros la sentencia sql.\n";  
		                    //return '';
		                    //die( print_r( sqlsrv_errors(), true));  
		 	     	 	  }		 	     	 	    
		 	     	 }else
		 	     	 {
		 	     	 	$parametros_ing = array();
		 	            $parametros_ing['ent']	  = $parametros['entidad'];     	 	 
		 	            $parametros_ing['cla'] = $parametros['pass'];
		 	            $parametros_ing['usu'] = $parametros['usuario'];
		 	            $parametros_ing['ced'] = $parametros['CI_usuario'];
		 	            $parametros_ing['nom'] = $parametros['nombre'];
		 	            $parametros_ing['n1'] = $parametros['n1'];
		 	            $parametros_ing['n2'] = $parametros['n2'];
		 	            $parametros_ing['n3'] = $parametros['n3'];
		 	            $parametros_ing['n4'] = $parametros['n4'];
		 	            $parametros_ing['n5'] = $parametros['n5'];
		 	            $parametros_ing['n6'] = $parametros['n6'];
		 	            $parametros_ing['n7'] = $parametros['n7'];
		 	            $parametros_ing['super'] = $parametros['super'];
		 	            $parametros_ing['email'] = $parametros['email'];
		 	            $parametros_ing['serie'] = $parametros['serie'];
		 	            // print_r($parametros_ing);die();
		 	     	 	 if($this->crear_como_cliente_SQLSERVER($parametros_ing)==1)
		 	     	 	 {
		 	     	 	 	$insertado = 1;
		 	     	 	 }
		 	     	 }
		 	     }
		 	  }
	        }     
		 }

		 // print_r($insertado);die();

		 return array('respuesta'=> $insertado,'mensaje'=>$mensaje);
		 // if($insertado == true)
		 // {
		 // 	return 1;
		 // }else
		 // {
		 // 	return -1;
		 // }


	}

	function usuario_existente($usuario,$clave,$entidad)
	{
	   $sql = "SELECT * FROM acceso_usuarios WHERE Usuario = '".$usuario."' AND Clave = '".$clave."' AND ID_Empresa = '".$entidad."'";
	   $res = $this->db->existe_registro($sql,'MY SQL');
	   if($res!=1)
	   {
	   	return -1;
	   }else{ return $resp; }
	}

	function usuario_existente_datos($id=false,$usuario=false,$clave=false,$entidad=false)
	{
	   $sql = "SELECT * FROM acceso_usuarios WHERE 1=1 ";
	   if($usuario)
	   	{
	   		$sql.=" AND Usuario = '".$usuario."' ";
	   	}
	   	if($clave)
	   	{
	   		$sql.=" AND Clave = '".$clave."' ";
	   	}
	   	if($entidad)
	   	{
	   		$sql.=" AND ID_Empresa = '".$entidad."'";
	   	}
	   	if($id)
	   	{
	   		$sql.=" AND ID = '".$id."'";
	   	}
	   $resp = $this->db->datos($sql,'MY SQL');
	   return $resp; 
	}



	function buscar_ruc($ruc)
	{
	   $sql = "SELECT Item,Empresa as 'emp',L.RUC_CI_NIC as 'ruc',Estado,L.ID_Empresa,Nombre_Entidad as 'Entidad',E.RUC_CI_NIC as 'Ruc_en' FROM lista_empresas L
	          LEFT JOIN entidad E ON  L.ID_Empresa = E.ID_Empresa
	          WHERE L.RUC_CI_NIC = '".$ruc."'";
       // $sql2 = "SELECT Item,Empresa as 'emp',RUC_CI_NIC as 'ruc',Estado FROM lista_empresas WHERE RUC_CI_NIC = '".$ruc."'";
	   $entidad = $this->db->datos($sql,'MY SQL');
		 return $entidad;

	}

	function accesos_modulos($entidad,$usuario,$item=false,$modulo=false)
	{

		$sql="SELECT Item,Modulo FROM acceso_empresas WHERE ID_Empresa = '".$entidad."' AND CI_NIC = '".$usuario."'";
		if($item)
		{
			$sql.=" AND Item = '".$item."'";
		}
		if($modulo)
		{
			$sql.=" AND Modulo= '".$modulo."'";
		}
		$datos = $this->db->datos($sql,'MY SQL');
	      return $datos;
	}

	function item_empresas_usuarios($entidad,$usuario,$item=false,$modulo=false)
	{

		$sql="SELECT DISTINCT Item FROM acceso_empresas WHERE ID_Empresa = '".$entidad."' AND CI_NIC = '".$usuario."'";
		if($item)
		{
			$sql.=" AND Item = '".$item."'";
		}
		if($modulo)
		{
			$sql.=" AND Modulo= '".$modulo."'";
		}

		// print_r($sql);die();
		$datos = $this->db->datos($sql,'MY SQL');
	      return $datos;
	}


	function Empresa_data()
   {   			
	   $sql = "SELECT * FROM Empresas where Item='".$_SESSION['INGRESO']['item']."'";
	   $datos = $this->db->datos($sql);
	   return $datos;
   }
   function usuarios_registrados_entidad($entidad)
   {
   	$sql="SELECT DISTINCT AE.CI_NIC,Nombre_Usuario,Email FROM acceso_empresas AE
   	INNER JOIN acceso_usuarios AU ON AE.CI_NIC = AU.CI_NIC 
   	WHERE AE.ID_Empresa = '".$entidad."'";
   	$datos = $this->db->datos($sql,'MY SQL');
	 return $datos;
   }


	function todos_modulos($entidad,$item,$usuario)
	{
		$sql = "SELECT DISTINCT(aplicacion), AE.modulo
		FROM acceso_empresas  AE
		INNER JOIN modulos M ON AE.Modulo= M.modulo
		WHERE CI_NIC='".$usuario."' AND item = '".$item."' AND ID_EMPRESA = '".$entidad."'";
		// print_r($sql);die();
		return $this->db->datos($sql,'MYSQL');
	}

	function paginas($modulo)
	{
		$sql = "SELECT ID,CodMenu,descripcionMenu FROM menu_modulos WHERE codMenu like '".$modulo.".%' AND LENGTH(codMenu)>4 ORDER BY descripcionMenu ASC";
		return $this->db->datos($sql,'MYSQL');
	}

	function existe_acceso_pag($entidad,$usuario,$mod,$item,$pagina)
	{
		$sql = "SELECT * FROM acceso_empresas
				WHERE ID_Empresa = '".$entidad."' 
				AND CI_NIC = '".$usuario."' 
				AND Modulo = '".$mod."' 
				AND Item = '".$item."' 
				AND Pagina = '".$pagina."'";
		// print_r($sql);die();
		return $this->db->datos($sql,'MYSQL');

	}

	function add_accesos($tabla,$da)
	{
		$campos = '';
		$datos = '';
		foreach ($da as $key => $value) {
			$campos.= $value['campo'].',';
			if(isset($value['tipo']) && $value['tipo']=='string')
			{
				$datos.= "'".$value['dato']."',";			
			}else
			{
		    	$datos.= $value['dato'].',';	
		    }		
		}
		$campos = substr($campos,0,-1);
		$datos = substr($datos,0,-1);
		$sql='INSERT INTO '.$tabla.' ('.$campos.')VALUES('.$datos.')';
		// print_r($sql);die();
		return $this->db->String_Sql($sql,'MYSQL');
	}

	function delete_acceso($enti,$usu,$mod,$item,$pag)
	{
		$sql = "DELETE FROM acceso_empresas WHERE ID_Empresa='".$enti."' AND CI_NIC='".$usu."' AND Modulo = '".$mod."' AND Item='".$item."' AND Pagina = '".$pag."'";
		return $this->db->String_Sql($sql,'MYSQL');
	}


	function comprobar_conexion($host,$usu,$pass,$base,$Puerto)
	{
		return $this->db->modulos_sql_server($host,$usu,$pass,$base,$Puerto);
	}

	function ejecutar_sql_terceros($sql,$entidad,$empresas)
	{
		$conn_sql = $this->empresas_datos($entidad,$empresas);
		// print_r($conn_sql);die();
  		if(count($conn_sql)>0)
  		{
  			$conn = $this->db->modulos_sql_server($conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
  			if($conn!=-1)
  			{  				
  				// print_r($sql2);die();
  				$re = $this->db->ejecutar_sql_terceros($sql,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
  				return $re;
  				// print_r($re);die();
  			}
  			
  		}
	}
	function ejecutar_datos_terceros($sql,$entidad,$empresas)
	{
		$conn_sql = $this->empresas_datos($entidad,$empresas);
		// print_r($conn_sql);die();
  		if(count($conn_sql)>0)
  		{
  			$conn = $this->db->modulos_sql_server($conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
  			if($conn!=-1)
  			{  				
  				// print_r($sql2);die();
  				$re = $this->db->consulta_datos_db_sql_terceros($sql,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
  				return $re;
  				// print_r($re);die();
  			}
  			
  		}
	}

	
}
?>