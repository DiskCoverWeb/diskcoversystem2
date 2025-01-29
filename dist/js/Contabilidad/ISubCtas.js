
//Definicion de variables
var indiceActual = 0;
var cadenaEliminar = "";//Para eliminar la subcuenta
var codigosCC = new Set();//Para guardar los codigos de las subcuentas de centro de costos

$(document).ready(function () {
    $('[data-bs-toggle="tooltip"]').tooltip();
    OpcI_Click();
    CheqBloquear_Click();
    $('#DLCtas').tooltip('disable');
    var tooltipEliminar  =  new bootstrap.Tooltip($('#btnEliminar')[0]);
    deshabilitarbtnEliminar();

    $('#btnSiguiente').click(function () {
        actualizarIndiceYLLenarCta(indiceActual + 1);
        QuitarBloqueoBotonesSubCtas();
        $('#DLCtas button').tooltip('enable');
        $('#DLCtas').tooltip('disable');
    });

    $('#btnAnterior').click(function () {
        actualizarIndiceYLLenarCta(indiceActual - 1);
        QuitarBloqueoBotonesSubCtas();
        $('#DLCtas button').tooltip('enable');
        $('#DLCtas').tooltip('disable');

    });

    $('#btnPrimero').click(function () {
        actualizarIndiceYLLenarCta(0);
        QuitarBloqueoBotonesSubCtas();
        $('#DLCtas button').tooltip('enable');
        $('#DLCtas').tooltip('disable');

    });

    $('#btnUltimo').click(function () {
        var ultimoIndice = $('#DLCtas').children('button').length - 1;
        actualizarIndiceYLLenarCta(ultimoIndice);
        QuitarBloqueoBotonesSubCtas();
        $('#DLCtas button').tooltip('enable');
        $('#DLCtas').tooltip('disable');
    });

    $(document).dblclick(function (event) {
        // Verifica si el doble clic no ocurrió en el contenedor de botones ni en sus elementos hijos
        if (!$(event.target).closest("#DLCtas").length) {
            deshabilitarbtnEliminar();
            despintarBoton();
        }
    });

    $('#btnNuevo').on('click', function () {
        deshabilitarbtnEliminar();
        despintarBoton();
        NuevaCta();
        $('#DLCtas').tooltip('enable');
        $('#DLCtas button').tooltip('disable');
        $('#TxtNivel').val('00');
        $('#TxtReembolso').val(0);
        $('#CheqNivel').prop('checked', false);
        $('#CheqBloquear').prop('checked', false).change();
        var TipoCta = $("input[name='TipoCuenta']:checked").val();
        if (TipoCta === 'CC') {
            $('#TxtCodigo').focus();
            $('#TxtCodigo').select();
        } else {
            $('#TxtNivel').focus();
        }

    });

    $('#btnEliminar').hover(function () {
        // Verifica si el botón está deshabilitado
        if ($(this).is(':disabled')) {
            // Muestra el tooltip
            $(this).tooltip('show');
        } else {
            // Oculta el tooltip
            $(this).tooltip('hide');
        }
    });
    
});

function habilitarbtnEliminar() {
    $('#btnEliminar').prop('disabled', false);
}

function deshabilitarbtnEliminar() {
    $('#btnEliminar').prop('disabled', true);
}

function actualizarIndiceYLLenarCta(nuevoIndice) {
    var listaItems = $('#DLCtas').children('button');
    if (nuevoIndice >= 0 && nuevoIndice < listaItems.length) {
        // Quitar el enfoque de todos los botones
        listaItems.removeClass('boton-enfocado');

        // Actualizar el índice
        indiceActual = nuevoIndice;

        // Enfocar el botón actual y aplicar la clase para resaltar
        var botonActual = $(listaItems[indiceActual]);
        botonActual.addClass('boton-enfocado');

        botonActual[0].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });

        var nombreCta = botonActual.text();
        LlenarCta(nombreCta);
        //split nombreCta para obtener el codigo de la subcuenta
        nombreCta = nombreCta.split(' ');
        cadenaEliminar = nombreCta[0];
        habilitarbtnEliminar();

    }
}

function actualizarIndiceYPintar(nuevoIndice) {//Lo mismo que el de arriba, pero solo es para que se pinte al hacer un click 
    var listaItems = $('#DLCtas').children('button');
    if (nuevoIndice >= 0 && nuevoIndice < listaItems.length) {
        listaItems.removeClass('boton-enfocado');

        indiceActual = nuevoIndice;

        var botonActual = $(listaItems[indiceActual]);
        botonActual.addClass('boton-enfocado');

    }
}

function despintarBoton() {
    var listaItems = $('#DLCtas').children('button');
    listaItems.removeClass('boton-enfocado');
}

//btnEliminar
function Eliminar() {
    var parametros = {
        "Cadena": cadenaEliminar
    }
    var TipoCta = $("input[name='TipoCuenta']:checked").val();

    $.ajax({
        data: { parametros: parametros },
        url: '../controlador/contabilidad/ISubCtasC.php?Eliminar=true',
        type: 'post',
        success: function (response) {
            var datos = JSON.parse(response);
            if (datos.length > 0) {
                swal.fire('No se puede eliminar esta SubCuenta porque tiene cuentas procesables.', '', 'error');
            } else {
                swal.fire({
                    title: 'Eliminar SubCuenta',
                    text: `¿Está seguro de eliminar la Cuenta No. [${cadenaEliminar}]`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            data: { parametros: parametros },
                            url: '../controlador/contabilidad/ISubCtasC.php?EliminarSubCta=true',
                            type: 'post',
                            success: function (response) {
                                var datos = JSON.parse(response);
                                if (datos == 1) {
                                    swal.fire('Subcuenta eliminada correctamente', '', 'success');
                                    ListarSubCtas(TipoCta);
                                    deshabilitarbtnEliminar();
                                } else {
                                    swal.fire('Error al eliminar la subcuenta', '', 'error');
                                }
                            }
                        });
                    }
                });
            }
        }
    });
}

//btnGrabar
function GrabarCta() {

    var TipoCta = $("input[name='TipoCuenta']:checked").val();

    var parametros = {
        "CodigoCta": $('#TxtCodigo').val(),
        "TipoCta": TipoCta,
        "TxtNivel": $('#TxtNivel').val(),
        "CheqCaja": $('#CheqCaja').prop('checked') ? 1 : 0,
        "CheqNivel": $('#CheqNivel').prop('checked') ? 1 : 0,
        "CheqBloquear": $('#CheqBloquear').prop('checked') ? 1 : 0,
        "TextSubCta": $('#TextSubCta').val(),
        "TextPresupuesto": $('#TextPresupuesto').val() == '' ? 0 : $('#TextPresupuesto').val(),
        "MBoxCta": $('#MBoxCta').val() == '' ? 0 : $('#MBoxCta').val(),
        "TxtReembolso": $('#TxtReembolso').val() == '' ? 0 : $('#TxtReembolso').val(),
        "MBFechaI": $('#MBFechaI').val(),
        "MBFechaF": $('#MBFechaF').val()
    }



    var TextSubCta = $('#TextSubCta').val().trim();

    if (TextSubCta === '') {
        swal.fire('Llenar el campo de SubCuenta', '', 'error');
        return false;
    }

    if (TipoCta === 'CC' && codigosCC.has($('#TxtCodigo').val())) {
        swal.fire({
            title: 'El código ya existe',
            text: `¿Desea actualizar la SubCuenta [${$('#TxtCodigo').val()}]?`,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                GrabarCtaTmp(parametros);
            } else {
                return false;
            }
        });
    } else {
        GrabarCtaTmp(parametros);
    }
}

function GrabarCtaTmp(parametros) {
    var TipoCta = $("input[name='TipoCuenta']:checked").val();
    $.ajax({
        data: { parametros: parametros },
        url: '../controlador/contabilidad/ISubCtasC.php?GrabarCta=true',
        type: 'post',
        success: function (response) {
            var datos = JSON.parse(response);
            if (datos == 1) {
                swal.fire('Grabación Exitosa', '', 'success');
                ListarSubCtas(TipoCta);
                $('#DLCtas').tooltip('disable');
            } else {
                swal.fire('Error al grabar la subcuenta', '', 'error');
            }
        }
    });
}

function ListarSubCtas(TipoCta) {
    var parametros = {
        "TipoCta": TipoCta
    };

    $("#DLCtas button").removeClass('estiloOscuro');

    $.ajax({
        data: { parametros: parametros },
        url: '../controlador/contabilidad/ISubCtasC.php?ListarSubCtas=true',
        type: 'post',
        success: function (response) {
            var datos = JSON.parse(response);
            var AdoCatalogo = datos.AdoCatalogo;
            const AdoSubCta = datos.AdoSubCta;
            const lista = $('#DLCtas');
            LimpiarCampos();
            lista.empty();
            $('#TxtCodigo').val(datos.TxtCodigo);
            if (AdoSubCta.length > 0) {
                $.each(AdoSubCta, function (index, item) {
                    if (TipoCta === 'CC') {
                        codigosCC.add(item.Codigo);
                    }
                    var botonEstilo = item.Agrupacion === 0 ? 'padding-left: 50px;' : '';
                    var boton = $('<button>', {
                        type: 'button',
                        'class': 'list-group-item list-group-item-action',
                        'style': `white-space: pre; font-family: Courier; ${botonEstilo}`,
                        'text': `${item.Codigo}  -  ${item.Detalle}  -  ${item.Nivel}`,
                        'dblclick': function () {
                            var indice = $('#DLCtas').children('button').index(this);
                            actualizarIndiceYLLenarCta(indice);
                            cadenaEliminar = item.Codigo;
                        },
                        'click': function () {
                            var indice = $('#DLCtas').children('button').index(this);
                            actualizarIndiceYPintar(indice);
                            cadenaEliminar = item.Codigo;
                        }
                    }).appendTo(lista);

                    boton.attr('data-bs-toggle', 'tooltip');
                    boton.attr('title', 'Doble clic para editar');
                    boton.tooltip();

                    $('#encabezadosSubCtas').empty().append('<h3>Código  -  Detalle  -  Nivel</h3>');
                });
            }
        }
    });
}

function LlenarCta(DLCtas) {
    var parametros = {
        "CodigoCta": DLCtas
    }

    $.ajax({
        data: { parametros: parametros },
        url: '../controlador/contabilidad/ISubCtasC.php?LlenarCta=true',
        type: 'post',
        success: function (response) {
            var data = JSON.parse(response);
            if (data.response == 1) {
                var fields = data.AdoSubCta1[0];

                $('#TextSubCta').val(fields.Detalle);
                $('#TxtCodigo').val(fields.Codigo);
                switch (fields.TC) {
                    case "G":
                        $('#OpcG').prop('checked', true);
                        break;
                    case "I":
                        $('#OpcI').prop('checked', true);
                        break;
                    case "PM":
                        $('#OpcPM').prop('checked', true);
                        break;
                    case "CC":
                        $('#OpcCC').prop('checked', true);
                        break;
                }
                $('#MBoxCta').val(fields.Cta_Reembolso);
                $('#Label5').val(fields.Label5);
                if (fields.Reembolso == ".") {
                    $('#TxtReembolso').val(0);
                } else {
                    $('#TxtReembolso').val(fields.Reembolso);
                }
                $('#TxtNivel').val(fields.Nivel);
                $('#TextPresupuesto').val(fields.Presupuesto);
                $('#MBFechaI').val(fields.Fecha_D);
                $('#MBFechaF').val(fields.Fecha_H);
                $('#CheqNivel').prop('checked', fields.Agrupacion == 1 ? true : false);
                $('#CheqBloquear').prop('checked', fields.Bloquear == 1 ? true : false).change();;
                $('#CheqCaja').prop('checked', fields.Caja == 1 ? true : false);
            } else {
                $('#TextSubCta').val('');
                $('#TxtCodigo').val(data.TxtCodigo);
            }
        }
    });

}

function CheqBloquear_Click() {
    if ($('#CheqBloquear').prop('checked')) {
        $('#MBFechaI').css('visibility', 'visible');
        $('#MBFechaF').css('visibility', 'visible');
        $('#Label10').css('visibility', 'visible');
        $('#Label9').css('visibility', 'visible');
    } else {
        $('#MBFechaI').css('visibility', 'hidden');
        $('#MBFechaF').css('visibility', 'hidden');
        $('#Label10').css('visibility', 'hidden');
        $('#Label9').css('visibility', 'hidden');
    }
}

function OpcCC_Click() {
    ListarSubCtas('CC');
    $('#Label1').css('visibility', 'hidden'); //Label1 Visible False
    $('#TextPresupuesto').css('visibility', 'hidden'); //TextPresupuesto Visible False
    $('#TxtCodigo').prop('disabled', false); //TxtCodigo Enabled True
    $('#TxtNivel').prop('disabled', true); //TxtNivel Enabled False
    $('#CheqCaja').css('visibility', 'hidden'); //CheqCaja Visible False
    $('#LabelCheqCaja').css('visibility', 'hidden');
    deshabilitarbtnEliminar();
}

function OpcPM_Click() {
    ListarSubCtas('PM');
    $('#Label1').css('visibility', 'hidden'); //Label1 Visible False
    $('#TextPresupuesto').css('visibility', 'hidden'); //TextPresupuesto Visible False
    $('#TxtCodigo').prop('disabled', true); //TxtCodigo Enabled False
    $('#TxtNivel').prop('disabled', false); //TxtNivel Enabled True
    $('#CheqCaja').css('visibility', 'hidden'); //CheqCaja Visible False
    $('#LabelCheqCaja').css('visibility', 'hidden');
    $('#CheqNivel').prop('checked', true);  //CheqNivel Enabled True
    deshabilitarbtnEliminar();
}

function OpcG_Click() {
    ListarSubCtas('G');
    $('#Label1').css('visibility', 'visible'); //Label1 Visible True
    $('#TextPresupuesto').css('visibility', 'visible'); //TextPresupuesto Visible True
    $('#TxtCodigo').prop('disabled', true); //TxtCodigo Enabled False
    $('#TxtNivel').prop('disabled', false); //TxtNivel Enabled True
    $('#CheqCaja').css('visibility', 'visible'); //CheqCaja Visible True
    $('#LabelCheqCaja').css('visibility', 'visible');
    $('#CheqNivel').prop('checked', true);  //CheqNivel Enabled True
    deshabilitarbtnEliminar();
}

function OpcI_Click() {
    ListarSubCtas('I');
    $('#Label1').css('visibility', 'visible'); //Label1 Visible True
    $('#TextPresupuesto').css('visibility', 'visible'); //TextPresupuesto Visible True
    $('#TxtCodigo').prop('disabled', true); //TxtCodigo Enabled False
    $('#TxtNivel').prop('disabled', false); //TxtNivel Enabled True
    $('#CheqCaja').css('visibility', 'hidden'); //CheqCaja Visible False
    $('#LabelCheqCaja').css('visibility', 'hidden');
    $('#CheqNivel').prop('checked', true);  //CheqNivel Enabled True
    deshabilitarbtnEliminar();
}

function MarcarTexto(element) {
    element.select();
}
