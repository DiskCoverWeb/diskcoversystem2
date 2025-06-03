  $( document ).ready(function() {
    cargar_productos();
    autocoplet_fami_modal();
    autocoplet_fami();
    autocoplet_cta();
    autocoplet_pro();
    autocoplet_prov();
    autocoplet_prov_modal();

    autocoplet_cta_inv();
    autocoplet_cta_CV();
    autocoplet_cta_ventas();
    autocoplet_cta_vnt_0();
    autocoplet_cta_vnt_ant();
    num_comprobante();

    DCPorcenIva('txt_fecha', 'PorcIVA');
    $('#PorcIVA').prop('disabled',true);


  $("#subir_imagen").on('click', function() {
          if($('#file_img').val()=="")
          {
            Swal.fire(
                  '',
                  'Asegurese primero de colocar una imagen.',
                  'info')
            return false;
          }
        var formData = new FormData(document.getElementById("form_img"));
        $('#myModal_espera').modal('show');
        $.ajax({
            url:   '../controlador/farmacia/articulosC.php?Articulos_imagen=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType:'json',
         // beforeSend: function () {
         //        $("#foto_alumno").attr('src',"../../img/gif/proce.gif");
         //     },
            success: function(response) {
               if(response==-1)
               {
                 Swal.fire('Algo extraño a pasado intente mas tarde.','','error').then(function(){
                   $('#myModal_espera').modal('hide');
                 });

               }else if(response ==-2)
               {
                  Swal.fire('Asegurese que el archivo subido sea una imagen.','','error').then(function(){
                   $('#myModal_espera').modal('hide');
                 });
               }  else
               {
                 Swal.fire('Imagen Guardada.','','success').then(function(){
                   $('#myModal_espera').modal('hide');
                 });
               } 
            }
        });
    });



        $( "#txt_nombre_prove" ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                     url:   '../controlador/farmacia/articulosC.php?search=true',           
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                      console.log(data);
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
              console.log(ui.item);
                $('#txt_id_prove').val(ui.item.value); // display the selected text
                $('#txt_nombre_prove').val(ui.item.label); // display the selected text
                $('#txt_ruc').val(ui.item.CI); // save selected id to input
                $('#txt_direccion').val(ui.item.dir); // save selected id to input
                $('#txt_telefono').val(ui.item.tel); // save selected id to input
                $('#txt_email').val(ui.item.email); // save selected id to input
                return false;
            },
            focus: function(event, ui){
                 $('#txt_nombre_prove').val(ui.item.label); // display the selected text
                
                return false;
            },
        });



  });
  function nombres(nombre)
  {
    $('#txt_nombre_prove').val(nombre.ucwords());
  }


   function autocoplet_fami(){
      $('#ddl_familia').select2({
        placeholder: 'Seleccione una familia',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?familias=true',
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

  function autocoplet_cta_inv(){
      $('#ddl_cta_inv').select2({
        width: '86%',        
        dropdownParent: $('#Nuevo_producto'),
        placeholder: 'Seleccione cuenta Inventario',
        ajax: {
          url:   "../controlador/farmacia/articulosC.php?cuenta_asignar='1'",
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

    function autocoplet_cta_CV(){
      $('#ddl_cta_CV').select2({        
        width: '86%',        
        dropdownParent: $('#Nuevo_producto'),
        placeholder: 'Seleccione cuenta Inventario',
        ajax: {
          url:   "../controlador/farmacia/articulosC.php?cuenta_asignar='5','9'",
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

    function autocoplet_cta_ventas(){
      $('#ddl_cta_venta').select2({
        width: '86%',        
        dropdownParent: $('#Nuevo_producto'),
        placeholder: 'Seleccione cuenta Inventario',
        ajax: {
          url:   "../controlador/farmacia/articulosC.php?cuenta_asignar='4'",
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

    function autocoplet_cta_vnt_0(){
      $('#ddl_cta_ventas_0').select2({
        width: '86%',        
        dropdownParent: $('#Nuevo_producto'),
        placeholder: 'Seleccione cuenta Inventario',
        ajax: {
          url:   "../controlador/farmacia/articulosC.php?cuenta_asignar='4'",
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

    function autocoplet_cta_vnt_ant(){
      $('#ddl_cta_vnt_anti').select2({
        width: '86%',        
        placeholder: 'Seleccione cuenta Inventario',
        dropdownParent: $('#Nuevo_producto'),
        ajax: {
          url:   "../controlador/farmacia/articulosC.php?cuenta_asignar='4'",
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

  
  function autocoplet_fami_modal(){
      $('#ddl_familia_modal').select2({
        placeholder: 'Seleccione una familia',
        dropdownParent: $('#Nuevo_producto'),
        width:'100%',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?familias=true',
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
  function autocoplet_cta(){
      $('#ddl_cta').select2({
        placeholder: 'Seleccione una familia',
        dropdownParent: $('#Nuevo_producto'),
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?cuenta=true',
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

    function autocoplet_pro(){
      $('#ddl_producto').select2({
        placeholder: 'Seleccione una producto',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?autocom_pro=true',
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
      function autocoplet_prov(){
      $('#ddl_proveedor').select2({
        placeholder: 'Seleccione una proveedor',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?proveedores=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            if(data != -1)
            {
              return {
                results: data
              };
            }else
            {
              Swal.fire('','Defina una cuenta "Cta_Proveedores" en Cta_Procesos.','info');
            }
          },
          cache: true
        }
      });
   
  }


   function autocoplet_prov_modal(){
      $('#ddl_proveedor_modal').select2({
        placeholder: 'Seleccione una proveedor',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?proveedores=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            if(data != -1)
            {
              return {
                results: data
              };
            }else
            {
              Swal.fire('','Defina una cuenta "Cta_Proveedores" en Cta_Procesos.','info');
            }
          },
          cache: true
        }
      });
  }



   function cargar_productos()
   {
    var query = $('#txt_query').val();
    var pag =$('#txt_pag').val();
    var parametros = 
    {
      'query':query,
      'pag':pag,  // numero de registeros que se van a visualizar
      'fun':'cargar_productos' // funcion que se va a a ejecutar en el paginando para recargar
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/farmacia/articulosC.php?productos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        if(response)
        {
          $('#tbl_ingresados').html(response.tabla);
          $('#tbl_pag').html(response.pag);
           $('#A_No').val(response.item);
        }
      }
    });

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


   function guardar_producto()
   {
     var datos =  $("#form_nuevo_producto").serialize();
     if($('#ddl_cta_inv').val()==0 || $('#ddl_cta_CV').val()==0 || $('#ddl_cta_CV').val()==null || $('#ddl_cta_inv').val()==null)
     {
       Swal.fire('Asegurese que la cuenta de inventario y la cuenta de costo de venta esten seleccionados.','','info');   
      return false;
     }

     if($('#ddl_familia_modal').val()=='' || $('#txt_ref').val()=='' || $('#txt_nombre').val()=='' || $('#txt_max').val()=='' || $('#txt_min').val()=='' || $('#txt_reg_sanitario').val()=='')
     {
       Swal.fire('','Llene todo lso campos.','info');   
      return false;
     }

     $.ajax({
      data:  datos,
      url:   '../controlador/farmacia/articulosC.php?producto_nuevo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        
          $('#Nuevo_producto').modal('hide');
        if(response==1)
        {
          Swal.fire('Nuevo producto registrado.','','success'); 
          limpiar_nuevo_producto();         
        }
      }
    });
     // console.log(datos);
   }


   function generar_factura(numero,prove)
   {
    $('#myModal_espera').modal('show');  
     var fac = $('#txt_num_fac').val();
     var iva = $('#iva_'+numero).val();
     var parametros = 
     {
      'num_fact':numero,
      'prove':prove,
      'iva_exist':iva,
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
          cargar_productos();
        }else if(response.resp==-2)
        {
          Swal.fire('Asegurese de tener una cuenta Cta_Iva_Inventario.','','info'); 
        }else if(response.resp==-3)
        {
          Swal.fire('','Esta factura Tiene dos  o mas fechas','info'); 
          cargar_productos();
        }
        else
        {
          Swal.fire('','No se pudo generado.','info'); 
        }
      }
    });
     // console.log(datos);
   }

  function guardar_proveedor()
   {
     var datos =  $("#form_nuevo_proveedor").serialize();
     $.ajax({
      data:  datos,
      url:   '../controlador/farmacia/articulosC.php?proveedor_nuevo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response==1)
        {
           $('#txt_nombre_prove').val('');  
          limpiar_t();        
          $('#Nuevo_proveedor').modal('hide');
          Swal.fire('Proveedores Guardo.','','success'); 
        }else if(response==-2)
        {
          Swal.fire('El numero de Cedula o ruc ingresado ya esta en uso.','','info');  
        }
      }
    });

     // console.log(datos);
   }


   function cargar_detalles()
   {
     var id = $('#ddl_producto').val();
     console.log(id);
     var datos = id.split('_');
      $('#ddl_familia').append($('<option>',{value: datos[1], text:datos[0],selected: true }));
      $('#txt_referencia').val(datos[2]);
      $('#txt_existencias').val(datos[9]);
      $('#txt_ubicacion').val(datos[7]);
      $('#txt_precio_ref').val(datos[3]);
      $('#txt_unidad').val(datos[6]);
      if(datos[8]==0)
      {
        $('#rbl_no').prop('checked',true);
      }else
      {        
        $('#rbl_si').prop('checked',true);
      }
      $('#txt_reg_sani').val(datos[10]);
      $('#txt_max_in').val(datos[11]);
      $('#txt_min_in').val(datos[12]);
          
     // console.log(datos);
   }

   function limpiar()
   {
      $("#ddl_familia").empty();
      $("#ddl_descripcion").empty();
      $("#ddl_pro").empty();
   }

   function familia_modal()
   {
     var cta = $('#ddl_familia_modal').val();
     var parte = cta.split('-');
     buscar_ultimo(parte[0]);
     // $('#txt_ref').val(cta);
     console.log(cta);
   }

   function buscar_ultimo(cta)
   {
     $.ajax({
      data:  {cta:cta},
      url:   '../controlador/farmacia/articulosC.php?buscar_ultimo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        $('#txt_ref').val(response);
      }
    });

   }

   function agregar()
   {
    if($('#ddl_producto').val()=='' || $('#ddl_proveedor').val()=='' || $('#ddl_familia').val()=='' || $('#txt_precio').val()=='' || $('#txt_canti').val()=='' || $('#txt_serie').val()=='' || $('#txt_num_fac').val()=='' || $('#txt_fecha_ela').val()=='' || $('#txt_fecha_exp').val()=='' || $('#txt_reg_sani').val()=='' || $('#txt_procedencia').val()=='' || $('#txt_lote').val()==''|| $('#txt_descto').val()=='' )
    {
      Swal.fire('Llene todo los campos.','','info');   
      return false;
    }
     var datos =  $("#form_add_producto").serialize();
     $.ajax({
      data:  datos,
      url:   '../controlador/farmacia/articulosC.php?add_producto=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response==1)
        {
          // $('#Nuevo_proveedor').modal('hide');
          Swal.fire('Producto agregado.','','success');   
          $('#txt_descto').val(0)
          cargar_productos();         
        }
      }
    });
   }

   function eliminar_lin(linea,orden,pro)
   { 

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
              'lin':linea,
              'ord':orden,
              'pro':pro,
            }
             $.ajax({
              data:  {parametros:parametros},
              url:   '../controlador/farmacia/articulosC.php?lin_eli=true',
              type:  'post',
              dataType: 'json',
              success:  function (response) { 
                if(response==1)
                {
                  cargar_productos();
                }
              }
            });
        }
      });

   }

   function calculos()
   {
     let cant = parseFloat($('#txt_canti').val());
     let pre = parseFloat($('#txt_precio').val());
     let des = parseFloat($('#txt_descto').val());
     if($('#rbl_si').prop('checked'))
     {
      $('#PorcIVA').prop('disabled',false);
       let subtotal = pre*cant;//1*25.86=25.86
       let dscto = subtotal*(des/100);//0
       let IVA = $('#PorcIVA').val() / 100;

       let subT =  (subtotal-dscto);
       let iva_valor = subT*IVA       
       let total = subT+iva_valor ;

       let iva = parseFloat($('#txt_iva').val()); 
       $('#txt_subtotal').val(subT.toFixed(2));
       $('#txt_total').val(total.toFixed(4));
       $('#txt_iva').val((iva_valor).toFixed(4));

     }else
     {
      //disabled PorcIVA
      $('#PorcIVA').prop('disabled',true);
      $('#txt_iva').val(0);
       let iva = parseFloat($('#txt_iva').val());       
       let sub = (pre*cant);
       let dscto = (sub*des)/100;

       let total = (sub-dscto);
       $('#txt_subtotal').val(sub-dscto);
       $('#txt_total').val(total);
     }
   }

   function limpiar_cta(cta)
   {
     $('#'+cta).empty();
   }

   function limpiar_nuevo_producto()
   {
     $('#ddl_cta_inv').empty();
     $('#ddl_cta_CV').empty();
     $('#ddl_cta_venta').empty();
     $('#ddl_cta_ventas_0').empty();
     $('#ddl_cta_vnt_anti').empty();
     $('#ddl_familia_modal').empty();
     $('#txt_ref').val('');
     $('#txt_nombre').val('');
     $('#txt_max').val('');
     $('#txt_min').val('');
     $('#txt_reg_sanitario').val('');
     $('#txt_cod_barras').val('');
   }
   function eliminar_todo(fac,prov)
   {
      Swal.fire({
      title: 'Quiere eliminar  toda la factura ingresada?',
      text: "Esta seguro de eliminar esta factura!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
            var parametros=
            {
              'orden':fac,
              'pro':prov
            }
             $.ajax({
              data:  {parametros:parametros},
              url:   '../controlador/farmacia/articulosC.php?eliminar_ingreso=true',
              type:  'post',
              dataType: 'json',
              success:  function (response) { 
                if(response==1)
                {
                  cargar_productos();
                }
              }
            });
        }
      });
   }

   function subir(orden,prov)
   {
     $('#txt_nom_img').val(orden+'_'+prov);
     $('#modal_de_foto').modal('show');
   }


   function limpiar_t()
   {
     var nom = $('#txt_nombre_prove').val();
     if(nom=='')
     {
       $('#txt_id_prove').val(''); // display the selected text
       $('#txt_nombre_prove').val(''); // display the selected text
       $('#txt_ruc').val(''); // save selected id to input
       $('#txt_direccion').val(''); // save selected id to input
       $('#txt_telefono').val(''); // save selected id to input
       $('#txt_email').val('');
     }
   }
   function cargar_datos_prov()
   {
     var pro = $('#ddl_proveedor option:selected').text();
     $('#lbl_nom_comercial').text(pro);
   }

   function abrir_modal()
   {
     $('#Nuevo_producto').modal('show');
   }