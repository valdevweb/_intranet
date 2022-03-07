<?php
require_once '../../config/session.php';
require_once '../../vendor/autoload.php';
require_once '../../Class/litiges/LitigeDao.php';
require_once '../../Class/litiges/ActionDao.php';
require_once '../../Class/litiges/DialDao.php';
require_once '../../Class/UserHelpers.php';
require_once '../../Class/mag/MagHelpers.php';





//			css dynamique
//----------------------------------------------------------------
$pageCss = explode(".php", basename(__file__));
$pageCss = $pageCss[0];
$cssFile = ROOT_PATH . "/public/css/" . $pageCss . ".css";



$errors = [];
$success = [];
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
$pdoSav = $db->getPdo('sav');
$pdoLitige = $db->getPdo('litige');
$pdoQlik = $db->getPdo('qlik');
$pdoSav = $db->getPdo('sav');
$pdoMag = $db->getPdo('magasin');

function getMagSav($pdoSav, $galec)
{
	$req = $pdoSav->prepare("SELECT sav FROM mag WHERE galec = :galec");
	$req->execute([
		':galec'		=> $galec
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getLdSav($pdoSav, $sav, $module)
{
	$req = $pdoSav->prepare("SELECT email FROM mail_sav LEFT JOIN sav_users ON mail_sav.id_user_sav=sav_users.id WHERE mail_sav.sav= :sav AND module= :module");
	$req->execute([
		':sav'			=> $sav,
		':module'		=> $module
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getLdAchat($pdoUser, $serviceId)
{
	$req = $pdoUser->prepare("SELECT email FROM intern_users WHERE id_service= :service");
	$req->execute([
		':service'			=> $serviceId
	]);

	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function updateCtrl($pdoLitige, $etat)
{
	$req = $pdoLitige->prepare("UPDATE dossiers SET ctrl_ok=:ctrl_ok WHERE id=:id");
	$req->execute(array(
		':ctrl_ok'	=> $etat,
		':id'		=> $_GET['id']
	));
	return $req->rowCount();
}
function getDialog($pdoLitige)
{
	$req = $pdoLitige->prepare("SELECT dial.*, id_dossier, DATE_FORMAT(date_saisie, '%d-%m-%Y') as dateFr, DATE_FORMAT(date_saisie, '%H:%i') as heure FROM dial WHERE id_dossier= :id AND mag!=3 ORDER BY date_saisie DESC");
	$req->execute(array(
		':id'		=> $_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$litigeDao = new LitigeDao($pdoLitige);
$actionDao = new ActionDao($pdoLitige);
$dialDao = new DialDao($pdoLitige);


$actionList = $actionDao->findActionsLitige($_GET['id']);
$infos = $litigeDao->getInfos($_GET['id']);
$analyse = $litigeDao->getAnalyse($_GET['id']);
$litige = $litigeDao->getLitigeDossierDetailReclamMagEtatById($_GET['id']);
$dials = getDialog($pdoLitige);

$actionLitige = $actionDao->findActionLitige($_GET['action']);

$listCentrales = MagHelpers::getListCentrale($pdoMag);





$initialCmt = $dialDao->getInitialCmt($_GET['id']);

$subjet = 'Portail BTLec - Litiges - {SPECIFIQUE_SUJET} : ' . $litige[0]['dossier'] . ' - ' . $litige[0]['mag'];
$link = '<a href="' . SITE_ADDRESS . '/index.php?litiges/intervention.php?id=' . $litige[0]['id_main'] . '&id_contrainte={ID_CONTRAINTE}"> cliquant ici</a>';

if ($_GET['contrainte'] == 2) {
	// demande de vérif de stock
	// maj etat ctrl => 2 pour controle à faire
	$litigeDao->updateCtrl($litige[0]['id_main'], 2);
	$dest = [EMAIL_PILOTAGE_PREPA];
	$cc = [EMAIL_LITIGES];
	ob_start();
	include('pdf/pdf-contrainte-commun.php');
	include('pdf/partials/detail-more.php');
	include('pdf/partials/ctrl-stock.php');
	$html = ob_get_contents();
	ob_end_clean();
	include 'contrainte/build-pdf.php';
	$subjet = str_replace('{SPECIFIQUE_SUJET}', 'Contrôle de stock', $subjet);
	$link = '<a href="' . SITE_ADDRESS . '/index.php?litiges/ctrl-stock.php?id=' . $litige[0]['id_main'] . '"> cliquant ici</a>';
	$mailFile = 'mail/mail-dde-ctrl-stock.php';
	include 'contrainte/send-mail.php';
} elseif ($_GET['contrainte'] == 1) {
	// retour controle de stock juste maj db
	$row = updateCtrl($pdoLitige, 1);
	header('Location:bt-action-add.php?id=' . $_GET['id'] . '&success=ok');

} elseif ($_GET['contrainte'] == 4) {
	// demande d'intervention du pole sav
	$galec = $litige[0]['galec'];
	$sav = getMagSav($pdoSav, $galec);
	$ldSav = getLdSav($pdoSav, $sav['sav'], 'litige');
	foreach ($ldSav as $ld) {
		$dest[] = $ld['email'];
	}
	$cc = [EMAIL_LITIGES];
	ob_start();
	include('pdf/pdf-contrainte-commun.php');
	include('pdf/partials/echanges-mag.php');
	$html = ob_get_contents();
	ob_end_clean();
	include 'contrainte/build-pdf.php';
	$subjet = str_replace('{SPECIFIQUE_SUJET}', 'Pôle SAV', $subjet);
	$link = str_replace('{ID_CONTRAINTE}', $_GET['contrainte'], $link);
	$mailFile = 'mail/mail_commun_sav_achats.php';
	include 'contrainte/send-mail.php';
} elseif ($_GET['contrainte'] == 7) {
	// retour verif video
	$dest = [EMAIL_LITIGES];
	$subjet = str_replace('{SPECIFIQUE_SUJET}', 'VIDEO réponse', $subjet);
	$link = str_replace('{ID_CONTRAINTE}', $_GET['contrainte'], $link);
	$link = '<a href="' . SITE_ADDRESS . '/index.php?litiges/bt-action-add.php?id=' . $_GET['id'] . '"> cliquant ici</a>';
	$mailFile = 'mail/mail-rep-video.php';
	include 'contrainte/send-mail-nopdf.php';
} elseif ($_GET['contrainte'] == 5) {
	// reponse sav, on ne fait rien, on redirige
	header('Location:bt-action-add.php?id=' . $_GET['id'] . '&success=ok');
} elseif ($_GET['contrainte'] == 8 || $_GET['contrainte'] == 9 || $_GET['contrainte'] == 10 || $_GET['contrainte'] == 14 || $_GET['contrainte'] == 15) {
	$serviceCorrespondance = [
		8 	=> 1,
		9	=> 2,
		10  => 3,
		14  => 29,
		15  => 28
	];
	$ldAchat = getLdAchat($pdoUser, $serviceCorrespondance[$_GET['contrainte']]);
	foreach ($ldAchat as $ld) {
		$dest[] = $ld['email'];
	}
	$dest[] = 'stephane.wendling@btlecest.leclerc';
	$cc = [EMAIL_LITIGES];
	ob_start();
	include('pdf/pdf-contrainte-commun.php');
	include('pdf/partials/echanges-mag.php');
	$html = ob_get_contents();
	ob_end_clean();
	include 'contrainte/build-pdf.php';
	$subjet = str_replace('{SPECIFIQUE_SUJET}', 'Intervention achats', $subjet);
	$link = str_replace('{ID_CONTRAINTE}', $_GET['contrainte'], $link);
	$mailFile = 'mail/mail_commun_sav_achats.php';
	include 'contrainte/send-mail.php';
} elseif ($_GET['contrainte'] == 6) {
	// envoi demande de recherche video a Benoit
	$dest = ['benoit.dubots@btlecest.leclerc'];
	ob_start();
	include('pdf/pdf-contrainte-commun.php');
	include('pdf/partials/detail-more.php');
	include('pdf/partials/ctrl-stock.php');
	$html = ob_get_contents();
	ob_end_clean();
	include 'contrainte/build-pdf.php';
	$subjet = str_replace('{SPECIFIQUE_SUJET}', 'Recherche video', $subjet);
	$link = str_replace('{ID_CONTRAINTE}', $_GET['contrainte'], $link);
	$mailFile = 'mail/mail-dde-video.php';
	include 'contrainte/send-mail.php';
}
elseif ($_GET['contrainte'] == 12) {
	// commmssion sav
	$dest = ['robert.dallasega@btlecest.leclerc', 'luc.muller@btlecest.leclerc',];
	ob_start();
	include('pdf/pdf-contrainte-commun.php');
	include('pdf/partials/ca.php');
	include('pdf/partials/detail-more.php');
	include('pdf/partials/actions.php');
	$html = ob_get_contents();
	ob_end_clean();
	include 'contrainte/build-pdf.php';

	$subjet = str_replace('{SPECIFIQUE_SUJET}', 'Commission SAV', $subjet);
	$link = str_replace('{ID_CONTRAINTE}', $_GET['contrainte'], $link);
	$mailFile = 'mail/mail_commun_sav_achats.php';
	include 'contrainte/send-mail.php';
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