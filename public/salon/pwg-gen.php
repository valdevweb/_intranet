<?php
require_once '../../config/session.php';

/** @var Db $db */




$specialChars=str_shuffle('!@#$%&*()_-=+;:,.?;');

$letters=str_shuffle('abcdefghjkmnpqrstuvwxyz');
$upperLetters=strtoupper($letters);
$numbers=str_shuffle('0123456789');

$pwd=substr($upperLetters,0, 1).substr($specialChars,0, 1).substr($letters,0,6);

echo $pwd;





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

<?php
require '../view/_footer-bt.php';
?>