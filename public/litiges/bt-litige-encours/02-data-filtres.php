<?php

if(isset($_POST['pending'])){
	if($_POST['pending']==0){
		$_SESSION['filter-data']['pending']='pending';
		$_SESSION['filter-data']['pending-ico']=	'<i class="fas fa-user-check stamp pending"></i>';
	}
	else{
		$_SESSION['filter-data']['pending']=$_POST['pending'];
		$_SESSION['filter-data']['pending-ico']=	'<i class="fas fa-user-check stamp validated"></i>';

	}
}

if(isset($_POST['vingtquatre'])){
	$_SESSION['filter-data']['vingtquatre']=$_POST['vingtquatre'];
	if($_SESSION['filter-data']['vingtquatre']==1){
		$_SESSION['filter-data']['vingtquatre-ico']='<div class="d-inline-block pl-3"><img src="../img/litiges/2448_ico.png"></div>';
	}elseif($_SESSION['filter-data']['vingtquatre']==0){
		$_SESSION['filter-data']['vingtquatre-ico']='<div class="d-inline-block  pl-3"><img src="../img/litiges/2448_no_ico.png"></div>';
	}
}
if(isset($_POST['occasion'])){
	$_SESSION['filter-data']['occasion']=$_POST['occasion'];
	if($_SESSION['filter-data']['occasion']==1){
		$_SESSION['filter-data']['occasion-ico']='<div class="d-inline-block pl-3"><img src="../img/logos/leclerc-occasion-circle-mini.gif"></div>';

	}elseif($_SESSION['filter-data']['occasion']==0){
		$_SESSION['filter-data']['occasion-ico']='<div class="d-inline-block  pl-3"><img src="../img/logos/leclerc-occasion-none.png"></div>';

	}
}
if(isset($_POST['dial_notif'])){
	$_SESSION['filter-notif']='dial';
}
if(isset($_POST['action_notif'])){
	$_SESSION['filter-notif']='action';
}
if(isset($_POST['reset-pending'])){
	unset($_POST['pending']);
	unset($_SESSION['filter-data']);
	unset($_SESSION['filter-data']);
}
if(isset($_POST['reset-vingtquatre'])){
	unset($_POST['vingtquatre']);
	unset($_SESSION['filter-data']);
	unset($_SESSION['filter-data']);
}
if(isset($_POST['reset-occasion'])){
	unset($_POST['occasion']);
	unset($_SESSION['filter-data']);
	unset($_SESSION['filter-data']);
}

