<?php

if(!file_exists($_FILES['my_file']['tmp_name']) || !is_uploaded_file($_FILES['my_file']['tmp_name'])) {

    echo 'Nu ai incarcat niciun fisier';

  }else{

if(move_uploaded_file($_FILES["my_file"]["tmp_name"], 'xml2pdf.xml')){


$string = file_get_contents("xml2pdf.xml");

$arrFrom = array('cac:','cbc:');
$arrTo = array("","");

$string = str_replace($arrFrom, $arrTo, $string);

$xml=simplexml_load_string($string) or die("Error: Cannot create object");

$html =  'Supplier: '.$xml->AccountingSupplierParty->Party->PartyIdentification->ID ."<br>" .
		'Customer: '.$xml->AccountingCustomerParty->Party->PartyIdentification->ID ."<br>" .
		'Amount: '.$xml->InvoiceLine->LineExtensionAmount.' '. $xml->InvoiceLine->LineExtensionAmount['currencyID'] ."<br>";

require_once('TCPDF-main/tcpdf.php');

$pdf = new Tcpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Șerbănescu Mircea');
$pdf->SetTitle('XML2PDF');
$pdf->SetSubject('FF ANAF');

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->SetFont('dejavusans', '', 14, '', true);

$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));


$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

$pdf->Output('xml2pdf.pdf', 'I');
exit;
}
}
?>
