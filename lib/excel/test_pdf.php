<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require 'vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

//require_once __DIR__ . '/../../src/Bootstrap.php';

require_once __DIR__ . '\vendor\phpoffice\phpspreadsheet\src\Bootstrap.php';

/*$helper = new Sample();
if ($helper->isCli()) {
    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

    return;
}*/
function download_file($archivo, $downloadfilename = null) 
{

    if (file_exists($archivo)) {
        $downloadfilename = $downloadfilename !== null ? $downloadfilename : basename($archivo);
		
        
		
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment;filename="'.$downloadfilename.'"');
		header('Cache-Control: max-age=0');

        ob_clean();
        flush();
        readfile($archivo);
		
        exit;
    }

}

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
    ->setLastModifiedBy('Maarten Balliauw')
    ->setTitle('PDF Test Document')
    ->setSubject('PDF Test Document')
    ->setDescription('Test document for PDF, generated using PHP classes.')
    ->setKeywords('pdf php')
    ->setCategory('Test result file');

// Add some data
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A1', 'Hello')
    ->setCellValue('B2', 'world!')
    ->setCellValue('C1', 'Hello')
    ->setCellValue('D2', 'world!');

// Miscellaneous glyphs, UTF-8


// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Simple');
$spreadsheet->getActiveSheet()->setShowGridLines(false);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

IOFactory::registerWriter('Pdf', \PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf::class);

// Redirect output to a client’s web browser (PDF)
/*header('Content-Type: application/pdf');
header('Content-Disposition: attachment;filename="01simple.pdf"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Pdf');
//$writer->save('php://output');
$writer->save('01simple.pdf');*/

$writer = IOFactory::createWriter($spreadsheet, 'Pdf');

$writer->save('01simple.pdf');
download_file("01simple.pdf", "01simple.pdf");

exit;