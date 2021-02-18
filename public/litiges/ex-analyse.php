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

include '../../Class/LitigeHelpers.php';


function getanalyse($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM analyse ORDER BY analyse");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function getconclusion($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM conclusion ORDER BY conclusion");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function getetat($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM etat ORDER BY etat");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function getimputation($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM imputation ORDER BY imputation");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getreclamation($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM reclamation LEFT JOIN reclamation_contrainte ON id_contrainte=reclamation_contrainte.id ORDER BY reclamation");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getgt($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM gt ORDER BY gt");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function gettypo($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM typo ORDER BY typo");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function add($pdoLitige, $key){
	$value=$key.'-form';
	$req=$pdoLitige->prepare("INSERT INTO $key SET $key=:value");
	$req->execute(array(
		':value'	=>$_POST[$value]
	));
	return $req->rowCount();
}

function addEtat($pdoLitige){
	$req=$pdoLitige->prepare("INSERT INTO etat (etat, occ_etat) VALUES (:etat, :occ_etat) ");
	$req->execute([
		':etat'		=>$_POST['etat-form'],
		':occ_etat'	=>$_POST['occasion']
	]);
	return $req->rowCount();

}

function addReclamation($pdoLitige){
	$contrainte=0;
	if (!empty($_POST['contrainte'])) {
		$contrainte=$_POST['contrainte'];
	}
	$req=$pdoLitige->prepare("INSERT INTO reclamation SET reclamation=:reclamation, id_contrainte=:id_contrainte");
	$req->execute(array(
		':reclamation'	=>$_POST['reclamation-form'],
		':id_contrainte'	=>$contrainte,

	));
	return $req->rowCount();
}

$errors=[];
$success=[];

$analyses=getanalyse($pdoLitige);
$conclusions=getconclusion($pdoLitige);
$etats=getetat($pdoLitige);
$imputations=getimputation($pdoLitige);
$reclamations=getreclamation($pdoLitige);
$gts=getgt($pdoLitige);
$typos=gettypo($pdoLitige);
$arReclamContrainte=LitigeHelpers::listReclamationContrainte($pdoLitige);

if(isset($_POST['analyse']))
{
	$row=add($pdoLitige,'analyse');
	if($row>0)
	{
		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
		header($loc);
	}
}

if(isset($_POST['conclusion']))
{
	$row=add($pdoLitige,'conclusion');
	if($row>0)
	{
		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
		header($loc);
	}
	else{
		$errors[]="impossible de mettre à jour la base de donnée";
	}
}

if(isset($_POST['etat']))
{
	$row=addEtat($pdoLitige);
	if($row>0)
	{
		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
		header($loc);
	}
	else{
		$errors[]="impossible de mettre à jour la base de donnée";
	}
}

if(isset($_POST['imputation']))
{
	$row=add($pdoLitige,'imputation');
	if($row>0)
	{
		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
		header($loc);
	}
	else{
		$errors[]="impossible de mettre à jour la base de donnée";
	}
}

if(isset($_POST['reclamation'])){
	$row=addReclamation($pdoLitige);
	if($row>0){
		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
		header($loc);
	}


}

if(isset($_POST['gt']))
{
	$row=add($pdoLitige,'gt');
	if($row>0)
	{
		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
		header($loc);
	}
	else{
		$errors[]="impossible de mettre à jour la base de donnée";
	}
}



if(isset($_POST['typo']))
{
	$row=add($pdoLitige,'typo');
	if($row>0)
	{
		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
		header($loc);
	}
	else{
		$errors[]="impossible de mettre à jour la base de donnée";
	}
}

if(isset($_GET['success']))
{
	$success[]='mise à jour effectuée';
}





$tableHeadAnalyse=	'<div class="col"><table class="table border table-striped"><thead class="thead-blue"><tr><th>Etat</th><th>Analyse</th></tr></thead><tbody>';
$tableHeadReponse=	'<div class="col"><table class="table border table-striped"><thead class="thead-blue"><tr><th>Etat</th><th>Reponse</th></tr></thead><tbody>';
$tableHeadStatut=	'<div class="col"><table class="table border table-striped"><thead class="thead-blue"><tr><th>Etat</th><th>Statut</th><th>Occasion</th></tr></thead><tbody>';
$tableHeadImputation=	'<div class="col"><table class="table border table-striped"><thead class="thead-blue"><tr><th>Etat</th><th>Imputation</th></tr></thead><tbody>';
$tableHeadgt=	'<div class="col"><table class="table border table-striped"><thead class="thead-blue"><tr><th>Etat</th><th>Nature</th></tr></thead><tbody>';
$tableHeadReclamation=	'<div class="col"><table class="table border table-striped"><thead class="thead-blue"><tr><th>Etat</th><th>Reclamation</th><th>Contrainte</th><th>Modifier</th></tr></thead><tbody>';
$tableHeadTypo=	'<div class="col"><table class="table border table-striped"><thead class="thead-blue"><tr><th>Etat</th><th>Typo</th></tr></thead><tbody>';
$tablefoot='</tbody></table></div>';


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
	<h1 class="text-main-blue py-5 ">Analyse - exploitation</h1>
	<div class="row no-gutters">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<div class="row" id="top">
		<div class="col-1"></div>
		<div class="col text-orange text-center mb-3 heavy bigger">
			Accès rapide aux rubriques :
		</div>
		<div class="col-1"></div>
	</div>


	<!-- sousmenu -->
	<div class="row">
		<div class="col-1"></div>
		<div class="col">
			<div class="row text-center">
				<div class="col submenu bg-orange rounded-left"><a href="#analyse" class="subbtn btn-four">Analyse</a></div>
				<div class="col submenu bg-orange"><a href="#reponse" class="subbtn btn-four">Réponse</a></div>
				<div class="col submenu bg-orange"><a href="#statut" class="subbtn btn-four">Statut</a></div>
				<div class="col submenu bg-orange"><a href="#imputation" class="subbtn btn-four">Imputation</a></div>
				<div class="col submenu bg-orange"><a href="#reclamation" class="subbtn btn-four">Réclamation</a></div>
				<div class="col submenu bg-orange"><a href="#gt" class="subbtn btn-four">Produit</a></div>
				<div class="col submenu bg-orange rounded-right"><a href="#typologie" class="subbtn btn-four">Typologie</a></div>
			</div>
		</div>
		<div class="col-1"></div>
	</div>
	<div class="row">
		<div class="col-1"></div>
		<div class="col mt-3">
			Pour masquer/afficher des choix, cliquez sur l'icône <i class="fas fa-eye px-3"></i> et pour ajouter de nouveaux éléments, saisissez vos informations dans les formulaires puis validez en cliquant sur le bouton enregistrer.
		</div>
		<div class="col-1"></div>
	</div>

	<!-- #1 -->
	<div class="row my-3">
		<div class="col"></div>
		<div class="col"><hr></div>
		<div class="col"></div>
	</div>
	<div class="row">
		<div class="col">

			<div class="row">
				<div class="col">
					<h3 class="text-main-blue text-center pb-3" id="analyse">Analyse</h3>
				</div>
			</div>
			<div class="row my-2">
				<div class="col"></div>
				<div class="col"><hr></div>
				<div class="col"></div>
			</div>
			<div class="row">
				<div class="col-4">
					<?php
					echo $tableHeadAnalyse;
					foreach ($analyses as $analyse)
					{
						if($analyse['mask']==0)
						{
							$ico='<a href="data-hide.php?table=analyse&id='.$analyse['id'].'" class="blue-link"><i class="fas fa-eye"></i></a>';
						}
						else
						{
							$ico='<a href="data-show.php?table=analyse&id='.$analyse['id'].'" class="text-dark-grey"><i class="fas fa-eye-slash"></i></a>';

						}
						echo '<tr>';
						echo'<td>'.$ico.'</td>';
						echo'<td>'.$analyse['analyse'].'</td>';
						echo '</tr>';

					}
					echo $tablefoot;
					?>
				</div>
				<div class="col p-2 mx-3">
					<p class="text-blue heavy bigger">Ajouter un type d'analyse : </p>
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="border p-3">
						<div class="form-group">
							<label>Analyse : </label>
							<input type="text" class="form-control" name="analyse-form" required></input>
						</div>
						<div class="pt-4 mt-2 text-right">
							<button type="submit" id="submit" class="btn btn-primary" name="analyse"><i class="fas fa-save pr-3"></i>Enregistrer</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col text-right"><a href="#top" class="link-blue">Retour au menu<i class="fas fa-chevron-circle-up fa-lg pl-3"></i></a></div>
	</div>
	<!-- #2 -->
	<div class="row my-3">
		<div class="col"></div>
		<div class="col"><hr></div>
		<div class="col"></div>
	</div>
	<div class="row">
		<div class="col">
			<div class="row">
				<div class="col">
					<h3 class="text-main-blue text-center pb-3" id="reponse">Réponse</h3>
				</div>
			</div>
			<div class="row my-2">
				<div class="col"></div>
				<div class="col"><hr></div>
				<div class="col"></div>
			</div>
			<div class="row">
				<div class="col-4">
					<?php
					echo $tableHeadReponse;
					foreach ($conclusions as $conclusion)
					{
						if($conclusion['mask']==0)
						{
							$ico='<a href="data-hide.php?table=conclusion&id='.$conclusion['id'].'" class="blue-link"><i class="fas fa-eye"></i></a>';
						}
						else
						{
							$ico='<a href="data-show.php?table=conclusion&id='.$conclusion['id'].'" class="text-dark-grey"><i class="fas fa-eye-slash"></i></a>';

						}
						echo '<tr>';
						echo'<td>'.$ico.'</td>';
						echo'<td>'.$conclusion['conclusion'].'</td>';
						echo '</tr>';

					}
					echo $tablefoot;
					?>
				</div>
				<div class="col p-2 mx-3">
					<p class="text-blue heavy bigger">Ajouter une réponse : </p>
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="shadow p-3">
						<div class="form-group">
							<label>Réponse : </label>
							<input type="text" class="form-control" name="conclusion-form" required></input>
						</div>
						<div class="pt-4 mt-2 text-right">
							<button type="submit" id="submit" class="btn btn-primary" name="conclusion"><i class="fas fa-save pr-3"></i>Enregistrer</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col text-right"><a href="#top" class="link-blue">Retour au menu<i class="fas fa-chevron-circle-up fa-lg pl-3"></i></a></div>
	</div>
	<!-- #3 -->
	<div class="row my-3">
		<div class="col"></div>
		<div class="col"><hr></div>
		<div class="col"></div>
	</div>
	<div class="row">
		<div class="col">
			<div class="row">
				<div class="col">
					<h3 class="text-main-blue text-center pb-3" id="statut">Statut</h3>
				</div>
			</div>
			<div class="row my-2">
				<div class="col"></div>
				<div class="col"><hr></div>
				<div class="col"></div>
			</div>
			<div class="row">
				<div class="col-5">
					<?php
					echo $tableHeadStatut;
					?>
					<?php foreach ($etats as $etat): ?>

						<?php
						if($etat['mask']==0){
							$ico='<a href="data-hide.php?table=etat&id='.$etat['id'].'" class="blue-link"><i class="fas fa-eye"></i></a>';
						}else{
							$ico='<a href="data-show.php?table=etat&id='.$etat['id'].'" class="text-dark-grey"><i class="fas fa-eye-slash"></i></a>';
						}
						?>
						<tr>
							<td><?=$ico?></td>
							<td><?=$etat['etat']?></td>
							<td><?=($etat['occ_etat']==1)?'oui':''?></td>
						</tr>


					<?php endforeach ?>
					<?php
					echo $tablefoot;
					?>
				</div>
				<div class="col p-2 mx-3">
					<p class="text-blue heavy bigger">Ajouter un statut : </p>
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="shadow p-3">
						<div class="form-group">
							<label>Statut : </label>
							<input type="text" class="form-control" name="etat-form" required></input>
						</div>
						<p>Est-ce un statut spécifique au GT occasion ?</p>
						<div class="form-check">
							<input class="form-check-input" type="radio" value="1" id="occ_oui" name="occasion">
							<label class="form-check-label" for="occ_oui">Oui</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" value="0" id="occ_non" name="occasion" checked>
							<label class="form-check-label" for="occ_non">Non</label>
						</div>
						<div class="pt-4 mt-2 text-right">
							<button type="submit" id="submit" class="btn btn-primary" name="etat"><i class="fas fa-save pr-3"></i>Enregistrer</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col text-right"><a href="#top" class="link-blue">Retour au menu<i class="fas fa-chevron-circle-up fa-lg pl-3"></i></a></div>
	</div>
	<!-- #4 -->
	<div class="row my-3">
		<div class="col"></div>
		<div class="col"><hr></div>
		<div class="col"></div>
	</div>
	<div class="row">
		<div class="col">
			<div class="row">
				<div class="col">
					<h3 class="text-main-blue text-center pb-3" id="imputation">Imputation</h3>
				</div>
			</div>
			<div class="row my-2">
				<div class="col"></div>
				<div class="col"><hr></div>
				<div class="col"></div>
			</div>
			<div class="row">
				<div class="col-4">
					<?php
					echo $tableHeadImputation;
					foreach ($imputations as $imputation)
					{
						if($imputation['mask']==0)
						{
							$ico='<a href="data-hide.php?table=imputation&id='.$imputation['id'].'" class="blue-link"><i class="fas fa-eye"></i></a>';
						}
						else
						{
							$ico='<a href="data-show.php?table=imputation&id='.$imputation['id'].'" class="text-dark-grey"><i class="fas fa-eye-slash"></i></a>';

						}
						echo '<tr>';
						echo'<td>'.$ico.'</td>';
						echo'<td>'.$imputation['imputation'].'</td>';
						echo '</tr>';

					}
					echo $tablefoot;
					?>
				</div>
				<div class="col p-2 mx-3">
					<p class="text-blue heavy bigger">Ajouter une imputation : </p>
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="shadow p-3">
						<div class="form-group">
							<label>Imputation : </label>
							<input type="text" class="form-control" name="imputation-form" required></input>
						</div>
						<div class="pt-4 mt-2 text-right">
							<button type="submit" id="submit" class="btn btn-primary" name="imputation"><i class="fas fa-save pr-3"></i>Enregistrer</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col text-right"><a href="#top" class="link-blue">Retour au menu<i class="fas fa-chevron-circle-up fa-lg pl-3"></i></a></div>
	</div>
	<!-- #5 -->
	<div class="row my-3">
		<div class="col"></div>
		<div class="col"><hr></div>
		<div class="col"></div>
	</div>
	<div class="row">
		<div class="col">
			<div class="row">
				<div class="col">
					<h3 class="text-main-blue text-center pb-3" id="reclamation">Réclamations (magasin)</h3>
				</div>
			</div>
			<div class="row my-2">
				<div class="col"></div>
				<div class="col"><hr></div>
				<div class="col"></div>
			</div>
			<div class="row">
				<div class="col-6">
					<?php
					echo $tableHeadReclamation;
					foreach ($reclamations as $reclamation)
					{
						if($reclamation['mask']==0)
						{
							$ico='<a href="data-hide.php?table=reclamation&id='.$reclamation['id'].'" class="blue-link"><i class="fas fa-eye"></i></a>';
						}
						else
						{
							$ico='<a href="data-show.php?table=reclamation&id='.$reclamation['id'].'" class="text-dark-grey"><i class="fas fa-eye-slash"></i></a>';

						}
						echo '<tr>';
						echo'<td>'.$ico.'</td>';
						echo'<td>'.$reclamation['reclamation'].'</td>';
						echo'<td>'.$reclamation['reclamation_contrainte'].'</td>';
						echo'<td class="text-center"><a href="ex-reclamation-modify.php?id='.$reclamation['id'].'"><i class="fas fa-pen"></i></a></td>';
						echo '</tr>';

					}
					echo $tablefoot;
					?>
				</div>
				<div class="col p-2 mx-3">
					<p class="text-blue heavy bigger">Ajouter une réclamation : </p>
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="shadow p-3">
						<div class="form-group">
							<label>Réclamation : </label>
							<input type="text" class="form-control" name="reclamation-form" required></input>
						</div>
						<p class="heavy text-blue">Imposer une contrainte :</p>

						<div class="form-group">
							<select class="form-control" name="contrainte" id="contrainte">
								<option value="">Pas de contrainte</option>

								<?php foreach ($arReclamContrainte as $keyContrainte => $reclamContrainte): ?>
									<option value="<?=$keyContrainte?>"><?=$arReclamContrainte[$keyContrainte]?></option>
								<?php endforeach ?>
							</select>
						</div>
						<div class="pt-4 mt-2 text-right">
							<button type="submit" id="submit" class="btn btn-primary" name="reclamation"><i class="fas fa-save pr-3"></i>Enregistrer</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col text-right"><a href="#top" class="link-blue">Retour au menu<i class="fas fa-chevron-circle-up fa-lg pl-3"></i></a></div>
	</div>
	<!-- #6 -->
	<div class="row my-3">
		<div class="col"></div>
		<div class="col"><hr></div>
		<div class="col"></div>
	</div>
	<div class="row">
		<div class="col">
			<div class="row">
				<div class="col">
					<h3 class="text-main-blue text-center pb-3" id="gt">Type de produit</h3>
				</div>
			</div>
			<div class="row my-2">
				<div class="col"></div>
				<div class="col"><hr></div>
				<div class="col"></div>
			</div>
			<div class="row">
				<div class="col-4">
					<?php
					echo $tableHeadgt;
					foreach ($gts as $gt)
					{
						if($gt['mask']==0)
						{
							$ico='<a href="data-hide.php?table=gt&id='.$gt['id'].'" class="blue-link"><i class="fas fa-eye"></i></a>';
						}
						else
						{
							$ico='<a href="data-show.php?table=gt&id='.$gt['id'].'" class="text-dark-grey"><i class="fas fa-eye-slash"></i></a>';

						}
						echo '<tr>';
						echo'<td>'.$ico.'</td>';
						echo'<td>'.$gt['gt'].'</td>';
						echo '</tr>';

					}
					echo $tablefoot;
					?>
				</div>
				<div class="col p-2 mx-3">
					<p class="text-blue heavy bigger">Ajouter un type de produit : </p>
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="shadow p-3">
						<div class="form-group">
							<label>Type de produit : </label>
							<input type="text" class="form-control" name="gt-form" required></input>
						</div>
						<div class="pt-4 mt-2 text-right">
							<button type="submit" id="submit" class="btn btn-primary" name="gt"><i class="fas fa-save pr-3"></i>Enregistrer</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col text-right"><a href="#top" class="link-blue">Retour au menu<i class="fas fa-chevron-circle-up fa-lg pl-3"></i></a></div>
	</div>


	<!-- #7 -->
	<div class="row my-3">
		<div class="col"></div>
		<div class="col"><hr></div>
		<div class="col"></div>
	</div>
	<div class="row">
		<div class="col">
			<div class="row">
				<div class="col">
					<h3 class="text-main-blue text-center pb-3" id="typologie">Typologie</h3>
				</div>
			</div>
			<div class="row my-2">
				<div class="col"></div>
				<div class="col"><hr></div>
				<div class="col"></div>
			</div>
			<div class="row">
				<div class="col-4">
					<?php
					echo $tableHeadTypo;
					foreach ($typos as $typo)
					{
						if($typo['mask']==0)
						{
							$ico='<a href="data-hide.php?table=typo&id='.$typo['id'].'" class="blue-link"><i class="fas fa-eye"></i></a>';
						}
						else
						{
							$ico='<a href="data-show.php?table=typo&id='.$typo['id'].'" class="text-dark-grey"><i class="fas fa-eye-slash"></i></a>';

						}
						echo '<tr>';
						echo'<td>'.$ico.'</td>';
						echo'<td>'.$typo['typo'].'</td>';
						echo '</tr>';

					}
					echo $tablefoot;
					?>
				</div>
				<div class="col p-2 mx-3">
					<p class="text-blue heavy bigger">Ajouter une typologie : </p>
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="shadow p-3">
						<div class="form-group">
							<label>typologie : </label>
							<input type="text" class="form-control" name="typo-form" required></input>
						</div>
						<div class="pt-4 mt-2 text-right">
							<button type="submit" id="submit" class="btn btn-primary" name="typo"><i class="fas fa-save pr-3"></i>Enregistrer</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col text-right"><a href="#top" class="link-blue">Retour au menu<i class="fas fa-chevron-circle-up fa-lg pl-3"></i></a></div>
	</div>
	<div class="row my-5">
		<div class="col">
			<p class="text-center "><a href="exploit-ltg.php" class="btn btn-primary"><i class="fas fa-arrow-alt-circle-left pr-3"></i>Retour</a></p>
		</div>
	</div>

</div>








<?php

require '../view/_footer-bt.php';

?>