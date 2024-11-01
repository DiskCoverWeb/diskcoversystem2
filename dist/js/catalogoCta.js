function consultar_datos()
{
    var parametros =
    {
        'OpcT':$("#OpcT").is(':checked'),
        'OpcG':$("#OpcG").is(':checked'),
        'OpcD':$("#OpcD").is(':checked'),
        'txt_CtaI':$('#txt_CtaI').val(),
        'txt_CtaF':$('#txt_CtaF').val(),			
    }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/contabilidad/catalogoCtaC.php?consultar=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () {		
            //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
                // $('#tabla_').html(spiner);
                $('#myModal_espera').modal('show');
        },
            success:  function (response) {
            
                $('#tabla_').html(response);	
                $('#myModal_espera').modal('hide');				    
            
        }
    });

}

/*       funcion enviada  a panel 
function validar_cuenta(campo)
{
    var id = campo.id;
    let cap = $('#'+id).val();
    let cuentaini = cap.replace(/[.]/gi,'');
//	var cuentafin = $('#txt_CtaF').val();
    var formato = "<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>";
    let parte =formato.split('.');
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


}*/

$(document).ready(function()
{
    consultar_datos();

    $('#txt_CtaI').keyup(function(e){ 
        if(e.keyCode != 46 && e.keyCode !=8)
        {
            validar_cuenta(this);
        }
        })

    $('#txt_CtaF').keyup(function(e){ 
        if(e.keyCode != 46 && e.keyCode !=8)
        {
            validar_cuenta(this);
        }
        })


    $('#imprimir_excel').click(function(){      		

    var url = '../controlador/contabilidad/catalogoCtaC.php?imprimir_excel=true&OpcT='+$("#OpcT").is(':checked')+'&OpcG='+$("#OpcG").is(':checked')+'&OpcD='+$("#OpcD").is(':checked')+'&txt_CtaI='+$('#txt_CtaI').val()+'&txt_CtaF='+$('#txt_CtaF').val();
        window.open(url, '_blank');
    });

    $('#imprimir_pdf').click(function(){      		

    var url = '../controlador/contabilidad/catalogoCtaC.php?imprimir_pdf=true&OpcT='+$("#OpcT").is(':checked')+'&OpcG='+$("#OpcG").is(':checked')+'&OpcD='+$("#OpcD").is(':checked')+'&txt_CtaI='+$('#txt_CtaI').val()+'&txt_CtaF='+$('#txt_CtaF').val();
        window.open(url, '_blank');
    });

    
});


