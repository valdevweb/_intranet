
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
require '../../Class/MagHelpers.php';

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
// $arMagFac=[];
foreach ($exps as $key => $exp) {
	$newExp[$exp['expid']]['datedelivery']=$exp['datedelivery'];
	$newExp[$exp['expid']]['datefac']=$exp['datefac'];
	$newExp[$exp['expid']]['certificat']=$exp['certificat'];
	$newExp[$exp['expid']]['mt_fac']=$exp['mt_fac'];
	$newExp[$exp['expid']]['mt_blanc']=$exp['mt_blanc'];
	$newExp[$exp['expid']]['mt_brun']=$exp['mt_brun'];
	$newExp[$exp['expid']]['mt_gris']=$exp['mt_gris'];
	$newExp[$exp['expid']]['file']=$exp['file'];
	$newExp[$exp['expid']]['btlec']=$exp['btlec'];
	$newExp[$exp['expid']]['galec']=$exp['galec'];
	$newExp[$exp['expid']]['palette'][]=$exp['palette'];
	$newExp[$exp['expid']]['paletteid'][]=$exp['paletteid'];
}
// calcul somme pa mag
foreach ($newExp as $key => $value) {
	if($value['mt_fac']!=''){
		if (isset($arMagFac[$value['galec']])) {
			$arMagFac[$value['galec']]=$value['mt_fac'] +$arMagFac[$value['galec']];
		}else{
			$arMagFac[$value['galec']]=$value['mt_fac'];
		}
	}
}
$nbMt=count($arMagFac);
$sumCasse=sumDeclare($pdoCasse);
$nbMagCol=ceil($nbMt/2);
$casseHs=getCasseHS($pdoCasse);
$wait=getExpWaiting($pdoCasse);
$noexpYet=getNoExpYet($pdoCasse);
$pending=$wait['sumvalo']+$noexpYet['sumvalo'];

$lig=1;
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
	<!-- barre de titre -->
	<div class="row">
		<div class="col">
			<h1 class="text-main-blue pt-5 ">Historique des casses enlevées</h1>
		</div>
		<div class="col">
			<?= Helpers::returnBtn('bt-casse-dashboard.php'); ?>
		</div>
	</div>
	<!-- zone de message -->
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<!-- recap montants -->
	<div class="row mb-3 p-3">
		<div class="col-lg-1"></div>
		<div class="col  bg-light-blue px-5 pb-3 font-weight-bold">
			<!-- montant total -->
			<div class="row pt-3">
				<div class="col text-center">
					Montant total déclaré :
				</div>
			</div>
			<div class="row">
				<div class="col text-center">
					<h5 class="text-red"><?=number_format((float)$sumCasse['valoTotal'],2,'.',' ')?>&euro; </h5>
					<hr>
				</div>
			</div>
			<!-- casse hs -->
			<div class="row ">
				<div class="col text-center">
					Montant produits détruits :<br>
					<i class="fas fa-trash pr-3 text-orange"></i>
				</div>
			</div>
			<div class="row ">
				<div class="col text-center text-orange font-weight-bold">
					<?= number_format((float)$casseHs['sumhs'],2,'.', ' ')?>&euro;

				</div>
			</div>
		<!-- a traiter -->
			<div class="row ">
				<div class="col text-center">
					Montant en attente de traitement :<br>
					<i class="fas fa-hourglass-half pr-3 text-greenwait"></i>
				</div>
			</div>
			<div class="row pb-2">
				<div class="col text-center text-greenwait font-weight-bold">
					<?= number_format((float)$pending,2,'.', ' ')?>&euro;
				</div>
			</div>
			<!-- facturé -->
			<div class="row">
				<div class="col text-center">
					Montant facturé  :<br>
					<i class="fas fa-coins text-fac pr-3"></i>
				</div>
			</div>
			<div class="row">
				<div class="col text-center">
					<span class="text-fac"><?= number_format((float)array_sum($arMagFac) ,2,'.',' ')  ?>&euro;</span>
				</div>
			</div>
			<!-- repartition -->
			<div class="row ">
				<div class="col text-center">
					Répartition magasin :
				</div>
			</div>
			<div class="row">
				<div class="col">
					<ul class="leaders">
						<?php foreach ($arMagFac as $galec => $mt): ?>
							<?php if ($lig<=$nbMagCol): ?>
								<li>
									<span class="text-main-blue heavy"><?=MagHelpers::deno($pdoUser,$galec)?></span>
									<span class="text-right text-fac font-weight-bold"><?= number_format((float)$mt,2,'.',' ') ?>&euro;</span>
								</li>

								<?php $lig++?>
								<?php else: ?>
									<?php $lig=1?>
								</ul>
							</div>
							<div class="col">
								<ul class="leaders">
									<li>
										<span class="text-main-blue heavy"><?=MagHelpers::deno($pdoUser,$galec)?></span>
										<span class="text-right text-fac font-weight-bold"><?= number_format((float)$mt,2,'.',' ') ?>&euro;</span>
									</li>
								<?php endif ?>
							<?php endforeach ?>
						</ul>
					</div>
				</div>
				<div class="row pb-2">
					<div class="col text-center up">
						<span class="smaller font-italic font-weight-normal ">(Avoirs à déduire)</span>
					</div>
				</div>
			</div>
			<div class="col-lg-1"></div>
		</div>
		<div class="row pb-5">
			<div class="col">
				<!-- start table -->
				<table class="table table-sm table-bordered table-striped">
					<thead class="thead-dark">
						<tr>
							<th>N° <br>expé</th>
							<th>Palettes</th>
							<th>Date<br>d'expé</th>
							<th>Date<br> facture</th>
							<th class="text-center">Facturé<br>/<br>expédié à</th>
							<th class="text-right">Montant</th>
							<th class="text-right">Avoir<br> Blanc</th>
							<th class="text-right">Avoir<br> Brun</th>
							<th class="text-right">Avoir<br> Gris</th>
							<th class="text-center">AR<br> facture</th>
							<th class="text-center">DEEE</th>

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
									<td class="text-right"><?=$exp['datedelivery']?></td>

									<td class="text-right"><?=$exp['datefac']?></td>
									<td clas="text-right"><?=$exp['btlec']?></td>

									<td class="text-right"><?=$exp['mt_fac']?></td>
									<td class="text-right"><?=$exp['mt_blanc']?></td>
									<td class="text-right"><?=$exp['mt_brun']?></td>
									<td class="text-right"><?=$exp['mt_gris']?></td>
									<td class="text-center">

										<?php if (!empty($exp['file'])): ?>
											<a href="<?=UPLOAD_DIR.'/casse/'.$exp['file']?>"><i class="far fa-file-alt"></i></a>
										<?php endif ?>

									</td>
									<td class="text-center">
										<?php if (!empty($exp['certificat'])): ?>
											<a href="<?=UPLOAD_DIR.'/casse/'.$exp['certificat']?>"><i class="far fa-file-alt"></i></a>
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