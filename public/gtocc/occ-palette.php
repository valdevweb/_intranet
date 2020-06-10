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

$paletteMgr=new OccPaletteMgr($pdoBt);
$paletteClos=$paletteMgr->getListPaletteDetailByStatut(1);
$paletteEncours=$paletteMgr->getListPaletteDetailByStatut(0);




//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);




// echo "<pre>";
// print_r($paletteClos);
// echo '</pre>';




//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];





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
	<?php if ($_SESSION['type']=='btlec'): ?>

		<div class="row">
			<div class="col">
				<h1>Palettes en cours de préparation</h1>

			</div>
		</div>

		<?php if (!empty($paletteEncours)): ?>
			<?php foreach ($paletteEncours as $key => $palette): ?>

				<div class="row my-3" id="detailPalette">
					<div class="col">
						<h5 class="text-main-blue">Contenu de la palette <?=$palette[0]['palette'] ?></h5>
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

		<?php endif ?>

		<div class="row">
			<div class="col">
				<h1>Palettes préparées</h1>

			</div>
		</div>


	<?php endif ?>


	<?php if (!empty($paletteClos)): ?>
		<?php foreach ($paletteClos as $key => $palette): ?>

			<div class="row my-3" id="detailPalette">
				<div class="col">
					<h5 class="text-main-blue">Contenu de la palette <?=$palette[0]['palette'] ?></h5>
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

	<?php endif ?>

	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>