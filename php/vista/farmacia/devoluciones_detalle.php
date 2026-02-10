<?php  $cod='';if(isset($_GET['comprobante'])){$cod =$_GET['comprobante'];} $_SESSION['INGRESO']['modulo_']='99'; date_default_timezone_set('America/Guayaquil'); 
      unset($_SESSION['NEGATIVOS']['CODIGO_INV']);?>
<script type="text/javascript">
 var cod = '<?php echo $cod; ?>';
</script>
<script src="../../dist/js/farmacia/devoluciones_detalle.js"></script>

<script type="text/javascript">
   $( document ).ready(function() {
    num_comprobante();
    var num_li=0;
    cargar_pedido();
    lista_devolucion();

  });
</script>
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
<div class="row row-cols-auto  mb-2">
  <div class="btn-group">    
      <a  href="./farmacia.php?mod=28" title="Salir de modulo" class="btn btn-outline-secondary btn-sm">
        <img src="../../img/png/salire.png">
      </a>
      <button href="#" title="Generar reporte pdf"  class="btn btn-outline-secondary btn-sm" onclick="generar_informe()">
        <img src="../../img/png/impresora.png" >
      </button>
      <a href="./farmacia.php?mod=28&acc=pacientes&acc1=Visualizar%20paciente&b=1&po=subcu#" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_pdf" title="Pacientes">
          <img src="../../img/png/pacientes.png">
      </a>           
      <a href="./farmacia.php?mod=28&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_excel" title="Descargos">
        <img src="../../img/png/descargos.png">
      </a>         
      <a href="./farmacia.php?mod=28&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulosr"  class="btn btn-outline-secondary btn-sm" onclick="">
          <img src="../../img/png/articulos.png" >
      </a>
  </div>
</div>
<div class="row mb-2">
  <div class="col-sm-12">
    <div class="card card-info">
      <div class="card-body" style="border: 1px solid #337ab7;">
        <div class="row">         
          <div class="col-sm-2"> 
            <b>Comprobante:</b>
            <input type="text" name="comp" id="comp" class="form-control form-control-sm" readonly="">      
          </div>
          <div class="col-sm-4">
            <b>Nombre:</b>
            <input type="text" name="paciente" id="paciente" class="form-control form-control-sm">
            <input type="hidden" name="cod" id="cod">
          </div>
          <div class="col-sm-6">
            <b>Detalle:</b>
            <textarea class="form-control" id="detalle" readonly="" rows="2"></textarea>            
          </div>          
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
      <div class="row">
          <div class="col-sm-6">
            <b>Buscar medicamento</b>
            <input type="text" name="txt_query" id="txt_query" class="form-control form-control-sm" placeholder="Buscar medicamento" onkeyup="cargar_pedido()">
          </div>
          <div class="col-sm-6 text-end">
            <br>
            <button id="" class="btn btn-primary btn-sm" onclick="generar_factura('<?php echo $cod;?>')">Generar devolucion</button> 
          </div>
      </div>
    </div>
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
      <ul class="nav nav-pills mb-3" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" data-bs-toggle="pill" href="#primary-pills-home" role="tab" aria-selected="true">
            <div class="d-flex align-items-center">
              <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
              </div>
              <div class="tab-title">Descargos Realizados</div>
            </div>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="pill" href="#primary-pills-profile" role="tab" aria-selected="false" tabindex="-1">
            <div class="d-flex align-items-center">
              <div class="tab-icon"><i class="bx bx-user-pin font-18 me-1"></i>
              </div>
              <div class="tab-title">Lista de devoluciones</div>
            </div>
          </a>
        </li>
      </ul>
      <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade active show" id="primary-pills-home" role="tabpanel">
            <div class="table-responsive">
              <input type="hidden" name="" id="txt_num_lin" value="0">
              <input type="hidden" name="" id="txt_num_item" value="0">
              <input type="hidden" name="txt_neg" id="txt_neg" value="false">
              <div class="col-sm-12"> 
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Uni</th>
                        <th>Precio Total</th>
                        <th>cant devolver</th>
                        <th>Valor</th>
                        <th>Total devolucion</th>
                        <th></th>
                      </thead>
                      <tbody id="tbl_body">
                      </tbody>
                    </table>
                  </div>
              </div>         
            </div>
        </div>
        <div class="tab-pane fade" id="primary-pills-profile" role="tabpanel">
          <div class="col-sm-12" >
               <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <th></th>
                    <th>CODIGO PRODUCTO</th>
                    <th>PRODUCTO</th>
                    <th>CANTIDAD</th>
                    <th>VALOR UNITARIO</th>
                    <th>VALOR TOTAL</th>
                    <th>FECHA </th>
                    <th>A_No</th>
                  </thead>
                  <tbody id="tbl_devoluciones">
                    
                  </tbody>
                  
                </table>                 
               </div>
             </div>
        </div>
       
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_procedimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Cambiar procedimiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div class="row">
          <div class="col-sm-12">
            Nombre de procedimiento
            <input type="text" class="form-control input-sm" name="txt_new_proce" id="txt_new_proce">
          </div>        
         </div> 
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="guardar_new_pro();">Guardar Todo</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button>
        </div>
    </div>
  </div>
</div>
