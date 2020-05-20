<?php
if(isset($_POST['iframe'])){
	$filenoext='info'.date('YmdHis');
	$file = 'info'.date('YmdHis').'.html';
	file_put_contents($file, $_POST['iframe']);
	echo $filenoext;

}

?>