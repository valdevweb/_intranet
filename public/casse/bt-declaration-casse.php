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

require ('../../Class/Form/Select.php');
require ('../../Class/Helpers.php');
require ('../../functions/global.fn.php');
require('casse-getters.fn.php');

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

$operateur=getOperateur($pdoUser);

$categories=getCategorie($pdoCasse);
$origines=getOrigine($pdoCasse);
$types=getTypecasse($pdoCasse);

// $lastInsertId=addCasse($pdoCasse,$dataArticle['gt'],$dataArticle['libelle'],$dataArticle['pcb'],$dataArticle['panf'],$dataArticle['fournisseur']);



function addCasse($pdoCasse,$gt,$libelle,$pcb,$panf,$fournisseur, $idPalette, $pfnp, $deee,$sacem,$deeeCodif){
	//panf =pu
	//valo = nbcolis *
	$uvc=$pcb * $_POST['nb_colis'];
	$valo=$uvc*$pfnp;
	$decote=round($valo/2);

	$req=$pdoCasse->prepare("INSERT INTO casses (date_casse, id_web_user, id_operateur, nb_colis, id_categorie, article, dossier, gt, designation, pcb, uvc,valo,pu, fournisseur, id_origine, id_type, id_palette, etat, last_maj, mt_mag, mt_decote,pfnp, deee,sacem, deee_codif) VALUES (:date_casse, :id_web_user, :id_operateur, :nb_colis, :id_categorie, :article, :dossier, :gt, :designation, :pcb, :uvc, :valo, :pu, :fournisseur, :id_origine, :id_type, :id_palette, :etat, :last_maj, :mt_mag, :mt_decote, :pfnp, :deee,:sacem, :deee_codif)" );

	$req->execute(array(
		':date_casse'	=>$_POST['date_casse'],
		':id_web_user'	=>$_SESSION['id_web_user'],
		':id_operateur'	=>$_POST['operateur'],
		':nb_colis'	=>$_POST['nb_colis'],
		':id_categorie'	=>$_POST['categorie'],
		':article'	=>$_POST['article'],
		':dossier'	=>$_POST['dossier'],
		':gt'	=>$gt,
		':designation'	=>$libelle,
		':pcb'	=>$pcb,
		':uvc'	=>$uvc,
		':valo'	=>$valo,
		':pu'	=>$panf,
		':fournisseur'	=>$fournisseur,
		':id_origine'	=>$_POST['origine'],
		':id_type'	=>$_POST['type'],
		':id_palette'	=>$idPalette,
		':etat'	=>0,
		':last_maj' =>date('Y-m-d H:i:s'),
		':mt_mag'		=> $valo,
		':mt_decote'	=>$decote,
		':pfnp'			=>$pfnp,
		':deee'			=>$deee,
		':sacem'		=>$sacem,
		':deee_codif'	=>$deeeCodif

	));
	if($req->rowCount()==1)
	{
		return $pdoCasse->lastInsertId();
	}
	else{
		return false;
	}
}


function addCasseDestroy($pdoCasse,$gt,$libelle,$pcb,$panf,$fournisseur, $idPalette, $pfnp,$deee,$sacem, $deeeCodif){
	$uvc=$pcb * $_POST['nb_colis'];
	$valo=$uvc*$pfnp;
	$req=$pdoCasse->prepare("INSERT INTO casses (date_casse, id_web_user, id_operateur, nb_colis, id_categorie, article, dossier, gt, designation, pcb, uvc,valo,pu, fournisseur, id_origine, id_type, id_palette, etat, last_maj,  detruit, mt_mag, mt_decote, pfnp, deee, sacem, deee_codif) VALUES (:date_casse, :id_web_user, :id_operateur, :nb_colis, :id_categorie, :article, :dossier, :gt, :designation, :pcb, :uvc, :valo, :pu, :fournisseur, :id_origine, :id_type, :id_palette, :etat, :last_maj, :detruit,  :mt_mag, :mt_decote, :pfnp, :deee, :sacem, :deee_codif)" );

	$req->execute(array(
		':date_casse'	=>$_POST['date_casse'],
		':id_web_user'	=>$_SESSION['id_web_user'],
		':id_operateur'	=>$_POST['operateur'],
		':nb_colis'	=>$_POST['nb_colis'],
		':id_categorie'	=>$_POST['categorie'],
		':article'	=>$_POST['article'],
		':dossier'	=>$_POST['dossier'],
		':gt'	=>$gt,
		':designation'	=>$libelle,
		':pcb'	=>$pcb,
		':uvc'	=>$uvc,
		':valo'	=>$valo,
		':pu'	=>$panf,
		':fournisseur'	=>$fournisseur,
		':id_origine'	=>$_POST['origine'],
		':id_type'	=>$_POST['type'],
		':id_palette'	=>$idPalette,
		':etat'	=>0,
		':last_maj' =>date('Y-m-d H:i:s'),
		':detruit'	=>1,
		':mt_mag'		=> 0,
		':mt_decote'	=>0,
		':pfnp'			=>$pfnp,
		':deee'			=>$deee,
		':sacem'		=>$sacem,
		':deee_codif'	=>$deeeCodif

	));
	if($req->rowCount()==1)
	{
		return $pdoCasse->lastInsertId();
	}
	else{
		return false;
	}
// return $req->errorInfo();
}




function paletteExist($pdoCasse){
	$palette=strtoupper($_POST['palette']);
	$req=$pdoCasse->prepare("SELECT id FROM palettes WHERE palette= :palette");
	$req->execute([
		':palette'	=>$palette
	]);
	if ($req) {
		return $req->fetch(PDO::FETCH_ASSOC);
	}
	else {
		return false;
	}
}

function addPalette($pdoCasse){
	//palette à détruire ou non, on met en statut 0

	$req=$pdoCasse->prepare("INSERT INTO palettes (palette, date_crea, statut) VALUES (:palette, :date_crea, :statut) ");
	$req->execute([
		':palette'	=>strtoupper($_POST['palette']),
		':date_crea'=>date('Y-m-d H:i:s'),
		':statut'=>0
	]);
	return $pdoCasse->lastInsertId();
}

function addSerials($pdoCasse,$lastInsertId,$serial)
{
	$req=$pdoCasse->prepare("INSERT INTO serials (id_casse,serial_nb) VALUES (:id_casse,:serial_nb)");
	$req->execute([
		':id_casse'			=>$lastInsertId,
		':serial_nb'		=>$serial
	]);
	return $req->rowCount();
// return $req->errorInfo();

}

function checkWebuser($pdoUser)
{
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
if(isset($_GET['idBa']))
{
// utilisé pour info article quand nouvelle déclaration (quand  modif, utilise getCasse)
	$dataArticle=getArticleFromBA($pdoQlik,$_GET['idBa']);

	if(isset($_POST['submit']))
	{
//  si GEM - TV, numéro de série obligatoires
		if($_POST['categorie']==2)
		{
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
		if(not_empty(['date_casse','operateur', 'article','dossier','categorie','origine','type','nb_colis','palette'])){
		}
		else{
			$errors[]="Veuillez remplir tous les champs";
		}
		if(count($errors)==0)
		{
// on vérifie si la palette existe déjà, si oui on récupère son id si non on l'ajoute et récupère son id
			$idPalette=paletteExist($pdoCasse);
			if($idPalette==false)
			{
				$idPalette=addPalette($pdoCasse);
			}
			else
			{
				$idPalette=$idPalette['id'];
			}
			if(isset($_POST['destroy'])){
// si à détruire a été selectionné, on peut clore le dossier
				$lastInsertId=addCasseDestroy($pdoCasse,$dataArticle['gt'],$dataArticle['libelle'],$dataArticle['pcb'],$dataArticle['panf'],$dataArticle['fournisseur'],$idPalette, $dataArticle['pfnp'],  $dataArticle['deee'],  $dataArticle['sacem'], $dataArticle['deee_codif']);
			}
			else{
				$lastInsertId=addCasse($pdoCasse,$dataArticle['gt'],$dataArticle['libelle'],$dataArticle['pcb'],$dataArticle['panf'],$dataArticle['fournisseur'],$idPalette,  $dataArticle['pfnp'],  $dataArticle['deee'],  $dataArticle['sacem'], $dataArticle['deee_codif']);

			}
			if($lastInsertId !=false)
			{
				echo "lastinsertid n'est pas faux";
				if(!empty($emptiedSerial))
				{

					for($i=0;$i<count($_POST['serial']);$i++)
					{
						if(!empty($_POST['serial'][$i]))
						{

							$add=addSerials($pdoCasse,$lastInsertId,$_POST['serial'][$i]);
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
		else
		{
			$errors[]='impossible d\'ajouter le dossier';
		}


		if(count($errors)==0)
		{
			$loc='Location:bt-casse-dashboard.php?success='.$lastInsertId;
			header($loc);
		}

	}

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
	<?= Helpers::returnBtn('bt-casse-dashboard.php'); ?>

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
								foreach($operateur as $op)
								{
// si l'opérateur a un code id_web_user, on met par défaut le select sur ce code
									if($idOp==$op['id'])
									{
										$selected="selected";
									}
									else{
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
							<input type="text" name="nb_colis" class="form-control" id="nb_colis" required >
						</div>
					</div>
					<div class="col-4">
						<div class="form-group">
							<label for="palette">Palette 4919</label>
							<input type="text" class="form-control" name="palette" id="palette" required>
						</div>
					</div>
					<div class="col-4 mt-4 pt-3">
						<div class="form-group form-check">
							<input type="checkbox" name="destroy" id="destroy" class="form-check-input">
							<label>A détruire</label>
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



		});
	</script>



	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>