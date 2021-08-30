<?php
require('../../../config/config.inc.php');



require '../../../Class/Db.php';
require '../../../Class/BaDao.php';

$db=new Db();
$pdoQlik=$db->getPdo('qlik');

$baDao= new BaDao($pdoQlik);

?>
<?php if (isset($_POST['search']) && strlen($_POST['search'])>5): ?>
<?php $datas=$baDao->getArtByEan($_POST['search']); ?>
<div class="row">
	<div class="col-1"></div>
	<div class="col rounded border bg-light-blue">
		<div class="text-main-blue font-weight-bold">Cliquez sur l'article pour récupérer ses infos :</div>
		<?php foreach ($datas as $key => $data): ?>
			<div class="selected-art" data-id-ba="<?=$data['id']?>"><b><?=$data['ean']?></b> art <?=$data['article']?>/<?=$data['dossier']?> - <?=$data['libelle']?></div>
		<?php endforeach ?>
	</div>
	<div class="col-1"></div>
</div>
<?php elseif(isset($_POST['search']) && strlen($_POST['search'])<5):?>
<div class="row">
	<div class="col-2"></div>
	<div class="col">
		La recherche débutera au 6ème caractère saisi
	</div>
	<div class="col-1"></div>

</div>
<?php endif ?>
