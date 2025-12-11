  $(document).ready(function () {

      tbl_pedidos_all = $('#tbl_lista_solicitud').DataTable({
          // responsive: true,
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
              url:   '../controlador/inventario/orden_trabajo_constC.php?cargar_lista=true',
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
              { data: 'Codigo' },
              { data: 'Comp_No' },
              { data: 'No_Contrato' },
              { data: 'Proceso' },
              { data: 'Grupo' },
              { data: 'Cuenta' },
              { data: 'Rubro' },
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
              { data: 'Categoria_Contrato'},
              { data: 'Detalle_SubCta' },
              { data: 'UnidadMed' },
              { data: 'Cantidad' },
              { data: 'CantidadOrd' },
              { data: 'Diferencia' },
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

function contratistas()
{
	$('#ddl_contratista').select2({
    placeholder: 'Seleccione contratista',
    width:'resolve',
    ajax: {
	    url:   '../controlador/inventario/reporte_constructora_Compras.php?contratistas=true',
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

function ddl_Proceso(){

     $('#ddl_Proceso').select2({
      placeholder: 'Centro costo',
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


function ddl_Grupo(){

     $('#ddl_Grupo').select2({
      placeholder: 'Centro costo',
      ajax: {
        url: '../controlador/inventario/orden_trabajo_constC.php?ddl_Grupo=true',
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

function agregar_tabla()
{
  var orden = ordenNo; 
  var data = $('#form_datos').serialize();
  $.ajax({
    url: '../controlador/inventario/orden_trabajo_constC.php?agregar_tabla=true',
    type:'post',
    dataType:'json',
    data:{parametros:data,orden:orden},     
    success: function(response){
      if(response.resp==1)
      {
        Swal.fire("Agregado","","success").then(function(){
          if(orden==-1)
          {
              var url = new URL(window.location.href);
              var params = new URLSearchParams(url.search);
              params.set('ordenNo',response.orden);
              window.location.href = url.pathname + '?' + params.toString();

           }else
           {
            cargar_lista();
           }

        })
      }else
      {
        Swal.fire("Algo salio mal","","error")
      }
    // console.log(response);
    }
  });
}

function cargar_lista()
{
  tbl_pedidos_all.ajax.reload(null, false);
}

function eliminar_pedido(id)
{

     Swal.fire({
     title: 'Esta seguro?',
     text: "Esta usted seguro de que quiere eliminar este registro!",
     icon: 'warning',
     showCancelButton: true,
     confirmButtonColor: '#3085d6',
     cancelButtonColor: '#d33',
     confirmButtonText: 'Si!'
   }).then((result) => {
     if (result.value==true) {
      eliminar(id);
     }
   })

}

function eliminar(ID)
{   
      $.ajax({
        type: "POST",
        url: '../controlador/inventario/orden_trabajo_constC.php?eliminar_linea=true',
        data:{ID:ID},
        dataType:'json',
        success: function(data)
        {
            if(data ==1)
            {
                Swal.fire('Registro eliminado','','info');
              cargar_lista();
            }     
        }
    });   
}



