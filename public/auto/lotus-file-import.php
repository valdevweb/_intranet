<?php

function getDataFromFile($contents,$searchCriteria){
	$pattern = preg_quote($searchCriteria, '/');
	$pattern = "/^.*$pattern.*\$/m";
	preg_match_all($pattern, $contents, $matches);
	return $matches[0];
}

function formatArrayNomListe($nomListesDiffu,$pdoUser){
	for($i=0;$i<count($nomListesDiffu);$i++){
		$nomListesDiffu[$i]=str_replace('ListName:','',$nomListesDiffu[$i]);
		$ldFull=trim($nomListesDiffu[$i]);
		$suffixe=substr($ldFull,strlen($ldFull)-4,4);
		$ldShort=substr($ldFull,0,strlen($ldFull)-4);
		$galec=getGalec($pdoUser,$ldShort);

		$nomLdClean[$i]['ld_full']=$ldFull;
		$nomLdClean[$i]['suffixe']=$suffixe;
		$nomLdClean[$i]['ld_short']=$ldShort;
		if($galec){
			$nomLdClean[$i]['galec']=$galec['galec'];
		}else{
			$nomLdClean[$i]['galec']='';
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

function insertEmail($pdoUser, $id_import, $ld_full, $ld_short, $ld_suffixe, $id_import_ld, $email, $lotus, $galec, $errors ){
	$req=$pdoUser->prepare("INSERT INTO mag_ld (id_import, ld_full, ld_short, ld_suffixe, id_import_ld, email, lotus, galec, errors) VALUES (:id_import, :ld_full, :ld_short, :ld_suffixe, :id_import_ld, :email, :lotus, :galec, :errors)");
	$req->execute([
		':id_import'		=>$id_import,
		':ld_full'		=>$ld_full,
		':ld_short'		=>$ld_short,
		':ld_suffixe'		=>$ld_suffixe,
		':id_import_ld'		=>$id_import_ld,
		':email'		=>$email,
		':lotus'		=>$lotus,
		':galec'		=>$galec,
		':errors'		=>$errors,

	]);
	$error=$req->errorInfo();
	if(!empty($error[2])){
		return false;
	}
	return true;
}

function copyToOld($pdoUser){
	$req=$pdoUser->query("DELETE FROM mag_ld_old");
	$req=$pdoUser->query("INSERT INTO mag_ld_old SELECT * FROM mag_ld");

}

function cleanActual($pdoUser){
	$req=$pdoUser->query("DELETE FROM mag_ld");

}


// OUVERTURE FICHIER
$file=$lotusDir."\\".$newFile;
$fn = fopen($file,"rw");
$contents = file_get_contents($file);

// copy de la base ld_mag vers la base ld_mag_old
if(!$fn){
	echo "impossible de faire le traitement, le fichier ne peut pas être lu";
exit();
}
copyToOld($pdoUser);
cleanActual($pdoUser);

$nomListesDiffu=getDataFromFile($contents,'ListName:');
$contenuListeDiffu=getDataFromFile($contents,'Members:');

if(count($contenuListeDiffu)!=count($nomListesDiffu)){
	echo "WARNING on ne peut pas traiter, envoyer un mail";
	exit();
}



$lastinsertId=addNewFile($pdoUser, $newFile);
// $lastinsertId=99;
$nomListesDiffu=formatArrayNomListe($nomListesDiffu,$pdoUser);
$contenuListeDiffu=formatArrayContenuListe($contenuListeDiffu);


for ($idLd=0; $idLd <count($contenuListeDiffu) ; $idLd++) {
	// le cas ou la liste est vide
	if(empty(trim($contenuListeDiffu[$idLd]))){
		// inserer nom ld avec code erreur vide (4)
		$one=insertEmail($pdoUser, $lastinsertId, $nomListesDiffu[$idLd]['ld_full'], $nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, '', '', $nomListesDiffu[$idLd]['galec'], 4);
	}else{
		$arrOfMails[$idLd]=explode(',',$contenuListeDiffu[$idLd]);
	}
}



foreach ($arrOfMails as $idLd => $singleMail) {

	for ($i=0; $i < count($arrOfMails[$idLd]) ; $i++) {
		// si on a une /, on a forcement une adresse lotus
		if(strpos($singleMail[$i],'/')){
			$lotusList[$idLd][]=trim($singleMail[$i]);
		}elseif(strpos($singleMail[$i],'@')){
			// si arobase, on a une adresse mail
			$validEmail[$idLd][]=trim($singleMail[$i]);
		}else{
			$anotherLd[$idLd][]=trim($singleMail[$i]);
		}

	}

}




// traitement du tableau email valids
foreach ($validEmail as $idLd => $email) {
	for ($i=0; $i < count($validEmail[$idLd]); $i++) {

		$one=insertEmail($pdoUser, $lastinsertId, $nomListesDiffu[$idLd]['ld_full'], $nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, $validEmail[$idLd][$i], '', $nomListesDiffu[$idLd]['galec'],0);
		if(!$one){
			$errors[]="erreur à l'insertion de ld avec mail vide";
		}
	}


}


$lotusCon=ldap_connect('217.0.222.26',389);
$ldaptree    = "OU=galec,o=e-leclerc,c=fr";
$ldapuser="ADMIN_BTLEC";
$lpappass="toronto";
$ldapbind = ldap_bind($lotusCon, $ldapuser, $lpappass) or die ("Error trying to bind: ".ldap_error($ldapbind));
$justThese = array( "mail","displayname");

foreach ($lotusList as $idLd => $lotus) {
	if($ldapbind){
		for ($i=0; $i < count($lotusList[$idLd]); $i++) {
		// on récupère le nom de la personne dans l'adresse lotus (1er partie de l'adresse et on reccherche son nom dans le carnet d'adresse)
			$name=explode('/',$lotusList[$idLd][$i]);
			$name=trim($name[0]);
			$result=ldap_search($lotusCon, $ldaptree, "(CN=*".$name."*)",$justThese);
			$data = ldap_get_entries($lotusCon, $result);
		// correspondace trouvée
			if(count($data)>1){
				$email=$data[0]['mail'][0];
			// certaines adrsse lotus renvoie d'autre adresse lotus.......
				$anotherLotus=explode('/',$email);
				if(count($anotherLotus)>1){
					$codeErr=5;
					$one=insertEmail($pdoUser, $lastinsertId, $nomListesDiffu[$idLd]['ld_full'], $nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, '', $email, $nomListesDiffu[$idLd]['galec'],$codeErr);

				}else{
					$codeErr=0;
					$one=insertEmail($pdoUser, $lastinsertId, $nomListesDiffu[$idLd]['ld_full'], $nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, $email, '', $nomListesDiffu[$idLd]['galec'],$codeErr);

				}
			}else{
			// correspondance non trouvée
				$one=insertEmail($pdoUser, $lastinsertId, $nomListesDiffu[$idLd]['ld_full'], $nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, '', $lotusList[$idLd][$i], $nomListesDiffu[$idLd]['galec'],2);


			}
		}
	}
}
foreach ($anotherLd as $idLd => $ld) {
	for ($i=0; $i <count($anotherLd[$idLd]) ; $i++) {
		$one=insertEmail($pdoUser, $lastinsertId, $nomListesDiffu[$idLd]['ld_full'], $nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd,'' , $anotherLd[$idLd][$i], $nomListesDiffu[$idLd]['galec'],6);

	}
}



fclose($fn);
ldap_close($lotusCon);


