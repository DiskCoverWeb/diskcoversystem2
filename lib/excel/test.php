<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;

function download_file($archivo, $downloadfilename = null) 
{

    if (file_exists($archivo)) {
        $downloadfilename = $downloadfilename !== null ? $downloadfilename : basename($archivo);
		
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $downloadfilename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($archivo));
		
        ob_clean();
        flush();
        readfile($archivo);
		
        exit;
    }

}

//require_once __DIR__ . '/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php';
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
//$sheet->setCellValue('A1', 'Hello World !');

// Set document properties
$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
    ->setLastModifiedBy('Maarten Balliauw')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Test result file');

// Add some data
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A1', 'Hello')
    ->setCellValue('B2', 'world!')
    ->setCellValue('C1', 'Hello')
    ->setCellValue('D2', 'world!');

// Miscellaneous glyphs, UTF-8
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A4', 'Miscellaneous glyphs')
    ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Simple');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

$writer = new Xlsx($spreadsheet);
$writer->save('hello world.xlsx');
//$writer->save('php://output');

/*$helper = new Sample();
if ($helper->isCli()) {
    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

    return;
}

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
    ->setLastModifiedBy('Maarten Balliauw')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Test result file');

// Add some data
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A1', 'Hello')
    ->setCellValue('B2', 'world!')
    ->setCellValue('C1', 'Hello')
    ->setCellValue('D2', 'world!');

// Miscellaneous glyphs, UTF-8
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A4', 'Miscellaneous glyphs')
    ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Simple');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xls)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="01simple.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xls');*/
download_file("hello world.xlsx", "hello world.xlsx");
?>