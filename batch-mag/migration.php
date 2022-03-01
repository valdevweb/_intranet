<?php
if (preg_match('/_btlecest/', dirname(__FILE__))) {
	set_include_path("D:\www\_intranet\_btlecest\\");
} else {
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'config/db-connect.php';
// include 'batch-mag/utils.fn.php';


function getSavCorrespondance($pdoMag, $sav)
{
	$req = $pdoMag->prepare("SELECT id from corresp_sav WHERE sav= :sav");
	$req->execute([
		':sav'		=> $sav
	]);
	$data = $req->fetch(PDO::FETCH_ASSOC);
	if (!empty($data)) {
		return $data['id'];
	}
	return NULL;
}

function getMagSav($pdoSav)
{
	$req = $pdoSav->query("SELECT * FROM mag");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getMagNotInGessica($pdoMag)
{
	$req = $pdoMag->query("SELECT * FROM mag where absent=1 and id>10000");
	return $req->fetchAll();
}

function magSca3($pdoMag)
{
	$req = $pdoMag->query("SELECT * FROM sca3 where date_fermeture is null");
	return $req->fetchAll();
}
function addToSca3($pdoMag, $id)
{
	$req = $pdoMag->query("
    INSERT INTO sca3(btlec_sca, galec_sca, deno_sca, centrale_sca, ad1_sca, ad2_sca,  cp_sca, ville_sca, tel_sca, fax_sca, surface_sca, adherent_sca, directeur_sca, sorti, backoffice_sca ) 
    SELECT id,galec, deno, centrale, ad1, ad2, cp, ville, tel, fax, surface, adherent, directeur, gel, backoffice FROM mag WHERE id={$id}");
	return $req->errorInfo();
}
$mags = getMagSav($pdoSav);


function getIdWebUser($pdoUser, $galec)
{
	$req = $pdoUser->prepare("SELECT * FROM users WHERE galec = :galec and id_type=2");
	$req->execute([
		':galec'	=> $galec


	]);
	$data = $req->fetch();
	return $data;
}
function getUserMag($pdoUser)
{
	$req = $pdoUser->query("SELECT * FROM users WHERE id_type=2");
	$data = $req->fetchAll();
	return $data;
}


function updateIdwebuser($pdoMag, $galec, $idwebuser){
	$req=$pdoMag->prepare("UPDATE sca3 SET id_web_user= :id_web_user where galec_sca= :galec");
	$req->execute([
		':galec'		=>$galec,
		':id_web_user'	=>$idwebuser
	]);
	return $req->rowCount();
}

function getId($pdoMag, $galec){
	$query="SELECT * from mag where galec='{$galec}'";
	// echo $query;
	$req=$pdoMag->query($query);

	return $req->fetch();
}

function updateUser($pdoUser, $id, $btlec){
	$req=$pdoUser->prepare("UPDATE users set btlec = :btlec where id= :id");
	$req->execute([
		':btlec'		=>$btlec,
		':id'			=>$id

	]);
}

// foreach($mags as $mag){

//     $poleSav=getSavCorrespondance($pdoMag,$mag['sav']);
//     $antenneSav=getSavCorrespondance($pdoMag,$mag['pole']);
//     $req=$pdoMag->query("UPDATE sca3 SET pole_sav={$poleSav}, antenne_sav={$antenneSav} WHERE btlec_sca={$mag['btlec']}");
//     echo $req->rowCount();


// }


// $toAdd=getMagNotInGessica($pdoMag);

// foreach ($toAdd as $key => $mag) {
//     $done=addToSca3($pdoMag, $mag['id']);
//     echo "<pre>";
//     print_r($done);
//     echo '</pre>';
// }

// update champ id_web_user de la table sca3


// $magSca3 = magSca3($pdoMag);


// foreach ($magSca3 as $mag) {
// 	// galec
// 	$user = getIdWebUser($pdoUser, $mag['galec_sca']);
// 	if (empty($user)) {

// 		// echo "NON TROUVE " . $mag['deno_sca'] . ' ' . $mag['galec_sca'];
// 		// echo "<br>";
// 	}else{
// 		echo $user['login'] . ' ' . $user['id'] . ' ' . $mag['deno_sca'] . ' ' . $mag['galec_sca'] . ' ' . $user['galec'];
// 		echo "<br>";
// 		$done=updateIdwebuser($pdoMag, $mag['galec_sca'], $user['id']);
// 	}

// }
// ajouter le code bt à la table user

$userMag=getUserMag($pdoUser);
foreach ($userMag as $key =>$user) {
	echo $user['galec']. ' ' .$user['login'] . ' ';
	$btlec=getId($pdoMag, $user['galec']);
	if(!empty($btlec)){
		echo $btlec['id'] . ' user id '. $user['id'];

		updateUser($pdoUser, $user['id'], $btlec['id']);

	}
	echo "<br>";
}