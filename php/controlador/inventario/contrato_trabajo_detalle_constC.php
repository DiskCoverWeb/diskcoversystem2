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
    if(isset($_GET['q'])){ $query = $_GET['q'];}
    echo json_encode($controlador->lista_etapas($query));
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


class contrato_trabajo_detalle_constC
{
    private $modelo;
    function __construct(){
        $this->modelo = new contrato_trabajo_detalle_constM();
    }


    function contratistas($query)
    {
        $datos = $this->modelo->contratistas($query);
        return $datos;
    }

    function personal($query)
    {
        $datos = $this->modelo->personal($query);
        return $datos;
    }

    function GuardarContrato($parametros)
    {
        $tipo = substr($parametros['cate_contrato_name'],0,2);
        $numeroContrato = ReadSetDataNum("Contrato_No",true,$Incrementar = false);

        // print_r($tipo);
        // print_r($parametros);die();
        // print_r($numeroContrato); die();
        $contrato = $tipo.'_'.$parametros['contratista'].'_'.date('ymd').'_'.generaCeros($numeroContrato,5);

        SetAdoAddNew("Trans_Contratistas");
        SetAdoFields("TC","OT");        
        SetAdoFields("Nombre_Contrato",$parametros['nombre_contrato']);
        SetAdoFields("Codigo",$parametros['contratista']);
        SetAdoFields("Proceso",$parametros['cate_contrato']);
        SetAdoFields("Cta",$parametros['cc_']);
        SetAdoFields("Proyecto",$parametros['proyecto']);
        SetAdoFields("Cargo_Mat",$parametros['material']);
        SetAdoFields("Mas_Persona",$parametros['mas_personas']);
        SetAdoFields("Categoria_Contrato",$parametros['cuenta_contable']);
        SetAdoFields("Autorizacion",$contrato);
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

    function  lista_etapas($query)
    {
        $datos = $this->modelo->lista_etapas($query);
        $lista = array();
        foreach ($datos as $key => $value) {
            $lista[] =array('id'=>$value['Cmds'],'text'=>$value['Proceso'], 'data'=>$value); 
        }
        return $lista;
    }


    function lista_solicitud_rubro($parametros)
    {
        $contrato = $parametros['orden'];
        $data = $this->modelo->lista_rubros_unicos($contrato);
        $lista = '';
        foreach ($data as $key => $value) {
            $lineas = $this->modelo->lista_solicitud_rubro($contrato,$value['Cta']);

            // print_r($lineas);die();
            $lista.= ' <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne_'.$value['Cta'].'" aria-expanded="true" aria-controls="collapseOne">'.$value['Detalle'].'
                      </button>
                    </h2>
                    <div id="collapseOne_'.$value['Cta'].'" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                      <div class="accordion-body"> 
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="table-responsive">
                              <table class="table w-100" id="tbl_lista_solicitud_rubro">
                                  <thead>                                  
                                     <th></th>
                                     <th>No</th>
                                     <th>Centro de costos</th>
                                     <th>Detalle</th>
                                     <th>U/m</th>
                                     <th>Cant</th>
                                     <th>Costo/Uni</th>
                                     <th>Total</th>
                                  </thead>
                                  <tbody>';
                                  foreach ($lineas as $key2 => $value2) {
                                    $lista.= '<tr>
                                          <td><button type="button" class="btn-danger btn-sm btn" onclick="eliminar_rubros('.$value2['ID'].')"><i class="bx bx-trash me-0"></i></button></td>
                                          <td>'.($key2+1).'</td>
                                          <td>Puerta batiente</td>
                                          <td>'.$value2['Codigo'].'</td>
                                          <td>'.$value2['Cantidad'].'</td>
                                          <td>'.$value2['Costo_Unit'].'</td>
                                          <td>'.$value2['Total'].'</td>
                                        </tr>';
                                  }
                                    
                                  $lista.='</tbody>
                              </table>
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>';
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



}

?>