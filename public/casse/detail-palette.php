<?php
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

$errors=[];
$success=[];
require('casse-getters.fn.php');
require ('../../Class/Helpers.php');
require('../../Class/Table.php');

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



if(isset($_GET['id']))
{
	// info de la palette
	$paletteInfo=getPaletteInfo($pdoCasse, $_GET['id']);


	// recupère les expéditions en cours => normalement une seule expédition en cours possible
	// permet l'affichage du bouton adpaté : ajouter à une nvelle expé / ajouter l'expé du magasin X / positionnée sur l'exp du magasin x
	$existingExp=getActiveExp($pdoCasse);
}
else{
	$loc='Location:bt-casse-dashboard.php?error=1';
	header($loc);

}

// nouvelle expédition
if(isset($_POST['submitnew']))
{
	// on verifie que le code bt exisite
	$galec=getMagInfo($pdoBt);
	if(!$galec){
		$errors[]="Vous avez saisi le code BT : ".$_POST['mag'].". Il semblerait que ce code n'existe pas";
	}
	else{
		//on vérifie si le mag n'a pas une expédtion en cours
		$magExp=magExpAlreadyExist($pdoCasse, $_POST['mag']);
		if($magExp==false){
			// on crée l'exp
			$lastExp=addExp($pdoCasse, $galec['galec']);
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

if(isset($_POST['submitadd']))
{
	$added=updatePal($pdoCasse,$existingExp[0]['id']);
	if($added==1){
		$mag=$existingExp[0]['btlec'];
		$loc='Location:detail-palette.php?id='.$_GET['id'].'&mag='.$mag;
		header($loc);
	}
	else{
		$errors[]="impossible d'ajouter la palette à l'expédition";
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
			<?php
			$th=['N° casse','Article','Désignation','nb colis','pcb','valo'];
			$fields=['idcasse','article','designation','nb_colis','pcb','valo'];
			$tablePalette=new Table(['table', 'table-bordered', 'table-sm' ],'palette');
			$arrLink=[
				'href'	=>'detail-casse.php',
				'text'	=>'',
				'col'	=>'1',
				'param'	=>'id',

			];
			$link=$tablePalette->addLink($arrLink);
			$tablePalette->createBasicTable($th,$paletteInfo,$fields, $link);
			?>



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
	if($paletteInfo[0]['statut']==0 )
	{
		echo $formExp;
	}
	// si palette est positionnée sur une expé
	elseif($paletteInfo[0]['statut']==1)
	{
		echo '<div class="row">';
		echo '<div class="col">';
		echo '<p class="alert alert-primary"><i class="fas fa-info-circle pr-3"></i>Cette palette est positionnée sur l\'expédition du magasin '.$paletteInfo[0]['btlec'];
		echo '</div>';
		echo '</div>';

	}
	elseif($paletteInfo[0]['statut']==2)
	{
		echo '<div class="row">';
		echo '<div class="col">';
		echo '<p class="alert alert-primary"><i class="fas fa-info-circle pr-3"></i>Cette palette a été expédiée sur le magasin '.$paletteInfo[0]['btlec'];
		echo '</div>';
		echo '</div>';
	}

	?>




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