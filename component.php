<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if( !\Bitrix\Main\Loader::includeModule('alfa4.chinavasion') ) 

    die('не удалось найти компонент [alfa4.chinavasion]');

$lifeTime = 30*60*24; 
 
$cacheID = '_CAT_1_'; 

$cache = new CPHPCache;

if($cache->StartDataCache($lifeTime, $cacheID) ) {
   
   $data_request = array(

       "include_content" => "2"
   );

   $responce = requestChinavasion('getCategory', $data_request);

   if($responce) {

       $arResult =  $responce['categories'];

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