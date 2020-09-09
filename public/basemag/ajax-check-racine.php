<?php

require '../../config/config.inc.php';


$req=$pdoMag->prepare("SELECT racine_list, btlec_sca,deno_sca FROM sca3 WHERE racine_list= :racine_list");
$req->execute([
	':racine_list'   =>$_POST['racine_list']
]);
$data=$req->fetch(PDO::FETCH_ASSOC);
if(!empty($data) && !empty($data['racine_list'])){



	echo '<div class="alert alert-danger ">Cette racine de liste est déjà utilisée dans la table sca3 pour '.$data['deno_sca']. ' - '.$data['btlec_sca'].'</div>';
}
else{
	echo '<p class="alert alert-success">Racine de liste disponible</div>';
}