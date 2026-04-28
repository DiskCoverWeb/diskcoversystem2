<?php
require_once(dirname(__DIR__,2).'/modelo/inventario/orden_ejecucionM.php');
require_once(dirname(__DIR__,2).'/modelo/inventario/contrato_trabajo_detalle_constM.php');
require_once(dirname(__DIR__,2).'/modelo/inventario/orden_trabajo_constM.php');

$controlador = new orden_ejecucionC();
if(isset($_GET['lista_orden_ejecucion']))
{
    echo json_encode($controlador->lista_orden_ejecucion());
}

if(isset($_GET['contratistas']))
{
    $query = false;
    if(isset($_GET['q'])){$query = $_GET['q'];}
    echo json_encode($controlador->contratistas($query));
}

if(isset($_GET['contratos']))
{
    $query = false;
    if(isset($_GET['q'])){$query = $_GET['q'];}
    if(isset($_GET['ContratosContratista'])){$contratista = $_GET['ContratosContratista'];}
    echo json_encode($controlador->contratos($contratista,$query));
}

if(isset($_GET['lista_semanas']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->lista_semanas($parametros));
}

if(isset($_GET['cargar_lista_subrubros']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cargar_lista_subrubros($parametros));
}

if(isset($_GET['detalleContrato']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->detalleContrato($parametros));
}

if(isset($_GET['guardar_subrubro_ejecucion']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->guardar_subrubro_ejecucion($parametros));
}

if(isset($_GET['cargar_fecha_periodo']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cargar_fecha_periodo($parametros));
}

if(isset($_GET['guardar_periodo']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->guardar_periodo($parametros));
}

class orden_ejecucionC
{
    private $modelo;
    private $contratos;
    private $orden;
    function __construct(){
        $this->modelo = new orden_ejecucionM();
        $this->contratos = new contrato_trabajo_detalle_constM();
        $this->orden = new orden_trabajo_constM();
    }

    function lista_orden_ejecucion(){

        $result = $this->contratos->detalleContrato(false,'E');
        // print_r($result);die();
        return $result;
    }

    function contratistas($query)
    {
       return $this->modelo->contratistas($query);
    }

    function contratos($contratista,$query)
    {
       $rubro = array();
       $data =  $this->modelo->rubrosXcontratista($query,$contratista);
       foreach ($data as $key => $value) {
           $rubro[] = array('id'=>$value['Cta'],'text'=>$value['Cuenta'],'data'=>$value);
       }
       return $rubro;
    }

    function lista_semanas($parametros)
    {
        // print_r($parametros);die();
         $data = $this->orden->SemanasXcentrosCostocXRubro($parametros['contrato'],$parametros['rubro']);
        // print_r($data);die();
        return $data;
    }

   function cargar_lista_subrubros($parametros)
    {            
        $tbl = '';
        $CentroCostos = $this->orden->centrosCostocXRubro($parametros['Contrato'],$parametros['rubro'],$parametros['semana']);
        foreach ($CentroCostos as $key => $value) {
            // print_r($value);die();
            $tbl.='<div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5>'.$value['Detalle'].'</h5>
                        </div>
                        <div class="col-sm-6 text-end">
                            <button type="button" onclick="add_periodo(\''.$value['ID'].'\')" class="btn btn-primary btn-sm"><i class="bx bx-calendar"></i> Periodos</button>
                        </div>
                        <div class="col-sm-12">


                <table class="table table-hover">
                    <thead>
                      <th></th>
                      <th>Sub Rubros</th>
                      <th>Unidad</th>
                      <th>Orden</th>
                      <th>Costo total de Orden </th>
                      <th>Por Ejecut - Ejecutado</th>
                      <th>Costo unitario ejecutado</th>
                      <th>Costo total ejecutado</th>
                      <th>Diferencia</th>
                    </thead><tbody>';
            $data = $this->orden->cargar_lista_subrubros($parametros['Contrato'],$parametros['rubro'],$subrubro=false,$value['Centro_Costos'],$parametros['contratista']);
            foreach ($data as $key => $value) {
                // print_r($value);die();
                $tbl.='<tr> 
                            <td><button type="button" class="btn btn-primary btn-sm" onclick="guardar_subrubro_ejecucion('.$value['ID'].')"><i class="bx bx-save me-0"></i></button></td>
                            <td>'.$value['Detalle'].'</td> 
                            <td>'.$value['Unidad'].'</td>
                            <td>'.$value['No_Contrato'].'</td>
                            <td>'.$value['Total'].'</td>
                            <td>
                                <div class="input-group">
                                  <input type="hidden" class="form-control form-control-sm" id="txt_pvp_'.$value['ID'].'" value="'.$value['PVP'].'" readonly />
                                    <input type="text" class="form-control form-control-sm" id="txt_cantidad_'.$value['ID'].'" value="'.$value['Cantidad'].'" readonly />
                                    <input type="text" class="form-control form-control-sm" id="txt_ejecucion_'.$value['ID'].'" onblur="calcular_ejecutado('.$value['ID'].')" value="'.$value['Cant_Ejec'].'" />
                                </div>
                            </td>
                            <td><input type="text" class="form-control form-control-sm" id="txt_ejecutado_pvp_'.$value['ID'].'" value="'.$value['Costo_Unit_Ejec'].'" readonly /></td>
                            <td><input type="text" class="form-control form-control-sm" id="txt_ejecutado_total_'.$value['ID'].'" value="'.$value['Costo_Total_Ejec'].'" readonly /></td>
                            <td><input type="text" class="form-control form-control-sm" id="txt_ejecutado_dif_'.$value['ID'].'"  value="'.$value['Diferencia'].'"readonly /></td>
                        </tr>';
            }

        // print_r($data);die();
            $tbl.='</table></div> </div>
                    </div>';
        }

        return $tbl;
        // print_r($data);die();
    }

    function detalleContrato($parametros)
    {
        $contrato = $parametros['contrato'];
        return $this->contratos->detalleContrato($contrato,"E");
        // print_r($parametros);die();
    }

    function guardar_subrubro_ejecucion($parametros)
    {
        // print_r($parametros);die();
        SetAdoAddNew("Entidad_Rubro_Contratista");
        SetAdoFields("Cant_Ejec",$parametros['ejec']);
        SetAdoFields("Diferencia",$parametros['ejec_dif']);
        SetAdoFields("Costo_Unit_Ejec",$parametros['pvp_ejec']);
        SetAdoFields("Costo_Total_Ejec",$parametros["total_ejec"]);


        SetAdoFieldsWhere('ID',$parametros["id"]);
        return SetAdoUpdateGeneric(); 
    }

    function cargar_fecha_periodo($parametros)
    {
        $data = $this->contratos->Trans_Contratistas($parametros['id']);
        return $data;
        // print_r($data);die();
    }

    function guardar_periodo($parametros)
    {

        // print_r($parametros);die();
        SetAdoAddNew("Trans_Contratistas_Rubros");
        SetAdoFields("Fecha_Inicio_Ejec",$parametros['fechaInicio']);
        SetAdoFields("Fecha_Fin_Ejec",$parametros['fechaFin']);
        SetAdoFields("Observacion",$parametros["observacion"]);


        SetAdoFieldsWhere('ID',$parametros["idrubro"]);
        return SetAdoUpdateGeneric(); 
    }
}



?>