<?php

/**
 * Autor: JAVIER FARINANGO.
 * Mail:  
 * web:   www.diskcoversystem.com
 */
require_once("../../headers/header.php");
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
	if(basename($errfile) == "inicio.php"){
		throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
	}
});

	if ($pagina) {
		try{
			switch ($pagina) {
				//**************************************CONTABILIDAD**************************************/
				case 'cambioc':
					//Cambio clave
					require_once("contabilidad/cambioc.php");
					break;
	
				//Contabilidad -> Archivo -> Ingreso clientes/proveedores
				case 'Clientes':
					include("contabilidad/FCliente.php");
					break;
	
				//Contabilidad -> Archivo -> Ingreso catalogo de cuentas
				case 'ctaOperaciones':
					require_once("contabilidad/ctaOperaciones.php");
					break;
	
				//Contabilidad -> Archivo -> Ingresar comprobantes (Ctrl+f5) 
				case 'incom':
					require_once("contabilidad/incom.php");
					break;
	
				//Contabilidad -> Archivo -> Ingresar subcuentas de proyecto
				case 'subcta_proyectos':
					require_once("contabilidad/Subcta_proyectos.php");
					break;
	
				//Contabilidad -> Archivo -> Ctas. Ingreso/Egresos/Primas/Centro de Costos
				case 'ISubCtas':
					require_once("contabilidad/ISubCtas.php");
					break;
	
				//Contabilidad -> Archivo -> Cierre de mes
				case 'CierreMes':
					require_once("contabilidad/cierre_mes.php");
					break;
	
				//Contabilidad -> Reportes -> Catalogo de cuentas
				case 'catalogo_cuentas':
					include("contabilidad/catalogoCta.php");
					break;
	
				//Contabilidad -> Reportes -> Diario general
				case 'diario_general':
					include("contabilidad/diario_general.php");
					break;
	
				//Contabilidad -> Reportes -> Libro banco
				case 'libro_banco':
					require_once("contabilidad/libro_banco.php");
					break;
	
				//Contabilidad -> Reportes -> Mayores auxiliares
				case 'mayor_auxiliar':
					require_once("contabilidad/mayor_auxiliar.php");
					break;
	
				//Contabilidad -> Reportes -> Comprobantes procesados
				case 'compro':
					require_once("contabilidad/compro.php");
					break;
	
				//Contabilidad -> Reportes -> Saldo de factura submodulo
				case 'saldo_fac_submodulo':
					require_once("contabilidad/saldo_fac_submodulo.php");
					break;
	
				//Contabilidad -> Reportes -> Mayores subcuentas
				case 'MayoresSubCta':
					require_once("contabilidad/mayores_sub_cuenta.php");
					break;
	
				//Contabilidad -> Anexos transaccionales -> Generar anexos transaccionales
				case 'anexos_trans': //FATAL ERROR
					require_once("contabilidad/anexos_trans.php");
					break;
				//Contabilidad -> Estados financieros -> Mayorizar comprobantes procesados 	
				case 'macom':
					require_once("contabilidad/macom.php");
					break;
	
				//Contabilidad -> Estados financieros -> Balance de Comprobacion/Situación/General
				case 'bacsg':
					require_once("contabilidad/bacsg1.php");
					break;
	
				//Contabilidad -> Estados financieros -> Resumen analitico mensual de utilidad/perdidas 
				case 'bamup':
					require_once("contabilidad/bamup.php");
					break;
	
				case 'incc':
					require_once("contabilidad/inccu.php");
					break;
	
				case 'hco':
					require_once("contabilidad/hco.php");
					break;
	
				//Contabilidad -> Estados financieros -> Resumen analitico mensual de utilidad/perdidas 
				case 'Reindexar':
					require_once("contabilidad/reindexar.php");
					break;
	
				//Empresas -> Archivo -> Cambio de periodo
				case 'campe':
					require_once("contabilidad/campe.php");
					break;
	
				//Inventario -> Archivo -> Asignacion proveedores/donantes
				case 'Proveedores':
					include("contabilidad/FProveedores.php");
					break;

				case 'mapro':
					require_once("inventario/mapro.php");
					break;
	
				case 'reportes':
					require_once("contabilidad/resumen_retenciones.php");
					break;
	
				//Facturacion -> Archivo -> Cierre diario de caja
				case 'cierre_caja':
					require_once("contabilidad/FCierre_Caja.php");
					break;
				case 'cierre_ejercicio':
					require_once("contabilidad/cierreejercicio.php");
					break;
	
				case 'InfoError':
					include("contabilidad/FInfoError.php");
					break;
	
	
	
				//**************************************FACTURACION**************************************/
				//Facturacion -> Archivo -> Facturar
				case 'facturar':
					require_once("facturacion/facturar.php");
					break;
	
				//Facturacion -> Archivo -> Facturar pensiones
				//Agua Potable -> Archivo -> Facturacion mensual
				case 'facturarPension':
					require_once("facturacion/facturar_pension.php");
					break;
	
				//Facturacion -> Archivo -> Listar factura
				case 'listarFactura':
					require_once("facturacion/listar_facturas.php");
					break;
	
				//Facturacion -> Archivo -> Compra/Venta de divisas
				case 'divisas':
					require_once("facturacion/divisas.php");
					break;
				
				//Facturacion -> Archivo -> Facturacion/Cobros Automaticos por Bancos
				case 'facturacion_cobro':
					require_once("facturacion/FRecaudacionBancosCxC.php");
					break;
	
				//Facturacion -> Reportes-> Resumen Historico de Cartera CxC
				case 'historico_facturas':
					require_once("facturacion/HistorialFacturas.php");
					break;
	
					
				//Facturacion -> Archivo -> Facturacion de Distribucion
				case 'factura_distribucion':
					require_once("facturacion/facturas_distribucion.php");
					break;

				//Facturacion -> Archivo -> Facturacion de Distribucion Familias
				case 'factura_distribucion_fam':
					require_once("facturacion/facturas_distribucion_fam.php");
					break;
				case 'factura_productoresAli':
					require_once("facturacion/facturacion_productoresAli.php");
					break;					
				//Facturacion -> Archivo -> Punto de venta
				case 'punto_venta':
				require_once("facturacion/punto_venta.php");
				break;
				
				//Facturacion -> Archivo -> Abonos Anticipados (CXP)
				case 'AbonoAnticipado':
					require_once("contabilidad/FAbonoAnticipado.php");
					break;
				
				//Facturacion -> Archivo -> Lista liquidacion compras
				//Facturacion -> Reportes -> Lista liquidacion compras
				case 'listarLiquidaciones':
					require_once("facturacion/lista_liquidacionCompra.php");
					break;
	
				//Facturacion -> Archivo -> Listar y anular
				case 'listar_anular':
					require_once("facturacion/listar_anular.php");
					break;
	
				//Facturacion -> Reportes -> Lista de facturas
				case 'facturarLista':
					require_once("facturacion/lista_facturas.php");
					break;
					
				//Facturacion -> Reportes -> Lista de NDO y NDU
				case 'facturarNDONDU':
					require_once("facturacion/lista_ndo_ndu.php");
					break;
	
				case 'punto_venta':
					require_once("facturacion/punto_venta.php");
					break;
	
				case 'factura_elec':
					require_once("facturacion/facturacion_elec.php");
					break;
	
				case 'lineas_cxc':
					require_once("facturacion/lineas_cxc.php");
					break;
					
				case 'lineas_cxc_fact':
					require_once("facturacion/lineas_cxc_fact.php");
					break;
	
				case 'cierre_diario':
					require_once("facturacion/cierre_diario_caja.php");
					break;
	
				case 'notascredito':
					require_once("facturacion/notas_credito.php");
					break;
	
				case 'lista_retenciones':
					require_once("facturacion/lista_retenciones.php");
					break;
	
				case 'lista_notas_credito':
					require_once("facturacion/lista_notas_credito.php");
					break;
	
				case 'lista_guias':
					require_once("facturacion/lista_guia_remision.php");
					break;
	
				case 'liquidacioncompra':
					require_once("facturacion/liquidacion_compra.php");
					break;
	
				case 'guiaremision':
					require_once("facturacion/guia_remision.php");
					break;
	
				case 'facturarLista':
					require_once("facturacion/lista_facturas.php");
					break;
				
				case 'envio_recepcion':
					require_once("facturacion/FRecaudacionBancosPreFa.php");
					break;
	
				case 'listar_clientes_grupo':
					require_once("facturacion/ListarGrupos.php");
					break;
					
				//Inventario -> Archivo -> Registro de bodegas 
				case 'catalogoBodega':
					require_once("facturacion/catalogo_bodega.php");
					break;
	
				//**************************************AGUA POTABLE**************************************/
				//Agua potable -> Archivo -> Ingresar cliente
				case 'ingresar_usuario':
					require_once("aguaPotable/ingresar_usuario.php");
					break;
	
				//Agua potable -> Archivo -> Ingreso de consumo de agua
				case 'ingreso_consumo_agua':
					require_once("aguaPotable/ingreso_consumo_agua.php");
					break;
	
				//Agua potable -> Reportes -> Consumo de agua
				case 'reporte_consumo_agua':
					require_once("aguaPotable/reporte_consumo_agua.php");
					break;
	
				//**************************************EMPRESAS**************************************/
				//Empresas -> Archivo ->  Crear empresa
				case 'crear_empresa':
					require_once("empresa/crear_empresa.php");
					break;
	
				//Empresas -> Archivo ->  Modificar empresa
				case 'cambioe':
					require_once("empresa/cambioe.php");
					break;
	
				case 'cambiou':
					require_once("empresa/cambiou.php");
					break;
	
				//Empresas -> Archivo ->  Niveles de seguridad
				case 'niveles_seguri':
					require_once("empresa/niveles_seguri.php");
					break;
	
				//Empresas -> Archivo -> Resumen de periodo
				case 'mostrar_venci':
					require_once("empresa/mostrar_venci.php");
					break;
	
				//Empresas -> Mantenimiento -> Recuperar facturas
				case 'recuperar_fac':
					require_once("empresa/recuperar_factura.php");
					break;
	
				//**************************************FARMACIA**************************************/
				//Farmacia -> Archivo -> Control de ingreso de articulo
				//Inventario -> Archivo -> Ingreso por bodegas
				case 'articulos':
					require_once("farmacia/articulos.php");
					break;
	
				//Farmacia -> Archivo -> Catalogo de productos
				case 'articulos_bodega':
					require_once("farmacia/articulos_bodega.php");
					break;
	
				//Farmacia -> Archivo -> Pacientes
				case 'pacientes':
					require_once("farmacia/paciente.php");
					break;
	
				//Farmacia -> Archivo -> Descargos
				case 'vis_descargos':
					require_once("farmacia/descargos.php");
					break;
	
				//Farmacia -> Archivo -> Descargos bodega
				case 'descargos_bodega':
					require_once("farmacia/descargos_bodega.php");
					break;
	
				//Farmacia -> Archivo -> Devoluciones x comprobante
				case 'devoluciones_insumos':
					require_once("farmacia/devoluciones_insumos.php");
					break;
	
				//Farmacia -> Archivo -> Devoluciones x departamento
				case 'devoluciones_departamento':
					require_once("farmacia/devoluciones_x_departamento.php");
					break;
	
				//Farmacia -> Reportes -> Reporte de comprobantes
				case 'descargos_procesados':
					require_once("farmacia/reporte_descargos_procesados.php");
					break;
	
				//Farmacia -> Reportes -> Farmacia interna
				case 'farmacia_interna':
					require_once("farmacia/farmacia_interna.php");
					break;
	
				//Farmacia -> Reportes -> Cliente/Proveedor
				case 'cliente_proveedor':
					require_once("farmacia/cliente_prove_bodega.php");
					break;
	
				//Farmacia -> Bodega -> Proveedor de bodega
				case 'prove_bodega':
					require_once("farmacia/proveedor_bodega.php");
					break;
	
				//Farmacia -> Bodega -> Ingreso facturas
				case 'factura_bodega':
					require_once("farmacia/ingreso_factura_bodega.php");
					break;
	
				case 'ingresar_proveedor':
					require_once("farmacia/ingresar_proveedor.php");
					break;
	
				case 'ingresar_paciente':
					require_once("farmacia/ingresar_paciente.php");
					break;
	
				case 'ingresar_descargos':
					require_once("farmacia/ingreso_descargos.php");
					break;
	
				case 'ingresar_factura':
					require_once("farmacia/ingresar_factura.php");
					break;
	
				case 'facturacion_insumos':
					require_once("farmacia/facturacion_insumos.php");
					break;
	
				case 'devoluciones_detalle':
					require_once("farmacia/devoluciones_detalle.php");
					break;
	
				case 'farmacia_interna_detalle':
					require_once("farmacia/farmacia_interna_detalle.php");
					break;
	
				//**************************************EDUCATIVO**************************************/
				//Educativo -> Archivo -> Detalle de estudiante
				case 'detalle_estudiante':
					require_once("educativo/detalle_estudiante.php");
					break;
					case 'matricula_estudiante':
					require_once("educativo/matricula_estudiante.php");
					break;
	
				//**************************************INVENTARIO**************************************/
	
				//Facturacion -> Archivo -> Catalogo de inventario
				case 'catalogoPro':
					require_once("inventario/catalogo_producto.php");
					break;
					
				//Facturacion -> Archivo -> Catalogo de inventario
				case 'catalogoProBaq':
					require_once("inventario/catalogo_producto_baq.php");
					break;
	
				//Inventario -> Archivo -> Asignación categorias generales
				case 'catalogo_bodega':
					require_once("inventario/catalogo_bodega.php");
					break;

				//Inventario -> Archivo -> Referenciales SRI
				case 'referenciales_sri':
					require_once("seteos/referenciales_sri.php");
					break;
	
				//Inventario -> Archivo -> Registro de Categorias
				case 'articulos_inventario':
					require_once("inventario/categorias.php");
					break;
	
				//Inventario -> Archivo -> Entrega de materiales online
				case 'inventario_online':
					require_once("inventario/inventario_online.php");
					break;
	
				//Inventario -> Archivo -> Control inventario E/S
				case 'registro_es':
					require_once("inventario/registro_es.php");
					break;
	
				//Inventario -> Archivo -> Ingreso de presupuestos
				case 'ingreso_presupuesto':
					require_once("inventario/ingreso_presupuesto.php");
					break;
	
				//Inventario  -> Ingreso de Productos -> Recepcion
				case 'alimentosRec':
					require_once("inventario/alimentos_recibidos.php");
					break;
	
				//Inventario  -> Ingreso de Productos -> Clasificacion
				case 'alimentosRec2':
					require_once("inventario/alimentos_recibidos2.php");
					break;
	
				//Inventario  -> Ingreso de Productos -> Checking contable
				case 'alimentosRec3':
					require_once("inventario/alimentos_recibidos_cheking.php");
					break;
				//Inventario  -> Ingreso de Productos -> Almacenamiento
				case 'almacenamiento_bod':
					require_once("inventario/almacenamiento_bodega.php");
					break;
				//Inventario  -> Ingreso de Productos -> Reubicar
				case 'reasignacion_bodega':
					require_once("inventario/reubicar.php");
					break;
					//Inventario -> Asignación
				case 'asignacion_os':
					require_once("inventario/asignacion_os.php");
					break;
				case 'asignacion_familia':
					require_once("gestion_social/asignacion_familia.php");
					break;
				case 'picking_familia':
					require_once("gestion_social/picking_familia.php");
					break;
				case 'picking_productoresAli':
					require_once("gestion_social/picking_productoresAli.php");
					break;
				case 'picking_org':
					require_once("inventario/asignacion_picking.php");
					break;
				//Inventario -> egreso de Productos -> egreso producto
				case 'egreso_alimentos':
					require_once("inventario/egreso_alimentos.php");
					break;
				case 'egreso_alimento2':
					require_once("inventario/egreso_alimento2.php");
					break;
	
				//Gestion Social -> egreso de Productos -> registro beneficiarios
				case 'registro_beneficiario':
					require_once("gestion_social/registro_beneficiario.php");
					break;
				
				//Inventario  -> Reportes -> Lista productos
				case 'CatalogoCtas':
					require_once("inventario/Catalogo.php");
					break;
	
				//Inventario  -> Reportes -> Kardex de productos
				case 'kardex':
					require_once("inventario/kardex.php");
					break;
	
				//Inventario  -> Reportes -> Resumen de existencia
				case 'ResumenKardex':
					require_once("inventario/ResumenK.php");
					break;
	
				//Agua potable -> Archivo -> Catalogo de productos
				case 'catalogoPro':
					require_once("inventario/catalogo_producto.php");
					break;
	
				case 'ingreso_articulos':
					require_once("inventario/ingreso_articulo.php");
					break;
	
				case 'solicitud_material':
					require_once("inventario/solicitud_material.php");
					break;
				case 'lista_aprobacion_solicitud':
					require_once("inventario/lista_aprobacion_solicitud.php");
					break;
				case 'aprobacion_solicitud':
					require_once("inventario/aprobacion_solicitud.php");
					break;
				case 'solicitud_proveedor':
					require_once("inventario/envio_solicitud_proveedor.php");
					break;
				case 'lista_solicitud_proveedor':
					require_once("inventario/lista_envio_solicitud_proveedor.php");
					break;
				case 'aprobar_proveedor':
					require_once("inventario/aprobar_solicitud_proveedor.php");
					break;
				case 'lista_aprobar_proveedor':
					require_once("inventario/lista_aprobar_solicitud_proveedor.php");
					break;
				case 'lista_compras':
					require_once("inventario/lista_compras.php");
					break;
				case 'detalle_compra':
					require_once("inventario/detalle_compra.php");
					break;
				//Auditoria -> Archivo -> Modulo de auditoria
				case 'auditoria':
					require_once("auditoria/modulo_auditoria.php");
					break;
	
				//MODALES
				case 'FSubCtas':
					require_once('contabilidad/FSubCtas.php');
					break;
	
				case 'FCompras':
					require_once('contabilidad/FCompras.php');
					break;
	
				case 'FExportaciones':
					require_once('contabilidad/FExportaciones.php');
					break;
	
				case 'FImportaciones':
					require_once('contabilidad/FImportaciones.php');
					break;
	
				case 'FVentas':
					require_once('contabilidad/FVentas.php');
					break;
	
				case 'FCliente':
					require_once('contabilidad/FCliente.php');
					break;
	
				//Facturacion -> Archivo -> Abonos de facturas
				case 'FAbonos':
					require_once('contabilidad/FAbonos.php');
					break;
	
				//Gerencia -> Reportes -> Listado de Facturas
				case 'listadoFacturasElectronicas':				
					require_once("facturacion/cartera_clientes.php");
					break;
				
				//Inscripciones -> Formularios -> Voluntarios
				case 'voluntarios':
					require_once("inscripciones/voluntarios.php");
					break;
	
				//Inscripciones -> Formularios -> Voluntarios
				case 'migrar_datos':
					require_once("migracion/migrar_datos.php");
					break;
				//Migracion -> Archivo -> Migrar_esquemas
				case 'migrar_esquemas':
					require_once('migracion/migrar_esquemas.php');
					break;

				/**************************************ROL PAGOS***************************************/
				//Rol Pagos -> Archivo -> Registro de empleados
				case 'registro_empleados':
					require_once('rol_pagos/registro_empleados.php');
					break;
				
				//Rol Pagos -> Archivo -> Registro horas trabajadas.
				case 'registro_horas_laboradas':
					require_once('rol_pagos/registro_horas_laboradas.php');
					break;
	
				default:
					echo "<div class='box-body'><img src='../../img/404.png' width='100%'></div>";
					break;
			}
		}catch(Exception $e){
			//print_r($e->getCode());
			echo "<div class='row justify-content-center'><div class='row' style='width:60%;'><img src='../../img/modulo_en_proceso.png'></div></div>\n";
		}
	} else {
		switch ($_SESSION['INGRESO']['modulo_']) {
			case '01':
				echo "<div class='box-body'><img src='../../img/modulo_contable.gif' width='100%'></div>";
				break;
			case '02':
				echo "<div class='box-body'><img src='../../img/modulo_facturacion.png' width='100%'></div>";
				break;
			case '03':
				echo "<div class='box-body'><img src='../../img/modulo_inventario1.gif' width='100%'></div>";
				break;
			case '09':
				echo "<div class='box-body'><img src='../../img/modulo_electronicos.png' width='100%'></div>";
				break;
			case '10':
				echo "<div class='box-body'><img src='../../img/modulo_comprobantes.png' width='100%'></div>";
				break;
			case '11':
				echo "<div class='box-body'><img src='../../img/modulo_educativo.png' width='100%' height='500px'></div>";
				break;
			case '14':
				echo "<div class='box-body'><img src='../../img/modulo_gestion_social.png' width='100%'></div>";
				break;
			case '28':
				echo "<div class='box-body'><img src='../../img/modulo_farmacia.png' width='100%'></div>";
				break;
			case '40':
				echo "<div class='box-body'><h1>MODULO INSCRIPCIONES</h1></div>";
				break;
			case '99':
				echo "<div class='box-body'><img src='../../img/modulo_empresa.png' width='100%' height='500px'></div>";
				break;

			default:
				$titulo = "
						<div class='card'>
						<div class='card-body' style='height: 50vh; height: calc(400px + 10vh);'>
							<div class='row h-100'>
						        <div class='col-sm-6  d-flex justify-content-center align-items-center'>
						            <h1>MÓDULO " . ((isset($_GET['mod'])) ? $NombreModulo : '') . "</h1>
						        </div>
						        <div class='col-sm-6 d-flex justify-content-center align-items-center'>
						          <img src='" . $modulo_logo . "' width='50%;'>
						        </div>
						    </div>";

			$titulo .= '</div></div>';
				echo $titulo;
				break;
		}
	}


require_once("../../headers/footer.php"); ?>