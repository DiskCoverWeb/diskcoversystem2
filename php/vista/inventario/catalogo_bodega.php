<!DOCTYPE html>
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

<body>
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
        <div class="col-sm-6 border border-1 border-primary-subtle rounded">
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
                <!-- Los paneles del acordeón se llenarán aquí dinámicamente -->
            </div>

            <div class="alert alert-warning" id="alertNoData" style="display: none; margin-top:10px">
                No se encontraron datos que mostrar.
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
                <span style="margin-top:5px; display:none">Tipo de producto</span>
                <div class="row" id="checkboxContainer" style="display: none; margin-top:10px;">
                    <div class="col-sm-12">
                        <b>Debito/Credito</b>
                    </div>
                    <div class="row col-sm-12">
                        <div class="col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cbxProdc" id="cbxCat" value="C">
                                <label class="form-check-label" for="cbxCat">
                                    Credito
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cbxProdc" id="cbxDet" value="D" checked="">
                                <label class="form-check-label" for="cbxDet">
                                    Debito
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
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
                    <div class="col-sm-6" style="display:none; margin-top:10px;" id="reqFacturaContainer">
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
                    </div>
                </div>
                <div class="row gap-3 px-2" style="display:none;margin-top:10px;" id="pictureContainer">
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
                    <div class="row col-sm-6 d-flex flex-column justify-content-center">
                        <label for="picture"><b>Imagen:</b></label>
                        <input type="hidden" value="" id="input_existeimg">
                        <input type="text" class="form-control form-control-sm" id="picture" placeholder="." onchange="validarExisteImg()" aria-describedby="pictureFeedback">
                        <div id="pictureFeedback" class="invalid-feedback">
                            Este nombre ya esta en uso
                        </div>
                        <input type="file" style="margin-top: 10px;" id="imagenPicker" accept="image/png" onchange="previsualizarImagen(this)">
                    </div>
                    <div class="row col-sm-6 d-flex flex-column align-items-center">
                        <div id="imagePreview" class="border rounded bg-white" style="width:fit-content">
                            <img id="imageElement" src="" style="min-width:130px;min-height:130px;max-height:130px;max-width:130px;object-fit:cover;"/>
                        </div>
                    </div>
                </div>
                <div class="row p-1" style="display:none" id="nombresContainer">
                    <div class="col-sm-12 bg-primary-subtle rounded py-2" id="archivosContainer">
                        <label for="select_archivos" id="archivosLbl"><b>Nombres de archivos en uso:</b></label>
                        <select class="form-select" id="select_archivos" size="4" aria-label="Size 3 select example">
                            
                        </select>
                    </div>
                </div>

                <div class="alert alert-light" id="alertUse" style="display: none; margin-top: 5px; padding: 2px;">
                    <span class="glyphicon glyphicon-exclamation-sign text-danger" aria-hidden="true"></span>
                </div>

            </form>

        </div>
    </div>
    <br><br>

    <!-- Script JavaScript para manipular la página -->
    <script src="../../dist/js/catalogo_bodega.js"></script>
</body>




</html>