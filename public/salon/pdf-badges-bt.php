<?php

require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
}


require_once '../../vendor/autoload.php';



function getUser($pdoUser){
	$req=$pdoUser->prepare("SELECT nom, prenom, fullname, full_name as service_name, id_web_user FROM intern_users LEFT JOIN services ON intern_users.id_service=services.id WHERE id_web_user = :id_web_user");
	$req->execute(array(
		':id_web_user'	=>$_SESSION['id_web_user']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

$participant=getUser($pdoUser);
;


if(!empty($participant)){
	$mpdf = new \Mpdf\Mpdf();
	ob_start();
	include('badge-single-bt.php');
	$html=ob_get_contents();
	ob_end_clean();


	$mpdf->WriteHTML($html);
	$pdfContent = $mpdf->Output();

}




