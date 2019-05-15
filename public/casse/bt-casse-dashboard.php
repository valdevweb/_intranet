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
//			INCLUDES
//------------------------------------------------------
require('../../Class/Table.php');


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
function searchArticle($pdoQlik){
	// $req=$pdoQlik->prepare("SELECT id,`GESSICA.CodeDossier` as dossier FROM basearticles WHERE `GESSICA.CodeArticle`= :article");
	$req=$pdoQlik->prepare("SELECT
		id,
		`GESSICA.CodeDossier` as dossier,
		`GESSICA.GT` as gt,
		`GESSICA.LibelleArticle` as libelle,
		`GESSICA.PCB` as pcb,
		`GESSICA.PANF` as valo,
		`GESSICA.CodeFournisseur` as cnuf,
		`GESSICA.NomFournisseur` as fournisseur FROM basearticles WHERE `GESSICA.CodeArticle`= :article ORDER BY `GESSICA.CodeDossier`");
	$req->execute(array(
		':article'	=>$_POST['article']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}

function search($pdoCasse)
{
	$req=$pdoCasse->prepare("SELECT *  FROM casses WHERE concat(article,id) LIKE :search");
	$req->execute(array(
		':search' =>'%'.$_POST['search_strg'] .'%'
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
 // return $req->errorInfo();
}

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

if(isset($_POST['varticle']))
{
	extract($_POST);
	$articles=searchArticle($pdoQlik);
}

if(isset($_GET['success']))
{
	$success[]="Votre dossier casse n° ".$_GET['success']." a bien été créé. <a href='bt-detail-casse.php?id=".$_GET['success']."'>Consulter votre dossier</a> ";
}

if(isset($_POST['search_form']))
{
	$casses=search($pdoCasse);
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
	<h1 class="text-main-blue py-5 ">La casse - accueil</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row pb-3">
		<div class="col">
			<h5 class="text-main-blue"><span class="step">1</span>Déclarer une casse :</h5>

		</div>
	</div>
	<div class="row pb-3">
		<div class="col border py-5">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" >
				<div class="row">
					<div class="col-4">
						<div class="form-group">
							<label for="article">Code de l'article : </label>
							<input class="form-control mr-5 pr-5" placeholder="code article" name="article" id="article" type="text"  value="<?=isset($article) ? $article : false?>">
						</div>
					</div>
					<div class="col pt-4 mt-2">
						<button class="btn btn-primary " type="submit" id="" name="varticle"><i class="fas fa-search pr-2"></i>Rechercher</button>
					</div>

				</div>
			</form>
		</div>
	</div>
	<?php if(isset($_POST['varticle'])): ?>
		<div class="row mb-3">
			<div class="col">
				<h5 class="text-main-blue py-3 text-center">Votre recherche pour le code article : <span class="heavy bg-grey patrick-hand px-3"><?=$_POST['article']?></span></h5>
				<p>Veuillez sélectionner le dossier qui correspond en cliquant sur le sigle<i class="far fa-check-circle pl-3 text-main-blue"></i> de la ligne correspondante</p>
				<?php
				$th=['dossiers','libellé','fournisseur','pcb','valo','Déclarer'];
				$fields=['dossier','libelle','fournisseur','pcb','valo','id'];
				$tableArticle=new Table('dossiers');
				$arrLink=[
					'href'	=>'bt-declaration-casse.php',
					'text'	=>'<i class="far fa-check-circle pr-3"></i>',
					'col'	=>'6',
					'param'	=>'idBa',

				];
				$link=$tableArticle->addLink($arrLink);
				$tableArticle->createBasicTable($th,$articles,$fields, $link);
				?>

			</div>
		</div>

	<?php endif ?>
	<div class="bg-separation"></div>
	<div class="row pt-3">
		<div class="col">
			<h5 class="text-main-blue"><span class="step">2</span>Rechercher une déclaration de casse :</h5>

		</div>
	</div>
	<div class="row pb-3">
		<div class="col border py-5">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" >
				<p>Saisissez le code article, le numéro de dossier ou la date de déclaration :</p>
				<div class="row">
					<div class="col-4">
						<div class="form-group">
							<input class="form-control mr-5 pr-5" placeholder="article, dossier, date" name="search_strg" id="search_strg" type="text"  value="<?=isset($search_strg) ? $search_strg : false?>">
						</div>
					</div>
					<div class="col">
						<button class="btn btn-primary " type="submit" id="search_form" name="search_form"><i class="fas fa-search pr-2"></i>Rechercher</button>
					</div>

				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col">

			<?php
			if(isset($casses) && !empty($casses))
			{
				echo '<h5 class="text-main-blue py-3 text-center">Résultat pour votre recherche : <span class="heavy bg-grey patrick-hand px-3">'.$_POST['search_strg'].'</span></h5>';

				$th=['N°','article','dossiers','Désignation','fournisseur','pcb','valo'];
				$fields=['id','article','dossier','designation','fournisseur','pcb','valo'];
				$tableCasse=new Table('search-result');
				$arrLink=[
					'href'	=>'bt-declaration-casse.php',
					'text'	=>'',
					'col'	=>'1',
					'param'	=>'idKs',

				];
				$link=$tableCasse->addLink($arrLink);
				$tableCasse->createBasicTable($th,$casses,$fields, $link);
			}
			elseif(isset($casses) && empty($casses)){
				echo '<p class="alert alert-warning">Aucun résultat pour votre recherche : <span class="heavy bg-reddish text-white px-3">'.$_POST['search_strg'] .'</span></p>';
			}
			?>



		</div>
	</div>


	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>