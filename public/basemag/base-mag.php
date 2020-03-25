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


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';
require_once '../../Class/MagDbHelper.php';
require_once '../../Class/Mag.php';
require_once '../../Class/UserHelpers.php';



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

$magDbHelper=new MagDbHelper($pdoMag);
$listCentrale=$magDbHelper->getDistinctCentrale();
$listType=$magDbHelper->getListType();
$listCm=UserHelpers::getUserByService($pdoUser,17);
$listTypePair=$magDbHelper->getListTypePair();
$listCodeAcdlec=$magDbHelper->getListCodeAcdlec();





$iCentrale=0;
$newRowCentrale=3;


function checkChecked($value,$field){
	if(isset($_SESSION['mag_filters']) && isset($_SESSION['mag_filters'][$field])){
		if(in_array($value,$_SESSION['mag_filters'][$field])){
			return "checked";
		}
	}

	return "";
}


function arrayCentrale($listCentrale){
	foreach ($listCentrale as $key => $value) {
		$centrale[$value['id_centrale']]=$value['centrale_name'];
	}
	$centrale[0]="";
	return $centrale;
}

$centraleName=arrayCentrale($listCentrale);


//------------------------------------------------------
//			EXPLOIT
//------------------------------------------------------


//------------------------------------------------------
//			gestion de l'affichage par défaut
//------------------------------------------------------



if(isset($_POST['filter'])){

	if(isset($_POST['centraleSelected'])){
		$_SESSION['mag_filters']['centraleSelected']=$_POST['centraleSelected'];
		if(in_array(1,$_POST['centraleSelected'])){
			$paramCentrale='';
		}else{
			$paramCentrale=join(' OR ', array_map(function($value){return 'centrale='.$value;},$_POST['centraleSelected']));

		}
	}else{
		$_SESSION['mag_filters']['centraleSelected']=[];
		$paramCentrale='';
	}
	$paramList[]=$paramCentrale;

	if(isset($_POST['typeSelected'])){
		$_SESSION['mag_filters']['typeSelected']=$_POST['typeSelected'];
		$paramType=join(' OR ', array_map(function($value){return 'id_type='.$value;},$_POST['typeSelected']));

	}else{
		$_SESSION['mag_filters']['typeSelected']=[];
		$paramType='';
	}
	$paramList[]=$paramType;

	if(isset($_POST['acdlecSelected'])){
		$_SESSION['mag_filters']['acdlecSelected']=$_POST['acdlecSelected'];
		$paramAcdlec=join(' OR ', array_map(function($value){return 'acdlec_code='.$value;},$_POST['acdlecSelected']));

	}else{
		$_SESSION['mag_filters']['acdlecSelected']=[];
		$paramAcdlec='';
	}
	$paramList[]=$paramAcdlec;


	if(isset($_POST['closed'])){
		$_SESSION['mag_filters']['closed']=$_POST['closed'];
		$paramClosed=join(' OR ', array_map(function($value){return 'closed='.$value;},$_POST['closed']));

	}else{
		$_SESSION['mag_filters']['closed']=[];
		$paramClosed='';
	}
	$paramList[]=$paramClosed;

	if(isset($_POST['cmSelected'])){
		$_SESSION['mag_filters']['cmSelected']=$_POST['cmSelected'];
		$paramCm=join(' OR ', array_map(function($value){
			if($value=='NULL'){
				return 'id_cm_web_user IS '.$value;
			}
			return 'id_cm_web_user='.$value;
		},$_POST['cmSelected']));

	}else{
		$_SESSION['mag_filters']['cmSelected']=[];
		$paramCm='';

	}
	$paramList[]=$paramCm;
}

if(isset($_POST['clear_form'])){
	$_POST=[];
	header("Location: ".$_SERVER['PHP_SELF']);

}



if(isset($_POST['clear_filter'])){
	unset($_SESSION['mag_filters']);
	header('Location:'.$_SERVER['PHP_SELF']);
}


$joinParam=function($value){
	if(!empty($value)){
		return '('.$value.')';
	}
};
if(isset($paramList)){
	$paramList=array_filter($paramList);
	$params=join(' AND ',array_map($joinParam,$paramList));
	$params= "WHERE " .$params;
	$req=$pdoMag->query("SELECT * FROM mag LEFT JOIN sca3 ON mag.id=sca3.btlec_sca $params");
	$magList=$req->fetchAll(PDO::FETCH_ASSOC);
}

// echo "<pre>";
// print_r($magList);
// echo '</pre>';

if(!isset($_POST['filter'])){
	if(isset($_SESSION['mag_filters'])){
		unset($_SESSION['mag_filters']);

	}

	// sans filtre centrale
	$_SESSION['mag_filters']['centraleSelected'][]=1;
		// uniquement les établissements de type magasin
	$_SESSION['mag_filters']['typeSelected'][]=1;
	$_SESSION['mag_filters']['typeSelected'][]=3;
	$_SESSION['mag_filters']['acdlecSelected']=["010","029","070","078","101","102","111","114","116","118","119"];
	$sessionAcdlec=join(' OR ', array_map(function($value){return 'acdlec_code='.$value;},$_SESSION['mag_filters']['acdlecSelected']));
	// echo $sessionAcdlec;


		// uniquement les magasins  ouverts
	$_SESSION['mag_filters']['closed'][]=0;

	// $req=$pdoMag->query("SELECT * FROM mag ");
	$req=$pdoMag->query("SELECT * FROM mag LEFT JOIN sca3 ON mag.id=sca3.btlec_sca WHERE (id_type=1 OR id_type=3) AND closed=0 AND {$sessionAcdlec}");
	$magList=$req->fetchAll(PDO::FETCH_ASSOC);

}



$nbResult=count($magList);
$countItem=0;

	// echo "<pre>";
	// print_r($magList);
	// echo '</pre>';





// echo "<pre>";
// print_r($_SESSION);
// echo '</pre>';
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
			<h1 class="text-main-blue py-3 ">Base magasins</h1>
		</div>
		<?php
		include('search-form.php')
		?></div>
		<div class="row">
			<div class="col-lg-1"></div>
			<div class="col">
				<?php
				include('../view/_errors.php');
				?>
			</div>
			<div class="col-lg-1"></div>
		</div>

		<div class="row mx-3">
			<div class="col">
				<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
					<div class="row">
						<div class="col">
							<fieldset class="position-relative">
								<legend><i class="fas fa-filter pr-3"></i> Filtrer par :</legend>
							<!--
										FILTRE PAR CENTRALE
									-->
									<p class="rubrique text-main-blue font-weight-bold">Centrales :</p>
									<?php foreach ($listCentrale as $key => $centrale): ?>
										<?php if ($iCentrale==0): ?>
											<div class="form-row">
												<div class="col pl-5">
													<div class="form-check">
														<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="<?=$centrale['id_centrale']?>" id="centrale-<?=$centrale['id_centrale']?>" <?= checkChecked($centrale['id_centrale'],'centraleSelected')?>>
														<label for="centrale-<?=$centrale['id_centrale']?>" class="form-check-label"><?=ucfirst(strtolower($centrale['centrale_name']))?></label>
													</div>
												</div>
												<?php $iCentrale++ ?>

												<?php elseif ($iCentrale==3): ?>
													<div class="col">
														<div class="form-check">
															<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="<?=$centrale['id_centrale']?>" id="centrale-<?=$centrale['id_centrale']?>"  <?= checkChecked($centrale['id_centrale'],'centraleSelected')?>>
															<label for="centrale-<?=$centrale['id_centrale']?>" class="form-check-label"><?=ucfirst(strtolower($centrale['centrale_name']))?></label>
														</div>
													</div>
												</div>
												<?php $iCentrale=0 ?>
												<?php else: ?>
													<div class="col">
														<div class="form-check">
															<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="<?=$centrale['id_centrale']?>" id="centrale-<?=$centrale['id_centrale']?>"  <?= checkChecked($centrale['id_centrale'],'centraleSelected')?>>
															<label for="centrale-<?=$centrale['id_centrale']?>" class="form-check-label"><?=ucfirst(strtolower($centrale['centrale_name']))?></label>
														</div>
													</div>
													<?php $iCentrale++ ?>
												<?php endif ?>
											<?php endforeach ?>
											<!-- fermeture div quand par col 4 -->
											<?= ($iCentrale!=0 )? "</div>" : ""?>
											<div class="form-row">
												<div class="col pl-5">
													<div class="form-check">
														<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="0" id="centrale-0?>"  <?= checkChecked(0,'centraleSelected')?>>
														<label for="centrale-0" class="form-check-label">Pas de centrale </label>
													</div>
												</div>
												<div class="col">
													<div class="form-check">
														<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="1" id="centrale-1?>"  <?= checkChecked(1,'centraleSelected')?>>
														<label for="centrale-1" class="form-check-label">Sans filtre centrale</label>
													</div>
												</div>
												<div class="col"></div>
												<div class="col"></div>
											</div>
											<!--										FILTRE PAR TYPE									-->
											<div class="form-row my-3">
												<div class="col-3">
													<p class="rubrique text-main-blue font-weight-bold">Type d'établissement :</p>
													<?php foreach ($listType as $key => $type): ?>
														<div class="form-check pl-5">
															<input type="checkbox" class="form-check-input" name="typeSelected[]" value="<?=$type['id']?>" <?= checkChecked($type['id'],'typeSelected')?>>
															<label class="form-check-label"><?=$type['type']?></label>
														</div>
													<?php endforeach ?>
												</div>
												<div class="col-6">
													<p class="rubrique text-main-blue font-weight-bold">Code Acdlec</p>
													<div class="row">

														<div class="col">

															<div class="form-check pl-5">
																<input type="radio" class="form-check-input" name="check_code" id="check-all-code">
																<label class="form-check-label" for="check-all-code">Cocher tout</label>
															</div>
														</div>
														<div class="col">
															<div class="form-check pl-5">
																<input type="radio" class="form-check-input" name="check_code" id="uncheck-code">
																<label class="form-check-label" for="uncheck-code">Décocher tout</label>
															</div>
														</div>
													</div>

													<div class="row">
														<div class="col">

															<?php foreach ($listCodeAcdlec as $code): ?>
																<?php if (!empty($code['acdlec_code'])): ?>
																	<?php
																	if ($countItem==4){
																		echo '</div><div class="col">';
																		$countItem=0;
																	}
																	?>
																	<div class="form-check pl-5">
																		<input type="checkbox" class="form-check-input acdlec" name="acdlecSelected[]" value="<?=$code['acdlec_code']?>" <?= checkChecked($code['acdlec_code'],'acdlecSelected')?>>
																		<label class="form-check-label"><?=$code['acdlec_code']?></label>
																	</div>
																	<?php $countItem++; ?>
																<?php endif ?>
															<?php endforeach ?>
														</div>
													</div>
												</div>


												<!--					FILTRE PAR ETAT				-->
												<div class="col-3">
													<p class="rubrique text-main-blue font-weight-bold">Ouvert/fermé :</p>
													<div class="form-check pl-5">
														<input type="checkbox" class="form-check-input" name="closed[]" value="0" <?= checkChecked(0,'closed')?>>
														<label class="form-check-label">Ouvert</label>
													</div>
													<div class="form-check pl-5">
														<input type="checkbox" class="form-check-input" name="closed[]" value="1" <?= checkChecked(1,'closed')?>>
														<label class="form-check-label">Fermé</label>
													</div>
												</div>
											</div>





											<div class="row">
												<!--					FILTRE PAR CM				-->
												<div class="col">
													<p class="rubrique text-main-blue font-weight-bold">Suivi par :</p>
													<?php foreach ($listCm as $key => $cm): ?>
														<div class="form-check pl-5">
															<input type="checkbox" class="form-check-input" name="cmSelected[]" value="<?=$cm['id_web_user']?>" <?= checkChecked($cm['id_web_user'],'cmSelected')?>>
															<label class="form-check-label"><?=$cm['fullname']?></label>
														</div>
													<?php endforeach ?>
													<div class="form-check pl-5">
														<input type="checkbox" class="form-check-input" name="cmSelected[]" value="NULL" <?= checkChecked('NULL','cmSelected')?>>
														<label class="form-check-label">Non suivi</label>
													</div>
												</div>
											</div>



											<div class="form-row">
												<div class="col text-right">
													<button class="btn btn-orange" name="clear_filter">Réinitialiser les filtres</button>
													<button class="btn btn-primary" name="filter">Filtrer</button>

												</div>
											</div>
										</fieldset>
									</div>
								</div>


							</form>

						</div>


					</div>

					<div class="row">
						<div class="col">
							<h5 class="text-main-blue text-center pt-5 pb-3">Nombre de magasins affichés : <?=$nbResult?></h5>
							<div class="alert alert-primary">Pour obtenir plus d'information sur un magasin, veuillez cliquer sur son nom</div>
							<table class="table table-sm shadow">
								<thead class="thead-dark">
									<tr>
										<th>Btlec</th>
										<th>Deno</th>
										<th>Galec</th>
										<th>Ville</th>
										<th>code acdlec</th>
										<th>Type Ets</th>
										<th>Centrale</th>
										<th>Chargé de mission</th>
									</tr>
								</thead>
								<tbody>
									<?php if (isset($magList)): ?>
										<?php foreach ($magList as $key => $mag): ?>
											<tr>
												<td><?=$mag['id']?></td>
												<td><a href="fiche-mag.php?id=<?=$mag['id']?>"><?=$mag['deno']?></a></td>
												<td><?=$mag['galec']?></td>
												<td><?=$mag['cp'] .' '.$mag['ville']?></td>
												<td><?=$mag['acdlec_code']?></td>
												<td><?=$listTypePair[$mag['id_type']] ?></td>
												<td><?=isset($centraleName[$mag['centrale']])?$centraleName[$mag['centrale']]:"" ?></td>
												<td><?= UserHelpers::getFullname($pdoUser, $mag['id_cm_web_user'])?></td>
											</tr>
										<?php endforeach ?>

									<?php endif ?>

								</tbody>
							</table>

						</div>
					</div>





					<!-- ./container -->
				</div>
				<script type="text/javascript">
					$(document).ready(function(){
						$('#search_term').keyup(function(){
							var path = window.location.pathname;

							var page = 'fiche-mag.php';

							var query = $(this).val()+"#"+page;
							if(query != '')
							{
								$.ajax({
									url:"ajax-search-mag.php",
									method:"POST",
									data:{query:query},
									success:function(data)
									{
										$('#magList').fadeIn();
										$('#magList').html(data);
									}
								});
							}
						});
						$(document).on('click', 'li', function(){
							$('#search_term').val($(this).text());
							$('#magList').fadeOut();
						});

						$("#check-all-code").click(function () {
							$('.acdlec').prop('checked', this.checked);

						});
						$("#uncheck-code").click(function () {
							$('.acdlec').removeAttr('checked');

						});




					});


				</script>
				<?php
				require '../view/_footer-bt.php';
				?>