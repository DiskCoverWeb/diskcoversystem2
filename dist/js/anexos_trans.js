
$(document).ready(function()
{
  cargar_meses();
  cargar_year();
})

 function cargar_meses()
{
    var meses='';
    $.ajax({
    // data:  {parametros:parametros},
    url:   '../controlador/contabilidad/anexos_transC.php?meses=true',
    type:  'post',
    dataType: 'json',
    // beforeSend: function () {   
    //   $('#myModal_espera').modal('show');   
    // },
    success:  function (response) { 
    if(response)
    {
        $.each(response, function(i, item){
            // console.log(item);
            meses+='<li><a href="#"  onclick="$(\'#txt_mes\').val(\''+item.mes+'\');titulo();">'+item.mes+'</a></li>';
        }); 
        $('#meses').html(meses);
        // $('#myModal_espera').modal('hide');   
    }

    }
    });
}
 function cargar_year()
{
    var year='';
    $.ajax({
    // data:  {parametros:parametros},
    url:   '../controlador/contabilidad/anexos_transC.php?year=true',
    type:  'post',
    dataType: 'json',
    // beforeSend: function () {   
    //   $('#myModal_espera').modal('show');   
    // },
    success:  function (response) { 
    if(response)
    {
        $.each(response, function(i, item){
            // console.log(item);
            year+='<li><a href="#" onclick="$(\'#txt_year\').val(\''+item.year+'\');titulo();">'+item.year+'</a></li>';
        }); 
        $('#year').html(year);
        // $('#myModal_espera').modal('hide');   
    }

    }
    });
}

function titulo()
{
    $('#home_').text('ANEXO TRANSACCIONAL DE '+$('#txt_year').val()+' - '+$('#txt_mes').val());
}

function generar_ats()
{
    var parametros = 
    {
        'mes':$('#txt_mes').val(),
        'year':$('#txt_year').val(),
    }
    $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/anexos_transC.php?generar_ats=true',
    type:  'post',
    // dataType: 'json',
    beforeSend: function () {   
        $('#myModal_espera').modal('show');   
    },
    success:  function (response) { 
        if(!response == '0')
        {
        vista_ATS();
        
        }
        $('#myModal_espera').modal('hide');   
    }
    });
}

function vista_ATS(){
    var parametros = 
    {
        'mes':$('#txt_mes').val(),
        'year':$('#txt_year').val(),
    }
    $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/anexos_transC.php?vista_ats=true',
    type:  'post',
    dataType: 'json',
    success:  function (response) { 
        if(response)
        { 
        // $('#home').html("<iframe style='width:100%; height:50vw; src='"+response+"' frameborder='0' allowfullscreen></iframe>");
        // $('#myModal_espera').modal('hide');  
        $('#home').html("<iframe src="+response+'#zoom=150'+" width='100%' height='500px' frameborder='0' allowfullscreen></iframe>");
        $('#myModal_espera').modal('hide');
        var ar = response.split('\/');
        var co = ar.length;
        var arc = ar[co-1].replace('pdf','xml');
            downloadURI(response.replace('pdf','xml'),arc);   
        }
    }
    });
}

function downloadURI(url,archivo) 
{      
  var link = document.createElement("a");
  link.download = archivo;
  link.href = url;
  link.click();
}