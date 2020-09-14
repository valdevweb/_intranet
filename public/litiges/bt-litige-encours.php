<?php
// session_cache_limiter('private_no_expire');
 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";



require('../../Class/FormHelpers.php');




//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function getEtat($pdoLitige){
	$req=$pdoLitige->prepare("SELECT etat,id FROM etat WHERE mask=0 ORDER BY etat");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getListLitige($pdoLitige){
	$query="SELECT dossiers.id as id_main, dossiers.dossier, DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea, dossiers.galec, dossiers.etat_dossier, dossiers.esp,
	dossiers.vingtquatre, dossiers.valo, dossiers.ctrl_ok, dossiers.commission,	magasin.mag.deno, magasin.mag.centrale,  magasin.mag.id as btlec, etat.etat	FROM dossiers
	LEFT JOIN etat ON id_etat=etat.id
	LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec WHERE
	id_etat != :id_etat AND commission != :commission
	ORDER BY dossiers.dossier DESC";
	echo $query;

	$req=$pdoLitige->prepare($query);
	$req->execute([
		':id_etat'=>1,
		':commission'	=>1,

	]);
	// return $req->errorInfo();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}



function getListCentrale($pdoMag){
	$req=$pdoMag->query("SELECT id_ctbt, centrale FROM centrales");
	return $req->fetchAll(PDO::FETCH_KEY_PAIR);
}







// function getSumValo($pdoLitige)
// {
// 	// $strg=isset($_POST['search_strg']) ? $_POST['search_strg'] :'';
// 	$strg=empty($_SESSION['form-data']['search_strg']) ? '': $_SESSION['form-data']['search_strg'];

// 	if(!empty($_SESSION['form-data']['etat']))
// 	{
// 		$reqEtat= ' AND id_etat= ' .$_SESSION['form-data']['etat'];
// 	}
// 	else
// 	{
// 		$reqEtat='';
// 	}
// 	if(!empty($_SESSION['filter-data']['pending'])){
// 		if($_SESSION['filter-data']['pending']=='pending'){
// 			$reqCommission= ' AND commission !=1';
// 		}
// 		else{
// 			$reqCommission= ' AND commission =' .intval($_SESSION['filter-data']['pending']);

// 		}
// 	}
// 	else{
// 		$reqCommission='';
// 	}

// 	if(isset($_SESSION['filter-data']['vingtquatre'])){
// 		if($_SESSION['filter-data']['vingtquatre']==1){
// 			$reqLivraison= ' AND vingtquatre=1 ';
// 		}elseif($_SESSION['filter-data']['vingtquatre']==0){
// 			$reqLivraison= ' AND vingtquatre='.intval(0);
// 		}
// 	}
// 	else{
// 		$reqLivraison= ' AND vingtquatre is NOT NULL';
// 	}
// 	if(isset($_SESSION['form-data']['esp'])){
// 		if($_SESSION['form-data']['esp']==1){
// 			$reqLivraisonEsp= ' AND esp=1 ';
// 		}elseif($_SESSION['form-data']['esp']==0){
// 			$reqLivraisonEsp= ' AND esp='.intval(0);
// 		}
// 	}
// 	else{
// 		$reqLivraisonEsp= ' AND esp is NOT NULL';
// 	}


// 	$req=$pdoLitige->prepare("SELECT  sum(dossiers.valo) as valo_totale
// 		FROM dossiers
// 		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
// 		-- LEFT JOIN details ON dossiers.id=details.id_dossier

// 		WHERE date_crea BETWEEN :date_start AND :date_end AND concat(dossiers.dossier,mag,dossiers.galec,sca3.btlec)
// 		LIKE :search $reqEtat $reqCommission $reqLivraison $reqLivraisonEsp");


// 	$req->execute(array(
// 		':search' =>'%'.$strg.'%',
// 		':date_start'=>$_SESSION['form-data']['date_start']. ' 00:00:00',
// 		':date_end'	=>$_SESSION['form-data']['date_end'].' 23:59:59',

// 	));
// 	return $req->fetch(PDO::FETCH_ASSOC);
// }
function test($pdoLitige){
	$dateStart="2020-01-01 00:00:00";
	$dateEnd="2020-09-11 00:00:00";

	$req=$pdoLitige->prepare("SELECT dossiers.id as id_main, dossiers.dossier, DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea, dossiers.galec, dossiers.etat_dossier, dossiers.esp,
		dossiers.vingtquatre, dossiers.valo, dossiers.ctrl_ok, dossiers.commission,	magasin.mag.deno, magasin.mag.centrale,  magasin.mag.id as btlec, etat.etat	FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec WHERE
		date_crea BETWEEN :date_start AND :date_end

		ORDER BY dossiers.dossier DESC");

	$req->execute([
		':date_start'=>$dateStart,
		':date_end'	=>$dateEnd,

	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}



function getSumValoByType($pdoLitige)
{
	$strg=empty($_SESSION['form-data']['search_strg']) ? '': $_SESSION['form-data']['search_strg'];

	if(!empty($_SESSION['form-data']['etat']))
	{
		$reqEtat= ' AND id_etat= ' .$_SESSION['form-data']['etat'];
	}
	else
	{
		$reqEtat='';
	}
	if(!empty($_SESSION['filter-data']['pending'])){
		if($_SESSION['filter-data']['pending']=='pending'){
			$reqCommission= ' AND commission !=1';
		}
		else{
			$reqCommission= ' AND commission =' .intval($_SESSION['filter-data']['pending']);

		}
	}
	else{
		$reqCommission='';
	}

	if(isset($_SESSION['filter-data']['vingtquatre'])){
		if($_SESSION['filter-data']['vingtquatre']==1){
			$reqLivraison= ' AND vingtquatre=1 ';
		}elseif($_SESSION['filter-data']['vingtquatre']==0){
			$reqLivraison= ' AND vingtquatre='.intval(0);
		}
	}
	else{
		$reqLivraison= ' AND vingtquatre is NOT NULL';
	}
	if(isset($_SESSION['form-data']['esp'])){
		if($_SESSION['form-data']['esp']==1){
			$reqLivraisonEsp= ' AND esp=1 ';
		}elseif($_SESSION['form-data']['esp']==0){
			$reqLivraisonEsp= ' AND esp='.intval(0);
		}
	}
	else{
		$reqLivraisonEsp= ' AND esp is NOT NULL';
	}
	$req=$pdoLitige->prepare("SELECT  sum(valo) as valo_etat, dossiers.id_etat, etat.etat, count(dossiers.id) as nbEtat FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		WHERE date_crea BETWEEN :date_start AND :date_end AND concat(dossiers.dossier,mag,dossiers.galec,sca3.btlec) LIKE :search $reqEtat $reqCommission $reqLivraison $reqLivraisonEsp GROUP BY etat");
	$req->execute(array(
		':search' =>'%'.$strg.'%',
		':date_start'=>$_SESSION['form-data']['date_start']. ' 00:00:00',
		':date_end'	=>$_SESSION['form-data']['date_end'].' 23:59:59',

	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// return $req->errorInfo();

}


function updateCommission($pdoLitige,$iddossier, $etat){
	$req=$pdoLitige->prepare("UPDATE dossiers SET commission = :commission, date_commission= :date_commission WHERE id= :id");
	$req->execute([
		':commission'	=>$etat,
		':date_commission'	=>date('Y-m-d H:i:s'),
		':id'		=>$iddossier

	]);
	return $req->rowCount($pdoLitige);
}



function addAction($pdoLitige, $idContrainte){
	$req=$pdoLitige->prepare("INSERT INTO action (id_dossier, libelle, id_contrainte, id_web_user, date_action) VALUES (:id_dossier, :libelle, :id_contrainte, :id_web_user, :date_action)");
	$req->execute([
		':id_dossier'		=>$_POST['iddossier'],
		':libelle'			=>$_POST['cmt'],
		':id_contrainte'	=>$idContrainte,
		':id_web_user'		=>$_SESSION['id_web_user'],
		':date_action'		=>date('Y-m-d H:i:s'),
	]);
	return $req->rowCount();
}


function isAction($pdoLitige,$idLitige,$idContrainte){
	$req=$pdoLitige->prepare("SELECT * FROM action WHERE id_dossier= :id_dossier AND id_contrainte= :id_contrainte");
	$req->execute([
		':id_dossier' => $idLitige,
		':id_contrainte' =>$idContrainte
	]);
	$data=$req->fetch();


	if(empty($data)){
		return false;
	}
	return true;
}


if(isset($_GET['notallowed'])){
	$errors[]="Vous n'êtes pas autorisé à modifier le statut 'validé en commission'";
}

// initialisation


// if(isset($_POST['search_form'])){
// 	foreach ($_POST as $key => $value) {
// 		if($key != 'reset-pending' || $key != 'clear_form' || $key != 'pending' || $key !='vingtquatre' || $key != 'reset-vingtquatre'){
// 			if($value !=''){
// 				$_SESSION['form-data'][$key]=$value;

// 			}
// 		}
// 	}
// }


// 1- traitement des formulaires de recherche
// si un des 2 formulaires est validé, on stock les post en var de sessions
// ce sont elle qui sont vérifiée pour le réaffichage des formulaires
// on reset les var de session du formulaire non validé pour le cas où il aurait été
// utilisé précédemment. Cela permet de ne pas cumuler les critères de recherches
// en revanche les critères de filtres eux sont cumulés aux formulaires
// pour retirer les filtres, l'utilisateur doit cliquer sur le bouton adéquat
// on recharge la page après traitement des posts pour que les var de session soient utilisées par la requete


if(isset($_POST['search_one'])){
	unset($_SESSION['form-data']);
	unset($_SESSION['form-data-deux']);
	// date début
	if (isset($_POST['date_start']) && !empty($_POST['date_start'])) {
		$_SESSION['form-data']['date_start']=$_POST['date_start'];
	}else{
		if(isset($_SESSION['form-data']['date_start'])){
			unset($_SESSION['form-data']['date_start']);
		}
	}

	// date fin
	if (isset($_POST['date_end']) && !empty($_POST['date_end'])) {
		$_SESSION['form-data']['date_end']=$_POST['date_end'];
	}else{
		if(isset($_SESSION['form-data']['date_end'])){
			unset($_SESSION['form-data']['date_end']);
		}
	}

	// etat
	if (isset($_POST['etat']) && !empty($_POST['etat'])) {
		$_SESSION['form-data']['etat']=$_POST['etat'];
	}else{
		if(isset($_SESSION['form-data']['etat'])){
			unset($_SESSION['form-data']['etat']);
		}
	}

	header("Location: ".$_SERVER['PHP_SELF'],true,303);
}

if(isset($_POST['search_two'])){
	unset($_SESSION['form-data']);
	unset($_SESSION['form-data-deux']);

	// article
	// si article est coché, on fait une requete avec une jointure sur la table détail
	if(isset($_POST['article'])){
		$_SESSION['form-data-deux']['article']=true;
	}

	// search_strg
	if(isset($_POST['search_strg']) && !empty($_POST['search_strg'])){
		$_SESSION['form-data-deux']['search_strg']=$_POST['search_strg'];
	}else{
		unset($_SESSION['form-data-deux']['search_strg']);

	}
	header("Location: ".$_SERVER['PHP_SELF'],true,303);
}
if(isset($_POST['clear_form'])){
	unset($_SESSION['form-data']);
	unset($_SESSION['form-data-deux']);
	unset($_SESSION['filter-data']);
	header("Location: ".$_SERVER['PHP_SELF'],true,303);
}

include 'bt-litige-encours-filtres-ex.php';

// $fAllActive=globalSearch($pdoLitige);



// 2- traitement des variables de session
// on compose la requete en fonction des var de session existantes
// 3 grand groupes :
// form-data pour le formulaire 1
// form-data-deux pour le formulaire 2
// filter data pour les filtres
// on ajoute tous les critères de recherche au tableau paramList


if(isset($_SESSION['form-data'])){
	$paramList=[];
	// unset($_SESSION['form-data']);
	unset($_SESSION['form-data-deux']);
	if(isset($_SESSION['form-data']['date_start']) && !empty($_SESSION['form-data']['date_start'])){
		$dateStart=$_SESSION['form-data']['date_start'];
	}else{
		$dateStart="2019-01-01 00:00:00";
	}
	if(isset($_SESSION['form-data']['date_end']) && !empty($_SESSION['form-data']['date_end'])){
		$dateEnd=$_SESSION['form-data']['date_end'];
	}else{
		$dateEnd=date('Y-m-d') ." 00:00:00";
	}
	$paramDate="date_crea BETWEEN '".$dateStart ."'  AND '".$dateEnd."'";
	$paramList[]=$paramDate;


	if(isset($_SESSION['form-data']['etat'])){
		$paramEtat= ' id_etat = '.$_SESSION['form-data']['etat'];
	}else{
		$paramEtat='';
	}
	$paramList[]=$paramEtat;
	// $listLitige=getListLitige($pdoLitige);
}
if (isset($_SESSION['form-data-deux'])) {
	if(isset($_SESSION['form-data-deux']['search_strg']) && !isset($_SESSION['form-data-deux']['article'])){
		$paramStrg= "concat(dossiers.dossier,magasin.mag.deno,dossiers.galec,magasin.mag.id) LIKE '%".$_SESSION['form-data-deux']['search_strg'] ."%'";
	}elseif(isset($_SESSION['form-data-deux']['search_strg']) && isset($_SESSION['form-data-deux']['article'])){
		$paramStrg= "details.article LIKE '%".$_SESSION['form-data-deux']['search_strg'] ."%'";
	}else{
		$paramStrg="";
	}
	$paramList[]=$paramStrg;
}
if(isset($_SESSION['filter-data'])){
	if(isset($_SESSION['filter-data']['vingtquatre']) ){
		$paramVingtQuatre= ' vingtquatre = 1 OR esp = 1';
	}else{
		$paramVingtQuatre= '';
	}
	$paramList[]=$paramVingtQuatre;

	if (isset($_SESSION['filter-data']['pending']) && $_SESSION['filter-data']['pending']==1) {
		$paramCommission= " commission=1 ";
	}elseif (isset($_SESSION['filter-data']['pending']) && $_SESSION['filter-data']['pending']==0){
		$paramCommission= " commission=0 ";
	}else{
		$paramCommission= "";
	}

	$paramList[]=$paramCommission;
}

// 3- requete grace à paramList si il existe ou
// requete par défaut

if(!isset($paramList)){
	$listLitige=getListLitige($pdoLitige);
}else{

	$paramList=array_filter($paramList);

	$joinParam=function($value){
		if(!empty($value)){
			return '('.$value.')';
		}
	};


	$params=join(' AND ',array_map($joinParam,$paramList));

	// 2 requetes types : une sur la table dossier "seule", une sur la table dossier jointe à la table article
	if(isset($_SESSION['form-data-deux']['article'])){
		$query="SELECT dossiers.id as id_main, dossiers.dossier, DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea, dossiers.galec, dossiers.etat_dossier, dossiers.esp,
		dossiers.vingtquatre, dossiers.valo, dossiers.ctrl_ok, dossiers.commission,	magasin.mag.deno, magasin.mag.centrale,  magasin.mag.id as btlec, etat.etat	FROM dossiers
		LEFT JOIN details ON dossiers.id=details.id_dossier
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec WHERE
		$params
		ORDER BY dossiers.dossier DESC";

	}else{
		$query="SELECT dossiers.id as id_main, dossiers.dossier, DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea, dossiers.galec, dossiers.etat_dossier, dossiers.esp,
		dossiers.vingtquatre, dossiers.valo, dossiers.ctrl_ok, dossiers.commission,	magasin.mag.deno, magasin.mag.centrale,  magasin.mag.id as btlec, etat.etat	FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec WHERE
		$params
		ORDER BY dossiers.dossier DESC";
	}

	$req=$pdoLitige->query($query);
	$listLitige=$req->fetchAll(PDO::FETCH_ASSOC);
}
$arCentrale=getListCentrale($pdoMag);
$nbLitiges=count($listLitige);
// $valoTotal=getSumValo($pdoLitige);
// $valoEtat=getSumValoByType($pdoLitige);


include 'bt-litige-encours-statut-ex.php';



$listEtat=getEtat($pdoLitige);
//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">


	<?php include ('bt-litige-encours-header.php') ?>
	<?php include ('bt-litige-encours-formsearch.php') ?>

	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>

		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>


	<?php //include ('bt-litige-encours-stats.php') ?>
	<?php include ('bt-litige-encours-filtres.php') ?>
	<?php include ('bt-litige-encours-table.php') ?>
	<?php include ('bt-litige-encours-statut-modal.php') ?>




	<!-- ./row -->
	<!-- ./row -->




</div>
<script src="../js/sorttable2.js"></script>

<script type="text/javascript">

	$(document).ready(function(){
		var url = window.location + '';
		var splited=url.split("#");
		if(splited[1]==undefined)
		{
			var line='';
		}
		else if(splited.length==2)
		{
			var line=splited[1];
			$("tr#"+line).addClass("anim");
		}

		$('.stamps').on('click',function(){
			var line=$(this).attr("data")
			console.log(line);
			$('#hiddeninput').val(line);
			$('#modal1').css("display","null");
			$('#modal1').removeAttr('aria-hidden');
				// $('#modal1').attr('aria-modal', true);
				$('#cmtarea').focus();
			// $("tr#"+line).addClass("anim");
		});
		$('#annuler').on('click', function(){

			$('#modal1').css("display","hidden");

		});

		$('.unvalidate').on('click', function(){
			return confirm('Etes vous sûrs de vouloir passer le statut du dossier en non statué ?')
		});

	});



</script>

<?php

require '../view/_footer-bt.php';

?>