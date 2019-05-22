<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

require_once '../../vendor/autoload.php';

require 'info-litige.fn.php';

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






function getMagName($pdoUser, $idwebuser)
{
	$req=$pdoUser->prepare("SELECT btlec.sca3.mag FROM users LEFT JOIN btlec.sca3 ON users.galec=btlec.sca3.galec WHERE users.id= :id_web_user ");
	$req->execute(array(
		':id_web_user'	=>$idwebuser
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

// $etat 1 =ctrl ok
// $etat 0= rien
// $etat 2 =ctrl demandé

function updateCtrl($pdoLitige, $etat)
{
	$req=$pdoLitige->prepare("UPDATE dossiers SET ctrl_ok=:ctrl_ok WHERE id=:id");
	$req->execute(array(
		':ctrl_ok'	=>$etat,
		':id'		=>$_GET['id']
	));
	return $req->rowCount();
}



$infos=getInfos($pdoLitige);
$analyse=getAnalyse($pdoLitige);
$litige=getLitige($pdoLitige);




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
	$htmlMail = file_get_contents('mail-dde-ctrl-stock.php');
	$htmlMail=str_replace('{DOSSIER}',$litige[0]['dossier'],$htmlMail);
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
	->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
	// ->setTo(['pilotageprepa@btlec.fr']);
	->setTo(['valerie.montusclat@btlec.fr']);
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
elseif($_GET['contrainte']==1)
{
	$row=updateCtrl($pdoLitige);
	if($row==1)
	{
		header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');
	}
	else
	{
		$errors[]="impossible de mettre à jour la base de donnée";

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