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
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getThisLitige($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT galec, dossiers.dossier,
		id_reclamation, article, descr, ean, fournisseur, qte_litige, pj, inv_palette, palette, inv_article, inv_qte, inv_descr, inv_tarif, inv_fournisseur, inversion,
		reclamation
		FROM dossiers
		LEFT JOIN details ON dossiers.id= details.id_dossier
		LEFT JOIN reclamation ON id_reclamation=reclamation.id
		WHERE dossiers.id= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


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

function getDial($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM dial WHERE id_dossier= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$thisLitige=getThisLitige($pdoLitige);
$dials=getDial($pdoLitige);

$errors=[];
$success=[];



if($thisLitige[0]['galec'] !=$_SESSION['id_galec'])
{
	header('Location:notyours.php');

}









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
	<h1 class="text-main-blue py-5 ">Dossier litige n°<?=$thisLitige[0]['dossier']?></h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row">
		<div class="col">
			<?php
		// affiche soit le tableau de detail des produits soit le tableau d'inversion de palette
			if($thisLitige[0]['id_reclamation']==7)
			{
				include('dt-mag-palette.php');
			}
			else
			{
				include('dt-mag-prod.php');
			}
			?>
		</div>
	</div>
	<div class="bg-separation"></div>
	<div class="row mt-5">
		<div class="col">
					<h5 class="khand text-main-blue pb-3">Echanges avec BTLec : </h5>
		</div>
	</div>
	<div class="row">
		<div class="col">



			<?php
			if(empty($dials))
			{
				echo '<p class="text-center">Aucun message n\'a été échangé avec BTLec</p>';
			}
			else
			{
				foreach($dials as $dial)
				{
					if($dial['mag']==1)
					{
						$bgColor='alert-primary';
					}
					else
					{
						$bgColor='alert-warning';

					}
					$pj='';
					if($dial['filename']!='')
					{
						$pj=createFileLink($dial['filename']);
					}
					echo '<div class="row alert '.$bgColor.'">';
					echo '<div class="col">';
					echo '<div class="text-right"><i class="far fa-calendar-alt pr-3"></i>'.$dial['date_saisie'] .'</div>';
					echo $dial['msg'];
					echo '<div class="text-right">'.$pj .'</div>';

					echo '</div>';
					echo '</div>';
				}

			}
			 ?>


		</div>
	</div>

	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>