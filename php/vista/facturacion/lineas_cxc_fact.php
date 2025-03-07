<link rel="stylesheet" href="../../dist/css/arbol.css">
<?php //print_r($_SESSION['INGRESO']);?>
<?php $CodEntidad = $_SESSION['INGRESO']['Entidad_No']; ?>
<script type="text/javascript">
	var esPrismanet = "<?php echo $CodEntidad; ?>" == "1";
</script>
<script src="../../dist/js/facturacion/lineas_cxc_fact.js"></script>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?></div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
          <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
          </li>
        </ol>
      </nav>
    </div>          
</div>
<div class="row mb-2">
  <div class="col-sm-6">
     <div class="btn-group">
		<a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
			print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
				<img src="../../img/png/salire.png">
		</a>
		<button type="button" class="btn btn-outline-secondary"  data-bs-toggle="tooltip" title="Grabar" onclick="confirmar()">  <img src="../../img/png/grabar.png"></button>
		<button type="button" class="btn btn-outline-secondary"  data-bs-toggle="tooltip" title="Vencimiento de Facturas" onclick="boton1()" disabled>  <img src="../../img/png/grabar.png"></button>
    </div>
  </div>  
</div>
<div class="row">
	<div class="col-lg-6 col-12">
		<div class="card border">
			<div class="card-header">
				NOMBRE DE LA CUENTA POR COBRAR
			</div>
			<div class="card-body">
				<input type="hidden" name="txt_anterior" id="txt_anterior">
				<div id="tree1">
					
				</div>
			</div>
		<!-- 	<input type="text" name="auto" id="auto">
			<input type="text" name="serie" id="serie">
			<input type="text" name="serie" id="serie">
			<input type="text" name="tipo" id="tipo"> -->
		</div>
	</div>
	<div class="col-lg-6 col-12">
		<form id="form_datos">
			<div class="row">
				<div class="col-5">
					<div class="input-group input-group-sm">
						<span class="input-group-text">CODIGO</span>
						<input type="text" class="form-control form-control-sm" id="TextCodigo" name="TextCodigo" placeholder="" value=".">

						<!-- <div class="col-sm-10">
						</div> -->
					</div>	
				</div>
				<div class="col-7">
					<div class="input-group input-group-sm">
						<span class="input-group-text">DESCRIPCION</span>
						<input type="text" class="form-control form-control-sm" id="TextLinea" name="TextLinea" placeholder="NO PROCESABLE" value="NO PROCESABLE">
						<!-- <div class="col-sm-10">
						</div> -->
					</div>	
				</div>
			</div>
			<div class="row mt-1">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-body">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="nav-item" role="presentation"><button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">DATOS DE PROCESO</button></li>
								<li class="nav-item" role="presentation"><button class="nav-link"  id="menu1-tab" data-bs-toggle="tab" data-bs-target="#menu1" type="button" role="tab" aria-controls="menu1" aria-selected="false">DATOS DEL S.R.I</button></li>
							</ul>

							<div class="tab-content" id="myTab">
								<div id="home" class="tab-pane fade show active" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
									<div class="row mt-1">
										<div class="input-group input-group-sm">
											<span class="input-group-text">CxC Clientes</span>
											<input type="text" class="form-control form-control-sm" id="MBoxCta" name="MBoxCta" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
											<span class="input-group-text">CxC Año Anterior</span>
											<input type="text" class="form-control form-control-sm" id="MBoxCta_Anio_Anterior" name="MBoxCta_Anio_Anterior"placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
										</div>
										<!-- <div class="col-sm-6">
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
										</div> -->
									</div>
									<div class="row align-items-center mt-1">
										<div class="col-6">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="CheqCtaVenta" id="CheqCtaVenta" onclick="facturacion_mes()">
												<label class="form-check-label" for="CheqCtaVenta">
													Cuenta de Venta si manejamos por Sector
												</label>
											</div>
											<!-- <label><input type="checkbox" name="CheqCtaVenta" id="CheqCtaVenta" onclick="facturacion_mes()"> Cuenta de Venta si manejamos por Sector</label> -->
										</div>
										<div class="col-6">
											<div id="panel_cta_venta" style="display:none">
												<input type="text" class="form-control form-control-sm" id="MBoxCta_Venta" name="MBoxCta_Venta"placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">				 			           
												<!-- <label for="inputEmail3" class="col-sm-5 control-label"> </label>
												<div class="col-sm-7">
												</div>				    		 -->
											</div>				    	    	
										</div>				     

									</div>
									<?php 
										if($CodEntidad == "1"){
											echo '<div class="row mt-1">
													<div class="col-12">
														<div class="form-check">
															<input class="form-check-input" type="checkbox" name="CheqPuntoEmision" id="CheqPuntoEmision">
															<label class="form-check-label" for="CheqPuntoEmision">
																Bloquear/Desbloquear Punto de Emisión
															</label>
														</div>
														
													</div>
												</div>';
										}
									?>
									
									<div class="row">
										<div class="col-lg-12">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="CheqMes" id="CheqMes">
												<label class="form-check-label" for="CheqMes">
													Facturacion por Meses
												</label>
											</div>
											<!-- <label><input type="checkbox" name="CheqMes" id="CheqMes"> Facturacion por Meses</label> -->
										</div>
									</div>
									<div class="row">
										<div class="col-xxl-6 col-lg-12 mt-1">
											<div class="input-group input-group-sm">
												<!-- <label for="inputEmail3" class="col-sm-5 control-label">TIPO DE DOCUMENTO</label> -->
												<span class="input-group-text">TIPO DE DOCUMENTO</span>
												<select class="form-select form-select-sm" id="CTipo" name="CTipo">
													<option value="FA">FA</option>
													<option value="NV">NV</option>
													<option value="PV">PV</option>
													<option value="FT">FT</option>
													<option value="NC">NC</option>
													<option value="LC">LC</option>
													<option value="GR">GR</option>
													<option value="CP">CP</option>
												</select>
												<!-- <div class="col-sm-7">
												</div> -->
											</div>	
										</div>				     	
										<div class="col-xxl-6 col-lg-12 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">NUMERO DE FACTURAS POR PAGINAS</span>
												<input type="text" class="form-control form-control-sm" id="TxtNumFact" name="TxtNumFact" placeholder="Email" value="00">
												<!-- <label for="inputEmail3" class="col-sm-7 control-label">NUMERO DE FACTURAS POR PAGINAS</label>
												<div class="col-sm-5">
												</div> -->
											</div>	
										</div>
										<div class="col-xxl-6 col-lg-12 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">ITEMS POR FACTURA</span>
												<input type="text" class="form-control form-control-sm" id="TxtItems" name="TxtItems" placeholder="Email" value="0.00">
												<!-- <label for="inputEmail3" class="col-sm-5 control-label">ITEMS POR FACTURA</label>
												<div class="col-sm-7">
												</div> -->
											</div>	
										</div>
										<div class="col-xxl-6 col-lg-12 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">FORMATO GRAFICO DOCUMENTO (.GIF)</span>
												<input type="text" class="form-control form-control-sm" id="TxtLogoFact" name="TxtLogoFact" placeholder="Email">
												<!-- <label for="inputEmail3" class="col-sm-5 control-label">FORMATO GRAFICO DEL DOCUMENTO (EXTENSION:GIF)</label>
												<div class="col-sm-7">
												</div> -->
											</div>	
										</div>				     	
									</div>
									<div class="row">
										<div class="col-lg-12 mt-1">
											<b>ESPACIO Y POSICION DE LA COPIA DE LA FACTURA / NOTA DE VENTA</b>
										</div>
										<div class="col-xxl-6 col-lg-12 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">POSICION X DE LA FACTURA</span>
												<!-- <label for="inputEmail3" class="col-sm-5 control-label">POSICION X DE LA FACTURA</label> -->
												<input type="text" class="form-control form-control-sm" id="TxtPosFact" name="TxtPosFact" placeholder="Email" value="0.00">
												<!-- <div class="col-sm-7">
												</div> -->
											</div>	
										</div>
										<div class="col-xxl-6 col-lg-12 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">POSICION Y DE LA FACTURA</span>
												<!-- <label for="inputEmail3" class="col-sm-5 control-label">POSICION Y DE LA FACTURA</label> -->
												<input type="text" class="form-control form-control-sm" id="TxtPosY" name="TxtPosY" placeholder="" value="0.00">
												<!-- <div class="col-sm-7">
												</div> -->
											</div>	
										</div>
										<div class="col-xxl-6 col-lg-12 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">ESPACIO ENTRE LA FACTURA</span>
												<!-- <label for="inputEmail3" class="col-sm-5 control-label">ESPACIO ENTRE LA FACTURA</label> -->
												<input type="text" class="form-control form-control-sm" id="TxtEspa" name="TxtEspa" placeholder="" value="0.00">
												<!-- <div class="col-sm-7">
												</div> -->
											</div>	
										</div>
										<div class="col-xxl-6 col-lg-12 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">LARGO</span>
												<input type="text" class="form-control form-control-sm" id="TxtLargo" name="TxtLargo" placeholder="" value="0.00">
												<span class="input-group-text"><b>X</b></span>
												<span class="input-group-text">ANCHO</span>
												<input type="text" class="form-control form-control-sm" id="TxtAncho" name="TxtAncho" placeholder="" value="0.00">
												<!-- <label for="inputEmail3" class="col-sm-2 control-label">LARGO</label>
												<div class="col-sm-3">
												</div>
												<label for="inputEmail3" class="col-sm-2 control-label">X</label>
												<label for="inputEmail3" class="col-sm-2 control-label">ANCHO</label>
												<div class="col-sm-3">
												</div> -->

											</div>	
										</div>
										
									</div>
									
								</div>
								<div id="menu1" class="tab-pane fade" role="tabpanel" aria-labelledby="menu1-tab" tabindex="0">
									<div class="row">
										<div class="col-lg-12 mt-1">
											<h4>DATOS DEL S.R.I. DE LA FACTURA / NOTA DE VENTA</h4>
										</div>
										<div class="col-xxl-6 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">FECHA DE INICIO</span>
												<input type="date" class="form-control form-control-sm" id="MBFechaIni" name="MBFechaIni" placeholder="" value="<?php echo date('Y-m-d');?>" >
												<!-- <label for="inputEmail3" class="col-sm-5 control-label">FECHA DE INICIO</label>
												<div class="col-sm-7">
												</div> -->
											</div>	
										</div>
										<div class="col-xxl-6 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">SECUENCIAL DE INICIO</span>
												<input type="text" class="form-control form-control-sm" id="TxtNumSerietres1" name="TxtNumSerietres1" placeholder="" value="000001">
												<!-- <label for="inputEmail3" class="col-sm-5 control-label">SECUENCIAL DE INICIO</label>
												<div class="col-sm-7">
												</div> -->
											</div>	
										</div>
										<div class="col-xxl-6 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">FECHA DE VENCIMIENTO</span>
												<input type="date" class="form-control form-control-sm" id="MBFechaVenc" name="MBFechaVenc" placeholder="" value="<?php echo date('Y-m-d');?>">
												<!-- <label for="inputEmail3" class="col-sm-5 control-label">FECHA DE VENCIMIENTO</label>
												<div class="col-sm-7">
												</div> -->
											</div>	
										</div>
										<div class="col-xxl-6 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">AUTORIZACION</span>
												<input type="text" class="form-control form-control-sm text-right" id="TxtNumAutor" name="TxtNumAutor" placeholder="" value="0000000001">
												<!-- <label for="inputEmail3" class="col-sm-5 control-label">AUTORIZACION</label>
												<div class="col-sm-7">
												</div> -->
											</div>	
										</div>
										<div class="col-sm-12 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">SERIE DE FACTURA / NOTA DE VENTA (ESTAB. Y PUNTO DE VENTA)</span>
												<input type="text" class="form-control form-control-sm" id="TxtNumSerieUno" name="TxtNumSerieUno" placeholder="" value="001">
												<input type="text" class="form-control form-control-sm" id="TxtNumSerieDos" name="TxtNumSerieDos" placeholder="" value="001">
												<!-- <label for="inputEmail3" class="col-sm-8 control-label">SERIE DE FACTURA / NOTA DE VENTA (ESTAB. Y PUNTO DE VENTA)</label>
												<div class="col-sm-2">
												</div>
												<div class="col-sm-2">
												</div> -->
											</div>	
										</div>
									</div>
									<div class="row mt-2">
										<h4>DATOS DEL ESTABLECIMIENTO</h4>
										<div class="col-sm-12 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">NOMBRE DEL ESTABLECIMIENTO</span>
												<!-- <label for="inputEmail3" class="col-sm-1 control-label">NOMBRE DEL ESTABLECIMIENTO</label> -->
												<input type="text" class="form-control form-control-sm" id="TxtNombreEstab" name="TxtNombreEstab" placeholder="" value=".">
											</div>	
											<!-- <B>NOMBRE DEL ESTABLECIMIENTO</B> -->
										</div>
										<div class="col-sm-12 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">DIRECCION</span>
												<input type="text" class="form-control form-control-sm" id="TxtDireccionEstab" name="TxtDireccionEstab" placeholder="" value=".">
												<!-- <label for="inputEmail3" class="col-sm-1 control-label">DIRECCION</label>
												<div class="col-sm-11">
												</div> -->
											</div>	
										</div>
										<div class="col-sm-6 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">TELEFONO</span>
												<input type="text" class="form-control form-control-sm" id="TxtTelefonoEstab" name="TxtTelefonoEstab" placeholder="" value=".">
												<!-- <label for="inputEmail3" class="col-sm-2 control-label">TELEFONO</label>
												<div class="col-sm-10">
												</div> -->
											</div>	
										</div>
										<div class="col-sm-6 mt-1">
											<div class="input-group input-group-sm">
												<span class="input-group-text">LOGOTIPO(GIF)</span>
												<input type="text" class="form-control form-control-sm" id="TxtLogoTipoEstab" name="TxtLogoTipoEstab" placeholder="" value=".">
												<!-- <label for="inputEmail3" class="col-sm-3 control-label">LOGOTIPO(GIF)</label>
												<div class="col-sm-9">
												</div> -->
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
	</div>
	<!-- <div class="col-sm-5">
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
		
	</div> -->
</div>
