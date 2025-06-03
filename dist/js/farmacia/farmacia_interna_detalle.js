   $( document ).ready(function() {
   	

   	 if(reporte==1)
   	 {
   	 	$('#lbl_fac').text(factura);
   	 	$('#tipo_cli').text('Proveedor');
   	 	detalle_ingresos();
   	 }

   	 if(reporte==4)
   	 {

   	 	$('#lbl_fac').text(factura);
   	 	if(numero==0)
   	 	{		$('#ti').text('Numero de pedido');	
   	 			$('#tipo_cli').text('Paciente');
   	 			$('#ti_to').text('Total de pedido');
   	 			$('#lbl_fac').text(factura);
   	 	}else
   	 	{		$('#ti').text('Numero de comprobante');
   	 			$('#ti_to').text('Total de comprobante');
   	 			$('#lbl_fac').text(numero);
   	 	}
   	 	detalle_descargo();
   	 }
   })

function detalle_ingresos()
   {
    // $('#tbl_op2').html('<div class="text-center"><img src="../../img/gif/loader4.1.gif"></div>');
   	 var parametros=
    {
      'comprobante':numero,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/farmacia_internaC.php?detalle_reporte_ingresos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
      	console.log(response);
        $('#detalle_ingresos').html(response.tabla);
        $('#proveedor').text(response.proveedor);
        $('#lbl_fecha').text(response.fecha);
        $('#lbl_total').text(response.total);
      }
    });

   }

   function detalle_descargo()
   {
    // $('#tbl_op2').html('<div class="text-center"><img src="../../img/gif/loader4.1.gif"></div>');
   	 var parametros=
    {
      'comprobante':numero,
      'orden':factura,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/farmacia_internaC.php?detalle_reporte_descargos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
      	console.log(response);
        $('#detalle_ingresos').html(response.tabla);
        $('#proveedor').text(response.proveedor);
        $('#lbl_fecha').text(response.fecha);
        $('#lbl_total').text(response.total);
      }
    });

   }

function reporte_excel()
{

   var url = '../controlador/farmacia/farmacia_internaC.php?imprimir_excel_detalle=true&';
   var opcion = $('#ddl_opciones').val();
  
   if(reporte==1)
   {
    url+='opcion=1&comprobante='+numero+'&factura='+factura;    
   }  
  
   if(reporte==4)
   {
    url+='opcion=4&comprobante='+numero+'&factura='+factura;    
   }  
  
    window.open(url, '_blank');
}
