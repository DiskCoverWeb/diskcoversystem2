<?php
date_default_timezone_set('America/Guayaquil');
?>
<style type="text/css">
    .boton-enfocado {
        border: 2px solid blue;
        /* o cualquier otro estilo que prefieras */
        background-color: lightgray;
    }
     .estiloOscuro {
        background-color: #f5f5f5;
        color: black;
    }
</style>
<script src="../../dist/js/contabilidad/ISubCtas.js"></script>

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
<div class="row row-cols-auto mb-2">
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
<div class="card">
    <div class="card-body">
         <div class="row ">
            <div class="col-sm-12 ">
                <div>
                    <h4 class="fw-normal h5 p-1">Tipo de Cuenta</h4>
                </div>
            </div>  
        </div> 
        <div class="row">
            <div class="col-sm-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="TipoCuenta" id="OpcI" value='I' checked
                        onclick="OpcI_Click();">
                    <label class="form-check-label" for="OpcI">
                        Modulo de Ingresos
                    </label>
                </div>
            </div>
            <div class="col-sm-3">            
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="TipoCuenta" id="OpcG" value='G'
                        onclick="OpcG_Click();">
                    <label class="form-check-label" for="OpcG">
                        Modulo de Gastos
                    </label>
                </div>            
            </div>
            <div class="col-sm-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="TipoCuenta" id="OpcPM" value='PM'
                        onclick="OpcPM_Click();">
                    <label class="form-check-label" for="OpcPM">
                        Modulo de Primas
                    </label>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="TipoCuenta" id="OpcCC" value='CC'
                        onclick="OpcCC_Click();">
                    <label class="form-check-label" for="OpcCC">
                        Centro de Costos
                    </label>
                </div>                
            </div>            
        </div>       
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 text-center">
                  <b> SUBCUENTA DE BLOQUE</b>
            </div>
            <div class="col-sm-9">
                 <div id="encabezadosSubCtas"></div>
            </div>
            <div class="col-sm-3 text-end">
                <button class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" title="Primero"
                    id="btnPrimero">
                    <img src="../../img/png/primero.png" style="width: 20px; height: 20px;">
                </button>
                <button class="btn btn-outline-secondary rotar-180 btn-sm" data-toggle="tooltip"
                    title="Anterior" id="btnAnterior">
                    <img src="../../img/png/siguiente.png" style="width: 20px; height: 20px;">
                </button>
                <button class="btn btn-outline-secondary btn-sm" data-toggle="tooltip"
                    title="Siguiente" id="btnSiguiente">
                    <img src="../../img/png/siguiente.png" style="width: 20px; height: 20px;">
                </button>
                <button class="btn btn-outline-secondary btn-sm" data-toggle="tooltip"
                    title="Ultimo" id="btnUltimo">
                    <img src="../../img/png/primero.png" style="width: 20px; height: 20px; transform: rotate(180deg);
        transform-origin: center;">
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12" style="overflow-y:scroll;height: 200px;">
                 <div id="DLCtas" class="list-group" data-toggle="tooltip" title="Presione las flechas para habilitar">
                </div>
            </div>
            
        </div>        
    </div>    
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <!-- Código -->
            <div class="col-sm-2">
                <b>CÓDIGO</b>
                <input type="text" class="form-control form-control-sm" id="TxtCodigo" placeholder="000"
                    onclick="MarcarTexto(this);">
            </div>
            <!-- Nivel -->
            <div class="col-sm-2">
                <b>NIVEL No.</b>
                <input type="text" class="form-control form-control-sm" id="TxtNivel" placeholder="00"
                    onclick="MarcarTexto(this);">
            </div>
            <div class="col-sm-2">
            <br>                
                <label class="form-check-label" for="CheqNivel" id="LabelCheqNivel">
                    <input class="form-check-input mr-1" type="checkbox" name="CheqNivel" id="CheqNivel"> Agrupación nivel
                </label>
            </div>
            <!-- SUBCUENTA -->
            <div class="col-sm-4">
                <b>SUBCUENTA</b>
                <input type="text" class="form-control form-control-sm" id="TextSubCta" placeholder="" value=""
                    onclick="MarcarTexto(this);">
            </div>
        </div>
         <div class="row">
            <!-- REEMBOLSO -->
            <div class="col-sm-2">
                <b>REEMBOLSO</b>
                <input type="text" class="form-control form-control-sm" id="TxtReembolso" placeholder="0" value="0"
                    onclick="MarcarTexto(this);">
            </div>
            <!-- VALOR -->
            <div class="col-sm-2">
                <b id="Label1">VALOR</b>
                <input type="text" class="form-control form-control-sm" id="TextPresupuesto" placeholder="0.00"
                    value="0" style="text-align:right;" onclick="MarcarTexto(this);">
            </div>
            <!-- CUENTA RELACIONADA -->
            <div class="col-sm-2 p-0">
                <b>CUENTA RELACIONADA</b>
                <input type="text" class="form-control form-control-sm" id="MBoxCta" placeholder="0" value="0"
                    style="text-align:right;" onclick="MarcarTexto(this);">
            </div>
            <!-- BLOQUEAR CODIGO -->
            <div class="col-md-2 col-sm-1" >
                <br>
                <label for="CheqBloquear" id="LabelCheqBloquear">
                     <input class="form-check-input" type="checkbox" name="CheqBloquear" id="CheqBloquear"
                    value='' onchange="CheqBloquear_Click();">
                    Bloquear Codigo
                </label>
            </div>
            <!-- FECHA DESDE -->
            <div class="col-md-2 col-sm-2">
                <b id="Label10">Desde</b>
                <input type="date" class="form-control form-control-sm" id="MBFechaI" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <!-- FECHA HASTA -->
            <div class="col-md-2 col-sm-2 col-lg-2">
                <b id="Label9">Hasta</b>
                <input type="date" class="form-control form-control-sm" id="MBFechaF" value="<?php echo date('Y-m-d'); ?>">
            </div>
        </div>
        <div class="row" style="padding: 10px 0">
            <!-- NOMBRE DE LA CUENTA -->
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm bg-light" id="Label5" placeholder="" value="" readonly
                    style="color:blue;" disabled>
            </div>
            <!-- GASTO DE CAJA -->
            <div class="col-md-2">
                <label class="form-check-label" for="CheqCaja" id="LabelCheqCaja">
                    <input class="form-check-input" type="checkbox" name="CheqCaja" id="CheqCaja" value=''>
                    Gasto de Caja
                </label>
            </div>
        </div>
        
    </div>    
</div>
  