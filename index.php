<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("not_show_nav_chain", "Y");
$APPLICATION->SetTitle("Shopping Cart");
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/slimbox2.css');
require_once $_SERVER['DOCUMENT_ROOT']."/tabgeo_country_v4.php";
$ip = $_SERVER['REMOTE_ADDR'];

$country_code = tabgeo_country_v4($ip);

global $USER;

?>
<h1>Your Shopping Cart <small id="count_items"></small></h1>

<table width="700" id="cart-list">
<tr class="title-cart"><td colspan="2">Product Name & Detail<td>Price<td>Quantity</tr>
</table>


<?if(!$USER->IsAuthorized()):?>
<div class="how-bay">
<p>
Now you can <span class="buy-quik">BUY QUICKLY</span> or <br>
<span class="log-buy">LOGIN AND BUY WITH DISCOUNTS AND GIFTS</span> or <br>
<span class="reg-free">REGISTER FOR FREE!</span>
</p>
</div>
<?endif;?>


<?if($USER->IsAuthorized()):?>
<div class="user-form">
<h3>Your shipping address</h3> 
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" id="pay-form">
<?

 $rsUser = CUser::GetByID($USER->GetID());
 $arUser = $rsUser->Fetch();

?>
  <label>First name: <span class="required-label">*</span> <input name="first_name" type="text" title="Name" required value="<?=$arUser['NAME']?>"  /></label>
  <label>Last name: <span class="required-label">*</span> <input name="last_name" type="text" title="LastName" required  value="<?=$arUser['LAST_NAME']?>" /></label>
  <?if($country_code == 'US'):?>
  <label>State: <select type="text" name="state">
  <?
 
     $f = fopen("state_base.txt","r");

     while (($data = fgetcsv($f, 1000, ",")) !== FALSE) {
    
       
           echo '<option value="',$data[0],'">',$data[1] ,"</option>\n";
     
    }
   fclose($f);

  ?>
  </select></label>
<?else : ?>
  <label>State/Region: <input name="state" type="text" title="State/Region" value="<?=$arUser['PERSONAL_STATE']?>"></label>
<?endif;?>
  <label>City: <span class="required-label">*</span> <input name="city" type="text" title="City" required  value="<?=$arUser['PERSONAL_CITY']?>"/></label>
  <label>ZIP/Post Code: <span class="required-label">*</span> <input name="zip" type="text" title="xxxxxx" required value="<?=$arUser['PERSONAL_ZIP']?>" /></label>
  <label>Address: <span class="required-label">*</span>  <input type="text" name="address1" type="text" title="88 Street" required  value="<?=$arUser['PERSONAL_STREET']?>" /></label> 
  <label>Phone:   <span class="required-label">*</span>  <input id="custom_var" type="text" placeholder="+x xxx-xx-xx" required pattern="(\+?\d[- .]*){4,13}" title="International, national, state or local phone number"/></label>

  <input type="hidden" name="address_override" value="1" />
  <input type="hidden" name="currency_code" value="USD" />
  <input type="hidden" name="country" value="<?=$country_code // echo 'US';?>" />
  <input type="hidden" name="cmd" value="_cart" /> 
  <input type="hidden" name="upload" value="1" /> 
  <input type="hidden" name="business" value="ivalderman345@mail.ru" /> 
  <input type="hidden" name="no_shipping" value="0" />
  <input type="hidden" name="return" value="http://www.focalsale.com/cart/ok.php"/>
  <input type="hidden" name="notify_url" value="http://www.focalsale.com/cart/transaction.php"/>

  <input type="hidden" id="orig_custon" name="custom" >
  <div id="items-pay"></div>

  <input type="image" src="/images/pay-button.png" alt="" class="order-item2" id="order-send">
</form>
</div>
<script>
 
 $('#custom_var').change(function() {
   
  var prefix_uid = <?=$USER->GetID()?>;   

  var curr_val = $(this).val();
  
  var ship_var = $('.radio-ship:checked').val();

  //console.log( ship_var );

  $('#orig_custon').val(curr_val + '#' + prefix_uid + '#' + ship_var + '#' + '<?=$currenname ?>' );     
    
 });
</script>

<? else : ?>

<div class="user-form">
<h3>Quick buying</h3>
<p>Quick purchase without savings discount and storage order history in your account.</p>
</p>

<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" id="pay-form">

 <label>First name: <span class="required-label">*</span> <input name="first_name" type="text" title="Name" required   /></label>
 <label>Last name: <span class="required-label">*</span> <input name="last_name" type="text" title="LastName" required /></label>
 <?if($country_code == 'US'):?>
  <label>State: <select type="text" name="state">
  <?
 
     $f = fopen("state_base.txt","r");

     while (($data = fgetcsv($f, 1000, ",")) !== FALSE) {
    
       
           echo '<option value="',$data[0],'">',$data[1] ,"</option>\n";
     
    }
   fclose($f);

  ?>
  </select></label>
<?else : ?>
 <label>State/Region: <input name="state" type="text" title="State/Region"></label>
<?endif;?>
 <label>City: <span class="required-label">*</span> <input name="city" type="text" title="City" required /></label>
 <label>ZIP/Post Code: <span class="required-label">*</span> <input name="zip" type="text" titile="xxxxx" required /></label>
 <label>Address: <span class="required-label">*</span> <input type="text" name="address1" type="text" title="Street 99/a 22" required  /></label> 
 <label>Phone:   <span class="required-label">*</span> <input id="custom_var" type="text" placeholder="+x xxx xxx-xx-xx"  required pattern="(\+?\d[- .]*){4,13}" title="International, national, state or local phone number" /></label>
 
 <input type="hidden" id="orig_custon" name="custom">
 <input type="hidden" name="address_override" value="0" />
 <input type="hidden" name="currency_code" value="USD" />
 <input type="hidden" name="country" value="<?=$country_code?>" />
 <input type="hidden" name="cmd" value="_cart" /> 
 <input type="hidden" name="upload" value="1" /> 
 <input type="hidden" name="business" value="ivalderman345@mail.ru" /> 
 <input type="hidden" name="no_shipping" value="0" />
 <input type="hidden" name="return" value="http://www.focalsale.com/cart/ok.php"/>
 <input type="hidden" name="notify_url" value="http://www.focalsale.com/cart/transaction.php"/>

 <div id="items-pay"></div>

 <input type="image" src="/images/pay-button.png" alt="" class="order-item2" id="order-send">
</form>
</div>

<script>
 
 $('#custom_var').change(function() {
   
  var prefix_uid = 'noauth';  
 
      prefix_uid+= String( 1 + (9999 - 1) * Math.random() ).substr(0,4);

  var curr_val = $(this).val();
  
  var ship_var = $('.radio-ship:checked').val();

  $('#orig_custon').val(curr_val + '#' + prefix_uid + '#' + ship_var + '#' + '<?=$currenname ?>' );     
    
 });
</script>

<?endif;?>

<?if(!$USER->IsAuthorized()):?>

<div class="user-form">
<h3>Login to FocalSale</h3>
<p>Login to your account to quickly fill in shipping addres, save order history and get discount and gifts.</p>

<?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form", 
	"auth", 
	array(
		"COMPONENT_TEMPLATE" => "auth",
		"REGISTER_URL" => "/register/",
		"FORGOT_PASSWORD_URL" => "/repass/",
		"PROFILE_URL" => "/profile/",
		"SHOW_ERRORS" => "Y"
	),
	false
);?>
</div>
<div class="user-form">
<h3>New to FocalSale? Register for free!</h3>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.register", 
	"register", 
	array(
		"COMPONENT_TEMPLATE" => "register",
		"SHOW_FIELDS" => array(
			0 => "NAME",
			1 => "LAST_NAME",
			2 => "PERSONAL_PHONE",
			3 => "PERSONAL_STREET",
			4 => "PERSONAL_CITY",
			5 => "PERSONAL_ZIP",
		),
		"REQUIRED_FIELDS" => array(
			0 => "EMAIL",
			1 => "NAME",
			2 => "SECOND_NAME",
			3 => "LAST_NAME",
			4 => "PERSONAL_PHONE",
			5 => "PERSONAL_STREET",
			6 => "PERSONAL_CITY",
			7 => "PERSONAL_ZIP",
		),
		"AUTH" => "Y",
		"USE_BACKURL" => "Y",
		"SUCCESS_PAGE" => "/register/done.php",
		"SET_TITLE" => "N",
		"USER_PROPERTY" => array(
		),
		"USER_PROPERTY_NAME" => ""
	),
	false
);?>

</div>

<?endif;?>

<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/slimbox2.js"></script>
<script>

 var CURRENTCY = '<?=$currenname ?>';

 var BASE_CURRENTCY = 'USD';

 var ICON_FOLDER = '/images/';

 function get_ship_icon(ship_code) {

    switch(ship_code) {
     
      case "DHL" : return 'dhl';
      break;
    
      case "FedEx" : return  'fedex';
      break;
 
      case "Air Mail" : return 'airmail';
      break;

      case "EMS" : return 'ems';
      break;
  
      case "UPS" : return 'ups';
      break;

      case "RU Special Line" : return 'ruline';
      break;
       
   } 

 }

 function convert_price(val,elm) {

   var basket = new Basket("sale");

   var from =  basket.getPriceCode();

   if(from == CURRENTCY) {

      $('#' + elm).html( Number(val).toFixed(2) );

      $('#total').html(summ_total());

      return false;
   }


   $.ajax({ url : 'converter.php',type : 'POST', data : 'from_valute=' + from + '&to=' + CURRENTCY + '&cost=' + val,success : function(resp){
  
     resp = JSON.parse(resp);
    
     if( resp["result"] == true && resp["error"] == "") {

       $('#' + elm).html( Number(resp["total"]).toFixed(2) );

        var summ_t = summ_total();

            $('#total').html(summ_t);

       //console.log('convert responce = ' +   Number(resp["total"]).toFixed(2) + ' ==' + 'from_curency=' + from + '&cost=' + val + ' valuta' + resp["currency"]);

     } else {
        console.log( resp["error"]);
    }

  }}); 

 
 }

 function convert_ship(val,elm,total_elm) {

   var basket = new Basket("sale");

   var from =  basket.getPriceCode();

   if(from == CURRENTCY) {

       //$(total_elm).html( parseFloat(Number(val).toFixed(2)) );

       $(elm).val( Number(val).toFixed(2) );

       $(total_elm).html(ship_total());  

       $('#total').html(summ_total());

       return false; 
   }
   
   $.ajax({ url : 'converter.php',type : 'POST', data : 'from_valute=' + from + '&to=' + CURRENTCY + '&cost=' + val,success : function(resp){
  
     resp = JSON.parse(resp);
    
     if( resp["result"] == true && resp["error"] == "") {

       $(elm).val( Number(resp["total"]).toFixed(2) );

       $(total_elm).html(ship_total());  

       $('#total').html(summ_total());

     } else {
        console.log( resp["error"]);
    }

  }}); 
 }

 function valute_convert(val,elm) {

   var basket = new Basket("sale");

   var from =  basket.getPriceCode();
   
   $.ajax({ url : 'converter.php',type : 'POST', data : 'from_valute=' + from + '&to=' + BASE_CURRENTCY + '&cost=' + val,success : function(resp){
  
     resp = JSON.parse(resp);
    
     if( resp["result"] == true && resp["error"] == "") {

       $('#' + elm).val( Number(resp["total"]).toFixed(2) );

       //console.log('RUB convert ' + elm +' ' + Number(resp["total"]).toFixed(2) ); 

     } else {
        console.log( resp["error"]);
    }

  }}); 


   return parseFloat( Number(val).toFixed(2));

 }

 function initCart() {

 var basket = new Basket("sale");

 var cart = basket.getCart();

 var items = basket.get(),

     cart_list = $('#cart-list tr:first'),
     
     pay_form =  $('#items-pay');

 if(items.length == 0) {

    $(cart_list).after('<tr><td colspan="4"><h4>cart is empty</h4>');
    return false;

 }

 $('#count_items').html(  '( '  + String( items.length ) + ' items )' );

 var storage_buffer = [],

     ship_codes = [];

  for(var item in items)  {
     
      var prod = (items[item]).split("||");

      var ship_method = JSON.parse( prod[5] );

      var tmp_codes = [];

      for(var j in ship_method) {

         var code = (ship_method[j]).ship_code;
         
             tmp_codes.push(code);
        
     }

     storage_buffer.push(tmp_codes);
  }

  var reverse = [];

      for(var i = storage_buffer.length; i > 0; i--) {

          reverse.push(storage_buffer[i - 1]);
      }

  storage_buffer = reverse;

  function intersect(A,B) {

    var M=A.length, N=B.length, C=[];

    for (var i=0; i< M; i++) { 

        var j=0, k=0;

        while (B[j] !==A[i] && j<N) j++;

        while (C[k]!==A[i] && k<C.length) k++;

        if (j!=N && k==C.length) C[C.length]=A[i];

     }

   return C;

  }

 function exists_ship_code(code,arr_ship) {

    for(var i = 0; i < arr_ship.length; i++) {

         var curr_ship_arr = arr_ship[i];

         if( $.inArray(code,curr_ship_arr) == -1)

           return false;

    }

   return true;

 }

 function shipObjToArray(arr) {
 
 var arr_result = [];
 
   for(var i in arr) {

      arr_result.push( arr[i].ship_code ) ;
   }

   return arr_result;

 }

 //console.log(  storage_buffer );


 for(var item in items)  {
     
    var prod = (items[item]).split("||");

    var item_c = Number(item) + 1;

    $(pay_form).append('<input type="hidden" name="item_name_' +  item_c + '" value="' + prod[0]+ '">'); 

    $(pay_form).append('<input type="hidden" name="item_number_' +  item_c + '" value="' + prod[3]+ '">'); 

    $(pay_form).append('<input type="hidden" class="item_sum" name="amount_' +  item_c + '" id="amount_' +  item_c  + '" value="' + prod[2] + '">'); 

    valute_convert(prod[2],'amount_' +  item_c);

    $(pay_form).append('<input type="hidden" id="shipping_' + item_c + '" name="shipping_' + item_c  +'" class="ship_item" value="0.0">');

    $(pay_form).append('<input type="hidden" id="user_ship_' + item_c + '" class="ship_item_user_valute" value="0.0">');

    $(pay_form).append('<input type="hidden" class="vq_' + item_c  + '" name="quantity_' +  item_c + '" value="' + prod[1] + '">'); 

    $(cart_list).after('<tr><td><a href="' + prod[4] + '"  class="full-img"  rel="lightbox"><img class="cart-prod-img" src="' + prod[4] + '" alt=""></a><td><a class="title-prod" href="/detail.php?code='+ prod[3] + '">' + prod[0] + '</a><td class="cost_price" id="price_' + item_c + '"></td><td><div class="quanity"><div class="quanity-control"><span class="down"></span> <input type="number" size="2" id="cv_' + item_c + '" value="'+ prod[1] + '" min="1" max="10" class="value-quantity"><span class="up"></span></div></div><a href="javascript:void(0)" class="control-basket" title="remove"><img src="/images/delete.png" alt="Remove"></a>');

    convert_price(prod[2],'price_' + item_c) ;

    var ship_method = JSON.parse( prod[5] );

    var html_select = '<select class="shipping-data" id="ship_method_' + item_c  +'">';

    var current_ship_method =  shipObjToArray(ship_method) ;

    var exists_ship_method; 

    if(item_c < items.length && storage_buffer[item_c] != undefined) {

       exists_ship_method = intersect(current_ship_method , storage_buffer[item_c]);

    }
   
    for(var i in ship_method) {

    var ship_obj = ship_method[i];

    if(storage_buffer[item_c] != undefined && $.inArray(ship_obj.ship_code, exists_ship_method) != -1  ) {

         html_select+='<option value="' +  ship_obj.ship_price + '">' + ship_obj.ship_code;

         if( $.inArray(ship_obj.ship_code,ship_codes) == -1 && exists_ship_code(ship_obj.ship_code,storage_buffer) ) {
      
                ship_codes.push(ship_obj.ship_code);
         }

    } else {


       html_select+='<option value="' +  ship_obj.ship_price + '">' + ship_obj.ship_code;

       if( $.inArray(ship_obj.ship_code,ship_codes) == -1 && exists_ship_code(ship_obj.ship_code,storage_buffer) ) {
      
                ship_codes.push(ship_obj.ship_code);
       }
   }

   }

   html_select+='</select>';

   $(cart_list).after('<tr><td>' + html_select);

  } 

  $('.full-img').slimbox();

  var html_radio = '',

      ship_icons = '';

      summ_ship =  '';

  for(var k in ship_codes) {

     var icon =  get_ship_icon(ship_codes[k]);

     html_radio+= '<td><input type="radio" class="radio-ship" name="ship_meth" value="' + ship_codes[k]  + '">';

     ship_icons+= '<td style="text-align:center !important;"><img src="' + ICON_FOLDER + icon  + '.png" alt="">';

     summ_ship+=  '<td style="text-align:center !important;"><span id="' + ( String(ship_codes[k]).replace(/\s+/g,'_')  ) + '">0.0</span>';
  }
  

  ship_icons+=  '<tr><td width="220">Shipping price: <div id="price-ship-items">' + summ_ship + '</div>';
  
  ship_icons+=  '<tr><td width="220">Select shipping method: <div class="ship-comp">'+  html_radio +'</div>';

  $('#cart-list tr:last').after('<tr><td colspan="4" class="left-spacing-cell" id="ship-icon"><table width="600" id="table-ship"><tr><td width="220">Shipping companies: ' + ship_icons + ' </table><tr class="center-row"><td colspan="4" class="total-cell">Total  <span id="total"></span> ' + CURRENTCY );

  var shipping_data = $('.shipping-data');


 $('.radio-ship').change(function(e) {

   var current_select = $(e.target).val();

   var ship_cost_id = current_select.replace(/\s+/g,'_'); 

   var shipping_user_valute = $('.ship_item_user_valute');

   //console.log(shipping_user_valute); 

   $(shipping_data).each(function(k,item) {

     var options = $(item).find("option");

     $(options).attr("selected",false);

     $(options).each(function(i,elm) {

       if( String( $(elm).text() ) == current_select ) {

           $(elm).attr("selected","selected");

        var ship_cost = String( $(elm).val() ) ;    

            //$('input[name="shipping_' + (k + 1) + '"]').val( ship_cost );
        
            convert_ship(ship_cost, shipping_user_valute[k], $('#' + ship_cost_id) ) ;

            valute_convert( ship_cost,'shipping_' + (k + 1) );
           
            //console.log( ship_cost_id );
             
       } 

     });
   });
  
   var summ = summ_total();
   
       $('#total').html( summ );

   
 });
 
 var summ =  summ_total();
   

     $('#total').html( summ );
}


 function ship_total() {
 
  var shipping = 0.0;

   $('.ship_item_user_valute').each(function(key,val) {
   
       shipping = shipping + parseFloat( Number( $(val).val() ).toFixed(2)) ;
      
   });

  return parseFloat(Number(shipping).toFixed(2)); 

 }

 function summ_total() {

  var summa = 0.0,
      counts = $('.value-quantity');

  //.item_sum
  $('.cost_price').each(function(key,val) {
   
     summa+= parseFloat( Number( $(val).html() ).toFixed(2)) * $(counts[key]).val();

  });

  summa = summa + ship_total();

  return parseFloat(Number(summa).toFixed(2)) ;

 }


 function update_basket() {

  var basket = new Basket("sale"),

      items = basket.get(),
     
      pay_form = $('#items-pay'),

      quanity = $('.value-quantity');

   $(pay_form).find("input").remove();
 
   for(var item in items)  {
     
    var prod = (items[item]).split("||");

    var item_c = Number(item) + 1;

    $(pay_form).append('<input type="hidden" name="item_name_' +  item_c + '" value="' + prod[0]+ '">'); 

    $(pay_form).append('<input type="hidden" name="item_number_' +  item_c + '" value="' + prod[3]+ '">'); 

    $(pay_form).append('<input type="hidden" class="item_sum" name="amount_' +  item_c + '" id="amount_' +  item_c  + '" value="' + prod[2] + '">'); 

    valute_convert(prod[2],'amount_' +  item_c);

    $(pay_form).append('<input type="hidden" name="shipping_' + item_c  +'" class="ship_item" value="0.0">');

    $(pay_form).append('<input type="hidden" id="user_ship_' + item_c + '" class="ship_item_user_valute" value="0.0">');

    $(pay_form).append('<input type="hidden" class="vq_' + item_c  + '" name="quantity_' +  item_c + '" value="' + prod[1] + '">'); 

    $(quanity[item]).attr("id","cv_" + item_c);

   }
  
 }

 function updateTotal(val,opt) {

    /*var curr_val = parseFloat( Number( $('#total').html() ).toFixed(2) );
  

    if( opt == 'sub' ) {
        curr_val-=val;
        
    }
    if( opt == 'add' ) {
        curr_val+=val;
      
    }*/

    $('#total').html(  summ_total() );    

 }


 initCart();

 $('.control-basket').click(function(e) {

   var prod_id = $(e.target).parent().parent().prev().prev().first().text();

   var basket = new Basket("sale");
 
       basket.remove(prod_id);
 
   var curr_node =  $(e.target).parent().parent().parent(),
       prev_node =  $(e.target).parent().parent().parent().prev();

       $(prev_node).remove();
       $(curr_node).remove();

   update_basket();

   var ship_t =  ship_total(),
       summ_t =  summ_total();
   
   $('#ship-total').html(ship_t);

   $('#total').html(summ_t);
 
   current_basket_item();

   $('#count_items').html( '( ' + $('#count-product').html()  + ' items )' );

 });

 $('.up').click(function(e) {

   var $qunty = $(e.target).parent().find('.value-quantity');

   var current_val =  Number(  $( $qunty ).val() ) ;

       current_val+=1;  

       $( $qunty ).val( current_val  );

   var val = parseFloat( Number( $(e.target).parent().parent().prev().html() ).toFixed(2)  );

   var input_id = String( $(e.target).parent().find(".value-quantity").attr("id") ).split("_"); 

   var input_val = $(e.target).parent().find(".value-quantity").val();

   $('.vq_' + input_id[1]).val( input_val  );

   updateTotal(val,'add');
   
 });


 $('.down').click(function(e) {

   var $qunty = $(e.target).parent().find('.value-quantity');

   var current_val =  Number(  $( $qunty ).val() ) ;

   if( current_val > 1 )  {

       current_val-= 1;  

       $( $qunty ).val( current_val  );

     
   var val = parseFloat( Number( $(e.target).parent().parent().prev().html() ).toFixed(2) );

   var input_id = String( $(e.target).parent().find(".value-quantity").attr("id") ).split("_"); 

   var input_val = $(e.target).parent().find(".value-quantity").val();

   $('.vq_' + input_id[1]).val( input_val );
    
    updateTotal(val,'sub');

   }

  });

 function inputValidateSupport() {

  var inputs = $('#pay-form').find("input");

  for(var i = 0; i < inputs.length; i++) {
    
     var supportValidite = $(inputs[i]).get();
     //console.log( supportValidite[0].validity instanceof ValidityState  );

     if( !supportValidite[0].validity instanceof ValidityState) {

         return false;
     }

  }

  return true;
 }


 function initFormValidate() {

  $('#pay-form').find("input").each(function(key,item) {

      $(item).change(function(e) {
        
         var input = e.target;

         var validatePattern = $(input).attr("pattern");

         validateInput(input,validatePattern);

      });

  });
 }

 function validateInput(input,pattern) {
    
   var input_val = String( $(input).val() ) ;

   var regExp = new RegExp(pattern);

   if(input_val == '' || !regExp.test(input_val) ) {

        showErrorInput(input,'Invalid value !');

        return false;
    } else {

        $(input).parent().find('.error-input').remove(); 
    }

    return true;
 }

 function showErrorInput(elm,mess) {

   var error_elm = '<spam class="error-input">' + mess + '</span>';
  
   $(elm).parent().append(error_elm);

 }

 $('#order-send').click(function(e) {

  // console.log($('.radio-ship').is(':checked') );

   if( !$('.radio-ship').is(':checked')  ) {

        alert('Select shipping method');

        return false;
   }

   return true;
  
 });

 if( !inputValidateSupport() ) {
 
  initFormValidate();

  $('#order-send').click(function(e) {

    var errors = $("#pay-form").find('.error-input');
   
    if(errors.length > 0)

       return false;

    return true;

   });

}

 //console.log( inputValidateSupport() );
</script>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>