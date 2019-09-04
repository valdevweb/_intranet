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
	$req=$pdoBt->prepare("SELECT * FROM btlec WHERE id= :btlec");
	$req->execute(array(
		':btlec'		=>$_SESSION['id_btlec']
	));

	return $req->fetch(PDO::FETCH_ASSOC);
}
// info mlag table sca3
function magInfo($pdoBt){

	$req=$pdoBt->prepare("SELECT * FROM sca3 WHERE galec= :galec");
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

/*_____________________________________________________________
*
* 							login
_______________________________________________________________*/

function login($pdoUser, $pdoBt,$pdoSav)
{
	//ETAPE 1 on récupère les infos du user grace au login
	$req=$pdoUser->prepare("SELECT * FROM users WHERE login= :postLogin");
	$req->execute(array(
		':postLogin'	=> $_POST['login']
	));
	//initialisation du message d'erreur
	$errors=[];
//	1 LOGIN existe ??
	if(!$web_users=$req->fetch(PDO::FETCH_ASSOC))
		{
			$errors[]= "le login n'existe pas";
		}
		else
		{
		// on vérifie si on a tj un vieux mot de passe
			if (empty($web_users['old_pwd']))
			{
			// cas 1 : MOT DE PASSE DEJA CONVERTI
				if(!password_verify($_POST['pwd'], $web_users['pwd']))
				{
					$errors[] = "le mot de passe est erroné";
				}

			}
		//cas 2 : VIEUX MDP
			else
			{
			// vérifie que le vieux mot de passe correspond au login
				if(sha1($_POST['pwd'])==$web_users['old_pwd'])
				{
					// convertion
					$convertedPwd=pwdHash($_POST['pwd']);
					// maj la db : sup old pwd et update pwd
					$req=$pdoUser->prepare('UPDATE users SET pwd=:convertedPwd, old_pwd=:old_pwd  WHERE login= :postLogin');
					$result=$req->execute(array(
						':convertedPwd'		=> $convertedPwd,
						':old_pwd'			=>"",
						':postLogin'		=> $_POST['login']

					));
				}
				else
				{
					$errors[]= "mot de passe erroné -2";
				}
		} //fin traitement vieux mot de passe
	}
	if (count($errors) == 0)
	{
		//commun
		$_SESSION['id']=$web_users['id'];
		$_SESSION['id_web_user']=$web_users['id'];
		$_SESSION['user']=$_POST['login'];
		$_SESSION['type']=$web_users['type'];
		if(isset($_POST['goto']))
		{
			$_SESSION['goto']=$_POST['goto'];
		}

		// cas spécifique pour salon pour luc puissse saisir invitation
		if($_SESSION['user']=="MULLER" || $_SESSION['user']=="user")
		{
			// recup info mag auquel le user bt est rattaché
			$_SESSION['id_galec']=$web_users['galec'];
			$scatrois=magInfo($pdoBt);
			$_SESSION['nom']=$scatrois['mag'];
			$_SESSION['city']=$scatrois['city'];
			$_SESSION['code_bt']=$scatrois['btlec'];
			$_SESSION['id_btlec']=$web_users['id_bt'];
			$btInfo=btInfo($pdoBt);
			$nom=$btInfo['nom'];
			$prenom=$btInfo['prenom'];
			$_SESSION['nom_bt'] = $prenom .' ' .$nom;
			$_SESSION['id_service']=$btInfo['id_service'];
			$_SESSION['spe']="yes";
		}

		if($_SESSION['type']=="mag" || $_SESSION['type']=="bbj" || $_SESSION['type']=="centrale" || $_SESSION['type']=='adh')
		{
			$_SESSION['id_galec']=$web_users['galec'];


		//recup info mag dans sca3
			$scatrois=magInfo($pdoBt);
			$_SESSION['nom']=$scatrois['mag'];
			$_SESSION['centrale']=$scatrois['centrale'];
			$_SESSION['city']=$scatrois['city'];
			$_SESSION['code_bt']=$scatrois['btlec'];
			// $_SESSION['id_btlec']=$web_users['id_bt'];
		}
		if( $_SESSION['type']=="mag" || $_SESSION['type']=="bbj")
		{
			$magSav=getSav($pdoSav);
			$_SESSION['sav']=$magSav['sav'];

		}
		if($_SESSION['type']=='scapsav')
		{
			$savInfo=getUserSavInfo($pdoSav);
			$prenom=$savInfo['prenom'];
			$nom=$savInfo['nom'];
			$_SESSION['nom'] = $prenom .' ' .$nom;
			$_SESSION['sav']=$savInfo['sav'];

		}

		if($_SESSION['type']=='btlec')
		{
			$_SESSION['id_btlec']=$web_users['id_bt'];
		//recup info user dans table btlec
			$btInfo=btInfo($pdoBt);
			$nom=$btInfo['nom'];
			$prenom=$btInfo['prenom'];
			$_SESSION['nom'] = $prenom .' ' .$nom;
			// test
			$_SESSION['nom_bt'] = $prenom .' ' .$nom;
			$_SESSION['id_service']=$btInfo['id_service'];

		}
		if($_SESSION['type']=="autre" ||$_SESSION['type']=="inconnu")
		{
			$_SESSION['nom'] = "";
			$_SESSION['nom_bt'] = "";
		}

		//----------------------------------------------------
		//  tout est ok, on redirige
		//----------------------------------------------------
		$success[]="user authentifié";
		return $success;



	}
	else
	{
		return $errors;

	}
}



