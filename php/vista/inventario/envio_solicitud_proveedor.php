<?php
 $orden = '';
if(isset($_GET['orden']))
{
  $orden = $_GET['orden'];
}

?>
<script type="text/javascript">
   var orden = '<?php echo $orden; ?>';
    $(document).ready(function () {   
    if(orden!='')
    {
        pedidos_solicitados(orden);
       lineas_pedido_solicitados_proveedor(orden)
    }

  })
</script>
<?php date_default_timezone_set('America/Guayaquil'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script type="text/javascript" src="../../dist/js/inventario/envio_solicitud_proveedor.js"></script>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?></div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
          <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
          </li>
        </ol>
      </nav>
    </div>          
</div>
<div class="row mb-2">
  <div class="col-sm-6">
     <div class="btn-group" role="group" aria-label="Basic example">
        <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
            <img src="../../img/png/salire.png">
        </a>
        <button type="button" class="btn btn-outline-secondary" title="Informe pdf" onclick="imprimir_pdf()" >
          <img src="../../img/png/pdf.png">
        </button>
        <button type="button" class="btn btn-outline-secondary" title="Informe excel" onclick="imprimir_excel()">
          <img src="../../img/png/excel2.png">
        </button>
        <button title="Guardar"  class="btn btn-outline-secondary" onclick="grabar_envio_solicitud()">
          <img src="../../img/png/grabar.png" >
        </button>    
      </div>
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-4">
          <b>Numero de orden </b><br>
          <span id="lbl_orden"></span>
        </div>
         <div class="col-lg-3">
          <b>Contratista</b><br>
          <span id="lbl_contratista"></span>
        </div>
        <div class="col-lg-3">
          <b>Total</b><br>
          <span id="lbl_total"></span>
        </div>        
      </div>
           
    </div>    
  </div>  
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <form id="form_lineas">
        <div class="col-sm-12">
          <input type="hidden" name="txt_linea_Select" id="txt_linea_Select" value="">
          <div class="table-responsive">
              <table class="table" id="tbl_lista_solicitud">
                <thead>
                  <thead>
                    <th>item</th>
                    <th>Codigo</th>
                    <th>Producto</th>
                    <th>Cant</th>
                    <th>Unidad</th>
                    <th>Costo ref</th>
                    <th>Total ref</th>
                    <th>Fecha solicitud</th>
                    <th>Fecha Entrega</th>
                    <th>Observacion</th>
                    <th>Proveedores</th>
                  </thead>
                  <tbody id="tbl_body">               
                  </tbody>
                </thead>
              </table>
          </div>
        </div>    
        </form>
      </div>  
    </div>    
  </div>
  
</div>
