<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";



require_once '../../vendor/autoload.php';



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function getListServices($pdoUser){
	$req=$pdoUser->query("SELECT * FROM services");
	return $req->fetchAll(PDO::FETCH_ASSOC);

}


function getUserFromService($pdoUser,$idService){
	$req=$pdoUser->prepare("SELECT * FROM intern_users LEFT JOIN services ON id_service=services.id WHERE id_service = :id_service");
	$req->execute([
		':id_service'		=>$idService
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


$listService=getListServices($pdoUser);



foreach ($listService as $key => $service) {
	$users=getUserFromService($pdoUser,$service['id']);


	ob_start();
	include('badge-bt.php');
	$html=ob_get_contents();
	ob_end_clean();
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

	$mpdf->WriteHTML($html);
	$mpdf->Output('D:\\www\\'.VERSION.'intranet\\'.VERSION.'btlecest\\public\\salon\\pdf-intern\\'.trim($service['full_name']).'.pdf', 'F');
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
			<p><a href="pdf-bt-download.php"><button class="btn btn-primary">Télécharger les badges</button></a></p>

		</div>
	</div>
	<div class="row">

	</div>

	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>