<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once $_SERVER['DOCUMENT_ROOT']."/export.php";
require($_SERVER["DOCUMENT_ROOT"]."/cart/PaypalIpn.php");

if( isset($_POST) && $_SERVER['REQUEST_METHOD'] == 'POST' ) {

    $ipn = new PaypalIpn( PaypalIpn::SANDBOX );

    $ipn->createIpnListener();
    
}
