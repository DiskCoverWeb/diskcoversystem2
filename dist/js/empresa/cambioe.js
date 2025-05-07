var arrNuevasLineas = [];
var lineaValAnteriores = {};
var lineaValNuevos = {};
$(document).ready(function () 
{
  $('[data-bs-toggle="tooltip"]').tooltip();
  $('#btnLineasGrabar').parent().on({
    mouseenter: function() {
        // Forzar mostrar tooltip
        $('#btnLineasGrabar').tooltip('show');
    },
    mouseleave: function() {
        // Ocultar tooltip
        $('#btnLineasGrabar').tooltip('hide');
    }
  });
  ddl_estados();
  ddl_naciones();
  $('#ciudad').select2();
  autocmpletar_entidad(); 

   $('#entidad').on('select2:select', function (e) {
       // console.log(e);
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
 
  // console.log(data);
});	

//Funcionalidades Lineas CxC

   $('#file_firma').on('change', function() {

       dato = this.files[0].name;
       $('#TxtEXTP12').val(dato);

   })

$('#MBoxCta_Anio_Anterior').keyup(function(e){ 
    if(e.keyCode != 46 && e.keyCode !=8)
    {
        validar_cuenta(this);
    }
})

$('#MBoxCta').keyup(function(e){ 
    if(e.keyCode != 46 && e.keyCode !=8)
    {
        validar_cuenta(this);
    }
})
$('#tree1').css('height','300px');
$('#tree1').css('overflow-y','scroll');


   // $('#empresas').on('select2:select', function (e) {
   // 	  var data = e.params.data.data;
   // 	  console.log(data);
   // })

document.addEventListener('keydown', (event)=>{
    if(event.key == 'Delete'){
        confirmar_eliminar();
    }
})
});

function ddl_estados()
{
 $.ajax({
  url: '../controlador/empresa/cambioeC.php?ddl_estados=true',
  type:'post',
  dataType:'json',
 // data:{:},     
  success: function(response){

      $('#Estado').html(response);
 
  // console.log(response);
}
});

}


function cargar_imgs()
{
  $.ajax({
  url: '../controlador/empresa/cambioeC.php?cargar_imgs=true',
  type:'post',
  dataType:'json',
 // data:{:},     
  success: function(response){

  $('#ddl_img').html(response);
  // console.log(response);
}
});
}

function ddl_naciones()
{
 $.ajax({
  url: '../controlador/empresa/cambioeC.php?ddl_nacionalidades=true',
  type:'post',
  dataType:'json',
 // data:{:},     
  success: function(response){      
      // console.log(response);
      var opNaciones = '<option value="">Seleccione Pais</option>';
      response.forEach(function(item,i){
          opNaciones+='<option value="'+item.Codigo+'">'+item.Descripcion_Rubro+'</option>';
      })
      $('#ddl_naciones').html(opNaciones);
 
     }
});

}


function subir_img()
{
 var fileInput = $('#file_img').get(0).files[0];    
  if(fileInput=='')
  {
    Swal.fire('','Seleccione una imagen','warning');
    return false;
  }
  $('#myModal_espera').modal('show');
  var formData = new FormData(document.getElementById("form_empresa"));
  formData.append('entidad', $('#entidad').val());
  formData.append('ciudad', $('#ciudad').val());
  formData.append('empresas', $('#empresas').val());
  formData.append('ci_ruc', $('#ci_ruc').val());

     $.ajax({
        url: '../controlador/empresa/cambioeC.php?cargar_imagen=true',
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        dataType:'json',
        success: function(response) {
           if(response==-1)
           {
             Swal.fire(
              '',
              'Algo extraño a pasado intente mas tarde.',
              'error')

           }else if(response ==-2)
           {
              Swal.fire(
              '',
              'Asegurese que el archivo subido sea una imagen.',
              'error')
           }else if(response==-3)
           {
                Swal.fire(
              'El nombre del logo es muy extenso',
              '',
              'error');

           }else
           {
               datos_empresa();
           } 
           setTimeout(()=>{
            $('#myModal_espera').modal('hide');
        }, 2000);
        }
    });

}

function subir_firma()
{
 var fileInput = $('#file_firma').get(0).files[0];    
  if(fileInput=='')
  {
    Swal.fire('','Seleccione una imagen','warning');
    return false;
  }
  // $('#myModal_espera').modal('show');
  var formData = new FormData(document.getElementById("form_empresa"));
  formData.append('entidad', $('#entidad').val());
  formData.append('ciudad', $('#ciudad').val());
  formData.append('empresas', $('#empresas').val());
  formData.append('ci_ruc', $('#ci_ruc').val());

     $.ajax({
        url: '../controlador/empresa/cambioeC.php?cargar_firma=true',
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        dataType:'json',
        success: function(response) {

              // $('#myModal_espera').modal('hide');
           if(response==-1)
           {
             Swal.fire(
              '',
              'Algo extraño a pasado intente mas tarde.',
              'error')

           }else if(response ==-2)
           {
              Swal.fire(
              '',
              'Asegurese que el archivo subido sea certificado (.p12) valido')
           }
        }
    });

}

// async function cargar_tb2()
// {
// 	$('#myModal_espera').modal('show');
// 	  	await datos_empresa();
// 	  	setTimeout(setear_Tab2, 2000);	  
// 	$('#myModal_espera').modal('hide');	
// }
// async function cargar_tb3()
// {
// 	$('#myModal_espera').modal('show');
// 	  	await datos_empresa();
// 	  	setTimeout(setear_Tab3, 2000);	  
// 	$('#myModal_espera').modal('hide');	
// }
// function setear_Tab2()
// {
// 		$(".active").removeClass("active");
// 	    $('.nav-tabs li').find('a[href="#tab_2"]').parent('li').addClass('active'); 
// 	    $('#tab_2').addClass("active");
// }
// function setear_Tab3()
// {
// 		$(".active").removeClass("active");
// 	    $('.nav-tabs li').find('a[href="#tab_3"]').parent('li').addClass('active'); 
// 	    $('#tab_3').addClass("active");
// }

function provincias(pais,callback)
{
var option ="<option value=''>Seleccione Provincia</option>"; 
 $.ajax({
  url: '../controlador/empresa/cambioeC.php?provincias=true',
  type:'post',
  dataType:'json',
 data:{pais:pais},
  beforeSend: function () {
               $("#ddl_ciudad").html("<option value=''>Seleccione provincia</option>");
         },
  success: function(response){
  response.forEach(function(data,index){
    option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
  });
    $('#prov').html(option); 
     if (callback && typeof callback === 'function') {
        callback();
      }
  // console.log(response);
}
});
}


function subdireccion()
{
var txtsubdi = $('#TxtSubdir').val();
$.ajax
({
    data:  {txtsubdi:txtsubdi},
    url:   '../controlador/empresa/crear_empresaC.php?subdireccion=true',
    type:  'post',
    dataType: 'json',
    success:  function (response) 
    { 
        if(response == null)
        {
            Swal.fire('Este directorio ya existe seleccione otro','','error');
            $('#TxtSubdir').val('');
        }
        else{
            // console.log(response);
        $('#TxtSubdir').val(response);
        }
    }
});
}

function MostrarUsuClave() 
{
if($('#AsigUsuClave').prop('checked'))
{
    $('#TxtUsuario').css('display','block');
    $('#lblUsuario').css('display','block');
    $('#TxtClave').css('display','block');
    $('#lblClave').css('display','block');
    TraerUsuClave();
}else
{
    $('#TxtUsuario').css('display','none');
    $('#lblUsuario').css('display','none');
    $('#TxtClave').css('display','none');
    $('#lblClave').css('display','none');
}
}
function TraerUsuClave()
{
var form = $('#TxtCI').val();
    $.ajax({
        data:{form:form},//son los datos que se van a enviar por $_POST
        url: '../controlador/empresa/crear_empresaC.php?traer_usuario=true',//los datos hacia donde se van a enviar el envio por url es por GET
        type:'post',//envio por post
        dataType:'json',
        success: function(response){
            // console.log(response);
            $('#TxtUsuario').val(response[0]['Usuario']);
            $('#TxtClave').val(response[0]['Clave']);
        }
    });
}

function autocompletarCempresa(){
    $('#ListaCopiaEmpresa').select2({
    placeholder: 'Seleccionar copia empresa',
    ajax: {
        url: '../controlador/empresa/crear_empresaC.php?Copiarempresas=true',
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

function ciudad_l(idpro,callback)
{
    // console.log(idpro);
    var option ="<option value=''>Seleccione Ciudad</option>"; 
    if(idpro !='')
    {
       $.ajax({
          url: '../controlador/empresa/cambioeC.php?ciudad2=true',
          type:'post',
          dataType:'json',
          data:{idpro:idpro},
          success: function(response){
            response.forEach(function(data,index){
                option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
            });
            $('#ddl_ciudad').html(option);
             if (callback && typeof callback === 'function') {
                callback();
              }
        }
      });
     } 

}


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
//$('#ciudad').val('');
$('#empresas').html('<option value="">Seleccione Empresa</option>');
$('#span_item_empresa').text('.');
$('#lbl_item').text('.');
$('#span_id_linea').text('.');
$('#form_empresa')[0].reset();
//document.querySelector('#form_empresa:not(#entidad select)').reset();
$('#tree1').html('');
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
$('#empresas').html('<option value="">Seleccione Empresa</option>');
$('#span_item_empresa').text('.');
$('#lbl_item').text('.');
$('#span_id_linea').text('.');
$('#form_empresa')[0].reset();
$('#tree1').html('');
 var ciu = $('#ciudad').val();
 var ent = $('#entidad').val();
$('#empresas').select2({
  placeholder: 'Seleccione una Empresa',
  ajax: {
    url: '../controlador/empresa/cambioeC.php?empresas=true&ciu='+ciu+'&ent='+ent,
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


function cambiarEmpresa()
{
let empresas = $('#empresas').val();

if(empresas == ''){
    Swal.fire('Seleccione una Empresa', '', 'error');
    return;
}

$('#myModal_espera').modal('show');
var parametros = $('#form_encabezados').serialize() + "&" + $('#form_empresa').find(':not(#tab_5 input, #tab_5 select)').serialize();
var parametros = parametros+'&ciu='+$('#ddl_ciudad option:selected').text();
$.ajax({
    type: "POST",
     url: '../controlador/empresa/cambioeC.php?editar_datos_empresa=true',
    data:parametros,
    dataType:'json',
    success: function(data)
    {	
        setTimeout(()=>{
            $('#myModal_espera').modal('hide');
        }, 2000);

        if($('#file_firma').val()!='')
        {
            subir_firma();
        }
        if(data==1)
        {
            Swal.fire('Empresa modificada con exito ','','success').then(function(){
                 datos_empresa();
            });

        }else
        {
            Swal.fire('Intente mas tarde '+data,'','error');
        }

    }
});
}

function emasivo()
{
$('#myModalCorreo').modal('show');

/*
var parametros = $('#form_empresa').find(':not(#tab_5 input, #tab_5 select)').serialize();
$.ajax({
    type: "POST",
     url: '../controlador/empresa/cambioeC.php?mensaje_masivo=true',
    data: parametros,
    dataType:'json',
    success: function(data)
    {
        if(data==1)
        {
            Swal.fire('Mensaje modificado en las entidades con exito ','','success');
        }else
        {
            Swal.fire('Intente mas tarde','','error');
        }
        
    }
});

*/
}

function mmasivo()
{
var parametros = $('#form_encabezados').serialize() + "&" + $('#form_empresa').find(':not(#tab_5 input, #tab_5 select)').serialize();
$.ajax({
    type: "POST",
     url: '../controlador/empresa/cambioeC.php?mensaje_masivo=true',
    data: parametros,
    dataType:'json',
    success: function(data)
    {
        if(data==1)
        {
            Swal.fire('Mensaje modificado en las entidades con exito ','','success');
        }else
        {
            Swal.fire('Intente mas tarde','','error');
        }
        
    }
});
}
function mgrupo()
{
var parametros = $('#form_encabezados').serialize() + "&" + $('#form_empresa').find(':not(#tab_5 input, #tab_5 select)').serialize();
$.ajax({
    type: "POST",
     url: '../controlador/empresa/cambioeC.php?mensaje_grupo=true',
    data: parametros,
    dataType:'json',
    success: function(data)
    {
        if(data==1)
        {
            Swal.fire('Mensaje modificado en las entidades con exito ','','success');
        }else
        {
            Swal.fire('Intente mas tarde','','error');
        }
        
    }
});

}
function mindividual()
{
var parametros = $('#form_encabezados').serialize() + "&" + $('#form_empresa').find(':not(#tab_5 input, #tab_5 select)').serialize();
$.ajax({
    type: "POST",
     url: '../controlador/empresa/cambioeC.php?mensaje_indi=true',
    data: parametros,
    dataType:'json',
    success: function(data)
    {
        if(data==1)
        {
            Swal.fire('Mensaje a entidad modificado con exito ','','success');
        }else
        {
            Swal.fire('Intente mas tarde','','error');
        }
        
    }
});
}

function cambiarEmpresaMa()
{
    let empresas = $('#empresas').val();

    if(empresas == ''){
        Swal.fire('Seleccione una Empresa', '', 'error');
        return;
    }

    $('#myModal_espera').modal('show');
    var parametros = $('#form_encabezados').serialize() + "&" + $('#form_empresa').find(':not(#tab_5 input, #tab_5 select)').serialize();
    $.ajax({
        type: "POST",
        url: '../controlador/empresa/cambioeC.php?guardar_masivo=true',
        data: parametros,
        dataType:'json',

        success: function(data)
        {
            if($('#file_firma').val()!='')
            {
                subir_firma();
            }
            if(data==1)
            {
                Swal.fire('Entidad modificada con exito.','','success');
            }else
            {
                Swal.fire('Intente mas tarde','','error');
            }	
            setTimeout(()=>{
                $('#myModal_espera').modal('hide');
            }, 2000);
        }
    });
}

function cambiarEmpresaMaFechaComElec()
{

    let empresas = $('#empresas').val();

    if(empresas == ''){
        Swal.fire('Seleccione una Empresa', '', 'error');
        return;
    }

    $('#myModal_espera').modal('show');
    var parametros = $('#form_encabezados').serialize() + "&" + $('#form_empresa').find(':not(#tab_5 input, #tab_5 select)').serialize();
    $.ajax({
        type: "POST",
         url: '../controlador/empresa/cambioeC.php?guardar_masivoFechaCompElec=true',
        data: parametros,
        dataType:'json',

        success: function(data)
        {
            if(data==1)
            {
                Swal.fire('Entidad modificada con exito.','','success');
            }else
            {
                Swal.fire('Intente mas tarde','','error');
            }   

            $('#myModal_espera').modal('hide');     
        }
    });

}

function mostrarEmpresa()
{
$('#reporte_exc').css('display','initial');
$('#form_empresa').css('display','none');
$('#form_encabezados').css('display','none');
$('#form_vencimiento').css('display','initial');
}
function cerrarEmpresa()
{
$('#reporte_exc').css('display','none');
$('#form_empresa').css('display','initial');
$('#form_encabezados').css('display','initial');
$('#form_vencimiento').css('display','none');
}

function consultar_datos(reporte = null)
{
    let desde= $('#desde').val();
    let hasta= $('#hasta').val();
    ///alert(desde.value+' '+hasta.value);
    var parametros =
    {
        'desde':desde,
        'hasta':hasta,
        'repor': reporte,			
    }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/contabilidad/contabilidad_controller.php?consultar=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () {	
            $('#myModal_espera').modal('show');
        },
        success:  function (response) {
                
                $('#tbl_vencimiento').DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                    paging: false,
                    searching: false, 
                    data: ProcesarDatos(response),
                    columns: [
                        {data: 'tipo'},
                        {data: 'Item'},
                        {data: 'Empresa'},
                        {data: 'Fecha'},
                    ],
                    destroy: true,
                    createdRow: function(row, data) {
                        alignEnd(row, data);
                    }
                });
                setTimeout(()=>{
                    $('#myModal_espera').modal('hide');
                }, 2000);
        }
    });
//document.getElementById('desde').value=desde;
// document.getElementById('hasta').value=hasta;
}

function reporte()
{
let desde= $('#desde').val();
let hasta= $('#hasta').val();
var tit = 'Reporte de vencimiento';
var url = ' ../controlador/contabilidad/contabilidad_controller.php?consultar_reporte=true&desde='+desde+'&hasta='+hasta+'&repor=2';
window.open(url,'_blank');
}

function asignar_clave()
{
if($('#entidad').val()==''){Swal.fire('Seleccione una entidad','','info');return false;}
// if($('#ciudad').val()==''){Swal.fire('Seleccione una Ciudad','','info');return false;}
if($('#empresas').val()==''){Swal.fire('Seleccione una empresa','','info');return false;}
var parametros = $('#form_encabezados').serialize() + "&" + $('#form_empresa').find(':not(#tab_5 input, #tab_5 select)').serialize();
$.ajax({
    type: "POST",
     url: '../controlador/empresa/cambioeC.php?asignar_clave=true',
    data: parametros,
    dataType:'json',
    beforeSend: function () {	
         $('#myModal_espera').modal('show');
    },
    success: function(data)
    {

        setTimeout(()=>{
            $('#myModal_espera').modal('hide');
        }, 2000);
        if(data==1)
        {
            Swal.fire('Credenciales de comprobantes electronicos Asignados.','','success');
        }else
        {
            Swal.fire('Intente mas tarde','','error');
        }		

    }
});
}

function AmbientePrueba()
{
$('#TxtWebSRIre').val('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl');
$('#TxtWebSRIau').val('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl');
}
function AmbienteProduccion()
{
$('#TxtWebSRIre').val('https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl');
$('#TxtWebSRIau').val('https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl');
}

function cargar_img()
{
var img  = $('#ddl_img').val();
// console.log(img)
$('#img_logo').prop('src','../../img/logotipos/'+img)
}


async function datos_empresa()
{
  $('#myModal_espera').modal('show');
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
        //console.log(data);
        empresa = data.empresa1[0];
        empresa2 = '';
        contribuyente = '';
        if(data.empresa2.length>0)
        {
            empresa2 = data.empresa2[0];
        }
        if(data.tipoContribuyente.length>0)
        {
            contribuyente = data.tipoContribuyente[0];
        }
        

        //console.log(data.empresa2);
        //console.log(contribuyente);
         limpiar_tabs();
        $('#datos_empresa').html(data.datos);
        $('#ci_ruc').val(data.ci);

        // -------------------tab1--------------------

        $('#Estado').val(empresa.Estado);
        $('#FechaR').val(empresa.Fecha);
        $('#FechaCE').val(empresa.Fecha_CE);
        $('#FechaDB').val(empresa.Fecha_DB);
        $('#FechaP12').val(empresa.Fecha_P12);
        $('#ci_ruc').val(empresa.RUC_CI_NIC);

        $('#Servidor').val(empresa.IP_VPN_RUTA);
        $('#Base').val(empresa.Base_Datos);
        $('#Usuario').val(empresa.Usuario_DB);
        $('#Clave').val(empresa.Contrasena_DB);
        $('#Motor').val(empresa.Tipo_Base);
        $('#Puerto').val(empresa.Puerto);
        $('#Plan').val(empresa.Tipo_Plan);
        $('#Mensaje').val(empresa.Mensaje);

        //----------------fin tab 1---------------------

        setTimeout(()=>{
            $('#myModal_espera').modal('hide');
        }, 2000);

        if (empresa2 == '') 
        {						
            $('#txt_sqlserver').val(0);
            Swal.fire('Esta empresa no tiene una configuracion SQL server','','warning');
            $('#li_tab1').addClass('active');
            $('#tab_1').addClass('active');	

            $('#li_tab2').css('display','none');
            $('#tab_2').removeClass('active');	
            $('#li_tab3').css('display','none');
            $('#tab_3').removeClass('active');
            $('#li_tab5').css('display','none');
            $('#tab_5').removeClass('active');
            return false

        }else
        {

            $('#txt_sqlserver').val(1);
            $('#li_tab2').css('display','initial');
            $('#li_tab2').removeClass('active');	
            $('#li_tab3').css('display','initial');
            $('#li_tab3').removeClass('active');	
            $('#li_tab5').css('display','initial');
            $('#li_tab5').removeClass('active');	
        }


        //----------------- tab 2 ----------------------
        $('#TxtEmpresa').val(empresa2.Empresa);
        $('#lbl_item').text(empresa2.Item);
        $('#TxtRazonSocial').val(empresa2.Razon_Social);
        $('#TxtNomComercial').val(empresa2.Nombre_Comercial);
        $('#TxtRuc').val(empresa2.RUC);
        $('#ddl_obli').val(empresa2.Obligado_Conta);
        $('#TxtRepresentanteLegal').val(empresa2.Gerente);
        $('#TxtCI').val(empresa2.CI_Representante);

        $('#ddl_naciones').val(empresa2.CPais);			
        provincias(empresa2.CPais,function () {
            $('#prov').val(empresa2.CProv);
        });

        var numero = parseFloat(empresa2.Ciudad);
        if (!isNaN(numero)) {
           ciudad_l(empresa2.CProv,function(){
                $('#ddl_ciudad').val(empresa2.Ciudad);
            })
        } else {		
            ciudad_l(empresa2.CProv,function(){
                $('#ddl_ciudad').val(21701);
            })	    
        }
        
        $('#TxtDirMatriz').val(empresa2.Direccion);
        $('#TxtEsta').val(empresa2.Establecimientos);
        $('#TxtTelefono').val(empresa2.Telefono1);
        $('#TxtTelefono2').val(empresa2.Telefono2);
        $('#TxtFax').val(empresa2.FAX);
        $('#TxtMoneda').val('USD');
        $('#TxtNPatro').val(empresa2.No_Patronal);
        $('#TxtCodBanco').val(empresa2.CodBanco);
        $('#TxtTipoCar').val(empresa2.Tipo_Carga_Banco);
        $('#TxtAbrevi').val(empresa2.Abreviatura);
        $('#TxtEmailEmpre').val(empresa2.Email);
        $('#TxtEmailConta').val(empresa2.Email_Contabilidad);
        $('#TxtEmailRespa').val(empresa2.Email_Respaldos);
        $('#TxtSegDes1').val(empresa2.Seguro);
        $('#TxtSegDes2').val(empresa2.Seguro2);
        $('#TxtSubdir').val(empresa2.SubDir);
        $('#TxtNombConta').val(empresa2.Contador);
        $('#TxtRucConta').val(empresa2.RUC_Contador);
        //------------- fin tab 2 ------------------

        //---------------- tab 3 -------------------
        cargar_imgs();			
        autocompletarCempresa();
        $('#ASDAS').prop('checked', false);
        $('#MFNV').prop('checked', false);
        $('#MPVP').prop('checked', false);
        $('#IRCF').prop('checked', false);
        $('#IMR').prop('checked', false);
        $('#IRIP').prop('checked', false);
        $('#PDAC').prop('checked', false);
        $('#RIAC').prop('checked', false);

        if(empresa2.Det_SubMod==1){
            $('#ASDAS').prop('checked', true);
        }
        if(empresa2.Mod_Fact==1){
            $('#MFNV').prop('checked', true);
        }
        if(empresa2.Mod_PVP==1){
            $('#MPVP').prop('checked', true);
        }
        if(empresa2.Imp_Recibo_Caja==1){
            $('#IRCF').prop('checked', true);
        }			
        if(empresa2.Medio_Rol==1){
            $('#IMR').prop('checked', true);
        }
        if(empresa2.Rol_2_Pagina==1){
            $('#IRIP').prop('checked', true);
        }
        if(empresa2.Det_Comp==1){
            $('#PDAC').prop('checked', true);
        }
        if(empresa2.Registrar_IVA==1){
            $('#RIAC').prop('checked', true);
        }

        if(empresa2.Logo_Tipo_url!='' && empresa2.Logo_Tipo_url!='.')
        {
            $('#img_logo').prop('src',empresa2.Logo_Tipo_url);
        }
        $('#img_foto_name').text(empresa2.Logo_Tipo);
        if(empresa2.Num_CD==1){ $('#DM').prop('checked', true); }else{ $('#DS').prop('checked', true); }

        if(empresa2.Num_CI==1){ $('#IM').prop('checked', true); }else{ $('#IS').prop('checked', true); }

        if(empresa2.Num_CE==1){ $('#EM').prop('checked', true); }else{ $('#ES').prop('checked', true); }

        if(empresa2.Num_ND==1){ $('#NDM').prop('checked', true); }else{ $('#NDS').prop('checked', true); }

        if(empresa2.Num_NC==1){ $('#NCM').prop('checked', true); }else{ $('#NCS').prop('checked', true); }

        $('#TxtServidorSMTP').val(empresa2.smtp_Servidor);

         if(empresa2.smtp_UseAuntentificacion==1){ $('#Autenti').prop('checked', true); }else{ $('#Autenti').prop('checked', false);}

        if(empresa2.smtp_SSL==1){ $('#SSL').prop('checked', true); }else{  $('#SSL').prop('checked', false); }
        if(empresa2.smtp_Secure==1){ $('#Secure').prop('checked', true); }else{ $('#Secure').prop('checked', false); }
        $('#TxtPuerto').val(empresa2.smtp_Puerto);
        $('#TxtPVP').val(empresa2.Dec_PVP);
        $('#TxtCOSTOS').val(empresa2.Dec_Costo);
        $('#TxtIVA').val(empresa2.Dec_IVA);
        $('#TxtCantidad').val(empresa2.Dec_Cant);

        // console.log(contribuyente)

        if(contribuyente!='')
        {
            $('#TxtRucTipocontribuyente').val(contribuyente.RUC)
              $('#TxtZonaTipocontribuyente').val(contribuyente.Zona)
              $('#TxtAgentetipoContribuyente').val(contribuyente.Agente_Retencion);
          }

          $('#rbl_ContEs').prop('checked',false)
         $('#rbl_rimpeE').prop('checked',false)
         $('#rbl_rimpeP').prop('checked',false)
         $('#rbl_regGen').prop('checked',false)
         $('#rbl_rise').prop('checked',false)
         $('#rbl_micro2020').prop('checked',false)
         $('#rbl_micro2021').prop('checked',false)

          if(contribuyente.Contribuyente_Especial==1){
              $('#rbl_ContEs').prop('checked',true)
          }

          if(contribuyente.RIMPE_E==1){
             $('#rbl_rimpeE').prop('checked',true)
         }

         if(contribuyente.RIMPE_P==1){
         $('#rbl_rimpeP').prop('checked',true)
         }

         if(contribuyente.Regimen_General==1){
         $('#rbl_regGen').prop('checked',true)
         }

         if(contribuyente.RISE==1){
         $('#rbl_rise').prop('checked',true)
         }

         if(contribuyente.Micro_2020==1){
         $('#rbl_micro2020').prop('checked',true)
         }

         if(contribuyente.Micro_2021==1){
         $('#rbl_micro2021').prop('checked',true)
         }

     



        //---------------------------------fin tab3---------------------

        //-----------------------------tab4-----------------------------

        // console.log(empresa2.Ambiente)			
        if(empresa2.Ambiente=='1')
        {
            $('#optionsRadios1').prop('checked', true);
        }else
        {
            $('#optionsRadios2').prop('checked', true);	
            // console.log('prioduc')			
        }
        $('#TxtContriEspecial').val(empresa2.Codigo_Contribuyente_Especial);
        $('#TxtWebSRIre').val(empresa2.Web_SRI_Recepcion);
        $('#TxtWebSRIau').val(empresa2.Web_SRI_Autorizado);
        $('#TxtEXTP12').val(empresa2.Ruta_Certificado);
        $('#TxtContraExtP12').val(empresa2.Clave_Certificado);
        $('#TxtEmailGE').val(empresa2.Email_Conexion);
        $('#TxtContraEmailGE').val(empresa2.Email_Contraseña);
        $('#TxtEmaiElect').val(empresa2.Email_Conexion_CE);
        $('#TxtContraEmaiElect').val(empresa2.Email_Contraseña_CE);
        if(empresa2.Email_CE_Copia==1 && empresa2.Email_Procesos!=''){	$('#rbl_copia').prop('checked', true); }
        $('#TxtCopiaEmai').val(empresa2.Email_Procesos);
        $('#TxtRUCOpe').val(empresa2.RUC_Operadora);
        $('#txtLeyendaDocumen').val(empresa2.LeyendaFA);
        $('#txtLeyendaImpresora').val(empresa2.LeyendaFAT);

        //-----------------------------fin tab4-----------------------------

        
        //-----------------------------tab5-----------------------------
        $('#span_item_empresa').text(empresa.Item);
        $('#TxtLineasItem').val(empresa.Item);
        $('#TxtLineasEntidad').val(empresa.ID_Empresa);

        resetearTab5();

        TVcatalogo();
        //consultarCatalogoLinea();
        $('#btnLineasGrabar').removeAttr('disabled');

        //-----------------------------fin tab5-----------------------------
    },error: function (jqXHR, textStatus, errorThrown) {
        setTimeout(()=>{
            $('#myModal_espera').modal('hide');
        }, 2000);
      }

});
}

function consultarCatalogoLinea(){
let entidad = $('#TxtLineasEntidad').val();
let item = $('#TxtLineasItem').val();

if(!entidad){
    Swal.fire('Seleccione una Entidad', '', 'error');
    return;
}

if(!item){
    Swal.fire('Seleccione una Empresa', '', 'error');
    return;
}

$.ajax({
    type: "POST",
    url: '../controlador/empresa/cambioeC.php?consultar_lineas=true',
    data:{item,entidad},
    dataType:'json',
    success: function (data) {
        //console.log(data);
        // Insertar la lista en el elemento con id 'contenedor'
        /*document.getElementById("prueba_contenedor").appendChild(generarLista(data));
        $("#modal_prueba").modal('show');*/
        $('#tree1').html(data);
        /*$('#tree2').html(data);
        $("#modal_prueba").modal('show');*/
    },
    error: function(err){
        console.error(err);
    }
});

}

  function generarLista(obj) {
    const ul = document.createElement("ul");

    for (const clave in obj) {
        const li = document.createElement("li");

        if (Array.isArray(obj[clave])) {
            li.textContent = clave;
            const subUl = document.createElement("ul");

            obj[clave].forEach((item, index) => {
                const subLi = document.createElement("li");
                subLi.textContent = item.Concepto;
                subLi.appendChild(generarLista(item)); // Llamada recursiva para cada objeto en el array
                subUl.appendChild(subLi);
            });

            li.appendChild(subUl);
        } else if (typeof obj[clave] === "object" && obj[clave] !== null) {
            li.textContent = clave;
            li.appendChild(generarLista(obj[clave])); // Llamada recursiva para objetos anidados
        } else {
            li.textContent = `${clave}: ${obj[clave]}`;
        }

        ul.appendChild(li);
    }

    return ul;
}

function resetearTab5(){
$('#TxtIDLinea').val('')
$('#TextCodigo').val('.')
$('#TextLinea').val('NO PROCESABLE')
$('#MBoxCta').val('')
//$('#MBoxCta').attr('') //BUSCAR FORMATO_CUENTAS DEPENDIENDO DE LA BD
$('#MBoxCta_Anio_Anterior').val('')
$('#MBoxCta_Venta').val('')
$('#CTipo').html('<option value="FA">FA</option><option value="FR">FR</option><option value="NV">NV</option><option value="PV">PV</option><option value="FT">FT</option><option value="NC">NC</option><option value="LC">LC</option><option value="GR">GR</option><option value="CP">CP</option><option value="DO">DO</option><option value="NDO">NDO</option><option value="NDU">NDU</option>');
$('#TxtNumFact').val('00')
$('#TxtItems').val('0.00')
$('#TxtLogoFact').val('')
$('#TxtPosFact').val('0.00')
$('#TxtEspa').val('0.00')
$('#TxtPosY').val(0.00)
$('#TxtLargo').val('0.00')
$('#TxtAncho').val('0.00')

$('#MBFechaIni').val("<?php echo date('Y-m-d');?>")
$('#MBFechaVenc').val("<?php echo date('Y-m-d');?>")
$('#TxtNumSerietres1').val('000001')
$('#TxtNumAutor').val('0000000001')
$('#TxtNumSerieUno').val('001')
$('#TxtNumSerieDos').val('001')

$('#TxtNombreEstab').val('.')
$('#TxtDireccionEstab').val('.')
$('#TxtTelefonoEstab').val('.')
$('#TxtLogoTipoEstab').val('.')
    
$('#CheqPuntoEmision').prop('checked', false);
}


function limpiar_tabs()
{
    $('#TxtEmpresa').val('.');
    $('#lbl_item').text('');
    $('#TxtRazonSocial').val('.');
    $('#TxtNomComercial').val('.');
    $('#TxtRuc').val('.');
    $('#ddl_obli').val('');
    $('#TxtRepresentanteLegal').val('.');
    $('#TxtCI').val('.');

    $('#ddl_naciones').val('');			
    $('#prov').val('');
    $('#ddl_ciudad').val('');
    
    $('#TxtDirMatriz').val('.');
    $('#TxtEsta').val('.');
    $('#TxtTelefono').val('.');
    $('#TxtTelefono2').val('.');
    $('#TxtFax').val('.');
    $('#TxtMoneda').val('USD');
    $('#TxtNPatro').val('.');
    $('#TxtCodBanco').val('.');
    $('#TxtTipoCar').val('.');
    $('#TxtAbrevi').val('.');
    $('#TxtEmailEmpre').val('.');
    $('#TxtEmailConta').val('.');
    $('#TxtEmailRespa').val('.');
    $('#TxtSegDes1').val('.');
    $('#TxtSegDes2').val('.');
    $('#TxtSubdir').val('.');
    $('#TxtNombConta').val('.');
    $('#TxtRucConta').val('.');
    //------------- fin tab 2 ------------------

    //---------------- tab 3 -------------------
    $('#ASDAS').prop('checked', false);
    $('#MFNV').prop('checked', false);
    $('#MPVP').prop('checked', false);
    $('#IRCF').prop('checked', false);
    $('#IMR').prop('checked', false);
    $('#IRIP').prop('checked', false);
    $('#PDAC').prop('checked', false);
    $('#RIAC').prop('checked', false);
    $('#img_logo').prop('src','../../img/logotipos/'+empresa2.Logo_Tipo+'.png');

    
    $('#DM').prop('checked', false); 
    $('#DS').prop('checked', false);
    $('#IM').prop('checked', false); 
    $('#IS').prop('checked', false);
    $('#EM').prop('checked', false); 
    $('#ES').prop('checked', false);
    $('#NDM').prop('checked', false );
    $('#NDS').prop('checked', false);
    $('#NCM').prop('checked', false );
    $('#NCS').prop('checked', false);

    $('#TxtServidorSMTP').val('.');
    $('#Autenti').prop('checked', false);

    $('#SSL').prop('checked', false);
    $('#Secure').prop('checked', false); 
    $('#TxtPuerto').val('.');
    $('#TxtPVP').val('.');
    $('#TxtCOSTOS').val('.');
    $('#TxtIVA').val('.');
    $('#TxtCantidad').val('.');
    //---------------------------------fin tab3---------------------

    //-----------------------------tab4-----------------------------
    
    $('#optionsRadios1').prop('checked', true);
    
    $('#TxtContriEspecial').val('.');
    $('#TxtWebSRIre').val('.');
    $('#TxtWebSRIau').val('.');
    $('#TxtEXTP12').val('.');
    $('#TxtContraExtP12').val('.');
    $('#TxtEmailGE').val('.');
    $('#TxtContraEmailGE').val('.');
    $('#TxtEmaiElect').val('.');
    $('#TxtContraEmaiElect').val('.');
    $('#rbl_copia').prop('checked', true); 
    $('#TxtCopiaEmai').val('.');
    $('#TxtRUCOpe').val('.');
    $('#txtLeyendaDocumen').val('.');
    $('#txtLeyendaImpresora').val('.');
    $('#file_firma').val('');
}


function guardarTipoContribuyente()
{
  
 var parametros = 
 {
      'ruc':$('#TxtRucTipocontribuyente').val(),
      'zona':$('#TxtZonaTipocontribuyente').val(),
      'agente':$('#TxtAgentetipoContribuyente').val(),
      'op1': $('#rbl_ContEs').prop('checked'),
     'op2': $('#rbl_rimpeE').prop('checked'),
     'op3': $('#rbl_rimpeP').prop('checked'),
     'op4': $('#rbl_regGen').prop('checked'),
     'op5': $('#rbl_rise').prop('checked'),
     'op6': $('#rbl_micro2020').prop('checked'),
     'op7': $('#rbl_micro2021').prop('checked'),
 }
 $.ajax({
  url: '../controlador/empresa/cambioeC.php?guardarTipoContribuyente=true',
  type:'post',
  dataType:'json',
 data:{parametros:parametros},     
  success: function(response){
      if(response==1)
      {
          Swal.fire('Guardado','','success')
      }

  // console.log(response);
}
});
}

function TVcatalogo(nl='',cod='',auto='',serie='',fact='')
 {
     let entidad = $('#TxtLineasEntidad').val();
    let item = $('#TxtLineasItem').val();
    if(!entidad){
        Swal.fire('Seleccione una Entidad', '', 'error');
        return;
    }
    if(!item){
        Swal.fire('Seleccione una Empresa', '', 'error');
        return;
    }
    if(cod)
    {
        var ant = $('#txt_anterior').val();
        var che = cod.split('.').join('_');	
        if(ant==''){	$('#txt_anterior').val(che); }else{	$('#label_'+ant).css('border','0px');$('#label_'+ant).removeAttr('title');}
        $('#label_'+che+auto+serie+fact).css('border','1px solid');
        $('#label_'+che+auto+serie+fact).attr('title', 'Presione Suprimir para eliminar');
        $('#LblTreeClick').val(auto+'_'+serie+'_'+fact);
        $('#txt_anterior').val(che+auto+serie+fact); 
        //$('#txt_anterior').val(che+auto+serie+fact); 
    }
          //fin de pinta el seleccionado
    if(cod)
    {
        $('#txt_codigo').val(cod);
        $('#txt_padre_nl').val(nl);
        $('#txt_padre').val(cod);
        var che = cod.split('.').join('_');
        if($('#'+che).prop('checked')==false){ return false;}
    }

    let parametros = {
        'item': item, 
        'ent': entidad,
    };

    $.ajax({
        type: "POST",
        url: '../controlador/empresa/cambioeC.php?TVcatalogo=true',
        data:{parametros: parametros},
        dataType:'json',
        success: function (data) {
            if(nl==''){
                $('#tree1').html(data);
            }
        },
        error: function(err){
            console.error(err);
        }
    });

    /*var parametros = 
    {
        'nivel':nl,
        'cod':cod,
        'auto':auto,
        'serie':serie,
        'fact':fact,
        'item':item,
        'ent':entidad
    }

    //console.log(parametros);

    $.ajax({
        type: "POST",
        url: '../controlador/empresa/cambioeC.php?TVcatalogo=true',
        data:{parametros:parametros},
        dataType:'json',
        beforeSend: function () {
            $('#hijos_'+che+auto+serie+fact).html("<img src='../../img/gif/loader4.1.gif' style='width:20%' />");
        },
        success: function(data)
        {
            if(nl=='')
            {
                $('#tree1').html(data);
            }else
            {
                cod = cod.split('.').join('_');
                // cod = cod.replace(//g,'_');
                console.log(cod);
                console.log(data);
                $('#hijos_'+cod+auto+serie+fact).html(data);
                // if('hijos_01_01'=='hijos_'+cod)
                // {
                //   $('#hijos_'+cod).html('<li>hola</li>');
                // }
                // $('#hijos_'+cod).html('hola');
            }	        
        }
    });*/


 }

 function confirmar(varEF=false)
 {
    let param_linea_nuevo = parametrizarCampos();
    if(param_linea_nuevo == null){
        return;
    }

    let parametros = [];
    //let listaTxtLineas = [];
    if($('#TxtIDLinea').val() != '.'){
        lineaValNuevos[$('#TxtIDLinea').val()] = param_linea_nuevo;
        //console.log(lineaValNuevos);
    }else{
        let indiceRepe = arrNuevasLineas.findIndex(rep => rep['TextCodigo'] == $('#TextCodigo').val());
        if(indiceRepe == -1){
            arrNuevasLineas.push(param_linea_nuevo);
            //console.log(arrNuevasLineas);
        }
    }

    for(let lva of Object.keys(lineaValNuevos)){
        let valores_nuevos = Object.values(lineaValNuevos[lva]).join('&');
        let valores_anteriores = lineaValAnteriores[lva] ? Object.values(lineaValAnteriores[lva]).join('&') : '';

        if(valores_anteriores != valores_nuevos){
            parametros.push(lineaValNuevos[lva]);
            //listaTxtLineas.push(lineaValNuevos[lva]['TextLinea']);
        }
    }

    /*for(let anl of arrNuevasLineas){
        listaTxtLineas.push(anl['TextLinea']);
    }*/

    parametros = parametros.concat(arrNuevasLineas);
    console.log(parametros);

    if(parametros.length > 0){
        //var nom = $('#TextLinea').val();
        Swal.fire({
          //title: 'Esta seguro de guardar las siguientes lineas:</br>'+listaTxtLineas.join(', '),
          title: 'Esta seguro de guardar las lineas modificadas',
          text: "",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si!'
      }).then((result) => {
          if (result.value==true) {
              /*if($("#CTipo").val()=='')
              {
                  $("#CTipo").val('FA');
              }*/
              guardar(parametros, varEF);
          }
      })
    }else{
        Swal.fire('No existen lineas modificadas para guardar.', '', 'error');
    }
 }

 function guardar(parametros, varEF=false)
 {

    $('#myModal_espera').modal('show');

   //parametros['item'] = $('#TxtLineasItem').val();
   //parametros['entidad'] = $('#TxtLineasEntidad').val();
      $.ajax({
      type: "POST",
      url: '../controlador/empresa/cambioeC.php?guardar=true',
      data:{parametros:parametros},
    dataType:'json',       
      success: function(data)
      {
        setTimeout(()=>{
            $('#myModal_espera').modal('hide');
        }, 2000);
           //console.log(data);
           if(data==1)
           {
               TVcatalogo();
               Swal.fire('El proceso de grabar se realizo con exito','','success')
            .then(result => {
                lineaValNuevos = {};
                lineaValAnteriores = {};
                arrNuevasLineas = [];
                resetearTab5();
                if(varEF){
                    ejecutarFunc(varEF);
                }
            });
           }
      },
      error: (err) => {
        setTimeout(()=>{
            $('#myModal_espera').modal('hide');
        }, 2000);
        Swal.fire('Ocurrio un error al procesar su solicitud. Error: ' + err, '', 'error');
      }
    })
 }

 function confirmacion()
 {
      var det = $('#TextLinea').val();
       Swal.fire({
     title: 'Esta seguro de Grabar el Producto'+det,
     text: "",
     icon: 'warning',
     showCancelButton: true,
     confirmButtonColor: '#3085d6',
     cancelButtonColor: '#d33',
     confirmButtonText: 'Si!'
   }).then((result) => {
     if (result.value==true) {
      Eliminar(parametros);
     }
   })
 }

 function confirmar_eliminar()
 {
      var strasf = $('#LblTreeClick').val();
     let strDetalle = strasf.split('_');
    let strAlerta = (strDetalle[2] ? 'tipo '+strDetalle[2]+' con ':'') +
        (strDetalle[1] ? 'serie '+strDetalle[1]+' y ':'') +
        (strDetalle[0] ? 'autorización '+strDetalle[0]:'');
    
    if(strDetalle.length == 4){
        strAlerta = (strDetalle[3] ? 'línea '+strDetalle[3]+' de ':'') + strAlerta;
    }

    if($('#LblTreeClick').val()=='' || strAlerta == ''){
        return;
    }

     Swal.fire({
     title: 'Esta seguro de eliminar: '+strAlerta,
     text: "",
     icon: 'warning',
     showCancelButton: true,
     confirmButtonColor: '#3085d6',
     cancelButtonColor: '#d33',
     confirmButtonText: 'Si!'
   }).then((result) => {
     if (result.value==true) {
        eliminar_linea(strDetalle);
     }
   })
 }

 function eliminar_linea(strDetalle){
     let fact = strDetalle[2];
     let serie = strDetalle[1];
     let auto = strDetalle[0];
     let codigo = '';
     
     if(strDetalle.length == 4){
        codigo = strDetalle[3];
    }
    
    let parametros = {
        'codigo': codigo,
        'fact': fact,
        'serie': serie,
        'auto': auto,
        'entidad': $('#TxtLineasEntidad').val(),
        'item': $('#TxtLineasItem').val()
    };

    $('#myModal_espera').modal('show');
    $.ajax({
        type: "POST",
        url: '../controlador/empresa/cambioeC.php?eliminar_linea=true',
        data:{parametros},
        dataType:'json',       
        success: function(data)
        {
            setTimeout(()=>{
                $('#myModal_espera').modal('hide');
            }, 2000);
            if(data==1){
                if($('#TxtIDLinea').val() != ''){
                    let param_linea_nuevo = parametrizarCampos(false);
                    
                    if($('#TxtIDLinea').val() != '.'){
                        lineaValNuevos[$('#TxtIDLinea').val()] = param_linea_nuevo;
                        //console.log(lineaValNuevos);
                    }else{
                        let indiceRepe = arrNuevasLineas.findIndex(rep => rep['TextCodigo'] == $('#TextCodigo').val());
                        if(indiceRepe == -1){
                            arrNuevasLineas.push(param_linea_nuevo);
                            //console.log(arrNuevasLineas);
                        }
                    }
                }

                TVcatalogo();
                Swal.fire('Se elimino con exito','','success')
                .then(result => {
                    let entradasObj = Object.entries(lineaValNuevos);
                    if(codigo){
                        let entObj = entradasObj.find(obj => obj[1]['TextCodigo'] == codigo && obj[1]['CTipo'] == fact && obj[1]['TxtNumAutor'] == auto && obj[1]['TxtNumSerieUno'] == serie.substring(0,3) && obj[1]['TxtNumSerieDos'] == serie.substring(3,6));
                        //console.log(entObj);
                        
                        if(entObj){
                            delete lineaValNuevos[entObj[0]];
                            delete lineaValAnteriores[entObj[0]];
                        }
                        arrNuevasLineas = arrNuevasLineas.filter(anl => anl['TextCodigo'] != codigo && anl['CTipo'] != fact && anl['TxtNumAutor'] != auto && anl['TxtNumSerieUno'] == serie.substring(0,3) && anl['TxtNumSerieDos'] == serie.substring(3,6));
                    }else if(fact){
                        let entObj = entradasObj.find(obj => obj[1]['CTipo'] == fact && obj[1]['TxtNumAutor'] == auto && obj[1]['TxtNumSerieUno'] == serie.substring(0,3) && obj[1]['TxtNumSerieDos'] == serie.substring(3,6));
                        
                        if(entObj){
                            delete lineaValNuevos[entObj[0]];
                            delete lineaValAnteriores[entObj[0]];
                        }
                        arrNuevasLineas = arrNuevasLineas.filter(anl => anl['CTipo'] != fact && anl['TxtNumAutor'] != auto && anl['TxtNumSerieUno'] == serie.substring(0,3) && anl['TxtNumSerieDos'] == serie.substring(3,6));
                    }else if(serie){
                        let entObj = entradasObj.find(obj => obj[1]['TxtNumAutor'] == auto && obj[1]['TxtNumSerieUno'] == serie.substring(0,3) && obj[1]['TxtNumSerieDos'] == serie.substring(3,6));
                        
                        if(entObj){
                            delete lineaValNuevos[entObj[0]];
                            delete lineaValAnteriores[entObj[0]];
                        }
                        arrNuevasLineas = arrNuevasLineas.filter(anl => anl['TxtNumAutor'] != auto && anl['TxtNumSerieUno'] == serie.substring(0,3) && anl['TxtNumSerieDos'] == serie.substring(3,6));
                    }else if(auto){
                        let entObj = entradasObj.find(obj => obj[1]['TxtNumAutor'] == auto);
                        
                        if(entObj){
                            delete lineaValNuevos[entObj[0]];
                            delete lineaValAnteriores[entObj[0]];
                        }
                        arrNuevasLineas = arrNuevasLineas.filter(anl => anl['TxtNumAutor'] != auto);
                    }
                    /*lineaValNuevos = {};
                    lineaValAnteriores = {};
                    arrNuevasLineas = [];*/
                    resetearTab5();
                });
            }else{
                Swal.fire('No se pudo eliminar', '', 'error');
            }
        },
        error: function(err){
            setTimeout(()=>{
                $('#myModal_espera').modal('hide');
            }, 2000);
            Swal.fire('Ocurrio un error al realizar la peticion', '', 'error');
        }
    })
 }

 function detalle_linea(id,cod)
 {
    document.querySelector('#carga_linea_detalles').style.display = 'flex';
    let entidad = $('#TxtLineasEntidad').val();
    let item = $('#TxtLineasItem').val();
    
    if(!entidad){
        Swal.fire('Seleccione una Entidad', '', 'error');
        return;
    }

    if(!item){
        Swal.fire('Seleccione una Empresa', '', 'error');
        return;
    }

    //Para modificar una linea existente
    if(Object.keys(lineaValAnteriores).length > 0 && $('#TxtIDLinea').val() != '.'){
        let param_linea_nuevo = parametrizarCampos();
        //console.log(lineaValNuevos);
        if(param_linea_nuevo == null){
            return;
        }
        lineaValNuevos[$('#TxtIDLinea').val()] = param_linea_nuevo;

    }
    
    //Para insertar nueva linea
    if($('#TxtIDLinea').val() == '.'){
        let param_linea_nuevo = parametrizarCampos();
        //console.log(lineaValNuevos);
        if(param_linea_nuevo == null){
            return;
        }
        let indiceRepe = arrNuevasLineas.findIndex(rep => rep['TextCodigo'] == $('#TextCodigo').val());
        if(indiceRepe == -1){
            arrNuevasLineas.push(param_linea_nuevo);
        }
    }

    //Anade el borde azul a la linea en la lista
    if(cod)
    {
        var ant = $('#txt_anterior').val();
        var che = cod.split('.').join('_');	
        if(ant==''){	$('#txt_anterior').val(che); }else{	$('#label_'+ant).css('border','0px');$('#label_'+ant).removeAttr('title');}
        $('#label_'+che+'_'+id).css('border','1px solid');
        $('#label_'+che+'_'+id).attr('title', 'Presione Suprimir para eliminar');
        $('#txt_anterior').val(che+'_'+id); 
    }
    
    //Setea los campos dependiendo si existen modificados o nuevos desde base de datos
    let datoModificado = lineaValNuevos[id];
    if(datoModificado){
        $('#TxtIDLinea').val(datoModificado.TxtIDLinea)
        $('#span_id_linea').text(datoModificado.TxtIDLinea)
        $('#TextCodigo').val(datoModificado.TextCodigo)
        $('#TextLinea').val(datoModificado.TextLinea)
        $('#MBoxCta').val(datoModificado.MBoxCta)
        $('#MBoxCta_Anio_Anterior').val(datoModificado.MBoxCta_Anio_Anterior)
        $('#MBoxCta_Venta').val(datoModificado.MBoxCta_Venta)
        $('#CTipo').val(datoModificado.CTipo)
        $('#TxtNumFact').val(datoModificado.TxtNumFact)
        $('#TxtItems').val(datoModificado.TxtItems)
        $('#TxtLogoFact').val(datoModificado.TxtLogoFact)
        $('#TxtPosFact').val(datoModificado.TxtPosFact)
        $('#TxtEspa').val(datoModificado.TxtEspa)
        $('#TxtPosY').val(datoModificado.TxtPosY)
        $('#TxtLargo').val(datoModificado.TxtLargo)
        $('#TxtAncho').val(datoModificado.TxtAncho)

        $('#MBFechaIni').val(datoModificado.MBFechaIni)
        $('#MBFechaVenc').val(datoModificado.MBFechaVenc)
        $('#TxtNumSerietres1').val(datoModificado.TxtNumSerietres1)
        $('#TxtNumAutor').val(datoModificado.TxtNumAutor)
        $('#TxtNumSerieUno').val(datoModificado.TxtNumSerieUno)
        $('#TxtNumSerieDos').val(datoModificado.TxtNumSerieDos)

        $('#TxtNombreEstab').val(datoModificado.TxtNombreEstab)
        $('#TxtDireccionEstab').val(datoModificado.TxtDireccionEstab)
        $('#TxtTelefonoEstab').val(datoModificado.TxtTelefonoEstab)
        $('#TxtLogoTipoEstab').val(datoModificado.TxtLogoTipoEstab)
    
        $('#CheqPuntoEmision').prop('checked', datoModificado.CheqPuntoEmision);
        document.querySelector('#carga_linea_detalles').style.display = 'none';

        let fact = $('#CTipo').val();
        let auto = $('#TxtNumAutor').val();
        let serie = $('#TxtNumSerieUno').val()+ $('#TxtNumSerieDos').val();
        let codigo = $('#TextCodigo').val();
        $('#LblTreeClick').val(auto+'_'+serie+'_'+fact+'_'+codigo);
    }else{
        

          $.ajax({
          type: "POST",
          url: '../controlador/empresa/cambioeC.php?detalle=true',
          data:{id,item,entidad},
        dataType:'json',       
          success: function(data)
          {
              data = data[0];
               //console.log(data);

               $('#TxtIDLinea').val(data.ID)
               $('#span_id_linea').text(data.ID)
               $('#TextCodigo').val(data.Codigo)
               $('#TextLinea').val(data.Concepto)
               $('#MBoxCta').val(data.CxC)
               $('#MBoxCta_Anio_Anterior').val(data.CxC_Anterior)
               $('#MBoxCta_Venta').val(data.Cta_Venta)
               $('#CTipo').val(data.Fact)
               $('#TxtNumFact').val(data.Fact_Pag)
               $('#TxtItems').val(data.ItemsxFA)
               $('#TxtLogoFact').val(data.Logo_Factura)
               $('#TxtPosFact').val(data.Pos_Factura)
               $('#TxtEspa').val(data.Espacios)
               $('#TxtPosY').val(data.Pos_Y_Fact)
               $('#TxtLargo').val(data.Largo)
               $('#TxtAncho').val(data.Ancho)

               $('#MBFechaIni').val(formatoDate(data.Fecha.date))
               $('#MBFechaVenc').val(formatoDate(data.Vencimiento.date))
               $('#TxtNumSerietres1').val(generar_ceros(data.Secuencial,9))
               $('#TxtNumAutor').val(data.Autorizacion)
               $('#TxtNumSerieUno').val(data.Serie.substring(0,3))
               $('#TxtNumSerieDos').val(data.Serie.substring(3,6))

               $('#TxtNombreEstab').val(data.Nombre_Establecimiento)
               $('#TxtDireccionEstab').val(data.Direccion_Establecimiento)
               $('#TxtTelefonoEstab').val(data.Telefono_Estab)
               $('#TxtLogoTipoEstab').val(data.Logo_Tipo_Estab)
            
            $('#CheqPuntoEmision').prop('checked', data.TL);

            lineaValAnteriores[data.ID] = parametrizarCampos();
            document.querySelector('#carga_linea_detalles').style.display = 'none';

            let fact = $('#CTipo').val();
            let auto = $('#TxtNumAutor').val();
            let serie = $('#TxtNumSerieUno').val()+ $('#TxtNumSerieDos').val();
            let codigo = $('#TextCodigo').val();
            $('#LblTreeClick').val(auto+'_'+serie+'_'+fact+'_'+codigo);
          }
        })
    }

 }

 function parametrizarCampos(mostrarAlertas=true){
    let TextCodigo = [$('#TextCodigo').val(), 'Código'];
    let TextLinea = [$('#TextLinea').val(), 'Descripción'];
    let MBoxCta = [$('#MBoxCta').val(), 'CxC Clientes'];
    let MBoxCta_Anio_Anterior = [$('#MBoxCta_Anio_Anterior').val(), 'CxC Año Anterior'];
    //let CTipo = [$('#CTipo').val(), 'Tipo de Documento'];
    let TxtLargo = [$('#TxtLargo').val(), 'Largo'];
    let TxtAncho = [$('#TxtAncho').val(), 'Ancho'];
    let MBFechaIni = [$('#MBFechaIni').val(), 'Fecha de Inicio'];
    let MBFechaVenc = [$('#MBFechaVenc').val(), 'Fecha de Vencimiento'];
    let TxtNumSerietres1 = [$('#TxtNumSerietres1').val(), 'Secuencial de Inicio'];
    let TxtNumAutor = [$('#TxtNumAutor').val(), 'Autorización'];
    let TxtNumSerieUno = [$('#TxtNumSerieUno').val(), 'Serie uno'];
    let TxtNumSerieDos = [$('#TxtNumSerieDos').val(), 'Serie dos'];

       let camposObl = [TextCodigo, TextLinea, MBoxCta, MBoxCta_Anio_Anterior, CTipo, TxtLargo, TxtAncho, MBFechaIni, MBFechaVenc, TxtNumSerietres1, TxtNumAutor, TxtNumSerieUno, TxtNumSerieDos];
       let camposFaltantes = [];

    for(let cobl of camposObl){
        if(cobl[0] == ''){
            camposFaltantes.push(cobl[1]);
        }
    }

    if(mostrarAlertas){
        if(camposFaltantes.length > 0){
            Swal.fire('Por favor llene los siguientes campos: ' + camposFaltantes.join(', '), '', 'info');
            return null;
        }else if($('#TxtIDLinea').val() == ''){
            Swal.fire('Ocurrio un problema, vuelva a consultar la informacion.', '', 'error');
            return null;
        }
    }

    //$('#myModal_espera').show();

       let parametros = {
        'TxtIDLinea': $('#TxtIDLinea').val(),
        'TextCodigo': TextCodigo[0],
        'TextLinea': TextLinea[0],
        'MBoxCta': MBoxCta[0],
        'MBoxCta_Anio_Anterior': MBoxCta_Anio_Anterior[0],
        'MBoxCta_Venta': $('#MBoxCta_Venta').val() == '' ? '0' : $('#MBoxCta_Venta').val(),
        'CheqPuntoEmision': $('#CheqPuntoEmision').prop('checked'),
        'CTipo': $('#CTipo').val() == '' ? 'FA' : $('#CTipo').val(),
        'TxtNumFact': $('#TxtNumFact').val() == '' ? '0' : $('#TxtNumFact').val(),
        'TxtItems': $('#TxtItems').val() == '' ? '0' : $('#TxtItems').val(),
        'TxtLogoFact': $('#TxtLogoFact').val() == '' ? 'NINGUNO' : $('#TxtLogoFact').val(),
        'TxtPosFact': $('#TxtPosFact').val() == '' ? '0.00' : $('#TxtPosFact').val(),
        'TxtPosY': $('#TxtPosY').val() == '' ? '0.00' : $('#TxtPosY').val(),
        'TxtEspa': $('#TxtEspa').val() == '' ? '0' : $('#TxtEspa').val(),
        'TxtLargo': TxtLargo[0],
        'TxtAncho': TxtAncho[0],
        'MBFechaIni': MBFechaIni[0],
        'TxtNumSerietres1': TxtNumSerietres1[0],
        'MBFechaVenc': MBFechaVenc[0],
        'TxtNumAutor': TxtNumAutor[0],
        'TxtNumSerieUno': TxtNumSerieUno[0],
        'TxtNumSerieDos': TxtNumSerieDos[0],
        'TxtNombreEstab': $('#TxtNombreEstab').val() == '' ? '.' : $('#TxtNombreEstab').val(),
        'TxtDireccionEstab': $('#TxtDireccionEstab').val() == '' ? '.' : $('#TxtDireccionEstab').val(),
        'TxtTelefonoEstab': $('#TxtTelefonoEstab').val() == '' ? '.' : $('#TxtTelefonoEstab').val(),
        'TxtLogoTipoEstab': $('#TxtLogoTipoEstab').val() == '' ? '.' : $('#TxtLogoTipoEstab').val(),
        'item': $('#TxtLineasItem').val(),
        'entidad': $('#TxtLineasEntidad').val(),
   };

   return parametros;
   //arrModificados.push(param_mod)
 }


 function facturacion_mes()
 {
     // console.log($('#CheqCtaVenta').prop('checked'))
      if($('#CheqCtaVenta').prop('checked'))
      {
          $('#panel_cta_venta').css('display','block');
      }else
      {
          $('#panel_cta_venta').css('display','none');	 	 	
      }
 }

 function ejecutarFunc(origen){
    resetearTab5();
    lineaValNuevos = {};
    lineaValAnteriores = {};
    arrNuevasLineas = [];
    switch(origen){
        case 'entidad':
            buscar_ciudad();
        break;
        case 'ciudad':
            buscar_empresas();
        break;
        case 'empresas':
            datos_empresa();
        break;
    }
 }

 function confirmarCambioEntidad(input_origen){
    //Para modificar una linea existente
    if(Object.keys(lineaValAnteriores).length > 0 && $('#TxtIDLinea').val() != '.'){
        let param_linea_nuevo = parametrizarCampos(false);
        //console.log(lineaValNuevos);
        if(param_linea_nuevo == null){
            return;
        }
        lineaValNuevos[$('#TxtIDLinea').val()] = param_linea_nuevo;

    }
    
    //Para insertar nueva linea
    if($('#TxtIDLinea').val() == '.'){
        let param_linea_nuevo = parametrizarCampos(false);
        //console.log(lineaValNuevos);
        if(param_linea_nuevo == null){
            return;
        }
        let indiceRepe = arrNuevasLineas.findIndex(rep => rep['TextCodigo'] == $('#TextCodigo').val());
        if(indiceRepe == -1){
            arrNuevasLineas.push(param_linea_nuevo);
        }
    }


    if(Object.keys(lineaValNuevos).length > 0 || arrNuevasLineas.length > 0){
        
        let listaTxtLineas = [];

        for(let lva of Object.keys(lineaValNuevos)){
            let valores_nuevos = Object.values(lineaValNuevos[lva]).join('&');
            let valores_anteriores = lineaValAnteriores[lva] ? Object.values(lineaValAnteriores[lva]).join('&') : '';

            if(valores_anteriores != valores_nuevos){
                listaTxtLineas.push(lineaValNuevos[lva]['TextLinea']);
            }
        }

        for(let anl of arrNuevasLineas){
            if(anl['TextLinea']) listaTxtLineas.push(anl['TextLinea']);
        }


        if(listaTxtLineas.length > 0){
            //Swal.fire('Tiene cambios sin confirmar en las lineas:</br>'+listaTxtLineas.join(', '), '', 'warning');
            Swal.fire({
                //title: 'Usted va a cambiar de Empresa. Se perderan los cambios realizados en las lineas:</br>'+listaTxtLineas.join(', '),
                title: 'Usted va a cambiar de Empresa. Se perderan los cambios realizados en las lineas',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Guardar cambios',
                confirmButtonColor: '#3085d6',
                cancelButtonText: 'Cambiar empresa',
                cancelButtonColor: '#d33',
                //confirmButtonText: 'Si!'
            }).then((result) => {
                if (result.value==true) {
                    /*if($("#CTipo").val()=='')
                    {
                        $("#CTipo").val('FA');
                    }*/
                    confirmar(input_origen);
                }else{
                    ejecutarFunc(input_origen);
                }
            })
        }else{
            ejecutarFunc(input_origen);
        }
    }else{
        ejecutarFunc(input_origen);
    }
 }

function validar_codigo(){
    let TextCodigo = $('#TextCodigo').val();

    if(TextCodigo.trim() == '' && TextCodigo.trim() == '.'){
        return;
    }
    let parametros = {
        'codigo': $('#TextCodigo').val(),
        'item': $('#TxtLineasItem').val(),
        'entidad': $('#TxtLineasEntidad').val(),
    }
    $('#myModal_espera').modal('show');

    $.ajax({
        type: "POST",
        url: '../controlador/empresa/cambioeC.php?validar_codigo=true',
        data:{parametros:parametros},
        dataType:'json',       
        success: function(data)
        {
            if(data.res==1)
            {
                $('#TxtIDLinea').val(data.ID);
            }else{
                $('#TxtIDLinea').val('.');
            }
            setTimeout(()=>{
                $('#myModal_espera').modal('hide');
            }, 2000);
        },
        error: (err) => {
            Swal.fire('Ocurrio un error al procesar su solicitud. Error: ' + err, '', 'error');
            setTimeout(()=>{
                $('#myModal_espera').modal('hide');
            }, 2000);
        }
    })
}
function renderhtml()
{
    datos = $('#simpleHtml').val();
    $('#htmlrender').html(datos);
}

function enviar_email()
{

    var formData = new FormData(document.getElementById("form_email"));
    formData.append('entidad',$('#entidad').val())
    formData.append('empresa',$('#empresas').val())

    $.ajax({
        type: "POST",
        url: '../controlador/empresa/cambioeC.php?enviar_email=true',
        dataType:'json',
        data: formData,
        contentType: false,
        processData: false,
        success: function(data)
        {
            
        }
    });
}
