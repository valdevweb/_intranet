<?php
require('../../config/autoload.php');
if (!isset($_SESSION['id'])) {
	header('Location:' . ROOT_PATH . '/index.php');
	exit();
}

$pageCss = explode(".php", basename(__file__));
$pageCss = $pageCss[0];
$cssFile = ROOT_PATH . "/public/css/" . $pageCss . ".css";


require '../../Class/Db.php';
require '../../Class/evo/PlanningDao.php';
require '../../Class/DateHelpers.php';
// require_once '../../vendor/autoload.php';

function getIsoWeeksInYear($year)
{
	$date = new DateTime;
	$date->setISODate($year, 53);
	return ($date->format("W") === "53" ? 53 : 52);
}
$errors = [];
$success = [];
$db = new Db();
$pdoUser = $db->getPdo('web_users');
$pdoEvo = $db->getPdo('evo');


$planningDao = new PlanningDao($pdoEvo);

$fullPlanning = $planningDao->getPlanningEvoUser($_SESSION['id_web_user']);

$thisMonth = (new DateTime())->format('n');
if ($thisMonth <= 6) {
	$yearOne = (new DateTime())->format('Y');
	$weekOneStart = 1;
	$weekOneEnd = (new DateTime($yearOne . '-06-30'))->format("W");
	$yearTwo = $yearOne;
	$weekTwoStart = $weekOneEnd + 1;

	$weekTwoEnd = getIsoWeeksInYear($yearOne);
} else {

	$yearOne = (new DateTime())->format('Y');
	$weekOneStart = (new DateTime($yearOne . '-07-01'))->format("W");
	$weekOneEnd = getIsoWeeksInYear($yearOne);
	$yearTwo = $yearOne + 1;
	$weekTwoStart = new DateTime();
	$weekTwoStart = ($weekTwoStart->setISODate($yearTwo, 1))->format("W");
	$weekTwoEnd = (new DateTime($yearTwo . '-06-30'))->format("W");
}

for ($i = $weekOneStart; $i <= $weekOneEnd; $i++) {
	$date = (new DateTime())->setISODate($yearOne, $i);
	if ($date->format('n') < $weekOneStart = (new DateTime($yearOne . '-07-01'))->format('n')) {
		$firstSemester[$date->format('n') + 1][$date->format('W')] = $date->format('d-M-Y');
	} else {

		$firstSemester[$date->format('n')][$date->format('W')] = $date->format('d-M-Y');
	}
}

for ($i = $weekTwoStart; $i <= $weekTwoEnd; $i++) {
	$date = (new DateTime())->setISODate($yearTwo, $i);
	$secondSemester[$date->format('n')][$date->format('W')] = $date->format('d-M-Y');
}
$byWeek = [];
foreach ($fullPlanning as $key => $planning) {
	$wStart = (new DateTime($planning['date_start']))->format('W');
	$wEnd = (new DateTime($planning['date_end']))->format('W');
	for ($w = $wStart; $w <= $wEnd; $w++) {
		$byWeek[$w][] = $planning;
	}
}



//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container-fluid  bg-white">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Planning</h1>
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
		<?php foreach ($firstSemester as $month => $week) : ?>
			<div class="col pr-5">
				<div class="row">
					<div class="col bg-light-blue p-3">
						<h5><?= strtoupper(DateHelpers::frenchMonth($month, "long")) ?> <?= $yearOne ?></h5>

					</div>
				</div>
				<div class="row">
					<div class="col">
						<?php foreach ($week as $weekNb => $value) : ?>
							<div class="row">
								<div class="col bg-secondary text-center text-white">
									Semaine <?= $weekNb ?>
								</div>
							</div>
							<?php if (isset($byWeek[$weekNb])) : ?>
								<?php foreach ($byWeek[$weekNb] as $key => $evo) : ?>
									Evo <?= $evo['id'] ?> - <?= ($evo['module'] != "") ? $evo['module'] : $evo['appli'] ?><br>
								<?php endforeach ?>

							<?php else : ?>
								<div class="row">
									<div class="col">
										Pas d'évo planifiée
									</div>
								</div>


							<?php endif ?>
						<?php endforeach ?>

					</div>
				</div>
			</div>
		<?php endforeach ?>
	</div>
	<div class="row">
		<?php foreach ($secondSemester as $month => $week) : ?>
			<div class="col pr-5">
				<div class="row">
					<div class="col bg-light-blue p-3">
						<h5><?= strtoupper(DateHelpers::frenchMonth($month, "long")) ?> <?= $yearTwo ?></h5>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<?php foreach ($week as $weekNb => $value) : ?>
							<div class="row">
								<div class="col bg-secondary text-center text-white">
									Semaine <?= $weekNb ?>
								</div>
							</div>
							<?php if (isset($byWeek[$weekNb])) : ?>
								<?php foreach ($byWeek[$weekNb] as $key => $evo) : ?>
									Evo <?= $evo['id'] ?> - <?= ($evo['module'] != "") ? $evo['module'] : $evo['appli'] ?><br>
								<?php endforeach ?>

							<?php else : ?>
								<div class="row">
									<div class="col">
										Pas d'évo planifiée
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

<?php
require '../view/_footer-bt.php';
?>