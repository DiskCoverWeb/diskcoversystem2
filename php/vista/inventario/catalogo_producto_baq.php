<link rel="stylesheet" href="../../dist/css/arbol.css">
<script src="../../dist/js/catalogo_producto_baq.js"></script>
<script type="text/javascript">
	
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
	<div class="col-sm-11">
		<div class="box">
			<div class="box border-top border-3 border-secondary-subtle bg-white" id="tabla" style="overflow-y: auto;min-height: fit-content;max-height: 40vh;">
				<ol class="tree" id="tree1">
				</ol>	
			</div>		
		</div>
		
	</div>
	<div class="col-sm-1 ps-0">
		<button class="btn btn-light border border-2 mb-1"  data-toggle="tooltip" title="Grabar" onclick="guardarINV()">  <img src="../../img/png/grabar.png"></button>
		<button class="btn btn-light border border-2 mb-1"  data-toggle="tooltip" title="Imprimir Grupo" onclick="codigo_barras_grupo();"><img src="../../img/png/impresora.png"></button>
		<button class="btn btn-light border border-2 mb-1"  data-toggle="tooltip" title="Imprimir" onclick="cantidad_codigo_barras();//codigo_barras()">  <img src="../../img/png/barcode.png"></button>
		<a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-light border border-2">
			<img src="../../img/png/salire.png">
		</a>
	</div>
    
</div>
<form id="form_datos" name="form_datos">
	<div class="row">
		<div class="col-sm-2">
			<label for="txt_codigo" class="col-form-label pb-0"><b>Codigo del producto</b></label>
			<input type="text" name="txt_codigo" id="txt_codigo" class="form-control form-control-sm"
				placeholder="<?php echo $_SESSION['INGRESO']['Formato_Inventario']; ?>" > <!--placeholder="CC.CC.CCC.CCCCCC"-->

			<!--<b>Codigo del producto</b>-->
			<input type="hidden" name="txt_padre" id="txt_padre">
			<input type="hidden" name="txt_padre" id="txt_padre_nl">
			<input type="hidden" name="txt_anterior" id="txt_anterior">
			<!--<input type="text" name="txt_codigo" id="txt_codigo" class="form-control input-xs" placeholder="<?php //echo $_SESSION['INGRESO']['Formato_Inventario']; ?>" >-->
		</div>
		<div class="col-sm-10">
			<label for="txt_concepto" class="col-form-label pb-0"><b>Concepto o detalle del producto</b></label>
			<input type="text" name="txt_concepto" id="txt_concepto" class="form-control form-control-sm">
			<!--<b>Concepto o detalle del producto</b>
			<input type="text" name="txt_concepto" id="txt_concepto" class="form-control input-xs">-->
		</div>
	</div>
	<div class="row mt-2">
		<div class="col-sm-6 order-sm-1 border border-2 rounded">
			<i><b>Tipo de Producto</b></i>
			<div class="col-sm-12">
				<div class="form-check form-check-inline">
					<label class="form-check-label" for="cbx_inv">Tipo de Inventario </label>
					<input class="form-check-input" type="radio" name="cbx_tipo" id="cbx_inv" value="I">
				</div>
				<div class="form-check form-check-inline">
					<label class="form-check-label" for="cbx_final">Producto final </label>
					<input class="form-check-input" type="radio" name="cbx_tipo" id="cbx_final" value="P">
				</div>
			</div>
		</div>
		<div class="col-sm-6 order-sm-2 d-flex align-items-center justify-content-center">
			<i></i>
			<div class="col-sm-12">
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" name="rbl_iva" id="rbl_iva">
					<label class="form-check-label" for="rbl_iva"> Factura con IVA</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" name="rbl_inv" id="rbl_inv">
					<label class="form-check-label" for="rbl_inv"> Producto para facturar</label>
				</div>
			</div>
		</div>
		
		
	</div>
	<div class="row mt-2">
		<div class="col-xxl-3 col-sm-4">
			<div class="input-group input-group-sm">
				<label for="cta_inventario" class="input-group-text">CTA. INVENTARIO</label>
				<input type="text" aria-label="CTAInventario" name="cta_inventario" id="cta_inventario" class="form-control form-control-sm"  placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
			</div>
		</div>
		<div class="col-xxl-3 col-sm-4">
			<div class="input-group input-group-sm">
				<label for="cta_costo_venta" class="input-group-text">CTA. COSTO DE VENTA</label>
				<input type="text" aria-label="CTACostoVenta" name="cta_costo_venta" id="cta_costo_venta" class="form-control form-control-sm"  placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
			</div>
		</div>
		<div class="col-xxl-3 col-sm-4">
			<div class="input-group input-group-sm">
				<label for="cta_venta" class="input-group-text">CTA. DE VENTA</label>
				<input type="text" aria-label="CTAVenta" name="cta_venta" id="cta_venta" class="form-control form-control-sm"  placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
			</div>
		</div>
		<div class="col-xxl-3 mt-xxl-0 col-sm-6 mt-sm-1">
			<div class="input-group input-group-sm">
				<label for="cta_tarifa_0" class="input-group-text">CTA. VENTA TARIFA 0%</label>
				<input type="text" aria-label="CTAVenta0" name="cta_tarifa_0" id="cta_tarifa_0" class="form-control form-control-sm"  placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
			</div>
		</div>
		<div class="col-xxl-4 col-sm-6 mt-1">
			<div class="input-group input-group-sm">
				<label for="cta_venta_anterior" class="input-group-text">CTA. DE VENTA AÑO ANTERIOR</label>
				<input type="text" aria-label="CTA_Venta_AA" name="cta_venta_anterior" id="cta_venta_anterior" class="form-control form-control-sm"  placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
			</div>
		</div>
		<div class="col-xxl-3 col-sm-3 mt-1">
			<div class="input-group input-group-sm">
				<label for="txt_unidad" class="input-group-text">UNIDAD:</label>
				<input type="text" aria-label="Unidad" name="txt_unidad" id="txt_unidad" class="form-control form-control-sm">
			</div>
		</div>
		<div class="col-xxl-3 col-sm-5 mt-1">
			<div class="input-group input-group-sm">
				<span class="input-group-text">P.V.P</span>
				<input type="text" aria-label="PVP" class="form-control form-control-sm text-end" name="pvp" id="pvp">
				<span class="input-group-text">P.V.P2</span>
				<input type="text" aria-label="PVP2" class="form-control form-control-sm text-end" name="pvp2" id="pvp2">
				<span class="input-group-text">P.V.P3</span>
				<input type="text" aria-label="PVP3" class="form-control form-control-sm text-end" name="pvp3" id="pvp3">
			</div>
		</div>
		
		<div class="col-xxl-2 col-sm-4 mt-1">
			<div class="input-group input-group-sm">
				<span class="input-group-text">MINIMO</span>
				<input type="text" aria-label="MINIMO" class="form-control form-control-sm" name="minimo" id="minimo">
				<span class="input-group-text">MAXIMO</span>
				<input type="text" aria-label="MAXIMO" class="form-control form-control-sm" name="maximo" id="maximo">
			</div>
			
		</div>	
		
	</div>
	<!-- <div class="row">
		<div class="col-sm-6">
			<div class="form-group">
			<label class="col-sm-5" style="padding:1px">CTA. DE VENTA</label>
			<div class="col-sm-6" style="padding:1px">
				<input type="text" name="cta_venta" id="cta_venta" class="form-control input-xs" placeholder="C.C.CC.CC.CC.CCC">
			</div>
			</div>
		</div>
		<div class="col-sm-6" style="padding:1px;">
			<div class="form-group">
			<label class="col-sm-6" style="padding:1px">CTA. VENTA TARIFA 0%</label>
			<div class="col-sm-6" style="padding:1px">
				<input type="text" name="cta_tarifa_0" id="cta_tarifa_0" class="form-control input-xs" placeholder="C.C.CC.CC.CC.CCC">
			</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
			<label class="col-sm-6" style="padding:1px;width:fit-content;">CTA. DE VENTA AÑO ANTERIOR</label>
			<div class="col-sm-6" style="padding:1px; padding-left:5px;">
				<input type="text" name="cta_venta_anterior" id="cta_venta_anterior" class="form-control input-xs" placeholder="C.C.CC.CC.CC.CCC">
			</div>
			</div>
		</div>
		<div class="col-sm-4" style="padding:1px">
			<div class="form-group">
			<label class="col-sm-5" style="padding:1px;width:fit-content;">UNIDAD: U:</label>
			<div class="col-sm-6" style="padding:1px; padding-left:5px;">
				<input type="text" name="txt_unidad" id="txt_unidad" class="form-control input-xs">
			</div>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
			<label class="col-sm-4" style="padding:1px">P.V.P</label>
			<div class="col-sm-8" style="padding:1px">
				<input type="text" name="pvp" id="pvp" class="form-control input-xs">
			</div>
			</div>
		</div>
		<div class="col-sm-2" style="padding:1px">
			<div class="form-group">
			<label class="col-sm-5" style="padding:1px">P.V.P2</label>
			<div class="col-sm-7" style="padding:3px">
				<input type="text" name="pvp2" id="pvp2" class="form-control input-xs">
			</div>
			</div>
		</div>
		<div class="col-sm-2" style="padding:1px">
			<div class="form-group">
			<label class="col-sm-5" style="padding:1px">P.V.P3</label>
			<div class="col-sm-7" style="padding:3px">
				<input type="text" name="pvp3" id="pvp3" class="form-control input-xs">
			</div>
			</div>
		</div>
		<div class="col-sm-offset-1 col-sm-5" style="padding:1px">
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
	<!--<div class="row" style="display:none;">
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
	</div>-->
	<div class="row mt-2">
		<div class="col-sm-8">
			<div class="input-group input-group-sm">
				<label for="txt_ubicacion" class="input-group-text">UBICACION</label>
				<input type="text" aria-label="Ubicacion" name="txt_ubicacion" id="txt_ubicacion" class="form-control form-control-sm">
			</div>
			
		</div>
		<div class="col-sm-4">
			<div class="input-group input-group-sm">
				<label for="txt_utilidad" class="input-group-text">UTILIDAD</label>
				<input type="text" aria-label="Utilidad" name="txt_utilidad" id="txt_utilidad" class="form-control form-control-sm text-end">
				<span class="input-group-text">%</span>
			</div>
			
		</div>
	</div>
	<!-- <div class="row" style="margin-bottom:5px">
		<div class="col-sm-7">
			<div class="form-group">
			<label class="col-sm-3" style="padding:1px">UBICACION</label>
			<div class="col-sm-9">
				<input type="text" name="txt_ubicacion" id="txt_ubicacion" class="form-control input-xs">
			</div>
			</div>
		</div>
		
		<div class="col-sm-5" style="padding-left:1px;">
			<div class="form-group">
			<label class="col-sm-4" style="padding:1px">UTILIDAD %</label>
			<div class="col-sm-8" style="padding:1px">
				<input type="text" name="txt_utilidad" id="txt_utilidad" class="form-control input-xs">
			</div>
			</div>
		</div>
	</div> -->
	<div class="row mt-2">
		<div class="col-sm-6">
			<label for="txt_codbanco" class="col-form-label pb-0"><b>Abreviatura del producto</b></label>
			<input type="text" name="txt_codbanco" id="txt_codbanco" class="form-control form-control-sm">
		</div>

		<div class="col-sm-6">
			<label for="txt_descripcion" class="col-form-label pb-0"><b>Categoria de GFN</b></label>
			<input type="text" name="txt_descripcion" id="txt_descripcion" class="form-control form-control-sm">
		</div>
	</div>
	<div class="row mt-2 ps-3">
		<div class="row row-cols-auto col-sm-12">
			<label for="txt_formula" class="col-sm-auto px-0 col-form-label"><b>Indicador nutricional</b></label>
			<div class="col-sm-10">
				<input type="text" aria-label="Ind Nutricional" name="txt_formula" id="txt_formula" class="form-control form-control-sm">
			</div>
		</div>
	</div>
</form>
<br>
<br>