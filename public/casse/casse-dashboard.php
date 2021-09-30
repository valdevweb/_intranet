<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


//------------------------------------------------------
//			INCLUDES
//------------------------------------------------------
require '../../Class/Db.php';


require('../../Class/Table.php');
require('../../Class/BaDao.php');
require('../../Class/casse/PalettesDao.php');
require('../../Class/casse/ExpDao.php');
require('../../Class/MagHelpers.php');
require('casse-getters.fn.php');

$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');
$pdoCasse=$db->getPdo('casse');
$pdoMag=$db->getPdo('magasin');

$baDao=new BaDao($pdoQlik);
$paletteDao=new PalettesDao($pdoCasse);
$expDao=new ExpDao($pdoCasse);


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



$errors=[];
$success=[];
$today=date('Y-m-d');
$start=date('Y-m-d',strtotime("2019-01-01"));
$yesterday=date('Y-m-d',strtotime("-1 days"));
$nbPalette=0;
$existingExp=$expDao->getActiveExp();
//  palkette en stock sur le 4919
$paletteEnStock=$paletteDao->getStockPalette();
if(!empty($paletteEnStock)){
	$nbPalette=count($paletteEnStock);
}
$statut=[0=>'<span class="text-red"><i class="fas fa-info-circle"></i></span>',1=>'<span class="text-orange"><i class="fas fa-clipboard-check"></i></span>', 2=>'<i class="fas fa-paper-plane text-green"></i>'];


$sumTot=0;
$arMagSum=[];



if(isset($_POST['clear_form'])){
	unset($_POST);
	unset($_SESSION['casse_filter']);
}

if(isset($_GET['field_1'])){
	$_SESSION['casse_filter']['field_1']=$_GET['field_1'];
}
if(isset($_POST['search'])){
	$params= $_POST['field'] ." = '".$_POST['search_string']."'";
	$_SESSION['casse_filter']['field_1']=$_POST['field'];
	$_SESSION['casse_filter']['value_1']=$_POST['search_string'];
}

if(!isset($_SESSION['casse_filter'])){
	$palettesToDisplay=$paletteDao->getPalettesNonClos();
}else{
	if (isset($_SESSION['casse_filter']['field_1'])) {
		if($_SESSION['casse_filter']['field_1']=="ean"){
			$params= " ean='".$_SESSION['casse_filter']['value_1']."'";
		}elseif($_SESSION['casse_filter']['field_1']=="palette"){
			$params= " palette LIKE '%".$_SESSION['casse_filter']['value_1']."%'";
		}elseif($_SESSION['casse_filter']['field_1']=="id_casse"){
			$params= " casses.id=".$_SESSION['casse_filter']['value_1'];
		}elseif($_SESSION['casse_filter']['field_1']=="pending"){
			$params=" statut=0 AND NumeroPalette IS NULL";
		}elseif($_SESSION['casse_filter']['field_1']=="todeliver"){
			$params=" statut=1 OR NumeroPalette IS NOT NULL";
		}elseif($_SESSION['casse_filter']['field_1']=="clos"){
			$params=" statut=2 AND mt_fac IS NOT NULL";
		}elseif($_SESSION['casse_filter']['field_1']=="destroyed"){
			$params=" statut=2 AND mt_fac IS NULL";
		}
		else{
			$params= " ".$_SESSION['casse_filter']['field_1']. "=".$_SESSION['casse_filter']['value_1'];
		}
	}
	$palettesToDisplay=$paletteDao->searchWithParam($params);
}



if(isset($palettesToDisplay)){
	$nbpalette=count($palettesToDisplay);

	foreach ($palettesToDisplay as $key => $value) {
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





if(isset($_GET['success'])){
	$success[]="Votre dossier casse n° ".$_GET['success']." a bien été créé. <a href='detail-casse.php?id=".$_GET['success']."'>Consulter votre dossier</a> ";
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
				<li><a href="declare-casse.php" class="link-main-blue">Déclarer</a></li>
				<li><a href="#searching" class="link-main-blue">Rechercher</a></li>
				<li><a href="#stock" class="link-main-blue">En stock</a></li>
				<li><a href="#traitement" class="link-main-blue">Traitement</a></li>
			</ul>
		</div>
		<div class="col"></div>
	</div>
	<div class="row pt-3">
		<div class="col">
			<h5 class="text-main-blue" id="searching">Rechercher :</h5>
		</div>
	</div>
	<?php include ('casse-dashboard/11-form-search.php');	?>

	<?php if(isset($palettesToDisplay)): ?>
		<?php include ('casse-dashboard/12-search-stat.php');	?>
		<?php include ('casse-dashboard/13-search-table-result.php')?>
	<?php endif ?>




	<div class="row">
		<div class="col text-right"><a href="#mini-menu" class="uplink">retour</a></div>
	</div>

	<div class="bg-separation"></div>



	<div class="row py-3">
		<div class="col">
			<h5 class="text-main-blue" id="stock">Palettes en stock :</h5>

		</div>
	</div>
	<div class="row">
		<div class="col">
			<p><span class="text-orange"><i class="fas fa-clipboard-check"></i> : palette contremarquée</span><span class="text-green pl-5"><i class="fas fa-paper-plane"></i> : palette expédiée</span><span class="text-red pl-5"><i class="fas fa-info-circle"></i> : palette à traiter </span></p>

		</div>
	</div>
	<div class="row pb-5">
		<div class="col">
			<?php if (!empty($paletteEnStock)): ?>
				<ul id="list-palette">
					<?php foreach ($paletteEnStock as $palette): ?>
						<li><a href="detail-palette.php?id=<?=$palette['paletteid']?>" class="link-main-blue"><?=$palette['palette']?></a> - <?=$statut[$palette['statut']]?></li>
					<?php endforeach ?>
				</ul>
			<?php else: ?>
				<p>aucune palette de casse en stock</p>
			<?php endif ?>
			<p class="alert alert-primary">Cliquez sur une palette pour en afficher le contenu et la positionner sur une livraison</p>

		</div>
	</div>
	<div class="row">
		<div class="col text-right"><a href="#mini-menu" class="uplink">retour</a></div>
	</div>
	<div class="bg-separation"></div>
	<div class="row py-3">
		<div class="col">
			<h5 class="text-main-blue" id="traitement">Palettes à livrer :</h5>
		</div>
	</div>


	<?php if (!empty($existingExp)): ?>
		<?php foreach ($existingExp as $exp): ?>
			<div class="row pb-5">
				<div class="col">
					<p class="text-main-blue">Livraison n°<?=$exp['id']?> pour le magasin <?=$exp['btlec']?></p>
					<table class="table table-sm light-shadow" id="action">
						<thead>

							<tr>
								<th class="align-top">Palette 4919</th>
								<th class="align-top">Palette <?=$exp['btlec']?></th>
								<th class="align-top">Mail ctrl <br>palettes</th>
								<th class="align-top">Retour ctrl <br>palettes</th>
								<th class="align-top">Expédiée le :</th>
								<th class="align-top">Mail magasin</th>
								<th class="align-top">Facturation</th>
							</tr>
						</thead>


						<?php  $detailExp=getExpAndPalette($pdoCasse,$exp['id']); ?>
						<?php foreach ($detailExp as $detail): ?>
							<?php

							$ddPilote=formatExistingDate($detail['date_dd_pilote']);
							$retourPilote=formatExistingDate($detail['date_retour_pilote']);
							$livraison=formatExistingDate($detail['date_delivery']);
							$mailMag=formatExistingDate($detail['date_info_mag']);
							$fac=formatExistingDate($detail['date_fac']);

							?>
							<tr>
								<td><a href="detail-palette.php?id=<?=$detail['paletteid']?>"><?=$detail['palette']?></a></td>
								<td><?=$detail['contremarque']?></td>
								<td class="text-right"><?=$ddPilote?></td>
								<td class="text-right"><?=$retourPilote?></td>
								<td class="text-right"><?=$livraison?></td>
								<td class="text-right"><?=$mailMag?></td>
								<td class="text-right"><?=$fac?></td>
							</tr>

						<?php endforeach ?>
						<tr >
							<td colspan="2"><h5 class="text-main-blue py-3 heavy">Traitements :</h5></td>
							<td class="text-center py-3"><a href="pilote-dd.php?id=<?=$exp['id']?>" id="mailpilote"><button class="btn secOne"><i class="far fa-envelope pr-3"></i>Envoyer</button></a></td>
							<td class="text-center py-3"><a href="pilote-palette-ok.php?id=<?=$exp['id']?>"><button class="btn secTwo"><i class="far fa-edit pr-3"></i>Saisir</button></a></td>
							<td class="text-center py-3"><a href="delivery-ok.php?id=<?=$exp['id']?>"><button class="btn secThree"><i class="far fa-edit pr-3"></i>Saisir</button></a></td>
							<td class="text-center py-3"><a href="mag-info-casse.php?id=<?=$exp['id']?>" id="mailMag"><button class="btn secFour"><i class="far fa-envelope pr-3"></i>Envoyer</button></a></td>

							<?php if($exp['btlec']==2051 || $exp['btlec']==2054 || $exp['btlec']==2069):?>
								<td class="text-center py-3">
									<a href="casse-dashboard/casse-clos.php?id=<?=$exp['id']?>" ><button class="btn btn-primary"><i class="far fa-credit-card pr-3"></i>Clôturer</button></a>
								</td>
							<?php else: ?>
								<td class="text-center py-3">
									<a href="facturation-casse.php?id=<?=$exp['id']?>" id="mailMag"><button class="btn secFive"><i class="far fa-credit-card pr-3"></i>Facturer</button></a><br>
									<div class="mt-3"><a href="casse-clos.php?id=<?=$exp['id']?>" ><button class="btn btn-primary"><i class="far fa-credit-card pr-3"></i>Clôturer</button></a></div>
								</td>
							<?php endif ?>


						</tr>
					</table>
				</div>
			</div>
			<div class="row">



			</div>
		<?php endforeach ?>
	<?php else: ?>
		aucune palette n'a été sélectionnée pour une livraison magasin
	<?php endif ?>

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

		$('button#submit_search[type="submit"]').attr('disabled','disabled');

		$("input[name='field']").on("click", function(){
			$('button#submit_search[type="submit"]').removeAttr('disabled');

		})



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