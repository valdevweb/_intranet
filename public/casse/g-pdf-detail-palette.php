<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

require('casse-getters.fn.php');
require_once '../../vendor/autoload.php';


// $footer='<table class="padding-table"><tr><td class="footer full-width">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td></tr></table>';

	$paletteInfo=getPaletteInfo($pdoCasse, $_GET['id']);


	ob_start();
	include('pdf-detail-palette.php');
	$html=ob_get_contents();
	ob_end_clean();

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->SetHTMLFooter(PDF_FOOTER);

	$mpdf->WriteHTML($html);


	$pdfContent = $mpdf->Output();

 ?>