<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config\config.inc.php';



// chemin+ nom des 3 fichiers
$enteteFile=DIR_IMPORT_GESSICA."SCEFFCFE.csv";
// $detailFile=DIR_IMPORT_GESSICA."test.csv";
$detailFile=DIR_IMPORT_GESSICA."SCEFFCFL.csv";

$row=0;


if (($handle = fopen($detailFile, "r")) !== FALSE) {
	$errArr=[];

	$req=$pdoQlik->query("DELETE FROM cdes_fou_details");
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		if($row==0){
			$row++;
		}else{
			if($data[0]!=0){

				$req=$pdoQlik->prepare("INSERT INTO cdes_fou_details (id_cde, num_cde, num_liv, article, dossier, qte_cde, cond_carton, uv_cde, date_import )
					VALUES (:id_cde, :num_cde, :num_liv, :article, :dossier, :qte_cde, :cond_carton, :uv_cde, :date_import)");
				$req->execute([
					':id_cde'	=>$data[0].$data[1],
					':num_cde'	=>$data[0],
					':num_liv'	=>$data[1],
					':article'	=>$data[5],
					':dossier'	=>$data[4],
					':qte_cde'	=>$data[7],
					':cond_carton'	=>$data[13],
					':uv_cde'	=>$data[8],
					':date_import'	=>date('Y-m-d')


				]);


				$err=$req->errorInfo();
				if($err[1]!= "" || $err[2]!=""){
					// echo $row;
					// echo "<pre>";
					// print_r($err);
					// echo '</pre>';
				}

			}

		}
		$row++;
	}
	$row=0;
	fclose($handle);
}