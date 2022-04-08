<?php

defined('BASEPATH') OR exit('No direct script access allowed');
 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Product_reports extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if(!is_cli()){
            $this->load->helpers('url');
            redirect(base_url());
        }

        $this->load->model('cummulative_insurance_report');
        $this->load->library('untils');
        $this->load->model('cumulative_report_log');
        $this->load->model('ReportSubscription');
        $this->load->library("pdolib");
    }

    public function sendReport() {
        $result = $this->cummulative_insurance_report->getCumulativePointReport();
        if (is_array($result) && !is_null($result) && !empty($result)) {
                
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Customer Phone number');
            $sheet->setCellValue('B1', 'Cash For Insurance(NGN)');
            $sheet->setCellValue('C1', 'iPoints equivalent');
            $sheet->setCellValue('D1', 'UIC Yearly Subscription Fee(NGN)');
            $sheet->setCellValue('E1', 'UIC Commission Per iPoint(NGN)');
            $sheet->setCellValue('F1', 'Total UIC Fee(NGN)');
            $sheet->setCellValue('G1', 'Net Customer Cash For Insurance');

            $count = 2;
            
            foreach ($result as $info) {
                
                $sheet->setCellValue('A'.$count, $info['mobile_number']);
                $sheet->setCellValue('B'.$count, $info['cash_ngn']);
                $sheet->setCellValue('C'.$count, $info['cash_point']);
                $sheet->setCellValue('D'.$count, $info['annual_sub']);
                $sheet->setCellValue('E'.$count, $info['comm_per_point']);
                $sheet->setCellValue('F'.$count, $info['total_fee']);
                $sheet->setCellValue('G'.$count, $info['net_cash_ngn']);

                $count++;
                
            }

            $writer = new Xlsx($spreadsheet);
            $path = 'assets/mail_attachments/';

            // if (!is_dir($path)) mkdir($path, 0777, true);
            
            $filename = date('YmdHis') .'-iSavings-report.xlsx';
            // $resolved_file_path = str_replace(__FILE__,$path.$filename,__FILE__);
            $resolved_file_path = REPORTS_TEMP_FOLDER.$filename;
            var_dump($resolved_file_path);
            $writer->save($resolved_file_path); // will create and save the file in the root of the project

            // "email" => array('eneh@celd.ng', 'ibrahimi@celd.ng'),
            $db = $this->pdolib->getPDO();
            $info['report_type'] = CUMULATIVE;
            $info['frequency'] = WEEKLY;
            $info['dispatcher_type'] = ALL;
            $reports = ReportSubscription::getReportSubscription($db,$info);
            $emails =  array_column($reports, 'email');
            //action name:: cummulative-isavings-report
            var_dump($emails);
            // $mailInfo = array(
            //     "email" => $emails,
            //     "email_subject" => 'CUMULATIVE iINSURANCE REPORT FOR LAST WEEK (UICI-WebMaster)',
            //     "message" => 'Hi All,<br/>Hope this mail meets you well. Please find the attached<br/><br/>Best Regards!',

            // );
            
            // $attach = array (
            //     "filename" => $resolved_file_path,
            //     "newname" => $filename,
            //     "mime" => 'application/vnd.ms-excel',
            //     "disposition" => 'attachment',

            // );

            $message_action = CUMULATIVE_ISAVINGS_REPORT;
            MessageQueue::messageCommitWithAttach($emails,MESSAGE_EMAIL,$message_action,[],$resolved_file_path);

            

            //$this->untils->defaultMesg($mailInfo, $attach);
            // $result = $this->untils->defaultMesgDir($mailInfo,$resolved_file_path);
            // var_dump($result);

            $entries = array(
                "file_path" => $path.$filename,
                "expires_at" => strtotime(date("d-m-Y H:i", time() + (28 * 24*60*60))),
            );

            $this->cumulative_report_log->addReportLog($entries);

            echo 'Report generated.';

        } else echo 'No report to send';

    }

    public function removeExpiredLogs() {

        $list = $this->cumulative_report_log->getExpiredLogs();

        if (is_array($list)) {
            foreach ($list as $row) {

                unlink(str_replace(__FILE__,$row['file_path'],__FILE__));
                $this->cumulative_report_log->removeReportLog($row['id']);
            }
            echo count($list) .' expired cumulative report file(s) removed';

        } else echo 'No expired cumulative report file';
    }

}
