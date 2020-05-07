<?php

if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\intranet\btlecest\\");
	$manuel=true;

}
else{
	set_include_path("D:\www\intranet\btlecest\\");
	$manuel=false;

}


include 'config\config.inc.php';
function getContact($pdoCm){
	$req=$pdoCm->query("SELECT * FROM mag_contact");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$contactList=getContact($pdoCm);
	echo "<pre>";
	print_r($contactList);
	echo '</pre>';
