
var scanning = false;
var tbl_pedidos_all;
$(document).ready(function () {
    // cargar_bodegas();
    // cargar_bodegas2();
       // lista_stock_ubicado();
$('#txt_bodega').keydown( function(e) { 
  var keyCode1 = e.keyCode || e.which; 
  if (keyCode1 == 13) { 
       codigo = $('#txt_bodega').val();
       codigo = codigo.trim();
       $('#txt_bodega').val(codigo);
       lista_stock_ubicado();
  }
});  
$('#txt_cod_barras').keydown( function(e) { 
  var keyCode1 = e.keyCode || e.which; 
  if (keyCode1 == 13) { 
      codigo = $('#txt_cod_barras').val();
      codigo = codigo.trim();
      $('#txt_cod_barras').val(codigo);

       lista_stock_ubicado();
  }
});  


 tbl_pedidos_all = $('#tbl_asignados').DataTable({
          // responsive: true,
            searching: true,
         
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
            url:   '../controlador/inventario/reubicarC.php?lista_stock_ubicado=true',
            type: 'POST',  // Cambia el método a POST    
            data: function(d) {
                  var parametros = {                    
                    'bodegas':$('#txt_bodega').val(),
                    'cod_articulo':$('#txt_cod_barras').val(),
                  };
                  return { parametros: parametros };
              },
              dataSrc: '',
          },
           scrollX: true,  // Habilitar desplazamiento horizontal
   
          columns: [
             
              { data: 'Codigo_Barra' },
              { data: 'Producto' },
              { data: 'Entrada',},
              { data: null,
                 render: function(data, type, item) {
                    botons =  formatoDate(data.Fecha_DUI.date);                  
                    return botons;                    
                  }
              },
              { data: 'CodBodega' },
              { data: 'Ruta'        },
             
              { data: null,
                 render: function(data, type, item) {
                    botons = `<button type='button' title = 'Cambiar ubicacion' class='btn btn-sm btn-primary p-1' onclick='cambiar_bodegas("${data.ID}")'><i class='bx bx-refresh'></i></button>`;
                  
                    return botons;                    
                  }
              },
              
          ]
      });




})

function abrir_modal_bodegas(op='')
{
if(op=='')
{
$('#arbol_bodegas2').html('');

console.log('1');
cargar_bodegas();
$('#myModal_arbol_bodegas').modal('show');
}else
{		

$('#arbol_bodegas').html('');
console.log('2');
cargar_bodegas2();
$('#myModal_arbol_bodegas2').modal('show');
}
}

function cargar_bodegas(nivel=1,padre='')
{
var parametros = {
'nivel':nivel,
'padre':padre,
}
$.ajax({
type: "POST",
url:   '../controlador/inventario/almacenamiento_bodegaC.php?lista_bodegas_arbol=true',
 data:{parametros:parametros},
dataType:'json',
success: function(data)
{
    // console.log(data);
    if(nivel==1)
    {
     $('#arbol_bodegas').html(data);
    }else
    {
         $('#h'+padre).html(data);
    }
}
});  
}
function cargar_nombre_bodega(nombre,cod)
{

$('#txt_bodega_title').text();
$('#txt_bodega_title').text(nombre);
$('#txt_bodega').val(cod);
if(cod!='.')
{
contenido_bodega();
}
}

function contenido_bodega()
{
var parametros = {
'num_ped':'',//$('#txt_codigo').val(),
'bodega':$('#txt_bodega').val(),
}
$.ajax({
type: "POST",
url:   '../controlador/inventario/almacenamiento_bodegaC.php?contenido_bodega=true',
 data:{parametros:parametros},
dataType:'json',
success: function(data)
{
    $('#arbol_bodegas li span.bg-success').removeClass('bg-success');
    id = $('#txt_bodega').val();
    // console.log(id);
    id = id.replaceAll('.','_');
    $('#contenido_bodega').html(data);
    $('#c_'+id).addClass('bg-success');	
    // productos_asignados();
}
});
}

function cargar_bodegas2(nivel=1,padre='')
{
var parametros = {
'nivel':nivel,
'padre':padre,
}
$.ajax({
type: "POST",
url:   '../controlador/inventario/almacenamiento_bodegaC.php?lista_bodegas_arbol2=true',
 data:{parametros:parametros},
dataType:'json',
success: function(data)
{
    // console.log(data);
    if(nivel==1)
    {
     $('#arbol_bodegas2').html(data);
    }else
    {
         $('#h'+padre).html(data);
    }
}
});  
}
function cargar_nombre_bodega2(nombre,cod)
{

$('#txt_bodega_title2').text();
$('#txt_bodega_title2').text(nombre);
$('#txt_cod_lugar').val(cod);
if(cod!='.')
{
contenido_bodega2();
}
}

function contenido_bodega2()
{
var parametros = {
'num_ped':'',//$('#txt_codigo').val(),
'bodega':$('#txt_cod_lugar').val(),
}
$.ajax({
type: "POST",
url:   '../controlador/inventario/almacenamiento_bodegaC.php?contenido_bodega=true',
 data:{parametros:parametros},
dataType:'json',
success: function(data)
{
    $('#arbol_bodegas2 li span.label-success').removeClass('label-success');
    id = $('#txt_cod_lugar').val();
    // console.log(id);
    id = id.replaceAll('.','_');
    $('#contenido_bodega2').html(data);
    $('#c_'+id).addClass('label-success');	
    // productos_asignados();
}
});
}

function lista_stock_ubicado()
{ 	


     tbl_pedidos_all.ajax.reload(null, false);

// var parametros = {
//     'bodegas':$('#txt_bodega').val(),
//     'cod_articulo':$('#txt_cod_barras').val(),
// }
//  $.ajax({
//     type: "POST",
//    url:   '../controlador/inventario/reubicarC.php?lista_stock_ubicado=true',
//      data:{parametros:parametros},
//    dataType:'json',
//     success: function(data)
//     {
//         $('#tbl_asignados').html(data);
//     }
// }); 
}

function cambiar_bodegas(id)
{
$('#myModal_cambiar_bodegas').modal('show');
$('#txt_id_inv').val(id);
}


async function buscar_ruta()
{  

codigo = $('#txt_bodega').val();
codigo = codigo.trim();
$('#txt_bodega').val(codigo);
var parametros = {
    'codigo':codigo,
}
$.ajax({
    type: "POST",
   url:   '../controlador/inventario/almacenamiento_bodegaC.php?cargar_lugar=true',
     data:{parametros:parametros},
   dataType:'json',
    success: function(data)
    {
        $('#txt_bodega_title').text('Ruta:'+data);
        // $('#txt_cod_bodega').val(codigo);

    }
});
}

async function buscar_ruta2()
{  

codigo = $('#txt_cod_lugar').val();
codigo = codigo.trim();
$('#txt_cod_lugar').val(codigo);
var parametros = {
    'codigo':codigo,
}
$.ajax({
    type: "POST",
   url:   '../controlador/inventario/almacenamiento_bodegaC.php?cargar_lugar=true',
     data:{parametros:parametros},
   dataType:'json',
    success: function(data)
    {
        $('#txt_bodega_title2').text('Ruta:'+data);
        // $('#txt_cod_bodega').val(codigo);

    }
});
}

function Guardar_bodega(id)
{
codigo = $('#txt_cod_lugar').val();
codigo = codigo.trim();
$('#txt_cod_lugar').val(codigo);
var parametros = {
    'codigo':codigo,
    'id':$('#txt_id_inv').val(),
}
$.ajax({
    type: "POST",
   url:   '../controlador/inventario/reubicarC.php?cambiar_bodega=true',
     data:{parametros:parametros},
   dataType:'json',
    success: function(data)
    {
        $('#myModal_cambiar_bodegas').modal('hide');
        $('#txt_bodega_title2').text('Ruta:');
        $('#txt_cod_lugar').val('')
        $('#txt_id_inv').val('')
        lista_stock_ubicado();
    }
});
} 

function bodegaPorQR(codigo){
    $('#txt_bodega').val(codigo.trim());
    $('#txt_bodega').trigger('blur');
}
function articuloPorQR(codigo){
    $('#txt_cod_barras').val(codigo.trim());
    $('#txt_cod_barras').trigger('blur');
}
function reubiPorQR(codigo){
    console.log('reubi')
    $('#txt_cod_lugar').val(codigo.trim());
    $('#txt_cod_lugar').trigger('blur');
    // cargar_bodegas2();
}

 function escanear_qr(item){
    $('#txt_tipo').val(item);
    iniciarEscanerQR();
        $('#modal_qr_escaner').modal('show');
}


 let scanner;
 let NumCamara = 0;
 function iniciarEscanerQR() {
    item = $('#txt_tipo').val();
    NumCamara = $('#ddl_camaras').val();
    scanner = new Html5Qrcode("reader");
    $('#qrescaner_carga').hide();
    Html5Qrcode.getCameras().then(devices => {
        if (devices.length > 0) {
            let cameraId = devices[NumCamara].id; // Usa la primera cámara disponible
            scanner.start(
                cameraId,
                {
                    fps: 10, // Velocidad de escaneo
                    qrbox: { width: 250, height: 250 } // Tamaño del área de escaneo
                },
                (decodedText) => {
                    if(item=='articulo')
                    {
                        articuloPorQR(decodedText)

                    }else if(item=='reubi')
                    {
                        reubiPorQR(decodedText)
                    }else
                    {
                        bodegaPorQR(decodedText);
                    }
                    scanner.stop(); // Detiene la cámara después de leer un código
                    $('#modal_qr_escaner').modal('hide');
                },
                (errorMessage) => {
                    console.log("Error de escaneo:", errorMessage);
                }
            );
        } else {
            alert("No se encontró una cámara.");
        }
    }).catch(err => console.error("Error al obtener cámaras:", err));
}

 