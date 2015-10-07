<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true )die();

if( !\Bitrix\Main\Loader::includeModule('alfa4.chinavasion') ) 

    die('не удалось найти компонент [alfa4.chinavasion]');

global $currenname;

$lifeTime = 30*60*24; 

$sect = $_GET['sect'];

$elements_on_page = 16;
 
$offset = isset($_GET['page']) ? (int)$_GET['page'] : 0;

$cacheID = $sect.'_1_'.$offset.'_'.$currenname;  

$cache = new CPHPCache;

if($cache->StartDataCache($lifeTime, $cacheID) ) {
   
   $data_request  = array(

      'currency' => "$currenname",

      'categories' => array("$sect"),
 
      'pagination' => array("start"=>$offset,"count"=>$elements_on_page)

  );

   $responce = getRequestChinavasion('getProductList', $data_request);


   if($responce) {

       $arResult =  $responce['products'];

       $arResult['pagination'] = $responce['pagination']['total'];

   }

    
   $this->IncludeComponentTemplate();

   $templateCachedData = $this->GetTemplateCachedData();

   $cache->EndDataCache(
      array(
         "arResult" => $arResult,
         "templateCachedData" => $templateCachedData    
      )
   );

} else {

   extract($cache->GetVars());
   $this->SetTemplateCachedData($templateCachedData);

}
 
?>