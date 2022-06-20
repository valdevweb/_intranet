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

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
require_once '../../config/constantes-salon.php';


function deleteInvit($pdoBt)
{
	$table='salon_'.YEAR_SALON;
	$req=$pdoBt->prepare("DELETE FROM $table WHERE  id= :id");
	$req->execute(array(
		':id' =>$_GET['id']
	));
	return $req->rowCount();
}

$row=deleteInvit($pdoBt);
if($row==1){
	header('Location:'. ROOT_PATH.'/public/salon/inscription-'.YEAR_SALON.'.php#inscription-lk');

}
else{
	$errors[]='impossible d\'exécuter la requête, merci d\'avertir le support technique';
}

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

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