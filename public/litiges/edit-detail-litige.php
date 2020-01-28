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

require('../../Class/Helpers.php');

//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';



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




//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getDetailArt($pdoLitige)
{
	$req=$pdoLitige->prepare("
		SELECT
		dossiers.id as id_main,	dossiers.dossier,dossiers.date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,dossiers.user_crea,dossiers.galec,dossiers.etat_dossier,vingtquatre, dossiers.id_web_user, inversion,inv_article,inv_fournisseur,inv_tarif,inv_descr,nom, valo, flag_valo, id_reclamation,inv_palette,inv_qte,id_robbery, commission, box_tete, box_art,
		details.id as id_detail,details.ean,details.id_dossier,	details.palette,details.facture,details.article,details.tarif,details.qte_cde, details.qte_litige,details.valo_line,details.dossier_gessica,details.descr,details.fournisseur,details.pj,DATE_FORMAT(details.date_facture, '%d-%m-%Y') as datefacture,
		reclamation.reclamation,
		btlec.sca3.mag, btlec.sca3.centrale, btlec.sca3.btlec

		FROM details
		LEFT JOIN dossiers ON details.id_dossier=dossiers.id
		LEFT JOIN reclamation ON details.id_reclamation = reclamation.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec

		WHERE details.id= :id ORDER BY date_crea");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}

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


function majDetailInv($pdoLitige){
	$valo=($_POST['tarif']/$_POST['qte_cde']*$_POST['qte_litige'])-$_POST['pu']*$_POST['inv_qte'];

	$req=$pdoLitige->prepare("UPDATE details SET inv_qte= :inv_qte, qte_litige= :qte_litige, valo_line= :valo_line WHERE id= :id");
	$req->execute([
		':inv_qte'		=>$_POST['inv_qte'],
		':qte_litige'	=>$_POST['qte_litige'],
		':valo_line'	=>$valo,
		':id'			=>$_GET['id']
	]);
	return $req->rowCount();
}
function majDetailNorm($pdoLitige){
	$valo=$_POST['tarif']/$_POST['qte_cde']*$_POST['qte_litige'];

	$req=$pdoLitige->prepare("UPDATE details SET qte_litige= :qte_litige, valo_line= :valo_line WHERE id= :id");
	$req->execute([
		':qte_litige'	=>$_POST['qte_litige'],
		':valo_line'	=>$valo,
		':id'			=>$_GET['id']
	]);
	return $req->rowCount();
}

function getSumLitige($pdoLitige, $idLitige){
	$req=$pdoLitige->prepare("SELECT sum(valo_line) as sumValo, dossiers.valo, id_reclamation FROM details LEFT JOIN dossiers ON details.id_dossier= dossiers.id WHERE details.id_dossier= :id");
	$req->execute([
		':id'		=>$idLitige
	]

);
	return $req->fetch(PDO::FETCH_ASSOC);
}
function getSumPaletteRecu($pdoLitige,$idLitige){
	$req=$pdoLitige->prepare("SELECT sum(tarif) as sumValo FROM palette_inv  WHERE palette_inv.id_dossier= :id");
	$req->execute([
		':id'		=>$idLitige
	]

);
	return $req->fetch(PDO::FETCH_ASSOC);
}
function updateValoDossier($pdoLitige,$sumValo,$idLitige){
	$req=$pdoLitige->prepare("UPDATE dossiers SET valo= :valo WHERE id= :id");
	$req->execute([
		':valo'			=>$sumValo,
		':id'			=>$idLitige
	]);
	return $req->rowCount();
}

$article=getDetailArt($pdoLitige,$_GET['id']);

if(isset($_POST['search'])){


	$found=searchDbArt($pdoQlik);
	if(!$found){
		$result="EAN non trouvé";
	}
}

if(isset($_POST['maj'])){

	if(isset($_POST['inv_qte'])){
		// maj dans le cas d'une inversion de produit
		$done=majDetailInv($pdoLitige);

	}else{
		// maj dans tout les autres cas => juste valo et qte
		$done=majDetailNorm($pdoLitige);
	}
	if($done==1){

		$sumLitige=getSumLitige($pdoLitige, $article['id_main']);

		if($sumLitige['id_reclamation']==7)
		{
			$sumRecu=getSumPaletteRecu($pdoLitige,$article['id_main']);
			$sumCde=$sumLitige['sumValo'];
			$sumRecu=$sumRecu['sumValo'];
			$sumValo=$sumCde -$sumRecu;
			$update=updateValoDossier($pdoLitige,$sumValo,$article['id_main']);
			// $redir='?id='.$_GET['id'].'&success';
			// unset($_POST);
			// header("Location: ".$_SERVER['PHP_SELF'].$redir,true,303);

		}
		else{
			$sumValo=$sumLitige['sumValo'];

			echo $sumValo;
			echo "<br>";

			echo $article['id_main'];
			$update=updateValoDossier($pdoLitige,$sumValo, $article['id_main']);
			$redir='?id='.$_GET['id'].'&success';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$redir,true,303);
		}




	}

}

if(isset($_GET['success'])){
	$success[]="Mise à jour effectuée";
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
		<div class="col">
			<h1 class="text-main-blue py-5 ">Dossier N°<?=$article['dossier']?></h1>

		</div>
		<div class="col"><?=Helpers::returnBtn('bt-detail-litige.php?id='.$article['id_dossier'])?>
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
	<div class="col-lg-1"></div>
	<div class="col">
		<form method="post"  action="<?=$_SERVER['PHP_SELF']?>?id=<?=$_GET['id']?>">
			<div class="row">
				<div class="col text-main-blue pb-3"><h5>Produit commandé : </h5></div>
			</div>
			<div class="row">
				<div class="col">
					<div class="form-group">
						<label for="article">Code article :</label>
						<input type="text" class="form-control" name="article" id="article"  value="<?=$article['article']?>" disabled>
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<label for="dossier">Code dossier :</label>
						<input type="text" class="form-control" name="dossier" id="dossier"  value="<?=$article['dossier_gessica']?>" disabled>
					</div>
				</div>

				<div class="col">
					<div class="form-group">
						<label for="qte_cde">Quantité commandée :</label>
						<input type="text" class="form-control" name="qte_cde" id="qte_cde" value="<?=$article['qte_cde']?>"  readonly>
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<label for="tarif">Tarif :</label>
						<input type="text" class="form-control" name="tarif" id="tarif" value="<?=$article['tarif']?>"  readonly>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col"></div>
				<div class="col"></div>

				<div class="col">
					<div class="form-group">
						<label for="qte_litige">Quantité litige :</label>
						<input type="text" class="form-control" name="qte_litige"  value="<?=$article['qte_litige']?>" id="qte_litige">
					</div>
				</div>
				<div class="col">
					<?php if ($article['inversion']==""): ?>
						<div class="form-group">
							<label for="valo">Valo :</label>
							<input type="text" class="form-control" name="valo"  value="<?=$article['valo_line']?>" id="valo"  disabled>
						</div>
					<?php endif ?>

				</div>


			</div>

			<?php if ($article['inversion']!="" && $article['inv_qte']!=""): ?>
				<div class="row">
					<div class="col text-main-blue pb-3"><h5>Produit reçu en inversion de référence: </h5></div>
				</div>
				<?php if ($article['inv_article']==""): ?>
					<div class="row">
						<div class="col pb-3">
							L'EAN saisi par le magasin n'a pas permis de trouver d'article correspondant.<br> Vous pouvez le modifer ci dessous et effectuer à nouveau une recherche dans la base
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="ean">EAN :</label>
								<input type="text" class="form-control" name="ean"  value="<?=$article['inversion']?>" id="ean" >
							</div>
						</div>
						<div class="col mt-4 pt-2">
							<button class="btn btn-primary" name="search">Rechercher l'article</button>
						</div>
					</div>
					<?php if (isset($found) && $found!=false): ?>
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
												<td><a href="edit-detail-litige-inv.php?id=<?=$_GET['id']?>&id_inv=<?=$f['id']?>&inv_qte=<?=$article['inv_qte']?>"><i class="fas fa-check-circle"></i></a></td>
											</tr>
										<?php endforeach ?>

									</tbody>
								</table>
							</div>
						</div>
						<?php elseif(isset($found) && $found==false): ?>
							<div class="alert alert-warning">Aucun article trouvé avec ce Gencod</div>
						<?php endif ?>
						<?php else: ?>
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label for="inv_article">Article reçu en inversion :</label>
										<input type="text" class="form-control" name="inv_article"  value="<?=$article['inv_article']?>" id="inv_article" disabled>
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label for="pu">Prix unitaire :</label>
										<input type="text" class="form-control" name="pu"  value="<?=$article['inv_tarif']?>" id="pu" readonly>
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label for="inv_qte">Quantité reçue :</label>
										<input type="text" class="form-control" name="inv_qte"  value="<?=$article['inv_qte']?>" id="inv_qte">
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label for="inv_somme">Soit une valeur de  :</label>
										<input type="text" class="form-control" name="inv_somme"  value="<?=$article['inv_qte'] * $article['inv_tarif']?>" id="inv_somme" disabled>
									</div>
								</div>
							</div>
						<?php endif ?>
					<?php endif ?>
					<div class="row mt-5">
						<div class="col"></div>
						<div class="col-3">
							<div class="form-group">
								<label for="valo">Valo :</label>
								<input type="text" class="form-control" name="valo"  value="<?=$article['valo_line']?>" id="valo"  disabled>
							</div>
						</div>
					</div>
					<div class="row pb-5">
						<div class="col text-center">
							<button class="btn btn-primary" name="maj">Mettre à jour</button>
						</div>
					</div>
				</form>
			</div>
			<div class="col-lg-1"></div>
		</div>
		<!-- ./container -->
	</div>

	<?php
	require '../view/_footer-bt.php';
	?>