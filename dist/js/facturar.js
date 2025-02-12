let FAGlobal = {};
/*window.closeModal = function () {
    $('#myModal_Abonos').modal('hide');
    Autorizar_Factura_Actual();
};*/
let Modificar = false;
let Bandera = true;
var PorCodigo = false;
let producto = ""; //para reserva
let detalle = ""; //para reserva

const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

var dataInv = [];//datos del SP.
$(document).ready(function () {
    var tipo = urlParams.get('tipo');
    tbl_fact = $('#tbl').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        paging:false,
        searching:false,
        info:false,
    });

    tbl_suscripcion = $('#tbl_suscripcion').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        paging:false,
        searching:false,
        info:false,
    });

    $.ajax({
        type: "GET",
        url: '../controlador/facturacion/facturarC.php?Sesion=true',
        //data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            console.log(data);
        }
    });

    //let lienzoElem = ;
    $('#interfaz_facturacion').parent().css('min-height', 'inherit');
    //$('#interfaz_facturacion').parent().css('background-color', 'yellow');

    $('#TipoFactura').val(tipo);
    $('#btnReserva').prop('disabled', true);//Por defecto el btn de reserva no se puede dar clic
    Eliminar_linea('', '');
    // lineas_factura();
    numero_factura();
    DCTipoPago();
    DCMod();
    DCMedico();
    DCGrupo_No();
    DCLineas();
    FPorCodigo();
    CDesc1();
    DCEjecutivo();
    DCBodega();
    DCMarca();
    autocomplete_cliente();
    autocomplete_producto();
    LstOrden();

    DCPorcenIva('MBoxFecha', 'DCPorcenIVA');
    // Lineas_De_CxC();
    $('#DCCliente').on('select2:select', function (e) {
        var data = e.params.data.datos;
        $('#LabelCodigo').val(data.Codigo);
        $('#LabelTelefono').val(data.Telefono);
        $('#LabelRUC').val(data.CI_RUC);
        $('#Label21').val(data.Actividad);
        $('#Label24').val(data.Direccion);
        $('#TxtEmail').val(data.Email);
        $('#LblSaldo').val(parseFloat(data.Saldo_Pendiente).toFixed(2));
        $('#Label13').text('C.I./R.U.C. (' + data.TD + ')');
    });

    $('#TxtEmail').on('focus', (e) => {
        if($('#DCCliente').val()==''){
            Swal.fire('Por favor, seleccione un cliente', '', 'error');
        }
    })

    $('#cambiar_nombre').on('hide.bs.modal', function () {

        setTimeout(function () { $('#TextComEjec').focus(); }, 500);
        // alert('asda');
    })

    $('#TxtDetalle').keydown(function (e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode == 9) {
            $('#TextComEjec').focus();
        }
    })

    var servicio = $('#Servicio').val();
    if (servicio != '0') {
        $('#label36').text(`Servicio ${servicio}%`);
    } else {
        $('#label36').text("Servicio");
    }

    /*let lienzoElem = $('#interfaz_facturacion').parent();

    let tamanoLienzo = lienzoElem[0].parentElement.style.minHeight;
    console.log(tamanoLienzo);*/
    //inicioAbono();
    //inicioAbonoAnticipado();

});

document.addEventListener('DOMContentLoaded', () => {
    //inicioAbonoAnticipado();
})

function fechaSistema() {
    var fecha = new Date();
    var año = fecha.getFullYear();
    var mes = ('0' + (fecha.getMonth() + 1)).slice(-2); // Sumamos 1 al mes porque en JavaScript los meses van de 0 a 11
    var dia = ('0' + fecha.getDate()).slice(-2);

    // Formatear la fecha como 'YYYY-MM-DD'
    var fechaFormateada = año + '-' + mes + '-' + dia;

    return fechaFormateada;
}

function DCTipoPago() {

    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?DCTipoPago=true',
        //data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            llenarComboList(data, 'DCTipoPago');
        }
    });

}
function DCMod() {

    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?DCMod=true',
        //data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            if (data.length > 0) {
                llenarComboList(data, 'DCMod');
            } else {
                $('#DCMod').css('display', 'block');
            }
        }
    });

}


function DCMedico() {

    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?DCMedico=true',
        //data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            if (data.length > 0) {
                llenarComboList(data, 'DCMedico');
            } else {
                $('#DCMedico').css('display', 'none');

            }
        }
    });

}


function DCGrupo_No() {
    $('#DCGrupo_No').select2({
        placeholder: 'Grupo',
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCGrupo_No=true',
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


function DCLineas() {
    var parametros =
    {
        'Fecha': $('#MBoxFecha').val(),
        'TC': $('#TipoFactura').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?DCLineas=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            llenarComboList(data, 'DCLineas');
            $('#DCLineas').trigger('change');
            $('#Cod_CxC').val(data[0].nombre);  //FA
            //Lineas_De_CxC();
        }
    });
}

function FPorCodigo() {
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?PorCodigo=true',
        // data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            if (data != 0) {
                PorCodigo = true;
            }
        }
    });
}

function Lineas_De_CxC(TC, cod_CXC) {
    if (TC != '') {
        TC = $('#TipoFactura').val();

    }
    if (cod_CXC != '') {
        // cod_CXC = $('#DCLineas option:selected').text();
        cod_CXC = $('#Cod_CxC').val();
    }
    var parametros =
    {
        'TC': TC,
        'Fecha': $('#MBoxFecha').val(),
        'Cod_CxC': $('#DCLineas option:selected').text(),
        'Vencimiento': $('#MBoxFechaV').val(),
    }
    //console.log(parametros['Cod_CxC']);

    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?Lineas_De_CxC=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            $("#TC").val(data.TFA.TC);   //FA
            $("#Autorizacion").val(data.TFA.Autorizacion);   //FA
            $("#CantFact").val(data.TFA.CantFact);   //FA
            $("#Cant_Item").val(data.TFA.Cant_Item_FA);   //FA
            $("#Cod_CxC").val(data.TFA.Cod_CxC);   //FA
            $("#Cta_CxP").val(data.TFA.Cta_CxP);   //FA
            $("#Cta_CxP_Anterior").val(data.TFA.Cta_CxP_Anterior);   //FA
            $("#Cta_Venta").val(data.TFA.Cta_Venta);   //FA
            $("#CxC_Clientes").val(data.TFA.CxC_Clientes);   //FA

            $("#DireccionEstab").val(data.TFA.DireccionEstab);   //FA
            $("#Fecha").val(data.TFA.Fecha);   //FA
            $("#Fecha_Aut").val(data.TFA.Fecha_Aut.date);   //FA
            $("#Fecha_NC").val(data.TFA.Fecha_NC);   //FA
            $("#Imp_Mes").val(data.TFA.Imp_Mes);   //FA


            $("#NoFactura").val(data.TFA.NoFactura);   //FA
            $("#NombreEstab").val(data.TFA.NombreEstab);   //FA
            $("#Porc_IVA").val(data.TFA.Porc_IVA);   //FA
            $("#Porc_Serv").val(data.TFA.Porc_Serv);   //FA
            $("#Pos_Copia").val(data.TFA.Pos_Copia);   //FA
            $("#Pos_Factura").val(data.TFA.Pos_Factura);   //FA
            $("#Serie").val(data.TFA.Serie);   //FA
            $("#TelefonoEstab").val(data.TFA.TelefonoEstab);   //FA
            $("#Vencimiento").val(data.TFA.Vencimiento.date);
            FAGlobal = data.TFA;

            if (data.respuesta == 1) {
                Tipo_De_Facturacion(data.TFA);
                $('#Cant_Item').val(data.TFA.Cant_Item_FA); //FA
            } else if (data.respuesta == 2) {
                Tipo_De_Facturacion(data.TFA);
                $('#Cant_Item').val(data.TFA.Cant_Item_FA); //FA
                swal.fire(data.mensaje, '', 'info');
            } else {
                swal.fire(data.mensaje, '', 'info');
                Tipo_De_Facturacion(data.TFA);
                $('#Cant_Item').val(data.TFA.Cant_Item_FA); //FA
            }
        }
    });
}

function Command8_Click() {
    if ($('#DCCiudadI').val() == '' || $('#DCCiudadF').val() == '' || $('#DCRazonSocial').val() == '' || $('#DCEmpresaEntrega').val() == '') {
        swal.fire('Llene todo lso campos', '', 'info');
        return false;
    }
    $('#ClaveAcceso_GR').val('.');
    $('#Autorizacion_GR').val($('#LblAutGuiaRem').val());
    var DCserie = $('#DCSerieGR').val();
    if (DCserie == '') { DCserie = '0_0'; }
    var serie = DCserie.split('_');
    $('#Serie_GR').val(serie[1]);
    $('#Remision').val($('#LblGuiaR').val());
    $('#FechaGRE').val($('#MBoxFechaGRE').val());
    $('#FechaGRI').val($('#MBoxFechaGRI').val());
    $('#FechaGRF').val($('#MBoxFechaGRF').val());
    $('#Placa_Vehiculo').val($('#TxtPlaca').val());
    $('#Lugar_Entrega').val($('#TxtLugarEntrega').val());
    $('#Zona').val($('#TxtZona').val());
    $('#CiudadGRI').val($('#DCCiudadI option:selected').text());
    $('#CiudadGRF').val($('#DCCiudadF option:selected').text());

    var nom = $('#DCRazonSocial').val();
    ci = nom.split('_');
    $('#Comercial').val($('#DCRazonSocial option:selected').text());
    $('#CIRUCComercial').val(ci[0]);
    var nom1 = $('#DCEmpresaEntrega').val();
    ci1 = nom1.split('_');
    $('#Entrega').val($('#DCEmpresaEntrega option:selected').text());
    $('#CIRUCEntrega').val(ci1[0]);
    $('#Dir_EntregaGR').val(ci1[1]);
    sms = "Guia de Remision: " + serie[1] + "-" + $('#LblGuiaR').val() + "  Autorizacion: " + $('#LblAutGuiaRem').val();
    $('#LblGuia').val(sms);
    $('#myModal_guia').modal('hide');

    /*console.log('ClaveAcceso_GR:', '.');
    console.log('Autorizacion_GR:', $('#LblAutGuiaRem').val());
    console.log('Serie_GR:', serie[1]);
    console.log('Remision:', $('#LblGuiaR').val());
    console.log('FechaGRE:', $('#MBoxFechaGRE').val());
    console.log('FechaGRI:', $('#MBoxFechaGRI').val());
    console.log('FechaGRF:', $('#MBoxFechaGRF').val());
    console.log('Placa_Vehiculo:', $('#TxtPlaca').val());
    console.log('Lugar_Entrega:', $('#TxtLugarEntrega').val());
    console.log('Zona:', $('#TxtZona').val());
    console.log('CiudadGRI:', $('#DCCiudadI option:selected').text());
    console.log('CiudadGRF:', $('#DCCiudadF option:selected').text());
    console.log('Comercial:', $('#DCRazonSocial option:selected').text());
    console.log('CIRUCComercial:', ci[0]);
    console.log('Entrega:', $('#DCEmpresaEntrega option:selected').text());
    console.log('CIRUCEntrega:', ci1[0]);
    console.log('Dir_EntregaGR:', ci1[1]);
    console.log('LblGuia:', sms);*/

}

function Tipo_De_Facturacion(data) {
    // console.log(data.Autorizacion);
    // console.log(data.Serie);
    // console.log(data.Porc_IVA);
    var TC = data.TC;
    if (TC == "NV") {
        // Facturas.Caption = "INGRESAR NOTA DE VENTA"
        $('#label2').text(data.Autorizacion + " NOTA DE VENTA No. " + data.Serie + "-");
        $('#label3').text("I.V.A. 0.00%");
    } else if (TC == "OP") {
        // Facturas.Caption = "INGRESAR ORDEN DE PEDIDO"
        $('#label2').text(data.Autorizacion + " ORDEN No. " + data.Serie + "-");
        $('#label3').text("I.V.A. 0.00%");
        $('#TextFacturaNo').val(data.NoFactura);
        $('#TextFact').val(data.NoFactura);
    } else {
        // Facturas.Caption = "INGRESAR FACTURA"
        $('#label2').text(data.Autorizacion + " FACTURA No. " + data.Serie + "-");
        //$('#label3').text("I.V.A. " + (parseFloat(data.Porc_IVA) * 100).toFixed(2) + "%")
        $('#TextFacturaNo').val(data.NoFactura);
        $('#TextFact').val(data.NoFactura);
    }
    // 'Facturas.Caption = Facturas.Caption & " (" & FA.TC & ")"
    //$('#label36').text("Servicio " + (data.Porc_Serv * 100).toFixed(2) + "%")
}

function cambiar_iva(valor) {
    $('#label3').text('I.V.A. ' + parseFloat(valor).toFixed(2) + '%');
}

function DCEjecutivo() {
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?DCEjecutivo=true',
        //data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            if (data.length > 0) {
                llenarComboList(data, 'DCEjecutivo');
            } else {
                $('#DCEjecutivo').append($('<option>', { value: '.', text: '.', selected: true }));
                $('#DCMedico').css('display', 'none');

            }
        }
    });

}




function lineas_factura() {
    tbl_fact.destroy();

    let altoContTbl = document.getElementById('interfaz_tabla').clientHeight;

    tbl_fact = $('#tbl').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        /*columnDefs: [
            { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
        ],*/
        ajax: {
            url: '../controlador/facturacion/facturarC.php?lineas_factura1=true',
            type: 'POST',  // Cambia el método a POST    
            data: function(d) {
                var parametros = {
                  'codigoCliente': '',
                    'tamanioTblBody': altoContTbl <= 25 ? 0 : altoContTbl - 12,
                };
                return { parametros: parametros };
            },
            dataSrc: '',             
        },
          scrollX: true,  // Habilitar desplazamiento horizontal
            paging:false,
            searching:false,
            info:false,
            scrollY: 330,
            scrollCollapse: true,
        columns: [
            { data: null,
                render: function(data, type, item) {
                    return `<button type="button" class="btn btn-sm btn-danger" onclick="Eliminar_linea('${item.A_No}','${item.CODIGO}')" title="Eliminar linea"><i class="bx bx-trash"></i></button>`;
                } 
            },
            { data: 'CODIGO'},
            { data: 'CANT',  
                render: function(data, type, item) {
                    return data ? parseInt(data) : 0;
                }
            },
            { data: 'CANT_BONIF',  
                render: function(data, type, item) {
                    return data ? parseInt(data) : 0;
                }
            },
            { data: 'PRODUCTO' },
            { data: 'PRECIO' },
            { data: 'Total_Desc' },
            { data: 'Total_Desc2' },
            { data: 'Total_IVA' },
            { data: 'SERVICIO' },
            { data: 'TOTAL' },
            { data: 'VALOR_TOTAL' },
            { data: 'COSTO' },
            { data: 'Fecha_IN.date',  
                render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                }
            },
            { data: 'Fecha_OUT.date',  
                render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                }
            },
            { data: 'Cant_Hab' },
            { data: 'Tipo_Hab' },
            { data: 'Orden_No' },
            { data: 'Mes' },
            { data: 'Cod_Ejec' },
            { data: 'Porc_C' },
            { data: 'REP' },
            { data: 'FECHA.date',  
                render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                }
            },
            { data: 'CODIGO_L' },
            { data: 'HABIT' },
            { data: 'RUTA' },
            { data: 'TICKET' },
            { data: 'Cta' },
            { data: 'Cta_SubMod' },
            { data: 'Item' },
            { data: 'CodigoU' },
            { data: 'CodBod' },
            { data: 'CodMar' },
            { data: 'TONELAJE' },
            { data: 'CORTE' },
            { data: 'A_No' },
            { data: 'Codigo_Cliente' },
            { data: 'Numero' },
            { data: 'Serie' },
            { data: 'Autorizacion' },
            { data: 'Codigo_B' },
            { data: 'PRECIO2' },
            { data: 'COD_BAR' },
            { data: 'Fecha_V.date',  
                render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                }
            },
            { data: 'Lote_No' },
            { data: 'Fecha_Fab.date',  
                render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                }
            },
            { data: 'Fecha_Exp.date',  
                render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                }
            },
            { data: 'Reg_Sanitario' },
            { data: 'Modelo' },
            { data: 'Procedencia' },
            { data: 'Serie_No' },
            { data: 'Cta_Inv' },
            { data: 'Cta_Costo' },
            { data: 'Estado' },
            { data: 'NoMes' },
            { data: 'Cheking' },
            { data: 'ID' }
        ]
    });

    var parametros =
    {
        'codigoCliente': '',
        'tamanioTblBody': altoContTbl <= 25 ? 0 : altoContTbl - 12,
    }

    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?lineas_factura2=true',
        data: {parametros: parametros},
        dataType: 'json',
        //beforeSend: function () { $('#tbl').html('<div style="height: 100%;width: 100%;display:flex;justify-content:center;align-items:center;"><img src="../../img/gif/loader4.1.gif" width="20%"></div> '); },
        success: function (data) {
            //$('#tbl').html(data.tbl);
            //$('#tbl').css('height', '100%');
            //$('#tbl tbody').css('height', '100%');
            $('#Mod_PVP').val(data.Mod_PVP);
            if (data.DCEjecutivo == 0) {
                $('#DCEjecutivoFrom').css('display', 'none');
            }
            if (data.TextFacturaNo == 0) {
                $('#TextFacturaNo').attr('readonly', true);
            }

            var servicio = $('#Servicio').val();
            var tot_sinIva = data.totales.Sin_IVA;
            var desc = data.totales.Descuento;
            var tot_serv = (tot_sinIva - desc) * (servicio / 100)


            $('#LabelSubTotal').val(parseFloat(data.totales.Sin_IVA).toFixed(2));
            $('#LabelConIVA').val(parseFloat(data.totales.Con_IVA).toFixed(2));
            $('#TextDesc').val(parseFloat(data.totales.Descuento).toFixed(2));
            $('#LabelServ').val(parseFloat(tot_serv).toFixed(2));
            $('#LabelIVA').val(parseFloat(data.totales.Total_IVA).toFixed(2));
            $('#LabelTotal').val(parseFloat(data.totales.Total_MN).toFixed(2));

        }
    });

}



function DCBodega() {

    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?DCBodega=true',
        //data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            llenarComboList(data, 'DCBodega');
        }
    });

}


function DCMarca() {

    /*$.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?DCMarca=true',
        //data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            llenarComboList(data, 'DCMarca');
        }
    });*/

    $('#DCMarca').select2({
        placeholder: 'Seleccione',
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCMarca=true',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                }
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

}


function CDesc1() {

    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?CDesc1=true',
        //data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            llenarComboList(data, 'CDesc1');
        }
    });

}



function autocomplete_cliente() {
    var grupo = $('#DCGrupo_No').val();
    $('#DCCliente').select2({
        placeholder: 'Seleccione un cliente',
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCCliente=true&Grupo=' + grupo,
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

function autocomplete_producto() {
    /*var marca = $('#DCMarca').val();
    var cod_marca = $('#DCMarca').val();*/
    // console.log(grupo);
    $('#DCArticulos').select2({
        placeholder: 'Producto',
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCArticulos=true', //&marca=' + marca + '&codMarca=' + cod_marca
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    marca: $('#DCMarca').val(),
                    codMarca: $('#DCMarca').val()
                }
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function LstOrden() {
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?LstOrden=true',
        // data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {

        }
    });

}

function numero_factura() {
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?numero_factura=true',
        // data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            data.CheqSP == true ? $('#CheqSPFrom').css('visibility', 'visible') : $('#CheqSPFrom').css('visibility', 'hidden');
        }
    });


}

function DCArticulo_LostFocus() {
    var parametros = {
        'codigo': $('#DCArticulos').val(),
        'fecha': $('#MBoxFecha').val(),
        'bodega': $('#DCBodega').val(),
        'marca': $('#DCMarca').val(),
        'tipoFactura': $('#TipoFactura').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?DCArticulo_LostFocus=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            console.log(data);
            $('#TextVUnit').val(data.TextVUnit);
            $('#LabelStock').val(data.labelstock);
            $('#LabelStockArt').html(data.LabelStockArt);
            $('#TextComEjec').val(data.TextComEjec);
            $('#TxtDetalle').val(data.TxtDetalle);
            $('#BanIVA').val(data.baniva);
            producto = data.producto;
            detalle = data.TxtDetalle;
            dataInv = data;
            data.por_reserva ? $('#btnReserva').prop('disabled', false) : $('#btnReserva').prop('disabled', true);
            // $('#DCArticulos').focus();
            // $('#cambiar_nombre').modal('show');

            let inputArticulos = document.getElementById('DCArticulos').getBoundingClientRect();
            let ia_top = inputArticulos.top + 28;
            let ia_left = inputArticulos.left;

            let ia_width = document.querySelector('#DCArticulos + span').getBoundingClientRect().width;

            document.querySelector('#cambiar_nombre div').style = "margin-top:"+ia_top+"px; margin-left:"+ia_left+"px;";
            document.querySelector('#cambiar_nombre div div').style.width = ia_width+"px";

            $('#cambiar_nombre').on('shown.bs.modal', function () {
                $('#TxtDetalle').focus();
            })

            $('#cambiar_nombre').modal('show', function () {
                $('#TxtDetalle').focus();
            })

        }
    })

}


function cerrar_modal_cambio_nombre() {

    $('#cambiar_nombre').modal('hide');

    var nuevo = $('#TxtDetalle').val();
    var dcart = $('#DCArticulos').val();
    $('#DCArticulos').append($('<option>', { value: dcart, text: nuevo, selected: true }));
    // $('#TextComEjec').focus();

}

function TextCant_Change() {
    var Real1 = 0;
    if ($('#TextCant').val() == "") { $('#TextCant').val(0); }
    if ($('#TextVUnit').val() == "") { $('#TextVUnit').val(0) }

    if ($('#TextCant').val() != 0 && $('#TextVUnit').val() != 0) { var Real1 = $('#TextCant').val() * $('#TextVUnit').val() }
    $('#LabelVTotal').val(Real1.toFixed(2));
}

function TextVUnit_LostFocus() {
    if ($('#DCCliente').val() == '') {
        Swal.fire('Seleccione un cliente', '', 'info');
        return false;
    }
    if($('#TextCant').val() == '' || $('#TextCant').val() == 0){
        Swal.fire('Ingrese una cantidad valida', '', 'info');
        return false;
    }
    var parametros = {
        'codigo': $('#DCArticulos').val(),
        'fecha': $('#MBoxFecha').val(),
        'fechaV': $('#MBoxFechaV').val(),
        'fechaVGR': $('#MBoxFechaV').val(), //ojo poner el verdadero
        'TxtDetalle': $('#TxtDetalle').val(),
        'bodega': $('#DCBodega').val(),
        'marca': $('#DCMarca').val(),
        'Cliente': $('#DCCliente').val(),
        'Cant_Item_FA': $('#Cant_Item').val(),
        'tipoFactura': $('#TipoFactura').val(),
        'Mod_PVP': $('#Mod_PVP').val(),
        'DatInv_Serie_No': $('#DatInv_Serie_No').val(),
        'TextVUnit': $('#TextVUnit').val(),
        'TextCant': $('#TextCant').val(),
        'TextFacturaNo': $('#TextFacturaNo').val(),
        'TextComision': $('#TextComision').val(),
        'CDesc1': $('#CDesc1').val(),
        'BanIVA': $('#BanIVA').val(),
        'TextComEjec': $('#TextComEjec').val(),
        'SubCta': '.',
        'Cod_Ejec': $('#DCEjecutivo').val(),
        'CodigoL': $('#DCLineas').val(),
        'MBFechaIn': $('#MBoxFechaV').val(), //ojo poner el verdadero
        'MBFechaOut': $('#MBoxFechaV').val(), //ojo poner el verdadero
        'TxtCantRooms': '.',//$('#MBoxFechaV').val(), //ojo poner el verdadero  	  	
        'TxtTipoRooms': '.',//$('#MBoxFechaV').val(), //ojo poner el verdadero
        'LstOrden': '.',//$('#MBoxFechaV').val(), //ojo poner el verdadero
        'Sec_Public': $('#CheqSP').prop('checked'),
        'PorcIva': $('#DCPorcenIVA').val()
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?TextVUnit_LostFocus=true',
        data: { parametros: parametros },
        dataType: 'json',
        //beforeSend: function () { $('#tbl').html('<img src="../../img/gif/loader4.1.gif" width="40%"> '); },
        success: function (data) {
            if (data == 1) {
                lineas_factura();
            } else {
                swal.fire(data, '', 'info');
            }

        }
    })
}


function Eliminar_linea(ln_No, Cod) {
    var parametros = {
        'codigo': Cod,
        'ln_No': ln_No,
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?Eliminar_linea=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            if (data == 1) {
                lineas_factura();
            }
        }
    })


}

function addCliente() {
    $("#myModal_cliente").modal("show");
    var src = "../vista/modales.php?FCliente=true";
    $('#FCliente').attr('src', src).show();
}

function DCLinea_LostFocus() {
    Lineas_De_CxC();
}

function boton1() {
    var TC = $('#TipoFactura').val();
    var FAC = $('#TextFacturaNo').val();
    if (TC == 'OP') {
        Mensajes = "La Orden de Producción No. " + FAC;
    } else {
        Mensajes = "La Factura No. " + FAC
    }
    Swal.fire({
        title: 'Esta Seguro que desea grabar?',
        text: Mensajes,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
    }).then((result) => {
        if (result.value == true) {
            Grabar_Factura_Actual();
        }
    })
}

function Grabar_Factura_Actual() {
    var FA = $("#FA").serialize();
    var parametros = {
        'TextObs': $('#TextObs').val(),
        'TextNota': $('#TextNota').val(),
        'TxtCompra': $('#TxtCompra').val(),
        'TxtPedido': $('#TxtPedido').val(),
        'TxtZona': $('#TxtZona').val(),
        'TxtLugarEntrega': $('#TxtLugarEntrega').val(),
        'TextComision': $('#TextComision').val(),
        'MBoxFechaV': $('#MBoxFechaV').val(),
        'Check1': $('#Check1').prop('checked'),
        'CheqSP': $('#CheqSP').prop('checked'),
        // 'DCTipoPago':$('#DCTipoPago option:selected').text(),
        'DCTipoPago': $('#DCTipoPago').val(),
        'TextFacturaNo': $('#TextFacturaNo').val(),
        'DCMod': $('#DCMod').val(),
        'Reprocesar': $('#Reprocesar').val(),
        'Cliente': $('#DCCliente').val(),
        'Total': $('#LabelTotal').val(),
        'TC': $('#TC').val(),
        'Serie': $('#Serie').val(),
        'Autorizacion': $('#Autorizacion').val(),
        'FA': FAGlobal,
        'Fecha':$('#MBoxFecha').val(),
        'PorcIva':$('#DCPorcenIVA').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?Grabar_Factura_Actual=true&' + FA,
        data: { parametros: parametros },
        dataType: 'json',
        beforeSend:function(){$('#myModal_espera').modal('show');},
        success: function (data) {
            $('#myModal_espera').modal('hide');
            if (data.res == -2) {
                alerta_reprocesar('ADVERTENCIA', data.men);
            } else if (data.res == -3) {
                alerta_reprocesar('Formulario de Confirmación', data.men);
            } else if (data.res == 1) {
                Abonos(data.data);
                //Autorizar_Factura_Actual(data.data);
            } else if (data.res == -1) {
                Swal.fire({
                    title: 'Algo salió mal',
                    text: data.men,
                    type: 'error',
                    confirmButtonText: 'Ok!',
                })
            }

        }
    })
}

function Autorizar_Factura_Actual2() {
    var FA = $("#FA").serialize();
    var parametros = {
        'TextObs': $('#TextObs').val(),
        'TextNota': $('#TextNota').val(),
        'TxtCompra': $('#TxtCompra').val(),
        'TxtPedido': $('#TxtPedido').val(),
        'TxtZona': $('#TxtZona').val(),
        'TxtLugarEntrega': $('#TxtLugarEntrega').val(),
        'TextComision': $('#TextComision').val(),
        'MBoxFechaV': $('#MBoxFechaV').val(),
        'Check1': $('#Check1').prop('checked'),
        'CheqSP': $('#CheqSP').prop('checked'),
        // 'DCTipoPago':$('#DCTipoPago option:selected').text(),
        'DCTipoPago': $('#DCTipoPago').val(),
        'TextFacturaNo': $('#TextFacturaNo').val(),
        'DCMod': $('#DCMod').val(),
        'Reprocesar': $('#Reprocesar').val(),
        'Cliente': $('#DCCliente').val(),
        'Total': $('#LabelTotal').val(),
    }

    // var url=  '../controlador/facturacion/facturarC.php?Autorizar_Factura_Actual=true&'+FA+'&'+parametros.serialize();;
    // window.open(url, '_blank'); 
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?Autorizar_Factura_Actual=true&' + FA,
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            var url = '../vista/TEMP/' + data + '.pdf';
            window.open(url, '_blank');
        }
    })
}


function Autorizar_Factura_Actual(FAc) {
    $('#myModal_espera').modal('show');
    var FA = $("#FA").serialize();
    var parametros = {
        'TextObs': $('#TextObs').val(),
        'TextNota': $('#TextNota').val(),
        'TxtCompra': $('#TxtCompra').val(),
        'TxtPedido': $('#TxtPedido').val(),
        'TxtZona': $('#TxtZona').val(),
        'TxtLugarEntrega': $('#TxtLugarEntrega').val(),
        'TextComision': $('#TextComision').val(),
        'MBoxFechaV': $('#MBoxFechaV').val(),
        'Check1': $('#Check1').prop('checked'),
        'CheqSP': $('#CheqSP').prop('checked'),
        // 'DCTipoPago':$('#DCTipoPago option:selected').text(),
        'DCTipoPago': $('#DCTipoPago').val(),
        'TextFacturaNo': $('#TextFacturaNo').val(),
        'DCMod': $('#DCMod').val(),
        'Reprocesar': $('#Reprocesar').val(),
        'Cliente': $('#DCCliente').val(),
        'Total': $('#LabelTotal').val(),
        'FA': FAc,
        'PorcIva': $('#DCPorcenIVA').val()
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?Autorizar_Factura_Actual=true&' + FA,
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {

            $('#myModal_espera').modal('hide');
            if (data.AU == 1) {
                /*var url = '../../TEMP/' + data.pdf + '.pdf';
                window.open(url, '_blank');*/
                Swal.fire('Factura Creada y Autorizada', '', 'success')
                .then((result)=>{
                    var url_fa = '../../TEMP/' + data.pdf + '.pdf';
                    window.open(url_fa, '_blank');
                    if(data.GR != '') abrirPDFGuiaDeRemision(data);
                });
                Eliminar_linea('', '');
            } else {
                Swal.fire('Factura creada pero no autorizada', '' , 'warning')
                .then((result) => {
                    var url_fa = '../../TEMP/' + data.pdf + '.pdf';
                    window.open(url_fa, '_blank');
                    if(data.GR != '') abrirPDFGuiaDeRemision(data);
                });
                /*var url = '../../TEMP/' + data.pdf + '.pdf';
                window.open(url, '_blank');*/
                Eliminar_linea('', '');
            }
        }
    })
}

function abrirPDFGuiaDeRemision(data){
    if (data.GR == 1) {
        /*var url = '../../TEMP/' + data.pdf + '.pdf';
        window.open(url, '_blank');*/
        Swal.fire('Guia de Remision Creada y Autorizada', '', 'success')
        .then((result)=>{
            var url_guia = '../../TEMP/' + data.pdf_guia + '.pdf';
            window.open(url_guia, '_blank');
        });
    } else {
        Swal.fire('Guia de Remision creada pero no autorizada', '' , 'warning')
        .then((result) => {
            var url_guia = '../../TEMP/' + data.pdf_guia + '.pdf';
            window.open(url_guia, '_blank');
        });
        /*var url = '../../TEMP/' + data.pdf + '.pdf';
        window.open(url, '_blank');*/
    }
}


function Abonos(FA) {
    /*Swal.fire({
        title: 'PAGO AL CONTADO',
        text: '',
        type: 'warning',
        confirmButtonText: 'Sí!',
        showCancelButton: true,
        allowOutsideClick: false,
        cancelButtonText: 'No!'
    }).then((result) => {
        if (result.value == true) {
            if (FA['TC'] == "OP") {
                Swal.fire({
                    title: 'Formulario de Grabación',
                    text: 'Anticipo de Abono',
                    type: 'info',
                    confirmButtonText: 'Sí!',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    cancelButtonText: 'No!'
                }).then((result) => {
                    if (result.value == true) {
                        var grupo = $('#DCGrupo_No').val();
                        var faFactura = $('#TextFacturaNo').val();
                        src = "../vista/modales.php?FAbonoAnticipado=true&tipo=FA&grupo=" + grupo + "&faFactura=" + faFactura;
                        $('#frame_anticipado').attr('src', src).show();
                        $('#my_modal_abono_anticipado').modal('show').on('hidden.bs.modal', function () {
                            Autorizar_Factura_Actual(FA);
                        })
                    }
                })
            } else {
                Swal.fire({
                    title: 'Formulario de Grabación',
                    text: 'Pago al Contado',
                    type: 'info',
                    confirmButtonText: 'Sí!',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    cancelButtonText: 'No!'
                }).then((result) => {
                    if (result.value == true) {
                        src = "../vista/modales.php?FAbonos=true";
                        $('#frame').attr('src', src).show();
                        $('#my_modal_abonos').modal('show').on('hidden.bs.modal', function () {
                            Autorizar_Factura_Actual(FA);
                        })
                    }
                })
            }
        } else {
            Autorizar_Factura_Actual(FA);
        }

    })*/
    if (FA['TC'] == "OP") {
        Swal.fire({
            title: 'Formulario de Grabación',
            text: 'Anticipo de Abono',
            type: 'info',
            confirmButtonText: 'Sí!',
            showCancelButton: true,
            allowOutsideClick: false,
            cancelButtonText: 'No!'
        }).then((result) => {
            if (result.value == true) {
                /*var grupo = $('#DCGrupo_No').val();
                var faFactura = $('#TextFacturaNo').val();
                src = "../vista/modales.php?FAbonoAnticipado=true&tipo=FA&grupo=" + grupo + "&faFactura=" + faFactura;
                $('#frame_anticipado').attr('src', src).show();*/
                inicioAbonoAnticipado();
                $('#my_modal_abono_anticipado').modal('show').on('hidden.bs.modal', function () {
                    Autorizar_Factura_Actual(FA);
                })
            }else{
                Autorizar_Factura_Actual(FA);
            }
        })
    } else {
        Swal.fire({
            title: 'Formulario de Grabación',
            text: 'Pago al Contado',
            type: 'info',
            confirmButtonText: 'Sí!',
            showCancelButton: true,
            allowOutsideClick: false,
            cancelButtonText: 'No!'
        }).then((result) => {
            if (result.value == true) {
                /*src = "../vista/modales.php?FAbonos=true";
                $('#frame').attr('src', src).show();*/
                inicioAbono();
                $('#my_modal_abonos').modal('show').on('hidden.bs.modal', function () {
                    Autorizar_Factura_Actual(FA);
                })
            }else{
                Autorizar_Factura_Actual(FA);
            }
        })
    }
}



function alerta_reprocesar(tit, mensaje) {
    Swal.fire({
        title: tit,
        text: mensaje,
        type: 'warning',
        confirmButtonText: 'Sí!',
        showCancelButton: true,
        allowOutsideClick: false,
        cancelButtonText: 'No!'
    }).then((result) => {
        if (result.value == true) {
            $('#Reprocesar').val(1)
            Grabar_Factura_Actual();
        } else {
            $('#Reprocesar').val(0)
        }
    })

}

function alerta_abonos(tit, mensaje) {
    Swal.fire({
        title: tit,
        text: mensaje,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
    }).then((result) => {
        if (result.value == true) {
            $('#Reprocesar').val(1)
            Grabar_Factura_Actual();
        } else {
            $('#Reprocesar').val(0)
        }
    })

}



function Grabar_Abonos() {
    var FA = $("#FA").serialize();
    var parametros = {
        'TextObs': $('#TextObs').val(),
        'TextNota': $('#TextNota').val(),
        'TxtCompra': $('#TxtCompra').val(),
        'TxtPedido': $('#TxtPedido').val(),
        'TxtZona': $('#TxtZona').val(),
        'TxtLugarEntrega': $('#TxtLugarEntrega').val(),
        'TextComision': $('#TextComision').val(),
        'MBoxFechaV': $('#MBoxFechaV').val(),
        'Check1': $('#Check1').prop('checked'),
        'CheqSP': $('#CheqSP').prop('checked'),
        // 'DCTipoPago':$('#DCTipoPago option:selected').text(),
        'DCTipoPago': $('#DCTipoPago').val(),
        'TextFacturaNo': $('#TextFacturaNo').val(),
        'DCMod': $('#DCMod').val(),
        'Reprocesar': $('#Reprocesar').val(),
        'Cliente': $('#DCCliente').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?Grabar_Factura_Actual=true&' + FA,
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            console.log(data);
            if (data.res == -2) {
                alerta_reprocesar('ADVERTENCIA', data.men);
            } else if (data.res == -3) {
                alerta_reprocesar('Formulario de Confirmación', data.men);
            }

        }
    })
}

function boton2() {
    DCBodega();
    DCMarca();
    autocomplete_producto();

}
function boton3() {
    Listar_Ordenes();
}
function boton4() {
    $('#myModal_guia').modal('show');
    DCCiudadI();
    DCCiudadF();
    AdoPersonas();
    DCEmpresaEntrega();

}
function boton5() {
    $('#myModal_suscripcion').modal('show');
    $('#LblClienteCod').val($('#LabelCodigo').val());
    $('#form_suscripcion #LblCliente').val($('#DCCliente option:selected').text());
    delete_asientoP();
    DGSuscripcion();
    DCCtaVenta();
    DCEjecutivoModal();

}
function boton6() {
    $('#myModal_reserva').modal('show');




    // src ="../vista/modales.php?FAbonos=true";
    // $('#frame').attr('src',src).show();
    // $('#myModal_Abonos').modal('show');

    // $.ajax({
    // 		  type: "POST",
    // 		  url: '../controlador/facturacion/facturarC.php?imprimir=true',
    // 		  // data: {parametros:parametros }, 
    // 		  dataType:'json',
    // 		  success: function(data)
    // 		  {
    // 		  	if(data.length>0)
    // 		  	{

    // 		  		//llena un alista
    // 		  	}else
    // 		  	{
    // 		  		Swal.fire('No existe Ordenes para procesar','','info');
    // 		  	}

    // 		  }
    // 		})



}
//---------------Listar_Ordenes()------------
function Listar_Ordenes() {
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?Listar_Ordenes=true',
        // data: {parametros:parametros }, 
        dataType: 'json',
        success: function (data) {
            if (data.length > 0) {
                $('#myModal_ordenesProd').modal('show');
                // var ordenTableBody = document.getElementById("ordenTableBody");
                // ordenTableBody.innerHTML = "";

                var selectOrden = document.getElementById("selectOrden");
                //var dataTest = ["Orden1", "Orden2", "Orden3"];
                data.forEach(function (orden) {
                    var option = document.createElement("option");
                    option.value = orden;
                    option.text = orden;
                    selectOrden.appendChild(option);
                });

                // for (var i = 0; i < data.length; i++) {
                // 	var row = ordenTableBody.insertRow();
                // 	var cell = row.insertCell(0);

                // 	cell.innerHTML = data[i][0]; // "Orden No. XXXXXXXXX - Nombre del Cliente"						
                // }

            } else {
                Swal.fire('No existen órdenes para procesar', '', 'info');
            }
        }
    })
}
//---------------fin Listar_Ordenes()--------

function CommandButton1_Click() {
    $('#myModal_ordenesProd').modal('hide');
    $('#dialog_impresion').modal('show');
}

function aceptarimprimir() {
    var ordenNoString = document.getElementById("valOrden").value;
    var ordenNo = parseFloat(ordenNoString);
    var option = "";
    var LstCliente = document.getElementById("DCCliente");
    var selectedOptions = LstCliente.selectedOptions;
    for (var i = 0; i < selectedOptions.length; i++) {
        option = selectedOptions[i].text;
    }
    var parametros = {
        OrdenNo: ordenNo,
        Option: option,
    };
    console.log("facturar " + parametros['Option']);
    console.log(parametros['OrdenNo'])

    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?Detalle_impresion=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            if (data.status == 200) {
                console.log("facturarC " + data.mensajeEncabData)
                generarPDF(data.mensajeEncabData, data.datos)
            } else {
                console.log("facturarC " + data.dccliente + "" + data.ordenno)
                $('#dialog_impresion').modal('hide');
                swal.fire("No se pudo generar el pdf", '', 'info');
                //generarPDF("no hay datos que mostrar",[])
            }
        }
    });
}

function generarPDF(titulo, datos) {
    var url = '../controlador/facturacion/facturarC.php?generar_detalle=true&titulo=' + titulo;

    var datosJSON = JSON.stringify(datos);
    var datosCodificados = encodeURIComponent(datosJSON);

    url += '&datos=' + datosCodificados;
    window.open(url, '_blank');
}

//------------------ guia-------------
function DCCiudadI() {
    $('#DCCiudadI').select2({
        placeholder: 'Seleccione una ciudad',
        dropdownParent: $('#form_guia'),
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCCiudadI=true',
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
function DCCiudadF() {
    $('#DCCiudadF').select2({
        placeholder: 'Seleccione una ciudad',
        dropdownParent: $('#form_guia'),
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCCiudadF=true',
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

function AdoPersonas() {
    $('#DCRazonSocial').select2({
        placeholder: 'Seleccione',
        dropdownParent: $('#form_guia'),
        ajax: {
            url: '../controlador/facturacion/facturarC.php?AdoPersonas=true',
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
function DCEmpresaEntrega() {
    $('#DCEmpresaEntrega').select2({
        placeholder: 'Seleccione una empresa',
        dropdownParent: $('#form_guia'),
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCEmpresaEntrega=true',
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
function MBoxFechaGRE_LostFocus() {
    var parametros = {
        'MBoxFechaGRE': $('#MBoxFechaGRE').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?MBoxFechaGRE_LostFocus=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            if (data.length > 0) {
                for(let i=0; i < data.length; i++){
                    data[i]['nombre'] = data[i]['codigo'].split('_')[1];
                }
                llenarComboList(data, 'DCSerieGR');
            }

        }
    })
}

function DCSerieGR_LostFocus() {
    var DCserie = $('#DCSerieGR').val();
    serie = DCserie.split('_');
    var parametros = {
        'DCSerieGR': serie[1],
        'MBoxFechaGRE': $('#MBoxFechaGRE').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?DCSerieGR_LostFocus=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            //console.log(data);
            /*if (data.length > 0) {
                $('#LblGuiaR').val(data.Guia);
                $('#LblAutGuiaRem').val(data.Auto);
            }*/
            if (data) {
                $('#LblGuiaR').val(data.Guia);
                $('#LblAutGuiaRem').val(data.Auto);
            }
        }
    })

}




//---------------------fin de guia-------------
//--------------sucripcion--------------
function DGSuscripcion() {
    tbl_suscripcion.destroy();

    tbl_suscripcion = $('#tbl_suscripcion').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        /*columnDefs: [
            { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
        ],*/
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DGSuscripcion=true',
            type: 'POST',  // Cambia el método a POST    
            // data: function(d) {
            //     var parametros = {
            //       'codigoCliente': '',
            //         'tamanioTblBody': altoContTbl <= 25 ? 0 : altoContTbl - 12,
            //     };
            //     return { parametros: parametros };
            // },
            dataSrc: '',             
        },
          scrollX: true,  // Habilitar desplazamiento horizontal
            paging:false,
            searching:false,
            info:false,
            scrollY: 330,
            scrollCollapse: true,
        columns: [
            
            { data: 'Ejemplar' },
            { data: 'Fecha.date',  
                render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                }
            },
            { data: 'Entregado' },
            { data: 'Sector' },
            { data: 'Comision' },
            { data: 'Capital' },
            { data: 'T_No' },
            { data: 'Item' },
            { data: 'CodigoU' }
        ]
    });

    // $.ajax({
    //     type: "POST",
    //     url: '../controlador/facturacion/facturarC.php?DGSuscripcion=true',
    //     // data: {parametros:parametros }, 
    //     dataType: 'json',
    //     beforeSend: function () { $('#tbl_suscripcion').html('<img src="../../img/gif/loader4.1.gif" width="40%"> '); },
    //     success: function (data) {
    //         $('#tbl_suscripcion').html(data);
    //         // console.log(data);
    //     }
    // })
}

function DCCtaVenta() {
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?DCCtaVenta=true',
        // data: {parametros:parametros }, 
        dataType: 'json',
        success: function (data) {
            // console.log(data);
            llenarComboList(data, 'DCCtaVenta');
        }
    })
}

function DCEjecutivoModal() {
    $('#DCEjecutivoModal').select2({
        placeholder: 'Seleccione un cliente',
        dropdownParent: $('#myModal_suscripcion'),
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCEjecutivoModal=true',
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

function TextComision_LostFocus() {
    var datos = $('#form_suscripcion').serialize();
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?TextComision_LostFocus=true',
        data: datos,
        dataType: 'json',
        success: function (data) {
            $('#txtperiodo').val(data);
            DGSuscripcion();
            // console.log(data);
        }
    })
}

function Command1() {
    var datos = $('#form_suscripcion').serialize();
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?Command1=true',
        data: datos,
        dataType: 'json',
        success: function (data) {
            $('#myModal_suscripcion').modal('hide');
            // delete_asientoP();
        }
    })
}
function delete_asientoP() {
    $('#TextContrato').val('.');
    $('#TextSector').val('.');
    $('#TxtHasta').val('0.00');
    $('#TextTipo').val('.');
    //$('#TextFact').val('000');
    $('#TextValor').val('0.00');

    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?delete_asientoP=true',
        // data: datos, 
        dataType: 'json',
        success: function (data) {
            // DGSuscripcion();
            // console.log(data);
        }
    })
}
function cerrarModal() {
    $('#my_modal_abonos').modal('hide');
}
//------------------fin de suscripcion------------------

//------------------abonos------------------

function inicioAbono(){
    DCVendedor();
    DiarioCaja();
    DCBanco();
    DCTarjeta();
    DCRetFuente();
    DCRetISer();
    DCRetIBienes();
    DCCodRet();
    DCTipo();
    $('#form_abonos #DCTipo').on('change', DCSerie);
}

function DCVendedor() {
    $('#DCVendedor').select2({
        placeholder: 'Vendedor',
        dropdownParent: $('#form_abonos'),
        width: 'resolve',
        ajax: {
            url: '../controlador/contabilidad/FAbonosC.php?DCVendedor=true',
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

function DCBanco() {
    $('#form_abonos #DCBanco').select2({
        placeholder: 'Cuenta Banco',
        dropdownParent: $('#form_abonos'),
        ajax: {
            url: '../controlador/contabilidad/FAbonosC.php?DCBanco=true',
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
function DCTarjeta() {
    $('#DCTarjeta').select2({
        placeholder: 'Cuenta Banco',
        dropdownParent: $('#form_abonos'),
        ajax: {
            url: '../controlador/contabilidad/FAbonosC.php?DCTarjeta=true',
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
function DCRetFuente() {
    $('#DCRetFuente').select2({
        placeholder: 'Cuenta Banco',
        dropdownParent: $('#form_abonos'),
        ajax: {
            url: '../controlador/contabilidad/FAbonosC.php?DCRetFuente=true',
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
function DCRetISer() {
    $('#DCRetISer').select2({
        placeholder: 'Cuenta Banco',
        dropdownParent: $('#form_abonos'),
        ajax: {
            url: '../controlador/contabilidad/FAbonosC.php?DCRetISer=true',
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
function DCRetIBienes() {
    $('#DCRetIBienes').select2({
        placeholder: 'Cuenta Banco',
        dropdownParent: $('#form_abonos'),
        ajax: {
            url: '../controlador/contabilidad/FAbonosC.php?DCRetIBienes=true',
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
function DCCodRet() {
    var MBFecha = $('#form_abonos #MBFecha').val();
    $('#DCCodRet').select2({
        placeholder: 'Cuenta Banco',
        dropdownParent: $('#form_abonos'),
        ajax: {
            url: '../controlador/contabilidad/FAbonosC.php?DCCodRet=true&MBFecha=' + MBFecha,
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

    $('#DCCodRet').on('select2:select', function (e) {
        var data = e.params.data.datos;
        $('#TextPorc').val(data.Porcentaje);
    });
}
function DCTipo() {
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosC.php?DCTipo=true',
        // data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            llenarComboList(data, 'form_abonos #DCTipo');
            buscarDCSerie();
        }
    });

}

function buscarDCSerie() {
    var parametros =
    {
        'tipo': $('#form_abonos #DCTipo').val(),
        'serie': $('#DCLineas').val().slice(-6),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosC.php?DCSerie=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            if ($('#form_abonos #DCTipo').val() == 'PV') {
                $('#Label2').text('Punto de Venta No.');
            } else if ($('#form_abonos #DCTipo').val() == 'NV') {
                $('#Label2').text('Nota de Venta No.');
            } else {
                $('#Label2').text('Factura No.');
            }
            llenarComboList(data, 'DCSerie');
            DCFactura_();
        }
    });

}

function DCFactura_() {
    var parametros =
    {
        'tipo': $('#form_abonos #DCTipo').val(),
        'serie': $('#DCSerie').val(),
        'factura': $('#TextFacturaNo').val()
    }
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosC.php?DCFactura=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            llenarComboList(data, 'form_abonos #DCFactura');
        }
    });
}

function DCFactura1() {
    var parametros =
    {
        'tipo': $('#form_abonos #DCTipo').val(),
        'serie': $('#DCSerie').val(),
        'factura': $('#form_abonos #DCFactura').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosC.php?DCFactura1=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            $('#LabelSaldo').val(parseFloat(data[0].Saldo_MN).toFixed(2));
            $('#form_abonos #TextCajaMN').val(parseFloat(data[0].Saldo_MN).toFixed(2));
            $('#form_abonos #LblCliente').val(data[0].Cliente);
            $('#LblGrupo').val(data[0].Grupo);
            $('#LabelDolares').val(parseFloat(data[0].Cotizacion).toFixed(2));
            $('#Cta_Cobrar').val(data[0].Cta_CxP);
            $('#CodigoC').val(data[0].CodigoC);
            $('#CI_RUC').val(data[0].CI_RUC);
            Calculo_Saldo();
            // $('#').val(data[0].);
            FechaCorte = new Date(data[0].Fecha.date.split(' ')[0]);
            const fechaInput = new Date($('#form_abonos #MBFecha').val());
            if (fechaInput < FechaCorte) {
                Swal.fire({
                    title: 'Error',
                    text: 'No se puede grabar abonos con fecha inferior a la emision de la factura',
                    type: 'error',
                });
            }
            var dia = FechaCorte.getDate();
            var mes = FechaCorte.getMonth() + 1;
            var anio = FechaCorte.getFullYear();
            FechaCorte = `${anio}-${mes}-${dia}`;
            $('#LabelAutorizacion').text(`Autorizacion Fecha de Emisión ${FechaCorte}`);
        }
    });
}

function DiarioCaja() {
    var parametros =
    {
        'CheqRecibo': $('#form_abonos #CheqRecibo').prop('checked'),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosC.php?DiarioCaja=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            $('#form_abonos #TxtRecibo').val(data);
        }
    });
}


function DCAutorizacionF() {
    var parametros =
    {
        'tipo': $('#form_abonos #DCTipo').val(),
        'serie': $('#DCSerie').val(),
        'factura': $('#form_abonos #DCFactura').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosC.php?DCAutorizacion=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            llenarComboList(data, 'DCAutorizacion');
        }
    });

}

function Calculo_Saldo() {

    var TotalCajaMN = $('#form_abonos #TextCajaMN').val();
    var TotalCajaME = $('#TextCajaME').val();
    var Total_IVA = 0;
    var Total_Bancos = $('#TextCheque').val();
    var Total_Tarjeta = $('#TextTotalBaucher').val();
    var Total_Ret = $('#TextRet').val();
    var Total_RetIVAB = $('#TextRetIVAB').val();
    var Total_RetIVAS = $('#TextRetIVAS').val();
    var Saldo = $('#LabelSaldo').val();
    // console.log(TotalCajaMN);
    // console.log(TotalCajaME);
    // console.log(Total_IVA);
    // console.log(Total_Bancos);
    // console.log(Total_Tarjeta);
    // console.log(Total_Ret);
    // console.log(Total_RetIVAB);
    // console.log(Total_RetIVAS);
    // console.log(Saldo);
    // console.log()


    var TotalAbonos = parseFloat(TotalCajaMN) + parseFloat(TotalCajaME) + parseFloat(Total_Bancos) + parseFloat(Total_Tarjeta) + parseFloat(Total_IVA) + parseFloat(Total_Ret) + parseFloat(Total_RetIVAB) + parseFloat(Total_RetIVAS);
    var SaldoDisp = parseFloat(Saldo) - parseFloat(TotalAbonos);
    var TotalRecibido = TotalAbonos + parseFloat($('#TextInteres').val());
    $('#form_abonos #LabelPend').val(SaldoDisp.toFixed(2));
    $('#TextRecibido').val(TotalRecibido.toFixed(2));
}

function calcTextInteres() {
    var TextInteres = $('#TextInteres').val();
    if (TextInteres.substring(TextInteres.length, 1) == "%") {
        var Valor = TextInteres.substring(0, TextInteres.length - 1);
        console.log($('#form_abonos #LabelPend').val());
        TextInteres = parseFloat(Valor) * parseFloat(($('#form_abonos #LabelPend').val()) / 100);
        $('#TextInteres').val(TextInteres.toFixed(2));
    } else {
        console.log(TextInteres);
    }
    TextRecibido();
}

function TextRecibido() {
    var TotalCajaMN = $('#form_abonos #TextCajaMN').val();
    var TotalCajaME = $('#TextCajaME').val();
    var Total_IVA = 0;
    var Total_Bancos = $('#TextCheque').val();
    var Total_Tarjeta = $('#TextTotalBaucher').val();
    var Total_Ret = $('#TextRet').val();
    var Total_RetIVAB = $('#TextRetIVAB').val();
    var Total_RetIVAS = $('#TextRetIVAS').val();
    var Saldo = $('#LabelSaldo').val();
    var TotalAbonos = parseFloat(TotalCajaMN) + parseFloat(TotalCajaME) + parseFloat(Total_Bancos) + parseFloat(Total_Tarjeta) + parseFloat(Total_IVA) + parseFloat(Total_Ret) + parseFloat(Total_RetIVAB) + parseFloat(Total_RetIVAS);
    var TextInteres = parseFloat($('#TextInteres').val());
    var TextRecibido = TotalAbonos + TextInteres;
    $('#TextRecibido').val(TextRecibido.toFixed(2));
}

function formatearValor(elemento){
    console.log(elemento);
    let valor = elemento.value;
    elemento.value = parseInt(valor).toFixed(2);
}

function guardar_abonos() {

    const fechaInput = new Date($('#form_abonos #MBFecha').val());
    if (fechaInput < FechaCorte) {
        Swal.fire({
            title: 'Error',
            text: 'No se puede grabar abonos con fecha inferior a la emision de la factura',
            type: 'error',
        });
        return;
    
    }

    Swal.fire({
        title: 'Esta Seguro que desea grabar estos pagos.',
        text: '',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
    }).then((result) => {
        if (result.value == true) {
            Grabar_abonos();
        }
    })
}

function Grabar_abonos() {
    $('#myModal_espera').modal('show');
    var datos = $('#form_abonos').serialize();
    var fac = $('#DCSerie').val() + '-' + $('#form_abonos #DCFactura').val();
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosC.php?Grabar_abonos=true',
        data: datos,
        dataType: 'json',
        success: function (data) {
            $('#myModal_espera').modal('hide');
            Swal.fire('Abono Guardado', '', 'success').then(function () {
                if(data.TJ != ''){
                    var url_compro = '../../TEMP/' + data.TJ + '.pdf';
                    window.open(url_compro, '_blank');
                }
                cerrar_modal();
            })
        }
    });

}

function cerrar_modal() {
    parent.cerrarModal();
}

//------------------fin de abonos------------------

//--------------abono anticipado--------------

function inicioAbonoAnticipado(){
    // Espera a que el DOM esté listo
    //document.addEventListener("DOMContentLoaded", function () {

    var txtCajaMN = document.querySelector("#form_abonos_anti #TextCajaMN");
    DCCtaAnt();//Hay que enviar el subCtaGen como parametro.
    DCBancoAnt();
    var url = window.location.href;
    var urlParams = new URLSearchParams(url.split('?')[1]);
    var TipoFactura = urlParams.get('tipo');
    var grupo = $('#DCGrupo_No').val();
    var faFactura = $('#TextFacturaNo').val();


    if (TipoFactura == "OP") {
        document.querySelector("#form_abonos_anti #LabelPend").style.display = 'block';
        document.getElementById("Label10").style.display = 'block';
        document.getElementById("Frame1").style.display = 'block';
        document.getElementById("Frame2").style.display = 'none';
        DCTipoAnt(faFactura);
    } else {
        document.querySelector("#form_abonos_anti #LabelPend").style.display = 'none';
        document.getElementById("Label10").style.display = 'none';
        document.getElementById("Frame1").style.display = 'none';
        document.getElementById("Frame2").style.display = 'block';
        DCClientes(grupo);
    }
    var CheqRecibo = document.querySelector("#form_abonos_anti #CheqRecibo");
    var txtRecibo = document.querySelector("#form_abonos_anti #TxtRecibo");
    ReadSetDataNum("Recibo_No", true, false)
        .then(function (data) {
            // Aquí puedes trabajar con los datos
            if (CheqRecibo.checked) {
                txtRecibo.value = data.toString().padStart(7, '0');
                console.log(txtRecibo.textContent);
            } else {
                txtRecibo.value = "";
            }
        })
        .catch(function (error) {
            // Manejo de errores si la solicitud Ajax falla
            txtRecibo.value = "";
            console.error("Error en la solicitud Ajax", error);
        });




    // Función clic en el botón "Aceptar"
    window.Command1_Click = function () {
        Swal.fire({
            title: 'Formulario de Grabación',
            text: 'Está Seguro que desea grabar Abono.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si!'
        }).then((result) => {
            if (result.value == true) {
                Grabar_abonosAnt();
            }
        });
    };




    //});
}

// Función para grabar abonos
function Grabar_abonosAnt() {

    insertAsientoSC();
    let Total = $('#form_abonos_anti #TextCajaMN').val();
    insertarAsiento(0, Total, 0);
    insertarAsiento(0, 0, Total);

    var parametros = {
        'codigo_cliente': $('#DCClientes').val(),
        'sub_cta_gen': $('#DCCtaAnt').val().split(" ")[0]
    };
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?AdoIngCaja_Catalogo_CxCxP=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            return data;
        }
    });

    GrabarComprobante();
}

function llenarSelect(data, idSelect, dataName) {
    var select = document.querySelector("#form_abonos_anti #" + idSelect);
    if (data.length == 0) {
        select.innerHTML = '';
        var option = document.createElement("option");
        option.text = "No existen datos";
        option.value = "";
        select.appendChild(option);
    } else {
        select.innerHTML = '';
        for (var i = 0; i < data.length; i++) {
            var option = document.createElement("option");
            option.value = data[i][dataName];
            option.text = data[i][dataName];
            select.appendChild(option);
        }
    }
}

/*
Método conectado con el controlador para obtener todos los tipos de DCBanco existentes
en la base de datos. Si la data retornada contiene 'status' quiere decir que no hay datos
y se rellena el select con un 'No existen datos'.
Con el for llenamos el select de todos los datos que hayamos encontrado de la consulta SQL.
*/
function DCBancoAnt() {
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCBanco=true',
        // data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            llenarSelect(data, "DCBanco", "NomCuenta")
        }
    });
}



function DCCtaAnt() {
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCCtaAnt=true',
        //data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            console.log(data);
            llenarSelect(data, "DCCtaAnt", "NomCuenta");
        }
    });
}

function DCTipoAnt(faFactura) {
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCTipo=true',
        data: { 'fafactura': faFactura },
        dataType: 'json',
        success: function (data) {
            llenarSelect(data, "DCTipo", "TC");
        }
    });
}

function DCClientes(grupo) {
    //let strGrupo = grupo!=null ? `&grupo=${grupo}` : '';
    /*$.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCClientes=true' + strGrupo,
        //data: {parametros: parametros},
        dataType: 'json',
        success: function (data) {
            var select = document.getElementById("DCClientes");
            if (data.length == 0) {
                select.innerHTML = '';
                var option = document.createElement("option");
                option.text = "No existen datos";
                option.value = "";
                select.appendChild(option);
            } else {
                select.innerHTML = '';
                for (var i = 0; i < data.length; i++) {
                    var option = document.createElement("option");
                    option.value = data[i]['Codigo'];
                    option.text = data[i]['Cliente'];
                    select.appendChild(option);
                }
            }
        }
    });*/

    $('#DCClientes').select2({
        placeholder: 'Seleccione',
        dropdownParent: $('#form_abonos'),
        ajax: {
            url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCClientes=true',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    grupo:  grupo!=null ? grupo : ''
                }
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function ReadSetDataNum(SQLs, ParaEmpresa, Incrementar) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            type: "POST",
            url: '../controlador/contabilidad/FAbonosAnticipadoC.php?ReadSetDataNum=true',
            data: {
                'SQLs': SQLs,
                'ParaEmpresa': ParaEmpresa,
                'Incrementar': Incrementar
            },
            dataType: 'json',
            success: function (data) {
                resolve(data); // Resolvemos la promesa con los datos
            },
            error: function (error) {
                reject(error); // Rechazamos la promesa en caso de error
            }
        });
    });
}


function cerrar_modal_ant() {
    window.parent.closeModal();
}

function Listar_Facturas_Pendientes() {
    //console.log("TIPO FACTURA LOST FOCUS", TipoFactura);
    var url = window.location.href;
    var urlParams = new URLSearchParams(url.split('?')[1]);
    var TipoFactura = urlParams.get('tipo');
    var faFactura = $('#TextFacturaNo').val();
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?DCFactura=true',
        data: {
            'TipoFactura': TipoFactura,
            'FaFactura': faFactura
        },
        dataType: 'json',
        success: function (data) {
            llenarSelect(data, "DCFactura", "Factura");
        }
    });
}

function insertAsientoSC() {
    var parametros = {
        'Fecha_V': $('#form_abonos_anti #MBFecha').val(),
        'CodigoC': $('#DCClientes').val(),
        'NombreC': $('#DCClientes').find("option:selected").text(),
        'SubCtaGen': $('#DCCtaAnt').val(),
        'Total': $('#form_abonos_anti #TextCajaMN').val(),
        'Trans_No': 200
    };

    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?AdoIngCaja_Asiento_SC=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            return data;
        }
    });
}

function insertarAsiento(Parcial_MEs, Debes, Habers) {
    if (document.getElementById("Frame2").style.display == 'block') {
        var Cta_Aux = $('#form_abonos_anti #DCBanco').val().split(" ")[0];
        if (Cta_Aux.length <= 1)
            Cta_Aux = '0';//Cta_CajaG
    } else {
        Cta_Aux = '0';
    }
    var parametros = {
        'trans_no': 200,
        'CodCta': Cta_Aux,
        'Parcial_MEs': Parcial_MEs,
        'Debes': Debes,
        'Habers': Habers,
        'CodigoCli': $('#DCClientes').val()
    };
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?AdoIngCaja_Asiento=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            return data;
        }
    });
}

function GrabarComprobante() {
    var url = window.location.href;
    var urlParams = new URLSearchParams(url.split('?')[1]);
    var TipoFactura = urlParams.get('tipo');
    var faFactura = $('#TextFacturaNo').val();
    var grupo = $('#DCGrupo_No').val();
    var parametros = {
        'Fecha': $('#form_abonos_anti #MBFecha').val(),
        'Total': $('#form_abonos_anti #TextCajaMN').val(),
        'TipoFactura': TipoFactura,
        'NombreC': $('#DCClientes').find("option:selected").text(),
        'Factura': faFactura,
        'Grupo': grupo,
        'TxtConcepto': $('#TxtConcepto').val(),
        'CodigoCli': $('#DCClientes').val(),
        'Trans_No': 200
    };
    //console.log(parametros);
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?GrabarComprobante=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            EnviarEmail(data);
        }
    });

}

function EnviarEmail(parametros) {
    /*var parametros = {
        'CodigoCli': $('#DCClientes').val(),
    };*/

    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?EnviarEmail=true',
        data: { parametros: parametros },
        dataType: 'json',
        success: function (data) {
            if (data.res == 1) {
                Swal.fire({
                    title: data.Titulo,
                    text: data.Mensaje,
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si!'
                }).then((result) => {
                    if (result.value == true) {
                        EnviarEmailAccept(data);
                    }
                });
            }
        }
    });
}

function EnviarEmailAccept(data){
    $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/FAbonosAnticipadoC.php?EnviarEmailAccept=true',
        data: { parametros: data },
        dataType: 'json',
        success: function (data) {
            
        }
    });
}

//------------------fin de abono anticipado------------------