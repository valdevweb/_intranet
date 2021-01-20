<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config\config.inc.php';



// chemin+ nom des 3 fichiers
$fouFile="D:\documents\p_litiges_fou\import2.csv";
// $fouFile=DIR_IMPORT_GESSICA."test.csv";

$row=0;


if (($handle = fopen($fouFile, "r")) !== FALSE) {
	$errArr=[];

	// $req=$pdoQlik->query("DELETE FROM mag_ctbt");
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		if($row==0){
			$row++;
		}else{
				$req=$pdoUser->prepare("INSERT INTO fournisseur_users(id, fullname, cnuf, gt, email, id_type, info) VALUES
					 (:id, :fullname, :cnuf, :gt, :email, :id_type, :info)");
				$req->execute([
					':id'	=>$row,
					 ':fullname'	=>$data[2],
					 ':cnuf'	=>$data[1],
					 ':gt'	=>$data[0],
					 ':email'	=>$data[3],
					 ':id_type'	=>$data[4],
					 ':info'	=>$data[5]
				]);


				$err=$req->errorInfo();
				if($err[1]!= "" || $err[2]!=""){
					echo $row;
					echo "<pre>";
					print_r($err);
					echo '</pre>';
				}
				// echo "<pre>";
				// print_r($data);
				// echo '</pre>';


		}
		$row++;
	}
	$row=0;
	fclose($handle);
}