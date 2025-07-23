
  $(document).ready(function () {
    productos();
    // familias();
    // $('#ddl_productos').select2();
    // $('#ddl_marca').select2();
     marcas()
    linea_pedido();

     $('#ddl_productos').on('select2:select', function (e) {
      var data = e.params.data.data;
      $('#txt_costo').val(data.Costo);
      $('#txt_stock').val(data.Existencia);
      $('#txt_uni').val(data.Unidad);
      $('#ddl_familia').text('Familia: '+data.familia);
      $('#ddl_idfamilia').text(data.codfamilia);
      console.log(data);
    });


  })

  function marcas()
  {
    $('#ddl_marca').select2({
        placeholder: 'Seleccione',
        width:'100%',
        ajax: {
            url:   '../controlador/inventario/solicitud_materialC.php?marca=true',
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
  }
  

  function productos()
  {
    $('#ddl_productos').select2({
        placeholder: 'Seleccione',
        width:'100%',
        ajax: {
            url:   '../controlador/inventario/solicitud_materialC.php?productos=true',
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
  }

  // function familias()
  // {
  //   $('#ddl_familia').select2({
  //       placeholder: 'Seleccione',
  //       width:'100%',
  //       ajax: {
  //           url:   '../controlador/inventario/solicitud_materialC.php?familia=true',
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

  function guardar_linea()
  {
     cant = $('#txt_cantidad').val();
     prod = $('#ddl_productos').val();
     costo = $('#txt_costo').val();

     if(prod=='')
     {
       Swal.fire("Seleccione un producto",'','info');
       return false;      
     }
      if(cant<=0 || cant =='')
     {
       Swal.fire("Cantidad no valida",'','info');
       return false;
     }
     //  if(costo==0 || costo =='')
     // {
     //   Swal.fire("Costo no valida",'','info');
     //   return false;
     // }
     var parametros = 
     {
        'cantidad':cant,
        'productos':prod,
        'familia':$('#ddl_idfamilia').val(),
        'marca':$('#ddl_marca').val(),
        'fecha':$('#txt_fecha').val(),
        'fechaEnt':$('#txt_fechaEnt').val(),
        'costo':$('#txt_costo').val(),
        'total':$('#txt_total').val(),
        'obs':$('#txt_observacion').val(),
        'stock':$('#txt_stock').val(),
     }

      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?guardar_linea=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
               Swal.fire("Agregado","","success");
               linea_pedido();
               limpiar();
            }
          
          }
      });
  }

  function limpiar()
  {
    $('#txt_cantidad').val(0);
    $('#txt_costo').val(0);
    $('#txt_stock').val(0);
    $('#txt_uni').val(0);
    $('#txt_total').val(0);    
    $('#ddl_productos').empty();
  }

  function linea_pedido()
  {     
      var parametros = 
      {
        'fecha': $('#txt_fecha').val(),
      }
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?linea_pedido=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {           
             $('#tbl_body').html(response);                     
          }
      });
  }


  function eliminar_linea(id)
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

  
  function grabar_solicitud()
  {   
      $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?grabar_solicitud=true',
          type:  'post',
          // data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
               Swal.fire("Registro Guardado","","success").then(function(){
                location.reload();
               });
               // linea_pedido();
            }
          
          }
      });
  }

  function calcular()
  {
    var costo = $('#txt_costo').val();
    var cantidad = $('#txt_cantidad').val();
    if(costo=='')
    {
      $('#txt_costo').val(0);
    }
    if(cantidad=='')
    {
      $('#txt_cantidad').val(0);
    }

    var total = parseFloat(costo*cantidad);
    $('#txt_total').val(total.toFixed(2))
  }

  function imprimir_excel(orden)
  {
    window.open('../controlador/inventario/solicitud_materialC.php?imprimir_excel=true&orden_pdf='+orden,'_blank');
  }

  function modal_marcas()
  {
    $('#myModal_marcas').modal('show');
  }

  function guardar_marca()
  {
    parametros = 
    {
      'marca':$('#txt_new_marca').val(),
    }
     $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?guardar_marca=true',
          type:  'post',
          data: {parametros:parametros},
          dataType: 'json',
          success:  function (response) {
            if(response==1)
            {
               Swal.fire("Registro Guardado","","success");
               $('#txt_new_marca').val('');
               $('#myModal_marcas').modal('hide');
               // linea_pedido();
            }
          
          }
      });
  }

  function buscar_modal()
  {
    $('#myModal_buscar').modal('show');
   // bucar_producto_modal();
    
  }

  function bucar_producto_modal()
  {
    $('#myModal_espera').modal('show');

    query = $('#txt_search_producto').val();
     $.ajax({
          url:   '../controlador/inventario/solicitud_materialC.php?productos=true&q='+query,
          type:  'post',
          // data: {parametros:parametros},
          dataType: 'json',          
          success:  function (response) {
            $('#myModal_espera').modal('hide');
            console.log(response);
            var tr = '';
            response.forEach(function(item,i){

               tr+=`<tr><td>`+item.id+`</td><td>`+item.text+`</td><td>`+item.data.Costo+`</td><td>
                        <button class=" btn-sm btn btn-primary" onclick="usar_producto('`+item.id+`','`+item.text+`','`+item.data.Costo+`','`+item.data.familia+`','`+item.data.Existencia+`','`+item.data.codfamilia+`','`+item.data.Unidad+`')"><i class="bx bx-box"></i>Usar</button></td></tr>`;
            })

            $('#tbl_producto_search').html(tr);
          
          }
      });
  }

  function usar_producto(cod,prod,cost,fami,stock,codfam,uni)
  {    
    $('#ddl_productos').empty();
    $('#myModal_buscar').modal('hide');
    $('#txt_costo').val(cost)
    $('#ddl_familia').text(fami)
    $('#ddl_idfamilia').val(codfam)
    $('#txt_stock').val(stock)
    $('#txt_uni').val(uni)

    $('#ddl_productos').append($('<option>',{value:  cod, text: prod,selected: true }));
  }


 