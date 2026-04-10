<?php
require_once(dirname(__DIR__,2).'/modelo/inventario/contrato_trabajo_detalle_constM.php');

$controlador = new contrato_trabajo_detalle_constC();
if(isset($_GET['GuardarContrato']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->GuardarContrato($parametros));
}

if(isset($_GET['detalleContrato']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->detalleContrato($parametros));
}

if(isset($_GET['cargar_lista_contratos']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cargar_lista_contratos($parametros));
}

if(isset($_GET['eliminar_contrato']))
{
    $id = $_POST['id'];
    echo json_encode($controlador->eliminar_contrato($id));
}

if(isset($_GET['ingresarOrden']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ingresarOrden($parametros));
}

if(isset($_GET['ingresar_personal_orden']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ingresar_personal_orden($parametros));
}

if(isset($_GET['lista_solicitud_rubro']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->lista_solicitud_rubro($parametros));
}

if(isset($_GET['lista_personal_contrato']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->lista_personal_contrato($parametros));
}
if(isset($_GET['contratistas']))
{
    // $parametros = $_POST['parametros'];
    $query = '';
    if(isset($_GET['q'])){ $query = $_GET['q'];}
    echo json_encode($controlador->contratistas($query));
}

if(isset($_GET['personal']))
{
    // $parametros = $_POST['parametros'];
    $query = '';
    if(isset($_GET['q'])){ $query = $_GET['q'];}
    echo json_encode($controlador->personal($query));
}

if(isset($_GET['lista_etapas']))
{
    $query = '';
    $proyecto = '';
    if(isset($_GET['q'])){ $query = $_GET['q'];}
    if(isset($_GET['pro'])){ $proyecto = $_GET['pro'];}

    echo json_encode($controlador->lista_etapas($proyecto,$query));
}

if(isset($_GET['eliminar_personal']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminar_personal($parametros));
}

if(isset($_GET['eliminar_rubros']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminar_rubros($parametros));
}

if(isset($_GET['proyecto']))
{   
   
    $query = '';
    if(isset($_GET['q'])){ $query = $_GET['q'];}
    echo json_encode($controlador->proyecto($query));
}

if(isset($_GET['ddl_Proceso']))
{
    $query = false;
    $proyecto = $_GET['idproyecto'];
    if(isset($_GET['q'])){$query = $_GET['q'];}
    echo json_encode($controlador->ddl_Proceso($proyecto,$query));
}

if (isset($_GET['cc'])) {
    if(!isset($_GET['q']))
    {
        $_GET['q'] =''; 
    }
    echo json_encode($controlador->lista_cc($_GET['q']));
}

if(isset($_GET['ddl_Rubro']))
{
    if(!isset($_GET['q']))
    {
        $_GET['q'] =''; 
    }
    $pro = $_GET['pro'];
    $etapa = $_GET['etapaCC'];
    echo json_encode($controlador->ddl_Rubro($_GET['q'],$pro,$etapa));
}

if(isset($_GET['grabar_orden_trabajo']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->grabar_orden_trabajo($parametros));
}



class contrato_trabajo_detalle_constC
{
    private $modelo;
    function __construct(){
        $this->modelo = new contrato_trabajo_detalle_constM();
    }

    function proyecto($query)
    {
        $datos = $this->modelo->proyecto($query);
        return $datos;
    }

    function contratistas($query)
    {
        $datos = $this->modelo->contratistas($query);
        return $datos;
    }

    function personal($query)
    {
        $datos = $this->modelo->personal($query);
        $lista = array();
        foreach ($datos as $key => $value) {
            $lista[] = array('id'=>$value['Codigo'],'text'=>$value['Cliente'],'data'=>$value);
        }
        return $lista;
    }

    function GuardarContrato($parametros)
    {
        $tipo = substr($parametros['cate_contrato_name'],0,2);
        $numeroContrato = ReadSetDataNum(strtoupper($tipo)."_SEC_999999",true,$Incrementar = false);

        // print_r($tipo);
        // print_r($parametros);die();
        // print_r($numeroContrato); die();
        $contrato = strtoupper($tipo).'_'.$numeroContrato.'_'.$parametros['contratista'];

        SetAdoAddNew("Trans_Contratistas");
        SetAdoFields("TC","OT");        
        SetAdoFields("Codigo",$parametros['contratista']);
        SetAdoFields("Proceso",$parametros['cate_contrato']);
        SetAdoFields("Proyecto",$parametros['proyecto']);
        SetAdoFields("Cargo_Mat",$parametros['material']);
        SetAdoFields("Mas_Persona",$parametros['mas_personas']);
        SetAdoFields("No_Contrato",$contrato);
        SetAdoFields("Fecha",$parametros['fecha_inicio']);
        SetAdoFields("Fecha_V",$parametros['fecha_fin']);

        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
        SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
        $resp = SetAdoUpdate();
        return array("respuesta"=>$resp,"contrato"=>$contrato);
        // print_r($parametros);die();
    }

    function detalleContrato($parametros)
    {
        $contrato = $parametros['contrato'];
        return $this->modelo->detalleContrato($contrato);
        // print_r($parametros);die();
    }

    function cargar_lista_contratos()
    {
        return $this->modelo->detalleContrato();
    }  

    function eliminar_contrato($id)
    {
        // print_r($id);die();
        return $this->modelo->eliminar_contrato($id);
    }

    function ingresarOrden($parametros)
    {

        // print_r($parametros);die();

        SetAdoAddNew("Trans_Contratistas_Rubros");
        SetAdoFields("Orden_Trabajo",$parametros['orden']);        
        SetAdoFields("Cantidad",$parametros['cantidad']);
        SetAdoFields("Costo_Unit",$parametros['pvp']);
        SetAdoFields("Total",$parametros['total']);
        SetAdoFields("Codigo",$parametros['unidad']);

        SetAdoFields("Etapa",$parametros['etapa']);
        SetAdoFields("Centro_Costos",$parametros['centro_costo']);
        SetAdoFields("Cta",$parametros['rubro']);

        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
        SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);


        // print_r($parametros);die();

        $resp = SetAdoUpdate();

        return $resp;

    }

    function  lista_etapas($proyecto,$query)
    {

        // print_r($proyecto);
        // print_r($query);
        // die();
        // $datos = $this->modelo->lista_etapas($query);
        $proyecto = $this->modelo->proyecto(false,$proyecto);
        $lista_etapas = $this->modelo->lista_etapas($proyecto[0]['Cmds'],$query=false);
        $lista = array();
        foreach ($lista_etapas as $key => $value) {
            $lista[] =array('id'=>$value['Cta_Debe'],'text'=>$value['Proceso'], 'data'=>$value); 
        }
        return $lista;
    }


    function lista_solicitud_rubro($parametros)
    {
        $contrato = $parametros['orden'];
        $data = $this->modelo->lista_solicitud_rubro($contrato);
        $lista = '';
                                  // print_r($data);die();
        foreach ($data as $key => $value) {
        $lista.= '<tr>
              <td><button type="button" class="btn-danger btn-sm btn" onclick="eliminar_rubros('.$value['ID'].')"><i class="bx bx-trash me-0"></i></button></td>
              <td>'.($key+1).'</td>
              <td>'.$value['Cuenta'].'</td>
              <td>'.$value['Detalle'].'</td>
              <td>'.$value['Codigo'].'</td>
              <td>'.$value['Cantidad'].'</td>
              <td>'.$value['Costo_Unit'].'</td>
              <td>'.$value['Total'].'</td>
            </tr>';              
        }

        return $lista;
       
    }

    function ingresar_personal_orden($parametros)
    {

        SetAdoAddNew("Entidad_CxP_Contrato");
        SetAdoFields("Codigo",$parametros['personal']);
        SetAdoFields("No_Contrato",$parametros['orden']);
        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
        SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);

        $resp = SetAdoUpdate();

        return $resp;
    }

    function lista_personal_contrato($parametros)
    {
        $contrato = $parametros['orden'];
        $data = $this->modelo->lista_personal_contrato($contrato);
        $lista = '';
        foreach ($data as $key => $value) {
            // print_r($value);die();

            $fechaNacimiento = new DateTime($value['Fecha_N']->format('Y-m-d'));
            $fechaActual = new DateTime(date('Y-m-d'));
            $edad = $fechaActual->diff($fechaNacimiento);

            $lista.= '<tr>                    
                      <td><button class="btn btn-danger btn-sm pe-1" onclick="eliminar_personal('.$value['ID'].')"><i class="bx bx-trash"></i></button></td>
                      <td>'.($key+1).'</td>
                      <td>'.$value['Cliente'].'</td>
                      <td>'.$value['CI_RUC'].'</td>
                      <td>'.$value['Actividad'].'</td>
                      <td>'.$edad->d.'</td>
                      <td>'.$value['Fecha']->format('Y-m-d').'</td>
                      <td>'.$value['Fecha_Cad']->format('Y-m-d').'</td>  
                    </tr>';
                                 
                                 
        }

        return $lista;
    }

    function eliminar_personal($parametros)
    {
        return $this->modelo->delete_personal($parametros['id']);
        // print_r($parametros);die();
    }

    function eliminar_rubros($parametros)
    {
        return $this->modelo->eliminar_rubros($parametros['id']);
        // print_r($parametros);die();
    }

    function ddl_Proceso($proyecto,$query)
    {
        $proyecto = $this->modelo->proyecto(false,$proyecto);
        $cmds = $proyecto[0]['Cmds'].'.02';

        // print_r($proyecto);
        // print_r($cmds);
        // die();

        $data = $this->modelo->ddl_Proceso($query,$cmds);
        return $data;
    }

    function lista_cc($query)
    {
        // print_r($query);
        // print_r($proyecto);
        // print_r($etapa);die();

        $resp = $this->modelo->listar_cc($query);
        // print_r($resp);die();
        return $resp;
    }

    function ddl_Rubro($query,$proyecto,$etapa)
    {
        // print_r($query);
        // print_r($proyecto);
        // print_r($etapa);die();

        $resp = $this->modelo->ddl_Rubro($query,$etapa);
        // print_r($resp);die();
        return $resp;
    }

    function grabar_orden_trabajo($parametros)
    {
        return $this->modelo->grabar_orden_trabajo($parametros['pedido']);

    }




}

?>