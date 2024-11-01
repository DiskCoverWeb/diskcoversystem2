$(document).ready(function()
	{

		$('#imprimir_excel').click(function(){
			$tipo =$('#txt_tipo').val();
            var url = '../controlador/contabilidad/contabilidad_controller.php?balance_excel=true&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&TipoBa='+$('#TipoBenef').val()+'&TipoPre='+$('input:radio[name=optionsRadios]:checked').val()+'&Tipo='+$tipo+'&Imp=true&pdf=false';                 
      	   window.open(url, '_blank');


       });

		$('#imprimir_pdf').click(function(){
			$tipo =$('#txt_tipo').val();
			var url = '../controlador/contabilidad/contabilidad_controller.php?reporte_pdf=true&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&Tipo='+$tipo;
			window.open(url, '_blank');


       });
		agencias();

    });

// $('#myModal_espera').modal('show');




function bamup(tipo)
{

  $('#myModal_espera').modal('show');
	$('#txt_tipo').val(tipo);
	var parametros = 
 	{
 		'desde':$('#desde').val(),
 		'hasta':$('#hasta').val(),
 		'TipoBa':$('#TipoBenef').val(),
 		'TipoPre':$('input:radio[name=optionsRadios]:checked').val(),
 		'Tipo':tipo,
 		'Imp':false,
 		'Agencia':$('#rbl_agencia').prop('checked'),
 		'AgenciaVal':$('#lista_agencia').val(),
 		//'ES' o 'ER',
 	}
//parametros que se van a enviar al controlado  tanto para SP como para reporte

//el meto de envio de datos al controlador sera por GET y por POST;
//POST se van a enviar los parametros
//GET se va a enviar la variable "balance=true" en la url;

// cuando exclusivamente sea  grilla lo que vayamos a mostrar el   dataType: 'json' debe estar comentado
	$.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/contabilidad_controller.php?balance=true',
      type:  'post',
     dataType: 'json',
         beforeSend: function () {		
			  //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
				 // $('#tabla_').html(spiner);
				 // $('#myModal_espera').modal('show');
			},
        success:  function (response) {
        	//resultado que obtenemos de todo el proceso si es -1 fallo proceso 
        	if(response.respuesta == -1)
        	{        		
        		 $('#myModal_espera').modal('hide');
        	}else
        	{
        		//colocamos el resultado donde queramos
        		// console.log(response);
        		 $('#myModal_espera').modal('hide');
        		$('#tabla').html(response);
        	}
      },
       error: function(xhr, textStatus, error){
		    $('#myModal_espera').modal('hide');
		    Swal.fire('No se pudo cargar','Intente mas tarde','info');
		  }
    }); 
}

function agencias()
{
	$.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/contabilidad/contabilidad_controller.php?agencias=true',
      type:  'post',
     dataType: 'json',
         beforeSend: function () {		
			  //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
				 // $('#tabla_').html(spiner);
				 // $('#myModal_espera').modal('show');
			},
        success:  function (response) {
        	//resultado que obtenemos de todo el proceso si es -1 fallo proceso 
        	llenarComboList(response,'lista_agencia');
        	console.log(response);
      },
       error: function(xhr, textStatus, error){
		  
		    Swal.fire('No se pudo cargar','Intente mas tarde','info');
		  }
    }); 
}

//Date picker
/*$('#desde').datepicker({
    dateFormat: 'dd/mm/yyyy',
    autoclose: true
});
$('#hasta').datepicker({
    dateFormat: 'dd/mm/yyyy',
    autoclose: true
});*/
//modificar url
function modificar(texto){
    if(texto=='NI')
    {
        texto='';
    }
    var fechai = document.getElementById('desde');
    var fechaf = document.getElementById('hasta');
    //alert(fechai.value+' '+fechaf.value);
    /*
    //cambiamos formato
    var ca1 = fechai.split('/');
    alert(ca1.length);
    var ca2 = fechaf.split('/');
    alert(ca2.length);
    for(var i=0;i < ca.length;i++) {

    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) {
        return decodeURIComponent( c.substring(nameEQ.length,c.length) );
    }

    }*/
    var l1=$('#l1').attr("href");
    var l1=l1+'&OpcDG='+texto+'&fechai='+fechai.value+'&fechaf='+fechaf.value;
    //asignamos
    $("#l1").attr("href",l1);

    var l2=$('#l2').attr("href");
    var l2=l2+'&OpcDG='+texto+'&fechai='+fechai.value+'&fechaf='+fechaf.value;
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
function modificarb1(id)
{
    texto='';
    if (document.getElementById(id).checked)
    {
        //alert('Seleccionado');
        document.getElementById("ineg").style.display = "block";
    }
    else
    {
        document.getElementById("ineg").style.display = "none";
        //alert('ppp');
        var l1=$('#l1').attr("href");
        var l1=l1+'&OpcBs='+texto;
        //asignamos
        $("#l1").attr("href",l1);

        var l2=$('#l2').attr("href");
        var l2=l2+'&OpcBs='+texto;
        //asignamos
        $("#l2").attr("href",l2);

        var l4=$('#l4').attr("href");
        var l4=l4+'&OpcBs='+texto;
        //asignamos
        $("#l4").attr("href",l4);

        var l5=$('#l5').attr("href");
        var l5=l5+'&OpcBs='+texto;
        //asignamos
        $("#l5").attr("href",l5);

        var l6=$('#l6').attr("href");
        var l6=l6+'&OpcBs='+texto;
        //asignamos
        $("#l6").attr("href",l6);
    }
}
function agregarSu(id)
{
    var select = document.getElementById(id); //El <select>
    texto = select.value;

    var l1=$('#l1').attr("href");
    var l1=l1+'&OpcBs='+texto;
    //asignamos
    $("#l1").attr("href",l1);

    var l2=$('#l2').attr("href");
    var l2=l2+'&OpcBs='+texto;
    //asignamos
    $("#l2").attr("href",l2);

    var l4=$('#l4').attr("href");
    var l4=l4+'&OpcBs='+texto;
    //asignamos
    $("#l4").attr("href",l4);

    var l5=$('#l5').attr("href");
    var l5=l5+'&OpcBs='+texto;
    //asignamos
    $("#l5").attr("href",l5);

    var l6=$('#l6').attr("href");
    var l6=l6+'&OpcBs='+texto;
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