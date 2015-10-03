<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("event");
CModule::IncludeModule("file");

$doc_id = $_SESSION['did'];

$event_id = 'SEND_PDF_INVOICE';
$site_id = 's1';

$arFields = array("EMAIL_TO"=>"a.i.n.1989@mail.ru");

$pdf_file = CFile::GetByID( $doc_id );

$arFile = $pdf_file->Fetch();

CEvent::send($event_id,$site_id,$arFields,false,false,array($arFile['ID']));

?>

