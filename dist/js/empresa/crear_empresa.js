buscar_empresas();
// TraerCheqCopiarEmpresa();
$(document).ready(function()
{
    autocompletar();
    // autocompletarCempresa();
    informacion_empresa();
    
    naciones();
    provincias();
    ciudad(17);
    MostrarUsuClave();
    MostrarEmpresaCopia();
    AmbientePrueba();
});
function autocompletar(){
        $('#select_empresa').select2({
        placeholder: 'Seleccionar empresa',
        ajax: {
            url: '../controlador/empresa/crear_empresaC.php?empresas=true',
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
function buscar_empresas() 
{
    var option ="<option value=''>Seleccione empresa</option>";
    $.ajax({
        url:'../controlador/empresa/crear_empresaC.php?empresas=true',
        type:'post',
        dataTye:'jason',
        success: function(response){
            response.forEach(function(data,index){
                option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
            });
            $('#select_empresa').html(option);
            //console.log(response);
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
function MostrarEmpresaCopia()
{//CheqCopiiarEmpresa_Click
    if($('#CopSeEmp').prop('checked'))
    {
        $('#ListaCopiaEmpresa').css('display','block');
        autocompletarCempresa();
        TraerCheqCopiarEmpresa();
    }else
    {
        $('#ListaCopiaEmpresa').css('display','none');
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
                //console.log(response);
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
function TraerCheqCopiarEmpresa()
{
    var option ="<option value=''>Seleccione empresa</option>";
    var Nomempresa = $('#Txtempresa').val();
    $.ajax({
        data:{Nomempresa:Nomempresa},
        url:'../controlador/empresa/crear_empresaC.php?Copiarempresas=true',
        type:'post',
        dataTye:'jason',
        success: function(response){
            response.forEach(function(data,index){
                option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
            });
            $('#ListaCopiaEmpresa').html(option);
            //console.log(response);
        }
    });
}
function naciones()
{
    var option ="<option value=''>Seleccione Pais</option>"; 
    $.ajax({
        url: '../controlador/empresa/crear_empresaC.php?naciones=true',
        type:'post',
        dataType:'json',
        // data:{usu:usu,pass:pass},
        beforeSend: function () {
            $("#prov").html("<option value=''>OTRO</option>");
            $("#ddl_ciudad").html("<option value=''>Seleccione provincia</option>");
        },
        success: function(response){
            response.forEach(function(data,index){
                option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
            });
            $('#ddl_naciones').html(option);
            $('#ddl_naciones').val('593');
            provincias('593');
            //console.log(response);
        }
    });
}

function provincias(pais)
  {
   var option ="<option value=''>Seleccione Provincia</option>"; 
     $.ajax({
      url: '../controlador/empresa/crear_empresaC.php?provincias=true',
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
        $('#prov').val(17);
      //console.log(response);
    }
    });

  }

  function ciudad(idpro)
	{
		// console.log(idpro);
		var option ="<option value=''>Seleccione Ciudad</option>"; 
		if(idpro !='')
		{
	   $.ajax({
		  url: '../controlador/empresa/crear_empresaC.php?ciudad=true',
		  type:'post',
		  dataType:'json',
		  data:{idpro:idpro},
		  success: function(response){
			response.forEach(function(data,index){
				option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
			});
            $('#ddl_ciudad').html(option);
            $('#ddl_ciudad').val(21701);
			//console.log(response);
		}
	  });
	 } 

	}
function informacion_empresa()
{
    var id =$('#TxtEmpresa').val();    
    parametros =
    {
        'id':id,
    }    
        $.ajax({
            data:{parametros:parametros},
            url: '../controlador/empresa/crear_empresaC.php?informacion_empre=true',
            type:'post',
            dataType:'json',            
            success: function(response){
                // console.log(response);                
            }
        });
}
function formulario()
{
    // var form = $('#formulario').serialize();
    var form = $('#TxtCI').val();
        $.ajax({
            data:{form:form},//son los datos que se van a enviar por $_POST
            url: '../controlador/empresa/crear_empresaC.php?usuario=true',//los datos hacia donde se van a enviar el envio por url es por GET
            type:'post',//envio por post
            dataType:'json',
            success: function(response){
                //console.log(response);
                $('#TxtUsuario').val(response[0]['Usuario']);
                $('#TxtClave').val(response[0]['Clave']);
                // console.log(response[0].id);
                // $('#TxtEmpresa').val(response[0]['Empresa']);
                // $('#TxtRazonSocial').val(response[0]['Razon_Social']);
                // $('#TxtNomComercial').val(response[0]['Nombre_Comercial']);                
                // $('#TxtRuc').val(response[0]['RUC']);
                // $('#TxtRepresentanteLegal').val(response[0]['Gerente']);
                // $('#TxtCI').val(response[0]['CI_Representante']);
                // $('#TxtDirMatriz').val(response[0]['Direccion']);
                // $('#TxtEsta').val(response[0]['Establecimientos']);
                // $('#TxtTelefono').val(response[0]['Telefono1']);
                // $('#TxtTelefono2').val(response[0]['Telefono2']);
                // $('#TxtFax').val(response[0]['FAX']);
                // $('#TxtMoneda').val(response[0]['S_M']);
                // $('#TxtNPatro').val(response[0]['No_Patronal']);
                // $('#TxtCodBanco').val(response[0]['CodBanco']);
                // $('#TxtTipoCar').val(response[0]['Tipo_Carga_Banco']);
                // $('#TxtAbrevi').val(response[0]['Abreviatura']);
                // $('#TxtEmailEmpre').val(response[0]['Email']);
                // $('#TxtEmailConta').val(response[0]['Email_Contabilidad']);
                // $('#TxtEmailRespa').val(response[0]['Email_Respaldos']);
                // $('#TxtSegDes').val(response[0]['Seguro']);
                // $('#TxtSubdir').val(response[0]['SubDir']);
                // $('#TxtNombConta').val(response[0]['Contador']);
                // $('#TxtRucConta').val(response[0]['RUC_Contador']);
            }
        });
}
function llamar()
{
    var item = $('#select_empresa').val();
    $.ajax({
            data:{item:item},//son los datos que se van a enviar por $_POST
            url: '../controlador/empresa/crear_empresaC.php?llamar=true',//los datos hacia donde se van a enviar el envio por url es por GET
            type:'post',//envio por post
            dataType:'json',
            success: function(response){
                //console.log(response);
                $('#TxtItem').val(response[0]['Item']);
                $('#TxtEmpresa').val(response[0]['Empresa']);
                $('#TxtRazonSocial').val(response[0]['Razon_Social']);
                $('#TxtNomComercial').val(response[0]['Nombre_Comercial']);                
                $('#TxtRuc').val(response[0]['RUC']);
                $('#TxtRepresentanteLegal').val(response[0]['Gerente']);
                $('#TxtCI').val(response[0]['CI_Representante']);
                $('#TxtDirMatriz').val(response[0]['Direccion']);
                $('#TxtEsta').val(response[0]['Establecimientos']);
                $('#TxtTelefono').val(response[0]['Telefono1']);
                $('#TxtTelefono2').val(response[0]['Telefono2']);
                $('#TxtFax').val(response[0]['FAX']);
                $('#TxtMoneda').val(response[0]['S_M']);
                $('#TxtNPatro').val(response[0]['No_Patronal']);
                $('#TxtCodBanco').val(response[0]['CodBanco']);
                $('#TxtTipoCar').val(response[0]['Tipo_Carga_Banco']);
                $('#TxtAbrevi').val(response[0]['Abreviatura']);
                $('#TxtEmailEmpre').val(response[0]['Email']);
                $('#TxtEmailConta').val(response[0]['Email_Contabilidad']);
                $('#TxtEmailRespa').val(response[0]['Email_Respaldos']);
                $('#TxtSegDes1').val(response[0]['Seguro']);
                $('#TxtSegDes2').val(response[0]['Seguro2']);
                $('#TxtSubdir').val(response[0]['SubDir']);
                $('#TxtNombConta').val(response[0]['Contador']);
                $('#TxtRucConta').val(response[0]['RUC_Contador']);
                if(response[0]['CPais']!=0)
                {
                    $("#ddl_naciones").val(response[0]['CPais']);
                }
                if(response[0]['Prov']==response[0]['CPais'])
                {
                    $("#prov").val(response[0]['Prov']);
                }
                if(response[0]['Ciudad']==response[0]['Prov'])
                {
                    $("#ddl_ciudad").val(response[0]['Ciudad']);
                }
                $('#TxtServidorSMTP').val(response[0]['smtp_Servidor']);//mail.diskcoversystem.com
                $('#TxtPuerto').val(response[0]['smtp_Puerto']);
                $('#TxtPVP').val(response[0]['Dec_PVP']);
                $('#TxtCOSTOS').val(response[0]['Dec_Costo']);
                $('#TxtIVA').val(response[0]['Dec_IVA']);
                $('#TxtCantidad').val(response[0]['Dec_Cant']);
                $('#TxtContriEspecial').val(response[0]['Codigo_Contribuyente_Especial']);
                if(response[0]['Ambiente'] == 1)
                {
                    $('#optionsRadios1').prop('checked',true);
                }else if(response[0]['Ambiente'] == 2)
                {
                    $('#optionsRadios2').prop('checked',true);
                }
                $('#TxtWebSRIre').val(response[0]['Web_SRI_Recepcion']);
                $('#TxtWebSRIau').val(response[0]['Web_SRI_Autorizado']);
                $('#TxtEXTP12').val(response[0]['Ruta_Certificado']);
                $('#TxtContraExtP12').val(response[0]['Clave_Certificado']);
                $('#TxtEmailGE').val(response[0]['Email_Conexion']);
                $('#TxtContraEmailGE').val(response[0]['Email_Contraseña']);
                $('#TxtEmaiElect').val(response[0]['Email_Conexion_CE']);
                $('#TxtContraEmaiElect').val(response[0]['Email_Contraseña_CE']);
                $('#TxtCopiaEmai').val(response[0]['Email_Procesos']);
                $('#TxtRUCOpe').val(response[0]['RUC_Operadora']);
                $('#txtLeyendaDocumen').val(response[0]['LeyendaFA']);
                $('#txtLeyendaImpresora').val(response[0]['LeyendaFAT']);
                
                if(response[0]['Det_SubMod']!=0)
                {
                    $('#ASDAS').prop('checked',true);
                }else if(response[0]['Det_SubMod']==0)
                {
                    $('#ASDAS').prop('checked',false);
                }

                if(response[0]['Mod_Fact']!=0)
                {
                    $('#MFNV').prop('checked',true);
                }else if(response[0]['Mod_Fact']==0)
                {
                    $('#MFNV').prop('checked',false);
                }

                if(response[0]['Mod_PVP']!=0)
                {
                    $('#MPVP').prop('checked',true);
                }else if(response[0]['Mod_PVP']==0)
                {
                    $('#MPVP').prop('checked',false);
                }

                if(response[0]['Imp_Recibo_Caja']!=0)
                {
                    $('#IRCF').prop('checked',true);
                }else if(response[0]['Imp_Recibo_Caja']==0)
                {
                    $('#IRCF').prop('checked',false);
                }

                if(response[0]['Medio_Rol']!=0)
                {
                    $('#IMR').prop('checked',true);
                }else if(response[0]['Medio_Rol']==0)
                {
                    $('#IMR').prop('checked',false);
                }

                if(response[0]['Rol_2_Pagina']!=0)
                {
                    $('#IRIP').prop('checked',true);
                }else if(response[0]['Rol_2_Pagina']==0)
                {
                    $('#IRIP').prop('checked',false);
                }

                if(response[0]['Det_Comp']!=0)
                {
                    $('#PDAC').prop('checked',true);
                }else if(response[0]['Det_Comp']==0)
                {
                    $('#PDAC').prop('checked',false);
                }

                if(response[0]['Registrar_IVA']!=0)
                {
                    $('#RIAC').prop('checked',true);
                }else if(response[0]['Registrar_IVA']==0)
                {
                    $('#RIAC').prop('checked',false);
                }

                // if(response[0]['Sucursal']!=0)
                // {
                //     $('#FCMS').prop('checked',true);
                // }
                //DS   IS  ES  NDS  NCS
                //NUMERACION DE COMPROBANTES
                if(response[0]['Num_CD']!=0)
                {
                    $('#DM').prop('checked',true);
                    $('#DS').prop('checked',false);
                }else if(response[0]['Num_CD']==0)
                {
                    $('#DM').prop('checked',false);
                    $('#DS').prop('checked',true);
                }

                if(response[0]['Num_CI']!=0)
                {
                    $('#IM').prop('checked',true);
                    $('#IS').prop('checked',false);
                }else if(response[0]['Num_CI']==0)
                {
                    $('#IM').prop('checked',false);
                    $('#IS').prop('checked',true);
                }

                if(response[0]['Num_CE']!=0)
                {
                    $('#EM').prop('checked',true);
                    $('#ES').prop('checked',false);
                }else if(response[0]['Num_CE']==0)
                {
                    $('#EM').prop('checked',false);
                    $('#ES').prop('checked',true);
                }

                if(response[0]['Num_ND']!=0)
                {
                    $('#NDM').prop('checked',true);
                    $('#NDS').prop('checked',false);
                }else if(response[0]['Num_ND']==0)
                {
                    $('#NDM').prop('checked',false);
                    $('#NDS').prop('checked',true);
                }

                if(response[0]['Num_NC']!=0)
                {
                    $('#NCM').prop('checked',true);
                    $('#NCS').prop('checked',false);
                }else if(response[0]['Num_NC']==0)
                {
                    $('#NCM').prop('checked',false);
                    $('#NCS').prop('checked',true);
                }

                if(response[0]['smtp_UseAuntentificacion']!=0)
                {
                    $('#Autenti').prop('checked',true);
                }else if(response[0]['smtp_UseAuntentificacion']==0)
                {
                    $('#Autenti').prop('checked',false);
                }

                if(response[0]['smtp_SSL']!=0)
                {
                    $('#SSL').prop('checked',true);
                }else if(response[0]['smtp_SSL']==0)
                {
                    $('#SSL').prop('checked',false);
                }

                if(response[0]['smtp_Secure']!=0)
                {
                    $('#Secure').prop('checked',true);
                }else if(response[0]['smtp_Secure']==0)
                {
                    $('#Secure').prop('checked',false);
                }

                if(response[0]['Obligado_Conta']!= '')
                {
                    $('#ddl_obli').val(response[0]['Obligado_Conta']);
                }
            }
        });
}
// function cambiar()
// {
   
    
// }
function guardar_empresa()
{       
    if(
        $('#TxtEmpresa').val()==''|| 
        $("#TxtRazonSocial").val()==''||
        $("#TxtNomComercial").val()==''||
        $("#TxtRuc").val()==''||
        $("#ddl_obli").val()==''||
        $("#TxtRepresentanteLegal").val()==''||
        $("#TxtCI").val()==''||
        $("#ddl_naciones").val()==''||
        $("#prov").val()==''||
        $("#ddl_ciudad").val()==''||
        $("#TxtDirMatriz").val()==''||
        $("#TxtEsta").val()==''||
        $("#TxtTelefono").val()==''||
        $("#TxtTelefono2").val()==''||
        $("#TxtFax").val()==''||
        $("#TxtMoneda").val()==''||
        $("#TxtNPatro").val()==''||
        $("#TxtCodBanco").val()==''||
        $("#TxtTipoCar").val()==''||
        $("#TxtAbrevi").val()==''||
        $("#TxtEmailEmpre").val()==''||
        $("#TxtEmailConta").val()==''||
        // $("#TxtEmailRespa").val()==''||
        $("#TxtSegDes1").val()==''||
        $("#TxtSegDes2").val()==''||
        $("#TxtSubdir").val()==''||
        $("#TxtNombConta").val()==''||
        $("#TxtRucConta").val()==''||
        $("#TxtRucConta").val()==''
    )
    {
        Swal.fire('Llene todos los campos para guardar la empresa','','info');
        return false;
    }
    // var razon = $('#TxtRazonSocial').val();
    var ckASDAS = $('#ASDAS').prop('checked');
    var ckMFNV = $('#MFNV').prop('checked');
    var ckMPVP = $('#MPVP').prop('checked');
    var ckIRCF = $('#IRCF').prop('checked');
    var ckIMR = $('#IMR').prop('checked');
    var ckIRIP = $('#IRIP').prop('checked');
    var ckPDAC = $('#PDAC').prop('checked');
    var ckRIAC = $('#RIAC').prop('checked');
    var ckFCMS = $('#FCMS').prop('checked');
    var ckDM = $('#DM').prop('checked');
    var ckDS = $('#DS').prop('checked');
    var ckIM = $('#IM').prop('checked');
    var ckIS = $('#IS').prop('checked');
    var ckEM = $('#EM').prop('checked');
    var ckES = $('#ES').prop('checked');
    var ckNDM = $('#NDM').prop('checked');
    var ckNDS = $('#NDS').prop('checked');
    var ckNCM = $('#NCM').prop('checked');
    var ckNCS = $('#NCS').prop('checked');
    var ckAutenti = $('#Autenti').prop('checked');
    var ckSSL = $('#SSL').prop('checked');
    var ckSecure = $('#Secure').prop('checked');
    var Ambiente1 = $('#optionsRadios1').prop('checked');
    var Ambiente2 = $('#optionsRadios2').prop('checked');
    var datos = $('#formulario').serialize();
    // guardar_usuario_clave();
    limpiar();
    
    $.ajax({	 	    
	 	type: "POST",
	 		url: '../controlador/empresa/crear_empresaC.php?guardar_empresa=true',
	 		// data: {razon1:razon}, 
            data:datos+
            '&ckASDAS='+ckASDAS+
            '&ckMFNV='+ckMFNV+
            '&ckMPVP='+ckMPVP+
            '&ckIRCF='+ckIRCF+
            '&ckIMR='+ckIMR+
            '&ckIRIP='+ckIRIP+
            '&ckPDAC='+ckPDAC+
            '&ckRIAC='+ckRIAC+
            '&ckDM='+ckDM+
            '&ckDS='+ckDS+
            '&ckIM='+ckIM+
            '&ckIS='+ckIS+
            '&ckEM='+ckEM+
            '&ckES='+ckES+
            '&ckNDM='+ckNDM+
            '&ckNDS='+ckNDS+
            '&ckNCM='+ckNCM+
            '&ckNCS='+ckNCS+
            '&ckAutenti='+ckAutenti+
            '&ckSSL='+ckSSL+
            '&ckSecure='+ckSecure+
            '&Ambiente1='+Ambiente1+
            '&Ambiente2='+Ambiente2,
	 		dataType:'json',
	 		success: function(response)
	 		{
                if(response==2)
		  	{
		  		Swal.fire('Datos Guardados','','success').then(function(){
		  			$('#TxtEmpresa').attr('readonly',true);
		  		});
		  		//console.log(response);
	 		}
	 		}
	 	});
}
function eliminar_empresa()
 {
 	var id = $('#TxtItem').val();
 		if(id=='')
 		{
 			Swal.fire('Seleccione una empresa','','error');
 			return false;
 		}
 		Swal.fire({
      title: 'Quiere eliminar esta empresa?',
      text: "Esta seguro de eliminar esta empresa!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
            eliminar(id);
        }
    })
 }
function eliminar(id)
{
    $.ajax({
        url: '../controlador/empresa/crear_empresaC.php?delete=true',
        type:'post',
        dataType:'json',
        data:{id:id},     
        success: function(response){
            if(response==1)
            {
                Swal.fire('Empresa eliminada','','success');
            }else{
                Swal.fire('Intente mas tarde','','error');
            }
            limpiar();
        }
    });
}

function limpiar()
{    
    $('#TxtEmpresa').val('');
    $("#TxtRazonSocial").val('');
    $("#TxtNomComercial").val('');
    $("#TxtRuc").val('');
    $("#ddl_obli").val('');
    $("#TxtRepresentanteLegal").val('');
    $("#TxtCI").val('');
    $("#ddl_naciones").val('');
    $("#TxtDirMatriz").val('');
    $("#TxtEsta").val('000')
    $("#TxtTelefono").val('');
    $("#TxtTelefono2").val('');
    $("#TxtFax").val('');
    $("#TxtMoneda").val('USD');
    $("#TxtNPatro").val('');
    $("#TxtCodBanco").val('');
    $("#TxtTipoCar").val('');
    $("#TxtAbrevi").val('');
    $("#TxtEmailEmpre").val('');
    $("#TxtEmailConta").val('');
    $("#TxtEmailRespa").val('');
    $("#TxtSegDes1").val('');
    $("#TxtSegDes2").val('');
    $("#TxtSubdir").val('');
    $("#TxtNombConta").val('')
    $("#TxtRucConta").val('');
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
function DiariosM()
{
    $('#DM').prop('checked',true);
    $('#DS').prop('checked',false);
}
function DiariosS()
{
    $('#DM').prop('checked',false);
    $('#DS').prop('checked',true);
}
function IngresosM()
{
    $('#IM').prop('checked',true);
    $('#IS').prop('checked',false);
}
function IngresosS()
{
    $('#IM').prop('checked',false);
    $('#IS').prop('checked',true);
}
function EgresosM()
{
    $('#EM').prop('checked',true);
    $('#ES').prop('checked',false);
}
function EgresosS()
{
    $('#EM').prop('checked',false);
    $('#ES').prop('checked',true);
}
function NDPM()
{
    $('#NDM').prop('checked',true);
    $('#NDS').prop('checked',false);
}
function NDPS()
{
    $('#NDM').prop('checked',false);
    $('#NDS').prop('checked',true);
}
function NCPM()
{
    $('#NCM').prop('checked',true);
    $('#NCS').prop('checked',false);
}
function NCPS()
{
    $('#NCM').prop('checked',false);
    $('#NCS').prop('checked',true);
}
function validar_CI()
{
    var ci = $('#TxtCI').val();
    if(ci.length<10)
    {
        Swal.fire('La cedula no tiene 10 caracteres','','info');
        $('#TxtCI').val('');
        return false;
    }
    $.ajax
    ({
        data:  {ci:ci},
        url:   '../controlador/empresa/crear_empresaC.php?validarCI=true',
        type:  'post',
        dataType: 'json',
        success:  function (response)
        { 
            //console.log(response);
            
        }
    });
}
function validar_RUC()
{
    var txtruc = $('#TxtRuc').val();
    if(txtruc.length<13)
    {
        Swal.fire('El RUC no tiene 13 caracteres','','info');
        $('#TxtRuc').val('');
        return false;
    }
    $.ajax
    ({
        data:  {txtruc:txtruc},
        url:   '../controlador/empresa/crear_empresaC.php?validarRUC=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) 
        { 
            //console.log(response);
            
        }
    });
}
function validar_RUConta()
{
    var txtruconta = $('#TxtRucConta').val();
    if(txtruconta.length<13)
    {
        Swal.fire('El RUC no tiene 13 caracteres','','info');
        $('#TxtRucConta').val('');
        return false;
    }
    $.ajax
    ({
        data:  {txtruconta:txtruconta},
        url:   '../controlador/empresa/crear_empresaC.php?validarRUConta=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) 
        { 
            //console.log(response);
            $('#TxtEmpresa').val('Tipo');
            if(response == 2)
            {
                Swal.fire('Numero de cedula invalido.','','error');
                $('#txt_ruc').val('');
                return false;
        }
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
                //console.log(response);
            $('#TxtSubdir').val(response);
            }
        }
    });
}