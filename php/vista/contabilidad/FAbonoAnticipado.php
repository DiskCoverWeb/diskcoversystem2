<script type="text/javascript"></script>
<script src="../../dist/js/FAbonoAnticipado.js"></script>

<div class="row">
    <div class="col-sm-10">
        <form id="form_abonos" class="row">

            <div class="form-inline col-sm-12">
                <div class="checkbox col-sm-4">
                    <input type="checkbox" id="CheqRecibo" checked>
                    <label for="CheqRecibo">RECIBO CAJA No.</label>
                </div>
                <div class="form-group col-sm-4">
                    <input type="" class="form-control" id="TxtRecibo" value="0">
                </div>
                <div class="form-group col-sm-4">
                    <label for="MBFecha">FECHA</label>
                    <input type="date" class="form-control" id="MBFecha" name="MBFecha"
                        value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>

            <div class="col-sm-12" style="padding-top: 5px;" id="Frame1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group-addon" for="DCTipo" style="color: red;">TIPO</label>
                                    <select class="form-control" id="DCTipo" name="DCTipo" style="width: 100%;" onchange="Listar_Facturas_Pendientes()">
                                        <option value="">Seleccione</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group-addon" for="DCFactura">Factura No.</label>
                                    <select class="form-control" id="DCFactura" name="DCFactura" style="width: 100%;">
                                        <option value="">Factura</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 5px;"></div>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="form-control">
                                    <label  id="Label4">FECHA DE EMISION</label>
                                </div>
                            </div>
                            <div class="col-sm-3">                               
                                <div class="form-control">
                                    <label  id="Label8"></label>
                                </div>                              
                            </div>
                            <div class="col-sm-4">
                                <div class="form-control" style="background-color: red;">
                                    <label id="Label1" ></label>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 5px;"></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-control">
                                    <label id="Label3"></label>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 5px;"></div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-control">
                                    <label id="Label6">Saldo Pendiente</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-control">
                                    <label id="LabelSaldo"></label>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 5px;"></div>
                        <div class="row">
                            <div class="col-sm-12" >
                                <div class="form-control " style="padding-bottom: 50px;">
                                    <label id="LblObs" style="color: violet;">Observacion</label>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 5px;"></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-control " style="padding-bottom: 50px;">
                                    <label id="LblNota" style="color: violet;">Nota</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12" style="padding-top: 5px;" id="Frame2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Abono Anticipado</h3>
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="DCCliente">Cliente</label>
                                    <select class="form-select form-select-sm" id="DCClientes" name="DCCliente"
                                        style="width: 100%;">
                                        <option value="">Seleccione</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="TxtConcepto">Observación</label>
                                    <textarea class="form-control" id="TxtConcepto" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="DCBanco">Cuenta Contable del Ingreso</label>
                                    <select class="form-select form-select-sm" id="DCBanco" name="DCBanco"
                                        style="width: 100%;">
                                        <option value="">Banco</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="DCCtaAnt">Cuenta Contable de Anticipo</label>
                                    <select class="form-select form-select-sm" id="DCCtaAnt" name="DCCtaAnt"
                                        style="width: 100%;">
                                        <option value="">Banco</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="TextCajaMN" class="col-sm-5 control-label">Caja MN.</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="TextCajaMN" placeholder="00000000" value="0.00">
                    </div>
                </div>
                <div class="form-group">
                    <label for="LabelPend" class="col-sm-5 control-label" id="Label10">Saldo Actual</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="LabelPend" placeholder="00000000" value="0.00">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm-2">
        <button class="btn btn-default btn-block" id="btn_g" onclick="Command1_Click()">
            <img src="../../img/png/grabar.png"><br>&nbsp;Guardar&nbsp;
        </button>
        <button class="btn btn-default btn-block" onclick="close()">
            <img src="../../img/png/bloqueo.png"><br>Cancelar
        </button>
    </div>
</div>