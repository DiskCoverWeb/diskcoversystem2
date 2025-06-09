<?php
include(dirname(__DIR__,2).'/modelo/inventario/picking_productoresAliM.php');
require_once(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");

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


class picking_productoresAliC
{
    private $modelo;
    private $sri;

    function __construct()
    {
        $this->modelo = new picking_productoresAliM();
        $this->sri = new autorizacion_sri();
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
}
?>