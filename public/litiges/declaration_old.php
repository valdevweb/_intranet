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
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function getMinAndMaxDate($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT date_mvt, MIN(date_mvt) as mini, MAX(date_mvt) as maxi, DATE_FORMAT(MIN(date_mvt),'%d-%m-%Y') as ministr, DATE_FORMAT(MAX(date_mvt),'%d-%m-%Y') as maxistr FROM qlik");
	$req->execute(array(
		':galec'	=>$_SESSION['id_galec']

	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
$dateBounderies=getMinAndMaxDate($pdoLitige);

function getQlik($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM qlik WHERE galec LIKE :galec GROUP BY facture ORDER BY facture");
	$req->execute(array(
		':galec'	=>$_SESSION['id_galec']

	));
	return $req->fetchAll(PDO::FETCH_ASSOC);

}
$dataQlik=getQlik($pdoLitige);

function getFacDetails($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM qlik WHERE facture = :facture ORDER BY palette, article");
	$req->execute(array(
		':facture'	=>$_POST['form_fac']

	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}



if(isset($_POST['submit']))
{
	$dataFac=getFacDetails($pdoLitige);
	echo $_POST['form_min'];
	echo '<br>';
	echo $_POST['form_max'];


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
	<h1 class="text-main-blue py-5 ">Déclarer un litige</h1>
	<!-- start row -->
	<div class="row">
		<div class="col">
			<p>Pour faciliter la déclaration de votre de litige, le formulaire ci dessous vous permet d'aller rechercher les articles concernés grâce au numéro de facture. Vous pouvez limiter la recherche </p>
		</div>
	</div>
	<!-- ./row -->

	<!-- start row -->
	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col bg-alert bg-alert-primary">
			<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>">
				<!-- start row -->
				<!-- start row -->
				<div class="row pb-5">
					<div class="col">
						<h4><i class="fas fa-search pr-3"></i>Rechercher </h4>
					</div>
				</div>
				<!-- ./row -->

				<div class="row pb-3 pl-5">
					<div class="col">
						<p><span class="step">1</span> Par période (seul trois mois d'historique sont disponibles) :</p>

					</div>
				</div>
				<!-- start row -->
				<div class="row pb-3">
					<div class="col-auto">
						<div class="form-group">
							du <input type="date" name="form_min" min="<?=$dateBounderies['mini']?>" value="<?=$dateBounderies['mini']?>">
						</div>
					</div>
					<div class="col-1 text-center"> au</div>
					<div class="col-auto">
						<div class="form-group">
							<input type="date" name="form_max" max="<?=$dateBounderies['maxi']?>" value="<?=$dateBounderies['maxi']?>">
						</div>
					</div>

				</div>


				<!-- ./row -->
				<!-- ./row -->
				<div class="row pl-5">
					<div class="col">
						<p class="pb-3"><span class="step">2</span>Par numéro de facture :</p>
					</div>
				</div>
				<div class="row">
					<div class="col-auto">
						<div class="form-group">
							<select class="form-control" id="form_fac" name="form_fac">
								<option value="">Numéro de facture</option>
								<?php
								foreach ($dataQlik as $qlik) {
									echo '<option value="'.$qlik['facture'].'">'.$qlik['facture'].'</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div class="col">
						<p class="text-right"><button class="btn btn-primary" type="submit" id="" name="submit">Afficher</button></p>
					</div>

				</div>
				<div class="row pl-5">
					<div class="col">
						<p class="pb-3"><span class="step">3</span>Par numéro de palette :</p>
					</div>
				</div>

			</form>

		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<!-- ./row -->





	<?php
	?>


</div>
<?php

require '../view/_footer-bt.php';

?>