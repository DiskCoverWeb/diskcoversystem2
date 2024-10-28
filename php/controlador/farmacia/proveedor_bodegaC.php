<?php 
require(dirname(__DIR__,2).'/modelo/farmacia/proveedor_bodegaM.php');
/**
 * 
 */
$controlador = new proveedor_bodegaC();
if(isset($_GET['lista_clientes']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_clientes($parametros));
}
if(isset($_GET['add_clientes']))
{
	$parametros = $_POST['parametros'];
	$respuesta = $controlador->add_clientes($parametros);
	echo json_encode($respuesta);
}
if(isset($_GET['delete']))
{
	$id =$_POST['id'] ;
	echo json_encode($controlador->delete_clientes($id));
}

if(isset($_GET['buscar_edi']))
{
	$id =$_POST['id'] ;
	echo json_encode($controlador->buscar_edi($id));
}
if(isset($_GET['guardar_cuentas']))
{
	$parametros =$_POST ;
	echo json_encode($controlador->guardar_cuentas($parametros));
}

if(isset($_GET['cliente_proveedor']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cliente_proveedor($query));
}

if(isset($_GET['historial_direcciones']))
{
	$codigo = $_POST['txtcodigo'];
	echo json_encode($controlador->historial_direcciones($codigo));
}

if(isset($_GET['guardar_datos']))
{
	$datos = $_POST;
	echo json_encode($controlador->guardar_datos($datos));
}
class proveedor_bodegaC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new proveedor_bodegaM();
	}

	function lista_clientes($parametros)
	{
		$datos = $this->modelo->lista_clientes($parametros['query']);
		$tr="";
		foreach ($datos as $key => $value) {
			$tr.='<tr>
			<td>'.$value['Cliente'].'</td>
			<td>'.$value['CI_RUC'].'</td>
			<td>'.$value['Telefono'].'</td>
			<td>'.$value['Email'].'</td>
			<td>'.$value['Direccion'].'</td>
			<td>
			<button class="btn btn-sm btn-primary" onclick="buscar_cliente('.$value['ID'].')"><i class="fa fa-pencil"></i></button>
			<button class="btn btn-sm btn-danger" onclick="eliminar('.$value['ID'].')"><i class="fa fa-trash"></i></button>
			</td>
			</tr>';
		}
		return $tr;
		// print_r($datos);die();
	}
	function add_clientes($parametros)
	{
		SetAdoAddNew("Clientes"); 
		SetAdoFields('Cliente',$parametros['nombre']);
		SetAdoFields('CI_RUC',$parametros['ci']);
		SetAdoFields('Telefono',$parametros['telefono']);
		SetAdoFields('Email',$parametros['email']);
				
		$codig = Digito_Verificador($parametros['ci']);
		// print_r($codig);die();
		if($codig['Tipo_Beneficiario']!='C')
		{
			return -3;
		}
		SetAdoFields('T','N');
		SetAdoFields('Codigo',$codig['Codigo_RUC_CI']);
		SetAdoFields('TD',$codig['Tipo_Beneficiario']);
		SetAdoFields('Direccion',$parametros['direccion']);

		if($parametros['id']=='')
		{
			 $val = $this->modelo->lista_clientes($query=false,$ci=$parametros['ci']);
			 if(count($val)>0)
			 {
			 	return -2;
			 }else
			 {
				 return SetAdoUpdate();
			 }
		}else
		{
			SetAdoFieldsWhere('ID',$parametros['id']);
			return SetAdoUpdateGeneric();
		}

	}

	function delete_clientes($id)
	{
		return $this->modelo->delete_clientes($id);
	}

	function buscar_edi($id)
	{
		$val = $this->modelo->lista_clientes($query=false,$ci=false,$id);
		return $val;
	}

	function guardar_cuentas($parametros)
	{

		// print_r($parametros);die();
		$codigoCliente = $parametros['txt_ci_cuenta'];
		$SubCta = $parametros['SubCta'];
		$Cta_Aux =$parametros['DLCxCxP'];
		$SubmoduloGastoCosto = G_NINGUNO;
		$SubmoduloGastoCosto = G_NINGUNO;
		if($parametros['TxtRetIVAB']=='' || $parametros['TxtRetIVAB']=='.'){$parametros['TxtRetIVAB']=0;}
		if($parametros['TxtRetIVAS']=='' || $parametros['TxtRetIVAS']=='.'){$parametros['TxtRetIVAS']=0;}
		
		if(isset($parametros['DLSubModulo']) && $parametros['DLSubModulo']!='')
		{
			$SubmoduloGastoCosto = $parametros['DLSubModulo'];
		}
		  $CtaGastoCosto = '.';		  
		  if(isset($parametros['cbx_cuenta_g'])){$CtaGastoCosto = $parametros['DLGasto']; }else{$Cta1 = G_NINGUNO;}
		  $encontrado = $this->modelo->Catalogo_CxCxP($Cta_Aux,$codigoCliente,$SubCta);


		  // print_r($encontrado);die();
		  	SetAdoAddNew("Catalogo_CxCxP"); 		 
		  	SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		  	SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
		  	SetAdoFields('Codigo',$codigoCliente);
		  	SetAdoFields('Cta',$Cta_Aux);
		  	SetAdoFields('TC',$parametros['SubCta']);
		  	SetAdoFields('Importaciones',0);

		  	SetAdoFields('Cta_Gasto',$CtaGastoCosto);
		  	SetAdoFields('SubModulo',$SubmoduloGastoCosto);
		  	SetAdoFields('Porc_IVAB',($parametros['TxtRetIVAB']/100));
		  	SetAdoFields('Porc_IVAS',($parametros['TxtRetIVAS']/100));
		  	SetAdoFields('Cod_Ret',$parametros['TxtCodRet']);	  		  

		  if(count($encontrado)<=0)
		  {
		  	  return SetAdoUpdate();
		  }else
		  {
		  	 SetAdoFieldsWhere('ID',$encontrado[0]['ID']);
		  	 return SetAdoUpdateGeneric();
		  }

	}

	function cliente_proveedor($query)
	{
		// print_r($query);die();
		// print_r($_SESSION['INGRESO']);die();
		$datos = $this->modelo->cliente_proveedor($query);
		$cli = array();
		foreach ($datos as $key => $value) {
			$cli[] = array('id'=>$value['CI_RUC'],'text'=>$value['Cliente'],'data'=>$value);
		}
		return $cli;
	}

	function historial_direcciones($codigo)
	{
		$datos= $this->modelo->historial_direcciones($codigo);
		$texto = '';
		foreach ($datos as $key => $value) {
			$texto.= $value["Fecha_Registro"]->format('Y-m-d').": ".$value["Ciudad"].", ".$value["Direccion"].". ".$value["Telefono"].'  <br>';
		}
		return $texto;
		// print_r($datos);die();
	}

	function guardar_datos($parametros)
	{
		// print_r($parametros);die();
	    $this->guardar_historial($parametros);

		SetAdoAddNew("Clientes");    
		
		SetAdoFields('CI_RUC',$parametros["txt_ci_ruc"]);
		SetAdoFields('FAX',$parametros["txt_fax"]);
		SetAdoFields('Telefono',$parametros["txt_telefono"]);
		SetAdoFields('Celular',$parametros["txt_celular"]);
		SetAdoFields('Grupo',$parametros["txt_grupo"]);
		SetAdoFields('Contacto',$parametros["txt_contactos"]);
		SetAdoFields('Descuento',$parametros["txt_descuento"]);
		SetAdoFields('Cliente',$parametros["txt_cliente"]);
		SetAdoFields('Tipo_Pasaporte',$parametros["CTipoProv"]);
		SetAdoFields('Parte_Relacionada',$parametros["CParteR"]);
		SetAdoFields('Sexo',$parametros["rbl_sexo"]);
		SetAdoFields('Direccion',$parametros["txt_direccion"]);
		SetAdoFields('DirNumero',$parametros["txt_numero"]);
		SetAdoFields('Email',$parametros["txt_email"]);
		SetAdoFields('Pais',$parametros["ddl_naciones"]);
		SetAdoFields('Prov',$parametros["prov"]);
		SetAdoFields('Ciudad',$parametros["ddl_ciudad"]);
		SetAdoFields('Fecha',$parametros["MBFecha"]);
		SetAdoFields('Fecha_N',$parametros["MBFechaN"]);
		SetAdoFields('Representante',$parametros["txt_representante"]);
		SetAdoFields('Est_Civil',$parametros["ddl_estado_civil"]);
		SetAdoFields('No_Dep',$parametros["txt_no_dep"]);
		SetAdoFields('Casilla',$parametros["txt_casilla"]);
		SetAdoFields('Comision',$parametros["txt_comision"]);
		SetAdoFields('Medidor',$parametros["ddl_medidor"]);
		SetAdoFields('Email2',$parametros["txt_Email2"]);
		SetAdoFields('Plan_Afiliado',$parametros["txt_afiliacion"]);
		SetAdoFields('Actividad',$parametros["txt_actividad"]);
		SetAdoFields('Credito',$parametros["txt_credito"]);
		SetAdoFields('Profesion',$parametros["txt_profesion"]);
		SetAdoFields('Lugar_Trabajo',$parametros["txt_lugar_trabajo"]);
		SetAdoFields('DireccionT',$parametros["txt_direccion_tra"]);
		SetAdoFields('Calificacion',$parametros["txt_califica"]);
		SetAdoFields('Especial',0);
		SetAdoFields('RISE',0);
		SetAdoFields('Asignar_Dr',0);
		SetAdoFields('Codigo',$parametros["txt_codigo"]);
		SetAdoFields('TD',$parametros["TD"]);

	    if(isset($parametros['cbx_ContEsp']))
	    {
	    	etAdoFields('Especial',1);
	    }
	    if(isset($parametros['cbx_rise']))
	    {
		    SetAdoFields('RISE',1);
	    }
	    if(isset($parametros['cbx_dr']))
	    {
	    	SetAdoFields('Asignar_Dr',1);
	    }

	    if($parametros['txt_id']!='')
	    {
			SetAdoFieldsWhere('ID', $parametros['txt_id']);
	    	return SetAdoUpdateGeneric();
	    }else
	    {
	    	$resp = $this->modelo->buscar_cliente(trim($parametros['txt_ci_ruc']),false,true);
		    if(count($resp)>0){return -2;}

		   
			return SetAdoUpdate();
	    }
	    

	}


	function guardar_historial($parametros)
	{
		 // 'Ingresamos el historial de direcciones

		// print_r($parametros);die();
		$Si_No = False;
  		$datos = $this->modelo->buscar_historial($parametros['txt_codigo']);
  		// print_r($datos);
  		// print_r($parametros);die();
  		if(count($datos)>0)
  		{
  		   if($datos[0]["Lugar_Trabajo"] <> $parametros["txt_lugar_trabajo"]) { $Si_No = True;}
	       if($datos[0]["Direccion"] <> $parametros["txt_direccion"] ) { $Si_No = True;}
	       if($datos[0]["DireccionT"] <> $parametros["txt_direccion_tra"] ) { $Si_No = True;}
	       if($datos[0]["TelefonoT"] <> $parametros["txt_telefono2"] ) { $Si_No = True;}
	       if($datos[0]["Telefono"] <> $parametros["txt_telefono"] ) { $Si_No = True;}
	       if($datos[0]["Celular"] <> $parametros["txt_celular"] ) { $Si_No = True;}
	       if($datos[0]["FAX"] <> $parametros["txt_fax"] ) { $Si_No = True;}
	       if($datos[0]["Ciudad"] <> $parametros["ddl_ciudad"] ) { $Si_No = True;}
	       if($datos[0]["Descuento"] <> $parametros["txt_descuento"] ) { $Si_No = True;}

  		}else
  		{
  			 $Si_No = True;
  		}

  		// print_r($Si_No);die();

	  if($Si_No){
	  	// print_r($datos);
  		// print_r($parametros);die();
  		 SetAdoAddNew("Clientes_Datos_Extras");    
		
		 SetAdoFields('CI_RUC',$parametros["txt_ci_ruc"]);
	     SetAdoFields("Fecha_Registro",date('Y-m-d'));
	     SetAdoFields("Codigo",$parametros["txt_codigo"]);
	     SetAdoFields("Lugar_Trabajo",$parametros["txt_lugar_trabajo"]);
	     SetAdoFields("Direccion",$parametros["txt_direccion"]);
	     SetAdoFields("DireccionT",$parametros["txt_direccion_tra"]);
	     SetAdoFields("TelefonoT",$parametros["txt_telefono2"]);
	     SetAdoFields("Telefono",$parametros["txt_telefono"]);
	     SetAdoFields("Celular",$parametros["txt_celular"]);
	     SetAdoFields("FAX",$parametros["txt_fax"]);
	     SetAdoFields("Ciudad",$parametros["ddl_ciudad"]);
	     SetAdoFields("Prov",$parametros["prov"]);
	     SetAdoFields("Pais",$parametros["ddl_naciones"]);
	     SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
	     SetAdoFields("Descuento",$parametros["txt_descuento"]);
	     SetAdoFields("Item",$_SESSION['INGRESO']['item']);
	     SetAdoFields("Tipo_Dato","DIRECCION1");
  		// print_r($datosH);die();// 
	     SetAdoUpdate();

	  }

	
	}
}
?>