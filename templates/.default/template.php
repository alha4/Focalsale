<?
 global $APPLICATION,$currency_alias;
 
 $navchain = $arResult['products'];
 $APPLICATION->AddChainItem($navchain[0]['category_name'],"{$arResult[path]}?sect=".str_replace("%26amp%3B","%26",rawurlencode($navchain[0]['category_name']))."&currency=".$arResult['currency_value']);

 if($arResult[0]['category_name'] != $_GET['sect']) 

     $APPLICATION->AddChainItem($navchain[0]['subcategory_name'],"{$arResult[path]}?sect=".str_replace("%26amp%3B","%26",rawurlencode($_GET['sect']))."&currency=".$arResult['currency_value']); 

?>
<? if($arResult['products']) : ?>
<?
 
?>
<div class="catalog-list">
<?
foreach($arResult['products'] as $item) :
?>
<div class="cat-item">
<?if( isset($item['special']) && is_array($item['special'])  ) : ?>
<span class="discont"><?=$item['special']['discount']?><br>OFF</span>
<?endif;?>
<?

 $img_src = resizeImage($item['main_picture']);

?>
<a href="<?=$arResult['detail_page']?>?code=<?=$item['model_code']?>" ><img class="cat-img" src="<?=$img_src?>" alt="<?=$item['short_product_name']?>" style="max-width:100px">
</a>
<br>
<a href="/detail.php?code=<?=$item['model_code']?>" >
<?=$item['short_product_name']?></a>
<br>
<div class="price"><?=$currency_alias[ $item['currency'] ]?> <?=$item['price']?> 
<?if( is_array($item['special']) ) : ?>
<del><?=$currency_alias[ $item['currency'] ] ?> <?=$item['special']['normal_price']?></del> 
<?endif;?>
</div>

</div>
<?
endforeach;
?>
</div>
<?
 $elements_on_page = (int)$arParams['COUNT_ELEMENTS'];
 $total_products   = $arResult['pagination'];
 $total_pages      = ceil($total_products / $elements_on_page);
?>
<?
if( $total_products > $elements_on_page) :
?>

<div class="pages">
<?
 for($i = 0 ; $i <= $total_pages; $i++) {
   echo '<a href="',$arResult['path'],'?sect=',rawurlencode($_GET['sect']),"&amp;page=",($i),'"', ($_GET['page'] == $i ? ' class="curr-page"' : '')  ,'>',$i + 1,'</a> ';
 }
?>
</div>
<?
 endif;
?>
<?endif;?>