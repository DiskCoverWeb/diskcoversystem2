<?php 
include(dirname(__DIR__,2).'/modelo/contabilidad/ctaOperacionesM.php');
if(!class_exists('variables_g'))
{
  include(dirname(__DIR__,1).'/db/variables_globales.php');//
}
/**
 * 
 */
$controlador =  new ctaOperacionesC();
if(isset($_GET['meses']))
{
	echo json_encode($controlador->meses_presu());
}
if(isset($_GET['cuentas']))
{
	echo json_encode($controlador->cuentas());
}
if(isset($_GET['tipo_pago']))
{
	echo json_encode($controlador->tipo_pago());
}
if(isset($_GET['tip_cuenta']))
{
	echo json_encode($controlador->tip_cuenta($_POST['cuenta']));
}
if(isset($_GET['grabar']))
{
	echo json_encode($controlador->grabar_cuenta($_POST['parametros']));
}
if(isset($_GET['presupuesto']))
{
	echo json_encode($controlador->presupuesto($_POST['cod']));
	
}
if(isset($_GET['datos_cuenta']))
{
	echo json_encode($controlador->datos_cuenta($_POST['cod']));
	
}
if(isset($_GET['ingresar_presu']))
{
	echo json_encode($controlador->presupuesto_ing($_POST['parametros']));
}
if(isset($_GET['copy_empresa']))
{
	echo json_encode($controlador->copiar_cuenta_lista());
}
if(isset($_GET['cambiar_empresa']))
{
	echo json_encode($controlador->cambiar_cuenta_lista($_POST['cta']));
}
if(isset($_GET['copiar']))
{
	echo json_encode($controlador->copiar_cuenta($_POST['parametros']));
}
if(isset($_GET['cambiar_op']))
{
	echo json_encode($controlador->cambiar_cuenta($_POST['parametros']));
}
if(isset($_GET['eliminar_cuentas']))
{
	echo json_encode($controlador->eliminar_cuentas($_POST['parametros']));
}
class ctaOperacionesC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new ctaOperacionesM();
	}


function datos_cuenta($cod)
{
	$dato = $this->modelo->datos_cuenta($cod);
	// print_r($dato);die();
	return $dato;
}
function presupuesto_ing($parametro)
{
	$m = nombre_X_mes($parametro['mes1']);
	$cta=substr($parametro['Cta'],0,-1);
	$fecha = date('Y').'-'.$m.'-01';
	SetAdoAddNew("Trans_Presupuestos");   
	SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
	SetAdoFields('Cta',$cta);
	SetAdoFields('Presupuesto',$parametro['valor']);
	SetAdoFields('Item',$_SESSION['INGRESO']['item']);
	SetAdoFields('Mes_No',$fecha);
	SetAdoFields('Mes',$parametro['mes']);
	SetAdoFields('Codigo',G_NINGUNO);
	$this->modelo->buscar_trans_presu($cta,G_NINGUNO,$fecha);
	return SetAdoUpdate();
}


function copiar_cuenta_lista()
{
	$dato = $this->modelo->copiar_cuenta_lista();
	return $dato;
}
function cambiar_cuenta_lista($cta)
{
	$dato = $this->modelo->cambiar_cuenta_lista(substr($cta,0,-1));
	return $dato;
}

function copiar_cuenta($parametros)
{
  $dato=1;
  $PeriodoCopy = $_SESSION['INGRESO']['periodo'];
  $Cadena = $parametros['empresa'];
  $NumItem = G_NINGUNO;
  if($Cadena == '')
  {
  	$Cadena = G_NINGUNO;
  }else
  {
  	$NumItem = $parametros['empresa'];
  }
  // If Cadena = "" Then Cadena = Ninguno
  // With AdoEmp.Recordset
  //  If .RecordCount > 0 Then
  //     .MoveFirst
  //     .Find ("Empresa LIKE '" & Cadena & "' ")
  //      If Not .EOF Then NumItem = .Fields("Item")
  //  End If
  // End With
  if($NumItem <> G_NINGUNO)
  {
  	if($parametros['CheqCatalogo']=='true')
  	{
  	    if(copiar_tabla_empresa("Catalogo_Cuentas", $NumItem, $PeriodoCopy,true,false,true)==1)
  	    {
  	    	 if (Copiar_tabla_empresa("Codigos",$NumItem, $PeriodoCopy,true,false,true)==1) 
  	    	 { 
  	    	 	if (copiar_tabla_empresa("Ctas_Proceso",$NumItem,$PeriodoCopy,true,false,true)==1){
  	    	 		  // 	$dato = -1;
  	    	 	}else{	$dato = -1;	}
  	    	 }else{ $dato=-1;}
  	    
  	    }else{ $dato = -1;}
  	   
  	}
  	if($parametros['CheqSetImp']=='true')
  	{
       if(Copiar_tabla_empresa("Formato", $NumItem, $PeriodoCopy,false,false,true)==1)
       {
       
       		if(Copiar_tabla_empresa("Seteos_Documentos", $NumItem, $PeriodoCopy,false,false,true)==-1)
             {
       	      $dato = -1;
             }
       }else
       {
       	$dato = -1;
       }
       
  	}
  	if($parametros['CheqFact']=='true')
  	{
  		if(Copiar_tabla_empresa("Catalogo_Lineas", $NumItem, $PeriodoCopy,true,false,true)==1)
  		{
  			if(Copiar_tabla_empresa("Catalogo_Productos", $NumItem, $PeriodoCopy,true,false,true)!=1)
  				{
  					$dato = -1;
  				}
  		}else{ $dato = -1;}
        
  	}
  	if($parametros['CheqSubCta'] == 'true')
  	{
  		if(Copiar_tabla_empresa("Catalogo_SubCtas", $NumItem, $PeriodoCopy,true,false,true)!=1)
  		{
  			$dato=-1;
  		}
  	}
  	if($parametros['CheqSubCP'] == 'true')
  	{
  		if(Copiar_tabla_empresa("Catalogo_CxCxP", $NumItem, $PeriodoCopy,true,false,true)!=1)
  		{
  			$dato = -1;
  		}
  	}

  }
	return $dato;
}


function presupuesto($cod)
{
	$dato = $this->modelo->presupuesto($cod);
	return $dato;
}
function meses_presu()
{
	return meses_del_anio();
}
   
	function cuentas()
	{

		//generar nivels cta 


		// fin de generar niveles cta
		// print_r();

		$p = explode('.',$_SESSION['INGRESO']['Formato_Cuentas']);
		$niveles = count($p);
		//carga las cuentas po seccion c.c.cc.cc.cc.ccc
		$datos = $this->modelo->cargar_cuentas(strlen($p[0]));
		$nivel = array();
		$len =0;
		foreach ($datos as $key => $value) {
		     //carga todos los componentes dentro de una			 
			  $niv = $this->modelo->cargar_niveles($value['Codigo']);
			for ($i=0; $i < $niveles; $i++) {
				$len+=strlen($p[$i]);
				 foreach ($niv as $key => $value2) {
				 	if(strlen($value2['Codigo']) == $len)
				 	{
				 		//if($value2['Codigo'] )
				 		$nivel[$i][$value2['Codigo']] = array('Codigo'=>$value2['Codigo'],'Cuentas'=>$value2['Cuenta'],'ico'=>$value2['TC']);
				 	}
				 }
				$len+=1;
			}
			$len=0;
		}
		// c.c.cc.cc.cc.cccc
  $le=0;
  $nom_nivel='';
  $nombretemp='';
  $tabla = '';
  $tablatemp = '';

  // print_r($nivel);die();
  
$temporar = array();
// C.C.CC.CC.CC.CCC
for ($i=$niveles; $i >0; $i--){
	if(isset($nivel[$i]))
	{		
		$le=strlen($p[$i]);
	   foreach ($nivel[$i] as $key => $value) {		
	   	  	$ni = substr($value['Codigo'], 0, (-1*$le)-1);
	   	  	if($nom_nivel == '')
	   	  	{
	   	  		$nombretemp = $ni;
	   	  		$nom_nivel = $ni;	
	   	  		$n = $ni;   	  		
	   	  		$tabla.='<li><a href="javascript:void(0)" id="niv_'.str_replace('.','_',$ni).'">'.$nom_nivel.'-</a><ul>';
	   	  		$tabla.='<li><a href="#" >'.$value['Codigo'].'- '.$value['Cuentas'].'</a></li>';
	   	  	}else
	   	  	{
	   	  		if($ni==$nombretemp)
	   	  		{
	   	  			$tabla.='<li><a href="#">'.$value['Codigo'].'- '.$value['Cuentas'].'</a></li>';
	   	  		}else
	   	  		{
	   	  			$tabla='';
	   	  			$nombretemp = $ni;
	   	  		    $nom_nivel = $ni;
	   	  		    $tabla.='<li><a href="javascript:void(0)" id="niv_'.str_replace('.','_',$ni).'">'.$nom_nivel.'-</a><ul>';
	   	  		    $tabla.='<li><a href="#">'.$value['Codigo'].'- '.$value['Cuentas'].'</a></li>';		   	  		   
	   	  		}

	   	  	}
	   	  	$temporar[$nombretemp]= array($tabla.'</ul></li fin>');
	   }

// print_r($tabla);die();  
	   $tabla='';	   	 
   }

}


// print_r($tabla);die();  
// print_r($temporar);die();

$corte ='';
$valor='';
$co = 0;
$corte_ante='';
$titulo='';

// print_r($nivel);
// die();
$tablatemp ='NO hay cuentas';
// print_r($temporar);
// die();
if(empty(!$temporar))
{

$tablatemp ='';
foreach ($temporar as $key => $value) {
	$tablatemp.=$value[0]; 
}

foreach ($temporar as $key => $value) {
	
	if(strlen($key) != 1)
	{
	$posicion_coincidencia = strpos($tablatemp, '</li fin>');
	if($posicion_coincidencia !== false)
	{	 
		$remplazo = substr($tablatemp,0,$posicion_coincidencia).'</li fin>';
		$tablatemp = str_replace($remplazo,'',$tablatemp);
	}
	$valor = $key.'- ';
	// print_r($tablatemp);
	$remplazar_en = strpos($tablatemp, $valor);
	if($remplazar_en !== false)
	{
		$corte = substr($tablatemp,$remplazar_en);
		$numc = strlen($corte);
			$hast_titulo = strpos($corte,'</a></li>');
			 if($hast_titulo !== false)
			 {
			 	$titulo = substr($corte,0,$hast_titulo);
			 	
			 }
			  $continua = strpos($corte,'</li>');
			 if($continua !== false)
			 {
			 	
			 	$corte = substr($corte,$continua+5);
			 	 
			 }
			 $corte_ante = substr($tablatemp,0,$remplazar_en);
			 $corte_ante = substr($corte_ante,0,-16);

			 $seccion = str_replace($key.'-',$titulo,$remplazo);
			
			 
			 $seccion = str_replace('</li fin>','</li>',$seccion);
			 $tablatemp = $corte_ante.''.$seccion.''.$corte;
			
	}	

  }

}
// print_r($tablatemp);
// print_r($datos);die();
foreach ($datos as $key => $value) {
	if(is_numeric($value['Codigo']))
	{
		$posicion_coincidencia = strpos($tablatemp,'>'.$value['Codigo'].'-<');
		if($posicion_coincidencia !== false)
		{	 		
			$tablatemp = str_replace('>'.$value['Codigo'].'-<','>'.$value['Codigo'].'- '.$value['Cuenta'].'<',$tablatemp);	
		}else
		{

			// print_r($key);
			// print_r($tablatemp);
			$parte_tabla = explode('</li fin>', $tablatemp);

			// print_r($parte_tabla);die();			
			$tablatemp = '';
			foreach ($parte_tabla as $key1 => $value1) {
				if($key-1 == $key1)
				{

				// print_r($parte_tabla[$key]);die();
					$tablatemp.='<li class="fa"><a href="#">'.$value['Codigo'].'- '.$value['Cuenta'].'</a></li fin>'.$parte_tabla[$key1];
					// print_r($tablatemp);die();
				}else
				{
					$tablatemp.=$parte_tabla[$key1].'</li fin>';
				}
			}
			// print_r($tablatemp);die();

			// print_r($tablatemp);
			// die();
			//$parte_tabla= array_map('trim', explode('</li fin>', $tablatemp));
			//$tablatemp.='<li><a href="#">'.$value['Codigo'].'- '.$value['Cuenta'].'</a></li>';
		}

		// $tablatemp = str_replace($value['Codigo'].'- '.$value['Cuenta'],$value['Codigo'].'- ',$tablatemp);	
	}
}

}

// print_r($tablatemp);die();
 $tabla1 ='<div class="menujq"><ul>';
 $tabla1.=$tablatemp;
 $tabla1.='</ul></div><script  src="../../dist/js/script_acordeon.js"></script>';
 $tabla = $tabla1;

 // $tabla = utf8_encode($tabla);
return $tabla;
	}

function tipo_pago()
{
	$datos = $this->modelo->tipo_pago_();
	return $datos;
}

function tip_cuenta($cuenta)
{
	$datos = TiposCtaStrg($cuenta);
	return $datos;
}


function grabar_cuenta($parametros)
{
	// print_r($parametros);
	$editar = false;
	$ID='';
	if($parametros['OpcG']=='true')
	{
		$TipoDoc = 'G';
	}else
	{
		$TipoDoc = 'D';
	}
	if($parametros['CheqTipoPago'] == 'true')
	{
		$FA_Tipo_Pago = $parametros['DCTipoPago'];
	}else
	{
		$FA_Tipo_Pago = "00";
	}

	$NuevaCta = False;
	$TextPresupuesto = TextoValido($parametros['TextPresupuesto']);
	if($parametros['LabelCtaSup'] == '')
	{
		$LabelCtaSup = '0';
	}else
	{
		$parametros['LabelCtaSup'] = substr($parametros['LabelCtaSup'],0,-1);  
	}
	$Numero = 0;
	$TipoCta = "N";
	// print_r($parametros[])
	$TipoCta = $parametros['LstSubMod'][0];
	if($TipoDoc == 'G')
	{
		$TextConcepto = TextoValido($parametros['TextConcepto'],false,True);
	}else
	{		
		$TextConcepto = TextoValido($parametros['TextConcepto']);
	}
	$formato = $_SESSION['INGRESO']['Formato_Cuentas'];
	$forma = explode('.',$formato);
	$int_cuenta =explode('.',substr($parametros['MBoxCta'],0,-1));
	$cuent = '';
	foreach ($int_cuenta as $key => $value) {
		if(strlen($value) == strlen($forma[$key]))
		{
			$cuent.=$value.'.';
		}else
		{
			$n = str_repeat("0", strlen($forma[$key])-strlen($value));
			$cuent.=$n.''.$value.'.';
		}
	}
	$parametros['MBoxCta'] =$cuent;
   $Codigo1 = substr($parametros['MBoxCta'],0,-1);  
   $Codigo = "C".$Codigo1;
   $Cta_Sup = "C".$parametros['LabelCtaSup'];
   $Cuenta = $Codigo1." - ".$TextConcepto;
  // Mensajes = "Esta seguro de Grabar la cuenta" & vbCrLf _
  //        No. [".$Codigo."] - " & TextConcepto.Text
  // Titulo = "Pregunta de grabación"

   	SetAdoAddNew("Catalogo_Cuentas");

  $cuenta_exist = $this->modelo->cta_existente();
  if(count($cuenta_exist)!=0)
  {
  	$cuenta_exist_1 = $this->modelo->cta_existente($Codigo1);
  	if(count($cuenta_exist_1) !=0)
  	{  	
  		
  	 $Numero = $cuenta_exist_1[0]["Clave"];
  	 $ID =  $cuenta_exist_1[0]["ID"];
	 $editar = true;
             if($parametros['OpcD'] == 'true' And $Numero = 0)
             {
             	$Numero = ReadSetDataNum("Numero Cuenta", True, True);
             }
  	}else
  	{
  		SetAdoFields('Codigo',strval($Codigo1));
        if($parametros['OpcD'] == 'true')
           {
                $Numero = ReadSetDataNum("Numero Cuenta",True,True);
           }
        $NuevaCta = True;
    }
  }else
  {
  	SetAdoFields('Codigo',strval($Codigo1));

    if($parametros['OpcD'] == 'true')
    {
    	 $Numero = ReadSetDataNum("Numero Cuenta", True, True);
    }
    // if($parametros['OpcG'] == true)
    // {
    // 	//AddNewCta "DG";
    // }else
    // {
    // 	//AddNewCta $TipoCta;

    // }  
  	 
  }

 
     // ' MsgBox TipoCta'
      SetAdoFields('Clave',$Numero);
      SetAdoFields('DG',$TipoDoc);
      SetAdoFields('TC',$TipoCta);
      SetAdoFields('ME',(int)($parametros['CheqUS'] === 'true'));
      SetAdoFields('Listar',(int)($parametros['CheqFE'] === 'true'));
      SetAdoFields('Mod_Gastos',(int)($parametros['CheqModGastos']=== 'true'));
      SetAdoFields('Cuenta',$TextConcepto);
      SetAdoFields('Presupuesto',$TextPresupuesto);
      SetAdoFields('Procesado',True);
      SetAdoFields('Periodo_Contable',$_SESSION['INGRESO']['periodo']);
      SetAdoFields('Item',$_SESSION['INGRESO']['item']);
      SetAdoFields('Codigo_Ext',$parametros['TxtCodExt']);
      SetAdoFields('Cta_Acreditar',$parametros['MBoxCtaAcreditar']);
      SetAdoFields('Tipo_Pago',$FA_Tipo_Pago);

     if($parametros['OpcNoAplica'] == 'true')
     {
        SetAdoFields('I_E_Emp', G_NINGUNO);
        SetAdoFields('Con_IESS',(int)('false' === 'true'));
        SetAdoFields('Cod_Rol_Pago',G_NINGUNO);

      }else
     {
         SetAdoFields('Cod_Rol_Pago',Rubro_Rol_Pago($TextConcepto));
         // print_R(Rubro_Rol_Pago($TextConcepto));
         // die();
     	   if($parametros['OpcIEmp']=='true')
     	   {
     		SetAdoFields('I_E_Emp','I');
     		if($parametros['CheqConIESS'] != 'false')
     		{
     			SetAdoFields('Con_IESS',(int)('true'=== 'true'));
     		}else
     		{
     			SetAdoFields('Con_IESS',(int)('false'=== 'true'));
     		}
     	   }else
     	   {
     	   	SetAdoFields('I_E_Emp','E');     		
     	   }
     } 
      SetAdoFields('CC',$parametros['TxtCodExt']);

      
      if($editar == true)
      {
      	
      	SetAdoFieldsWhere('ID',strval($ID));
      	return  SetAdoUpdateGeneric();
      	
      }else
      {
      	return  SetAdoUpdate();
      }
}

function cambiar_cuenta($parametros)
{
	$Codigo2 = $parametros['n_codigo'];
	$Codigo1 = $parametros['codigo'];
	$producto = $parametros['producto'];
	switch ($producto) {
		case 'Catalogo':
		if($Codigo2=='')
		{
			$Codigo2 = G_NINGUNO;
		}
		$datos = $this->modelo->datos_cuenta($Codigo2);
		if(count($datos)> 0 && $Codigo2 != G_NINGUNO)
		{
			 $sql = "UPDATE Catalogo_CxCxP 
                     SET Cta = '".$Codigo2. "' 
                     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
                     AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                     AND Cta = '".$Codigo1. "' ";
                 
                  
                  $sql .= "UPDATE Catalogo_Lineas 
                     SET CxC = '".$Codigo2. "' 
                     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
                     AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                     AND CxC = '".$Codigo1. "' ";
                 
                  
                  $sql.= "UPDATE Catalogo_Lineas 
                     SET CxC_Anterior = '".$Codigo2. "' 
                     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
                     AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                     AND CxC_Anterior = '".$Codigo1. "' ";
                 
                  
                  $sql.= "UPDATE Transacciones 
                     SET Cta = '".$Codigo2. "' 
                     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
                     AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                     AND Cta = '".$Codigo1. "' ";
                 
                  $sql .= "UPDATE Trans_SubCtas 
                     SET Cta = '".$Codigo2. "' 
                     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
                     AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                     AND Cta = '".$Codigo1. "' ";
                 
        // ''          $sql = "UPDATE Trans_Retenciones 
        // ''             SET Cta = '".$Codigo2. "' 
        // ''             WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
        // ''             AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        // ''             AND Cta = '".$Codigo1. "' "
        // ''         
                  
                  $sql .= "UPDATE Facturas 
                     SET Cta_CxP = '".$Codigo2. "' 
                     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
                     AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                     AND Cta_CxP = '".$Codigo1. "' ";
                 
                  
                  $sql .= "UPDATE Trans_Abonos 
                     SET Cta = '".$Codigo2. "' 
                     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
                     AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                     AND Cta = '".$Codigo1. "' ";
                 
                  
                  $sql .= "UPDATE Trans_Abonos 
                     SET Cta_CxP = '".$Codigo2. "' 
                     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
                     AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                     AND Cta_CxP = '".$Codigo1. "' ";

                     return  $this->modelo->cambiar_datoS_cuenta($sql);
		}else
		{
			return -2;
		}
			break;
		
		default:
		$Co_TP = '';
		$Co_Numero = '';
		$Asiento = '';
			if($Codigo2=='')
			{
				$Codigo2 = G_NINGUNO;
			}
				$datos = $this->modelo->datos_cuenta($Codigo2);
		if(count($datos)> 0 && $Codigo2 != G_NINGUNO)
		{
			 $sql = "UPDATE Transacciones
                     SET Cta = '".$Codigo2."'
                     WHERE TP = '".$Co_TP."'
                     AND Numero = ".$Co_Numero. "
                     AND Item = '".$_SESSION['INGRESO']['item']."'
                     AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
                     AND ID = ".$Asiento."
                     AND Cta = '".$Codigo1."' ";
                  
                  
                  $sql .= "UPDATE Trans_SubCtas
                     SET Cta = '".$Codigo2."'
                     WHERE TP = '".$Co_TP."'
                     AND Numero = ".$Co_Numero. "
                     AND Item = '".$_SESSION['INGRESO']['item']."'
                     AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
                     AND Cta = '".$Codigo1."' ";
                  
                  
                  $sql .= "UPDATE Trans_Compras
                     SET Cta_Servicio = '".$Codigo2."'
                     WHERE TP = '".$Co_TP."'
                     AND Numero = ".$Co_Numero. "
                     AND Item = '".$_SESSION['INGRESO']['item']."'
                     AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
                     AND Cta_Servicio = '".$Codigo1."' ";
                  
                  
                  $sql .= "UPDATE Trans_Compras
                     SET Cta_Bienes = '".$Codigo2."'
                     WHERE TP = '".$Co_TP."'
                     AND Numero = ".$Co_Numero. "
                     AND Item = '".$_SESSION['INGRESO']['item']."'
                     AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
                     AND Cta_Bienes = '".$Codigo1."' ";
                  
                  
                  $sql .= "UPDATE Trans_Air
                     SET Cta_Retencion = '".$Codigo2."'
                     WHERE TP = '".$Co_TP."'
                     AND Numero = ".$Co_Numero. "
                     AND Item = '".$_SESSION['INGRESO']['item']."'
                     AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
                     AND Cta_Retencion = '".$Codigo1."' ";
                  
                    return  $this->modelo->cambiar_datoS_cuenta($sql);


		}else
		{
			return -2;
		}

			break;
	}
	// print_r('expression');die();
}
	function eliminar_cuentas($parametros)
	{
		$datos = $this->modelo->transacciones_cta($parametros['codigo']);
		$lista='';
		if(count($datos)>0)
		{
			foreach ($datos as $key => $value) {			
				$lista.= '<li>'.$value['Cta'].' - Cantidad de movimientos:'.$value['Cant_Cta'].'</li>';
			}
		}else
		{
          return $this->modelo->eliminar_cta($parametros['codigo']);                     
		}

		return $lista;

		print_r($datos);die();
	}

	
}
?>


	