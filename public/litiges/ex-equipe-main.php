
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

$errors=[];
$success=[];


function getequipe($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM equipe ORDER BY nom");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

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

function addPerson($pdoLitige)
{
	$req=$pdoLitige->prepare("INSERT INTO equipe (nom, prenom, activite, fonction,service, contrat) VALUES (:nom, :prenom, :activite, :fonction, :service, :contrat)");
	$req->execute(array(
		':nom'=>$_POST['nom'],
		':prenom'=>$_POST['prenom'],
		':activite'=>$_POST['activite-form'],
		':fonction'=>$_POST['fonction-form'],
		':service'=>$_POST['service-form'],
		':contrat'=>$_POST['contrat-form'],
	));
	return $req->rowCount();
}

function search($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM equipe WHERE concat(nom, activite,contrat, service) LIKE :search ");
	$req->execute(array(
		':search' =>'%'.$_POST['search_strg'] .'%'
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
 // return $req->errorInfo();
}
if(isset($_POST['search_form']))
{
	$equipes=search($pdoLitige);

}

else
{
	$equipes=getequipe($pdoLitige);

}

if(isset($_POST['clear_form'])){
	$_POST=[];
	$equipes=getequipe($pdoLitige);

}

if(isset($_POST['submit']))
{
	$row=addPerson($pdoLitige);
	if($row==1)
	{
		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
		header($loc);
	}
	else{
		$errors[]='Un incident est survenu, impossible d\'insérer les données';
	}
}
if(isset($_GET['success']))
{
		$success[]="la personne a bien été ajoutée";

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
	<h1 class="text-main-blue py-5 text-center">Equipes - exploitation</h1>
	<div class="row no-gutters">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<div class="row">
		<div class="col"></div>
		<div class="col">
			<div class="row opacity">
				<div class="col-auto text-center no-padding light-shadow mr-3" >
					<a href="#modify"><img src="../img/litiges/modifier.jpg" class="border"></a>
					<div class="bg-alert-primary light-shadow"><a href="#modify">Modifier</a></div>
				</div>
				<div class="col-auto text-center no-padding light-shadow">
					<a href="#creer"> <img src="../img/litiges/creer.png" class="border"></a>
					<div class="bg-alert-primary light-shadow"><a href="#creer">Créer</a></div>
				</div>
			</div>
		</div>
		<div class="col"></div>
	</div>
	<!-- formulaire de recherche -->
	<div class="row my-5">
		<div class="col-2"></div>
		<div class="col border py-3">
			<p class="text-orange">Rechercher un employé :</p>

			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>#equipe" method="post" class="form-inline">
				<div class="form-group" id="equipe">
					<input class="form-control mr-5 pr-5" placeholder="nom, contrat, service,activité" name="search_strg" id="" type="text"  value="<?=isset($search_strg)? $search_strg: false?>">
				</div>
				<button class="btn btn-primary mr-5" type="submit" id="" name="search_form"><i class="fas fa-search pr-2"></i>Rechercher</button>
				<button class="btn btn-blue" type="submit" id="" name="clear_form"><i class="fas fa-eraser pr-2"></i>Effacer</button>
			</form>
		</div>
		<div class="col-2"></div>
	</div>
	<!-- ./formulaire de recherche-->


	<div class="row" id="modify">
		<div class="col">
			<h3 class="text-main-blue mt-5 mb-3"><img src="../img/litiges/ico-modifier.jpg"> Modifier</h3>
		</div>
	</div>
	<div class="row">
			<div class="col">
				<p class="text-center" ><button class="btn btn-yellow" id="hide-equipe"> <i class="fas fa-filter pr-3"></i>filtrer le tableau</button></p>
			</div>
		</div>
	<div class="row">
		<div class="col">
			<table class="table table-bordered table-striped" id="equipe-table">
				<thead class="thead-blue">
					<tr>
						<th>Etat</th>
						<th>Nom</th>
						<th>Prénom</th>
						<th>Activité</th>
						<th>Fonction</th>
						<th>Service</th>
						<th>Contrat</th>
						<th class="text-center">Modifier</th>
					</tr>
				</thead>
				<tbody>

					<?php

					foreach ($equipes as $equipe)
					{
						if($equipe['mask']==0)
						{
							$ico='<a href="data-hide.php?table=equipe&id='.$equipe['id'].'" class="blue-link"><i class="fas fa-eye"></i></a>';
						}
						else
						{
							$ico='<a href="data-show.php?table=equipe&id='.$equipe['id'].'" class="text-dark-grey"><i class="fas fa-eye-slash"></i></a>';

						}
						echo '<tr>';
						echo'<td>'.$ico.'</td>';
						echo'<td>'.$equipe['nom'].'</td>';
						echo'<td>'.$equipe['prenom'].'</td>';
						echo'<td>'.$equipe['activite'].'</td>';
						echo'<td>'.$equipe['fonction'].'</td>';
						echo'<td>'.$equipe['service'].'</td>';
						echo'<td>'.$equipe['contrat'].'</td>';
						echo'<td class="text-center"><a href="ex-equipe.php?id='.$equipe['id'].'"><i class="fas fa-user-edit"></i></a></td>';
						echo '</tr>';

					}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row" id="creer">
		<div class="col">
			<h3 class="text-main-blue mt-5 mb-5"><img src="../img/litiges/ico-creer.png" class="pr-2">Ajouter un employé</h3>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="row">
				<div class="col">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="shadow p-3">
						<div class="row">
							<div class="col-3">
								<div class="form-group">
									<label>Nom</label>
									<input type="text" name="nom" class="form-control" required >
								</div>
							</div>
							<div class="col-3">
								<div class="form-group">
									<label>Prénom</label>
									<input type="text" name="prenom" class="form-control" required>
								</div>
							</div>
							<div class="col"></div>
						</div>
						<div class="row">
							<div class="col-3">
								<div class="form-group">
									<label>Activité</label>
									<select name="activite-form" id="" class="form-control" required>
										<option value="">Sélectionnez</option>
										<?php
										foreach ($activites as $activite)
										{
											echo '<option value="'.$activite['activite'].'">'.$activite['activite'].'</option>';
										}
										?>

									</select>
								</div>
							</div>
							<div class="col-3">
								<div class="form-group">
									<label>Fonction</label>
									<select name="fonction-form" id="" class="form-control" required>
										<option value="">Sélectionnez</option>
										<?php
										foreach ($fonctions as $fonction)
										{
											echo '<option value="'.$fonction['fonction'].'">'.$fonction['fonction'].'</option>';
										}
										?>

									</select>
								</div>

							</div>
							<div class="col-3">
								<div class="form-group">
									<label>Service</label>
									<select name="service-form" id="" class="form-control" required>
										<option value="">Sélectionnez</option>
										<?php
										foreach ($services as $service)
										{
											echo '<option value="'.$service['service'].'">'.$service['service'].'</option>';
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
									<select name="contrat-form" id="" class="form-control" required>
										<option value="">Sélectionnez</option>
										<?php
										foreach ($contrats as $contrat)
										{

											echo '<option value="'.$contrat['contrat'].'">'.$contrat['contrat'].'</option>';
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

	<div class="row mt-5">
		<div class="col">
			<p class="text-center"><a href="exploit-ltg.php" class="btn btn-primary"><i class="fas fa-arrow-alt-circle-left pr-3"></i>Retour</a></p>
		</div>
	</div>


</div>


</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#hide-equipe').click(function(){
			$('#equipe-table > tbody > tr').each(function(){
				if ($(this).find('i.fa-eye-slash').length)
				{
					$(this).toggleClass('hide');
				}
			});
		});

	});
</script>



<?php

require '../view/_footer-bt.php';

?>