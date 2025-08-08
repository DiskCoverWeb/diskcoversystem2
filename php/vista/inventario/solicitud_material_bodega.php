
<?php  $_SESSION['INGRESO']['modulo_']='60'; 
$marca = '';
$proye = '';
if(isset($_GET['marca'])){$marca=$_GET['marca'];}
if(isset($_GET['proyecto'])){$proye=$_GET['proyecto'];}

?>
<script type="text/javascript">
  var f = '';
  var nuevaTabla = false;
  var marca = '<?php $marca; ?>';

  $(document).ready(function()
  {
    	$('.select2').select2();
    	marcas();
    	proyectos();
    	verificar_cuenta();

    	$('#contenedor_tabla_csv').hide();
	    autocmpletar_cc();
	    cargar_entrega();
	    autocmpletar();
	    marca = '<?php echo $marca; ?>'
	    if(marca == '01' && marca!=''){
	      $('#ob').css('display','none')
	    }
  });
</script>
<script type="text/javascript" src="../../dist/js/inventario/solicitud_material_bodega.js"></script>
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
<div class="row mb-2">
	<div class="col-sm-6">
		 <div class="btn-group" role="group" aria-label="Basic example">
			<a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
						  print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
				  <img src="../../img/png/salire.png">
			</a>
			<button type="button" class="btn btn-outline-secondary" id="imprimir_pdf" title="Descargar PDF">
		        <img src="../../img/png/impresora.png">
		    </button>  
		    <button type="button" class="btn btn-outline-secondary" id="imprimir_excel" title="Descargar Excel">
		        <img src="../../img/png/table_excel.png">
		    </button>  
			<button title="Mayorizar Articulos"  class="btn btn-outline-secondary" onclick="mayorizar_inventario()">
			    <img src="../../img/png/update.png" >
			</button>
			<button title="Guardar y generar Comprobante"  class="btn btn-outline-secondary" onclick="genera_comprobantes_por_fecha();">
			        <img src="../../img/png/grabar.png" >
			</button>      
		</div>
	</div>
</div>

<div class="row">
  <div class="col-sm-12 text-center">
    <h1><b>SOLICITUD DE MATERIA PARA BODEGA</b></h1>
  </div>
</div>
    
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<input type="hidden" name="" id="TC">
		        <input type="hidden" name="" id="valor_total">
		        <input type="hidden" name="" id="valor_total_linea">
		        <input type="hidden" name="" id="fechas_compro">
        
				<div class="row">
		        	<div class="col-sm-3">
		        		<b class="mt-4">Contratista</b>
		        		<br>
		        		<label><?php echo $_SESSION['INGRESO']['Nombre']; ?></label>
		        	</div>          
			        <div class="col-sm-2">
			          <b class="mt-4">Proceso</b><br>
			          <label id="lbl_marca"></label>
			        </div>
		         	<div class="col-sm-4">
			          <b class="mt-4">Proyecto</b><br>
			          <label id="lbl_proyecto"></label>
			        </div>
			        <div class="col-sm-2">
			          <b>Fecha</b><br>
			           <input type="date" name="txt_fecha" id="txt_fecha" class="form-control form-control-sm" value="<?php echo date('Y-m-d');?>">
			        </div>		          	
		        </div>
		        <div class="row">
		        	<div class="col-sm-3">
		            	<b>Centro de costos</b><br>
		                 <select class="form-control" id="ddl_cc_" onchange="autocmpletar_rubro();autocmpletar()">
		                   <option value="">Centro de costos</option>
		                 </select>
		            </div>
		            <div class="col-sm-2">
	                  <b>Codigo</b>
	                  <input type="text" name="txt_codigo_" id="txt_codigo_" disabled="" class="form-control form-control-sm" style="padding: 0px 3px 0px 3px;">
	                </div>
	                <div class="col-sm-4"  style=" padding-left: 2px;  padding-right: 0px;">
	                   <b>Descripcion</b><br>
	                  <select class="form-control input-sm select2" id="ddl_productos_" name="ddl_productos_" onchange="cargar_datos()">
	                    <option value="">Seleccione producto</option>
	                  </select>
	                </div>
	                <div class="col-sm-1">
                       <b>UNI</b>                  
                       <input type="" name="txt_uni_" id="txt_uni_" disabled="" class="form-control form-control-sm" style="padding: 6px 5px;">
                     </div>
                    <div class="col-sm-1">
                      <b>Stock </b>                        
                      <input type="text" class="form-control form-control-sm" name="txt_stock" id="txt_stock" readonly=""  style="padding: 6px 5px;">
                    </div>
                    <div class="col-sm-1">
	                  <b>Cantidad</b>
	                  <input name="txt_cant_" id="txt_cant_" placeholder="Cantidad" class="form-control form-control-sm" onblur="validar_stock()" value="0" type="text" onkeyup="comprueba_negativos('cant')">
	                </div>
		        </div>
		        <div class="row">
		        	<div class="col-sm-3">
	                  <b>Rubro</b><br>
	                    <select class="form-control form-control-sm" id="ddl_rubro_" name="ddl_rubro_">
	                      <option value="">Rubro</option>
	                    </select>
	                </div> 
	                <div class="col-sm-5">
	                	<div id="ob">
	                		<b>Observaciones</b>
	                  		<input placeholder="observacion" class="form-control form-control-sm" id="txt_obs_"/>	                		
	                	</div>
	                </div>
	                <div class="col-sm-4 text-end">
	                	<br>
	                    <button class="btn btn-primary btn-sm" title="Agregar" onclick="Guardar();"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Agregar</button>
	                    <input type="hidden" name="" id="num_filas">                	
	                </div>        
		        	
		        </div>
			</div>
		</div>
	</div>
</div>
<div class="row mb-2">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<input type="hidden" name="txt_CodMar" id="txt_CodMar" class="form-control input-sm" value="<?php echo $marca; ?>">
        		<input type="hidden" name="txt_proyecto" id="txt_proyecto" class="form-control input-sm" value="<?php echo $proye; ?>">  
        		<div class="table-responsive">
        			<table class="table " id="tbl_contenido_entrega">
			          <thead>
			            <th>Fecha</th>
			            <th>Codigo</th>
			            <th>Producto</th>
			            <th>UNI</th>
			            <th>Cant</th>
			            <th>Centro Costo</th>
			            <th>Rubro</th>
			            <th></th>
			          </thead>
			          <tbody id="contenido_entrega">
			            
			          </tbody>
			        </table>
        		</div>
			</div>
		</div>
	</div>
</div> 
 

<div id="myModal_proyecto" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
 <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <!-- <h4 class="modal-title">Cliente Nuevo</h4> -->
      </div>
      <div class="modal-body">
        <b>Proyectos</b>
        <select class="form-control" name="ddl_proyecto" id="ddl_proyecto">
          <option value="">Seleccione proyecto</option>
        </select>     
        <b>Proceso</b>
        <select class="form-control" name="ddl_marca" id="ddl_marca">
          <option value="">Seleccione marca</option>
        </select>
           
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-sm" onclick="generar_proyecto()">Aceptar</button> 
        <a href="../vista/inventario.php?mod=03" class="btn btn-default">Cerrar</a>
      </div>
    </div>

  </div>
</div>