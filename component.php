<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true )die();

if( !\Bitrix\Main\Loader::includeModule('alfa4.chinavasion') ) die('не удалось найти модуль [alfa4.chinavasion]');
  ini_set("display_errors",1);
  error_reporting(E_ERROR);

  //$path = $this->GetPath();

  require_once $_SERVER['DOCUMENT_ROOT']."/tabgeo_country_v4.php";
 
  //echo $path."/tabgeo_country_v4.php";exit;

  $currency_var = $GLOBALS[$arParams['CURRENCY_VAR_NAME']];

  $ip = $_SERVER['REMOTE_ADDR'];

  $country_code = tabgeo_country_v4($ip);
 
  $code = $_GET['code'];

  $data_request = array(
    "model_code"  => "$code",
    "currency" => "$currency_var"
  );

  $responce = getRequestChinavasion('getProductDetails', $data_request);

  $data_request = array(
   'currency' => "$currency_var",
   "socket" => "EU",
   "shipping_country_iso2" => "$country_code",
   "products" => array(array(
              "model_code" => "$code",
              "quantity" => 1
      )
    )
  );

  $price = getRequestChinavasion('getPrice', $data_request);
 
  $delivery = $price['shipping'];

  if($responce) {

      $arResult['item'] = $responce['products'][0];
      $arResult['shipping']  = $delivery;
   }

   $this->IncludeComponentTemplate();
 
?>