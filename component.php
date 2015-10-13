<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();

if( !\Bitrix\Main\Loader::includeModule('alfa4.chinavasion') ) {

     echo('не удалось подключить модуль [alfa4.chinavasion], проверьте корректность установки молуля');

     return false;
}

CPageOption::SetOptionString("main", "nav_page_in_session", "N"); 

if(!isset($arParams['CURRENCY_VAR_NAME'])) {
   $currency_var = 'USD';
} else {
   $currency_var = $GLOBALS[$arParams['CURRENCY_VAR_NAME']];
}
if(!isset($arParams["CACHE_TIME"]))

   $arParams["CACHE_TIME"] = 86400;

   $sect  = $_GET['sect'];
   
   $page = intval($_GET['PAGEN_1']);

$offset_page = isset($_GET['page']) ? (int)$_GET['page'] : 0;

$count_on_page = is_numeric($arParams['COUNT_ELEMENTS']) === true ? (int)$arParams['COUNT_ELEMENTS'] : 16;

$cacheTime = $arParams['CACHE_TIME'];

$cacheID  = $sect.'_'.$count_on_page.'_'.$offset_page.'_'.$cacheTime.'_'.$currency_var;  

$cache = new CPHPCache;

if($cache->StartDataCache($cacheTime, $cacheID) ) {
   
   $data_request  = array(

      'currency'   => "$currency_var",

      'categories' => array("$sect"),
 
      'pagination' => array("start"=>0,"count"=>500)
   );

   $responce = requestChinavasion('getProductList', $data_request);

   if($responce['products']) {

      $arResult['products']   = $responce['products'];

      $rs_ObjectList = new CDBResult;

      $rs_ObjectList->InitFromArray($arResult['products']);
      $rs_ObjectList->NavStart($count_on_page, false);
      $rs_ObjectList->NavPageCount = ceil(count($arResult['products'] ) / $count_on_page);
      $rs_ObjectList->NavPageNomer = $page;
      $rs_ObjectList->NavNum = 1;
      $rs_ObjectList->NavPageSize = $count_on_page;
      $rs_ObjectList->NavRecordCount = count($arResult['products']);

      $arResult["NAV_STRING"] = $rs_ObjectList->GetPageNavString("products", "visual_sale");

      $arResult["PAGE_START"] = $rs_ObjectList->SelectedRowsCount() - ($rs_ObjectList->NavPageNomer - 1) * $rs_ObjectList->NavPageSize;

      while($ar_Field = $rs_ObjectList->Fetch()) {

          $arResult['products'][] = $ar_Field;
      }
        
      $this->SetResultCacheKeys(array('products','NAV_STRING','PAGE_START'));

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