<?php
if (preg_match('/_btlecest/', dirname(__FILE__))) {
    set_include_path("D:\www\_intranet\_btlecest\\");
} else {
    set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.php';
include 'Class/litiges/ActionDao.php';
include 'Class/litiges/LitigeDao.php';
include 'vendor/autoload.php';


$pdoLitige = $db->getPdo('litige');

$actionDao = new ActionDao($pdoLitige);
$litigeDao = new LitigeDao($pdoLitige);

$idContrainteDde = 2;
$idContrainteRep = 1;
$demandeCtrlStock = $actionDao->getLitigeByContrainte($idContrainteDde);

if (empty($demandeCtrlStock)) {
    exit();
}


foreach ($demandeCtrlStock as $key => $ctrl) {
    // $repondu=$actionDao->isReponseContrainte($ctrl['id_dossier'], $idContrainteRep, $ctrl['date_action']);
    $infoLitige = $litigeDao->getLitigeInfoMagById($ctrl['id_dossier']);

    if ($infoLitige['ctrl_ok'] == 0) {
        echo "send mail for " . $ctrl['id_dossier'] . '<br>';
        $infoLitige = $litigeDao->getLitigeInfoMagById($ctrl['id_dossier']);
        $dest = [EMAIL_PILOTAGE_PREPA];
        if (VERSION == "_") {
            $dest = [MYMAIL];
        }

        $subject = "Relance demande de contrôle de stock - litige {$infoLitige['dossier']} - magasin {$infoLitige['btlec']} {$infoLitige['deno']}";
        $link = '<a href="' . SITE_ADDRESS . '/index.php?litiges/ctrl-stock.php?id=' . $ctrl['id_dossier'] . '"> cliquer</a>';


        $transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
        $mailer = new Swift_Mailer($transport);

        $htmlMail = file_get_contents(DIR_SITE.'batch-litige/mail-relance-stock.html');
        $htmlMail = str_replace('{LINK}', $link, $htmlMail);
        $htmlMail = str_replace('{MAG}', $infoLitige['btlec'] . ' ' . $infoLitige['deno'], $htmlMail);
        $htmlMail = str_replace('{NUMDOSSIER}', $infoLitige['dossier'], $htmlMail);
        $htmlMail = str_replace('{DATEACTION}', date('d/m/Y', strtotime($demandeCtrlStock[$key]['date_action'])), $htmlMail);

        $message = (new Swift_Message($subject))
            ->setBody($htmlMail, 'text/html')
            ->setFrom(EXPEDITEUR_MAIL)
            ->setTo($dest);


        if (!$mailer->send($message, $failures)) {
            print_r($failures);
        } else {
            $success[] = "mail envoyé avec succés";
        }
    }else{
        echo "do not send mail for " . $ctrl['id_dossier'] . '<br>';

    }


}
