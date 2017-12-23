<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}



//header et nav bar
include ('../view/_head.php');
include ('../view/_navbar.php');

?>



<div class="down"></div>
<div class="container center">

<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2601.6523253947857!2d4.129958315959371!3d49.301928879333424!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e99d70fa6491a9%3A0x19d33c57f68318a6!2sBazar+Technique+Leclerc+Est!5e0!3m2!1sfr!2sfr!4v1512051858263" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
</div>
<?php
// footer avec les scripts et fin de html
include('../view/_footer.php');
?>