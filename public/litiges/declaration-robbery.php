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
require "../../functions/stats.fn.php";
$descr="saisie déclaration vol" ;
$page=basename(__file__);
$action="";
// addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
addRecord($pdoStat,$page,$action, $descr, 208);

function searchFac($pdoQlik)
{
	$req=$pdoQlik->prepare("SELECT palette, facture, DATE_FORMAT(date_mvt, '%d-%m-%Y') as datemvt, galec FROM statsventeslitiges WHERE concat('0',facture) LIKE :facture GROUP BY  palette ORDER BY palette");
	$req->execute([
		':facture'		=>'%'.$_POST['fac'].'%'
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function searchDate($pdoQlik){
	$req=$pdoQlik->prepare("SELECT palette, facture, DATE_FORMAT(date_mvt, '%d-%m-%Y') as datemvt,galec  FROM statsventeslitiges WHERE date_mvt BETWEEN :date_start  AND :date_end AND btlec= :btlec GROUP BY  palette ORDER BY palette");
	$req->execute([
		':date_start'		=>$_POST['date_start'],
		':date_end'		=>$_POST['date_end'],
		':btlec'		=>$_POST['btlec']
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);

}

function getLastRobbery($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM robbery ORDER BY id DESC LIMIT 3");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$robberies=getLastRobbery($pdoLitige);

function getLastRobberyId($pdoLitige){
	$req=$pdoLitige->prepare("SELECT MAX(id) FROM robbery");
	$req->execute();
	return $req->fetch(PDO::FETCH_ASSOC);
}

// require_once '../../vendor/autoload.php';

require ('../../Class/Uploader.php');

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
if(isset($_POST['submit'])){
	if(!empty($_POST['fac']))	{
		$dataSearch=searchFac($pdoQlik);
	}
	else{
		$dataSearch=searchDate($pdoQlik);

	}
}



if(isset($_POST['choose']) && isset($_POST['vol'])){


	$_SESSION['palette']=[];
	foreach ($_POST as $key => $value) {
		if($key !='choose' && $key != 'galec' && $key!='vol' && $key !='new-vol'){
			$_SESSION['palette'][]=$key;

		}
	}
	$_SESSION['id_galec']=$_POST['galec'];
	$_SESSION['vol-id']=$_POST['vol'];

	header('location: declaration-stepone.php');
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
<!-- 	<div class="row">
		<div class="col">
			<h1 class="text-main-blue py-5 ">Déclaration d'un vol</h1>

		</div>
		<div class="col text-right">
			<img src="../img/litiges/thief200.png">

		</div>
	</div>
-->
<div class="row">
	<div class="col-auto">
		<!-- <img src="../img/litiges/thief200.png"> -->

	</div>
	<div class="col">
		<h1 class="text-main-blue py-5 ">Déclaration d'un vol</h1>

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
	<!-- <div class="col-lg-1 col-xxl-2"></div> -->
	<div class="col bg-alert bg-alert-primary">
		<div class="row">
			<div class="col-auto">
					<img src="../img/litiges/thief200.png">
				</div>
			<div class="col">




		<form method="post" id="search">

			<div class="row pb-1">
				<div class="col heavy form-title ml-5"><i class="fas fa-search  fa-lg pr-3"></i>Rechercher par code BT et période :</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-3">
					<div class="form-group">
						<label for="btlec">Code BT : </label>
						<input type="text" class="form-control" name="btlec" id="btlec">
					</div>
				</div>
				<div class="col-3">
					<div class="form-group">
						<label>du</label>
						<input type="date" class="form-control" name="date_start" id="date_start">
					</div>
				</div>
				<div class="col-3">
					<div class="form-group">
						<label>au </label>
						<input type="date" class="form-control" name="date_end" id="date_end">
					</div>
				</div>
			</div>
			<div class="row pb-1">
				<div class="col heavy form-title ml-5"><i class="fas fa-search fa-lg pr-3"></i>Rechercher par numéro de facture :</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-3">
					<div class="form-group">
						<label for="fac">Facture : </label>
						<input type="text" class="form-control" name="fac" id="fac">
					</div>
				</div>
				<div class="col-3"></div>

				<div class="col-3 text-right">
					<p>&nbsp;</p>
					<button class="btn btn-thief" name="submit" type="submit" ><i class="fas fa-search pr-3"></i>Rechercher</button>
					<div id="waitun"></div>
				</div>


			</div>

		</form>
	</div>
			</div>

		</div>
	<!-- <div class="col-lg-1 col-xxl-2"></div> -->

</div>

<?php
ob_start();
?>
<div class="row">
	<div class="col-lg-1 col-xxl-2"></div>
	<div class="col bg-alert bg-alert-grey">
		<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" id="submit">
			<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">1</span>Sélectionnez la ou les palettes concernées par le vol</p>
			<table class="table table-striped border border-white">
				<thead class="thead-dark">
					<tr>
						<th>Date facture</th>
						<th>Facture</th>
						<th>Palette</th>
						<th><i class="fas fa-times-circle"></i></th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(empty($dataSearch))
					{
						echo '<p>Aucun résultat ne correspond à votre recherche</p>';
					}
					else
					{

						foreach ($dataSearch as $sResult)
						{
							echo '<tr>';
							echo'<td>'.$sResult['datemvt'].'</td>';
							echo'<td>'.$sResult['facture'].'</td>';
							echo'<td>'.$sResult['palette'].'</td>';
							echo'<td>';
							echo '<div class="form-check article"><input class="form-check-input checkarticle" type="checkbox" name="'.$sResult['palette'].'"></div>';
							echo '</td></tr>';
						}
						echo '<input type="hidden" name="galec" value="'.$dataSearch[0]['galec'].'">';

					}
					?>
				</tbody>
			</table>
			<div class="alert alert-light">
				<div class="form-check text-right">
					<input type="checkbox" class="form-check-input" id="checkAll">
					<label class="form-check-label" for="checkAll">Sélectionner tout / désélectionner tout</label>
				</div>
			</div>
			<p class="text-main-blue heavy"><span class="step step-bg-blue mr-3">2</span>Sélectionnez le vol auquel se rattache la déclaration</p>
			<div class="row">
				<div class="col-4">
					<div class="form-group">
						<select name="vol" id="vol" class="form-control" required>
							<option value="">Sélectionner</option>
							<?php if (!empty($robberies)): ?>
								<?php foreach ($robberies as $robbery): ?>
									<option value="<?=$robbery['id']?>">Vol n° <?=$robbery['id']?></option>
								<?php endforeach ?>
								<option value="0">Pas de rattachement</option>
								<?php else: ?>
									<option value="0">Pas de rattachement</option>

								<?php endif ?>
							</select>
						</div>
					</div>
				</div>
				<p class="text-right"><button class="btn btn-primary" type="submit" name="choose" id="choose">Valider</button></p>


			</form>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<?php
	$tablePalette=ob_get_contents();
	ob_end_clean();
	if(isset($_POST['submit'])){
		echo $tablePalette;
	}


	?>
	<!-- ./row -->




</div>



<script type="text/javascript">

	$("#checkAll").click(function () {
		$('.article input:checkbox').not(this).prop('checked', this.checked);
			// $('input:checkbox').(#checkpalette).prop('unchecked', this.checked);
		});
	$("#search").submit(function( event )
	{
		$("#waitun" ).append('<i class="fas fa-spinner fa-spin"></i><span class="pl-3">Merci de patienter pendant la recherche</span>')
	});

</script>

<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>