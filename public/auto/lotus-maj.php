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


function getToImport($pdoUser, $sens){
	$req=$pdoUser->prepare("SELECT * FROM lotus_histo WHERE added= :added AND cm=0");
	$req->execute([
		':added'=>$sens
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function deleteEntries($pdoCm,$email, $ldFull){
	$req=$pdoCm->prepare("DELETE FROM mag_email WHERE email= :email AND ld_full = :ld_full");
	$req->execute([
		':ld_full'	=>$ldFull,
		':email'	=>$email

	]);
	return $req->rowCount();
}


function addEntries($pdoCm,$email, $ldFull, $galec){

	$suffixe=substr($ldFull,strlen($ldFull)-4,4);
	$ldShort=substr($ldFull,0,strlen($ldFull)-4);

	$req=$pdoCm->prepare("INSERT INTO mag_email (ld_full, ld_short, ld_suffixe, email, galec) VALUES (:ld_full, :ld_short, :ld_suffixe, :email, :galec)");
	$req->execute([
		':ld_full'	=>$ldFull,
		':ld_short'	=>$ldShort,
		':ld_suffixe'=>$suffixe,
		':email'	=>$email,
		':galec'	=>$galec

	]);
	return $req->rowCount();
	// return $req->errorInfo();
}


function majHisto($pdoUser, $idHisto){
	$req=$pdoUser->prepare("UPDATE lotus_histo SET cm=1 WHERE id= :id");
	$req->execute([
		':id'		=>$idHisto
	]);
	return $req->rowCount();
}

$toAdd=getToImport($pdoUser,1);

$toDelete=getToImport($pdoUser,0);

foreach ($toDelete as $key => $value) {
	$row=deleteEntries($pdoCm, $value['email'], $value['ld_full']);

	if($row==1){
		majHisto($pdoUser, $value['id']);
		echo "sup " .$value['email'];

	}else{
		echo "impossible de supprimer " .$value['email'];
		echo "<br>";
	}
}




foreach ($toAdd as $key => $value) {

	$row=addEntries($pdoCm, $value['email'], $value['ld_full'], $value['galec']);

	if($row==1){
		majHisto($pdoUser, $value['id']);
		echo "add " .$value['email'];

	}else{
		echo "impossible d'ajouter " .$value['email'];
		echo "<br>";
	}
}
