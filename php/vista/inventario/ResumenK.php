
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
		<div class="btn-group">
			<a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
				print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
					<img src="../../img/png/salire.png">
			</a>
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Resumen de Existecia" id="Stock" onclick="ConsultarStock(true);">
				<img src="../../img/png/archivo1.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Resumen de Existencia por Lotes" id="Lote" onclick="ConsultarResumen('Lote');">
				<img src="../../img/png/archivo2.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Resumen en Codigos de Barra" id="Barras" onclick="ConsultarResumen('Barras');">
				<img src="../../img/png/archivo3.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Imprime Resultado" id="Imprimir" onclick="Imprimir_ResumenK();">
				<img src="../../img/png/pdf.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Enviar a Excel el resultado" id="Excel" onclick="generarExcelResumenK();">
				<img src="../../img/png/table_excel.png">
			</button>
			
		</div>
	</div>  
</div>	

<div class="container-fluid">
<!-- <div class="row mb-3">
  <div class="col">
    <a href="./inventario.php?mod=<?php echo @$_GET['mod']; ?>" title="Salir de modulo" class="btn btn-default">
      <img src="../../img/png/salire.png">
    </a>
  </div> 
  <div class="col">
    <a href="#" id="Stock" class="btn btn-default"  onclick="ConsultarStock(true)" title="Resumen de Existecia">
      <img src="../../img/png/archivo1.png">
    </a>
  </div>
  <div class="col">
    <a href="#" id="Lote" title="Resumen de Existencia por Lotes" onclick="ConsultarResumen_Lote()" class="btn btn-default" >
      <img src="../../img/png/archivo2.png" >
    </a>
  </div>
  <div class="col">
    <a href="#" id="Barras" title="Resumen en Codigos de Barra" onclick="ConsultarResumen_Barras()" class="btn btn-default" >
      <img src="../../img/png/archivo3.png" >
    </a>
  </div> 
  <div class="col">
    <a href="#" id="Imprimir"  class="btn btn-default" title="Imprime Resultado" onclick="Imprimir_ResumenK()">
      <img src="../../img/png/pdf.png">
    </a>                           
  </div>
  <div class="col">
    <a href="#" id="Excel"  class="btn btn-default" title="Enviar a Excel el resultado" onclick="generarExcelResumenK()">
      <img src="../../img/png/table_excel.png">
    </a>                           
  </div>
</div> -->

  <div class="row div_filtro">
    <form id="FormResumenK">
      <div class="row align-items-center mb-1">
        <div class="col-3">
          <div class="input-group input-group-sm">
            <span class="input-group-text" id="inputGroup-sizing-sm">Fecha Inicial</span>
            <input tabindex="2" type="date" name="MBoxFechaI" id="MBoxFechaI" class="form-control form-control-sm validateDate mw115" value="<?php echo date('Y-m-d'); ?>">
          </div>
        </div>
        <div class="col-3">
          <div class="input-group input-group-sm">
            <span class="input-group-text" id="inputGroup-sizing-sm">Fecha Final</span>
            <input type="date" tabindex="3" name="MBoxFechaF" id="MBoxFechaF" class="form-control form-control-sm validateDate mw115" value="<?php echo date('Y-m-d'); ?>">
          </div>
        </div>
        <div class="col-3">
          <div class="row align-items-center">
            <div class="col-4">
              <div class="form-check">
                <input class="form-check-input" tabindex="" type="checkbox" value="1" id="CheqMonto" name="CheqMonto" onchange="var selectElement = document.getElementById('TxtMonto'); selectElement.style.visibility = (this.checked) ? 'visible' : 'hidden';(this.checked) ? selectElement.focus() : '';">
                <label class="form-check-label" for="CheqMonto">
                  <b>Monto</b>
                </label>
              </div>
            </div>
            <div class="col-8">
              <input type="tel" tabindex="" name="TxtMonto" id="TxtMonto" class="form-control form-control-sm" placeholder="0.00" style="visibility: hidden;">
            </div>
          </div>
          
        </div>
        <div class="col-3">
          <div class="input-group input-group-sm">
            <div class="input-group input-group-sm">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="CheqExist" name="CheqExist" tabindex="">
                <label class="form-check-label" for="flexCheckDefault">
                  <b>Listar Catalogo Completo</b>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      

      <div class="row mb-1">
        <div class="col-6">
          <div class="row align-items-center">
            <div class="col-3">
              <div class="form-check">
                <input class="form-check-input" id="CheqBod" name="CheqBod" tabindex="2" value="1" type="checkbox" onchange="document.getElementById('DCBodega_cont').style.visibility = (this.checked) ? 'visible' : 'hidden';(this.checked) ? document.getElementById('DCBodega').focus() : '';">
                <label class="form-check-label" for="CheqBod">
                  <b>BODEGA</b>
                </label>
              </div>
            </div>
            <div class="col-9" id="DCBodega_cont" style="visibility: hidden;">
              <select class="form-select form-select-sm" tabindex="3" id="DCBodega" name="DCBodega">
                <option value=''>** Seleccionar Bodega**</option>
                
              </select>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="row align-items-center">
            <div class="col-4">
              <div class="form-check">
                <input class="form-check-input" id="CheqGrupo" name="CheqGrupo" tabindex="2" value="1" type="checkbox"  onchange="document.getElementById('DCTInv_cont').style.visibility = (this.checked) ? 'visible' : 'hidden';(this.checked) ? document.getElementById('DCTInv').focus() : '';">
                <label class="form-check-label" for="CheqGrupo">
                  <b>TIPO GRUPO</b>
                </label>
              </div>
            </div>
            <div class="col-8" id="DCTInv_cont"  style="visibility: hidden;">
              <select class="form-select form-select-sm" tabindex="3" id="DCTInv" name="DCTInv" onchange="Listar_X_Producto()">
                <option value=''>** Seleccionar Grupo**</option>
                
              </select>
            </div>
          </div>
        </div>

      
      </div>
      
      

      <div class="row align-items-center mb-1">
        <div class="col-2">
          <div class="form-check">
            <input class="form-check-input" id="CheqProducto" name="CheqProducto" tabindex="2" value="1" type="checkbox" onchange="let selectElement = $('.FrmProducto'); selectElement.css('visibility',(this.checked) ? 'visible' : 'hidden');(this.checked) ? $('#OpcProducto').focus() : '';">
            <label class="form-check-label" for="CheqProducto">
              <b>PRODUCTO</b>
            </label>
          </div>
        </div>
        <div class="col-5 FrmProducto" style="visibility: hidden;">
          <div class="form-check form-check-inline">
            <input class="form-check-input" id="OpcProducto" name="ProductoPor" checked tabindex="" value="OpcProducto" type="radio">
            <label class="form-check-label" for="OpcProducto"><b>Producto</b></label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" id="OpcBarra" name="ProductoPor" tabindex="" value="OpcBarra" type="radio">
            <label class="form-check-label" for="OpcBarra"><b>Codigo Barra</b></label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" id="OpcMarca" name="ProductoPor" tabindex="" value="OpcMarca" type="radio">
            <label class="form-check-label" for="OpcMarca"><b>Marca</b></label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" id="OpcLote" name="ProductoPor" tabindex="" value="OpcLote" type="radio">
            <label class="form-check-label" for="OpcLote"><b>Lote</b></label>
          </div>
          <!-- <label><input id="OpcProducto" name="ProductoPor" checked tabindex="" value="OpcProducto" type="radio"><b>Producto</b></label>   
          <label><input id="OpcBarra" name="ProductoPor" tabindex="" value="OpcBarra" type="radio"><b>Codigo Barra</b></label>   
          <label><input id="OpcMarca" name="ProductoPor" tabindex="" value="OpcMarca" type="radio"><b>Marca</b></label>   
          <label><input id="OpcLote" name="ProductoPor" tabindex="" value="OpcLote" type="radio"><b>Lote</b></label>    -->
        </div>
        <div class="col-5 FrmProducto" style="visibility: hidden;">
          <select class="form-select form-select-sm" tabindex="" id="DCTipoBusqueda" name="DCTipoBusqueda">
            <option value=''>** Seleccionar**</option>
            
          </select>
        </div>
        
      </div>

      

      <div class="row align-items-center mb-1">
        <div class="col-2">
          <div class="form-check">
            <input class="form-check-input" id="CheqCtaInv" name="CheqCtaInv" tabindex="2" value="1" type="checkbox" onchange="let selectElement = $('.FrmCuenta'); selectElement.css('visibility',(this.checked) ? 'visible' : 'hidden');(this.checked) ? $('#OpcInv').focus() : '';">
            <label class="form-check-label" for="CheqCtaInv">
              <b>TIPO DE CTA.</b>
            </label>
          </div>
        </div>
        <div class="col-5 FrmCuenta" style="visibility: hidden;">
          <div class="form-check form-check-inline">
            <input class="form-check-input" id="OpcInv" name="TipoCuentaDe" checked tabindex="" value="OpcInv" type="radio">
            <label class="form-check-label" for="OpcInv"><b>Inventario</b></label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" id="OpcCosto" name="TipoCuentaDe" tabindex="" value="OpcCosto" type="radio">
            <label class="form-check-label" for="OpcCosto"><b>Costo</b></label>
          </div>
        </div>
        <div class="col-5 FrmCuenta" style="visibility: hidden;">
          <select class="form-select form-select-sm" tabindex="" id="DCCtaInv" name="DCCtaInv">
            <option value=''>** Seleccionar Cuenta**</option>
            
          </select>
        </div>
        
      </div>
      
      

      <div class="row align-items-center mb-1">
        <div class="col-2">
          <div class="form-check">
            <input class="form-check-input" id="CheqSubMod" name="CheqSubMod" tabindex="2" value="1" type="checkbox" onchange="let selectElement = $('.FrmSubModulo'); selectElement.css('visibility',(this.checked) ? 'visible' : 'hidden');(this.checked) ? $('#OpcGasto').focus() : '';">
            <label class="form-check-label" for="CheqSubMod">
              <b>POR SUBMODULO</b>
            </label>
          </div>
        </div>
        <div class="col-5 FrmSubModulo" style="visibility: hidden;">
          <div class="form-check form-check-inline">
            <input class="form-check-input" id="OpcGasto" name="SuModeloDe" checked tabindex="" value="OpcGasto" type="radio">
            <label class="form-check-label" for="OpcGasto"><b>Centro de Costo</b></label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" id="OpcCxP" name="SuModeloDe" tabindex="" value="OpcCxP" type="radio">
            <label class="form-check-label" for="OpcCxP"><b>CxP/Proveedores</b></label>
          </div>
        </div>
        <div class="col-5 FrmSubModulo" style="visibility: hidden;">
          <select class="form-select form-select-sm" tabindex="" id="DCSubModulo" name="DCSubModulo">
            <option value=''>** Seleccionar Modulo**</option>
            
          </select>
        </div>

        
      </div>

      

      <input type="hidden" id="heightDisponible" name="heightDisponible" value="100"> 
    </form>
  </div>

  <div class="row" id="DGQuery">
    <div class="col-md-12"  tabindex="15">
      <table class="table table-hover table-striped" id="tbl_DGQuery">
        <thead>
          <tr>
            <th class="text-start" style="width:40px">TC</th>
            <th class="text-start" style="width:200px">Codigo_Inv</th>
            <th class="text-start" style="width:300px">Producto</th>
            <th class="text-start" style="width:136px">Unidad</th>
            <th class="text-end" style="width:64px">Stock_Anterior</th>
            <th class="text-end" style="width:64px">Entradas</th>
            <th class="text-end" style="width:64px">Salidas</th>
            <th class="text-end" style="width:64px">Stock_Actual</th>
            <th class="text-end" style="width:64px">Promedio</th>
            <th class="text-end" style="width:64px">PVP</th>
            <th class="text-end" style="width:64px">Valor_Total</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
  <div class="row" id="DGQuery2" style="display: none;">
    <div class="col-md-12"  tabindex="15">
      <table class="table table-hover table-striped" id="tbl_DGQuery2">
        <thead>
          <tr>
            <th class="text-start" style="width:40px">TC</th>
            <th class="text-start" style="width:200px">Codigo_Inv</th>
            <th class="text-start" style="width:300px">Producto</th>
            <th class="text-start" style="width:136px">Unidad</th>
            <th class="text-end" style="width:64px">Stock_Anterior</th>
            <th class="text-end" style="width:64px">Entradas</th>
            <th class="text-end" style="width:64px">Salidas</th>
            <th class="text-end" style="width:64px">Stock_Actual</th>
            <th class="text-left" style="width:64px">Costo_Unit</th>
            <th class="text-right" style="width:112px">Total</th>
            <th class="text-right" style="width:136px">Diferencias</th>
            <th class="text-left" style="width:0px">Bodega</th>
            <th class="text-left" style="width:400px">Ubicacion</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
  <div class="row" id="DGQuery3" style="display: none;">
    <div class="col-md-12"  tabindex="15">
      <table class="table table-hover table-striped" id="tbl_DGQuery3">
        <thead>
          <tr>
            <th>Serie_No</th>
            <th>Detalle</th>
            <th>Promedio</th>
            <th>Saldo_Ant</th>
            <th>Entradas</th>
            <th>Salidas</th>
            <th>Stock_Act</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
  <!-- <div class="row" id="DGQuery4" style="display: none;">
    <div class="col-md-12"  tabindex="15">
      <table class="table table-hover table-striped" id="tbl_DGQuery4">
        <thead>
          <tr>
            <th>Serie_No</th>
            <th>Detalle</th>
            <th>Promedio</th>
            <th>Saldo_Ant</th>
            <th>Entradas</th>
            <th>Salidas</th>
            <th>Stock_Act</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div> -->
  <div class="row">
    <div class="col-auto">
      <div class="input-group input-group-sm">
        <span class="input-group-text">Stock Total</span>
        <input type="tel" name="LabelStock" id="LabelStock" class="form-control form-control-sm mw115">
      </div>
    </div>
    <div class="col-auto">
      <div class="input-group input-group-sm">
        <span class="input-group-text">Valor Total</span>
        <input type="tel" name="LabelTot" id="LabelTot" class="form-control form-control-sm mw115">
      </div>
    </div>
        <!-- <div class="form-group col margin-b-1">
          <label for="inputEmail3" class="col control-label">Valor Total</label>
          <div class="col">
            <input type="tel" name="LabelTot" id="LabelTot" class="form-control input-xs mw115">
          </div>
        </div> -->
    
  </div>
</div>
<br>

<script type="text/javascript" src="../../dist/js/inventario/ResumenK.js">

</script>