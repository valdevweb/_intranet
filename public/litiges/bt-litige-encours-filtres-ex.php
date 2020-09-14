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

// if(isset($_POST['esp'])){
// 	$_SESSION['filter-data']['esp']=$_POST['esp'];
// 	if($_SESSION['filter-data']['esp']==1){
// 		$_SESSION['filter-data']['esp-ico']='<div class="d-inline-block pl-3"><img src="../img/litiges/2448esp_ico.png"></div>';

// 	}elseif($_SESSION['filter-data']['esp']==1){
// 		$_SESSION['filter-data']['esp-ico']='<div class="d-inline-block  pl-3"><img src="../img/litiges/2448esp_no_ico.png"></div>';

// 	}
// }


if(isset($_POST['reset-pending'])){
	unset($_POST['pending']);
	unset($_SESSION['filter-data']['pending']);
	unset($_SESSION['filter-data']['pending-ico']);
}
if(isset($_POST['reset-vingtquatre'])){
	unset($_POST['vingtquatre']);
	unset($_SESSION['filter-data']['vingtquatre']);
	unset($_SESSION['filter-data']['vingtquatre-ico']);
}
// if(isset($_POST['reset-esp'])){
// 	unset($_POST['esp']);
// 	unset($_SESSION['filter-data']['esp']);
// 	unset($_SESSION['filter-data']['esp-ico']);
// }