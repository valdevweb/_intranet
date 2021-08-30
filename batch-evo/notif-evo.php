<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'vendor/autoload.php';
include 'Class/Db.php';
include 'Class/evo/NotifDao.php';


$db=new Db();

$pdoEvo=$db->getPdo('evo');

$notifDao=new NotifDao($pdoEvo);

$notifs=$notifDao->getTodaysNotifs(date('Y-m-d'));



if(!empty($notifs)){
	foreach ($notifs as $key => $notif) {

		if(VERSION=="_"){
			$dest=['valerie.montusclat@btlec.fr'];

		}else{
			$dest=[$notif['email']];
		}


		$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
		$mailer = new Swift_Mailer($transport);



		$htmlMail = file_get_contents(DIR_SITE.'batch-evo/mail-notif.html');

		$htmlMail=str_replace('{X}',$notif['id_evo'],$htmlMail);
		$htmlMail=str_replace('{OBJET}',$notif['objet'],$htmlMail);
		$htmlMail=str_replace('{EVO}',$notif['evo'],$htmlMail);
		$htmlMail=str_replace('{NOTIF}',$notif['notif'],$htmlMail);
		$subject='[Demande d\'evo] - '.$notif['title'];
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTlec'))
		->setTo($dest);

		if (!$mailer->send($message, $failures)){
			print_r($failures);
		}else{
			$success[]="mail envoyé avec succés";
		}




	}
}