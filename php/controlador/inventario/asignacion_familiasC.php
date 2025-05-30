<?php
 // DESARROLLADOR     : Javier Farinango
 // FECHA CREACION    : 02/04/2025
 // FECHA MODIFICACION: 02/04/2025 - 02/04/
 // DESCIPCION        : pantalla de asignaciones  

require_once(dirname(__DIR__, 2) . "/modelo/inventario/asignacion_familiasM.php");
require_once(dirname(__DIR__, 2) . "/modelo/inventario/egreso_alimentosM.php");
require_once(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");

$controlador = new asignacion_familiasC();
if (isset($_GET['addAsignacionFamilias'])) {
    $parametros = $_POST['param'];
    echo json_encode($controlador->addAsignacion($parametros));
}
if(isset($_GET['listaAsignacion'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->listaAsignacion($parametros));
}
if(isset($_GET['GuardarAsignacion'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->GuardarAsignacion($parametros));
}
if(isset($_GET['cargarOrden'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->cargarOrden($parametros));
}
if(isset($_GET['ddl_asignaciones_fam'])){
    $parametros = array();
    if(isset($_POST['param']))
    {
        $parametros = $_POST['param'];
    }
    echo json_encode($controlador->ddl_asignaciones_fam($parametros));
}
if(isset($_GET['agregar_picking'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->agregar_picking($parametros));
}
if(isset($_GET['cargar_asignacion'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cargar_asignacion($parametros));
}
if(isset($_GET['GuardarPicking'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->GuardarPicking($parametros));
}
if(isset($_GET['tipo_asignacion'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->tipo_asignacion($parametros));
}



class asignacion_familiasC
{
    private $modelo;
    private $sri;
    private $egresos;

    public function __construct()
    {
        $this->sri = new autorizacion_sri();
        $this->modelo = new asignacion_familiasM();
        $this->egresos = new egreso_alimentosM();

    }

    function ddl_asignaciones_fam($parametros)
    {
        $datos = $this->modelo->listaAsignacionUnicos(false,'K');
        $lista = array();
        foreach ($datos as $key => $value) {
            $integrantes = $this->modelo->integrantes_Grupo($value['CodigoB']);
            $value['NoGrupoInt'] = count($integrantes);
            $lista[] = array('text' =>$value['Orden_No'] ,'id'=>$value['Orden_No'].'-'.$value['No_Hab'],'data'=>$value );
        }
        return $lista;
        // print_r($datos);die();
    }

    function addAsignacion($parametros)
    {

        // print_r($parametros);die();

        $producto = Leer_Codigo_Inv($parametros['Codigo'],$parametros['FechaAte']);

        // print_r($parametros);die();
        SetAdoAddNew("Detalle_Factura");
        SetAdoFields("TC","OF");
        SetAdoFields("T","P");  // p =>pendiente
        // SetAdoFields("CodigoC",$parametros['beneficiarioCodigo']);
        
        SetAdoFields("CodigoL",$parametros['programa']);
        SetAdoFields("CodigoB",$parametros['grupo']);

        SetAdoFields("Procedencia",$parametros['Comentario']);
        SetAdoFields("Codigo",$parametros['Codigo']);
        SetAdoFields("Producto",$parametros['Producto']);
        SetAdoFields("Cantidad",$parametros['CantidadCp']);
        SetAdoFields("Cant_Hab",number_format($parametros['Cantidad'],2,'.',''));
        SetAdoFields("Precio",number_format($producto['datos']['PVP'],2,'.',''));
        SetAdoFields("Total",number_format($producto['datos']['PVP']*$parametros['Cantidad'],2,'.',''));
        SetAdoFields("Fecha",$parametros['FechaAte']);
        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
        SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
        SetAdoFields("No_Hab",$parametros['asignacion']);
        SetAdoFields("Orden_No",str_replace('.',"_",$parametros['grupo']).'_'.str_replace('-',"", $parametros['FechaAte']));
        
        return SetAdoUpdate();
    }

    function cargar_asignacion($parametros)
    {        
        $Beneficiario = explode('-',$parametros['beneficiario']);
        $datos = $this->modelo->cargar_asignacion($Beneficiario[0],$Beneficiario[1],'P',$parametros["FechaAte"]);
        $tbl = '';
        $total = 0;
        foreach ($datos as $key => $value) {

            $producto = $this->modelo->lineasKArdex($value['CodBodega']);   
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


        return array('tabla'=>$datos,'total'=>$total);
    }


    function listaAsignacion($parametros)
    {
        // print_r($parametros);die();
        $tr = '';
        $cantidad = 0;
        $res = array();
        $orden = str_replace('.',"_",$parametros['grupo']).'_'.str_replace('-',"", $parametros['fecha']);
        $datos = $this->modelo->listaAsignacion($orden,'P',false,$parametros['tipo']);
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

        $res = array('tabla'=>$datos,'cantidad'=>$cantidad);

        return $res;
        // print_r($datos);die();
    }

    function GuardarAsignacion($parametros)
    {
        // print_r($parametros);die();        
        $orden = str_replace('.',"_",$parametros['grupo']).'_'.str_replace('-',"", $parametros['fecha']);
        SetAdoAddNew('Detalle_Factura');
        SetAdoFields('T','K');      
        SetAdoFields('Ruta',$parametros['comentario']);  
       
        SetAdoFieldsWhere('CodigoU',$_SESSION['INGRESO']['CodigoU']);
        SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
        SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']); 
        SetAdoFieldsWhere('orden',$orden);  
        SetAdoFieldsWhere('No_Hab',$parametros['tipo']);  
        return SetAdoUpdateGeneric();
    }

    function cargarOrden($parametros)
    {
        // print_r($parametros);die();
        $tr = '';
        $cantidad = 0;
        $res = array();
        $datos = $this->modelo->listaAsignacion($parametros['orden'],'K',false,$parametros['tipo'],$parametros['Fecha_asig']);
        // print_r($datos);die();

        $detalle = '';
        $ddlGrupoPro = '';
        $total = 0;
        $ctotal = 0;
        // print_r($datos);die();
        foreach ($datos as $key => $value) {
            $cant = $value['Cant_Hab']; 
            $cant_ing = $this->modelo->total_ingresados($parametros['orden'],$value['Codigo'],$value['No_Hab']);
            // print_r($cant_ing);die();
            if($cant_ing[0]['Total']!=''){ 
                $c = ($value['Cant_Hab']-$cant_ing[0]['Total']);
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
                            <input type="text" class="form-control form-control-sm" value="'.number_format($value['Cant_Hab'],2,'.','').'" readonly="">
                            <span class="input-group-text"><b>Dif:</b></span>
                            <input type="text" class="form-control form-control-sm" value="'.number_format(($cant),2,'.','').'" readonly>                            
                        </div>
                    </div>
                    <div class="col-sm-6">                      
                        <input type="text" class="form-control form-control-sm" value="'.$value['Procedencia'].'">
                    </div>                               
                </div>';
            $ddlGrupoPro.= '<option value="'.$value['Codigo'].'" >'.$value['Producto'].'</option>';
            $total =  $total+number_format($value['Cant_Hab'],2,'.','');
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



    function agregar_picking($parametros)
    {

        // print_r($parametros);die();
        $asignacion = explode('-', $parametros['asignacion']);
        $producto = $this->modelo->cargar_asignacion($asignacion[0],$asignacion[1],'P',$parametros['FechaAsig'],$parametros['codBarras']);
        if(count($producto)==0)
        {

            $Beneficiario = explode('-',$parametros['asignacion']);
            $stock = 0; 
            // buscar producto bodega
             $bode = $this->egresos->buscar_producto(false,$parametros['codigoProducto']);

            // cantidad ingresada
            $cant_ing = $this->modelo->total_ingresados($Beneficiario[0],$parametros['CodigoInv'],$Beneficiario[1],$parametros['FechaAsig']);
            $cant_ing = $cant_ing[0]['Total'];

            // cantida que se pide
            $stock = $this->modelo->listaAsignacion($Beneficiario[0],$T=false,$parametros['CodigoInv'],$Beneficiario[1],$parametros['FechaAsig']);
            if(isset($stock[0]['Cantidad']))
            {
                $stock = $stock[0]['Cant_Hab'];
            }

            // print_r($cant_ing);die();
            // print_r($stock);die();
            $cant_ing = $cant_ing+$parametros['Cantidad'];


            // print_r($cant_ing);
            // print_r('-'.$stock);
            
            // print_r($bode);
            // die();
            if($cant_ing<=$stock)
            {

                $producto = Leer_Codigo_Inv($parametros['CodigoInv'],$parametros['FechaAte']);
                SetAdoAddNew("Trans_Comision");
                SetAdoFields("Codigo_Barra",$parametros['codBarras']);
                SetAdoFields("Cta",$Beneficiario[1]);
                SetAdoFields("Codigo_Inv",$parametros['CodigoInv']);
                SetAdoFields("Total",$parametros['Cantidad']);
                SetAdoFields("Fecha",$parametros['FechaAte']);
                SetAdoFields("Fecha_A",$parametros['FechaAsig']);
                SetAdoFields("Fecha_C",date('Y-m-d'));      
                SetAdoFields("CodBodega",$bode[0]['Codigo_Barra']);        
                SetAdoFields("Item",$_SESSION['INGRESO']['item']);
                SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
                SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
                SetAdoFields("T","P");
                SetAdoFields("TP","OF");
                SetAdoFields("Orden_No",$Beneficiario[0]);
                
                return SetAdoUpdate();
            }else{ return -2; }
        }else
        {
            return -3;
        }
    }

    function GuardarPicking($parametros)
    {
        
        SetAdoAddNew('Detalle_Factura');
        SetAdoFields('T','KF');      
        SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
        SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']); 
        SetAdoFieldsWhere('Orden_No',$parametros['orden']);  
        SetAdoFieldsWhere('Cta',$parametros['tipo']);  
        SetAdoFieldsWhere('Fecha_A',$parametros['fechaAsi']);  
        // SetAdoFieldsWhere('Fecha',$parametros['fechaAsi']);   
        
        SetAdoUpdateGeneric();


        SetAdoAddNew('Trans_Comision');
        SetAdoFields('T','F');      
        SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
        SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']); 
        SetAdoFieldsWhere('Orden_No',$parametros['orden']);  
        SetAdoFieldsWhere('Cta',$parametros['tipo']);  
        SetAdoFieldsWhere('Fecha_A',$parametros['fechaAsi']);  
        // SetAdoFieldsWhere('Fecha',$parametros['fechaAsi']);   

        return SetAdoUpdateGeneric();
    }

    function tipo_asignacion($parametros)
    {
        $asignaciones = $this->modelo->listaAsignacionUnicos($orden=false,$T=false,$tipo=false,$parametros['fecha'],$parametros['grupo']);
        $tipo = $this->modelo->tipo_asignacion();
        $lista = array();
        foreach ($tipo as $key => $value) {
            $encontrado = 0;
            foreach ($asignaciones as $key2 => $value2) {
                if($value2['No_Hab']==$value['Cmds'])
                {
                    $encontrado = 1;
                    break;
                }
            }
            if($encontrado==0)
            {
                $lista[] = $value;
            }
        }
        return $lista;

    }


}