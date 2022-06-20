<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

$cssFile=ROOT_PATH ."/public/css/".str_replace('php','css', basename(__file__) ).".css";


require '../../Class/Db.php';
// require_once '../../vendor/autoload.php';
include '../../vendor/phpqrcode/qrlib.php';

$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');


// QRcode::png('PHP QR Code :)');

$param = 120; // remember to sanitize that - it is user input!
    
// we need to be sure ours script does not output anything!!!
// otherwise it will break up PNG binary!

ob_start("callback");

// here DB request or some processing
$codeText = 'DEMO - '.$param;

// end of processing here
$debugLog = ob_get_contents();
ob_end_clean();

// outputs image directly into browser, as PNG stream
// QRcode::png($codeText);








$tempDir = DIR_UPLOAD;
    
$codeContents = 'This Goes From File';

// we need to generate filename somehow, 
// with md5 or with database ID used to obtains $codeContents...
$fileName = md5($codeContents).'.png';

$pngAbsoluteFilePath = DIR_UPLOAD.$fileName;
$urlRelativeFilePath = URL_UPLOAD.$fileName;


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
    <?php 
    // generating
if (!file_exists($pngAbsoluteFilePath)) {
    QRcode::png($codeContents, $pngAbsoluteFilePath);
    echo 'File generated!';
    echo '<hr />';
} else {
    echo 'File already generated! We can use this cached file to speed up site on common codes!';
    echo '<hr />';
}

echo 'Server PNG File: '.$pngAbsoluteFilePath;
echo '<hr />';

// displaying
echo '<img src="'.$urlRelativeFilePath.'" />';
?>
	
	<!-- contenu -->
</div>
<?php
require '../view/_footer-bt.php';
?>