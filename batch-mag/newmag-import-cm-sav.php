<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config\config.inc.php';
include 'functions\tasklog.fn.php';
include 'batch-mag\utils.fn.php';


function getMagAttribution($pdoUser){
	$req=$pdoUser->query("SELECT * FROM mag_attribution");
	return $req->fetchAll(PDO::FETCH_GROUP);
}

function updateAttr($pdoMag,$key,$data){
	$req=$pdoMag->prepare("UPDATE mag SET id_cm_intern= :id_cm_intern, id_cm_web_user= :id_cm_web_user, date_update= :date_update WHERE galec= :galec");
	$req->execute([
		':id_cm_intern'		=>$data['id_intern'],
		':id_cm_web_user'	=>$data['id_web_user'],
		':date_update'		=>date('Y-m-d H:i:s'),
		':galec'			=>$key
	]);
		$done=$req->rowCount();
	if($done==1){
		return $done;
	}else{
		return $req->errorInfo();
	}
}

function getSavCorrespondance($pdoMag,$sav){
	$req=$pdoMag->prepare("SELECT id from corresp_sav WHERE sav= :sav");
	$req->execute([
		':sav'		=>$sav
	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);
	if(!empty($data)){
		return $data['id'];
	}
	return NULL;
}

function updateSav($pdoMag,$pole, $antenne,$galec){
	$req=$pdoMag->prepare("UPDATE mag SET pole_sav= :pole_sav, antenne= :antenne, date_update= :date_update WHERE galec= :galec");
	$req->execute([
		':pole_sav'		=>$pole,
		':antenne'		=>$antenne,
		':date_update'	=>date('Y-m-d H:i:s'),
		':galec'		=>$galec

	]);
	$done=$req->rowCount();
	if($done==1){
		return $done;
	}else{
		return $req->errorInfo();
	}

}
function notInNew($pdoMag){
	//  ce qui est dans la nouvelle db qui ne l'était pas dans l'ancienne
	$req=$pdoMag->query("SELECT * FROM mag t1 RIGHT OUTER JOIN sca3 t2 ON t1.id = t2.btlec_sca WHERE t1.id IS NULL  AND t2.galec_sca!=''");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function insertInNew($pdoMag, $data, $centrale){
	$req=$pdoMag->prepare("INSERT INTO mag (id, galec, centrale, ad1, ad2, ville, cp, deno, absent, date_insert) VALUES (:id, :galec, :centrale, :ad1, :ad2, :ville, :cp, :deno, :absent, :date_insert)");
	$req->execute([
		':id'	=>$data['btlec_sca'],
		':galec'	=>$data['galec_sca'],
		':centrale'	=>$centrale,
		':ad1'	=>$data['ad1_sca'],
		':ad2'	=>$data['ad2_sca'],
		':ville'	=>$data['city'],
		':cp'	=>$data['cp_sca'],
		':deno'	=>$data['deno_sca'],
		':absent'	=>1,
		':date_insert'	=>date('Y-m-d H:i:s')
	]);
	$done=$req->rowCount();
	if($done==1){
		return $done;
	}else{
		return $req->errorInfo();
	}


}


$cmAttr=getMagAttribution($pdoUser);
$mags=getMagSav($pdoSav);
$absents=notInNew($pdoMag);
$centraleList=getCentrales($pdoMag);

$updated=0;
$updatedSav=0;
$insertedMag=0;
$errorsSav=[];
$errorsCm=[];
$errorsNew=[];


foreach ($cmAttr as $key => $cm) {
	$updated=updateAttr($pdoMag, $key, $cm[0]);
	if($updated==1){
		$updated++;
	}else{



		echo "erreur cm";
echo "<br>";

	}
}
foreach ($mags as $key => $mag) {
	$poleSav=getSavCorrespondance($pdoMag,trim($mag['sav']));
	$antenne=getSavCorrespondance($pdoMag,trim($mag['pole']));
	$updateSav=updateSav($pdoMag, $poleSav, $antenne, $mag['galec']);
	if($updateSav==1){
		$updatedSav++;
	}else{
				echo "erreur sav";
echo "<br>";


		$errorsSav[]=$mag['galec'];

	}

}
foreach ($absents as $key => $abs) {
	$centrale=convertCentrale($abs['centrale_sca'], $centraleList);
	if($centrale==''){
		$centrale=NULL;
	}
	$newInsert=insertInNew($pdoMag, $abs, $centrale);
	if($newInsert==1){
		$insertedMag++;
	}else{


		echo "erreur mag sca3 to mag";
echo "<br>";

	}
}



echo "mise à jour cm : " .$updated;
echo "<br>";
echo "mise à jour sav : " .$updatedSav;
echo "<br>";
echo "ajout mag sca3 : " .$insertedMag;
	echo "<pre>";
	print_r($errorsSav);
	echo '</pre>';
// Array
// (
//     [0] => 1668
//     [1] => 6771
//     [2] => 0968B
//     [3] => BBJRA
//     [4] => 0367B
//     [5] => BBJBA
//     [6] => 0215
// )
// comparer mag et sca3
//
//    [0] => 6772
    // [1] => 6771
    // [2] => 1654		idem
    // [3] => 1754
    // [4] => 9843		pas noram
    // [5] => 1113
    // [6] => 7301
    // [7] => 1734	idme