<?php date_default_timezone_set('America/Guayaquil'); ?> 
<script src="../../dist/js/qrCode.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script type="text/javascript">
	!function(e){var t=function(t,n){this.$element=e(t),this.type=this.$element.data("uploadtype")||(this.$element.find(".thumbnail").length>0?"image":"file"),this.$input=this.$element.find(":file");if(this.$input.length===0)return;this.name=this.$input.attr("name")||n.name,this.$hidden=this.$element.find('input[type=hidden][name="'+this.name+'"]'),this.$hidden.length===0&&(this.$hidden=e('<input type="hidden" />'),this.$element.prepend(this.$hidden)),this.$preview=this.$element.find(".fileupload-preview");var r=this.$preview.css("height");this.$preview.css("display")!="inline"&&r!="0px"&&r!="none"&&this.$preview.css("line-height",r),this.original={exists:this.$element.hasClass("fileupload-exists"),preview:this.$preview.html(),hiddenVal:this.$hidden.val()},this.$remove=this.$element.find('[data-dismiss="fileupload"]'),this.$element.find('[data-trigger="fileupload"]').on("click.fileupload",e.proxy(this.trigger,this)),this.listen()};t.prototype={listen:function(){this.$input.on("change.fileupload",e.proxy(this.change,this)),e(this.$input[0].form).on("reset.fileupload",e.proxy(this.reset,this)),this.$remove&&this.$remove.on("click.fileupload",e.proxy(this.clear,this))},change:function(e,t){if(t==="clear")return;var n=e.target.files!==undefined?e.target.files[0]:e.target.value?{name:e.target.value.replace(/^.+\\/,"")}:null;if(!n){this.clear();return}this.$hidden.val(""),this.$hidden.attr("name",""),this.$input.attr("name",this.name);if(this.type==="image"&&this.$preview.length>0&&(typeof n.type!="undefined"?n.type.match("image.*"):n.name.match(/\.(gif|png|jpe?g)$/i))&&typeof FileReader!="undefined"){var r=new FileReader,i=this.$preview,s=this.$element;r.onload=function(e){i.html('<img src="'+e.target.result+'" '+(i.css("max-height")!="none"?'style="max-height: '+i.css("max-height")+';"':"")+" />"),s.addClass("fileupload-exists").removeClass("fileupload-new")},r.readAsDataURL(n)}else this.$preview.text(n.name),this.$element.addClass("fileupload-exists").removeClass("fileupload-new")},clear:function(e){this.$hidden.val(""),this.$hidden.attr("name",this.name),this.$input.attr("name","");if(navigator.userAgent.match(/msie/i)){var t=this.$input.clone(!0);this.$input.after(t),this.$input.remove(),this.$input=t}else this.$input.val("");this.$preview.html(""),this.$element.addClass("fileupload-new").removeClass("fileupload-exists"),e&&(this.$input.trigger("change",["clear"]),e.preventDefault())},reset:function(e){this.clear(),this.$hidden.val(this.original.hiddenVal),this.$preview.html(this.original.preview),this.original.exists?this.$element.addClass("fileupload-exists").removeClass("fileupload-new"):this.$element.addClass("fileupload-new").removeClass("fileupload-exists")},trigger:function(e){this.$input.trigger("click"),e.preventDefault()}},e.fn.fileupload=function(n){return this.each(function(){var r=e(this),i=r.data("fileupload");i||r.data("fileupload",i=new t(this,n)),typeof n=="string"&&i[n]()})},e.fn.fileupload.Constructor=t,e(document).on("click.fileupload.data-api",'[data-provides="fileupload"]',function(t){var n=e(this);if(n.data("fileupload"))return;n.fileupload(n.data());var r=e(t.target).closest('[data-dismiss="fileupload"],[data-trigger="fileupload"]');r.length>0&&(r.trigger("click.fileupload"),t.preventDefault())})}(window.jQuery)
</script>
<style type="text/css">


.clearfix{*zoom:1;}.clearfix:before,.clearfix:after{display:table;content:"";line-height:0;}
.clearfix:after{clear:both;}
.hide-text{font:0/0 a;color:transparent;text-shadow:none;background-color:transparent;border:0;}
.input-block-level{display:block;width:100%;min-height:30px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
.btn-file{overflow:hidden;position:relative;vertical-align:middle;}.btn-file>input{position:absolute;top:0;right:0;margin:0;opacity:0;filter:alpha(opacity=0);transform:translate(-300px, 0) scale(4);font-size:23px;direction:ltr;cursor:pointer;}
.fileupload{margin-bottom:9px;}.fileupload .uneditable-input{display:inline-block;margin-bottom:0px;vertical-align:middle;cursor:text;}
.fileupload .thumbnail{overflow:hidden;display:inline-block;margin-bottom:5px;vertical-align:middle;text-align:center;}.fileupload .thumbnail>img{display:inline-block;vertical-align:middle;max-height:100%;}
.fileupload .btn{vertical-align:middle;}
.fileupload-exists .fileupload-new,.fileupload-new .fileupload-exists{display:none;}
.fileupload-inline .fileupload-controls{display:inline;}
.fileupload-new .input-append .btn-file{-webkit-border-radius:0 3px 3px 0;-moz-border-radius:0 3px 3px 0;border-radius:0 3px 3px 0;}
.thumbnail-borderless .thumbnail{border:none;padding:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none;}
.fileupload-new.thumbnail-borderless .thumbnail{border:1px solid #ddd;}
.control-group.warning .fileupload .uneditable-input{color:#a47e3c;border-color:#a47e3c;}
.control-group.warning .fileupload .fileupload-preview{color:#a47e3c;}
.control-group.warning .fileupload .thumbnail{border-color:#a47e3c;}
.control-group.error .fileupload .uneditable-input{color:#b94a48;border-color:#b94a48;}
.control-group.error .fileupload .fileupload-preview{color:#b94a48;}
.control-group.error .fileupload .thumbnail{border-color:#b94a48;}
.control-group.success .fileupload .uneditable-input{color:#468847;border-color:#468847;}
.control-group.success .fileupload .fileupload-preview{color:#468847;}
.control-group.success .fileupload .thumbnail{border-color:#468847;}

</style>
<script type="text/javascript" src="../../dist/js/egreso_alimentos.js"></script>
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
	<div class="col-sm-12">
		<div class="btn-group">
			<a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
				print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
				<img src="../../img/png/salire.png">
			</a>
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Guardar" onclick="guardar()">
				<img src="../../img/png/grabar.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Historial" onclick="myModal_historial()">
				<img src="../../img/png/file_crono.png" style="width:32px;height:32px">
			</button>
		</div>
		
	</div>
</div>

<div class="row mb-2">
	<div class="card" style="background: antiquewhite;">		
		<div class="card-body">
			<form id="form_datos">			
				<div class="row">	
					<label class="col-auto px-0 col-form-label"><b>Fecha de Egreso</b></label>
					<div class="col-auto">
						<input type="date" class="form-control form-control-sm" id="txt_fecha" name="txt_fecha" value="<?php echo date('Y-m-d'); ?>" readonly>		
					</div>
				</div>			
				<div class="row">
					<div class="col-lg-3 col-md-6 col-sm-12">
						<div class="row">
							<div class="col-lg-4 col-md-3 col-sm-3  d-flex align-items-center justify-content-center">
								<button type="button" class="btn btn-light" onclick="abrir_modal('A')">
									<img src="../../img/png/area_egreso.png" class="w-100" />
								</button>
							</div>
							<div class="col-lg-8 col-md-9 col-sm-9">
								<b>Area de egreso:</b>
								<div class=" d-flex align-items-center input-group-sm">
								<select class="form-select form-select-sm" id="ddl_areas" name="ddl_areas">
									<option value="">Seleccione</option>
								</select>									
								</div>
							</div>
						</div>						
					</div>
					<div class="col-lg-3 col-md-6 col-sm-12">
						<div class="row">
							<div class="col-lg-4 col-md-3 col-sm-3  d-flex align-items-center justify-content-center">
								<button type="button" class="btn btn-light" onclick="abrir_modal('M')">
									<img src="../../img/png/transporte_caja.png" class="w-100"  />
								</button>
							</div>
							<div class="col-lg-8 col-md-9 col-sm-9">
								<b>Motivo de egreso</b>								
								<div class=" d-flex align-items-center input-group-sm">
									<select class="form-select form-select-sm" id="ddl_motivo" name="ddl_motivo">
										<option value="">Seleccione</option>
									</select>
								</div>
							</div>
						</div>						
					</div>
					<div class="col-lg-3 col-md-6 col-sm-12">
						<div class="row">
							<div class="col-lg-4 col-md-3 col-sm-3 d-flex align-items-center justify-content-center">
								<button type="button" class="btn btn-light">
									<img src="../../img/png/detalle_egreso.png" class="w-100" />
								</button>
							</div>
							<div class="col-lg-8 col-md-9 col-sm-9">
								<b>Detalle de egreso:</b>
								<textarea id="txt_detalle" name="txt_detalle" rows="1"  class="form-control form-control-sm"></textarea>
							</div>
						</div>						
					</div>
					<div class="col-lg-3 col-md-6 col-sm-12">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12">
								<form enctype="multipart/form-data" id="form_img" method="post" style="width: inherit;">
									<div class="fileupload fileupload-new" data-provides="fileupload">
										<span class="btn btn-light btn-file">
										<img src="../../img/png/clip.png" style="width:20%">
										<span class="fileupload-new">Archivo Adjunto</span>
										<span class="fileupload-exists">Archivo Adjunto</span> 
											<input type="file" id="file_doc" name="file_doc" onchange="revisarAdjuntos('archivo')"/>
										</span>
										<span class="fileupload-preview"></span>
										<a href="#" class="close fileupload-exists" data-dismiss="fileupload" onclick="revisarAdjuntos('archivo')" style="float: none">×</a>
									</div>
								</form>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12">
								<button type="button" id="btn_photoupload" class="btn btn-light" onclick="$('#modal_camara').modal('show')">
									<img src="../../img/png/camara.png" style="width:20%">
									Adjuntar por cámara
								</button>
								<br>
								<div id="photoupload_span" style="display: none;">
									<span><a href="#" class="text-secondary-emphasis" onclick="$('#modal_foto').modal('show');" style="text-decoration: underline;">Ver foto</a></span>
									<a href="#" class="close" onclick="quitarFotoAdjunta()" style="float: none">×</a>
								</div>
							</div>
						</div>						
					</div>
				</div>
				<hr>
				<div class="row mb-2">
					<div class="col-lg-3 col-md-4 col-sm-12">
						<b>Codigo productos</b>
						<div class="input-group input-group-sm">
							<input type="" class="form-control form-control-sm" id="txt_cod_producto" name="txt_cod_producto" onblur="buscar_producto()">			
							<button type="button" class="btn btn-primary btn-sm" style="font-size:8pt;" title="Escanear QR" onclick="escanear_qr()">
								<i class="fa fa-qrcode" aria-hidden="true" style="font-size:8pt;"></i>
							</button>
							
						</div>
						
						<input type="hidden" id="txt_id" name="txt_id">								
					</div>	
					<div class="col-lg-3 col-md-4 col-sm-12">
						<b>Proveedor / Donante</b>
						<input type="" class="form-control form-control-sm" id="txt_donante" name="txt_donante" readonly>	
								
					</div>														
					<div class="col-lg-3 col-md-4 col-sm-12">
						<b>Grupo de producto</b>
						<input type="" class="form-control form-control-sm" id="txt_grupo" name="txt_grupo" readonly>	
								
					</div>	
					<div class="col-lg-1 col-md-2 col-sm-12">
						<b>Stock</b>
						<input type="" class="form-control form-control-sm" id="txt_stock" name="txt_stock" readonly>	
								
					</div>	
					<div class="col-lg-1 col-md-2 col-sm-12">
						<b>Unidad</b>
						<input type="" class="form-control form-control-sm" id="txt_unidad" name="txt_unidad" readonly>	
					</div>	
					<div class="col-lg-1 col-md-2 col-sm-12">
						<b>Cantidad</b>
						<input type="" class="form-control form-control-sm" id="txt_cantidad" name="txt_cantidad">									
					</div>	
				</div>
				<div class="row">					
					<div class="col-sm-12 text-end">
						<button class="btn btn-danger btn-sm">Borrar</button>
						<button type="button" class="btn btn-success btn-sm" onclick="add_egreso()">Agregar</button>
					</div>
				</div>			
			</form>
		</div>
	</div>
</div>
<div class="row mb-2">		
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-sm-12">
					<table class="table table-hover w-100" id="tbl_asignados_all">
						<thead>
							<th>Item</th>
							<th>Fecha de Egreso</th>
							<th>Producto</th>
							<th>Cantidad</th>
							<th></th>
						</thead>
						<tbody>
							<tr>
								<td></td>
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
	</div>
</div>

<div class="row">
	<div class="col-sm-12">		
		<div class="box">
		</div>	
	</div>
</div>


<!-- Modal -->
<div id="myModal_opciones" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content" style="background: antiquewhite;">
			<div class="modal-header bg-primary">
				<h4 class="modal-title text-white" id="lbl_titulo_modal"></h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="row text-center" id="pnl_opciones"></div> 
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light border border-1" data-bs-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>

<div id="modal_qr_escaner" class="modal fade"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
      <div class="modal-content" style="background: antiquewhite;">
          <div class="modal-header bg-primary">
			  <h4 class="modal-title text-white">Escanear QR</h4>
			  <button type="button" class="btn-close" aria-label="Close" onclick="cerrarCamara()"></button>
          </div>
          <div class="modal-body">
          	 <div class="row">
              <div class="col-sm-12">
                <select class="form-select" id="ddl_camaras" name="ddl_camaras" onchange="cambiarCamara()">
                  <option value="0">Camara 1</option>                 
                  <option value="1">Camara 2</option>                   
                </select>               
              </div>              
            </div>
			<div id="qrescaner_carga">
              <div style="height: 100%;width: 100%;display:flex;justify-content:center;align-items:center;">
                <img src="../../img/gif/loader4.1.gif" width="20%"></div>
            </div>
           <div id="reader" style="height: 100%;width: 100%;"></div>
            <p><strong>QR Detectado:</strong> <span id="resultado"></span></p>

          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-danger" onclick="cerrarCamara()">Cerrar</button>
          </div>
      </div>
  </div>
</div>

<div id="myModal_arbol_bodegas" class="modal fade myModalNuevoCliente" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" style="background: antiquewhite;">
            <div class="modal-header bg-primary">
				<h4 class="modal-title text-white">Seleccion manual de bodegas</h4>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenido_prov">
            		<ul class="tree_bod" id="arbol_bodegas"></ul>               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border border-1" data-bs-dismiss="modal">Cerrar</button>
            </div> 
        </div>
    </div>
</div>

<div id="myModal_historial" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content" style="background: antiquewhite;">
      <div class="modal-header bg-primary">
		  <h4 class="modal-title text-white" id="lbl_titulo_modal"></h4>
		  <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
      		
			<div class="row d-flex justify-content-center row-cols-auto">
	      		<div class="row row-cols-auto col-auto align-items-center">
	      			<label class="col-auto px-0 col-form-label"><b>Desde</b></label>
                    <div class="col-auto">
                        <input type="date" class="form-control form-control-sm" name="txt_desde" id="txt_desde" value="<?php echo date('Y-m-d'); ?>">    
                    </div>
	        	</div>	
                <div class="row row-cols-auto col-auto align-items-center">
                    
	      			<label class="col-auto px-0 col-form-label"><b>Hasta</b></label>
                    <div class="col-auto">
                        <input type="date" class="form-control form-control-sm" name="txt_hasta" id="txt_hasta" value="<?php echo date('Y-m-d'); ?>">    
                    </div>
	        		
	        	</div>	
	        	
        		<div class="col-sm-5 d-flex justify-content-end">
        			<button class="btn btn-primary btn-sm" onclick="lista_egreso_checking();">Buscar</button>
        		</div>		
      		</div>
        	<div class="row mt-2">
        		
						<div class="col-sm-12">
							<div class="table-responsive">
								<table class="table-sm table-hover table bg-light" id="tbl_asignados_check">
									<thead class="text-center bg-primary text-white">
										<th><b>Item</b></th>
										<th><b>Fecha de Egreso</b></th>
										<th><b>Usuario</b></th>
										<th><b>Area Egreso</b></th>
										<th><b>Detalle Egreso</b></th>
										<th><b>Motivo</b></th>
										<th><b>Observacion</b></th>
										<th></th>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>	
						</div>
					</div>
				
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light border border-1" data-bs-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="modal_camara" class="modal fade"  role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
	<div class="modal-dialog modal-xl">
		<div class="modal-content" style="background-color:antiquewhite;">
			<div class="modal-header bg-primary">
				<h4 class="modal-title text-white">Tomar Foto</h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="col-sm-12">
          <select class="form-select" id="ddl_camaras" name="ddl_camaras" onchange="cambiarCamara()">
            <option value="0">Camara 1</option>                
          </select>               
        </div> 
				<div id="carga_camara">
					<div style="height: 100%;width: 100%;display:flex;justify-content:center;align-items:center;">
						<img src="../../img/gif/loader4.1.gif" width="20%">
					</div>
				</div>
				<div class="row justify-content-center" id="contenedor_camera" style="display: none; position: relative;">
					<video id="video">La camara no está funcional actualmente.</video>
					<button id="btnTomarFoto" class="btn btn-light" style="position:absolute; width: fit-content; bottom: 20px; margin: 0 auto;">Tomar foto</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modal_foto" class="modal fade"  role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
	<div class="modal-dialog modal-xl">
		<div class="modal-content" style="background-color:antiquewhite;">
			<div class="modal-body">
				<canvas id="canvas" style="display: none;"> </canvas>
				<div class="row justify-content-center">
					<img id="photo" alt="La foto tomada aparecerá aquí." style="width: 60%;"/>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-secondary" onclick="$('#modal_foto').modal('hide');$('#modal_camara').modal('show');">Volver a tomar</button>
				<button type="button" class="btn btn-success" onclick="adjuntarFoto()">Adjuntar</button>
			</div>
		</div>
	</div>
</div>

 <script src="../../dist/js/arbol_bodegas/arbol_bodega.js"></script>