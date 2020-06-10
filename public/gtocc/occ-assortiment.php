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
	$req=$pdoBt->prepare("SELECT id_palette, palette  FROM occ_articles LEFT JOIN occ_palettes ON occ_articles.id_palette=occ_palettes.id WHERE code_article= :code_article");
	$req->execute([
		':code_article'	=>$article

	]);
	return $req->fetch(PDO::FETCH_ASSOC);


}

function getPalette($pdoBt,$id){
	$req=$pdoBt->prepare("SELECT occ_articles.*  FROM occ_articles LEFT JOIN occ_palettes ON occ_articles.id_palette=occ_palettes.id
		WHERE id_palette= :id_palette ");
	$req->execute([
		':id_palette'	=>$id

	]);
	// return $req->errorInfo();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

$listAssortiment=getAssortiment($pdoQlik);
$detailPalette="";
$arrPalette=OccHelpers::arrayPalette($pdoBt);


if(isset($_GET['id'])){
	$detailPalette=getPalette($pdoBt, $_GET['id']);


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
	<h1 class="text-main-blue py-5 ">Assortiment - Leclerc Occasion</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row">
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
							<td><?=empty($palette)?"":'<a href="occ-assortiment.php?id='.$palette['id_palette'].'#detailPalette">'.$palette['palette'].'</a>'?></td>
						</tr>
					<?php endforeach ?>

				</tbody>
			</table>

		</div>
	</div>

	<?php if (!empty($detailPalette)): ?>
		<div class="row my-3" id="detailPalette">
			<div class="col">
				<h5 class="text-main-blue">Contenu de la palette <?=$arrPalette[$_GET['id']]?></h5>
			</div>
		</div>

		<div class="row pb-5">
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
	<?php endif ?>


	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>