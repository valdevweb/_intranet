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
require "../../Class/EvoManager.php";
require "../../Class/EvoHelpers.php";
// require "../../functions/form.fn.php";





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


function statuer($pdoEvo){

	$req=$pdoEvo->prepare("UPDATE evos SET id_etat= :id_etat,cmt_dd= :cmt_dd,cmt_dev= :cmt_dev,date_validation= :date_validation, deadline= :deadline,id_prio= :id_prio WHERE id= :id");
	$req->execute([
		':id'		=>$_POST['id_evo'],
		':id_etat'		=>$_POST['statut'],
		':cmt_dd'		=>$_POST['cmt_dd'],
		":cmt_dev"		=>$_POST['cmt_dev'],
		':date_validation'	=>date('Y-m-d H:i:s'),
		':deadline'			=>!empty($_POST['deadline'])? $_POST['deadline'] : NULL,
		':id_prio'			=>$_POST['prio']

	]);
	return $req->errorInfo();
}


$evoMgr=new EvoManager($pdoEvo);
$listResp=$evoMgr->getListResp();
$listEtat=$evoMgr-> getListEtat();
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




$query="SELECT evos.*, plateforme, module, id_web_user, CONCAT(prenom, ' ', nom) as ddeur, appli FROM evos
LEFT JOIN web_users.intern_users ON id_from= web_users.intern_users.id_web_user
LEFT JOIN plateformes ON evos.id_plateforme=plateformes.id
LEFT JOIN modules ON evos.id_module=modules.id
LEFT JOIN appli ON evos.id_appli=appli.id
$params ORDER BY date_dde DESC";


$req=$pdoEvo->query($query);
$listEvo=$req->fetchAll(PDO::FETCH_ASSOC);



if(isset($_POST['statuer'])){
	include('dashboard-post-statuer.php');
}

if(isset($_POST['cloturer'])){
	include('dashboard-post-cloturer.php');
}


if(isset($_GET['start'])){
	$up=$evoMgr->startEvo($_GET['start'],3);

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
			<h1 class="text-main-blue py-5 text-center">Supervision des demandes d'évolution</h1>
		</div>
	</div>

	<!-- filtres -->
	<div class="row">
		<div class="col-xl-1"></div>
		<div class="col">
			<form name="filtrer-evo" action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
				<fieldset>

					<div class="row justify-content-center mb-4">
						<div class="col-xl-1"></div>

						<div class="col-xl-4 text-main-blue">
							Sélectionnez un statut
						</div>
						<div class="col-xl-7">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" <?= checkChecked(1,'etat')?> value="1" id="etat" name="etat">
								<label class="form-check-label pr-5" for="etat">En attente de validation</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio"  <?= checkChecked(2,'etat')?>  value="2" id="etat" name="etat">
								<label class="form-check-label pr-5" for="etat">Validées</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio"  <?= checkChecked(3,'etat')?>  value="3" id="etat" name="etat">
								<label class="form-check-label pr-5" for="etat">En cours</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio"  <?= checkChecked(4,'etat')?> value="4" id="etat" name="etat">
								<label class="form-check-label pr-5" for="etat">Terminées</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio"  <?= checkChecked(5,'etat')?> value="5" id="etat" name="etat">
								<label class="form-check-label pr-5" for="etat">Refusées</label>
							</div>
						</div>
					</div>


					<div class="row mb-4">
						<div class="col-xl-1"></div>
						<div class="col-xl-4 text-main-blue">
							Sélectionnez un salarié
						</div>
						<div class="col-xl-7">
							<?php foreach ($listResp as $key => $resp): ?>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" <?= checkChecked($resp['id'],'resp')?>   value="<?=$resp['id']?>" id="resp" name="resp">
									<label class="form-check-label pr-5" for="resp"><?=$resp['resp']?></label>
								</div>
							<?php endforeach ?>
						</div>
					</div>


					<div class="row">
						<div class="col-xl-1"></div>

						<div class="col-xl-4 mt-3 pt-2 text-main-blue">
							Sélectionnez une application :
						</div>
						<div class="col-xl-4">
							<div class="form-group">
								<label for="appli"></label>
								<select class="form-control" name="appli" id="appli">
									<option value="">Sélectionner</option>
									<?php if (isset($_POST['resp'])){
										$listAppli=$evoMgr->getListAppliResp($_POST['resp']);
									}
									elseif((isset($_SESSION['evo_filters']['resp']) && !empty($_SESSION['evo_filters']['resp']))){
										$listAppli=$evoMgr->getListAppliResp($_SESSION['evo_filters']['resp']);
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
				</div>
				<div class="row ">
					<div class="col-xl-1"></div>

					<div class="col-xl-4 mt-3 pt-2 text-main-blue">
						Sélectionnez un module :
					</div>
					<div class="col-xl-4">
						<div class="form-group">
							<label for="module"></label>
							<select class="form-control" name="module" id="module">
								<option value="">Veuillez sélectionner une application</option>
								<?php
								if (isset($_POST['appli'])){
									$listModule=$evoMgr->getListModule($_POST['appli']);
								}elseif((isset($_SESSION['evo_filters']['appli']) && !empty($_SESSION['evo_filters']['appli']))){
									$listModule=$evoMgr->getListModule($_SESSION['evo_filters']['appli']);
								}
								?>
								<?php if (isset($listModule) && !empty($listModule)): ?>
									<?php foreach ($listModule as $key => $module): ?>
										<option value="<?=$module['id']?>" <?=checkSelected($module['id'],'module')?>><?=$module['module']?></option>
									<?php endforeach ?>
								<?php endif ?>
						</select>
					</div>

				</div>
			</div>

			<div class="row justify-content-center">
				<div class="col-xl-9 pt-3 text-right">
					<button class="btn btn-primary" name="filtrer">Filtrer</button>
				</div>
			</div>
		</fieldset>
	</form>
</div>
<div class="col-xl-1"></div>

</div>
<div class="row mt-5">
	<div class="col-lg-1"></div>
	<div class="col">
		<?php
		include('../view/_errors.php');
		?>
	</div>
	<div class="col-lg-1"></div>
</div>

<div class="row mb-3">
	<div class="col-lg-1"></div>
	<div class="col sub">
		<h4 class="text-orange marvel mt-5"><i class="fas fa-check-double pr-4"></i>Listing des demandes d'évolution :</h4>
	</div>
	<div class="col-lg-1"></div>
</div>
<div class="mt-4"></div>




<div class="row mt-3 mb-5">
	<div class="col-lg-1"></div>
	<div class="col">
		<!-- Evo en cours -->
		<table class="table shadow mt-4" id="listing-evo">
			<thead class="thead-dark">
				<tr>
					<th>N°</th>

					<th>Appli</th>
					<th>Module</th>
					<th>Objet</th>
					<th>Détail</th>
					<th>Date demande</th>
					<th>Demandeur</th>
					<th class="text-center">Action</th>
				</tr>
			</thead>
			<tbody>

				<?php foreach ($listEvo as $evo):?>

					<tr>
						<td><?=$evo['id']?></td>

						<td><?=$evo['appli']?></td>
						<td><?=$evo['module']?></td>
						<td><?=$evo['objet']?></td>
						<td>
							<?=$evo['evo']?>
							<div class="text-right hide-btn" data-btn-id="<?=$evo['id']?>">
								<?php if (!empty($evo['cmt_dd']) || !empty($evo['cmt_dev'])): ?>
								<i class="fas fa-angle-double-down pr-2 align-text-bottom"></i>afficher/masquer les commentaires
							<?php endif ?>

						</div>
					</td>
					<td><?=$evo['date_dde']?></td>
					<td><?=$evo['ddeur']?></td>
					<td class="text-center">
						<?php if ($evo['id_etat']==1): ?>
							<a href="#modal-statuer" data-toggle="modal" data-prio="<?=$evo['id_prio']?>" data-id="<?=$evo['id']?>">
								<button class="btn btn-primary">Statuer</button>
							</a>
							<?php elseif($evo['id_etat']==2):?>
								<a href="?start=<?=$evo['id']?>" >
									<button class="btn btn-primary">Démarrer</button>
								</a>
								<?php elseif($evo['id_etat']==3):?>
									<a href="#modal-cloturer" data-toggle="modal" data-prio="<?=$evo['id_prio']?>" data-id="<?=$evo['id']?>">
										<button class="btn btn-primary">Cloturer</button>
									</a>
								<?php endif ?>

							</td>
						</tr>
						<?php if (!empty($evo['cmt_dd'])): ?>
							<tr class="bg-verylight-blue cmt" data-cmt-id="<?=$evo['id']?>">
								<td  colspan="4">Commentaire à l'intention du demandeur : </td>
								<td><?=$evo['cmt_dd']?></td>
								<td colspan="3"></td>
							</tr>
						<?php endif ?>

						<?php if (!empty($evo['cmt_dev'])): ?>
							<tr class="bg-verylight-blue cmt" data-cmt-id="<?=$evo['id']?>">
								<td colspan="4" >Commentaires à l'intension du développeur : </td>
								<td><?=$evo['cmt_dev']?></td>
								<td colspan="3"></td>
							</tr>
						<?php endif ?>
					<?php endforeach?>

					<!-- $currentEvo=getCurrentEvo($pdoSav); -->
					<!-- $todoEvo=getTodoEvo($pdoSav); -->

				</tbody>
			</table>
			<!-- ./evo en cours -->
		</div>
		<div class="col-lg-1"></div>
	</div>

	<?php
	include('dashboard-modal-statuer.php');
	include('dashboard-modal-cloturer.php');
	?>

	<!-- fin container -->
</div>

<script type="text/javascript">
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