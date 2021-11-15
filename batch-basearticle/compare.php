
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
$pdoQlik=$db->getPdo('_qlik');

$file=DIR_IMPORT_GESSICA."SCEBFART.csv";
$file="test.csv";



$row=0;




if (($handle = fopen($file, "r")) !== FALSE) {
	$pdoQlik->query("DELETE FROM `ba_ref`");

	$errArr=[];
	$i=0;
	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if($row==0){
			$entete=$data;
		}else{
			$req=$pdoQlik->prepare("INSERT INTO test (article, dossier) VALUES (:article, :dossier)");
			$req->execute([
				':article'		=>$data[0],
				':dossier'		=>$data[1]
			]);

		}
		$row++;
		echo $row;
		echo "<br>";

	}
	fclose($handle);
}



