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

function undoneImport($pdoMag){
	$req=$pdoMag->query("SELECT * FROM lotus_imports WHERE done=0");
	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($data)){
		return false;
	}elseif(count($data) > 1){
		// prévoir envoi mail
		return false;
	}else{
		return $data;
	}
}

function getExtraction($pdoMag,$id,$type){
	$req=$pdoMag->prepare("SELECT * FROM lotus_extraction WHERE id_import= :id_import AND type= :type");
	$req->execute([
		':type'		=>$type,
		':id_import'=>$id
	]);
	$datas=$req->fetchAll();
	if(empty($datas)){
		return "";
	}
	return $datas;
}
function eraseLdLotus($pdoMag){
	$req=$pdoMag->query("DELETE FROM lotus_ld");
}

function insertEmail($pdoMag, $id_import, $ld_full, $ld_short, $ld_suffixe, $id_import_ld, $email, $lotus, $btlec, $errors ){
	$req=$pdoMag->prepare("INSERT INTO lotus_ld (id_import, ld_full, ld_short, ld_suffixe, id_import_ld, email, lotus, btlec, errors) VALUES (:id_import, :ld_full, :ld_short, :ld_suffixe, :id_import_ld, :email, :lotus, :btlec, :errors)");
	$req->execute([
		':id_import'		=>$id_import,
		':ld_full'		=>$ld_full,
		':ld_short'		=>$ld_short,
		':ld_suffixe'		=>$ld_suffixe,
		':id_import_ld'		=>$id_import_ld,
		':email'		=>$email,
		':lotus'		=>$lotus,
		':btlec'		=>$btlec,
		':errors'		=>$errors,

	]);
	$error=$req->errorInfo();
	if(!empty($error[2])){
		return false;
	}
	return true;
}
/* ------------------------------------
*				Initialisation
---------------------------------------	*/
$added=0;

$lotusCon=ldap_connect('217.0.222.26',389);
$ldaptree    = "OU=galec,o=e-leclerc,c=fr";
$ldapuser="ADMIN_BTLEC";
$lpappass="toronto";
$ldapbind = ldap_bind($lotusCon, $ldapuser, $lpappass) or die ("Error trying to bind: ".ldap_error($ldapbind));
$justThese = array( "mail","displayname");


/* ------------------------------------
*				traitement
---------------------------------------	*/


// regarde si import non traité => done à 0
$newData=undoneImport($pdoMag);
if(!$newData){
	exit;
}

// si on a un import à traiter, on récupère les extractions par catégories de traitement
$empty=getExtraction($pdoMag,$newData[0]['id'],"vide");
$lotus=getExtraction($pdoMag,$newData[0]['id'],"lotus");
$emailDb=getExtraction($pdoMag,$newData[0]['id'],"email");
$ld=getExtraction($pdoMag,$newData[0]['id'],"ld");
// efface lotus_ld
eraseLdLotus($pdoMag);

if(!empty($empty)){
	foreach ($empty as $key => $extraction) {
		$codeErr=4;
		$one=insertEmail($pdoMag, $newData[0]['id'], $extraction['ld_full'], $extraction['ld_short'], $extraction['suffixe'], $extraction['ld_id'], '', '', $extraction['btlec'], $codeErr);
		if($one){
			$added++;
		}else{
			$taskErrors[$extraction['ld_id']][$extraction['array_i']]['type']="vide";
			$taskErrors[$extraction['ld_id']][$extraction['array_i']]['ld_full']=$extraction['ld_full'];
			$taskErrors[$extraction['ld_id']][$extraction['array_i']]['contenu']='';
			$taskErrors[$extraction['ld_id']][$extraction['array_i']]['id_import']=$newData[0]['id'];
		}
	}
}

if(!empty($lotus)){
	foreach ($lotus as $key => $extraction) {
		$name=explode('/',$extraction['contenu']);
		$name=trim($name[0]);
		$result=ldap_search($lotusCon, $ldaptree, "(CN=*".$name."*)",$justThese);
		$data = ldap_get_entries($lotusCon, $result);
		// correspondace trouvée
		if(count($data)>1){
			$email=$data[0]['mail'][0];
			$anotherLotus=explode('/',$email);
			if(count($anotherLotus)>1){
				$codeErr=5;
				$one=insertEmail($pdoMag, $newData[0]['id'], $extraction['ld_full'], $extraction['ld_short'], $extraction['suffixe'], $extraction['ld_id'], '', $email, $extraction['btlec'], $codeErr);
				if($one){
					$added++;
				}else{
					$taskErrors[$extraction['ld_id']][$extraction['array_i']]['type']="lotus";
					$taskErrors[$extraction['ld_id']][$extraction['array_i']]['ld_full']=$extraction['ld_full'];
					$taskErrors[$extraction['ld_id']][$extraction['array_i']]['contenu']=$email;
					$taskErrors[$extraction['ld_id']][$extraction['array_i']]['id_import']=$newData[0]['id'];
				}
			}else{
				$codeErr=0;
				$one=insertEmail($pdoMag, $newData[0]['id'], $extraction['ld_full'], $extraction['ld_short'], $extraction['suffixe'], $extraction['ld_id'], $email,'', $extraction['btlec'], $codeErr);
				if($one){
					$added++;
				}else{
					$taskErrors[$extraction['ld_id']][$extraction['array_i']]['type']="lotus";
					$taskErrors[$extraction['ld_id']][$extraction['array_i']]['ld_full']=$extraction['ld_full'];
					$taskErrors[$extraction['ld_id']][$extraction['array_i']]['contenu']=$email;
					$taskErrors[$extraction['ld_id']][$extraction['array_i']]['id_import']=$newData[0]['id'];
				}
			}
		}else{
			// correspondance non trouvée
			$codeErr=2;
			$one=insertEmail($pdoMag, $newData[0]['id'], $extraction['ld_full'], $extraction['ld_short'], $extraction['suffixe'], $extraction['ld_id'], '', $email, $extraction['btlec'], $codeErr);
			if($one){
				$added++;
			}else{
				$taskErrors[$extraction['ld_id']][$extraction['array_i']]['type']="lotus";
				$taskErrors[$extraction['ld_id']][$extraction['array_i']]['ld_full']=$extraction['ld_full'];
				$taskErrors[$extraction['ld_id']][$extraction['array_i']]['contenu']=$extraction['contenu'];
				$taskErrors[$extraction['ld_id']][$extraction['array_i']]['id_import']=$newData[0]['id'];
			}
		}
	}
}


if(!empty($emailDb)){
	foreach ($emailDb as $key => $extraction) {
		$name=explode('@',$extraction['contenu']);
		$name=trim($name[0]);
		$result=ldap_search($lotusCon, $ldaptree, "(mail=*".$name."*)",$justThese);
		$data = ldap_get_entries($lotusCon, $result);
			// echo "<pre>";
			// print_r($data);
			// echo '</pre>';

		// correspondace trouvée
		if(count($data)>1){
			$codeErr=0;
			$email=$data[0]['mail'][0];
			$one=insertEmail($pdoMag, $newData[0]['id'], $extraction['ld_full'], $extraction['ld_short'], $extraction['suffixe'], $extraction['ld_id'], $email,'', $extraction['btlec'], $codeErr);
			if($one){
				$added++;
			}else{
				$taskErrors[$extraction['ld_id']][$extraction['array_i']]['type']="lotus";
				$taskErrors[$extraction['ld_id']][$extraction['array_i']]['ld_full']=$extraction['ld_full'];
				$taskErrors[$extraction['ld_id']][$extraction['array_i']]['contenu']=$email;
				$taskErrors[$extraction['ld_id']][$extraction['array_i']]['id_import']=$newData[0]['id'];
			}

		}else{
			// adresse mail non trouvée
			$codeErr=9;
			$one=insertEmail($pdoMag, $newData[0]['id'], $extraction['ld_full'], $extraction['ld_short'], $extraction['suffixe'], $extraction['ld_id'], '', $extraction['contenu'], $extraction['btlec'], $codeErr);
			if($one){
				$added++;
			}else{
				$taskErrors[$extraction['ld_id']][$extraction['array_i']]['type']="lotus";
				$taskErrors[$extraction['ld_id']][$extraction['array_i']]['ld_full']=$extraction['ld_full'];
				$taskErrors[$extraction['ld_id']][$extraction['array_i']]['contenu']=$extraction['contenu'];
				$taskErrors[$extraction['ld_id']][$extraction['array_i']]['id_import']=$newData[0]['id'];
			}
		}
	}
}
if(!empty($ld)){
	foreach ($ld as $key => $extraction) {
		$codeErr=9;
		$one=insertEmail($pdoMag, $newData[0]['id'], $extraction['ld_full'], $extraction['ld_short'], $extraction['suffixe'], $extraction['ld_id'], '', $extraction['contenu'], $extraction['btlec'], $codeErr);
		if($one){
			$added++;
		}else{
			$taskErrors[$extraction['ld_id']][$extraction['array_i']]['type']="lotus";
			$taskErrors[$extraction['ld_id']][$extraction['array_i']]['ld_full']=$extraction['ld_full'];
			$taskErrors[$extraction['ld_id']][$extraction['array_i']]['contenu']=$extraction['contenu'];
			$taskErrors[$extraction['ld_id']][$extraction['array_i']]['id_import']=$newData[0]['id'];
		}

	}
}

echo "nb adresses ajoutées " .$added;