<?php
// recup des login d'alex dans table webaccess2 et injection dans web users

function getWebUser() {
	    $host='localhost';
		$username='sql';
		$pwd='User19092017+';
		$database='web_users';

	try {
		$pdo=new PDO("mysql:host=$host;dbname=$database", $username, $pwd);

	}

		 	catch(Exception $e)
	     	{
	         	die('Erreur : '.$e->getMessage());
	      	}
 	return  $pdo;

}


$pdoweb=getWebUser();
// $pdomag=getMag();
function getMag($pdoweb){

	$req=$pdoweb->prepare("SELECT * FROM users WHERE galec IS NOT NULL ");
	$req->execute();
	//$result=$pdoweb->query($req);
	$row= $req->fetchAll(PDO::FETCH_ASSOC);
return $row;

}

$mags=getMag($pdoweb);



foreach ($mags as $mag) {

if(!empty($mag['galec'])){
	echo $mag['login'] . $mag['galec']."<br>" ;

	$req=$pdoweb->prepare('UPDATE users SET type= :type WHERE id= :id');
	//$req->execute(array(
		':type'	=>"mag",
		':id'	=>$mag['id']
	));
	}
}

// $req=$pdomag->prepare('INSERT INTO lkmaguser (iduser,galec)
// 		VALUE(:iduser, :galec)');

// $req->execute(array(
// 	':iduser'	=> $id,
// 	':galec'	=> $galec

// ));