<?php
require_once '../../config/session.php';
// require_once '../../vendor/autoload.php';
// require_once '../../Class/UserDao.php';










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
    <div class="text-test">hzaejjiojjiza</div>
	
	<!-- contenu -->
</div>

<?php
require '../view/_footer-bt.php';
?>