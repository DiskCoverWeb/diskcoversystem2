<?php
include(dirname(__DIR__,2).'/modelo/empresa/crear_empresaM.php');

$controlador = new crear_empresaC();

if(isset($_GET['llamar'])){
    $ll='';
    if(isset($_POST['item']))
    {
        $ll=$_POST['item'];
    }
    echo json_encode($controlador->llamardb($ll));	
}
if(isset($_GET['guardar_empresa']))
{
    $query = $_POST;
	echo json_encode($controlador->guardardb_empresas($query));
}
if(isset($_GET['delete']))
{
    $id='';
    if(isset($_POST['id']));
    {
        $id =$_POST['id'];
    }
	echo json_encode($controlador->delete_empresas($id));
}
if(isset($_GET['validarCI']))
{
	$ci =$_POST['ci'] ;
	echo json_encode(Digito_verificador($ci));
}
if(isset($_GET['validarRUC']))
{
	$ci =$_POST['txtruc'] ;
	echo json_encode(Digito_verificador($ci));
}
if(isset($_GET['validarRUConta']))
{
	$ci =$_POST['txtruconta'] ;
	echo json_encode(Digito_verificador($ci));
}
if(isset($_GET['subdireccion']))
{
    $query = $_POST['txtsubdi'];
	echo json_encode($controlador->TextSubDir_LostFocus($query));
}

if(isset($_GET['naciones']))
{
  echo json_encode(naciones_todas());
}
if(isset($_GET['provincias']))
{
  $pais = '';
  if(isset($_POST['pais']))
  {
    $pais = $_POST['pais'];
  }
	echo json_encode(provincia_todas($pais));
}
if(isset($_GET['ciudad']))
{
	echo json_encode(todas_ciudad($_POST['idpro']));
}
if(isset($_GET['empresas']))
{	
    $dato = '';
    if(isset($_GET['q']))
    {
        $dato = $_GET['q'];
    }
	echo json_encode($controlador->lista_empresas($dato));	
}
if(isset($_GET['Copiarempresas']))
{
    $cempresa = '';
    if(isset($_GET['Nomempresa']))
    {
        $cempresa = $_GET['Nomempresa'];
    }
    echo json_encode($controlador->lista_cempresa($cempresa));
}
if(isset($_GET['traer_usuario']))
{	

    $ll='';
    if(isset($_POST['form']))
    {
        $ll=$_POST['form'];
    }
	echo json_encode($controlador->lista_usuario($ll));	
}
if(isset($_GET['informacion_empre']))
{	
    $para = $_POST['parametros'];
    $res = $controlador->info_empresa($para);
}
if(isset($_GET['formulario']))
{	
    $para = '';
    $res = $controlador->formulario1($para);

    echo json_encode($res);
}
class crear_empresaC 
{
    private $modelo;
    function __construct()
	{
        $this->modelo = new crear_empresaM();
    }

    function info_empresa($parametros)
    {        
        if($parametros ['id']=='empresa' )
        {
            return 'empresa1';
        }
        else{
            return 'no empresa';
        }
        
    }
    function formulario1($dato)
    {
        $datos = $this->modelo->lista_empresas($dato);        
        $lis = array();
        foreach ($datos as $key => $value) {
            $lis[] = array(
                'id'=>$value['Item'],
                'Fecha'=>$value['Fecha'],
                'Ciudad'=>$value['Ciudad'],
                'Pais'=>$value['Pais'],
                'Empresa'=>$value['Empresa'],
                'Gerente'=>$value['Gerente'],
                'RUC'=>$value['RUC'],
                'Telefono1'=>$value['Telefono1'],
                'Telefono2'=>$value['Telefono2'],
                'FAX'=>$value['FAX'],
                'Direccion'=>$value['Direccion'],
                'SubDir'=>$value['SubDir'],
                'Logo_Tipo'=>$value['Logo_Tipo'],
                'Alto'=>$value['Alto'],
                'Servicio'=>$value['Servicio'],
                'S_M'=>$value['S_M'],
                'Cta_Caja'=>$value['Cta_Caja'],
                'Cotizacion'=>$value['Cotizacion'],
                'Email'=>$value['Email'],
                'Contador'=>$value['Contador'],
                'CodBanco'=>$value['CodBanco'],
                'Num_Meses'=>$value['Num_Meses'],
                'Nombre_Comercial'=>$value['Nombre_Comercial'],
                'Mod_Fact'=>$value['Mod_Fact'],
                'Mod_Fecha'=>$value['Mod_Fecha'],
                'Num_CD'=>$value['Num_CD'],
                'Num_CE'=>$value['Num_CE'],
                'Num_CI'=>$value['Num_CI'],
                'Plazo_Fijo'=>$value['Plazo_Fijo'],
                'Det_Comp'=>$value['Det_Comp'],
                'CI_Representante'=>$value['CI_Representante'],                
                'TD'=>$value['TD'],
                'RUC_Contador'=>$value['RUC_Contador'],
                'CPais'=>$value['CPais'],
                'No_Patronal'=>$value['No_Patronal'],
                'Dec_PVP'=>$value['Dec_PVP'],
                'Dec_Costo'=>$value['Dec_Costo'],
                'CProv'=>$value['CProv'],
                'Grabar_PV'=>$value['Grabar_PV'],
                'Separar_Grupos'=>$value['Separar_Grupos'],
                'Credito'=>$value['Credito'],
                'Rol_2_Pagina'=>$value['Rol_2_Pagina'],
                'Cant_Item_PV'=>$value['Cant_Item_PV'],
                'Copia_PV'=>$value['Copia_PV'],
                'Encabezado_PV'=>$value['Encabezado_PV'],
                'Calcular_Comision'=>$value['Calcular_Comision'],
                'Formato_Inventario'=>$value['Formato_Inventario'],
                'Formato_Activo'=>$value['Formato_Activo'],
                'Cant_Ancho_PV'=>$value['Cant_Ancho_PV'],
                'Grafico_PV'=>$value['Grafico_PV'],
                'Referencia'=>$value['Referencia'],
                'Fecha_Rifa'=>$value['Fecha_Rifa'],
                'Rifa'=>$value['Rifa'],
                'Monto_Minimo'=>$value['Monto_Minimo'],
                'Num_ND'=>$value['Num_ND'],
                'Num_NC'=>$value['Num_NC'],
                'Cierre_Vertical'=>$value['Cierre_Vertical'],
                'Tipo_Carga_Banco'=>$value['Tipo_Carga_Banco'],
                'Comision_Ejecutivo'=>$value['Comision_Ejecutivo'],
                'Seguro'=>$value['Seguro'],
                'Nombre_Banco'=>$value['Nombre_Banco'],
                'Impresora_Rodillo'=>$value['Impresora_Rodillo'],
                'Impresora_Defecto'=>$value['Impresora_Defecto'],
                'Papel_Impresora'=>$value['Papel_Impresora'],
                'Marca_Agua'=>$value['Marca_Agua'],
                'Seguro2'=>$value['Seguro2'],
                'Cta_Banco'=>$value['Cta_Banco'],
                'Mod_PVP'=>$value['Mod_PVP'],
                'Abreviatura'=>$value['Abreviatura'],
                'Registrar_IVA'=>$value['Registrar_IVA'],
                'Imp_Recibo_Caja'=>$value['Imp_Recibo_Caja'],
                'Det_SubMod'=>$value['Det_SubMod'],
                'Establecimientos'=>$value['Establecimientos'],
                'Email_Conexion'=>$value['Email_Conexion'],
                'Actualizar_Buses'=>$value['Actualizar_Buses'],
                'Email_Contabilidad'=>$value['Email_Contabilidad'],
                'Cierre_Individual'=>$value['Cierre_Individual'],
                'Email_Respaldos'=>$value['Email_Respaldos'],
                'Imp_Ceros'=>$value['Imp_Ceros'],
                'Tesorero'=>$value['Tesorero'],
                'CIT'=>$value['CIT'],
                'Dec_IVA'=>$value['Dec_IVA'],
                'Dec_Cant'=>$value['Dec_Cant'],
                'Razon_Social'=>$value['Razon_Social'],
                'Formato_Cuentas'=>$value['Formato_Cuentas'],
                'Ambiente'=>$value['Ambiente'],
                'Ruta_Certificado'=>$value['Ruta_Certificado'],
                'Clave_Certificado'=>$value['Clave_Certificado'],
                'Web_SRI_Recepcion'=>$value['Web_SRI_Recepcion'],
                'Codigo_Contribuyente_Especial'=>$value['Codigo_Contribuyente_Especial'],
                'Email_Conexion_CE'=>$value['Email_Conexion_CE'],
                'Obligado_Conta'=>$value['Obligado_Conta'],
                'Por_CxC'=>$value['Por_CxC'],
                'Estado'=>$value['Estado'],
                'No_Autorizar'=>$value['No_Autorizar'],
                'Email_Procesos'=>$value['Email_Procesos'],
                'Email_CE_Copia'=>$value['Email_CE_Copia'],
                'Firma_Digital'=>$value['Firma_Digital'],
                'Combo'=>$value['Combo'],
                'Fecha_Igualar'=>$value['Fecha_Igualar'],
                'Ret_Aut'=>$value['Ret_Aut'],
                'LeyendaFAT'=>$value['LeyendaFAT'],
                'Signo_Dec'=>$value['Signo_Dec'],
                'Signo_Mil'=>$value['Signo_Mil'],
                'Fecha_CE'=>$value['Fecha_CE'],
                'Centro_Costos'=>$value['Centro_Costos'],
                'smtp_Servidor'=>$value['smtp_Servidor'],
                'smtp_Puerto'=>$value['smtp_Puerto'],
                'smtp_UseAuntentificacion'=>$value['smtp_UseAuntentificacion'],
                'smtp_SSL'=>$value['smtp_SSL'],
                'Serie_FA'=>$value['Serie_FA'],
                'Email_Contraseña'=>$value['Email_Contraseña'],
                'Email_Contraseña_CE'=>$value['Email_Contraseña_CE'],                
                'X'=>$value['X'],
                'Debo_Pagare'=>$value['Debo_Pagare'],
                'smtp_Secure'=>$value['smtp_Secure'],
                'Cartera'=>$value['Cartera'],
                'Cant_FA'=>$value['Cant_FA'],
                'Fecha_P12'=>$value['Fecha_P12'],
                'Tipo_Plan'=>$value['Tipo_Plan'],
                'ID'=>$value['ID'],
                'RUC_Operadora'=>$value['RUC_Operadora'],
            );
        }
        return $lis; 
    }

    function lista_empresas($dato)
    {
        $datos = $this->modelo->lista_empresas($dato);

        $lis = array();
        foreach ($datos as $key => $value) {
            $lis[] = array('id'=>$value['Item'],'text'=>$value['Empresa']);
        }
        return $lis;    
    }
    function lista_cempresa($dato)
    {
        $datos = $this->modelo->copia_empresa($dato);

        $lis = array();
        foreach ($datos as $key => $value) {
            $lis[] = array('id'=>$value['Item'],'text'=>$value['Empresa']);
        }
        return $lis; 
    }
    function lista_usuario($dato)
    {
        $datos = $this->modelo->usuario($dato);
        $lis = array();
        foreach ($datos as $key => $value) {
            $lis[] = array(
                'Codigo'=>$value['Codigo'],
                'Usuario'=>$value['Usuario'],
                'Clave'=>$value['Clave']
            );
        }
        return $lis;    
    }
    function llamardb($l1)
    {
        $datos = $this->modelo->lista_empresas(false,$l1);
        return $datos;
    }


function  TextSubDir_LostFocus($query)
{
    $TextSubDir = TextoValido($query);
    $dato = $this->modelo->consulta_empresa();
    if(count($dato)>0)
    {
        if($TextSubDir == G_NINGUNO  )
        {
            $NumEmpSubDir = 0;
            if(count($dato) > 0)
            {
                $NumEmpSubDir = intval($dato[0]["Item"]);
            }
            $NumEmpSubDir = $NumEmpSubDir + 1;
            $TextSubDir = "EMPRE".generaCeros($NumEmpSubDir,3);
            return $TextSubDir;
        }else
        {
            $dato2 = $this->modelo->consulta_empresa($TextSubDir);
            if(count($dato2)>0)
            {
                if($_SESSION['INGRESO']['item'] <> $dato2[0]["Item"] )
                {
                    return null;
                }

            }
        }
    }
}


    // function guardar_subdirec($parametros)
    // {

    //     $variable = $parametros[]
    //     // print_r($_POST);
    //     // $txtsubdi = $_POST['txtsubdi'];
    //     // TextoValido($txtsubdi,"" , true);
    //     // if($parametros["txtsubdi"]=='Ninguno')
    //     // {
    //     //     $NumEmpSubDir = '0';
    //     //     print_r($NumEmpSubDir);0
    //     // }
    //     // if(guardardb_empresa()!='0'){
    //     //     MoveFirst();
    //     //     $NumEmpSubDir = $NumEmpSubDir + '1' ;
    //     //     print_r($NumEmpSubDir);die();
    //     // }
    //     // if($parametros["txtsubdi"]!='')
    //     // {
    //     //     $txtsubdi = $_POST['txtsubdi'];
    //     //     $r = TextoValido($txtsubdi);
    //     //     print_r($r);die();
    //     //     return $r;
    //     // }
    //     // else
    //     // {
    //     //     print_r('0');
    //     // }
    //     $txtsubdi = $parametros["txtsubdi"];
    //     $r = TextoValido($txtsubdi,'',true)

    //     $datos = $this->modelo->consulta_empresa();
    //     if(count($dato)>0)
    //     {
            
    //     }
    //     // if($r!= '')
	// 	// {
	// 	// 	$datos[0]["dato"] = $r;
    //     //     $datos[0]["campo"]='Det_SubMod';
    //     //     print_r($datos);die();
	// 	// }else
    //     // {
    //     //     print_r('0');
    //     // }
    //     // $txtsubdi = $_POST['txtsubdi'];
    //     // TextoValido($txtsubdi,"" , true);

    //     // if($txtsubdi == 'Ninguno')
    //     // {
    //     //     $NumEmpSubDir == $txtsubdi;
    //     //     print_r($NumEmpSubDir);die();
    //     // }
    // }
    // function guardardb_empresa($parametro)
    // {//TODO LS 2023-05-19 NO SE ESTA USANDO - SE COMENTA - PARA EN UN FUTURO BORRARLA
    //     $resp = $this->modelo->lista_empresas(trim($parametro['item']));
    //     $datos[0]['campo'] = 'Razon_Social'; 
    //     $datos[0]['dato'] = $parametro['TxtRazonSocial'];  
        
    //     if($parametro['TxtItem']!='')
	// 	{
	// 		$campoWhere[0]['campo'] = 'Item';
	// 		$campoWhere[0]['valor'] = $parametro['TxtItem'];
	// 		$re = update_generico($datos,'Empresa',$campoWhere);
	// 	}else
	// 	{
	// 		print_r($resp);die();
	// 		if(count($resp)==0)
	// 	      {
	// 		    $re = insert_generico('Empresas',$datos); // optimizado pero falta 
	// 		  }else{
	// 		  	return 2;
	// 		  }
	// 	}

    //     // insert_generico('Empresas',$datos);
    // }
    function delete_empresas($id)
	{
        $Item = $id;
        $re =  Eliminar_Empresa_SP($Item);
        return $this->modelo->delete_empresa($re);
	}
    function guardardb_empresas($parametros)  //para un solo dato string
    {
        $CorreoDiskCover = CorreoDiskCover;
        $ContrasenaDiskCover = ContrasenaDiskCover;

        //DATOS PRINCIPALES
        SetAdoAddNew("Empresas");
        SetAdoFields("Empresa", $parametros['TxtEmpresa']);
        SetAdoFields("Razon_Social", $parametros["TxtRazonSocial"]);
        SetAdoFields("Nombre_Comercial", $parametros["TxtNomComercial"]);
        SetAdoFields("RUC", $parametros["TxtRuc"]);
        SetAdoFields("Gerente", $parametros["TxtRepresentanteLegal"]);
        SetAdoFields("CI_Representante", $parametros["TxtCI"]);
        SetAdoFields("Direccion", $parametros["TxtDirMatriz"]);
        SetAdoFields("Establecimientos", $parametros["TxtEsta"]);
        SetAdoFields("Telefono1", $parametros["TxtTelefono"]);
        SetAdoFields("Telefono2", $parametros["TxtTelefono2"]);
        SetAdoFields("FAX", $parametros['TxtFax']);
        SetAdoFields("S_M", $parametros["TxtMoneda"]);
        SetAdoFields("No_Patronal", $parametros["TxtNPatro"]);
        SetAdoFields("CodBanco", $parametros["TxtCodBanco"]);
        SetAdoFields("Tipo_Carga_Banco", $parametros["TxtTipoCar"]);
        SetAdoFields("Abreviatura", $parametros["TxtAbrevi"]);
        SetAdoFields("Email", $parametros["TxtEmailEmpre"]);
        SetAdoFields("Email_Contabilidad", $parametros["TxtEmailConta"]);
        SetAdoFields("Email_Respaldos", ($parametros["TxtEmailRespa"]=='')?$CorreoDiskCover:$parametros["TxtEmailRespa"]);

	    // $datos[18]["dato"] = $parametros["TxtEmailRespa"];
	    // $datos[18]["campo"] = 'Email_Respaldos';
	    SetAdoFields("Seguro", $parametros["TxtSegDes1"]);
        SetAdoFields("Seguro2", $parametros["TxtSegDes2"]);
        SetAdoFields("SubDir", $parametros["TxtSubdir"]);
        SetAdoFields("Contador", $parametros["TxtNombConta"]);
        SetAdoFields("RUC_Contador", $parametros["TxtRucConta"]);
        SetAdoFields("Obligado_Conta", $parametros["ddl_obli"]);
        SetAdoFields("CPais", $parametros["ddl_naciones"]);
        SetAdoFields("Prov", $parametros["prov"]);
        SetAdoFields("Ciudad", $parametros["ddl_ciudad"]);

        //PROCESOS GENERALES        
        if($parametros["ckASDAS"]== 'false')
		{
            SetAdoFields("Det_SubMod", 0);
		}else if($parametros["ckASDAS"]== 'true')
        {
            SetAdoFields("Det_SubMod", 1);
        }
        
        SetAdoFields("smtp_Servidor", $parametros["TxtServidorSMTP"]);
        SetAdoFields("smtp_Puerto", $parametros["TxtPuerto"]);

        if($parametros["TxtPVP"]<=2)
        {
            SetAdoFields("Dec_PVP", 2);
        }
        if($parametros["TxtCOSTOS"]<=2)
        {
            SetAdoFields("Dec_Costo", 2);
        }
        if($parametros["TxtIVA"]<=2)
        {
            SetAdoFields("Dec_IVA", 2);
        }else if ($parametros["TxtIVA"]>=4)
        {
            SetAdoFields("Dec_IVA", 4);
        }
	    // $datos[31]["dato"] = $parametros["TxtPVP"];
	    // $datos[31]["campo"] = 'Dec_PVP';
	    // $datos[32]["dato"] = $parametros["TxtCOSTOS"];
	    // $datos[32]["campo"] = 'Dec_Costo';
	    // $datos[33]["dato"] = $parametros["TxtIVA"];
	    // $datos[33]["campo"] = 'Dec_IVA';
        SetAdoFields("Dec_Cant", $parametros["TxtCantidad"]);

        //COMPROBANTES ELECTRÓNICOS
        SetAdoFields("Codigo_Contribuyente_Especial", $parametros["TxtContriEspecial"]);

        if($parametros["Ambiente1"]== 'true' && $parametros["Ambiente2"]== 'false')
        {
            SetAdoFields("Ambiente", 1);
            SetAdoFields("Web_SRI_Recepcion", $parametros["TxtWebSRIre"]);
            SetAdoFields("Web_SRI_Autorizado", $parametros["TxtWebSRIau"]);
        }else if ($parametros["Ambiente1"]== 'false' && $parametros["Ambiente2"]== 'true')
        {
            SetAdoFields("Ambiente", 2);
            SetAdoFields("Web_SRI_Recepcion", $parametros["TxtWebSRIre"]);
            SetAdoFields("Web_SRI_Autorizado", $parametros["TxtWebSRIau"]);
        }
		
        SetAdoFields("Ruta_Certificado", $parametros["TxtEXTP12"]);
        SetAdoFields("Clave_Certificado", $parametros["TxtContraExtP12"]);
        SetAdoFields("Email_Conexion", ($parametros["TxtEmailGE"]=='')?$CorreoDiskCover:$parametros["TxtEmailGE"]);
        SetAdoFields("Email_Contraseña", ($parametros["TxtContraEmailGE"]=='')?$ContrasenaDiskCover:$parametros["TxtContraEmailGE"]);
        SetAdoFields("Email_Conexion_CE", $parametros["TxtEmaiElect"]);
        SetAdoFields("Email_Contraseña_CE", $parametros["TxtContraEmaiElect"]);
        SetAdoFields("Email_Procesos", $parametros["TxtCopiaEmai"]);
        SetAdoFields("RUC_Operadora", $parametros["TxtRUCOpe"]);
        SetAdoFields("LeyendaFA", $parametros["txtLeyendaDocumen"]);
        SetAdoFields("LeyendaFAT", $parametros["txtLeyendaImpresora"]);

        if($parametros["ckMFNV"]== 'false')
		{
            SetAdoFields("Mod_Fact", 0);
		}else if($parametros["ckMFNV"]== 'true')
        {
            SetAdoFields("Mod_Fact", 1);
        }
        
        if($parametros["ckMPVP"]== 'false')
		{
            SetAdoFields("Mod_PVP", 0);
		}else if($parametros["ckMPVP"]== 'true')
        {
            SetAdoFields("Mod_PVP", 1);
        }

        if($parametros["ckIRCF"]== 'false')
		{
            SetAdoFields("Imp_Recibo_Caja", 0);
		}else if($parametros["ckIRCF"]== 'true')
        {
            SetAdoFields("Imp_Recibo_Caja", 1);
        }

        if($parametros["ckIMR"]== 'false')
		{
            SetAdoFields("Medio_Rol", 0);
		}else if($parametros["ckIMR"]== 'true')
        {
            SetAdoFields("Medio_Rol", 1);
        }

        if($parametros["ckIRIP"]== 'false')
		{
            SetAdoFields("Rol_2_Pagina", 0);
		}else if($parametros["ckIRIP"]== 'true')
        {
            SetAdoFields("Rol_2_Pagina", 1);
        }

        if($parametros["ckPDAC"]== 'false')
		{
            SetAdoFields("Det_Comp", 0);
		}else if($parametros["ckPDAC"]== 'true')
        {
            SetAdoFields("Det_Comp", 1);
        }

        if($parametros["ckRIAC"]== 'false')
		{
            SetAdoFields("Registrar_IVA", 0);
		}else if($parametros["ckRIAC"]== 'true')
        {
            SetAdoFields("Registrar_IVA", 1);
        }

        if($parametros["ckDM"]== 'true' && $parametros["ckDS"]== 'false')
		{
            SetAdoFields("Num_CD", 1);
		}else if($parametros["ckDM"]== 'false' && $parametros["ckDS"]== 'true')
        {
            SetAdoFields("Num_CD", 0);
        }

        if($parametros["ckAutenti"]== 'false')
		{
            SetAdoFields("smtp_UseAuntentificacion", 0);
		}else if($parametros["ckAutenti"]== 'true')
        {
            SetAdoFields("smtp_UseAuntentificacion", 1);
        }

        if($parametros["ckSSL"]== 'false')
		{
            SetAdoFields("smtp_SSL", 0);
		}else if($parametros["ckSSL"]== 'true')
        {
            SetAdoFields("smtp_SSL", 1);
        }

        if($parametros["ckSecure"]== 'false')
		{
            SetAdoFields("smtp_Secure", 0);
		}else if($parametros["ckSecure"]== 'true')
        {
            SetAdoFields("smtp_Secure", 1);
        }

        if($parametros["ckIM"]== 'true' && $parametros["ckIS"]== 'false')
		{
            SetAdoFields("Num_CI", 1);
		}else if($parametros["ckIM"]== 'false' && $parametros["ckIS"]== 'true')
        {
            SetAdoFields("Num_CI", 0);
        }

        if($parametros["ckEM"]== 'true' && $parametros["ckES"]== 'false')
		{
            SetAdoFields("Num_CE", 1);
		}else if($parametros["ckEM"]== 'false' && $parametros["ckES"]== 'true')
        {
            SetAdoFields("Num_CE", 0);
        }

        if($parametros["ckNDM"]== 'true' && $parametros["ckNDS"]== 'false')
		{
            SetAdoFields("Num_ND", 1);
		}else if($parametros["ckNDM"]== 'false' && $parametros["ckNDS"]== 'true')
        {
            SetAdoFields("Num_ND", 0);
        }

        if($parametros["ckNCM"]== 'true' && $parametros["ckNCS"]== 'false')
		{
            SetAdoFields("Num_NC", 1);
		}else if($parametros["ckNCM"]== 'false' && $parametros["ckNCS"]== 'true')
        {
            SetAdoFields("Num_NC", 0);
        }

        if($parametros['TxtCI']!='' && $parametros['TxtItem']!='')
        {
            SetAdoFieldsWhere("Item", $parametros['TxtItem']);
            SetAdoUpdateGeneric();
        }else{
            SetAdoFields("Item", $this->modelo->generarItem());
            SetAdoUpdate();
        }

        SetAdoAddNew("Accesos");
        SetAdoFields("Codigo", $parametros["TxtCI"]);
        SetAdoFields("Usuario", $parametros["TxtUsuario"]);
        SetAdoFields("Clave", $parametros["TxtClave"]);
        SetAdoFields("Nombre_Completo", $parametros["TxtRepresentanteLegal"]);

        if($parametros['TxtCI']!='' && $parametros['TxtItem']!='')
        {
            SetAdoFieldsWhere("Codigo", $parametros['TxtCI']);
            SetAdoUpdateGeneric();
        }else
        {
            SetAdoUpdate();
        }

        return 2;
    }

}



?>