<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
require '../../config/db-connect.php';

require_once '../../vendor/autoload.php';

require ('../../Class/Helpers.php');
require ('../../Class/mag/MagHelpers.php');

require 'casse-getters.fn.php';

require('../../Class/casse/TrtDao.php');
$trtDao=new TrtDao($pdoCasse);



unset($_SESSION['goto']);

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// $descr="saisie déclaration mag hors qlik" ;
// $page=basename(__file__);
// $action="";
// // addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
// addRecord($pdoStat,$page,$action, $descr, 208);

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function updatePalette($pdoCasse,$cmt, $id){
	$req=$pdoCasse->prepare("UPDATE palettes SET id_pilote= :id_pilote, date_retour_pilote= :date_retour, cmt_pilote= :cmt WHERE id= :id");
	$req->execute([
		':id_pilote' =>$_SESSION['id_web_user'],
		':date_retour'	=>date('Y-m-d H:i:s'),
		':cmt'	=>$cmt,
		':id'	=>$id,

	]);
	return $req->rowCount();
}


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

if(isset($_GET['id'])){
	$numExp=$_GET['id'];
	// on récupère les palettes pour afficher un champ de commentaire en face de chaque
	$listPalette=getExpAndPalette($pdoCasse,$numExp);

}

if(isset($_POST['submit'])){

	$nbPalette=count($_POST['idpalette']);
	$majko=false;
	for ($i=0; $i < $nbPalette; $i++) {
		if($_POST['cmt'][$i] !=''){
			$cmt=strip_tags($_POST['cmt'][$i]);
			$cmt= nl2br($cmt);
			$addcmt=updatePalette($pdoCasse,$cmt, $_POST['idpalette'][$i]);

			if($addcmt!=1){
				$majko=true;
			}
		}
		else{
			$cmt= '';
			$addcmt=updatePalette($pdoCasse,$cmt, $_POST['idpalette'][$i]);
			if($addcmt!=1){
				$majko=true;
			}
		}
	}
	if(!$majko){
		// pas d'erreur on envoi le mail
		if(VERSION=='_'){
			$dest=MYMAIL;
			$cc=[MYMAIL];
		}
		else{
			$dest='ga-btlecest-portailweb-logistique@btlecest.leclerc';
			$cc=[MYMAIL];
		}
		$table='';
		$table.='<table style="border-collapse: collapse; border: 1px solid grey;padding:10px;"><tr style="background-color:firebrick;color:white;"><th style="border: 1px solid grey;padding:10px;">Palette 4919</th><th style="border: 1px solid grey;padding:10px;">Palette contremarque</th><th style="border: 1px solid grey;padding:10px;">Commentaires</th></tr>';
		foreach ($listPalette as $exp) {
			$table.='<tr><td style="border: 1px solid grey;padding:10px;">'.$exp['palette'].'</td><td style="border: 1px solid grey;padding:10px;">'.$exp['contremarque'].'</td><td style="border: 1px solid grey;padding:10px;">'.$exp['cmt_pilote'].'</td></tr>';
		}
		$table.='</table>';
		$deno=MagHelpers::deno($pdoMag,$listPalette[0]['galec']);
		$htmlMail = file_get_contents('mail/mail-pilote-retour.php');
		$htmlMail=str_replace('{MAG}',$listPalette[0]['btlec'],$htmlMail);
		$htmlMail=str_replace('{DENO}',$deno,$htmlMail);
		$htmlMail=str_replace('{IDEXP}',$_GET['id'],$htmlMail);
		$htmlMail=str_replace('{TABLE}',$table,$htmlMail);
		$subject='Portail BTLec Est - Casses : retour de contrôle palettes';

// ---------------------------------------
// initialisation de swift
		$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
		$mailer = new Swift_Mailer($transport);
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')

		->setFrom(EMAIL_NEPASREPONDRE)
		->setTo($dest)
		->setCc($cc);

		$delivered=$mailer->send($message);
		if($delivered !=0){
			$trtDao->insertTrtHisto($_GET['id'], $_GET['id_trt']);
			header('Location:casse-dashboard.php?#exp-'.$_GET['id']);
		}


	}else{
		$errors[]="impossible de mettre à jour la base palette";

	}

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
				<?= Helpers::returnBtn('casse-dashboard.php'); ?>
			</div>
		</div>
		<h1 class="text-main-blue pb-5 ">Retour contrôle palettes de casse</h1>

		<div class="row">
			<div class="col-lg-1"></div>
			<div class="col">
				<?php
				include('../view/_errors.php');
				?>
			</div>
			<div class="col-lg-1"></div>
		</div>
		<div class="row mb-5">
			<div class="col">
				<p class="alert alert-primary"><i class="fas fa-info-circle pr-3"></i>Pour chacune des palettes contrôlées ci dessous, veuillez saisir <b>un commentaire si besoin</b> et valider votre saisie en cliquant sur le <b>bouton "envoyer"</b>. Un mail sera envoyé aux personnes concernées pour les avertir que le contrôle et la mise en RAQ a été faite</p>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'].'&id_trt='.$_GET['id_trt']?>" method="post" class="pb-5">
					<?php foreach ($listPalette as $palette): ?>
						<div class="row">
							<div class="col-3">
								<div class="form-group">
									<label for="palette">Palette 4919 :</label>
									<input type="text" name="palette" id="palette" class="form-control" value="<?=$palette['palette']?>" readonly>
								</div>
							</div>
							<div class="col-3">
								<div class="form-group">
									<label for="contremarque">Palette contremarque : </label>
									<input type="text" name="contremarque" id="contremarque" class="form-control" value="<?=$palette['palette']?>" readonly>
								</div>

							</div>
							<div class="col">
								<div class="form-group">
									<label for="cmt">Commentaire : </label>
									<input type="text" name="cmt[]" id="cmt" class="form-control">
								</div>
							</div>
							<input type="hidden" name="idpalette[]" value="<?=$palette['paletteid']?>">

						</div>
					<?php endforeach ?>
					<div class="text-right">
						<button class="btn btn-black" name="submit"><i class="far fa-envelope pr-3"></i> Envoyer</button>
					</div>
				</form>


			</div>
		</div>
		<!-- ./container -->
	</div>

	<?php
	require '../view/_footer-bt.php';
?>