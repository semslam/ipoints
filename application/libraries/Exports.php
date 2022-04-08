<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Exports{

    private $ci;
  
    public function __construct()
    {
      $this->ci =& get_instance();
      $this->ci->load->library('pdolib');
    }

     /**
   * @throws Exception
   */
  public function exportExcel($data, array $header=[], array $excludes=[])
  {
    $this->ci->load->library('excel');
    if (!($data instanceof CI_DB_mysqli_result)) {
      $data = $this->ci->db->query($data);
      if (!$data) {
        throw new Exception('Invalid data report data');
      }
    }
    // setup workbook doc
    $excelWorkbook = new PHPExcel();
    $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
    $cacheSettings = array('memoryCacheSize' => '512MB');
    PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

    if ($data instanceof CI_DB_mysqli_result) {
      $pageSize = 65000;
      $rowIdx = 1;
      $sheetNo = 0;
      $hasHeader = !empty($header);
      $headerKeys = $hasHeader ? array_flip($header) : [];
      // set sheet
      $excelWorkbook->setActiveSheetIndex(0);

      while(($row = $data->unbuffered_row('array'))) {
        if ($hasHeader) {
          $row = array_intersect_key($row, $headerKeys);
        }
        
        if ($rowIdx == 1) {
          $header = $this->getResultTableHeader($row);
          $this->addExcelRecordRow($excelWorkbook, $header, $rowIdx);
          $rowIdx++;
        }
        // check sheet 
        if ($rowIdx > $pageSize) {
          $sheetNo++;
          $excelWorkbook->addSheet(new PHPExcel_Worksheet($excelWorkbook, 'Worksheet ' . ($sheetNo+1)));
          $excelWorkbook->setActiveSheetIndex($sheetNo);
          $rowIdx = 1;
        }

        $this->addExcelRecordRow($excelWorkbook, $row, $rowIdx);
        $rowIdx++;
      }

      $writer = PHPExcel_IOFactory::createWriter($excelWorkbook, 'Excel5');
      $this->setExportExcelHeader();
      $writer->save('php://output');
    }
    exit();
  }

  private function setExportExcelHeader()
  {
      header('Content-Type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=export.xls"); 
  }

  private function addExcelRecordRow(PHPExcel &$excelWorkbook, array $row, $rowIndex)
  {
    $columnIdx = 0;

    foreach($row as $val) {
      $excelWorkbook->getActiveSheet()->setCellValueByColumnAndRow($columnIdx, $rowIndex, $val);
      $columnIdx++;
    }
  }

  private function getResultTableHeader(array $rs)
  {
    return array_keys($rs);
  }
}