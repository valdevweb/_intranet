<?php

	$msg=getActionMsg($pdoLitige);

	if(VERSION=='_'){
		$cc=$dest='valerie.montusclat@btlec.fr';
	}
	else{
		$dest='benoit.dubots@btlec.fr';
		$cc='btlecest.portailweb.logistique@btlec.fr';
	}
	$footer='<table class="padding-table">';
	$footer.='<tr><td class="footer full-width">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td></tr></table>';
	ob_start();
	include('pdf/pdf-video.php');
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

		$htmlMail = file_get_contents('mail/mail-dde-video.php');
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
		->addCc($cc)
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
