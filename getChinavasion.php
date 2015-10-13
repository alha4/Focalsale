<?php

 class Chinavasion {

 static function request($uri,$params,$is_test) {

  $TEST_API_KEY = 'yf7dGULjbSnQ8uYupGc_pveaXUfHaapXANbTdW9foNQ.';

  $API_KEY      = 'HX9rMhhzNW0eB24xgUnvCzz606t4V3LvN2GuuMhCH-Q.';

  $url          =  "https://secure.chinavasion.com/api/{$uri}.php";

  if( false === array_key_exists('key',$params) ) {

     $params['key'] = $API_KEY;

  }

  if($is_test === true) {

     $params['key'] = $TEST_API_KEY;
  }

  $content = json_encode($params);

  $curl = curl_init($url);

  curl_setopt($curl, CURLOPT_HEADER, false);

  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));

  curl_setopt($curl, CURLOPT_POST, true);

  curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

  $json_response = curl_exec($curl);

  $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

  if( $status != 200 ) {

      die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));

  }

  curl_close($curl);

  $response = json_decode($json_response, true);

  if($response) {
  
      return $response;
  }
  return false;
 }

}