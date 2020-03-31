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
require_once '../../vendor/autoload.php';
require_once '../../Class/MagDbHelper.php';
require_once '../../Class/Mag.php';
require_once '../../Class/Helpers.php';

//---------------------------------------
//	STRUCTURE PAGE HTML
//---------------------------------------
/*





 */


//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
require "../../functions/stats.fn.php";
addRecord($pdoStat,basename(__file__),'consultation', "fiche mag", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
// $searchResults=false;
function convertArray($data, $field,$separator){
	if(!empty($data)){
		$rValue='';
		foreach ($data as $key => $value) {
			$rValue.=$value[$field].$separator;
		}
		return $rValue;
	}
	return '';
}

function updateSca($pdoMag){
	$req=$pdoMag->prepare("UPDATE sca3 SET galec_sca= :galec_sca, deno_sca= :deno_sca, ad1_sca= :ad1_sca, ad2_sca= :ad2_sca, ad3= :ad3_sca, cp_sca= :cp_sca, ville_sca= :ville_sca, tel_sca= :tel_sca, fax_sca= :fax_sca, adherent_sca= :adh_sca, centrale_sca= :centrale_sca, centrale_doris= :centrale_doris, centrale_smiley= :centrale_smiley, surface_sca= :surface_sca, sorti= :sorti, date_ouverture= :date_ouverture, date_adhesion= :date_adhesion, date_fermeture= :date_fermeture, date_resiliation= :date_resiliation, date_sortie= :date_sortie, pole_sav_sca= :pole_sav_sca, nom_gesap= :gesap, affilie= :affilie, date_update= :date_update WHERE btlec_sca= :btlec_sca");

	$req->execute([
		':btlec_sca'		=>$_GET['id'],
		':galec_sca'		=>$_POST['galec_sca'],
		':deno_sca'		=>$_POST['deno_sca'],
		':ad1_sca'		=>$_POST['ad1_sca'],
		':ad2_sca'		=>$_POST['ad2_sca'],
		':ad3_sca'		=>$_POST['ad3_sca'],
		':cp_sca'		=>$_POST['cp_sca'],
		':ville_sca'		=>$_POST['ville_sca'],
		':tel_sca'		=>$_POST['tel_sca'],
		':fax_sca'		=>$_POST['fax_sca'],
		':adh_sca'		=>$_POST['adh_sca'],
		':centrale_sca'		=>(empty($_POST['centrale_sca']))? NULL:$_POST['centrale_sca'] ,
		':centrale_doris'		=>(empty($_POST['centrale_doris']))? NULL :$_POST['centrale_doris'],
		':centrale_smiley'		=>(empty($_POST['centrale_smiley']))? NULL :$_POST['centrale_smiley'],
		':surface_sca'		=>$_POST['surface_sca'],
		':sorti'		=>$_POST['sorti'],
		':date_ouverture'		=>(empty($_POST['date_ouverture']))? NULL:(DateTime::createFromFormat('d/m/Y',$_POST['date_ouverture']) )->format('Y-m-d'),
		':date_adhesion'		=>(empty($_POST['date_adhesion']))? NULL:(DateTime::createFromFormat('d/m/Y',$_POST['date_adhesion']))->format('Y-m-d'),
		':date_fermeture'		=>(empty($_POST['date_fermeture']))? NULL:(DateTime::createFromFormat('d/m/Y',$_POST['date_fermeture']))->format('Y-m-d'),
		':date_resiliation'		=>(empty($_POST['date_resiliation']))? NULL:(DateTime::createFromFormat('d/m/Y',$_POST['date_resiliation']))->format('Y-m-d'),
		':date_sortie'		=>(empty($_POST['date_sortie']))? NULL:( DateTime::createFromFormat('d/m/Y',$_POST['date_sortie']))->format('Y-m-d'),
		':pole_sav_sca'		=>(empty($_POST['pole_sav_sca']))? NULL:$_POST['pole_sav_sca'],
		':gesap'		=>$_POST['gesap'],
		':affilie'		=>$_POST['affilie'],
		':date_update'	=>date('Y-m-d H:i:s')
	]);
	return $req->rowCount();
}

function updateAutre($pdoMag){
	$req=$pdoMag->prepare("UPDATE mag SET id_type= :id_type WHERE id=id");
	$req->execute([
		':id'	=>$_GET['id'],
		':id_type'		=>$_POST['id_type']
	]);
	return $req->rowCount();
}
if(isset($_POST['clear_form'])){
	$_POST=[];
	header("Location: ".$_SERVER['PHP_SELF']);

}
if (isset($_GET['id'])){
	$magDbHelper=new MagDbHelper($pdoMag);
	$mag=$magDbHelper->getMagBt($_GET['id']);
	$histo= $magDbHelper->getHisto($mag->getGalec());
	$listCentralesSca=$magDbHelper->getDistinctCentraleSca();
	$webusers=$magDbHelper->getWebUser($mag->getGalec());
	$centreRei=$magDbHelper->centreReiToString($mag->getCentreRei());
	$listTypesMag=$magDbHelper-> getListType();

	// ld
	$ldRbt=$magDbHelper-> getMagLd($mag->getGalec(),'-RBT');
	$ldRbtName=(!empty($ldRbt))? $ldRbt[0]['ld_full']:  "Liste RBT :";
	$ldRbt=convertArray($ldRbt,'email','<br>');
	$ldRbt=(!empty($ldRbt))? $ldRbt:  "Aucune adresse RBT";
	$ldDir=$magDbHelper-> getMagLd($mag->getGalec(),'-DIR');
	$ldDirName=(!empty($ldDir))? $ldDir[0]['ld_full']:  "Liste directeur :";
	$ldDir=convertArray($ldDir,'email','<br>');
	$ldDir=(!empty($ldDir))? $ldDir : "Aucune adresse directeur";

	$ldAdh=$magDbHelper-> getMagLd($mag->getGalec(),'-ADH');
	$ldAdhName=(!empty($ldAdh))? $ldAdh[0]['ld_full']:  "Liste adhérent :";
	$ldAdh=convertArray($ldAdh,'email','<br>');
	$ldAdh=(!empty($ldAdh))? $ldAdh: "Aucune adresse adhérent";



	if(!empty($mag->getCentrale())){
		// $centraleGessica=$mag->getCentrale();
		$centraleGessica=$magDbHelper->centraleToString($mag->getCentrale());
	}else{
		$centraleGessica="Pas de centrale renseignée";
	}


	$ad2=!empty($mag->getAd2()) ? $mag->getAd2().'<br>' :'';


}

if(isset($_GET['id'])){
	$magDbHelper=new MagDbHelper($pdoMag);
	$mag=$magDbHelper->getMagBt($_GET['id']);

}

if(isset($_POST['maj'])){
	$up=updateSca($pdoMag);
	if(count($up)==1){
		$successQ='success=maj';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&'.$successQ,true,303);
	}else{
		$errors[]="Une erreur est survenue, impossible de mettre  à jour la base de donnée";
	}


}

if(isset($_POST['maj_autre'])){
	$up=updateAutre($pdoMag);
	if(count($up)==1){
		$successQ='success=maj';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&'.$successQ,true,303);
	}else{
		$errors[]="Une erreur est survenue, impossible de mettre  à jour la base de donnée";
	}

}

if(isset($_GET['success'])){
	$arrSuccess=[
		'maj'=>'Magasin mis à jour avec succès',
	];
	$success[]=$arrSuccess[$_GET['success']];
}

if(!isset($_SESSION['']))
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


//------------------------------------------------------
//			VIEW
//------------------------------------------------------



	include('../view/_head-bt.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->

<section class="info-main fixed-top">
<?php include('../view/_navbar.php');?>

	<div class="container">
		<div class="row">
			<div class="col-lg-1"></div>
			<div class="col">
				<?php
				include('../view/_errors.php');
				?>
			</div>
			<div class="col-lg-1"></div>
		</div>

		<?php if (!isset($mag)): ?>
			<div class="row  pb-3 ">
				<div class="col">
					<h1 class="text-main-blue pt-5 ">F</span>iche magasin</h1>
				</div>
				<?php
				include('search-form.php')
				?>
				<div class="col-auto">
					<?=Helpers::returnBtn('base-mag.php','btn-light-blue')?>
				</div>
				<!-- <div class="col-lg-1"></div> -->
			</div>
			<?php else: ?>
				<div class="row  pb-3 ">
					<div class="col">
						<h1 class="text-main-blue pt-5 ">
							<?= (isset($mag))? 'Leclerc '.$mag->getDeno(): "Fiche magasin" ?>
						</h1>
						<h5 class="yanone">Code BTLec : <span class="text-orange" ><?= $mag->getId() .'</span><span class="pl-5">Panonceau Galec : <span class="text-orange">'.$mag->getGalec().'</span>'?></h5>

					</div>
					<?php
					include('search-form.php')
					?>
					<div class="col-auto mt-4 pt-2">
						<?=Helpers::returnBtn('base-mag.php','btn-kaki')?>
					</div>
					<!-- <div class="col-lg-1"></div> -->
				</div>


				<!-- commun -->
				<div class="bg-separation-small"></div>
				<!-- <div class="sub-title-ico"><i class="fas fa-home text-light-grey pt-3"></i></div> -->
				<h5 class="text-center font-weight-bold  mag-title sub-title">Information générales</h5>

				<div class="row yanone py-3 light-shadow-round">
					<div class="col">
						<div class="text-orange">
							<i class="fas fa-map-marked pr-3"></i>
							<?= 'Leclerc '.$mag->getDeno()?>
						</div>

						<div class="pl-2  border-left-dashed">
							<?= $mag->getAd1()?><br>
							<?= $ad2?>
							<?= $mag->getCp() . ' '. $mag->getVille()?>

						</div>
					</div>
					<div class="col-3 border-left-dashed">

						<i class="fas fa-user pr-3 text-orange "></i>
						<?= $mag->getAdherent()?><br>

						<br><br>
						<i class="fas fa-phone pr-3 text-orange"></i>
						<?= $mag->getTel();?>
					</div>
					<div class="col-3">
						<br><br><br>
						<span class="border-left-dashed-simple"></span><i class="fas fa-fax pr-3 text-orange"></i>
						<?= $mag->getFax()?>
						<!-- <div class="text-center pr-5"><img src="../img/logos/leclerc-rond-50.jpg"></div> -->

					</div>

				</div>
			</div>
		</section>
	<?php endif?>
	<?php if (isset($mag)): ?>
	<div class="container">

		<section class="second-container">
			<h5 class="text-center font-weight-bold  mag-title sub-title">Informations Complémentairs</h5>

			<div class="row py-3">
				<div class="col py-3 light-shadow-round">

					<div class="row yanone ">
						<div class="col-3">
							<span class="text-orange">Centrale : </span>
							<?= $centraleGessica;?>
						</div>
						<div class="col">
							<i class="fas fa-arrows-alt-h pr-3 text-orange"></i>
							<?= $mag->getSurfaceStrg();?>
						</div>
						<div class="col-3">
							<span class="text-orange">Pole SAV : </span>

							<?= $mag->getPoleSavSca()?>
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
				</div>

			</div>
			<div class="bg-separation-small"></div>
			<!-- <div class="sub-title-ico pt-3"><i class="fas fa-at text-light-grey"></i></div> -->
			<h5 class="text-center font-weight-bold  sub-title ">Listes de diffusion</h5>

			<div class="row py-3">
				<div class="col light-shadow-round">
					<div class="row yanone pt-3">
						<div class="col text-orange"><?=$ldAdhName?></div>
						<div class="col text-orange"><?=$ldDirName?></div>
						<div class="col text-orange"><?=$ldRbtName?></div>
					</div>
					<div class="row yanone pb-3">
						<div class="col"><div class="pl-2 border-left"><?=$ldAdh?></div></div>
						<div class="col"><div class="pl-2 border-left"><?=$ldDir?></div> </div>
						<div class="col"><div class="pl-2 border-left"><?=$ldRbt?></div></div>
					</div>
				</div>
			</div>
			<div class="bg-separation-small"></div>
			<!-- <div class="sub-title-ico  pt-3"><i class="fas fa-calendar text-light-grey"></i></div> -->
			<h5 class="text-center font-weight-bold  sub-title">Historique magasin</h5>

			<div class="row light-shadow-round py-3 mb-3">
				<div class="col yanone">
					<div class="font-weight-bold"><i class="far fa-calendar pr-3 text-orange"></i> de <?=$mag->getDateOuvertureFr()?> à aujourd'hui :</div>
					<div class="pl-5"><?=$mag->getId() .' - '. $mag->getDeno()?></div>

					<?php if (!empty($histo)): ?>
						<?php foreach ($histo as $key => $prevMag): ?>

							<?= ($key+1==ceil((count($histo)+1)/2))? "</div><div class='col yanone'>" :'' ?>

							<div class="font-weight-bold"><i class="far fa-calendar pr-3 text-orange"></i><?=$prevMag['dateOuv'] .'<i class="fas fa-long-arrow-alt-right px-3"></i> '.$prevMag['dateFerm']?> :</div>
							<div class="pl-5"><?=$prevMag['btlec_old'] .' - '.$prevMag['deno_sca']?></div>


						<?php endforeach ?>

					<?php endif ?>
				</div>
			</div>
			<div class="bg-separation-small"></div>

			<div class="row">
				<div class="col">
					<!-- <div class="sub-title-ico  pt-3"><i class="fas fa-user-lock text-light-grey "></i></div> -->
					<h5 class="text-center font-weight-bold  sub-title">Identifiants</h5>
				</div>
			</div>
			<div class="row yanone light-shadow-round py-3 mb-3">
				<div class="col">
					<img src="../img/logos/docubase-logo.png" class="pr-3">
					<div class="text-orange">Docubase :</div>
					<span class="text-orange pl-3" id="docubase">Login :</span> <?= $mag->getDocubaseLogin()?> <br>
					<span class="text-orange pl-3">Mot de Passe : </span> <?= $mag->getDocubasePwd() ?><br>

				</div>
				<div class="col">
					<img src="../img/logo_bt/bt-rond-20.jpg" class="pr-3">
					<div class="text-orange">Portail :</div>
					<?php if (!empty($webusers)): ?>
						<?php foreach ($webusers as $key => $webuser): ?>
							<span class="text-orange pl-3">Login :</span> <?= $webuser['login']?> <br>
							<span class="text-orange pl-3">Mot de Passe : </span> <?= $webuser['nohash_pwd']?><br>
							<span class="text-orange pl-3">Ident : </span><?= $webuser['id_web_user']?><br>
						<?php endforeach ?>

						<?php else: ?>
							Ce magasin n'a pas de compte sur le portail
						<?php endif ?>

					</div>
				</div>




				<!-- exploit -->
				<div class="bg-separation-small"></div>
				<div class="row">
					<div class="col">
						<h5 class="text-center font-weight-bold  sub-title">Exploitation</h5>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="alert alert-primary">
							Pour reporter une information gessica sur la base magasin, veuillez cliquer sur l'icône <i class="fas fa-sign-out-alt"></i>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="col"><h5 class="text-orange font-weight-bold text-center">Informations base magasins</h5></div>
					</div>
				</div>
				<form action="<?=$_SERVER['PHP_SELF'].'?id='.$mag->getId()?>" method="post">

					<div class="row pb-5">
						<div class="col-6">
							Type d'établissement :
						</div>
						<div class="col-4">

							<select class="form-control" name="id_type" id="id_type">
								<option value="">Sélectionnez</option>
								<?php foreach ($listTypesMag as $type): ?>
									<option value="<?=$type['id']?>" <?= ($mag->getIdtype()==$type['id']) ? " selected" :""?>>
										<?=$type['type']?>
									</option>
								<?php endforeach ?>
							</select>
						</div>
						<div class="col-2">
							<button class="btn btn-primary" name="maj_autre">Modifier</button>
						</div>

					</div>
				</form>

				<div class="row">
					<!-- données gessica -->
					<div class="col">
						<form action="<?=$_SERVER['PHP_SELF'].'?id='.$mag->getId()?>" method="post" name="updatesca">
							<div class="row">
								<div class="col"><h5 class="text-orange font-weight-bold text-center">Informations Gessica</h5></div>
								<div class="col"><h5 class="text-orange font-weight-bold text-center">Information Sca</h5></div>
							</div>
							<!-- galec -->
							<div class="row">
								<div class="col-2">
									Code galec :
								</div>
								<div class="col-4">
									<?=$mag->getGalec()?>
									<a href="fiche-mag-copy.php?id=<?=$_GET['id'].'&field=galec_sca&value=galec'?>" class="btn-ico">
										<i class="fas fa-sign-out-alt"></i>
									</a>
								</div>
								<div class="col">
									<div class="form-group">
										<input type="text" class="form-control" name="galec_sca" id="galec_sca" value="<?=$mag->getGalecSca()?>">
									</div>
								</div>
							</div>
							<!-- deno -->
							<div class="row">
								<div class="col-2">
									Deno :
								</div>
								<div class="col-4">
									<?= $mag->getDeno()?>
									<a href="fiche-mag-copy.php?id=<?=$_GET['id'].'&field=deno_sca&value=deno'?>" class="btn-ico">
										<i class="fas fa-sign-out-alt"></i>
									</a>
								</div>
								<div class="col">
									<div class="form-group">
										<input type="text" class="form-control" name="deno_sca" id="deno_sca" value="<?=$mag->getDenoSca()?>">
									</div>
								</div>
							</div>

							<!-- ad1 -->
							<div class="row">
								<div class="col-2">
									adresse 1 :
								</div>
								<div class="col-4">
									<?= $mag->getAd1()?>
									<a href="fiche-mag-copy.php?id=<?=$_GET['id'].'&field=ad1_sca&value=ad1'?>" class="btn-ico">
										<i class="fas fa-sign-out-alt"></i>
									</a>
								</div>
								<div class="col">
									<div class="form-group">
										<input type="text" class="form-control" name="ad1_sca" id="ad1_sca" value="<?=$mag->getAd1Sca()?>">
									</div>
								</div>
							</div>
							<!-- ad2 -->
							<div class="row">
								<div class="col-2">
									adresse 2 :
								</div>
								<div class="col-4">
									<?= $mag->getAd2()?>
									<a href="fiche-mag-copy.php?id=<?=$_GET['id'].'&field=ad2_sca&value=ad2'?>" class="btn-ico">
										<i class="fas fa-sign-out-alt"></i>
									</a>
								</div>
								<div class="col">
									<div class="form-group">
										<input type="text" class="form-control" name="ad2_sca" id="ad2_sca" value="<?=$mag->getAd2Sca()?>">
									</div>
								</div>
							</div>
							<!-- ad3 -->
							<div class="row">
								<div class="col">adresse 3 : </div>
								<div class="col">
									<div class="form-group">
										<input type="text" class="form-control" name="ad3_sca"  value="<?=$mag->getAd3()?>">
									</div>
								</div>
							</div>
							<!-- cp -->
							<div class="row">
								<div class="col-2">
									CP  :
								</div>
								<div class="col-4">
									<?= $mag->getCp() ?>
									<a href="fiche-mag-copy.php?id=<?=$_GET['id'].'&field=cp_sca&value=cp'?>" class="btn-ico">
										<i class="fas fa-sign-out-alt"></i>
									</a>
								</div>
								<div class="col-3">
									<div class="form-group">
										<input type="text" class="form-control" name="cp_sca" id="cp_sca" value="<?=$mag->getCpSca()?>">
									</div>
								</div>
							</div>
							<!-- ville -->
							<div class="row">
								<div class="col-2">
									Ville :
								</div>
								<div class="col-4">
									<?=$mag->getVille() ?>
									<a href="fiche-mag-copy.php?id=<?=$_GET['id'].'&field=ville_sca&value=ville'?>" class="btn-ico">
										<i class="fas fa-sign-out-alt"></i>
									</a>
								</div>

								<div class="col-3">
									<div class="form-group">
										<input type="text" class="form-control" name="ville_sca" id="ville_sca" value="<?=$mag->getVilleSca()?>">
									</div>

								</div>
							</div>
							<!-- tel -->
							<div class="row">
								<div class="col-2">
									Téléphone :
								</div>
								<div class="col-4">
									<?= $mag->getTel()?>
									<a href="fiche-mag-copy.php?id=<?=$_GET['id'].'&field=tel_sca&value=tel'?>" class="btn-ico">
										<i class="fas fa-sign-out-alt"></i>
									</a>
								</div>
								<div class="col-3">
									<div class="form-group">
										<input type="text" class="form-control" name="tel_sca" id="tel_sca" value="<?=$mag->getTelSca()?>">
									</div>
								</div>

							</div>
							<!-- fax -->
							<div class="row">
								<div class="col-2">
									Fax :
								</div>
								<div class="col-4">
									<?=$mag->getFax()?>
									<a href="fiche-mag-copy.php?id=<?=$_GET['id'].'&field=fax_sca&value=fax'?>" class="btn-ico">
										<i class="fas fa-sign-out-alt"></i>
									</a>
								</div>
								<div class="col-3">
									<div class="form-group">
										<input type="text" class="form-control" name="fax_sca" id="fax_sca" value="<?=$mag->getFaxSca()?>">
									</div>
								</div>
							</div>
							<!-- adhérent -->
							<div class="row">
								<div class="col-2">
									adhérent :
								</div>
								<div class="col-4">
									<?= $mag->getAdherent()?>
									<a href="fiche-mag-copy.php?id=<?=$_GET['id'].'&field=adherent_sca&value=adherent'?>" class="btn-ico">
										<i class="fas fa-sign-out-alt"></i>
									</a>
								</div>
								<div class="col">
									<div class="form-group">
										<input type="text" class="form-control" name="adh_sca" id="adh_sca" value="<?=$mag->getAdherentSca()?>">
									</div>
								</div>
							</div>
							<!-- centrale gessica -->
							<div class="row">
								<div class="col-2">
									Centrale :
								</div>
								<div class="col-4">
									<?= $centraleGessica?>
									<a href="fiche-mag-copy.php?id=<?=$_GET['id'].'&field=centrale_sca&value=centrale'?>" class="btn-ico">
										<i class="fas fa-sign-out-alt"></i>
									</a>
								</div>
								<div class="col">
									<div class="form-group">
										<select class="form-control" name="centrale_sca" id="centrale_sca">
											<option value="">Sélectionnez</option>

											<?php foreach ($listCentralesSca as $centraleSca): ?>
												<option value="<?=$centraleSca['centrale_sca']?>" <?= ($mag->getCentraleSca()==$centraleSca['centrale_sca']) ? " selected" :""?>>
													<?=$centraleSca['centrale']?>
												</option>
											<?php endforeach ?>
										</select>
									</div>


								</div>
							</div>
							<!-- centrale doris -->
							<div class="row">
								<div class="col">Centrale Doris : </div>
								<div class="col">

									<div class="form-group">
										<select class="form-control" name="centrale_doris" id="centrale_doris">
											<option value="">Sélectionnez</option>
											<?php foreach ($listCentralesSca as $centraleSca): ?>
												<option value="<?=$centraleSca['centrale_sca']?>" <?= ($mag->getCentraleDoris()==$centraleSca['centrale_sca']) ? " selected" :""?>>
													<?=$centraleSca['centrale']?>
												</option>
											<?php endforeach ?>
										</select>
									</div>

								</div>
							</div>
							<!-- centrale smiley -->
							<div class="row">
								<div class="col">Centrale Smiley : </div>
								<div class="col">
									<div class="form-group">
										<select class="form-control" name="centrale_smiley" id="centrale_smiley">
											<option value="">Sélectionnez</option>
											<?php foreach ($listCentralesSca as $centraleSca): ?>
												<option value="<?=$centraleSca['centrale_sca']?>" <?= ($mag->getCentraleSmiley()==$centraleSca['centrale_sca']) ? " selected" :""?>>
													<?=$centraleSca['centrale']?>
												</option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
							</div>
							<!-- surface -->
							<div class="row">
								<div class="col-2">
									Surface :
								</div>
								<div class="col-4">
									<?= $mag->getSurface()?>
									<a href="fiche-mag-copy.php?id=<?=$_GET['id'].'&field=surface_sca&value=surface'?>" class="btn-ico">
										<i class="fas fa-sign-out-alt"></i>
									</a>
								</div>
								<div class="col">
									<div class="form-group">
										<input type="text" class="form-control" name="surface_sca" id="surface_sca" value="<?=$mag->getSurfaceSca()?>">
									</div>
								</div>
							</div>
							<!-- etat -->
							<div class="row">
								<div class="col-2">
									Etat :
								</div>
								<div class="col-4">
									<?= $mag->getGelStr()?>

									<a href="fiche-mag-copy.php?id=<?=$_GET['id'].'&field=sorti&value=gel'?>" class="btn-ico">
										<i class="fas fa-sign-out-alt"></i>
									</a>

									<!-- end -->
								</div>
								<div class="col">
									<div class="form-group">
										<select class="form-control" name="sorti" id="sorti">
											<option value="">pas d'état</option>
											<option value="0" <?= ($mag->getSorti()==0) ? " selected" :""?>>en activité</option>
											<option value="1" <?= ($mag->getSorti()==1) ? " selected" :""?>>En cours d'ouverture</option>
											<option value="9" <?= ($mag->getSorti()==9) ? " selected" :""?>>Fermé</option>
											<option value="99" <?= ($mag->getSorti()==99) ? " selected" :""?>>nc</option>
										</select>
									</div>


								</div>
							</div>
							<!-- date ouverture -->
							<div class="row">
								<div class="col-2">
									date ouverture :
								</div>
								<div class="col-4"><br>
									<?= $mag->getDateOuvFr()?>
									<a href="fiche-mag-copy.php?id=<?=$_GET['id'].'&field=date_ouverture&value=date_ouv'?>" class="btn-ico">
										<i class="fas fa-sign-out-alt"></i>
									</a>

								</div>
								<div class="col">
									<div class="form-group">
										<label>Date ouverture</label>
										<input type="text" class="form-control" name="date_ouverture" id="date_ouverture" value="<?=$mag->getDateOuvertureFr()?>">
									</div>
									<div class="form-group">
										<label>Date adhésion</label>
										<input type="text" class="form-control" name="date_adhesion" id="date_adhesion" value="<?=$mag->getDateAdhesionFr()?>">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-2">
									date fermeture :
								</div>
								<div class="col-4"><br>
									<?= $mag->getDateFermFr()?>
									<a href="fiche-mag-copy.php?id=<?=$_GET['id'].'&field=date_fermeture&value=date_ferm'?>" class="btn-ico">
										<i class="fas fa-sign-out-alt"></i>
									</a>

								</div>
								<div class="col">
									<div class="form-group">
										<label>Date fermeture</label>
										<input type="text" class="form-control" name="date_fermeture" id="date_fermeture" value="<?=$mag->getDateFermetureFr()?>">
									</div>
									<div class="form-group">
										<label>Date résiliation</label>
										<input type="text" class="form-control" name="date_resiliation" id="date_resiliation" value="<?=$mag->getDateResiliationFr()?>">
									</div>
									<div class="form-group">
										<label>Date sortie</label>
										<input type="text" class="form-control" name="date_sortie" id="date_sortie" value="<?=$mag->getDateSortieFr()?>">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-2">
								Pôle SAV : </div>
								<div class="col-4">
									<?= $mag->getPoleSavGessica()?>
									<a href="fiche-mag-copy.php?id=<?=$_GET['id'].'&field=pole_sav_sca&value=pole_sav_gessica'?>" class="btn-ico">
										<i class="fas fa-sign-out-alt"></i>
									</a>
								</div>
								<div class="col">
									<div class="form-group">
										<input type="text" class="form-control" name="pole_sav_sca" id="pole_sav_sca" value="<?=$mag->getPoleSavSca()?>">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">Nom Gesap : </div>
								<div class="col">
									<div class="form-group">
										<input type="text" class="form-control" name="gesap" id="gesap" value="<?=$mag->getNomGesap()?>">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">Affilié ou adhérent </div>
								<div class="col">
									<select class="form-control" name="affilie" id="affilie">
										<option value="">na</option>
										<option value="0" <?= ($mag->getAffilie()==0) ? " selected" :""?>>adhérent</option>
										<option value="1" <?= ($mag->getAffilie()==1) ? " selected" :""?>>affilé</option>
									</select>
								</div>
							</div>
							<?php
						// echo "<pre>";
						// print_r($mag);
						// echo '</pre>';

							?>
							<div class="row">
								<div class="col text-right pt-3 pb-5">
									<button class="btn btn-primary" name="maj">Mettre à jour</button>
								</div>
							</div>
						</form>

					</div>
				</div>
			</section>
			<!-- ./container -->

		<?php endif ?>

	</div>
	<script src="../js/autocomplete-searchmag.js"></script>

	<script type="text/javascript">



		function getScroll() {
			var position = $( document ).scrollTop();
			return position;
		}

		function jsScrollTo(hash) {
			location.hash = "#" + hash;
		}

		$(document).ready(function(){
			// masque pour saisie date et etc
			$('#date_ouverture').mask('00/00/0000');
			$('#date_fermeture').mask('00/00/0000');
			$('#date_adhesion').mask('00/00/0000');
			$('#date_resiliation').mask('00/00/0000');
			$('#date_sortie').mask('00/00/0000');
			$('#tel_sca').mask('00 00 00 00 00');

			// gestion du scroll qd met à jour sca3 avec une donnée de la base mag
			$('.btn-ico').on('click',function(){
				var position=getScroll();
							var oldUrl = $(this). attr("href"); // Get current url.
							$(this). attr("href", oldUrl+"&position="+position); // Set herf value.
						});
			window.onload = function () {
				var url = window.location.href;
				url=url.split("#");
				window.scrollTo(0, url[1]);
			}
		});

		document.onkeyup = function(e) {
			if (e.ctrlKey && e.altKey  && e.which == 68) {
				jsScrollTo('docubase');

			}
		};






	</script>

	<?php
	require '../view/_footer-bt.php';
	?>