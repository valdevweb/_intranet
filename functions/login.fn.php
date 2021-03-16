<?php

// hash du pwd
function pwdHash($pwd){
	$pwdHash=password_hash($pwd, PASSWORD_BCRYPT);
	return $pwdHash;
}

function btInfo($pdoUser)
{
	$req=$pdoUser->prepare("SELECT * FROM intern_users WHERE id_web_user= :id_web_user");
	$req->execute(array(
		':id_web_user'		=>$_SESSION['id_web_user']
	));
	$data=$req->fetch(PDO::FETCH_ASSOC);
	if(!empty($data)){
		return $data;
	}
	return "";
}
// info mlag table sca3
function magInfo($pdoMag){

	$req=$pdoMag->prepare("SELECT * FROM mag WHERE galec= :galec");
	$req->execute(array(
		':galec'		=>$_SESSION['id_galec']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}

function getSav($pdoSav){
	$req=$pdoSav->prepare("SELECT sav FROM mag WHERE id_web_user= :id_web_user");
	$req->execute(array(
		':id_web_user'		=>$_SESSION['id_web_user']
	));

	return $req->fetch(PDO::FETCH_ASSOC);
}

function getUserSavInfo($pdoSav){
	$req=$pdoSav->prepare("SELECT nom,prenom,sav FROM sav_users WHERE id_web_user= :id_web_user");
	$req->execute(array(
		':id_web_user'		=>$_SESSION['id_web_user']
	));

	return $req->fetch(PDO::FETCH_ASSOC);
}




function getDateMajNohash($pdoUser){
	$req=$pdoUser->prepare("SELECT date_maj_nohash FROM users WHERE id= :id_web_user LIMIT 1");
	$req->execute([
		':id_web_user'		=>$_SESSION['id_web_user']

	]
);
	return $req->fetch(PDO::FETCH_ASSOC);
}



function updateNoHash($pdoUser){
	$req=$pdoUser->prepare('UPDATE users SET nohash_pwd=:pwd, date_maj_nohash= :today WHERE id= :id_web_user');
	$req->execute([
		':pwd'	=>$_POST['pwd'],
		':today'	=>date('Y-m-d H:i:s'),
		':id_web_user'		=>$_SESSION['id_web_user']

	]);
	return $req->rowCount();
}
function updatePwd($pdoUser){
	$req=$pdoUser->prepare('UPDATE users SET pwd=:convertedPwd, old_pwd=:old_pwd  WHERE login= :postLogin');
	$req->execute(array(
		':convertedPwd'		=> $convertedPwd,
		':old_pwd'			=>"",
		':postLogin'		=> $_POST['login']

	));
	return $req->rowCount();

}
/*_____________________________________________________________
*
* 							login
_______________________________________________________________*/



function loginExist($pdoUser){
	$req=$pdoUser->prepare("SELECT * FROM users WHERE login= :postLogin");
	$req->execute(array(
		':postLogin'	=> $_POST['login']
	));
	$data=$req->fetch(PDO::FETCH_ASSOC);
	if(!empty($data)){
		return $data;
	}
	return false;
}
// function login($pdoUser, $pdoBt,$pdoSav)




// 2 vérifie correspondance mot de passe saisi et mot de passe db identique
// + converti mdp si tj sha1

// cas 1 : MOT DE PASSE DEJA CONVERTI
function checkPwd($webUser,$pdoMag,$pdoUser){
	if (empty($webUser['old_pwd']) && !password_verify($_POST['pwd'], $webUser['pwd'])){
		$errors[] = "mot de passe incorrect";
	}elseif(empty($webUser['old_pwd']) && password_verify($_POST['pwd'], $webUser['pwd'])){
		return true;
	}elseif(!empty($webUser['old_pwd'])){
		if(sha1($_POST['pwd'])!=$webUser['old_pwd']){
			return false;

		}else{
			$pwdHash=password_hash($_POST['pwd'], PASSWORD_BCRYPT);

			$req=$pdoUser->prepare('UPDATE users SET pwd=:convertedPwd, old_pwd=:old_pwd  WHERE login= :postLogin');
			$result=$req->execute(array(
				':convertedPwd'		=> $pwdHash,
				':old_pwd'			=>"",
				':postLogin'		=> $_POST['login']

			));

			return true;

		}
	}

}



function initSession($pdoBt, $pdoSav, $pdoMag,$pdoCm, $pdoUser, $webUser){
		//commun
	$_SESSION['id']=$webUser['id'];
	$_SESSION['id_web_user']=$webUser['id'];
	$_SESSION['user']=$_POST['login'];
	$_SESSION['type']=$webUser['type'];
	$_SESSION['id_type']=$webUser['id_type'];

	if(isset($_POST['goto'])){
		$_SESSION['goto']=$_POST['goto'];
	}
	// scapsav
	if($_SESSION['id_type']==3){
		$savInfo=getUserSavInfo($pdoSav);
		if(!empty($savInfo)){
			$_SESSION['nom'] = $savInfo['prenom'] .' ' .$savInfo['nom'];
			$_SESSION['sav']=$savInfo['sav'];
		}
	}

	// if($_SESSION['type']=='btlec' || $_SESSION['type']=="autre" || $_SESSION['type']=="mask"){
	if($_SESSION['id_type']==1 || $_SESSION['id_type']==9 || $_SESSION['id_type']==8){
		$btInfo=btInfo($pdoUser);
		if(!empty($btInfo)){
			$nom=$btInfo['nom'];
			$prenom=$btInfo['prenom'];

			$_SESSION['nom'] = $btInfo['prenom'] .' ' .$btInfo['nom'];
			$_SESSION['id_service']=$btInfo['id_service'];
			$_SESSION['id_btlec']=$webUser['id_bt'];
			$_SESSION['id_group']=$btInfo['id_group'];

		}else{
			$_SESSION['nom'] = "";

			$_SESSION['id_service']="";
			$_SESSION['id_btlec']="";
		}
	}

	// if($_SESSION['type']=="mag" || $_SESSION['type']=="lcommerce" || $_SESSION['type']=="centrale" || $_SESSION['type']=='adh'){
	if($_SESSION['id_type']==2 || $_SESSION['id_type']==7 || $_SESSION['id_type']==5 || $_SESSION['id_type']==4){
		$_SESSION['id_galec']=$webUser['galec'];
		$scatrois=magInfo($pdoMag);
		$magSav=getSav($pdoSav);
		if(!empty($scatrois)){
			$_SESSION['nom']=$scatrois['deno'];
			$_SESSION['centrale']=$scatrois['centrale'];
			$_SESSION['city']=$scatrois['ville'];
			$_SESSION['code_bt']=$scatrois['id'];
		}
		if(!empty($magSav)){
			$_SESSION['sav']=$magSav['sav'];
		}
		$rdvDao=new CmRdvDao($pdoCm);
		$pendingRdv=$rdvDao->getLastPendingRdv();

		if($pendingRdv){
			$_SESSION['rdv_cm']=1;
		}
	}
}



