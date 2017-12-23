<?php
require('config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
else {
	//echo "vous êtes connecté avec :";
	//
	}
include('config/nav.fn.php');

include 'public/view/_head.php';
echo "<div id='cssmenu'>";

echo afficher_menu(0,0,$associative);
echo "</div>";