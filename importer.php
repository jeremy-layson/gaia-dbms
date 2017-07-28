<?php 
require_once('sql.php');

ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 0);
set_time_limit(0);
//  Include PHPExcel_IOFactory
include '/Classes/PHPExcel/IOFactory.php';

$inputFileName = 'data.xlsx';

//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

//15 is surveyData
$worksheet = $objPHPExcel->setActiveSheetIndex(15);

$worksheetTitle     = $worksheet->getTitle();
$highestRow = 831;

$columns = array('A', 'C', 'D', 'E', 'AG', 'AH', 'AI', 'AK', 'BP', 'BQ', 'BS', 'BK', 'BL', 'BM', 'BN', 'FQ', 'JJ');

$all = [];
$showHidden = true;
for ($i=5; $i <=$highestRow; $i++) {
    if ($worksheet->getRowDimension($i)->getVisible() || $showHidden ) {
        //get data
        $row = [];
        foreach ($columns as $value) {
            $cellValue = $worksheet->getCell($value . $i)->getValue();
            //get only isf or legal
            if ($value == 'A') {
                if (strpos(strtoupper($cellValue), 'ISF') !== false) {
                    $data = 'ISF';
                } else {
                    $data = 'LEGAL';
                }
            } else {
                $data = $cellValue;
            }
            
            $row[] = $data;

            //add boq
            if ($value == 'BQ') {
                $row[] = 0;
                $row[] = 0;
            }
        }

        $query = "INSERT INTO survey VALUES(NULL, " . "'" . implode("', '", $row) . "')";
        mysqli_query($link, $query);
        $all[] = $row;
    }

}