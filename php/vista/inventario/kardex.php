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
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Consulta el kardex de un producto" id="Consultar" onclick="Consultar_Tipo_Kardex(true);">
				<img src="../../img/png/archivo1.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Presenta el kardex de todos los productos" id="Kardex_Total" onclick="Consultar_Tipo_Kardex(false);">
				<img src="../../img/png/archivo2.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Presenta el Resumen de Codigos de Barra" id="Kardex" onclick="consulta_kardex();">
				<img src="../../img/png/archivo3.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Descargar PDF Kardex de un Producto" id="Imprimir_Kardex" onclick="generarPDF();">
				<img src="../../img/png/pdf.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Descargar Excel" id="Excel" onclick="generarExcelKardex();">
				<img src="../../img/png/table_excel.png">
			</button>
			
		</div>
	</div>  
</div>	
<div class="container-fluid">

  <div class="row div_filtro">
    <form id="FormKardex">
      <div class="row">
        <div class="col-sm-6">
          <div class="row mb-2">
            <select class="form-select form-select-sm" id="DCTInv" name="DCTInv">
              <option value=''>** Seleccionar **</option>
              
            </select>
          </div>
          <div class="row mb-2">
            <select class="form-select form-select-sm" multiple size="10" id="DCInv" name="DCInv" onchange="productoFinal();">
              <option value=''>** Seleccionar **</option>
            </select>
          </div>
        </div>
  
        <div class="col-sm-6">
          <div class="row mb-2">
            <div class="input-group input-group-sm">
              <div class="input-group-text">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="CheqBod" id="CheqBod" value="1">
                  <label class="form-check-label" for="CheqBod">
                    <b>Bodega:</b>
                  </label>
                </div>
              </div>
              <select class="form-select form-select-sm" id="DCBodega" name="DCBodega" style="padding: 0;">
                <option value=''>** Seleccionar Bodega**</option>
              </select>
            </div>
            <!-- <div class="col-sm-3 padding-all" style="max-width:   80px;">
              <label><input id="CheqBod" name="CheqBod" tabindex="2" value="1" type="checkbox"><b>Bodega:</b></label>    
            </div>
            <div class="col-sm-9 padding-all" style="max-width: 330px;">
              <select class="form-control input-sm" tabindex="3" id="DCBodega" name="DCBodega">
                <option value=''>** Seleccionar Bodega**</option>
              </select>
            </div> -->
          </div>
          <div class="row">
            <div class="col-sm-6">
                <div class="row mb-2">
                  <div class="input-group input-group-sm">
                    <span class="input-group-text"><b>Desde:</b></span>
                    <input type="date" name="MBoxFechaI" id="MBoxFechaI" tabindex="5" class="form-control form-control-sm"  value="<?php echo date("Y-m-d");?>" onblur="validar_year_menor(this.id);" onkeyup="validar_year_mayor(this.id)">
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="input-group input-group-sm">
                    <span class="input-group-text"><b>Código:</b></span>
                    <input type="text" class="form-control form-control-sm" tabindex="14" id="LabelCodigo" name="LabelCodigo" readonly>
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="input-group input-group-sm">
                    <span class="input-group-text"><b>Unidad:</b></span>
                    <input type="text" class="form-control form-control-sm" tabindex="13" id="LabelUnidad" name="LabelUnidad" readonly>
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="input-group input-group-sm">
                    <span class="input-group-text"><b>Bodega:</b></span>
                    <input type="text" class="form-control form-control-sm" tabindex="12" id="LabelBodega" name="LabelBodega" value="0" readonly>
                  </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row mb-2">
                  <div class="input-group input-group-sm">
                    <span class="input-group-text"><b>Hasta:</b></span>
                    <input type="date" name="MBoxFechaF" id="MBoxFechaF" tabindex="7" class="form-control input-sm"  value="<?php echo date("Y-m-d");?>" onblur="validar_year_menor(this.id);" onkeyup="validar_year_mayor(this.id)">
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="input-group input-group-sm">
                    <span class="input-group-text"><b>Mínimo:</b></span>
                    <input type="text" class="form-control input-sm" tabindex="11" id="LabelMinimo" name="LabelMinimo" readonly>
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="input-group input-group-sm">
                    <span class="input-group-text"><b>Existe:</b></span>
                    <input type="text" class="form-control input-sm" tabindex="10" id="LabelExitencia" name="LabelExitencia" readonly style="color:red">
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="input-group input-group-sm">
                    <span class="input-group-text"><b>Máximo:</b></span>
                    <input type="text" class="form-control input-sm" tabindex="9" id="LabelMaximo" name="LabelMaximo" readonly>
                  </div>
                  <input type="hidden" id="heightDisponible" name="heightDisponible" value="100">    
                  <input type="hidden" id="NombreProducto" name="NombreProducto">    
                </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="row" id="DGKardex" style="display: none;">
    <div class="col-md-12"  tabindex="8">
      <table class="table table-hover table-sm table-striped" id="tbl_DGKardex">
        <thead>
          <tr>
            <th></th>
            <th>Codigo_Inv</th>
            <th>Producto</th>
            <th>Unidad</th>
            <th>Bodega</th>
            <th>Fecha</th>
            <th>TP</th>
            <th>Numero</th>
            <th>Entrada</th>
            <th>Salida</th>
            <th>Stock</th>
            <th>Costo</th>
            <th>Saldo</th>
            <th>Valor_Unitario</th>
            <th>Valor_Total</th>
            <th>TC</th>
            <th>Serie</th>
            <th>Factura</th>
            <th>Cta_Inv</th>
            <th>Contra_Cta</th>
            <th>Serie_No</th>
            <th>Codigo_Barra</th>
            <th>Lote_No</th>
            <th>CI_RUC_CC</th>
            <!-- <th>Marca_Tipo_Proceso</th> -->
            <th>Detalle</th>
            <th>Beneficiario_Centro_Costo</th>
            <th>Orden_No</th>
            <th>ID</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <div class="row" id="DGKardexRes" style="display: none;">
    <div class="col-md-12"  tabindex="8">
      <table class="table table-hover table-sm table-striped" id="tbl_DGKardexRes">
        <thead>
          <tr>
            <th>Codigo_Inv</th>
            <th>Codigo_Barra</th>
            <th>Entradas</th>
            <th>Salidas</th>
            <th>Stock_Kardex</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="FrmProductos" tabindex="-1" role="dialog" aria-labelledby="FrmProductosLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="FrmProductosLabel">| CAMBIO DE PRODUCTOS |</h5>
        <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="FormCambiarProducto">
          <div class="row mb-3">
            <div class="col-sm-12">
              <input type="text" class="form-control input-sm" title="Producto anterior" tabindex="27" id="LblProducto" name="LblProducto" readonly>
              <input type="hidden" id="ID_Reg" name="ID_Reg">
              <input type="hidden" id="TC" name="TC">
              <input type="hidden" id="Serie" name="Serie">
              <input type="hidden" id="Factura" name="Factura">
              <input type="hidden" id="CodigoInv" name="CodigoInv">
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <select class="form-select form-select-sm" tabindex="26" id="DCArt" name="DCArt">
                <option value=''>** Seleccionar Nuevo**</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
          
        <button class="btn btn-success" id="Command1" title="Aceptar" onclick="AceptarCambio()">
          <img  src="../../img/png/grabar.png" width="25" height="30" tabindex="24">
        </button>
        <button class="btn btn-warning" id="Command3" title="Salir" data-bs-dismiss="modal">
          <img  src="../../img/png/salire.png" width="25" height="30" tabindex="25">
        </button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="../../dist/js/inventario/kardex.js">

</script>