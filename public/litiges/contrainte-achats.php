<?php
// correspondance num contrainte et num service
$serviceCorrespondance=[
	8 	=>1,
	9	=>2,
	10  =>3,
	14  =>29,
	15  =>28
];
$ldAchat=getLdAchat($pdoUser,$serviceCorrespondance[$_GET['contrainte']]);

if(VERSION=='_'){
	$achatDest=['valerie.montusclat@btlec.fr'];
	$cc='valerie.montusclat@btlec.fr';
}
else{

	foreach ($ldAchat as $ld) {
		$achatDest[]=$ld['email'];

	}
	$achatDest[]='stephane.wendling@btlec.fr';
	$cc='btlecest.portailweb.litiges@btlec.fr';

}



		// génération du pdf
$footer='<table class="padding-table">';
$footer.='<tr><td class="footer full-width">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td></tr></table>';
ob_start();
include('pdf/pdf-achat.php');



$html=ob_get_contents();
ob_end_clean();
$mpdf = new \Mpdf\Mpdf();
$mpdf->SetHTMLFooter($footer);
$mpdf->AddPage('', '', '', '', '','', '',  '',  40, 0, 10);
$mpdf->WriteHTML($html);
$pdfContent = $mpdf->Output('', 'S');
		// $pdfContent = $mpdf->Output();
$filename='litige '.$litige[0]['dossier'].' - fiche suivi achats.pdf';

		// recup msg action
$msg=getActionMsg($pdoLitige);


$link='<a href="'.SITE_ADDRESS.'/index.php?litiges/intervention-achats.php?id='.$litige[0]['id_main'].'"> cliquant ici</a>';

$htmlMail = file_get_contents('mail/mail_dde_sav_achats.php');
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
->setTo($achatDest)
->addCc($cc)
->attach($attachmentPdf);

$delivered=$mailer->send($message);

if($delivered !=0){
	header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');
}
else{
	$errors[]="impossible d'envoyer le mail";

}


