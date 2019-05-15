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

require ('../../Class/Form/Select.php');


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getOperateur($pdoCasse){
	$req=$pdoCasse->query("SELECT CONCAT(prenom, ' ', nom) as operateur,id FROM operateurs WHERE mask=0");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$operateur=getOperateur($pdoCasse);

function getCategorie($pdoCasse){
	$req=$pdoCasse->query("SELECT * FROM categories WHERE mask=0 ORDER BY categorie");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$categories=getCategorie($pdoCasse);


function getOrigine($pdoCasse){
	$req=$pdoCasse->query("SELECT * FROM origines WHERE mask=0 ORDER BY origine");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$origines=getOrigine($pdoCasse);

function getTypecasse($pdoCasse){
	$req=$pdoCasse->query("SELECT * FROM type_casse WHERE mask=0 ORDER BY type");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$types=getTypecasse($pdoCasse);

function getArticle($pdoQlik){
	$req=$pdoQlik->prepare("SELECT `GESSICA.CodeDossier` as dossier, `GESSICA.CodeArticle` as article, `GESSICA.GT` as gt, `GESSICA.LibelleArticle` as libelle, `GESSICA.PCB` as pcb, `GESSICA.PANF` as valo, `GESSICA.CodeFournisseur` as cnuf, `GESSICA.NomFournisseur` as fournisseur,	id FROM basearticles WHERE id = :id");
	$req->execute(array(
		':id'	=>$_GET['idBa']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function addCasse($pdoCasse,$gt,$libelle,$pcb,$uvc,$valo,$pu,$fournisseur){
	foreach ($_POST as $key => $post)
	{
		// le champ opérateur est obligatoire, les autres non car ils peuvent saisir leur déclaration en plusieurs fois
		if($post=='' && $key!='operateur')
		{
			$_POST[$key]=null;
		}
	}
	$req=$pdoCasse->prepare("INSERT INTO casses (date_casse, id_web_user, id_operateur, nb_colis, id_categorie, article, dossier, gt, designation, pcb, uvc,valo,pu, fournisseur, id_origine, id_type, palette,cde, etat) VALUES (:date_casse, :id_web_user, :id_operateur, :nb_colis, :id_categorie, :article, :dossier, :gt, :designation, :pcb, :uvc, :valo, :pu, :fournisseur, :id_origine, :id_type, :palette, :cde, :etat)" );

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

	));
	return $pdoCasse->lastInsertId();
	// return $req->errorInfo();

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


//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

if(isset($_GET['idBa']))
{
	$dataArticle=getArticle($pdoQlik);
	$pu=round($dataArticle['valo']/$dataArticle['pcb'],2);
	$pu=number_format((float)$pu,2,'.',' ');
	$uvc=$dataArticle['pcb'];

}

if(checkWebuser($pdoCasse)){
	$idOp=checkWebuser($pdoCasse);
	$idOp=$idOp['id'];
}
else{
	$idOp='';
}

if(isset($_POST['submit']))
{
	$postData=$_POST;
	$lastInsertId=addCasse($pdoCasse,$dataArticle['gt'],$dataArticle['libelle'],$dataArticle['pcb'],$uvc,$dataArticle['valo'],$pu,$dataArticle['fournisseur']);
	$emptiedSerial = array_filter($_POST['serial']);
	if($lastInsertId>0)
	{
	}
	else{
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
		// $success[]='yeah';
	}

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
	<h1 class="text-main-blue pt-5 pb-3 ">Déclaration de casse</h1>

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
				<div class="col-3 bg-light-blue py-1"><?=$dataArticle['libelle']?></div>
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
				<div class="col-3 py-1 bg-light-blue text-right"> <?=$dataArticle['valo']?>&euro;</div>
				<div class="col-2 py-1">soit (PU)</div>
				<div class="col-3 py-1 bg-light-blue text-right"><?=$pu?>&euro;</div>
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
			<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>?idBa=<?=$_GET['idBa']?>">
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
						<select name="operateur" id="operateur" class="form-control">
							<option value="">Sélectionnez</option>
							<?php

							foreach($operateur as $op)
							{
								if($_POST['operateur']==$op['id'])
								{
									$selected="selected";
								}
								elseif($idOp==$op['id'])
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
						<?php
						$selectCat=new Select("categorie","Catégorie");
						// $selectCat->id='mask';
						$selectCat->createFirstOption("Sélectionnez");
						$selectCat->createOption($categories,'categorie','id');
						?>
					</div>
				</div>
				<div class="row">
					<div class="col-4">
						<?php
						$selectOrg=new Select("origine","Origine");
						$selectOrg->createFirstOption("Sélectionnez");
						$selectOrg->createOption($origines,'origine');
						?>
					</div>
					<div class="col-4">
						<?php
						$selectType=new Select("type","Type de casse");
						$selectType->createFirstOption("Sélectionnez");
						$selectType->createOption($types,'type','id');
						?>
					</div>


				</div>
				<div class="row">
					<div class="col-4">
						<div class="form-group">
							<label for="nb_colis">Nombre de colis</label>
							<input type="text" name="nb_colis" class="form-control" id="nb_colis" required>
						</div>
					</div>
					<div class="col-4">
						<div class="form-group">
							<label for="palette">Palette</label>
							<input type="text" class="form-control" name="palette" id="palette">
						</div>
					</div>
					<div class="col-4">
						<div class="form-group">
							<label for="cde">Commande</label>
							<input type="text" class="form-control" name="cde" id="cde">
						</div>
					</div>

				</div>


				<div class="row">


				</div>


				<div id="serial">

				</div>



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