<?php
require_once(dirname(__DIR__,2).'/modelo/inventario/picking_productoresAliM.php');
require_once(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");
require_once(dirname(__DIR__, 2) . "/modelo/inventario/egreso_alimentosM.php");

$controlador = new picking_productoresAliC();


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

if(isset($_GET['cargarOrden'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->cargarOrden($parametros));
}

if(isset($_GET['grupoProducto'])){
    // $parametros = $_POST['param'];
    echo json_encode($controlador->grupoProducto());
}

if(isset($_GET['cargarProductosGrupo'])){
    $parametros = $_GET;
    echo json_encode($controlador->cargarProductosGrupo($parametros));
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
    // $parametros = $_POST['parametros'];
    echo json_encode($controlador->tipo_asignacion());
}

class picking_productoresAliC
{
    private $modelo;
    private $sri;
    private $egresos;

    function __construct()
    {
        $this->modelo = new picking_productoresAliM();
        $this->sri = new autorizacion_sri();
        $this->egresos = new egreso_alimentosM();
    }

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
                $asignacionesLis =$this->modelo->asignaciones_hechas($value['Codigo']);                
                $asignaciones = count($asignacionesLis);               
                    // print_r($dia);die();
                    $value['asignaciones_hechas'] =  $asignacionesLis;
                    $res[] = array( 'id' => $value['Codigo'],'text' => $value['Cliente'],'data'=>$value);
            
            }
            return $res;
        
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

    function grupoProducto()
    {
        $datos = $this->modelo->grupoProducto();
        $lineas = array();
        foreach ($datos as $key => $value) {
            $lineas[] = array('codigo'=>$value['Codigo_Inv'],'nombre'=>$value['Producto']);
        }

        return $lineas;
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


    function agregar_picking($parametros)
    {
        // print_r($parametros);die();
        $Beneficiario = explode('-',$parametros['beneficiario']);
        $stock = 0; 
        // buscar producto bodega
         $bode = $this->egresos->buscar_producto(false,$parametros['codigoProducto']);

        // cantidad ingresada
        $producto = Leer_Codigo_Inv($parametros['CodigoInv'],$parametros['FechaAte']);
        SetAdoAddNew("Trans_Comision");
        SetAdoFields("CodigoC",$parametros['beneficiario']);
        SetAdoFields("Cta",$parametros['asignacion']);
        SetAdoFields("Codigo_Inv",$parametros['CodigoInv']);
        SetAdoFields("Total",$parametros['Cantidad']);
        SetAdoFields("Fecha",$parametros['FechaAte']);
        // SetAdoFields("Fecha_A",$parametros['FechaAsign']);
        SetAdoFields("Fecha_C",date('Y-m-d'));      
        SetAdoFields("CodBodega",$bode[0]['Codigo_Barra']);        
        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
        SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
        SetAdoFields("T","P");
        SetAdoFields("Orden_No",'PA_'.$parametros['beneficiario'].'_'.str_replace('.','_',$parametros['asignacion']));
        
        return SetAdoUpdate();
    }

    function cargar_asignacion($parametros)
    {        
        $Beneficiario = $parametros['beneficiario'];
        $datos = $this->modelo->cargar_asignacion($Beneficiario,$parametros['asignacion'],'P',$parametros["FechaAte"]);
        $tbl = '';
        $total = 0;
        foreach ($datos as $key => $value) {

            $producto = $this->modelo->lineasKArdex($value['CodBodega']);   
            // print_r($producto);die();        
            $datos[$key]['Producto'] = $producto[0]['Producto'];
            $datos[$key]['Codigo_Barra'] = $producto[0]['Codigo_Barra'];
            // print_r($producto);die();
            $tbl.='<tr>
                    <td><button class="btn btn-sm btn-danger" onclick="eliminarlinea('.$value['ID'].')"><i class="fa fa-trash me-0"></i></button></td>
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

    function GuardarPicking($parametros)
    {
        // print_r($parametros);die();
        SetAdoAddNew('Trans_Comision');
        SetAdoFields('T','F');      
        SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
        SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']); 
        SetAdoFieldsWhere('CodigoC',$parametros['beneficiario']);  
        SetAdoFieldsWhere('Orden_No','PA_'.$parametros['beneficiario'].'_'.str_replace(".","_", $parametros['asignacion']));  
        SetAdoFieldsWhere('Cta',$parametros['asignacion']);  
        // SetAdoFieldsWhere('Fecha',$parametros['fechaAsi']);         
        return SetAdoUpdateGeneric();
    }

    function tipo_asignacion()
    {
        // $asignadas = $this->modelo->cargar_asignacion($bene,$tipo,$T,$fecha=false);
        $datos = $this->modelo->tipo_asignacion();
        foreach ($datos as $key => $value) {
            $lista[] = array('ID' =>$value['Cmds'] ,'Proceso'=>$value['Proceso'],'Picture'=>$value['Picture'] );
        }
        return $lista;
    }

}
?>