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
//			INFO
//------------------------------------------------------
/*
maj du 26/06/2019
pb rencontré : il arrive que des magasins d'arrêtent au milieu d'une déclaration, on a ainsi des dossiers litiges ouverts mais non complet
on souhaite que seuls les dossiers finalisés soient enregistrés (arrivé sur la page récap)
remède : cré de 2 tables temporaires : dossiers_temp, details_temp

=>pb le numéro de litige est un numéro calculé et non un id (nu)



 */




//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
//recherche dans statsvente facture ou palette
function search($pdoQlik)
{
	$req=$pdoQlik->prepare("SELECT * FROM statsventeslitiges  WHERE concat( concat('0',facture),palette) LIKE :search AND galec= :galec ORDER BY article,dossier");
	$req->execute(array(
		':search' =>'%'.$_POST['search_strg'] .'%',
		':galec'	=>$_SESSION['id_galec']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

// recherche les palettes sélectionné dans page declaration-robbery (SESSION)
function getPaletteForRobbery($pdoQlik)
{
	$placeholders=array_fill(0, count($_SESSION['palette']), ' palette = ? OR ');
	$placeholders[count($_SESSION['palette']) -1]= 'palette = ? ';
	$placeholders=implode(' ',$placeholders);
	$req=$pdoQlik->prepare("SELECT * FROM statsventeslitiges  WHERE $placeholders ORDER BY palette, article");
	$req->execute($_SESSION['palette']);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

// si on a un tarif à 0, on peut-etre un tête de box, on vérifie donc si couple code art et dossier est dans assortiment
function checkBox($pdoQlik, $dossier,$article)
{
	$req=$pdoQlik->prepare("SELECT * FROM assortiments WHERE `SCEBFAST.AST-ART`= :article AND `SCEBFAST.DOS-COD`= :dossier ");
	$req->execute(array(
		':dossier' =>$dossier,
		':article'	=>$article
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

// si box, on récupère le contenu du box dans la table assortiment (voir powerpoint)
function getBoxContent($pdoQlik,$dossier, $article)
{
	$req=$pdoQlik->prepare("SELECT `SCEBFAST.AST-ART` as tete FROM assortiments WHERE `SCEBFAST.ART-COD`= :article AND `SCEBFAST.DOS-COD` =:dossier");
	$req->execute(array(
		':article'	=>$article,
		':dossier'	=>$dossier
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
//  on vérifie si art = tete de box
function getBoxHead($pdoQlik,$dossier, $article)
{
	$req=$pdoQlik->prepare("SELECT * FROM assortiments WHERE `SCEBFAST.AST-ART`= :article AND `SCEBFAST.DOS-COD` =:dossier");
	$req->execute(array(
		':article'	=>$article,
		':dossier'	=>$dossier
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}

// cas ou bt déclare pour un mag, on récupère le nom du mag
function getMagName($pdoBt)
{
	$req=$pdoBt->prepare("SELECT mag FROM sca3 WHERE galec=:galec");
	$req->execute(array(
		':galec'		=>$_SESSION['id_galec']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
// recup idwebuser du mag
function getMagIdwebuser($pdoUser)
{
	$req=$pdoUser->prepare("SELECT id FROM users WHERE galec=:galec");
	$req->execute(array(
		':galec'		=>$_SESSION['id_galec']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

// initialisation des variables suivant le user connecté
if($_SESSION['type']=='btlec')
{
	$magId=getMagIdwebuser($pdoUser);
	$magId=$magId['id'];
}
else
{
	$magId=$_SESSION['id_web_user'];
}

// création du dossier dans la table ($idRobbery = 0 si pas vol, 1 si vol)
function insertDossier($pdoLitige, $numDossier,$magId, $idRobbery)
{

	if(isset($_POST['date_bt']) && !empty($_POST['date_bt']))
	{
		$dateDecl=$_POST['date_bt'];
	}
	else
	{
		$dateDecl=	date('Y-m-d H:i:s');
	}

	$req=$pdoLitige->prepare("INSERT INTO dossiers_temp(date_crea,user_crea,nom,galec,id_web_user, dossier, id_robbery) VALUES(:date_crea,:user_crea,:nom, :galec, :id_web_user, :dossier, :id_robbery)");
	$req->execute(array(
		':date_crea'		=>$dateDecl,
		':user_crea'		=>$_SESSION['id_web_user'],
		':nom'				=>$_POST['nom'],
		':galec'			=>$_SESSION['id_galec'],
		':id_web_user'		=>$magId,
		':dossier'		=>$numDossier,
		':id_robbery'		=>$idRobbery,
	));
	return $pdoLitige->lastInsertId();
}


// recupère les infos articles dans la base statventes
function getSelectedDetails($pdoQlik,$id)
{
	$req=$pdoQlik->prepare("SELECT * FROM statsventeslitiges WHERE id= :id");
	$req->execute(array(
		':id'	=>$id

	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
// ajoute info produits dans table detail
function addDetails($pdoLitige, $lastInsertId,$numDossier,$palette,	$facture,$dateFacture, $article, $ean,$dossierG, $descr, $qteC,	$tarif, $fou, $cnuf,$boxTete,$boxDetail, $puv,$pul)
{
	$req=$pdoLitige->prepare("INSERT INTO details_temp(id_dossier, dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, fournisseur, cnuf, box_tete,box_art, puv, pul) VALUES(:id_dossier, :dossier, :palette, :facture, :date_facture, :article, :ean, :dossier_gessica, :descr, :qte_cde, :tarif, :fournisseur, :cnuf, :box_tete, :box_art, :puv, :pul)");
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
		':cnuf'			=>$cnuf,
		':box_tete'		=>$boxTete,
		':box_art'		=>$boxDetail,
		':puv'			=>$puv,
		':pul'			=>$pul
	));
	$row=$req->rowCount();
	return	$row;
}




// recup poids dans la base article (info non présente dans statsvente mais obligatoire pour les déclarations de vol )
function getPoids($pdoQlik, $art,$dossier)
{
	$req=$pdoQlik->prepare("SELECT `GESSICA.PoidsBrutUV` as puv, `GESSICA.PoidsBrutUL` as pul FROM basearticles  WHERE `GESSICA.CodeArticle` = :article AND `GESSICA.CodeDossier` = :dossier LIMIT 1");
	$req->execute([
		':article'	=>	$art,
		':dossier'	=>$dossier

	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

// ajout d'un dossier vol => recupère l'id  pour le mettre ensuite dans table dossiers id_robbery
function insertRobbery($pdoLitige)
{
	$req=$pdoLitige->prepare("INSERT INTO robbery (date_saisie) VALUES (:date_saisie)");
	$req->execute([
		':date_saisie' =>date('Y-m-d H:i:s'),

	]);
	return $pdoLitige->lastInsertId();
}

//------------------------------------------------------
//			AFFICHAGE DES RECHERCHES
//------------------------------------------------------
if(isset($_POST['submit']))
{
	$i=1;
	$arrI=[];
	$dataSearch=search($pdoQlik);
	$searchStr=$_POST['search_strg'];
	// on récupère les tete de box et contenu de box dans ces tableaux pour plus tard dans l'insertion de donnée pourvoir préciser si box
	$boxTete=[];
	$boxDetail=[];
	foreach ($dataSearch as $key =>$data)
	{
		$dataSearch[$key]['box-tete']='';
		$dataSearch[$key]['box-detail']='';

		if($data['tarif']==0)
		{
			$teteboxFound=checkBox($pdoQlik, $data['dossier'], $data['article']);
			if(!empty($teteboxFound))
			{
				$dataSearch[$key]['box-tete']=$i;
				$arrI[]=$i;
				// pour faciliter le tri on assigne le code  article -1 à la tête de box
				$dataSearch[$key]['box-detail']= $data['article']-1;
				$boxTete[]=$data['article'];
				$i++;
				// echo $data['article'] .' : '.$data['tarif'] .' - '.$dataSearch[$key]['box-tete'] .' - '.$dataSearch[$key]['box-detail'] .'<br>';
			}
		}

	}
	$arrI=implode(',', $arrI);

	foreach ($dataSearch as $key =>$data)
	{
		// on verifie chaque couple code article et dossier, si il est dans la table assortiment => si oui article= detail box
		$boxContent=getBoxContent($pdoQlik, $data['dossier'], $data['article']);

			// si article =detail box, on mette le code article de la tete de box dans boxdetail
		if(!empty($boxContent))
		{
			$boxDetail[]=$data['article'];
			$dataSearch[$key]['box-detail']=$boxContent['tete'];
		}

	}

	$boxExist=array_sum(array_column($dataSearch, 'box-tete'));
	if($boxExist>=1)
	{
		function nameSort($a, $b)
		{
			return strcmp($a['box-detail'], $b['box-detail']);
		}
		usort($dataSearch, 'nameSort');

	}
}
// si on vient de la page déclaration de vol et que l'on a récupéré les numéros de palettes volées, on lance la recherche directement
if(isset($_SESSION['palette']))
{
	$_POST['submit']=true;
	$dataSearch=getPaletteForRobbery($pdoQlik);
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
		if($key!='choose' && $key!='selectAll'  && $key !='nom' && $key !='date_bt' && $key != 'num_dossier_form' && $key != 'palette_complete')
		{
			$ids[]=$key;
		}
	}

// 1- creation du dossier
	if(count($ids)>0)
	{
		//si on a une variable de session vol-id, il faut vérifier ce qu'elle renvoie :
		//si elle est égale à zéro, c'est une nouvelle décalration de vol donc on la créé et on récupère son id,
		//sinon c'est la suite d'une décalartion existante donc on récupère la valeur de session['id_vol']

		if(isset($_SESSION['vol-id']))
		{
			if($_SESSION['vol-id']==0){
				//ajout nouveau vol dans la table robbery
				$idRobbery=insertRobbery($pdoLitige);
				if($idRobbery>=0){
				}
				else{
					$errors[]="impossible d'ajouter le vol";
				}
				$_SESSION['vol-id']=$idRobbery;
			}
			else{
				$idRobbery=$_SESSION['vol-id'];
			}
		}
		else
		{
			$idRobbery=null;
		}

		// soit le numéro de dossier a été saisi, on écrit dans la table finale => c'est un utilisateur btlec donc il est censé terminer sa déclaration
		// soit il n'a pas été saisi, on
		if(!empty($_POST['num_dossier_form']))
		{
			// le numéro de dossier sera ecrasé au moment de la recopie de la table temporaire vers la table active
			// on mémorise donc le numéro de dossier dans une variable session
			$_SESSION['dossier_litige']=$_POST['num_dossier_form'];
			$numDossier=9999;
			$lastInsertId=insertDossier($pdoLitige,$numDossier, $magId, $idRobbery);
		}
		else
		{

			// si pas de numéro de dossier imposé, on prend le der num et on ajoute 1
			$numDossier=9999;

			$lastInsertId=insertDossier($pdoLitige,$numDossier, $magId, $idRobbery);

		}
	// créa du dossier (sans numéro officiel pour l'instant)
		if($lastInsertId>0){
			$sucess[]="ajout du dossier réussie";
		}
		else{
			$errors[]="Impossible de créer le dossier";
		}
	}
	else
	{
		// pas d'article sélectionné
		$errors[]="Merci de sélectionner au moins une ligne";
	}
// 2- ajout des ref art, num, num fac, date fac, n°palette, qte originale, num dossier,gencod, id_web_user, btlec, galec, deno
	if(count($errors)==0)
	{
		$tete=0;
		$added=0;
		$nbArticle=count($ids);
		for ($i=0; $i <$nbArticle ; $i++)
		{
			$art=getSelectedDetails($pdoQlik, $ids[$i]);
			$isBoxHead=getBoxHead($pdoQlik,$art['dossier'], $art['article']);
			if(!empty($isBoxHead))
			{
				$tete=1;
				$detailbox=NULL;
				$tetedebox=$art['article'];
			}
			else
			{
				$tete=0;
				$detailbox=NULL;
			}

			$isBoxDetail=getBoxContent($pdoQlik,$art['dossier'], $art['article']);
			if(!empty($isBoxDetail))
			{
				$detailbox=$tetedebox;
			}
			else
			{
				$detailbox=NULL;
			}

			$dateFact=date('Y-m-d H:i:s',strtotime($art['date_mvt']));
			if(isset($_SESSION['vol-id']))
			{
				$poids=getPoids($pdoQlik,$art['article'],$art['dossier']);
				if(count($poids==1))
				{
					$puv=$poids['puv'];
					$pul=$poids['pul'];
				}
				else
				{
					$errors[]="ATTENTION, les poids n'ont pas pu être récupérés dans la base article";
					$puv=null;
					$pul=null;
				}
			}
			else
			{
				$puv=null;
				$pul=null;

			}

			$detail=addDetails($pdoLitige, $lastInsertId,$numDossier,$art['palette'],$art['facture'],$dateFact, $art['article'], $art['gencod'],$art['dossier'], $art['libelle'], $art['qte'],$art['tarif'], $art['fournisseur'], $art['cnuf'],$tete,$detailbox,$puv,$pul );
			if($detail>0)
			{
				$added++;
			}
			else{
				$errors[]="erreur à l'enregistrement";
			}
		}
		// suivant type de déclaration (palette complète ou non), on ne renvoie pas sur la même page
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
// ajoute nom du mag au titre si dclaration faite par btlec
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
	<div class="row no-gutters">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col">
			<div class="bg-alert bg-alert-red">ATTENTION !<br>Assurez vous d'avoir toutes les informations en votre possession avant de faire votre déclaration de litige, <strong>toute déclaration non menée jusqu'au bout sera supprimée</strong></div>
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
				<p class="text-center alert-title-grey"><span class="text-main-blue"><?=  isset($searchStr) ? 'Votre recherche : '.$searchStr : '' ?></span></p>
				<p  class="text-center heavy alert-title-grey">Résultats :</p>
				<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">1</span>Sélectionnez le ou les articles sur lesquels vous avez un litige à déclarer</p>
				<div class="alert alert-light">
					<p class="text-main-blue">Dans le cas d'une <span class="text-reddish">inversion de palette ou d'une palette manquante ou en excédent,</span> merci de cocher ci dessous l'option "palette entière"</p>
					<div class="form-check">
						<input type="checkbox" class="form-check-input" id="checkpalette" name="palette_complete">
						<label class="form-check-label" for="checkAll">Palette entière</label>
					</div>
				</div>




				<!-- <p class="text-main-blue font-italic closer"><i class="fas fa-info-circle"></i>Vous pouvez trier les résultats en cliquant sur les entêtes de colonne</p> -->
				<!-- <div class="alert alert-light"><i class="fas fa-info-circle pr-3"></i>Vous pouvez trier les résultats en cliquant sur les entêtes de colonne</div> -->

				<table class="table table-striped border border-white">
					<thead class="thead-dark">
						<tr>
							<th>Date facture</th>
							<th>Facture</th>
							<th>Palette</th>
							<th>ean</th>
							<th>Article</th>
							<th>Désignation</th>
							<th><i class="fas fa-times-circle"></i></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(empty($dataSearch))
						{
							echo '<p>La palette que vous recherchez n\'a pas été trouvée. Elle ne vous était pas destinée ? Veuillez vous rendre sur <a href="declaration-horsqlik.php">cette page</a></p>';
						}
						elseif(!empty($dataSearch))
						{
							$saveBoxTeteId='';
							foreach ($dataSearch as $sResult)
							{
								if(!empty($sResult['box-tete']))
								{
									$boxClass= 'heavy';
									$idBox='id="'.$sResult['box-tete'].'"';
									$saveBoxTeteId=$sResult['box-tete'];
									$classBoxDetail='';
								}
								elseif(!empty($sResult['box-detail']))
								{
									$boxClass='none';
									$idBox='';
									$classBoxDetail=$saveBoxTeteId;

								}
								else
								{
									$boxClass='none';

									$idBox='';
									$saveBoxTeteId='';
									$classBoxDetail='';



								}

								echo '<tr class="'.$boxClass.' '. $classBoxDetail.'">';
								echo'<td>'.$sResult['date_mvt'].'</td>';
								echo'<td>'.$sResult['facture'].'</td>';
								echo'<td>'.$sResult['palette'].'</td>';
								echo'<td>'.$sResult['gencod'].'</td>';
								echo'<td>'.$sResult['article'].'</td>';
								echo'<td>'.$sResult['libelle'].'</td>';
								echo'<td>';
								echo '<div class="form-check article"><input class="form-check-input checkarticle '.$sResult['palette'].'" type="checkbox" name="'.$sResult['id'].'"' .$idBox.'></div>';
								echo '</td></tr>';

							}


						}
						?>
					</tbody>
				</table>
				<div class="alert alert-light">
					<div class="row">
						<div class="col"></div>
						<div class="col">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" id="checkAll">
								<label class="form-check-label" for="checkAll">Sélectionner tout / désélectionner tout</label>
							</div>
						</div>
					</div>
					<?php
					if(isset($_SESSION['palette']))
					{

						for ($i=0; $i < count($_SESSION['palette']) ; $i++)
						{

							echo '<div class="row">';
							echo '<div class="col"></div>';
							echo '<div class="col">';
							echo '<div class="form-check">';
							echo '<input type="checkbox" class="form-check-input vol-list-palette" id="'.$_SESSION['palette'][$i].'" >';
							echo '<label class="form-check-label" for="checkAll">Sélectionner tous les articles de la palette : '.$_SESSION['palette'][$i].'</label>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
						}
					}
					else{

						if(!empty($dataSearch))
						{
							$paletteinitiale='';
							foreach ($dataSearch as $palette)
							{
								if($paletteinitiale!=$palette['palette'])
								{
									echo '<div class="row">';
									echo '<div class="col"></div>';
									echo '<div class="col">';
									echo '<div class="form-check">';
									echo '<input type="checkbox" class="form-check-input vol-list-palette" id="'.$palette['palette'].'">';
									echo '<label class="form-check-label" for="checkAll">Sélectionner tous les articles de la palette : '.$palette['palette'].'</label>';
									echo '</div>';
									echo '</div>';
									echo '</div>';

									$paletteinitiale=$palette['palette'];
								}
							}
						}
					}
					?>


					<p><i class="fas fa-info-circle  pr-3"></i>Le produit que vous avez reçu n'apparaît pas dans la liste et vous avez bien reçu tous les autres produits commandés ? Veuillez vous rendre sur <a href="declaration-horsqlik.php">cette page</a></p>


				</div>
					<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">2</span>Nom de l'interlocuteur</p>
					<div class="form-group">
						<input type="text" class="form-control"  name="nom" required>
					</div>

					<?php
					ob_start();
					?>
					<div class="row">
						<div class="col">
							<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">3</span>Date de déclaration</p>
						</div>
						<div class="col">
							<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">4</span>Numéro de dossier : </p>
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


		$(".vol-list-palette").click(function(){
			var palette=$(this).attr('id');
				// var thisclass='.'+palette;
				$('.'+ palette).prop('checked', this.checked);


				console.log(palette);
			});


		$("#search").submit(function( event )
		{
			$("#waitun" ).append('<i class="fas fa-spinner fa-spin"></i><span class="pl-3">Merci de patienter pendant la recherche</span>')
		});

		$("#submit").submit(function( event )
		{
			$("#waitdeux" ).append('<i class="fas fa-spinner fa-spin"></i><span class="pl-3">Merci de patienter</span>')

		});

		$('.checkarticle').click(function(e)
		{
			var test=$(e.target).closest('tr');
			// console.log(test);
		});
		var boxText='<tr><td class="heavy text-red"colspan="7"><i class="fas fa-exclamation-triangle pr-3"></i>Vous avez sélectionné un BOX, veuillez cocher parmi les articles du box (en bleu),ceux sur lesquels vous avez un litige </td>/<tr>';
		$('.1').hide()
		$('#1').change(function(){
			if($(this).is(":checked")) {
				$('#1').closest('tr').after(boxText);
				$('.1').show();
				$('.1').addClass('text-blue');
			}
			else
			{
				$('.1').hide();
				var thistr=$('#1').closest('tr');
				thistr.next().remove();
			}
		});


		$('.2').hide()
		$('#2').change(function(){
			if($(this).is(":checked")) {
				$('#2').closest('tr').after(boxText);
				$('.2').show();
				$('.2').addClass('text-blue');
			}
			else
			{
				$('.2').hide();
				var thistr=$('#2').closest('tr');
				thistr.next().remove();
			}
		});

		$('.3').hide()
		$('#3').change(function(){
			if($(this).is(":checked")) {
				$('#3').closest('tr').after(boxText);
				$('.3').show();
				$('.3').addClass('text-blue');
			}
			else
			{
				$('.3').hide();
				var thistr=$('#3').closest('tr');
				thistr.next().remove();
			}
		});

		$('.4').hide()
		$('#4').change(function(){
			if($(this).is(":checked")) {
				$('#4').closest('tr').after(boxText);
				$('.4').show();
				$('.4').addClass('text-blue');
			}
			else
			{
				$('.4').hide();
				var thistr=$('#4').closest('tr');
				thistr.next().remove();
			}
		});


		$('.5').hide()
		$('#5').change(function(){
			if($(this).is(":checked")) {
				$('#5').closest('tr').after(boxText);
				$('.5').show();
				$('.5').addClass('text-blue');
			}
			else
			{
				$('.5').hide();
				var thistr=$('#5').closest('tr');
				thistr.next().remove();
			}
		});


		$('.6').hide()
		$('#6').change(function(){
			if($(this).is(":checked")) {
				$('#6').closest('tr').after(boxText);
				$('.6').show();
				$('.6').addClass('text-blue');
			}
			else
			{
				$('.6').hide();
				var thistr=$('#6').closest('tr');
				thistr.next().remove();
			}
		});
	</script>


	<?php

	require '../view/_footer-bt.php';

	?>