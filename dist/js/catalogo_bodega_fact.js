$(document).ready(function () { 
	 		TVcatalogo();
	 		var h = (screen.height)-478;
    $('#tabla').css('height',h);

	 /*$('#txt_codigo').keyup(function(e){ 
			if(e.keyCode != 46 && e.keyCode !=8)
			{
				validar_cuenta_inv(this);
			}
		 })*/

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

	 function generarQR(){
		var codigo = $('#txt_codigo').val();

		if(!codigo.trim()){
			Swal.fire('No se puede generar el QR sin el codigo','','info');
			return;
		}

		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/catalogo_productosC.php?generarQR=true',
			data: {codigo}, 
			dataType:'json',
			success: function(data){
				if(data.res == 1){
					$('#codigo_qr').attr('src', data.qr);
				}
			}
		});
	 }

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
	 	var qr = $('#codigo_qr').attr('src');
  		$.ajax({
			  type: "POST",
			  url: '../controlador/facturacion/catalogo_productosC.php?eliminarINVBod=true',
			  data: {codigo,codigo,qr}, 
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
						/*if(padre!=cod[0] && cod.length==2)
						{
							TVcatalogo(padre_nl,padre);
						}else
						{
							//TVcatalogo(parseInt(padre_nl), padre);
							TVcatalogo(padre_nl,padre);
						}*/
						TVcatalogo(padre_nl,padre);
						$('#codigo_qr').attr('src', '');
						$('#txt_codigo').val('');
						$('#txt_nomenclatura').val('');
						$('#txt_concepto').val('');
				  	});
			    }else
			    {
			    	Swal.fire('No se puede eliminar','','error');
			    }
			  }
			})
	 }

	 function TVcatalogo(nl='',cod=false)
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
	      url: '../controlador/facturacion/catalogo_productosC.php?TVcatalogo_Bodega=true',
	      data:{nivel:nivel,cod:cod},
        dataType:'json',
        beforeSend: function () {
            $('#hijos_'+che).html("<img src='../../img/gif/loader4.1.gif' style='width:20%' />");
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
	      url: '../controlador/facturacion/catalogo_productosC.php?LlenarInvBod=true',
	      data:{parametros,parametros},
        dataType:'json',
	      success: function(data)
	      {
			let qr = data['qr'];
	      	let detalle = data['detalle'][0];
	      	console.log(detalle);
			console.log(qr);
	      	$('#txt_concepto').val(detalle.Producto);
	      	$('#txt_nomenclatura').val(detalle.Nomenclatura);
	      	$('#txt_codigo').val(detalle.CodBod);
	      	$('#pvp').val(detalle.PVP);
	      	$('#pvp2').val(detalle.PVP_2);
	      	$('#pvp3').val(detalle.PVP_3);
	      	$('#maximo').val(detalle.Maximo);
	      	$('#minimo').val(detalle.Minimo);
			$('#codigo_qr').attr('src', qr);
	      	if(detalle.TC=='P'){ $('#cbx_final').prop('checked',true);}else{$('#cbx_inv').prop('checked',true);}
	      	// if(data.IVA==1){ $('#rbl_iva').prop('checked',true);}else{$('#rbl_iva').prop('checked',false);}
	      	if(detalle.INV==1){ $('#rbl_inv').prop('checked',true);}else{$('#rbl_inv').prop('checked',false);}
	      	/*if(data.Agrupacion==1){ $('#rbl_agrupacion').prop('checked',true);}else{$('#rbl_agrupacion').prop('checked',false);}
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
	      	$('#txt_formula').val(data.Ayuda);*/
          
	      }
	    });
   }


  function guardarINV()
  {
  	var datos = $('#form_datos').serialize();
  		$.ajax({
			  type: "POST",
			  url: '../controlador/facturacion/catalogo_productosC.php?guardarINVBod=true',
			  data: datos, 
			  dataType:'json',
			  success: function(data)
			  {
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
			  				/*if(padre==cod[0])
			  				{
			  					TVcatalogo(padre_nl,padre);
							}else
							{
								TVcatalogo();
							}*/
							TVcatalogo(padre_nl,padre);
			  			});
			  	}
			  	console.log(data);
			  }
			})
  }

  function imprimirEtiqueta(){
	  let codigo = $('#txt_codigo').val();
	  let nomenclatura = $('#txt_nomenclatura').val();
	  let producto = $('#txt_concepto').val();
	  let qr = $('#codigo_qr').attr('src');
	
	  if(!(codigo && nomenclatura && producto && qr)){
		Swal.fire('Seleccione una bodega para poder imprimir.', '', 'info');
		return;
	  }
	
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/catalogo_productosC.php?imprimirEtiqueta=true',
		data: {codigo, nomenclatura, producto, qr}, 
		dataType:'json',
		success: function(data)
		{
			var url = '../../TEMP/' + data.pdf + '.pdf';
			window.open(url, '_blank');
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