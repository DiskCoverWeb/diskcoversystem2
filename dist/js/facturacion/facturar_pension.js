  $('body').on("keydown", function(e) { 
    if ( e.which === 27) {
      document.getElementById("factura").focus();
      e.preventDefault();
    }
  });
  var total = 0;
  var total0 = 0;
  var total12 = 0;
  var iva12 = 0;
  var descuento = 0;

  var tempRepresentante = "";
  var tempCI = "";
  var tempTD = "";
  var tempTelefono = "";
  var tempDirS = "";
  var tempDireccion = "";
  var tempEmail = "";
  var tempGrupo = "";
  var tempCtaNo = "";
  var tempTipoCta = "";
  var tempDocumento = "";
  var tempCaducidad = "";
  $(document).ready(function () {

document.addEventListener('keydown', function(e) {
    if (e.key === "Tab" && !e.shiftKey) { // Solo cuando se presiona Tab (sin Shift)
        // Obtiene solo los elementos con tabindex explícito en el HTML
        var focusableElements = Array.from(document.querySelectorAll('*')).filter(function(el) {
            // Verifica que el atributo tabindex esté presente en el HTML
            return el.hasAttribute('tabindex') && 
                   !isNaN(el.getAttribute('tabindex')) && 
                   parseInt(el.getAttribute('tabindex')) > 0;
        }).sort(function(a, b) {
            return a.tabIndex - b.tabIndex;
        });
        
        console.log(focusableElements)
        var currentIndex = focusableElements.indexOf(document.activeElement);
        
        if (currentIndex === focusableElements.length - 1) {
            e.preventDefault(); // Evita el enfoque natural
            focusableElements[0].focus(); // Va al primer elemento
        }
    }
});

    autocomplete_cliente();
    catalogoLineas();
    totalRegistros();
    //verificarTJ();
    cargarBancos();
    DCGrupo_No();
    selectCatalogoCuentas();
    selectAnticipos();
    selectNotasCredito();

    DCPorcenIva('fechaEmision', 'DCPorcenIVA');

    document.addEventListener('click', function(event) {
      let backdrop = document.querySelector('.modal-backdrop');
      if (backdrop === event.target) {
        backdrop.parentNode.removeChild(backdrop);
      }
    });

    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?getMBHistorico=true',
      dataType:'json', 
      success: function(data)             
      {
          $("#MBHistorico").val(data.MBHistorico)        
      }
    });

    // $("#DCPorcenIVA").change(function() {
    //      alert('dd');
    //   });

    $('.validateDate').on('keyup', function () {
        if($(this).val().length >= 10){
          let inputDate = $(this).val();
          const dateArray = inputDate.split("-"); // Separar la fecha en partes (año, mes, día)
          let year = dateArray[0];
          const month = dateArray[1];
          const day = dateArray[2];
          if(year.length>4){
            year = year.slice(0,4)
          }
          $(this).val(year+'-'+month+'-'+day);
       }
    });

    $("input").focusin(function() {
      $(this).select();
    });
    $("#factura").blur(function(){
      var currentTabIndex = parseInt($(this).attr("tabindex"));
      var nextTabIndex = currentTabIndex + 1;
      $("[tabindex='" + nextTabIndex + "']").focus();
    });

      $(".btnDepositoAutomatico").blur(function(){
        if($('.contenidoDepositoAutomatico').is(':visible') && $('.contenidoDepositoAutomatico').css("display") != "none"){
          var currentTabIndex = parseInt($(this).attr("tabindex"));
          var nextTabIndex = currentTabIndex + 1;
          $("[tabindex='" + nextTabIndex + "']").focus();
        }else{
          $("#checkbox1").focus();
        }
      });

    $(".btnDepositoAutomatico").on('click',function () {
      if($('.contenidoDepositoAutomatico').is(':visible') && $('.contenidoDepositoAutomatico').css("display") != "none"){
        $('.contenidoDepositoAutomatico').css('display', 'none')
      }else{
        $('.contenidoDepositoAutomatico').css('display', 'flex')
      }
    })

    //enviar datos del cliente
    $('#cliente').on('select2:select', function (e) {
      var data = e.params.data.data;
      // var dataM = e.params.data.dataMatricula;
      cambiarlabel()
      $('#email').val(data.EmailR);
      $('#direccion').val(data.direccion);
      $('#direccion1').val(data.direccion1);
      $('#telefono').val(data.Telefono_R);
      $('#codigo').val(data.codigo);
      $('#ci_ruc').val(data.ci_ruc);
      $('#persona').val(data.Representante);
      $('#chequeNo').val(data.grupo);
      $('#codigoCliente').val(data.codigo);
      $('#tdCliente').val(data.tdCliente);
      $('.spanNIC').text(data.TD_R);
      $('#TextCI').val(data.RUC_CI_Rep);
      $('#codigoB').val("Código del banco: "+data.ci_ruc);
      $("#total12").val(parseFloat(0.00).toFixed(2));
      $("#descuento").val(parseFloat(0.00).toFixed(2));
      $("#descuentop").val(parseFloat(0.00).toFixed(2));
      $("#efectivo").val(parseFloat(0.00).toFixed(2));
      $("#abono").val(parseFloat(0.00).toFixed(2));
      $("#iva12").val(parseFloat(0.00).toFixed(2));
      $("#total").val(parseFloat(0.00).toFixed(2));
      $("#total0").val(parseFloat(0.00).toFixed(2));
      $("#valorBanco").val(parseFloat(0.00).toFixed(2));
      $("#saldoTotal").val(parseFloat(0.00).toFixed(2));
      DCGrupo_NoPreseleccion(data.grupo)

      if(data.Archivo_Foto_url!=''){
        $("#img_estudiante").attr('src','../img/img_estudiantes/'+data.Archivo_Foto_Url)
      }else{
        $("#img_estudiante").attr('src','../img/img_estudiantes/SINFOTO.jpg')

      }
      //$("input[type=checkbox]").prop("checked", false);
      total = 0;
      total0 = 0;
      total12 = 0;
      iva12 = 0;
      descuento = 0;
      catalogoProductos(data.codigo);
      saldoFavor(data.codigo);
      saldoPendiente(data.codigo);
      clienteMatricula(data.codigo);
      ListarMedidoresHeader($("#CMedidorFiltro"),data.codigo, true)

      //Actualizar cliente
      tempRepresentante = $('#persona').val()
      tempCI  = $('#ci_ruc').val()
      tempTD  = $('#tdCliente').val()
      tempTelefono  = $('#telefono').val()
      tempDireccion  = $('#direccion').val()
      tempDirS  = $("#direccion1").val().toUpperCase()
      tempEmail  = $("#email").val().toUpperCase()
      tempGrupo  = $("#DCGrupo_No").val()
      tempCtaNo  = $('#numero_cuenta_debito_automatico').val()
      tempTipoCta  = $('#tipo_debito_automatico').val()
      tempDocumento  = $('#debito_automatica').val()
      tempCaducidad  = $('#caducidad_debito_automatico').val()

      //prefactura pension
      $('#PFcodigoCliente').val(data.codigo);
      $('#PFnombreCliente').text(data.cliente);
      $('#PFGrupoNo').val(data.grupo);
    });

    $("#DCGrupo_No").on('select2:select', function (e) {
      $.ajax({
        url:   '../controlador/facturacion/facturar_pensionC.php?DireccionByGrupo=true&grupo='+$("#DCGrupo_No").val()+'',
        dataType: 'json',
        success: function (data) {
          $('#direccion').val(data[0].Direccion)
        }
      })
    });

    $("#CMedidorFiltro").on('change', function () {
      catalogoProductos($('#codigo').val(), $("#CMedidorFiltro").val());
    })

    cambiarlabel()

  });

  function selectCatalogoCuentas(){
    $.ajax({
      type: "GET",                 
      url: '../controlador/facturacion/facturar_pensionC.php?CatalogoCuentas=true',
      dataType:'json', 
      success: function(data)
      {
        console.log(data);
        if (data.length>0) {
          let selectHtml = "";
          for(let d of data){
            selectHtml += `<option value='${d['codigo']}'>${d['nombre']}</option>`;
          }
          $('#cuentaBanco').html(selectHtml);
          verificarTJ();
        }else{
          Swal.fire('Hubo un error', '', 'error');
        }
      }
    });
  }

  function selectAnticipos(){
    $.ajax({
      type: "GET",                 
      url: '../controlador/facturacion/facturar_pensionC.php?Anticipos=true',
      dataType:'json', 
      success: function(data)
      {
        console.log(data);
        if (data.length>0) {
          let selectHtml = "";
          for(let d of data){
            selectHtml += `<option value='${d['codigo']}'>${d['nombre']}</option>`;
          }
          $('#DCAnticipo').html(selectHtml);
        }else{
          Swal.fire('Hubo un error', '', 'error');
        }
      }
    });
  }

  function selectNotasCredito(){
    $.ajax({
      type: "GET",                 
      url: '../controlador/facturacion/facturar_pensionC.php?NotasCredito=true',
      dataType:'json', 
      success: function(data)
      {
        console.log(data);
        if (data.length>0) {
          let selectHtml = "";
          for(let d of data){
            selectHtml += `<option value='${d['codigo']}'>${d['nombre']}</option>`;
          }
          $('#cuentaNC').html(selectHtml);
        }else{
          Swal.fire('Hubo un error', '', 'error');
        }
      }
    });
  }

  function usar_cliente(nombre, ruc, codigocliente, email, T, grupo) {
    $('#PFcodigoCliente').val(codigocliente);
    $('#PFnombreCliente').text(nombre);
    $('#PFGrupoNo').val(grupo);
    OpenModalPreFactura(cantidadProductoPreFacturar)
  }

  function autocomplete_cliente(){
    $('#cliente').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url:   '../controlador/facturacion/facturar_pensionC.php?cliente=true',
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

  function catalogoLineas(){
    // $('#myModal_espera').modal('show');
    var cursos = $("#DCLinea");
    fechaEmision = $('#fechaEmision').val();
    fechaVencimiento = $('#fechaVencimiento').val();
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?catalogo=true',
      data: {'fechaVencimiento' : fechaVencimiento , 'fechaEmision' : fechaEmision},      
      dataType:'json', 
      success: function(data)             
      {
        if (data) {
          datos = data;
          // Limpiamos el select
          cursos.find('option').remove();
          for (var indice in datos) {
            cursos.append('<option value="' + datos[indice].id +" "+datos[indice].text+ ' ">' + datos[indice].text + '</option>');
          }
        }else{
          console.log("No tiene datos");
        }
        numeroFactura();            
      }
    });
    $('#myModal_espera').modal('hide');
  }

  function imprimir_ticket_fac(mesa,ci,fac,serie)
  {
    var html='<iframe style="width:100%; height:50vw;" src="../appr/controlador/imprimir_ticket.php?mesa='+mesa+'&tipo=FA&CI='+ci+'&fac='+fac+'&serie='+serie+'" frameborder="0" allowfullscreen></iframe>';
    $('#contenido').html(html); 
    $("#myModal").modal();
  }

  function catalogoProductos(codigoCliente, CMedidor="."){
    console.log(codigoCliente);
    console.log(CMedidor);
    $('#myModal_espera').modal('show');
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?catalogoProducto=true',
      data: {'codigoCliente' : codigoCliente, 'CMedidor' : CMedidor }, 
      dataType:'json', 
      success: function(data)
      {
        if (data) {
          datos = data;
          clave = 1;
          $("#cuerpo").empty();
          let totalItem = datos.length
          for (var indice in datos) {
            if(datos[indice].iva==1)
            {
              subtotal = (parseFloat(datos[indice].valor) + (parseFloat(datos[indice].valor) * parseFloat($('#DCPorcenIVA').val()) / 100)) - parseFloat(datos[indice].descuento) - parseFloat(datos[indice].descuento2);
            }else{
              subtotal = (parseFloat(datos[indice].valor) + (parseFloat(datos[indice].valor) * parseFloat(datos[indice].iva) / 100)) - parseFloat(datos[indice].descuento) - parseFloat(datos[indice].descuento2);
            }
            var tr = `<tr class="tr`+clave+`">
              <td><input ${((totalItem==clave)?`onblur="$('#TextBanco').focus()"`:'')} style="border:0px;background:bottom;" type="checkbox" id="checkbox`+clave+`" onclick="totalFactura('checkbox`+clave+`','`+subtotal+`','`+datos[indice].iva+`','`+datos[indice].descuento+`','`+datos.length+`','`+clave+`')" name="`+datos[indice].mes+`"></td>
              <td><input style="border:0px;background:bottom;max-width: 85px;" type ="text" id="Mes`+clave+`" value ="`+datos[indice].mes+`" disabled/></td>
              <td><input style="border:0px;background:bottom" type ="text" id="Codigo`+clave+`" value ="`+datos[indice].codigo+`" disabled/></td>
              <td><input style="border:0px;background:bottom;max-width: 50px;" type ="text" id="Periodo`+clave+`" value ="`+datos[indice].periodo+`" disabled/></td>
              <td><input style="border:0px;background:bottom" type ="text" id="Producto`+clave+`" value ="`+datos[indice].producto+`" disabled/></td>
              <td><input class="text-right" style="border:0px;background:bottom;max-width: 75px;"  size="10px" type ="text" id="valor`+clave+`" value ="`+parseFloat(datos[indice].valor).toFixed(2)+`" disabled/></td>
              <td><input class="text-right" style="border:0px;background:bottom;max-width: 85px;"  size="10px" type ="text" id="descuento`+clave+`" value ="`+parseFloat(datos[indice].descuento).toFixed(2)+`" disabled/></td>
              <td><input class="text-right" style="border:0px;background:bottom;max-width: 85px;"  size="10px" type ="text" id="descuento2`+clave+`" value ="`+parseFloat(datos[indice].descuento2).toFixed(2)+`" disabled/></td>
              <td><input class="text-right" style="border:0px;background:bottom;max-width: 85px;" size="10px" type ="text" id="subtotal`+clave+`" value ="`+parseFloat(subtotal).toFixed(2)+`" disabled/></td>
              
              <td`
              if(mostrar_medidor==''){ tr+=`style="display:none"`}
              		tr+=`><input class="text-right" style="border:0px;background:bottom;max-width: 65px;" size="10px" type ="text" id="inputLectura`+clave+`" value ="`+datos[indice].Credito_No+`" disabled/>
              </td>
              <td`
              if(mostrar_medidor==''){ tr+=`style="display:none"`}
              tr+=`><input class="text-right" style="border:0px;background:bottom;max-width: 65px;" size="10px" type ="text" id="inputMedidor`+clave+`"  value ="`+datos[indice].Codigo_Auto+`" disabled/></td>
              
              <input size="10px" type ="hidden" id="CodigoL`+clave+`" value ="`+datos[indice].CodigoL+`"/>
              <input size="10px" type ="hidden" id="Iva`+clave+`" value ="`+datos[indice].iva+`"/>
            </tr>`;
            $("#cuerpo").append(tr);
            clave++;
          }
          $("#efectivo").val(parseFloat(0.00).toFixed(2));
          $("#abono").val(parseFloat(0.00).toFixed(2));
          $("#descuentop").val(parseFloat(0.00).toFixed(2));
        }else{
          console.log("No tiene datos");
        }    

        setTimeout(function() {   $('#myModal_espera').modal('hide');   }, 1500);
     
      },
      complete: function (argument) {
        $('#myModal_espera').modal('hide');
      }
    });
  }

  function historiaCliente(){
    codigoCliente = $('#codigoCliente').val();
    $('#myModal_espera').modal('show');
    
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?historiaCliente=true',
      data: {'codigoCliente' : codigoCliente }, 
      dataType:'json', 
      success: function(data)
      {
        $('#myModal_espera').modal('hide');
        $('#myModalHistoria').modal('show');
        if (data) {
          datos = data;
          clave = 0;
          $("#cuerpoHistoria").empty();
          for (var indice in datos) {
            var tr = `<tr>
              <td><input style="border:0px;background:bottom" size="1" type ="text" id="TD`+clave+`" value ="`+datos[indice].TD+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="7" type ="text" id="Fecha`+clave+`" value ="`+datos[indice].Fecha+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="6" type ="text" id="Serie`+clave+`" value ="`+datos[indice].Serie+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="6" type ="text" id="Factura`+clave+`" value ="`+datos[indice].Factura+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="70" type ="text" id="Detalle`+clave+`" value ="`+datos[indice].Detalle+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="2" class="text-right" type ="text" id="Anio`+clave+`" value ="`+datos[indice].Anio+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="10" type ="text" id="Mes`+clave+`" value ="`+datos[indice].Mes+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="6" class="text-right" size="10px" type ="text" id="Total`+clave+`" value ="`+parseFloat(datos[indice].Total).toFixed(2)+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="6" class="text-right" type ="text" id="Abonos`+clave+`" value ="`+parseFloat(datos[indice].Abonos).toFixed(2)+`" disabled/></td>
              <td><input size="2" class="text-right" style="border:0px;background:bottom"  type ="text" id="Mes_No`+clave+`" value ="`+datos[indice].Mes_No+`" disabled/></td>
              <td><input size="2" class="text-right" style="border:0px;background:bottom"  type ="text" id="No`+clave+`" value ="`+datos[indice].No+`" disabled/></td>
            </tr>`;
            $("#cuerpoHistoria").append(tr);
            clave++;
          }
        }else{
          console.log("No tiene datos");
        }            
      }
    });
  }

  function historiaClienteExcel(){
    codigoCliente = $('#codigoCliente').val();
    if(codigoCliente=='')
    {
      codigoCliente = $('#codigo').val();
    }

    if(codigoCliente!=''){
      url = '../controlador/facturacion/facturar_pensionC.php?historiaClienteExcel=true&codigoCliente='+codigoCliente;
      window.open(url, '_blank');
    }else{
      Swal.fire({
          type: 'warning',
          title: 'Seleccione un cliente',
          text: ''
        });
    }
  }

  function historiaClientePDF(){
    codigoCliente = $('#codigoCliente').val();
    if(codigoCliente=='')
    {
      codigoCliente = $('#codigo').val();
    }

    if(codigoCliente!=''){
      url = '../controlador/facturacion/facturar_pensionC.php?historiaClientePDF=true&codigoCliente='+codigoCliente;
      window.open(url,'_blank');
    }else{
      Swal.fire({
          type: 'warning',
          title: 'Seleccione un cliente',
          text: ''
        });
    }
  }

    function DeudaPensionPDF(){
    var parametros=[];
    codigoCliente = $('#codigoCliente').val();
    var can = $('#txt_cant_datos').val();
    var j=0;
    for (var i = 1; i < can+1; i++) {
      if($('#checkbox'+i).prop('checked'))
      {
       parametros[j] = {
        'mes':$('#Mes'+i).val(),
        'cod':$('#Codigo'+i).val(),
        'ani':$('#Periodo'+i).val(),
        'pro':$('#Producto'+i).val(),
        'val':$('#valor'+i).val(),
        'des':$('#descuento'+i).val(),
        'p.p':$('#descuento2'+i).val(),
        'tot':$('#subtotal'+i).val(),
      }
      j= j+1;
    }

    }

    parametros = JSON.stringify(parametros);
    parametros = encodeURI(parametros);

    
    url = '../controlador/facturacion/facturar_pensionC.php?DeudaPensionPDF=true&codigoCliente='+codigoCliente+'&lineas='+parametros;
    // console.log(parametros);
    // return false;
    window.open(url, '_blank');
  }

  function enviarHistoriaCliente(){
    codigoCliente = $('#codigoCliente').val();
    email = $('#email').val();
    if(email!=""){
      //url = '../controlador/facturacion/facturar_pensionC.php?enviarCorreo=true&codigoCliente='+codigoCliente+'&email='+email;
      //window.open(url, '_blank');
      $('#myModal_espera').modal('show');
      $.ajax({
        type: "POST",                 
        url: '../controlador/facturacion/facturar_pensionC.php?enviarCorreo=true&codigoCliente='+codigoCliente,
        data: {'email' : email }, 
        success: function(data)
        {
          $('#myModal_espera').modal('hide');
          Swal.fire({
            type: 'success',
            title: 'Correo enviado correctamente',
            text: ''
          });
        }
      });
    }else{
      Swal.fire({
          type: 'warning',
          title: 'Seleccione un cliente',
          text: ''
        });
      
    }
  }

  function saldoFavor(codigoCliente){
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?saldoFavor=true',
      data: {'codigoCliente' : codigoCliente },
      dataType:'json', 
      success: function(data)
      {
        let valor = 0;
        if (data.length>0) {
          valor = data[0].Saldo_Pendiente;
        }
        $("#saldoFavor").val(parseFloat(valor).toFixed(2));
      }
    });
  }

  function saldoPendiente(codigoCliente){
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?saldoPendiente=true',
      data: {'codigoCliente' : codigoCliente }, 
      dataType:'json', 
      success: function(data)
      {
        let valor = 0;
        if (data.length>0) {
          valor = data[0].Saldo_Pend;
        }
        $("#saldoPendiente").val(parseFloat(valor).toFixed(2));
      }
    });
  }

  function totalFactura(id,valor,iva,descuento1,datos,clave){
    $('#txt_cant_datos').val(datos);
    $('.tr'+clave).toggleClass("filaSeleccionada");
    datosLineas = [];
    key = 0;
    for (var i = 1; i <= datos; i++) {
      datosId = 'checkbox'+i;
      if ($('#'+datosId).prop('checked')) {
        let adicionNombre = (($("#inputMedidor"+i).val()!="." && $("#inputMedidor"+i).val()!="")?" - "+$("#inputMedidor"+i).val():"");
        adicionNombre += (($("#inputLectura"+i).val()!="." && $("#inputLectura"+i).val()!="")?" - "+$("#inputLectura"+i).val():"");
        datosLineas[key] = {
          'Codigo' : $("#Codigo"+i).val(),
          'CodigoL' : $("#CodigoL"+i).val(),
          'Producto' : $("#Producto"+i).val()+adicionNombre,
          'Precio' : $("#valor"+i).val(),
          'Total_Desc' : $("#descuento"+i).val(),
          'Total_Desc2' : $("#descuento2"+i).val(),
          'Iva' : $("#Iva"+i).val(),
          'Total' : $("#subtotal"+i).val(),
          'MiMes' : $("#Mes"+i).val(),
          'Periodo' : $("#Periodo"+i).val(),
          'CORTE' : $("#inputLectura"+i).val(),
          'Tipo_Hab' : $("#inputMedidor"+i).val(),
          'Iva_val':$('#DCPorcenIVA').val(),
        };
        key++;
      }
    }
    codigoCliente = $("#codigoCliente").val();
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/facturar_pensionC.php?guardarLineas=true',
      data: {
        'codigoCliente' : codigoCliente,
        'datos' : datosLineas,
      }, 
      success: function(data){calcularSaldo()}
    });
    // console.log('conti');
    var valor = 0; var descuento = 0; var descuentop = 0; var total = 0;var subtotal = 0;var iva12 = 0; var valor12 = 0;
    for(var i=1; i<datos+1; i++){
      checkbox = "checkbox"+i;
      if($('#'+checkbox).prop('checked'))
      {

        descuento+=parseFloat($('#descuento'+i).val());
        descuentop+=parseFloat($('#descuento2'+i).val());       
        iva = $('#Iva'+i).val()
        if(iva==1)
        {
          iva12+= parseFloat($('#valor'+i).val())*(parseFloat($('#DCPorcenIVA').val())/100) 
          valor12+=parseFloat($('#valor'+i).val());
        }else
        {
           valor+=parseFloat($('#valor'+i).val());
        }
        subtotal+=parseFloat($('#descuento2'+i).val());
        total+=parseFloat($('#subtotal'+i).val());
      }

    }

    //$("#total12").val(parseFloat(subtotal).toFixed(2));
    $("#descuentop").val(parseFloat(descuentop).toFixed(2));
    $("#descuento").val(parseFloat(descuento).toFixed(2));
    $("#iva12").val(parseFloat(iva12).toFixed(2));
    $("#total").val(parseFloat(total).toFixed(2));
    $("#total0").val(parseFloat(valor).toFixed(2));
    $("#total12").val(parseFloat(valor12).toFixed(2));
    $("#valorBanco").val(parseFloat(total).toFixed(2));
   // $("#saldoTotal").val(parseFloat(0).toFixed(2));


  }

  function calcularDescuento(){
    $('#myModalDescuentoP').modal('hide');
    let ContDesc = 0
    let SubTotal_Desc2 = parseFloat($("#total0").val()) + parseFloat($("#total12").val()) - parseFloat($("#descuento").val())

    if (SubTotal_Desc2 > 0 ){
      let Valor_Desc2 = 0
      let Porc_Desc2 = $('#porcentaje').val();

      if( $.isNumeric(Porc_Desc2) ){
        var table = document.getElementById('tablaDetalle');
        var rowLength = table.rows.length;
        for(let i=1; i<rowLength; i+=1){
          if ($("#checkbox"+i).prop('checked')){
            ContDesc++
          }
        }

        if(ContDesc>0){ Valor_Desc2 = (((Porc_Desc2 / 100) * SubTotal_Desc2) / ContDesc).toFixed(2) }
        let S_Descuento2_Total = S_Descuento1_Total = 0;
        for(let i=1; i<rowLength; i+=1){
          if ($("#checkbox"+i).prop('checked')){
            let S_Valor = $("#valor"+i).val()
            let S_Descuento1 = $("#descuento"+i).val()
            let S_Descuento2 = Valor_Desc2
            let S_SubTotal = ((S_Valor) - (S_Descuento1) - (S_Descuento2)).toFixed(2)

            S_Descuento2_Total += parseFloat(S_Descuento2)
            S_Descuento1_Total += parseFloat(S_Descuento1)
            $("#descuento2"+i).val(S_Descuento2);
            $("#subtotal"+i).val(S_SubTotal);
            totalFactura("checkbox"+i,S_Valor,iva=0,S_Descuento1,rowLength);
          }
        }

        total0 = $("#total0").val();
        total = total0 - S_Descuento2_Total - S_Descuento1_Total;
        $("#descuentop").val(parseFloat(S_Descuento2_Total).toFixed(2));
        $("#total").val(parseFloat(total).toFixed(2));
        $("#valorBanco").val(parseFloat(total).toFixed(2));

        calcularSaldo()
      }else{
        Swal.fire({
          type: 'info',
          title: 'Por favor indique un valor numerico',
          text: ''
        });
      }
    }else{
      Swal.fire({
        type: 'info',
        title: 'No tiene items a descontar',
        text: ''
      });
    }
  }

  function calcularSaldo(){
    total = $("#total").val();
    efectivo = $("#efectivo").val();
    abono = $("#abono").val();
    banco = $("#valorBanco").val();
    saldoFavor = $("#saldoFavor").val();
    saldo = total - banco - efectivo - abono -saldoFavor;
    $("#saldoTotal").val(saldo.toFixed(2));
  }

  function numeroFactura(){
    DCLinea = $("#DCLinea").val();
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/facturar_pensionC.php?numFactura=true',
      data: {
        'DCLinea' : DCLinea,
      },       
      dataType:'json', 
      success: function(data)
      {
        datos = data;
        document.querySelector('#numeroSerie').innerText = datos.serie;
        $("#factura").val(datos.codigo);
      }
    });
  }

  function totalRegistros(){
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/facturar_pensionC.php?cliente=true&total=true',
      data: {
        'q' : '',
      },       
      dataType:'json', 
      success: function(data)
      {
        datos = data;
        $("#registros").val(datos.registros);
      }
    });
  }

  function verificarTJ(){
    TC = $("#cuentaBanco").val();
    TC = TC.split("/");
    //console.log("entra");
    if (TC[1] == "TJ") {
      $("#divInteres").show();
    }else{
      $("#divInteres").hide();
    }
  }

  function guardarPension()
  {

    saldoTotal = $("#saldoTotal").val();
    validarDatos = $("#total").val();
    if($('#cliente').val()=="")
      {
        Swal.fire("","Seleccione cliente o alumno","info")
        return false;
      }
   if($('#persona').val()=='' ||  $('#persona').val()=='.' || $('#TextCI').val()=='' || $('#TextCI').val()=='.' || $('#direccion1').val()=='' || $('#direccion1').val()=='.'  || $('#email').val()=='' || $('#email').val()=='.' || $('#telefono').val()=='' || $('#telefono').val()=='.')
    {
      Swal.fire("","Datos para la factura incorrectos","info")
      return false;
    }
   if(validarDatos <= 0 ) 
    {
     Swal.fire("","Seleccione una elemento para facturar","info")
     return false;
    } 

    if($('#DCLinea').val() == "" ) 
    {
     Swal.fire("","Se debe indicar una Linea de Catalogo","info")
     return false;
    } 

    saldoTotal  = $("#saldoTotal").val();
    if(saldoTotal<0)
    {
      Swal.fire("","El total de abonos supera el total de la factura.","info")
      return false;
    }

    ProcesoFacturacion();
  }

  function ProcesoFacturacion()
  {
      var update = false;
      //var update = confirm("¿Desea actualizar los datos del cliente?");
      Swal.fire({
        title: 'Desea actualizar los datos del cliente?',
        text: "Los datos del cliente se actualizaran",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
      }).then((result) => {
        if (result.value==true) {
            actualizarCliente()            
        }else{
          GuardarFactura();
        }
      })

  }

  function actualizarCliente()
  {
    parametros = 
    {
      'Grupo_No': $("#DCGrupo_No").val(),
      'CodigoCliente':$("#codigoCliente").val(),
      'Telefono': $("#telefono").val(),
      'DirS':$("#direccion1").val().toUpperCase(),
      'Direccion':$("#direccion").val().toUpperCase(),
      'Email':$("#email").val(),
      'MBFecha':$('#caducidad_debito_automatico').val(),
      'Representante':$("#persona").val(),
      'TextCI':$("#TextCI").val(),
      'TxtCtaNo': $('#numero_cuenta_debito_automatico').val(),
      'CTipoCta': $("#tipo_debito_automatico").val(),
      'CheqPorDeposito': $("#por_deposito_debito_automatico").prop('checked'),
      'Documento':$('#debito_automatica').val(),
      'DCDebito':$('#debito_automatica').val(),
      'TD_Rep':$("#tdCliente").val(),
    }    
      $('#myModal_espera').modal('show');
     $.ajax({
        url: '../controlador/facturacion/facturar_pensionC.php?ActualizarCliente=true',
        type:  'post',
        data: {parametros:parametros},
        dataType: 'json',
        success:  function (response) {
          $('#myModal_espera').modal('hide');
          GuardarFactura()       
        },
          error: function (jqXHR, exception) {
            $('#myModal_espera').modal('hide');
            console.log(exception);
            alert("Ocurrio un error inesperado, por favor contacte a soporte.");
          }
      });
  }

  function GuardarFactura()
  {
     TextFacturaNo = $("#factura").val();
     TextSerie = $("#DCLinea").val();
     var Serie = TextSerie.split(" ");
console.log(Serie)
      Swal.fire({
          title: 'Esta seguro?',
          text: "Esta seguro que desea guardar \n La factura No."+ Serie[1] +"-"+TextFacturaNo,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si!'
        }).then((result) => {
          if (result.value==true) {
            GenerarFactura();
          }
        })

  }

  function GenerarFactura()
  {

    $('#myModal_espera').modal('show');
    DCBanco = $("#cuentaBanco").val();
    DCBanco = DCBanco.split("/");
    DCBanco = DCBanco[0];
    let por_deposito_debito_automatico ="0";
      if($('#por_deposito_debito_automatico').prop('checked')){
        por_deposito_debito_automatico = "1";
    }
    parametros = 
    {
        'DCLinea':$("#DCLinea option:selected").val(),
        'Cod_CxC':$("#DCLinea option:selected").text(),
        'Total':$("#total").val(),
        'Descuento':$("#descuento").val(),
        'Descuento2':$("#descuentop").val(),
        'TextRepresentante':$("#persona").val(),
        'TxtDireccion' : $("#direccion").val(),
        'TxtTelefono' : $("#telefono").val(),
        'TextFacturaNo' :  $("#factura").val(),
        'Grupo_No' : $("#DCGrupo_No").val(),
        'chequeNo' : $("#chequeNo").val(),
        'TextCI' : $("#TextCI").val(),
        'TD_Rep' : $("#tdCliente").val(),
        'TxtEmail' : $("#email").val().toUpperCase(),
        'TxtDirS' : $("#direccion1").val().toUpperCase(),
        'CodigoCliente' : $("#codigoCliente").val(),
        'TextCheque' :  $("#valorBanco").val(),
        'TextBanco': $("#TextBanco").val(),
        'DCBanco' : DCBanco,
        'DCAnticipo' : $("#DCAnticipo").val(),
        'TxtEfectivo' :  $("#efectivo").val(),
        'TxtNC' :  $("#abono").val(),
        'Fecha' :  $("#fechaEmision").val(),
        'DCNC' : $("#cuentaNC").val(),
        'SaldoTotal': $("#saldoTotal").val(), //no hay
        'SaldoFavor':$('#saldoFavor').val(),
        'TxtNCVal':$('#abono').val(), 
        'DCDebito': $('#debito_automatica').val(), 
        'Documento': $('#debito_automatica').val(), 
        'CTipoCta':$('#tipo_debito_automatico').val(), 
        'TxtCtaNo':$('#numero_cuenta_debito_automatico').val(), 
        'MBFecha':$('#caducidad_debito_automatico').val(), 
        'CheqPorDeposito':por_deposito_debito_automatico, 
        'TextInteres' :  $('#interesTarjeta').val(),
        'PorcIva': $('#DCPorcenIVA').val(),
    }
     
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturar_pensionC.php?guardarPension=true',
        data: {parametros,parametros},
        dataType:'json',  
        success: function(response)
        {
            $('#myModal_espera').modal('hide');

          if(response.respuesta==-1)
          {
            Swal.fire("",response.text,"error");

          }else if(response.respuesta == '3')
          {
            Swal.fire('Este documento electronico ya esta autorizado','','error');

          }else if(response.respuesta == 1)
          {
            Swal.fire('','Este documento electronico fue autorizado','success').then(function(){
                var url = '../controlador/facturacion/lista_facturasC.php?ver_fac=true&codigo='+response.Factura+'&ser='+response.Serie+'&ci='+response.CodigoCliente+'&per='+response.per+'&auto='+response.auto;
                window.open(url,'_blank');
                location.reload();
            })            
          }else if(response.respuesta == '2')
          {
             Swal.fire('','XML devuelto','info').then(function(){
                var url = '../controlador/facturacion/lista_facturasC.php?ver_fac=true&codigo='+response.Factura+'&ser='+response.Serie+'&ci='+response.CodigoCliente+'&per='+response.per+'&auto='+response.auto;
                window.open(url,'_blank');
                location.reload();
            })   
          }

          ///---------------------------antiua----
          /*
            recargarData = true;
            $('#myModal_espera').modal('hide');
              if (response) {

                response = response;
                if(response.respuesta == '3')
                {
                  Swal.fire('Este documento electronico ya esta autorizado','','error');

                }else if(response.respuesta == '1')
                {
                    Swal.fire({
                      type: 'success',
                      title: 'Este documento electronico fue autorizado',
                      text: ''
                    }).then(() => {
                      serie = DCLinea.split(" ");
                      //url = '../vista/appr/controlador/imprimir_ticket.php?mesa=0&tipo=FA&CI='+TextCI+'&fac='+TextFacturaNo+'&serie='+serie[1];
                      //window.open(url, '_blank');
                      var url = '../controlador/facturacion/lista_facturasC.php?ver_fac=true&codigo='+TextFacturaNo+'&ser='+serie[1]+'&ci='+codigoCliente+'&per='+response.per+'&auto='+response.auto;
                      window.open(url,'_blank');
                      location.reload();
                      //imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
                    });
                }else if(response.respuesta == '2')
                {
                    Swal.fire({
                       type: 'info',
                       title: 'XML devuelto',
                       text: ''
                     }).then(() => {
                      serie = DCLinea.split(" ");
                      cambio = $("#cambio").val();
                      efectivo = $("#efectivo").val();
                      var url = '../controlador/facturacion/lista_facturasC.php?ver_fac=true&codigo='+TextFacturaNo+'&ser='+serie[1]+'&ci='+TextCI+'&per='+response.per+'&auto='+response.auto;
                      window.open(url,'_blank');
                      location.reload();
                      //imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
                    });

                }else if(response.respuesta == '5')
                {
                    Swal.fire({
                      type: 'success',
                      title: 'Factura guardada correctamente',
                      text: ''
                    }).then(() => {
                      serie = DCLinea.split(" ");
                      cambio = $("#cambio").val();
                      efectivo = $("#efectivo").val();
                      var url = '../controlador/facturacion/lista_facturasC.php?ver_fac=true&codigo='+TextFacturaNo+'&ser='+serie[1]+'&ci='+TextCI+'&per='+response.per+'&auto='+response.auto;
                      window.open(url,'_blank');
                      location.reload();
                      //imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
                    });
                  }else if(response.respuesta==4)
                  {
                     Swal.fire('SRI intermitente si el problema persiste por mas de 1 dia comuniquese con su proveedor','','info');
                     catalogoProductos(codigoCliente);
                  }
                  else
                  {
                    Swal.fire({
                       type: 'info',
                       title: 'Error por: ',
                       html: `<div style="width: 100%; color:black;font-weight: 400;">${response.text}</div>`
                     });
                    if(response.respuesta==6){recargarData = false}
                  }
              }else{
                Swal.fire({
                  type: 'info',
                  title: 'La factura ya se autorizo',
                  text: ''
                });
                catalogoProductos(codigoCliente);
              }

              if(recargarData){
                if($('#persona').val()!=""){
                  ClientePreseleccion($('#persona').val());
                }
              } */

            },
            error: function (jqXHR, exception) {
              $('#myModal_espera').modal('hide');
              console.log(exception);
              control_errores('guardarPension',exception);
              alert("Ocurrio un error inesperado, por favor contacte a soporte.");
            }
        });
  }

  function clienteMatricula(codigoCliente){
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?clienteMatricula=true&codigoCliente='+codigoCliente,
      dataType:'json', 
      success: function(data)
      {
        if (data[0]) {
          let Caducidad = new Date(data[0].Caducidad.date);
          let mesCaducidad = (Caducidad.getMonth()+1);
          if(mesCaducidad<10){
            mesCaducidad = '0'+mesCaducidad; 
          }
          cargarBancosPreseleccion(data[0].Cod_Banco)
          $("#tipo_debito_automatico").val(data[0].Tipo_Cta);
          $("#numero_cuenta_debito_automatico").val(data[0].Cta_Numero);
          $("#caducidad_debito_automatico").val(mesCaducidad+'/'+Caducidad.getFullYear());
          if(data[0].Por_Deposito=='1'){
            $("#por_deposito_debito_automatico").prop('checked', true);
          }else{
            $("#por_deposito_debito_automatico").prop('checked', false);
          }
          if (data[0].Cod_Banco!="." && data[0].Cta_Numero!=".") {
            $('.contenidoDepositoAutomatico').css('display', 'flex')
          }else{
            $('.contenidoDepositoAutomatico').css('display', 'none')
          }
        }else{
          $('#debito_automatica').val(null).trigger('change');
          $("#tipo_debito_automatico").val('.');
          $("#numero_cuenta_debito_automatico").val('');
          $("#caducidad_debito_automatico").val('');
          $("#por_deposito_debito_automatico").prop('checked', false);
          $('.contenidoDepositoAutomatico').css('display', 'none')
        }            
      }
    });
  }

  function cargarBancos() {
    $('#debito_automatica').select2({
      width: '100%',
      placeholder: 'Seleccione un Banco',
      ajax: {
        url: '../controlador/facturacion/facturar_pensionC.php?cargarBancos=true&limit=true',
        dataType: 'json',
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: false
      }
    });
  }

  function cargarBancosPreseleccion(preseleccionado) {
    var debito = $('#debito_automatica');
    $.ajax({
      type: 'GET',
      dataType: 'json',
      url: '../controlador/facturacion/facturar_pensionC.php?cargarBancos=true&limit=true&id='+preseleccionado
    }).then(function (data) {
      if(data.length>0){
        var option = new Option(data[0].text, data[0].id, true, true);
        debito.append(option).trigger('change');
        debito.trigger({
            type: 'select2:select',
            params: {
                data: data[0]
            }
        });
      }else{
        $('#debito_automatica').val(null).trigger('change');
      }
    });
  }

  function DCGrupo_No()
  {
    $('#DCGrupo_No').select2({
      placeholder: 'Grupo',
      ajax: {
        url: '../controlador/facturacion/facturar_pensionC.php?DCGrupo_No=true',
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

   function DCGrupo_NoPreseleccion(preseleccionado) {
    var debito = $('#DCGrupo_No');
    $.ajax({
      type: 'GET',
      dataType: 'json',
      url: '../controlador/facturacion/facturar_pensionC.php?DCGrupo_No=true&q='+preseleccionado
    }).then(function (data) {
      if(data.length>0){
        var option = new Option(data[0].text, data[0].id, true, true);
        debito.append(option).trigger('change');
        debito.trigger({
            type: 'select2:select',
            params: {
                data: data[0]
            }
        });
      }else{
        $('#DCGrupo_No').val(null).trigger('change');
      }
    });
  }

  function ClientePreseleccion(preseleccionado) {
    var debito = $('#cliente');
    $.ajax({
      type: 'GET',
      dataType: 'json',
      url: '../controlador/facturacion/facturar_pensionC.php?cliente=true&q='+preseleccionado
    }).then(function (data) {
      if(data.length>0){
        var option = new Option(data[0].text, data[0].id, true, true);
        debito.append(option).trigger('change');
        debito.trigger({
            type: 'select2:select',
            params: {
                data: data[0]
            }
        });
      }else{
        $('#cliente').val(null).trigger('change');
      }
    });
  }

  function Actualiza_Datos_Cliente() {
    if (tempRepresentante !== $('#persona').val() ||
    tempCI !== $('#TextCI').val() ||
    tempTD !== $('#tdCliente').val() ||
    tempTelefono !== $('#telefono').val() ||
    tempDireccion !== $('#direccion').val() ||
    tempDirS !== $("#direccion1").val().toUpperCase() ||
    tempEmail !== $("#email").val().toUpperCase() ||
    tempGrupo !== $("#DCGrupo_No").val() ||
    tempCtaNo !== $('#numero_cuenta_debito_automatico').val() ||
    tempTipoCta !== $('#tipo_debito_automatico').val() ||
    tempDocumento !== $('#debito_automatica').val() ||
    tempCaducidad !== $('#caducidad_debito_automatico').val()) {
      Swal.fire({
          title: 'DESEA ACTUALIZAR DATOS DEL REPRESENTANTE',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si!'
        }).then((result) => {
          if (result.value==true) {

            let CheqPorDeposito ="0";
            if($('#por_deposito_debito_automatico').prop('checked')){
              CheqPorDeposito = "1";
            }

            $('#myModal_espera').modal('show');
            $.ajax({
              type: 'POST',
              dataType: 'json',
              url: '../controlador/facturacion/facturar_pensionC.php?ActualizaDatosCliente=true',
              data: {
                "Representante" : $("#persona").val(),
                "Direccion" : $("#direccion").val(),
                "Telefono" : $("#telefono").val(),
                "Grupo_No" : $("#DCGrupo_No").val(),
                "TextCI" : $("#TextCI").val(),
                "Email" : $("#email").val(),
                "DirS" : $("#direccion1").val().toUpperCase(),
                "CodigoCliente" : $("#codigoCliente").val(),
                "Documento" : $('#debito_automatica').val(),
                "CTipoCta" : $('#tipo_debito_automatico').val(),
                "TxtCtaNo" : $('#numero_cuenta_debito_automatico').val(),
                "MBFecha" : $('#caducidad_debito_automatico').val(),
                "Label18" : $("#tdCliente").val(),
                "CheqPorDeposito" : CheqPorDeposito,
                'DCDebito':$('#debito_automatica').val(),
                'TD_Rep':$("#tdCliente").val(),
              }, 
              success: function(response)
              {
                $('#myModal_espera').modal('hide');  
                if(response.rps){
                  Swal.fire('¡Bien!', response.mensaje, 'success')
                }else{
                  Swal.fire('¡Oops!', response.mensaje, 'warning')
                }        
              },
              error: function () {
                $('#myModal_espera').modal('hide');
                alert("Ocurrio un error inesperado, por favor contacte a soporte.");
              }
            });
          }
        })
    }else{
      Swal.fire({type: 'info',title: 'NO SE ACTUALIZARA DATOS PORQUE USTED NO HA REALIZADO CAMBIOS DEL REPRESENTANTE',text: ''});
      $('#myModal_espera').modal('hide')
    }
  }


  function cambiar_iva(valor)
  {
    codigoCliente = $('#codigo').val();
    catalogoProductos(codigoCliente);
  }
  function cambiarlabel()
  {
     valiva = $("#DCPorcenIVA").val();
     $('#lbl_iva2').text(valiva);
     $('#lbl_iva').text(valiva);
  }

  function codigo() {
  var ci = $('#TextCI').val();
  console.log(ci)
  if (ci != '' && ci != '.') {
    
  $("#myModal_espera").modal('show');
    $.ajax({
      url: '../controlador/modalesC.php?codigo=true',
      type: 'post',
      dataType: 'json',
      data: { ci: ci },
     
      success: function (response) {
        console.log(response);
        // $('#codigoc').val(response.Codigo_RUC_CI);
        $('.spanNIC').text(response.Tipo_Beneficiario);
        $('#tdCliente').val(response.Tipo_Beneficiario)
        $("#myModal_espera").modal('hide');
      }
    });
  } else {

        $("#myModal_espera").modal('hide');   
  }

}
