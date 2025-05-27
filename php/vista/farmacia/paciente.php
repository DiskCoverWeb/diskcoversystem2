<script src="../../dist/js/farmacia/pacientes.js"></script>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?></div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
          <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
          </li>
        </ol>
      </nav>
    </div>          
</div>
<div class="row row-cols-auto  mb-2">
	<div class="btn-group">
	    <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-outline-secondary btn-sm">
            <img src="../../img/png/salire.png">
        </a>
        <a href="./farmacia.php?mod=28&acc=pacientes&acc1=Visualizar%20paciente&b=1&po=subcu#" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_pdf" title="Pacientes">
            <img src="../../img/png/pacientes.png">
        </a>           
       <a href="./farmacia.php?mod=28&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_excel" title="Descargos">
            <img src="../../img/png/descargos.png">
       </a>         
       <a href="./farmacia.php?mod=28&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulosr"  class="btn btn-outline-secondary btn-sm" onclick="">
            <img src="../../img/png/articulos.png" >
       </a>   
 	</div>
</div>
<div class="row">
	<div class="card">
		<div class="card-head">
			<b>BUSCAR CLIENTES</b>			
		</div>
		<div class="card-body">
			<div class="row">
	          <div class="col-sm-3">
	            <b>Num. Historia Clinica :</b>
	            <input type="hidden" class="form-control" id="txt_tip" value="N">
	            <input type="hidden" class="form-control" id="txt_id">   
	            <div class="input-group">
	                <input type="text" class="form-control form-control-sm" id="txt_codigo" onblur="validar_num_historia()" autocomplete="off">                
	                <button class="btn btn-outline-secondary btn-sm" title="Buscar" onclick="buscar_cod('C1','txt_codigo')"><i class="bx bx-search me-0"></i></button>
	                <!-- <span class="input-group-addon" title="Buscar"><i class="fa fa-search"></i></span> -->
	            </div>            
	          </div>
	          <div class="col-sm-5">
	             <b>Nombre</b>
	              <input type="text" name="txt_nombre" id="txt_nombre" class="form-control form-control-sm" onblur="nombres(this.value)" autocomplete="off">            
	          </div>
	          <div class="col-sm-4">
	             <b>CI / Cedula:</b>
	            <input type="text" name="txt_ruc" id="txt_ruc" class="form-control form-control-sm" onblur="paciente_existente()" onkeyup="num_caracteres('txt_ruc',10)" autocomplete="off">            
	          </div>
	        </div>
	        <div class="row mb-2">
	          <div class="col-sm-3">
	            <b>Provincia:</b>
	            <select class="form-control form-control-sm" id="ddl_provincia">
	              <option value="0">Seleccione una provincia</option>
	            </select>       
	          </div>
	          <div class="col-sm-3">
	             <b>Localidad:</b>
	            <input type="text" name="txt_localidad" id="txt_localidad" class="form-control form-control-sm" value="QUITO" autocomplete="off">
	          </div>
	          <div class="col-sm-3">
	            <b>Teléfono:</b>
	            <input type="text" name="txt_telefono" id="txt_telefono" class="form-control form-control-sm" autocomplete="off">            
	          </div>
	          <div class="col-sm-3">
	            <b>Email:</b>
	            <input type="text" name="txt_email" id="txt_email" class="form-control form-control-sm" value="comprobantes@clinicasantabarbara.com.ec" autocomplete="off">            
	          </div>
	        </div>
	        <div class="row text-end">
			    <div class="col-sm-12">
			        <div class="modal-footer">
			          <input type="hidden" name="txt_validado" id="txt_validado" value="0">
				        <button type="button" class="btn btn-primary btn-sm" id="btn_nu" onclick="nuevo_paciente()"><i class="fa fa-user-plus"></i> Nuevo cliente</button>
				        <button type="button" class="btn btn-outline-secondary btn-sm" onclick=" limpiar()"><i class="fa fa-paint-brush"></i> Limpiar</button>
				        <!-- <button type="button" class="btn btn-default"><i class="fa fa-print"></i> Imprimirr</button> -->
				    </div>            
			  	</div>
			</div>
		</div>		
	</div>	
</div>

  <div class="row mb-2">
  	<div class="card">
  		<div class="card-body">
  			<div class="row text-end">
  				<div class="col-sm-6"></div>
  				<div class="col-sm-6">
			        <input type="text" name="" placeholder="Buscar" class="form-control form-control-sm" onkeyup="cargar_clientes()" id="txt_query">
			        <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_nombre" checked="" value="N"><b> Nombre</b></label>
			        <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_codigo" value="C"><b> Num. Historia Clinica </b></label>
			        <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_ruc" value="R"><b> RUC / CI</b></label>
			    </div> 
  			</div>
  			<div class="row">
  				<div class="col-sm-12" >
			  	<div class="table-responsive">      
			  		<table class="table table-hover" id="tbl_pacientes">
			  			<thead>
			  				<th>ITEM</th>
			  				<th>NUM HISTORIA</th>
			  				<th>NOMBRE</th>
			  				<th>RUC</th>
			  				<th>TELEFONO</th>
			  				<th></th>
			  			</thead>
			  			<tbody id="tbl_pacientes_">
			  				<tr>
			  					<td></td>
			  					<td></td>
			  					<td></td>
			  					<td></td>
			  					<td></td>
			  					<td>
			  						<button class="btn btn-sm"><span class="glyphicon glyphicon-pencil"></span></button>
			  						<button class="btn btn-sm"><span class="glyphicon glyphicon-search"></span></button>
			  						<button class="btn btn-sm"><span class="glyphicon glyphicon-trash"></span></button>
			  					</td>
			  				</tr>
			  			</tbody>
			  		</table>
			  	</div>
			  </div>
  			</div>
  		</div>  		
  	</div>  	
  </div>
  
  
