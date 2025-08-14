var tbl_pedidos_all;
$(document).ready(function () {

    tbl_pedidos_all = $('#tbl_listado').DataTable({
      
          responsive: false,
          autoWidth: false,   
          // responsive: true,
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
              url:   '../controlador/inventario/solicitud_material_bodegaC.php?pedidos_contratistaCheck=true',
              type: 'POST',  // Cambia el método a POST    
              data: function(d) {
                 var parametros = 
                  {
                    'fecha': $('#txt_fecha').val(),
                    'query': $('#txt_query').val(),
                  }
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
              { data: null,
                 render: function(data, type, item) {
                    return `<a href="../vista/inicio.php?mod=03&acc=DetalleSolicitudesBodegaCheck&orden=${data.Orden_No}">${data.Cliente}'</a>`;                    
                  }
              },              
              { data: 'Orden_No' },
              { data: 'Fecha.date',  
                  render: function(data, type, item) {
                      return data ? new Date(data).toLocaleDateString() : '';
                  }
              },              
             
              { data: null,
                 render: function(data, type, item) {
                  if(data.TC=='.')
                  {
                     return `<div class="badge rounded-pill bg-danger text-white w-100">Para Revision</div>`;                    
                  

                  }else{
                     return `<div class="badge rounded-pill bg-warning text-white w-100">Para Checking</div>`;                    
                  
                  }
                }
              },
              
          ],
          order: [
              [1, 'asc']
          ]
      });

})
	
function pedidos_contratista()
{     

     tbl_pedidos_all.ajax.reload(null, false);

  // var parametros = 
  // {
  //   'fecha': $('#txt_fecha').val(),
  //   'query': $('#txt_query').val(),
  // }
  // $.ajax({
  //     url:   '../controlador/inventario/solicitud_material_bodegaC.php?pedidos_contratistaCheck=true',
  //     type:  'post',
  //     data: {parametros:parametros},
  //     dataType: 'json',
  //     success:  function (response) {           
  //        $('#tbl_body').html(response);                     
  //     }
  // });
}

function imprimir_pdf(orden)
{
	window.open('../controlador/inventario/solicitud_materialC.php?imprimir_pdf=true&orden_pdf='+orden,'_blank');
}
function imprimir_excel(orden)
{
	window.open('../controlador/inventario/solicitud_materialC.php?imprimir_excel=true&orden_pdf='+orden,'_blank');
}

function eliminar_solicitud(orden)
{
	  Swal.fire({
       title: 'Esta seguro?',
       text: "Esta usted seguro de eliminar el pedido!",
       type: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Si!'
     }).then((result) => {
       if (result.value==true) {
        EliminarSolicitud(orden);
       }
     })
}

function EliminarSolicitud(orden)
{
	var parametros = 
  {
    'orden': orden,
  }
  $.ajax({
      url:   '../controlador/inventario/solicitud_materialC.php?EliminarSolicitud=true',
      type:  'post',
      data: {parametros:parametros},
      dataType: 'json',
      success:  function (response) {       
      if(response==1)
      {
      	 Swal.fire("Solicitud eliminada","Solicitud eliminada","success").then(function(){
      	 	location.reload();
      	 })
      }                   
      }
  });

}
