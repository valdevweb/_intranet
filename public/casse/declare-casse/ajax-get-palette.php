<?php

require('../../../config/config.inc.php');

include '../../../Class/Db.php';
include '../../../Class/casse/PalettesDao.php';


$db=new Db();
$pdoCasse=$db->getPdo('casse');

$paletteDao=new PalettesDao($pdoCasse);
if(isset($_POST['id_palette'])){
	$palette=$paletteDao->getPalette($_POST['id_palette']);

	if($palette['destruction']==1){
		echo "Palette de destruction";
	}else{
		echo "Palette autre";
	}

}
