<?

class PaypalSinglePayment {
 
  const ERROR_TRANSACTION  = 'Ошибка проведения транзикции';
  const ERROR_ORDER_CREATE = 'Ошибка создания заказа';

  function processPayment($post) {

    $custom_vars = explode('#',$post['custom']);

    $user_id = $custom_vars[1];

    $data_request = $this->parsePost($post);

    try {

      $order_id = $this->createOrder($data_request,$user_id);

      $fields = array(

             "txn_id"=>      $post['txn_id'],
             "ipn_track_id"=>$post['ipn_track_id'],
             "payer_email" =>$post['receiver_email'],
             "user_id" =>    $user_id,
             "payment_status" => $post['payment_status'],
             "txn_date" =>   date("d.m.Y H:i:s")
      );

      $this->saveUserOrderID($post['txn_id'],$user_id,$order_id);
      $this->saveTransaction($fields);

    }
    catch(Exception $e) {

      $this->errorLogTransaction( $e->getMessage() );
  
    } 
  }

  private function parsePost($post) {

   $products = array();

    foreach($post as $key=>$value) { 

      if( preg_match("/item_number(\d)/i",$key,$match)  ) {

          $q = (int)trim($post['quantity'.$match[1] ]);
         
          $products[] = array("model_code"=>urldecode($value),"quantity"=>$q);
      }
    }

    $fio = explode(' ',$post['address_name']);

    $custom_vars = explode('#',$post['custom']);

    $data_request = array(

      "payment_method" => "PayPal",
      "shipping" => "$custom_vars[2]",
      "currency" =>  $post["mc_currency"],
      "products" =>  $products,
      "address" => array(
         "tax_id" =>     $post["txn_id"],
         "first_name" => $fio[0],
         "last_name" =>  $fio[1],
         "street" =>     urldecode($post["address_street"]),
         "zip" =>        $post["address_zip"],
         "city" =>       urldecode($post['address_city']),
         "country" =>    $post['address_country_code'],
         "telephone" =>  $custom_vars[0],
         "state" =>      $post['address_state'],
         "country_iso2" => $post['address_country_code'],
         "socket" => "US"
       )
    );

    return $data_request;

  }

  private function createOrder($data_request,$user_id) {

    $responce = getRequestChinavasion('createOrder', $data_request);

    if(is_numeric($responce['order']['order_id'])) {

      global $USER;

      if(is_numeric($user_id)) {

         $rsUser = $USER->GetByID($user_id);

         $arUser = $rsUser->Fetch();

         $fileds_order_id =   $arUser['UF_ORDER_ID'];

         $fileds_order_date = $arUser['UF_DATE_ORDER'];

         if(is_array($fileds_order_id)) {

            array_push($fileds_order_id, $responce['order']['order_id']);

         } else {

            $fileds_order_id = array($responce['order']['order_id']);
 
         }
    
         if(is_array($fileds_order_date)) {

            array_push($fileds_order_date, date("d.m.Y H:i:s") );

         } else {

            $fileds_order_date = array( date("d.m.Y H:i:s"));

         }

         $fields = Array( 

           "UF_ORDER_ID" =>   $fileds_order_id,
           "UF_DATE_ORDER" => $fileds_order_date
       
         ); 

         $USER->Update($user_id, $fields);
      } 

      return $responce['order']['order_id'];

    } else {
  
     $res = '';

     foreach($responce as $k=>$val) {

       if(is_array($val)) {

          foreach($val as $i=>$v) {
 
             $res.= $i.'='.$v.' ';

          }
     
       } else {
          $res.= $k.'='.$val.' ';
       }

     }

     throw new Exception(PaypalSinglePayment::ERROR_ORDER_CREATE.' '.$res);

     return false;

   }

  }

  private function saveUserOrderID($txn_id,$user_id,$order_id) {

    global $DB;

    $data = array(

            "txn_id" =>  $txn_id,
            "user_id"=>  $user_id, 
            "order_id"=> $order_id,
            "date_create" => date("d.m.Y H:i:s")

          );

    $arInsert = $DB->PrepareInsert("user_orders",$data);

    $strSql = "INSERT INTO user_orders (".$arInsert[0].") VALUES (".$arInsert[1].")";

    $DB->Query($strSql, false);

    return intval($DB->LastID());

  }

  private function saveTransaction($data) {

    global $DB;

    $arInsert = $DB->PrepareInsert("transactions",$data);

    $strSql   = "INSERT INTO transactions (".$arInsert[0].") VALUES (".$arInsert[1].")";

    $DB->Query($strSql, false);

    return intval($DB->LastID());

  }

  private function errorLogTransaction($data_log) {

     $f = fopen("log_error.txt","a+");

     fwrite($f,$data_log);

     fclose($f);
  }

  private function postLog($data_text) {

    $f = fopen("post_log.txt","a+");

    fwrite($f,$data_text);

    fclose($f);
 }
}