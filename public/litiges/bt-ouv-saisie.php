<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


require '../../Class/MagDao.php';
require '../../Class/Mag.php';

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


function searchEan($pdoQlik, $ean)
{
	$req=$pdoQlik->prepare("SELECT id, `GESSICA.CodeArticle` as article,`GESSICA.CodeDossier` as dossier,`GESSICA.PANF` as panf,`GESSICA.PFNP` as pfnp,`GESSICA.LibelleArticle` as descr, `GESSICA.PCB` as pcb,`GESSICA.NomFournisseur` as fournisseur,`GESSICA.Gencod` as ean FROM basearticles WHERE `GESSICA.Gencod` LIKE :ean");
	$req->execute(array(
		':ean'	=>'%'.$ean.'%'
	));
	return  $req->fetchAll(PDO::FETCH_ASSOC);
}
//  on renomme les champs pour avoir les même que dans la recherche ean et pouvoir utiliser le même tableau pour la sélection de produits
function searchPalette($pdoQlik){
	$req=$pdoQlik->prepare("SELECT *, gencod as ean, qte as pcb, tarif as panf , libelle as descr FROM statsventeslitiges  WHERE concat( concat('0',facture),palette) LIKE :search  ORDER BY article,dossier");
	$req->execute(array(
		':search' =>'%'.$_POST['search_strg'] .'%',

	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getMagIdwebuser($pdoUser){
	$req=$pdoUser->prepare("SELECT id FROM users WHERE galec=:galec");
	$req->execute(array(
		':galec'		=>$_GET['galec']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function insertDossier($pdoLitige, $numDossier,$magId){
	// par défaut l'état est à 0 = ouvert
	if(isset($_POST['rapid']) && $_POST['rapid']=="oui")
	{
		$vingtquatre=1;
	}
	else{
		$vingtquatre=0;
	}
	if(isset($_POST['date_bt']) && !empty($_POST['date_bt']))
	{
		$dateDecl=$_POST['date_bt'];
	}
	else
	{
		$dateDecl=	date('Y-m-d H:i:s');
	}
	$req=$pdoLitige->prepare("INSERT INTO dossiers_temp(date_crea,user_crea,nom,galec,id_web_user,vingtquatre, dossier) VALUES(:date_crea,:user_crea,:nom, :galec, :id_web_user, :vingtquatre, :dossier)");
	$req->execute(array(
		':date_crea'		=>$dateDecl,
		':user_crea'		=>$_SESSION['id_web_user'],
		':nom'				=>$_POST['nom'],
		':galec'			=>$_GET['galec'],
		':id_web_user'		=>$magId,
		':vingtquatre'		=>$vingtquatre,
		':dossier'		=>$numDossier,
	));
	return $pdoLitige->lastInsertId();
	 // return $req->errorInfo();

}



// function updateDossier($pdoLitige,$numDossier, $lastInsertId)
// {
// 	$req=$pdoLitige->prepare("UPDATE dossiers SET dossier= :dossier WHERE id= :id");
// 	$req->execute(array(
// 		':dossier'		=>$numDossier,
// 		':id'			=>$lastInsertId
// 	));
// 	$row=$req->rowCount();
// 	return	$row;
// }


function getSelectedDetailsBaseArticle($pdoQlik,$id)
{
	$req=$pdoQlik->prepare("SELECT id, `GESSICA.CodeArticle` as article,`GESSICA.CodeDossier` as dossier,`GESSICA.PANF` as tarif,`GESSICA.PFNP` as pfnp,`GESSICA.LibelleArticle` as libelle, `GESSICA.PCB` as qte,`GESSICA.NomFournisseur` as fournisseur,`GESSICA.Gencod` as gencod,`GESSICA.CodeFournisseur` as cnuf  FROM basearticles WHERE id= :id");
	$req->execute(array(
		':id'	=>$id

	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getSelectedDetailsBaseLitige($pdoQlik,$id)
{
	$req=$pdoQlik->prepare("SELECT * FROM statsventeslitiges WHERE id= :id");
	$req->execute(array(
		':id'	=>$id

	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function addDetails($pdoLitige, $lastInsertId,$numDossier, $article, $ean,$dossierG, $descr, $qteC,	$tarif, $fou, $cnuf, $palette, $facture, $dateFact)
{
	$req=$pdoLitige->prepare("INSERT INTO details_temp(id_dossier, dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, fournisseur, cnuf) VALUES(:id_dossier, :dossier, :palette, :facture, :date_facture, :article, :ean, :dossier_gessica, :descr, :qte_cde, :tarif, :fournisseur, :cnuf)");
	$req->execute(array(
		':id_dossier'	=>$lastInsertId,
		':dossier'		=>$numDossier,
		':palette'		=>$palette,
		':facture'		=>$facture,
		':date_facture'	=>$dateFact,
		':article'		=>$article,
		':ean'			=>$ean,
		':dossier_gessica'	=>$dossierG,
		':descr'		=>$descr,
		':qte_cde'		=> $qteC,
		':tarif'		=>$tarif,
		':fournisseur'	=>$fou,
		':cnuf'			=>$cnuf

	));
	$row=$req->rowCount();
	return	$row;
	// return $req->errorInfo();

}



// le numéro de dossier du litige et non l'id du litige
function getLastNumDossier($pdoLitige){
	$req=$pdoLitige->prepare("SELECT dossier FROM dossiers ORDER BY dossier DESC LIMIT 1");
	$req->execute();
	return $req->fetch(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}



//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$ids=[];

//  la saisie libre peut venir de bt (declaration-bt-basic) ou d'un magasin (/bt-ouvertures.php)
//  on a aussi 2 type de recherche et donc 2 formulaires qui vont permettre l'affichage des articles :
//   recherche par palette (submit-palette)
//   recherche par code article (submit)
//   => les articles affichés proviennent
//   l'affichage des articles est identique pour les 2 cas, en revanche, la récupération des infos article à la soumission du formulmaire (choose)
//   est différente (base article ou base litige + info sur facture, palette, date fac présente ou non)
if(!isset($_GET['id_ouv']) && !isset($_GET['palette'])){
	$formaction=htmlspecialchars($_SERVER['PHP_SELF']).'?galec='.$_GET['galec'];
}elseif(!isset($_GET['id_ouv']) && isset($_GET['palette'])){
	$formaction=htmlspecialchars($_SERVER['PHP_SELF']).'?galec='.$_GET['galec'].'&palette=true';

}elseif(isset($_GET['id_ouv']) && isset($_GET['palette'])){
	$formaction=htmlspecialchars($_SERVER['PHP_SELF']).'?id_ouv='.$_GET['id_ouv'].'&galec='.$_GET['galec'].'&palette=true';

}
else{
	// id-ouv mais pas palette
	$formaction=htmlspecialchars($_SERVER['PHP_SELF']).'?id_ouv='.$_GET['id_ouv'].'&galec='.$_GET['galec'];
}

// 8806098019618
// 4897008076382

$magDao=new MagDao($pdoMag);
$infoMag=$magDao->getMagByGalec($_GET['galec']);

$magtxt="<span class='text-reddish'>pour ".$infoMag->getDeno()."</span>";

if(isset($_POST['submit']))
{

	$eanAr=str_replace(' ','',$_POST['search_ean']);
	$eanAr=explode(',',$eanAr);
	$nbEan=count($eanAr);
	$foundProd=[];

	for ($i=0; $i < $nbEan ; $i++)
	{
		$found=searchEan($pdoQlik,$eanAr[$i]);
		for ($y=0; $y <count($found) ; $y++)
		{
			array_push($foundProd,$found[$y]);
		}
	}

}

if(isset($_POST['submit-palette'])){
	$foundProd=searchPalette($pdoQlik);
}



if(isset($_POST['choose'])){

	$magId=getMagIdwebuser($pdoUser);
	$magId=$magId['id'];

//  on ne veut récuperer que les id donc on supprime les valeurs des champ submit, date, etc
	foreach ($_POST as $key => $value){
		if($key!='choose' && $key!='rapid' && $key !='nom' && $key !='date_bt' && $key != 'num_dossier_form' )
		{
			$ids[]=$key;
		}
	}


// 1- creation du dossier
	if(count($ids)>0)
	{

		if(!empty($_POST['num_dossier_form']))
		{
			// le numéro de dossier sera ecrasé au moment de la recopie de la table temporaire vers la table active
			// on mémorise donc le numéro de dossier dans une variable session
			$_SESSION['dossier_litige']=$_POST['num_dossier_form'];
			$numDossier=9999;
			$lastInsertId=insertDossier($pdoLitige,$numDossier, $magId);
		}
		else
		{

			// si pas de numéro de dossier imposé, on prend le der num et on ajoute 1
			$numDossier=9999;

			$lastInsertId=insertDossier($pdoLitige,$numDossier, $magId);

		}
		// créa du dossier (sans numéro pour l'instant)
		if($lastInsertId>0)
		{
			$sucess[]="ajout du dossier réussie";
		}
		else
		{
			$errors[]="Impossible de créer le dossier";

		}
	}
	else
	{
		$errors[]="Merci de sélectionner au moins une ligne";
	}
// 2- ajout des ref art, num, num fac, date fac, n°palette, qte originale, num dossier,gencod, id_web_user, btlec, galec, deno
	if(count($errors)==0)
	{

		$added=0;
		$nbArticle=count($ids);
		for ($i=0; $i <$nbArticle ; $i++){
			//  on a 2 cas => recherche par palette ou recherche par ean
			//  si ean => basearticle
			//  si palette => statsventelitige
			//  on sait que c'est une echerhce palette si on a une querystring palette (poussé par le formulaire recherche palette)
			if(isset($_GET['palette'])){
					echo "<pre>";
					print_r($ids);
					echo '</pre>';

				$art=getSelectedDetailsBaseLitige($pdoQlik,$ids[$i]);
				$palette=$art['palette'];
				$facture=$art['facture'];
				$dateFact=$art['date_mvt'];

			}else{
				$art=getSelectedDetailsBaseArticle($pdoQlik, $ids[$i]);
				$palette=0;
				$facture=0;
				$dateFact='1900-01-01 00:00:00';
			}
			// $dateFact=date('Y-m-d H:i:s',strtotime($art['date_mvt']));
// vérif getSeletect art retour
			$tarif=$art['tarif']*$art['qte'];
			$detail=addDetails($pdoLitige, $lastInsertId,$numDossier, $art['article'], $art['gencod'],$art['dossier'], $art['libelle'], $art['qte'],$tarif, $art['fournisseur'], $art['cnuf'], $palette, $facture, $dateFact);



			if($detail>0)
			{
				$added++;
			}
			else{
				$errors[]="erreur à l'enregistrement";
			}

		}
		if($added>0)
		{
			// si la délcaration provient d'une demande magasin, on memorise l'id de cette demande pour ensuite pouvoir mettre ç jour la demande
			if(isset($_GET['id_ouv'])){
				unset($_SESSION['dd_ouv']);
				$_SESSION['dd_ouv']=$_GET['id_ouv'];

			}
			header('Location:declaration-steptwo.php?id='.$lastInsertId);
		}
	}
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
	<h1 class="text-main-blue py-5 ">Saisie libre <?=$magtxt?></h1>

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
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col bg-alert bg-alert-primary">
			<h5>Recherche par ean</h5>
			<form method="post" action="<?=$formaction?>" id="search">
				<div class="row pb-3">
					<div class="col">
						<p>EAN du produit ou des produits (merci de séparer les EAN par une virgule): </p>
					</div>
				</div>
				<!-- ./row -->

				<div class="row pl-5">
					<div class="col">
						<div class="form-group">
							<input type="text" class="form-control" name="search_ean" required>
						</div>
					</div>
					<div class="col">
						<p class="text-left"><button class="btn btn-primary" type="submit" name="submit">Rechercher</button></p>
						<div id="waitun"></div>
					</div>
				</div>
			</form>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>

	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col bg-alert bg-alert-primary">
			<h5>Recherche par palette / facture</h5>
			<!--  on force le parametre palette pour les autres formulaires -->
			<form method="post" action="<?=$_SERVER['PHP_SELF'].'?id_ouv='.$_GET['id_ouv'].'&galec='.$_GET['galec'].'&palette=true'?>" id="search">
				<div class="row pb-3">
					<div class="col">
						<p>Numéro de facture ou de palette </p>
					</div>
				</div>
				<!-- ./row -->

				<div class="row pl-5">
					<div class="col">
						<div class="form-group">
							<input type="text" class="form-control" name="search_strg" required>
						</div>
					</div>
					<div class="col">
						<p class="text-left"><button class="btn btn-primary" type="submit" name="submit-palette">Rechercher</button></p>

					</div>
				</div>
			</form>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>



	<?php
	ob_start();
	?>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">1</span>Résultat de la recherche :</p>

			<form method="post" action="<?=$formaction?>" id="submit">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th>Article</th>
							<th>Dossier</th>
							<th>EAN</th>
							<th>PCB</th>
							<th>PANF</th>
							<th>Désignation</th>
							<th>Fournisseur</th>
							<th><i class="fas fa-times-circle"></i></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(!empty($foundProd)){
							foreach ($foundProd as $prod)
							{
								echo '<tr>';
								echo '<td>'.$prod['article'].'</td>';
								echo '<td>'.$prod['dossier'].'</td>';
								echo '<td>'.$prod['ean'].'</td>';
								echo '<td>'.$prod['pcb'].'</td>';
								echo '<td>'.$prod['panf'].'</td>';
								echo '<td>'.$prod['descr'].'</td>';
								echo '<td>'.$prod['fournisseur'].'</td>';
								echo '<td>';
								echo '<div class="form-check article"><input class="form-check-input" type="checkbox" name="'.$prod['id'].'"></div>';
								echo '</td>';



								echo '</tr>';
							}
						}
						else
						{
							echo '<tr>';
							echo '<td colspan="8">Aucun résultat trouvé. Souhaitez vous saisir la totalité des informations</td>';
							echo '</tr>';


						}
						?>

					</tbody>
				</table>
				<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">2</span>S'agit-il d'une livraison 24/48h ?</p>
				<div class="alert alert-light">
					<div class="form-check">
						<input class="form-check-input" type="radio" name="rapid" value="oui" id="rapidoui">
						<label class="form-check-label" for="rapidoui">Oui</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="rapid" value="non" id="rapidnon">
						<label class="form-check-label" for="rapidnon">Non</label>
					</div>
				</div>
				<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">3</span>Nom de l'interlocuteur</p>
				<div class="form-group">
					<input type="text" class="form-control"  name="nom" required>
				</div>
				<div class="row">
					<div class="col">
						<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">4</span>Date de déclaration</p>
					</div>
					<div class="col">
						<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">5</span>Numéro de dossier : </p>
					</div>
				</div>
				<div class="row">
					<div class="col-4">
						<div class="alert alert-light ">
							<div class="form-group pt-2">
								<input type="date" class="form-control" name="date_bt">
							</div>
						</div>
					</div>
					<div class="col-2"></div>
					<div class="col-4">
						<div class="alert alert-light ">
							<div class="form-group pt-2">
								<input type="text" class="form-control" name="num_dossier_form">
							</div>
						</div>

					</div>
				</div>

				<p class="text-right"><button class="btn btn-primary" type="submit" name="choose">Sélectionner</button></p>
			</form>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<?php
	$resultList=ob_get_contents();
	ob_end_clean();
	if(isset($_POST['submit']) || isset($_POST['submit-palette']))
	{
		echo $resultList;
	}
	?>

	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>