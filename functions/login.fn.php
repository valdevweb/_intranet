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
}



/*_____________________________________________________________
*
* 							login
_______________________________________________________________*/

function login($dbUser, $pdoBt)
{
	//ETAPE 1 on récupère les infos du user grace au login
	$req=$dbUser->prepare("SELECT * FROM users WHERE login= :postLogin");
	$req->execute(array(
		':postLogin'	=> $_POST['login']
	));
	//initialisation du message d'erreur
	$errors=[];
//	1 LOGIN existe ??
	if(!$data=$req->fetch(PDO::FETCH_ASSOC))
		{
			$errors[]= "le login n'existe pas";
		}
		else
		{
		// on vérifie si on a tj un vieux mot de passe
			if (empty($data['old_pwd']))
			{
			// cas 1 : MOT DE PASSE DEJA CONVERTI
				if(password_verify($_POST['pwd'], $data['pwd']))
				{
				//initialisation session ut
					$_SESSION['id']=$data['id'];
					$_SESSION['user']=$_POST['login'];
					$_SESSION['type']=$data['type'];
					$_SESSION['goto']=$_POST['goto'];
				//header('Location:'. ROOT_PATH. '/public/home.php');
				}
				else
				{
					$errors[] = "le mot de passe est erroné";
				}

			}
		//cas 2 : VIEUX MDP
			else
			{
			// vérifie que le vieux mot de passe correspond au login
				if(sha1($_POST['pwd'])==$data['old_pwd'])
				{
			// convertion
					$convertedPwd=pwdHash($_POST['pwd']);
			// maj la db : sup old pwd et update pwd
					$req=$dbUser->prepare('UPDATE users SET pwd=:convertedPwd, old_pwd=:old_pwd  WHERE login= :postLogin');
					$result=$req->execute(array(
						':convertedPwd'		=> $convertedPwd,
						':old_pwd'			=>"",
						':postLogin'		=> $_POST['login']

					));
				//initialisation session ut
					$_SESSION['id']=$data['id'];
					$_SESSION['user']=$_POST['login'];
					$_SESSION['type']=$data['type'];
					$_SESSION['goto']=$_POST['goto'];
				//redirection sur home.php
				//header('Location:'. ROOT_PATH. '/public/home.php');


				}
				else
				{
					$errors[]= "mot de passe erroné -2";
				}
		} //fin traitement vieux mot de passe
	}
	if (count($errors) == 0)
	{
		if($_SESSION['type']=="mag" || $_SESSION['type']=="centrale")
		{
			$_SESSION['id_galec']=$data['galec'];
		//recup info mag dans sca3
			$scatrois=magInfo($pdoBt,$data['galec']);
			$_SESSION['nom']=$scatrois['mag'];
			$_SESSION['centrale']=$scatrois['centrale'];
			$_SESSION['city']=$scatrois['city'];

		}
		elseif($_SESSION['type']=='btlec')
		{
			$_SESSION['id_btlec']=$data['id_bt'];
		//recup info user dans table btlec
			$btInfo=btInfo($pdoBt);
			$nom=$btInfo['nom'];
			$prenom=$btInfo['prenom'];
			$_SESSION['nom'] = $prenom .' ' .$nom;
			$_SESSION['id_service']=$btInfo['id_service'];

		}
		elseif ($_SESSION['type']=='scapsav')
		{
			$_SESSION['nom'] = "";

		}
		else
		{
		// si ni de type mag, ni de type bt, ni scapsav
			$_SESSION['nom'] = "";

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



