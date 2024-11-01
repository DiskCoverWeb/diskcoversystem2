<script src="/../../dist/js/catalogoCta.js"></script>
   <div class="col-12" style="margin-top: -60px">
	  <br><br>
	  <div class="row">          
          <div class="row panel-body p-2">
	  	    <div class="col-3 pt-3">
            	<a href="inicio.php?mod=<?php echo $_SESSION['INGRESO']['modulo_']; ?>" data-toggle="tooltip" title="Salir de modulo" class="btn btn-default p-1 border border-3 rouded-sm ps-2 pe-2">
            		<img src="../../img/png/salire.png">
            	</a>
            	 <a href="#" class="btn btn-default p-1 border border-3 rouded-sm ps-2 pe-2" id='imprimir_pdf'  data-toggle="tooltip"title="Descargar PDF">
            		<img src="../../img/png/pdf.png">
            	</a>
            	<a href="#"  class="btn btn-default p-1 border border-3 rouded-sm ps-2 pe-2"  data-toggle="tooltip"title="Descargar excel" id='imprimir_excel'>
            		<img src="../../img/png/table_excel.png">
            	</a>
            	<button title="Consultar Catalogo de cuentas"  data-toggle="tooltip" class="btn btn-default p-1 border border-3 rouded-sm ps-2 pe-2" onclick="consultar_datos();">
            		<img src="../../img/png/consultar.png" >
            	</button>

	  	     </div>
	  	<div class="row col-5">
	  		<div class="row h-25">
	  			<div class="col-6">
             		<b>Cuenta inicial:</b>
             		<br>
             	   <input type="text" name="txt_CtaI" id="txt_CtaI" class="form-control input-xs" placeholder="<?php 
						echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
             	</div>
                <div class="col-6">
             	  <b> Cuenta final:</b>
             	<br>
             	   <input type="text" name="txt_CtaF" id="txt_CtaF" class="form-control input-xs" placeholder="<?php 
						echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>"> 
             	</div>       	
             	  		
	  			
	  		</div>             	
	  	</div>
	  	<div class="col-4">
           <div class="row">
             <div class="">
             	<br>
                <label class="pt-1 pe-2 ps-2"><input type="radio" name="OpcP"  id="OpcT" checked="" onchange="consultar_datos();"><b>Todos</b></label>
          	    <label class="pt-1 pe-2 ps-2"><input type="radio" name="OpcP" id="OpcG" onchange="consultar_datos();"><b>De grupo</b></label>            
          	    <label class="pt-1 pe-2 ps-2"><input type="radio" name="OpcP" id="OpcD" onchange="consultar_datos();"><b>De Detalles</b></label>              			
            </div>             		          		
          </div>
          </div>	
	   </div>

	  </div>	 
	  <!--seccion de panel-->
	  <div class="row">
	  	<input type="input" name="activo" id="activo" value="1" hidden="">
	  	<div class="col-12 p-2">
	  		<ul class="nav nav-tabs">
	  		   <li class="active border p-2">
	  		   	<a data-toggle="tab" href="#home" id="titulo_tab" onclick="activar(this)"><b>PLAN DE CUENTAS</b></a></li>
	  		</ul>
	  	    <div class="tab-content" style="background-color:#E7F5FF">
	  	    	<div id="home" class="tab-pane fade in active">
	  	    	   <div  id="tabla_">
	  	    	   		  	    	   	
	  	    	   </div>
	  	    	 </div>	  	    	
	  	    </div>
	  	</div>
	  </div>
	</div>
   <br><br>