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



//---------------------------------------
//	DESCR
//---------------------------------------
/*
Page qui sert à saisir une déclaration initiale => GET['idBa']= article slectionné dans bt-casse dashboard
ou à modifier /enrichier une déclaration existante GET[idKc]
*/



require '../../Class/Db.php';
require ('../../Class/casse/CasseHelpers.php');
require ('../../Class/casse/CasseDao.php');
require ('../../Class/casse/PalettesDao.php');


require ('../../Class/Form/Select.php');
require ('../../Class/Helpers.php');
require ('../../functions/global.fn.php');
require('casse-getters.fn.php');

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');
$pdoCasse=$db->getPdo('casse');

$paletteDao=new PalettesDao($pdoCasse);
$casseDao=new CasseDao($pdoCasse);

$operateur=getOperateur($pdoUser);

$categories=getCategorie($pdoCasse);
$origines=getOrigine($pdoCasse);
$types=getTypecasse($pdoCasse);
$listPalette=CasseHelpers::getPaletteActive($pdoCasse);

function addSerials($pdoCasse,$lastInsertId,$serial){
	$req=$pdoCasse->prepare("INSERT INTO serials (id_casse,serial_nb) VALUES (:id_casse,:serial_nb)");
	$req->execute([
		':id_casse'			=>$lastInsertId,
		':serial_nb'		=>$serial
	]);
	return $req->rowCount();
}

function checkWebuser($pdoUser){
	$req=$pdoUser->prepare("SELECT id FROM intern_users WHERE id_web_user= :id_web_user");
	$req->execute([
		'id_web_user'		=>$_SESSION['id_web_user']
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}


//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

// déclaration initiale
if(isset($_GET['idBa'])){
	$dataArticle=getArticleFromBA($pdoQlik,$_GET['idBa']);
}

if(isset($_POST['submit']))	{
	include 'bt-declaration-casse/01-save-new-casse.php';
}




// vérifie si le user connecté à un id_web_user dans la table opérateur => si oui opérateur sélectionné par défaut dans la ld opérateur
if(checkWebuser($pdoUser)){
	$idOp=checkWebuser($pdoUser);
	$idOp=$idOp['id'];
}
else{
	$idOp='';
}


$today=new DateTime();
$today=$today->format('Y-m-d');


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
	<?= Helpers::returnBtn('casse-dashboard.php'); ?>

	<h1 class="text-main-blue pb-3 ">Déclaration de casse</h1>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

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
				<!-- <div class="col-1"></div> -->


			</div>
			<div class="row mb-1">
				<div class="col-2 py-1">PCB : </div>
				<div class="col-3 py-1 bg-light-blue text-right"><?=$dataArticle['pcb']?></div>
				<div class="col-2 py-1">PFNP :</div>
				<div class="col-3 py-1 bg-light-blue text-right"><?=isset($dataArticle['pfnp']) ? $dataArticle['pfnp'] :''?>&euro;</div>
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
							<input type="date" name="date_casse" class="form-control" value="<?=$today?>" id="date_casse">
						</div>
					</div>
					<div class="col">

						<div class="form-group">
							<label for="operateur">Opérateur</label>
							<select name="operateur" id="operateur" class="form-control" required>
								<option value="">Sélectionnez</option>
								<?php
								foreach($operateur as $op){
// si l'opérateur a un code id_web_user, on met par défaut le select sur ce code
									if($idOp==$op['id']){
										$selected="selected";
									}else{
										$selected='';
									}
									echo '<option value="'.$op['id'].'" '.$selected.'>'.$op['operateur'].'</option>';

								}


								?>
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
								<?php

								foreach($categories as $categorie)
								{

									if(isset($_POST['categorie']) && $_POST['categorie']==$categorie['id'])
									{
										$selected="selected";
									}
									else{
										$selected='';
									}
									echo '<option value="'.$categorie['id'].'" '.$selected.'>'.$categorie['categorie'].'</option>';
								}

								?>
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
								<?php
								foreach($origines as $origine)
								{

									if(isset($_POST['origine']) && $_POST['origine']==$origine['id'])
									{
										$selected="selected";
									}
									else{
										$selected='';
									}
									echo '<option value="'.$origine['id'].'" '.$selected.'>'.$origine['origine'].'</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-4">
						<div class="form-group">
							<label for="type">Type de casse</label>
							<select name="type" id="type" class="form-control">
								<option value="">Sélectionnez</option>
								<?php
								foreach($types as $type)
								{

									if(isset($_POST['type']) && $_POST['type']==$type['id'])
									{
										$selected="selected";
									}
									else{
										$selected='';
									}
									echo '<option value="'.$type['id'].'" '.$selected.'>'.$type['type'].'</option>';
								}

								?>
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
										<option value="">Sélectionner</option>
										<option value="new">Nouvelle palette</option>
										<?php foreach ($listPalette as $idPalette => $palette): ?>
											<option value="<?=$idPalette?>"><?=$listPalette[$idPalette]?></option>
										<?php endforeach ?>
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
								Palette déstinée à la destruction
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

				<?php if(isset($dataArticle['nb_colis']))
				{
					for ($i=0; $i < $dataArticle['nb_colis'] ; $i++)
					{
						if(isset($serials[$i]['serial_nb'])){
							$value= $serials[$i]['serial_nb'];
						}
						else{
							$value="";
						}
						echo '<div class="row"><div class="col-4"><div class="form-group">';
						echo '<label>N° de série : </label><input type="text" name="serial[]" class="form-control" value="'.$value.'">';
						echo '</div><div class="col"></div></div></div>';
					}
				}

				else{
					echo '<div id="serial"></div>';

				}


				?>

				<!-- <i class="fas fa-search"></i> -->
				<button class="btn btn-primary" type="submit" name="submit">Enregistrer</button>

			</form>

		</div>
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
					$.ajax({
						url:'bt-declaration-casse/ajax-get-palette.php',
						method:"POST",
						data:{id_palette:paletteSelected},
						success:function(data){
							console.log(data);
							$('#type-palette').empty();

							$('#type-palette').text(data);
						}
					});
				}
			});

		});
	</script>



	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>