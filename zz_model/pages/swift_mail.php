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
// ou
// ->setBcc([adress@btl.fr, adresse@bt.fr])

// echec => renvoie 0
$delivered=$mailer->send($message);
if($delivered !=0)
{
}







//  Pass a variable name to the send() method
if (!$mailer->send($message, $failures))
{
  echo "Failures:";
  print_r($failures);
}

/*
Failures:
Array (
  0 => receiver@bad-domain.org,
  1 => other-receiver@bad-domain.org
)
*/




?>





<!-- exemple mail -->



  <html>
    <head>
        <title></title>
    </head>
    <body style="font-family:arial,helvetica, 'sans serif'; color:dimGray;">
        {MSG}
        <p>Cordialement,</p>
        <p style="color:darkblue;">------------------<br>
        Portail BTLec EST</p>
        <p style="color:firebrick;">*** Merci de ne pas répondre à ce mail, cette boîte mail n'est pas consultée ***</p>


    </body>
    </html>