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
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';


require_once '../../Class/OccPaletteMgr.php';
require '../../Class/UserHelpers.php';
require '../../Class/OccHelpers.php';





 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

$paletteMgr=new OccPaletteMgr($pdoOcc);
$paletteMag=$paletteMgr->getListCommandeByMag($_SESSION['id_web_user']);



$arrayListPalette=OccHelpers::arrayPalette($pdoOcc);

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
	<h1 class="text-main-blue py-5 ">Vos commandes Leclerc Occasion</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

<?php if (!empty($paletteMag)): ?>
		<div class="row pb-2">
			<div class="col">
				<table class="table table-sm shadow table-striped borderless">
					<thead class="thead-dark">
						<tr>
							<th>Cde n°</th>
							<th>Date commande</th>
							<th>Date expédition</th>
							<th>Détail</th>
							<th>BL</th>

						</tr>
					</thead>
					<tbody>
						<?php foreach ($paletteMag as $key => $palette): ?>
							<tr>
								<td><?=$palette['id_cde']?></td>
								<td><?=date('d-m-Y', strtotime($palette['date_insert']))?></td>

								<td><?= !empty($palette['date_exp'])? date('d-m-Y', strtotime($palette['date_exp'])):""  ?></td>
								<td><div class="btn btn-primary detail-btn" data-btn-id="<?=$palette['id_cde']?>">Voir le détail</div></td>
								<td><a href="occ-mag-cdes-pdf.php?id=<?=$palette['id_cde']?>" class="btn btn-danger detail-btn" target="_blank" ><i class="fas fa-file-pdf pr-3"></i>Bon de livraison</a></td>
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
						Aucune commande
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