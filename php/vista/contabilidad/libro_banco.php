

<?php
	Ejecutar_SQL_SP("UPDATE Comprobantes " .
        "SET Cotizacion = 0.004 " .
        "WHERE Cotizacion = 0 " .
        "AND Item = '" . $_SESSION['INGRESO']['item'] . "' " .
        "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'");
?>
<script src="../../dist/js/libro_banco"></script>

   	<div class="row">
   		<div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   			<div class="col-xs-2 col-md-2 col-sm-2">
   				<a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" data-toggle="tooltip" title="Salir de modulo" class="btn btn-default">
            		<img src="../../img/png/salire.png">
            	</a>
            </div>
             <div class="col-xs-2 col-md-2 col-sm-2">
            	<button title="Consultar"  data-toggle="tooltip" class="btn btn-default" onclick="ConsultarDatosLibroBanco();">
            		<img src="../../img/png/consultar.png" >
            	</button>
            	</div>		
           
            <div class="col-xs-2 col-md-2 col-sm-2">
              <a href="#" id="imprimir_pdf" class="btn btn-default" data-toggle="tooltip" title="Descargar PDF">
                 <img src="../../img/png/pdf.png">
              </a>                          	
            </div>
            	
            <div class="col-xs-2 col-md-2 col-sm-2">
            		<a href="#" id="imprimir_excel"  class="btn btn-default" data-toggle="tooltip" title="Descargar excel">
            	      <img src="../../img/png/table_excel.png">
            	     </a>                          	
                </div>

           
   		</div>
   		
   	</div>
	<div class="row">   	  	
	  	<div class="col-sm-3"><br>
	  		<b>Desde:</b>
            <input type="date" name="desde" id="desde" class="input-xs"  value="<?php echo date("Y-m-d");?>" onblur="validar_year_menor(this.id);fecha_fin()" onkeyup="validar_year_mayor(this.id)">
			<br>
            <b>Hasta:&nbsp;</b>
            <input type="date" name="hasta" id="hasta"  class="input-xs"  value="<?php echo date("Y-m-d");?>" onblur="validar_year_menor(this.id);ConsultarDatosLibroBanco();" onkeyup="validar_year_mayor(this.id)">  	              	
	  	</div>

	  	<div class="col-sm-3">
                <label style="margin:0px"><input type="checkbox" name="CheckUsu" id="CheckUsu">  <b>Por usuario</b></label>
                <select class="form-control input-xs" id="DCUsuario"  onchange="ConsultarDatosLibroBanco();">
                	<option value="">Seleccione usuario</option>
                </select>
          	    <label id="lblAgencia" style="margin:0px"><input type="checkbox" name="CheckAgencia" id="CheckAgencia">  <b>Agencia</b></label>
          	     <select class="form-control input-xs" id="DCAgencia" onchange="ConsultarDatosLibroBanco();">
                	<option value="">Seleccione agencia</option>
                </select>             
        </div>
        <div class="col-sm-3">
        	<b>Por cuenta</b>
                <select class="form-control input-xs" id="DCCtas" onchange="ConsultarDatosLibroBanco();">
                	<option value="">Seleccione cuenta</option>
                </select>
          	   
        </div>		
	</div>
	<br>
	  <!--seccion de panel-->
	  <div class="row">
	  	<input type="input" name="OpcU" id="OpcU" value="true" hidden="">
	  	<div class="col-sm-12">
	  		<ul class="nav nav-tabs">
	  		   <li class="active">
	  		   	<a data-toggle="tab" href="#home" id="titulo_tab" onclick="activar(this)"><b id="tit">Mayores auxiliares</b></a></li>
	  		</ul>
	  	    <div class="tab-content" style="background-color:#E7F5FF">
	  	    	<div id="home" class="tab-pane fade in active">	  	    			
	  	    	   <div id="tabla_">
	  	    	   		  	    	   	
	  	    	   </div>
	  	    	 </div>		  	    	  	    	
	  	    </div>	  	  
	  	</div>
	  </div>
	  <div class="row">
	  	<div class="col-sm-2">
	  		<b>Saldo Ant MN:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="saldo_ant" class="text-right rounded border border-primary" size="8" readonly value="0.00" />
	  	</div>
	  	<div class="col-sm-1">
	  		<b>Debe MN:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="debe" class="text-right rounded border border-primary" size="8" readonly value="0.00" />
	  	</div>
	  	<div class="col-sm-1">
	  		<b>Haber MN:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="haber" class="text-right rounded border border-primary" size="8" readonly value="0.00" />
	  	</div>
	  	<div class="col-sm-1">
	  		<b>Saldo MN:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="saldo" class="text-right rounded border border-primary" size="8" readonly value="0.00"/>
	  	</div>	  	
	  </div>
	  <div class="row">
	  	<div class="col-sm-2">
	  		<b>Saldo Ant ME:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="saldo_ant_" class="text-right rounded border border-primary" size="8" readonly value="0.00"/>
	  	</div>
	  	<div class="col-sm-1">
	  		<b>Debe ME:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="debe_" class="text-right rounded border border-primary" size="8" readonly value="0.00"/>
	  	</div>
	  	<div class="col-sm-1">
	  		<b>Haber ME:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="haber_" class="text-right rounded border border-primary" size="8" readonly value="0.00"/>
	  	</div>
	  	<div class="col-sm-1">
	  		<b>Saldo ME:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="saldo_" class="text-right rounded border border-primary" size="8" readonly value="0.00"/>
	  	</div>
	  </div>