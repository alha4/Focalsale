<?

if( isset($_POST['from_valute']) && isset($_POST['cost']) ) {

$to = isset($_POST['to']) ? $_POST['to'] : 'USD';

$code = $_POST['from_valute'];

$cost = $_POST['cost'];

$data = array(
    'amount'        =>  $cost, 
    'from_currency' => "$code", 
    'to_currency'   => "$to",
    'version'       => '1.0'
);                                                                    
$ch = curl_init('http://rates.ssk.io/');  
                                                                                                                                       
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);   
                                                               
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
                                                                                                                                                                                  
$result = curl_exec($ch);

echo $result;

}