<?php
// require_once '../../config/session.php';

/** @var Db $db */
// require_once '../../Class/mag/MagHelpers.php';

// ga-btlecest-'.$btlec.'-'.$suffixe.'@btlecest.leclerc

// $pdoSav=$db->getPdo('sav');
// $req=$pdoSav->query("SELECT *FROM mail_mag LEFT JOIN magasin.sca3 ON galec=galec_sca WHERE email like  '%@btlec.fr%'");
// $mails=$req->fetchAll(PDO::FETCH_ASSOC);
// // echo "<pre>";
// // print_r($mails);
// // echo '</pre>';
// foreach($mails as $mail){
// 	if($mail['galec']!=0100){
// 		echo $mail['email'] . ' ' .$mail['btlec_sca'] ;
// 		if($mail['btlec_sca']!=''){
// 			$newMail=MagHelpers::makeLdMag($mail['btlec_sca'], 'rbt');
// 			$req=$pdoSav->prepare("UPDATE mail_mag SET email = :email WHERE id=:id");
// 			$req->execute([
// 				':email'		=>$newMail,
// 				':id'		=>$mail['id']
// 			]);
// 			echo $newMail;
// 			echo "<br>";

// 		}else{
// 			echo "NO BTLEC";
// 			echo "<br>";

// 		}

// 	}

// }







//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div id="container" class="container">
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

	<!-- contenu -->
</div>
<script>
	if ('serviceWorker' in navigator) {
		// navigator.serviceWorker.register('./sw.js').then((resp) => {
		// 	console.warn("resp", resp);
		// }).cache((e) => {
		// 	console.error(e);
		// })

	} else {
		// console.error("sw not working");
	}
</script>
<?php
require '../view/_footer-bt.php';
?>