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

unset($_SESSION['goto']);

//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
require_once '../../vendor/autoload.php';
require_once '../../Class/UserHelpers.php';
require_once '../../Class/MagHelpers.php';
require_once '../../Class/MagDao.php';
require_once '../../Class/CmRdvDao.php';
require_once '../../Class/Helpers.php';


$rdvDao=new CmRdvDao($pdoCm);
$magDao=new MagDao($pdoMag);
$pendingRdv=$rdvDao->getLastPendingRdv($pdoCm);

$magInfo=MagHelpers::magInfo($pdoMag, $_SESSION['id_galec']);
$magLdAdh=$magDao->getMagLdEmails($magInfo['id'],'-ADH');
$magLdDir=$magDao->getMagLdEmails($magInfo['id'],'-DIR');

$destAdhDir=array_merge($magLdAdh,$magLdDir);


$deno=MagHelpers::deno($pdoMag, $_SESSION['id_galec']);
$city=MagHelpers::ville($pdoMag, $_SESSION['id_galec']);

$cm=UserHelpers::getInternUser($pdoUser, $magInfo['id_cm_web_user']);



$jours=['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
$moisFr = ['','Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
if($pendingRdv){
	$fullDate=new DateTime($pendingRdv['date_start']);

	$jour=$jours[$fullDate->format('w')];
	$mois=$moisFr[$fullDate->format('n')];
	$dateFr=$jour .' '.$fullDate->format('d').' '.$mois .' '.$fullDate->format('Y');
}

function getPreviousRdv($pdoCm){
	$req=$pdoCm->prepare("SELECT * FROM rdv WHERE galec= :galec AND accepted=1 ORDER BY date_start desc ");
	$req->execute([
		':galec'	=>$_SESSION['id_galec']
	]);
	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	if(!empty($data)){
		return $data;
	}
	return false;
}


function addReply($pdoCm,$accepted){
	if(isset($_POST['cmt'])){
		$cmt=Helpers::sanitize($_POST['cmt']);
	}
	else{
		$cmt="";
	}

	$req=$pdoCm->prepare("UPDATE rdv SET accepted=:accepted, date_accepted=:date_accepted, by_accepted= :by_accepted, cmt_accepted= :cmt_accepted WHERE id= :id");
	$req->execute([
		':accepted'				=>$accepted,
		':date_accepted'		=>date('Y-m-d H:i:s'),
		':by_accepted'			=>$_SESSION['id_web_user'],
		':cmt_accepted'			=>$cmt,
		':id'					=>$_GET['rdv']
	]);
	return $req->rowCount();

}

// recup le prochain rendezvous accepté
function getFutureAcceptedRdv($pdoCm,$galec){
	$req=$pdoCm->prepare("SELECT * FROM rdv WHERE galec= :galec AND accepted=1 AND date_start >= NOW()");
	$req->execute([
		':galec'		=>$galec
	]);
	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($data)){
		return false;
	}
	return $data;
}
$futureRdv=getFutureAcceptedRdv($pdoCm, $magInfo['galec']);


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
$histoRdv=getPreviousRdv($pdoCm);


if(isset($_POST['accept']) || isset($_POST['deny'])){
	if(isset($_POST['accept'])){
		$accepted=1;
		$acceptedStr="<span style='color:darkorange;'> accepté </span>";
	}

	if(isset($_POST['deny'])){
		$accepted=2;
		$acceptedStr="<span style='color:darkorange;'>  refusé </span>";

	}
	$updateChoice=addReply($pdoCm,$accepted);
	if($updateChoice==1){

		$dateMailFormat= $dateFr .' à '.$fullDate->format('H') .':'.$fullDate->format('i');
		if(isset($_POST['cmt'])){
			$cmt="Commentaire du magasin : <br>";
			$cmt.=Helpers::sanitize($_POST['cmt']);
		}
		else{
			$cmt="";
		}

		if(VERSION=='_'){
			$dest=['valerie.montusclat@btlec.fr'];
			$bcc=[];
		}else{


			$dest=['luc.muller@btlec.fr', 'stephane.wendling@btlec.fr', $cm['email']];
			$bcc=['valerie.montusclat@btlec.fr'];
		}


		$htmlMail = file_get_contents('mail-rep-rdv.html');
		$htmlMail=str_replace('{MAG}',$deno,$htmlMail);
		$htmlMail=str_replace('{CITY}',$city,$htmlMail);
		$htmlMail=str_replace('{REP}',$acceptedStr,$htmlMail);
		$htmlMail=str_replace('{RDV}',$dateMailFormat,$htmlMail);
		$htmlMail=str_replace('{CMT}',$cmt,$htmlMail);
		$htmlMail=str_replace('{CM}',$cm['fullname'],$htmlMail);
		$subject='Portail BTLec - Rendez-vous Chargé de mission BTLec EST - Réponse du magasin '.$deno;

// ---------------------------------------
// initialisation de swift
		$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
		$mailer = new Swift_Mailer($transport);
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(array('ne_pas_repondre@btlec.fr' => 'PORTAIL BTLEC'))
		->setTo($dest)
		->setBcc($bcc);



		if (!$mailer->send($message, $failures)){
			print_r($failures);
			$errors[]="erreur à l'envoi du mail";
		}
		if(empty($errors)){
			if(isset($_POST['accept'])){
				if(VERSION =="_"){
					$destAdhDir=['valerie.montusclat@btlec.fr'];
				}
				$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
				$mailer = new Swift_Mailer($transport);

				$htmlMail = file_get_contents('mail-confirm-rdv-adhdir.html');
				$htmlMail=str_replace('{MAG}',$deno,$htmlMail);
				$htmlMail=str_replace('{RDV}',$dateMailFormat,$htmlMail);
				$htmlMail=str_replace('{CM}',$cm['fullname'],$htmlMail);
				$subject='Portail BTLec - Rendez-vous Chargé de mission BTLec EST';



				$message = (new Swift_Message($subject))
				->setBody($htmlMail, 'text/html')
				->setFrom(array('ne_pas_repondre@btlec.fr' => 'PORTAIL BTLEC'))
				->setTo($destAdhDir)
				->setBcc(['valerie.montusclat@btlec.fr']);

			}
			if (!$mailer->send($message, $failures)){
				print_r($failures);
				$errors[]="erreur à l'envoi du mail";
			}
		}
		if(empty($errors)){
			$successQ='?success='.$accepted;
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
		}



	}
	else{
		$errors[]="impossible de mettre à jour la base de donnée";
	}

}

if(isset($_GET['success'])){
	$arrSuccess=[
		1=>'Confirmation du rendez-vous prise en compte. Un mail a été envoyé à votre chargé de mission pour l\'avertir',
		2=>'votre refus a été pris en compte. Un mail a été envoyé à votre chargé de mission pour l\'avertir',
	];
	$success[]=$arrSuccess[$_GET['success']];
}


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
	<h1 class="text-main-blue pt-5 ">Les visites de votre chargé de mission</h1>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row ">
		<div class="col-lg-1"></div>

		<?php if (!empty($futureRdv)): ?>
			<div class="col-lg-3 light-shadow mb-5 py-3">
				<div class="contact-card">
					<i class="fas fa-calendar-check  pr-3 text-orange"></i>Prochain rendez-vous :<br>
					<?php foreach ($futureRdv as $rdv): ?>
						<div class="text-center font-weight-bold pt-3">	<?=
						$jours[(new DateTime($rdv['date_start']))->format('w')] .' '.
						(new DateTime($rdv['date_start']))->format('d') .' '.
						$moisFr[(new DateTime($rdv['date_start']))->format('n')].' '.
						(new DateTime($rdv['date_start']))->format('Y')?> à <?=(new DateTime($rdv['date_start']))->format('H:i')?></div>
					<?php endforeach ?>
				</div>
			</div>


		<?php endif ?>
		<div class="col"></div>
		<div class="col-lg-3 light-shadow mb-5 py-3">
			<img src="../img/logo_bt/bt-rond-30.jpg" class="float-right">
			<div class="name">M. <?=$cm['fullname']?></div>
			<div class="contact-card"><i class="fas fa-mobile-alt pr-3 text-orange"></i> <?=$cm['mobile'] ?> <br>
				<i class="fas fa-envelope pr-3 text-orange"></i><?=$cm['email']?>
			</div>
		</div>
		<div class="col-lg-1"></div>

	</div>
	<?php if ($pendingRdv): ?>

		<div class="list-article full-bg-blue pt-5">
			<div class="row justify-content-center">
				<div class="col-xs-12 col-lg-10 article-wrapper">
					<article>
						<a href="?rdv=<?=$pendingRdv['id']?>" class="more">Accepter ou refuser le rendez-vous</a>
						<div class="img-wrapper">
							<!-- <img src="../img/cm/temp.png"> -->
							<img src="../img/cm/notif-or-shadow-red2.jpg">

						</div>
						<h5 class="text-center">Vous avez une invitation en attente </h5>
						<p class="smaller">Merci d'accepter ou refuser ce rendez-vous en cliquant ci-dessous :</p>
						<p class=" rdv text-center"><?=$dateFr?>
						<br>
						<?=$fullDate->format('H')?>h<?=$fullDate->format('i')?>
					</p>
				</article>
			</div>
		</div>
	</div>
<?php endif ?>

<?php if (isset($_GET['rdv']) && $pendingRdv): ?>
	<div class="full-border-blue">
		<div class="row my-3">
			<div class="col-lg-1"></div>
			<div class="col">
				<h5 class="text-main-blue">Valider ou refuser le rendez-vous</h5>
				<form action="<?=$_SERVER['PHP_SELF'].'?rdv='.$_GET['rdv']?>" id="validation" method="post">
					<div class="row">
						<div class="col-sm-12 col-lg-8">
							<div class="form-group">
								<label>Commentaire : </label>
								<textarea name="cmt" class="form-control"></textarea>
							</div>
						</div>
						<div class="col-sm-12 col-lg-4 mb-3 align-self-end">
							<button class="btn btn-primary submit" name="accept">Accepter</button>
							<button class="btn btn-red submit" name="deny">Décliner</button>
						</div>
					</div>
				</form>
			</div>
			<div class="col-lg-1"></div>
		</div>
	</div>
<?php endif ?>

<!--
<div class="row mt-5">
	<div class="col-lg-1"></div>
	<div class="col">
		<h5 class="text-main-blue">Historique des visites et rapports d'activité</h5>
		Historique de vos rendez-vous
		//les rapoorts d'activité
	</div>
	<div class="col-lg-1"></div>
</div> -->



<!-- ./container -->
</div>
<script type="text/javascript">
	$(function() {
		var buttonpressed;
		$('.submit').click(function() {
			buttonpressed = $(this).attr('name')
		})



		$('#validation').submit(function()
		{

			if(buttonpressed=="accept"){
				boxState="Confirmez-vous que vous acceptez le rendez-vous ?";
			}
			else if(buttonpressed=="deny"){
				boxState="Confirmez-vous que vous déclinez le rendez-vous ?";
			}
			return confirm(boxState);
		});

	});
</script>
<?php
require '../view/_footer-bt.php';
?>