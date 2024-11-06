

<?php
	Ejecutar_SQL_SP("UPDATE Comprobantes " .
        "SET Cotizacion = 0.004 " .
        "WHERE Cotizacion = 0 " .
        "AND Item = '" . $_SESSION['INGRESO']['item'] . "' " .
        "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'");
?>
<style>
  .font-box{
    font-size: 0.9rem;
    margin-bottom: 10px; 
  }
</style>
<script src="../../dist/js/libro_banco"></script>

   	<div class="row">
   		<div class="col-3 row m-0 p-0">
   			<div class="col-3">
   				<a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" data-toggle="tooltip" title="Salir de modulo" class="btn btn-default border border-3 rounded-2">
            		<img src="../../img/png/salire.png">
            	</a>
            </div>
             <div class="col-3">
            	<button title="Consultar"  data-toggle="tooltip" class="btn btn-default border border-3 rounded-2" onclick="ConsultarDatosLibroBanco();">
            		<img src="../../img/png/consultar.png" >
            	</button>
            	</div>		
           
            <div class="col-3">
              <a href="#" id="imprimir_pdf" class="btn btn-default border border-3 rounded-2" data-toggle="tooltip" title="Descargar PDF">
                 <img src="../../img/png/pdf.png">
              </a>                          	
            </div>
            	
            <div class="col-3">
            		<a href="#" id="imprimir_excel"  class="btn btn-default border border-3 rounded-2" data-toggle="tooltip" title="Descargar excel">
            	      <img src="../../img/png/table_excel.png">
            	     </a>                          	
                </div>

           
   		</div>
   		
   	</div>
	<div class="row">   	  	
	  	<div class="col-4"><br>
	  		<div class="row p-0 m-0">
				<b class="col-2">Desde:</b>
            	<input type="date" name="desde" id="desde" class="col-6 border-light ms-2"  value="<?php echo date("Y-m-d");?>" onblur="validar_year_menor(this.id);fecha_fin()" onkeyup="validar_year_mayor(this.id)">
			</div>
			<div class="row p-0 m-0">
            	<b class="col-2">Hasta:&nbsp;</b>
            	<input type="date" name="hasta" id="hasta"  class="col-6 border-light ms-2"  value="<?php echo date("Y-m-d");?>" onblur="validar_year_menor(this.id);ConsultarDatosLibroBanco();" onkeyup="validar_year_mayor(this.id)">  	              	
			</div>
		</div>

	  	<div class="col-4">
                <label style="margin:0px"><input type="checkbox" name="CheckUsu" id="CheckUsu">  <b>Por usuario</b></label>
                <select class="form-control form-control-sm h-25 w-75 font-box" id="DCUsuario"  onchange="ConsultarDatosLibroBanco();">
                	<option value="" class="">Seleccione usuario</option>
                </select>
          	    <label id="lblAgencia" style="margin:0px"><input type="checkbox" name="CheckAgencia" id="CheckAgencia">  <b>Agencia</b></label>
          	     <select class="form-control form-control-sm h-25 w-75 font-box" id="DCAgencia" onchange="ConsultarDatosLibroBanco();">
                	<option value="" class="">Seleccione agencia</option>
                </select>             
        </div>
        <div class="col-4">
        	<b>Por cuenta</b>
                <select class="form-control form-control-sm h-25 w-75 font-box" id="DCCtas" onchange="ConsultarDatosLibroBanco();">
                	<option value="">Seleccione cuenta</option>
                </select>
          	   
        </div>		
	</div>
	<br>
	  <!--seccion de panel-->
	  <div class="row">
	  	<input type="input" name="OpcU" id="OpcU" value="true" hidden="">
	  	<div class="col-sm-12">
	  		<ul class="nav nav-tabs p-2">
	  		   <li class="active">
	  		   	<a data-toggle="tab" href="#home" id="titulo_tab" class="h6" onclick="activar(this)">Mayores auxiliares</a></li>
	  		</ul>
	  	    <div class="tab-content" style="background-color:#E7F5FF">
	  	    	<div id="home" class="tab-pane fade in active">	  	    			
	  	    	   <div id="tabla_">
	  	    	   		  	    	   	
	  	    	   </div>
	  	    	 </div>		  	    	  	    	
	  	    </div>	  	  
	  	</div>
	  </div>
	<div class="p-2">
		<div class="row">
			<div class="col-sm-2">
				<b>Saldo Ant MN:</b>
			</div>
			<div class="col-sm-1">
				<input type="text" id="saldo_ant" class="text-right rounded border border-primary" size="8" readonly value="0.00" />
			</div>
			<div class="col-sm-2">
				<b>Debe MN:</b>
			</div>
			<div class="col-sm-1">
				<input type="text" id="debe" class="text-right rounded border border-primary" size="8" readonly value="0.00" />
			</div>
			<div class="col-sm-2">
				<b>Haber MN:</b>
			</div>
			<div class="col-sm-1">
				<input type="text" id="haber" class="text-right rounded border border-primary" size="8" readonly value="0.00" />
			</div>
			<div class="col-sm-2">
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
			<div class="col-sm-2">
				<b>Debe ME:</b>
			</div>
			<div class="col-sm-1">
				<input type="text" id="debe_" class="text-right rounded border border-primary" size="8" readonly value="0.00"/>
			</div>
			<div class="col-sm-2">
				<b>Haber ME:</b>
			</div>
			<div class="col-sm-1">
				<input type="text" id="haber_" class="text-right rounded border border-primary" size="8" readonly value="0.00"/>
			</div>
			<div class="col-sm-2">
				<b>Saldo ME:</b>
			</div>
			<div class="col-sm-1">
				<input type="text" id="saldo_" class="text-right rounded border border-primary" size="8" readonly value="0.00"/>
			</div>
		</div>
	</div>