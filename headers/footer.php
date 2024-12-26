
			  
			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer" id="footerInfo" style="font-size: x-small;">
			<p class="mb-0"><img src="../../img/logotipos/diskcover_web.gif" class="m-1" style="width:60px"><b class="breadcrumb-title pe-1" style="font-size: 12px;">Diskcover Systema </b> <b class="m-1">DIRECCION:</b> Atacames N23-226 y Av. La Gasca - <b>EMAIL:</b> prisma_net@hotmail.com / diskcove@msn.com / info@diskcoversystem.com - <b>TELEFONO:</b> (+593)989105300 - 999654196 - 986524396</p>
		</footer>
	</div>
	

	<!-- search modal -->
	<div class="modal" id="SearchModal" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-md-down">
		<div class="modal-content">
			<div class="modal-header gap-2">
			<div class="position-relative popup-search w-100">
				<h3>Informacion de empresa</h3>
			</div>
			<button type="button" class="btn-close d-md-none" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="search-list">
				<div class="list-group">
					<a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1">
							<i class='bx bx-building fs-4'></i>Razon Social:<br><?php echo $_SESSION['INGRESO']['Razon_Social']; ?>
					</a>
					<a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1">
							<i class='bx bx-buildings fs-4'></i>Nombre Comercial: <br><?php echo $_SESSION['INGRESO']['Nombre_Comercial']; ?>
					</a>
					<a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1">
							<i class='bx bx-caret-right fs-4'></i><b>RUC:</b> <?php echo $_SESSION['INGRESO']['RUC']; ?>
					</a>
					<a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1">
							<i class='bx bx-caret-right fs-4'></i><b>Item:</b> <?php echo $_SESSION['INGRESO']['item']; ?>
					</a>
					<a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1">
							<i class='bx bx-caret-right fs-4'></i><?php echo ($_SESSION['INGRESO']['Ambiente'] == "1") ? "AMBIENTE DE PRUEBA" : (($_SESSION['INGRESO']['Ambiente'] == "2") ? "AMBIENTE EN PRODUCCION" : ""); ?>
					</a>
					
	
	

	

				</div>
				
				</div>
			</div>
		</div>
		</div>
	</div>
    <!-- end search modal -->



	<!--start switcher-->
	<div class="switcher-wrapper" id="switcher">
		<div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
		</div>
		<div class="switcher-body">
			<div class="d-flex align-items-center">
				<h5 class="mb-0 text-uppercase">Theme Customizer</h5>
				<button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
			</div>
			<hr/>
			<h6 class="mb-0">Theme Styles</h6>
			<hr/>
			<div class="d-flex align-items-center justify-content-between">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode">
					<label class="form-check-label" for="lightmode">Light</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode">
					<label class="form-check-label" for="darkmode">Dark</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark" checked>
					<label class="form-check-label" for="semidark">Semi Dark</label>
				</div>
			</div>
			<hr/>
			<div class="form-check">
				<input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault">
				<label class="form-check-label" for="minimaltheme">Minimal Theme</label>
			</div>
			<hr/>
			<h6 class="mb-0">Header Colors</h6>
			<hr/>
			<div class="header-colors-indigators">
				<div class="row row-cols-auto g-3">
					<div class="col">
						<div class="indigator headercolor1" id="headercolor1"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor2" id="headercolor2"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor3" id="headercolor3"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor4" id="headercolor4"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor5" id="headercolor5"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor6" id="headercolor6"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor7" id="headercolor7"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor8" id="headercolor8"></div>
					</div>
				</div>
			</div>
			<hr/>
			<h6 class="mb-0">Sidebar Colors</h6>
			<hr/>
			<div class="header-colors-indigators">
				<div class="row row-cols-auto g-3">
					<div class="col">
						<div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor2" id="sidebarcolor2"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor3" id="sidebarcolor3"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor4" id="sidebarcolor4"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor5" id="sidebarcolor5"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor6" id="sidebarcolor6"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor7" id="sidebarcolor7"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor8" id="sidebarcolor8"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="../../assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="../../assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="../../assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="../../assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script src="../../assets/plugins/select2/js/select2-custom.js"></script>
	<script src="../../assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="../../assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
	<script src="../../dist/js/login.js"></script>
	<!--app JS-->
	<script src="../../assets/js/app.js"></script>
</body>

</html>


<div class="modal fade" id="myModal_espera" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="../../img/gif/loader4.1.gif" width="80%">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal_sri_error" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
              <div class="modal-body">
                  <div class="row">
                      <div class="col-xs-2"><b>RUC Empresa</b> </div>
                      <div class="col-xs-10"><?php echo $_SESSION['INGRESO']['RUC']; ?></div>
                  </div>
                  <div class="row">
                      <div class="col-xs-2"><b>Estado</b> </div>
                      <div class="col-xs-10" id="sri_estado"></div>
                  </div>
                  <div class="row">
                      <div class="col-xs-6"><b>Codigo de error</b> </div>
                      <div class="col-xs-6" id="sri_codigo"></div>
                  </div>
                  <div class="row">
                      <div class="col-xs-2"><b>Fecha</b></div>
                      <div class="col-xs-10" id="sri_fecha"></div>
                  </div>
                  <div class="row">
                      <div class="col-xs-12"><b>Mensaje</b></div>
                      <div class="col-xs-12" id="sri_mensaje"></div>
                  </div>
                  <div class="row">
                      <div class="col-xs-12"><b>Info Adicional</b></div>
                      <div class="col-xs-12" id="sri_adicional"></div>
                  </div>
              </div>
              <input type="hidden" id="txtclave" name="">

              <div class="modal-footer">
                  <!-- <a type="button" class="btn btn-primary" href="#" id="doc_xml">Descargar xml</button>         -->
                  <!-- <button type="button" class="btn btn-default" onclick="location.reload();">Cerrar</button> -->
                   <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
              </div>
          </div>
      </div>
  </div>
 <div id="myModal_guia" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title">Datos de guia de remision</h4>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-sm-4">
                          <b>Fecha guia remision</b>
                          <input type="date" name="MBoxFechaGRE" id="MBoxFechaGRE" class="form-control input-xs"
                                  value="<?php echo date('Y-m-d'); ?>" onblur="MBoxFechaGRE_LostFocus()">
                      </div>
                      <div class="col-sm-8">
                      		<div class="row">
                      			<div class="col-sm-9">
                      			 <b>Guia de remision</b><br>
                            	  <select class="form-select" id="DCSerieGR" name="DCSerieGR"
                                  onblur="DCSerieGR_LostFocus()">
                                	  <option value="">No Existe</option>
                              	  </select>
                              	</div>
                          <div class="col-sm-3" style="padding: 0px">
                          	<b> No.</b>
                             	 <input type="text" name="LblGuiaR" id="LblGuiaR" class="form-control input-xs"
                                  value="000000">
                          </div>
                      		
                      	</div>
                         
                      </div>
                      <div class="col-sm-12">
                          <b>AUTORIZACION GUIA DE REMISION</b>
                          <input type="text" name="LblAutGuiaRem" id="LblAutGuiaRem" class="form-control input-xs"
                              value="0">
                      </div>
                      <div class="col-sm-5">
                          <b>Iniciacion  traslados</b>
                          <input type="date" name="MBoxFechaGRI" id="MBoxFechaGRI" class="form-control input-xs"
                                  value="<?php echo date('Y-m-d'); ?>">
                      </div>
                      <div class="col-sm-7">
                          <b>Ciudad</b>
                          <select class="form-control input-xs" id="DCCiudadI" name="DCCiudadI" style="width:100%">
                              <option value=""></option>
                          </select>
                      </div>
                      <div class="col-sm-5">
                          <b>Finalizacion del traslados</b>
                          <input type="date" name="MBoxFechaGRF" id="MBoxFechaGRF" class="form-control input-xs"
                                  value="<?php echo date('Y-m-d'); ?>">
                      </div>
                      <div class="col-sm-7">
                          <b>ciudad</b>
                              <select class="form-control input-xs" id="DCCiudadF" name="DCCiudadF" style="width:100%">
                                  <option value=""></option>
                              </select>
                      </div>
                      <div class="col-sm-12">
                          <b>Nombre o razon socila (Transportista)</b>
                          <select class="form-control input-xs" id="DCRazonSocial" name="DCRazonSocial" style="width:100%">
                              <option value=""></option>
                          </select>
                      </div>
                      <div class="col-sm-12">
                          <b>Empresa de Transporte</b>
                          <select class="form-control input-xs" id="DCEmpresaEntrega" name="DCEmpresaEntrega" style="width:100%">
                              <option value=""></option>
                          </select>
                      </div>
                      <div class="col-sm-4">
                          <b>Placa</b>
                          <input type="text" name="TxtPlaca" id="TxtPlaca" class="form-control input-xs"
                              value="XXX-999">
                      </div>
                      <div class="col-sm-4">
                          <b>Pedido</b>
                          <input type="text" name="TxtPedido" id="TxtPedido" class="form-control input-xs">
                      </div>
                      <div class="col-sm-4">
                          <b>Zona</b>
                          <input type="text" name="TxtZona" id="TxtZona" class="form-control input-xs">
                      </div>
                      <div class="col-sm-12">
                          <b>Lugar entrega</b>
                          <input type="text" name="TxtLugarEntrega" id="TxtLugarEntrega" class="form-control input-xs">
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button class="btn btn-primary" onclick="Command8_Click();">Aceptar</button>
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
              </div>
          </div>

      </div>
  </div>
  
  <script>
	function IngClave(tipo,base=false)
    {
        $.ajax({
            data: {
                usuario: tipo
            },
            url: '../controlador/panel.php?IngClaveCredenciales=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if(response['res'] == 1){
                    $('#titulo_clave').text(response['nombre']);

                    if(base)
                    {
                        $('#BuscarEn').val(base);
                    }
                    $('#TipoSuper_MYSQL').val(tipo);
                    $("#clave_supervisor").modal('show');
                }else{
                    Swal.fire("Error", "Hubo un problema al obtener datos del supervisor.", "error");
                }
            }
        });
    }
  </script>

