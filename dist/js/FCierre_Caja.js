$(document).ready(function () {
    Form_Activate();
    //ajustarAlturaTabla();

    $('#CheqCajero').click(function () {
      $('#DCBenef').toggle($(this).is(':checked'));
    });

    $('#MBFechaI').blur(function () {
      let fechaI = $(this).val();
      FechaValidaJs(fechaI);
      $('#MBFechaF').val(fechaI);
    });

    $('#MBFechaF').blur(function () {
      let fechaF = $(this).val();
      FechaValidaJs(fechaF);
    });

    $('#MBFechaF').keydown(function (event) {
      let keyCode = event.which;
      let shift = event.shiftKey;
      if (shift && keyCode === 77) { // 77 es el código para la letra "M"
        let fechaI = $('#MBFechaI').val();
        let fechaF = UltimoDiaMes(fechaI);
        $(this).val(fechaF);
      }
    });
  });

  function Form_Activate() {
    $.ajax({
      type: "POST",
      url: '../controlador/contabilidad/FCierre_CajaC.php?Form_Activate=true',
      dataType: 'json',
      success: function (data) {
        // construirTabla(data.AdoAsiento, "DGAsiento")  
        // construirTabla(data.AdoAsiento1, "DGAsiento1") 

        var DCBanco = $("#DCBanco");
        for (var indice in data.AdoCtaBanco) {
          DCBanco.append('<option value="' + data.AdoCtaBanco[indice].NomCuenta + ' ">' + data.AdoCtaBanco[indice].NomCuenta + '</option>');
        }

        var DCBenef = $("#DCBenef"); ////TODO LS carga a carga con ajax
        for (var indice in data.AdoClientes) {
          DCBenef.append('<option value="' + data.AdoClientes[indice].Codigo + ' ">' + data.AdoClientes[indice].Cajero + '</option>');
        }
      }
    });
  }

  function Diario_Caja() {
    $('#myModal_espera_progress').modal('show');
    $("#Bar_espera_progress").css('width', '0%')
    $("#Bar_espera_progress .txt_progress").text('Procesando el Cierre de Caja...')

    $.ajax({
      type: "POST",
      url: '../controlador/contabilidad/FCierre_CajaC.php?Diario_CajaInicio=true',
      dataType: 'json',
      data: { 'MBFechaI': $("#MBFechaI").val(), 'MBFechaF': $("#MBFechaF").val() },
      success: function (datos) {
        // construirTabla(datos.AdoAsiento, "DGAsiento")  
        // construirTabla(datos.AdoAsiento1, "DGAsiento1") 
        $("#Bar_espera_progress").css('width', '20%')
        $("#Bar_espera_progress .txt_progress").text('Actualizando Productos')
        $.ajax({
          type: "POST",
          url: '../controlador/contabilidad/FCierre_CajaC.php?Productos_Cierre_Caja=true',
          dataType: 'json',
          data: { 'MBFechaI': $("#MBFechaI").val(), 'MBFechaF': $("#MBFechaF").val() },
          success: function (datos2) {
            $("#Bar_espera_progress").css('width', '40%')
            $("#Bar_espera_progress .txt_progress").text('Mayorizando Inventarios')
            $.ajax({
              type: "POST",
              url: '../controlador/contabilidad/FCierre_CajaC.php?Mayorizar_Inventario=true',
              dataType: 'json',
              // data: {'MBFechaI' : $("#MBFechaI").val() ,'MBFechaF' : $("#MBFechaF").val() },
              success: function (datos3) {
                $("#Bar_espera_progress").css('width', '60%')
                $("#Bar_espera_progress .txt_progress").text('Actualizando Abonos')
                $.ajax({
                  type: "POST",
                  url: '../controlador/contabilidad/FCierre_CajaC.php?Actualizar_Abonos_Facturas=true',
                  dataType: 'json',
                  data: { 'MBFechaI': $("#MBFechaI").val(), 'MBFechaF': $("#MBFechaF").val() },
                  success: function (datos4) {
                    $("#Bar_espera_progress").css('width', '70%')
                    $("#Bar_espera_progress .txt_progress").text('Actualizando Clientes')
                    $.ajax({
                      type: "POST",
                      url: '../controlador/contabilidad/FCierre_CajaC.php?Actualizar_Datos_Representantes=true',
                      dataType: 'json',
                      data: { 'MBFechaI': $("#MBFechaI").val(), 'MBFechaF': $("#MBFechaF").val() },
                      success: function (datos5) {
                        $("#Bar_espera_progress").css('width', '70%')
                        $("#Bar_espera_progress .txt_progress").text('Procesando Asientos Contables')
                        $.ajax({
                          type: "POST",
                          url: '../controlador/contabilidad/FCierre_CajaC.php?Grabar_Asientos_Facturacion=true',
                          dataType: 'json',
                          data: {
                            'MBFechaI': $("#MBFechaI").val(),
                            'MBFechaF': $("#MBFechaF").val(),
                            'CheqCajero': ($('#CheqCajero').prop('checked')) ? 1 : 0,
                            'CheqOrdDep': ($('#CheqOrdDep').prop('checked')) ? 1 : 0,
                            'DCBenef': $("#DCBenef").val()
                          },
                          beforeSend: function () {
                            $("#tblDGVentas").html('<tr class="text-center"><td colspan="20"><img src="../../img/gif/loader4.1.gif" width="20%">');
                          },
                          success: function (datos6) {
                            if (datos6.error) {
                              $('#myModal_espera_progress').modal('hide');
                              Swal.fire({
                                type: 'warning',
                                title: '',
                                text: datos6.mensaje
                              });
                            }
                            else {
                              CargarDataResponseGrabar_Asientos_Facturacion(datos6);
                              $("#Bar_espera_progress").css('width', '90%')
                              $("#Bar_espera_progress .txt_progress").text('Verificando Errores')
                              $.ajax({
                                type: "POST",
                                url: '../controlador/contabilidad/FCierre_CajaC.php?VerificandoErrores=true',
                                dataType: 'json',
                                data: { 'MBFechaI': $("#MBFechaI").val(), 'MBFechaF': $("#MBFechaF").val() },
                                success: function (datos7) {
                                  $("#Bar_espera_progress").css('width', '95%')
                                  $("#Bar_espera_progress .txt_progress").text('Fechas de Cierres')
                                  $.ajax({
                                    type: "POST",
                                    url: '../controlador/contabilidad/FCierre_CajaC.php?FechasdeCierre=true',
                                    dataType: 'json',
                                    data: { 'MBFechaI': $("#MBFechaI").val(), 'MBFechaF': $("#MBFechaF").val() },
                                    success: function (datos8) {
                                      construirTabla(datos8.AdoCierres, "TblDGCierres")
                                      construirTabla(datos8.AdoAnticipos, "TblDGAnticipos")

                                      $("#Bar_espera_progress").css('width', '99%')
                                      $("#Bar_espera_progress .txt_progress").text('Finalizando Proceso')
                                      if (esDiferenteDeCero(redondear(datos6.LabelDebe1 - datos6.LabelHaber1, 2)) || esDiferenteDeCero(redondear(datos6.LabelDebe - datos6.LabelHaber, 2))) {

                                        Swal.fire({
                                          type: 'warning',
                                          text: '',
                                          title: "Las Transacciones no cuadran, verifique las facturas emitidas o los abonos del día."
                                        });
                                      }
                                      $('#myModal_espera_progress').modal('hide');
                                      ShowFInfoErrorShowView()
                                    },
                                    error: function (e) {
                                      $('#myModal_espera_progress').modal('hide');
                                      alert("error inesperado en Fechas de Cierres")
                                    }
                                  });
                                },
                                error: function (e) {
                                  $('#myModal_espera_progress').modal('hide');
                                  alert("error inesperado al Verificar Errores")
                                }
                              });
                            }
                          },
                          error: function (e) {
                            $('#myModal_espera_progress').modal('hide');
                            alert("error inesperado al Procesar Asientos Contables")
                          }
                        });
                      },
                      error: function (e) {
                        $('#myModal_espera_progress').modal('hide');
                        alert("error inesperado al Actualizar Clientes")
                      }
                    });
                  },
                  error: function (e) {
                    $('#myModal_espera_progress').modal('hide');
                    alert("error inesperado al Actualizar Abonos")
                  }
                });
              },
              error: function (e) {
                $('#myModal_espera_progress').modal('hide');
                alert("error inesperado al Mayorizar Inventario")
              }
            });
          },
          error: function (e) {
            $('#myModal_espera_progress').modal('hide');
            alert("error inesperado al actualizar los productos")
          }
        });
      },
      error: function (e) {
        $('#myModal_espera_progress').modal('hide');
        alert("error inesperado iniciar el proceso")
      }
    });
  }

  // construye la tabla con los datos procesados
  function construirTabla(datos, tablaId) {
    $('#' + tablaId).html(datos);
  }

  function CargarDataResponseGrabar_Asientos_Facturacion(datos) {
    construirTabla(datos.DGCxC, "TblDGCxC")

    var AdoCxC = $("#AdoCxC");
    for (var indice in datos.AdoCxC) { //TODO LS que valor se asigna al select??
      AdoCxC.append('<option value="' + datos.AdoCxC[indice].Orden_No + ' ">' + datos.AdoCxC[indice].Orden_No + '</option>');
    }

    construirTabla(datos.AdoAsiento, "TblDGAsiento")
    construirTabla(datos.AdoAsiento1, "TblDGAsiento1")
    construirTabla(datos.DGFactAnul, "TblDGFactAnul")
    construirTabla(datos.DGInv, "TblDGInv")
    construirTabla(datos.DGProductos, "TblDGProductos")
    construirTabla(datos.DGVentas, "TblDGVentas")

    var AdoVentas = $("#AdoVentas");
    for (var indice in datos.AdoVentas) { //TODO LS que valor se asigna al select??
      AdoVentas.append('<option value="' + datos.AdoVentas[indice].Factura + ' ">' + datos.AdoVentas[indice].Factura + ' - ' + datos.AdoVentas[indice].Total_MN + '</option>');
    }

    construirTabla(datos.DGSRI, "TblDGSRI")

    var AdoSRI = $("#AdoSRI");
    for (var indice in datos.AdoSRI) { //TODO LS que valor se asigna al select??
      AdoSRI.append('<option value="' + datos.AdoSRI[indice].RUC_CI + ' ">' + datos.AdoSRI[indice].RUC_CI + ' - ' + datos.AdoSRI[indice].Razon_Social + '</option>');
    }

    $("#LabelAbonos").val(formatearNumero(datos.LabelAbonos))
    $("#LabelCheque").val(formatearNumero(datos.LabelCheque))
    $("#LabelDebe").val(formatearNumero(datos.LabelDebe))
    $("#LabelDebe1").val(formatearNumero(datos.LabelDebe1))
    $("#LabelHaber").val(formatearNumero(datos.LabelHaber))
    $("#LabelHaber1").val(formatearNumero(datos.LabelHaber1))
    $("#LblConIVA").val(formatearNumero(datos.LblConIVA))
    $("#LblDescuento").val(formatearNumero(datos.LblDescuento))
    $("#LblDiferencia").val(formatearNumero(datos.LblDiferencia))
    $("#LblDiferencia1").val(formatearNumero(datos.LblDiferencia1))
    $("#LblIVA").val(formatearNumero(datos.LblIVA))
    $("#LblServicio").val(formatearNumero(datos.LblServicio))
    $("#LblSinIVA").val(formatearNumero(datos.LblSinIVA))
    $("#LblTotalFacturado").val(formatearNumero(datos.LblTotalFacturado))
  }

  function redondear(valor, decimales) {
    if (decimales <= 0) decimales = 0;
    if (decimales >= 6) decimales = 6;
    valor_redondeo = parseFloat(valor).toFixed(6);
    valor_redondeo = parseFloat(valor_redondeo).toFixed(decimales);
    return valor_redondeo;
  }

  function ajustarAlturaTabla(tabla) {
    var posicionEncabezado = document.querySelector("#DGVentas thead").getBoundingClientRect().top;
    var alturaDisponible = window.innerHeight - posicionEncabezado;
    $(".DGVentas-container").height(alturaDisponible - 55);
    $(".DGCierres-container").height(alturaDisponible);
    $(".DGFactAnul-container").height(alturaDisponible);
    $(".DGBanco-container").height(alturaDisponible - 40);

  }

  function Grabar_Cierre_DiarioV() {

    Swal.fire({
      title: 'Esta seguro?',
      text: "¿Está seguro de grabar el Cierre de Caja?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si!'
    }).then((result) => {
      if (result.value == true) {
        $('#myModal_espera').modal('show');
        $.ajax({
          type: "POST",
          url: '../controlador/contabilidad/FCierre_CajaC.php?Grabar_Cierre_Diario=true',
          dataType: 'json',
          data: {
            'MBFechaI': $("#MBFechaI").val(),
            'MBFechaF': $("#MBFechaF").val(),
            'CheqCajero': ($('#CheqCajero').prop('checked')) ? 1 : 0,
            'CheqOrdDep': ($('#CheqOrdDep').prop('checked')) ? 1 : 0,
            'DCBenef': $("#DCBenef").val()
          },
          success: function (datos) {
            if (datos.error) {
              Swal.fire({
                type: 'warning',
                title: datos.mensaje,
                text: ''
              });
            }
            else {
              Swal.fire({
                type: 'success',
                title: 'Cierre del día ' + ((datos.dataCierre.MBFechaI) ? datos.dataCierre.MBFechaI : "") + ((datos.dataCierre.Factura) ? "(" + datos.dataCierre.Factura + ")" : ""),
              });

              if (datos.dataCierre.MBFechaI) {
                $("#MBFechaI").val(datos.dataCierre.MBFechaI)
                $("#MBFechaF").val(datos.dataCierre.MBFechaI)
              }
            }

            $('#myModal_espera').modal('hide');
          },
          error: function (e) {
            $('#myModal_espera').modal('hide');
            alert("error inesperado en Grabar_Cierre_Diario")
          }
        });
      }
    })
  }

  function FechaValidaJs(fecha) {
    $.ajax({
      type: "POST",
      url: '../controlador/contabilidad/FCierre_CajaC.php?FechaValida=true',
      dataType: 'json',
      data: { 'fecha': fecha },
      success: function (datos) {
        if (datos.ErrorFecha) {
          Swal.fire({
            type: 'warning',
            title: datos.MsgBox,
            text: fecha
          });
        }
      }
    });
  }

  function IESS_Cierre_DiarioV() {
    $('#myModal_espera').modal('show');
    $.ajax({
      type: "POST",
      url: '../controlador/contabilidad/FCierre_CajaC.php?IESS_Cierre_Diario=true',
      dataType: 'json',
      data: {
        'MBFechaI': $("#MBFechaI").val(),
        'MBFechaF': $("#MBFechaF").val()
      },
      success: function (datos) {
        if (datos.rps) {
          Swal.fire({
            type: 'success',
            title: datos.mensaje,
            html: "<a class='btn btn-xs btn-warning' onclick=\"descargarArchivo('" + datos.nombre_archivo + "', '../.." + datos.ruta + "')\"><i class='fa fa-download' aria-hidden='true'></i> Descargar Archivo</a>"
          });
        } else {
          Swal.fire({
            type: 'warning',
            title: datos.mensaje
          });
        }
        $('#myModal_espera').modal('hide');
      },
      error: function (e) {
        $('#myModal_espera').modal('hide');
        alert("error inesperado en IESS_Cierre_DiarioV")
      }
    });
  }

  function SolicitarReactivar() {
    if ($("#MBFechaI").val() != "" && $("#MBFechaF").val() != "") {
      /*$('#clave_contador').modal('show');
      $('#titulo_clave').text('Contador General');
      $('#TipoSuper').val('Contador');*/
      IngClave('Contador');
    } else {
      Swal.fire('Seleccione las fechas', '', 'info');
    }
  }

  // funcion de respuesta para la clave
  function resp_clave_ingreso(response) {
    if (response['respuesta'] == 1) {
      ReactivarV()
    } else {
      Swal.fire({
        type: 'warning',
        title: response['msj']
      });
    }
  }

  function ReactivarV() {
    $('#myModal_espera').modal('show');
    $.ajax({
      type: "POST",
      url: '../controlador/contabilidad/FCierre_CajaC.php?Reactivar=true',
      dataType: 'json',
      data: {
        'MBFechaI': $("#MBFechaI").val(),
        'MBFechaF': $("#MBFechaF").val()
      },
      success: function (datos) {
        Swal.fire({
          type: (datos.rps) ? 'success' : 'warning',
          title: datos.mensaje
        });

        if (datos.rps) {
          if (datos.CierreDelDia && datos.CierreDelDia.MBFechaI) {
            $("#MBFechaI").val(datos.CierreDelDia.MBFechaI)
            $("#MBFechaF").val(datos.CierreDelDia.MBFechaF)
          }

          //construirTabla(datos.AdoAsiento, "TblDGAsiento")
          //construirTabla(datos.AdoAsiento1, "TblDGAsiento1")

        }
        $("#LabelDebe").val('0')
        $("#LabelHaber").val('0')
        $('#myModal_espera').modal('hide');
      },
      error: function (e) {
        $('#myModal_espera').modal('hide');
        alert("error inesperado en IESS_Cierre_DiarioV")
      }
    });
  }

  function GenerarExcelResultadoCierreCaja() {
    var activeTabHref = $('.nav-tabs .nav-item.active a').attr('href');
    var activeTabTitle = $('.nav-tabs .nav-item.active a').text();
    var activeTabName = activeTabHref.substring(1);
    var url, tabName, secondTabUrl;
    var Titulo;

    switch (activeTabName) {
      case "AdoCxCT":
        secondTabUrl = "AdoAnticipos&Titulo=Anticipos";
        Titulo = "Anticipos";
        break;

      case "AdoInv":
        secondTabUrl = "AdoProductos&Titulo=Productos";
        Titulo = "Productos";
        break;

      case "AdoAsientoT":
        secondTabUrl = "AdoAsiento1T&Titulo=Caja de CxC";
        Titulo = "Caja de CxC";
        break;
    }

    url = `../controlador/contabilidad/FCierre_CajaC.php?ExcelResultadoCierreCaja=true&Tabs=${activeTabName}&Titulo=${activeTabTitle}`;
    console.log(url);
    window.open(url, '_blank');

    if (secondTabUrl) {
      url = `../controlador/contabilidad/FCierre_CajaC.php?ExcelResultadoCierreCaja=true&Tabs=${secondTabUrl}`;
      console.log(url);

      $.ajax({
        url: url,
        method: 'GET',
        xhrFields: {
          responseType: 'blob' // Especificamos que la respuesta será un Blob
        },
        success: function (response) {
          console.log(response)
          // Crear un enlace para descargar el archivo
          const downloadLink = document.createElement('a');
          downloadLink.href = URL.createObjectURL(response);
          downloadLink.download = 'Cierre de Caja ' + Titulo + ' .xlsx'; // Nombre del archivo a descargar
          downloadLink.click();
        },
        error: function (xhr, status, error) {
          console.error('Error al descargar el archivo:', error);
        }
      });
    }
  }