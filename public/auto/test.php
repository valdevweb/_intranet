<?php
if (preg_match('/_btlecest/', dirname(__FILE__)))
{
	define("VERSION",'_');
}
else
{
	define("VERSION",'');
}

function connectToDb($dbname) {
	$host='localhost';
	$username='sql';
	$pwd='User19092017+';
	try {
		$pdo=new PDO("mysql:host=$host;dbname=$dbname", $username, $pwd);

	}
	catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
	}
	return  $pdo;
}
$dbCm=VERSION."cm";
$pdoCm=connectToDb($dbCm);
$pdoUser=connectToDb('web_users');
$req=$pdoUser->prepare("INSERT INTO mag_l (id_import, ld_full, ld_short, ld_suffixe, id_import_ld, email, lotus, galec, errors) VALUES (:id_import, :ld_full, :ld_short, :ld_suffixe, :id_import_ld, :email, :lotus, :galec, :errors)");
			$req->execute([
				':id_import'		=>3,
				':ld_full'		=>$oneLd['ld_full'],
				':ld_short'		=>$oneLd['ld_short'],
				':ld_suffixe'		=>$oneLd['suffixe'],
				':id_import_ld'		=>$key,
				':email'		=>$oneLd['mail'][$i],
				':lotus'		=>$oneLd['lotus'][$i],
				':galec'		=>$oneLd['galec'],
				':errors'		=>$oneLd['error'][$i],



			]);
			$error=$req->errorInfo();
			// $a=$req->errorInfo();
		if(!empty($error[2])){
			$insertSuccess=false;
		}




 ?>