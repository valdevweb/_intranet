<?php 
require_once '../../../config/config.php';

require_once '../../../Class/litiges/ActionDao.php';

$pdoLitige=$db->getPdo('litige');
$actionDao=new ActionDao($pdoLitige);

if(isset($_POST['id_action'])){
    $action=$actionDao->findAction($_POST['id_action']);
    echo json_encode($action);
}


