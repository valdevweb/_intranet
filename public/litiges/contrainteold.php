<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

require_once '../../vendor/autoload.php';

require 'info-litige.fn.php';
require 'echanges.fn.php';

//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";



$errors=[];
$success=[];
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


function getMagSav($pdoSav,$galec){
	$req=$pdoSav->prepare("SELECT sav FROM mag WHERE galec = :galec");
	$req->execute([
		':galec'		=>$galec
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getLdSav($pdoSav, $sav, $module){
	$req=$pdoSav->prepare("SELECT email FROM mail_sav LEFT JOIN sav_users ON mail_sav.id_user_sav=sav_users.id WHERE mail_sav.sav= :sav AND module= :module");
	$req->execute([
		':sav'			=>$sav,
		':module'		=>$module
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getLdAchat($pdoUser,$serviceId){
	$req=$pdoUser->prepare("SELECT email FROM intern_users WHERE id_service= :service");
	$req->execute([
		':service'			=>$serviceId
	]);

	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function updateCtrl($pdoLitige, $etat)
{
	$req=$pdoLitige->prepare("UPDATE dossiers SET ctrl_ok=:ctrl_ok WHERE id=:id");
	$req->execute(array(
		':ctrl_ok'	=>$etat,
		':id'		=>$_GET['id']
	));
	return $req->rowCount();
}

function getActionMsg($pdoLitige){
	$req=$pdoLitige->prepare("SELECT libelle FROM action WHERE id= :id");
	$req->execute([
		':id'		=>$_GET['action']
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

$infos=getInfos($pdoLitige);
$analyse=getAnalyse($pdoLitige);
$litige=getLitige($pdoLitige);
$firstCmt=getComment($pdoLitige);




//------------------------------------------------------
//			CONTRAINTE ACTUELLES
//------------------------------------------------------
/*

1 = envoi mail de demande de contrôle de stock
3= mettre le contrôle de stock à  oui
 */
if($_GET['contrainte']==2)
{
	// 1 récup info litige pour envoyer demande de contrôle aux pilotes
	ob_start();
	include('pdf-pilote-stock.php');
	$html=ob_get_contents();
	ob_end_clean();

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->WriteHTML($html);
	$pdfContent = $mpdf->Output('', 'S');
	// $pdfContent = $mpdf->Output();
	$filename='litige '.$litige[0]['dossier'].'- fiche pilotage.pdf';

	// $pdfContent = $mpdf->Output();
	$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
	$mailer = new Swift_Mailer($transport);
	$attachmentPdf = new Swift_Attachment($pdfContent, $filename, 'application/pdf');
// // content
	$link='<a href="'.SITE_ADDRESS.'/index.php?litiges/ctrl-stock.php?id='.$litige[0]['id_main'].'"> cliquant ici</a>';

	$htmlMail = file_get_contents('mail-dde-ctrl-stock.php');
	$htmlMail=str_replace('{DOSSIER}',$litige[0]['dossier'],$htmlMail);
	$htmlMail=str_replace('{LINK}',$link,$htmlMail);
// // sujet
	$subject='Portail BTLec - Litiges - Contrôle de stock ';
	// PROD
// $message = (new Swift_Message($subject))
// 	->setBody($htmlMail, 'text/html')
// 	->attach($attachmentPdf)
// 	->setFrom(array('litigelivraison@btlec.fr' => 'Litige Livraison'))
// 	->setTo(['pilotageprepa@btlec.fr'])
// 	->setBcc(['litigelivraison@btlec.fr','valerie.montusclat@btlec.fr']);
// 	$delivered = $mailer->send($message);
// dev
	$message = (new Swift_Message($subject))
	->setBody($htmlMail, 'text/html')
	->attach($attachmentPdf)
	->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec Est'))
	->setTo(['pilotageprepa@btlec.fr']);
	// ->setTo(['valerie.montusclat@btlec.fr']);
	// ->addBcc('litigelivraison@btlec.fr');
	$delivered = $mailer->send($message);
	if($delivered !=0)
	{
		// met à jour ctrl_ok =>2 =demande de contrôle en cours
		updateCtrl($pdoLitige, 2);
		header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');
	}
	else
	{
		$errors[]="impossible d'envoyer le mail";

	}


}
// controle de stock fait
elseif($_GET['contrainte']==1)
{
	$row=updateCtrl($pdoLitige, 1);
	if($row==1)
	{
		header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');
	}
	else
	{
		$errors[]="impossible de mettre à jour la base de donnée";

	}
}
// demande d'intervention du pole sav
elseif($_GET['contrainte']==4){
	$galec=$litige[0]['galec'];
	$sav=getMagSav($pdoSav,$galec);
	$ldSav=getLdSav($pdoSav, $sav['sav'], 'litige');
	if(!empty($ldSav)){

		// génération du pdf
		$footer='<table class="padding-table">';
		$footer.='<tr><td class="footer full-width">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td></tr></table>';
		ob_start();
		include('pdf-sav.php');
		$html=ob_get_contents();
		ob_end_clean();
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->SetHTMLFooter($footer);
		$mpdf->AddPage('', '', '', '', '','', '',  '',  40, 0, 10); // margin footer
		$mpdf->WriteHTML($html);
		$pdfContent = $mpdf->Output('', 'S');
		// $pdfContent = $mpdf->Output();
		$filename='litige '.$litige[0]['dossier'].' - fiche suivi sav.pdf';

		// recup msg action
		$msg=getActionMsg($pdoLitige);

		if(VERSION=='_'){
			$savDest='valerie.montusclat@btlec.fr';
		}
		else{

			foreach ($ldSav as $ld) {
				$savDest[]=$ld['email'];
			}
		}

		// mail

		$link='<a href="'.SITE_ADDRESS.'/index.php?litiges/intervention-sav.php?id='.$litige[0]['id_main'].'"> cliquant ici</a>';

		$htmlMail = file_get_contents('mail_dde_sav_achats.php');
		$htmlMail=str_replace('{MAG}',$litige[0]['mag'],$htmlMail);
		$htmlMail=str_replace('{DOSSIER}',$litige[0]['dossier'],$htmlMail);
		$htmlMail=str_replace('{MSG}',$msg['libelle'],$htmlMail);
		$htmlMail=str_replace('{LINK}',$link,$htmlMail);
		$subject='Portail BTLec - Litige livraison '.$litige[0]['dossier'].' - '.$litige[0]['mag'];
// ---------------------------------------
// initialisation de swift
		$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
		$mailer = new Swift_Mailer($transport);
		$attachmentPdf = new Swift_Attachment($pdfContent, $filename, 'application/pdf');

		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')

		->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec Est'))
// ->setTo(array('valerie.montusclat@btlec.fr', 'valerie.montusclat@btlec.fr' => 'val'))
		->setTo($savDest)
		->addCc('btlecest.portailweb.logistique@btlec.fr')
		// ->addCc('valerie.montusclat@btlec.fr')
		->addBcc('valerie.montusclat@btlec.fr')
		->attach($attachmentPdf);
		// ->attach(Swift_Attachment::fromPath('demande-culturel.xlsx'));
// ou
// ->setBcc([adress@btl.fr, adresse@bt.fr])

// echec => renvoie 0

		$delivered=$mailer->send($message);

		if($delivered !=0)
		{
			header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');

		}
		else
		{
			$errors[]="impossible d'envoyer le mail";

		}



	}
	else
	{
		$errors[]="la liste de diffusion sav est vide";
	}
}
	// demande intervention service achats

elseif($_GET['contrainte']==8 || $_GET['contrainte']==9 || $_GET['contrainte']==10){
	$serviceCorrespondance=[
		8 	=>1,
		9	=>2,
		10  =>3
	];
	$ldAchat=getLdAchat($pdoUser,$serviceCorrespondance[$_GET['contrainte']]);

	if(!empty($ldAchat)){

		// génération du pdf
		$footer='<table class="padding-table">';
		$footer.='<tr><td class="footer full-width">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td></tr></table>';
		ob_start();
		include('pdf-achat.php');



		$html=ob_get_contents();
		ob_end_clean();
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->SetHTMLFooter($footer);
		$mpdf->AddPage('', '', '', '', '','', '',  '',  40, 0, 10); // margin footer
		$mpdf->WriteHTML($html);
		$pdfContent = $mpdf->Output('', 'S');
		// $pdfContent = $mpdf->Output();


		$filename='litige '.$litige[0]['dossier'].' - fiche suivi sav.pdf';

		// recup msg action
		$msg=getActionMsg($pdoLitige);

		if(VERSION=='_'){
			$achatDest='valerie.montusclat@btlec.fr';
			$cc='valerie.montusclat@btlec.fr';
		}
		else{

			foreach ($ldAchat as $ld) {
				$achatDest[]=$ld['email'];
			}
			$achatDest[]='stephane.wendling@btlec.fr';
			$cc='btlecest.portailweb.logistique@btlec.fr';

		}

		$link='<a href="'.SITE_ADDRESS.'/index.php?litiges/intervention-achats.php?id='.$litige[0]['id_main'].'"> cliquant ici</a>';

		$htmlMail = file_get_contents('mail_dde_sav_achats.php');
		$htmlMail=str_replace('{MAG}',$litige[0]['mag'],$htmlMail);
		$htmlMail=str_replace('{DOSSIER}',$litige[0]['dossier'],$htmlMail);
		$htmlMail=str_replace('{MSG}',$msg['libelle'],$htmlMail);
		$htmlMail=str_replace('{LINK}',$link,$htmlMail);
		$subject='Portail BTLec - Litige livraison '.$litige[0]['dossier'].' - '.$litige[0]['mag'];
// ---------------------------------------
// initialisation de swift
		$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
		$mailer = new Swift_Mailer($transport);
		$attachmentPdf = new Swift_Attachment($pdfContent, $filename, 'application/pdf');

		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')

		->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec Est'))
// ->setTo(array('valerie.montusclat@btlec.fr', 'valerie.montusclat@btlec.fr' => 'val'))
		->setTo($achatDest)
		->addCc($cc)
		// ->addCc('valerie.montusclat@btlec.fr')
		->addBcc('valerie.montusclat@btlec.fr')
		->attach($attachmentPdf);
		// ->attach(Swift_Attachment::fromPath('demande-culturel.xlsx'));
// ou
// ->setBcc([adress@btl.fr, adresse@bt.fr])

// echec => renvoie 0

		$delivered=$mailer->send($message);

		if($delivered !=0)
		{
			header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');

		}
		else
		{
			$errors[]="impossible d'envoyer le mail";

		}



	}
	else
	{
		$errors[]="la liste de diffusion achats est vide";
	}
}

elseif($_GET['contrainte']==6)
{
	// envoi demande de recherhce video a Benoit
	$msg=getActionMsg($pdoLitige);

	if(VERSION=='_'){
		$dest='valerie.montusclat@btlec.fr';
	}
	else{
		$dest='benoit.dubots@btlec.fr';
	}
	$footer='<table class="padding-table">';
	$footer.='<tr><td class="footer full-width">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td></tr></table>';
	ob_start();
	include('pdf-video.php');
	$html=ob_get_contents();
	ob_end_clean();
	$mpdf = new \Mpdf\Mpdf();
	$mpdf->SetHTMLFooter($footer);
		$mpdf->AddPage('', '', '', '', '','', '',  '',  40, 0, 10); // margin footer
		$mpdf->WriteHTML($html);
		$pdfContent = $mpdf->Output('', 'S');
		// $pdfContent = $mpdf->Output();
		$filename='litige '.$litige[0]['dossier'].' - fiche recap.pdf';

		$link='<a href="'.SITE_ADDRESS.'/index.php?litiges/bt-action-add.php?id='.$litige[0]['id_main'].'"> cliquant ici</a>';

		$htmlMail = file_get_contents('mail-dde-video.php');
		$htmlMail=str_replace('{MAG}',$litige[0]['mag'],$htmlMail);
		$htmlMail=str_replace('{DOSSIER}',$litige[0]['dossier'],$htmlMail);
		$htmlMail=str_replace('{MSG}',$msg['libelle'],$htmlMail);
		$htmlMail=str_replace('{LINK}',$link,$htmlMail);
		$subject='Portail BTLec EST - Litige livraison '.$litige[0]['dossier'].' - '.$litige[0]['mag'] .' - VIDEO demande de recherche';
		$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
		$mailer = new Swift_Mailer($transport);
		$attachmentPdf = new Swift_Attachment($pdfContent, $filename, 'application/pdf');

		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')

		->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec EST'))
		->setTo($dest)
		->addCc('btlecest.portailweb.logistique@btlec.fr')
		// ->addCc('valerie.montusclat@btlec.fr')
		->addBcc('valerie.montusclat@btlec.fr')
		->attach($attachmentPdf);


		$delivered=$mailer->send($message);

		if($delivered !=0)
		{
			header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');

		}
		else
		{
			$errors[]="impossible d'envoyer le mail";
		}
	}
	elseif($_GET['contrainte']==7)
	{
	// reponse demande de recherche de video
		$msg=getActionMsg($pdoLitige);

		if(VERSION=='_'){
			$dest='valerie.montusclat@btlec.fr';
		}
		else{
			$dest='btlecest.portailweb.logistique@btlec.fr';
		}
		$link='<a href="'.SITE_ADDRESS.'/index.php?litiges/bt-detail-litige.php?id='.$litige[0]['id_main'].'"> cliquez ici</a>';

		$htmlMail = file_get_contents('mail-rep-video.php');
		$htmlMail=str_replace('{MAG}',$litige[0]['mag'],$htmlMail);
		$htmlMail=str_replace('{DOSSIER}',$litige[0]['dossier'],$htmlMail);
		$htmlMail=str_replace('{MSG}',$msg['libelle'],$htmlMail);
		$htmlMail=str_replace('{LINK}',$link,$htmlMail);
		$subject='Portail BTLec EST - Litige livraison '.$litige[0]['dossier'].' - '.$litige[0]['mag'] .' - VIDEO réponse';
		$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
		$mailer = new Swift_Mailer($transport);
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')

		->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec EST'))
		->setTo($dest)
		// ->addCc('btlecest.portailweb.logistique@btlec.fr')
		// ->addCc('valerie.montusclat@btlec.fr')
		->addBcc('valerie.montusclat@btlec.fr');
		$delivered=$mailer->send($message);

		if($delivered !=0)
		{
			header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');

		}
		else
		{
			$errors[]="impossible d'envoyer le mail";
		}
	}



	echo $_GET['contrainte'];
	echo '<br>';
	echo $_GET['id'];

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