<?php date_default_timezone_set('America/Guayaquil'); $ordenNo = '-1'; if(isset($_GET['ordenNo']) && $_GET['ordenNo']!=''){$ordenNo = $_GET['ordenNo'];}?>

<script type="text/javascript">
 var ordenNo = '<?php echo $ordenNo; ?>';
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script type="text/javascript" src="../../dist/js/inventario/control_avances.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
   contratistas();
   // ddl_cuenta_contable();
   // ddl_Proceso();
   // ddl_Grupo();
   // ddl_Rubro();

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
       <!--  <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
            <img src="../../img/png/salire.png">
        </a> -->
        <button type="button" class="btn btn-outline-secondary" title="Informe excel" onclick="imprimir_excel()" >
          <img src="../../img/png/excel2.png">
        </button>
        <button type="button" class="btn btn-outline-secondary" title="Informe pdf" onclick="imprimir_pdf()">
          <img src="../../img/png/pdf.png">
        </button>
       <!--  <button title="Guardar"  class="btn btn-outline-secondary" onclick="grabar_orden_trabajo()">
          <img src="../../img/png/grabar.png" >
        </button> -->
      </div>
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
        <div class="row">
          <div class="col-lg-4">
            <b>Contratista </b><br>
            <select class="form-select form-select-sm" id="ddl_contratista" name="ddl_contratista" onchange="cargar_contratos();cargar_lista_subrubros()">
              <option value="">Seleccione</option>
            </select>
          </div>
           <div class="col-lg-3">
            <b>Contrato</b><br>
            <select class="form-select form-select-sm" id="ddl_Contrato" name="ddl_Contrato" onchange="cargar_rubros();cargar_lista_subrubros()">
              <option value="">Seleccione</option>
            </select>
          </div>
          <div class="col-lg-3">
            <b>Rubro </b><br>
            <select class="form-select form-select-sm" id="ddl_Rubro" name="ddl_Rubro" onchange="cargar_meses();cargar_lista_subrubros()">
              <option value="">seleccione</option>
            </select>
          </div>
           <div class="col-lg-1">
            <b>Mes </b><br>
            <select class="form-select form-select-sm" id="ddl_meses" name="ddl_meses" onchange="cargar_lista_subrubros()">
              <option value="">seleccione</option>
            </select>
          </div>
          <div class="col-lg-1">
            <b>Semana </b><br>
            <select class="form-select form-select-sm" id="ddl_semana" name="ddl_semana" onchange="cargar_lista_subrubros()">
              <option value="">seleccione</option>
            </select>
          </div>
         
          
        </div>
    </div>
  </div>
</div>
<div class="row mb-2">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-sm-6">
              <h5>Resumen de Contrato</h5>
          </div>          
          <div class="col-sm-6 text-end">
            <!-- <button type="button" class="btn btn-primary btn-sm" onclick="ver_resumen()">Ver Resumen</button>             -->
          </div>
        </div>
      </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-4">              
              <b>Proyectos: </b><br>
              <label id="lbl_proyecto">-</label>
              <input type="hidden" name="txt_cuenta_proyecto" id="txt_cuenta_proyecto">
            </div>   
            <div class="col-sm-4">
              <b>Contratista: </b>    <br>         
              <label id="lbl_contratista">-</label>
            </div>
            <div class="col-sm-4">
                <b>Se entrega a cargo material: </b>
                <span id="lbl_material">-</span>
            </div>
            <div class="col-sm-5">
              <b>El contratista ejecuta el trabajo con marquinaria:</b>
              <span id="lbl_mas_personas"> - </span>
            </div>
           <!--  <div class="col-sm-12">
              <b>Nombre de contrato: </b><br>
              <label id="lbl_nombre_contrato">-</label>
            </div> -->
           <div class="col-sm-3">
            <b>Tipo Contrato: </b>
            <label id="lbl_categoria">-</label>
          </div>
          <!-- <div class="col-sm-4">
            <b>Tipo Costo: </b>            
            <label id="lbl_tipo_costo">-</label>
          </div> -->
          <!-- <div class="col-sm-4">
            <b>Cuenta contable: </b>            
            <label id="lbl_cuenta_contable">-</label>
          </div> -->
          <div class="col-sm-2">
            <b>Fecha inicio: </b><br>    
            <label id="lbl_fecha">0000-00-00</label>          
          </div>
          <div class="col-sm-2">
            <b>Fecha fin: </b>     <br>
            <label id="lbl_fecha_v">0000-00-00</label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2">
            <b>Cantidad de contrato:<h1 id="lbl_cant_contrato"></h1></b>
          </div>
          <div class="col-sm-2">
            <b>Cantidad ejecutado:<h1 id="lbl_cant_procesado"></h1></b>
          </div>  
          <div class="col-sm-2">
            <b>Porcentaje ejecutado:<h1 id="lbl_porcentaje"></h1></b>
          </div>          
        </div>
      </div>
    </div> 
</div>
<div class="row mb-2" id="pnl_detalle_proyecto">
  <div class="card">
    <div class="card-header">
        <div class="row">
          <div class="col-sm-6">
              <h5>Detalle de Ejecucion</h5>
          </div>          
          <div class="col-sm-6 text-end">
            <!-- <button type="button" class="btn btn-primary btn-sm" onclick="ver_resumen()">Ver Resumen</button>             -->
          </div>
        </div>
      </div>
    <div class="card-body">
       <div class="row">
          <div class="col-sm-12">
            <div class="table-responsive">
              <table class="table table-hover" id="tbl_body_procesados">
                <thead>
                  <th></th>
                  <th>Centro de costos</th>
                  <th>Sub Rubros</th>
                  <th>Unidad</th>
                  <th>Orden</th>
                  <th>Ejecutado</th>
                  <th>Avance</th>
                  <th>Costo orden</th>
                  <th>Costo ejecutado</th>
                  <th>Diferencia</th>
                </thead>
                
              </table>            
            </div>
            
          </div>
        </div>
    </div>
  </div>
</div>
<div class="row mb-2" id="pnl_centro_costos_proyecto">
  <div class="card">
    <div class="card-body">
      <div class="row" id="tbl_subrubros">
       <!--  <div class="col-sm-1">
          <input type="hidden" name="txt_centro_costos" id="txt_centro_costos">
          <input type="hidden" name="txt_id_centro_costos" id="txt_id_centro_costos">
        </div>
        <div class="col-sm-12">
          <h5>Cetro de costos: <span id="lbl_centro_costo"></span></h5>
        </div>
        <div class="col-sm-12">
          <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <th></th>
                  <th>Sub Rubros</th>
                  <th>Unidad</th>
                  <th>Orden</th>
                  <th>Costo total de Orden </th>
                  <th>Ejecutado</th>
                  <th>Costo unitario ejecutado</th>
                  <th>Costo total ejecutado</th>
                  <th>Diferencia</th>
                </thead>
                <tbody id="tbl_body"></tbody>
              </table>            
          </div>          
        </div>    -->
              
      </div>
    </div>    
  </div>
</div>

<div class="modal fade" id="myModal_periodo_trabajo" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12">
                  <input type="hidden" name="txt_rubro" id="txt_rubro">
                  <div  class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <td colspan="2" class="text-center"><b>ORDEN</b></td>
                          <td colspan="2" class="text-center"><b>EJECUCION</b></td>
                          <td colspan="3"></td>
                        </tr>
                        <tr>
                          <td>Fecha inicio</td>
                          <td>Fecha fin</td>
                          <td>Fecha inicio</td>
                          <td>Fecha fin</td>
                          <td>Retrazo (dias)</td>
                          <td>Adelanto (dias)</td>
                        </tr>
                      </thead>
                      <tbody>
                         <tr>
                          <td><b id="lbl_fecha_ini_sub">0000-00-00</b></td>
                          <td><b id="lbl_fecha_fin_sub">0000-00-00</b></td>
                          <td><input type="date" class="form-control form-control-sm" onchange="calcular_dias()" name="txt_fechaIni_eje" id="txt_fechaIni_eje"></td>
                          <td><input type="date" class="form-control form-control-sm" onchange="calcular_dias()" name="txt_fechaFin_eje" id="txt_fechaFin_eje"></td>
                          <td><input type="" class="form-control form-control-sm text-danger" name="txt_retrazo" id="txt_retrazo" readonly></td>
                          <td><input type="" class="form-control form-control-sm text-success" name="txt_adelanto" id="txt_adelanto" readonly></td>
                        </tr>                        
                      </tbody>
                    </table>                    
                  </div>                  
                </div>
                <div class="col-sm-12">
                  <b>Observacion</b>
                  <textarea id="txt_observacion" name="txt_observacion" class="form-control form-control-sm"></textarea>                  
                </div>           
              </div>
            </div>
             <div class="modal-footer">
                  <button type="button" class="btn btn-primary" id="btn_guardar_periodo" name="btn_guardar_periodo" onclick="guardar_periodo()"><i class="bx bx-save"></i>Guardar</button>        
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
              </div>
        </div>
    </div>
</div>
