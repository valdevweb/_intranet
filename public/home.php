<?php
require('../config/autoload.php');

if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

require "../functions/form.fn.php";
require "../functions/userinfo.fn.php";
require "../functions/gazette.fn.php";
require "../functions/stats.fn.php";

//recup gazette de la semaine en cours
$gazettes=showThisWeek($pdoBt);
$links=createLinks($pdoBt,$gazettes,$version);
//stats
$descr="page d'accueil";
$page=basename(__file__);
$action="";
if(!empty($_SERVER['HTTP_REFERER']))
{
	if((parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) !='/'. VERSION .'btlecest/index.php'))
	{
	$action="retour sur la page d'accueil";
	}
}

//personnalisation des sessions et récup de données utilisateur
	if($_SESSION['type']=="mag")
	{
		// lk_user => lien id webuser /idgalec => permet d'interroger le sca 3

		$userPanoGalec=getPanoGalec($pdoUser);
		var_dump($userPanoGalec);
		$_SESSION['id_galec']=$userPanoGalec['galec'];
		//recup info mag dans sca3
		$scatrois=magInfo($pdoBt,$userPanoGalec['galec']);
		//personnalisation affichage => nom du mag
		$typeTitle="Leclerc";
		$_SESSION['nom']=$scatrois['mag'];

		//---------------------------
		//stats
		//---------------------------
		//si action est vide, le user vient de se connecté
		$action = (empty($action)) ? "connexion mag" : $action;
		addRecord($pdoStat,$page,$action, $descr);
			echo "<pre>";
			var_dump($_SESSION);
			echo '</pre>';

	}
	elseif($_SESSION['type']=='btlec')
	{
		// lk_user => lien id webuser /id_btlec => permet d'interroger table btlec (user)
		$lkuserid=getUserId($pdoBt);
		//id_btlec de la table btlec
		$_SESSION['id_btlec']=$lkuserid['id_btlec'];
		//recup info user dans table btlec
		$btInfo=btInfo($pdoBt);
		//personnalisation affichage => nom du mag
		$nom=$btInfo['nom'];
		$prenom=$btInfo['prenom'];
		//$idService=$btInfo['id_service'];

		//si resp => affiché en 1er dans pages contact
		$resp=$btInfo['resp'];
		$typeTitle="";
		$_SESSION ['nom'] = $prenom .' ' .$nom;
		$_SESSION['id_service']=$btInfo['id_service'];

		//---------------------------
		//stats
		//---------------------------
		//si action est vide, le user vient de se connecté
		$action = (empty($action)) ? "connexion BT" : $action;
		addRecord($pdoStat,$page,$action, $descr);
	}
	elseif ($_SESSION['type']=='scapsav')
	{
		$typeTitle="";
		$_SESSION ['nom'] = "";
		//---------------------------
		//stats
		//---------------------------
		//si action est vide, le user vient de se connecté
		$action = (empty($action)) ? "connexion scapsav" : $action;
		addRecord($pdoStat,$page,$action, $descr);
	}
	else
	{
		// si ni de type mag, ni de type bt, ni scapsav
		$typeTitle="";
		$_SESSION ['nom'] = "";
		//---------------------------
		//stats
		//---------------------------
		//si action est vide, le user vient de se connecté
		$action = (empty($action)) ? "connexion non mag - non BT" : $action;
		addRecord($pdoStat,$page,$action, $descr);
	}
// redirection si besoin
	if(!empty($_SESSION['goto']))
	{
		if($_SESSION['type']=="btlec")
		{
			header('Location:'. ROOT_PATH. '/public/btlec/answer.php?msg='.$_SESSION['goto']);
		}

			// mag, scapsav ou vide
		else
		{
			header('Location:'. ROOT_PATH. '/public/mag/edit-msg.php?msg='.$_SESSION['goto']);
		}

	}





include ('view/_head.php');
include ('view/_navbar.php');



//contenu
include('home.ct.php');
?>
<script src="js/slider.js"></script>
<?php



// footer avec les scripts et fin de html
include('view/_footer.php');
 ?>
