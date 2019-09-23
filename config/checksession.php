<?php
session_start();

if(isset($_SESSION['id'])){
	echo $_SESSION['id_web_user']. ' id : '.$_SESSION['id_web_user'] .date("H:i:s");

}else{
	echo "1";

}



