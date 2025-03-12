<!--
    AUTOR DE RUTINA	: Teddy Moreira
    MODIFICADO POR : Teddy Moreira
    FECHA CREACION : 10/03/2025
    FECHA MODIFICACION : 11/03/2025
    DESCIPCION : Interfaz de modulo Seteos/Referenciales SRI
 -->
<link rel="stylesheet" href="../../dist/css/arbol.css">
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
    <div class="row" style="margin:10px">
        <div class="col-sm-6">
            <div class="card border" style="height: 68vh;">
                <input type="hidden" id="txt_anterior" val="">
                <div class="card-header">
                    Referenciales SRI
                </div>
                <div class="card-body" id="acordion_refs" style="overflow-y: auto;">
                </div>
                <div class="alert alert-warning" id="alertNoData" style="display: none; margin-top:10px">
                    No se encontraron datos que mostrar.
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row row-cols-auto gx-3 pb-2 d-flex align-items-center ps-2">
                <div class="row row-cols-auto btn-group">
                    <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                                    print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
                            <img src="../../img/png/salire.png">
                    </a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Guardar" id="btnGuardar">
                        <img src="../../img/png/grabar.png">
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Eliminar" id="btnEliminar">
                        <img src="../../img/png/eliminar.png">
                    </button>
                </div>
            </div>
            
            <form id="miFormulario" class="mt-2">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="codigoP"><b>Código del referencial</b></label>
                        <!--
                        <input type="text" class="form-control" maxlength="5" id="codigoP"
                            placeholder="<?php echo $_SESSION['INGRESO']['Formato_Inventario']; ?>">
                        -->
                        <input type="text" class="form-control form-control-sm" maxlength="6" id="codigoP"
                            maxlength="5">
                    </div>
                    <div class="col-sm-6">
                        <label for="txtTP"><b>Tipo de Referencia:</b></label>
                        <input type="text" class="form-control form-control-sm" maxlength="15" id="txtTP">
                    </div>
                </div>
                <div class="row mt-2">
                    <!-- <div class="col-sm-6">
                        <label for="selectNivel"><b>Nivel:</b></label>
                        <select class="form-select form-select-sm" name="selectNivel" id="selectNivel"></select>
                    </div> -->
                    <div class="col-sm-12">
                        <label for="txtConcepto"><b>Descripción del referencial</b></label>
                        <input type="text" class="form-control form-control-sm" maxlength="130" id="txtConcepto">
                    </div>
                </div>
                <div class="row align-items-end mt-2">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="habilitarCuentas" id="habilitarCuentas" checked onchange="toggleInput(this, 'containerC')">
                                    <label class="form-check-label" for="habilitarCuentas">
                                        <b>Abreviado</b>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="contsCuentas">
                            <div class="col-sm-12">
                                <input type="text" class="form-control form-control-sm" maxlength="18" id="txtAbrev">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="cbxTFA" id="cbxTFA">
                            <label class="form-check-label" for="cbxTFA">TFA</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="cbxTPFA" id="cbxTPFA">
                            <label class="form-check-label" for="cbxTPFA">TPFA</label>
                        </div>
                    </div>
                </div>
                
                
                <div class="row mt-2" id="nombresContainer">
                    
                </div>

                <div class="alert alert-light" id="alertUse" style="display: none; margin-top: 5px; padding: 2px;">
                    <span class="glyphicon glyphicon-exclamation-sign text-danger" aria-hidden="true"></span>
                </div>

            </form>

        </div>
    </div>
    <br><br>
    <div class="modal fade" id="InfoCatalogo" tabindex="-1" role="dialog" aria-labelledby="FrmProductosLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="FrmProductosLabel">Catalogo Prueba</h5>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="lista_catalogo_real">
                        
                    </div>
                    <ul>
                        <li>hola</li>
                        <li>hola</li>
                        <ul>
                            <li>hola 2</li>
                            <li>hola 2</li>
                        </ul>
                        <li>hola</li>
                    </ul>
                </div>
            
            </div>
        </div>
    </div>
    <!-- Script JavaScript para manipular la página -->
    <script src="../../dist/js/seteos/referenciales_sri.js"></script>