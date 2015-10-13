<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true )die();

if( !\Bitrix\Main\Loader::includeModule('alfa4.chinavasion') ) 

    die('не удалось подключить модуль [alfa4.chinavasion], проверьте наличие молуля в системе');

$currency_var = $GLOBALS[$arParams['CURRENCY_VAR_NAME']];

$lifeTime = $arParams['CACHE_TIME']; 

$sect     = $_GET['sect'];

$offset   = isset($_GET['page']) ? (int)$_GET['page'] : 0;

$count_on_page = (int)$arParams['COUNT_ELEMENTS'];

$cacheID  = $sect.'_'.$count_on_page.'_'.$offset.'_'.$lifeTime.'_'.$currency_var;  

$cache = new CPHPCache;

if($cache->StartDataCache($lifeTime, $cacheID) ) {
   
   $data_request  = array(

      'currency'   => "$currency_var",

      'categories' => array("$sect"),
 
      'pagination' => array("start"=>$offset,"count"=>$count_on_page)

   );

   $responce = requestChinavasion('getProductList', $data_request);

   if($responce['products']) {

       $arResult['products']   = $responce['products'];

       $arResult['pagination'] = $responce['pagination']['total'];

       $arResult['currency_value'] = $currency_var; 
        
       $this->SetResultCacheKeys(array('products'));

       $this->IncludeComponentTemplate();

       $templateCachedData = $this->GetTemplateCachedData();

       $cache->EndDataCache(
         array(
           "arResult" => $arResult,
           "templateCachedData" => $templateCachedData    
         )
      );

      $result = $arResult['products'];
      $category_name  = $result[0]['category_name']; 

      $APPLICATION->SetTitle($category_name);
      $APPLICATION->SetPageProperty("keywords",   $category_name);
      $APPLICATION->SetPageProperty("description",$category_name);

      $APPLICATION->AddChainItem($category_name,"{$arParams[CATALOG_PATH]}?sect=".str_replace("%26amp%3B","%26",rawurlencode($category_name)));

      if($category_name != $sect) 
     
         $APPLICATION->AddChainItem($result[0]['subcategory_name'],"{$arParams[CATALOG_PATH]}?sect=".str_replace("%26amp%3B","%26",rawurlencode($sect)));  
   }

} else {
  
  extract($cache->GetVars());
  $this->SetTemplateCachedData($templateCachedData);

  if($arResult) {

    $result = $arResult['products'];
    $category_name  = $result[0]['category_name']; 

    $APPLICATION->SetTitle($category_name);
    $APPLICATION->SetPageProperty("keywords",   $category_name);
    $APPLICATION->SetPageProperty("description",$category_name);

    $APPLICATION->AddChainItem($category_name,"{$arParams[CATALOG_PATH]}?sect=".str_replace("%26amp%3B","%26",rawurlencode($category_name)));

    if($category_name != $sect) 
     
       $APPLICATION->AddChainItem($result[0]['subcategory_name'],"{$arParams[CATALOG_PATH]}?sect=".str_replace("%26amp%3B","%26",rawurlencode($sect))); 
  }
}
?>