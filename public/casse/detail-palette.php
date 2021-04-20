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

$errors=[];
$success=[];

require '../../config/db-connect.php';

require('casse-getters.fn.php');
require ('../../Class/Helpers.php');
require('../../Class/Table.php');
require('../../Class/MagDao.php');
require('../../Class/Mag.php');


//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// $descr="saisie déclaration mag hors qlik" ;
// $page=basename(__file__);
// $action="";
// // addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
// addRecord($pdoStat,$page,$action, $descr, 208);

require_once '../../vendor/autoload.php';





function getMagInfo($pdoBt){
	$req=$pdoBt->prepare("SELECT galec FROM sca3 WHERE btlec= :btlec");
	$req->execute([
		':btlec'	=>$_POST['mag']
	]);
	if($req){
		return $req->fetch(PDO::FETCH_ASSOC);
	}
	else{
		return false;
	}
}

function addExp($pdoCasse, $galec){
	$req=$pdoCasse->prepare("INSERT INTO exps (btlec, galec, date_crea) VALUES (:btlec, :galec, :date_crea)");
	$req->execute([
		':btlec'		=>$_POST['mag'],
		':galec'		=>$galec,
		':date_crea'	=>date('Y-m-d H:i:s')
	]);
	return $pdoCasse->lastInsertId();
}

function updatePal($pdoCasse,$lastExp){
	$req=$pdoCasse->prepare("UPDATE palettes SET statut= :statut, id_exp= :id_exp, contremarque= :contremarque WHERE id= :id");
	$req->execute([
		':statut'		=>1,
		':id_exp'		=>$lastExp,
		':id'			=>$_GET['id'],
		':contremarque'	=>$_POST['contremarque']

	]);
	return $req->rowCount();
}



if(isset($_GET['id'])){
	// info de la palette
	$paletteInfo=getPaletteInfo($pdoCasse, $_GET['id']);


	// recupère les expéditions en cours => normalement une seule expédition en cours possible
	// permet l'affichage du bouton adpaté : ajouter à une nvelle expé / ajouter l'expé du magasin X / positionnée sur l'exp du magasin x
	$existingExp=getActiveExp($pdoCasse);
	$serials=getSerialsPalette($pdoCasse, $_GET['id']);

}
else{
	$loc='Location:bt-casse-dashboard.php?error=1';
	header($loc);

}

// nouvelle expédition
if(isset($_POST['submitnew']))
{
	// on verifie que le code bt exisite
	$magDao=new MagDao($pdoMag);
	$magInfo=$magDao->getMagByBtlec($_POST['mag']);

	if(empty($magInfo)){
		$errors[]="Vous avez saisi le code BT : ".$_POST['mag'].". Il semblerait que ce code n'existe pas";
	}
	else{
		//on vérifie si le mag n'a pas une expédtion en cours
		$magExp=magExpAlreadyExist($pdoCasse, $_POST['mag']);
		if($magExp==false){
			// on crée l'exp
			$lastExp=addExp($pdoCasse, $magInfo->getGalec());
		}
		else{
				// on récupère l'id de l'exp
			$lastExp=$magExp['id'];
		}
		if($lastExp>0){
			$added=updatePal($pdoCasse,$lastExp);

			if($added==1){
				$mag=$_POST['mag'];
				$loc='Location:detail-palette.php?id='.$_GET['id'].'&mag='.$mag;
				header($loc);

			}
			else{
				$errors[]="impossible de créer l'expédition";
			}
		}
	}
}



if(isset($_GET['mag']))
{
	$success[]="la palette a bien été ajoutée à l'expédition du magasin ".$_GET['mag'];

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
		<div class="col-auto"> <img src="../img/litiges/broken-ico.jpg"> </div>
		<div class="col">
			<div class="row">
				<div class="col">
					<?= Helpers::returnBtn('bt-casse-dashboard.php'); ?>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<h1 class="text-main-blue py-5 ">Palette <?=$paletteInfo[0]['palette']?></h1>
				</div>
			</div>
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

	<div class="row">
		<div class="col">


			<table class="table table-sm">
				<thead class="thead-dark">
					<tr>
						<th>Casse</th>
						<th>Article</th>
						<th>Désignation</th>
						<th>Nb colis</th>
						<th>SN</th>
						<th>PCB</th>
						<th>Valo</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($paletteInfo as $key => $detail): ?>

						<tr>
							<td><a href="detail-casse.php?id=<?=$detail['idcasse']?>"><?=$detail['idcasse']?></a></td>
							<td><?=$detail['article']?></td>
							<td><?=$detail['designation']?></td>
							<td><?=$detail['nb_colis']?></td>
							<?php if (isset($serials[$detail['idcasse']])): ?>
								<td>
								<?php foreach ($serials[$detail['idcasse']] as $key => $sn): ?>
									<?=$sn['serial_nb']?><br>
								<?php endforeach ?>
								</td>
								<?php else: ?>
									<td></td>
							<?php endif ?>
							<td><?=$detail['pcb']?></td>
							<td><?=$detail['valo']?></td>
						</tr>
					<?php endforeach ?>

				</tbody>
			</table>




		</div>
	</div>

	<div class="row">
		<div class="col text-right">
			<a href="g-pdf-detail-palette-valo.php?id=<?=$_GET['id']?>"  target="_blank"><button class="btn btn-primary"><i class="fas fa-print pr-3" name="print-valo"></i>Avec Valo</button></a>
			<a href="g-pdf-detail-palette.php?id=<?=$_GET['id']?>"  target="_blank"><button class="btn btn-black"><i class="fas fa-print pr-3" name="print"></i>Sans valo</button></a>
		</div>
	</div>
	<?php
	// formulaire pour positionner une palette sur une expédition
	ob_start();
	?>
	<div class="row pb-5">
		<div class="col">
			<p class="text-red">Positionner la palette sur une expédition : </p>
			<form action="<?=htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" id="form-new-exp">
				<div class="row pb-5">
					<div class="col"></div>

					<div class="col-auto">
						<p class="pt-2">Code BTLec du magasin :</p>
					</div>

					<div class="col-2">
						<div class="form-group">
							<input type="text" name="mag" class="form-control" placeholder="4xxx" id="mag" required>
						</div>
					</div>
					<div class="col-auto">
						<p class="pt-2">Palette contremarquée :</p>
					</div>
					<div class="col">
						<div class="form-group">
							<input type="text" name="contremarque" class="form-control" placeholder="palette" required>
						</div>
					</div>
					<div class="col-auto">
						<button class="btn btn-red" name="submitnew"><i class="fas fa-paper-plane pr-3"></i>Ajouter</button>
					</div>
				</div>

			</form>

		</div>
	</div>
	<?php
	$formExp=ob_get_contents();
	ob_end_clean();
	// si la palette  n'ets pas positionnée sur une edxpédition et si elle n'a pas étét expédiée c'est à dire statut palette =0
	// on affiche soit un bouton pour créer une nouvelle expédition soit un bouton pour ajouter à l'expédition en cours

	?>
	<?php if ($paletteInfo[0]['statut']==0): ?>
		<!-- en cours -->
		<?php echo $formExp; ?>
		<!-- expé en cours -->
		<?php elseif($paletteInfo[0]['statut']==1 || $paletteInfo[0]['statut']==2): ?>
			<div class="pb-5">

				<div class="row pb-3">
					<div class="col">
						<div class="text-main-blue"><img src ="../img/litiges/arrow.svg" class="arrow pr-3">Etat de la palette :</div>
					</div>

				</div>

				<div class="row">
					<div class="col-md-5 col-lg-4 ml-5"><img src="../img/icons/ico-cross.svg" class="pr-1">Palette contremarque : </div>
					<div class="col-md-2 bg-light-blue text-right">
						<?= !is_null($paletteInfo[0]['contremarque']) ? $paletteInfo[0]['contremarque'] :'' ?>
					</div>
					<div class="col-md"></div>
				</div>
				<div class="row">
					<div class="col-md-5 col-lg-4 ml-5"><img src="../img/icons/ico-cross.svg" class="pr-1">Magasin /pôle destinataire : </div>
					<div class="col-md-2 bg-light-blue text-right">
						<?= !is_null($paletteInfo[0]['btlec']) ? $paletteInfo[0]['btlec'] :'' ?>

					</div>
					<div class="col-md"></div>
				</div>
				<div class="row">
					<div class="col-md-5 col-lg-4 ml-5"><img src="../img/icons/ico-cross.svg" class="pr-1">Demande de contrôle le : </div>
					<div class="col-md-2 bg-light-blue text-right">
						<?= !is_null($paletteInfo[0]['dateddpilote']) ? $paletteInfo[0]['dateddpilote'] :'' ?>

					</div>
					<div class="col-md"></div>
				</div>
				<div class="row">
					<div class="col-md-5 col-lg-4 ml-5"><img src="../img/icons/ico-cross.svg" class="pr-1">Retour de contrôle le </div>
					<div class="col-md-2 bg-light-blue text-right">
						<?= !is_null($paletteInfo[0]['dateretourpilote']) ? $paletteInfo[0]['dateretourpilote'] :'' ?>

					</div>
					<div class="col-md"></div>
				</div>
				<div class="row">
					<div class="col-md-5 col-lg-4 ml-5"><img src="../img/icons/ico-cross.svg" class="pr-1">Expédition le  : </div>
					<div class="col-md-2 bg-light-blue text-right">
						<?= !is_null($paletteInfo[0]['datedelivery']) ? $paletteInfo[0]['datedelivery'] :'' ?>
					</div>
					<div class="col-md"></div>
				</div>
				<div class="row">
					<div class="col-md-5 col-lg-4 ml-5"><img src="../img/icons/ico-cross.svg" class="pr-1">Information magasin /pôle le : </div>
					<div class="col-md-2 bg-light-blue text-right">
						<?= !is_null($paletteInfo[0]['dateinfomag']) ? $paletteInfo[0]['dateinfomag'] :'' ?>

					</div>
					<div class="col-md"></div>
				</div>
				<?php if (!is_null($paletteInfo[0]['certificat'])): ?>
					<div class="row">
						<div class="col-md-5 col-lg-4 ml-5"><img src="../img/icons/ico-cross.svg" class="pr-1">Certificat de destruction : </div>
						<div class="col-md-2 bg-light-blue text-right">
							<a href="<?=URL_UPLOAD?>\casse\<?=$paletteInfo[0]['certificat']?>" target="_blank">voir / télécharger</a>

						</div>
						<div class="col-md"></div>
					</div>

				<?php endif ?>
			</div>
		<?php endif ?>




		<!-- ./container -->
	</div>

	<script type="text/javascript">
		$(document).ready(function(){

			$('#form-new-exp').submit(function(){
				var mag=$('#mag').val();

				boxState="Confirmez la préparation de l'expédition pour le magasin " +mag +" ?";
				return confirm(boxState);

			});
		});

	</script>


	<?php
	require '../view/_footer-bt.php';
	?>