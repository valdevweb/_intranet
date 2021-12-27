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
include 'Class/CrudDao.php';



$db=new Db();

$pdoQlik=$db->getPdo('qlik');
$qlikCrud=new CrudDao($pdoQlik);
// $test=$qlikCrud->getAll("test");


$pdoQlik->query("delete FROM  cdes_fou_qte_init ");
