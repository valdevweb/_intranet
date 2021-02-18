<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'config/db-connect.php';

// penser à ajouter au batch le drop table fournisseurs.fournisseurs et la réinsertion
// INSERT INTO _fournisseurs.fournisseurs (id, fournisseur) SELECT cnuf, qlik.fournisseurs.fournisseur FROM `fournisseurs` GROUP BY cnuf
// chemin+ nom des 3 fichiers
$fouFile=DIR_IMPORT_GESSICA."SCEBFADR.csv";
// $fouFile=DIR_IMPORT_GESSICA."test.csv";

$row=0;
function notEmptyQlikFou($pdoQlik){
	$req=$pdoQlik->query("SELECT * FROM fournisseurs");
	$datas=$req->fetch();
	if(empty($datas)){
		return false;
	}
	return true;
}

if (($handle = fopen($fouFile, "r")) !== FALSE) {
	$errArr=[];

	$req=$pdoQlik->query("DELETE FROM fournisseurs");
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		if($row==0){
			$row++;
		}else{
			// if($data[2]==1){
			$req=$pdoQlik->prepare("INSERT INTO fournisseurs( fournisseur, cnuf, cif, interlocuteur, ad1, ad2, cp, ville, pays, tel, fax, mobile, email, adrcod, majcod, adrcif, date_import) VALUES
				( :fournisseur, :cnuf, :cif, :interlocuteur, :ad1, :ad2, :cp, :ville, :pays, :tel, :fax, :mobile, :email, :adrcod, :majcod, :adrcif, :date_import)");
			$req->execute([
				':fournisseur'	=>$data[3],
				':cnuf'	=>$data[1],
				':cif'	=>$data[2],
				':interlocuteur'	=>$data[4],
				':ad1'	=>$data[5],
				':ad2'	=>$data[6],
				':cp'	=>$data[7],
				':ville'	=>$data[8],
				':pays'	=>$data[9],
				':tel'	=>$data[10],
				':fax'	=>$data[11],
				':mobile'	=>$data[26],
				':email'	=>$data[27],
				':adrcod'	=>$data[0],
				':majcod'	=>$data[18],
				':adrcif'	=>$data[2],
					':date_import'	=>date('Y-m-d')

			]);


			$err=$req->errorInfo();
			if($err[1]!= "" || $err[2]!=""){
				echo $row;
				echo "<pre>";
				print_r($err);
				echo '</pre>';
			}

			// }



		}
		$row++;
	}
	$row=0;
	fclose($handle);
}
function import($pdoFou){
	$req=$pdoFou->query("INSERT INTO fournisseurs (id, fournisseur, date_import)  SELECT cnuf, fournisseur, date_import FROM qlik.fournisseurs WHERE `majcod`<>3 AND `adrcod`=0 AND adrcif=0 GROUP BY cnuf");
	$data=$req->rowCount();
	return $data;
}
if(notEmptyQlikFou($pdoQlik)){
	$req=$pdoFou->query("DELETE FROM fournisseurs");

	$data=import($pdoFou);



}else{
	echo "vide";
}

