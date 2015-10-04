<?php
require($_SERVER["DOCUMENT_ROOT"]."/cart/PaypalSinglePayment.php");
/*
ini_set("display_errors",1);
error_reporting(E_ALL);*/

class PaypalIpn {

    private $service;

    private $paypal_url;

    const IPN_ERROR = 'Ошибка http запроса IPN';

    const IPN_NOT_CONFIRM = 'IPN Запрос не прощел проверку';

    const IPN_ERROR_TRANSACTION = 'Не верный тип транзикции';
  
    const LIVE    = 'https://www.paypal.com/cgi-bin/websc';

    const SANDBOX = 'https://www.sandbox.paypal.com/cgi-bin/websc';

    const BUSSINES_EMAIL = 'ivalderman345@mail.ru';

    public function __construct($config_url) {

       global $DB;

       $this->service = new PaypalSinglePayment($DB);

       $this->paypal_url = $config_url;
  
    }

    public function createIpnListener() {

      $raw_post_data = file_get_contents('php://input');

      $raw_post_array = explode('&', $raw_post_data);

      if( count($raw_post_array) == 1) {
      
          return false;
       
      }
     
      $myPost = array();

      foreach($raw_post_array as $keyval) {

        $keyval = explode ('=', $keyval);

        if(count($keyval) == 2)
           $myPost[$keyval[0]] = urldecode($keyval[1]);
      }

      $ipn_query_string = 'cmd=_notify-validate';

      if(function_exists('get_magic_quotes_gpc')) {

         $get_magic_quotes_exists = true;

      }  else {
           
         $get_magic_quotes_exists = false;
      }

      foreach($myPost as $key=>$value) {

        if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {

           $value = urlencode(stripslashes($value));

        } else {

           $value = urlencode($value);
        }
        
        $ipn_query_string.= "&$key=$value";
      }

      // проверка подлинности IPN запроса
      try {

        $ipn_result = $this->sendRequest($this->paypal_url,$ipn_query_string);

      } catch(Exception $e) {

        $this->ipn_log_error((PaypalIpn::IPN_ERROR).' '.$e->getMessage());

      } 

      $tokens = explode("\r\n\r\n", trim($ipn_result));

      $verified_responce = trim(end($tokens));

      if(strcmp($verified_responce, "VERIFIED") == 0) {
 
         $this->service->processPayment($myPost);

      } 

      if(strcmp($verified_responce, "INVALID") == 0) {

         // запрос не прощел проверку
         $this->log((PaypalIpn::IPN_NOT_CONFIRM).':'.$ipn_query_string.':'.date("d, m, Y H:i:s")."\r\n");

         return false;

      }

      if($myPost["txn_type"] != "cart" || urldecode($myPost["receiver_email"]) != PaypalIpn::BUSSINES_EMAIL) {

         $this->log((PaypalIpn::IPN_ERROR_TRANSACTION).':'.$ipn_query_string.':'.date("d, m, Y H:i:s")."\r\n");

         return false;

      }
        
    }

    private function sendRequest($paypal_url,$http_request_data){

      $ch = curl_init($paypal_url);

      if($ch == FALSE) {

         throw new Exception('Не удалось создать подключение curl');

      }
      curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $http_request_data);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
      curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
      curl_setopt($ch, CURLOPT_HEADER, 1);
      curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
   
      //передаем заголовок, указываем User-Agent - название нашего приложения. Необходимо для работы в live режиме
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: ' . $_SERVER['HTTP_USER_AGENT']));

      $ipn_paypal_result = curl_exec($ch);

      curl_close($ch);

      if(!$ipn_paypal_result) {

         throw new Exception( curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) );

      }
      
      return $ipn_paypal_result;

    }
 
   private function log($debug_mess) {
    
     $f = fopen("ipn_log.txt","a+");

     fwrite($f,$debug_mess);

     fclose($f);

   }

   private function ipn_log_error($debug_mess) {
    
    $f = fopen("ipn_log_error.txt","a+");

    fwrite($f,$debug_mess);

    fclose($f);
     
   }
}   