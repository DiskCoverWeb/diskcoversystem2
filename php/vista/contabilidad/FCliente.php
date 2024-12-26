<?php
$mostrar_medidor = false;
switch ($_SESSION['INGRESO']['modulo_']) {
  case '07': //AGUA POTABLE
    $mostrar_medidor = true;
    break;
  default:

    break;
}
?>

<!--SCRIPTS-->
<script type="text/javascript">
  var prove = '<?php if (isset($_GET['proveedor'])) {
    echo 1;
  } ?>'
</script>
<script src="../../dist/js/FCliente.js"></script>

<style type="text/css">
  .visible {
    visibility: visible;
  }

  .no-visible {
    visibility: hidden;
  }

  .LblSRI {
    display: inline-grid;
    max-width: 80%;
  }

  .LblSRI p {
    padding: 0;
  }

  #swal2-content {
    font-weight: 600;
  }

  #panel-container {
    max-width: 350px;
    width: 350px;
    height: 150px;
  }

  .footer-content {
    display: flex;
    align-items: start;
    justify-content: space-between;
  }

  .custom-gray {
    background-color: #f2f2f2; /*Un gris claro que he definido, fuera de bootstrap*/ }
</style>
<div class="overflow-auto">
  <div class="">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?>
      </div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
          </ol>
        </nav>
      </div>          
    </div>
	</div>
  <!-- BOTONES CXC y CXP -->
  <div class="row row-cols-auto">
    <div class="d-flex justify-content-center align-items-center btn-group">
        <a href="./farmacia.php?mod=Farmacia#" data-toggle="tooltip" title="Salir de modulo" class="btn btn-sm btn-outline-secondary"
          >
          <img src="../../img/png/salire.png">
        </a>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cargar_cuentas('cxc')" data-toggle="tooltip"
          title="Asignar a Cuenta por Cobrar Contabilidad" >
          <img src="../../img/png/cxc.png">
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cargar_cuentas('cxp')" data-toggle="tooltip"
          title="Asignar a Cuenta por Pagar Contabilidad " >
          <img src="../../img/png/cxp.png">
        </button>
    </div>
  </div>
  <!-- FIN BOTONES CXC y CXP -->

  <div class="box box-info p-2">

    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form_cliente">
      <div class="box-body p-3">
        <div class="row">
          <div class="col-xs-4 col-sm-3 ">
            <label for="ruc" class="control-label" id="resultado"><span style="color: red;">*</span>RUC/CI</label>
            <input type="hidden" class="form-control" id="txt_id" name="txt_id" placeholder="ruc" autocomplete="off">
            <input type="text" class="form-control form-control-sm" id="ruc" name="ruc" placeholder="RUC/CI" autocomplete="off"
              onblur="buscar_numero_ci();/*codigo()*/" style="z-index: 1;">
            <span class="help-block" id='e_ruc' style='display:none;color: red;'>Debe ingresar RUC/CI</span>

          </div>
          <div class="col-sm-1 p-0"><br>
            <!-- <iframe src="https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/existePorNumeroRuc?numeroRuc=1722214507001&output=embed"></iframe> -->
            <button type="button" class="btn btn-sm" onclick="validar_sriC($('#ruc').val())">
              <img src="../../img/png/SRI.jpg" style="width: 60%">
            </button>

          </div>
          <div class=" col-sm-3 ">
            <label for="telefono" class="col-sm-1 control-label"><span style="color: red;">*</span>Telefono</label>
            <input type="text" class="form-control form-control-sm" id="telefono" name="telefono" placeholder="Telefono"
              autocomplete="off">
            <span class="help-block" id='e_telefono' style='display:none;color: red;'>Debe ingresar Telefono</span>
          </div>
          <div class="col-sm-3 ">
            <label for="codigoc" class="control-label"><span style="color: red;">*</span>Codigo</label>
            <input type="hidden" id='buscar' name='buscar' value='' />
            <input type="text" id='TD' name='TD' value='' readonly style="width:30px" />
            <input type="text" class="form-control form-control-sm" id="codigoc" name="codigoc" placeholder="Codigo" readonly="">
            <span class="help-block" id='e_codigoc' style='display:none;color: red;'>debe agregar Codigo</span>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-9 col-sm-11 col-lg-10">
            <label for="nombrec" class="control-label"><span style="color: red;">*</span>Apellidos y Nombres</label>
            <input type="text" class="form-control form-control-sm" id="nombrec" name="nombrec" placeholder="Razon social"
              onkeyup="buscar_cliente_nom();" onblur="mayusculas('nombrec',this.value);">
            <span class="help-block" id='e_nombrec' style='display:none;color: red;'>Debe ingresar nombre</span>
          </div>
          <div class="col-sm-3 col-sm-1 col-lg-2">
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
          <div class="col-sm-4 col-xs-4">
            <b>Abreviado</b>
            <input type="" name="txt_ejec" id="txt_ejec" class="form-control form-control-sm">
          </div>
          <div class="col-sm-8 col-xs-8">
            <b>Tipo de proveedor</b>
            <select class="form-select form-select-sm" id="txt_actividadC" name="txt_actividad">
              <option value=".">Seleccione</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-5">
            <label for="nv" class="control-label">Ubicacion geografica</label>
            <input type="text" class="form-control form-control-sm" id="nv" name="nv" placeholder="Numero vivienda" tabindex="0"
              onkeyup="mayusculas('nv',this.value)" onblur="mayusculas('nv',this.value)">
          </div>
          <div class="col-sm-2">
            <label for="grupo" class="control-label">Grupo</label>
            <input type="text" class="form-control form-control-sm" id="grupo" name="grupo" placeholder="Grupo" tabindex="0">
          </div>
          <div class="col-sm-5">
            <label for="naciona" class="col-sm-1 control-label">Nacionalidad</label>
            <input type="text" class="form-control form-control-sm" id="naciona" name="naciona" placeholder="Nacionalidad" tabindex="0">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <label for="prov" class="control-label">Provincia</label>
            <select class="form-select form-select-sm" id="prov" name="prov">
              <option value="." >Seleccione una provincia</option>
            </select>
          </div>
          <div class="col-sm-6">
            <label for="ciu" class="control-label">Ciudad</label>
            <input type="text" class="form-control form-control-sm" id="ciu" name="ciu" placeholder="Ciudad" tabindex="0">
          </div>
        </div>
        <?php if ($mostrar_medidor): ?>
          <div class="row">
            <div class="col-sm-6 col-sm-4">
              <label for="CMedidor" class="control-label">Medidor No.</label>
              <div class="input-group contenedor_item_center">
                <select class="form-control form-control-sm" id="CMedidor" name="CMedidor">
                  <option value="<?php echo G_NINGUNO ?>">NINGUNO</option>
                </select>
                <a class="btn btn-sm btn-success no-visible" id="AddMedidor" title="Agregar Medidor"
                  onclick="AddMedidor()"><i class="fa fa-plus"></i></a>
                <a class="btn btn-sm btn-danger no-visible" id="DeleteMedidor" title="Eliminar Medidor"
                  onclick="DeleteMedidor()"><i class="fa fa-trash-o"></i></a>

              </div>
            </div>
          </div>
        <?php endif ?>
      </div>
      <!-- /.box-body -->
      <div class="box-footer p-3">
        <div class="footer-content">
          <button type="button" id="BtnGuardarClienteFCliente" onclick="guardar_cliente()" class="btn btn-primary ml-2">Guardar
          </button>
          <!--PRODUCTOS RELACIONADOS -->
          <div class="panel panel-default border" id="panel-container">
            <div class="panel-heading border p-2 custom-gray">
              <h3 class="panel-title h6 text-center">PRODUCTOS RELACIONADOS</h3>
            </div>
            <div class="panel-body" style="max-height: 95px; overflow-y: auto;">
              <ul style="padding-left: 15px;" id="listaProductosRelacionados">

              </ul>
            </div>
          </div>
          <!--FIN PRODUCTOS RELACIONADOS -->
        </div>
        <div class="text-left LblSRI">
        </div>
      </div>
      <!-- /.box-footer -->
    </form>
  </div>
  <!-- Modal CXC y CXP -->
  <div class="modal fade" id="modal_cuentas" role="dialog" data-keyboard="false" data-backdrop="static">
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
            <div class="col-sm-2">
              <div class="btn-group">
                <button class="btn btn-default btn-sm" onclick="guardar_cuentas()"><img
                    src="../../img/png/grabar.png"><br>&nbsp;&nbsp;&nbsp;Aceptar&nbsp;&nbsp;&nbsp;</button>
                <button class="btn btn-default" data-dismiss="modal" onclick="cancelar()"> <img
                    src="../../img/png/bloqueo.png"><br> Cancelar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- FIN Modal CXC y CXP -->
</div> 