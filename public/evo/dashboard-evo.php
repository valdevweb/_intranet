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

$paramList=[];
if(isset($_POST['etat'])  && !empty($_POST['etat'])){
	$_SESSION['evo_filters']['etat']=$_POST['etat'];
	$paramEtat='id_etat='.$_SESSION['evo_filters']['etat'];

}else{
	$_SESSION['evo_filters']['etat']='';
	$paramEtat='';
}


if(isset($_POST['resp'])  && !empty($_POST['resp'])){
	$_SESSION['evo_filters']['resp']=$_POST['resp'];
	$paramResp='evos.id_resp='.$_SESSION['evo_filters']['resp'];

}else{
	$_SESSION['evo_filters']['resp']='';
	$paramResp='';

}

if(isset($_POST['appli']) && !empty($_POST['appli'])){
	$_SESSION['evo_filters']['appli']=$_POST['appli'];
	$paramAppli='evos.id_appli='.$_SESSION['evo_filters']['appli'];

}else{
	$_SESSION['evo_filters']['appli']='';
	$paramAppli='';
}

if(isset($_POST['module']) && !empty($_POST['module'])){
	$_SESSION['evo_filters']['module']=$_POST['module'];
	$paramModule='id_module='.$_SESSION['evo_filters']['module'];

}else{
	$_SESSION['evo_filters']['module']='';
	$paramModule='';
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
	$err=statuer($pdoEvo);
	//besoin de récupérer les infos de la demande d'évo
	$idEvo=$_POST['id_evo'];
	$thisEvo=$evoMgr->getThisEvo($idEvo);
	//  envoi mail dev et demandeur
	if(VERSION=="_"){
		$destDev=['valerie.montusclat@btlec.fr'];
		$destDd=['valerie.montusclat@btlec.fr'];
		$cc=[];
		$hidden=[];
	}else{
		$destDev[]=$thisEvo['dev_mail'];
		$destDd[]=$thisEvo['dev_dd'];
		$cc=[];
		$hidden=['valerie.montusclat@btlec.fr'];
	}
	$arrDecision=[2 =>"validée", 5=>"refusée"];
	$decision=$arrDecision[$_POST['statut']];

// ---------------------------------------
		// MAIL  developpeur

// ---------------------------------------
	$htmlMail = file_get_contents('mail-decision-dev.html');
	$htmlMail=str_replace('{OBJET}',$thisEvo['objet'],$htmlMail);
	$htmlMail=str_replace('{DECISION}',$decision,$htmlMail);
	$htmlMail=str_replace('{CMTDEV}',$thisEvo['cmt_dev'],$htmlMail);
	$htmlMail=str_replace('{EVO}',$thisEvo['evo'],$htmlMail);
	$subject="Portail SAV Leclerc - Demandes d'évo " ;

// ---------------------------------------
	$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
	$mailer = new Swift_Mailer($transport);
	$message = (new Swift_Message($subject))
	->setBody($htmlMail, 'text/html')
	->setFrom(array('ne_pas_repondre@btlec.fr' => 'EXPEDITEUR NAME'))
	->setTo($destDev)
	->setCc($cc)
	->setBcc($hidden);

	if (!$mailer->send($message, $failures)){
		print_r($failures);
	}else{
		$success[]="mail envoyé avec succés";
	}





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
									<?php if (isset($_POST['resp'])): ?>
										<?php $listAppli=$evoMgr->getListAppliResp($_POST['resp']) ;?>
										<?php if (!empty($listAppli)): ?>
											<?php foreach ($listAppli as $key => $appli): ?>
												<option value="<?=$appli['id']?>" <?=checkSelected($appli['id'],'appli')?>><?=$appli['appli']?></option>
											<?php endforeach ?>
										<?php endif ?>
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
									<?php if (isset($_POST['appli'])): ?>
										<?php $listModule=$evoMgr->getListModule($_POST['appli']) ;?>
										<?php if (!empty($listModule)): ?>
											<?php foreach ($listModule as $key => $module): ?>
												<option value="<?=$module['id']?>" <?=checkSelected($module['id'],'module')?>><?=$module['module']?></option>
											<?php endforeach ?>
										<?php endif ?>
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
						<th>Date demande</th>
						<th>Demandeur</th>
						<th>Détail</th>
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
						<tr class="bg-verylight-orange cmt" data-cmt-id="<?=$evo['id']?>">
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

<!-- ./row -->
<div class="modal fade" id="modal-statuer" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-main-blue">
				<h5 class="modal-title text-white" id="myModalLabel">Objet : <span id="objet"></span></h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
					<input type="hidden" name="id_evo" id="id_evo" >
					<div class="row">
						<div class="col">

						</div>
					</div>
					<div class="row">
						<div class="col">
							Accepter ou réfuser la demande :
						</div>
					</div>
					<div class="row py-3">
						<div class="col">
							<div class="form-check form-check-inline">
								<input class="form-check-input faible" type="radio" value="2" id="statut" name="statut">
								<label class="form-check-label font-weight-bold text-green" for="statut">Valider</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input urgent" type="radio" value="5" id="statut" name="statut">
								<label class="form-check-label font-weight-bold text-red" for="statut">Refuser</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="cmt_dd">Commentaires pour le demandeur : </label>
								<textarea class="form-control" name="cmt_dd" id="cmt_dd" row="3"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="cmt_dev">Commentaires pour le développeur :</label>
								<textarea class="form-control" name="cmt_dev" id="cmt_dev" row="3"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							Modification de la priorité :
						</div>
					</div>
					<div class="row mb-3">
						<div class="col">
							<div class="form-check form-check-inline">
								<input class="form-check-input urgent" type="radio" value="1"  name="prio" required>
								<label class="form-check-label pr-5 text-red" for="urgent"><b>urgent</b></label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input normal" type="radio" value="2"  name="prio">
								<label class="form-check-label pr-5 text-main-blue" for="normal"><b>normal</b></label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input faible" type="radio" value="3"  name="prio">
								<label class="form-check-label pr-5 text-green" for="faible"><b>faible</b></label>
							</div>
						</div>
					</div>
					<div class="row">

					</div>
					<div class="row">
						<div class="col-auto">
							Imposer une deadline :

						</div>
						<div class="col-md-6 col-xl-3">

							<div class="form-group">
								<input type="date" class="form-control" name="deadline" id="deadline">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col text-right">
							<button class="btn btn-primary" name="statuer">Envoyer</button>
						</div>
					</div>
				</form>


			</div>
				<!-- <div class="modal-footer">
					<button type="button" class="btn btn-black" data-dismiss="modal">Fermer</button>
				</div> -->
			</div>
		</div>
	</div>

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

			// (){
			// 	$('.acdlec').prop('checked', this.checked);
			// }
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