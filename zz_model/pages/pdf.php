<?php

//----------------------------------------------------------------
//		INCLUDES
//----------------------------------------------------------------
require_once  '../vendor/autoload.php';
require "../functions/ld.fn.php";



		//----------------------------------------------
		//  		PDF
		//----------------------------------------------

		// récupération du contenu html du pdf
		ob_start();
		include('bordereau_retro.php');
		$html=ob_get_contents();
		ob_end_clean();

		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML($html);


		$pdfContent = $mpdf->Output('', 'S');
		// $pdfContent = $mpdf->Output();
		$filename='demande de rétrocession - ' .$magFrom['deno'].' vers '.$magDdeur['deno'] . '.pdf';

		// --------------------------------------
		// destinataires

		$mailDdeur=getMailMag($pdoSav,$retroInfo['id_web_to']);
		$mailFrom=getMailMag($pdoSav,$retroInfo['id_web_from']);
		$savDest=getMailSav($pdoSav, 'retro', $magDdeur['sav']);


			//"applatissement du tableau de ld
		$magDest=[];
		foreach ($mailDdeur as $mail) {
			$magDest[]=$mail['email'];
		}
		foreach ($mailFrom as $mail) {
			$magDest[]=$mail['email'];
		}
		foreach ($savDest as $mail) {
			$magDest[]=$mail['email'];
		}

$htmlMail = file_get_contents('mail_ar_mag_saisie.php');
$htmlMail=str_replace('{PROD}',$prod,$htmlMail);
$htmlMail=str_replace('{MAGFROM}',$from,$htmlMail);
$htmlMail=str_replace('{MAGTO}',$_SESSION['nom'],$htmlMail);
		$subject='Portail SAV Leclerc - Rétrocession - ';



		// ---------------------------------------
		// initialisation de swift
		$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
		$mailer = new Swift_Mailer($transport);
		$attachmentPdf = new Swift_Attachment($pdfContent, $filename, 'application/pdf');

		$message = (new Swift_Message($subject))
		->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail SAV Leclerc'))
// // // ->setTo(array('valerie.montusclat@btlec.fr', 'valerie.montusclat@btlec.fr' => 'val'))
		->setTo($magDest)
		->setBody($htmlMail, 'text/html')
		->attach($attachmentPdf);
// 		// ->attach(Swift_Attachment::fromPath('demande-culturel.xlsx'));

		$mailer->send($message);





		// LES OPTIONS
		//
		//
		//
		// 		//CHANGER L ORIENTATION
		// $mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
		//
		//
		// ob_start();
		//
		// AJOUTER UN FOOTER
include('pdf-fiche-suivi.php');
$html=ob_get_contents();
ob_end_clean();
$footer='<table class="padding-table"><tr><td>TRAITEMENT</td></tr><tr><td class="spacing-l"></td></tr><tr><td>Date et validation</td></tr><tr><td class="spacing-l"></td></tr></table>';
$path='http://172.30.92.53/'.VERSION.'upload/litiges/'.$html;

$mpdf = new \Mpdf\Mpdf();
$mpdf->SetHTMLFooter($footer);
$mpdf->WriteHTML($path);
		// $pdfContent = $mpdf->Output('', 'S');
$pdfContent = $mpdf->Output();