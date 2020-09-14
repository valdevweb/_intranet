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







function getSumValo($pdoLitige, $listLitige){
	$valoTotal=0;
	foreach ($listLitige as $key => $litige) {
		$valoTotal+=$litige['valo'];
	}
	return $valoTotal;
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







include 'bt-litge-encours-formsearch-ex.php';
include 'bt-litige-encours-filtres-ex.php';
include 'bt-litige-encours-sessions-ex.php';






// 3- requete grace à paramList si il existe ou
// requete par défaut


if(!isset($paramList)){
	$listLitige=getListLitige($pdoLitige);

	$dateStart=(new DateTime('first day of january this year'))->format('Y-m-d H:i:s');
	$dateEnd=date('Y-m-d H:i:s');


	$queryStats="SELECT  sum(valo) as valo, dossiers.id_etat, etat.etat, count(dossiers.id) as nbEtat FROM dossiers
	LEFT JOIN etat ON id_etat=etat.id
	WHERE date_crea BETWEEN '$dateStart' AND '$dateEnd' GROUP BY etat";
	$req=$pdoLitige->query($queryStats);

	$valoEtat=$req->fetchAll(PDO::FETCH_ASSOC);
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

	$queryStats="SELECT  sum(valo) as valo, dossiers.id_etat, etat.etat, count(dossiers.id) as nbEtat,	magasin.mag.deno, magasin.mag.centrale, magasin.mag.id as btlec  FROM dossiers
	LEFT JOIN etat ON id_etat=etat.id
	LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec
	WHERE $params GROUP BY etat";
	$req=$pdoLitige->query($queryStats);
	$valoEtat=$req->fetchAll(PDO::FETCH_ASSOC);
}
$arCentrale=getListCentrale($pdoMag);
$nbLitiges=count($listLitige);
// apparemment le moins gourmand est de parcourir le tableau avec un foreach (un array_map ferait lui aussi une boucle or ce serait moins rapide qu'une vraie boucle
// https://stackoverflow.com/questions/16138395/sum-values-of-multidimensional-array-by-key-without-loop


//  la valoTotale
$valoTotalDefault=getSumValo($pdoLitige, $listLitige);
$valoTotalEtat=getSumValo($pdoLitige, $valoEtat);

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


	<?php include ('bt-litige-encours-stats.php') ?>
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