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
require '../../Class/MagHelpers.php';


//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
function searchDbArt($pdoQlik){

	$req=$pdoQlik->prepare("SELECT * FROM basearticles WHERE `GESSICA.Gencod` LIKE :ean");
	$req->execute([
		':ean'		=>'%'.$_POST['ean'].'%'
	]);
	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($data)){
		return false;
	}
	return $data;
}

function searchDbArticleByEanOrArticle($pdoQlik){
	$req=$pdoQlik->prepare("SELECT `GESSICA.CodeArticle` as article, `GESSICA.CodeDossier` as dossier, `GESSICA.PANF` as panf, `GESSICA.PCB` as pcb, `GESSICA.LibelleArticle` as libelle, `GESSICA.CodeFournisseur` as cnuf, `GESSICA.NomFournisseur` as fournisseur, `GESSICA.Gencod` as gencod FROM basearticles WHERE `GESSICA.Gencod` LIKE :ean OR `GESSICA.CodeArticle` LIKE :ean");
	$req->execute([
		':ean'		=>'%'.$_POST['article'].'%'
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function searchDbLitigeByEanOrArticle($pdoQlik, $btlec){
	$req=$pdoQlik->prepare("SELECT * FROM statsventeslitiges WHERE (gencod LIKE :ean OR  article LIKE :ean) AND btlec= :btlec");
	$req->execute([
		':ean'		=>'%'.$_POST['article'].'%',
		':btlec'		=>$btlec
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function addArticle($pdoLitige, $key, $palette, $facture, $dateFacture, $qteCde,$valo ){
	$req=$pdoLitige->prepare("INSERT INTO details (id_dossier, dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif,  fournisseur, cnuf, qte_litige, id_reclamation, valo_line) VALUES (:id_dossier, :dossier, :palette, :facture, :date_facture, :article, :ean, :dossier_gessica, :descr, :qte_cde, :tarif, :fournisseur, :cnuf, :qte_litige, :id_reclamation, :valo_line)");
	$req->execute([
		':id_dossier'		=>$_POST['id_litige'][$key],
		':dossier'		=>$_POST['dossier_litige'][$key],
		':palette'		=>$palette,
		':facture'		=>$facture,
		':date_facture'		=>$dateFacture,
		':article'		=>$_POST['article'][$key],
		':ean'		=>$_POST['ean'][$key],
		':dossier_gessica'		=>$_POST['dossier'][$key],
		':descr'		=>$_POST['descr'][$key],
		':qte_cde'		=>$qteCde,
		':tarif'		=>$_POST['tarif'][$key],
		':fournisseur'		=>$_POST['fournisseur'][$key],
		':cnuf'		=>$_POST['cnuf'][$key],
		':qte_litige'		=>$_POST['qte_litige'][$key],
		':id_reclamation'		=>$_POST['id_reclamation'][$key],
		':valo_line'		=>$valo,
	]);
	return $req->rowCount();
}

$errors=[];
$success=[];



$litigeDao=new LitigeDao($pdoLitige);

$detailLitige=$litigeDao ->getDetail($_GET['id']);
$listReclamationsIncludingMasked=LitigeHelpers::listReclamationIncludingMasked($pdoLitige);
$listReclamations=LitigeHelpers::listReclamation($pdoLitige);





if(isset($_POST['update_detail'])){
	// on récupère l'index du bouton submit qui a été  cliqué
	$key=implode(array_keys($_POST['update_detail']));

	if($litigeDao->saveDetailInModif($_POST['id_detail'][$key])){
		$errors[]= "impossible de recopier les données initiales";
	}
	//si on etait sur une inversion de référence (inv_ref existe), on a 2 cas :
	//on reste en inversion de réf après la modification,
	//on décide ne ne plus être en inversion de référence après la modification
	//et dans ce dernier cas, on remet les champs inv à 0.
	//si on n'était pas sur une inversion de réfénrece, on initialise aussi les champs inv à 0
	if(isset($_POST['inv_ref'][$key]) && $_POST['id_reclamation'][$key]==5){
		$inversion=$_POST['inversion'][$key];
		$invArticle=$_POST['inv_article'][$key];
		$invQte=$_POST['inv_qte'][$key];
		$invTarif=$_POST['inv_tarif'][$key];

	}else{
		$inversion=$invArticle=$invQte=$invTarif=null;

	}

	if($litigeDao->updateDetailInvRef($_POST['id_detail'][$key], $_POST['qte_cde'][$key], $_POST['tarif'][$key], $_POST['id_reclamation'][$key], $_POST['qte_litige'][$key], $inversion, $invArticle, $invQte, $invTarif, $_POST['valo_line'][$key])){
		$errors[]= "impossible de modifier l'article";
	}
	$idTableModif=$litigeDao->saveDetailInModif($_POST['id_detail'][$key], true);

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

if(isset($_POST['search'])){
	$found=searchDbArt($pdoQlik);
	if(!$found){
		$result="EAN non trouvé";
	}
}

if(isset($_POST['delete_detail'])){
	$key=implode(array_keys($_POST['delete_detail']));
	$idDetail=$_POST['id_detail'][$key];
	$litigeDao->deleteDetail($idDetail);
	// echo $idDetail;
	$successQ='?id='.$_GET['id'];
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}

if (isset($_POST['search_2'])) {
	if($_POST['cde_mag']==1){
		$btlec=MagHelpers::btlec($pdoMag, $detailLitige[0]['galec']);
		$listArticles=searchDbLitigeByEanOrArticle($pdoQlik, $btlec);
	}elseif($_POST['cde_mag']==2){
		$listArticles=searchDbArticleByEanOrArticle($pdoQlik);
	}

}

if(isset($_POST['add_article'])){
	// on récupère l'index du bouton submit qui a été  cliqué
	$key=implode(array_keys($_POST['add_article']));
	if(empty($_POST['qte_litige'][$key]) || empty($_POST['id_reclamation'][$key])){
		$errors[]="Merci de renseigner la quantité et le type de réclamation";
	}
	if(empty($errors)){
		if(empty($_POST['qte_cde'][$key])){
			$qteCde=1;
			echo $qteCde;
			$valo=$_POST['tarif'][$key]*$_POST['qte_litige'][$key];

		}else{
			$qteCde=$_POST['qte_cde'][$key];
			$valo=$_POST['tarif'][$key]/$_POST['qte_cde'][$key]*$_POST['qte_litige'][$key];
		}

		$facture=isset($_POST['facture'][$key])?$_POST['facture'][$key]:null;
		$dateFacture=isset($_POST['date_facture'][$key])?$_POST['date_facture'][$key]:null;
		$palette=isset($_POST['palette'][$key])?$_POST['palette'][$key]:null;
		$done=addArticle($pdoLitige, $key, $palette, $facture, $dateFacture, $qteCde, $valo);

		if($done==1){
			$successQ='?id='.$_GET['id'].'&success=add';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
		}

	}

}

if(isset($_GET['success'])){
	$arrSuccess=[
		'add'=>'Article ajouté avec succès',
	];
	$success[]=$arrSuccess[$_GET['success']];
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

	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue ">Modification du  litige  <?=$detailLitige[0]['dossier']?></h1>
		</div>
		<div class="col-auto">
			<a href="bt-detail-litige.php?id=<?=$_GET['id']?>" class="btn btn-primary">Retour</a>
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

	<!-- valo totale -->
	<div class="row">
		<div class="col">
			<h5 class="text-main-blue">Modification de la valo totale du dossier :</h5>
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
			<h5 class="text-main-blue">Modification des détails articles du dossier :</h5>
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
						<?php if (empty($detail['inversion'])): ?>
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
										<select class="form-control" name="id_reclamation[]" id="id_reclamation">
											<?php foreach ($listReclamations as $key => $reclam): ?>
												<option value="" >NC</option>
												<option value="<?=$key?>" <?=FormHelpers::restoreSelected($key,$detail['id_reclamation'])?>>
													<?=$listReclamations[$key]?>
												</option>
											<?php endforeach ?>
										</select>
									</div>

								</td>
								<td>
									<input type="hidden" class="form-control" name="id_detail[]" id="id_detail" value="<?=$detail['id_detail']?>">
									<button class="btn btn-primary" type="submit" name="update_detail[<?=$keydetail?>]" >Modifier</button>
								</td>
								<td>
									<button class="btn btn-red" type="submit" name="delete_detail[<?=$keydetail?>]">Supprimer</button>
								</td>
							</tr>
							<?php else: ?>
								<tr>
									<td class="inv" colspan="9">Article commandé :</td>
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
													<?php foreach ($listReclamationsIncludingMasked as $key => $reclam): ?>
														<option value="<?=$key?>" <?=FormHelpers::restoreSelected($key,$detail['id_reclamation'])?>>
															<?=$listReclamationsIncludingMasked[$key]?>

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
									<td class="inv" colspan="9">Article reçu :</td>
								</tr>
								<?php if ($detail['inv_article']==""): ?>
									<tr>
										<td  colspan="9">
											L'EAN saisi par le magasin n'a pas permis de trouver d'article correspondant. Vous pouvez le modifer ci dessous et effectuer à nouveau une recherche dans la base
										</td>
									</tr>
									<tr class="no-bottom-border">
										<td colspan="4">
											<div class="form-group">

												<input type="text" class="form-control" name="ean"  value="<?=$detail['inversion']?>" id="ean" >
											</div>
										</td>
										<td colspan="5">
											<button class="btn btn-primary" name="search">Rechercher l'article</button>
										</td>
									</tr>

									<?php if (isset($found) && $found!=false): ?>
										<tr>
											<td class="inv" colspan="9">
												Articles trouvés dans la base :<br>

											</td>
										</tr>
										<tr  class="no-bottom-border">
											<td></td>
											<td colspan="7">
												<div class="text-center">Pour mettre à jour le litige avec un des articles listé, veuillez cliquer sur le bouton <i class="fas fa-check-circle"></i></div>
												<table class="table table-sm">
													<thead class="thead-dark">
														<tr>
															<th>EAN</th>
															<th>Article</th>
															<th>Dossier</th>
															<th>Libellé</th>
															<th>PANF</th>
															<th>PCB</th>
															<th><i class="fas fa-check-circle"></i></th>

														</tr>
													</thead>
													<tbody>

														<?php foreach ($found as $key => $f): ?>
															<tr>
																<td><?=$f['GESSICA.Gencod']?></td>
																<td><?=$f['GESSICA.CodeArticle']?></td>
																<td><?=$f['GESSICA.CodeDossier']?></td>
																<td><?=$f['GESSICA.LibelleArticle']?></td>
																<td><?=$f['GESSICA.PANF']?></td>
																<td><?=$f['GESSICA.PCB']?></td>
																<td><a href="edit-litige-inv-inc.php?id_dossier=<?=$_GET['id']?>&id_detail=<?=$detail['id_detail']?>&id_inv=<?=$f['id']?>&inv_qte=<?=$detail['inv_qte']?>"><i class="fas fa-check-circle"></i></a></td>
															</tr>
														<?php endforeach ?>

													</tbody>
												</table>
											</td>
											<td></td>

										</tr>
										<?php elseif(isset($found) && $found==false): ?>
											<div class="alert alert-warning">Aucun article trouvé avec ce Gencod</div>
										<?php endif ?>

										<?php else: ?>

											<tr>
												<td>
													<?=$detail['inv_article']?>
													<input type="hidden" class="form-control" name="inv_article[]" id="inv_article" value="<?=$detail['inv_article']?>">
													<input type="hidden" class="form-control" name="inversion[]" id="inversion" value="<?=$detail['inversion']?>">

												</td>
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
													<input type="hidden" class="form-control" name="inv_ref[]" id="inv_ref" value="<?=$detail['id_detail']?>">
													<input type="hidden" class="form-control" name="id_detail[]" id="id_detail" value="<?=$detail['id_detail']?>">
													<button class="btn btn-primary" type="submit" name="update_detail[<?=$keydetail?>]" >Modifier <?=$keydetail?></button>
												</td>
												<td>
													<button class="btn btn-red" type="submit" name="delete_detail[<?=$keydetail?>]">Supprimer</button>
												</td>
											</tr>
										<?php endif?>

									<?php endif ?>

								<?php endforeach ?>
							</form>
						</tbody>
					</table>
					<?php else: ?>
						Inversion de palette, la fonctionnalité de modification sur une inversion de la palette n'a pas été développée

					<?php endif ?>
					<div class="row mt-5">
						<div class="col">
							<h5 class="text-main-blue">Ajout d'articles à la déclaration :</h5>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label for="article">Rechercher un article </label>
											<input type="text" class="form-control" placeholder="EAN ou code article" name="article" id="article">
										</div>
									</div>
									<div class="col-auto">
										Article présent sur une commande du magasin ?<br>
										<div class="form-check-inline mt-3">
											<input class="form-check-input" type="radio" value="1" id="cde_mag1" name="cde_mag">
											<label class="form-check-label" for="cde_mag1">oui</label>
										</div>
										<div class="form-check-inline">
											<input class="form-check-input" type="radio" value="2" id="cde_mag2" name="cde_mag">
											<label class="form-check-label" for="cde_mag2">non</label>
										</div>
									</div>
									<div class="col mt-4 pt-2">
										<button type="submit" name="search_2" class="btn btn-primary">Rechercher</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<?php if (isset($listArticles)): ?>
						<?php if (empty($listArticles)): ?>
							<div class="alert alert-danger">Aucun article n'a été trouvé</div>
							<?php else: ?>
								<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">

									<div class="row">
										<div class="col">
											Articles trouvés dans la base :<br>
											<div class="alert alert-primary">Mettre à jour le litige avec un des articles listé, veuillez cliquer sur le bouton <i class="fas fa-check-circle"></i></div>
											<table class="table">
												<thead class="thead-dark">
													<tr>
														<th>EAN</th>
														<th>Article</th>
														<th>Dossier</th>
														<th>Libellé</th>
														<th>Tarif</th>

														<th>Qte cde</th>
														<th>Qte litige</th>
														<th>Reclamation</th>
														<th>Action</th>

													</tr>
												</thead>
												<tbody>

													<?php foreach ($listArticles as $keyArticles => $article): ?>
														<tr>
															<td>
																<?=$article['gencod']?>
																<input type="hidden" class="form-control" name="ean[]" id="ean" value="<?=$article['gencod']?>">
															</td>
															<td>
																<?=$article['article']?>
																<input type="hidden" class="form-control" name="article[]" id="article" value="<?=$article['article']?>">
															</td>
															<td>
																<?=$article['dossier']?>
																<input type="hidden" class="form-control" name="dossier[]" id="dossier" value="<?=$article['dossier']?>">
															</td>
															<td>
																<?=$article['libelle']?>
																<input type="hidden" class="form-control" name="descr[]" id="descr" value="<?=$article['libelle']?>">
															</td>
															<td>
																<?php $tarif=(isset($article['panf']))? $article['panf']* $article['pcb'] :$article['tarif']?>
																<?=$tarif?>

																<input type="hidden" class="form-control" name="tarif[]" id="tarif" value="<?=$tarif?>">
															</td>
															<td>
																<div class="form-group">
																	<input type="text" class="form-control mini-input" name="qte_cde[]" value="<?=isset($article['qte'])? $article['qte']:""?>">
																</div>
															</td>
															<td>
																<div class="form-group">
																	<input type="text" class="form-control mini-input" name="qte_litige[]" id="qte_litige">
																</div>
															</td>
															<td>
																<div class="form-group">
																	<select class="form-control" name="id_reclamation[]" id="id_reclamation">
																		<option value="">sélectionnez</option>
																		<?php foreach ($listReclamations as $key => $reclam): ?>
																			<option value="<?=$key?>">
																				<?=$listReclamations[$key]?>
																			</option>
																		<?php endforeach ?>
																	</select>
																</div>

															</td>
															<td>
																<button type="submit" class="btn btn-primary" name="add_article[<?=$keyArticles?>]">Ajouter</button>
																<input type="hidden" class="form-control" name="dossier_litige[]"  value="<?=$detailLitige[0]['dossier']?>">
																<input type="hidden" class="form-control" name="id_litige[]" value="<?=$detailLitige[0]['id_dossier']?>">
																<input type="hidden" class="form-control" name="fournisseur[]" value="<?=$article['fournisseur']?>">
																<input type="hidden" class="form-control" name="cnuf[]" value="<?=$article['cnuf']?>">
																<?php if ($_POST['cde_mag']==1): ?>

																	<input type="hidden" class="form-control" name="facture[]" value="<?=$article['facture']?>">
																	<input type="hidden" class="form-control" name="palette[]" value="<?=$article['palette']?>">
																	<input type="hidden" class="form-control" name="date_facture[]" value="<?=$article['date_mvt']?>">
																<?php endif ?>

															</td>
														</tr>
													<?php endforeach ?>

												</tbody>
											</table>
										</div>
									</div>
								<?php endif ?>
							</form>
						<?php endif ?>

						<!-- ./container -->
					</div>

					<?php
					require '../view/_footer-bt.php';
					?>

