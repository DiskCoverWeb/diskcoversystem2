  var tbl_procesados_all = null;
  $(document).ready(function () {
    contratistas();


       tbl_procesados_all = $('#tbl_body_procesados').DataTable({
          // responsive: true,
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
              url:   '../controlador/inventario/orden_ejecucionC.php?cargar_lista_subrubros_avance=true',
              type: 'POST',  // Cambia el método a POST    
              data: function(d) {
                  var parametros = {                    
                    contratista:$('#ddl_contratista').val(),
                    contrato:$('#ddl_Contrato').val(),
                    rubro:$('#ddl_Rubro').val(),
                    mes:$('#ddl_meses').val(),
                  };
                  return { parametros: parametros };
              },
              dataSrc:  function(json) {

                  $('#lbl_cant_contrato').text(json.total_rubro)
                  $('#lbl_cant_procesado').text(json.total_procesado)
                  $('#lbl_porcentaje').text(json.porcentaje)
                  
                  // // Extraer solo el array 'lineas' de la respuesta
                  // // Guardar los totales en variables globales o en otro lugar
                  // if (json.total_rubro !== undefined) {
                  //     window.totalRubro = json.total_rubro;
                  // }
                  // if (json.total_procesado !== undefined) {
                  //     window.totalProcesado = json.total_procesado;
                  // }
                  
                  // Devolver solo las líneas para DataTables
                  return json.lineas || [];
              }           
          },
           scrollX: true,  // Habilitar desplazamiento horizontal
   
          columns: [
              { data: null, // Columna autoincremental
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // meta.row es el índice de la fila
                    }
              },
              { data: 'CentroCosto' },
              { data: 'SubRubro'},
              { data: 'Unidad'},
              { data: 'No_Contrato' },
              { data: 'Cant_Ejec' },
              { data: null,
                 render: function(data, type, item) {
                    var porcentaje = parseFloat( (data.Cant_Ejec * 100)/ data.Cantidad);
                      return porcentaje.toFixed(2)+'%';
                      //  <button type="button" title="Imprimir Etiqueta" class="btn-sm btn-warning btn" onclick="imprimir_pedido_pdf('${data.Envio_No}','${data.CodigoP}')"><i class="bx bx-printer m-0"></i></button>
                      // <button type="button" title="Editar Pedido" class="btn-sm btn-primary btn" onclick="editar_pedido('${data.ID}')"><i class="bx bx-pencil m-0"></i></button>
                      // <button type="button" title="Eliminar Pedido" class="btn-sm btn-danger btn" onclick="eliminar_pedido('${data.ID}')"><i class="bx bx-trash m-0"></i></button>`;                    
                    }
              },
              { data: 'Total' },
              { data: 'Costo_Total_Ejec' },
              { data: 'Diferencia' },
              { data: null,
                 render: function(data, type, item) {
                    return ``
                    //  <button type="button" title="Imprimir Etiqueta" class="btn-sm btn-warning btn" onclick="imprimir_pedido_pdf('${data.Envio_No}','${data.CodigoP}')"><i class="bx bx-printer m-0"></i></button>
                    // <button type="button" title="Editar Pedido" class="btn-sm btn-primary btn" onclick="editar_pedido('${data.ID}')"><i class="bx bx-pencil m-0"></i></button>
                    // <button type="button" title="Eliminar Pedido" class="btn-sm btn-danger btn" onclick="eliminar_pedido('${data.ID}')"><i class="bx bx-trash m-0"></i></button>`;                    
                  }
              },
              
          ],
          order: [
              [1, 'asc']
          ]
      });

    $('#ddl_contratista').on('change', function() {

      $('#ddl_Contrato').val(null).trigger('change');
      $('#ddl_Rubro').val(null).trigger('change');
      $('#ddl_meses').val(null).trigger('change');
      $('#ddl_semana').val(null).trigger('change');
              
    });

    $('#ddl_Contrato').on('change', function() {

      $('#ddl_Rubro').val(null).trigger('change');
      $('#ddl_meses').val(null).trigger('change');
      $('#ddl_semana').val(null).trigger('change');
              
    });

    $('#ddl_Rubro').on('change', function() {
      $('#ddl_meses').val(null).trigger('change');
      $('#ddl_semana').val(null).trigger('change');
              
    });

     $('#ddl_meses').on('change', function() {
      $('#ddl_semana').val(null).trigger('change');              
    });




  })


  function contratistas()
  {
    $('#ddl_Contrato').val("");
    $('#ddl_contratista').select2({
      placeholder: 'Seleccione contratista',
      width:'resolve',
      allowClear: true,
      ajax: {
        url:   '../controlador/inventario/orden_ejecucionC.php?contratistasAvances=true',
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
     var contratista = $('#ddl_contratista').val();
    $('#ddl_Contrato').select2({
      placeholder: 'Seleccione contratos',
      width:'resolve',
      allowClear: true,
      ajax: {
        url:   '../controlador/inventario/orden_ejecucionC.php?contratosAvances=true&CotraAvance='+contratista,
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

  function cargar_rubros()
  {
    cargar_detalle_contratos();
    var contratista = $('#ddl_Contrato').val();
    $('#ddl_Rubro').select2({
      placeholder: 'Seleccione rubros',
      width:'resolve',
      allowClear: true,
      ajax: {
        url:   '../controlador/inventario/orden_ejecucionC.php?rubrosAvances=true&CotratoAvance='+contratista,
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

  function cargar_detalle_contratos()
  {
    var parametros = 
     {
        'contrato':$('#ddl_Contrato').val(),
     }
      $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/inventario/orden_ejecucionC.php?cargar_detalle_contratos=true',
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
                $('#lbl_categoria').text(response[0].Proceso)
                // $('#lbl_tipo_costo').text(response[0].tipo_costo)
                // $('#lbl_cuenta_contable').text(response[0].cuenta_contable)
                $('#lbl_fecha').text(formatoDate(response[0].Fecha.date))
                $('#lbl_fecha_v').text(formatoDate(response[0].Fecha_V.date))
                $('#txt_cuenta_proyecto').val(response[0].ProyectoID);

        }
      });
  }

  function cargar_meses()
  {
    var contratista = $('#ddl_Rubro').val();
    $('#ddl_meses').select2({
      placeholder: 'Seleccione rubros',
      width:'resolve',
      allowClear: true,
      ajax: {
        url:   '../controlador/inventario/orden_ejecucionC.php?mesesAvances=true&CtaRubroAvance='+contratista,
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

  function cargar_semanas()
  {
     
  }


  function cargar_lista_subrubros()
  {
     tbl_procesados_all.ajax.reload(null, false);
  }
