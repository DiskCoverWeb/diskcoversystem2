<?php 
require_once(dirname(__DIR__,2)."/modelo/facturacion/lineas_cxc_factM.php");
require_once(dirname(__DIR__,3)."/lib/fpdf/generar_codigo_barras.php");


$controlador = new lineas_cxc_factC();
if(isset($_GET['TVcatalogo']))
{
  $parametros = $_POST['parametros'];
  echo json_encode($controlador->TVcatalogo($parametros));
}
if(isset($_GET['guardar']))
{
	$parametros = $_POST;
  echo json_encode($controlador->GrabarArticulos($parametros));
}
if(isset($_GET['detalle']))
{
  $id = $_POST['id'];
  echo json_encode($controlador->detalle_linea($id));
}



/**
 * 
 */
class lineas_cxc_factC
{
	private $modelo;
	private $barras;
	function __construct()
	{
		$this->modelo = new lineas_cxc_factM();
		$this->barras = new generar_codigo_barras();
	}

	function TVcatalogo($parametros)
	{

    // print_r($parametros);die();
    $nl = $parametros['nivel'];
	  $h='';
		if($nl!='')
		{

      if($nl==1)
      {
        $datos = $this->modelo->nivel1();
        foreach ($datos as $key => $value) {
           $h.= '<li  title="Presione Suprimir para eliminar">
                  <label id="label_'.str_replace('.','_','A1_'.$key).$value['Autorizacion'].'" for="A1_'.$key.$value['Autorizacion'].'">'.$value['Autorizacion'].'</label>
                  <input type="checkbox" id="A1_'.$key.$value['Autorizacion'].'" onclick="TVcatalogo(2,\'A1_'.$key.'\',\''.$value['Autorizacion'].'\',\'\',\'\')" />
                 <ol id="hijos_'.str_replace('.','_','A1_'.$key).$value['Autorizacion'].'"></ol></li>';
        }
      }

      if($nl==2)
      {
        $datos = $this->modelo->nivel2($parametros['auto']);
        foreach ($datos as $key => $value) {
           $h.= '<li  title="Presione Suprimir para eliminar">
                  <label id="label_'.str_replace('.','_','A2_'.$key).$parametros['auto'].$value['Serie'].'" for="A2_'.$key.$parametros['auto'].$value['Serie'].'">'.$value['Serie'].'</label>
                  <input type="checkbox" id="A2_'.$key.$parametros['auto'].$value['Serie'].'" onclick="TVcatalogo(3,\'A2_'.$key.'\',\''.$parametros['auto'].'\',\''.$value['Serie'].'\',\'\')" />
                 <ol id="hijos_'.str_replace('.','_','A2_'.$key).$parametros['auto'].$value['Serie'].'"></ol></li>';
        }
      }

      if($nl==3)
      {
        $datos = $this->modelo->nivel3($parametros['auto'],$parametros['serie']);
        foreach ($datos as $key => $value) {
           $h.= '<li  title="Presione Suprimir para eliminar">
                  <label id="label_'.str_replace('.','_','A3_'.$key).$parametros['auto'].$parametros['serie'].$value['Fact'].'" for="A3_'.$key.$parametros['auto'].$parametros['serie'].$value['Fact'].'">'.$value['Fact'].'</label>
                  <input type="checkbox" id="A3_'.$key.$parametros['auto'].$parametros['serie'].$value['Fact'].'" onclick="TVcatalogo(4,\'A3_'.$key.'\',\''.$parametros['auto'].'\',\''.$parametros['serie'].'\',\''.$value['Fact'].'\')" />
                 <ol id="hijos_'.str_replace('.','_','A3_'.$key).$parametros['auto'].$parametros['serie'].$value['Fact'].'"></ol></li>';
        }
      }

      if($nl==4)
      {
        $datos = $this->modelo->nivel4($parametros['auto'],$parametros['serie'],$parametros['fact']);
        foreach ($datos as $key => $value) {
          $h.='<li class="file" id="label_'.str_replace('.','_','A4_'.$key).'_'.$value['ID'].'" title=""><a href="#" onclick="detalle_linea(\''.$value['ID'].'\',\'A4_'.$key.'\')">'.$value['Concepto'].'</a></li>';


           // $h.= '<li  title="Presione Suprimir para eliminar">
           //        <label id="label_'.str_replace('.','_','A4_'.$key).'" for="A4_'.$key.'">'.$value['Concepto'].'</label>
           //        <input type="checkbox" id="A4_'.$key.'" onclick="detalle_linea(\''.$value['ID'].'\')" />
           //       <ol id="hijos_'.str_replace('.','_','A2_'.$key).'"></ol></li>';
        }
      }

		}else
		{
			$codigo = 'A';
		  $detalle = 'AUTORIZACIONES';

			 $h = '<li  title="Presione Suprimir para eliminar">
							    <label id="label_'.str_replace('.','_','A').'" for="A">AUTORIZACIONES</label>
							    <input type="checkbox" id="A" onclick="TVcatalogo(1,\'A\',\'\',\'\',\'\')" />
							   <ol id="hijos_'.str_replace('.','_','A').'"></ol></li>';
		}

		return $h;
	}
	function detalle_linea($id)
	{
    if(is_numeric($id))
    {
      $datos = $this->modelo->Catalogo_Lineas($id);
    }
		return $datos;
	}
	function  GrabarArticulos($parametros)
	{
        $Codigo = $parametros['TextCodigo'];
        $TxtLargo = TextoValido($parametros['TxtLargo']);
        $TxtAncho = TextoValido($parametros['TxtAncho']);
        $TxtAncho =  TextoValido($parametros['TxtEspa']);
        $TxtPosFact  =  TextoValido($parametros['TxtPosFact']);
        if($parametros['CTipo']== ""){$parametros['CTipo'] = "FA";} 

        $datos = $this->modelo->validar_codigo($Codigo);
        SetAdoAddNew("Catalogo_Lineas");
        if(count($datos) <= 0 ){
            control_procesos("F","Creación de Punto de Venta de ".$parametros['CTipo']."-".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos']);
            control_procesos( "F","Creación de Fecha de Vencimiento de ".$parametros['TextCodigo']." ".$parametros['MBFechaVenc']);
            control_procesos( "F","Creación de Autorización de ".$parametros['TextCodigo']." ".$parametros['TxtNumAutor']);
            control_procesos("F","Creación de Serie de ".$parametros['TextCodigo']." ".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos']);
            control_procesos( "F", "Creación de Secuencial Inicial de ".$parametros['TextCodigo']." ".$parametros['TxtNumSerietres1']);

            SetAdoFields("Codigo", $parametros['TextCodigo']);
            SetAdoFields("Item", intval($_SESSION['INGRESO']['item']));
            SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
            if($_SESSION['INGRESO']['Entidad_No'] == "1"){
              $TLValor = $parametros['CheqPuntoEmision']=='on'?1:0;
              SetAdoFields("TL", $TLValor);
            }else{
              SetAdoFields("TL", 1);
            }
            //SetAdoFields("TL", 1);

            $Codigo = "A.".$parametros['TxtNumAutor'].".".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos'].".".$parametros['CTipo'].".".$parametros['TextCodigo'];
            $Cuenta = $parametros['TextLinea'];
        }else{
            control_procesos("F", "Modificación de Punto de Venta de ".$parametros['CTipo']."-".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos']);
            $this->modelo->elimina_linea($Codigo);              
            $datos1 = $this->modelo->validar_codigo($Codigo);
            if($parametros['MBFechaVenc'] <> $datos[0]["Vencimiento"]->format('Y-m-d')){ control_procesos("F", "Modifico: Fecha de Vencimiento de ".$parametros['TextCodigo']." ".$parametros['MBFechaVenc']);}
            if($parametros['TxtNumAutor'] <> $datos[0]["Autorizacion"]){ control_procesos("F", "Modifico: Autorización de ".$parametros['TextCodigo']." ".$parametros['TxtNumAutor']);}
            if($parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos'] <> $datos[0]["Serie"]){ control_procesos("F", "Modifico: Serie de ".$parametros['TextCodigo']." ".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos']);}
            if($parametros['TxtNumSerietres1'] <> $datos[0]["Secuencial"]){ control_procesos("F","Modifico: Secuencial Inicial de ".$parametros['TextCodigo']." ".$parametros['TxtNumSerietres1']);}

            SetAdoFields("Codigo", $parametros['TextCodigo']);
            SetAdoFields("Item", $_SESSION['INGRESO']['item']);
            SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
            if($_SESSION['INGRESO']['Entidad_No'] == "1"){
              $TLValor = $parametros['CheqPuntoEmision']=='on'?1:0;
              SetAdoFields("TL", $TLValor);
            }else{
              SetAdoFields("TL", 1);
            }
        }

        SetAdoFields("Concepto", $parametros['TextLinea']);
        SetAdoFields("CxC", substr($parametros['MBoxCta'], 0, -1));
        SetAdoFields("CxC_Anterior", substr($parametros['MBoxCta_Anio_Anterior'], 0, -1));
        SetAdoFields("Cta_Venta", substr($parametros['MBoxCta_Venta'], 0, -1));
        SetAdoFields("Logo_Factura", $parametros['TxtLogoFact']);
        SetAdoFields("Largo", $parametros['TxtLargo']);
        SetAdoFields("Ancho", $parametros['TxtAncho']);
        SetAdoFields("Espacios", $parametros['TxtEspa']);
        SetAdoFields("Pos_Factura", $parametros['TxtPosFact']);
        SetAdoFields("Pos_Y_Fact", $parametros['TxtPosY']);
        SetAdoFields("Fact_Pag", $parametros['TxtNumFact']);
        SetAdoFields("ItemsxFA", $parametros['TxtItems']);
        SetAdoFields("Fact", $parametros['CTipo']);
        // 'SRI
        SetAdoFields("Fecha", $parametros['MBFechaIni']);
        SetAdoFields("Vencimiento", $parametros['MBFechaVenc']);
        SetAdoFields("Secuencial", $parametros['TxtNumSerietres1']);
        SetAdoFields("Autorizacion", $parametros['TxtNumAutor']);
        SetAdoFields("Serie", $parametros['TxtNumSerieUno'] . $parametros['TxtNumSerieDos']);
        SetAdoFields("Nombre_Establecimiento", $parametros['TxtNombreEstab']);
        SetAdoFields("Direccion_Establecimiento", $parametros['TxtDireccionEstab']);
        SetAdoFields("Telefono_Estab", $parametros['TxtTelefonoEstab']);
        SetAdoFields("Logo_Tipo_Estab", $parametros['TxtLogoTipoEstab']);
        if (isset($parametros['CheqMes']) && $parametros['CheqMes'] != 'false') {
            SetAdoFields("Imp_Mes", 1);
        }
        SetAdoUpdate();

        $datos = $this->modelo->facturas_formato($Codigo,$parametros['TxtNumSerieUno'],$parametros['TxtNumSerieDos'],$parametros['TxtNumAutor']);
        SetAdoAddNew("Facturas_Formatos");
        SetAdoFields("Cod_CxC", $parametros['TextCodigo']);
        SetAdoFields("Item", $_SESSION['INGRESO']['item']);
        SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
        SetAdoFields("Concepto", $parametros['TextLinea']);
        SetAdoFields("Formato_Factura", $parametros['TxtLogoFact']);
        SetAdoFields("Largo", $parametros['TxtLargo']);
        SetAdoFields("Ancho", $parametros['TxtAncho']);
        SetAdoFields("Espacios", $parametros['TxtEspa']);
        SetAdoFields("Pos_Factura", $parametros['TxtPosFact']);
        SetAdoFields("Pos_Y_Fact", $parametros['TxtPosY']);
        SetAdoFields("Fact_Pag", $parametros['TxtNumFact']);
        SetAdoFields("TC", $parametros['CTipo']);
        // 'SRI
        SetAdoFields("Fecha_Inicio", $parametros['MBFechaIni']);
        SetAdoFields("Fecha_Final", $parametros['MBFechaVenc']);
        SetAdoFields("Autorizacion", $parametros['TxtNumAutor']);
        SetAdoFields("Serie", $parametros['TxtNumSerieUno'] . $parametros['TxtNumSerieDos']);
        SetAdoFields("Nombre_Establecimiento", $parametros['TxtNombreEstab']);
        SetAdoFields("Direccion_Establecimiento", $parametros['TxtDireccionEstab']);
        SetAdoFields("Telefono_Estab", $parametros['TxtTelefonoEstab']);
        SetAdoFields("Logo_Tipo_Estab", $parametros['TxtLogoTipoEstab']);
        if(count($datos)<=0)
        {
            SetAdoUpdate();
        }else
        {
            SetAdoFieldsWhere("ID", $datos[0]['ID']);
            SetAdoUpdateGeneric();
        }

        // 'Numeracion de FA,NC,GR,ETC
        switch ($parametros['CTipo']) {
            case 'NC':
            $datos = $this->modelo->NC($parametros['TxtNumSerieUno'],$parametros['TxtNumSerieDos']);
            break;
            case 'GR':
            $datos = $this->modelo->GR($parametros['TxtNumSerieUno'],$parametros['TxtNumSerieDos']);
            break;                
            default:
            $datos = $this->modelo->FACTURAS($parametros['CTipo'],$parametros['TxtNumSerieUno'],$parametros['TxtNumSerieDos']);
            break;
        }
    
        if(count($datos)>0) {
            foreach ($datos as $key => $value) {
                $datos2 = $this->modelo->codigos($value['Periodo'],$value['Item'],$value['TC'],$value['Serie_X']);
                SetAdoAddNew("Codigos");
                if(count($datos2)>0)
                {
                    SetAdoFields("Numero", $value['TC_No']+1);
                    SetAdoFieldsWhere("ID", $datos2[0]['ID']);
                    SetAdoUpdateGeneric();
                }else
                {
                    SetAdoFields("Item", $value['Item']);
                    SetAdoFields("Periodo", $value['Periodo']);
                    SetAdoFields("Concepto", $value['TC']."_SERIE_".$value['Serie_X']);
                    SetAdoFields("Numero", $value['TC_No']+1);
                    SetAdoUpdate();
                }
            }
        }else{
            SetAdoAddNew("Codigos");
            SetAdoFields("Item", $_SESSION['INGRESO']['item']);
            SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
            SetAdoFields("Concepto", $parametros['CTipo'] . "_SERIE_" . $parametros['TxtNumSerieUno'] . $parametros['TxtNumSerieDos']);
            SetAdoFields("Numero", intval($parametros['TxtNumSerietres1']));
            SetAdoUpdate();
        }  

        return 1;

	}
}

?>