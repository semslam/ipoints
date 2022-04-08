<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
class Excel extends CI_Controller {
    
    public function index()
    {       
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');
        
        $writer = new Xlsx($spreadsheet);
 
        $filename = 'name-of-the-generated-file.xlsx';
 
        $writer->save($filename); // will create and save the file in the root of the project
 
    }
 
    public function download()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');
        
        $writer = new Xlsx($spreadsheet);
 
        $filename = 'name-of-the-generated-file';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file 
 
    }
 
    public function ipoints_random_bytes()
    {
        try {
		$string = random_bytes(32);
		} catch (TypeError $e) {
			// Well, it's an integer, so this IS unexpected.
			die("An unexpected error has occurred"); 
		} catch (Error $e) {
			// This is also unexpected because 32 is a reasonable integer.
			die("An unexpected error has occurred");
		} catch (Exception $e) {
			// If you get this message, the CSPRNG failed hard.
			die("Could not generate a random string. Is our OS secure?");
		}

		var_dump(bin2hex($string));
 
    }
 
    public function ipoints_random_integers($x =0,$y=255)
    {
		try {
			$int = random_int($x, $y);
		} catch (TypeError $e) {
			// Well, it's an integer, so this IS unexpected.
			die("An unexpected error has occurred"); 
		} catch (Error $e) {
			// This is also unexpected because 0 and 255 are both reasonable integers.
			die("An unexpected error has occurred");
		} catch (Exception $e) {
			// If you get this message, the CSPRNG failed hard.
			die("Could not generate a random int. Is our OS secure?");
		}
		var_dump($int); 
    }
}