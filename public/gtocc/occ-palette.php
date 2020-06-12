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

function addToCmd($pdoBt,$idPalette){
	$req=$pdoBt->prepare("INSERT INTO occ_cdes (id_web_user, id_palette, date_insert) VALUES (:id_web_user, :id_palette, :date_insert) ");
	$req->execute([
		':id_web_user'		=>$_SESSION['id_web_user'],
		':id_palette'		=>$idPalette,
		':date_insert'		=>date('Y-m-d H:i:s')

	]);
	$err=$req->errorInfo();
	if(!empty($err[2])){
		return false;
	}
	return true;
}
function updateTempCmd($pdoBt,$idPalette){
	$req=$pdoBt->prepare("DELETE FROM occ_cdes_temp WHERE id_palette= :id_palette");
	$req->execute([
		':id_palette'	=>$idPalette
	]);
	$err=$req->errorInfo();
	if(!empty($err[2])){
		return false;
	}
	return true;
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
$paletteDansPanierMag=getListPanier($pdoBt);

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
	foreach ($paletteDansPanierMag as $key => $paletteReserve) {
		$paletteStatut = getPaletteStatut($pdoBt,$paletteReserve['id_palette']);
		//  on vérifie l'état des palettes si commandé ou expédié entre temps, on suppprile de temp et on averti le mag

			//  palette plus dispo
		if($paletteStatut['statut'] !=1){
			$errors[]="la palette ".$paletteStatut['palette'].' a été commandée entre temps par un autre magasin. Veuillez la supprimer';
		}



	}
		//  sinon commande et met à jour
//  le statu de la palette en commandé
//  la table temporaire pourretirer toutes les palettes de cette commande
	if(empty($errors)){
		foreach ($paletteDansPanierMag as $key => $paletteReserve) {

			$cdeOk=addToCmd($pdoBt,$paletteReserve['id_palette']);
			if($cdeOk){
				$statut=2;
				$upPalette=updatePalette($pdoBt,$paletteReserve['id_palette'],$statut);
			}else{
				$errors[]="Une erreur est survenue avec la palette ".$paletteReserve['palette'];
			}
			if($upPalette){
				$upTemp=updateTempCmd($pdoBt,$paletteReserve['id_palette']);
			}
			if($upTemp){
				header("Location:occ-palette.php?success=cde");

			}


		}
	}

}


if(isset($_GET['success'])){
	$arrSuccess=[
		'cart'=>'Palette ajoutée à votre panier.<br> Attention, pensez à validez votre panier rapidement, si un autre magasin a commandé la palette avant vous, elle disparaîtra automatiquement de votre panier',

		'cde'	=>"Votre commande a bien été envoyée"
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

	<h1 class="text-main-blue py-5 ">Palettes de produits d'occasion</h1>




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

		<div class="row">
			<div class="col">
				<h2 class="text-main-blue">Palettes en cours de préparation</h2>

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

		<!--  -->
		<div class="row">
			<div class="col">
				<h2 class="text-main-blue">Palettes disponibles à la commande</h2>
			</div>
		</div>
		<?php if (!empty($paletteDansPanierMag)): ?>
			<div class="row pt-2 pb-1 rounded bg-grey">
				<div class="col">
					<p class="text-secondary">Mes palettes en attente de commande</p>

				</div>
				<div class="col text-right mr-2">
					<div class="cart-toggle position-relative">
						<a href="#" id="cart">
							<i class="fa fa-shopping-cart fa-lg"></i>
							<div class='cart-wrapper'><span id='cart-count'><?=$nbPalettePanier?></span></div>
						</a>
					</div>
				</div>
			</div>
			<div class="row ontop">
				<div class="col text-right">
					<div class="shopping-cart shadow hidden">
						<div class="shopping-cart-header">
							<i class="fa fa-shopping-cart cart-icon"></i>
							<div class="shopping-cart-total">
								<span class="lighter-text">Palette(s):</span>
								<span class="text-danger font-weight-bold"><?=$nbPalettePanier?></span>
							</div>
						</div> <!--end shopping-cart-header -->

						<div class="shopping-cart-items">
							<?php foreach ($paletteDansPanierMag as $key => $tempPalette): ?>
								<div class="row no-gutters">
									<div class="col text-left">
										Palette :
									</div>
									<div class="col text-right pr-2">
										<a href="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$tempPalette['id_palette']?>#detailPalette"><?=$tempPalette['palette']?></a>
									</div>
									<div class="col-auto">
										<a href="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?idTempDel='.$tempPalette['id']?>">
											<i class="fas fa-ban"></i>
										</a>
									</div>
								</div>
							<?php endforeach ?>


						</div>
						<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
							<button href="#" name="checkout" class="btn button">Commander</button>
						</form>
					</div>
				</div>
			</div>
		<?php endif ?>






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

			<!-- ./container -->
		</div>
		<script type="text/javascript">
			(function(){
				$("#cart").on("click", function() {
					$(".shopping-cart").toggleClass("hidden shown");
				});


			})();
		</script>
		<?php
		require '../view/_footer-bt.php';
		?>