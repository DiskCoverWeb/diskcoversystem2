<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <style>
        .accordion-body {
            margin-left: 15px;
        }

        .accordion-body:hover {
            color: blue !important;
            cursor: pointer;
        }

        .icono {
            margin-right: 5px;
            font-size: 26px;
        }

        #accordion {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>

<body> -->
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
                <div style="display: none;">
                    <div style="padding-top:10px">
                        <h4>Tipos de Procesos</h4>
                    </div>
                    <div style="padding-top:10px">
                        <select class="form-select form-select-sm" id="selectTipo" name="selectTipo">
                            <option value="">Tipo de Informacion</option>
                        </select>
                        <input type="text" style="display:none" value="" id="tp">
                    </div>
                    <div class="accordion" id="accordion" style="margin-top:20px">>
                        
                    </div>
                </div>
                <input type="hidden" id="txt_anterior" val="">
                <div class="card-header">
                    TIPOS DE PROCESOS
                </div>
                <div class="card-body" id="tree1" style="overflow-y: auto;">
    
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
                        <label for="codigoP"><b>Código del producto</b></label>
                        <!--
                        <input type="text" class="form-control" maxlength="5" id="codigoP"
                            placeholder="<?php echo $_SESSION['INGRESO']['Formato_Inventario']; ?>">
                        -->
                        <input type="text" class="form-control form-control-sm" maxlength="5" id="codigoP" placeholder="CC.CC"
                            maxlength="5">
                    </div>
                    <div class="col-sm-6">
                        <label for="txtConcepto"><b>Concepto o detalle del producto</b></label>
                        <input type="text" class="form-control form-control-sm" id="txtConcepto">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        <label for="selectNivel"><b>Nivel:</b></label>
                        <select class="form-select form-select-sm" name="selectNivel" id="selectNivel"></select>
                    </div>
                    <div class="col-sm-6">
                        <label for="txtTP"><b>Tipo de Proceso:</b></label>
                        <input type="text" class="form-control form-control-sm" id="txtTP">
                    </div>
                </div>
                <div id="cuentasContainer">
                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="habilitarCuentas" id="habilitarCuentas" checked onchange="toggleInput(this, 'containerC')">
                                <label class="form-check-label" for="habilitarCuentas">
                                    <b>Cuentas Contables</b>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="contsCuentas">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Cta. Debe</span>
                                    <input type="text" class="form-control form-control-sm" id="txtDebe" placeholder="C.C.CC.CC.CC">
                                    
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group input-group-sm">
                                    
                                    <span class="input-group-text">Cta. Haber</span>
                                    <input type="text" class="form-control form-control-sm" id="txtHaber" placeholder="C.C.CC.CC.CC">
                                </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="habilitarDebe" id="habilitarDebe" checked onchange="toggleInput(this, 'inpDebe')">
                                    <label class="form-check-label" for="habilitarDebe">
                                        <b>Cta. Debe</b>
                                    </label>
                                </div>
                                <input type="text" class="form-control form-control-sm" id="txtDebe" placeholder="C.C.CC.CC.CC">
                                
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="habilitarHaber" id="habilitarHaber" checked onchange="toggleInput(this, 'inpHaber')">
                                    <label class="form-check-label" for="habilitarHaber">
                                        <b>Cta. Haber</b>
                                    </label>
                                </div>
                                
                                <input type="text" class="form-control form-control-sm" id="txtHaber" placeholder="C.C.CC.CC.CC">
                            </div> -->
                        </div>
                    </div>
                </div>
                <span style="margin-top:5px; display:none">Tipo de producto</span>
                <div class="row mt-2" id="checkboxContainer">
                    
                    <div class="col-sm-6"  id="colorContainer">
                        <div class="row">
                            <div class="col-sm-12">
                                <b>Color</b>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="pordefault" id="pordefault" checked onchange="toggleInputColor(this)">
                                    <label class="form-check-label" for="pordefault">
                                        Por defecto
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <input type="color" class="form-control form-control-sm form-control-color" style="display:none;" id="colorPick" value="#000000" title="Elegir color">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="habilitarDC" id="habilitarDC" checked onchange="toggleInput(this, 'containerDC');">
                                    <label class="form-check-label" for="habilitarDC">
                                        <b>Tipo Documento</b>
                                    </label>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row" id="containerDC">
                            <div class="col-sm-12">
                                <select name="selectTipoDoc" id="selectTipoDoc" class="form-select form-select-sm">
                                    <option value="">Seleccionar</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cbxProdc" id="cbxCat" value="C" onchange="">
                                    <label class="form-check-label" for="cbxCat">
                                        Credito
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cbxProdc" id="cbxDet" value="D" checked="" onchange="">
                                    <label class="form-check-label" for="cbxDet">
                                        Debito
                                    </label>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <!-- <div class="col-sm-6">
                        <label for="txtTP"><b>Tipo de Proceso:</b></label>
                        <input type="text" class="form-control form-control-sm" id="txtTP">
                    </div> -->
                    <!-- <div class="row col-sm-12">
                    </div> -->
                </div>

                <!-- <div class="row">
                    <div class="col-sm-6" style="display: none; margin-top:10px;" id="colorContainer">
                        <div class="row">
                            <div class="col-sm-12">
                                <b>Color</b>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="pordefault" id="pordefault" checked onchange="toggleInputColor(this)">
                                    <label class="form-check-label" for="pordefault">
                                        Por defecto
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <input type="color" class="form-control form-control-sm form-control-color" style="display:none;" id="colorPick" value="#000000" title="Elegir color">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="txtTP"><b>Tipo de Proceso:</b></label>
                        <input type="text" class="form-control form-control-sm" id="txtTP">
                    </div> -->
                    <!-- <div class="col-sm-6" style="display:none; margin-top:10px;" id="reqFacturaContainer">
                        <label for="picture"><b>Requiere Factura?</b></label>
                        <div class="row">
                            <div class="form-check col-sm-6">
                                <input class="form-check-input" type="radio" name="cbxReqFA" id="siFA" value='FA'>
                                <label class="form-check-label" for="siFA">
                                    Sí
                                </label>
                            </div>
                            <div class="form-check col-sm-6">
                                <input class="form-check-input" type="radio" name="cbxReqFA" id="noFA" value='.'
                                    checked>
                                <label class="form-check-label" for="noFA">
                                    No
                                </label>
                            </div>
                        </div>
                    </div> -->
                <!-- </div> -->
                <div class="row mt-2" id="pictureContainer">
                    <!--<div class="row">
                        <label>Color</label><br>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <input type="checkbox" id="pordefault" onchange="toggleInputColor(this)"> Por defecto
                        </div>
                        <div class="col-sm-2">
                            <input type="color" class="form-control input-xs" id="colorPick" value ="#000000">
                        </div>
                    </div>-->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="divImage" id="divImage" checked onchange="toggleInput(this, 'divImg')">
                                <label class="form-check-label" for="divImage">
                                    <b>Imagen:</b>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="containerImg">
                        <div class="col-sm-6 d-flex flex-column justify-content-start">
                            <!-- <label for="picture"><b>Imagen:</b></label> -->
                            <input type="hidden" value="" id="input_existeimg">
                            <input type="text" class="form-control form-control-sm" id="picture" placeholder="." onchange="validarExisteImg()" aria-describedby="pictureFeedback">
                            <div id="pictureFeedback" class="invalid-feedback">
                                Este nombre ya esta en uso
                            </div>
                            <input type="file" style="margin-top: 10px;" id="imagenPicker" accept="image/png" onchange="previsualizarImagen(this)">
                        </div>
                        <div class="col-sm-6 d-flex flex-column align-items-center">
                            <div id="imagePreview" class="border rounded bg-white" style="width:fit-content">
                                <img id="imageElement" src="" style="min-width:130px;min-height:130px;max-height:130px;max-width:130px;object-fit:cover;"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2" id="nombresContainer">
                    <div class="card">
                        <div class="card-body bg-primary-subtle rounded" id="archivosContainer">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="select_archivos" id="archivosLbl"><b>Nombres de archivos en uso:</b></label>
                                    <select class="form-select" id="select_archivos" size="5" aria-label="Size 3 select example" onchange="previsualizar_ImagenArc()">
                                        
                                    </select>
                                </div>
                                <div class="col-sm-6 d-flex flex-column align-items-center">
                                    <div id="imagePreviewArc" class="border rounded bg-white d-flex justify-content-center align-items-center" style="min-width:130px;min-height:130px;max-height:130px;max-width:130px">
                                        <img id="imageElementArc" src="" style="min-width: 60px;min-height: 60px;max-height:130px;max-width:130px;object-fit:cover;"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-sm-6 bg-primary-subtle rounded py-2" id="archivosContainer">
                        <label for="select_archivos" id="archivosLbl"><b>Nombres de archivos en uso:</b></label>
                        <select class="form-select" id="select_archivos" size="4" aria-label="Size 3 select example">
                            
                        </select>
                    </div>
                    <div class="col-sm-6 d-flex flex-column align-items-center">
                        <div id="imagePreviewArc" class="border rounded bg-white" style="width:fit-content">
                            <img id="imageElementArc" src="" style="min-width:130px;min-height:130px;max-height:130px;max-width:130px;object-fit:cover;"/>
                        </div>
                    </div> -->
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
    <script src="../../dist/js/catalogo_bodega.js"></script>
<!-- </body>




</html> -->