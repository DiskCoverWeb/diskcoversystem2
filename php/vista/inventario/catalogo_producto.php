<link rel="stylesheet" href="../../dist/css/arbol.css">
<script src="../../dist/js/catalogo_producto.js"></script>
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
      <div class="box border" id="tabla" style="overflow-y: auto;min-height: fit-content;max-height: 40vh;">
        <ol class="tree" id="tree1">
        </ol>	
		<!--<div class="accordion accordion-flush" id="accordionExample">
			<div class="accordion-item">
				<h2 class="accordion-header">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
					Accordion Item #1
				</button>
				</h2>
				<div id="collapseOne" class="accordion-collapse collapse show ps-2" data-bs-parent="#accordionExample">
					
					<div class="accordion" id="accordionExample1">
						<div class="accordion-item">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOneOne" aria-expanded="false" aria-controls="collapseOneOne">
									Accordion Item #1
								</button>
							</h2>
							<div id="collapseOneOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample1">
								<div class="accordion-body">
									<strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
					Accordion Item #2
				</button>
				</h2>
				<div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
				<div class="accordion-body">
					<strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
				</div>
				</div>
			</div>
			<div class="accordion-item">
				<h2 class="accordion-header">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
					Accordion Item #3
				</button>
				</h2>
				<div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
				<div class="accordion-body">
					<strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
				</div>
				</div>
			</div>
		</div>-->
      </div>		
		</div>
	</div>
    <div class="col-sm-1 ps-0">
    	<button class="btn btn-light border border-2 mb-1"  data-toggle="tooltip" title="Grabar" onclick="guardarINV()">  <img src="../../img/png/grabar.png"></button><br>
    	<button class="btn btn-light border border-2 mb-1"  data-toggle="tooltip" title="Imprimir Grupo" onclick="codigo_barras_grupo();"><img src="../../img/png/impresora.png"></button><br>
    	<button class="btn btn-light border border-2 mb-1"  data-toggle="tooltip" title="Imprimir" onclick="cantidad_codigo_barras();//codigo_barras()">  <img src="../../img/png/barcode.png"></button><br>
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
			placeholder="<?php echo $_SESSION['INGRESO']['Formato_Inventario']; ?>" >

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
	<div class="col-xxl-3 order-xxl-1 col-sm-6 order-sm-1 border border-2 rounded">
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
	<div class="col-xxl-6 order-xxl-2 col-sm-12 order-sm-3 mt-sm-2 d-flex align-items-center justify-content-center">
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
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="checkbox" name="rbl_inv" id="rbl_inv">
				<label class="form-check-label" for="rbl_inv"> Agrupar</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="checkbox" name="rbl_reserva" id="rbl_reserva">
				<label class="form-check-label" for="rbl_reserva"> Por Reservas</label>
			</div>
		</div>
	</div>
	<div class="col-xxl-3 order-xxl-3 col-sm-6 order-sm-2 border border-2 rounded">
		<i><b>Calcular Divisas</b></i>
		<div class="col-sm-12">
			<div class="form-check form-check-inline">
				<label class="form-check-label" for="cbx_dividir">Dividir </label>
				<input class="form-check-input" type="radio" name="cbx_calcular" id="cbx_dividir" value="div">
			</div>
			<div class="form-check form-check-inline">
				<label class="form-check-label" for="cbx_multiplicar">Multiplicar </label>
				<input class="form-check-input" type="radio" name="cbx_calcular" id="cbx_multiplicar" value="mul">
			</div>
		</div>
	</div>
	<!--<div class="col-sm-10">		
		<i>Tipo de Producto</i>
		<div class="row">
			<div class="col-sm-2" style="padding-right:0px">
				<label>Tipo de Inventario <input type="radio" name="cbx_tipo" id="cbx_inv" value="I"></label>
			</div>
			<div class="col-sm-2">
				<label>Producto final <input type="radio" name="cbx_tipo" id="cbx_final" value="P"></label>
			</div>
			<div class="col-sm-2">
				<label><input type="checkbox" name="rbl_iva" id="rbl_iva"> Factura con IVA</label>
			</div>
			<div class="col-sm-3" style="padding-right:0px">
				<label><input type="checkbox" name="rbl_inv" id="rbl_inv"> Producto para facturar</label>
			</div>
			<div class="col-sm-1" style="padding:0px">
				<label><input type="checkbox" name="rbl_agrupacion" id="rbl_agrupacion"> Agrupar</label>
			</div>
			<div class="col-sm-2">
				<label><input type="checkbox" name="rbl_reserva" id="rbl_reserva"> Por Reservas</label>
			</div>
		</div>
	</div>
	<div class="col-sm-2">
		<i>Calcular Divisas</i>
		<div class="row">
			<div class="col-sm-5" style="padding: 0px;">
				<label>Dividir <input type="radio" name="cbx_calcular" id="cbx_dividir" value="div" ></label>
			</div>
			<div class="col-sm-7" style="padding: 0px;">
				<label>Multiplicar <input type="radio" name="cbx_calcular" id="cbx_multiplicar" value="mul" ></label>
			</div>
		</div>
	</div>-->
</div>
<div class="row mt-2">
	<div class="col-xxl-3 col-sm-4">
		<div class="input-group input-group-sm">
			<label for="cta_inventario" class="input-group-text">CTA. INVENTARIO</label>
			<input type="text" aria-label="CTAInventario" name="cta_inventario" id="cta_inventario" class="form-control form-control-sm"  placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
		</div>
		<!--<div class="form-group">
          <label class="col-sm-6" style="padding:0px">CTA. INVENTARIO</label>
          <div class="col-sm-6" style="padding:0px">
            <input type="text" name="cta_inventario" id="cta_inventario" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
          </div>
        </div>-->
	</div>
	<div class="col-xxl-3 col-sm-4">
		<div class="input-group input-group-sm">
			<label for="cta_costo_venta" class="input-group-text">CTA. COSTO DE VENTA</label>
			<input type="text" aria-label="CTACostoVenta" name="cta_costo_venta" id="cta_costo_venta" class="form-control form-control-sm"  placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
		</div>
		<!--<div class="form-group">
          <label class="col-sm-6" style="padding:1px">CTA. COSTO DE VENTA</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="cta_costo_venta" id="cta_costo_venta" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
          </div>
        </div>-->
	</div>
	<div class="col-xxl-3 col-sm-4">
		<div class="input-group input-group-sm">
			<label for="cta_venta" class="input-group-text">CTA. DE VENTA</label>
			<input type="text" aria-label="CTAVenta" name="cta_venta" id="cta_venta" class="form-control form-control-sm"  placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
		</div>
		<!--<div class="form-group">
          <label class="col-sm-5" style="padding:1px">CTA. DE VENTA</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="cta_venta" id="cta_venta" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
          </div>
        </div>-->
	</div>
	<div class="col-xxl-3 mt-xxl-0 col-sm-6 mt-sm-1">
		<div class="input-group input-group-sm">
			<label for="cta_tarifa_0" class="input-group-text">CTA. VENTA TARIFA 0%</label>
			<input type="text" aria-label="CTAVenta0" name="cta_tarifa_0" id="cta_tarifa_0" class="form-control form-control-sm"  placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
		</div>
		<!--<div class="form-group">
          <label class="col-sm-6" style="padding:1px">CTA. VENTA TARIFA 0%</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="cta_tarifa_0" id="cta_tarifa_0" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
          </div>
        </div>-->
	</div>
	<div class="col-xxl-4 col-sm-6 mt-1">
		<div class="input-group input-group-sm">
			<label for="cta_venta_anterior" class="input-group-text">CTA. DE VENTA AÑO ANTERIOR</label>
			<input type="text" aria-label="CTA_Venta_AA" name="cta_venta_anterior" id="cta_venta_anterior" class="form-control form-control-sm"  placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
		</div>
		<!--<div class="form-group">
		  <label class="col-sm-7" style="padding:1px">CTA. DE VENTA AÑO ANTERIOR</label>
		  <div class="col-sm-5" style="padding:1px">
			<input type="text" name="cta_venta_anterior" id="cta_venta_anterior" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
		  </div>
		</div>-->
	</div>
	<div class="col-xxl-3 col-sm-3 mt-1">
		<div class="input-group input-group-sm">
			<label for="txt_unidad" class="input-group-text">UNIDAD:</label>
			<input type="text" aria-label="Unidad" name="txt_unidad" id="txt_unidad" class="form-control form-control-sm">
		</div>
		<!--<div class="form-group">
		  <label class="col-sm-5" style="padding:1px">UNIDAD: U:</label>
		  <div class="col-sm-6" style="padding:1px">
			<input type="text" name="txt_unidad" id="txt_unidad" class="form-control input-xs">
		  </div>
		</div>-->
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
	<!--<div class="col-sm-1" style="padding:1px">
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
	</div>-->
	<div class="col-xxl-2 col-sm-4 mt-1">
		<div class="input-group input-group-sm">
			<span class="input-group-text">MINIMO</span>
			<input type="text" aria-label="MINIMO" class="form-control form-control-sm" name="minimo" id="minimo">
			<span class="input-group-text">MAXIMO</span>
			<input type="text" aria-label="MAXIMO" class="form-control form-control-sm" name="maximo" id="maximo">
		</div>
		<!--<div class="row">
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
		</div>-->
	</div>	
</div>
<div class="row mt-2">
	<div class="col-sm-4">
		<div class="input-group input-group-sm">
			<label for="txt_barras" class="input-group-text">CODIGO DE BARRAS</label>
			<input type="text" aria-label="CodBarras" name="txt_barras" id="txt_barras" class="form-control form-control-sm">
		</div>
		<!--<div class="form-group">
          <label class="col-sm-5" style="padding:1px">CODIGO DE BARRAS</label>
          <div class="col-sm-7">
            <input type="text" name="txt_barras" id="txt_barras" class="form-control input-xs">
          </div>
        </div>-->
	</div>
	<div class="col-sm-3">
		<div class="input-group input-group-sm">
			<label for="txt_marca" class="input-group-text">MARCA</label>
			<input type="text" aria-label="Marca" name="txt_marca" id="txt_marca" class="form-control form-control-sm">
		</div>
		<!--<div class="form-group">
          <label class="col-sm-2" style="padding:1px">MARCA</label>
          <div class="col-sm-10">
            <input type="text" name="txt_marca" id="txt_marca" class="form-control input-xs">
          </div>
        </div>-->
	</div>
	<div class="col-sm-5">
		<div class="input-group input-group-sm">
			<label for="txt_reg_sanitario" class="input-group-text">REGISTRO SANITARIO</label>
			<input type="text" aria-label="RegSanitario" name="txt_reg_sanitario" id="txt_reg_sanitario" class="form-control form-control-sm">
		</div>
		<!--<div class="form-group">
          <label class="col-sm-4" style="padding:1px">REGISTRO SANITARIO</label>
          <div class="col-sm-8">
            <input type="text" name="txt_reg_sanitario" id="txt_reg_sanitario" class="form-control input-xs">
          </div>
        </div>-->
	</div>
</div>
<div class="row mt-2">
	<div class="col-sm-4">
		<div class="input-group input-group-sm">
			<label for="txt_ubicacion" class="input-group-text">UBICACION</label>
			<input type="text" aria-label="Ubicacion" name="txt_ubicacion" id="txt_ubicacion" class="form-control form-control-sm">
		</div>
		<!--<div class="form-group">
          <label class="col-sm-3" style="padding:1px">UBICACION</label>
          <div class="col-sm-9">
            <input type="text" name="txt_ubicacion" id="txt_ubicacion" class="form-control input-xs">
          </div>
        </div>-->
	</div>
	<div class="col-sm-3">
		<div class="input-group input-group-sm">
			<label for="txt_iess" class="input-group-text">COD. I.E.S.S</label>
			<input type="text" aria-label="CodIESS" name="txt_iess" id="txt_iess" class="form-control form-control-sm">
		</div>
		<!--<div class="form-group">
          <label class="col-sm-4" style="padding:1px">COD. I.E.S.S</label>
          <div class="col-sm-8" style="padding:1px">
            <input type="text" name="txt_iess" id="txt_iess" class="form-control input-xs">
          </div>
        </div>-->
	</div>
	<div class="col-sm-3">
		<div class="input-group input-group-sm">
			<label for="txt_codres" class="input-group-text">COD RES</label>
			<input type="text" aria-label="CodRes" name="txt_codres" id="txt_codres" class="form-control form-control-sm">
		</div>
		<!--<div class="form-group">
          <label class="col-sm-3" style="padding:1px">COD RES</label>
          <div class="col-sm-9" style="padding:1px">
            <input type="text" name="txt_codres" id="txt_codres" class="form-control input-xs">
          </div>
        </div>-->
	</div>
	<div class="col-sm-2">
		<div class="input-group input-group-sm">
			<label for="txt_utilidad" class="input-group-text">UTILIDAD</label>
			<input type="text" aria-label="Utilidad" name="txt_utilidad" id="txt_utilidad" class="form-control form-control-sm text-end">
			<span class="input-group-text">%</span>
		</div>
		<!--<div class="form-group">
          <label class="col-sm-4" style="padding:1px">UTILIDAD %</label>
          <div class="col-sm-8" style="padding:1px">
            <input type="text" name="txt_utilidad" id="txt_utilidad" class="form-control input-xs">
          </div>
        </div>-->
	</div>
</div>
<div class="row mt-2">
	<div class="col-sm-4">
		<div class="input-group input-group-sm">
			<label for="txt_codbanco" class="input-group-text">Codigo item del banco</label>
			<input type="text" aria-label="CodBanco" name="txt_codbanco" id="txt_codbanco" class="form-control form-control-sm">
		</div>
		<!--<div class="form-group">
          <label class="col-sm-6" style="padding:1px">Codigo item del banco</label>
          <div class="col-sm-6">
            <input type="text" name="txt_codbanco" id="txt_codbanco" class="form-control input-xs">
          </div>
        </div>-->
	</div>

	<div class="col-sm-3">
		<div class="input-group input-group-sm">
			<label for="txt_descripcion" class="input-group-text">Descripcion</label>
			<input type="text" aria-label="Descripcion" name="txt_descripcion" id="txt_descripcion" class="form-control form-control-sm">
		</div>
		<!--<div class="form-group">
          <label class="col-sm-3" style="padding:1px">Descripcion</label>
          <div class="col-sm-9">
            <input type="text" name="txt_descripcion" id="txt_descripcion" class="form-control input-xs">
          </div>
        </div>-->
	</div>
	<div class="col-sm-2">
		<div class="input-group input-group-sm">
			<label for="txt_gramaje" class="input-group-text">Gramaje</label>
			<input type="text" aria-label="Gramaje" name="txt_gramaje" id="txt_gramaje" class="form-control form-control-sm">
		</div>
		<!--<div class="form-group">
          <label class="col-sm-6" style="padding:1px">Gramaje</label>
          <div class="col-sm-6">
            <input type="text" name="txt_gramaje" id="txt_gramaje" class="form-control input-xs">
          </div>
        </div>-->
	</div>
	<div class="col-sm-3">
		<div class="input-group input-group-sm">
			<span class="input-group-text">POS. X</span>
			<input type="text" aria-label="POS X" class="form-control form-control-sm" name="txt_posx" id="txt_posx">
			<span class="input-group-text">POS. Y</span>
			<input type="text" aria-label="POS Y" class="form-control form-control-sm" name="txt_posy" id="txt_posy">
		</div>
		<!--<div class="form-group">
          <label class="col-sm-6" style="padding:1px">POS. X</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="txt_posx" id="txt_posx" class="form-control input-xs">
          </div>
        </div>-->
	</div>
	<!--<div class="col-sm-1" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-6" style="padding:1px">POS. Y</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="txt_posy" id="txt_posy" class="form-control input-xs">
          </div>
        </div>
	</div>-->
</div>
<div class="row mt-2">
	<div class="col-sm-12">
		<div class="input-group input-group-sm">
				<label for="txt_formula" class="input-group-text">FORMULA FARMACEUTICA (AYUA)</label>
				<input type="text" aria-label="Formula" name="txt_formula" id="txt_formula" class="form-control form-control-sm">
		</div>
		<!--<div class="form-group">
          <label class="col-sm-3" style="padding:1px">FORMULA FARMACEUTICA (AYUA)</label>
          <div class="col-sm-9">
            <input type="text" name="txt_formula" id="txt_formula" class="form-control input-xs">
          </div>
        </div>-->
	</div>
</div>
</div>
</form>