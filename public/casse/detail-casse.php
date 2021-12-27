<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

require '../../Class/Db.php';
require ('../../Class/Helpers.php');
require ('../../Class/CrudDao.php');
require ('../../Class/casse/CasseDao.php');
require('casse-getters.fn.php');



$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoCasse=$db->getPdo('casse');

$casseCrud=new CrudDao($pdoCasse);
$casseDao=new CasseDao($pdoCasse);




//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function addInfoFi($pdoCasse){
	$fields=['mt_mag','mt_decote','mt_ndd'];
	for ($i=0; $i < count($fields); $i++)
	{
		if($_POST[$fields[$i]]=='')
		{
			$_POST[$fields[$i]]=null;
		}
	}
	$req=$pdoCasse->prepare("UPDATE casses SET mt_mag = :mt_mag, mt_decote= :mt_decote, mt_ndd= :mt_ndd, num_ndd= :num_ndd WHERE id= :id");
	$req->execute([
		':mt_mag' => $_POST['mt_mag'],
		':mt_decote' => $_POST['mt_decote'],
		':mt_ndd' => $_POST['mt_ndd'],
		':num_ndd' => $_POST['num_ndd'],
		':id'		=>$_GET['id']

	]);
	return $req->rowCount();
}

function endCasse($pdoCasse){
	if($_POST['motif']=="detruit"){
		$detruit=1;
		$repris=0;
	}else{
		$detruit=0;
		$repris=1;
	}
	$req=$pdoCasse->prepare("UPDATE casses SET detruit = :detruit, repris= :repris, date_clos= :date_clos, etat= :etat WHERE id= :id");
	$req->execute([
		':detruit'	=>$detruit,
		':repris'	=>$repris,
		':date_clos'	=>$_POST['date_clos'],
		':id'		=>$_GET['id'],
		':etat'		=>1

	]);
	return $req->rowCount();
}


//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

//------------------------------------------------------
//			TRAITEMENT
//------------------------------------------------------

if(isset($_GET['id'])){
	$idCasse=$_GET['id'];
	$casseInfo=getCasse($pdoCasse, $idCasse);
	$serials=getSerialsCasse($pdoCasse, $idCasse);
	$mtMag= ($casseInfo['mt_mag']!=null) ? $casseInfo['mt_mag'] : '0.00';
	$mtDecote= ($casseInfo['mt_decote']!=null) ? $casseInfo['mt_decote'] : '0.00';
	$mtNdd= ($casseInfo['mt_ndd']!=null) ? $casseInfo['mt_ndd'] : '0.00';
	$numNdd= ($casseInfo['num_ndd']!=null) ? $casseInfo['num_ndd'] : '_';
	$cmts=getCmt($pdoCasse, $idCasse);
}
else{
	$loc='Location:casse-dashboard.php?error=1';
	header($loc);
}

if(isset($_POST['submit_mag'])){
	$add=addInfoFi($pdoCasse);
	if($add==1){
		$success[]="info financières ajoutées avec succès";
	}
	else
	{
		$errors[]="impossible d'ajouter les informations";
	}
}
if(isset($_POST['submit_clos']))
{
	if(isset($_POST['motif'])){
		$maj=endCasse($pdoCasse);
		if($maj==1){
			$success[]="Casse mise à jour et cloturée";
		}
		else{
			$errors[]="impossible de faire la mise à jour";

		}
	}else{
		$errors[]="Merci de sélectionner un motif";
	}
}

 // on affiche soit les info financières, soit la cloture avec reprise ou destruction  soit les formulaires de traitement

// if($casseInfo['detruit']==1 ){
// 	$sansSuite=true;
// }
if(isset($_POST['add-cmt'])){
	if(!empty($_POST['cmt'])){
		$cmt=strip_tags($_POST['cmt']);
		$cmt=nl2br($cmt);
		$req=$pdoCasse->prepare("INSERT INTO cmt (id_casse, cmt, id_web_user, date_cmt) VALUES (:id_casse, :cmt, :id_web_user, :date_cmt)");
		$req->execute([
			':id_casse'	=> $idCasse,
			':cmt'		=> $cmt,
			':id_web_user'	=>$_SESSION['id_web_user'],
			':date_cmt'	=>date('Y-m-d H:i:s')
		]);
		$insert=$req->rowCount();

		if($insert>0){
			header('Location:detail-casse.php?id='.$idCasse);
		}
		else{
			$errors[]="Impossible d'enregistrer votre commentaire";
		}
	}else{
		$errors[]="Merci de saisir un commentaire";
	}
}

if(isset($_POST['update_casse'])){
	$nbColisBefore=$_POST['nb_colis_before_update'];
	$nbColis=$_POST['nb_colis'];
	$newValo=$nbColis*$_POST['valo']/$nbColisBefore;
	$casseCrud->update('casses', 'id='.$_GET['id'], ['nb_colis'=>$nbColis, 'valo'=>$newValo]);
	if(isset($_POST['serials']) && !empty($_POST['serials'])){
		$casseCrud->deleteByField("serials", 'id_casse', $_GET['id']);
		foreach ($_POST['serials'] as $keyInput => $value) {
			$casseDao->addSerials($_GET['id'],$_POST['serials'][$keyInput]);
		}
	}

	header('Location:detail-casse.php?id='.$_GET['id']);

}


function getPagination($pdoCasse){
	$req=$pdoCasse->query("SELECT id FROM casses ORDER BY id ASC");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_COLUMN);
}

$pagination=getPagination($pdoCasse);



$page=array_search($_GET['id'], $pagination);
$last=$pagination[count($pagination)-1];

if($_GET['id']!=$last){
	$next=$pagination[$page+1];
}
else{
	$next=$last;
}

if($_GET['id']>$pagination[0])
{
	$prev=$pagination[$page-1];
}
else{
	$prev=$pagination[0];
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
		<div class="row pt-3">
			<div class="col">
				<h1 class="text-main-blue ">Déclaration de casse n°<?=$casseInfo['cassesid']?></h1>

			</div>
			<div class="col-auto">
				<?= Helpers::returnBtn('casse-dashboard.php'); ?>

			</div>
		</div>

		<div class="row">
			<div class="col">
				<p class="text-right pt-1">
					<?php if ($_GET['id']!=$pagination[0]): ?>
						<a href="detail-casse.php?id=<?=$prev?>" class="grey-link"><i class="fas fa-angle-left pr-2 pt-2"></i>Précédent</a>
					<?php endif ?>
					<?php if ($_GET['id']!=$last): ?>
						<a href="detail-casse.php?id=<?=$next?>" class="grey-link"><i class="fas fa-angle-right pl-5 pr-2 pt-1"></i>Suivant</a></p>

					<?php endif ?>
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
			<div class="bg-separation"></div>

			<div class="row pt-5">
				<div class="col">
					<h5 class="text-main-blue">Produit(s) : </h5>
				</div>
			</div>

			<div class="row yellow-box border-top-big">
				<div class="col">
					<!-- operteur - service - type de casse -->
					<div class="row ">
						<div class="col">
							<i class="fas fa-calendar-alt pr-3"></i><?=$casseInfo['dateCasse']?>
						</div>
						<div class="col">
							<i class="fas fa-user pr-3"></i> <?=$casseInfo['prenom'] .' '.$casseInfo['nom']?>
						</div>
						<div class="col">
							<span class="heavy"> Origine : </span><?=$casseInfo['origine']?>
						</div>
						<div class="col">
							<span class="heavy"> Type de casse : </span><?=$casseInfo['type']?>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<span class="heavy">Palette 4919 : </span><?=$casseInfo['palette']?>
						</div>
						<div class="col">
							<span class="heavy">Palette contremarque : </span><?=$casseInfo['contremarque']?>
						</div>

					</div>
				</div>
			</div>
			<div class="row bg-alert-grey mb-3">
				<div class="col">
					<div class="row">
						<div class="col">
							<span class="heavy"> Article : </span>
							<?=$casseInfo['article']?>
						</div>
						<div class="col">
							<span class="heavy"> Dossier : </span>
							<?=$casseInfo['dossier']?>
						</div>
						<div class="col">
							<span class="heavy"> Fournisseur : </span>
							<?=$casseInfo['fournisseur']?>
						</div>
						<div class="col">
							<span class="heavy">GT : </span>
							<?=$casseInfo['gt']?>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<span class="heavy">Désignation : </span>
							<?=$casseInfo['designation']?>
						</div>
						<div class="col">
							<span class="heavy">Catégorie : </span>
							<?=$casseInfo['categorie']?>
						</div>
					</div>

					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" >

						<div class="row">
							<div class="col-1 heavy">PCB : </div>
							<div class="col-2 text-right"><?=$casseInfo['pcb']?></div>


							<div class="col-2 heavy">Nb colis : </div>

							<div class="col-1 text-right">
								<input type="hidden" class="form-control" name="valo" value="<?=$casseInfo['valo']?>">
								<div class="form-group">
									<input type="text" class="form-control text-right" name="nb_colis" id="nb_colis" value="<?=$casseInfo['nb_colis']?>">
								</div>
							</div>
							<div class="col-1 heavy">SN : </div>

							<?php if (isset($serials[$casseInfo['cassesid']])): ?>
								<div class="col text-right">
									<input type="hidden" class="form-control" name="nb_colis_before_update" id="nb_colis_before_update" value="<?=count($serials[$casseInfo['cassesid']])?>">

									<?php foreach ($serials[$casseInfo['cassesid']] as $key => $sn): ?>
										<div class="row" data-row-serial-id="<?=$sn['serial_nb']?>">
											<div class="col">
												<div class="form-group">
													<input type="text" class="form-control" name="serials[]"  data-input-serial-id="<?=$sn['serial_nb']?>" value="<?=$sn['serial_nb']?>">
												</div>
											</div>
											<div class="col-1 mt-2 text-left">
												<i class="fas fa-minus-circle text-danger fa-lg minus" data-minus-serial-id="<?=$sn['serial_nb']?>"></i>
											</div>
										</div>
									<?php endforeach ?>
									<div class="row">
										<div class="col" id="new-sn">

										</div>
									</div>
								</div>
							<?php endif ?>

						</div>

						<div class="row">
							<div class="col-1 heavy">PU : </div>
							<div class="col-2 text-right"><?=$casseInfo['pfnp']?>&euro;</div>
							<div class="col-1 heavy">Valo : </div>
							<div class="col-2 text-right bg-light-blue"><?=$casseInfo['valo']?>&euro;</div>
						</div>
						<div class="row">
							<div class="col heavy pb-2">
								Commentaires :
							</div>
						</div>
						<?php if ($cmts): ?>
							<?php foreach ($cmts as $cmt): ?>
								<div class="row pb-2">
									<div class="col-auto pl-5">
										<?= $cmt['dateCmt'] ?> :
									</div>
									<div class="col patrick-hand text-main-blue">
										<?= $cmt['cmt'] ?>
									</div>
								</div>
							<?php endforeach ?>
						<?php endif ?>

						<div class="row">
							<div class="col text-right">
								<button class="btn btn-black" name="update_casse">Modifier</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" >
						<div class="row mb-3 border p-2 bg-grey">
							<div class="col">
								<div class="form-group">
									<label><i class="fas fa-comments pr-3"></i>Ajouter un commentaire : </label>
									<textarea class="form-control" name="cmt"></textarea>
								</div>
							</div>
							<div class="col-4 d-flex align-items-end pb-3">
								<button class="btn btn-black" name="add-cmt">Enregistrer</button>
							</div>
						</div>
					</form>

				</div>
			</div>
			<div class="row">
				<div class="col-3">Montant Vente Magasin :</div><div class="col-2 text-right"> <?=$mtMag?> &euro;</div>
			</div>
			<div class="row">
				<div class="col-3">Décote /avoir :</div><div class="col-2 text-right"> <?=$mtDecote?> &euro;</div>
			</div>
			<div class="row">
				<div class="col-3">Note de débit fournisseur :</div><div class="col-2 text-right"> <?=$mtNdd?> &euro;</div>
			</div>
			<div class="row pb-5">
				<div class="col-3">Numéro de la note de débit :</div><div class="col-2 text-right"> <?=$numNdd?></div>
			</div>
			<!-- si dossier clos -->
			<?php if ($casseInfo['etat']==1): ?>
				<div class="row pb-5">
					<div class="col">
						<?php if ($casseInfo['detruit'] ==1): ?>
							<p class="alert alert-primary ">Ce ou ces produits ont été détruits</p>
						<?php else: ?>
							<p class="alert alert-primary ">Ce ou ces produits ont été expédiés</p>
						<?php endif ?>
					</div>
				</div>
			<?php endif ?>




			<!-- ./container -->
		</div>

		<script type="text/javascript">
			$(document).ready(function (){

				$('.minus').hide();
				var inputSerial='<div class="row"><div class="col"><div class="form-group"><input type="text" class="form-control" name="serials[]"  value=""></div></div></div>';
				var alert='<div class="alert alert-danger text-left">Merci de supprimer les numéros de series des colis qui ne font plus parti de cette casse</div>';


				var nbColisBefore=$('#nb_colis_before_update').val();
				$('#nb_colis').keyup(function(){
					$('#new-sn').empty();
					$('.minus').hide();

					var nbColis=$('#nb_colis').val();
					var diffColis=nbColis-nbColisBefore;
					console.log(diffColis);
					if(diffColis>0){
						for (var i = 0; i < diffColis; i++) {
							$('#new-sn').append(inputSerial);
						}
					}else if(diffColis==0){

					}else{
						$('.minus').show();
						$('#new-sn').append(alert);
					}
				});

				$('.minus').click(function(){
					var idSerial=$(this).data('minus-serial-id');
					$('[data-row-serial-id="'+idSerial+'"]').remove();
					var nbColis=$('#nb_colis').val();

					var nbInput=$("[data-input-serial-id]").length;

					var diffColis=nbColis-nbInput;
					if(diffColis==0){
						$('.minus').hide();
						$('#new-sn').empty();
					}
				});

			});

		</script>


		<?php
		require '../view/_footer-bt.php';
	?>