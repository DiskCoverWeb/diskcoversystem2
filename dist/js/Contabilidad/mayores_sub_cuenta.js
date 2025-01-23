$(document).ready(function()
  {
  	sucursal_exis();
  	consultar_datos();
  	// FDLCtas();
  	FDCCtas();
  })

function sucursal_exis()
{ 

    $.ajax({
    //data:  {parametros:parametros},
    url:   '../controlador/contabilidad/diario_generalC.php?sucu_exi=true',
    type:  'post',
    dataType: 'json',
    /*beforeSend: function () {   
        var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
        $('#tabla_').html(spiner);
    },*/
    success:  function (response) { 
    if(response == 1)
    {
        // $("#CheckAgencia").show();
        $('#panel_agencia').show();
        // $('#lblAgencia').show();
    } else
    {
        // $("#CheckAgencia").hide();
        $('#panel_agencia').css('display','none');
        // $('#lblAgencia').hide();
    }     
    
    }
});

}
		
function consultar_datos()
    { 

    var agencia='<option value="">Seleccione Agencia</option>';
    var usu='<option value="">Seleccione Usuario</option>';
    $.ajax({
        //data:  {parametros:parametros},
        url:   '../controlador/contabilidad/mayores_sub_cuentaC.php?drop=true',
        type:  'post',
        dataType: 'json',	     
        success:  function (response) {       
        $.each(response.agencia, function(i, item){
            agencia+='<option value="'+response.agencia[i].Item+'">'+response.agencia[i].NomEmpresa+'</option>';
        });       
        $('#DCAgencia').html(agencia);
        $.each(response.usuario, function(i, item){
            usu+='<option value="'+response.usuario[i].Codigo+'">'+response.usuario[i].CodUsuario+'</option>';
        });         
        $('#DCUsuario').html(usu);          
        
        }
    });

    }

    function FDCCtas()
    {
    var parametros = 
    {
        'tipoMod':$('input[name="rbl_subcta"]:checked').val(),
    }
    var ddl = '';
    var lis = '';
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/contabilidad/mayores_sub_cuentaC.php?DCCtas=true',
        type:  'post',
        dataType: 'json',	     
        success:  function (response) {    

        $.each(response, function(i, item){
            ddl+='<option value="'+item.Codigo+'">'+item.Nombre_Cta+'</option>';
        });       
        if(ddl!='')
        {
            FDLCtas(response[0]['Codigo']);
        }else
        {
            ddl='<option value="">No existe</option>';
            FDLCtas('');	        	
        }

        $('#DCCtas').html(ddl);
        }
    });
    }

    function FDLCtas(DCcta)
    {
    console.log(DCcta);
    $('#DLCtas').html('');   
    var parametros = 
    {
        'tipoMod':$('input[name="rbl_subcta"]:checked').val(),
        'DCCta':DCcta,
    }
    var lis = '';
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/contabilidad/mayores_sub_cuentaC.php?DLCtas=true',
        type:  'post',
        dataType: 'json',	     
        success:  function (response) {     
        console.log(response);  	       
        $.each(response, function(i, item){
            lis+='<option value="'+item.Codigo+'">'+item.Nombre_Cta+'</option>';
        });

        if(lis==''){ lis='<option value="">No existe</option>';}         
        $('#DLCtas').html(lis);          
        
        }
    });
    }


function Consultar_Un_Submodulo()
{
    if($.fn.dataTable.isDataTable('#tbl_body')){
        $('#tbl_body').DataTable().clear().destroy();
    }
    var parametros =
    {
        'tipoM':$('input[name="rbl_subcta"]:checked').val(),
        'DCCtas':$("#DCCtas").val(),
        'DLCtas':$("#DLCtas").val(),
        'estado':$("input[name='rbl_estado']:checked").val(),
        'unoTodos':$("input[name='rbl_opc']:checked").val(),
        'checkusu':$("#check_usu").is(':checked'),
        'usuario':$('#DCUsuario').val(),
        'desde':$('#txt_desde').val(),
        'hasta':$('#txt_hasta').val(),	
        'checkagencia':$('#check_agencia').is(':checked'),
        'agencia':$('#DCAgencia').val(),		
    }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/contabilidad/mayores_sub_cuentaC.php?Consultar_Un_Submodulo=true',
        type:  'post',
        dataType: 'json',			
            beforeSend: function(){
                $('#myModal_espera').modal('show');
            },
            success:  function (response) {				
                $('#txt_debito').val(response.Debe);
                $('#txt_credito').val(response.Haber);
                $('#txt_saldo_actual').val(response.Saldo);
                $('#txt_saldo_ant').val(response.SaldoAnt);
                setTimeout(function(){
                    $('#myModal_espera').modal('hide');
                }, 1000); 
                tbl_body = $('#tbl_body').DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                    data: ProcesarDatos(response.tbl.data),
                    scrollY: '300px',
                    'columns': [
                        {"data":"Cta"},
                        {"data":"Fecha"},
                        {"data":"TP"},
                        {"data":"Numero"},
                        {"data":"Cliente"},
                        {"data":"Concepto"},
                        {"data":"Debitos"},
                        {"data":"Creditos"},
                        {"data":"Saldo_MN"},
                        {"data":"Factura"},
                        {"data":"Parcial_ME"},
                        {"data":"Detalles_SubCta"},
                        {"data":"Fecha_V"},
                        {"data":"Codigo"},
                        {"data":"Item"}
                    ], 
                    createdRow: function(row, data){
                        alingEnd(row, data)
                    }
                })
                //console.log(response.titulo);
        }
    });

}


$(document).ready(function()
{

    $("#descargar_pdf").click(function(){ 
            var datos = $('#form_filtros').serialize();
            var	url =  '../controlador/contabilidad/mayores_sub_cuentaC.php?reporte_pdf=true&'+datos;
        window.open(url, '_blank');
    });



    $('#descargar_excel').click(function(){       
    var datos = $('#form_filtros').serialize();
            var	url =  '../controlador/contabilidad/mayores_sub_cuentaC.php?reporte_excel=true&'+datos;
        window.open(url, '_blank');
    });

    
});


