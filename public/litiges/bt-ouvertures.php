<?php

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

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// $descr="saisie déclaration mag hors qlik" ;
// $page=basename(__file__);
// $action="";
// // addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
// addRecord($pdoStat,$page,$action, $descr, 208);



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getWaiting($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT ouverture.id, DATE_FORMAT(date_saisie, '%d-%m-%Y') as datesaisie, msg, pj, mag, btlec FROM ouverture LEFT JOIN btlec.sca3 ON ouverture.galec=btlec.sca3.galec WHERE etat=0 ORDER BY date_saisie");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$waiting=getWaiting($pdoLitige);

function createFileLink($filelist)
{
	$rValue='';
	$filelist=explode(';',$filelist);

	for ($i=0; $i < count($filelist); $i++)
	{
		if($filelist[$i] !="")
		{
			$rValue.='<a href="'.UPLOAD_DIR.'/litiges/'.$filelist[$i].'" class="link-main-blue"><span class="pr-3"><i class="fas fa-link"></i></span></a>';

		}
	}
	return $rValue;
}



//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

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
	<h1 class="text-main-blue py-5 ">Demandes d'ouvertures de dossiers</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
			<div class="spinner-border" role="status">
  <span class="sr-only">Loading...</span>
</div>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<?php
	foreach ($waiting as $wait)
	{
		$pj='';
		if(!empty($wait['pj']))
		{
			$pjtemp=createFileLink($wait['pj']);
			$pj='Pièce jointe : <span class="pr-3">'.$pjtemp .'</span>';
		}

	echo '<div class="row">';
		echo '<div class="col alert alert-primary">';
			echo '<div class="row">';
				echo '<div class="col">';
					echo $wait['btlec'].'-'.$wait['mag'];
				echo '</div>';
				echo '<div class="col text-right">';
					echo 'date de la demande : '.$wait['datesaisie'];
				echo '</div>';
			echo '</div>';
			echo '<div class="row">';
				echo '<div class="col border-top-blue">';
					echo $wait['msg'];
				echo '</div>';
			echo '</div>';
			echo '<div class="row pt-3">';
				echo '<div class="col">';
					echo $pj;
				echo '</div>';

			echo '</div>';
			echo '<div class="row">';
				echo '<div class="col text-right">';
					echo '<a href="bt-ouv-traitement.php?id='.$wait['id'].'" class="btn btn-primary">Répondre</a>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}

	?>



	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>