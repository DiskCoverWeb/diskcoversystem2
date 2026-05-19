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
              dataSrc: '',             
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
