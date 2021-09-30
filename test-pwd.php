<?php
require('config/config.inc.php');
require 'config/db-connect.php';


function pwdHash($pwd){
	$pwdHash=password_hash($pwd, PASSWORD_BCRYPT);
	return $pwdHash;
}

$pwd=pwdHash('mag');

$req=$pdoUser->prepare("UPDATE users set pwd= :pwd WHERE id=980 ");
$req->execute([
':pwd'			=>$pwd
]);


