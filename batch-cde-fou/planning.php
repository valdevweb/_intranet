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

$planning=DIR_IMPORT_GESSICA."SCEFFPLR.csv";



$row=0;
if (($handle = fopen($planning, "r")) !== FALSE) {
	$errArr=[];
	$req=$pdoQlik->query("DELETE FROM planning");

	// $req=$pdoQlik->query("DELETE FROM cdes_fou_details_new");
	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if($row==0){
			$row++;
		}else{
			if(($data[6])!=0 && ($data[6]!=1)){

		$req=$pdoQlik->prepare("INSERT INTO planning
					(date_recep, id_cde, date_import)
					VALUES
					(:date_recep, :id_cde, :date_import)");
				$req->execute([
					':date_recep'	=>convertToDate($data[2]),
					':id_cde'	=>$data[6],
					':date_import'	=>date('Y-m-d H:i:s'),


				]);


				$err=$req->errorInfo();
				echo "<pre>";
				print_r($err);
				echo '</pre>';

			}




		}
		$row++;
	}
	$row=0;
	fclose($handle);
}else{
	echo "not found";
}



