
<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config/config.inc.php';
require 'Class/Db.php';

$db=new Db();
$pdoQlik=$db->getPdo('qlik');



function getBasearticle($pdoQlik){
	$req=$pdoQlik->query("SELECT * FROM basearticles LIMIT 1");
	return $req->fetch(PDO::FETCH_ASSOC);
}
function copyBa($pdoQlik){
	$req=$pdoQlik->query("INSERT INTO ba SELECT * FROM basearticles");
}

$qlikBa=getBasearticle($pdoQlik);
if(isset($qlikBa['DateExecutionScriptQlik'])){
	$dateImport=new DateTime($qlikBa['DateExecutionScriptQlik']);
	$today=(new DateTime())->setTime(0,0);
	if($dateImport==$today){
		$pdoQlik->query("DELETE FROM `ba`");
		copyBa($pdoQlik);

	}


}
