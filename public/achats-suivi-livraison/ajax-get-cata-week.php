<?php
require('../../config/autoload.php');

require '../../Class/Db.php';
require '../../Class/CataDao.php';


$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');


$cataDao=new CataDao($pdoQlik);

?>
<?php if (isset($_POST['week'])): ?>
	<?php $ops=$cataDao->getOneWeekOp($_POST['week']);  ?>
	<?php if (!empty($ops)): ?>
			<option value="">Sélectionner</option>
		<?php foreach ($ops as $keyOps => $op): ?>
			<option value="<?=$op['code_op']?>"><?=$op['code_op']. ' - '.$op['libelle']?></option>
		<?php endforeach ?>
	<?php endif ?>
<?php endif ?>




