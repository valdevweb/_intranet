<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

//----------------------------------------------
// css dynamique
//----------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";


$title="Venir";

//------------------------------
//	ajout enreg dans stat
//------------------------------<
require "../../functions/stats.fn.php";
$descr="venir à btlec";
$page=basename(__file__);
$action="consultation";
addRecord($pdoStat,$page,$action, $descr);




//header et nav bar
include ('../view/_head.php');
include ('../view/_navbar.php');

?>




<div class="container">
	<h1 class="blue-text text-darken-4">Venir à BTLec Est</h1>
	<br><br>
	<div class="row box-bd">
	<div class="row white-box-no-bd">
		<div class="col">
			<h4 class="blue-text text-darken-4"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Nous situer :</h4>
			<hr>
			<br><br>
		</div>
	</div>
	<div class="row white-box-no-bd">
		<div class="col s12 m9 l9">

			<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2601.6523253947857!2d4.129958315959371!3d49.301928879333424!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e99d70fa6491a9%3A0x19d33c57f68318a6!2sBazar+Technique+Leclerc+Est!5e0!3m2!1sfr!2sfr!4v1512051858263" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
		</div>
		<div class="col s12 m3 l3 coord">
			<p><strong>Nos coordonnées :</strong></p>
			<p><i class="fa fa-phone" aria-hidden="true"></i>03 26 89 86 88</p>
			<p><i class="fa fa-map-marker" aria-hidden="true"></i><strong>lat.</strong> : 49.30 - <strong>long.</strong> : 4.13 </p>
			<p><i class="fa fa-globe" aria-hidden="true"></i><strong>Google Map : </strong></p>

			<p id="qrcode">
				<img class= "qrcode-img" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADEAAAAxAQMAAABJUtNfAAAABlBMVEUAAAD///+l2Z/dAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABiUlEQVQYlWNgnLO4brUDA0Mtu7ewSTwDg+sDafe4QCBda8Fx7SKQfrRDMPkiSD7zlOp9BgbGUCAAqv//wvI4z/8GBhb7COmkVQ0M3uFzdC56MzBc0H/32Px2A8Pquv1PLcQYGGzPBUc06jIw7DIXSnw9oYFBcu/b0yJpDAxCF26azP3MwPBy7kTdH0IMDJt8i+3CXjEwTNTM5K0NZWDg+xP10cKogWGvQbHFI1sGhnT9+xXJDQ0MCeoO8lfYGBime+w9sr0Q6D71UN/kAAYG8zDNY1MLGxgWyL+RVmRsYOCfJcEoDzTP4Nn1H81Ace/kqzyfgO70UfUz63jCwGBmZD5h6XQGBg2FjpdRig0M0VciJa4A3WnrOVtzWyvQvDu/3e2B+gsaI3Y9z2xgmDS9tvDTxAaGHa3ncy68Y2DoM5rhbg4MJ9l7JZI/GIHhof7xilgpMHz8b7pbhDYw1N7SPbS+DOi+8AKZRtYGBletnxZ6jUB+zZ16Xj1geGrNezmdGaheZVZPwSsGACt6i8mujClVAAAAAElFTkSuQmCC" alt="QR Code by www.procato.com"/>
			</p>
		</div>
	</div>
	</div>
</div>
<?php
// footer avec les scripts et fin de html
include('../view/_footer.php');
?>