<html>

<!--
    AUTOR DE RUTINA	: Dallyana Vanegas
    MODIFICADO POR : Teddy Moreira
    FECHA CREACION : 16/02/2024
    FECHA MODIFICACION : 28/05/2024
    DESCIPCION : Interfaz de modulo Gestion Social/Registro Beneficiario
 -->

<head>
    <style>
        
    </style>
    <link rel="stylesheet" href="../../dist/css/registro_beneficiario.css">

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/es.global.min.js'></script>
   
</head>
<body>
    <div>
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
        <div class="row row-cols-auto gx-3 pb-2 d-flex align-items-center ps-2">
            <div class="row row-cols-auto btn-group" id="btnsContainers">
                <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                                print_r($ruta[0] . '#'); ?>" title="Salir" class="btn btn-outline-secondary">
                    <img src="../../img/png/salire.png" width="35" height="35" alt="Salir">
                </a>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Guardar" id="btnGuardarAsignacion">
                    <img src="../../img/png/disco.png" width="35" height="35" alt="Guardar">
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Autorizar" id="btnAutorizarCambios">
                    <img src="../../img/png/admin.png" width="35" height="35" alt="Autorizar">
                </button>
            </div>
        </div>

        <div class="accordion" id="accordionExample">
            <div class="accordion-item" id="headingOne">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold" style="background-color:#f3e5ab; color:#000" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        INFORMACION GENERAL
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show">
                    <div class="accordion-body" style="background-color:#fffacd;">
                        
                        <div class="d-flex flex-wrap">
                            <div id="carouselBtnIma_93" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                </div>
                            </div>
                            <!--<div id="carouselBtnIma_93" class="carousel slide" data-ride="carousel"
                                style="margin-right: 10px;">
                                <div class="carousel-inner">
                                </div>
                            </div>-->
            
                            <div class="flex-grow-1 me-2">
                                <label for="select_93" style="display: block;"><b>Tipo de Beneficiario</b></label>
                                <select class="form-control input-xs" name="select_93" id="select_93"
                                    style="width: 100%;"></select>
                            </div>
            
                            <div id="carouselBtnImaDon" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                </div>
                            </div>
                            <!--<div id="carouselBtnImaDon" class="carousel slide" data-ride="carousel"
                                style="margin-right: 10px;">
                                <div class="carousel-inner">
                                </div>
                            </div>-->

                            <div class="flex-grow-1 me-2">
                                <label for="select_CxC" style="display: block;"><b>Tipo de Donación</b></label>
                                <select class="form-control input-xs" name="select_CxC" id="select_CxC"
                                    style="width: 100%;"></select>
                            </div>
            
                            <div class="campoSocial" class="flex-grow-1">
                                <label for="ruc" style="display: block;"><b>CI/RUC</b></label>
                                <select class="form-control input-xs" name="ruc" id="ruc" style="width: 100%;"></select>
                            </div>
            
                            <div class="campoSocial justify-content-center align-items-center">
                                <img src="../../img/png/SRIlogo.png" width="80" height="50"
                                    onclick="validarRucYValidarSriC()" id="validarSRI" title="VALIDAR RUC">
                            </div>
            
                            <div class="row campoSocial">
                                <div class="col-sm-6" style="width:100%">
                                    <label for="cliente" style="display: block;"><b>Nombre del
                                        Beneficiario/Usuario</b></label>
                                    <div class="input-group">
                                        <select class="form-control input-xs" name="cliente" id="cliente"
                                            style="width: 100%;"></select>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-success btn-xs btn-flat"
                                                id="btn_nuevo_cli" onclick="addCliente()" title="Nuevo cliente">
                                                <span class="fa fa-user-plus"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
            
                            <div class="campoFamilia w-100">
                                <label for="fechaIngreso" style="display: block;"><b>Fecha de ingreso</b></label>
                                <input type="date" id="fechaIngreso">
                            </div>

                            <div id="carouselBtnIma_87" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                </div>
                            </div>
            
                            <!--<div id="carouselBtnIma_87" class="carousel slide" data-ride="carousel"
                                style="margin-right: 10px;">
                                <div class="carousel-inner">
                                </div>
                            </div>-->
            
                            <div class="flex-grow-1">
                                <label for="select_87" style="display: initial;"><b>Estado</b></label>
                                <select class="form-control input-xs" name="select_87" id="select_87"
                                    style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="row campoSocial" style="margin: 10px; display: flex; flex-wrap: wrap;">
                            <div style="flex: 1; margin-right: 10px; ">
                                <div class="col" style="width:100%">
                                    <label for="nombreRepre" style="display: block;">Nombre Representante
                                        Legal</label>
                                    <input class="form-control input-xs" type="text" name="nombreRepre" id="nombreRepre"
                                        placeholder="Nombre Representante">
                                </div>
                            </div>
                            <div style="flex: 1; margin-right: 10px; ">
                                <label for="ciRepre" style="display: block;">CI Representante Legal</label>
                                <input class="form-control input-xs" type="text" name="ciRepre" id="ciRepre"
                                    placeholder="CI Representante">
                            </div>
                            <div style="flex: 1;">
                                <label for="telfRepre" style="display: block;">Teléfono Representante Legal</label>
                                <input class="form-control input-xs" type="text" name="telfRepre" id="telfRepre"
                                    placeholder="Representante legal">
                            </div>
                        </div>
            
                        <div class="row campoSocial" style="margin: 10px; display: flex; flex-wrap: wrap;">
                            <div style="flex: 1; margin-right: 10px; ">
                                <label for="contacto" style="display: block;">Contacto/Encargado</label>
                                <input class="form-control input-xs" type="text" name="contacto" id="contacto"
                                    placeholder="Contacto">
                            </div>
                            <div style="flex: 1; margin-right: 10px; ">
                                <label for="cargo" style="display: block;">Cargo</label>
                                <input class="form-control input-xs" type="text" name="cargo" id="cargo"
                                    placeholder="Profesión">
                            </div>
                            <div style="margin-right: 10px;  display: flex; ">
                                <img src="../../img/png/calendario2.png" width="60" height="60">
                            </div>
                            <div style="flex: 1; margin-right: 10px; ">
                                <label for="diaEntrega" style="display: block;">Día Entrega a Usuarios
                                    Finales</label>
                                <select class="form-control input-xs" name="diaEntrega" id="diaEntrega"></select>
                            </div>
                            <div style="margin-right: 10px;  display: flex; ">
                                <img src="../../img/png/reloj.png" width="55" height="55">
                            </div>
                            <div style="flex: 1; ">
                                <label for="horaEntrega" style="display: block;">Hora Entrega a Usuarios
                                    Finales</label>
                                <input type="time" name="horaEntrega" id="horaEntrega" class="form-control input-xs">
                            </div>
                        </div>
            
                        <div class="row" style="margin: 10px; display: flex; justify-content: center;">
                            <div class="col-sm-3 campoFamilia" style="margin-right:10px;">
                                <div class="row" style="display: flex; flex: 1; align-items: center;">
                                    <div style="flex: 0 0 auto; margin-right: 10px;" id="btnPrograma">
                                        <img src="../../img/png/programa.png" width="60" height="60"
                                            title="TIPO DE PROGRAMA" class="icon">
                                    </div>
                                    <div style="flex: 1; margin-right: 10px; margin-left: 10px;">
                                        <label for="select_85" style="display: block;">Programa</label>
                                        <select class="form-control input-xs" name="select_85" id="select_85"
                                            style="width: 100%;">
                                            <!--<option value="" selected disabled></option>
                                            <option value="familias">Familias</option>
                                            <option value="setentaYPiquito">70 y piquito</option>-->
                                        </select>
                                    </div>
                                </div>
            
                                <div class="row">
                                    <div style="flex: 1; margin-right: 10px; margin-left: 10px;">
                                        <label for="grupo" style="display: block;">Grupo</label>
                                        <select class="form-control input-xs" name="grupo" id="grupo"
                                            style="width: 100%;"></select>
                                    </div>
                                </div>
                            </div>
            
                            <div class="col-sm-3 campoFamilia" style="margin-right:10px;">
                                <div class="row">
                                    <label for="nombres" style="display: block;">Nombres</label>
                                    <input class="form-control input-xs" type="text" name="nombres" id="nombres"
                                        placeholder="Nombres">
                                </div>
                                <div class="row">
                                    <label for="apellidos" style="display: block;">Apellidos</label>
                                    <input class="form-control input-xs" type="text" name="apellidos" id="apellidos"
                                        placeholder="Apellidos">
                                </div>
            
                                <div class="row">
                                    <label for="cedula" style="display: block;">Cédula de identidad</label>
                                    <input class="form-control input-xs" type="text" name="cedula" id="cedula"
                                        placeholder="Cédula de identidad">
                                </div>
            
                                <div class="row">
                                    <label for="nivelEscolar" style="display: block;">Nivel escolar</label>
                                    <input class="form-control input-xs" type="text" name="nivelEscolar"
                                        id="nivelEscolar" placeholder="Cédula de identidad">
                                </div>
            
                                <div class="row">
                                    <label for="estadoCivil" style="display: block;">Estado civil</label>
                                    <select class="form-control input-xs" name="estadoCivil" id="estadoCivil"
                                        style="width: 100%;">
                                        <option value='' disabled selected>Seleccione</option>
                                    </select>
                                </div>
                            </div>
            
                            <div class="col-sm-3 campoFamilia" style="margin-right:10px;">
                                <div class="row">
                                    <label for="edad" style="display: block;">Edad</label>
                                    <input class="form-control input-xs" type="number" name="edad" id="edad"
                                        placeholder="Edad">
                                </div>
            
                                <div class="row">
                                    <label for="ocupacion" style="display: block;">Ocupación</label>
                                    <input class="form-control input-xs" type="text" name="ocupacion" id="ocupacion"
                                        placeholder="Ocupación">
                                </div>
            
                                <div class="row">
                                    <label for="telefonoFam" style="display: block;">Teléfono</label>
                                    <input class="form-control input-xs" type="text" name="telefonoFam" id="telefonoFam"
                                        placeholder="Teléfono">
                                </div>
            
                                <div class="row">
                                    <label for="pregunta" style="display: block;">¿Cómo se enteró del BAQ?</label>
                                    <input class="form-control input-xs" type="text" name="pregunta" id="pregunta"
                                        placeholder="¿Cómo se enteró del BAQ?">
                                </div>
                            </div>
            
                            <div class="col-sm-6 col-md-2 p-1 d-flex justify-content-center align-items-center text-center flex-column campoVolNo"
                                style="margin-right:10px; text-align: center; padding: 10px;">
                                <div class="row" id="btnMostrarDir">
                                    <img src="../../img/png/map.png" width="60" height="60" title="INGRESAR DIRECCIÓN"
                                        class="icon">
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label><b>Ingresar Dirección</b></label>
                                    </div>
                                </div>
            
                                <div class="row campoFamilia" id="btnInfoUser">
                                    <img src="../../img/png/infoUser.png" width="60" height="60"
                                        title="INFORMACIÓN DEL USUARIO" class="icon">
                                </div>
                                <div class="row campoFamilia">
                                    <div class="form-group">
                                        <label>Información del usuario</label>
                                    </div>
                                </div>
                            </div>
            
                            <div class="col-sm-3 campoSocial" style="margin-right:10px;">
                                <div class="row">
                                    <label for="email" style="display: block;">Email</label>
                                    <input class="form-control input-xs" type="text" name="email" id="email"
                                        placeholder="Email">
                                </div>
                                <div class="row">
                                    <label for="email2" style="display: block;">Email 2</label>
                                    <input class="form-control input-xs" type="text" name="email2" id="email2"
                                        placeholder="Email2">
                                </div>
                            </div>
            
                            <div class="col-sm-3 campoSocial" style="margin-right:10px;">
                                <div class="row">
                                    <label for="telefono" style="display: block;">Teléfono 1</label>
                                    <input class="form-control input-xs" type="text" name="telefono" id="telefono"
                                        placeholder="Teléfono ">
                                </div>
                                <div class="row">
                                    <label for="telefono2" style="display: block;">Teléfono 2</label>
                                    <input class="form-control input-xs" type="text" name="telefono2" id="telefono2"
                                        placeholder="Teléfono 2">
                                </div>
                            </div>
                        </div>
            
                        <div class="row" style="margin: 10px; display: flex; justify-content: center;">
                            <div class="col-sm-12 campoVoluntario">
                                <div class="mensaje-form-enviado" id="mensaje-form-enviado">
                                    <div class="res-form" id="res-form">
                                        <!--<div class="icon-rform rf-icheck">
                                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                                        </div>
                                        <p class="msg-rform">Se pudo enviar el formulario con exito</p>-->
                                        <!--<div class="icon-rform rf-ierr">
                                            <i class="fa fa-times-circle" aria-hidden="true"></i>
                                        </div>
                                        <p class="msg-rform">No se pudo enviar el formulario</p>-->
                                        <div class="icon-rform rf-iload">
                                            <i class="fa fa-circle-o-notch" aria-hidden="true"></i>
                                        </div>
                                        <p class="msg-rform">Enviando Formulario...</p>
                                    </div>
                                </div>
                                <div class="contenedor-cf" id="contenedor-cf">
                                    <div class="cargar-formulario">
                                        <div>
                                            <div class="icono-cargar"><i class="fa fa-circle-o-notch" aria-hidden="true"></i></div>
                                        </div>
                                        <div class="p-cargar">Cargando Formulario...</div>
                                    </div>
                                </div>
                                <div class="form-contenedor" id="form-contenedor">
            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item" id="headingTwo">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold" id="botonInfoAdd" style="background-color:#f3e5ab; color:#000" type="button">
                        INFORMACION ADICIONAL
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse">
                    <div id="mostrarOrgSocialAdd" class="accordion-body" style="background-color:#fffacd;">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="flex-grow-1 mx-2">
                                <b>Tipo de Entrega</b>
                                <select class="form-select form-select-sm" name="select_88" id="select_88"
                                    style="width: 100%;"></select>
                            </div>
                            <div class="flex-grow-1 mx-2">
                                <img src="../../img/png/calendario2.png" width="60" height="60" id="btnMostrarModal"
                                    title="CALENDARIO ASIGNACION">
                            </div>
                            <div class="flex-grow-1 mx-2">
                                <b>Día de Entrega</b>
                                <select class="form-select form-select-sm" name="diaEntregac" id="diaEntregac"
                                    style="width: 100%;"></select>
                            </div>
                            <div class="flex-grow-1 mx-2">
                                <img src="../../img/png/reloj.png" width="55" height="55">
                            </div>
                            <div class="flex-grow-1 flex-fill mx-2">
                                <b>Hora de Entrega</b>
                                
                                <input type="time" name="horaEntregac" id="horaEntregac" class="form-control form-control-sm">
                            </div>
                            <div class="flex-grow-1 mx-2">
                                <b>Frecuencia</b>
                                
                                <select class="form-select form-select-sm" name="select_86" id="select_86"
                                    style="width: 100%;"></select>
                            </div>
                            <div id="comentariodiv" class="flex-grow-1 mx-2"
                                style="display: none;">
                                <b>Comentario (máximo 85 caracteres)</b>
                                
                                <textarea class="form-control form-control-sm" id="comentario" rows="2" style="resize: none"
                                    maxlength="85"></textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="flex-grow-1 mx-2">
                                <img src="../../img/png/grupoEdad.png" width="60" height="60" id="btnMostrarGrupo"
                                    title="TIPO DE POBLACIÓN">
                            </div>
                            <div class="flex-grow-1 mx-2">
                                <b>Total de Personas Atendidas</b>
                                
                                <input type="number" name="totalPersonas" id="totalPersonas"
                                    class="form-control form-control-sm" min="0" max="100" readonly>
                            </div>

                            <div class="flex-grow-1 mx-2">
                                <b>Acción Social</b>
                                <select class="form-select form-select-sm" name="select_92" id="select_92"
                                    style="width: 100%;"></select>
                            </div>
                            <div class="flex-grow-1 mx-2">
                                <b>Vulnerabilidad</b>
                                <select class="form-select form-select-sm" name="select_90" id="select_90"
                                    style="width: 100%;"></select>
                            </div>
                            <div class="flex-grow-1 mx-2">
                                <b>Tipo de Atención</b>
                                <select class="form-select form-select-sm" name="select_89" id="select_89"
                                    style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-1 mx-2">
                                <div class="d-flex justify-content-center">
                                    <a href="#" id="descargarArchivo">
                                        <img src="../../img/png/adjuntar-archivo.png" width="60" height="60"
                                            title="DESCARGAR ARCHIVO">
                                    </a>
                                </div>
                                <div class="row">
                                    <label for="archivoAdd"><b>Archivos Adjuntos</b></label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="form-floating">
                                        <textarea placeholder="" class="form-control form-control-sm h-100" id="infoNut" rows="4"
                                            style="resize: none"></textarea>
                                        <label for="infoNut" style="display: block;">Información Nutricional</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="mostrarFamiliasAdd" class="accordion-body pt-1 pb-4"  style="display: none;background-color:#fffacd;">
                        <div class="d-flex m-2 justify-content-center align-items-center">
                            <div class="col-sm-6 col-md-2 me-2 d-flex flex-column justify-content-center align-items-center text-center p-2" id="iconEstructuraFam">
                                <div class="row">
                                    <img src="../../img/png/estructura_familiar.png" width="80" height="80"
                                        title="ESTRUCTURA FAMILIAR" class="icon">
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for=""><b>Estructura familiar</b></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-2 me-2 d-flex flex-column justify-content-center align-items-center text-center p-2" id="iconVulnerabilidadFam">
                                <div class="row">
                                    <img src="../../img/png/vulnerabilidades.png" width="80" height="80"
                                        title="VULNERABILIDADES" class="icon">
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for=""><b>Vulnerabilidades</b></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-2 me-2 d-flex flex-column justify-content-center align-items-center text-center p-2" id="iconSituacionFam">
                                <div class="row">
                                    <img src="../../img/png/situacion_economica.png" width="80" height="80"
                                        title="SITUACIÓN ECONÓMICA" class="icon">
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for=""><b>Situación Económica</b></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-2 me-2 d-flex flex-column justify-content-center align-items-center text-center p-2" id="iconViviendaServicios">
                                <div class="row">
                                    <img src="../../img/png/vivienda_servicios.png" width="80" height="80"
                                        title="VIVIENDA Y SERVICIOS BÁSICOS" class="icon">
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for=""><b>Vivienda y Servicios Básicos</b></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-2 me-2 d-flex flex-column justify-content-center align-items-center text-center p-2" id="iconEvaluacionFam">
                                <div class="row">
                                    <img src="../../img/png/evaluacion.png" width="80" height="80" title="EVALUACIÓN"
                                        class="icon">
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label for=""><b>Evaluación</b></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="mostrarVoluntariosAdd" class="accordion-body"  style="background-color:#fffacd;">

                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <div class="modal" id="mycalendar">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background-color: white; ">
                <div class="modal-header">
                    <h4 class="modal-title">CALENDARIO DE ASIGNACION</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="calendar" style="overflow-y: auto; max-height: 400px;"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnGuardarCale">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalBtnDir" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ingresar Dirección</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto;">
                    <div class="form-group row">
                        <label for="Provincia" class="col-sm-3 col-form-label">Provincia</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm" id="select_prov" onchange="ciudad(this.value)">
                                <option value="">Seleccione provincia</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Ciudad" class="col-sm-3 col-form-label">Ciudad</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm" id="select_ciud">
                                <option value="">Seleccione ciudad</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Canton" class="col-sm-3 col-form-label">Cantón</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="Canton" id="Canton"
                                placeholder="Ingrese un cantón">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Parroquia" class="col-sm-3 col-form-label">Parroquia</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="Parroquia" id="Parroquia"
                                placeholder="Ingrese una parroquia">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Barrio" class="col-sm-3 col-form-label">Barrio</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="Barrio" id="Barrio"
                                placeholder="Ingrese un barrio">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="CalleP" class="col-sm-3 col-form-label">Calle principal</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="CalleP" id="CalleP"
                                placeholder="Ingrese calle principal">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="CalleS" class="col-sm-3 col-form-label">Calle secundaria</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="CalleS" id="CalleS"
                                placeholder="Ingrese calle secundaria">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Referencia" class="col-sm-3 col-form-label">Referencia</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="Referencia" id="Referencia"
                                placeholder="Ingrese una referencia">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnGuardarDir">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalBtnGrupo" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tipo de población</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px;">
                    <div class="table-responsive">
                        <table class="table" id="tablaPoblacion">
                            <thead>
                                <tr>
                                    <th scope="col" colspan="2">Tipo de Población</th>
                                    <th scope="col">Hombres</th>
                                    <th scope="col">Mujeres</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnGuardarGrupo">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalDescarga" data-backdrop="static" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Gestionar Archivos</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="margin:10px">
                    <div class="row-sm-12">
                        <div id="cargarArchivo" class="form-group" style="display: flex;">
                            <label for="archivoAdd">Adjuntar Archivos: (máximo 3 archivos) </label>
                            <input type="file" style="margin-left: 10px" class="form-control-file" id="archivoAdd"
                                multiple onchange="checkFiles(this)">
                        </div>
                    </div>
                    <div class="row-sm-12" style="width: 100%; margin-right:10px; margin-left:10px;">
                        <div class="form-group" style="display: flex; justify-content: center;">
                            <div id="modalDescContainer" class="d-flex justify-content-center flex-wrap">
                            </div>
                        </div>
                    </div>
                    <div class="row-sm-12">
                        <div class="col" id="divNoFile" style="display:flex">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display:none">
                    <div class="row" style="margin: 10px;">
                        <div class="col-xs-4">

                        </div>
                        <div class="col-xs-4">
                            <button id="btnDescargar" type="button" class="btn btn-default btn-block"
                                onclick="descargarArchivo(ruta, nombre)">
                                <span class="glyphicon glyphicon-download" aria-hidden="true"></span> Descargar
                            </button>
                        </div>
                        <div class="col-xs-4">
                            <button type="button" class="btn btn-danger btn-block"
                                onclick="eliminarArchivo(ruta, nombre)">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalsBtnpAliado" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Productor</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin: 10px; display: flex;">
                        <div id="" style="display: flex; flex-wrap: wrap; overflow-y: auto; max-height: 200px;">
                            <div class="col-md-6 col-sm-6">
                                <button type="button" class="btn btn-default btn-sm">
                                    <img src="../../img/png/industrial.png" style="width: 90%; height: 90%;"
                                        alt="Imagen">
                                </button>
                                <b>Industrial</b>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <button type="button" class="btn btn-default btn-sm">
                                    <img src="../../img/png/animales.png" style="width: 90%; height: 90%;" alt="Imagen">
                                </button>
                                <b>Artesanal</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalsBtn87" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Estado</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin: 10px; display: flex;">
                        <div id="modal_87" style="display: flex; flex-wrap: wrap; overflow-y: auto; max-height: 200px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalsBtn93" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Beneficiario</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin: 10px; display: flex;">
                        <div id="modal_93" style="display: flex; flex-wrap: wrap; overflow-y: auto; max-height: 200px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalsBtnDon" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Donacion</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin: 10px; display: flex;">
                        <div id="modal_Don"
                            style="display: flex; flex-wrap: wrap; overflow-y: auto; max-height: 200px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalEstructuraFam" class="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Estructura familiar</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px;">
                    <div style="margin: 10px; overflow-x: auto;">
                        <table class="table campos-d" id="tablaIntegrantes">
                            <thead>
                                <tr>
                                    <th>Nombres y Apellidos</th>
                                    <th>Género</th>
                                    <th>Parentesco</th>
                                    <th>Rango de edad</th>
                                    <th>Ocupación</th>
                                    <th>Estado Civil</th>
                                    <th>Nivel de Escolaridad</th>
                                    <th>Nombre de la Institución</th>
                                    <th>Tipo de Institución</th>
                                    <th>Vulnerabilidad</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="filaAgregar">
                                    <td><input type="text" class="form-control imput-xs" id="nuevoNombre"></td>
                                    <td>
                                        <select class="form-control imput-xs" id="nuevoGenero">
                                            <option value="" selected disabled>Seleccione</option>
                                            <option value="masculino">Masculino</option>
                                            <option value="femenino">Femenino</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control imput-xs" id="nuevoParentesco"></td>
                                    <td>
                                        <select class="form-control imput-xs" id="nuevoRangoEdad">
                                            <option value="" selected disabled>Seleccione</option>
                                            <option value="0-5">0-5 años</option>
                                            <option value="6-12">6-12 años</option>
                                            <option value="13-18">13-18 años</option>
                                            <option value="19-64">19-64 años</option>
                                            <option value="65+">65 años o más</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control imput-xs" id="nuevaOcupacion"></td>
                                    <td>
                                        <select class="form-control imput-xs" id="nuevoEstadoCivil">
                                            <option value="" selected disabled>Seleccione</option>
                                            <option value="soltero">Soltero/a</option>
                                            <option value="casado">Casado/a</option>
                                            <option value="divorciado">Divorciado/a</option>
                                            <option value="viudo">Viudo/a</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control imput-xs" id="nuevoNivelEscolaridad" onchange="validarNinguno(this, 'nuevoNombreInstitucion', 'nuevoTipoInstitucion')">
                                            <option value="" selected disabled>Seleccione</option>
                                            <option value="ninguno">Ninguna</option>
                                            <option value="primaria">Primaria</option>
                                            <option value="secundaria">Secundaria</option>
                                            <option value="bachillerato">Bachillerato</option>
                                            <option value="tecnico">Técnico</option>
                                            <option value="universidad">Universidad</option>
                                            <option value="posgrado">Posgrado</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control imput-xs" id="nuevoNombreInstitucion" disabled>
                                    </td>
                                    <td>
                                        <select class="form-control imput-xs" id="nuevoTipoInstitucion" disabled>
                                            <option value="" selected disabled>Seleccione</option>
                                            <option value="fiscal">Fiscal</option>
                                            <option value="fiscomisional">Fiscomisional</option>
                                            <option value="particular">Particular</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control imput-xs" id="nuevaVulnerabilidad">
                                            <option value="" selected disabled>Seleccione</option>
                                            <option value="discapacidad">Discapacidad</option>
                                            <option value="enfermedad">Enfermedad</option>
                                            <option value="ninguna">Ninguna</option>
                                        </select>
                                    </td>
                                    <td><button type="button" class="btn btn-primary"
                                            id="agregarIntegrante">Agregar</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnAceptarIntegrante">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalVulnerabilidadFam" class="modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Vulnerabilidades</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px; margin:5px">
                    <p>Integrantes discapacitados</p>
                    <div style="overflow-x: auto;">
                        <table class="table" id="tablaFamDisc">
                            <thead>
                                <tr>
                                    <th>Nombre persona</th>
                                    <th>Nombre de la discapacidad</th>
                                    <th>Tipo de discapacidad</th>
                                    <th>% discapacidad</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div id="mensajeNoIntegrantes" class="alert alert-info" style="display: none;">
                        No hay integrantes discapacitados en la familia.
                    </div>

                    <p>Integrantes con Enfermedad</p>
                    <div style="overflow-x: auto; margin-top: 10px">
                        <table class="table" id="tablaFamEnfe">
                            <thead>
                                <tr>
                                    <th>Nombre persona</th>
                                    <th>Nombre de la enfermedad</th>
                                    <th>Tipo de enfermedad</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div id="mensajeNoIntegrantesE" class="alert alert-info" style="display: none;">
                        No hay integrantes enfermos en la familia.
                    </div>

                    <div class="row" style=" margin:10px">
                        <div class="col-6">
                            <label for="totalFamVuln">Total de integrantes vulnerables:</label>
                            <input class="form-control imput-xs" id="totalFamVuln" readonly></input>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnAceptarVulnerable">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalSituacionFam" class="modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Situación Económica</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin: 10px;">
                        <div id="modal_" style="display: flex; flex-wrap: wrap; overflow-y: auto; max-height: 200px;">
                            <div class="col-md-6 col-sm-6 d-flex justify-content-center">
                                <button id="btnIngresos" type="button" class="btn btn-default btn-sm">
                                    <img src="../../img/png/ingresos.png" style="width: 60px; height: 60px">
                                </button>
                                <br>
                                <b>Ingresos</b>
                            </div>
                            <div class="col-md-6 col-sm-6 d-flex justify-content-center">
                                <button id="btnEgresos" type="button" class="btn btn-default btn-sm">
                                    <img src="../../img/png/egresos.png" style="width: 60px; height: 60px">
                                </button>
                                <br>
                                <b>Egresos</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalIngresosFam" class="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Situación Económica (Ingresos)</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px;">
                    <div style="margin: 10px; overflow-x: auto;">
                        <table class="table campos-d" id="tablaSituacion">
                            <thead>
                                <tr>
                                    <th>Nombres y Apellidos</th>
                                    <th>Lugar de trabajo</th>
                                    <th>Tipo de seguro</th>
                                    <th>Sueldo fijo</th>
                                    <th>Ingreso fijo $</th>
                                    <th>Ingreso eventual $</th>
                                    <th>Pensión de alimentos $</th>
                                    <th>Ayuda familiar $</th>
                                    <th>Jubilación $</th>
                                    <th>Tipo de Bono</th>
                                    <th>Bono $</th>
                                    <th>Uso del Bono</th>
                                    <th>Suma de Ingresos $</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="filaAgregar">
                                    <td><input type="text" class="form-control imput-xs" id="nombreSituacion"></td>
                                    <td><input type="text" class="form-control imput-xs" id="lugarTrabajo"></td>
                                    <td>
                                        <select class="form-control imput-xs" id="tipoSeguro">
                                            <option value="" selected disabled>Seleccione</option>
                                            <option value="iess">IEES</option>
                                            <option value="issfa">ISSFA</option>
                                            <option value="ispol">ISPOL</option>
                                            <option value="seguro">Seguro privado</option>
                                            <option value="ninguno">Ninguno</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control imput-xs" id="sueldoFijo" onchange="validarNinguno(this,'ingresoFijo')">
                                            <option value="" selected disabled>Seleccione</option>
                                            <option value="si">Si</option>
                                            <option value="no">No</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control imput-xs" id="ingresoFijo" onchange="sumarCamposIngreso(this)" disabled></td>
                                    <td><input type="text" class="form-control imput-xs" id="ingresoEventual" onchange="sumarCamposIngreso(this)"></td>
                                    <td><input type="text" class="form-control imput-xs" id="pensionAlimentos" onchange="sumarCamposIngreso(this)"></td>
                                    <td><input type="text" class="form-control imput-xs" id="ayudaFamiliar" onchange="sumarCamposIngreso(this)"></td>
                                    <td><input type="text" class="form-control imput-xs" id="jubilacion" onchange="sumarCamposIngreso(this)"></td>
                                    <td>
                                        <select class="form-control imput-xs" id="tipoBono" onchange="validarNinguno(this,'bono','usoBono')">
                                            <option value="" selected disabled>Seleccione</option>
                                            <option value="desarrollo">Desarrollo Humano</option>
                                            <option value="manuela">Manuela Sáenz</option>
                                            <option value="joaquin">Joaquín Gallegos Lara</option>
                                            <option value="ninguno">Ninguno</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control imput-xs" id="bono" onchange="sumarCamposIngreso(this)" disabled></td>
                                    <td>
                                        <select class="form-control imput-xs" id="usoBono" disabled>
                                            <option value="" selected disabled>Seleccione</option>
                                            <option value="mediacion">Mediación e insumos y movilización</option>
                                            <option value="gastos">Gastos generales</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control imput-xs" id="sumaIngresos" readonly value="0.00"></td>
                                    <td><button type="button" class="btn btn-primary"
                                            id="agregarSituacion">Agregar</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row" style=" margin:10px">
                        <div class="col-6">
                            <label for="totalIngresos">Total ingresos:</label>
                            <input class="form-control imput-xs" id="totalIngresos" value="0.00" readonly></input>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnAceptarIngreso">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalEgresosFam" class="modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Situación Económica (Egresos)</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px;">
                    <div style="margin: 10px; overflow-x: auto;">
                        <table class="table" id="tablaSituacionE">
                            <thead>
                                <tr>
                                    <th>Tipo de vivienda</th>
                                    <th>¿La vivienda es?</th>
                                    <th>Valor/Avalúo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td>
                                    <select class="form-control imput-xs" id="tipoVivienda">
                                        <option value="" selected disabled>Seleccione</option>
                                        <option value="casa">Casa</option>
                                        <option value="departamento">Departamento</option>
                                        <option value="mediaagua">Media Agua</option>
                                        <option value="cuarto">Cuarto</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control imput-xs" id="laViviendaEs">
                                        <option value="" disabled selected>Seleccione</option>
                                        <option value="propia">Propia</option>
                                        <option value="prestada">Prestada</option>
                                        <option value="arrendada">Arrendada</option>
                                        <option value="compartida">Compartida</option>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control imput-xs" id="valor" onchange="verificarDecimales(this)"></td>
                            </tbody>
                        </table>
                        <table class="table" id="tablaServicios">
                            <thead>
                                <tr>
                                    <th>¿Qué servicios posee?</th>
                                    <th>Dispone</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <table class="table" id="tablaOtrosGastos">
                            <thead>
                                <tr>
                                    <th>Otros Gastos</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="row" style=" margin:10px">
                        <div class="col-6">
                            <label for="totalEgresos">Total egresos:</label>
                            <input class="form-control imput-xs" id="totalEgresos" readonly></input>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnAceptarEgreso">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalViviendaFam" class="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Vivienda y Servicios Básicos</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px; margin:5px">
                    <div style="overflow-x: auto;">
                        <table class="table" id="tablaVivienda">
                            <thead>
                                <tr>
                                    <th>No. Pisos</th>
                                    <th>Tipo material</th>
                                    <th>Tipo techo</th>
                                    <th>Tipo piso</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td><input type="number" class="form-control imput-xs" id="nopisos" min="0"></td>
                                <td>
                                    <select class="form-control imput-xs" id="tipoMaterial">
                                        <option value="" selected disabled>Seleccione</option>
                                        <option value="bloque">Bloque</option>
                                        <option value="adobe">Adobe</option>
                                        <option value="caña">Caña</option>
                                        <option value="tabla">Tabla</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control imput-xs" id="tipoTecho">
                                        <option value="" selected disabled>Seleccione</option>
                                        <option value="losa">Losa</option>
                                        <option value="paja">Paja</option>
                                        <option value="zinc">Zinc</option>
                                        <option value="eternit">Eternit</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control imput-xs" id="tipoPiso">
                                        <option value="" selected disabled>Seleccione</option>
                                        <option value="tierra">Tierra</option>
                                        <option value="madera">Madera</option>
                                        <option value="cemento">Cemento</option>
                                        <option value="baldosa">Baldosa</option>
                                        <option value="vinil">Vinil</option>
                                    </select>
                                </td>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table" id="tablaTec">
                                <thead>
                                    <tr>
                                        <th>Tecnología</th>
                                        <th>Número</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table" id="tablaElec">
                                <thead>
                                    <tr>
                                        <th>Electrodomésticos</th>
                                        <th>Número</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table" id="tablaMueble">
                                <thead>
                                    <tr>
                                        <th>Muebles</th>
                                        <th>Número</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table" id="tablaAmbiente">
                                <thead>
                                    <tr>
                                        <th>Ambientes</th>
                                        <th>Número</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnAceptarViviendaServ">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalEvaluacionFam" class="modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Evaluación</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px; margin:5px">
                    <div style="overflow-x: auto;">
                        <table class="table table-xs" id="tablaEvaluacion">
                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Ingresos</td>
                                    <td><input type="number" class="form-control imput-xs" id="ingresos" readonly></td>
                                </tr>
                                <tr>
                                    <td>Egresos</td>
                                    <td><input type="number" class="form-control imput-xs" id="egresos" readonly></td>
                                </tr>
                                <tr>
                                    <td>Disponible</td>
                                    <td><input type="number" class="form-control imput-xs" id="disponible" readonly>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table" id="tabla evaluacion completa">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Descripción</th>
                                    <th>Valor Numérico</th>
                                    <th>Valor Textual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Edad</td>
                                    <td><input type="number" class="form-control" id="edadDato" readonly></td>
                                    <td><input type="number" class="form-control" id="edadEval" readonly></td>
                                    <td><input type="text" class="form-control" id="edadText" readonly></td>
                                </tr>
                                <tr>
                                    <td>Ingreso x habitante</td>
                                    <td><input type="number" class="form-control" id="ingresoHabitanteDato" readonly>
                                    </td>
                                    <td><input type="number" class="form-control" id="ingresoHabitante" readonly></td>
                                    <td><input type="text" class="form-control" id="ingresoHabitanteText" readonly></td>
                                </tr>
                                <tr>
                                    <td>Discapacidad o Enfermedades</td>
                                    <td><input type="number" class="form-control" id="discapacidadDato" readonly></td>
                                    <td><input type="number" class="form-control" id="discapacidadEval" readonly></td>
                                    <td><input type="text" class="form-control" id="discapacidadText" readonly></td>
                                </tr>
                                <tr>
                                    <td>Número de hijos</td>
                                    <td><input type="number" class="form-control" id="numHijosDato" readonly></td>
                                    <td><input type="number" class="form-control" id="numHijosEval" readonly></td>
                                    <td><input type="text" class="form-control" id="numHijosText" readonly></td>
                                </tr>
                                <tr>
                                    <td>Vivienda</td>
                                    <td><input type="text" class="form-control" id="viviendaDato" readonly></td>
                                    <td><input type="number" class="form-control" id="vivienda" readonly></td>
                                    <td><input type="text" class="form-control" id="viviendaText" readonly></td>
                                </tr>
                                <tr>
                                    <td>Estado Civil</td>
                                    <td><input type="text" class="form-control" id="madrePadreSolteroDato" readonly>
                                    </td>
                                    <td><input type="number" class="form-control" id="madrePadreSoltero" readonly></td>
                                    <td><input type="text" class="form-control" id="madrePadreSolteroText" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Trabajo Usuario</td>
                                    <td><input type="text" class="form-control" id="trabajoUsuarioDato" readonly></td>
                                    <td><input type="number" class="form-control" id="trabajoUsuario" readonly></td>
                                    <td><input type="text" class="form-control" id="trabajoUsuarioText" readonly></td>
                                </tr>
                                <tr>
                                    <td>Trabajo Cónyuge</td>
                                    <td><input type="text" class="form-control" id="trabajoConyugeDato" readonly></td>
                                    <td><input type="number" class="form-control" id="trabajoConyuge" readonly></td>
                                    <td><input type="text" class="form-control" id="trabajoConyugeText" readonly></td>
                                </tr>
                                <tr>
                                    <td>Uso del Bono de discapacidad</td>
                                    <td><input type="number" class="form-control" id="usoBonoDiscapacidadDato" readonly>
                                    </td>
                                    <td><input type="number" class="form-control" id="usoBonoDiscapacidad" readonly>
                                    </td>
                                    <td><input type="text" class="form-control" id="usoBonoDiscapacidadText" readonly>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-6 col-sm-3">
                                <label for="totalAplica">TOTAL:</label>
                                <input class="form-control imput-xs" id="totalAplica" readonly></input>
                            </div>
                            <div class="col-6 col-sm-8">
                                <label for="totalAplicaVC">VALOR CONTEXTUAL:</label>
                                <input class="form-control imput-xs" id="totalAplicaVC" readonly></input>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnAceptarEvaluacion">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalInfoUserFam" class="modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Información del Usuario</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px;">
                    <div class="campoFamilia" style="margin-right: 10px;">
                        <div class="row form-group form-group-xs">
                            <div class="col-sm-6">
                                <div>
                                    <label for="trabajaSelect">¿Trabaja?</label>
                                    <div class="d-flex">
                                        <select class="form-control input-xs" id="trabajaSelect">
                                            <option value="0" selected>Sí</option>
                                            <option value="1">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="trabajaAct" style="display: none;">
                                    <label for="comentarioAct">Actividad:</label>
                                    <textarea class="form-control input-xs" id="comentarioAct" rows="2"
                                        style="resize: none"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="trabajaAct">
                                    <label for="modalidadSelect">Modalidad</label>
                                    <select class="form-control input-xs" id="modalidadSelect">
                                        <option value="" selected disabled>Seleccione una opción</option>
                                        <option value="0">Dependiente</option>
                                        <option value="1">Independiente</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group form-group-xs">
                            <div class="col-sm-6">
                                <div>
                                    <label for="conyugeSelect">¿Cónyuge trabaja?</label>
                                    <div class="d-flex">
                                        <select class="form-control input-xs" id="conyugeSelect">
                                            <option value="0" selected>Sí</option>
                                            <option value="1">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="conyugeAct" style="display: none;">
                                    <label for="comentarioConyugeAct">Actividad:</label>
                                    <textarea class="form-control input-xs" id="comentarioConyugeAct" rows="2"
                                        style="resize: none"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="conyugeAct">
                                    <label for="modalidadConyugeSelect">Modalidad</label>
                                    <select class="form-control input-xs" id="modalidadConyugeSelect">
                                        <option value="" disabled selected>Seleccione una opción</option>
                                        <option value="0">Dependiente</option>
                                        <option value="1">Independiente</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group form-group-xs">
                            <div>
                                <div class="col-sm-6">
                                    <label for="numHijosI">Número de hijos</label>
                                    <input class="form-control input-xs" type="number" id="numHijosI" name="numHijosI"
                                        min="0">
                                </div>
                                <div class="col-sm-6 hijosAct" style="display: none;">
                                    <div>
                                        <label for="numHijosMayores">Mayores de edad</label>
                                        <input class="form-control input-xs" type="number" id="numHijosMayores"
                                            name="numHijosMayores" min="0" value="">
                                    </div>
                                    <div>
                                        <label for="numHijosMenores">Menores de edad</label>
                                        <input class="form-control input-xs" type="number" id="numHijosMenores"
                                            name="numHijosMenores" min="0" value="">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="col-sm-6">
                                    <label for="numPersonas">Número de personas que viven en la casa</label>
                                    <input class="form-control input-xs" type="number" id="numPersonas"
                                        name="numPersonas" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnAceptarUser">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>

        </div>
    </div>
    </div>

    <div id="modalsBtn85" class="modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Programa</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin: 10px;">
                        <div id="modal_85" style="display: flex; flex-wrap: wrap; overflow-y: auto; max-height: 200px;">
                            <!--<div class="col-md-6 col-sm-6 d-flex justify-content-center">
                                <button id="btnFamilias" type="button" class="btn btn-default btn-sm" onclick="cambiarSelectPrograma(this)">
                                    <img src="../../img/png/familias2.png" style="width: 60px; height: 60px">
                                </button>
                                <br>
                                <b>Familias</b>
                            </div>
                            <div class="col-md-6 col-sm-6 d-flex justify-content-center">
                                <button id="btn70Piquito" type="button" class="btn btn-default btn-sm" onclick="cambiarSelectPrograma(this)">
                                    <img src="../../img/png/70piquito.png" style="width: 60px; height: 60px">
                                </button>
                                <br>
                                <b>70 y piquito</b>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modalVistaArchivo" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modVATitulo"></h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="height:70vh;margin:5px">
                    <iframe id="modVAFrame" src="" frameborder="0" style="height:100%; width:100%;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</body>

<script src="../../dist/js/registro_beneficiario.js"></script>