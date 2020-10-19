<?php


if(VERSION=='_'){
	$dest=['valerie.montusclat@btlec.fr'];
	$cci=[];
}
else{
	$dest=['robert.dalla-sega@btlec.fr','luc.muller@btlec.fr', ];
	$cci=['valerie.montusclat@btlec.fr', 'nathalie.pazik@btlec.fr', 'jonathan.domange@btlec.fr'];


}


		// génération du pdf
$footer='<table class="padding-table">';
$footer.='<tr><td class="footer full-width">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td></tr></table>';
ob_start();
include('pdf/pdf-commission.php');



$html=ob_get_contents();
ob_end_clean();
$mpdf = new \Mpdf\Mpdf();
$mpdf->SetHTMLFooter($footer);
$mpdf->AddPage('', '', '', '', '','', '',  '',  40, 0, 10);
$mpdf->WriteHTML($html);
$pdfContent = $mpdf->Output('', 'S');
// $pdfContent = $mpdf->Output();

$filename='litige '.$litige[0]['dossier'].' - fiche suivi.pdf';

// recup msg action
$msg=getActionMsg($pdoLitige);



$link='<a href="'.SITE_ADDRESS.'/index.php?litiges/intervention-commission-sav.php?id='.$litige[0]['id_main'].'"> cliquant ici</a>';
// mail commmun
$htmlMail = file_get_contents('mail/mail_dde_sav_achats.php');
$htmlMail=str_replace('{MAG}',$litige[0]['mag'],$htmlMail);
$htmlMail=str_replace('{DOSSIER}',$litige[0]['dossier'],$htmlMail);
$htmlMail=str_replace('{MSG}',$msg['libelle'],$htmlMail);
$htmlMail=str_replace('{LINK}',$link,$htmlMail);
$subject='Portail BTLec Litige livraison => commission SAV - dossier '.$litige[0]['dossier'].' - '.$litige[0]['mag'] ;
// ---------------------------------------
// initialisation de swift
$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);
$attachmentPdf = new Swift_Attachment($pdfContent, $filename, 'application/pdf');

$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')
->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec Est'))
->setTo($dest)
->setBcc($cci)
->attach($attachmentPdf);

if (!$mailer->send($message, $failures)){
  print_r($failures);
}else{
  $success[]="mail envoyé avec succés";
	header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');

}
// if($delivered !=0){
// 	header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');
// }
// else{
// 	$errors[]="impossible d'envoyer le mail";

// }


