<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
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

function getListFournisseur($pdoBt){
	$req=$pdoBt->query("SELECT * FROM salon_fournisseurs");
	return $req->fetchAll(PDO::FETCH_ASSOC);

}


function getListQrcode($pdoBt){
	$req=$pdoBt->query("SELECT id,qrcode FROM qrcode WHERE  id>=500");
	return $req->fetchAll(PDO::FETCH_KEY_PAIR);
}

function getFournisseur($pdoBt, $idFou){
	$req=$pdoBt->prepare("SELECT * FROM salon_fournisseurs_presence WHERE  id_fournisseur= :id_fournisseur");
	$req->execute(array(
		':id_fournisseur'	=>$idFou
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$listFournisseur=getListFournisseur($pdoBt);
$listQrcode=getListQrcode($pdoBt);





foreach ($listFournisseur as $key => $fournisseurs) {
	$thisFou=getFournisseur($pdoBt, $fournisseurs['id']);
	ob_start();

	include('badge-fournisseur.php');
	$html=ob_get_contents();
	ob_end_clean();
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

	$mpdf->WriteHTML($html);
	// $pdfContent = $mpdf->Output();
	$mpdf->Output('D:\\www\\'.VERSION.'intranet\\'.VERSION.'btlecest\\public\\salon\\pdf-fournisseur\\'.trim($fournisseurs['fournisseur']).'.pdf', 'F');
}




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
	<h1 class="text-main-blue py-5 ">Téléchargement des badges générés</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col text-right">
			<p><a href="exploit-2020.php"><button class="btn btn-primary">Retour</button></a></p>

		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row">
		<div class="col">
			<p><a href="pdf-fournisseur-download.php"><button class="btn btn-primary">Télécharger les badges</button></a></p>

		</div>
	</div>
	<div class="row">

	</div>

	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>