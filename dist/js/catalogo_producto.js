$(document).ready(function () { 
    //getCatalogoProductos();
        TVcatalogo();
//         var h = (screen.height)-478;
// $('#tabla').css('height',h);

$('#txt_codigo').keyup(function(e){ 
       if(e.keyCode != 46 && e.keyCode !=8)
       {
           validar_cuenta_inv(this);
       }
    })

$('#cta_inventario').keyup(function(e){ 
       if(e.keyCode != 46 && e.keyCode !=8)
       {
           validar_cuenta(this);
       }
    })
$('#cta_costo_venta').keyup(function(e){ 
       if(e.keyCode != 46 && e.keyCode !=8)
       {
           validar_cuenta(this);
       }
    })
$('#cta_venta').keyup(function(e){ 
       if(e.keyCode != 46 && e.keyCode !=8)
       {
           validar_cuenta(this);
       }
    })
$('#cta_tarifa_0').keyup(function(e){ 
       if(e.keyCode != 46 && e.keyCode !=8)
       {
           validar_cuenta(this);
       }
    })
$('#cta_venta_anterior').keyup(function(e){ 
       if(e.keyCode != 46 && e.keyCode !=8)
       {
           validar_cuenta(this);
       }
    })
})
$(document).keyup(function(e){ 
  // console.log(e);   	
  // console.log(document.activeElement);
  var ele = document.activeElement.tagName;   
   if((e.keyCode==46 && e.target.type=='checkbox') || (e.keyCode==46 && ele=='A'))
   {
       eliminar();
   }
})

function eliminar()
{
     Swal.fire({
 title: 'Quiere eliminar este registro?',
 text: "Esta seguro de eliminar este registro!",
 type: 'warning',
 showCancelButton: true,
 confirmButtonColor: '#3085d6',
 cancelButtonColor: '#d33',
 confirmButtonText: 'Si'
}).then((result) => {
   if (result.value) {
        delete_cuenta();
   }
 })
}

function delete_cuenta()
{
    var codigo = $('#txt_codigo').val();
     $.ajax({
         type: "POST",
         url: '../controlador/facturacion/catalogo_productosC.php?eliminarINV=true',
         data: {codigo,codigo}, 
         dataType:'json',
         success: function(data)
         {
             if(data==1)
             {
                 var padre_nl = $('#txt_padre_nl').val();
                 var padre = $('#txt_padre').val();
                 Swal.fire('Eliminado','','success').then(function(){ 
                     var cod = $('#txt_codigo').val();
                         var cod = cod.split('.');
                         if(padre!=cod[0] && cod.length==2)
                         {
                             TVcatalogo(padre_nl,padre);
                         }else
                         {
                             TVcatalogo();
                         }
                 });
           }else
           {
               Swal.fire('No se puede eliminar','','error');
           }
         }
       })
}

function getCatalogoProductos(){
   $.ajax({
     type: "GET",
     url: '../controlador/facturacion/catalogo_productosC.php?CatalogoProductos=true',
   dataType:'json',
     success: function(data)
     {
       console.log(data);
     }
   });
}

function TVcatalogo(nl='',cod=false)
{

    //pinta el seleccionado
    if(cod)
{
    var ant = $('#txt_anterior').val();
    var che = cod.split('.').join('_');	
    if(ant==''){	$('#txt_anterior').val(che); }else{	$('#label_'+ant).css('border','0px');/*$('#label_'+ant).removeAttr('title');*/}
    $('#label_'+che).css('border','1px solid');
    $('#txt_anterior').val(che); 
 }
    //fin de pinta el seleccionado
if(cod)
{
 $('#txt_codigo').val(cod);
 $('#txt_padre_nl').val(nl);
 $('#txt_padre').val(cod);
 LlenarInv(cod);
 var che = cod.split('.').join('_');
 if($('#'+che).prop('checked')==false){ return false;}
}

$('#txt_padre_nl').val(nl);
var nivel = nl;
   $.ajax({
     type: "POST",
     url: '../controlador/facturacion/catalogo_productosC.php?TVcatalogo=true',
     data:{nivel,nivel,cod:cod},
   dataType:'json',
   beforeSend: function () {
        if(nivel=='')
        {
            $('#tree1').html("<img src='../../img/gif/loader4.1.gif' style='width:60%' />");
        }else{
            $('#hijos_'+che).html("<img src='../../img/gif/loader4.1.gif' style='width:20%' />");
        }
   },
     success: function(data)
     {
     if(nivel=='')
     {
       $('#tree1').html(data);
     }else
     {
       cod = cod.split('.').join('_');
       // cod = cod.replace(//g,'_');
       console.log(cod);
       $('#hijos_'+cod).html(data);
       // if('hijos_01_01'=='hijos_'+cod)
       // {
       //   $('#hijos_'+cod).html('<li>hola</li>');
       // }
       // $('#hijos_'+cod).html('hola');
     }	        
     }
   });
}

function detalle(nl,cod)
{
      
    //pinta el seleccionado
    if(cod)
{
    var ant = $('#txt_anterior').val();
    var che = cod.split('.').join('_');	
    if(ant==''){	$('#txt_anterior').val(che); }else{	$('#label_'+ant).css('border','0px');}
    $('#label_'+che).css('border','1px solid');
    $('#txt_anterior').val(che); 
 }
    //fin de pinta el seleccionado


    $('#txt_codigo').val(cod);
 $('#txt_padre_nl').val(nl-1);
 var pa = cod.split('.');

 var padre = '';
 for (var i = 0; i < nl-2; i++) {
         padre+= pa[i]+'.';
 }


 // console.log(padre);
 // console.log(cod);
 padre2 = padre.substr(-1*padre.length,padre.length-1);
 // console.log(padre2);

 $('#txt_padre').val(padre2);
 LlenarInv(cod);

}

function LlenarInv(cod)
{
  var parametros = 
  {
      'codigo':cod,
  }
      $.ajax({
     type: "POST",
     url: '../controlador/facturacion/catalogo_productosC.php?LlenarInv=true',
     data:{parametros,parametros},
   dataType:'json',
     success: function(data)
     {
         data = data[0];
         console.log(data);
         $('#txt_concepto').val(data.Producto);
         $('#txt_codigo').val(data.Codigo_Inv);
         $('#pvp').val(data.PVP);
         $('#pvp2').val(data.PVP_2);
         $('#pvp3').val(data.PVP_3);
         $('#maximo').val(data.Maximo);
         $('#minimo').val(data.Minimo);
         if(data.TC=='P'){ $('#cbx_final').prop('checked',true);}else{$('#cbx_inv').prop('checked',true);}
         if(data.IVA==1){ $('#rbl_iva').prop('checked',true);}else{$('#rbl_iva').prop('checked',false);}
         if(data.INV==1){ $('#rbl_inv').prop('checked',true);}else{$('#rbl_inv').prop('checked',false);}
         if(data.Agrupacion==1){ $('#rbl_agrupacion').prop('checked',true);}else{$('#rbl_agrupacion').prop('checked',false);}
         if(data.Por_Reservas==1){ $('#rbl_reserva').prop('checked',true);}else{$('#rbl_reserva').prop('checked',false);}
         if(data.Div==1){ $('#cbx_dividir').prop('checked',true);}else{$('#cbx_multiplicar').prop('checked',true);}


         $('#cta_costo_venta').val(data.Cta_Costo_Venta);
         $('#cta_inventario').val(data.Cta_Inventario);
         $('#cta_venta').val(data.Cta_Ventas);
         $('#cta_venta_anterior').val(data.Cta_Ventas_Ant);
         $('#cta_tarifa_0').val(data.Cta_Ventas_0);

         $('#txt_unidad').val(data.Unidad);
         $('#txt_barras').val(data.Codigo_Barra);
         $('#txt_marca').val(data.Marca);
         $('#txt_reg_sanitario').val(data.Reg_Sanitario);
         $('#txt_ubicacion').val(data.Ubicacion);
         $('#txt_iess').val(data.Codigo_IESS);
         $('#txt_codres').val(data.Codigo_RES);
         $('#txt_utilidad').val(data.Utilidad);
         $('#txt_codbanco').val(data.Item_Banco);
         $('#txt_descripcion').val(data.Desc_Item);

         $('#txt_gramaje').val(data.Gramaje);
         $('#txt_posx').val(data.PX);
         $('#txt_posy').val(data.PY);
         $('#txt_formula').val(data.Ayuda);
     
     }
   });
}


function guardarINV()
{
    if($('#txt_codigo').val() == '' || $('#txt_codigo').val() == '.' || $('#txt_concepto').val() == '' || $('#txt_concepto').val() == '.'){
        Swal.fire({
            title: 'Completar campos Codigo y Concepto del producto.',
            icon: 'error'
        });
        return;
    }
    //$('#myModal_espera').modal('show');
 var datos = $('#form_datos').serialize();
     $.ajax({
         type: "POST",
         url: '../controlador/facturacion/catalogo_productosC.php?guardarINV=true',
         data: datos, 
         dataType:'json',
         success: function(data)
         {
            //$('#myModal_espera').modal('hide');
             if(data==1)
             {
                 var padre_nl = $('#txt_padre_nl').val();
                 var padre = $('#txt_padre').val();
                 Swal.fire('Guardado correctamente','','success').then(function()
                     { 
                         console.log(padre_nl);
                         console.log(padre);
                         var cod = $('#txt_codigo').val();
                         var cod = cod.split('.');
                         if(padre==cod[0])
                         {
                             TVcatalogo(padre_nl,padre);
                         }else
                         {
                             TVcatalogo();
                         }
                     });
             }
             console.log(data);
         }
       })
}

function codigo_barras(cant=1)
{
 var codigo = $('#txt_codigo').val();
   var url= '../controlador/facturacion/catalogo_productosC.php?cod_barras=true&codigo='+codigo+'&cant='+cant;
 window.open(url,'_blank');
}

function codigo_barras_grupo()
{
 var codigo = $('#txt_codigo').val();
   var url= '../controlador/facturacion/catalogo_productosC.php?cod_barras_grupo=true&codigo='+codigo;
 window.open(url,'_blank');
}

function cantidad_codigo_barras()
{
  Swal.fire({
 title: 'Cantidad de etiquetas',
     input: 'text',
 showCancelButton: true,
 confirmButtonColor: '#3085d6',
 cancelButtonColor: '#d33',
 confirmButtonText: 'Generar'
}).then((result) => {
   // console.log(result);
   if (result.value) {
       codigo_barras(result.value);
   }
})
}   