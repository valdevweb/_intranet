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
// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');


// // $req=$this->pdo->prepare("SELECT * FROM table WHERE cond");
// // $req->execute([

// // ]);
// // return $req->fetchAll();
// $req=$pdoUser->query("SELECT * FROM intern_users");
// $data=$req->fetchAll();


// foreach ($data as $key => $user) {
// 	$req=$pdoUser->prepare("update intern_users SET email= :email WHERe id= :id");
// 	$req->execute([
// 		':email'		=>trim($user['email']),
// 		':id'		=>trim($user['id'])

// 	]);
// 	echo $pdoUser->lastInsertId();

// }


//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Main title</h1>
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

	<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
		<?php for ($i=0; $i < 2 ; $i++):?>
			<div class="form-group">
				<label for="model"></label>
				<input type="text" class="form-control" name="model[<?=$i?>]" id="model">
			</div>

		<?php endfor?>
		<div class="row">
			<div class="col text-right">
				<button class="btn btn-primary" name="submit">Valider</button>
			</div>
		</div>
	</form>

	<?php

	if (isset($_POST['submit'])) {
		echo "<pre>";
		print_r($_POST);
		echo '</pre>';

		for ($i=0; $i < count($_POST['model']); $i++) {

			if(!empty($_POST['model'][$i]) || $_POST['model'][$i]==0){
				echo $_POST['model'][$i] ." est non vide";
			echo "<br>";

			}else{
			echo $_POST['model'][$i] ." est vide";
			echo "<br>";
			}

		}

	}
	?>

</div>

<?php
require '../view/_footer-bt.php';
?>