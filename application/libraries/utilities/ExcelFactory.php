<?php
require_once __DIR__ . '/../Spout/Autoloader/autoload.php';

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\WriterInterface;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\Style\Color;

class ExcelFactory
{
  private static $ci;

  public function __construct()
  {
    self::_initStaticMembers();
    self::$ci->load->model('cumulative_report_log');
  }

  protected static function _initStaticMembers()
  {
    if (empty(self::$ci)) {
      self::$ci =& get_instance();
    }
  }

  /**
   * @throws Exception
   */
  public static function createExcel($sqlQueryString, array $bindings=[], array $header=[], $reportName='export')
  {
    self::_initStaticMembers();
    try {
      $headerCols = $headerColsTitles = [];
      if (!empty($header)) {
        $headerCols = array_keys($header);
        $customProjection = implode(', ', $headerCols);
        $sqlQueryString = 'SELECT ' . $customProjection . ' FROM (' . $sqlQueryString . ') subQ';
        $headerColsTitles = array_values($header);
        log_message('debug', 'Excel factory query with custom header');
      }
      $sqlStmt = self::$ci->db->query($sqlQueryString, $bindings);
      if (!$sqlStmt) {
        throw new Exception('Failed to execute query string! Invalid data report');
      }
      // var_dump('<pre>',$sqlQueryString);exit;
      if(!$sqlStmt->num_rows() > 0){
        //var_dump('<pre>',print_r($sqlStmt->result_array(), true));
        $Message = $reportName.' excel report is empty, Please try again';
        print '<script type="text/javascript">alert("' . $Message . '");</script>';exit;
      }
      // create writer
      $defaultStyle = (new StyleBuilder())
        ->setFontName('Arial')
        ->setFontSize(11)
        ->build();
      
      $writer = WriterFactory::create(Type::XLSX);
      $writer->setDefaultRowStyle($defaultStyle);
      $writer->openToBrowser($reportName.'.xlsx');
      $sheet = $writer->getCurrentSheet();
      // $sheet->setName($reportName);
      log_message('debug', 'excel factory supplied query ==> ' . $sqlQueryString);
      // set header
      $headerRow = $sqlStmt->unbuffered_row('array') ?: [];
      self::setSheetHeader($writer, $headerRow, $headerColsTitles);
      while(($row = $sqlStmt->unbuffered_row('array'))) {
        $writer->addRow(array_values($row));
      }
      $writer->close();
    } catch (Exception $e) {
      throw $e;
    }
  }

  public static function dumpExcelInDirectory($sqlQueryString, array $bindings=[], array $header=[], $reportName='export', $expiring_date = (28 * 24*60*60))
  {
    self::_initStaticMembers();
    try {
      $headerCols = $headerColsTitles = [];
      if (!empty($header)) {
        $headerCols = array_keys($header);
        $customProjection = implode(', ', $headerCols);
        $sqlQueryString = 'SELECT ' . $customProjection . ' FROM (' . $sqlQueryString . ') subQ';
        $headerColsTitles = array_values($header);
        log_message('debug', 'Excel factory query with custom header');
      }
      $sqlStmt = self::$ci->db->query($sqlQueryString, $bindings);
      if (!$sqlStmt) {
        throw new Exception('Failed to execute query string! Invalid data report');
      }
      // create writer
      $defaultStyle = (new StyleBuilder())
        ->setFontName('Arial')
        ->setFontSize(11)
        ->build();
      
      $resolved_file_path = REPORTS_TEMP_FOLDER.$reportName.'.xlsx';
      log_message('INFO', 'REPORTS_TEMP_FOLDER Dry ==> ' .  $resolved_file_path);
      $writer = WriterFactory::create(Type::XLSX);
      $writer->setDefaultRowStyle($defaultStyle);
      $writer->openToFile($resolved_file_path);
      $sheet = $writer->getCurrentSheet();
      // $sheet->setName($reportName);
      log_message('debug', 'excel factory supplied query ==> ' . $sqlQueryString);
      // set header
      $headerRow = $sqlStmt->unbuffered_row('array') ?: [];
      self::setSheetHeader($writer, $headerRow, $headerColsTitles);
      while(($row = $sqlStmt->unbuffered_row('array'))) {
        $writer->addRow(array_values($row));
      }

      $entries = array(
        "file_path" => $resolved_file_path,
        "expires_at" => strtotime(date("d-m-Y H:i", time() + $expiring_date)),
    );
      self::$ci->cumulative_report_log->addReportLog($entries);
      $writer->close();
      return $resolved_file_path;
    } catch (Exception $e) {
      throw $e;
    }
  }
  
  private static function setSheetHeader(WriterInterface $writer, array $firstRecordRow, array $customHeader)
  {
    $headerStyle = (new StyleBuilder)
      ->setFontBold()
      ->setFontSize(11)
      ->build();    
    $row = []; $rowCols = $customHeader;
    if (!empty($firstRecordRow)) {
      $row = array_values($firstRecordRow);
      $rowCols = $customHeader ?: array_keys($firstRecordRow);
    }
    $writer->addRowWithStyle($rowCols, $headerStyle);
    $writer->addRow($row);
  }


  public static function readExcel($filePath){
    try{
      $reader = ReaderFactory::create(Type::XLSX);
      $reader->setShouldFormatDates(true);
      $reader->open($filePath);
   
      $i = 0; 
      $result;           
      foreach ($reader->getSheetIterator() as $sheet) {
        $column_headers = [];
          foreach ($sheet->getRowIterator() as $row) {
            $row = array_filter($row);
                    
              if ($i == 0) {
                $column_headers = array_values($row);
              }
              else{
                $result[] = array_combine($column_headers, $row);
              
              }    
              ++$i;
          }
        }
        unset($column_headers,$row);
              
              //echo "Total Rows : " . $i;              
        $reader->close();
              //print_r($result);
              // echo "Peak memory:", (memory_get_peak_usage(true) / 1024 / 1024), " MB";
        return $result;
    }catch(Exception $e){
      throw new Exception($e->getMessage());
    }
    
  }

  // this will read the XLSX file at the given path and return the number of rows for the file
  private static function getNumRows($xlsxPath) {
    $numRows = 0;

    $reader = ReaderFactory::create(Type::XLSX);
    $reader->open($xlsxPath);

    foreach ($reader->getSheetIterator() as $sheet) {
        foreach ($sheet->getRowIterator() as $row) {
            $numRows++;
        }
    }

    $reader->close();

    return $numRows;
  }




}
