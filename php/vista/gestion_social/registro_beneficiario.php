
<!--
    AUTOR DE RUTINA	: Dallyana Vanegas
    MODIFICADO POR : Javier farinango
    FECHA CREACION : 16/02/2024
    FECHA MODIFICACION : 10/02/2025
    DESCIPCION : Interfaz de modulo Gestion Social/Registro Beneficiario
 -->
<!-- <link rel="stylesheet" href="../../dist/css/registro_beneficiario.css"> -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/es.global.min.js'></script>
   

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
    <div class="col-sm-6">
        <div class="btn-group" id="btnsContainers">
            <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                            print_r($ruta[0] . '#'); ?>" title="Salir" class="btn btn-outline-secondary">
                <img src="../../img/png/salire.png" alt="Salir">
            </a>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Guardar" id="btnGuardarAsignacionn" onclick="guardar_registros()">
                <img src="../../img/png/grabar.png" alt="Guardar">
            </button>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Autorizar" id="btnAutorizarCambios">
                <img src="../../img/png/admin.png" width="32" height="32" alt="Autorizar">
            </button>
        </div>
    </div>
</div>

<form id="form_data" method="post" enctype="multipart/form-data">
<div class="accordion" id="accordionExample">
    <div class="accordion-item mb-2" id="headingOne">
        <h2 class="accordion-header">
            <button class="accordion-button fw-bold" style="background-color:#f3e5ab; color:#000" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                INFORMACION GENERAL
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show">
            <div class="accordion-body" style="background-color:#fffacd;">
                <input type="hidden" name="txt_id" id="txt_id">
                <div class="row">
                    <div class="col-lg-3">
                        <b>CI / RUC </b>
                        <div class="input-group">
                            <button type="button" class="btn btn-success btn-sm p-0" id="btn_nuevo_cli" onclick="validarRucYValidarSriC()" title="Nuevo cliente">
                                <img src="../../img/png/SRIlogo.png" style="width: 45px;"  id="validarSRI" title="VALIDAR RUC">
                            </button>
                            <input type="text" name="txt_ci" id="txt_ci" class="form-control form-control-sm" onblur="validar_registro()">

                            <input type="text" name="txt_td" id="txt_td" class="" style="color:red; width:35px" readonly>
                            <input type="hidden" name="txt_codigo" id="txt_codigo" class="form-control form-control-sm">

                            
                        </div>
                    </div>
                     <div class="col-lg-5">
                        <b>Nombre del Beneficiario/Usuario </b>
                        <div class="input-group">
                            <input type="text" name="cliente" id="cliente" class="form-control form-control-sm">
                            <!-- <button class="btn-sm btn btn-primary"><i class="bx bx-search"></i></button> -->
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-3">
                                 <img id="img_Tipo_Beneficiario" src="../../img/png/previnculacion.png"alt="user avatar" width="55" height="55">                                
                            </div>
                            <div class="col-sm-9">
                                <label for="select_93"><b>Tipo de Beneficiario</b></label>
                                <select class="form-select" name="select_93" id="select_93"></select>                                
                            </div>                            
                        </div>
                    </div>
                    <div class="col-lg-3">
                         <div class="row">
                            <div class="col-lg-3">
                                  <img id="img_Tipo_Donacion" src="../../img/png/ndo.png"alt="user avatar" width="55" height="55">                      
                            </div>
                            <div class="col-sm-9">
                                <label for="select_CxC"><b>Tipo de Donación</b></label>
                                <br>
                                <h3  name="select_CxClabel" id="select_CxClabel"></h3>
                                <input type="hidden" id="select_CxC" name="select_CxC" value="">
                                <!-- <select class="form-select" name="select_CxC" id="select_CxC" ></select> -->
                            </div>                            
                        </div>
                    </div>  
                    <div class="col-lg-3">
                        <div class="row">
                            <div class="col-lg-3">
                                  <img id="img_estado_beneficiario" src="../../img/png/previnculacion.png"alt="user avatar" width="55" height="55">                      
                            </div>
                            <div class="col-lg-9">
                                <label for="select_87"><b>Estado</b></label>
                                <select class="form-select form-select-sm" name="select_87" id="select_87"></select>                                
                            </div>                            
                        </div> 
                    </div>
                     <div class="col-lg-2">
                        <label for="fechaIngreso"><b>Fecha de ingreso</b></label>
                        <input type="date" id="fechaIngreso" class="form-control form-control-sm" readonly>                       
                    </div>
                </div> 
                <div class="row campoSocial">
                    <div class="col-lg-4">
                        <label for="nombreRepre" style="display: block;"><b>Nombre Representante Legal</b></label>
                        <input class="form-control form-control-sm" type="text" name="nombreRepre" id="nombreRepre" placeholder="Nombre Representante">                        
                    </div>
                    <div class="col-lg-4">
                        <label for="ciRepre" style="display: block;"><b>CI Representante Legal</b></label>
                        <input class="form-control form-control-sm" type="text" name="ciRepre" id="ciRepre" placeholder="CI Representante">                        
                    </div>
                    <div class="col-lg-4">
                        <label for="telfRepre"><b>Teléfono Representante Legal</b></label>
                        <input class="form-control form-control-sm" type="text" name="telfRepre" id="telfRepre" placeholder="Representante legal">                        
                    </div>
                    <div class="col-lg-3">
                        <label for="contacto"><b>Contacto/Encargado</b></label>
                        <input class="form-control form-control-sm" type="text" name="contacto" id="contacto" placeholder="Contacto">                        
                    </div>
                    <div class="col-lg-3">
                        <label for="cargo"><b>Cargo</b></label>
                        <input class="form-control form-control-sm" type="text" name="cargo" id="cargo" placeholder="Profesión">                        
                    </div>
                    <div class="col-lg-3">
                        <div class="row">
                            <div class="col-3">
                                <img src="../../img/png/calendario2.png" width="60" height="60">
                            </div>
                            <div class="col-9">
                                 <label for="diaEntrega"><b>Día Entrega visita</b></label>
                                <select class="form-select form-select-sm" name="diaEntrega" id="diaEntrega"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="row">
                            <div class="col-3">
                                <img src="../../img/png/reloj.png" width="55" height="55">                                
                            </div>         
                            <div class="col-9">
                                <label for="horaEntrega"><b>Hora de visita</b></label>
                                <input type="time" name="horaEntrega" id="horaEntrega" class="form-control form-control-sm">                                
                            </div>                   
                        </div>
                    </div>
                </div>
                <div class="row">
                     <div class="col-lg-9 campoFamilia">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-3"  id="btnPrograma" style="cursor:pointer">
                                        <img src="../../img/png/programa.png" width="60" height="60" title="TIPO DE PROGRAMA" class="icon">
                                    </div>
                                    <div class="col-9">
                                        <label for="select_85" style="display: block;"><b>Programa</b></label>
                                        <select class="form-select form-select-sm" name="select_85" id="select_85" onchange="grupos()"></select>
                                    </div>                            
                                </div> 
                            </div> 
                            <div class="col-lg-3">
                                <label for="grupo"><b>Grupo</b></label>
                                <select class="form-select form-select-sm" name="grupo" id="grupo"></select>
                            </div>
                            <div class="col-lg-4">
                                <label for="estadoCivil"><b>Estado civil</b></label>
                                <select class="form-select form-select-sm" name="estadoCivil" id="estadoCivil">
                                        <option value='' disabled selected>Seleccione</option>
                                    </select>                                
                            </div>                            
                        </div>                                         
                        <div class="row">
                            <div class="col-6">
                                <label for="nombres"><b>Nombres</b></label>
                                <input class="form-control form-control-sm" type="text" name="nombres" id="nombres" placeholder="Nombres">                                
                            </div>
                            <div class="col-6">
                                <label for="edad"><b>Edad</b></label>
                                <input class="form-control form-control-sm" type="number" name="edad" id="edad" placeholder="Edad">                                
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label for="apellidos"><b>Apellidos</b></label>
                                <input class="form-control form-control-sm" type="text" name="apellidos" id="apellidos"
                                        placeholder="Apellidos">                                
                            </div>  
                             <div class="col-6">
                                <label for="ocupacion"><b>Ocupación</b></label>
                                <input class="form-control form-control-sm" type="text" name="ocupacion" id="ocupacion"placeholder="Ocupación">
                                
                            </div>                           
                        </div>
                         <div class="row">
                            <div class="col-6">
                                <label for="cedula"><b>Cédula de identidad</b></label>
                                <input class="form-control form-control-sm" type="text" name="cedula" id="cedula"       placeholder="Cédula de identidad">                                
                            </div>  
                             <div class="col-6">
                                <label for="telefonoFam"><b>Teléfono</b></label>
                                <input class="form-control form-control-sm" type="text" name="telefonoFam" id="telefonoFam" placeholder="Teléfono">
                            </div>                           
                        </div>
                         <div class="row">
                            <div class="col-6">
                                <label for="nivelEscolar"><b>Nivel escolar</b></label>
                                <select class="form-select form-select-sm" id="nivelEscolar" name="nivelEscolar">
                                    <option value="">Seleccione</option>
                                </select>

                               <!--  <input class="form-control form-control-sm" type="text" name="nivelEscolar"
                                        id="nivelEscolar" placeholder="Nivel de escolaridad"> -->
                                
                            </div>  
                             <div class="col-6">
                                <label for="pregunta" style="display: block;"><b>¿Cómo se enteró del BAQ</b>?</label>
                                <input class="form-control form-control-sm" type="text" name="pregunta" id="pregunta"
                                        placeholder="¿Cómo se enteró del BAQ?">                                
                            </div>                           
                        </div>         
                    </div>
                     <div class="col-lg-3 text-center">
                        <div class="row ">
                            <div class="col-lg-12 mb-3" id="btnMostrarDir" style="cursor:pointer">
                                <img src="../../img/png/map.png" width="60" height="60" title="INGRESAR DIRECCIÓN"
                                        class="icon">   
                                <br>
                                <label><b>Ingresar Dirección</b></label>                             
                            </div>
                            <div class="col-lg-12 mb-3 campoFamilia" id="btnInfoUser" style="cursor:pointer">
                                <img src="../../img/png/infoUser.png" width="60" height="60" title="INFORMACIÓN DEL USUARIO" class="icon">
                                <br>      
                                <label><b>Información del usuario</b></label>                          
                            </div>
                            
                        </div>
                    </div>           
                   
                   
                    <div class="col-lg-6 campoSocial">
                        <div class="row">
                            <div class="col-6">
                                <label for="email"><b>Email</b></label>
                                <input class="form-control form-control-sm" type="text" name="email" id="email" placeholder="Email">                                
                            </div>
                            <div class="col-6">
                                <label for="telefono"><b>Teléfono 1</b></label>
                                <input class="form-control form-control-sm" type="text" name="telefono" id="telefono"
                                    placeholder="Teléfono ">
                            </div>
                            <div class="col-6">
                                <label for="email2"><b>Email 2</b></label>
                                <input class="form-control form-control-sm" type="text" name="email2" id="email2"
                                    placeholder="Email2">
                            </div>                            
                            <div class="col-6">
                                <label for="telefono2"><b>Teléfono 2</b></label>
                                <input class="form-control form-control-sm" type="text" name="telefono2" id="telefono2"
                                    placeholder="Teléfono 2">
                            </div> 
                        </div>
                    </div>
                    
                </div>
                <div class="row" style="margin: 10px; display: flex; justify-content: center;">
                    <div class="col-sm-12 campoVoluntario">
                        
                        <div class="form-contenedor" id="form-contenedor">
    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item mb-3" id="headingTwo">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-bold" id="botonInfoAdd" style="background-color:#f3e5ab; color:#000" type="button">
                INFORMACION ADICIONAL
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse">
            <div id="mostrarOrgSocialAdd" class="accordion-body" style="background-color:#fffacd;">
                <div class="row">
                    <div class="col-lg-3">
                        <b>Tipo de Entrega</b>
                        <select class="form-select form-select-sm" name="select_88" id="select_88"></select>
                    </div>
                    <div class="col-lg-3">
                        <div class="row">
                            <div class="col-3">
                                <img src="../../img/png/calendario2.png" width="60" height="60" id="btnMostrarModal"
                                    title="CALENDARIO ASIGNACION">                                
                            </div>
                            <div class="col-9">
                                <b>Día Entrega en BAQ</b>
                                <select class="form-select form-select-sm" name="diaEntregac" id="diaEntregac"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="row">
                            <div class="col-3">                
                                <img src="../../img/png/reloj.png" width="55" height="55">
                            </div>
                            <div class="col-9">
                                <b>Hora Entrega en BAQ</b>
                                <input type="time" name="horaEntregac" id="horaEntregac" class="form-control form-control-sm">                               
                            </div>                            
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <b>Frecuencia</b>
                        <select class="form-select form-select-sm" name="select_86" id="select_86"></select>
                    </div>
                    <div class="col-lg-3" id="comentariodiv" style="display: none;">
                        <b>Comentario (máximo 85 caracteres)</b>
                        <textarea class="form-control form-control-sm" id="comentario" name="comentario" rows="2" style="resize: none"
                                    maxlength="85"></textarea>
                    </div>
                    <div class="col-lg-3">
                        <div class="row">
                            <div class="col-3">
                                <img src="../../img/png/grupoEdad.png" width="60" height="60" id="btnMostrarGrupo"
                                    title="TIPO DE POBLACIÓN">                                
                            </div>
                            <div class="col-9">
                                <b class="small">Total Personas Atendidas</b>
                                <input type="number" name="totalPersonas" id="totalPersonas" class="form-control form-control-sm" min="0" max="100" readonly>                                
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <b>Acción Social</b>
                        <select class="form-select form-select-sm" name="select_92" id="select_92"></select>                        
                    </div>
                    <div class="col-lg-3">
                        <b>Vulnerabilidad</b>
                        <select class="form-select form-select-sm" name="select_90" id="select_90"></select>                        
                    </div>
                    <div class="col-lg-3">
                        <b>Tipo de Atención</b>
                        <select class="form-select form-select-sm" name="select_89" id="select_89"></select>
                    </div>
                    <div class="col-lg-12 col-md-6 col-sm-12 text-end">
                        <div class="row">
                            <div class="col-3">
                                <img src="../../img/png/adjuntar-archivo.png" width="60" height="60" title="DESCARGAR ARCHIVO">                                 
                                <label for="archivoAdd"><b>Archivos Adjuntos</b></label>                               
                            </div>
                            <div class="col-9">                                
                                <textarea placeholder="Informacion nutricional" class="form-control form-control-sm h-100" id="infoNut" name="infoNut" rows="4" style="resize: none"></textarea>
                            </div>                            
                        </div>
                    </div>
                </div>  
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-1 mx-2">
                        <div class="d-flex justify-content-center">
                            <a href="#" id="descargarArchivo">
                               
                            </a>
                        </div>
                        <div class="row">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            <div class="form-floating">
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    
            <div id="mostrarFamiliasAdd" class="accordion-body pt-1 pb-4"  style="display: none;background-color:#fffacd;">
                <div class="d-flex m-2 justify-content-center align-items-center">
                    <div class="col-sm-6 col-md-2 me-2 d-flex flex-column justify-content-center align-items-center text-center p-2" id="iconEstructuraFam" onclick="modal_estructura_fami()">
                        <div class="row">
                            <img src="../../img/png/estructura_familiar.png" width="80" height="80" title="ESTRUCTURA FAMILIAR" class="icon">
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for=""><b>Estructura familiar</b></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2 me-2 d-flex flex-column justify-content-center align-items-center text-center p-2" id="iconVulnerabilidadFam" onclick="iconVulnerabilidadFam()">
                        <div class="row">
                            <img src="../../img/png/vulnerabilidades.png" width="80" height="80" title="VULNERABILIDADES" class="icon">
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for=""><b>Vulnerabilidades</b></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2 me-2 d-flex flex-column justify-content-center align-items-center text-center p-2" id="iconSituacionFam">
                        <div class="row">
                            <img src="../../img/png/situacion_economica.png" width="80" height="80" title="SITUACIÓN ECONÓMICA" class="icon">
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for=""><b>Situación Económica</b></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2 me-2 d-flex flex-column justify-content-center align-items-center text-center p-2" id="iconViviendaServicios">
                        <div class="row">
                            <img src="../../img/png/vivienda_servicios.png" width="80" height="80" title="VIVIENDA Y SERVICIOS BÁSICOS" class="icon">
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for=""><b>Vivienda y Servicios Básicos</b></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2 me-2 d-flex flex-column justify-content-center align-items-center text-center p-2" id="iconEvaluacionFam">
                        <div class="row">
                            <img src="../../img/png/evaluacion.png" width="80" height="80" title="EVALUACIÓN" class="icon">
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for=""><b>Evaluación</b></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="mostrarVoluntariosAdd" class="accordion-body"  style="background-color:#fffacd;"></div>
        </div>
    </div>
</div>
</form>
        


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
                    <form id="form_direccion">
                        <div class="form-group row">
                            <label for="Provincia" class="col-sm-3 col-form-label">Provincia</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="select_prov"  name="select_prov" onchange="ciudad(this.value)">
                                    <option value="">Seleccione provincia</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="Ciudad" class="col-sm-3 col-form-label">Ciudad</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="select_ciud"  name="select_ciud">
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
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success"  id="btnGuardarDir">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalBtnGrupo" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
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
                                <button type="button" class="btn btn-light border border-1 btn-sm">
                                    <img src="../../img/png/industrial.png" style="width: 90%; height: 90%;"
                                        alt="Imagen">
                                </button>
                                <b>Industrial</b>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <button type="button" class="btn btn-light border border-1 btn-sm">
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
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white">Beneficiario</h4>
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Estructura familiar</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div style="overflow-x: scroll;">
                        <table class="table table-sm table-hover campos-d" id="tablaIntegrantes">
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
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="filaAgregar">
                                    <td><input type="text" class="form-control form-control-sm" id="nuevoNombre" style="width:300px"></td>
                                    <td>
                                        <select class="form-select" id="nuevoGenero" name="nuevoGenero"></select>
                                    </td>
                                    <td>
                                        <select class="form-select" id="nuevoParentesco" name="nuevoParentesco"></select>
                                    </td>
                                    <td>
                                        <select class="form-select" id="nuevoRangoEdad" name="nuevoRangoEdad"></select>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm" id="nuevaOcupacion" style="width:200px"></td>
                                    <td>
                                        <select class="form-select" id="nuevoEstadoCivil" name="nuevoEstadoCivil"></select>
                                    </td>
                                    <td>
                                        <select class="form-control" id="nuevoNivelEscolaridad" name="nuevoNivelEscolaridad" onchange="validarNinguno(this, 'nuevoNombreInstitucion', 'nuevoTipoInstitucion')"></select>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm" id="nuevoNombreInstitucion" style="width:300px" disabled>
                                    </td>
                                    <td>
                                        <select class="form-select" id="nuevoTipoInstitucion" name="nuevoTipoInstitucion" disabled></select>
                                    </td>
                                    <td>
                                        <select class="form-select" id="nuevaVulnerabilidad" name="nuevaVulnerabilidad"></select>
                                    </td>
                                    <td><button type="button" class="btn btn-primary btn-sm" id="agregarIntegrante">Agregar</button></td>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Vulnerabilidades</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px; margin:5px">
                    <p>Integrantes discapacitados</p>
                    <div style="overflow-x: auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre persona</th>
                                    <th>Nombre de la discapacidad</th>
                                    <th>Tipo de discapacidad</th>
                                    <th>% discapacidad</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody  id="tablaFamDisc">
                            </tbody>
                        </table>
                    </div>

                    <div id="mensajeNoIntegrantes" class="alert alert-info" style="display: none;">
                        No hay integrantes discapacitados en la familia.
                    </div>

                    <p>Integrantes con Enfermedad</p>
                    <div style="overflow-x: auto; margin-top: 10px">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre persona</th>
                                    <th>Nombre de la enfermedad</th>
                                    <th>Tipo de enfermedad</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody  id="tablaFamEnfe">
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
                        <div id="modal_" style="display: flex; flex-wrap: wrap; overflow-y: auto; max-height: 200px;" class="justify-content-around">
                            <div class="d-flex flex-column justify-content-center">
                                <button id="btnIngresos" type="button" class="btn btn-light border border-1 btn-sm">
                                    <img src="../../img/png/ingresos.png" style="width: 60px; height: 60px">
                                </button>
                                
                                <b>Ingresos</b>
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <button id="btnEgresos" type="button" class="btn btn-light border border-1 btn-sm">
                                    <img src="../../img/png/egresos.png" style="width: 60px; height: 60px">
                                </button>
                                
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
                            <label for="totalIngresos"><b>Total ingresos:</b></label>
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
                        <table class="table table-sm table-hover" id="tablaSituacionE">
                            <thead>
                                <tr>
                                    <th>Tipo de vivienda</th>
                                    <th>¿La vivienda es?</th>
                                    <th>Valor/Avalúo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td>
                                    <select class="form-select form-select-sm" id="tipoVivienda">
                                        <option value="" selected disabled>Seleccione</option>
                                        <option value="casa">Casa</option>
                                        <option value="departamento">Departamento</option>
                                        <option value="mediaagua">Media Agua</option>
                                        <option value="cuarto">Cuarto</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" id="laViviendaEs">
                                        <option value="" disabled selected>Seleccione</option>
                                        <option value="propia">Propia</option>
                                        <option value="prestada">Prestada</option>
                                        <option value="arrendada">Arrendada</option>
                                        <option value="compartida">Compartida</option>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control form-control-sm" id="valor" onchange="verificarDecimales(this)"></td>
                            </tbody>
                        </table>
                        <table class="table table-sm table-hover" id="tablaServicios">
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
                        <table class="table table-sm table-hover" id="tablaOtrosGastos">
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
                            <label for="totalEgresos"><b>Total egresos:</b></label>
                            <input class="form-control imput-xs" id="totalEgresos" readonly></input>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnAceptarEgreso" data-bs-dismiss="modal">Aceptar</button>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Evaluación</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px; margin:5px">
                    <div style="overflow-x: auto;">
                        <table class="table table-sm table-hover" id="tablaEvaluacion">
                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Ingresos</td>
                                    <td><input type="number" class="form-control" id="ingresos" readonly></td>
                                </tr>
                                <tr>
                                    <td>Egresos</td>
                                    <td><input type="number" class="form-control" id="egresos" readonly></td>
                                </tr>
                                <tr>
                                    <td>Disponible</td>
                                    <td><input type="number" class="form-control" id="disponible" readonly>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-sm table-hover" id="tabla evaluacion completa">
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
                                <label for="totalAplica"><b>TOTAL:</b></label>
                                <input class="form-control imput-xs" id="totalAplica" readonly></input>
                            </div>
                            <div class="col-6 col-sm-8">
                                <label for="totalAplicaVC"><b>VALOR CONTEXTUAL:</b></label>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Información del Usuario</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px;">
                    <form id="form_info_usuario">                        
                        <div class="campoFamilia" style="margin-right: 10px;">
                            <div class="row form-group form-group-xs">
                                <div class="col-sm-6">
                                    <div>
                                        <label for="trabajaSelect"><b>¿Trabaja?</b></label>
                                        <div class="d-flex">
                                            <select class="form-select form-select-sm" id="trabajaSelect" name="trabajaSelect">
                                                <option value="0" selected>Sí</option>
                                                <option value="1">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="trabajaAct" style="display: none;">
                                        <label for="comentarioAct"><b>Actividad:</b></label>
                                        <textarea class="form-control form-control-sm" id="comentarioAct" name="comentarioAct" rows="2"
                                            style="resize: none"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="trabajaAct">
                                        <label for="modalidadSelect"><b>Modalidad</b></label>
                                        <select class="form-select form-select-sm" id="modalidadSelect" name="modalidadSelect">
                                            <option value="" selected disabled>Seleccione una opción</option>
                                            <option value="1">Dependiente</option>
                                            <option value="2">Independiente</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group form-group-xs">
                                <div class="col-sm-6">
                                    <div>
                                        <label for="conyugeSelect"><b>¿Cónyuge trabaja?</b></label>
                                        <div class="d-flex">
                                            <select class="form-select form-select-sm" id="conyugeSelect" name="conyugeSelect">
                                                <option value="0" selected>Sí</option>
                                                <option value="1">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="conyugeAct" style="display: none;">
                                        <label for="comentarioConyugeAct"><b>Actividad:</b></label>
                                        <textarea class="form-control form-control-sm" id="comentarioConyugeAct" name="comentarioConyugeAct" rows="2"
                                            style="resize: none"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="conyugeAct">
                                        <label for="modalidadConyugeSelect"><b>Modalidad</b></label>
                                        <select class="form-select form-select-sm" id="modalidadConyugeSelect" name="modalidadConyugeSelect">
                                            <option value="" disabled selected>Seleccione una opción</option>
                                            <option value="1">Dependiente</option>
                                            <option value="2">Independiente</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group form-group-xs">
                                <div class="col-sm-6">
                                    <label for="numHijosI"><b>Número de hijos</b></label>
                                    <input class="form-control form-control-sm" type="number" id="numHijosI" name="numHijosI"
                                        min="0">
                                </div>
                                <div class="col-sm-6 hijosAct" style="display: none;">
                                    <div>
                                        <label for="numHijosMayores"><b>Mayores de edad</b></label>
                                        <input class="form-control form-control-sm" type="number" id="numHijosMayores"
                                            name="numHijosMayores" min="0" value="">
                                    </div>
                                    <div>
                                        <label for="numHijosMenores"><b>Menores de edad</b></label>
                                        <input class="form-control form-control-sm" type="number" id="numHijosMenores"
                                            name="numHijosMenores" min="0" value="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="numPersonas"><b>Número de personas que viven en la casa</b></label>
                                    <input class="form-control form-control-sm" type="number" id="numPersonas"
                                        name="numPersonas" min="0">
                                </div>
                                
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success"  data-bs-dismiss="modal" id="btnAceptarUser">Aceptar</button>
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

<script src="../../dist/js/registro_beneficiario.js"></script>