var video;
var canvasElement;
var canvas;
var scanning = false;
$(document).ready(function () {
    video = document.createElement("video");
	canvasElement = document.getElementById("qr-canvas");
	canvas = canvasElement.getContext("2d", { willReadFrequently: true });
    // cargar_bodegas();
    // cargar_bodegas2();
       lista_stock_ubicado();
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
    $('#arbol_bodegas li span.label-success').removeClass('label-success');
    id = $('#txt_bodega').val();
    // console.log(id);
    id = id.replaceAll('.','_');
    $('#contenido_bodega').html(data);
    $('#c_'+id).addClass('label-success');	
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
var parametros = {
    'bodegas':$('#txt_bodega').val(),
    'cod_articulo':$('#txt_cod_barras').val(),
}
 $.ajax({
    type: "POST",
   url:   '../controlador/inventario/reubicarC.php?lista_stock_ubicado=true',
     data:{parametros:parametros},
   dataType:'json',
    success: function(data)
    {
        $('#tbl_asignados').html(data);
    }
}); 
}

function cambiar_bodegas(id)
{
$('#myModal_cambiar_bodegas').modal('show');
$('#txt_id_inv').val(id);
}


async function buscar_ruta()
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
        $('#txt_bodega_title').text('Ruta:'+data);
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

function escanear_qr(){
	/*if(campo == 'lugar' && $('#txt_codigo').val() == ''){
		Swal.fire('Seleccione un codigo de ingreso', '', 'error');
		return;
	}*/
	$('#modal_qr_escaner').modal('show');
	navigator.mediaDevices
	.getUserMedia({ video: { facingMode: "environment" } })
	.then(function (stream) {
	$('#qrescaner_carga').hide();
		scanning = true;
		//document.getElementById("btn-scan-qr").hidden = true;
		canvasElement.hidden = false;
		video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
		video.srcObject = stream;
		video.play();
		tick();
		scan();
	});
}

//funciones para levantar las funiones de encendido de la camara
function tick() {
	canvasElement.height = video.videoHeight;
	canvasElement.width = video.videoWidth;
	//canvasElement.width = canvasElement.height + (video.videoWidth - video.videoHeight);
	canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

	scanning && requestAnimationFrame(tick);
}

function scan() {
	try {
		qrcode.decode();
	} catch (e) {
		setTimeout(scan, 300);
	}
}

const cerrarCamara = () => {
	video.srcObject.getTracks().forEach((track) => {
		track.stop();
	});
	canvasElement.hidden = true;
$('#qrescaner_carga').show();
	$('#modal_qr_escaner').modal('hide');
};

//callback cuando termina de leer el codigo QR
qrcode.callback = (respuesta) => {
	if (respuesta) {
		//console.log(respuesta);
		//Swal.fire(respuesta)

        bodegaPorQR(respuesta);
		
		//activarSonido();
		//encenderCamara();    
		cerrarCamara();    
	}
};