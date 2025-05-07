<?php
date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION);die();//print_r($_SESSION['INGRESO']);die();?>
<link rel="stylesheet" href="../../dist/css/arbol.css">
<script type="text/javascript" src="../../dist/js/empresa/cambioe.js" ></script>
<div class="pb-4">
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3">
			<?php echo $NombreModulo; ?>
		</div>
		<div class="ps-3">
			<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
			<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
			</ol>
			</nav>
		</div>          
	</div>
	<div class="row row-cols-auto">
		<div class="btn-group">
			<a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" data-bs-toggle="tooltip" title="Salir de modulo" class="btn btn-outline-secondary btn-sm">
				<img src="../../img/png/salire.png">
			</a>
			<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Email masivo todas las empresas" onclick='emasivo();'><img src="../../img/png/email.png"></button>
			<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Mensaje masivo todas las empresas" onclick='mmasivo();'><img src="../../img/png/masivo.png"></button>
			<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Mensaje masivo a grupo seleccionado" onclick='mgrupo();'><img src="../../img/png/email_grupo.png" ></button>
			<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Mensaje solo a empresa" onclick='mindividual();'><img src="../../img/png/mensajei.png" ></button>
			<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Guardar" onclick="cambiarEmpresa();"><img src="../../img/png/grabar.png"></button>
			<div>
				<button type="button" id="btnLineasGrabar" class="btn btn-outline-secondary btn-sm rounded-0" disabled data-bs-toggle="tooltip" title="Actualizar Puntos de Emisión" onclick="confirmar()"><img src="../../img/png/grabar_lineascxc.png"></button>
			</div>
			<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Guardar Masivo: Fechas de renovaciones" onclick='cambiarEmpresaMa();'><img src="../../img/png/guardarmasivo.png"></button>
			<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Guardar Masivo: Fechas de Comprobantes electronicos" onclick='cambiarEmpresaMaFechaComElec();'><img src="../../img/png/guardarmasivo.png"></button>
	        
			<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Mostrar Vencimiento" onclick='mostrarEmpresa();'><img src="../../img/png/reporte_1.png"></button>
			<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Asignar credenciales de comprobanmtes electronicos" onclick='asignar_clave();'><img src="../../img/png/credencial_cliente.png"></button>
			<a href="#" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" style="display: none;" title="Asignar reserva" id="reporte_exc" onclick="reporte()"><img img src="../../img/png/table_excel.png"></a>
		</div>
	</div>
		<div id="form_vencimiento" style="display:none;">
			<div class="row row-cols-auto">
				<div>
					<button class="btn btn-danger btn-sm" type="button" onclick="cerrarEmpresa()">
						<i class="bx bx-close"></i>Cerrar
					</button>
				</div>
				<div class="row row-cols-auto">
					<div><b>Desde:</b></div>
					<div>
						<input type="date" class="form-control form-control-sm" id="desde" value="<?php echo date("Y-m-d");?>"  onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
					</div>
				</div>
				<div class="row row-cols-auto">
					<div><b>Hasta:</b></div>
					<div>
						<input type="date" id="hasta"  class="form-control form-control-sm"  value="<?php echo date("Y-m-d");?>"  onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);consultar_datos();">
					</div>
				</div>		
			</div>
			<div class='col-12-sm'>
				<div class="table-responsive overflow-y-auto w-100" style="max-height: 400px;">
					<table class="table text-sm"id="tbl_vencimiento">
						<thead>
							<tr>
								<th class="text-center" >Tipo</th>
								<th class="text-center" >Item</th>
								<th class="text-center" >Empresa</th>
								<th class="text-center" >Fecha</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<form id="form_encabezados">
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label>Entidad: </label><i id="lbl_ruc"></i>-<i id="lbl_enti"></i>
					<select class="form-select form-select-sm" name="entidad" id='entidad' onchange="confirmarCambioEntidad('entidad')">
						<option value=''>Seleccione Entidad</option>
					</select>
				</div>			
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					<label for="Entidad">Ciudad</label>
					<select class="form-select form-select-sm" name="ciudad" id='ciudad' onchange="confirmarCambioEntidad('ciudad')">
						<option value=''>Seleccione ciudad</option>
					</select>
				</div>			
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="Entidad">Empresa (<span id="span_item_empresa">.</span>)</label>
					<select class="form-select form-select-sm" name="empresas" id='empresas' onchange="confirmarCambioEntidad('empresas')">
						<option value=''>Seleccione Empresa</option>
					</select>
				</div>			
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					<label for="Entidad">CI / RUC </label>
					<input type="text" class="form-control form-control-sm" name="ci_ruc" id="ci_ruc" readonly>
					<input type="hidden" name="txt_sqlserver" id="txt_sqlserver" value="0">
				</div>			
			</div>
		</div>
		</form>

		<form id="form_empresa">
		<div class="row" id="datos_empresa">
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-pills">					
						<li id="li_tab1"><a class="nav-link active" href="#tab_1" data-bs-toggle="tab" aria-expanded="true">Configuracion Principal</a></li>
						<li id="li_tab2"><a class="nav-link" href="#tab_2" data-bs-toggle="tab" aria-expanded="false">Datos Principales</a></li>
						<li id="li_tab3"><a class="nav-link" href="#tab_3" data-bs-toggle="tab" aria-expanded="false">Procesos Generales</a></li>
						<li id="li_tab5"><a class="nav-link" href="#tab_5" data-bs-toggle="tab" aria-expanded="false">Lineas de CxC (Puntos de Emisión)</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1">
							<div class="row">
								<div class="col-sm-12 row">
									<div class="col-sm-3 col-lg-3 row">
										<div class="form-group">
											<label for="Estado">Estado</label>
											<select class="form-select form-select-sm" name="Estado" id="Estado" >
												<option value=''>Estado</option>
												<option value="0">Seleccione Estado</option>
												<!-- $op.= $this->estados(); -->
											</select>
										</div>
									</div>
									<div class="col-sm-6 col-lg-5 row" style="padding:0;">
										<div class="col-sm-4 col-lg-4">
											<div class="form-group">
											<label for="FechaR">Renovación</label>
											
											<input type="date" class="form-control form-control-sm" id="FechaR" name="FechaR" placeholder="FechaR" 
											value='' onKeyPress="return soloNumeros(event)"  maxlength="10" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
											</div>
										</div>
										<div class="col-sm-4 col-lg-5 row">
											<div class="form-group">
											<label for="Fecha">Comp. Electronico</label>								   
											<input type="date" class="form-control form-control-sm" id="FechaCE" name="FechaCE" placeholder="Fecha" 
											value="" onKeyPress="return soloNumeros(event)" maxlength="10" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
											</div>
										</div>							
										<div class="col-sm-4 col-lg-4 row">
											<div class="form-group">
											<label for="Fecha_DB">BD</label>
											<input type="date" class="form-control form-control-sm" id="FechaDB" name="FechaDB" value="">
											</div>
										</div>
									</div>
									<div class="col-sm-3 col-lg-4 row">
										<div class="col-sm-6 col-lg-7 row">
											<div class="form-group">
											<label for="Fecha_P12">Fecha P12</label>
											<input type="date" class="form-control form-control-sm" id="FechaP12" name="FechaP12" value="">
											</div>
										</div>
										<div class="col-sm-6 col-lg-6 row">
											<div class="form-group">
												<label for="Plan">Plan</label>
												
												<input type="text" class="form-control form-control-sm" id="Plan" name="Plan" placeholder="Plan" value="">
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-12 row">
									<div class="col-lg-2 row">
										<div class="form-group">
										<label for="Servidor">Servidor</label>
										<input type="text" class="form-control form-control-sm" id="Servidor" name="Servidor" placeholder="Servidor" value="">
										</div>
									</div>
									<div class="col-lg-3 row">
										<div class="form-group">
										<label for="Base">Base</label>
										<input type="text" class="form-control form-control-sm" id="Base" name="Base" placeholder="Base" value="">
										</div>
									</div>
									<div class="col-lg-7 row">
										<div class="col-lg-3">
											<div class="form-group">
											<label for="Usuario">Usuario</label>								   
											<input type="text" class="form-control form-control-sm" id="Usuario" name="Usuario" placeholder="Usuario" value="">
											</div>
										</div>
										<div class="col-lg-3">
											<div class="form-group">
											<label for="Clave">Clave</label>
											<input type="text" class="form-control form-control-sm" id="Clave" name="Clave" placeholder="Clave" value="">
											</div>
										</div>
										
										<div class="col-lg-3">
											<div class="form-group">
											<label for="Motor">Motor BD</label>
											<input type="text" class="form-control form-control-sm" id="Motor" name="Motor" placeholder="Motor" value="">
											</div>
										</div>
										<div class="col-lg-3">
											<div class="form-group">
											<label for="Puerto">Puerto</label>
											
											<input type="text" class="form-control form-control-sm" id="Puerto" name="Puerto" placeholder="Puerto" value="">
											</div>
										</div>				
									</div>
								</div>
								<div class="col-sm-12">
									<div class="col-sm-12">
										<div class="form-group">
										<label for="Mensaje">Mensaje</label>
										<input type="text" class="form-control form-control-sm" id="Mensaje" name="Mensaje" placeholder="Mensaje" value="">
										</div>
									</div>
								</div>	        
							</div>
							<div class="col-sm-12" style="font-size:16pt;font-weight:800;padding-left:0"><b style="background-color: #c6dcf9;padding: 5px;border: 1px solid #999999;border-radius: 5px 5px 0 0;">Comprobantes Electrónicos</b></div>
							<div class="row">
								<div class="col-md-12" style="background-color:#c6dcf9;margin: 0 5px 5px 5px;padding:10px;border: 1px solid #999999;border-radius: 5px;">
									
									<div class="row">
										<div class="col-sm-3"><label>WEBSERVICE SRI RECEPCION</label></div>
									<div class="col-sm-2">                                    
									<label><input class="form-check-input" type="radio" name="optionsRadios" id="optionsRadios1" value="option1" onclick="AmbientePrueba()">
										Ambiente de Prueba</label>
									</div>
									<div class="col-sm-3">
										<label><input class="form-check-input" type="radio" name="optionsRadios" id="optionsRadios2" value="option2" onclick="AmbienteProduccion()">
										Ambiente de Producción</label>
									</div>
								<div class="col-sm-2">Contribuyente Especial</div>
								<div class="col-sm-2">
									<input type="text" name="TxtContriEspecial" id="TxtContriEspecial" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-12">
									<input type="text" name="TxtWebSRIre" id="TxtWebSRIre" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-12">
									<label>WEBSERVICE SRI AUTORIZACIÓN</label>
										<input type="text" name="TxtWebSRIau" id="TxtWebSRIau" class="form-control form-control-sm" value="">
								</div>           
								<div class="col-sm-10">
									<label>CERTIFICADO FIRMA ELECTRONICA (DEBE SER EN FORMATO DE EXTENSION P12)</label>
									<div class="input-group">

										<input type="text" name="TxtEXTP12" id="TxtEXTP12" class="form-control form-control-sm" value="" >
										<span class="input-group-addon">
											<input type="file" class="btn btn-sm"  id="file_firma" data-placeholder="Elegir imágen..." name="file_firma" />
										</span>
										<!-- <span class="input-group-btn">
											<button type="button" class="btn btn-info btn-flat btn-sm" onclick="subir_firma()">Subir firma</button>
										</span> -->
									</div>
								</div>
								<div class="col-sm-2">
									<label>CONTRASEÑA:</label>
									<input type="text" name="TxtContraExtP12" id="TxtContraExtP12" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-4">
									<label>EMAIL PARA PROCESOS GENERALES:</label>
									<input type="text" name="TxtEmailGE" id="TxtEmailGE" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-2">
									<label>CONTRASEÑA:</label>
									<input type="text" name="TxtContraEmailGE" id="TxtContraEmailGE" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-4">
									<label>EMAIL PARA DOCUMENTOS ELECTRONICOS:</label>
									<input type="text" name="TxtEmaiElect" id="TxtEmaiElect" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-2">
									<label>CONTRASEÑA:</label>
									<input type="text" name="TxtContraEmaiElect" id="TxtContraEmaiElect" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-10">
								<label><input type="checkbox" id="rbl_copia" name="rbl_copia">Enviar Copia de Email</label>';
								<input type="text" name="TxtCopiaEmai" id="TxtCopiaEmai" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-2">
									<label>RUC Operadora</label>
									<input type="text" name="TxtRUCOpe" id="TxtRUCOpe" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-6">                            
										<label>LEYENDA AL FINAL DE LOS DOCUMENTOS ELECTRONICOS</label>
										<textarea name="txtLeyendaDocumen" id="txtLeyendaDocumen"class="form-control" rows="2" resize="none"></textarea>                            
								</div>
								<div class="col-sm-6">
									<label>LEYENDA AL FINAL DE LA IMPRESION EN LA IMPRESORA DE PUNTO DE VENTA DE DOCUMENTOS ELECTRÓNICOS</label><br>                            
									<textarea name="txtLeyendaImpresora" id="txtLeyendaImpresora"class="form-control" rows="2" resize="none"></textarea>
								</div>
								</div>
								</div>
							</div>
						</div>

						<div class="tab-pane " id="tab_2">
							<div class="row">
								<div class="col-sm-2">
									<label>EMPRESA:</label>
									<label id="lbl_item" name=></label>
								</div>
								<div class="col-sm-10">                                
									<input type="text" name="TxtEmpresa" id="TxtEmpresa" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-2">
									<label hidden="hidden">ITEM:</label>
								</div>
								<div class="col-sm-10" style="display:none;">                                
									<input type="text" name="TxtItem" id="TxtItem" class="form-control form-control-sm" value="">
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<label>RAZON SOCIAL:</label>
								</div>
								<div class="col-sm-10">
									<input type="text" name="TxtRazonSocial" id="TxtRazonSocial" class="form-control form-control-sm" value="">
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<label>NOMBRE COMERCIAL:</label>
								</div>
								<div class="col-sm-10">
									<input type="text" name="TxtNomComercial" id="TxtNomComercial" class="form-control form-control-sm" value="">
								</div>
							</div>                
							<div class="row">
								<div class="col-sm-2">
									<label>RUC:</label>
									<input type="text" name="TxtRuc" id="TxtRuc" class="form-control form-control-sm" value="" onkeyup="num_caracteres('TxtRuc',13)" autocomplete="off">
								</div>
								<div class="col-sm-2">
									<label>OBLIG</label>
									<select class="form-select form-select-sm" id="ddl_obli" name="ddl_obli">
										<option value="">Seleccione</option>
										<option value="SI">SI</option>
										<option value="NO">NO</option>
									</select>
								</div>
								<div class="col-sm-6">
									<label>REPRESENTANTE LEGAL:</label>
									<input type="text" name="TxtRepresentanteLegal" id="TxtRepresentanteLegal" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-2">
									<label>C.I/PASAPORTE</label>
									<input type="text" name="TxtCI" id="TxtCI" class="form-control form-control-sm" value="" onkeyup="num_caracteres('TxtCI',10)" autocomplete="off">
								</div>
							</div>                    
							<div class="row">
								<div class="col-sm-4">
									<label>NACIONALIDAD</label>
									<select class="form-select form-select-sm" id="ddl_naciones" name="ddl_naciones" onchange="provincias(this.value)">
										<option value="">Seleccione</option>
									</select>
								</div>
								<div class="col-sm-4">
									<label>PROVINCIA</label>
									<select class="form-select form-select-sm  id="prov" name="prov" onchange="ciudad_l(this.value)">
										<option value="">Seleccione una provincia</option>		                           
									</select>
								</div>
								<div class="col-sm-4">
									<label>CIUDAD</label>
									<select class="form-select form-select-sm" id="ddl_ciudad" name="ddl_ciudad">
										<option value="">Seleccione una ciudad</option>		                            
									</select>
								</div>                        
							</div>                    
							<div class="row">
								<div class="col-sm-10">
									<label>DIRECCION MATRIZ:</label>
									<input type="text" name="TxtDirMatriz" id="TxtDirMatriz" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-2">
									<label>ESTA.</label>
									<input type="text" name="TxtEsta" id="TxtEsta" class="form-control form-control-sm" value="">
								</div>                        
							</div>
							<div class="row">
								<div class="col-sm-2">
									<label>TELEFONO:</label>
									<input type="text" name="TxtTelefono" id="TxtTelefono" class="form-control form-control-sm" value="" onkeyup="num_caracteres(this.id,10)" onblur="num_caracteres(this.id,10)">
								</div>
								<div class="col-sm-2">
									<label>TELEFONO 2:</label>
									<input type="text" name="TxtTelefono2" id="TxtTelefono2" class="form-control form-control-sm" value="" onkeyup="num_caracteres(this.id,10)" onblur="num_caracteres(this.id,10)">
								</div>
								<div class="col-sm-1">
									<label>FAX:</label>
									<input type="text" name="TxtFax" id="TxtFax" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-1">
									<label>MONEDA</label>
									<input type="text" name="TxtMoneda" id="TxtMoneda" class="form-control form-control-sm" value="USD">
								</div>
								<div class="col-sm-2">
									<label>NO. PATRONAL:</label>
									<input type="text" name="TxtNPatro" id="TxtNPatro" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-1">
									<label>COD.BANCO</label>
									<input type="text" name="TxtCodBanco" id="TxtCodBanco" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-1" style="padding: 0 0 0 8px;">
									<label>TIPO CAR.</label>
									<input type="text" name="TxtTipoCar" id="TxtTipoCar" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-2">
									<label>ABREVIATURA</label>
									<input type="text" name="TxtAbrevi" id="TxtAbrevi" class="form-control form-control-sm" value="">
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<label>EMAIL DE LA EMPRESA:</label>
									<input type="text" name="TxtEmailEmpre" id="TxtEmailEmpre" class="form-control form-control-sm" value="">
								</div>                        
							</div>
							<div class="row">
								<div class="col-sm-12">
									<label>EMAIL DE CONTABILIDAD:</label>
									<input type="text" name="TxtEmailConta" id="TxtEmailConta" class="form-control form-control-sm" value="">
								</div>                        
							</div>
							<div class="row">
								<div class="col-sm-6">
									<label>EMAIL DE RESPALDO:</label>
									<input type="text" name="TxtEmailRespa" id="TxtEmailRespa" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-4 text-center">
									<label>SEGURO DESGRAVAMEN %</label>
									<div class="row">
										<div class="col-sm-6">
											<input type="text" name="TxtSegDes1" id="TxtSegDes1" class="form-control form-control-sm" value="">
										</div>
										<div class="col-sm-6">
											<input type="text" name="TxtSegDes2" id="TxtSegDes2" class="form-control form-control-sm" value="">
										</div>
									</div>                        
								</div>
								<div class="col-sm-2">
									<label>SUBDIR:</label>
									<input type="text" name="TxtSubdir" id="TxtSubdir" class="form-control form-control-sm" value="" onblur="subdireccion()" onkeyup="mayusculas('TxtSubdir',this.value);">
								</div>
							</div>
							<div class="row">
								<div class="col-sm-10">
									<label>NOMBRE DEL CONTADOR</label>
									<input type="text" name="TxtNombConta" id="TxtNombConta" class="form-control form-control-sm" value="">
								</div>
								<div class="col-sm-2">
									<label>RUC CONTADOR:</label>
									<input type="text" name="TxtRucConta" id="TxtRucConta" class="form-control form-control-sm" value="" onkeyup="num_caracteres('TxtRucConta',13)" autocomplete="off">
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab_3">


							<div class="row">
								<div class="col-md-4" style="background-color:#ffe0c0">                                   
								<!-- setesos -->
									<label class="fw-bold">|Seteos Generales|</label>
									<div class="checkbox">
										<label><input class="form-check-input" type="checkbox" name="ASDAS" id="ASDAS">Agrupar Saldos Detalle Auxiliar de Submodulos</label>
									</div>
									<div class="checkbox">
										<label><input class="form-check-input" type="checkbox" name="MFNV" id="MFNV">Modificar Facturas o Notas de Venta</label>
									</div>
									<div class="checkbox">
										<label><input class="form-check-input" type="checkbox" name="MPVP" id="MPVP">Modificar Precio de Venta al Público</label>
									</div>
									<div class="checkbox">
										<label><input class="form-check-input" type="checkbox" name="IRCF" id="IRCF">Imprimir Recibo de Caja en Facturación</label>
									</div>
									<div class="checkbox">
										<label><input class="form-check-input" type="checkbox" name="IMR" id="IMR">Imprimir Medio Rol</label>
									</div>
									<div class="checkbox">
										<label><input class="form-check-input" type="checkbox" name="IRIP" id="IRIP">Imprimir dos Roles Individuales por pagina</label>
									</div>
									<div class="checkbox">
										<label><input class="form-check-input" type="checkbox" name="PDAC" id="PDAC" >Procesar Detalle Auxiliar de Comprobantes</label>
									</div>
									<div class="checkbox">
										<label><input class="form-check-input" type="checkbox" name="RIAC" id="RIAC">Registrar el IVA en el Asiento Contable</label>
									</div>
									<div class="checkbox">
										<label><input class="form-check-input" type="checkbox" name="FCMS" id="FCMS">Funciona como Matriz de Sucursales</label>
									</div>
								</div>
								<div class="col-md-4">                                        
									<label class="fw-bold">LOGO TIPO </label>
									<!-- llenar con contenuido de la carpeta logotipos -->

									<input type="text" name="TxtXXXX" id="TxtXXXX" class="form-control form-control-sm" value="XXXXXXXXXX">
									<div class="" rows="11">                                        
										<select class="form-control form-control-sm" onchange="cargar_img()" id="ddl_img" name="ddl_img" row="11" multiple></select>                                                
									</div>
									<div class="row">
										<div class="col-sm-10">
											<input class="btn btn-sm" type="file" id="file_img" name="file_img" />
										</div>
										<div class="col-sm-2">
											<button type="button" class="btn btn-primary btn-sm" id="subir_imagen" onclick="subir_img()" >Cargar</button>                        	
										</div>
									</div>
									
								</div>
								<div class="col-md-4">                                        
									<div class="box-body">
									<img src="../../img/logotipos/sin_img.jpg" id="img_logo" name="img_logo" style="width:316px;height:158px; border:1px solid"/>
									<p><b>Nombre: </b><span id="img_foto_name"></span></p>   
									</div>                                     
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label class="fw-bold">|Numeración de Comprobantes|</label>
									<div class="row">
										<div class="col-sm-6">
											<label><input class="form-check-input" type="radio" name="dm1" id="DM" value="1"  onclick="DiariosM()">Diarios por meses</label>
										</div>
										<div class="col-sm-6">
											<label><input class="form-check-input" type="radio" name="dm1" id="DS" value="0" onclick="DiariosS()">Diarios secuenciales</label>                                
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<label><input class="form-check-input" type="radio" name="dm2" id="IM"  value="1" onclick="IngresosM()">Ingresos por meses</label>
										</div>
										<div class="col-sm-6">
											<label><input class="form-check-input" type="radio" name="dm2" id="IS"  value="0" onclick="IngresosS()">Ingresos secuenciales</label>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<label><input class="form-check-input" type="radio" name="dm3" id="EM"  value="1" onclick="EgresosM()">Egresos por meses</label>
										</div>
										<div class="col-sm-6">
											<label> <input class="form-check-input" type="radio" name="dm3" id="ES"  value="0" onclick="EgresosS()">Egresos secuenciales</label>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<label><input class="form-check-input" type="radio" name="dm4" id="NDM" value="1" onclick="NDPM()">N/D por meses</label>
										</div>
										<div class="col-sm-6">
											<label><input class="form-check-input" type="radio" name="dm4" id="NDS" value="0" onclick="NDPS()">N/D secuenciales</label>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<label><input class="form-check-input" type="radio" name="dm5" id="NCM" value="1" onclick="NCPM()">N/C por meses</label>
										</div>
										<div class="col-sm-6">
											<label><input class="form-check-input" type="radio" name="dm5" id="NCS"  value="0" onclick="NCPS()">N/C secuenciales</label>
										</div>
									</div>
								</div>
								<div class="col-sm-8" style="background-color:#ffffc0">                        
									<div class="row">
										<div class="col-sm-12">
											<b>|Servidor de Correos|</b>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-10" style="background-color:#ffffc0">
											<b>Servidor SMTP</b>
											<input type="text" name="TxtServidorSMTP" id="TxtServidorSMTP" class="form-control form-control-sm" value="">
										</div>
										<!-- <div class="col-sm-2" style="background-color:#ffffc0">
											<div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
											<button type="button" class="btn btn-default" title="Grabar Empresa" onclick="()">
												<img src="../../img/png/grabar.png">
											</button>
										</div>
										</div> -->
									</div>
									<div class="row" style="background-color:#ffffc0">
										<div class="col-sm-3">
											<input class="form-check-input" type="checkbox" name="Autenti" id="Autenti">Autentificación
										</div>
										<div class="col-sm-2">
											<input class="form-check-input" type="checkbox" name="SSL" id="SSL">SSL
										</div>
										<div class="col-sm-2">
											<input class="form-check-input" type="checkbox" name="Secure" id="Secure">SECURE
										</div>
										<div class="col-sm-1">
											PUERTO
										</div>
										<div class="col-sm-2">
											<input type="text" name="TxtPuerto" id="TxtPuerto" class="form-control form-control-sm">
										</div>
									</div>
									<div class="row">
										<div class="col-sm-8">
											<input class="form-check-input" type="checkbox" name="AsigUsuClave" id="AsigUsuClave" onclick="MostrarUsuClave()">ASIGNA USUARIO Y CLAVE DEL REPRESENTANTE LEGAL
										</div>
										<div class="col-sm-2">
											<label id="lblUsuario" style="display:none" >USUARIO</label>
										</div>
										<div class="col-sm-2">
											<input type="text" name="TxtUsuario" id="TxtUsuario" class="form-control form-control-sm" value="USUARIO"  style="display:none" > 
										</div>
									</div>
									<div class="row">
										<div class="col-sm-8">
											<input class="form-check-input" type="checkbox" name="CopSeEmp" id="CopSeEmp" onclick="MostrarEmpresaCopia()">COPIAR SETEOS DE OTRA EMPRESA
										</div>
										<div class="col-sm-2">
											<label id="lblClave"  style="display:none" >CLAVE</label>
										</div>
										<div class="col-sm-2">
											<input type="text" name="TxtClave" id="TxtClave" class="form-control form-control-sm" style="display:none" >
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<select class="form-select form-select-sm" id="ListaCopiaEmpresa" name="ListaCopiaEmpresa"width="100%" >
												<option value="">Empresa</option>
											</select>
										</div>
									</div>
									
								</div>
						</div>
						<div class="row">
							<div class="col-sm-4" style="background-color:#c0ffc0">
								<div class="row">
									<label class="fw-bold">|Cantidad de Decimales en|</label>
								</div>   
							<div class="row">
								<div class="col-md-3" style="background-color:#c0ffc0">
									P.V.P
									<input type="text" name="TxtPVP" id="TxtPVP" class="form-control form-control-sm" value="">
								</div>
								<div class="col-md-3" style="background-color:#c0ffc0">
									COSTOS
									<input type="text" name="TxtCOSTOS" id="TxtCOSTOS" class="form-control form-control-sm" value="">
								</div>
								<div class="col-md-3" style="background-color:#c0ffc0">
									I.V.A
									<input type="text" name="TxtIVA" id="TxtIVA" class="form-control form-control-sm" value="">
								</div>
								<div class="col-md-3" style="background-color:#c0ffc0">
									CANTIDAD
									<input type="text" name="TxtCantidad" id="TxtCantidad" class="form-control form-control-sm" value="">
								</div>
							</div>
								
							</div>
							<div class="col-sm-8" style="background-color:#c0ecff">
									<div class="row" >
										<div class="col-sm-12" >
											<b>|Tipo Contibuyente|</b>
										</div>
									</div>
									
									<div class="row">
										<div class="col-sm-4">
											<b>RUC</b>
											<input type="text" name="TxtRucTipocontribuyente" id="TxtRucTipocontribuyente" class="form-control form-control-sm" value="" readonly>
										</div>
										<div class="col-sm-3">
											<b>Zona</b>
											<input type="text" name="TxtZonaTipocontribuyente" id="TxtZonaTipocontribuyente" class="form-control form-control-sm" value="">
										</div>
										<div class="col-sm-4">
											<b>Agente de retencion</b>
											<input type="text" name="TxtAgentetipoContribuyente" id="TxtAgentetipoContribuyente" class="form-control form-control-sm" value="">
										</div>
										<div class="col-sm-2">
										<!--  <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
												<button type="button" class="btn btn-default" title="Grabar Empresa" onclick="guardarTipoContribuyente()">
													<img src="../../img/png/grabar.png">
												</button>
											</div> -->
										</div>
									</div>
									<div class="row">
										<label class="col-sm-4">
											<input class="form-check-input" type="checkbox" name="rbl_ContEs" id="rbl_ContEs">Contribuyente Especial
									</label>
										<label class="col-sm-2">
											<input class="form-check-input" type="checkbox" name="rbl_rimpeE" id="rbl_rimpeE">RIMPE E
										</label>
										<label class="col-sm-2">
											<input class="form-check-input" type="checkbox" name="rbl_rimpeP" id="rbl_rimpeP">RIMPE P
									</label>
										<label class="col-sm-3">
											<input class="form-check-input" type="checkbox" name="rbl_regGen" id="rbl_regGen">Regimen General
										</label>
										<label class="col-sm-2">
											<input class="form-check-input" type="checkbox" name="rbl_rise" id="rbl_rise">RISE
										</label>
										<label class="col-sm-2">
											<input class="form-check-input" type="checkbox" name="rbl_micro2020" id="rbl_micro2020">Micro 2020
									</label>
										<label class="col-sm-2">
											<input class="form-check-input" type="checkbox" name="rbl_micro2021" id="rbl_micro2021">Micro 2021
										</label>
									</div>
							</div>
							
						</div>                
							

						</div>

						<div class="tab-pane" id="tab_5">
							<div class="row" style="display:none;">
								<input type="text" id="TxtLineasItem" name="TxtLineasItem" value="">
								<input type="text" id="TxtLineasEntidad" name="TxtLineasEntidad" value="">
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="row">
										<div class="col-sm-12">
											<div class="panel border border-primary rounded">
												<div class="panel-heading bg-primary text-white" style="padding: 0px 10px 0px 10px;">
													NOMBRE DE LA CUENTA POR COBRAR
												</div>
											<!-- 	<input type="text" name="auto" id="auto">
												<input type="text" name="serie" id="serie">
												<input type="text" name="serie" id="serie">
												<input type="text" name="tipo" id="tipo"> -->
												<input type="hidden" name="txt_anterior" id="txt_anterior">
												<div class="panel-body text-sm" id="tree1">

												</div>
											</div>
										</div>
									</div>
									<!--<div class="row">
										<div class="col-sm-12">
											<button type="button" id="btnLineasGrabar" class="btn btn-default" title="Grabar factura" onclick="confirmar()" disabled>
												<img src="../../img/png/grabar.png"><br>
												&nbsp; &nbsp;&nbsp;  Grabar&nbsp; &nbsp; &nbsp; 
												<br>
											</button>
											
										</div>
									</div>-->
								</div>
								<div class="col-sm-8">
									<form id="form_datos">
										<div class="row" style="display:none;">
											<input type="hidden" id="TxtIDLinea" name="TxtIDLinea">
											<input type="hidden" id="LblTreeClick" name="LblTreeClick">
										</div>
										<div class="row">
											<div class="col-sm-5">
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label">CODIGO</label>
													<div class="col-sm-10">
														<input type="text" class="form-control form-control-sm" id="TextCodigo" name="TextCodigo" placeholder="" value="." onchange="validar_codigo();">
													</div>
												</div>	
											</div>
											<div class="col-sm-7">
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label">DESCRIPCION</label>
													<div class="col-sm-10">
														<input type="text" class="form-control form-control-sm" id="TextLinea" name="TextLinea" placeholder="NO PROCESABLE" value="NO PROCESABLE">
													</div>
												</div>	
											</div>
										</div>
										<div class="row">
											<div id="carga_linea_detalles" style="display: none; position: absolute; top: 0px; left: 0px; height: 100%; width: 100%; background-color: rgba(255, 255, 255, 0.5); z-index: 50; justify-content: center; align-items: center;">
												<img src="../../img/gif/loader4.1.gif" height="20%">
											</div>
											<div class="col-sm-12">
												<div class="box">
													<div class="box-body">
														<ul class="nav nav-pills">
														<li><a class="nav-link active" data-bs-toggle="tab" href="#home">DATOS DE PROCESO</a></li>
														<li><a class="nav-link" data-bs-toggle="tab" href="#menu1">DATOS DEL S.R.I</a></li>
														</ul>

														<div class="tab-content">
														<div id="home" class="tab-pane show active">
															<div class="row"><br>
																<div class="col-sm-6">
																	<div class="">
																	<label for="inputEmail3" class="col-sm-5 control-label">CxC Clientes (<span id="span_id_linea">.</span>)</label>
																	<div class="col-sm-7">
																		<input type="text" class="form-control form-control-sm" id="MBoxCta" name="MBoxCta" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
																	</div>
																	</div>	
																</div>
																<div class="col-sm-6">
																			<div class="form-group">
																	<label for="inputEmail3" class="col-sm-5 control-label">CxC Año Anterior</label>
																	<div class="col-sm-7">
																		<input type="text" class="form-control form-control-sm" id="MBoxCta_Anio_Anterior" name="MBoxCta_Anio_Anterior"placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">					           
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
																		<input type="text" class="form-control form-control-sm" id="MBoxCta_Venta" name="MBoxCta_Venta"placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">				 			           
																	</div>				    		
																</div>				    	    	
															</div>				     
															</div>
															<div class="row">
																<div class="col-sm-12">
																	<label><input type="checkbox" name="CheqPuntoEmision" id="CheqPuntoEmision"> Activar/Desactivar Punto de Emisión</label>
																</div>
															</div>
															<div class="row">
																	<div class="col-sm-6">
																	<label><input type="checkbox" name="CheqMes" id="CheqMes"> Facturacion por Meses</label>
																	</div>
																	<div class="col-sm-6">
																		<div class="">
																		<label for="inputEmail3" class="col-sm-5 control-label">TIPO DE DOCUMENTO</label>
																		<div class="col-sm-7">
																			<select class="form-select form-select-sm" id="CTipo" name="CTipo">
																				<option value="FA">FA</option>
																				<option value="FR">FR</option>
																				<option value="NV">NV</option>
																				<option value="PV">PV</option>
																				<option value="FT">FT</option>
																				<option value="NC">NC</option>
																				<option value="LC">LC</option>
																				<option value="GR">GR</option>
																				<option value="CP">CP</option>
																				<option value="DO">DO</option>
																				<option value="NDO">NDO</option>
																				<option value="NDU">NDU</option>
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
																			<input type="text" class="form-control form-control-sm" id="TxtNumFact" name="TxtNumFact" placeholder="Email" value="00">
																	</div>
																	</div>	
																</div>
																<div class="col-sm-6">
																	<div class="form-group">
																		<label for="inputEmail3" class="col-sm-5 control-label">ITEMS POR FACTURA</label>
																		<div class="col-sm-7">
																				<input type="text" class="form-control form-control-sm" id="TxtItems" name="TxtItems" placeholder="Email" value="0.00">
																		</div>
																		</div>	
																</div>
																<div class="col-sm-12">
																	<div class="form-group">
																		<label for="TxtLogoFact" class="col-sm-5 control-label">FORMATO GRAFICO DEL DOCUMENTO (EXTENSION:GIF)</label>
																		<div class="col-sm-7">
																			<input type="text" class="form-control form-control-sm" id="TxtLogoFact" name="TxtLogoFact">
																		</div>
																		</div>	
																</div>				     	
															</div>
															<div class="row">
																<div class="col-sm-12">
																	ESPACIO Y POSICION DE LA COPIA DE LA FACTURA / NOTA DE VENTA
																</div>
																<div class="col-sm-6">
																	<div class="">
																		<label for="inputEmail3" class="col-sm-5 control-label">POSICION X DE LA FACTURA</label>
																		<div class="col-sm-7">
																			<input type="text" class="form-control form-control-sm" id="TxtPosFact" name="TxtPosFact" placeholder="Email" value="0.00">
																		</div>
																	</div>	
																</div>
																<div class="col-sm-6">
																	<div class="">
																		<label for="inputEmail3" class="col-sm-5 control-label">POSICION Y DE LA FACTURA</label>
																		<div class="col-sm-7">
																				<input type="text" class="form-control form-control-sm" id="TxtPosY" name="TxtPosY" placeholder="" value="0.00">
																		</div>
																	</div>	
																</div>
																<div class="col-sm-6">
																	<div class="">
																		<label for="inputEmail3" class="col-sm-5 control-label">ESPACIO ENTRE LA FACTURA</label>
																		<div class="col-sm-7">
																			<input type="text" class="form-control form-control-sm" id="TxtEspa" name="TxtEspa" placeholder="" value="0.00">
																		</div>
																	</div>	
																</div>
																<div class="col-sm-6">
																	<div class="">
																		<label for="inputEmail3" class="col-sm-2 control-label">LARGO</label>
																		<div class="col-sm-3">
																			<input type="text" class="form-control form-control-sm" id="TxtLargo" name="TxtLargo" placeholder="" value="0.00">
																		</div>
																		<label for="inputEmail3" class="col-sm-2 control-label">X</label>
																		<label for="inputEmail3" class="col-sm-2 control-label">ANCHO</label>
																		<div class="col-sm-3">
																			<input type="text" class="form-control form-control-sm" id="TxtAncho" name="TxtAncho" placeholder="" value="0.00">
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
																	<div class="">
																		<label for="inputEmail3" class="col-sm-5 control-label">FECHA DE INICIO</label>
																		<div class="col-sm-7">
																			<input type="date" class="form-control form-control-sm" id="MBFechaIni" name="MBFechaIni" placeholder="" value="<?php echo date('Y-m-d');?>" >
																		</div>
																		</div>	
																</div>
																<div class="col-sm-6">
																	<div class="">
																		<label for="inputEmail3" class="col-sm-5 control-label">SECUENCIAL DE INICIO</label>
																		<div class="col-sm-7">
																			<input type="text" class="form-control form-control-sm" id="TxtNumSerietres1" name="TxtNumSerietres1" placeholder="" value="000001">
																		</div>
																		</div>	
																</div>
																<div class="col-sm-6">
																	<div class="">
																		<label for="inputEmail3" class="col-sm-5 control-label">FECHA DE VENCIMIENTO</label>
																		<div class="col-sm-7">
																			<input type="date" class="form-control form-control-sm" id="MBFechaVenc" name="MBFechaVenc" placeholder="" value="<?php echo date('Y-m-d');?>">
																		</div>
																		</div>	
																</div>
																<div class="col-sm-6">
																	<div class="">
																		<label for="inputEmail3" class="col-sm-5 control-label">AUTORIZACION</label>
																		<div class="col-sm-7">
																			<input type="text" class="form-control form-control-sm text-end" id="TxtNumAutor" name="TxtNumAutor" placeholder="" value="0000000001">
																		</div>
																		</div>	
																</div>
																<div class="col-sm-12">
																	<div class="">
																		<label for="inputEmail3" class="col-sm-8 control-label">SERIE DE FACTURA / NOTA DE VENTA (ESTAB. Y PUNTO DE VENTA)</label>
																		<div class="col-sm-2">
																			<input type="text" class="form-control form-control-sm" id="TxtNumSerieUno" name="TxtNumSerieUno" placeholder="" value="001">
																		</div>
																		<div class="col-sm-2">
																			<input type="text" class="form-control form-control-sm" id="TxtNumSerieDos" name="TxtNumSerieDos" placeholder="" value="001">
																		</div>
																		</div>	
																</div>
																</div>
																<div class="row">
																	<h4>DATOS DEL ESTABLECIMIENTO</h4>
																	<div class="col-sm-12">
																		<B>NOMBRE DEL ESTABLECIMIENTO</B>
																		<input type="text" class="form-control form-control-sm" id="TxtNombreEstab" name="TxtNombreEstab" placeholder="" value=".">
																	</div>
																	<div class="col-sm-12">
																		<div class="">
																<label for="inputEmail3" class="col-sm-1 control-label">DIRECCION</label>
																<div class="col-sm-11">
																	<input type="text" class="form-control form-control-sm" id="TxtDireccionEstab" name="TxtDireccionEstab" placeholder="" value=".">
																</div>
																</div>	
																	</div>
																	<div class="col-sm-6">
																		<div class="">
																<label for="inputEmail3" class="col-sm-2 control-label">TELEFONO</label>
																<div class="col-sm-10">
																	<input type="text" class="form-control form-control-sm" id="TxtTelefonoEstab" name="TxtTelefonoEstab" placeholder="" value=".">
																</div>
																</div>	
																	</div>
																	<div class="col-sm-6">
																		<div class="">
																<label for="inputEmail3" class="col-sm-3 control-label">LOGOTIPO(GIF)</label>
																<div class="col-sm-9">
																	<input type="text" class="form-control form-control-sm" id="TxtLogoTipoEstab" name="TxtLogoTipoEstab" placeholder="" value=".">
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
								</div>
							</div>
							
							<!--<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="Estado">Estado</label>
										<select class="form-control input-sm" name="Estado" id="Estado" >
											<option value=''>Estado</option>
											<option value="0">Seleccione Estado</option>
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
									<label for="FechaR">Renovación</label>
									
									<input type="date" class="form-control input-sm" id="FechaR" name="FechaR" placeholder="FechaR" 
									value='' onKeyPress="return soloNumeros(event)"  maxlength="10" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
									<label for="Fecha">Comp. Electronico</label>								   
									<input type="date" class="form-control input-sm" id="FechaCE" name="FechaCE" placeholder="Fecha" 
									value="" onKeyPress="return soloNumeros(event)" maxlength="10" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
									</div>
								</div>							
								<div class="col-md-2">
									<div class="form-group">
									<label for="Fecha_DB">BD</label>
									<input type="date" class="form-control input-sm" id="FechaDB" name="FechaDB" value="">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
									<label for="Fecha_P12">Fecha P12</label>
									<input type="date" class="form-control input-sm" id="FechaP12" name="FechaP12" value="">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									<label for="Servidor">Servidor</label>
									<input type="text" class="form-control input-sm" id="Servidor" name="Servidor" placeholder="Servidor" value="">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									<label for="Base">Base</label>
									<input type="text" class="form-control input-sm" id="Base" name="Base" placeholder="Base" value="">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
									<label for="Usuario">Usuario</label>								   
									<input type="text" class="form-control input-sm" id="Usuario" name="Usuario" placeholder="Usuario" value="">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
									<label for="Clave">Clave</label>
									<input type="text" class="form-control input-sm" id="Clave" name="Clave" placeholder="Clave" value="">
									</div>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
									<label for="Motor">Motor BD</label>
									<input type="text" class="form-control input-sm" id="Motor" name="Motor" placeholder="Motor" value="">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
									<label for="Puerto">Puerto</label>
									
									<input type="text" class="form-control input-sm" id="Puerto" name="Puerto" placeholder="Puerto" value="">
									</div>
								</div>				
								<div class="col-md-2">
									<div class="form-group">
									<label for="Plan">Plan</label>
									
									<input type="text" class="form-control input-sm" id="Plan" name="Plan" placeholder="Plan" value="">
									</div>
								</div>
							
								<div class="col-md-12">
									<div class="form-group">
									<label for="Mensaje">Mensaje</label>
									<input type="text" class="form-control input-sm" id="Mensaje" name="Mensaje" placeholder="Mensaje" value="">
									</div>
								</div>	        
							</div>-->
						</div>
					</div>

				</div>
			</div>		
		</div>

	</form>	

	<div id="myModalCorreo" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-start">Enviar Comunicado</h4>
					<button type="button" class="btn-close text-end" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<form id="form_email">
						<div class="row">
							<div class="col-sm-12">
								<b>Para</b>
								<input type="input" class="form-control form-control-sm" name="txt_to" id="txt_to">
							</div> 
							<div class="col-sm-9">
								<b>Asunto</b>
								<input type="input" class="form-control form-control-sm" name="txt_asunto" id="txt_asunto">
							</div> 
							<div class="col-sm-3">
								<br>
								<b>Contenido HTML</b>
								<input type="checkbox" class="form-check-input" name="rbl_html" id="rbl_html">
							</div>              			
							<div class="col-sm-12">
								<b>Cuerpo de correo</b>
								<textarea class="form-control" rows="15" id="simpleHtml" name="simpleHtml"></textarea>
							</div>
							<div class="col-sm-12">
								<b>Archivo</b>
								<input type="file" id="file_archivo" name="file_archivo" class="form-control">
							</div>
							<div class="col-sm-6">	
								<button type="button" class="btn btn-primary w-100" onclick="renderhtml()">Vista Previa</button>
							</div>
							<div class="col-sm-6">	
								<button type="button" class="btn btn-primary w-100" onclick="enviar_email()">Enviar Correo</button>
							</div>
							<div class="col-sm-12">
								<div class="" id="htmlrender">
									
								</div>
							</div>
						</div>

					</form>
				</div>
				<!--  <div class="modal-footer">
					<button type="button" class="btn btn-default">Enviar</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div> -->
			</div>

		</div>
	</div>

	
	<div id="modal_prueba" class="modal" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header" style="padding: 6px 0px 6px 15px;">
					<button type="button" class="btn-close" data-bs-dismiss="modal">&times;</button>
					<h4 class="modal-title">MODAL PRUEBA</h4>
				</div>
				<div class="modal-body">
					<div id="prueba_contenedor">

					</div>
					<div class="panel-body" id="tree2" style="height: 300px; overflow-y: scroll;">

					</div>
				</div>
				<div class="modal-footer">
					
				</div>
			</div>

		</div>
	</div>
</div>
