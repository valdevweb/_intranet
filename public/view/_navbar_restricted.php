<?php
// fonction pour vérifier les droits utilisateur
function isUserAllowed($pdoUser, $params){
	$session=$_SESSION['id'];
	$placeholders=implode(',', array_fill(0, count($params), '?'));
	$req=$pdoUser->prepare("SELECT id_user FROM attributions WHERE id_droit IN($placeholders) AND id_user=$session" );
	$req->execute($params);
	return $req->fetchAll(PDO::FETCH_ASSOC);

}

$gtOccMagIds=array(84);
$d_occMag=isUserAllowed($pdoUser,$gtOccMagIds);

?>
<div id='cssmenu'>
	<ul>
		<li class='active has-sub'><a href="<?= ROOT_PATH?>/public/gtocc/#"><span>Leclerc occasion</span></a>
			<ul>
				<li><a href="<?= ROOT_PATH. '/public/gtocc/offre-produit.php'?>">Offres produits</a></li>
			</ul>
		</li>
		<li><a href="<?= ROOT_PATH ?>/public/logoff.php" class="tooltipped" data-position="bottom" data-tooltip="se déconnecter"><span><i class="fa fa-power-off"></i></span></a></li>
	</ul>
</div>

