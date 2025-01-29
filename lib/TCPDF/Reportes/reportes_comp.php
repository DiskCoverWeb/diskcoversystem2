<?php
require('tcpdf_include.php');
require('../../funciones/numeros_en_letras.php');

class PDFC extends TCPDF{
    public $datos_cabecera;
    function url_logo($logoName=false)
    {
        $logo = $_SESSION['INGRESO']['Logo_Tipo'];
        if($logoName){ 
            $logo = $logoName;
        }
        $src_jpg = dirname(__DIR__, 3).'/img/logotipos/'.$logo.'.jpg';
        $src_gif = dirname(__DIR__, 3).'/img/logotipos/'.$logo.'.gif';
        $src_png = dirname(__DIR__, 3).'/img/logotipos/'.$logo.'.png';

        if(@getimagesize($src_jpg)){
            return $src_jpg;
        } else if (@getimagesize($src_gif)){
            return $src_gif;
        } else if (@getimagesize($src_png)){
            return $src_png;
        } else { 
            return '.';
        }
    }
    function Header() {
        //Marco para el header
        $this->SetFont('Times', 'B', 30);
        if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social']){
            $this->Cell(180,  25, '', 1, 1,  'C', false);
        }
        else {
            $this->Cell(180, 20, '', 1, 1, 'C', false);
        }
        //Lado Izquiero (logo)
        $this->SetXY(x: 13, y: 3);
        $src = $this->url_logo();
        if ($src !== '.'){
            $this->Image($src, 20, 5, 35, 15);
        } else{
            $txt = '';
            $this->Write(0, $txt, '', 0, 'C', true);
        }
        //Lado central (Informacion de cabecera)
        $this->SetFont('times','',size: 10);
        $this->Cell(0, 0, $_SESSION['INGRESO']['Razon_Social'], 0, 1, 'C', false);
        //si razon social es distinto de nombre comercial, imprimirlo
        if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social']){
            $this->SetFont('times', '', 9);
            $this->Cell(0, 0, $_SESSION['INGRESO']['Nombre_Comercial'], 0, 1, 'C', false);
        }
        $txt = 'R.U.C. :'.$_SESSION['INGRESO']['RUC'];
        $this->SetFont('times','',8);
        $this->Cell(0, 0, $txt, 0, 1, 'C', false);
        $this->SetFont('times', '', 6);
        $txt_dir = $_SESSION['INGRESO']['Direccion'].'-'.$_SESSION['INGRESO']['Telefono1'].' / '.$_SESSION['INGRESO']['FAX'];
        $this->Cell(0, 0, $txt_dir, 0, 1, 'C', false);
        $this->SetFont('times', 'B', 12);
        $pag = $this->getPage();
        if($pag !== 1){
            $txt_t = 'CONTINUACIÓN';
        }
        else{
            switch ($this->datos_cabecera['Tipo_Comprobante']){
                case 'CD':
                    $txt_t = 'COMPROBANTE DE DIARIO';
                    break;
                case 'CI':
                    $txt_t = 'COMPROBANTE DE INGRESO';
                    break;
                case 'CE':
                    $txt_t = 'COMPROBANTE DE EGRESO';
                    break;
                case 'ND':
                    $txt_t = 'NOTA DE DEBITO';
                    break;
                case 'NC':
                    $txt_t = 'NOTA DE CREDITO';
                    break;
                default:
                    $txt_t = '';
                    break;
            }
        }
        $this->Cell(0, 0, $txt_t, 0, 0, 'C', false);
        //lado derecho (Informacion del comprobante)
        $this->SetY(3);
        $Fecha1 = explode("-", $this->datos_cabecera['Fecha_ex']);
        $txt_no = 'No. '.substr($this->datos_cabecera['Fecha_ex'], 2, 2)."-".$this->datos_cabecera['Numero'];
        $txt_fecha = 'Fecha: '.$this->datos_cabecera['Fecha'];
        $txt_pag = 'Pagina No. '.$this->getPage();
        $this->SetX(x: 150);
        $this->Cell(40, 0, $txt_no, 1, 1, 'L', false);
        $this->ln(h: 1);
        $this->SetX(x: 150);
        $this->Cell(40, 0, $txt_fecha, 1, 1, 'L', false);
        $this->SetX(x: 150);
        $this->Cell(40, 0, $txt_pag, 1, 1, 'L', false);

        //Para cuando hay mas paginas
        $pag = $this->getPage();
        if($pag !== 1){
            $this->SetY(25);
            $this->MultiCell(0, 0, 'C O N T A B I L I Z A C I O N', 'LRT', 'C', false, 0);
        }
    } 

    function cab_table($array){
        $tbl = <<<EOD
            <table>
                <thead>
                    <tr> 
                        <th style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black;" align="center">{$array[0]}</th> 
                        <th style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black;" align="center" colspan="2">{$array[1]}</th> 
                        <th style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black;" align="center">{$array[2]}</th> 
                        <th style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black;" align="center">{$array[3]}</th> 
                        <th style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black;" align="center">{$array[4]}</th> 
                    </tr>
                </thead>
            </table> 
    EOD;
    $this->writeHTML($tbl, 0, false, false, false, 'C');
    }
    
    function Row($array){
        $tbl = <<<EOD
        <table cellpadding="2">
            <tbody>
                <tr>
                    <td style="border-left: 1px solid black; border-right: 1px solid black;">{$array[0]}</td>
                    <td style="border-left: 1px solid black; border-right: 1px solid black;" colspan="2">{$array[1]} </td>
                    <td align="right" style="border-left: 1px solid black; border-right: 1px solid black;">{$array[2]}</td>
                    <td align="right" style="border-left: 1px solid black; border-right: 1px solid black;">{$array[3]}</td>
                    <td align="right" style="border-left: 1px solid black; border-right: 1px solid black;">{$array[4]}</td>
                </tr>
            </tbody>
        </table> 
        EOD;
        $this->writeHTML($tbl, 0, false, false, false, 'L');
    }

    function Row_End($array){
        $tbl = <<<EOD
        <table cellpadding="2">
            <tbody>
                <tr>
                    <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">{$array[0]}</td>
                    <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;" colspan="2">{$array[1]}</td>
                    <td align="right" style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">{$array[2]}</td>
                    <td align="right" style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">{$array[3]}</td>
                    <td align="right" style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">{$array[4]}</td>
                </tr>
            </tbody>
        </table> 
        EOD;
        $this->writeHTML($tbl, 0, false, false, false, 'L');
    }

    function Row_Totales($array){
        $tbl = <<<EOD
        <table cellpadding="2">
            <tbody>
                <tr>
                    <td align="right" border="1" colspan="4">{$array[0]}</td>
                    <td align="right" border="1">{$array[1]}</td>
                    <td align="right" border="1">{$array[2]}</td>
                </tr>
            </tbody>
        </table> 
        EOD;
        $this->writeHTML($tbl, 0, false, false, false, 'L');
    }

    function Footer_1 ($usuario){
        $this->SetFont('times', '', 8);
        $tbl = <<<EOD
        <table border="1" cellpadding="3">
            <tbody>
                <tr>
                    <td align="center" rowspan="2">Cotizacion</td>
                    <td align="center">{$usuario}</td>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center"></td>
                </tr>
                <tr>
                    <td align="center">Elaborado por</td>
                    <td align="center">Contador</td>
                    <td align="center">Aprovado por</td>
                    <td align="center">Conforme</td>
                </tr>
            </tbody> 
        </table>
        EOD;
        $this->writeHTML($tbl, 0, false, false, false, 'C');
    }

    function PageCheckBreak(){
        $y = $this->GetY();
        $PageHeight = $this->getPageHeight();
        $limit = 40;
        $txt = strtoupper("continua en la siguiente pagina");
        if(($PageHeight - $y) < $limit){
            $tbl_limit = <<<EOD
                <table>
                    <tbody>
                        <tr>
                            <td style="border-top: 1px solid black;"></td>
                            <td style="border-top: 1px solid black;" colspan="2">{$txt}</td>
                            <td style="border-top: 1px solid black;"></td>
                            <td style="border-top: 1px solid black;"></td>
                            <td style="border-top: 1px solid black;"></td>
                        </tr> 
                    </tbody>
                </table>
            EOD;
            $this->writeHTML($tbl_limit, 0, false, false, false, 'L');
            $this->AddPage();
            if ($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social']){
                $this->SetY(30);
            } else {
                $this->SetY(25);
            }
            $this->SetFont('times', 'B', 11);
            $arr=array('CODIGO', 'CONCEPTO', 'PARCIAL M/E', 'DEBE', 'HABER');
            $this->cab_table($arr);
        }else{

        }
    }
}
function imprimirCD($stmt, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null,
        $stmt2_count=null,$stmt4_count=null,$stmt5_count=null,$stmt6_count=null)
{
    $pdf = new PDFC(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT,true, 'UTF-8', false);
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(false);
    $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    //Definimos datos para usar en cabecera
    $datos_cabecera['Tipo_Comprobante'] = $stmt[0]['TP'];
    $datos_cabecera['Numero'] = $stmt[0]['Numero'];
    $datos_cabecera['Fecha'] = strtoupper($stmt[0]['Fecha']->format('d/M/Y'));
    $datos_cabecera['Fecha_ex'] = $stmt[0]['Fecha']->format('Y-m-d');
    $pdf->datos_cabecera = $datos_cabecera;

    //Despues de la cabecera
    $pdf->AddPage();
    if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social']){
        $pdf->SetY(y: 25);
    }
    else {
        $pdf->SetY(20);
    }
    $pag = $pdf->getPage();
    if($pag === 1){
        $pdf->SetFont('times', '', 10);
        $txt_p = "Concepto de: ";
        $pdf->MultiCell(0, 10, '', 1,  'L', false, 0);
        $pdf->SetX(15);
        $pdf->MultiCell(21, 10, $txt_p, 0, 'L', false, 0);
        $pdf->SetFont('times', '', 10);
        $pdf->MultiCell(0, 10, $stmt[0]['Concepto'], 0, 'L', false, 1);
        $pdf->SetFont('times', 'B', 11);
        $pdf->MultiCell(0, 0,  'C O N T A B I L I Z A C I O N', 1, 'C', false, 1);
    }
    
    

    //Cabecera de la tabla
    $pdf->SetFont('times', 'B', 11);
    $val = array('CODIGO', 'CONCEPTO', 'PARCIAL M/E', 'DEBE', 'HABER');
    $pdf->cab_table($val);

    //Cuerpo de la tabla
    $sumcr = 0; 
    $sumdb = 0;
    if(count($stmt2)>0){
        $parc=''; $debe=''; $haber='';
        foreach($stmt2 as $key => $value){
            if($value['Parcial_ME']!=0 and $value['Parcial_ME']!='0.00'){
                $parc = number_format($value['Parcial_ME'], 2, '.', '');
            }
            if($value['Debe']!=0 and $value['Debe']!='0.00'){
                $sumdb = $sumdb + $value['Debe'];
                $debe = number_format($value['Debe'], 2, '.', '');
            }
            if($value['Haber']!=0 and $value['Haber']!='0.00'){
                $sumcr = $sumcr + $value['Haber'];
                $haber = number_format($value['Haber'], 2, '.', '');
            }
            $array = array($value['Cta'], $value['Cuenta'], $parc, $debe, $haber);
            $pdf->SetFont('times', '', 9);
            if($value === end($stmt2)){
                if($value['Detalle']!='.' && $value['Detalle']!=''){
                    $pdf->SetFont('helvetica', '', 8);
                    $arr=array('', $value['Detalle'], '', '', '');
                    $pdf->Row($array);
                    $pdf->Row_End($arr);
                }
                else{
                    $pdf->Row_End($array);
                }
            }
            else{
                $pdf->Row($array);
                if($value['Detalle']!='.' && $value['Detalle']!=''){
                    $pdf->SetFont('helvetica', '', 8);
                    $arr=array('', $value['Detalle'], '', '', '');
                    $pdf->Row($arr);
                }
            }
            $pdf->PageCheckBreak();
        }
        $sumdb = number_format($sumdb, 2, '.', '');
        $sumcr = number_format($sumcr, 2, '.', '');
        $array = array('TOTALES', $sumdb, $sumcr);
        $pdf->SetFont('times', 'B', 10);
        $pdf->Row_Totales($array);
    }
    $usuario = $stmt1[0]['Nombre_Completo'];
    $pdf->Footer_1($usuario);

    //$pdf->Write(0, 'hola', '', false, '', 0);



    //Salida
    $pdf->Output('Diario.pdf', 'I');
}

function imprimirCI($stmt, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $stmt8, $id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null,
$stmt2_count=null,$stmt4_count=null,$stmt5_count=null,$stmt6_count=null, $stmt8_count=null)
{
    $pdf = new PDFC(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT,true, 'UTF-8', false);
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(false);
    $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf = new PDFC(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT,true, 'UTF-8', false);
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(false);
    $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    //Definimos datos para usar en cabecera
    $datos_cabecera['Tipo_Comprobante'] = $stmt[0]['TP'];
    $datos_cabecera['Numero'] = $stmt[0]['Numero'];
    $datos_cabecera['Fecha'] = strtoupper($stmt[0]['Fecha']->format('d/M/Y'));
    $datos_cabecera['Fecha_ex'] = $stmt[0]['Fecha']->format('Y-m-d');
    $pdf->datos_cabecera = $datos_cabecera;

    //Despues de la cabecera
    $pdf->AddPage();
    if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social']){
        $pdf->SetY(y: 25);
    }
    else {
        $pdf->SetY(20);
    }
    $pag = $pdf->getPage();
    if($pag === 1){
        $cliente = $stmt1[0]['Cliente'];
        $ruc_ci = $stmt1[0]['CI_RUC'];
        $Monto_Total = $stmt[0]['Monto_Total'];
        $Monto_Total =NumerosEnLetras::convertir(number_format($Monto_Total, 2, '.', ''),'', true, 'Centavos');
        $y = $pdf->GetY();
        $txt_1 = 'Pagado a: ';
        $txt_2 = 'R.U.C. / C.I.: ';
        
        //Fila informacion de pago
        $pdf->SetFont('times', style: 'B', size: 10);
        $pdf->MultiCell(0, 0, $txt_1, 1, 'L', false, 0);
        $pdf->SetFont('times', '', size: 10);
        $pdf->SetXY(31,$y);
        $pdf->MultiCell(0, 0, $cliente, 0, 'L', false, 0);
        $pdf->SetXY(140, $y);
        $pdf->SetFont('times', style: 'B', size: 10);
        $pdf->MultiCell(0, 0, $txt_2, 0, 'L', false, 1);
        $pdf->SetXY( 162, $y);
        $pdf->SetFont('times', style: '', size: 10);
        $pdf->MultiCell(0, 0, $ruc_ci, 0, 'L', false, 1);
        //Informacion de cheque
        if($stmt8_count<>0){
            $y = $pdf->GetY();
            $pdf->MultiCell(0, 10,'', 1, 'L', false, 1);
            $pdf->SetY($y);
            $pdf->MultiCell(0, 0, 'La cantidad de: ', 0, 'L', false, 1);
            $pdf->SetXY(40, $y);
            $pdf->MultiCell(0, 10, $Monto_Total, 0, 'L', false, 1);
            if(count($stmt8)>0){
                $y = $pdf->GetY();
                $pdf->MultiCell(0, 30, '', 1, 'L', false, 1);
                $pdf->SetXY(22, $y);
                $pdf->MultiCell(0, 0, 'Cheques S/.', '0', 'L', false, 1);
                $pdf->SetXY(22, $y + 5);
                $pdf->MultiCell(0, 0, 'Efectivo S/.', 0, 'L', false, 1); 
                if($stmt8[0]['TC'] == 'BA'){
                    $pdf->Circle(20, $y + 2, 1, 0, 360, 'D');
                    $pdf->Circle(20, $y + 7, 1, 0, 360, 'D');
                    $pdf->SetXY(55, $y);
                    $Monto = number_format($stmt8[0]['monto'],2,'.',',');
                    $pdf->MultiCell(0, 10, $Monto, 0, 'L', false, 1);
                    $pdf->SetXY(80,$y);
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->MultiCell(0, 10, 'Banco: ', 0, 'L', false, 1);
                    $pdf->SetFont('times', '', 10);
                    $pdf->SetXY( 93, $y);
                    $pdf->MultiCell(0, 10, $stmt8[0]['Cuenta'], 0, 'L', false, 1);
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->SetXY( 140, $y);
                    $pdf->MultiCell(0, 10, 'Deposito No.', 0, 'L', false, 1);
                    $pdf->SetFont('times', '', 10);
                    $pdf->SetXY(160, $y);
                    $pdf->MultiCell(0, 10, $stmt8[0]['Cheq_Dep'], 0, 'L', false, 1);
                }
                if($stmt8[0]['TC'] == 'CJ'){
                    $pdf->Circle(20, $y + 2, 1, 0, 360, 'D');
                    $pdf->Circle(20, $y + 7, 1, 0, 360, 'D'); 
                    $pdf->SetXY(55, $y + 5);
                    $Monto = number_format($stmt8[0]['monto'],2,'.',',');
                    $pdf->MultiCell(0, 0, $Monto, 0, 'L', false, 1);
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->SetXY(80,  $y);
                    $pdf->MultiCell(0, 10, 'Caja:', 0, 'L', false, 1);
                    $pdf->SetXY(140, $y);
                    $pdf->MultiCell(0, 10, 'Deposito No.', 0, 'L', false, 0);
                    $pdf->SetFont('times', '', 10);
                    $pdf->SetXY(93, $y);
                    $pdf->MultiCell(0, 10, $stmt8[0]['Cuenta'], 0, 'L', false, 1);
                    $pdf->SetXY(160, $y);
                    $pdf->MultiCell(0, 10, $stmt8[0]['Cheq_Dep'], 0, 'L', false, 1);
                }
            }
        }

        //Cabecera Cheques/Efectivo
        

        
        
        $txt_p = "Concepto de: ";
        $pdf->MultiCell(0, 10, '', 1,  'L', false, 0);
        $pdf->SetX(15);
        $pdf->MultiCell(21, 10, $txt_p, 0, 'L', false, 0);
        $pdf->SetFont('times', '', 10);
        $pdf->MultiCell(0, 10, $stmt[0]['Concepto'], 0, 'L', false, 1);
        $pdf->SetFont('times', 'B', 11);
        $pdf->MultiCell(0, 0,  'C O N T A B I L I Z A C I O N', 1, 'C', false, 1);
    }
    
    

    //Cabecera de la tabla
    $pdf->SetFont('times', 'B', 11);
    $val = array('CODIGO', 'CONCEPTO', 'PARCIAL M/E', 'DEBE', 'HABER');
    $pdf->cab_table($val);

    //Cuerpo de la tabla
    $sumcr = 0; 
    $sumdb = 0;
    if(count($stmt2)>0){
        $parc=''; $debe=''; $haber='';
        foreach($stmt2 as $key => $value){
            if($value['Parcial_ME']!=0 and $value['Parcial_ME']!='0.00'){
                $parc = number_format($value['Parcial_ME'], 2, '.', '');
            }
            if($value['Debe']!=0 and $value['Debe']!='0.00'){
                $sumdb = $sumdb + $value['Debe'];
                $debe = number_format($value['Debe'], 2, '.', '');
            }
            if($value['Haber']!=0 and $value['Haber']!='0.00'){
                $sumcr = $sumcr + $value['Haber'];
                $haber = number_format($value['Haber'], 2, '.', '');
            }
            $array = array($value['Cta'], $value['Cuenta'], $parc, $debe, $haber);
            $pdf->SetFont('times', '', 9);
            if($value === end($stmt2)){
                if($value['Detalle']!='.' && $value['Detalle']!=''){
                    $pdf->SetFont('helvetica', '', 8);
                    $arr=array('', $value['Detalle'], '', '', '');
                    $pdf->Row($array);
                    $pdf->Row_End($arr);
                }
                else{
                    $pdf->Row_End($array);
                }
            }
            else{
                $pdf->Row($array);
                if($value['Detalle']!='.' && $value['Detalle']!=''){
                    $pdf->SetFont('helvetica', '', 8);
                    $arr=array('', $value['Detalle'], '', '', '');
                    $pdf->Row($arr);
                }
            }
            $pdf->PageCheckBreak();
        }
        $sumdb = number_format($sumdb, 2, '.', '');
        $sumcr = number_format($sumcr, 2, '.', '');
        $array = array('TOTALES', $sumdb, $sumcr);
        $pdf->SetFont('times', 'B', 10);
        $pdf->Row_Totales($array);
    }
    $usuario = $stmt1[0]['Nombre_Completo'];
    $pdf->Footer_1($usuario);
    
    //Salida
    $pdf->Output('Ingresos.pdf', 'I');
}

function imprimirCE($stmt, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $stmt8, $id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null,
$stmt2_count=null,$stmt4_count=null,$stmt5_count=null,$stmt6_count=null,$stmt8_count=null)
{
    $pdf = new PDFC(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT,true, 'UTF-8', false);
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(false);
    $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf = new PDFC(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT,true, 'UTF-8', false);
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(false);
    $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    //Definimos datos para usar en cabecera
    $datos_cabecera['Tipo_Comprobante'] = $stmt[0]['TP'];
    $datos_cabecera['Numero'] = $stmt[0]['Numero'];
    $datos_cabecera['Fecha_ex'] = $stmt[0]['Fecha']->format('Y-m-d');
    $datos_cabecera['Fecha'] = strtoupper($stmt[0]['Fecha']->format('d/M/Y'));
    $pdf->datos_cabecera = $datos_cabecera;

    //Despues de la cabecera
    $pdf->AddPage();
    if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social']){
        $pdf->SetY(y: 25);
    }
    else {
        $pdf->SetY(20);
    }
    $pag = $pdf->getPage();
    if($pag === 1){
        $cliente = $stmt1[0]['Cliente'];
        $ruc_ci = $stmt1[0]['CI_RUC'];
        $Monto_Total = $stmt[0]['Monto_Total'];
        $Monto_Total =NumerosEnLetras::convertir(number_format($Monto_Total, 2, '.', ''),'', true, 'Centavos');
        $y = $pdf->GetY();
        $txt_1 = 'Pagado a: ';
        $txt_2 = 'R.U.C. / C.I.: ';
        
        //Fila informacion de pago
        $pdf->SetFont('times', style: 'B', size: 10);
        $pdf->MultiCell(0, 0, $txt_1, 1, 'L', false, 0);
        $pdf->SetFont('times', '', size: 10);
        $pdf->SetXY(31,$y);
        $pdf->MultiCell(0, 0, $cliente, 0, 'L', false, 0);
        $pdf->SetXY(140, $y);
        $pdf->SetFont('times', style: 'B', size: 10);
        $pdf->MultiCell(0, 0, $txt_2, 0, 'L', false, 1);
        $pdf->SetXY( 162, $y);
        $pdf->SetFont('times', style: '', size: 10);
        $pdf->MultiCell(0, 0, $ruc_ci, 0, 'L', false, 1);
        //Informacion de cheque
        if($stmt8_count<>0){
            $y = $pdf->GetY();
            $pdf->MultiCell(0, 10,'', 1, 'L', false, 1);
            $pdf->SetY($y);
            $pdf->MultiCell(0, 0, 'La cantidad de: ', 0, 'L', false, 1);
            $pdf->SetXY(40, $y);
            $pdf->MultiCell(0, 10, $Monto_Total, 0, 'L', false, 1);
            if(count($stmt8)>0){
                $y = $pdf->GetY();
                $pdf->MultiCell(0, 30, '', 1, 'L', false, 1);
                $pdf->SetXY(22, $y);
                $pdf->MultiCell(0, 0, 'Cheques S/.', '0', 'L', false, 1);
                $pdf->SetXY(22, $y + 5);
                $pdf->MultiCell(0, 0, 'Efectivo S/.', 0, 'L', false, 1); 
                if($stmt8[0]['TC'] == 'BA'){
                    $pdf->Circle(20, $y + 2, 1, 0, 360, 'D');
                    $pdf->Circle(20, $y + 7, 1, 0, 360, 'D');
                    $pdf->SetXY(55, $y);
                    $Monto = number_format($stmt8[0]['monto'],2,'.',',');
                    $pdf->MultiCell(0, 10, $Monto, 0, 'L', false, 1);
                    $pdf->SetXY(80,$y);
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->MultiCell(0, 10, 'Banco: ', 0, 'L', false, 1);
                    $pdf->SetFont('times', '', 10);
                    $pdf->SetXY( 93, $y);
                    $pdf->MultiCell(0, 10, $stmt8[0]['Cuenta'], 0, 'L', false, 1);
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->SetXY( 140, $y);
                    $pdf->MultiCell(0, 10, 'Cheque No.', 0, 'L', false, 1);
                    $pdf->SetFont('times', '', 10);
                    $pdf->SetXY(160, $y);
                    $pdf->MultiCell(0, 10, $stmt8[0]['Cheq_Dep'], 0, 'L', false, 1);
                }
                if($stmt8[0]['TC'] == 'CJ'){
                    $pdf->Circle(20, $y + 2, 1, 0, 360, 'D');
                    $pdf->Circle(20, $y + 7, 1, 0, 360, 'D'); 
                    $pdf->SetXY(55, $y + 5);
                    $Monto = number_format($stmt8[0]['monto'],2,'.',',');
                    $pdf->MultiCell(0, 0, $Monto, 0, 'L', false, 1);
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->SetXY(80,  $y);
                    $pdf->MultiCell(0, 10, 'Caja:', 0, 'L', false, 1);
                    $pdf->SetXY(140, $y);
                    $pdf->MultiCell(0, 10, 'Retiro No.', 0, 'L', false, 0);
                    $pdf->SetFont('times', '', 10);
                    $pdf->SetXY(93, $y);
                    $pdf->MultiCell(0, 10, $stmt8[0]['Cuenta'], 0, 'L', false, 1);
                    $pdf->SetXY(160, $y);
                    $pdf->MultiCell(0, 10, $stmt8[0]['Cheq_Dep'], 0, 'L', false, 1);
                }
            }
        }


        $pdf->SetFont('times', '', 10);
        $txt_p = "Concepto de: ";
        $pdf->MultiCell(0, 10, '', 1,  'L', false, 0);
        $pdf->SetX(15);
        $pdf->MultiCell(21, 10, $txt_p, 0, 'L', false, 0);
        $pdf->SetFont('times', '', 10);
        $pdf->MultiCell(0, 10, $stmt[0]['Concepto'], 0, 'L', false, 1);
        $pdf->SetFont('times', 'B', 11);
        $pdf->MultiCell(0, 0,  'C O N T A B I L I Z A C I O N', 1, 'C', false, 1);
    }
    
    

    //Cabecera de la tabla
    $pdf->SetFont('times', 'B', 11);
    $val = array('CODIGO', 'CONCEPTO', 'PARCIAL M/E', 'DEBE', 'HABER');
    $pdf->cab_table($val);

    //Cuerpo de la tabla
    $sumcr = 0; 
    $sumdb = 0;
    if(count($stmt2)>0){
        $parc=''; $debe=''; $haber='';
        foreach($stmt2 as $key => $value){
            if($value['Parcial_ME']!=0 and $value['Parcial_ME']!='0.00'){
                $parc = number_format($value['Parcial_ME'], 2, '.', '');
            }
            if($value['Debe']!=0 and $value['Debe']!='0.00'){
                $sumdb = $sumdb + $value['Debe'];
                $debe = number_format($value['Debe'], 2, '.', '');
            }
            if($value['Haber']!=0 and $value['Haber']!='0.00'){
                $sumcr = $sumcr + $value['Haber'];
                $haber = number_format($value['Haber'], 2, '.', '');
            }
            $array = array($value['Cta'], $value['Cuenta'], $parc, $debe, $haber);
            $pdf->SetFont('times', '', 9);
            if($value === end($stmt2)){
                if($value['Detalle']!='.' && $value['Detalle']!=''){
                    $pdf->SetFont('helvetica', '', 8);
                    $arr=array('', $value['Detalle'], '', '', '');
                    $pdf->Row($array);
                    $pdf->Row_End($arr);
                }
                else{
                    $pdf->Row_End($array);
                }
            }
            else{
                $pdf->Row($array);
                if($value['Detalle']!='.' && $value['Detalle']!=''){
                    $pdf->SetFont('helvetica', '', 8);
                    $arr=array('', $value['Detalle'], '', '', '');
                    $pdf->Row($arr);
                }
            }
            $pdf->PageCheckBreak();
        }
        $sumdb = number_format($sumdb, 2, '.', '');
        $sumcr = number_format($sumcr, 2, '.', '');
        $array = array('TOTALES', $sumdb, $sumcr);
        $pdf->SetFont('times', 'B', 10);
        $pdf->Row_Totales($array);
    }
    $usuario = $stmt1[0]['Nombre_Completo'];
    $pdf->Footer_1($usuario);
    
    $pdf->Output('Egresos.pdf', 'I');
}

function imprimirND($stmt, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null,
$stmt2_count=null,$stmt4_count=null,$stmt5_count=null,$stmt6_count=null)
{
    $pdf = new PDFC(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT,true, 'UTF-8', false);
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(false);
    $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



    //Definimos datos para usar en cabecera
    $datos_cabecera['Tipo_Comprobante'] = $stmt[0]['TP'];
    $datos_cabecera['Numero'] = $stmt[0]['Numero'];
    $datos_cabecera['Fecha'] = strtoupper($stmt[0]['Fecha']->format('d/M/Y'));
    $datos_cabecera['Fecha_ex'] = $stmt[0]['Fecha']->format('Y-m-d');
    $pdf->datos_cabecera = $datos_cabecera;

    $pdf = new PDFC(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT,true, 'UTF-8', false);
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(false);
    $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    //Definimos datos para usar en cabecera
    $datos_cabecera['Tipo_Comprobante'] = $stmt[0]['TP'];
    $datos_cabecera['Numero'] = $stmt[0]['Numero'];
    $datos_cabecera['Fecha'] = strtoupper($stmt[0]['Fecha']->format('d/M/Y'));
    $pdf->datos_cabecera = $datos_cabecera;

    //Despues de la cabecera
    $pdf->AddPage();
    if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social']){
        $pdf->SetY(y: 25);
    }
    else {
        $pdf->SetY(20);
    }
    $pag = $pdf->getPage();
    if($pag === 1){
        $pdf->SetFont('times', '', 10);
        $txt_p = "Concepto de: ";
        $pdf->MultiCell(0, 10, '', 1,  'L', false, 0);
        $pdf->SetX(15);
        $pdf->MultiCell(21, 10, $txt_p, 0, 'L', false, 0);
        $pdf->SetFont('times', '', 10);
        $pdf->MultiCell(0, 10, $stmt[0]['Concepto'], 0, 'L', false, 1);
        $pdf->SetFont('times', 'B', 11);
        $pdf->MultiCell(0, 0,  'C O N T A B I L I Z A C I O N', 1, 'C', false, 1);
    }
    
    

    //Cabecera de la tabla
    $pdf->SetFont('times', 'B', 11);
    $val = array('CODIGO', 'CONCEPTO', 'PARCIAL M/E', 'DEBE', 'HABER');
    $pdf->cab_table($val);

    //Cuerpo de la tabla
    $sumcr = 0; 
    $sumdb = 0;
    if(count($stmt2)>0){
        $parc=''; $debe=''; $haber='';
        foreach($stmt2 as $key => $value){
            if($value['Parcial_ME']!=0 and $value['Parcial_ME']!='0.00'){
                $parc = number_format($value['Parcial_ME'], 2, '.', '');
            }
            if($value['Debe']!=0 and $value['Debe']!='0.00'){
                $sumdb = $sumdb + $value['Debe'];
                $debe = number_format($value['Debe'], 2, '.', '');
            }
            if($value['Haber']!=0 and $value['Haber']!='0.00'){
                $sumcr = $sumcr + $value['Haber'];
                $haber = number_format($value['Haber'], 2, '.', '');
            }
            $array = array($value['Cta'], $value['Cuenta'], $parc, $debe, $haber);
            $pdf->SetFont('times', '', 9);
            if($value === end($stmt2)){
                if($value['Detalle']!='.' && $value['Detalle']!=''){
                    $pdf->SetFont('helvetica', '', 8);
                    $arr=array('', $value['Detalle'], '', '', '');
                    $pdf->Row($array);
                    $pdf->Row_End($arr);
                }
                else{
                    $pdf->Row_End($array);
                }
            }
            else{
                $pdf->Row($array);
                if($value['Detalle']!='.' && $value['Detalle']!=''){
                    $pdf->SetFont('helvetica', '', 8);
                    $arr=array('', $value['Detalle'], '', '', '');
                    $pdf->Row($arr);
                }
            }
            $pdf->PageCheckBreak();
        }
        $sumdb = number_format($sumdb, 2, '.', '');
        $sumcr = number_format($sumcr, 2, '.', '');
        $array = array('TOTALES', $sumdb, $sumcr);
        $pdf->SetFont('times', 'B', 10);
        $pdf->Row_Totales($array);
    }
    $usuario = $stmt1[0]['Nombre_Completo'];
    $pdf->Footer_1($usuario);

    $pdf->Output('NotaDebito.pdf', 'I');
}

function imprimirNC($stmt, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null,
$stmt2_count=null,$stmt4_count=null,$stmt5_count=null,$stmt6_count=null)
{
    $pdf = new PDFC(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT,true, 'UTF-8', false);
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(false);
    $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



    //Definimos datos para usar en cabecera
    $datos_cabecera['Tipo_Comprobante'] = $stmt[0]['TP'];
    $datos_cabecera['Numero'] = $stmt[0]['Numero'];
    $datos_cabecera['Fecha'] = strtoupper($stmt[0]['Fecha']->format('d/M/Y'));
    $datos_cabecera['Fecha_ex'] = $stmt[0]['Fecha']->format('Y-m-d');
    $pdf->datos_cabecera = $datos_cabecera;

    $pdf = new PDFC(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT,true, 'UTF-8', false);
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(false);
    $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    //Definimos datos para usar en cabecera
    $datos_cabecera['Tipo_Comprobante'] = $stmt[0]['TP'];
    $datos_cabecera['Numero'] = $stmt[0]['Numero'];
    $datos_cabecera['Fecha'] = strtoupper($stmt[0]['Fecha']->format('d/M/Y'));
    $pdf->datos_cabecera = $datos_cabecera;

    //Despues de la cabecera
    $pdf->AddPage();
    if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social']){
        $pdf->SetY(y: 25);
    }
    else {
        $pdf->SetY(20);
    }
    $pag = $pdf->getPage();
    if($pag === 1){
        $pdf->SetFont('times', '', 10);
        $txt_p = "Concepto de: ";
        $pdf->MultiCell(0, 10, '', 1,  'L', false, 0);
        $pdf->SetX(15);
        $pdf->MultiCell(21, 10, $txt_p, 0, 'L', false, 0);
        $pdf->SetFont('times', '', 10);
        $pdf->MultiCell(0, 10, $stmt[0]['Concepto'], 0, 'L', false, 1);
        $pdf->SetFont('times', 'B', 11);
        $pdf->MultiCell(0, 0,  'C O N T A B I L I Z A C I O N', 1, 'C', false, 1);
    }
    
    

    //Cabecera de la tabla
    $pdf->SetFont('times', 'B', 11);
    $val = array('CODIGO', 'CONCEPTO', 'PARCIAL M/E', 'DEBE', 'HABER');
    $pdf->cab_table($val);

    //Cuerpo de la tabla
    $sumcr = 0; 
    $sumdb = 0;
    if(count($stmt2)>0){
        $parc=''; $debe=''; $haber='';
        foreach($stmt2 as $key => $value){
            if($value['Parcial_ME']!=0 and $value['Parcial_ME']!='0.00'){
                $parc = number_format($value['Parcial_ME'], 2, '.', '');
            }
            if($value['Debe']!=0 and $value['Debe']!='0.00'){
                $sumdb = $sumdb + $value['Debe'];
                $debe = number_format($value['Debe'], 2, '.', '');
            }
            if($value['Haber']!=0 and $value['Haber']!='0.00'){
                $sumcr = $sumcr + $value['Haber'];
                $haber = number_format($value['Haber'], 2, '.', '');
            }
            $array = array($value['Cta'], $value['Cuenta'], $parc, $debe, $haber);
            $pdf->SetFont('times', '', 9);
            if($value === end($stmt2)){
                if($value['Detalle']!='.' && $value['Detalle']!=''){
                    $pdf->SetFont('helvetica', '', 8);
                    $arr=array('', $value['Detalle'], '', '', '');
                    $pdf->Row($array);
                    $pdf->Row_End($arr);
                }
                else{
                    $pdf->Row_End($array);
                }
            }
            else{
                $pdf->Row($array);
                if($value['Detalle']!='.' && $value['Detalle']!=''){
                    $pdf->SetFont('helvetica', '', 8);
                    $arr=array('', $value['Detalle'], '', '', '');
                    $pdf->Row($arr);
                }
            }
            $pdf->PageCheckBreak();
        }
        $sumdb = number_format($sumdb, 2, '.', '');
        $sumcr = number_format($sumcr, 2, '.', '');
        $array = array('TOTALES', $sumdb, $sumcr);
        $pdf->SetFont('times', 'B', 10);
        $pdf->Row_Totales($array);
    }
    $usuario = $stmt1[0]['Nombre_Completo'];
    $pdf->Footer_1($usuario);

    $pdf->Output('NotaCredito.pdf', 'I');
}
?>