eliminar_linea('', '');
$(document).ready(function() {

   
    DCPorcenIva('MBFecha', 'DCPorcenIVA');
    if(servicio!=0)
    {
        $('#campo_servicio').css('display','block');
        $('#campo_totalServicio').css('display','flex');
        $('#textoServicio').text(`Total Servicio ${servicio}%`);
    }

    if (operadora != '.') {
        buscar_cliente(operadora);
        $('#btn_nuevo_cli').css('display', 'none');
        $('#DCCliente').prop('disabled', true);
    }
    ddl_DCTipoPago();
    catalogoLineas();
    autocomplete_cliente();
    autocomplete_producto();
    serie();
    // tipo_documento();
    DCBodega();
    DGAsientoF();


    DCBanco();

    $(document).keyup(function(e) {
        // if (e.key === "Escape") { // escape key maps to keycode `27`
        //   ingresar_total();
        // }
        console.log(e.key);
    });


    function buscar_cliente(ruc) {

        $.ajax({
            type: "POST",
            url: '../controlador/facturacion/punto_ventaC.php?DCCliente_exacto=true&q=' + ruc,
            // data: {parametros: parametros},
            dataType: 'json',
            success: function(data) {
                datos = data[0].data[0];
                // console.log(datos);
                $('#Lblemail').val(datos.Email);
                $('#LblRUC').val(datos.CI_RUC);
                $('#codigoCliente').val(datos.Codigo);
                $('#LblT').val(datos.T);
                // $('#DCCliente').append('<option value="' +codi+ ' ">' + datos[indice].text + '</option>');
                $('#DCCliente').append($('<option>', {
                    value: datos.Codigo,
                    text: datos.Cliente,
                    selected: true
                }));
                // console.log(data);
            }
        });


        // $.ajax({
        //      url: '../controlador/facturacion/punto_ventaC.php?DCCliente=true&q='+ruc,
        //      dataType: 'json',
        //      delay: 250,
        //      processResults: function (data) {
        //        return {
        //          results: data
        //        };
        //      },
        //      cache: true
        //    })
    }


    // $('#MBFecha').on('change', function(){
    //     validar_cta()
    // })

    // $('#DCCliente').on('select2:select', function (e) {
    //     var data = e.params.data.data;
    //     console.log(e);
    //     $('#Lblemail').val(data[0].Email);
    //     $('#LblRUC').val(data[0].CI_RUC);
    //     $('#codigoCliente').val(data[0].Codigo);
    //     $('#LblT').val(data[0].T);

    //     console.log(data);
    //   });


});


function usar_cliente(nombre, ruc, codigo, email, t = 'N') {
    $('#Lblemail').val(email);
    $('#LblRUC').val(ruc);
    $('#codigoCliente').val(codigo);
    $('#LblT').val(t);
    $('#LblTD').text(t);
    // $('#DCCliente').append('<option value="' +codi+ ' ">' + datos[indice].text + '</option>');
    $('#DCCliente').append($('<option>', {
        value: codigo,
        text: nombre,
        selected: true
    }));
    $('#myModal').modal('hide');
}

function select() {
    var seleccionado = $('#DCCliente').select2("data");
    var data = seleccionado[0].data;
    console.log(data);
    $('#Lblemail').val(data[0].Email);
    $('#LblRUC').val(data[0].CI_RUC);
    $('#codigoCliente').val(data[0].Codigo);
    $('#LblT').val(data[0].T);
    $('#LblTD').text(data[0].TD);
}

function validar_cta() {
    var parametros = {
        'TC': TC,
        'Serie': $('#LblSerie').text(),
        'Fecha': $('#MBFecha').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/punto_ventaC.php?validar_cta=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            if (data != 1) {
                Swal.fire({
                    type: 'info',
                    title: data,
                    text: '',
                    allowOutsideClick: false,
                });                
                $('#btn_g').prop('disabled', true);
            }
        }
    });

}

function tipo_documento() {
    var tc = $('#DCLinea').val();
    if (tc == '') {
        return false;
    }
    tc = tc.split(' ');

    // var TipoFactura = '<?php //echo $TC; ?>';

    var TipoFactura = tc[0];

    var Porc_IVA = parseFloat(Porc_IVA) * 100;
    if (TipoFactura == "PV") {
        // FacturasPV.Caption = "INGRESAR TICKET"
        $('#Label1').text(" TICKET No.");
        //$('#Label3').text(" I.V.A. " + Porc_IVA.toFixed(2) + "%")
    } else if (TipoFactura == "CP") {
        // FacturasPV.Caption = "INGRESAR CHEQUES PROTESTADOS"
        $('#Label1').text(" COMPROBANTE No.");
        //$('#Label3').text(" I.V.A. 0.00%")
    } else if (TipoFactura == "NV") {
        // FacturasPV.Caption = "INGRESAR NOTA DE VENTA"
        $('#Label1').text(" NOTA DE VENTA No.");
        //$('#Label3').text(" I.V.A. 0.00%")
    } else if (TipoFactura == "DO") {
        // FacturasPV.Caption = "INGRESAR NOTA DE DONACION"
        $('#Label1').text(" NOTA DE DONACION No.");
        //$('#Label3').text(" I.V.A. 0.00%")
    } else if (TipoFactura == "LC") {
        // FacturasPV.Caption = "INGRESAR LIQUIDACION DE COMPRAS"
        $('#Label1').text(" LIQUIDACION DE COMPRAS No.");
        //$('#Label3').text(" I.V.A. 0.00%")
        OpcDiv.value = True
        // 'If Len(Opc_Grupo_Div) > 1 Then Grupo_Inv = Opc_Grupo_Div
    } else {
        // FacturasPV.Caption = "INGRESAR FACTURA"
        $('#Label1').text(" FACTURA No.");
        //$('#Label3').text(" I.V.A. " + Porc_IVA.toFixed(2) + "%")
        $('#CodDoc').val("01");
    }
}


function autocomplete_cliente() {
    $('#DCCliente').select2({
        placeholder: 'Seleccione un cliente',
        ajax: {
            url: '../controlador/facturacion/punto_ventaC.php?DCCliente=true',
            width:'resolve',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}


function autocomplete_producto() {
    var parametros = '&TC=' +TC;
    $('#DCArticulo').select2({
        placeholder: 'Seleccione un Producto',
        ajax: {
            url: '../controlador/facturacion/punto_ventaC.php?DCArticulo=true' + parametros,
            dataType: 'json',
            width:'resolve',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function DCBanco() {
    // alert('das');
    $('#DCBanco').select2({
        placeholder: 'Seleccione un cliente',
        ajax: {
            url: '../controlador/facturacion/punto_ventaC.php?DCBanco=true',
            dataType: 'json',
            width:'resolve',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}


function serie() {
    var parametros = {
        'TC': TC,
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/punto_ventaC.php?LblSerie=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            if (data.serie != '.') {
                $('#LblSerie').text(data.serie);
                $('#TextFacturaNo').val(data.NumCom);
            } else {
                numeroFactura();
            }
            validar_cta();
        }
    });
}

function DCBodega() {
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/punto_ventaC.php?DCBodega=true',
        //data: {parametros: parametros},
        dataType: 'json',
        success: function(data) {
            llenarComboList(data, 'DCBodega');
        }
    });

}

function DGAsientoF() {
    // $.ajax({
    //     type: "POST",
    //     url: '../controlador/facturacion/punto_ventaC.php?DGAsientoF=true',
    //     //data: {parametros: parametros},
    //     dataType: 'json',
    //     beforeSend: function() {
    //         $('#tbl_DGAsientoF').html('<img src="../../img/gif/loader4.1.gif" width="40%"> ');
    //     },
    //     success: function(data) {
    //         $('#tbl_DGAsientoF').html(data);
    //     }
    // });


      tbl_lineas_all.ajax.reload(function() {
          // Cerrar el modal después de que se hayan recargado los datos
        setTimeout(function() {
        $('#myModal_espera').modal('hide');
        }, 500); 
      }, false);

}

function Articulo_Seleccionado() {
    var parametros = {
        'codigo': $('#DCArticulo').val(),
        'fecha': $('#MBFecha').val(),
        'CodBod': $('#DCBodega').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/punto_ventaC.php?ArtSelec=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);
            if (data.respueta == true) {
                if (data.datos.Stock < 0) {
                    Swal.fire(data.datos.Producto + ' ES UN PRODUCTO SIN EXISTENCIA', '', 'info').then(
                        function() {
                            $('#DCArticulo').empty();
                            // $('#LabelStock').val(0);
                        });

                } else {

                    $('#LabelStock').val(data.datos.Stock);
                    $('#TextVUnit').val(data.datos.PVP);
                    $('#LabelStock').focus();

                    $('#TxtDetalle').val(data.datos.Producto);
                    // $('#').val(data.datos.);


                    $('#cambiar_nombre').on('shown.bs.modal', function() {
                        $('#TxtDetalle').focus();
                    })

                    $('#cambiar_nombre').modal('show', function() {
                        $('#TxtDetalle').focus();
                    })

                }

            }
        }
    });

}

function calcular() {
    var VUnit = $('#TextVUnit').val();
    if (VUnit == '') {
        Swal.fire('INGRESE UN PRECIO VALIDO', 'PUNTO VENTA', 'info').then(function() {
            $('#TextVUnit').select()
        })
    }
    var serv = servicio;
    if(serv!=0)
    {
        var cant = $('#TextCant').val();
        var pvp = $('#TextVUnit').val();
        $('#TextServicios').val(cant * pvp * (serv/100));
    }

    var Cant = $('#TextCant').val();
    var OpcMult = $('#OpcMult').prop('checked');
    var ban = $('#TextCheque').val()
    var servi = $('#TextServicios').val();
    if (is_numeric(VUnit) && is_numeric(Cant)) {
        if (VUnit == 0) {
            VUnit = "0.01";
        }
        if (OpcMult) {
            Real1 = parseFloat(Cant) * parseFloat(VUnit) + parseFloat(servi);
        } else {
            Real1 = parseFloat(Cant) / parseFloat(VUnit);
        }
        // console.log(Real1);
        $('#LabelVTotal').val(Real1.toFixed(4));
    } else {
        $('#LabelVTotal').val(0.0000);
    }
}

function valida_Stock() {
    var Cantidad = $('#TextCant').val();
    if (Cantidad == '' || Cantidad == 0) {
        Swal.fire('INGRESE UNA CANTIDAD VALIDA', 'PUNTO DE VENTA', 'info').then(function() {
            $('#TextCant').select();
        });
    }
    var DifStock = parseFloat($('#LabelStock').val()) - parseFloat(Cantidad);
    var producto = $('#DCArticulo option:selected').text();
    if (DifStock.toFixed(2) < 0) {
        Swal.fire(producto + ' NO PUEDE QUEDAR EXISTENCIA NEGATIVA, SOLICITE ALIMENTACION DE STOCK', 'PUNTO DE VENTA',
            'info').then(function() {
            $('#TextCant').select();
        });
        // $('#DCArticulo').focus();
    }
}

function ingresar() {
    var cli = $('#DCCliente').val();
    if (cli == '') {
        Swal.fire('Seleccione un cliente', '', 'info');
        return false;
    }
    var pro = $('#DCArticulo').val();
    if (pro == '') {
        Swal.fire('Seleccione un producto valido', '', 'info');
        return false;
    }
    var tc = $('#DCLinea').val();
    tc = tc.split(' ');
    var parametros = {
        'opc': $('input[name="radio_conve"]:checked').val(),
        'TextVUnit': $('#TextVUnit').val(),        
        'TextVDescto': $('#TextVDescto').val(),
        'TextServicios': $('#TextServicios').val(),
        'TextCant': $('#TextCant').val(),
        'TC': tc[0],
        'TxtDocumentos': $('#TxtDocumentos').val(),
        'Codigo': $('#DCArticulo').val(),
        'Producto': $('#DCArticulo :selected').text(),
        'fecha': $('#MBFecha').val(),
        'CodBod': $('#DCBodega').val(),
        'VTotal': $('#LabelVTotal').val(),
        'TxtRifaD': $('#TxtRifaD').val(),
        'TxtRifaH': $('#TxtRifaH').val(),
        'Serie': $('#LblSerie').text(),
        'CodigoCliente': $('#codigoCliente').val(),
        'PorcIva':$('#DCPorcenIVA').val(),
        'electronico': 1,
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/punto_ventaC.php?IngresarAsientoF=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            if (data == 2) {
                Swal.fire('Ya no puede ingresar mas productos', '', 'info');
            } else if (data == 1) {
                DGAsientoF();
                Calculos_Totales_Factura();
                $('#DCArticulo').empty();
            } else {
                Swal.fire('Intente mas tarde', '', 'info');
            }
        }
    });

}

function Eliminar(A_no, cod) {
    Swal.fire({
        title: 'Esta seguro?',
        text: "Esta usted seguro de eliminar este registro!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
    }).then((result) => {
        if (result.value == true) {
            eliminar_linea(cod, A_no);
        }
    })
}

function eliminar_linea(cod, A_no) {
    var parametros = {
        'cod': cod,
        'A_no': A_no,
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/punto_ventaC.php?eliminar_linea=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            if (data == 1) {
                DGAsientoF();
                Calculos_Totales_Factura();
            }
        }
    });

}

function Calculos_Totales_Factura() {
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/punto_ventaC.php?Calculos_Totales_Factura=true',
        // data: {parametros: parametros},
        dataType: 'json',
        success: function(data) {
            console.log(data)
            $('#LabelSubTotal').val(parseFloat(data.Sin_IVA).toFixed(2));
            $('#LabelConIVA').val(parseFloat(data.Con_IVA).toFixed(2));
            $('#LabelDescto').val(parseFloat(data.Descuento).toFixed(2));
            $('#LabelServicio').val(parseFloat(data.Servicio).toFixed(2));
            $('#LabelIVA').val(parseFloat(data.Total_IVA).toFixed(2));
            $('#LabelTotal').val(parseFloat(data.Total_MN).toFixed(2));
        }
    });
}

function ingresar_total() {
    Swal.fire({
        allowOutsideClick: false,
        title: 'INGRESE EL TOTAL DEL RECIBO',
        input: 'text',
        inputValue: 0,
        inputAttributes: {
            autocapitalize: 'off',
        },
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        showLoaderOnConfirm: true,
    }).then((result) => {
        if (result.value >= 0) {
            var total = result.value;
            editar_factura(total);
        } else {

        }
    })
}

function editar_factura(total) {
    var parametros = {
        'total': total,
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/punto_ventaC.php?editar_factura=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            DGAsientoF();
            Calculos_Totales_Factura();
        }
    });
}

function generar() {
    var bod = $('#DCBodega').val();
    if (bod == '') {

        Swal.fire('Ingrese o Seleccione una bodega', '', 'info').then(function() {
            $('#TextFacturaNo').focus()
        });
        return false;
    }
    var Cli = $('#DCCliente').val();
    if (Cli == '') {
        Swal.fire('Seleccione un cliente', '', 'info');
        return false;
    }
    var total = parseFloat($('#LabelTotal').val()).toFixed(4);
    var efectivo = parseFloat($('#TxtEfectivo').val()).toFixed(4);
    var banco = parseFloat($('#TextCheque').val()).toFixed(4);
    Swal.fire({
        allowOutsideClick: false,
        title: 'Esta Seguro que desea grabar: \n Factura  No. ' + $('#TextFacturaNo').val(),
        text: '',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
    }).then((result) => {
        if (result.value == true) {
            if (banco > total) {
                Swal.fire('Si el pago es por banco este no debe superar el total de la factura', 'PUNTO VENTA',
                    'info').then(function() {
                    $('#TextCheque').select();
                });
                return false;
            }
            generar_factura()
        }
    })
}



function generar_factura() {

    var tc = $('#DCLinea').val();
    tc = tc.split(' ');

    $('#myModal_espera').modal('show');

    var parametros = {
        'MBFecha': $('#MBFecha').val(),
        'TxtEfectivo': $('#TxtEfectivo').val(),
        'TextFacturaNo': $('#TextFacturaNo').val(),
        'TxtNota': $('#TxtNota').val(),
        'TxtObservacion': $('#TxtObservacion').val(),
        'TipoFactura': tc[0],
        'TxtGavetas': $('#TxtGavetas').val(),
        'CodigoCliente': $('#codigoCliente').val(),
        'email': $('#Lblemail').val(),
        'CI': $('#LblRUC').val(),
        'NombreCliente': $('#DCCliente option:selected').text(),
        'TC': tc[0],
        'Serie': $('#LblSerie').text(),
        'DCBancoN': $('#DCBanco option:selected').text(),
        'DCBancoC': $('#DCBanco').val(),
        'T': $('#LblT').val(),
        'TextBanco': $('#TextBanco').val(),
        'TextCheqNo': $('#TextCheqNo').val(),
        'TextBanco': $('#TextBanco').val(),
        'TextCheqNo': $('#TextCheqNo').val(),
        'CodDoc': $('#CodDoc').val(),
        'valorBan': $('#TextCheque').val(),
        'electronico': 1,
        'tipo_pago': $('#DCTipoPago').val(),
        'PorcIva': $('#DCPorcenIVA').val(),

        //---------------datos de gia de remicion--------------///
        'MBoxFechaGRE': $('#MBoxFechaGRE').val(),
        'DCSerieGR': $('#DCSerieGR').val(),
        'LblGuiaR': $('#LblGuiaR').val(),
        'LblAutGuiaRem': $('#LblAutGuiaRem').val(),
        'MBoxFechaGRI': $('#MBoxFechaGRI').val(),
        'DCCiudadI': $('#DCCiudadI').val(),
        'MBoxFechaGRF': $('#MBoxFechaGRF').val(),
        'DCCiudadF': $('#DCCiudadF').val(),
        'DCRazonSocial': $('#DCRazonSocial').val(),
        'Razon': $('#DCRazonSocial option:selected').text(),
        'DCEmpresaEntrega': $('#DCEmpresaEntrega').val(),
        'Entrega': $('#DCEmpresaEntrega option:selected').text(),
        'TxtPlaca': $('#TxtPlaca').val(),
        'TxtPedido': $('#TxtPedido').val(),
        'TxtZona': $('#TxtZona').val(),
        'TxtLugarEntrega': $('#TxtLugarEntrega').val(),
    }
    console.log(parametros);
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/punto_ventaC.php?generar_factura_elec=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            $('#myModal_espera').modal('hide');
            console.log(data);
            var msj = "";
            if(data.Factura[0]==1 && data.Guia_remision.length==0)
            {
                if(data.impresion_rodillo=='0')
                {
                    msj = "Factura Procesada y Autorizada";
                    nombre_pdf = [data.Factura['pdf']+'.pdf'];
                    clave_Acceso = [data.Factura[1]+'.xml'];

                    Swal.fire(msj,"","success").then(function(){
                                var url = '../../TEMP/' + data.Factura['pdf'] + '.pdf';
                                window.open(url, '_blank'); 
                                enviar_email_comprobantes(nombre_pdf,clave_Acceso);
                                location.reload();
                    })
                }else
                {
                    Re_imprimir($('#TextFacturaNo').val(),$('#LblSerie').text(),$('#LblRUC').val(),tc[0])
                }
            }else if(data.Factura[0]==1 && data.Guia_remision[0]==1)
            {
                  Swal.fire({
                    // type: 'success',
                    // title: 'Factura Procesada y Autorizada',
                    confirmButtonText: 'Cerrar',
                    allowOutsideClick: false,
                    html:'<div class="row">'+
                            '<div class="col-sm-8">'+
                                '<h4><i class="fa fa-check-circle text-success"  style="font-size: xx-large;"></i> Factura autorizada</h4>'+
                            '</div>'+    
                            '<div class="col-sm-4">'+                     
                                '<br>'+                 
                                '<a href="../../TEMP/' + data.Factura['pdf'] + '.pdf" target="_blank" class="btn btn-sm btn-primary">Ver pdf</a> '+   
                            '</div>'+
                        '</div>'+
                        '<hr>'+ 
                        '<div class="row">'+
                            '<div class="col-sm-8">'+
                                '<h4><i class="fa fa-check-circle text-success"  style="font-size: xx-large;"></i> Guia de remision autorizada</h4>'+
                            '</div>'+    
                            '<div class="col-sm-4">'+                       
                                '<br>'+
                                '<a href="../../TEMP/' + data.Guia_remision['pdf_guia'] + '.pdf" target="_blank" class="btn btn-sm btn-primary">Ver pdf</a> '+   
                            '</div>'+
                        '</div>'+
                        '<hr>',
                }).then(function() {
                    nombre_pdf = [data.Factura['pdf']+'.pdf',data.Guia_remision['pdf_guia']+'.pdf'];
                    clave_Acceso = [data.Factura[1]+'.xml',data.Guia_remision[1]+'.xml'];
                    enviar_email_comprobantes(nombre_pdf,clave_Acceso);
                })

            }else if(data.Factura[0]==1 && data.Guia_remision[0]==0)
            {
                    //factura con guia remision devuelta
                 Swal.fire({
                    // type: 'success',
                    // title: 'Factura Procesada y Autorizada',
                    confirmButtonText: 'Cerrar',
                    allowOutsideClick: false,
                    html:'<div class="row">'+
                            '<div class="col-sm-8">'+
                                '<h4><i class="fa fa-check-circle text-success"  style="font-size: xx-large;"></i> Factura autorizada</h4>'+
                            '</div>'+    
                            '<div class="col-sm-4">'+                     
                                '<br>'+                 
                                '<a href="../../TEMP/' + data.Factura['pdf'] + '.pdf" target="_blank" class="btn btn-sm btn-primary">Ver pdf</a> '+   
                            '</div>'+
                        '</div>'+
                        '<hr>'+ 
                        '<div class="row">'+
                            '<div class="col-sm-8">'+
                                '<h4><i class="fa fa-times text-danger"  style="font-size: xx-large;"></i> Guia de remision No Autorizada</h4>'+
                            '</div>'+    
                            '<div class="col-sm-4">'+                       
                            '<br>'+
                            '<a href="#" type="button" class="btn btn-sm btn-danger" onclick="tipo_error_sri(\''+data.Guia_remision[1]+'\')"> Ver Error</a> '+   
                            '</div>'+
                        '</div>'+
                        '<hr>',
                }).then(function() {
                    nombre_pdf = [data.Factura['pdf']+'.pdf'];
                    clave_Acceso = [data.Factura[1]+'.xml'];
                    enviar_email_comprobantes(nombre_pdf,clave_Acceso);   
                    location.reload();      
                })
            }else if(data.Factura[0] == -1)
            {

                //factura si gia de remision 
                Swal.fire({
                    icon: 'error',
                    title: 'Factura no autorizada',
                    confirmButtonText: 'Ok!',
                    allowOutsideClick: false,
                }).then(function() {
                    tipo_error_sri(data.Factura[1])
                })
            }else
             {
                Swal.fire('Error inesperado consulte con su proveedor','','error').then(function() {
                    location.reload();            
                })
             }



            // if(data[0] == 1 && data['respuesta_guia']==0)
            // {               
            //     //factura si gia de remision 
            //     Swal.fire({
            //         icon: 'success',
            //         title: 'Factura Procesada y Autorizada',
            //         confirmButtonText: 'Ok!',
            //         allowOutsideClick: false,
            //     }).then(function() {

            //         nombre_pdf = [data['pdf']+'.pdf'];
            //         clave_Acceso = [data[1]+'.xml'];
            //         enviar_email_comprobantes(nombre_pdf,clave_Acceso);

            //         if (data.rodillo == '0') 
            //         {
            //             var url = '../../TEMP/' + data['pdf'] + '.pdf';
            //             window.open(url, '_blank'); 
            //             location.reload();
            //         }else
            //         {                       
            //             Re_imprimir($('#TextFacturaNo').val(),$('#LblSerie').text(),$('#LblRUC').val(),tc[0])
            //         }
            //     })
                
            // }else if(data[0]==1 && data['respuesta_guia'][0]==0)
            // {
            //     //factura con guia remision devuelta
            //      Swal.fire({
            //         // type: 'success',
            //         // title: 'Factura Procesada y Autorizada',
            //         confirmButtonText: 'Cerrar',
            //         allowOutsideClick: false,
            //         html:'<div class="row">'+
            //                 '<div class="col-sm-8">'+
            //                     '<h4><i class="fa fa-check-circle text-success"  style="font-size: xx-large;"></i> Factura autorizada</h4>'+
            //                 '</div>'+    
            //                 '<div class="col-sm-4">'+                     
            //                     '<br>'+                 
            //                     '<a href="../../TEMP/' + data['pdf'] + '.pdf" target="_blank" class="btn btn-sm btn-primary">Ver pdf</a> '+   
            //                 '</div>'+
            //             '</div>'+
            //             '<hr>'+ 
            //             '<div class="row">'+
            //                 '<div class="col-sm-8">'+
            //                     '<h4><i class="fa fa-times text-danger"  style="font-size: xx-large;"></i> Guia de remision No Autorizada</h4>'+
            //                 '</div>'+    
            //                 '<div class="col-sm-4">'+                       
            //                 '<br>'+
            //                 '<a href="#" type="button" class="btn btn-sm btn-danger" onclick="tipo_error_sri(\''+data['clave_guia']+'\')"> Ver Error</a> '+   
            //                 '</div>'+
            //             '</div>'+
            //             '<hr>',
            //     }).then(function() {
            //         nombre_pdf = [data['pdf']+'.pdf'];
            //         clave_Acceso = [data[1]+'.xml'];
            //         enviar_email_comprobantes(nombre_pdf,clave_Acceso);         
            //     })

            // }else if(data[0]==1 && data['respuesta_guia'][0]==1)
            // {
            //      Swal.fire({
            //         // type: 'success',
            //         // title: 'Factura Procesada y Autorizada',
            //         confirmButtonText: 'Cerrar',
            //         allowOutsideClick: false,
            //         html:'<div class="row">'+
            //                 '<div class="col-sm-8">'+
            //                     '<h4><i class="fa fa-check-circle text-success"  style="font-size: xx-large;"></i> Factura autorizada</h4>'+
            //                 '</div>'+    
            //                 '<div class="col-sm-4">'+                     
            //                     '<br>'+                 
            //                     '<a href="../../TEMP/' + data['pdf'] + '.pdf" target="_blank" class="btn btn-sm btn-primary">Ver pdf</a> '+   
            //                 '</div>'+
            //             '</div>'+
            //             '<hr>'+ 
            //             '<div class="row">'+
            //                 '<div class="col-sm-8">'+
            //                     '<h4><i class="fa fa-check-circle text-success"  style="font-size: xx-large;"></i> Guia de remision autorizada</h4>'+
            //                 '</div>'+    
            //                 '<div class="col-sm-4">'+                       
            //                     '<br>'+
            //                     '<a href="../../TEMP/' + data['pdf_guia'] + '.pdf" target="_blank" class="btn btn-sm btn-primary">Ver pdf</a> '+   
            //                 '</div>'+
            //             '</div>'+
            //             '<hr>',
            //     }).then(function() {
            //         nombre_pdf = [data['pdf']+'.pdf',data['pdf_guia']+'.pdf'];
            //         clave_Acceso = [data[1]+'.xml',data['respuesta_guia'][1]+'.xml'];
            //         enviar_email_comprobantes(nombre_pdf,clave_Acceso);
       
            //     })
            // }else if(data[0] == -1)
            // {

            //     //factura si gia de remision 
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Factura no autorizada',
            //         confirmButtonText: 'Ok!',
            //         allowOutsideClick: false,
            //     }).then(function() {
            //         tipo_error_sri(data[1])
            //     })
            // }else
            //  {
            //     Swal.fire('Error inesperado consulte con su proveedor','','error').then(function() {
            //         location.reload();            
            //     })
            //  }



          

        },
        error: function (request, status, error) {   
          Swal.fire('Error inesperado ','consulte con su proveedor de servicio','error');         
            $('#myModal_espera').modal('hide');
        }
    });

}

function enviar_email_comprobantes(nombre_pdf,clave_Acceso)
{
    $('#myModal_envio_Email').modal('show');
    var parametros = {
        'clave':clave_Acceso,
        'pdf':nombre_pdf,
        'correo':$('#Lblemail').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/punto_ventaC.php?enviar_email_comprobantes=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);
            $('#myModal_envio_Email').modal('hide');        
            if(data==1)
            {
                Swal.fire('Email enviado','','success').then(function(){
                    location.reload();
                });
            }   
        }
    });
}

function Re_imprimir(fac,serie,ci,tc)
{
    var totalFA = $('#LabelTotal').val();
 var url = '../controlador/facturacion/divisasC.php?ticketPDF_fac=true&fac='+fac+'&serie='+serie+'&CI='+ci+'&TC='+tc+'&efectivo=0.0000&saldo=0.00&pdf=no'+"&totalFA="+totalFA;
     var html='<iframe style="width:100%; height:50vw;" src="'+url+'&pdf=no" frameborder="0" allowfullscreen id="re_ticket"></iframe>';
    $('#re_frame').html(html);
     Swal.fire({
        icon: 'success',
        title: 'Factura Procesada y Autorizada',
        confirmButtonText: 'Ok!',
        allowOutsideClick: false,
    }).then(function(){
        const iframeWindow = document.getElementById('re_ticket').contentWindow;
        iframeWindow.onafterprint = function() {
            window.location.reload(); // Recarga la página principal
        };
        iframeWindow.print();
    })                     
}


function tipo_error_sri(clave) {
    var parametros = {
        'clave': clave,
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/punto_ventaC.php?error_sri=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {

            console.log(data);
            $('#myModal_sri_error').modal('show');
            $('#sri_estado').text(data.estado[0]);
            $('#sri_codigo').text(data.codigo[0]);
            $('#sri_fecha').text(data.fecha[0]);
            $('#sri_mensaje').text(data.mensaje[0]);
            $('#sri_adicional').text(data.adicional[0]);
            // $('#doc_xml').attr('href','')
        }
    });
}

function calcular_pago() {

    var cotizacion = parseInt($('#TextCotiza').val());
    var efectivo = parseFloat($('#TxtEfectivo').val());
    var Total_Factura = parseFloat($('#LabelTotalME').val());
    var Total_Factura2 = parseFloat($('#LabelTotal').val());
    var Total_banco = parseFloat($('#TextCheque').val());
    if (cotizacion > 0) {
        if (parseFloat(efectivo) > 0) {
            var ca = efectivo - Total_Factura + Total_banco;
            $('#LblCambio').val(ca.toFixed(2));
        }
    } else {
        if (efectivo > 0 || Total_banco > 0) {
            var ca = efectivo - Total_Factura2 + Total_banco;
            $('#LblCambio').val(ca.toFixed(2))
        }
    }

}

function numeroFactura() {
    DCLinea = $("#DCLinea").val();
    // console.log(DCLinea);
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturar_pensionC.php?numFactura=true',
        data: {
            'DCLinea': DCLinea,
        },
        dataType: 'json',
        success: function(data) {
            datos = data;
            document.querySelector('#LblSerie').innerText = datos.serie;
            $("#TextFacturaNo").val(datos.codigo);
        }
    });
}

function catalogoLineas() {
    // $('#myModal_espera').modal('show');
    var cursos = $("#DCLinea");
    fechaEmision = $('#MBFecha').val();
    fechaVencimiento = $('#MBFecha').val();
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturar_pensionC.php?catalogo=true',
        data: {
            'fechaVencimiento': fechaVencimiento,
            'fechaEmision': fechaEmision,
            'tipo':'FA',
        },
        dataType: 'json',
        success: function(data) {
            if (data.length > 0) {
                datos = data;
                // Limpiamos el select
                cursos.find('option').remove();
                for (var indice in datos) {
                    cursos.append('<option value="' + datos[indice].id + " " + datos[indice].text + ' ">' +
                        datos[indice].text + '</option>');
                }
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Usted no tiene un punto de emsion asignado  o esta mal configurado, contacte con la administracion del sistema',
                    text: '',
                    allowOutsideClick: false,
                }).then(() => {
                    console.log('ingresa');
                    location.href = '../vista/modulos.php';
                });

            }

            tipo_documento();
            numeroFactura();
        }
    });
    $('#myModal_espera').modal('hide');
}

function validar_bodega() {
    var ddl = $('DCBodega').val();
    if (ddl == '') {
        Swal.fire('Ingrese o Seleccione una bodega', '', 'info').then(function() {
            $('#TextFacturaNo').focus()
        });
    }
}


function cerrar_modal_cambio_nombre() {
    var nuevo = $('#TxtDetalle').val();
    var dcart = $('#DCArticulo').val();
    $('#DCArticulo').append($('<option>', {
        value: dcart,
        text: nuevo,
        selected: true
    }));
    $('#LabelStock').focus();

    $('#cambiar_nombre').modal('hide');

}

function mostara_observacion(opcion) {
    if(opcion===1){
        if($('#rbl_obs').is(':checked')){
            $('#modal_obs').modal('show');
        } else {
            txt='';
        }
        var op = $('#rbl_obs').prop('checked');
    } else if (opcion===2){
        if($('#rbl_obs1').is(':checked')){
            $('#modal_obs1').modal('show');
        } else {
            txt1='';
        }
    }
    add_observaciones();
}

function ddl_DCTipoPago() {
    var opcion = '<option value="">Seleccione tipo de pago</option>';
    $.ajax({
        //data:  {parametros:parametros},
        url: '../controlador/inventario/registro_esC.php?DCTipoPago=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            //console.log(response);
            $.each(response, function(i, item) {
                opcion += '<option value="' + item.Codigo + '">' + item.CTipoPago + '</option>';
            })
            $('#DCTipoPago').html(opcion);
            $('#DCTipoPago').val("01");
            // console.log(response);
        }
    });
}

//inicia guia de remision

function btn_guiaRemision() {
    $('#myModal_guia').modal('show');
    DCCiudadI();
    DCCiudadF();
    AdoPersonas();
    DCEmpresaEntrega();

}

function DCCiudadI() {
    $('#DCCiudadI').select2({
        dropdownParent:$('#myModal_guia'),
        placeholder: 'Seleccione la ciudad',
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCCiudadI=true',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
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
        dropdownParent:$('#myModal_guia'),
        placeholder: 'Seleccione la ciudad',
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCCiudadF=true',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
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
        dropdownParent:$('#myModal_guia'),
        placeholder: 'Seleccione un Grupo',
        ajax: {
            url: '../controlador/facturacion/facturarC.php?AdoPersonas=true',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
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
        dropdownParent:$('#myModal_guia'),
        placeholder: 'Seleccione la Empresa',
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCEmpresaEntrega=true',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
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
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            if (data.length > 0) {
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
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);
           
                //llenarComboList(data, 'DCSerieGR');
                $('#LblGuiaR').val(data['Guia']);
                $('#LblAutGuiaRem').val(data['Auto']);
           
        }
        // success: function(response) {
        //     console.log(response);
        //     $('#LblGuiaR').val(response[0]['Guia']);
        // }
    })

}

function Command8_Click() {
    if ($('#DCCiudadI').val() == '' || $('#DCCiudadF').val() == '' || $('#DCRazonSocial').val() == '' || $(
            '#DCEmpresaEntrega').val() == '') {
        swal.fire('Llene todo los campos', '', 'info');
        return false;
    }
    $('#ClaveAcceso_GR').val('.');
    $('#Autorizacion_GR').val($('#LblAutGuiaRem').val());
    var DCserie = $('#DCSerieGR').val();
    if (DCserie == '') {
        DCserie = '0_0';
    }
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

}

function cambiar_iva(valor)
{
    $('#Label3').text('I.V.A. '+parseFloat(valor).toFixed(2)+'%');
    $('#LabelTotTarifa').text('Total Tarifa '+parseInt(valor)+'%');
}

//fin guia de remision


function enviaremail()   //funcion para enviarlo por javascript
  { 


          const xhr = new XMLHttpRequest();
          const url =  'https://erp.diskcoversystem.com/php/comprobantes/SRI/autorizar_sri_visual.php?AutorizarXMLOnline=true';
            // const url =  '../../php/comprobantes/SRI/autorizar_sri_visual.php?AutorizarXMLOnline=true';


          xhr.open('POST', url, true);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

          xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
              console.log('Respuesta:', xhr.responseText);
            }
          };

           const params = `XML=2008202507179280254700120010020000068401234567818.xml&RUTA=BROODBOETIEK_S_A_2024_03_28_2.p12&PASS=Jurgen2024`;

          xhr.send(params);
  }