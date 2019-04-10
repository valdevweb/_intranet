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

function getDossier($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT id, dossier, etat_dossier,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea FROM dossiers WHERE galec= :galec ORDER BY dossiers.dossier DESC");
	$req->execute(array(
		':galec'	=>$_SESSION['id_galec']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function search($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT id, dossier, etat_dossier,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea FROM dossiers WHERE concat(dossiers.dossier,DATE_FORMAT(date_crea, '%d-%m-%Y')) LIKE :search AND galec= :galec  ORDER BY dossiers.dossier DESC");
	$req->execute(array(
		':search' =>'%'.$_POST['search_strg'] .'%',
		':galec'	=>$_SESSION['id_galec']

	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
 // return $req->errorInfo();
}
if(isset($_POST['search_form']))
{
	$listDossier=search($pdoLitige);
}

else
{
	$listDossier=getDossier($pdoLitige);
}

$etatDossier=['en cours', 'clos'];


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
	<h1 class="text-main-blue pt-5 pb-3">Vos dossiers litiges </h1>

	<!-- formulaire de recherche -->
	<div class="row my-5">
		<div class="col-2"></div>
		<div class="col border py-3">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
				<div class="row">
					<div class="col">
						<p class="text-red">Rechercher un litige :</p>
					</div>
				</div>
				<div class="row">
					<div class="col-6">

						<div class="form-group" id="equipe">
							<input class="form-control mr-5 pr-5" placeholder="n°litige, date (jj-mm-aaaa)" name="search_strg" id="" type="text"  value="<?=isset($search_strg)? $search_strg: false?>">
						</div>
					</div>
					<div class="col">
						<button class="btn btn-black mr-5" type="submit" id="" name="search_form"><i class="fas fa-search pr-2"></i>Rechercher</button>
						<button class="btn btn-red" type="submit" id="" name="clear_form"><i class="fas fa-eraser pr-2"></i>Effacer</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-2"></div>
	</div>
	<!-- ./formulaire de recherche-->

	<div class="row">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
<div class="row">
	<div class="col-lg-1"></div>
	<div class="col">
		<div class="alert alert-primary">
			<i class="fas fa-info-circle pr-3"></i>Vous pouvez cliquer sur les entêtes du tableau pour effectuer un tri
		</div>
	</div>
	<div class="col-lg-1"></div>

</div>

	<!-- start row -->
	<div class="row pb-5">
		<div class="col-lg-1"></div>
		<div class="col">
			<table class="table border" id="dossier">
				<thead class="thead-dark ">
					<th class="sortable">Dossier</th>
					<th class="sortable">Date déclaration</th>
					<th class="sortable">Etat</th>
					<th class="text-center">Détail</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($listDossier as $dossier)
				{
					if($dossier['etat_dossier']==1)
					{
						$etat="text-dark-grey";
					}
					else
					{
						$etat="text-red";

					}

					echo '<tr>';
					echo'<td>'.$dossier['dossier'].'</td>';
					echo'<td>'.$dossier['datecrea'].'</td>';
					echo'<td class="'.$etat.'">'.$etatDossier[$dossier['etat_dossier']].'</td>';
					echo'<td class="text-center"><a href="mag-detail-litige.php?id='.$dossier['id'].'"><i class="fas fa-book-reader"></i></a></td>';
					echo '</tr>';

				}

				?>
			</tbody>
		</table>
	</div>
		<div class="col-lg-1"></div>

</div>
<div class="row mt-5">
	<div class="col"></div>
</div>



</div>


<script src="../js/sorttable.js"></script>

<?php

require '../view/_footer-bt.php';

?>