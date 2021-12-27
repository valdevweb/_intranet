<?php


require('../../../config/config.inc.php');

include '../../../Class/Db.php';
include '../../../Class/CrudDao.php';


$db=new Db();
$pdoCasse=$db->getPdo('casse');

$casseCrud=new CrudDao($pdoCasse);

if(isset($_POST)){
	if(isset($_POST['id_palette']) && $_POST['id_palette'] !="" && isset($_POST['id_casse']) && $_POST['id_casse']!=""){
		$casseCrud->updateOneField("casses", 'id_palette', $_POST['id_palette'], $_POST['id_casse']);
	}
}

