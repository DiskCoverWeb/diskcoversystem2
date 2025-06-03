   var tbl_devoluciones = null;
   $( document ).ready(function() {
    var num_li=0;
    autocopletar_solicitante();
   	autocoplet_pro();
   	 autocoplet_area();
    num_comprobante();
    lista_devolucion();
    autocoplet_cc();

  });



function lista_devolucion222()
  {   
    var comprobante = cod;  
      $('#txt_orden').val(comprobante);
      $('#ddl_areas').append($('<option>',{value: sub, text: no,selected: true }));
      $('#ddl_cc').append($('<option>',{value: cc, text: cc_no,selected: true }));
    if(sub!='')
    {
       $('#ddl_areas').prop('disabled', true);
    }
   

     $.ajax({
      data:  {comprobante:comprobante},
      url:   '../controlador/farmacia/devoluciones_insumosC.php?lista_devolucion_dep=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {
        $('#tbl_devoluciones').html('<img src="../../img/gif/loader4.1.gif" width="30%">');        
      },
      success:  function (response) { 
        $('#tbl_devoluciones').html(response.tr);
        $('#lineas').val(response.lineas)
        // if(response.lineas==0)
        // {
        //    location.href='../vista/farmacia.php?mod=Farmacia&acc=devoluciones_departamento&acc1=Devolucion%20por%20departamentos&b=1&po=sub'
        // }
      }
    });
  }

function lista_devolucion()
{
   var comprobante = cod;  
      $('#txt_orden').val(comprobante);
      $('#ddl_areas').append($('<option>',{value: sub, text: no,selected: true }));
      $('#ddl_cc').append($('<option>',{value: cc, text: cc_no,selected: true }));
    if(sub!='')
    {
       $('#ddl_areas').prop('disabled', true);
    }

   if(tbl_devoluciones!=null)
    {
      if ($.fn.DataTable.isDataTable('#tbl_devoluciones')) {
        $('#tbl_devoluciones').DataTable().destroy();
      }
    }
    tbl_devoluciones = $('#tbl_devoluciones').DataTable({
          scrollX: true,
          searching: false,
          responsive: false,
          // paging: false,   
          info: false,   
          autoWidth: false,   
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
          url:   '../controlador/farmacia/devoluciones_insumosC.php?lista_devolucion_dep=true',
          type: 'POST',  // Cambia el método a POST   
          data: function(d) {
            
              return { comprobante: comprobante };
          },   
          dataSrc: function(json) {
            // console.LOG(json)
            $('#lineas').val(json.lineas)
            return json.tr;
          }             
        },
         scrollX: true,  // Habilitar desplazamiento horizontal
         columnDefs: [
           { width: "150px", targets: 1 },  // Ajusta la columna 0 (la primera)
          { width: "200px", targets: 2 }  // Ajusta la columna 0 (la primera)
          ],     
        columns: [
          { data: null,
            render: function(data, type, item) {
             return `
                    <button class="btn btn-danger btn-sm p-1" title="Ver detalle" onclick="Eliminar('`+comprobante+`','${item.CODIGO}','${item.A_No}')">
                        <span class="bx bx-trash me-0"></span>
                    </button>`;
            }
          },
          { data:'CODIGO'},
          { data:'PRODUCTO'},
          { data:'CANTIDAD'},
          { data:'VALOR UNITARIO'},
          { data:'VALOR TOTAL'},
          { data: null,
            render: function(data, type, item) {
              return formatoDate(data.FECHA.date)      
            }
          },
          { data:'Area'}, 
          { data:'A_No'}, 
          { data:'ORDEN'},       
        ],
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


  function calcular()
  {
     var cant = $('#txt_cant').val();
     var prec = $('#txt_precio').val();
     if(cant==0 || cant=='')
     {
      cant =1;
     }
     if(prec=='')
     {
       prec=0;
     }

     var t = parseFloat(cant*prec);
     $('#txt_total').val(t.toFixed(2));


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
        var num = Math.floor((Math.random() * (10-0))+0);
        console.log(num);
        var ped = cod;
        if(ped=='')
        {
          $('#txt_orden').val(response+''+num);
        }
      }
    });

   }


   function guardar_devolucion()
   {
   	var com = $('#txt_orden').val();
    var are = $('#ddl_areas').val();
    var cc_nom = $('#ddl_cc option:selected').text();
    var cc = $('#ddl_cc').val();
    var cc = cc.split('-');
    var cc = cc[0];
    var nom_a = $('#ddl_areas option:selected').text();
    var parametros = 
    {
      'codigo':$('#txt_codigo').val(),
      'producto':$('#ddl_producto option:selected').text(),
      'cantidad':$('#txt_cant').val(),
      'precio':$('#txt_precio').val(),
      'total':$('#txt_total').val(), 
      'area':are,
      'comprobante': com,
      'linea': $('#lineas').val(),
      'cc':cc,
      'solicitante':'.', //$('#txt_soli').val(),
    }
    if( $('#txt_cant').val() == 0 || $('#ddl_producto').val()=='' || $('#ddl_areas').val() =='' || com=='' || cc=='' || $('#txt_soli').val()=='')
    {
      Swal.fire('Asegurese de llenar todos os campos','','info');
      return false;
    }

    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/farmacia/devoluciones_insumosC.php?guardar_devolucion_departamentos=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
         if(response==1)
         {
          lista_devolucion();
          Swal.fire('Agregado a lista de devoluciones','','success');
          if($('#lineas').val()==0)
          {
          var mod = ModuloActual; 
          location.href='../vista/inicio.php?mod='+mod+'&acc=devoluciones_departamento&comprobante='+com+'&subcta='+are+'&area='+nom_a+'&centroc='+cc+'&cc_no='+cc_nom; 
          }else{
          lista_devolucion();
          }
          // cargar_pedido();
         }
        }
      });

   }

    function Eliminar(comp,codigo,No)
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
         Eliminar_linea(comp,codigo,No)
        }
      })
  }

   function Eliminar_linea(comp,codigo,No)
   {
    var parametros = 
    {
      'codigo':codigo,
      'comprobante': comp,
      'No':No,  
    }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/farmacia/devoluciones_insumosC.php?eliminar_linea_dev_dep=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
         if(response==1)
         {
          Swal.fire('Devolucion eliminada','','success');
          lista_devolucion();
          // cargar_pedido();
         }
        }
      });

   }

  function generar_factura(numero)
   {
    var prove = $('#ddl_areas').val();
    $('#myModal_espera').modal('show');  
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

          lista_devolucion();
          Swal.fire({
            icon: 'success',
            title: 'Comprobante '+response.com+' generado.',
            confirmButtonText: 'OK!',
            allowOutsideClick: false,
          }).then((result) => {
            if (result.value) {
              var mod = ModuloActual;
               location.href='../vista/farmacia.php?mod='+mod+'&acc=devoluciones_departamento&acc1=Devolucion%20por%20departamentos&b=1&po=sub'
            }
          })

          // Swal.fire('Comprobante '+response.com+' generado.','','success'); 
          // lista_devolucion();
          // cargar_pedido();
        }else if(response.resp==-2)
        {
          Swal.fire('Asegurese de tener una cuenta Cta_Iva_Inventario.','','info'); 
        }else if(response.resp==-3)
        {
          Swal.fire('','Esta factura Tiene dos  o mas fechas','info'); 
          lista_devolucion();
          // cargar_pedido();
        }
        else
        {
          Swal.fire('','No se pudo generado.','info'); 
        }
      }
    });
     // console.log(datos);
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
  function cargar_detalles()
   {
     var id = $('#ddl_producto').val();
     console.log(id);
     var datos = id.split('_');
      $('#txt_codigo').val(datos[2]);
      $('#txt_stock').val(datos[9]);
      $('#txt_precio').val(datos[3]);
      $('#txt_cant').focus();

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
