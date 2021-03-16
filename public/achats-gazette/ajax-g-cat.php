<?php
require('../../config/autoload.php');

require '../../Class/Db.php';
require '../../Class/GazetteDao.php';


$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');


$gazetteDao=new GazetteDao($pdoDAchat);

?>
<?php if (isset($_POST['main_cat'])): ?>
	<?php $cats=$gazetteDao->getCatByMain($_POST['main_cat']);  ?>
	<?php if (!empty($cats)): ?>
			<option value="">Sélectionner</option>
		<?php foreach ($cats as $keyCat => $cat): ?>
			<option value="<?=$keyCat?>"><?=$cats[$keyCat]?></option>
		<?php endforeach ?>
	<?php endif ?>
<?php endif ?>




