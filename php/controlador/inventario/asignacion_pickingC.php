<?php
require_once(dirname(__DIR__, 2) . "/modelo/inventario/asignacion_pickingM.php");
require_once(dirname(__DIR__, 2) . "/modelo/inventario/asignacion_osM.php");
require_once(dirname(__DIR__, 2) . "/modelo/inventario/egreso_alimentosM.php");

$controlador = new asignacion_pickingC();

if (isset($_GET['Beneficiario'])) {
    $query = '';
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    echo json_encode($controlador->Beneficiario($query));
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
/*
if(isset($_GET['tipo_asignacion'])){
    // $parametros = $_POST['parametros'];
    echo json_encode($controlador->tipo_asignacion());
}

*/



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

    function Beneficiario($query)
    {

    	$datos = $this->modelo->tipoBeneficiario($query);

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
                $datos1 = $this->modelo->cargar_asignacion($value['Codigo'],$value['No_Hab'],'F',date('Y-m-d'));
                if(count($datos1)==0)
                {
            	   $lista[] = array('id'=>$value['Codigo'].'-'.$value['No_Hab'],'text'=>$value['Cliente'].' ('.$value['Tipo Asignacion'].')','data'=>$value); 
                }   	
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
        $datos = $this->asignacion->listaAsignacion($parametros['beneficiario'],'K',$parametros['tipo']);

        $detalle = '';
        $ddlGrupoPro = '';
        $total = 0;
        // print_r($parametros);die();
        foreach ($datos as $key => $value) {
            $cant = 0; 
            $cant_ing = $this->modelo->total_ingresados($parametros['beneficiario'],$value['Codigo'],$value['No_Hab']);
            if($cant_ing[0]['Total']!=''){ $cant = $cant_ing[0]['Total'];}
            // print_r($cant_ing);die();
            $detalle.='<div class="row mb-3">                                    
                   <div class="col-sm-4">   
                        <b>Grupo de productos</b>
                        <h4>'.$value['Producto'].'</h4>
                    </div>
                    <div class="col-sm-4" style="padding:0px">                      
                        <b>Cantidad parcial a distribuir</b>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control input-xs" value="'.number_format($value['Cantidad'],2,'.','').'" readonly="">
                            <div class="input-group-addon input-xs">
                                <b>Dif:</b>
                            </div>
                            <input type="text" class="form-control input-xs" value="'.number_format(($value['Cantidad']-$cant),2,'.','').'" readonly>                            
                        </div>
                    </div>              
                    <div class="col-sm-4">                      
                        <b>Comentario de asignacion</b>
                        <input type="text" class="form-control input-xs" value="'.$value['Procedencia'].'">
                    </div>                     
                </div>';
            $ddlGrupoPro.= '<option value="'.$value['Codigo'].'" >'.$value['Producto'].'</option>';
            $total =  $total+number_format($value['Cantidad'],2,'.','');
        }
        $detalle.='<div class="row">                                    
                    <div class="col-sm-4 text-center">
                        <label>Total</label>
                    </div>
                    <div class="col-sm-4" style="padding:0px">      
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control input-xs" value="'.$total.'" readonly>
                            <div class="input-group-addon input-xs">
                                <b>Dif:</b>
                            </div>
                            <input type="text" class="form-control input-xs" readonly>
                            
                        </div>
                    </div>              
                    <div class="col-sm-4">                      
                    </div>                     
                </div>';

        $res = array('detalle'=>$detalle,'ddl'=>$ddlGrupoPro,'total'=>$total);

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
        foreach ($datos as $key => $value) {
           $tr[] = array('id'=>$value['Codigo_Barra'],'text'=>$value['Codigo_Barra']);
        }
        return $tr;
        // print_r($datos);die();
    
    }

    function buscar_producto($parametros)
    {
        $datos = $this->egresos->buscar_producto($parametros['codigo']);
        // print_r($parametros);
        $validado_grupo =1;
        $lista_producto = array();
        foreach ($datos as $key => $value) {
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
        
        // print_r($parametros);die();

        $cant_ing = $this->modelo->total_ingresados($Beneficiario[0],$parametros['CodigoInv'],$Beneficiario[1]);
        $cant_ing = $cant_ing[0]['Total'];
        $stock = $this->modelo->listaAsignacion($Beneficiario[0],$T=false,$parametros['CodigoInv'],$Beneficiario[1]);
        if(isset($stock[0]['Cantidad']))
        {
            $stock = $stock[0]['Cantidad'];
        }

        // print_r($cant_ing);die();
        // print_r($stock);die();
        $cant_ing = $cant_ing+$parametros['Cantidad'];


        // print_r($cant_ing);die();
        
        // print_r($parametros);die();
        if($cant_ing<=$stock)
        {

            $producto = Leer_Codigo_Inv($parametros['CodigoInv'],$parametros['FechaAte']);
            SetAdoAddNew("Trans_Comision");
            SetAdoFields("CodigoC",$Beneficiario[0]);
            SetAdoFields("Cta",$Beneficiario[1]);
            SetAdoFields("Codigo_Inv",$parametros['CodigoInv']);
            SetAdoFields("Total",$parametros['Cantidad']);
            SetAdoFields("Fecha",$parametros['FechaAte']);
            SetAdoFields("Fecha_C",date('Y-m-d'));      
            SetAdoFields("CodBodega",$parametros['codigoProducto']);        
            SetAdoFields("Item",$_SESSION['INGRESO']['item']);
            SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
            SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
            SetAdoFields("T","P");
            
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
        $datos = $this->modelo->cargar_asignacion($Beneficiario[0],$Beneficiario[1],'P',date('Y-m-d'));
        $tbl = '';
        $total = 0;
        foreach ($datos as $key => $value) {
            $producto = $this->modelo->lineasKArdex($value['CodBodega']);           
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
        return array('tabla'=>$tbl,'total'=>$total);
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

    function tipo_asignacion()
    {
        $datos = $this->modelo->tipo_asignacion();
        foreach ($datos as $key => $value) {
            $lista[] = array('ID' =>$value['Cmds'] ,'Proceso'=>$value['Proceso'],'Picture'=>$value['Picture'] );
        }
        return $lista;
    }
    */

    function GuardarPicking($parametros)
    {
        // print_r($parametros);die();
        SetAdoAddNew('Trans_Comision');
        SetAdoFields('T','F');      
       
        SetAdoFieldsWhere('CodigoU',$_SESSION['INGRESO']['CodigoU']);
        SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
        SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']); 
        SetAdoFieldsWhere('CodigoC',$parametros['beneficiario']);  
        SetAdoFieldsWhere('Fecha',$parametros['fecha']);  
        SetAdoFieldsWhere('Cta',$parametros['tipo']);  
        return SetAdoUpdateGeneric();
    }

}