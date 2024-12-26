function autocoplet_bene()
{
    $('#DCProveedor').select2({
      placeholder: 'Seleccione una beneficiario',
      width:'resolve',
      ajax: {
        url:   '../controlador/contabilidad/incomC.php?beneficiario_p=true',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          console.log(data);
          return {
            results: data
          };
        },
        cache: true
      }
    });
}


function familias()
{
    $('#ddl_familia').select2({
      placeholder: 'Seleccione una Familia',
      ajax: {
         url:   '../controlador/inventario/registro_esC.php?familias=true',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
         /// console.log(data);
          return {
            results: data
          };
        },
        cache: true
      }
    });

}
 function producto_famili(familia)
{ 
  var fami = $('#ddl_familia').val();
  $('#ddl_producto').select2({
      placeholder: 'Seleccione producto',
      ajax: {
         url:   '../controlador/inventario/registro_esC.php?producto=true&fami='+fami,
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
function contracuenta()
{ 
  $('#DCCtaObra').select2({
      placeholder: 'Seleccione Contracuenta',
      ajax: {
         url:   '../controlador/inventario/registro_esC.php?contracuenta=true',
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

function codigo_beneficiario(ruc)
{ 
  var parametros =
  {
      'ruc':ruc,
  }
  $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/inventario/registro_esC.php?leercodigo=true',
    type:  'post',
    dataType: 'json',
      success:  function (response) {
          if (response.length !=0) 
          {
              //console.log(response)
              Ult_fact_Prove(response);
          }
       
    }
  });  

}

function leercuenta()
{ 
   $('#DCBenef').val('').trigger('change');
  var parametros =
  {
      'cuenta':$('#DCCtaObra').val(),
  }
  $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/inventario/registro_esC.php?leercuenta=true',
    type:  'post',
    dataType: 'json',
      success:  function (response) {
          if (response.length !=0) 
          {
              $('#Codigo').val(response.Codigo);
              $('#Cuenta').val(response.Cuenta);
              $('#SubCta').val(response.SubCta);
              $('#Moneda_US').val(response.Moneda_US);
              $('#TipoCta').val(response.TipoCta);
              $('#TipoPago').val(response.TipoPago);
              ListarProveedorUsuario();

          }
       
    }
  });  

}

 function Trans_Kardex()
{ 
  $.ajax({
   // data:  {parametros:parametros},
    url:   '../controlador/inventario/registro_esC.php?Trans_Kardex=true',
    type:  'post',
    dataType: 'json',
      success:  function (response) {
      if (response.length !=0) 
      {
          // console.log(response);
      }         
    }
  });  

}

   function bodega()
{ 
  var option = '<option value="">Seleccione bodega</option>';
  $.ajax({
   // data:  {parametros:parametros},
    url:   '../controlador/inventario/registro_esC.php?bodega=true',
    type:  'post',
    dataType: 'json',
      success:  function (response) {
      if (response.length !=0) 
      {
      $.each(response,function(i,item){
        //  console.log(item);
           option+='<option value="'+item.CodMar+'">'+item.Marca+'</option';
         });
         $('#DCBodega').html(option); 
      }         
    }
  });  

}


 function marca()
{ 
  var option = '<option value="">Seleccione marca</option>';
  $.ajax({
   // data:  {parametros:parametros},
    url:   '../controlador/inventario/registro_esC.php?marca=true',
    type:  'post',
    dataType: 'json',
      success:  function (response) {
      if (response.length !=0) 
      {
         $.each(response,function(i,item){
         // console.log(item);
           option+='<option value="'+item.CodMar+'">'+item.Marca+'</option';
         });
         $('#DCMarca').html(option); 
      }         
    }
  });  

}



 function ListarProveedorUsuario()
{ 
  var cta = $('#SubCta').val();
  var contra = $('#DCCtaObra').val();
  $('#DCBenef').select2({
      placeholder: 'Seleccione Cliente',
      ajax: {
         url:   '../controlador/inventario/registro_esC.php?ListarProveedorUsuario=true&cta='+cta+'&contra='+contra,
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
        //  console.log(data);
          return {
            results: data
          };
        },
        cache: true
      }
    });
 

}

function guardar()
{
    var tipo = $('input:radio[name=rbl_]:checked').val();
}


function modal_retencion()
{
    if($('#rbl_retencion').prop('checked'))
    {
      $('#myModal').modal('show');
    }
}

function detalle_articulo()
{
  var arti = $('#ddl_producto').val();
  var fami = $('#ddl_familia').val();
  var nom_ar = $('select[name="ddl_producto"] option:selected').text();
  var parametros = 
  {
      'arti':arti,
      'nom':nom_ar,
      'fami':fami,
  }
  $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/inventario/registro_esC.php?detalle_articulos=true',
    type:  'post',
    dataType: 'json',
      success:  function (response) {
      if (response.length !=0) 
      {
          $('#labelProductro').val(response.producto);
          $('#LabelUnidad').val(response.unidad);
          $('#LabelCodigo').val(response.codigo);
          $('#TxtRegSanitario').val(response.registrosani);
          if(response.si_no==0){
               $('#Sin').prop('checked',true);
          }else
          {
              $('#con').prop('checked',true);
          }
      // console.log(response);
      }         
    }
  });  

}
function tipo_ingreso()
{
  if($('#ingreso').prop('checked'))
  {
      // alert('ingreso');
      $('#DCCtaObra').attr('disabled',false);
      $('#DCBenef').attr('disabled',false);
      $('#cbx_contra_cta').attr('disabled',false);
      $('#cbx_contra_cta').attr('checked',true);
  }else
  {
      $('#DCCtaObra').attr('disabled',true);
      $('#DCBenef').attr('disabled',true);
      $('#cbx_contra_cta').attr('disabled',true);
      $('#cbx_contra_cta').attr('checked',false);
      // alert('egreso');
  }

}
function limpiar_retencion()
{
  $('#modal_cuenta').modal('hide');
   window.parent.postMessage('closeModal', '*');
}


function pdf_retencion()
{

  var src ="../controlador/modalesC.php?pdf_retencion=true";
  window.open(src,'_blank');

}


function datos_pro()
{
  var ddl = $('#DCProveedor').val();
  var ddl = ddl.split('-');
  $('#txtemail').val(ddl[1])
  $('#LblTD').val(ddl[2])
  $('#LblNumIdent').val(ddl[3])
  var nombre = $('#DCProveedor option:selected').text()
  $('#DCProveedor').empty()
  $('#DCProveedor').append($('<option>',{value:ddl[4], text:nombre,selected: true }));
}


function addCliente(){
  $("#myModal").modal("show");
  var src ="../vista/modales.php?FCliente=true&proveedor=true";
   $('#FCliente').attr('src',src).show();
}
function usar_cliente(nombre,ruc,codigo,email,td='N')
{
    $('#txtemail').val(email);
    $('#LblNumIdent').val(ruc);
    $('#codigoCliente').val(codigo);
    $('#LblTD').val(td);
    // $('#DCCliente').append('<option value="' +codi+ ' ">' + datos[indice].text + '</option>');
    $('#DCProveedor').append($('<option>',{value:codigo, text:nombre,selected: true }));
    $('#myModal').modal('hide');
}


function eliminar_linea_Retencion(A_no,Codret)
{

   var parametros = 
  {
      'a_no':A_no,
      'cod':Codret,
  }
  $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/inventario/registro_esC.php?eliminar_air=true',
    type:  'post',
    dataType: 'json',
      success:  function (response) {
      if (response.length !=0) 
      {
         cargar_grilla();
      }         
    }
  });  


}
