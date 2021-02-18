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
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

function flashValid($pdoBt)
{
	$req=$pdoBt->prepare("UPDATE flash SET valid=1 WHERE id=:id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->rowCount();

}

if(isset($_GET['id']))
{
	$row=flashValid($pdoBt);
	if($row==1)
	{
		header('Location:'. $_SERVER['HTTP_REFERER']);
	}
	else
	{
		$errors[]='Impossible de traiter votre demande, merci de prévenir la personne en charge';
	}
}


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
	<h1 class="text-main-blue py-5 ">Validation de flash info</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

<div class="text-center pb-5">
	<a href="flash-validation.php" class="btn btn-primary">Retour</a>
</div>
	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>