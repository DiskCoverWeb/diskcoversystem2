 let tablaKardex;
 $(document).ready(function()
  {
  	delete_sientos_nc();
  	DCBodega();
  	DCMarca();
		autocoplete_contraCta()
  	autocoplete_articulos();

  	autocoplete_clinete();
    DCLineas();
    catalogoLineas();


  	$('#DCClientes').on('select2:select', function (e) {
      // console.log(e);
      var data = e.params.data.data;
      // var dataM = e.params.data.dataMatricula;
      $('#TxtConcepto').val('Nota de Crédito de: '+data.Cliente);
        fecha= fecha_actual();
      	// DCLineas(data.Cta_CxP);
      	DCTC(data.Codigo)    
    });

    $('#DCArticulo').on('select2:select', function (e) {
      // console.log(e);
      var data = e.params.data.data;
      console.log(data);
      $('#TextVUnit').val(data.PVP);
      $('#TextIva').val(data.IVA);
      // $('#LabelVTotal').val(data.);
    }); 	 

     tablaKardex = $('#tbl_nota_credito').DataTable({
        scrollX: true,
        searching: false,
        responsive: false,
        // paging: false,   
        info: false,   
        autoWidth: false,  
        ajax: {
            url: '../controlador/facturacion/notas_creditoC.php?tabla=true',
            type: "POST",
            data: function(d) {
                  // var parametros = {                    
                  //   desde: $('#txt_desde').val(),
                  //   hasta: $('#txt_hasta').val()
                  // };
                  // return { parametros: parametros };
            },      
             dataSrc: function(data) {
                 $('#TxtConIVA').val(data.TxtConIVA);
                 $('#TxtDescuento').val(data.TxtDescuento);
                 $('#TxtIVA').val(data.TxtIVA);
                 $('#TxtSaldo').val(data.TxtSaldo);
                 $('#TxtSinIVA').val(data.TxtSinIVA);
                 $('#LblTotalDC').val(data.LblTotalDC);

                console.log(data);
                // Devolver solo la parte de la tabla para DataTables
                return data.tabla;
            }        
        },
        columns: [

            { data: null,
                 render: function(data, type, row) {
                    return `<button type="button" class="btn btn-sm btn-danger" onclick="eliminar('${data.CODIGO}','${data.A_No}')"><i class="bx bx-trash me-0"></i></button>`;
                }
            },
            { data: 'CODIGO' },
            { data: 'PRODUCTO' },
            { data: 'CANT' },
            { data: 'PVP' },
            { data: 'SUBTOTAL' },
            { data: 'TOTAL_IVA' },
            { data: 'DESCUENTO' },
            { data: 'CodBod' },
            { data: 'CodMar' },
            { data: 'Item' },
            { data: 'CodigoU' },
            { data: 'Codigo_C' },
            { data: 'Ok' },
            { data: 'COSTO' },
            { data: 'Cod_Ejec' },
            { data: 'Porc_C' },
            { data: 'Porc_IVA' },
            { data: 'Mes' },
            { data: 'Mes_No' },
            { data: 'Anio' },
            { data: 'Cta_Inventario' },
            { data: 'Cta_Costo' },
            { data: 'A_No' }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        }
    });

  })

function delete_sientos_nc()
{
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?delete_sientos_nc=true',
      // data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
         //console.log(data);
         //$('#tbl_datos').html(data);
      }
    });
}

function DCBodega()
{
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?DCBodega=true',
      // data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
      	llenarComboList(data,'DCBodega'); 
      }
    });
}
function DCMarca()
{
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?DCMarca=true',
      // data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
      	llenarComboList(data,'DCMarca'); 
      }
    });
}

function DCMarca()
{
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?DCMarca=true',
      // data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
      	llenarComboList(data,'DCMarca'); 
      }
    });
}


function autocoplete_articulos(){
    $('#DCArticulo').select2({
      placeholder: 'Seleccione articulos',
      width:'90%',
      ajax: {
        url:   '../controlador/facturacion/notas_creditoC.php?DCArticulo=true',
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

function autocoplete_contraCta(){
    $('#DCContraCta').select2({
      placeholder: 'Seleccione cuenta',
      width:'90%',
      ajax: {
        url: '../controlador/facturacion/notas_creditoC.php?DCContraCta=true',
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


function cargar_tabla()
{
  if ( $.fn.DataTable.isDataTable('#tbl_nota_credito') ) {
      tablaKardex.ajax.reload(); // Si ya existe, recarga los datos
  } 

  	//  $.ajax({
    //   type: "POST",
    //   url: '../controlador/facturacion/notas_creditoC.php?tabla=true',
    //   // data: {parametros: parametros},
    //   dataType:'json', 
    //   success: function(data)
    //   {
    //      $('#tbl_datos').html(data.tabla);

    //      $('#TxtConIVA').val(data.TxtConIVA);
    //      $('#TxtDescuento').val(data.TxtDescuento);
    //      $('#TxtIVA').val(data.TxtIVA);
    //      $('#TxtSaldo').val(data.TxtSaldo);
    //      $('#TxtSinIVA').val(data.TxtSinIVA);
    //      $('#LblTotalDC').val(data.LblTotalDC);
    //   }
    // });
}

function DCLineas()
{
	var parametros = 
	{
		'fecha':$('#MBoxFecha').val(),
		// 'cta_cxp':cta_cxp,
	}
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?DCLineas=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
      // console.log(data);         
       // llenarComboList(data,'DCLineas');
       // $('#TextBanco').val(data[0].Autorizacion);
       // $('#TextCheqNo').val(data[0].codigo);
       // numero_autorizacion();
      }
    });
}

function catalogoLineas() {
    $('#myModal_espera').modal('show');
    var cursos = $("#DCLineas");
    fechaEmision = $('#MBoxFecha').val();
    fechaVencimiento = $('#MBoxFecha').val();
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturar_pensionC.php?catalogo=true',
        data: {
            'fechaVencimiento': fechaVencimiento,
            'fechaEmision': fechaEmision,
            'tipo':'NC',
        },
        dataType: 'json',
        success: function(data) {
          console.log(data);

           setTimeout(()=>{$('#myModal_espera').modal('hide'); }, 1000);
          // llenarComboList(data,'DCLineas');


            if (data.length > 0) {

                datos = data;
                // Limpiamos el select
                cursos.find('option').remove();
                for (var indice in datos) {
                    cursos.append('<option value="' + datos[indice].id + " " + datos[indice].text + ' ">' +
                        datos[indice].text + '</option>');
                }

               $('#TextBanco').val(data[0].data.Autorizacion);
               $('#TextCheqNo').val(data[0].data.Serie);
               autocoplete_clinete()

            } else {
                $('#myModal_espera').modal('hide');
                Swal.fire({
                    icon: 'info',
                    title: 'Usted no tiene un punto de emsion asignado  o esta mal configurado, contacte con la administracion del sistema',
                    text: '',
                    allowOutsideClick: false,
                }).then(() => {
                    console.log('ingresa');
                    location.href = '../vista/modulos.php';
                });

            }

            // tipo_documento();
            // numeroFactura();
           numero_autorizacion();
        }
    });
}



function numero_autorizacion()
{
  var parametros = 
  {
    'serie':$('#TextCheqNo').val(),
  }
     $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?numero_autorizacion=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
        console.log(data);
        $('#TextCompRet').val(data);
      }
    });
}

function DCTC(codigoC)
{
	var parametros = 
	{
		'CodigoC':codigoC,
	}
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?DCTC=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
         if(data.length==0)
         {
         	 Swal.fire('Este Cliente no ha empezado a generar facturas','','info')
         }else
         {
         	llenarComboList(data,'DCTC');
         	// console.log(data);
         	DCSerie(data[0].codigo,codigoC);
         	 // console.log(data);
         }
      }
    });
}

function DCSerie(TC=false,codigoC=false)
{
	if(TC==false)	{		TC = $('#DCTC').val();	}
	if(codigoC==false)	{		codigoC = $('#DCClientes').val();	}
	var parametros = 
	{
		'TC':TC,
		'CodigoC':codigoC,
	}
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?DCSerie=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
         if(data.length==0)
         {
         	 Swal.fire('Este Cliente no ha empezado a generar facturas','','info')
         }else
         {
         	llenarComboList(data,'DCSerie');
         	DCFactura(data[0].codigo,TC,codigoC)
         	 // console.log(data);
         }
      }
    });
}

function DCFacturaNuevo(Serie=false,TC=false,codigoC=false,Factura=false)
{
	if(Serie==false)	{		Serie = $('#DCSerie').val();	}
	if(TC==false)	{		TC = $('#DCTC').val();	}
	if(codigoC==false)	{		codigoC = $('#DCClientes').val();	}

	var parametros = 
	{
		'Serie':Serie,
		'TC':TC,
		'CodigoC':codigoC,
    'Factura':Factura,
	}
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?DCFactura=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
         if(data.length==0)
         {
         	 Swal.fire('Este Cliente no ha empezado a generar facturas','','info')
         }else
         {
          $('#porc_iva').val( data[0].data['Porc_IVA'].toFixed(2));
         	 console.log(data);
         }
      }
    });
}

function DCFactura(Serie=false,TC=false,codigoC=false)
{
  if(Serie==false)  {   Serie = $('#DCSerie').val();  }
  if(TC==false) {   TC = $('#DCTC').val();  }
  if(codigoC==false)  {   codigoC = $('#DCClientes').val(); }

  var parametros = 
  {
    'Serie':Serie,
    'TC':TC,
    'CodigoC':codigoC,
  }
     $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?DCFactura=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
         if(data.length==0)
         {
           Swal.fire('Este Cliente no ha empezado a generar facturas','','info')
         }else
         {
          llenarComboList(data,'DCFactura');
          Detalle_Factura(data[0].codigo,Serie,TC,codigoC)
          $('#porc_iva').val( data[0].data['Porc_IVA'].toFixed(2));
         }
      }
    });
}

function Detalle_Factura(Factura=false,Serie=false,TC=false,codigoC=false)
{

	if(Factura==false)	{	Factura = $('#DCFactura').val();	}
	if(Serie==false)	{	Serie = $('#DCSerie').val();	}
	if(TC==false)	{	TC = $('#DCTC').val();	}
	if(codigoC==false)	{	codigoC = $('#DCClientes').val();	}
  DCFacturaNuevo(Serie,TC,codigoC,Factura)
	var parametros = 
	{		
		'Factura':Factura,
		'Serie':Serie,
		'TC':TC,
		'CodigoC':codigoC,
	}
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?Detalle_Factura=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
        // console.log(data);
        
        $('#TxtAutorizacion').val(data[0].Autorizacion);
        $('#LblTotal').val(data[0].Total_MN); 
        $('#LblSaldo').val(data[0].Saldo_MN); 
        Lineas_Factura(Factura,Serie,TC,data[0].Autorizacion)
        cargar_tabla();
         
      }
    });
}

function Lineas_Factura(Factura=false,Serie=false,TC=false,Autorizacion=false)
{
	if(Factura==false)	{	Factura = $('#DCFactura').val();	}
	if(Serie==false)	{	Serie = $('#DCSerie').val();	}
	if(TC==false)	{	TC = $('#DCTC').val();	}

	var parametros = 
	{		
		'Factura':Factura,
		'Serie':Serie,
		'TC':TC,
		'Autorizacion':Autorizacion,
		'Fecha':$('#MBoxFecha').val(),
		'CodigoC':$('#DCClientes').val(),
	}
  	 $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?Lineas_Factura=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      { 
        if(data!=null)
        {       
          $('#TxtAutorizacion').val(data[0].Autorizacion);
          $('#LblTotal').val(data[0].Saldo_MN); 
          $('#LblSaldo').val(data[0].Total_MN); 
        }
         
      }
    });
}
function autocoplete_clinete(){
    var serie = $('#TextCheqNo').val();
      $('#DCClientes').select2({
        placeholder: 'Seleccione una beneficiario',
        width:'90%',
        ajax: {
          url:   '../controlador/facturacion/notas_creditoC.php?cliente=true&serie='+serie,
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

 function calcular() {
    var VUnit = $('#TextVUnit').val();
    if (VUnit == '' || VUnit == 0) {
      Swal.fire('INGRESE UN PRECIO VALIDO', '', 'info').then(function () { $('#TextVUnit').select() })
    }
    var Cant = $('#TextCant').val();
    var ivaOp = $('#TextIva').val();

    // var OpcMult = $('#OpcMult').prop('checked');
    // var ban = $('#TextCheque').val()
    if (is_numeric(VUnit) && is_numeric(Cant)) {
    
       Real2 = parseFloat(Cant) * parseFloat(VUnit); 
       valorIva = parseFloat($('#porc_iva').val())+1;
       if(ivaOp==1)
        {
           Real1 = Real2*valorIva;
           $('#TextIvaTotal').val( (Real1-Real2).toFixed(4));
        }else{
          Real1 = Real2;
        }

      //} else { Real1 = parseFloat(Cant) / parseFloat(VUnit); }
      // console.log(Real1);
      $('#LabelVTotal').val(Real1.toFixed(4));
    } else {
      $('#LabelVTotal').val(0.0000);
    }
  }


function TextDesc_lost()
{
   Factura = $('#DCFactura').val();  
   Serie = $('#DCSerie').val(); 
   TC = $('#DCTC').val();  
   codigoC = $('#DCClientes').val(); 
   auto =  $('#TxtAutorizacion').val();
   cant = $('#TextCant').val();
   if($('#TextCant').val()=='' || $('#TextCant').val()=='0' || $('#TextCant').val() =='.')
   {

      Swal.fire('Cantidad invalida','','info')
      return false;
   }

    var parametros = 
    {   
      'productos':$('#DCArticulo').val(),
      'productosName':$('#DCArticulo option:selected').text(),
      'Factura':Factura,
      'Serie':Serie,
      'CodigoC':codigoC,
      'TC':TC,
      'Autorizacion':auto,
      'TextCant':$('#TextCant').val(),
      'TextVUnit':$('#TextVUnit').val(),
      'TextDesc':$('#TextDesc').val(),
      'MBoxFecha':$('#MBoxFecha').val(),
      'Cod_Bodega':$('#DCBodega').val(),
      'Cod_Marca':$('#DCMarca').val(),
      'ConIVA':$('#TxtConIVA').val(),
      'Descuento':$('#TxtDescuento').val(),
      'IVA':$('#TextIvaTotal').val(),
      'IVAPor':$('#porc_iva').val(),
      'Saldo':$('#TxtSaldo').val(),
      'SinIVA':$('#TxtSinIVA').val(),
      'TotalDC':$('#LblTotalDC').val(),
      'TotalFA':$('#LblTotal').val(),
    }
     $.ajax({
      type: "POST",
      url: '../controlador/facturacion/notas_creditoC.php?guardar=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
        if(data==-3)
        {
          Swal.fire('el producto ya sea ingresado','','info')
        }
         if(data==-4)
        {
          Swal.fire('El total Supera la de la nota de credito','','info')
        }
        cargar_tabla();         
      }
    });
}

function generar_pdf()
{
  $.ajax({
    type: "POST",
    url: '../controlador/facturacion/notas_creditoC.php?generar_pdf=true',
    //data: datos,
    dataType:'json', 
    success: function(data)
    {
      if(data.respuesta == 1)
        {
            Swal.fire({
                type: 'success',
                title: 'Factura Procesada y Autorizada',
                confirmButtonText: 'Ok!',
                allowOutsideClick: false,
            }).then(function() {
                var url = '../../TEMP/' + data.pdf + '.pdf';
                window.open(url, '_blank'); 
                location.reload();

            })
        }
      cargar_tabla();         
    }
  });
}

function generar_nc()
{
  if($('#DCContraCta').val()=='')
  {
    Swal.fire('Contra Cuenta a aplicar a la Nota de Credito','','info')
    return false;
  }
  $('#myModal_espera').modal('show');
  var cliente = $('#DCClientes option:selected').text();
  datos = $('#form_nc').serialize();  
  datos = datos+'&Cliente='+cliente;
  $.ajax({
    type: "POST",
    url: '../controlador/facturacion/notas_creditoC.php?generar_nota_credito=true',
    data: datos,
    dataType:'json', 
    success: function(data)
    {
      $('#myModal_espera').modal('hide');
      
        if(data[0]==1 && data[1]!='')
        { 
          Swal.fire({
            icon:'success',
            title: 'Nota de Credito Procesada y Autorizada',
            confirmButtonText: 'Ok!',
            allowOutsideClick: false,
          }).then(function(){
            var url=  '../../TEMP/'+data.pdf+'.pdf';
            window.open(url, '_blank'); 
            location.reload();    

          })
        }else if(data[0]==1 && data.clave=='')
        { 
          Swal.fire({
            icon:'success',
            title: 'Nota de Credito Procesada',
            confirmButtonText: 'Ok!',
            allowOutsideClick: false,
          }).then(function(){
            var url=  '../../TEMP/'+data.pdf+'.pdf';
            window.open(url, '_blank'); 
            location.reload();    

          })
        }else if(data[0]==-1)
        {

          Swal.fire('XML DEVUELTO:'+data[3],'XML DEVUELTO','error').then(function(){ 
            var url=  '../../TEMP/'+data.pdf+'.pdf';    window.open(url, '_blank');   
            tipo_error_sri(data.clave);
          }); 
        }else if(data[0]==2)
        {
          // tipo_error_comprobante(clave)
          Swal.fire('XML devuelto','','error'); 
          tipo_error_sri(data.clave);
        }
        else if(data[0]==4)
        {
          Swal.fire('SRI intermitente intente mas tarde','','info');  
        }else if(data[0]==5)
        {
          Swal.fire('El Saldo Pendiente es menor que el total de la Nota de Credito','','info');  
        }else
        {
          Swal.fire('XML devuelto por:'+data.text,'','error');  
        }         

      cargar_tabla();         
    }
  });
}

function valida_cxc()
{
   var serie =  $('#DCLineas').val();
   if(serie=='' || serie =='.')
   {
     Swal.fire('Lineas Cxc No asignada o fuera de fecha','','info');
     $('#TextCheqNo').val('.');
     $('#TextCompRet').val('00000001');
   }
}	

function validar_procesar()
{
  // cambiar el uno opr la variable corespondiente
   numero = $('#TextCompRet').val();
   if(1 != null)
   {
     Swal.fire({
         title: 'Desea procesar esta nota de credito?',
         // text: "Esta usted seguro de que quiere borrar este registro!",
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Si!'
       }).then((result) => {
         if (result.value==true) {
            $('#ReIngNC').val('1');
         }
       })
   }
}

function eliminar(CODIGO,A_NO)
{
  parametros = 
  {
    'codigo':CODIGO,
    'a_no':A_NO,
  }
   $.ajax({
    type: "POST",
    url: '../controlador/facturacion/notas_creditoC.php?eliminar_linea=true',
    data: {parametros,parametros},
    dataType:'json', 
    success: function(data)
    {
      
      cargar_tabla();         
    }
  });

}

function cerrar_modal_cambio_nombre() {
    var nuevo = $('#TxtDetalle').val();
    var dcart = $('#DCArticulo').val();
    $('#DCArticulo').append($('<option>', {
        value: dcart,
        text: nuevo,
        selected: true
    }));
    $('#TextCant').focus();

    $('#cambiar_nombre').modal('hide');

}
function Articulo_Seleccionado()
{  
   dato = $('#DCArticulo option:selected').text();
    $('#TxtDetalle').val(dato);
      $('#cambiar_nombre').on('shown.bs.modal', function() {
          $('#TxtDetalle').focus();
      })

      $('#cambiar_nombre').modal('show', function() {
          $('#TxtDetalle').focus();
      })
}