<?php
include(dirname(__DIR__,2).'/modelo/inventario/categoriasM.php');

$controlador = new categoriasC();

if (isset($_GET['MostrarTabla'])) {
    $option = $_POST['option'];
    echo json_encode($controlador->MostrarTabla($option));
}

if (isset($_GET['AceptarAgregar'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->AceptarAgregar($parametros));
}

if (isset($_GET['MostrarDatosPorId'])) {
    $id = $_POST['id'];
    echo json_encode($controlador->MostrarDatosPorId($id));
}

if (isset($_GET['AceptarEditar'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->AceptarEditar($parametros));
}

if (isset($_GET['AceptarEliminar'])) {
    $id = $_POST['id'];
    echo json_encode($controlador->EliminarPorId($id));
}

if (isset($_GET['AsignarCategoria'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->AsignarCategoria($parametros));
}

if (isset($_GET['ListarCategorias'])) {
    echo json_encode($controlador->ListarCategorias());
}

if (isset($_GET['EditarCategoriaPorId'])) {
    $id = $_POST['id'];
    echo json_encode($controlador->EditarCategoriaPorId($id));
}

if (isset($_GET['AceptarEditarCategoria'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->AceptarEditarCategoria($parametros));
}

if (isset($_GET['EliminarCategoria'])) {
    $id = $_POST['id'];
    echo json_encode($controlador->EliminarCategoriaPorId($id));
}


class categoriasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new categoriasM();
    }

    function MostrarTabla($option)
    {        
        try {
            $datos = $this->modelo->ConsultarCategoriaClientesDatosExtras($option); 
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No hay datos que mostrar.');
        }      
    }

    function AceptarAgregar($parametros)
    {    
        try {
            $datos = $this->modelo->AgregarCategoriaClientesDatosExtras($parametros); 
            Eliminar_Nulos_SP("Clientes_Datos_Extras");            
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron agregar los datos.');
        }                     
    }

    function MostrarDatosPorId($id) {
        try {
            $datos = $this->modelo->MostrarDatosPorId($id);
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron mostrar los datos.');
        }
    }

    function AceptarEditar($parametros)
    {
        try {
            $this->modelo->EditarCategoriaClientesDatosExtrasPorId($parametros);            
            return array('status' => '200', 'msj' => 'Se actualizo correctamente');
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron editar los datos.');
        }   
    }

    function EliminarPorId($id) 
    {        
        try {
            $this->modelo->EliminarCategoriaClientesDatosExtrasPorId($id);
            return array('status' => '200', 'msj' => 'Se elimino correctamente');
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron eliminar los datos.');
        }
    }

    function AsignarCategoria($parametros)
    {    
        try {
            $datos = $this->modelo->AsignarCategoria($parametros); 
            Eliminar_Nulos_SP("Catalogo_Proceso");            
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron agregar los datos.');
        }                     
    }

    function ListarCategorias()
    {        
        try {
            $datos = $this->modelo->ListarCategorias(); 
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No hay datos que mostrar.');
        }      
    }

    function EditarCategoriaPorId($id) {
        try {
            $datos = $this->modelo->EditarCategoriaPorId($id);
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron mostrar los datos.');
        }
    }

    function AceptarEditarCategoria($parametros)
    {
        try {
            $this->modelo->EditarCategoriaCatalogoProcesoPorId($parametros);            
            return array('status' => '200', 'msj' => 'Se actualizo correctamente');
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron editar los datos.');
        }   
    }

    function EliminarCategoriaPorId($id) 
    {        
        try {
            $this->modelo->EliminarCategoriaCatalogoProcesosPorId($id);
            return array('status' => '200', 'msj' => 'Se elimino correctamente');
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron eliminar los datos.');
        }
    }
}
?>