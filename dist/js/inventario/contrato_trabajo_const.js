  var tbl_pedidos_all;
  $(document).ready(function () {

      tbl_pedidos_all = $('#tbl_lista_solicitud').DataTable({
          // responsive: true,
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
              url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?cargar_lista_contratos=true',
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
                 
                    return `<a href="inicio.php?mod=03&acc=contrato_trabajo_detalle_const&ordenNo=${data.Autorizacion}">${data.Autorizacion}</a>`;                    
                  }
              },
              { data: 'Fecha.date',  
                  render: function(data, type, item) {
                      return data ? new Date(data).toLocaleDateString() : '';
                  }
              },
              { data: 'Fecha_V.date',  
                  render: function(data, type, item) {
                      return data ? new Date(data).toLocaleDateString() : '';
                  }
              },
              { data: null,
                 render: function(data, type, item) {
                    // <button type="button" title="Imprimir Etiqueta" class="btn btn-warning btn-sm p-0 m-0" onclick="imprimir_pedido_pdf()"><i class="bx bx-printer m-0"></i></button>
                    // <button type="button" title="Editar Pedido" class="btn btn-primary btn-sm p-0 m-0" onclick="editar_pedido()"><i class="bx bx-pencil m-0"></i></button>
                 
                    return `
                     <button type="button" title="Eliminar Pedido" class="btn btn-danger btn-sm p-0 m-0" onclick="eliminar_pedido('${data.ID}')"><i class="bx bx-trash m-0"></i></button>`;                    
                  }
              },
              
          ],
          order: [
              [1, 'asc']
          ]
      });
  })

function proyectos()
{
  var pro = $('#txt_proyecto').val();
  $.ajax({
      // data:  {id:id},
      url:   '../controlador/inventario/inventario_onlineC.php?proyecto=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
           llenarComboList(response,'ddl_proyecto');
      }
    });
}


function autocmpletar_cc(id=''){
     var pro = $('#ddl_proyecto').val();   
     $('#ddl_cc_'+id).select2({
      placeholder: 'Centro costo',
      dropdownParent: $('#myModal_proyecto'),
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

function ddl_cate_contrato(){

     $('#ddl_cate_contrato').select2({
      placeholder: 'Centro costo',
      dropdownParent: $('#myModal_proyecto'),
      ajax: {
        url: '../controlador/inventario/orden_trabajo_constC.php?ddl_Proceso=true',
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



function ddl_cuenta_contable(){

     $('#ddl_cuenta_contable').select2({
      placeholder: 'Centro costo',
      dropdownParent: $('#myModal_proyecto'),
      ajax: {
        url: '../controlador/inventario/orden_trabajo_constC.php?ddl_cuenta_contable=true',
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

function agregar_a_orden()
{
   $('#myModal_orden_trabajo').modal('show')
}
function agregar_personal()
{
   $('#myModal_personal').modal('show')
}


function eliminar_pedido(id)
{
   Swal.fire({
      title: 'Esta seguro?',
      text: "Esta usted seguro de que quiere borrar este registro!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si!'
    }).then((result) => {
      if (result.value==true) {
        eliminar_contrato(id)
      }
    })

}

function eliminar_contrato(id)
{
  $.ajax({
    data:  {id:id},
    url:   '../controlador/inventario/contrato_trabajo_detalle_constC.php?eliminar_contrato=true',
    type:  'post',
    dataType: 'json',
    success:  function (response) { 
      if(response)
      {
        Swal.fire("Registro eliminado","","success").then(function(){
          tbl_pedidos_all.ajax.reload(null, false);
        })
      }
    }
  });

}