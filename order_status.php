<?

function getOrderID($transaction_id,$user_id) {

   global $DB;
   
   $tx_id = $DB->ForSql(htmlspecialchars(trim($transaction_id))) ;

   $user_id = $DB->ForSql($user_id);

   $SQL= "SELECT order_id FROM user_orders WHERE txn_id='".$tx_id."' AND user_id='".$user_id."'";

   $db_result = $DB->Query( $SQL );

   $rows  = $db_result->Fetch();

   $order_id =  $rows['order_id'];

   if( is_numeric($order_id) ) {
 
     return $order_id;

   }

   return false;
 
}

function isRegisterUser($user_id) {

  
  if(preg_match("/^(\d)$/i", $user_id)) {

       return true;
  }

  return false;

}

function getUserEmail($transaction_id,$user_id) {

  global $DB;
   
  $tx_id = $DB->ForSql(htmlspecialchars(trim($transaction_id))) ;

  $user_id = $DB->ForSql($user_id);

  $SQL= "SELECT buyler_email FROM transactions WHERE txn_id='".$tx_id."' AND user_id='".$user_id."'";

   $db_result = $DB->Query( $SQL );

   $rows  = $db_result->Fetch();

   $email_buyler =  $rows['buyler_email'];

   if( is_string($email_buyler) ) {
 
     return $email_buyler;

   }

   return false;

}