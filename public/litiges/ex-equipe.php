
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
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

// analyse
// conclusion
// equipe
// état
// imputation
// reclamation
// typo




$errors=[];
$success=[];


function getequipe($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM equipe WHERE id=:id");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
$userNow=getequipe($pdoLitige);

function getActivite($pdoLitige){
	$req=$pdoLitige->prepare("SELECT DISTINCT activite FROM equipe ORDER BY activite");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$activites=getActivite($pdoLitige);

function getFonction($pdoLitige){
	$req=$pdoLitige->prepare("SELECT DISTINCT fonction FROM equipe ORDER BY fonction");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$fonctions=getFonction($pdoLitige);

function getService($pdoLitige){
	$req=$pdoLitige->prepare("SELECT DISTINCT service FROM equipe ORDER BY service");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$services=getService($pdoLitige);

function getContrat($pdoLitige){
	$req=$pdoLitige->prepare("SELECT DISTINCT contrat FROM equipe ORDER BY contrat");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$contrats=getContrat($pdoLitige);

function updateUser($pdoLitige)
{
	$req=$pdoLitige->prepare("UPDATE equipe SET nom=:nom, prenom=:prenom,activite=:activite, fonction=:fonction,service=:service,contrat=:contrat WHERE id=:id");
	$req->execute(array(
		':nom'			=>$_POST['nom'],
		':prenom'			=>$_POST['prenom'],
		':activite'			=>$_POST['activite-form'],
		':fonction'			=>$_POST['fonction-form'],
		':service'			=>$_POST['service-form'],
		':contrat'			=>$_POST['contrat-form'],
		':id'			=>$_GET['id']
	));
	return $req->rowCount();
}

if(isset($_POST['submit']))
{
	$updated=updateUser($pdoLitige);
	if($updated>0)
	{

		header('Location:ex-equipe.php?id='.$_GET['id'].'&etat=ok');

	}
	else
	{
		$errors[]="impossible de mettre à jour les données";

	}
}

if(isset($_GET['etat']))
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
	<h1 class="text-main-blue py-5 ">Modification info employé</h1>
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
								<label>Nom</label>
								<?php
								$nomNow="";
								if(isset($userNow['nom']))
								{
									$nomNow=$userNow['nom'];
								}
								 ?>

								<input type="text" name="nom" class="form-control" required value=<?=$nomNow?>>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Prénom</label>
								<?php
								$prenomNow="";
								if(isset($userNow['prenom']))
								{
									$prenomNow=$userNow['prenom'];
								}
								 ?>

								<input type="text" name="prenom" class="form-control" required value=<?=$prenomNow?>>
							</div>
						</div>
						<div class="col"></div>
					</div>
					<div class="row">
						<div class="col-3">
							<div class="form-group">
								<label>Activité</label>
								<select name="activite-form" id="" class="form-control">
									<option value="">Sélectionnez</option>
									<?php
									foreach ($activites as $activite)
									{
										$selected='';
										if($activite['activite']==$userNow['activite'])
										{
											$selected='selected';
										}
										echo '<option value="'.$activite['activite'].'" '.$selected.'>'.$activite['activite'].'</option>';
									}
									 ?>

								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Fonction</label>
								<select name="fonction-form" id="" class="form-control">
									<option value="">Sélectionnez</option>
									<?php
									foreach ($fonctions as $fonction)
									{
										$selected='';
										if($fonction['fonction']==$userNow['fonction'])
										{
											$selected='selected';
										}
										echo '<option value="'.$fonction['fonction'].'" '.$selected.'>'.$fonction['fonction'].'</option>';
									}
									 ?>

								</select>
							</div>

						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Service</label>
								<select name="service-form" id="" class="form-control">
									<option value="">Sélectionnez</option>
									<?php
									foreach ($services as $service)
									{
										$selected='';
										if($service['service']==$userNow['service'])
										{
											$selected='selected';
										}
										echo '<option value="'.$service['service'].'" '.$selected.'>'.$service['service'].'</option>';
									}
									 ?>

								</select>
							</div>
						</div>
						<div class="col-3">

						</div>
					</div>
					<div class="row">
						<div class="col-3">
						<div class="form-group">

								<label>Contrat</label>
								<select name="contrat-form" id="" class="form-control">
									<option value="">Sélectionnez</option>
									<?php
									foreach ($contrats as $contrat)
									{
										$selected='';
										if(strtoupper($contrat['contrat'])==strtoupper($userNow['contrat']))
										{
											$selected='selected';
										}
										echo '<option value="'.$contrat['contrat'].'" '.$selected.'>'.$contrat['contrat'].'</option>';
									}
									 ?>

								</select>
							</div>
						</div>
						<div class="col"></div>
					</div>
						<div class="row">
							<div class="col-9">
								<div class="pt-4 mt-2 text-right">
									<button type="submit" id="submit" class="btn btn-primary" name="submit"><i class="fas fa-save pr-3"></i>Enregistrer</button>
								</div>
							</div>
							<div class="col"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row my-5">
				<div class="col-lg-1 col-xxl-2"></div>
				<div class="col mb-5">

					<p class="text-center"><a href="ex-equipe-main.php?id=<?=$_GET['id']?>" class="btn btn-primary"><i class="fas fa-arrow-alt-circle-left pr-3"></i>Retour</a></p>


				</div>
				<div class="col-lg-1 col-xxl-2"></div>
			</div>




</div>








<?php

require '../view/_footer-bt.php';

?>