<?php 

require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

/**
 * 
 */
class cambioeM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();
	}

	function ciudad($IDempresa)
	{
		$sql ="SELECT Ciudad
			  FROM lista_empresas
			  WHERE ID_Empresa = '".$IDempresa."' group by Ciudad";
	    return $this->db->datos($sql,'MYSQL');

	}

	function entidad($query=false,$IDempresa="",$ciudad=false)
	{

		if($ciudad)
		{
			$sql = "SELECT *
				  FROM lista_empresas
				  WHERE ID_Empresa = '".$IDempresa."' AND Ciudad='".$ciudad."' ";

		}else{
			
			$sql = "SELECT *
				  FROM lista_empresas
				  WHERE ID_Empresa = '".$IDempresa."' ";
		}
		if($query)
		{
			$sql.=" AND (RUC_CI_NIC LIKE '%".$query."%' OR Empresa LIKE '%".$query."%') ";
			// $sql.=" and CI_RUC+ +Empresa like '%".$query."%' ";
		}

		$sql.='ORDER BY Empresa;';		
		// print_r($sql);die();
		return $this->db->datos($sql,'MYSQL');
	}

	function datos_empresa($ID)
	{		
		$sql = "SELECT *
		  FROM lista_empresas
		  WHERE ID = '".$ID."';";
		return $this->db->datos($sql,'MYSQL');
	}
	function datos_empresa_sqlserver()
	{		
		$sql = "SELECT *
		  FROM lista_empresas
		  WHERE ID = '".$ID."';";
		return $this->db->datos($sql);
	}
	function estado()
	{
		$sql = 'SELECT Estado,Descripcion FROM lista_estados';
		return $this->db->datos($sql,'MYSQL');
	}

	function editar_datos_empresaMYSQL($parametros)
	{
		$img = '.';
		
		$sql = "UPDATE lista_empresas set 
		Estado='".$parametros['Estado']."',
		Mensaje='".$parametros['Mensaje']."',
		Fecha_CE='".$parametros['FechaCE']."' ,
		IP_VPN_RUTA='".$parametros['Servidor']."',
		Base_Datos='".$parametros['Base']."' ,
		Usuario_DB='".$parametros['Usuario']."',
		contrasena_DB='".$parametros['Clave']."' ,
		Tipo_Base='".$parametros['Motor']."',
	    Puerto='".$parametros['Puerto']."',
	    Fecha='".$parametros['FechaR']."',
	    Fecha_DB='".$parametros['FechaDB']."',
	    Fecha_P12='".$parametros['FechaP12']."', 
	    Tipo_Plan='".$parametros['Plan']."' ";
	    if(isset($parametros['ddl_img']) && $parametros['ddl_img']!='')
	    { 
	    	$logo = explode('.',$parametros['ddl_img']); $img = $logo[0];
	    	$sql.=",Logo_Tipo ='".$img."' ";
		}

		$sql.=" WHERE ID='".$parametros['empresas']."' ";	  

	    // print_r($sql);die();  
	    return $this->db->String_Sql($sql,'MYSQL');
	    // print_r($parametros);die();
	}

	function editar_catalogoLineas_empresa($parametros)
	{
		// buscamos datos d ela empresa por su id en mysql
		$em = $this->datos_empresa($parametros['empresas']);
	    if(count($em)>0)
	    {
	    	// validamos si tiene cadena de conexion 
	    	if($em[0]['IP_VPN_RUTA']!='.' && $em[0]['IP_VPN_RUTA']!='')
	    	{
	            $conn = $this->db->modulos_sql_server($em[0]['IP_VPN_RUTA'],$em[0]['Usuario_DB'],$em[0]['Contrasena_DB'],$em[0]['Base_Datos'],$em[0]['Puerto']);
	            // print_r($conn);die();
	            // validamos si se conecto 
	            if($conn!=-1)
	            {
	            	$fe =  date("Y-m-d",strtotime($parametros['FechaCE']."- 1 year"));
	            	$sql2 = "UPDATE Catalogo_Lineas 
		    		SET Vencimiento = '".$parametros['FechaCE']."',Fecha = '".$fe."' 
		    		WHERE Item = '".$em[0]['Item']."' 
		    		AND Periodo = '.'  
		    		AND TL <> 0 
		    		AND len(Autorizacion)>=13";

		    		$resp_catalogoLineas = $this->db->ejecutar_sql_terceros($sql2,$em[0]['IP_VPN_RUTA'],$em[0]['Usuario_DB'],$em[0]['Contrasena_DB'],$em[0]['Base_Datos'],$em[0]['Puerto']);
		    		return $resp_catalogoLineas ;
		    	}else
		    	{
		    		// tiene datos de conexion pero no hay conexion a la base de datos
		    		return -2;
		    	}
		    }else
		    {
		    	//sin datos de conexion
		    	return -3;
		    }
		}else
		{
			// no hay empresa con el id enviado en mysql
			return -4;
		}

	}

	function editar_datos_empresa($parametros)
	{


		// print_r($parametros);die();

		$img = '.';
		
	    $em = $this->datos_empresa($parametros['empresas']);
	    if(count($em)>0)
	    {
	    	if($em[0]['IP_VPN_RUTA']!='.' && $em[0]['IP_VPN_RUTA']!='')
	    	{
	            $conn = $this->db->modulos_sql_server($em[0]['IP_VPN_RUTA'],$em[0]['Usuario_DB'],$em[0]['Contrasena_DB'],$em[0]['Base_Datos'],$em[0]['Puerto']);
	            // print_r($conn);die();
	            if($conn!=-1)
	            {	            	
		    		$ambiente = 1;
		    		if($parametros['optionsRadios']=='option2')
		    		{
		    			$ambiente = 2;
		    		}
		    		$copia = 0;
		    		if(isset($parametros['rbl_copia']))
		    		{
		    			$copia = 1;
		    		}

		    		// print_r($parametros);die();

		    		$ASDAS = 0; $MFNV = 0; $MPVP = 0; $IRCF = 0; $IMR = 0; $IRIP = 0; $PDAC = 0; $RIAC = 0;
		    		if(isset($parametros['ASDAS'])){ $ASDAS = 1; }
		    		if(isset($parametros['MFNV'])){ $MFNV = 1; }
		    		if(isset($parametros['MPVP'])){ $MPVP = 1; }
		    		if(isset($parametros['IRCF'])){ $IRCF = 1; }
		    		if(isset($parametros['IMR'])){ $IMR = 1; }
		    		if(isset($parametros['IRIP'])){ $IRIP = 1; }
		    		if(isset($parametros['PDAC'])){ $PDAC = 1; }
		    		if(isset($parametros['RIAC'])){ $RIAC = 1; }

		    		$Autenti = 0; $SSL=0; $Secure=0;
		    		if(isset($parametros['Autenti'])){ $Autenti = 1; }
		    		if(isset($parametros['SSL'])){ $SSL = 1; }
		    		if(isset($parametros['Secure'])){ $Secure = 1; }


		    		$sql3 = "UPDATE Empresas SET Fecha_CE = '".$parametros['FechaCE']."',
		    		Estado = '".$parametros['Estado']."',
		    		Codigo_Contribuyente_Especial = '".$parametros['TxtContriEspecial']."',
		    		Web_SRI_Recepcion = '".$parametros['TxtWebSRIre']."',
		    		Web_SRI_Autorizado = '".$parametros['TxtWebSRIau']."',
		    		Ruta_Certificado = '".$parametros['TxtEXTP12']."',
		    		Clave_Certificado = '".$parametros['TxtContraExtP12']."',
		    		Email_Conexion = '".$parametros['TxtEmailGE']."',
		    		Email_Contraseña = '".$parametros['TxtContraEmailGE']."',
		    		Email_Conexion_CE = '".$parametros['TxtEmaiElect']."',
		    		Email_Contraseña_CE = '".$parametros['TxtContraEmaiElect']."',
		    		Email_Procesos = '".$parametros['TxtCopiaEmai']."',
		    		RUC_Operadora = '".$parametros['TxtRUCOpe']."',
		    		LeyendaFA = '".$parametros['txtLeyendaDocumen']."',
		    		LeyendaFAT = '".$parametros['txtLeyendaImpresora']."',
		    		Ambiente = '".$ambiente."',
		    		Email_CE_Copia = '".$copia."',
		    		Fecha_P12 = '".$parametros['FechaP12']."',
		    		Fecha= '".$parametros['FechaR']."',

		    		Empresa = '".$parametros['TxtEmpresa']."',
		    		Item = '".$em[0]['Item']."',
		    		Razon_Social = '".$parametros['TxtRazonSocial']."',
		    		Nombre_Comercial = '".$parametros['TxtNomComercial']."',
		    		RUC = '".$parametros['TxtRuc']."',
		    		Obligado_Conta = '".$parametros['ddl_obli']."',
		    		Gerente = '".$parametros['TxtRepresentanteLegal']."',
		    		CI_Representante = '".$parametros['TxtCI']."',
		    		CPais = '".$parametros['ddl_naciones']."',
		    		CProv = '".$parametros['prov']."',
		    		Ciudad = '".$parametros['ciu']."',
		    		Direccion = '".$parametros['TxtDirMatriz']."',
		    		Establecimientos = '".$parametros['TxtEsta']."',
		    		Telefono1 = '".$parametros['TxtTelefono']."',
		    		Telefono2 = '".$parametros['TxtTelefono2']."',
		    		FAX = '".$parametros['TxtFax']."',
		    		No_Patronal = '".$parametros['TxtNPatro']."',
		    		CodBanco = '".$parametros['TxtCodBanco']."',
		    		Tipo_Carga_Banco = '".$parametros['TxtTipoCar']."',
		    		Abreviatura = '".$parametros['TxtAbrevi']."',
		    		Email = '".$parametros['TxtEmailEmpre']."',
		    		Email_Contabilidad = '".$parametros['TxtEmailConta']."',
		    		Email_Respaldos = '".$parametros['TxtEmailRespa']."',
		    		Seguro = '".$parametros['TxtSegDes1']."',
		    		Seguro2 = '".$parametros['TxtSegDes2']."',
		    		SubDir = '".$parametros['TxtSubdir']."',
		    		Contador = '".$parametros['TxtNombConta']."',
		    		RUC_Contador = '".$parametros['TxtRucConta']."',
		    		Det_SubMod = '".$ASDAS."',
		    		Mod_Fact = '".$MFNV."',
		    		Mod_PVP = '".$MPVP."',
		    		Imp_Recibo_Caja = '".$IRCF."',
		    		Medio_Rol = '".$IMR."',
		    		Rol_2_Pagina = '".$IRIP."',
		    		Det_Comp = '".$PDAC."',
		    		Registrar_IVA = '".$RIAC."',
		    		smtp_Servidor = '".$parametros['TxtServidorSMTP']."',
		    		smtp_Puerto = '".$parametros['TxtPuerto']."',
		    		Dec_PVP = '".$parametros['TxtPVP']."',
		    		Dec_Costo = '".$parametros['TxtCOSTOS']."',
		    		Dec_IVA = '".$parametros['TxtIVA']."',
		    		Dec_Cant = '".$parametros['TxtCantidad']."',
		    		smtp_UseAuntentificacion = '".$Autenti."',
		    		smtp_SSL = '".$SSL."',
		    		smtp_Secure = '".$Secure."',
		    		Num_CD = '".$parametros['dm1']."',
		    		Num_CI = '".$parametros['dm2']."',
		    		Num_CE ='".$parametros['dm3']."',
		    		Num_ND ='".$parametros['dm4']."',
		    		Num_NC='".$parametros['dm5']."' ";
		    		if(isset($parametros['ddl_img']) && $parametros['ddl_img']!='')
		    		{ 
		    			$logo = explode('.',$parametros['ddl_img']); $img = $logo[0];
		    			$sql3.= ",Logo_Tipo='".$img."'";
		    		}
		    		
		    		$sql3.=" WHERE Item='".$em[0]['Item']."'";

		    		// print_r($sql3);die();
		    		// print_r($sql2);

	            	
	            	return $this->db->ejecutar_sql_terceros($sql3,$em[0]['IP_VPN_RUTA'],$em[0]['Usuario_DB'],$em[0]['Contrasena_DB'],$em[0]['Base_Datos'],$em[0]['Puerto']);

	            	// print_r($r);die();
	            }else
	            {
	            	// no se pudo conectar a la base de datos
	            	return -2;
	            }
	        }else
	        {
	        	// no hay datos de conexion a la base
	        	return -3;
	        }


	    }else{
	    	//no exister la empres que se busca en mysql
	    	return -4;
	    }
	}
	function mensaje_masivo($parametros)
	{		
		// print_r($parametros);die();
		$sql = "UPDATE lista_empresas set Mensaje='".$parametros['Mensaje']."'; ";
		return $this->db->String_Sql($sql,'MYSQL');
	}
	function mensaje_grupo($parametros)
	{		
		
		if($parametros['ciudad']=='')
		{
			 $sql = "UPDATE lista_empresas set Mensaje='".$parametros['Mensaje']."' WHERE ID_Empresa='".$parametros['entidad']."' ";
		}
		else
		{
			 $sql = "UPDATE lista_empresas set Mensaje='".$parametros['Mensaje']."' WHERE ID_Empresa='".$parametros['entidad']."'  AND Ciudad='".$parametros['ciudad']."'";
		}
		return $this->db->String_Sql($sql,'MYSQL');
	}
	function mensaje_indi($parametros)
	{
		$sql = "UPDATE lista_empresas set Mensaje='".$parametros['Mensaje']."' WHERE ID_Empresa='".$parametros['entidad']."' AND ID='".$parametros['empresas']."'";
		return $this->db->String_Sql($sql,'MYSQL');
	}
	function guardar_masivo($parametros)
	{
		$sql = "UPDATE lista_empresas set Fecha='".$parametros['FechaR']."' , Fecha_VPN='".$parametros['FechaV']."' , Fecha_CE='".$parametros['Fecha']."'  
		WHERE ID_Empresa='".$parametros['entidad']."'";

		$em = $this->entidad($query=false,$parametros['entidad'],$ciudad=false);
		// print_r($em);die();
		if(count($em)>0)
		{
			foreach ($em as $key => $value) {
				if($value['IP_VPN_RUTA']!='.' && $value['IP_VPN_RUTA']!='')
				{
					$conn = $this->db->modulos_sql_server($value['IP_VPN_RUTA'],$value['Usuario_DB'],$value['Contrasena_DB'],$value['Base_Datos'],$value['Puerto']);
		            // print_r($conn);die();
		            if($conn!=-1)
		            {
		            	$fe =  date("Y-m-d",strtotime($parametros['Fecha']."- 1 year"));
		            	$sql2 = "UPDATE Catalogo_Lineas 
			    		SET Vencimiento = '".$parametros['Fecha']."',Fecha = '".$fe."' 
			    		WHERE Item = '".$value['Item']."' AND Periodo = '.'  AND TL <> 0 AND len(Autorizacion)>=13";

			    		// print_r($sql2);die();
			    		$sql3 = "UPDATE Empresas SET Fecha_CE = '".$parametros['Fecha']."' WHERE Item='".$value['Item']."'";

		    		// print_r($sql3);

	            	    $r = $this->db->ejecutar_sql_terceros($sql2,$value['IP_VPN_RUTA'],$value['Usuario_DB'],$value['Contrasena_DB'],$value['Base_Datos'],$value['Puerto']);

		            	$r = $this->db->ejecutar_sql_terceros($sql3,$value['IP_VPN_RUTA'],$value['Usuario_DB'],$value['Contrasena_DB'],$value['Base_Datos'],$value['Puerto']);

		            	// print_r($r);die();
		            }
	        	}
			}
		}

		// print_r($em);die();


		return $this->db->String_Sql($sql,'MYSQL');
	}

	function asignar_clave($parametros)
	{
		$sql="Update Clientes set Clave = SUBSTRING(CI_RUC,1,10)where Codigo <> '.' and LEN(Clave)<=1";		
		// print_r($parametros);die();
		return $this->db->ejecutar_sql_terceros($sql,$parametros['Servidor'],$parametros['Usuario'],$parametros['Clave'],$parametros['Base'],$parametros['Puerto']);
	}

	function todos_modulos()
	{
		$sql = "SELECT modulo,aplicacion FROM modulos WHERE modulo <> '.' and modulo <> 'VS' ORDER BY aplicacion ASC";
		return $this->db->datos($sql,'MYSQL');
	}

	function paginas($modulo)
	{
		$sql = "SELECT ID,CodMenu,descripcionMenu FROM menu_modulos WHERE codMenu like '".$modulo.".%' AND LENGTH(codMenu)>4 ORDER BY descripcionMenu ASC";
		return $this->db->datos($sql,'MYSQL');
	}

	function datos_sql_terceros($parametros,$host,$user,$pass,$base,$Puerto)
	{
		// print_r($host);die();
		$sql = "SELECT * FROM Empresas WHERE Item = '".$parametros['Item']."' AND RUC = '".$parametros['RUC_CI_NIC']."'";
		// print_r($sql);die();
		return  $this->db->consulta_datos_db_sql_terceros($sql,$host,$user,$pass,$base,$Puerto);
	}

	function actualizar_firma($firma,$ruc,$em)
	{
		$sql = "UPDATE Empresas SET Ruta_Certificado = '".$firma."' WHERE RUC='".$ruc."'";

		// print_r($sql);die();
		return $this->db->ejecutar_sql_terceros($sql,$em[0]['IP_VPN_RUTA'],$em[0]['Usuario_DB'],$em[0]['Contrasena_DB'],$em[0]['Base_Datos'],$em[0]['Puerto']);
	}
	function actualizar_foto($foto,$ruc,$em)
	{
		$sql = "UPDATE Empresas SET Logo_Tipo = '".$foto."' WHERE RUC='".$ruc."'";
		// print_r($sql);die();
		return $this->db->ejecutar_sql_terceros($sql,$em[0]['IP_VPN_RUTA'],$em[0]['Usuario_DB'],$em[0]['Contrasena_DB'],$em[0]['Base_Datos'],$em[0]['Puerto']);
	}

	function tipoContribuyente($ruc)
	{
		$sql = "SELECT * FROM lista_tipo_contribuyente WHERE RUC = '".$ruc."'";
		return $this->db->datos($sql,'MYSQL');

	}
	function editar_tipo_contribuyente($parametros)
	{
		$op1 = 0;$op2 = 0;$op3 = 0;$op4 = 0;$op5 = 0;$op6 = 0;$op7 = 0;
		if(isset($parametros['rbl_ContEs']) && $parametros['rbl_ContEs'] =='on'){$op1 = 1;	}
		if(isset($parametros['rbl_rimpeE']) && $parametros['rbl_rimpeE'] =='on'){$op2 = 1;	}
		if(isset($parametros['rbl_rimpeP']) && $parametros['rbl_rimpeP'] =='on'){$op3 = 1;	}
		if(isset($parametros['rbl_regGen']) && $parametros['rbl_regGen'] =='on'){$op4 = 1;	}
		if(isset($parametros['rbl_rise']) && $parametros['rbl_rise'] =='true'){$op5 = 1;	}
		if(isset($parametros['rbl_micro2020']) && $parametros['rbl_micro2020'] =='on'){$op6 = 1;	}
		if(isset($parametros['rbl_micro2021']) && $parametros['rbl_micro2021'] =='on'){$op7 = 1;	}


		// print_r($parametros);die();

		$sql = "UPDATE lista_tipo_contribuyente 
		SET Zona='".$parametros['TxtZonaTipocontribuyente']."',
		Agente_Retencion = '".$parametros['TxtAgentetipoContribuyente']."' ,
		Contribuyente_Especial = ".$op1.",
		RIMPE_E = ".$op2.",
		RIMPE_P = ".$op3.",
		Regimen_General = ".$op4.",
		RISE = ".$op5.",
		Micro_2020 = ".$op6.",
		Micro_2021 = ".$op7."
		WHERE RUC = '".$parametros['TxtRuc']."'";

		// print_r($sql);die();
		return $this->db->String_Sql($sql,'MYSQL');
	}

	function ingresar_tipo_contribuyente($ruc)
	{
		$sql = "INSERT INTO lista_tipo_contribuyente (RUC,Zona,Agente_Retencion) values('".$ruc."','.','.')";
		return $this->db->String_Sql($sql,'MYSQL');
	}

	//FUNCIONES LINEAS CXC

	function empresas_datos($entidad,$Item)
	{
		$sql="SELECT  ID,Empresa,Item,IP_VPN_RUTA,Base_Datos,Usuario_DB,Contrasena_DB,Tipo_Base,Puerto   FROM lista_empresas WHERE ID_Empresa=".$entidad." AND Item = '".$Item."' AND Item <> '".G_NINGUNO."' ORDER BY Empresa";
		$resp = $this->db->datos($sql,'MY SQL');
		// print_r($sql);die();
		  $datos=[];
		foreach ($resp as $key => $value) {
		
					$datos[]=['id'=>$value['ID'],'text'=>$value['Empresa'],'host'=>$value['IP_VPN_RUTA'],'usu'=>$value['Usuario_DB'],'pass'=>$value['Contrasena_DB'],'base'=>$value['Base_Datos'],'Puerto'=>$value['Puerto'],'Item'=>$value['Item']];				
		 }

	      return $datos;
	}

	function Catalogo_Lineas($entidad, $item, $id=false)
	{
		
		// print_r($cuenta);die();
		$conn_sql = $this->empresas_datos($entidad,$item);
	   $sql="SELECT * 
        	FROM Catalogo_Lineas 
        	WHERE Item = '".$item."' 
        	AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        	AND TL <> 0 ";
        	if($id)
        	{
        		$sql.=" AND ID = '".$id."'"; 
        	}
       	// print_r($sql);die();
       	//return $this->db->datos($sql);
		return $this->db->consulta_datos_db_sql_terceros($sql,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
	}

	function nivel1($entidad, $item)
	{
		$conn_sql = $this->empresas_datos($entidad,$item);
		$sql= "SELECT DISTINCT Autorizacion FROM Catalogo_Lineas
			WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND  Item = '$item' 
			AND TL <> 0";
		//print_r($sql);die();
       	//return $this->db->datos($sql);
		return $this->db->consulta_datos_db_sql_terceros($sql,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);

	}

	function nivel2($entidad, $item, $autorizacion)
	{
		$conn_sql = $this->empresas_datos($entidad,$item);
		$sql= "SELECT DISTINCT Serie FROM Catalogo_Lineas
		WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND Item = '".$item."' 
		AND Autorizacion = '".$autorizacion."'
		AND TL <> 0";
       	//return $this->db->datos($sql);
		return $this->db->consulta_datos_db_sql_terceros($sql,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
	}

	function nivel3($entidad, $item, $autorizacion,$serie)
	{
		$conn_sql = $this->empresas_datos($entidad,$item);
		$sql="SELECT DISTINCT Fact FROM Catalogo_Lineas
			WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND Item = '".$item."' 
			AND Autorizacion = '".$autorizacion."'
			AND Serie = '".$serie."'
			AND TL <> 0";
       	//return $this->db->datos($sql);
		return $this->db->consulta_datos_db_sql_terceros($sql,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
	}

	function nivel4($entidad, $item, $autorizacion,$serie,$fact)
	{
		$conn_sql = $this->empresas_datos($entidad,$item);
		$sql="SELECT ID,Concepto FROM Catalogo_Lineas
			WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND Item = '".$item."' 
			AND Autorizacion = '".$autorizacion."'
			AND Serie = '".$serie."'
			AND TL <> 0
			AND Fact = '".$fact."'";
       	//return $this->db->datos($sql);
		return $this->db->consulta_datos_db_sql_terceros($sql,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
	}

	function validar_codigo($codigo, $item, $entidad)
	{
		$conn_sql = $this->empresas_datos($entidad,$item);
		$sql="SELECT *
      		FROM Catalogo_Lineas
      		WHERE Codigo = '".$codigo."'
      		AND Item = '".$item."' 
      		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
      		AND TL <> 0 ";      		
       	//return $this->db->datos($sql);
		//print_r($sql."\n");
		return $this->db->consulta_datos_db_sql_terceros($sql,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
	}

	function elimina_linea($Codigo, $item, $entidad)
	{
		$conn_sql = $this->empresas_datos($entidad,$item);
		$sql= "DELETE 
              FROM Catalogo_Lineas 
              WHERE Codigo = '".$Codigo."' 
              AND Item = '".$item."' 
              AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
              AND TL <> 0";
        //return $this->db->String_Sql($sql);
		//print_r($sql."\n");
		return $this->db->ejecutar_sql_terceros($sql,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
	}

	function facturas_formato($entidad, $item, $Codigo,$TxtNumSerieUno,$TxtNumSerieDos,$TxtNumAutor)
	{
		$conn_sql = $this->empresas_datos($entidad,$item);
		 $sql = "SELECT * 
         	FROM Facturas_Formatos 
         	WHERE Item = '".$item."' 
         	AND  Periodo = '".$_SESSION['INGRESO']['periodo']."' 
         	AND Cod_CxC = '".$Codigo."' 
         	AND Serie = '".$TxtNumSerieUno.$TxtNumSerieDos."' 
         	AND Autorizacion = '".$TxtNumAutor."' ";

       	//return $this->db->datos($sql);
		//print_r($sql."\n");
		return $this->db->consulta_datos_db_sql_terceros($sql,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
	}

	function NC($entidad, $item, $TxtNumSerieUno,$TxtNumSerieDos)
	{
		$conn_sql = $this->empresas_datos($entidad,$item);
		 $sql = "SELECT Periodo, Item, 'NC' As TC, Serie_NC As Serie_X, MAX(Secuencial_NC) As TC_No 
                 FROM Trans_Abonos 
                
                 GROUP BY Periodo, Item, Serie_NC 
                 ORDER BY Periodo, Item, Serie_NC ";
		//return $this->db->datos($sql);
		//print_r($sql."\n");
		return $this->db->consulta_datos_db_sql_terceros($sql,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
	}

	function GR($entidad, $item, $TxtNumSerieUno,$TxtNumSerieDos)

	{
		$conn_sql = $this->empresas_datos($entidad,$item);
		  $sql = "SELECT Periodo, Item, 'GR' As TC, Serie_GR As Serie_X, MAX(Remision) As TC_No 
                 FROM Facturas_Auxiliares 
                 WHERE Serie_GR = '".$TxtNumSerieUno.$TxtNumSerieDos."' 
                 GROUP BY Periodo, Item, Serie_GR 
                 ORDER BY Periodo, Item, Serie_GR ";
        //return $this->db->datos($sql);
		//print_r($sql."\n");
		return $this->db->consulta_datos_db_sql_terceros($sql,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
	}
	function FACTURAS($entidad, $item, $CTipo,$TxtNumSerieUno,$TxtNumSerieDos)
	{
		$conn_sql = $this->empresas_datos($entidad,$item);
		$sql = "SELECT Periodo, Item, TC, Serie As Serie_X, MAX(Factura) As TC_No
                 FROM Facturas 
                 WHERE TC = '".$CTipo."' 
                 AND Serie = '".$TxtNumSerieUno.$TxtNumSerieDos."' 
                 GROUP BY Periodo, Item, TC, Serie 
                 ORDER BY Periodo, Item, TC, Serie ";
        //return $this->db->datos($sql);
		//print_r($sql."\n");
		return $this->db->consulta_datos_db_sql_terceros($sql,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
	}

	function codigos($entidad, $empItem, $periodo,$item,$tc,$Serie_X)
	{
		$conn_sql = $this->empresas_datos($entidad,$empItem);
		$sql = "SELECT * 
           		FROM Codigos 
            	WHERE Periodo = '".$periodo."' 
            	AND Item = '".$item."' 
            	AND Concepto = '".$tc."_SERIE_".$Serie_X."'";

        //return $this->db->datos($sql);
		//print_r($sql."\n");
		return $this->db->consulta_datos_db_sql_terceros($sql,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
	}

	function crearActualizarRegistro($entidad, $item, $tabla, $campos, $actualizar=0, $where=array()){
		$conn_sql = $this->empresas_datos($entidad,$item);
		$sql = "";
		if($actualizar){
			$sql = "UPDATE ".$tabla." SET ";

			$arrCampos = array();
			foreach($campos as $key => $value){
				array_push($arrCampos, $key."=".$value);
			}
			$camposJoin = join(", ", $arrCampos);
			$sql .= $camposJoin . " WHERE ";

			$whereJoin = '';
			if(count($where)>0){
				$arrWhere = array();
				foreach($where as $key => $value){
					array_push($arrWhere, $key."=".$value);
				}
				$whereJoin = join(", ", $arrWhere);
			}

			$sql .= $whereJoin;
		}else{
			$sql = "INSERT INTO ".$tabla." ";

			$arrCampos = array();
			foreach($campos as $key => $value){
				array_push($arrCampos, $key);
			}
			$camposJoin = join(", ", $arrCampos);
			$sql .= "(" . $camposJoin . ") VALUES ";

			$arrValues = array();
			foreach($campos as $key => $value){
				array_push($arrValues, $value);
			}
			$valuesJoin = join(", ", $arrValues);
			$sql .= "(" . $valuesJoin . ")";
		}
		//print_r($sql."\n");
		return $this->db->ejecutar_sql_terceros($sql,$conn_sql[0]['host'],$conn_sql[0]['usu'],$conn_sql[0]['pass'],$conn_sql[0]['base'],$conn_sql[0]['Puerto']);
	}

}

?>