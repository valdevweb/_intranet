<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

require_once '../../vendor/autoload.php';



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
function getLitige($pdoLitige)
{
	$req=$pdoLitige->prepare("
		SELECT
		dossiers.id as id_main,	dossiers.dossier,dossiers.date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,dossiers.user_crea,dossiers.galec,dossiers.etat_dossier,vingtquatre, inversion,inv_article,inv_fournisseur,inv_tarif,inv_descr,nom,
		details.id as id_detail,details.ean,details.id_dossier,	details.palette,details.article,details.tarif,details.qte_cde, details.qte_litige,details.dossier_gessica,details.descr,details.fournisseur,details.pj,
		reclamation.reclamation,
		btlec.sca3.mag, btlec.sca3.centrale, btlec.sca3.btlec,
		etat.etat
		FROM dossiers
		LEFT JOIN details ON dossiers.id=details.id_dossier
		LEFT JOIN reclamation ON details.id_reclamation = reclamation.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		LEFT JOIN etat ON etat_dossier=etat.id
		WHERE dossiers.id= :id ORDER BY date_crea");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}


function getInfos($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT transporteur.transporteur, affrete.affrete, transit.transit, CONCAT(prepa.nom,' ', prepa.prenom) as fullprepa, CONCAT(ctrl.nom,' ',ctrl.prenom) as fullctrl,CONCAT(chg.nom,' ',chg.prenom) as fullchg, mt_transp, mt_assur, mt_fourn, mt_mag, fac_mag, DATE_FORMAT(date_prepa,'%d-%m-%Y') as dateprepa, ctrl_ok FROM dossiers
		LEFT JOIN transporteur ON id_transp=transporteur.id
		LEFT JOIN affrete ON id_affrete=affrete.id
		LEFT JOIN transit ON id_transit=transit.id
		LEFT JOIN equipe as prepa ON id_prepa=prepa.id
		LEFT JOIN equipe as ctrl ON id_ctrl=ctrl.id
		LEFT JOIN equipe as chg ON id_chg=chg.id
		LEFT JOIN equipe as ctrl_stock ON id_ctrl_stock=ctrl_stock.id
		WHERE  dossiers.id= :id ");

	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
$infos=getInfos($pdoLitige);


function getAnalyse($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT gt, imputation, typo, etat, analyse, conclusion FROM dossiers
		LEFT JOIN gt ON id_gt=gt.id
		LEFT JOIN imputation ON id_imputation=imputation.id
		LEFT JOIN typo ON id_typo=typo.id
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN analyse ON id_analyse=analyse.id
		LEFT JOIN conclusion ON id_conclusion=conclusion.id
		WHERE dossiers.id= :id");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}
$analyse=getAnalyse($pdoLitige);



function getMagName($pdoUser, $idwebuser)
{
	$req=$pdoUser->prepare("SELECT btlec.sca3.mag FROM users LEFT JOIN btlec.sca3 ON users.galec=btlec.sca3.galec WHERE users.id= :id_web_user ");
	$req->execute(array(
		':id_web_user'	=>$idwebuser
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}



$fLitige=getLitige($pdoLitige);


function updateCtrl($pdoLitige)
{
	$req=$pdoLitige->prepare("UPDATE dossiers SET ctrl_ok=:ctrl_ok WHERE id=:id");
	$req->execute(array(
		':ctrl_ok'	=>1,
		':id'		=>$_GET['id']
	));
	return $req->rowCount();
}


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
	$filename='litige '.$fLitige[0]['dossier'].'- fiche pilotage.pdf';

	// $pdfContent = $mpdf->Output();
	$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
	$mailer = new Swift_Mailer($transport);
	$attachmentPdf = new Swift_Attachment($pdfContent, $filename, 'application/pdf');
// // content
	$htmlMail = file_get_contents('mail-dde-ctrl-stock.php');
	$htmlMail=str_replace('{DOSSIER}',$fLitige[0]['dossier'],$htmlMail);
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
	->setFrom(array('litigelivraison@btlec.fr' => 'Litige Livraison'))
	->setTo(['pilotageprepa@btlec.fr']);
	// ->setTo(['valerie.montusclat@btlec.fr']);
	// ->addBcc('litigelivraison@btlec.fr');
	$delivered = $mailer->send($message);
	if($delivered !=0)
	{
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