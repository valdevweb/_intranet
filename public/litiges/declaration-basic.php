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

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
require "../../functions/stats.fn.php";
$descr="saisie déclaration litige" ;
$page=basename(__file__);
$action="consultation";
// addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
addRecord($pdoStat,$page,$action, $descr, 101);



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function search($pdoQlik)
{
	// $req=$pdoLitige->prepare("SELECT * FROM statsventeslitiges  WHERE concat(facture,palette,gencod,article) LIKE :search AND galec= :galec");
	$req=$pdoQlik->prepare("SELECT * FROM statsventeslitiges  WHERE concat( concat('0',facture),palette) LIKE :search AND galec= :galec ORDER BY article");
	$req->execute(array(
		':search' =>'%'.$_POST['search_strg'] .'%',
		':galec'	=>$_SESSION['id_galec']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

// ca sou bt déclare pour un mag, on récupère le nom du mag
function getMagName($pdoBt)
{
	$req=$pdoBt->prepare("SELECT mag FROM sca3 WHERE galec=:galec");
	$req->execute(array(
		':galec'		=>$_SESSION['id_galec']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getMagIdwebuser($pdoUser)
{
	$req=$pdoUser->prepare("SELECT id FROM users WHERE galec=:galec");
	$req->execute(array(
		':galec'		=>$_SESSION['id_galec']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
if($_SESSION['type']=='btlec')
{
	$magId=getMagIdwebuser($pdoUser);
	$magId=$magId['id'];
}
else
{
	$magId=$_SESSION['id_web_user'];
}

function insertDossier($pdoLitige, $numDossier,$magId)
{
	// par défaut l'état est à 0 = ouvert
	if(isset($_POST['rapid']) && $_POST['rapid']=="oui")
	{
		$vingtquatre=1;
	}
	else{
		$vingtquatre=0;
	}
	if(isset($_POST['date_bt']) && !empty($_POST['date_bt']))
	{
		$dateDecl=$_POST['date_bt'];
	}
	else
	{
		$dateDecl=	date('Y-m-d H:i:s');
	}
	$req=$pdoLitige->prepare("INSERT INTO dossiers(date_crea,user_crea,nom,galec,id_web_user,vingtquatre, dossier) VALUES(:date_crea,:user_crea,:nom, :galec, :id_web_user, :vingtquatre, :dossier)");
	$req->execute(array(
		':date_crea'		=>$dateDecl,
		':user_crea'		=>$_SESSION['id_web_user'],
		':nom'				=>$_POST['nom'],
		':galec'			=>$_SESSION['id_galec'],
		':id_web_user'		=>$magId,
		':vingtquatre'		=>$vingtquatre,
		':dossier'		=>$numDossier,
	));
	return $pdoLitige->lastInsertId();
}



function updateDossier($pdoLitige,$numDossier, $lastInsertId)
{
	$req=$pdoLitige->prepare("UPDATE dossiers SET dossier= :dossier WHERE id= :id");
	$req->execute(array(
		':dossier'		=>$numDossier,
		':id'			=>$lastInsertId
	));
	$row=$req->rowCount();
	return	$row;
}

function getSelectedDetails($pdoQlik,$id)
{
	$req=$pdoQlik->prepare("SELECT * FROM statsventeslitiges WHERE id= :id");
	$req->execute(array(
		':id'	=>$id

	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function addDetails($pdoLitige, $lastInsertId,$numDossier,$palette,	$facture,$dateFacture, $article, $ean,$dossierG, $descr, $qteC,	$tarif, $fou, $cnuf)
{
	$req=$pdoLitige->prepare("INSERT INTO details(id_dossier, dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, fournisseur, cnuf) VALUES(:id_dossier, :dossier, :palette, :facture, :date_facture, :article, :ean, :dossier_gessica, :descr, :qte_cde, :tarif, :fournisseur, :cnuf)");
	$req->execute(array(
		':id_dossier'	=>$lastInsertId,
		':dossier'		=>$numDossier,
		':palette'		=>$palette,
		':facture'		=>$facture,
		':date_facture'	=>$dateFacture,
		':article'		=>$article,
		':ean'			=>$ean,
		':dossier_gessica'	=>$dossierG,
		':descr'		=>$descr,
		':qte_cde'		=> $qteC,
		':tarif'		=>$tarif,
		':fournisseur'	=>$fou,
		':cnuf'			=>$cnuf

	));
	$row=$req->rowCount();
	return	$row;
}


// le numéro de dossier du litige et non l'id du litige
function getLastNumDossier($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT dossier FROM dossiers ORDER BY dossier DESC LIMIT 1");
	$req->execute();
	return $req->fetch(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}

//------------------------------------------------------
//			AFFICHAGE RES RECHERCHE
//------------------------------------------------------
if(isset($_POST['submit']))
{
	$dataSearch=search($pdoQlik);
	$searchStr=$_POST['search_strg'];
}
//------------------------------------------------------
//			TRAITEMENT CHOIX ARTICLES
//------------------------------------------------------
$ids=[];
$errors=[];
$success=[];
//
if(isset($_POST['choose']))
{

//  on ne veut récuperer que les id donc on supprime les valeurs des champ submit, date, etc
	foreach ($_POST as $key => $value)
	{
		if($key!='choose' && $key!='selectAll' && $key!='rapid' && $key !='nom' && $key !='date_bt' && $key != 'num_dossier_form' && $key != 'palette_complete')
		{
			$ids[]=$key;
		}
	}

// 1- creation du dossier
	if(count($ids)>0)
	{

		// soit le numéro de dossier a été saisi soit, on doit le calculer
		if(!empty($_POST['num_dossier_form']))
		{
			$numDossier=$_POST['num_dossier_form'];
			$lastInsertId=insertDossier($pdoLitige,$numDossier, $magId);

		}
		else
		{
			// si pas de numéro de dossier imposé, on prend le der num et on ajoute 1
			$numDossier=getLastNumDossier($pdoLitige);
			$numDossier=$numDossier['dossier'];
			// il faut vérifier que l'on a pas changé d'année
			// prend les 2 1er caractère du numdossier pour les comparer à l'année actuelle
			// si différent de l'anneé actuelle, on a changé d'année par rapport au der dossier
			// il faut donc créer le 1er numdossier
			$yearDossier=substr($numDossier,0,2);
			if($yearDossier==date('y'))
			{
				// pas de chg d'année, on prend le der num dossier, oon ajoute 1
				$numDossier=$numDossier +1;
			}
			else
			{
				$numDossier=date('y').'001';

			}
			$lastInsertId=insertDossier($pdoLitige,$numDossier, $magId);
		}
		// créa du dossier (sans numéro pour l'instant)
		if($lastInsertId>0)
		{
			$sucess[]="ajout du dossier réussie";
		}
		else
		{
			$errors[]="Impossible de créer le dossier";

		}
	}
	else
	{
		$errors[]="Merci de sélectionner au moins une ligne";
	}
// 2- ajout des ref art, num, num fac, date fac, n°palette, qte originale, num dossier,gencod, id_web_user, btlec, galec, deno
	if(count($errors)==0)
	{
		$added=0;
		$nbArticle=count($ids);
		for ($i=0; $i <$nbArticle ; $i++)
		{
			$art=getSelectedDetails($pdoQlik, $ids[$i]);
			$dateFact=date('Y-m-d H:i:s',strtotime($art['date_mvt']));
			$detail=addDetails($pdoLitige, $lastInsertId,$numDossier,$art['palette'],$art['facture'],$dateFact, $art['article'], $art['gencod'],$art['dossier'], $art['libelle'], $art['qte'],$art['tarif'], $art['fournisseur'], $art['cnuf']);
			if($detail>0)
			{
				$added++;
			}
			else{
				$errors[]="erreur à l'enregistrement";
			}

		}
		if($added>0 && !isset($_POST['palette_complete']))
		{
			header('Location:declaration-detail.php?id='.$lastInsertId);
		}
		elseif ($added>0 && isset($_POST['palette_complete']))
		{
			header('Location:declaration-detail-palette.php?id='.$lastInsertId);
		}
	}
}
$magtxt="";
if($_SESSION['type']=='btlec')
{
	$mag=getMagName($pdoBt);
	$magtxt="<span class='text-reddish'>pour ".$mag['mag']."</span>";

}

// on va utiliser l'id pour enregistrer les produits sélectionnés sachant qu'à chaque import de la base, il changera

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
	<h1 class="text-main-blue py-5 ">Déclarer un litige <?= $magtxt?></h1>

	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>

		<div class="col">
		</div>
		<div class="col-lg-1 col-xxl-2"></div>

	</div>
	<!-- start row -->
	<div class="row no-gutters">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col">
			<div class="bg-alert bg-alert-red">ATTENTION !<br>Tout litige doit être déclaré dans les <strong>48 heures MAXIMUM suivant la réception</strong> des produits</div>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<!-- ./row -->
	<!-- ./row -->
	<!-- FORMULAIRE DE RECHERCHE -->
	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col bg-alert bg-alert-primary">
			<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" id="search">
				<!-- start row -->
				<!-- start row -->
				<div class="row pb-3">
					<div class="col">
						<p>Saisissez le numéro de palette ou à défaut, le numéro de facture concernée par le litige</p>
					</div>
				</div>
				<!-- ./row -->

				<div class="row pl-5">
					<div class="col">
						<div class="form-group">
							<input type="text" class="form-control" name="search_strg" required>
						</div>
					</div>
					<div class="col">
						<p class="text-left"><button class="btn btn-primary" type="submit" name="submit">Rechercher</button></p>
						<div id="waitun"></div>
					</div>
				</div>
			</form>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>



	<!-- start row -->
	<div class="row no-gutters">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<!-- ./row -->
	<?php
	ob_start();
	?>
	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col bg-alert bg-alert-grey">
			<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" id="submit">
				<p class="text-center alert-title-grey">Votre recherche : <span class="text-main-blue">"<?=  isset($searchStr) ? $searchStr : '' ?>"</span></p>
				<p  class="text-center heavy alert-title-grey">Résultats :</p>
				<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">1</span>Sélectionnez le ou les articles sur lesquels vous avez un litige à déclarer</p>
				<div class="alert alert-light">
					<p class="text-main-blue">Si la palette entière est concernée par le litige, merci de cocher ci dessous l'option "palette entière"</p>
					<div class="form-check">
						<input type="checkbox" class="form-check-input" id="checkpalette" name="palette_complete">
						<label class="form-check-label" for="checkAll">Palette entière</label>
					</div>

				</div>

				<!-- <p class="text-main-blue font-italic closer"><i class="fas fa-info-circle"></i>Vous pouvez trier les résultats en cliquant sur les entêtes de colonne</p> -->
				<div class="alert alert-light"><i class="fas fa-info-circle pr-3"></i>Vous pouvez trier les résultats en cliquant sur les entêtes de colonne</div>

				<table class="table table-striped border border-white">
					<thead class="thead-dark">
						<tr>
							<th class="sortable">Date facture</th>
							<th class="sortable">Facture</th>
							<th class="sortable">Palette</th>
							<th class="sortable">ean</th>
							<th class="sortable">Article</th>
							<th class="sortable">Désignation</th>
							<th class="sortable"><i class="fas fa-times-circle"></i></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(empty($dataSearch))
						{
								echo '<p>La palette que vous recherchez n\'a pas été trouvée. Elle ne vous était pas destinée ? Veuillez vous rendre sur <a href="declaration-horsqlik.php">cette page</a></p>';
						}
						else
						{
							foreach ($dataSearch as $sResult)
							{
								echo '<tr>';
								echo'<td>'.$sResult['date_mvt'].'</td>';
								echo'<td>'.$sResult['facture'].'</td>';
								echo'<td>'.$sResult['palette'].'</td>';
								echo'<td>'.$sResult['gencod'].'</td>';
								echo'<td>'.$sResult['article'].'</td>';
								echo'<td>'.$sResult['libelle'].'</td>';
								echo'<td>';
								echo '<div class="form-check article"><input class="form-check-input" type="checkbox" name="'.$sResult['id'].'"></div>';
								echo '</td></tr>';

							}


						}
						?>
					</tbody>
				</table>
				<p>Le produit que vous avez reçu n'apparaît pas dans la liste et vous avez bien reçu tous les autres produits commandés ? Veuillez vous rendre sur <a href="declaration-horsqlik.php">cette page</a></p>
				<!-- <p> -->
					<div class="alert alert-light">
						<div class="form-check text-right">
							<input type="checkbox" class="form-check-input" id="checkAll">
							<label class="form-check-label" for="checkAll">Sélectionner tout / désélectionner tout</label>
						</div>
					</div>
					<!-- </p> -->
					<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">2</span>S'agit-il d'une livraison 24/48h ?</p>
					<div class="alert alert-light">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="rapid" value="oui" id="rapidoui">
							<label class="form-check-label" for="rapidoui">Oui</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="rapid" value="non" id="rapidnon">
							<label class="form-check-label" for="rapidnon">Non</label>
						</div>
					</div>
					<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">3</span>Nom de l'interlocuteur</p>
					<div class="form-group">
						<input type="text" class="form-control"  name="nom" required>
					</div>

					<?php
					ob_start();
					?>
					<div class="row">
						<div class="col">
							<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">4</span>Date de déclaration</p>
						</div>
						<div class="col">
							<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">5</span>Numéro de dossier : </p>
						</div>
					</div>
					<div class="row">
						<div class="col-4">
							<div class="alert alert-light ">
								<div class="form-group pt-2">
									<input type="date" class="form-control" name="date_bt">
								</div>
							</div>
						</div>
						<div class="col-2"></div>
						<div class="col-4">
							<div class="alert alert-light ">
								<div class="form-group pt-2">
									<input type="text" class="form-control" name="num_dossier_form">
								</div>
							</div>

						</div>
					</div>


					<?php
					$datebtform=ob_get_contents();
					ob_end_clean();
					if($_SESSION['type']=="btlec")
					{
						echo $datebtform;
					}

					?>

					<p class="text-right"><button class="btn btn-primary" type="submit" name="choose" id="choose">Valider</button></p>


				</form>
			</div>
			<div class="col-lg-1 col-xxl-2"></div>
		</div>
		<?php
		$dataMag=ob_get_contents();
		ob_end_clean();
		if(isset($_POST['submit'])){
			echo $dataMag;
		}


		?>
		<!-- ./row -->
	</div>
	<script src="../js/sorttable.js"></script>
	<script type="text/javascript">
		$("#checkAll").click(function () {
			$('.article input:checkbox').not(this).prop('checked', this.checked);
			// $('input:checkbox').(#checkpalette).prop('unchecked', this.checked);
		});
		$("#checkpalette").click(function () {
			$('input:checkbox').not(this).prop('checked', this.checked);
		});
		$("#choose").click(function() {
			if ($('input[name="rapid"]:checked').length == 0) {
				alert('Vous devez préciser si il s\'agit d\'une livraison 24/48h');
				return false; }

			});
		$("#search").submit(function( event )
		{
			$("#waitun" ).append('<i class="fas fa-spinner fa-spin"></i><span class="pl-3">Merci de patienter pendant la recherche</span>')
		});

		$("#submit").submit(function( event )
		{
			$("#waitdeux" ).append('<i class="fas fa-spinner fa-spin"></i><span class="pl-3">Merci de patienter</span>')

		});
	</script>


	<?php

	require '../view/_footer-bt.php';

	?>