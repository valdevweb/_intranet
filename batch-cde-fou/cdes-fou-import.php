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
$enteteFile=DIR_IMPORT_GESSICA."SCEFFCFE.csv";
// $detailFile=DIR_IMPORT_GESSICA."test.csv";
$detailFile=DIR_IMPORT_GESSICA."SCEFFCFL.csv";

$row=0;


// (id, MAG_LIB, MAG_LIBR, MAG_MAI, MAG_PAN, MAG_ANCPAN, MAG_SCA, MAG_BAS, MAG_TYPINF, CRE_OPE, CRE_DATE, CRE_HEURE, MAJ_OPE, MAJ_DATE, MAJ_HEURE, MAJ_COD, MAG_LIVSCA, MAG_PANGAL, date_import


if (($handle = fopen($enteteFile, "r")) !== FALSE) {
	$errArr=[];

	$req=$pdoQlik->query("DELETE FROM cdes_fou");
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		if($row==0){
			$row++;
		}else{

			if($data[1]==1 && $data[15]!=3){
				$req=$pdoQlik->prepare("INSERT INTO cdes_fou (id, num_cde, cnuf, gt, date_cde, date_liv, nb_palettes, date_import)
					VALUES (:id, :num_cde, :cnuf, :gt, :date_cde, :date_liv, :nb_palettes, :date_import)");
				$req->execute([
					':id'	=>$data[0],
					':num_cde'	=>$data[0],
					':cnuf'	=>$data[2],
					':gt'	=>$data[4],
					':date_cde'	=>(!empty($data[5]))?convertTodate($data[5]):null,
					':date_liv'	=>(!empty($data[16]))?convertTodate($data[16]):null,
					':nb_palettes'	=>$data[12],
					':date_import'	=>date('Y-m-d')

				]);
			}

		}
		$row++;
	}
	$row=0;
	fclose($handle);
}




