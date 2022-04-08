<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IsavingsPortToEspiWallet {


    public static function espiInterceptorPost($url,$request_data){
        try{
            $ch = curl_init();
            $pubKey = ESPI_PUBKEY;
            $priKey = ESPI_PRIKEY;

            $payload = json_encode($request_data);
            $hash = hash('sha512', $payload.$priKey);
            $headers = [
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer {$pubKey}:{$hash}"
            ];

            log_message('info','Request Headers =====>'.print_r($headers,true));
            log_message('info','Request URL =====>'.$url);
            log_message('info','Request PubKey =====>'.$pubKey);
            log_message('info','Request Before Hash Payload =====>'.print_r($request_data,true));
            log_message('info','Request After Hash Payload =====>'.$payload);
        
            //Use this only on localhost
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            //
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if (($request = curl_exec($ch)) === FALSE) {
                log_message("info", "Service Error". curl_error($ch));
                throw new Exception("Error occurred while accessing ESPI API.");
            }
            curl_close($ch);
            $result = json_decode($request, true);
            log_message('info','ESPI Request Response =====> '.print_r($result, true));
            $bool = ($result['status'] == 'succeeded')? "TRUE":"FALSE";
            log_message('info','ESPI Response BOOLEAN =====> '.$bool);
            //var_dump($result['failed']);
            return ($result['status'] == 'succeeded')? true:false;
            //return $request;
        }catch (Exception $ex){
            log_message("error", $ex->getMessage());
            print_r($ex->getMessage(),true);
            return false;
        }
    }

    public static function checkIsavingsBalance($url){
        $ch = curl_init();
        $pubKey = ESPI_PUBKEY;
        $priKey = ESPI_PRIKEY;
       
        $hash = hash('sha512', $priKey);
        $headers = [
            "Accept: application/json",
            "Content-Type : application/json",
            "Authorization: Bearer {$pubKey}:{$hash}"
        ];

        log_message('info','ESPI Get URL Request =====> '.print_r($url, true));
        log_message('info','ESPI Get Hash Request =====> '.print_r($hash, true));
        log_message('info','ESPI Get Header Request =====> '.print_r($headers, true));
        log_message('info','Request After Hash Payload =====>'.$priKey);
       
         //Use this only on localhost
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (($request = curl_exec($ch)) === FALSE) {
            log_message("info", "Service Error". curl_error($ch));
            throw new Exception("Error occurred while trying to verify Uici payment.");
        }
        curl_close($ch);
        $result = json_decode($request, true);
        log_message("info","ESPI GET REQUEST=====> ".print_r($result,true));

        return $result;
    }

}