<?php

if(VERSION=='_'){
	$dest=['valerie.montusclat@btlec.fr'];
}else{
	$dest=['pilotageprepa@btlec.fr'];
}
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
$msg=getActionMsg($pdoLitige);

	// $pdfContent = $mpdf->Output();
$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);
$attachmentPdf = new Swift_Attachment($pdfContent, $filename, 'application/pdf');
// // content
$link='<a href="'.SITE_ADDRESS.'/index.php?litiges/ctrl-stock.php?id='.$litige[0]['id_main'].'"> cliquant ici</a>';

$htmlMail = file_get_contents('mail-dde-ctrl-stock.php');
$htmlMail=str_replace('{DOSSIER}',$litige[0]['dossier'],$htmlMail);
$htmlMail=str_replace('{LINK}',$link,$htmlMail);
$htmlMail=str_replace('{MSG}',$msg['libelle'],$htmlMail);
// // sujet
$subject='Portail BTLec - Litiges - Contrôle de stock ';

$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')
->attach($attachmentPdf)
->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec Est'))
->setTo($dest);

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