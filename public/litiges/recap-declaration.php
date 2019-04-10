<?php

 // require('../../config/pdo_connect.php');
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


require_once  '../../vendor/autoload.php';


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getLitige($pdoLitige){
	$req=$pdoLitige->prepare("SELECT dossiers.id as id, dossier, mag, btlec FROM dossiers LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec WHERE dossiers.id= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
$fLitige=getLitige($pdoLitige);

$errors=[];
$success=[];


//------------------------------------------------------
//			ENVOI MAIL
//------------------------------------------------------
// mail mag = 4444-rbt@btlec.fr
if(VERSION =='_' || $_SESSION['code_bt']=='4201' || $_SESSION['type']=='btlec')
{
	$mailMag=array('valerie.montusclat@btlec.fr');
	$mailBt=array('valerie.montusclat@btlec.fr');
}
else
{
	$mailMag=array($_SESSION['code_bt'].'-rbt@btlec.fr');
	$mailBt=array('litigelivraison@btlec.fr');
}






// ---------------------------------------
// gestion du template mag
$magTemplate = file_get_contents('mail-mag-new-litige.php');
$magTemplate=str_replace('{DOSSIER}',$fLitige['dossier'],$magTemplate);
$subject='Portail BTLec Est  - ouverture du dossier litige ' . $fLitige['dossier'];
// ---------------------------------------
$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);
$message = (new Swift_Message($subject))
->setBody($magTemplate, 'text/html')
->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
->setTo($mailMag)
->addBcc('valerie.montusclat@btlec.fr');
$delivered=$mailer->send($message);
if($delivered >0)
{
}
else
{
	$errors='Nous n\'avons pas pu vous faire parvenir le mail accusant réception de votre dossier';
}


// ---------------------------------------
// gestion du template mag
$btTemplate = file_get_contents('mail-bt-new-litige.php');
$btTemplate=str_replace('{DOSSIER}',$fLitige['dossier'],$btTemplate);
$btTemplate=str_replace('{MAG}',$fLitige['mag'],$btTemplate);
$btTemplate=str_replace('{BTLEC}',$fLitige['btlec'],$btTemplate);
$subject='Portail BTLec Est  - nouveau dossier litige ' . $fLitige['dossier'];
// ---------------------------------------
$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);
$message = (new Swift_Message($subject))
->setBody($btTemplate, 'text/html')
->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
->setTo($mailBt)
->addBcc('valerie.montusclat@btlec.fr');
$delivered=$mailer->send($message);
if($delivered >0)
{
}
else
{
	$errors='Le mail n\'a pas pu être envoyé à notre service livraison';
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
	<h1 class="text-main-blue py-5 ">Ouverture du dossier de litige n° <?=$fLitige['dossier']?></h1>
	<!-- start row -->
	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col">
			<div class="alert alert-success">
				Votre dossier litige <strong>n° <?= $fLitige['dossier'] ?> </strong>a été transmis à BTLec pour étude. <br> Vous pourrez consulter l'avancement de votre dossier sur le portail sous cette référence.
			</div>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<!-- ./row -->
<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>
	<div class="col">
		<?php
			include('../view/_errors.php');
		 ?>
	</div>
		<div class="col-lg-1 col-xxl-2"></div>

</div>
</div>
<?php
require '../view/_footer-bt.php';
?>