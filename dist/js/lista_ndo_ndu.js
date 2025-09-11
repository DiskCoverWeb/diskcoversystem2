var cartera_usu = '';
var cartera_pass = '';
var tipo = '';

  $(document).ready(function()
  {
    if($('#hidden_cartera_usu').val() != '')
    {
        cartera_usu = $('#hidden_cartera_usu').val();
        cartera_pas = $('#hidden_cartera_pass').val();
    }

    if($('#hidden_tipo').val() != '' && $('#hidden_tipo').val()==2)
    {
        tipo=2;
    }

    // tbl_ndo_ndu = $('#tbl_tabla').DataTable({
    //     // responsive: true,
    //     language: {
    //         url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
    //     }
    // });

    $.ajax({
      url: '../controlador/facturacion/lista_ndo_nduC.php?sesion=true',
      type:  'GET',
      dataType: 'json',
       success:  function (response) { 
        console.log(response);
        
       }
        
    });
    catalogoLineas();
    //console.log();
    // fin paginacion

    if(cartera_usu!='')
    {
      //buscar_cliente(cartera_usu);
      periodos(cartera_usu);
      rangos();
      $('#txt_clave').val(cartera_pas);
      $('#ddl_cliente').attr('disabled',true);
      $('#ddl_grupo').attr('disabled',true);
      $('#txt_clave').attr('readonly',true);
    }
    //var tipo = '<?php echo $tipo; ?>';
    //autocmpletar_cliente();
    if(tipo==2)
    {
      autocmpletar_cliente_tipo2();
      $('#campo_clave').css('display','none');      
      $('#mes').css('display','none');
      $('#ticket').css('display','none');
      $('#tab_4_').css('display', 'none');
    }else
    {
      $('#tab_2_').css('display', 'none');
      $('#tab_3_').css('display', 'none');
      $('#tab_4_').css('display', 'block');
    }

  	// cargar_registros();
  	autocmpletar();    
    /*paginacion('cargar_registros','panel_pag');
    paginacion('cargar_registrosAu','panel_pagAu',0,50,'1');
    paginacion('cargar_registrosAu','panel_pagNoAu',0,50,'2');*/

  	$('#ddl_grupo').on('select2:select', () => {
      catalogoLineas();
    })
  });

  $.ajax({
      type: "GET",      
      //dataType: 'json',
      url: '../controlador/facturacion/lista_ndo_nduC.php?sesion=true',
      //data: {parametros:parametros }, 
      success: function(data)
      {
        if(data!='')
        {
          //$('#ddl_periodo').html(data);
          //console.log(data);
        }
      }
    });


  function periodos(codigo){
    var parametros = 
    {
      'codigo':codigo,
    }
    $.ajax({
      type: "POST",      
      dataType: 'json',
      url: '../controlador/facturacion/lista_ndo_nduC.php?perido=true',
      data: {parametros:parametros }, 
      success: function(data)
      {
        if(data!='')
        {
          $('#ddl_periodo').html(data);
        }
      }
    });
  }


  function autocmpletar(){
      $('#ddl_grupo').select2({
        placeholder: 'Seleccione grupo',
        width:'resolve',
	    // minimumResultsForSearch: Infinity,
        ajax: {
          url: '../controlador/facturacion/lista_ndo_nduC.php?grupos=true',
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

  function autocmpletar_cliente(){
  	   var g = $('#ddl_grupo').val();
       var s = $('#DCLinea').val();
        if(s!='' && s!='.')
        {
          s = s.split(' ');
          s = s[1];
        }else
        {
          s = '';
        }
      $('#ddl_cliente').select2({
        placeholder: 'Seleccione Cliente',
        width:'resolve',
	    // minimumResultsForSearch: Infinity,
        ajax: {
          url: '../controlador/facturacion/lista_ndo_nduC.php?clientes2=true&g='+g+'&s='+s,
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

   function autocmpletar_cliente_tipo2(){
       var g = $('#ddl_grupo').val();
       var s = $('#DCLinea').val();
        if(s!='' && s!='.')
        {
          s = s.split(' ');
          s = s[1];
        }else
        {
          s = '';
        }
      $('#ddl_cliente').select2({
        placeholder: 'Seleccione Cliente',
        width:'resolve',
      // minimumResultsForSearch: Infinity,
        ajax: {
          url: '../controlador/facturacion/lista_ndo_nduC.php?clientes2=true&g='+g+'&s='+s,
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


  function buscar_cliente(ci_ruc)
  {
     var g = $('#ddl_grupo').val();
     $.ajax({
       // data:  {parametros:parametros},
      url: '../controlador/facturacion/lista_ndo_nduC.php?clientes2=true&q='+ci_ruc+'&g='+g+'&cartera=1',         
      type:  'post',
      dataType: 'json',
       success:  function (response) { 
        console.log(response);
           if(response.length==0)
            {
              Swal.fire('Cliente no apto para facturar <br> Asegurese que el cliente este asignado a facturacion','asegurece que FA = 1','info').then(function()
                {
                  location.href = '../vista/modulos.php';
                });
            }
          $('#ddl_cliente').append($('<option>',{value: response[0].id, text:response[0].text,selected: true }));
          $('#lbl_cliente').text(response[0].data.Cliente);
          $('#lbl_ci_ruc').text(response[0].data.CI_RUC);
          $('#lbl_tel').text(response[0].data.Telefono);
          $('#lbl_ema').text(response[0].data.Email);
          $('#lbl_dir').text(response[0].data.Direccion);  
          $('#panel_datos').css('display','block'); 
      }
    });
  }


   function catalogoLineas(){
    let fechaEmision = fecha_actual();
    let fechaVencimiento = fecha_actual();
    let tipo = $('#ddl_grupo').val();

    let parametros = {
      fechaVencimiento : fechaVencimiento,
      fechaEmision : fechaEmision,
      tipo : tipo
    }

    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/lista_ndo_nduC.php?catalogo=true',
      data: {'parametros': parametros},      
      dataType:'json', 
      success: function(data)             
      {
        //console.log(data);
        if (data.length>0) {
          datos = data;
          // Limpiamos el select
          //console.log(datos);
          $("#DCLinea").find('option').remove();
          if(data.length>1)
          {
            $("#DCLinea").append('<option value="">Todos</option>');
          }
          data.forEach(function(item,i){
            serie = item.id.split(' ');
            serie = serie[1];
             $("#DCLinea").append('<option value="' + item.id +" "+item.text+ ' ">' + serie + '</option>');

            // console.log(item);
             // console.log(i);
          })
        }else{
          Swal.fire({
            type:'info',
            title: 'Usted no tiene un punto de venta asignado o esta mal configurado, contacte con la administracion del sistema',
            text:'',
            allowOutsideClick: false,
          }).then(()=>{
            console.log('ingresa');
                location.href = '../vista/modulos.php';
              });

        }  
        // cargar_registros();       
      }
    });
  }


  function cargar_registros()
   {
   
    var per = $('#ddl_periodo').val();
    var serie = $('#DCLinea').val();
    if(serie!='' && serie!='.')
    {
     var serie = serie.split(' ');
     var serie = serie[1];
    }else
    {
      serie = '';
    }
    //var tipo = '<?php echo $tipo; ?>'
    
    if ($.fn.DataTable.isDataTable('#tbl_tabla')) {
        $('#tbl_tabla').DataTable().destroy();
    }

    tbl_ndo_ndu = $('#tbl_tabla').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        columnDefs: [
            { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
        ],
        ajax: {
            url: '../controlador/facturacion/lista_ndo_nduC.php?tabla=true',
            type: 'POST',  // Cambia el método a POST    
            data: function(d) {
                var parametros = {
                  'ci':$('#ddl_cliente').val(),
                  'per':per,      
                  'desde':$('#txt_desde').val(),
                  'hasta':$('#txt_hasta').val(),
                  'tipo':tipo,
                  'serie':serie,
                };
                return { parametros: parametros };
            },
            dataSrc: '',             
        },
          scrollX: true,  // Habilitar desplazamiento horizontal
  
        columns: [
            { data: null,
                render: function(data, type, item) {
                      email = '';
                      if (item.Email != '.' && item.Email != '') {
                        email+= item.Email+',';
                      }
                      if (item.EmailR != '.' && item.EmailR != '') {
                        email+= item.EmailR+',';
                      }
                      if (item.Email2 != '.' && item.Email2 != '') {
                        email+= item.Email2+ ',';
                      }
                  options=`<li>
                              <a href="#" class="dropdown-item" onclick="Ver_nd('${item.Factura}','${item.Serie}','${item.CodigoC}','${item.Autorizacion}','${item.TC}')"><i class="bx bxs-printer"></i> Impresora</a>
                          </li>
                          <li>
                              <a href="#" class="dropdown-item" onclick="Ver_nd_pdf('${item.Factura}','${item.Serie}','${item.CodigoC}','${item.Autorizacion}','${item.TC}')"><i class="bx bxs-file-pdf"></i> PDF</a>
                          </li>
                          <li>
                            <a href="#" class="dropdown-item" onclick=" modal_email_fac('${item.Factura}','${item.Serie}','${item.CodigoC}','${email}','${item.Autorizacion}','${item.TC}')"><i class="bx bx-envelope"></i> Enviar por email</a>
                          </li>
                          <li>
                              <a href="#" class="dropdown-item" onclick="descargar_fac('${item.Factura}','${item.Serie}','${item.CodigoC}','${item.Autorizacion}','${item.TC}')"><i class="bx bx-download"></i> Descargar Factura</a>
                          </li>`;
                  if (item.ExisteSerie =='Si'  && item.Autorizacion.length == 13 && tipo != '') 
                  {
                      options+=`<li>
                        <a href="#" class="dropdown-item" onclick="autorizar('${item.TC}','${item.Factura}','${item.Serie}','${formatoDate(item.Fecha.date)}','${email}')" ><i class="bx bx-paper-plane"></i>Autorizar</a>
                        </li>`;
                  }
                  if (item.T != 'A' && tipo != '') 
                  {
                        options+=`<li><a href="#" class="dropdown-item" onclick="anular_factura('${item.Factura}','${item.Serie}','${item.CodigoC}')"><i class="bx bx-x"></i>Anular nota de donación</a></li>`;
                  }
                  
                  return `<div class="input-group-btn">
                              <button type="button" class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Acciones</button>
                              <ul class="dropdown-menu">`+options
                                
                              options+=`</ul>
                          </div>`;                    
                }
            },
            { data: 'T' },
            { data: 'Razon_Social' },
            { data: 'TC' },
            { data: 'Serie' },
            { data: 'Autorizacion' },
            { data: 'Factura' },
            { data: 'Fecha.date',  
                render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                }
            },
            { data: 'SubTotal', render: function(data, type, item){return parseFloat(data).toFixed(2)}},
            { data: 'Con_IVA', render: function(data, type, item){return parseFloat(data).toFixed(2)}},
            { data: 'IVA', render: function(data, type, item){return parseFloat(data).toFixed(2)}},
            { data: 'Descuentos', render: function(data, type, item){return parseFloat(data).toFixed(2)}},
            { data: 'Total', render: function(data, type, item){return parseFloat(data).toFixed(2)}},
            { data: 'Saldo', render: function(data, type, item){return parseFloat(data).toFixed(2)}},
            { data: 'RUC_CI' },
            { data: 'TB' }
        ],
        order: [
            [1, 'desc']
        ]
    });
  }

   function cargar_lineas()
   {   
    var per = $('#ddl_periodo').val();
    var serie = $('#DCLinea').val();
    if(serie!='' && serie!='.')
    {
     var serie = serie.split(' ');
     var serie = serie[1];
    }else
    {
      serie = '';
    }
    //var tipo = '<?php echo $tipo; ?>'
    var parametros = 
    {
      'ci':$('#ddl_cliente').val(),
      'per':per,      
      'desde':$('#txt_desde').val(),
      'hasta':$('#txt_hasta').val(),
      'tipo':tipo,
      'serie':serie,
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/facturacion/lista_ndo_nduC.php?tabla_lineas=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {
        $("#tbl_tabla_detalle").html('<tr class="text-center"><td colspan="16"><img src="../../img/gif/loader4.1.gif" width="20%">');
      },
       success:  function (response) { 
        // console.log(response);
       $('#tbl_tabla_detalle').html(response);
       setTimeout(()=>{
					$('#myModal_espera').modal('hide');
				}, 1000)
      }
    });

   }


   function cargar_registrosAu(AU)
   {
   
    var per = $('#ddl_periodo').val();
    var serie = $('#DCLinea').val();
    if(serie!='' && serie!='.')
    {
     var serie = serie.split(' ');
     var serie = serie[1];
    }else
    {
      serie = '';
    }
    //var tipo = '<?php echo $tipo; ?>'
    var parametros = 
    {
      'ci':$('#ddl_cliente').val(),
      'per':per,      
      'desde':$('#txt_desde').val(),
      'hasta':$('#txt_hasta').val(),
      'tipo':tipo,
      'serie':serie,
      'auto':AU,
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/facturacion/lista_ndo_nduC.php?tablaAu=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {
         if(AU==1)
        {         
          $("#tbl_tablaAu").html('<tr class="text-center"><td colspan="16"><img src="../../img/gif/loader4.1.gif" width="20%">');
        }else
        {
           $("#tbl_tablaNoAu").html('<tr class="text-center"><td colspan="16"><img src="../../img/gif/loader4.1.gif" width="20%">');
        }
       
      },
       success:  function (response) { 
        // console.log(response);
        if(AU==1)
        {
          $('#tbl_tablaAu').html(response);
        }else
        {
          $('#tbl_tablaNoAu').html(response);
        }
       setTimeout(()=>{
					$('#myModal_espera').modal('hide');
				}, 1000)
      }
    });

   }

  function Ver_nd(id,serie,ci,aut,tc)
	{

    $('#myModal_espera').show();		 
    var peri = $('#ddl_periodo').val();
    var url = '../controlador/facturacion/lista_ndo_nduC.php?ver_fac=true&codigo='+id+'&ser='+serie+'&ci='+ci+'&per='+peri+'&auto='+aut+'&tc='+tc;
    var html='<iframe style="width: 48mm; height: 100vh; border: none;" src="'+url+'&pdf=no" frameborder="0" allowfullscreen id="re_ticket"></iframe>';
    $('#re_frame').html(html);
    $('#myModal_espera').hide();
    document.getElementById('re_ticket').contentWindow.print();

    // $.ajax({
		// 	type: "GET",
		// 	//data: { parametros: parametros },
		// 	//dataType: 'json',
		// 	success: function (data) {
    //     setTimeout(()=>{
		// 			$('#myModal_espera').modal('hide');
		// 		}, 1000)
    //     let datajson = JSON.parse(data);
    //     if(datajson['respuesta'] == 1){
    //       var url = '../../TEMP/' + datajson['pdf'] + '.pdf';
    //       //window.open(url, '_blank');

    //       const iframe = document.getElementById('pdfFrame');
    //       iframe.src = url;
          
    //       iframe.onload = function () {
    //           iframe.contentWindow.print();
    //       };

    //     }else{
    //       Swal.fire('Error', 'Hubo un problema al mostrar la factura.');  
    //     }
    //   },
    //   error: function (err) {
    //     $('#myModal_espera').hide();
    //     Swal.fire('Error', 'Hubo un problema al mostrar la factura.');
    //   }
    // });
		/*var url = '../controlador/facturacion/lista_ndo_nduC.php?ver_fac=true&codigo='+id+'&ser='+serie+'&ci='+ci+'&per='+peri+'&auto='+aut+'&tc='+tc;		
		window.open(url,'_blank');*/
	}

  function Ver_nd_pdf(id,serie,ci,aut,tc)
	{		 
    $('#myModal_espera').modal('show');
    peri = $('#ddl_periodo').val();
    $.ajax({
			type: "GET",
			url: '../controlador/facturacion/lista_ndo_nduC.php?ver_fac_pdf=true&codigo='+id+'&ser='+serie+'&ci='+ci+'&per='+peri+'&auto='+aut+'&tc='+tc,
			//data: { parametros: parametros },
			//dataType: 'json',
			success: function (data) {
        setTimeout(()=>{
					$('#myModal_espera').modal('hide');
				}, 1000)
        let datajson = JSON.parse(data);
        if(datajson['respuesta'] == 1){
          var url = '../../TEMP/' + datajson['pdf'] + '.pdf';
          window.open(url, '_blank');
        }else{
          Swal.fire('Error', 'Hubo un problema al mostrar la factura.');  
        }
      },
      error: function (err) {
        $('#myModal_espera').hide();
        Swal.fire('Error', 'Hubo un problema al mostrar la factura.');
      }
    });
		/*var url = '../controlador/facturacion/lista_ndo_nduC.php?ver_fac=true&codigo='+id+'&ser='+serie+'&ci='+ci+'&per='+peri+'&auto='+aut+'&tc='+tc;		
		window.open(url,'_blank');*/
	}

  function autorizar(tc,factura,serie,fecha,email)
  { 
    $('#myModal_espera').modal('show');
    var parametros = 
    {
      'tc':tc,
      'FacturaNo':factura,
      'serie':serie,
      'Fecha':fecha,
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/facturacion/lista_ndo_nduC.php?re_autorizar=true',
      type:  'post',
      dataType: 'json',
       success:  function (data) {
       

    // setTimeout(()=>{
				// 	$('#myModal_espera').modal('hide');
				// }, 1000)
    //    if(response==1)
    //    {
    //      Swal.fire('Factura autoizada','','success').then(function()
    //      {
    //        cargar_registros();
    //      })
    //    }else if(response == 2)
    //    {
    //     Swal.fire('Error al enviar el comprobante estado : Revisar la carpeta de rechazados','','error')
    //    }else if(response==-1)
    //    {
    //     Swal.fire('Comprobante devuelto : Revisar la carpeta de rechazados','','error')
    //    }else{        
    //     Swal.fire(response,'','error')
    //    }

      setTimeout(()=>{
        $('#myModal_espera').modal('hide');
      }, 1000)
      // console.log(data);
      if(data.respuesta==1)
      { 
        Swal.fire({
          type:'success',
          title: 'Factura Procesada y Autorizada',
          confirmButtonText: 'Ok!',
          allowOutsideClick: false,
        }).then(function(){

            nombre_pdf = [data.pdf+'.pdf'];
            clave_Acceso = [data.clave+'.xml'];
            enviar_email_comprobantes(nombre_pdf,clave_Acceso,email);

        })
      }else if(data.respuesta==-1)
      {
        if(data.text==2 || data.text==null)
          {

          Swal.fire('XML devuleto','XML DEVUELTO','error').then(function(){ 
            // var url=  '../../TEMP/'+data.pdf+'.pdf';    window.open(url, '_blank');             
          }); 
            tipo_error_sri(data.clave);
          }else
          {
            Swal.fire({
             type: 'error',
             title: 'XML DEVUELTO',
             html: `<div style="width: 100%; color:black;font-weight: 400;font-size: 1.525em;">${data.text}</div>`
           });
          }
      }else if(data.respuesta==2)
      {
        tipo_error_comprobante(clave)
        Swal.fire('XML devuelto','','error'); 
        tipo_error_sri(data.clave);
      }
      else if(data.respuesta==4)
      {
        Swal.fire('SRI intermitente intente mas tarde','','info');  
      }else
      {
        if(data==-1)
        {
           Swal.fire('Revise CI_RUC de factura en base','Cliente no encontrado','info');
         }else{
          Swal.fire('XML devuelto por:'+data.text,'','error');  
        }
      }


      },
      error: function () {
        setTimeout(()=>{
					$('#myModal_espera').modal('hide');
				}, 1000)
        alert("Ocurrio un error inesperado, por favor contacte a soporte.");
      }
    });
    
}

function enviar_email_comprobantes(nombre_pdf,clave_Acceso,email)
{
    $('#myModal_envio_Email').modal('show');
    var parametros = {
        'clave':clave_Acceso,
        'pdf':nombre_pdf,
        'correo':email,
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/punto_ventaC.php?enviar_email_comprobantes=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);
            $('#myModal_envio_Email').modal('hide');        
            if(data==1)
            {
                Swal.fire('Email enviado','','success').then(function(){
                    location.reload();
                });
            }   
        }
    });
}


  function tipo_error_sri(clave)
  {
    var parametros = 
    {
      'clave':clave,
    }
     $.ajax({
      type: "POST",
      url: '../controlador/facturacion/punto_ventaC.php?error_sri=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
        
         console.log(data);
        $('#myModal_sri_error').modal('show');
        $('#sri_estado').text(data.estado[0]);
        $('#sri_codigo').text(data.codigo[0]);
        $('#sri_fecha').text(data.fecha[0]);
        $('#sri_mensaje').text(data.mensaje[0]);
        $('#sri_adicional').text(data.adicional[0]);
        // $('#doc_xml').attr('href','')
      }
    });
  }


  function reporte_pdf()
  {  var cli = $('#ddl_cliente').val();
     var datos =  $("#filtros").serialize();
    if($('#tab_4_').hasClass('active'))
    {
      var url = '../controlador/facturacion/lista_ndo_nduC.php?imprimir_pdf_lineas=true&ddl_cliente='+cli+'&';  
    }else
    {    
     var url = '../controlador/facturacion/lista_ndo_nduC.php?imprimir_pdf=true&ddl_cliente='+cli+'&';  
    }    
      window.open(url+datos, '_blank');
  }
  function generar_excel()
	{		
    var cli = $('#ddl_cliente').val();
    var datos =  $("#filtros").serialize();
    if($('#tab_4_').hasClass('active'))
    {
      var url = '../controlador/facturacion/lista_ndo_nduC.php?imprimir_excel_fac_line=true&ddl_cliente='+cli+'&'+datos;
    
    }else{
	    var url = '../controlador/facturacion/lista_ndo_nduC.php?imprimir_excel_fac=true&ddl_cliente='+cli+'&'+datos;
	  }
    window.open(url);
    

	}
	function validar()
	{
		var cli = $('#ddl_cliente').val();
		var cla = $('#txt_clave').val();
    var tip = tipo;
    var ini = $('#txt_desde').val();
    var fin = $('#txt_hasta').val();
    var periodo = $('#ddl_periodo').val();
    //si existe periodo valida si esta en el rango
    if(periodo!='.')
    {
      const fechaInicio=new Date(periodo+'-01-01');
      const fechaFin=new Date(periodo+'-01-30');
      var ini = new Date(ini);
      var fin = new Date(fin);

      // console.log(fechaInicio)
      // console.log(fechaFin)
      // console.log(ini)
      // console.log(fin)

      if(ini>fechaFin || ini<fechaInicio)
      {
        Swal.fire('la fecha desde:'+ini,'No esta en el rango','info').then(function(){
           $('#txt_desde').val(periodo+'-01-01');
           return false;
        })
      }
      if(fin>fechaFin || fin<fechaInicio)
      {
        Swal.fire('la fecha hasta:'+fin,'No esta en el rango','info').then(function(){
           $('#txt_hasta').val(periodo+'-01-30');
           return false;
        })
      }

    }

    
      if(cli=='')
      {
        Swal.fire('Seleccione un cliente','','error');
        return false;
      }
    if(tip=='')
    {
  		if(cla=='')
  		{
  			Swal.fire('Clave no ingresados','','error');
  			return false;
  		}
    }
		var parametros = 
		{
			'cli':cli,
			'cla':cla,
      'tip':tip,
		}
		 $.ajax({
             data:  {parametros:parametros},
             url:   '../controlador/facturacion/lista_ndo_nduC.php?validar=true',
             type:  'post',
             dataType: 'json',
             success:  function (response) {
             if(response == 1)
             {
             	//$('#myModal_espera').modal('show');
             	cargar_registros();
              //cargar_registrosAu(1);
              //cargar_registrosAu(2);
             }else
             {
             	Swal.fire('Clave incorrecta.','Asegurese de que su clave sea correcta','error');
             }
          } 
        });
	}

 function recuperar_clave()
 {
 	$("#modal_email").modal('show');
 	var g = $('#ddl_grupo').val();
 	var cli = $('#ddl_cliente').val();
 	if(cli=='')
 	{
 		Swal.fire('Seleccione Cliente.','','error');
 		return false;
 	}
 	 var parametros = {  'ci':cli,'gru':g, }
     $.ajax({
      data:  {parametros:parametros},
      url: '../controlador/facturacion/lista_ndo_nduC.php?clientes_datos=true',
      type:  'post',
      dataType: 'json',
       success:  function (response) { 
       	console.log(response);
       	if(response.length >0)
       	{
       		var ema = response[0]['Email'];
       		if(ema!='' && ema !='.')
       		{
       			"intimundosa@hotmail.com"
       			var ini = ema.substring(0,4);
       			var divi = ema.split('@');
       			var num_car =  divi[0].substring(4).length;
       			// num_car = num_car
       			var medio = '';
       			for (var i = 0; i < num_car; i++) {
       				medio+='*';       				
       			}
       			var fin = divi[1];
       			// console.log(ini+medio+fin);

       		 $('#lbl_email').text(ini+medio+'@'+fin);
       		 $('#txt_email').val(ema);
       		 $('#btn_email').css('display','initial');
       		}else
       		{
       			$('#lbl_email').text('El usuario no tien un Email registrado contacte con la institucion');
       			$('#btn_email').css('display','none');
       			$('#txt_email').val('');
       		}
       	}else
       	{
       		$('#btn_email').css('display','none');
       		$('#lbl_email').text('El usuario no tien un Email registrado contacte con la institucion');
       		$('#txt_email').val('');
       	}

      }
    });
 }

 function enviar_mail()
 {
 	  var cli = $('#ddl_cliente').val();
    var ema = $('#txt_email').val();
 	  var parametros = {  'ci':cli,'ema':ema }
 	 $.ajax({
      data:  {parametros:parametros},
      url: '../controlador/facturacion/lista_ndo_nduC.php?enviar_mail=true',
      type:  'post',
      dataType: 'json',
       success:  function (response) { 
       	console.log(response);
       	if(response==1)
       	{
       		Swal.fire('Email enviado.','Revise su correo','success');
       		$('modal_email').modal('hide');
       	}
       }
       	
    });
 }

 function rangos()
 {
    periodo  = $('#hidden_periodo').val();
    if(periodo!='.')
    {
      year = periodo.split('/');
      year = year[2];
    }
    if(periodo!='.')
    {
       $('#txt_desde').val(year+'-01-01');
       $('#txt_hasta').val(year+'-12-30');
    }else
    {
       year = new Date().getFullYear();
      // console.log(currentTime);
      $('#txt_desde').val(year+'-01-01');
      $('#txt_hasta').val(year+'-12-30');
    }
 }


 function anular_factura(Factura,Serie,Codigo)
 {
    Swal.fire({
       title: 'Esta seguro? \n Esta usted seguro de Anular la factura:'+Factura,
       text:'' ,
       type: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Si!'
     }).then((result) => {
       if (result.value==true) {
         Anular(Factura,Serie,Codigo);
       }
     })
 }

 function Anular(Factura,Serie,Codigo)
 {
  var parametros = 
  {
    'factura':Factura,
    'serie':Serie,
    'codigo':Codigo,
  }
   $.ajax({
      data:  {parametros:parametros},
      url: '../controlador/facturacion/lista_ndo_nduC.php?Anular=true',
      type:  'post',
      dataType: 'json',
       success:  function (response) { 
        console.log(response);
        if(response==1)
        {
          Swal.fire('Factura Anulada','','success').then(function()
          {
            cargar_registros();
          })
        }
       }
        
    });
 }

function modal_email_fac(factura,serie,codigoc,emails, auto, tc)
  {
    $('#myModal_email').modal('show'); 
    $('#txt_fac').val(factura);
    $('#txt_serie').val(serie);
    $('#txt_codigoc').val(codigoc);
    $('#txt_NDauto').val(auto);
    $('#txt_NDtc').val(tc);

    var to = emails.substring(0,emails.length-1);
    var ema = to.split(',');
    var t = ''
    ema.forEach(function(item,i)
    {
      t+='<div class="emails emails-input"><span role="email-chip" class="email-chip"><span>'+item+'</span><a href="#" class="remove">×</a></span><input type="text" role="emails-input" placeholder="añadir email ...">       </div>';
       console.log(item);
    })
   $('#emails-input').html(t)
   $('#txt_to').val(emails);

  }

 function enviar_email()
  {
     $('#myModal_espera').modal('show');
    var to = $('#txt_to').val();
    var cuerpo = $('#txt_texto').val();
    var pdf_fac = $('#cbx_factura').prop('checked');
    var titulo = $('#txt_titulo').val();
    var factura = $('#txt_fac').val();
    var serie = $('#txt_serie').val();
    var codigoc = $('#txt_codigoc').val();
    var auto = $('#txt_NDauto').val();
    var tc = $('#txt_NDtc').val();

    // var adjunto =  new FormData(document.getElementById("form_img"));

    // console.log()
// return false;
    console.log(to);
    parametros = 
    {
        'to':to,
        'cuerpo':cuerpo,
        'pdf_fac':pdf_fac,
        'titulo':titulo,
        'fac':factura,
        'serie':serie,
        'codigoc':codigoc,
        'auto':auto,
        'tc':tc,
    }
     $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/facturacion/lista_ndo_nduC.php?enviar_email_detalle=true',
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 
            setTimeout(()=>{
					    $('#myModal_espera').modal('hide');
				    }, 1000)
            if(response==1)
            {
                Swal.fire('Email enviado','','success').then(function(){
                    $('#myModal_email').modal('hide');
                })
            }else
            {
                Swal.fire('Email no enviado','Revise que sea un correo valido','info');
            }
         
        }, 
        error: function(xhr, textStatus, error){
            setTimeout(()=>{
              $('#myModal_espera').modal('hide');
            }, 1000)
            // $('#lbl_mensaje').text(xhr.statusText);
            // alert(xhr.statusText);
            // alert(textStatus);
            // alert(error);
        }
      });

  }


  function descargar_fac(factura,serie,codigoc,auto,tc)
  {
    var parametros = 
    {
        'fac':factura,
        'serie':serie,
        'codigoc':codigoc,
        'auto':auto,
        'tc':tc
    }
     $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/facturacion/lista_ndo_nduC.php?descargar_factura=true',
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 
            console.log(response);
              var link = document.createElement("a");
              link.download = response;
              link.href = '../../TEMP/'+response;
              link.click();
        
         
        }
      });

  }

  function descargar_xml(xml)
  {
    var parametros = 
    {
        'xml':xml,
    }
     $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/facturacion/lista_ndo_nduC.php?descargar_xml=true',
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 
          if(response!='-1')
          {
            console.log(response);
              var link = document.createElement("a");
              link.download = response.xml;
              link.href ='../../php/'+response.ruta;
              link.click();
              console.log(link.href)
          }else
          {
            Swal.fire('No se encontro el xml','','info');
          }
        }
      });
  }

  function generar_xml(factura,serie,tc)
  {

    $('#myModal_espera').modal('show');
     var parametros = 
    {
        'Factura':factura,
        'Serie':serie,
        'TC':tc,
    }
     $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/facturacion/lista_ndo_nduC.php?generar_xml=true',
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 

          setTimeout(()=>{
            $('#myModal_espera').modal('hide');
          }, 1000)
          if(response.respuesta=='1')
          {
              console.log(response);
              var link = document.createElement("a");
              link.download = response.xml;
              link.href ='../../php/'+response.ruta;
              link.click();
              console.log(link.href)
          }else
          {
            Swal.fire('No se pude generar el xml','','info');
          }
        }
      });
  }

  function autorizar_blo()
    {
        Swal.fire({
         title: 'Esta seguro de realizar esta accion?',
         text: " Esto podria tomar varios munutos!",
         type: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Si!'
       }).then((result) => {
         if (result.value==true) {

           $('#myModal_espera').modal('show');
            autorizar_bloque();
         }
       })
    }

    function autorizar_bloque()
    {
      var per = $('#ddl_periodo').val();
        var serie = $('#DCLinea').val();
        if(serie!='' && serie!='.')
        {
         var serie = serie.split(' ');
         var serie = serie[1];
        }else
        {
          serie = '';
        }
        //var tipo = '<?php echo $tipo; ?>'
        var parametros = 
        {
          'ci':$('#ddl_cliente').val(),
          'per':per,      
          'desde':$('#txt_desde').val(),
          'hasta':$('#txt_hasta').val(),
          'tipo':tipo,
          'serie':serie,
          'auto':2,
        }
         $.ajax({
           data:  {parametros:parametros},
          url:   '../controlador/facturacion/lista_ndo_nduC.php?autorizar_bloque=true',
          type:  'post',
          dataType: 'json',
          // beforeSend: function () {                            
          //      $("#tbl_tablaNoAu").html('<tr class="text-center"><td colspan="16"><img src="../../img/gif/loader4.1.gif" width="20%">');                           
          // },
           success:  function (response) { 
            // console.log(response);

           $('#myModal_bloque').modal('show');                           
              $('#bloque_resp').html(response);                            
              setTimeout(()=>{
                $('#myModal_espera').modal('hide');
              }, 1000)
          }
        });

    }