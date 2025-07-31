<?php
 // DESARROLLADOR     : Javier Farinango
 // FECHA CREACION    : 02/04/2025
 // FECHA MODIFICACION: 02/04/2025 - 02/04/
 // DESCIPCION        : pantalla de asignaciones  

require_once(dirname(__DIR__, 2) . "/modelo/inventario/asignacion_pickingM.php");
require_once(dirname(__DIR__, 2) . "/modelo/inventario/asignacion_osM.php");
require_once(dirname(__DIR__, 2) . "/modelo/inventario/egreso_alimentosM.php");

$controlador = new asignacion_pickingC();

if (isset($_GET['Beneficiario'])) {
    $fecha = $_GET['fecha'];
    $query = '';
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    echo json_encode($controlador->Beneficiario($query, $fecha));
}
if (isset($_GET['BeneficiarioPickFac'])) {
    $fecha = $_GET['fecha'];
    $query = '';
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    echo json_encode($controlador->BeneficiarioPickFac($query, $fecha));
}

if (isset($_GET['BeneficiarioPickFacFam'])) {
    $fecha = $_GET['fecha'];
    $query = '';
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    echo json_encode($controlador->BeneficiarioPickFacFam($query, $fecha));
}

if(isset($_GET['datosExtra'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->datosExtra($parametros));
}

if(isset($_GET['cargarOrden'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->cargarOrden($parametros));
}

if(isset($_GET['cargarProductosGrupo'])){
    $parametros = $_GET;
    echo json_encode($controlador->cargarProductosGrupo($parametros));
}

if(isset($_GET['buscar_producto']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->buscar_producto($parametros));
}

if(isset($_GET['agregar_picking'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->agregar_picking($parametros));
}

if(isset($_GET['eliminarLinea'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminarLinea($parametros));
}
if(isset($_GET['cargar_asignacion'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cargar_asignacion($parametros));
}
/*
if(isset($_GET['llenarCamposPoblacion'])){
    $valor = $_POST['valor'];
    echo json_encode($controlador->llenarCamposPoblacion($valor));
}
*/
if(isset($_GET['GuardarPicking'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->GuardarPicking($parametros));
}

if(isset($_GET['eliminarPickingAsig'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminarPickingAsig($parametros));
}

if(isset($_GET['eliminarPickingAsigfam'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminarPickingAsigfam($parametros));
}




class asignacion_pickingC
{
    private $modelo;
    private $asignacion;
    private $egresos;

    public function __construct()
    {

        $this->modelo = new asignacion_pickingM();
        $this->asignacion = new asignacion_osM();
        $this->egresos = new egreso_alimentosM();


    }

    function Beneficiario($query, $fecha)
    {

    	$datos = $this->modelo->tipoBeneficiario($query, $fecha);

        // print_r($datos);die();
    	$lista = array();
        $diaActual =  BuscardiasSemana(date('w'));
        $diaActual = $diaActual[1]+1;
        if($diaActual>6)
        {
            $diaActual = 0;
        }   

    	foreach ($datos as $key => $value) {
            $dia = BuscardiasSemana($value['Dia_Ent']);
            
            $data = $this->cargar_datos($value['Codigo']);
            $value1 = array_merge($value,$data);

            $value['Dia_Ent']  = $dia[0];
            // if($diaActual==$dia[1])
            // {
            //     //buscamos si el usuario ya genero en este dia pedidos para facturar
                // $datos1 = $this->modelo->cargar_asignacion($value['Codigo'],$value['No_Hab'],'F',$fecha);
                // if(count($datos1)==0)
                // {
            	   $lista[] = array('id'=>$value['Codigo'].'-'.$value['No_Hab'].'-'.$value['Orden_No'],'text'=>$value['Cliente'].' ('.$value['Tipo Asignacion'].')','data'=>$value1); 
                // }   	
            // }	
    	}
    	return $lista;
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


        $estado = $this->asignacion->estado($codigo);
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
        $frecuencia = $this->asignacion->Frecuencia($codigo);
        if(count($frecuencia)>0)
        {
            $frecuencia = $frecuencia[0]['Frecuencia'];
        }
        $TipoEntega = $this->asignacion->TipoEntega($codigo);
        if(count($TipoEntega)>0)
        {
            $TipoEntega = $TipoEntega[0]['TipoEntega'];
        }
        $AccionSocial = $this->asignacion->AccionSocial($codigo);
        if(count($AccionSocial)>0)
        {
             $AccionSocial =  $AccionSocial[0]['AccionSocial'];
        }
        $TipoAtencion = $this->asignacion->TipoAtencion($codigo);
        if(count($TipoAtencion)>0)
        {
            $TipoAtencion = $TipoAtencion[0]['TipoAtencion'];
        }
        $vulnerabilidad = $this->asignacion->vulnerabilidad($codigo);
        if(count($vulnerabilidad)>0)
        {
            $vulnerabilidad = $vulnerabilidad[0]['vulnerabilidad'];
        }
        $TipoBene = $this->asignacion->TipoBene($codigo);
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

    
    function BeneficiarioPickFac($query, $fecha)
    {

        $datos = $this->modelo->tipoBeneficiarioPickFac($query, $fecha);

        // print_r($datos);die();
        $lista = array();
        $diaActual =  BuscardiasSemana(date('w'));
        $diaActual = $diaActual[1]+1;
        if($diaActual>6)
        {
            $diaActual = 0;
        }   

        foreach ($datos as $key => $value) {
            $dia = BuscardiasSemana($value['Dia_Ent']);

            $value['Dia_Ent']  = $dia[0];
            // if($diaActual==$dia[1])
            // {
            //     //buscamos si el usuario ya genero en este dia pedidos para facturar
                // $datos1 = $this->modelo->cargar_asignacion($value['Codigo'],$value['No_Hab'],'F',$fecha);
                // if(count($datos1)==0)
                // {
                   $lista[] = array('id'=>$value['Codigo'].'-'.$value['No_Hab'].'-'.$value['Orden_No'],'text'=>$value['Cliente'].' ('.$value['Tipo Asignacion'].')','data'=>$value); 
                // }    
            // }    
        }
        return $lista;
    }


    function BeneficiarioPickFacFam($query, $fecha)
    {

        $datos = $this->modelo->tipoBeneficiarioPickFacFam($query, $fecha);

        // print_r($datos);die();
        $lista = array();
        $diaActual =  BuscardiasSemana(date('w'));
        $diaActual = $diaActual[1]+1;
        if($diaActual>6)
        {
            $diaActual = 0;
        }   

        foreach ($datos as $key => $value) {
            // $dia = BuscardiasSemana($value['Dia_Ent']);

            // $value['Dia_Ent']  = $dia[0];
            // if($diaActual==$dia[1])
            // {
            //     //buscamos si el usuario ya genero en este dia pedidos para facturar
                // $datos1 = $this->modelo->cargar_asignacion($value['Codigo'],$value['No_Hab'],'F',$fecha);
                // if(count($datos1)==0)
                // {

             $lista[] = array('id'=>$value['Orden_No'].'-'.$value['No_Hab'],'text'=>$value['familia'].' - '.$value['grupo'].' ( '.$value['TipoEntega'].')','data'=>$value); 

                   // $lista[] = array('id'=>$value['Codigo'].'-'.$value['No_Hab'].'-'.$value['Orden_No'],'text'=>$value['Proceso'].' ('.$value['Tipo Asignacion'].')','data'=>$value); 
                // }    
            // }    
        }
        return $lista;
    }





    function cargarOrden($parametros)
    {
        // print_r($parametros);die();
        $tr = '';
        $cantidad = 0;
        $res = array();
        $datos = $this->modelo->listaAsignacion($parametros['beneficiario'],'K',false,$parametros['tipo'],$parametros['fecha'],$parametros['orden']);

        $detalle = '';
        $ddlGrupoPro = '';
        $total = 0;
        $ctotal = 0;
        // print_r($datos);die();
        foreach ($datos as $key => $value) {
            $cant = $value['Cantidad']; 
            $cant_ing = $this->modelo->total_ingresados($parametros['beneficiario'],$value['Codigo'],$value['No_Hab'],$value['Fecha']->format('Y-m-d'),$parametros['orden']);
            if($cant_ing[0]['Total']!=''){ 
                $c = ($value['Cantidad']-$cant_ing[0]['Total']);
                $cant = $c;
            }
            // print_r($cant_ing);die();
            $detalle.='<div class="row">                                    
                    <div class="col-sm-3">   
                        <b>Grupo de productos</b>
                    </div>
                    <div class="col-sm-3" style="padding:0px">                      
                        <b>Cantidad parcial a distribuir</b>
                    </div>              
                    <div class="col-sm-6">                      
                        <b>Comentario de asignacion</b>
                    </div>                     
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="h6 text-end">'.$value['Producto'].'</h6>
                    </div>
                    <div class="col-sm-3" style="padding:0px">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-sm" value="'.number_format($value['Cantidad'],2,'.','').'" readonly="">
                            <span class="input-group-text"><b>Dif:</b></span>
                            <input type="text" class="form-control form-control-sm" value="'.number_format(($cant),2,'.','').'" readonly>                            
                        </div>
                    </div>
                    <div class="col-sm-6">                      
                        <input type="text" class="form-control form-control-sm" value="'.$value['Procedencia'].'">
                    </div>                               
                </div>';
            $ddlGrupoPro.= '<option value="'.$value['Codigo'].'" >'.$value['Producto'].'</option>';
            $total =  $total+number_format($value['Cantidad'],2,'.','');
            $ctotal =  $ctotal+number_format($cant,2,'.','');
        }
        $detalle.='<div class="row">                                    
                    <div class="col-sm-3 text-end">
                        <label><b>Total</b></label>
                    </div>
                    <div class="col-sm-3" style="padding:0px">      
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-sm" value="'.$total.'" readonly>
                            <span class="input-group-text"><b>Dif:</b></span>
                            <input type="text" class="form-control form-control-sm" value="'.$ctotal.'" readonly>
                            
                        </div>
                    </div>              
                    <div class="col-sm-6">                      
                    </div>                     
                </div>';

        $res = array('detalle'=>$detalle,'ddl'=>$ddlGrupoPro,'total'=>$total);

        // print_r($res);die();

        return $res;
    }

    function cargarProductosGrupo($parametros)
    {
        // print_r($parametros);die(); 
        $codigo = false;
        if(isset($parametros['query']))
        {
            $codigo = $parametros['query'];
        }
        $datos = $this->modelo->lista_stock_ubicado(false,$codigo,$parametros['grupo']);


        $tr = array();  
        $color2 = '#000000';
        $color = '';
        $fecha_now = new DateTime();
        foreach ($datos as $key => $value) {
            $fecha1 = new DateTime();
            $fecha2 = new DateTime($value['Fecha_Exp']->format('Y-m-d'));
            $diferenciaEnSegundos = $fecha2->getTimestamp() - $fecha1->getTimestamp();

            $dias = intval($diferenciaEnSegundos / 86400);
            // print_r($value);die();
            // if($value['Cod_C']=='AR01')
            // {
            //     $color2 = '#0070C0';
            // }
            if($dias<=0){$color = '#ffff00';}else if ($dias<=8 && $dias>0) { $color = '#ff0000';}

           $tr[] = array('id'=>$value['ID'],'text'=>$value['Codigo_Barra'],'data'=>$value,'fondo'=>$color,'texto'=>$color2);
            $color2 = '#000000';
         $color = '';
        }
        return $tr;
        // print_r($datos);die();    
    }

    function buscar_producto($parametros)
    {
        $datos = $this->egresos->buscar_producto(false,$parametros['codigo']);
       
        // print_r($existencias); die();
        $validado_grupo =1;
        $lista_producto = array();
        foreach ($datos as $key => $value) {
             $existencias = costo_venta($value['Codigo_Inv']);
             // print_r($existencias);die();
             $value['Stock'] = $existencias[0]['Existencia'];

            if($parametros['grupo'] == $value['Codigo_Inv'])
            {
                $value['ubicacion'] =  $this->ruta_bodega($value['CodBodega']);

                $lista_producto[] = $value;
            }else
            {
                $validado_grupo = 0;
            }
        }

        return array('producto'=>$lista_producto,'validado_grupo'=>$validado_grupo);
    }

    function ruta_bodega($padre)
    {
        $datos = explode('.',$padre);
        $camino = '';
        $buscar = '';
        foreach ($datos as $key => $value) {
            $camino.= $value.'.';
            $buscar.= "'".substr($camino, 0,-1)."',";
        }

        $buscar = substr($buscar, 0,-1);
        $pasos = $this->modelo->catalogo_bodetagas($buscar);
        $ruta = '';
        foreach ($pasos as $key => $value) {
            $ruta.=$value['Bodega'].'/';            
        }
        $ruta = substr($ruta,0,-1);
        return $ruta;
    }



    function agregar_picking($parametros)
    {

        $Beneficiario = explode('-',$parametros['beneficiario']);
        $stock = 0; 
        // buscar producto bodega
         $bode = $this->egresos->buscar_producto(false,$parametros['codigoProducto']);

        // cantidad ingresada
         // print_r('ddd');die();
        $cant_ing = $this->modelo->total_ingresados($Beneficiario[0],$parametros['CodigoInv'],$Beneficiario[1],$parametros['FechaAsign'],$Beneficiario[2]);
        $cant_ing = $cant_ing[0]['Total'];

        // cantida que se pide

         // print_r('ddd');die();
        $stock = $this->modelo->listaAsignacion($Beneficiario[0],$T=false,$parametros['CodigoInv'],$Beneficiario[1],$parametros['FechaAsign'],$Beneficiario[2]);
        if(isset($stock[0]['Cantidad']))
        {
            $stock = $stock[0]['Cantidad'];
        }

        // print_r($cant_ing);die();
        // print_r($stock);die();
        $cant_ing = $cant_ing+$parametros['Cantidad'];


        // print_r($cant_ing);
        // print_r($stock);
        
        // print_r($parametros);
        // die();
        if($cant_ing<=$stock)
        {

            $linea_kardex = $this->modelo->buscar_trans_kardex($parametros['codigoProducto']);
            $producto = Leer_Codigo_Inv($parametros['CodigoInv'],$parametros['FechaAte']);

            SetAdoAddNew("Trans_Comision");
            SetAdoFields("CodigoC",$Beneficiario[0]);
            SetAdoFields("Cta",$Beneficiario[1]);
            SetAdoFields("Codigo_Inv",$parametros['CodigoInv']);
            SetAdoFields("Total",$parametros['Cantidad']);
            SetAdoFields("Fecha",$parametros['FechaAte']);
            SetAdoFields("Fecha_A",$parametros['FechaAsign']);
            SetAdoFields("Fecha_C",date('Y-m-d'));      
            SetAdoFields("CodBodega",$linea_kardex[0]['CodBodega']);   
            SetAdoFields("Codigo_Barra",$bode[0]['Codigo_Barra']);        
            SetAdoFields("Item",$_SESSION['INGRESO']['item']);
            SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
            SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
            SetAdoFields("T","P");
            SetAdoFields("Cmds",$linea_kardex[0]['Cmds']);
            SetAdoFields("Orden_No",$Beneficiario[2]);
            
            return SetAdoUpdate();
        }else{ return -2; }
    }

    function eliminarLinea($parametros)
    {
        return $this->modelo->eliminarLinea($parametros['id']);
        // print_r($parametros);die();
    }

    function cargar_asignacion($parametros)
    {        
        $Beneficiario = explode('-',$parametros['beneficiario']);
        $datos = $this->modelo->cargar_asignacion($Beneficiario[0],$Beneficiario[1],'P',$parametros["FechaAte"],$Beneficiario[2]);
        $tbl = '';
        $total = 0;
        // print_r('ss');die();
        foreach ($datos as $key => $value) {

            $producto = $this->modelo->lineasKArdex($value['Codigo_Barra']);   
            // print_r($producto);die();        
            $datos[$key]['Producto'] = $producto[0]['Producto'];
            $datos[$key]['Codigo_Barra'] = $producto[0]['Codigo_Barra'];
            // print_r($producto);die();
            $tbl.='<tr>
                    <td><button class="btn btn-sm btn-danger" onclick="eliminarlinea('.$value['ID'].')"><i class="fa fa-trash"></i></button></td>
                    <td>'.$value['Fecha']->format('Y-m-d').'</td>
                    <td>'.$value['Fecha_C']->format('Y-m-d').'</td>
                    <td>'.$producto[0]['Producto'].'</td>
                    <td>'.$producto[0]['Codigo_Barra'].'</td>
                    <td>'.$value['Nombre_Completo'].'</td>
                    <td>'.$value['Total'].'</td>

                </tr>';
                  $total  =   $total +$value['Total'];
        }

        // print_r($datos);die();


        return array('tabla'=>$datos,'total'=>number_format($total,2,'.',''));
    }
/*
    function llenarCamposPoblacion($parametros)
    {
        $tr = '';
        $poblacion = $this->modelo->tipo_poblacion();
        $datos = $this->modelo->llenarCamposPoblacion($parametros);
        if (count($datos)>0) {
            $tr = '';
            foreach ($poblacion as $key => $value) {
                $clave = array_search($value['Cmds'], array_column($datos, 'Cmds'));
                if($clave=='') 
                    { $item['Hombres']=0; $item['Mujeres']=0; $item['Total']=0;}else{
                $item = $datos[$clave];
            }   
                // print_r($item);die();
                $tr.='<tr><td colspan="2">'.$value['Proceso'].'</td><td>'.$item['Hombres'].'</td><td>'.$item['Mujeres'].'</td><td>'.$item['Total'].'</td></tr>';
            }
        }
        return $tr;

    }
    */

    function eliminarPickingAsig($parametros)
    {

        $data = explode('-', $parametros['idBeneficiario']);
        $beneficiario = $data[0];
        $tipo = $data[1]; 
        return $this->modelo->delete_lineas($parametros['fecha'],$beneficiario,$tipo);
    }

    function eliminarPickingAsigfam($parametros)
    {

        $data = explode('-', $parametros['idBeneficiario']);
        $orden = $data[0];
        $tipo = $data[1]; 
        return $this->modelo->delete_lineasFam($orden,$tipo);
    }

    function GuardarPicking($parametros)
    {
        // print_r($parametros);die();

        SetAdoAddNew('Clientes');
        SetAdoFields('FA',True);      
        SetAdoFields('Calificacion','NDO');  
        SetAdoFieldsWhere('Codigo',$parametros['beneficiario']);     
        SetAdoUpdateGeneric(); 


        SetAdoAddNew('Trans_Comision');
        SetAdoFields('T','F');      
        SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
        SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']); 
        SetAdoFieldsWhere('CodigoC',$parametros['beneficiario']);  
        SetAdoFieldsWhere('Orden_No',$parametros['orden']);  
        SetAdoFieldsWhere('Cta',$parametros['tipo']);  
        // SetAdoFieldsWhere('Fecha',$parametros['fechaAsi']);   
        SetAdoUpdateGeneric(); 

        SetAdoAddNew('Detalle_Factura');
        SetAdoFields('T','KF');      
        SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
        SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']); 
        SetAdoFieldsWhere('CodigoC',$parametros['beneficiario']);  
        SetAdoFieldsWhere('Orden_No',$parametros['orden']);  
        SetAdoFieldsWhere('No_Hab',$parametros['tipo']);  
        // SetAdoFieldsWhere('Fecha',$parametros['fechaAsi']);   
        return SetAdoUpdateGeneric();
    }

}