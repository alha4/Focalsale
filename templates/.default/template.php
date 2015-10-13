<? global $currency_alias; ?>

<div class="new-arrivals">
<?foreach($arResult['products']  as $item) :?>
<?
$month_alias = array(
    'Jan' => 1,
    'Feb' => 2,
    'Mar' => 3,
    'Apr' => 4,
    'May' => 5,
    'Jun'  => 6,
    'Jul'   => 7,
    'Aug' => 8,
    'Sep' => 9,
    'Oct'  => 10,
    'Nov' => 11,
    'Dec' => 12
  );

  $date_p = explode("-",$item['date_product_was_launched']);
 
  $curr_date = date("Y-M-d");
  
  $curr_date = explode("-",$curr_date);

  $curr_year = $curr_date[0];

  $curr_month = (int)$month_alias[$curr_date[1]];
  
  $year = $date_p[0];

  $month = (int)$month_alias[$date_p[1]];

  if( $curr_year  == $year && 
     ($month == $curr_month ||  
      $month <= $curr_month - 1 ||  
      $month <= $curr_month - 2 ||  
      $month <= $curr_month - 3) && 
      $new_count <= 6 ) :
?>
<div class="new-item">
<?
  $img_src = resizeImage($item['main_picture'],60,60);
?>
<a href="<?=$arParams['PATH']?>?code=<?=$item['model_code']?>"><img alt="<?=$item['short_product_name']?>" src="<?=$img_src?>" style="max-width:60px;float:left"></a> 
<a href="<?=$arParams['PATH']?>?code=<?=$item['model_code']?>"><?=$item['short_product_name']?></a> 
<b class="price-new"><?=$currency_alias[ $item['currency'] ]?><?=$item['price']?></b>
</div>
<? $new_count++; ?>
<?endif; ?> 
<?endforeach;?>
</div>