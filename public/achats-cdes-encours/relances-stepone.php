<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


require '../../Class/Db.php';
require '../../Class/CdesDao.php';
require '../../Class/CdesAchatDao.php';
require '../../Class/CdesRelancesDao.php';
require '../../Class/FournisseursHelpers.php';
require '../../Class/UserDao.php';
require '../../Class/FouDao.php';
// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');
$pdoFou=$db->getPdo('fournisseurs');
$pdoDAchat=$db->getPdo('doc_achats');

$cdesDao=new CdesDao($pdoQlik);
$cdesRelancesDao=new CdesRelancesDao($pdoDAchat);
$cdesAchatDao=new CdesAchatDao($pdoDAchat);
$userDao= new UserDao($pdoUser);
$fouDao=new FouDao($pdoFou);
if(isset($_GET['success'])){
	if(isset($_SESSION['temp_relance'])){
		unset($_SESSION['temp_relance']);
	}
	if(isset($_SESSION['temp_relance_perm'])){
		unset($_SESSION['temp_relance_perm']);
	}
	$success[]="Relances envoyées avec succès";
}




if(isset($_SESSION['temp_relance']) || isset($_SESSION['temp_relance_perm'])){
	if(isset($_SESSION['temp_relance'])){
		array_unique($_SESSION['temp_relance']);
		$param="WHERE id=".join(' OR id=',$_SESSION['temp_relance']);
	}
	if(isset($_SESSION['temp_relance_perm'])){
		array_unique($_SESSION['temp_relance_perm']);
		$param="WHERE id=".join(' OR id=',$_SESSION['temp_relance_perm']);
	}

	$listProd=$cdesDao->getEncoursByIdsGroup($param);
	$listFou=$cdesDao->getEncoursCnufByIds($param);
	$listCnuf=array_column($listFou,'cnuf');
	$paramContact="WHERE cnuf=".join(' OR cnuf=',$listCnuf);
	$listContact=$fouDao->getFouContactGroup($paramContact);
}else{
	echo "une erreur est survenue";
	exit();
}

if(isset($_POST['send_to_me']) || isset($_POST['send_to_fou'])){

	foreach ($_POST['id_contact'] as $keyCnufContact => $value) {
		if(isset($_POST['id_contact_email'][$keyCnufContact])){
			$newAr=explode("#",$keyCnufContact);
			$cnufIdContact[$newAr[0]]['id_contact'][]=$newAr[1];
			$cnufIdContact[$newAr[0]]['email'][]=$_POST['id_contact_email'][$keyCnufContact];
		}
	}
	foreach($_POST['qte_restante'] as $keyCnufEncours => $value){
		$newAr=explode("#",$keyCnufEncours);
		$cnufIdEncours[$newAr[0]]['id_encours'][]=$newAr[1];
		$cnufIdEncours[$newAr[0]]['qte_restante'][]=$_POST['qte_restante'][$keyCnufEncours];
	}




	for ($i=0; $i <count($listCnuf) ; $i++) {
		// si on n'a pas de case à cocher email sélectionnée et pas d'email libre
		if(!isset($cnufIdContact[$listCnuf[$i]]) && empty($_POST['email'][$listCnuf[$i]])){
			$errors[]="Pour le cnuf ".$listCnuf[$i].", aucune adresse mail n'a été sélectionnée et vous n'avez pas saisi d'adresse mail, vous ne pouvez pas envoyer les relances";
		}
		if(!empty($_POST['email'][$listCnuf[$i]])){
			$listEmail=explode(",", $_POST['email'][$listCnuf[$i]]);
			for ($k=0; $k <count($listEmail) ; $k++) {
				if(!filter_var(trim($listEmail[$k]),FILTER_VALIDATE_EMAIL)){
					$errors[]='l\'adresse mail "' .$listEmail[$i]. '" n\'est pas valide, merci de la corriger';
				}
			}
		}
	}
// on insere une relance par fournisseur
	if(empty($errors)){
		for ($i=0; $i <count($listCnuf) ; $i++) {
			$idR=$cdesRelancesDao->insertRelance($listCnuf[$i], $_POST['cmt'][$listCnuf[$i]]);
			if(isset($cnufIdContact[$listCnuf[$i]])){
				foreach($cnufIdContact[$listCnuf[$i]]['email'] as $m =>$val){
					$cdesRelancesDao->insertEmail($idR, $cnufIdContact[$listCnuf[$i]]['email'][$m], $cnufIdContact[$listCnuf[$i]]['id_contact'][$m]);
				}
			}
			if(!empty($_POST['email'][$listCnuf[$i]])){
				$listEmail=explode(",", $_POST['email'][$listCnuf[$i]]);
				for ($k=0; $k <count($listEmail) ; $k++) {
					$cdesRelancesDao->insertEmail($idR, trim($listEmail[$k]), null);

				}
			}
			if(isset($cnufIdEncours[$listCnuf[$i]])){
				foreach($cnufIdEncours[$listCnuf[$i]]['id_encours'] as $n =>$val){
					$cdesRelancesDao->insertRelanceDetail($idR, $cnufIdEncours[$listCnuf[$i]]['id_encours'][$n],  $cnufIdEncours[$listCnuf[$i]]['qte_restante'][$n]);
				}

			}

		}
	}

	if(empty($errors)){
		if(isset($_SESSION['temp_relance'])){
			if (isset($_POST['send_to_fou'])) {
				header('Location:relances-synthese.php?op&dest=fou');

			}elseif(isset($_POST['send_to_me'])){
				header('Location:relances-synthese.php?op&dest=me');

			}
		}
		elseif(isset($_SESSION['temp_relance_perm'])){
			if (isset($_POST['send_to_fou'])) {
				header('Location:relances-synthese.php?perm&dest=fou');

			}elseif(isset($_POST['send_to_me'])){
				header('Location:relances-synthese.php?perm&dest=me');

			}
		}

	}
}


//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row pt-5 pb-3">
		<div class="col">
			<h1 class="text-main-blue">Envoi des relances</h1>
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
		<div class="col">
			<div class="alert alert-secondary">
				<div class="text-danger font-weight-bold"><i class="fas fa-exclamation-circle pr-3"></i>Adresses emails supplémentaires</div>
				Les adresses emails supplémentaires doivent être saisies en les séparant par une <strong>virgule</strong>. Si le format n'est pas correct, vous verrez un message d'erreur s'afficher à droite des adresses saisies
			</div>
		</div>
	</div>
	<?php if (isset($_SESSION['temp_relance']) || isset($_SESSION['temp_relance_perm'])): ?>

	<div class="row">
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
				<?php foreach ($listFou as $key => $fou): ?>
					<div class="row border rounded p-3">
						<div class="col">

							<!-- fournisseur -->
							<div class="row">
								<div class="col">
									<h5 class="text-main-blue"><?= $fou['fournisseur'] ?></h5>
								</div>
							</div>
							<?php if (isset($listContact[$fou['cnuf']])): ?>
								<div class="row">
									<div class="col cols-three">
										<?php foreach ($listContact[$fou['cnuf']] as $keyContact => $value): ?>
											<div class="form-check">
												<input class="form-check-input" type="checkbox" value="<?=$listContact[$fou['cnuf']][$keyContact]['email']?>"  name="id_contact_email[<?=$fou['cnuf']?>#<?=$listContact[$fou['cnuf']][$keyContact]['id']?>]" checked>
												<label class="form-check-label"><?=$listContact[$fou['cnuf']][$keyContact]['email']?></label>
											</div>
											<input type="hidden" name="id_contact[<?=$fou['cnuf']?>#<?=$listContact[$fou['cnuf']][$keyContact]['id']?>]" value="<?=$listContact[$fou['cnuf']][$keyContact]['id']?>">

										<?php endforeach ?>
									</div>
								</div>
							<?php endif ?>
							<div class="row">
								<div class="col">
									<div class="form-group form-inline">
										<label>Email(s) supplémentaires :</label>
										<input type="text" class="form-control w-500 email" name="email[<?=$fou['cnuf']?>]" data-cnuf="<?=$fou['cnuf']?>">
									</div>
								</div>
								<div class="col error-email mt-4" id="error-email-<?=$fou['cnuf']?>"></div>
							</div>
							<input type="hidden" name="cnuf[<?=$fou['cnuf']?>]" value="<?=$fou['cnuf']?>">
							<!-- article -->
							<div class="row font-weight-bold">
								<div class="col-lg-1">Article</div>
								<div class="col-lg-3">Référence</div>
								<div class="col-lg-4">Désignation</div>
								<div class="col">Qte restante</div>
							</div>

							<?php foreach ($listProd[$fou['cnuf']] as $keyProd => $value): ?>
								<div class="row">
									<div class="col-lg-1">
										<?=$listProd[$fou['cnuf']][$keyProd]['article']?>
									</div>
									<div class="col-lg-3">
										<?=$listProd[$fou['cnuf']][$keyProd]['ref']?>
									</div>
									<div class="col-lg-4">
										<?=$listProd[$fou['cnuf']][$keyProd]['libelle_art']?>
									</div>
									<div class="col">
										<div class="form-group form-inline no-margin mini-input">
											<input type="text" class="form-control ml-3 w-60 text-right special" name="qte_restante[<?=$fou['cnuf']?>#<?=$listProd[$fou['cnuf']][$keyProd]['id']?>]" value="<?=$listProd[$fou['cnuf']][$keyProd]['qte_cde']?>">
										</div>
									</div>
								</div>
							<?php endforeach ?>
							<div class="row">
								<div class="col">
									<div class="form-group">
										<textarea class="form-control" name="cmt[<?=$fou['cnuf']?>]"  row="3" placeholder="Commentaire"></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach ?>
				<div class="row mb-5">
					<div class="col text-right align-self-end">Envoyer les relances :</div>
					<div class="col-auto text-right">
						<button class="btn btn-primary" name="send_to_me">A moi</button>
						<button class="btn btn-orange" name="send_to_fou">Au fournisseur</button>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php endif ?>

</div>
<script type="text/javascript">

	$(".email").keyup(function(){
		// data-cnuf
		// data-error-email
		var email = $(this).val();
		var cnuf=$(this).data("cnuf");

		console.log(email);
		console.log(cnuf);
		// var msgZone=$("#error-email-"+cnuf).find(`[data-error-email='${cnuf}']`);
		// console.log(msgZone);

		var filter = /^(([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)(\s*(;|,)\s*|\s*$))*$/;
		if (!filter.test(email)) {
             //alert('Please provide a valid email address');
             $("#error-email-"+cnuf).text(email+" n'est pas une adresse valide");
             $("#error-email-"+cnuf).addClass('alert alert-danger');
             // email.focus;
         } else {
         	$("#error-email-"+cnuf).text("adresse(s) valide(s)");
         	$("#error-email-"+cnuf).removeClass('alert-danger');
         	$("#error-email-"+cnuf).addClass('alert alert-success');

         }
     });
 </script>

 <?php
 require '../view/_footer-bt.php';
 ?>

 <!-- o.fn.init [prevObject: o.fn.init(6), context: document, selector: ".col.error-email [data-error-email='110830']"] -->
<!--  o.fn.init [prevObject: o.fn.init(6), context: document, selector: ".col.error-email [data-error-email='110830']"]
context: document
length: 0
prevObject: o.fn.init(6) [div.col.error-email, div.col.error-email, div.col.error-email, div.col.error-email, div.col.error-email, div.col.error-email, prevObject: o.fn.init(1), context: document, selector: ".col.error-email"]
selector: ".col.error-email [data-error-email='110830']"
__proto__: Object(0) -->

<!-- o.fn.init [div.col.error-email, prevObject: o.fn.init(105), context: document, selector: ".col [data-error-email='110830']"] -->