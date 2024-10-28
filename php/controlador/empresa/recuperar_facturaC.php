<?php
include(dirname(__DIR__,2).'/modelo/empresa/recuperar_facturaM.php');
if(!class_exists('autorizacion_sri'))
{
    include(dirname(__DIR__,2).'/comprobantes/SRI/autorizar_sri.php');
}

$controlador = new recuperar_facturaC();

if(isset($_GET['recuperar_factura']))
{	    
	$parametros = $_POST['parametros'];
    echo json_encode($controlador->recuperar($parametros));
}

if(isset($_GET['lista_factura_recuperar']))
{       
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->lista_recuperar($parametros));
}
if(isset($_GET['actualizar_fechas']))
{       
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->actualizar_fechas($parametros));
}
if(isset($_GET['empresas']))
{
    $query = ''; $ciu = ''; $ent = '';
    if(isset($_GET['q'])){ $query = $_GET['q']; }
    if(isset($_GET['ciu'])){ $ciu = $_GET['ciu'];   }
    if(isset($_GET['ent'])){ $ent = $_GET['ent'];   }
    echo json_encode($controlador->empresas($query,$ent,$ciu));
}
class recuperar_facturaC 
{
    private $modelo;
    private $sri;
    function __construct()
	{
        $this->modelo = new recuperar_facturaM();
        $this->sri = new autorizacion_sri();
    }

    function empresas($query,$ent,$ciu)
    {
        $datos = $this->modelo->entidad($query,$ent,$ciu);
        // print_r($dato);die();
        if(count($datos)>0)
        {
                foreach ($datos as $key => $value) {
                $resp[] = array('id'=>$value['Item'],'text'=>$value['Empresa'],'CI'=>$value['RUC_CI_NIC'],'data'=>$value);
            }
        }else
        {
            $resp[0] = array('id'=>'','text'=>'Empresa no encontrada');
        }
        return $resp;
    }

    function actualizar_fechas($parametros)
    {
        $recuperar = 1;
        $TFA = array();
        $item = generaCeros($_SESSION['INGRESO']['item'],3);
        $entidad = generaCeros($_SESSION['INGRESO']['IDEntidad'],3);
        $periodo = $_SESSION['INGRESO']['periodo']; //'.';
        $desde = $parametros['desde'];
        $hasta = $parametros['hasta'];
       
        $lista_faltantes = array(); 

        $serie_lineas = $this->modelo->catalogo_lineas($TC='FA',$item,$periodo,$SerieFactura=false);
        foreach ($serie_lineas as $key => $value) {
            // print_r($value);die();
            $facturas_TD =  $this->modelo->lista_facturas_faltantes($item,$periodo,$desde,$hasta,$value['Serie']);
            if(count($facturas_TD)>0)
            {
                foreach ($facturas_TD as $key => $value) {                    
                    array_push($lista_faltantes,$value);
                }               
            }   
        }

        $success = 1;
        foreach ($lista_faltantes as $key => $value) {
            $dia = substr($value['Clave_Acceso'],0,2);
            $mes = substr($value['Clave_Acceso'],2,2);
            $year = substr($value['Clave_Acceso'],4,4);
            $fecha2 = $year.'-'.$mes.'-'.$dia;

            $datosD[0]['campo'] ='Fecha';            
            $datosD[0]['dato'] = $fecha2;

            $datosW[0]['campo'] ='ID' ;            
            $datosW[0]['valor'] =$value['ID'] ;

            $resp = update_generico($datosD,'Trans_Documentos',$datosW);
            if($resp!=1)
            {
                $success = 0;
            }
        }

        return $success;
    }

    function lista_recuperar($parametros)
    {
        $recuperar = 1;
        $TFA = array();
        $item = generaCeros($_SESSION['INGRESO']['item'],3);
        $entidad = generaCeros($_SESSION['INGRESO']['IDEntidad'],3);
        $periodo = $_SESSION['INGRESO']['periodo']; //'.';
        $desde = $parametros['desde'];
        $hasta = $parametros['hasta'];
       
        $lista_faltantes = array(); 

        $serie_lineas = $this->modelo->catalogo_lineas($TC='FA',$item,$periodo,$SerieFactura=false);
        foreach ($serie_lineas as $key => $value) {
            // print_r($value);die();
            $facturas_TD =  $this->modelo->lista_facturas_faltantes($item,$periodo,$desde,$hasta,$value['Serie']);
            if(count($facturas_TD)>0)
            {
                foreach ($facturas_TD as $key => $value) {                    
                    array_push($lista_faltantes,$value);
                }
               
            }   
        }
        $tr='';
        foreach ($lista_faltantes as $key => $value) {
            $tr.='<tr>
            <td>'.$value['Fecha']->format('Y-m-d').'</td>
            <td>'.$value['Clave_Acceso'].'</td>
            <td>'.$value['Serie'].'</td>
            <td>'.$value['Documento'].'</td>
            </tr>';
        }

        return array('tabla'=>$tr,'num'=>count($lista_faltantes));

    }


    function recuperar($parametros)
    {
    	$recuperar = 1;
        $TFA = array();
        $item = generaCeros($_SESSION['INGRESO']['item'],3);
        $entidad = generaCeros($_SESSION['INGRESO']['IDEntidad'],3);
        $periodo = $_SESSION['INGRESO']['periodo']; //'.';
        $desde = $parametros['desde'];
        $hasta = $parametros['hasta'];
       
       
        $lista_faltantes = array(); 

        $serie_lineas = $this->modelo->catalogo_lineas($TC='FA',$item,$periodo,$SerieFactura=false);
        foreach ($serie_lineas as $key => $value) {
            // print_r($value);die();
            $facturas_TD =  $this->modelo->lista_facturas_faltantes($item,$periodo,$desde,$hasta,$value['Serie']);
            if(count($facturas_TD)>0)
            {
                foreach ($facturas_TD as $key => $value) {                    
                    array_push($lista_faltantes,$value);
                }
               
            }   
        }

        // print_r($lista_faltantes);die();

        if(count($lista_faltantes)>0)
        {
            foreach ($lista_faltantes as $key => $value)
            {
                if($value['Documento']!='' || $value['Documento']!=null)
                {   
                    // print_r($value);die();
                    $factura = $this->modelo->facturas_a_recuperar($item,$periodo,$value['Serie'],$value['Documento'],$value['Clave_Acceso'],$desde=false,$hasta=false);
                    // print_r($factura);die();
                    if (count($factura)>0) 
                    {
                        $respuesta = $this->sri->recuperar_xml_a_factura($factura[0]['Documento_Autorizado'],$value['Clave_Acceso'],$entidad,$item);
                        // print_r($respuesta);die();
                        if($respuesta==-2)
                        {
                            return -2;
                        }
                            $data =  $this->sri->recuperar_cliente_xml_a_factura($factura[0]['Documento_Autorizado'],$value['Clave_Acceso'],$entidad,$item);
                            // print_r($data);die();
                        $lineas = $this->sri->catalogo_lineas_sri('FA',$value['Serie'],$factura[0]['Fecha']->format('Y-m-d') ,$factura[0]['Fecha']->format('Y-m-d'),1);
                        if(count($lineas)==0)
                        {
                            $lineas = $this->sri->catalogo_lineas_sri('FA',$value['Serie'],date('Y-m-d'),date('Y-m-d'),1);
                        }

                        if($respuesta==1)
                        {
                            // print_r('sdasdasdasd');die();
                            $TFA['Factura'] = $value['Documento'];
                            $TFA['TC'] = 'FA';
                            $TFA['Serie'] = $value['Serie'];
                            $TFA['Autorizacion'] = $value['Clave_Acceso'];
                            $TFA['CodigoC'] = $data['Codigo'];
                            $TFA['ClaveAcceso'] = $value['Clave_Acceso'];
                            $TFA['Cta_CxP'] = $lineas[0]['CxC'];
                            $TFA['Porc_IVA'] = $_SESSION['INGRESO']['porc'];
                            $TFA['Fecha'] = $data['Fecha'];
                            // print_r($TFA);die();
                            if(Grabar_Factura1($TFA)!=1)
                            {
                                $respuesta =-1;
                            }
                        }else
                        {
                            //print_r('dasdas');die();
                            echo 'no se pudo recuperar lineas de factura';
                        }
                    }
                }
                
                // if($key==20)
                // {
                //      print_r('diez');die();
                // }
            }
            // print_r('uno');die();
            return $respuesta;
        }else
        {
            return -3;
        }


        // print_r($factura);die();
    }
    

   

}



?>