<?php
if (isset($_POST['filter_op'])) {
	$param=join(' OR ',array_map(
		function($value){return "code_op='".$value."'";},
		$_POST['select_op']));
	// $param= "code_op='".$_POST['select_op']."'";
	$opToDisplay=$infoLivDao->getOpByCode($param);
}