<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.php';
include 'Class/litiges/ActionDao.php';
include 'Class/litiges/LitigeDao.php';
include 'vendor/autoload.php';


$pdoLitige=$db->getPdo('litige');

$actionDao=new ActionDao($pdoLitige);
$litigeDao=new LitigeDao($pdoLitige);

$idContrainteDde=6;
$idContrainteRep=7;
$demandeVideo=$actionDao->getLitigeByContrainte($idContrainteDde);

if(empty($demandeVideo)){
    exit();
}

foreach ($demandeVideo as $key => $video) {
    $repondu=$actionDao->isReponseContrainte($video['id_dossier'], $idContrainteRep, $video['date_action']);
    if(!$repondu){
        // envoi mail
        echo "send mail for ". $video['id_dossier']. '<br>';
        $infoLitige=$litigeDao->getLitigeInfoMagById($video['id_dossier']);
        $dest = ['pilotageprepa@btlec.fr'];
        if(VERSION=="_"){
            $dest=[MYMAIL];
        }

        $subject = "Relance demande de recherche vidéo - litige {$infoLitige['dossier']} - magasin {$infoLitige['btlec']} {$infoLitige['deno']}";
        $link = '<a href="' . SITE_ADDRESS . '/index.php?litiges/intervention.php?id=' . $video['id_dossier'] . '&id_contrainte='.$idContrainteDde.'"> cliquant ici</a>';


        $transport = (new Swift_SmtpTransport('217.0.222.26', 25));
        $mailer = new Swift_Mailer($transport);
        
        $htmlMail = file_get_contents('mail-relance-video.html');
        $htmlMail=str_replace('{LINK}',$link,$htmlMail);
        $htmlMail=str_replace('{MAG}',$infoLitige['btlec'].' '. $infoLitige['deno'],$htmlMail);
        $htmlMail=str_replace('{NUMDOSSIER}',$infoLitige['dossier'],$htmlMail);
  
        $message = (new Swift_Message($subject))
        ->setBody($htmlMail, 'text/html')
        ->setFrom(EXPEDITEUR_MAIL)
        ->setTo($dest);

        
        if (!$mailer->send($message, $failures)){
            print_r($failures);
        }else{
            $success[]="mail envoyé avec succés";
        }


    }
}


