<?php

session_start();
include('../../config/config.inc.php');
require '../../config/db-connect.php';

include('../../Class/evo/EvoDao.php');

$evoMgr=new EvoDao($pdoEvo);
$thisEvo=$evoMgr->getThisEvo($_POST['id_evo']);

echo $thisEvo['objet'];

