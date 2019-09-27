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
require('../../Class/MagHelpers.php');
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

	if($_POST['statut']==''){
		$etat=' etat IS NOT NULL ';
	}
	elseif($_POST['statut']==0){
		$etat=' etat=0 ';
	}
	elseif($_POST['statut']==1){
		$etat=' etat =1 ';

	}

	$req=$pdoCasse->prepare("SELECT *  FROM casses WHERE concat(article,id) LIKE :search AND date_casse BETWEEN :date_start AND :date_end AND $etat");
	$req->execute(array(
		':search' =>'%'.$_POST['search_strg'] .'%',
		':date_start'		=>$_POST['date_start'],
		':date_end'			=>$_POST['date_end']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
 // return $req->errorInfo();
}

function searchPalette($pdoCasse)
{

	//tout statut
	if($_POST['statut']==''){
		$statut=' statut IS NOT NULL ';
	}
	// statut en cours
	elseif($_POST['statut']==0){
		$statut=' statut=0 AND NumeroPalette IS NULL ';
	}
	// en stock à expédié pas positionné
	elseif($_POST['statut']==4919){
		$statut=' NumeroPalette IS NOT NULL ';
	}
	// positionnée à expédier
	elseif($_POST['statut']==1){
		$statut=' statut =1 ';
	}
	// clos fac
	elseif($_POST['statut']==5){
		$statut=' exp=1 AND exps.mt_fac IS NOT NULL ';
	}
	// clos hs
	elseif($_POST['statut']==6){
		$statut=' exp=1 AND mt_fac IS  NULL ';
	}
	$req=$pdoCasse->prepare("SELECT id_palette, palettes.palette,contremarque,id_exp,exps.exp,palettes.statut,qlik.palettes4919.NumeroPalette, DATE_FORMAT(date_delivery, '%d/%m/%y') as dateDelivery,sum(valo) as valopalette, btlec, galec, mt_fac FROM palettes
		LEFT JOIN exps ON palettes.id_exp=exps.id
		LEFT JOIN casses ON palettes.id=casses.id_palette
		left JOIN qlik.palettes4919 ON palettes.palette=NumeroPalette
		WHERE palette LIKE :search AND cast(palettes.date_crea as date) BETWEEN :date_start AND :date_end AND $statut GROUP BY palettes.id ORDER BY palettes.date_crea ");
	$req->execute(array(
		':search' =>'%'.$_POST['search_strg'] .'%',
		':date_start'		=>$_POST['date_start'],
		':date_end'			=>$_POST['date_end']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
function formatExistingDate($date){
	if(isset($date)){
		$date=new DateTime($date);
		$date=$date->format('d/m/y');
	}
	else{
		$date='';
	}
	return $date;
}


// echo $_POST['statut'];


//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$today=date('Y-m-d');
$start=date('Y-m-d',strtotime("2019-01-01"));
$yesterday=date('Y-m-d',strtotime("-1 days"));
$existingExp=getActiveExp($pdoCasse);
//  palkette en stock sur le 4919
$paletteEnStock=getStockPalette($pdoCasse);
$sumTot=0;
$arMagSum=[];
if(isset($_POST['varticle']))
{
	extract($_POST);
	$articles=searchArticle($pdoQlik);
}

if(isset($_POST['vpalette'])){
	extract($_POST);
	$palettes=searchPalette($pdoCasse);
	$nbpalette=count($palettes);


	foreach ($palettes as $key => $value) {
		$sumTot+=$value['valopalette'];

		if(isset($arMagSum[$value['galec']])){
			$arMagSum[$value['galec']]+=$value['valopalette'];
		}else{
			$arMagSum[$value['galec']]=$value['valopalette'];
		}
	}


	$nbMagCol=ceil(count($arMagSum)/2);
	$lig=1;
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
if(isset($_GET['error'])){
	if($_GET['error']==1){
		$errors[]="Vous avez été redirigé, cette page n'est pas accessible";
	}
	elseif($_GET['error']==2){
		$errors[]="Une erreur est survenue";
	}
}




if(isset($_GET['deleteOk'])){
	$success[]="La casse n° ". $_GET['deleteOk'] ." a bien été supprimée";
}

if(isset($_GET['mailPilote'])){
	$success[]="Le mail a bien été envoyé aux pilotes";
}

if(isset($_GET['majExp'])){
	$success[]="La date d'expédition a bien été enregistrée";
}
if(isset($_GET['mailMag'])){
	$success[]="Le mail a bien été envoyé magasin";
}

if(isset($_POST['clear_form'])){
	$_POST=[];

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
	<div class="row no-gutters" id="mini-menu">
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
	<div class="row" >
		<div class="col"></div>
		<div class="col-auto">
			<ul class="css-not-selector-shortcut sibling-fade">
				<li><a href="#declarer" class="link-main-blue">Déclarer</a></li>
				<li><a href="#searching" class="link-main-blue">Rechercher</a></li>
				<li><a href="#stock" class="link-main-blue">En stock</a></li>
				<li><a href="#traitement" class="link-main-blue">Traitement</a></li>
			</ul>
		</div>
		<div class="col"></div>

	</div>
	<div class="row py-3">
		<div class="col">
			<h5 class="text-main-blue" id="declarer"><span class="step">1</span>Déclarer une casse :</h5>

		</div>
	</div>


	<!-- recherche article pour déclarer une casse -->
	<div class="row mb-3 border focusing">
		<div class="col  py-5">
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
			<h5 class="text-main-blue" id="searching"><span class="step">2</span>Formulaires de recherche :</h5>

		</div>
	</div>
	<!-- recherche déclaration de casse -->

	<div class="row border">
		<div class="col">

			<div class="row focusing">
				<div class="col  pt-5">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" >
						<div class="form-row ">
							<div class="col-4">
								<div class="form-group">
									<label class="font-weight-bold">Article :</label>
									<input class="form-control mr-5 pr-5" placeholder="article, palette" name="search_strg" id="search_strg" type="text"  value="<?=isset($search_strg) ? $search_strg : false?>">
								</div>
							</div>
							<div class="col-2">
								<div class="form-group">
									<label>Date de début :</label>
									<input type="date" name="date_start" id="date_start" class="form-control" value="<?=$start?>">
								</div>
							</div>
							<div class="col-2">
								<div class="form-group">
									<label>Date de fin :</label>
									<input type="date" name="date_end" id="date_end" class="form-control" value="<?=$today?>">
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label>Statut : </label>
									<select name="statut" id="statut" class="form-control">
										<option value="">Tout statut</option>
										<option value="0">En cours</option>
										<option value="1">Cloturé</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-row text-right">

							<div class="col">
								<button class="btn btn-primary " type="submit" id="search_form" name="search_form"><i class="fas fa-search pr-2"></i>Rechercher une casse</button>
								<button class="btn secTwo" type="submit" id="" name="clear_form"><i class="fas fa-eraser pr-2"></i>Effacer</button>


							</div>

						</div>
					</form>
				</div>
			</div>


			<div class="row" id="result">
				<div class="col">
					<?php
			// si résultat
					if(isset($casses) && !empty($casses))
					{
						$arrStatutCasse=['<span class="text-red">en cours</span>','clos'];

						echo '<h5 class="text-main-blue py-3 text-center">Résultat pour votre recherche : <span class="heavy bg-grey patrick-hand px-3">'.$_POST['search_strg'].'</span></h5>';

						echo '<div class="text-center pb-3"><a href="xl-dashboard-casse.php?date_start='.$_POST['date_start'].'&date_end='.$_POST['date_end'].'&statut='.$_POST['statut'].'&search_strg='.$_POST['statut'].'" class="btn secTwo"><i class="fas fa-file-excel pr-3"></i>Exporter</a></div>';

						echo '<table class="table table-sm table-bordered">';
						echo '<thead class="thead-dark">';
						echo '<tr>';
						echo '<th>N°</th>';
						echo '<th>Article</th>';
						echo '<th>Date</th>';
						echo '<th>Désignation</th>';
						echo '<th>Fournisseur</th>';
						echo '<th class="text-right">PCB</th>';
						echo '<th class="text-right">Valo</th>';
						echo '<th>Statut</th>';
						echo '<th class="text-right"><i class="far fa-trash-alt"></i></th>';
						echo '</tr>';
						echo '</thead>';
						echo '<tbody>';
						foreach ($casses as $casse) {
					// $dateCasse=;
					// $dateCasse=date('d/m/y', strtotime($casse['date_casse']));
							echo '<tr>';
							echo '<td><a href="detail-casse.php?id='.$casse['id'].'">'.$casse['id'].'</td>';
							echo '<td>'.$casse['article'].'</td>';
							echo '<td class="text-right">'.date('d/m/y', strtotime($casse['date_casse'])).'</td>';

							echo '<td>'.$casse['designation'].'</td>';
							echo '<td>'.$casse['fournisseur'].'</td>';
							echo '<td class="text-right">'.$casse['pcb'].'</td>';
							echo '<td class="text-right">'.$casse['valo'].'</td>';
							echo '<td class="text-right">'.$arrStatutCasse[$casse['etat']].'</td>';
							echo '<td class="text-right"><a href="delete-casse.php?id='.$casse['id'].'" class="red-link"><i class="far fa-trash-alt"></i></a></td>';
							echo '</tr>';
						}
						echo '</tbody>';
						echo '</table>';


					}
					elseif(isset($casses) && empty($casses)){
						echo '<p class="alert alert-warning">Aucun résultat pour votre recherche : <span class="heavy bg-reddish text-white px-3">'.$_POST['search_strg'] .'</span></p>';
					}

					?>

				</div>
			</div>




			<div class="row mb-3 focusing">
				<div class="col  py-5">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" >
						<div class="form-row ">
							<div class="col-4">
								<div class="form-group">
									<label lcass="font-weight-bold">Palette :</label>
									<input class="form-control mr-5 pr-5" placeholder="article, palette" name="search_strg" id="search_strg" type="text"  value="<?=isset($search_strg) ? $search_strg : false?>">
								</div>
							</div>
							<div class="col-2">
								<div class="form-group">
									<label>Date de début :</label>
									<input type="date" name="date_start" id="date_start" class="form-control" value="<?=$start?>">
								</div>
							</div>
							<div class="col-2">
								<div class="form-group">
									<label>Date de fin :</label>
									<input type="date" name="date_end" id="date_end" class="form-control" value="<?=$today?>">
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label>Statut : </label>
									<select name="statut" id="statut" class="form-control">
										<option value="">Tout statut</option>
										<option value="0">En cours</option>
										<option value="4919">En stock - à expédier</option>
										<option value="1">Positionnée - à  expédier</option>
										<option value="5">Clos - facturé</option>
										<option value="6">Clos - detruit</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-row text-right">

							<div class="col">

								<button class="btn btn-black " type="submit" id="" name="vpalette"><i class="fas fa-search pr-2"></i>Rechercher une palette</button>
								<button class="btn secTwo" type="submit" id="" name="clear_form"><i class="fas fa-eraser pr-2"></i>Effacer</button>


							</div>

						</div>
					</form>
				</div>
			</div>





		</div>
	</div>







	<?php if(isset($_POST['vpalette'])): ?>





		<div class="result-zone bg-grey px-5 pb-2 pt-2 mb-2">

<div class="row">
	<div class="col">
		<?php if (isset($_POST['search_strg']) && !empty($_POST['search_strg'])): ?>
		<h5 class="text-main-blue pb-3 text-center">Résultat de votre recherche pour : <span class="heavy bg-title patrick-hand px-3"><?=$_POST['search_strg']?></span></h5>
		<?php else: ?>
			<h5 class="text-main-blue pb-3 text-center">Résultat de votre recherche  : </h5>

		<?php endif ?>
	</div>
</div>
<div class="row">
	<div class="col text-center">

		<h6 class="qtext-center bg-title p-3 d-inline-block rounded-more"><?= $nbpalette ?> palettes pour un montant total de <span class="under"><?= number_format((float)$sumTot,2,'.',' ')?>&euro;</span></h6>

	</div>
</div>
<div class="row">
	<div class="col">
		<ul class="leaders">
			<?php $lig=1 ?>
			<?php foreach ($arMagSum as $galec => $mt): ?>
				<?php if ($lig<=$nbMagCol): ?>

					<li>
						<span class="mag-txt heavy"><?= ($galec!="")? MagHelpers::deno($pdoUser,$galec) : "Non positionné" ?></span>
						<span class="text-right sum-txt font-weight-bold">
							<?= number_format((float)$mt,2,'.',' ') ?>&euro;</span>						</li>


						<?php $lig++?>
						<?php else: ?>
							<?php $lig=1?>
						</ul>
					</div>
					<div class="col">
						<ul class="leaders">
							<li>
								<span class="mag-txt heavy"><?=MagHelpers::deno($pdoUser,$galec)?></span>


								<span class="text-right sum-txt font-weight-bold">
									<?= number_format((float)$mt,2,'.',' ') ?>&euro;
								</span>
							</li>


						<?php endif ?>

					<?php endforeach ?>
				</ul>
			</div>
		</div>
		<div class="row">
				<div class="col">
					<div class="legend"><img class="pr-3" src="../img/casse/encours.jpg"> En cours</div>
				</div>
				<div class="col">
					<div class="legend"><img class="pr-3" src="../img/casse/livrer.png">A livrer</div>
				</div>
				<div class="col">
					<div class="legend"><img  src="../img/casse/livre.png"><img class="pr-3" src="../img/casse/creditcard.png">Clos - facturé</div>
				</div>
				<div class="col">
					<div class="legend"><img  src="../img/casse/livre.png"><img class="pr-3" src="../img/casse/logo_deee.jpg">Clos - détruit</div>
				</div>



</div>
	</div>
	<div class="row mb-3">
		<div class="col">
			<table class="table table-sm table-bordered table-striped" id="palettes">
				<thead class="thead-dark">
					<tr>
						<th class="sortable" onclick="sortTable(0);">Exp</th>
						<th class="sortable" onclick="sortTable(1);">Palette</th>
						<th class="sortable" onclick="sortTable(2);">Palette<br> contremarque</th>
						<th class="sortable text-center" onclick="sortTable(3);">Statut</th>

						<th class="sortable" onclick="sortTable(4);">Date expé</th>
						<th class="sortable" onclick="sortTable(5);">Magasin</th>
						<th class="sortable" onclick="sortTable(6);">Valo<br>palette</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($palettes as $palette)
					{
						echo '<tr>';
						echo '<td>'.$palette['id_exp'].'</td>';
						echo '<td><a href="detail-palette.php?id='.$palette['id_palette'].'">'.$palette['palette'].'</a></td>';
						echo '<td>'.$palette['contremarque'].'</td>';


						if($palette['statut']==0 && $palette['NumeroPalette']==null){
							$statut='<img src="../img/casse/encours.jpg">';
						}
						elseif($palette['statut']==1 || $palette['NumeroPalette']!=null){
							$statut='<img src="../img/casse/livrer.png">';
						}elseif ($palette['exp']==1 && $palette['mt_fac']!='') {
							$statut='<img src="../img/casse/livre.png"><img src="../img/casse/creditcard.png">';
						}elseif ($palette['exp']==1 && $palette['mt_fac']==null){
							$statut='<img src="../img/casse/livre.png"><img src="../img/casse/logo_deee.jpg">';
						}
						echo '<td class="text-center">'.$statut.'</td>';



						echo '<td class="text-right">'.$palette['dateDelivery'].'</td>';
						echo '<td class="text-right">'.$palette['btlec'].'</td>';
						echo '<td class="text-right">'.$palette['valopalette'].'</td>';

						echo '</tr>';
					}

					?>
				</tbody>
			</table>
		</div>
	</div>

<?php endif ?>


<div class="row">
	<div class="col text-right"><a href="#mini-menu" class="uplink">retour</a></div>
</div>

<div class="bg-separation"></div>



<div class="row py-3">
	<div class="col">
		<h5 class="text-main-blue" id="stock"><span class="step">3</span>Liste des palettes de casse en stock :</h5>

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

		if($paletteEnStock==false){
			echo "<p>aucune palette de casse en stock</p>";
		}
		else
		{
			$nbPalette=count($paletteEnStock);
			$statut=[0=>'<span class="text-red"><i class="fas fa-info-circle"></i></span>',1=>'<span class="text-orange"><i class="fas fa-clipboard-check"></i></span>', 2=>'<i class="fas fa-paper-plane text-green"></i>'];
			echo '<ul id="list-palette">';
			foreach ($paletteEnStock as $palette)
			{
				echo '<li><a href="detail-palette.php?id='.$palette['paletteid'].'" class="link-main-blue">'.$palette['palette'].'</a> - '.$statut[$palette['statut']].'</li>';
			}
			echo '</ul>';

		}
		?>
		<p class="alert alert-primary">Cliquez sur une palette pour en afficher le contenu et la positionner sur une livraison</p>

	</div>
</div>
<div class="row">
	<div class="col text-right"><a href="#mini-menu" class="uplink">retour</a></div>
</div>
<div class="bg-separation"></div>
<div class="row py-3">
	<div class="col">
		<h5 class="text-main-blue" id="traitement"><span class="step">4</span>Traitement des palettes à livrer :</h5>
	</div>
</div>
<?php

if($existingExp!=false)
{


	foreach ($existingExp as $exp)
	{
		echo '<div class="row pb-5">';
		echo '<div class="col">';
		echo '<p class="text-main-blue">Livraison n°'.$exp['id'].' pour le magasin '.$exp['btlec'].'</p>';
		echo '<table class="table table-sm light-shadow" id="action">';
		echo '<thead>';

		echo '<tr>';
		echo '<th class="align-top">Palette 4919</th>';
		echo '<th class="align-top">Palette '.$exp['btlec'].'</th>';
		echo '<th class="align-top">Mail ctrl <br>palettes</th>';
		echo '<th class="align-top">Retour ctrl <br>palettes</th>';
		echo '<th class="align-top">Expédiée le :</th>';
		echo '<th class="align-top">Mail magasin</th>';
		echo '<th class="align-top">Facturation</th>';
		echo '</tr>';
		echo '</thead>';

		$detailExp=getExpAndPalette($pdoCasse,$exp['id']);


		foreach ($detailExp as $detail)
		{
			$ddPilote=formatExistingDate($detail['date_dd_pilote']);
			$retourPilote=formatExistingDate($detail['date_retour_pilote']);
			$livraison=formatExistingDate($detail['date_delivery']);
			$mailMag=formatExistingDate($detail['date_info_mag']);
			$fac=formatExistingDate($detail['date_fac']);
					// $ddPilote=isset() ? $detail['date_dd_pilote'] : false;
			echo '<tr>';
			echo '<td><a href="detail-palette.php?id='.$detail['paletteid'].'">'.$detail['palette'].'</a></td>';
			echo '<td>'.$detail['contremarque'].'</td>';
			echo '<td class="text-right">'.$ddPilote.'</td>';
			echo '<td class="text-right">'.$retourPilote.'</td>';
			echo '<td class="text-right">'.$livraison.'</td>';
			echo '<td class="text-right">'.$mailMag.'</td>';
			echo '<td class="text-right">'.$fac.'</td>';
			echo '</tr>';

		}

		echo '<tr >';
		echo '<td colspan="2"><h5 class="text-main-blue py-3 heavy">Traitements :</h5></td>';
		echo '<td class="text-center py-3"><a href="pilote-dd.php?id='.$exp['id'].'" id="mailpilote"><button class="btn secOne"><i class="far fa-envelope pr-3"></i>Envoyer</button></a></td>';
		echo '<td class="text-center py-3"><a href="pilote-palette-ok.php?id='.$exp['id'].'"><button class="btn secTwo"><i class="far fa-edit pr-3"></i>Saisir</button></a></td>';
		echo '<td class="text-center py-3"><a href="delivery-ok.php?id='.$exp['id'].'"><button class="btn secThree"><i class="far fa-edit pr-3"></i>Saisir</button></a></td>';
		echo '<td class="text-center py-3"><a href="mag-info-casse.php?id='.$exp['id'].'" id="mailMag"><button class="btn secFour"><i class="far fa-envelope pr-3"></i>Envoyer</button></a></td>';
			//  pas de btn facturer si pôle
		if($exp['btlec']==2051 || $exp['btlec']==2054 || $exp['btlec']==2069){
			echo '<td class="text-center py-3">';
			echo '<a href="casse-clos.php?id='.$exp['id'].'" ><button class="btn btn-primary"><i class="far fa-credit-card pr-3"></i>Clôturer</button></a>';

			echo '</td>';
		}
		else{
			echo '<td class="text-center py-3">';
			echo '<a href="facturation-casse.php?id='.$exp['id'].'" id="mailMag"><button class="btn secFive"><i class="far fa-credit-card pr-3"></i>Facturer</button></a><br>';
			echo '<div class="mt-3"><a href="casse-clos.php?id='.$exp['id'].'" ><button class="btn btn-primary"><i class="far fa-credit-card pr-3"></i>Clôturer</button></a></div>';
			echo '</td>';

		}
		echo '</tr>';
		echo '</table>';
		echo '</div>';
		echo '</div>';
		echo '<div class="row">';



		echo '</div>';




	}

}
else{
	echo "aucune palette n'a été sélectionnée pour une livraison magasin";
}



?>


<div class="row">
	<div class="col text-right"><a href="#mini-menu" class="uplink">retour</a></div>
</div>



<!-- ./container -->
</div>
<script src="../js/sortmultitable.js"></script>

<script type="text/javascript">
	function sortTable(n) {
		sort_table(document.getElementById("palettes"), n);
	}



	$(document).ready(function(){
	// boite de dialogue confirmation clic sur lien
	$('.red-link').on('click', function(e){
		var webid='<?php echo $_SESSION['id_web_user'];?>';
		console.log(webid);
		if(webid!=981 && webid!=959 && webid !=1043 && webid != 1279){
			alert("vous n'avez pas les droits pour supprimer une casse. Merci de faire votre demande à Christelle Trousset et/ou Nathalie Pazik ");
			e.preventDefault();

		}else{
			return confirm('Etes vous sûrs de vouloir supprimer cette casse ?');
		}
	});
	$('#mailpilote').on('click', function(){
		return confirm('Envoyer le mail de demande de contrôle des palettes aux pilotes ?');
	});
	$('#mailMag').on('click', function(){
		return confirm('Envoyer le mail d\'information au magasin ?');
	});

});
</script>



<?php
require '../view/_footer-bt.php';
?>