<?php date_default_timezone_set('America/Guayaquil'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script type="text/javascript" src="../../dist/js/inventario/solicitud_material.js"></script>
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
				<button type="button" class="btn btn-outline-secondary" title="Guardar" id="btn_guardar" onclick="grabar_solicitud()" >
				  <img src="../../img/png/grabar.png">
				</button>
			<!-- 	<button type="button" class="btn btn-outline-secondary" title="Imprimir QR" onclick="imprimir_pedido()">
					<img src="../../img/png/impresora.png" height="32px">
				</button> -->
				<!-- <button type="button" class="btn btn-outline-secondary" title="Imprimir QR PDF" onclick="imprimir_pedido_pdf()">
					<img src="../../img/png/paper.png" height="32px">
				</button>	 -->	
			</div>
	</div>
</div>

<div class="row">
    <div class="card">
      <div class="card-body">
        <div class="row">
          
        <div class="col-sm-2">
              <b>Contratista</b><br>
              <span><?php echo $_SESSION['INGRESO']['Nombre']; ?></span>
        </div>
        <div class="col-sm-2">
            <b>Fecha</b>
            <input type="date" name="txt_fecha" id="txt_fecha" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>" readonly >
        </div>        
        <div class="col-sm-5">
            <b>Producto / articulo </b>
             <div class="d-flex align-items-center">   
                <select class="form-select form-select-sm" id="ddl_productos" name="ddl_productos">
	                <option value="">Seleccione producto</option>
	              </select>         
                <button type="button" class="btn btn-primary btn-flat btn-sm" onclick="buscar_modal()"><i class="fa fa-search m-0"></i></button>
              
              </div>                  
            <label id="ddl_familia" name="ddl_familia" ></label>
            <input type="hidden" name="ddl_idfamilia" id="ddl_idfamilia">
        </div>
        <div class="col-sm-3">
          <b>Marcas </b>
          <div class="d-flex align-items-center">
            <select class="form-select form-select-sm" id="ddl_marca" name="ddl_marca">
              <option value="">Seleccione</option>
            </select>
              <button type="button" class="btn btn-primary btn-flat btn-sm" onclick="modal_marcas()"><i class="fa fa-plus m-0"></i></button>
          </div>
        </div>
      </div>
      <div class="row">

        <div class="col-sm-2">
            <b>Fecha Entrega</b>
            <input type="date" name="txt_fechaEnt" id="txt_fechaEnt" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>" >
        </div>
        <div class="col-sm-4">
            <b>Observacion</b>  
            <input type="text" name="txt_observacion" id="txt_observacion" class="form-control form-control-sm" placeholder="Observacion" >
        </div>
        <div class="col-sm-1">
          <b>unidad</b>
          <input type="text" name="txt_uni" id="txt_uni" class="form-control form-control-sm" placeholder="0"  onblur="calcular()" readonly>
        </div> 
        <div class="col-sm-1">
          <b>Costo</b>
          <input type="text" name="txt_costo" id="txt_costo" class="form-control form-control-sm" placeholder="0"  onblur="calcular()" readonly>
        </div>
        <div class="col-sm-1">
          <b>Stock</b>
          <input type="text" name="txt_stock" id="txt_stock" class="form-control form-control-sm" readonly >
        </div>
        <div class="col-sm-1">
          <b>Cantidad</b>
          <input type="text" name="txt_cantidad" id="txt_cantidad" class="form-control form-control-sm" placeholder="0" onblur="calcular()" >
        </div>
         <div class="col-sm-1">
          <b>Total</b>
          <input type="text" name="txt_total" id="txt_total" class="form-control form-control-sm" placeholder="0" readonly >
        </div>                
        <div class="col-sm-1 text-right">
          <br>
            <button type="button" class="btn  btn-sm btn-primary" onclick="guardar_linea()" >Agregar</button>
        </div>
      </div>
      </div>  
    </div>
  </div>
<div class="row">
	<div class="card">
		<div class="card-body">
		  <div class="row">
		    <div class="col-sm-12">
		        <table class="table">
		          <thead>
		            <thead>
		              <th>item</th>
		              <th>Codigo</th>
		              <th>Producto</th>
		              <th>Cantidad</th>
		              <th>Unidad</th>   
		              <th>Costo ref</th>     
		              <th>Total ref </th> 
		              <th>Marca</th>          
		              <th>Fecha Solicitud</th>
		              <th>Fecha Entrega</th> 
		              <th>Observacion</th>
		              <th></th>
		            </thead>
		            <tbody id="tbl_body">
		            
		            </tbody>
		          </thead>
		        </table>
		    </div>    
		  </div>  
		</div>
	</div>
</div>

 <div id="myModal_marcas" class="modal fade myModalMArcas" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Nueva Marca</h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12">
                    <b>Nombre de marca</b>
                    <input type="" class="form-control input-sm" name="txt_new_marca" id="txt_new_marca">
                </div>
                
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="guardar_marca()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
  </div>

   <div id="myModal_buscar" class="modal fade myModalBuscar" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Buscar productos</h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-10">
                    <b>Nombre producto</b>
                    <input type="" class="form-control input-sm" name="txt_search_producto" id="txt_search_producto">
                    <br>
                </div>
                <div class="col-sm-2">
                  <br>
                  <button type="button" class="btn btn-primary" onclick="bucar_producto_modal()"><i class="fa fa-search"></i> Buscar</button>                  
                </div> 
                <div class="col-sm-12" style="overflow-y: scroll; height: 300px;">
                    <table class="table table-hover text-sm">
                      <thead>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Costo</th>
                        <th></th>
                      </thead>
                      <tbody id="tbl_producto_search">
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                      </tbody>
                    </table>
                </div>
                
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="guardar_marca()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
  </div>

