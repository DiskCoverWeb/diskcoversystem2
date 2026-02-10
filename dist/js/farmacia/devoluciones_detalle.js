
  function cargar_pedido()
  {   
    $('#modal_espera').modal('show');
    var comprobante = cod;  
    var query = $('#txt_query').val();    
     // console.log(parametros);
     $.ajax({
      data:  {comprobante:comprobante,query:query},
      url:   '../controlador/farmacia/devoluciones_insumosC.php?datos_comprobante=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 

    $('#modal_espera').modal('hide');
      	console.log(response);
        if(response)
        {
          $('#tbl_body').html(response.tabla);
          $('#paciente').val(response.cliente[0].Cliente);
          $('#cod').val(response.cliente[0].CodigoL);
          $('#detalle').text(response.cliente[0].Concepto);
          $('#fecha').text(response.cliente[0].Fecha.date);
          $('#comp').val(response.cliente[0].Numero);
          num_li = response.lineas;
        }
      }
    });
  }

function lista_devolucion()
  {   
    var comprobante = cod;  
     // console.log(parametros);
     $.ajax({
      data:  {comprobante:comprobante},
      url:   '../controlador/farmacia/devoluciones_insumosC.php?lista_devolucion=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response)
        var tr = "";
        response.tr.data.forEach(function(item,i){
          tr+=`<tr>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="Eliminar('`+comprobante+`','`+item.CODIGO_PRODUCTO+`','`+item.A_No+`')" title="Eliminar"><i class="fa fa-trash"></i></button></td>
            <td>`+item.CODIGO_PRODUCTO+`</td>
            <td>`+item.PRODUCTO+`</td>
            <td>`+item.CANTIDAD+`</td>
            <td>`+item.VALOR_UNITARIO+`</td>
            <td>`+item.VALOR_TOTAL+`</td>
            <td>`+item.FECHA.date+`</td>
            <td>`+item.A_No+`</td>
          </tr>`;

        })
        $('#tbl_devoluciones').html(tr);
        $('#lineas').val(response.lineas)
      }
    });
  }




  function costo(codigo,id)
  {    
     $.ajax({
      data:  {codigo:codigo},
      url:   '../controlador/farmacia/devoluciones_insumosC.php?costo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        $('#txt_valor_'+id).val(response[0].Costo.toFixed(2));
        var Costo = response[0].Costo;
        var devolucion = $('#txt_cant_dev_'+id).val();
        var tot = Costo*devolucion;
        $('#txt_gran_t_'+id).val(tot.toFixed(2));
         var total =0; 
         for (var i =1 ; i < num_li+1; i++){
            total+=parseFloat($('#txt_gran_t_'+i).val());       
         }
         $('#txt_tt').text(total.toFixed(2));
      }
    });
  }


  function calcular_dev(id)
  {
    var salida = parseFloat($('#txt_salida_'+id).val());
    var devolucion = parseFloat($('#txt_cant_dev_'+id).val());
    if(devolucion>salida)
    {
      Swal.fire('la devolucion no debe ser mayor a la cantidad de salida','','info');
      $('#txt_cant_dev_'+id).val(salida);
      var codigo = $('#codigo_'+id).text();
      costo(codigo,id);
      return false;
    }
    if(devolucion ==0)
    {
      $('#txt_valor_'+id).val(0); 
      $('#txt_gran_t_'+id).val(0);
      var total =0; 
         for (var i =1 ; i < num_li+1; i++){
            total+=parseFloat($('#txt_gran_t_'+i).val());       
         }
         $('#txt_tt').text(total.toFixed(2));
    }else
    {
       var codigo = $('#codigo_'+id).text();
       costo(codigo,id);
    }
   

  }


   function num_comprobante()
   {
    var fecha = $('#txt_fecha').val();
     $.ajax({
       data:  {fecha:fecha},
      url:   '../controlador/farmacia/articulosC.php?num_com=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        $('#num').text(response);
      }
    });

   }


   function guardar_devolucion(linea,id)
   {
    var parametros = 
    {
      'codigo':$('#codigo_'+id).text(),
      'producto':$('#producto_'+id).text(),
      'cantidad':$('#txt_cant_dev_'+id).val(),
      'precio':$('#txt_valor_'+id).val(),
      'total':$('#txt_gran_t_'+id).val(),
      'comprobante': cod,  
      'linea':linea,
    }
    if( $('#txt_cant_dev_'+id).val() == 0 || $('#txt_valor_'+id).val()==0 || $('#txt_gran_t_'+id).val() ==0)
    {
      // Swal.fire('Asegurese que los totales y la cantidad no sean igual a cero','','info');
      return false;
    }

    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/farmacia/devoluciones_insumosC.php?guardar_devolucion=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
         if(response==1)
         {
          Swal.fire('Agregado a lista de devoluciones','','success');
          lista_devolucion();
          cargar_pedido();
         }
        }
      });

   }

    function Eliminar(comp,codigo)
  {
       Swal.fire({
      title: 'Esta seguro de eliminar este registro?',
      text:  "No se eliminara el registro seleccionado",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
         Eliminar_linea(comp,codigo)
        }
      })
  }

   function Eliminar_linea(comp,codigo)
   {
    var parametros = 
    {
      'codigo':codigo,
      'comprobante': comp,  
    }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/farmacia/devoluciones_insumosC.php?eliminar_linea_dev=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
         if(response==1)
         {
          Swal.fire('Devolucion eliminada','','success');
          lista_devolucion();
          cargar_pedido();
         }
        }
      });

   }

  function generar_factura(numero)
   {
    var prove = $('#cod').val();
    // $('#myModal_espera').modal('show');  
     var parametros = 
     {
      'num_fact':numero,
      'prove':prove,
      'iva_exist':0,
     }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/articulosC.php?generar_factura=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);

       $('#myModal_espera').modal('hide');  
       if(response.resp==1)
        {
          Swal.fire('Comprobante '+response.com+' generado.','','success'); 
          lista_devolucion();
          cargar_pedido();
        }else if(response.resp==-2)
        {
          Swal.fire('Asegurese de tener una cuenta Cta_Iva_Inventario.','','info'); 
        }else if(response.resp==-3)
        {
          Swal.fire('','Esta factura Tiene dos  o mas fechas','info'); 
          lista_devolucion();
          cargar_pedido();
        }
        else
        {
          Swal.fire('','No se pudo generado.','info'); 
        }
      }
    });
     // console.log(datos);
   }
