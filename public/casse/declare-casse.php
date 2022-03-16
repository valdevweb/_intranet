<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


require '../../Class/Db.php';
require('../../Class/BaDao.php');
require('../../Class/FormHelpers.php');
require('../../Class/casse/PalettesDao.php');
require ('../../Class/casse/CasseDao.php');

require('../../Class/casse/CasseHelpers.php');
require ('../../functions/global.fn.php');



$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');
$pdoCasse=$db->getPdo('casse');


$baDao=new BaDao($pdoQlik);
$paletteDao=new PalettesDao($pdoCasse);
$casseDao=new CasseDao($pdoCasse);


$operateur=CasseHelpers::getOperateur($pdoUser);

$categories=CasseHelpers::getCategorie($pdoCasse);
$origines=CasseHelpers::getOrigine($pdoCasse);
$types=CasseHelpers::getTypecasse($pdoCasse);
$listPaletteDestruction=CasseHelpers::getPaletteActiveDestruction($pdoCasse, 1);
$listPaletteNorm=CasseHelpers::getPaletteActiveDestruction($pdoCasse,0 );



function checkWebuser($pdoUser){
	$req=$pdoUser->prepare("SELECT id FROM intern_users WHERE id_web_user= :id_web_user");
	$req->execute([
		'id_web_user'		=>$_SESSION['id_web_user']
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}


if(isset($_POST['search_article'])){
	$articles=$baDao->getArtByArt($_POST['article']);
}


if(isset($_GET['id'])){
	$dataArticle=$baDao->getArtByIdBa($_GET['id']);
}
$idOp='';

$findOp=checkWebuser($pdoUser);
if(!empty($findOp)){
	$idOp=$findOp['id'];
}

if(isset($_POST['insert_casse'])){


	if(!not_empty(['date_casse','operateur', 'article','dossier','categorie','origine','type','nb_colis', 'palette_existante'])){
		$errors[]="Veuillez remplir tous les champs";
	}
	if($_POST['palette_existante']=="new" && empty($_POST['palette'])){
		$errors[]="Veuillez saisir le numéro de la nouvelle palette";
	}
	if($_POST['palette_existante']=="new" && !isset($_POST['destroy'])){
		$errors[]="Vous devez préciser si il s'agit  d'une palette avec des produits à détruire ou non";

	}
//  si GEM - TV, numéro de série obligatoires
	if($_POST['categorie']==2){
	//	$_POST['serial'] renvoie un tableau de tableau
	// vide le tableau si il est vide (multidimentional array => jamais vide même si pas de donnée ppuis que contient au moins un tableau vide ou non)
	// nb de numero de series demandés
		$nbSerialsAsked=count($_POST['serial']);
		$emptiedSerial = array_filter($_POST['serial']);
	// nb de serie remplis
		$nbSerialsGiven=count($emptiedSerial);
		if($nbSerialsAsked != $nbSerialsGiven){
			$errors[]="Il manque des numeros de séries, merci de compléter";
		}
	}




	if(count($errors)==0){
// on vérifie si la palette existe déjà, si oui on récupère son id si non on l'ajoute et récupère son id
		if($_POST['palette_existante']!="new"){
			$paletteChoisie=$paletteDao->getPalette($_POST['palette_existante']);
			$idPalette=$paletteChoisie['id'];
			$detruit=$paletteChoisie['destruction'];
		}else{
			if($_POST['destroy']==1){
				$detruit=1;
				$idAffectation=3;
			}else{
				$detruit=0;
				$idAffectation=null;

			}
			$idPalette=$paletteDao->insertPalette($_POST['palette'],$detruit, $statut=0, $idAffectation);
		}


		$uvc=$dataArticle['pcb'] * $_POST['nb_colis'];
		$valo=$uvc*$dataArticle['pfnp'];
		$etat=0;
		$decote=round($valo/2);
		$mtMag=$valo;

		$lastInsertId=$casseDao->insertCasse($_POST['date_casse'], $_POST['operateur'], $_POST['nb_colis'],$_POST['categorie'],$_POST['article'], $_POST['dossier'], $dataArticle['ean'], $dataArticle['gt'],$dataArticle['libelle'], $dataArticle['pcb'],$uvc,$valo, $dataArticle['panf'],$dataArticle['fournisseur'], $_POST['origine'], $_POST['type'], $idPalette, $etat, $detruit, $mtMag, $decote, $dataArticle['pfnp'], $dataArticle['deee'],$dataArticle['sorecop'],$dataArticle['codif_deee'], $dataArticle['ppi']);

		if($lastInsertId !=false){
			if(!empty($emptiedSerial)){
				for($i=0;$i<count($_POST['serial']);$i++){
					if(!empty($_POST['serial'][$i])){
						$add=$casseDao->addSerials($lastInsertId,$_POST['serial'][$i]);
						if($add!=1){
							$errors[]="impossible d'ajouter le numéro de serie";
						}
					}
				}
			}
		}
		else{
			$errors[]='impossible d\'enregistrer les infos casses';
		}
	}
	else{
		$errors[]='impossible d\'ajouter le dossier';
	}


	if(count($errors)==0){
		$loc='Location:casse-dashboard.php#palette-'.$idPalette;
		header($loc);
	}
}


//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Déclarer une casse</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>


	<div class="row ">
		<div class="col  ">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" >
				<div class="row">
					<div class="col-4">
						<div class="form-group">
							<label for="article">Code de l'article : </label>
							<input class="form-control mr-5 pr-5" placeholder="code article" name="article" id="article" type="text"  value="<?=isset($article) ? $article : false?>">
						</div>
					</div>
					<div class="col pt-4 mt-2">
						<button class="btn btn-primary " type="submit" id="" name="search_article"><i class="fas fa-search pr-2"></i>Rechercher</button>
					</div>

				</div>
			</form>
		</div>
	</div>
	<?php if(isset($articles)): ?>
		<div class="row mb-3">
			<div class="col">
				<h5 class="text-main-blue py-3 text-center">Votre recherche pour le code article : <span class="heavy bg-grey patrick-hand px-3"><?=$_POST['article']?></span></h5>
				<p>Veuillez sélectionner le dossier qui correspond en cliquant sur le sigle<i class="far fa-check-circle pl-3 text-main-blue"></i> de la ligne correspondante<br>
					<strong>Attention, </strong>le stock affiché est le stock à j-1
				</p>

				<table class="table table-sm" id="dossiers">
					<thead class="thead-dark">
						<tr>
							<th>dossiers</th>
							<th>lbelleé</th>
							<th>fournisseur</th>
							<th>pcb</th>
							<th>valo</th>
							<th>stock</th>
							<th>déclarer</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($articles as $key => $article): ?>
							<tr>
								<td><?=$article['dossier']?></td>
								<td><?=$article['libelle']?></td>
								<td><?=$article['fournisseur']?></td>
								<td><?=$article['pcb']?></td>
								<td><?=$article['panf']?></td>
								<td><?=$article['stock_entrepot']?></td>
								<td><a href="?id=<?=$article['id_ba']?>"><i class="far fa-check-circle pr-3"></i></a></td>
							</tr>
						<?php endforeach ?>

					</tbody>
				</table>

			</div>
		</div>
	<?php endif ?>

	<?php if (isset($_GET['id'])): ?>
		<div class="row mb-3">
			<div class="col border p-3">
				<div class="row">
					<div class="col">
						<h5 class="text-main-blue pb-3">Article : <?=$dataArticle['article'] .' - '.$dataArticle['dossier']?></h5>
					</div>
				</div>
				<div class="row mb-1 ">
					<div class="col-2 py-1">Désignation : </div>
					<div class="col-3 bg-light-blue py-1"><?=isset($dataArticle['libelle']) ? $dataArticle['libelle'] : $dataArticle['designation']?></div>
					<div class="col-2 py-1">Fournisseur :</div>
					<div class="col  py-1 bg-light-blue"><?=$dataArticle['fournisseur']?></div>
					<div class="col-1 py-1">GT :</div>
					<div class="col-1 py-1 bg-light-blue"><?=$dataArticle['gt']?></div>
				</div>
				<div class="row mb-1">
					<div class="col-2 py-1">PCB : </div>
					<div class="col-3 py-1 bg-light-blue text-right"><?=$dataArticle['pcb']?></div>
					<div class="col-2 py-1">PFNP :</div>
					<div class="col-3 py-1 bg-light-blue text-right"><?=$dataArticle['pfnp']?>&euro;</div>
					<div class="col py-1"></div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col border p-3">
				<div class="row">
					<div class="col ">
						<h5 class="text-main-blue pb-3">Ajout infos casse :</h5>
					</div>
				</div>

				<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>?<?=key($_GET).'='.$_GET[key($_GET)]?>">
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="date_casse">Date</label>
								<input type="date" name="date_casse" class="form-control" value="<?=date('Y-m-d')?>" id="date_casse">
							</div>
						</div>
						<div class="col">

							<div class="form-group">
								<label for="operateur">Opérateur</label>
								<select name="operateur" id="operateur" class="form-control" required>
									<option value="">Sélectionnez</option>
									<?php foreach ($operateur as $key => $op): ?>
										<option value="<?=$op['id']?>"  <?=FormHelpers::restoreSelected($op['id'],$idOp)?>><?=$op['operateur']?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col">
						</div>
					</div>
					<div class="row">
						<div class="col-4">
							<div class="form-group">
								<label label="article">Article</label>
								<input type="text" name="article" class="form-control" id="article" value="<?= isset($dataArticle['article']) ? $dataArticle['article'] :'';?>" readonly>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label label="Dossier">Dossier</label>
								<input type="text" name="dossier" id="dossier" class="form-control"  value="<?= isset($dataArticle['dossier']) ? $dataArticle['dossier'] :'';?>" readonly>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label for="categorie">Catégorie</label>
								<select name="categorie" id="categorie" class="form-control">
									<option value="">Sélectionnez</option>

									<?php foreach ($categories as $categorie): ?>
										<option value="<?=$categorie['id']?>" <?=FormHelpers::restoreSelectedPost($categorie['id'], 'categorie')?>><?=$categorie['categorie']?></option>

									<?php endforeach ?>

								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-4">
							<div class="form-group">
								<label for="origine">Origine</label>
								<select name="origine" id="origine" class="form-control">
									<option value="">Sélectionnez</option>
									<?php foreach ($origines as $origine): ?>
										<option value="<?=$origine['id']?>" ><?=$origine['origine']?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label for="type">Type de casse</label>
								<select name="type" id="type" class="form-control">
									<option value="">Sélectionnez</option>
									<?php foreach ($types as $type): ?>
										<option value="<?=$type['id']?>" ><?=$type['type']?></option>

									<?php endforeach ?>
								</select>
							</div>
						</div>


					</div>
					<div class="row">
						<div class="col-4">
							<div class="form-group">
								<label for="nb_colis">Nombre de colis</label>
								<input type="text" name="nb_colis" class="form-control" pattern="[0-9]+" title="Seuls les chiffres sont autorisés" id="nb_colis" required >
							</div>
						</div>
						<div class="col-3">
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label for="palette_existante">Sélectionner une palette 4919</label>
										<select class="form-control" name="palette_existante" id="palette_existante" required>
											<option value="">palettes existantes :</option>
											<optgroup label="palettes destruction :" class="text-success">
												<?php foreach ($listPaletteDestruction as $idPalette => $palette): ?>
													<option value="<?=$idPalette?>"><?=$listPaletteDestruction[$idPalette]?></option>
												<?php endforeach ?>
											</optgroup>
											<optgroup label="palettes magasin :" class="text-primary">
												<?php foreach ($listPaletteNorm as $idPalette => $palette): ?>
													<option value="<?=$idPalette?>"><?=$listPaletteNorm[$idPalette]?></option>
												<?php endforeach ?>
											</optgroup>
											<optgroup label="nouvelle palette">
												<option value="new">créer une palette</option>
											</optgroup>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col" id="type-palette">
								</div>
							</div>
						</div>
						<div class="col-5" id="new_palette">
							<div class="row">
								<div class="col">
									<div class="row">
										<div class="col">
											<div class="form-group">
												<label for="palette">Palette 4919</label>
												<input type="text" class="form-control" name="palette" id="palette">
											</div>
										</div>
									</div>

								</div>
								<div class="col-auto">
									Palette destinée à la destruction
									<div class="form-check">
										<input class="form-check-input" type="radio" value="1" id="destroy_yes" name="destroy">
										<label class="form-check-label" for="destroy_yes">Oui</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="radio" value="0" id="destroy_no" name="destroy">
										<label class="form-check-label" for="destroy_no">Non</label>
									</div>
								</div>
							</div>
						</div>


					</div>


					<?php if (isset($dataArticle['nb_colis'])): ?>
						<?php for ($i=0; $i < $dataArticle['nb_colis'] ; $i++): ?>
							<?php
							if(isset($serials[$i]['serial_nb'])){
								$value= $serials[$i]['serial_nb'];
							}
							else{
								$value="";
							}
							?>
						<?php endfor ?>
						<div class="row"><div class="col-4"><div class="form-group">
							<label>N° de série : </label><input type="text" name="serial[]" class="form-control" value="<?=$value?>">
						</div><div class="col"></div></div></div>

					<?php else: ?>
						<div id="serial"></div>
					<?php endif ?>

					<button class="btn btn-primary" type="submit" name="insert_casse">Enregistrer</button>

				</form>

			</div>
		</div>


	<?php endif ?>



</div>



<script type="text/javascript">
	$(document).ready(function(){
		$('#new_palette').hide();
		$('#nb_colis').focusout(function(){
			var serialInput='<div class="row"><div class="col-4"><div class="form-group">';
			serialInput+='<label>N° de série : </label><input type="text" name="serial[]" class="form-control">';
			serialInput+='</div><div class="col"></div></div></div>';

			var nbserial = $(this).val();
			$('#serial').empty();
			for (var i = 0; i < nbserial; i++)
			{
				$('#serial').append(serialInput);
			}
		});
		$('#palette_existante').on("change", function(){
			var paletteSelected=$('#palette_existante').val();
			if (paletteSelected=="new") {
				$('#new_palette').show();

			}else{
				$('#new_palette').hide();

			}
		});
	});
</script>


<?php
require '../view/_footer-bt.php';
?>