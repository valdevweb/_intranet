<?php
require('../../../config/config.inc.php');



require '../../../Class/Db.php';
require '../../../Class/BaDao.php';

$db=new Db();
$pdoQlik=$db->getPdo('qlik');

$baDao= new BaDao($pdoQlik);
// 	echo "<pre>";
// 	print_r($_POST);
// 	echo '</pre>';


if (isset($_POST['id']) && strlen($_POST['id'])>5){
	$datas=$baDao->getArtById($_POST['id']);
echo json_encode($datas);

}



