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



require '../../Class/LitigeDao.php';
require '../../Class/MagDao.php';
require '../../Class/Mag.php';


// recup idwebuser du mag
function getMagIdwebuser($pdoUser)
{
	$req=$pdoUser->prepare("SELECT id FROM users WHERE galec=:galec");
	$req->execute(array(
		':galec'		=>$_SESSION['id_galec']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}



// création du dossier dans la table ($idRobbery = 0 si pas vol, 1 si vol)
function insertDossier($pdoLitige, $numDossier,$magId, $idRobbery){

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
function getSelectedDetails($pdoQlik,$id){
	$req=$pdoQlik->prepare("SELECT * FROM statsventeslitiges WHERE id= :id");
	$req->execute(array(
		':id'	=>$id

	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
// ajoute info produits dans table detail
function addDetails($pdoLitige, $lastInsertId,$numDossier,$palette,	$facture,$dateFacture, $article, $ean,$dossierG, $descr, $qteC,	$tarif, $fou, $cnuf,$boxTete,$boxDetail, $puv,$pul){
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
function getPoids($pdoQlik, $art,$dossier){
	$req=$pdoQlik->prepare("SELECT `GESSICA.PoidsBrutUV` as puv, `GESSICA.PoidsBrutUL` as pul FROM basearticles  WHERE `GESSICA.CodeArticle` = :article AND `GESSICA.CodeDossier` = :dossier LIMIT 1");
	$req->execute([
		':article'	=>	$art,
		':dossier'	=>$dossier

	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

// ajout d'un dossier vol => recupère l'id  pour le mettre ensuite dans table dossiers id_robbery
function insertRobbery($pdoLitige){
	$req=$pdoLitige->prepare("INSERT INTO robbery (date_saisie) VALUES (:date_saisie)");
	$req->execute([
		':date_saisie' =>date('Y-m-d H:i:s'),

	]);
	return $pdoLitige->lastInsertId();
}


$ids=[];
$errors=[];
$success=[];
$saveBoxTeteId='';



$litigeDao=new LitigeDao($pdoLitige);
$magDao=new MagDao($pdoMag);
$infoMag=$magDao->getMagByGalec($_SESSION['id_galec']);



// ajoute nom du mag au titre si declaration faite par btlec
$magtxt="";
if($_SESSION['type']=='btlec'){
}


// si on vient de la page déclaration de vol et que l'on a récupéré les numéros de palettes volées, on lance la recherche directement
if(isset($_SESSION['palette'])){
	$_POST['submit']=true;
}

// initialisation des variables suivant le user connecté
if($_SESSION['type']=='btlec'){
	$magUser=$magDao->getWebUser($_SESSION['id_galec']);
	$magId=$magUser['id_web_user'];
		echo "<pre>";
		print_r($magUser);
		echo '</pre>';

	// echo $magId=$magId['id_web_user'];
}else{
	$magId=$_SESSION['id_web_user'];
}



//-----------------------------------------------------------------
//			AFFICHAGE RESULTAT DE LA RECHERCHE DE PALETTE/FACTURE
//-----------------------------------------------------------------
include 'declaration-stepone-search-inc.php';



//------------------------------------------------------
//			TRAITEMENT VALIDATION FORMUALIRE SELECTION ARTICLE
//------------------------------------------------------
if(isset($_POST['choose'])){
// 1- creation du dossier
	if(empty($_POST['article_id'])){
		$errors[]="Merci de sélectionner un article";
	}
	if(empty($errors)){
		//si on a une variable de session vol-id, il faut vérifier ce qu'elle renvoie :
		//si elle est égale à zéro, c'est une nouvelle décalration de vol donc on la créé et on récupère son id,
		//sinon c'est la suite d'une décalartion existante donc on récupère la valeur de session['id_vol']
		if(isset($_SESSION['vol-id'])){
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
		else{
			$idRobbery=null;
		}

		// si le numéro de dossier a été saisi, on le mémorise dans $_SESSION['dossier_litige']
		if(!empty($_POST['num_dossier_form'])){
			$_SESSION['dossier_litige']=$_POST['num_dossier_form'];
		}
		// le numéro de dossier sera ecrasé au moment de la recopie de la table temporaire vers la table active
		$numDossier=9999;
		$lastInsertId=insertDossier($pdoLitige,$numDossier, $magId, $idRobbery);

	// créa du dossier (sans numéro officiel pour l'instant)
		if($lastInsertId>0){
			$sucess[]="ajout du dossier réussie";
		}
		else{
			$errors[]="Impossible de créer le dossier";
		}
	}

// 2- ajout des ref art, num, num fac, date fac, n°palette, qte originale, num dossier,gencod, id_web_user, btlec, galec, deno
	if(count($errors)==0){
		$tete=0;
		$added=0;

		for ($i=0; $i <count($_POST['article_id']) ; $i++){
			// on récupère l'index de l'article coché en cherchant la valeur de $_POST['article_id'] (id statsventelitige) dans le table hidden_id. C'est les post ayant cet index que l'on devra récupérer et pousser danxs la db
			$rowArticle=array_search($_POST['article_id'][$i], $_POST['hidden_id']);
			if(!empty($_POST['hidden_boxhead'][$rowArticle])){
				$tete=1;
				$detailbox=NULL;
				$tetedebox=$_POST['hidden_article'][$rowArticle];
			}
			else{
				$tete=0;
				$detailbox=NULL;
			}


			if(empty($_POST['hidden_boxhead'][$rowArticle]) && !empty($_POST['hidden_boxdetail'][$rowArticle])){
				$detailbox=$_POST['hidden_article'][$rowArticle];
			}else{
				$detailbox=NULL;
			}

			$dateFact=date('Y-m-d H:i:s',strtotime($_POST['hidden_date_facture'][$rowArticle]));
			if(isset($_SESSION['vol-id'])){
				$poids=getPoids($pdoQlik,$_POST['hidden_article'][$rowArticle],$_POST['hidden_dossier'][$rowArticle]);
				if(count($poids==1)){
					$puv=$poids['puv'];
					$pul=$poids['pul'];
				}else{
					// $errors[]="ATTENTION, les poids n'ont pas pu être récupérés dans la base article";
					$puv=null;
					$pul=null;
				}
			}else{
				$puv=null;
				$pul=null;
			}


			$detail=addDetails($pdoLitige, $lastInsertId,$numDossier,$_POST['hidden_palette'][$rowArticle],$_POST['hidden_facture'][$rowArticle],$dateFact, $_POST['hidden_article'][$rowArticle], $_POST['hidden_ean'][$rowArticle],$_POST['hidden_dossier'][$rowArticle], $_POST['hidden_descr'][$rowArticle], $_POST['hidden_qte'][$rowArticle],$_POST['hidden_tarif'][$rowArticle], $_POST['hidden_fou'][$rowArticle], $_POST['hidden_cnuf'][$rowArticle],$tete,$detailbox,$puv,$pul );
			if($detail>0){
				$added++;
			}
			else{
				$errors[]="erreur à l'enregistrement";
			}
		}
		// suivant type de déclaration (palette complète ou non), on ne renvoie pas sur la même page
		if($added>0 && !isset($_POST['palette_complete'])){
			header('Location:declaration-steptwo.php?id='.$lastInsertId);
		}		elseif ($added>0 && isset($_POST['palette_complete'])){

			header('Location:declaration-steptwo-palette.php?id='.$lastInsertId);
		}
	}
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
				<p class="text-main-blue heavy">
					<span class="step step-bg-blue mr-3">1</span>Sélectionnez le ou les articles sur lesquels vous avez un litige à déclarer
				</p>
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
						<?php if (empty($dataSearch)): ?>
							<p>La palette que vous recherchez n'a pas été trouvée ? Elle ne vous était pas destinée ? Veuillez vous rendre sur <a href="dde-ouv-dossier.php">cette page</a></p>

							<?php else : ?>
								<?php foreach ($dataSearch as  $sResult): ?>
									<?php
									if(!empty($sResult['box-tete'])){
										$boxClass= 'heavy';
										$idBox=$sResult['box-tete'];
										$saveBoxTeteId=$sResult['box-tete'];
										$classBoxDetail='';
									}elseif(!empty($sResult['box-detail'])){
										$boxClass='none';
										$idBox='';
										$classBoxDetail=$saveBoxTeteId;
									}
									else{
										$boxClass='';
										$idBox='';
										$saveBoxTeteId='';
										$classBoxDetail='';
									}
									?>
									<tr class="<?=$boxClass.' '. $classBoxDetail?>">
										<td><?=$sResult['date_mvt']?></td>
										<td><?=$sResult['facture']?></td>
										<td><?=$sResult['palette']?></td>
										<td><?=$sResult['gencod']?></td>
										<td><?=$sResult['article']?></td>
										<td><?=$sResult['libelle']?></td>
										<td>
											<div class="form-check article">
												<input class="form-check-input checkarticle <?=$sResult['palette']?>" type="checkbox"
												data-id="<?=$idBox?>" name="article_id[]" value="<?=$sResult['id']?>" id="<?=$idBox?>">
											</div>
										</td>
									</tr>


											<input type="hidden" class="form-check-input" name="hidden_id[]" value="<?=$sResult['id']?>">

											<input type="hidden" class="form-check-input" name="hidden_palette[]" value="<?=$sResult['palette']?>">

											<input type="hidden" class="form-check-input" name="hidden_facture[]" value="<?=$sResult['facture']?>">

											<input type="hidden" class="form-check-input" name="hidden_date_facture[]" value="<?=$sResult['date_mvt']?>">

											<input type="hidden" class="form-check-input" name="hidden_article[]" value="<?=$sResult['article']?>">

											<input type="hidden" class="form-check-input" name="hidden_ean[]" value="<?=$sResult['gencod']?>">

											<input type="hidden" class="form-check-input" name="hidden_dossier[]" value="<?=$sResult['dossier']?>">

											<input type="hidden" class="form-check-input" name="hidden_descr[]" value="<?=$sResult['libelle']?>">

											<input type="hidden" class="form-check-input" name="hidden_qte[]" value="<?=$sResult['qte']?>">

											<input type="hidden" class="form-check-input" name="hidden_tarif[]" value="<?=$sResult['tarif']?>">

											<input type="hidden" class="form-check-input" name="hidden_fou[]" value="<?=$sResult['fournisseur']?>">

											<input type="hidden" class="form-check-input" name="hidden_cnuf[]" value="<?=$sResult['cnuf']?>">

											<input type="hidden" class="form-check-input" name="hidden_boxhead[]" value="<?=$sResult['box-tete']?>">

											<input type="hidden" class="form-check-input" name="hidden_boxdetail[]" value="<?=$sResult['box-detail']?>">



								<?php endforeach ?>

							<?php endif ?>

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

						<?php if (!empty($dataSearch)): ?>
							<?php
							$arPalettesCheckBox = array_column($dataSearch, 'palette');
							$arPalettesCheckBox=array_unique($arPalettesCheckBox);
							$arPalettesCheckBox=array_values($arPalettesCheckBox);
							?>
							<?php for ($i=0; $i < count($arPalettesCheckBox) ; $i++):?>
								<div class="row">
									<div class="col"></div>
									<div class="col">
										<div class="form-check">
											<input type="checkbox" class="form-check-input vol-list-palette" id="<?=$arPalettesCheckBox[$i]?>">
											<label class="form-check-label" for="checkAll">Sélectionner tous les articles de la palette <?= $arPalettesCheckBox[$i]?></label>
										</div>
									</div>
								</div>

							<?php endfor?>
						<?php endif ?>

						<p><i class="fas fa-info-circle  pr-3"></i>Le produit que vous avez reçu n'apparaît pas dans la liste et vous avez bien reçu tous les autres produits commandés ? Veuillez vous rendre sur <a href="dde-ouv-dossier.php">cette page</a></p>


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
									<input type="date" class="form-control" name="date_bt" value="<?= date('Y-m-d')?>">
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
					if($_SESSION['type']=="btlec"){
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

		$('input[type=checkbox]').click(function(){
			var id=$(this).data("id");
			if($(this).is(":checked")) {
				$('#'+id).closest('tr').after(boxText);
				$('.'+id).show();
				$('.'+id).addClass('text-blue');
			}
			else
			{
				$('.'+id).hide();
				var thistr=$('#'+id).closest('tr');
				thistr.next().remove();
			}

		});


	</script>


	<?php

	require '../view/_footer-bt.php';

	?>