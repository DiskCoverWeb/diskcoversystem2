$(document).ready(function () {
    lista_egreso_checking();
    areas();  
    motivo_egreso()	
})

function modal_mensaje(orden)
{
  $('#myModal_notificar_usuario').modal('show');
  $('#txt_codigo').val(orden);
}

function notificar(usuario = false)
{
       var mensaje = $('#txt_notificar').val();
       var para_proceso = 4;
       if(usuario=='usuario')
       {
           mensaje = $('#txt_texto').val();
           para_proceso = 4;
       }
 
  var parametros = {
      'notificar':mensaje,
      'asunto':'De Checking egreso',
      'pedido':$('#txt_codigo').val(),
      'de_proceso':5,
      'pa_proceso':para_proceso,
  }

   $.ajax({
    data:  {parametros,parametros},
    url:   '../controlador/inventario/alimentos_recibidosC.php?notificar_egresos=true',
    type:  'post',
    dataType: 'json',
    success:  function (response) { 
      if(response==1)
      {
          
            Swal.fire("","Notificacion enviada","success").then(function(){
            $('#myModal_notificar_usuario').modal('hide'); 
            $('#txt_texto').val('');   
            $('#txt_codigo_usu').val('') 
        });
      }
      console.log(response);
      
    }, 
    error: function(xhr, textStatus, error){
      $('#myModal_espera').modal('hide');           
    }
  });
}


function cambiar_a_reportado()
{
   orden = $('#txt_codigo').val();
   var parametros = {
      'orden':orden
  }
   $.ajax({
      type: "POST",
         url:   '../controlador/inventario/egreso_alimentosC.php?cambiar_a_reportado=true',
      data:{parametros:parametros},
     dataType:'json',
      success: function(data)
      {
          lista_egreso_checking();
      }
  });
}
function modal_motivo(orden)
{
    cargar_motivo_lista(orden);
    $('#myModal_motivo').modal('show');
}

function cargar_motivo_lista(orden)
  {		
      var parametros = {
          'orden':orden
      }
       $.ajax({
          type: "POST",
             url:   '../controlador/inventario/egreso_alimentosC.php?cargar_motivo_lista=true',
          data:{parametros:parametros},
         dataType:'json',
          success: function(data)
          {
              $('#txt_motivo_lista').html(data);	
          }
      });
  }

function lista_egreso_checking()
  {		
      var parametros = 
      {
          'areas':$('#ddl_areas').val(),
      }
       $.ajax({
          type: "POST",
             url:   '../controlador/inventario/egreso_alimentosC.php?lista_egreso_checking=true',
          data:{parametros:parametros},
         dataType:'json',
          success: function(data)
          {
              $('#tbl_asignados').html(data);	
          }
      });
  }

   function areas(){
    $('#ddl_areas').select2({
      placeholder: 'Seleccione una Area',
      // width:'90%',
      ajax: {
        url:   '../controlador/inventario/egreso_alimentosC.php?areas_checking=true',          
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          // console.log(data);
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function motivo_egreso(){
    $('#ddl_motivo').select2({
      placeholder: 'Seleccione una beneficiario',
      // width:'90%',
      ajax: {
        url:   '../controlador/inventario/egreso_alimentosC.php?motivos=true',          
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          // console.log(data);
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function imprimir_item_empresa(){
    let item_empresa = $('#item_empresa').val();
    console.log(item_empresa);
  }

  function mostra_doc(documento)
  {
      let item_empresa = $('#item_empresa').val();
      $('#modal_documento').modal('show');
      $('#img_documento').attr('src','../comprobantes/sustentos/empresa_'+item_empresa+'/'+documento)
  }

  function modal_areas()
  {
      $('#myModal_opciones').modal('show');
      area_egreso_modal();
  }

  function area_egreso_modal(){
      
       $.ajax({
          type: "POST",
         url:   '../controlador/inventario/egreso_alimentosC.php?areas_checking=true',
           // data:{parametros:parametros},
         dataType:'json',
          success: function(data)
          {
              console.log(data)
              var option = '';
               data.forEach(function(item,i){
               img = 'simple';
                   if(item.data.Picture!='.'){	 		img = item.data.Picture; 	 	}
            option+= '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">'+
                        '<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/'+img+'.png" onclick="cambiar_area(\''+item.id+'\',\''+item.text+'\')"  style="width: 60px;height: 60px;"></button><br>'+
                        '<b style="white-space: nowrap;">'+item.text+'</b>'+
                      '</div>';
          })
          // $('#txt_paquetes').html(op); 
          $('#pnl_opciones').html(option);      
          }
      });
  }