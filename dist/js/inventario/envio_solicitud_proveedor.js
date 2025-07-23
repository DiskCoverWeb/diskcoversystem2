

  function pedidos_solicitados(orden)
  {
    var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?pedido_solicitados_proveedor=true',
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

  function lineas_pedido_solicitados_proveedor(orden)
  {     
      var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?lineas_pedido_solicitados_proveedor=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {           
             $('#tbl_body').html(response);     

                 $('.select2_prove').select2({
                      placeholder: 'Seleccione',
                      width:'200px',
                      ajax: {
                          url:   '../controlador/inventario/solicitud_materialC.php?lista_proveedores=true',
                          dataType: 'json',
                          delay: 250,
                          processResults: function (data) {
                            // console.log(data);
                            return {
                              results: data
                          };
                      },
                      cache: true
                    }
                  });

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
               linea_pedido();
            }
          
          }
      });
  }

  function grabar_envio_solicitud()
  {

    var selects = document.querySelectorAll('#form_lineas select');
    var todosSeleccionados = true;

    // Iterar sobre cada select y verificar si está seleccionado
    selects.forEach(function(select) {
        if (select.value === '') {
            todosSeleccionados = false;
           
        } 
      });

    if(todosSeleccionados==false)
    {
      Swal.fire("Seleccione los proveedores para todas las lineas","","info")
      return false;
    }


     $('#myModal_espera').modal('show');
      form = $('#form_lineas').serialize();
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?grabar_envio_solicitud=true',
          type:  'post',
          data: form,
          dataType: 'json',
          success:  function (response) {
            $('#myModal_espera').modal('hide');
            if(response==1)
            {
               Swal.fire("Registro guardado","","success").then(function(){
                location.reload();
               });
            }
          
          },
          error: function (error) {
            $('#myModal_espera').modal('hide');
            console.error('Error en numero_comprobante:', error);
            // Puedes manejar el error aquí si es necesario
          },
      });
  }
  function imprimir_pdf()
  {
    window.open('../controlador/inventario/solicitud_materialC.php?imprimir_pdf_envio=true&orden_pdf='+orden,'_blank');
  }
  function imprimir_excel()
  {
     window.open('../controlador/inventario/solicitud_materialC.php?imprimir_excel_envio=true&orden_pdf='+orden,'_blank');
  }

  function lineaSolProv(linea)
  {
    $('#txt_linea_Select').val(linea);
  }

  function usar_cliente(nombre, ruc, codigocliente, email, T,grupo)
  {
    linea = $('#txt_linea_Select').val();
    $('#ddl_selector_'+linea).append($('<option>',{value:  codigocliente, text: nombre,selected: true }));
    $('#myModal').modal('hide');
  }
