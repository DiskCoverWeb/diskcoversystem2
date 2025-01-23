<script src="../../dist/js/empresa/crear_empresa.js"></script>
<div class="pb-4">
    <div class="">
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<div class="breadcrumb-title pe-3">
				<?php echo $NombreModulo; ?>
			</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
					<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
					</li>
					</ol>
				</nav>
			</div>          
		</div>
	</div> 
    <div class="row row-cols-auto">
        <div class="btn-group">
                <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-outline-secondary">
                    <img src="../../img/png/salire.png">
                </a>
                <button  title="Eliminar Registro" data-bs-toggle="tooltip" class="btn btn-outline-secondary" onclick="eliminar_empresa()">
                    <img src="../../img/png/delete_file.png" >
                </button>
                <button type="button" class="btn btn-outline-secondary" title="Grabar Empresa" onclick="guardar_empresa()">
                    <img src="../../img/png/grabar.png">
                </button>
    </div>


    <div class="col-sm-12">
        <div class="box-body">        
            <div class="row">
                <div class="col-sm-2">
                    <label>LISTA DE EMPRESAS</label>
                </div>
                <div class="col-sm-10">
                    <select class="form-control form-control-sm" id="select_empresa" name="select_empresa" onchange="llamar()" onblur="cambiar()">
                            <option value="">Seleccione</option>
                    </select>
                </div>		
            </div>        
        </div>
    </div>
                
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-bs-toggle="tab">Datos Principales</a></li>
                <li class="nav-item"><a class="nav-link" href="#tab_2" data-bs-toggle="tab">Procesos Generales</a></li>
                <li class="nav-item"><a class="nav-link" href="#tab_3" data-bs-toggle="tab">Comprobantes Electrónicos</a></li>                        
            </ul>        
            <div class="tab-content">
                <!-- DATOS PRINCIPALES INICIO -->
                <div class="tab-pane active" id="tab_1">
                    <form action="" id="formulario">
                    <div class="row">
                        <div class="col-sm-2">
                            <label>EMPRESA:</label>
                        </div>
                        <div class="col-sm-10">                                
                            <input type="text" name="TxtEmpresa" id="TxtEmpresa" class="form-control form-control-sm" value="">
                        </div>
                        <div class="col-sm-2">
                            <label hidden="hidden">ITEM:</label>
                        </div>
                        <div class="col-sm-10">                                
                            <input type="hidden" name="TxtItem" id="TxtItem" class="form-control form-control-sm" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label>RAZON SOCIAL:</label>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" name="TxtRazonSocial" id="TxtRazonSocial" class="form-control form-control-sm" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label>NOMBRE COMERCIAL:</label>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" name="TxtNomComercial" id="TxtNomComercial" class="form-control form-control-sm" value="">
                        </div>
                    </div>                
                    <div class="row">
                        <div class="col-sm-2">
                            <label>RUC:</label>
                            <input type="text" name="TxtRuc" id="TxtRuc" class="form-control form-control-sm" value="" onblur="validar_RUC()" onkeyup="num_caracteres('TxtRuc',13)" autocomplete="off">
                        </div>
                        <div class="col-sm-2">
                            <label>OBLIG</label>
                            <select class="form-select form-select-sm" id="ddl_obli" name="ddl_obli">
                                <option value="">Seleccione</option>
                                <option value="SI">SI</option>
                                <option value="NO">NO</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label>REPRESENTANTE LEGAL:</label>
                            <input type="text" name="TxtRepresentanteLegal" id="TxtRepresentanteLegal" class="form-control form-control-sm" value="">
                        </div>
                        <div class="col-sm-2">
                            <label>C.I/PASAPORTE</label>
                            <input type="text" name="TxtCI" id="TxtCI" class="form-control form-control-sm" value="" onblur="validar_CI()" onkeyup="num_caracteres('TxtCI',10)" autocomplete="off">
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-sm-4">
                            <label>NACIONALIDAD</label>
                            <select class="form-control form-control-sm" id="ddl_naciones" name="ddl_naciones" onchange="provincias(this.value)">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label>PROVINCIA</label>
                            <select class="form-control form-control-sm"  id="prov" name="prov" onchange="ciudad(this.value)">
                                <option value="">Seleccione una provincia</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label>CIUDAD</label>
                            <select class="form-control form-control-sm" id="ddl_ciudad" name="ddl_ciudad">
                                <option value="">Seleccione una ciudad</option>
                            </select>
                        </div>                        
                    </div>                    
                    <div class="row">
                        <div class="col-sm-10">
                            <label>DIRECCION MATRIZ:</label>
                            <input type="text" name="TxtDirMatriz" id="TxtDirMatriz" class="form-control form-control-sm" value="">
                        </div>
                        <div class="col-sm-2">
                            <label>ESTA.</label>
                            <input type="text" name="TxtEsta" id="TxtEsta" class="form-control form-control-sm" value="000">
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label>TELEFONO:</label>
                            <input type="text" name="TxtTelefono" id="TxtTelefono" class="form-control form-control-sm" value="">
                        </div>
                        <div class="col-sm-2">
                            <label>TELEFONO 2:</label>
                            <input type="text" name="TxtTelefono2" id="TxtTelefono2" class="form-control form-control-sm" value="">
                        </div>
                        <div class="col-sm-1">
                            <label>FAX:</label>
                            <input type="text" name="TxtFax" id="TxtFax" class="form-control form-control-sm" value="">
                        </div>
                        <div class="col-sm-1">
                            <label>MONEDA</label>
                            <input type="text" name="TxtMoneda" id="TxtMoneda" class="form-control form-control-sm" value="USD">
                        </div>
                        <div class="col-sm-2">
                            <label>NO. PATRONAL:</label>
                            <input type="text" name="TxtNPatro" id="TxtNPatro" class="form-control form-control-sm" value="">
                        </div>
                        <div class="col-sm-1">
                            <label>COD.BANCO</label>
                            <input type="text" name="TxtCodBanco" id="TxtCodBanco" class="form-control form-control-sm" value="">
                        </div>
                        <div class="col-sm-1">
                            <label>TIPO CAR.</label>
                            <input type="text" name="TxtTipoCar" id="TxtTipoCar" class="form-control form-control-sm" value="">
                        </div>
                        <div class="col-sm-2">
                            <label>ABREVIATURA</label>
                            <input type="text" name="TxtAbrevi" id="TxtAbrevi" class="form-control form-control-sm" value="Ninguna">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>EMAIL DE LA EMPRESA:</label>
                            <input type="text" name="TxtEmailEmpre" id="TxtEmailEmpre" class="form-control form-control-sm" value="@">
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>EMAIL DE CONTABILIDAD:</label>
                            <input type="text" name="TxtEmailConta" id="TxtEmailConta" class="form-control form-control-sm" value="@">
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>EMAIL DE RESPALDO:</label>
                            <input type="text" name="TxtEmailRespa" id="TxtEmailRespa" class="form-control form-control-sm" value="@">
                        </div>
                        <div class="col-sm-4 text-center">
                            <label>SEGURO DESGRAVAMEN %</label>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <input type="text" name="TxtSegDes1" id="TxtSegDes1" class="form-control form-control-sm" value="">
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" name="TxtSegDes2" id="TxtSegDes2" class="form-control form-control-sm" value="">
                                </div>
                            </div>                        
                        </div>
                        <div class="col-sm-2">
                            <label>SUBDIR:</label>
                            <input type="text" name="TxtSubdir" id="TxtSubdir" class="form-control form-control-sm" value="" onblur="subdireccion()" onkeyup="mayusculas('TxtSubdir',this.value);">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-10">
                            <label>NOMBRE DEL CONTADOR</label>
                            <input type="text" name="TxtNombConta" id="TxtNombConta" class="form-control form-control-sm" value="">
                        </div>
                        <div class="col-sm-2">
                            <label>RUC CONTADOR:</label>
                            <input type="text" name="TxtRucConta" id="TxtRucConta" class="form-control form-control-sm" value="" onblur="validar_RUConta()" onkeyup="num_caracteres('TxtRucConta',13)" autocomplete="off">
                        </div>
                    </div>
                </div>
                <!-- DATOS PRINCIPALES FIN -->
                <!-- PROCESOS GENERALES INICIO -->
                <div class="tab-pane" id="tab_2">                                
                    <div class="row">
                        <div class="col-md-4" style="background-color:#ffe0c0">                                   
                        <!-- setesos -->
                            <label>|Seteos Generales|</label>
                            <div class="checkbox">
                                <label><input class="form-check-input" type="checkbox" id="ASDAS">Agrupar Saldos Detalle Auxiliar de Submodulos</label>
                            </div>
                            <div class="checkbox">
                                <label><input class="form-check-input" type="checkbox" id="MFNV">Modificar Facturas o Notas de Venta</label>
                            </div>
                            <div class="checkbox">
                                <label><input class="form-check-input" type="checkbox" id="MPVP">Modificar Precio de Venta al Público</label>
                            </div>
                            <div class="checkbox">
                                <label><input class="form-check-input" type="checkbox" id="IRCF">Imprimir Recibo de Caja en Facturación</label>
                            </div>
                            <div class="checkbox">
                                <label><input class="form-check-input" type="checkbox" id="IMR">Imprimir Medio Rol</label>
                            </div>
                            <div class="checkbox">
                                <label><input class="form-check-input" type="checkbox" id="IRIP">Imprimir dos Roles Individuales por pagina</label>
                            </div>
                            <div class="checkbox">
                                <label><input class="form-check-input" type="checkbox" id="PDAC">Procesar Detalle Auxiliar de Comprobantes</label>
                            </div>
                            <div class="checkbox">
                                <label><input class="form-check-input" type="checkbox" id="RIAC">Registrar el IVA en el Asiento Contable</label>
                            </div>
                            <div class="checkbox">
                                <label><input class="form-check-input" type="checkbox" id="FCMS">Funciona como Matriz de Sucursales</label>
                            </div>
                        </div>
                        <div class="col-md-4">                                        
                            <label>LOGO TIPO</label>
                            <input type="text" name="TxtXXXX" id="TxtXXXX" class="form-control form-control-sm" value="XXXXXXXXXX">
                            <div class="form-group" rows="11">                                        
                                <select multiple="" class="form-select form-select-sm" >
                                    <option>ADDSCCES.DLL</option>
                                    <option>ADDSCCUS.DLL</option>
                                    <option>BIBLIO.MDB</option>
                                    <option>C2.EXE</option>
                                    <option>CVPACK.EXE</option>
                                    <option>DATAVIEW.DLL</option>
                                    <option>INSTALL.HTM</option>
                                    <option>LINK.EXE</option>
                                    <option>MSDIS110.DLL</option>
                                    <option>MSPDB60.DLL</option>                                                    
                                </select>                                                
                            </div>
                        </div>
                        <style type="text/css">
                            textarea {
                                resize : none;
                            }
                        </style>
                        <div class="col-md-4">                                        
                            <div class="box-body">
                                <textarea class="form-control form-control-sm" rows="11" resize="none" placeholder=""></textarea>
                            </div>                                        
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>|Numeración de Comprobantes|</label>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <input class="form-check-input" type="checkbox" name="dm1" id="DM" onclick="DiariosM()">Diarios por meses
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <input class="form-check-input" type="checkbox" name="dm1" id="DS" onclick="DiariosS()">Diarios secuenciales
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <input class="form-check-input" type="checkbox" id="IM" onclick="IngresosM()">Ingresos por meses
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <input class="form-check-input" type="checkbox" id="IS" onclick="IngresosS()">Ingresos secuenciales
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <input class="form-check-input" type="checkbox" id="EM" onclick="EgresosM()">Egresos por meses
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <input class="form-check-input" type="checkbox" id="ES" onclick="EgresosS()">Egresos secuenciales
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <input class="form-check-input" type="checkbox" id="NDM" onclick="NDPM()">N/D por meses
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <input class="form-check-input" type="checkbox" id="NDS" onclick="NDPS()">N/D secuenciales
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <input class="form-check-input" type="checkbox" id="NCM" onclick="NCPM()">N/C por meses
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <input class="form-check-input" type="checkbox" id="NCS" onclick="NCPS()">N/C secuenciales
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8">                        
                            <div class="row">
                                <div class="col-sm-12" style="background-color:#ffffc0">
                                    <b>|Servidor de Correos|</b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10" style="background-color:#ffffc0">
                                    <b>Servidor SMTP</b>
                                    <input type="text" name="TxtServidorSMTP" id="TxtServidorSMTP" class="form-control input-xs" value="">
                                </div>
                                <div class="col-sm-2" style="background-color:#ffffc0">
                                    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
                                    <button type="button" class="btn btn-outline-secondary" title="Grabar Empresa" onclick="()">
                                        <img src="../../img/png/grabar.png">
                                    </button>
                                </div>
                                </div>
                            </div>
                            <div class="row" style="background-color:#ffffc0">
                                <div class="col-sm-2">
                                    <input class="form-check-input" type="checkbox" id="Autenti">Autentificación
                                </div>
                                <div class="col-sm-1">
                                    <input class="form-check-input" type="checkbox" id="SSL">SSL
                                </div>
                                <div class="col-sm-2">
                                    <input class="form-check-input" type="checkbox" id="Secure">SECURE
                                </div>
                                <div class="col-sm-1">
                                    PUERTO
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" name="TxtPuerto" id="TxtPuerto" class="form-control form-control-sm" value="">                                
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">
                                    <input class="form-check-input" type="checkbox" id="AsigUsuClave" onclick="MostrarUsuClave()">ASIGNA USUARIO Y CLAVE DEL REPRESENTANTE LEGAL
                                </div>
                                <div class="col-sm-2">
                                    <label id="lblUsuario">USUARIO</label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" name="TxtUsuario" id="TxtUsuario" class="form-control form-control-sm" value="USUARIO"> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">
                                    <input type="checkbox" id="CopSeEmp" onclick="MostrarEmpresaCopia()">COPIAR SETEOS DE OTRA EMPRESA
                                </div>
                                <div class="col-sm-2">
                                    <label id="lblClave">CLAVE</label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" name="TxtClave" id="TxtClave" class="form-control form-control-sm" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <select class="form-control form-control-sm" id="ListaCopiaEmpresa" name="ListaCopiaEmpresa">
                                        <option value="">Empresa</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                </div>
                    
                    <div class="row">
                        <div class="col-md-4" style="background-color:#c0ffc0">
                            <label>|Cantidad de Decimales en|</label>
                        </div>                                    
                    </div>
                    <div class="row">
                        <div class="col-md-1" style="background-color:#c0ffc0">
                            P.V.P
                            <input type="text" name="TxtPVP" id="TxtPVP" class="form-control form-control-sm" value="">
                        </div>
                        <div class="col-md-1" style="background-color:#c0ffc0">
                            COSTOS
                            <input type="text" name="TxtCOSTOS" id="TxtCOSTOS" class="form-control form-control-sm" value="">
                        </div>
                        <div class="col-md-1" style="background-color:#c0ffc0">
                            I.V.A
                            <input type="text" name="TxtIVA" id="TxtIVA" class="form-control form-control-sm" value="">
                        </div>
                        <div class="col-md-1" style="background-color:#c0ffc0">
                            CANTIDAD
                            <input type="text" name="TxtCantidad" id="TxtCantidad" class="form-control form-control-sm" value="">
                        </div>
                    </div>
                </div>
                <!-- PROCESOS GENERALES FIN -->
                <!-- COMPROBANTES ELECTRONICOS INICIO-->
                <div class="tab-pane" id="tab_3">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>|Firma Electrónica|</label>
                        </div>                                
                        <div class="col-sm-4">                                    
                            <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="option1" checked="" onclick="AmbientePrueba()">
                            Ambiente de Prueba
                        </div>
                        <div class="col-sm-4">
                            <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="option2" onclick="AmbienteProduccion()">
                                Ambiente de Producción
                            </div>
                            <div class="col-sm-2">
                                CONTRIBUYENTE ESPECIAL          
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="TxtContriEspecial" id="TxtContriEspecial" class="form-control form-control-sm" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label>WEBSERVICE SRI RECEPCION</label>
                                <input type="text" name="TxtWebSRIre" id="TxtWebSRIre" class="form-control form-control-sm" value="TxtWebSRIre"><!-- disabled="disabled">-->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label>WEBSERVICE SRI AUTORIZACIÓN</label>
                                    <input type="text" name="TxtWebSRIau" id="TxtWebSRIau" class="form-control form-control-sm" value="TxtWebSRIau"><!-- disabled="disabled">-->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10">
                                <label>CERTIFICADO FIRMA ELECTRONICA (DEBE SER EN FORMATO DE EXTENSION P12</label>
                                <input type="text" name="TxtEXTP12" id="TxtEXTP12" class="form-control form-control-sm" value="">
                            </div>
                            <div class="col-sm-2">
                                <label>CONTRASEÑA:</label>
                                <input type="text" name="TxtContraExtP12" id="TxtContraExtP12" class="form-control form-control-sm" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10">
                                <label>EMAIL PARA PROCESOS GENERALES:</label>
                                <input type="text" name="TxtEmailGE" id="TxtEmailGE" class="form-control form-control-sm" value="@">
                            </div>
                            <div class="col-sm-2">
                                <label>CONTRASEÑA:</label>
                                <input type="text" name="TxtContraEmailGE" id="TxtContraEmailGE" class="form-control form-control-sm" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10">
                                <label>EMAIL PARA DOCUMENTOS ELECTRONICOS:</label>
                                <input type="text" name="TxtEmaiElect" id="TxtEmaiElect" class="form-control form-control-sm" value="@">
                            </div>
                            <div class="col-sm-2">
                                <label>CONTRASEÑA:</label>
                                <input type="text" name="TxtContraEmaiElect" id="TxtContraEmaiElect" class="form-control form-control-sm" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10">
                                <label><input type="checkbox">Enviar Copia de Email</label>                                        
                                <input type="text" name="TxtCopiaEmai" id="TxtCopiaEmai" class="form-control form-control-sm" value="@">
                            </div>
                            <div class="col-sm-2">
                                <label>RUC Operadora</label>
                                <input type="text" name="TxtRUCOpe" id="TxtRUCOpe" class="form-control form-control-sm" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">                            
                                    <label>LEYENDA AL FINAL DE LOS DOCUMENTOS ELECTRONICOS</label>
                                    <textarea name="txtLeyendaDocumen" id="txtLeyendaDocumen"class="form-control form-control-sm" rows="2" resize="none" placeholder="Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: NUMERO_TELEFONO, o escriba al correo EMAIL_EMPRESA; para Transferencia o Depósitos hacer en El Banco NOMBRE_BANCO a Nombre de REPRESENTANTE_LEGAL/CTA_AHR_CTE_NUMERO, a Nombre de RAZON_SOCIAL"></textarea>                            
                            </div>
                            <div class="col-sm-12">
                                <label>LEYENDA AL FINAL DE LA IMPRESION EN LA IMPRESORA DE PUNTO DE VENTA DE DOCUMENTOS ELECTRÓNICOS</label><br>                            
                                <textarea name="txtLeyendaImpresora" id="txtLeyendaImpresora"class="form-control form-control-sm" rows="2" resize="none" placeholder="Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: NUMERO_TELEFONO"></textarea>
                            </div>
                        </div>
                    </div>                
                </div>
                <!-- COMPROBANTES ELECTRONICOS FIN-->  
                </form>                  
            </div>
        </div>
    </div>            
</div>