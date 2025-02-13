<?php
require('tcpdf_include.php');

class PDF_MC extends TCPDF
{
    public $fechaini;
    public $fechafin;
    public $titulo;
    public $salto_header_cuerpo;
    public $orientacion;

    function Header()
    {
        if(isset($_SESSION['INGRESO']['Logo_Tipo']))
        {
            $logo=$_SESSION['INGRESO']['Logo_Tipo'];
            $src = dirname(__DIR__,3).'/img/logotipos/'.$logo.'.jpg';
            if(!file_exists($src))
            {
                $src = dirname(__DIR__,3).'/img/logotipos'.$logo.'.gif';
                if(!file_exists($src))
                {
                    $src = dirname(__DIR__,3).'/img/logotipos/'.$logo.'.png';
                    if(!file_exists($src))
                    {
                        $logo="diskcover";
                        $src=dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif';
                    }
                }
            }
        }
        $this->Image($src, 10, 3, 35, 20);
        $this->SetFont('Times', 'B', 12);
        $this->SetXY(10, 10);
        $this->Cell(0, 3, $_SESSION['INGRESO']['Nombre_Comercial'], 0, 0, 'C');
        $this->SetFont('Times', 'I', 13);
        $this->ln(5);
        $this->Cell(0, 3, strtoupper($_SESSION['INGRESO']['noempr']), 0, 0, 'C');
        $this->ln(5);
        
        $this->SetFont('Times', 'I', 11);
        $this->Cell(0, 3, ucfirst(strtolower($_SESSION['INGRESO']['Direccion'].'Telefono: '.$_SESSION['INGRESO']['Telefono1'])), 0, 0, 'C');
        $this->ln(5);
        
        $this->SetFont('helvetica', 'b', 12);

        $this->Cell(0, 3, $this->titulo, 0, 0, 'C');
        if($this->fechaini != '' && $this->fechaini != 'null' && $this->fechafin != '' && $this->fechafin != ''){
            $this->SetFont('helvetica', 'b', 10);
            $this->ln(5);

            $this->Cell(0, 3, 'DESDE: '.$this->fechaini.'HASTA: '.$this->fechafin, 0, 0, 'C');
            $this->ln(10);
        }
        if($this->orientacion == 'P'){
            $this->Image(dirname(__DIR__,3).'/img/logotipos/diskcov2.gif', 182, 3, 20, 8);
            $this->ln(2);

            $this->SetFont('helvetica', 'b', 8);
            $this->SetFont(155, 5);
            $this->Cell(9, 2, 'Hora: ', 0, 0, 'L');
            $this->SetFont('helvetica', '', 8);
            $this->Cell(0, 2, date('h:i:s A'), 0, 0, 'L');
            $this->ln(2);
            $this->SetFont('helvetica', 'b', 8);
            $this->SetXY(155, 8);
            $this->Cell(17, 2, 'Pagina No. ', 0, 0, 'L');
            $this->SetFont('helvetica', '', 8);
            $this->Cell(0, 2, $this->PageNo(), 0, 0, 'L');
            $this->ln(2);
            $this->SetXY(155, 11);
            $this->SetFont('helvetica', 'b', 8);
            $this->Cell(10, 2, 'Fecha: ', 0, 0, 'L');
            $this->SetFont('helvetica', '', 8);
            $this->Cell(0, 2, date('Y-m-d'), 0, 0, 'L'); 
            $this->ln(2);
            $this->SetXY(155, 14);
            $this->SetFont('helvetica', 'b', 8);
            $this->Cell(12, 2, 'Usuario: ', 0, 0, 'L'); 
            $this->SetFont('helvetica', '', 8);
            $this->Cell(0, 2, $_SESSION['INGRESO']['Nombre_Completo'], 0, 0, 'L');
            $this->Line(20, 35, 210-20, 35);
            $this->Line(20, 36, 210-20, 36);
            $this->ln($this->salto_header_cuerpo);
        }
        else 
        {
            $this->Image(dirname(__DIR__,3).'/img/logotipos/diskcov2.gif', 482, 3, 20, 8);
            $this->ln(2);
            $this->SetFont('helvetica', 'b', 8);
            $this->SetXY(255, 5);
            $this->Cell(9, 2, 'Horas: ', 0, 0, 'L');
            $this->SetFont('helvetica', '', 8);
            $this->Cell(0, 2, date('h:i:s A'), 0, 0, 'L');
            $this->ln(2);
            $this->SetXY(255, 8);
            $this->SetFont('helvetica', 'b', 8);
            $this->Cell(17, 2, 'Pagina No.', 0, 0, 'L');
            $this->SetFont('helvetica', 'b', 8);
            $this->Cell(0, 2, $this->PageNo(), 0, 0, 'L');
            $this->ln(2);
            $this->SetXY(255, 11);
            $this->SetFont('helvetica', 'b', 8);
            $this->Cell(10, 2, 'Fecha: ', 0, 0, 'L');
            $this->SetFont('helvetica', '', 8);
            $this->Cell(0, 2, date("Y-m-d"), 0, 0, 'L');
            $this->ln(2);
            $this->SetXY(255, 14);
            $this->SetFont('helvetica', 'b', 8);
            $this->Cell(12, 2, 'Usuario: ', 0, 0, 'L');
            $this->SetFont('helvetica', '', 8);
            $this->Cell(0, 2, $_SESSION['INGRESO']['Nombre_Comercial'], 0, 0, 'L');
            $this->Line(20, 35, 300-20, 35);
            $this->Line(20, 36, 300-20, 36);
            $this->ln($this->salto_header_cuerpo);
        }
    }
}

class cabecera_pdf
{
    private $pdftable;
    private $conn;
    private $fechaini;
    private $fechafin;
    private $sizetable;

    function ___construct()
    {
        $this->pdftable = new PDF_MC;
        $this->conn = cone_ajax();
        $this->fechaini = '';
        $this->fechafin = '';
        $this->sizetable = '12';
    }

    function cabecera_reporte_MC($titulo, $tablaHTML, $contenido, $image, $fechaini, $fechafin, $sizetable, $mostrar, $sal_hea_body, $orientacion)
    {
        $this->pdftable->fechaini = $fechaini;
        $this->pdftable->fechafin = $fechafin;
        $this->pdftable->titulo = $titulo;
        $this->pdftable->salto_header_cuerpo = $sal_hea_body;
        $this->pdftable->orientacion = $orientacion;

        $estiloRow='';
        $this->pdftable->AddPage($orientacion);
        if($image){
            foreach($image as $key => $value){
                $this->pdftable->Image($value['url'], $value['x'], $value['y'], $value['width'], $value['height']);
                $this->pdftable->ln(5);
            }
        }
        if($contenido){
            foreach ($contenido as $key => $value){
                if(!isset($value['estilo'])){
                    $value['estilo']='';
                }
                if($value['tipo'] == 'texto' && $value['posicion'] == 'top-tabla')
                {
                    $siz = 11;
                    $separacion = 4;
                    if(isset($value['tamaño'])){$siz = $value['tamaño'];}
                    if(isset($value['separacion'])){$separacion = $value['separacion'];}
                    $this->pdftable->SetFont('helvetica', $value['estilo'], $siz);
                    $this->pdftable->MultiCell(0, 3, $value['valor']);
                    $this->pdftable->ln($separacion);
                }
                else if($value['tipo'] == 'titulo' && $value['posicion']=='top-tabla'){
                    $siz = 18;
                    $separacion = 4;
                    if(isset($value['tamaño'])){$siz = $value['tamaño'];}
                    if(isset($value['separacion'])){$separacion = $value['separacion'];}
                    $this->pdftable->SetFont('helvetica', '', $siz);
                    $this->pdftable->Cell(0, 3, $value['valor'], 0, 0, 'C');
                    $this->pdftable->ln($separacion);
                }
            }
        }
        $this->pdftable->SetFont('helvetica', '', $sizetable);
        foreach ($tablaHTML as $key => $value){
            if(isset($value['newpag']) && $value['newpag'] == 1 && $key!=0){
                $this->pdftable->AddPage($orientacion);
            }
            $tama = 7;
            $esti = '';
            if (isset($value['estilo']) && $value['estilo']!=''){
                $esti = $value['estilo'];
            } (isset($value['size']) && $value['size']!=''){
                $tama = $value['size'];
            }
            $this->pdftable->SetFont('helvetica', $esti, $tama);
            $estiloRow = $esti;

            if(isset($value['borde']) && $value['borde'] != '0')
            {
                $borde=$value['borde'];
            } 
            else
            {
                $borde = '0'; 
            }
            $widths = $values['medidas'];
            $aligns = $values['alineado'];
            $arr = $values['datos'];
            if(!is_null($repetirCabecera) && is_array($repetirCabecera)){
                $repetirCabecera['row']['medidas'] = $value['medidas'];
                $repetirCabecera['row']['alineado'] = $value['alinead'];
            }

            if($download){
                if($mostrar==true)
                {
                    $this->pdftable->Output()
                }
            }

        }
    }
}

?>