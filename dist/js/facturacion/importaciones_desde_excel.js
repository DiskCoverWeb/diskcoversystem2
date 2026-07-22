
    $(document).ready(function() {

        switch(ModuloActualNombre)
        {
            case "INVENTARIO":
                 $('#lbl_label2').text("Seleccione la Cuenta");
                 // Command1.Visible = True
                 $('#pnl_dclineas').removeClass('d-none');
            case "CONTABILIDAD":
                 // Label2.Visible = False
                 // Label7.Visible = False
                 // Label12.Visible = False
                 // MBoxCta_Pat.Visible = False
                 // MBoxCta_Inv.Visible = False
                 // DCLinea.Visible = False
                 // Command1.Visible = True
                 // Command4.Visible = True
            case "FACTURACION":
                 // Label1.Visible = True
                 // Label11.Visible = False
                 // LblConcepto.Visible = False
                 // LblDiferencia.Visible = False
                 // LabelDebe.Visible = False
                 // LabelHaber.Visible = False
                 // Command1.Visible = False
                 // DGAsiento.Visible = False
                 // AdoAsiento.Visible = False
                 $('#pnl_dclineas').removeClass('d-none');
            case "EDUCATIVO":
                 // Label2.Caption = "SELECCIONE EL CURSO"
                 // Label2.Visible = True
                 // DCLinea.Visible = True
            case "ROL PAGOS":
                 // Label1.Visible = True
                 // Label11.Visible = False
                 // LblConcepto.Visible = False
                 // LblDiferencia.Visible = False
                 // LabelDebe.Visible = False
                 // LabelHaber.Visible = False
                 // Command1.Visible = False
                 // DGAsiento.Visible = False
                 // AdoAsiento.Visible = False
                 // Label2.Visible = True
                 // DCLinea.Visible = True
            case "CAJACREDITO":
    }
  

        DCLineas();
    });


    function guardarINV() 
    {        
        var fileInput = document.getElementById('fileInput');
        var archivo = fileInput.files[0];

        if (!archivo) {
            Swal.fire('Error', 'Por favor seleccione un archivo', 'error');
            return;
        }

        var formData = new FormData();
        formData.append('archivo', archivo); 

        var parametros = 
        {
            'Tipo_Carga':$('#txt_tipo_carga').val(),
            'CTP':$('#CTP').val(),
        }


        formData.append('parametros', JSON.stringify(parametros)); 
        $.ajax({
            type: "POST",
               url:   '../controlador/facturacion/importar_desde_excelC.php?guardarINV=true',
            // data:{parametros:parametros,formData},
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType:'json',
            success: function(data)
            {
                if(data==1)
                {
                    Swal.fire('Guardado','','success').then(function(){
                        location.reload();
                    })
                }else if(data==-2)
                {
                    Swal.fire('formato de evidencia incorrecto','asegurese de que sea una imagen','error');
                }else if(data==-3)
                {
                    Swal.fire('No se ha agregado ninguna linea','','error');
                }
            }
        });
    }


    function DCLineas()
    {
        $('#ddl_DCLinea').select2({
          placeholder: 'Seleccione un cliente',
          ajax: {
            url:   '../controlador/facturacion/importar_desde_excelC.php?DCLinea=true&modulo='+ModuloActualNombre,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
              return {
                results: data
              };
            },
            cache: true
          }
        });
    }



    const fileInput = document.getElementById('fileInput');
    const uploadBtn = document.getElementById('btn_importar');
    const fileName = document.getElementById('fileName');
    const contenidoDiv = document.getElementById('pnl_contenido_excel');
    var ruta_archivo = "";
        
          uploadBtn.addEventListener('click', () => {
            fileInput.click();
        });
        
        fileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (!file) return;
            
            const extension = file.name.split('.').pop().toLowerCase();
            ruta_archivo = file.name;
            console.log(file);
            
            if (extension === 'xlsx' || extension === 'xls') {
                leerExcel(file);
            } else if (extension === 'txt' || extension === 'csv') {
                leerTXT(file);
            } else {
              Swal.fire("Formato no soportado. Use .xlsx, .xls, .txt o .csv","","error");
            }
        });
        
        function leerExcel(file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const primeraHoja = workbook.Sheets[workbook.SheetNames[0]];
                    
                    // Convertir a JSON
                    const datos = XLSX.utils.sheet_to_json(primeraHoja);                    
                    // Mostrar contenido
                    mostrarComoTabla(datos);
                    
                    // // Recorrer el Excel (ejemplo)
                    // console.log('=== RECORRIENDO EXCEL ===');
                    // datos.forEach((fila, index) => {
                    //     console.log(`Fila ${index + 1}:`, fila);
                    // });
                    
                } catch (error) {
                		Swal.fire("Error al leer documento",error.message,"error");
                }
            };
            
            reader.onerror = function() {
                		Swal.fire("Error al leer documento",error.message,"error");
            };
            
            reader.readAsArrayBuffer(file);
        }
        
        function leerTXT(file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                try {
                    const contenido = e.target.result;
                    const lineas = contenido.split('\n');
                    
                    
                    // Si es CSV (separado por comas o tabs)
                    if (file.name.endsWith('.csv') || contenido.includes(',') || contenido.includes(';')) {
                        procesarCSV(contenido);
                    } else {
                        // TXT plano
                       
                       // mostrarComoTexto(contenido, lineas);
                    }
                    
                } catch (error) {
                    	Swal.fire("Error al leer documento",error.message,"error");
                }
            };
            
            reader.onerror = function() {
                Swal.fire("Error al leer documento",error.message,"error");
            };
            
            reader.readAsText(file,'UTF-8');
        }
        
        function procesarCSV(contenido) {
            const lineas = contenido.split('\n').filter(line => line.trim());
            
            if (lineas.length === 0) {
                contenidoDiv.innerHTML = '<p>Archivo vacío</p>';
                return;
            }
            
            // Intentar detectar separador (coma o tabulación)
            let separador = ',';
            if (lineas[0].includes('\t')) {
                separador = '\t';
            }

             if (lineas[0].includes(';')) {
                separador = ';';
            }
            
            // Convertir CSV a array de objetos
            const encabezados = lineas[0].split(separador).map(h => h.trim());
            const datos = [];
            
            for (let i = 1; i < lineas.length; i++) {
                const valores = lineas[i].split(separador);
                const fila = {};
                encabezados.forEach((enc, idx) => {
                    fila[enc] = valores[idx] ? valores[idx].trim() : '';
                });
                datos.push(fila);
            }
            
            console.log(datos);

            mostrarComoTabla(datos);
            
            // // Recorrer CSV
            // console.log('=== RECORRIENDO CSV ===');
            // datos.forEach((fila, index) => {
            //     console.log(`Fila ${index + 1}:`, fila);
            // });
        }
        
        function mostrarComoTabla(datos) {
            if (!datos || datos.length === 0) {
                contenidoDiv.innerHTML = '<p>No hay datos para mostrar</p>';
                return;
            }
            
            let html = '<table class="table table-hover">';
            
            // Encabezados
            html += '<thead><tr>';
            var i = 0;
            Object.keys(datos[0]).forEach(key => {
                Tipo_Carga(i,key);
                html += `<th>${escapeHTML(key)}</th>`;
                i++;
            });
            html += '</tr></thead><tbody>';
            
            // Filas
            var total_lineas = 0;
            datos.forEach(fila => {
                html += '<tr>';
                Object.values(fila).forEach(valor => {
                    html += `<td>${escapeHTML(String(valor))}</td>`;
                });
                html += '</tr>';
                total_lineas++;
            });
            
            html += '</tbody></table>';
            contenidoDiv.innerHTML = html;
            fileName.innerHTML = 'Reg. No. '+total_lineas+', '+ruta_archivo;
        }
        
               
        function escapeHTML(str) {
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        function Tipo_Carga(IdField,IdName)
        {
            var Tipo_Carga = 1;
             switch(IdField)
             {
               case 0:
                    if(IdName == "TD"){ Tipo_Carga = 15 }
                    if(IdName == "CI_CLIENTE"){ Tipo_Carga = 20 }
                    if(IdName == "OTRO_PROGRAMA"){ Tipo_Carga = 105}  
                break;
               case 1:
                    if(IdName == "Codigo_Inv"){ Tipo_Carga = 9}
                    if(IdName == "CONSUMO (M3)"){ Tipo_Carga = 17}
                    if(IdName == "CI_RUC_PAS"){ Tipo_Carga = 10}
                break;
               case 2:
                    if(IdName == "Codigo_Nuevo"){ Tipo_Carga = 7}
                    if(IdName == "CodMateria"){ Tipo_Carga = 22}
                    if(IdName == "ALUMNOS"){ Tipo_Carga = 8}
                    if(IdName == "Autorizacion"){ Tipo_Carga = 25}
                    if(IdName == "FECHA DE NACIMIENTO"){ Tipo_Carga = 50}
                    if(IdName == "MADRE"){ Tipo_Carga = 51}
                    if(IdName == "LUGAR Y FECHA"){ Tipo_Carga = 52}  
                break;
               case 3:
                    if(IdName == "Razon_Social"){ Tipo_Carga = 24}
                    if(IdName == "COMPROBANTE"){ Tipo_Carga = 12}
                    if(IdName == "CC"){ Tipo_Carga = 30}
                    if(IdName == "SALDO_ACT"){ Tipo_Carga = 32}
                break;        
               case 4:
                    if(IdName == "DETALLE_DESCUENTO"){ Tipo_Carga = 19}
                    if(IdName == "CODIGO_EXT"){ Tipo_Carga = 4}
                break;
               case 5:
                    if(IdName == "Num_Lista"){ Tipo_Carga = 13}
                    if(IdName == "Correcto"){ Tipo_Carga = 16}
                    if(IdName == "VALOR DIARIO"){ Tipo_Carga = 26}
                    if(IdName == "CATEGORIA"){ Tipo_Carga = 11}
                break;
               case 6:
                    if(IdName == "FECHA_DOC"){ Tipo_Carga = 32}
                    if(IdName == "Desc_Item"){ Tipo_Carga = 101 }
                break;
               case 7:
                    if(IdName == "PROFESION"){ Tipo_Carga = 18}
                    if(IdName == "Sustento"){ Tipo_Carga = 23}
                    if(IdName == "ruc_proveedor"){ Tipo_Carga = 38}
                break;
               case 8:
                    if(IdName == "emision"){ Tipo_Carga = 5}
                    if(IdName == "Telefono_Rep"){ Tipo_Carga = 107}
                    if(IdName == "CI_RUC_P_SUBMOD"){ Tipo_Carga = 99}
                break;
               case 9:
                    if(IdName == "Tipo_Abonos"){ Tipo_Carga = 6}
                    if(IdName == "CodMateria"){ Tipo_Carga = 21}
                    if(IdName == "AUXILIAR"){ Tipo_Carga = 28}
                break;
               case 10:
                    if(IdName == "EDUCATIVO"){ Tipo_Carga = 3}
                    if(IdName == "Diferencias"){ Tipo_Carga = 14}
                break;
               case 11:
                    if(IdName == "NOTA"){ Tipo_Carga = 106}
                break;
               case 12:
                    if(IdName == "Fecha"){ Tipo_Carga = 12}
                break;
               case 13:
                    if(IdName == "Bonificacion Adicional"){ Tipo_Carga = 29}
                    if(IdName == "Serie"){ Tipo_Carga = 103}
                break;
               case 14:
                    if(IdName == "CUENTA ACTIVO"){ Tipo_Carga = 253}
                break;
               case 15:
                    if(IdName == "Razon_Social"){ Tipo_Carga = 102 }
                break;
               case 16:
                    if(IdName == "Grupo"){ Tipo_Carga = 100 }   
                break;
               case 32:
                    if(IdName == "COD_MES"){ Tipo_Carga = 27}  
                break;
               case 41:
                    if(IdName == "SUB_MOD_GASTO"){ Tipo_Carga = 254}
                break;
               case 42:
                break;
             }

            $('#txt_tipo_carga').val(Tipo_Carga)

             // console.log(Tipo_Carga);
        }

