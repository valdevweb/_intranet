<?php
// ---------------------------------------------------
// SESSION & AUTOLOAD
//----------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
}

// require 'pdfgenmail.php';
//-----------------------------------------------------
//	css dynamique
//-----------------------------------------------------
$pageCss=(basename(__FILE__));
$pageCss=explode(".php",$pageCss);
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

function convertArray($data, $field,$separator){
	if(!empty($data)){
		if (preg_match('</a>', $separator)){
			$rValue='';
			foreach ($data as $key => $value) {
				$rValue.='<a class="text-norm" href="mailto:'.$value[$field].'">'.$value[$field].$separator;
			}
			return $rValue;
		}else{
			$rValue='';
			foreach ($data as $key => $value) {
				$rValue.=$value[$field].$separator;
			}
			return $rValue;
		}
	}
	return '';
}


if(isset($_SESSION['code_bt'])){
	require_once '../../Class/MagDbHelper.php';
	require_once '../../Class/Mag.php';
	require_once '../../Class/UserHelpers.php';

	require "../../functions/stats.fn.php";
	addRecord($pdoStat,basename(__file__),'consultation', "fiche mag profil mag", 101);

	$magDbHelper=new MagDbHelper($pdoMag);
	$mag=$magDbHelper->getMagBt($_SESSION['code_bt']);
	$listCentralesSca=$magDbHelper->getDistinctCentraleSca();
	$centreRei=$magDbHelper->centreReiToString($mag->getCentreRei());
	$listCm=UserHelpers::getUserByService($pdoUser, 17);
	$ad2=!empty($mag->getAd2()) ? $mag->getAd2().'<br>' :'';

	// // ld
	$ldRbt=$magDbHelper-> getMagLd($mag->getId(),'-RBT');
	$ldRbtName=(!empty($ldRbt))? '<a class="text-orange" href="mailto:'.$ldRbt[0]['ld_full'].'">'.$ldRbt[0]['ld_full'].'</a>':  $mag->getRacineList()."-RBT";
	$ldRbtLink=convertArray($ldRbt,'email','</a><br>');
	$ldRbtExist=(!empty($ldRbt))? true:  false;
	$ldRbtLink=(!empty($ldRbtLink))? $ldRbtLink:  "Aucune adresse RBT";

	$ldDir=$magDbHelper-> getMagLd($mag->getId(),'-DIR');
	$ldDirName=(!empty($ldDir))? '<a class="text-orange" href="mailto:'.$ldDir[0]['ld_full'].'">'.$ldDir[0]['ld_full'].'</a>':  $mag->getRacineList()."-DIR";
	$ldDirLink=convertArray($ldDir,'email','</a><br>');
	$ldDirExist=(!empty($ldDir))? true:  false;
	$ldDirLink=(!empty($ldDirLink))? $ldDirLink : "Aucune adresse directeur";

	$ldAdh=$magDbHelper-> getMagLd($mag->getId(),'-ADH');
	$ldAdhName=(!empty($ldAdh))? '<a class="text-orange" href="mailto:'.$ldAdh[0]['ld_full'].'">'.$ldAdh[0]['ld_full'].'</a>':  $mag->getRacineList()."-ADH :";
	$ldAdhLink=convertArray($ldAdh,'email','</a><br>');
	$ldAdhExist=(!empty($ldAdh))? true:  false;
	$ldAdhLink=(!empty($ldAdhLink))? $ldAdhLink: "Aucune adresse adhérent dans ";

	$docubaseLink="http://172.30.101.66/rheaweb/controler?cmd=home&baseid=1&view=1&j_username=".$mag->getDocubaseLogin() ."&j_password=".$mag->getDocubasePwd();

	if(!empty($mag->getCentraleSca())){
		// $centraleGessica=$mag->getCentrale();
		$centraleSca=$magDbHelper->centraleToString($mag->getCentraleSca());
	}else{
		$centraleSca="Pas de centrale renseignée";
	}

}



//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<div class="container">
	<div class="row pb-3 ">
		<div class="col">
			<h1 class="text-main-blue pt-5 "><?= 'Leclerc '.$mag->getDeno() ?></h1>

			<h5 class="yanone">Code BTLec :
				<span class="text-orange" >
					<?= $mag->getId() ?>
				</span>
				<span class="pl-5">Panonceau Galec : </span>
				<span class="text-orange">
					<?= $mag->getGalec()?>
				</span>
				<span class="pl-5"> Centrale :</span>
				<span class="text-orange ">
					<?=$centraleSca?>
				</span>
			</h5>

		</div>
	</div>
	<div class="row yanone pb-3 mb-3 ">
		<div class="col">
			<div class="row">
				<div class="col">
					<span class="text-orange">
						<i class="fas fa-map-marked pr-3"></i>
						<?= 'Leclerc '.$mag->getDeno()?>
					</span>
					<?= !empty($mag->getDateFermeture())? '<span class="text-orange pl-5">Fermé le : </span>' .$mag->getDateFermetureFr() :''?>
				</div>

			</div>

			<div class="row">
				<div class="col">
					<div class="pl-2  border-left-dashed">
						<?= $mag->getAd1()?><br>
						<?= $ad2?>
						<?= $mag->getCp() . ' '. $mag->getVille()?>

					</div>
				</div>

			</div>
		</div>
		<div class="col-3 border-left-dashed">

			<i class="fas fa-user pr-3 text-orange "></i>
			<?= $mag->getAdherent()?><br>

			<br>
			<i class="fas fa-phone pr-3 text-orange"></i>
			<?= $mag->getTel();?>
		</div>
		<div class="col-3">
			<br><br>
			<span class="border-left-dashed-simple"></span><i class="fas fa-fax pr-3 text-orange"></i>
			<?= $mag->getFax()?>
		</div>
	</div>
	<div class="bg-separation-small"></div>

</div>

</section>

<div class="container">
	<section class="second-container">
		<div class="row">
			<div class="col">
				<!-- <div class="sub-title-ico  pt-3"><i class="fas fa-user-lock text-light-grey "></i></div> -->
				<h5 class="text-center font-weight-bold  sub-title" id="identifiants">Identifiants</h5>
			</div>
		</div>
		<div class="row yanone light-shadow-round py-3 mb-3">
			<div class="col-auto">
				<div class="text-orange"> <img src="../img/logos/docubase-logo.png" class="float-left pr-3"> Docubase :</div>
			</div>
			<div class="col">
				<span class="text-orange pl-3" >Login :</span> <?= $mag->getDocubaseLogin()?><a href="<?=$docubaseLink?>" target="_blank"><i class="fas fa-external-link-alt pl-5 fa-sm"></i></a> <br>
				<span class="text-orange pl-3">Mot de Passe : </span> <?= $mag->getDocubasePwd() ?><br>

			</div>
			<div class="col-auto">
				<div class="text-orange"><img src="../img/logo_bt/bt-rond-20.jpg" class="float-left pr-3">Portail :</div>
			</div>
			<div class="col">
				<?php if (!empty($webusers)): ?>
					<?php foreach ($webusers as $key => $webuser): ?>
						<div class="row">
							<div class="col">
								<span class="text-orange pl-3">Login :</span> <?= $webuser['login']?> <br>
								<span class="text-orange pl-3">Mot de Passe : </span> <?= $webuser['nohash_pwd']?><br>
							</div>
							<div class="col">
								<span class="text-orange pl-3">Ident : </span><?= $webuser['id_web_user']?><br>
							</div>
						</div>
					<?php endforeach ?>

					<?php else: ?>
						Ce magasin n'a pas de compte sur le portail
					<?php endif ?>

				</div>
			</div>

			<div class="bg-separation-small"></div>
			<h5 class="text-center font-weight-bold  mag-title sub-title">Informations Complémentaires</h5>

			<div class="row pb-3">
				<div class="col py-3 light-shadow-round">

					<div class="row yanone ">
						<div class="col-3">
							<span class="text-orange">Acdlec : </span>
							<?= $mag->getAcdlec();?>
						</div>
						<div class="col">
							<i class="fas fa-arrows-alt-h pr-3 text-orange"></i>
							<?= $mag->getSurfaceStrg();?>
						</div>
						<div class="col-3">
							<span class="text-orange">Pôle SAV Gessica : </span>

							<?= $mag->getPoleSavSca()?>
							<span class="text-orange pl-3">CTBT : </span>
							<?= $mag->getPoleSavCtbt()?>

						</div>
						<div class="col">
							<span class="text-orange">Antenne : </span>

							<?= $mag->getAntenne();?>
						</div>

					</div>
					<div class="row yanone">
						<div class="col-3">
							<span class="text-orange">TVA : </span>
							<?= $mag->getTva();?>

						</div>
						<div class="col-3">
							<span class="text-orange">Siret : </span>
							<?= $mag->getSiret();?>

						</div>
						<div class="col-3">
							<span class="text-orange">Code REI : </span>
							<?= $mag->getRei();?>
						</div>
						<div class="col-3">
							<span class="text-orange">Centre de redevance : </span>
							<?= $centreRei?>

						</div>
					</div>
					<div class="row yanone">
						<div class="col-3">
							<span class="text-orange">Gestion du réservable :</span>
							<?= $mag->getReservableStr()?>
						</div>
						<div class="col-3">
							<span class="text-orange">Backoffice :</span>
							<?= $mag->getBackofficeStr()?>
						</div>
						<div class="col-3">
							<span class="text-orange">Chargé de mission :</span>

							<?= UserHelpers::getFullname($pdoUser, $mag->getIdCmWebUser())?>
						</div>
					</div>
				</div>

			</div>
			<div class="bg-separation-small"></div>
			<!-- <div class="sub-title-ico pt-3"><i class="fas fa-at text-light-grey"></i></div> -->
			<div class="row">
				<div class="col"></div>
				<div class="col">
					<h5 class="text-center font-weight-bold  sub-title" id="ld">Listes de diffusion</h5>
				</div>
				<div class="col">
					<div class="alert alert-warning">
						<i class="far fa-lightbulb pr-1"></i>
						<span class="smaller"> Cliquez sur une adresse/une LD pour envoyer un lotus</span>
					</div>
				</div>

			</div>
			<div class="row py-3">
				<div class="col light-shadow-round">
					<div class="row yanone pt-3">
						<div class="col text-orange"><?=$ldAdhName?></div>
						<div class="col text-orange"><?=$ldDirName?></div>
						<div class="col text-orange"><?=$ldRbtName?></div>
					</div>
					<div class="row yanone pb-3">
						<div class="col"><div class="pl-2 border-left"><?=$ldAdhLink?></div></div>
						<div class="col"><div class="pl-2 border-left"><?=$ldDirLink?></div> </div>
						<div class="col"><div class="pl-2 border-left"><?=$ldRbtLink?></div></div>
					</div>
				</div>
			</div>
			<div class="bg-separation-small"></div>
			<!-- <div class="sub-title-ico  pt-3"><i class="fas fa-calendar text-light-grey"></i></div> -->

			<div class="bg-separation-small"></div>





		</div><!-- fin de container -->


		<?php require '../view/_footer-bt.php'; ?>
