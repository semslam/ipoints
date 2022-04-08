<?php 


class Test extends CI_Controller
{
    public function testcredit(){
        //$this->load->model('UserBalance');
        //var_dump(UserBalance::credit(10,5000,1));
        Wallet::validateCrossWalletSpending(1,9);
        var_dump(UserBalance::findAll());
    }

    public function testcredits(){
        $this->load->model('UserBalance');
        var_dump(UserBalance::credit(10,5000,1));
    }

    public function testfetchgroup(){
        var_dump(User::validateCrossUserTransfer(5,2));
    }

    public function testtransfer(){
        var_dump(Transaction::transfer(2,3,3,3,500,'',true));
    }

    public function testsub(){
        Transaction::startDbTransaction();
        var_dump(Transaction::paySubscription(20,1));
        Transaction::endDbTransaction(true);
    }

    public function testcredittransaction(){
        $this->load->model('Transaction');
        var_dump(Transaction::debit(5,2000,1,'','SKIBO',NULL,TRUE));
    }

    public function testingFunction(){
        $this->load->library("pdolib");
        $db = $this->pdolib->getPDO();
        $this->load->model('Wallet');
        $this->load->model('Setting_m');
        $result =  Wallet::getAllWallets($db);
        $tting =  Setting_m::getAlliPointCharges($db);
        var_dump($result);
        var_dump($setting);
    }

    public function loadwip(){
        $this->load->model('WIPBulkTransferRequest');
        WIPBulkTransferRequest::load([
            "request_id"=> "fbjbvbkd",
            "client_id"=> 1,
            "recipients"=> [
                [
                    "recipient_phone" => "2348094227050",
                    "qty" => 20,
                    "service_id" => 1
                ]
            ]
        ]);
    }

    public function testproductrelations(){
        $this->load->model('Product');
        $product = Product::findByPK(1);
        var_dump(($product->with('wallet'))->wallet);
    }

    public function smsSend(){
        $this->load->library("Sms");
        for ($x = 0; $x <= 10; $x++) {
            try{
                $this->sms->sendOTP('2348094227050', "Testing sms API by UiciInnovation");
            }catch(Exception $e){
                echo $e->getMessage();
            }
        } 
       
    }

    public function setMemcacheTest(){
        $aData = array(
            'name' => 'table',
            'color' => 'brown',
            'size' => array(
                'x' => 200,
                'y' => 120,
                'z' => 150,
            ),
            'strength' => 10,
        );
        $this->load->library("CacheMemcache");
        $oCache = new CacheMemcache();
        echo 'Initial data: <pre>'; // lets see what we have
        print_r($aData);
        echo '</pre>';
        if ($oCache->bEnabled) { // if Memcache enabled
            $oCache->setData('my_object', $aData); // saving data to cache server
            $oCache->setData('our_class_object', $oCache); // saving object of our class into cache server too
            echo 'Now we saved all in cache server, click <a href="index2.php">here</a> to check what we have in cache server';
        } else {
            echo 'Seems Memcache not installed, please install it to perform tests';
        }
    }

    public function getMemcacheTest(){
        $this->load->library('CacheMemcache');
        $oCache = new CacheMemcache();
        if ($oCache->bEnabled) { // if Memcache enabled
            $aMemData = $oCache->getData('my_object'); // getting data from cache server
            $aMemData2 = $oCache->getData('our_class_object'); // getting data from cache server about our class
            echo 'Data from cache server: <pre>'; // lets see what we have from cache server
            print_r($aMemData);
            echo '</pre>';
            echo 'Data from cache server of object of CacheMemcache class: <pre>';
            print_r($aMemData2);
            echo '</pre>';
            echo 'As you can see - all data read successfully, now lets remove data from cache server and check results, click <a href="index3.php">here</a> to continue';
        } else {
            echo 'Seems Memcache not installed, please install it to perform tests';
        }
    }

    public function delMemcacheTest(){
        $this->load->library('CacheMemcache');
        $oCache = new CacheMemcache();
        if ($oCache->bEnabled) { // if Memcache enabled
            $oCache->delData('my_object'); // removing data from cache server
            $oCache->delData('our_class_object'); // removing data from cache server
            $aMemData = $oCache->getData('my_object'); // lets try to get data again
            $aMemData2 = $oCache->getData('our_class_object');
            echo 'Data from cache server: <pre>'; // lets see what we have from cache server
            print_r($aMemData);
            echo '</pre>';
            echo 'Data from cache server of object of CacheMemcache class: <pre>';
            print_r($aMemData2);
            echo '</pre>';
            echo 'As you can see - all data successfully removed. Great !';
        } else {
            echo 'Seems Memcache not installed, please install it to perform tests';
        }
    }

    public function bulkTransferMessage(){
        $this->load->model('WIPBulkTransferRequest');
        log_message('INFO','Start_______________');
         $this->WIPBulkTransferRequest->msgProcess('new');
    }

    public function sendEmail(){
        $this->load->library("Untils");
        $info['email'] = 'ibrahimi@celd.ng';
        $info['email_subject'] = 'Testing UICI Emailer';
        $info['message'] = 'This message is from UICI';
        $result =  $this->untils->defaultMesgDir($info,'');

        echo ($result)? 'The mail was sent' : 'the mail was not send';
        
    }

    public function encryptedAndDecryptedMessage(){
        $this->load->library("Untils");
        $info = Untils::generateActivationCode();
        $encrypted =  Untils::encryptedMessage($info);
        echo $encrypted;
        echo '\r\n';
        $decrypted =  Untils::decryptedMessage($encrypted);

        if($decrypted == $info){
            echo ' Encrypted';
        }else{
            echo ' Not Encrypted';
        }
        
    }

    public function excelReader(){
       
        $this->load->library('utilities/ExcelFactory');
       
       //$path = 'C:\xampp\htdocs\uici_repo\exports\20190409205937-iSavings-report.xlsx';
       $path = 'C:\xampp\htdocs\uici_repo\exports\users_testings_for_kyc11_.xlsx';
        ExcelFactory::readExcel($path);

    }

    public function espiIsavingsDeposite(){
        $this->load->model('EspiTransaction');
        $this->load->model('User');
    //     $isaving = [
    //         'reference'=>'sdnksjdfhaksdjh',
    //         'amount'=>500.00,
    //         'description'=>'Debit isavings',
    //     ];
    //   $result =  Transaction::depositUiciWallet('ESPI_sdnksjdfhaksdjh',35000.00,'Uici isavings');
      $system_user = User::getSystemUser();
      $result = EspiTransaction::transferOrDepositIsavingsOnEspi('deposit',$system_user->id,128,7500,'jagweuyq','Test',TRUE);
      var_dump('<pre>',$result);
    }

    public function espiIsavingsTransfer(){
        $this->load->model('EspiTransaction');
        $bulk_transfer = [
            [
            'recipient'=>[
                'phone'=>'2347035165199',
                'walletId'=> 6,
                'walletType'=> 'iSavings'
                ],
            'amount'=> 50.00,
            'ref'=>'ESPI_sdnk565654haksdjh'
            ],
            [
            'recipient'=>[
                'phone'=>'2349099387285',
                'walletId'=> 6,
                'walletType'=> 'iSavings'
                ],
                'amount'=> 100.00,
                'ref'=>'ESPI_sdnksjdfhuugsdjh'
                ],
            [
            'recipient'=>[
                'phone'=>'2348094227050',
                'walletId'=> 6,
                'walletType'=> 'iSavings'
                ],
                'amount'=> 100.00,
                'ref'=>'ESPI_sdnksjdf675vcjh'
            ]
        ];

      //$result =  Transaction::bulktransferForIsavings($bulk_transfer);
      $result =  EspiTransaction::bulkTransferOnEspiWallet(5,200,'ESPI_5_3015_3015_deposit_2_5d4a299210a0f',50,$bulk_transfer,'Bulk Transfer isavings on espi Test');
      var_dump('<pre>',$result);
    }
    public function paymentRef(){
        $this->load->model('EspiTransaction');
        $result =  EspiTransaction::isRefExistReValue('ESPI__5d278fa11b631');
        var_dump($result);
        log_message('info','Payment Transfer===> '.print_r($result, true));
    }

    public function getcustomerBlanace(){
        $this->load->library('paymentProcessors/IsavingsPortToEspiWallet');
        $url = 'https://staging.espi.com.ng/api/users/2348094227050';
        $result =  IsavingsPortToEspiWallet::checkIsavingsBalance($url);
        var_dump('<pre>',$result['status']);
        var_dump('<pre>',$result['status']);
        // var_dump('<pre>',$result['wallets'][0]['id']);
        //var_dump('<pre>',$result['data']['wallets']['fela']['id']);
        var_dump('<pre>',$result['data']['wallets']['isavings']['balance']);
        log_message('info','Customer Balance===> '.print_r($result, true));
    }

    public function getUsersUiciLevies(){
        $this->load->model('Uici_levies');
       var_dump(Uici_levies::getUsersUiciLevies(5));
   }

   public function bulkTransferProcess(){
    $this->load->model('WIPBulkGiftingProcess');
    WIPBulkGiftingProcess::backgroundBulkProcess();
}

}