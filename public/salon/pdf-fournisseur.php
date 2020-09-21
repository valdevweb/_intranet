<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";



require_once '../../vendor/autoload.php';



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function getFournisseur($pdoBt){
	$req=$pdoBt->prepare("SELECT * FROM salon_fournisseurs_presence WHERE  id_fournisseur= :id_fournisseur");
	$req->execute(array(
		':id_fournisseur'	=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getListQrcode($pdoBt){
	$req=$pdoBt->query("SELECT id,qrcode FROM qrcode WHERE  id>=500");
	return $req->fetchAll(PDO::FETCH_KEY_PAIR);
}


function upadateFournisseur($pdoBt){
	$req=$pdoBt->prepare("UPDATE salon_fournisseurs_presence set printed=1 where  id_fournisseur= :id_fournisseur");
	$req->execute(array(
		':id_fournisseur'	=>$_GET['id']
	));
}

upadateFournisseur($pdoBt);
$fournisseurs=getFournisseur($pdoBt);
$listQrcode=getListQrcode($pdoBt);





ob_start();
include('badge-fournisseur-par-fournisseur.php');
$html=ob_get_contents();
ob_end_clean();
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

$mpdf->WriteHTML($html);
$pdfContent = $mpdf->Output();
// $mpdf->Output('filename.pdf', '\Mpdf\Output\Destination::FILE');
//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">
	<h1 class="text-main-blue py-5 ">Main title</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>


	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>