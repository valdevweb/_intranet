<?php

require '../../Class/MagDao.php';
require '../../Class/Mag.php';

$magDao=new MagDao($pdoMag);
$infoMag=$magDao->getMagByGalec($fLitige['galec']);
$codeBt=$infoMag->getId();
$mailMag=array($codeBt.'-rbt@btlec.fr');




				$from=UserHelpers::getFullname($pdoUser, $rep['id_web_user']);
