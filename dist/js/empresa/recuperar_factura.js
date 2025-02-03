$(document).ready(function () {
    $('#ciudad').select2();
   autocmpletar_entidad(); 

    $('#entidad').on('select2:select', function (e) {
        console.log(e);
   var data = e.params.data.data;
   $('#lbl_ruc').html(data.RUC_CI_NIC);
   if(data.ID_Empresa.length<3 && data.ID_Empresa.length>=2)
   {
       var item = '0'+data.ID_Empresa;
   }else if(data.ID_Empresa.length<2)
   {
       var item = '00'+data.ID_Empresa
   }
   $('#lbl_enti').html(item);
  
   console.log(data);
 });


});

function autocmpletar_entidad()
{
 $('#entidad').select2({
   placeholder: 'Seleccione una Entidad',
   ajax: {
     url: '../controlador/empresa/niveles_seguriC.php?entidades=true',
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

function buscar_ciudad()
{
   var parametros = 
   {
       'entidad':$('#entidad').val(),
   }
   $.ajax({
     type: "POST",
      url: '../controlador/empresa/cambioeC.php?ciudad=true',
     data: {parametros: parametros},
     dataType:'json',
     success: function(data)
     {
         llenarComboList(data,'ciudad');
     }
 });
}

function buscar_empresas()
{
  var ciu = $('#ciudad').val();
  var ent = $('#entidad').val();
 $('#empresas').select2({
   placeholder: 'Seleccione una Empresa',
   ajax: {
     url: '../controlador/empresa/recuperar_facturaC.php?empresas=true&ciu='+ciu+'&ent='+ent,
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

function datos_empresa()
{
   var sms = !!document.getElementById("Mensaje");
   if(sms==false)
   {
       sms='';
   }else
   {
       sms = $('#Mensaje').val();
   }
   var parametros = 
   {
       'empresas':$('#empresas').val(),
       'sms':sms,
   }
   $.ajax({
     type: "POST",
      url: '../controlador/empresa/cambioeC.php?datos_empresa=true',
     data: {parametros: parametros},
     dataType:'json',
     success: function(data)
     {
         $('#datos_empresa').html(data.datos);
         $('#ci_ruc').val(data.ci);

         console.log(data);
     }
 });
}


function recuperar()
{

   if($('#total_fac').text()=='0')
   {
       Swal.fire('Realize una busqueda primero','','info');
       return false;
   }
   if($('#entidad').val()=='' || $('#empresas').val()=='')
   {
       Swal.fire('Seleccione una entidad y una empresa','','info')
       return false;
   }
   $('#myModal_espera').modal('show');
   
   var parametros = 
   {
       'desde':$('#txt_desde').val(),
       'hasta':$('#txt_hasta').val(),
   }
   
   $.ajax({
     type: "POST",
      url: '../controlador/empresa/recuperar_facturaC.php?recuperar_factura=true',
     data: {parametros: parametros},
     dataType:'json',
     success: function(data)
     {
         setTimeout(()=>{
                $('#myModal_espera').modal('hide');
            }, 2000);
         if(data==1)
         {
             Swal.fire('Factura recuperada de xml','','info');

         }else if(data==-2)
         {
             Swal.fire('Xml encontrado en transdocumentos pero tiene un estado de en proceso','','info');
         }else if(data==-3)
         {
             Swal.fire('No hay facturas que recuperar','','info');				
         }
         console.log(data);
     },error: function (request, status, error) {   
       Swal.fire('Error inesperado ','consulte con su proveedor de servicio','error');         
       setTimeout(()=>{
        $('#myModal_espera').modal('hide');
    }, 2000);
   }
 });
}

function lista_recuperar()
{
   $('#myModal_espera').modal('show');
   var parametros = 
   {
       'desde':$('#txt_desde').val(),
       'hasta':$('#txt_hasta').val(),
   }  	  	
   $.ajax({
         type: "POST",
          url: '../controlador/empresa/recuperar_facturaC.php?lista_factura_recuperar=true',
         data: {parametros: parametros},
         dataType:'json',
         success: function(data)
         {
            setTimeout(()=>{
                $('#myModal_espera').modal('hide');
            }, 2000);			
             $('#tbl_datos').DataTable({
                 language: {
                     url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json',
                 },
                 data: ProcesarDatos(data.Tabla),
                 destroy: true, 
                 columns: [
                     {data: 'Fecha'}, 
                     {data: 'Clave_Acceso'},
                     {data: 'Serie'},
                     {data: 'Documento'}
                 ],
                 createdRow: function (row, data){
                     alignEnd(row, data); 
                 }
             });
             $('#total_fac').text(data.num)	
             if(data.num==0)
             {
                     Swal.fire('No existen documentos electronicos','','info')
             }

             console.log(data);
         },
   error: function (request, status, error) {   
       Swal.fire('Error inesperado ','consulte con su proveedor de servicio','error');         
       setTimeout(()=>{
        $('#myModal_espera').modal('hide');
    }, 2000);
   }
     });
}

function editar_fechas()
{
   if($('#total_fac').text()=='0')
   {
       Swal.fire('Realize una busqueda primero','','info');
       return false;
   }
   $('#myModal_espera').modal('show');
   
   var parametros = 
   {
       'desde':$('#txt_desde').val(),
       'hasta':$('#txt_hasta').val(),
   }  	  	
   $.ajax({
         type: "POST",
          url: '../controlador/empresa/recuperar_facturaC.php?actualizar_fechas=true',
         data: {parametros: parametros},
         dataType:'json',
         success: function(data)
         {
             if(data!=1){ text = 'Uno o varios Documentos no pudieron editar fecha'; tipo = 'info'}else{text = 'Fechas de Documentos Actualizados';tipo='success'}
             Swal.fire(text,'',tipo).then(function()
                 {
                     lista_recuperar();
                 })

         },error: function (request, status, error) {   
             Swal.fire('Error inesperado ','consulte con su proveedor de servicio','error');         
             setTimeout(()=>{
                $('#myModal_espera').modal('hide');
            }, 2000);
           }
     });

}