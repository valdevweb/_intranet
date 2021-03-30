<?php
if (isset($_POST['filter_op'])) {
	if(empty($_POST['select_op'][0])){
		$opToDisplay=$infoLivDao->getOpAVenir();


	}else{
		$param=join(' OR ',array_map(
			function($value){return "code_op='".$value."'";},
			$_POST['select_op']));
		$opToDisplay=$infoLivDao->getOpByCode($param);
	}

}