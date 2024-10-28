
			  
			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer" style="font-size: x-small;">
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
	<div class="switcher-wrapper">
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
	<!--app JS-->
	<script src="../../assets/js/app.js"></script>
</body>

</html>