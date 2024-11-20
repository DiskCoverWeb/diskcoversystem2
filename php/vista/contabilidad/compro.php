<?php
	 @session_start();
	// include(dirname(__DIR__,2)."/controlador/contabilidad/contabilidad_controller.php");
 //verificacion titulo accion
	$_SESSION['INGRESO']['ti']='';
	if(isset($_GET['ti'])) 
	{
		$_SESSION['INGRESO']['ti']=$_GET['ti'];
	}
	else
	{
		unset( $_SESSION['INGRESO']['ti']);
		$_SESSION['INGRESO']['ti']='BALANCE DE COMPROBACIÓN';
	}
?>
<!-- <meta charset="ISO-8859-1"> -->

<script src="../../dist/js/compro.js"></script>
<script>	
	 //------------------------------------
	 function confirmar_edicion(response)
	 {
	 	var ti = $('#tipoc').val();
	 	var be = $('#LabelRecibi').val(); 
	 	var co = $('#ddl_comprobantes').val();
	 	var va = $('#Co').val();
	 	var mod = '<?php echo $_SESSION['INGRESO']['modulo_']; ?>';
	 	 Swal.fire({
         title: 'Esta seguro que quiere modificar el comprobante '+ti+' No. '+co+' de '+be,
         text: "Esta usted seguro de que quiere modificar!",
         type: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Si!'
       }).then((result) => {
         if (result.value==true) {
         	// location.href='../vista/contabilidad.php?mod='+mod+'&acc=incom&acc1=Ingresar%20Comprobantes&b=1&modificar=1&variables='+va+'#';
         	location.href='../vista/contabilidad.php?mod='+mod+'&acc=incom&acc1=Ingresar%20Comprobantes&b=1&modificar=1&TP='+ti+'&com='+co+'&num_load=1';
         }
       })
	 }
</script>
<div>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?></div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
						<li class="breadcrumb-item active" aria-current="page">Comprobantes Procesados</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
	<div class="">
	 	<div class="row row-cols-auto d-flex align-items-center w-auto pb-2">
			<div class="row row-cols-auto btn-group" role="group">
				<a class="btn btn-outline-secondary" title="Salir del modulo" href="./contabilidad.php?mod=<?php echo $_SESSION['INGRESO']['modulo_']; ?>">
					<img src="../../img/png/salire.png">
				</a>
				<a class="btn btn-outline-secondary" title="Exportar Excel"	href="javascript:void(0)" onclick="GenerarExcelResultadoComprobante()" ><img src="../../img/png/table_excel.png"></a>	      
				<button class="btn btn-outline-secondary" title="Modificar el comprobante" onclick="IngClave('Contador')">
							<img src="../../img/png/modificar.png" >
				</button>		   
				<button type="button" id='l2' class="btn btn-outline-secondary" title="Anular comprobante" onclick="anular_comprobante()"><img src="../../img/png/anular.png" >
				</button>
				<a id='l3' class="btn btn-outline-secondary" title="Autorizar comprobante autorizado">
							<img src="../../img/png/autorizar.png" > 
				</a>
				<a id='l4' class="btn btn-outline-secondary" title="Realizar una copia al comprobante" href="contabilidad.php?mod=<?php echo $_SESSION['INGRESO']['modulo_'];?>&acc=bacsg&acc1=Presenta balance de Comprobación&ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1">
							<img src="../../img/png/copiar.png" > 
				</a>
				<a id='l5' class="btn btn-outline-secondary" title="Copiar a otra empresa el comprobante" href="contabilidad.php?mod=<?php echo $_SESSION['INGRESO']['modulo_'];?>&acc=bacsg&acc1=Presenta estado de situación (general)&ti=ESTADO SITUACIÓN&Opcb=5&Opcen=1&b=0">
							<img src="../../img/png/copiare.png" > 
				</a>		
			</div>
		</div> 

		<div class="col-sm-4 ps-4">
			<br>
			<?php echo $_SESSION['INGRESO']['item']; ?> 
			<div class="btn-group btn-group-toggle" data-bs-toggle="buttons">
			<label style="font-size: 0.8rem" class="btn btn-primary active btn-sm">
				<input type="radio" name="options" id="CD" value="CD" autocomplete="off"class="d-none" checked onchange="comprobante();"> Diario
			</label>
			<label style="font-size: 0.8rem" class="btn btn-primary btn-sm">
				<input type="radio" name="options" id="CI" value="CI" autocomplete="off"class="d-none" onchange="comprobante();"> Ingresos
			</label>
			<label style="font-size: 0.8rem" class="btn btn-primary btn-sm">
				<input type="radio" name="options" id="CE" value="CE" autocomplete="off"class="d-none" onchange="comprobante();"> Egresos
			</label>
			<label style="font-size: 0.8rem" class="btn btn-primary btn-sm">
				<input type="radio" name="options" id="ND" value="ND" autocomplete="off"class="d-none" onchange="comprobante();"> N/D
			</label>
			<label style="font-size: 0.8rem" class="btn btn-primary btn-sm">
				<input type="radio" name="options" id="NC" value="NC" autocomplete="off"class="d-none" onchange="comprobante();"> N/C
			</label>
				<input id="tipoc" name="tipoc" type="hidden" value="CD">					
			<input type="hidden" name="TipoProcesoLlamadoClave" id="TipoProcesoLlamadoClave">
			</div>
		</div>	
		<div class="col-sm-3">
			<br>
			<div class="row">
			<div class="col-5">
				<select class="form-control form-control-sm" style="font-size:0.7rem" name="tipo" id='mes' onchange="comprobante()">
					<option value='0'>Todos</option><?php echo  Tabla_Dias_Meses();?>
				</select>			      			
			</div>
			<div class="col-7">
				<select class="form-control form-control-sm" style="font-size:0.7rem" name="ddl_comprobantes" id="ddl_comprobantes" onchange="listar_comprobante()">
					<option value="">Seleccione</option>
				</select>			      			
			</div>			      		
		</div>		
		</div>
	</div>
	<br>
	<div class="row">	
			<div class="col-sm-3">    
		<div class="input-group">
			<div class="input-group-addon input-xs btn btn-info" onclick="BtnFechaClick()" style="background-color:#00c0ef">
			<b>FECHA:</b>
			</div>
			<input type="date" class="form-control input-xs" id="MBFecha" placeholder="01/01/2019" value="<?php echo date("Y-m-d") ?>" maxlength="10" size="15" disabled onblur="MBFecha_LostFocus()">
		</div>
		</div>
			<div class="col-sm-6 text-center">
				<div class="input-group">
			<div class="input-group-addon input-xs">
			<b id="LabelEst">Normal</b>
			</div>       
		</div>

			<!-- <label>Normal</label> -->
			</div>
			<div class="col-sm-3">    
		<div class="input-group">
			<div class="input-group-addon input-xs">
			<b>CANTIDAD:</b>
			</div>
			<input type="" class="form-control input-xs" id="LabelCantidad">
		</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-9">    
		<div class="input-group">
			<div class="input-group-addon input-xs">
			<b>PAGADO A:</b>
			</div>
			<input type="text" class="form-control input-xs" id="LabelRecibi">
		</div>
		</div>	
		<div class="col-sm-3">    
		<div class="input-group">
			<div class="input-group-addon input-xs">
			<b>EFECTIVO:</b>
			</div>
			<input type="text" class="form-control input-xs" id="LabelFormaPago" >
		</div>
		</div>	
	</div>
	<div class="row">
		<div class="col-sm-12">    
		<div class="input-group">
			<div class="input-group-addon input-xs">
			<b>POR CONCEPTO DE :</b>
			</div>
			<!-- <input type="text" class="form-control input-xs" id="LabelFormaPago" > -->
			<textarea id="LabelConcepto" class="form-control input-xs"></textarea>
		</div>
		</div>		
	</div>
	<div class="row">
			<input type="hidden" name="" id="txt_empresa" value="<?php echo $_SESSION['INGRESO']['item'];?>">
			<input type="hidden" name="" id="TP" value="CD">
			<!-- <input type="hidden" name="" id="beneficiario" value=""> -->
			<input type="hidden" name="" id="Co" value="">
			<!-- <input type="hidden" name="" id="Concepto" value=""> -->
				<div class="col-sm-12">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item active">
						<a class="nav-link" id="home-tab" data-toggle="tab" href="#contabilizacion" role="tab" aria-controls="contabilizacion" aria-selected="true">CONTABILIZACION</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="profile-tab" data-toggle="tab" href="#retencion" role="tab" aria-controls="retencion" aria-selected="false">RETENCIONES</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="contact-tab" data-toggle="tab" href="#subcuenta" role="tab" aria-controls="subcuenta" aria-selected="false">SUBCUENTAS</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="contact-tab" data-toggle="tab" href="#kardex" role="tab" aria-controls="kardex" aria-selected="false">KARDEX</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="contact-tab" data-toggle="tab" href="#informe" role="tab" aria-controls="informe" aria-selected="false">INFORME</a>
					</li>
					</ul>
					<div class="tab-content" id="myTabContent">
					<div class="tab-pane active" id="contabilizacion" role="tabpanel" aria-labelledby="home-tab">
						<div class="row" ><br>
							<div class="col-sm-12" id="tbl_contabilidad">
								
							</div>
							
						</div>
					</div>
					<div class="tab-pane fade" id="retencion" role="tabpanel" aria-labelledby="profile-tab">
						<div class="row">
							<div class="col-sm-12" id="tbl_retenciones_co">
								
							</div> 
							<div class="col-sm-12" id="tbl_retenciones_ve">
								
							</div> 
							<div class="col-sm-12" id="tbl_retenciones">
								
							</div>                   	 	
						</div>
					</div>
					<div class="tab-pane fade" id="subcuenta" role="tabpanel" aria-labelledby="contact-tab">
						<div class="row">
							<div class="col-sm-12" id="tbl_subcuentas">
								
							</div>                   	 	
						</div>
					</div>
					<div class="tab-pane fade" id="kardex" role="tabpanel" aria-labelledby="contact-tab">
						<div class="row">
							<div class="col-sm-12" id="tbl_kardex">
								
							</div>                   	 	
						</div>
						<div class="row">
							<div class="col-sm-6"></div>
							<div class="col-sm-1" style="padding:0px;">
								<b>Total compra</b>
							</div> 
							<div class="col-sm-2">
								<input type="text" name="txt_total" readonly="" class="form-control input-sm" id="txt_total">
							</div> 
							<div class="col-sm-1" style="padding:0px;">
								<b>Total costo</b>
							</div>                   	 	
							<div class="col-sm-2">
								<input type="text" name="txt_saldo" readonly="" class="form-control input-sm" id="txt_saldo">
							</div> 
						</div>
					</div>                   
					<div class="tab-pane fade" id="informe" role="tabpanel" aria-labelledby="contact-tab">
						<div class="row">
							<div class="col-sm-12">
								<div id='pdfcom'></div>                      			
							</div>                   		
						</div>                	
					</div>
					</div>
				</div>			
	</div>
	<div class="row">
		<div class="col-sm-2">
		<b>Elaborador por</b>				
		</div>
		<div class="col-sm-4">
		<input type="text" id="LabelUsuario" name="LabelUsuario" readonly="" class="form-control input-sm">		
		</div>
		<div class="col-sm-2">
		<b>Totales</b>				
		</div>
		<div class="col-sm-2">			  				
		<input type="text" name="txt_debe" readonly="" class="form-control input-sm" id="txt_debe">
		</div>			
		<div class="col-sm-2">
		<input type="text" name="txt_haber" readonly="" class="form-control input-sm" id="txt_haber">				
		</div>
	</div>

	<div id="myModal_anular" class="modal fade myModalNuevoCliente" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Formulario de Anulacion</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<b>Motivo de la anulacion</b>
							<input type="" name="" id="txt_motivo_anulacion" class="form-control input-sm">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="anular_comprobante_procesar()">Aceptar</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</div>
	</div>
</div>

	<?php include_once("FChangeCta.php") ?>
	<?php include_once("FChangeValores.php") ?>
	