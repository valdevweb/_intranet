<?php
/*

dans reserver

 */

$today= new DateTime();

// calcul dateFirstCours
$stringDay=DAYOFWEEK[$cours['jour']];

$cloneToday= clone $today;
$dateFirstCours=$cloneToday->modify($stringDay .' this week');
$sundayThisWeek=clone($today->modify('Sunday this week'));
$sundayNextWeek=clone $sundayThisWeek;
$sundayNextWeek=$sundayNextWeek->modify('+ 7 days');

// $dateFirstCours=$dateFirstCours->modify('friday this week');


$dbHourStart=explode(':',$cours['hour_start']);
$dbHeureCours=$dbHourStart[0];
$dbMinuteCours=$dbHourStart[1];
$dateFirstCours=$dateFirstCours->setTime($dbHeureCours,$dbMinuteCours);


if($dateFirstCours<$today){
	$dateFirstCours=$dateFirstCours->modify('+ 7 day');
	$dateSecondCours=clone $dateFirstCours;
	$dateSecondCours=$dateSecondCours->modify('+ 7 day');
}else{

	$dateSecondCours=clone $dateFirstCours;
	$dateSecondCours=$dateSecondCours->modify('+ 7 day');
}





include('../config/config.inc.php');
include('check.fn.php');
function getCoursDateHeureReservation($pdo,$dateHeure,$idPlanning){
	$req=$pdo->prepare("SELECT * FROM reservation WHERE date_cours= :date_cours AND id_planning = :id_planning");
	$req->execute([
		':date_cours'	=>$dateHeure,
		':id_planning'	=>$idPlanning,
	]);

	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($data)){
		return "";
	}else{
		return count($data);
	}
}
function getMaxUser($pdo,$idPlanning){
	$req=$pdo->prepare("SELECT * FROM planning WHERE id = :id LIMIT 1");
	$req->execute([
		':id'		=>$idPlanning
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getUserJournee($pdo,$idUser,$dateHeure){
	$req=$pdo->prepare("SELECT id FROM reservation WHERE id_user= :id_user AND DATE_FORMAT(date_cours,'%Y-%m-%d') = :date_cours");
	$req->execute([
		':date_cours'		=>$dateHeure,
		':id_user'			=> $idUser
	]);
	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($data)){
		return "";
	}else{
		return count($data);
	}
}

function getUserThisCours($pdo,$dateHeure,$idUser,$idPlanning){
	$req=$pdo->prepare("SELECT * FROM reservation WHERE id_user= :id_user AND date_cours= :date_cours AND id_planning= :id_planning");
	$req->execute([
		':date_cours'		=>$dateHeure,
		':id_user'			=>$idUser,
		':id_planning'		=>$idPlanning
	]);
	// echo $dateHeure;
	// echo "<br>";
	// echo 'user'. $idUser;
	// echo "<br>";
	// echo 'planning'.$idPlanning;
	// echo "<br>";
	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($data)){
		return "";
	}else{
		return count($data);
	}
}


$dateHeure=$_POST['date_cours'].' '.$_POST['hour_start'];
$dateJour=$_POST['date_cours'];
$idPlanning=$_POST['id_planning'];
$idUser=$_POST['id_session'];

$maxUser=getMaxUser($pdo, $idPlanning);
$nbInscrits= getCoursDateHeureReservation($pdo,$dateHeure,$idPlanning);

if($nbInscrits>=$maxUser['max_user']){
	echo "Cet horaire est complet. Nous ne pouvons malheureusement plus accepter d'inscription<br>";
}else{
	$dejaInscritCours=getUserThisCours($pdo, $dateHeure, $idUser, $idPlanning);
	$dejaInscritJour=getUserJournee($pdo, $idUser,$dateJour);

	if(!empty($dejaInscritCours)){
		echo "Vous êtes inscrit à ce cours. Souhaitez-vous vous désinscrire ?<br>";
		echo "<div class='text-center'><button class='btn btn-danger' name='delete'>Se désinscrire</button></div>";
	}elseif(!empty($dejaInscritJour)){
		echo "Vous êtes déjà inscrit à un cours à cette date. Vous ne pouvez malheureusement vous inscrire qu'à un cours par jour";
	}
}