<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

require_once '../../config/constantes-salon.php';
include '../../vendor/phpqrcode/qrlib.php';

require '../../Class/Db.php';
// require_once '../../vendor/autoload.php';
require_once '../../vendor/autoload.php';
require_once '../../Class/mag/MagHelpers.php';
require_once '../../Class/salon/FormationDAO.php';
require_once '../../Class/FormHelpers.php';
require_once '../../Class/salon/SalonDao.php';
require_once '../../Class/UserHelpers.php';




$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoBt=$db->getPdo('btlec');
$pdoMag=$db->getPdo('magasin');

$salonDao=new SalonDao($pdoBt);




// ------------------------------------->

//------------------------------------------------------
//			FONCTIONS
//------------------------------------------------------




$fonctionList=$salonDao->getFunction();



//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$YesNo=array('_','oui');
if(isset($_SESSION['id_galec'])){
	$participantList=$salonDao->getParticipant();
}

$formationDOA=new FormationDAO($pdoBt);
$listCreneau=$formationDOA->getCreneaux($pdoBt);

//------------------------------------------------------
//			TRAITEMeNT
//------------------------------------------------------
if(isset($_POST['add-participant'])){

	if(isset($_POST['email'])){
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			$errors[]="Merci de saisir une adresse mail valide";
		}
	}else{
		$errors[]="Merci de saisir une adresse mail";
	}
	// au moins une des case doit être cochée
	if(empty($_POST['mardi']) &&  empty($_POST['mercredi'])){
		$errors[]="Merci de sélectionner au moins un jour de présence";
	}
	if(isset($_POST['mardi']) && !isset($_POST['repas-mardi'])){
		$errors[]="Veuillez préciser si vous souhaitez prendre votre repas à BTlec ou non le mardi";
	}
	if(!isset($_POST['mardi'])){
		$_POST['mardi']=0;
		$_POST['repas-mardi']=0;
	}
	if(isset($_POST['mercredi']) && !isset($_POST['repas-mercredi'])){
		$errors[]="Veuillez préciser si vous souhaitez prendre votre repas à BTlec ou non le mercredi";
	}
	if(!isset($_POST['mercredi'])){
		$_POST['mercredi']=0;
		$_POST['repas-mercredi']=0;
	}

	if(count($errors)==0){
		$idPart=$salonDao->addParticipant();
		// génération qrcode
		$qrcodeData=10000+$idPart;
		$qrcodeImg=md5($qrcodeData).'.png';
		$qrcodeFile=DIR_UPLOAD.'qrcodes\\'.$qrcodeImg;
		QRcode::png($qrcodeData, $qrcodeFile);
		$salonDao->updateParticipantQrcode($idPart, $qrcodeImg);
		// on vérifie si code appli salon déja crées sinon, on les créé
		// le login étant de la forme galec@cp, on récup le cp du mag
		$cp=UserHelpers::getMagInfoByIdWebUser($pdoUser, $pdoMag, $_SESSION['id_web_user'] );
		$salonDao->addlogin($cp['galec_sca'], $cp['cp_sca']);

		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success-inscr=ok#inscription-lk';
		header($loc);		
	}
}



if(isset($_POST['more-info'])){
	if(isset($_POST['msg'])){
		$formMsg=htmlspecialchars(stripslashes($_POST['msg']));
	}else{
		$errors[]="Merci de saisir votre demande";
	}
	if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
		$errors[]="adresse mail non valide";
	}

	//si pas d'erreur
	if(count($errors)==0){
		$dest=[EMAIL_SALON];
		if(VERSION=="_"){
			$dest=[MYMAIL];

		}


		$infosMag="<p><strong>Demande du magasin : " . $_SESSION['nom'] ." - " .$_SESSION['id_galec'] .' email :'.$_POST['email'].  "</strong></p>";

		$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
		$mailer = new Swift_Mailer($transport);

		$htmlMail = file_get_contents('inscription/mail-demande-renseignement.html');
		$htmlMail=str_replace('{MSG}',htmlspecialchars(stripslashes($_POST['msg'])),$htmlMail);
		$htmlMail=str_replace('{INFO}',$infosMag,$htmlMail);
		$subject="Portail BTLec - Salon ".YEAR_SALON. " - demande de renseignements - " .$_SESSION['nom'];
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(EMAIL_NEPASREPONDRE)
		->setTo($dest);

		if (!$mailer->send($message, $failures)){
			print_r($failures);
		}else{
			$success[]="mail envoyé avec succés";
		}

	}
}


if( isset($_POST['send'])){
	$mpdf = new \Mpdf\Mpdf();
	foreach ($participantList as $invit){
		ob_start();
		include('inscription\template-badge-mag-multiple.php');
		$html=ob_get_contents();
		ob_end_clean();

		$mpdf->AddPage();
		$mpdf->WriteHTML($html);

	}

	$pdfContent = $mpdf->Output('', 'S');


	$pdfname='salon BTLec Est '.YEAR_SALON.' - badges.pdf';

	$htmlMail = file_get_contents('inscription/mail_invitation.php');
	$htmlMail=str_replace('{YEAR}',YEAR_SALON,$htmlMail);
	$subject='Portail BTLec EST - Salon '.YEAR_SALON.' - Vos badges';

	$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
	$mailer = new Swift_Mailer($transport);
	$attachmentPdf = new Swift_Attachment($pdfContent, $pdfname, 'application/pdf');

	$message = (new Swift_Message($subject))
	->setFrom(EMAIL_NEPASREPONDRE)
	->setTo(array($_POST['email']))
	->setBody($htmlMail, 'text/html')
	->attach($attachmentPdf);


	$delivered=$mailer->send($message);
	if($delivered !=0){
		$success[]='Vos badges ont été envoyées';
	}else{
		$errors[]='impossible d\'envoyer les invitations';
	}
}


if(isset($_POST['add-formation-google'])){
	if(empty($_POST['email'])){
		$errors[]="Merci de renseigner une adresse email";
	}
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$errors[]="L'adresse mail ". $_POST['email']. " n'est pas valide";
	}

	if(empty($errors)){
		$updated=$formationDOA->addFormation($pdoBt, 1);
		if($updated==1){
			$successQ='?success=2';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
		}

	}
}

if(isset($_GET['print-one'])){


	$thisInvit=$salonDao->getOneBadge($_GET['print-one']);


	ob_start();
	include('inscription/template-badge-mag-simple.php');
	$html=ob_get_contents();
	ob_end_clean();
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

	$mpdf->WriteHTML($html);
	$pdfContent = $mpdf->Output();
}

if(isset($_GET['success-inscr'])){
	$success[]="Votre inscription a bien été prise en compte";
}
if(isset($_GET['success'])){
	$arrSuccess=[
		2=>'Choix de formation ajouté',
	];
	$success[]=$arrSuccess[$_GET['success']];
}

//----------------------------------------------------
// VIEW - HEADER
//----------------------------------------------------
require '../view/_head-bt.php';
require '../view/_navbar.php';

?>
<div class="container" id="up">
	<!-- main title -->
	<div class="row pt-5">
		<div class="col">
			<h1 class="text-main-blue">SALON TECHNIQUE BTLEC Est <?=YEAR_SALON?><br>
				<span class="sub-h1"><i class="far fa-calendar-alt pr-3" aria-hidden="true"></i> du <?=JOURUN_DATE?> au <?=JOURDEUX_DATE?></span>
			</h1>

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
	<?php include 'inscription/01-bandeau.php' ?>

	<div class="bg-separation"></div>
	<?php include 'inscription/02-salon.php' ?>
	<div class="bg-separation"></div>

	<?php include 'inscription/02-salon-presentation.php' ?>
	<div class="bg-separation"></div>
	<?php include 'inscription/03-badges.php' ?>
	<div class="bg-separation"></div>
	<div class="row">
		<div class="col">
			<div class="row mt-5">
				<div class="col">
					<h4 class="text-main-blue"><i class="fas fa-hand-point-right pr-3" id="inscription-lk"></i>FORMULAIRE D'INSCRIPTION</h4>
				</div>
			</div>
			<?php if($_SESSION['type']=='mag'  || $_SESSION['user']=="user" ||  $_SESSION['type']=='centrale' ): ?>
				<?php include('inscription/04-form-inscription.php');?>
			<?php else: ?>
				<div class='alert alert-danger'>L'inscription est réservée aux magasins</div>
			<?php endif ?>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col">
			<p class="text-right"><a href="#up" class="blue-link">retour</a></p>
		</div>
	</div>
	<div class="bg-separation"></div>
	<?php
	//include 'inscription/05-formation-google.php';
	?>

	<!-- fin zone inscription -->
	<div class="bg-separation"></div>

	<!-- modalités -->
	<div class="row pt-3">
		<div class="col">
			<h4 class="text-main-blue" id="modalite-lk"><i class="fas fa-hand-point-right pr-3"></i>MODALITES D'ACCUEIL ET ACCES</h4>


			<ul class="browser-default">
				<li>restauration : un petit déjeuner vous sera servi sur le salon et un buffet traiteur vous accueillera mardi et mercredi</li>
				<li>Sociétés de taxi :
					<ul class="browser-default">
						<li><strong><a href="http://www.taxi-city-reims.com" target="_blank">taxi city</a></strong> - 06 64 90 93 43</li>
						<li><strong><a href="#" target="_blank">taxis du vignoble</a></strong> - 06 06 60 60 20</li>
						<li><strong><a href="http://www.aid-taxi.com" target="_blank">AID Taxis</a></strong> - 06 16 17 68 70 ou 03 26 85 80 73</li>
						<li><strong><a href="#" target="_blank">Taxi LCDLM</a></strong> - 06 08 47 00 27</li>
					</ul>
				</li>
				<li>venir à BTlec : <a href="../mag/google-map.php" class="blue-link">coordonnées gps, carte</a></li>
			</ul>
			<!-- <br> -->
			<!-- <p><i class="fa fa-envelope-o" aria-hidden="true"></i>Nous contacter  pour tout renseignement complémentaire</a></p> -->

			<p class="text-right"><a href="#up" class="blue-link">retour</a></p>
		</div>
	</div>
	<div class="bg-separation"></div>
	<div class="row pt-3">
		<div class="col">
			<h4 class="text-main-blue"><i class="fas fa-hand-point-right pr-3" id="inscription-lk"></i>DEMANDE DE RENSEIGNEMENT COMPLEMENTAIRES</h4>
		</div>
	</div>
	<div class="row">
		<div class="col p-4 alert alert-primary">
			<form method="post" id="add" action="<?=$_SERVER['PHP_SELF']?>">
				<div class="row">
					<div class="col ">
						<div class="form-group">
							<label>Votre demande :</label>
							<textarea name="msg" class="form-control" required="require"></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-auto">
						<div class="form-group">
							<label>Votre email :</label>
							<input type="email" name="email" required="require" class="form-control" >
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col text-right">
						<button class="btn btn-primary" type="submit" name="more-info">Envoyer</button>
					</div>
				</div>

			</form>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col">
			<p class="text-right"><a href="#up" class="blue-link">retour</a></p>
		</div>
	</div>
	<div class="bg-separation"></div>
	<div class="row pt-3">
		<div class="col">
			<h4 class="text-main-blue"><i class="fas fa-hand-point-right pr-3" id="sanitaire-lk"></i>Informations QHSE-É</h4>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="iframe"><iframe src="pdf/information-qhse.pdf?#view=FitH" height="100%" width="100%"></iframe></div>
		</div>
	</div>





</div>   <!--fin container -->

<script type="text/javascript">
	$(document).ready(function(){

		$(":checkbox#mardi").change(function(){
			if($(this).prop("checked")) {
				// $('#toogle_transit').show();
				$('#repas-mardi').attr('class','show');
			} else {
				$('#repas-mardi').attr('class', 'hidden');
			}
		});
		$(":checkbox#mercredi").change(function(){
			if($(this).prop("checked")) {
				// $('#toogle_transit').show();
				$('#repas-mercredi').attr('class','show');
			} else {
				$('#repas-mercredi').attr('class', 'hidden');
			}
		});

		$('#show-google').on("click",function(){
			$('#google').attr('class','show');
		});
		$("#email").keyup(function(){

			var email = $("#email").val();
			var filter = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (!filter.test(email)) {
             //alert('Please provide a valid email address');
             $("#error_email").text(email+" n'est pas une adresse mail valide");
             $("#error_email").addClass('alert alert-danger');
             email.focus;
         } else {
         	$("#error_email").text("");
         	$("#error_email").removeClass('alert alert-danger');
         	$("#error_email").text(email+' : adresse valide');
         	$("#error_email").addClass('alert alert-success');
         }
     });

	});


</script>


<?php
//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
require '../view/_footer-bt.php';

?>