<?
 require_once $_SERVER['DOCUMENT_ROOT'].'/export.php';

 require_once $_SERVER['DOCUMENT_ROOT']."/tabgeo_country_v4.php";

 $ip = $_SERVER['REMOTE_ADDR'];

 $country_code = tabgeo_country_v4($ip);

 if( isset($_POST['prod_id']) ) {

 $data_request = array(
   "currency" => "USD",
   "socket" => "EU",
   "shipping_country_iso2" => "$country_code",
   "products" => array(array(
              "model_code" => $_POST['prod_id'],
              "quantity" => 1
      )
    )
 );

 $price = getRequestChinavasion('getPrice', $data_request);

 if($price['shipping']) {
 
    $delivery = $price['shipping'];

    echo json_encode($delivery);

 } else {

   echo json_encode($price);
 }

}
?>