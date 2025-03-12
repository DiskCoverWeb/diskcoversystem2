<?php
require_once(dirname(__DIR__,2).'/modelo/inventario/mayorizar_productoM.php');

$controlador = new mayorizar_productoC();
if(isset($_GET['mayorizar_producto']))
{
    echo json_encode($controlador->mayorizar_productos());
}

class mayorizar_productoC
{
    private $modelo;
    function __construct(){
        $this->modelo = new mayorizar_productoM();
    }

    function mayorizar_productos(){
        $result = $this->modelo->Mayorizar_Inventario();
        $fecha = BuscarFecha(date('Y-m-d'));
        $result1 = $this->modelo->Mayorizar_Inventario_SP($fecha);
        return $result1;
    }

}

?>