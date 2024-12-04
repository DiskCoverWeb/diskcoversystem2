
$(document).ready(function () {

   if (window.self !== window.top) {
    $('#BtnGuardarClienteFCliente').css('display','none');
    // console
  } 

  provincias();
  tipo_proveedor_Cliente()

  $("#CMedidor").on('change', function () {
    if ($("#CMedidor").val() != "." && $("#CMedidor").val() != "") {
      $("#DeleteMedidor").removeClass("no-visible")
    } else {
      $("#DeleteMedidor").addClass("no-visible")
    }
  })
});



function buscar_numero_ci() {
  $('#listaProductosRelacionados').empty();
  $('#LblSRI').html('');
  var ci_ruc = $('#ruc').val();
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
      limpiar();
      if (response.length > 0) {
        // console.log(response[0]);
        $('#txt_id').val(response[0].value); // display the selected text
        $('#ruc').val(response[0].label); // display the selected text
        $('#nombrec').val(response[0].nombre); // save selected id to input
        $('#direccion').val(response[0].direccion); // save selected id to input
        $('#telefono').val(response[0].telefono); // save selected id to input
        $('#codigoc').val(response[0].codigo); // save selected id to input
        $('#email').val(response[0].email); // save selected id to input
        $('#nv').val(response[0].vivienda); // save selected id to input
        $('#grupo').val(response[0].grupo); // save selected id to input
        $('#naciona').val(response[0].nacionalidad); // save selected id to input
        $('#prov').val(response[0].provincia); // save selected id to input
        console.log(response[0])
        if (response[0].provincia == '' || response[0].provincia == '.') {
          $('#prov').append('<option value=".">Seleccione</option>'); // save selected id to input                  
        }
        $('#ciu').val(response[0].ciudad); // save selected id to input
        $('#TD').val(response[0].TD); // save selected id to input
        $('#txt_ejec').val(response[0].Cod_Ejec); // save selected id to input

        // Verificar si ya existe una opción con el mismo valor
        if ($('#txt_actividadC option[value="' + response[0].Actividad + '"]').length === 0) {
          // Si no existe, agregar la nueva opción al final del select
          var nuevaOpcion = '<option value="' + response[0].Actividad + '">' + response[0].Actividad + '</option>';
          $('#txt_actividadC').append(nuevaOpcion);
        }
        $('#txt_actividadC').val(response[0].Actividad); // save selected id to input

        if (response[0].FA == 1) { $('#rbl_facturar').prop('checked', true); } else { $('#rbl_facturar').prop('checked', false); }
        MostrarOcultarBtnAddMedidor()
        ListarCuenta(response[0].nombre);
      } else {
        $('#ruc').val(ci_ruc);
        codigo();
      }

      $("#myModal_espera").modal('hide');

    }
  });
}

function provincias() {
  var option = "<option value=''>Seleccione provincia</option>";
  $.ajax({
    url: '../controlador/educativo/detalle_estudianteC.php?provincias=true',
    type: 'post',
    dataType: 'json',
    // data:{usu:usu,pass:pass},
    beforeSend: function () {
      $("#select_ciudad").html("<option value=''>Seleccione provincia</option>");
    },
    success: function (response) {
      response.forEach(function (data, index) {
        option += "<option value='" + data.Codigo + "'>" + data.Descripcion_Rubro + "</option>";
      });
      $('#prov').html(option);
      console.log(response);
    }
  });

}

function limpiar() {
  $('#txt_id').val(''); // display the selected text
  $('#ruc').val(''); // display the selected text
  $('#nombrec').val(''); // save selected id to input
  $('#direccion').val(''); // save selected id to input
  $('#telefono').val(''); // save selected id to input
  $('#codigoc').val(''); // save selected id to input
  $('#email').val(''); // save selected id to input
  $('#nv').val(''); // save selected id to input
  $('#grupo').val(''); // save selected id to input
  $('#naciona').val(''); // save selected id to input
  $('#prov').val(''); // save selected id to input
  $('#ciu').val(''); // save selected id to input
  $('#CMedidor').empty();
  MostrarOcultarBtnAddMedidor()
}

function codigo() {
  $("#myModal_espera").modal('show');
  var ci = $('#ruc').val();
  if (ci != '') {
    $.ajax({
      url: '../controlador/modalesC.php?codigo=true',
      type: 'post',
      dataType: 'json',
      data: { ci: ci },
      beforeSend: function () {
        // $("#myModal_espera").modal('show');
      },
      success: function (response) {
        console.log(response);
        $('#codigoc').val(response.Codigo_RUC_CI);
        $('#TD').val(response.Tipo_Beneficiario);
        $("#myModal_espera").modal('hide');
        MostrarOcultarBtnAddMedidor()

      }
    });
  } else {
    limpiar();
  }

}


function buscar_cliente_nom() {
  var ci = $('#nombrec').val();
  var parametros =
  {
    'nombre': ci,
  }
  $.ajax({
    data: { parametros: parametros },
    url: '../controlador/modalesC.php?buscar_cliente_nom=true',
    type: 'post',
    dataType: 'json',
    success: function (response) {
      // console.log(response);
      if (response) {

      }
    }
  });
}

function guardar_cliente() {
  $('#myModal_espera').modal('show');
  if (validar() == true) {
    swal.fire('Llene todos los campos', '', 'info')
    return false;
  }
  var rbl = $('#rbl_facturar').prop('checked');
  var datos = $('#form_cliente').serialize();
  $.ajax({
    data: datos + '&rbl=' + rbl + '&cxp=' + prove,
    url: '../controlador/modalesC.php?guardar_cliente=true',
    type: 'post',
    dataType: 'json',
    success: function (response) {
      $('#myModal_espera').modal('hide');
      // console.log(response);
      var url = location.href;
      if (response == 1) {
        if ($('#txt_id').val() != '') {
          swal.fire('Registro guardado', '', 'success')
          .then(result => {
            if(result.value){
              $('#BtnGuardarClienteFCliente').attr('disabled', true);
              window.location.reload();
            }
          });
        } else {
          swal.fire('Registro guardado', '', 'success')
          .then(result => {
            if(result.value){
              $('#BtnGuardarClienteFCliente').attr('disabled', true);
              window.location.reload();
            }
          });
        }

      } else if (response == 2) {
        swal.fire('Este CI / RUC ya esta registrado', '', 'info');
      } else if (response == 3) {
        swal.fire('El Nombre ya esta registrado', '', 'info');
      }
    },
    error:function(err){
      $('#myModal_espera').modal('hide');
      swal.fire('Ocurrio un error al procesar la solicitud. Error: ' + err, '', 'error');
    }
  });
}

function validar() {

  $('#e_ruc').css('display', 'none');
  $('#e_telefono').css('display', 'none');
  $('#e_nombrec').css('display', 'none');
  $('#e_direccion').css('display', 'none');

  var vali = false;
  if ($('#ruc').val() == '') {
    $('#e_ruc').css('display', 'initial');
    vali = true;
  }
  if ($('#telefono').val() == '') {
    $('#e_telefono').css('display', 'initial');
    vali = true;
  }
  if ($('#nombrec').val() == '') {
    $('#e_nombrec').css('display', 'initial');
    vali = true;
  }
  if ($('#direccion').val() == '') {
    $('#e_direccion').css('display', 'initial');
    vali = true;
  }
  if ($('#email').val() == '') {
    $('#e_email').css('display', 'initial');
    vali = true;
  }

  return vali;

}

function AddMedidor() {
  let CodigoC = $("#codigoc").val();

  if (CodigoC != "" && CodigoC != ".") {
    Swal.fire({
      title: 'Ingresar Nuevo Medidor:',
      showCancelButton: true,
      cancelButtonText: 'Cerrar',
      confirmButtonText: 'Guardar',
      html:
        '<label for="CMedidorNew">Numero de Medidor</label>' +
        '<input type="tel" id="CMedidorNew" class="swal2-input" required>' +
        '<span id="error1" style="color: red;"></span><br>' +
        '<label for="LecturaInicial">Lectura Anterior</label>' +
        '<input type="tel" id="LecturaInicial" class="swal2-input inputNumero">' +
        '<span id="error2" style="color: red;"></span><br>',
      focusConfirm: false,
      preConfirm: () => {
        const CMedidorNew = document.getElementById('CMedidorNew').value;
        const LecturaInicial = document.getElementById('LecturaInicial').value;

        if ($.isNumeric(CMedidorNew)) {
          if ($.isNumeric(LecturaInicial) || LecturaInicial == "") {
            return [CMedidorNew, LecturaInicial];
          } else {
            Swal.getPopup().querySelector('#error2').textContent = 'Debe ingresar un valor numérico';
            return false
          }
        } else {
          Swal.getPopup().querySelector('#error1').textContent = 'Debe ingresar un valor numérico';
          return false
        }
      }
    }).then((result) => {
      if (result.value) {
        const [CMedidorNew, LecturaInicial] = result.value;
        if ($.isNumeric(CMedidorNew)) {
          $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '../controlador/modalesC.php?AddMedidor=true',
            data: { 'Cuenta_No': CMedidorNew, 'TxtCodigo': CodigoC, 'LecturaInicial': LecturaInicial },
            beforeSend: function () {
              $('#myModal_espera').modal('show');
            },
            success: function (response) {
              $('#myModal_espera').modal('hide');
              if (response.rps) {
                Swal.fire('¡Bien!', response.mensaje, 'success')
                ListarMedidores(CodigoC)
              } else {
                Swal.fire('¡Oops!', response.mensaje, 'warning')
              }
            },
            error: function () {
              $('#myModal_espera').modal('hide');
              alert("Ocurrio un error inesperado, por favor contacte a soporte.");
            }
          });
        }
      }
    });
  } else {
    swal.fire('No se ha definido un Codigo de usuario, ingrese un RUC/CI para obtener el codigo.', '', 'warning')
  }
}

function DeleteMedidor() {
  let idMedidor = $("#CMedidor").val();
  let TxtApellidosS = $("#nombrec").val();
  let CodigoC = $("#codigoc").val();

  if (idMedidor != "." && idMedidor != "") {
    Swal.fire({
      title: `Esta seguro que desea Eliminar\nEl Medidor No. ${idMedidor} \nDe ${TxtApellidosS}`,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'No.',
      confirmButtonText: 'Si, Eliminar'
    }).then((result) => {
      if (result.value == true) {
        $.ajax({
          type: 'POST',
          dataType: 'json',
          url: '../controlador/modalesC.php?DeleteMedidor=true',
          data: { 'Cuenta_No': idMedidor, 'TxtCodigo': CodigoC },
          beforeSend: function () {
            $('#myModal_espera').modal('show');
          },
          success: function (response) {
            $('#myModal_espera').modal('hide');
            if (response.rps) {
              Swal.fire('¡Bien!', response.mensaje, 'success')
            } else {
              Swal.fire('¡Oops!', response.mensaje, 'warning')
            }
            ListarMedidores(CodigoC)
          },
          error: function () {
            $('#myModal_espera').modal('hide');
            alert("Ocurrio un error inesperado, por favor contacte a soporte.");
          }
        });
      }
    })
  } else {
    swal.fire('Debe seleccionar el medidor que desea eliminar', '', 'warning')
  }
}

function ListarMedidores(codigo) {
  if (codigo != "" && codigo != ".") {
    $.ajax({
      url: '../controlador/modalesC.php?ListarMedidores=true',
      type: 'POST',
      dataType: 'json',
      data: { 'codigo': codigo },
      success: function (response) {
        // construye las opciones del select dinámicamente
        var select = $('#CMedidor');
        select.empty(); // limpia las opciones existentes
        $.each(response, function (i, opcion) {
          if (opcion.Cuenta_No == ".") {
            select.append($('<option>', {
              value: '.',
              text: 'NINGUNO'
            }));
          } else {
            select.append($('<option>', {
              value: opcion.Cuenta_No,
              text: opcion.Cuenta_No
            }));
          }
        });
        $('#CMedidor').change()
      }
    });
  }

}

function MostrarOcultarBtnAddMedidor() {
  if ($('#codigoc').val() != "" && $('#codigoc').val() != ".") {
    $("#AddMedidor").removeClass("no-visible")
    ListarMedidores($('#codigoc').val())
  } else {
    $("#AddMedidor").addClass("no-visible")
  }
}

function tipo_proveedor_Cliente() {
  $.ajax({
    url: '../controlador/modalesC.php?tipo_proveedor=true&TP=TIPOPROV',
    type: 'post',
    dataType: 'json',
    success: function (response) {
      var op = '<option value=".">Seleccione</option>';
      response.forEach(function (item, i) {
        console.log(item)
        op += "<option value='" + item.Proceso + "'>" + item.Proceso + "</option>";
      })
      $('#txt_actividadC').html(op);
    },
    error: function (xhr, textStatus, error) {
      $('#myModal_espera').modal('hide');
    }
  });
}
//FUNCIONES BOTONES CXC y CXP
function cargar_cuentas(tipo) {
  if ($('#txt_id').val() == '') {
    Swal.fire('Selecione un registro', '', 'info');
    return false;
  }
  $('#modal_cuentas').modal('show');

  $('#txt_nombre_cuenta').val($('#nombrec').val());
  $('#txt_ci_cuenta').val($('#codigoc').val());
  if (tipo == 'cxc') {
    $('#titulo').text('ASIGNACION DE CUENTAS POR COBRAR');
    $('#SubCta').val('C');
    $('#cbx_cuenta_g').prop('disabled', true);


  } else {

    $('#cbx_cuenta_g').prop('disabled', false);
    $('#titulo').text('ASIGNACION DE CUENTAS POR PAGAR');
    $('#SubCta').val('P');
  }
  DLCxCxP();
  DLGasto();
  DLSubModulo();
}

function DLCxCxP() {
  $('#DLCxCxP').select2({
    placeholder: 'Seleccione una beneficiario',
    ajax: {
      url: '../controlador/modalesC.php?DLCxCxP=true&SubCta=' + $('#SubCta').val(),
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        console.log(data);
        return {
          results: data
        };
      },
      cache: true
    }
  });
}

function DLGasto() {
  $('#DLGasto').select2({
    placeholder: 'Seleccione una beneficiario',
    width: '100%',
    ajax: {
      url: '../controlador/modalesC.php?DLGasto=true&SubCta=' + $('#SubCta').val(),
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        console.log(data);
        return {
          results: data
        };
      },
      cache: true
    }
  });
}

function DLSubModulo() {
  $('#DLSubModulo').select2({
    placeholder: 'Seleccione una beneficiario',
    width: '100%',
    ajax: {
      url: '../controlador/modalesC.php?DLSubModulo=true&SubCta=' + $('#SubCta').val(),
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        console.log(data);
        return {
          results: data
        };
      },
      cache: true
    }
  });
}

function cancelar() {
  $('#DLCxCxP').empty();
  $('#DLGasto').empty();
  $('#DLSubModulo').empty();
  $('#TxtCodRet').val('.');
  $('#TxtRetIVAB').val('.');
  $('#TxtRetIVAS').val('.');
  $('#txt_ci_cuenta').val('.');
  $('#Ttxt_nombre_cuenta').val('.');

  if ($('#cbx_retencion').prop('checked')) {
    $('#cbx_retencion').click();
  }
  if ($('#cbx_cuenta_g').prop('checked')) {
    $('#cbx_cuenta_g').click();
  }

}
function guardar_cuentas() {
  datos = $('#form_cuentas').serialize();
  $.ajax({
    url: '../controlador/farmacia/proveedor_bodegaC.php?guardar_cuentas=true',
    type: 'post',
    dataType: 'json',
    data: datos,
    success: function (response) {
      if (response == 1) {
        cancelar();
        $('#modal_cuentas').modal('hide');
        swal.fire('Asignación realizada correctamente', '', 'success');
      } else {
        swal.fire('Error al asignar', '', 'error');
      }
    }
  });
}
function mostar_porcentaje_retencion() {
  if ($('#cbx_retencion').prop('checked')) {
    $('#panel_retencion').css('display', 'block');
  } else {
    $('#panel_retencion').css('display', 'none');
  }
}
function mostar_cuenta_Gastos() {
  if ($('#cbx_cuenta_g').prop('checked')) {
    $('#panel_cuenta_gasto').css('display', 'block');
  } else {
    $('#panel_cuenta_gasto').css('display', 'none');
  }

}
//FIN FUNCIONES BOTONES CXC y CXP

//ESTE METODO NO ES IGUAL AL DE VB, SOLO SIRVE PARA LLENAR EL PANEL DE PRODUCTOS RELACIONADOS
function ListarCuenta(nombre){
  var parametros = {
    'cliente': nombre
  }
  $.ajax({
    data: { parametros: parametros },
    url: '../controlador/modalesC.php?ListarCuenta=true',
    type: 'post',
    dataType: 'json',
    success: function (response) {
      console.log(response);
      var cliente = response.Cliente;
      var lista = response.Lista;
      var li = "";
      if(cliente.length > 0){
        if(cliente[0]["T"] === "N"){
          li+= "<li>Activo</li>";
        }else{
          li+= "<li>Inactivo</li>";
        }
        if(cliente[0]["FA"]){
          li+= "<li>Cliente de Facturación</li>";
        }
        for(var i = 0; i < lista.length; i++){
          li += "<li>"+lista[i]+"</li>";
        }
        $('#listaProductosRelacionados').html(li);
      }
    }
  });

}