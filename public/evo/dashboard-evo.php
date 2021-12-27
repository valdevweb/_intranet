<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";



//----------------------------------------------------------------
//		INCLUDES
//----------------------------------------------------------------

require_once  '../../vendor/autoload.php';
require "../../Class/evo/EvoDao.php";
require "../../Class/evo/PlanningDao.php";
require "../../Class/evo/AppliDao.php";
require "../../Class/evo/ModuleDao.php";
require "../../Class/evo/EvoHelpers.php";
require '../../Class/evo/AffectationDao.php';





//----------------------------------------------
// css dynamique
//----------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile="../css/".$pageCss.".css";



//----------------------------------------------
//  		FUNCTIONS
//----------------------------------------------


$success=[];
$errors=[];

function checkChecked($value,$field){
	if(isset($_SESSION['evo_filters']) && isset($_SESSION['evo_filters'][$field])){
		if($value==$_SESSION['evo_filters'][$field]){

			return "checked";
		}
	}
	return "";
}

function checkSelected($value,$field){
	if(isset($_SESSION['evo_filters']) && isset($_SESSION['evo_filters'][$field])){
		if($value==$_SESSION['evo_filters'][$field]){

			return "selected";
		}
	}
	return "";
}

function getNewEvo($pdoEvo){
	$req=$pdoEvo->query("SELECT evos.*, plateforme, module, outil, id_web_user, CONCAT(prenom, ' ', nom) as ddeur FROM evos
		LEFT JOIN web_users.intern_users ON id_from= web_users.intern_users.id_web_user
		LEFT JOIN plateformes ON evos.id_plateforme=plateformes.id
		LEFT JOIN modules ON evos.id_module=modules.id
		LEFT JOIN appli ON evos.id_appli=appli.id
		WHERE id_etat=0 ORDER BY date_dde DESC");
	// $req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// return	$req->errorInfo();
}




$evoDao=new EvoDao($pdoEvo);
$appliDao=new AppliDao($pdoEvo);
$moduleDao=new ModuleDao($pdoEvo);
$planningDao=new PlanningDao($pdoEvo);
$affectationDao= new AffectationDao($pdoEvo);

$listResp=$evoDao->getListResp();
$listEtat=$evoDao-> getListEtat();


$arrDevMail=EvoHelpers::arrayAppliRespEmail($pdoEvo);



$paramList=[];
if(isset($_POST['etat'])  && !empty($_POST['etat'])){
	$_SESSION['evo_filters']['etat']=$_POST['etat'];
	$paramEtat='id_etat='.$_SESSION['evo_filters']['etat'];
}elseif(!isset($_SESSION['evo_filters']['etat']) || empty($_SESSION['evo_filters']['etat'])){
	$paramEtat='';
}else{
	$paramEtat='id_etat='.$_SESSION['evo_filters']['etat'];
}


if(isset($_POST['resp'])  && !empty($_POST['resp'])){
	$_SESSION['evo_filters']['resp']=$_POST['resp'];
	$paramResp='evos.id_resp='.$_SESSION['evo_filters']['resp'];
}elseif(!isset($_SESSION['evo_filters']['resp']) || empty($_SESSION['evo_filters']['resp'])){
	$paramResp='';
}else{
	$paramResp='evos.id_resp='.$_SESSION['evo_filters']['resp'];
}


// on a sélectionné une appli
if(isset($_POST['appli']) && !empty($_POST['appli'])){
	$_SESSION['evo_filters']['appli']=$_POST['appli'];
	$paramAppli='evos.id_appli='.$_SESSION['evo_filters']['appli'];
}
elseif( (isset($_POST['appli']) && empty($_POST['appli'])) || !isset($_SESSION['evo_filters']['appli']) || empty($_SESSION['evo_filters']['appli'])){
	unset($_SESSION['evo_filters']['appli']);
	$paramAppli='';
}else{
	$paramAppli='';
	$paramAppli='evos.id_appli='.$_SESSION['evo_filters']['appli'];
}

if(isset($_POST['module']) && !empty($_POST['module'])){
	$_SESSION['evo_filters']['module']=$_POST['module'];
	$paramModule='id_module='.$_SESSION['evo_filters']['module'];
}elseif((isset($_POST['module']) && empty($_POST['module'])) || !isset($_SESSION['evo_filters']['module']) || empty($_SESSION['evo_filters']['module']) ){
	unset($_SESSION['evo_filters']['module']);
	$paramModule='';
}else{
	$paramModule='id_module='.$_SESSION['evo_filters']['module'];
}

$paramList[]=$paramEtat;
$paramList[]=$paramResp;
$paramList[]=$paramAppli;
$paramList[]=$paramModule;

    // retire les élements videq du tableau
$paramList=array_filter($paramList);
if(!empty($paramList)){
	$params=join(' AND ',$paramList);
	$params= "WHERE " .$params;
}else{
	$_SESSION['evo_filters']['etat']=1;
	$params= " WHERE id_etat=1 " ;
}

$listPlanning=[];
if(isset($_SESSION['evo_filters']['etat'])){
	$listPlanning=$planningDao->getPlanningByEvo($_SESSION['evo_filters']['etat']);
}

$query="SELECT evos.*, plateforme, module, id_web_user, CONCAT(prenom, ' ', nom) as ddeur, appli FROM evos
LEFT JOIN web_users.intern_users ON id_from= web_users.intern_users.id_web_user
LEFT JOIN plateformes ON evos.id_plateforme=plateformes.id
LEFT JOIN modules ON evos.id_module=modules.id
LEFT JOIN appli ON evos.id_appli=appli.id
$params ORDER BY id_plateforme, id_appli, id_module DESC";


$req=$pdoEvo->query($query);
$listEvo=$req->fetchAll(PDO::FETCH_ASSOC);



if(isset($_POST['statuer'])){
	include('dashboard-evo\01-post-statuer.php');
}

if(isset($_POST['cloturer'])){
	include('dashboard-evo\02-post-cloturer.php');
}


if(isset($_GET['start'])){
	$up=$evoDao->startEvo($_GET['start'],3);

	header("Location: ".$_SERVER['PHP_SELF'],true,303);
}





if(isset($_GET['success'])){
	$arrSuccess=[
		'decision'=>'Envoi de la décision au demandeur et au développeur fait avec succès',
		'over'=>'Demande clôturée',
	];
	$success[]=$arrSuccess[$_GET['success']];
}



include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container-fluid bg-white">
	<!-- main title -->
	<div class="row">
		<div class="col">
			<h1 class="text-main-blue pt-5 pb-3 text-center">Supervision des demandes d'évolution</h1>
		</div>
	</div>

	<!-- filtres -->
	<div class="row">
		<div class="col-xl-2"></div>
		<div class="col">
			<form name="filtrer-evo" action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
				<fieldset>
					<div class="row justify-content-center mb-2">

						<div class="col-auto">
							Salarié :
							<?php foreach ($listResp as $key => $resp): ?>
								<div class="form-check">
									<input class="form-check-input" type="radio" <?= checkChecked($resp['id'],'resp')?>   value="<?=$resp['id']?>" id="resp" name="resp">
									<label class="form-check-label pr-5" for="resp"><?=$resp['resp']?></label>
								</div>
							<?php endforeach ?>
						</div>
						<div class="col-auto">
							Statut :

							<?php foreach ($listEtat as $key => $etat): ?>
								<div class="form-check">
									<input class="form-check-input" type="radio" <?= checkChecked($etat['id'],'etat')?> value="<?=$etat['id']?>" id="etat" name="etat">
									<label class="form-check-label pr-5" for="etat"><?=$etat['etat']?></label>
								</div>
							<?php endforeach ?>
						</div>


						<div class="col">
							<div class="form-group">
								<label for="appli">Application :</label>
								<select class="form-control" name="appli" id="appli">
									<option value="">Sélectionner</option>
									<?php if (isset($_POST['resp'])){
										$listAppli=$appliDao->getListAppliResp($_POST['resp']);
									}
									elseif((isset($_SESSION['evo_filters']['resp']) && !empty($_SESSION['evo_filters']['resp']))){
										$listAppli=$appliDao->getListAppliResp($_SESSION['evo_filters']['resp']);
									}
									?>


									<?php if (isset($listAppli) && !empty($listAppli)): ?>
									<?php foreach ($listAppli as $key => $appli): ?>
										<option value="<?=$appli['id']?>" <?=checkSelected($appli['id'],'appli')?>><?=$appli['appli']?></option>
									<?php endforeach ?>
								<?php endif ?>

							</select>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="module">Module</label>
							<select class="form-control" name="module" id="module">
								<option value="">Veuillez sélectionner une application</option>
								<?php
								if (isset($_POST['appli'])){
									$listModule=$moduleDao->getListModule($_POST['appli']);
								}elseif((isset($_SESSION['evo_filters']['appli']) && !empty($_SESSION['evo_filters']['appli']))){
									$listModule=$moduleDao->getListModule($_SESSION['evo_filters']['appli']);
								}
								?>
								<?php if (isset($listModule) && !empty($listModule)): ?>
								<?php foreach ($listModule as $key => $module): ?>
									<option value="<?=$module['id']?>" <?=checkSelected($module['id'],'module')?>><?=$module['module']?></option>
								<?php endforeach ?>
							<?php endif ?>
						</select>
					</div>

					<div class="text-right mt-3"><button class="btn btn-primary" name="filtrer">Filtrer</button></div>
				</div>
			</div>

		</fieldset>
	</form>
</div>
<div class="col-xl-2"></div>
</div>


<div class="row mt-5">
	<div class="col-lg-2"></div>
	<div class="col">
		<?php
		include('../view/_errors.php');
		?>
	</div>
	<div class="col-lg-2"></div>
</div>

<div class="row mb-3">
	<div class="col-xl-2"></div>
	<div class="col sub">
		<h4 class="text-orange marvel mt-3"><i class="fas fa-check-double pr-4"></i>Demandes d'évolution :</h4>
	</div>

	<div class="col-xl-1"></div>
</div>


<div class="row">
	<div class="col-lg-1"></div>
	<div class="col"></div>
	<div class="col-auto font-weight-boldless text-main-blue">
		Rechercher  :
	</div>
	<div class="col-2">

		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" id="search_form">
			<div class="form-group text-center">
				<input type="text" class="form-control" name="str" id="str"  style="font-family:'Font Awesome 5 Free',sans-serif !important; font-weight: 900 !important;" type="text" placeholder="&#xf002">
			</div>
		</form>
	</div>
	<div class="col-lg-2"></div>

</div>


<div class="row mt-3 mb-5">
	<div class="col-lg-1 col-xl-2"></div>
	<div class="col">
		<?php foreach ($listEvo as $evo):?>
			<div class="row mb-3">
				<div class="col  rounded">
					<div class="row py-2  border-dark bg-light-grey">
						<div class="col-lg-3">
							Application :
						</div>
						<div class="col-lg-4">
							Module :
						</div>

						<div class="col-lg-3 ">
							<i class="fas fa-user text-orange pr-3"></i><?=$evo['ddeur']?>
						</div>
						<div class="col-lg-2 text-right">
							<div class="row">
								<div class="col">
									<i class="fas fa-calendar-alt text-orange pr-3"></i><?=date('d-m-Y', strtotime($evo['date_dde']))?>
								</div>
							</div>
						</div>
					</div>
					<div class="row bg-light-grey border-bottom border-dark">
						<div class="col-lg-3">
							<div class="badge badge-btlec"><?=$evo['appli']?></div>

						</div>
						<div class="col-lg-4">
							<div class="badge badge-btlec"><?=$evo['module']?></div>
						</div>

						<div class="col text-right">
							<?php if (isset($listPlanning[$evo['id']])): ?>
								Développement du
								<?php foreach ($listPlanning[$evo['id']] as $key => $planning): ?>
									<span class="badge badge-success"><?=date('d-m-Y', strtotime($planning['date_start'])) . '</span> au <span class="badge badge-success">'.date('d-m-Y', strtotime($planning['date_end']))?></span><br>
								<?php endforeach ?>
							<?php endif ?>
						</div>
					</div>


					<div class="row text-orange">
						<div class="col my-2 ">
							<h6><a href="evo-detail.php?id=<?=$evo['id']?>" class="link-orange">Objet : <?=$evo['objet']?></a></h6>
						</div>
						<div class="col-1 text-right"><h6>#<?=$evo['id']?></h6></div>
					</div>



					<div class="row">
						<div class="col-lg-10">
							<div class="row">
								<div class="col">
									<div class="alert alert-secondary"><?=nl2br($evo['evo'])?></div>
								</div>
							</div>
							<?php if (!empty($evo['cmt_dd'])): ?>

								<div class="row">
									<div class="col">
										Commentaire à l'intention du demandeur :

									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="alert alert-light"><?=nl2br($evo['cmt_dd'])?></div>
									</div>
								</div>
							<?php endif ?>

							<?php if (!empty($evo['cmt_dev'])): ?>
								<div class="row">
									<div class="col">
										Commentaires à l'intension du développeur :
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="alert alert-light"><?=nl2br($evo['cmt_dev'])?></div>
									</div>
								</div>
							<?php endif ?>
						</div>
						<div class="col-lg-2 align-self-end ">
							<?php if ($evo['id_etat']==1): ?>
								<div class="text-center pb-3">
									<a href="#modal-statuer" data-toggle="modal" data-prio="<?=$evo['id_prio']?>" data-id="<?=$evo['id']?>">
										<button class="btn btn-primary">Statuer</button>
									</a>
								</div>

							<?php elseif($evo['id_etat']==2):?>
								<div class="text-center  pb-3">
									<a href="#modal-cloturer" data-toggle="modal" data-prio="<?=$evo['id_prio']?>" data-id="<?=$evo['id']?>">
										<button class="btn btn-primary">Cloturer</button>
									</a>
								</div>
							<?php endif ?>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach?>
	</div>
	<div class="col-lg-1 col-xl-2"></div>
</div>
</div>
<?php
include('dashboard-evo\10-modal-statuer.php');
include('dashboard-evo\11-modal-cloturer.php');
?>

<!-- fin container -->
</div>
<script src="../js/search-in-window.js"></script>
<script type="text/javascript">
	document.getElementById('search_form').onsubmit = function() {
		findString(this.str.value);
		return false;
	};
	$(document).ready(function() {
		$('#appli').on("change",function(){
			$(this).closest("form").submit();
		});
		$('#module').on("change",function(){
			$(this).closest("form").submit();
		});

		// $('input[type=radio]').on('change', function() {
			$('input[name=etat]').on('change', function() {
				$(this).closest("form").submit();
			});

			$('input[name=resp]').on('change', function() {
				$(this).closest("form").submit();
			});

			$('#modal-statuer').on('show.bs.modal', function (e) {
				var idevo = $(e.relatedTarget).data('id');
				var prio = $(e.relatedTarget).data('prio');
				$("input[name=prio][value=" + prio + "]").attr('checked', 'checked');
				var hiddenidevo=$('#id_evo');
				hiddenidevo.val(idevo)
				$.ajax({
					type:'POST',
					url:'ajax-getthis-evo.php',
					data:{id_evo:idevo},
					success: function(html){
						$("#objet").html(html)
					}
				});
			});

			$('#cmt_dd').on("keyup", function(){
				var cmt=$('#cmt_dd').val();
				$('#cmt_dev').val(cmt);
			});
			$('#modal-cloturer').on('show.bs.modal', function (e) {
				var idevo = $(e.relatedTarget).data('id');
				var hiddenidevo=$('#id_evo_cloture');

				hiddenidevo.val(idevo)
				$.ajax({
					type:'POST',
					url:'ajax-getthis-evo.php',
					data:{id_evo:idevo},
					success: function(html){
						$("#objet_cloture").html(html)
					}
				});
			});


			$('#deadline').on("change", function(){
				var today = new Date();
				var dateSelected=$('#deadline').val();
				var dateSelected=new Date(dateSelected);
				if(dateSelected<today){
					console.log("inf");
					$('#error-msg').append('<div class="alert alert-danger">La deadline est inférieure à la date du jour</div>');
					$('button[type=submit]').prop('disabled', true);

				}else{
					console.log("sup");

					$('#error-msg').empty();
					$('button[type=submit]').prop('disabled', false);

				}

			})
			$('tr.cmt').hide();
			$('.hide-btn').on("click", function(){
				var id= $(this).data("btn-id");
				if($('tr[data-cmt-id="'+id+'"]').is(":visible")){
					$('tr[data-cmt-id="'+id+'"]').hide();

				}else{
					$('tr[data-cmt-id="'+id+'"]').show();
				}
			});


		});


	</script>

	<?php
	require '../view/_footer-bt.php';
?>