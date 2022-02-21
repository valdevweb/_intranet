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
require '../../Class/CrudDao.php';
require('../../Class/casse/ExpDao.php');
require('../../Class/casse/PalettesDao.php');

require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoCasse=$db->getPdo('casse');



$expDao=new ExpDao($pdoCasse);

$paletteDao=new PalettesDao($pdoCasse);
$casseCrud=new CrudDao($pdoCasse);


$idAffectation=2;
$btlec=4920;
$galec=9966;


$palettesDispo=$paletteDao->getEnStockDispo();



if(isset($_POST['submit'])){


	if(!empty($_POST['id_palette'])){
		$magExp=$expDao->magExpAlreadyExist($btlec);
		if(empty($magExp)){
			$lastExp=$expDao->insertExp($btlec, $galec, $idAffectation);
			$lastExp=$lastExp;
		}else{
			$lastExp=$magExp['id'];
		}

	}else{
		$errors[]="Merci de sélectionner au moins une palette";
	}
	if(empty($errors)){
		$nbPalette=0;
		$strListPalette="";
		for ($i=0; $i <count($_POST['id_palette']) ; $i++) {
			$added=$paletteDao->updatePaletteExp($_POST['id_palette'][$i], $lastExp, 2);
			$strListPalette.="- ".$_POST['palette_nb'][$_POST['id_palette'][$i]].'<br>';
			$nbPalette++;
		}
// envoi mail avertissement

		if(VERSION=="_"){
			$dest=['valerie.montusclat@btlecest.leclerc'];
			$cc=[];
		}else{
			$dest=['nathalie.pazik@btlecest.leclerc', 'christelle.trousset@btlecest.leclerc'];
			$cc=['valerie.montusclat@btlecest.leclerc', 'jonathan.domange@btlecest.leclerc'];
		}


		$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
		$mailer = new Swift_Mailer($transport);

		$htmlMail = file_get_contents('../mail/occasion-casse-newexp.html');
		$htmlMail=str_replace('{NB}',$nbPalette,$htmlMail);
		$htmlMail=str_replace('{LISTPALETTE}',$strListPalette,$htmlMail);
		$subject='Portail BTLec Est - regroupement palettes GT Occasion';
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(EXPEDITEUR_MAIL)
		->setTo($dest)
		->setCc($cc);

		if (!$mailer->send($message, $failures)){
			print_r($failures);
		}else{
			$success[]="mail envoyé avec succés";
		}
		$successQ='?success=sent';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);



	}
}
if(isset($_GET['success'])){
    $arrSuccess=[
        'sent'=>'Un mail a été envoyé pour prévenir l\'entrepôt, vous êtes en copie',
    ];
    $success[]=$arrSuccess[$_GET['success']];
}

//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div id="container" class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Palettes à traiter</h1>
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
		<div class="col text-right">Afficher les détails des palettes</div>
		<div class="col-auto">
			<label class="switch">
				<input class="switch-input-detail" type="checkbox" />
				<span class="switch-label" data-on="On" data-off="Off"></span>
				<span class="switch-handle"></span>
			</label>


		</div>
	</div>

	<div class="row">
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">


				<?php if ($palettesDispo): ?>
					<?php foreach ($palettesDispo as $paletteNb => $details): ?>
						<div class="row">
							<div class="col">
								<div class="row bg-blue p-2 border border-white">
									<div class="col ">
										<div class="">Palette <?=$paletteNb?></div>
									</div>
									<div class="col-1">
										<div class="form-check">
											<input class="form-check-input checkbox-palette" type="checkbox" value="<?=$details[0]['id']?>" name="id_palette[]">
											<input  type="hidden" value="<?=$paletteNb?>" name="palette_nb[<?=$details[0]['id']?>]">


										</div>

									</div>
								</div>
								<div class="row detail">
									<div class="col-1"></div>
									<div class="col">
										<table class="table table-sm table-bordered">
											<thead class="thead-light">
												<tr>
													<th>Article</th>
													<th>EAN</th>
													<th>Désignation</th>
													<th class="text-right">PCB</th>

													<th class="text-right">Qte</th>
													<th class="text-right">Valo</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$sumValo=0;
												$sumQte=0;
												?>

												<?php foreach ($details as $key => $detail): ?>

													<tr>
														<td><?=$detail['article']?></td>
														<td><?=$detail['ean']?></td>
														<td><?=$detail['designation']?></td>
														<td class="text-right"><?=$detail['pcb']?></td>
														<td class="text-right"><?=$detail['nb_colis']?></td>
														<td class="text-right"><?=$detail['valo']?></td>
													</tr>
													<?php
													$sumQte+=$detail['nb_colis'];
													$sumValo+=$detail['valo'];
													?>
												<?php endforeach ?>
												<tr class="bg-light-blue font-weight-bold">
													<td colspan="4">TOTAUX</td>
													<td class="text-right"><?=$sumQte?></td>
													<td class="text-right"><?=$sumValo?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-1"></div>

								</div>


							</div>
						</div>
					<?php endforeach ?>
					<div class="row">
						<div class="col text-right">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="" id="check-all" name="check-all">
								<label class="form-check-label" for="check-all">Cocher/décocher tout</label>
							</div>

						</div>
					</div>
					<div class="row">
						<div class="col text-right">
							<button class="btn btn-primary" name="submit">Traiter</button>
						</div>
					</div>
				</form>

			<?php else: ?>
				<div class="alert alert-secondary">Aucune palette à traiter</div>
			<?php endif ?>
		</div>
	</div>


</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.detail').hide();
		$('.switch-input-detail').on("click", function(){
			if ( $('.switch-input-detail').prop("checked") ){
				$('.detail').show();
				$('#switch-descr-detail').text("Masquer les détails");
				sessionStorage.setItem("show-all", "true");
			}else{
				$('.detail').hide();
				$('#switch-descr-detail').text("Afficher les détails");
				sessionStorage.setItem("show-all", "false");
			}
		});



		$('#check-all').change(function(){
			if($(this).prop("checked")){
				$('.checkbox-palette').prop('checked',true);
			}else{
				$('.checkbox-palette').prop('checked',false);

			}
		})


	});

</script>
<?php
require '../view/_footer-bt.php';
?>