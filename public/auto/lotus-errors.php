<?php
// require('../../config/autoload.php');


function getMissingGalec($pdoUser){
	$req=$pdoUser->query("SELECT * FROM mag_ld WHERE galec='' AND (ld_full NOT LIKE '%PILOTAGE%' AND ld_full NOT LIKE '%VINGT%' AND ld_full NOT LIKE '%CHMOU%' AND ld_full NOT LIKE '%CM-GAILLAC%'  AND ld_full NOT LIKE '%TASKADMIN%')");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getErrorList($pdoUser,$codeErr){
	$req=$pdoUser->prepare("SELECT * FROM mag_ld WHERE errors = :errors ORDER BY errors, ld_full");
	$req->execute([
		':errors'		=>$codeErr
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);

}

function getIncludedLd($pdoUser,$ldfull){
	$req=$pdoUser->prepare("SELECT * FROM mag_ld WHERE ld_full= :ld_full");
	$req->execute([
		':ld_full'	=>$ldfull
	]);
	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($data)){
		return false;
	}
	return $data;
}
function addMail($pdoUser, $id_import,$ld_full, $ld_short,$ld_suffixe, $id_import_ld, $email,$lotus, $galec){
	$req=$pdoUser->prepare("INSERT INTO mag_ld (id_import, ld_full, ld_short, ld_suffixe, id_import_ld, email, lotus, galec, errors) VALUES (:id_import, :ld_full, :ld_short, :ld_suffixe, :id_import_ld, :email, :lotus, :galec, :errors)");
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
function deleteLine($pdoUser,$id){
	$req=$pdoUser->prepare("DELETE FROM mag_ld WHERE id= :id");
	$req->execute([
		':id'	=>$id
	]);
	return $req->rowCount();
}

function updateErrorCode($pdoUser,$id, $errCode,$detail){
	$req=$pdoUser->prepare("UPDATE mag_ld SET errors= :errors, error_detail= :error_detail WHERE id= :id");
	$req->execute([
		':id'			=>$id,
		':errors'		=>$errCode,
		':error_detail'	=>$detail
	]);
	return $req->rowCount();
}

function updateMail($pdoUser,$id, $email){
	$req=$pdoUser->prepare("UPDATE mag_ld SET errors= 0, email= :email WHERE id= :id");
	$req->execute([
		':id'			=>$id,
		':email'		=>$email,
	]);
	return $req->rowCount();
}

function updateErrorCodeAndMail($pdoUser,$id, $errCode,$detail,$lotus){
	$req=$pdoUser->prepare("UPDATE mag_ld SET errors= :errors, error_detail= :error_detail, email= :email,lotus= :lotus WHERE id= :id");
	$req->execute([
		':id'			=>$id,
		':email'		=>'',
		':lotus'		=>$lotus,
		':errors'		=>$errCode,
		':error_detail'	=>$detail
	]);
	return $req->rowCount();
}


$galecList=getMissingGalec($pdoUser);




$ldW='';
$listGalecMail='<ul>';
if(!empty($galecList)){
	// mail avertissement
	"Sujet : import listes diffu - panonceau galec absents";
	"Durant l'import des listes de diffusion, certaines listes de diffusion n'ont par pu être rapprochées d'un code galec. Ci dessous les listes de diffusiuon concernées";
	foreach ($galecList as $key => $ld) {
		if($ldW != $ld['ld_short']){
			$listGalecMail='<li>'.$ld['ld_short'].'</li>';
			$ldW=$ld['ld_short'];
		}else{
			$ldW=$ld['ld_short'];

		}

	}
}
$listGalecMail.='</ul>';

$decompte=1;
// 2 = pas de correspondance lotus/email
// 4 =liste vide
// 5 = renvoie sur une adresse lotus
// 6 =renvoie sur une ld
// 9 =renvoie sur une ld
$noMatch=getErrorList($pdoUser,2);
$emptyLd=getErrorList($pdoUser,4);
$lotusAgain=getErrorList($pdoUser,5);
$ldAgain=getErrorList($pdoUser,6);

foreach ($ldAgain as $key => $ld) {
	$linkedLd=getIncludedLd($pdoUser,trim($ld['lotus']));
	if(!$linkedLd){
		// faire un code erreur ld inexistante => 7 renvoie vers une ld inexistante
		echo "ld non trouvée" .$ld['lotus'].'<br><br>';
		$detail="la liste ".$ld['ld_full'].' renvoie vers la liste '.$ld['lotus'].'.Celle-ci n\'a pas été trouvée ';
		updateErrorCode($pdoUser,$ld['id'], 7,$detail);
		echo $detail;
	}else{
		foreach ($linkedLd as $key => $found) {
					// peut renvoyer plusieur email
			if(trim($found['email'])!=''){
						// ajout de l'adresse mail :
				$done[$ld['id']]=addMail($pdoUser, $ld['id_import'],$ld['ld_full'], $ld['ld_short'],$ld['ld_suffixe'],$ld['id_import_ld'], $found['email'],$ld['lotus'], $ld['galec']);
				echo $found['email'].' ajouté pour '.$ld['ld_full'].' id '.$ld['id'];
				echo "<br>";

							// delete l'actuelle ligne
			}else{
						//renvoie vers une ld vide
						//faire un code erreur ld vide
				echo "autre ld vide" .$ld['lotus'].'<br><br>';
				$detail="la liste ".$ld['ld_full'].' renvoie vers la liste '.$ld['lotus'].'.Cette liste est vide ';
				updateErrorCode($pdoUser,$ld['id'], 8,$detail);
				echo $detail;
			}
		}
		foreach ($done as $key => $value) {
			deleteLine($pdoUser,$key);
			echo $key .'supprimée<br>';
		}

	}
}


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
			updateMail($pdoUser,$lotus['id'], $email);
			echo "mise à jour de l'id ". $lotus['id'] .' avec l\'adresse '.$email .' a la place de '.$lotus['lotus'];
		}else{
			echo "pas de lotus trouvé pour " .$lotus['email'].'<br><br>';
			$detail='';
			updateErrorCodeAndMail($pdoUser,$lotus['id'], 2,$detail,$lotus['lotus']);
			// updateErrorCode($pdoUser,$lotus['id'], 9,$detail);

		}

	}
}


