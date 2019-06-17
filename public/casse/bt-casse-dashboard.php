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
require('casse-getters.fn.php');


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
		`GESSICA.NomFournisseur` as fournisseur,
		`CTBT.StkEnt` as stock
		FROM basearticles WHERE `GESSICA.CodeArticle`= :article ORDER BY `GESSICA.CodeDossier`");
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
	$success[]="Votre dossier casse n° ".$_GET['success']." a bien été créé. <a href='detail-casse.php?id=".$_GET['success']."'>Consulter votre dossier</a> ";
}

if(isset($_POST['search_form']))
{
	$casses=search($pdoCasse);
}

// cas ou va sur detail-palette sans id en paramètre => redirige ici
if(isset($_GET['error']) && $_GET['error']==1){
	$errors[]="Vous avez été redirigé, cette page n'est pas accessible";
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

<div class="container no-padding">
	<div class="row no-gutters">
		<div class="col">
			<img src="../img/litiges/brokenphone2.png" class="img-fluid">
		</div>
	</div>
</div>
<div class="container">


	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row py-3">
		<div class="col">
			<h5 class="text-main-blue"><span class="step">1</span>Déclarer une casse :</h5>

		</div>
	</div>
	<!-- recherche article pour déclarer une casse -->
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
				<p>Veuillez sélectionner le dossier qui correspond en cliquant sur le sigle<i class="far fa-check-circle pl-3 text-main-blue"></i> de la ligne correspondante<br>
				<strong>Attention, </strong>le stock affiché est le stock à j-1
				</p>
				<?php
				$th=['dossiers','libellé','fournisseur','pcb','valo','stock','Déclarer'];
				$fields=['dossier','libelle','fournisseur','pcb','valo','stock','id'];
				$tableArticle=new Table(['table', 'table-sm','table-bordered'],'dossiers');
				$arrLink=[
					'href'	=>'bt-declaration-casse.php',
					'text'	=>'<i class="far fa-check-circle pr-3"></i>',
					'col'	=>'7',
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
	<!-- recherche déclaration de casse -->
	<div class="row pb-3">
		<div class="col border py-5">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" >
				<p>Saisissez le code article ou le numéro de dossier :</p>
				<div class="row">
					<div class="col-4">
						<div class="form-group">
							<input class="form-control mr-5 pr-5" placeholder="article, dossier" name="search_strg" id="search_strg" type="text"  value="<?=isset($search_strg) ? $search_strg : false?>">
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
				$tableCasse=new Table(['table', 'table-sm','table-bordered'],'search-result');
				$arrLink=[
					'href'	=>'detail-casse.php',
					'text'	=>'',
					'col'	=>'1',
					'param'	=>'id',

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
	<div class="bg-separation"></div>

	<div class="row py-3">
		<div class="col">
			<h5 class="text-main-blue"><span class="step">3</span>Liste des palettes de casse en stock :</h5>

		</div>
	</div>
	<div class="row">
		<div class="col">
			<p><span class="text-orange"><i class="fas fa-clipboard-check"></i> : palette contremarquée</span><span class="text-green pl-5"><i class="fas fa-paper-plane"></i> : palette expédiée</span><span class="text-red pl-5"><i class="fas fa-info-circle"></i> : palette à traiter </span></p>

		</div>
	</div>


	<div class="row pb-5">
		<div class="col">
	<?php

	$paletteList=getStockPalette($pdoCasse);
	if($paletteList==false){
		echo "<p>aucune palette de casse en stock</p>";
	}
	else
	{
		$nbPalette=count($paletteList);
		$statut=[0=>'<span class="text-red"><i class="fas fa-info-circle"></i></span>',1=>'<span class="text-orange"><i class="fas fa-clipboard-check"></i></span>', 2=>'<i class="fas fa-paper-plane text-green"></i>'];
		echo '<ul id="list-palette">';
		foreach ($paletteList as $palette)
		{
			echo '<li><a href="detail-palette.php?id='.$palette['id'].'" class="link-main-blue">'.$palette['palette'].'</a> - '.$statut[$palette['statut']].'</li>';
		}
		echo '</ul>';

	}
	?>
			<p class="alert alert-primary">Cliquez sur une palette pour en afficher le contenu et agir sur celle-ci</p>

		</div>
	</div>
	<div class="bg-separation"></div>
	<div class="row py-3">
		<div class="col">
			<h5 class="text-main-blue"><span class="step">4</span>Traitement des palettes à livrer :</h5>
		</div>
	</div>




	<?php



	$existingExp=getActiveExp($pdoCasse);
	if($existingExp!=false)
	{
		foreach ($existingExp as $exp)
		{
			echo '<div class="row">';
	echo '<div class="col-auto">';

			echo '<table class="table table-sm table-bordered">';
			echo '<tr>';
			echo '<th>Palette 4919</th>';
			echo '<th>Palette '.$exp['btlec'].'</th>';
			echo '</tr>';
			$detailExp=getDetailExp($pdoCasse,$exp['id']);

			foreach ($detailExp as $detail)
			{
			echo '<tr>';
			echo '<td>'.$detail['palette'].'</td>';
			echo '<td>'.$detail['contremarque'].'</td>';

			echo '</tr>';

			}
			echo '</table>';
			echo '</div>';
			echo '<div class="col-1"></div>';
			echo '<div class="col">';
			echo '<p><span class="text-main-blue">Actions faites / à faire :</span><br>';

			echo '<a href="pilote-dd.php?id='.$exp['id'].'">1- Faire la demande de départ/contrôle aux pilotes</a><br>';
			echo '2- Mettre les palettes en RAQ<br>';
			echo '3- Saisir les infos financières<br>';
			echo '4- Prévenir le magasin<br>';
			echo '5- Facturer';
			echo '</p>';
			echo '</div>';


			echo '</div>';
			echo '<div class="row">';


	/*si une livraison eest en cours, afficher bouton
		pour générer pdf et l'envoyer aux pilotes
		pour prévenir le mgasin de la livraison et lui envoyer le pdf
		pour que les pilotes puissent valider l'expédition
		pour lancer les facturations */

			echo '</div>';




		}

	}
	else{
		echo "aucune palette n'a été sélectionnée pour une livraison magasin";
	}

	 ?>


	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>