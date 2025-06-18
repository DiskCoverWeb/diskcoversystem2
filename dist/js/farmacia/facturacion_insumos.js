$( document ).ready(function() {
    numeroFactura();
    autocopletar_servicios();
   	 cargar_pedidos();
     var num_li =0;
   
  });


function autocopletar_servicios(){
      $('#ddl_servicios').select2({
        placeholder: 'Seleccione',
        ajax: {
          url:   '../controlador/farmacia/facturacion_insumosC.php?servicios=true',
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


  function cargar_pedidos()
  {
    
    var comprobante = cod;    
     // console.log(parametros);
     $.ajax({
      data:  {comprobante:comprobante},
      url:   '../controlador/farmacia/facturacion_insumosC.php?datos_comprobante=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
      	console.log(response);
        if(response)
        {
          $('#tbl_body').html(response.tabla);
          $('#paciente').text(response.cliente[0].Cliente);
          $('#detalle').text(response.cliente[0].Concepto);
          $('#fecha').text(response.cliente[0].Fecha.date);
          $('#comp').text(response.cliente[0].Numero);
          $('#txt_total2').text(response.total);
          num_li = response.lineas;
        }
      }
    });
  }

function calcular(id)
{
  console.log(id);
  var por = $('#txt_porcentaje_'+id).val();
  var total = $('#txt_to_'+id).val();
  if(por=='')
  {
    por = 0;
    $('#txt_porcentaje_'+id).val(0);
  }

  var valor = (por/100)*total;
  var tt = 0;
  var gran = parseFloat(total)+parseFloat(valor);

  $('#txt_valor_'+id).val(valor.toFixed(2));
  $('#txt_gran_t_'+id).val(gran.toFixed(2));

  for (var i= 1; i < num_li+1; i++) {
    tt+= parseFloat($('#txt_gran_t_'+i).val());
    
  }
  $('#txt_tt').text(tt.toFixed(2));
  $('#txt_total2').text(tt.toFixed(2));
  
}
function calcular_uti(id)
{
   var  total = $('#txt_to_'+id).val();
   var gran = $('#txt_gran_t_'+id).val();
   if(gran=='')
   {
    gran = 0;
   }
   var valor = gran-total;
   var tt = 0;
   var porc = parseInt((valor*100)/total);

    $('#txt_porcentaje_'+id).val(porc);
    $('#txt_valor_'+id).val(valor.toFixed(2));
    $('#txt_gran_t_'+id).val(parseFloat(gran).toFixed(2));

     for (var i= 1; i < num_li+1; i++) {
    tt+= parseFloat($('#txt_gran_t_'+i).val());
    
  }
  $('#txt_tt').text(tt.toFixed(2));
  $('#txt_total2').text(tt.toFixed(2));

}

function preview()
{
  for (var i= 1; i < num_li+1; i++) {
      $('#btn_linea_'+i).click();
  }
}


function Ver_Comprobante(comprobante)
{
    url='../controlador/farmacia/reporte_descargos_procesadosC.php?Ver_comprobante=true&comprobante='+comprobante;
    window.open(url, '_blank');
}

function Ver_Comprobante_clinica(comprobante)
{
    url='../controlador/farmacia/reporte_descargos_procesadosC.php?Ver_comprobante_clinica=true&comprobante='+comprobante;
    window.open(url, '_blank');
}

function reporte_excel(comprobante)
{
    url='../controlador/farmacia/facturacion_insumosC.php?reporte_excel=true&comprobante='+comprobante;
    window.open(url, '_blank');
}

function reporte_excel_clinica(comprobante)
{
    url='../controlador/farmacia/facturacion_insumosC.php?reporte_excel_clinica=true&comprobante='+comprobante;
    window.open(url, '_blank');
}

function guardar_uti(linea,pos)
{
  var parametros = 
  {
    'linea':linea,
    'utilidad':$('#txt_porcentaje_'+pos).val(),
  } 
      // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/facturacion_insumosC.php?guardar_utilidad=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response==1)
        {
          Swal.fire('Linea editada','','success')
          cargar_pedidos();
          
        }
      }
    });

}


function facturar()
{
  var servicio =$('#ddl_servicios option:selected').text();
  var servicio_cod = $('#ddl_servicios').val();
  if(servicio =='')
  {
    Swal.fire('Seleccione un servicio a facturar','','info');
    return false;
  }

   var parametros = 
  {
    'servicio':servicio,
    'servicio_cod': servicio_cod,
    'comprobante':cod,
    'total':$('#txt_total2').text(),
  } 
      // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/facturacion_insumosC.php?facturar=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response==1)
        {
          Swal.fire('Factura Generada','','success')
          cargar_pedidos();
          
        }
      }
    });
}

 function numeroFactura(){
    $.ajax({
      type: "POST",
      url: '../controlador/farmacia/facturacion_insumosC.php?numFactura=true',
      // data: {
      //   'DCLinea' :'FA',
      // }, 
      success: function(data)
      {
        // datos = JSON.parse(data);
        // labelFac = "("+datos.autorizacion+") No. "+datos.serie;
        // document.querySelector('#numeroSerie').innerText = labelFac;
        $("#factura").val(datos.codigo);
      }
    });
  }
