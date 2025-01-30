<?php  
include_once("../db/chequear_seguridad.php"); 
include_once("../controlador/panel.php");
include_once(dirname(__DIR__,1)."/php/controlador/menuC.php"); 
include_once(dirname(__DIR__,1)."/php/controlador/contabilidad/contabilidad_controller.php");
include_once(dirname(__DIR__,1)."/php/modelo/contabilidad/contabilidad_model.php");
$menuC = new menuC();

$pagina =  isset($_GET['acc']) ? $_GET['acc'] : "";
$modulo =  isset($_GET['mod']) ? $_GET['mod'] : "";
$listaMenu = $menuC->generar_menu($modulo);
$_SESSION['INGRESO']['modulo_'] = $modulo;

// print_r($listaMenu);die();

$noty_lic = 0;
$licencia = date('Y-m-d');
$comprobante = date('Y-m-d');
$p12 = date('Y-m-d');
if (isset($_SESSION['INGRESO']['Fecha'])) {
	$originalDate = $_SESSION['INGRESO']['Fecha'];
	$licencia = date("Y-m-d", strtotime($originalDate));
	$estado_li = estado_licencia($licencia);
	$noty_lic = $noty_lic+$estado_li['noty'];
}
if (isset($_SESSION['INGRESO']['Fecha_ce'])) {
	$originalDate = $_SESSION['INGRESO']['Fecha_ce'];
	$comprobante = date("Y-m-d", strtotime($originalDate));
	$estado_comp = estado_licencia($comprobante);
	$noty_lic = $noty_lic+$estado_comp['noty'];
}
if (isset($_SESSION['INGRESO']['Fecha_P12'])) {
	$originalDate = $_SESSION['INGRESO']['Fecha_P12'];
	$p12  = date("Y-m-d", strtotime($originalDate));
	$estado_p12 = estado_licencia($p12);
	$noty_lic = $noty_lic+$estado_p12['noty'];
}
// print_r($noty_lic);die();

function estado_licencia($f3)
{
	$noty_lic = 0;
	$date1 = new DateTime(date('Y-m-d'));
	$date2 = new DateTime($f3);
	$diff = date_diff($date1, $date2)->format('%R%a');
	$diffdias3 = date_diff($date1, $date2)->format('%R%a días');


	$color = 'white';
	$estado = 'Infefinido';
	if ($diff > 241) {
	  $color = 'success';
	  $estado = 'Licencia activa';

	} else if ($diff >= 121 and $diff <= 240) {

	  $estado = 'Licencia activa';
	  $color = 'success';
	} else if ($diff >= 1 and $diff <= 120) {
	  $estado = 'Casi por renovar';
	  $color = 'warning';
	  $noty_lic++;
	} else if ($diff <= 0 and isset($_SESSION['INGRESO']['item'])) {
	  $estado = 'licencia vencida';
	  $color = 'danger';
	  $noty_lic++;
	}	
	return array('estado'=>$estado,'color'=>$color,'dias'=>$diffdias3,'noty'=>$noty_lic);
}


?>

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

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

	<link href="../../assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
	
	<link href="../../assets/css/app.css" rel="stylesheet">
	<link href="../../assets/css/icons.css" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="../../assets/css/dark-theme.css"/>
	<!-- <link rel="stylesheet" href="../../assets/css/semi-dark.css" /> -->
	<link rel="stylesheet" href="../../assets/css/header-colors.css"/>
	<link rel="stylesheet" href="../../dist/css/sweetalert2.min.css"/>
	<link rel="stylesheet" href="../../assets/css/jquery-ui.min.css"/>
	<link rel="stylesheet" href="../../dist/css/style_acordeon.css"/>
	<script type="text/javascript">
	var ModuloActual = '<?php echo $modulo;  ?>'; 
	</script>
	<script src="../../assets/js/jquery.min.js"></script>
	<script src="../../assets/js/jquery-ui.js"></script>
	<script src="../../dist/js/js_globales.js"></script>	
	<script src="../../dist/js/sweetalert2@11.js"></script>
	<script type="text/javascript">
		var formato = "<?php if (isset($_SESSION['INGRESO']['Formato_Cuentas'])) {
      	echo $_SESSION['INGRESO']['Formato_Cuentas'];
    	} ?>";
	</script>
	<title>Diskcover system - <?php  $_SESSION['INGRESO']['NombreModulo'] = $NombreModulo; echo $NombreModulo; ?></title>
	<script type="text/javascript">
		$(document).ready(function () {
      		setInterval(validar_session_Activa, 5000);
			labelPeriodo();
	    });

		var periodo = '<?php echo $_SESSION['INGRESO']['periodo'] ?>'
		function labelPeriodo(){
			if (periodo != '.'){
				var txt = '<b>Periodo: </b><label>'+ periodo +'</label>';
				console.log(txt);
				$('#periodo').html(txt);
			}
		}

	</script>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<div class="">
					 <?php
		              $url = '../../img/logotipos/diskcover_web.gif"';
		              if (isset($_SESSION['INGRESO']['Logo_Tipo'])) {
		                $tipo_img = array('jpg', 'gif', 'png', 'jpeg');
		                foreach ($tipo_img as $key => $value) {
		                  if (file_exists(dirname(__DIR__, 1) . '/img/logotipos/' . $_SESSION['INGRESO']['Logo_Tipo'] . '.' . $value)) {
		                    $timestamp = time();
		                    $url = '../../img/logotipos/' . $_SESSION['INGRESO']['Logo_Tipo'] . '.' . $value . '?v=' . $timestamp;
		                    break;
		                  }
		                }
		              }
		              ?>
					<img src=" <?php echo $url; ?>" class="logo-icon" alt="logo icon" style="width: 100px;">
				</div>
				<!-- <div>
					<h4 class="logo-text">Rukada</h4>
				</div> -->
				<div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
				</div>
			 </div>
			<!--navigation-->
			<ul class="metismenu" id="menu">				
				<li class="menu-label">MENU <?php echo $NombreModulo; ?></li>
				<?php echo $listaMenu; ?>
				<li>
					<a href="../vista/modulos.php">
						<div class="parent-icon">
							<i class="bx bx-log-out"></i> 					
						</div>
						<div class="menu-title">
							Salir a modulos
						</div>
	               </a>
           		</li>
			</ul>
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->
		<!--start header -->
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand gap-3">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>

					  <div class="position-relative search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
						
						<span class="px-5"></b> <?php if(strlen($_SESSION['INGRESO']['Nombre_Comercial'])<25) { 
														echo $_SESSION['INGRESO']['Nombre_Comercial'].' ...<b>Ver mas</b>';
														}else{
															$newName  = substr($_SESSION['INGRESO']['Nombre_Comercial'], 0,25);
															echo $newName.'... <b>Ver mas</b>';
														} ?></span>
						<span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-5"><i class='bx bx-buildings'></i></span>

					  </div>


					  <div class="top-menu ms-auto">
						<ul class="navbar-nav align-items-center gap-1">
							<li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
								<a class="nav-link" href="avascript:;"><i class='bx bx-buildings'></i>
								</a>
							</li>
							<!-- <li class="nav-item dropdown dropdown-laungauge d-none d-sm-flex">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="avascript:;" data-bs-toggle="dropdown"><img src="../../assets/images/county/02.png" width="22" alt="">
								</a>
								<ul class="dropdown-menu dropdown-menu-end">
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="../../assets/images/county/01.png" width="20" alt=""><span class="ms-2">English</span></a>
									</li>
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="../../assets/images/county/02.png" width="20" alt=""><span class="ms-2">Catalan</span></a>
									</li>
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="../../assets/images/county/03.png" width="20" alt=""><span class="ms-2">French</span></a>
									</li>
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="../../assets/images/county/04.png" width="20" alt=""><span class="ms-2">Belize</span></a>
									</li>
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="../../assets/images/county/05.png" width="20" alt=""><span class="ms-2">Colombia</span></a>
									</li>
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="../../assets/images/county/06.png" width="20" alt=""><span class="ms-2">Spanish</span></a>
									</li>
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="../../assets/images/county/07.png" width="20" alt=""><span class="ms-2">Georgian</span></a>
									</li>
									<li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="../../assets/images/county/08.png" width="20" alt=""><span class="ms-2">Hindi</span></a>
									</li>
								</ul>
							</li> -->

							<li>
								<div id="periodo"></div>
							</li>

							<li class="nav-item dark-mode d-none d-sm-flex">
								<a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i>
								</a>
							</li>

							

							<li class="nav-item dropdown dropdown-app" style="display:none;">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="javascript:;"><i class='bx bx-grid-alt'></i></a>
								<div class="dropdown-menu dropdown-menu-end p-0">
									<div class="app-container p-2 my-2">
									  <div class="row gx-0 gy-2 row-cols-3 justify-content-center p-2">
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../../../assets/images/app/slack.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Slack</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/behance.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Behance</p>
											  </div>
											  </div>
										  </a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												<img src="../../assets/images/app/google-drive.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Dribble</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/outlook.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Outlook</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/github.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">GitHub</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/stack-overflow.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Stack</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/figma.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Stack</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/twitter.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Twitter</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/google-calendar.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Calendar</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/spotify.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Spotify</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/google-photos.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Photos</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/pinterest.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Photos</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/linkedin.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">linkedin</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/dribble.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Dribble</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/youtube.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">YouTube</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/google.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">News</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/envato.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Envato</p>
											  </div>
											  </div>
											</a>
										 </div>
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/safari.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">Safari</p>
											  </div>
											  </div>
											</a>
										 </div>
				
									  </div><!--end row-->
				
									</div>
								</div>
							</li>
								<!--Notificaciones del la suscripcion--->
								<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <?php if($noty_lic!=0){ ?>
									<span class="alert-count"><?php echo $noty_lic; ?></span>
									<?php } ?>
									<i class='bx bx-calendar'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Licencias</p>
										</div>
									</a>
									<div class="header-message-list">
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify">
													<img src="../../img/png/calendario.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Fecha de licencia: 
														<span class="msg-time float-end"> <span class="badge bg-<?php echo $estado_li['color']; ?> rounded-pill"><?php echo $estado_li['estado']; ?></span>
													</span>
													</h6>
													<p class="msg-info"><?php echo $licencia; ?></p>
													<h6 class="msg-name">Dias Restantes:</h6>
													<p class="msg-info"><?php echo $estado_li['dias']; ?></p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify">
													<img src="../../img/png/calendario.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name"> Fecha de comprobante:
														<span class="msg-time float-end"> <span class="badge bg-<?php echo $estado_comp['color']; ?> rounded-pill"><?php echo $estado_comp['estado']; ?></span>
													</span>
													</h6>
													<p class="msg-info"><?php echo $comprobante; ?></p>
													<h6 class="msg-name">Dias Restantes:</h6>
													<p class="msg-info"><?php echo $estado_comp['dias']; ?></p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify">
													<img src="../../img/png/calendario.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name"> Fecha de p12:
														<span class="msg-time float-end"> <span class="badge bg-<?php echo $estado_p12['color']; ?> rounded-pill"><?php echo $estado_p12['estado']; ?></span>
													</span>
													</h6>
													<p class="msg-info"><?php echo $p12; ?></p>
													<h6 class="msg-name">Dias Restantes:</h6>
													<p class="msg-info"><?php echo $estado_p12['dias']; ?></p>
												</div>
											</div>
										</a>		
										
									
									
									</div>
									
								</div>
							</li>

							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown"><span class="alert-count">7</span>
									<i class='bx bx-bell'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Notifications</p>
											<p class="msg-header-badge">8 New</p>
										</div>
									</a>
								<!--Barra de notificaciones (Icono de campana)-->
									<div class="header-notifications-list">
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="../../assets/images/avatars/avatar-1.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Daisy Anderson<span class="msg-time float-end">5 sec
												ago</span></h6>
													<p class="msg-info">The standard chunk of lorem</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-danger text-danger">dc
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Orders <span class="msg-time float-end">2 min
												ago</span></h6>
													<p class="msg-info">You have recived new orders</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="../../assets/images/avatars/avatar-2.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Althea Cabardo <span class="msg-time float-end">14
												sec ago</span></h6>
													<p class="msg-info">Many desktop publishing packages</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-success text-success">
													<img src="../../assets/images/app/outlook.png" width="25" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Account Created<span class="msg-time float-end">28 min
												ago</span></h6>
													<p class="msg-info">Successfully created new email</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-info text-info">Ss
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Product Approved <span
												class="msg-time float-end">2 hrs ago</span></h6>
													<p class="msg-info">Your new product has approved</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="../../assets/images/avatars/avatar-4.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Katherine Pechon <span class="msg-time float-end">15
												min ago</span></h6>
													<p class="msg-info">Making this the first true generator</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-success text-success"><i class='bx bx-check-square'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Your item is shipped <span class="msg-time float-end">5 hrs
												ago</span></h6>
													<p class="msg-info">Successfully shipped your item</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-primary">
													<img src="../../assets/images/app/github.png" width="25" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day
												ago</span></h6>
													<p class="msg-info">24 new authors joined last week</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="../../assets/images/avatars/avatar-8.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Peter Costanzo <span class="msg-time float-end">6 hrs
												ago</span></h6>
													<p class="msg-info">It was popularised in the 1960s</p>
												</div>
											</div>
										</a>
									</div>
									<a href="javascript:;">
										<div class="text-center msg-footer">
											<button class="btn btn-primary w-100">View All Notifications</button>
										</div>
									</a>
								</div>
							</li>

							
						</ul>
					</div>
					<div class="user-box dropdown px-3">
						<a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<img id="img_foto" src="../../img/usuarios/<?php echo $_SESSION['INGRESO']['Foto']; ?>" class="user-img border border-dark"  alt="user avatar">
							<div class="user-info">
								<p class="user-name mb-0"><?php echo $_SESSION['INGRESO']['Nombre']; ?></p>
								<p class="designattion mb-0"><?php echo ($_SESSION['INGRESO']['Ambiente'] == "1") ? "Ambiente de prueba" : (($_SESSION['INGRESO']['Ambiente'] == "2") ? "Ambiente en producción" : ""); ?></p>
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-user fs-5"></i><span>Profile</span></a>
							</li>
							<li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-cog fs-5"></i><span>Settings</span></a>
							</li>
							<li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-home-circle fs-5"></i><span>Dashboard</span></a>
							</li>
							<li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-dollar-circle fs-5"></i><span>Earnings</span></a>
							</li>
							<li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-download fs-5"></i><span>Downloads</span></a>
							</li>
							<li>
								<div class="dropdown-divider mb-0"></div>
							</li>
							<li><a class="dropdown-item d-flex align-items-center" href="javascript:;" onclick="logout()"><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			  
	