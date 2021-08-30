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

require '../../Class/Month.php';

// require '../Class/CmManager.php';
// require '../Class/Cm.php';
// require '../Class/Helpers.php';
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoEvo=$db->getPdo('evo');

$errors=[];
$success=[];


$tdClass="calendar-shortweek";

$_SESSION['calendar_mode']="year";

if(isset($_POST['weekend'])){
	$tdClass="calendar-fullweek";
}


$thisMonth = new Month(5,2021);

$start = $thisMonth->getStartingDay();
	echo "<pre>";
	print_r($start);
	echo '</pre>';

$start = $start->format('N') === '1' ? $start : $thisMonth->getStartingDay()->modify('last monday');
	echo "<pre>";
	print_r($start);
	echo '</pre>';
$weeks = $thisMonth->getWeeks();
	echo "<pre>";
	print_r($weeks);
	echo '</pre>';

$end = $start->modify('+' . (6 + 7 * ($weeks -1)) . ' days');
	echo "<pre>";
	print_r($end);
	echo '</pre>';





if(isset($_GET['error'])){
	$msgOfError=[
		1 =>"impossible de supprimer le rendez-vous",
	];
	$errors[]=$msgOfError[$_GET['error']];
}
//------------------------------------------------------
// echo "<pre>";
// print_r($events);
// echo '</pre>';


include('../view/_head.php');
include('../view/_navbar.php');
?>
<!--********************************
ligne de titre avec btn de nav et selection
*********************************-->
<div class="container-fluid">

	<!-- en tete avant le calendrier -->
	<div class="row">



		<!-- btn previous next -->
		<div class="col-sm-2 col-xl-3 text-right">


			<?php if ($_SESSION['calendar_mode']=='week'): ?>
				<a href="calendar.php?month=<?= $thisMonth->previousWeek()->month; ?>&year=<?= $thisMonth->previousWeek()->year; ?>&week=<?= $thisMonth->previousWeek()->nWeek; ?>" class="btn btn-blue">&lt;</a>
				<a href="calendar.php?month=<?= $thisMonth->nextWeek()->month; ?>&year=<?= $thisMonth->nextWeek()->year; ?>&week=<?= $thisMonth->nextWeek()->nWeek; ?>" class="btn btn-blue">&gt;</a>
			<?php elseif($_SESSION['calendar_mode']=='month'): ?>
				<a href="calendar.php?month=<?= $thisMonth->previousMonth()->month; ?>&year=<?= $thisMonth->previousMonth()->year; ?>" class="btn btn-blue">&lt;</a>
				<a href="calendar.php?month=<?= $thisMonth->nextMonth()->month; ?>&year=<?= $thisMonth->nextMonth()->year; ?>" class="btn btn-blue">&gt;</a>
			<?php elseif($_SESSION['calendar_mode']=='year'): ?>
				<a href="calendar.php?month=<?= $thisMonth->previousYear()->month; ?>&year=<?= $thisMonth->previousYear()->year; ?>" class="btn btn-blue">&lt;</a>
				<a href="calendar.php?month=<?= $thisMonth->nextYear()->month; ?>&year=<?= $thisMonth->nextYear()->year; ?>" class="btn btn-blue">&gt;</a>
			<?php endif ?>

		</div>
	</div>
	<!-- fin row titre  -->



		<div class="row justify-content-center">
			<?php
		// affichage année
			for ($i=1; $i <=12 ; $i++) {
			// bloc mois
			// echo '<div class="col-xl-1"></col>';

				echo '<div class="col-md-4 col-lg-auto m-md-1 m-lg-3 m-xl-4 light-shadow rounded year-box">';
				echo '<table>';
				$thisMonth = new Month($i, isset($_GET['year']) ? $_GET['year']: null);

				$start = $thisMonth->getStartingDay();
				$start = $start->format('N') === '1' ? $start : $thisMonth->getStartingDay()->modify('last monday');
				$weeks = $thisMonth->getWeeks();
				$end = $start->modify('+' . (6 + 7 * ($weeks -1)) . ' days');
				echo '<tr class="font-weight-bold text-blue-c3" ><td colspan="7" class="p-1">';
				echo '<a href="calendar.php?month='.$thisMonth->getMonth().'&year='.$thisMonth->getYear().'&view=month" class="link-calendar">'.$thisMonth->toString() .'</a>';
				echo '</td></tr>';

				echo '<tr>';
				for ($w = 0; $w < $weeks; $w++){


					foreach($thisMonth->days as $k => $day){
						// equivalent à jour +1
						$date = $start->modify("+" . ($k + $w * 7) . " days");
						$isToday = date('Y-m-d') === $date->format('Y-m-d');
						$isTodayClass = !empty($isToday) ? 'is-today-year' : '' ;
						$isHoly=isset($holy[$date->format('Y-m-d')]) ? 'holyday-year':'';
	$outOfMonth='out-of-month';
						// if(!empty($thisMonth->withinMonth($date))){
						// 	$outOfMonth='';
						// }
						// else{
						// 	$outOfMonth='out-of-month';

						// }
						echo '<td class="'.$isTodayClass. $isHoly .' p-1">';

						if ($w === 0){
						// affichage lettre du jour grace à after
							echo '<div class="yeardays text-blue-d9 year-'.$k.'"></div>';
						}

						echo '<a class="'.$outOfMonth.' link-calendar" href="oneday.php?date='.$date->format('Y-m-d').'">'.$date->format('d').'</a>';

						echo '</td>';
					}
					echo '</tr>';
				}
					// echo $i;
			// fin bloc mois
				echo '</table>';
				echo '</div>';



				if($i%4==0){
					echo '<div class="w-100"></div>';
				}

			}




			?>


		</div>

	</div>



	<?php require '../view/_footer.php'; ?>