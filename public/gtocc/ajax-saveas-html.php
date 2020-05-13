<?php
if(isset($_POST['iframe'])){
	$file = 'info'.date('YmdHis');
	file_put_contents($file, $_POST['iframe']);
}

?>