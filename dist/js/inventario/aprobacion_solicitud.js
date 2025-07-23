
  function pedidos_solicitados(orden)
  {
    var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?pedidos_solicitados=true',
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

  function lineas_pedido_solicitados(orden)
  {     
      var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?lineas_pedido_solicitados=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {           
             $('#tbl_body').html(response);   
              $('#tbl_lista_solicitud').DataTable({
              scrollX: true,
              scrollCollapse: true, 
              searching: false,
              responsive: false,
              paging: false,   
              info: false,   
              autoWidth: false,  
              order: [[1, 'asc']], // Ordenar por la segunda columna
              language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
              },
              initComplete: function() {
                  // Ajustar columnas después de la inicialización
                  this.api().columns.adjust().draw();
              }
            });                  
          }
      });
  }


  function eliminar_linea(id)
  {
      Swal.fire({
        title: 'Esta seguro?',
        text: "Esta usted seguro de que quiere borrar este registro!",
        type: 'warning',
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

  function eliminar(id)
  {     
      var parametros = 
      {
        'id': id,
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?eliminar_linea=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
               Swal.fire("Registro eliminado","","success");
                pedidos_solicitados(orden);
                lineas_pedido_solicitados(orden);
            }
          
          }
      });
  }

  function grabar_solicitud_proveedor()
  {   

     data = $('#form_aprobacion').serialize();
    // if($('#ddl_pedidos').val()=='')
    // {
    //   Swal.fire("Seleccione un pedido","","info")
    //   return false;
    // }

     // console.log(data);
     // return false;
    var parametros = 
    {
      'pedido':$('#lbl_orden').text(),
      'aprobacion':data,
    }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?grabar_solicitud_proveedor=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
              location.reload();
            }
          
          }
      });
  }

  function guardar_linea_aprobacion(id)
  {

     
    can = $('#txt_cant_'+id).val();
    if(parseFloat(can)<0)
    {
      Swal.fire("","La cantidad invalida","error");
      return false;
    }
    var parametros = 
    {
      'id_linea':id,
      'cantida':$('#txt_cant_'+id).val(),
    }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?guardar_linea_aprobacion=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
                Swal.fire("Linea Editada","","success")
                pedidos_solicitados(orden);
                lineas_pedido_solicitados(orden);
            }
          
          }
      });
  }
function imprimir_pdf()
{ 
  window.open('../controlador/inventario/solicitud_materialC.php?imprimir_pdf=true&orden_pdf='+orden,'_blank');
}

function imprimir_excel()
{ 
  window.open('../controlador/inventario/solicitud_materialC.php?imprimir_excel=true&orden_pdf='+orden,'_blank');
}

