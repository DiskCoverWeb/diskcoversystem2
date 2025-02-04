let Trans_No = '97';
    let Cod_Inv_Producto = '';
    let OpcDH = 0;
    let Cantidad = 0;
    let SaldoAnterior = 0;
    let Contra_Cta1 = '.';
    let CodigoCliente = '.';
    let Cta_Inventario = '.';
    let Cod_Benef = '.';

    $(document).ready(function () {
        familias();
        contracuenta();
        Trans_Kardex();
        bodega();
        marca();
        DCPorcenIva('MBFechaI', 'DCPorcIVA');
        $('#DCPorcIVA').attr('disabled', true);
        iniciar_asientos();

        //DCBenef_LostFocus
        $('#DCBenef').on('select2:select', function (e) {
            let data = e.params.data;
            let parametros = {
                'CodigoCliente': data.id
            }
            $('#Label3').val(data.CICLIENTE);
            CodigoCliente = data.CICLIENTE;
            $('#TextConcepto').val(data.text);
            Cod_Benef = data.cod_benef;
        });

        //Text_Orden Got Focus
        $('#TextOrden').one('focus', function () {
            stock_actual_inventario();
        });

        $('#TextOrden').attr('disabled', true);

        //TextEntrada_GotFocus
        $('#TextEntrada').on('blur', function () {
            TextEntrada_GotFocus();
        });

        $('#TextTotal').one('focus', function () {
            TextTotal_GotFocus();
        });

        $('#myModal_comprobante').on('show.bs.modal', function (e) {
            $('#titulo-modal').text(`GRABACIÓN DEL COMPROBANTE: ${$('#CLTP').val()}`);
        });

    });

    //Seleccionar comprobante
    function Command3_Click() {
        var numero = parseInt($('#numComprobante').val());
        if(numero < 1){
            swal.fire({
                type: 'error',
                title: 'Error',
                text: 'Debe ingresar un número de comprobante válido'
            });
            return;
        }
        var parametros = {
            'Numero': numero,
            'Trans_No':Trans_No,
            'CLTP': $('#CLTP').val(),
            'MBFechaI': $('#MBFechaI').val(),

        };

        $.ajax({
            data: { 'parametros': parametros },
            url: '../controlador/inventario/registro_esC.php?seleccionar_comprobante=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.res == 1) {
                    //close modal
                    $('#myModal_comprobante').modal('hide');
                    swal.fire({
                        type: 'success',
                        title: '',
                        text: data.msg
                    });
                    $('tbody').empty();
                }else{
                    swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'No se pudo procesar el comprobante' + data.msg
                    });
                }
            }
        });

    }

    function validar_grabacion(){
        if($('tbody').children().length == 2){
            Swal.fire({
                type: 'error',
                title: 'Error',
                text: 'No se ha ingresado ningún producto'
            });
            return;
        }
        swal.fire({
            title: '¿Está seguro de grabar el comprobante?',
            text: "GRABACIÓN DEL COMPROBANTE",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Grabar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                grabar_comprobante();
            }else{
                return;
            }
        });
    }

    function grabar_comprobante(){
        var parametros = {
            'Trans_No': Trans_No,
            'CodigoCli': CodigoCliente,
            'MBFechaI': $('#MBFechaI').val(),
            'MBVence': $('#MBVence').val(),
            'TextOrden': $('#TextOrden').val(),
            'Factura_No': $('#TxtFactNo').val(),
            'CLTP': $('#CLTP').val(),
            'OpcI': $('#OpcI').prop('checked') ? 1 : 0,
            'OpcE': $('#OpcE').prop('checked') ? 1 : 0,
            'NombreCliente': $('#DCBenef').val(),
            'CheqContraCta': $('#CheqContraCta').prop('checked') ? 1 : 0,
            'TxtDifxDec': $('#TxtDifxDec').val(),
            'DCCtaObra': $('#DCCtaObra').val(),
            'TxtFactNo': $('#TxtFactNo').val(),
            'Cod_Benef': Cod_Benef,
            'TextConcepto': $('#TextConcepto').val(),
        };
        
        $.ajax({
            data: { 'parametros': parametros },
            url: '../controlador/inventario/registro_esC.php?grabar_comprobante=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if(data.res == 1){
                    var url = "../../TEMP/" + data.pdf1;
                    window.open(url, '_blank');
                    var url = "../../TEMP/" + data.pdf2;
                    window.open(url, '_blank');
                    swal.fire({
                        type: 'success',
                        title: 'Todo correcto',
                        text: data.msg
                    }).then((result) => {
                        if(result.value){
                            location.reload();
                        }
                    });
                }else{
                    swal.fire({
                        type: 'error',
                        title: 'No se pudo grabar el comprobante',
                        text: data.msg
                    }).then((result) => {
                        if(result.value){
                            location.reload();
                        }
                    });
                
                }
            }
        });


    }

    function iniciar_asientos() {
        $.ajax({
            url: '../controlador/inventario/registro_esC.php?iniciar_aseinto=true',
            type: 'post',
            dataType: 'json',
            data: { 'Trans_No': Trans_No },
            success: function (data) {
                grid_kardex();
            }
        });
    }

    function toupper(input) {
        input.value = input.value.toUpperCase();
    }

    function TextTotal_GotFocus() {
        let Cod_Bodega = $('#DCBodega').val();
        let Cod_Marca = $('#DCMarca').val();
        let Entrada = $('#TextEntrada').val();
        let ValorUnit = $('#TextVUnit').val();
        let DValorUnit = ValorUnit;
        let Total_Desc = $('#TextDesc').val() / 100;
        DValorUnit = DValorUnit - (DValorUnit * Total_Desc);
        Total_Desc = $('#TextDesc1').val() / 100;
        if (Total_Desc > 0) {
            DValorUnit = DValorUnit - (DValorUnit * Total_Desc);
        }
        let DValorTotal = DValorUnit * Entrada;
        ValorUnit = DValorUnit;
        let ValorTotal = DValorTotal.toFixed(2);
        $('#TextTotal').val(ValorTotal);
    }

    function TexTotal_LostFocus() {
        let FechaTexto = $('#MBFechaI').val();
        let Entrada = parseInt($('#TextEntrada').val());
        let Factura_No = $('#TxtFactNo').val() <= 0 ? 0 : $('#TxtFactNo').val();
        let SubTotal_IVA = 0;
        let ValorTotal = parseFloat($('#TextTotal').val());

        if (Entrada <= 0 || $('#TextVUnit').val() <= 0) {
            Swal.fire({
                type: 'error',
                title: 'Error',
                text: 'Falta de Ingresar la cantidad o el valor unitario'
            });
            return;
        }

        if ($('#OpcIVA').prop('checked')) {
            SubTotal_IVA = $('#TextTotal').val() * ($('#DCPorcenIva').val() / 100);
        }

        let Saldo = 0;
        let Contra_Cta = '.';

        if ($('#OpcI').prop('checked')) {
            Cantidad = Cantidad + Entrada;
            Saldo = SaldoAnterior + ValorTotal
            OpcDH = 1;
            Contra_Cta = $('#DCCtaObra').val();
        } else {
            Cantidad = Cantidad - Entrada;
            Saldo = SaldoAnterior - ValorTotal;
            OpcDH = 2;
            Contra_Cta = $('#CheqContraCta').prop('checked') ? $('#DCCtaObra').val() : Contra_Cta1;
        }


        var parametros = {
            'OpcDH': OpcDH,
            'CodigoInv': $('#LabelCodigo').val(),
            'TextDesc': $('#TextDesc').val(),
            'TextDesc1': $('#TextDesc1').val(),
            'Producto': $('#labelProductro').val(),
            'Entrada': $('#TextEntrada').val(),
            'ValorUnit': $('#TextVUnit').val(),
            'ValorTotal': $('#TextTotal').val(),
            'SubTotal_IVA': SubTotal_IVA,
            'Cta_Inventario': Cta_Inventario,
            'Contra_Cta': Contra_Cta,
            'Cantidad': Cantidad,
            'Saldo': Saldo,
            'UNIDAD': $('#LabelUnidad').val(),
            'Cod_Bodega': $('#DCBodega').val(),
            'Cod_Marca': $('#DCMarca').val(),
            'Trans_No': Trans_No,
            'SubCtaGen': '.',
            'SubCta': $('#SubCta').val(),
            'CodigoCliente': CodigoCliente,
            'TxtCodBar': $('#TxtCodBar').val(),
            'TextOrden': $('#TextOrden').val(),
            'TxtLoteNo': $('#TxtLoteNo').val(),
            'MBFechaFab': $('#MBFechaFab').val(),
            'MBFechaExp': $('#MBFechaExp').val(),
            'TxtRegSanitario': $('#TxtRegSanitario').val(),
            'TxtModelo': $('#TxtModelo').val(),
            'TxtProcedencia': $('#TxtProcedencia').val(),
            'TxtSerieNo': $('#TxtSerieNo').val(),
        };
        $.ajax({
            data: { 'parametros': parametros },
            url: '../controlador/inventario/registro_esC.php?IngresoAsientoK=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.res == 1) {
                    $('#TxtSubTotal').val(data.total.toFixed(2));
                    $('#TxtIVA').val(data.total_iva.toFixed(2));
                    const tmp = data.total + data.total_iva;
                    $('#Label1').val(tmp.toFixed(2));
                    grid_kardex();
                }
            }
        });
    }

    function stock_actual_inventario() {
        var parametros = {
            'Codigo_Inventario': Cod_Inv_Producto,
            'Fecha_Inv': $('#MBFechaI').val()
        }
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/inventario/registro_esC.php?stock_actual_inventario=true',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.res) {
                    const precio = data.valor_unit;
                    $("#TextVUnit").val(precio.toFixed(2));
                    Cantidad = data.cantidad;
                    SaldoAnterior = data.saldo_anterior;
                }
            }
        });
    }

    function TextVUnit_LostFocus() {
        const valorUnit = $('#TextVUnit').val();
        const entrada = $('#TextEntrada').val();
        const valorTotal = valorUnit * entrada;
        $('#TextTotal').val(valorTotal.toFixed(2));
    }

    function TextEntrada_GotFocus() {
        const OpcI = $('#OpcI').prop('checked');
        const OpcE = $('#OpcE').prop('checked');
        const precio = $('#TextVUnit').val();
        if (OpcI) {
            OpcDH = 1;
        } else {
            OpcDH = 2;
        }
        if (OpcE) {
            if (precio == 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error',
                    text: `Falta de Ingresar en este codigo ${$('#LabelCodigo').val()}: La entrada inicial`,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }
    }

    function grid_kardex() {
        var parametros = {
            'Trans_No': Trans_No
        };
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/inventario/registro_esC.php?grid_kardex=true',
            type: 'post',
            dataType: 'json',
            data: { 'parametros': parametros },
            success: function (data) {
                if (data.res == 1) {
                    console.log(data);
                    $('#tbl-container').empty();
                    $('#tbl-container').html(data.tabla);
                }
            }
        });
    }

    function habilitar_iva() {
        if ($('#OpcIVA').prop('checked')) {
            $('#DCPorcIVA').attr('disabled', false);
        } else {
            $('#DCPorcIVA').attr('disabled', true);
        }
    }

    function familias() {
        $('#ddl_familia').select2({
            placeholder: 'Seleccione una Familia',
            ajax: {
                url: '../controlador/inventario/registro_esC.php?familias=true',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    /// console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

    }
    function producto_famili(familia) {
        var fami = $('#ddl_familia').val();
        $('#ddl_producto').select2({
            placeholder: 'Seleccione producto',
            ajax: {
                url: '../controlador/inventario/registro_esC.php?producto=true&fami=' + fami,
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
    function contracuenta() {
        $('#DCCtaObra').select2({
            placeholder: 'Seleccione Contracuenta',
            ajax: {
                url: '../controlador/inventario/registro_esC.php?contracuenta=true',
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

    function leercuenta() {
        $('#DCBenef').val('').trigger('change');
        var parametros =
        {
            'cuenta': $('#DCCtaObra').val(),
        }
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/inventario/registro_esC.php?leercuenta=true',
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.length != 0) {
                    $('#Codigo').val(response.Codigo);
                    $('#Cuenta').val(response.Cuenta);
                    $('#SubCta').val(response.SubCta);
                    $('#Moneda_US').val(response.Moneda_US);
                    $('#TipoCta').val(response.TipoCta);
                    $('#TipoPago').val(response.TipoPago);
                    ListarProveedorUsuario();

                }

            }
        });

    }

    function Trans_Kardex() {
        $('#DCDiario').select2({
            placeholder: 'Seleccione Diario',
            ajax: {
                url: '../controlador/inventario/registro_esC.php?trans_kardex_opcional=true',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    //  console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

    }

    function bodega() {
        //var option = '<option value="">Seleccione bodega</option>';
        $.ajax({
            // data:  {parametros:parametros},
            url: '../controlador/inventario/registro_esC.php?bodega=true',
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.length != 0) {
                    $.each(response, function (i, item) {
                        //  console.log(item);
                        let option = $('<option>', {
                            value: item.CodBod,
                            text: item.Bodega
                        });
                        $('#DCBodega').append(option);
                    });

                }
            }
        });

    }


    function marca() {
        //var option = '<option value="">Seleccione marca</option>';
        $.ajax({
            // data:  {parametros:parametros},
            url: '../controlador/inventario/registro_esC.php?marca=true',
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.length != 0) {
                    $.each(response, function (i, item) {
                        // console.log(item);
                        let option = $('<option>', {
                            value: item.CodMar,
                            text: item.Marca
                        });
                        $('#DCMarca').append(option);
                    });

                }
            }
        });

    }



    function ListarProveedorUsuario() {
        var cta = $('#SubCta').val();
        var contra = $('#DCCtaObra').val();
        $('#DCBenef').select2({
            placeholder: 'Seleccione Cliente',
            ajax: {
                url: '../controlador/inventario/registro_esC.php?ListarProveedorUsuario=true&cta=' + cta + '&contra=' + contra,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    //  console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

    }

    function guardar() {
        var tipo = $('input:radio[name=rbl_]:checked').val();
    }


    function modal_retencion() {
        if ($('#CheqRF').prop('checked')) {
            $('#myModal').modal('show');
        }
    }

    function detalle_articulo() {
        var arti = $('#ddl_producto').val();
        var fami = $('#ddl_familia').val();
        var nom_ar = $('select[name="ddl_producto"] option:selected').text();
        var parametros =
        {
            'arti': arti,
            'nom': nom_ar,
            'fami': fami,
        }
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/inventario/registro_esC.php?detalle_articulos=true',
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.length != 0) {
                    $('#labelProductro').val(response.producto);
                    $('#LabelUnidad').val(response.unidad);
                    $('#LabelCodigo').val(response.codigo);
                    $('#TxtRegSanitario').val(response.registrosani);
                    Cod_Inv_Producto = response.codigo;
                    Contra_Cta1 = response.contra_cta1;
                    Cta_Inventario = response.cta_inventario;
                    $('#TextOrden').attr('disabled', false);
                    if (response.si_no == 0) {
                        $('#OpcX').prop('checked', true);
                    } else {
                        $('#OpcIVA').prop('checked', true);
                    }
                    // console.log(response);
                }
            }
        });

    }
    function tipo_ingreso() {
        if ($('#OpcI').prop('checked')) {
            // alert('ingreso');
            //make visible Label 11
            $('#TextIVA').attr('disabled', false);
            $('#CheqContraCta').attr('checked', true);
            $('#CheqContraCta').attr('disabled', false);
            $('#DCCtaObra').attr('disabled', false);
            $('#DCBenef').attr('disabled', false);
            $('#CheqRF').attr('disabled', false);

        } else {
            $('#TextIVA').attr('disabled', true);
            $('#CheqContraCta').attr('checked', false);
            $('#CheqContraCta').attr('disabled', true);
            $('#DCCtaObra').attr('disabled', true);
            $('#DCBenef').attr('disabled', true);
            $('#CheqRF').attr('disabled', true);
            let cltp = $('#CLTP').val();
            switch (cltp) {
                case "ND":
                case "NC":
                    $('#CheqRF').attr('disabled', false);
                    $('#TextIVA').attr('disabled', true);
                    break;
            }
            // alert('egreso');
        }

    }

    function CheqContraCuenta_Clic() {
        const contra = $('#CheqContraCta').prop('checked');
        if (contra) {
            //DCCtaObra visible true
            $('#DCCtaObra').attr('disabled', false);
            //DCBenef visible true
            $('#DCBenef').attr('disabled', false);
        } else {
            const opce = $('#OpcE').prop('checked');
            if (opce) {
                //DCCtaObra visible false
                $('#DCCtaObra').attr('disabled', true);
                //DCBenef visible false
                $('#DCBenef').attr('disabled', true);
            }
        }
    }

    function limpiar_retencaion() {
        $('#CheqRF').prop('checked', false);
        $('#myModal').modal('hide');
        cancelar();
    }
    function enviar_correo() {
        let htmlLoading = "<div class='bg-envcorreo' id='bg-envcorreo'><img id='load-gif' src='../../img/gif/correo_fin.gif' alt='Enviando correo'></div><div class='text-envcorreo' id='text-envcorreo'>Estimado usuario, su correo está siendo procesado para ser envíado...</div>"
        let htmlSuccess = "<div class='success-checkmark'><div class='check-icon'><span class='icon-line line-tip'></span><span class='icon-line line-long'></span><div class='icon-circle'></div><div class='icon-fix'></div></div></div>";
        let htmlError = "<div class='sa'><div class='sa-error'><div class='sa-error-x'><div class='sa-error-left'></div><div class='sa-error-right'></div></div><div class='sa-error-placeholder'></div><div class='sa-error-fix'></div></div></div>";

        document.getElementById("contenedor-envcorreo").innerHTML = htmlLoading;
        document.getElementById("contenedor-envcorreo").style.backgroundColor = "#fff";

        let advEnvCorreo = document.getElementById("contenedor-envcorreo");
        let bannerEC = document.getElementById("bg-envcorreo");
        let txtEC = document.getElementById("text-envcorreo");

        const datosCorreo = {
            'subject': "Prueba de Correo",
            'de': "electronicos@diskcoversystem.com",
            'mensaje': "Prueba de Envio de Correos",
            'adjunto': "",
            'credito_no': '',
            'tipoDeEnvio': '',
            'listaMail': null,
            'para': 'tedalemorvel@gmail.com;'
        };

        advEnvCorreo.classList.add("cont-ec-open");
        $.ajax({
            data: { 'data': datosCorreo },
            url: './inventario/sv_envio_correo.php',
            type: 'post',
        })
            .done(msg => {
                if (msg == "success") {
                    bannerEC.innerHTML = htmlSuccess;
                    advEnvCorreo.style.backgroundColor = "#009df9";
                    txtEC.innerText = "El correo ha sido enviado con exito";
                } else {
                    bannerEC.innerHTML = htmlError;
                    bannerEC.style.backgroundColor = "#F27474";
                    advEnvCorreo.style.backgroundColor = "#F27474";
                    txtEC.innerText = "Ocurrió un error al envíar el correo";
                }
                txtEC.style.color = "#fff";
                txtEC.style.textAlign = "center";
                txtEC.style.fontWeight = "700";

                setTimeout(function () {
                    advEnvCorreo.classList.remove("cont-ec-open");
                }, 3000);

            });

    }