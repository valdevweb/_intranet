<?php
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


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';
require 'casse-getters.fn.php';
require '../../Class/Helpers.php';

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
require "../../functions/stats.fn.php";
addRecord($pdoStat,basename(__file__),'consultation', "histo casses", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
$exps=getClosExp($pdoCasse);

foreach ($exps as $key => $exp) {
	$newExp[$exp['expid']]['datedelivery']=$exp['datedelivery'];
	$newExp[$exp['expid']]['datefac']=$exp['datefac'];
	$newExp[$exp['expid']]['certificat']=$exp['certificat'];
	$newExp[$exp['expid']]['mt_fac']=$exp['mt_fac'];
	$newExp[$exp['expid']]['mt_blanc']=$exp['mt_blanc'];
	$newExp[$exp['expid']]['mt_brun']=$exp['mt_brun'];
	$newExp[$exp['expid']]['mt_gris']=$exp['mt_gris'];
	$newExp[$exp['expid']]['file']=$exp['file'];
	$newExp[$exp['expid']]['palette'][]=$exp['palette'];
	$newExp[$exp['expid']]['paletteid'][]=$exp['paletteid'];
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
	<div class="row">
		<div class="col">
			<h1 class="text-main-blue py-5 ">Historique des casses enlevées</h1>

		</div>
		<div class="col">
			<?= Helpers::returnBtn('bt-casse-dashboard.php'); ?>

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
	<div class="row pb-5">
		<div class="col-lg-1"></div>
		<div class="col">
			<!-- start table -->
			<table class="table table-sm table-bordered">
				<thead class="thead-dark">
					<tr>
						<th>N° expé</th>
						<th>Palettes</th>
						<th>Date d'expé</th>
						<th>Date facture</th>
						<th class="text-center">Certificat</th>
						<th class="text-right">Facturé</th>
						<th class="text-right">Avoir Blanc</th>
						<th class="text-right">Avoir Brun</th>
						<th class="text-right">Avoir Gris</th>
						<th class="text-center">AR facture</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($newExp as $key => $exp): ?>
						<tr>
							<td><?=$key?></td>
							<td>
								<?php for ($i=0; $i <count($exp['palette']) ; $i++):?>
									<a href="detail-palette.php?id=<?=$exp['paletteid'][$i]?>">
									<?=$exp['palette'][$i]?></a>,
								<?php endfor ?>
							</td>
							<td><?=$exp['datedelivery']?></td>
							<td><?=$exp['datefac']?></td>
							<td class="text-center">
								<?php if (!empty($exp['certificat'])): ?>
									<a href="<?=UPLOAD_DIR.'/casse/'.$exp['certificat']?>"><i class="far fa-file-alt"></i></a>
								<?php endif ?>
							</td>
							<td class="text-right"><?=$exp['mt_fac']?></td>
							<td class="text-right"><?=$exp['mt_blanc']?></td>
							<td class="text-right"><?=$exp['mt_brun']?></td>
							<td class="text-right"><?=$exp['mt_gris']?></td>
							<td class="text-center">

								<?php if (!empty($exp['file'])): ?>
									<a href="<?=UPLOAD_DIR.'/casse/'.$exp['file']?>"><i class="far fa-file-alt"></i></a>
								<?php endif ?>

							</td>
						</tr>


					<?php endforeach ?>

				</tbody>
			</table>
			<!-- ./table -->
		</div>
		<!-- <div class="col-lg-1"></div> -->
	</div>


	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>