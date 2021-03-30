
<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'config/db-connect.php';

function convertToDate($data){
	$date=NULL;
	if(!empty($data)){
		$date=DateTime::createFromFormat('d/m/Y', $data);
		return $date->format("Y-m-d");
	}

	return $date;
}
$thisYear=(new DateTime())->format('Y');
$oldest=$thisYear -3;

// chemin+ nom des 3 fichiers
$file=DIR_IMPORT_GESSICA."SCEBFART.csv";

$row=0;

function getArticle($pdoQlik, $article, $dossier){
	$req=$pdoQlik->prepare("SELECT id from ba where article= :article AND dossier= :dossier", array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false));

	$req->execute([
		':article'		=>$article,
		':dossier'		=>$dossier
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}
function updateBa($pdoQlik,$id, $ref){
	$req=$pdoQlik->prepare("UPDATE ba SET ref= :ref WHERE id=:id");
	$req->execute([
		':ref'		=>$ref,
		':id'		=>$id
	]);
}

// if (($handle = fopen($file, "r")) !== FALSE) {
// 	$errArr=[];
// 	$i=0;
// 	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
// 		if($row==0){
// 			$entete=$data;
// 		}else{
// 			if (substr($data[82],-4)<$oldest) {
// 				if(substr($data[86],-4)<$oldest){

// 				}
// 			}else{
// 				$req=$pdoQlik->prepare("INSERT INTO ba_ref (article, dossier, ref) VALUES (:article, :dossier, :ref) ");
// 				$req->execute([
// 					':article'	=>$data[1],
// 					':dossier'	=>$data[0],
// 					':ref'		=>$data[20]
// 				]);

// 			}

// 		}

// 		$row++;
// 	}
// 	$row=0;
// 	fclose($handle);

// }



if (($handle = fopen($file, "r")) !== FALSE) {
	$errArr=[];
	$i=0;
	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if($row==0){
			$entete=$data;
		}else{
			if($data[85]!=3){
				$id=getArticle($pdoQlik, $data[1], $data[0]);
				if(!empty($id)){
					updateBa($pdoQlik, $id['id'], $data[20]);
				}
			}
		}
		$row++;
	}
	$row=0;
	fclose($handle);
}
