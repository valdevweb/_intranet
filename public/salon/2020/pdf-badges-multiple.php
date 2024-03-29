<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

require_once '../../vendor/autoload.php';
require_once '../../Class/mag/MagHelpers.php';


function getParticipant($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM salon_2020 LEFT JOIN qrcode ON salon_2020.id=qrcode.id WHERE galec= :galec");
	$req->execute(array(
		':galec'	=>$_SESSION['id_galec']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

if(isset($_SESSION['id_galec'])){
	$participantList=getParticipant($pdoBt);
}

if(isset($participantList) && !empty($participantList)){
	$mpdf = new \Mpdf\Mpdf();

		ob_start();
		include('badge-multiple.php');
		$html=ob_get_contents();
		ob_end_clean();

		$mpdf->AddPage();
		$mpdf->WriteHTML($html);


	// $pdfContent = $mpdf->Output('', 'S');
	$pdfContent = $mpdf->Output();

}else{
	echo "vous n'êtes pas inscrit au salon";
}








