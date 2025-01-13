<?php
  $mostrar_medidor = false;
  $d_none = 'none';
  switch ($_SESSION['INGRESO']['modulo_']) {
    case '07': //AGUA POTABLE
      $mostrar_medidor =  true;
      $d_none = '';
      break;    
    default:
      
      break;
  }
?>
<style type="text/css">
  .bg-amarillo{
    background: #bfc003;
  }

  .bg-amarillo-suave{
    background-color: #fffec5;
  }
</style>
<script type="text/javascript">
  mostrar_medidor = '<?php echo $mostrar_medidor; ?>'
</script>
<script src="../../dist/js/facturacion/facturar_pension.js"></script>
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
    <div class="col-lg-5 col-md-6 col-sm-12">
      <div class="btn-group" role="group" aria-label="Basic example">
        <a  href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" title="Salir de modulo" class="btn btn-outline-secondary">
          <img src="../../img/png/salire.png" width="25" height="30">
        </a>
         <a title="Presenta la historia del cliente" data-toggle="dropdown" class="btn btn-outline-secondary" >
          <img src="../../img/png/document.png" width="25" height="30">
        </a>
        <ul class="dropdown-menu">
          <li><a href="#" onclick="historiaClientePDF();">En PDF</a></li>
          <li><a href="#" onclick="historiaClienteExcel();">En Excel</a></li>
          <li><a href="#" onclick="enviarHistoriaCliente();">Por Email</a></li>
        </ul>
         <a href="#" title="Presenta la Deuda Pendiente"  class="btn btn-outline-secondary" onclick="DeudaPensionPDF()">
          <img src="../../img/png/project.png" width="25" height="30">
        </a>
        <?php include_once("prefactura.php") ?>
        <a href="#" title="Insertar nuevo Beneficiario/Cliente"  class="btn btn-outline-secondary" onclick="addCliente(1)">
          <img src="../../img/png/group.png" width="25" height="30">
        </a>
        <a href="#" title="Actualizar datos del Cliente"  class="btn btn-outline-secondary" onclick="Actualiza_Datos_Cliente()">
          <img src="../../img/png/update_user.png" width="25" height="30">
        </a>
      </div>
    </div>    
    <div class="col-lg-7 col-md-6 col-sm-12">
       <div class="row">
          <div class="col-lg-7">
              <div class="input-group">
                  <div class="input-group-addon form-control-sm p-2 text-box">
                    <b>Inicio Resumen</b>
                 </div>
                 <input type="date" class="form-control form-control-sm" id="MBHistorico" name="MBHistorico" readonly>
              </div>
          </div>
          <div class="col-lg-5">
            <div class="input-group">
               <div class="input-group-addon form-control-sm p-2 text-box">
                 <b>Factura No. <span id="numeroSerie" class="red"></span></b>
               </div>
                <input style="  max-width: 110px;" type="input" class="form-control form-control-sm" tabindex="1" name="factura" id="factura">
            </div>          
          </div>        
        </div>
    </div>
</div>
<div class="card mb-2">
  <div class="card-body">
      <div class="row">
          <div class="col-md-7 ">
            <div class="row mb-1">              
              <div class="col-lg-3">
                <b>Fecha Emision</b>
              <!-- </label> -->
                <!-- <div class="col"> -->
                  <input tabindex="2" type="date" name="fechaEmision" id="fechaEmision" class="form-control form-control-sm validateDate mw115" value="<?php echo date('Y-m-d'); ?>" onblur="catalogoLineas();DCPorcenIva('fechaEmision', 'DCPorcenIVA');">
                <!-- </div> -->
              </div>
              <div class="col-lg-3 col-xs-6 col-md-3">
                <b>Fecha Vencimiento</b>
                <!-- <div class="col"> -->
                  <input type="date" tabindex="3" name="fechaVencimiento" id="fechaVencimiento" class="form-control form-control-sm validateDate mw115" value="<?php echo date('Y-m-d'); ?>" onchange="catalogoLineas();">
                <!-- </div> -->
              </div>
              <div class="col-lg-2 col-md-2">
                 <b>I.V.A</b>
                 <select class="form-select form-select-sm" name="DCPorcenIVA" id="DCPorcenIVA"  tabindex="4" onchange="cambiar_iva(this.value);cambiarlabel()" onblur="cambiarlabel()"></select>
                
              </div>
              <div class="col-lg-4 col-xs-12 col-md-4">
                  <br>
                  <select class="form-select form-select-sm" name="DCLinea" id="DCLinea" tabindex="5" onchange="numeroFactura();">
                    <option value="">Sin Serie</option>
                  </select>
                  <input type="hidden" id="Autorizacion">
                  <input type="hidden" id="Cta_CxP">
              </div>
            </div>

            <div class="row mb-1">              
              <div class="col-xs-12 col-md-3 text-end">
                <b>Cliente/Alumno (<span class="spanNIC"></span>)</b>
              </div>
              <div class="col-xs-12  <?php echo ($mostrar_medidor)?'col-md-6':'col-md-9' ?> colCliente">
                <select class="form-select form-select-sm" id="cliente" name="cliente" tabindex="6">
                  <option value="">Seleccione un cliente</option>
                </select>
                <input type="hidden" name="codigoCliente" id="codigoCliente">
              </div>
              <?php if ($mostrar_medidor): ?>
              <div class="col-xs-12  col-md-3">
                <select class="form-select form-select-sm" id="CMedidorFiltro" name="CMedidorFiltro">  
                  <option value="<?php echo G_NINGUNO ?>">Medidores</option>
                </select>
              </div>
              <?php endif ?>
            </div>

            <div class="row mb-1">
              <div class="col-xs-12 col-md-3   ">
                <select class="form-select form-select-sm" id="DCGrupo_No" name="grupo" tabindex="8">
                  <option value=".">Grupo</option>
                </select>
              </div>
              <div class="col-xs-12 col-md-9  ">
                <input tabindex="9" type="input" class="form-control form-control-sm" name="direccion" id="direccion">
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12 col-md-3 text-end bg-amarillo">
                <b>Razón social</b>
              </div>
              <div class="col-xs-12 col-md-5 bg-amarillo-suave p-1">
                <input tabindex="10" type="input" class="form-control form-control-sm bg-amarillo-suave" name="persona" id="persona">
              </div>
              <div class="col-xs-12 col-md-2 text-end  bg-amarillo">
                <b>CI/RUC(<span class="spanNIC"></span>) </b>
              </div>
              <div class="col-xs-12 col-md-2 text-end  bg-amarillo-suave  p-1">
                <input  type="hidden" class="form-control form-control-sm" name="tdCliente" id="tdCliente" readonly>
                <input type="text" tabindex="11" name="TextCI" id="TextCI" class="form-control form-control-sm red text-end bg-amarillo-suave">
              </div>
            </div>

            <div class="row bg-amarillo-suave">
              <div class="col-xs-12 col-md-3 text-end  bg-amarillo">
                <b>Dirección</b>
              </div>
              <div class="col-xs-12 col-md-9  bg-amarillo-suave  p-1">
                <input tabindex="12" type="input" class="form-control form-control-sm  bg-amarillo-suave" style="text-transform: uppercase;" name="direccion1" id="direccion1">
              </div>  
            </div>

            <div class="row bg-amarillo-suave">
              <div class="col-xs-12 col-md-3 text-end  bg-amarillo">
                <b>Email</b>
              </div>
              <div class="col-xs-12 col-md-5  bg-amarillo-suave  p-1">
                <input tabindex="13" type="input" class="form-control form-control-sm bg-amarillo-suave"style="text-transform: uppercase;" name="email" id="email">
              </div>
              <div class="col-xs-12 col-md-2 text-end  bg-amarillo">
                <b>Telefono </b>
              </div>
              <div class="col-xs-12 col-md-2 text-end  bg-amarillo-suave  p-1">
                <input type="text" tabindex="14" name="telefono" id="telefono" class="form-control form-control-sm red text-end bg-amarillo-suave">
              </div>
            </div>
            <div class="row m-2">
              <div class="col-xs-12 text-center">
                <button tabindex="15" style="width:100%;" class="btn btn-block btn btn-outline-info px-5 btn-sm btnDepositoAutomatico">Ingrese sus datos para el Debito Automatico</button>
              </div>
            </div>
            <div class="row bg-info contenidoDepositoAutomatico p-2" style="display: none;">
              <div class="col-xs-12 col-sm-2 text-end p-0">
                <b for="debito_automatica">Debito Automatico</b>
              </div>
              <div class="col-xs-12 col-sm-6">
                <select tabindex="16"  class="form-select form-select-sm" name="debito_automatica" id="debito_automatica">
                  <option value="">Seleccione un Banco</option>
                </select>
              </div>

              <div class="col-xs-12 col-sm-1 text-end p-0">
                <b>Tipo</b>
              </div>
              <div class="col-xs-12 col-sm-3">
                <select  tabindex="17" type="input" class="form-select form-select-sm" name="tipo_debito_automatico" id="tipo_debito_automatico">
                  <option value=".">Seleccionar Tipo</option>
                  <option value="CORRIENTE">CORRIENTE</option>
                  <option value="AHORROS">AHORROS</option>
                  <option value="TARJETA">TARJETA</option>
                </select>
              </div>
            </div>
            <div class="row bg-info contenidoDepositoAutomatico" style="display: none;">
              <div class="col-xs-12 col-sm-2 text-end p-0">
                <b>Numero de Cuenta</b>
              </div>
              <div class="col-xs-12 col-sm-3">
                <input  tabindex="18" type="input" class="form-control form-control-sm" name="numero_cuenta_debito_automatico" id="numero_cuenta_debito_automatico">
              </div>

              <div class="col-xs-12 col-sm-1 text-end p-0">
                <b>Caducidad</b>
              </div>
              <div class="col-xs-12 col-sm-2 contenedor_fecha_caducidad">
                <input  tabindex="19" type="text" maxlength="7"  class="form-control form-control-sm fecha_caducidad" name="caducidad_debito_automatico" id="caducidad_debito_automatico" placeholder="MM/YYYY">
              </div>
              <div class="col-xs-6 col-sm-3 text-end">
                <b class="text-end" for="rbl_no">Depositar al Banco</b>
              </div>
              <div class="col-xs-6 col-sm-1 ">
                <input tabindex="20" style="margin-top: 0px;margin-right: 2px;" type="checkbox" name="por_deposito_debito_automatico" id="por_deposito_debito_automatico" onblur="$('#checkbox1').focus()">
              </div>
            </div>

          </div>
          <div class="col-lg-2 col-md-2">
            
            <div class="row">
              <div class="col-xs-12">
                <input tabindex="" type="checkbox" name="rbl_radio" id="rbl_no" checked=""> Con mes </div>
            </div>

            <div class="row mb-1">
              <div class="col-xs-12">
                <div class="input-group  ">
                  <span class="input-group-addon strong form-control-sm ps-0 px-1">
                    NIC (<span class="spanNIC"></span>)
                  </span>
                  <input type="text" tabindex="7" name="ci" id="ci_ruc" class="form-control form-control-sm red text-end p-1" readonly>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12 text-center">
                <div class="contenedor_img centrado_margin">
                  <img src="../img/img_estudiantes/SINFOTO.jpg" id="img_estudiante" class="img-responsive img-thumbnail">
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 ">
            <div class="row">
               <div class="input-group">
                  <div class="input-group-addon form-control-sm p-1 text-box col-6">
                    <b>Total Tarifa 0%</b>
                 </div> 
                 <input type="text" style="color: coral;" name="total0" id="total0" class="form-control form-control-sm red text-end " readonly value="0.00">
              </div>
            </div>
            <div class="row">
              <div class="input-group">
                  <div class="input-group-addon form-control-sm p-1 text-box col-6">
                    <b>Total Tarifa <span id="lbl_iva">0</span>%</b>
                 </div> 
                 <input type="text" style="color: coral;" name="total12" id="total12" class="form-control form-control-sm red text-end " readonly value="0.00">
              </div>
            </div>
            <div class="row">
              <div class="input-group">
                  <div class="input-group-addon form-control-sm p-1 text-box col-6">
                    <b>Descuentos</b>
                 </div> 
                 <input type="text" style="color: coral;" name="descuento" id="descuento" class="form-control form-control-sm red text-end " readonly value="0.00">
              </div>
            </div>
            <div class="row">
               <div class="input-group">
                  <div class="input-group-addon form-control-sm p-1 text-box col-6">
                    <b>Desc x P P</b>
                 </div> 
                    <button tabindex="41" style="border: 1px #b1b1b1 solid;border-right: 2px #b1b1b1 solid;padding: 2px;"  type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#myModalDescuentoP">%</button>
                    <input type="text" style="color: coral;"  name="descuentop" id="descuentop" class="form-control form-control-sm red text-end" readonly value="0.00">
              </div>
            </div>
            <div class="row">
               <div class="input-group">
                  <div class="input-group-addon form-control-sm p-1 text-box col-6">                 
                     <b>I.V.A. <span id="lbl_iva2"></span>%</b>
                  </div> 
                  <input type="text" style="color: coral;"  name="iva12" id="iva12" class="form-control form-control-sm red text-end " readonly value="0.00">
              </div>
            </div>
            <div class="row">
               <div class="input-group">
                  <div class="input-group-addon form-control-sm p-1 text-box col-6">
                    <b>Total Facturado</b>
                 </div> 
                  <input type="text" style="color: coral;"  name="total" id="total" class="form-control form-control-sm red text-end " readonly value="0.00" onblur="$('#TextBanco').focus()">
              </div>
            </div>
          </div>
      </div>
  </div>
</div>
<div class="card mb-2">
  <div class="card-body">
    <div class="row">
      <div class="col-sm-12">
        <div class="table-responsive" style="overflow-y: scroll; min-height: 50px;max-height:200px; width: auto;">
            <table id="tablaDetalle" class="table-sm" style="width: -webkit-fill-available;">
              <thead>
                <tr>
                  <th></th>
                  <th>Mes</th>
                  <th>Código</th>
                  <th>Año</th>
                  <th>Producto</th>
                  <th>Valor</th>
                  <th>Descuento</th>
                  <th>Desc. P. P.</th>
                  <th>Total</th>
                  <th style="display: <?php echo $d_none; ?>">Lectura</th>
                  <th style="display: <?php echo $d_none; ?>">Medidor</th>
                </tr>
              </thead>
              <tbody id="cuerpo">
              </tbody>
            </table>       
        </div>              
      </div>      
    </div>
  </div>
</div>
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-sm-2 text-end  ">
        <b>Detalle del pago</b>
      </div>
      <div class="col-sm-6 ">
        <input type="text" name="TextBanco" id="TextBanco" class="form-control form-control-sm" value="." tabindex="21">
      </div>

      <div class="col-sm-2 text-end  bg-amarillo">
        <b id="saldo">Saldo pendiente</b>
      </div>
      <div class="col-sm-2">
        <input type="input" id="saldoPendiente" class="form-control form-control-sm text-end bg-amarillo-suave" name="saldoPendiente">
      </div>
    </div>

    <div class="row">
      <div class="col-sm-2 text-end  ">
        <b>Bancos/Tarjetas</b>
      </div>
      <div class="col-sm-4 ">
        <select class="form-select form-select-sm" name="cuentaBanco" id="cuentaBanco" tabindex="29" onchange="verificarTJ();" onblur="$('#chequeNo').focus()">
         
        </select>
      </div>

      <div class="col-sm-1 text-end p-0">
        <b>Cheque No.</b>
      </div>
      <div class="col-sm-2 ">
        <input type="text" name="chequeNo" id="chequeNo" class="form-control form-control-sm text-end" tabindex="30"  >
      </div>
      <div class="col-sm-1 text-end ">
        <b>USD</b>
      </div>
      <div class="col-sm-2 ">
        <input  type="text" name="valorBanco" id="valorBanco" tabindex="31" onkeyup="calcularSaldo();" class="form-control form-control-sm red text-end " value="0.00">
      </div>
    </div>
    <div class="row">
      <div class="col-sm-2 text-end  ">
        <b>Anticipos</b>
      </div>
      <div class="col-sm-7 ">
        <select class="form-select form-select-sm" name="DCAnticipo" id="DCAnticipo" tabindex="32">         
        </select>
      </div>
      <div class="col-sm-1 text-end ">
        <b>USD</b>
      </div>
      <div class="col-sm-2">
        <input title="Saldo a Favor" type="input" id="saldoFavor" class="form-control form-control-sm red text-end " name="saldoFavor" tabindex="33" onkeyup="calcularSaldo();" value="0.00" style="color:yellowgreen;">
      </div>
    </div>
    <div class="row">
      <div class="col-sm-2 text-end  ">
        <b>Notas de crédito</b>
      </div>
      <div class="col-sm-7 ">
        <select class="form-select form-select-sm" name="cuentaNC" id="cuentaNC" tabindex="34">            
        </select>
      </div>
      <div class="col-sm-1 text-end ">
        <b>USD</b>
      </div>
      <div class="col-sm-2">
        <input tabindex="35" type="text" name="abono" id="abono" onkeyup="calcularSaldo();" class="form-control form-control-sm red text-end " value="0.00">
      </div>
    </div>
    <div class="row">
      <div class="col-sm-2 text-center  " style="visibility: hidden;">
        <input type="text" name="codigoB" class="form-control form-control-sm" id="codigoB" style="color: white; background: brown;" value="Código del banco: " readonly />
      </div>
      <div class="col-sm-8 text-end ">
        <b>Efectivo USD</b>
      </div>
      <div class="col-sm-2">
        <input tabindex="36" type="text" name="efectivo" id="efectivo" onkeyup="calcularSaldo();" class="form-control form-control-sm red text-end " value="0.00"  onblur="$('#saldo').focus()">
      </div>
    </div>
    <div class="row" id="divInteres">
      <div class="col-sm-10 text-end ">
        <b>Interés Tarjeta USD</b>
      </div>
      <div class="col-sm-2">
        <input tabindex="37" type="text" name="interesTarjeta" id="interesTarjeta" class="form-control form-control-sm red text-end " >
      </div>
    </div>
     <div class="row">
      <div class="col-sm-2 text-end  ">
        <label>Código interno</label>
         <input type="hidden" name="txt_cant_datos" id="txt_cant_datos" readonly>
      </div>
      <div class="col-xs-12 col-sm-4 col-lg-4">          
        <div class="col-xs-6 ">
          <input type="input" class="form-control form-control-sm" name="codigo" id="codigo" tabindex="41">
        </div>
      </div>
      <div class="col-sm-1 text-center justify-content-center align-items-center d-none">
        <input style="width: 50px" type="text" id="registros" class="form-control form-control-sm text-center justify-content-center align-items-center" readonly>
      </div>
      <div class=" col-sm-2 ">
          <a title="Guardar" class="btn btn-outline-secondary" tabindex="39" id="guardar">
            <img src="../../img/png/grabar.png" width="25" height="30" onclick="guardarPension();">
          </a>
          <a title="Salir del panel" class="btn btn-outline-secondary" tabindex="40" href="inicio.php?mod=02">
            <img src="../../img/png/salire.png" width="25" height="30" >
          </a>
      </div>
      <div class="col-sm-2 text-end  ">
        <b>Saldo USD</b>
      </div>
      <div class="col-sm-2 ">
        <input type="text" name="saldoTotal" id="saldoTotal" class="form-control form-control-sm red text-end" value="0.00" style="color:coral;" onblur="$('#guardar').focus()" tabindex="38" >
      </div>
    </div>

  </div>  
</div>


  <div class="row">
    <input type="hidden" name="" id="txt_pag" value="0">
    <div id="tbl_pag"></div>    
  </div>
</div>

<!-- Modal porcentaje-->
<div id="myModalDescuentoP" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Porcentaje de descuento</h4>
      </div>
      <div class="modal-body">
        <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el porcentaje de descuento %">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="calcularDescuento();">Aceptar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal historia del cliente-->
<div id="myModalHistoria" class="modal fade modal-xl" role="dialog">
  <div class="modal-dialog modal-xl" style="width:1250px;height: 400px">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Historia del cliente</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="tab-content" style="background-color:#E7F5FF">
              <div id="home" class="tab-pane fade in active">
                <div class="table-responsive" id="tabla_" style="overflow-y: scroll; height:450px; width: auto;">
                  <!-- <div class="sombra" style> -->
                    <table border class="table table-striped table-hover" id="tbl_style" tabindex="14" >
                      <thead>
                        <tr>
                          <th>TD</th>
                          <th>Fecha</th>
                          <th>Serie</th>
                          <th>Factura</th>
                          <th>Detalle</th>
                          <th>Año</th>
                          <th>Mes</th>
                          <th>Total</th>
                          <th>Abonos</th>
                          <th>Mes No</th>
                          <th>No</th>
                        </tr>
                      </thead>
                      <tbody id="cuerpoHistoria">
                      </tbody>
                    </table>
                  <!-- </div> -->
                </div>
              </div>
            </div>  
        </div>
      </div>
      <div class="modal-footer">
        <div class="col-xs-2 col-md-1 col-sm-1">
          <a type="button" href="#" class="btn btn-default" onclick="historiaClientePDF();">
            <img title="Generar PDF" src="../../img/png/impresora.png">
          </a>                           
        </div>      
        <div class="col-xs-2 col-md-1 col-sm-1">
          <a type="button" href="#" class="btn btn-default" onclick="historiaClienteExcel();">
            <img title="Generar EXCEL" src="../../img/png/table_excel.png">
          </a>                          
        </div>
        <div class="col-xs-2 col-md-1 col-sm-1">
          <a type="button" class="btn btn-default" onclick="enviarHistoriaCliente();">
            <img title="Enviar a correo" src="../../img/png/email.png">
          </a>                          
        </div>
        
        
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
