<?php
// require('../../config/autoload.php');
// if(!isset($_SESSION['id'])){
// 	echo "pas de variable session";
// 	header('Location:'. ROOT_PATH.'/index.php');
// }
// if(isset($_POST['maj'])){
// 	$up=updateSca($pdoMag);
// 	if(count($up)==1){
// 		$successQ='success=maj';
// 		unset($_POST);
// 		// header("Location: ".$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&'.$successQ,true,303);
// 	}else{
// 		$errors[]="Une erreur est survenue, impossible de mettre  à jour la base de donnée";
// 	}

// }

// echo "hello";
// echo $_GET['id'];

// echo $_GET['field'];
// echo $_GET['page_y'];
if(isset($_GET['id']) && isset($_GET['field'])){
	// http://172.30.92.53/_btlecest/public/basemag/fiche-mag-copy.php?id=4026?page_y=1700&field=pole_sav_sca&value=pole_sav_gessica
	// $query="UPDATE sca3 LEFT JOIN mag ON sca3.btlec_sca= mag.id SET {$_GET['field']}=mag.{$_GET['value']} WHERE btlec_sca={$_GET['id']}";
	$query="UPDATE sca3 LEFT JOIN mag ON sca3.btlec_sca= mag.id SET {$_GET['field']}=123 WHERE btlec_sca={$_GET['id']}";
	$req=$pdoMag->query($query);
	echo "<pre>";
	print_r($query);
	print_r($req->errorInfo());
	echo '</pre>';
	$err=$req->errorInfo();
// 2 => message d'erreur / 1 = code erreur / 0 =sql state
	if(empty($err[2])){
		// header('Location:fiche-mag.php?id='.$_GET['id']);
		header('Location:fiche-mag.php?id='.$_GET['id'].'#page'.$_GET['page_y']);

		// header('Location:fiche-mag.php?id='.$_GET['id'].'&'.$_GET['page_y']);

	}
}
// if(isset($_GET['page_y'])){
// 	header('Location:fiche-mag.php?id'.$_GET['id'].'&#page'.$_GET['page_y']);

// }

// 	header('Location:fiche-mag.php?id'.$_GET['id'].'&#'.$_GET['page_y']);



?>

