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
require_once '../../Class/OccHelpers.php';




//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


function getAssortiment($pdoQlik){
	$version=VERSION;
	$req=$pdoQlik->query("
		SELECT basearticles.id as idqlik, `GESSICA.CodeArticle`as article_qlik,`GESSICA.CodeDossier`as dossier_qlik,`GESSICA.PANF` as panf_qlik,`GESSICA.D3E`as deee_qlik,`GESSICA.SORECOP` as sorecop,`GESSICA.LibelleArticle`as design_qlik, `GESSICA.PCB`as pcb_qlik, `GESSICA.NomFournisseur` as fournisseur_qlik, `GESSICA.Gencod` as ean_qlik

		FROM `basearticles`

		WHERE `GESSICA.GT` LIKE '13' ORDER BY article_qlik
		");
	return $req->fetchAll();
}


function onPaletteOccas($pdoBt, $article){
	$req=$pdoBt->prepare("SELECT id_palette, palette, statut  FROM occ_articles LEFT JOIN occ_palettes ON occ_articles.id_palette=occ_palettes.id WHERE code_article= :code_article");
	$req->execute([
		':code_article'	=>$article

	]);
	return $req->fetch(PDO::FETCH_ASSOC);


}

function getPalette($pdoBt,$id){
	$req=$pdoBt->prepare("SELECT occ_articles.*, occ_palettes.palette, statut  FROM occ_articles LEFT JOIN occ_palettes ON occ_articles.id_palette=occ_palettes.id
		WHERE id_palette= :id_palette ");
	$req->execute([
		':id_palette'	=>$id

	]);
	// return $req->errorInfo();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}











//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$inCart=false;
$listAssortiment=getAssortiment($pdoQlik);
$detailPalette="";
$arrPalette=OccHelpers::arrayPalette($pdoBt);
// $listTempPalette=tempPalettes($pdoBt);
if(!empty($listTempPalette)){
	$nbTempPalette=count($listTempPalette);
}else{
	$nbTempPalette=0;
}
if(isset($_GET['id'])){
	$detailPalette=getPalette($pdoBt, $_GET['id']);
}
// if(isset($_POST['addtocart'])){
// 	$inCart=addToTemp($pdoBt);
// 	$successQ='?success=cart';
// 	unset($_POST);
// 	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
// }

// if(isset($_GET['idTempDel'])){
// 	delTempCde($pdoBt);
// 	header("Location:occ-assortiment.php");

// }


if(isset($_POST['checkout'])){
	foreach ($listTempPalette as $key => $paletteReserve) {
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
		foreach ($listTempPalette as $key => $paletteReserve) {

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
				header("Location:occ-assortiment.php?success=cde");

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
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Assortiment - Leclerc Occasion</h1>
		</div>


	</div>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<!-- toogle cart panel -->



	<div class="row under">
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
						<th>Palette</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($listAssortiment as $assor): ?>
						<?php
						$palette=onPaletteOccas($pdoBt, $assor['article_qlik']);


						?>
						<tr>
							<td><?=$assor['article_qlik']?></td>
							<td><?=$assor['dossier_qlik']?></td>
							<td><?=$assor['design_qlik']?></td>
							<td><?=$assor['fournisseur_qlik']?></td>
							<td><?=$assor['ean_qlik']?></td>
							<td><?=$assor['panf_qlik']?></td>
							<td><?=$assor['deee_qlik']?></td>
							<td><?=$assor['sorecop']?></td>
							<!-- on affiche le numéro de palette qd c'est une palette terminée -->
							<td><?= (!empty($palette) && ($palette['statut']==1) )?'<a href="occ-assortiment.php?id='.$palette['id_palette'].'#detailPalette">'.$palette['palette'].'</a>': "" ?></td>
						</tr>
					<?php endforeach ?>

				</tbody>
			</table>

		</div>
	</div>

	<?php if (!empty($detailPalette)): ?>

		<div class="row border rounded pt-3 pb-2 mb-2">
			<div class="col">
				<div class="row my-3" id="detailPalette">
					<div class="col">
						<h5 class="text-main-blue text-ceznter">Contenu de la palette <?=$arrPalette[$_GET['id']]?></h5>
					</div>
				</div>

				<div class="row pb-2">
					<div class="col">
						<table class="table table-sm">
							<thead class="thead-dark">
								<tr>
									<th>Code article</th>
									<th>Code Dossier</th>
									<th>Désignation</th>
									<th>EAN</th>

								</tr>
							</thead>
							<tbody>

								<?php foreach ($detailPalette as $key => $article): ?>
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

			</div>
		</div>

	<?php endif ?>
<div class="row pb-3">
	<div class="col"></div>
</div>


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