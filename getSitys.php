<?
//echo json_encode($_POST);

$request_url = "http://api.geonames.org/citiesJSON?north=".$_POST['north'].'&south='.$_POST['south'].'&east='.$_POST['east'].'&west='.$_POST['west'].'&lang=en&username=demo';
$ch = curl_init($request_url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$xml_raw = curl_exec($ch);
echo json_encode($xml_raw);