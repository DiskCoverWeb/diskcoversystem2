<?php date_default_timezone_set('America/Guayaquil'); $ordenNo = '-1'; if(isset($_GET['ordenNo']) && $_GET['ordenNo']!=''){$ordenNo = $_GET['ordenNo'];}?>

<script type="text/javascript">
 var ordenNo = '<?php echo $ordenNo; ?>';
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script type="text/javascript" src="../../dist/js/inventario/contrato_trabajo_detalle_const.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
  // ddl_cuenta_contable();
  // autocmpletar_cc();
  // ddl_cate_contrato();
  // lista_etapas();
  // lista_cc();
  // ddl_personal();
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
        <button title="Guardar"  class="btn btn-outline-secondary" onclick="grabar_orden_trabajo()">
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
               <div class="ms-auto"><button class="btn btn-sm btn-outline-secondary" onclick="agregar_personal()"><i class="font-22 bx bx-plus"></i>Agregar</button>
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
               <div class="ms-auto"><button class="btn btn-sm btn-outline-secondary" onclick="agregar_a_orden()"><i class="font-22 bx bx-plus"></i>Agregar</button>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                          <th></th>
                          <th>No</th>
                          <th>Rubro</th>
                          <th>Centro de costos</th>
                          <th>U/m</th>
                          <th>Cant</th>
                          <th>Costo/Uni</th>
                          <th>Total</th>
                        </thead>
                        <tbody id="tbl_body_orden">
                          
                        </tbody>
                    </table>
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
            <b>Proyectos</b><br>
            <select class="form-select form-select-sm" name="ddl_proyecto" id="ddl_proyecto" onchange="ddl_cate_contrato()">
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
        <a href="../vista/inicio.php?mod=<?php echo $_GET['mod']; ?>&acc=contrato_trabajo_const" class="btn btn-default">Cerrar</a>
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
            <select class="form-select form-select-sm" style="width:100%" name="ddl_etapa" id="ddl_etapa" onchange="ddl_Rubro()">
              <option value="">Seleccione proyecto</option>
            </select>    
          </div>
           <div class="col-sm-12">
            <b>Rubro</b><br>
            <select class="form-select form-select-sm" style="width:100%" name="ddl_Rubro" id="ddl_Rubro">
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
            <div class="d-flex align-items-center">
               <select class="form-select form-select-sm" style="width:100%" name="ddl_personal" id="ddl_personal">
                <option value="">Seleccione proyecto</option>
              </select>
              <button type="button" class="btn btn-sm btn-primary"onclick="nuevo_personal()" ><i class="bx bx-plus me-0"></i></button>
            </div>
          </div>
          <div class="col-sm-6">
            <b>Fecha Inicio</b>
            <input type="date" name="txt_fecha_ini_p" id="txt_fecha_ini_p" class="form-control form-control-sm">            
          </div>
           <div class="col-sm-6">
            <b>Fecha Fin</b>
            <input type="date" name="txt_fecha_fin_p" id="txt_fecha_fin_p" class="form-control form-control-sm">            
          </div>
          <div class="col-sm-12 d-none" id="pnl_data_personal">
              <div class="row">
                 <div class="col-sm-6">
                    <label><b>Cedula:</b></label>
                    <label id="lbl_ci_ruc">-</label>
                 </div>              
                 <div class="col-sm-6">
                    <label><b>Fecha Nacimiento:</b></label>
                    <label id="lbl_fecha_na">-</label>
                   
                 </div>              
                 <div class="col-sm-3">
                    <label><b>Edad:</b></label>
                    <label id="lbl_edad">-</label>
                 </div>   
                  <div class="col-sm-6">
                    <label><b>Cargo:</b></label>
                    <label id="lbl_cargo">-</label>
                 </div>                
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

<div id="myModal_nuevo_personal" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Agregar a personal</h4>
      </div>
      <div class="modal-body">  
        <div class="row">
          <form class="form-horizontal" id="form_cliente">
              <div class="row">
                <div class="col-sm-4">
                  <label for="ruc" class="control-label" id="resultado"><span style="color: red;">*</span>RUC/CI</label>
                    <div class="d-flex align-items-center">
                      <input type="hidden" class="form-control" id="txt_id" name="txt_id" placeholder="ruc" autocomplete="off">
                      <input type="text" class="form-control form-control-sm" id="ruc" name="ruc" placeholder="RUC/CI" autocomplete="off"
                      onblur="buscar_numero_ci()">
                      <button type="button" class="btn btn-sm" onclick="validar_sriC($('#ruc').val())" style="width: 20%">
                        <img src="../../img/png/SRI.jpg" style="width: 100%">
                      </button>
                    </div>
                    <span class="help-block" id='e_ruc' style='display:none;color: red;'>Debe ingresar RUC/CI</span>
                </div>
                <div class="col-sm-3">
                  <label for="telefono" class=""><span style="color: red;">*</span>Telefono</label>
                  <input type="text" class="form-control form-control-sm" id="telefono" name="telefono" placeholder="Telefono"
                      autocomplete="off">
                  <span class="help-block" id='e_telefono' style='display:none;color: red;'>Debe ingresar Telefono</span>
                </div>
                <div class="col-sm-3">
                  <label for="codigoc" class="control-label"><span style="color: red;">*</span>Codigo</label>
                  <input type="hidden" id='buscar' name='buscar' value='' />
                  <div class="input-group"> 
                    <input type="text" class="form-control form-control-sm" id="codigoc" name="codigoc" placeholder="Codigo" readonly="">
                    <input type="text" id='TD' name='TD' value='' readonly style="width:30px" />
                  </div>
                  <span class="help-block" id='e_codigoc' style='display:none;color: red;'>debe agregar Codigo</span>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-9 col-md-11 col-lg-10">
                  <label for="nombrec" class="control-label"><span style="color: red;">*</span>Apellidos y Nombres</label>
                  <input type="text" class="form-control form-control-sm" id="nombrec" name="nombrec" placeholder="Razon social"
                    onkeyup="buscar_cliente_nom();" onblur="mayusculas('nombrec',this.value);">
                  <span class="help-block" id='e_nombrec' style='display:none;color: red;'>Debe ingresar nombre</span>
                </div>
                <div class="col-sm-3 col-md-1 col-lg-2 d-none">
                  <br>
                  <label> </label><input type="checkbox" name="rbl_facturar" id="rbl_facturar" checked> Para Facturar
                </div>
              </div>
              <div class="row">
                <div class="col-sm-8">
                  <label for="direccion" class="control-label"><span style="color: red;">*</span>Direccion</label>
                  <input type="text" class="form-control form-control-sm" id="direccion" name="direccion" placeholder="Direccion"
                    tabindex="0" onblur="mayusculas('direccion',this.value)">
                  <span class="help-block" id='e_direccion' style='display:none;color: red;'>debe agregar Direccion</span>
                </div>
                <div class="col-sm-4">
                  <label for="email" class="control-label"><span style="color: red;">*</span>Email Principal</label>
                  <input type="email" class="form-control form-control-sm" id="email" name="email" placeholder="Email" tabindex="0"
                    onblur="validador_correo('email')">
                  <span class="help-block" id='e_email' style='display:none;color: red;'> debe agregar un email</span>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <b>Cargo</b>
                  <input type="" name="txt_ejec" id="txt_ejec" class="form-control form-control-sm">
                </div>
                <div class="col-sm-3">
                  <b>Fecha Nacimiento</b>
                  <input type="date" name="txt_fecha_na" id="txt_fecha_na" class="form-control form-control-sm" onchange="calcular_edad_form()">
                </div>
                <div class="col-sm-3">
                  <b>Edad</b>
                  <input type="" name="txt_edad" id="txt_edad" class="form-control form-control-sm" readonly>
                </div>
              </div>
          </form>
      </div>
    </div> 
    <div class="modal-footer">
        <button class="btn btn-primary btn-sm" onclick="guardar_cliente()">Agregar</button>
        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal" aria-bs-label="Close">Cerrar</button>   
    </div>   
  </div>
</div>
  <!-- Modal CXC y CXP -->
  <div class="modal fade" id="modal_cuentas" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="titulo">ASIGNACION DE CUENTAS POR COBRAR</h5>
        </div>
        <div class="modal-body">
          <div class="row">
            <form id="form_cuentas">
              <input type="hidden" name="SubCta" id="SubCta" value="">

              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-4">
                    <input id="txt_ci_cuenta" name="txt_ci_cuenta" class="form-control form-control-sm" readonly
                      style="background-color:black; color: yellow;" value="999999999999">
                  </div>
                  <div class="col-sm-8">
                    <input id="txt_nombre_cuenta" name="txt_nombre_cuenta" class="form-control form-control-sm" readonly
                      style="background-color:black; color: yellow;" value="CONSUMIDOR FINAL">
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <b>Asignar a:</b>
                    <select class="form-control form-control-sm" id="DLCxCxP" name="DLCxCxP" style="width: 100%;">
                      <option value="">Seleccione Cuenta</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <label><input type="checkbox" name="cbx_cuenta_g" id="cbx_cuenta_g" onclick="mostar_cuenta_Gastos()">
                      ASIGNAR A LA CUENTA DE GASTOS</label>
                  </div>
                </div>
                <div class="row" id="panel_cuenta_gasto" style="display:none">
                  <div class="col-sm-6 nopadding">
                    <select class="form-control form-control-sm col-sm-6" id="DLGasto" name="DLGasto">
                      <option value="">Seleccione Cuenta</option>
                    </select>
                  </div>
                  <div class="col-sm-6 nopadding">
                    <select class="form-control form-control-sm col-sm-6" id="DLSubModulo" name="DLSubModulo">
                      <option value="">Seleccione Cuenta</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <label><input type="checkbox" name="cbx_retencion" id="cbx_retencion"
                        onclick="mostar_porcentaje_retencion()"> PORCENTAJES DE RETENCION</label>
                  </div>
                </div>
                <div class="row" id="panel_retencion" style="display:none">
                  <div class="col-sm-4">
                    Codigo Retencion
                    <input type="" name="TxtCodRet" id="TxtCodRet" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-4">
                    Retencion IVA Bienes
                    <input type="" name="TxtRetIVAB" id="TxtRetIVAB" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-4">
                    Retencion IVA servicios
                    <input type="" name="TxtRetIVAS" id="TxtRetIVAS" class="form-control form-control-sm">
                  </div>

                </div>
              </div>
            </form>
     
            
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-sm" onclick="ingresar_personal_orden()">Agregar</button> 
        <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-bs-label="Close">Cerrar</button>
      </div>
    </div>

  </div>
</div>