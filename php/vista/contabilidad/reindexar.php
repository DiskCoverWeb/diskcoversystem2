<?php ?>

<script src="../../dist/js/reindexar.js"></script>

<div class="container">
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
			<a href="inicio.php?mod=<?php echo $_SESSION['INGRESO']['modulo_']; ?>" data-bs-toggle="tooltip" title="Salir de modulo" class="btn btn-default">
        		<img src="../../img/png/salire.png">
        	</a>
			<a href="#" class="btn btn-default" id='imprimir_pdf'  data-bs-toggle="tooltip"title="Descargar PDF">
        		<img src="../../img/png/pdf.png">
        	</a>
        	<a href="#"  class="btn btn-default"  data-bs-toggle="tooltip"title="Descargar excel" id='imprimir_excel'>
        		<img src="../../img/png/table_excel.png">
        	</a>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="box">
			<div class="box-body" style="height:300px; overflow-y:scroll;">
				<div class="col-sm-12">
					<table>
						<tbody id="lista_errores">
							
						</tbody>
					</table>
				</div>						
			</div>
		</div>
		
	</div>
</div>

<div class="modal fade" id="myModal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" >
		<div class="modal-content">
			<div class="modal-header">
		  		<button type="button" class="close" data-dismiss="modal">&times;</button>
		  		<h4 class="modal-title"><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg"> Reindexar cuentas</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">							
					<div class="row">
						<div class="col-sm-12">
							<p  align='left'><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg">Reindexar cuentas</p>											
						</div>										
					</div>
				</div>
			</div>
			<div class="modal-footer" style="background-color: #fff;">
				<button id="btnCopiar" class="btn btn-primary" onclick='reindexar();'>Reindexar</button>
			    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>			  
	</div>
</div>
