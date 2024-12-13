<?php
date_default_timezone_set('America/Guayaquil');
?>
<style>
    .rotar-180 {
        transform: rotate(180deg);
        transform-origin: center;
    }

    .text-box{
        font-size: 0.8rem; 
    }

    .bg-gray-custom{
        background-color: #e8e9eb; 
    }
    #DLCtas {
        max-height: 165px;
        overflow-y: auto;
        width: auto;
        height: 165px;
    }

    .boton-enfocado {
        border: 2px solid blue;
        /* o cualquier otro estilo que prefieras */
        background-color: lightgray;
    }

    .estiloOscuro {
        background-color: #f5f5f5;
        color: black;
    }

    .btn-small {
        padding: 5px;
        font-size: 12px;
    }

    #encabezadosSubCtas h3 {
        /*text-align: center;*/
        font-size: 1.5em;
        white-space: pre;
        margin: 10px 0px 10px 0px;
    }

    #btnContainer>.row {
        display: flex;
        align-items: center;
        margin: -15px 0px 0px 10px;
    }

    #btnContainer>.row>div:first-child {
        flex-grow: 3;
        /* Toma 3/4 del espacio disponible */
        text-align: right;
        padding-right: 125px;
        /* Ajusta según sea necesario */
    }

    #btnContainer>.row>div:last-child {
        flex-grow: 1;
        /* Toma 1/4 del espacio disponible */
        text-align: right;
        padding-left: 10px;
        /* Ajusta según sea necesario */
    }
</style>

<script src="../../dist/js/ISubCtas.js"></script>

<script>
    //btnNuevo
    function NuevaCta() {
        $("#DLCtas button").prop('disabled', true).addClass('estiloOscuro');
        $('#TextSubCta').val('');
        $('#MBoxCta').val(0);
        var NumEmpresa = <?php echo $_SESSION['INGRESO']['item']; ?>;
        $('#TxtCodigo').val(NumEmpresa + '0000000');
    }

    function QuitarBloqueoBotonesSubCtas() {
        $("#DLCtas button").prop('disabled', false).removeClass('estiloOscuro');
    }

    function LimpiarCampos() {
        $('#TextSubCta').val('');
        $('#TxtCodigo').val('<?php echo $_SESSION['INGRESO']['item']; ?>0000000');
        $('#MBoxCta').val('0');
        $('#Label5').val('');
        $('#TxtReembolso').val(0);
        $('#TxtNivel').val('00');
        $('#TextPresupuesto').val('0');
        $('#MBFechaI').val('<?php echo date('Y-m-d'); ?>');
        $('#MBFechaF').val('<?php echo date('Y-m-d'); ?>');
        $('#CheqNivel').prop('checked', false);
        $('#CheqBloquear').prop('checked', false).change();;
        $('#CheqCaja').prop('checked', false);
    }

</script>

<div id="generalContainer" class="overflow-auto pt-1 pb-3">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">
        <?php echo $NombreModulo; ?>
      </div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Ctas. Ingreso / Egresos / Primas / Centro de Costos</li>
            </ol>
          </nav>
        </div>
    </div>
    <div class="row row-cols-auto">
        <div class="btn-group">
            <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
            print_r($ruta[0] . '#'); ?>" data-bs-toggle="tooltip" title="Salir" class="btn btn-outline-secondary btn-small">
                <img src="../../img/png/salire.png">
            </a>
            <button class="btn btn-outline-secondary btn-small" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Grabar" id="btnGrabar"
                onclick="GrabarCta();">
                <img src="../../img/png/grabar.png">
            </button>
            <button class="btn btn-outline-secondary btn-small" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nuevo" id="btnNuevo"
                onclick="">
                <img src="../../img/png/nuevo.png">
            </button>
            <button class="btn btn-outline-secondary btn-small" data-bs-toggle="tooltip" data-bs-placement="bottom"
                title="Seleccione una SubCuenta" id="btnEliminar" onclick="Eliminar();">
                <img src="../../img/png/eliminar.png">
            </button>
        </div>
    </div>
    <div class="row mt-1 pt-2 ps-3 pb-0 pe-0" >
        <div class="col-sm-12 panel panel-info">
            <div class="row ">
                <div class="col-sm-12 ">
                    <div>
                        <h4 class="fw-normal h5 p-1">Tipo de Cuenta</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-check col-sm-3">
                    <input class="form-check-input" type="radio" name="TipoCuenta" id="OpcI" value='I' checked
                        onclick="OpcI_Click();">
                    <label class="form-check-label" for="OpcI">
                        Modulo de Ingresos
                    </label>
                </div>
                <div class="form-check col-sm-3">
                    <input class="form-check-input" type="radio" name="TipoCuenta" id="OpcG" value='G'
                        onclick="OpcG_Click();">
                    <label class="form-check-label" for="OpcG">
                        Modulo de Gastos
                    </label>
                </div>
                <div class="form-check col-sm-3">
                    <input class="form-check-input" type="radio" name="TipoCuenta" id="OpcPM" value='PM'
                        onclick="OpcPM_Click();">
                    <label class="form-check-label" for="OpcPM">
                        Modulo de Primas
                    </label>
                </div>
                <div class="form-check col-sm-3">
                    <input class="form-check-input" type="radio" name="TipoCuenta" id="OpcCC" value='CC'
                        onclick="OpcCC_Click();">
                    <label class="form-check-label" for="OpcCC">
                        Centro de Costos
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-12 ps-0">
                    <div class="panel border panel-default col-12">
                        <div class="panel-heading bg-gray-custom" style="text-align:center;">
                            SUBCUENTA DE BLOQUE
                        </div>
                        <div class="panel-body" id="btnContainer">
                            <div class="row row-cols-auto pt-3" style="text-align: right;">
                                <div>
                                    <div id="encabezadosSubCtas">
                                    </div>
                                </div>
                                <div>
                                    <div class = "">
                                        <button class="btn btn-outline-secondary btn-small" data-toggle="tooltip" title="Primero"
                                            id="btnPrimero">
                                            <img src="../../img/png/primero.png" style="width: 20px; height: 20px;">
                                        </button>
                                        <button class="btn btn-outline-secondary rotar-180 btn-small" data-toggle="tooltip"
                                            title="Anterior" id="btnAnterior">
                                            <img src="../../img/png/siguiente.png" style="width: 20px; height: 20px;">
                                        </button>
                                        <button class="btn btn-outline-secondary btn-small" data-toggle="tooltip"
                                            title="Siguiente" id="btnSiguiente">
                                            <img src="../../img/png/siguiente.png" style="width: 20px; height: 20px;">
                                        </button>
                                        <button class="btn btn-outline-secondary rotar-180 btn-small" data-toggle="tooltip"
                                            title="Ultimo" id="btnUltimo">
                                            <img src="../../img/png/primero.png" style="width: 20px; height: 20px;">
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="DLCtas" class="list-group" data-toggle="tooltip"
                                title="Presione las flechas para habilitar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row p-1 border mt-2 me-2">
                <div class="panel panel-default">
                    <div class="panel-body p-3">

                        <div class="row" style="margin: -15px 0px 0px -13px ;">
                            <!-- Código -->
                            <div class="col-2 text-box fw-bold">
                                <label for="TxtCodigo" class="ms-2 col-2">CÓDIGO</label>
                                <input type="text" class="form-control form-control-sm col-2" id="TxtCodigo" placeholder="000"
                                    onclick="MarcarTexto(this);">
                            </div>
                            <!-- Nivel -->
                            <div class="col-2 text-box fw-bold text-box fw-bold">
                                <label for="TxtNivel" class="ms-2">NIVEL No.</label>
                                <input type="text" class="form-control form-control-sm" id="TxtNivel" placeholder="00"
                                    onclick="MarcarTexto(this);">
                            </div>
                            <!-- Agrupación Nivel -->
                            <div class="col-2 text-box fw-bold" style="padding-top:30px">
                                <input class="form-check-input mr-1" type="checkbox" name="CheqNivel" id="CheqNivel">
                                <label class="form-check-label" for="CheqNivel" id="LabelCheqNivel">Agrupación
                                    nivel</label>
                            </div>
                            <!-- SUBCUENTA -->
                            <div class="col-4 text-box fw-bold">
                                <label for="TextSubCta">SUBCUENTA</label>
                                <input type="text" class="form-control form-control-sm" id="TextSubCta" placeholder="" value=""
                                    onclick="MarcarTexto(this);">
                            </div>
                        </div>

                        <div class="row" style="padding: 10px 0">
                            <!-- REEMBOLSO -->
                            <div class="col-2 text-box fw-bold">
                                <label for="TxtReembolso">REEMBOLSO</label>
                                <input type="text" class="form-control form-control-sm" id="TxtReembolso" placeholder="0" value="0"
                                    onclick="MarcarTexto(this);">
                            </div>
                            <!-- VALOR -->
                            <div class="col-2 text-box fw-bold">
                                <label for="TextPresupuesto" id="Label1">VALOR</label>
                                <input type="text" class="form-control form-control-sm" id="TextPresupuesto" placeholder="0.00"
                                    value="0" style="text-align:right;" onclick="MarcarTexto(this);">
                            </div>
                            <!-- CUENTA RELACIONADA -->
                            <div class="text-box fw-bold col-3">
                                <label for="MBoxCta" style="font-size:0.7rem">CUENTA RELACIONADA</label>
                                <input type="text" class="form-control form-control-sm" id="MBoxCta" placeholder="0" value="0"
                                    style="text-align:right;" onclick="MarcarTexto(this);">
                            </div>
                            <!-- BLOQUEAR CODIGO -->
                            <div class="col-md-1 text-box fw-bold" style="padding-top:25px">
                                <input class="form-check-input" type="checkbox" name="CheqBloquear" id="CheqBloquear"
                                    value='' onchange="CheqBloquear_Click();">
                                <label class="form-check-label col-2" for="CheqBloquear" id="LabelCheqBloquear">
                                    Bloquear Codigo
                                </label>
                            </div>
                            <!-- FECHA DESDE -->
                            <div class="col-md-2 text-box fw-bold">
                                <label for="MBFechaI" id="Label10">Desde</label>
                                <input type="date" class="form-control" id="MBFechaI"
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <!-- FECHA HASTA -->
                            <div class="col-md-2 text-box fw-bold">
                                <label for="MBFechaF" id="Label9">Hasta</label>
                                <input type="date" class="form-control" id="MBFechaF"
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>

                        <div class="row" style="padding: 10px 0">
                            <!-- NOMBRE DE LA CUENTA -->
                            <div class="col-md-6 d-flex align-items-center">
                                <input type="text" class="form-control form-control-sm bg-light" id="Label5" placeholder="" value="" readonly
                                    style="color:blue;" disabled>
                            </div>
                            <!-- GASTO DE CAJA -->
                            <div class="col-md-2 d-flex align-items-center" style="padding-top:5px">
                                <input class="form-check-input" type="checkbox" name="CheqCaja" id="CheqCaja" value=''>
                                <label class="form-check-label" for="CheqCaja" id="LabelCheqCaja">
                                    Gasto de Caja
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>