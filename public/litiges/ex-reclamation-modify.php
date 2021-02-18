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



$errors=[];
$success=[];


function getReclamation($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM reclamation WHERE id=:id");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
$reclamationNow=getReclamation($pdoLitige);


function updateReclamation($pdoLitige)
{
	if(empty($_POST['libelle-contrainte-post']))
	{
		$contrainte=0;
	}
	else
	{
		$contrainte=1;
	}
	$req=$pdoLitige->prepare("UPDATE reclamation SET reclamation=:reclamation, contrainte=:contrainte,libelle_contrainte=:libelle_contrainte WHERE id=:id");
	$req->execute(array(
		':reclamation'			=>$_POST['reclamation-post'],
		':contrainte'			=>$contrainte,
		':libelle_contrainte'			=>$_POST['libelle-contrainte-post'],
		':id'					=>$_GET['id']
	));
	return $req->rowCount();
}

if(isset($_POST['submit']))
{
	$updated=updateReclamation($pdoLitige);
	if($updated>0)
	{

		header('Location:ex-reclamation-modify.php?id='.$_GET['id'].'&success=ok');

	}
	else
	{
		$errors[]="impossible de mettre à jour les données";

	}
}

if(isset($_GET['success']))
{
	$success[]="Mise à jour effectuée";
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
	<h1 class="text-main-blue py-5 ">Modification d'une réclamation</h1>
	<div class="row no-gutters">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="row">
				<div class="col">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" class="shadow p-3">
					<div class="row">
						<div class="col-3">
							<div class="form-group">
								<label>Réclamation</label>
								<input type="text" name="reclamation-post" class="form-control" required value="<?=$reclamationNow['reclamation']?>">
							</div>
						</div>
						<div class="col-3">

							<div class="form-group">
								<label>Document obligatoire :</label>
								<input type="text" name="libelle-contrainte-post" class="form-control" value="<?=$reclamationNow['libelle_contrainte']?>">
							</div>
						</div>
						<div class="col pt-2">
								<button type="submit" id="submit" class="btn btn-primary mt-4" name="submit"><i class="fas fa-save pr-3"></i>Modifier</button>
						</div>
					</div>

					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row my-5">
				<div class="col-lg-1 col-xxl-2"></div>
				<div class="col mb-5">

					<p class="text-center"><a href="ex-analyse.php#reclamation" class="btn btn-primary"><i class="fas fa-arrow-alt-circle-left pr-3"></i>Retour</a></p>


				</div>
				<div class="col-lg-1 col-xxl-2"></div>
			</div>




</div>








<?php

require '../view/_footer-bt.php';

?>