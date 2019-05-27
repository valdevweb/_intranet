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

function getEtat($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT etat,id FROM etat WHERE mask=0 ORDER BY etat");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function globalSearch($pdoLitige)
{
	$strg=empty($_SESSION['form-data']['search_strg']) ? '': $_SESSION['form-data']['search_strg'];
	if(!empty($_SESSION['form-data']['etat']))
	{
		$reqEtat= ' AND id_etat= ' .$_SESSION['form-data']['etat'];
	}
	else
	{
		$reqEtat='';
	}
	if(!empty($_SESSION['pending'])){
		if($_SESSION['pending']=='pending'){
			$reqCommission= ' AND commission !=1';
		}
		else{
			$reqCommission= ' AND commission =' .intval($_SESSION['pending']);

		}
	}
	else{
		$reqCommission='';
	}

	$req=$pdoLitige->prepare("SELECT dossiers.id as id_main,dossiers.dossier,date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,user_crea,dossiers.galec,etat_dossier, mag, centrale, sca3.btlec,vingtquatre, valo, etat,ctrl_ok,commission
		FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		WHERE
		date_crea BETWEEN :date_start AND :date_end
		AND concat(dossiers.dossier,mag,dossiers.galec,sca3.btlec) LIKE :search
		$reqEtat
		$reqCommission
		");
// return  $req;

	$req->execute(array(
		':search' =>'%'.$strg.'%',
		':date_start'=>$_SESSION['form-data']['date_start'],
		':date_end'	=>$_SESSION['form-data']['date_end'],

	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}





function getSumValo($pdoLitige)
{
	// $strg=isset($_POST['search_strg']) ? $_POST['search_strg'] :'';
	$strg=empty($_SESSION['form-data']['search_strg']) ? '': $_SESSION['form-data']['search_strg'];

	if(!empty($_SESSION['form-data']['etat']))
	{
		$reqEtat= ' AND id_etat= ' .$_SESSION['form-data']['etat'];
	}
	else
	{
		$reqEtat='';
	}
	if(!empty($_SESSION['pending'])){
		if($_SESSION['pending']=='pending'){
			$reqCommission= ' AND commission !=1';
		}
		else{
			$reqCommission= ' AND commission =' .intval($_SESSION['pending']);

		}
	}
	else{
		$reqCommission='';
	}
	$req=$pdoLitige->prepare("SELECT  sum(valo) as valo_totale
		FROM dossiers
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		WHERE date_crea BETWEEN :date_start AND :date_end AND concat(dossiers.dossier,mag,dossiers.galec,sca3.btlec) LIKE :search $reqEtat $reqCommission ");


	$req->execute(array(
		':search' =>'%'.$strg.'%',
		':date_start'		=>$_SESSION['form-data']['date_start'],
		':date_end'		=>$_SESSION['form-data']['date_end'],
	));
	return $req->fetch(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}

function getSumValoByType($pdoLitige)
{
	$strg=empty($_SESSION['form-data']['search_strg']) ? '': $_SESSION['form-data']['search_strg'];

	if(!empty($_SESSION['form-data']['etat']))
	{
		$reqEtat= ' AND id_etat= ' .$_SESSION['form-data']['etat'];
	}
	else
	{
		$reqEtat='';
	}
	if(!empty($_SESSION['pending'])){
		if($_SESSION['pending']=='pending'){
			$reqCommission= ' AND commission !=1';
		}
		else{
			$reqCommission= ' AND commission =' .intval($_SESSION['pending']);

		}
	}
	else{
		$reqCommission='';
	}
	$req=$pdoLitige->prepare("SELECT  sum(valo) as valo_etat, dossiers.id_etat, etat.etat, count(dossiers.id) as nbEtat FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec

		WHERE date_crea BETWEEN :date_start AND :date_end AND concat(dossiers.dossier,mag,dossiers.galec,sca3.btlec) LIKE :search $reqEtat $reqCommission GROUP BY etat");
	$req->execute(array(
		':search' =>'%'.$strg.'%',
		':date_start'		=>$_SESSION['form-data']['date_start'],
		':date_end'		=>$_SESSION['form-data']['date_end'],
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// return $req->errorInfo();

}


function updateCommission($pdoLitige,$etat)
{
	$req=$pdoLitige->prepare("UPDATE dossiers SET commission = :commission, date_commission= :date_commission WHERE id= :id");
	$req->execute([
		':commission'	=>$etat,
		':date_commission'	=>date('Y-m-d H:i:s'),
		':id'		=>$_POST['iddossier']

	]);
	return $req->rowCount($pdoLitige);
}

function addAction($pdoLitige, $idContrainte){
	$req=$pdoLitige->prepare("INSERT INTO action (id_dossier, libelle, id_contrainte, id_web_user, date_action) VALUES (:id_dossier, :libelle, :id_contrainte, :id_web_user, :date_action)");
	$req->execute([
		':id_dossier'		=>$_POST['iddossier'],
		':libelle'			=>$_POST['cmt'],
		':id_contrainte'	=>$idContrainte,
		':id_web_user'		=>$_SESSION['id_web_user'],
		':date_action'		=>date('Y-m-d H:i:s'),
	]);
	return $req->rowCount();
}

if(isset($_GET['notallowed'])){
	$errors[]="Vous n'êtes pas autorisé à modifier le statut 'validé en commission'";
}

// initialisation
if(empty($_SESSION['form-data']['date_start'])){
	// $_POST['date_start']='2019-01-01';
	$_SESSION['form-data']['date_start']='2019-01-01';
}

if(empty($_SESSION['form-data']['date_end'])){
	// $_POST['date_end']=date('Y-m-d');
	$_SESSION['form-data']['date_end']=date('Y-m-d');

}

if(isset($_POST['search_form'])){
	foreach ($_POST as $key => $value) {
		if($key != 'reset-pending' || $key != 'clear_form' || $key != 'pending'){
			if($value !=''){
			$_SESSION['form-data'][$key]=$value;

			}
		}
	}
}


if(isset($_POST['clear_form']))
{
	unset($_SESSION['form-data']);
	$_SESSION['form-data']['date_start']=$_POST['date_start']='2019-01-01';
	$_SESSION['form-data']['date_end']=$_POST['date_end']=date('Y-m-d');

}

if(isset($_POST['pending'])){
	if($_POST['pending']==0){
		$_SESSION['pending']='pending';

	}
	else
	{

		$_SESSION['pending']=$_POST['pending'];

	}
}


if(isset($_POST['reset-pending'])){
	unset($_POST['pending']);
	unset($_SESSION['pending']);
}


$fAllActive=globalSearch($pdoLitige);
$nbLitiges=count($fAllActive);
$valoTotal=getSumValo($pdoLitige);
$valoEtat=getSumValoByType($pdoLitige);

if(isset($_POST['validate'])){
	if(!empty($_POST['cmt']))
	{

		$action=addAction($pdoLitige, 3);
		if($action==1){
			$result=updateCommission($pdoLitige,1);
		}
		else{
			$errors[]="impossible d'ajouter le commentaire";
		}
		if($result==1)
		{
			header('Location:bt-litige-encours.php#'.$_POST['iddossier']);

		}
		else{
			$errors[]="impossible de mettre le statut à jour";
		}
	}
	else{
		$errors[]="Veuillez saisir un commentaire";
	}
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
	<div class="row pt-5 mb-5">
		<div class="col">
			<h1 class="text-main-blue">Listing des dossiers litiges </h1>

		</div>
		<div class="col border p-3">
			<div class="row">
				<div class="col-auto"><i class="fas fa-user-check stamp pending"></i></div>
				<div class="col"> non statué</div>
				<div class="col-auto"><i class="fas fa-hourglass-end text-red"></i></div>
				<div class="col">en attente de contrôle</div>
			</div>
			<div class="row">
				<div class="col-auto"><i class="fas fa-user-check stamp validated"></i></div>
				<div class="col">statué</div>
				<div class="col-auto"><i class="fas fa-boxes text-green"></i></div>
				<div class="col">contrôlé</div>
			</div>

		</div>
	</div>



	<!-- formulaire de recherche -->
	<div class="row mb-5">
		<div class="col border py-3">
			<div class="row">
				<div class="col-6">
					<p class="text-red heavy">Critères de recherche :</p>
				</div>
			</div>

			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
				<div class="row">
					<div class="col-auto mt-2">
						<p class="text-red">Date de début :</p>
					</div>
					<div class="col-auto">
						<div class="form-group">
							<input type="date" class="form-control" min="2019-01-01" value="<?= isset($_SESSION['form-data']['date_start']) ? $_SESSION['form-data']['date_start'] :'' ?>" name="date_start">
						</div>

					</div>
					<div class="col-auto mt-2">
						<p class="text-red">Date de fin :</p>
					</div>
					<div class="col-auto">
						<div class="form-group">
							<input type="date" class="form-control" min="2019-01-01" value="<?=isset($_SESSION['form-data']['date_end']) ? $_SESSION['form-data']['date_end'] :'' ?>" name="date_end">
						</div>
					</div>
					<!-- <div class="col"></div> -->
					<!-- </div> -->
					<!-- <div class="row"> -->
						<div class="col-auto mt-2">
							<p class="text-red ">Etat :</p>
						</div>
						<div class="col-2">
							<div class="form-group">
								<select name="etat" id="" class="form-control">
									<option value="">Sélectionner</option>
									<?php
									foreach ($listEtat as $etat)
									{
										$selected="";
										if(!empty($_SESSION['form-data']['etat']))
										{
											if($etat['id']==$_SESSION['form-data']['etat'])
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
					</div>
					<div class="row">
						<div class="col">
							<p class="text-red">Préciser un numéro de litige, un magasin par le nom ou le code galec :</p>
						</div>
					</div>
					<div class="row">
						<div class="col-4">

							<div class="form-group" id="equipe">
								<input class="form-control mr-5 pr-5" placeholder="n°litige,  magasin, galec" name="search_strg" id="" type="text"  value="<?=isset($_SESSION['form-data']['search_strg'])? $_SESSION['form-data']['search_strg'] : false?>">
							</div>
						</div>

						<div class="col text-right">
							<button class="btn btn-black mr-5" type="submit" id="" name="search_form"><i class="fas fa-search pr-2"></i>Rechercher</button>
						</div>
						<div class="col-auto text-right">

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
		<div class="row mt-3">
			<div class="col">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
					<div class="row">
						<div class="col-auto">
							<p class="text-red heavy">Filtrer par statut :</p>
						</div>

						<div class="col">
							<button class="stamp pending" type="submit" name="pending" value="0"><i class="fas fa-user-check"></i></button>
							<button class="stamp validated" type="submit" name="pending" value="1"><i class="fas fa-user-check"></i></button>
							<button class="stamp reset-pending" type="submit" name="reset-pending" ><i class="fas fa-user-check"></i></button>
						</div>
						<div class="col"></div>
					</div>
				</form>
			</div>
		</div>
		<!-- start row -->
		<div class="row">
			<div class="col">
				<table class="table border" id="dossier">
					<thead class="thead-dark smaller">
						<th class="sortable align-top">Dossier</th>
						<th class="sortable smaller">Date déclaration</>
							<th class="sortable align-top">Magasin</th>
							<th class="sortable align-top">Code BT</th>
							<th class="sortable align-top">Centrale</th>
							<th class="sortable align-top">Etat</th>
							<th class="sortable align-top text-right">Valo</th>
							<th class="sortable text-center align-top">Ctrl Stock</th>
							<th class="sortable text-center align-top">Statué</th>
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


							if($active['commission']==0)
							{
								$class='pending';

							}
							else{
								$class='validated';
							}
	// <div class="row">
	// 	<div class="col">
	// 		<div class="text-center"><i class="fas fa-user-check stamp pending"></i></div><br>
	// 		<div class="text-center"><i class="fas fa-user-check circle-icon validated"></i></div>

	// 	</div>
	// </div>



							echo '<tr class="'.$active['etat_dossier'].'" id="'.$active['id_main'].'">';
							echo'<td><a href="bt-detail-litige.php?id='.$active['id_main'].'">'.$active['dossier'].'</a></td>';
							echo'<td>'.$active['datecrea'].'</td>';
							echo'<td><a href="stat-litige-mag.php?galec='.$active['galec'].'">'.$active['mag'].'</a></td>';
							echo'<td>'.$active['btlec'].'</td>';
							echo'<td>'.$active['centrale'].'</td>';
							echo'<td class="'.$etat.'">'.$active['etat'].'</td>';
							echo'<td class="text-right">'.number_format((float)$active['valo'],2,'.',' ').'&euro;</td>';
							echo '<td class="text-center">'.$ctrl .'</td>';
							// echo '<td class="text-center"><a href="commission-traitement.php?id='.$active['id_main'].'&etat='.$class.'" class="stamps"><i class="fas fa-user-check stamp '.$class.'"></i></a></td>';
							if($class=='validated'){

								echo '<td class="text-center"><a href="commission-traitement.php?id='.$active['id_main'].'&etat='.$class.'" ><i class="fas fa-user-check stamp '.$class.'"></i></a></td>';
							}
							else{
								echo '<td class="text-center"><a href="#modal1" data="'.$active['id_main'].'" class="stamps"><i class="fas fa-user-check stamp '.$class.'"></i></a></td>';

							}

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
		<aside id="modal1" class="vm-modal" aria-hidden="true" role="modal"  style="display: none;">
			<div class="vm-modal-wrapper">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
					<div class="form-group">
						<label class="text-main-blue">Commentaire :</label>
						<textarea class="form-control" name="cmt" rows="3" id="cmtarea"></textarea>
					</div>
					<div class="form-group">

						<input type="hidden" class="form-control" name="iddossier" id="hiddeninput">
					</div>
					<button class="btn btn-primary" name="validate">Valider</button>
					<button class="btn btn-red" id="annuler">Annuler</button>
				</form>
			</div>
		</aside>





	</div>
	<script src="../js/sorttable2.js"></script>

	<script type="text/javascript">

		$(document).ready(function(){
			var url = window.location + '';
			var splited=url.split("#");
			if(splited[1]==undefined)
			{
				var line='';
			}
			else if(splited.length==2)
			{
				var line=splited[1];
				$("tr#"+line).addClass("anim");
			}

			$('.stamps').on('click',function(){
				var line=$(this).attr("data")
				console.log(line);
				$('#hiddeninput').val(line);
				$('#modal1').css("display","null");
				$('#modal1').removeAttr('aria-hidden');
				// $('#modal1').attr('aria-modal', true);
				$('#cmtarea').focus();
			// $("tr#"+line).addClass("anim");
		});
			$('#annuler').on('click', function(){

				$('#modal1').css("display","hidden");

			});



		});



	</script>

	<?php

	require '../view/_footer-bt.php';

	?>