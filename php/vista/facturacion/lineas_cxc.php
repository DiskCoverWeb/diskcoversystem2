<link rel="stylesheet" href="../../dist/css/arbol.css">
<?php //print_r($_SESSION['INGRESO']);?>
<script type="text/javascript">
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
	      url: '../controlador/facturacion/lineas_cxcC.php?TVcatalogo=true',
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
            // console.log(cod);
            // console.log(data);

            // console.log('#hijos_'+cod)
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
	 	 $.ajax({
	      type: "POST",
	      url: '../controlador/facturacion/lineas_cxcC.php?guardar=true',
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
	    })
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
	      url: '../controlador/facturacion/lineas_cxcC.php?detalle=true',
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

	
</script>
<div class="row">
	<div class="col-sm-7">
		<div class="panel panel-primary">
			<div class="panel-heading" style="padding: 0px 10px 0px 10px;">
				NOMBRE DE LA CUENTA POR COBRAR
			</div>
		<!-- 	<input type="text" name="auto" id="auto">
			<input type="text" name="serie" id="serie">
			<input type="text" name="serie" id="serie">
			<input type="text" name="tipo" id="tipo"> -->
			<input type="hidden" name="txt_anterior" id="txt_anterior">
			<div class="panel-body" id="tree1">
				
			</div>
		</div>
	</div>
	<div class="col-sm-5">
		<button type="button" class="btn btn-default" title="Grabar factura" onclick="confirmar()">
			<img src="../../img/png/grabar.png"><br>
			&nbsp; &nbsp;&nbsp;  Grabar&nbsp; &nbsp; &nbsp; 
			<br>
		</button>
		<br>
		<button type="button" class="btn btn-default" title="Grabar factura" onclick="boton1()" disabled>
			<img src="../../img/png/grabar.png"><br>
			Vencimiento <br> de Facturas
		</button>
		<br>
		
	</div>
</div>
<form id="form_datos">
<div class="row">
	<div class="col-sm-5">
		<div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">CODIGO</label>
          <div class="col-sm-10">
            <input type="text" class="form-control input-xs" id="TextCodigo" name="TextCodigo" placeholder="" value=".">
          </div>
        </div>	
	</div>
	<div class="col-sm-7">
		<div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">DESCRIPCION</label>
          <div class="col-sm-10">
            <input type="text" class="form-control input-xs" id="TextLinea" name="TextLinea" placeholder="NO PROCESABLE" value="NO PROCESABLE">
          </div>
        </div>	
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="box">
			<div class="box-body">
				<ul class="nav nav-tabs">
				  <li class="active"><a data-toggle="tab" href="#home">DATOS DE PROCESO</a></li>
				  <li><a data-toggle="tab" href="#menu1">DATOS DEL S.R.I</a></li>
				</ul>

				<div class="tab-content">
				  <div id="home" class="tab-pane fade in active">
				     <div class="row"><br>
				     	<div class="col-sm-6">
							<div class="form-group">
					          <label for="inputEmail3" class="col-sm-5 control-label">CxC Clientes</label>
					          <div class="col-sm-7">
					            <input type="text" class="form-control input-xs" id="MBoxCta" name="MBoxCta" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
					          </div>
					        </div>	
						</div>
						<div class="col-sm-6">
									<div class="form-group">
					          <label for="inputEmail3" class="col-sm-5 control-label">CxC Año Anterior</label>
					          <div class="col-sm-7">
					            <input type="text" class="form-control input-xs" id="MBoxCta_Anio_Anterior" name="MBoxCta_Anio_Anterior"placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">					           
					          </div>
					        </div>	
						</div>
						<div class="col-sm-6">
				     	    <label><input type="checkbox" name="CheqCtaVenta" id="CheqCtaVenta" onclick="facturacion_mes()"> Cuenta de Venta si manejamos por Sector</label>
				    </div>
				    <div class="col-sm-6">
				    	<div class="form-group" id="panel_cta_venta" style="display:none">
				    		 <label for="inputEmail3" class="col-sm-5 control-label"> </label>
					          <div class="col-sm-7">
					              <input type="text" class="form-control input-xs" id="MBoxCta_Venta" name="MBoxCta_Venta"placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">				 			           
					          </div>				    		
				    	</div>				    	    	
				    </div>				     
				     </div>
				     <div class="row">
				     		<div class="col-sm-6">
				     	     <label><input type="checkbox" name="CheqMes" id="CheqMes"> Facturacion por Meses</label>
					     	</div>
					     	<div class="col-sm-6">
					     	    <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">TIPO DE DOCUMENTO</label>
						          <div class="col-sm-7">
						            <select class="form-control input-xs" id="CTipo" name="CTipo">
						            	<option value="FA">FA</option>
											    <option value="NV">NV</option>
											    <option value="PV">PV</option>
											    <option value="FT">FT</option>
											    <option value="NC">NC</option>
											    <option value="LC">LC</option>
											    <option value="GR">GR</option>
											    <option value="CP">CP</option>
						            </select>
						          </div>
						        </div>	
					     	</div>				     	
				     </div>
				     <div class="row">
				     	<div class="col-sm-6">
				     	    <div class="form-group">
					         <label for="inputEmail3" class="col-sm-7 control-label">NUMERO DE FACTURAS POR PAGINAS</label>
					          <div class="col-sm-5">
					          		<input type="text" class="form-control input-xs" id="TxtNumFact" name="TxtNumFact" placeholder="Email" value="00">
					          </div>
					        </div>	
				     	</div>
				     	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">ITEMS POR FACTURA</label>
						          <div class="col-sm-7">
						            	<input type="text" class="form-control input-xs" id="TxtItems" name="TxtItems" placeholder="Email" value="0.00">
						          </div>
						        </div>	
				     	</div>
				     	<div class="col-sm-12">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">FORMATO GRAFICO DEL DOCUMENTO (EXTENSION:GIF)</label>
						          <div class="col-sm-7">
						            <input type="text" class="form-control input-xs" id="TxtLogoFact" name="TxtLogoFact" placeholder="Email">
						          </div>
						        </div>	
				     	</div>				     	
				     </div>
				     <div class="row">
				     	<div class="col-sm-12">
				     		ESPACIO Y POSICION DE LA COPIA DE LA FACTURA / NOTA DE VENTA
				     	</div>
				     	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">POSICION X DE LA FACTURA</label>
						          <div class="col-sm-7">
						            <input type="text" class="form-control input-xs" id="TxtPosFact" name="TxtPosFact" placeholder="Email" value="0.00">
						          </div>
						        </div>	
				     	</div>
				     	<div class="col-sm-6">
				     	    <div class="form-group">
					          <label for="inputEmail3" class="col-sm-5 control-label">POSICION Y DE LA FACTURA</label>
					          <div class="col-sm-7">
						            <input type="text" class="form-control input-xs" id="TxtPosY" name="TxtPosY" placeholder="" value="0.00">
					          </div>
					        </div>	
				     	</div>
				     	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">ESPACIO ENTRE LA FACTURA</label>
						          <div class="col-sm-7">
					            <input type="text" class="form-control input-xs" id="TxtEspa" name="TxtEspa" placeholder="" value="0.00">
						          </div>
						        </div>	
				     	</div>
				     	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-2 control-label">LARGO</label>
						          <div class="col-sm-3">
						            <input type="text" class="form-control input-xs" id="TxtLargo" name="TxtLargo" placeholder="" value="0.00">
						          </div>
						          <label for="inputEmail3" class="col-sm-2 control-label">X</label>
						          <label for="inputEmail3" class="col-sm-2 control-label">ANCHO</label>
						          <div class="col-sm-3">
						            <input type="text" class="form-control input-xs" id="TxtAncho" name="TxtAncho" placeholder="" value="0.00">
						          </div>

						      </div>	
				     	</div>
				     	
				     </div>
				     
				  </div>
				  <div id="menu1" class="tab-pane fade">
					   <div class="row">
					   	<div class="col-sm-12">
					   		DATOS DEL S.R.I. DE LA FACTURA / NOTA DE VENTA
					   	</div>
					   	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">FECHA DE INICIO</label>
						          <div class="col-sm-7">
						            <input type="date" class="form-control input-xs" id="MBFechaIni" name="MBFechaIni" placeholder="" value="<?php echo date('Y-m-d');?>" >
						          </div>
						        </div>	
				     	</div>
				     	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">SECUENCIAL DE INICIO</label>
						          <div class="col-sm-7">
						            <input type="text" class="form-control input-xs" id="TxtNumSerietres1" name="TxtNumSerietres1" placeholder="" value="000001">
						          </div>
						        </div>	
				     	</div>
				     	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">FECHA DE VENCIMIENTO</label>
						          <div class="col-sm-7">
						            <input type="date" class="form-control input-xs" id="MBFechaVenc" name="MBFechaVenc" placeholder="" value="<?php echo date('Y-m-d');?>">
						          </div>
						        </div>	
				     	</div>
				     	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">AUTORIZACION</label>
						          <div class="col-sm-7">
						            <input type="text" class="form-control input-xs text-right" id="TxtNumAutor" name="TxtNumAutor" placeholder="" value="0000000001">
						          </div>
						        </div>	
				     	</div>
				     	<div class="col-sm-12">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-8 control-label">SERIE DE FACTURA / NOTA DE VENTA (ESTAB. Y PUNTO DE VENTA)</label>
						          <div class="col-sm-2">
						            <input type="text" class="form-control input-xs" id="TxtNumSerieUno" name="TxtNumSerieUno" placeholder="" value="001">
						          </div>
						          <div class="col-sm-2">
						            <input type="text" class="form-control input-xs" id="TxtNumSerieDos" name="TxtNumSerieDos" placeholder="" value="001">
						          </div>
						        </div>	
				     	</div>
					 	</div>
					 	<div class="row">
					 		<h4>DATOS DEL ESTABLECIMIENTO</h4>
					 		<div class="col-sm-12">
					 			 <B>NOMBRE DEL ESTABLECIMIENTO</B>
					 			 <input type="text" class="form-control input-xs" id="TxtNombreEstab" name="TxtNombreEstab" placeholder="" value=".">
					 		</div>
					 		<div class="col-sm-12">
					 			<div class="form-group">
				          <label for="inputEmail3" class="col-sm-1 control-label">DIRECCION</label>
				          <div class="col-sm-11">
				            <input type="text" class="form-control input-xs" id="TxtDireccionEstab" name="TxtDireccionEstab" placeholder="" value=".">
				          </div>
				        </div>	
					 		</div>
					 		<div class="col-sm-6">
					 			<div class="form-group">
				          <label for="inputEmail3" class="col-sm-2 control-label">TELEFONO</label>
				          <div class="col-sm-10">
				            <input type="text" class="form-control input-xs" id="TxtTelefonoEstab" name="TxtTelefonoEstab" placeholder="" value=".">
				          </div>
				        </div>	
					 		</div>
					 		<div class="col-sm-6">
					 			<div class="form-group">
				          <label for="inputEmail3" class="col-sm-3 control-label">LOGOTIPO(GIF)</label>
				          <div class="col-sm-9">
				            <input type="text" class="form-control input-xs" id="TxtLogoTipoEstab" name="TxtLogoTipoEstab" placeholder="" value=".">
				          </div>
				        </div>						 			
					 		</div>
					 	</div>
				  </div>				  
				</div>
			</div>
		</div>		
	</div>
</div>
</form>