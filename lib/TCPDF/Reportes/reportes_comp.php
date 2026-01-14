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
        $this-> setLineWidth(0.02646);
        $this->SetFont('helvetica', 'B', 30);
        if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social']){
            $this->MultiCell(180,  25, '', 1, 'C',  false, 1);
        }
        else {
            $this->MultiCell(180, 20, '', 1, 'C', false, 1);
        }
        //Lado Izquiero (logo)
        $this->SetXY(x: 13, y: 3);
        $src = $this->url_logo();
        if ($src !== '.'){
            $this->Image($src, 20, 5, 25, 15);
        } else{
            $txt = '';
            $this->Write(0, $txt, '', 0, 'C', true);
        }
        //Lado central (Informacion de cabecera)
        $this->SetFont('helvetica','B', 10);
        //$this->MultiCell(w: 0, h: 0, txt: '', border: 0, align: 'L', fill: false, ln: 1);
        $this->MultiCell(0, 0, $_SESSION['INGRESO']['Razon_Social'], 0, 'C', false, 1);
        //si razon social es distinto de nombre comercial, imprimirlo
        if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social']){
            $this->SetFont('helvetica', 'B', 9);
            $this->MultiCell(0, 0, $_SESSION['INGRESO']['Nombre_Comercial'], 0, 'C', false, 1);
        }
        $txt = 'R.U.C. :'.$_SESSION['INGRESO']['RUC'];
        $this->SetFont('helvetica','B',8);
        $this->MultiCell(0, 0, $txt, 0, 'C', false, 1);
        $this->SetFont('helvetica', 'B', 6);
        $txt_dir = 'Dir: '.$_SESSION['INGRESO']['Direccion'].' - Teléf: '.$_SESSION['INGRESO']['Telefono1'].' /FAX:'.$_SESSION['INGRESO']['FAX'];
        $this->Cell(0, 0, $txt_dir, 0, 1, 'C', false);
        $this->SetFont('times', 'B', 16);
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
        $txt_no = substr($this->datos_cabecera['Fecha_ex'], 2, 2)."-".$this->datos_cabecera['Numero'];
        $txt_fecha = $this->datos_cabecera['Fecha'];
        $txt_pag = $this->getPage();
        $this->SetFont('times', 'B', 14);
        $this->SetX(150+3);
        $this->MultiCell(40, 0, ' No. ', 1, 'L', false, 0);
        $this->SetFont('helvetica', '', 8);
        $this->SetX(167+3);
        $this->MultiCell(40,  0, $txt_no, 0, 'L', false, 1);
        $this->ln(4);
        $this->SetFont('times', 'B', 10);
        $this->SetX(150+3);
        $this->MultiCell(40, 0, ' Fecha: ', 1, 'L', false, 0);
        $this->SetFont('helvetica', '', 8);
        $this->SetX(164+3);
        $this->MultiCell(40, 0, $txt_fecha, 0, 'L', false, 1);
        $this->ln(2);
        $this->SetX(150+3);
        //esto Cambia en nota de credito y en nota de debito
        if ($this->datos_cabecera['Tipo_Comprobante'] == 'CI' || $this->datos_cabecera['Tipo_Comprobante'] == 'CE'){
            $this->SetFont('helvetica', '', 8);
            $this->MultiCell(40, 5, '        '.$this->datos_cabecera['Monto'], 1, 'L', false, 1, null, null, true, 0, 0, true, 0, 'M');
        } else {
            $this->SetFont('times', 'B', 10);
            $this->MultiCell(40, 0, ' Pagina No. ', 1, 'L', false, 0);
            $this->SetX(174+3);
            $this->SetFont('helvetica', '', 8);
            $this->MultiCell(40, 0, $txt_pag, 0, 'L', false, 0); 
        }
        

        //Para cuando hay mas paginas
        $pag = $this->getPage();
        if($pag !== 1){
            $this->SetFont('times', 'B', 11);
            $this->SetY(25);
            $this->MultiCell(0, 0, 'C O N T A B I L I Z A C I O N', 'LRT', 'C', false, 0);
        }
    } 

    function cab_table($array){
        $tbl = <<<EOD
            <table>
                <thead>
                    <tr> 
                        <th style="border:0.1px solid black;" align="center">{$array[0]}</th> 
                        <th style="border:0.1px solid black;" align="center" colspan="2">{$array[1]}</th> 
                        <th style="border:0.1px solid black;" align="center">{$array[2]}</th> 
                        <th style="border:0.1px solid black;" align="center">{$array[3]}</th> 
                        <th style="border:0.1px solid black;" align="center">{$array[4]}</th> 
                    </tr>
                </thead>
            </table> 
    EOD;
    $this->writeHTML($tbl, 0, false, false, false, 'C');
    }
    
    function Row($array){
        $this->SetFont('helvetica', '', 8);
        $tbl = <<<EOD
        <table cellpadding="1">
            <tbody>
                <tr>
                    <td style="border-left: 0.1px solid black; border-right: 0.1px solid black;">{$array[0]}</td>
                    <td style="border-left: 0.1px solid black; border-right: 0.1px solid black;" colspan="2">{$array[1]} </td>
                    <td align="right" style="border-left: 0.1px solid black; border-right: 0.1px solid black;">{$array[2]}</td>
                    <td align="right" style="border-left: 0.1px solid black; border-right: 0.1px solid black;">{$array[3]}</td>
                    <td align="right" style="border-left: 0.1px solid black; border-right: 0.1px solid black;">{$array[4]}</td>
                </tr>
            </tbody>
        </table> 
        EOD;
        $this->writeHTML($tbl, 0, false, false, false, 'L');
    }

    function Row_Totales($array){
        $this->SetFont('times', '', 8);
        $tbl = <<<EOD
        <table cellpadding="2">
            <tbody>
                <tr>
                    <td align="right" style="border: 0.1px solid black" colspan="4"><b>{$array[0]}</b></td>
                    <td align="right" style="border: 0.1px solid black">{$array[1]}</td>
                    <td align="right" style="border: 0.1px solid black">{$array[2]}</td>
                </tr>
            </tbody>
        </table> 
        EOD;
        $this->writeHTML($tbl, 0, false, false, false, 'L');
    }

    function Footer_1 ($usuario){
        $this->SetFont('times', '', 8);
        $tbl = <<<EOD
        <table cellpadding="3">
            <tbody>
                <tr>
                    <td style="font-size: 11px; text-align: center; border: 0.1px solid black" rowspan="2">COTIZACIÓN</td>
                    <td style="font-size: 11px; text-align: center; border: 0.1px solid black">{$usuario}</td>
                    <td style="font-size: 11px; text-align: center; border: 0.1px solid black"></td>
                    <td style="font-size: 11px; text-align: center; border: 0.1px solid black"></td>
                    <td style="font-size: 11px; text-align: center; border: 0.1px solid black"></td>
                </tr>
                <tr>
                    <td style="font-size: 11px; text-align: center; border: 0.1px solid black">Elaborado por</td>
                    <td style="font-size: 11px; text-align: center; border: 0.1px solid black">Contador</td>
                    <td style="font-size: 11px; text-align: center; border: 0.1px solid black">Aprovado por</td>
                    <td style="font-size: 11px; text-align: center; border: 0.1px solid black">Conforme</td>
                </tr>
            </tbody> 
        </table>
        EOD;
        $this->writeHTML($tbl, 0, false, false, false, 'C');
    }

    function PageCheckBreak(){
        $y = $this->GetY();
        $PageHeight = $this->getPageHeight();
        $limit = 45;
        $txt = strtoupper("continua en la siguiente pagina...");
        if(($PageHeight - $y) < $limit){
            $tbl_limit = <<<EOD
                <table>
                    <tbody>
                        <tr>
                            <td style="border-top: 0.1px solid black;"></td>
                            <td style="font-size: 8px; border-top: 0.1px solid black;" colspan="2"><i>{$txt}</i></td>
                            <td style="border-top: 0.1px solid black;"></td>
                            <td style="border-top: 0.1px solid black;"></td>
                            <td style="border-top: 0.1px solid black;"></td>
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
            $arr=array('CODIGO', 'C O N C E P T O', 'PARCIAL M/E', 'D E B E', 'H A B E R');
            $this->cab_table($arr);
        }else{

        }
    }

    function CheckPageEnd($CurrentY){
        $PageHeight = $this->getPageHeight();
        $MidPageHeight = ($this->getPageHeight())/2;
        if ($CurrentY < $MidPageHeight){
            if ($CurrentY < ($MidPageHeight - 45)){
                $array = ['', '', '', '', ''];
                $this->Row($array);
                $this->ln();
                $this->CheckPageEnd($this->GetY());
            } else {
                return; // retornemos la funcion
            }
        } elseif($CurrentY > $MidPageHeight) {
            if ($CurrentY < ($PageHeight - 45)){
                $array = ['', '', '', '', ''];
                $this->Row($array);
                $this->ln();
                $this->CheckPageEnd($this->GetY());
            } else {
                return; // retornar a la funcion
            }
        }
    }
}
function imprimirCD($stmt, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null,
        $stmt2_count=null,$stmt4_count=null,$stmt5_count=null,$stmt6_count=null)
{
    // print_r($stmt2);die();
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
    $datos_cabecera['Monto'] = number_format(0, 2, '.', ',');
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
    if($pag == 1){
        $pdf->setLineWidth(0.02646);
        $pdf->SetFont('times', 'B', 10);
        $txt_p = "Concepto de: ";
        $pdf->MultiCell(0, 10, '', 1,  'L', false, 0);
        $pdf->SetX(15);
        $pdf->MultiCell(23, 10, $txt_p, 0, 'L', false, 0);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->MultiCell(0, 10, $stmt[0]['Concepto'], 0, 'L', false, 1);
        $pdf->SetFont('times', 'B', 11);
        $pdf->MultiCell(0, 0,  'C O N T A B I L I Z A C I O N', 1, 'C', false, 1);
    }
    
    

    //Cabecera de la tabla
    $pdf->SetFont('times', 'B', 11);
    $val = array('CODIGO', 'C O N C E P T O', 'PARCIAL M/E', 'D E B E', 'H A B E R');
    $pdf->cab_table($val);

    //Cuerpo de la tabla
    $sumcr = 0; 
    $sumdb = 0;
    if(count($stmt2)>0){
        foreach($stmt2 as $key => $value){
            $array = array();
            $parc=''; $debe=''; $haber='';
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
            // print_r(end($stmt2));die();
            if($value === end($stmt2)){
                if($value['Detalle']!='.' && $value['Detalle']!=''){
                    $pdf->SetFont('helvetica', '', 8);
                    $arr=array('',' - '.$value['Detalle'], '', '', '');
                    $pdf->Row($array);
                    $pdf->Row($arr);
                }
                else{
                    $pdf->Row($array);
                }
            }
            else{
                $pdf->Row($array);
                if($value['Detalle']!='.' && $value['Detalle']!=''){
                    $arr=array('', ' - '.$value['Detalle'], '', '', '');
                    $pdf->Row($arr);
                }
            }
            $pdf->PageCheckBreak();
        }
        $sumdb = number_format($sumdb, 2, '.', '');
        $sumcr = number_format($sumcr, 2, '.', '');
        $array = array('T O T A L E S', $sumdb, $sumcr);
        $pdf->SetFont('times', '', 10);
        $pdf->CheckPageEnd($pdf->GetY());
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
    $Monto_Total = $stmt[0]['Monto_Total'];
    $datos_cabecera['Monto'] = number_format($Monto_Total, 2, '.', ',');
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
    if($pag == 1){
        $pdf->setLineWidth(0.02646);
        $cliente = $stmt1[0]['Cliente'];
        $ruc_ci = $stmt1[0]['CI_RUC'];
        $Monto_Total =NumerosEnLetras::convertir(number_format($Monto_Total, 2, '.', ''),'', true, 'Centavos');
        
        //Para la cabecera
        
        $y = $pdf->GetY();
        $txt_1 = 'Recibido de: ';
        $txt_2 = 'R.U.C. / C.I.: ';
        
        //Fila informacion de pago
        $pdf->SetFont('times', style: 'B', size: 10);
        $pdf->MultiCell(0, 0, $txt_1, 'LTR', 'L', false, 0);
        $pdf->SetFont('helvetica', '', size: 8);
        $pdf->SetXY(35,$y);
        $pdf->MultiCell(0, 0, $cliente, 0, 'L', false, 0);
        $pdf->SetXY(140, $y);
        $pdf->SetFont('times', style: 'B', size: 10);
        $pdf->MultiCell(0, 0, $txt_2, 0, 'L', false, 0);
        $pdf->SetXY( 162, $y);
        $pdf->SetFont('helvetica', style: '', size: 8);
        $pdf->MultiCell(0, 0, $ruc_ci, 0, 'L', false, 0);
        $pdf->ln();
        $pdf->SetY(($pdf->GetY())+1);
        //Informacion de cheque
        if($stmt8_count<>0){
            $y = $pdf->GetY();
            $pdf->MultiCell(0, 10,'', 1, 'L', false, 1);
            $pdf->SetY($y);
            $pdf->SetFont('times', 'B', 10);
            $pdf->MultiCell(0, 0, 'La cantidad de: ', 0, 'L', false, 1);
            $pdf->SetXY(40, $y);
            $pdf->SetFont('helvetica', '', 8);
            $pdf->MultiCell(0, 10, $Monto_Total, 0, 'L', false, 1);
            if(count($stmt8)>0){
                $y = $pdf->GetY();
                $pdf->SetFont('timesB', '', 10);
                $pdf->MultiCell(0, 30, '', 'TLR', 'L', false, 1);
                $pdf->SetXY(22, $y);
                $pdf->MultiCell(0, 0, 'Cheques S/.', '0', 'L', false, 1);
                $pdf->SetXY(22, $y + 5);
                $pdf->MultiCell(0, 0, 'Efectivo S/.', 0, 'L', false, 1); 
                if($stmt8[0]['TC'] == 'BA'){
                    $pdf->Circle(20, $y + 2, 1, 0, 360, 'D');
                    $pdf->Circle(20, $y + 7, 1, 0, 360, 'D');
                    $pdf->SetXY(55, $y);
                    $pdf->SetFont('helvetica', '', 8);
                    $Monto = number_format($stmt8[0]['monto'],2,'.',',');
                    $pdf->MultiCell(0, 10, $Monto, 0, 'L', false, 1);
                    $pdf->SetXY(80,$y);
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->MultiCell(0, 10, 'Banco: ', 0, 'L', false, 1);
                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->SetXY( 93, $y);
                    $pdf->MultiCell(0, 10, $stmt8[0]['Cuenta'], 0, 'L', false, 1);
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->SetXY( 140, $y);
                    $pdf->MultiCell(0, 10, 'Deposito No.', 0, 'L', false, 1);
                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->SetXY(160, $y);
                    $pdf->MultiCell(0, 10, $stmt8[0]['Cheq_Dep'], 0, 'L', false, 1);
                }
                if($stmt8[0]['TC'] == 'CJ'){
                    $pdf->Circle(20, $y + 2, 1, 0, 360, 'D');
                    $pdf->Circle(20, $y + 7, 1, 0, 360, 'D'); 
                    $pdf->SetXY(55, $y + 5);
                    $pdf->SetFont('helvetica', '', 8);
                    $Monto = number_format($stmt8[0]['monto'],2,'.',',');
                    $pdf->MultiCell(0, 0, $Monto, 0, 'L', false, 1);
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->SetXY(80,  $y);
                    $pdf->MultiCell(0, 10, 'Caja:', 0, 'L', false, 1);
                    $pdf->SetXY(140, $y);
                    $pdf->MultiCell(0, 10, 'Deposito No.', 0, 'L', false, 0);
                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->SetXY(93, $y);
                    $pdf->MultiCell(0, 10, $stmt8[0]['Cuenta'], 0, 'L', false, 1);
                    $pdf->SetXY(160, $y);
                    $pdf->MultiCell(0, 10, $stmt8[0]['Cheq_Dep'], 0, 'L', false, 1);
                }
            }
        }

        //Cabecera Cheques/Efectivo
        

        
        $pdf->SetFont('times', 'B', 10);
        $txt_p = "Concepto de: ";
        $pdf->MultiCell(0, 10, '', 1,  'L', false, 0);
        $pdf->SetX(15);
        $pdf->MultiCell(23, 10, $txt_p, 0, 'L', false, 0);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->MultiCell(0, 10, $stmt[0]['Concepto'], 0, 'L', false, 1);
        $pdf->SetFont('times', 'B', 11);
        $pdf->MultiCell(0, 0,  'C O N T A B I L I Z A C I O N', 1, 'C', false, 1);
    }
    
    

    //Cabecera de la tabla
    $pdf->SetFont('times', 'B', 11);
    $val = array('CODIGO', 'C O N C E P T O', 'PARCIAL M/E', 'D E B E', 'H A B E R');
    $pdf->cab_table($val);

    //Cuerpo de la tabla
    $sumcr = 0; 
    $sumdb = 0;
    if(count($stmt2)>0){
        foreach($stmt2 as $key => $value){
            $parc=''; $debe=''; $haber='';
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
                    $pdf->Row($arr);
                }
                else{
                    $pdf->Row($array);
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
        $array = array('T O T A L E S', $sumdb, $sumcr);
        $pdf->SetFont('times', '', 10);
        $pdf->CheckPageEnd($pdf->GetY());
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
    $Monto_Total = $stmt[0]['Monto_Total'];
    $datos_cabecera['Monto'] = number_format((float)$Monto_Total, 2, '.', ',');
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
    if($pag == 1){
        $pdf->setLineWidth(0.02646);
        $cliente = $stmt1[0]['Cliente'];
        $ruc_ci = $stmt1[0]['CI_RUC'];
        

        $Monto_Total =NumerosEnLetras::convertir(number_format($Monto_Total, 2, '.', ''),'', true, 'Centavos');
        $y = $pdf->GetY();
        $txt_1 = 'Pagado a: ';
        $txt_2 = 'R.U.C. / C.I.: ';
        
        //Fila informacion de pago
        $pdf->SetFont('times', style: 'B', size: 10);
        $pdf->MultiCell(0, 0, $txt_1, 'LTR', 'L', false, 0);
        $pdf->SetFont('helvetica', '', size: 8);
        $pdf->SetXY(31,$y);
        $pdf->MultiCell(0, 0, $cliente, 0, 'L', false, 0);
        $pdf->SetXY(140, $y);
        $pdf->SetFont('times', style: 'B', size: 10);
        $pdf->MultiCell(0, 0, $txt_2, 0, 'L', false, 0);
        $pdf->SetXY( 162, $y);
        $pdf->SetFont('helvetica', style: '', size: 8);
        $pdf->MultiCell(0, 0, $ruc_ci, 0, 'L', false, 0);
        //Informacion de cheque
        $pdf->ln();
        $pdf->SetY(($pdf->GetY())+1);
        if($stmt8_count<>0){
            $y = $pdf->GetY();
            $pdf->MultiCell(0, 10,'', 1, 'L', false, 1);
            $pdf->SetY($y);
            $pdf->SetFont('timesB', '', 10);
            $pdf->MultiCell(0, 0, 'La cantidad de: ', 0, 'L', false, 1);
            $pdf->SetXY(40, $y);
            $pdf->SetFont('helvetica', '', 8);
            $pdf->MultiCell(0, 10, $Monto_Total, 0, 'L', false, 1);
            if(count($stmt8)>0){
                $y = $pdf->GetY();
                $pdf->SetFont('timesB', '', 10);
                $pdf->MultiCell(0, 30, '', 'LTR', 'L', false, 1);
                $pdf->SetXY(22, $y);
                $pdf->MultiCell(0, 0, 'Cheques S/.', '0', 'L', false, 1);
                $pdf->SetXY(22, $y + 5);
                $pdf->MultiCell(0, 0, 'Efectivo S/.', 0, 'L', false, 1); 
                if($stmt8[0]['TC'] == 'BA'){
                    $pdf->Circle(20, $y + 2, 1, 0, 360, 'D');
                    $pdf->Circle(20, $y + 7, 1, 0, 360, 'D');
                    $pdf->SetXY(55, $y);
                    $pdf->SetFont('helvetica', '', 8);
                    $Monto = number_format($stmt8[0]['monto'],2,'.',',');
                    $pdf->MultiCell(0, 10, $Monto, 0, 'L', false, 1);
                    $pdf->SetXY(70,$y);
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->MultiCell(0, 10, 'Banco: ', 0, 'L', false, 1);
                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->SetXY( 83, $y);
                    $pdf->MultiCell(50, 10, $stmt8[0]['Cuenta'], 0, 'L', false, 1);
                    $pdf->SetFont('times', 'B', 10);
                    $pdf->SetXY( 140, $y);
                    $pdf->MultiCell(0, 10, 'Cheque No.', 0, 'L', false, 1);
                    $pdf->SetFont('helvetica', '', 8);
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
                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->SetXY(93, $y);
                    $pdf->MultiCell(0, 10, $stmt8[0]['Cuenta'], 0, 'L', false, 1);
                    $pdf->SetXY(160, $y);
                    $pdf->MultiCell(0, 10, $stmt8[0]['Cheq_Dep'], 0, 'L', false, 1);
                }
            }
        }


        $pdf->SetFont('times', 'B', 10);
        $txt_p = "Concepto de: ";
        $pdf->MultiCell(0, 10, '', 1,  'L', false, 0);
        $pdf->SetX(15);
        $pdf->MultiCell(23, 10, $txt_p, 0, 'L', false, 0);
        $pdf->SetFont('helvetica', '',8);
        $pdf->MultiCell(0, 10, $stmt[0]['Concepto'], 0, 'L', false, 1);
        $pdf->SetFont('times', 'B', 11);
        $pdf->MultiCell(0, 0,  'C O N T A B I L I Z A C I O N', 1, 'C', false, 1);
    }
    
    

    //Cabecera de la tabla
    $pdf->SetFont('times', 'B', 11);
    $val = array('CODIGO', 'C O N C E P T O', 'PARCIAL M/E', 'D E B E', 'H A B E R');
    $pdf->cab_table($val);

    //Cuerpo de la tabla
    $sumcr = 0; 
    $sumdb = 0;
    if(count($stmt2)>0){
        foreach($stmt2 as $key => $value){
            $parc=''; $debe=''; $haber='';
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
                    $pdf->Row($arr);
                }
                else{
                    $pdf->Row($array);
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
        $array = array('T O T A L E S', $sumdb, $sumcr);
        $pdf->SetFont('times', '', 10);
        $pdf->CheckPageEnd($pdf->GetY());
        $pdf->Row_Totales($array);
    }
    $usuario = $stmt1[0]['Nombre_Completo'];
    $y = $pdf->GetY();
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
    $datos_cabecera['Monto'] = number_format(0, 2, '.', ',');
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
    if($pag == 1){
        $pdf->setLineWidth(0.02646);
        $pdf->SetFont('times', 'B', 10);
        $txt_p = "Concepto de: ";
        $pdf->MultiCell(0, 10, '', 1,  'L', false, 0);
        $pdf->SetX(15);
        $pdf->MultiCell(23, 10, $txt_p, 0, 'L', false, 0);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->MultiCell(0, 10, $stmt[0]['Concepto'], 0, 'L', false, 1);
        $pdf->SetFont('times', 'B', 11);
        $pdf->MultiCell(0, 0,  'C O N T A B I L I Z A C I O N', 1, 'C', false, 1);
    }
    
    

    //Cabecera de la tabla
    $pdf->SetFont('times', 'B', 11);
    $val = array('CODIGO', 'C O N C E P T O', 'PARCIAL M/E', 'D E B E', 'H A B E R');
    $pdf->cab_table($val);

    //Cuerpo de la tabla
    $sumcr = 0; 
    $sumdb = 0;
    if(count($stmt2)>0){
        foreach($stmt2 as $key => $value){
            $parc=''; $debe=''; $haber='';
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
                    $pdf->Row($arr);
                }
                else{
                    $pdf->Row($array);
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
        $array = array('T O T A L E S', $sumdb, $sumcr);
        $pdf->SetFont('times', '', 10);
        $pdf->CheckPageEnd($pdf->GetY());
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
    $datos_cabecera['Monto'] = number_format(0, 2, '.', ',');
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
    if($pag == 1){
        $pdf->setLineWidth(0.02646);
        $pdf->SetFont('times', 'B', 10);
        $txt_p = "Concepto de: ";
        $pdf->MultiCell(0, 10, '', 1,  'L', false, 0);
        $pdf->SetX(15);
        $pdf->MultiCell(23, 10, $txt_p, 0, 'L', false, 0);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->MultiCell(0, 10, $stmt[0]['Concepto'], 0, 'L', false, 1);
        $pdf->SetFont('times', 'B', 11);
        $pdf->MultiCell(0, 0,  'C O N T A B I L I Z A C I O N', 1, 'C', false, 1);
    }
    
    

    //Cabecera de la tabla
    $pdf->SetFont('times', 'B', 11);
    $val = array('CODIGO', 'C O N C E P T O', 'PARCIAL M/E', 'D E B E', 'H A B E R');
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
                    $pdf->Row($arr);
                }
                else{
                    $pdf->Row($array);
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
        $array = array('T O T A L E S', $sumdb, $sumcr);
        $pdf->SetFont('times', '', 10);
        $pdf->CheckPageEnd($pdf->GetY());
        $pdf->Row_Totales($array);
    }
    $usuario = $stmt1[0]['Nombre_Completo'];
    $pdf->Footer_1($usuario);

    $pdf->Output('NotaCredito.pdf', 'I');
}
?>