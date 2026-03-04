
  $(document).ready(function () {

  })

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
        'cc_':$('#ddl_cc_').val(),
        'cuenta_contable':$('#ddl_cuenta_contable').val(),
        'fecha_inicio':$('#txt_fecha_inicio').val(),
        'fecha_fin':$('#txt_fecha_fin').val(),
        'nombre_contrato':$('#txt_nombre_contrato').val(),
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
                var mate = 'NO';
                var mas_per = 'NO';
                $('#lbl_proyecto').text(response[0].proyecto);
                $('#lbl_contratista').text(response[0].Cliente)
                if(response[0].Cargo_Mat==1){mate = 'SI';}
                if(response[0].mas_per==1){mate = 'SI';}
                $('#lbl_material').text(mate)
                $('#lbl_mas_personas').text(mas_per);
                $('#lbl_nombre_contrato').text(response[0].Nombre_Contrato)
                $('#lbl_categoria').text(response[0].categoria)
                $('#lbl_tipo_costo').text(response[0].tipo_costo)
                $('#lbl_cuenta_contable').text(response[0].cuenta_contable)
                $('#lbl_fecha').text(formatoDate(response[0].Fecha.date))
                $('#lbl_fecha_v').text(formatoDate(response[0].Fecha_V.date))
                $('#txt_cuenta_proyecto').val(response[0].proyectoId);

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
              $('#accordionExample').html(response);
          }
        });

}



function lista_etapas()
{
  $('#ddl_etapa').select2({
    placeholder: 'Seleccione etapa',
    dropdownParent: $('#myModal_orden_trabajo'),
    ajax: {
      url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?lista_etapas=true',
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
     $('#ddl_cc').select2({
      placeholder: 'Centro costo',
      dropdownParent: $('#myModal_orden_trabajo'),
      ajax: {
        url: '../controlador/inventario/inventario_onlineC.php?cc=true&pro='+pro,
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
                    lista_personal_contrato();
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

