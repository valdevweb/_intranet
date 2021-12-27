<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'config/db-connect.php';
include 'functions/tasklog.fn.php';
require 'Class/mag/LotusDao.php';

/*
vérif dans table imports si import avec done à 0 => si oui, la table lotus_ld a été changée donc :
ajoute adresses non existantes dans nouvelle base email_list
récupére les nouveau id de la table email list pour les mettre dans lotus_ld
met l'import à done = 1

 */
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

function getLdEmails($pdoMag){
	$req=$pdoMag->query("SELECT DISTINCT email FROM lotus_ld");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function alreadyInEmails($pdoMag, $email){
	$req=$pdoMag->prepare("SELECT * FROM  emails WHERE email LIKE :email");
	$req->execute([
		':email'	=>'%'.$email.'%'
	]);
	$data=$req->fetch();
	if(empty($data)){
		return false;
	}
	return true;
}
function addEmail($pdoMag,$email){
	$req=$pdoMag->prepare("INSERT INTO emails (email) VALUES (:email)");
	$req->execute([
		':email'	=>$email
	]);
}

function getAllLdEmails($pdoMag){
	$req=$pdoMag->query("SELECT id, email FROM lotus_ld");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function updateLd($pdoMag, $idMail, $idLd){
	$req=$pdoMag->prepare("UPDATE lotus_ld SET id_emails= :id_emails WHERE id= :id");
	$req->execute([
		':id'		=>$idLd,
		':id_emails'	=>$idMail
	]);
	return $req->rowCount();
}

function getMailId($pdoMag,$email){
	$req=$pdoMag->prepare("SELECT id FROM emails WHERE email LIKE :email");
	$req->execute([
		':email'		=>$email
	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);
	if(!empty($data)){
		return $data['id'];
	}
	return false;
}

function updateImport($pdoMag,$id){
	$req=$pdoMag->prepare("UPDATE lotus_imports SET done=2 WHERE id= :id");
	$req->execute([
		':id' => $id
	]);
	return $req->rowCount();

}
// regarde si import non traité => done à 0
$newData=undoneImport($pdoMag);

$lotusDao= new LotusDao($pdoMag);





if(!$newData){
	exit;
}
$addedMails=0;
$updatedIds=0;
$errors=[];
$ldEmails=getLdEmails($pdoMag);

// parcours les emails du nouvel import et vérfie si ils exsitent dans emails

foreach ($ldEmails as $key => $emailsFromLd) {
	// si on ne trouve pas le mail, on l'ajoute
	if(!empty(trim($emailsFromLd['email']))){
		if(!alreadyInEmails($pdoMag, trim($emailsFromLd['email']))){
			addEmail($pdoMag,trim($emailsFromLd['email']));
			$addedMails++;
		}
	}
}
//1461
echo "nb de mails ajoutés " . $addedMails;
echo "<br>";

$allLdEmails= getAllLdEmails($pdoMag);
foreach ($allLdEmails as $key => $ld) {
	$id=getMailId($pdoMag, $ld['email']);
	if($id){
		$update=updateLd($pdoMag,$id,$ld['id']);
		if($update==1){
			$updatedIds++;
		}else{
			$errors[]="erreur de mise à jour pour la ligne ". $ld['id'] . " de lotus_ld";
		}
	}
}
echo "nb de maj " . $updatedIds;
echo "<br>";

echo "<pre>";
print_r($errors);
echo '</pre>';

//  ajout adresses réachemeniement
$listEmails=$lotusDao->getAllMailFromLd();

$lotusCon=ldap_connect('217.0.222.26',389);
$ldaptree    = "OU=galec,o=e-leclerc,c=fr";
$ldapuser="ADMIN_BTLEC";
$lpappass="toronto";
$ldapbind = ldap_bind($lotusCon, $ldapuser, $lpappass) or die ("Error trying to bind: ".ldap_error($ldapbind));
$justThese = array( "mail","displayname", "mailaddress");

foreach ($listEmails as $email) {

			// echo $email['contenu'];
	$name=explode('@',$email['email']);

	if (count($name)>1) {
		$name=trim($name[0]);


		$result=ldap_search($lotusCon, $ldaptree, "(CN=*".$name."*)",$justThese);
		$data = ldap_get_entries($lotusCon, $result);

		if(isset($data[0]['mailaddress'])){
			if(str_contains($data[0]['mailaddress'][0],'.leclerc')){
				$lotusDao->updateRouting($email['id'], $data[0]['mailaddress'][0]);
				echo "OUI pour " .$name ."et k'adresse mail ".$email['email'] ." REDIRECTION " .$data[0]['mailaddress'][0];
				echo "<br>";
			}
		}
	}
}




// maj import => traité
$over=updateImport($pdoMag, $newData[0]['id']);
if($over==1){
	echo "traitement réussi";
}else{
	echo "echec de traitement";

}