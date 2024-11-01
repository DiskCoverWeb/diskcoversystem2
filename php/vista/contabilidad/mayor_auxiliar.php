<script src="../../dist/js.mayor_auxiliar.js"></script>

   	<div class="row">
   		<div class="col-lg-4 col-sm-4 col-md-8 col-xs-12">
   			<div class="col-xs-2 col-md-2 col-sm-2">
   				<a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" data-toggle="tooltip" title="Salir de modulo" class="btn btn-default">
            		<img src="../../img/png/salire.png">
            	</a>
            </div>   
             <div class="col-xs-2 col-md-2 col-sm-2">
            	<button title="Consultar un Mayor Auxiliar"  data-toggle="tooltip" class="btn btn-default" onclick="consultar_datos(true,Individual);">
            		<img src="../../img/png/consultar.png" >
            	</button>
             </div>		        
        	<div class="col-xs-2 col-md-2 col-sm-2">
        	   <button type="button" class="btn btn-default" data-toggle="dropdown" title="Descargar PDF">
        	      <img src="../../img/png/pdf.png">
               </button>
  		       	<ul class="dropdown-menu">
  		       	 <li><a href="#" id="imprimir_pdf">Impresion normal</a></li>
  		       	  <li><a href="#" id="imprimir_pdf_2">Por Sub Modulo / Centro de costos</a></li>
  		       	</ul>
  		    </div>            	
        	<div class="col-xs-2 col-md-2 col-sm-2">
        		<button type="button" class="btn btn-default" data-toggle="dropdown"  title="Descargar Excel">
        	      <img src="../../img/png/table_excel.png">
               </button>
  		       	<ul class="dropdown-menu">
  		       	 <li><a href="#" id="imprimir_excel">Impresion normal</a></li>
  		       	  <li><a href="#" id="imprimir_excel_2">Por Sub Modulo / Centro de costos</a></li>
  		       	</ul>            	
            </div>
             <div class="col-xs-2 col-md-2 col-sm-2">
            	<button title="Consultar Varios Mayor Auxiliar"  class="btn btn-default" data-toggle="tooltip" onclick="consultar_datos(false,Individual);">
            		<img src="../../img/png/es.png" >
            	</button>
            </div>           
   		</div>
   		
   	</div>
	<div class="row">          
              
	  	<div class="col-sm-3"><br>
	  	   <b>Cuenta inicial:</b>
             <input type="text" name="txt_CtaI" id="txt_CtaI" class="input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
			 <br>
             <b>Cuenta final:&nbsp;&nbsp;&nbsp;</b>
             <input type="text" name="txt_CtaF" id="txt_CtaF" onblur="llenar_combobox_cuentas()" class="input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" > 
        </div>
	  	<div class="col-sm-3"><br>
	  		<b>Desde:</b>
            <input type="date" name="desde" id="desde" class="input-xs"  value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);">
			<br>
            <b>Hasta:&nbsp;</b>
            <input type="date" name="hasta" id="hasta"  class="input-xs"  value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);">  	              	
	  	</div>

	  	<div class="col-sm-3">
                <label style="margin:0px"><input type="checkbox" name="CheckUsu" id="CheckUsu">  <b>Por usuario</b></label>
                <select class="form-control input-xs" id="DCUsuario"  onchange="consultar_datos(true,Individual);">
                	<option value="">Seleccione usuario</option>
                </select>
          	    <label id="lblAgencia"><input type="checkbox" name="CheckAgencia" id="CheckAgencia">  <b>Agencia</b></label>
          	     <select class="form-control input-xs" id="DCAgencia" onchange="consultar_datos(true,Individual);">
                	<option value="">Seleccione agencia</option>
                </select>             
        </div>
        <div class="col-sm-3">
        	<b>Por cuenta</b>
                <select class="form-control input-xs" id="DCCtas" onchange="consultar_datos(true,Individual);">
                	<option value="">Seleccione cuenta</option>
                </select>
          	    <b>Saldo anterior MN:</b> 
          	    <input type="text" name="OpcP" id="LabelTotSaldoAnt" class="input-xs">           
        </div>		
	</div>
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
	  	    			
	  	    	   <div class="table-responsive" id="DGMayor">
	  	    	   		  	    	   	
	  	    	   </div>
	  	    	 </div>		  	    	  	    	
	  	    </div>
	  	   <!--  <div class="table-responsive">
	  	    	<table>
	  	    	 <tr><td width="70px"><b>DEBE:</b></td><td id="debe" width="70px">0.00</td><td width="70px"><b>HABER:</b></td><td id="haber" width="70px">0.00</td><td width="100px"><b>SALDO MN:</b></td><td id="saldo" width="70px">0.00</td></tr>
	  	    	</table>	  	    	 	
	  	    </div>  -->
	  	</div>
	  </div>
	  <div class="row">
	  	<div class="col-xs-2">
	  		<b>DEBE:</b>
	  	</div>
	  	<div class="col-xs-2">
	  		<input type="text" class="form-control input-sm" name="debe" id="debe" value="0.00" readonly>	  		
	  	</div>
	  	<div class="col-xs-2">
	  		<b>HABER:</b>
	  	</div>
	  	<div class="col-xs-2">	  		
	  		<input type="text" class="form-control input-sm" name="haber" id="haber" value="0.00" readonly>
	  	</div>
	  	<div class="col-xs-2">
	  		<b>SALDO MN:</b>
	  	</div>
	  	<div class="col-xs-2">	  		
	  		<input type="text" class="form-control input-sm" name="saldo" id="saldo" value="0.00" readonly>
	  	</div>

	  </div>