<?php

// hash du pwd
function pwdHash($pwd){
	// définition du salt
	$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
	$salt = base64_encode($salt);
	$options = ['salt' => $salt];
	$pwdHash = password_hash($pwd, PASSWORD_BCRYPT, $options);
	return $pwdHash;
}

function btInfo($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM btlec WHERE id_webuser= :id_webuser");
	$req->execute(array(
		':id_webuser'		=>$_SESSION['id_webuser']
	));
	$data=$req->fetch(PDO::FETCH_ASSOC);
	if(!empty($data)){
		return $data;
	}
	return "";
}
// info mlag table sca3
function magInfo($pdoMag){

	$req=$pdoBt->prepare("SELECT * FROM mag WHERE galec= :galec");
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
function checkPwd($webUser,$pdoMag){
	if (empty($webUser['old_pwd']) && !password_verify($_POST['pwd'], $webUser['pwd'])){
		$errors[] = "mot de passe incorrect";
	}elseif(empty($webUser['old_pwd']) && password_verify($_POST['pwd'], $webUser['pwd'])){
		return true;
	}elseif(!empty($webUser['old_pwd'])){
		if(sha1($_POST['pwd'])!=$webUser['old_pwd']){
			return $errors[]= "mot de passe incorrect";

		}else{

			$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
			$salt = base64_encode($salt);
			$options = ['salt' => $salt];
			$pwdHash = password_hash($pwd, PASSWORD_BCRYPT, $options);
			$req=$pdoUser->prepare('UPDATE users SET pwd=:convertedPwd, old_pwd=:old_pwd  WHERE login= :postLogin');
			$result=$req->execute(array(
				':convertedPwd'		=> $convertedPwd,
				':old_pwd'			=>"",
				':postLogin'		=> $_POST['login']

			));

			login($pdoMag, $pdoBt, $pdoSav, $pdoUser);
		}
	}

}



function login($pdoUser, $pdoBt, $pdoSav, $pdoMag){
		//commun
	$_SESSION['id']=$webUser['id'];
	$_SESSION['id_web_user']=$webUser['id'];
	$_SESSION['user']=$_POST['login'];
	$_SESSION['type']=$webUser['type'];

	if(isset($_POST['goto'])){
		$_SESSION['goto']=$_POST['goto'];
	}
	if($_SESSION['type']=='scapsav'){
		$savInfo=getUserSavInfo($pdoSav);
		if(!empty($savInfo)){
			$_SESSION['nom'] = $savInfo['prenom'] .' ' .$savInfo['nom'];
			$_SESSION['sav']=$savInfo['sav'];
		}

	}

	if($_SESSION['type']=='btlec' || $_SESSION['type']=="autre" || $_SESSION['type']=="mask"){
		$btInfo=btInfo($pdoBt);
		if(!empty($btInfo)){
			$nom=$btInfo['nom'];
			$prenom=$btInfo['prenom'];
			$_SESSION['nom_bt']= $btInfo['prenom'] .' ' .$btInfo['nom'];
			$_SESSION['nom'] = $btInfo['prenom'] .' ' .$btInfo['nom'];
			$_SESSION['id_service']=$btInfo['id_service'];
			$_SESSION['id_btlec']=$webUser['id_bt'];

		}else{
			$_SESSION['nom'] = "";
			$_SESSION['nom_bt'] = "";
			$_SESSION['id_service']="";
			$_SESSION['id_btlec']="";
		}

	}



	if($_SESSION['type']=="mag" || $_SESSION['type']=="bbj" || $_SESSION['type']=="centrale" || $_SESSION['type']=='adh'){
		$_SESSION['id_galec']=$webUser['galec'];
		$scatrois=magInfo($pdoMag);
		$magSav=getSav($pdoSav);
		if(!empty($scatrois)){
			$_SESSION['nom']=$scatrois['mag'];
			$_SESSION['centrale']=$scatrois['centrale'];
			$_SESSION['city']=$scatrois['city'];
			$_SESSION['code_bt']=$scatrois['btlec'];
		}
		if(!empty($magSav)){
			$_SESSION['sav']=$magSav['sav'];
		}
	}









