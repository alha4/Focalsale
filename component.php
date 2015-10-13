<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true )die();

if( !\Bitrix\Main\Loader::includeModule('alfa4.chinavasion') ) {

     echo('не удалось подключить модуль [alfa4.chinavasion], проверьте корректность установки молуля');

     return false;
}

if(!isset($arParams["CACHE_TIME"]))

   $arParams["CACHE_TIME"] = 86400;

if(!isset($arParams['CURRENCY_VAR_NAME'])) {
   $currency_var = 'USD';
} else {
   $currency_var = $GLOBALS[$arParams['CURRENCY_VAR_NAME']];
}

$category = $GLOBALS["CATEGORY"];

$lifeTime = $arParams['CACHE_TIME']; 
 
$cacheID = '_NEW_2_'.$currency_var.'_'.$lifeTime; 

$cache = new CPHPCache;

if($cache->StartDataCache($lifeTime, $cacheID) ) {

   $category_new = $category[mt_rand(0,count($category) - 1)];

   $data_request  = array(

      'currency' => "$currency_var",

      'categories' => array("$category_new"),
    
      'pagination' => array("start"=>0,"count"=>50)
  );

  $responce = Chinavasion::request('getProductList', $data_request);

  if($responce['products']) {

       $arResult['products'] = $responce['products'];

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