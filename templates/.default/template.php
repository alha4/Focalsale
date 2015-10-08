<?
 global $APPLICATION,$currency_alias;

 $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/slimbox2.css');

 $navchain = $arResult['item'];
 
 $APPLICATION->AddChainItem($navchain['category_name'],"{$arParams[CATALOG_PATH]}?sect=".str_replace("%26amp%3B","%26",rawurlencode($navchain['category_name'])) );

 if( $navchain['subcategory_name'] ) 

    $APPLICATION->AddChainItem($navchain['subcategory_name'],"{$arParams[CATALOG_PATH]}?sect=".str_replace("%26amp%3B","%26",rawurlencode($navchain['subcategory_name'])));

?>
<table class="product-detail"> 

<?
foreach($arResult['item'] as $item) :
?>
<tr>
<td class="img-product">
<div class="img-main">

<? $img = resizeImage($item['main_picture']);?>

<img id="main-pict" src="<?=$img ?>" alt="">

</div>

<table class="additional-image">
<tr>
<?
   $item['additional_images'] = array_slice($item['additional_images'],0,5);
   foreach($item['additional_images'] as $img) :
?>
<td><a href="<?=$img?>" class="full-img"><img class="add-pict" src="<?=$img?>" alt="<?=$item['short_product_name']?>" style="max-width:60px;height:auto;margin:10px"></a>
<? 
 endforeach;
?>
</table>

<td class="detail-row">
<h1 class="detail-title"><?=$item['full_product_name']?></h1>

<span>SKU: <?=$item['model_code']?></span>
<span>     <?=$item['status']?></span>

<div class="detail-price">Price: <span class="price-value"><?=$currency_alias[ $item['currency'] ]?> <?=$item['price']?> 

<?if(isset($item['special']) && is_array($item['special'])) : ?>

    <del><?=$currency_alias[ $item['currency'] ]?> <?=$item['special']['normal_price']?></del>

<?endif;?>

</span>
</div>

<?if($item['status'] == 'In Stock'):?>

<div class="quanity"> Quantity: 

<div class="quanity-control">
<span class="down"></span> 
<input type="number" size="2" value="1" min="1" max="10" id="value-quantity">
<span class="up"></span> 
</div>
</div>

<div class="action-button">
<a href="/cart/" class="order-item" data-cart="<?=$item['short_product_name']?>#<?=$item['price']?>#<?=$item['model_code']?>#<?=$item['main_picture']?>"><img src="/images/buy.png" alt="add to cart"> Buy it now</a>
<a href="javascript:void(0);" class="add-cart" data-cart="<?=$item['short_product_name']?>#<?=$item['price']?>#<?=$item['model_code']?>#<?=$item['main_picture']?>"><img src="/images/sh.png" alt="add to cart"> Add cart</a>
</div>
<?else :?>
<span>Date back: <?=$item['date_back']?></span>
<?endif;?>

<tr><td colspan="2">

<section class="tabs">
	<input id="tab_1" type="radio" name="tab" checked="checked" />
	<input id="tab_2" type="radio" name="tab" />
	<input id="tab_3" type="radio" name="tab" />
	
	<label for="tab_1" id="tab_l1">Product details</label>
	<label for="tab_2" id="tab_l2">Shipping methods</label>
	<label for="tab_3" id="tab_l3">Payment methods</label>

	<div style="clear:both"></div>
	<div class="tabs_cont">

<div id="tab_c1">
<?
 if($item['video_link']):
?>
<iframe src="<?=$item['video_link']?>" width="320" height="240" style="overflow:hidden;border:none;"></iframe>
<br>
<?endif;?>

<?=$item['overview']?>
<table>
<?
  foreach($item['additional_images'] as $img) :
?>
<tr><td><img src="<?=$img?>" alt="<?=$item['short_product_name']?>" style="max-width:500px;height:auto;margin:10px">
<? 
 endforeach;
?>
</table>

<?
  endforeach;
?>

</div>
<div id="tab_c2">

<table>
<tr><th colspan="2">Shipping from Chinese Warehouse</th></tr>
<? foreach($delivery as $ship_item) :?>

<tr><td><?=$ship_item['name']?> <td> <?=$ship_item['delivery']?>
<?endforeach;?>

</table>
	
</div>
<div id="tab_c3">
<h4>Paying with PayPal</h4>
<p>NOTE: Your order will be shipped to your PayPal address. Ensure you have selected or entered the correct delivery address.</p>
<ul>
<li>1) Login To Your Account or use Credit Card Express.
<li>2) Enter your Card Details the order will be shipped to your PayPal address. and click Submit.
<li>3) Your Payment will be processed and a receipt will be sent to your email inbox.
</ul>

<h4>Paying with Credit card</h4>
<ul>
<li>1) Choose your shipping address OR create a new one.
<li>2) Enter your Card Details and click Submit.
<li>3) Your Payment will be processed and a receipt will be sent to your email inbox.
</ul>
</div>

</div>
</section>

</table>

<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/slimbox2.js"></script>
<script>

 $('.full-img').slimbox();

 $('.add-pict').mouseover(function(e) {
  
   var active_img_src = (e.target).src;

   (document.getElementById("main-pict")).src = active_img_src; 

 });

 var CURRENCY = "<?=$currenname?>";

 var $qunty = $('#value-quantity');

 $('.add-cart,.order-item').click(function(e) {

  var shipping_method = [];

  $('.ship-method').each(function(key,item) {

    var data_attr = String( $(item).val() ) ;

        data_attr  = data_attr.split("#");

   if(data_attr[0] != false && data_attr[1] != undefined) {
     
        shipping_method.push({ ship_code :data_attr[0], ship_price : data_attr[1] });

   }
  });

  shipping_method = JSON.stringify(shipping_method);

  //console.log( shipping_method );

  var cart_data = String($(e.target).attr("data-cart")).split("#"),

      count_product = $($qunty).val();

      basket = new Basket('sale');

      //console.log( cart_data  ); 

      basket.add(cart_data[0],cart_data[1],count_product,cart_data[2],cart_data[3],shipping_method,CURRENCY);
    
  var count = basket.getCart();
 
      $('#count-product').html( count['count'] );
     
      $('#count-product').animate({fontSize : "20px"},200,function(){
         $(this).animate({fontSize : "14px"},200);
      });

 
 });

 $('.up').click(function() {

   var current_val =  Number(  $( $qunty ).val() ) ;

       current_val+=1;  

       $( $qunty ).val( current_val  );
   
 });

 $('.down').click(function() {

   var current_val =  Number(  $( $qunty ).val() ) ;

   if( current_val > 1 )  {

       current_val-= 1;  

       $( $qunty ).val( current_val  );

  }
   
 });
</script>