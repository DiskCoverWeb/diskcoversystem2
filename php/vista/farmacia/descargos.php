<?php  $cod = ''; $ci =''; if(isset($_GET['cod'])){$cod = $_GET['cod'];} if(isset($_GET['ci'])){$ci = $_GET['ci'];}  unset($_SESSION['NEGATIVOS']);?>
<script>
  var cod ='<?php echo $cod; ?>';
  var ci = '<?php echo $ci; ?>';
</script>
<script src="../../dist/js/farmacia/descargos.js"></script>
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
      <div class="btn-group">
          <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            <img src="../../img/png/pdf.png">
            <span class="caret"></span>
          </button>
           <ul class="dropdown-menu" role="menu" id="year">
            <li><a href="#" class="dropdown-item" onclick="reporte_pdf()"> Descargos</a></li>
            <li><a href="#" class="dropdown-item" onclick="reporte_pdf_nega()"> Descargos en Negativos</a></li>
          </ul>
      </div>
      <div class="btn-group">
          <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            <img src="../../img/png/table_excel.png">
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu" id="year">
            <li><a href="#" class="dropdown-item" onclick="reporte_excel()"> Descargos</a></li>
            <li><a href="#" class="dropdown-item" onclick="reporte_excel_nega()"> Descargos en Negativos</a></li>
          </ul>
      </div>
      <a href="./inicio.php?mod=28&acc=pacientes" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_pdf" title="Pacientes">
        <img src="../../img/png/pacientes.png">
      </a>   
      <a href="./inicio.php?mod=28&acc=vis_descargos" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_excel" title="Descargos">
            <img src="../../img/png/descargos.png">
      </a>
      <a href="./inicio.php?mod=28&acc=articulos" title="Ingresar Articulosr"  class="btn btn-outline-secondary btn-sm">
        <img src="../../img/png/articulos.png" >
      </a>
      <button title="Mayorizar Articulos"  class="btn btn-outline-secondary btn-sm"onclick="mayorizar_inventario()">
        <img src="../../img/png/update.png" >
      </button> 
 </div>
</div>
<div class="row mb-2">
  <div class="card">
      <div class="card-title"><b>DESCARGOS REALIZADOS</b></div>
      <div class="card-body">
      	<div class="row">
          <div class="col-sm-4">
            <b>Num. Historia Clinica :</b>
            <input type="text" class="form-control form-control-sm" readonly="" id="txt_codigo"value="<?php echo $cod; ?>">
          </div>
          <div class="col-sm-8">
            <b>Nombre</b>
            <div class="input-group">
                <select class="form-control form-control-sm" id="ddl_paciente" onchange="buscar_cod()">
                  <option value="">Seleccione paciente</option>
                </select>
                <button class="btn btn-outline-secondary btn-sm p-0"><i class="bx bx-search me-0"></i></button>
            </div>
          </div>  
        </div>
        <div class="row">
          <div class="col-sm-6">
            <b>Area de descargo</b>
            <select class="form-control form-control-sm" id="ddl_areas">
              <option value="">Seleccione area de ingreso</option>
            </select>            
          </div>
          <div class="col-sm-6">
            <b>Procedimiento</b>
            <input type="text" name="" class="form-control form-control-sm" name="txt_procedimiento" id="txt_procedimiento">
          </div>          
        </div>
        <div class="row mt-2">
          <div class="col-sm-12 text-end">
            <button type="button" class="btn btn-primary btn-sm" onclick="limpiar()"><i class="fa fa-paint-brush"></i> Limpiar</button>
            <button type="button" class="btn btn-success btn-sm" onclick="nuevo_pedido()"><i class="fa fa-plus"></i> Nuevo Descargos</button>
          </div> 
        </div>        
      </div>
  </div>
</div>
<div class="row">
  <div class="card">
    <div class="card-body">
      <form method="post" id="filtro_bus" enctype="multipart/form-data">
        <div class="row mb-2">
           <div class="col-sm-5">
              <b>NOMBRE DE PACIENTE</b>
              <input type="text" name="txt_query" id="txt_query" class="form-control form-control-sm" placeholder="Nombre paciente" onkeyup="cargar_pedidos()">
               <div class="pull-right"  name="txt_codigo" id="txt_codigo" >
                <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_nombre" checked="" value="N"> Nombre</label>
                 <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_ruc" value="C"> CI / RUC</label>
                 <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_pedido" value="P"> Pedido</label>
              </div>
            </div>
            <div class="col-sm-2">
              <b>FECHA INICIO</b>
              <input type="date" name="txt_desde" id="txt_desde" class="form-control form-control-sm" value="<?php echo date('Y-m-d')?>" onblur="cargar_pedidos('f');cargar_pedidos_detalle('f')">
            </div>
            <div class="col-sm-2">
              <b>FECHA FIN</b>
              <input type="date" name="txt_hasta" id="txt_hasta" class="form-control form-control-sm" value="<?php echo date('Y-m-d')?>" onblur="cargar_pedidos('f');cargar_pedidos_detalle('f')">
            </div>
            <div class="col-sm-3 ">
              <b>Area de descargo</b>
              <div class="d-flex align-items-center">
                <select class="form-select form-select-sm" id="ddl_areas_filtro" name="ddl_areas_filtro" onchange="cargar_pedidos();">
                  <option value="">Seleccione area de ingreso</option>
                </select> 
                <button type="button" class="btn-outline-danger btn btn-sm p-1" onclick="$('#ddl_areas_filtro').val(null).trigger('change');"><i class="bx bx-x me-0"></i></button> 
              </div>
            </div>
        </div>
        <div class="row" style="display:none;">
          <div class=" col-sm-12 input-group">
             <select class="form-control" id="ddl_articulo" name="ddl_articulo" onchange="cargar_pedidos();">
             <option value="">Seleccione producto</option>
          </select>
            <button type="button" class="btn btn-default btn-flat" onclick="$('#ddl_articulo').val(null).trigger('change');"><i class="fa fa-close"></i></button>
          </div>                           
        </div>
        <input type="hidden" name="txt_tipo_filtro" id="txt_tipo_filtro" value=""> 
      </form>
      <div class="row">
        <div class="col-sm-12">
                <ul class="nav nav-tabs nav-warning" role="tablist">
                  <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#warninghome" role="tab" aria-selected="true">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Descargos Realizados</div>
                      </div>
                    </a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#warningprofile" role="tab" aria-selected="false" tabindex="-1">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-user-pin font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Detalle de descargos</div>
                      </div>
                    </a>
                  </li>
                </ul>
                <div class="tab-content py-3">
                  <div class="tab-pane fade active show" id="warninghome" role="tabpanel">
                      <div class="row">
                        <div class="col-sm-12 text-end">
                          <label><input type="checkbox" name="rbl_negativos" id="rbl_negativos" onclick="cargar_pedidos()"> Mostrar pedidos en negativo?</label>
                        </div>          
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
                         <div class="table-responsive">      
                           <table class="table table-hover" id="tbl_descargos">
                             <thead>
                              <tr>
                               <th>ITEM</th>
                               <th>NUM PEDIDO</th>
                               <th>PACIENTE</th>
                               <th>AREA INGRESO</th>
                               <th>IMPORTE</th>
                               <th>FECHA</th>
                               <th>ESTADO</th>
                               <th></th>
                             </tr>
                             </thead>
                             <tbody id="tbl_body">
                        
                             </tbody>
                           </table>      
                         </div>
                       </div>
                       </div>
                  </div>
                  <div class="tab-pane fade" id="warningprofile" role="tabpanel">
                     <div class="row">
                        <div class="col-sm-12 text-center">
                            <h4><b id="titulo_detalle"></b></h4>
                        </div>
                        <div class="col-sm-12" >
                          <br>
                          <table class="table table-hover" id="tbl_detalle">
                            <!-- <tr>
                              <td colspan="2"><b>NOMBRE:</b></td>
                              <td><b>PROCEDIMIENTO:</b></td>
                              <td><b>AREA:</b></td>
                              <td><b>No. DESCARGO</b></td>                
                            </tr>
                            <tr>
                              <td colspan="5"><b>FECHA DE DESCARGO:</b></td>
                            </tr>
                            <tr>
                              <td><b>CODIGO</b></td>
                              <td><b>PRODUCTO</b></td>
                              <td><b>CANTIDAD</b></td>
                              <td><b>VALOR UNI</b></td>
                              <td><b>VALOR TOTAL</b></td>
                            </tr> -->
                          </table>
                          
                        </div>
                      </div>
                  </div>
                </div>
          
        </div>
        
      </div>
    </div>    
  </div>  
</div>



<div id="num_historial" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Numero historial</h4>
      </div>
      <div class="modal-body">
        <form>
        <div class="row text-center">
          <div class="col-sm-12">
            <b>Numero de Historia clinica</b>
            <input type="text" name="txt_histo_actu" id = "txt_histo_actu" class="form-control input-sm">  
          </div> 
      </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="actualizar_num_historia()">Guardar</button>
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
      </div>
    </div>
  </div>
</div>

