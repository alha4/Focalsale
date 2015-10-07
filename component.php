<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true )die();

if( !\Bitrix\Main\Loader::includeModule('alfa4.chinavasion') ) 

    die('не удалось найти компонент [alfa4.chinavasion]');

$currency_var = $GLOBALS[$arParams['CURRENCY_VAR_NAME']];

$lifeTime = 30*60*24; 

$sect = $_GET['sect'];

$elements_on_page = is_int($arParams['COUNT_ELEMENTS']) ? : 16;
 
$offset = isset($_GET['page']) ? (int)$_GET['page'] : 0;

$cacheID = $sect.'_1_'.$offset.'_'.$currency_var;  

$cache = new CPHPCache;

if($cache->StartDataCache($lifeTime, $cacheID) ) {
   
   $data_request  = array(

      'currency' => "$currency_var",

      'categories' => array("$sect"),
 
      'pagination' => array("start"=>$offset,"count"=>$elements_on_page)

  );

  $responce = getRequestChinavasion('getProductList', $data_request);


  if($responce) {

       $arResult['products'] =   $responce['products'];

       $arResult['pagination'] = $responce['pagination']['total'];

       $arResult['path'] = $arParams['CATALOG_PATH'];
  
       $arResult['currency_value'] =  $currency_var; 

       $arResult['on_page'] = $elements_on_page;
       
       $arResult['detail_page'] = $arParams['DETAIL_PATH'];
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