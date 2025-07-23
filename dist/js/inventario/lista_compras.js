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
      url:   '../controlador/inventario/lista_comprasC.php?pedidos_compra_contratista=true',
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
	window.open('../controlador/inventario/lista_comprasC.php?imprimir_pdf=true&orden_pdf='+orden,'_blank');
}
function imprimir_excel(orden)
{
	window.open('../controlador/inventario/lista_comprasC.php?imprimir_excel=true&orden_pdf='+orden,'_blank');
}
