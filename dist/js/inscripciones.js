document.addEventListener("DOMContentLoaded", function (){
    let area = $('#form-contenedor').parent().parent();
    area.css('background-color', 'rgb(251, 241, 221)');
    area.css('overflow-y', 'scroll');
    area.css('overflow-x', 'hidden');
    $('#contenedor-cf').parent().css('position', 'relative');
    $.ajax({
        type: "GET",
        url: '../controlador/inscripciones/voluntarios.php?CatalogoForm=true',
        //data: { 'fafactura': faFactura },
        dataType: 'json',
        success: function (data) {
            cargarEncabezado();
            construirFormulario(data);
            setRestricciones();
            $('#contenedor-cf').css('visibility', 'hidden');
        }
    });
})

var voluntario;

function resp_clave_ingreso(response) {
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
    
    /*mostrarBotonesArchivo()
    document.getElementById('05.01').style.visibility = 'hidden';
    document.getElementById('documentoVoluntario-05.01').style.visibility = 'visible';
    document.getElementById('05.02').style.visibility = 'hidden';
    document.getElementById('documentoVoluntario-05.02').style.visibility = 'visible';
    document.getElementById('05.03').style.visibility = 'hidden';
    document.getElementById('documentoVoluntario-05.03').style.visibility = 'visible';
    document.getElementById('05.04').style.visibility = 'hidden';
    document.getElementById('documentoVoluntario-05.04').style.visibility = 'visible';
    document.getElementById('05.05').style.visibility = 'hidden';
    document.getElementById('documentoVoluntario-05.05').style.visibility = 'visible';
    document.getElementById('05.06').style.visibility = 'hidden';
    document.getElementById('documentoVoluntario-05.06').style.visibility = 'visible';
    document.getElementById('05.07').style.visibility = 'hidden';
    document.getElementById('documentoVoluntario-05.07').style.visibility = 'visible';*/

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

function cargarEncabezado(){
    let encabezado = `
    <div class="f-seccion encabezado">
        <h1>Formulario de Inscripción Voluntario Operativo</h1>
        <p>
            Estimado/a postulante por favor llenar el formulario 
            correctamente, si al final le falta completar algún 
            campo el sistema no le permitirá enviar la información. 
            Lea detenidamente la información de cada una de los campos 
            y secciones.
        </p>
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
                <div class="f-seccion fc-pregunta">
                    <h2>${capitalizarString(seccion['Cuenta'])}</h2>
                    <p>${formatearComentarioVol((seccion['Comentario'] == '.' || seccion['Comentario'] == null) ? '' : seccion['Comentario'])}</p>
                    <img class="center-block" src="../../img/inscripcion/${seccion['Imagen']}.png" alt="" height="250px">
                </div>
            `;
        }else{
            cuerpoHtml += `
                <div class="f-seccion fc-pregunta">
                    <div class="f-sec-label">
                        ${capitalizarString(seccion['Cuenta'])} <span class="f-label-obl">*</span>
                    </div>
                    <p>${formatearComentarioVol((seccion['Comentario'] == '.' || seccion['Comentario'] == null) ? '' : seccion['Comentario'])}</p>
                    <div class="f-sec-input">
                `;
            if(seccion.hasOwnProperty('Respuestas')){
                for(respuestas of seccion['Respuestas']){
                    cuerpoHtml += `
                        <input class="f-input f-input-${tiposInput[respuestas['Tipo']]}" type="${tiposInput[respuestas['Tipo']]}" name="${seccion['Codigo']}" id="${respuestas['Codigo']}" value="${/*respuestas['Codigo']*/setVoluntarioValue(seccion, respuestas)}"> <label for="${respuestas['Codigo']}"> ${capitalizarString(respuestas['Cuenta'])}</label><br/>
                    `;
                }
            }else{
                let esPorcentaje = (seccion['Tipo']=='%');
                cuerpoHtml += `
                    <input class="f-input f-input-${esPorcentaje?'perc':tiposInput[seccion['Tipo']]}" type="${tiposInput[seccion['Tipo']]}" id="${seccion['Codigo']}" name="${seccion['Codigo']}" ${esPorcentaje?'min="0" max="100"':''} ${seccion['Codigo']=="01.04"?'onblur=\"verificarCedula(this)\"':''} ${seccion['Codigo']=="01.06"?'onchange=\"setEdadVoluntario()\"':''}>${esPorcentaje?'   %':''}
                `;
                if(seccion['Tipo']=="F"){cuerpoHtml += `<div class="btnsDocumentoVol" id="documentoVoluntario-${seccion['Codigo']}" style="visibility:hidden;"><button class="btn btn-primary" >Ver archivo cargado</button> <button class="btn btn-warning" onclick="cambiarArchivoVol('${seccion['Codigo']}')">Cambiar archivo</button></div>`;}
            }
            if(seccion['Tipo']=="F") {
                cuerpoHtml += `</div><div id="f-error-${seccion['Codigo']}" class="f-error-file">`;
            }
            cuerpoHtml += `</div></div>`;
        }
    }


    cuerpoHtml += `
            <div class="form-footer">
                <input type="hidden" id="env-insc-modo" value="1">
                <button class="env-insc-form" id="btnEnviarInsc" onclick="enviarFormInsc()" disabled>Enviar</button>
                <button class="reset-insc-form" id="btnResetInsc" onclick="resetFormInsc()">Borrar formulario</button>
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

/*function getEstadoCivil(valor){
    if(valor.includes(" ")){
        return `${valor.split(" ")[0][0]}${valor.split(" ")[2][0]}`;
    }else{
        return valor.split(" ")[0][0];
    }
}*/

function verificarCedula(elem){
    let cedula = elem.value;
    if(cedula == ""){
        $('#btnEnviarInsc').attr('disabled', 'true');
        return;
    }

    $.ajax({
        type: "POST",
        url: '../controlador/inscripciones/voluntarios.php?ConsultarCliente=true',
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
                    type: 'question',
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
    /*formData.append('Archivo_Record_Policial', document.getElementById('05.02').files[0]);
    formData.append('Archivo_Planilla', document.getElementById('05.03').files[0]);
    formData.append('Archivo_Carta_Recom', document.getElementById('05.04').files[0]);
    formData.append('Archivo_Certificado_Medico', document.getElementById('05.05').files[0]);
    formData.append('Archivo_VIH', document.getElementById('05.06').files[0]);
    formData.append('Archivo_Reglamento', document.getElementById('05.07').files[0]);*/
    if(dActualizadosVoluntario['datos']==0 && dActualizadosVoluntario['archivos']==0){
        Swal.fire(
            "Error al actualizar la información",
            "No se han proporcionado nuevos datos para este voluntario.",
            "error"
        );
        return;
    }
    $("#mensaje-form-enviado").css('visibility', 'visible');

    //TODO: RESTRICCION CAMPOS OBLIGATORIOS
    
    $.ajax({
        type: "POST",
        url: `../controlador/inscripciones/voluntarios.php?EnviarInscripcion=true&ModoEnviar=${modoEnviar}`,
        processData: false,
        contentType: false,
        data: formData,
        dataType: 'json',
        success: function (respuesta) {
            $("#mensaje-form-enviado").css('visibility', 'hidden');
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
            $("#mensaje-form-enviado").css('visibility', 'hidden');
            Swal.fire(
                "Error",
                `Hubo un error al realizar la consulta. Error: ${err}.`,
                "error"
            )
        }
    });

    //Creacion de reporte del formulario
}