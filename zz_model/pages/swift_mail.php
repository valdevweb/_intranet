<?php
require_once  '../vendor/autoload.php';


foreach ($ld as $mail) {
	$dest[]=$mail['email'];
}
// ---------------------------------------


// ---------------------------------------
// gestion du template
$htmlMail = file_get_contents('template.html');
$htmlMail=str_replace('{PROD}',$prod,$htmlMail);
$subject='Portail SAV Leclerc - Rétrocession - ';

// ---------------------------------------
// initialisation de swift
$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);
$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')
->setFrom(array('ne_pas_repondre@btlec.fr' => 'EXPEDITEUR NAME'))
->setTo($dest)
// copie
// ->addCc($copySender['email'])
// ->setCc($cc);
->addBcc('valerie.montusclat@btlec.fr');
// ->setBcc([adress@btl.fr, adresse@bt.fr])

		// ->attach($attachmentPdf)
		// ->attach(Swift_Attachment::fromPath('demande-culturel.xlsx'));


if (!$mailer->send($message, $failures)){
  print_r($failures);
}else{
  $success[]="mail envoyé avec succés";
}
/*
Failures:
Array (
  0 => receiver@bad-domain.org,
  1 => other-receiver@bad-domain.org
)
*/



/*OPTIONS :

copies
->addCc($copySender['email'])
->setCc($array);

copies cachées
->addBcc('valerie.montusclat@btlec.fr');
->setBcc([adress@btl.fr, adresse@bt.fr])


pj :
->attach(Swift_Attachment::fromPath('demande-culturel.xlsx'));

pj pdf :
$pdfContent = $mpdf->Output('', 'S');
$filename='nompdf.pdf';

$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
$mailer = new Swift_Mailer($transport);
$attachmentPdf = new Swift_Attachment($pdfContent, $filename, 'application/pdf');

$message = (new Swift_Message($subject))
....
->attach($attachmentPdf);

*/



// echec => renvoie 0
$delivered=$mailer->send($message);
if($delivered !=0)
{
}





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