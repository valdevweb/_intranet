<?php

// fonction d'inscription - db web_user(db,login,pwd,type)
function signup($dbUser,$login,$pwd,$type, $dbBt, $dbMag){
	// on verifie que le login n'est pas déja pris
	$req=$dbUser->prepare("SELECT * FROM users WHERE login= :login");
	{
		$req->execute(array(
			':login'		=> $login
		));
	}

	//si l'utilisateur existe
	if($loginExist=$req->fetch())
	{
		echo "le login existe déjà";
	}
	else
	//sinon on met à jour la db
	{
		//hashage du pwd
		$pwd=pwdHash($pwd);

		$insert=$dbUser->prepare('INSERT INTO users(login,pwd,type) VALUES (:login,:pwd,:type)');
		$insert->execute(array(
			':login' 	=>$login,
			':pwd' 	 	=>$pwd,
			':type'		=>$type
		));
		echo "votre inscription a bien été enregistrée";
	}
}

		// getId($dbUser,$dbBt, $login);


function getId($dbUser,$dbBt, $login)
{	// recup id enregistré
		$req=$dbUser->prepare("SELECT * FROM users WHERE login= :login");
		$req->execute(array(
			':login'	=>$login
		));

		if($idExist=$req->fetch()){
		// update des bases mag ou bt
			switch ($idExist['type']) {
				case 'btlec':
					updateBt($dbBt,$idExist['id'],$idExist['login']);
					break;

				default:
					# code...
					break;
			}

		}

	}






function updateBt($dbBt,$id,$login)
{
	$update=$dbBt->prepare('UPDATE users SET id_login= :id WHERE login= :login');
	$update->execute(array(
		':id' 			=>$id,
		':login'		=>$login

	));
	echo $login;
	echo $id;

}







function addToMag()
{

}