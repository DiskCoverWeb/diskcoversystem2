<?php  //print_r( $_SESSION['SETEOS']);die();?>
<script type="text/javascript">
 $(document).ready(function(){
 		DCTipo();
  })

 function DCTipo()
 {
 	 // parametros = {
   //      'guia':$('#LblGuiaR_').val(),
   //      'serie':$('#DCSerieGR').val(),
   //      'auto':$('#LblAutGuiaRem_').val(),
   //  }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?DCTipo=true',
        // data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	llenarComboList(data,'DCTipo');    
        	Fun_DCSerie();        
        }
    });
 }
 function Fun_DCSerie()
 {
 	 parametros = {
        'tc':$('#DCTipo').val(),
    }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?DCSerie=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	llenarComboList(data,'DCSerie');  
        	fun_DCFact();          
        }
    });
 }

 function fun_DCFact()
 {
 	 parametros = {
        'tc':$('#DCTipo').val(),
        'serie':$('#DCSerie').val(),
    }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?DCFact=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	// console.log(data);
        	llenarComboList(data,'DCFact');            
        }
    });
 }
function detalle_factura()
{
	$('#myModal_espera').modal('show');
	parametros = {
        'tc':$('#DCTipo').val(),
        'serie':$('#DCSerie').val(),
        'factura':$('#DCFact option:selected').text(),
        'Autorizacion':$('#DCFact').val(),
    }
    console.log(parametros);
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?detalle_factura=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {

        	$('#LabelFechaPe').val(formatoDate(data.FA.Fecha.date));  
        	$('#Label7').val(data.FA.Grupo);      
        	$('#LabelCodigo').val(data.FA.CodigoC);      	
        	$('#TxtAutorizacion').val(data.FA.Autorizacion)
        	$('#TxtClaveAcceso').val(data.FA.Clave_Acceso)

        	 $('#Label8').val(data.FA.Razon_Social+", CI/RUC: "+data.FA.CI_RUC+
        	 	        	 			"\n Dirección: "+data.FA.DireccionC+", Teléfono: "+data.FA.TelefonoC+
		                    		"\n Emails: "+data.FA.EmailC+"; "+data.FA.EmailR+
		                    		"\n Elaborado por: "+data.FA.Digitador +" ("+data.FA.Hora+")")

        	switch (data.FA.T) {
						  case 'A':
						    $('#LabelEstado').val('ANULADO');
						    break;
						  case 'P':						    
						  case 'N':
						    $('#LabelEstado').val('PENDIENTE');
						    break;
						  case 'C':
						    $('#LabelEstado').val('CANCELADA');
						    break;
						  default:
						    $('#LabelEstado').val('NO EXISTE');
						}

        	$('#LabelCliente').val(data.FA.Cliente)
        	$('#LabelVendedor').val(" Ejecutivo: "+data.FA.Ejecutivo_Venta)
        	$('#Label15').val(data.FA.Comercial)
        	$('#TxtObs').val(data.FA.Observacion)
        	$('#LabelTransp').val(data.FA.Nota)


        	$('#Cod_CxC').val(data.FA.Cod_CxC)        	
        	$('#Cta_CxP').val(data.FA.Cta_CxP)
        	
        	$('#TextFHasta').val($('#DCFact option:selected').text())
					$('#TextFDesde').val($('#DCFact option:selected').text())


        	$('#tbl_detalle').html(data.detalle);

        	
        	$('#LabelServicio').val(data.FA.Servicio)
        	$('#LabelConIVA').val(data.FA.Con_IVA)
        	$('#LabelSubTotal').val(data.FA.Sin_IVA)
        	$('#LabelSubTotalFA').val(0.00);
        	$('#LabelDesc').val(parseFloat(data.FA.Descuento)+parseFloat(data.FA.Descuento2))
        	$('#LabelIVA').val(data.FA.Total_IVA)
        	$('#LabelTotal').val(data.FA.Total_MN)
        	$('#LabelSaldoAct').val(data.FA.Saldo_MN)


        	//en anular
        		$('#LblSubTotal').val(data.FA.SubTotal - data.FA.Descuento - data.FA.Descuento2);
		 				$('#LblIVA').val(data.FA.Total_IVA);
		 				$('#LblTotal').val(data.FA.Total_MN);
		 				$('#LblSaldo').val(data.FA.Saldo_Actual); 

        		$('#myModal_espera').modal('hide');


        console.log(data);         
        }
    });
}

function abonos_fac()
 {
 	$('#FrmTotalAsiento').css('display','none');
 	 parametros = {
        'TC':$('#DCTipo').val(),
        'Serie':$('#DCSerie').val(),
        'Factura':$('#DCFact option:selected').text(),
        'Autorizacion':$('#DCFact').val(),
    }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?abonos_fac=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	$('#tbl_abonos').html(data);
        }
    });
 }

 function  guias()
 {

 	$('#FrmTotalAsiento').css('display','none');
 	 parametros = {
        'TC':$('#DCTipo').val(),
        'Serie':$('#DCSerie').val(),
        'Factura':$('#DCFact option:selected').text(),
        'Autorizacion':$('#DCFact').val(),
    }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?guias=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	$('#tbl_guias').html(data);
        }
    });
 }

function contabilizacion()
 {

 	 parametros = {
        'TC':$('#DCTipo').val(),
        'Serie':$('#DCSerie').val(),
        'Factura':$('#DCFact option:selected').text(),
        'Autorizacion':$('#DCFact').val(),
    }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?contabilizacion=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	$('#tbl_conta').html(data.tbl);
        	$('#FrmTotalAsiento').css('display','initial');
        	$('#LblDiferencia').val(data.LblDiferencia)
        	$('#LabelDebe').val(data.LabelDebe)
        	$('#LabelHaber').val(data.LabelHaber)
        }
    });
 }

function resultado_sri()
 {
 	$('#FrmTotalAsiento').css('display','none');
 	 parametros = {
        'TC':$('#DCTipo').val(),
        'Serie':$('#DCSerie').val(),
        'Factura':$('#DCFact option:selected').text(),
        'Autorizacion':$('#DCFact').val(),
    }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?resultado_sri=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	$('#tbl_resultados').html(data);
        }
    });
 }

 function anular_factura()
 {
		parametros = {
        'TC':$('#DCTipo').val(),
        'Serie':$('#DCSerie').val(),
        'Factura':$('#DCFact option:selected').text(),
        'Autorizacion':$('#DCFact').val(),
    }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?anular_factura=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	if(data==1)
        	{		        	
				 		/*$('#TipoSuper_MYSQL').val('Auxiliar');
				 		$('#BuscarEn').val('SQL');
				 		$('#clave_supervisor').modal('show');*/
						IngClave('Auxiliar');
				 	}else
				 	{
				 		 Swal.fire('Esta Factura ya esta anulada','','info');
				 	}
        }
    });
 }

 function resp_clave_ingreso(response)
 {
 		if(response.respuesta==1)
 			{
 				$('#clave_supervisor').modal('hide');
 				$('#myModal_anular').modal('show');

 				$('#Label1').val($('#LabelCliente').val());
 				$('#MBoxFecha').val();
 				var tc = $('#DCTipo').val();

 				switch(tc)
 				{
 					case 'PV':
 						tc = " Punto de Venta No."+$('#TxtAutorizacion').val()+'-'+$('#DCSerie').val()+'-';
 						break;
 					case 'NV':
 					 	tc = " Nota de Venta No. "+$('#TxtAutorizacion').val()+'-'+$('#DCSerie').val()+'-';
 						break;
 					default:
 						tc = " Factura No. "+$('#TxtAutorizacion').val()+'-'+$('#DCSerie').val()+'-';
 						break;
 				}
 				$('#Label3').val(tc);
 				$('#Label2').val($('#DCFact option:selected').text());
 				$('#LblAnular').val("SI YA REALIZO EL CIERRE DE CAJA, AL ANULAR ESTA FACTURA TENDRA QUE"+
 				 									"\n VOLVER A REALIZAR EL CIERRE DEL DIA DE EMISION DE LA FACTURA."+
 				 									"\n SOLO SE PUEDE ANULAR FACTURAS SI SE TIENE PRESENTE LA FACTURA ORIGINAL Y COPIA,"+
 				 									"\n SI ES ELECTRONICA SE DEBE COMUNICAR AL CLIENTE DE LA ANULACION.");
 			}
 		
 }
 function anular()
 {
 		if($('#MBoxFecha').val()=='')
 		{
 			Swal.fire('Ingrese una fecha valida','','info');
 			 return false;
 		}
 		 Swal.fire({
       title: 'Esta seguro?',
       text: "Esta seguro que desea proceder, \n con la Factura No. "+$('#DCFact option:selected').text(),
       type: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Si!'
     }).then((result) => {
       if (result.value==true) {

     		 $('#myModal_espera').modal('show');

       		parametros = {
			        'TC':$('#DCTipo').val(),
			        'Serie':$('#DCSerie').val(),
			        'Factura':$('#DCFact option:selected').text(),
			        'Autorizacion':$('#DCFact').val(),
			        'MBoxFecha':$('#MBoxFecha').val(),
			    }
			     $.ajax({
			        type: "POST",
			        url: '../controlador/facturacion/listar_anularC.php?anular=true',
			        data:{parametros:parametros}, 
			        dataType: 'json',
			        success: function(data) {
			        	if(data==1)
			        	{		        								 		
			        		 $('#myModal_espera').modal('hide');
			        		 $('#myModal_anular').modal('hide');
							 		 Swal.fire('comprobante anulada','','success');
							 	}
			        },
			      error: function () {
			        $('#myModal_espera').modal('hide');
			        alert("Ocurrio un error inesperado, por favor contacte a soporte.");
			      }
			    });
       
       }
     })
 }

 function Anular_en_masa()
 {
 		// $('#TipoSuper_MYSQL').val('Auxiliar');
		// $('#BuscarEn').val('SQL');
		// $('#clave_supervisor').modal('show');
  	$('#myModal_espera').modal('show');	
 		parametros = {
        'TC':$('#DCTipo').val(),
        'Serie':$('#DCSerie').val(),
        'Factura':$('#DCFact option:selected').text(),
        'Autorizacion':$('#DCFact').val(),
        'TextFHasta':$('#TextFHasta').val(),
        'TextFDesde':$('#TextFDesde').val(),
        'Cod_CxC':$('#Cod_CxC').val(),
        'Cta_CxP':$('#Cta_CxP').val(),
    }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?Anular_en_masa=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	if(data==1)
        	{		        								 		
        		 $('#myModal_espera').modal('hide');
        		 $('#myModal_anular').modal('hide');
				 		 Swal.fire('comprobantes anulados','','success');
				 	}else
				 	{
				 		 Swal.fire('Rango incorrecto','','info');				 		
				 	}
        },
      error: function () {
        $('#myModal_espera').modal('hide');
        alert("Ocurrio un error inesperado, por favor contacte a soporte.");
      }
    });
 }

 function Volver_Autorizar()
 {
 		parametros = {
        'TC':$('#DCTipo').val(),
        'Serie':$('#DCSerie').val(),
        'Factura':$('#DCFact option:selected').text(),
        'Autorizacion':$('#DCFact').val(),
        'MBFecha':$('#MBFecha').val(),
    }
    $('#myModal_espera').modal('show');
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?Volver_Autorizar=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
             $('#myModal_espera').modal('hide');
            console.log(data);
        	if(data==1)
        	{		        								 		
		        $('#myModal_espera').modal('hide');
		 		Swal.fire('Esta Factura ya esta autorizada','','success');
		 	}
        },
      error: function () {
        $('#myModal_espera').modal('hide');
        alert("Ocurrio un error inesperado, por favor contacte a soporte.");
      }
    });
 }
 function exportar_excel()
 {
    var tc = $('#DCTipo').val();
    var serie = $('#DCSerie').val();
    var Aut = $('#DCFact').val();
    if(tc=='' || serie=='' || Aut=='')
    {
        Swal.fire('No existe datos para exportar','','error');
        return false;
    }


    var parametros = {
        'TC':$('#DCTipo').val(),
        'Serie':$('#DCSerie').val(),
        'Factura':$('#DCFact option:selected').text(),
        'Autorizacion':$('#DCFact').val(),
        'MBFecha':$('#MBFecha').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?exportar_excel_validador=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        if(data==-1)
        {
            Swal.fire('No existe datos para exportar','','error');
        }else
        {
            var url = '../controlador/facturacion/listar_anularC.php?excel_exportar=true&TC='+$('#DCTipo').val()+'&Serie='+$('#DCSerie').val()+'&Factura='+$('#DCFact option:selected').text()+'&Autorizacion='+$('#DCFact').val()+'&MBFecha='+$('#MBFecha').val();
            window.open(url, '_blank');
        }
    },
      error: function () {
        $('#myModal_espera').modal('hide');
        alert("Ocurrio un error inesperado, por favor contacte a soporte.");
      }
    });
 }
 function actualizar_kardex()
 {
    var tc = $('#DCTipo').val();
    var serie = $('#DCSerie').val();
    var factura = $('#DCFact option:selected').text();
    var fac = $('#DCFact').val();
     var parametros = {
        'TC':$('#DCTipo').val(),
        'Serie':$('#DCSerie').val(),
        'Factura':$('#DCFact option:selected').text(),
        'Autorizacion':$('#DCFact').val(),
        'MBFecha':$('#MBFecha').val(),
    }

    if(tc=='' || serie=='' || fac == '')
    {
        Swal.fire('Seleccione un Documento','','info');
        return false;
    }

     Swal.fire({
       title: "Esta Seguro que desea re-activar kardex del \n Documento No. "+tc+': '+serie+'-'+factura,
       text: 'FORMULARIO DE RE-ACTIVACION',
       type: 'info',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       cancelButtonText: 'No',
       confirmButtonText: 'Si!'
     }).then((result) => {
       if (result.value==true) { 
           
            $.ajax({
                type: "POST",
                url: '../controlador/facturacion/listar_anularC.php?actualizar_kardex=true',
                data:{parametros:parametros}, 
                dataType: 'json',
                success: function(data) {
                    if(data==1)
                    {
                        Swal.fire("Proceso Terminado","",'success');
                    }
                    console.log(data);
               
                },
                  error: function () {
                    $('#myModal_espera').modal('hide');
                    alert("Ocurrio un error inesperado, por favor contacte a soporte.");
                  }
                });


       }
     });
 }

 function imprimir()
 {
    var tc = $('#DCTipo').val();
    var serie = $('#DCSerie').val();
    var factura = $('#DCFact option:selected').text();
    var auto = $('#DCFact').val();
    var ci = $('#LabelCodigo').val();
    var parametros = {'TC':tc,'Serie':serie,'Factura':factura,'auto':auto}
    if(tc=='' || serie=='' || auto == '')
    {
        Swal.fire('Seleccione un Documento','','info');
        return false;
    }

    switch(tc) {
    case 'FA':
     src = '../controlador/facturacion/lista_facturasC.php?ver_fac=true&codigo='+factura+'&ser='+serie+'&ci='+ci+'&auto='+auto+'&per=.';     
    break;
    case 'LC':
     src = '../controlador/facturacion/lista_liquidacionCompraC.php?ver_fac=true&codigo='+factura+'&ser='+serie+'&ci='+ci+'&per=.';       
    break;
    case 'NC':
        var url = '../controlador/facturacion/lista_notas_creditoC.php?Ver_nota_credito=true&nota='+factura+'&serie='+serie;  
    break;
    case 'GR':
     src = '../controlador/facturacion/lista_guia_remisionC.php?Ver_guia_remision=true&tc='+tc+'&factura='+factura+'&serie='+serie+'&Auto='+auto+'&AutoGR='+auto;   
    break;
    case 'OP':
    // code block
    break;
      default:
        // code block
    }

  /*  $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?validar_existencia=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
            if(data==1)
            {
                Swal.fire("Proceso Terminado","",'success');
            }
            console.log(data);
       
        },
        error: function () {
            $('#myModal_espera').modal('hide');
            alert("Ocurrio un error inesperado, por favor contacte a soporte.");
        }
    });
*/


    window.open(src, '_blank');
 }



</script>
<div class="row">
 	
	<!--<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
		<div class="col">
			<div class="col-sm-1" style="padding: 0px;">
			  <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
    			<img src="../../img/png/salire.png">
    	 	</a>
    	</div>
    </div>    
  <div class="col">
  	<div class="col-sm-1" style="padding: 0px;">
	    <button type="button" class="btn btn-default"  data-toggle="dropdown" title="Descargar PDF" style="padding: 6px;">
	      <img src="../../img/png/impresora.png">
	      
	    </button>
	      <ul class="dropdown-menu">
	        <li><a href="#" id="imprimir_pdf">Factura Individual</a></li>
	        <li><a href="#" id="imprimir_pdf_2">Facturas en Bloque</a></li> <li><a href="#" id="imprimir_pdf_2">Imprime en impresora P.V.</a></li>
	       	<li><a href="#" id="imprimir_pdf_2">Nota de Crédito</a></li>
	       	<li><a href="#" id="imprimir_pdf_2">Recibos en Bloque</a></li>
	        <li><a href="#" id="imprimir_pdf_2">Guia de Remision</a></li>     
	      </ul>
	    </div>
  </div>
  <div class="col">
  	<div class="col-sm-1" style="padding: 0px;">
    <button type="button" class="btn btn-default"  data-toggle="dropdown" title="Descargar PDF" style="padding: 6px;">
      <img src="../../img/png/email.png">
      
    </button>
      <ul class="dropdown-menu">
        <li><a href="#" id="imprimir_pdf">Enviar Email Factura</a></li>
        <li><a href="#" id="imprimir_pdf_2">Enviar Email Nota de Credito</a></li>
        <li><a href="#" id="imprimir_pdf_2">Enviar Email Guia de Remision</a></li>
      </ul>
      </div>
  </div>
  <div class="col">
  	<div class="col-sm-1" style="padding: 0px;">
    <button type="button" class="btn btn-default" onclick="imprimir()" title="Descargar PDF" style="padding: 6px;">
      <img src="../../img/png/pdf.png">
      
    </button>
     
    </div>
  </div>
   <div class="col">
    	<button title="Consultar Mayores auxiliares"  data-toggle="tooltip" class="btn btn-default" onclick="consultar_datos(true,Individual);" style="padding: 6px;">
    		<img src="../../img/png/mes.png" >
    	</button>
    </div>		
    <div class="col">
    	<button title="Consultar Mayores auxiliares"  data-toggle="tooltip" class="btn btn-default" onclick="consultar_datos(true,Individual);" style="padding: 6px;">
    		<img src="../../img/png/calendario.png" >
    	</button>
    </div>		   
    <div class="col">
    	<button title="Consultar Mayores auxiliares"  data-toggle="tooltip" class="btn btn-default" onclick="consultar_datos(true,Individual);" style="padding: 6px;">
    		<img src="../../img/png/edit_file.png" >
    	</button>
    </div>
     <div class="col">
    	<button title="Consultar Mayores auxiliares"  data-toggle="tooltip" class="btn btn-default" onclick="consultar_datos(true,Individual);" style="padding: 6px;">
    		<img src="../../img/png/change_number.png" >
    	</button>
    </div>		
    <div class="col">
    	<button title="Consultar Mayores auxiliares"  data-toggle="tooltip" class="btn btn-default" onclick="consultar_datos(true,Individual);" style="padding: 6px;">
    		<img src="../../img/png/saldos.png" >
    	</button>
    </div>		
    <div class="col">
    	<button title="Consultar Mayores auxiliares"  data-toggle="tooltip" class="btn btn-default" onclick="consultar_datos(true,Individual);">
    		<img src="../../img/png/resumen.png" >
    	</button>
    </div>				
    <div class="col">
    	<button title="Consultar Mayores auxiliares"  data-toggle="tooltip" class="btn btn-default" onclick="consultar_datos(true,Individual);">
    		<img src="../../img/png/cambiar.jpeg" >
    	</button>
    	</div>		
    <div class="col">
    	<button title="Consultar Mayores auxiliares"  data-toggle="tooltip" class="btn btn-default" onclick="consultar_datos(true,Individual);">
    		<img src="../../img/png/users.png" >
    	</button>
    	</div>		
    <div class="col">
    	 	<div class="col-sm-1" style="padding: 0px;">
		    	<button title="Anular Factura"  data-toggle="tooltip" class="btn btn-default" onclick="anular_factura();">
		    		<img src="../../img/png/bloqueo.png" >
		    	</button>
		    </div>
		</div>		
    <div class="col">
     	<div class="col-sm-1" style="padding: 0px;">
	    	<button title="Anular en masa"  data-toggle="tooltip" class="btn btn-default" onclick="Anular_en_masa()">
	    		<img src="../../img/png/anular.png" >
	    	</button>
	    </div>
	  </div>		
    <div class="col">
     	
	    	<button title="Consultar Mayores auxiliares" data-toggle="dropdown" class="btn btn-default">
	    		<img src="../../img/png/sri_azul.jpg" >
	    	</button>
				<ul class="dropdown-menu">
	        <li><a href="#" id="" onclick="Volver_Autorizar()">Factura Actual</a></li>
	        <li><a href="#" id="imprimir_pdf">Nota de credito</a></li>
	        <li><a href="#" id="imprimir_pdf_2">Facturas pendientes</a></li>
	        <li><a href="#" id="imprimir_pdf">Guia de Remision</a></li>  
	      </ul>
	    
    </div>		
    <div class="col">
    	<button title="Consultar Mayores auxiliares"  data-toggle="tooltip" class="btn btn-default" onclick="consultar_datos(true,Individual);">
    		<img src="../../img/png/sri_blanco.jpg" >
    	</button>
    	</div>		
    <div class="col">
    	<button title="Consultar Mayores auxiliares"  data-toggle="tooltip" class="btn btn-default" onclick="consultar_datos(true,Individual);">
    		<img src="../../img/png/ejecutivo.png" >
    	</button>
    	</div>		
    <div class="col">
        <div class="col-sm-1" style="padding:0px">
    	   <button title="Actualizar Kardex"  data-toggle="tooltip" class="btn btn-default" onclick="actualizar_kardex();">
    	       	<img src="../../img/png/update_kardex.png" >
    	   </button>
    	</div>
    </div>		
    <div class="col">
        <div class="col-sm-1" style="padding:0px">
            <button title="Excel"  data-toggle="tooltip" class="btn btn-default" id="" onclick="exportar_excel()">
                <img src="../../img/png/excel2.png" >
            </button>
        </div>    	
    </div>
  </div>-->
</div>
<form id="form_nc">
	<input type="hidden" name="Cod_CxC" id="Cod_CxC" value="">
	<input type="hidden" name="Cta_CxP" id="Cta_CxP" value="">
<div class="row">
	<div class="col-sm-12">
		
		<div class="panel panel-primary" style="margin-bottom: 0px;">			
			<div class="panel-body">
				<div class="row"  style="padding:3px">
					<div class="col-md-2 col-sm-2 col-xs-2" style="padding-right: 0px;">   
             <div class="input-group">
               <div class="input-group-addon input-xs">
                 <b>Tipo de documento:</b>
               </div>
               <select class="form-control input-xs" id="DCTipo" name="DCTipo" onchange="Fun_DCSerie()" style="padding: 0px;">
        					<option value="">Seleccione</option>
				        </select>
             </div>
          </div>
          <div class="col-md-2 col-sm-1 col-xs-1">   
            <div class="input-group">
              <div class="input-group-addon input-xs">
                <b>Serie:</b>
              </div>
              <select class="form-control input-xs" id="DCSerie" name="DCSerie" onchange="fun_DCFact()">
				         	<option value="">Seleccione</option>
				      </select>
          	</div>
          </div>
          <div class="col-md-2 col-sm-2 col-xs-2" style="padding-right: 0px;">   
             <div class="input-group">
               <div class="input-group-addon input-xs">
                 <b>Secuencuial No:</b>
               </div>
                <select class="form-control input-xs" id="DCFact" name="DCFact" onchange="detalle_factura()" style="padding:2px">
				          	<option value="">Seleccione</option>
				        </select>
             </div>
          </div>
					<div class="col-sm-2">
						<input type="date" name="LabelFechaPe" id="LabelFechaPe" class="form-control input-xs" value="" readonly>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2" style="padding-right: 0px;">   
             <div class="input-group">
                <span class="input-group-btn" style="width: auto;">
                 <input type="text" name="Label7" id="Label7" class="form-control input-xs" value="">
								</span>
								<input type="text" name="LabelCodigo" id="LabelCodigo" class="form-control input-xs" value="0.00">              
             </div>
          </div>			
					<div class="col-sm-2">
						<input type="text" name="LabelEstado" id="LabelEstado" class="form-control input-xs" value="0.00">
					</div>
				</div>
				<div class="row" style="padding:3px">
					<div class="col-md-6 col-sm-6 col-xs-6">   
	          <div class="input-group">
	            <div class="input-group-addon input-xs">
	              <b>Clave de Acceso:</b>
	            </div>                         
							<input type="text" name="TxtClaveAcceso" id="TxtClaveAcceso" class="form-control input-xs" value=".">
            </div>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-6">   
            <div class="input-group">
              <div class="input-group-addon input-xs">
              	<b>autorizacion:</b>
              </div>                         
							<input type="text" name="TxtAutorizacion" id="TxtAutorizacion" class="form-control input-xs" value=".">
            </div>
          </div>
				</div>
				
				<div class="row" style="padding:3px">
					<div class="col-sm-2">   
            <div class="input-group">
              <div class="input-group-addon input-xs">
              	<b>Desde:</b>
              </div>                         
							<input type="text" name="TextFDesde" id="TextFDesde" class="form-control input-xs" value="0.00">
            </div>
          </div>
          <div class="col-sm-2">   
            <div class="input-group">
              <div class="input-group-addon input-xs">
              	<b>Hasta:</b>
              </div>                         
							<input type="text" name="TextFHasta" id="TextFHasta" class="form-control input-xs" value="0.00">
							<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-xs"><i class="fa fa-arrow-up"></i></button>
									<button type="button" class="btn btn-default btn-xs"><i class="fa fa-arrow-down"></i></button>
							</span>
            </div>
          </div>
           <div class="col-sm-2">   
           		<input type="date" name="MBFecha" id="MBFecha" class="form-control input-xs" value="<?php echo date('Y-m-d') ?>">
          </div>
          <div class="col-sm-1" style="padding:0px">   
           		<label><input type="checkbox" name="CheqSoloCopia" id="CheqSoloCopia"> Imprimir solo copia</label>
          </div>
          <div class="col-sm-1" style="padding:0px">   
           		<label><input type="checkbox" name="CheqMatricula" id="CheqMatricula"> Sin deuda pendiente</label>
          </div>
          <div class="col-sm-2" style="padding:0px">   
           		<label><input type="checkbox" name="CheqSinCodigo" id="CheqSinCodigo"> Imprimir sin codigo de alumno</label>
          </div>         
          <div class="col-sm-1 text-right">   
          	<button class="btn btn-default btn-sm"> Actualizar Alumno</button>
          </div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="row">
								<div class="col-sm-12">   
			            <div class="input-group">
			              <div class="input-group-addon input-xs">
			              	<b>Cliente:</b>
			              </div>                         
										<input type="text" name="LabelCliente" id="LabelCliente" class="form-control input-xs" value="0.00">
			            </div>
			          </div>
			          <div class="col-sm-12">  
			            <div class="input-group">
			              <div class="input-group-addon input-xs">
			              	<b>No. de Bultos:</b>
			              </div>
			              <div class="row">
			              <div class="col-sm-3" style="padding-right: 0px;">
											<input type="text" class="form-control input-xs" id="LabelBultos" name="LabelBultos" placeholder=".">
										</div>
										<div class="col-sm-9" style="padding-left: 0px;">
												<input type="text" name="LabelVendedor" id="LabelVendedor" class="form-control input-xs" value="">
										</div>
										</div>
			            </div>
			          </div>
			          <div class="col-sm-12">   
			            <div class="input-group">
			              <div class="input-group-addon input-xs">
			              	<b>Entregado en:</b>
			              </div>                         
										<input type="text" name="Label15" id="Label15" class="form-control input-xs" value="0.00">
			            </div>
			          </div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-12">
								<textarea rows="4" style="resize:none;font-size: 11px;" class="form-control" readonly id="Label8" name="Label8"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">   
            <div class="input-group">
              <div class="input-group-addon input-xs">
              	<b>Observacion:</b>
              </div>                         
							<input type="text" name="TxtObs" id="TxtObs" class="form-control input-xs" value="">
            </div>
          </div>
          <div class="col-sm-12">   
            <div class="input-group">
              <div class="input-group-addon input-xs">
              	<b>Nota:</b>
              </div>                         
							<input type="text" name="LabelTransp" id="LabelTransp" class="form-control input-xs" value="">
            </div>
          </div>					
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
      <div class="panel panel-primary">
      	<div class="panel-body">
	          <ul class="nav nav-tabs">
	            <li class="active"><a href="#tab_detalle" data-toggle="tab" onclick="$('#FrmTotalAsiento').css('display','none');">DETALLE DE FACTURA</a></li>
	            <li><a href="#tab_abono" data-toggle="tab" onclick="abonos_fac()">ABONOS DE LA FACTURA</a></li>
	            <li><a href="#tab_guia" data-toggle="tab" onclick="guias()">GUIA DE REMISION</a></li>
	            <li><a href="#tab_conta" data-toggle="tab" onclick="contabilizacion()">CONTABILIZACION</a></li>
	            <li><a href="#tab_resultado" data-toggle="tab" onclick="resultado_sri()">RESULTADO SRI</a></li>
	          </ul>
	          <div class="tab-content">
	            <div class="tab-pane fade in active" id="tab_detalle"> 
	            	<div class="row" >
	            		<br>
	            		<div class="col-sm-12" id="tbl_detalle">
	            			
	            		</div>
	            		
	            	</div>
	            </div>
	            <div class="tab-pane fade" id="tab_abono">
	              <div class="row" >
	            		<br>
	            		<div class="col-sm-12" id="tbl_abonos">
	            			
	            		</div>
	            		
	            	</div>
	            </div>
	            <div class="tab-pane fade" id="tab_guia">
	            	<div class="row" >
	            		<br>
	            		<div class="col-sm-12" id="tbl_guias">
	            			
	            		</div>
	            		
	            	</div>
	            </div>
	            <div class="tab-pane fade" id="tab_conta">
	            	<div class="row" >
	            		<br>
	            		<div class="col-sm-12" id="tbl_conta">
	            			
	            		</div>	            		
	            	</div>
	            </div>
	            <div class="tab-pane fade" id="tab_resultado">
	              <div class="row" >
	            		<br>
	            		<div class="col-sm-12" id="tbl_resultados">
	            			
	            		</div>
	            		
	            	</div>
	            </div>
	      </div>
      </div>
    </div>
  </div>
</div>
<div class="row" id="FrmTotalAsiento" style="display:none;">	
	<div class="col-sm-6">
		<div class="row">
				<div class="col-sm-6">   
          <div class="input-group">
            <div class="input-group-addon input-xs">
            	<b>Diferencias:</b>
            </div>                         
						<input type="text" name="LblDiferencia" id="LblDiferencia" class="form-control input-xs" value="0.00">
          </div>
        </div>
        <div class="col-sm-6">  
          <div class="input-group">
            <div class="input-group-addon input-xs">
            	<b>TOTALES:</b>
            </div>
            <div class="row">
            <div class="col-sm-6" style="padding-right: 0px;">
							<input type="text" class="form-control input-xs" id="LabelDebe" name="LabelDebe" placeholder="0.00">
						</div>
						<div class="col-sm-6" style="padding-left: 0px;">
								<input type="text" name="LabelHaber" id="LabelHaber" class="form-control input-xs" value="0.00">
						</div>
						</div>
          </div>
        </div>			         
		</div>
	</div>
</div>
<div class="row">	
	<div class="col-sm-2"  style="padding-right: 1px">
     	<label>Subtotal sin iva</label>
      <input type="text" name="LabelSubTotal" id="LabelSubTotal" class="form-control input-xs">
	</div>
	<div class="col-sm-2"  style="padding:1px">
      <label>Subtotal con iva</label>
      <input type="text" name="LabelConIVA" id="LabelConIVA" class="form-control input-xs"> 
	</div>
	<div class="col-sm-1"  style="padding:1px">
      <label>Descuento</label>
      <input type="text" name="LabelDesc" id="LabelDesc" class="form-control input-xs">
  </div>
	<div class="col-sm-1"  style="padding:1px">
     <label> Subtotal</label>
     <input type="text" name="LabelSubTotalFA" id="LabelSubTotalFA" class="form-control input-xs">
 	</div>
	<div class="col-sm-1"  style="padding:1px">
    <label> I.V.A</label>
    <input type="text" name="LabelIVA" id="LabelIVA" class="form-control input-xs">
	</div>
	<div class="col-sm-2"  style="padding:1px">
	    <label>Subtotal Servicios</label>
      <input type="text" name="LabelServicio" id="LabelServicio" class="form-control input-xs">
  </div>
  <div class="col-sm-1"  style="padding:1px">
	    <label>Total Factura</label>
      <input type="text" name="LabelTotal" id="LabelTotal" class="form-control input-xs">
  </div>
  <div class="col-sm-1"  style="padding:1px">
	    <label>Saldo actual</label>
      <input type="text" name="LabelSaldoAct" id="LabelSaldoAct" class="form-control input-xs">
  </div>
</div>

</form>


<div id="myModal_anular" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Anulacion de factura</h4>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-sm-10">
      			<div class=" row">
      				<div class="col-sm-8">
      						<input type="" class="form-control input-xs" readonly name="Label1" id="Label1"> 
      				</div>
      				<div class="col-sm-4" style="padding-left:0px">
      					<div class="input-group">
									<span class="input-group-addon input-xs" style="padding: 5px 4px;">Fecha</span>
									<input type="date" class="form-control input-xs" style="width: 88%; padding: 5px;" name="MBoxFecha" id="MBoxFecha">
								</div>
      				</div>      				
      			</div>   
      			<div class=" row">
      				<div class="col-sm-9">
      						<input type="" class="form-control input-xs text-right" readonly name="Label3" id="Label3" value="Factura No.">
      				</div>
      				<div class="col-sm-3">
      						<input readonly type="" class="form-control input-xs" name="Label2" style="color: coral;" id="Label2">      				
      				</div>      				
      			</div>   
      			<div class=" row">
      				<div class="col-sm-3">
      					<b>subTotal</b>
      						<input readonly type="" class="form-control input-xs" name="LblSubTotal" id="LblSubTotal" value="0.00">
      				</div>
      				<div class="col-sm-3">
      					<b>I.V.A</b>
      						<input readonly type="" class="form-control input-xs" name="LblIVA" id="LblIVA" value="0.00">
      				</div>      		
      				<div class="col-sm-3">
      					<b>Total</b>
      						<input readonly type="" class="form-control input-xs" name="LblTotal" id="LblTotal" value="0.00">
      				</div>
      				<div class="col-sm-3">
      					<b style="font-size: 13px;">Saldo pendiente</b>
      						<input readonly type="" class="form-control input-xs" name="LblSaldo" id="LblSaldo" value="0.00">
      				</div>      				
      			</div> 
      			<div class="row">
      				<div class="col-sm-12">
      					<textarea readonly style="resize: none; font-size: 11px; color: coral;" rows="4" name="LblAnular" id="LblAnular" class="form-control"></textarea>
      				</div>      				
      			</div>     			
      		</div>
      		<div class="col-sm-2">
			      <button  class="btn btn-default" onclick="anular()"style="padding: 6px;">
			    		<img src="../../img/png/grabar.png" >
			    		<br>
			    		Anulacion
			    	</button>
			    	<button  data-dismiss="modal" class="btn btn-default"  style="padding: 6px;">
			    		<img src="../../img/png/salire.png">
			    		<br>
			    		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Salir&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			    	</button>
      		</div>      		
      	</div>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div> -->
    </div>
  </div>
</div>