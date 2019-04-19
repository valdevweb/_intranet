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

// require_once '../../vendor/autoload.php';



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------



function getBox($pdoQlik)
{
	$req=$pdoQlik->prepare("SELECT `SCEBFAST.ART-COD` as article FROM assortiments WHERE `SCEBFAST.DOS-COD` LIKE :dos AND `SCEBFAST.AST-ART` LIKE :art ");
	$req->execute(array(
		':dos'	=>$_GET['searchdoss'],
		':art'	=>$_GET['searchart']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getThisLitigePalette($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT palette,id FROM details WHERE id_dossier= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

$palette=getThisLitigePalette($pdoLitige);

function getBoxProd($pdoQlik, $palette,$article)
{
	$req=$pdoQlik->prepare("SELECT * FROM statsventeslitiges WHERE palette LIKE :palette AND article  LIKE :article");
	$req->execute(array(
		':palette'		=>'%'.$palette.'%',
		':article'		=>$article
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}

if(isset($_GET['searchart']) && isset($_GET['searchdoss']))
{
	$boxArt=getBox($pdoQlik);
	if(!empty($boxArt))
	{
		$sumBox=0;
		$i=0;

		foreach ($boxArt as $art)
		{
			$produit=getBoxProd($pdoQlik, $palette['palette'], $art['article']);
			$_SESSION['box'][$i]['article']=$produit['article'];
			$_SESSION['box'][$i]['dossier']=$produit['dossier'];
			$_SESSION['box'][$i]['libelle']=$produit['libelle'];
			$_SESSION['box'][$i]['fournisseur']=$produit['fournisseur'];
			$_SESSION['box'][$i]['qte']=$produit['qte'];
			$_SESSION['box'][$i]['tarif']=$produit['tarif'];
			$sumBox=$sumBox+$produit['tarif'];

			$i++;
		}
		$_SESSION['boxvalo']=$sumBox;
		$_SESSION['id_detail']=$palette['id'];

	}


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
	<h1 class="text-main-blue py-5 ">Recherche du contenu du box <?=$_GET['searchart'] .' sur le dossier '.$_GET['searchdoss']?></h1>

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
		<div class="col-xl-1"></div>
		<div class="col">

			<?php
			if(isset($_SESSION['box']))
			{
					echo '<p class="heavy text-main-blue">Résultat de la recherche : </p>';
					echo '<table class="table table-striped light-shadow">';
					echo '<thead class="thead-dark">';
					echo '<tr>';
					echo '<th>Article</th>';
					echo '<th>Dossier</th>';
					echo '<th>Désignation</th>';
					echo '<th>Fournisseur</th>';
					echo '<th>Quantité</th>';
					echo '<th>Valo</th>';
					echo '</tr>';
					echo '</thead>';
					echo '<tbody>';

					foreach ($_SESSION['box'] as $produit)
					{

						echo '<tr>';
						echo '<td>'.$produit['article'].'</td>';
						echo '<td>'.$produit['dossier'].'</td>';
						echo '<td>'.$produit['libelle'].'</td>';
						echo '<td>'.$produit['fournisseur'].'</td>';
						echo '<td>'.$produit['qte'].'</td>';
						echo '<td>'.$produit['tarif'].'</td>';
						echo '</tr>';

					}
					echo '<tr>';
					echo '<td colspan="5">Valorisation du box : </td>';
					echo '<td>'.$_SESSION['boxvalo'].'</td>';

					echo '</tr>';
					echo '</tbody>';
					echo '</table>';
					echo '<p>Pour mettre à jour le litige avec les données ci dessus, cliquez sur MAJ litige</p>';
					echo '<p class="text-right"><a href="box-hidden-maj.php?id='.$_GET['id'].'" class="btn btn-primary">MAJ litige</a></p>';
				}
				else
				{
					'<div class="alert alert-warning">Aucun article n\'a été trouvé dans la base</div>';
				}






			?>



		</div>
		<div class="col-x-1"></div>
	</div>


	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>