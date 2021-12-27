<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


require '../../Class/Db.php';
require '../../Class/ProspectusDao.php';
require '../../Class/OffreDao.php';

$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');
$today=new Datetime();

$prospDao=new ProspectusDao($pdoDAchat);
$offreDao=new OffreDao($pdoDAchat);

if(isset($_POST['date_saisie'])){
	$listProsp=$prospDao->getComingProspectusMagDateSaisie($_POST['date_saisie']);
	$listOffre=$offreDao->getOffreEncoursByProspDateSaisie($_POST['date_saisie']);

}else{

	$listProsp=$prospDao->getComingProspectusMag();
	$listOffre=$offreDao->getOffreEncoursByProsp();
}

$listFiles=$prospDao->getComingProspectusFiles();
$listLinks=$prospDao->getComingProspectusLinks();



// $prospDao->testDate((new Datetime('2021-03-15'))->setTime(00, 01), (new Datetime('2021-03-23'))->setTime(23, 01));




//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container pb-5">
	<div class="row pt-5 pb-3">
		<div class="col">
			<h1 class="text-main-blue">Les offres TEL/BRII</h1>
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
	<div class="row">
		<div class="col">
			<div class="  ">
				<div class="row">
					<div class="col">
						<div class="alert alert-primary">
							<i class="fas fa-lightbulb pr-3"></i><b>Astuce : </b>Filtrer les offres par date de saisie pour afficher les offres que vous n'avez pas encore vu
							<div class="row">
							<div class="col"></div>
							<div class="col-auto mt-3">
								<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
									<div class="form-group">
										<input type="date" class="form-control" name="date_saisie" id="date_saisie" onchange='this.form.submit()' value="<?=$_POST['date_saisie']??""?>">
									</div>
								</form>
							</div>
							<div class="col"></div>
						</div>
						</div>

					</div>
				</div>


			</div>
		</div>
		<div class="col-auto">
			<div class="row bg-grey rounded py-2">
				<div class="col-auto">Recherche :</div>
				<div class="col-auto text-right ">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" id="search_form">
						<div class="form-group text-center no-margin">
							<input type="text" class="form-control w-180" name="str" id="str" style="font-family:'Font Awesome 5 Free',sans-serif !important; font-weight: 900 !important;" type="text" placeholder="&#xf002">
						</div>
					</form>
				</div>
			</div>

		</div>
	</div>

	<?php foreach ($listProsp as $key => $prosp): ?>
		<div class="row oneop" id="op-<?=$prosp['id']?>">
			<div class="col">
				<div class="row pt-5">
					<div class="col text-center">
						<h5 class="text-main-blue no-margin"><?=$prosp['prospectus']?> - <?=$prosp['code_op']?></h5>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col text-center">
						<div class="badge badge-orange">du <?=date('d-m-Y', strtotime($prosp['date_start']))?> au <?=date('d-m-Y', strtotime($prosp['date_end']))?></div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="row  text-main-blue font-weight-boldless">
							<div class="col">
								Fichier ficwopc :
							</div>
							<div class="col text-center border-left border-right">
								Fichiers relatifs aux offres :
							</div>
							<div class="col">
								Liens web :
							</div>
						</div>
						<div class="row  mb-3">
							<div class="col ">
								<a class="grey-link" href="<?=URL_UPLOAD?>ficwopc/<?=$prosp['fic']?>" target="_blank"><?=$prosp['fic']?></a>
							</div>
							<div class="col text-center border-left border-right ">
								<?php if (isset($listFiles[$prosp['id']])): ?>
									<?php foreach ($listFiles[$prosp['id']] as $key => $file): ?>
										<a class="grey-link" href="<?=URL_UPLOAD?>offres/<?=$file['file']?>"  target="_blank"><?=!empty($file['filename'])?$file['filename']:$file['file']?></a><br>
									<?php endforeach ?>

								<?php endif ?>
							</div>
							<div class="col ">
								<?php if (isset($listLinks[$prosp['id']])): ?>

									<?php foreach ($listLinks[$prosp['id']] as $key => $link): ?>
										<a class="grey-link" href="<?=$link['link']?>" target="_blank"><?=!empty($link['linkname'])?$link['linkname']:$link['link']?></a><br>
									<?php endforeach ?>

								<?php endif ?>
							</div>
						</div>
					</div>
				</div>
				<?php if (isset($listOffre[$prosp['id']])): ?>

					<?php

					?>
					<div class="row pb-3">
						<div class="col">
							<table class="table table-sm shadow-sm">
								<thead class="thead-light">
									<tr>

										<th>Marque</th>
										<th>Produit</th>
										<th>Référence</th>
										<th>EAN</th>
										<th class="text-right">GT</th>
										<th class="text-right">PVC</th>
										<th class="text-right">Montant</th>
										<th class="text-right">Financé</th>
										<th>Offre</th>
										<th>Commentaire</th>

									</tr>
								</thead>
								<tbody>
									<?php foreach ($listOffre[$prosp['id']] as $key => $offre): ?>
										<tr  >
											<td><?=$offre['marque']?></td>
											<td><?=$offre['produit']?></td>
											<td><?=$offre['reference']?></td>
											<td><?=$offre['ean']?></td>
											<td class="text-right"><?=$offre['gt']?></td>
											<td class="text-right"><?=$offre['pvc']?></td>
											<td class="text-right">
												<?=($offre['euro']==1)?str_replace('.','&euro;',$offre['montant']):round($offre['montant']).'%'?>
											</td>
											<td class="text-right">
												<?=($offre['euro']==1)?str_replace('.','&euro;',$offre['montant_finance']):round($offre['montant_finance']).'%'?>
											</td>
											<td><?=($offre['offre']==1)?"<span class='badge badge-primary'>BRII</span>":"<span class='badge badge-orange'>TEL</span>"?></td>
											<td><?=$offre['cmt']?></td>

										</tr>
									<?php endforeach ?>

								</tbody>
							</table>
						</div>
					</div>
					<div class="bg-separation-thin"></div>
				</div>
			</div>
		<?php endif ?>
	<?php endforeach ?>

	<div class="row  py-5">
		<div class="col-auto">
			<h6 class="text-main-blue ">Exporter les offres en cours au format excel :</h6>
		</div>
		<div class="col pr-2">
			<a href="xl-offres.php" class="btn bg-green">Export Excel</a>
		</div>
	</div>

</div>
<script src="../js/search-in-window.js"></script>
<script type="text/javascript">
	$('.instruction').hide();
	$.fn.gparent = function( recursion ){
		console.log( 'recursion: ' + recursion );
		if( recursion > 1 ) return $(this).parent().gparent( recursion - 1 );
		return $(this).parent();
	};

	document.getElementById('search_form').onsubmit = function() {
		findString(this.str.value);
		return false;
	};






</script>
<?php
require '../view/_footer-bt.php';
?>