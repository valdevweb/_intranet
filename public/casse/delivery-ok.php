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
require '../../config/db-connect.php';

require 'casse-getters.fn.php';
require ('../../Class/Helpers.php');
require ('../../Class/MagDao.php');
require('../../Class/Mag.php');
require('../../Class/casse/TrtDao.php');
$trtDao=new TrtDao($pdoCasse);



function updatePalette($pdoCasse, $idPalette){
	$req=$pdoCasse->prepare("UPDATE palettes SET statut=2, date_delivery= :date_delivery WHERE id= :id");
	$req->execute([
		':date_delivery'	=>$_POST['delivery'],
		':id'			=>$idPalette
	]);
	return $req->rowCount();
	// return $req->errorInfo();
}


function getMagInfo($pdoMag, $btlec){
	$req=$pdoMag->prepare("SELECT galec FROM mag WHERE id= :id");
	$req->execute([
		':id'	=>$btlec
	]);
	if($req){
		return $req->fetch(PDO::FETCH_ASSOC);
	}
	else{
		return false;
	}
}

function addExp($pdoCasse, $galec, $btlec){

	$req=$pdoCasse->prepare("INSERT INTO exps (btlec, galec, date_crea) VALUES (:btlec, :galec, :date_crea)");
	$req->execute([
		':btlec'		=>$btlec,
		':galec'		=>$galec,
		':date_crea'	=>date('Y-m-d H:i:s')
	]);
	return $pdoCasse->lastInsertId();
}

function updateNumExp($pdoCasse,$newExpId,$key){
	$req=$pdoCasse->prepare("UPDATE palettes SET statut= :statut, id_exp= :id_exp WHERE id= :id");
	$req->execute([
		':statut'		=>1,
		':id_exp'		=>$newExpId,
		':id'			=>$key,

	]);
	return $req->rowCount();
}


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$today=date('Y-m-d');




if(isset($_GET['id'])){
	$numExp=$_GET['id'];
	// on récupère les palettes pour afficher un champ de commentaire en face de chaque
	$listPalette=getExpAndPalette($pdoCasse,$numExp);

}






if(isset($_POST['submit'])){



	// on vérifié si une case a été décochée (hidden =unchecked)
	$unchecked=false;
	foreach ($_POST as $idPalette => $value) {
		if($_POST[$idPalette]=='unchecked'){
			$unchecked=true;
		}
	}
	if(!$unchecked){
		$majError=false;
		foreach ($_POST as $idPalette => $value) {
			if($idPalette!='submit' && $idPalette!='delivery'){
	// traitement simple :  toutes les palettes ont été livrées, on met juyste à jour la date de livraison sur les palettes et leur statut
				$up=updatePalette($pdoCasse, $idPalette);
				if($up!=1){
					$majError=true;
				}
			}
		}
	}
	else{
		//on créé une nouvelle expédition,
		//on met à jour les palettes livrées avec la date et le statut expédié (2)
		//on récupère l'id de la palette qui n'a pas été livrée et on met à jour son id_exp
		$magDao=new MagDao($pdoMag);
		$galec=$magDao->getMagByBtlec($listPalette[0]['btlec']);

		$newExpId=addExp($pdoCasse, $galec->getGalec(), $listPalette[0]['btlec']);


		foreach ($_POST as $idPalette => $value) {
			if($idPalette!='submit' && $idPalette!='delivery'){
				if($value=='unchecked'){
					$up=updateNumExp($pdoCasse,$newExpId,$idPalette);
				}
			}
		}
	}
	$trtDao->insertTrtHisto($_GET['id'], $_GET['id_trt']);
		header('Location:casse-dashboard.php?majExp');
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
		<div class="row">
			<div class="col">
				<?= Helpers::returnBtn('casse-dashboard.php'); ?>
			</div>
		</div>
		<h1 class="text-main-blue pb-5 ">Livraison expédition n°<?=$listPalette[0]['expid']?> pour le <?=$listPalette[0]['btlec']?></h1>

		<div class="row">
			<div class="col-lg-1"></div>
			<div class="col">
				<?php
				include('../view/_errors.php');
				?>
			</div>
			<div class="col-lg-1"></div>
		</div>
		<div class="row mb-5">
			<div class="col">
				<p class="alert alert-primary"><i class="fas fa-info-circle pr-3"></i>Saisissez la date de livraison et le cas échéant, décochez les palettes qui n'auraient pas été livrées</p>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'].'&id_trt='.$_GET['id_trt']?>" method="post" class="pb-5">
					<div class="row">
						<div class="col-4">
							<?php foreach ($listPalette as $palette): ?>
								<div class="row">
									<div class="col">
										<div class="form-group form-check">
											<input type="hidden" name="<?=$palette['paletteid']?>" value="unchecked" />
											<input type="checkbox" class="form-check-input" name="<?=$palette['paletteid']?>" id="<?=$palette['paletteid']?>" value="1" checked>
											<label class="form-check-label" for="<?=$palette['paletteid']?>" >Palette <?=$palette['palette']?></label>
										</div>
									</div>
								</div>
							<?php endforeach ?>
						</div>
						<div class="col-4 d-flex align-items-end">
							<div class="row">
								<div class="col-auto">
									<div class="form-group">
										<label for="delivery">Date de livraison : </label>
										<input type="date" name="delivery" id="delivery" class="form-control" value="<?=$today?>">
									</div>
								</div>

								<div class="col-3 mt-4 pt-2">
									<div class="text-right">
										<button class="btn btn-primary" name="submit"><i class="fas fa-check-circle pr-3"></i>Valider</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>


		</div>
	</div>
	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>