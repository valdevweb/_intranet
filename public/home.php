<?php
require('../config/autoload.php');

if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

//----------------------------------------------------------------
//	css dynamique
//----------------------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";


require "../functions/form.fn.php";
require "../functions/userinfo.fn.php";
require "../functions/gazette.fn.php";
require "../functions/stats.fn.php";

//recup gazette de la semaine en cours
$gazettes=showThisWeek($pdoBt);
$links=createLinks($pdoBt,$gazettes,$version);

	echo "<pre>";
	var_dump($_SESSION);
	echo '</pre>';




// les 2 dernière gazettes opportunités
$gazetteAppros=showLastGazettesAppros($pdoBt);
$link="http://172.30.92.53/".$version."upload/gazette/";
$approHtml="";
if($gazetteAppros){
	foreach ($gazetteAppros as $gazette)
	{
		$approHtml .= "<li><i class='fa fa-angle-double-right'></i><a class='simple-link stat-link' data-user-session='".$_SESSION['user']."' href='".$link.$gazette['file']."'>" .mb_strtolower($gazette['file'],'UTF-8') ."</a></li>";
	}
}

// $gazetteAppros=showLastGazettesAppros($pdoBt);
//$linksAppros=createLinksAppros($pdoBt,$gazetteAppros,$version);

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
	if($_SESSION['type']=="mag" || $_SESSION['type']=="centrale")
	{
		//id user=> galec
		$userPanoGalec=getPanoGalec($pdoUser);
		$_SESSION['id_galec']=$userPanoGalec['galec'];
		//recup info mag dans sca3
		$scatrois=magInfo($pdoBt,$userPanoGalec['galec']);
		//personnalisation affichage => nom du mag
		if($_SESSION['type']=='mag')
		{
			$typeTitle="Leclerc";
		}
		else
		{
			$typeTitle="Centrale";
		}
		$_SESSION['nom']=$scatrois['mag'];
		$_SESSION['centrale']=$scatrois['centrale'];
		$_SESSION['city']=$scatrois['city'];

		//---------------------------
		//stats
		//---------------------------
		//si action est vide, le user vient de se connecté
		$action = (empty($action)) ? "connexion mag" : $action;
		addRecord($pdoStat,$page,$action, $descr);
	}
	elseif($_SESSION['type']=='btlec')
	{
		//id user => id_bt
		$userIdBt=getUserId($pdoUser);
		//id_btlec de la table btlec
		$_SESSION['id_btlec']=$userIdBt['id_bt'];

		//recup info user dans table btlec
		$btInfo=btInfo($pdoBt);
		//personnalisation affichage => nom du mag
		$nom=$btInfo['nom'];
		$prenom=$btInfo['prenom'];
		//$idService=$btInfo['id_service'];

		//si resp => affiché en 1er dans pages contact
		$resp=$btInfo['resp'];
		$typeTitle="";
		$_SESSION['nom'] = $prenom .' ' .$nom;
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
		$_SESSION['nom'] = "";
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
		$_SESSION['nom'] = "";
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
		//si on a une query string, on la découpe et on vérif si la 1er partie est numerique ou pas
		//si 1ere partie numérique, c'est un vieux lien donc on redirige sur page edit-msg(mag) ou page answer(btlec)
		//sinon on recupère toute la query string
		$goto=$_SESSION['goto'];
		$redir=explode("&",$goto);
		if(is_numeric($redir[0]))
		{
			if($_SESSION['type']=="btlec")
			{
				header('Location:'. ROOT_PATH. '/public/btlec/answer.php?msg='.$_SESSION['goto']);
			}
			else
			{
				header('Location:'. ROOT_PATH. '/public/mag/edit-msg.php?msg='.$_SESSION['goto']);
			}
		}else
		{
				header('Location:' .ROOT_PATH. '/public/' .$goto);
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
