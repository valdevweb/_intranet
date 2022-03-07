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
	$achatDest=[MYMAIL];
	$cc=MYMAIL;
}
else{
	foreach ($ldAchat as $ld) {
		$achatDest[]=$ld['email'];

	}
	$achatDest[]='stephane.wendling@btlecest.leclerc';
	$cc=EMAIL_LITIGES;

}



		// génération du pdf

$mpdf = new \Mpdf\Mpdf();
$mpdf->setFooter(PDF_FOOTER_PAGE);
$mpdf->WriteHTML($html);
$pdfContent = $mpdf->Output('', 'S');
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
$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
$mailer = new Swift_Mailer($transport);
$attachmentPdf = new Swift_Attachment($pdfContent, $filename, 'application/pdf');

$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')
->setFrom(EMAIL_NEPASREPONDRE)
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


