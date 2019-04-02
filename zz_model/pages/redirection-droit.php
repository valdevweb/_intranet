<?php

include('../config/autoload.php');
$userExploitEvo=array(42);
$d_userExploitEvo=isUserAllowed($pdoUser,$userExploitEvo);
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. AUTHENTIFICATION);
}
elseif (!$d_userExploitEvo) {
	header('Location:../redirect/redirect.php');
}
