<?php
require_once '../../config/session.php';
require_once '../../Class/Litiges/ContrainteDao.php';

/** @var Db $db */
$pdoLitige=$db->getPdo('litige');

$contrainteDao=new ContrainteDao($pdoLitige);

$services=$contrainteDao->uniqueService();
$btnColors=['btn-primary', 'btn-danger', 'btn-dark', 'btn-secondary', 'btn-info'];

//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div id="container" class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Litiges intervention services</h1>
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
    <div class="row pb-5">
        <?php foreach ($services as $key => $service): ?>
            <div class="col">
                
            <a class="btn <?=isset($btnColors[$key])?$btnColors[$key]:''?>" href="intervention.php?id_contrainte=<?=$service['id_contrainte_dde']?>">Page <?=$service['service']?></a>
            </div>
        <?php endforeach ?>
    </div>
    <div class="pb-5"></div>
	
	<!-- contenu -->
</div>

<?php
require '../view/_footer-bt.php';
?>