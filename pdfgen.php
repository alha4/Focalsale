<?
require_once('tcpdf/tcpdf.php');
require_once $_SERVER['DOCUMENT_ROOT']."/export.php";

class Report {


  private function parseData($ordID) {

    $data_request = array("order_id"=>$ordID);
     
    $dataPeport = array();

    $responce = getRequestChinavasion('getOrderDetails', $data_request);

    if( is_array($responce['order']) ) {

        $order = $responce['order'];
  
        $products = $order['products'];

        foreach($products as $key=>$item) {


          $data_request = array(
            "model_code"  => $item['model_code']
          );

          $responce = getRequestChinavasion('getProductDetails', $data_request);

          $detail = $responce['products'][0];

          $dataPeport[] = array('order_id' => $order['order_id'],
                                'product' =>  $detail['full_product_name'],
                                'quantity' => $item['quantity'],
                                'price' =>    $item['price'],
                                'shipping_price' => $order['shipping_cost']
                           );
                                

          

       }

       $addres = $order['address'];


     
       $dataPeport['client'] = array(
                  'name'=>$addres['first_name'],
                  'last_name'=>$addres['last_name'],
                  'Country'=>  $addres['country'],
                  'City' =>    $addres['city'],
                  'State' =>   $addres['state'],
                  'Street' =>  $addres['street'],
                  'ZIP' => $addres['zip']
                  );

    }

    return $dataPeport;
  }

  public function create($order_id) {
 

    $data = $this->parseData($order_id);
     
    $client_data = $data['client'];

    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);


$pdf->SetCreator("focalsale.com");
$pdf->SetAuthor('focalsale.com');
$pdf->SetTitle('Invoice');
$pdf->SetSubject('Invoice orders');


$pdf->setSpacesRE("\r");
$pdf->SetMargins(5,10,5); // устанавливаем отступы (20 мм - слева, 25 мм - сверху, 25 мм - справа)
$pdf->AddPage();


$pdf->SetFillColor(255, 0, 0);
$pdf->SetTextColor(255);
$pdf->SetDrawColor(128, 0, 0);
$pdf->SetLineWidth(0.3);


$header = array('ORDER ID','Product','Quantity','Price','Shipping price');

$num_headers = count($header);

$cell_w = 40;

$colspan = ($cell_w * $num_headers) - $cell_w;

$w = array(25,100,20,15,35);
  
for($i = 0; $i < $num_headers; ++$i) {

    $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
}


$pdf->Ln();

$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0,0,0);

$summ = 0.0;
$ship = 0.0;

$pos_x = array(0,25,125,155,170);

for($i = 0; $i < count($data); $i++) {

    $cells = $data[$i];
    $j = 0;
    $summ+= $cells['price'] + $cells['shipping_price'];
    $ship+= $cells['shipping_price'];

    foreach($cells as $key=>$val) {

        $text = wordwrap($val,40,"\r");//substr($val,0,45);

        $lines = count( explode("\r",$cells['product']) );

        $pdf->MultiCell($w[$j],0,$text,1,'C', 1, 0, '', '', false, 0, false, true, 0);
        $j+=1;
   
   }
   $pdf->Ln();
}

$pdf->Ln();
$pdf->Cell($colspan, 7, 'Shipping: $'.$ship, 1, 0, 'L', 1);
$pdf->Ln();
$pdf->Cell($colspan, 7, 'Total: $'.$summ, 1, 0, 'L', 1);
$pdf->Ln();
$pdf->Cell($colspan, 7, 'Date: '.date("d.m.Y H:i"), 1, 0, 'L', 1);
$pdf->Ln();
$pdf->SetFillColor(255, 0, 0);
$pdf->SetTextColor(255);
$pdf->SetDrawColor(128, 0, 0);

$pdf->Cell($colspan,7,"Buyer", 1, 0, 'L', 1); 
$pdf->Ln();

$j=0;


$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0,0,0);
foreach($client_data as $k=>$val) {


        $text = $val;

        $pdf->Cell($colspan,7, $k.' : '.$text, 1, 0, 'L', 1); 

        $j+=1;

        $pdf->Ln();
    
}

$pdf->SetFillColor(255, 0, 0);
$pdf->SetTextColor(255);
$pdf->SetDrawColor(128, 0, 0);
$pdf->Ln();

$pdf->Cell($colspan,7, 'Shop', 1, 0, 'L', 1); 
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0,0,0);
$pdf->Ln();
$pdf->Cell($colspan,7, "www.focalsale.com", 1, 0, 'L', 1); 

$pdf->Output('doc.pdf', 'I');

}

}

$report = new Report();
$report->create($_GET['report_blank']);