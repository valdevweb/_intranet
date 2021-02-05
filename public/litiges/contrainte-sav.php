<?php
$galec=$litige[0]['galec'];
$sav=getMagSav($pdoSav,$galec);
if(VERSION=='_'){
	$savDest='valerie.montusclat@btlec.fr';
	$copy=[];
}
else{
	$ldSav=getLdSav($pdoSav, $sav['sav'], 'litige');
	foreach ($ldSav as $ld) {
		$savDest[]=$ld['email'];
		$copy=['btlecest.portailweb.litiges@btlec.fr'];
	}
}




if(!empty($ldSav)){

		// génération du pdf
	$footer='<table class="padding-table">';
	$footer.='<tr><td class="footer full-width">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td></tr></table>';
	ob_start();
	include('pdf/pdf-sav.php');
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


		// mail

		$link='<a href="'.SITE_ADDRESS.'/index.php?litiges/intervention-sav.php?id='.$litige[0]['id_main'].'"> cliquant ici</a>';

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
		->setTo($savDest)
		->setCc($copy)
		->attach($attachmentPdf);

		$delivered=$mailer->send($message);
		if($delivered !=0){
			header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');
		}
		else{
			$errors[]="impossible d'envoyer le mail";
		}
	}
	else
	{
		$errors[]="la liste de diffusion sav est vide";
	}
