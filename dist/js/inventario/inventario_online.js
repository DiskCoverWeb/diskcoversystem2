var f = '';
var nuevaTabla = false;
function verificar_cuenta()
  {
    var mar = $('#txt_CodMar').val(); 
    var pro = $('#txt_proyecto').val(); 

    $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?existe_cuenta=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          console.log(response);
          if(response== -1)
          {
            Swal.fire('','Genere una cuenta llamada "Cta_Desperdicio" para desperdicios.','info');
           window.location = "../vista/inventario.php?mod=03&cuenta=-1";
          }else if(response == -2)
          {           
            window.location = "../vista/inventario.php?mod=03&cuenta=-2";
          }else
          {
            console.log(mar+'-'+pro);
            if(mar=='' && pro == '')
            {
              $('#myModal_proyecto').modal('show');    
            }else
            {
              $("#ddl_proyecto option[value='"+pro+"']").attr("selected",true);
              $("#ddl_marca option[value='"+mar+"']").attr("selected",true);

              console.log($('#ddl_proyecto option:selected').text());

              // $('#ddl_proyecto').val(pro);
              // $('#ddl_marca').val(mar);
              
              $('#lbl_proyecto').text($('#ddl_proyecto option:selected').text());
              $('#lbl_marca').text($('#ddl_marca option:selected').text());
            }
          }     
         
      }
    });
  }


  $(document).ready(function()
  {
     $('.select2').select2();
    marcas();
    proyectos();
    verificar_cuenta();

    $('#contenedor_tabla_csv').hide();

  $('#imprimir_pdf').click(function(){
  var url = '../controlador/inventario/inventario_onlineC.php?reporte_pdf';                
  window.open(url, '_blank');
}); 
  $('#imprimir_excel').click(function(){
  var url = '../controlador/inventario/inventario_onlineC.php?reporte_excel';                
  window.open(url, '_blank');
}); 

    // autocmpletar_rubro();
    autocmpletar_cc();
    cargar_entrega();
    // autocmpletar_rubro_bajas();
    autocmpletar();



  });

  function simularClick(){
    $('#archivoAdjunto').click();
  }

  function subir_archivo(){
    var fileInput = $('#archivoAdjunto')[0];
    var archivo = fileInput.files[0];

    var formData = new FormData();

    if (archivo) {
      formData.append('archivo', archivo, archivo.name);
    }

    var file = $('#archivoAdjunto').val().toLowerCase();
    if(file && (file.indexOf('.csv') == -1 && file.indexOf('.txt') == -1)) {
        swal.fire('Por favor, seleccione un archivo CSV o TXT.','','error')
        $(this).val('');
        return;
    }


    //modal #myModal_espera
    //$('#myModal_espera').modal('show');

    $.ajax({
      url: '../controlador/inventario/inventario_onlineC.php?subir_archivo=true',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        var data = JSON.parse(response);
        $('#myModal_espera').modal('hide');
        if(data.res == 1)
        {
          procesar_archivo(data.nombreArchivo);
          //vaciar el input file
          $('#archivoAdjunto').val('');
          
        }else
        {
          Swal.fire('Error al subir archivo','','error');
          $('#archivoAdjunto').val('');
        }
      }
    });
  }

  function procesar_archivo(nombreArchivo){
    var parametros = {
      'archivo': nombreArchivo,
      'fecha': $('#txt_fecha').val()
    };
    $.ajax({
      url: '../controlador/inventario/inventario_onlineC.php?procesar_archivo=true',
      type: 'POST',
      data: {'parametros': parametros},
      success: function(response) {
        var data = JSON.parse(response);
        if(data.res == 1)
        {
          swal.fire({
            title: 'Archivo procesado con exito',
            type: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok',
          }).then((result) => {
            if (result.value) {
              //nuevaTabla = true;
              eliminar_archivo(nombreArchivo);
            }
          });
        }else
        {
          Swal.fire(response.mensaje,'','error');
        }
      }
    });
  }

  function cargar_tabla(){
    //modal #myModal_espera
    $('#myModal_espera').modal('show');
    $.ajax({
      url: '../controlador/inventario/inventario_onlineC.php?asiento_csv=true',
      type: 'POST',
      dataType: 'json',
      success: function(response) {
        var data = response;
        if(data.res == 1){
          $('#myModal_espera').modal('hide');
          //t-head hide
          $('#contendor-tabla').hide();
          $('#contenedor_tabla_csv').show();
          $('#contenedor_tabla_csv').empty();
          $('#contenedor_tabla_csv').html(data.tbl);
        }
        nuevaTabla = false;
      }
    });
  }

  function eliminar_archivo(nombreArchivo){
    var parametros = {
      'archivo': nombreArchivo
    };  
    //modal #myModal_espera
    $('#myModal_espera').modal('show');
    $.ajax({
      url: '../controlador/inventario/inventario_onlineC.php?eliminar_archivo=true',
      type: 'POST',
      data: {'parametros': parametros},
      success: function(response) {
        $('#myModal_espera').modal('hide');
        var data = JSON.parse(response);
        if(data.res == 1)
        {
          //cargar_tabla();
          cargar_entrega();
          console.log('Archivo eliminado con exito');
        }else
        {
          console.log('Error al eliminar archivo');
        }
      }
    });
  }

   function autocmpletar(){
      var centro = $('#ddl_cc_').val();
      // if(centro==''){Swal.fire('Seleccione Centro de costos','','info'); return false;}
      $('#ddl_productos_').select2({
        placeholder: 'Seleccione una producto',
        ajax: {
          url: '../controlador/inventario/inventario_onlineC.php?producto=true&centro='+centro,
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


  //  function autocmpletar(){
  //     var centro = $('#ddl_cc_').val();
  //     // if(centro==''){Swal.fire('Seleccione Centro de costos','','info'); return false;}
  //     $('#ddl_productos_').select2({
  //       placeholder: 'Seleccione una producto',
  //       ajax: {
  //         url: '../controlador/inventario/inventario_onlineC.php?producto=true&centro='+centro,
  //         dataType: 'json',
  //         delay: 250,
  //         processResults: function (data) {
  //           // console.log(data);
  //           if(length.data==0)
  //           {
  //             return {
  //               results: data
  //             };
  //           }
  //         },
  //         cache: true
  //       }
  //     });
   
  // }
  function autocmpletar_rubro(id=''){
      var pro = $('#ddl_cc_'+id).val();      
      $('#ddl_rubro_'+id).select2({
        placeholder: 'Seleccione rubro',
        ajax: {
          url: '../controlador/inventario/inventario_onlineC.php?rubro=true&pro='+pro,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
    
  }
    function autocmpletar_rubro_bajas(id=''){
       var pro = $('#txt_proyecto').val();         
      $('#ddl_rubro_bajas_'+id).select2({
        placeholder: 'Seleccione Baja por',
        ajax: {
          url: '../controlador/inventario/inventario_onlineC.php?rubro_bajas=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
    
  }
   function autocmpletar_cc(id=''){

       var pro = $('#txt_proyecto').val();   
       $('#ddl_cc_'+id).select2({
        placeholder: 'Centro costo',
        ajax: {
          url: '../controlador/inventario/inventario_onlineC.php?cc=true&pro='+pro,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
  }

  function cargar_datos(ID='')
  {
    if(ID=='')
    {
     var selec = $('#ddl_productos_').val();
     console.log(selec);
     var cod = selec.split(','); 
     console.log(cod);
     $('#txt_codigo_').val(cod[0]);

     costo_existencias(cod[0]);
     // stock_real(cod[0]);
     // costo_venta(cod[0]);
     $('#txt_uni_').val(cod[1]);
     $('#TC').val(cod[3]);   
     validar_presupuesto(cod[0]);
    }else
    {
     var selec = $('#ddl_productos_'+ID).val();
     var cod = selec.split(','); 
     $('#txt_codigo_'+ID).val(cod[0]);
     $('#txt_uni_'+ID).val(cod[1]);
     $('#txt_uni_'+ID).val(cod[2]);
     stock_real(cod[0]);

    }
  }

  function validar_presupuesto(codigo_inv)
  {
    var centro = $('#ddl_cc_').val();
    if(centro==''){ Swal.fire('Seleccione un centro de costos','','info').then(function(){
      $('#ddl_productos_').empty(); return false;
    }) }
    var parametros = 
    {
      'codigo':codigo_inv,
      'centro':centro,
    }
    $.ajax({
     data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?validar_presupuesto=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          console.log(response);
          if(response.length!=0)
          {
            $('#txt_presupuesto').val(response[0].Presupuesto);
            $('#txt_consumido').val(response[0].consu);
            if(parseFloat(response[0].consu) >= parseFloat(response[0].Presupuesto))
            {
               $('#txt_consumido').css('background-color','coral');
            }else
            {

               $('#txt_consumido').css('background-color','greenyellow');
            }
          }else
          {

               $('#txt_consumido').css('background-color','lightgray');
              $('#txt_presupuesto').val(0);
              $('#txt_consumido').val(0);
          }
        }
      });
  }
  function validar_stock()
  {
    var cant = 0;
    if($('#txt_stock').val()=='' || $('#txt_stock').val()==0 )
    {
       Swal.fire(
            'Producto sin stock presupuestado.',
            '',
            'info'
          );
       $('#txt_cant_').val(0);

    }
    //else
    // {
    //   var filas = $('#num_filas').val();
    //   var b = parseFloat($('#txt_bajas_').val());
    //    cant =parseFloat($('#txt_cant_').val())+parseFloat($('#txt_bajas_').val());
    //   if(filas != 0)
    //   {
    //      for (var i = 0; i < filas ; i++) {
    //        if($('#txt_codigo_').val() == $('#txt_codigo_'+i).val())
    //         {              
    //            cant = cant+parseFloat($('#txt_cant_'+i).val())+parseFloat($('#txt_bajas_'+i).val());
    //         }        
    //      }
    //  }
// console.log(cant);
      if(parseFloat($('#txt_stock').val()) >= cant)
      {

        var val = $('#valor_total').val();
        var can = $('#txt_cant_').val();
       $('#valor_total_linea').val((can*val).toFixed(2));

      }else
      {
        var val = $('#valor_total').val();
         Swal.fire(
            'La cantidad elegida supera al Stock de '+$('#txt_stock').val()+'.',
            '',
            'info'
          );

       //   if(b == 0)
       //   {
       //    $('#txt_cant_').val(0);
       //   }else
       //   {
       //     $('#txt_bajas_').val(0);
       //   }
       // $('#valor_total_linea').val(0*val);

      }
  }
   function validar_stock2(id)
  {
    if($('#txt_stock_'+id).val()=='')
    {
       Swal.fire(
            '',
            'Seleccione un producto.',
            'info'
          );
       $('#txt_cant_'+id).val(0);

    }else
    {
      var filas = $('#num_filas').val();
      var cant = $('#txt_cant_'+id).val();
      if(filas != 0)
      {
         for (var i = 0; i < filas ; i++) {
           if($('#txt_codigo_'+id).val() == $('#txt_codigo_'+i).val())
            {              
               cant = cant + $('#txt_cant_'+i).val();
            }        
         }
     }

      if($('#txt_stock_'+id).val() >= cant)
      {

        var val = $('#valor_total').val();
        var can = $('#txt_cant_').val();
       $('#valor_total_linea').val(can*val);

      }else
      {
         Swal.fire(
            '',
            'La cantidad elegida supera al Stock de '+$('#txt_stock_'+id).val()+'.',
            'info'
          );

       $('#txt_cant_'+id).val(0);       
        var val = $('#valor_total').val();
        var can = $('#txt_cant_').val();
       $('#valor_total_linea').val(can*val);

      }
    }
  }
  function cargar_entrega()
    {
      var lineas = '';
      
      $.ajax({
        // data:  {parametros:parametros},
        url:   '../controlador/inventario/inventario_onlineC.php?entrega=true',
        type:  'post',
        dataType: 'json',
        // beforeSend: function () { 
        //   $('#contenido_entrega').html('<img src="../../img/gif/loader4.1.gif" width="50%">');
        // },
        success:  function (response) { 
          console.log(response);
          if(response)
           {
            $('#num_filas').val(response.length);
            $.each(response,function(i,item){

              console.log(item);
              lineas+='<tr>'+
              '<td style="display:none"><input type="text" id="txt_pos_'+i+'" value="'+item.A_No+'"></td>'+
              '<td id="fecha_'+i+'">'+item.Fecha_Fab.date.substr(0,10)+'</td>'+
              '<td id="txt_codigo_'+i+'" >'+item.CODIGO_INV+'</td>'+
              '<td>'+item.PRODUCTO+'</td>'+
              '<td>'+item.UNIDAD+'</td>'+
              '<td>'+item.CANT_ES+'</td>'+
              '<td>'+item.Cuenta+'</td>'+
              '<td>'+item.Detalle+'</td>'+
              '<td><button onclick="eliminar(\''+i+'\')" class="btn btn-danger btn-sm" title="Eliminar"><i class="bx bx-trash me-0"></i></button></td>'+
              '</tr>';
              // console.log(item.Fecha_Fab.date);
                // lineas +='<div class="row"><div class="col-sm-1" style="padding-right: 6px;"><b>Fecha</b><br><label id="fecha_'+i+'">'+item.Fecha_Fab.date.substr(0,10)+'<label/></div><div class="col-sm-1" style=" padding-left: 0px;  padding-right: 0px;"><b>Codigo</b><input type="hidden" id="txt_id_pro_'+i+'" value="'+item.CODIGO_INV+'"><input type="hidden" id="txt_pos_'+i+'" value="'+item.A_No+'"><input type="text" name="" id="txt_codigo_'+i+'" class="form-control " value="'+item.CODIGO_INV+'"></div><div class="col-sm-3"  style=" padding-left: 2px;  padding-right: 0px;"><b>Descripcion</b><select class="form-control select2" id="ddl_productos_'+i+'" name="ddl_productos_'+i+'" onchange="cargar_datos(\''+i+'\')" onfocus="cargar(\''+i+'\')"><option>Seleccione producto</option></select><input type="hidden" id="txt_stock_'+i+'"></div><div class="col-sm-1"  style="width:4%; padding-left: 2px;  padding-right: 0px;"><b>UNI</b><input type="" value="'+item.UNIDAD+'" name="txt_uni_" id="txt_uni_'+i+'" class="form-control "></div><div class="col-sm-1"  style="width:5%; padding-left: 2px;  padding-right: 0px;"><b>Cantidad</b><input value="'+item.CANT_ES+'" type="text" name="txt_cant_" id="txt_cant_'+i+'" placeholder="Cantidad" class="form-control " onblur="validar_stock2(\''+i+'\');"></div><div class="col-sm-2"  style=" padding-left: 2px;  padding-right: 0px;"><b>Centro de costos</b><br><select class="form-control select_cc" id="ddl_cc_'+i+'"  id="ddl_cc_'+i+'"><option>Centro de costos</option></select></div><div class="col-sm-2"  style=" padding-left: 2px;  padding-right: 0px;"><b>Rubro</b><br><select class="form-control selectr" id="ddl_rubro_'+i+'" name="ddl_rubro_'+i+'"><option>Rubro</option></select></div><div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px; display:none"> <b style="font-size: 13px;">Bajas o desperdi</b><input type="" name="" placeholder="Bajas o desperdicios" id ="txt_bajas_'+i+'" class="form-control " value="'+item.Consumos+'" onblur="validar_bajas(\''+i+'\')"></div>';
                  
                //     lineas+='<div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px; display:none"><b>Bajas por</b><br><select class="form-control select_b" id="ddl_rubro_bajas_'+i+'" name="ddl_rubro_bajas_'+i+'"><option>Bajas por</option></select></div><div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px; display:none "><b>Observaciones</b><textarea placeholder="observacion" class="form-control" id="txt_obs_'+i+'">'+item.Procedencia+'</textarea></div><div class="col-sm-1"><br><button onclick="Guardar(\''+i+'\')" class="btn btn-primary btn-sm" title="Guardar" style="display:none"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button><button onclick="eliminar(\''+i+'\')" class="btn btn-danger btn-sm" title="Eliminar"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></div></div>';


                    // lineas +='<div class="row"><div class="col-sm-1" style="padding-right: 6px;"><b>Fecha</b><br><label id="fecha_'+i+'">'+item.Fecha_Fab.date.substr(0,10)+'<label/></div><div class="col-sm-1" style=" padding-left: 0px;  padding-right: 0px;"><b>Codigo</b><br>'+item.CODIGO_INV+'"<input type="hidden" id="txt_pos_'+i+'" value="'+item.A_No+'"></div><div class="col-sm-3"  style=" padding-left: 2px;  padding-right: 0px;"><b>Descripcion</b></br><p id="ddl_productos_'+i+'" name="ddl_productos_'+i+'"</p></div><div class="col-sm-1"  style="width:4%; padding-left: 2px;  padding-right: 0px;"><b>UNI</b></br><p>'+item.UNIDAD+'"</p></div><div class="col-sm-1"  style="width:5%; padding-left: 2px;  padding-right: 0px;"><b>Cantidad</b><br><p>'+item.CANT_ES+'"</p></div><div class="col-sm-2"  style=" padding-left: 2px;  padding-right: 0px;"><b>Centro de costos</b><br><p>Centro de costos</p></div><div class="col-sm-2"  style=" padding-left: 2px;  padding-right: 0px;"><b>Rubro</b><br><p>Rubro</p></div><div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px; display:none"> <b style="font-size: 13px;">Bajas o desperdi</b><input type="" name="" placeholder="Bajas o desperdicios" id ="txt_bajas_'+i+'" class="form-control " value="'+item.Consumos+'" onblur="validar_bajas(\''+i+'\')"></div>';
                  
                    // lineas+='<div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px; display:none"><b>Bajas por</b><br><select class="form-control select_b" id="ddl_rubro_bajas_'+i+'" name="ddl_rubro_bajas_'+i+'"><option>Bajas por</option></select></div><div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px; display:none "><b>Observaciones</b><textarea placeholder="observacion" class="form-control" id="txt_obs_'+i+'">'+item.Procedencia+'</textarea></div><div class="col-sm-1"><br><button onclick="Guardar(\''+i+'\')" class="btn btn-primary btn-sm" title="Guardar" style="display:none"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button><button onclick="eliminar(\''+i+'\')" class="btn btn-danger btn-sm" title="Eliminar"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></div></div>';

              
                 
            });            
            $('#contenido_entrega').html(lineas);

            // $.each(response,function(i,item){
            //   stock_pro(item.CODIGO_INV,i);
            //    $('#ddl_productos_'+i).append($('<option>',{value: item.CODIGO_INV, text: item.PRODUCTO,selected: true }));
               
            //    cc_cod(item.CONTRA_CTA,i,item.SUBCTA);
            //    // bajas_(item.Codigo_Dr,i);
            // });
                
           }
        }
      });
  }

  function cargar(id)
  {
    alert('ddd');

  }

  function Guardar(id='')
  {
    if(nuevaTabla === true){
      cargar_tabla();
    }else{
      $('#contenedor_tabla_csv').hide();
      $('#contendor-tabla').show();
      rubro = $('#ddl_rubro_'+id).val().split(',');
        bajas = $('#txt_bajas_'+id).val();
        ddl_bajas = $('#ddl_rubro_bajas_'+id).val().split(',');
        producto = $('#ddl_productos_'+id).val().split(',');
        if(producto=='')
        {
          Swal.fire('Seleccione producto','','info');
          return false;
        }
        console.log(producto);
        var parametros = 
        {
            'codigo':$('#txt_codigo_'+id).val(),
            'producto':$('select[name="ddl_productos_'+id+'"] option:selected').text(),
            'cta_pro':producto[5],
            'uni':$('#txt_uni_'+id).val(),
            'cant':$('#txt_cant_'+id).val(),
            'cc':$('#ddl_cc_'+id).val(),
            'rubro':rubro[0],
            'bajas':bajas,
            'observacion':$('#txt_obs_'+id).val(),
            'id':id,
            'ante':$('#txt_id_pro_'+id).val(),
            'fecha':$('#txt_fecha').val(),
            'bajas_por':ddl_bajas[0],
            'TC':$('#TC').val(),
            'valor':$('#valor_total').val(),
            'total':$('#valor_total_linea').val(),
            'pro':$('#txt_proyecto').val(),
            'codma':$('#txt_CodMar').val(),
        };

      if(validar_entrada()==true)
      {
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/inventario/inventario_onlineC.php?guardar=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
            if(response ==1)
            {
              Swal.fire(
                
                'Operacion realizada con exito.','',
                'success'
              )
              $('#txt_cant_').val(0);
              cargar_entrega()
              validar_presupuesto($('#txt_codigo_').val())
            // location.reload();
            }else
            {
              Swal.fire(
                '',
                'Algo extraño a pasado.',
                'error'
              )

            }           
          }
        });
      }
    }

  }


function validar_entrada()
{

  if($('#txt_bajas_').val()!=0)
  {
    if($('#ddl_rubro_bajas_').val() == "")
    {
       Swal.fire(
            '',
            'Procure escoger bajas y agregar un comentaerio.',
            'error'
          );
       return false;
    }
    if($('#ddl_cc_').val() !='' && $('#ddl_rubro_').val() !='')
    {

    return true;
  }else
  {
    Swal.fire(
            '',
            'Rubro o Centro de costo no seleccionado.',
            'error'
          );
    return false;
  }
  }else
  {
    if($('#ddl_rubro_bajas_').val() != "")
    {
       Swal.fire(
            '',
            'Procure colocar un valor en bajas.',
            'error'
          );
       return false;
    }
    if($('#ddl_cc_').val() !='' && $('#ddl_rubro_').val() !='')
    {

    return true;
  }else
  {
    Swal.fire(
            '',
            'Rubro o Centro de costo no seleccionado.',
            'error'
          );
    return false;
  }
  }

}

  function eliminar(id)
  {
    Swal.fire({
  title: 'Quiere eliminar linea?',
  text: "Esta seguro d eliminar linea!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Si'
}).then((result) => {
  if (result.value) {

    var parametros = 
    {
        'id':$('#txt_codigo_'+id).text(),        
        'id_':$('#txt_pos_'+id).val(),
    };
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?eliminar_linea=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        if(response ==1)
        {
          Swal.fire(
            'Registro eliminado',
            '',
            'success'
          )         
         cargar_entrega();          
          // validar_presupuesto($('#txt_codigo_'+id).text())
        }else
        {
          Swal.fire(
            '',
            'Algo extraño a pasado.',
            'error'
          )

        }           
      }
    });
  }
})

  }


   function stock_pro(id,pos)
  {

          // alert(id);
    $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?producto_id=true&q='+id,
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          console.log(response);
          var selec = response[0].id;
     var cod = selec.split('/'); 
          $('#txt_stock_'+pos).val(cod[2]);
         
      }
    });
  }

  function rubro_cod(id,pos)
  {

    var cc = $('#ddl_cc_'+pos).val();  
    console.log(pos);
    console.log(cc);
    false;
    $.ajax({
     // data:  {parametros:parametros},
      url:    '../controlador/inventario/inventario_onlineC.php?rubro=true&q='+id+'&pro='+cc,
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          console.log(response);
          console.log('ss');

             $('#ddl_rubro_'+pos).append($('<option>',{value: response[0].id, text:response[0].text,selected: true }));
         
      }
    });
  }
function cc_cod(id,pos,subcta)
  {
    $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?cc=true&q='+id+'&pro=',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          // console.log(response); 
             $('#ddl_cc_'+pos).append($('<option>',{value: response[0].id, text:response[0].text,selected: true }));    
            rubro_cod(subcta,pos);    
           
      }
    });
  }
  function bajas_(id,pos)
  {
    $.ajax({
     // data:  {parametros:parametros},
      url:  '../controlador/inventario/inventario_onlineC.php?rubro_bajas=true&q='+id,
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          // console.log(response)
             $('#ddl_rubro_bajas_'+pos).append($('<option>',{value: response[0].id, text:response[0].text,selected: true }));         
      }
    });
  }

  function generar_asiento_datos()
  {
     $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?generar_asiento=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          if(response.length > 0)
          {
            generar_asiento(response);
          }
      }
    });

  }

  function datos_asientos()
  {
    var fechaA = f;
    var acabar_debe = 0;
    var acabar_haber = 0; 
      $.ajax({
      data:  {fechaA:fechaA},
      url:  '../controlador/inventario/inventario_onlineC.php?datos_asiento=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          $.each(response.debe,function(i,item){
            console.log(response.debe);
             var parametros = 
                {
                  "va" :item.valor,//valor que se trae del otal sumado
                  "dconcepto1" :item.dconcepto1,
                  "codigo" : item.codigo, // cuenta de codigo de 
                  "cuenta" : item.dconcepto1, // detalle de cuenta;
                  "efectivo_as" :item.fecha, // observacion si TC de catalogo de cuenta
                  "chq_as" : 0,
                  "moneda" : 1,
                  "tipo_cue" : item.tipo_cue,
                  "cotizacion" : 0,
                  "con" : 0,// depende de moneda
                  "t_no" : '60',
                  "ajax_page": 'ing1',
                  "cl": 'as_i',
                };
                generar_asiento(parametros);
          });
          acabar_debe = 1;
          $.each(response.haber,function(i,item){
            var parametros = 
                {
                  "va" :item.valor,//valor que se trae del otal sumado
                  "dconcepto1" :item.dconcepto1,
                  "codigo" : item.codigo, // cuenta de codigo de 
                  "cuenta" : item.dconcepto1, // detalle de cuenta;
                  "efectivo_as" :item.fecha, // observacion si TC de catalogo de cuenta
                  "chq_as" : 0,
                  "moneda" : 1,
                  "tipo_cue" : item.tipo_cue,
                  "cotizacion" : 0,
                  "con" : 0,// depende de moneda
                  "t_no" : '60',
                  "ajax_page": 'ing1',
                  "cl": 'as_i',
                };
                generar_asiento(parametros);
            
          });          
          acabar_haber = 1; 
          if(acabar_haber ==1 && acabar_debe == 1)
          {           
            numero_com();
          }else
          {
            alert('no entro');
          }           
      }
    });

  }

  function generar_asiento(parametros)
  {
     $.ajax({
      data:  parametros,
      url:   'ajax/vista_ajax.php',
      type:  'post',
      dataType: 'json',
      success:  function (response) {
        console.log(response);
        if(response.resp==1)
        {
          Swal.fire('Generado comprobantre'+response.com,'','success').then(function(){ location.reload();});
        }else
        {
          Swal.fire(response.com,'','error');
        }
      }
    });
  }

// function generar_comprobante(parametros)
// {
//   var fech = f;
//     var parame = 
//     {
//        "ajax_page": 'ing1',
//       cl: 'ing_com',
//       ru: parametros.ru, //codigo del cliente que sale co el ruc del beneficiario codigo
//       tip:parametros.tip,//tipo de cuenta contable cd, etc
//       fecha1: fech,// fecha actual 2020-09-21
//       concepto: parametros.concepto, //detalle de la transaccion realida
//       totalh: parametros.totalh, //total del haber
//       num_com: parametros.num_com // codigo de comprobante de esta forma 2019-9000002
//     };
//     $.ajax({
//        data:  parame,
//       url:   'ajax/vista_ajax.php',
//       type:  'post',
//       // beforeSend: function () {
//       //     $("#compro").html("");
//       // },
//        success:  function (response) {

//         var com = parametros.num_com.split('-'); 
//          Swal.fire({
//             type: 'success',
//             title: 'Comprobante '+com[1]+' ingresado con exito!',
//             confirmButtonText: 'OK!',
//             allowOutsideClick: false,
//           }).then((result) => {
//             if (result.value) {
//                location.href='../vista/inventario.php?mod=03&acc=inventario_online&acc1=Inventario%20online&b=1&po=subcu'
//             }
//           })


//          // Swal.fire({
//          //     //position: 'top-end',
//          //    type: 'success',
//          //    title: 'Comprobante '+com[1]+' ingresado con exito!',
//          //    showConfirmButton: true
//          //    //timer: 2500
//          //  }); 
//       $('#myModal_espera').modal('hide');                                  
//       generar_uno_uno();
//       }

//     });
// }


  // function numero_com(){
  //  // var f = fec;
  //   var parametros = 
  //     {
  //       "ajax_page": 'bus',
  //       cl: 'num_com',
  //       tip: 'CD',
  //       fecha: f, //fecha                    
  //     };
  //     $.ajax({
  //       data:  parametros,
  //       url:   'ajax/vista_ajax.php',
  //       type:  'post',
  //       // beforeSend: function () {
  //       //     $("#num_com").html("");
  //       // },
  //       success:  function (response) {
  //         //console.log(response);
  //         datos_comprobante(response);
  //       }
  //     });
  // }
// function datos_comprobante(codigo)
// {
//   var lbl = $('#lbl_proyecto').text();

//   var fechaC = f;
//    $.ajax({
//       data:  {codigo:codigo,fechaC:fechaC,proyecto:lbl},
//       url:   '../controlador/inventario/inventario_onlineC.php?datos_comprobante=true',
//       type:  'post',
//       dataType: 'json',
//         success:  function (response) { 
//           if(response==-1)
//           {
//             datos_asiento_SC();            
//              // Swal.fire({
//              //    type: 'error',
//              //    title: 'Las transacciones no cuadran correntamente vuelva a intentar para corregir!',
//              //    text: ''
//              //  });
//           }else if(response == -2)
//           {
//             datos_asiento_SC();
//             // Swal.fire({
//             //     type: 'error',
//             //     title: 'Las transacciones no cuadran correntamente vuelva a intentar para corregir!',
//             //     text: ''
//             //   });
//           }
//           else
//           {
            
//             generar_comprobante(response);
//             ingresar_trans_kardex(response.num_com,f);

//           }
//       }
//     });
//  // }else
//  // {
//  //  Swal.fire({
//  //                type: 'error',
//  //                title: 'Las transacciones no cuadran correntamente vuelva a intentar mas tarde!',
//  //                text: ''
//  //              });
//  // }
// } 
// function ingresar_trans_kardex(comprobante,fechaC)
// {
//   //var f = fechaC;
//    $.ajax({
//       data:  {comprobante:comprobante,f:f},
//       url:   '../controlador/inventario/inventario_onlineC.php?Trans_kardex=true',
//       type:  'post',
//       dataType: 'json',
//         success:  function (response) { 
        
//       }
//     });

// }

function genera_comprobantes_por_fecha()
{
  var fc = new Array();
  var num = $('#num_filas').val();
  for (var i = 0; i < num; i++) {
    fc[i] = $('#fecha_'+i).text();
  }
  let fec=fc.filter (
      (value,pos,self) => {
        return pos === self.indexOf(value);
      }
    );
  var fecha_c ='';
  $.each(fec,function(i,item){
     generar_comprobante1(item);
  })



}


function generar_comprobante1(fecha)
{
  // var fecha = '2022-02-07';  
  // $('#myModal_espera').modal('show');
  $.ajax({
      data:  {fecha:fecha},
      url:  '../controlador/inventario/inventario_onlineC.php?generar_comprobante=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {  
         console.log(response);
        if(response.resp==1)
        {            // allowOutsideClick:false
          Swal.fire('Generado comprobante '+response.com,'','success').then(function(){ location.reload();});
        }else
        {
          Swal.fire(response.com,'','error');
        }      
        $('#myModal_espera').modal('hide');
      }
    });

}

//inicia qui
function datos_asiento_SC()
{
   $('#myModal_espera').modal('show');

  var fc = new Array();
  var num = $('#num_filas').val();
  for (var i = 0; i < num; i++) {
    fc[i] = $('#fecha_'+i).val();
  }
  let fec=fc.filter (
      (value,pos,self) => {
        return pos === self.indexOf(value);
      }
    );
  var fecha_c ='';
  $.each(fec,function(i,item){
      fecha_c+= item+'/'; 
  })
  $('#fechas_compro').val(fecha_c);
  generar_uno_uno();
}

function generar_uno_uno()
{
  if($('#fechas_compro').val() !="")
  {
    var fe = $('#fechas_compro').val().slice(0,-1).split('/');
    var res = $('#fechas_compro').val().split(fe[0]+'/').join('');
    $('#fechas_compro').val(res);
    f = fe[0];
     datos_asiento_SC_(fe[0]);
  }else
  {
    eliminar_asientosk();
  }
}

function eliminar_asientosk()
{
    $.ajax({
      //data:  {},
      url:  '../controlador/inventario/inventario_onlineC.php?eliminar_asientos_k=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {  
           cargar_entrega();
      }
    });

}

function datos_asiento_SC_(fecha)
{ 
 // var f = fecha;
  $.ajax({
      data:  {fecha:fecha},
      url:  '../controlador/inventario/inventario_onlineC.php?datos_asiento_SC=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 

          console.log(response);
         $.each(response,function(i,item){
           var parametros = 
                  {
                    "ajax_page": 'ing1',
                    cl: 'ing_sub1',
                    be:item.benericiario,
                    ru: item.ruc,
                    co: item.Codigo,// codigo de cuenta cc
                    tip: item.tipo,//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
                    tic: item.tic, //debito o credito (1 o 2);
                    sub: item.sub, //Codigo se trae catalogo subcuenta
                    sub2:item.benericiario,//nombre del beneficiario
                    fecha_sc: item.fecha, //fecha 
                    fac2: item.fac2,
                    mes: 0,
                    valorn: item.valorn,//valor de sub cuenta 
                    moneda: item.moneda, /// moneda 1
                    Trans: item.Trans,//detalle que se trae del asiento
                    T_N: item.T_N,
                    t: item.tipo,                        
                  };
                  // console.log(parametros);
                  // break;
                 asiendo_sc(parametros);
         }); 

        datos_asientos();     
      }
    });



}
  function asiendo_sc(parametros)
  {     
    console.log(parametros);
    $.ajax({
      data:  parametros,
      url:   'ajax/vista_ajax.php',
      type:  'post',
      success:  function (response) {
        // console.log(response);
      }
    });
  }


  function validar_bajas(id='')
  {  

      var bajas = $('#txt_bajas_').val();
      var cant = $('#txt_cant_').val();
      sum_stock = parseFloat(bajas)+parseFloat(cant);
      stock = parseFloat($('#txt_stock').val());
      if(sum_stock <= stock){
      if(bajas =='')
      {
        $('#txt_bajas_').val(0);
      }
        var bajas = $('#txt_bajas_').val();
      if(bajas !=0)
      {
         $('#ba').css('display','block');
        $('#ob').css('display','block');
      }else
      {
         $('#ba').hide();
         $('#ob').hide();

      }
    }else
    {
       Swal.fire(
            '',
            'La cantidad elegida supera al Stock de '+$('#txt_stock').val()+'.',
            'info'
          );

       $('#txt_bajas_').val(0);

    }
   }


   function costo_existencias(codigoInv)
   {
       $.ajax({
        data:  {codigoInv:codigoInv},
        url:   '../controlador/inventario/inventario_onlineC.php?costo_existencias=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) { 
            // console.log(response);
            if(response.respueta==true)
            {
               $('#valor_total').val(parseFloat(response.datos.Costo).toFixed(2));
               $('#txt_stock').val(response.datos.Stock);
               if(response.datos.Stock > response.datos.Minimo)
                {
                   $('#txt_stock').css('background-color','greenyellow');
                }else
                {
                   $('#txt_stock').css('background-color','coral');
                }
            }else
            {
               $('#valor_total').val(0);         
               $('#txt_stock').val(0);
             Swal.fire('Este Producto no contiene  Stock','','info').then(function(){$('#ddl_productos_').empty();});
            }
        }
      });
   }

   // function stock_real(id)
   // {
   //   $.ajax({
   //    data:  {id:id},
   //    url:   '../controlador/inventario/inventario_onlineC.php?stock_kardex=true',
   //    type:  'post',
   //    dataType: 'json',
   //      success:  function (response) { 
   //        console.log(response);
   //        if(response[0].stock != null)
   //        {
   //           if(response[0].stock > response[0].min)
   //          {
   //             $('#txt_stock').css('background-color','greenyellow');
   //          }else
   //          {
   //             $('#txt_stock').css('background-color','coral');
   //          }
   //          $('#txt_stock').val(response[0].stock);

           

   //        }else
   //        {
   //          $('#txt_stock').val(0);
   //           Swal.fire(
   //          'Este Producto no contiene  Stock',
   //          '',
   //          'info'
   //        ).then(function(){
   //          $('#ddl_productos_').empty();
   //        });
   //           // $('#ddl_productos_').val('').trigger('change');
   //        }
   //    }
   //  });
   // }
  // function costo_venta(id)
  //  {
  //    $.ajax({
  //     data:  {id:id},
  //     url:   '../controlador/inventario/inventario_onlineC.php?costo_venta=true',
  //     type:  'post',
  //     dataType: 'json',
  //       success:  function (response) { 
  //         console.log(response);
  //         if(response!='')
  //         {
  //         if(response[0].Costo!= null && response[0].Costo !='')
  //         {
  //           $('#valor_total').val(response[0].Costo);
  //         }else
  //         {
  //           $('#valor_total').val(0);            
  //         }
  //       }else
  //       {
  //         $('#valor_total').val(0);  
  //       }
  //     }
  //   });
  //  }

 function comprueba_negativos(id){
  //var nu = parseFloat($('#txt_'+id+'_').val());
  var nu = $('#txt_'+id+'_').val();
  var patt = new RegExp("^[+]?[0-9]{1,9}(?:.[0-9]{1,3})?$");
  var patt1 = new RegExp("^[+]?[0-9]{1,9}(?:.)?$");
  if(isNaN(nu) || nu=="" )
  {
    nu = '';
  }else
  { 
    if(patt.test(nu))
    {
     // console.log('cumple');
    }else
    {
       if(patt1.test(nu))
        {
           //console.log('cumple');
        }else
        {
          nu=0
        }
    }
  }
//   if( /^-?(?:\d+(?:,\d*)?)$/.test(nu)){
//   if(nu < 0){
//      nu = 1;
//   }
// } 
$('#txt_'+id+'_').val(nu);

}


function generar_proyecto()
{
  var a = location.href;
  var pro = $('#ddl_proyecto').val();
  var marca = $('#ddl_marca').val();
  if(pro!='' && marca!='')
  {
   location.href = a+'&proyecto='+pro+'&marca='+marca;
  }else
  {
    Swal.fire('Seleccione todos los campos','','info');
  }
}

function marcas()
{
  // var ma = $('#txt_Codmar').val();
  $.ajax({
      // data:  {id:id},
      url:   '../controlador/inventario/inventario_onlineC.php?codmarca=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          llenarComboList(response,'ddl_marca');
      }
    });
}

function proyectos()
{
  var pro = $('#txt_proyecto').val();
  $.ajax({
      // data:  {id:id},
      url:   '../controlador/inventario/inventario_onlineC.php?proyecto=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
           llenarComboList(response,'ddl_proyecto');
      }
    });
}
function mayorizar_inventario()
  {
    $('#myModal_espera').modal('show');
    var fecha = $('#txt_fecha').val();
    var parametros =  
    {
      'fecha':fecha,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?mayorizar=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        $('#myModal_espera').modal('hide');
         Swal.fire('Mayorizacion completada','','success');
      
      }
    });
  }