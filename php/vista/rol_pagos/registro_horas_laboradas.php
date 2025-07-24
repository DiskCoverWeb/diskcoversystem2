<?php
?>
<style>
    .bg-person-sky-blue {
        background-color: #CFE9EF;
        color: #444;
        border-color: #ddd;
    }
</style>
<script src="../../dist/js/rol_pagos/registro_horas_laboradas.js"></script>
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
<div>
    <div class="card">
        <div class="card-body row">
            <div class="col-8 row">
                <div class="col-3">
                    <div class="input-group">
                        <div class="col-12 bg-person-sky-blue text-center rounded-top">
                            <b>FECHA:</b>
                        </div>
                        <div class="col-12">
                            <input type="date" id="txt_fecha" class="form-control form-control-sm" onblur="rellenarBeneficiarios()"></input>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <label>Ingreso:</label>
                    <div class="d-flex flex-wrap gap-5 border rounded p-1 justify-content-center">
                        <div class="form-check d-flex flex-row-reverse align-items-center gap-2">
                            <label class="form-check-label fw-bold" for="Check_diario">Diario</label>
                            <input class="form-check-input" name="Ingreso" type="radio" value="Diario" id="Check_diario" checked>
                        </div>

                        <div class="form-check d-flex flex-row-reverse align-items-center gap-2">
                            <label class="form-check-label fw-bold" for="Check_semanal">Semanal</label>
                            <input class="form-check-input" name="Ingreso" type="radio" value="Semanal" id="Check_semanal">
                        </div>

                        <div class="form-check d-flex flex-row-reverse align-items-center gap-2">
                            <label class="form-check-label fw-bold" for="Check_quincenal">Quincenal</label>
                            <input class="form-check-input" name="Ingreso" type="radio" value="Quincenal" id="Check_quincenal">
                        </div>

                        <div class="form-check d-flex flex-row-reverse align-items-center gap-2">
                            <label class="form-check-label fw-bold" for="Check_mensual">Mensual</label>
                            <input class="form-check-input" name="Ingreso" type="radio" value="Mensual" id="Check_mensual">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="input-group">
                        <div class="col-12 bg-person-sky-blue rounded-top ps-2">
                            <b>BENEFICIARIO:</b>
                        </div>
                        <select class="form-select form-select-sm" id="beneficiario">
                            <option value="">Seleccione un beneficiario</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 row">
                    <div class="col-4">
                        <div class="input-group">
                            <div class="rounded-start bg-person-sky-blue text-center d-flex align-items-center p-1">
                                <b>VALOR HORA: </b>
                            </div>
                            <input class="form-control form-control-sm" type="number" id="txt_valor_hora" placeholder="0.00"></input>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="input-group">
                            <div class="rounded-start bg-person-sky-blue text-center d-flex align-items-center p-1">
                                <b>HORAS TRABAJADAS: </b>
                            </div>
                            <input class="form-control form-control-sm" type="number" id="txt_horas_trabajadas" placeholder="0.00"></input>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-group">
                            <div class="rounded-start bg-person-sky-blue text-center d-flex align-items-center p-1">
                                <b>DIAS: </b>
                            </div>
                            <input class="form-control form-control-sm" type="number" id="txt_dias" placeholder="0.00"></input>
                        </div>
                    </div> 
                </div>
                 <div class="col-12 row">
                    <div class="col-4">
                        <div class="input-group">
                            <div class="rounded-start bg-person-sky-blue text-center d-flex align-items-center p-1">
                                <b>HORAS EXTRAS: </b>
                            </div>
                            <input class="form-control form-control-sm" type="number" id="txt_horas_extras" placeholder="0.00"></input>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="input-group">
                            <div class="rounded-start bg-person-sky-blue text-center d-flex align-items-center p-1">
                                <b>Valor por hora</b>
                            </div>
                            <select class="form-select form-select-sm">
                                <option>V<option>
                            </select>
                            <input class="form-control form-control-sm" type="number" id="txt_valor_por_hora" placeholder="0.00"></input>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-group">
                            <div class="rounded-start bg-person-sky-blue text-center d-flex align-items-center p-1">
                                <b>ORDEN: </b>
                            </div>
                            <input class="form-control form-control-sm" id="txt_orden" placeholder="0.00"></input>
                        </div>
                    </div> 
                </div>
            </div>
            <div class="row col-4">
                <div class="col-6">
                    <label>Movimientos de:</label>
                    <div class="d-flex flex-column flex-wrap gap-2 border rounded p-1 text-center">
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input" type="radio" name="Movimientos" value="mes_a" id="Check_mes_a" checked>
                            <label class="form-check-label fw-bold" for="Check_mes_a"> Mes actual</label>
                        </div>
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input" type="radio" name="Movimientos" value="dos_m" id="Check_dos_m">
                            <label class="form-check-label fw-bold" for="Check_dos_m"> Dos meses</label>
                        </div>
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input" type="radio" name="Movimientos" value="tres_m" id="Check_tres_m">
                            <label class="form-check-label fw-bold" for="Check_tres_m"> Tres meses</label>
                        </div>
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input" type="radio" name="Movimientos" value="cuatro_m" id="Check_cuatro_m">
                            <label class="form-check-label fw-bold" for="Check_cuatro_m"> Cuatro meses</label>
                        </div>
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input" type="radio" name="Movimientos" value="anio_a" id="Check_anio_a">
                            <label class="form-check-label fw-bold" for="Check_anio_a"> Anual actual</label>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <button class="btn btn-sm btn-outline-secondary d-flex flex-column justify-content-center align-items-center" style="min-width: 120px; height: 70px;"><img src="../../img/png/users.png" style="width: 40px; height: 40px;" onclick="generarDias()"><label>Generar días</label></button>
                    <button class="btn btn-sm btn-outline-secondary d-flex flex-column justify-content-center align-items-center" style="min-width: 120px; height: 70px;"><img src="../../img/png/sub_mod_mes.png" style="width: 40px; height: 40px;"><label>Eliminar días</label></button>
                    <button class="btn btn-sm btn-outline-secondary d-flex flex-column justify-content-center align-items-center" style="min-width: 120px; height: 70px;"><img src="../../img/png/salire.png" style="width: 40px; height: 40px;"><label>Salir</label></button>
                </div>
            </div>
        <div>
    <div>
</div>
<div class="mt-3">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-tabs w-100">
                        <li class="nav-item flex-fill text-center" role="presentation">
                            <a class="nav-link active" href="#sueldo_div" data-bs-toggle="tab">SUELDO</a>
                        </li>
                        <li class="nav-item flex-fill text-center" role="presentation">
                            <a class="nav-link" href="#novedades_div" data-bs-toggle="tab">NOVEDADES</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="sueldo_div">
                            <div class="col-sm-12">
                                <table class="table text-sm w-100" id="tbl_sueldo"></table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="novedades_div">
                            <div class="col-sm-12">
                                <table class="table text-sm w-100" id="tbl_novedades"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 row">
        <div class="col-3">
            <div class="input-group">
                <div class="rounded-start bg-person-sky-blue d-flex align-items-center p-2">
                    <b>Horas Trabajadas</b>
                </div>
                <input class="form-control form-control-sm" type="number" id="txt_total_horas_t" placeholder="0.00" disabled></input>
            </div>
        </div>
        <div class="col-3">
            <div class="input-group">
                <div class="rounded-start bg-person-sky-blue d-flex align-items-center p-2">
                    <b>Ingreso Liquido</b>
                </div>
                <input class="form-control form-control-sm" type="number" id="txt_total_ing_liq" placeholder="0.00" disabled></input>
            </div>
        </div>
    </div>
</div>
