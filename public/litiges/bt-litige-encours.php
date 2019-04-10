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

function getAllDossier($pdoLitige)
{
	$req=$pdoLitige->prepare("
		SELECT dossiers.id as id_main,dossiers.dossier,date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,user_crea,dossiers.galec,etat_dossier, mag, centrale, sca3.btlec,vingtquatre, etat
		FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		ORDER BY dossiers.dossier DESC");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function search($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT dossiers.id as id_main,dossiers.dossier,date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,user_crea,dossiers.galec,etat_dossier, mag, centrale, sca3.btlec,vingtquatre, etat
		FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		WHERE concat(dossiers.dossier,mag,dossiers.galec,DATE_FORMAT(date_crea, '%d-%m-%Y'),sca3.btlec) LIKE :search ");
	$req->execute(array(
		':search' =>'%'.$_POST['search_strg'] .'%'
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
 // return $req->errorInfo();
}
if(isset($_POST['search_form']))
{
	$fAllActive=search($pdoLitige);

}

else
{
	$fAllActive=getAllDossier($pdoLitige);


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
	<h1 class="text-main-blue pt-5 pb-3">Listing des dossiers litiges </h1>

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
							<input class="form-control mr-5 pr-5" placeholder="n°litige, date, magasin, galec" name="search_strg" id="" type="text"  value="<?=isset($search_strg)? $search_strg: false?>">
						</div>
					</div>
					<div class="col">
						<button class="btn btn-black mr-5" type="submit" id="" name="search_form"><i class="fas fa-search pr-2"></i>Rechercher</button>
						<button class="btn btn-red" type="submit" id="" name="clear_form"><i class="fas fa-eraser pr-2"></i>Effacer</button>
					</div>
				</div>
			</form>

			<div class="row pt-5">
				<div class="col-6">
					<p class="text-red ">Filtrer les résultats :</p>
				</div>
				<div class="col">
					 <button class="btn btn-red " id="hide-clos"> <i class="fas fa-filter pr-3"></i>Dossiers en cours</button>
				</div>
			</div>

		</div>
	<div class="col-2"></div>
</div>
<!-- ./formulaire de recherche-->
<div class="row pt-2 pb-2">
	<div class="col">
		<div>
			<div class="alert alert-primary">
				<i class="fas fa-info-circle pr-3"></i>Vous pouvez cliquez sur le nom du magasin pour afficher l'historique de ses réclamations
			</div>
		</div>
	</div>
	<div class="col-auto text-right">
		<a href="xl-encours.php" class="btn btn-red"> <i class="fas fa-file-excel pr-3"></i>Exporter</a>
	</div>
</div>
<div class="row">
	<div class="col">
		<?php
		include('../view/_errors.php');
		?>
	</div>
	<div class="col-lg-1 col-xxl-2"></div>
</div>
<!-- start row -->
<div class="row">
	<div class="col">
		<table class="table border" id="dossier">
			<thead class="thead-dark ">
				<th class="sortable">Dossier</th>
				<th class="sortable">Date déclaration</>
					<th class="sortable">Magasin</th>
					<th class="sortable">Code BT</th>
					<th class="sortable">Centrale</th>
					<th class="sortable">Etat</th>
					<th class="sortable text-center">Détails</th>
					<th class="sortable text-center">24/48h</th>

				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($fAllActive as $active)
				{
					if($active['vingtquatre']==1)
					{
						$vingtquatre='<img src="../img/litiges/2448_ico.png">';

					}
					else
					{
						$vingtquatre="";
					}

					if($active['etat']=="Cloturé")
					{
						$etat="text-dark-grey";
					}
					else
					{
						$etat="text-red";

					}

					echo '<tr class="'.$active['etat_dossier'].'">';
					echo'<td>'.$active['dossier'].'</td>';
					echo'<td>'.$active['datecrea'].'</td>';
					echo'<td><a href="stat-litige-mag.php?galec='.$active['galec'].'">'.$active['mag'].'</a></td>';
					echo'<td>'.$active['btlec'].'</td>';
					echo'<td>'.$active['centrale'].'</td>';
					echo'<td class="'.$etat.'">'.$active['etat'].'</td>';
					echo'<td class="text-center"><a href="bt-detail-litige.php?id='.$active['id_main'].'"><i class="fas fa-eye"></i></a></td>';
					echo '<td class="text-center">'.$vingtquatre .'</td>';
					echo '</tr>';

				}

				?>
			</tbody>
		</table>
	</div>

</div>
<!-- ./row -->
<!-- ./row -->



</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#hide-clos').click(function(){
			$('#dossier > tbody > tr').each(function(){
				if ($(this).find('td.text-dark-grey').length)
				{
					$(this).toggleClass('hide');
					// console.log($(this).find('img.test').length);
				}
				else{
					console.log('na');
				}
			});
		});
	});

</script>
<script src="../js/sorttable.js"></script>

<?php

require '../view/_footer-bt.php';

?>