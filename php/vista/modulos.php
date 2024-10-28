<?php require_once(dirname(__Dir__,2).'/headers/header2.php'); ?>

	<div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-4">

		<?php //print_r($_SESSION['INGRESO']['Nombre_Comercial']);
			$modulo = $_SESSION['INGRESO']['modulo']; 
			$moduloView = '';
			$color = array('1' => 'overflow-hidden bg-primary', 
						   '2' => 'overflow-hidden bg-danger', 
						   '3' => 'overflow-hidden bg-success', 
						   '4' => 'overflow-hidden bg-warning',
						   '5' => 'bg-gradient-cosmic',
						   '6' => 'bg-gradient-burning',
						   '7' => 'bg-gradient-kyoto',
						   '8' => 'bg-gradient-moonlit',
						   '9' => 'bg-success',
						   '10' => 'bg-linkedin',
						   '11' => 'bg-danger',
						   '12' => 'bg-primary');
    		$pos = 1;
    		// print_r($modulo);die();
			foreach ($modulo as $key => $value) {				
				$moduloView.='<div class="col">
								<a href="'.$value['link'].'">
									<div class="card radius-10 overflow-hidden '.$color[$pos].' ">
										<div class="card-body">
											<div class="d-flex align-items-center">
												<div>
													<p class="mb-0 text-white">MODULO</p>
													<h4 class="mb-0 text-white">'.$value['apli'].'</h4>
													<p class="mb-0 font-13 text-white"><i class="bx bxs-right-arrow align-middle"></i>Ingresar al modulo</p>
												</div>
												<div class="widgets-icons rounded-circle text-white ms-auto bg-gradient-burning">
												<img src="' . $value['icono'] . '" class="user-img">
												</div>
											</div>
										</div>
									</div>
								</a>
							</div>';
				$pos++;
				if($pos>12){$pos = 1;}
			}
		?>
		<?php echo $moduloView; ?>
	</div>
	<!--end row-->
</div>

<?php require_once(dirname(__Dir__,2).'/headers/footer2.php'); ?>