  $(document).ready(function () {

      tbl_pedidos_all = $('#tbl_lista_solicitud').DataTable({
          // responsive: true,
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
              url:   '../controlador/inventario/orden_ejecucionC.php?lista_orden_ejecucion=true',
              type: 'POST',  // Cambia el método a POST    
              data: function(d) {
                  var parametros = {                    
                    orden:ordenNo,
                  };
                  return { parametros: parametros };
              },
              dataSrc: '',             
          },
          // searching: false,
          // responsive: true,
          // paging: false,   
          // info: false,   
          // autoWidth: true,
          scrollX:true,
          columns: [
              { data: null, // Columna autoincremental
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // meta.row es el índice de la fila
                    }
              },
              { data: 'Cliente' },
              { data: null,
                 render: function(data, type, item) {
                    // <button type="button" title="Imprimir Etiqueta" class="btn btn-warning btn-sm p-0 m-0" onclick="imprimir_pedido_pdf()"><i class="bx bx-printer m-0"></i></button>
                    // <button type="button" title="Editar Pedido" class="btn btn-primary btn-sm p-0 m-0" onclick="editar_pedido()"><i class="bx bx-pencil m-0"></i></button>
                 
                    return `<a href="inicio.php?mod=`+ModuloActual+`&acc=detalle_orden_trabajo_const&ordenNo=${data.No_Contrato}">${data.No_Contrato}</a>`;                    
                  }
              },
              { data: 'Fecha_D.date',  
                  render: function(data, type, item) {
                      return data ? new Date(data).toLocaleDateString() : '';
                  }
              },
              { data: null,
                 render: function(data, type, item) {
                    // <button type="button" title="Imprimir Etiqueta" class="btn btn-warning btn-sm p-0 m-0" onclick="imprimir_pedido_pdf()"><i class="bx bx-printer m-0"></i></button>
                    // <button type="button" title="Editar Pedido" class="btn btn-primary btn-sm p-0 m-0" onclick="editar_pedido()"><i class="bx bx-pencil m-0"></i></button>
                 
                    return `
                     <button type="button" title="Eliminar Pedido" class="btn btn-danger btn-sm p-0 m-0" onclick="eliminar_pedido('${data.ID}')" disabled=""><i class="bx bx-trash m-0"></i></button>`;                    
                  }
              },
              
          ],
          order: [
              [1, 'asc']
          ]
      });


      $("#ddl_Rubro").on('select2:select', function (e) {
         var data = e.params.data.data;
         console.log(data);
         $('#ddl_Contrato').append('<option value="' + data.Orden_Trabajo +'">' + data.Orden_Trabajo+ '</option>');         
         $('#ddl_Contrato').val(data.Orden_Trabajo);
         detalleContrato(data.Orden_Trabajo)
         lista_semanas();
       });

  })


function detalleContrato(ordenNo)
{
     var parametros = 
     {
        'contrato':ordenNo,
     }
      $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/inventario/orden_ejecucionC.php?detalleContrato=true',
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

              $('#pnl_detalle_proyecto').removeClass("d-none");

              console.log(response);
        }
      });

}



function contratistas()
{
  $('#ddl_Contrato').val("");
  $('#ddl_contratista').select2({
    placeholder: 'Seleccione contratista',
    width:'resolve',
    allowClear: true,
    ajax: {
      url:   '../controlador/inventario/orden_ejecucionC.php?contratistas=true',
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

function cargar_contratos()
{
    $('#ddl_Contrato').val("");
    $('#ddl_Rubro').empty();
    // $('#ddl_Rubro').val("");
    var contratista = $('#ddl_contratista').val();
    $('#ddl_Rubro').select2({
      placeholder: 'Seleccione contratista',
      allowClear: true,
      width:'resolve',
      ajax: {
        url:   '../controlador/inventario/orden_ejecucionC.php?contratos=true&ContratosContratista='+contratista,
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

function lista_semanas()
{
  var parametros = 
  {
    'contrato':$('#ddl_Contrato').val(),
    'contratista':$('#ddl_contratista').val(),
    'rubro':$('#ddl_Rubro').val(),
  }
  $.ajax({
        type: "POST",
        url: '../controlador/inventario/orden_ejecucionC.php?lista_semanas=true',
        data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
          lista = '<option>Seleccione semana</option>'
           data.forEach(function(item,i){
            lista+='<option value="'+item.Semana+'">'+item.Semana+'</option>'
           })

           $('#ddl_semana').html(lista);     
        }
    });   
}

// function ddl_cuenta_contable(){

//      $('#ddl_cuenta_contable').select2({
//       placeholder: 'Centro costo',
//       ajax: {
//         url: '../controlador/inventario/orden_trabajo_constC.php?ddl_cuenta_contable=true',
//         dataType: 'json',
//         delay: 250,
//         processResults: function (data) {
//           return {
//             results: data
//           };
//         },
//         cache: true
//       }
//     });
// }

// function ddl_Proceso(){

//      $('#ddl_Proceso').select2({
//       placeholder: 'Centro costo',
//       ajax: {
//         url: '../controlador/inventario/orden_trabajo_constC.php?ddl_Proceso=true',
//         dataType: 'json',
//         delay: 250,
//         processResults: function (data) {
//           return {
//             results: data
//           };
//         },
//         cache: true
//       }
//     });
// }


// function ddl_Grupo(){

//      $('#ddl_Grupo').select2({
//       placeholder: 'Centro costo',
//       ajax: {
//         url: '../controlador/inventario/orden_trabajo_constC.php?ddl_Grupo=true',
//         dataType: 'json',
//         delay: 250,
//         processResults: function (data) {
//           return {
//             results: data
//           };
//         },
//         cache: true
//       }
//     });
// }


function ddl_Rubro(){

     $('#ddl_Rubro').select2({
      placeholder: 'Centro costo',
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

// function agregar_tabla()
// {
//   var orden = ordenNo; 
//   var data = $('#form_datos').serialize();
//   $.ajax({
//     url: '../controlador/inventario/orden_trabajo_constC.php?agregar_tabla=true',
//     type:'post',
//     dataType:'json',
//     data:{parametros:data,orden:orden},     
//     success: function(response){
//       if(response.resp==1)
//       {
//         Swal.fire("Agregado","","success").then(function(){
//           if(orden==-1)
//           {
//               var url = new URL(window.location.href);
//               var params = new URLSearchParams(url.search);
//               params.set('ordenNo',response.orden);
//               window.location.href = url.pathname + '?' + params.toString();

//            }else
//            {
//             cargar_lista();
//            }

//         })
//       }else
//       {
//         Swal.fire("Algo salio mal","","error")
//       }
//     // console.log(response);
//     }
//   });
// }

function cargar_lista()
{
  tbl_pedidos_all.ajax.reload(null, false);
}

// function eliminar_pedido(id)
// {

//      Swal.fire({
//      title: 'Esta seguro?',
//      text: "Esta usted seguro de que quiere eliminar este registro!",
//      icon: 'warning',
//      showCancelButton: true,
//      confirmButtonColor: '#3085d6',
//      cancelButtonColor: '#d33',
//      confirmButtonText: 'Si!'
//    }).then((result) => {
//      if (result.value==true) {
//       eliminar(id);
//      }
//    })

// }

// function eliminar(ID)
// {   
//       $.ajax({
//         type: "POST",
//         url: '../controlador/inventario/orden_trabajo_constC.php?eliminar_linea=true',
//         data:{ID:ID},
//         dataType:'json',
//         success: function(data)
//         {
//             if(data ==1)
//             {
//                 Swal.fire('Registro eliminado','','info');
//               cargar_lista();
//             }     
//         }
//     });   
// }

// function ddl_sub_rubro()
// {
//   var rubro = $('#ddl_Rubro').val();  
//   $('#ddl_sub_rubro').select2({
//     placeholder: 'Seleccione contratista',
//     width:'100%',
//     allowClear: true,
//     ajax: {
//       url:   '../controlador/inventario/orden_trabajo_constC.php?subrubro=true&rubro='+rubro,
//         dataType: 'json',
//         delay: 250,
//         processResults: function (data) {
//           return {
//             results: data
//           };
//         },
//         cache: true
//       }
//     });
// }

function calcular_costo_total()
{
  var cantidad = $('#txt_cantidad').val();
  var pvp = $('#txt_costo_pvp').val();
  var total = cantidad * pvp;
  if(cantidad=='' || pvp=='')
  {
    $('#txt_costo_total').val(0);
  }else
  {
    $('#txt_costo_total').val(total.toFixed(2));
  }
}

// function add_subRubro()
// {
//     var contratista = $('#ddl_contratista').val();
//     var rubro = $('#ddl_Rubro').val();
//     var Contrato=$('#ddl_Contrato').val();
//     var subRubro = $('#ddl_sub_rubro').val();
//     var unidad = $('#txt_unidad').val();
//     var cantidad = $('#txt_cantidad').val();
//     var pvp = $('#txt_costo_pvp').val();
//     var total = $('#txt_costo_total').val();
//     var centroCostos = $('#txt_centro_costos').val();

//     var parametros = 
//     {
//       'contratista':contratista,
//       'rubro':rubro,
//       'centroCostos':centroCostos,
//       'Contrato':Contrato,
//       'subRubro':subRubro,
//       'unidad':unidad,
//       'cantidad':cantidad,
//       'pvp':pvp,
//       'total':total,
//     }

//      $.ajax({
//         type: "POST",
//         url: '../controlador/inventario/orden_trabajo_constC.php?add_subRubro=true',
//         data:{parametros:parametros},
//         dataType:'json',
//         success: function(data)
//         {
//             if(data ==1)
//             {
//                 Swal.fire('Sub rubro ingresado','','success').then(function(){
//                   cargar_lista_subrubros();
//                 });
//             }     
//         }
//     });   
// }

function cargar_lista_subrubros()
{
  var contratista = $('#ddl_contratista').val();
  var rubro = $('#ddl_Rubro').val();
  var Contrato=$('#ddl_Contrato').val();
  var semana=$('#ddl_semana').val();

  lbl_semana = $('#ddl_semana').text().split(' - ');
   centro  = lbl_semana[1];

  $('#lbl_centro_costo').text(centro);
    
  var parametros = 
  {
    'contratista': contratista,
    'rubro': rubro,
    'Contrato': Contrato,
    'semana':semana,

  }
   $.ajax({
        type: "POST",
        url: '../controlador/inventario/orden_ejecucionC.php?cargar_lista_subrubros=true',
        data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {

          $('#tbl_subrubros').html(data);
       
            // table = '';
            //   data.forEach(function(item,i){
            //     table+=`<tr> 
            //                 <td><button type="button" class="btn btn-primary btn-sm" onclick="eliminar_subrubro('`+item.ID+`')"><i class="bx bx-save me-0"></i></button></td>
            //                 <td>`+item.Detalle+`</td> 
            //                 <td>`+item.Unidad+`</td>
            //                 <td>`+$('#ddl_Contrato').val()+`</td>
            //                 <td>`+item.Total+`</td>
            //                 <td><input type="text" class="form-control form-control-sm" /></td>
            //                 <td><input type="text" class="form-control form-control-sm" readonly /></td>
            //                 <td><input type="text" class="form-control form-control-sm" readonly /></td>
            //                 <td><input type="text" class="form-control form-control-sm" readonly /></td>
            //             </tr>`
            //   })
            //   $('#tbl_body').html(table);
        }
    });   

}

function eliminar_subrubro(id)
{
    Swal.fire({
          title: 'Esta seguro de eliminar',
          text: "Se eliminara este registro",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Continuar'
        }).then((result) => {
          if (result.value) {
            delete_subrubro(id)
          }
        })

}

function delete_subrubro(id)
{
  var parametros = 
  {
    'id': id,
  }
   $.ajax({
        type: "POST",
        url: '../controlador/inventario/orden_trabajo_constC.php?delete_subrubro=true',
        data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
          if(data==1)
          {
            cargar_lista_subrubros();
          }       
          
        }
    });   
}

function modal_periodo_trabajo()
{
  $('#myModal_periodo_trabajo').modal('show');
  numero_semanas();
}

function numero_semanas()
{
  var option = '<option value="">Seleccione semana </option>';
  for (var i = 1; i <=52; i++) {
      option+='<option value="'+i+'">'+i+'</option>';
  }
  $('#ddl_semana').html(option);
}

function guardar_periodo()
{
  var semana = $('#ddl_semana').val()
  var fechaInicio = $('#txt_fechaIni_eje').val()
  var fechaFin = $('#txt_fechaFin_eje').val()
  var observacion = $('#txt_observacion').val()

  var centroCostos = $('#txt_centro_costos').val()
  var idCentroCostos = $('#txt_id_centro_costos').val()
  var idrubro = $('#txt_rubro').val()
  var contrato = $('#ddl_Contrato').val()

  if(semana=='' || fechaInicio=='' || fechaFin=='')
  {
    Swal.fire("Llene todo los campos","","error")
    return false;
  }


  var parametros = 
  {
    'idCentroCostos':idCentroCostos,
    'centroCostos':centroCostos,
    'idrubro':idrubro,
    'contrato':contrato,
    'semana': semana,
    'fechaInicio': fechaInicio,
    'fechaFin': fechaFin,
    'observacion': observacion,
    'atrazo': $('#txt_retrazo').val(),
    'adelanto': $('#txt_adelanto').val(),
  }
   $.ajax({
        type: "POST",
        url: '../controlador/inventario/orden_ejecucionC.php?guardar_periodo=true',
        data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
          if(data==1)
          {
            Swal.fire("Fechas de ejecucion Asignadas","","success");
            centrosCostocXRubro(contrato);
            $('#myModal_periodo_trabajo').modal('hide');
          }       
          
        }
    });   
}


function grabar_orden_trabajo()
{
  var rubro = $('#ddl_Rubro').val()
  var contrato = $('#ddl_Contrato').val()

  var parametros = 
  {
    'rubro':rubro,
    'contrato':contrato,
  }
   $.ajax({
        type: "POST",
        url: '../controlador/inventario/orden_trabajo_constC.php?grabar_orden_trabajo=true',
        data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
          if(data.respuesta!=1)
          {
            Swal.fire("No se pudo Guardar",data.mensaje,"info");
          }  

          if(data.respuesta==1)
          {
            Swal.fire("Orden de trabajo generada","","success").then(function(){
              location.reload();
            });
          }       
          
        }
    });   
}

function calcular_ejecutado(id)
{
  var pvp = $('#txt_pvp_'+id).val();
  var can = $('#txt_cantidad_'+id).val();
  var eje = $('#txt_ejecucion_'+id).val();

  if(eje>can || eje<0  || eje=="")
  {
    Swal.fire("Ingrese un cantidad valida","","info");
    $('#txt_ejecucion_'+id).val(can);
    return false;
  }

  var dif = can-eje;
  var total = eje*pvp;
  
  $('#txt_ejecutado_pvp_'+id).val(pvp);
  $('#txt_ejecutado_total_'+id).val(total);
  $('#txt_ejecutado_dif_'+id).val(dif);

}

function guardar_subrubro_ejecucion(id)
{

  var ejec = $('#txt_ejecucion_'+id).val();
  var pvp_ejec = $('#txt_ejecutado_pvp_'+id).val();
  var total_ejec = $('#txt_ejecutado_total_'+id).val();
  var ejec_dif = $('#txt_ejecutado_dif_'+id).val();

  var parametros = 
  {
    'id':id,
    'ejec':ejec,
    'pvp_ejec':pvp_ejec,
    'total_ejec':total_ejec,
    'ejec_dif':ejec_dif,
  }
   $.ajax({
        type: "POST",
        url: '../controlador/inventario/orden_ejecucionC.php?guardar_subrubro_ejecucion=true',
        data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
          if(data)
          {
            Swal.fire("Guardado",'','success').then(function(){
                cargar_lista_subrubros()
            })
          }        

        }
    });   
}


function add_periodo(id)
{
  $('#myModal_periodo_trabajo').modal('show')
  $('#txt_rubro').val(id);
  var parametros = 
  {
    'id':id,
  }
   $.ajax({
        type: "POST",
        url: '../controlador/inventario/orden_ejecucionC.php?cargar_fecha_periodo=true',
        data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
           if(data.length>0)
           {
            console.log(data);
            $('#lbl_fecha_ini_sub').text(formatoDate(data[0].Fecha_Inicio.date))
            $('#lbl_fecha_fin_sub').text(formatoDate(data[0].Fecha_Fin.date))

           }
        }
    });   
}

function calcular_dias()
{
    var InicioOrden =  new Date($('#lbl_fecha_ini_sub').text());
    var FinOrden =  new Date($('#lbl_fecha_fin_sub').text());
    var InicioEje =  new Date($('#txt_fechaIni_eje').val());
    var FinEje =  new Date($('#txt_fechaFin_eje').val());

    if(InicioEje<InicioOrden)
    {
      Swal.fire("El Inicio de ejecucion no debe ser menor al de la orden","","info");
      $('#txt_fechaIni_eje').val("");
      return false;
    }

    if(FinEje < InicioEje)
    {
      Swal.fire("El Fin de ejecucion no debe ser menor al de la orden o de ejecucion","","info");
      $('#txt_fechaFin_eje').val("");
      return false;
    }

    var adelantado = 0;
    var atrasado = 0;

    if(FinEje>FinOrden)
    {
        var atrasado = diferenciaEnDias(FinEje, FinOrden);
        $('#txt_retrazo').val(atrasado);
        $('#txt_adelanto').val(0);
    }

    if(FinEje<FinOrden)
    {
        var adelantado = diferenciaEnDias(FinEje, FinOrden);
        $('#txt_retrazo').val(0);
        $('#txt_adelanto').val(adelantado);
    }
}


