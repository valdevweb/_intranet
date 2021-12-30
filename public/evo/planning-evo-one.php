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
require '../../Class/evo/PlanningDao.php';
require '../../Class/evo/EvoDao.php';
require '../../Class/DateHelpers.php';
require '../../Class/UserHelpers.php';
// require_once '../../vendor/autoload.php';

function getIsoWeeksInYear($year) {
	$date = new DateTime;
	$date->setISODate($year, 53);
	return ($date->format("W") === "53" ? 53 : 52);
}
function getStartAndEndDate($week, $year) {
	$dto = new DateTime();
	$dto->setISODate($year, $week);
	$ret['monday'] = $dto->format('Y-m-d');
	$dto->modify('+6 days');
	$ret['sunday'] = $dto->format('Y-m-d');
	return $ret;
}



$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoEvo=$db->getPdo('evo');


$planningDao=new PlanningDao($pdoEvo);
$evoDao=new EvoDao($pdoEvo);
$listResp=$evoDao->getListResp();



$countColUn=1;
$countColDeux=1;



if(isset($_GET['id'])){
	$idwebuser=$_GET['id'];
}else{
	$idwebuser=$_SESSION['id_web_user'];
}

$fullPlanning= $planningDao->getPlanningEvoDev($idwebuser);
$fullname=UserHelpers::getFullnameIdwebuser($pdoUser,$idwebuser);

$validatedNotPlanned=$evoDao->getEvoNoPlanning($idwebuser,2);
$notValidatedNotPlanned=$evoDao->getEvoNoPlanning($idwebuser,1);




$thisMonth= (new DateTime())->format('n');
if ($thisMonth<=6) {
	$yearOne=(new DateTime())->format('Y');
	$weekOneStart=1;
	$weekOneEnd=(new DateTime($yearOne. '-06-30'))->format("W");
	$yearTwo=$yearOne;
	$weekTwoStart=$weekOneEnd+1;
	$weekTwoEnd=getIsoWeeksInYear($yearOne);


}else{

	$yearOne=(new DateTime())->format('Y');
	$weekOneStart=(new DateTime($yearOne. '-07-01'))->format("W");
	$weekOneEnd=getIsoWeeksInYear($yearOne);
	$yearTwo=$yearOne+1;
	$weekTwoStart=new DateTime();
	$weekTwoStart=($weekTwoStart->setISODate($yearTwo, 1))->format("W");
	$weekTwoEnd=(new DateTime($yearTwo. '-06-30'))->format("W");
}

for ($i=$weekOneStart; $i <=$weekOneEnd; $i++) {
	$date = (new DateTime())->setISODate($yearOne,$i);
	if($date->format('n')<$weekOneStart=(new DateTime($yearOne. '-07-01'))->format('n')){
		$firstSemester[$date->format('n')+1][$date->format('W')]=$date->format('d-M-Y');
	}else{

		$firstSemester[$date->format('n')][$date->format('W')]=$date->format('d-M-Y');
		// $javaWeeks[]=$date->format('n');

	}
	$javaWeeks[]=$date->format('W');

}

for ($i=$weekTwoStart; $i <=$weekTwoEnd; $i++) {
	$date = (new DateTime())->setISODate($yearTwo,$i);
	$secondSemester[$date->format('n')][$date->format('W')]=$date->format('d-M-Y');
	$javaWeeks[]=$date->format('W');

}
$byWeek=[];
foreach ($fullPlanning as $key => $planning) {
	$wStart=(new DateTime($planning['date_start']))->format('W');
	$wEnd=(new DateTime($planning['date_end']))->format('W');
	for ($w=$wStart; $w <=$wEnd ; $w++) {
		$year=(new DateTime($planning['date_start']))->format('Y');
		$byWeek[$w.$year][]=$planning;
	}
}






//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container-fluid  bg-white">
	<div class="row py-3">
		<div class="col"></div>
		<div class="col-auto">
			<h1 class="text-main-blue text-center">Planning <?=$fullname?></h1>
		</div>
		<div class="col mt-3 text-right pr-5">
			Choisir un planning :
			<?php foreach ($listResp as $key => $resp): ?>
				<a href="?id=<?=$resp['idwebuser']?>"><i class="fas fa-calendar px-3"></i><?=$resp['resp']?></a>
			<?php endforeach ?>
		</div>

	</div>

	<!-- <div class="bg-separation-thin"></div> -->
	<div class="row pt-3">
		<div class="col">
			<div class="row">
				<div class="col"></div>

				<div class="col-auto p-3 border">
					<span class="text-danger pr-3"><i class="fas fa-question-circle pr-3"></i>Non statué</span>
					<span class="text-main-blue pr-3"><i class="fas fa-hourglass-start  pr-3"></i>A développer</span>
					<span class="text-success pr-3"><i class="fas fa-hourglass-end pr-3"></i>Terminée</span>
					<span class="text-danger pr-3"><i class="fas fa-times-circle pr-3"></i>Réfusée</span>
				</div>
				<div class="col"></div>
			</div>
		</div>
		<div class="col">
			<div class="row">
				<div class="col"></div>
				<div class="col-auto">
					<div class="alert alert-primary">
						<i class="fas fa-lightbulb pr-3"></i>Survolez le numéro de l'évo pour voir l'objet<br>
						<i class="fas fa-lightbulb pr-3"></i>Cliquez sur la semaine pour afficher un pdf des évos de la semaine
					</div>
				</div>
				<div class="col"></div>
			</div>

		</div>
	</div>

	<div class="row">
		<div class="col text-center">
			<h6>Evo à planifier :</h6>

		</div>
		<div class="col text-center">
			<h6>Evo à valider :</h6>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col toggle-target hidden">
			<?php if (!empty($validatedNotPlanned)): ?>
				<?php foreach ($validatedNotPlanned as $key => $toPlan): ?>
					- <a href="evo-detail.php?id=<?=$toPlan['id']?>" class="grey-link draggable" data-num-evo="<?=$toPlan['id']?>"><?=$toPlan['id'].' : ' .$toPlan['objet']?></a><br>
				<?php endforeach ?>
			<?php else: ?>
				toutes les évo validées ont été planifiées
			<?php endif ?>
		</div>
		<div class="col toggle-target hidden">
			<?php if (!empty($notValidatedNotPlanned)): ?>
				<?php foreach ($notValidatedNotPlanned as $key => $toValidate): ?>
					- <a href="evo-detail.php?id=<?=$toValidate['id']?>" class="grey-link draggable" data-num-evo="<?=$toValidate['id']?>"><?=$toValidate['id'].' : ' .$toValidate['objet']?></a><br>
				<?php endforeach ?>
			<?php else: ?>
				toutes les evos ont été validées
			<?php endif ?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row">
		<div class="col text-center">
			<div id="toggle"><i class="fas fa-arrow-up pr-2"></i> Afficher /masquer</div>
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

	<div class="bg-separation-thin"></div>

	<div class="row" id="planning">
		<div class="col">



			<div class="row">
				<div class="col mx-5">
					<div class="row">
						<?php foreach ($firstSemester as $month => $week): ?>
							<?php
							$nbCol=count($firstSemester);
							$padding='pr-5';
							if($countColUn==$nbCol){
								$padding='';
							}
							$countColUn++;
							?>
							<div class="col <?=$padding?>">
								<div class="row">
									<div class="col text-center text-main-blue pt-3">
										<h6><?=ucfirst(DateHelpers::frenchMonth($month, "long"))?> 	<?=$yearOne?></h6>

									</div>
								</div>
								<div class="row">
									<div class="col shadow">
										<?php foreach ($week as $weekNb => $value): ?>
											<?php 	$linkWeekEvo=""; ?>
											<?php if (isset($byWeek[$weekNb.$yearOne])){
												$linkWeekEvo="week-evo.php?week=".$weekNb."&";
												$ids=array_column($byWeek[$weekNb.$yearOne], 'id');
												for ($i=0; $i <count($byWeek[$weekNb.$yearOne]) ; $i++) {
													if($i!=count($byWeek[$weekNb.$yearOne])-1){
														$linkWeekEvo.=$i."=".$ids[$i].'&';
													}else{
														$linkWeekEvo.=$i."=".$ids[$i];
													}
												}
											}
											$date=getStartAndEndDate($weekNb, $yearOne);
											?>
											<div class="row">
												<div class="col bg-blue text-center text-white rounded py-2 my-2">
													<a class="text-white" href="<?=$linkWeekEvo?>" data-monday="<?=$date['monday']?>" data-sunday="<?=$date['sunday']?>" target="_blank" id="<?=$weekNb?>">Semaine <?=$weekNb?></a>
												</div>
											</div>
											<?php if (isset($byWeek[$weekNb.$yearOne])): ?>
												<?php foreach ($byWeek[$weekNb.$yearOne] as $key => $evo): ?>

													<?php
													switch ($evo['id_etat']) {
														case 1:
														$icon='<i class="fas fa-question-circle text-danger pr-3"></i>';
														$class="text-danger";
														break;
														case 2:
														$icon='<i class="fas fa-hourglass-start text-main-blue pr-3"></i>';
														$class="text-main-blue";
														break;
														case 4:
														$icon='<i class="fas fa-hourglass-end text-success pr-3"></i>';
														$class="text-success";
														break;
														case 5:
														$icon='<i class="fas fa-times-circle text-danger pr-3"></i>';
														$class="text-danger";

														break;
														default:
														$icon="";
														$class="grey-link";
														break;
													}

													?>
													<div class="tooltiplaunch">
														<?=$icon?><a  href="evo-detail.php?id=<?=$evo['id']?>" class="<?=$class?>">Evo <?=$evo['id']?> - <?=($evo['module']!="")?$evo['module']:$evo['appli']?></a>
														<span class="tooltiptext"><?=$evo['objet']?></span>
													</div><br>
												<?php endforeach ?>

											<?php else: ?>
												<div class="row">
													<div class="col">
														<!-- Pas d'évo planifiée -->
													</div>
												</div>


											<?php endif ?>

										<?php endforeach ?>

									</div>
								</div>
							</div>
						<?php endforeach ?>
					</div>
					<div class="row mt-3 mb-5">
						<?php foreach ($secondSemester as $month => $week): ?>
							<?php
							$nbCol=count($secondSemester);
							$padding='pr-5';
							if($countColDeux==$nbCol){
								$padding='';
							}
							$countColDeux++;
							?>
							<div class="col <?=$padding?>">

								<div class="row">
									<div class="col text-main-blue text-center pt-3">
										<h6><?=ucfirst(DateHelpers::frenchMonth($month, "long"))?> 	<?=$yearTwo?></h6>

									</div>
								</div>

								<div class="row">
									<div class="col shadow">
										<?php foreach ($week as $weekNb => $value): ?>
											<?php 	$linkWeekEvo=""; ?>
											<?php if (isset($byWeek[$weekNb.$yearTwo])){
												$linkWeekEvo="week-evo.php?week=".$weekNb."&";
												$ids=array_column($byWeek[$weekNb.$yearTwo], 'id');
												for ($i=0; $i <count($byWeek[$weekNb.$yearTwo]) ; $i++) {
													if($i!=count($byWeek[$weekNb.$yearTwo])-1){
														$linkWeekEvo.=$i."=".$ids[$i].'&';
													}else{
														$linkWeekEvo.=$i."=".$ids[$i];
													}
												}
											}
											$date=getStartAndEndDate($weekNb, $yearOne);

											?>
											<div class="row">
												<div class="col bg-blue text-center text-white rounded py-2 my-2">
													<a class="text-white" href="<?=$linkWeekEvo?>" data-monday="<?=$date['monday']?>" data-sunday="<?=$date['sunday']?>" target="_blank" id="<?=$weekNb?>">Semaine <?=$weekNb?></a>
												</div>
											</div>
											<?php if (isset($byWeek[$weekNb.$yearTwo])): ?>
												<?php foreach ($byWeek[$weekNb.$yearTwo] as $key => $evo): ?>
													<?php
													switch ($evo['id_etat']) {
														case 1:
														$icon='<i class="fas fa-question-circle text-danger pr-3"></i>';
														$class="text-danger";
														break;
														case 2:
														$icon='<i class="fas fa-hourglass-start text-main-blue pr-3"></i>';
														$class="text-main-blue";
														break;
														case 4:
														$icon='<i class="fas fa-hourglass-end text-success pr-3"></i>';
														$class="text-success";
														break;
														case 5:
														$icon='<i class="fas fa-times-circle text-danger pr-3"></i>';
														$class="text-danger";

														break;
														default:
														$icon="";
														$class="grey-link";
														break;
													}

													?>
													<div class="tooltiplaunch">
														<?=$icon?><a  href="evo-detail.php?id=<?=$evo['id']?>" class="<?=$class?>">Evo <?=$evo['id']?> - <?=($evo['module']!="")?$evo['module']:$evo['appli']?></a>

														<!-- <span class="tooltiptext"> -->
															<?php//$evo['objet']?>

															<!-- </span> -->
														</div><br>
													<?php endforeach ?>

												<?php else: ?>
													<div class="row">
														<div class="col">
															<!-- Pas d'évo planifiée -->
														</div>
													</div>


												<?php endif ?>

											<?php endforeach ?>

										</div>
									</div>


								</div>
							<?php endforeach ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<a href="#largeModal" data-toggle="modal" >
					modal open
				</a>
			</div>
		</div>


		<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">Planication de l'évo #<span id="id_evo_title"></span></h4>
					</div>

					<div class="modal-body">
						<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
							<input type="hidden" class="form-control" name="id_evo" id="id_evo">
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label for="date_start">Date de début</label>
										<input type="date" class="form-control" name="date_start" id="date_start">
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label for="date_end">Date de fin</label>
										<input type="date" class="form-control" name="date_end" id="date_end">
									</div>
								</div>
								<div class="col-auto mt-4 pt-2">
									<button class="btn btn-primary">Planifier</button>
								</div>
							</div>
						</form>



					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
					</div>
				</div>
			</div>
		</div>


	</div>
	<script type="text/javascript">
		$("#toggle").on("click", function() {
			$(".toggle-target").toggleClass("hidden shown");
		});

		var javaWeeks=<?php echo json_encode($javaWeeks)?>;
		for ( var i=0; i<=javaWeeks.length; i++ ) {
			$('#'+javaWeeks[i]).droppable( {
				accept: '.draggable',
				hoverClass: 'hovered',
				drop: test
			} );
		}
		function test(event, ui){
			$(this).addClass( "ui-state-highlight" );
			var weekNb=$(this).attr('id');
			var numEvo = ui.draggable.data( 'num-evo' );
			var monday = $(this).data( 'monday' );
			var sunday = $(this).data( 'sunday' );

			$('#id_evo').val(numEvo);
			$('#id_evo_title').text(numEvo);
			$('#date_start').val(monday);
			$('#date_end').val(sunday);
			$('#largeModal').modal('show');

		}



		$('.draggable').draggable({
			containment: '#planning',
			cursor: 'move',
			helper: myHelper,
			over: function(event, ui) {
				$(this).addClass('temporaryhighlight');
			},
			out: function(event, ui) {
				$(this).removeClass('temporaryhighlight');
			},
			drop: handleDragStop

		});
		function myHelper( event ) {
		// var numEvo = ui.draggable.data( 'num-evo' );
		var numEvo = $(this).data( 'num-evo' );

		return '<div id="draggableHelper"><i class="fas fa-calendar"></i>Evo #'+numEvo+'</div>';
	}
	function handleDragStop( event, ui ) {
		var offsetXPos = parseInt( ui.offset.left );
		var offsetYPos = parseInt( ui.offset.top );
		// alert( "Drag stopped!nnOffset: (" + offsetXPos + ", " + offsetYPos + ")n");
	}
</script>
<?php
require '../view/_footer-bt.php';
?>