function validar_cuenta(campo)
{

	var id = campo.id;
	let cap = $('#'+id).val();
	let cuentaini = cap.replace(/[.]/gi,'');
	var cuentafin = $('#txt_CtaF').val();
	// var formato = "<?php if(isset($_SESSION['INGRESO']['Formato_Cuentas'])){echo $_SESSION['INGRESO']['Formato_Cuentas'];}?>";
	// console.log(formato);
	// ---formato se se encuenta en header

	let parte =formato.split('.');
	var nuevo =  new Array(); 
	let cadnew ='';
	console.log(parte)
	for (var i = 0 ; i < parte.length; i++) {

		if(cuentaini.length != '')
		{
			var b = parte[i].length;
			var c = cuentaini.substr(0,b);
			if(c.length==b)
			{
				if(c==0){ $('#'+id).val(''); return false;}
				nuevo[i] = c;
				cuentaini = cuentaini.substr(b);
			}else
			{   
			  if(c != 0){  
					var n = c;
					ceros = '0'.repeat(b-n.length);
					nuevo[i] =n+''+ceros;
		            cuentaini = cuentaini.substr(b);
			        
			  }else
			  {
			  	uno = '1'.repeat(b-c.length);
			  	nuevo[i] =c+''+uno;
			    cuentaini = cuentaini.substr(b);
			  }
			}
		}
	}
	var m ='';
	nuevo.forEach(function(item,index){
		m+=item+'.';
	})
	//console.log(m);
	$('#'+id).val(m);
}

function validar_cuenta_inv(campo)
{
	var id = campo.id;
	let cap = $('#'+id).val();
	let cuentaini = cap.replace(/[.]/gi,'');
	// var formato = "<?php if(isset($_SESSION['INGRESO']['Formato_Cuentas'])){echo $_SESSION['INGRESO']['Formato_Cuentas'];}?>";
	// console.log(formato);
	// ---formato se se encuenta en header
	let parte =formato_inv.split('.');
	var nuevo =  new Array(); 
	let cadnew ='';
	for (var i = 0 ; i < parte.length; i++) {

		if(cuentaini.length != '')
		{
			var b = parte[i].length;
			var c = cuentaini.substr(0,b);
			if(c.length==b)
			{
				nuevo[i] = c;
				cuentaini = cuentaini.substr(b);
			}else
			{   
			  if(c != 0){  
				//for (var ii =0; ii<b; ii++) {
					var n = c;
					//if(n.length==b)
					//{
					   //if(n !='00')
					  // {
						nuevo[i] =n;
			            cuentaini = cuentaini.substr(b);
			         //  }
			         //break;
					  
					//}else
					//{
					//	c = n;
					//}
					
				//}
			  }else
			  {
			  	nuevo[i] =c;
			    cuentaini = cuentaini.substr(b);
			  }
			}
		}
	}
	var m ='';
	nuevo.forEach(function(item,index){
		m+=item+'.';
	})
	//console.log(m);
	$('#'+id).val(m);
}



function validar_year_mayor(nombre)
{

	var fecha = $('#'+nombre+'').val();
	var partes = fecha.split('-');
	if(partes[0].length > 4 || partes[0] > 2050)
	{
		$('#'+nombre+'').val('2050-'+partes[1]+'-'+partes[2]);
	}
	

}
function validar_year_menor(nombre)
{

	var fecha = $('#'+nombre+'').val();
	var partes = fecha.split('-');
	if(partes[0] < 2000)
	{
		alert('Año seleccionado menor a 1999');
		$('#'+nombre+'').val('1999-'+partes[1]+'-'+partes[2]);
	}
}
function addCommas(nStr) 
{
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
function num_caracteres(campo,num)
{
	var val = $('#'+campo).val();
	var cant = val.length;

	if(cant>num)
	{
		$('#'+campo).val(val.substr(0,num));
		return false;
	}

}

String.prototype.ucwords = function() {
	str = this.toLowerCase();
	// return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
	// 	function($1){
	// 		return $1.toUpperCase();
	// 		});
	return str.toUpperCase(); 
}

function llenarComboList(datos,nombre){
    var nombreCombo = $("#"+nombre);
    nombreCombo.find('option').remove();
    for (var indice in datos) {      
      nombreCombo.append('<option value="' + datos[indice].codigo + '">' + datos[indice].nombre + '</option>');        
    }
}

function is_numeric(value) {
	return !isNaN(parseFloat(value)) && isFinite(value);
}

function soloNumerosDecimales(e)
{
	var key = window.Event ? e.which : e.keyCode
	return (key <= 13 || (key >= 48 && key <= 57) || key==46)
}
function mayusculas(campo,valor)
{
    $('#'+campo).val(valor.ucwords());
}
function mayusculasevent(e) {
    e.value = e.value.toUpperCase();
}

function generar_ceros(num,cant)
{
	var n = num.toString();
	var len_num = n.length;
	// console.log(len_num);
	// console.log(cant);
	if(len_num<cant)
	{
		ceros = cant-len_num;
		var new_num = '0'.repeat(ceros);
		return new_num+''+num;
	} 
}

function fecha_valida(campo)
{
	// console.log(campo.id);
	valor = $('#'+campo.id).val();
	console.log(valor)
	if(campo=='' || campo==undefined || valor=='' || valor==undefined)  
	{
		// alert('Fecha no valida');
		Swal.fire('Fecha no valida','','info').then(function(){
			  $('#'+campo.id).focus();
		});
	}
}


 function validar_numeros_decimal(campo) 
 {
 	contador = 0;
 	valor = $('#'+campo.id).val();
 	valor = valor.replace(',','.')
   	var new_val = valor.replace(/[^0-9.]/g, '');
   	var contador = 0;

      for (var i = 0; i < valor.length; i++) {
        if (valor[i] === '.') {
          contador++;
        }
      }

      if(contador>1)
      {
      	new_val = new_val.substr(0,new_val.length-1);
      }
      $('#'+campo.id).val(new_val);
 }


function validar_float(campo,decimales)
{
	valor = $('#'+campo.id).val();
	if(valor=='' || valor=='.')
	{
		$('#'+campo.id).val('0.'+'0'.repeat(decimales));
	}

	valor = valor.replace(',','.')

	contador = 0;
 	valor = $('#'+campo.id).val();
   	var new_val = valor.replace(/[^0-9.]/g, '');
   	var contador = 0;

      for (var i = 0; i < valor.length; i++) {
        if (valor[i] === '.') {
          contador++;
        }
      }

      if(contador>1)
      {
      	new_val = new_val.substr(0,new_val.length-1);
      }

      if(new_val.indexOf('.')!='-1')
      {
	      dec = new_val.split('.');
	      console.log(dec);
	      if(dec[1].length<decimales && dec[1].length!=decimales )
	      {
	      	   dec_can = decimales-(dec[1].length)
	      	   decN = dec[1]+'0'.repeat(dec_can);
	      	   if(dec[0]!='')
	      	   {
	      	   	new_val = dec[0]+'.'+decN; 
	      	   }else
	      	   {
	      	   		new_val = '0'+'.'+decN; 
	      	   }
	      }
	  }else{
	  	console.log(new_val.indexOf('.'));
	  	 new_val = new_val+'.'+'0'.repeat(decimales);
	  }
      $('#'+campo.id).val(new_val);
}

function validar_cantidad_numeros_blur(campo,min,max=false)
{  
  var v = $('#'+campo.id).val();
  var num = v.length;
  if(num < min || num>max)
  {
  	Swal.fire('El numero de ingresado debes tener como minimo :'+min+' y maximo:'+max,'','info');
  	return false;
  }
}

function paginacion(funcion,posicion,inicio=0,numreg=50,variables=false)
{
	// console.log(variables);
	$.ajax({
       // data:  {parametros:parametros},
      url:   '../controlador/panel.php?paginacion=true&ini='+inicio+'&numreg='+numreg,
      type:  'post',
      dataType: 'json',
       success:  function (response) { 
      }
    });
    var vari = '()';
    if(variables!=false)
    {
    	variables = variables.split(',');
    	cadena = '';
    	if(variables.length>1){
    		variables.forEach(function(item,i){
    			cadena+="'"+item+"',";
    		})
    		cadena = cadena.substr(0,-1);
    		vari = "("+cadena+")";

    	}else
    	{
    		vari = "('"+variables+"')";
    	}
    	

    }

    // console.log(inicio+'-'+numreg);
    var j = 0; var k = 10;
    var pos = (inicio/numreg)+1;
    var rangos = true;
    while(rangos==true)
    {
    	rangos = false;
    	if(pos >=j && pos<k)
    	{
    		rangos= false;
    	}else
    	{
    		console.log('asd');
    		j+=9;
    		k+=10;

    		// rangos= false;

    		console.log(pos);
    	}
    }

    // console.log(rangos);
    // console.log(j);
    // console.log(k);
    // console.log(pos);

	var pag = '<nav aria-label="...">'+
	  '<ul class="pagination pagination-sm" style="margin: 0px;">'+
	   ' <li class="page-item disabled">'+
	      '<!-- <a class="page-link" href="#" tabindex="-1">Inicio</a> -->'+
	   ' </li>'
	    if(j>=9)
	   	{
	    	pag+=' <li class="page-item"  onclick="paginacion(\''+funcion+'\',\''+posicion+'\',0,50,'+variables+');'+funcion+vari+'"><a class="page-link" href="#">1</a></li>'
	   	}
	    for (var i = j; i < k; i++) {
	    	 pos = (inicio/numreg);
	    	// console.log(pos);
	    	var ini = i*numreg
	    	if(pos==i)
	    	{
		   		pag+=' <li class="page-item active" onclick="paginacion(\''+funcion+'\',\''+posicion+'\','+ini+','+numreg+','+variables+');'+funcion+vari+'"><a class="page-link" href="#">'+(i+1)+'</a></li>'
			}else
			{
				pag+=' <li class="page-item"  onclick="paginacion(\''+funcion+'\',\''+posicion+'\','+ini+','+numreg+','+variables+');'+funcion+vari+'"><a class="page-link" href="#">'+(i+1)+'</a></li>'
			}
		}
	    pag+='<li class="page-item">'+
	      '<!-- <a class="page-link" href="#">Fin</a> -->'+
	    '</li>'+
	  '</ul>'+
	  '<!-- <select id="ddl_pag" onchange="paginacion(\''+funcion+'\',\''+posicion+'\','+ini+','+numreg+','+variables+');'+funcion+vari+'">'+
	'<option value="50">50</option>'+
	'<option value="100">100</option>'+
	'<option value="150">150</option>'+
	'</select> -->'+
	'</nav>';

	// setTimeout(funcion,100);
	$('#'+posicion).html(pag);
	$('#ddl_pag').val();
}

function fecha_actual()
{
	 var d = new Date();
    var month = d.getMonth()+1;
    var day = d.getDate();
    var output = d.getFullYear() + '-' +((''+month).length<2 ? '0' : '') + month + '-' +((''+day).length<2 ? '0' : '') + day;
    return output;
}

 function tipo_error_sri(clave)
  {
    var parametros = 
    {
      'clave':clave,
    }
     $.ajax({
      type: "POST",
      url: '../controlador/facturacion/punto_ventaC.php?error_sri=true',
      data: {parametros: parametros},
      dataType:'json', 
      success: function(data)
      {
        
         console.log(data);
        $('#myModal_sri_error').modal('show');
        $('#sri_estado').text(data.estado[0]);
        $('#sri_codigo').text(data.codigo[0]);
        $('#sri_fecha').text(data.fecha[0]);
        $('#sri_mensaje').text(data.mensaje[0]);
        $('#sri_adicional').text(data.adicional[0]);
        // $('#doc_xml').attr('href','')
      }
    });
  }

 function asignarHeightPantalla(elemento, input) {
	var distanciaHastaElBordeInferior = $(window).height() - (elemento.offset().top + elemento.outerHeight());
	let disponible = distanciaHastaElBordeInferior-elemento.outerHeight(true);
	input.val(((disponible>100)?disponible:100));
	$(window).resize(function() {
	    var distanciaHastaElBordeInferior = $(window).height() - (elemento.offset().top + elemento.outerHeight());
		let disponible = distanciaHastaElBordeInferior-elemento.outerHeight(true);
		input.val(((disponible>100)?disponible:100));
	});
 }

function esDiferenteDeCero(numero) {
  return parseFloat(numero) !== 0 && parseFloat(numero).toFixed(3) !== "0.000";
}

function formatearNumero(numero, decimales, separadorDecimal, separadorMiles) {
    decimales = isNaN(decimales = Math.abs(decimales)) ? 2 : decimales;
    separadorDecimal = separadorDecimal === undefined ? "." : separadorDecimal;
    separadorMiles = separadorMiles === undefined ? "," : separadorMiles;
  
    var signo = numero < 0 ? "-" : "";
    var parteEntera = parseInt(numero = Math.abs(+numero || 0).toFixed(decimales), 10) + "";
  
    var longitud = parteEntera.length > 3 ? parteEntera.length % 3 : 0;
  
    return signo +
      (longitud ? parteEntera.substr(0, longitud) + separadorMiles : "") +
      parteEntera.substr(longitud).replace(/(\d{3})(?=\d)/g, "$1" + separadorMiles) +
      (decimales ? separadorDecimal + Math.abs(numero - parteEntera).toFixed(decimales).slice(2) : "");
}

function agregarOpcionPorDefecto(selectId) {
  var select = $('#' + selectId);

  // Verificar si el select no tiene opciones
  if (select.find('option').length === 0) {
    // Agregar una opción por defecto
    select.append($('<option>', {
      value: '',
      text: 'Sin data'
    }));
  }
}

async function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function TextoValido(input, Numero=false, Mayusculas=false, NumeroDecimales=2) {
	var TextB = $(input)
    var TextosB = TextB.val();

    if (TextosB === null || TextosB === undefined) {
        TextosB = "";
    }

    TextosB = TextosB.replace(/\r|\n/g, "");
    TextosB = TextosB.trim();

    if (Mayusculas) {
        TextosB = TextosB.toUpperCase();
    }

    if (Numero) {
        if (TextosB === "") {
            TextosB = "0";
        }

        if ($.isNumeric(TextosB)) {
            switch (true) {
                case NumeroDecimales === 0:
                    TextosB = Number(TextosB).toFixed(2);
                    break;
                case NumeroDecimales > 2:
                    TextosB = Number(TextosB).toFixed(NumeroDecimales);
                    break;
                default:
                    TextosB = Number(TextosB).toFixed(2);
                    break;
            }

            TextB.val(TextosB.trim());
        } else {
            TextosB = "0";
            TextB.val(TextosB);
            TextB.focus();
        }
    } else {
        if (TextosB === "") {
            TextosB = ".";
        }

        TextB.val(TextosB);
    }
}

function validar_sriC(ci)
  {
    $('#myModal_espera').modal('show');
    $.ajax({
    data: {ci,ci},
    url: '../controlador/modalesC.php?validar_sri_cliente=true',
    type: 'POST',
    dataType: 'json',
    success: function(response) {
      $('#myModal_espera').modal('hide');
      if(response.res=='1')
        {
          $('.LblSRI').html(response.tbl).css('background-color','rgb(226 251 255)');
          if(response.data){
            if ($('#nombrec').length > 0) {
              if($('#nombrec').val()!='' && $('#nombrec').val()!=response.data.RazonSocial){
                Swal.fire({
                  html: `ESTE RUC ESTA ASIGNADO A:<br>${$('#nombrec').val()}<br>
                  LA INFORMACION CORRECTA DEL R.U.C. ES:<br>
                  ${response.data.RazonSocial} <br>
                  ¿Desea actualizar el campo Apellidos y Nombres?`,
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  cancelButtonText: 'No.',
                  confirmButtonText: 'Si'
                }).then((result) => {
                  $('#nombrec').val(response.data.RazonSocial);
                  $("#BtnGuardarClienteFCliente").focus()
                })
              }else{
                $('#nombrec').val(response.data.RazonSocial);
              }
            }
          }
        }else
        {
          $('.LblSRI').html('');
          Swal.fire(response.msg,'','info')
        }

      }
    });

  }


function solo_numeros(input)
{
   var valor = input.value;

    // Reemplazar cualquier caracter que no sea un número con una cadena vacía
    var soloNumeros = valor.replace(/\D/g, '');

    // Actualizar el valor del input con solo números
    input.value = soloNumeros;
}

function DCPorcenIva(idFecha, idSelect){
	// alert(idFecha);
	var parametros = {
		'Fecha_Ini': $('#'+idFecha).val()
	};
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturarC.php?DCPorcenIVA=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (data) {
			llenarComboList(data,idSelect);

			// $('#'+idSelect).empty();
			// $.each(data, function (i, item) {
			// 	$('#'+idSelect).append($('<option>', {
			// 		value: item.Porc,
			// 		text: item.Porc
			// 	}));
			// });
		}
	});

}

function truncarValor(num) {
    return Math.floor(num * 100) / 100;
}

function contarDecimales(num) {
    const decimales = num.toString().split('.')[1];
    return decimales ? decimales.length : 0;
}

function solo_3_numeros(id)
{  
  var v = $('#'+id).val();
  if(v.length >3)
  {
   val  = v.substr(0,3);
    $('#'+id).val(val);
  }else{
    $('#'+id).val(v);
  }
}
