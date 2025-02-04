$('#myModal_espera').modal('show');
$(function() { 
    $("#myModal").modal();
});
$('#myModal_espera').modal('hide');
function copiar()
{
    var codigoACopiar = document.getElementById('texto');
    var seleccion = document.createRange();
    seleccion.selectNodeContents(codigoACopiar);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(seleccion);
    try {
        // $('#myModal_espera').modal('show');
        var res = document.execCommand('copy'); //Intento el copiado
        if (res){

     // $('#myModal_espera').modal('hide');
            exito();
        }else{
            // $('#myModal_espera').modal('hide');
            fracaso();
        }
        mostrarAlerta();
    }
    catch(ex) {
        excepcion();
    }
    window.getSelection().removeRange(seleccion);
}
function interceptarPegado(ev) 
{
    alert('Has pegado el texto:' + ev.clipboardData.getData('text/plain'));
}

///////
// Auxiliares para mostrar y ocultar mensajes
///////
var divAlerta = document.getElementById('alerta');

function exito() {
    divAlerta.innerText = '¡¡Copiado con exito al portapapeles!!';
    divAlerta.classList.add('alert-success');
}

function fracaso() {
    divAlerta.innerText = '¡¡Ha fallado el copiado al portapapeles!!';
    divAlerta.classList.add('alert-warning');
}

function excepcion() {
    divAlerta.innerText = 'Se ha producido un error al copiar al portapaples';
    divAlerta.classList.add('alert-danger');
}

function mostrarAlerta() {
    divAlerta.classList.remove('invisible');
    divAlerta.classList.add('visible');
    setTimeout(ocultarAlerta, 1500);
}

function ocultarAlerta() {
    divAlerta.innerText = '';
    divAlerta.classList.remove('alert-success', 'alert-warning', 'alert-danger', 'visible');
    divAlerta.classList.add('invisible');
}




//Date picker
$('#desde').datepicker({
    dateFormat: 'dd/mm/yyyy',
    autoclose: true
});
$('#hasta').datepicker({
    dateFormat: 'dd/mm/yyyy',
    autoclose: true
});
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
//balance nomenclatura nacional o internacional
    //modificar url
function modificarb(id){
    texto='0';
    if (document.getElementById(id).checked)
    {
        //alert('Seleccionado');
        texto='1';
    }
    
    var l1=$('#l1').attr("href");  
    var l1=l1+'&OpcCE='+texto;
    //asignamos
    $("#l1").attr("href",l1);
    
    var l2=$('#l2').attr("href");  
    var l2=l2+'&OpcCE='+texto;
    //asignamos
    $("#l2").attr("href",l2);
    
    var l4=$('#l4').attr("href");  
    var l4=l4+'&OpcCE='+texto;
    //asignamos
    $("#l4").attr("href",l4);
    
    var l5=$('#l5').attr("href");  
    var l5=l5+'&OpcCE='+texto;
    //asignamos
    $("#l5").attr("href",l5);
    
    var l6=$('#l6').attr("href");  
    var l6=l6+'&OpcCE='+texto;
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