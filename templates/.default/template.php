<?
 global $APPLICATION,$currency_alias;
?>
<? if($arResult['products']) : ?>
<div class="catalog-list">
<?
  $page = intval($_GET['PAGEN_1']);
  $on_page = (int)$arParams['COUNT_ELEMENTS'];

  if( $page * $on_page < count($arResult['products']) ) {
    $arResult['products'] = array_slice($arResult['products'],$page * $on_page,$on_page);
  } else {
    $arResult['products'] = array_slice($arResult['products'],count($arResult['products']) - $on_page);
  }
  foreach($arResult['products'] as $item) :
?>
<div class="cat-item">
<?if( isset($item['special']) && is_array($item['special'])  ) : ?>
  <span class="discont"><?=$item['special']['discount']?><br>OFF</span>
<?endif;?>
<?
  $img_src = resizeImage($item['main_picture']);
?>
<a href="<?=$arParams['DETAIL_PATH']?>?code=<?=$item['model_code']?>" ><img src="<?=$img_src?>" alt="<?=$item['short_product_name']?>" style="max-width:100px"></a>
<br>
<a href="<?=$arParams['DETAIL_PATH']?>?code=<?=$item['model_code']?>" ><?=$item['short_product_name']?></a>
<div class="price"><?=$currency_alias[ $item['currency'] ]?> <?=$item['price']?> 
<?if(is_array($item['special'])): ?>
  <del><?=$currency_alias[ $item['currency'] ] ?> <?=$item['special']['normal_price']?></del> 
<?endif;?>
</div>
</div>
<?
endforeach;
?>
</div>
<div style="clear:both;">
<?
 echo $arResult["NAV_STRING"];
?>
</div>
<?endif;?>