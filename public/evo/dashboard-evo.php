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


require "../../Class/EvoManager.php";
require "../../Class/EvoHelpers.php";





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




function getNewEvo($pdoEvo){
	$req=$pdoEvo->query("SELECT evos.*, plateforme, module, outil, id_web_user, CONCAT(prenom, ' ', nom) as ddeur FROM evos
		LEFT JOIN web_users.intern_users ON id_from= web_users.intern_users.id_web_user
		LEFT JOIN plateformes ON evos.id_plateforme=plateformes.id
		LEFT JOIN modules ON evos.id_module=modules.id
		LEFT JOIN outils ON evos.id_outils=outils.id
		WHERE id_etat=0 ORDER BY date_dde DESC");
	// $req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// return	$req->errorInfo();
}


$evoMgr=new EvoManager($pdoEvo);
$listResp=$evoMgr->getListResp();


$newEvo=$evoMgr->getListEvo(0,3);

echo count($newEvo);
echo date('d-m-Y', strtotime("2020-06-09 00:00:00 "));


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
		<div class="col">
			<form name="test" action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
				<fieldset>


					<div class="row">
						<div class="col-4 text-main-blue">
							Sélectionnez un salarié
						</div>
						<div class="col">
							<?php foreach ($listResp as $key => $resp): ?>

								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" value="<?=$resp['id']?>" id="resp" name="resp">
									<label class="form-check-label pr-5" for="resp"><?=$resp['resp']?></label>
								</div>

							<?php endforeach ?>
						</div>
					</div>


					<div class="row ">
						<div class="col-md-4 mt-3 pt-2 text-main-blue">
							Sélectionnez un outil :
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="outils"></label>
								<select class="form-control" name="outils" id="outils">
									<option value="">Sélectionner</option>
								</select>
							</div>

						</div>
					</div>
					<div class="row">
						<div class="col-md-4 mt-3 pt-2 text-main-blue">
							Sélectionnez un module :
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="module"></label>
								<select class="form-control" name="module" id="module">
									<option value="">Sélectionner</option>
								</select>
							</div>

						</div>
					</div>



					<div class="row">
						<div class="col pt-3 text-right">
							<button class="btn btn-cyan" name="send-rapport">Envoyer</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
	<div class="row mb-3">
		<div class="col-lg-1"></div>
		<div class="col sub">
			<h4 class="text-orange marvel mt-5"><i class="fas fa-check-double pr-4"></i>En attente de validation :</h4>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="mt-4"></div>




	<div class="row mt-3 mb-5">
		<div class="col-lg-1"></div>
		<div class="col">
			<!-- Evo en cours -->
			<table class="table shadow mt-4">
				<thead class="thead-dark">
					<tr>
						<th>N°</th>

						<th>Outils</th>
						<th>Module</th>
						<th>Objet</th>
						<th>Date demande</th>
						<th>Demandeur</th>
						<th>Détail</th>
						<th class="text-center">Statuer</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($newEvo as $current)
					{
						echo '<tr><td>'.$current['id'].'</td>';

						echo '<td>'.$current['outils'].'</td>';
						echo '<td>'.$current['module'].'</td>';
						echo '<td>'.$current['objet'].'</td>';
						echo '<td>'.$current['evo'].'</td>';
						echo '<td>'.$current['date_dde'].'</td>';
						echo '<td>'.$current['ddeur'].'</td>';
						echo '<td class="text-center"><a href="manage-evo.php?over='.$current['id'].'"><i class="fas fa-question-circle"></i></td></tr>';
					}
					?>
					<!-- $currentEvo=getCurrentEvo($pdoSav); -->
					<!-- $todoEvo=getTodoEvo($pdoSav); -->

				</tbody>
			</table>
			<!-- ./evo en cours -->
		</div>
		<div class="col-lg-1"></div>
	</div>

	<!-- ./row -->


	<!-- fin container -->
</div>

<script type="text/javascript">
	$(document).ready(function() {
// http://www.lisenme.com/dynamic-dependent-select-box-using-jquery-ajax-php/
//
$("input:radio[name='resp']").click(function () {
			// $("#outil").empty();


			var resp=$('input[name="resp"]:checked').val();
			console.log("resp" + resp);

			$.ajax({
				type:'POST',
				url:'ajax-get-evo.php',
				data:{id_resp:resp},
				success: function(html){
					$("#outils").html(html)
				}
			});
		});



$('#outils').on("change",function(){
	var outils=$('#outils').val();
	console.log("outils" + outils);
	$.ajax({
		type:'POST',
		url:'ajax-get-evo.php',
		data:{id_outils:outils},
		success: function(html){
			$("#module").html(html)
		}
	});
});






});








</script>

<?php
require '../view/_footer-bt.php';
?>