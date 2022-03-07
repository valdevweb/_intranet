<?php

require '../../Class/mag/MagDao.php';
require '../../Class/mag/MagEntity.php';

$magDao = new MagDao($pdoMag);
$infoMag = $magDao->getMagByGalec($fLitige['galec']);
$codeBt = $infoMag->getId();
$mailMag = array('ga-btlecest-' . $codeBt . '-rbt@btlecest.leclerc');
$from = UserHelpers::getFullname($pdoUser, $rep['id_web_user']);
