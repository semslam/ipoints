<?php

class Imports extends MY_Model{

  public static function getTableName()
  {
    //wrong table name
      return 'ipin_generate';
  }

  public static function getPrimaryKey()
  {
      return SELF::DEFAULT_PRIMARY_KEY;
  }

    const EXCEL_PROCESS_LIMITE = 5;
  public static function readAndUpdateLargeExcelFile($filePath){
    try{
      $reader = ReaderFactory::create(Type::XLSX);

      $reader->setShouldFormatDates(true);
      $reader->open($filePath);
   
      $i = 0; 
      $result;       
      foreach ($reader->getSheetIterator() as $sheet) {
        $iterate = 0;
        $column_headers = [];
          
          foreach ($sheet->getRowIterator() as $row) {
            $row = array_filter($row);
                    
              if ($i == 0) {
                $column_headers = array_values($row);
              }
              else{
                $result[] = array_combine($column_headers, $row);
            
                ++$iterate;
                if($iterate == SELF::EXCEL_PROCESS_LIMITE){
                  $result;
                unset($result);
                $iterate = 0;
                }
              }    
              ++$i;
          }
         
          //break and array into a list and return on complete
          //the process should be pick the array one after the other and iterate on it
          //update in batch and clear
        }
        unset($column_headers,$row);
                     
        $reader->close();
          print_r($fragment_row);
          
        return $fragment_row;
    }catch(Exception $e){
      throw new Exception($e->getMessage());
    }
    
  }

  public static function requestIdGenerator(){
    $request = new static();
    $request->load->library('untils');
    $request_id= $request->untils->otpGenerator(7);
    $request->load->library("PinGenerator");
    $upper = strtoupper(str_replace(' ', '', $request_id));
    $iv = "-";
    $first = substr($upper, 0, 3).$iv;
    $last = $iv.substr($upper, -3);
    return $first.PinGenerator::generate(7, $first, $last).$last;

  }


  public static function loadExcel(array $recipients = [],$data){
    $loader = new static();
    $loader->load->model('WIPBulkTransferRequest');
    $data['recipients'] = $recipients;
    unset($recipients);
    //var_dump('<pre>',$data);
   return WIPBulkTransferRequest::load($data, $verbose = TRUE); 
  }
}