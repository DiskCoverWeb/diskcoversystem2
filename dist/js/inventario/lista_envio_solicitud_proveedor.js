$(document).ready(function () {
    pedidos_contratista();

})
	
function pedidos_contratista()
{     
  var parametros = 
  {
    'fecha': $('#txt_fecha').val(),
    'query':$('#txt_query').val(),
  }
  $.ajax({
      url:   '../controlador/inventario/solicitud_materialC.php?envio_pedidos_contratista=true',
      type:  'post',
      data: {parametros:parametros},
      dataType: 'json',
      success:  function (response) {           
         $('#tbl_body').html(response);                    
          // $('#tbl_lista_solicitud').DataTable({
          //     scrollX: true,
          //     scrollCollapse: true, 
          //     searching: false,
          //     responsive: false,
          //     paging: false,   
          //     info: false,   
          //     autoWidth: false,  
          //     order: [[1, 'asc']], // Ordenar por la segunda columna
          //     language: {
          //     url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          //     },
          //     initComplete: function() {
          //         // Ajustar columnas después de la inicialización
          //         this.api().columns.adjust().draw();
          //     }
          //   }); 
      }
  });
}

function imprimir_pdf(orden)
{
	window.open('../controlador/inventario/solicitud_materialC.php?imprimir_pdf_envio=true&orden_pdf='+orden,'_blank');
}
function imprimir_excel(orden)
{
	window.open('../controlador/inventario/solicitud_materialC.php?imprimir_excel_envio=true&orden_pdf='+orden,'_blank');
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
    'tipo':'E',
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