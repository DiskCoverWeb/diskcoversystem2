<?php 
date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION);die();
 // print_r($_SESSION['INGRESO']);die();
$TC = 'FA'; if(isset($_GET['tipo'])){$TC = $_GET['tipo'];}
$operadora = $_SESSION['INGRESO']['RUC_Operadora'];
if($operadora!='.' && strlen($operadora)>=13)
{
	$operadora = $_SESSION['INGRESO']['RUC_Operadora'];
}
$servicio = $_SESSION['INGRESO']['Servicio'];
?>
<script type="text/javascript">
	var operadora = '<?php echo $operadora; ?>';
    var servicio = '<?php echo $servicio; ?>';
    var Porc_IVA = '<?php echo $_SESSION['INGRESO']['porc']; ?>';
    var TC = '<?php echo $TC; ?>';
    var servicio = '<?php echo $servicio; ?>';
</script>
<script type="text/javascript">
$(document).ready(function(){

      tbl_lineas_all = $('#tbl_DGAsientoF').DataTable({
          // responsive: true,
        searching: false, // Deshabilita el buscador
        paging: false,    // Deshabilita la paginación
        info: false,      // Oculta la información del total de filas
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
              url:  '../controlador/facturacion/punto_ventaC.php?DGAsientoF=true',
              type: 'POST',  // Cambia el método a POST    
              // data: function(d) {
              //     var parametros = {
              //         ci: $('#ddl_cliente').val(),
              //         desde: $('#txt_desde').val(),
              //         hasta: $('#txt_hasta').val(),
              //         serie: $('#DCLinea').val()
              //     };
              //     return { parametros: parametros };
              // },
              dataSrc: '',             
          },
           scrollX: true,  // Habilitar desplazamiento horizontal
   
          columns: [
             { data: null,
                  render: function(data, type, item) {
                      return `
                                <button type="button" class="btn btn-danger btn-sm" onclick="Eliminar('${item.A_No}','${item.CODIGO}')">
                                    <span class="fa fa-trash"></span>
                                </button>
                               `;       
                  }
            },
            { data: 'CODIGO' },
            { data: 'CANT' },
            { data: 'CANT_BONIF' },
            { data: 'PRODUCTO' },
            { data: 'PRECIO' },
            { data: 'Total_Desc' },
            { data: 'Total_Desc2' },
            { data: 'Total_IVA' },
            { data: 'SERVICIO' },
            { data: 'TOTAL' },
            { data: 'VALOR_TOTAL' },
            { data: 'COSTO'},
            { data: 'Fecha_IN' },
            { data: 'Fecha_OUT' },
            { data: 'Cant_Hab' },
            { data: 'Tipo_Hab' },
            { data: 'Orden_No' },
            { data: 'Mes' },
            { data: 'Cod_Ejec' },
            { data: 'Porc_C' },
            { data: 'REP' },
            { data: 'FECHA' },
            { data: 'CODIGO_L' },
            { data: 'HABIT' },
            { data: 'RUTA' },
            { data: 'TICKET' },
            { data: 'Cta' },
            { data: 'Cta_SubMod' },
            { data: 'Item' },
            { data: 'CodigoU' },
            { data: 'CodBod' },
            { data: 'CodMar' },
            { data: 'TONELAJE' },
            { data: 'CORTE' },
            { data: 'A_No' },
            { data: 'Codigo_Cliente' },
            { data: 'Numero' },
            { data: 'Serie' },
            { data: 'Autorizacion' },
            { data: 'Codigo_B' },
            { data: 'PRECIO2' },
            { data: 'COD_BAR' },
            { data: 'Fecha_V' },
            { data: 'Lote_No' },
            { data: 'Fecha_Fab' },
            { data: 'Fecha_Exp' },
            { data: 'Reg_Sanitario' },
            { data: 'Modelo' },
            { data: 'Procedencia' },
            { data: 'Serie_No' },
            { data: 'Cta_Inv' },
            { data: 'Cta_Costo' },
            { data: 'Estado' },
            { data: 'NoMes' },
            { data: 'Cheking' },
            { data: 'ID' },
          ],
          order: [
              [1, 'asc']
          ]
      });
       
   })

</script>
<script src="../../dist/js/facturacion_elec.js"></script>

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
  <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?></div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0">
          <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Generacion de facturas</li>
        </ol>
      </nav>
    </div>          
</div>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-12">
    <div class="btn-group" role="group" aria-label="Basic example">
        <a href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-outline-secondary">
        	<img src="../../img/png/salire.png">
        </a>	
         <button type="button" class="btn btn-outline-secondary" title="Asignar guia de remision" onclick="btn_guiaRemision()"><img src="../../img/png/ats.png"></button>         

    </div>
  </div>
   <div class="col-lg-6 col-md-6 col-sm-12 text-end">
        <?php if($_SESSION['INGRESO']['Ambiente']==1){echo '<h4>Ambiente Pruebas</h4>';}else if($_SESSION['INGRESO']['Ambiente']==2){echo '<h4>Ambiente Produccion</h4>';} ?>
    </div>
</div>

<input type="hidden" name="CodDoc" id="CodDoc" class="form-control form-control-sm" value="00">
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-3 ">
        <input type="hidden" id="Autorizacion">
        <input type="hidden" id="Cta_CxP">
        <b>Punto de emision</b>
        <select class="form-select form-select-sm" name="DCLinea" id="DCLinea" tabindex="1"
            onchange="numeroFactura(); tipo_documento();">
            <option value=""></option>
        </select>       
    </div>
    <div class="col-lg-5 col-md-5 col-sm-9">
        <b>Nombre del cliente</b>
        <div class="input-group" id="ddl">
            <select class="form-select form-select-sm" id="DCCliente" name="DCCliente" onchange="select()">
                <option value="">Seleccione Bodega</option>
            </select>
                <button type="button" class="btn btn-success btn-sm btn-flat" id="btn_nuevo_cli" onclick="addCliente()"
                    title="Nuevo cliente"><span class="bx bx-user-plus"></span></button>
        

            <!-- <button onclick="tipo_error_sri('0308202203179238540700120010020000006811234567815')" class="btn">error</button>  -->
        </div>
        <input type="hidden" name="codigoCliente" id="codigoCliente" class="form-control input-xs">
        <input type="hidden" name="LblT" id="LblT" class="form-control input-xs">       
    </div>    
    <div class="col-lg-2 col-sm-5">
        <b>CI/RUC/PAS</b>
        <div class="input-group">
            <input type="" name="LblRUC" id="LblRUC" class="form-control form-control-sm" readonly>
            <input type="hidden" name="Lblemail" id="Lblemail" class="form-control input-xs">
            <span class="input-group-btn">
               <label id="LblTD" name="LblTD" class="form-control form-control-sm" style="color :coral;"></label>
            </span>
        </div>       
    </div>
    <div class="col-lg-3 col-sm-5">
        <b id="Label1">FACTURA No.</b>
        <div class="row">
            <div class="col-sm-3" id="LblSerie">
                999999
            </div>
            <div class="col-sm-9">
                <input type="" class="form-control  form-control-sm" id="TextFacturaNo" name="TextFacturaNo" readonly>
            </div>
        </div>
    </div>
    <div class="col-sm-2" style="display:none">
        <b>BODEGAS</b>
        <select class="form-control input-xs" id="DCBodega" name="DCBodega" onblur="validar_bodega()">
            <option value="01">Seleccione Bodega</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-sm-3">
         <b>Fecha</b>
        <input type="date" name="MBFecha" id="MBFecha" class="form-control  form-control-sm" value="<?php echo date('Y-m-d'); ?>" onblur="DCPorcenIva('MBFecha', 'DCPorcenIVA'); validar_cta();">        
    </div>
    <div class="col-lg-1 col-sm-2">
         <b>I.V.A</b>
        <select class="form-select form-select-sm" name="DCPorcenIVA" id="DCPorcenIVA" onblur="cambiar_iva(this.value)"> 
        </select>
    </div>
    <div class="col-lg-7 col-sm-7">
         <b>Tipo de pago</b>
        <select class="form-select form-select-sm" style="width: 100%;" id="DCTipoPago" onchange="$('#DCTipoPago').css('border','1px solid #d2d6de');">
            <option value="">Seleccione tipo de pago</option>
        </select>        
    </div>
</div>
<div class="row">
    <div class="col-lg-9 col-sm-12">
        <div class="row box box-success">
            <div class="col-lg-6 col-sm-8">
                <b>Producto</b>
                <select class="form-select form-select-sm" id="DCArticulo" name="DCArticulo"
                    onchange="Articulo_Seleccionado()">
                    <option value="">Seleccione Producto</option>
                </select>
            </div>
            <div class="col-lg-1 col-sm-2" style="padding-right:0px">
                <b>Stock</b>
                <input type="text" name="LabelStock" id="LabelStock" class="form-control form-control-sm" readonly
                    style="color: red;" value="999999999">
            </div>
            <div class="col-lg-1 col-sm-2" style="padding-right:0px">
                <b>Cantidad</b>
                <input type="text" name="TextCant" id="TextCant" class="form-control form-control-sm" value="1"
                    onblur="valida_Stock()">
            </div>
            <div class="col-lg-1 col-sm-2" style="padding-right:0px">
                <b>P.V.P</b>
                <input type="text" name="TextVUnit" id="TextVUnit" class="form-control form-control-sm" value="0.01"
                    onblur="calcular()">
            </div>           
            <div class="col-lg-1 col-sm-2" style="padding-right:0px">
                <b>Dcto_Val</b>
                <input type="text" name="TextVDescto" id="TextVDescto" class="form-control form-control-sm" value="0">
            </div>  
            <div class="col-lg-1 col-sm-2" style="padding-right:0px;display:none;" id="campo_servicio">
                <b>Servicio</b>
                <input type="text" name="TextServicios" id="TextServicios" class="form-control form-control-sm" value="0">
            </div>            
            <div class="col-lg-1 col-sm-2" style="padding-right:0px">
                <b>TOTAL</b>
                <input type="text" name="LabelVTotal" id="LabelVTotal" class="form-control form-control-sm" value="0">
            </div>
            <div class="col-lg-4 col-sm-4">
                <b>Detalle</b>
                <input type="text" name="TxtDocumentos" id="TxtDocumentos" class="form-control form-control-sm" value="."
                    onblur="ingresar()">
            </div>

        </div>
        <div class="row mt-2">
    <div class="card">
      <div class="card-body">
        <table class="table text-sm h-100" id="tbl_DGAsientoF">
          <thead>
            <th></th>
            <th>CODIGO</th>
            <th>CANT</th>
            <th>CANT_BONIF</th>
            <th>PRODUCTO</th>
            <th>PRECIO</th>
            <th>Total_Desc</th>
            <th>Total_Desc2</th>
            <th>Total_IVA</th>
            <th>SERVICIO</th>
            <th>TOTAL</th>
            <th>VALOR_TOTAL</th>
            <th>COSTO</th>
            <th>Fecha_IN</th>
            <th>Fecha_OUT</th>
            <th>Cant_Hab</th>
            <th>Tipo_Hab</th>
            <th>Orden_No</th>
            <th>Mes</th>
            <th>Cod_Ejec</th>
            <th>Porc_C</th>
            <th>REP</th>
            <th>FECHA</th>
            <th>CODIGO_L</th>
            <th>HABIT</th>
            <th>RUTA</th>
            <th>TICKET</th>
            <th>Cta</th>
            <th>Cta_SubMod</th>
            <th>Item</th>
            <th>CodigoU</th>
            <th>CodBod</th>
            <th>CodMar</th>
            <th>TONELAJE</th>
            <th>CORTE</th>
            <th>A_No</th>
            <th>Codigo_Cliente</th>
            <th>Numero</th>
            <th>Serie</th>
            <th>Autorizacion</th>
            <th>Codigo_B</th>
            <th>PRECIO2</th>
            <th>COD_BAR</th>
            <th>Fecha_V</th>
            <th>Lote_No</th>
            <th>Fecha_Fab</th>
            <th>Fecha_Exp</th>
            <th>Reg_Sanitario</th>
            <th>Modelo</th>
            <th>Procedencia</th>
            <th>Serie_No</th>
            <th>Cta_Inv</th>
            <th>Cta_Costo</th>
            <th>Estado</th>
            <th>NoMes</th>
            <th>Cheking</th>
            <th>ID</th>
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
<!-- 
        <div class="row text-center">
            <div class="col-sm-12" id="tbl_DGAsientoF">

            </div>

        </div> -->
        <div class="row">
            <div class="col-sm-6">
                <b>NOTA</b>
                <input type="text" name="TxtNota" id="TxtNota" class="form-control form-control-sm">
            </div>
            <div class="col-sm-6">
                <label><input type="checkbox" name="rbl_obs" id="rbl_obs" onclick="mostara_observacion()">
                    OBSERVACION</label>
                <input type="text" name="TxtObservacion" id="TxtObservacion" class="form-control form-control-sm">
            </div>
            <div class="col-sm-2" style="display:none">
                <b>COTIZACION</b>
                <input type="text" name="TextCotiza" id="TextCotiza" class="form-control form-control-sm">
            </div>
            <div class="col-sm-1" style="display:none">
                <b>CONVERSION</b>
                <div class="row">
                    <div class="col-sm-6" style="padding-right:0px">
                        <label><input type="radio" name="radio_conve" id="OpcMult" value="OpcMult" checked> (X)</label>
                    </div>
                    <div class="col-sm-6" style="padding-right:0px">
                        <label><input type="radio" name="radio_conve" id="OpcDiv" value="OpcDiv"> (Y)</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-1" style="display:none">
                <b>Gavetas</b>
                <input type="text" name="TxtGavetas" id="TxtGavetas" class="form-control form-control-sm" value="0">
            </div>
        </div>

    </div>
    <div class="col-lg-3 col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <b>Total Tarifa 0%</b>
            </div>
            <div class="col-sm-6">
                <input type="text" name="LabelSubTotal" id="LabelSubTotal" class="form-control form-control-sm text-right"
                    value="0.00" style="color:red" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <b id="LabelTotTarifa">Total Tarifa</b>
            </div>
            <div class="col-sm-6">
                <input type="text" name="LabelConIVA" id="LabelConIVA" class="form-control form-control-sm text-right"
                    value="0.00" style="color:red" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <b>Total Descuento</b>
            </div>
            <div class="col-sm-6">
                <input type="text" name="LabelDescto" id="LabelDescto" class="form-control form-control-sm text-right"
                    value="0.00" style="color:red" readonly>
            </div>
        </div>
        <div class="row" id="campo_totalServicio" style="display:none;">
            <div class="col-sm-6">
                <b id="textoServicio" style="letter-spacing: -0.5px;"></b>
            </div>
            <div class="col-sm-6">
                <input type="text" name="LabelServicio" id="LabelServicio" class="form-control form-control-sm text-right"
                    value="0.00" style="color:red" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <b id="Label3">I.V.A. </b>
            </div>
            <div class="col-sm-6">
                <input type="text" name="LabelIVA" id="LabelIVA" class="form-control form-control-sm text-right" value="0.00"
                    style="color:red" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <b>Total Factura</b>
            </div>
            <div class="col-sm-6">
                <input type="text" name="LabelTotal" id="LabelTotal" class="form-control form-control-sm text-right"
                    value="0.00" style="color:red" readonly>
            </div>
        </div>
        <div class="row" style="display:none;">
            <div class="col-sm-6">
                <b>Total Fact (ME)</b>
            </div>
            <div class="col-sm-6">
                <input type="text" name="LabelTotalME" id="LabelTotalME" class="form-control form-control-sm text-right"
                    value="0.00" style="color:red" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <b>EFECTIVO</b>
            </div>
            <div class="col-sm-6">
                <input type="text" name="TxtEfectivo" id="TxtEfectivo" class="form-control form-control-sm text-right"
                    value="0.00" onblur="calcular_pago()" onkeyup="calcular_pago()">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <b>CUENTA DEL BANCO</b>
                <select class="form-control input-xs select2" id="DCBanco" name="DCBanco">
                    <option value="">Seleccione Banco</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <b>Documento</b>
            </div>
            <div class="col-sm-9">
                <input type="text" name="TextCheqNo" id="TextCheqNo" class="form-control form-control-sm">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <b>NOMBRE DEL BANCO</b>
                <input type="text" name="TextBanco" id="TextBanco" class="form-control form-control-sm">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <b>VALOR BANCO</b>
            </div>
            <div class="col-sm-6">
                <input type="text" name="TextCheque" id="TextCheque" class="form-control form-control-sm text-right"
                    value="0.00" onblur="calcular_pago()">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <b>CAMBIO</b>
            </div>
            <div class="col-sm-6">
                <input type="text" name="LblCambio" id="LblCambio" class="form-control form-control-sm text-right"
                    style="color: red;" value="0.00">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12"><br>
                <button class="btn btn-default btn-block" id="btn_g"> <img src="../../img/png/grabar.png"
                        onclick="generar()"><br> Guardar</button>
            </div>
        </div>

    </div>
</div>
<div class="row">
    <div id="re_frame" style="display:none;"></div>
</div>


<div id="myModal_boletos" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Ingrese el rango de boletos</h4>
            </div>
            <div class="modal-body">
                <b>Desde:</b>
                <input type="text" name="TxtRifaD" id="TxtRifaD" class="form-control form-control-sm" value="0">
                <b>Hasta:</b>
                <input type="text" name="TxtRifaH" id="TxtRifaH" class="form-control form-control-sm" value="0">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="cambiar_nombre" role="dialog" data-keyboard="false" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-dialog-centered modal-sm detalles_proh" style=" margin-left: calc(270px + -10vh);
          margin-top: calc(100px + 10vh);">
        <div class="modal-content">
            <div class="modal-body text-center">
                <textarea class="form-control" style="resize: none;" rows="4" id="TxtDetalle" name="TxtDetalle"
                    onblur="cerrar_modal_cambio_nombre()"></textarea>
                <button style="border:0px"></button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_obs" role="dialog" data-keyboard="false" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Observaciones</h4>
            </div>
            <div class="modal-body">
                <b>Tonelaje:</b>
                <input type="text" name="TxtTonelaje" id="TxtTonelaje" class="form-control form-control-sm" value="0">
                <b>Año:</b>
                <input type="text" name="TxtAnio" id="TxtAnio" class="form-control form-control-sm" value="0">
                <b>Placas:</b>
                <input type="text" name="TxtPlacas" id="TxtPlacas" class="form-control form-control-sm" value="0">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="add_observaciones()">Agregar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function add_observaciones() {
    var to = $('#TxtTonelaje').val();
    var an = $('#TxtAnio').val();
    var pl = $('#TxtPlacas').val();
    $('#modal_obs').modal('hide');
    $('#TxtObservacion').val('Tonelaje=' + to + ', Año=' + an + ', Placa=' + pl);
}
</script>