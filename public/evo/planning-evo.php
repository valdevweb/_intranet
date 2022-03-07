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
require '../../Class/UserDao.php';
require '../../Class/evo/AffectationDao.php';
require '../../Class/evo/EvoHelpers.php';
require '../../Class/DateHelpers.php';
require '../../Class/UserHelpers.php';
require_once '../../vendor/autoload.php';

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
$affectationDao= new AffectationDao($pdoEvo);

$userDao=new UserDao($pdoUser);
$droits=$userDao->getUserAttributions();




$listResp=$evoDao->getListResp();
$arrDevMail=EvoHelpers::arrayAppliRespEmail($pdoEvo);
$listLevel=EvoHelpers::arrayLevels($pdoEvo);
$countColUn=1;
$countColDeux=1;



if(isset($_GET['id'])){
	$idwebuser=$_GET['id'];
	$idResp=EvoHelpers::getIdResp($pdoEvo, $idwebuser);
}else{
	$idwebuser=$_SESSION['id_web_user'];
	$idResp=EvoHelpers::getIdResp($pdoEvo, $idwebuser);
	if(empty($idResp)){
		$errors[]="Vous n'avez pas de planning. Vous pouvez sélectionner un planning existant en cliquant sur le nom de la personne";
	}
}





$thisMonth= (new DateTime())->format('n');
$thisWeek=(new DateTime())->format('W');
if ($thisMonth<=6) {
	//  du 1er janv au 31 dec
	$yearOne=(new DateTime())->format('Y');
	$weekOneStart=1;
	$weekOneDate=(new DateTime($yearOne. '-01-01'));
	$weekOneEnd=(new DateTime($yearOne. '-06-30'))->format("W");
	$yearTwo=$yearOne;
	$weekTwoStart=$weekOneEnd+1;
	$weekTwoDate=(new DateTime($yearOne. '-12-31'));
	$weekTwoEnd=getIsoWeeksInYear($yearOne);
}else{

	$yearOne=(new DateTime())->format('Y');
	$weekOneStart=(new DateTime($yearOne. '-07-01'))->format("W");
	$weekOneDate=(new DateTime($yearOne. '-07-01'));
	$weekOneEnd=getIsoWeeksInYear($yearOne);

	$yearTwo=$yearOne+1;
	$weekTwoStart=new DateTime();
	$weekTwoStart=($weekTwoStart->setISODate($yearTwo, 1));


	$weekTwoStart=($weekTwoStart->setISODate($yearTwo, 1))->format("W");
	$weekTwoEnd=(new DateTime($yearTwo. '-06-30'))->format("W");
	$weekTwoDate=(new DateTime($yearTwo. '-06-30'));

}
for ($i=$weekOneStart; $i <=$weekOneEnd; $i++) {
	$date = (new DateTime())->setISODate($yearOne,$i);
	if($date>=$weekOneDate){
		$firstSemester[$date->format('n')][$date->format('W')]=$date->format('d-M-Y');
	}
	$javaWeeks[]=$date->format('W');
}

$periodeStart=$weekOneDate->format('Y-m-d');
$periodeEnd=$weekTwoDate->format('Y-m-d');


$fullPlanning= $planningDao->getPlanningEvoDev($idwebuser, $periodeStart, $periodeEnd);
$fullname=UserHelpers::getFullnameIdwebuser($pdoUser,$idwebuser);
$affectations=$affectationDao->getAffectationByEvo($periodeStart, $periodeEnd, $idwebuser);



foreach ($affectations as $idEvo => $value) {
	$evoAffectation[$idEvo]=array_column($value, 'id_web_user');
}





$validatedNotPlanned=$evoDao->getEvoNoPlanning($idwebuser,2);
$notValidatedNotPlanned=$evoDao->getEvoNoPlanning($idwebuser,1);




for ($i=$weekTwoStart; $i <=$weekTwoEnd; $i++) {
	$date = (new DateTime())->setISODate($yearTwo,$i);
	$secondSemester[$date->format('n')][$date->format('W')]=$date->format('d-M-Y');
	$javaWeeks[]=$date->format('W');
}


$twelveMonths=array_replace($firstSemester, $secondSemester);

$byWeek=[];


foreach ($fullPlanning as $key => $planning) {
	$wStartZero=(new DateTime($planning['date_start']))->format('W');
	$wEndZero=(new DateTime($planning['date_end']))->format('W');
	$wStart=intval($wStartZero);
	$wEnd=intval($wEndZero);

	for ($w=$wStart; $w <=$wEnd ; $w++) {
		$year=(new DateTime($planning['date_start']))->format('Y');
		$date=$w.$year;
		if($w<10){
			$date="0".$w.$year;
		}
		$byWeek[$date][]=$planning;

	}
}

if(isset($_POST['plan'])){
	if(!in_array(87,$droits)){
		echo "vos droits ne vous permettent pas de placer des demandes d'évo au planning";
		exit();
	}
	if(empty($_POST['date_start']) ||empty($_POST['date_end'])){
		$errors[]="Merci de saisir une date de fin et de début";
	}

	if(empty($errors)){
		$planningDao->insertPlanning($_POST['id_evo'], $idResp, $_POST['date_start'], $_POST['date_end']);
		if(isset($_POST['envoi']) && $_POST['envoi']==1){
			$affectation=$affectationDao->getAffectation($_POST['id_evo']);

			if(!empty($affectation)){
				foreach ($affectation as $key => $affect) {
					$dest[]=$affect['email'];
				}
			}

			$cc[]=$arrDevMail[$idResp];


			if(VERSION=="_"){
				$dest=[MYMAIL];
				$cc=[];
			}

			$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
			$mailer = new Swift_Mailer($transport);

			$htmlMail = file_get_contents('mail/plannification.html');
			$htmlMail=str_replace('{OBJET}',$evo['objet'],$htmlMail);
			$htmlMail=str_replace('{EVO}',$evo['evo'],$htmlMail);
			$htmlMail=str_replace('{DATE_START}',date('d-m-Y', strtotime($_POST['date_start'])),$htmlMail);
			$htmlMail=str_replace('{DATE_END}',date('d-m-Y', strtotime($_POST['date_end'])),$htmlMail);
			$subject='Portail BTLec - demande d\'évo - mise au planning';
			$message = (new Swift_Message($subject))
			->setBody($htmlMail, 'text/html')
			->setFrom(EMAIL_NEPASREPONDRE)
			->setTo($dest)
			->setCc($cc);

			if (!$mailer->send($message, $failures)){
				print_r($failures);
			}else{
				$success[]="mail envoyé avec succés";
			}
		}


		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'],true,303);
	}
}

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
				<?php
				$icoClass="";
				$icoClass=(isset($listLevel[$validatedNotPlanned['id_chrono']]))? 'text-'.$listLevel[$validatedNotPlanned['id_chrono']]['class']:"";
				?>

				<?php foreach ($validatedNotPlanned as $key => $toPlan): ?>
					- <a href="evo-detail.php?id=<?=$toPlan['id']?>" class="grey-link draggable" data-num-evo="<?=$toPlan['id']?>"><?=$toPlan['id'].' : ' .$toPlan['objet']?></a><i class="fas fa-tachometer-alt pl-2 <?=$icoClass?>"></i><br>
				<?php endforeach ?>
			<?php else: ?>
				toutes les évo validées ont été planifiées
			<?php endif ?>
		</div>
		<div class="col toggle-target hidden">
			<?php if (!empty($notValidatedNotPlanned)): ?>
				<?php foreach ($notValidatedNotPlanned as $key => $toValidate): ?>
					<?php
					$icoClass="";
					$icoClass=(isset($listLevel[$toValidate['id_chrono']]))? 'text-'.$listLevel[$toValidate['id_chrono']]['class']:"";
					?>
					- <a href="evo-detail.php?id=<?=$toValidate['id']?>" class="grey-link draggable" data-num-evo="<?=$toValidate['id']?>"><?=$toValidate['id'].' - ' .$toValidate['objet']?></a><i class="fas fa-tachometer-alt pl-2 <?=$icoClass?>"></i><br>
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

			<!-- zone planning -->

			<div class="row">
				<div class="col mx-5">
					<!-- interieur  -->
					<?php foreach ($twelveMonths as $month => $week): ?>
						<?php
						$nbCol=6;
						$padding='pr-5';
						if($countColUn==$nbCol){
							$padding='';
						}
						if(!isset($yearEncours)){
							// 1er boucle, on n'a pas parcouru $week donc on ne connait pas la date
							$yearEncours=$yearOne;
						}else{
							// on a toujours l'année de week qui vient d'être parcouru donc si on affiche le mois de janvier et que ce n'est pas le 1er mois qu'on affiche, on doit ajouter un à l'année
							if($month==1){
								$yearEncours=$yearEncours+1;
							}
						}
						$countColUn++;
						?>
						<?php if ($countColUn==2 || $countColUn==8): ?>
							<div class="row debut">
							<?php endif ?>
							<div class="col <?=$padding?>">
								<!-- titre mois -->
								<div class="row">
									<div class="col text-center text-main-blue pt-3">
										<h6><?=ucfirst(DateHelpers::frenchMonth($month, "long"))?> <?=$yearEncours?></h6>
									</div>
								</div>
								<!-- bloc mois -->
								<div class="row">
									<div class="col shadow">
										<?php foreach ($week as $weekNb => $date): ?>
											<?php
											$linkWeekEvo="";
											$weekClass="bg-blue";
											$yearEncours=substr($date,-4);
											?>
											<?php
											if (isset($byWeek[$weekNb.$yearEncours])){
												$linkWeekEvo="week-evo.php?week=".$weekNb."&";
												$ids=array_column($byWeek[$weekNb.$yearEncours], 'id');
												for ($i=0; $i <count($byWeek[$weekNb.$yearEncours]) ; $i++) {
													if($i!=count($byWeek[$weekNb.$yearEncours])-1){
														$linkWeekEvo.=$i."=".$ids[$i].'&';
													}else{
														$linkWeekEvo.=$i."=".$ids[$i];
													}
												}
											}
											$date=getStartAndEndDate($weekNb, $yearEncours);
											if($weekNb==$thisWeek){
												$weekClass="bg-gold";
											}
											?>
											<!-- titre : date semaine -->
											<div class="row">
												<div class="col <?=$weekClass?> text-center  rounded py-2 my-2" data-monday="<?=$date['monday']?>" data-sunday="<?=$date['sunday']?>" target="_blank" id="<?=$weekNb?>">
													<a  href="<?=$linkWeekEvo?>" >Semaine <?=$weekNb?></a><br>
													du <?=date('d', strtotime($date['monday']))?> <?=(DateHelpers::frenchMonth(date('n', strtotime($date['monday']))))?> au <?=date('d', strtotime($date['sunday']))?> <?=(DateHelpers::frenchMonth(date('n', strtotime($date['sunday']))))?> <?=$yearEncours?>

												</div>
											</div>
											<?php if (isset($byWeek[$weekNb.$yearEncours])): ?>
												<?php foreach ($byWeek[$weekNb.$yearEncours] as $key => $evo): ?>

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
													$icoClass=(isset($listLevel[$evo['id_chrono']]))? 'text-'.$listLevel[$evo['id_chrono']]['class']:"";
													$bgClass="";
													if(isset($evoAffectation[$evo['id']]) && in_array($_SESSION['id_web_user'], $evoAffectation[$evo['id']])){
														$bgClass="bg-yellow-light";
													}

													?>
													<!-- ligne evo -->
													<div class="row">
														<div class="col tooltiplaunch <?=$bgClass?>">
															<?=$icon?>
															<a  href="evo-detail.php?id=<?=$evo['id']?>" class="<?=$class?>">Evo <?=$evo['id']?> - <?=($evo['module']!="")?$evo['module']:$evo['appli']?><i class="fas fa-tachometer-alt pl-2 <?=$icoClass?>"></i></a>
															<span class="tooltiptext"><?=$evo['objet']?></span>
														</div>
													</div>
													<!-- fin ligne evo -->

												<?php endforeach ?>

											<?php else: ?>
												<div class="row">
													<div class="col">
													</div>
												</div>
											<?php endif ?>

										<?php endforeach ?>

									</div>
								</div>
							</div>
							<?php if ($countColUn==7 || $countColUn==13): ?>
								<!-- fin de row semester -->
							</div>
						<?php endif ?>
					<?php endforeach ?>

				</div>
			</div>
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
							<div class="col">
								Informer les demandeurs :
								<div class="form-check">
									<input class="form-check-input" type="radio" value="1" id="oui" name="envoi" checked>
									<label class="form-check-label" for="oui">Oui</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" value="0" id="non" name="envoi">
									<label class="form-check-label" for="non">Non</label>
								</div>
							</div>
							<div class="col-auto mt-4 pt-2">
								<button class="btn btn-primary" name="plan">Planifier</button>
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
			drop: launchModal
			// drop: console.log("ici")
		} );
	}
	function launchModal(event, ui){
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
	});
	function myHelper( event ) {
		var numEvo = $(this).data( 'num-evo' );
		return '<div id="draggableHelper"><i class="fas fa-calendar"></i>Evo #'+numEvo+'</div>';
	}

</script>
<?php
require '../view/_footer-bt.php';
?>