
  $(document).ready(function () {
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
	TVcatalogo();
  })


	 function TVcatalogo(nl='',cod='',auto='',serie='',fact='')
	 {
         if(cod)
        {
            var ant = $('#txt_anterior').val();
            var che = cod.split('.').join('_');	
            if(ant==''){	$('#txt_anterior').val(che); }else{	$('#label_'+ant).css('border','0px');}
            $('#label_'+che+auto+serie+fact).css('border','1px solid');
            $('#txt_anterior').val(che+auto+serie+fact); 
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

		var parametros = 
		{
			'nivel':nl,
			'cod':cod,
			'auto':auto,
			'serie':serie,
			'fact':fact,
		}

        
        $.ajax({
            type: "POST",
			url: '../controlador/facturacion/lineas_cxc_factC.php?TVcatalogo=true',
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
                    console.log('#hijos_'+cod+auto+serie+fact);
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
	    });
	 }

	 function confirmar()
	 {
	 	 var nom = $('#TextLinea').val();
	 	 Swal.fire({
			title: 'Esta seguro de guardar '+nom,
			text: "",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si!'
		}).then((result) => {
			if (result.value==true) {
				if($("#CTipo").val()=='')
				{
					$("#CTipo").val('FA');
				}
				guardar()
			}
		})
	 }

	 function guardar()
	 {
	   parametros = $('#form_datos').serialize();
	   console.log(parametros);
	 	 /*$.ajax({
	      type: "POST",
	      url: '../controlador/facturacion/lineas_cxc_factC.php?guardar=true',
	      data:parametros,
        dataType:'json',       
	      success: function(data)
	      {
	       	console.log(data);
	       	if(data==1)
	       	{
	       		TVcatalogo();
	       		Swal.fire('El proceso de grabar se realizo con exito','','success');
	       	}
	      }
	    })*/
	 }

	 function confirmacion()
	 {
	 	 var det = $('#TextLinea').val();
	 	  Swal.fire({
         title: 'Esta seguro de Grabar el Producto'+det,
         text: "",
         type: 'warning',
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

	 function detalle_linea(id,cod)
	 {
	 	if(cod)
    {
		 	var ant = $('#txt_anterior').val();
		 	var che = cod.split('.').join('_');	
		 	if(ant==''){	$('#txt_anterior').val(che); }else{	$('#label_'+ant).css('border','0px');}
		 	$('#label_'+che+'_'+id).css('border','1px solid');
		 	$('#txt_anterior').val(che+'_'+id); 
	  }

	 	 $.ajax({
	      type: "POST",
	      url: '../controlador/facturacion/lineas_cxc_factC.php?detalle=true',
	      data:{id,id},
        dataType:'json',       
	      success: function(data)
	      {
	      	data = data[0];
	       	console.log(data);

	       	$('#TextCodigo').val(data.Codigo)
	       	$('#TextLinea').val(data.Concepto)
	       	$('#MBoxCta').val(data.CxC)
	       	$('#MBoxCta_Anio_Anterior').val(data.CxC_Anterior)
	       	$('#CTipo').val(data.Fact)
	       	$('#TxtNumFact').val(data.Fact_Pag)
	       	$('#TxtItems').val(data.ItemsxFA)
	       	$('#TxtLogoFact').val(data.Logo_Factura)
	       	$('#TxtPosFact').val(data.Pos_Factura)
	       	$('#TxtEspa').val(data.Espacios)
	       	$('#TxtPosY').val(data.Pos_Y_Fact.toFixed(2))
	       	$('#TxtLargo').val(data.Largo.toFixed(2))
	       	$('#TxtAncho').val(data.Ancho.toFixed(2))

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
			
			if(esPrismanet){
				$('#CheqPuntoEmision').attr('checked', data.TL);
			}
	      }
	    })

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