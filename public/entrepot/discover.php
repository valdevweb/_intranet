<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
include('../view/_head.php');
include('../view/_navbar.php');

//------------------------------
//			ajout enreg dans stat
//------------------------------
require "../../functions/stats.fn.php";
//------------------------------

$descr="page visite entrepot";
$page=basename(__file__);
$action="consultation";
addRecord($pdoStat,$page,$action, $descr);

?>
<div class="container">


	<h1 class="light-blue-text text-darken-2">L'entrepôt</h1>
	<!-- 	<p><img src="../img/soon.png"></p> -->
<!-- 	<div class="row center">
		<div class="col l4"></div>
		<div class="col l4">
			<h4 class="light-blue-text text-darken-2">Visite virtuelle de l'entrepôt</h4>

			<div class="down"></div>
			<video width="400" height="300" controls poster="../img/entrepot/before-video.png">
				<source src="" type="video/mp4">
					<source src="" type="video/ogg">
					</video>
				</div>
				<div class="col l4"></div>

 -->
	 <div class="row ">
			<h4 class="light-blue-text text-darken-2">Visite virtuelle de l'entrepôt</h4>
	</div>
	 <div class="row center">
	 	<div class="col l4">
	 		<video width="400" height="300" controls poster="">
	 			<source src="../video/entrepot02.mp4" type="video/mp4">
					<source src="" type="video/ogg">
			</video>
			<p>quai de chargements</p>
	 	</div>
	 	<div class="col l4">
	 		<video width="400" height="300" controls poster="">
	 			<source src="../video/entrepot03.mp4" type="video/mp4">
	 			<source src="" type="video/ogg">
	 		</video>
			<p>zone internet</p>

	 	</div>
	 	<div class="col l4">
	 		<video width="400" height="300" controls poster="">
	 			<source src="../video/entrepot04.mp4" type="video/mp4">
	 			<source src="" type="video/ogg">
	 		</video>
			<p>"filmeuse"</p>

	 	</div>
	</div>

</div>
			<?php
			include('../view/_footer.php');

			?>