<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if( !\Bitrix\Main\Loader::includeModule('alfa4.chinavasion') ) {

     echo('не удалось подключить модуль [alfa4.chinavasion], проверьте корректность установки молуля');

     return false;
}

if(!isset($arParams["CACHE_TIME"]))

   $arParams["CACHE_TIME"] = 86400;

$lifeTime = $arParams['CAHCE_TIME']; 
 
$cacheID = '_CAT_1_'; 

$cache = new CPHPCache;

if($cache->StartDataCache($lifeTime, $cacheID) ) {
   
   $data_request = array(

       "include_content" => "2"
   );

   $responce = Chinavasion::request('getCategory', $data_request);

   if($responce) {

       $arResult = $responce['categories'];

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
$sections = array();
foreach($arResult as $item) {
        $sections[] = $item['name'];
}
$GLOBALS["CATEGORY"] = $sections;
?>