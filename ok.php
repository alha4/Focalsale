<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require($_SERVER["DOCUMENT_ROOT"]."/cart/order_status.php");

if( !isset($_SESSION['confirm_t']) ) 

     $_SESSION['confirm_t'] = 0;

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

$email_report = getUserEmail($_GET['tx'],$responce[1]);

if($order_id) :

?>
<h1>Thank you payment done! <br> the order number [ №<? echo $order_id; ?> ]</h1>

<?
 if($is_register_user) :
?>

<p>To trace the history of your orders you can in your profile <a href="/profile/">My profile</a></p>

<p>Get invoice on the link <a href="<?=$APPLICATION->GetCurDir()?>pdfgen.php?report_blank=<?=$order_id ?>&eml=<?=$email_report?>" target="blank">invoice PDF</a></p>

<? else : ?>

<p>Get invoice on the link <a href="<?=$APPLICATION->GetCurDir()?>pdfgen.php?report_blank=<?=$order_id ?>&eml=<?=$email_report?>" target="blank">invoice PDF</a></p>


<? 
 endif;
?>

<script>
  
  (new Basket('sale')).clear();

</script>
<?else :?>

<? if($_SESSION['confirm_t'] < 5): ?>

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
  $_SESSION['confirm_t'] = $_SESSION['confirm_t'] + 1;
?>
<?else:?>
  <p>Your transaction cannot be reported, repeat the order you may have used the correct address for the delivery and shipping method</p>

<? unset($_SESSION['confirm_t']); ?>
<?endif;?>
<?
 endif;
?>
<?
  }
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>