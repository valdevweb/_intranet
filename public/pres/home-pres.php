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
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';



//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function getPres($pdoBt){
	$req=$pdoBt->query("SELECT *, DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea FROM pres WHERE mask=0 ORDER BY date_crea DESC");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function addPres($pdoBt){
	$req=$pdoBt->prepare("INSERT INTO pres (name, date_crea) VALUES (:name, :date_crea)");
	$req->execute([
		':name'	=>$_POST['name'],
		':date_crea'=> date('Y-m-d H:i:s')

	]);
	return $req->rowCount();
}


$allPres=getPres($pdoBt);


if(isset($_POST['add-pres'])){
	$addpres=addPres($pdoBt);
	if($addpres==1){
		$successQ='?success=1';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}

}
if(isset($_GET['success'])){
	$arrSuccess=[
		1=>'présentation ajoutée',
		2 =>'présentation supprimée',
	];
	$success[]=$arrSuccess[$_GET['success']];
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
	<h1 class="text-main-blue py-5 ">Présentations</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<div class="row my-3">
		<div class="col-lg-1"></div>
		<div class="col"><h5>Listes des présentations existantes</h5></div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php if (isset($allPres)): ?>
				<!-- start table -->
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Présentation</th>
							<th>Date de création</th>
							<th class="text-center">Supprimer</th>
							<th class="text-center">Editer</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($allPres as $pres): ?>
							<tr>
								<td><a href="display-pres.php?id_pres=<?=$pres['id'].'&iddoc=0' ?>" ><?=$pres['name']?></a></td>
								<td><?=$pres['datecrea']?></td>
								<td class="text-center"><a class="delete" id="<?=$pres['name']?>" href="delete-pres.php?id=<?=$pres['id']?>"><i class="fas fa-trash-alt"></i></a></td>
								<td class="text-center"><a href="upload-doc-pres.php?id=<?=$pres['id']?>"><i class="far fa-edit"></i></a></td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row my-3">
		<div class="col-lg-1"></div>
		<div class="col"><h5>Ajouter une présentation</h5></div>
		<div class="col-lg-1"></div>
	</div>

	<div class="row mb-5 pb-5">
		<div class="col-lg-1"></div>
		<div class="col">
			<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="name">Nom de la présentation</label>
							<input type="text" class="form-control" id="name" name="name">
						</div>
					</div>
					<div class="col mt-4 pt-2">
						<button class="btn btn-primary" type="submit" name="add-pres">Ajouter</button>

					</div>
				</div>

			</form>

		</div>
		<div class="col-lg-1"></div>
	</div>
	<!-- ./container -->
</div>
<script type="text/javascript">
	$(function(){
		$('.delete').click(function(){
			 var pres_name=$(this).attr('id');
			 return confirm("Etes vous sûr de vouloir supprimer la présentation du " + pres_name +"?");
		});
});
</script>
<?php
require '../view/_footer-bt.php';
?>