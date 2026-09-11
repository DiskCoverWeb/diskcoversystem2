<?php @session_start();
date_default_timezone_set('America/Guayaquil'); //print_r($_SESSION['INGRESO']);die();
$cartera_usu = '';
$cartera_pass = '';
if (isset($_SESSION['INGRESO']['CARTERA_USUARIO'])) {
  $cartera_usu = $_SESSION['INGRESO']['CARTERA_USUARIO'];
  $cartera_pass = $_SESSION['INGRESO']['CARTERA_PASS'];
}
$tipo = '';

if (isset($_GET['tipo']) && $_GET['tipo'] == 2) {
  $tipo = 2;
}

?>
<script>  
    var cartera_usu = '<?php echo $cartera_usu; ?>';
    var cartera_pas = '<?php echo $cartera_pass; ?>';
    var periodo = '<?php echo $_SESSION['INGRESO']['periodo']; ?>';
    var tipo = '<?php echo $tipo; ?>';
  
</script>
<script src="../../dist/js/facturacion/cartera_clientes.js"></script>
<link rel="stylesheet" href="../../dist/css/customTable.css">

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
  <div class="col-sm-12">
    <div class="btn-group">
      <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
        print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
        <img src="../../img/png/salire.png">
      </a>
      <button type="button" class="btn btn-outline-secondary" title="Generar Pdf" onclick="reporte_pdf()" id="btnPDF"><img
          src="../../img/png/pdf.png"></button>
      <button type="button" class="btn btn-outline-secondary" title="Generar Excel" onclick="generar_excel()" id="btnExcel"><img
          src="../../img/png/table_excel.png"></button>
      <button type="button" class="btn btn-outline-secondary" style="display: none;" title="Enviar Email" onclick="modal_email_fac()"><img
          src="../../img/png/email.png"></button>
    </div>
    
  </div>
</div>

<div class="row mb-2">
  <div class="col-sm-12">
    <div class="panel panel-primary">
      <div class="panel-body">
        <form id="filtros">
          <div class="row">
            <div class="col-sm-2" style="display:none;">
              <b>GRUPO</b>
              <select class="form-control input-xs" id="ddl_grupo" name="ddl_grupo" onchange="autocmpletar_cliente()">
                <option value=".">TODOS</option>
              </select>
              <!-- <input type="text" name="txt_grupo" id="txt_grupo" class="form-control input-sm"> -->
            </div>
            <div class="col-sm-2" id="campo_estado">
              <b>Estado</b>
              <select class="form-select form-select-sm" name="DCLinea" id="ddl_estado" tabindex="1"
                style="padding-left:8px" onchange="autocmpletar_cliente_tipo2()">
                <option value="P">Pendiente</option>
                <option value="C">Cancelado</option>
                <option value="A">Anulado</option>
              </select>
            </div>
            <div class="col-sm-5">
              <b>CI / RUC</b>
              <select class="form-control input-xs" id="ddl_cliente" name="ddl_cliente"
                onchange="periodos(this.value);rangos();">
                <option value="">Seleccione Cliente</option>
              </select>
            </div>
            <div class="col-sm-1" style="padding: 0px;">
              <b>Serie</b>
              <select class="form-select form-select-sm" name="DCLinea" id="DCLinea" tabindex="1" style="padding-left:8px">
                <option value=""></option>
              </select>
            </div>
            <div class="col-sm-2" id="campo_clave">
              <b>CLAVE</b>
              <input type="password" name="txt_clave" id="txt_clave" class="form-control form-control-sm">
              <a href="#" onclick="recuperar_clave()"><i class="fa fa-key"></i> Recupera clave</a>
            </div>
            <div class="col-sm-2" style="display:none;">
              <b>Periodo</b>
              <select class="form-control input-xs" id="ddl_periodo" name="ddl_periodo" onchange="rangos()">
                <option value=".">Seleccione perido</option>
              </select>
            </div>
            <div class="col-sm-2">
              <b>Desde</b>
              <input type="date" name="txt_desde" id="txt_desde" class="form-control form-control-sm"
                value="<?php echo date('Y-m-d') ?>">
            </div>
            <div class="col-sm-2">
              <b>Hasta</b>
              <input type="date" name="txt_hasta" id="txt_hasta" class="form-control form-control-sm"
                value="<?php echo date('Y-m-d') ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 text-end">
            </div>
            <div class="col-sm-6 text-end">
              <button class="btn btn-primary btn-sm" type="button" onclick="validar_neo()"><i class="fa fa-search"></i>
                Buscar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="panel" id="panel_datos" style="display:none;margin-bottom: 1px;">
  <div class="row">
    <div class="col-sm-4">
      <b>Cliente: </b><i id="lbl_cliente"></i>
    </div>
    <div class="col-sm-3">
      <b>CI / RUC: </b><i id="lbl_ci_ruc"></i>
    </div>
    <div class="col-sm-3">
      <b>Telefono: </b><i id="lbl_tel"></i>
    </div>
    <div class="col-sm-4">
      <b>Email: </b><i id="lbl_ema"></i>
    </div>
    <div class="col-sm-8">
      <b>Direccion: </b><i id="lbl_dir"></i>
    </div>
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true" style="display:none;">Todos</a></li>
              <li class="" id="tab_2_"><a href="#tab_2" data-toggle="tab" aria-expanded="false"
                  style="display:none;">Autorizados</a></li>
              <li class="" id="tab_3_"><a href="#tab_3" data-toggle="tab" aria-expanded="false" style="display:none;">No
                  Autorizados</a></li>
              <li class="" id="tab_4_" onclick="cargar_lineas()"><a href="#tab_4" data-toggle="tab"
                  aria-expanded="false">Detalle Factura</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="row">
                  <div class="col-sm-6">
                    <h2 style="margin-top: 0px;">Listado de facturas</h2>
                  </div>
                  <div class="col-sm-6 text-end" id="panel_pag">
                    <label for="Sum_SaldoMN" id="label_SumSaldoMN"> Total Saldo MN:
                      <input type="text" readonly id="Sum_SaldoMN" style="text-align: right;" class="form-control form-control-sm">
                    </label>
                  </div>
                  <div class="col-sm-12" style="overflow-x: scroll;height: 500px;">
                    <table class="resp table" style="white-space: nowrap;" id="tablaContenedor">
                    </table>

                  </div>
                </div>
              </div>

              <div class="tab-pane" id="tab_2">
                <div class="row">
                  <div class="col-sm-6">
                    <h2 style="margin-top: 0px;">Listado de facturas</h2>
                  </div>
                  <div class="col-sm-6 text-end" id="panel_pagAu">

                  </div>
                  <div class="col-sm-12" style="overflow-x: scroll;height: 500px;">
                    <table class="table text-sm" style=" white-space: nowrap;">
                      <thead>
                        <th></th>
                        <th>T</th>
                        <th>Razon_Social</th>
                        <th>TC</th>
                        <th>Serie</th>
                        <th>Autorizacion</th>
                        <th>Factura</th>
                        <th>Fecha</th>
                        <th>SubTotal</th>
                        <th>Con_IVA</th>
                        <th>IVA</th>
                        <th>Descuento</th>
                        <th>Total</th>
                        <th>Saldo</th>
                        <th>RUC_CI</th>
                        <th>TB</th>
                      </thead>
                      <tbody id="tbl_tablaAu">
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
                        </tr>
                      </tbody>
                    </table>

                  </div>
                </div>


              </div>

              <div class="tab-pane" id="tab_3">
                <div class="row">
                  <div class="col-sm-6">

                    <div class="input-group margin">
                      <div class="input-group-btn open">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                          aria-expanded="true">Acciones
                          <span class="fa fa-caret-down"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <li onclick="autorizar_blo()"><a href="#">Autorizar en bloque</a></li>
                          <li onclick=""><a href="#">Anular en bloque</a></li>
                          <!--<li><a href="#">Something else here</a></li> -->
                        </ul>
                      </div>
                    </div>
                    <h2 style="margin-top: 0px;">Listado de facturas</h2>
                  </div>
                  <div class="col-sm-6 text-end" id="panel_pagNoAu">

                  </div>
                  <div class="col-sm-12" style="overflow-x: scroll;height: 500px;">
                    <table class="table text-sm" style=" white-space: nowrap;">
                      <thead>
                        <th></th>
                        <th>T</th>
                        <th>Razon_Social</th>
                        <th>TC</th>
                        <th>Serie</th>
                        <th>Autorizacion</th>
                        <th>Factura</th>
                        <th>Fecha</th>
                        <th>SubTotal</th>
                        <th>Con_IVA</th>
                        <th>IVA</th>
                        <th>Descuento</th>
                        <th>Total</th>
                        <th>Saldo</th>
                        <th>RUC_CI</th>
                        <th>TB</th>
                      </thead>
                      <tbody id="tbl_tablaNoAu">
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
                        </tr>
                      </tbody>
                    </table>

                  </div>
                </div>
              </div>
              <div class="tab-pane" id="tab_4">
                <div class="row">
                  <div class="col-sm-6">
                    <h2 style="margin-top: 0px;">Listado de facturas</h2>
                  </div>
                  <div class="col-sm-6 text-end" id="panel_pag">

                  </div>
                  <div class="col-sm-12" style="overflow-x: scroll;height: 500px;">
                    <table class="table text-sm" style=" white-space: nowrap;">
                      <thead>
                        <th>T</th>
                        <th>Producto</th>
                        <th>TC</th>
                        <th>Serie</th>
                        <th>Autorizacion</th>
                        <th>Factura</th>
                        <th>Fecha</th>
                        <th>Mes</th>
                        <th>Año</th>
                        <th>IVA</th>
                        <th>Descuento</th>
                        <th>Total</th>
                        <th>RUC_CI</th>
                      </thead>
                      <tbody id="tbl_tabla_detalle">
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


<div id="modal_email" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Recuperar Clave</h4>
      </div>
      <div class="modal-body">
        <div class="col-sm-12">
          <p>Su nueva clave se enviara al correo:</p>
          <h5 id="lbl_email">El usuario no tien un Email registrado contacte con la institucion</h5>
          <input type="hidden" name="txt_email" id="txt_email">
          <!-- <form enctype="multipart/form-data" id="form_img" method="post"> -->

          <!-- </form>   -->
          <br>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-sm btn-block" id="btn_email" onclick="enviar_mail()"> Enviar
            Email</button>
          <button type="button" class="btn btn-default btn-sm btn-block" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal_email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enviar email</h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div id="emails-input" name="emails-input" placeholder="añadir email"></div>
            <input type="hidden" name="txt_fac" id="txt_fac">
            <input type="hidden" name="txt_serie" id="txt_serie">
            <input type="hidden" name="txt_codigoc" id="txt_codigoc">
            <input type="hidden" name="txt_to" id="txt_to">
          </div>
          <div class="col-sm-12">
            <input type="" id="txt_titulo" name="txt_titulo" class="form-control form-control-sm"
              placeholder="titulo de correo" value="comprobantes">
          </div>
          <div class="col-sm-12">
            <textarea class="form-control" rows="3" style="resize:none" placeholder="Texto" id="txt_texto"
              name="txt_texto"></textarea>
          </div>
          <div class="col-sm-3">
            <label><input type="checkbox" name="cbx_factura" id="cbx_factura" checked>Enviar Factura</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="enviar_email()">Enviar</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="myModal_bloque" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Respuesta autorizacion en bloque</h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12" id="bloque_resp" style="height:350px; overflow-y: scroll;">

          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>





<script src="../../dist/js/utils.js"></script>
<script src="../../dist/js/emails-input.js"></script>
<script src="../../dist/js/multiple_email.js"></script>