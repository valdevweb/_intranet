<?php
require('../config/autoload.php');

if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
else {
	echo "vous êtes connecté avec :";
	echo $_SESSION['id'];
}




//récupère le ou les objet(s) pdo
$pdoUser=getBTLink();
$pdoMag=getMagLink();

// appel au(x) fichier(s) de fonction
require_once '../../functions/form.fn.php';

//header et nav bar
include ('../view/_head.php');
include ('../view/_navbar.php');

//contenu
include('contact.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer.php');







function getGalec($pdomag, $id)
{
	$req=$pdomag->prepare("SELECT * FROM lkmaguser WHERE iduser= :id");
	$req->execute(array(
		':id'	=>$id

	));
	if($idExist=$req->fetch(PDO::FETCH_ASSOC))
		{
			return $idExist;
		}

}


$result=getGalec($pdomag,$id);
$galec= $result['galec'];




function getMagInfo($pdomag,$galec)
{
	$req=$pdomag->prepare("SELECT * FROM sca3 WHERE galec= :galec");
	$req->execute(array(
		':galec'	=> $galec
	));
	return $req->fetch(PDO::FETCH_ASSOC);


}


?>