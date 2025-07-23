$(document).ready(function () {
    pedidos_contratista();

})
  
function pedidos_contratista()
{     
  var parametros = 
  {
    'fecha': $('#txt_fecha').val(),
    'query': $('#txt_query').val(),
  }
  $.ajax({
      url:   '../controlador/inventario/solicitud_materialC.php?lista_pedido_aprobacion_solicitados_proveedor=true',
      type:  'post',
      data: {parametros:parametros},
      dataType: 'json',
      success:  function (response) {           
         $('#tbl_body').html(response);                     
      }
  });
}

function imprimir_pdf(orden)
{ 
  window.open('../controlador/inventario/solicitud_materialC.php?imprimir_pdf_proveedor=true&orden_pdf='+orden,'_blank');
}
function imprimir_excel(orden)
{
  window.open('../controlador/inventario/solicitud_materialC.php?imprimir_excel_proveedor=true&orden_pdf='+orden,'_blank');
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
    'tipo':'T',
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
