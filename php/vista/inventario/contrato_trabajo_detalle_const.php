<?php date_default_timezone_set('America/Guayaquil'); $ordenNo = '-1'; if(isset($_GET['ordenNo']) && $_GET['ordenNo']!=''){$ordenNo = $_GET['ordenNo'];}?>

<script type="text/javascript">
 var ordenNo = '<?php echo $ordenNo; ?>';
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script type="text/javascript" src="../../dist/js/inventario/contrato_trabajo_const.js"></script>
<script type="text/javascript" src="../../dist/js/inventario/contrato_trabajo_detalle_const.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
  proyectos();
  contratistas();
  ddl_cuenta_contable();
  autocmpletar_cc();
  ddl_cate_contrato();
  lista_etapas();
  lista_cc();
  ddl_personal();
  if(ordenNo!='-1')
  {
    detalleContrato()
    lista_solicitud_rubro();
    lista_personal_contrato();
  }else
  {
    $('#myModal_proyecto').modal('show');
  }

   // $('#myModal_orden_trabajo').modal('show')

   // ddl_cuenta_contable();
   // ddl_Proceso();
   // ddl_Grupo();
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
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-sm-6">
              <h5>Resumen de Contrato</h5>
          </div>          
          <div class="col-sm-6 text-end">
            <button type="button" class="btn btn-primary btn-sm" onclick="ver_resumen()">Ver Resumen</button>            
          </div>
        </div>
      </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">              
              <b>Proyectos: </b><br>
              <label id="lbl_proyecto">-</label>
              <input type="hidden" name="txt_cuenta_proyecto" id="txt_cuenta_proyecto">
            </div>   
            <div class="col-sm-6">
              <b>Contratista: </b>    <br>         
              <label id="lbl_contratista">-</label>
            </div>
            <div class="col-sm-4">
                <b>Se entrega a cargo material: </b>
                <span id="lbl_material">-</span>
            </div>
            <div class="col-sm-5">
              <b>El contratista ejecuta el trabajo con mas de una persona:</b>
              <span id="lbl_mas_personas"> - </span>
            </div>
           <!--  <div class="col-sm-12">
              <b>Nombre de contrato: </b><br>
              <label id="lbl_nombre_contrato">-</label>
            </div> -->
           <div class="col-sm-3">
            <b>Categoria Contrato: </b>
            <label id="lbl_categoria">-</label>
          </div>
          <div class="col-sm-4">
            <b>Tipo Costo: </b>            
            <label id="lbl_tipo_costo">-</label>
          </div>
          <div class="col-sm-4">
            <b>Cuenta contable: </b>            
            <label id="lbl_cuenta_contable">-</label>
          </div>
          <div class="col-sm-2">
            <b>Fecha inicio: </b><br>    
            <label id="lbl_fecha">0000-00-00</label>          
          </div>
          <div class="col-sm-2">
            <b>Fecha fin: </b>     <br>
            <label id="lbl_fecha_v">0000-00-00</label>
          </div>
        </div>
        <hr>
      </div>
    </div> 
  </div>
</div>
<div class="row">
  <div class="col-sm-6">
    <div class="card">          
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div>
                <h5 class="mb-0">Personal</h5>
              </div>
               <div class="ms-auto"><button class="btn btn-outline-secondary" onclick="agregar_personal()"><i class="font-22 bx bx-plus"></i>Agregar</button>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-12">
                <div class="table-responsive">
                  <table class="table w-100" id="tbl_lista_solicitud_rubro">
                      <thead>
                        <td></td>
                         <th>No</th>
                         <th>Personal</th>
                         <th>Cargo</th>
                         <th>Cedula</th>
                         <th>Edad</th>
                         <th>Fecha Inicio</th>
                         <th>Fecha Fin</th>
                      </thead>
                      <tbody id="tbl_body_personal">
                      </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>    
        </div>
      </div>


  <div class="col-sm-6">
        <div class="card">          
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div>
                <h5 class="mb-0">Orden de trabajo</h5>
              </div>
               <div class="ms-auto"><button class="btn btn-outline-secondary" onclick="agregar_a_orden()"><i class="font-22 bx bx-plus"></i>Agregar</button>
              </div>
            </div>
            <hr>

             <div class="row">
                <div class="accordion" id="accordionExample">
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Accordion Item #1
                      </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                      <div class="accordion-body"> 
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="table-responsive">
                              <table class="table w-100" id="tbl_lista_solicitud_rubro">
                                  <thead>
                                     <th>No</th>
                                     <th>Categoria del servicio</th>
                                     <th>U/m</th>
                                     <th>Costo/Uni</th>
                                     <th>Cant</th>
                                     <th>Total</th>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>1</td>
                                      <td>Puerta batiente</td>
                                      <td>$/m2</td>
                                      <td>75.73</td>
                                      <td>10</td>
                                      <td>757.3</td>
                                    </tr>
                                  </tbody>
                              </table>
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Accordion Item #2
                      </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="table-responsive">
                              <table class="table w-100" id="tbl_lista_solicitud">
                                  <thead>
                                     <th>No</th>
                                     <th>Categoria del servicio</th>
                                     <th>U/m</th>
                                     <th>Costo/Uni</th>
                                     <th>Cant</th>
                                     <th>Total</th>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td colspan="6">Sin datos</td>
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
          </div>    
    </div>
  </div>

  
</div>



<div id="myModal_proyecto" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
 <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h4 class="modal-title">Nuevo Contrato</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <b>Proyectos</b>
            <select class="form-select form-select-sm" name="ddl_proyecto" id="ddl_proyecto">
              <option value="">Seleccione proyecto</option>
            </select>    
          </div>
          <div class="col-sm-12">
            <b>Contratista</b>
            <br>
            <select class="form-select form-select-sm" style="width: 100%;" name="ddl_contratista" id="ddl_contratista">
              <option value="">Seleccione proyecto</option>
            </select>
          </div>
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-9">
                SE ENTREGA A CARGO MATERIAL
              </div>
              <div class="col-sm-3 text-end">
                <label><input type="radio" name="rbl_material" id="" value="1"> SI</label>
                <label><input type="radio" name="rbl_material" id="" value="0" checked> NO</label>
              </div>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-9">
                MAQUINARIA
              </div>
              <div class="col-sm-3 text-end">
                <label><input type="radio" name="rbl_mas_personas" id="" value="1"> SI</label>
                <label><input type="radio" name="rbl_mas_personas" id="" value="0" checked> NO</label>
              </div>
            </div>
          </div>
          <!-- <div class="col-sm-12">
            <b>Nombre de contrato</b>
            <input type="" name="txt_nombre_contrato" id="txt_nombre_contrato" class="form-control form-control-sm">
          </div> -->
           <div class="col-sm-6">
            <b>Tipo de Contrato</b>
            <br>
            <select class="form-select form-select-sm" style="width: 100%;" name="ddl_cate_contrato" id="ddl_cate_contrato">
              <option value="">Seleccione proyecto</option>
            </select>
          </div>
         <!--  <div class="col-sm-6">
            <b>Tipo Costo</b>
            <br>
            <select class="form-select form-select-sm" style="width: 100%;" name="ddl_cc_" id="ddl_cc_">
              <option value="">Seleccione proyecto</option>
            </select>
          </div> -->
          <div class="col-sm-6">
            <!-- <b>Cuenta contable</b>
            <br>
            <select class="form-select form-select-sm" style="width: 100%;" name="ddl_cuenta_contable" id="ddl_cuenta_contable">
              <option value="">Seleccione proyecto</option>
            </select> -->
          </div>
          <div class="col-sm-6">
            <b>Fecha inicio</b>            
            <input type="date" name="txt_fecha_inicio" id="txt_fecha_inicio" class="form-control form-control-sm">
          </div>
           <div class="col-sm-6">
            <b>Fecha fin</b>            
            <input type="date" name="txt_fecha_fin" id="txt_fecha_fin" class="form-control form-control-sm">
          </div>
          
        </div>
           
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" onclick="GuardarContrato()">Aceptar</button> 
        <a href="../vista/inicio.php?mod=03&acc=contrato_trabajo_const" class="btn btn-default">Cerrar</a>
      </div>
    </div>

  </div>
</div>



<div id="myModal_orden_trabajo" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
 <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h4 class="modal-title">Agregar a Orden de trabajo</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <b>Etapa</b><br>
            <select class="form-select form-select-sm" style="width:100%" name="ddl_etapa" id="ddl_etapa">
              <option value="">Seleccione proyecto</option>
            </select>    
          </div>
          <div class="col-sm-12">
            <b>Centro de costos</b>
            <br>
            <select class="form-select form-select-sm" style="width: 100%;" name="ddl_cc" id="ddl_cc">
              <option value="">Seleccione proyecto</option>
            </select>
          </div>
          <div class="col-sm-12">
            <b>Rubro</b><br>
            <select class="form-select form-select-sm" style="width:100%" name="ddl_Rubro" id="ddl_Rubro">
              <option value="">Seleccione proyecto</option>
            </select>    
          </div>
          
          <div class="col-sm-3">
            <b>U/m</b>
            <input type="" name="txt_unidad_med" id="txt_unidad_med" class="form-control form-control-sm">
          </div>
          <div class="col-sm-4">
            <b>Costo Unitario</b>
            <input type="" name="txt_pvp" id="txt_pvp" class="form-control form-control-sm" onblur="calcular_total()">
          </div>
          <div class="col-sm-4">
           <b>Cantidad</b>
            <input type="" name="txt_cantidad" id="txt_cantidad" class="form-control form-control-sm" onblur="calcular_total()">
          </div>
           <div class="col-sm-4">
           <b>Total</b>
            <input type="" name="txt_total" id="txt_total" class="form-control form-control-sm" readonly>
          </div>
        </div>
           
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-sm" onclick="ingresar_orden()">Agregar</button> 
        <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-bs-label="Close">Cerrar</button>
      </div>
    </div>

  </div>
</div>

<div id="myModal_personal" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
 <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h4 class="modal-title">Agregar a personal</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <b>Personal</b><br>
            <div class="input-group">
               <select class="form-select form-select-sm" style="width:100%" name="ddl_personal" id="ddl_personal">
                <option value="">Seleccione proyecto</option>
              </select>
              <button type="button" class="btn btn-primary"><i class="bx bx-home-alt"></i></button>                 
            </div>
            
          </div>
        </div>
           
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-sm" onclick="ingresar_personal_orden()">Agregar</button> 
        <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-bs-label="Close">Cerrar</button>
      </div>
    </div>

  </div>
</div>


<div id="myModal_ver_resumen" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
 <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h4 class="modal-title">Agregar a personal</h4>
      </div>
      <div class="modal-body">
        
        <div class="row">
          <div class="col-sm-12">
            <div class="table-responsive">
              <table class="table w-100" id="tbl_lista_solicitud">
                  <thead>
                     <th>No</th>
                     <th>Categoria del servicio</th>
                     <th>U/m</th>
                     <th>Costo unitario</th>
                     <th>ESPECIFICACIONES</th>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="5">Sin datos</td>
                    </tr>
                  </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-sm" onclick="ingresar_personal_orden()">Agregar</button> 
        <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-bs-label="Close">Cerrar</button>
      </div>
    </div>

  </div>
</div>