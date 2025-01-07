$( document ).ready(function() {
    tipo_proveedor();

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
            $('#txt_email2').val(ui.item.email2); // save selected id to 
            $('#txt_ejec').val(ui.item.Cod_Ejec)
            $('#CParteR').val(ui.item.Parte_Relacionada)

            $("#txt_actividad option").filter(function() {
                return $(this).text() === ui.item.Actividad;
            }).prop('selected', true);

            
             $("#CTipoProv").val(ui.item.Tipo_Pasaporte);


           // $('#txt_actividad').val(ui.item.Actividad); // save selected id to input
            // $('#txt_ejec').val(ui.item.Cod_Ejec); // save selected id to input
            // $('#txt_actividad').val();
            // $('#CTipoProv').val();
            cargar_sucursales();
            return false;
        },
        focus: function(event, ui){
             $('#txt_nombre_prove').val(ui.item.label); // display the selected text
            
            return false;
        },
    });

    $( "#txt_ruc" ).autocomplete({
        source: function( request, response ) {
            
            $.ajax({
                 url:   '../controlador/farmacia/articulosC.php?search_ruc=true',           
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
            $('#txt_nombre_prove').val(ui.item.Nombre); // display the selected text
            $('#txt_ruc').val(ui.item.label); // save selected id to input
            $('#txt_direccion').val(ui.item.dir); // save selected id to input
            $('#txt_telefono').val(ui.item.tel); // save selected id to input
            $('#txt_email').val(ui.item.email); // save selected id to input
            $('#txt_email2').val(ui.item.email2); // save selected id to input
            $('#txt_ejec').val(ui.item.Cod_Ejec)

              $("#txt_actividad option").filter(function() {
                return $(this).text() === ui.item.Actividad;
            }).prop('selected', true);
            $('#CParteR').val(ui.item.Parte_Relacionada)

             $("#CTipoProv").val(ui.item.Tipo_Pasaporte);

            // $('#txt_actividad').val(ui.item.Actividad); // save selected id to input
            // $('#txt_ejec').val(ui.item.Cod_Ejec); // save selected id to input
            cargar_sucursales();
            return false;
        },
        focus: function(event, ui){
             $('#txt_ruc').val(ui.item.label); // display the selected text
            
            return false;
        },
    });

  //autocmpretar abreviado

  $( "#txt_ejec" ).autocomplete({
        source: function( request, response ) {
            
            $.ajax({
                 url:   '../controlador/farmacia/articulosC.php?searchAbre=true',           
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
          // console.log(ui.item);
            // $('#txt_id_prove').val(ui.item.value); // display the selected text
            // $('#txt_ejec').val(ui.item.label); // display the selected text
            // $('#txt_ruc').val(ui.item.CI); // save selected id to input
            // $('#txt_direccion').val(ui.item.dir); // save selected id to input
            // $('#txt_telefono').val(ui.item.tel); // save selected id to input
            // $('#txt_email').val(ui.item.email); // save selected id to input
            // $('#txt_actividad').val(ui.item.Actividad); // save selected id to input
            // $('#txt_ejec').val(ui.item.Cod_Ejec); // save selected id to input
            return false;
        },
        focus: function(event, ui){
             $('#txt_ejec').val(ui.item.label); // display the selected text
            
            return false;
        },
    });



});

function mostrar_ingreso_sucursal()
{
if($('#txt_id_prove').val()=='')
{
    Swal.fire('Seleccione un proveedor','','info');
    return false;
}
$('#pnl_sucursal').css('display','block');
}

function cargar_sucursales()
{
if($('#txt_id_prove').val()=='')
{
    Swal.fire('Seleccione un proveedor','','info');
    return  false;
}

var parametros = {
    'ruc':$('#txt_ruc').val(),
}
 $.ajax({
  data:  {parametros,parametros},
  url:   '../controlador/modalesC.php?sucursales=true',
  type:  'post',
  dataType: 'json',
  success:  function (response) { 
    op = '';
    var sucursal = 0;
    response.forEach(function(item,i){
        sucursal = 1;
        op+="<tr><td>"+item.Direccion+"</td><td>"+item.TP+"</td><td><button class='btn btn-danger btn-xs' type='button' onclick='eliminar_sucursal(\""+item.ID+"\")'><i class='fa fa-trash'></i></button></td></tr>";
    })

    if(sucursal==1)
    {
        $('#pnl_sucursal').css('display','block');
    }else{            
        $('#pnl_sucursal').css('display','none');
    }

    $('#tbl_sucursales').html(op);
    console.log(response);
    
  }, 
  error: function(xhr, textStatus, error){
    $('#myModal_espera').modal('hide');           
  }
});

}

function nombres(nombre)
{
$('#txt_nombre_prove').val(nombre.ucwords());
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
   $('#txt_email2').val('');
   $('#txt_actividad').val('');
   $('#txt_ejec').val('');
 }
}

function guardar_proveedor()
{

 var datos =  $("#form_nuevo_proveedor").serialize();
    if(TipoProveedor!='')
    {
         abre = $('#txt_ejec').val();
         console.log(abre);
         if($('#txt_actividad').val()=='' || $('#txt_actividad').val()==null)
         {
            Swal.fire("Seleccione tipo de proveedor","","info");
            return false;
         }
         if(abre.length >5 || abre=='.' || abre=='' || abre.length <2)
         {
             Swal.fire('Abreviatura incorrecta ','Asegurese de colocar una abreviatura mayor a 2 digitos y menor o igual 5 digitos y diferente de punto (.)','info')
            return false;
         }
         datos = datos+'&actividad='+$('#txt_actividad option:selected').text()+'&CTipoProv='+$('#CTipoProv').val()
    }else
    {
         datos = datos+'&actividad=.&CTipoProv=.'
    }


 $('#myModal_espera').modal('show');
 $.ajax({
  data:  datos,
  url:   '../controlador/farmacia/articulosC.php?proveedor_nuevo=true',
  type:  'post',
  dataType: 'json',
  success:  function (response) { 
     $('#myModal_espera').modal('hide');
    // console.log(response);
    if(response==1)
    {
       $('#txt_nombre_prove').val('');  
      limpiar_t();        
      Swal.fire('Proveedores Guardado.','','success').then(function(){
          //cerrar();
      }); 
    }else if(response==-2)
    {
      Swal.fire('El numero de Cedula o ruc ingresado ya esta en uso.','','info');  
    }
  }, 
    error: function(xhr, textStatus, error){
    $('#myModal_espera').modal('hide');
        // $('#lbl_mensaje').text(xhr.statusText);
        // alert(xhr.statusText);
        // alert(textStatus);
        // alert(error);
    }
});

 // console.log(datos);
}


function eliminar_proveedor()
{
    if( $('#txt_id_prove').val()=='')
    {
        Swal.fire("No se ha seleccionado un proveedor","","error");
        return false;
    }
    
     $('#myModal_espera').modal('show');
     datos = 
     {
        'idProv': $('#txt_id_prove').val(),
     }
     $.ajax({
      data:  datos,
      url:   '../controlador/farmacia/articulosC.php?proveedor_eliminar=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
         $('#myModal_espera').modal('hide');
        // console.log(response);
        if(response==1)
        {
           
          Swal.fire('Proveedores eliminado.','','success').then(function(){
            location.reload();
          }); 
        }else if(response==-1)
        {
          Swal.fire('El Provedor tiene registros ligados','','info');  
        }
      }, 
        error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');
            // $('#lbl_mensaje').text(xhr.statusText);
            // alert(xhr.statusText);
            // alert(textStatus);
            // alert(error);
        }
    });

 // console.log(datos);
}

function tipo_proveedor()
{
 
 $.ajax({
  // data:  datos,
  url:   '../controlador/modalesC.php?tipo_proveedor=true',
  type:  'post',
  dataType: 'json',
  success:  function (response) { 
     var op = '<option value="">Seleccione</option>';
    response.forEach(function(item,i){
        op+="<option value="+item.ID+">"+item.Proceso+"</option>";
    })

    $('#txt_actividad').html(op);
    // console.log(response);
    
  }, 
  error: function(xhr, textStatus, error){
    $('#myModal_espera').modal('hide');           
  }
});

 // console.log(datos);
}

function cerrar()
{
 //window.parent.postMessage('closeModal', '*');;
 location.href = window.location.pathname + '?mod=03';
}
function validar_abrev()
{
    var ab = $('#txt_ejec').val();
    var id = $('#txt_id_prove').val();
    var parametros = {
        'abre':ab,
        'id':id,
    }
if(ab!='')
{
     $.ajax({
      data:  {parametros,parametros},
      url:   '../controlador/farmacia/articulosC.php?validar_abre=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
            Swal.fire('Esta abreviatura ya esta en uso','Coloque otra abreviacion','info').then(function(){
                $('#txt_ejec').val('');
            });
        }
       
      }, 
        error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');
            // $('#lbl_mensaje').text(xhr.statusText);
            // alert(xhr.statusText);
            // alert(textStatus);
            // alert(error);
        }
    });
 }

}

function validar_sri()
{
var ci = $('#txt_ruc').val();
if(ci.length<10)
{
    Swal.fire("","","info");
    return false;
}
if(ci!='')
{
  url = 'https://srienlinea.sri.gob.ec/facturacion-internet/consultas/publico/ruc-datos2.jspa?accion=siguiente&ruc='+ci
  window.open(url, "_blank");
}else
{
   Swal.fire('Coloque un numero de CI / RUC','','info')
}    
}

function add_sucursal()
{
var parametros = {
    'ruc':$('#txt_ruc').val(),
    'direccion':$('#txt_sucursal_dir').val(),
    'tp':$('#txt_cod_sucursal').val(),
}
 $.ajax({
  data:  {parametros,parametros},
  url:   '../controlador/modalesC.php?add_sucursal=true',
  type:  'post',
  dataType: 'json',
  success:  function (response) { 
   if(response==1)
   {
    $('#txt_sucursal_dir').val('');
    cargar_sucursales();
   }        
  }, 
  error: function(xhr, textStatus, error){
    $('#myModal_espera').modal('hide');           
  }
});

}

function eliminar_sucursal(id)
{
var parametros = {
    'id':id,
}
 $.ajax({
  data:  {parametros,parametros},
  url:   '../controlador/modalesC.php?delete_sucursal=true',
  type:  'post',
  dataType: 'json',
  success:  function (response) { 
   if(response==1)
   {
    cargar_sucursales();
   }        
  }, 
  error: function(xhr, textStatus, error){
    $('#myModal_espera').modal('hide');           
  }
});
}


