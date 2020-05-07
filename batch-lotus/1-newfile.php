<?php

if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
	$manuel=true;

}
else{
	set_include_path("D:\www\intranet\btlecest\\");
	$manuel=false;

}

include 'config\config.inc.php';
include 'functions\tasklog.fn.php';

function addNewFile($pdoMag, $newFile){
	$req=$pdoMag->prepare("INSERT INTO lotus_imports (date_import, file) VALUES (:date_import, :file)");
	$req->execute([
		':date_import'		=>date('Y-m-d H:i:s'),
		':file'				=>$newFile,

	]);
	// return $req->errorInfo();
	return $pdoMag->lastInsertId();

}




function getDataFromFile($contents,$searchCriteria){
	$pattern = preg_quote($searchCriteria, '/');
	$pattern = "/^.*$pattern.*\$/m";
	preg_match_all($pattern, $contents, $matches);
	return $matches[0];
}

function copyToOld($pdoMag){
	$req=$pdoMag->query("DELETE FROM lotus_ld_old");
	$req=$pdoMag->query("INSERT INTO lotus_ld_old SELECT * FROM lotus_ld");

}

function cleanActual($pdoMag){
	$req=$pdoMag->query("DELETE FROM lotus_ld");

}

function getBtlec($pdoMag,$ldName){
	$req=$pdoMag->prepare("SELECT btlec_sca as btlec, deno_sca as deno, racine_list FROM sca3 WHERE racine_list=:racine_list");
	$req->execute([
		':racine_list'		=>$ldName
	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);
	if(!empty($data)){
		return $data;
	}


	return false;
}
function formatArrayNomListe($nomListesDiffu,$pdoMag){
	for($i=0;$i<count($nomListesDiffu);$i++){
		$nomListesDiffu[$i]=str_replace('ListName:','',$nomListesDiffu[$i]);
		$ldFull=trim($nomListesDiffu[$i]);
		$suffixe=substr($ldFull,strlen($ldFull)-4,4);
		$ldShort=substr($ldFull,0,strlen($ldFull)-4);
		$btlec=getBtlec($pdoMag,$ldShort);

		$nomLdClean[$i]['ld_full']=$ldFull;
		$nomLdClean[$i]['suffixe']=$suffixe;
		$nomLdClean[$i]['ld_short']=$ldShort;
		if($btlec){
			$nomLdClean[$i]['btlec']=$btlec['btlec'];
		}else{
			$nomLdClean[$i]['btlec']='';
		}
	}
	return $nomLdClean;
}

function formatArrayContenuListe($contenuListeDiffu){
	for($i=0;$i<count($contenuListeDiffu);$i++){
		$contenuListeDiffu[$i]=str_replace('Members:','',$contenuListeDiffu[$i]);
		$contenuListeDiffu[$i]=str_replace('CN=','',$contenuListeDiffu[$i]);
		$contenuListeDiffu[$i]=str_replace('OU=','',$contenuListeDiffu[$i]);
		$contenuListeDiffu[$i]=str_replace('O=','',$contenuListeDiffu[$i]);
		$contenuListeDiffu[$i]=str_replace('C=','',$contenuListeDiffu[$i]);
		$contenuLdClean[$i]=utf8_encode($contenuListeDiffu[$i]);
	}
	return $contenuLdClean;
}

function addToExtraction($pdoMag,$idImport, $ldFull, $ldShort,$suffixe, $idLd,$i,$contenu, $btlec, $type){
	$req=$pdoMag->prepare("INSERT INTO lotus_extraction (id_import, ld_full, ld_short, suffixe, ld_id, array_i, contenu, btlec, type) VALUES (:id_import, :ld_full, :ld_short, :suffixe, :ld_id, :array_i, :contenu, :btlec, :type)");
	$req->execute([
		':id_import' => $idImport,
		':ld_full'	=> $ldFull,
		':ld_short' => $ldShort,
		':suffixe'   => $suffixe,
		':ld_id'		=>$idLd,
		':array_i'		=>$i,
		':contenu'		=>$contenu,
		':btlec'		=>$btlec,
		':type'		=>$type

	]);
	$err=$req->errorInfo();
	if(empty($err[2])){
		return true;
	}
	return false;
}

$taskErrors=[];
$newFile='';



// 1- vérif si fichier à la date du jour
$lotusFileList = scandir(DIR_LOTUS_CSV);
foreach ($lotusFileList as $filename){
// récup la date de dépot du fichier
	if($filename!='.' && $filename!='..'){
		$fileDate=date ('Y-m-d H:i:s', filemtime(DIR_LOTUS_CSV.'\\'.$filename));
		$fileDate=new DateTimeImmutable($fileDate);
		$today=new DateTime();
		if($fileDate->format('Y-m-d') == (new DateTime())->format('Y-m-d')){
			if($fileDate->format('Y-m-d') == $today->format('Y-m-d')){
				$newFile=$filename;
			}
		}

	}

}

if(empty($newFile)){
	echo 'rien';
	exit;
}elseif($manuel){
	$newFile='LOTUS_20200505.txt';
}else{
	echo $newFile;
}
// 2- ouvre le fichier
$file=DIR_LOTUS_CSV."\\".$newFile;
$fn = fopen($file,"rw");
if(!$fn){
	echo "impossible de faire le traitement, le fichier ne peut pas être lu";
	exit();
}
// 3- recup les lignes listName et Membres
$contents = file_get_contents($file);
$nomListesDiffu=getDataFromFile($contents,'ListName:');
//     [0] => ListName:  WINTZEDIS-RBT
    // [1] => ListName:  WINTZEDIS-DIR
$contenuListeDiffu=getDataFromFile($contents,'Members:');
// [158] => Members:  eric.morlier@lecasud.fr
// [159] => Members:  CN=Culturel Sodimaz/OU=Sodimaz/OU=socamil/OU=btlec/O=e-leclerc/C=fr,CN=David Santoul/OU=Sodimaz/OU=socamil/OU=btlec/O=e-leclerc/C=fr
// echo "<pre>";
// print_r($nomListesDiffu);
// print_r($contenuListeDiffu);
// echo '</pre>';


if(count($contenuListeDiffu)!=count($nomListesDiffu)){
	echo "WARNING on ne peut pas traiter, envoyer un mail";
	exit();
}
if($manuel){
	$lastinsertId=51;
}else{
	$lastinsertId=addNewFile($pdoMag, $newFile);
}
echo $lastinsertId;
echo "<br>";
$nomListesDiffu=formatArrayNomListe($nomListesDiffu,$pdoMag);
    // [0] => Array
    //     (
    //         [ld_full] => WINTZEDIS-RBT
    //         [suffixe] => -RBT
    //         [ld_short] => WINTZEDIS
    //         [btlec] => 1468
    //     )

    // [1] => Array
$contenuListeDiffu=formatArrayContenuListe($contenuListeDiffu);
   // [16] =>   Directeur VIRYDIS/virydis/scadif/btlec/e-leclerc/fr
    // [17] =>   Thierry JODET/virydis/scadif/btlec/e-leclerc/fr
echo "<pre>";
print_r($nomListesDiffu);

echo '</pre>';



/*
1er parcours d
 */

for ($idLd=0; $idLd <count($contenuListeDiffu) ; $idLd++) {
	// le cas ou la liste est vide
	if(empty(trim($contenuListeDiffu[$idLd]))){
		// inserer nom ld avec code erreur vide (4)
		$type="vide";
		$videExt=addToExtraction($pdoMag,$lastinsertId,$nomListesDiffu[$idLd]['ld_full'],$nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, 1,'', $nomListesDiffu[$idLd]['btlec'], $type);
			if(!$videExt){
				$taskErrors[][$idLd]['type']="lotus";
				$taskErrors[][$idLd]['ld_full']=$nomListesDiffu[$idLd]['ld_full'];
				$taskErrors[][$idLd]['contenu']=trim($singleMail[$i]);
			}
		// $one=insertEmail($pdoMag, $lastinsertId, $nomListesDiffu[$idLd]['ld_full'], $nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, '', '', $nomListesDiffu[$idLd]['btlec'], $codeErr);
		echo $nomListesDiffu[$idLd]['ld_full'] . ' contenu '. $contenuListeDiffu[$idLd];
		echo "<br>";

	}else{
		$arrOfMails[$idLd]=explode(',',$contenuListeDiffu[$idLd]);
	}
}

foreach ($arrOfMails as $idLd => $singleMail) {

	for ($i=0; $i < count($arrOfMails[$idLd]) ; $i++) {
		// si on a une /, on a forcement une adresse lotus
		if(strpos($singleMail[$i],'/')){
			$type="lotus";
			$lotusExt=addToExtraction($pdoMag,$lastinsertId,$nomListesDiffu[$idLd]['ld_full'],$nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, $i,trim($singleMail[$i]), $nomListesDiffu[$idLd]['btlec'], $type);
			if(!$lotusExt){
				$taskErrors[$i][$idLd]['type']="lotus";
				$taskErrors[$i][$idLd]['ld_full']=$nomListesDiffu[$idLd]['ld_full'];
				$taskErrors[$i][$idLd]['contenu']=trim($singleMail[$i]);
			}
			// echo $idLd.' LOTUS ' . trim($singleMail[$i]) .'<br>';
		}elseif(strpos($singleMail[$i],'@')){
			$type="email";
			$emailExt=addToExtraction($pdoMag,$lastinsertId,$nomListesDiffu[$idLd]['ld_full'],$nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, $i,trim($singleMail[$i]), $nomListesDiffu[$idLd]['btlec'], $type);
			if(!$emailExt){
				$taskErrors[$i][$idLd]['type']="lotus";
				$taskErrors[$i][$idLd]['ld_full']=$nomListesDiffu[$idLd]['ld_full'];
				$taskErrors[$i][$idLd]['contenu']=trim($singleMail[$i]);
			}
			// echo $idLd.' EMAIL ' . trim($singleMail[$i]) .'<br>';

		}else{
			$type='ld';
			$ldExt=addToExtraction($pdoMag,$lastinsertId,$nomListesDiffu[$idLd]['ld_full'],$nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, $i,trim($singleMail[$i]), $nomListesDiffu[$idLd]['btlec'], $type);
			if(!$ldExt){
				$taskErrors[$i][$idLd]['type']="lotus";
				$taskErrors[$i][$idLd]['ld_full']=$nomListesDiffu[$idLd]['ld_full'];
				$taskErrors[$i][$idLd]['contenu']=trim($singleMail[$i]);
			}
			// $anotherLd[$idLd][]=trim($singleMail[$i]);
				// addToExtraction($pdoMag,$idLd,$i,trim($singleMail[$i]),$nomListesDiffu[$idLd]['ld_full'],2);
			echo $idLd.' LD ' . trim($singleMail[$i]) .'<br>';
		}

	}

}

if(count($taskErrors)!=0){
	echo "mauvais";
		echo "<pre>";
		print_r($taskErrors);
		echo '</pre>';

}else{
	echo 'tout s\'est bein passé';
}