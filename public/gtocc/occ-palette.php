<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	echo "pas de variable session";

}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';
require_once '../../Class/OccPaletteMgr.php';
require '../../Class/UserHelpers.php';






//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);




// echo "<pre>";
// print_r($paletteCommandable);
// echo '</pre>';




//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getListPanier($pdoBt){
	$req=$pdoBt->prepare("SELECT occ_cdes_temp.*, palette FROM occ_cdes_temp LEFT JOIN occ_palettes ON id_palette=occ_palettes.id WHERE id_web_user= :id_web_user");
	$req->execute([
		':id_web_user'		=>$_SESSION['id_web_user']

	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
function addToTemp($pdoBt){
	$req=$pdoBt->prepare("INSERT INTO occ_cdes_temp (id_web_user, id_palette, date_insert) VALUES (:id_web_user, :id_palette, :date_insert) ");
	$req->execute([
		':id_web_user'		=>$_SESSION['id_web_user'],
		':id_palette'		=>$_POST['id_palette'],
		':date_insert'		=>date('Y-m-d H:i:s')

	]);

	$err=$req->errorInfo();


	if(!empty($err[2])){
		return false;
	}
	return true;
}

function addToTempArt($pdoBt){
	$req=$pdoBt->prepare("INSERT INTO occ_cdes_temp (id_web_user, id_palette, article_occ, design_occ, fournisseur_occ, ean_occ, panf_occ, deee_occ, sorecop_occ ,qte_cde, date_insert) VALUES (:id_web_user, :id_palette, :article_occ, :design_occ, :fournisseur_occ, :ean_occ, :panf_occ, :deee_occ, :sorecop_occ , :qte_cde, :date_insert) ");
	$req->execute([
		':id_web_user'		=>$_SESSION['id_web_user'],
		':id_palette'		=>0,
		':article_occ'		=>$_POST['article_qlik'],
		':design_occ'		=>$_POST['design_qlik'],
		':fournisseur_occ'	=>$_POST['fournisseur_qlik'],
		':ean_occ'		=>$_POST['ean_qlik'],
		':panf_occ'		=>$_POST['panf_qlik'],
		':deee_occ'		=>$_POST['deee_qlik'],
		':sorecop_occ'		=>$_POST['sorecop'],
		':qte_cde'		=>$_POST['qte_cde'],
		':date_insert'		=>date('Y-m-d H:i:s')

	]);

	$err=$req->errorInfo();

	return $err;

	if(!empty($err[2])){
		return false;
	}
	return true;
}


function delTempCde($pdoBt){
	$req=$pdoBt->prepare("DELETE FROM occ_cdes_temp WHERE id= :id");
	$req->execute([
		':id'		=>$_GET['idTempDel']
	]);
}

function getPaletteStatut($pdoBt,$id){
	$req=$pdoBt->prepare("SELECT * FROM occ_palettes WHERE id= :id ");
	$req->execute([
		':id'	=>$id

	]);
	// return $req->errorInfo();
	return $req->fetch(PDO::FETCH_ASSOC);
}

function updatePalette($pdoBt,$idPalette,$statut){
	$req=$pdoBt->prepare("UPDATE occ_palettes SET statut= :statut WHERE id= :id");
	$req->execute([
		':id'		=>$idPalette,
		':statut'	=>$statut
	]);
	$err=$req->errorInfo();
	if(!empty($err[2])){
		return false;
	}
	return true;
}

function addToCmd($pdoBt,$idPalette, $article, $panf, $deee, $sorecop, $design, $fournisseur, $ean, $qte){
	$req=$pdoBt->prepare("INSERT INTO occ_cdes (id_web_user, id_palette, article_occ, panf_occ, deee_occ, sorecop_occ, design_occ, fournisseur_occ, ean_occ, qte_cde, date_insert) VALUES (:id_web_user, :id_palette, :article_occ, :panf_occ, :deee_occ, :sorecop_occ, :design_occ, :fournisseur_occ, :ean_occ, :qte_cde, :date_insert) ");
	$req->execute([
		':id_web_user'		=>$_SESSION['id_web_user'],
		':article_occ'		=>$article,
		':panf_occ'		=>$panf,
		':deee_occ'		=>$deee,
		':sorecop_occ'		=>$sorecop,
		':design_occ'		=>$design,
		':fournisseur_occ'		=>$fournisseur,
		':ean_occ'		=>$ean,
		':qte_cde'		=>$qte,
		':id_palette'		=>$idPalette,
		':date_insert'		=>date('Y-m-d H:i:s')

	]);
	$err=$req->errorInfo();
	return $err;
	if(!empty($err[2])){
		return false;
	}
	return true;
}
function deleteTempCmd($pdoBt,$id){
	$req=$pdoBt->prepare("DELETE FROM occ_cdes_temp WHERE id= :id");
	$req->execute([
		':id'	=>$id
	]);
	$err=$req->errorInfo();
	if(!empty($err[2])){
		return false;
	}
	return true;
}

function getAssortiment($pdoBt){

	$req=$pdoBt->query(" SELECT *	FROM occ_article_qlik ORDER BY article_qlik");
	return $req->fetchAll();
}


function onPaletteOccas($pdoBt, $article){
	$req=$pdoBt->prepare("SELECT id_palette, palette, statut  FROM occ_articles LEFT JOIN occ_palettes ON occ_articles.id_palette=occ_palettes.id WHERE code_article= :code_article");
	$req->execute([
		':code_article'	=>$article

	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function isMagArticleInTemp($pdoBt, $article){
	$req=$pdoBt->prepare("SELECT * FROM occ_cdes_temp WHERE article_occ= :article_occ AND id_web_user= :id_web_user");
	$req->execute([
		':article_occ'	=>$article,
		':id_web_user'	=>$_SESSION['id_web_user']

	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);
	return $data;
}


function delLine($pdoBt,$idCdeTemp){
	$req=$pdoBt->prepare("DELETE FROM occ_cdes_temp WHERE id= :id");
	$req->execute([
		':id'	=>$idCdeTemp
	]);
	$err=$req->errorInfo();
	if(!empty($err[2])){
		return false;
	}
	return true;
}

function updateTempArt($pdoBt,$id){

	$req=$pdoBt->prepare("UPDATE occ_cdes_temp SET qte_cde= :qte_cde, date_insert= :date_insert WHERE id= :id" );
	$req->execute([
		':qte_cde'		=>$_POST['qte_cde'],
		':date_insert'		=>date('Y-m-d H:i:s'),
		':id'		=>$id
	]);
}

function updateQteArticle($pdoBt,$article, $qte){
	$req=$pdoBt->prepare("UPDATE occ_article_qlik SET qte_qlik= :qte_qlik WHERE article_qlik= :article_qlik");
	$req->execute([
		':qte_qlik'	=>$qte,
		':article_qlik'		=>$article

	]);
	$err=$req->errorInfo();
	if(!empty($err[2])){
		return false;
	}
	return true;
}

function getQteArticleQlik($pdoBt, $article){
	$req=$pdoBt->prepare(" SELECT qte_qlik	FROM occ_article_qlik WHERE article_qlik= :article_qlik");
	$req->execute([
		':article_qlik' =>$article
	]);
	$data=$req->fetch();
	return $data['qte_qlik'];
}

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$displayCart=false;

$paletteMgr=new OccPaletteMgr($pdoBt);
$paletteCommandable=$paletteMgr->getListPaletteDetailByStatut(1);
$paletteEnPrepa=$paletteMgr->getListPaletteDetailByStatut(0);
$paletteCommandees=$paletteMgr-> getListPaletteByCde(2);
$paletteExpediees=$paletteMgr-> getListPaletteByCde(3);
$paletteDansPanierMag=getListPanier($pdoBt);

$listAssortiment=getAssortiment($pdoBt);



if(!empty($paletteDansPanierMag)){
	$nbPalettePanier=count($paletteDansPanierMag);
}else{
	$nbPalettePanier=0;
}
// if(isset($_GET['id'])){
// 	$detailPalette=getPalette($pdoBt, $_GET['id']);
// }
if(isset($_POST['addtocart'])){
	$displayCart=addToTemp($pdoBt);
		// echo "<pre>";
		// print_r($displayCart);
		// echo '</pre>';

	if($displayCart){

		$successQ='?success=cart';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

	}else{
		$errors[]="erreur";
	}

}

if(isset($_GET['idTempDel'])){
	delTempCde($pdoBt);
	header("Location:occ-palette.php");

}
// echo "<pre>";
// print_r($paletteDansPanierMag);
// echo '</pre>';


if(isset($_POST['checkout'])){

	foreach ($paletteDansPanierMag as $key => $itemReserve) {
		// cas palette
		if(!empty($itemReserve['id_palette'])){
		//  on vérifie l'état des palettes si commandé ou expédié entre temps, on suppprile de temp et on averti le mag
			$paletteStatut = getPaletteStatut($pdoBt,$itemReserve['id_palette']);

			//  palette plus dispo
			if($paletteStatut['statut'] !=1){
				$errors[]="la palette ".$paletteStatut['palette'].' a été commandée entre temps par un autre magasin. Veuillez la supprimer';
			}
		}

	}
		//  sinon commande et met à jour
//  le statu de la palette en commandé
//  la table temporaire pourretirer toutes les palettes de cette commande
	if(empty($errors)){

		foreach ($paletteDansPanierMag as $key => $itemReserve) {
			if(!empty($itemReserve['id_palette'])){

				$cdeOk=addToCmd($pdoBt,$itemReserve['id_palette'], $itemReserve['article_occ'], $itemReserve['panf_occ'], $itemReserve['deee_occ'], $itemReserve['sorecop_occ'], $itemReserve['design_occ'], $itemReserve['fournisseur_occ'], $itemReserve['ean_occ'], $itemReserve['qte_cde']);
				if($cdeOk){
					$statut=2;
					$upPalette=$paletteMgr->updatePaletteStatut($pdoBt,$itemReserve['id_palette'],$statut);
				}else{
					$errors[]="Une erreur est survenue avec la palette ".$itemReserve['palette'];
				}
				if($upPalette){
					$deleteTemRow=deleteTempCmd($pdoBt,$itemReserve['id']);
				}

			}else{
				$cdeOk=addToCmd($pdoBt,$itemReserve['id_palette'], $itemReserve['article_occ'], $itemReserve['panf_occ'], $itemReserve['deee_occ'], $itemReserve['sorecop_occ'], $itemReserve['design_occ'], $itemReserve['fournisseur_occ'], $itemReserve['ean_occ'], $itemReserve['qte_cde']);
				if($cdeOk){
					// on supprime la ligne temporaire
					$deleteTemRow=deleteTempCmd($pdoBt,$itemReserve['id']);
					// on met à jour les quantité de la table cde
					// donc on récupère la qte actuelle
					$qteStock=getQteArticleQlik($pdoBt, $itemReserve['article_occ']);
					$qte=$qteStock - $itemReserve['qte_cde'];
					$ok=updateQteArticle($pdoBt,$itemReserve['article_occ'], $qte);
					if(!$ok){
						$errors[]="une erreur est survenue, impossible de passer votre commande";
					}
				}else{
					$errors[]="une erreur est survenue, impossible de passer votre commande";
				}
			}
		}

		if(empty($errors)){
			header("Location:occ-palette.php?success=cde");
		}

	}

}

if(isset($_POST['add-article'])){
	// on vérifie que le magasin n'a pas ocmmandé un quantité supérieur à celle du stock


	if($_POST['qte_cde']>$_POST['qte_qlik']){
		$errors[]="Impossible d'ajouter la quantité, elle est supérieure au stock de l'article " .$_POST['article_qlik'];
	}
	if(empty($errors)){

		// on regarde si l'article est dans la table de commande temparaire
	// on fait un update sauf si quantité est à 0, là on supprime
		$inTemp=isMagArticleInTemp($pdoBt,$_POST['article_qlik']);
		if(empty($inTemp) && $_POST['qte_cde']!=0 ){
			$added=addToTempArt($pdoBt);
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'],true,303);
		}elseif(!empty($inTemp) && $_POST['qte_cde']==0) {
		// on supprimer
			delLine($pdoBt,$inTemp['id']);
		}else{
		// on update
			updateTempArt($pdoBt,$inTemp['id']);

		}
	}

}



if(isset($_GET['expedier'])){
	$upPalette=$paletteMgr->updatePaletteStatut($pdoBt,$_GET['expedier'],3);
	if($upPalette){
		header("Location:occ-palette.php?success=expedie");
	}else{
		$errors[]="une erreur est survenue, impossible de mettre la palette à jour";

	}
}



if(isset($_GET['success'])){
	$arrSuccess=[
		'cart'=>'Palette ajoutée à votre panier.<br> Attention, pensez à validez votre panier rapidement, si un autre magasin a commandé la palette avant vous, elle disparaîtra automatiquement de votre panier',

		'cde'	=>"Votre commande a bien été envoyée",
		'expedie'	=>"la palette a bien été passée en statut expédiée",
	];
	$success[]=$arrSuccess[$_GET['success']];
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

	<h1 class="text-main-blue pt-5 pb-2">Produits d'occasion</h1>




	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<!-- partie réservée BT -->

	<?php if ($_SESSION['type']=='btlec'): ?>

		<div class="row justify-content-center mb-2">
			<div class="col-md-6 border rounded py-3">
				<div class="row">
					<div class="col text-center text-main-blue">
						Voir les palettes :
					</div>
				</div>
				<div class="row ">
					<div class="col ">
						<nav class="text-center nav-planning">
							<a href="#prepa" class="nav-elt">En prépa</a>
							<a href="#over" class="nav-elt">Terminées</a>
							<a href="#cde" class="nav-elt">Commandées</a>
							<a href="#exp" class="nav-elt">Expédiées</a>
						</nav>

					</div>

				</div>
			</div>
		</div>

		<?php if (!empty($paletteDansPanierMag)){include 'occ-palette-cart.php';}?>

		<div class="row">
			<div class="col">
				<h3 class="text-main-blue text-center pt-4" id="article">Articles d'occasion</h3>

			</div>
		</div>

		<div class="row ">
			<div class="col">

				<table class="table table-sm">
					<thead class="thead-dark">
						<tr>
							<th>Code article</th>
							<th>Code Dossier</th>
							<th>Désignation</th>
							<th>Fournisseur</th>
							<th>EAN</th>
							<th>PANF</th>
							<th>DEEE</th>
							<th>SORECOP</th>
							<th class="text-right">Qté à dispo</th>
							<th colspan="2" class="text-center">Ajouter</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($listAssortiment as $assor): ?>
							<?php
							$artInTemp=isMagArticleInTemp($pdoBt, $assor['article_qlik']);
							?>
							<tr>
								<td id="<?=$assor['article_qlik']?>"><?=$assor['article_qlik']?></td>
								<td><?=$assor['dossier_qlik']?></td>
								<td><?=$assor['design_qlik']?></td>
								<td><?=$assor['fournisseur_qlik']?></td>
								<td><?=$assor['ean_qlik']?></td>
								<td><?=$assor['panf_qlik']?></td>
								<td><?=$assor['deee_qlik']?></td>
								<td><?=$assor['sorecop']?></td>
								<td class="text-right"><?=$assor['qte_qlik']?></td>
								<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">

									<td class="text-right">
										<input type="text" data-input="<?=$assor['article_qlik']?>" name="qte_cde" class="form-control mini-input" value="<?=!empty($artInTemp)? $artInTemp['qte_cde']:""?>">

									</td>
									<td>
										<div class="hidden" data-btn="<?=$assor['article_qlik']?>">
											<button class="btn btn-primary" name="add-article"><i class="fa fa-shopping-cart"></i></button>
										</div>

									</td>
									<input type="hidden" name="article_qlik" value="<?=$assor['article_qlik']?>">
									<input type="hidden" name="design_qlik" value="<?=$assor['design_qlik']?>">
									<input type="hidden" name="fournisseur_qlik" value="<?=$assor['fournisseur_qlik']?>">
									<input type="hidden" name="ean_qlik" value="<?=$assor['ean_qlik']?>">
									<input type="hidden" name="panf_qlik" value="<?=$assor['panf_qlik']?>">
									<input type="hidden" name="deee_qlik" value="<?=$assor['deee_qlik']?>">
									<input type="hidden" name="sorecop" value="<?=$assor['sorecop']?>">
									<input type="hidden" name="qte_qlik" value="<?=$assor['qte_qlik']?>">
								</form>


							</tr>
						<?php endforeach ?>

					</tbody>
				</table>

			</div>
		</div>

		<div class="row pb-3">
			<div class="col text-right">
			</div>
		</div>

		<div class="bg-separation"></div>
		<!-- modal -->


		<div class="row">
			<div class="col">
				<h3 class="text-main-blue text-center pt-4" id="prepa">Palettes en cours de préparation</h3>

			</div>
		</div>
		<?php if (!empty($paletteEnPrepa)): ?>
			<?php foreach ($paletteEnPrepa as $key => $palette): ?>

				<div class="row my-3" id="detailPalette">
					<div class="col">
						<h5 class="text-main-blue text-center">Palette <?=$palette[0]['palette'] ?> (en préparation)</h5>
					</div>
				</div>

				<div class="row pb-5">
					<div class="col">
						<table class="table table-sm shadow">
							<thead class="thead-dark">
								<tr>
									<th>Code article</th>
									<th>Code Dossier</th>
									<th>Désignation</th>
									<th>EAN</th>

								</tr>
							</thead>
							<tbody>
								<?php foreach ($palette as $key => $article): ?>

									<tr>
										<td><?=$article['code_article']?></td>
										<td><?=$article['code_dossier']?></td>
										<td><?=$article['designation']?></td>
										<td><?=$article['ean']?></td>

									</tr>
								<?php endforeach ?>


							</tbody>
						</table>

					</div>
				</div>
			<?php endforeach ?>
			<?php else: ?>

				<div class="row pb-5">
					<div class="col">
						<div class="alert alert-primary">
							Pas de palette en cours de préparation
						</div>
					</div>
				</div>
			<?php endif ?>
			<!-- titre uniquement pour bt -->


		<?php endif ?>
		<!-- fin partie réservée bt -->

		<!-- panier -->
		<div class="bg-separation"></div>
		<div class="row">
			<div class="col">
				<h3 class="text-main-blue text-center pt-4 pb-2" id="over">Palettes disponibles à la commande</h3>
			</div>
		</div>






		<?php if (!empty($paletteCommandable)): ?>

			<?php foreach ($paletteCommandable as $key => $palette): ?>

				<div class="row my-3" id="detailPalette">
					<div class="col">
						<h5 class="text-main-blue text-center">Palette <?=$palette[0]['palette'] ?></h5>
					</div>
				</div>

				<div class="row pb-2">
					<div class="col">
						<table class="table table-sm shadow">
							<thead class="thead-dark">
								<tr>
									<th>Code article</th>
									<th>Code Dossier</th>
									<th>Désignation</th>
									<th>EAN</th>

								</tr>
							</thead>
							<tbody>
								<?php foreach ($palette as $key => $article): ?>

									<tr>
										<td><?=$article['code_article']?></td>
										<td><?=$article['code_dossier']?></td>
										<td><?=$article['designation'] . $article['id_palette']?></td>
										<td><?=$article['ean']?></td>

									</tr>
								<?php endforeach ?>


							</tbody>
						</table>

					</div>
				</div>
				<div class="row pb-2">
					<div class="col">
						<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'#cart-count'?>" method="post">
							<input type="hidden" name="id_palette" value="<?=$article['id_palette']?>">
							<div class="row">
								<div class="col text-right">
									<button class="btn btn-primary" name="addtocart"><i class="fas fa-cart-plus pr-3"></i>Ajouter</button>
								</div>
							</div>
						</form>
					</div>
				</div>

			<?php endforeach ?>
			<?php else: ?>

				<div class="row">
					<div class="col">
						<div class="alert alert-primary">
							Aucune palette occasion disponible pour l'instant
						</div>
					</div>
				</div>


			<?php endif ?>


			<?php if ($_SESSION['type']=='btlec'): ?>
				<div class="bg-separation"></div>
				<div class="row">
					<div class="col">
						<h3 class="text-main-blue text-center pt-4 pb-2" id="cde">Palettes commandées</h3>
					</div>
				</div>


				<?php if (!empty($paletteCommandees)): ?>
					<div class="row pb-2">
						<div class="col">
							<table class="table table-sm shadow">
								<thead class="thead-dark">
									<tr>
										<th>Cde n°</th>
										<th>Palette</th>
										<th>Magasin</th>
										<th>Date commande</th>
										<th>Modifier</th>

									</tr>
								</thead>
								<tbody>
									<?php foreach ($paletteCommandees as $key => $palette): ?>
										<tr>
											<td><?=$palette['id_cde']?></td>
											<td><?=$palette['palette']?></td>
											<td><?= UserHelpers::getMagInfoByIdWebUser($pdoUser, $pdoMag, $palette['id_web_user'], 'deno_sca')  ?></td>
											<td><?=$palette['date_cde']?></td>
											<td><a href="<?=$_SERVER['PHP_SELF'].'?expedier='.$palette['id_palette']?>" class="btn btn-primary">Expédier</a></td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>

						</div>
					</div>


					<?php else: ?>

						<div class="row">
							<div class="col">
								<div class="alert alert-primary">
									Aucune palette en commande
								</div>
							</div>
						</div>


					<?php endif ?>

					<div class="bg-separation"></div>
					<div class="row">
						<div class="col">
							<h3 class="text-main-blue text-center pt-4 pb-2" id="exp">Palettes Expédiées</h3>
						</div>
					</div>

					<?php if (!empty($paletteExpediees)): ?>
						<div class="row pb-2">
							<div class="col">
								<table class="table table-sm shadow">
									<thead class="thead-dark">
										<tr>
											<th>Cde n°</th>
											<th>Palette</th>
											<th>Magasin</th>
											<th>Date commande</th>

										</tr>
									</thead>
									<tbody>
										<?php foreach ($paletteExpediees as $key => $palette): ?>
											<tr>
												<td><?=$palette['id_cde']?></td>
												<td><?=$palette['palette']?></td>
												<td><?= UserHelpers::getMagInfoByIdWebUser($pdoUser, $pdoMag, $palette['id_web_user'], 'deno_sca')  ?></td>
												<td><?=$palette['date_cde']?></td>

											</tr>
										<?php endforeach ?>
									</tbody>
								</table>

							</div>
						</div>


						<?php else: ?>

							<div class="row">
								<div class="col">
									<div class="alert alert-primary">
										Aucune palette en commande
									</div>
								</div>
							</div>


						<?php endif ?>





						<!-- fin partie bt -->
					<?php endif ?>

					<!-- ./container -->
				</div>
				<script type="text/javascript">
					$( document ).ready(function() {
						$("#cart").on("click", function() {
							$(".shopping-cart").toggleClass("hidden shown");
						});
						// $("input.mini-input").focus(function(){
						// 	var inputFocused=$(this).attr("data-input");
						// 	var btn=$("div").find(`[data-btn='${inputFocused}']`);
						// 	btn.toggleClass("hidden shown");

						// 	// $(this).data("id")
						// });
						// $("input.mini-input").focusout(function(){
						// 	var inputFocused=$(this).attr("data-input");
						// 	var btn=$("div").find(`[data-btn='${inputFocused}']`);
						// 	btn.toggleClass("hidden shown");

						// });
					});

				</script>
				<?php
				require '../view/_footer-bt.php';
				?>