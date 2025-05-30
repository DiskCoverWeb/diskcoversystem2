
//modificar url
function modificar(texto){
    var l1=$('#l1').attr("href");  
    var l1=l1+'&OpcDG='+texto;
    //asignamos
    $("#l1").attr("href",l1);
    
    var l2=$('#l2').attr("href");  
    var l2=l2+'&OpcDG='+texto;
    //asignamos
    $("#l2").attr("href",l2);
    
    var l4=$('#l4').attr("href");  
    var l4=l4+'&OpcDG='+texto;
    //asignamos
    $("#l4").attr("href",l4);
    
    var l5=$('#l5').attr("href");  
    var l5=l5+'&OpcDG='+texto;
    //asignamos
    $("#l5").attr("href",l5);
    
    var l6=$('#l6').attr("href");  
    var l6=l6+'&OpcDG='+texto;
    //asignamos
    $("#l6").attr("href",l6);
    //var ti=getParameterByName('ti');
    //alert(ti);
  //document.getElementById("mienlace").innerHTML = texto;
  //document.getElementById("mienlace").href = url;
  //document.getElementById("mienlace").target = destino;
} 
function modificar1()
{
    var ti=getParameterByName('ti');
    //alert(ti);
    if( ti=='BALANCE DE COMPROBACIÓN')
    {
        var l1=$('#l1').attr("href"); 
        patron = "contabilidad.php";
        nuevoValor    = "descarga.php";
        l1 = l1.replace(patron, nuevoValor);		
        //asignamos
        $("#l7").attr("href",l1+'&ex=1');
    }
    if( ti=='BALANCE MENSUAL')
    {
        var l1=$('#l2').attr("href"); 
        patron = "contabilidad.php";
        nuevoValor    = "descarga.php";
        l1 = l1.replace(patron, nuevoValor);		
        //asignamos
        $("#l7").attr("href",l1+'&ex=1');
    }
    if( ti=='ESTADO SITUACIÓN')
    {
        var l1=$('#l5').attr("href"); 
        patron = "contabilidad.php";
        nuevoValor    = "descarga.php";
        l1 = l1.replace(patron, nuevoValor);		
        //asignamos
        $("#l7").attr("href",l1+'&ex=1');
    }
    if( ti=='ESTADO RESULTADO')
    {
        var l1=$('#l6').attr("href"); 
        patron = "contabilidad.php";
        nuevoValor    = "descarga.php";
        l1 = l1.replace(patron, nuevoValor);		
        //asignamos
        $("#l7").attr("href",l1+'&ex=1');
    }
    
}



function comprobante()
{
    var tp = $('input[name="options"]:checked').val();
    $('#tipoc').val(tp);
    var parametros = 
    {
        'MesNo':$('#mes').val(),
        'TP':tp,
    }
     $.ajax({
  data:  {parametros:parametros},
   url:   '../controlador/contabilidad/contabilidad_controller.php?comprobantes',
  type:  'post',
  dataType: 'json',
    success:  function (response) {
        $('#ddl_comprobantes').html(response);
  }
}); 
}


function listar_comprobante()
{
  $('#myModal_espera').modal('show');
    reporte_comprobante();
    var parametros = 
    {
        'numero':$('#ddl_comprobantes').val(),
        'item':$('#txt_empresa').val(),
        'TP':$('#tipoc').val(),
    }
     $.ajax({
  data:  {parametros:parametros},
   url:   '../controlador/contabilidad/contabilidad_controller.php?listar_comprobante',
  type:  'post',
  dataType: 'json',
    success:  function (response) {
        if(response==2)
        {
            Swal.fire('El Comprobante no exite.','','info');
        }else
        {
              tbl_contabilidad = $('#tbl_contabilidad').DataTable({
              language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
              },
              data: ProcesarDatos(response.tbl1.data),
              searching: false,
              destroy: true,
              columns: [
                { 
                  data : null,
                  render: function(data, type, row){
                    return data[0] || '';
                  }, 
                  title: "Acciones"
                },
                { data : "Cta" },
                { data : "Cuenta" },
                { data : "Parcial_ME" },
                { data : "Debe" },
                { data : "Haber" },
                { data : "Detalle"},
                { data : "Cheq_Dep" },
                { data : "Fecha_Efec.date",
                  render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                  }
                 },
                { data : "Codigo_C" },
                { data : "Item" },
                { data : "TP" },
                { data : "Numero" },
                { data : "Fecha.date",
                  render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                  }
                 },
                { data : "ID" },
              ],
              createdRow: function(row, data){
                alignEnd(row, data); 
              },
              complete: function(settings, json){ 
                tbl_contabilidad.columns.adjust().draw();
              },
              columnDefs: [
                {targest: [2, 6]},
                {width: '400px' }
              ] 
            });
            tbl_retenciones = $('#tbl_retenciones').DataTable({
              language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
              },
              data: ProcesarDatos(response.tbl2.data),
              searching: false, 
              destroy: true,
              columns: [
                { data : "T" },
                { data : "CodRet" },
                { data : "BaseImp" },
                { data : "Porcentaje" },
                { data : "ValRet" },
                { data : "EstabRetencion" },
                { data : "PtoEmiRetencion" },
                { data : "SecRetencion" },
                { data : "AutRetencion" },
                { data : "EstabFactura" },
                { data : "PuntoEmiFactura" },
                { data : "Factura_No" },
                { data : "Item" },
                { data : "CodigoU" },
                { data : "Periodo" },
                { data : "Cta_Retencion" },
                { data : "IdProv" },
                { data : "TP" },
                { data : "Fecha.date",
                  render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                  }
                 },
                { data : "Numero" },
                { data : "Linea_SRI" },
                { data : "Tipo_Trans" },
                { data : "X" },
                { data : "IDT" },
                { data : "RUC_CI" },
                { data : "TB" },
                { data : "Razon_Social" },
                { data : "ID" }
              ],
              createdRow: function(row, data){
                alignEnd(row, data)
              },
              complete: function(settings, json){ 
                tbl_retenciones.columns.adjust().draw();
              }
            });
            tbl_retenciones_co = $('#tbl_retenciones_co').DataTable({
              language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
              },
              data: ProcesarDatos(response.tbl2_1.data),
              searching: false,
              destroy: true,
              columns: [
                { data: "Linea_SRI" },
                { data: "Cliente" },
                { data: "TD" },
                { data: "CI_RUC" },
                { data: "TipoComprobante" },
                { data: "CodSustento" },
                { data: "TP" },
                { data: "Numero" },
                { data: "Establecimiento" },
                { data: "PuntoEmision" },
                { data: "Secuencial" },
                { data: "Autorizacion" },
                { data: "FechaEmision.date",
                  render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                  }
                 },
                { data: "FechaRegistro.date",
                  render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                  }
                 },
                { data: "FechaCaducidad.date",
                  render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                  }
                 },
                { data: "BaseImponible" },
                { data: "BaseImpGrav" },
                { data: "PorcentajeIva" },
                { data: "MontoIva" },
                { data: "BaseImpIce" },
                { data: "PorcetajeIce" },
                { data: "MontoIce" },
                { data: "MontoIvaBienes" },
                { data: "PorRetBienes" },
                { data: "ValorRetBienes" },
                { data: "MontoIvaServicios" },
                { data: "PorRetServicios" },
                { data: "ValorRetServicios" },
                { data: "ContratoPartidoPolitico" },
                { data: "MontoTituloOneroso" },
                { data: "MontoTituloGratuito" },
                { data: "DevIva" },
                { data: "Clave_Acceso" },
                { data: "Estado_SRI" },
                { data: "AutRetencion" }
              ],
              createdRow: function(row, data){
                alignEnd(row, data)
              },
              complete: function(settings, json){ 
                tbl_retenciones_co.columns.adjust().draw();
              }
            });
            tbl_retenciones_ve = $('#tbl_retenciones_ve').DataTable({
              language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
              },
              data: ProcesarDatos(response.tbl2_2.data),
              searching: false, 
              destroy: true,
              columns: [
                { data: "Linea_SRI" }, 
                { data: "Cliente" }, 
                { data: "TipoComprobante" }, 
                { data: "CI_RUC" }, 
                { data: "TD" }, 
                { data: "TP" }, 
                { data: "Numero" }, 
                { data: "Establecimiento" }, 
                { data: "PuntoEmision" }, 
                { data: "NumeroComprobantes" }, 
                { data: "Secuencial" }, 
                { data: "FechaEmision.date",
                  render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                  }
                 }, 
                { data: "FechaRegistro.date",
                  render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                  }
                 }, 
                { data: "BaseImponible" }, 
                { data: "BaseImpGrav" }, 
                { data: "PorcentajeIva" }, 
                { data: "MontoIva" }, 
                { data: "BaseImpIce" }, 
                { data: "PorcentajeIce" }, 
                { data: "MontoIce" }, 
                { data: "MontoIvaBienes" }, 
                { data: "PorRetBienes" }, 
                { data: "ValorRetBienes" }, 
                { data: "MontoIvaServicios" }, 
                { data: "PorRetServicios" }, 
                { data: "ValorRetServicios" }, 
                { data: "RetPresuntiva" }
              ],
              createdRow: function(row, data){
                alignEnd(row, data)
              },
              complete: function(settings, json){ 
                tbl_retenciones_ve.columns.adjust().draw();
              }
            });
            tbl_subcuentas = $('#tbl_subcuentas').DataTable({
              language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
              },
              data: ProcesarDatos(response.tbl3.data),
              searching: false, 
              destroy: true,
              columns: [
                { data: "TC" },
                { data: "Detalles" },
                { data: "Factura" },
                { data: "Fecha_V.date",
                  render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                  }
                },
                { data: "Parcial_ME" },
                { data: "Debitos" },
                { data: "Creditos" },
                { data: "Prima" },
                { data: "Detalle_SubCta" },
                { data: "Cta" },
                { data: "Codigo" }
              ],
              createdRow: function(row, data){
                alignEnd(row, data)
              },
              complete: function(settings, json){ 
                tbl_subcuentas.columns.adjust().draw();
              }
            });
            tbl_kardex = $('#tbl_kardex').DataTable({
              language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
              },
              data: ProcesarDatos(response.tbl4.data),
              paging: false, 
              searching: false,
              destroy: true,
              columns: [
                { data: "Codigo_Inv" },
                { data: "Producto" },
                { data: "CodBodega" },
                { data: "Entrada" },
                { data: "Salida" },
                { data: "Valor_Unitario" },
                { data: "Valor_Total" },
                { data: "Costo" },
                { data: "Total" },
                { data: "Fecha.date",
                  render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                  }
                },
                { data: "TC" },
                { data: "Serie" },
                { data: "Factura" },
                { data: "Orden_No" },
                { data: "Lote_No" },
                { data: "Fecha_Fab.date",
                  render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                  }
                },
                { data: "Fecha_Exp.date",
                  render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                  }
                },
                { data: "Reg_Sanitario" },
                { data: "Modelo" },
                { data: "Procedencia" },
                { data: "Serie_No" },
                { data: "Codigo_Barra" },
                { data: "Cta_Inv" },
                { data: "Contra_Cta" }
              ],
              createdRow: function(row, data){
                alignEnd(row, data)
              },
              complete: function(settings, json){ 
                tbl_kardex.columns.adjust().draw();
              }
            });
            $('#txt_debe').val(response.Debe);
            $('#txt_haber').val(response.haber);        		
            $('#txt_total').val(response.total);
            $('#txt_saldo').val(response.saldo);
            $('#LabelRecibi').val(response.beneficiario);
            $('#Co').val(response.Co);
            $('#MBFecha').val(response.Co.fecha);
            $('#LabelConcepto').val(response.Co.Concepto);
            $('#LabelCantidad').val(response.Debe);
            let Efectivo = (response.Co.Efectivo);
            $('#LabelFormaPago').val(Number(Efectivo).toFixed(2));
            $('#LabelUsuario').val(response.Nombre_Completo);
            if(response.Co.T=='A')
            {
                $('#LabelEst').text('ANULADO');
            }else
            {
                $('#LabelEst').text('NORMAL');
            }
        }
        setTimeout(()=>{
          $('#myModal_espera').modal('hide');
        }, 2000);
        

  }
}); 
}

function reporte_comprobante()
{

    var parametros = 
    {
        'comprobante':$('#ddl_comprobantes').val(),
    }
     // $.ajax({
  // data:  {parametros:parametros},
   url=  '../controlador/contabilidad/comproC.php?reporte&comprobante='+$('#ddl_comprobantes').val()+'&TP='+$('#tipoc').val();
  // type:  'post',
  // dataType: 'json',
    // success:  function (response) {
        $('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="'+url+'" frameborder="0" allowfullscreen></iframe>');

  // }
// }); 


            // value1 = $('#ddl_comprobantes').val();
            // //alert(value1);
            // $.post('ajax/vista_ajax.php'
            // 	, {ajax_page: 'comp', com: value1 }, function(data){
            // 		//$('div.pdfcom').load(data);
            // 		$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
            // 		//alert('entrooo '+idMensaje+" ajax/TEMP/'+value1+'.pdf");
            // 	});
}

function modificar_comprobante()
{
    var com = $('#ddl_comprobantes').val();
    $("#TipoProcesoLlamadoClave").val("");
    if(com!='')
    {
     /*$('#clave_contador').modal('show');
     $('#titulo_clave').text('Contador General');
     $('#TipoSuper').val('Contador');*/
     IngClave('Contador');
  }else
  {
      Swal.fire('Seleccione un comprobante','','info');
  }
}

// funcion de respuesta para la clave
function resp_clave_ingreso(response)
{
    if(response['respuesta']==1)
    {
        if($("#TipoProcesoLlamadoClave").val() =="ModalChangeCa"){
                $('#ModalChangeCa').modal('show');
        }else if($("#TipoProcesoLlamadoClave").val() =="ModalChangeValores"){
            $('#ModalChangeValores').modal('show')
        }else{
            confirmar_edicion(response);
        }
    }else
    {

    }
}


function anular_comprobante()
{
    Swal.fire({
         title: 'Seguro de Anular El Comprobante No. '+$('#tipoc').val()+' - '+$('#ddl_comprobantes').val(),
         // text: "You won't be able to revert this!",
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'SI'
       }).then((result) => {
         if (result.value) {
             $('#myModal_anular').modal('show');			  	
         }
       })			
}

function anular_comprobante_procesar()
{
        $('#myModal_espera').modal('show');
   var parametros = 
           {	
               'numero':$('#ddl_comprobantes').val(),
               'item':$('#txt_empresa').val(),
               'TP':$('#tipoc').val(),		
               'Fecha':$('#MBFecha').val(),
               'Concepto':$('#LabelConcepto').val(),
               'Motivo_Anular':$('#txt_motivo_anulacion').val(),
           }
            $.ajax({
         data:  {parametros:parametros},
          url:   '../controlador/contabilidad/comproC.php?anular_comprobante=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) {
              setTimeout(()=>{
                $('#myModal_espera').modal('hide');
                $('#myModal_anular').modal('hide');	
              }, 2000);
               setTimeout(listar_comprobante, 1000);
         }
       }); 
}

function BtnFechaClick(){
 Swal.fire({
   title: 'PREGUNTA DE MODIFICACION',
   text: "Seguro desea cambiar la Fecha del Comprobante",
   icon: 'warning',
   showCancelButton: true,
   confirmButtonColor: '#3085d6',
   cancelButtonColor: '#d33',
   confirmButtonText: 'SI'
 }).then((result) => {
   if (result.value) {
     $('#MBFecha').removeAttr('disabled');
     FechaTemp = $('#MBFecha').val();
     $('#MBFecha').focus();          
   }else{
     $('#MBFecha').attr('disabled','disabled');
   }
 })  
}

function MBFecha_LostFocus() {
 if(FechaTemp != $('#MBFecha').val()){
   Swal.fire({
     title: 'PREGUNTA DE MODIFICACION',
     text: "Seguro de realizar el cambio",
     icon: 'warning',
     showCancelButton: true,
     confirmButtonColor: '#3085d6',
     cancelButtonColor: '#d33',
     confirmButtonText: 'SI'
   }).then((result) => {
     if (result.value) {
       $('#myModal_espera').modal('show');
       $.ajax({
         url:   '../controlador/contabilidad/comproC.php?ActualizarFechaComprobante=true',
         type:  'post',
         data: {
           'MBFecha': $("#MBFecha").val(),
           'Numero': $("#ddl_comprobantes").val(),
           'TP': $('input[name="options"]:checked').val(),
         },
         dataType: 'json',
         success:  function (response) {
           Swal.fire('Proceso terminado con exito, vuelva a listar el comprobante','','info');
           $('#MBFecha').attr('disabled','disabled');
           setTimeout(()=>{
            $('#myModal_espera').modal('hide');
            }, 2000);
         }
       }); 

     }else{
       $('#MBFecha').focus();
     }
   })
 }
}

function Eliminar_Cuenta(Cta, CuentaBanco, Debe, Haber, Asiento) {
  Swal.fire({
 title: 'PREGUNTA DE ELIMINACION',
 text: "Esta seguro de eliminar la cuenta: "+Cta+' '+CuentaBanco,
 icon: 'warning',
 showCancelButton: true,
 confirmButtonColor: '#3085d6',
 cancelButtonColor: '#d33',
 confirmButtonText: 'SI'
}).then((result) => {
 if (result.value) {
   $('#myModal_espera').modal('show');
   $.ajax({
     url:   '../controlador/contabilidad/comproC.php?Eliminar_Cuenta=true',
     type:  'post',
     data: {
       'Cta': Cta,
       'Asiento': Asiento,
       'Numero': $("#ddl_comprobantes").val(),
       'TP': $('input[name="options"]:checked').val(),
     },
     dataType: 'json',
     success:  function (response) {
       setTimeout(()=>{
        $('#myModal_espera').modal('hide');
       }, 2000);
       Swal.fire('Proceso terminado con exito, vuelva a listar el comprobante','','info');
     }
   }); 

 }else{
   $('#MBFecha').focus();
 }
})
}

function Cambiar_Cuenta(Codigo1, Cuenta, Asiento) {
 $("#TipoProcesoLlamadoClave").val("ModalChangeCa");
 /*$('#clave_contador').modal('show');
   $('#titulo_clave').text('Contador General');
   $('#TipoSuper').val('Contador');*/
   IngClave('Contador');

 let Codigo3 = Codigo1+' - '+Cuenta;
 let Producto = "Transacciones";
 let TP = $('#tipoc').val();
 let Numero = $('#ddl_comprobantes').val();
 Form_Activate_ModalChangeCa(Codigo1, Asiento, Producto, Codigo3, TP, Numero)
}

function Cambiar_Valores(Cta, Cuenta_No, Debe, Haber, NomCtaSup, NoCheque, Asiento) {  	
 $("#TipoProcesoLlamadoClave").val("ModalChangeValores");
 /*$('#clave_contador').modal('show');
   $('#titulo_clave').text('Contador General');
   $('#TipoSuper').val('Contador');*/
   IngClave('Contador');
 ;
 let NomCta = $("#LabelConcepto").val();
 let TP = $('#tipoc').val();
 let Numero = $('#ddl_comprobantes').val();
 let Fecha = $('#MBFecha').val();
 Form_Activate_ModalChangeValores(NomCta, Cta, Cuenta_No, Debe, Haber, NomCtaSup, NoCheque, Asiento, TP, Numero, Fecha)
}

function GenerarExcelResultadoComprobante() {
 url = '../controlador/contabilidad/comproC.php?ExcelResultadoComprobante=true&Numero='+$('#ddl_comprobantes').val()+'&fecha='+$('#MBFecha').val()+'&concepto='+$("#LabelConcepto").val();
window.open(url, '_blank');
}

$( document ).ready(function() {
    //buscar('comproba');
    comprobante();
    $('[data-bs-toggle="tooltip"]').tooltip();
    // listar_comprobante();

});
