  const hoy = new Date(); 
  const mes = hoy.getMonth(); 
  
  $(document).ready(function () {
    ddl_indicador_gestion();

       tbl_pedidos_all = $('#tbl_lista').DataTable({
          // responsive: true,
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
              url:   '../controlador/contratos/reporte_GFNC.php?cargar_lista=true',
              type: 'POST',  // Cambia el método a POST    
              data: function(d) {
                  var parametros = {                    
                    indicador:$('#ddl_indicador_gestion').val(),
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
              { data: 'Codigo' },
              { data: 'Descripcion' },
              { data: 'Enero' },
              { data: 'Febrero' },
              { data: 'Marzo' },
              { data: 'Abril' },
              { data: 'Mayo' },
              { data: 'Junio'},
              { data: 'Julio' },
              { data: 'Agosto' },
              { data: 'Septiembre' },
              { data: 'Octubre' },
              { data: 'Noviembre' },
              { data: 'Diciembre' },
              { data: 'Total_Meses' },
              // { data: null,
              //    render: function(data, type, item) {
              //       // <button type="button" title="Imprimir Etiqueta" class="btn btn-warning btn-sm p-0 m-0" onclick="imprimir_pedido_pdf()"><i class="bx bx-printer m-0"></i></button>
              //       // <button type="button" title="Editar Pedido" class="btn btn-primary btn-sm p-0 m-0" onclick="editar_pedido()"><i class="bx bx-pencil m-0"></i></button>
                 
              //       return `
              //        <button type="button" title="Eliminar Pedido" class="btn btn-danger btn-sm p-0 m-0" onclick="eliminar_pedido('${data.ID}')"><i class="bx bx-trash m-0"></i></button>`;                    
              //     }
              // },
              
          ],
          order: [
              [1, 'asc']
          ]
      });

       $('#imprimir_excel').click(function(){             

            var url = '../controlador/contratos/reporte_GFNC.php?imprimir_excel=true&indicador='+$("#ddl_indicador_gestion").val();
            window.open(url, '_blank');
        });

        $('#imprimir_pdf').click(function(){            

            var url = '../controlador/contratos/reporte_GFNC.php?imprimir_pdf=true&indicador='+$("#ddl_indicador_gestion").val();
            window.open(url, '_blank');
        });

  })

    function ddl_indicador_gestion(){
         $('#ddl_indicador_gestion').select2({
          placeholder: 'Centro costo',
          ajax: {
            url: '../controlador/contratos/reporte_GFNC.php?ddl_indicador_gestion=true',
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

function cargar_lista()
{
  tbl_pedidos_all.ajax.reload(null, false);
}

function limpiar()
{
    $('#ddl_indicador_gestion').val(null).trigger('change');
    cargar_lista()
}


