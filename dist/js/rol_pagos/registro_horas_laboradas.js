$(document).ready(function(){
     $('#beneficiario').on('change', function(){
          let fecha = $('#txt_fecha').val();
          //let val = $(this).val();
          let NombreCliente = $(this).find('option:selected').text();
          let CodigoCliente = '';
          let Evaluar, MiTiempo1, TotalIngreso;
          let opcionMovimientos = $('input[name="Movimientos"]:checked').val();
          let Grupo = $(this).find('option:selected').text();
          obtenerBeneficiarios().then(function(beneficiarios){
               $.each(beneficiarios, function(key, value){
                    if (NombreCliente == value.Cliente){
                         NombreCliente = value.Cliente;
                         CodigoCliente = value.Codigo;
                         Evaluar = value.Horas_Ext;
                         Grupo = value.Grupo;
                         //$('#').val(value.Valor_hora);
                         MiTiempo1 = value.Horas_Sem * 4;
                         TotalIngreso = value.Salario;
                    }
                    else if (Grupo == value.Grupo){
                         NombreCliente = value.Cliente;
                         CodigoCliente = value.Codigo;
                         Evaluar = value.Horas_Ext;
                         Grupo = value.Grupo;
                         //$('#').val(value.Valor_hora);
                         MiTiempo1 = value.Horas_Sem * 4;
                         TotalIngreso = value.Salario;
                    }
               });
               if (CodigoCliente === ''){
                    swal.fire('Codigo no asignado', '', 'error');
                    return;
               } else {
                    parametros = {
                         'NombreCliente': NombreCliente,
                         'Codigo': CodigoCliente,
                         'Grupo': Grupo,
                         'Fecha': fecha,
                         'OpcMov': opcionMovimientos,
                    }
                    $.ajax({
                         type: 'POST',
                         url: '../controlador/rol_pagos/registro_horas_laboradasC.php?datos_beneficiario=true',
                         data: parametros,
                         dataType: 'json',
                         success: function(response){
                              $('#tbl_sueldo').DataTable({
                                   language:{
                                   url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json',
                                   },
                                   data: ProcesarDatos(response.HorasTrabajadas),
                                   columns: [
                                        {data: 'Codigo', title: 'Codigo'},
                                        {data: 'Dias', title: 'Dias'},
                                        {data: 'Fecha',
                                             render: function(data){
                                                  const fecha_s = data?.date;
                                                  return fecha ? new Date(fecha_s).toLocaleDateString() : '';                                                   },
                                             title: 'Fecha'},
                                        {data: 'Horas', title: 'Horas'},
                                        {data: 'Horas_Exts', title: 'Horas_Exts'},
                                        {data: 'Porc_Hr_Ext', title: 'Porc_Hr_Ext'},
                                        {data: 'Valor_Hora', title: 'Valor_Hora'},
                                        {data: 'Ing_Liquido', title: 'Ing_Liquido'},
                                        {data: 'Ing_Horas_Ext', title: 'Ing_Horas_Ext'},
                                        {data: 'Orden', title: 'Orden'}
                                   ],
                                   scrollX: true,
                                   scrollY: '400px',
                                   scrollCollapse: true,
                                   destroy: true,
                                   paging: false,
                                   searching: false,
                                   info: false,
                                   createdRow: function(row, data){
                                        alignEnd(row, data);
                                   },
                              });
                              $('#tbl_novedades').DataTable({
                                   language:{
                                   url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json',
                                   },
                                   data: ProcesarDatos(response.novedades),
                                   columns: [
                                        {data: 'Fecha',
                                             render: function(data){
                                                  const fecha_n = data?.date;
                                                  return fecha ? new Date(fecha_n).toLocaleDateString : '';
                                             },
                                        title: 'Fecha',
                                        },
                                        {data: 'Hora', title: 'Hora'},
                                        {data: 'Proceso', title: 'Proceso'},
                                        {data: 'Novedades', title: 'Novedades'},
                                        {data: 'Codigo', title: 'Codigo'}
                                   ],
                                   scrollX: true,
                                   scrollY: '400px',
                                   scrollCollapse: true,
                                   destroy: true,
                                   paging: false,
                                   searching: false,
                                   info: false,
                                   createdRow: function(row, data){
                                        alignEnd(row, data);
                                   },
                              });
                              $('#txt_total_horas_t').val(response.Total);
                              $('#txt_total_ing_liq').val(response.Saldo);
                              if (Array.isArray(response.Dato_Valor_Hora) && response.Dato_Valor_Hora.length > 0) {
                                   valor_hora = response.Dato_Valor_Hora[0].Valor_Hora
                                   console.log(valor_hora);
                              }
                         },
                         error: function(){
                              Swal.fire('Error al obtener datos, intentelo nuevamente', '', 'error');
                         }
                    })
               }
          });
     });

     $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
          const target = $(e.target).attr("href");
          $(target).find('table.dataTable').each(function () {
               if ($.fn.DataTable.isDataTable(this)) {
               $(this).DataTable().columns.adjust().draw();
               }
          });
     });


})

function GetValorHora(parametros){
     return $.ajax({
          type: 'POST',
          url: '',
          data: parametros,
          success: function (response){

          },
          error: function(){
               Swal.fire('Error!', 'Ocurrió un problema', 'error')
          }
     })
}

function generarDias(){
     let parametros = '';
     let opcionIngreso = $('input[name="Ingreso"]:checked').val(); //Valor = Nombre del Label
     let fecha = $('#txt_fecha').val();
     fecha = fecha.replace(/-/g, "/");
     isFecha = fecha_valida_valor(fecha);
     if (isFecha.valido === true){
          ShowModalEspera();
          parametros = {
               'Fecha': fecha,
               'OpcIngreso': opcionIngreso,
          };
          $.ajax({
               type: 'POST',
               url: '../controlador/rol_pagos/registro_horas_laboradasC.php?generarDias=true',
               data: parametros,
               success: function(response){
                    if(response == 1){
                         Swal.fire('Días generados', '', 'success');
                    } else {
                         Swal.fire('Error', 'Ocurrió un error, intentelo nuevamente', 'error');
                    }
                    HideModalEspera();
               },
               error: function(){
                    Swal.fire('Ocurrió un problema!', '', 'error');
               }
          });
     } else {
          swal.fire('Error', isFecha.mensaje, 'error');
     }
     
}

function obtenerBeneficiarios(){
     let fecha = $('#txt_fecha').val();
     let parametros = {
          'Fecha': fecha
     };
     return $.ajax({
          type: 'POST',
          url: '../controlador/rol_pagos/registro_horas_laboradasC.php?beneficiarios=true',
          data: parametros,
          dataType: 'json'
     });
}

function rellenarBeneficiarios() {
     obtenerBeneficiarios().then(function(beneficiarios) {
          $('#beneficiario').html('<option value="">Seleccione un beneficiario</option>');          
          $.each(beneficiarios, function(key, value){
               $('#beneficiario').append(
                    $('<option>', {value: value.Codigo, text: value.Cliente})
               );
          });
     }).catch(function() {
          Swal.fire('Ocurrió un problema!', '', 'error');
     });
}