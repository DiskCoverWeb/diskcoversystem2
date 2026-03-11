
  $(document).ready(function () {
    contratistas();
    proyectos();
    ddl_personal();
    ddl_Rubro();
    lista_etapas();

     //enviar datos del cliente
    $('#ddl_personal').on('select2:select', function (e) {
      var data = e.params.data.data;

      $('#lbl_ci_ruc').text(data.CI_RUC);
      $('#lbl_cargo').text(data.Actividad)
      $('#lbl_fecha_na').text(formatoDate(data.Fecha_N.date));
      var anios = calcular_edad(data.Fecha_N.date)
      $('#lbl_edad').text(anios);

      $('#pnl_data_personal').removeClass('d-none');

      console.log(data);
    });

    $('#ddl_personal').on('select2:clear', function (e) {
      $('#pnl_data_personal').addClass('d-none');
    });


  });


function ddl_Rubro(){

     $('#ddl_Rubro').select2({
      placeholder: 'Centro costo',
      dropdownParent: $('#myModal_orden_trabajo'),
      ajax: {
        url: '../controlador/inventario/orden_trabajo_constC.php?ddl_Rubro=true',
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


function proyectos()
{

  $('#ddl_proyecto').select2({
    placeholder: 'Seleccione proyecto',
    dropdownParent: $('#myModal_proyecto'),
    allowClear: true,
    width: '100%',
    ajax: { 
    url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?proyecto=true',
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


function ddl_cate_contrato(){
     var pro = $('#ddl_proyecto').val();
     $('#ddl_cate_contrato').select2({
      placeholder: 'Seleccion tipo de contrato',
      dropdownParent: $('#myModal_proyecto'),
      allowClear: true,
      ajax: {
        url: '../controlador/inventario/contrato_trabajo_detalle_constC.php?ddl_Proceso=true&idproyecto='+pro,
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



  function GuardarContrato()
  {
     var parametros = 
     {
        'proyecto':$('#ddl_proyecto').val(),
        'contratista':$('#ddl_contratista').val(),
        'material':$('input[name="rbl_material"]:checked').val(),
        'mas_personas':$('input[name="rbl_mas_personas"]:checked').val(),
        'cate_contrato':$('#ddl_cate_contrato').val(),
        'cate_contrato_name':$('#ddl_cate_contrato option:selected').text(),
        'fecha_inicio':$('#txt_fecha_inicio').val(),
        'fecha_fin':$('#txt_fecha_fin').val(),
     }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?GuardarContrato=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
                if(response.respuesta)
                {
                    Swal.fire("Contrato guardado","","success").then(function(){
                         const url = new URL(window.location.href);
                        url.searchParams.set('ordenNo', response.contrato);
                        window.location.href = url.toString();
                    })
                }
          }
        });

  }


function ddl_personal()
{
  $('#ddl_personal').select2({
    placeholder: 'Seleccione ddl_personal',
    dropdownParent: $('#myModal_personal'),
    allowClear: true,
    ajax: {
      url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?personal=true',
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


function contratistas()
{
  $('#ddl_contratista').select2({
    placeholder: 'Seleccione contratista',
    dropdownParent: $('#myModal_proyecto'),
    allowClear: true,
    width: '100%',
    ajax: {
      url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?contratistas=true',
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


  function detalleContrato()
  {
     var parametros = 
     {
        'contrato':ordenNo,
     }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?detalleContrato=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              console.log(response)
                var mate = 'NO';
                var mas_per = 'NO';
                $('#lbl_proyecto').text(response[0].proyecto);
                $('#lbl_contratista').text(response[0].Cliente)
                if(response[0].Cargo_Mat==1){mate = 'SI';}
                if(response[0].mas_per==1){mate = 'SI';}
                $('#lbl_material').text(mate)
                $('#lbl_mas_personas').text(mas_per);
                $('#lbl_nombre_contrato').text(response[0].Nombre_Contrato)
                $('#lbl_categoria').text(response[0].Proceso)
                // $('#lbl_tipo_costo').text(response[0].tipo_costo)
                // $('#lbl_cuenta_contable').text(response[0].cuenta_contable)
                $('#lbl_fecha').text(formatoDate(response[0].Fecha.date))
                $('#lbl_fecha_v').text(formatoDate(response[0].Fecha_V.date))
                $('#txt_cuenta_proyecto').val(response[0].ProyectoID);

                console.log(response);
          }
        });

  }

  function calcular_total()
  {
    if($('#txt_cantidad').val()=='') {   $('#txt_cantidad').val(0);  }
    if($('#txt_pvp').val()=='') {   $('#txt_pvp').val(0);  }
    var cantidad = $('#txt_cantidad').val();
    var pvp = $('#txt_pvp').val();
    
    var total = parseFloat(cantidad)*parseFloat(cantidad);
    $('#txt_total').val(total.toFixed(4));
  }

function ingresar_orden()
{
  var parametros = 
     {
        'orden':ordenNo,
        'etapa':$('#ddl_etapa').val(),
        'rubro':$('#ddl_Rubro').val(),
        'centro_costo':$('#ddl_cc').val(),
        'unidad':$('#txt_unidad_med').val(),
        'pvp':$('#txt_pvp').val(),
        'cantidad':$('#txt_cantidad').val(),
        'total':$('#txt_total').val()
     }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?ingresarOrden=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
                if(response)
                {
                    lista_solicitud_rubro();
                }
          }
        });
}

function lista_solicitud_rubro()
{
    var parametros = 
     {
        'orden':ordenNo
     }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?lista_solicitud_rubro=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              $('#tbl_body_orden').html(response);
          }
        });

}



function lista_etapas()
{
  var proy = $('#txt_cuenta_proyecto').val();
  console.log(proy);
  $('#ddl_etapa').select2({
    placeholder: 'Seleccione etapa',
    dropdownParent: $('#myModal_orden_trabajo'),
    ajax: {
      url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?lista_etapas=true&pro='+proy,
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

function lista_cc(){
     var pro = $('#txt_cuenta_proyecto').val();  
     var etapa = $('#ddl_etapa').val();   
     $('#ddl_cc').select2({
      placeholder: 'Centro costo',
      dropdownParent: $('#myModal_orden_trabajo'),
      ajax: {
        url: '../controlador/inventario/contrato_trabajo_detalle_constC.php?cc=true&pro='+pro+'&etapaCC='+etapa,
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

  function ingresar_personal_orden()
  {    
      var parametros = 
         {
            'orden':ordenNo,
            'personal':$('#ddl_personal').val()
         }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?ingresar_personal_orden=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
                if(response)
                {
                  Swal.fire("Agregado a personal","","success").then(function(){
                      lista_personal_contrato();
                  })
                }
          }
        });

  }

  function lista_personal_contrato()
  {    
      var parametros = 
         {
            'orden':ordenNo,
         }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?lista_personal_contrato=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              $('#tbl_body_personal').html(response);
                console.log(response)
          }
        });
  }

  function eliminar_personal(id)
  {
     var parametros = 
         {
            'id':id,
         }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?eliminar_personal=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              lista_personal_contrato();
          }
        });

  }

  function eliminar_rubros(id)
  {
     var parametros = 
         {
            'id':id,
         }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?eliminar_rubros=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              lista_solicitud_rubro();
          }
        });

  }

  function ver_resumen()
  {
     $('#myModal_ver_resumen').modal("show");
  }

  function agregar_a_orden()
  {
    lista_etapas();
     $('#myModal_orden_trabajo').modal('show')
  }
  function agregar_personal()
  {
     $('#myModal_personal').modal('show')
  }

  function nuevo_personal()
  {
    $('#myModal_nuevo_personal').modal('show');

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
        setTimeout(()=>{
          $('#myModal_espera').modal('hide');
        }, 500);
        MostrarOcultarBtnAddMedidor()
      }
    });
  } else {
    limpiar();
  }

}

function limpiar() {
  $('#txt_id').val(''); // display the selected text
  $('#ruc').val(''); // display the selected text
  $('#nombrec').val(''); // save selected id to input
  $('#direccion').val(''); // save selected id to input
  $('#telefono').val(''); // save selected id to input
  $('#codigoc').val(''); // save selected id to input
  $('#email').val(''); // save selected id to input
  $('#txt_ejec').val(''); // save selected id to input
  $('#txt_fecha_na').val(''); // save selected id to input
  $('#txt_edad').val(''); // save selected id to input
}



function buscar_numero_ci() {
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
      console.log(response);
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
        $('#grupo').val(response[0].grupo); // save selected id to input
        $('#prov').val(response[0].provincia); // save selected id to input
        $('#TD').val(response[0]['TD'])
        $('#txt_ejec').val(response[0]['Actividad'])
        $('#txt_fecha_na').val(formatoDate(response[0]['Fecha_N'].date))

        anios = calcular_edad(response[0]['Fecha_N'].date);        
        $('#txt_edad').val(anios)


        console.log(response[0])
       } else {
        $('#ruc').val(ci_ruc);
        codigo();
      }
      setTimeout(()=>{
        $("#myModal_espera").modal('hide');
      }, 500);

    }
  });
}

function calcular_edad_form()
{
    var fecha = $('#txt_fecha_na').val();
    var anios = calcular_edad(fecha);        
    $('#txt_edad').val(anios)
}

function calcular_edad(fecha_na)
{
  const nacimiento = new Date(fecha_na);
      const hoy = new Date();
      
      let anios = hoy.getFullYear() - nacimiento.getFullYear();
      
      // Restar un año si aún no ha cumplido años este año
      if (hoy.getMonth() < nacimiento.getMonth() || 
          (hoy.getMonth() === nacimiento.getMonth() && hoy.getDate() < nacimiento.getDate())) {
          anios--;
      }

      return anios

}



function guardar_cliente() {
  $('#myModal_espera').modal('show');

  var rbl = $('#rbl_facturar').prop('checked');
  var datos = $('#form_cliente').serialize();
  $.ajax({
    data: datos + '&rbl=' + rbl,
    url: '../controlador/modalesC.php?guardar_cliente_orden_trabajo=true',
    type: 'post',
    dataType: 'json',
    success: function (response) {
      setTimeout(()=>{
        $('#myModal_espera').modal('hide');
      }, 2000);
      // console.log(response);
      var url = location.href;
      if (response == 1) {
        if ($('#txt_id').val() != '') 
        {
          swal.fire('Registro guardado', '', 'success').then(function(){
            limpiar();
            $('#myModal_nuevo_personal').modal("hide");
          });

        } else {
          swal.fire('Registro guardado', '', 'success').then(function(){
            limpiar();
            $('#myModal_nuevo_personal').modal("hide");
          });
        }

      } else if (response == 2) {
        swal.fire('Este CI / RUC ya esta registrado', '', 'info');
      } else if (response == 3) {
        swal.fire('El Nombre ya esta registrado', '', 'info');
      }
    },
    error:function(err){
      swal.fire('Ocurrio un error al procesar la solicitud. Error: ' + err, '', 'error');
      setTimeout(() => {
        $('#myModal_espera').modal('hide');
      }, 2000)
    }
  });
}


  function grabar_orden_trabajo()
  {   

    var parametros = 
    {
      'pedido':ordenNo,
    }
      $.ajax({
          url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?grabar_orden_trabajo=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
              swal.fire("orden procesada",'','success').then(function(){
                  location.href = "../vista/inicio.php?mod="+ModuloActual+"&acc=contrato_trabajo_const";
              })
            }
          
          }
      });
  }