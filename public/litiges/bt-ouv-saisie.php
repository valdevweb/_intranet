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
function getMagName($pdoBt)
{
	$req=$pdoBt->prepare("SELECT mag FROM sca3 WHERE galec=:galec");
	$req->execute(array(
		':galec'		=>$_GET['galec']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}


function searchEan($pdoQlik, $ean)
{
	$req=$pdoQlik->prepare("SELECT id, `GESSICA.CodeArticle` as article,`GESSICA.CodeDossier` as dossier,`GESSICA.PANF` as panf,`GESSICA.PFNP` as pfnp,`GESSICA.LibelleArticle` as descr, `GESSICA.PCB` as pcb,`GESSICA.NomFournisseur` as fournisseur,`GESSICA.Gencod` as ean FROM basearticles WHERE `GESSICA.Gencod` LIKE :ean");
	$req->execute(array(
		':ean'	=>'%'.$ean.'%'
	));
return  $req->fetchAll(PDO::FETCH_ASSOC);
}
function getMagIdwebuser($pdoUser)
{
	$req=$pdoUser->prepare("SELECT id FROM users WHERE galec=:galec");
	$req->execute(array(
		':galec'		=>$_GET['galec']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function insertDossier($pdoLitige, $numDossier,$magId)
{
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
	$req=$pdoLitige->prepare("INSERT INTO dossiers(date_crea,user_crea,nom,galec,id_web_user,vingtquatre, dossier) VALUES(:date_crea,:user_crea,:nom, :galec, :id_web_user, :vingtquatre, :dossier)");
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


function getSelectedDetails($pdoQlik,$id)
{
	$req=$pdoQlik->prepare("SELECT id, `GESSICA.CodeArticle` as article,`GESSICA.CodeDossier` as dossier,`GESSICA.PANF` as tarif,`GESSICA.PFNP` as pfnp,`GESSICA.LibelleArticle` as libelle, `GESSICA.PCB` as qte,`GESSICA.NomFournisseur` as fournisseur,`GESSICA.Gencod` as gencod,`GESSICA.CodeFournisseur` as cnuf  FROM basearticles WHERE id= :id");
	$req->execute(array(
		':id'	=>$id

	));
	return $req->fetch(PDO::FETCH_ASSOC);
}



function addDetails($pdoLitige, $lastInsertId,$numDossier, $article, $ean,$dossierG, $descr, $qteC,	$tarif, $fou, $cnuf)
{
	$req=$pdoLitige->prepare("INSERT INTO details(id_dossier, dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, fournisseur, cnuf) VALUES(:id_dossier, :dossier, :palette, :facture, :date_facture, :article, :ean, :dossier_gessica, :descr, :qte_cde, :tarif, :fournisseur, :cnuf)");
	$req->execute(array(
		':id_dossier'	=>$lastInsertId,
		':dossier'		=>$numDossier,
		':palette'		=>0,
		':facture'		=>0,
		':date_facture'	=>'1900-01-01 00:00:00',
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


function updateOuv($pdoLitige,$lastInsertId,$numdossier)
{

	$req=$pdoLitige->prepare("UPDATE ouv SET id_litige= :id_litige, dossier= :dossier WHERE id= :id");
	$req->execute(array(
		':id_litige'		=>$lastInsertId,
		':dossier'		=>$numdossier,
		'id'		=>$_GET['id_ouv']
	));
	return $req->rowCount();

}
// le numéro de dossier du litige et non l'id du litige
function getLastNumDossier($pdoLitige)
{
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

if(!isset($_GET['id_ouv']))
{
	$formaction=htmlspecialchars($_SERVER['PHP_SELF']).'?galec='.$_GET['galec'];
}
else
{
	$formaction=htmlspecialchars($_SERVER['PHP_SELF']).'?id_ouv='.$_GET['id_ouv'].'&galec='.$_GET['galec'];

}

// 8806098019618
// 4897008076382
$mag=getMagName($pdoBt);
$magtxt="<span class='text-reddish'>pour ".$mag['mag']."</span>";


if(isset($_POST['submit']))
{

	$eanAr=str_replace(' ','',$_POST['search_ean']);
	$eanAr=explode(',',$eanAr);
	$nbEan=count($eanAr);
	$foundProd=[];

	// $eanAr=explode(',',$string);
	// $nbEan=count($eanAr);
	for ($i=0; $i < $nbEan ; $i++)
	{
		$found=searchEan($pdoQlik,$eanAr[$i]);
		for ($y=0; $y <count($found) ; $y++)
		{
		array_push($foundProd,$found[$y]);
		}
	}
}


if(isset($_POST['choose']))
{
	$magId=getMagIdwebuser($pdoUser);
	$magId=$magId['id'];

//  on ne veut récuperer que les id donc on supprime les valeurs des champ submit, date, etc
	foreach ($_POST as $key => $value)
	{
		if($key!='choose' && $key!='rapid' && $key !='nom' && $key !='date_bt' && $key != 'num_dossier_form' )
		{
			$ids[]=$key;
		}
	}


// 1- creation du dossier
	if(count($ids)>0)
	{

		// soit le numéro de dossier a été saisi soit, on doit le calculer
		if(!empty($_POST['num_dossier_form']))
		{
			$numDossier=$_POST['num_dossier_form'];
			$lastInsertId=insertDossier($pdoLitige,$numDossier, $magId);

		}
		else
		{
			// si pas de numéro de dossier imposé, on prend le der num et on ajoute 1
			$numDossier=getLastNumDossier($pdoLitige);


			$numDossier=$numDossier['dossier'];
			// il faut vérifier que l'on a pas changé d'année
			// prend les 2 1er caractère du numdossier pour les comparer à l'année actuelle
			// si différent de l'anneé actuelle, on a changé d'année par rapport au der dossier
			// il faut donc créer le 1er numdossier
			$yearDossier=substr($numDossier,0,2);
			if($yearDossier==date('y'))
			{
				// pas de chg d'année, on prend le der num dossier, oon ajoute 1
				$numDossier=$numDossier +1;
			}
			else
			{
				$numDossier=date('y').'001';

			}
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
		for ($i=0; $i <$nbArticle ; $i++)
		{
			$art=getSelectedDetails($pdoQlik, $ids[$i]);
			// $dateFact=date('Y-m-d H:i:s',strtotime($art['date_mvt']));

			$tarif=$art['tarif']*$art['qte'];
			$detail=addDetails($pdoLitige, $lastInsertId,$numDossier, $art['article'], $art['gencod'],$art['dossier'], $art['libelle'], $art['qte'],$tarif, $art['fournisseur'], $art['cnuf']);

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
			if(isset($_GET['id_ouv']))
			{
				updateOuv($pdoLitige,$lastInsertId,$numDossier);
			}
			header('Location:declaration-detail.php?id='.$lastInsertId);
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
	if(isset($_POST['submit']))
	{
		echo $resultList;
	}
	 ?>

	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>