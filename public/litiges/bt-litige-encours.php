<?php
// session_cache_limiter('private_no_expire');
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
		SELECT dossiers.id as id_main,dossiers.dossier,date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,user_crea,dossiers.galec,etat_dossier, mag, centrale, sca3.btlec,vingtquatre, valo, etat, ctrl_ok
		FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		ORDER BY dossiers.dossier DESC");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function search($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT dossiers.id as id_main,dossiers.dossier,date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,user_crea,dossiers.galec,etat_dossier, mag, centrale, sca3.btlec,vingtquatre, valo, etat
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

function getEtat($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT etat,id FROM etat WHERE mask=0 ORDER BY etat");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function filterJustDate($pdoLitige)
{

	$req=$pdoLitige->prepare("SELECT dossiers.id as id_main,dossiers.dossier,date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,user_crea,dossiers.galec,etat_dossier, mag, centrale, sca3.btlec,vingtquatre, etat, valo
		FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		WHERE DATE_FORMAT(date_crea,'%Y-%m-%d') BETWEEN :date_start AND :date_end  ORDER BY dossier  DESC");
	$req->execute(array(
		':date_start'		=>$_POST['date_start'],
		':date_end'		=>$_POST['date_end'],
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getSumValoAll($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT  sum(valo) as valo_totale FROM dossiers");
	$req->execute();
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getSumValoJustDate($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT  sum(valo) as valo_totale FROM dossiers WHERE DATE_FORMAT(date_crea,'%Y-%m-%d') BETWEEN :date_start AND :date_end");
$req->execute(array(
		':date_start'		=>$_POST['date_start'],
		':date_end'		=>$_POST['date_end'],
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
function getSumValoJustDateGpType($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT  sum(valo) as valo_etat, dossiers.id_etat, etat.etat, count(dossiers.id) as nbEtat FROM dossiers LEFT JOIN etat ON id_etat=etat.id WHERE DATE_FORMAT(date_crea,'%Y-%m-%d') BETWEEN :date_start AND :date_end GROUP BY etat");
	$req->execute(array(
		':date_start'		=>$_POST['date_start'],
		':date_end'		=>$_POST['date_end'],
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function getSumValoFilter($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT  sum(valo) as valo_totale FROM dossiers WHERE DATE_FORMAT(date_crea,'%Y-%m-%d') BETWEEN :date_start AND :date_end  AND id_etat= :id_etat ");
$req->execute(array(
		':date_start'		=>$_POST['date_start'],
		':date_end'		=>$_POST['date_end'],
		':id_etat'		=>$_POST['etat'],

	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
function getSumValoFilterGpType($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT  sum(valo) as valo_etat, dossiers.id_etat, etat.etat, count(dossiers.id) as nbEtat FROM dossiers LEFT JOIN etat ON id_etat=etat.id WHERE DATE_FORMAT(date_crea,'%Y-%m-%d') BETWEEN :date_start AND :date_end  AND id_etat= :id_etat  GROUP BY etat");
	$req->execute(array(
		':date_start'		=>$_POST['date_start'],
		':date_end'		=>$_POST['date_end'],
		':id_etat'		=>$_POST['etat'],

	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function searchGpType($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT sum(valo) as valo_etat, dossiers.id_etat, etat.etat, count(dossiers.id) as nbEtat FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec

		WHERE concat(dossiers.dossier,mag,dossiers.galec,DATE_FORMAT(date_crea, '%d-%m-%Y'),sca3.btlec) LIKE :search GROUP BY etat");
	$req->execute(array(
		':search' =>'%'.$_POST['search_strg'] .'%'
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
 // return $req->errorInfo();
}


function searchValo($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT  sum(valo) as valo_totale FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		WHERE concat(dossiers.dossier,mag,dossiers.galec,DATE_FORMAT(date_crea, '%d-%m-%Y'),sca3.btlec) LIKE :search ");
	$req->execute(array(
		':search' =>'%'.$_POST['search_strg'] .'%'
	));
	return $req->fetch(PDO::FETCH_ASSOC);
 // return $req->errorInfo();
}

function getSumValoAllGpType($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT  sum(valo) as valo_etat, dossiers.id_etat, etat.etat, count(dossiers.id) as nbEtat FROM dossiers LEFT JOIN etat ON id_etat=etat.id GROUP BY etat");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function filter($pdoLitige)
{

	$req=$pdoLitige->prepare("SELECT dossiers.id as id_main,dossiers.dossier,date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,user_crea,dossiers.galec,etat_dossier, mag, centrale, sca3.btlec,vingtquatre, etat, valo
		FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		WHERE DATE_FORMAT(date_crea,'%Y-%m-%d') BETWEEN :date_start AND :date_end  AND id_etat= :id_etat ORDER BY dossier  DESC");
	$req->execute(array(
		':date_start'		=>$_POST['date_start'],
		':date_end'		=>$_POST['date_end'],
		':id_etat'		=>$_POST['etat'],
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


if(isset($_POST['search_form']))
{
	$fAllActive=search($pdoLitige);
	$nbLitiges=count($fAllActive);
	$valoTotal=searchValo($pdoLitige);
	$valoEtat=searchGpType($pdoLitige);
}
elseif(isset($_POST['filter']))
{


	if(!empty($_POST['etat']))
	{
		$fAllActive=filter($pdoLitige);
		$valoTotal=getSumValoFilter($pdoLitige);
		$valoEtat=getSumValoFilterGpType($pdoLitige);

	}
	else
	{
		$fAllActive=filterJustDate($pdoLitige);
		$valoTotal=getSumValoJustDate($pdoLitige);
		$valoEtat=getSumValoJustDateGpType($pdoLitige);

	}
	$nbLitiges=count($fAllActive);
}

else
{
	$fAllActive=getAllDossier($pdoLitige);
	$nbLitiges=count($fAllActive);
	$valoTotal=getSumValoAll($pdoLitige);
	$valoEtat=getSumValoAllGpType($pdoLitige);
}
if(isset($_POST['date_start']))
{
	$start_month=$_POST['date_start'];
}
else
{
	$start_month='2019-01-01';
}
if(isset($_POST['date_end']))
{
	$thismonth=$_POST['date_end'];
}
else
{
	$thismonth=date('Y-m-d');
}



$listEtat=getEtat($pdoLitige);
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
	<div class="row mb-5">
		<div class="col border py-3">
			<div class="row">
				<div class="col-6">
					<p class="text-red heavy">Filtrer par date et/ou état :</p>
				</div>
			</div>

			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
				<div class="row">
					<div class="col-auto mt-2">
						<p class="text-red">Par date :</p>
					</div>
					<div class="col-auto">
						<div class="form-group">
							<input type="date" class="form-control" min="2019-01-01" value="<?=$start_month?>" name="date_start">
						</div>
					</div>
					<div class="col-auto mt-2">
						à
					</div>
					<div class="col-auto">
						<div class="form-group">
							<input type="date" class="form-control" min="2019-01-01" value="<?=$thismonth?>" name="date_end">
						</div>
					</div>
					<!-- <div class="col"></div> -->
					<!-- </div> -->
					<!-- <div class="row"> -->
						<div class="col-auto mt-2">
							<p class="text-red ">Et/ou état :</p>
						</div>
						<div class="col-2">
							<div class="form-group">
								<select name="etat" id="" class="form-control">
									<option value="">Sélectionner</option>
									<?php
									foreach ($listEtat as $etat)
									{
										$selected="";
										if(!empty($_POST['etat']))
										{
											if($etat['id']==$_POST['etat'])
											{
												$selected='selected';
											}
											else
											{
												$selected="";

											}
										}
										echo '<option value="'.$etat['id'].'" '.$selected.'>'.$etat['etat'].'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="col text-right">
							<div class="col ml-2">
								<button class="btn btn-black" type="submit" name="filter"> <i class="fas fa-filter pr-3"></i>Filtrer</button>
							</div>
						</div>
					</div>
				</form>
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
					<div class="row">
						<div class="col">
							<p class="text-red heavy">Rechercher un litige :</p>
						</div>
					</div>
					<div class="row">
						<div class="col-4">

							<div class="form-group" id="equipe">
								<input class="form-control mr-5 pr-5" placeholder="n°litige, date, magasin, galec" name="search_strg" id="" type="text"  value="<?=isset($search_strg)? $search_strg: false?>">
							</div>
						</div>
						<div class="col">
							<button class="btn btn-black mr-5" type="submit" id="" name="search_form"><i class="fas fa-search pr-2"></i>Rechercher</button>
						</div>
						<div class="col text-right">

							<button class="btn btn-red" type="submit" id="" name="clear_form"><i class="fas fa-eraser pr-2"></i>Effacer toutes les sélections</button>

						</div>
					</div>
				</form>


			</div>
		</div>
		<div class="row">
			<div class="col-lg-1 col-xxl-2"></div>

			<div class="col">
				<?php
				include('../view/_errors.php');
				?>
			</div>
			<div class="col-lg-1 col-xxl-2"></div>
		</div>
		<!-- ./formulaire de recherche-->
		<div class="row pb-2">
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
				<h4 class="text-main-blue text-center"> Listing des  <?=$nbLitiges?> litiges de votre sélection</h4>
			</div>
		</div>
	<div class="row">
		<div class="col">
				<p class="text-main-blue">Statistiques : </p>

		</div>
	</div>
		<div class="row ">
			<div class="col-6">
				<table class="table">
					<tbody>
						<tr>
							<td class="text-red">Valorisation Totale</td>
							<td class="text-right heavy bg-red"><?= number_format((float)$valoTotal['valo_totale'],2,'.',' ')?>&euro;</td>
							<td></td>
						</tr>
						<?php
						$col=1;
						$maxLig=ceil(count($valoEtat)/2);
						foreach ($valoEtat as $vEtat)
						{
							if(empty($vEtat['etat']))
							{
								$denoEtat='sans statut';
							}
							else
							{
								$denoEtat=$vEtat['etat'];
							}
							if($col<=$maxLig)
							{
								echo '<tr>';
								echo '<td>'.$denoEtat.'</td>';
								echo '<td class="text-right heavy">'.number_format((float)$vEtat['valo_etat'],2,'.',' ').'&euro;</td>';
								echo '<td class="text-right">'.$vEtat['nbEtat'].' dossiers</td>';
								echo '</tr>';
								$col++;
							}
							else
							{
								echo '</tbody>';
								echo '</table>';
								echo '</div>';
								echo '<div class="col-6">';
								echo '<table class="table">';
								echo '<tbody>';
								echo '<tr>';
								echo '<td>'.$denoEtat.'</td>';
								echo '<td class="text-right heavy">'.number_format((float)$vEtat['valo_etat'],2,'.',' ').'&euro;</td>';
								echo '<td class="text-right">'.$vEtat['nbEtat'].' dossiers</td>';
								echo '</tr>';
								$col=1;
							}
						}
						?>

					</tbody>
				</table>
			</div>
		</div>

		<!-- start row -->
		<div class="row">
			<div class="col">
				<table class="table border" id="dossier">
					<thead class="thead-dark ">
						<th class="sortable align-top">Dossier</th>
						<th class="sortable">Date déclaration</>
							<th class="sortable align-top">Magasin</th>
							<th class="sortable align-top">Code BT</th>
							<th class="sortable align-top">Centrale</th>
							<th class="sortable align-top">Etat</th>
							<th class="sortable align-top text-right">Valo</th>
							<th class="sortable text-center align-top">Ctrl Stock</th>

							<th class="sortable text-center align-top">24/48h</th>

						</tr>
					</thead>
					<tbody id="tosort">
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

							if($active['ctrl_ok']==0){
								$ctrl='';
							}
							elseif($active['ctrl_ok']==1){
								$ctrl= '<i class="fas fa-boxes text-green"></i>';
							}
							elseif($active['ctrl_ok']==2)
							{
								$ctrl='<i class="fas fa-hourglass-end text-red"></i>';
							}

							echo '<tr class="'.$active['etat_dossier'].'">';
							echo'<td><a href="bt-detail-litige.php?id='.$active['id_main'].'">'.$active['dossier'].'</a></td>';
							echo'<td>'.$active['datecrea'].'</td>';
							echo'<td><a href="stat-litige-mag.php?galec='.$active['galec'].'">'.$active['mag'].'</a></td>';
							echo'<td>'.$active['btlec'].'</td>';
							echo'<td>'.$active['centrale'].'</td>';
							echo'<td class="'.$etat.'">'.$active['etat'].'</td>';
							echo'<td class="text-right">'.number_format((float)$active['valo'],2,'.',' ').'&euro;</td>';
							echo '<td class="text-center">'.$ctrl .'</td>';
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
	<script src="../js/sorttable2.js"></script>

	<?php

	require '../view/_footer-bt.php';

	?>