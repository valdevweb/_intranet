<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'config/db-connect.php';



// chemin+ nom des 3 fichiers
$enteteFile=DIR_IMPORT_GESSICA."SCEFFCFE.csv";
// $detailFile=DIR_IMPORT_GESSICA."test.csv";
$detailFile=DIR_IMPORT_GESSICA."SCEFFCFL.csv";

$row=0;
// SELECT *, count(id) FROM `cdes_fou_details_new`group by compare  ORDER BY `count(id)`  desc

if (($handle = fopen($detailFile, "r")) !== FALSE) {
	$errArr=[];
	$nbInsert=0;

	$req=$pdoQlik->query("DELETE FROM cdes_fou_details");
	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if($row==0){
			$row++;
		}else{
			if($data[0]!=0 && $data[51]!=3 && $data[1]==1){
				echo $row;
				echo "<br>";
				$nbInsert++;
				echo $data[51];
				if($data[13]!=0){
					$qteUv=$data[7]*$data[13];

				}else{
					$qteUv=$data[7];
				}

				$req=$pdoQlik->prepare("INSERT INTO cdes_fou_details
					(id, id_cde, id_detail,  id_artdos, num_cde, article, dossier, qte_cde, cond_carton, qte_uv_cde, qte_grp, date_import )
					VALUES
					(:id, :id_cde, :id_detail,  :id_artdos, :num_cde, :article, :dossier, :qte_cde, :cond_carton, :qte_uv_cde, :qte_grp, :date_import)");
				$req->execute([
					':id'	=>$data[0].$data[2],
					':id_cde'	=>$data[0],
					':id_detail'	=>$data[0].$data[2],
					':id_artdos'	=>$data[5].$data[4],
					':num_cde'	=>$data[0],
					':article'	=>$data[5],
					':dossier'	=>$data[4],
					':qte_cde'	=>$data[7],
					':cond_carton'	=>$data[13],
					':qte_uv_cde'		=>$qteUv,
					':qte_grp'		=>$data[57],
					':date_import'	=>date('Y-m-d')


				]);


				$err=$req->errorInfo();




			}

		}
		$row++;
	}
	$row=0;
	fclose($handle);
	$pdoQlik->query("OPTIMIZE TABLE cdes_fou_details ");
}else{
	echo "not found";
}



echo "NOMBRE " .$nbInsert;