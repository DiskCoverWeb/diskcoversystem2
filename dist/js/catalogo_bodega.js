var tp = "";
var arr_archivos = [];
var arr_procesos = [];
$(document).ready(function () {
    $("input[name='cbxProdc']").prop("checked", false);
    ocultarMsjError();
    llenarListaTipoProcesosGenerales();
    llenarNombreArchivos();
    tipoProceso2();
    ListaCatalogoLineas();
    $('#selectTipo').on('change', tipoProceso);
});

var idSeleccionada = null;
var valproducto = null;

$('#txtConcepto').on('click', function () {

});

$('#txtConcepto').on('blur', function () {
    if ($(this).val() === '') {
        $(this).val(valproducto);
    }
});

$("#btnGuardar").click(function () {
    // $(`#select_archivos option`).removeClass('bg-danger text-white');
    // $('#picture').removeClass('is-invalid');
    var imgEnabled = $('#divImage').prop('checked');
    let imgRef = '';
    var imagen = undefined;
    var picture = '.';

    if(imgEnabled){
        imgRef = $('#imageElement').attr('src');
        imagen = document.querySelector('#imagenPicker').files[0];
        picture = $('#picture').val().trim()==''?'.':$('#picture').val();

        if(picture == '.' && (imgRef != ''|| imagen)){
            Swal.fire({
                title: "Error", 
                text: "Ingrese un nombre para la imagen", 
                icon:"error"
            });
            return;
        }

        if(validarExisteImg()){
            Swal.fire({
                title:'No se puede guardar porque el nombre de la imagen esta en uso.', 
                text:'Por favor, cambie el nombre a la imagen.', 
                icon:'error'
            });
            return;
        }
    }

    /*var tipoProducto = $("input[name='cbxReqFA']:checked").val();

    if(tipoProducto == '.' && $('#habilitarDC').prop('checked')){
        tipoProducto = $("input[name='cbxProdc']:checked").val();
    }*/

    var nivel = parseInt($('#selectNivel').val());//nivel
    var tipoDoc = nivel == 0 ? '--' : '.';
    if($('#habilitarDC').prop('checked') && tipoDoc != '--'){
        tipoDoc = $('#selectTipoDoc').val();
    }


    var codigoP = $("#codigoP").val();
    var txtConcepto = $("#txtConcepto").val();
    //var tipoProducto = $("input[name='cbxProdc']:checked").val() ? $("input[name='cbxProdc']:checked").val() : '--';
    var tp = $('#txtTP').val();//tipo de proceso
    var wtp = $('#tp').val();//tipo de proceso
    var color = $('#pordefault').prop('checked')?'.':$('#colorPick').val();
    // if (nivel === '99') {
    //     //picture = $('#picture').val();
    //     tp = codigoP;
    //     tipoProducto = $("input[name='cbxReqFA']:checked").val();
    // }


    var mensaje = "";

    if(tp == '' || tp == '.'){
        Swal.fire('Por favor complete el campo de Tipo de Proceso', '', 'error');
        return;
    }

    if (!codigoP.trim()) {
        mensaje = "Ingrese un código de producto válido";
    } else if (!txtConcepto.trim()) {
        mensaje = "Ingrese un concepto válido";
    }

    if (mensaje) {
        mostrarMsjError(mensaje);
        return;
    }

    let cta_debe = '.';
    let cta_haber = '.';
    
    if($('#habilitarCuentas').prop('checked')){
        cta_debe = $('#txtDebe').val() == '' ? '.' : $('#txtDebe').val();
        cta_haber = $('#txtHaber').val() == '' ? '.' : $('#txtHaber').val()
    }
    /*if($('#habilitarDebe').prop('checked')){
        cta_debe = $('#txtDebe').val() == '' ? '.' : $('#txtDebe').val()
    }
    if($('#habilitarHaber').prop('checked')){
        cta_haber = $('#txtHaber').val() == '' ? '.' : $('#txtHaber').val()
    }*/
    
    ocultarMsjError();
    var parametros = {
        "codigo": codigoP,
        "concepto": txtConcepto,
        "tipo": tipoDoc,
        "nivel": nivel,
        "tp": tp,
        "wtp": wtp,
        "picture": (!imagen&&imgRef=='') ? '.' : picture,
        "color": color.replace('#','Hex_'),
        "cta_debe": cta_debe,
        "cta_haber": cta_haber
    };

    let formData = new FormData();
    for (const [key, value] of Object.entries(parametros)) {
        formData.append(key, value);
    }
    //formData.append('parametros', parametros);

    if(imagen){
        formData.append('imagen', imagen);
    }

    verificarExistenciaCodigo(codigoP)
        .then(function (resp) {
            if (resp.existe) {
                var id = resp.id;
                formData.append('id', id);
                //parametros.id = id;
                if(!imagen){
                    let srcAnt = imgRef != '' ? imgRef.substr(imgRef.lastIndexOf('/')+1) : '';
                    formData.append('srcAnt', srcAnt);
                }
                actualizarProducto(formData);
            } else {
                guardarNuevoProducto(formData);
            }
        })
        .catch(function (error) {
            console.error('Error:', error);
        });
});

function marcarCampos(estado,coincidencia=null){
    if(estado == 'danger'){
        $('#picture').addClass('is-invalid');
        $('#archivosContainer').removeClass('bg-primary-subtle');
        $('#archivosContainer').addClass('bg-danger-subtle');
        $('#archivosLbl b').addClass('text-danger');
        //$('#input_existeimg').val(coincidencia);
        $('#select_archivos').val(coincidencia);
        $('#select_archivos').trigger('change');
        $(`#select_archivos option[value="${coincidencia}"]`).addClass('bg-danger text-white');
    }else{
        $('#picture').removeClass('is-invalid');
        $('#archivosContainer').removeClass('bg-danger-subtle');
        $('#archivosContainer').addClass('bg-primary-subtle');
        $('#archivosLbl b').removeClass('text-danger');
        $('#select_archivos').val(null);
        $(`#select_archivos option`).removeClass('bg-danger text-white');
    }
}

function validarExisteImg(){
    let imgRef = $('#imageElement').attr('src');

    if(imgRef == ''){
        return null;
    }

    let coincidencia = undefined;

    if($('#picture').val().endsWith('.png')){
        let nuevoNombre = $('#picture').val().split('.png')[0];
        $('#picture').val(nuevoNombre);
    }

    let nombrepic = $('#picture').val();
    marcarCampos('normal');
    // $('#picture').removeClass('is-invalid');
    // $('#archivosContainer').removeClass('bg-danger-subtle');
    // $('#archivosContainer').addClass('bg-primary-subtle');
    // $('#archivosLbl b').removeClass('text-danger');
    // $('#select_archivos').val(null);
    // $(`#select_archivos option`).removeClass('bg-danger text-white');

    if(imgRef.includes('img/png/')){
        let arr_est = imgRef.split('/');
        if(nombrepic != arr_est[arr_est.length-1].split('.png')[0]){
            coincidencia = arr_archivos.find(arc => arc.split('.png')[0] == nombrepic);
        }
    }else{
        coincidencia = arr_archivos.find(arc => arc.split('.png')[0] == nombrepic);
    }

    if(coincidencia){
        marcarCampos('danger', coincidencia);
        // $('#picture').addClass('is-invalid');
        // $('#archivosContainer').removeClass('bg-primary-subtle');
        // $('#archivosContainer').addClass('bg-danger-subtle');
        // $('#archivosLbl b').addClass('text-danger');
        // //$('#input_existeimg').val(coincidencia);
        // $('#select_archivos').val(coincidencia);
        // $(`#select_archivos option[value="${coincidencia}"]`).addClass('bg-danger text-white');
        return 1;
    }

    return null;
}

function previsualizarImagen(elem){
    // $(`#select_archivos option`).removeClass('bg-danger text-white');
    // $('#picture').removeClass('is-invalid');
    const archivo = elem.files[0];
    if(!archivo['type'].includes('png')){
        $('#imagenPicker').val("");
        Swal.fire({
            title: 'Elija un archivo con extensión .png', 
            icon: 'error'
        });
        return;
    }
    marcarCampos('normal');

    let formData = new FormData();
    formData.append('imagen', archivo);
    $('#myModal_espera').modal('show');
    setTimeout(() => {$.ajax({
        type: 'POST',
        url: '../controlador/inventario/catalogo_bodegaC.php?SubirImagenTemp=true',
        processData: false,
        contentType: false,
        data: formData,
        dataType: 'json',
        success: function (respuesta) {
            $('#myModal_espera').modal('hide');
            if(respuesta['res'] == 1 || respuesta['res'] == 2){
                let ruta = '../../TEMP/catalogo_procesos/' + respuesta['imagen'];
                $('#picture').val(respuesta['imagen'].split('.')[0]);
                $('#imageElement').prop('src',ruta);

                if(respuesta['res'] == 2){
                    $('#picture').addClass('is-invalid');
                    //$('#input_existeimg').val(respuesta['imagen']);
                    $('#select_archivos').val(respuesta['imagen']);
                    $('#select_archivos').trigger('change');
                    $(`#select_archivos option[value="${respuesta['imagen']}"]`).addClass('bg-danger text-white');
                    Swal.fire({
                        title:'Ya existe una imagen con ese nombre en el directorio', 
                        title:'Por favor, cambie el nombre antes de guardar.', 
                        icon:'warning'
                    })
                }
            }else{
                $('#picture').val(".");
                $('#imageElement').prop('src','');
                Swal.fire({
                    title:"Error", 
                    text:"Hubo un problema al mostrar la imagen", 
                    icon: "error"
                });
            }
        },
        error: function (error) {
            $('#myModal_espera').modal('hide');
            $('#picture').val(".");
            $('#imagenPicker').val('');
            $('#imageElement').prop('src','');
            Swal.fire({
                title:"Error al procesar la imagen", 
                text:"Error: " + error, 
                icon:"error"
            });
        }
    })}, 1000)
}

function toggleInputColor(elem){
    if(elem.checked==true){
        $('#colorPick').hide();
    }else{
        $('#colorPick').show();
    }
}

function toggleBloqFA(){
    let cd = $('input[name="cbxProdc"]:checked').val();

    // if(!cd || !$('#habilitarDC').prop('checked')){
    //     $('#noFA').prop('checked', true);
    // }else{
    //     $('input[name="cbxReqFA"]').prop('checked', false);

    // }
    if(cd && $('#habilitarDC').prop('checked')){
        $('input[name="cbxReqFA"]').prop('checked', false);
    }else{
        $('#noFA').prop('checked', true);

    }
}

function toggleInput(elem, parametro){
    console.log(elem);
    let dic_params = {
        color: 'colorPick',
        containerC: 'contsCuentas',
        inpDebe: 'txtDebe',
        inpHaber: 'txtHaber',
        containerDC: 'containerDC',
        divImg: 'containerImg'
    }
    if(elem.checked==true){
        $(`#${dic_params[parametro]}`).show();
    }else{
        $(`#${dic_params[parametro]}`).hide();
    }
}

function mostrarMsjError(mensaje) {
    $('#alertUse').find('.text-danger').text(mensaje);
    $('#alertUse').css('display', 'block');
}

function ocultarMsjError() {
    $('#alertUse').css('display', 'none');
}

function verificarExistenciaCodigo(codigoP) {
    var codigoP = $("#codigoP").val();
    var txtConcepto = $("#txtConcepto").val();
    var tipoProducto = $("input[name='cbxProdc']:checked").val();
    var nivel = $('#selectTipo').val();//nivel
    var tp = $('#txtTP').val();//tipo de proceso
    return new Promise(function (resolve, reject) {
        var parametros = {
            "codigo": codigoP,
            /*"concepto": txtConcepto,
            "tipo": tipoProducto,
            "nivel": nivel,*/
            "tp": tp
        };
        console.log(parametros);
        $.ajax({
            type: 'POST',
            url: '../controlador/inventario/catalogo_bodegaC.php?verificarProductos=true',
            data: { parametros: parametros },
            success: function (data) {
                var responseData = JSON.parse(data);
                console.log(responseData['status']);
                console.log(responseData['datos'].length);
                if (responseData['status'] == 200 && responseData['datos'].length > 0) {
                    /*for (var i = 0; i < responseData['datos'].length; i++) {
                        if (responseData['datos'][i].Nivel === 99 && responseData['datos'][i].TP === codigoP) {
                            resolve({ existe: true, id: responseData['datos'][i].ID });
                            return;
                        }
                        if (responseData['datos'][i].Cmds === codigoP) {
                            resolve({ existe: true, id: responseData['datos'][i].ID });
                            return;
                        }
                    }*/
                   console.log("resuelto");
                    resolve({ existe: true, id: responseData['datos'][0].ID });
                    return;
                } else {
                    resolve({ existe: false, id: null });
                }
            },
            error: function (error) {
                console.error('Error en la solicitud AJAX:', error);
                reject(error);
            }
        });
    });
}

function actualizarProducto(parametros) {
    // $(`#select_archivos option`).removeClass('bg-danger text-white');
    // $('#picture').removeClass('is-invalid');
    marcarCampos('normal');
    Swal.fire({
        title: 'Está seguro que desea actualizar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, actualizar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value == true) {
            $.ajax({
                type: 'POST',
                url: '../controlador/inventario/catalogo_bodegaC.php?EditarProducto=true',
                processData: false,
                contentType: false,
                data: parametros,
                dataType: 'json',
                success: function (data) {
                    var data = data;
                    if (data['status'] == 200) {
                        Swal.fire({
                            title: 'Éxito!, se actualizó correctamente.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false
                        });
                        llenarNombreArchivos();
                        $('#codigoP').val('');
                        $('#txtConcepto').val('');
                        $('#txtDebe').val('');
                        $('#txtHaber').val('');
                        $('#txtTP').val('');
                        //$("input[name='cbxProdc']").prop("checked", false);
                        $('#habilitarCuentas').prop("checked", false);
                        $('#habilitarCuentas').trigger("change");
                        $('#divImage').prop("checked", false);
                        $('#divImage').trigger("change");
                        $('#habilitarDC').prop("checked", false);
                        $('#habilitarDC').trigger("change");
                        $('#selectTipoDoc').val('C');
                        $('#picture').val('');
                        $('#imagenPicker').val('');
                        $('#imageElement').prop('src', '');
                        $('#pordefault').prop('checked', true);
                        $('#colorPick').hide();
                        //$('#picture').val('');
                        // if (parametros.nivel === '99') {
                        //     $('#siFA').prop('checked', false);
                        //     $('#noFA').prop('checked', false);
                        // }
                        // tipoProceso();
                        tipoProceso2();

                    } else {
                        Swal.fire({
                            title: 'Error, no se actualizó.',
                            icon: 'error',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function (error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });
            $('#modalEditar').modal('hide');
        }
    });
}

function guardarNuevoProducto(parametros) {
    // $(`#select_archivos option`).removeClass('bg-danger text-white');
    // $('#picture').removeClass('is-invalid');
    marcarCampos('normal');
    Swal.fire({
        title: 'Está seguro que desea guardar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, guardar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value == true) {
            $.ajax({
                type: 'POST',
                url: '../controlador/inventario/catalogo_bodegaC.php?GuardarProducto=true',
                processData: false,
                contentType: false,
                data: parametros,
                dataType: 'json',
                success: function (data) {
                    var responseData = data;
                    if (responseData['status'] == 200) {
                        Swal.fire({
                            title: "Éxito!, se registro correctamente.",
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false
                        });
                        llenarNombreArchivos()
                        $('#codigoP').val('');
                        $('#txtConcepto').val('');
                        $('#txtDebe').val('');
                        $('#txtHaber').val('');
                        $('#txtTP').val('');
                        //$("input[name='cbxProdc']").prop("checked", false);
                        $('#habilitarCuentas').prop("checked", false);
                        $('#habilitarCuentas').trigger("change");
                        $('#divImage').prop("checked", false);
                        $('#divImage').trigger("change");
                        $('#habilitarDC').prop("checked", false);
                        $('#habilitarDC').trigger("change");
                        $('#selectTipoDoc').val('C');
                        $('#picture').val('');
                        $('#imagenPicker').val('');
                        $('#imageElement').prop('src', '');
                        $('#pordefault').prop('checked', true);
                        $('#colorPick').hide();
                        //$('#picture').val('');
                        /*if (parametros.nivel === '99') {
                            $('#siFA').prop('checked', false);
                            $('#noFA').prop('checked', false);
                        }*/
                        //tipoProceso();
                        tipoProceso2();

                        
                    } else {
                        Swal.fire({
                            title: 'Error, no se registró.',
                            icon: 'error',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function (error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });
            $('#modalAgregar').modal('hide');
        }
    });
}

function listarDatos() {
    var nivel = $('#selectTipo').val();//nivel
    var tp = $('#tp').val();//tipo de proceso
    var parametros = {
        "nivel": nivel,
        "tp": tp
    };
    $.ajax({
        type: 'POST',
        url: '../controlador/inventario/catalogo_bodegaC.php?ListaProductos=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            var responseData = data;
            if (responseData['status'] == 200 && responseData['datos'].length > 0) {
                llenarAcordeon(responseData['datos']);
                $('#alertNoData').css('display', 'none');
            } else {
                var acordeon = $('#accordion');
                acordeon.empty();
                $('#alertNoData').css('display', 'block');
            }
        },
        error: function (error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });
}



function llenarAcordeon(datos) {
    var acordeon = $('#accordion');
    acordeon.empty();

    var grupos = {};

    datos.forEach(function (dato) {
        var niveles = "";
        if (dato.Nivel === 99) {
            niveles = dato.TP.split('.');
        } else {
            niveles = dato.Cmds.split('.');
        }
        var nivel1 = niveles[0];

        if (!grupos[nivel1]) {
            grupos[nivel1] = [];
        }

        grupos[nivel1].push(dato);
    });

    Object.keys(grupos).forEach(function (nivel1, index) {
        var panel = $('<div class="accordion-item">');
        var panelHeading = $('<h4 class="accordion-header">');
        //var panelTitle = $('<h4 class="panel-title">');
        var title = $('<button class="accordion-button py-1 bg-body-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' + index + '" aria-expanded="false" aria-controls="collapse' + index + '">');

        title.html('<span class="bx bxs-folder-open icono"></span> ' + nivel1 + ' ' + grupos[nivel1][0].Proceso);

        var panelBody = $('<div id="collapse' + index + '" class="accordion-collapse collapse" data-bs-parent="#accordion">');

        grupos[nivel1].forEach(function (dato, subindex) {
            var niveles = dato.Cmds.split('.');

            if (niveles.length > 1) {
                var subnivel = niveles.slice(1).join('.');
                var body = $('<div class="accordion-body border-bottom">').text(dato.Cmds + ' ' + dato.Proceso);
                panelBody.append(body);

                body.on('click', function (e) {
                    clickProducto(dato, e);
                });
            }
        });

        title.on('click', function () {
            var dato = grupos[nivel1][0];
            clickProducto(dato);
        });

        //panelTitle.append(title);
        panelHeading.append(title);
        panel.append(panelHeading);
        panel.append(panelBody);
        acordeon.append(panel);
    });
}

function clickProducto(dato, e=null) {
    //$('input[name="cbxProdc"]').prop('disabled', false);
    //$('input[name="cbxReqFA"]').prop('disabled', false);
    // $('#picture').removeClass('is-invalid');
    
    //$(`#selectNivel`).val('00');
    marcarCampos('normal');
    // Cambio del color segun la seleccion
    if(e){
        document.querySelectorAll('.accordion-body.border-bottom').forEach(item => item.style.color="#4c5258");
        e.target.style.color="blue";
    }

    ocultarMsjError();
    idSeleccionada = dato.ID;
    valproducto = dato.Proceso;
    $('#txtTP').val(dato.TP);
    $('#codigoP').val(dato.Cmds);
    // if (dato.Nivel === 99) {
    //     $('#codigoP').val(dato.TP);
    // } else {
    //     $('#codigoP').val(dato.Cmds);
    //     //$('#tp').val(dato.TP);
    // }
    $('#imagenPicker').val('');
    if(dato.Picture == '.'){
        $('#divImage').prop('checked',false);
        $('#imageElement').prop('src','');
    }else{
        $('#divImage').prop('checked',true);
        $('#imageElement').prop('src',`../../img/png/${dato.Picture}.png`);
    }

    //$("input[name='cbxProdc']").prop('checked', false);

    if(dato.DC == '.' || dato.DC == '--'){
        //$("input[name='cbxProdc'][value='" + dato.DC + "']").prop("checked", true);
        $("#habilitarDC").prop("checked", false);
    }else{
        $("#habilitarDC").prop("checked", true);
        $('#selectTipoDoc').val(dato.DC);
    }

    // if(dato.DC != '.' && dato.DC != 'FA'){
    //     console.log(dato.DC);
    //     $("input[name='cbxProdc'][value='" + dato.DC + "']").prop("checked", true);
    //     $("#habilitarDC").prop("checked", true);
    // }else{
    //     $("input[name='cbxProdc']").prop("checked", false);
    //     $("#habilitarDC").prop("checked", false);
        
    // }

    // if(dato.DC == '.' || dato.DC == 'FA'){
    //     $("input[name='cbxProdc']").prop("checked", false);
    //     $("#habilitarDC").prop("checked", false);
    // }

    if(dato.Cta_Debe == '.' && dato.Cta_Haber == '.'){
        $("#habilitarCuentas").prop("checked", false);
    }else{
        $("#habilitarCuentas").prop("checked", true);
    }
    
    // if(dato.Cta_Debe == '.'){
    //     $("#habilitarDebe").prop("checked", false);
    // }else{
    //     $("#habilitarDebe").prop("checked", true);
    // }

    // if(dato.Cta_Haber == '.'){
    //     $("#habilitarHaber").prop("checked", false);
    // }else{
    //     $("#habilitarHaber").prop("checked", true);
    // }
    
    //Activacion de funciones por triggers
    $('#divImage').trigger('change');
    $('#habilitarDC').trigger('change');
    $('#habilitarCuentas').trigger('change');
    // $('#habilitarDebe').trigger('change');
    // $('#habilitarHaber').trigger('change');

    $('#picture').val(dato.Picture);
    if(dato.Color != '.'){
        $('#pordefault').prop('checked', false);
        $('#colorPick').show();
        let dColor = dato.Color.replace("Hex_", "#");
        $('#colorPick').val(dColor);
    }else{
        $('#pordefault').prop('checked', true);
        $('#colorPick').hide();
    }
    /*var reqFact = dato.DC;
    if (reqFact === 'FA') {
        $('#siFA').prop('checked', true);
    } else {
        $('#noFA').prop('checked', true);
    }*/
    $('#txtDebe').val(dato.Cta_Debe);
    $('#txtHaber').val(dato.Cta_Haber);
    $('#txtConcepto').val(dato.Proceso);
    $(`#selectNivel`).val(dato.Nivel);
    /*if(dato.Nivel == 0){
        $('input[name="cbxProdc"]').prop('disabled', true);
        $('input[name="cbxReqFA"]').prop('disabled', true);
        $('input[name="cbxProdc"]').prop('checked', false);
        $('input[name="cbxReqFA"]').prop('checked', false);
    }*/
}

$("#btnEliminar").on('click', function () {
    // $(`#select_archivos option`).removeClass('bg-danger text-white');
    // $('#picture').removeClass('is-invalid');
    marcarCampos('normal');
    if (idSeleccionada != null) {
        var codigoP = $('#codigoP').val();
        var nivel = $('#selectNivel').val();//nivel
        var tp = $('#txtTP').val();//tipo de proceso

        /*if(tp == '' || tp.split(',') > 1){
            Swal.fire('Seleccionar un tipo de proceso para eliminar', '', 'error');
            return;
        }*/
        
        var parametros = {
            "codigo": codigoP,
            "nivel": nivel,
            "tp": tp
        };

        $.ajax({
            type: 'POST',
            url: '../controlador/inventario/catalogo_bodegaC.php?ListaEliminar=true',
            data: { parametros: parametros },
            success: function (data) {
                var responseData = JSON.parse(data);
                if (responseData['status'] == 200) {
                    var listaEliminar = responseData['datos'];
                    if (listaEliminar.length > 0) {
                        var textAreaContent;
                        if (nivel === '99' || tp == '99') {
                            var textAreaContent = listaEliminar.map(function (registro) {
                                return registro['TP'] + ' - ' + registro['Proceso'];
                            }).join('\n');
                        } else {
                            var textAreaContent = listaEliminar.map(function (registro) {
                                return registro['Cmds'] + ' - ' + registro['Proceso'];
                            }).join('\n');
                        }
                        Swal.fire({
                            title: 'Está seguro que desea eliminar?',
                            html: 'Se borrará de forma permanente!<br>' +
                                '<textarea disabled id="selectEliminar" rows="2" style="overflow-y: auto; resize: none; margin-top:3px; width:300px" >' + textAreaContent + '</textarea>',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sí, eliminar!',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.value == true) {
                                $.ajax({
                                    type: 'POST',
                                    url: '../controlador/inventario/catalogo_bodegaC.php?EliminarProducto=true',
                                    data: { parametros: listaEliminar },
                                    success: function (data) {
                                        var data = JSON.parse(data);
                                        if (data['status'] == 200) {
                                            Swal.fire({
                                                title: 'Éxito!, los datos se eliminaron correctamente.',
                                                icon: 'success',
                                                timer: 1000,
                                                showConfirmButton: false
                                            });
                                            llenarNombreArchivos()
                                            $('#codigoP').val('');
                                            $('#txtConcepto').val('');
                                            $('#txtDebe').val('');
                                            $('#txtHaber').val('');
                                            $('#txtTP').val('');
                                            //$("input[name='cbxProdc']").prop("checked", false);
                                            $('#habilitarCuentas').prop("checked", false);
                                            $('#habilitarCuentas').trigger("change");
                                            $('#divImage').prop("checked", false);
                                            $('#divImage').trigger("change");
                                            $('#habilitarDC').prop("checked", false);
                                            $('#habilitarDC').trigger("change");
                                            $('#selectTipoDoc').val('C');
                                            $('#picture').val('');
                                            $('#imagenPicker').val('');
                                            $('#pordefault').prop('checked', true);
                                            $('#colorPick').hide();
                                            // if (parametros.nivel === '99') {
                                            //     $('#siFA').prop('checked', false);
                                            //     $('#noFA').prop('checked', false);
                                            // }
                                            // tipoProceso();
                                            tipoProceso2();
                                        } else {
                                            Swal.fire({
                                                title: 'Error, no se pudieron eliminar los datos.',
                                                icon: 'error',
                                                timer: 1000,
                                                showConfirmButton: false
                                            });
                                        }
                                    },
                                    error: function (error) {
                                        console.error('Error en la solicitud AJAX:', error);
                                    }
                                });
                            } else {
                                idSeleccionada = null;
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'No hay datos para eliminar',
                            icon: 'info',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                }
            },
            error: function (error) {
                console.error('Error en la solicitud AJAX:', error);
            }
        });
    } else {
        Swal.fire({
            title: 'Error, seleccione un producto',
            icon: 'error',
            timer: 1000,
            showConfirmButton: false
        });
    }
});

function llenarListaTipoProcesosGenerales() {
    $.ajax({
        type: 'GET',
        url: '../controlador/inventario/catalogo_bodegaC.php?ListaTipo=true',
        dataType: 'json',
        success: function (data) {
            var responseData = data;
            if (responseData['status'] == '200' && responseData['datos'].length > 0) {
                var datos = responseData['datos'];
                var select = $('#selectTipo');
                select.empty();
                datos.forEach(function (dato) {
                    select.append('<option value="' + dato.TP + '">' + dato.Proceso + '</option>');
                });
            }
        },
        error: function (error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });
}

function ListaCatalogoLineas() {
    $.ajax({
        type: 'GET',
        url: '../controlador/inventario/catalogo_bodegaC.php?ListaCatalogoLineas=true',
        dataType: 'json',
        success: function (data) {
            if(data.length > 0){
                //console.log(data);
                var select = $('#selectTipoDoc');
                select.empty();
                data.forEach(function (dato) {
                    select.append('<option value="' + dato.Fact + '">' + dato.Fact + '</option>');
                });
            }
        },
        error: function (error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });
}

function llenarNombreArchivos() {
    arr_archivos = [];
    $.ajax({
        url: '../controlador/inventario/catalogo_bodegaC.php?cargar_imgs=true',
        type:'post',
        dataType:'json',
       // data:{:},     
        success: function(response){
        $('#select_archivos').html(response);
        for(let e of $('#select_archivos').children()){
            arr_archivos.push(e.value);
        }
      }
    });

    
}

/*function construirLista(datos){
    let html = ``;
    if(datos.length > 0){
        for(let d of datos){
            html += `<li>${d['Proceso']}</li>` + construirLista(d['children']);
        }
        return `<ul>${html}</ul>`;
    }else{
        return html;
    }
}*/


function tipoProceso2() {
    //var tipoProceso = $('#selectTipo').val();
    $('#txt_anterior').val('');
    /*var parametros = {
        'tp': tipoProceso
    };*/
    $.ajax({
        type: 'POST',
        url: '../controlador/inventario/catalogo_bodegaC.php?ListaTipoProcesosGeneralesCompleto=true',
        //data: { parametros: parametros },
        dataType:'json',
        beforeSend: function () {
            $('#tree1').html("<img src='../../img/gif/loader4.1.gif' style='width:60%' />");
        },
        success: function (data) {
            arr_procesos = data.slice();
            let html = crearArbolHTML(data);
            $('#tree1').html(html);

            let listaNiveles = arr_procesos.filter(p => p['Nivel'] == 0);
            var select = $('#selectNivel');
            select.empty();
            listaNiveles.forEach(function (dato) {
                let nivel = dato.TP == '00' ? 0 : dato.TP;
                select.append('<option value="' + nivel + '">' + dato.TP + ' ' + dato.Proceso + '</option>');
            });
        },
        error: function (error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });


}

function crearArbolHTML(datos){
    let html = "";

    let raiz = datos.shift();

    html += `<li>
                <label id="label_${raiz['Cmds'].replaceAll('.', '_')}_${raiz['ID']}" for="${raiz['Cmds'].replaceAll('.', '_')}">
                    ${raiz['Proceso']}
                </label>
                <input type="checkbox" id="${raiz['Cmds'].replaceAll('.', '_')}" onclick="detalleProceso(${raiz['ID']}, '${raiz['Cmds']}')">
                <ol id="hijos_${raiz['Cmds'].replaceAll('.', '_')}">`;
    //raiz['children'] = [];

    for(let item of datos){
        if(item['Nivel'] == 0){
            let hijos = `<li>
                            <label id="label_${item['Cmds'].replaceAll('.', '_')}_${item['ID']}" for="${item['Cmds'].replaceAll('.', '_')}">
                                ${item['Cmds']} ${item['Proceso']}
                            </label>
                            <input type="checkbox" id="${item['Cmds'].replaceAll('.', '_')}" onclick="detalleProceso(${item['ID']}, '${item['Cmds']}')">
                            <ol id="hijos_${item['Cmds'].replaceAll('.', '_')}">`;
            //let  = `<ol id="hijos_${item['Cmds'].replaceAll('.', '_')}">`;
            for(let d of datos){
                if(d['Nivel'] == item['Cmds']){
                    //hijos.push(d);
                    hijos += `<li class="file" id="label_${d['Cmds'].replaceAll('.', '_')}_${d['ID']}">
                                <a href="#" onclick="detalleProceso(${d['ID']}, '${d['Cmds']}')">${d['Nivel'] == 99 ? d['TP'] : d['Cmds']} ${d['Proceso']}</a>
                            </li>`;
                }
            }
            hijos += "</ol></li>";
            html += hijos;
            /*item['children'] = hijos;
            raiz['children'].push(item)*/
        }
    }
    html += "</ol></li>";
    return html;
    //arbol.push(raiz);
    //console.log(arbol);
}

function detalleProceso(id, cod){
    let proceso = arr_procesos.find(p => p['ID'] == id);

    var ant = $('#txt_anterior').val();
    var che = cod.split('.').join('_');
    if(ant==''){	$('#txt_anterior').val(che+'_'+id); }else{	$('#label_'+ant).css('border','0px');}
    $('#label_'+che+'_'+id).css('border','1px solid');
    $('#txt_anterior').val(che+'_'+id); 

    /*switch (proceso.TP) {
        case '99':
            $('#txtConcepto').attr('placeholder', '');
            $('#pictureContainer').css('display', 'flex');
            $('#nombresContainer').css('display', 'flex');
            $('#reqFacturaContainer').css('display', 'block');
            $('#checkboxContainer').css('display', 'block');
            $('#cuentasContainer').css('display', 'block');
            $('#colorContainer').css('display', 'block');
            break;
        case '00':
            $('#txtConcepto').attr('placeholder', '');
            //$('#tp').val('CATEGORI');
            $('#pictureContainer').css('display', 'flex');
            $('#nombresContainer').css('display', 'flex');
            $('#reqFacturaContainer').css('display', 'block');
            $('#checkboxContainer').css('display', 'block');
            $('#cuentasContainer').css('display', 'block');
            $('#colorContainer').css('display', 'block');
            break;
        default:
            $('#pictureContainer').css('display', 'flex'); //none
            $('#nombresContainer').css('display', 'flex'); //none
            $('#reqFacturaContainer').css('display', 'block'); //none
            $('#checkboxContainer').css('display', 'block'); //none
            $('#cuentasContainer').css('display', 'block'); //none
            $('#colorContainer').css('display', 'block');
            break;
    }*/

    clickProducto(proceso);
}

function tipoProceso() {
    //de selectTipo se obtiene la opcion seleccionada
    var tipoProceso = $('#selectTipo').val();
    var parametros = {
        'tp': tipoProceso
    };
    $.ajax({
        type: 'POST',
        url: '../controlador/inventario/catalogo_bodegaC.php?ListaTipoProcesosGeneralesAux=true',
        data: { parametros: parametros },
        success: function (data) {
            var jsonData = JSON.parse(data);
            if (jsonData.length > 0) {
                var tps = jsonData.map(jd => jd.TP);
                let arrTps = tps.reduce((acc, item) => {
                    if (!acc.includes(item)) acc.push(item);
                    return acc;
                }, []);
                let tpAux = arrTps.join(',');
                $('#txtConcepto').attr('placeholder', '');
                $('#tp').val(tpAux);
                console.log($('#tp').val());
                // if(jsonData.length == 1){
                //     var tpAux = jsonData[0].TP;
                //     $('#txtConcepto').attr('placeholder', '');
                //     $('#tp').val(tpAux);
                // }else{
                //     var tpAux = jsonData.map(jd => jd.TP);
                //     $('#tp').val(tpAux.join(','));
                //     //console.log($('#tp').val());
                // }
                switch (tipoProceso) {
                    case '99':
                        $('#txtConcepto').attr('placeholder', '');
                        $('#pictureContainer').css('display', 'flex');
                        $('#nombresContainer').css('display', 'flex');
                        $('#reqFacturaContainer').css('display', 'block');
                        $('#checkboxContainer').css('display', 'block');
                        $('#cuentasContainer').css('display', 'block');
                        $('#colorContainer').css('display', 'block');
                        break;
                    case '00':
                        $('#txtConcepto').attr('placeholder', '');
                        //$('#tp').val('CATEGORI');
                        $('#pictureContainer').css('display', 'flex');
                        $('#nombresContainer').css('display', 'flex');
                        $('#reqFacturaContainer').css('display', 'block');
                        $('#checkboxContainer').css('display', 'block');
                        $('#cuentasContainer').css('display', 'block');
                        $('#colorContainer').css('display', 'block');
                        break;
                    default:
                        $('#pictureContainer').css('display', 'flex'); //none
                        $('#nombresContainer').css('display', 'flex'); //none
                        $('#reqFacturaContainer').css('display', 'block'); //none
                        $('#checkboxContainer').css('display', 'block'); //none
                        $('#cuentasContainer').css('display', 'block'); //none
                        $('#colorContainer').css('display', 'block');
                        break;
                }
            }
            listarDatos();
        },
        error: function (error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });
    //se verifica que tipo de TP es
    /*switch (tipoProceso) {
        //Tipo de Ingreso
        case '99':
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('AR00');
            $('#pictureContainer').css('display', 'block');
            $('#reqFacturaContainer').css('display', 'block');
            $('#checkboxContainer').css('display', 'none');
            break;
        //Tipo de Categorias
        case '00':
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('CATEGORI');
            $('#pictureContainer').css('display', 'none');
            $('#reqFacturaContainer').css('display', 'none');
            $('#checkboxContainer').css('display', 'block');
            break;
        //Tipo de Proveedor
        case '98':
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('TIPOPROV');
            $('#pictureContainer').css('display', 'none');
            $('#reqFacturaContainer').css('display', 'none');
            $('#checkboxContainer').css('display', 'none');
            break;
        //Tipo de Empaque
        case '97':
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('EMPAQUE');
            $('#pictureContainer').css('display', 'none');
            $('#reqFacturaContainer').css('display', 'none');
            $('#checkboxContainer').css('display', 'none');
            break;
        //Tipo de Estado de Transporte
        case '96':
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('ESTTRANS');
            $('#pictureContainer').css('display', 'none');
            $('#reqFacturaContainer').css('display', 'none');
            $('#checkboxContainer').css('display', 'none');
            break;
        case '94':
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('MOTIVOS');
            $('#pictureContainer').css('display', 'none');
            $('#reqFacturaContainer').css('display', 'none');
            $('#checkboxContainer').css('display', 'none');
            break;
        case '95':
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('AREAEGRE');
            $('#pictureContainer').css('display', 'none');
            $('#reqFacturaContainer').css('display', 'none');
            $('#checkboxContainer').css('display', 'none');
            break;
        default:
            $('#txtConcepto').attr('placeholder', '');
            $('#tp').val('CATEGORI');
            $('#pictureContainer').css('display', 'none');
            $('#reqFacturaContainer').css('display', 'none');
            $('#checkboxContainer').css('display', 'none');
            break;
    };*/


}

function previsualizar_ImagenArc(){
    let nombre_img = $('#select_archivos').val();
    let ruta = '../../img/png/'+nombre_img;
    $('#imageElementArc').prop('src',ruta);
}





