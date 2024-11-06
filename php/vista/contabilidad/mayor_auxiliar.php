<script src="../../dist/js.mayor_auxiliar.js"></script>

   	<div class="row">
   		<div class="row col-5">
   			<div class="col-2 m-0 pe-0 d-flex">
   				<a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" data-toggle="tooltip" title="Salir de modulo" class="btn btn-default border border-3 rounded-2">
            		<img src="../../img/png/salire.png">
            	</a>
            </div>   
             <div class="col-2 m-0 pe-0 d-flex">
            	<button title="Consultar un Mayor Auxiliar"  data-toggle="tooltip" class="btn btn-default border border-3 rounded-2" onclick="consultar_datos(true,Individual);">
            		<img src="../../img/png/consultar.png" >
            	</button>
             </div>		        
        	<div class="col-2 m-0 pe-0 d-flex">
        	   <button type="button" class="btn btn-default border border-3 rounded-2" data-toggle="dropdown" title="Descargar PDF">
        	      <img src="../../img/png/pdf.png">
               </button>
  		       	<ul class="dropdown-menu">
  		       	 <li><a href="#" id="imprimir_pdf">Impresion normal</a></li>
  		       	  <li><a href="#" id="imprimir_pdf_2">Por Sub Modulo / Centro de costos</a></li>
  		       	</ul>
  		    </div>            	
        	<div class="col-2 m-0 pe-0 d-flex">
        		<button type="button" class="btn btn-default border border-3 rounded-2" data-toggle="dropdown"  title="Descargar Excel">
        	      <img src="../../img/png/table_excel.png">
               </button>
  		       	<ul class="dropdown-menu">
  		       	 <li><a href="#" id="imprimir_excel">Impresion normal</a></li>
  		       	  <li><a href="#" id="imprimir_excel_2">Por Sub Modulo / Centro de costos</a></li>
  		       	</ul>            	
            </div>
             <div class="col-2 m-0 pe-0 d-flex">
            	<button title="Consultar Varios Mayor Auxiliar"  class="btn btn-default border border-3 rounded-2" data-toggle="tooltip" onclick="consultar_datos(false,Individual);">
            		<img src="../../img/png/es.png" >
            	</button>
            </div>           
   		</div>
   		
   	</div>
	<div class="row">          
              
	  	<div class="col-3 p-0 m-0 ps-3"><br>
	  	   	<div>	
				<b class="">Cuenta inicial:</b>
            	<br>
				<input type="text" name="txt_CtaI" id="txt_CtaI" class="col-7 border border-2" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
			</div>
			<div> 
				<b>Cuenta final:</b>
				<br>
				<input type="text" name="txt_CtaF" id="txt_CtaF" onblur="llenar_combobox_cuentas()" class="col-7 border border-2" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" > 
			</div>
		</div>
	  	<div class="col-3 mt-2"><br>
	  		<div class="row p-0 m-0">
				<b class="col-2">Desde:</b>
				<input type="date" name="desde" id="desde" class="col-6 ms-4 border border-2 border-dark"  value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);">
			</div>
            <div class="row p-0 m-0">
				<b class="col-2">Hasta:&nbsp</b>
            	<input type="date" name="hasta" id="hasta"  class="col-6 ms-4 border border-2 border-dark" value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);">  	              	
			</div>
			</div>

	  	<div class="col-3 row pt-4">
				<div class="">
					<label style="margin:0px"><input type="checkbox" name="CheckUsu" id="CheckUsu"><b class=""> Por usuario</b></label>
					<select class="form-control form-control-sm h-25" style="font-size: 0.83rem;" id="DCUsuario"  onchange="consultar_datos(true,Individual);">
						<option value="" style="">Seleccione usuario</option>
					</select>
				</div>
				<!--No aparece aparentemente en la vieja plantilla
				<div class="col-6">
					<label id="lblAgencia"><input type="checkbox" name="CheckAgencia" id="CheckAgencia"><b class=""> Agencia</b></label>
					<select class="form-control" class="" style="font-size: 0.83rem; height: 26%" id="DCAgencia" onchange="consultar_datos(true,Individual);">
						<option value="">Seleccione agencia</option>
					</select>
				</div>             
				-->
		</div>
        <div class="col-3 pt-4">
        	<b>Por cuenta</b>
                <select class="form-control form-control-sm h-25" style="font-size: 0.83rem" id="DCCtas" onchange="consultar_datos(true,Individual);">
                	<option value="">Seleccione cuenta</option>
                </select>
          	    <b>Saldo anterior MN:</b> 
          	    <input type="text" name="OpcP" id="LabelTotSaldoAnt" class="border border-1">          
        </div>		
	</div>
	  <!--seccion de panel-->
	  <div class="row">
	  	<input type="input" name="OpcU" id="OpcU" value="true" hidden="">
	  	<div class="col-sm-12">
	  		<ul class="nav nav-tabs">
	  		   <li class="active p-2 fw-normal">
	  		   	<a data-toggle="tab" href="#home" class="h6" id="titulo_tab" onclick="activar(this)">Mayores auxiliares</a></li>
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
	  	<div class="col-sm-2 pt-2">
	  		<b>DEBE:</b>
	  	</div>
	  	<div class="col-sm-2 pt-2">
	  		<input type="text" class="form-control form-control-sm" style="font-size: 0.70rem" name="debe" id="debe" value="0.00" readonly>	  		
	  	</div>
	  	<div class="col-sm-2 pt-2">
	  		<b>HABER:</b>
	  	</div>
	  	<div class="col-sm-2 pt-2">	  		
	  		<input type="text" class="form-control form-control-sm" style="font-size: 0.70rem" name="haber" id="haber" value="0.00" readonly>
	  	</div>
	  	<div class="col-sm-2 pt-2">
	  		<b>SALDO MN:</b>
	  	</div>
	  	<div class="col-sm-2 pt-2">	  		
	  		<input type="text" class="form-control form-control-sm" style="font-size: 0.70rem" name="saldo" id="saldo" value="0.00" readonly>
	  	</div>

	  </div>