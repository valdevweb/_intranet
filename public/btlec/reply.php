<?php
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
else {
	echo "vous êtes connecté avec :";
	echo $_SESSION['id'];
}

require('../../functions/form.bt.fn.php');
//______________________________________




$idMsg=$_GET['msg'];

$msg=displayMsgMag($pdoBt,$idMsg);
$id_mag=$msg['id_mag'];

//$mag_name=infoMag($pdoMag, $idMag);

include('../view/_head.php');
include('../view/_navbar.php');

?>
<div class="container">
	<div class="down"></div>
	<!--historique-->
	<div class="row">

		<h4 class="light-blue-text text-darken-2">Répondre au magasin <?= $mag_name['name_mag']?></h4>


		<h5 class="light-blue-text text-darken-2">Demande du magasin</h5>



	<p>objet :	<?= $msg['objet']?></p>
	<p>message : <?= $msg['msg']?></p>
	<p>date du message : <?= $msg['date_msg']?></p>
	<p>service : <?= $msg['id_service']?></p>
	<p>statut : <?= $msg['etat']?></p>




<?php
include('../view/_footer.php');
 ?>
<script src="<?= $jquery ?>"></script>
<script src="<?= $materializeJs ?>"></script>
<script src="<?= $mainJs ?>"></script>
</body>
</html>
