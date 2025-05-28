   $( document ).ready(function() {

     if(c!='')
    {
      buscar_codi();
    }
    if(area !='')
    {
      buscar_Subcuenta();
    }


    autocoplet_paci();
    autocoplet_ref();
    autocoplet_desc();
    autocoplet_cc();
    autocoplet_area();
    num_comprobante();
    autocopletar_solicitante();
    $('#txt_procedimiento').val(pro);
     // buscar_cod();

    // cargar_pedido();
    

  });



   function buscar_Subcuenta()
   {
      var  parametros = 
      { 
        'cod':area,
      }    
      // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/ingreso_descargosC.php?areas=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        if(response.length >0){       
           $('#ddl_areas').append($('<option>',{value: response[0].Codigo, text:response[0].Detalle,selected: true }));
         }
      }
    });


   }
   function autocoplet_paci(){
      $('#ddl_paciente').select2({
        placeholder: 'Seleccione una paciente',
        disabled:true,
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?paciente=true',
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

   function autocopletar_solicitante(){
      $('#txt_soli').select2({
        placeholder: 'Seleccione una paciente',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?solicitante=true',
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

  function autocoplet_cc(){
      $('#ddl_cc').select2({
        placeholder: 'Seleccione centro de costos',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?cc=true',
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
    function autocoplet_ref(){
      $('#ddl_referencia').select2({
        placeholder: 'Escriba Referencia',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?producto=true&tipo=ref',
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
   function autocoplet_desc(){
      $('#ddl_descripcion').select2({
        placeholder: 'Escriba Descripcion',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?producto=true&tipo=desc',
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

 
  function buscar_cod()
  {
      var  parametros = 
      { 
        'query':$('#ddl_paciente').val(),
        'tipo':'R1',
        'codigo':'',
      }    
      // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?buscar_edi=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        if(response != -1){       
           $('#txt_codigo').val(response[0].Matricula);
           $('#txt_nombre').val(response[0].Cliente);
           $('#ddl_paciente').append($('<option>',{value: response[0].CI_RUC, text:response[0].Cliente,selected: true }));
           $('#txt_ruc').val(response[0].CI_RUC);
         }
      }
    });
  }

   function buscar_codi()
  {
      var  parametros = 
      { 
        'query':cod,
        'tipo':'C1',
        'codigo':'',
      }    
      // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?buscar_edi=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        if(response.matricula==0 || response.matricula=='' || response.matricula==null)
        {
          Swal.fire('El paciente no tiene numero de historial','','error').then(function(){
            location.href = 'inicio.php?mod=28&acc=vis_descargos';
          })
        }
       
           $('#txt_codigo').val(response.matricula);
           $('#txt_nombre').val(response.nombre);
           $('#ddl_paciente').append($('<option>',{value: response.Codigo, text:response.nombre,selected: true }));
           $('#txt_ruc').val(response.ci);
           cargar_pedido();
      }
    });
  }

  function producto_seleccionado(tipo)
  {
    if(tipo=='R')
    {
      var val = $('#ddl_referencia').val();
      var partes = val.split('_');
        $('#ddl_descripcion').append($('<option>',{value: partes[0]+'_'+partes[1]+'_'+partes[2]+'_'+partes[3]+'_'+partes[4]+'_'+partes[5]+'_'+partes[6], text:partes[2],selected: true }));
        $('#txt_precio').val(partes[1]); 
        $('#txt_iva').val(partes[6]); 
        $('#txt_unidad').val(partes[7]); 
        $('#txt_Stock').val(partes[8]);

        $('#txt_max').val(partes[9]);
        $('#txt_min').val(partes[10]); 

        if(parseFloat(partes[8])>=parseFloat(partes[10]))
        {
          $('#txt_Stock').css('background-color','greenyellow','!important');
        }else
        {
           $('#txt_Stock').css('background-color','coral','!important');
        }
        // console.log(partes[8]+'-'+partes[10]);
    }else
    {
      var val = $('#ddl_descripcion').val();
      var partes = val.split('_');
        $('#ddl_referencia').append($('<option>',{value: partes[0]+'_'+partes[1]+'_'+partes[2]+'_'+partes[3]+'_'+partes[4]+'_'+partes[5]+'_'+partes[6], text:partes[0],selected: true }));

        // console.log($('#ddl_descripcion').val());
        $('#txt_precio').val(partes[1]); 
        $('#txt_iva').val(partes[6]);  
        $('#txt_unidad').val(partes[7]);
        $('#txt_Stock').val(partes[8]);
        $('#txt_max').val(partes[9]);
        $('#txt_min').val(partes[10]); 
         if(parseFloat(partes[8])>=parseFloat(partes[10]))
        {
          $('#txt_Stock').css('background-color','greenyellow','!important');
        }else
        {
           $('#txt_Stock').css('background-color','coral','!important');
        }
        // console.log($('#ddl_descripcion').val());
        // console.log(partes[8]+'-'+partes[10]);
    }

  }


  function autocoplet_area(){
      $('#ddl_areas').select2({
        placeholder: 'Seleccione una Area de descargo',
        ajax: {
          url:   '../controlador/farmacia/descargosC.php?areas=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   
  }


  function Guardar()
  {
   var producto = $('#ddl_descripcion').val();
   var cc = $('#ddl_cc').val();
   var cc1 = cc.split('-');
   var ruc = $('#txt_ruc').val();
   var cc = $('#ddl_cc').val();
   var cos = $('#txt_precio').val();
   var soli = '.';//$('#txt_soli').val();

   var paciente = $('#ddl_paciente').val();
   if(paciente=='' || paciente=='0')
   {
    Swal.fire('Paciente no seleccionado','','info');
    return false;
   }
   // if(soli=='')
   // {
   //   Swal.fire('Agregue la persona solicitante','','info');
   //   return false;
   // }
   if(cos=='' || cos ==0)
   {
     Swal.fire('No se pudo agregar por que el costo de este articulo es igual 0.','','info');
     return false;
   }
    if(producto !='' && ruc!='' && cc!='')
    {
      if($('#txt_cant').val()<=0)
      {
        Swal.fire('','La cantidad Debe ser mayor que 0.','info');
        $('#txt_cant').val('1');
        return false;
      }
      // if(parseFloat($('#txt_cant').val()) > parseFloat($('#txt_Stock').val()))
      // {
      //   Swal.fire('','Stock insuficiente.','info');
      //   $('#txt_cant').val($('#txt_Stock').val());
      //   return false;
      // }
      var prod = producto.split('_');
    // console.log(producto);
    // return false;
       var parametros = 
       {
           'codigo':prod[0],
           'producto':prod[2],
           'cta_pro':prod[3],
           'uni':'',
           'cant':$('#txt_cant').val(),
           'cc':cc1[0],
           'rubro':$('#ddl_areas').val(),
           'bajas':'',
           'observacion':'',
           'id':$('#txt_num_item').val(),
           'ante':'',
           'fecha':$('#txt_fecha').val(),
           'bajas_por':'',
           'TC':prod[4],
           'valor':$('#txt_precio').val(),
           'total':$('#txt_importe').val(),
           'num_ped':$('#txt_pedido').val(),
           'ci':$('#txt_ruc').val(),
           'CodigoP':$('#ddl_paciente').val(),
           'descuento':0,
           'iva':$('#txt_iva').val(),
           'pro':$('#txt_procedimiento').val(),
           'area':$('#ddl_areas option:selected').text(),
           'solicitante':soli,
       };
       $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/farmacia/ingreso_descargosC.php?guardar=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 

            // console.log(response);
           if(response.resp==1)
           {
            $('#txt_pedido').val(response.ped);
              Swal.fire({
                icon:'success',
                title: 'Agregado a pedido',
                text :'',
              }).then( function() {
                   cargar_pedido();
                });

            // Swal.fire('','Agregado a pedido.','success');
            limpiar();
            // location.reload();
           }else
           {
            Swal.fire('','Algo extraño a pasado.','error');
           }           
         }
       });
    }else
    {
       Swal.fire('','Producto,Centro de costos ó Cliente no seleccionado.','error');
    }
  }




  function cargar_pedido()
  {
    var num_his = cod;
    var pro = pro;
    var parametros=
    {
      'num_ped':num_ped,
      'area':area,
      'num_his':num_his,
      'paciente':$('#ddl_paciente').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/ingreso_descargosC.php?pedido=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {
        // console.log(response);
        num_ped = $('#txt_pedido').val();
        if(num_ped=='')
        {
           $('#tabla').html(response.tabla);
        }else{
          var ped = reload_();
          if(ped==-1)
          {
            num_ped = $('#txt_pedido').val();
            mod = ModuloActual;
            var url="../vista/inicio.php?mod="+mod+"&acc=ingresar_descargos&area="+area+"-"+pro+"&num_ped="+num_ped+"&cod="+num_his+"#";
            $(location).attr('href',url);
          }else
          {

             $('#txt_num_lin').val(response.num_lin);
            $('#txt_num_item').val(response.item);
            $('#tabla').html(response.tabla);
            $('#txt_neg').val(response.neg);
            $('#txt_sub_tot').val(response.subtotal);
            $('#txt_tot_iva').val(response.iva);
            $('#txt_pre_tot').val(response.total);
            $('#txt_procedimiento').val(response.detalle);
            if($('#txt_num_lin').val()!=0 && $('#txt_num_lin').val()!='')
            {
              $('#btn_comprobante').css('display','block');
            }

          }
        }
      }
    });
  }

  function reload_()
  {
    var url = location.href;
    let posicion = url.indexOf('num_ped');
    if (posicion !== -1)
    {
      return 1; //econtro
    }else{
      return -1; //no encontro
    }

  }

  function editar_lin(num)
  {
    var parametros=
    {
      'can':$('#txt_can_lin_'+num).val(),
      'pre':$('#txt_pre_lin_'+num).val(),
      'des':0,
      'tot':$('#txt_tot_lin_'+num).val(),
      'lin':num,
      'ped':$('#txt_pedido').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/ingreso_descargosC.php?lin_edi=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
          Swal.fire('Linea de pedido Editado.','','success');         
          cargar_pedido();
        }
      }
    });
  }
  function eliminar_lin(num)
  {
    var ruc = $('#txt_ruc').val();
    var cli = $('#ddl_paciente').text();
    // console.log(cli);
    Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
            var parametros=
            {
              'lin':num,
              'ped':$('#txt_pedido').val(),
            }
             $.ajax({
              data:  {parametros:parametros},
              url:   '../controlador/farmacia/ingreso_descargosC.php?lin_eli=true',
              type:  'post',
              dataType: 'json',
              success:  function (response) { 
                if(response==1)
                {
                  cargar_pedido();
                }
              }
            });
        }
      });
  }

  function calcular_totales(num=false)
  {
    if(num)
    {
      var cant = parseFloat($('#txt_can_lin_'+num).val());
      var pre = $('#txt_pre_lin_'+num).val();
      var uti = parseFloat($('#txt_uti_lin_'+num).val());


      var sin_des = (cant*pre);
      var des = 0;
      var val_des = (sin_des*des)/100;
      var impo = parseFloat(sin_des-val_des);
      var iva =0; 
      // parseFloat($('#txt_iva_lin_'+num).val());
      var tot = $('#txt_tot_lin_'+num).val(parseFloat(impo));

       if(iva!=0 && uti!=0)
       {
         var sin_des = (cant*pre);
         var des = 0;
         var val_des = (sin_des*des)/100;
         var impo = parseFloat(sin_des-val_des);
         var tot_iva = ((impo*1.12)-impo);
         // console.log(tot_iva);
          $('#txt_iva_lin_'+num).val(0);
          $('#txt_tot_lin_'+num).val(parseFloat(impo).toFixed(4));
       }else if(uti!=0 && iva==0)
       {
         var sin_des = (cant*pre);
         var des = 0;
         var val_des = ((sin_des*des)/100);
         var impo = parseFloat(sin_des-val_des);
         var tot_iva = ((impo*1.12)-impo);
         // console.log(tot_iva);
          // $('#txt_iva_lin_'+num).val(parseFloat(tot_iva));
          $('#txt_tot_lin_'+num).val(parseFloat(impo).toFixed(4));
       }

    }else
    {
      // console.log('entr');
      var cant = $('#txt_cant').val();
      var pre = $('#txt_precio').val();
      var sin_des = (cant*pre);
      var des = 0;
      var val_des = (sin_des*des)/100;
      var tot = $('#txt_importe').val((sin_des-val_des).toFixed(2));

    }

      // descuentos();
  }

  function descuentos()
  {
    var num = $('#txt_num_lin').val();
    var item = $('#txt_num_item').val();
    var op = $('input:radio[name=rbl_des]:checked').val();

      // console.log(op);
    if(op=='L')
    {
       $('#txt_tot_des').val(0);
       var tot = 0;
       var sub = 0;
       var iva = 0;
      for (var i = 0; i <=item ; i++) {
            $('#txt_des_lin_'+i).attr("readonly", false);
            calcular_totales(i);
            if($('#txt_tot_lin_'+i).length)
            {
              var des = parseFloat($('#txt_des_lin_'+i).val());          
              pre = parseFloat($('#txt_pre_lin_'+i).val());
              can = parseFloat($('#txt_can_lin_'+i).val());
              uti = parseFloat($('#txt_uti_lin_'+i).val());
              sub+= parseFloat($('#txt_tot_lin_'+i).val());
              iva+=parseFloat($('#txt_iva_lin_'+i).val());
              tot+=((((pre*can)+uti)*des)/100);
            }
             $('#txt_tot_des').val(tot.toFixed(2))
             $('#txt_sub_tot').val(sub.toFixed(2));
             $('#txt_tot_iva').val(iva.toFixed(2));
      }

    }else if(op=='TL')
    {
      var des =$('#txt_des').val();
      var tot = 0;
      var sub = 0;
      var iva = 0;
      for (var i = 0; i <=item ; i++) {
            // console.log(i);
           
            if($('#txt_tot_lin_'+i).length)
            {
              $('#txt_des_lin_'+i).val(des);            
              $('#txt_des_lin_'+i).attr("readonly", true);
              calcular_totales(i);
              pre = parseFloat($('#txt_pre_lin_'+i).val());
              can = parseFloat($('#txt_can_lin_'+i).val());
              uti = parseFloat($('#txt_uti_lin_'+i).val());
              sub+= parseFloat($('#txt_tot_lin_'+i).val());
              iva+= parseFloat($('#txt_iva_lin_'+i).val());
              
              if(uti!=0)
              {
                tot+=(((can*uti)*des)/100);
              }else
              {
                tot+=(((can*pre)*des)/100);
              }
            }
      }
      $('#txt_tot_des').val(tot.toFixed(2))
      $('#txt_sub_tot').val(sub.toFixed(2));
      $('#txt_tot_iva').val(iva.toFixed(2));
    }else
    {
      var tot = 0;
      var des = parseFloat($('#txt_des').val());
      var iva = 0;
      for (var i = 0; i <=item ; i++) {
            // console.log(i);
            $('#txt_des_lin_'+i).attr("readonly", true);
            calcular_totales(i);
            if($('#txt_tot_lin_'+i).length)
            {
              
            // $('#txt_des_lin_'+i).val(0);
              tot = parseFloat($('#txt_tot_lin_'+i).val())+tot;
              iva+=parseFloat($('#txt_iva_lin_'+i).val());
            }
      }

      // console.log(iva);
      $('#txt_sub_tot').val(tot.toFixed(2));
      var des_t = ((tot*des)/100);
      $('#txt_tot_des').val(des_t.toFixed(2));
      $('#txt_tot_iva').val(iva.toFixed(2));


    }
    var sub = parseFloat($('#txt_sub_tot').val());
    var des = parseFloat($('#txt_tot_des').val());
    var iva = parseFloat($('#txt_tot_iva').val());
    $('#txt_pre_tot').val(((sub-des)+iva).toFixed(2));
  }

  function validar_pvp_costo(i)
  {
    var costo = $('#txt_pre_lin_'+i).val();
    var pvp = $('#txt_uti_lin_'+i).val();
    // console.log(costo);
    // console.log(pvp);
    if(parseFloat(pvp)< parseFloat(costo))
    {
      Swal.fire('','Precio de PVP debe ser mayor al costo.','error'); 
      $('#txt_uti_lin_'+i).focus();
      $('#txt_uti_lin_'+i).val(parseFloat(costo)+0.01);           
    }

  }

  function limpiar()
  {
    $('#txt_precio').val(0);
    $('#txt_cant').val(1);
    $('#txt_descuento').val(0);
    $('#txt_importe').val(0);
    $('#txt_precio').val(0);
    // $('#txt_precio').val(0);
    $("#ddl_referencia").empty();
    $("#ddl_descripcion").empty();
    $("#txt_iva").val(0);
  }

  function generar_factura(fecha)
  {

    // if($('#txt_neg').val()=='true')
    // {
    //   Swal.fire('','Tiene Stocks en negativos Ingrese el producto faltante.','info'); 
    //   return false;
    // }

    $('#myModal_espera').modal('show');    
    var orden = $('#txt_pedido').val();
    var ruc= $('#ddl_paciente').val();
    var area= $('#ddl_areas').val();
    var his= $('#txt_codigo').val();
    var nombre=  $('#ddl_paciente option:selected').text();

    var reg=  $('#txt_num_lin').val();
     $.ajax({
      data:  {orden:orden,ruc:ruc,area:area,nombre:nombre,fecha:fecha},
      url:   '../controlador/farmacia/ingreso_descargosC.php?facturar=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {    
      $('#myModal_espera').modal('hide');    
        if(response.resp==1)
        {
          cargar_pedido();
          Swal.fire({
            title: 'Comprobante '+response.com+' generado.',
            icon:'success',
            showDenyButton: false,
            showCancelButton: false,
            allowOutsideClick:false,
            confirmButtonText: `OK`,
            denyButtonText: `Don't save`,
          }).then((result) => {

              var  mod =ModuloActual;
              if (result.isConfirmed) {

                if(reg==0)
                {
                var url="../vista/inicio.php?mod="+mod+"&acc=ingresar_descargos&area="+area+"-"+pro+"&num_ped="+orden+"&cod="+his;
                }else
                {
                  var url="../vista/inicio.php?mod="+mod+"&acc=ingresar_descargos&area="+area+"-"+pro+"&num_ped="+orden+"&cod="+his;
                }
                $(location).attr('href',url);             
                  } else
                  {
                    if(reg==0)
                {
                 var url="../vista/inicio.php?mod="+mod+"&acc=ingresar_descargos&area="+area+"-"+pro+"&cod="+his;
                }else
                {
                  var url="../vista/inicio.php?mod="+mod+"&acc=ingresar_descargos&area="+area+"-"+pro+"&num_ped="+orden+"&cod="+his;
                }
                
                   
                  $(location).attr('href',url);
                  }
            })    


        }else if(response.resp==-3)
        {
          Swal.fire('','Esta salida tiene mas de una fecha','info'); 
          cargar_pedido();
        }
        else
        {
          Swal.fire('','No se pudo generado.','info'); 
        }
      }
    });

  }

  function cambiar_procedimiento()
  {
    $('#modal_procedimiento').modal('show');
  }

  function guardar_new_pro()
  {

    $('#modal_procedimiento').modal('show');
    var orden = $('#txt_pedido').val();
    var new_pro = $('#txt_new_proce').val();
    if(orden!='')
    {
      cambiar_proce_orden(new_pro);
    }else
    {
      $('#txt_procedimiento').val(new_pro);
    $('#modal_procedimiento').modal('hide');
    }
  }

  function cambiar_proce_orden(pro)
  {
    var parametros=
    {
      'text':pro,
      'ped':$('#txt_pedido').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/ingreso_descargosC.php?edi_proce=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
          cargar_pedido();
          $('#modal_procedimiento').modal('hide');
        }else
        {

        }
      }
    });
  }

  // function generar_informe()
  // {
  //   var url =  '../controlador/farmacia/ingreso_descargosC.php?imprimir_pdf=true',
  //  var datos = ;
  //   window.open(url+datos, '_blank');
  //    $.ajax({
  //        data:  {datos:datos},
  //        url:   url,
  //        type:  'post',
  //        dataType: 'json',
  //        success:  function (response) {  
          
  //         } 
  //      });
  // }



   function num_comprobante()
   {
    var fecha = $('#txt_fecha').val();
     $.ajax({
       data:  {fecha:fecha},
      url:   '../controlador/farmacia/articulosC.php?num_com=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        $('#num').text(response);
      }
    });

   }

   function mayorizar_inventario()
  {
    $('#myModal_espera').modal('show');
     $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/farmacia/ingreso_descargosC.php?mayorizar=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        $('#myModal_espera').modal('hide');
        Swal.fire('Mayorizacion completada','','success');
      
      }
    });
  }

  function validar_area()
  {
    Swal.fire({
      title: 'Quiere Cambiar el area de descargo?',
      text: "Si desea cambiar el area de descargo debera realizar un nuevo ingreso!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Generar nuevo registro'
    }).then((result) => {
        if (result.value) {
          location.href = 'inicio.php?mod='+ModuloActual+'&acc=vis_descargos';
        }else
        {
          location.reload();
        }
    })
  }
