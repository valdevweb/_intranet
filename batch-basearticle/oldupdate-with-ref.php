
<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config/config.inc.php';
require 'Class/Db.php';



function insertRef($pdoQlik, $article, $dossier, $ref){
	$req=$pdoQlik->prepare("INSERT INTO ba_ref (id, article, dossier, ref) VALUES (:id, :article, :dossier, :ref)" );
	$req->execute([
		':id'	=>$article.$dossier,
		':article'	=>$article,
		':dossier'	=>$dossier,
		':ref'		=>$ref

	]);
}


function getBasearticle($pdoQlik){
	$req=$pdoQlik->query("SELECT * FROM basearticles LIMIT 1");
	return $req->fetch(PDO::FETCH_ASSOC);
}


function getRef($pdoQlik){
	// $req=$pdoQlik->query("SELECT * FROM ba_ref LIMIT 500 OFFSET 357500");
	$req=$pdoQlik->prepare("SELECT * FROM ba_ref ", array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false));
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getArticle($pdoQlik, $article, $dossier){
	$req=$pdoQlik->prepare("SELECT id from ba where article= :article AND dossier= :dossier");

	$req->execute([
		':article'		=>$article,
		':dossier'		=>$dossier
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}
function updateBa($pdoQlik){
	$req=$pdoQlik->query("UPDATE ba LEFT JOIN ba_ref ON ba.id=ba_ref.id SET ba.ref=ba_ref.ref");
}

$db=new Db();
$pdoQlik=$db->getPdo('qlik');

$file=DIR_IMPORT_GESSICA."SCEBFART.csv";



$row=0;

if (($handle = fopen($file, "r")) !== FALSE) {
		$pdoQlik->query("DELETE FROM `ba_ref`");

	$errArr=[];
	$i=0;
	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if($row==0){
			$entete=$data;
		}else{
	if($data[85]!=3){
			insertRef($pdoQlik,$data[1] ,$data[0], $data[20]);
		}

		}
		$row++;
	}
	$row=0;
	fclose($handle);
}


updateBa($pdoQlik);
