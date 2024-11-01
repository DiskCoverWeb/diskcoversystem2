
<style type="text/css">
	.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
    color: #fff;
    cursor: default;
    background-color:#7bbad3;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
}
</style>
<script type="text/javascript">
	$(document).ready(function () {
     
	})

	function procesar()
	{
		var parametros = 
		{
			'CheqSinConc':$('#CheqSinConc').prop('checked'),
			'CheqDetalle':$('#CheqDetalle').prop('checked'),
			'CheqRenumerar':$('#CheqRenumerar').prop('checked'),
			'MBoxCtaI':$('#MBoxCtaI').val(),
			'LabelTotInv':$('#LabelTotInv').val()
		}
		$.ajax({
	      data:  {parametros:parametros},
	      url:   '../controlador/contabilidad/cierre_ejercicioC.php?procesar=true',
	      type:  'post',
	      dataType: 'json',
	        success:  function (response) {
	        	if(response==2)
	        	{
	        		Swal.fire('Cuenta no asignada en el Catalogo de Cuentas','','error');
	        	}
	      }
	    });
	}
	function grabar()
	{
		$.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/contabilidad/cierre_ejercicioC.php?grabar=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {

          $('#LstMeses').html(response);               
        
      }
    });

	}
	function actualizar()
	{
		$.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/contabilidad/cierre_ejercicioC.php?actualizar=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {

          $('#LstMeses').html(response);               
        
      }
    });

	}
	function imprimir()
	{
		$.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/contabilidad/cierre_ejercicioC.php?imprimir=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {

          $('#LstMeses').html(response);               
        
      }
    });

	}
</script>

<div class="row">
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
       <!-- <div class="col-xs-2 col-md-1 col-sm-2"> -->
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default" style="border: solid 2px;">
              <img src="../../img/png/salire.png"><br>
             <span class="text-sm"> Salir</span>
            </a>
        <!-- </div> -->
        <!-- <div class="col-xs-2 col-md-2 col-sm-2"> -->
			<button class="btn btn-default"style="border: solid 2px;" title="Guardar" onclick="procesar()" id="btn_guardar">
				<img src="../../img/png/autorizar1.png">
				<br>
				<span class="text-sm">Procesar Cierre</span>
			</button>
		<!-- </div>   -->
        <!-- <div class="col-xs-2 col-md-2 col-sm-2"> -->
			<button class="btn btn-default"style="border: solid 2px;" title="Guardar" onclick="grabar()" id="btn_guardar">
				<img src="../../img/png/grabar.png"><br>
				<span class="text-sm">Guardar Cierre</span>
			</button>
		<!-- </div>   -->
		<!-- <div class="col-xs-2 col-md-2 col-sm-2"> -->
			<button class="btn btn-default"style="border: solid 2px;" title="Guardar" onclick="actualizar()" id="btn_guardar">
				<img src="../../img/png/edit_file.png"><br>
				<span class="text-sm">Actualizar Cierre</span>
			</button>
		<!-- </div>   -->
		<!-- <div class="col-xs-2 col-md-2 col-sm-2"> -->
			<button class="btn btn-default"style="border: solid 2px;" title="Guardar" onclick="imprimir()" id="btn_guardar">
				<img src="../../img/png/impresora.png"><br>
				<span class="text-sm">Imprimir Asiento</span>
			</button>
		<!-- </div>   -->
		
		
	</div>
	<div class="col-lg-3 col-sm-10 col-md-6 col-xs-12">
		<b>Cuenta Utilidad/Perdida </b>    			
      	<input type="text" name="MBoxCtaI" id="MBoxCtaI" class="form-control input-sm" id="txt_nombre_r" placeholder="<?php echo  $_SESSION['INGRESO']['Formato_Cuentas'];?>">            
	</div>
	<div class="col-lg-3 col-sm-10 col-md-6 col-xs-12">
		<b>TOTAL INVENTARIO </b>    			
      	<input type="text" name="LabelTotInv" id="LabelTotInv" class="form-control input-sm" id="txt_nombre_r">            
	</div>
</div>
<div class="row">
	<div class="col-sm-4">
		<label class="text-sm" style="padding:3px"><input type="checkbox" id="CheqSinConc" name="CheqSinConc">Detalle <br> Auxiliar</label>
		<label class="text-sm" style="padding:3px"><input type="checkbox" id="CheqDetalle" name="CheqDetalle">Sin <br>conciliacion</label>
		<label class="text-sm" style="padding:3px"><input type="checkbox" id="CheqRenumerar" name="CheqRenumerar">Remunerar <br>Comprobantes</label>
		
	</div>
	<div class="col-sm-8">
		<h3>CIERRE DE EJERCICIO AL </h3>
		
	</div>
	
	




</div>
<div class="box">
	<div class="box-body">
		<div class="row">
			<div class="col-sm-12">
				<ul class="nav nav-tabs nav-justified">
				  <li class="active"><a data-toggle="tab" href="#home">CONTABILIZACION</a></li>
				  <li><a data-toggle="tab" href="#menu1">SUB MODULOS</a></li>
				  <li><a data-toggle="tab" href="#menu2">INVENTARIOS</a></li>
				  <li><a data-toggle="tab" href="#menu3">cheques Giros y No cobrados</a></li>
				</ul>

				<div class="tab-content">
				  <div id="home" class="tab-pane fade in active">
				    <h3>HOME</h3>
				    <p>Some content.</p>
				  </div>
				  <div id="menu1" class="tab-pane fade">
				    <h3>Menu 1</h3>
				    <p>Some content in menu 1.</p>
				  </div>
				  <div id="menu2" class="tab-pane fade">
				    <h3>Menu 2</h3>
				    <p>Some content in menu 2.</p>
				  </div>
				  <div id="menu3" class="tab-pane fade">
				    <h3>Menu 3</h3>
				    <p>Some content in menu 2.</p>
				  </div>
				</div>
				
			</div>
		</div>
	</div>
</div>
	