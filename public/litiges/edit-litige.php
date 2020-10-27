<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	echo "pas de variable session";

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
require '../../Class/LitigeDao.php';
require '../../Class/LitigeHelpers.php';
require '../../Class/FormHelpers.php';


//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];



$litigeDao=new LitigeDao($pdoLitige);

$detailLitige=$litigeDao ->getDetail($_GET['id']);
$listReclamations=LitigeHelpers::listReclamationIncludingMasked($pdoLitige);

if(isset($_POST['update_detail'])){
	$key=array_keys($_POST['update_detail']);

	if($litigeDao->saveDetailInModif($_POST['id_detail'][$key[0]])){
		$errors[]= "impossible de recopier les données initiales";
	}
	if($litigeDao->updateDetail($_POST['id_detail'][$key[0]], $_POST['qte_cde'][$key[0]], $_POST['tarif'][$key[0]], $_POST['id_reclamation'][$key[0]], $_POST['qte_litige'][$key[0]], $_POST['valo_line'][$key[0]])){
		$errors[]= "impossible de modifier l'article";
	}
	$idTableModif=$litigeDao->saveDetailInModif($_POST['id_detail'][$key[0]], true);

	if($idTableModif>0){
		if($litigeDao->updateModif( $idTableModif)){
			$successQ='?id='.$_GET['id'];
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
		}else{
			$errors[]="une erreur est survenue";
		}
	}else{
		$errors[]= "impossible de recopier les données initiales";

	}




}


if(isset($_POST['delete_detail'])){
	$key=array_keys($_POST['delete_detail']);
	$idDetailToDelete=$_POST['id_detail'][$key[0]];

}


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


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
	<h1 class="text-main-blue py-5 ">Modification du  litige  <?=$detailLitige[0]['dossier']?></h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<!-- valo totale -->
	<div class="row">
		<div class="col">
			<h5>Modification de la valo totale du dossier :</h5>
		</div>
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">

				<div class="row">
					<div class="col">
						<div class="form-group">
							<input type="text" class="form-control" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Exemple 1.1" name="valo_totale" id="valo_totale" value="<?=$detailLitige[0]['valo']?>">
						</div>
					</div>
					<div class="col">
						<button class="btn btn-black" name="update_valo">Modifier</button>
					</div>
				</div>
			</form>

		</div>
	</div>
	<div class="row">
		<div class="col">
			<h5>Modification des détails articles du dossier :</h5>
		</div>
	</div>

	<div class="row">
		<div class="col">


		</div>
	</div>
	<?php if ($detailLitige[0]['id_reclamation']!=7): ?>
		<table class="table">
			<thead class="thead-dark">
				<tr>
					<th>Article</th>
					<th>Dossier</th>
					<th>Qte cde</th>
					<th>Tarif</th>
					<th>Qte litige</th>
					<th>Valo</th>
					<th>Réclamation</th>
					<th>Modifier</th>
					<th>Supprimer</th>
				</tr>
			</thead>
			<tbody>
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">
					<?php foreach ($detailLitige as $keydetail => $detail): ?>
						<?php if (empty($detail['inv_article'])): ?>
							<tr>
								<td><?=$detail['article']?></td>
								<td><?=$detail['dossier']?></td>
								<td>
									<div class="form-group">
										<input type="text" class="form-control mini-input" pattern="[0-9]+" title="Quantité non valide" name="qte_cde[]" id="qte_cde" value="<?=$detail['qte_cde']?>">
									</div>


								</td>
								<td>
									<div class="form-group">
										<input type="text" class="form-control moyen-input" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Exemple 1.1" name="tarif[]" id="tarif" value="<?=$detail['tarif']?>">
									</div>
								</td>
								<td>
									<div class="form-group">
										<input type="text" class="form-control mini-input" pattern="[0-9]+" title="Quantité non valide"  name="qte_litige[]" id="qte_litige" value=<?=$detail['qte_litige']?>>
									</div>


								</td>
								<td>
									<div class="form-group">
										<input type="text" class="form-control moyen-input" name="valo_line[]" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Exemple 1.1" id="valo_line" value="<?=$detail['valo_line']?>">
									</div>


								</td>
								<td>
									<div class="form-group">
										<div class="form-group">
											<select class="form-control" name="id_reclamation[]" id="id_reclamation">
												<?php foreach ($listReclamations as $key => $reclam): ?>
													<option value="<?=$key?>" <?=FormHelpers::restoreSelected($key,$detail['id_reclamation'])?>>
														<?=$listReclamations[$key]?>

													</option>

												<?php endforeach ?>
											</select>
										</div>

									</div>
								</td>
								<td>
									<input type="hidden" class="form-control" name="id_detail[]" id="id_detail" value="<?=$detail['id_detail']?>">
									<button class="btn btn-primary" type="submit" name="update_detail[<?=$keydetail?>]" >Modifier <?=$keydetail?></button>
								</td>
								<td>
									<button class="btn btn-red" type="submit" name="delete_detail[<?=$keydetail?>]">Supprimer</button>
								</td>
							</tr>
							<?php else: ?>
								<tr>
									<td colspan="9">Article commandé :</td>
								</tr>
								<tr>
									<td><?=$detail['article']?></td>
									<td><?=$detail['dossier']?></td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control mini-input" pattern="[0-9]+" title="Quantité non valide" name="qte_cde[]" id="qte_cde" value="<?=$detail['qte_cde']?>">
										</div>


									</td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control moyen-input" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Exemple 1.1" name="tarif[]" id="tarif" value="<?=$detail['tarif']?>">
										</div>
									</td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control mini-input" pattern="[0-9]+" title="Quantité non valide"  name="qte_litige[]" id="qte_litige" value=<?=$detail['qte_litige']?>>
										</div>
									</td>
									<td></td>
									<td>
										<div class="form-group">
											<div class="form-group">
												<select class="form-control" name="id_reclamation[]" id="id_reclamation">
													<?php foreach ($listReclamations as $key => $reclam): ?>
														<option value="<?=$key?>" <?=FormHelpers::restoreSelected($key,$detail['id_reclamation'])?>>
															<?=$listReclamations[$key]?>

														</option>

													<?php endforeach ?>
												</select>
											</div>

										</div>
									</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td colspan="9">Article reçu :</td>
								</tr>
								<tr>
									<td><?=$detail['inv_article']?></td>
									<td></td>
									<td></td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control moyen-input" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Exemple 1.1" name="inv_tarif[]" id="inv_tarif" value="<?=$detail['inv_tarif']?>">
										</div>

									</td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control mini-input" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Exemple 1.1" name="inv_qte[]" id="tarif" value="<?=$detail['inv_qte']?>">
										</div>
									</td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control moyen-input" name="valo_line[]" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Exemple 1.1" id="valo_line" value="<?=$detail['valo_line']?>">
										</div>

									</td>
									<td></td>
									<td>
										<input type="hidden" class="form-control" name="id_detail[]" id="id_detail" value="<?=$detail['id_detail']?>">
										<button class="btn btn-primary" type="submit" name="update_detail[<?=$keydetail?>]" >Modifier <?=$keydetail?></button>
									</td>
									<td>
										<button class="btn btn-red" type="submit" name="delete_detail[<?=$keydetail?>]">Supprimer</button>
									</td>
								</tr>
							<?php endif ?>

						<?php endforeach ?>
					</form>
				</tbody>
			</table>
			<?php else: ?>
				Inversion de palette, la fonctionnalité de modification sur une inversion de la palette n'a pas été développée

			<?php endif ?>

			<!-- ./container -->
		</div>

		<?php
		require '../view/_footer-bt.php';
		?>