<?
require_once('tcpdf/tcpdf.php');
require_once $_SERVER['DOCUMENT_ROOT']."/export.php";
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
/*ini_set("display_errors",1);
error_reporting(E_ALL);*/
CModule::IncludeModule("file");

class Report {

  private function parseData($ordID) {

    $data_request = array("order_id"=>$ordID);
     
    $dataPeport = array();

    $responce = getRequestChinavasion('getOrderDetails', $data_request);

    if( is_array($responce['order']) ) {

        $order    = $responce['order'];
  
        $products = $order['products'];


        foreach($products as $key=>$item) {

          $data_request = array(
              "model_code"  => $item['model_code']
          );

          $responce = getRequestChinavasion('getProductDetails', $data_request);

          $detail = $responce['products'][0];

          $dataPeport[] = array('product' =>  $detail['full_product_name'],
                                'order_id' => $order['order_id'],
                                'quantity' => $item['quantity'],
                                'price' =>    $order['currency']['prefix'].$item['price'],
                                'shipping_price' => $order['shipping_cost']
                           );
        }

        $addres = $order['address'];
     
        $dataPeport['client'] = array(
                    'Name'=>$addres['first_name'],
                    'Last name'=>$addres['last_name'],
                    'Country'=>  $addres['country'],
                    'City' =>    $addres['city'],
                    'State' =>   $addres['state'],
                    'Street' =>  $addres['street'],
                    'ZIP' => $addres['zip']
                  );
       $dataPeport['tracking_number'] = $order['tracking_number'];
       $dataPeport['shipping']        = $order['shipping'];
    }

    return $dataPeport;
  }

  public function create($order_id) {
 
    $data = $this->parseData($order_id);

    $track_number = $data['tracking_number'];

    $ship_company = $data['shipping'];
     
    $client_data = $data['client'];

    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);


    $pdf->SetCreator("focalsale.com");
    $pdf->SetAuthor('focalsale.com');
    $pdf->SetTitle('Invoice');
    $pdf->SetSubject('Invoice orders');


    $pdf->setSpacesRE("\r");
    $pdf->SetMargins(5,10,5); // устанавливаем отступы (20 мм - слева, 25 мм - сверху, 25 мм - справа)
    $pdf->AddPage();

    $bigFont = 20;
    $imageScale = ( 128.0 / 26.0 ) * $bigFont;
    $smallFont = ( 16.0 / 26.0 ) * $bigFont;

    $pdf->SetFont('times', 'b', $bigFont );
    $pdf->Cell( 0, 0, 'PROFORMA INVOICE', 0, 1);
    $pdf->SetFont('times', 'i', $smallFont );
    
    $pdf->Line( 72, 36 + $imageScale, 72, 36 + $imageScale );
    $pdf->Ln();

    $pdf->SetFillColor(255, 0, 0);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(128, 0, 0);
    $pdf->SetLineWidth(0.1);

    $header = array('Product','Order ID','Quantity','Price');

    $num_headers = count($header);

    $w = array(100,25,20,30);

    $colspan = array_sum($w);
  
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
    $summ+= (float)substr($cells['price'],1) + (float)$cells['shipping_price'];
    $ship+= (float)$cells['shipping_price'];

    foreach($cells as $key=>$val) {

        if($key == 'shipping_price') 
           continue;

        $text = wordwrap($val,40,"\r");//substr($val,0,45);

        $lines = count( explode("\r",$cells['product']) );

        $pdf->MultiCell($w[$j],0,$text,1,'C', 1, 0, '', '', false, 0, false, true, 0);
        $j+=1;
   
    }

  }
  
  $pdf->Ln();
 
  $pdf->SetFillColor(255, 0, 0);
  $pdf->SetTextColor(255);
  $pdf->SetDrawColor(128, 0, 0);
  $pdf->Cell($colspan,7,"", 1, 0, 'L', 1); 
  $pdf->Ln();

  $pdf->SetFillColor(255, 255, 255);
  $pdf->SetTextColor(0,0,0);

  $colspan3 = array_sum(array_slice($w,0,3));
  $w_cell_last = $w[count($w)-1];

  $pdf->Cell($colspan3, 7, 'Total amount:', 1, 0, 'R', 1);
  $pdf->Cell($w_cell_last, 7,'$'.$summ, 1, 0, 'C', 1);
  $pdf->Ln();

  $pdf->Cell($colspan3, 7, 'Shipping costs:', 1, 0, 'R', 1);
  $pdf->Cell($w_cell_last, 7,'$'.$ship, 1, 0, 'C', 1);

  $pdf->Ln();
  $pdf->Cell($colspan3, 7, 'Ordered on: ', 1, 0, 'R', 1);
  $pdf->Cell($w_cell_last,7,date("d.m.Y"), 1, 0, 'C', 1);
  $pdf->Ln();
  
  $pdf->SetFillColor(255, 0, 0);
  $pdf->SetTextColor(255);
  $pdf->SetDrawColor(128, 0, 0);

  $colspan = $colspan - $w[count($w) - 1];

  $pdf->Ln();
  $pdf->Cell($colspan,7,"Buyer", 1, 0, 'L', 1); 
  $pdf->Ln();

  $j=0;
  $pdf->SetFillColor(255, 255, 255);
  $pdf->SetTextColor(0,0,0);

  $colspan2first = (int)($w[0] / 2) - 3.5;
  $colspan_last3 = array_sum( array_slice($w,1));

  foreach($client_data as $k=>$val) {

    $text = $val;

    $pdf->Cell($colspan2first,7, $k , 1, 0, 'L', 1); 
    $pdf->Cell($colspan_last3 + ($colspan2first / 2),7,$text, 1, 0, 'L', 1); 
    $j+=1;

    $pdf->Ln();
    
  }

  $pdf->SetFillColor(255, 0, 0);
  $pdf->SetTextColor(255);
  $pdf->SetDrawColor(128, 0, 0);
  $pdf->Ln();

  
  $pdf->Cell($colspan,7, 'Tracking number for shipping status:', 1, 0, 'L', 1);
  $pdf->Ln();

  $pdf->SetFillColor(255, 255, 255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Cell($colspan,7, $ship_company.' : '.$track_number, 1, 0, 'L', 1);
  $pdf->Ln();
   
  $pdf->SetFillColor(255, 0, 0);
  $pdf->SetTextColor(255);
  $pdf->SetDrawColor(128, 0, 0);
  $pdf->Cell($colspan,7, 'Shop', 1, 0, 'L', 1); 

  $pdf->SetFillColor(255, 255, 255);
  $pdf->SetTextColor(0,0,0);
  $pdf->Ln();
  $pdf->Cell($colspan,7, "www.focalsale.com", 1, 0, 'L', 1); 

  $tid = substr(time(),0,5);

  $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/cart/invoice/doc'.$tid.'.pdf',"F");

  $arrFile = CFile::MakeFileArray( $_SERVER['DOCUMENT_ROOT'].'/cart/invoice/doc'.$tid.'.pdf');

  $fid = CFile::SaveFile($arrFile);

  $_SESSION['did']  = $fid;
  $_SESSION['ord']  = $order_id;

  $pdf->Output('doc.pdf', 'I');

  require_once "send_invoice.php";

 }

}
$report = new Report();
$report->create($_GET['report_blank']);