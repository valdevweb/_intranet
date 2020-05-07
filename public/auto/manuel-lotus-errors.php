<?php


function getMissingGalec($pdoMag){
	$req=$pdoMag->query("SELECT * FROM lotus_ld WHERE galec='' AND (ld_full NOT LIKE '%PILOTAGE%' AND ld_full NOT LIKE '%VINGT%' AND ld_full NOT LIKE '%CHMOU%' AND ld_full NOT LIKE '%CM-GAILLAC%'  AND ld_full NOT LIKE '%TASKADMIN%')");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getErrorList($pdoMag,$codeErr){
	$req=$pdoMag->prepare("SELECT * FROM lotus_ld WHERE errors = :errors ORDER BY errors, ld_full");
	$req->execute([
		':errors'		=>$codeErr
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);

}

function getIncludedLd($pdoMag,$ldfull){
	$req=$pdoMag->prepare("SELECT * FROM lotus_ld WHERE ld_full= :ld_full");
	$req->execute([
		':ld_full'	=>$ldfull
	]);
	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($data)){
		return false;
	}
	return $data;
}
function addMail($pdoMag, $id_import,$ld_full, $ld_short,$ld_suffixe, $id_import_ld, $email,$lotus, $galec){
	$req=$pdoMag->prepare("INSERT INTO lotus_ld (id_import, ld_full, ld_short, ld_suffixe, id_import_ld, email, lotus, galec, errors) VALUES (:id_import, :ld_full, :ld_short, :ld_suffixe, :id_import_ld, :email, :lotus, :galec, :errors)");
	$req->execute([
		':id_import'	=>$id_import,
		':ld_full'		=>$ld_full,
		':ld_short'		=>$ld_short,
		':ld_suffixe'		=>$ld_suffixe,
		':id_import_ld'		=>$id_import_ld,
		':email'		=>$email,
		':lotus'		=>$lotus,
		':galec'		=>$galec,
		':errors'		=>'',
	]);
	return $req->errorInfo();
	// return $req->rowCount();
}
function deleteLine($pdoMag,$id){
	$req=$pdoMag->prepare("DELETE FROM lotus_ld WHERE id= :id");
	$req->execute([
		':id'	=>$id
	]);
	return $req->rowCount();
}

function updateErrorCode($pdoMag,$id, $errCode,$detail){
	$req=$pdoMag->prepare("UPDATE lotus_ld SET errors= :errors, error_detail= :error_detail WHERE id= :id");
	$req->execute([
		':id'			=>$id,
		':errors'		=>$errCode,
		':error_detail'	=>$detail
	]);
	return $req->rowCount();
}

function updateMail($pdoMag,$id, $email){
	$req=$pdoMag->prepare("UPDATE lotus_ld SET errors= 0, email= :email WHERE id= :id");
	$req->execute([
		':id'			=>$id,
		':email'		=>$email,
	]);
	return $req->rowCount();
}

function updateErrorCodeAndMail($pdoMag,$id, $errCode,$detail,$lotus){
	$req=$pdoMag->prepare("UPDATE lotus_ld SET errors= :errors, error_detail= :error_detail, email= :email,lotus= :lotus WHERE id= :id");
	$req->execute([
		':id'			=>$id,
		':email'		=>'',
		':lotus'		=>$lotus,
		':errors'		=>$errCode,
		':error_detail'	=>$detail
	]);
	return $req->rowCount();
}


$galecList=getMissingGalec($pdoMag);






$decompte=1;
// 2 = pas de correspondance lotus/email
// 4 =liste vide
// 5 = renvoie sur une adresse lotus
// 6 =renvoie sur une ld
// 9 =renvoie sur une ld
// 7=renvoie vers une liste de diffus qui n'existe pas
// 8 renvoie vers une ld vide

$noMatch=getErrorList($pdoMag,2);
$emptyLd=getErrorList($pdoMag,4);
$lotusAgain=getErrorList($pdoMag,5);
$ldAgain=getErrorList($pdoMag,6);






foreach ($lotusAgain as $key => $lotus) {
	$lotusCon=ldap_connect('217.0.222.26',389);
	$ldaptree    = "OU=galec,o=e-leclerc,c=fr";
	$ldapuser="ADMIN_BTLEC";
	$lpappass="toronto";
	$ldapbind = ldap_bind($lotusCon, $ldapuser, $lpappass) or die ("Error trying to bind: ".ldap_error($ldapbind));
	$justThese = array( "mail","displayname");
	if($ldapbind){
		// on récupère le nom de la personne dans l'adresse lotus (1er partie de l'adresse et on reccherche son nom dans le carnet d'adresse)
		$name=explode('/',$lotus['lotus']);
		$name=trim($name[0]);
		$result=ldap_search($lotusCon, $ldaptree, "(CN=*".$name."*)",$justThese);
		$data = ldap_get_entries($lotusCon, $result);
		// correspondace trouvée
		if(count($data)>1){
			$email=$data[0]['mail'][0];

			echo "mise à jour de l'id ". $lotus['id'] .' avec l\'adresse '.$email .' a la place de '.$lotus['lotus'];
		}else{
			echo "pas de lotus trouvé pour " .$lotus['email'].'<br><br>';
			$detail='';

		}

	}
}


