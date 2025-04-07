<?php
 // DESARROLLADOR     : Javier Farinango
 // FECHA CREACION    : 02/04/2025
 // FECHA MODIFICACION: 02/04/2025 - 02/04/
 // DESCIPCION        : pantalla de asignaciones  

require_once(dirname(__DIR__, 2) . "/modelo/inventario/asignacion_familiasM.php");
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
class asignacion_familiasC
{
    private $modelo;
    private $sri;

    public function __construct()
    {
        $this->sri = new autorizacion_sri();
        $this->modelo = new asignacion_familiasM();

    }

    function addAsignacion($parametros)
    {

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
        SetAdoFields("Cantidad",$parametros['Cantidad']);
        SetAdoFields("Precio",number_format($producto['datos']['PVP'],2,'','.'));
        SetAdoFields("Total",number_format($producto['datos']['PVP']*$parametros['Cantidad'],2,'','.'));
        SetAdoFields("Fecha",$parametros['FechaAte']);
        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
        SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
        SetAdoFields("No_Hab",$parametros['asignacion']);
        SetAdoFields("Orden_No",str_replace('.',"_",$parametros['grupo']).'_'.str_replace('-',"", $parametros['FechaAte']));
        
        return SetAdoUpdate();
    }


    function listaAsignacion($parametros)
    {
        // print_r($parametros);die();
        $tr = '';
        $cantidad = 0;
        $res = array();
        $orden = str_replace('.',"_",$parametros['grupo']).'_'.str_replace('-',"", $parametros['fecha']);
        $datos = $this->modelo->listaAsignacion($orden,'P',$parametros['tipo']);
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

}