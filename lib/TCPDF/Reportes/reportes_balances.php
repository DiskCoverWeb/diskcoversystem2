<?php
/**
 * Autor: Jean Asencio.
 * Archivo para generar los reportes de los balances 
 * Pantalla Contabilidad -> Estados Financieros -> Balance de Comprobacion / Situacion / General
 */
require('tcpdf_include.php');
class PDFBal extends TCPDF{
    public $datos_cabecera;
    function Header(){
        if ($this->datos_cabecera['tipo_balance'] == 1 || $this->datos_cabecera['tipo_balance'] == 2 || $this->datos_cabecera['tipo_balance'] == 4){
            if(isset($_SESSION['INGRESO']['Logo_Tipo'])){
                    $logo=$_SESSION['INGRESO']['Logo_Tipo'];
                    //si es jpg
                    $src = dirname(__DIR__,3).'/img/logotipos/'.$logo.'.jpg'; 
                    if(!file_exists($src))
                    {
                        $src = dirname(__DIR__,3).'/img/logotipos/'.$logo.'.gif'; 
                        if(!file_exists($src))
                        {
                            $src = dirname(__DIR__,3).'/img/logotipos/'.$logo.'.png'; 
                            if(!file_exists($src))
                            {
                                $logo="diskcover";
                                $src= dirname(__DIR__,3).'/img/logotipos/'.$logo.'.gif';
        
                            }         
                        }
                    }
            }
            $this->Image($src, 10, 3, 35, 20);
            $this->SetFont('helvetica', 'B', 12);
            $this->SetXY(10, 5);

            if($_SESSION['INGRESO']['Razon_Social'] <> $_SESSION['INGRESO']['Nombre_Comercial']){
                $this->Cell(0, 3, $_SESSION['INGRESO']['Razon_Social'], 0, 0, 'C');
                $this->SetFont('helvetica', '', 10);
                $this->ln(5);
                $this->SetX(10);
                $this->Cell(0, 3, strtoupper($_SESSION['INGRESO']['Nombre_Comercial']), 0, 0, 'C');
                $this->ln(5);
            } else {
                $this->Cell(0, 3 , $_SESSION['INGRESO']['Razon_Social'], 0, 0, 'C');
                $this->ln(5);
            }
            $this->SetFont('helvetica', '', 7);
            $txt_ruc = 'R.U.C.: '.$_SESSION['INGRESO']['RUC'];
            $this->Cell(0, 3, $txt_ruc, 0, 0, 'C', false);
            $this->ln(5);

            $this->SetFont('helvetica', '', 7);
            $this->Cell(0, 3, ucfirst(strtolower($_SESSION['INGRESO']['Direccion'].' Telefono: '.$_SESSION['INGRESO']['Telefono1'])), 0, 0, 'C');            

            //logo superior derecho
            $this->Image(dirname(__DiR__, 3).'/img/logotipos/diskcov2.gif', 195, 7, 10, 4);
            $this->ln(2);		
            $this->SetFont('helvetica','b',5);
            $this->SetXY(175,5);
            $this->Cell(17,2,'Pagina No.  ',0,0,'L');
            $this->SetFont('helvetica','',5);
            $this->Cell(0,2,$this->PageNo(),0,0,'L');
            $this->Ln(2);
            $this->SetXY(175,8);
            $this->SetFont('helvetica','b',5);		
            $this->Cell(10,2,'Fecha: ',0,0,'L');
            $this->SetFont('helvetica','',5);
            $this->Cell(0,2,date("Y-m-d") ,0,0,'L');
            $this->Ln(2);
            $this->SetFont('helvetica', 'B', 5);
            $this->SetXY(175,11);
            $this->Cell(9,2,'Hora: ',0,0,'L');
            $this->SetFont('helvetica','',5);
            $this->Cell(0,2,date('h:i:s A'),0,0,'L');
            $this->Ln(2);
            $this->SetXY(175,14);
            $this->SetFont('helvetica','b',5);	
            $this->Cell(12,2,'Usuario: ',0,0,'L');
            $this->SetFont('helvetica','',5);	
            $this->Cell(0,2,$_SESSION['INGRESO']['Nombre_Completo'],0,0,'L');
            $this->ln(2);
            $this->SetXY(175, 17);
            $this->SetFont('helvetica', 'B', 5);
            $this->Cell(10, 2, 'https://www.diskcoversystem.com', 0, 0, 'L');
            $this->ln(2);
            $this->Line(20, 26, 210-20, 26); 
            $this->Line(20, 28, 210-20, 28);
            
            //Titulo de balance de comprobacion.
            $this->SetY(29);
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetLineWidth(0.01);
            $this->MultiCell(0, 12, '', 1, 'L', false);
            $this->SetY(29);
            $this->SetFont('timesB', '', 15);
            $titulo = '';
            if($this->datos_cabecera['tipo_balance'] == 1){
                $titulo = 'BALANCE DE COMPROBACIÓN';
            }
            if($this->datos_cabecera['tipo_balance'] == 2){
                $titulo = 'BALANCE DE COMPROBACIÓN MENSUAL';
            }
            if($this->datos_cabecera['tipo_balance'] == 4){
                $titulo = 'BALANCE DE COMPROBACIÓN';
            }
            $fechaini = $this->datos_cabecera['desde'];
            $fechafin = $this->datos_cabecera['hasta'];
            $formatoFechaIni = strtoupper(date('d/M/y', strtotime($fechaini)));
            $formatoFechaFin = strtoupper(date('d/M/y', strtotime($fechafin)));
            $this->MultiCell(0, 0, $titulo, 0, 'C', false, 1);
            $this->SetFont('times', '', 10);
            $txt_fecha = 'Periodo Desde: '.$formatoFechaIni.' Hasta: '.$formatoFechaFin.'';
            $this->MultiCell(0, 0, $txt_fecha, 0, 'C', false, 1);
        }
        if ($this->datos_cabecera['tipo_balance'] == 6 || $this->datos_cabecera['tipo_balance'] == 5){
            $this->setFont('timesB', '', 10);
            $text_pag = 'Página No.'.$this->getPage();
            $this->Cell(0, 10, $text_pag, 0, 1, 'L');
            $this->setFont('timesB', '', 16);
            $this->SetY(2);
            $this->MultiCell(0, 0, $_SESSION['INGRESO']['Nombre_Comercial'], 0, 'C', false, 1);
            $this->SetFont('timesB', '', 15);
            if($this->datos_cabecera['tipo_balance'] == 5){
                $this->MultiCell(0, 0, 'BALANCE GENERAL', 0, 'C', false, 1);
            } else if($this->datos_cabecera['tipo_balance'] == 6) {
                $this->MultiCell(0, 0, 'ESTADO DE RESULTADOS', 0, 'C', false, 1);
            }
            $fecha = new DateTime($this->datos_cabecera['hasta']);
            $formatter = new IntlDateFormatter(
                'es_ES',
                IntlDateFormatter::LONG,
                IntlDateFormatter::NONE
            );
            $fecha = $formatter->format($fecha);
            $this->SetFont('times', '', 12);
            $this->MultiCell(0, 0, 'Al '.$fecha, 0, 'C', false, 1);
            $y = $this->GetY();
            $this->MultiCell(0, 0, '', 'TB', 'L', false, 1);
            $this->SetY($y);
            $this->SetFont('times', '', 12);
            $Cabecera_tabla = ["CODIGO", "CUENTA", "ANALITICO", "PARCIAL", "TOTAL"];
            $this->Row_Cabecera($Cabecera_tabla);
            $y = $this->GetY();
            $this->ln();
        }
    }
    function Row_Cabecera($array_Datos){
        $this->SetFont('timesB', '', 12);
        $this->MultiCell(25, 10, $array_Datos[0], 0, 'L', false, 0);
        $this->MultiCell(85, 10, $array_Datos[1], 0, 'L', false, 0);
        $this->MultiCell(35, 10, $array_Datos[2], 0, 'L', false, 0);
        $this->MultiCell(25, 10, $array_Datos[3], 0, 'L', false, 0);
        $this->MultiCell(30, 10, $array_Datos[4], 0, 'L', false, 0);
    }   

    function Row_Balance_Estado($datos, $array_param){
        if($array_param['DG'] == 'G'){
            $this->SetFont('timesB', '', 8);
        } else {
            $this->SetFont('times', '', 8);
        }
        $ancho0 = $this->GetStringWidth($datos[0]) + 6;
        $ancho1 = $this->GetStringWidth($datos[1]) + 4;
        $ancho2 = $this->GetStringWidth($datos[2]) + 8;
        $ancho3 = $this->GetStringWidth($datos[3]) + 8;
        $ancho4 = $this->GetStringWidth($datos[4]) + 8;
        $ancho5 = $this->GetStringWidth($datos[5]) + 8;
        $ancho6 = $this->GetStringWidth($datos[6]) + 8;
        $ancho7 = $this->GetStringWidth($datos[7]) + 8;

        if($array_param['DG'] == 'D'){
            //subrayado y curva.
            if(in_array($array_param['TC'], ['C', 'P'])){
                $this->SetFont('times', 'IU', 8);
            }
            //curvilinea nada mas.
            else if(in_array($array_param['TC'], ['CJ', 'TJ', 'BA', 'CB', 'RP', 'RF', 'RB', 'RI', 'CC'])){
                $this->SetFont('times', 'I', 8);
            }
            else {
                $this->SetFont('times', '', 8);
            }
        }
        $this->MultiCell($ancho0, 0, $datos[0], 0, 'L', false, 0);
        $this->MultiCell(10, 0, '', 0, 'L', false, 0);
        $this->MultiCell($ancho1, 0, $datos[1],  0, 'L', false, 0);
        $x = $this->GetX();
        // Bucle para avanzar en X hasta llegar a 135
        while ($x < 135) {
            $this->SetX($x + 1); // Mueve 1 unidad hacia la derecha
            $x = $this->GetX();  // Actualiza la posición
        }
        if($array_param['DG'] == 'G'){
            $this->SetFont('timesB', '', 8);
        } else {
            $this->SetFont('times', '', 8);
        }
        $this->MultiCell($ancho2, 0, $datos[2], 0, 'L', false, 0);
        $this->MultiCell($ancho3, 0, $datos[3], 0, 'L', false, 0);
        $this->MultiCell($ancho4, 0, $datos[4], 0, 'L', false, 0);
        $this->MultiCell($ancho5, 0, $datos[5], 0, 'L', false, 0);
        $this->MultiCell($ancho6, 0, $datos[6], 0, 'L', false, 0);
        $this->MultiCell($ancho7, 0, $datos[7], 0, 'L', false, 0);

        /*$x = $this->GetX();
        $info = [$x, $datos[1]];
        print_r($info); 
        echo '</br>';*/
        $this->ln();
    }

    function PageCheckBreak($y){
        $limit = 40;
        $PageHeight = $this->getPageHeight();
        if(($PageHeight - $y) < $limit){
            if ($this->datos_cabecera['tipo_balance'] == 1 || $this->datos_cabecera['tipo_balance'] == 2 || $this->datos_cabecera['tipo_balance']  == 4){
                $this->setLineWidth(0.2);
                $this->line(16, $y, 195, $y);
                $this->AddPage();
                $this->SetY(44);
                $Nom_Columnas = ["CODIGO", "CUENTA", "SALDO ANTERIOR", "DEBITOS", "CREDITOS", "SALDO ACTUAL"];
                $this->Row_Head($Nom_Columnas);
            } else if ($this->datos_cabecera['tipo_balance'] == 6 || $this->datos_cabecera['tipo_balance'] == 5) {
                $this->AddPage();
                $this->SetY(28);
            }
        }

    }

    function Row_Head($array){
        $html = '<table><thead><tr>';
        $contador = 0;
        foreach($array as $dato){
            $contador++;
            if ($contador==2){
                $html .= '<th colspan="2" style="border: 0.5x solid black; text-align: center; font-size: 15px"><b>'."{$dato}".'</b></th>';
            }
            else{
                $html .= '<th style="border: 0.5px solid black; text-align: center;"><b>'."{$dato}".'</b></th>';
            }
        }
        $html .= '</tr></thead></table>';
        $this->writeHTML($html, 1, true, true, true, 'C');
    }

    function Row_Body($array, $array_param=""){
        $html = '<table><tbody><tr>';
        $contador = 0; // Nos servira tanto para el tamaño de la 2 columna como para alinear los parametrso
        //Vemos si es la ultima columna: la suma de debitos y creditos.
        if($array_param['sum'] == true){
            foreach($array as $data){
               $contador++;
               if($contador == 2){
                    $html .= '<td colspan="2" style="border: 0.5px solid black;"><b>'."{$data}".'</b></td>';
               } else {
                    if(floatval($data)< 0 ){
                        $html .= '<td style="border: 0.5px solid black; text-align: right; color: red;"><b>'."{$data}".'</b></td>';
                    } else {
                        $html .= '<td style="border: 0.5px solid black; text-align: right;"><b>'."{$data}".'</b></td>';
                    }
               }
            }
        }else{    //Para grupo
            if($array_param['DG'] == 'G'){
                foreach($array as $data){
                    $contador++;
                    if ($contador == 2){
                        if($array_param['TC'] == 'CC'){
                            $html .= '<td colspan="2" style="border: 0.5px solid black;"><b><i>'."{$data}".'</i></b></td>';
                        } else {
                            $html .= '<td colspan="2" style="border: 0.5px solid black;"><b>'."{$data}".'</b></td>';
                        }
                    } else{
                        if ($contador >= 3){
                            if (intval($data)< 0){
                                $html .= '<td style="border: 0.5 solid black; color: red; text-align: right;"><b>'."{$data}".'</b></td>';
                            } else
                            {   
                                $html .= '<td style="border: 0.5px solid black; text-align: right;"><b>'."{$data}".'</b></td>';
                            }
                        } else {
                            $html .= '<td style="border: 0.5px solid black;"><b>'."{$data}".'</b></td>';
                        }
                    } 
                }
            }
            //Para Detalle 
            else if ($array_param['DG'] == 'D'){
                foreach ($array as $data){
                    $contador++;
                    if ($contador == 2){
                        //Analizamos el TC aqui
                        if (in_array($array_param['TC'], ['TJ', 'G', 'CC', 'P', 'CB', 'RP', 'RF', 'RB', 'RI', 'I'])){
                            $html .= '<td colspan="2" style="border-left: 0.5px solid black; border-right: 0.5px solid black;"><i>'."{$data}".'</i></td>';
                        }
                        //Cursiva y Subrayado
                        elseif (in_array($array_param['TC'], ['CJ', 'BA', 'C'])){
                            $html .= '<td colspan="2" style="border-left: 0.5px solid black; border-right: 0.5px solid black;"><i><u>'."{$data}".'</u></i></td>';
                        } else {
                            $html .= '<td colspan="2" style="border-left: 0.5px solid black: border-right: 0.5px solid black;">'."{$data}".'</td>';
                        }
                    } else {
                        if ($contador >= 3){
                            if (intval($data)< 0){
                                $html .= '<td style="border-right: 0.5px solid black; border-left: 0.5px solid black; color: red; text-align: right;">'."{$data}".'</td>';
                            } else
                            {   
                                $html .= '<td style="border-left: 0.5px solid black; border-right: 0.5px solid black; text-align: right;">'."{$data}".'</td>';
                            }
                        } else {
                            $html .= '<td style="border-left: 0.5px solid black; border-right: 0.5px solid black;">'."{$data}".'</td>';
                        }
                    }
                }
            }
        }
        $html .= '</tr></tbody></table>';
        $this->writeHTML($html, 0, false, false, false, 'L');
    }
}

function imprimirEstadoResultado($datosTabla, $tipoBal, $fecha_hasta){
    $pdf = new PDFBal(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setPrintHeader(true);
    $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->setMargins(3.5, 24.7, 5.5);  // Valores más razonables en cm
    $pdf->setAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    //Definimos la variable que no ayudará a identifica que tipo de reporte es
    $pdf->datos_cabecera['tipo_balance'] = $tipoBal;
    $pdf->datos_cabecera['hasta'] = $fecha_hasta;
    $pdf->AddPage(); // Añadir una página antes de agregar contenido
    //Salida de Datos;
    $pdf->SetY(28);
    if (count($datosTabla)>0){
        foreach($datosTabla as $value){
            if (!is_array($value)) {
                continue;  // Saltar si no es un array
            }
            foreach($value as $value2){
                if (intval($value2['Total_N6']) == 0){
                    $value2['Total_N6'] = '';
                } else {
                    $value2['Total_N6'] = number_format((float)$value2['Total_N6'], 2, '.', ',');
                }
                if (intval($value2['Total_N5']) == 0){
                    $value2['Total_N5'] = '';
                } else {
                    $value2['Total_N5'] = number_format((float)$value2['Total_N5'], 2, '.', ',');
                }
                if (intval($value2['Total_N4']) == 0){
                    $value2['Total_N4'] = '';
                } else {
                    $value2['Total_N4'] = number_format((float)$value2['Total_N4'], 2, '.', ',');
                }
                if (intval($value2['Total_N3']) == 0){
                    $value2['Total_N3'] = '';
                } else {
                    $value2['Total_N3'] = number_format((float)$value2['Total_N3'], 2, '.', ',');
                }
                if (intval($value2['Total_N2']) == 0){
                    $value2['Total_N2'] = '';
                } else {
                    $value2['Total_N2'] = number_format((float)$value2['Total_N2'], 2, '.', ',');
                }
                if (intval($value2['Total_N1']) == 0){
                    $value2['Total_N1'] = '';
                } else {
                    $value2['Total_N1'] = number_format((float)$value2['Total_N1'], 2, '.', ',');
                }
                //Transformamos el array para que pueda ser recorrido mediante indices.
                $datos = array_values($value2);
                $param['DG'] = $value2['DG'];
                $param['TC'] = $value2['TC'];
                $pdf->Row_Balance_Estado($datos, $param);
                $y = $pdf->GetY();
                $pdf->PageCheckBreak($y);
            }
        }
    }

    //final Firma de gerente y contador
    $pdf->ln(20);
    $y = $pdf->GetY(); 
    $pdf->Line( 30, $y, 65, $y);
    $pdf->Line( 125, $y, 160, $y);
    $pdf->SetFont('times', 'B', 8);
    $pdf->SetX(30);
    //Puesto para nombre representante legal
    $pdf->Multicell(0, 0, '', 0, 'L', false, 0);
    $pdf->SetX(125);
    //Puesto para nombre contador
    $pdf->Multicell(0, 0, '', 0, 'L', false, 0);
    $pdf->ln();
    $pdf->SetX(30);
    $pdf->Multicell(0, 0, 'R.U.C. '.$_SESSION['INGRESO']['RUC_Contador'], 0, 'L', false, 0);
    $pdf->SetX(125);
    $pdf->Multicell(0, 0, 'R.U.C. '.$_SESSION['INGRESO']['CI_Representante'], 0, 'L', false, 0);
    $pdf->ln();
    $pdf->SetX(30);
    $pdf->Multicell(0, 0, 'REPRESENTANTE LEGAL', 0, 'L', false, 0);
    $pdf->SetX(125);
    $pdf->Multicell(0, 0, 'CONTADOR', 0, 'L', false, 0);


    //Salida del PDF
    $pdf->Output('Estado de Situacion.pdf', 'I');
}

function imprimirBalanceComprobacion($datosTabla, $tipoBal, $fechaHasta, $fechaDesde="", $titulo=""){
    $pdf = new PDFBal(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setPrintHeader(true);
    $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->datos_cabecera['tipo_balance'] = $tipoBal;
    $pdf->datos_cabecera['hasta'] = $fechaHasta;
    $pdf->datos_cabecera['desde'] = $fechaDesde;
    $pdf->datos_cabecera['titulo'] = $titulo;
    $pdf->AddPage();
    $pdf->SetY(44);
    $pdf->SetFont('times', '', 10);
    $Nom_Columnas = ["CODIGO", "CUENTA", "SALDO ANTERIOR", "DEBITOS", "CREDITOS", "SALDO ACTUAL"];
    //ancho de 180//
    $pdf->Row_Head($Nom_Columnas);
    //print_r($datosTabla); die(); 
    $pdf->SetFont("times", "", 9);
    $Suma_debitos = 0;
    $Suma_creditos = 0;
    foreach ($datosTabla as $datos){
        if (is_array($datos)){
            foreach($datos as $dato){
                if(is_array($dato)){
                    if (floatval($dato['Saldo_Anterior']) == 0){
                        $dato['Saldo_Anterior'] = '';
                    } else {
                        $Saldo_anterior = (float)$dato['Saldo_Anterior'];
                        $dato['Saldo_Anterior'] = number_format($Saldo_anterior,2,'.', ',');
                    }
                    if (floatval($dato['Debitos']) == 0){
                        $dato['Debitos'] = '';
                    } else {
                        $debito = (float)$dato['Saldo_Anterior'];
                        $Suma_debitos += $debito;
                        $dato['Debitos'] = number_format($debito,2,'.', ',');
                    }
                    if (floatval($dato['Creditos']) == 0){
                        $dato['Creditos'] = '';
                    } else {
                        $credito = (float)$dato['Creditos'];
                        $Suma_creditos += $credito;
                        $dato['Creditos'] = number_format($credito,2,'.', ',');
                    }
                    if (floatval($dato['Saldo_Total']) == 0){
                        $dato['Saldo_Total'] = '';
                    } else {
                        $Saldo_total = (float)$dato['Saldo_Total'];
                        $dato['Saldo_Total'] = number_format($Saldo_total,2,'.', ',');
                    }
                    $row_datos = [$dato['Codigo'], $dato['Cuenta'], $dato['Saldo_Anterior'], $dato['Debitos'], $dato['Creditos'], $dato['Saldo_Total']];
                    $param['TC'] = $dato['TC'];
                    $param['DG'] = $dato['DG']; 
                    $param['sum'] = false;
                    $pdf->Row_Body($row_datos, $param);
                    $y = $pdf->GetY();
                    $pdf->PageCheckBreak($y);
                }
            }
        }
    }
    //al final agregamos las sumatorias de creditos y debitos
    $Creditos=number_format($Suma_creditos, 2, '.', ',');
    $Debitos=number_format($Suma_debitos, 2, '.', ',');
    $array=['', '', '', $Debitos, $Creditos, ''];
    $param['sum'] = true;
    $pdf->Row_Body($array, $param);
    $pdf->Output('Balance.pdf'. 'I');
}

?>