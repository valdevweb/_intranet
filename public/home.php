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

// les 2 dernière gazettes opportunités
$gazetteAppros=showLastGazettesAppros($pdoBt);
$link="http://172.30.92.53/".$version."upload/gazette/";
$approHtml="";
if($gazetteAppros){
	foreach ($gazetteAppros as $gazette)
	{
		//modif du 20/06
		if(!empty($gazette['title']))
		{
			$detail=" : <br>";
			$detail.=str_replace("<br />"," - ",$gazette['title']);
		}
		else
		{
			$detail="";
		}
		$filename=$gazette['file'];
		$filename=explode(".",$filename);
		$approFilename=$filename[0];
		$approHtml .= "<li><a class='simple-link stat-link' data-user-session='".$_SESSION['user']."' href='".$link.$gazette['file']."'><i class='fa fa-hand-o-right pr-3' aria-hidden='true'></i>".$approFilename."</a>"  . $detail."</li>";
	}
}
$gazetteSpe=showThisWeekSpe($pdoBt);
$speHtml="";

if($gazetteSpe){
	foreach ($gazetteSpe as $gSpe)
	{
		$speHtml .= "<li><a class='simple-link stat-link' data-user-session='".$_SESSION['user']."' href='".$link.$gSpe['file']."'><i class='fa fa-hand-o-right pr-3' aria-hidden='true'></i>" .mb_strtolower($gSpe['title'],'UTF-8') ."</a></li>";
	}
}



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

		if($_SESSION['type']=='mag')
		{
			$typeTitle="Leclerc";
			$nom=$_SESSION['nom'];
		}
		else
		{
			$typeTitle="";
			$nom="Bienvenue " .$_SESSION['nom'].',';
		}
		//---------------------------
		//stats
		//---------------------------
		//si action est vide, le user vient de se connecté
		$action = (empty($action)) ? "connexion mag" : $action;
		addRecord($pdoStat,$page,$action, $descr);
	}
	elseif($_SESSION['type']=='btlec')
	{
		$typeTitle="";
		$nom=$_SESSION['nom_bt'];
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
		$nom="Bienvenue,";
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
		$nom="Bienvenue,";
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
/*--------------------------------------------------*/
/*        reversements                              */
/*            => si info moins de 7 jours afficher*/
/*--------------------------------------------------*/



function rev($pdoBt)
{
	$today=date('Y-m-d H:i:s');
	$todayMoinsSept=date_sub(date_create($today), date_interval_create_from_date_string('7 days'));
	$todayMoinsSept=date_format($todayMoinsSept,'Y-m-d H:i:s');

	$req=$pdoBt->prepare("SELECT divers, date_rev,doc_type.name, id_type, DATE_FORMAT(date_rev,'%d/%m/%Y') as date_display FROM reversements LEFT JOIN doc_type ON reversements.id_type = doc_type.id WHERE date_rev >= :todayMoinsSept AND date_rev <= :today ");
	$req->execute(array(
		':todayMoinsSept' =>$todayMoinsSept,
		':today'	=>$today
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$revRes=rev($pdoBt);
$info=[];
if(!empty($revRes))
{
	$infoRev='<div class="info"><li class="orange-text text-darken-2"><i class="fa fa-info-circle fa-2x" aria-hidden="true"></i> Nouveaux documents déposés : </li></div><div class="detail orange-text text-darken-2">';
	foreach ($revRes as $key => $thisRev)
	{
		if($thisRev['id_type']==18)
		{
			$infoRev.='<br> >> ' .$thisRev['divers'] . ' du ' .$thisRev['date_display'];

		}
		else
		{
			$infoRev.='<br> >> ' .$thisRev['name'] . ' du ' .$thisRev['date_display'] ;
		}
	}
	$infoRev.='</div>';
}

function getFlashNews($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM flash WHERE valid=1 AND date_start <= :today AND date_end >= :today ORDER BY id DESC");
	$req->execute(array(
		':today'		=>date('Y-m-d 00:00:00')
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$flashNews=getFlashNews($pdoBt);

$flashFilesDir='..\..\..\upload\flash\\';


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
