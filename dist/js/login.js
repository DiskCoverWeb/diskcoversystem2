$(document).ready(function(){
 	$('#txt_contra').on('keypress', function(e) {
            if (e.which === 13) {
               /*Ingresar();*/Ingresar_vali();
            }
        });
});

function validar_entidad()
  { 
	var entidad = $("#txt_entidad").val();

     $.ajax({
      data:  {'entidad':entidad},
      url:   '../controlador/loginC.php?Cartera_Entidad=true&pantalla='+screen.height,
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) { 
         if(response==-2)
         {     	
	  		$('#lbl_razon').text('Diskcover system');
	  		$('#img_logo').removeAttr('style');
	  		$('#img_logo').attr('src','../../img/jpg/logo.jpg');
	        $('#txt_entidad_id').val('');	        
	  		$('#lbl_nombre').css('display','none');
	        Swal.fire('Error Entidad!','"La entidad que ingresaste no tiene el formato correcto.','error');
         }
        if(response.length==1)
        {
        	$('#alerta').css('display','block');
        	$('#txt_item').val(response[0].Item);
        	if(response[0].Nombre == response[0].Razon_Social)
					{
        		$('#alerta').html(response[0].Nombre);
        	}else
        	{
        		$('#alerta').html(response[0].Razon_Social+'<br>'+response[0].Nombre);   
        		$('#alerta').css('font-size','10px');        		
        	}
			var newImgSrc = response[0].Logo + "?timestamp=" + new Date().getTime();
        	$('#img_logo').attr('src',newImgSrc);
        	$('#img_logo').css('width','35%');
        	$('#img_logo').css('border-radius','5px');
        	$('#txt_entidad_id').val(response[0].entidad);
        	$('#txt_cartera').val('0');


        }else if(response.length>1)
        {
        	 $('#lbl_ruc').text(entidad);
        	 tr = '';
        	 response.forEach(function(item, i) {
				// Añadir un timestamp o un identificador único a la URL de la imagen para evitar la caché
				var uniqueImgSrc = item.Logo + '?v=' + new Date().getTime();

				// tr += '<tr><td><img style="height: 73px;width: 190px;" src="' + uniqueImgSrc + '"></td><td><button class="btn btn-block btn-default" onclick="seleccionar_empresa(\'' + item.Nombre + '\',\'' + item.Razon_Social + '\',\'' + uniqueImgSrc + '\',\'' + item.entidad + '\',\'' + item.Item + '\')">' + item.Razon_Social + '<br>' + item.Nombre + '<br>' + item.ci + '</button></td></tr>';
				tr+= '<div class="customers-list-item d-flex align-items-center border-bottom p-2 cursor-pointer" onclick="seleccionar_empresa(\'' + item.Nombre + '\',\'' + item.Razon_Social + '\',\'' + uniqueImgSrc + '\',\'' + item.entidad + '\',\'' + item.Item + '\')">'
						+'<div class="p-2">'
							+'<img src="' + uniqueImgSrc + '" height="46px" width="92px" alt="" />'
						+'</div>'
						+'<div class="ms-2">'
							+'<h6 class="mb-1 font-14">' + item.Razon_Social + '</h6>'
							+'<p class="mb-0 font-13 text-secondary">' + item.Nombre + '</p>'
							+'<p class="mb-0 font-13 text-secondary">' + item.ci + '</p>'
						+'</div>'
					+'</div>';

        	 	// console.log(item)
			})

        	 	// console.log(item)
        	 $('#tbl_empresas').html(tr);
        	 $('#mis_empresas').modal('show');
        }else
        {
	        	$('#lbl_razon').text('Diskcover system');
		  		$('#img_logo').removeAttr('style');
		  		$('#img_logo').attr('src','../../img/jpg/logo.jpg');
		        $('#txt_entidad_id').val('');	        
		  		$('#lbl_nombre').css('display','none');
        		Swal.fire('Error Entidad!','No se a encontrado la entidad.','error');
        }

      }
    });

  }

  function seleccionar_empresa(Nombre,Razon_Social,Logo,entidad,item)
  {
  		$('#mis_empresas').modal('hide');

  	 	$('#txt_item').val(item);
	  	if(Nombre == Razon_Social)
		{
	  		$('#lbl_razon').text(Nombre);
	  		$('#lbl_nombre').css('display','none');
	  	}else
	  	{
	  		$('#lbl_nombre').css('display','block');

	  		$('#lbl_razon').text(Razon_Social);
	  		$('#lbl_nombre').text(Nombre);      		
	  	}

	  	$('#img_logo').attr('src',Logo);
	  	$('#img_logo').css('width','35%');
	  	$('#img_logo').css('border-radius','5px');
	  	$('#txt_entidad_id').val(entidad);
	  	$('#txt_cartera').val('0');
	  	$('#txt_correo').focus();

	  	// var parametros = 
	  	// {
	  	// 	'empresa':Nombre,
	  	// 	'item_cartera':item,
	  	// }

	  	//  $.ajax({
	    //   data:  {parametros:parametros},
	    //   url:   '../controlador/login_controller.php?setear_empresa=true',
	    //   type:  'post',
	    //   dataType: 'json',
	    //   /*beforeSend: function () {   
	    //        var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
	    //      $('#tabla_').html(spiner);
	    //   },*/
	    //     success:  function (response) { 
	        
	    //   }
	    // });
  }
  
  function validar_usuario()
  { 

		 var usuario = $("#txt_correo").val();
		 var entidad = $("#txt_entidad_id").val();		 
		 var item = $("#txt_item").val();

		 if(usuario =='')
		 {
		 	return false;
		 }
		 if(entidad=='')
		 {
		 	return false;
		 }
		 var parametros = 
		 {
		 	 'usuario':usuario,
		 	 'entidad':entidad,
		 	 'item':item,
		 }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/loginC.php?Validar_Usuario=true',
      type:  'post',
      dataType: 'json',     
        success:  function (response) { 
        	// console.log(response);
        if(response.respuesta == -1)
        {
            $('#txt_cartera').val('1');
            $('#correo_cartera').val(response.cartera_usu);
			$('#contra_cartera').val(response.cartera_pass);
      }else if(response.respuesta==-2)
        {      	
        	$('#txt_cartera').val('0');
        	Swal.fire('Este usuario se encuentra bloqueado!','Usuario bloqueado.','error');
        }else if(response.respuesta==1)
        {
        	$('#txt_cartera').val('0');
        }     
      }
    });

  }

  function Ingresar_vali()
  {
  	if($('#txt_cartera').val()==1)
  	{
  		 Swal.fire({
           title: 'Esta seguro?',
           text: "Esta apunto de entrar a la cartera de clientes!",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Si!'
         }).then((result) => {
           if (result.value==true) {
				Ingresar();
           }else
           {
           		$('#txt_correo').val('');
           		$('#txt_contra').val('');
           		$('#txt_cartera').val(0);           	 
           }
         })

  	}else
  	{
		Ingresar();	
  	}
  }

  function Ingresar()
  { 

		 var usuario = $("#txt_correo").val();
		 var entidad = $("#txt_entidad_id").val();
		 var item = $("#txt_item").val();
		 var pass = $("#txt_contra").val();		 
		 var cartera = $("#txt_cartera").val();
		 var cartera_usu = $("#correo_cartera").val();
		 var cartera_pass = $("#contra_cartera").val();
		 var ci_empresa = $("#txt_entidad").val();
		 if(entidad =='')
		 {
		 		Swal.fire('No se a verificado la entidad Asegurese de colocar una entidad valida','Se volvera a verificar la empresa','info').then(function(){ $('#entidad').focus()});
		 	return false;
		 }
		 if(pass=='' || entidad=='')
		 {
		 	Swal.fire('Llene todo los campos','Asegurese de colocar una entidad, usuario y password validos','info')
		 	return false;
		 }

		 var localIp;
		 var pc = new RTCPeerConnection();
      pc.createDataChannel('');
      pc.createOffer(function(sdp) {
          pc.setLocalDescription(sdp);
      }, function() {});
      pc.onicecandidate = function(event) {
        if (event.candidate) {
            var ipRegex = /(\d+\.\d+\.\d+\.\d+)/;
            let a = ipRegex.exec(event.candidate.candidate);
            if(a){
            	localIp = a[1];
            }
				}
			}

		
			
		 var parametros = 
		 {
		 	 'usuario':usuario,
		 	 'entidad':entidad,
		 	 'item':item,
		 	 'empresa':ci_empresa,
		 	 'pass':pass,
		 	 'cartera':cartera,
		 	 'cartera_usu':cartera_usu,
		 	 'cartera_pass':cartera_pass,
		 	 'localIp':localIp,
			 'ipWAN': ""
		 }


      $('#btn_ingreso').attr('disabled',true);
      $('#loader_ingreso').css('display','inline-block');

     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/loginC.php?Ingresar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) { 

        	console.log(response);
        if(response==-1)
        {
        	Swal.fire('Clave o usuario invalidos!','No se pudo acceder. Comuniquese con la institucion para ser habilitado.','error');
        	$('#btn_ingreso').attr('disabled',false);
      		$('#loader_ingreso').css('display','none');
        }else if(response==-2)
        {        	
        	Swal.fire('Clave o usuario invalidos!','No se pudo acceder.  Comuniquese con la institucion para ser habilitado.','error');
        	$('#btn_ingreso').attr('disabled',false);
      		$('#loader_ingreso').css('display','none');
        }
        else
        {     	
        	// console.log(response); return false;
        	window.location.href = "modulos.php";
        }     
      },
	    error: function (error) {
	      console.error('Error en numero_comprobante:', error);
      		$('#btn_ingreso').attr('disabled',false);
      		$('#loader_ingreso').css('display','none');
	    },
    });

  }


    function logout() {

      $.ajax({
        // data:  {parametros:parametros},
        url: '../controlador/loginC.php?logout=true',
        type: 'post',
        dataType: 'json',
        /*beforeSend: function () {   
             var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
           $('#tabla_').html(spiner);
        },*/
        success: function (response) {
          console.log(response);
          if (response == 1) {
            location.href = 'login.php';
          }
        }
      });
    }
  
