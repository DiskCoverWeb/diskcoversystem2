
<!doctype html>
<html lang="en" class="semi-dark">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="../../img/jpg/logo.jpg" type="image/png" />
	<!--plugins-->
	<link href="../../assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="../../assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="../../assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="../../assets/css/pace.min.css" rel="stylesheet" />
	<script src="../../assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="../../assets/css/app.css" rel="stylesheet">
	<link href="../../assets/css/icons.css" rel="stylesheet">

	
	<title>Diskcover system - Login</title>
</head>

<body class="bg-login">
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="card mb-0">
							<div class="card-body">
								<div class="p-2">
									<div class="mb-3 text-center">
										<img src="../../img/jpg/logo.jpg" id="img_logo" width="60" alt="" />
									</div>
									<div class="text-center mb-2">
										<h5 class="" id="lbl_razon">Diskcover system</h5>
										<h6 class="" id="lbl_nombre" style="display: none;" ></h6>
										<p class="mb-0">Por favor, inicie sesión en su cuenta</p>
									</div>
									<div class="form-body">
										<form class="row g-3" autocomplete="on">
											<input type="hidden" name="txt_cartera" id="txt_cartera" value="0">
											<input type="hidden" name="txt_entidad_id" id="txt_entidad_id">
		   								<input type="hidden" name="txt_item" id="txt_item">
											<div class="col-12">
												<label for="inputEntidad" class="form-label">Entidad</label>
												<input type="text" class="form-control" placeholder="Entidad a la que pertenece" onblur="validar_entidad()" name="txt_entidad" id="txt_entidad">
											</div>
											<div class="col-12">
												<label for="inputEmailAddress" class="form-label">Email</label>
												<input type="email" class="form-control"  name="txt_correo" id="txt_correo" placeholder="Correo / Usuario" onblur="validar_usuario()" autocomplete="username">
											</div>
											<div class="col-12 mb-0">
												<label for="inputChoosePassword" class="form-label">Contraseña</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control border-end-0" name="txt_contra" id="txt_contra"  value="12345678" placeholder="Enter Password" autocomplete="current-password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
												</div>
											</div>
												<div id="form_cartera" style="display:none">
													<div class="input-group input-group-lg" >
									        	<span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-envelope"></i></span>
									          <input type="text" class="form-control" name="correo_cartera" placeholder="Correo Electrónico/Usuario" id="correo_cartera" onblur="validar_usuario()">                 
									        </div>  <br>        
									         <div class="input-group input-group-lg" >
									            <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
									              <input type="password" name="contra_cartera" id="contra_cartera" class="form-control" placeholder="******" aria-describedby="sizing-addon1" required autocomplete="new-password">
									         </div>							
												</div>

											
											<div class="col-md-12 text-end"><a href="recuperar.php">¿Olvidó su contraseña?</a>
											</div>
											<div class="col-12">
												<div class="d-grid">
													<button type="button" class="btn btn-primary" id="btn_ingreso" onclick="/*Ingresar();*/Ingresar_vali()">Ingresar
														<div class="spinner-border text-light spinner-border-sm" style="display:none;" role="status" id="loader_ingreso"> 
															<span class="visually-hidden">Loading...</span>
														</div>
													</button>
												</div>
											</div>
										<!-- 	<div class="col-12">
												<div class="text-center ">
													<p class="mb-0">Don't have an account yet? <a href="auth-basic-signup.html">Sign up here</a>
													</p>
												</div>
											</div> -->
										</form>
									</div>
									<!--
									<div class="login-separater text-center mb-2"> <span>OR SIGN IN WITH</span>
										<hr/>
									</div>
									<div class="list-inline contacts-social text-center">
										<a href="javascript:;" class="list-inline-item bg-facebook text-white border-0 rounded-3"><i class="bx bxl-facebook"></i></a>
										<a href="javascript:;" class="list-inline-item bg-twitter text-white border-0 rounded-3"><i class="bx bxl-twitter"></i></a>
										<a href="javascript:;" class="list-inline-item bg-google text-white border-0 rounded-3"><i class="bx bxl-google"></i></a>
										<a href="javascript:;" class="list-inline-item bg-linkedin text-white border-0 rounded-3"><i class="bx bxl-linkedin"></i></a>
									</div>
									-->
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="../../assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="../../assets/js/jquery.min.js"></script>
	<script src="../../assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="../../assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="../../assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>

	<script src="../../dist/js/login.js"></script>
	<script src="../../dist/js/sweetalert2@11.js"></script>
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>

	<div class="modal fade" id="mis_empresas" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Empreas asociadas a <label id="lbl_ruc"></label></h5>
					<!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
				</div>
				<div class="modal-body">
						<div class="customers-list p-0 mb-3" style="height:300px" id="tbl_empresas"></div>
				</div>
				<div class="modal-footer">
						<button type="button" class="btn btn-secondary" onclick="location.reload()">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	
</body>

</html>