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
        <div class="row">
          <div class="col-lg-4">
            <b>Contratista </b><br>
            <select class="form-select form-select-sm" id="ddl_contratista" name="ddl_contratista" onchange="cargar_contratos()">
              <option value="">Seleccione</option>
            </select>
          </div>
          <div class="col-lg-2">
            <b>Contrato</b><br>
            <select class="form-select form-select-sm" id="ddl_Contrato" name="ddl_Contrato">
              <option value="">Seleccione</option>
            </select>
          </div>
           <div class="col-lg-4">
            <b>Rubro </b><br>
            <select class="form-select form-select-sm" id="ddl_Rubro" name="ddl_Rubro">
              <option value="">seleccione</option>
            </select>
          </div>
        </div>
    </div>
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
       <div class="row">
            <div class="col-sm-4">              
              <b>Proyectos: </b><br>
              <label>Proyecto de aaa</label>
            </div>   
           <!--  <div class="col-sm-12">
              <b>Contratista: </b>    <br>         
              <label>Proyecto de aaa</label>
            </div> -->
            <div class="col-sm-3">
                <b>Se entrega a cargo material: </b><br>
                <span class=" text-center">SI / NO</span>
            </div>
            <div class="col-sm-5">
              <b>El contratista ejecuta el trabajo con mas de una persona:</b><br>
              <span class=" text-center"> SI / NO </span>
            </div>
            <div class="col-sm-4">
              <b>Nombre de contrato: </b><br>
              <label>nombre de contrato aaa</label>
            </div>
           <div class="col-sm-4">
            <b>Categoria Contrato: </b><br>
            <label>Adicionales</label>
          </div>
          <div class="col-sm-4">
            <b>Tipo Costo: </b>       <br>     
            <label>Centro de costos</label>
          </div>
          <div class="col-sm-4">
            <b>Cuenta contable: </b>  <br>          
            <label>cuenta contable</label>
          </div>
          <div class="col-sm-4">
            <b>Fecha inicio: </b>  <br>
            <label>2025-02-01</label>          
          </div>
          <div class="col-sm-4">
            <b>Fecha fin: </b> <br>
            <label>2025-02-01</label>
          </div>
        </div>
    </div>
  </div>
</div>
<div class="row mb-2">
 <div class="card">
              <div class="card-body">
                <ul class="nav nav-pills nav-pills-warning mb-3" role="tablist">
                  <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="pill" href="#warning-pills-home" role="tab" aria-selected="true">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Bloque 1 - 2</div>
                      </div>
                    </a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="pill" href="#warning-pills-profile" role="tab" aria-selected="false" tabindex="-1">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-user-pin font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Bloque 1</div>
                      </div>
                    </a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="pill" href="#warning-pills-contact" role="tab" aria-selected="false" tabindex="-1">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-microphone font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Bloque 2</div>
                      </div>
                    </a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane fade active show" id="warning-pills-home" role="tabpanel">
       <div class="row">
         <div class="col-sm-12">
              <div class="table-responsive">
                <table class="table w-100" id="tbl_lista_solicitud">
                  <thead>
                     <th>No</th>
                     <th>Categoria del servicio</th>
                     <th>U/m</th>
                     <th>Cantidad/uni</th>
                     <th>Cantidad de la orden </th>
                     <th>Diferencia</th>
                     <th>Observacion</th> 
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
                    </tr>
                  </tbody>
                </table>
              </div>
        </div> 
      </div>
                  </div>
                  <div class="tab-pane fade" id="warning-pills-profile" role="tabpanel">
                   
       <div class="row">
         <div class="col-sm-12">
              <div class="table-responsive">
                 <table class="table w-100" id="tbl_lista_solicitud">
                  <thead>
                     <th>No</th>
                     <th>Categoria del servicio</th>
                     <th>U/m</th>
                     <th>Cantidad/uni</th>
                     <th>Cantidad de la orden </th>
                     <th>Diferencia</th>
                     <th>Observacion</th> 
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
                    </tr>
                  </tbody>
                </table>
              </div>
        </div> 
      </div>
                  </div>
                  <div class="tab-pane fade" id="warning-pills-contact" role="tabpanel">
       <div class="row">
         <div class="col-sm-12">
              <div class="table-responsive">
                <table class="table w-100" id="tbl_lista_solicitud">
                  <thead>
                     <th>No</th>
                     <th>Categoria del servicio</th>
                     <th>U/m</th>
                     <th>Cantidad/uni</th>
                     <th>Cantidad de la orden </th>
                     <th>Diferencia</th>
                     <th>Observacion</th> 
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
                    </tr>
                  </tbody>
                </table>
              </div>
        </div> 
      </div>
                  </div>
                </div>
              </div>
            </div>
</div>
