<?php
 $ciCliente = "";
if(isset( $_GET['CIBuscar']))
{
  $ciCliente = explode("-",$_GET['CIBuscar']);
  $ciCliente = $ciCliente[0];
}
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

<script src="../../dist/js/contabilidad/FCliente.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        buscar_numero_ci();
    })

    function editar_cliente_cli()
    {
        $('#myModal_espera').modal('show');
        if (validar() == true) {
          swal.fire('Llene todos los campos', '', 'info').then(() => { 
            setTimeout(()=>{
              $('#myModal_espera').modal('hide');
            }, 2000);
          });
          return false;
        }
      var rbl = $('#rbl_facturar').prop('checked');
      var datos = $('#form_cliente_edit').serialize();
      console.log(datos);


        $.ajax({
          data: datos + '&rbl=' + rbl + '&cxp=' + prove,
          url: '../controlador/modalesC.php?guardar_cliente=true',
          type: 'post',
          dataType: 'json',
          success: function (response) {
            setTimeout(()=>{
              $('#myModal_espera').modal('hide');
            }, 2000);
            // console.log(response);
            var url = location.href;
            if (response == 1) {
              if ($('#txt_id').val() != '') {
                swal.fire('Registro guardado', '', 'success')
                .then(result => {
                  if(result.value){
                    $('#BtnGuardarClienteFCliente').attr('disabled', true);
                    window.location.reload();
                  }
                });
              } else {
                swal.fire('Registro guardado', '', 'success')
                .then(result => {
                  if(result.value){
                    $('#BtnGuardarClienteFCliente').attr('disabled', true);
                    window.location.reload();
                  }
                });
              }

            } else if (response == 2) {
              swal.fire('Este CI / RUC ya esta registrado', '', 'info');
            } else if (response == 3) {
              swal.fire('El Nombre ya esta registrado', '', 'info');
            }
          },
          error:function(err){
            swal.fire('Ocurrio un error al procesar la solicitud. Error: ' + err, '', 'error');
            setTimeout(() => {
              $('#myModal_espera').modal('hide');
            }, 2000)
          }
        });
    }

  var prove = '<?php if (isset($_GET['proveedor'])) {
    echo 1;
  } ?>'
</script>

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
  
  <div class="card">
    <div class="card-body">
      <form class="form-horizontal" id="form_cliente_edit">
      <!-- <div class="box-body p-3"> -->
        <div class="row">
          <div class="col-sm-4">
            <label for="ruc" class="control-label" id="resultado"><span style="color: red;">*</span>RUC/CI</label>
            <input type="hidden" class="form-control" id="txt_id" name="txt_id" placeholder="ruc" autocomplete="off">
            <input type="text" class="form-control form-control-sm" id="ruc" name="ruc" placeholder="RUC/CI" autocomplete="off"
              onblur="buscar_numero_ci();" style="z-index: 1;" readonly value="<?php echo  $ciCliente; ?>">
            <span class="help-block" id='e_ruc' style='display:none;color: red;'>Debe ingresar RUC/CI</span>

          </div>
        <!--   <div class="col-sm-1"><br>
            <button type="button" class="btn btn-sm" onclick="validar_sriC($('#ruc').val())">
              <img src="../../img/png/SRI.jpg" style="width: 60%">
            </button>

          </div> -->
          <div class="col-sm-4">
            <label for="codigoc" class="control-label"><span style="color: red;">*</span>Codigo</label>
            <div class="input-group">
                <input type="text" class="form-control form-control-sm" id="codigoc" name="codigoc" placeholder="Codigo" readonly="">
                <input type="text" id='TD' name='TD' value='' readonly style="width:30px" />              
            </div>
            <input type="hidden" id='buscar' name='buscar' value='' />
            <span class="help-block" id='e_codigoc' style='display:none;color: red;'>debe agregar Codigo</span>
          </div>
          <div class="col-sm-4">
            <label for="telefono" class=""><span style="color: red;">*</span>Telefono</label>
            <input type="text" class="form-control form-control-sm" id="telefono" name="telefono" placeholder="Telefono"
              autocomplete="off">
            <span class="help-block" id='e_telefono' style='display:none;color: red;'>Debe ingresar Telefono</span>
          </div>

        </div>
        <div class="row">
          <div class="col-sm-9 col-md-11 col-lg-10">
            <label for="nombrec" class="control-label"><span style="color: red;">*</span>Apellidos y Nombres</label>
            <input type="text" class="form-control form-control-sm" id="nombrec" name="nombrec" placeholder="Razon social"
              onkeyup="buscar_cliente_nom();" onblur="mayusculas('nombrec',this.value);">
            <span class="help-block" id='e_nombrec' style='display:none;color: red;'>Debe ingresar nombre</span>
          </div>
          <div class="col-sm-3 col-md-1 col-lg-2">
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
            <b>Abreviado</b>
            <input type="" name="txt_ejec" id="txt_ejec" class="form-control form-control-sm">
          </div>
          <div class="col-sm-8">
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
            <label for="naciona" class="control-label">Nacionalidad</label>
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
            <div class="col-sm-6 col-md-4">
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
      <!-- </div> -->
      <!-- /.box-body -->
      <div class="row">
        <div class="col-sm-6">
            <button type="button" id="BtnGuardarClienteFCliente" onclick="guardar_cliente()" class="btn btn-primary">Guardar
          </button>

          <br>
           <div class="text-left LblSRI"></div>
          
        </div> 
      </div>
     
      <!-- /.box-footer -->
      </form>
      
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
            <div class="col-sm-2">
              <div class="btn-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="guardar_cuentas()"><img
                    src="../../img/png/grabar.png"><br>&nbsp;&nbsp;&nbsp;Aceptar&nbsp;&nbsp;&nbsp;</button>
                <button class="btn btn-outline-danger" data-bs-dismiss="modal" onclick="cancelar()"> <img
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