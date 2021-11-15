<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'config/db-connect.php';


function connectToDbDev($dbname) {
	$host='localhost';
	$username='sql';
	$pwd='User19092017+';
	try {
		$pdo=new PDO("mysql:host=$host;dbname=$dbname", $username, $pwd);

	}
	catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
	}
	return  $pdo;
}

// $pdoQlik=connectToDbDev('_qlik');





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
$row=0;
$previousYear=new DateTime("last year");
$previousYear=$previousYear->format('y');
echo $previousYear;
/*

BESOIN

de la table opértion SCEBFOPR
- 5 :maj.datcre pour ne prendre que les op année en cours et mins1
- 0 : opr-cod pour les codes op
- 1 opr-lib : nom op
- 2 opr-datdeb : date deb op
- 2 opr-datfin : date fin op


de la table dossier
- 4 : opr cod pour jointure avec l'autre
- 0 pour code dossier
- 37 sln pour code cata



 */




if (($handle = fopen($op, "r")) !== FALSE) {
	$errArr=[];
	$i=0;
	$req=$pdoQlik->query("DELETE FROM cata_op");

	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if($row==0){
			$entete=$data;
		}

		if($row==0){
			$row++;
		}else{
			if (substr($data[0],0,2)>=$previousYear) {
				$req=$pdoQlik->prepare("INSERT INTO cata_op (code_op, libelle, date_start, date_end, origine) VALUES (:code_op, :libelle, :date_start, :date_end, :origine) ");
				$req->execute([
					':code_op'		=>$data[0],
					':libelle'		=>$data[1],
					':date_start'		=>convertToDate($data[2]),
					':date_end'		=>convertToDate($data[3]),
					':origine'		=>substr($data[0],2,1)
				]);
			}

		}
		$row++;
	}
	$row=0;
	fclose($handle);
}
				echo "<br>";
//
echo $previousYear;
				echo "<br>";

if (($handle = fopen($dossier, "r")) !== FALSE) {
	$errArr=[];
	$i=0;
	$req=$pdoQlik->query("DELETE FROM cata_dossiers");

	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if($row==0){
			$entete=$data;
		}

		if($row==0){
			$row++;
		}else{
			// if($data[37]!="" && substr($data[4],0,2)>=$previousYear){
			if( $data[4]!=""){
				if(!empty($data[37])){
					$cata=substr($data[37],-5,5);
				}else{
					$cata=null;
				}
				echo  "CODE OP ". $data[4] ." CODE DOSSIER".$data['0'];
				echo "<br>";

				$req=$pdoQlik->prepare("INSERT INTO cata_dossiers (code_op, dossier, cata) VALUES (:code_op, :dossier, :cata) ");
				$req->execute([
					':code_op'		=>$data[4],
					':dossier'		=>$data[0],
					':cata'		=>$cata,
				]);

				$err=$req->errorInfo();
				if(!empty($err[1])){
					echo "<pre>";
					print_r($err);
					echo '</pre>';
				}




			}else{
				// echo "NON ".$data[4];
				// echo "<br>";
			}

		}
		$row++;
	}
	$row=0;
	fclose($handle);
}

