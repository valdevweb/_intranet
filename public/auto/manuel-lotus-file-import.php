<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config\config.inc.php';
	function getDataFromFile($contents,$searchCriteria){
		$pattern = preg_quote($searchCriteria, '/');
		$pattern = "/^.*$pattern.*\$/m";
		preg_match_all($pattern, $contents, $matches);
		return $matches[0];
	}

	function formatArrayNomListe($nomListesDiffu,$pdoMag){
		for($i=0;$i<count($nomListesDiffu);$i++){
			$nomListesDiffu[$i]=str_replace('ListName:','',$nomListesDiffu[$i]);
			$ldFull=trim($nomListesDiffu[$i]);
			$suffixe=substr($ldFull,strlen($ldFull)-4,4);
			$ldShort=substr($ldFull,0,strlen($ldFull)-4);
			$galec=getGalec($pdoMag,$ldShort);

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

	function insertEmail($pdoMag, $id_import, $ld_full, $ld_short, $ld_suffixe, $id_import_ld, $email, $lotus, $galec, $errors ){
		$req=$pdoMag->prepare("INSERT INTO lotus_ld (id_import, ld_full, ld_short, ld_suffixe, id_import_ld, email, lotus, galec, errors) VALUES (:id_import, :ld_full, :ld_short, :ld_suffixe, :id_import_ld, :email, :lotus, :galec, :errors)");
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

	function copyToOld($pdoMag){
		$req=$pdoMag->query("DELETE FROM lotus_ld_old");
		$req=$pdoMag->query("INSERT INTO lotus_ld_old SELECT * FROM lotus_ld");

	}

	function cleanActual($pdoMag){
		$req=$pdoMag->query("DELETE FROM lotus_ld");

	}


	function addToExtraction($pdoMag,$idLd,$i,$contenu,$ldName,$type){
		$req=$pdoMag->prepare("INSERT INTO lotus_extraction( ld_id, array_i, contenu, ld, type) VALUES (:ld_id, :array_i, :contenu, :ld, :type)");
		$req->execute([
			':ld_id'		=>$idLd,
			':array_i'		=>$i,
			':contenu'		=>$contenu,
			':ld'		=>$ldName,
			':type'		=>$type

		]);
	}
$newFile="LOTUS_20200505.txt";

// OUVERTURE FICHIER
	$file=DIR_LOTUS_CSV."\\".$newFile;
	$fn = fopen($file,"rw");
	$contents = file_get_contents($file);

// copy de la base ld_mag vers la base ld_mag_old
	if(!$fn){
		echo "impossible de faire le traitement, le fichier ne peut pas être lu";
		exit();
	}


	$nomListesDiffu=getDataFromFile($contents,'ListName:');
	$contenuListeDiffu=getDataFromFile($contents,'Members:');


	if(count($contenuListeDiffu)!=count($nomListesDiffu)){
		echo "WARNING on ne peut pas traiter, envoyer un mail";
		exit();
	}

	// copyToOld($pdoMag);
	// cleanActual($pdoMag);

	// $lastinsertId=addNewFile($pdoMag, $newFile);


// $lastinsertId=99;
	$nomListesDiffu=formatArrayNomListe($nomListesDiffu,$pdoMag);
	$contenuListeDiffu=formatArrayContenuListe($contenuListeDiffu);


	for ($idLd=0; $idLd <count($contenuListeDiffu) ; $idLd++) {
	// le cas ou la liste est vide
		if(empty(trim($contenuListeDiffu[$idLd]))){

		// inserer nom ld avec code erreur vide (4)
			// $one=insertEmail($pdoMag, $lastinsertId, $nomListesDiffu[$idLd]['ld_full'], $nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, '', '', $nomListesDiffu[$idLd]['galec'], 4);
		}else{
			$arrOfMails[$idLd]=explode(',',$contenuListeDiffu[$idLd]);
			// addToExtraction($pdoMag,$idLd,$i,trim($singleMail[$i]),$nomListesDiffu[$idLd]['ld_full'],0);

		}
	}



	foreach ($arrOfMails as $idLd => $singleMail) {
		for ($i=0; $i < count($arrOfMails[$idLd]) ; $i++) {
		// si on a une /, on a forcement une adresse lotus
			if(strpos($singleMail[$i],'/')){
				$lotusList[$idLd][]=trim($singleMail[$i]);
				// echo $idLd.' LOTUS ' . trim($singleMail[$i]) .'<br>';
			}elseif(strpos($singleMail[$i],'@')){
			// si arobase, on a une adresse mail
				$validEmail[$idLd][]=trim($singleMail[$i]);
				// echo $idLd.' EMAIL ' . trim($singleMail[$i]) .'<br>';

			}else{
				$anotherLd[$idLd][]=trim($singleMail[$i]);
					// echo $idLd.' LD ' . trim($singleMail[$i]) .'<br>';
			}
		}
	}


exit;

	$lotusCon=ldap_connect('217.0.222.26',389);
	$ldaptree    = "OU=galec,o=e-leclerc,c=fr";
	$ldapuser="ADMIN_BTLEC";
	$lpappass="toronto";
	$ldapbind = ldap_bind($lotusCon, $ldapuser, $lpappass) or die ("Error trying to bind: ".ldap_error($ldapbind));
	$justThese = array( "mail","displayname");


// traitement du tableau email valids
	foreach ($validEmail as $idLd => $email) {
		for ($i=0; $i < count($validEmail[$idLd]); $i++) {
			$name=explode('@',$lotusList[$idLd][$i]);
			$name=trim($name[0]);
			$result=ldap_search($lotusCon, $ldaptree, "(CN=*".$name."*)",$justThese);
			$data = ldap_get_entries($lotusCon, $result);
			if(count($data)>1){
				$codeErr=0;
				$email=$data[0]['mail'][0];
				echo ' ok dès le début '.$email . ' '.$nomListesDiffu[$idLd]['ld_full'];
				// $one=insertEmail($pdoMag, $lastinsertId, $nomListesDiffu[$idLd]['ld_full'], $nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, $validEmail[$idLd][$i], '', $nomListesDiffu[$idLd]['galec'],0);
			}else{
				$codeErr=2;
				// $one=insertEmail($pdoMag, $lastinsertId, $nomListesDiffu[$idLd]['ld_full'], $nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, '', $email, $nomListesDiffu[$idLd]['galec'],$codeErr);

			}

		}
	}




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
						// $one=insertEmail($pdoMag, $lastinsertId, $nomListesDiffu[$idLd]['ld_full'], $nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, '', $email, $nomListesDiffu[$idLd]['galec'],$codeErr);
						// if(!$one){
						// 	$taskErrors[]="erreur insertion mail avec code erreur ".$codeErr ." sur liste diffu ".$nomListesDiffu[$idLd]['ld_full'];
						// }

					}else{
						$codeErr=0;
						// $one=insertEmail($pdoMag, $lastinsertId, $nomListesDiffu[$idLd]['ld_full'], $nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, $email, '', $nomListesDiffu[$idLd]['galec'],$codeErr);
						// if(!$one){
						// 	$taskErrors[]="erreur insertion mail avec code erreur ".$codeErr ." sur liste diffu ".$nomListesDiffu[$idLd]['ld_full'];
						// }

					}
				}else{
			// correspondance non trouvée
					$codeErr=2;
					// $one=insertEmail($pdoMag, $lastinsertId, $nomListesDiffu[$idLd]['ld_full'], $nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd, '', $lotusList[$idLd][$i], $nomListesDiffu[$idLd]['galec'],2);
					// if(!$one){
					// 	$taskErrors[]="erreur insertion mail avec code erreur ".$codeErr ." sur liste diffu ".$nomListesDiffu[$idLd]['ld_full'];
					// }


				}
			}
		}
	}
	foreach ($anotherLd as $idLd => $ld) {
		$codeErr=6;
		for ($i=0; $i <count($anotherLd[$idLd]) ; $i++) {
			// $one=insertEmail($pdoMag, $lastinsertId, $nomListesDiffu[$idLd]['ld_full'], $nomListesDiffu[$idLd]['ld_short'], $nomListesDiffu[$idLd]['suffixe'], $idLd,'' , $anotherLd[$idLd][$i], $nomListesDiffu[$idLd]['galec'],6);
			// if(!$one){
			// 	$taskErrors[]="erreur insertion mail avec code erreur ".$codeErr ." sur liste diffu ".$nomListesDiffu[$idLd]['ld_full'];
			// }
			echo $nomListesDiffu[$idLd]['ld_full'] . ' ' . $idLd. ' ' . $anotherLd[$idLd][$i];
			echo "<br>";

		}
	}



	fclose($fn);
	ldap_close($lotusCon);


