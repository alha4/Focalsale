<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require($_SERVER["DOCUMENT_ROOT"]."/cart/order_status.php");

$APPLICATION->SetPageProperty("not_show_nav_chain", "Y");
$APPLICATION->SetTitle(" ");

CModule::IncludeModule("event");

/*ini_set("display_errors",1);
error_reporting(E_ALL);*/

if( isset($_GET['tx']) && isset($_GET['st']) ) {

$responce = urldecode(htmlspecialchars(trim($_GET['cm'])));
$responce = explode('#',$responce);

$is_register_user = isRegisterUser($responce[1]);

$order_id = getOrderID($_GET['tx'],$responce[1]);

if($order_id) :

?>
<h1>Thank you payment done! <br> the order number [ №<? echo $order_id; ?> ]</h1>

<?
 if($is_register_user) :
?>
<div class="pay-done">
<p>To trace the history of your orders you can in your profile <a href="/profile/">My profile</a></p>
<p>Your invoice will be automatically sent to you by email <br>  click the link <a href="<?=$APPLICATION->GetCurDir()?>pdfgen.php?report_blank=<?=$order_id ?>" target="blank">invoice PDF</a></p>
</div>
<? else : ?>
<div class="pay-done">
<p>Your invoice will be automatically sent to you by email <br>  click the link <a href="<?=$APPLICATION->GetCurDir()?>pdfgen.php?report_blank=<?=$order_id ?>" target="blank">invoice PDF</a></p>
</div>
<? 
 endif;
?>

<script>
  
  (new Basket('sale')).clear();

</script>
<?else :?>

<h1>Please wait for confirmation of the transaction.</h1>


<p>wait: <span id="timeback"></span> times</p>

<script>

var timer = document.getElementById("timeback");

 timeend = new Date();

// IE и FF по разному отрабатывают getYear()

timeend= new Date(timeend.getYear() > 1900 ? (timeend.getYear()+1) : (timeend.getYear()+1901),timeend.getMonth(),timeend.getDate(),timeend.getHours(),timeend.getMinutes(),timeend.getSeconds() + 5);

setInterval(function() {

    today = new Date();

    today = Math.floor((timeend-today)/1000);

    tsec=today%60; today=Math.floor(today/60); 
    if(tsec<10)  tsec='0'+tsec;
    tmin=today%60; today=Math.floor(today/60); 
    if(tmin<10) tmin='0'+tmin;
    thour=today%24; today=Math.floor(today/24);

    timestr= tsec + " seconds";

    timer.innerHTML=timestr;

},1000);


 setTimeout(function() {

    location = '<?=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']?>';

 },4000);

</script>

<?
 endif;
?>
<?
  }
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>