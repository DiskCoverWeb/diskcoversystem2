/** 
 * AUTOR DE RUTINA	: Teddy Moreira
 * MODIFICADO POR : Teddy Moreira
 * FECHA CREACION	: 10/03/2025
 * FECHA MODIFICACION : 11/03/2025
 * DESCIPCION : Script de modulo Seteos/Referenciales SRI
*/
var tp = "";
var arr_archivos = [];
var arr_referenciales = [];
$(document).ready(function () {
    ocultarMsjError();
    ListarReferenciales();
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
    var codigoP = $("#codigoP").val();
    
    var txtConcepto = $("#txtConcepto").val();
    
    var tp = $('#txtTP').val();//tipo de proceso

    var mensaje = "";

    if(tp == '' || tp == '.'){
        Swal.fire('Por favor complete el campo de Tipo de Referencia', '', 'error');
        return;
    }

    if (!codigoP.trim()) {
        mensaje = "Ingrese un código del referencial válido";
    } else if (!txtConcepto.trim()) {
        mensaje = "Ingrese una descripción válida";
    }

    if (mensaje) {
        mostrarMsjError(mensaje);
        return;
    }

    let abreviado = '.';
    
    if($('#habilitarCuentas').prop('checked')){
        abreviado = $('#txtAbrev').val() == '' ? '.' : $('#txtAbrev').val();
    }

    let tfa = $('#cbxTFA').prop('checked') == true ? 1 : 0;
    let tpfa = $('#cbxTPFA').prop('checked') == true ? 1 : 0;
    
    ocultarMsjError();
    var parametros = {
        "codigo": codigoP,
        "concepto": txtConcepto,
        "tp": tp,
        "abreviado": abreviado,
        "tfa": tfa,
        "tpfa": tpfa
    };

    let formData = new FormData();
    for (const [key, value] of Object.entries(parametros)) {
        formData.append(key, value);
    }
    

    verificarExistenciaCodigo(codigoP)
        .then(function (resp) {
            if (resp.existe) {
                var id = resp.id;
                formData.append('id', id);
                //parametros.id = id;
                actualizarProducto(formData);
            } else {
                guardarNuevoProducto(formData);
            }
        })
        .catch(function (error) {
            console.error('Error:', error);
        });
});



function toggleInput(elem, parametro){
    console.log(elem);
    let dic_params = {
        containerC: 'contsCuentas',
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
    var tp = $('#txtTP').val();//tipo de proceso
    return new Promise(function (resolve, reject) {
        var parametros = {
            "codigo": codigoP,
            "tp": tp
        };
        console.log(parametros);
        $.ajax({
            type: 'POST',
            url: '../controlador/seteos/referenciales_sriC.php?verificarProductos=true',
            data: { parametros: parametros },
            success: function (data) {
                var responseData = JSON.parse(data);
                console.log(responseData['status']);
                console.log(responseData['datos'].length);
                if (responseData['status'] == 200 && responseData['datos'].length > 0) {
                    
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
                url: '../controlador/seteos/referenciales_sriC.php?EditarProducto=true',
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

                        $('#codigoP').val('');
                        $('#txtConcepto').val('');
                        $('#txtTP').val('');
                        
                        $('#habilitarCuentas').prop("checked", false);
                        $('#habilitarCuentas').trigger("change");
                        $('#txtAbrev').val('');
                        
                        $('#cbxTFA').prop("checked", false);
                        $('#cbxTPFA').prop("checked", false);
                        
                        
                        ListarReferenciales();

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
                url: '../controlador/seteos/referenciales_sriC.php?GuardarProducto=true',
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
                        
                        $('#codigoP').val('');
                        $('#txtConcepto').val('');
                        $('#txtTP').val('');
                        
                        $('#habilitarCuentas').prop("checked", false);
                        $('#habilitarCuentas').trigger("change");
                        $('#txtAbrev').val('');
                        
                        $('#cbxTFA').prop("checked", false);
                        $('#cbxTPFA').prop("checked", false);
                        
                        ListarReferenciales();

                        
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
    
    idSeleccionada = dato.ID;
    valproducto = dato.Descripcion;
    $('#txtTP').val(dato.Tipo_Referencia);
    $('#codigoP').val(dato.Codigo);
    $('#txtConcepto').val(dato.Descripcion);
    

    $("#txtAbrev").val(dato.Abreviado);
    if(dato.Abreviado == '.'){
        $("#habilitarCuentas").prop("checked", false);
    }else{
        $("#habilitarCuentas").prop("checked", true);
        
    }

    $("#cbxTFA").prop("checked", parseInt(dato.TFA));
    $("#cbxTPFA").prop("checked", parseInt(dato.TPFA));

    
    $('#habilitarCuentas').trigger('change');
    
}

$("#btnEliminar").on('click', function () {
    
    if (idSeleccionada != null) {
        var codigoP = $('#codigoP').val();
        

        
        var tp = $('#txtTP').val();//tipo de proceso

        
        var parametros = {
            "codigo": codigoP,
            "tp": tp
        };

        $.ajax({
            type: 'POST',
            url: '../controlador/seteos/referenciales_sriC.php?ListaEliminar=true',
            data: { parametros: parametros },
            success: function (data) {
                var responseData = JSON.parse(data);
                if (responseData['status'] == 200) {
                    var listaEliminar = responseData['datos'];
                    if (listaEliminar.length > 0) {
                        var textAreaContent;
                        var textAreaContent = listaEliminar.map(function (registro) {
                            return registro['Descripcion'];
                        }).join('\n');
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
                                    url: '../controlador/seteos/referenciales_sriC.php?EliminarProducto=true',
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
                                            
                                            $('#codigoP').val('');
                                            $('#txtConcepto').val('');
                                            $('#txtTP').val('');
                                            
                                            $('#habilitarCuentas').prop("checked", false);
                                            $('#habilitarCuentas').trigger("change");
                                            $('#txtAbrev').val('');
                                            
                                            $('#cbxTFA').prop("checked", false);
                                            $('#cbxTPFA').prop("checked", false);
                                            
                                            ListarReferenciales();
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


function ListarReferenciales() {
    $('#txt_anterior').val('');
    
    $.ajax({
        type: 'POST',
        url: '../controlador/seteos/referenciales_sriC.php?ListaReferenciales=true',
        dataType:'json',
        beforeSend: function () {
            $('#acordion_refs').html("<img src='../../img/gif/loader4.1.gif' style='width:60%' />");
        },
        success: function (data) {
            idSeleccionada = null;
            arr_referenciales = data.slice();
            let html = crearArbol(data);
            $('#acordion_refs').html(html);
        },
        error: function (error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });


}

function crearArbol(datos){
    

    const agrupado = datos.reduce((acc, item) => {
        // Si la categoría no existe en el acumulador, la inicializamos como un array vacío
        if (!acc[item.Tipo_Referencia]) {
            acc[item.Tipo_Referencia] = [];
        }
        // Agregamos el elemento al grupo correspondiente
        acc[item.Tipo_Referencia].push(item);
        return acc;
    }, {});


    let html = `<div class="accordion accordion-flush" id="accordionFlushExample">`;
    
    for(let llave of Object.keys(agrupado)){
        html += `<div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-${llave.replaceAll(' ', '_')}" aria-expanded="false" aria-controls="flush-collapse-${llave.replaceAll(' ', '_')}">
                            ${llave}
                        </button>
                    </h2>
                    <div id="flush-collapse-${llave.replaceAll(' ', '_')}" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <ul class="list-group list-group-flush" id="hijos_${llave.replaceAll(' ', '_')}">`;
        for(let item of agrupado[llave]){
            html += `<li class="list-group-item" id="label_${item.Tipo_Referencia.replaceAll(' ', '_')}${item.Codigo}"><a href="#" class="card-link" onclick="detalleProceso(${item.ID},'${item.Tipo_Referencia.replaceAll(' ', '_')}${item.Codigo}')">${item.Descripcion}</a></li>`;
        }

        html += `</ul></div></div>`;
    }

    html +=`</div>`;

    return html;
}

function detalleProceso(id,codigo){
    let referencial = arr_referenciales.find(p => p['ID'] == id);
    console.log(referencial);

    var ant = $('#txt_anterior').val();
    
    if(ant==''){	$('#txt_anterior').val(codigo); }else{	$('#label_'+ant).removeClass('bg-primary');$('#label_'+ant+' a').removeClass('text-white');}
    $('#label_'+codigo).addClass('bg-primary');
    $('#label_'+codigo+' a').addClass('text-white');
    
    $('#txt_anterior').val(codigo); 

    
    clickProducto(referencial);
}
