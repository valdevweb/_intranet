<?php

$ldList=[];
$mailList=[];
$galecNotFound=[];
$missingPerson=[];
$ldEmpty=[];

// OUVERTURE FICHIER
$file=$lotusDir."\\".$newFile;
$fn = fopen($file,"rw");
$contents = file_get_contents($file);
// RECHERCHE NOM LISTES DE DIFFU =>				$arrListName
$searchListName='ListName:';
$patternListName = preg_quote($searchListName, '/');
$patternListName = "/^.*$patternListName.*\$/m";
preg_match_all($patternListName, $contents, $matches);
$arrListName=$matches;



// RECHERCHE LISTES ADRESSES MAIL 				$arrMembers
$searchMembers='Members:';
$patternMembers = preg_quote($searchMembers, '/');
$patternMembers = "/^.*$patternMembers.*\$/m";
preg_match_all($patternMembers, $contents, $matches);
$arrMembers=$matches;




// si on n'a pas la même taille de tableau (membre et nom ld), on a un soucis !!!!
// => mail d'avertissement
if(count($arrMembers)!=count($arrListName)){
	echo "WARNING on ne peut pas traiter, envoyer un mail";
}
else{
/*----------------------------------------------------------------------------------------------
	AJOUT ENTREE DANS BASE IMPORT
	------------------------------------------------------------------------------------------------ */
//ajoute le nom du fichier à la table import
$lastinsertId=addNewFile($pdoUser, $fileDate, $newFile);
	// $lastinsertId=3;
/*----------------------------------------------------------------------------------------------
	CONNEXION AU SERVEUR LOTUS
	------------------------------------------------------------------------------------------------ */
	$lotusCon=ldap_connect('217.0.222.26',389);
	$ldaptree    = "OU=galec,o=e-leclerc,c=fr";
	$ldapuser="ADMIN_BTLEC";
	$lpappass="toronto";
	$justThese = array( "mail","displayname");
	if ($lotusCon) {
		$ldapbind = ldap_bind($lotusCon, $ldapuser, $lpappass) or die ("Error trying to bind: ".ldap_error($ldapbind));
	}
/*----------------------------------------------------------------------------------------------
	NETTOYAGE TABLEAU arrMembers
	------------------------------------------------------------------------------------------------ */
	for($i=0;$i<count($arrMembers[0]);$i++){
		$arrMembers[0][$i]=str_replace('Members:','',$arrMembers[0][$i]);
		$arrMembers[0][$i]=str_replace('CN=','',$arrMembers[0][$i]);
		$arrMembers[0][$i]=str_replace('OU=','',$arrMembers[0][$i]);
		$arrMembers[0][$i]=str_replace('O=','',$arrMembers[0][$i]);
		$arrMembers[0][$i]=str_replace('C=','',$arrMembers[0][$i]);
		$mailList[$i]=utf8_encode($arrMembers[0][$i]);
	}


/*----------------------------------------------------------------------------------------------
	NETTOYAGE TABLEAU arrListName
	ET CREA TABLEAU ldList avec
	- nom ld entier
	- suffixe (adh, rbt, dir)
	- nom cours (= nom mag)
	- pano galec
	ET CREA TABLEAU PANO GALEC NON TROUVES $galecNotFound avec le nom de la ld
	------------------------------------------------------------------------------------------------ */
	for($i=0;$i<count($arrListName[0]);$i++){
		$arrListName[0][$i]=str_replace('ListName:','',$arrListName[0][$i]);
		$ldFull=trim($arrListName[0][$i]);
		$suffixe=substr($ldFull,strlen($ldFull)-4,4);

		$ldShort=substr($ldFull,0,strlen($ldFull)-4);
		$galec=getGalec($pdoUser,$ldShort);
		$ldList[$i]['ld_full']=$ldFull;
		$ldList[$i]['suffixe']=$suffixe;
		$ldList[$i]['ld_short']=$ldShort;
		if($galec){
			$ldList[$i]['galec']=$galec['galec'];
		}else{
			$ldList[$i]['galec']='';
			$galecNotFound=$ldFull;
		}



		$mails=explode(',',$mailList[$i]);
		for($j=0;$j<count($mails);$j++){
			if(!empty($mails[$j])){
				// !strpos($mails[$j],'@') &&
				// l'adresse mail a des /, c'est une adresse lotus (nb certaines adresses lotus on des @)
				if(strpos($mails[$j],'/')){
					// l'adresse est est elle une adresse de type lotus
					echo $mails[$j] .'<br>';
					exit();
					$person=explode('/',$mails[$j]);
					// si on n'a pas d'adresse lotus
					if(count($person)==1){
						// echo "pas d'adresse lotus pour ".$person[0].$mailList[$i];
						$ldList[$i]['lotus'][$j]=$mails[$j];
						$ldList[$i]['mail'][$j]='';
						$ldList[$i]['error'][$j]=1;
						$missingPerson[$i][$mailList[$i]]=$person[0];
					}
					else{
						// recupère la correspondance email
						if($ldapbind){
							$persontrim=trim($person[0]);
							// if(empty($persontrim)){
							// 	$persontrim=$person[0];
							// }
							$result=ldap_search($lotusCon, $ldaptree, "(CN=*".$persontrim."*)",$justThese);
							$data = ldap_get_entries($lotusCon, $result);
							if(count($data)>1){
								$email=$data[0]['mail'][0];
								$ldList[$i]['lotus'][$j]=$mails[$j];
								$ldList[$i]['mail'][$j]=$email;
								$ldList[$i]['error'][$j]='';
							}else{
								// echo "correspondance email non trouvée ".$person[0].$mailList[$i];
								$ldList[$i]['error'][$j]=2;
								$ldList[$i]['lotus'][$j]=$mails[$j];
								$ldList[$i]['mail'][$j]='';
								$missingPerson[$i][$mailList[$i]]=$person[0];
							}
						}
					}

				}else{
					$ldList[$i]['error'][$j]='';

					//adresse email directement
					if(!strpos($mails[$j],'@')){
						$ldList[$i]['error'][$j]=3;
					}
					$ldEmpty[]=$mails[$j];
					$ldList[$i]['lotus'][$j]=$mails[$j];
					$ldList[$i]['mail'][$j]='';


				}

			}else{
				echo "liste de diffu vide<br>";
				$ldEmpty[]=$mails[$j];
				$ldList[$i]['lotus'][0]='';
				$ldList[$i]['mail'][0]='';
				$ldList[$i]['error'][0]=4;
			}

		}


	}
}
if(count($ldList)>1){
	$insertSuccess=true;

	foreach ($ldList as $key => $oneLd) {
		for ($i=0; $i < count($oneLd['mail']) ; $i++) {
			$req=$pdoUser->prepare("INSERT INTO mag_ld (id_import, ld_full, ld_short, ld_suffixe, id_import_ld, email, lotus, galec, errors) VALUES (:id_import, :ld_full, :ld_short, :ld_suffixe, :id_import_ld, :email, :lotus, :galec, :errors)");
			$req->execute([
				':id_import'		=>$lastinsertId,
				':ld_full'		=>$oneLd['ld_full'],
				':ld_short'		=>$oneLd['ld_short'],
				':ld_suffixe'		=>$oneLd['suffixe'],
				':id_import_ld'		=>$key,
				':email'		=>$oneLd['mail'][$i],
				':lotus'		=>$oneLd['lotus'][$i],
				':galec'		=>$oneLd['galec'],
				':errors'		=>$oneLd['error'][$i],

			]);
			$error=$req->errorInfo();
			if(!empty($error[2])){
				$insertSuccess=false;
			}

		}

	}
	fclose($fn);
	ldap_close($lotusCon);
}

if($insertSuccess){
// si insertion en base de donnée ok, on traite les erreurs et les panneaux galec manquants
// on compare base actuelle et base ancienne
// ajoute différences à la table de comparaison
//
//

}



