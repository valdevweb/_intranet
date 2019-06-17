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
require('casse-getters.fn.php');

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

$operateur=getOperateur($pdoCasse);
$categories=getCategorie($pdoCasse);
$origines=getOrigine($pdoCasse);
$types=getTypecasse($pdoCasse);




function addCasse($pdoCasse,$gt,$libelle,$pcb,$uvc,$valo,$pu,$fournisseur){
	foreach ($_POST as $key => $post)
	{
// le champ opérateur est obligatoire, les autres non car ils peuvent saisir leur déclaration en plusieurs fois
		if($post=='' && $key!='operateur')
		{
			$_POST[$key]=null;
		}
	}
	$req=$pdoCasse->prepare("INSERT INTO casses (date_casse, id_web_user, id_operateur, nb_colis, id_categorie, article, dossier, gt, designation, pcb, uvc,valo,pu, fournisseur, id_origine, id_type, palette,cde, etat, last_maj) VALUES (:date_casse, :id_web_user, :id_operateur, :nb_colis, :id_categorie, :article, :dossier, :gt, :designation, :pcb, :uvc, :valo, :pu, :fournisseur, :id_origine, :id_type, :palette, :cde, :etat, :last_maj)" );

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
		':pu'	=>$pu,
		':fournisseur'	=>$fournisseur,
		':id_origine'	=>$_POST['origine'],
		':id_type'	=>$_POST['type'],
		':palette'	=>$_POST['palette'],
		':cde'	=>$_POST['cde'],
		':etat'	=>0,
		':last_maj' =>date('Y-m-d H:i:s')


	));
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

function checkWebuser($pdoCasse)
{
	$req=$pdoCasse->prepare("SELECT id FROM operateurs WHERE id_web_user= :id_web_user");
	$req->execute([
		'id_web_user'		=>$_SESSION['id_web_user']
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

// utilisé pour info article + info déclaration quand dossier déjà ouvert


function getSerials($pdoCasse){
	$req=$pdoCasse->prepare("SELECT * FROM serials WHERE  id_casse= :id_casse");
	$req->execute([
		':id_casse'		=>$_GET['idKs']
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function updateCasse($pdoCasse){
	foreach ($_POST as $key => $value)
	{
// le champ opérateur est obligatoire, les autres non car ils peuvent saisir leur déclaration en plusieurs fois
		if($value=='')
		{
			$_POST[$key]=null;
		}
	}
	$req=$pdoCasse->prepare("UPDATE casses
		SET id_operateur= :id_operateur, id_categorie= :id_categorie, id_origine= :id_origine, id_type= :id_type, palette= :palette , cde= :cde, last_maj= :last_maj WHERE id= :id" );
	$req->execute(array(
		':id'		=>$_GET['idKs'],
		':id_operateur'	=>$_POST['operateur'],
		':id_categorie'	=>$_POST['categorie'],
		':id_origine'	=>$_POST['origine'],
		':id_type'	=>$_POST['type'],
		':palette'	=>$_POST['palette'],
		':cde'	=>$_POST['cde'],
		':last_maj' =>date('Y-m-d H:i:s')

	));
	return $req->rowCount();
// return $req->errorInfo();

}

function deleteSerials($pdoCasse)
{
	$req=$pdoCasse->prepare("DELETE FROM serials WHERE id_casse= :id ");
	$req->execute([
		':id'		=>$_GET['idKs']

	]);
	return $req->rowCount();
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
	$pu=round($dataArticle['panf']/$dataArticle['pcb'],2);
	$pu=number_format((float)$pu,2,'.',' ');
	$uvc=$dataArticle['pcb'];
	if(isset($_POST['submit']))
	{
		$postData=$_POST;
		$lastInsertId=addCasse($pdoCasse,$dataArticle['gt'],$dataArticle['libelle'],$dataArticle['pcb'],$uvc,$dataArticle['panf'],$pu,$dataArticle['fournisseur']);
		// vide le tableau si il est vide (multidimentional array => jamais vide même si pas de donnée ppuis que contient au moins un tableau vide ou non)
		$emptiedSerial = array_filter($_POST['serial']);
		if($lastInsertId>0)
		{
		}
		else
		{
			$errors[]='impossible d\'ajouter le dossier';
// exit();
		}

		if(count($errors)==0){


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
		if(count($errors)==0)
		{
			$loc='Location:bt-casse-dashboard.php?success='.$lastInsertId;
			header($loc);
		}

	}

}

// déclaration en cours
if(isset($_GET['idKs']))
{
	$dataArticle=getCasse($pdoCasse, $_GET['idKs']);

	//  on a pas forcement de numeros de series à chaque fois et ils puevent être saisi ulterieurement
	if(getSerials($pdoCasse)){
		$serials=getSerials($pdoCasse);
	}
	else{
		$serials='';
	}

	if(isset($_POST['submit']))
	{

		$majCasse=updateCasse($pdoCasse);
		if($majCasse==1)
		{
			$emptiedSerial = array_filter($_POST['serial']);
			if(!empty($emptiedSerial))
			{
				//  on supprimer les nbum"o de serie si il en existe
				if(count($serials)>0)
				{
					$deletedSerials=deleteSerials($pdoCasse);
					if($deletedSerials>0)
					{
						// $success[]="suppression des numéros de séries réussie";
					}
					else{
						$errors[]="Impossible de supprimer les numéros de series pour la mise à jour de la table";
					}
				}
				if(count($errors)==0)
				{
					for($i=0;$i<count($_POST['serial']);$i++)
					{
						if(!empty($_POST['serial'][$i]))
						{
							$id=$_GET['idKs'];
							$add=addSerials($pdoCasse,$id,$_POST['serial'][$i]);
							if($add!=1){
								$errors[]="impossible d'ajouter le numéro de serie";
							}
						}
					}
				}
			}
			$success[]='mise à jour correctement effectuée';
		}
		else
		{
			$errors[]='impossible de mettre à jour le dossier';
		}

	}


}

// vérifie si le user connecté à un id_web_user dans la table opérateur => si oui opérateur sélectionné par défaut dans la ld opérateur
if(checkWebuser($pdoCasse)){
	$idOp=checkWebuser($pdoCasse);
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
				<div class="col-2 py-1">soit (uvc)</div>
				<div class="col-3 py-1 bg-light-blue text-right"></div>
				<div class="col py-1"></div>
			</div>
			<div class="row">
				<div class="col-2 py-1">Valo :</div>
				<div class="col-3 py-1 bg-light-blue text-right"></div>
				<div class="col-2 py-1">soit (PU)</div>
				<div class="col-3 py-1 bg-light-blue text-right"><?=isset($dataArticle['pu']) ? $dataArticle['pu'] :$pu?>&euro;</div>
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
								if(isset($dataArticle['id_operateur']))
								{
									foreach($operateur as $op)
									{
										if($dataArticle['id_operateur']==$op['id'])
										{
											$selected="selected";

										}
										else{
											$selected='';
										}
										echo '<option value="'.$op['id'].'" '.$selected.'>'.$op['operateur'].'</option>';

									}
								}
								else
								{
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
							<input type="text" name="article" class="form-control" id="article" value="<?= isset($dataArticle['article']) ? $dataArticle['article'] :'';?>">
						</div>
					</div>
					<div class="col-4">
						<div class="form-group">
							<label label="Dossier">Dossier</label>
							<input type="text" name="dossier" id="dossier" class="form-control"  value="<?= isset($dataArticle['dossier']) ? $dataArticle['dossier'] :'';?>">
						</div>
					</div>
					<div class="col-4">
						<div class="form-group">
							<label for="categorie">Catégorie</label>
							<select name="categorie" id="categorie" class="form-control">
								<option value="">Sélectionnez</option>
								<?php
								if(isset($dataArticle['id_categorie']))
								{
									foreach($categories as $categorie)
									{

										if($dataArticle['id_categorie']==$categorie['id'])
										{
											$selected="selected";
										}
										else{
											$selected='';
										}
										echo '<option value="'.$categorie['id'].'" '.$selected.'>'.$categorie['categorie'].'</option>';
									}
								}
								else
								{
									foreach($categories as $categorie)
									{

										if($_POST['categorie']==$categorie['id'])
										{
											$selected="selected";
										}
										else{
											$selected='';
										}
										echo '<option value="'.$categorie['id'].'" '.$selected.'>'.$categorie['categorie'].'</option>';
									}
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
								if(isset($dataArticle['id_origine']))
								{
									foreach($origines as $origine)
									{

										if($dataArticle['id_origine']==$origine['id'])
										{
											$selected="selected";
										}
										else{
											$selected='';
										}
										echo '<option value="'.$origine['id'].'" '.$selected.'>'.$origine['origine'].'</option>';
									}
								}
								else
								{
									foreach($origines as $origine)
									{

										if($_POST['origine']==$origine['id'])
										{
											$selected="selected";
										}
										else{
											$selected='';
										}
										echo '<option value="'.$origine['id'].'" '.$selected.'>'.$origine['origine'].'</option>';
									}
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
								if(isset($dataArticle['id_type']))
								{
									foreach($types as $type)
									{

										if($dataArticle['id_type']==$type['id'])
										{
											$selected="selected";
										}
										else{
											$selected='';
										}
										echo '<option value="'.$type['id'].'" '.$selected.'>'.$type['type'].'</option>';
									}
								}
								else
								{
									foreach($types as $type)
									{

										if($_POST['type']==$type['id'])
										{
											$selected="selected";
										}
										else{
											$selected='';
										}
										echo '<option value="'.$type['id'].'" '.$selected.'>'.$type['type'].'</option>';
									}
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
							<input type="text" name="nb_colis" class="form-control" id="nb_colis" required value="<?=isset($dataArticle['nb_colis']) ? $dataArticle['nb_colis'] :''?>">
						</div>
					</div>
					<div class="col-4">
						<div class="form-group">
							<label for="palette">Palette</label>
							<input type="text" class="form-control" name="palette" id="palette" value="<?=isset($dataArticle['palette']) ? $dataArticle['palette'] :''?>">
						</div>
					</div>
					<div class="col-4">

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