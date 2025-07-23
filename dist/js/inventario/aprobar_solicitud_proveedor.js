
  function pedidos_solicitados(orden)
  {
    var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?pedido_aprobacion_solicitados_proveedor=true',
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

  // function pedidos_solicitados()
  // {
  //   $('#ddl_pedidos').select2({
  //       placeholder: 'Seleccione',
  //       width:'100%',
  //       ajax: {
  //           url:   '../controlador/inventario/solicitud_materialC.php?pedido_aprobacion_solicitados_proveedor=true',
  //           dataType: 'json',
  //           delay: 250,
  //           processResults: function (data) {
  //             // console.log(data);
  //             return {
  //               results: data
  //           };
  //       },
  //       cache: true
  //     }
  //   });
  // } 

  // function llenarProveedores(selector)
  // {
  //    $('#'+selector).select2({
  //       placeholder: 'Seleccione',
  //       width:'100%',
  //       ajax: {
  //           url:   '../controlador/inventario/solicitud_materialC.php?lista_proveedores=true',
  //           dataType: 'json',
  //           delay: 250,
  //           processResults: function (data) {
  //             // console.log(data);
  //             return {
  //               results: data
  //           };
  //       },
  //       cache: true
  //     }
  //   });

  // } 

  function lineas_pedido_aprobacion_solicitados_proveedor(orden)
  {     
      var parametros = 
      {
        'orden':orden,
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?lineas_pedido_aprobacion_solicitados_proveedor=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {           
             $('#tbl_body').html(response);     

                 $('.select2_prove').select2({
                      placeholder: 'Seleccione',
                      width:'100%',
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

  function grabar_compra_pedido()
  {
    parametros = 
    {
      'orden':$('#lbl_orden').text()
    }

      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?grabar_compra_pedido=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            $('#myModal_espera').modal('hide');
            if(response==1)
            {
               Swal.fire("Registro guardado","","success").then(function(){
                location.reload();
               });
            }
             if(response==-2)
            {
               Swal.fire("Seleccione los proveedores de todos los articulos","","error")
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
    var orden = '<?php echo $orden; ?>';
    window.open('../controlador/inventario/solicitud_materialC.php?imprimir_pdf_proveedor=true&orden_pdf='+orden,'_blank');
  }
  function imprimir_excel()
  {

    var orden = '<?php echo $orden; ?>';
    window.open('../controlador/inventario/solicitud_materialC.php?imprimir_excel_proveedor=true&orden_pdf='+orden,'_blank');
  }

  function mostrar_proveedor(id,codigo,orden,cantidad)
  {
    $('#myModal_provedor').modal('show');
    $('#txt_id_linea').val(id);
    parametros = 
    {
      'orden':orden,
      'codigo':codigo,
      'cantidad':cantidad,
      'id':id,
    }
     $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?lista_provee=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
           // $('#ddl_proveedores_list').empty();

            $('#tbl_body_prov').html(response.option);
            $('#txt_id_prove').val(response.idProve);
          //  $('#txt_costoAnt').val(response.CostoTotal);
           
          
          },
          error: function (error) {
            $('#myModal_espera').modal('hide');
            console.error('Error en numero_comprobante:', error);
            // Puedes manejar el error aquí si es necesario
          },
      });
  }

  function guardar_seleccion_proveedor(codigo,orden)
  {
    // costo = $('#txt_costoAct').val()
    var ord = '<?php echo $orden; ?>';
    // if(costo=='')
    // {

    //     Swal.fire("El costo no puede estar vacio","","info")
    //   return false;
    // }
    var total = $('#lbl_total_linea').text();
    data = $('#form_proveedor_seleccionado').serialize();
    data = data+'&total='+total;


   
     $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?guardar_seleccion_proveedor=true',
          type:  'post',
          data: data,
          dataType: 'json',
          success:  function (response) {
              if(response==1)
              {
                Swal.fire("Proveedor Asignado","","success")
                $('#myModal_provedor').modal('hide');               
                lineas_pedido_aprobacion_solicitados_proveedor(ord)
              }
              if(response==-2)
              {
                Swal.fire("La cantidad no concide con el total","","info");
              }
               if(response==-3)
              {
                Swal.fire("El costo no debe ser cero o vacio","","info");
              }

          },
          error: function (error) {
            $('#myModal_espera').modal('hide');
            console.error('Error en numero_comprobante:', error);
            // Puedes manejar el error aquí si es necesario
          },
      });
  }

  function lineaSolProv(linea)
  {
    $('#txt_linea_Select').val(linea);
  }

  function usar_cliente(nombre, ruc, codigocliente, email, T,grupo)
  {
    linea = $('#txt_linea_Select').val();
    parametros = 
    {
      'linea':linea,
      'proveedor':codigocliente,
    }
     $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?AddProveedorExta=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
              Swal.fire("Proveedor asignado","","success");
               $('#ddl_selector_'+linea).append($('<option>',{value:  codigocliente, text: nombre,selected: true }));
              $('#myModal').modal('hide');
            }else
            {              
              Swal.fire("el proveedor ya esta asignado a este producto","","info");
            }
          
          
          },
          error: function (error) {
            $('#myModal_espera').modal('hide');
            console.error('Error en numero_comprobante:', error);
            // Puedes manejar el error aquí si es necesario
          },
      });   
  }

  function eliminar_seleccion(id)
  {
    Swal.fire({
        title: 'Esta seguro?',
        text: "Esta usted seguro de que quiere eliminar el proveedor!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
     }).then((result) => {
          if (result.value==true) {
              eliminar_prove(id);
          }
    })
  }

  function eliminar_prove(id)
  {
     var orden = '<?php echo $orden; ?>';
    var parametros = 
      {
        'id': id,
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?eliminar_prove=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
               Swal.fire("Proveedor eliminado","","success");
               lineas_pedido_aprobacion_solicitados_proveedor(orden)
            }
          
          }
      });
  }
