<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
if($_SESSION['id']==1531){
	header('Location:../gtocc/offre-produit.php');
}
require '../../Class/Db.php';
require '../../config/db-connect.php';

//----------------------------------------------------------------
//	css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

require "../../functions/form.fn.php";
require "../../functions/gazette.fn.php";
require "../../functions/stats.fn.php";
require_once '../../Class/OpportuniteDAO.php';
require_once '../../Class/litiges/LitigeDialDao.php';
require_once '../../Class/UserHelpers.php';
require_once '../../Class/UserDao.php';
require_once '../../Class/FlashDao.php';
require '../../Class/GazetteDao.php';

$db=new Db();
$pdoUser=$db->getPdo('web_users');

$pdoStat=$db->getPdo('stats');
$pdoBt=$db->getPdo('btlec');
$pdoLitige=$db->getPdo('litige');
$pdoDAchat=$db->getPdo('doc_achats');

function rev($pdoBt){
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


$errors=[];
$success=[];
$oppDao=new OpportuniteDAO($pdoBt);
$flashDao=new FlashDao($pdoBt);
$dialDao=new LitigeDialDao($pdoLitige);
$userDao=new UserDao($pdoUser);
$gazetteDao=new GazetteDao($pdoDAchat);

$droitExploit=$userDao->isUserAllowed([5]);




$nbRecup=$userDao->getNbPwd();
$nbCompte=$userDao->getNbCompte();

$listActiveOpp=$oppDao->getActiveOpp();

$newDialLitige=$dialDao->getUnreadDossier();

// $gazettes=showThisWeek($pdoBt);

// $links=createLinks($pdoBt,$gazettes);
$listFlashBt=$flashDao->getListFlashBySite((new DateTime())->format('Y-m-d'),'portail_bt');

$catBt=$gazetteDao->getCatByMain(1);
$catGalec=$gazetteDao->getCatByMain(2);
$mainCat=[1 =>"btlec", 2 =>"galec"];

$listCat=$gazetteDao->getCat();
$listGazette=$gazetteDao->getGazetteThisWeek();
$gazetteDate="";
if(!empty($listGazette)){
	$listFiles=[];
	$listLinks=[];
	$listFiles=$gazetteDao->getFilesEncours();
	$listLinks=$gazetteDao->getLinkEncours();
}



//stats
$descr="page d'accueil";
$page=basename(__file__);
$action="";
if(!empty($_SERVER['HTTP_REFERER'])){
	if((parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) !='/'. VERSION .'btlecest/index.php')){
		$action="retour sur la page d'accueil";
	}
}

//personnalisation des sessions et récup de données utilisateur
if($_SESSION['id_type']==2 || $_SESSION['id_type']==5){

	if($_SESSION['id_type']==2){
		$typeTitle="Bienvenue Leclerc";
		$nom=$_SESSION['nom'];
	}else{
		$typeTitle="";
		$nom="Bienvenue " .$_SESSION['nom'].',';
	}

		//si action est vide, le user vient de se connecté
	$action = (empty($action)) ? "connexion mag" : $action;
	addRecord($pdoStat,$page,$action, $descr);
}
elseif($_SESSION['id_type']==1){
	$typeTitle="Bienvenue";
	$nom=$_SESSION['nom'];
	$action = (empty($action)) ? "connexion BT" : $action;
	addRecord($pdoStat,$page,$action, $descr);
}
elseif ($_SESSION['id_type']==3){
	$typeTitle="";
	$nom="Bienvenue,";
		//si action est vide, le user vient de se connecté
	$action = (empty($action)) ? "connexion scapsav" : $action;
	addRecord($pdoStat,$page,$action, $descr);
}else{
		// si ni de type mag, ni de type bt, ni scapsav
	$typeTitle="";
	$nom="Bienvenue,";
	$action = (empty($action)) ? "connexion non mag - non BT" : $action;
	addRecord($pdoStat,$page,$action, $descr);
}


// redirection si besoin
if(!empty($_SESSION['goto'])){
		//si on a une query string, on la découpe et on vérif si la 1er partie est numerique ou pas
		//si 1ere partie numérique, c'est un vieux lien donc on redirige sur page edit-msg(mag) ou page answer(btlec)
		//sinon on recupère toute la query string
	$goto=$_SESSION['goto'];
	$redir=explode("&",$goto);
	if(is_numeric($redir[0])){
		if($_SESSION['id_type']==1){
			header('Location:'. ROOT_PATH. '/public/btlec/answer.php?msg='.$_SESSION['goto']);
		}elseif($_SESSION['id_type']==2 || $_SESSION['id_type']==3 || $_SESSION['id_type']==4 || $_SESSION['id_type']==5){
			header('Location:'. ROOT_PATH. '/public/mag/edit-msg.php?msg='.$_SESSION['goto']);
		}else{
			header('Location:' .ROOT_PATH. '/public/' .$goto);
		}
	}else{
		if(str_contains($_SESSION['goto'], "workflow") == 1 ){

			if( $_SESSION['id'] == 1053 || $_SESSION['id'] == 1895 || $_SESSION['id'] == 974 || $_SESSION['id'] == 687 || $_SESSION['id'] == 968 ){
				
				header('Location:'. ROOT_PATH. '/public/workflow/indexutilisateur.php');
			}else{
			
					header('Location:'. ROOT_PATH. '/public/workflow/index.php');
			
				
			}
		}else{
			header('Location:' .ROOT_PATH. '/public/' .$goto);
		}
	}

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


// on réinitialise les filtres mémorisés par la page base mag
if(isset($_SESSION['mag_filters'])){
	unset($_SESSION['mag_filters']);
}



if(isset($_GET['access-denied'])){
	$errors[]="Vous avez été redirigé ici car vos droits d'accès ne vous permettent pas de consulter la page demandée";
}





include('../view/_head-bt.php');
include ('../view/_navbar.php');

//contenu
include('home.ct.php');

?>
<script type="text/javascript">

	$(document).ready(function() {


		$('div.more').hide();
		$('.show-link').on("click", function(){
			var id= $(this).data("gazette-id");
			if($('div[data-content-id="'+id+'"]').is(":visible")){
				$('div[data-content-id="'+id+'"]').hide();

			}else{
				$('div[data-content-id="'+id+'"]').show();
			}
		});

	});

</script>

<!-- <script src="js/slider.js"></script> -->
<?php
require '../view/_footer-bt.php';
?>
