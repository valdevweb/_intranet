
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

// chemin+ nom des 3 fichiers
$cata=DIR_IMPORT_GESSICA."SGSPFCAT.csv";
// $detailFile=DIR_IMPORT_GESSICA."test.csv";
$op=DIR_IMPORT_GESSICA."SCEBFOPR.csv";
$dossier=DIR_IMPORT_GESSICA."SCEBFDOS.csv";
$cde=DIR_IMPORT_GESSICA."SCEFFCFL.csv";
$entete=DIR_IMPORT_GESSICA."SCEFFCFE.csv";
$row=0;
$previousYear=new DateTime("last year");
$previousYear=$previousYear->format('y');

//21e104
//809794
// 809794
$row=0;


if (($handle = fopen($op, "r")) !== FALSE) {
	$errArr=[];
	$i=0;
	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if($row==0){
			$opToDisplay[]=$data;

		}
		if($data[0]=="21E104"){
			$opToDisplay[]=$data;


		}
		$row++;

	}
	fclose($handle);
	$row=0;

}

if (($handle = fopen($cata, "r")) !== FALSE) {
	$errArr=[];
	$i=0;
	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if ($row==0){
			$cataToDisplay[]=$data;

		}
		if($data[1]=="104"){

			$cataToDisplay[]=$data;
		}
		$row++;

	}
	fclose($handle);
	$row=0;

}

if (($handle = fopen($dossier, "r")) !== FALSE) {
	$errArr=[];
	$i=0;
	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if($row==0){
			$dossierToDisplay[]=$data;
		}
		if($data[4]=="21E104"){
			$dossierToDisplay[]=$data;


		}
		$row++;

	}
	fclose($handle);
	$row=0;

}
if (($handle = fopen($cde, "r")) !== FALSE) {
	$errArr=[];
	$i=0;
	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {

		if($row==0){
			$cdeToDisplay[]=$data;
		}
		if($data[4]=="809794"){

			$cdeToDisplay[]=$data;




		}
		$row++;

	}
	fclose($handle);
	$row=0;

}

if (($handle = fopen($entete, "r")) !== FALSE) {
	$errArr=[];
	$i=0;
	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {

		if($row==0){
			$enteteToDisplay[]=$data;
		}
		if($data[0]=="630170"){

			$enteteToDisplay[]=$data;




		}
		$row++;

	}
	fclose($handle);
	$row=0;

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<style type="text/css">
	table, td{
		border:  1px solid #000;
	}
</style>
<body>


	<?php
	// echo "<pre>";
	// print_r($cataToDisplay);
	// echo '</pre>';


	?>


	<table class="table table-sm">
		<tbody>
			<?php foreach ($cataToDisplay as $keyCata => $value): ?>
				<tr>

					<?php for ($i=0; $i <count($cataToDisplay[$keyCata]) ; $i++):?>
						<td><?= str_replace("﻿SGSPFCAT.","",$cataToDisplay[$keyCata][$i])?>&nbsp;&nbsp;&nbsp;&nbsp;   </td>

					<?php endfor ?>
				</tr>

			<?php endforeach ?>
		</tbody>
	</table>
	<p></p>
	<p></p>

	<table class="table table-sm">
		<tbody>
			<?php foreach ($dossierToDisplay as $keyDossier => $value): ?>
				<tr>

					<?php for ($i=0; $i <count($dossierToDisplay[$keyDossier]) ; $i++):?>
						<td><?= str_replace("﻿SGSPFCAT.","",$dossierToDisplay[$keyDossier][$i])?>&nbsp;&nbsp;&nbsp;&nbsp;   </td>

					<?php endfor ?>
				</tr>

			<?php endforeach ?>
		</tbody>
	</table>
	<p></p>
	<p></p>



	<table class="table table-sm">
		<tbody>
			<?php foreach ($opToDisplay as $keyOp => $value): ?>
				<tr>

					<?php for ($i=0; $i <count($opToDisplay[$keyOp]) ; $i++):?>
						<td><?= str_replace("﻿SGSPFCAT.","",$opToDisplay[$keyOp][$i])?>&nbsp;&nbsp;&nbsp;&nbsp;   </td>

					<?php endfor ?>
				</tr>

			<?php endforeach ?>
		</tbody>
	</table>
	<p></p>
	<p></p>

Commandes
		<table class="table table-sm">
		<tbody>
			<?php foreach ($cdeToDisplay as $keyCde => $value): ?>
				<tr>

					<?php for ($i=0; $i <count($cdeToDisplay[$keyCde]) ; $i++):?>
						<td><?= str_replace("﻿SGSPFCAT.","",$cdeToDisplay[$keyCde][$i])?>&nbsp;&nbsp;&nbsp;&nbsp;   </td>

					<?php endfor ?>
				</tr>

			<?php endforeach ?>
		</tbody>
	</table>
	<p></p>
	<p></p>

entete
		<table class="table table-sm">
		<tbody>
			<?php foreach ($enteteToDisplay as $keyCde => $value): ?>
				<tr>

					<?php for ($i=0; $i <count($enteteToDisplay[$keyCde]) ; $i++):?>
						<td><?= str_replace("﻿SGSPFCAT.","",$enteteToDisplay[$keyCde][$i])?>&nbsp;&nbsp;&nbsp;&nbsp;   </td>

					<?php endfor ?>
				</tr>

			<?php endforeach ?>
		</tbody>
	</table>
	<p></p>
	<p></p>
</body>
</html>