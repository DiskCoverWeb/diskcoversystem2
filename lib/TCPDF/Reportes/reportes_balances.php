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
        //Header para Balance de comprobación
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
            $this->MultiCell(0, 0, $txt_fecha, 0, 'C', false, 0);
        }
        //Header para estad de resultado
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
            $this->Cabecera_Estado($Cabecera_tabla);
            $y = $this->GetY();
            $this->ln();
        }
    }
    function Cabecera_Estado($array_Datos){
        $this->SetFont('timesB', '', 12);
        $this->MultiCell(25, 10, $array_Datos[0], 0, 'L', false, 0);
        $this->MultiCell(95, 10, $array_Datos[1], 0, 'L', false, 0);
        $this->MultiCell(30, 10, $array_Datos[2], 0, 'L', false, 0);
        $this->MultiCell(25, 10, $array_Datos[3], 0, 'L', false, 0);
        $this->MultiCell(30, 10, $array_Datos[4], 0, 'L', false, 0);
    }   

    function Fila_Balance_Estado($datos){
        
        if($datos['DG'] == 'G'){
            $this->SetFont('timesB', '', 8);
        } else {
            $this->SetFont('times', '', 8);
        }

        //limpiamos los datos a no imprimir.
        $tipo['DG'] = $datos['DG'];
        $tipo['TC'] = $datos['TC'];
        unset($datos['DG'], $datos['TC']);
        
        $contador = 0;
        foreach ($datos as $elemento) {
            if($contador<2){
                $anchoExtra = ($contador==0) ? 6 : 4;
                $ancho[$contador] = $this->GetStringWidth($elemento) + $anchoExtra;
            } else {
                $ancho[$contador] = ($this->GetStringWidth($elemento)!=0) ? 20 : 8;
            }
            $contador++;
        }


        if($tipo['DG'] == 'D'){
            //subrayado y curva.
            if(in_array($tipo['TC'], ['C', 'P'])){
                $this->SetFont('times', 'IU', 8);
            }
            //curvilinea nada mas.
            else if(in_array($tipo['TC'], ['CJ', 'TJ', 'BA', 'CB', 'RP', 'RF', 'RB', 'RI', 'CC'])){
                $this->SetFont('times', 'I', 8);
            }
            else {
                $this->SetFont('times', '', 8);
            }
        }
        //Imprimir, volvemos contador a 0 para el foreach
        $contador = 0;
        foreach($datos as $elemento){
            switch($contador){
                case 0:
                    $this->MultiCell($ancho[$contador], 0, $elemento, 0, 'L', false, 0);
                    $this->MultiCell(10, 0, '', 0, 'L', false, 0);
                    break;
                case 1:
                    $this->MultiCell($ancho[$contador], 0, $elemento, 0, 'L', false, 0);
                    //bucle para rellenar el espacio
                    $x = $this->GetX();
                    if($x < 137){
                        $this->SetX(137);
                    }
                    break;
                default:
                    if($tipo['DG'] == 'G'){
                        $this->SetFont('timesB', '', 8);
                    } else {
                        $this->SetFont('times', '', 8);
                    }
                    $this->MultiCell($ancho[$contador], 0, $elemento, 0, 'R', false, 0);
                    break;
            }
            $contador++;
        }
        $this->ln();
    }

    function PageCheckBreak($y){
        $limit = 30;
        $PageHeight = $this->getPageHeight();
        if(($PageHeight - $y) < $limit){
            if ($this->datos_cabecera['tipo_balance'] == 1 || $this->datos_cabecera['tipo_balance'] == 2 || $this->datos_cabecera['tipo_balance']  == 4){
                $this->setLineWidth(0.2);
                $this->line(16, $y, 195, $y);
                $this->AddPage();
                $this->SetY(44);
                $Nom_Columnas = ["CODIGO", "CUENTA", "SALDO ANTERIOR", "DEBITOS", "CREDITOS", "SALDO ACTUAL"];
                $this->Cabecera_Balance_Estado($Nom_Columnas);
            } else if ($this->datos_cabecera['tipo_balance'] == 6 || $this->datos_cabecera['tipo_balance'] == 5) {
                $this->AddPage();
                $this->SetY(28);
            }
        }

    }

    function Cabecera_Balance_Estado($array){
        //Grosor de lineas
        $this->setLineWidth(0.132);
        $this->SetFont('timesB', '', 10);
        //medidas para la
        $w1 = 25.7;
        $w2 = 51.5;
        $w3 = 25.65;
        $w4 = 25.68;
        $w5 = 25.75;
        $w6 = 25.7;

        $height = 10;
        $x = 15;
        $y = ($this->GetY()) - 3;

        //Aqui imprimimos la cabecera
        $this->setLineWidth(0.132);
        $this->MultiCell($w1, $height, $array[0], 1, 'C', false, 0, $x, $y, true, 0, false, true, 10, 'M');
        $this->SetFont('timesB', '', 14);
        $this->MultiCell($w2, $height, $array[1],1, 'C', false, 0, '', '', true, 0, false, true, 10, 'M');
        $this->SetFont('timesB', '', 10);
        $this->MultiCell($w3, $height, $array[2], 1, 'C', false, 0, '', '', true, 0, false, true, 10, 'M');
        $this->MultiCell($w4, $height, $array[3], 1, 'C', false, 0, '', '', true, 0, false, true, 10, 'M');
        $this->MultiCell($w5, $height, $array[4], 1, 'C', false, 0, '', '', true, 0, false, true, 10, 'M');
        $this->MultiCell($w6, $height, $array[5], 1, 'C', false, 1, '', '', true, 0, false, true, 10, 'M');
    }

    function Fila_Balance_Comprobacion($array){
        //Limpiamos valores a no imprimir, si son condicionales usamos otro array.
        if(isset($array['DG'])){
            $condicional['DG'] = $array['DG'];
        }
        if(isset($array['TC'])){
            $condicional['TC'] = $array['TC'];
        }
        $condicional['sum']  = $array['sum'];

        unset($array['DG'], $array['TC'], $array['sum']);

        $this->SetFont("times", "", 9);
        $html = '<table><tbody><tr>';
        $contador = 0; // Nos servira tanto para el tamaño de la 2 columna como para alinear los parametrso
        //Vemos si es la ultima columna: la suma de debitos y creditos.
        if($condicional['sum'] == true){
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
            if($condicional['DG'] == 'G'){
                foreach($array as $data){
                    $contador++;
                    if ($contador == 2){
                        if($condicional['TC'] == 'CC'){
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
            else if ($condicional['DG'] == 'D'){
                foreach ($array as $data){
                    $contador++;
                    if ($contador == 2){
                        //Analizamos el TC aqui
                        if (in_array($condicional['TC'], ['TJ', 'G', 'CC', 'P', 'CB', 'RP', 'RF', 'RB', 'RI', 'I'])){
                            $html .= '<td colspan="2" style="border-left: 0.5px solid black; border-right: 0.5px solid black;"><i>'."{$data}".'</i></td>';
                        }
                        //Cursiva y Subrayado
                        elseif (in_array($condicional['TC'], ['CJ', 'BA', 'C'])){
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
    if(isset($datosTabla['datos'])){
        foreach($datosTabla['datos'] as $row){
            $pdf->Fila_Balance_Estado($row);
            $y = $pdf->GetY();
            $pdf->PageCheckBreak($y);
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
    $pdf->Multicell(0, 0, $_SESSION['INGRESO']['Gerente'], 0, 'L', false, 0);
    $pdf->SetX(125);
    //Puesto para nombre contador
    $pdf->Multicell(0, 0, $_SESSION['INGRESO']['Contador'], 0, 'L', false, 0);
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
    $pdf->Cabecera_Balance_Estado($Nom_Columnas);
    //print_r($datosTabla); die(); 
    $pdf->SetFont("times", "", 9);
    $Suma_debitos = 0;
    $Suma_creditos = 0;
    if(isset($datosTabla['datos'])){
        foreach($datosTabla['datos'] as $row){
            $Suma_creditos += (float)$row['Creditos'];
            $Suma_debitos += (float)$row['Saldo_Anterior'];
            //valor row, sirve para ubicar la fila de los totales.
            $row['sum'] = false;
            $pdf->Fila_Balance_Comprobacion($row);
            $y = $pdf->GetY();
            $pdf->PageCheckBreak($y);
        }
    }
    //al final agregamos las sumatorias de creditos y debitos
    $Creditos=number_format($Suma_creditos, 2, '.', ',');
    $Debitos=number_format($Suma_debitos, 2, '.', ',');
    $array=['', '', '', $Debitos, $Creditos, ''];
    $array['sum'] = true;
    $pdf->Fila_Balance_Comprobacion($array);
    $pdf->Output('Balance de comprobación.pdf', 'I');
}

?>