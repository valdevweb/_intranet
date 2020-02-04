<?php
if (preg_match('/_btlecest/', dirname(__FILE__)))
{
	define("VERSION",'_');
}
else
{
	define("VERSION",'');
}

function connectToDb($dbname) {
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
$dbCm=VERSION."cm";
$pdoCm=connectToDb($dbCm);
$pdoUser=connectToDb('web_users');
function addNewFile($pdoUser, $newFile){
	$req=$pdoUser->prepare("INSERT INTO lotus_imports (date_import, file) VALUES (:date_import, :file)");
	$req->execute([
		':date_import'		=>date('Y-m-d H:i:s'),
		':file'				=>$newFile,

	]);
	// return $req->errorInfo();
	return $pdoUser->lastInsertId();

}


function getGalec($pdoUser,$ldName){
	$req=$pdoUser->prepare("SELECT galec,deno,lotus FROM mag WHERE lotus=:lotus");
	$req->execute([
		':lotus'		=>$ldName
	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);
	if(!empty($data)){
		return $data;
	}


	return false;
}




// $lastImport=getLastImport($pdoUser);

 //  faire un systeme quotidien qui vérife si nouveau fichier à l'emplacement prévu

//1- récup date der fichier importé dans db lotus_import ou verfi si fichier à la date du jour
//2- vérifier si fichier avec date sup à celui de  la db
//3- si oui inserer en db
//

/*----------------------------------------------------------------------------------------------
FICHIER A TRAITER ? => newFile

------------------------------------------------------------------------------------------------ */
$lotusDir="D:\btlec\lotus";
$lotusFileList = scandir($lotusDir);
$newFile='';
// vérif si fichier à la date du jour
foreach ($lotusFileList as $filename){
// récup la date de dépot du fichier
	if($filename!='.' && $filename!='..'){
		$fileDate=date ('Y-m-d H:i:s', filemtime($lotusDir.'\\'.$filename));
		$fileDate=new DateTimeImmutable($fileDate);
		$today=new DateTime();
		// if($fileDate->format('Y-m-d') == (new DateTime("2020-01-30"))->format('Y-m-d')){
		if($fileDate->format('Y-m-d') == $today->format('Y-m-d')){
			$newFile=$filename;
		}
	}

}


/*----------------------------------------------------------------------------------------------
TRAITEMENT NOUVEAU FICHIER

----------------------------------------------------------------------------------------------- */
// $newFile="lotus.txt";


if(!empty($newFile)){
	require('lotus-file-import.php');
	require('lotus-errors.php');
	// require('lotus-compare.php');
// echo $newFile;
}





