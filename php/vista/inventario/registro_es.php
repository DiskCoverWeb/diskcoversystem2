<script src="../../dist/js/kardex_ing.js"></script>

<style type="text/css">
    
</style>
<script type="text/javascript" src="../../dist/js/registro_es.js">

    
</script>

<style>
    
</style>
<!-- <div id="contenedor-envcorreo" class="contenedor-envcorreo">
    <div class="bg-envcorreo" id="bg-envcorreo">
        <img id="load-gif" src="../../img/gif/correo_fin.gif" alt="Enviando correo">
    </div>
    <div class="text-envcorreo" id="text-envcorreo">Estimado usuario, su correo está siendo procesado para ser
        envíado...
    </div>
</div> -->
<div class="container-lg">
    <div class="row">
        <div class="col-sm-6">
            <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                                print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
                        <img src="../../img/png/salire.png">
                    </a>
                    <button type="button" class="btn btn-outline-secondary" id="imprimir_excel" title="Descargar Excel">
                        <img src="../../img/png/table_excel.png">
                    </button>
                    <button type="button" class="btn btn-outline-secondary" title="Guardar" onclick="validar_grabacion()">
                        <img src="../../img/png/grabar.png">
                    </button>
                    	
                </div>
        </div>
    </div>
    <div class="">

        <div class="row text-center">
            <div class="col-sm-12">
                <div class="accordion" id="accordion">
                    <div class="accordion-item">
                        <h4 class="accordion-header text-center" id="headingOne">
                            <a class="accordion-button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                CONTROL DE INVENTARIO PARA INGRESOS/EGRESOS
                            </a>
                        </h4>
                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <input type="hidden" name="si_no" id="si_no">


                                <input type="hidden" name="" id="Codigo">
                                <input type="hidden" name="" id="Cuenta">
                                <input type="hidden" name="" id="SubCta">
                                <input type="hidden" name="" id="Moneda_US">
                                <input type="hidden" name="" id="TipoCta">
                                <input type="hidden" name="" id="TipoPago">


                                <input type="hidden" name="grupo_no" id="grupo_no">
                                <input type="hidden" name="Tipodoc" id="Tipodoc">
                                <input type="hidden" name="TipoBenef" id="TipoBenef">
                                <input type="hidden" name="cod_benef" id="cod_benef">
                                <input type="hidden" name="InvImp" id="InvImp">
                                <input type="hidden" name="ci" id="ci">


                                <div class="row"><br>
                                    <div class="col-sm-1 text-end">
                                        <label for="CLTP"><b>TD:</b></label>
                                    </div>
                                    <div class="col-sm-1 pe-0">
                                        <select class="form-select form-select-sm" id="CLTP" name="CLTP">
                                            <!--<option value="">Seleccione TP</option>-->
                                            <option value="CD">CD</option>
                                            <option value="NC">NC</option>
                                            <option value="ND">ND</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rbl_tipo" id="OpcI" onclick="tipo_ingreso()">
                                            <label class="form-check-label" for="OpcI"> Ingreso</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rbl_tipo" id="OpcE" onclick="tipo_ingreso()">
                                            <label class="form-check-label" for="OpcE"> Egreso</label>
                                        </div>
                                        <!-- <label class="radio-inline"><b><input type="radio" name="rbl_tipo" checked=""
                                                    id="OpcI" onclick="tipo_ingreso()"> Ingreso</b></label>
                                        <label class="radio-inline"><b><input type="radio" name="rbl_tipo" id="OpcE"
                                                    onclick="tipo_ingreso()">
                                                Egreso</b></label> -->
                                    </div>
                                    <div class="col-sm-2">
                                        <!-- <label class="radio-inline"><b><input class="" type="checkbox" name="CheqContraCta"
                                                    checked="" id="CheqContraCta" onchange="CheqContraCuenta_Clic();">
                                                CONTRA CUENTA</b></label> -->

                                            <input class="form-check-input" type="checkbox" name="CheqContraCta" id="CheqContraCta" checked="" onchange="CheqContraCuenta_Clic();">
                                            <label class="form-check-label" for="CheqContraCta">
                                                CONTRA CUENTA
                                            </label>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="form-control form-control-sm" id="DCCtaObra" onchange="leercuenta();"
                                            placeholder="Contra Cuenta">
                                            <!--<option>Contra Cuenta</option>-->
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-1 text-end">
                                        <label for="MBFechaI"><b>Fecha:</b></label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input class="form-control form-control-sm" type="date"
                                            name="MBFechaI" value="<?php echo date('Y-m-d') ?>" id="MBFechaI"
                                            onblur="DCPorcenIva('MBFechaI', 'DCPorcIVA');">
                                    </div>
                                    <div class="col-sm-4 offset-sm-3">
                                        <select class="form-select form-select-sm" id="DCBenef" name="DCBenef"
                                            placeholder="Clientes">
                                            <!--<option>Clientes</option>-->
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-select form-select-sm" name="DCDiario" id="DCDiario"
                                            placeholder="Diario"></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-1 text-end">
                                        <label for="MBVence"><small><b>Vencimiento:</b></small></label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input class="form-control form-control-sm" type="date"
                                            name="MBVence" id="MBVence" value="<?php echo date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-sm-2 text-end">
                                        <label for="TextConcepto"><b>POR CONCEPTO DE:</b></label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="TextConcepto" class="form-control form-control-sm"
                                            id="TextConcepto" value=".">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label class="radio-inline">
                                            <b>
                                                <input class="form-check-input" type="checkbox" name="CheqRF" id="CheqRF" onclick="Ult_fact_Prove($('#DCProveedor').val());modal_retencion();">
                                                <label class="form-check-label" for="CheqRF">
                                                    Retencion en la
                                                    fuente:
                                                </label>
                                                <!-- <input type="checkbox" name="CheqRF"
                                                    onclick="Ult_fact_Prove($('#DCProveedor').val());modal_retencion();"
                                                    id="CheqRF">
                                                Retencion en la
                                                fuente: -->
                                            </b>
                                        </label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="LblRF" class="form-control-sm form-control" id="LblRF"
                                            value="0.00">
                                    </div>
                                    <div class="col-sm-2 alineacion text-right">
                                        <label for="LblRIVA"><b>Retencion del I.V.A:</b></label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="LblRIVA" id="LblRIVA" class="form-control-sm form-control"
                                            value="0.00">
                                    </div>
                                    <div class="col-sm-1 alineacion text-right">
                                        <label for="TxtFactNo"><b>N° Factura:</b></label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="TxtFactNo" id="TxtFactNo" class="form-control-sm form-control"
                                            value="0">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <select class="form-select form-select-sm" id="ddl_familia" name="ddl_familia"
                                            onchange="producto_famili($('#ddl_familia').val())">
                                            <option value="">Seleccione un Familiar</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control text-center form-control-sm"
                                            name="labelProductro" id="labelProductro" value="PRODUCTO" readonly="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <select class="form-select form-select-sm" id="ddl_producto" name="ddl_producto"
                                            placeholder="Seleccione Producto" onchange="detalle_articulo()">
                                        </select>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <!-- <label class="radio-inline"><b><input type="radio" name="rbl_"
                                                                    id="OpcIVA" onchange="habilitar_iva();"> Con
                                                                Iva</b>
                                                        </label> -->
                                                        <input class="form-check-input" type="radio" name="rbl_" id="OpcIVA" onchange="habilitar_iva();">
                                                        <label class="form-check-label" for="OpcIVA">
                                                            Con Iva
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input class="form-check-input" type="radio" name="rbl_" id="OpcX" checked onchange="habilitar_iva();">
                                                        <label class="form-check-label" for="OpcX">
                                                            Sin Iva
                                                        </label>
                                                        <!-- <label class="radio-inline"><b><input type="radio" name="rbl_"
                                                                    id="OpcX" checked onchange="habilitar_iva();"> Sin
                                                                Iva</b>
                                                        </label> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <label for="DCPorcIVA"><b>I.V.A</b></label>
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="DCMarca"><b>MARCA</b></label>
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="LabelCodigo"><b>CODIGO</b></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <!--Bodega-->
                                                <select class="form-select form-select-sm" id="DCBodega">
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <!--IVA-->
                                                <select class="form-select form-select-sm" id="DCPorcIVA" name="DCPorcIVA">
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <!--Marca-->
                                                <select class="form-select form-select-sm" id="DCMarca">
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <!--Codigo-->
                                                <input type="text" class="form-control form-control-sm" id="LabelCodigo"
                                                    name="LabelCodigo" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label for="LabelUnidad"><b>UNIDAD</b></label>
                                        <input type="text" name="LabelUnidad" class="form-control form-control-sm"
                                            id="LabelUnidad" readonly>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TextOrden" id=""><b>GUIA N°</b></label>
                                        <input type="text" name="TextOrden" id="TextOrden" class="form-control form-control-sm"
                                            value="0">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TextEntrada"><b>CANTIDAD</b></label>
                                        <input type="text" name="TextEntrada" id="TextEntrada"
                                            class="form-control form-control-sm" value="0">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TextVUnit"><b>VALOR UNIT.</b></label>
                                        <input type="text" name="TextVUnit" id="TextVUnit" class="form-control form-control-sm"
                                            value="0.00" onblur="TextVUnit_LostFocus();">
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="TxtCodBar"><b>CODIGO DE BARRA</b></label>
                                        <input type="text" name="TxtCodBar" id="TxtCodBar"
                                            class="form-control form-control-sm" value=".">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label for="TxtLoteNo"><b>LOTE N°</b></label>
                                        <input type="text" name="TxtLoteNo" id="TxtLoteNo" class="form-control form-control-sm"
                                            value="0">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="MBFechaFab"><b>FECHA FAB</b></label>
                                        <input type="date" name="MBFechaFab" id="MBFechaFab"
                                            class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="MBFechaExp"><b>FECHA EXP</b></label>
                                        <input type="date" name="MBFechaExp" id="MBFechaExp"
                                            class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TxtRegSanitario"><b>REG. SANITARIO</b></label>
                                        <input type="text" name="TxtRegSanitario" id="TxtRegSanitario"
                                            class="form-control form-control-sm" readonly>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="TxtModelo"><b>MODELO</b></label>
                                        <input type="text" name="TxtModelo" id="TxtModelo" class="form-control form-control-sm"
                                            onblur="toupper(this);" value=".">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="TxtProcedencia"><b>PROCEDENCIA/UBICACION</b></label>
                                        <input type="text" name="TxtProcedencia" id="TxtProcedencia"
                                            class="form-control form-control-sm" onblur="toupper(this);" value=".">
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="TxtSerieNo"><b>SERIE No.</b></label>
                                        <input type="text" name="TxtSerieNo" id="TxtSerieNo"
                                            class="form-control form-control-sm" value=".">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TextDesc"><b>DESC. 1</b></label>
                                        <input type="text" name="TextDesc" id="TextDesc" class="form-control form-control-sm"
                                            value="0.00">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TextDesc1"><b>DESC. 2</b></label>
                                        <input type="text" name="TextDesc1" id="TextDesc1" class="form-control form-control-sm"
                                            value="0.00">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TextTotal"><b>VALOR TOTAL</b></label>
                                        <input type="text" name="TextTotal" id="TextTotal" class="form-control form-control-sm"
                                            value="0.00" onblur="TexTotal_LostFocus();">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
        <div class="tbl-container" style="margin:1vw;">
            <div class="row">
                <div class="table-responsive" style="height: 400px">
                    <table class="table table-sm table-hover" id="tbl_registro">
                        <thead>
                            <th>TP</th>
                            <th>CODIGO_INV</th>
                            <th>DH</th>
                            <th>PRODUCTO</th>
                            <th>CANT_ES</th>
                            <th>VALOR_UNI</th>
                            <th>VALOR_TOTAL</th>
                            <th>CANTIDAD</th>
                            <th>SALDO</th>
                            <th>P_DESC</th>
                            <th>P_DESC1</th>
                            <th>IVA</th>
                            <th>CTA_INVENTARIO</th>
                            <th>CONTRA_CTA</th>
                            <th>UNIDAD</th>
                            <th>CodBod</th>
                            <th>CodMar</th>
                            <th>COD_BAR</th>
                            <th>T_No</th>
                            <th>Item</th>
                            <th>CodigoU</th>
                            <th>SUBCTA</th>
                            <th>Cod_Tarifa</th>
                            <th>Fecha_DUI</th>
                            <th>No_Refrendo</th>
                            <th>DUI</th>
                            <th>A_No</th>
                            <th>ValorEM</th>
                            <th>Especifico</th>
                            <th>Consumo</th>
                            <th>Antidumping</th>
                            <th>Modernizacion</th>
                            <th>Control</th>
                            <th>Almacenaje</th>
                            <th>FODIN</th>
                            <th>Salvaguardas</th>
                            <th>Interes</th>
                            <th>CODIGO_INV1</th>
                            <th>CodBod1</th>
                            <th>Codigo_B</th>
                            <th>Codigo_Dr</th>
                            <th>ORDEN</th>
                            <th>VALOR_FOB</th>
                            <th>COMIS</th>
                            <th>TRANS_UNI</th>
                            <th>TRANS_TOTAL</th>
                            <th>PRECION_CIF</th>
                            <th>UTIL</th>
                            <th>PVP</th>
                            <th>CTA_COSTO</th>
                            <th>CTA_VENTA</th>
                            <th>TOTAL_PVP</th>
                            <th>Codigo_Tra</th>
                            <th>Lote_N°</th>
                            <th>Fecha_Fab</th>
                            <th>Fecha_Exp</th>
                            <th>Reg_Sanitario</th>
                            <th>Modelo</th>
                            <th>Procedencia</th>
                            <th>Serie_N°</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row"><br><br>
            <div class="col-sm-2">
                <input type="text" name="Label3" id="Label3" class="form-control form-control-sm">
            </div>
            <div class="col-sm-2">
                <button class="btn btn-light" data-toggle="modal" data-target="#myModal_comprobante">Seleccionar <br>
                    comprobante</button>
            </div>
            <div class="col-sm-2">
                <label for="TxtDifxDec"><b>DIFxDECIMALES</b></label>
                <input type="text" name="TxtDifxDec" id="TxtDifxDec" class="form-control-sm form-control" value="0">
            </div>
            <div class="col-sm-2">
                <label for="TxtSubTotal"><b>SUBTOTAL</b></label>
                <input type="text" name="TxtSubTotal" id="TxtSubTotal" class="form-control-sm form-control" value="0">
            </div>
            <div class="col-sm-2">
                <label for="TextIVA" id="Label11"><b>I.V.A</b></label>
                <input type="text" name="TextIVA" id="TextIVA" class="form-control-sm form-control" value="0">
            </div>
            <div class="col-sm-2">
                <label for="Label1"><b>TOTAL</b></label>
                <input type="text" name="Label1" id="Label1" class="form-control-sm form-control">
            </div>
        </div>
    </div>
</div>
<br><br>

<div id="myModal" class="modal fade" role="dialog" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title">Compras</h4>
            </div>
            <div class="modal-body">
                <div class="row align-items-start">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-10">
                                <div class="card">
                                    
                                    <div class="card-body">
                                        <h3 class="card-title">Retencion de IVA por</h3>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="ChRetB" id="ChRetB" onclick="habilitar_bienes()">
                                                    <label class="form-check-label" for="ChRetB">
                                                        Bienes
                                                    </label>
                                                </div>
                                                <!-- <label class="radio-inline" onclick="habilitar_bienes()"><input
                                                        type="checkbox" name="ChRetB" id="ChRetB"> Bienes</label> -->
                                            </div>
                                            <div class="col-sm-9">
                                                <select class="form-select form-select-sm" id="DCRetIBienes">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="ChRetS" id="ChRetS" onclick="habilitar_servicios()">
                                                    <label class="form-check-label" for="ChRetS">
                                                        Servicios
                                                    </label>
                                                </div>

                                                <!-- <label class="radio-inline" onclick="habilitar_servicios()"><input
                                                        type="checkbox" name="ChRetS" id="ChRetS">Servicios</label> -->
                                            </div>
                                            <div class="col-sm-9">
                                                <select class="form-select form-select-sm" id="DCRetISer"
                                                    onblur="alert('s');">
                                                    <option>Seleccione Tipo Retencion</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 text-center">
                                <button class="btn btn-outline-secondary"> <img src="../../img/png/grabar.png"
                                        onclick="validar_formulario()"><br>
                                    Guardar</button>
                                <button class="btn btn-outline-secondary" data-bs-dismiss="modal" onclick="limpiar_retencaion()">
                                    <img src="../../img/png/bloqueo.png"><br> Cancelar</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- <div class="col-sm-8">
                                            <b>PROVEEDOR</b>
                                            <select class="form-select form-select-sm" id="DCProveedor">
                                                <option value="">No seleccionado</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1"><br>
                                            <input type="text" class="form-control form-control-sm text-danger" name="" id="LblTD"
                                                readonly="">
                                        </div>
                                        <div class="col-sm-3"><br>
                                            <input type="text" class="form-control form-control-sm" name="" id="LblNumIdent" readonly="">
                                        </div> -->
                                        <b>PROVEEDOR</b>
                                        <div class="input-group input-group-sm">
                                            <select class="form-select form-select-sm" id="DCProveedor">
                                                <option value="">No seleccionado</option>
                                            </select>
                                            <input type="text" class="form-control form-control-sm text-danger" name="" id="LblTD"
                                                readonly="">
                                            <input type="text" class="form-control form-control-sm" name="" id="LblNumIdent" readonly="">
                                            <!-- <div class="col-sm-8">
                                            </div>
                                            <div class="col-sm-1"><br>
                                            </div>
                                            <div class="col-sm-3"><br>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Comprobante de compra</button>
                                    <button class="nav-link" id="menu1-tab" data-bs-toggle="tab" data-bs-target="#menu1" type="button" role="tab" aria-controls="menu1" aria-selected="false">Conceptos AIR</button>
                                    <button class="nav-link" id="menu2-tab" data-bs-toggle="tab" data-bs-target="#menu2" type="button" role="tab" aria-controls="menu2" aria-selected="false">Partidos politicos</button>
                                    <!-- <li class="nav-item active">
                                        <a class="nav-link" data-bs-toggle="tab" href="#home">Comprobante de compra</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#menu1">Conceptos AIR</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#menu2">Partidos politicos</a>
                                    </li> -->
                                </div>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <b>Devolucion del IVA:</b>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="cbx_iva" id="iva_si" value="S" checked="">
                                                            <label class="form-check-label" for="iva_si"> SI</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="cbx_iva" id="iva_no" value="N">
                                                            <label class="form-check-label" for="iva_no"> NO</label>
                                                        </div>
    
                                                        <!-- <label class="radio-inline"><input type="radio" name="cbx_iva"
                                                                id="iva_si" value="S" checked="">
                                                            SI</label>
                                                        <label class="radio-inline"><input type="radio" name="cbx_iva"
                                                                id="iva_no" value="N"> NO</label> -->
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <b>Tipo de sustento Tributario</b>
                                                        <select class="form-select form-select-sm" id="DCSustento"
                                                            onchange="ddl_DCTipoComprobante();ddl_DCDctoModif();">
                                                            <option value="">seleccione sustento </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <br>
                                                <button type="button" id="btn_air" class="btn btn-light text-center"
                                                    onclick="cambiar_air()"><i class="fa fa-arrow-right"></i><br>AIR</button>
                                            </div>
                                        </div>
        
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <!-- <div class="box-header" style="padding:0px">
                                                        <h3 class="box-title"><b>INGRESE LOS DATOS DE LA FACTURA, NOTA DE VENTA,
                                                            ETC_________________FORMULARIO 104</b></h3>
                                                    </div> -->
                                                    <div class="card-body">
                                                        <h3 class="card-title">INGRESE LOS DATOS DE LA FACTURA, NOTA DE VENTA,
                                                            ETC_________________FORMULARIO 104</h3>
                                                        <div class="row">
                                                            <div class="col-sm-5">
                                                                <b>tipo de comprobate</b>
                                                                <select class="form-select-sm form-select-sm" id="DCTipoComprobante"
                                                                    onchange="mostrar_panel()">
                                                                    <option value="">Seleccione tipo de comprobante</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <b>Serie</b>
                                                                <div class="row">
                                                                    <div class="input-group input-group-sm">
                                                                        <input type="text" name="" class="form-control form-control-sm"
                                                                            id="TxtNumSerieUno" placeholder="001"
                                                                            onblur="autocompletar_serie_num(this.id)"
                                                                            onkeyup=" solo_3_numeros(this.id)">
                                                                        <input type="text" name="" class="form-control form-control-sm"
                                                                            id="TxtNumSerieDos" placeholder="001"
                                                                            onblur="autocompletar_serie_num(this.id)"
                                                                            onkeyup=" solo_3_numeros(this.id)">
                                                                    </div>
                                                                    <!-- <div class="col-sm-6 p-0">
                                                                        <input type="text" name="" class="form-control form-control-sm"
                                                                            id="TxtNumSerieUno" placeholder="001"
                                                                            onblur="autocompletar_serie_num(this.id)"
                                                                            onkeyup=" solo_3_numeros(this.id)">
                                                                    </div>
                                                                    <div class="col-sm-6 p-0">
                                                                        <input type="text" name="" class="form-control form-control-sm"
                                                                            id="TxtNumSerieDos" placeholder="001"
                                                                            onblur="autocompletar_serie_num(this.id)"
                                                                            onkeyup=" solo_3_numeros(this.id)">
                                                                    </div> -->
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <b>Numero</b>
                                                                <input type="text" name="" class="form-control form-control-sm"
                                                                    id="TxtNumSerietres" onblur="validar_num_factura(this.id)"
                                                                    placeholder="000000001" onkeyup="solo_9_numeros(this.id)">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <b>Autorizacion</b>
                                                                <input type="text" name="" class="form-control form-control-sm"
                                                                    id="TxtNumAutor" onblur="autorizacion_factura()"
                                                                    placeholder="0000000001" onkeyup="solo_10_numeros(this.id)">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="row">
                                                                    <div class="col-sm-2 p-0">
                                                                        <b>Emision</b>
                                                                        <input type="date" name="" class="form-control form-control-sm"
                                                                            value="<?php echo date('Y-m-d') ?>" id="MBFechaEmi">
                                                                    </div>
                                                                    <div class="col-sm-2 p-0">
                                                                        <b>Registro</b>
                                                                        <input type="date" name="" class="form-control form-control-sm"
                                                                            value="<?php echo date('Y-m-d') ?>" id="MBFechaRegis">
                                                                    </div>
                                                                    <div class="col-sm-2 p-0">
                                                                        <b>Caducidad</b>
                                                                        <input type="date" name="" class="form-control form-control-sm"
                                                                            value="<?php echo date('Y-m-d') ?>" id="MBFechaCad">
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <b>No Obj. IVA</b>
                                                                        <input type="text" name="" class="form-control form-control-sm"
                                                                            value="0.00" id="TxtBaseImpoNoObjIVA">
                                                                    </div>
                                                                    <div class="col-sm-1 p-1">
                                                                        <b>Tarifa 0</b>
                                                                        <input type="text" name="" class="form-control form-control-sm"
                                                                            value="0.00" id="TxtBaseImpo">
                                                                    </div>
                                                                    <div class="col-sm-1 p-1">
                                                                        <b>Tarifa 12</b>
                                                                        <input type="text" name="" class="form-control form-control-sm"
                                                                            value="0.00" id="TxtBaseImpoGrav">
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <b>Valor ICE</b>
                                                                        <input type="text" name="" class="form-control form-control-sm"
                                                                            value="0.00" id="TxtBaseImpoIce">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="card">
                                                    <!-- <div class="box-header" style="padding:0px">
                                                        <h3 class="box-title">Porcentajes de las bases Imponibles</h3>
                                                    </div> -->
                                                    <div class="card-body">
                                                        <h3 class="card-title">Porcentajes de las bases Imponibles</h3>
                                                        <div class="row">
                                                            <div class="col-sm-2">
                                                                IVA
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select class="form-select form-select-sm" id="DCPorcenIva"
                                                                    onchange="calcular_iva()">
                                                                    <option value="I">Iva</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                Valor I.V.A
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <input type="text" name="" class="form-control form-control-sm"
                                                                    id="TxtMontoIva" value="0">
                                                            </div>
                                                        </div>
                                                        <div class="row"><br>
                                                            <div class="col-sm-2">
                                                                ICE
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select class="form-select form-select-sm" id="DCPorcenIce"
                                                                    onchange="calcular_ice()">
                                                                    <option value="I">ICE</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                Valor ICE
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <input type="text" name="" class="form-control form-control-sm"
                                                                    id="TxtMontoIce" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="box-body">
                                                    </div> -->
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="card">
                                                    <!-- <div class="box-header" style="padding:0px">
                                                        <h3 class="box-title">Retencion del IVA por Bienes Y/O Servicios </h3>
                                                    </div> -->
                                                    <div class="card-body">
                                                        <h3 class="card-title">Retencion del IVA por Bienes Y/O Servicios </h3>
                                                        <div class="row">
                                                            <div class="col-sm-4"><br>
                                                                Monto
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <b>BIENES</b>
                                                                <input type="text" name="" class="form-control form-control-sm"
                                                                    id="TxtIvaBienMonIva" readonly="">
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <b>SERVICIOS</b>
                                                                <input type="text" name="" class="form-control form-control-sm"
                                                                    id="TxtIvaSerMonIva" readonly="">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                Porcentaje
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <select class="form-select form-select-sm" id="DCPorcenRetenIvaBien"
                                                                    disabled="" onchange="calcular_retencion_porc_bienes()">
                                                                    <option value="0">0</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <select class="form-select form-select-sm" id="DCPorcenRetenIvaServ"
                                                                    disabled="" onchange="calcular_retencion_porc_serv()">
                                                                    <option value="0">0</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                Valor RET
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <input type="text" name="" class="form-control form-control-sm"
                                                                    id="TxtIvaBienValRet" value="0" readonly="">
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <input type="text" name="" class="form-control form-control-sm"
                                                                    id="TxtIvaSerValRet" value="0" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="box-body">
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="row" id="panel_notas" style="display: none">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <!-- <div class="box-header" style="padding:0px">
                                                        <h3 class="box-title"><b>NOTAS DE DEBITO / NOTAS DE CREDITO</b></h3>
                                                    </div> -->
                                                    <div class="card-body">
                                                        <h3 class="card-title">NOTAS DE DEBITO / NOTAS DE CREDITO</h3>
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <b>tipo de comprobate</b>
                                                                <select class="form-select form-select-sm" id="DCDctoModif">
                                                                    <option>Seleccione tipo de comprobante</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <b>Serie</b>
                                                                <div class="row">
                                                                    <div class="input-group input-group-sm">
                                                                        <input type="text" name="" class="form-control form-control-sm"
                                                                            id="TxtNumSerieUnoComp" placeholder="001"
                                                                            onblur="autocompletar_serie_num(this.id)"
                                                                            onkeyup="solo_3_numeros(this.id)">

                                                                        <input type="text" name="" class="form-control form-control-sm"
                                                                            id="TxtNumSerieDosComp" placeholder="001"
                                                                            onblur="autocompletar_serie_num(this.id)"
                                                                            onkeyup="solo_3_numeros(this.id)">
                                                                    </div>
                                                                    <!-- <div class="col-sm-6" style="padding: 0px">
                                                                        <input type="text" name="" class="form-control form-control-sm"
                                                                            id="TxtNumSerieUnoComp" placeholder="001"
                                                                            onblur="autocompletar_serie_num(this.id)"
                                                                            onkeyup="solo_3_numeros(this.id)">
                                                                    </div>
                                                                    <div class="col-sm-6" style="padding: 0px">
                                                                        <input type="text" name="" class="form-control form-control-sm"
                                                                            id="TxtNumSerieDosComp" placeholder="001"
                                                                            onblur="autocompletar_serie_num(this.id)"
                                                                            onkeyup="solo_3_numeros(this.id)">
                                                                    </div> -->
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-1" style="padding-left: 5px;padding-right: 5px">
                                                                <b>Numero</b>
                                                                <input type="text" name="" class="form-control form-control-sm"
                                                                    id="CNumSerieTresComp" onkeyup="solo_9_numeros(this.id)"
                                                                    onblur="validar_num_factura(this.id)"
                                                                    placeholder="000000001">
                                                            </div>
                                                            <div class="col-sm-2" style="padding-left: 5px;padding-right: 5px">
                                                                <b>Fecha</b>
                                                                <input type="date" name="" class="form-control form-control-sm"
                                                                    id="MBFechaEmiComp">
                                                            </div>
                                                            <div class="col-sm-3" style="padding-right: 5px;">
                                                                <b>Autorizacion sri</b>
                                                                <input type="text" name="" class="form-control form-control-sm"
                                                                    id="TxtNumAutComp">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="box-body">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="menu1" role="tabpanel" aria-labelledby="menu1-tab" tabindex="0">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <b>Forma de pago</b>
                                                <select class="form-select form-select-sm" onchange="mostrar_panel_ext()"
                                                    id="CFormaPago">
                                                    <option value="">Seleccione forma de pago</option>
                                                    <option value="1">Local</option>
                                                    <option value="2">Exterior</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-8">
                                                <b>Tipo de pago</b>
                                                <select class="form-select form-select-sm" id="DCTipoPago"
                                                    onchange="$('#DCTipoPago').css('border','1px solid #d2d6de');">
                                                    <option value="">Seleccione tipo de pago</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" id="panel_exterior" style="display: none;">
                                            <div class="col-sm-4">
                                                <b>Pais al que se efectua el pago</b>
                                                <select class="form-select form-select-sm" id="DCPais">
                                                    <option>Seleccione Pais</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-6"><br>
                                                Aplica convenio de doble tributacion?
                                                <br>
                                                Pago sujeto a retencion en aplicacion de la forma legal?
                                                <br>
                                            </div>
                                            <div class="col-sm-2 text-end"><br>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rbl_convenio" id="rbl_convenio_si" value="SI" checked="">
                                                    <label class="form-check-label" for="rbl_convenio_si">SI</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rbl_convenio" id="rbl_convenio_no" value="NO">
                                                    <label class="form-check-label" for="rbl_convenio_no">NO</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rbl_pago_retencion" id="rbl_pago_retencion_si" value="SI" checked="">
                                                    <label class="form-check-label" for="rbl_pago_retencion_si">SI</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rbl_pago_retencion" id="rbl_pago_retencion_no" value="NO">
                                                    <label class="form-check-label" for="rbl_pago_retencion_no">NO</label>
                                                </div>

                                                <!-- <label class="radio-inline"><input type="radio" name="rbl_convenio" checked=""
                                                        value="SI">SI</label>
                                                <label class="radio-inline"><input type="radio" name="rbl_convenio"
                                                        value="NO">NO</label>
                                                <label class="radio-inline"><input type="radio" name="rbl_pago_retencion"
                                                        checked="" value="SI">SI</label>
                                                <label class="radio-inline"><input type="radio" name="rbl_pago_retencion"
                                                        value="NO">NO</label> -->
                                            </div>
                                        </div>
                                        <div class="row"><br>
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <!-- <div class="box-header" style="padding:0px">
                                                        <h3 class="box-title"><b>INGRESE LOS DATOS DE LA
                                                                RETENCION_________________FORMULARIO 103</b>
                                                        </h3>
                                                    </div> -->
                                                    <div class="card-body">
                                                        <h3 class="card-title">INGRESE LOS DATOS DE LA
                                                                RETENCION_________________FORMULARIO 103
                                                        </h3>
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <input class="form-check-input" type="checkbox" name="ChRetF" id="ChRetF" onclick="mostra_select()">
                                                                <label class="form-check-label" for="inlineRadio1" id="lbl_rbl"> Retencion en la fuente</label>
                                                                <!-- <label class="radio-inline" onclick="mostra_select()"
                                                                    id="lbl_rbl"><input type="checkbox" name="ChRetF"
                                                                        id="ChRetF"> Retenecion en la fuente</label> -->
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <select class="form-select form-select-sm" id="DCRetFuente"
                                                                    style="display: none;"
                                                                    onchange="$('#DCRetFuente').css('border','1px solid #d2d6de');">
                                                                    <option value=""> Seleccione Tipo de retencion</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-2">
                                                                Serie
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="input-group input-group-sm">
                                                                            <input
                                                                                type="text" class="form-control form-control-sm"
                                                                                name="TxtNumUnoComRet" id="TxtNumUnoComRet"
                                                                                onkeyup="solo_3_numeros(this.id)" placeholder="001"
                                                                                onblur="autocompletar_serie_num(this.id)">
                                                                            <input
                                                                                type="text" class="form-control form-control-sm"
                                                                                name="TxtNumDosComRet" id="TxtNumDosComRet"
                                                                                onkeyup="solo_3_numeros(this.id)" placeholder="001"
                                                                                onblur="autocompletar_serie_num(this.id)">
                                                                        </div>
                                                                    </div>
                                                                    <!-- <div class="col-sm-6"
                                                                        style="padding-left: 0px;padding-right: 0px;"><input
                                                                            type="text" class="form-control form-control-sm"
                                                                            name="TxtNumUnoComRet" id="TxtNumUnoComRet"
                                                                            onkeyup="solo_3_numeros(this.id)" placeholder="001"
                                                                            onblur="autocompletar_serie_num(this.id)"></div>
                                                                    <div class="col-sm-6"
                                                                        style="padding-left: 0px;padding-right: 0px;"><input
                                                                            type="text" class="form-control form-control-sm"
                                                                            name="TxtNumDosComRet" id="TxtNumDosComRet"
                                                                            onkeyup="solo_3_numeros(this.id)" placeholder="001"
                                                                            onblur="autocompletar_serie_num(this.id)"></div> -->
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                Numero
                                                                <input type="text" class="form-control form-control-sm"
                                                                    name="TxtNumTresComRet" id="TxtNumTresComRet"
                                                                    onblur="validar_num_retencion()"
                                                                    onkeyup="solo_9_numeros(this.id)" placeholder="000000001">
                                                            </div>
                                                            <div class="col-sm-4">
                                                                Autorizacion
                                                                <input type="text" name="" class="form-control form-control-sm"
                                                                    id="TxtNumUnoAutComRet">
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="row">
                                                                    <div class="col-sm-4"><br>
                                                                        SUMATORIA
                                                                    </div>
                                                                    <div class="col-sm-8"><br>
                                                                        <input type="text" name="" class="form-control form-control-sm"
                                                                            id="TxtSumatoria">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-7">
                                                                <b>CODIGO DE RETENCION</b>
                                                                <select class="form-select form-select-sm" id="DCConceptoRet"
                                                                    name="DCConceptoRet" onchange="calcular_porc_ret()">
                                                                    <option value="">Seleccione Codigo de retencion</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <b>BASE IMP</b>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    name="TxtBimpConA" id="TxtBimpConA">
                                                            </div>
                                                            <div class="col-sm-1" style="padding-left: 0px;padding-right: 0px">
                                                                <b>PORC</b>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    name="TxtPorRetConA" id="TxtPorRetConA"
                                                                    onblur="insertar_grid()" readonly="">
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <b>VALOR RET</b>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    name="TxtValConA" id="TxtValConA" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="box-body">
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover" id="tbl_retencion">
                                                    <thead>
                                                        <th>CodRet</th>
                                                        <th>Detalle</th>
                                                        <th>BaseImp</th>
                                                        <th>Porcentaje</th>
                                                        <th>ValRet</th>
                                                        <th>EstabRetencion</th>
                                                        <th>PtoEmiRetencion</th>
                                                        <th>SecRetencion</th>
                                                        <th>AutoRetencion</th>
                                                        <th>FechaEmiRet</th>
                                                        <th>Cta_Retencion</th>
                                                        <th>EstabFactura</th>
                                                        <th>PuntoEmiFactura</th>
                                                        <th>Factura_No</th>
                                                        <th>IdProv</th>
                                                        <th>Item</th>
                                                        <th>codigoU</th>
                                                        <th>A_No</th>
                                                        <th>T_No</th>
                                                        <th>Tipo_Trans</th>
                                                    </thead>
                                                    <tbody>
        
                                                    </tbody>
        
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 text-end">
                                                <b>Total Retencion</b>
                                                <input type="text" class="form-control form-control-sm" name="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="menu2" role="tabpanel" aria-labelledby="menu2-tab" tabindex="0">
                                        <div class="row text">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <b>NUMERO DEL CONTRATO DEL PARTIDO POLITICO</b>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control form-control-sm" name="" id="TxtNumConParPol">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <b>MONTO TITULO ONEROSO</b>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control form-control-sm" name="TxtMonTitOner"
                                                            id="TxtMonTitOner">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <b>MONTO DEL CONTRATO</b>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control form-control-sm" name="TxtMonTitGrat"
                                                            id="TxtMonTitGrat">
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
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div id="myModal_comprobante" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="titulo-modal">GRABACIÓN DE COMPROBANTE:</h4>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label for="numComprobante"><b>Ingrese el número de comprobante:</b></label>
                <input type="text" name="numComprobante" id="numComprobante" value="0">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="Command3_Click()">Buscar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>

    </div>
</div>

<!-- partial:index.partial.html -->

<!-- partial -->
<!-- //<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->