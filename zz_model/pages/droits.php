<?php


//LES DROITS

//Ils sont gérés par la fonction isUserAllowed($pdoUser, $params) qui est dans l'autoload. Ainsi la fonction est chargée sur toutes les pages


function isUserAllowed($pdoUser, $params)
{
	$session=$_SESSION['id_web_user'];
	$placeholders=implode(',', array_fill(0, count($params), '?'));
	$req=$pdoUser->prepare("SELECT id_user FROM attributions WHERE id_droit IN($placeholders) AND id_user=$session" );
	$req->execute($params);
	 return $req->fetchAll(PDO::FETCH_ASSOC);
}
//On passe un tableau de codes (id des droits) à la fonction => la requete ajuste le nombre de paramètres à vérifier en fonction du nb de codes passés à la fonction. Elle vérifie si l'id du user connecté a un des codes attribué


// Exemple d'utilisation, dans la navbar, on veut que le menu culturel ne soit affiché qu'aux mag SCAPALSACE (droit  id 60) et à l'admin (42)
// 1 on récupère la liste de id_web_user à qui on a attribué le droit 60 et 42 dans la table attribution grâce à notre fonction
// notre table d'id droits
$magCulturelIds=array(60,42);
// renvoi 0 si l'id n'y est pas, l'id si celui-ci est trouvé
$d_magCulturelIds=isUserAllowed($pdoUser,$magCulturelIds);



ob_start();
// ____________________________
//
// ici le menu culturel
// ____________________________

// on récupère le contenu du menu
$magCulturelNav=ob_get_contents();
ob_end_clean();
// on l'affiche si la fonction a renvoyé un id
if($d_magCulturelIds)
{
	echo $magCulturelNav;
}





// application des droits sur une page => redirecytion, si non droit


include('../config/autoload.php');
$userExploitEvo=array(53);
$d_userExploitEvo=isUserAllowed($pdoUser,$userExploitEvo);
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. AUTHENTIFICATION);
}
elseif (!$d_userExploitEvo) {
	header('Location:../redirect/redirect.php');
}