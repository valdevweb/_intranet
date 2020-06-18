<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config\config.inc.php';
include 'functions\tasklog.fn.php';

$fieldseparator = ",";
$lineseparator = "\n";

// chemin+ nom des 3 fichiers

$file="D:\www\_intranet\_btlecest\batch-mag\\utf.csv";

$row=0;
// paramètre pour les requetes

// enregistrer au format csv mac
// //passer sur notepas ++
// faire converti

header('Content-Type: text/html; charset=UTF-8');
if (($handle = fopen($file, "r")) !== FALSE) {

	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if($row==0){
			$row++;
		}else{
			echo $nom=$data[0];
			echo "<br>";

			echo $prenom=$data[1];
			echo "<br>";


			echo "<br>";


			echo $tel=$data[7];
			echo "<br>";

			echo $mobile=$data[8];
			echo "<br>";

			echo $mobile=$data[8];
			echo "<br>";

			echo $birth=$data[10];
			echo "<br>";


			echo "<br>";

		}
		$row++;
	}
	$row=0;
	fclose($handle);
}

