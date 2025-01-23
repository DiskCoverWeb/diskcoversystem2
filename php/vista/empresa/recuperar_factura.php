<?php  date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION);die();//print_r($_SESSION['INGRESO']);die();?>
<script type="text/javascript" src="../../dist/js/empresa/recuperar_factura.js"></script>

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
    <div class="row-cols-auto">
        <div class="btn-group">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-outline-secondary">
            <img src="../../img/png/salire.png">
            </a>
            <button type="button" class="btn btn-outline-secondary" title="Buscar" onclick='lista_recuperar();'><img src="../../img/png/consultar.png" ></button>
            <button type="button" class="btn btn-outline-secondary" title="Recuperar Facturas" onclick='recuperar();'><img src="../../img/png/update.png" ></button>
            <button type="button" class="btn btn-outline-secondary" title="Igualar fecha de autorizacion con fecha de Emision" onclick='editar_fechas();'><img src="../../img/png/sub_mod_mes.png" ></button>
        </div>
    </div>
	<div class="row">
		<div class="col-sm-6">			
			<p style="margin: 0px;"><b>Entidad:</b><?php echo $_SESSION['INGRESO']['IDEntidad']; ?></p>
			<p style="margin: 0px;"><b>Item: </b><?php echo $_SESSION['INGRESO']['item']; ?></p>
			<p style="margin: 0px;"><b>Empresa: </b><?php echo $_SESSION['INGRESO']['Nombre_Comercial']; ?></p>
			<p style="margin: 0px;"><b>Base de datos: </b><?php echo $_SESSION['INGRESO']['Base_Datos']; ?></p>
			<!-- <p><?php print_r($_SESSION['INGRESO']);?></p> -->			
		</div>
		<div class="col-sm-6">
			<div class="row">			
				<div class="col-sm-6">
					<b>Desde:</b>
					<input type="date" class="form-control form-control-sm" id="txt_desde" value=""  onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
				</div>
				<div class="col-sm-6">
					<b>Hasta</b>
					<input type="date" id="txt_hasta"  class="form-control form-control-sm"  value=""  onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);consultar_datos();">
				</div>
			</div>
			<!-- <i>*Si las fechas son iguales este motrara todos los registros entocntrados</i> -->
	    </div>
    </div> 
</div>	

<div class="row">
    <div class="col-sm-8">			
    	<p>Total de facturas:<b id="total_fac">0</b></p>
	</div>
    <div class="col-sm-12"> 
        <div class="table-responsive overflow-y-auto" style="max-height: 300px;">
            <table class="table text-sm w-100" id="tbl_datos">
                <thead>
                    <th>Fecha Emision</th>
                    <th>Autorizacion</th>
                    <th>Serie</th>
                    <th>Factura</th>
                </thead>
                <tbody>
                    <tr>
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
