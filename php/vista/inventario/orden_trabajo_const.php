<?php date_default_timezone_set('America/Guayaquil'); $ordenNo = '-1'; if(isset($_GET['ordenNo']) && $_GET['ordenNo']!=''){$ordenNo = $_GET['ordenNo'];}?>

<script type="text/javascript">
 var ordenNo = '<?php echo $ordenNo; ?>';
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script type="text/javascript" src="../../dist/js/inventario/orden_trabajo_const.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
   contratistas();
   ddl_cuenta_contable();
   ddl_Proceso();
   ddl_Grupo();
   ddl_Rubro();
  })

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
<div class="row mb-2">
  <div class="col-sm-6">
     <div class="btn-group" role="group" aria-label="Basic example">
        <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
            <img src="../../img/png/salire.png">
        </a>
        <button type="button" class="btn btn-outline-secondary" title="Informe excel" onclick="imprimir_excel()" >
          <img src="../../img/png/excel2.png">
        </button>
        <button type="button" class="btn btn-outline-secondary" title="Informe pdf" onclick="imprimir_pdf()">
          <img src="../../img/png/pdf.png">
        </button>
        <button title="Guardar"  class="btn btn-outline-secondary" onclick="grabar_solicitud_proveedor()">
          <img src="../../img/png/grabar.png" >
        </button>
      </div>
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
      <form id="form_datos">
        <div class="row">
          <div class="col-lg-4">
            <b>Contratista </b><br>
            <select class="form-select form-select-sm" id="ddl_contratista" name="ddl_contratista">
              <option value="">Seleccione</option>
            </select>
          </div>
           <div class="col-lg-2">
            <b>Contrato</b><br>
            <label><input type="radio" name="rbl_contrato" value="1" checked> SI</label>
            <label><input type="radio" name="rbl_contrato" value="0"> No</label>
          </div>
          <div class="col-lg-3">
            <b>No de  contrato</b><br>
            <input type="text" name="txt_NoContrato" id="txt_NoContrato" class="form-control form-control-sm">
          </div>
           <div class="col-lg-3">
            <b>Proceso </b><br>
            <select class="form-select form-select-sm" id="ddl_Proceso" name="ddl_Proceso">
              <option value="">Seleccione</option>
            </select>
          </div>
           <div class="col-lg-4">
            <b>Grupo </b><br>
            <select class="form-select form-select-sm" id="ddl_Grupo" name="ddl_Grupo">
              <option value="">Seleccionar</option>
            </select>
          </div>
          <div class="col-lg-4">
            <b>Cuenta contable </b><br>
            <select class="form-select form-select-sm" id="ddl_cuenta_contable" name="ddl_cuenta_contable">
              <option value="">Seleccione</option>
            </select>
          </div>
           <div class="col-lg-4">
            <b>Rubro </b><br>
            <select class="form-select form-select-sm" id="ddl_Rubro" name="ddl_Rubro">
              <option value="">seleccione</option>
            </select>
          </div>
           <div class="col-lg-2">
            <b>Fecha inicio</b><br>
            <input type="date" name="txt_fechaIni" id="txt_fechaIni" class="form-control form-control-sm">
          </div>
           <div class="col-lg-2">
            <b>Fecha finalizacion</b><br>
            <input type="date" name="txt_fechaFin" id="txt_fechaFin" class="form-control form-control-sm">
          </div>
           <div class="col-lg-2">
            <b>Categoria contrato</b><br>
            <input type="text" name="txt_categoria" id="txt_categoria" class="form-control form-control-sm">
          </div>
          <div class="col-lg-6">
            <b>Observacion</b><br>          
            <input type="input" name="txt_observacion" id="txt_observacion" class="form-control form-control-sm">
          </div>
          <div class="col-lg-2">
            <b>Unidad de medida</b><br>
            <input type="input" name="txt_unidadMed" id="txt_unidadMed" class="form-control form-control-sm">
          </div>
          <div class="col-lg-2">
            <b>Cantidad contrato</b><br>
            <input type="input" name="txt_cantidad" id="txt_cantidad" class="form-control form-control-sm">
          </div>
          <div class="col-lg-2">
            <b>Cantidad de la orden</b><br>
            <input type="input" name="txt_cantidadOrd" id="txt_cantidadOrd" class="form-control form-control-sm">
          </div>
          <div class="col-lg-2">
            <b>Diferencia</b><br>
            <input type="input" name="txt_diferencia" id="txt_diferencia" class="form-control form-control-sm">
          </div>
          <div class="col-sm-12 col-md-12 col-lg-4 text-end">
            <br>
            <button type="button" class="btn btn-primary btn-sm" onclick="agregar_tabla()">Agregar</button>          
          </div>
        </div>
      </form>
    </div>    
  </div>  
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
       <div class="row">
         <div class="col-sm-12">
              <div class="table-responsive">
                <table class="table w-100" id="tbl_lista_solicitud">
                  <thead>
                     <th>No</th>
                     <th>Contratista</th>
                     <th>Contrato</th>
                     <th>No de contrato</th>
                     <th>Proceso</th>
                     <th>Grupo</th>
                     <th>Cuenta contable</th>
                     <th>Rubro</th>
                     <th>Fecha inicio</th>
                     <th>Fecha finalizacion</th>
                     <th>Categoria Contrato</th>
                     <th>Observacion</th>
                     <th>Unidad de medida</th>
                     <th>Cantidad contrato</th>
                     <th>Cantidad de orden</th>
                     <th>Diferencia</th>
                     <th></th>
                  </thead>
                  <tbody>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
                
              </div>
            </div> 
       
      </div>
    </div>    
  </div>  
</div>
