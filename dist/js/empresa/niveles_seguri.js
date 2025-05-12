let listado_empresas = [];
  let listado_empresas_modificados = [];
  $(document).ready(function()
  {
    //cargar tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
   // cargar_modulos();
   todos_modulos();
   autocmpletar();
   autocmpletar_usuario();

  $('#ddl_usuarios').on('select2:select', function (e) {
        // console.log(e);
        limpiar();
        var data = e.params.data.data;    
        $('#txt_usu').val(data.Usuario);
        $('#txt_cla').val(data.Clave);
        $('#txt_nom').val(data.Nombre_Usuario);
        $('#txt_ced').val(data.CI_NIC);
        $('#txt_ema').val(data.Email);
        $('#txt_id_usu').val(data.ID);

        console.log(data);
  }); 



  });

  function autocmpletar(){
      $('#ddl_entidad').select2({
        placeholder: 'Seleccione una Entidad',
        width: '180px',
        dropdownAutoWidth: true,
        ajax: {
          url: '../controlador/empresa/niveles_seguriC.php?entidades=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true,
        }
      });
  }

  function guardar_accesos()
  {
    data = $('#form_modulos_check').serialize();
    console.log(data);
  }

  function autocmpletar_usuario(){
       let entidad = $('#ddl_entidad').val();	
      $('#ddl_usuarios').select2({
        placeholder: 'Seleccione un Usuario',
        width: '200px',
        dropdownAutoWidth: 'true', 
        ajax: {
          url: '../controlador/empresa/niveles_seguriC.php?usuarios=true&entidad='+entidad,
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

  function limpiar_check_todos()
  {

  }

  function buscar_permisos()
  {
    
      var id = $('#ddl_usuarios').val();
      var texto = $('select[name="ddl_usuarios"] option:selected').text();
      cargar_empresas();
      $('#ddl_usuarios').append($('<option>',{value: id, text:texto,selected: true }));
  	if($('#ddl_usuarios').val()!='')
  	{
  		usuario();
  	}    
  }

   function usuario_empresa()
  {

      var parametros ={
        'entidad':$('#ddl_entidad').val(),
        'usuario':$('#ddl_usuarios').val(),
      }
      $.ajax({
         data:  {parametros:parametros},
        url:   '../controlador/empresa/niveles_seguriC.php?usuario_empresa=true',
        type:  'post',
        dataType: 'json',
        // beforeSend: function () { 
        //   $('#modu').html('<img src="../../img/gif/loader4.1.gif" width="50%">');
        // },
        success:  function (response) { 
          if(response)
           {
            $.each(response,function(i,item){
              // console.log(item);
               var ind = item.split('_');
               $('#'+item).prop('checked',true);
               // console.log('#indice_'+ind[2]);
               $('#indice_'+ind[2]).css('display','initial');  
            })

             // console.log(response);
           }
        }
      });

  }

   function todos_modulos()
  {

      listado_empresas_modificados = [];

      var parametros ={
        'entidad':$('#ddl_entidad').val(),
        'usuario':$('#ddl_usuarios').val(),
      }
      $.ajax({
         data:  {parametros:parametros},
        url:   '../controlador/empresa/niveles_seguriC.php?todos_modulos=true',
        type:  'post',
        dataType: 'json',
        // beforeSend: function () { 
        //   $('#modu').html('<img src="../../img/gif/loader4.1.gif" width="50%">');
        // },
        success:  function (response) { 
         $('#todo_modulos').html(response)
        }
      });

  }

  function guardar_pag(entidad,empresa,usuario,pag,mod)
  {
      var estado = $('#rbl_'+pag).prop('checked');
      var parametros ={
        'entidad':entidad,
        'empresa':empresa,
        'usuario':usuario,
        'estado':estado,
        'pagina':pag,
        'modulo':mod,
      }
      $.ajax({
         data:  {parametros:parametros},
        url:   '../controlador/empresa/niveles_seguriC.php?savepag=true',
        type:  'post',
        dataType: 'json',
        // beforeSend: function () { 
        //   $('#modu').html('<img src="../../img/gif/loader4.1.gif" width="50%">');
        // },
        success:  function (response) { 
         $('#todo_modulos').html(response)
        }
      });

  }


  function usuario()
  {
      $('#myModal_espera').modal('show'); 
    	var parametros ={
    		'entidad':$('#ddl_entidad').val(),
    		'usuario':$('#ddl_usuarios').val(),
    	}
    	$.ajax({
    		 data:  {parametros:parametros},
    		url:   '../controlador/empresa/niveles_seguriC.php?usuario_data=true',
    		type:  'post',
    		dataType: 'json',
    		// beforeSend: function () { 
    		//   $('#modu').html('<img src="../../img/gif/loader4.1.gif" width="50%">');
    		// },
    		success:  function (response) { 
          console.log(response);
    			if(response)
    			 {
    			 	if(response[0].n1==1)
    			 	{
    			 		$('#rbl_n1').prop('checked',true);
    			 	}else { $('#rbl_n1').prop('checked',false); }
    			 	if(response[0].n2==1)
    			 	{
    			 		$('#rbl_n2').prop('checked',true);
    			 	}else { $('#rbl_n2').prop('checked',false); }
    			 	if(response[0].n3==1)
    			 	{
    			 		$('#rbl_n3').prop('checked',true);
    			 	}else { $('#rbl_n3').prop('checked',false); }
    			 	if(response[0].n4==1)
    			 	{
    			 		$('#rbl_n4').prop('checked',true);
    			 	}else { $('#rbl_n4').prop('checked',false); }
    			 	if(response[0].n5==1)
    			 	{
    			 		$('#rbl_n5').prop('checked',true);
    			 	}else { $('#rbl_n5').prop('checked',false); }
    			 	if(response[0].n6==1)
    			 	{
    			 		$('#rbl_n6').prop('checked',true);
    			 	}else { $('#rbl_n6').prop('checked',false); }
    			 	if(response[0].n7==1)
    			 	{
    			 		$('#rbl_n7').prop('checked',true);
    			 	}else { $('#rbl_n7').prop('checked',false); }
    			 	if(response[0].Supervisor==1)
    			 	{
    			 		$('#rbl_super').prop('checked',true);
    			 	}else { $('#rbl_super').prop('checked',false); }
    			 	$('#txt_usuario').val(response[0].Usuario);
    			 	$('#txt_pass').val(response[0].Clave);
            $('#txt_email').val(response[0].Email);
            $('#txt_ci_nic').val(response[0].CI_NIC);
    			 	console.log(response);
            $('#serie').val(response[0].Serie_FA);
    			 }

            $('#myModal_espera').modal('hide'); 

           setTimeout('usuario_empresa()',2000);
    		}
    	});

  }



  function cargar_empresas()
  {

      listado_empresas_modificados = [];
     style="display: flex; width: 85%;"
     $('#ddl').css('display','flex');     
     $('#ddl').css('width','85%');

  	$('#ddl_usuarios').val('');   
    $('#tbl_modulos').html('');
  	$('#modu').html('<div>No a seleccionado ninguna empresa</div>');
  	$('#txt_empresas').val('');
  	autocmpletar_usuario();
  	let entidad = $('#ddl_entidad').val();
    // alert(entidad);
    // $('#myModal_espera').modal('show');
  	$.ajax({
    		 data:  {entidad:entidad},
    		url:   '../controlador/empresa/niveles_seguriC.php?empresas=true',
    		type:  'post',
    		dataType: 'json',
    		// beforeSend: function () { 
    		//   $('#myModal_espera').modal('show'); 
    		// },
    		success:  function (response) { 
          // console.log(response);
          listado_empresas = [];
           if(response.alerta!='')
           {
            Swal.fire({
                title: response.alerta,
                text:'Esto podria ocasionar problemas futuros',
                type:'info',
                width: 600,
              })
             // Swal.fire(,'s','info');
           }

           opPuntoEmi = '<option value="">Seleccione Empresa</option>';
           $.each(response.empresas,function(i,item){
            console.log(item);
                 listado_empresas.push(item)
                 if(item.dbSQLSERVER==1)
                 {
                    opPuntoEmi+='<option value="'+item.id+'">'+item.text+'</option>'
                 }
              })
          
            var enti = $('#ddl_entidad').val();
            $('#lbl_enti').text(enti);
    			 	$('#myModal_espera').modal('hide');				
    				$('#tbl_modulos').html(response.tbl);
            $('#usuarios_tbl').html(response.usuarios); 
            $('#ddl_empresa_puntoEmi').html(opPuntoEmi); 
            // DoubleScroll(document.getElementById('tbl_modulos'));
    		}
    	});

  }
function guardar()
  {
    var selected=[];
    // var selected = $('#form_modulos_check').serialize();
    var array = Object.values(listado_empresas_modificados); // Convertir objeto a array
    var uniqueArray = Array.from(new Set(array)); // Eliminar duplicados
// console.log(uniqueArray);

    var emp = $('#txt_empresas').val();
   $("#form_modulos_check input[type='checkbox']").each(function() {
      var id = $(this).attr("id");
      var check = this.checked;
      var empEnti = id.replace('rbl_','');
      var empEnti = empEnti.split('_');
      uniqueArray.forEach(function(item,i){
        if(item == empEnti[1])
        {

          empEnti.push(check);
          selected.push(empEnti);

        }
      })

        //console.log(empEnti);
        // empresa[]
        // selected.push($(this).id)
    });

   //console.log(selected);
   //console.log(listado_empresas_modificados);

   enviar_para_guardar(selected,listado_empresas_modificados); 
  }

  function listar_empresa_modificada(item)
  {
    if($('#ddl_usuarios').val()!='')
    {
      listado_empresas_modificados.push(item);
    }else{
      Swal.fire("Seleccione un usuario",'','info');
      return false;
    }
  }

 function enviar_para_guardar(modulos,empresas)
 {
   if($('#ddl_usuarios').val()=='' || $('#ddl_entidad').val()==''){
      Swal.fire("Asegurese de escoger una entidad y un usuario",'','info');
      return false;
   }
 	var parametros = {
 		'n1':$('#rbl_n1').prop('checked'),
 		'n2':$('#rbl_n2').prop('checked'),
 		'n3':$('#rbl_n3').prop('checked'),
 		'n4':$('#rbl_n4').prop('checked'),
 		'n5':$('#rbl_n5').prop('checked'),
 		'n6':$('#rbl_n6').prop('checked'),
 		'n7':$('#rbl_n7').prop('checked'),
 		'super':$('#rbl_super').prop('checked'),
 		'usuario':$('#txt_usuario').val(),
    'nombre': $('select[name="ddl_usuarios"] option:selected').text(),
 		'pass':$('#txt_pass').val(),
    'email':$('#txt_email').val(),
    'serie':$('#serie').val(),
 		'modulos':modulos,
    'empresas':empresas,
 		'entidad':$('#ddl_entidad').val(),
 		'CI_usuario':$('#ddl_usuarios').val(),
 	}

  // console.log(parametros);
  // return false;
 	$.ajax({
    		 data:  {parametros:parametros},
    		url:   '../controlador/empresa/niveles_seguriC.php?guardar_datos=true',
    		type:  'post',
    		dataType: 'json',
    		beforeSend: function () { 
    		 $('#myModal_espera').modal('show'); 
    		},
    		success:  function (response) { 
          // console.log(response);
          if(response.mensaje!='')
          {
            Swal.fire({
               type: 'success',               
               title: response.mensaje,
               text: 'Guardado Correctamente!',
               showConfirmButton: true
               //timer: 2500
               });

          }else
          {

            todos_modulos();
            Swal.fire('Guardado Correctamente','','success');
          }
    			// if(response==1)
    			// 	{    					
    			// 		// $('#modulo').html(response);
    			// 		Swal.fire({
    			// 			//position: 'top-end',
    			// 			type: 'success',
    			// 			title: 'Guardado Correctamente!',
          //       text: response.mensaje,
    			// 			showConfirmButton: true
    			// 			//timer: 2500
    			// 			});
    			// 		$('#myModal_espera').modal('hide'); 
          //     $('#rbl_all').prop('checked',false); 
    			// 		//buscar_permisos();
    			// 	}else if(response== -1)
          //   {
              $('#myModal_espera').modal('hide'); 
          //     Swal.fire('No se pudo crear el usuario para SQLServer','Pongace en contacto con el administrador del sistema, su base no esta actualizada o no tiene las credenciales correctas','error');
          //   }
    		},
          error: function (error) {
            $('#myModal_espera').modal('hide');
            // console.error('Error en numero_comprobante:', error);
            // Puedes manejar el error aquí si es necesario
          },
    	});

 }
 function bloquear()
 {
 	var parametros = 
 	{
 		'entidad':$('#ddl_entidad').val(),
 		'usuario':$('#ddl_usuarios').val(),
 	}
 	$.ajax({
    		data:  {parametros:parametros},
    		url:   '../controlador/empresa/niveles_seguriC.php?bloqueado=true',
    		type:  'post',
    		dataType: 'json',
    		beforeSend: function () { 
    		  $('#myModal_espera').modal('show'); 
    		},
    		success:  function (response) { 
    			if(response == 1)
    			 {
    			 	Swal.fire({
    						//position: 'top-end',
    						type: 'success',
    						title: 'Usuario bloqueado Correctamente!',
    						showConfirmButton: true
    						//timer: 2500
    						});
    					$('#myModal_espera').modal('hide'); 
    			 	
    			 }
    		}
    	});
 }

 function desbloquear()
 {
  var parametros = 
  {
    'entidad':$('#ddl_entidad').val(),
    'usuario':$('#ddl_usuarios').val(),
  }
  $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/empresa/niveles_seguriC.php?desbloqueado=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () { 
          $('#myModal_espera').modal('show'); 
        },
        success:  function (response) { 
          if(response == 1)
           {
            Swal.fire({
                //position: 'top-end',
                type: 'success',
                title: 'Usuario Desbloqueado Correctamente!',
                showConfirmButton: true
                //timer: 2500
                });
              $('#myModal_espera').modal('hide'); 
            
           }
        }
      });

 }

 function limpiar() {

    $('#txt_id_usu').val('');
    $('#txt_usu').val('');
    $('#txt_cla').val('');
    $('#txt_nom').val('');
    $('#txt_ced').val('');
    // $('#ddl_entidad').val('');
    $('#txt_ema').val('');

    //SRI

    $('#txt_id_CatLin').val('');
    $('#rbl_emision').prop('checked',false);
    $('#txt_estab').val('');
    $('#txt_emision').val('');
    $('#txt_email2').val('');
    $('#txt_direccion').val('');
    $('#txt_telefono').val('');
    $('#txt_logo').val('');
 }
 function guardarN()
 {
  var id = $('#txt_id_usu').val();
  var usu=$('#txt_usu').val();
  var cla=$('#txt_cla').val();
  var nom=$('#txt_nom').val();
  var ced=$('#txt_ced').val();
  var ent=$('#ddl_entidad').val();
  var ema=$('#txt_ema').val();

  //SRI

  var ddl_empresa=$('#ddl_empresa_puntoEmi').val();
  var cbx=$('#rbl_emision').prop('checked');
  var idCL = $('#txt_id_CatLin').val();
  var estab=$('#txt_estab').val();
  var emision=$('#txt_emision').val();
  var email=$('#txt_email2').val();
  var dir=$('#txt_direccion').val();
  var tel=$('#txt_telefono').val();
  var logo=$('#txt_logo').val();
  if(ddl_empresa=='' && cbx==true)
  {
    Swal.fire('Seleccione una empresa','','info');
    return false
  }


  var parametros = 
  {
    'id':id,
    'usu':usu,
    'cla':cla,
    'nom':nom,
    'ced':ced,
    'ent':ent,
    'ema':ema,
    'idCL':idCL,
    'cbx':cbx,
    'emp':ddl_empresa,
    'estab':estab,
    'emision':emision,
    'email2':email,
    'direc':dir,
    'tel':tel,
    'logo':logo,

  }
  if(ent != "")
  {
   if(usu=='' || cla == '' || nom== '' || ced == '' )
   {
    Swal.fire({
      type: 'info',
      title: 'Llene todo los campos!',
      showConfirmButton: true});
   }else
   {
      $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/empresa/niveles_seguriC.php?nuevo_usuario=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () { 
          // $('#myModal_espera').modal('show'); 
        },
        success:  function (response) { 
          if (response == 1)
           {

          $('#myModal_espera').modal('hide'); 
         
             Swal.fire({
                 type: 'success',
                 title: 'Usuario Guardado!',
                 showConfirmButton: true});
          
          $('#myModal').modal('hide'); 
           }else if(response == -2)
           {

          $('#myModal_espera').modal('hide'); 
            Swal.fire({
              type: 'info',
              title: 'Usuario y Clave existente!',
              showConfirmButton: true});

           }
           else if(response == -3)
           {

          $('#myModal_espera').modal('hide'); 
            Swal.fire({
              type: 'info',
              title: 'Nuevo Usuario no registrar en base de datos de la entidad!',
              showConfirmButton: true});

           }else
           {

          $('#myModal_espera').modal('hide'); 
             Swal.fire({
              type: 'error',
              title: 'Surgio un problema intente mas tarde!',
              showConfirmButton: true});

           }
          
        }
      });


   }
 }else
 {
  Swal.fire({
              type: 'error',
              title: 'Selecione una entidad!',
              showConfirmButton: true});
 }

 }

 function buscar_empresa_ruc()
 {
  var ruc = $('#ruc_empresa').val();
      $.ajax({
        data:  {ruc:ruc},
        url:   '../controlador/empresa/niveles_seguriC.php?buscar_ruc=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () { 
          $('#myModal_espera').modal('show'); 
          $('#list_empre').html('<tr class="text-center"><td colspan="6"> No encontrado... </td></tr>');
          $('#txt_enti').val('');
        },
        success:  function (response) { 
          if(response == -1)
           {
            Swal.fire({
                //position: 'top-end',
                type: 'info',
                title: 'RUC no encontrado!',
                showConfirmButton: true
                //timer: 2500
                });
              $('#myModal_espera').modal('hide'); 
            
           }else
           {

            // $('#txt_enti').val(response.entidad[0]['Nombre_Entidad']);
            var empresa = '';
            console.log(response);
            $.each(response, function(i,item){
              if(i==0)
              {
             empresa+="<tr><td><input type='radio' name='radio_usar' value='"+item.ID_Empresa+"-"+item.Entidad+"-"+item.Item+"' checked></td><td>"+item.emp+"</td><td>"+item.Item+"</td><td>"+item.ruc+"</td><td>"+item.Estado+"</td><td><i><b><u>"+item.Entidad+"</u></b></i></td><td><i><b><u>"+item.Ruc_en+"</u></b></i></td></tr>";
              }else
              {
                 empresa+="<tr><td><input type='radio' name='radio_usar' value='"+item.ID_Empresa+"-"+item.Entidad+"-"+item.Item+"'></td><td>"+item.emp+"</td><td>"+item.Item+"</td><td>"+item.ruc+"</td><td>"+item.Estado+"</td><td><i><b><u>"+item.Entidad+"</u></b></i></td><td><i><b><u>"+item.Ruc_en+"</u></b></i></td></tr>";

              }
            });

           $('#list_empre').html(empresa);
             $('#myModal_espera').modal('hide'); 
           }
        }
      });

}

function marcar_all(item)
{
  
  var parametros = 
  {
    'item':item,
    'modulo':'',
    'entidad':$('#ddl_entidad').val(),
    'usuario':$('#ddl_usuarios').val(),
    'check':$('#rbl_'+item+'_T').prop('checked'),
  }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/empresa/niveles_seguriC.php?acceso_todos=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () { 
          $('#myModal_espera').modal('show'); 
        },
        success:  function (response) { 
          if(response == 1)
           {  
             usuario_empresa();
            $('#myModal_espera').modal('hide');             
           }
        }
      });

    if($('#rbl_'+item+'_T').prop('checked')==false)
    {
      var id = $('#ddl_usuarios').val();
      var texto = $('select[name="ddl_usuarios"] option:selected').text();
      cargar_empresas();
      $('#ddl_usuarios').append($('<option>',{value: id, text:texto,selected: true }));
      usuario_empresa();
    }
  }
 

function marcar_acceso(item,modulo)
{
  if($('#ddl_usuarios').val()=='' || $('#ddl_usuarios').val()=='.' || $('#ddl_usuarios').val()==null )
{
  Swal.fire('Seleccione un usuario','','info');
  return false;
}
console.log($('#ddl_usuarios').val());
  var parametros = 
  {
    'item':item,
    'modulo':modulo,
    'entidad':$('#ddl_entidad').val(),
    'usuario':$('#ddl_usuarios').val(),
    'check':$('#rbl_'+modulo+'_'+item).prop('checked'),
  }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/empresa/niveles_seguriC.php?acceso_todos=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () { 
          // $('#myModal_espera').modal('show'); 
        },
        success:  function (response) { 
          if(response == 1)
           {
              usuario_empresa();
              // $('#myModal_espera').modal('hide');      
            
           }
        }
      }); 
}


function marcar_acceso_todos(modulo)
{

  if($('#ddl_entidad').val()==null || $('#ddl_entidad').val()=='' || $('#ddl_entidad').val()=='.')
{
  Swal.fire('Seleccione un Entidad','','info');
  $('#rbl_'+modulo).prop('checked',false);
  return false;
}
  if($('#ddl_usuarios').val()=='' || $('#ddl_usuarios').val()=='.' || $('#ddl_usuarios').val()==null || $('#ddl_usuarios').val()=='0')
{
  Swal.fire('Seleccione un usuario valido','','info');
  $('#rbl_'+modulo).prop('checked',false);
  return false;
}
// console.log($('#ddl_usuarios').val());

if($('#rbl_'+modulo).prop('checked'))
{
  $.each(listado_empresas,function(i,item){
    $('#rbl_'+modulo+'_'+item.id).prop('checked',true);
     listado_empresas_modificados.push(item.id);
  })
}else
{
   $.each(listado_empresas,function(i,item){
    $('#rbl_'+modulo+'_'+item.id).prop('checked',false);
  })
}

/*return false;
  $('#myModal_espera').modal('show');
var entidad = $('#ddl_entidad').val();
  var parametros = 
  {
    'entidad':entidad,
    'modulo':modulo,
    'entidad':$('#ddl_entidad').val(),
    'usuario':$('#ddl_usuarios').val(),
    'check':$('#rbl_'+modulo).prop('checked'),
  }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/empresa/niveles_seguriC.php?acceso_todos_empresa=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () { 
          // $('#myModal_espera').modal('show'); 
        },
        success:  function (response) { 
          if(response == 1)
           {
              usuario_empresa();
              buscar_permisos();
              $('#myModal_espera').modal('hide');      
            
           }
        }
      }); */
}


function activo(id)
{
  var emp = $('#txt_empresas').val();
  emp = emp.slice(0,-1).split(',');
  if(emp.length ==1)
  {
   $('#txt_modal_conten').val(id);
  }
}


function confirmar_email()
{
  var email  = $('#txt_email').val();
  if(email == '.' || email =='')
  {
      Swal.fire('Campo de email vacio','','info');
    return false;
  }
  var parametros = 
  {
    'nick':$('#txt_usuario').val(),
    'clave':$('#txt_pass').val(),
    'email':email,
    'entidad':$('select[name="ddl_entidad"] option:selected').text(),
    'ruc':$('#ddl_entidad').val(),
    'usuario':$('select[name="ddl_usuarios"] option:selected').text(), 
    'CI_usuario':$('#ddl_usuarios').val(),
  }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/empresa/niveles_seguriC.php?confirmar_enviar_email=true',
        type:  'post',
        dataType: 'json',
        // beforeSend: function () { 
        //   $('#myModal_espera').modal('show'); 
        // },
        success:  function (response) { 
          $('#mymodal_email').modal('show');
          $('#div_email').html(response);
          console.log(response);
         
           // $('#myModal_espera').modal('hide');
        }
      }); 

}

function enviar_email()
{

  var email  = $('#txt_email').val();
  if(email == '.' || email =='')
  {
      Swal.fire('Campo de email vacio','','info');
    return false;
  }
  var parametros = 
  {
    'nick':$('#txt_usuario').val(),
    'clave':$('#txt_pass').val(),
    'email':email,
    'entidad':$('select[name="ddl_entidad"] option:selected').text(),
    'ruc':$('#ddl_entidad').val(),
    'usuario':$('select[name="ddl_usuarios"] option:selected').text(), 
    'CI_usuario':$('#ddl_usuarios').val(),
  }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/empresa/niveles_seguriC.php?enviar_email=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () { 
          $('#myModal_espera').modal('show'); 
        },
        success:  function (response) { 
          console.log(response);
          if(response == 1)
           {
             Swal.fire('Email enviado,Se guardara el correo','','success');
             guardar();
            
           }else
           {
             Swal.fire('No se pudo enviar el correo','asegurese que el correo o las credensiales SMTP sean correctos','error');
           }

           $('#myModal_espera').modal('hide');
           $('#mymodal_email').modal('hide');
        }
      }); 
}

function enviar_email_masivo()
{
  var email  = $('#txt_email').val();
  var parametros = 
  {
    'nick':$('#txt_usuario').val(),
    'clave':$('#txt_pass').val(),
    'email':email,
    'entidad':$('select[name="ddl_entidad"] option:selected').text(),
    'ruc':$('#ddl_entidad').val(),
    'usuario':$('select[name="ddl_usuarios"] option:selected').text(), 
    'CI_usuario':$('#ddl_usuarios').val(),
  }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/empresa/niveles_seguriC.php?enviar_email_masivo=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () { 
          $('#myModal_espera').modal('show'); 
        },
        success:  function (response) { 
          console.log(response);
          if(response==1)
          {
            Swal.fire('Email enviado,Se guardara el correo','','success');
          }else if(response==2)
          {
            Swal.fire('Puede ser que algunos usuarios no hayan recibido sus credenciales','info');
          }
          $('#myModal_espera').modal('hide');
        }
    }); 
}


function DoubleScroll(element) {
    var scrollbar = document.createElement('div');
    scrollbar.appendChild(document.createElement('div'));
    scrollbar.style.overflow = 'auto';
    scrollbar.style.overflowY = 'hidden';
    scrollbar.firstChild.style.width = element.scrollWidth+'px';
    scrollbar.firstChild.style.paddingTop = '1px';
    scrollbar.firstChild.appendChild(document.createTextNode('\xA0'));
    scrollbar.onscroll = function() {
        element.scrollLeft = scrollbar.scrollLeft;
    };
    element.onscroll = function() {
        scrollbar.scrollLeft = element.scrollLeft;
    };
    element.parentNode.insertBefore(scrollbar, element);
}


function acceso_pagina(entidad,item)
{
  var usu = $('#ddl_usuarios').val();
  console.log(usu);
  if(usu=='' || usu==null)
  {
    Swal.fire('Seleccione un usuario','','info');
    return false;
  }
  var parametros = 
  {
    'entidad':entidad,
    'item':item,
    'usuario':usu,
  }
  $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/empresa/niveles_seguriC.php?paginas_acceso=true',
    type:  'post',
    dataType: 'json',       
    success:  function (response) { 
      console.log(response);
      $('#panel_paginas').html(response);          
      $('#mymodal_acceso_pag').modal('show');
    }
    }); 
}


function showemision()
{
   console.log(listado_empresas);
   console.log(listado_empresas_modificados);
   if($('#rbl_emision').prop('checked'))
   {
      $('#pnl_punto_emision').css('display','block');
   }else
   {
      $('#pnl_punto_emision').css('display','none');    
   }
}

function habilitarEdit()
{
  $('#btn_edit').css('display','initial');
}

function nuevoUsuario()
{
  limpiar();
  $('#ddl_usuarios').empty();
  $('#btn_edit').css('display','none');
  $('#pnl_punto_emision').css('display','none');
  $('#pnl_punto_emision_check').css('display','none');
}
function EditUsuario()
{
  $('#pnl_punto_emision_check').css('display','block');
}

function buscarPuntoVenta(item)
{
  // $('#myModal_espera').modal('show');
  var id = $('#txt_id_usu').val();
  var enti =$('#ddl_entidad').val();
  var parametros = 
  {
    'entidad':enti,
    'item':item,
    'idUsu':id,
    'ci':$('#txt_ced').val(),
  }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/empresa/niveles_seguriC.php?buscarPuntoVenta=true',
      type:  'post',
      dataType: 'json',       
      success:  function (response) { 
        $('#myModal_espera').modal('hide');
        console.log(response);
         //SRI

          $('#txt_id_CatLin').val(response.id);
          $('#txt_estab').val(response.estab);
          $('#txt_emision').val(response.punto);
          $('#txt_email2').val(response.correo);
          $('#txt_direccion').val(response.direccion);
          $('#txt_telefono').val(response.telefono);
          $('#txt_logo').val(response.logo);

      }
    }); 

}