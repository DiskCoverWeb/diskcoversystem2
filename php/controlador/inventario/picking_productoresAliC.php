<?php
include(dirname(__DIR__,2).'/modelo/inventario/picking_productoresAliM.php');

$controlador = new picking_productoresAliC();

// if (isset($_GET['lista_stock_ubicado'])) {
//     $parametros = $_POST['parametros'];
//     echo json_encode($controlador->lista_stock_ubicado($parametros));
// }
// if (isset($_GET['cambiar_bodega'])) {
//     $parametros = $_POST['parametros'];
//     echo json_encode($controlador->cambiar_bodega($parametros));
// }


class picking_productoresAliC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new picking_productoresAliM();
    }
}
?>