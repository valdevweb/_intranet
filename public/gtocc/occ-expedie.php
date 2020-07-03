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
require_once '../../Class/OccPaletteMgr.php';
require '../../Class/UserHelpers.php';
require '../../Class/OccHelpers.php';


//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];


$paletteMgr=new OccPaletteMgr($pdoBt);
$paletteExpediees=$paletteMgr->getListCommandeByStatut(3);



$arrayListPalette=OccHelpers::arrayPalette($pdoBt);


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


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
	<h1 class="text-main-blue py-5 ">Expéditions Leclerc Occasion</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<?php if (!empty($paletteExpediees)): ?>
		<div class="row pb-2">
			<div class="col">
				<table class="table table-sm shadow table-striped borderless">
					<thead class="thead-dark">
						<tr>
							<th>Cde n°</th>
							<th>Magasin</th>
							<th>Date commande</th>
							<th>Détail</th>

						</tr>
					</thead>
					<tbody>
						<?php foreach ($paletteExpediees as $key => $palette): ?>
							<tr>
								<td><?=$palette['id_cde']?></td>

								<td><?= UserHelpers::getMagInfoByIdWebUser($pdoUser, $pdoMag, $palette['id_web_user'], 'deno_sca')  ?></td>
								<td><?=$palette['date_insert']?></td>
								<td><div class="btn btn-primary detail-btn" data-btn-id="<?=$palette['id_cde']?>">Voir le détail</div></td>
							</tr>
							<tr class="borderless">

								<td colspan="6" class="mx-auto text-center">
									<?php

									$infoCde=$paletteMgr->getCdeByIdCde($palette['id_cde']);

									?>
									<table class="table more w-auto ml-5" data-table-id="<?=$palette['id_cde']?>">
										<tr>
											<td colspan="4" class="font-weight-bold">Détail de la commande : </td>
										</tr>
										<tr>
											<th>Palette</th>
											<th>EAN</th>
											<th>Désignation</th>
											<th class="text-right">Quantité</th>
										</tr>
										<tbody>
											<?php foreach ($infoCde as $key => $cde): ?>
												<?php
												if(!empty($cde['id_palette'])){
													$article=$cde['code_article'];
													$designation=$cde['designation'];
													$ean=$cde['ean'];
													$qte=$cde['quantite'];
													$palette=$arrayListPalette[$cde['id_palette']];
												}else{
													$article=$cde['article_occ'];
													$designation=$cde['design_occ'];
													$ean=$cde['ean_occ'];
													$qte=$cde['qte_cde'];
													$palette="";

												}

												?>

												<tr >
													<td ><?=$palette?></td>
													<td ><?=$ean?></td>
													<td ><?=$designation?></td>
													<td class="text-right"><?=$qte?></td>
												</tr>
											<?php endforeach ?>
										</tbody>
									</table>
								</td>
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
						Aucune palette expédiée
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col text-right"><a href="#top">Haut</a></div>
			</div>

		<?php endif ?>

		<!-- ./container -->
	</div>
<script type="text/javascript">
	$( document ).ready(function() {

		$('table.more').hide();
		$('.detail-btn').on("click", function(){
			var id= $(this).data("btn-id");
			console.log(id);
			if($('table[data-table-id="'+id+'"]').is(":visible")){
				$('table[data-table-id="'+id+'"]').hide();

			}else{
				$('table[data-table-id="'+id+'"]').show();
			}
		});



	});

</script>
	<?php
	require '../view/_footer-bt.php';
	?>