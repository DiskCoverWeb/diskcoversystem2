var voluntario;
$(document).ready(function () {
    Form_Activate();
    // Form_ActivateFamilias();
    Form_ActivateVoluntarios();
    $('.campoSocial').hide();
    $('.campoFamilia').hide();
    $('.campoVoluntario').hide();
    $('.campoAliados').hide();

    var fechaActual = new Date().toISOString().split('T')[0];
    $('#fechaIngreso').val(fechaActual);
});

function Form_ActivateVoluntarios(){
    $.ajax({
        type: "GET",
        url: '../controlador/inventario/registro_beneficiarioC.php?CatalogoForm=true',
        //data: { 'fafactura': faFactura },
        dataType: 'json',
        success: function (data) {
            cargarEncabezado();
            construirFormulario(data);
            setRestricciones();
            //$('#contenedor-cf').css('visibility', 'hidden');
        }
    });
}

function cargarEncabezado(){
    let encabezado = `
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Formulario de Inscripción Voluntario Operativo</h1>
            <p>
                Estimado/a postulante por favor llenar el formulario 
                correctamente, si al final le falta completar algún 
                campo el sistema no le permitirá enviar la información. 
                Lea detenidamente la información de cada una de los campos 
                y secciones.
            </p>
        </div>
    </div>
    `;
    $('#form-contenedor').html(encabezado);
}

function construirFormulario(data){
    let seccionesForm = [];
    for(d of data){
        let rCodSplit = d['Codigo'].split('.');
        if(rCodSplit.length < 3){
            let dgrupo = d;
            if(rCodSplit.length == 2 && d['DG'] == "G"){
                dgrupo['Respuestas'] = new Array();
            }
            seccionesForm.push(dgrupo);
        }else if(rCodSplit.length == 3){
            seccionesForm[seccionesForm.length-1]['Respuestas'].push(d);
            
        }
    }
    
    let tiposInput = {
        "T": 'text',
        "B": 'radio',
        "D": 'date',
        "N": 'number',
        "%": 'number',
        "M": 'checkbox',
        "F": 'file'
    };
    let cuerpoHtml = `<div class="f-cuerpo">`;

    for(seccion of seccionesForm){
        let rCodSplit = seccion['Codigo'].split('.');
        if(rCodSplit.length == 1){
            cuerpoHtml += `
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">${capitalizarString(seccion['Cuenta'])}</h2>
                        <p class="text-secondary">${formatearComentarioVol((seccion['Comentario'] == '.' || seccion['Comentario'] == null) ? '' : seccion['Comentario'])}</p>
                        <div class="row justify-content-center">
                            <div class="col-sm-auto">
                                <img src="../../img/inscripcion/${seccion['Imagen']}.png" alt="" height="250px">
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }else{
            cuerpoHtml += `
                <div class="card">
                    <div class="card-body">
                    <label>
                        <b>${capitalizarString(seccion['Cuenta'])} <span class="text-danger">*</span></b>
                    </label>
                    <p class="text-secondary">${formatearComentarioVol((seccion['Comentario'] == '.' || seccion['Comentario'] == null) ? '' : seccion['Comentario'])}</p>
                    <div class="f-sec-input">
                `;
            if(seccion.hasOwnProperty('Respuestas')){
                let arrFormRadCheck = seccion['Respuestas'].filter(r => r['Tipo']=='B' || r['Tipo']=='M')
                let arrFormNoRC = seccion['Respuestas'].filter(r => r['Tipo']!='B' && r['Tipo']!='M')
                
                if(arrFormRadCheck.length > 0){
                    cuerpoHtml += `<div class="form-check">`;

                    arrFormRadCheck.forEach(respuestas => {
                        cuerpoHtml += `
                            <input class="form-check-input f-input f-input-${tiposInput[respuestas['Tipo']]}" type="${tiposInput[respuestas['Tipo']]}" name="${seccion['Codigo']}" id="${respuestas['Codigo']}" value="${setVoluntarioValue(seccion, respuestas)}"> <label class="form-check-label" for="${respuestas['Codigo']}"> ${capitalizarString(respuestas['Cuenta'])}</label><br/>
                        `;
                    })

                    cuerpoHtml += `</div>`;
                }

                if(arrFormNoRC.length > 0){
                    arrFormRadCheck.forEach(respuestas => {
                        cuerpoHtml += `
                            <input class="form-control form-control-sm f-input f-input-${tiposInput[respuestas['Tipo']]}" type="${tiposInput[respuestas['Tipo']]}" name="${seccion['Codigo']}" id="${respuestas['Codigo']}" value="${setVoluntarioValue(seccion, respuestas)}"> <label for="${respuestas['Codigo']}"> ${capitalizarString(respuestas['Cuenta'])}</label><br/>
                        `;
                    })
                }
            }else{
                let esPorcentaje = (seccion['Tipo']=='%');

                if(esPorcentaje){
                    cuerpoHtml += `
                        <div class="input-group input-group-sm" style="width:400px">
                            <input class="form-control form-control-sm f-input f-input-${esPorcentaje?'perc':tiposInput[seccion['Tipo']]}" type="${tiposInput[seccion['Tipo']]}" id="${seccion['Codigo']}" name="${seccion['Codigo']}" ${esPorcentaje?'min="0" max="100"':''} ${seccion['Codigo']=="01.04"?'onblur=\"verificarCedula(this)\"':''} ${seccion['Codigo']=="01.06"?'onchange=\"setEdadVoluntario()\"':''}>
                            <span class="input-group-text">%</span>
                        </div>
                    `;
                }else{
                    cuerpoHtml += `
                        <input style="width:400px" class="form-control form-control-sm f-input f-input-${esPorcentaje?'perc':tiposInput[seccion['Tipo']]}" type="${tiposInput[seccion['Tipo']]}" id="${seccion['Codigo']}" name="${seccion['Codigo']}" ${esPorcentaje?'min="0" max="100"':''} ${seccion['Codigo']=="01.04"?'onblur=\"verificarCedula(this)\"':''} ${seccion['Codigo']=="01.06"?'onchange=\"setEdadVoluntario()\"':''}>
                    `;
                }
                if(seccion['Tipo']=="F"){cuerpoHtml += `<div class="btnsDocumentoVol" id="documentoVoluntario-${seccion['Codigo']}" style="visibility:hidden;"><button class="btn btn-primary" >Ver archivo cargado</button> <button class="btn btn-warning" onclick="cambiarArchivoVol('${seccion['Codigo']}')">Cambiar archivo</button></div>`;}
            }
            if(seccion['Tipo']=="F") {
                cuerpoHtml += `</div><div id="f-error-${seccion['Codigo']}" class="f-error-file text-danger">`;
            }
            cuerpoHtml += `</div></div></div>`;
        }
    }


    cuerpoHtml += `
            <div class="row justify-content-between">
                <input type="hidden" id="env-insc-modo" value="1">
                <div class="col-sm-auto">
                    <button class="btn btn-warning env-insc-form" id="btnEnviarInsc" onclick="enviarFormInsc()" disabled>Enviar</button>
                </div>
                <div class="col-sm-auto">
                    <button class="btn btn-outline-warning reset-insc-form" id="btnResetInsc" onclick="resetFormInsc()">Borrar formulario</button>
                </div>
            </div>
        </div>
    `;
    $('#form-contenedor').append(cuerpoHtml);
}

function formatearComentarioVol(comentario){
    if(comentario == ''){return '';}

    let linkComentario = comentario.match(/http[s]?:\/\/[^ ]+/g);
    if(linkComentario){
        let arrComentario = comentario.split(linkComentario);
        let formatLinkComent = `<a href="${linkComentario}" target="_blank">${linkComentario}</a>`;
        return arrComentario[0] + formatLinkComent + arrComentario[1];
    }else{
        return comentario;
    }
}

function cambiarArchivoVol(codigo){
    document.getElementById(`${codigo}`).style.visibility = 'visible';
    document.getElementById(`documentoVoluntario-${codigo}`).style.visibility = 'hidden';
}

function setVoluntarioValue(seccion, respuestas){
    let valor = respuestas['Cuenta'];
    switch(seccion['Codigo']){
        case '01.05':
            valor = respuestas['Cuenta'][0];
        break;
        case '01.08':
            valor = respuestas['Cuenta'].substr(0,4);
        break;
        case '01.09':
        {
            if(respuestas['Cuenta'].includes(' DE ')){
                let separacion = respuestas['Cuenta'].split(' DE ');
                valor = separacion[0][0]+separacion[1][0];
            }else{
                valor = respuestas['Cuenta'][0];
            }
        }
        break;
        case '02.12':
        case '04.10':
            valor = respuestas['Codigo'];
        break;
        case '02.03':
        case '02.06':
        case '04.05':
        case '04.09':
        case '04.15':
        case '04.16':
            valor = respuestas['Cuenta']=="SI"?1:0;
        break;
    }
    return valor;
}

function setEdadVoluntario(){
    let hoy = new Date();
    let fNac = new Date(document.getElementById('01.06').value);
    let edad = hoy.getFullYear() - fNac.getFullYear();
    let m = hoy.getMonth() - fNac.getMonth();

    if (m < 0 || (m === 0 && hoy.getDate() < fNac.getDate())) {
        edad--;
    }
    document.getElementById('01.07').value = parseInt(edad);
}

function setRestricciones(){
    // RESTRICCIONES INPUT FILES
    document.querySelectorAll(".f-input-file").forEach(elem => elem.addEventListener('change', (e)=> {
        let archivo = e.target.files[0];
        if (archivo && /\s+/g.test(archivo.name)) {
            document.getElementById(`f-error-${e.target.id}`).innerText = "* No se pudo seleccionar el archivo debido a que su nombre tiene espacios.";
            e.target.value = "";
        }else if(archivo && archivo.name.length > 20){
            document.getElementById(`f-error-${e.target.id}`).innerText = "* El nombre del archivo no debe superar los 20 caracteres.";
            e.target.value = "";
        }else{
            document.getElementById(`f-error-${e.target.id}`).innerText = "";
        }
    }));

    //RESTRICCIONES CAMPO EDAD
    document.getElementById('01.07').setAttribute('disabled', 'true');

    //RESTRICCIONES CAMPOS OBLIGATORIOS
    document.getElementsByName('02.12')[0].parentElement.parentElement.children[0].children[0].remove();
    document.getElementsByName('04.08')[0].parentElement.parentElement.children[0].children[0].remove();
    document.getElementsByName('04.10')[0].parentElement.parentElement.children[0].children[0].remove();
}

function capitalizarString(cadena){
    return cadena.toLowerCase().replace(/^\w|(?<=\s)\w/g, l => l.toUpperCase()); //#TODO: Capitalizar \w luego de ?
}

function verificarCedula(elem){
    let cedula = elem.value;
    if(cedula == ""){
        $('#btnEnviarInsc').attr('disabled', 'true');
        return;
    }

    $.ajax({
        type: "POST",
        url: '../controlador/inventario/registro_beneficiarioC.php?ConsultarCliente=true',
        data: { 'cedula': cedula },
        dataType: 'json',
        success: function (data) {
            if(data['res'] == 1){
                elem.value="";
                $('#btnEnviarInsc').attr('disabled', 'true');
                $('#env-insc-modo').val("1");
                Swal.fire({
                    title: "Ya existe un voluntario asociado a este número de cédula.",
                    text: "¿Desea actualizar sus datos?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'SI',
                    cancelButtonText: 'NO'
                }).then((result) => {
                    if (result.value) {
                        IngClave('Supervisor');
                        voluntario = data['datos'];
                        voluntario['datos']['Fecha_N'] = voluntario['datos']['Fecha_N']['date'].split(' ')[0];
                    }else{
                        voluntario = undefined;
                    }
                });
            }else{
                $('#btnEnviarInsc').removeAttr('disabled');
                $('#env-insc-modo').val("1");
                voluntario = undefined;
            }
        }
    });
}

// TODO: Implementar funcion para la respuesta "Otros"
function selectOtros(){
    let elem = document.getElementById('b-01.05.03');
    setTimeout(() => {
        if(elem.checked == true){
            elem.nextElementSibling.nextElementSibling.removeAttribute('disabled')
        }else{
            elem.nextElementSibling.nextElementSibling.setAttribute('disabled', 'true')
        }
    }, 200);
}

function resetFormInsc(){
    document.querySelectorAll('.f-input').forEach(elem => {
        switch(elem.type){
            case 'text':
            case 'date':
            case 'number':
            case 'file':
                elem.value = '';
                break;
            case 'radio':
            case 'checkbox':
                elem.checked = false;
                break;
        }
    });
    $('#btnEnviarInsc').attr('disabled', 'true');
    document.querySelector("#form-contenedor").scrollIntoView({ behavior: 'smooth' });
    voluntario = undefined;
    $('#env-insc-modo').val("1");
    document.querySelectorAll('.f-input-file').forEach(el => el.style.visibility = 'visible');
    document.querySelectorAll('.btnsDocumentoVol').forEach(el => el.style.visibility = 'hidden');
}

function enviarFormInsc(){
    let servicio_basico = new Array();
    document.querySelectorAll('input[name="04.19"]:checked').forEach(el => {
        servicio_basico.push(el.value);
    });

    let parametros = {
        Cliente: `${document.getElementById('01.01').value} ${document.getElementById('01.02').value}`.toUpperCase(),
        Telefono: document.getElementById('01.03').value.toUpperCase(),
        CI_RUC: document.getElementById('01.04').value.toUpperCase(),
        Sexo: document.querySelector('input[name="01.05"]:checked').value,
        Fecha_N: document.getElementById('01.06').value,
        //edad: document.getElementById('01.07').value,
        Plan_Afiliado: document.querySelector('input[name="01.08"]:checked').value,
        Est_Civil: document.querySelector('input[name="01.09"]:checked').value,
        Calificacion: document.querySelector('input[name="02.01"]:checked').value,
        Gestacion: document.querySelector('input[name="02.02"]:checked').value,
        Especial: parseInt(document.querySelector('input[name="02.03"]:checked').value),
        Referencia: document.getElementById('02.04').value.toUpperCase(),
        Dosis: document.getElementById('02.05').value.toUpperCase(),
        Asignar_Dr: parseInt(document.querySelector('input[name="02.06"]:checked').value),
        DireccionT: document.getElementById('02.07').value.toUpperCase(),
        Representante: document.getElementById('02.08').value.toUpperCase(),
        CodigoA: document.getElementById('02.09').value.toUpperCase(),
        Contacto: document.getElementById('02.10').value.toUpperCase(),
        Telefono_R: document.getElementById('02.11').value.toUpperCase(),
        Tipo_Cta: document.querySelector('input[name="02.12"]:checked').value, //No va a dar
        Canton: document.querySelector('input[name="03.01"]:checked').value,
        Parroquia: document.getElementById('03.02').value.toUpperCase(),
        Barrio: document.getElementById('03.03').value.toUpperCase(),
        Direccion: `${document.getElementById('03.04').value} y ${document.getElementById('03.05').value}`.toUpperCase(),
        DirNumero: document.getElementById('03.06').value,
        Credito: parseInt(document.getElementById('04.01').value),
        No_Dep: parseInt(document.getElementById('04.02').value),
        Matricula: parseInt(document.getElementById('04.03').value),
        Cod_Banco: parseInt(document.getElementById('04.04').value),
        Descuento: parseInt(document.querySelector('input[name="04.05"]:checked').value),
        Profesion: document.querySelector('input[name="04.06"]:checked').value,
        Porc_C: parseInt(document.getElementById('04.07').value),
        FAX: document.getElementById('04.08').value,
        FactM: parseInt(document.querySelector('input[name="04.09"]:checked').value),
        Casilla: document.querySelector('input[name="04.10"]:checked').value,
        Cod_Ejec: document.getElementById('04.11').value,
        Cta_CxP: document.getElementById('04.12').value.toUpperCase(),
        Lugar_Trabajo: document.querySelector('input[name="04.13"]:checked').value,
        Tipo_Cliente: document.getElementById('04.14').value.toUpperCase(),
        Bono_Desarrollo: parseInt(document.querySelector('input[name="04.15"]:checked').value),
        IESS: parseInt(document.querySelector('input[name="04.16"]:checked').value),
        Actividad: document.getElementById('04.17').value.toUpperCase(),
        Tipo_Vivienda: document.querySelector('input[name="04.18"]:checked').value,
        Servicios_Basicos: servicio_basico.join(','),
        /*Archivo_CI_RUC_PAS: document.getElementById('05.01').files[0].name,
        Archivo_Record_Policial: document.getElementById('05.02').files[0].name,
        Archivo_Planilla: document.getElementById('05.03').files[0].name,
        Archivo_Carta_Recom: document.getElementById('05.04').files[0].name,
        Archivo_Certificado_Medico: document.getElementById('05.05').files[0].name,
        Archivo_VIH: document.getElementById('05.06').files[0].name,
        Archivo_Reglamento: document.getElementById('05.07').files[0].name*/
    }
    console.log(parametros['Fecha_N']);

    let modoEnviar = $('#env-insc-modo').val()=="1"?"CrearVoluntario":"ActualizarVoluntario";
    let dActualizadosVoluntario = {
        datos: 0,
        archivos: 0
    }

    if(modoEnviar == "ActualizarVoluntario"){
        let keys = Object.getOwnPropertyNames(parametros);
        let paramsAct = {};
        keys.forEach((p) => {
            if(parametros[p] != voluntario['datos'][p]){
                paramsAct[p] = parametros[p];
                dActualizadosVoluntario['datos'] = 1
            }
        })
        //if(Object.getOwnPropertyNames(paramsAct).length <= 0){dActualizadosVoluntario['datos'] = 1}
        paramsAct['CI_RUC'] = parametros['CI_RUC'];
        parametros = paramsAct;
    }
    parametros['TB'] = '93.03';

    console.log(parametros);
    console.log(modoEnviar);

    let formData = new FormData();
    //formData.append('parametros', parametros);
    for (const [key, value] of Object.entries(parametros)) {
        formData.append(key, value);
    }

    document.querySelectorAll('.f-input-file').forEach((el) => {
        let archivoTemp = el.files[0];
        if(archivoTemp){
            formData.append(obtenerKeyArchivoV(el.id), archivoTemp);
            dActualizadosVoluntario['archivos'] = 1;
        }
    })

    if(dActualizadosVoluntario['datos']==0 && dActualizadosVoluntario['archivos']==0){
        Swal.fire(
            "Error al actualizar la información",
            "No se han proporcionado nuevos datos para este voluntario.",
            "error"
        );
        return;
    }
    $("#myModal_espera").modal('show');

    //TODO: RESTRICCION CAMPOS OBLIGATORIOS
    
    $.ajax({
        type: "POST",
        url: `../controlador/inventario/registro_beneficiarioC.php?EnviarInscripcion=true&ModoEnviar=${modoEnviar}`,
        processData: false,
        contentType: false,
        data: formData,
        dataType: 'json',
        success: function (respuesta) {
            $("#myModal_espera").modal('hide');
            console.log(respuesta);
            if(respuesta['codigo'] == 1){
                Swal.fire(
                    "Éxito",
                    `${respuesta['respuesta']}`,
                    "success"
                )
                resetFormInsc();
            }else{
                Swal.fire(
                    "Error",
                    `${respuesta['respuesta']}`,
                    "error"
                )
            }
        },
        error: function(err){
            $("#myModal_espera").modal('hide');
            Swal.fire(
                "Error",
                `Hubo un error al realizar la consulta. Error: ${err}.`,
                "error"
            );
        }
    });

    //Creacion de reporte del formulario
}


/**
 * SITUACION ECONOMICA INGRESOS
*/
$('#iconSituacionFam').click(function () {
    $('#modalSituacionFam').modal('show');
});

$("#btnIngresos").click(function () {
    $('#modalSituacionFam').modal('hide');
    $('#modalIngresosFam').modal('show');
});

$("#btnAceptarIngreso").click(function () {
    //console.log(situaciones);     
    $('#modalIngresosFam').modal('hide');
});

function calcularTotalIngresos() {
    let totalIngresos = 0;
    for (let i = 0; i < situaciones.length; i++) {
        totalIngresos += parseFloat(situaciones[i].sumaIngresos) || 0;
    }
    $("#totalIngresos").val(totalIngresos.toFixed(2));
}

function validarNinguno(selectElem, ...valores){
    if(selectElem.value == "ninguno" || selectElem.value == "no" || selectElem.value == "01"){
        for(let valor of valores){
            $(`#${valor}`).attr('disabled', 'true');
            $(`#${valor}`).val("");
        }
    }else{
        for(let valor of valores){
            $(`#${valor}`).removeAttr('disabled');
            //$(`#${valor}`).val("");
        }
    }
}

function verificarDecimales(elem){
    if(isNaN(parseFloat(elem.value))){
        elem.value = "";
        Swal.fire('Valor no permitido', 'Este campo debe ser numerico', 'info');
    }else{
        console.log(parseFloat(elem.value));
        elem.value = parseFloat(elem.value).toFixed(2);
    }
}

function sumarCamposIngreso(elem){
    verificarDecimales(elem);

    let ingresoFijo = $("#ingresoFijo").val().trim() == "" ? 0 : parseFloat($("#ingresoFijo").val());
    let ingresoEventual = $("#ingresoEventual").val().trim() == "" ? 0 : parseFloat($("#ingresoEventual").val());
    let pensionAlimentos = $("#pensionAlimentos").val().trim() == "" ? 0 : parseFloat($("#pensionAlimentos").val());
    let ayudaFamiliar = $("#ayudaFamiliar").val().trim() == "" ? 0 : parseFloat($("#ayudaFamiliar").val());
    let jubilacion = $("#jubilacion").val().trim() == "" ? 0 : parseFloat($("#jubilacion").val());
    let bono = $("#bono").val().trim() == "" ? 0 : parseFloat($("#bono").val());

    let totalIng = ingresoFijo + ingresoEventual + pensionAlimentos + ayudaFamiliar + jubilacion + bono;
    $("#sumaIngresos").val(totalIng.toFixed(2));
}

var situaciones = [];
$("#agregarSituacion").click(function () {
    var situacion = {
        nombre: $("#nombreSituacion").val(),
        lugarTrabajo: $("#lugarTrabajo").val(),
        tipoSeguro: $("#tipoSeguro").val()==null?"":$("#tipoSeguro").val(),
        sueldoFijo: $("#sueldoFijo").val()==null?"":$("#sueldoFijo").val(),
        ingresoFijo: $("#ingresoFijo").val()==""?"0.00":$("#ingresoFijo").val(),
        ingresoEventual: $("#ingresoEventual").val()==""?"0.00":$("#ingresoEventual").val(),
        pensionAlimentos: $("#pensionAlimentos").val()==""?"0.00":$("#pensionAlimentos").val(),
        ayudaFamiliar: $("#ayudaFamiliar").val()==""?"0.00":$("#ayudaFamiliar").val(),
        jubilacion: $("#jubilacion").val()==""?"0.00":$("#jubilacion").val(),
        tipoBono: $("#tipoBono").val()==null?"":$("#tipoBono").val(),
        bono: $("#bono").val()==""?"0.00":$("#bono").val(),
        usoBono: $("#usoBono").val()==null?"":$("#usoBono").val(),
        sumaIngresos: $("#sumaIngresos").val()
    };

    let situacionVacios = [];
    if(situacion['nombre'].trim() == "") situacionVacios.push("Nombres y Apellidos");
    if(situacion['tipoSeguro'] == "") situacionVacios.push("Tipo de Seguro");
    if(situacion['sueldoFijo'] == "") situacionVacios.push("Sueldo Fijo");
    if(situacion['tipoBono'] == "") situacionVacios.push("Tipo de Bono");
    if(situacion['tipoBono'] != "ninguno" && situacion['usoBono'] == "") situacionVacios.push("Uso del Bono");

    if(situacionVacios.length > 0){
        swal.fire('Campos Vacíos', `Rellene los campos: ${situacionVacios.join(', ')}`, 'error');
    }else{
        situaciones.push(situacion);
        actualizarTablaSituacion();
        limpiarCamposSit();
        calcularTotalIngresos();
    }
    
});

function actualizarTablaSituacion() {
    var tablaBody = $("#tablaSituacion tbody");
    tablaBody.children(':not(:first)').remove();

    for (var i = 0; i < situaciones.length; i++) {
        var situacion = situaciones[i];
        var fila = $("<tr></tr>");
        fila.append($("<td></td>").text(situacion.nombre));
        fila.append($("<td></td>").text(situacion.lugarTrabajo));
        fila.append($("<td></td>").text(situacion.tipoSeguro));
        fila.append($("<td></td>").text(situacion.sueldoFijo));
        fila.append($("<td></td>").text(situacion.ingresoFijo));
        fila.append($("<td></td>").text(situacion.ingresoEventual));
        fila.append($("<td></td>").text(situacion.pensionAlimentos));
        fila.append($("<td></td>").text(situacion.ayudaFamiliar));
        fila.append($("<td></td>").text(situacion.jubilacion));
        fila.append($("<td></td>").text(situacion.tipoBono));
        fila.append($("<td></td>").text(situacion.bono));
        fila.append($("<td></td>").text(situacion.usoBono));
        fila.append($("<td></td>").text(situacion.sumaIngresos));
        fila.append($("<td><button type='button' class='btn btn-danger btn-eliminar'>Eliminar</button> <button type='button' class='btn btn-warning btn-editar'>Editar</button></td>"));
        tablaBody.append(fila);
    }
}

function limpiarCamposSit() {
    $("#nombreSituacion").val("");
    $("#lugarTrabajo").val("");
    $("#tipoSeguro").val("");
    $("#sueldoFijo").val("");
    $("#ingresoFijo").val("");
    $("#ingresoFijo").attr("disabled", "true");
    $("#ingresoEventual").val("");
    $("#pensionAlimentos").val("");
    $("#ayudaFamiliar").val("");
    $("#jubilacion").val("");
    $("#tipoBono").val("");
    $("#bono").val("");
    $("#bono").attr("disabled", "true");
    $("#usoBono").val("");
    $("#usoBono").attr("disabled", "true");
    $("#sumaIngresos").val("");
}

$("#tablaSituacion").on("click", ".btn-eliminar", function () {
    var fila = $(this).closest("tr");
    var index = fila.index() - 1;
    situaciones.splice(index, 1);
    actualizarTablaSituacion();
    calcularTotalIngresos();
});

$("#tablaSituacion").on("click", ".btn-editar", function () {
    var fila = $(this).closest("tr");
    var index = fila.index() - 1;
    var situacion = situaciones[index];

    $("#nombreSituacion").val(situacion.nombre);
    $("#lugarTrabajo").val(situacion.lugarTrabajo);
    $("#tipoSeguro").val(situacion.tipoSeguro);
    $("#sueldoFijo").val(situacion.sueldoFijo);
    $("#ingresoFijo").val(situacion.ingresoFijo);
    $("#ingresoEventual").val(situacion.ingresoEventual);
    $("#pensionAlimentos").val(situacion.pensionAlimentos);
    $("#ayudaFamiliar").val(situacion.ayudaFamiliar);
    $("#jubilacion").val(situacion.jubilacion);
    $("#tipoBono").val(situacion.tipoBono);
    $("#bono").val(situacion.bono);
    $("#usoBono").val(situacion.usoBono);
    $("#sumaIngresos").val(situacion.sumaIngresos);

    situaciones.splice(index, 1);
    actualizarTablaSituacion();
    calcularTotalIngresos();
});

/**
 * ESTRUCTURA FAMILIAR
*/





/**
 * PROGRAMA
*/
$('#btnPrograma').click(function () {
    $('#modalsBtn85').modal('show');
})

/*function cambiarSelectPrograma(elem){ // cambiada reciente
    if(elem.id == "btnFamilias"){
        $("#programa").val("familias");
    }else if(elem.id == "btn70Piquito"){
        $("#programa").val("setentaYPiquito");
    }
    $('#modalPrograma').modal('hide');
}*/

/**
 * INFORMACION USUARIO
*/
// $('#btnAceptarUser').click(function () {
//     const trabaja = $("#trabajaSelect").val();
//     const cometntarioAc = $("#comentarioAct").val();
//     const modalidad = $("#modalidadSelect").val();
//     const conyugeTrabaja = $("#conyugeSelect").val();
//     const comentarioConyugeAct = $("#comentarioConyugeAct").val();
//     const modalidadConyuge = $("#modalidadConyugeSelect").val();
//     const numHijos = $("#numHijos").val();
//     const numPersonas = $("#numPersonas").val();

//     /*console.log("Trabaja:", trabaja);
//     console.log("Actividad (Trabaja):", comentarioAct);
//     console.log("Modalidad (Trabaja):", modalidad);
//     console.log("Cónyuge Trabaja:", conyugeTrabaja);
//     console.log("Actividad (Cónyuge):", comentarioConyugeAct);
//     console.log("Modalidad (Cónyuge):", modalidadConyuge);
//     console.log("Número de Hijos:", numHijos);
//     console.log("Número de Personas en la Casa:", numPersonas);*/

//     $('#modalInfoUserFam').modal('hide');
// });

$('#btnInfoUser').click(function () {
    $('#modalInfoUserFam').modal('show');
    if ($('#trabajaSelect').val() === '0') {
        $('.trabajaAct').show();
    }
    if ($('#conyugeSelect').val() === '0') {
        $('.conyugeAct').show();
    }
});

$('#numHijosI').change(function () {
    const numHijos = parseInt($(this).val());
    const $hijosAct = $('.hijosAct');

    if (numHijos > 0) {
        $hijosAct.show();
    } else {
        $hijosAct.hide();
        $('#numHijosMayores, #numHijosMenores').val();
    }
});

$('#numHijosMayores, #numHijosMenores').change(function () {
    const numHijos = parseInt($('#numHijosI').val());
    const numHijosMayores = parseInt($('#numHijosMayores').val());
    const numHijosMenores = parseInt($('#numHijosMenores').val());
    const totalHijos = numHijosMayores + numHijosMenores;

    if (totalHijos > numHijos) {
        $(this).val(numHijos - (totalHijos - parseInt($(this).val())));
    }
});

$('#trabajaSelect').change(function () {
    var valorSeleccionado = $(this).val();
    if (valorSeleccionado === '0') {
        $('.trabajaAct').show();
    } else {
        $('.trabajaAct').hide();
        $('#comentarioAct').val('');
        $('#modalidadSelect').val('');
    }
});

$('#conyugeSelect').change(function () {
    var valorSeleccionado = $(this).val();
    if (valorSeleccionado === '0') {
        $('.conyugeAct').show();
    } else {
        $('.conyugeAct').hide();
        $('#comentarioConyugeAct').val('');
        $('#modalidadConyugeSelect').val('');
    }
});

/**
* VULNERABILIDADES
*/



/**
* SITUACION ECONOMICA EGRESOS
*/
$("#btnAceptarEgreso").click(function () {
    const tipoVivienda = $("#tipoVivienda").val();
    const laViviendaEs = $("#laViviendaEs").val();
    const valor = parseFloat($("#valor").val()) || 0;

    let totalEgresos = valor;

    $("#tablaServicios tbody tr").each(function () {
        const servicio = $(this).find("td:eq(0)").text();
        const dispone = $(this).find("td:eq(1) select").val();
        const valorServicio = parseFloat($(this).find("td:eq(2) input").val()) || 0;
        totalEgresos += valorServicio;
    });

    $("#tablaOtrosGastos tbody tr").each(function () {
        const otroGasto = $(this).find("td:eq(0)").text();
        const dispone = $(this).find("td:eq(1) select").val();
        const valorOtroGasto = parseFloat($(this).find("td:eq(2) input").val()) || 0;
        totalEgresos += valorOtroGasto;
    });

    $("#totalEgresos").val(totalEgresos.toFixed(2));

    $('#modalEgresosFam').modal('hide');
});

$("#tablaSituacionE, #tablaServicios, #tablaOtrosGastos").on("change", "input, select", function () {
    //const valor = parseFloat($("#valor").val()) || 0;

    let totalEgresos = 0;

    $("#tablaSituacionE tbody tr").each(function () {
        const valorServicio = parseFloat($(this).find("td:eq(2) input").val()) || 0;
        totalEgresos += valorServicio;
    });

    $("#tablaServicios tbody tr").each(function () {
        const valorServicio = parseFloat($(this).find("td:eq(2) input").val()) || 0;
        totalEgresos += valorServicio;
    });

    $("#tablaOtrosGastos tbody tr").each(function () {
        const valorOtroGasto = parseFloat($(this).find("td:eq(2) input").val()) || 0;
        totalEgresos += valorOtroGasto;
    });

    $("#totalEgresos").val(totalEgresos.toFixed(2));
});

$("#btnEgresos").click(function () {
    const servicios = ["Agua", "Luz", "Alcantarillado", "Internet", "Teléfono convencional",
        "Plan de Celular", "TvCable", "Plataformas Streaming", "Gas doméstico",
    ];

    function agregarFila(servicio) {
        const fila = `
    <tr>
        <td>${servicio}</td>
        <td>
            <select class="form-select form-select-sm">
                <option value="" selected disabled>Seleccione</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
            </select>
        </td>
        <td><input type="text" class="form-select form-select-sm" onchange="verificarDecimales(this)"></td>            
    </tr>
`;
        $("#tablaServicios tbody").append(fila);
    }

    servicios.forEach(servicio => agregarFila(servicio));

    const otrosGastos = ["Deudas", "Medicamentos", "Estudios", "Seguro"];

    function agregarFila2(otrosGastos) {
        const fila = `
    <tr>
        <td>${otrosGastos}</td>
        <td>
            <select class="form-select form-select-sm">
                <option value="" selected disabled>Seleccione</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
            </select>
        </td>
        <td><input type="text" class="form-select form-select-sm" onchange="verificarDecimales(this)"></td>            
    </tr>
`;
        $("#tablaOtrosGastos tbody").append(fila);
    }

    otrosGastos.forEach(gastos => agregarFila2(gastos));

    $('#modalSituacionFam').modal('hide');
    $('#modalEgresosFam').modal('show');
});

/**
 * VIVIENDA Y SITUACION ECONOMICA
*/
$('#btnAceptarViviendaServ').click(function () {

    $("#tablaVivienda tbody tr").each(function () {
        const pisos = $(this).find("input").val();
        const material = $(this).find("select").val();
        const techo = $(this).find("select").val();
        const piso = $(this).find("select").val();
        //console.log(`Vivienda: ${pisos}, Material ${material}, Techo ${techo}, Piso ${piso}`);
    });

    $("#tablaTec tbody tr").each(function () {
        const tecnologia = $(this).find("td:first").text();
        const cantidad = $(this).find("input").val();
        //console.log(`Tecnología: ${tecnologia}, Cantidad: ${cantidad}`);
    });

    $("#tablaElec tbody tr").each(function () {
        const electrodomestico = $(this).find("td:first").text();
        const cantidad = $(this).find("input").val();
        //console.log(`Electrodoméstico: ${electrodomestico}, Cantidad: ${cantidad}`);
    });

    $("#tablaMueble tbody tr").each(function () {
        const mueble = $(this).find("td:first").text();
        const cantidad = $(this).find("input").val();
        //console.log(`Mueble: ${mueble}, Cantidad: ${cantidad}`);
    });

    $("#tablaAmbiente tbody tr").each(function () {
        const ambiente = $(this).find("td:first").text();
        const cantidad = $(this).find("input").val();
        //console.log(`Ambiente: ${ambiente}, Cantidad: ${cantidad}`);
    });

    $('#modalViviendaFam').modal('hide');

});

$('#iconViviendaServicios').click(function () {
    const tecnologias = ["Televisores/SmartTV/LCD", "Equipos de sonido", "Computadores/Laptops", "Celulares",
        "Play Station", "DVD/Blue Ray", "Radiograbadora", "Tablets"];

    function agregarFila1(tecnologia) {
        const fila = `<tr><td>${tecnologia}</td><td><input type="number" min="0" value="0" class="form-control input-xs"></td></tr>`;
        $("#tablaTec  tbody").append(fila);
    }

    tecnologias.forEach(tecnologia => agregarFila1(tecnologia));

    const electrodomesticos = ["Horno microondas", "Licuadora", "Refrigeradora", "Lavadora",
        "Secadora", "Extractor", "Waflera", "Calefón"];
    function agregarFila2(electrodomestico) {
        const fila = `<tr><td>${electrodomestico}</td><td><input type="number" min="0" value="0" class="form-control input-xs"></td></tr>`;
        $("#tablaElec tbody").append(fila);
    }
    electrodomesticos.forEach(electrodomestico => agregarFila2(electrodomestico));

    const muebles = ["camas", "armarios", "juego de comedor", "juego de sala", "mueble de cocina"];
    function agregarFila3(mueble) {
        const fila = `<tr><td>${mueble}</td><td><input type="number" min="0" value="0" class="form-control input-xs"></td></tr>`;
        $("#tablaMueble tbody").append(fila);
    }
    muebles.forEach(mueble => agregarFila3(mueble));

    const ambientes = ["Cocina", "Sala", "Comedor", "Garaje", "Cuarto de lavado/lavandería",
        "Cuarto de estudio", "Vehículo", "Habitaciones", "Baños"];
    function agregarFila4(ambiente) {
        const fila = `<tr><td>${ambiente}</td><td><input type="number" min="0" value="0" class="form-control input-xs"></td></tr>`;
        $("#tablaAmbiente tbody").append(fila);
    }
    ambientes.forEach(ambiente => agregarFila4(ambiente));

    $('#modalViviendaFam').modal('show');
});

/**
 * EVALUACION
*/
$('#iconEvaluacionFam').click(function () {
    $("#ingresos").val($("#totalIngresos").val());
    $("#egresos").val($("#totalEgresos").val());
    var ingresos = parseFloat($("#ingresos").val()) || 0;
    var egresos = parseFloat($("#egresos").val()) || 0;
    var disponible = (ingresos - egresos).toFixed(2);
    $("#disponible").val(disponible);

    var totalAplica = 0;

    if (parseInt($("#edad").val()) >= 65) {
        $("#edadDato").val(parseInt($("#edad").val()));
        $("#edadEval").val(1);
        $("#edadText").val("APLICA");
        $("#edadText").css("color", "green");
        totalAplica++;
    } else if (parseInt($("#edad").val()) < 65) {
        $("#edadDato").val(parseInt($("#edad").val()));
        $("#edadEval").val(0);
        $("#edadText").val("NO APLICA");
        $("#edadText").css("color", "red");
    }else{
        $("#edadDato").val("");
        $("#edadText").val("NO DEFINIDO");
        $("#edadText").css("color", "grey");
    }

    var numPersonas = ($("#numPersonas").val()) || 0;
    var ingresoPorPersona = ingresos / numPersonas;
    console.log(ingresoPorPersona)
    console.log(ingresos)
    console.log(numPersonas)
    if (ingresoPorPersona <= 48) {
        $("#ingresoHabitanteDato").val(ingresoPorPersona);
        $("#ingresoHabitante").val(2);
        $("#ingresoHabitanteText").val("POBREZA EXTREMA");
        $("#ingresoHabitanteText").css("color", "green");
        totalAplica++;
    } else if (ingresoPorPersona > 48 && ingresoPorPersona <= 85) {
        $("#ingresoHabitanteDato").val(ingresoPorPersona);
        $("#ingresoHabitante").val(1);
        $("#ingresoHabitanteText").val("POBREZA");
        $("#ingresoHabitanteText").css("color", "orange");
        totalAplica++;
    } else if (ingresoPorPersona > 85) {
        $("#ingresoHabitanteDato").val(ingresoPorPersona);
        $("#ingresoHabitante").val(0);
        $("#ingresoHabitanteText").val("NO APLICA");
        $("#ingresoHabitanteText").css("color", "red");
    }else{
        $("#ingresoHabitanteDato").val("");
        $("#ingresoHabitanteText").val("NO DEFINIDO");
        $("#ingresoHabitanteText").css("color", "grey");
    }

    if (totalVulnerables >= 1) {
        $("#discapacidadDato").val(totalVulnerables);
        $("#discapacidadEval").val(1);
        $("#discapacidadText").val("APLICA");
        $("#discapacidadText").css("color", "green");
        totalAplica++;
    } else if (totalVulnerables == 0) {
        $("#discapacidadDato").val(totalVulnerables);
        $("#discapacidadEval").val(0);
        $("#discapacidadText").val("NO APLICA");
        $("#discapacidadText").css("color", "red");
    }else{
        $("#discapacidadDato").val("");
        $("#discapacidadText").val("NO DEFINIDO");
        $("#discapacidadText").css("color", "grey");
    }

    var numHijosMenores = parseInt($('#numHijosMenores').val());
    if (numHijosMenores > 1) {
        $("#numHijosDato").val(numHijosMenores);
        $("#numHijosEval").val(1);
        $("#numHijosText").val("APLICA");
        $("#numHijosText").css("color", "green");
        totalAplica++;
    } else if (numHijosMenores == 0) {
        $("#numHijosDato").val(numHijosMenores);
        $("#numHijosEval").val(0);
        $("#numHijosText").val("NO APLICA");
        $("#numHijosText").css("color", "red");
    }else{
        $("#numHijosDato").val("");
        $("#numHijosText").val("NO DEFINIDO");
        $("#numHijosText").css("color", "grey");
    }

    var laViviendaEs = $("#laViviendaEs").val();
    console.log(laViviendaEs);
    if (laViviendaEs == "prestada" || laViviendaEs == "arrendada" || laViviendaEs == "compartida") {
        $("#viviendaDato").val(laViviendaEs);
        $("#vivienda").val(1);
        $("#viviendaText").val("APLICA");
        $("#viviendaText").css("color", "green");
        totalAplica++;
    } else if (laViviendaEs == "propia") {
        $("#viviendaDato").val(laViviendaEs);
        $("#vivienda").val(0);
        $("#viviendaText").val("NO APLICA");
        $("#viviendaText").css("color", "red");
    } else {
        //$("#vivienda").val();
        $("#viviendaDato").val("");
        $("#viviendaText").val("NO DEFINIDO");
        $("#viviendaText").css("color", "grey");
    }

    var estadoCivil = $('#estadoCivil').val();
    if (estadoCivil == "soltero" || estadoCivil == "separado" || estadoCivil == "viudo") {
        $("#madrePadreSolteroDato").val(estadoCivil);
        $("#madrePadreSoltero").val(1);
        $("#madrePadreSolteroText").val("APLICA");
        $("#madrePadreSolteroText").css("color", "green");
        totalAplica++;
    } else if (estadoCivil == "casado" || estadoCivil == "unionL" || estadoCivil == "unionH") {
        $("#madrePadreSolteroDato").val(estadoCivil);
        $("#madrePadreSoltero").val(0);
        $("#madrePadreSolteroText").val("NO APLICA");
        $("#madrePadreSolteroText").css("color", "red");
    } else {
        $("#madrePadreSolteroDato").val("");
        $("#madrePadreSolteroText").val("NO DEFINIDO");
        $("#madrePadreSolteroText").css("color", "grey");
    }

    var modalidad = $("#modalidadSelect").val();
    if (modalidad == "1") {
        $("#trabajoUsuarioDato").val(modalidad);
        $("#trabajoUsuario").val(1);
        $("#trabajoUsuarioText").val("APLICA");
        $("#trabajoUsuarioText").css("color", "green");

        totalAplica++;
    } else if (modalidad == "0") {
        $("#trabajoUsuarioDato").val(modalidad);
        $("#trabajoUsuario").val(0);
        $("#trabajoUsuarioText").val("NO APLICA");
        $("#trabajoUsuarioText").css("color", "red");
    } else {
        $("#trabajoUsuarioDato").val("");
        $("#trabajoUsuarioText").val("NO DEFINIDO");
        $("#trabajoUsuarioText").css("color", "grey");
    }

    var modalidadC = $("#modalidadConyugeSelect").val();
    if (modalidadC == "1") {
        $("#trabajoConyugeDato").val(modalidadC);
        $("#trabajoConyuge").val(1);
        $("#trabajoConyugeText").val("APLICA");
        $("#trabajoConyugeText").css("color", "green");

        totalAplica++;
    } else if (modalidadC == "0") {
        $("#trabajoConyugeDato").val(modalidadC);
        $("#trabajoConyuge").val(0);
        $("#trabajoConyugeText").val("NO APLICA");
        $("#trabajoConyugeText").css("color", "red");

    } else {
        $("#trabajoConyugeDato").val("");
        $("#trabajoConyugeText").val("NO DEFINIDO");
        $("#trabajoConyugeText").css("color", "grey");
    }

    var usoBono = null;
    for (var i = 0; i < situaciones.length; i++) {
        var situacion = situaciones[i];
        if (situacion.usoBono != "") {
            usoBono = 1;
            break;
        }

        if(i == situaciones.length-1){
            usoBono = 0;
        }
    }
    if (usoBono === 1) {
        $("#usoBonoDiscapacidadDato").val(usoBono);
        $("#usoBonoDiscapacidad").val(1);
        $("#usoBonoDiscapacidadText").val("APLICA");
        $("#usoBonoDiscapacidadText").css("color", "green");
        totalAplica++;
    } else if (usoBono === 0) {
        $("#usoBonoDiscapacidadDato").val(usoBono);
        $("#usoBonoDiscapacidad").val(0);
        $("#usoBonoDiscapacidadText").val("NO APLICA");
        $("#usoBonoDiscapacidadText").css("color", "red");

    } else {
        $("#usoBonoDiscapacidadDato").val("");
        $("#usoBonoDiscapacidadText").val("NO DEFINIDO");
        $("#usoBonoDiscapacidadText").css("color", "grey");
    }

    if (totalAplica >= 5) {
        $("#totalAplica").val(totalAplica);
        $("#totalAplicaVC").val("APLICA");
        $("#totalAplicaVC").css("color", "green");
    } else {
        $("#totalAplica").val(totalAplica);
        $("#totalAplicaVC").val("NO APLICA");
        $("#totalAplicaVC").css("color", "red");
    }

    $('#modalEvaluacionFam').modal('show');
});


$('#btnAceptarEvaluacion').click(function () {
    const edad = $("#edadEval").val();
    const edadText = $("#edadText").val();
    const ingresoHabitante = $("#ingresoHabitante").val();
    const ingresoHabitanteText = $("#ingresoHabitanteText").val();
    const discapacidad = $("#discapacidad").val();
    const discapacidadText = $("#discapacidadText").val();
    const numHijos = $("#numHijos").val();
    const numHijosText = $("#numHijosText").val();
    const vivienda = $("#vivienda").val();
    const viviendaText = $("#viviendaText").val();
    const madrePadreSoltero = $("#madrePadreSoltero").val();
    const madrePadreSolteroText = $("#madrePadreSolteroText").val();
    const trabajoUsuario = $("#trabajoUsuario").val();
    const trabajoUsuarioText = $("#trabajoUsuarioText").val();
    const trabajoConyuge = $("#trabajoConyuge").val();
    const trabajoConyugeText = $("#trabajoConyugeText").val();
    const usoBonoDiscapacidad = $("#usoBonoDiscapacidad").val();
    const usoBonoDiscapacidadText = $("#usoBonoDiscapacidadText").val();
    /*console.log("Edad:", edad, edadText);
    console.log("Ingreso x habitante:", ingresoHabitante, ingresoHabitanteText);
    console.log("Discapacidad o Enfermedades:", discapacidad, discapacidadText);
    console.log("Número de hijos:", numHijos, numHijosText);
    console.log("Vivienda:", vivienda, viviendaText);
    console.log("Madre/Padre Solter@:", madrePadreSoltero, madrePadreSolteroText);
    console.log("Trabajo Usuario:", trabajoUsuario, trabajoUsuarioText);
    console.log("Trabajo Cónyuge:", trabajoConyuge, trabajoConyugeText);
    console.log("Uso del Bono de discapacidad:", usoBonoDiscapacidad, usoBonoDiscapacidadText);*/

    $('#modalEvaluacionFam').modal('hide');
});

/**
 * CALENDARIO 
*/
function Calendario(datos) {
    return new Promise((resolve, reject) => {
        const promesas = datos.map(async (cliente) => {
            var TB = cliente.TB || '';
            var Cliente = cliente.Cliente || '';
            var Envio_No = cliente.Envio_No || '';
            var Dia_Ent = cliente.Dia_Ent || '';
            var Hora_Ent = cliente.Hora_Ent || '';
            var colorV = await ObtenerColor(Envio_No);
            var fechaActual = new Date();
            var diaSemana = fechaActual.getDay();
            var fechaEvento;

            switch (Dia_Ent) {
                case 'Lun':
                    fechaEvento = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate() - diaSemana + 1);
                    break;
                case 'Mar':
                    fechaEvento = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate() - diaSemana + 2);
                    break;
                case 'Mie':
                    fechaEvento = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate() - diaSemana + 3);
                    break;
                case 'Jue':
                    fechaEvento = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate() - diaSemana + 4);
                    break;
                case 'Vie':
                    fechaEvento = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate() - diaSemana + 5);
                    break;
                case 'Sab':
                    fechaEvento = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate() - diaSemana + 6);
                    break;
                case 'Dom':
                    fechaEvento = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate() - diaSemana + 0);
                    break;
                default:
                    fechaEvento = fechaActual;
            }

            const fechaInicio = new Date(fechaEvento.getFullYear(), fechaEvento.getMonth(), fechaEvento.getDate(), Hora_Ent.split(':')[0], Hora_Ent.split(':')[1]);
            const fechaFin = new Date(fechaInicio.getTime() + 30 * 60000);

            return {
                title: Cliente,
                start: fechaInicio,
                end: fechaFin,
                backgroundColor: colorV,
                textColor: 'black',
            };
        });

        Promise.all(promesas)
            .then((events) => {
                resolve(events);
                inicializarCalendario(events);
            })
            .catch((error) => {
                reject(error);
            });
    });
}

var eventosEliminados = [];
var eventosEditados = [];
var eventosCreados = [];
function inicializarCalendario(events) {
    $('#myModal_espera').modal('hide')
    $('#mycalendar').modal('show');
    var calendarEl = $("#calendar")[0];
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            //left: 'prev,next today',
            //center: 'title',
            right: 'timeGridWeek,listWeek'
        },
        locale: 'es',
        views: {
            timeGridWeek: {
                buttonText: 'Semana'
            },
            listWeek: {
                buttonText: 'Lista'
            }
        },
        allDaySlot: false,
        weekends: false,
        navLinks: true,
        selectable: true,
        selectMirror: true,
        slotMinTime: '09:30:00',
        slotMaxTime: '16:30:00',
        slotDuration: '00:15:00',
        select: function (arg) {
            var eventoExistente = calendar.getEvents().find(function (evento) {
                return evento.title === miCliente;
            });

            if (!eventoExistente) {
                var endDate = new Date(arg.start.getTime() + 30 * 60000);
                var nuevoEvento = {
                    title: miCliente,
                    start: arg.start,
                    end: endDate,
                    allDay: arg.allDay
                };
                calendar.addEvent(nuevoEvento);
                eventosCreados.push(nuevoEvento);
            } else {
                swal.fire("", "El usuario ya tiene una asignación en el Calendario", "error");
            }
            calendar.unselect();
        },
        eventClick: function (arg) {
            if (arg.event.title === miCliente) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción eliminará el evento',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        arg.event.remove();
                        eventosEliminados.push({
                            title: arg.event.title,
                            start: arg.event.start,
                            end: arg.event.end
                        });
                    }
                });
            }
        },
        editable: true,
        dayMaxEvents: true,
        events: events.map(event => {
            console.log(`Evento: ${event.title}, Día: ${event.start.getDay()}`);
            return event;
        }).concat([
            {
                daysOfWeek: [1, 2, 3, 4, 5],
                startTime: '12:30',
                endTime: '13:30',
                display: 'background',
                rendering: 'background'
            }
        ]),
        eventChange: function (info) {
            if (info.event.title === miCliente) {
                var index = eventosEditados.findIndex(function (item) {
                    return item.title === miCliente;
                });
                if (index !== -1) {
                    eventosEditados[index] = {
                        title: info.event.title,
                        start: info.event.start,
                        end: info.event.end
                    };
                } else {
                    eventosEditados.push({
                        title: info.event.title,
                        start: info.event.start,
                        end: info.event.end
                    });
                }
            }
        }
    });

    calendar.render();
}

$('#btnGuardarCale').click(function () {
    $('#mycalendar').modal('hide');

    if (eventosEditados.length > 0) {
        eventosEditados.forEach(function (evento) {
            var startDate = new Date(evento.start);
            var dayName = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'][startDate.getDay()];
            var startTime = startDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            $('#diaEntregac').val(dayName.substring(0, 3));

            $('#horaEntregac').val(startTime);
        });
    } else {
        eventosCreados.forEach(function (evento) {
            var startDate = new Date(evento.start);
            var dayName = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'][startDate.getDay()];
            var startTime = startDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            $('#diaEntregac').val(dayName.substring(0, 3));
            $('#horaEntregac').val(startTime);
        });
    }

    if (eventosEliminados.length > 0) {
        $('#diaEntregac').val('');
        $('#horaEntregac').val('');

    }
});

/**
 * ARCHIVOS ADJUNTOS
*/
function checkFiles(input) {
    var maxFiles = 3;
    var max = 3;
    const maxFileSize = 10;

    const files = input.files;
    if (contador != 0) {
        max = maxFiles - contador;
        if (files.length > max) {
            Swal.fire({
                title: 'Solo se permiten un máximo de ' + maxFiles + ' archivos.',
                text: 'Ya cargó ' + contador + ' archivo (s). Puede eliminar algunos si es necesario.',
                icon: 'error'
            });
            input.value = '';
            return;
        }
    }
    if (files.length > maxFiles) {
        Swal.fire({
            title: 'Solo se permiten un máximo de ' + maxFiles + ' archivos.',
            text: 'Intentó cargar ' + files.length + ' archivo (s).',
            icon: 'error'
        });
        input.value = '';
    } else if (files.length > 0) {
        var fileNames = [];
        var fileSizeLimit = false;
        var contieneSpecialChar = false;
        var specialChar = /[!@#$%^&*()+\-=\[\]{};':"\\|,<>\/?]/;

        for (var i = 0; i < files.length; i++) {
            var fileName = files[i].name.toLowerCase();
            if (specialChar.test(fileName)) {
                contieneSpecialChar = true;
                break;
            }

            if (fileName.includes(' ')) {
                fileName = fileName.replace(/ /g, "_");
            }
            fileNames.push(fileName);

            if (files[i].size > maxFileSize * 1024 * 1024) {
                fileSizeLimit = true;
                break;
            }
        }

        if (contieneSpecialChar) {
            Swal.fire({
                title: 'Los nombres de los archivos no deben contener caracteres especiales',
                text: specialChar,
                icon: 'error'
            });
            input.value = '';
        } else if (fileSizeLimit) {
            Swal.fire({
                title: 'El tamaño máximo permitido por archivo es de ' + maxFileSize + 'MB.',
                text: '',
                icon: 'error'
            });
            input.value = '';
        } else {
            if (contador != 0) {
                fileNames.push(nombreArchivo);
            }
            var fileList = fileNames.join(',');
            if (fileList.length > 50) {
                Swal.fire({
                    title: 'La longitud total de los nombres de archivo supera el máximo de caracteres.',
                    text: '',
                    icon: 'error'
                });
                input.value = '';
            } else {
                $('#modalDescarga').modal('hide');
                Swal.fire({
                    title: 'Archivos cargados con éxito',
                    text: 'Archivos seleccionados: ' + fileList,
                    icon: 'success'
                });
                $('#modalDescarga .modal-footer').hide();
            }
        }
    }

}

//direccion
$('#btnMostrarDir').click(function () {
    provincias();
    $('#modalBtnDir').modal('show');
});

$('#btnInfoAliado').click(function () {
    $('#modalsBtnpAliado').modal('show');
});

//direccion
$('#btnGuardarDir').click(function () {
    $('#modalBtnDir').modal('hide');
    var provincia = $('#select_prov').val();
    var ciudad = $('#select_ciud').val();
    var canton = $('#Canton').val();
    var parroquia = $('#Parroquia').val();
    var barrio = $('#Barrio').val();
    var callep = $('#CalleP').val();
    var calles = $('#CalleS').val();
    var referencia = $('#Referencia').val();
});

//select provincias
function provincias() {
    var option = "<option value='' disabled selected>Seleccione provincia</option>";
    $.ajax({
        url: '../controlador/inventario/registro_beneficiarioC.php?provincias=true',
        type: 'post',
        dataType: 'json',
        beforeSend: function () {
            $("#select_ciud").html("<option value='' disabled selected>Seleccione ciudad</option>");
        },
        success: function (response) {
            response.forEach(function (data, index) {
                option += "<option value='" + data.Codigo + "'>" + data.Descripcion_Rubro + "</option>";
            });
            $('#select_prov').html(option);
            if (prov != null) {
                $('#select_prov').val(prov).trigger('change');
                ciudad(prov);
            }
        }
    });
}

//select ciudad
function ciudad(idpro) {
    var option = "<option value='' disabled selected>Seleccione ciudad</option>";
    if (idpro != '') {
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?ciudad=true',
            type: 'post',
            dataType: 'json',
            data: { idpro: idpro },
            success: function (response) {
                response.forEach(function (data, index) {
                    option += "<option value='" + data.Codigo + "'>" + data.Descripcion_Rubro + "</option>";
                });
                $('#select_ciud').html(option);
                if (ciud != null) {
                    $('#select_ciud').val(ciud).trigger('change');
                }
            }
        });
    }
}

//grupo
$('#btnMostrarGrupo').click(function () {
    $('#modalBtnGrupo').modal('show');
    agregarFila();
});

function agregarFila() {
    var tbody = $('#tablaPoblacion tbody');
    tbody.empty();
    datosArray.forEach(function (item) {
        var valor = item.id.substring(0, 2);
        if (valor == 91) {
            var fila = $('<tr>', { valueData: item.id });
            var celda1 = $('<td>', { colspan: 2, text: item.text });
            var celda2 = $('<td>').append($('<input>', { type: 'number', class: 'form-control hombres', name: 'hombres', value: 0 }));
            var celda3 = $('<td>').append($('<input>', { type: 'number', class: 'form-control mujeres', name: 'mujeres', value: 0 }));
            var celda4 = $('<td>').append($('<input>', { type: 'number', class: 'form-control total', name: 'total', readonly: true, value: 0 }));
            fila.append(celda1, celda2, celda3, celda4);
            tbody.append(fila);

            var valorFila = valoresFilas.find(f => f.valueData === item.id);

            if (valorFila) {
                fila.find('.hombres').val(valorFila.hombres);
                fila.find('.mujeres').val(valorFila.mujeres);
                fila.find('.total').val(valorFila.total);
            }

            fila.find('.hombres, .mujeres').on('change', function () {
                var hombres = parseInt(fila.find('.hombres').val()) || 0;
                var mujeres = parseInt(fila.find('.mujeres').val()) || 0;
                var total = hombres + mujeres;
                fila.find('.total').val(total);
            });
        }
    });
}

var valoresFilas = [];
$('#btnGuardarGrupo').click(function () {
    var filas = $('#tablaPoblacion tbody tr');
    valoresFilas = [];
    var totalSum = 0;
    filas.each(function () {
        var hombres = parseInt($(this).find('.hombres').val()) || 0;
        var mujeres = parseInt($(this).find('.mujeres').val()) || 0;
        var total = parseInt($(this).find('.total').val()) || 0;
        var textoFila = $(this).find('td:first-child').text();
        var valueData = $(this).attr('valueData');

        if (hombres > 0 || mujeres > 0 || total > 0) {
            totalSum += total;
            valoresFilas.push({ hombres, mujeres, total, valueData });
        }
    });
    $('#totalPersonas').val(totalSum);
    $('#modalBtnGrupo').modal('hide');
});


var datosArray = [];
function llenarCarousels(valor, valor2) {
    $.ajax({
        type: "GET",
        url: '../controlador/inventario/registro_beneficiarioC.php?LlenarSelects_Val=true',
        data: { valor: valor, valor2: valor2 },
        dataType: 'json',
        success: function (res) {
            if(res.respuesta){
                var val = res.val;
                var datos = res.respuesta;
    
                datos.forEach(function (item) {
                    datosArray.push(item);
                });
                if (valor != 91) {
                    if (val == 1) {
                        var carouselInner = $('#carouselBtnImaDon .carousel-inner');
                        if (datos.length > 0) {
                            carouselInner.empty();
                        }
                        datos.forEach(function (item, index) {
                            var carouselItem = $('<div class="carousel-item">');
                            if (index === 0) {
                                carouselItem.addClass('active');
                            }
                            var imgSrc = '../../img/png/' + item.picture + '.png';
                            var carouselContent = '<img src="' + imgSrc + '" alt="' + item.text + '" width="50" height="50">' +
                                '<div class="carousel-caption">' +
                                '</div>';
                            carouselItem.html(carouselContent);
                            carouselItem.click(function () {
                                abrirModal('Don');
                            });
                            carouselInner.append(carouselItem);
                        });
                        var option = '';
                        var opt = '<option value="">Estado</option>';
                        datos.forEach(function (item) {
                            option += '<div class="col-md-6 col-sm-6">' +
                                '<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/' + item.picture + '.png" onclick="itemSelect(\'' + item.picture +
                                '\',\'' + item.text + '\', \'' + item.color + '\', \'' + item.id +
                                '\')" style="width: 50px;height: 50px;"></button><br>' +
                                '<b>' + item.text + '</b>' +
                                '</div>';
                            opt += '<option value="' + item.id + '">' + item.text + '</option>';
                        });
                        $('#modal_Don').html(option);
    
                    } else {
                        var carouselInner = $('#carouselBtnIma_' + valor + ' .carousel-inner');
                        if (datos.length > 0) {
                            carouselInner.empty();
                        }
                        datos.forEach(function (item, index) {
                            var carouselItem = $('<div class="carousel-item">');
                            if (index === 0) {
                                carouselItem.addClass('active');
                            }
                            var imgSrc = '../../img/png/' + item.picture + '.png';
                            var carouselContent = '<img src="' + imgSrc + '" alt="' + item.text + '" width="100%">' +
                                '<div class="carousel-caption">' +
                                '</div>';
                            carouselItem.html(carouselContent);
                            carouselItem.click(function () {
                                abrirModal(valor);
                            });
                            carouselInner.append(carouselItem);
                        });
    
                        var option = '';
                        if (valor == 87) {
                            var opt = '<option value="">Estado</option>';
                            datos.forEach(function (item) {
                                option += '<div class="col-md-6 col-sm-6">' +
                                    '<button type="button" class="btn btn-light border border-1 btn-sm"><img src="../../img/png/' +
                                    item.picture + '.png" onclick="itemSelect(\'' + item.picture +
                                    '\',\'' + item.text + '\', \'' + item.color + '\', \'' + item.id +
                                    '\')" style="width: 50px;height: 50px;"></button><br>' +
                                    '<b class="text-center">' + item.text + '</b>' +
                                    '</div>';
                                opt += '<option value="' + item.id + '">' + item.text + '</option>';
                            });
                            $('#modal_87').html(option);
                        }
                        if (valor == 93) {
                            var opt = '<option value="">Beneficiario</option>';
                            datos.forEach(function (item) {
                                option += '<div class="col-md-6 col-sm-6">' +
                                    '<button type="button" class="btn btn-light border border-1 btn-sm" onclick="' +
                                    (item.id === '93.04' ? "itemSelect('" + item.picture + "','" + item.text +
                                        "','" + item.color + "','" + item.id + "'); abrirModal('pAliado');" :
                                        "itemSelect('" + item.picture + "','" + item.text + "','" + item.color +
                                        "','" + item.id + "');") +
                                    '">' +
                                    '<img src="../../img/png/' + item.picture + '.png" style="width: 50px;height: 50px;"></button><br>' +
                                    '<b class="text-center">' + item.text + '</b>' +
                                    '</div>';
                                opt += '<option value="' + item.id + '">' + item.text + '</option>';
                            });
                            $('#modal_93').html(option);
                        }
                        if (valor == 85) {
                            var opt = '<option value="">Programa</option>';
                            datos.forEach(function (item) {
                                option += '<div class="col-md-6 col-sm-6">' +
                                    '<button type="button" class="btn btn-light border border-1 btn-sm"><img src="../../img/png/' +
                                    item.picture + '.png" onclick="itemSelect(\'' + item.picture +
                                    '\',\'' + item.text + '\', \'' + item.color + '\', \'' + item.id +
                                    '\')" style="width: 50px;height: 50px;"></button><br>' +
                                    '<b class="text-center">' + item.text + '</b>' +
                                    '</div>';
                                opt += '<option value="' + item.id + '">' + item.text + '</option>';
                            });
                            $('#modal_85').html(option);
                        }
                    }
                }
            }

        }
    });
}

function handleSelectEvent(e, valor) {
    var data = e.params.data;
    var id = data.id;
    datosArray.forEach(function (item) {
        if (item.id == id) {
            var imagen = "../../img/png/" + item.picture + ".png";
            if (valor == 93) {
                // var val = id.substring(0, 2);
                // $("#carouselBtnIma_" + val + " .item.active img").attr("src", imagen);
                // $("#carouselBtnIma_" + val).carousel("pause");
                // actualizarEstilo(item.color);
                // if (id == "93.04") abrirModal('pAliado');
            }
            if (valor == 85) {
                var val = id.substring(0, 2);
                $("#carouselBtnIma_" + val + " .item.active img").attr("src", imagen);
                $("#carouselBtnIma_" + val).carousel("pause");
            }
            // if (valor == 87) {
            //     var val = id.substring(0, 2);
            //     $("#carouselBtnIma_" + val + " .item.active img").attr("src", imagen);
            //     $("#carouselBtnIma_" + val).carousel("pause");
            // }
            if (valor == 0) {
                $("#carouselBtnImaDon .item.active img").attr("src", imagen);
                $("#carouselBtnImaDon").carousel("pause");
            }
        }
    });
}

// $('#select_93').on('select2:select', function (e) {
//     handleSelectEvent(e, 93);
// });
// $('#select_85').on('select2:select', function (e) {
//     handleSelectEvent(e, 85);
// });
// $('#select_87').on('select2:select', function (e) {
//     handleSelectEvent(e, 87);
// });
// $('#select_CxC').on('select2:select', function (e) {
//     handleSelectEvent(e, 0);
// });

function abrirModal(valor) {
    $('#modalsBtn' + valor).modal('show');
};

// function itemSelect(picture, text, color, id) {
//     if (id.length == 3) {
//         var imagen = "../../img/png/" + picture + ".png";

//         $("#carouselBtnImaDon .carousel-item.active img").attr("src", imagen);
//         $("#carouselBtnImaDon").carousel("pause");
//         $("#modalsBtnDon").modal("hide");
//         var newOption = new Option(text, id, true, true);
//         $('#select_CxC').append(newOption).trigger('change');
//     } else {
//         var valor = id.substring(0, 2);
//         var imagen = "../../img/png/" + picture + ".png";

//         $("#carouselBtnIma_" + valor + " .carousel-item.active img").attr("src", imagen);
//         $("#carouselBtnIma_" + valor).carousel("pause");
//         $("#modalsBtn" + valor).modal("hide");

//         if (valor == 93) {
//             actualizarEstilo(color);
//         }

//         var newOption = new Option(text, id, true, true);
//         $('#select_' + valor).append(newOption).trigger('change');
//     }
// }

//btn icono RUC
function validarRucYValidarSriC() {
    limpiar_info_general();
    var ruc = $('#txt_ci').val();
    if (ruc) {
        validar_sri_registro(ruc);
    } else {
        Swal.fire({
            title: 'Por favor, seleccione un RUC',
            text: '',
            icon: 'error'
        });
    }
}

//btn dentro de modal icono RUC
function usar_cliente(nombre, ruc, codigo, email, td = 'N') {
    LimpiarPanelOrgSocialAdd();

    var newOption = new Option(nombre, codigo, true, true);
    $('#cliente').append(newOption).trigger('change');
    $('#lbl_ci').text(ruc)

    miCliente = nombre;
    miRuc = ruc;
    miCodigo = codigo;

    $('#myModal').modal('hide');
    llenarCamposInfo(codigo);
}

var horaActual;
function Form_Activate() {
    $('#comentariodiv').hide();
    LlenarSelectSexo();
    // LlenarSelectRucCliente();
    // llenarCarousels(85);
    // llenarCarousels(87);
    llenarCarousels(91);
    // llenarCarousels("CxC", true);
    // LlenarSelects_Val("CxC", true);
    // LlenarSelects_Val(85);
    // LlenarSelects_Val("estadoCivil");
    // LlenarSelects_Val(86);
    // LlenarSelects_Val(87);
    // LlenarSelects_Val(88);
    LlenarSelects_Val(89);
    // LlenarSelects_Val(90);
    LlenarSelects_Val(91);
    // LlenarSelects_Val(92);
    //[86, 87, 88, 89, 90, 91, 92, 93].forEach(LlenarSelects_Val);

    horaActual = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    $('#horaEntregac').val(horaActual);
}

//textarea para tipo de frecuencia ocacional
$('#select_86').change(function () {
    var selectedValue = $(this).val();
    if (selectedValue === '86.04') {
        $('#comentario').val(comen);
        $('#comentariodiv').show();
    } else {
        $('#comentario').val('.');
        $('#comentariodiv').hide();
    }
});

//calendario
$('#btnMostrarModal').click(function () {
    var valorSeleccionado = $('#select_93').val();
    if (valorSeleccionado !== null && valorSeleccionado !== undefined) {
        $('#myModal_espera').modal('show');
        LlenarCalendario(valorSeleccionado);
    } else {
        Swal.fire({
            title: 'Por favor, seleccione una organización',
            text: '',
            icon: 'info'
        });
    }
});

function LlenarCalendario(TB) {
    if (TB) {
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?LlenarCalendario=true',
            type: 'post',
            dataType: 'json',
            data: { valor: TB },
            success: function (datos) {
                if (datos != 0 && datos[0].Envio_No != null) {
                    Calendario(datos);
                } else {
                    $('#tabla-body').empty();
                    Swal.fire({
                        title: 'No se encontraron datos de asignación',
                        text: '',
                        icon: 'info'
                    });
                }
            }
        });
    }
}

//color para celdas del calendario
function ObtenerColor(valEnvio_No) {
    return new Promise((resolve) => {
        if (valEnvio_No) {
            $.ajax({
                url: '../controlador/inventario/registro_beneficiarioC.php?ObtenerColor=true',
                type: 'post',
                dataType: 'json',
                data: { valor: valEnvio_No },
                success: function (data) {
                    var color = "#" + data.Color.substring(4);
                    resolve(color);
                }
            });
        } else {
            resolve('#000000');
        }
    });
}

//selects Sexo
function LlenarSelectSexo() {
    $.ajax({
        url: '../controlador/inventario/registro_beneficiarioC.php?LlenarSelectSexo=true',
        type: 'post',
        dataType: 'json',
        success: function (datos) {
            $('#sexo').append('<option value="" disabled selected>Seleccione una opción</option>');

            $.each(datos, function (index, opcion) {
                $('#sexo').append('<option value="' + opcion['Codigo'] + '">' + opcion['Descripcion'] + '</option>');
            });
        }
    });
}


//select RUC y Cliente
// function LlenarSelectRucCliente() {
//     $('#cliente').select2({
//         width: '84%',
//         placeholder: 'Seleccione una opción',
//         ajax: {
//             url: '../controlador/inventario/registro_beneficiarioC.php?',
//             dataType: 'json',
//             delay: 250,
//             data: function (params) {
//                 return {
//                     query: params.term,
//                     LlenarSelectRucCliente: true
//                 }
//             },
//             processResults: function (data) {
//                 return {
//                     results: data.clientes
//                 };
//             },
//             cache: true
//         }
//     });
// }



//todos los selects_num
function LlenarSelects_Val(valor, valor2) {
    $('#select_' + valor).select2({
        placeholder: 'Seleccione una opción',        
        width:'100%',
        ajax: {
            url: '../controlador/inventario/registro_beneficiarioC.php?LlenarSelects_Val=true',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    query: params.term,
                    valor: valor,
                    valor2: valor2,
                    //LlenarSelects_Val: true
                };
            },
            processResults: function (data) {
                var options = [];
                if (data.respuesta === "No se encontraron datos para mostrar") {
                    options.push({
                        id: '',
                        text: data.respuesta
                    });
                } else {
                    $.each(data.respuesta, function (index, opcion) {
                        var option = {
                            id: opcion.id,
                            text: opcion.text,
                            color: opcion.color
                        };
                        options.push(option);
                    });
                }
                return {
                    results: options
                };
            },
            cache: true
        }
    });
}

function autorizarCambios() {
    Swal.fire({
        title: "Se requiere autorización para modificar el beneficiario: " + miCliente,
        text: "¿Desea proceder ingresando su contraseña?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'SI',
        cancelButtonText: 'NO'
    }).then((result) => {
        if (result.value) {
            IngClave('Supervisor');
        } else {
            $('#collapseTwo').collapse('hide');
        }
    });
}

$('#btnAutorizarCambios').click(function () {
    if($("#select_93").val() == "93.01"){
        if (miCliente != undefined) {
            autorizarCambios();
        } else {
            Swal.fire({
                title: 'No se seleccionó un Cliente',
                text: '',
                icon: 'warning',
            });
        }
    }
});

function resp_clave_ingreso(response) {
    if($("#select_93").val() == "93.03"){
        if (response.respuesta == 1) {
            $('#clave_supervisor').modal('hide');
            cargarVoluntario(voluntario['datos'], voluntario['archivos']);
            $('#btnEnviarInsc').removeAttr('disabled');
            $('#env-insc-modo').val("2");
        } else {
            document.getElementById('01.04').value = "";
            voluntario = undefined;
            $('#btnEnviarInsc').attr('disabled', 'true');
        }
    }else{
        if (response.respuesta == 1) {
            $('#clave_supervisor').modal('hide');
            $('#collapseTwo').collapse('show');
            userAuth = true;
        } else {
            $('#collapseTwo').collapse('hide');
        }
    }
}

function cargarVoluntario(voluntario, archivos){
    console.log(voluntario);
    let nombresArr = voluntario['Cliente'].split(' ');
    document.getElementById('01.01').value = `${nombresArr[0]} ${nombresArr[1]}`;
    document.getElementById('01.02').value = `${nombresArr[2]} ${nombresArr[3]}`;
    document.getElementById('01.03').value = voluntario['Telefono'];
    document.getElementById('01.04').value = voluntario['CI_RUC'];
    document.querySelector(`input[name="01.05"][value=${voluntario['Sexo']}]`).checked = true;
    document.getElementById('01.06').value = voluntario['Fecha_N'];
    document.querySelector(`input[name="01.08"][value="${voluntario['Plan_Afiliado']}"]`).checked = true;
    document.querySelector(`input[name="01.09"][value="${voluntario['Est_Civil']}"]`).checked = true;
    document.querySelector(`input[name="02.01"][value="${voluntario['Calificacion']}"]`).checked = true;
    document.querySelector(`input[name="02.02"][value="${voluntario['Gestacion']}"]`).checked = true;
    document.querySelector(`input[name="02.03"][value="${voluntario['Especial']}"]`).checked = true;
    document.getElementById('02.04').value = voluntario['Referencia'];
    document.getElementById('02.05').value = voluntario['Dosis'];
    document.querySelector(`input[name="02.06"][value="${voluntario['Asignar_Dr']}"]`).checked = true;
    document.getElementById('02.07').value = voluntario['DireccionT'];
    document.getElementById('02.08').value = voluntario['Representante'];
    document.getElementById('02.09').value = voluntario['CodigoA'];
    document.getElementById('02.10').value = voluntario['Contacto'];
    document.getElementById('02.11').value = voluntario['Telefono_R'];
    document.querySelector(`input[name="02.12"][value="${voluntario['Tipo_Cta']}"]`).checked = true;
    document.querySelector(`input[name="03.01"][value="${voluntario['Canton']}"]`).checked = true;
    document.getElementById('03.02').value = voluntario['Parroquia'];
    document.getElementById('03.03').value = voluntario['Barrio'];
    document.getElementById('03.04').value = voluntario['Direccion'].split(' Y ')[0];
    document.getElementById('03.05').value = voluntario['Direccion'].split(' Y ')[1];
    document.getElementById('03.06').value = voluntario['DirNumero'];
    document.getElementById('04.01').value = voluntario['Credito'];
    document.getElementById('04.02').value = voluntario['No_Dep'];
    document.getElementById('04.03').value = voluntario['Matricula'];
    document.getElementById('04.04').value = voluntario['Cod_Banco'];
    document.querySelector(`input[name="04.05"][value="${voluntario['Descuento']}"]`).checked = true;
    document.querySelector(`input[name="04.06"][value="${voluntario['Profesion']}"]`).checked = true;
    document.getElementById('04.07').value = voluntario['Porc_C'];
    document.getElementById('04.08').value = voluntario['FAX'];
    document.querySelector(`input[name="04.09"][value="${voluntario['FactM']}"]`).checked = true;
    document.querySelector(`input[name="04.10"][value="${voluntario['Casilla']}"]`).checked = true;
    document.getElementById('04.11').value = voluntario['Cod_Ejec'];
    document.getElementById('04.12').value = voluntario['Cta_CxP'];
    document.querySelector(`input[name="04.13"][value="${voluntario['Lugar_Trabajo']}"]`).checked = true;
    document.getElementById('04.14').value = voluntario['Tipo_Cliente'];
    document.querySelector(`input[name="04.15"][value="${voluntario['Bono_Desarrollo']}"]`).checked = true;
    document.querySelector(`input[name="04.16"][value="${voluntario['IESS']}"]`).checked = true;
    document.getElementById('04.17').value = voluntario['Actividad'];
    document.querySelector(`input[name="04.18"][value="${voluntario['Tipo_Vivienda']}"]`).checked = true;
    let sbArray = voluntario['Servicios_Basicos'].split(',');
    sbArray.forEach((v) => {
        document.querySelector(`input[name="04.19"][value="${v}"]`).checked = true;
    });
    setEdadVoluntario();
    /*TODO: Archivos*/

    for (const [key, value] of Object.entries(archivos)){
        mostrarBotonesArchivo(key, value);
    }
    
}

function obtenerKeyArchivoV(valor){
    let archivoKey = {
        'Archivo_CI_RUC_PAS': '05.01',
        'Archivo_Record_Policial': '05.02',
        'Archivo_Planilla': '05.03',
        'Archivo_Carta_Recom': '05.04',
        'Archivo_Certificado_Medico': '05.05',
        'Archivo_VIH': '05.06',
        'Archivo_Reglamento': '05.07'
    }

    if(/^\d{2}.\d{2}$/g.test(valor)){
        return Object.keys(archivoKey).find(key => archivoKey[key] === valor) || null;
    }else{
        return archivoKey[valor] || null;
    }
}

function mostrarBotonesArchivo(key, value){
    let codigo = obtenerKeyArchivoV(key);

    document.getElementById(`${codigo}`).style.visibility = 'hidden';
    document.getElementById(`${codigo}`).value = '';
    let elemBtnsVol = document.getElementById(`documentoVoluntario-${codigo}`);
    elemBtnsVol.style.visibility = 'visible';

    if(elemBtnsVol.firstChild.hasAttribute('onclick')){
        elemBtnsVol.firstChild.removeAttribute('onclick');
    }
    elemBtnsVol.firstChild.setAttribute('onclick', `mostrarArchivoVoluntario('${value}')`);
}

function mostrarArchivoVoluntario(nombreArch){
    let ruta = `../comprobantes/inscripciones/${nombreArch}#view=FitH`;
    document.getElementById('modVATitulo').innerText = nombreArch;
    document.getElementById('modVAFrame').src = ruta;
    $('#modalVistaArchivo').modal('show');
}

//registro 

$('#btnGuardarAsignacion').click(function () {



    switch($('#select_93').val()){
        case '93.01':
        {
            var fileInput = $('#archivoAdd')[0];
            var formData = new FormData();
    
            for (var i = 0; i < fileInput.files.length; i++) {
                formData.append('Evidencias[]', fileInput.files[i]);
            }
    
            formData.append('Cliente', miCliente);
            formData.append('CI_RUC', miRuc);
            formData.append('Codigo', miCodigo);
            formData.append('TB', $('#select_93').val() || '.');
            formData.append('CodigoA', $('#select_87').val() || '.');
            formData.append('Calificacion', $('#select_CxC').val() || '.');
            formData.append('Representante', $('#nombreRepre').val());
            formData.append('CI_RUC_R', $('#ciRepre').val());
            formData.append('Telefono_R', $('#telfRepre').val());
            formData.append('Contacto', $('#contacto').val());
            formData.append('Profesion', $('#cargo').val());
            formData.append('Dia_Ent', $('#diaEntrega').val() || '.');
            formData.append('Hora_Ent', $('#horaEntrega').val());
    
            formData.append('Sexo', $('#sexo').val() || '.');
            formData.append('Email', $('#email').val());
            formData.append('Email2', $('#email2').val());
            formData.append('Telefono', $('#telefono').val());
            formData.append('TelefonoT', $('#telefono2').val());
    
            formData.append('Provincia', $('#select_prov').val() || '.');
            formData.append('Ciudad', $('#select_ciud').val() || '.');
            formData.append('Canton', $('#Canton').val() || '.');
            formData.append('Parroquia', $('#Parroquia').val() || '.');
            formData.append('Barrio', $('#Barrio').val() || '.');
            formData.append('CalleP', $('#CalleP').val() || '.');
            formData.append('CalleS', $('#CalleS').val() || '.');
            formData.append('Referencia', $('#Referencia').val() || '.');
    
            // Información adicional
            formData.append('CodigoA2', $('#select_88').val());
            formData.append('Dia_Ent2', $('#diaEntregac').val() || '.');
            formData.append('Hora_Registro', $('#horaEntregac').val());
            formData.append('Envio_No', $('#select_86').val());
            formData.append('Comentario', $('#comentario').val() || '.');
            formData.append('No_Soc', $('#totalPersonas').val());
            //formData.append('Area', $('#select_91').val());
            formData.append('Acreditacion', $('#select_92').val());
            formData.append('Tipo_Dato', $('#select_90').val());
            formData.append('Cod_Fam', $('#select_89').val());
            formData.append('Observaciones', $('#infoNut').val());
    
            formData.append('TipoPoblacion', JSON.stringify(valoresFilas));
    
            //validacion campos llenos
            var camposVacios = [];
            if (!miRuc) camposVacios.push('RUC');
            if (!$('#select_88').val()) camposVacios.push('Tipo Entrega');
            if (!$('#diaEntregac').val()) camposVacios.push('Fecha Entrega');
            if (!$('#horaEntregac').val()) camposVacios.push('Hora Entrega');
            if (!$('#select_86').val()) camposVacios.push('Frecuencia');
            if (!$('#totalPersonas').val()) camposVacios.push('Personas Atendidas');
            //if (!$('#select_91').val()) camposVacios.push('Tipo poblacion');
            if (valoresFilas.length == 0) {
                camposVacios.push('Tipo Poblacion');
            }
            if (!$('#select_92').val()) camposVacios.push('Accion social');
            if (!$('#select_90').val()) camposVacios.push('Vulnerabilidad');
            if (!$('#select_89').val()) camposVacios.push('Tipo Atencion');
            if (!fileInput.files.length && nombreArchivo == "") camposVacios.push('Evidencias');
            if (!$('#infoNut').val()) camposVacios.push('Observaciones');
    
            if (userNew == false && userAuth == false) {
                Swal.fire({
                    title: '',
                    text: "Usted no está autorizado.",
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            } else if (camposVacios.length > 0) {
                var mensaje = 'Los siguientes campos están vacíos:\n';
                camposVacios.forEach(function (campo) {
                    mensaje += campo + ',';
                });
                Swal.fire({
                    title: 'Campos Vacíos',
                    text: mensaje,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            } else {
                $('#myModal_espera').modal('show');
                $.ajax({
                    type: 'post',
                    url: '../controlador/inventario/registro_beneficiarioC.php?guardarAsignacion=true',
                    processData: false,
                    contentType: false,
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        $('#myModal_espera').modal('hide');
                        if (response.res == '0') {
                            Swal.fire({
                                title: 'AVISO',
                                text: response.mensaje + (response.datos || ''),
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        } else {
                            Swal.fire({
                                title: response.mensaje,
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });
                            nombreArchivo = response.datos.result;
                        }
                    }
                });
            }
        }
        break;
        case '93.02':
        {
            const formData = new FormData();
            formData.append('TB', $('#select_93').val());
            formData.append('Calificacion', $('#select_CxC').val());
            formData.append('Num_Lista', $('#select_85').val());
            formData.append('Fecha', $('#fechaIngreso').val());
            formData.append('CodigoA', $('#select_87').val());
            formData.append('Cliente', `${$('#apellidos').val()} ${$('#nombres').val()}` || '.');
            formData.append('CI_RUC', $('#cedula').val() || '.');
            formData.append('Profesion', $('#nivelEscolar').val() || '.');
            formData.append('Est_Civil', $('#estadoCivil').val() || '.');
            formData.append('Fecha_N', $('#edad').val() || '.');// ??????
            formData.append('Actividad', $('#ocupacion').val() || '.');
            formData.append('Telefono', $('#telefonoFam').val() || '.');
            formData.append('Referencia', $('#pregunta').val() || '.');
            
            $('#myModal_espera').modal('show');
            console.log(formData);
            /*$.ajax({
                type: 'post',
                url: '../controlador/inventario/registro_beneficiarioC.php?guardarAsignacion=true',
                processData: false,
                contentType: false,
                data: formData,
                dataType: 'json',
                success: function (response) {
                    $('#myModal_espera').modal('hide');
                    if (response.res == '0') {
                        Swal.fire({
                            title: 'AVISO',
                            text: response.mensaje + (response.datos || ''),
                            type: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        Swal.fire({
                            title: response.mensaje,
                            type: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                        nombreArchivo = response.datos.result;
                    }
                }
            });*/
        }
        break;
        default:
        {
            Swal.fire({
                title: 'ERROR',
                text: 'Porfavor seleccione un Tipo de Beneficiario',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
        break;
    }
});



//llenar campos del cliente segun ruc seleccionado
$('#ruc').on('select2:select', function (e) {
    LimpiarPanelOrgSocialAdd();
    var data = e.params.data;
    miCodigo = data.id;
    miRuc = data.text;
    miCliente = data.Cliente;
    if (data.id === '.') {
        Swal.fire("No se encontró un Cliente relacionado.", "", "error");
    } else {
        if ($('#cliente').find("option[value='" + data.id + "']").length) {
            $('#cliente').val(data.id).trigger('change');
        } else {
            var newOption = new Option(data.Cliente, data.id, true, true);
            $('#cliente').append(newOption).trigger('change');
        }
        //var valorSeleccionado = $('#cliente').val();
        llenarCamposInfo(miCodigo);
    }
});



//llenar campos panel voluntarios
function CamposPanelVoluntarios() {
    $("#mostrarVoluntariosAdd").css("display", "block");
    $("#mostrarFamiliasAdd").css("display", "none");
    $("#mostrarOrgSocialAdd").css("display", "none");

}

//llenar campos de panel informacion adicional
var comen;
var userNew = false;
var userAuth = false;
 $('#botonInfoAdd').click(function () {
    var valorSeleccionado = $('#select_93').val();
    if ($('#contenidoColapsado').hasClass('show')) {
         $('#collapseTwo').collapse('hide');
    } else {
         $('#collapseTwo').collapse('show');
    }
    
    switch (valorSeleccionado) {
        case '93.01':
            $("#mostrarFamiliasAdd").css("display", "none");
            $("#mostrarVoluntariosAdd").css("display", "none");
            $("#mostrarOrgSocialAdd").css("display", "block");                
            break;
        case '93.02':
            $("#mostrarFamiliasAdd").css("display", "block");
            $("#mostrarVoluntariosAdd").css("display", "none");
            $("#mostrarOrgSocialAdd").css("display", "none");
            break;
        case '93.03':

            break;
        case '93.04':
             $("#mostrarFamiliasAdd").css("display", "none");
             $("#mostrarAliadosAdd").css("display", "block");
             $("#mostrarOrgSocialAdd").css("display", "none");
            break;
        default:
            Swal.fire("Error", "Tipo de Beneficiario no ha sido seleccionado.", "error").then(function(){
                $('#collapseTwo').collapse('hide');
            })
            break;
    }
});



//llenar selects preseleccionados
function llenarPreSelects(valor) {
    if (valor != ".") {
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?LlenarSelects_Val=true',
            type: 'GET',
            dataType: 'json',
            data: { valor: valor },
            success: function (res) {
                var val = res.val;
                var datos = res.respuesta;
                if (!res.error) {
                    datos.forEach(function (item) {
                        valorp = item.id.slice(0, 2);
                        if ($('#select_' + valorp).find("option[value='" + item.id + "']").length) {
                            $('#select_' + valorp).val(item.id).trigger('change');
                        } else {
                            var newOption = new Option(item.text, item.id, true, true);
                            $('#select_' + valorp).append(newOption).trigger('change');
                        }
                    });
                }
            }
        });
    }
}

//estilos de panel


//conversion color y tono mas oscuro para encabezado del panel
function darkenColor(color, percent) {
    var num = parseInt(color, 16),
        amt = Math.round(2.55 * percent),
        R = (num >> 16) - amt,
        G = (num >> 8 & 0x00FF) - amt,
        B = (num & 0x0000FF) - amt;

    R = (R < 255 ? (R < 1 ? 0 : R) : 255);
    G = (G < 255 ? (G < 1 ? 0 : G) : 255);
    B = (B < 255 ? (B < 1 ? 0 : B) : 255);

    return "#" + ((1 << 24) + (R << 16) + (G << 8) + B).toString(16).slice(1);
}

var ruta;
var nombre;
function DownloadOrDelete(archivo, noDescarga) {
    nombre = archivo;
    if (noDescarga == true) {
        $('#btnDescargar').hide();
    }
    else { $('#btnDescargar').show(); }
    $('#modalDescarga .modal-footer').show();
}

//descarga del archivo adjunto
function descargarArchivo(url, nombre) {
    $('#modalDescarga').modal('hide');
    $('#modalDescarga .modal-footer').hide();
    Swal.fire({
        title: '',
        text: "Archivo descargado con éxito",
        icon: 'success',
    });
    var ruta = "../../" + url + nombre;
    var enlaceTemporal = $('<a></a>')
        .attr('href', ruta)
        .attr('download', nombre)
        .appendTo('body');
    enlaceTemporal[0].click();
    enlaceTemporal.remove();
}

function eliminarArchivo(url, nombre) {
    $('#modalDescarga').modal('hide');
    $('#modalDescarga .modal-footer').hide();
    var parametros = {
        'nombre': nombre,
        'ruta': ruta,
        'codigo': miCodigo,
    };
    Swal.fire({
        title: 'Formulario de confirmación',
        text: "(SI) Eliminar el archivo: " + nombre + "\n(NO) Cancelar",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'SI',
        cancelButtonText: 'NO'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: '../controlador/inventario/registro_beneficiarioC.php?EliminaArchivosTemporales=true',
                dataType: 'json',
                data: { 'parametros': parametros },
                success: function (data) {
                    if (data.res == 0) {
                        Swal.fire({
                            title: 'Archivo eliminado con éxito',
                            text: '',
                            icon: 'success',
                        });
                        nombreArchivo = data.res2;
                        $('#modalDescarga .modal-footer').hide();
                    }
                }
            });
        }
    });
}

var contador = 0;
$('#descargarArchivo').click(function () {
    if (miCliente) {
        $('#modalDescarga').modal('show');
        $.ajax({
            url: '../controlador/inventario/registro_beneficiarioC.php?descargarArchivo=true',
            type: 'post',
            dataType: 'json',
            data: { valor: nombreArchivo },
            success: function (data) {
                ruta = data.dir;
                $('#modalDescContainer').empty();
                $('#divNoFile').empty();
                var archivosEncontrados = data.archivos.length;
                var archivosNoEncontrados = data.archivosNo.length;
                contador = archivosEncontrados + archivosNoEncontrados;
                if (data.archivos.length > 0) {
                    var buttonsHTML = '';
                    data.archivos.forEach(function (archivo) {
                        var extension = archivo.split('.').pop().toLowerCase();
                        var iconSrc;
                        switch (extension) {
                            case 'pdf':
                                iconSrc = '../../img/png/pdf_icon.png';
                                break;
                            case 'doc':
                            case 'docx':
                                iconSrc = '../../img/png/doc_icon.png';
                                break;
                            case 'png':
                            case 'jpg':
                                iconSrc = '../../img/png/jpg_icon.png';
                                break;
                            default:
                                iconSrc = '../../img/png/file_icon.png';
                                break;
                        }
                        var maxLength = 12;

                        var truncatedFileName = archivo.length > maxLength ? archivo.substr(0, maxLength) + '...' : archivo;
                        var buttonHTML = '<div class="col-md-4 col-sm-4">' +
                            '<button style="margin-right:50px" title="clic para descargar o eliminar" type="button" class="btn btn-default btn-sm btnsDD"' +
                            'onclick="DownloadOrDelete(\'' + archivo + '\')">' +
                            '<img src="' + iconSrc + '" style="width: 60px;height: 60px;">' +
                            '</button><br>' +
                            '<b title="' + archivo + '">' + truncatedFileName + '</b>' +
                            '</div>';
                        buttonsHTML += buttonHTML;
                    });
                    $('#modalDescContainer').html('<div class="row">' + buttonsHTML + '</div>');
                    if (archivos.length < 3) {
                        $('#modalDescContainer').addClass('justify-content-center');
                    } else {
                        $('#modalDescContainer').removeClass('justify-content-center');
                    }
                }
                if (data.archivosNo.length > 0) {
                    var archivosNoEncontradosHTML = '<ul class="list-unstyled"><b>Archivos no encontrados en el directorio:</b>';
                    data.archivosNo.forEach(function (archivoNoEncontrado) {
                        archivosNoEncontradosHTML += '<li><span class="text-danger">' + archivoNoEncontrado + '</span></li>';
                    });
                    archivosNoEncontradosHTML += '</ul>';
                    $('#divNoFile').append(archivosNoEncontradosHTML);
                }
            }
        });
    } else {
        Swal.fire({
            title: 'Seleccione un nombre de Beneficiario/Usuario o CI/RUC',
            text: '',
            icon: 'error',
        });
    }
});


$('#divNoFile').on('click', 'span.text-danger', function () {
    var archivoClic = $(this).text();
    DownloadOrDelete(archivoClic, true);
});

   function addCliente(otherName = 0) {
      var src = "../vista/modales.php?FCliente=true";
      $('#FCliente').attr('src', src);
      if (otherName == 0) {
        $("#myModal").modal("show");
      } else {
        $(".myModalNuevoCliente").modal("show");
      }
    }
  //===============================================================================================================================//
var valoresFilas = [];

$(document).ready(function () {
    LlenarSelectDiaEntrega();
    Tipo_Beneficiario();
    // LlenarTipoDonacion();
    ddl_estados();
    Tipo_Entrega();
    Frecuencia()
    FrecuenciaAli();
    Accion_Social()
    Vulnerabilidad()
    Tipo_Atencion()
    Programa()
    estado_civilPrincipal()

     $('#select_93').on('select2:select', function (e) {
        var data = e.params.data.data;      
        console.log(data);  
        $('#select_CxClabel').text(data.DC)        
        $('#select_CxC').val(data.DC)        
        $("#img_Tipo_Donacion").attr("src", "../../img/png/"+data.DC.toLowerCase()+'.png');
        actualizarEstilo(data.Color);   
        CargarEstilo();     
        $("#img_Tipo_Beneficiario").attr("src", "../../img/png/"+data.Picture+'.png');
        LimpiarPanelOrgSocialAdd();
        // if (id == "93.04") abrirModal('pAliado');
        $('#cedula').val($('#txt_ci').val());

         nombre = $('#cliente').val().split(" ");
        //para familias
        if(nombre.length>1)
        {
            switch(nombre.length)
            {
                case 2:
                    $('#nombres').val(nombre[0]);
                    $('#apellidos').val(nombre[1]);
                    break;
                case 3:
                    $('#nombres').val(nombre[0]);
                    $('#apellidos').val(nombre[1]+" "+nombre[2]);
                    break;
                case 4:
                    $('#nombres').val(nombre[0]+" "+nombre[1]);
                    $('#apellidos').val(nombre[2]+" "+nombre[3]);
                    break;
                default:            
                    $('#nombres').val(datos.cliente.label);
                    break;
            }
        }
    });

    $('#select_87').on('select2:select', function (e) {
        var data = e.params.data.data;
        console.log(data)
        $("#img_estado_beneficiario").attr("src", "../../img/png/"+data.Picture+'.png');
        // if (id == "93.04") abrirModal('pAliado');
    });


     $("#cliente").autocomplete({
      source: function( request, response ) {
                
            $.ajax({
                url: '../controlador/inventario/registro_beneficiarioC.php?LlenarSelectRucCliente=true',
                type: 'get',
                dataType: "json",
                data: {
                    query: request.term,
                },
                success: function( data ) {
                  // console.log(data);
                    response( data );
                }
            });
        },
        select: function (event, ui) {

            LimpiarPanelOrgSocialAdd();
            $('#txt_ci').val(ui.item.value); // display the selected text
            $('#cliente').val(ui.item.label); // save selected id to input
            $('#txt_id').val(ui.item.ID)
            ClienteAllData(ui.item.Codigo);

            return false;
        },
        focus: function(event, ui){
            $("#txt_ci").val(ui.item.value);
            $('#cliente').val(ui.item.label); // save selected id to input
            return false;
        },
    });

});


function actualizarEstilo(colorValor) {
    if (colorValor) {
        var hexColor = colorValor.substring(4);
        var darkerColor = darkenColor(hexColor, 20);
        $('.accordion-body').css('background-color', '#' + hexColor);
        $('.accordion-header button, .modal-header, .table thead').css('background-color', darkerColor);
    } else {
        $('.accordion-body').css('background-color', '#fffacd');
        $('.accordion-header button, .modal-header, .table thead').css('background-color', '#f3e5ab');
    }
}

function CargarEstilo()
{
    var valorSeleccionado = $('#select_93').val();
    switch (valorSeleccionado) {
        case '93.01':
            $('.campoSocial').show();
            $('.campoFamilia').hide();
            $('.campoAliados').hide();
            // $('.campoVolNo').show();
            break;
        case '93.02':
            $('.campoSocial').hide();
            $('.campoAliados').hide();
            $('.campoFamilia').show();            
            nivel_escolaridadprincipal();
            // $('.campoVolNo').show();
            break;
        case '93.03':
            $('.campoSocial').hide();
            $('.campoFamilia').hide();
            // $('.campoVolNo').hide();
            $('.campoAliados').hide();
            break;
        case '93.04':
            $('.campoSocial').hide();
            $('.campoFamilia').hide();
            $('.campoAliados').show();
            break;
        default:
            $('.campoSocial').hide();
            $('.campoFamilia').hide();
            $('.campoVoluntario').hide();
            break;
    }
}

//limpieza
function LimpiarPanelOrgSocialAdd() {
    eventosEliminados = [];
    eventosEditados = [];
    eventosCreados = [];
    userAuth = false;
    userNew = false;
    $('#collapseTwo').collapse('hide');
    $('#select_92').val(null).trigger('change');
    $('#select_86').val(null).trigger('change');
    $('#select_88').val(null).trigger('change');
    $('#select_89').val(null).trigger('change');
    $('#select_90').val(null).trigger('change');
    //$('#select_91').val(null).trigger('change');
    $('#archivoAdd').val('');
    $('#diaEntregac').val('');
    $('#horaEntregac').val('');
    $('#totalPersonas').val('');
    $('#infoNut').val('');
    $('#comentario').val('');
    comen = '';
    nombreArchivo = '';
    ruta = '';
    nombre = '';
    valoresFilas = [];
    $('#modalDescarga .modal-footer').hide();
    CargarEstilo()
}





function Tipo_Beneficiario()
{
  $('#select_93').select2({
        placeholder: 'Seleccione Tipo Beneficiario',
        width:'100%',
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?Tipo_Beneficiario=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}

function ClienteAllData(codigo)
{
    $('#myModal_espera').modal('show');
      $.ajax({
        url:   '../controlador/inventario/registro_beneficiarioC.php?datosClienteaAll=true',
        type: 'post',
        dataType: 'json',
        data: { valor: codigo },
        success: function (datos) {
           console.log(datos);
          
            llenarCamposInfo(datos);
        }
    })
}

function LlenarTipoDonacion() {
    $('#select_CxC').select2({
        placeholder: 'Seleccione Tipo donacion',
        width:'100%',
        ajax: {
            url: '../controlador/inventario/registro_beneficiarioC.php?LlenarTipoDonacion=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function ddl_estados()
{
    $('#select_87').select2({
        placeholder: 'Seleccione estado',
        width:'100%',
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?ddl_estados=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}

function Tipo_Entrega()
{
    $('#select_88').select2({
        placeholder: 'Seleccione Tipo entrega',
        width:'100%',
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?Tipo_Entrega=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}

function Frecuencia()
{
    $('#select_86').select2({
        placeholder: 'Seleccione Frecuencia',
        width:'100%',
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?Frecuencia=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}

function FrecuenciaAli()
{
    $('#ddl_frecuenciaAli').select2({
        placeholder: 'Seleccione Frecuencia',
        width:'100%',
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?Frecuencia=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}

function Accion_Social()
{
    $('#select_92').select2({
        placeholder: 'Seleccione Accion social',
        width:'100%',
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?Accion_Social=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}


function Vulnerabilidad()
{
    $('#select_90').select2({
        placeholder: 'Seleccione vulnerabilidad',
        width:'100%',
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?Vulnerabilidad=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}

function Tipo_Atencion()
{
    $('#select_89').select2({
        placeholder: 'Seleccione tipo atencion',
        width:'100%',
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?Tipo_Atencion=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}

function Programa()
{
    $('#select_85').select2({
        placeholder: 'Seleccione programa',
        width:'100%',
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?programas=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}

function grupos()
{
    var programa = $('#select_85').val();
    $('#grupo').select2({
        placeholder: 'Seleccione una beneficiario',
        width:'100%',
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?ddl_grupos=true&programa='+programa,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}

// ============================================ para familias ==========================================

function parentesco()
{
    $('#nuevoParentesco').select2({
        placeholder: 'Seleccione parentesco',
        width:'100%',
        dropdownParent: $('#modalEstructuraFam'),
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?parentesco=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });

}
function sexo()
{
    $('#nuevoGenero').select2({
        placeholder: 'Seleccione genero',
        width:'100%',
        dropdownParent: $('#modalEstructuraFam'),
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?sexo=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });

}
function rango_edades()
{
    $('#nuevoRangoEdad').select2({
        placeholder: 'Seleccione rango edad',
        width:'100%',
        dropdownParent: $('#modalEstructuraFam'),
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?rango_edades=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });

}
function estado_civilPrincipal()
{
    $('#estadoCivil').select2({
        placeholder: 'Seleccione estado civil',
        width:'100%',
        // dropdownParent: $('#modalEstructuraFam'),
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?estado_civil=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}
function estado_civil()
{
    $('#nuevoEstadoCivil').select2({
        placeholder: 'Seleccione estado civil',
        width:'100%',
        dropdownParent: $('#modalEstructuraFam'),
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?estado_civil=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}
function nivel_escolaridadprincipal()
{
    $('#nivelEscolar').select2({
        placeholder: 'nivel escolaridad',
        width:'100%',
        // dropdownParent: $('#modalEstructuraFam'),
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?nivel_escolaridad=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });

}

function nivel_escolaridad()
{
    $('#nuevoNivelEscolaridad').select2({
        placeholder: 'nivel escilaridad',
        width:'100%',
        dropdownParent: $('#modalEstructuraFam'),
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?nivel_escolaridad=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });

}
function tipo_institucion()
{
    $('#nuevoTipoInstitucion').select2({
        placeholder: 'Tipo Institucion',
        width:'100%',
        dropdownParent: $('#modalEstructuraFam'),
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?tipo_institucion=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });

}
function vulnerabilidad()
{
    $('#nuevaVulnerabilidad').select2({
        placeholder: 'Seleccione vulnerabilidad',
        width:'100%',
        dropdownParent: $('#modalEstructuraFam'),
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?vulnerabilidadFam=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });

}
function  tipo_enfermedad()
 {
    $('.tipoEnfermedad').select2({
        placeholder: 'Seleccione tipo enfermedad',
        width:'100%',
        dropdownParent: $('#modalVulnerabilidadFam'),
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?tipo_enfermedad=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });

 }
function tipo_discapacidad()
{
    $('.tipoDiscapacidad').select2({
        placeholder: 'Seleccione tipo discapacidad',
        width:'100%',
        dropdownParent: $('#modalVulnerabilidadFam'),
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?tipo_discapacidad=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });

}
// ================================================= fin para familias =================================

function llenarCamposInfo(datos) {
    $('#btn_editar_ci').attr('disabled',false);
    //console.log(datos);
     $('#txt_id_datos_extra').val(datos.cliente_datos_extra.ID);
    actualizarEstilo(datos.Tipo_Beneficiario.Color)
    nombre = datos.cliente.label.split(" ");
    //para familias
    if(nombre.length>1)
    {
        switch(nombre.length)
        {
            case 2:
                $('#nombres').val(nombre[0]);
                $('#apellidos').val(nombre[1]);
                break;
            case 3:
                $('#nombres').val(nombre[0]);
                $('#apellidos').val(nombre[1]+" "+nombre[2]);
                break;
            case 4:
                $('#nombres').val(nombre[0]+" "+nombre[1]);
                $('#apellidos').val(nombre[2]+" "+nombre[3]);
                break;
            default:            
                $('#nombres').val(datos.cliente.label);
                break;
        }
    }

    if(datos.Tipo_Beneficiario)
    {
        $('#select_93').append($('<option>',{value: datos.Tipo_Beneficiario.Cmds, text: datos.Tipo_Beneficiario.Proceso,selected: true }));
        if(datos.Tipo_Beneficiario.Picture!='' && datos.Tipo_Beneficiario.Picture !='.'  && datos.Tipo_Beneficiario.Picture !=undefined )
        {
            $('#img_Tipo_Beneficiario').attr("src",'../../img/png/'+datos.Tipo_Beneficiario.Picture+'.png')
        }
        
        if(datos.Tipo_Beneficiario.DC!='' && datos.Tipo_Beneficiario.DC !='.'  && datos.Tipo_Beneficiario.DC !=undefined )
        {

            $('#select_CxClabel').text(datos.Tipo_Beneficiario.DC);        
            $('#select_CxC').val(datos.Tipo_Beneficiario.DC);
            $("#img_Tipo_Donacion").attr("src", "../../img/png/"+datos.Tipo_Beneficiario.DC.toLowerCase()+'.png');
        }


    }    
    // if(datos.tipoDonacion){
    //     if(datos.tipoDonacion.Picture!='' && datos.tipoDonacion.Picture !='.' && datos.tipoDonacion.Picture !=undefined)
    //     {
    //         $('#img_Tipo_Donacion').attr("src",'../../img/png/'+datos.tipoDonacion.Picture+'.png')
    //     }
    // }
    if(datos.Estado_Beneficiario)
    {
        $('#select_87').append($('<option>',{value: datos.Estado_Beneficiario.Cmds, text: datos.Estado_Beneficiario.Proceso,selected: true }));
        if(datos.Estado_Beneficiario.Picture!='' && datos.Estado_Beneficiario.Picture !='.'  && datos.Estado_Beneficiario.Picture !=undefined)
        {
            $('#img_estado_beneficiario').attr("src",'../../img/png/'+datos.Estado_Beneficiario.Picture+'.png')
        }
    }

    CargarEstilo();

    prov = (datos.cliente.Prov !== '.') ? datos.cliente.Prov : null;
    ciud = (datos.cliente.Ciudad !== '.') ? datos.cliente.Ciudad : null;

    $('#Canton').val(datos.cliente.Canton);
    $('#Parroquia').val(datos.cliente.Parroquia);
    $('#Barrio').val(datos.cliente.Barrio);
    $('#CalleP').val(datos.cliente.Direccion);
    $('#CalleS').val(datos.cliente.DireccionT);
    $('#Referencia').val(datos.cliente.Referencia);
    $('#txt_codigo').val(datos.cliente.Codigo);
    if(datos.cliente.Fecha_Bloq!=null)
    {
        $('#fechaDesviculacion').val(formatoDate(datos.cliente.Fecha_Bloq.date));
    }

    switch(datos['Tipo_Beneficiario']['Cmds'])
    {
        case "93.01":
            if(datos.Tipo_Entrega){
                $('#select_88').append($('<option>',{value: datos.Tipo_Entrega.Cmds, text: datos.Tipo_Entrega.Proceso,selected: true }));
            }    
            if(datos.Frecuencia){
                $('#select_86').append($('<option>',{value: datos.Frecuencia.Cmds, text: datos.Frecuencia.Proceso,selected: true }));
            }
            if(datos.Accion_Social){
                $('#select_92').append($('<option>',{value: datos.Accion_Social.Cmds, text: datos.Accion_Social.Proceso,selected: true }));
            }
            if(datos.Vulnerabilidad){
                $('#select_90').append($('<option>',{value: datos.Vulnerabilidad.Cmds, text: datos.Vulnerabilidad.Proceso,selected: true }));
            }
            if(datos.Tipo_Atencion){
                $('#select_89').append($('<option>',{value: datos.Tipo_Atencion.Cmds, text: datos.Tipo_Atencion.Proceso,selected: true }));
            }

            $('#nombreRepre').val(datos.cliente.Representante);
            $('#ciRepre').val(datos.cliente.CI_RUC_R);
            if(datos.cliente.TD!='' && datos.cliente.TD!='.')
            {
                $('#txt_td').val(datos.cliente.TD);
            }else
            {
                // validar_registro()
                codigo();
            }
            $('#telfRepre').val(datos.cliente.Telefono_R);
            $('#contacto').val(datos.cliente.Contacto);
            $('#cargo').val(datos.cliente.Profesion);
            $('#email').val(datos.cliente.Email);
            $('#email2').val(datos.cliente.Email2);
            $('#telefono').val(datos.cliente.Telefono);
            $('#telefono2').val(datos.cliente.TelefonoT);

            if (datos.Sexo == '.') {
                $('#sexo').val($('#sexo option:first').val());
            } else {
                $('#sexo').val(datos.cliente.Sexo);
            }
           
            if (datos.cliente.Dia_Ent == '.') {
                $('#diaEntrega').val($('#diaEntrega option:first').val());
            } else {
                $('#diaEntrega').val(datos.cliente.Dia_Ent);
            }
            if (/^\d{2}:\d{2}$/.test(datos.cliente.Hora_Ent)) {
                $('#horaEntrega').val(datos.cliente.Hora_Ent);
            }
            
            $('#diaEntregac').val(datos.cliente_datos_extra.Dia_Ent2);
            $('#horaEntregac').val(datos.cliente_datos_extra.Hora_Ent2);
            $('#totalPersonas').val(datos.cliente_datos_extra.No_Soc);
            $('#comentario').val(datos.cliente_datos_extra.Etapa_Procesal);
            comen = datos.cliente_datos_extra.Etapa_Procesal;
            $('#infoNut').val(datos.cliente_datos_extra.Observaciones);
            nombreArchivo = datos.cliente_datos_extra.Evidencias;
            llenarCamposPoblacion(datos.cliente.Codigo);


            break;
        case "93.02":

            if(datos.Programas){
                $('#select_85').append($('<option>',{value: datos.Programas.Cmds, text: datos.Programas.Proceso,selected: true }));
            }
            if(datos.Grupo){
                grupos();                
                $('#grupo').append($('<option>',{value: datos.Grupo.Cmds, text: datos.Grupo.Proceso,selected: true }));
                // grupos();

            }
            if(datos.EstadoCivil){
                $('#estadoCivil').append($('<option>',{value: datos.EstadoCivil.Codigo, text: datos.EstadoCivil.Descripcion,selected: true }));
            }

            if(datos.Escolaridad){
                $('#nivelEscolar').append($('<option>',{value: datos.Escolaridad.Codigo, text: datos.Escolaridad.Descripcion,selected: true }));
            }

            //----------- datos de familias --------------//

            $('#cedula').val(datos.cliente.CI_RUC);
            $('#edad').val(datos.cliente.Dosis);
            $('#emailFam').val(datos.cliente.Email);
            $('#telefonoFam').val(datos.cliente.Telefono);
            $('#ocupacion').val(datos.cliente.Profesion);
            $('#estadoCivil').val(datos.cliente.Est_Civil);
            $('#pregunta').val(datos.cliente.Contacto);
            //----------- datos de familia --------------//
             //--------------- Datos extra familias ----------------------------//
            $('#numHijosI').val(datos.cliente_datos_extra.Num);
            if(datos.cliente_datos_extra.Num>0)
            {
                const hijosAct = $('.hijosAct');
                hijosAct.show();
            }
            $('#pregunta').val(datos.cliente_datos_extra.Observaciones);
            $('#numHijosMayores').val(datos.cliente_datos_extra.Credito_No);
            $('#numHijosMenores').val(datos.cliente_datos_extra.Cuenta_No);
            $('#numPersonas').val(datos.cliente_datos_extra.Etapa_Procesal);
            $('#comentarioAct').val(datos.cliente_datos_extra.No_Juicio);
            $('#comentarioConyugeAct').val(datos.cliente_datos_extra.Causa);
            $('#modalidadSelect').val(datos.cliente_datos_extra.Sujeto_Procesal);
            $('#modalidadConyugeSelect').val(datos.cliente_datos_extra.Agente_Fiscal);
            $('#modalidadConyugeSelect').val(datos.cliente_datos_extra.Instruccion_Fiscal);

            //--------------- Datos extra familias ----------------------------//
            llenarCamposExtructuraFami(datos.cliente.Codigo);

            break;
         case "93.04":
             if(datos.Frecuencia){
                $('#ddl_frecuenciaAli').append($('<option>',{value: datos.Frecuencia.Cmds, text: datos.Frecuencia.Proceso,selected: true }));
            }
             if (datos.cliente.Dia_Ent == '.') {
                $('#diaEntregaAli').val($('#diaEntrega option:first').val());
            } else {
                $('#diaEntregaAli').val(datos.cliente.Dia_Ent);
            }
            if (/^\d{2}:\d{2}$/.test(datos.cliente.Hora_Ent)) {
                $('#horaEntregaAli').val(datos.cliente.Hora_Ent);
            }

            $('#txt_emailAli').val(datos.cliente.Email);
            $('#txt_telefonoAli').val(datos.cliente.Telefono);

            break;
        default:
            break;
    }

       
     setTimeout(() => { $('#myModal_espera').modal('hide');  }, 1000);
 }
   

//selects Dia de Entrega
function LlenarSelectDiaEntrega() {
    $.ajax({
        url: '../controlador/inventario/registro_beneficiarioC.php?LlenarSelectDiaEntrega=true',
        type: 'post',
        dataType: 'json',
        success: function (datos) {
            $('#diaEntregac').append('<option value="" disabled selected>Seleccione una opción</option>');
            $('#diaEntrega').append('<option value="" disabled selected>Seleccione una opción</option>');
            $('#diaEntregaAli').append('<option value="" disabled selected>Seleccione una opción</option>');

            $.each(datos, function (index, opcion) {
                $('#diaEntregac').append('<option value="' + opcion['Dia_Mes_C'] + '">' + opcion['Dia_Mes'] + '</option>');
                $('#diaEntrega').append('<option value="' + opcion['Dia_Mes_C'] + '">' + opcion['Dia_Mes'] + '</option>');
                $('#diaEntregaAli').append('<option value="' + opcion['Dia_Mes_C'] + '">' + opcion['Dia_Mes'] + '</option>');
            });
        }
    });
}

function llenarCamposPoblacion(Codigo) {
    $.ajax({
        url: '../controlador/inventario/registro_beneficiarioC.php?llenarCamposPoblacion=true',
        type: 'post',
        dataType: 'json',
        data: { valor: Codigo },
        success: function (datos) {
            if (datos != 0) {
                total_global = 0;
                datos.forEach(function (registro) {
                    var hombres = registro.Hombres;
                    var mujeres = registro.Mujeres;
                    var total = registro.Total;
                    total_global = total+total_global;
                    var valueData = registro.Cmds;
                    valoresFilas.push({ hombres, mujeres, total, valueData });
                });
                $('#totalPersonas').val(total_global);
            }
        }
    });
}

function llenarCamposExtructuraFami(Codigo)
{
     $.ajax({
        url: '../controlador/inventario/registro_beneficiarioC.php?llenarCamposExtructuraFami=true',
        type: 'post',
        dataType: 'json',
        data: { valor: Codigo },
        success: function (datos) {
            if (datos != 0) {
                integrantes = datos;
                actualizarTabla();
            }
        }
    });

}

function validar_registro()
{
  var ci_ruc = $('#txt_ci').val();
  if (ci_ruc == '' || ci_ruc == '.') {
    return false;
  }
  $.ajax({
    url: '../controlador/modalesC.php?buscar_cliente=true',
    type: 'post',
    dataType: 'json',
    data: { search: ci_ruc },
    beforeSend: function () {
      $("#myModal_espera").modal('show');
    },
    success: function (response) {
        console.log(response)
      if (response.length > 0) {

            $('#txt_id').val(response[0].value);
            $('#txt_codigo').val(response[0].codigo);
            $('#txt_td').val(response[0].TD);    
            $('#cliente').val(response[0].nombre);
            ClienteAllData(response[0].codigo);
            $("#myModal_espera").modal('hide');
           

      } else {
        $('#cliente').val("");
        limpiar_info_general();
        codigo();
      }

      setTimeout(()=>{
            $('#myModal_espera').modal('hide');
        }, 1000)

    },
    error: (err) => {
        setTimeout(()=>{
            $('#myModal_espera').modal('hide');
        }, 1000)
        Swal.fire('Error', 'Hubo un problema al guardar la factura.','info');
    }
  });
}

function codigo() {
  // $("#myModal_espera").modal('show');
  var ci = $('#txt_ci').val();
        $.ajax({
          url: '../controlador/modalesC.php?codigo=true',
          type: 'post',
          dataType: 'json',
          data: { ci: ci },
          beforeSend: function () {
            // $("#myModal_espera").modal('show');
          },
          success: function (response) {
            $('#txt_codigo').val(response.Codigo_RUC_CI);
             if(response.Tipo_Beneficiario=='P')
            {
                Swal.fire({
                  title: 'Numero de cedula inconsistente?',
                  text: "Desea registrar como numero de cedula?",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Si!'
                }).then((result) => {
                  if (result.value==true) {
                    $('#txt_td').val("C");    
                  }else
                  {
                    $('#txt_td').val(response.Tipo_Beneficiario);
                  }
                })

            }else
            {
                $('#txt_td').val(response.Tipo_Beneficiario);          
            }
            $("#myModal_espera").modal('hide');


          }
        });
}

function guardar_registros()
{
    tipoBeneficiario = $('#select_93').val();
    var parametros;
    data = $('#form_data').serialize();
    dataD = $('#form_direccion').serialize();
    switch(tipoBeneficiario){
        case '93.01':            
            poblacion = JSON.stringify(valoresFilas);
            parametros = 
            {
                'data':data,
                'dataD':dataD,
                'poblacion':poblacion,
            }

             if($('#txt_ci').val()=='' ||
            $('#cliente').val()=='' ||
            $('#select_93').val()=='' ||
            $('#select_87').val()=='' ||
            $('#nombreRepre').val()=='' ||
            $('#ciRepre').val()=='' ||
            $('#telfRepre').val()=='' ||
            $('#contacto').val()=='' ||
            $('#cargo').val()=='' ||
            $('#diaEntrega').val()=='' ||
            $('#horaEntrega').val()=='' ||
            $('#email').val()=='' ||
            $('#telefono').val()=='' ||
            $('#diaEntregac').val()=='' ||
            $('#horaEntregac').val()=='')
            {
                Swal.fire("No se pudo guardar","Llene todo los campos obligatorios de informacion general e informacion Adicional","error")
                return false;
            }

            break;
        case '93.02':
            data_info_user = $('#form_info_usuario').serialize();
            parametros = 
            {
                'data':data,
                'dataD':dataD,
                'data_info':data_info_user,
                'estru_fam':integrantes
            }
            break;
        case '93.04':
            // data_info_user = $('#form_info_usuario').serialize();
            parametros = 
            {
                'data':data,
                'dataD':dataD,
                // 'data_info':data_info_user,
                // 'estru_fam':integrantes
            }
            break;
        default:
            Swal.fire({
                title: 'ERROR',
                text: 'Porfavor seleccione un Tipo de Beneficiario',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        break;
    }

    $('#myModal_espera').modal('show');
      $.ajax({
        url: '../controlador/inventario/registro_beneficiarioC.php?guardarAsignacion1=true',
        type: 'post',
        dataType: 'json',
        data: { parametros: parametros },
        success: function (datos) {
            $('#myModal_espera').modal('hide');
            if(datos==1)
            {
                Swal.fire("Datos Guardados Correctamente","","success").then(function(){
                    location.reload();
                })
            }else if(datos==-4)
            {
                Swal.fire("No se pudo guardar","El nombre ya esta registrado","info")
                setTimeout(() => { $('#myModal_espera').modal('hide');  }, 1000);
            }
        },
        error: function(error){

            $('#myModal_espera').modal('hide');
             Swal.fire("No se pudo guardar","Consulte con el distribuidor de servicio","error")
        }
    })
}

function modal_estructura_fami()
{
    parentesco()
    sexo()
    rango_edades()
    estado_civil()
    nivel_escolaridad()
    tipo_institucion()
    vulnerabilidad()
     tipo_enfermedad()
    tipo_discapacidad()
    $('#modalEstructuraFam').modal('show');
}


function iconVulnerabilidadFam() {

    // console.log(integrantes)
    if (integrantes.length > 0) {
  
        listaDiscapacidad = []
        listaEnfermedad = [];
        integrantes.forEach(function(item,i){
            console.log(item)
            console.log(i)
            if(item.vulnerabilidad.toUpperCase()=='DISCAPACIDAD')
            {
                listaDiscapacidad.push(item);
            }
            if(item.vulnerabilidad.toUpperCase()=='ENFERMEDAD')
            {
                listaEnfermedad.push(item);
            }
        })

        //crea tabla discapacidad
        trd = '';
        listaDiscapacidad.forEach(function(item,i){
            nomdiscapacidad = '';
            porcediscapacidad = '';
            op = '';

            if (item.hasOwnProperty('nomdiscapacidad')) {
                nomdiscapacidad = item.nomdiscapacidad;
            }
            if (item.hasOwnProperty('porcediscapacidad')) {
                porcediscapacidad = item.porcediscapacidad;
            }
            if(item.hasOwnProperty('tipodiscapacidadid')) {
                op = '<option value="'+item.tipodiscapacidadid+'">'+item.tipodiscapacidad+'</option>';
            }

             trd+=`<tr><td>`+item.nombre+`</td>
                        <td><input type='text' class='form-control  form-control-sm' id='nombreDiscapacidad_`+item.id+`' value='`+nomdiscapacidad+`'></td>
                        <td><select class="form-select tipoDiscapacidad" id='tipoDiscapacidad_`+item.id+`'>`+op+`</select></td>
                        <td><input type='text' class='form-control  form-control-sm' id='porDiscapacidad_`+item.id+`' value = '`+porcediscapacidad+`'></td>
                        <td><button class="btn-sm btn btn-primary" onclick="add_datoDis(`+item.id+`)"><i class="bx bx-save m-0"></i></button></td>
                    </tr>`
           
            $("#mensajeNoIntegrantes").hide();
        })
        $('#tablaFamDisc').html(trd);


        // crea tablas efermerdades
        trf = '';
        listaEnfermedad.forEach(function(item,i){
            nomEnfermedad = '';
            op = '';
            if (item.hasOwnProperty('nomEnfermedad')) {
                nomEnfermedad = item.nomEnfermedad;
            }
            if (item.hasOwnProperty('tipoEnfermedadid')) {                
                op = '<option value="'+item.tipoEnfermedadid+'">'+item.tipoEnfermedad+'</option>';
            }
            trf+=`<tr><td>`+item.nombre+`</td>
                        <td><input type='text' class='form-control form-control-sm' id='nombreEnfermedad_`+item.id+`' value='`+nomEnfermedad+`' ></td>
                        <td><select class="form-select tipoEnfermedad" id='tipoEnfermedad_`+item.id+`'>`+op+`</select></td>
                        <td><button class="btn-sm btn btn-primary" onclick="add_datoEnf(`+item.id+`)"><i class="bx bx-save m-0"></i></button></td>
                    </tr>`
           
            $("#mensajeNoIntegrantesE").hide();
        })


        $('#totalFamVuln').val(listaEnfermedad.length+listaDiscapacidad.length);
        $('#tablaFamEnfe').html(trf);
        tipo_enfermedad();  //inicializa los select2
        tipo_discapacidad();  //inicializa los select2
        $('#modalVulnerabilidadFam').modal('show');
      } else {
        var nombreSol = $('#nombres').val();
        swal.fire('', 'No hay integrantes para el Sr.(a) ' + nombreSol, 'info');
    }
}

function add_datoDis(id)
{
    if($('#nombreDiscapacidad_'+id).val()=='' || $('#tipoDiscapacidad_'+id).val()=='' || $('#porDiscapacidad_'+id).val()=='')
    {
        Swal.fire("Ingrese todos los datos","","info");
    }else
    {
        integrantes.forEach(function(item,i){
            if(item.id==id)
            {
                item['nomdiscapacidad'] = $('#nombreDiscapacidad_'+id).val();
                item['tipodiscapacidadid'] = $('#tipoDiscapacidad_'+id).val();
                item['tipodiscapacidad'] = $('#tipoDiscapacidad_'+id+' option:selected').text();
                item['porcediscapacidad'] = $('#porDiscapacidad_'+id).val();
            }
        })

    }

}
function add_datoEnf(id)
{
    if( $('#nombreEnfermedad_'+id).val() =='' || $('#tipoEnfermedad_'+id).val()=='')
    {
        Swal.fire("Ingrese todos los datos","","info");
    }else
    {
        integrantes.forEach(function(item,i){
            if(item.id==id)
            {
                item['nomEnfermedad'] = $('#nombreEnfermedad_'+id).val();
                item['tipoEnfermedadid'] = $('#tipoEnfermedad_'+id).val();
                item['tipoEnfermedad'] = $('#tipoEnfermedad_'+id+' option:selected').text();
            }
        })
    }
}
$("#btnAceptarVulnerable").click(function () {
    
    console.log(integrantes);
    $('#modalVulnerabilidadFam').modal('hide');
});

$("#btnAceptarIntegrante").click(function () {
    $('#modalEstructuraFam').modal('hide');
});

var integrantes = [];
var totalVulnerables = 0;
$("#agregarIntegrante").click(function () {

    let integVacios = [];
    if($("#nuevoNombre").val() == "") integVacios.push("Nombres y Apellidos");
    if($("#nuevoGenero").val() == "") integVacios.push("Genero");
    if($("#nuevoParentesco").val() == "") integVacios.push("Parentesco");
    if($("#nuevoRangoEdad").val() == "") integVacios.push("Rango de edad");
    if($("#nuevaOcupacion").val() == "") integVacios.push("Ocupacion");
    if($("#nuevoEstadoCivil").val() == "") integVacios.push("Estado civil");
    if($("#nuevoNivelEscolaridad").val() == "") integVacios.push("Nivel de Escolaridad");
    if($("#nuevoNivelEscolaridad").val() != "01" && $("#nuevoNombreInstitucion").val() == "") integVacios.push("Nombre de la Institución");
    if($("#nuevoNivelEscolaridad").val() != "01" && $("#nuevoTipoInstitucion").val() == "") integVacios.push("Tipo de Institución");
    if($("#nuevaVulnerabilidad").val() == "") integVacios.push("Vulnerabilidad");

    if(integVacios.length > 0){
        Swal.fire('Campos Vacíos', `Rellene los campos: ${integVacios.join(', ')}`, 'error');
        return false;
    }else{
            key = 0;
         i = integrantes.length
         if(i>0)
         {
            key = (integrantes[(i-1)].id+1);
         }

        integrantes[i] = 
        {

            id: key,
            nombre: $("#nuevoNombre").val() || "",
            generoid: $("#nuevoGenero").val() || "",
            genero: $("#nuevoGenero option:selected").text() || "",
            parentescoid: $("#nuevoParentesco").val() || "",
            parentesco: $("#nuevoParentesco option:selected").text() || "",
            rangoEdadid: $("#nuevoRangoEdad").val() || "",
            rangoEdad: $("#nuevoRangoEdad option:selected").text() || "",
            ocupacion: $("#nuevaOcupacion").val() || "",
            estadoCivilid: $("#nuevoEstadoCivil").val() || "",
            estadoCivil: $("#nuevoEstadoCivil option:selected").text() || "",
            nivelEscolaridadid: $("#nuevoNivelEscolaridad").val() || "",
            nivelEscolaridad: $("#nuevoNivelEscolaridad option:selected").text() || "",
            nombreInstitucion: $("#nuevoNombreInstitucion").val() || "",
            tipoInstitucionid: $("#nuevoTipoInstitucion").val() || "", 
            tipoInstitucion: $("#nuevoTipoInstitucion option:selected").text() || "",
            vulnerabilidadid: $("#nuevaVulnerabilidad").val() || "",
            vulnerabilidad: $("#nuevaVulnerabilidad option:selected").text() || "",
        }
        actualizarTabla();
        limpiarCampos();
    }
});


function actualizarTabla() {
    var tablaBody = $("#tablaIntegrantes tbody");
    tablaBody.children(':not(:first)').remove();

    for (var i = 0; i < integrantes.length; i++) {
        var integrante = integrantes[i];
        var fila = $("<tr></tr>");
        fila.append($("<td></td>").text(integrante.nombre));
        fila.append($("<td></td>").text(integrante.genero));
        fila.append($("<td></td>").text(integrante.parentesco));
        fila.append($("<td></td>").text(integrante.rangoEdad));
        fila.append($("<td></td>").text(integrante.ocupacion));
        fila.append($("<td></td>").text(integrante.estadoCivil));
        fila.append($("<td></td>").text(integrante.nivelEscolaridad));
        fila.append($("<td></td>").text(integrante.nombreInstitucion));
        fila.append($("<td></td>").text(integrante.tipoInstitucion));
        fila.append($("<td></td>").text(integrante.vulnerabilidad));
        fila.append($("<td><button type='button' class='btn btn-danger btn-eliminar btn-sm p-0' title='Elimnar registro'><i class='bx bx-trash m-0'></i></button> <button type='button' title='editar registro' class='btn btn-warning btn-editar btn-sm p-0'><i class='bx bx-pen m-0'></i></button></td>"));
        tablaBody.append(fila);
    }
}

function limpiarCampos() {
    $("#nuevoNombre").val("");
    $("#nuevoGenero").empty();
    $("#nuevoParentesco").empty();
    $("#nuevoRangoEdad").empty();
    $("#nuevaOcupacion").val("");
    $("#nuevoEstadoCivil").empty();
    $("#nuevoNivelEscolaridad").empty();
    $("#nuevoNombreInstitucion").val("");
    $("#nuevoNombreInstitucion").attr("disabled", "true");
    $("#nuevoTipoInstitucion").empty();
    $("#nuevoTipoInstitucion").attr("disabled", "true");
    $("#nuevaVulnerabilidad").empty();
}

$("#tablaIntegrantes").on("click", ".btn-eliminar", function () {
    var fila = $(this).closest("tr");
    var index = fila.index() - 1;
    var integrante = integrantes[index];

    if (integrante.vulnerabilidad === "discapacidad") {
        totalVulnerables--;
        integrantesDisc = integrantesDisc.filter(d => d.nombre !== integrante.nombre);
    }

    if (integrante.vulnerabilidad === "enfermedad") {
        totalVulnerables--;
        integrantesEnfe = integrantesEnfe.filter(e => e.nombre !== integrante.nombre);
    }

    integrantes.splice(index, 1);
    actualizarTabla();
});

$("#tablaIntegrantes").on("click", ".btn-editar", function () {
    var fila = $(this).closest("tr");
    var index = fila.index() - 1;
    var integrante = integrantes[index];

    $("#nuevoNombre").val(integrante.nombre);
    $('#nuevoGenero').append($('<option>',{value:integrante.generoid, text:integrante.genero,selected: true }));
    $('#nuevoParentesco').append($('<option>',{value:integrante.parentescoid, text:integrante.parentesco,selected: true }));
    $('#nuevoRangoEdad').append($('<option>',{value:integrante.rangoEdadid, text:integrante.rangoEdad,selected: true }));
    $("#nuevaOcupacion").val(integrante.ocupacion);    
    $('#nuevoEstadoCivil').append($('<option>',{value:integrante.estadoCivilid, text:integrante.estadoCivil,selected: true }));
    $('#nuevoNivelEscolaridad').append($('<option>',{value:integrante.nivelEscolaridadid, text:integrante.nivelEscolaridad,selected: true }));
    $("#nuevoNombreInstitucion").val(integrante.nombreInstitucion);
    $('#nuevoTipoInstitucion').append($('<option>',{value:integrante.tipoInstitucionid, text:integrante.tipoInstitucion,selected: true }));
    $('#nuevaVulnerabilidad').append($('<option>',{value:integrante.vulnerabilidadid, text:integrante.vulnerabilidad,selected: true }));


    if (integrante.vulnerabilidad === "discapacidad") {
        totalVulnerables--;
        integrantesDisc = integrantesDisc.filter(d => d.nombre !== integrante.nombre);
    }

    if (integrante.vulnerabilidad === "enfermedad") {
        totalVulnerables--;
        integrantesEnfe = integrantesEnfe.filter(e => e.nombre !== integrante.nombre);
    }

    integrantes.splice(index, 1);
    actualizarTabla();
});

function validar_sri_registro(ci)
  {
    $('#myModal_espera').modal('show');
    $.ajax({
    data: {ci,ci},
    url: '../controlador/modalesC.php?validar_sri_cliente=true',
    type: 'POST',
    dataType: 'json',
    success: function(response) {
      $('#myModal_espera').modal('hide');
      if(response.res=='1')
        {
          if(response.data){
            if ($('#cliente').length > 0) {
              if($('#cliente').val()!='' && $('#cliente').val()!=response.data.RazonSocial){
                Swal.fire({
                  html: `ESTE RUC ESTA ASIGNADO A:<br>${$('#cliente').val()}<br>
                  LA INFORMACION CORRECTA DEL R.U.C. ES:<br>
                  ${response.data.RazonSocial} <br>
                  ¿Desea actualizar el campo Apellidos y cliente?`,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  cancelButtonText: 'No.',
                  confirmButtonText: 'Si'
                }).then((result) => {
                  $('#cliente').val(response.data.RazonSocial);
                  $("#BtnGuardarClienteFCliente").focus()
                })
              }else{
                $('#cliente').val(response.data.RazonSocial);
              }
            }else
            {
                console.log(response.data);
                $('#cliente').val(response.data.RazonSocial);
            }
          }
        }else
        {
          $('.LblSRI').html('');
          Swal.fire(response.msg,'','info')
        }

      }
    });

  }


function limpiar_info_general()
{
    $('#cliente').val('');
    $('#select_93').empty();
    $('#select_87').empty();
    $('#nombreRepre').val('');
    $('#ciRepre').val('');
    $('#telfRepre').val('');
    $('#contacto').val('');
    $('#cargo').val('');
    $('#diaEntrega').val('');
    $('#horaEntrega').val('');
    $('#email').val('');
    $('#telefono').val('');
    $('#diaEntregac').val('');
    $('#horaEntregac').val('');
    
}

function modalCambioCI(){
    $('#modalCambioCI_RUC').modal('show');
}

function guardar_new_ci()
{
    var parametros = {
        'id': $('#txt_id').val(),
        'ci':$('#txt_ci').val(),
        'td':$('#txt_td_new').val(),
        'codigo':$('#txt_codigo_new').val(),
    }
      $.ajax({
        type: "POST",
        url: '../controlador/inventario/registro_beneficiarioC.php?guardar_new_ci=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            if(data==1)
            {
                $('#modalCambioCI_RUC').modal("hide");
               Swal.fire("Se recargara la pagina","CI / RUC Actualizadas","success").then(function(){
                location.reload();
               })
            }else
            {
               Swal.fire("Se recargara la pagina","No se pudo actualizar","error")                
            }
        }
    });
}

function codigo_new() {
  $("#myModal_espera").modal('show');
  var ci = $('#txt_ci').val();
        $.ajax({
          url: '../controlador/modalesC.php?codigo=true',
          type: 'post',
          dataType: 'json',
          data: { ci: ci },
          beforeSend: function () {
            // $("#myModal_espera").modal('show');
          },
          success: function (response) {
            setTimeout(() => {  $("#myModal_espera").modal('hide');   }, 1000);

            $('#txt_codigo_new').val(response.Codigo_RUC_CI);
             if(response.Tipo_Beneficiario=='P')
            {
                Swal.fire({
                  title: 'Numero de cedula inconsistente?',
                  text: "Desea registrar como numero de cedula?",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Si!'
                }).then((result) => {
                  if (result.value==true) {
                    $('#txt_td_new').val("C");    
                  }else
                  {
                    $('#txt_td_new').val(response.Tipo_Beneficiario);
                  }
                })

            }else
            {
                $('#txt_td_new').val(response.Tipo_Beneficiario);          
            }
            $("#myModal_espera").modal('hide');


          }
        });
}
