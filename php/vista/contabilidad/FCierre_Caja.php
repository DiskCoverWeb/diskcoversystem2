<?php
$GrabarEnabled = true;
switch ($_SESSION['INGRESO']['modulo_']) {
  case '01': //CONTABILIDAD
  case '05': //CAJACREDITO
    $GrabarEnabled = false;
    break;
}
?>

<link rel="stylesheet" href="../../dist/css/estilostabla.css">

<style type="text/css">
  .col {
    display: inline-block;
  }

  .padding-all {
    padding: 2px !important;
  }

  #swal2-content {
    font-size: 13px;
    font-weight: 500;
  }

  input:focus,
  select:focus,
  span:focus,
  button:focus,
  #guardar:focus,
  a:focus {
    border: 2px solid #3c8cbb !important;
  }
</style>

<div class="row" style="margin:5px">
  <div class="col-sm-5 col-xs-12">
    <div class="col">
      <a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" title="Salir de modulo" class="btn btn-default">
        <img src="../../img/png/salire.png" width="25" height="30">
      </a>
    </div>
    <div class="col">
      <a href="javascript:void(0)" title="Diario de Caja" class="btn btn-default" onclick="Diario_Caja()">
        <img src="../../img/png/file2.png" width="25" height="30">
      </a>
    </div>
    <?php if ($GrabarEnabled): ?>
      <div class="col">
        <a href="javascript:void(0)" id="Grabar" title="Grabar Diario de Caja" class="btn btn-default"
          onclick="Grabar_Cierre_DiarioV()">
          <img src="../../img/png/grabar.png" width="25" height="30">
        </a>
      </div>
    <?php endif ?>
    <div class="col">
      <a href="javascript:void(0)" id="Reactivar" title="Reactivar" class="btn btn-default"
        onclick="SolicitarReactivar()">
        <img src="../../img/png/folder-check.png" width="25" height="30">
      </a>
    </div>
    <div class="col">
      <a href="javascript:void(0)" id="IESS" title="I.E.S.S" class="btn btn-default" onclick="IESS_Cierre_DiarioV()">
        <img src="../../img/png/iess.png" width="25" height="30">
      </a>
    </div>
    <div class="col">
      <a href="javascript:void(0)" id="Excel" title="Enviar a Excel los resultados" class="btn btn-default"
        onclick="GenerarExcelResultadoCierreCaja()">
        <img src="../../img/png/excel.png" width="25" height="30">
      </a>
    </div>
  </div>
  <div class="col-sm-7 col-xs-12">
    <div class="row">
      <!-- Periodo de Cierre -->
      <div class="form-group col-xs-12 col-md-6 padding-all margin-b-1">
        <div class="col-xs-12">
          <label for="inputEmail3" class="col control-label" style="font-size: 13px;">Periodo de Cierre</label>
        </div>
        <div class="col-xs-6">
          <input tabindex="43" type="date" name="MBFechaI" id="MBFechaI" class="form-control input-xs validateDate"
            onchange="" title="Fecha Inicial" value="<?php echo date("Y-m-d") ?>">
        </div>
        <div class="col-xs-6">
          <input tabindex="44" type="date" name="MBFechaF" id="MBFechaF" class="form-control input-xs validateDate"
            onchange="" title="Fecha Final" value="<?php echo date("Y-m-d") ?>">
        </div>
      </div>

      <!-- Checkboxes and Select -->
      <div class="form-group col-xs-12 col-md-6 padding-all margin-b-1">
        <div class="col-xs-7 padding-all">
          <div class="col-xs-12">
            <label for="CheqCajero" class="col control-label" style="font-size: 13px;">
              <input style="margin-top: 0px; margin-right: 2px;" tabindex="47" type="checkbox" name="CheqCajero"
                id="CheqCajero"> Por Cajero
            </label>
          </div>
          <div class="col-xs-12">
            <select style="display:none" class="form-control input-xs" name="DCBenef" id="DCBenef" tabindex="46"
              onchange=""></select>
          </div>
        </div>
        <div class="col-xs-5 padding-all">
          <label for="CheqOrdDep" class="col control-label" style="font-size: 13px;">
            <input style="margin-top: 0px; margin-right: 2px;" tabindex="48" type="checkbox" name="CheqOrdDep"
              id="CheqOrdDep"> Ordenar Por Depósito
          </label>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- <div id="resultado">
</div>-->

<div class="row">
  <div class="panel panel-primary col-sm-12" style="  margin-bottom: 3px; height:700px">
    <div class="panel-body" style=" padding-top: 5px;padding-bottom: 0px;">
      <div class="col-sm-12">
        <ul class="nav nav-tabs">
          <li class="nav-item active">
            <a class="nav-link" data-toggle="tab" href="#AdoVentasT">1 VENTAS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#AdoCxCT">2 ABONOS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#AdoInv">3 INVENTARIO</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#AdoAsientoT">4 CONTABILIDAD</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#AdoFactAnul">5 ANULADAS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#AdoSRIT">6 REPORTE DE AUDITORIA</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#AdoBanco">7 REPORTE DEL BANCO</a>
          </li>
        </ul>

        <div class="tab-content">
          <!--VENTAS-->
          <div class="tab-pane modal-body active" id="AdoVentasT">
            <div class="row">
              <div class="form-group col-xs-6 padding-all margin-b-1">
                <label for="LabelAbonos" class="col control-label">TOTAL</label>
                <div class="col">
                  <input type="tel" class="form-control input-xs" id="LabelAbonos" name="LabelAbonos">
                </div>
              </div>
              <div class="form-group col-xs-6 padding-all margin-b-1">
                <label for="AdoVentas" class="col control-label">Ventas</label>
                <div class="col">
                  <select style="min-width: 150px;" class="form-control input-xs" name="AdoVentas" id="AdoVentas"
                    onchange="">
                  </select>
                </div>
              </div>
            </div>
            <!--div class="row">
              <div class="col-sm-12" id="DGVentas" style="min-height: 80px;">
                <table class="responsive-table">
                  <thead>
                    <tr>
                      <th class="text-left" style="width:40px">TC</th>
                      <th class="text-left" style="width:80px">Fecha</th>
                      <th class="text-left" style="width:300px">Cliente</th>
                      <th class="text-left" style="width:136px">Serie</th>
                      <th class="text-left" style="width:392px">Autorizacion</th>
                      <th class="text-right" style="width:136px">Factura</th>
                      <th class="text-right" style="width:112px">Total_IVA</th>
                      <th class="text-right" style="width:112px">Descuento</th>
                      <th class="text-right" style="width:112px">Descuento2</th>
                      <th class="text-right" style="width:112px">Servicio</th>
                      <th class="text-right" style="width:112px">Propina</th>
                      <th class="text-right" style="width:112px">Total_MN</th>
                      <th class="text-right" style="width:112px">Saldo_MN</th>
                      <th class="text-left" style="width:144px">Cta_CxP</th>
                      <th class="text-left" style="width:280px">Ciudad</th>
                      <th class="text-left" style="width:240px">Sectorizacion</th>
                      <th class="text-left" style="width:300px">Ejecutivo</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div-->

            <div class="col-sm-12" style="overflow-x: scroll; height:500px">
              <table class="blue-table" style="white-space: nowrap;" id="TblDGVentas">
              </table>
            </div>
          </div>

          <!--ABONOS-->
          <div class="tab-pane modal-body" id="AdoCxCT">
            <div class="row" height:100px>
              <div class="form-group col-xs-6 padding-all margin-b-1">
                <label for="LabelCheque" class="col control-label">TOTAL</label>
                <div class="col">
                  <input type="tel" class="form-control input-xs" id="LabelCheque" name="LabelCheque">
                </div>
              </div>
              <div class="form-group col-xs-6 padding-all margin-b-1">
                <label for="AdoCxC" class="col control-label">CxC</label>
                <div class="col">
                  <select style="min-width: 150px;" class="form-control input-xs" name="AdoCxC" id="AdoCxC" onchange="">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <!--div class="col-sm-12 mb-3" id="DGCxC" style="min-height: 80px;">
                <table class="table-sm" style="width: -webkit-fill-available;">
                  <thead>
                    <tr>
                      <th>TP</th>
                      <th>Fecha</th>
                      <th>COD_BANCO</th>
                      <th>Cliente</th>
                      <th>Serie</th>
                      <th>Autorizacion</th>
                      <th>Factura</th>
                      <th>Banco</th>
                      <th>Cheque</th>
                      <th>Abono</th>
                      <th>Comprobante</th>
                      <th>Orden_No</th>
                      <th>Cta</th>
                      <th>Cta_CxP</th>
                      <th>CodigoC</th>
                      <th>Ciudad</th>
                      <th>Ejecutivo</th>
                    </tr>
                  </thead>
                </table>
              </div-->
              <div class="col-sm-12" style="overflow-x: scroll; height:250px">
                <table class="blue-table" style="white-space: nowrap;" id="TblDGCxC">
                </table>
              </div>
            </div>
            <hr>
            <div class="row">
              <!--div class="col-sm-12" id="DGAnticipos" style="min-height: 80px;">
                <table class="table-sm" style="width: -webkit-fill-available;">
                  <thead>
                    <tr>
                      <th>TP</th>
                      <th>Fecha</th>
                      <th>Cuenta</th>
                      <th>Cliente</th>
                      <th>Numero</th>
                      <th>Creditos</th>
                      <th>Contra_Cta</th>
                      <th>Cta</th>
                    </tr>
                  </thead>
                </table>
              </div-->
              <div class="col-sm-12" style="overflow-x: scroll; height:250px">
                <table class="blue-table" style="white-space: nowrap;" id="TblDGAnticipos">
                </table>
              </div>
            </div>
          </div>

          <!--INVENTARIO-->
          <div class="tab-pane modal-body" id="AdoInv">
            <!--div class="col-md-2" id="DGCierres" style="min-height: 80px;">
              <table class="table-sm tablaHeight" style="width: -webkit-fill-available;">
                <thead>
                  <tr>
                    <th>Fecha</th>
                  </tr>
                </thead>
              </table>
            </div-->
            <div class="col-md-2" style="overflow-x: scroll; height:610px">
              <table class="blue-table" style="white-space: nowrap;" id="TblDGCierres">
              </table>
            </div>

            <div class="col-md-10">
              <!--div id="DGInv" style="min-height: 80px;">
                <table class="table-sm" style="width: -webkit-fill-available;">
                  <thead>
                    <tr>
                      <th>Codigo_Inv</th>
                      <th>Producto</th>
                      <th>Entradas</th>
                    </tr>
                  </thead>
                </table>
              </div-->
              <div class="col-sm-12" style="overflow-x: scroll; height:300px">
                <table class="blue-table" style="white-space: nowrap;" id="TblDGInv">
                </table>
              </div>

              <!--div id="DGProductos" style="min-height: 80px;">
                <table id="" class="table-sm" style="width: -webkit-fill-available;">
                  <thead>
                    <tr>
                      <th>Codigo</th>
                      <th>Producto</th>
                      <th>CANTIDADES</th>
                      <th>SUBTOTALES</th>
                      <th>SUBTOTAL_IVA</th>
                      <th>Cta_Venta</th>
                    </tr>
                  </thead>
                </table>              
              </div-->
              <div class="col-sm-12" style="overflow-x: scroll; height:300px; margin-top:10px">
                <table class="blue-table" style="white-space: nowrap;" id="TblDGProductos">
                </table>
              </div>
            </div>
          </div>

          <!--CONTABILIDAD-->
          <div class="tab-pane modal-body" id="AdoAsientoT">
            <div class="row">
              <!--div class="col-sm-12" id="DGAsiento" style="min-height: 80px;">
                <table class="table-sm" style="width: -webkit-fill-available;">
                  <thead>
                    <tr>
                      <th>CODIGO</th>
                      <th>CUENTA</th>
                      <th>PARCIAL_ME</th>
                      <th>DEBE</th>
                      <th>HABER</th>
                      <th>CHEQ_DEP</th>
                      <th>DETALLE</th>
                    </tr>
                  </thead>                
              </div-->
              <div class="col-sm-12" style="overflow-x: scroll; height:200px">
                <table class="blue-table" style="white-space: nowrap;" id="TblDGAsiento">
                </table>
              </div>
            </div>

            <div class="text-right" style="margin-bottom: 10px; margin-top: 15px; height:100px">
              <label for="LblDiferencia">Diferencia</label>
              <input id="LblDiferencia"></input>
              <label>TOTALES</label>
              <input id="LabelDebe"></input>
              <input id="LabelHaber"></input>
            </div>

            <div class="row">
              <!--div class="col-sm-12" id="DGAsiento1" style="min-height: 80px;">
                <table class="table-sm" style="width: -webkit-fill-available;">
                  <thead>
                    <tr>
                      <th>CODIGO</th>
                      <th>CUENTA</th>
                      <th>PARCIAL_ME</th>
                      <th>DEBE</th>
                      <th>HABER</th>
                      <th>CHEQ_DEP</th>
                      <th>DETALLE</th>
                    </tr>
                  </thead>
                </table>
              </div-->
              <div class="col-sm-12" style="overflow-x: scroll; height:200px">
                <table class="blue-table" style="white-space: nowrap;" id="TblDGAsiento1">
                </table>
              </div>
            </div>

            <div class="text-right" style="margin-top: 15px; height:100px">
              <label for="LblDiferencia">Diferencia</label>
              <input id="LblDiferencia1"></input>
              <label>TOTALES</label>
              <input id="LabelDebe1"></input>
              <input id="LabelHaber1"></input>
            </div>
          </div>

          <!--ANULADAS-->
          <div class="tab-pane modal-body" id="AdoFactAnul">
            <div class="row">
              <!--div class="col-sm-12" id="DGFactAnul" style="min-height: 80px;">
                <table class="table-sm" style="width: -webkit-fill-available;">
                  <thead>
                    <tr>
                      <th>T</th>
                      <th>TC</th>
                      <th>Fecha</th>
                      <th>Cliente</th>
                      <th>Factura</th>
                      <th>Total_IVA</th>
                      <th>Total_MN</th>
                      <th>Cta_CxP</th>
                    </tr>
                  </thead>
                </table>                
              </div-->
              <div class="col-sm-12" style="overflow-x: scroll; height:600px">
                <table class="blue-table" style="white-space: nowrap;" id="TblDGFactAnul">
                </table>
              </div>
            </div>
          </div>

          <!--REPORTE ADITORIA-->
          <div class="tab-pane modal-body" id="AdoSRIT">
            <select style="min-width: 150px; margin-bottom:15px;" class="form-control input-xs" name="AdoSRI"
              id="AdoSRI" onchange=""></select>

            <div class="row">
              <!--div class="col-sm-12" id="DGSRI" style="white-space: nowrap;">
                <table class="table-sm" style="width: -webkit-fill-available;">
                  <thead>
                    <tr>
                      <th>TC</th>
                      <th>T</th>
                      <th>RUC_CI</th>
                      <th>TB</th>
                      <th>Razon_Social</th>
                      <th>Fecha</th>
                      <th>Hora</th>
                      <th>Usuario</th>
                      <th>Autorizacion</th>
                      <th>Serie</th>
                      <th>Secuencial</th>
                      <th>Base_12</th>
                      <th>Base_0</th>
                      <th>Descuento</th>
                      <th>Descuento2</th>
                      <th>TOTAL</th>
                    </tr>
                  </thead>
                </table>
              </div-->
              <div class="col-sm-12" style="overflow-x: scroll; height:500px">
                <table class="blue-table" style="white-space: nowrap;" id="TblDGSRI">
                </table>
              </div>
            </div>

            <div class="row" style="margin-top: 15px;">
              <div class="col-xs-6 col-md-2">
                <label>CON I.V.A.</label><br>
                <input id="LblConIVA" />
              </div>
              <div class="col-xs-6 col-md-2">
                <label>SIN I.V.A.</label><br>
                <input id="LblSinIVA" />
              </div>
              <div class="col-xs-6 col-md-2">
                <label>DESCUENTO</label><br>
                <input id="LblDescuento" />
              </div>
              <div class="col-xs-6 col-md-2">
                <label>TOTAL I.V.A.</label><br>
                <input id="LblIVA" />
              </div>
              <div class="col-xs-6 col-md-2">
                <label>TOTAL SERVICIO</label><br>
                <input id="LblServicio" />
              </div>
              <div class="col-xs-6 col-md-2">
                <label>T O T A L</label><br>
                <input id="LblTotalFacturado" />
              </div>
            </div>
          </div>

          <!--REPORTE BANCO-->
          <div class="tab-pane modal-body" id="AdoBanco">
            <select style="min-width: 150px; margin-bottom:15px;" class="form-control input-xs" name="DCBanco"
              id="DCBanco" onchange=""></select>

            <!-- //TODO LS cuando se llena esta tabla -->
            <div class="row">
              <!--div class="col-sm-12" id="DGBanco" style="min-height: 80px;">                
                <table class="table-sm" style="width: -webkit-fill-available;">
                  <thead>
                    <tr>
                      <th>T</th>
                    </tr>
                  </thead>
                  <tbody id="DGBancoBody"></tbody>
                </table>
              </div-->
              <div class="col-sm-12" style="overflow-x: scroll; height:550px">
                <table class="blue-table" style="white-space: nowrap;" id="TblDGBanco">
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="../../dist/js/FCierre_Caja.js"></script>