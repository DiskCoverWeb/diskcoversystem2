<?php  date_default_timezone_set('America/Guayaquil');  
 //print_r($_SESSION);die();//print_r($_SESSION['INGRESO']);die();
 //verificacion titulo accion
  $_SESSION['INGRESO']['ti']='';
  if(isset($_GET['ti'])) { $_SESSION['INGRESO']['ti']=$_GET['ti']; } else{ unset( $_SESSION['INGRESO']['ti']); $_SESSION['INGRESO']['ti']='ADMINISTRAR EMPRESA';}
?>
<script type="text/javascript" src="../../dist/js/empresa/mostrar_venci.js"></script>
<div>
    <div>
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<div class="breadcrumb-title pe-3">
				<?php echo $NombreModulo; ?>
			</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
					<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
					</li>
					</ol>
				</nav>
			</div>          
		</div>
	</div> 
    <div class="row row-cols-auto">
        <div class="btn-group">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" data-bs-toggle="tooltip" title="Salir de modulo" class="btn btn-outline-secondary">
                <img src="../../img/png/salire.png">
            </a>
            <button type="button" data-bs-toggle="tooltip" class="btn btn-outline-secondary" title="Mostrar Vencimiento" onclick='mostrarEmpresa();'><img src="../../img/png/reporte_1.png"></button>
            <button type="button" data-bs-toggle="tooltip" class="btn btn-outline-secondary" title="Mostrar Vencimiento" onclick="reporte()"><img src="../../img/png/table_excel.png"></button>
        </div>
        <!-- <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a href="#" class="btn btn-default" title="Asignar reserva" onclick="Autorizar_Factura_Actual2();" target="_blank" ><img src="../../img/png/archivero2.png"></a>
        </div> -->
    </div>
    <div class="row">
        <div class="col-sm-2">
            <b>Desde: </b>
            <input type="date" class="form-control form-control-sm" id="desde" value="<?php echo date("Y-m-d"); ?>">      
        </div> 
        <div class="col-sm-2">
            <b>Hasta: </b>
            <input type="date" id="hasta"  class="form-control form-control-sm"  value="<?php echo date("Y-m-d");?>" onblur="consultar_datos();">      
        </div>    
        </div>
        <br>
        <div class="row">
            <div id='mostraE'>
            <div class="col-sm-12">
                <div class="table-responsive overflow-y-auto w-100" style="max-height:300px"*>
                <table class="table" id="tbl_vencimiento">
                    <thead>
                        <th>Tipo</th>
                        <th>Item</th>
                        <th>Empresa</th>
                        <th>Fecha</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
            </div>             
            </div>
        </div>
    </div>
</div>