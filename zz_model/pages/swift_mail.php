<?php

require_once  '../vendor/autoload.php';
require "../functions/ld.fn.php";




//----------------------------------------------
//  		MAIL
//----------------------------------------------


// --------------------------------------
// destinataires
// getMailSav($pdoSav,$module,$sav="")

// function getMailMag($pdoSav,$id_web_user="")
$magLD=getMailMag($pdoSav);
//"applatissement du tableau de ld
$magDest=[];
foreach ($mailLD as $ld) {
	$magDest[]=$ld['email'];
}
// ---------------------------------------


// ---------------------------------------
// gestion du template
$htmlMail = file_get_contents('mail_ar_mag_saisie.php');
$htmlMail=str_replace('{PROD}',$prod,$htmlMail);
$htmlMail=str_replace('{MAGFROM}',$from,$htmlMail);
$htmlMail=str_replace('{MAGTO}',$_SESSION['nom'],$htmlMail);
$subject='Portail SAV Leclerc - Rétrocession - ';

// ---------------------------------------
// initialisation de swift
$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);
$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')

->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail SAV Leclerc'))
// ->setTo(array('valerie.montusclat@btlec.fr', 'valerie.montusclat@btlec.fr' => 'val'))
->setTo($magDest)
// ->addCc($copySender['email'])
->addBcc('valerie.montusclat@btlec.fr');
		// ->attach($attachmentPdf)
		// ->attach(Swift_Attachment::fromPath('demande-culturel.xlsx'));

// echec => renvoie 0
$delivered=$mailer->send($message);
if($delivered >0)
{
}


?>
<!-- exemple mail -->



    <html>
    <head>
        <title></title>
    </head>
    <body style="font-family:arial,helvetica, 'sans serif'; color:dimGray;">
        <p>Bonjour,</p>
        <p>Veuillez trouver ci-joint le bordereau de demande d'enlèvement DEEE pour le magasin <span style="color:dodgerBlue;"><b>{MAG}</b></span></p>
        <p>Détail de la demande : </p>
        <p>{PROD}</p>

        <p></p>
        <p>Cordialement,</p>
        <p style="color:dodgerBlue;"><b>Portail SAV Leclerc</b></p>

    </body>
    </html>