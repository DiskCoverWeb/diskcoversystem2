<?php
require_once(dirname(__DIR__, 2) . "/modelo/inventario/asignacion_osM.php");
require_once(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");

$controlador = new asignacion_osC();
if (isset($_GET['initPAge'])) {
    $parametros = $_POST['parametros'];
    $controlador->initPAge($parametros);
}
if (isset($_GET['Beneficiario'])) {
    $query = '';
    $dia = '';
    if (isset($_GET['query'])) {  $query = $_GET['query'];   }
    if (isset($_GET['dia'])) {  $dia =   $_GET['dia'];   }

    // print_r($_GET);die();
    echo json_encode($controlador->tipoBeneficiario($query,$dia));
}


if (isset($_GET['Beneficiario_new'])) {
    $query = '';
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    echo json_encode($controlador->agregar_nuevo_beneficiario($query));
}

if(isset($_GET['datosExtra'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->datosExtra($parametros));
}

if(isset($_GET['listaAsignacion'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->listaAsignacion($parametros));
}
if(isset($_GET['addAsignacion'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->addAsignacion($parametros));
}
if(isset($_GET['addAsignacionFamilias'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->addAsignacionFamilias($parametros));
}
if(isset($_GET['eliminarLinea'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminarLinea($parametros));
}
if(isset($_GET['Codigo_Inv_stock'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Codigo_Inv_stock($parametros));
}
if(isset($_GET['llenarCamposPoblacion'])){
    $valor = $_POST['valor'];
    echo json_encode($controlador->llenarCamposPoblacion($valor));
}
if(isset($_GET['GuardarAsignacion'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->GuardarAsignacion($parametros));
}
if(isset($_GET['tipo_asignacion'])){
    // $parametros = $_POST['parametros'];
    echo json_encode($controlador->tipo_asignacion());
}
if(isset($_GET['autocom_pro'])){
    $query = '';
    if(isset($_GET['q']))
    {
        $query = $_GET['q'];
    }
    // $parametros = $_POST['parametros'];
    echo json_encode($controlador->autocom_pro($query));
}

if(isset($_GET['asignar_beneficiario'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->asignar_beneficiario($parametros));
}

if(isset($_GET['eliminar_asignacion_beneficiario'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminar_asignacion_beneficiario($parametros));
}


class asignacion_osC
{
    private $modelo;
    private $sri;

    public function __construct()
    {
        $this->sri = new autorizacion_sri();
        $this->modelo = new asignacion_osM();

    }

    /**
     * 
     * @return array
     * @throws Exception Cuando no se encuentran datos
     */


    function initPAge($parametros)
    {
        // print_r($parametros);die();
          $this->modelo->cambiar_estado_all();
          $clientes_dia = $this->modelo->Cliente_datos_Extra($parametros['dia']);
          foreach ($clientes_dia as $key => $value) {
            SetAdoAddNew("Clientes");             
            SetAdoFields("Estado",'1');
            SetAdoFieldsWhere('Codigo',$value['Codigo']);
            SetAdoUpdateGeneric();
          }
           
    }
    // function tipoBeneficiario($query,$dia)
    // {
    //     //encero los que estan con activo a clientes
          
    //         $datos = $this->modelo->tipoBeneficiario($query,1,$dia);
    //         // print_r($datos);
    //         $res = array();
    //         if (count($datos) == 0) {
    //             throw new Exception('No se encontraron datos');
    //         }

    //         $cant_asig = $this->modelo->tipo_asignacion();
    //         $cant_asig = count($cant_asig);
    //         foreach ($datos as $key => $value) {
    //             $asignacionesLis =$this->modelo->asignaciones_hechas($value['Codigo']);                
    //             $asignaciones = count($asignacionesLis);

               
    //                 // print_r($dia);die();
    //                 $res[] = array( 'id' => $value['Codigo'],'text' => $value['Cliente'],
    //                     'CodigoA' => $value['CodigoA'],
    //                     'CI_RUC' => $value['CI_RUC'],
    //                     'Fecha_Atencion' => $value['Fecha_Registro']->format('Y-m-d'),
    //                     'Dia_Entrega' => $dia[0],
    //                     'Hora_Entrega' => $value['Fecha_Registro']->format('H:i'),
    //                     'Envio_No' => $value['Envio_No'],
    //                     'Frecuencia' => $value['Frecuencia'],
    //                     'Beneficiario' => $value['Beneficiario'],
    //                     'No_Soc' => $value['No_Soc'],
    //                     'Area' => $value['Area'],
    //                     'Acreditacion' => $value['Acreditacion'],
    //                     'AccionSocial' => $value['AccionSocial'],
    //                     'Tipo' => $value['Tipo'],
    //                     'Cod_Fam' => $value['Cod_Fam'],
    //                     'TipoAtencion' => $value['TipoAtencion'],
    //                     'Salario' => $value['Salario'],
    //                     'CodigoACD' => $value['CodigoACD'],
    //                     'TipoEntega' => $value['TipoEntega'],
    //                     'Descuento' => $value['Descuento'],
    //                     'Evidencias' => $value['Evidencias'],
    //                     'CodTipoBene' => $value['Actividad'],
    //                     'TipoBene' => $value['TipoBene'],
    //                     'Color'=>$value['Color'],
    //                     'Picture'=>$value['Picture'],
    //                     'Estado'=>$value['Estado'],
    //                     'Hora'=>$value['Hora'],
    //                     'vulneravilidad'=>$value['vulnerabilidad'],
    //                     // 'InfoNutri'=>$value['Observaciones'],
    //                     'asignaciones_hechas' =>$asignacionesLis,
    //                 );
            
    //         }

    //         return $res;
        
    // }

    function tipoBeneficiario($query,$dia)
    {
        //encero los que estan con activo a clientes
          
            $datos = $this->modelo->tipoBeneficiario($query,1,$this->sri->quitar_carac($dia));
            // print_r($datos);
            $res = array();
            if (count($datos) == 0) {
                throw new Exception('No se encontraron datos');
            }

            $cant_asig = $this->modelo->tipo_asignacion();
            $cant_asig = count($cant_asig);
            foreach ($datos as $key => $value) {

                $data = $this->cargar_datos($value['Codigo']);
                $value1 = array_merge($value,$data);

                $asignacionesLis =$this->modelo->asignaciones_hechas($value['Codigo']);                
                $asignaciones = count($asignacionesLis);               
                    // print_r($dia);die();
                    $value1['asignaciones_hechas'] =  $asignacionesLis;
                    $res[] = array( 'id' => $value['Codigo'],'text' => $value['Cliente'],'data'=>$value1);
            
            }

            // print_r($res);die();

            return $res;
        
    }

    function cargar_datos($codigo)
    {
        $estado = '.';
        $Fecha_Registro = '.';
        $Envio_No ='.';
        $CodigoACD ='.';
        $Beneficiario ='.';
        $No_Soc = '.';
        $Area = '.';
        $Acreditacion ='.';
        $Tipo = '.';
        $Cod_Fam ='.';
        $Salario ='.';
        $Descuento ='.';
        $Evidencias = '.';
        $Item ='.';
        $Hora ='.';
        $CodVulnera = '.';
        $Hora_Ent = '.';
        $frecuencia = '.';
        $TipoEntega = '.';
        $AccionSocial = '.';
        $TipoAtencion = '.';
        $vulnerabilidad = '.';
        $TipoBene = '.';
        $Color = '.';
        $Picture = '.';


        $estado = $this->modelo->estado($codigo);
        if(count($estado)>0)
        {
            $Fecha_Registro = $estado[0]['Fecha_Registro']->format('Y-m-d');
            $Envio_No = $estado[0]['Envio_No'];
            $CodigoACD = $estado[0]['CodigoACD'];
            $Beneficiario = $estado[0]['Beneficiario'];
            $No_Soc = $estado[0]['No_Soc'];
            $Area = $estado[0]['Area'];
            $Acreditacion = $estado[0]['Acreditacion'];
            $Tipo = $estado[0]['Tipo'];
            $Cod_Fam = $estado[0]['Cod_Fam'];
            $Salario = $estado[0]['Salario'];
            $Descuento = $estado[0]['Descuento'];
            $Evidencias = $estado[0]['Evidencias'];
            $Item = $estado[0]['Item'];
            $Hora = $estado[0]['Hora'];
            $CodVulnera = $estado[0]['CodVulnera'];
            $Hora_Ent = $estado[0]['Hora_Ent'];
            $estado =  $estado[0]['Estado'];
        }
        $frecuencia = $this->modelo->Frecuencia($codigo);
        if(count($frecuencia)>0)
        {
            $frecuencia = $frecuencia[0]['Frecuencia'];
        }
        $TipoEntega = $this->modelo->TipoEntega($codigo);
        if(count($TipoEntega)>0)
        {
            $TipoEntega = $TipoEntega[0]['TipoEntega'];
        }
        $AccionSocial = $this->modelo->AccionSocial($codigo);
        if(count($AccionSocial)>0)
        {
             $AccionSocial =  $AccionSocial[0]['AccionSocial'];
        }
        $TipoAtencion = $this->modelo->TipoAtencion($codigo);
        if(count($TipoAtencion)>0)
        {
            $TipoAtencion = $TipoAtencion[0]['TipoAtencion'];
        }
        $vulnerabilidad = $this->modelo->vulnerabilidad($codigo);
        if(count($vulnerabilidad)>0)
        {
            $vulnerabilidad = $vulnerabilidad[0]['vulnerabilidad'];
        }
        $TipoBene = $this->modelo->TipoBene($codigo);
        if(count($TipoBene)>0)
        {
            $Color = $TipoBene[0]['Color'];
            $Picture = $TipoBene[0]['Picture'];
            $TipoBene = $TipoBene[0]['TipoBene'];
        }

       
        $data = array('Estado'=>$estado,
                    'Fecha_Registro'=>$Fecha_Registro,
                    'Envio_No'=>$Envio_No ,
                    'CodigoACD'=>$CodigoACD ,
                    'Beneficiario'=>$Beneficiario ,
                    'No_Soc'=>$No_Soc,
                    'Area'=>$Area,
                    'Acreditacion'=>$Acreditacion ,
                    'Tipo'=>$Tipo,
                    'Cod_Fam'=>$Cod_Fam,
                    'Salario'=>$Salario,
                    'Descuento'=>$Descuento,
                    'Evidencias'=>$Evidencias,
                    'Item'=>$Item,
                    'Hora'=>$Hora,
                    'CodVulnera'=>$CodVulnera,
                     'Hora_Ent'=>$Hora_Ent,
                     'Frecuencia'=>$frecuencia,
                     'TipoEntega'=>$TipoEntega,
                     'AccionSocial'=>$AccionSocial,
                     'TipoAtencion'=>$TipoAtencion,
                     'Vulnerabilidad'=>$vulnerabilidad,
                     'TipoBene'=>$TipoBene,
                     'Color'=>$Color,
                     'Picture'=>$Picture,
                    );        
        return $data;

    }

    function AddBeneficiario($query)
    {

            $datos = $this->modelo->tipoBeneficiario($query);
            // print_r($datos);
            $res = array();
            if (count($datos) == 0) {
                throw new Exception('No se encontraron datos');
            }

            $cant_asig = $this->modelo->tipo_asignacion();
            $cant_asig = count($cant_asig);
            foreach ($datos as $value) 
            {
                $asignacionesLis =$this->modelo->asignaciones_hechas($value['Codigo']);                
                $asignaciones = count($asignacionesLis);

                $dia =  BuscardiasSemana($value['Dia_Ent']);
                // print_r($diaActual);
                // print_r($dia);
                // print_r($asignaciones);
                // print_r($cant_asig.'\n');
                if($diaActual==$dia[1] && $asignaciones != $cant_asig)
                {
                    // print_r($dia);die();
                    $res[] = array(
                        'id' => $value['Codigo'],
                        'text' => $value['Cliente'],
                        'CodigoA' => $value['CodigoA'],
                        'CI_RUC' => $value['CI_RUC'],
                        'Fecha_Atencion' => $value['Fecha_Registro']->format('Y-m-d'),
                        'Dia_Entrega' => $dia[0],
                        'Hora_Entrega' => $value['Fecha_Registro']->format('H:i'),
                        'Envio_No' => $value['Envio_No'],
                        'Frecuencia' => $value['Frecuencia'],
                        'Beneficiario' => $value['Beneficiario'],
                        'No_Soc' => $value['No_Soc'],
                        'Area' => $value['Area'],
                        'Acreditacion' => $value['Acreditacion'],
                        'AccionSocial' => $value['AccionSocial'],
                        'Tipo' => $value['Tipo'],
                        'Cod_Fam' => $value['Cod_Fam'],
                        'TipoAtencion' => $value['TipoAtencion'],
                        'Salario' => $value['Salario'],
                        'CodigoACD' => $value['CodigoACD'],
                        'TipoEntega' => $value['TipoEntega'],
                        'Descuento' => $value['Descuento'],
                        'Evidencias' => $value['Evidencias'],
                        'CodTipoBene' => $value['Actividad'],
                        'TipoBene' => $value['TipoBene'],
                        'Color'=>$value['Color'],
                        'Picture'=>$value['Picture'],
                        'Estado'=>$value['Estado'],
                        'Hora'=>$value['Hora'],
                        'vulneravilidad'=>$value['vulnerabilidad'],
                        // 'InfoNutri'=>$value['Observaciones'],
                        'asignaciones_hechas' =>$asignacionesLis,
                    );
                }
            }
            return $res; // Ajuste aquí para coincidir con el formato de Select2
    }


    function datosExtra($parametros){
        try{
            $consulta = '(';
            foreach($parametros as $value){
                //if value == '.' ignore the value
                if($value == '.'){
                    continue;
                }
                $consulta .= "'" . $value . "',";
            }
            //remove the last comma
            if(substr($consulta, -1) == ','){
                $consulta = substr($consulta, 0, -1);
            }
            $consulta .= ')';
            //if consulta is equals to () return ('.')
            if($consulta === '()'){
                $consulta = "('.')";
            }
            $datos = $this->modelo->datosExtra($consulta);
            if(count($datos) == 0){
                throw new Exception('No se encontraron datos');
            }
            $res = array();
            return array('result' => '1', 'datos' => $datos);
        }catch(Exception $e){
            return array('result' => '0', 'message' => $e->getMessage());
        }
    }


    function listaAsignacion($parametros)
    {
        $tr = '';
        $cantidad = 0;
        $res = array();
        $datos = $this->modelo->listaAsignacion($parametros['beneficiario'],'.');
        $orden = '.';
        if(isset($datos[0]['Orden_No']))
        {
            $orden = $datos[0]['Orden_No'];
        }
        foreach ($datos as $key => $value) {
            $tr.='<tr>
                    <td>'.($key+1).'</td>
                    <td>'.$value['Producto'].'</td>
                    <td>'.$value['Cantidad'].'</td>
                    <td>'.$value['Procedencia'].'</td>
                    <td><button class="btn btn-danger btn-sm" onclick="eliminar_linea('.$value['ID'].')"><i class="fa fa-trash"></i></button></td>
                </tr>';
                $cantidad+= number_format($value['Cantidad'],2,'.','');
        }

        $res = array('tabla'=>$datos,'cantidad'=>$cantidad,'orden'=>$orden);

        return $res;
        // print_r($datos);die();
    }

    function addAsignacion($parametros)
    {

        $producto = Leer_Codigo_Inv($parametros['Codigo'],$parametros['FechaAte']);
        $datos = $this->modelo->listaAsignacionActual($parametros['beneficiarioCodigo'],'OP',$parametros['asignacion'],$parametros['FechaAte'],$parametros['Codigo']);
        if(count($datos)==0)
        {
            // print_r($parametros);die();
            $order = $parametros['beneficiarioCodigo'].''.str_replace('-',"", $parametros['FechaAte']);

            $data = $this->modelo->buscarAsignacionPrevia($order,$parametros['beneficiarioCodigo'],$parametros['FechaAte'],'OP',$parametros['asignacion']);
            if(isset($data[0]['Orden_No']) && $data[0]['Orden_No']>0)
                {
                    if(strlen($data[0]['Orden_No'])==18)
                    {
                        $order = $order.'1';
                    }else
                    {
                        $ultimo = substr($data[0]['Orden_No'],-1,1);
                        $digito = $ultimo+1;
                        $order = $order.$digito;
                    }
                }

            // print_r($order);die();
            SetAdoAddNew("Detalle_Factura");
            SetAdoFields("TC","OP");
            SetAdoFields("CodigoC",$parametros['beneficiarioCodigo']);
            SetAdoFields("Procedencia",$parametros['Comentario']);
            SetAdoFields("Codigo",$parametros['Codigo']);
            SetAdoFields("Producto",$parametros['Producto']);
            SetAdoFields("Cantidad",$parametros['Cantidad']);
            SetAdoFields("Precio",number_format($producto['datos']['PVP'],$_SESSION['INGRESO']['Dec_PVP'],'','.'));
            SetAdoFields("Total",number_format($producto['datos']['PVP']*$parametros['Cantidad'],2,'','.'));
            SetAdoFields("Fecha",$parametros['FechaAte']);
            SetAdoFields("Item",$_SESSION['INGRESO']['item']);
            SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
            SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
            SetAdoFields("No_Hab",$parametros['asignacion']);
            SetAdoFields("Orden_No",$order);
            
            return SetAdoUpdate();
        }else
        {
            return -2;
        }
    }

    function addAsignacionFamilias($parametros)
    {

        $producto = Leer_Codigo_Inv($parametros['Codigo'],$parametros['FechaAte']);
        // print_r($parametros);die();
        SetAdoAddNew("Detalle_Factura");
        SetAdoFields("TC","OP");
        SetAdoFields("CodigoC",$parametros['beneficiarioCodigo']);
        SetAdoFields("Procedencia",$parametros['Comentario']);
        SetAdoFields("Codigo",$parametros['Codigo']);
        SetAdoFields("Producto",$parametros['Producto']);
        SetAdoFields("Cantidad",$parametros['Cantidad']);
        SetAdoFields("Precio",number_format($producto['datos']['PVP'],$_SESSION['INGRESO']['Dec_PVP'],'','.'));
        SetAdoFields("Total",number_format($producto['datos']['PVP']*$parametros['Cantidad'],2,'','.'));
        SetAdoFields("Fecha",$parametros['FechaAte']);
        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
        SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
        SetAdoFields("No_Hab",$parametros['asignacion']);
        
        return SetAdoUpdate();
    }

    function eliminarLinea($parametros)
    {
        return $this->modelo->eliminarLinea($parametros['id']);
        // print_r($parametros);die();
    }

    function Codigo_Inv_stock($parametros)
    {
        $CodigoDeInv = $parametros['codigo'];
        $FechaInventario = date('Y-m-d');
        $datos = Leer_Codigo_Inv($CodigoDeInv,$FechaInventario);
        return $datos;
    }

    function llenarCamposPoblacion($parametros)
    {
        $tr = '';
        $poblacion = $this->modelo->tipo_poblacion();
        $datos = $this->modelo->llenarCamposPoblacion($parametros);
        if (count($datos)>0) {
            $tr = '';
            foreach ($poblacion as $key => $value) {
                $clave = array_search($value['Cmds'], array_column($datos, 'Cmds'));
                if($clave==''){
                    $item['Hombres']=0; $item['Mujeres']=0; $item['Total']=0;
                }else{
                    $item = $datos[$clave];
                }   
                // print_r($item);die();
                $tr.='<tr><td colspan="2">'.$value['Proceso'].'</td><td>'.$item['Hombres'].'</td><td>'.$item['Mujeres'].'</td><td>'.$item['Total'].'</td></tr>';
            }
        }
        return array('poblacion'=>$poblacion, 'datos'=>$datos);

    }

    function tipo_asignacion()
    {
        $datos = $this->modelo->tipo_asignacion();
        foreach ($datos as $key => $value) {
            $lista[] = array('ID' =>$value['Cmds'] ,'Proceso'=>$value['Proceso'],'Picture'=>$value['Picture'] );
        }
        return $lista;
    }

    function GuardarAsignacion($parametros)
    {
        // print_r($parametros);die();
        SetAdoAddNew('Detalle_Factura');
        SetAdoFields('T','K');      
        SetAdoFields('Ruta',$parametros['comentario']);  
       
        SetAdoFieldsWhere('CodigoU',$_SESSION['INGRESO']['CodigoU']);
        SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
        SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']); 
        SetAdoFieldsWhere('CodigoC',$parametros['beneficiario']);  
        SetAdoFieldsWhere('Fecha',$parametros['fecha']);  
        SetAdoFieldsWhere('Orden_No',$parametros['orden']);  

        return SetAdoUpdateGeneric();
    }

    function autocom_pro($query)
    {
        $datos = $this->modelo->alimentosRecibidoscompra($query);
        $lista = array();
        foreach ($datos as $key => $value) {
            $lista[] = array('id'=>$value['Codigo_Inv'], 'text'=>$value['Producto'],'data'=>$value);
        }

        return $lista;
    }

    function agregar_nuevo_beneficiario($query)
    {
        // print_r('ddd');die();
        $lista = array();
        $datos = $this->modelo->tipoBeneficiario($query,'0');
        // print_r($datos);die();
        foreach ($datos as $key => $value) {
              $lista[] = array('id' => $value['Codigo'],'text' => $value['Cliente']);
        }
        return $lista;
    }

    function asignar_beneficiario($parametros)
    {
        return  $this->modelo->cambiar_estado($parametros['cliente']);
        // print_r($parametros);die();
    }

    function eliminar_asignacion_beneficiario($parametros)
    {       
        return  $this->modelo->cambiar_estado_eliminado($parametros['cliente']);
    }

}