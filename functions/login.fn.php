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


/*_____________________________________________________________
*
* 							login
_______________________________________________________________*/
// ,$postLogin,$postPwd

function login($dbUser){
	//ETAPE 1 on récupère les infos du user grace au login
	$req=$dbUser->prepare("SELECT * FROM users WHERE login= :postLogin");
	$req->execute(array(
		':postLogin'	=> $_POST['login']
	));
	//initialisation du message d'erreur
	$err='';

//	1 LOGIN existe ??
	if(!$data=$req->fetch(PDO::FETCH_ASSOC))
	{
		$err= "le login n'existe pas";
	}
	else
	{
		//login trouvé on récupère l'id :
		$id=$data['id'];

		// on vérifie si on a tj un vieux mot de passe
		if (empty($data['old_pwd']))
		{
			// cas 1 : MOT DE PASSE DEJA CONVERTI
			if(password_verify($_POST['pwd'], $data['pwd']))
			{
				//initialisation session ut
				$_SESSION['id']=$id;
				$_SESSION['user']=$_POST['login'];
				$_SESSION['type']=$data['type'];
				$_SESSION['goto']=$_POST['goto'];

				header('Location:'. ROOT_PATH. '/public/home.php');
			}
			else
			{
				$err = "le mot de passe est erroné";
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
				$_SESSION['id']=$id;
				$_SESSION['user']=$_POST['login'];
				$_SESSION['type']=$data['type'];
				$_SESSION['goto']=$_POST['goto'];
				//redirection sur home.php
				header('Location:'. ROOT_PATH. '/public/home.php');


			}
			else
			{
				$err= "mot de passe erroné -2";
			}
		} //fin traitement vieux mot de passe
	}
	if (!empty($err))
	{
		return $err;
	}
}// fin fonction login



/*_____________________________________________________________
*
* 							chg pwd
_______________________________________________________________*/


/*function updateDb($dbUser,$login,$pwd)
{

$req=$dbUser->prepare("SELECT * FROM users WHERE login= :login");
	{
		$req->execute(array(
			':login'		=> $login
		));
	}

	//si le login n'existe pas
	if(!$getUser=$req->fetch())
	{
		echo "identifiant inconnu";
	}
	else
	//sinon on met à jour la db
	{
		//hashage du pwd
		$pwd=pwdHash($pwd);
		$update=$dbUser->prepare('UPDATE users SET pwd= :pwd, date_signin= :date_signin WHERE id= :id');
		$update->execute(array(
			':id' 	=>$getUser['id'],
			':pwd' 	=>$pwd,
			':date_signin'=>date('Y-m-d H:i:s')

		));
		echo "mot de passe mis à jour";

	}



}*/
