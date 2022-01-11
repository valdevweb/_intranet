<?php

if (preg_match('/_btlecest/', dirname(__FILE__))) {
	set_include_path("D:\www\_intranet\_btlecest\\");
	$manuel = true;
} else {
	set_include_path("D:\www\intranet\btlecest\\");
	$manuel = false;
}


include 'config/config.inc.php';
include 'config/db-connect.php';
include 'functions/tasklog.fn.php';

function undoneImport($pdoMag)
{
	$req = $pdoMag->query("SELECT * FROM lotus_imports WHERE done=0");
	$data = $req->fetchAll(PDO::FETCH_ASSOC);
	if (empty($data)) {
		return false;
	} elseif (count($data) > 1) {
		// prévoir envoi mail
		return false;
	} else {
		return $data;
	}
}

function getExtraction($pdoMag, $id, $type)
{
	$req = $pdoMag->prepare("SELECT * FROM lotus_extraction WHERE id_import= :id_import AND type= :type");
	$req->execute([
		':type'		=> $type,
		':id_import' => $id
	]);
	$datas = $req->fetchAll(PDO::FETCH_ASSOC);
	if (empty($datas)) {
		return "";
	}
	return $datas;
}
function eraseLdLotus($pdoMag)
{
	$req = $pdoMag->query("DELETE FROM lotus_ld");
}

function insertEmail($pdoMag, $id_import, $ld_full, $ld_short, $ld_suffixe, $id_import_ld, $idExtraction, $email, $lotus, $btlec, $galec, $errors)
{
	$req = $pdoMag->prepare("INSERT INTO lotus_ld (id_import, ld_full, ld_short, ld_suffixe, id_import_ld, id_extraction, email, lotus, btlec, galec, errors) VALUES (:id_import, :ld_full, :ld_short, :ld_suffixe, :id_import_ld, :id_extraction, :email, :lotus, :btlec, :galec, :errors)");
	$req->execute([
		':id_import'		=> $id_import,
		':ld_full'		=> $ld_full,
		':ld_short'		=> $ld_short,
		':ld_suffixe'		=> $ld_suffixe,
		':id_import_ld'		=> $id_import_ld,
		':id_extraction'	=> $idExtraction,
		':email'		=> $email,
		':lotus'		=> $lotus,
		':btlec'		=> $btlec,
		':galec'		=> $galec,
		':errors'		=> $errors,

	]);
	$error = $req->errorInfo();
	if (!empty($error[2])) {
		return false;
	}
	return true;
}

function returnNameFromLdap($ldapResult, $searchString)
{

	$email = "";
	// on trouve une correspondance unique dans ldap
	if ($ldapResult['count'] == 1) {
		// on a une adresse d redirection
		if (isset($ldapResult[0]['mailaddress'])) {
			if (!str_contains($ldapResult[0]['mailaddress'][0], '/') && str_contains($ldapResult[0]['mailaddress'][0], '.leclerc')) {
				$email = $ldapResult[0]['mailaddress'][0];
			}
		}
		if ($email == "" && !strpos($ldapResult[0]['mail'][0], '/')) {
			$email = $ldapResult[0]['mail'][0];
		}
	} else {

		echo "cas plusieurs correspondances";
		// plusieurs correspondances trouvées
		for ($i = 0; $i < count($ldapResult) - 1; $i++) {
			// on compare l'adresse lotus des entrées trouvée à l'adresse lotus que l'on cherche
			if ($searchString == $ldapResult[$i]['displayname'][0]) {
				if (isset($ldapResult[$i]['mailaddress'])) {
					if (str_contains($ldapResult[$i]['mailaddress'][0], '.leclerc')) {
						$email = $ldapResult[$i]['mailaddress'][0];
						$codeErr = 0;
					}
				} elseif (!strpos($ldapResult[$i]['mail'][0], '/')) {
					// si on n'a pas à nouveau une adresse lotus c'est bon
					$email = $ldapResult[$i]['mail'][0];
				}
			}
		}
	}
	return $email;
}



function getNomFromLotusString($lotusString)
{
	$name = explode('/', $lotusString);
	$name = trim($name[0]);
	return $name;
}

function getNomFromEmail($emailToValidate)
{
	$name = explode('@', $emailToValidate);
	$name = trim($name[0]);
	return $name;
}


/* ------------------------------------
*				Initialisation
---------------------------------------	*/
$added = 0;

$lotusCon = ldap_connect('217.0.222.26', 389);
$ldaptree    = "OU=galec,o=e-leclerc,c=fr";
$ldapuser = "ADMIN_BTLEC";
$lpappass = "toronto";
$ldapbind = ldap_bind($lotusCon, $ldapuser, $lpappass) or die("Error trying to bind: " . ldap_error($ldapbind));
$justThese = array("mail", "displayname", "mailaddress");


/* ------------------------------------
*				traitement
---------------------------------------	*/


// regarde si import non traité => done à 0
$newData = undoneImport($pdoMag);


if (!$newData) {
	echo "no data";
	exit;
}


// si on a un import à traiter, on récupère les extractions par catégories de traitement
$empty = getExtraction($pdoMag, $newData[0]['id'], "vide");
$lotus = getExtraction($pdoMag, $newData[0]['id'], "lotus");
$emailDb = getExtraction($pdoMag, $newData[0]['id'], "email");
$ld = getExtraction($pdoMag, $newData[0]['id'], "ld");
// echo "<pre>";
// print_r($empty);
// echo '</pre>';


// echo "<pre>";
// print_r($lotus);
// echo '</pre>';





eraseLdLotus($pdoMag);


if (!empty($lotus)) {
	foreach ($lotus as $key => $extraction) {
		$searchString = $extraction['contenu'];
		$searchName = getNomFromLotusString($extraction['contenu']);
		$result = ldap_search($lotusCon, $ldaptree, "(CN=*" . $searchName . "*)", $justThese);
		$ldapResult = ldap_get_entries($lotusCon, $result);
		echo "<pre>";
		print_r($ldapResult);
		echo '</pre>';


		if ($ldapResult['count'] == 0) {
			$codeErr = 4;
			$email = "";
		} else {
			$codeErr = 0;
			$email = returnNameFromLdap($ldapResult, $searchString);
			echo $email;
		}
		if ($email != "") {
			insertEmail($pdoMag, $newData[0]['id'], $extraction['ld_full'], $extraction['ld_short'], $extraction['suffixe'], $extraction['ld_id'], $extraction['id'], $email, '', $extraction['btlec'], $extraction['galec'], $codeErr);
		}
	}
}

// if(!empty($empty)){
// 	foreach ($empty as $key => $extraction) {
// 		$codeErr=4;
// insertEmail($pdoMag, $newData[0]['id'], $extraction['ld_full'], $extraction['ld_short'], $extraction['suffixe'], $extraction['ld_id'],$extraction['id'], '', '', $extraction['btlec'], $extraction['galec'], $codeErr);

// 	}
// }





if (!empty($emailDb)) {
	foreach ($emailDb as $key => $extraction) {
		$emailToValidate = $extraction['contenu'];
		// si on a une adresse en .leclerc, elle n'est pad dans ldpa donc on ne vérifié pas
		if (strpos($emailToValidate, '.leclerc')) {
			$codeErr = 0;
			$email = $emailToValidate;
			insertEmail($pdoMag, $newData[0]['id'], $extraction['ld_full'], $extraction['ld_short'], $extraction['suffixe'], $extraction['ld_id'], $extraction['id'], $email, '', $extraction['btlec'], $extraction['galec'], $codeErr);
		} else {
			$searchName = getNomFromEmail($emailToValidate);
			$result = ldap_search($lotusCon, $ldaptree, "(mail=" . $searchName . "*)", $justThese);
			$ldapResult = ldap_get_entries($lotusCon, $result);
			// correspondace trouvée


			if (count($ldapResult) > 1) {
				$codeErr = 0;
				$email = $ldapResult[0]['mail'][0];
				insertEmail($pdoMag, $newData[0]['id'], $extraction['ld_full'], $extraction['ld_short'], $extraction['suffixe'], $extraction['ld_id'], $extraction['id'], $email, '', $extraction['btlec'], $extraction['galec'], $codeErr);
			} else {

				// adresse mail non trouvée
				// $codeErr=9;
				// insertEmail($pdoMag, $newData[0]['id'], $extraction['ld_full'], $extraction['ld_short'], $extraction['suffixe'], $extraction['ld_id'], $extraction['id'], '', $emailToValidate, $extraction['btlec'], $extraction['galec'], $codeErr);

			}
		}
	}
}



// if(!empty($ld)){
// 	foreach ($ld as $key => $extraction) {
// $codeErr=9;
// $one=insertEmail($pdoMag, $newData[0]['id'], $extraction['ld_full'], $extraction['ld_short'], $extraction['suffixe'], $extraction['ld_id'], $extraction['id'],'', $extraction['contenu'], $extraction['btlec'], $extraction['galec'], $codeErr);


// 	}
// }

echo "nb adresses ajoutées " . $added;
