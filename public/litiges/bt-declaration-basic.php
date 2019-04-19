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
function search($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM sca3  WHERE concat(mag,galec,btlec,city) LIKE :search ");
	$req->execute(array(
		':search' =>'%'.$_POST['search_strg'] .'%'
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
 // return $req->errorInfo();
}
// $search=search($pdoSav);

if(isset($_POST['search_form']))
{
	$mags=search($pdoBt);

}
else{

	$mags="";
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
	<h1 class="text-main-blue py-5 ">Déclarer un litige pour un magasin</h1>
	<!-- formulaire de recherche -->
	<div class="row mt-5">
		<div class="col-2"></div>
		<div class="col border shadow py-5">
			<p class="text-orange">Rechercher un magasin :</p>

			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="form-inline">
				<div class="form-group">
					<input class="form-control mr-5 pr-5" placeholder="nom de magasin, ville, panonceau galec, code btlec" name="search_strg" id="" type="text"  value="<?=isset($search_strg)? $search_strg: false?>">
				</div>
				<button class="btn btn-primary mr-5" type="submit" id="" name="search_form"><i class="fas fa-search pr-2"></i>Rechercher</button>
			</form>
		</div>
		<div class="col-2"></div>
	</div>
	<!-- ./formulaire de recherche-->

	<div class="row mt-5">
		<div class="col-1"></div>
		<div class="col">
			<table class="table">
				<thead class="thead-dark">
					<tr>
						<th>BTLEC</th>
						<th>Galec</th>
						<th>Déno</th>
						<th>Ville</th>
						<th>Centrale</th>
						<th class="text-center">Sélectionner</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(!empty($mags)){
						foreach ($mags as $mag)
						{
							echo '<tr>';
							echo'<td>'.$mag['btlec'].'</td>';
							echo'<td>'.$mag['galec'].'</td>';
							echo'<td>'.$mag['mag'].'</td>';
							echo'<td>'.$mag['city'].'</td>';
							echo'<td>'.$mag['centrale'].'</td>';
							echo'<td class="text-center"><a href="hidden-session.php?galec='.$mag['galec'].'"><i class="fas fa-hand-pointer"></i></td>';
							echo '</tr>';

						}
					}


					?>

				</tbody>
			</table>


		</div>
		<div class="col-1"></div>
	</div>

	<div class="row no-gutters">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<!-- ./row -->


	<!-- ./row -->
</div>

<?php

require '../view/_footer-bt.php';

?>



<?php

?>


