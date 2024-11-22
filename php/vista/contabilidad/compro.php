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
	<div class="row">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div class="d-flex align-items-center btn-group">
            <a class="btn btn-outline-secondary" title="Salir del modulo" href="./contabilidad.php?mod=<?php echo $_SESSION['INGRESO']['modulo_']; ?>">
                <img src="../../img/png/salire.png">
            </a>
            <a class="btn btn-outline-secondary" title="Exportar Excel" href="javascript:void(0)" onclick="GenerarExcelResultadoComprobante()">
                <img src="../../img/png/table_excel.png">
            </a>   
            <button class="btn btn-outline-secondary" title="Modificar el comprobante" onclick="IngClave('Contador')">
                <img src="../../img/png/modificar.png">
            </button>
            <button type="button" id='l2' class="btn btn-outline-secondary" title="Anular comprobante" onclick="anular_comprobante()">
                <img src="../../img/png/anular.png">
            </button>
            <a id='l3' class="btn btn-outline-secondary" title="Autorizar comprobante autorizado">
                <img src="../../img/png/autorizar.png">
            </a>
            <a id='l4' class="btn btn-outline-secondary" title="Realizar una copia al comprobante" href="contabilidad.php?mod=<?php echo $_SESSION['INGRESO']['modulo_'];?>&acc=bacsg&acc1=Presenta balance de Comprobación&ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1">
                <img src="../../img/png/copiar.png">
            </a>
            <a id='l5' class="btn btn-outline-secondary" title="Copiar a otra empresa el comprobante" href="contabilidad.php?mod=<?php echo $_SESSION['INGRESO']['modulo_'];?>&acc=bacsg&acc1=Presenta estado de situación (general)&ti=ESTADO SITUACIÓN&Opcb=5&Opcen=1&b=0">
                <img src="../../img/png/copiare.png">
            </a>
        </div>
        <div class="d-flex justify-content-center">
            <?php echo $_SESSION['INGRESO']['item']; ?>
			<div class="btn-group" role="group" aria-label="Tipo de comprobante">
				<input type="radio" class="btn-check" name="options" id="CD" value="CD" autocomplete="off" onchange="comprobante();" checked>
				<label class="btn btn-primary btn-sm" for="CD">Diario</label>

				<input type="radio" class="btn-check" name="options" id="CI" value="CI" autocomplete="off" onchange="comprobante();">
				<label class="btn btn-primary btn-sm" for="CI">Ingresos</label>

				<input type="radio" class="btn-check" name="options" id="CE" value="CE" autocomplete="off" onchange="comprobante();">
				<label class="btn btn-primary btn-sm" for="CE">Egresos</label>

				<input type="radio" class="btn-check" name="options" id="ND" value="ND" autocomplete="off" onchange="comprobante();">
				<label class="btn btn-primary btn-sm" for="ND">N/D</label>

				<input type="radio" class="btn-check" name="options" id="NC" value="NC" autocomplete="off" onchange="comprobante();">
				<label class="btn btn-primary btn-sm" for="NC">N/C</label>
				<input id="tipoc" name="tipoc" type="hidden" value="CD">
                <input type="hidden" name="TipoProcesoLlamadoClave" id="TipoProcesoLlamadoClave">
			</div>
        </div>
        <div class="d-flex justify-content-end">
            <div class="w-75">
                <select class="form-select form-select-sm fs-6" name="tipo" id="mes" onchange="comprobante()">
                    <option value="0">Todos</option><?php echo  Tabla_Dias_Meses();?>
                </select>   
            </div>
            <div class="w-75 ms-2">
                <select class="form-select form-select-sm fs-6" name="ddl_comprobantes" id="ddl_comprobantes" onchange="listar_comprobante()">
                    <option value="">Seleccione</option>
                </select> 
            </div>
        </div>
    </div>
	</div>
	<br>
	<div class="row">	
		<div class="col-3">    
			<div class="input-group">
				<div class="input-group-text btn btn-info btn-sm" onclick="BtnFechaClick()" style="background-color:#00c0ef">
				<b>FECHA:</b>
				</div>
				<input type="date" class="form-control form-control-sm" id="MBFecha" placeholder="01/01/2019" value="<?php echo date("Y-m-d") ?>" maxlength="10" size="15" disabled onblur="MBFecha_LostFocus()">
			</div>
		</div>
		<div class="col-6">
			<div class="input-group input-group-sm">
				<div class="input-group-text col-12 p-1 d-flex justify-content-center border">
					<b id="LabelEst">Normal</b>
				</div>       
			</div>
		</div>
		<div class="col-3">    
			<div class="input-group input-group-sm">
				<div class="input-group-text">
				<b>CANTIDAD:</b>
				</div>
				<input type="" class="form-control form-control-sm" id="LabelCantidad">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-9">    
		<div class="input-group input-group-sm">
			<div class="input-group-text">
			<b>PAGADO A:</b>
			</div>
			<input type="text" class="form-control form-control-sm" id="LabelRecibi">
		</div>
		</div>	
		<div class="col-sm-3">    
		<div class="input-group input-group-sm">
			<div class="input-group-text">
			<b>EFECTIVO:</b>
			</div>
			<input type="text" class="form-control form-control-sm" id="LabelFormaPago" >
		</div>
		</div>	
	</div>
	<div class="row">
		<div class="col-sm-12">    
		<div class="input-group input-group-sm">
			<div class="input-group-text">
			<b>POR CONCEPTO DE :</b>
			</div>
			<!-- <input type="text" class="form-control input-xs" id="LabelFormaPago" > -->
			<textarea id="LabelConcepto" class="form-control"></textarea>
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
					<ul class="nav nav-pills" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#contabilizacion" role="tab" aria-controls="contabilizacion" aria-selected="true">
							CONTABILIZACION
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#retencion" role="tab" aria-controls="retencion" aria-selected="false">
							RETENCIONES
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#subcuenta" role="tab" aria-controls="subcuenta" aria-selected="false">
							SUBCUENTAS
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#kardex" role="tab" aria-controls="kardex" aria-selected="false">
							KARDEX
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#informe" role="tab" aria-controls="informe" aria-selected="false">
							INFORME
						</a>
					</li>
					</ul>
					<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade show active" id="contabilizacion" role="tabpanel" aria-labelledby="home-tab">
						<div class="row" >
							<div class="col-sm-12">
								<table class="table text-sm w-100" id="tbl_contabilidad">
									<thead>
										<tr>
											<th class="text-center"></th>
											<th class="text-center">Cta</th>
											<th class="text-center">Cuenta</th>
											<th class="text-center">Parcial_ME</th>
											<th class="text-center">Debe</th>
											<th class="text-center">Haber</th>
											<th class="text-center">Detalle</th>
											<th class="text-center">Cheq_Dep</th>
											<th class="text-center">Fecha_Efec</th>
											<th class="text-center">Codigo_C</th>
											<th class="text-center">Item</th>
											<th class="text-center">TC</th>
											<th class="text-center">Numero</th>
											<th class="text-center">Fecha</th>
											<th class="text-center">ID</th>
										</tr> 
									</thead>
									<tbody>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr> 
									</tbody> 
								</table>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="retencion" role="tabpanel" aria-labelledby="profile-tab">
						<div class="row">
							<div class="col-sm-12">
								<table class="table text-sm w-100" id="tbl_retenciones_co">
									<thead>
										<tr>
											<th class="text-center">Linea_SRI</th>
											<th class="text-center">Cliente</th>
											<th class="text-center">TD</th>
											<th class="text-center">CI_RUC</th>
											<th class="text-center">TipoComprobante</th>
											<th class="text-center">CodSustento</th>
											<th class="text-center">TP</th>
											<th class="text-center">Numero</th>
											<th class="text-center">Establecimiento</th>
											<th class="text-center">PuntoEmision</th>
											<th class="text-center">Secuencial</th>
											<th class="text-center">Autorizacion</th>
											<th class="text-center">FechaEmision</th>
											<th class="text-center">FechaRegistro</th>
											<th class="text-center">FechaCaducidad</th>
											<th class="text-center">BaseImponible</th>
											<th class="text-center">BaseImpGrav</th>
											<th class="text-center">PorcentajeIva</th>
											<th class="text-center">MontoIva</th>
											<th class="text-center">BaseImpIce</th>
											<th class="text-center">PorcentajeIce</th>
											<th class="text-center">MontoIce</th>
											<th class="text-center">MontoIvaBienes</th>
											<th class="text-center">PorRetBienes</th>
											<th class="text-center">ValorRetBienes</th>
											<th class="text-center">MontoIvaServicios</th>
											<th class="text-center">PorRetServicios</th>
											<th class="text-center">ValorRetServicios</th>
											<th class="text-center">ContratoPartidoPolitico</th>
											<th class="text-center">MontoTituloOneroso</th>
											<th class="text-center">MontoTituloGratuito</th>
											<th class="text-center">DevIva</th>
											<th class="text-center">Clave_Acceso</th>
											<th class="text-center">Estado_SRI</th>
											<th class="text-center">AutRetencion</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</tbody> 
								</table> 
							</div> 
							<div class="col-sm-12">
								<table class="table text-sm w-100" id="tbl_retenciones_ve">
	 								<thead>
										<tr>
	 										<th class="text-center">Linea_SRI</th>
	 										<th class="text-center">Cliente</th>
	 										<th class="text-center">TipoComprobante</th>
	 										<th class="text-center">CI_RUC</th>
	 										<th class="text-center">TD</th>
	 										<th class="text-center">TP</th>
	 										<th class="text-center">Numero</th>
	 										<th class="text-center">Establecimiento</th>
	 										<th class="text-center">PuntoEmision</th>
	 										<th class="text-center">NumeroComprobantes</th>
	 										<th class="text-center">Secuencial</th>
	 										<th class="text-center">FechaEmision</th>
	 										<th class="text-center">FechaRegistro</th>
	 										<th class="text-center">BaseImponible</th>
	 										<th class="text-center">BaseImpGrav</th>
	 										<th class="text-center">PorcentajeIva</th>
	 										<th class="text-center">MontoIva</th>
	 										<th class="text-center">BaseImpIce</th>
											<th class="text-center">PorcentajeIce</th>
	 										<th class="text-center">MontoIce</th>
	 										<th class="text-center">MontoIvaBienes</th>
	 										<th class="text-center">PorRetBienes</th>
	 										<th class="text-center">ValorRetBienes</th>
	 										<th class="text-center">MontoIvaServicios</th>
	 										<th class="text-center">PorRetServicios</th>
	 										<th class="text-center">ValorRetServicios</th>
	 										<th class="text-center">RetPresuntiva</th>
										</tr>
									</thead>
									<tbody>
										<tr>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
	 										<td></td>
										</tr>
									</tbody> 
								</table>
							</div> 
							<div class="col-sm-12">
								<table class="table text-sm w-100" id="tbl_retenciones">
									<thead>
										<tr>
											<th class="text-center">T</th>
											<th class="text-center">CodRet</th>
											<th class="text-center">BaseImp</th>
											<th class="text-center">Porcentaje</th>
											<th class="text-center">ValRet</th>
											<th class="text-center">EstabRetencion</th>
											<th class="text-center">PtoEmiRetencion</th>
											<th class="text-center">SecRetencion</th>
											<th class="text-center">AutRetencion</th>
											<th class="text-center">EstabFactura</th>
											<th class="text-center">PuntoEmiFactura</th>
											<th class="text-center">Factura_No</th>
											<th class="text-center">Item</th>
											<th class="text-center">CodigoU</th>
											<th class="text-center">Periodo</th>
											<th class="text-center">Cta_Retencion</th>
											<th class="text-center">IdProv</th>
											<th class="text-center">TP</th>
											<th class="text-center">Fecha</th>
											<th class="text-center">Numero</th>
											<th class="text-center">Linea_SRI</th>
											<th class="text-center">Tipo_Trans</th>
											<th class="text-center">X</th>
											<th class="text-center">IDT</th>
											<th class="text-center">RUC_CI</th>
											<th class="text-center">TB</th>
											<th class="text-center">Razon_Social</th>
											<th class="text-center">ID</th>
										</tr> 
									</thead> 
									<tbody>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>                   	 	
						</div>
					</div>
					<div class="tab-pane fade" id="subcuenta" role="tabpanel" aria-labelledby="contact-tab">
						<div class="row">
							<div class="col-sm-12">
								<table class="table text-sm w-100" id="tbl_subcuentas">
									<thead>
										<tr>
											<th class="text-center">TC</th> 
											<th class="text-center">Detalles</th> 
											<th class="text-center">Factura</th> 
											<th class="text-center">Fecha_V</th> 
											<th class="text-center">Parcial_ME</th> 
											<th class="text-center">Debitos</th> 
											<th class="text-center">Creditos</th> 
											<th class="text-center">Prima</th> 
											<th class="text-center">Detalle_SubCta</th> 
											<th class="text-center">Cta</th> 
											<th class="text-center">Codigo</th> 
										</tr> 
									</thead>
									<tbody>
										<tr>
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
										</tr>
									</tbody>
								</table>
							</div>                   	 	
						</div>
					</div>
					<div class="tab-pane fade" id="kardex" role="tabpanel" aria-labelledby="contact-tab">
						<div class="row">
							<div class="col-sm-12">
								<table class="table text-sm w-100" id="tbl_kardex">
									<thead>
	 									<tr>
											<th class="text-center">Codigo_Inv</th>
											<th class="text-center">Producto</th>
											<th class="text-center">CodBodega</th>
											<th class="text-center">Entrada</th>
											<th class="text-center">Salida</th>
											<th class="text-center">Valor_Unitario</th>
											<th class="text-center">Valor_Total</th>
											<th class="text-center">Costo</th>
											<th class="text-center">Total</th>
											<th class="text-center">Fecha</th>
											<th class="text-center">TC</th>
											<th class="text-center">Serie</th>
											<th class="text-center">Factura</th>
											<th class="text-center">Orden_No</th>
											<th class="text-center">Lote_No</th>
											<th class="text-center">Fecha_Fab</th>
											<th class="text-center">Fecha_Exp</th>
											<th class="text-center">Reg_Sanitario</th>
											<th class="text-center">Modelo</th>
											<th class="text-center">Procedencia</th>
											<th class="text-center">Serie_No</th>
											<th class="text-center">Codigo_Barra</th>
											<th class="text-center">Cta_Inv</th>
											<th class="text-center">Contra_Cta</th>
										</tr>
									</thead>
									<tbody>
	 									<tr>
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
											<td></td> 
										</tr>
									</tbody>
								</table>
							</div>                   	 	
						</div>
						<div class="row d-flex align-items-center">
							<div class="col-sm-6"></div>
								<div class="col-sm-1 p-0">
									<b>Total compra</b>
								</div> 
								<div class="col-sm-2">
									<input type="text" name="txt_total" readonly="" class="form-control form-control-sm" id="txt_total">
								</div> 
								<div class="col-sm-1 p-0">
									<b>Total costo</b>
								</div>                   	 	
								<div class="col-sm-2">
									<input type="text" name="txt_saldo" readonly="" class="form-control form-control-sm" id="txt_saldo">
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
	<div class="row d-flex align-items-center">
		<div class="col-sm-2">
		<b>Elaborador por</b>				
		</div>
		<div class="col-sm-4">
		<input type="text" id="LabelUsuario" name="LabelUsuario" readonly="" class="form-control form-control-sm">		
		</div>
		<div class="col-sm-2">
		<b>Totales</b>				
		</div>
		<div class="col-sm-2">			  				
		<input type="text" name="txt_debe" readonly="" class="form-control form-control-sm" id="txt_debe">
		</div>			
		<div class="col-sm-2">
		<input type="text" name="txt_haber" readonly="" class="form-control form-control-sm" id="txt_haber">				
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
	