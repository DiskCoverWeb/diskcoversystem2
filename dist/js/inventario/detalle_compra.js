
  function pedidos_compra_solicitados(orden)
  {
    var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/lista_comprasC.php?pedidos_compra_solicitados=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {   

            $('#lbl_orden').text(response[0].Orden_No);   
            $('#lbl_contratista').text(response[0].Cliente);   
            $('#lbl_total').text(response[0].Total);   

          // $('#').text(response.)   
          // console.log(response);                  
          }
      });


  }  

  function lineas_compras_solicitados(orden)
  {     
      var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/lista_comprasC.php?lineas_compras_solicitados=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {           
             $('#tbl_body').html(response);                     
          }
      });
  }


 
function imprimir_pdf()
{ 
  var orden_p = orden;
  window.open('../controlador/inventario/lista_comprasC.php?imprimir_pdf=true&orden_pdf='+orden_p,'_blank');
}

function imprimir_excel()
{ 
  var orden_p = orden;
  window.open('../controlador/inventario/lista_comprasC.php?imprimir_excel=true&orden_pdf='+orden_p,'_blank');
}

function grabar_kardex()
{
  var orden_p = orden;
  var parametros = 
      {
        'orden':orden_p,
        'T_No' : '101',
      }
    //  $('#myModal_espera').modal('show');
      $.ajax({
          url:   '../controlador/inventario/lista_comprasC.php?grabar_kardex=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {   
            $('#myModal_espera').modal('hide');
            if(response.resp==1)
            {
              Swal.fire('Comprobantes '+response.com+' Generado',"","success").then(function(){
                 window.open('../controlador/contabilidad/comproC.php?reporte&comprobante='+response.com+'&TP=CD','_blank')
            
                location.href = 'inicio.php?mod=03&acc=lista_compras';
              })
            }  
                              
          }
      });
}

function comprobante_individual(orden,proveedor)
{
    $('#myModal_espera').modal('show');
    var parametros = 
      {
        'orden':orden,
        'proveedor':proveedor,
        'T_No':'101',
      }
     $.ajax({
          url:   '../controlador/inventario/lista_comprasC.php?grabar_kardex_indi=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {   
            $('#myModal_espera').modal('hide');
            if(response.resp==1)
            {
              Swal.fire('Comprobantes '+response.com+' Generado',"","success").then(function(){
                  window.open('../controlador/contabilidad/comproC.php?reporte&comprobante='+response.com+'&TP=CD','_blank')
            
                location.href = 'inicio.php?mod=03&acc=lista_compras';
              })
            }  
                              
          },
          error: function (error) {
            $('#myModal_espera').modal('hide');
            // Puedes manejar el error aquí si es necesario
          },
      });
}


