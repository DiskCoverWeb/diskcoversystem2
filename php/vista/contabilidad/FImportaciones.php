<?php 

?>
<script type="text/javascript">
</script>
<script type="text/javascript">
  $(document).ready(function()
  {
       
  });
 
  </script>
<div class="row">
    <div class="col-sm-12">
            <div class="col-sm-8" style="padding:0px">
                <div class="box box-info">                    
                    <div class="box-body">
                        <div class="row">                            
                            <div class="col-sm-12">
                            	<div class="col-sm-8" style="padding:0px"><br>
                            		<select class="form-control input-sm" id="DCProveedor">
                            			<option value="">No seleccionado</option>
                            		</select>
                            	</div>
                            	<div class="col-sm-1"><br>
                            		<input type="text" class="form-control input-sm" name="" id="LblTD" style="color: red" readonly="">
                            	</div>
                            	<div class="col-sm-3" style="padding-right: 0px"><br>  
                            	    <input type="text" class="form-control input-sm" name="" id="LblNumIdent" readonly="">
                            	</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 text-center">
                <button class="btn btn-default"> <img src="../../img/png/grabar.png"  onclick="validar_formulario();"><br> Guardar</button>
                <button class="btn btn-default"  data-dismiss="modal" onclick="limpiar_retencaion()"> <img src="../../img/png/bloqueo.png" ><br> Cancelar</button>
            </div>        
    </div>
</div>
<div class="row">
	<div class="col-sm-12">
        <ul class="nav nav-tabs">
        	<li class="nav-item active">
        		<a class="nav-link" data-toggle="tab" href="#home">Importaciones</a>
        	</li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#menu1">Conceptos AIR</a>
            </li>
        </ul>
               <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane modal-body active" id="home">
            	<div class="row">
            		<div class="col-sm-12">
            			<select class="form-control input-sm">
            				<option value="">seleciones</option>
            			</select>
            		</div>
            	</div>
            	<div class="row"><br>
            		<div class="col-sm-10">
            			<div class="col-sm-8" style="padding-left: 0px;">
            				<select class="form-control input-sm">
            					<option value="">seleciones</option>
            				</select>
            			</div>
            		    <div class="col-sm-4">            			
            			    <input type="text" name="" class="form-control input-sm">         			
            		    </div>            			
            		</div>            		
            		<div class="col-sm-2">
            			<input type="text" name="" class="form-control input-sm">            			
            		</div>
            	</div>
            	<div class="row"><br>
            		<div class="col-sm-6"><br>
            		    <select class="form-control input-sm">
            			    <option value="">seleciones</option>
            		    </select>           		         			
            		</div>            		
            		<div class="col-sm-6">
            			<div class="col-sm-12  box box-info" style="margin: 0px">
            				<b>Refrendo</b>
            			</div>
            			<div class="col-sm-3" style="padding-left: 0px;">
            				<select class="form-control input-sm">
            					<option value="">seleciones</option>
            				</select>       			
            		    </div> 
            		    <div class="col-sm-2"  style="padding-left: 0px;">            			
            			    <input type="text" name="" class="form-control input-sm">         			
            		    </div> 
            		    <div class="col-sm-3"  style="padding-left: 0px;">            			
            			    <select class="form-control input-sm">
            			        <option value="">seleciones</option>
            		        </select>         			
            		    </div> 
            		    <div class="col-sm-2"  style="padding-left: 0px;">            			
            			    <input type="text" name="" class="form-control input-sm">         			
            		    </div> 
            		    <div class="col-sm-2"  style="padding-left: 0px;">            			
            			    <input type="text" name="" class="form-control input-sm">         			
            		    </div>               			
            		</div>
            	</div><br>
            	<div class="row box-info box" style="margin: 0px">
            		<div class="col-sm-12"><b>BASE IMPONIBLE</b></div>
            		<div class="col-sm-2" style="padding-left: 0px;">
            			<input type="text" name="" id="" class="form-control input-sm">            			
            		</div>
            		<div class="col-sm-1" style="padding-left: 0px;">
            			<input type="text" name="" id="" class="form-control input-sm">            			
            		</div>
            		<div class="col-sm-2" style="padding-left: 0px;">
            			<select class="form-control input-sm">
            				<option value="">seleciones</option>
            			</select>          			
            		</div>
            		<div class="col-sm-1" style="padding-left: 0px;">
            			<input type="text" name="" id="" class="form-control input-sm">            			
            		</div>
            		<div class="col-sm-1" style="padding-left: 0px;">
            			<input type="text" name="" id="" class="form-control input-sm">            			
            		</div>
            		<div class="col-sm-2" style="padding-left: 0px;">
            			<select class="form-control input-sm">
            				<option value="">seleciones</option>
            			</select>            			
            		</div>
            		<div class="col-sm-2" style="padding-left: 0px;">
            			<input type="text" name="" id="" class="form-control input-sm">            			
            		</div>
            		<div class="col-sm-1" style="padding-left: 0px; padding-right: 0px">
            			<button class="btn btn-default text-center" onclick="cambiar_air()"><i class="fa fa-arrow-right"></i><br>AIR</button>         			
            		</div>

            	</div>
                   
            </div>
            <div class="tab-pane modal-body fade" id="menu1">
            	<div class="row box-info box">
            		<div class="col-sm-6">
            			<b>INGRESE LOS DATOS DE LA RETENCION</b>
            		</div>
            		<div class="col-sm-6 text-right">
            			<b>FORMULARIO 103</b>
            		</div>
            	</div>
            	<div class="row">
            		<div class="col-sm-4">
            			<label class="radio-inline"><input type="checkbox" name=""> Retencion en la fuente</label>
            		</div>
            		<div class="col-sm-8">
            			<select class="form-control input-sm">
            				<option value="">seleciones</option>
            			</select>
            		</div>
            	</div><br>
            	<div class="row">
            		<div class="col-sm-6">
            			<div class="col-sm-3" style="padding-left: 0px;">
            				<input type="text" name="" class="form-control input-sm">            				
            			</div>
            			<div class="col-sm-3" style="padding-left: 0px;">
            				<input type="text" name="" class="form-control input-sm">            				
            			</div>
            			<div class="col-sm-3" style="padding-left: 0px;">
            				<input type="text" name="" class="form-control input-sm">            				
            			</div>
            			<div class="col-sm-3" style="padding-left: 0px;">
            				<input type="text" name="" class="form-control input-sm">            				
            			</div>            			
            		</div>
            		<div class="col-sm-6 text-right">
            			<div class="col-sm-6"></div> 
            			<div class="col-sm-6" style="padding-right: 0px">
            				<input type="text" name="" class="form-control input-sm">            				
            			</div> 
            		</div>
            	</div><br>
            	<div class="row">
            		<div class="col-sm-6" style="padding-left: 0px;">
            			<select class="form-control input-sm">
            				<option value="">seleciones</option>
            			</select>          				
            		</div>
            		<div class="col-sm-2" style="padding-left: 0px;">
            			<input type="text" name="" class="form-control input-sm">            				
            		</div>
            		<div class="col-sm-2" style="padding-left: 0px;">
            			<input type="text" name="" class="form-control input-sm">            				
            		</div>
            		<div class="col-sm-2" style="padding-left: 0px;">
            			<input type="text" name="" class="form-control input-sm">            				
            		</div>            			
            		
            	</div><br>
            	<div class="row">
            		<div class="col-sm-12">
            			<table class="table table-hover">
            				<thead>
            					<th>tabla</th>
            					<th>tabla</th>
            					<th>tabla</th>
            					<th>tabla</th>
            					<th>tabla</th>
            				</thead>
            				<tbody>
            					<tr>
            						<td>1</td>
            						<td>1</td>
            						<td>1</td>
            						<td>1</td>
            					</tr>
            				</tbody>
            			</table>
            		</div>
            	</div>
                  
            </div>
        </div>
            
    </div>
</div>
     