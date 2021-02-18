<?php

session_start();
include('../../config/config.inc.php');
require '../../config/db-connect.php';

include('../../Class/EvoManager.php');

$evoMgr=new EvoManager($pdoEvo);
$thisEvo=$evoMgr->getThisEvo($_POST['id_evo']);

echo $thisEvo['objet'];

