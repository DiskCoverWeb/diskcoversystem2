<link rel="stylesheet" href="../../dist/css/arbol.css">
<script src="../../dist/js/catalogo_bodega_fact.js">
	 
</script>
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
<div class="row">
	<div class="col-sm-12">
		<div class="btn-group" role="group" aria-label="Basic example">
			<a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
						print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
				<img src="../../img/png/salire.png">
			</a>
			<button class="btn btn-outline-secondary"  data-toggle="tooltip" title="Grabar" onclick="guardarINV()">  <img src="../../img/png/grabar.png"></button>
			<a title="IMPRIMIR ETIQUETA DE PRODUCTO" id="imprimir_etiqueta" class="btn btn-outline-secondary" onclick="imprimirEtiqueta()">
				<img src="../../img/png/paper.png" height="32px">
			</a>
		</div>
	</div>
</div>
<div class="row mt-1">
	<div class="col-sm-6">
		<div class="card">
			<div class="card-body" id="tabla" style="overflow-y:auto">
				<ol class="tree" id="tree1">
				</ol>	
			</div>		
		</div>
	</div>
    <div class="col-sm-6">
		<form id="form_datos" name="form_datos">
			<div class="row" style="margin-bottom:5px;">
				<div class="col-sm-7">
					<b>Codigo del producto</b>
					<input type="hidden" name="txt_padre" id="txt_padre">
					<input type="hidden" name="txt_padre" id="txt_padre_nl">
					<input type="hidden" name="txt_anterior" id="txt_anterior">
					<input type="text" name="txt_codigo" id="txt_codigo" class="form-control input-xs" placeholder="<?php echo "CCC.CCC.CCCC.CCCCC.CCCC";/*$_SESSION['INGRESO']['Formato_Inventario'];*/ ?>" onblur="generarQR()">
				</div>
				<div class="col-sm-5">
					<b>Nomenclatura</b>
					<input type="text" name="txt_nomenclatura" id="txt_nomenclatura" class="form-control input-xs" >
				</div>
			</div>
			<div class="row" style="margin-bottom:5px;">
				<div class="col-sm-12">
					<b>Concepto o detalle del producto</b>
					<input type="text" name="txt_concepto" id="txt_concepto" class="form-control input-xs">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<b>Código QR</b>
					<div style="background-color: #a0a0a0;border-radius: 5px;">
						<div style="display:flex;justify-content:center;padding:5px;">
							<img id="codigo_qr" src="" style="min-width:200px;min-height:200px;max-height:200px;max-width:200px;object-fit:cover;"/>
						</div>
					</div>
				</div>
			</div>
		</form>
    	<!-- <button class="btn btn-default"  data-toggle="tooltip" title="Imprimir Grupo" onclick="codigo_barras_grupo();"><img src="../../img/png/impresora.png"></button><br> -->
    	<!-- <button class="btn btn-default"  data-toggle="tooltip" title="Imprimir" onclick="cantidad_codigo_barras();">  <img src="../../img/png/barcode.png"></button><br> -->
    	 
    </div>
</div>
<!--<form id="form_datos" name="form_datos">
<div class="row">
	<div class="col-sm-2">
		<b>Codigo del producto</b>
		<input type="hidden" name="txt_padre" id="txt_padre">
		<input type="hidden" name="txt_padre" id="txt_padre_nl">
		<input type="hidden" name="txt_anterior" id="txt_anterior">
		<input type="text" name="txt_codigo" id="txt_codigo" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Inventario']; ?>" >
	</div>
	<div class="col-sm-10">
		<b>Concepto o detalle del producto</b>
		<input type="text" name="txt_concepto" id="txt_concepto" class="form-control input-xs">
	</div>
</div>-->

<!-- <div class="row">
	<div class="col-sm-3">
		<div class="form-group">
          <label class="col-sm-6" style="padding:0px">CTA. INVENTARIO</label>
          <div class="col-sm-6" style="padding:0px">
            <input type="text" name="cta_inventario" id="cta_inventario" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
          </div>
        </div>
	</div>
	<div class="col-sm-3" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-6" style="padding:1px">CTA. COSTO DE VENTA</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="cta_costo_venta" id="cta_costo_venta" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
          </div>
        </div>
	</div>
	<div class="col-sm-3" style="padding:1px;">
		<div class="form-group">
          <label class="col-sm-5" style="padding:1px">CTA. DE VENTA</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="cta_venta" id="cta_venta" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
          </div>
        </div>
	</div>
	<div class="col-sm-3" style="padding:1px;">
		<div class="form-group">
          <label class="col-sm-6" style="padding:1px">CTA. VENTA TARIFA 0%</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="cta_tarifa_0" id="cta_tarifa_0" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
          </div>
        </div>
	</div>
</div> -->
<!-- <div class="row">
	<div class="col-sm-4">
		<div class="form-group">
          <label class="col-sm-7" style="padding:1px">CTA. DE VENTA AÑO ANTERIOR</label>
          <div class="col-sm-5" style="padding:1px">
            <input type="text" name="cta_venta_anterior" id="cta_venta_anterior" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
          </div>
        </div>
	</div>
	<div class="col-sm-2"  style="padding:1px">
		<div class="form-group">
          <label class="col-sm-5" style="padding:1px">UNIDAD: U:</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="txt_unidad" id="txt_unidad" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-1" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-4" style="padding:1px">P.V.P</label>
          <div class="col-sm-8" style="padding:1px">
            <input type="text" name="pvp" id="pvp" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-1" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-5" style="padding:1px">P.V.P2</label>
          <div class="col-sm-7" style="padding:3px">
            <input type="text" name="pvp2" id="pvp2" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-1" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-5" style="padding:1px">P.V.P3</label>
          <div class="col-sm-7" style="padding:3px">
            <input type="text" name="pvp3" id="pvp3" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-2" style="padding:1px">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
		          <label class="col-sm-6" style="padding:1px">MINIMO</label>
		          <div class="col-sm-6" style="padding:3px">
		            <input type="text" name="minimo" id="minimo" class="form-control input-xs">
		          </div>
		        </div>				
			</div>
			<div class="col-sm-6">
				<div class="form-group">
		          <label class="col-sm-6" style="padding:1px">MAXIMO</label>
		          <div class="col-sm-6" style="padding:3px">
		            <input type="text" name="maximo" id="maximo" class="form-control input-xs">
		          </div>
		        </div>				
			</div>
		</div>
	</div>	
</div> -->
<!-- <div class="row">
	<div class="col-sm-4">
		<div class="form-group">
          <label class="col-sm-5" style="padding:1px">CODIGO DE BARRAS</label>
          <div class="col-sm-7">
            <input type="text" name="txt_barras" id="txt_barras" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
          <label class="col-sm-2" style="padding:1px">MARCA</label>
          <div class="col-sm-10">
            <input type="text" name="txt_marca" id="txt_marca" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-5">
		<div class="form-group">
          <label class="col-sm-4" style="padding:1px">REGISTRO SANITARIO</label>
          <div class="col-sm-8">
            <input type="text" name="txt_reg_sanitario" id="txt_reg_sanitario" class="form-control input-xs">
          </div>
        </div>
	</div>
</div> -->
<!-- <div class="row">
	<div class="col-sm-3">
		<div class="form-group">
          <label class="col-sm-3" style="padding:1px">UBICACION</label>
          <div class="col-sm-9">
            <input type="text" name="txt_ubicacion" id="txt_ubicacion" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-3" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-4" style="padding:1px">COD. I.E.S.S</label>
          <div class="col-sm-8" style="padding:1px">
            <input type="text" name="txt_iess" id="txt_iess" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
          <label class="col-sm-3" style="padding:1px">COD RES</label>
          <div class="col-sm-9" style="padding:1px">
            <input type="text" name="txt_codres" id="txt_codres" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-3" style="padding-left:1px">
		<div class="form-group">
          <label class="col-sm-4" style="padding:1px">UTILIDAD %</label>
          <div class="col-sm-8" style="padding:1px">
            <input type="text" name="txt_utilidad" id="txt_utilidad" class="form-control input-xs">
          </div>
        </div>
	</div>
</div> -->
<!-- <div class="row">
	<div class="col-sm-4">
		<div class="form-group">
          <label class="col-sm-6" style="padding:1px">Codigo item del banco</label>
          <div class="col-sm-6">
            <input type="text" name="txt_codbanco" id="txt_codbanco" class="form-control input-xs">
          </div>
        </div>
	</div>

	<div class="col-sm-3" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-3" style="padding:1px">Descripcion</label>
          <div class="col-sm-9">
            <input type="text" name="txt_descripcion" id="txt_descripcion" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-2"  style="padding:1px">
		<div class="form-group">
          <label class="col-sm-6" style="padding:1px">Gramaje</label>
          <div class="col-sm-6">
            <input type="text" name="txt_gramaje" id="txt_gramaje" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-1" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-6" style="padding:1px">POS. X</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="txt_posx" id="txt_posx" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-1" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-6" style="padding:1px">POS. Y</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="txt_posy" id="txt_posy" class="form-control input-xs">
          </div>
        </div>
	</div>
</div> -->
<!-- <div class="row">
	<div class="col-sm-12">
		<div class="form-group">
          <label class="col-sm-3" style="padding:1px">FORMULA FARMACEUTICA (AYUA)</label>
          <div class="col-sm-9">
            <input type="text" name="txt_formula" id="txt_formula" class="form-control input-xs">
          </div>
        </div>
	</div>
</div> -->
<!--</div>
</form>-->