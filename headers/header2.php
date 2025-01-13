<?php  include_once("../db/chequear_seguridad.php"); 
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
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="../../img/jpg/logo.jpg" type="image/png" />
	<!--plugins-->
	<link href="../../assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet"/>
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
	<link href="../../assets/css/TopMenu/app.css" rel="stylesheet">
	<link href="../../assets/css/icons.css" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="../../assets/css/TopMenu/dark-theme.css" />
	<link rel="stylesheet" href="../../assets/css/TopMenu/semi-dark.css" />
	<link rel="stylesheet" href="../../assets/css/TopMenu/header-colors.css" />
	<script src="../../dist/js/js_globales.js"></script>	
	
	<title>Diskcover system - Modulos</title>
	<script type="text/javascript">
		$(document).ready(function () {
      		 setInterval(validar_session_Activa, 5000);
	    });
	</script>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
	  <!--start header wrapper-->	
	  <div class="header-wrapper">
		<!--start header -->
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand gap-3">
					<div class="topbar-logo-header d-none d-lg-flex">
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
						<!-- <div class="">
							<h4 class="logo-text">Rukada</h4>
						</div> -->
					</div>
					<div class="mobile-toggle-menu d-block d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
						<img src=" <?php echo $url; ?>" class="logo-icon" alt="logo icon" style="width: 100px;">
					</div>
					<div class="position-relative search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
						
						<span class="px-5"></b> <?php if(strlen($_SESSION['INGRESO']['Nombre_Comercial'])<30) { 
														echo $_SESSION['INGRESO']['Nombre_Comercial'].' ...Ver mas';
														}else{
															$newName  = substr($_SESSION['INGRESO']['Nombre_Comercial'], 0,32);
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
							<li class="nav-item dark-mode d-none d-sm-flex">
								<a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i>
								</a>
							</li>

							<li class="nav-item dropdown dropdown-app" style="display: none;">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="javascript:;"><i class='bx bx-grid-alt'></i></a>
								<div class="dropdown-menu dropdown-menu-end p-0">
									<div class="app-container p-2 my-2">
									  <div class="row gx-0 gy-2 row-cols-3 justify-content-center p-2">
										 <div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="../../assets/images/app/slack.png" width="30" alt="">
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
				
									  </div>
				
									</div>
								</div>
							</li>


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
								<p class="designattion mb-0">Web Designer</p>
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
							<li><a class="dropdown-item d-flex align-items-center" href="javascript:;"  onclick="logout()" ><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>
		<!--end header -->
		<!--navigation-->
		   <div class="primary-menu">
			<!--    <nav class="navbar navbar-expand-lg align-items-center">
				  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
					<div class="offcanvas-header border-bottom">
						<div class="d-flex align-items-center">
							<div class="">
								<img src="../../assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
							</div>
							<div class="">
								<h4 class="logo-text">Rukada</h4>
							</div>
						</div>
					  <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
					</div>
					<div class="offcanvas-body">
					  <ul class="navbar-nav align-items-center flex-grow-1">
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
								<div class="parent-icon"><i class='bx bx-home-alt'></i>
								</div>
								<div class="menu-title d-flex align-items-center">Dashboard</div>
								<div class="ms-auto dropy-icon"><i class='bx bx-chevron-down'></i></div>
							</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="index.html"><i class='bx bx-pie-chart-alt' ></i>Default</a></li>
								<li><a class="dropdown-item" href="index2.html"><i class='bx bx-shield-alt-2'></i>Alternate</a></li>
								<li><a class="dropdown-item" href="index3.html"><i class='bx bx-line-chart'></i>Digital Marketing</a></li>
								<li><a class="dropdown-item" href="index4.html"><i class='bx bx-bar-chart-alt-2'></i>Human Resources</a></li>
							  </ul>
						  </li>
						  <li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
								<div class="parent-icon"><i class='bx bx-cube'></i>
								</div>
								<div class="menu-title d-flex align-items-center">Apps & Pages</div>
								<div class="ms-auto dropy-icon"><i class='bx bx-chevron-down'></i></div>
							</a>
							<ul class="dropdown-menu">
							  <li><a class="dropdown-item" href="app-emailbox.html"><i class='bx bx-envelope'></i>Email</a></li>
							  <li><a class="dropdown-item" href="app-chat-box.html"><i class='bx bx-conversation' ></i>Chat Box</a></li>
							  <li><a class="dropdown-item" href="app-file-manager.html"><i class='bx bx-file' ></i>File Manager</a></li>
							  <li><a class="dropdown-item" href="app-contact-list.html"><i class='bx bx-microphone' ></i>Contacts</a></li>
							  <li><a class="dropdown-item" href="app-to-do.html"><i class='bx bx-check-shield'></i>Todo</a></li>
							  <li><a class="dropdown-item" href="app-invoice.html"><i class='bx bx-printer' ></i>Invoice</a></li>
							  <li class="nav-item dropend">
								<a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"><i class='bx bx-file'></i>Pages</a>
								<ul class="dropdown-menu submenu">
									<li class="nav-item dropend"><a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"><i class='bx bx-radio-circle'></i>Error</a>
										<ul class="dropdown-menu">
											<li><a class="dropdown-item" href="errors-404-error.html"><i class='bx bx-radio-circle'></i>404 Error</a></li>
											<li><a class="dropdown-item" href="errors-500-error.html"><i class='bx bx-radio-circle'></i>500 rror</a></li>
											<li><a class="dropdown-item" href="errors-coming-soon.html"><i class='bx bx-radio-circle'></i>Coming Soon</a></li>
											<li><a class="dropdown-item" href="error-blank-page.html"><i class='bx bx-radio-circle'></i>Blank Page</a></li>
										  </ul>
									</li>
									<li><a class="dropdown-item" href="user-profile.html"><i class='bx bx-radio-circle'></i>User Profile</a></li>
									<li><a class="dropdown-item" href="timeline.html"><i class='bx bx-radio-circle'></i>Timeline</a></li>
									<li><a class="dropdown-item" href="faq.html"><i class='bx bx-radio-circle'></i>FAQ</a></li>
									<li><a class="dropdown-item" href="pricing-table.html"><i class='bx bx-radio-circle'></i>Pricing</a></li>
								  </ul>
							  </li>
							</ul>
						  </li>
						  <li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
								<div class="parent-icon"><i class='bx bx-message-square-edit'></i>
								</div>
								<div class="menu-title d-flex align-items-center">Forms</div>
								<div class="ms-auto dropy-icon"><i class='bx bx-chevron-down'></i></div>
							</a>
							<ul class="dropdown-menu">
								<li> <a class="dropdown-item" href="form-elements.html"><i class='bx bx-message-square-dots'></i>Form Elements</a>
								</li>
								<li> <a class="dropdown-item" href="form-input-group.html"><i class='bx bx-book-content' ></i>Input Groups</a>
								</li>
								<li> <a class="dropdown-item" href="form-radios-and-checkboxes.html"><i class='bx bx-radio-circle-marked'></i>Radios & Checkboxes</a>
								</li>
								<li> <a class="dropdown-item" href="form-layouts.html"><i class='bx bx-layer'></i>Forms Layouts</a>
								</li>
								<li> <a class="dropdown-item" href="form-validations.html"><i class='bx bx-file-blank' ></i>Form Validation</a>
								</li>
								<li> <a class="dropdown-item" href="form-wizard.html"><i class='bx bx-glasses'></i>Form Wizard</a>
								</li>
								<li> <a class="dropdown-item" href="form-text-editor.html"><i class='bx bx-edit'></i>Text Editor</a>
								</li>
								<li> <a class="dropdown-item" href="form-file-upload.html"><i class='bx bx-upload'></i>File Upload</a>
								</li>
								<li> <a class="dropdown-item" href="form-date-time-pickes.html"><i class='bx bx-calendar-check' ></i>Date Pickers</a>
								</li>
								<li> <a class="dropdown-item" href="form-select2.html"><i class='bx bx-check-double'></i>Select2</a>
								</li>
								<li> <a class="dropdown-item" href="form-repeater.html"><i class='bx bx-directions'></i>Form Repeater</a>
								</li>
							</ul>
						  </li>
						  <li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
								<div class="parent-icon"><i class='bx bx-lock'></i>
								</div>
								<div class="menu-title d-flex align-items-center">Authentication</div>
								<div class="ms-auto dropy-icon"><i class='bx bx-chevron-down'></i></div>
							</a>
							<ul class="dropdown-menu">
							  <li class="nav-item dropend">
								<a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"><i class='bx bx-receipt'></i>Basic</a>
								<ul class="dropdown-menu submenu">
									<li><a class="dropdown-item" href="auth-basic-signin.html"><i class='bx bx-radio-circle'></i>Sign In</a></li>
									<li><a class="dropdown-item" href="auth-basic-signup.html"><i class='bx bx-radio-circle'></i>Sign Up</a></li>
									<li><a class="dropdown-item" href="auth-basic-forgot-password.html"><i class='bx bx-radio-circle'></i>Forgot Password</a></li>
									<li><a class="dropdown-item" href="auth-basic-reset-password.html"><i class='bx bx-radio-circle'></i>Reset Password</a></li>
								  </ul>
							  </li>
							  <li class="nav-item dropend">
								<a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"><i class='bx bx-cylinder'></i>Cover</a>
								<ul class="dropdown-menu submenu">
									<li><a class="dropdown-item" href="auth-cover-signin.html"><i class='bx bx-radio-circle'></i>Sign In</a></li>
									<li><a class="dropdown-item" href="auth-cover-signup.html"><i class='bx bx-radio-circle'></i>Sign Up</a></li>
									<li><a class="dropdown-item" href="auth-cover-forgot-password.html"><i class='bx bx-radio-circle'></i>Forgot Password</a></li>
									<li><a class="dropdown-item" href="auth-cover-reset-password.html"><i class='bx bx-radio-circle'></i>Reset Password</a></li>
								  </ul>
							  </li>
							  <li class="nav-item dropend">
								<a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"><i class='bx bx-aperture'></i>Header & Footer</a>
								<ul class="dropdown-menu submenu">
									<li><a class="dropdown-item" href="auth-header-footer-signin.html"><i class='bx bx-radio-circle'></i>Sign In</a></li>
									<li><a class="dropdown-item" href="auth-header-footer-signup.html"><i class='bx bx-radio-circle'></i>Sign Up</a></li>
									<li><a class="dropdown-item" href="auth-header-footer-forgot-password.html"><i class='bx bx-radio-circle'></i>Forgot Password</a></li>
									<li><a class="dropdown-item" href="auth-header-footer-reset-password.html"><i class='bx bx-radio-circle'></i>Reset Password</a></li>
								  </ul>
							  </li>
							</ul>
						  </li>
						  <li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
								<div class="parent-icon"><i class='bx bx-briefcase-alt'></i>
								</div>
								<div class="menu-title d-flex align-items-center">UI Elements</div>
								<div class="ms-auto dropy-icon"><i class='bx bx-chevron-down'></i></div>
							</a>
							<ul class="dropdown-menu">
							  <li> <a class="dropdown-item" href="widgets.html"><i class='bx bx-wine'></i>Widgets</a></li>
							  <li class="nav-item dropend">
								<a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"><i class='bx bx-cart' ></i>eCommerce</a>
								<ul class="dropdown-menu submenu">
									<li><a class="dropdown-item" href="ecommerce-products.html"><i class='bx bx-radio-circle'></i>Products</a></li>
									<li><a class="dropdown-item" href="ecommerce-products-details.html"><i class='bx bx-radio-circle'></i>Product Details</a></li>
									<li><a class="dropdown-item" href="ecommerce-add-new-products.html"><i class='bx bx-radio-circle'></i>Add New Products</a></li>
									<li><a class="dropdown-item" href="ecommerce-orders.html"><i class='bx bx-radio-circle'></i>Orders</a></li>
								  </ul>
							  </li>
							  <li class="nav-item dropend">
								<a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"><i class='bx bx-ghost'></i>Components</a>
								<ul class="dropdown-menu scroll-menu">
									<li><a class="dropdown-item" href="component-alerts.html"><i class='bx bx-radio-circle'></i>Alerts</a></li>
									<li><a class="dropdown-item" href="component-accordions.html"><i class='bx bx-radio-circle'></i>Accordions</a></li>
									<li><a class="dropdown-item" href="component-badges.html"><i class='bx bx-radio-circle'></i>Badges</a></li>
									<li><a class="dropdown-item" href="component-buttons.html"><i class='bx bx-radio-circle'></i>Buttons</a></li>
									<li><a class="dropdown-item" href="component-cards.html"><i class='bx bx-radio-circle'></i>Cards</a></li>
									<li><a class="dropdown-item" href="component-carousels.html"><i class='bx bx-radio-circle'></i>Carousels</a></li>
									<li><a class="dropdown-item" href="component-list-groups.html"><i class='bx bx-radio-circle'></i>List Groups</a></li>
									<li><a class="dropdown-item" href="component-media-object.html"><i class='bx bx-radio-circle'></i>Media Objects</a></li>
									<li><a class="dropdown-item" href="component-modals.html"><i class='bx bx-radio-circle'></i>Modals</a></li>
									<li><a class="dropdown-item" href="component-navs-tabs.html"><i class='bx bx-radio-circle'></i>Navs & Tabs</a></li>
									<li><a class="dropdown-item" href="component-navbar.html"><i class='bx bx-radio-circle'></i>Navbar</a></li>
									<li><a class="dropdown-item" href="component-paginations.html"><i class='bx bx-radio-circle'></i>Pagination</a></li>
									<li><a class="dropdown-item" href="component-popovers-tooltips.html"><i class='bx bx-radio-circle'></i>Popovers & Tooltips</a></li>
									<li><a class="dropdown-item" href="component-progress-bars.html"><i class='bx bx-radio-circle'></i>Progress</a></li>
									<li><a class="dropdown-item" href="component-spinners.html"><i class='bx bx-radio-circle'></i>Spinners</a></li>
									<li><a class="dropdown-item" href="component-notifications.html"><i class='bx bx-radio-circle'></i>Notifications</a></li>
									<li><a class="dropdown-item" href="component-avtars-chips.html"><i class='bx bx-radio-circle'></i>Avatrs & Chips</a></li>
								  </ul>
							  </li>
							  <li class="nav-item dropend">
								<a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"><i class='bx bx-card' ></i>Content</a>
								<ul class="dropdown-menu submenu">
									<li><a class="dropdown-item" href="content-grid-system.html"><i class='bx bx-radio-circle'></i>Grid System</a></li>
									<li><a class="dropdown-item" href="content-typography.html"><i class='bx bx-radio-circle'></i>Typography</a></li>
									<li><a class="dropdown-item" href="content-text-utilities.html"><i class='bx bx-radio-circle'></i>Text Utilities</a></li>
								  </ul>
							  </li>
							  <li class="nav-item dropend">
								<a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"><i class='bx bx-droplet' ></i>Icons</a>
								<ul class="dropdown-menu submenu">
									<li><a class="dropdown-item" href="icons-line-icons.html"><i class='bx bx-radio-circle'></i>Line Icons</a></li>
									<li><a class="dropdown-item" href="icons-boxicons.html"><i class='bx bx-radio-circle'></i>Boxicons</a></li>
									<li><a class="dropdown-item" href="icons-feather-icons.html"><i class='bx bx-radio-circle'></i>Feather Icons</a></li>
								  </ul>
							  </li>
							</ul>
						  </li>
						  <li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
								<div class="parent-icon"><i class='bx bx-line-chart'></i>
								</div>
								<div class="menu-title d-flex align-items-center">Charts</div>
								<div class="ms-auto dropy-icon"><i class='bx bx-chevron-down'></i></div>
							</a>
							<ul class="dropdown-menu">
							  <li><a class="dropdown-item" href="charts-apex-chart.html"><i class='bx bx-bar-chart-alt-2' ></i>Apex</a></li>
							  <li><a class="dropdown-item" href="charts-chartjs.html"><i class='bx bx-line-chart'></i>Chartjs</a></li>
							  <li><a class="dropdown-item" href="charts-highcharts.html"><i class='bx bx-pie-chart-alt'></i>HighCharts</a></li>
							  <li class="nav-item dropend">
								<a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"><i class='bx bx-map-pin'></i>Maps</a>
								<ul class="dropdown-menu submenu">
									<li><a class="dropdown-item" href="map-google-maps.html"><i class='bx bx-radio-circle'></i>Google Maps</a></li>
									<li><a class="dropdown-item" href="map-vector-maps.html"><i class='bx bx-radio-circle'></i>Vector Maps</a></li>
								 </ul>
							  </li>
							</ul>
						  </li>
						  <li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
								<div class="parent-icon"><i class="bx bx-grid-alt"></i>
								</div>
								<div class="menu-title d-flex align-items-center">Tables</div>
								<div class="ms-auto dropy-icon"><i class='bx bx-chevron-down'></i></div>
							</a>
							<ul class="dropdown-menu">
							  <li><a class="dropdown-item" href="table-basic-table.html"><i class='bx bx-table'></i>Basic Table</a></li>
							  <li><a class="dropdown-item" href="table-datatable.html"><i class='bx bx-data' ></i>Data Table</a></li>
							</ul>
						  </li>
					  </ul>
					</div>
				  </div>
			  </nav> -->
		</div>
		<!--end navigation-->
	   </div>
	   <!--end header wrapper-->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				