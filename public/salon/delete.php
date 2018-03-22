<?php
// ---------------------------------------------------
// SESSION & AUTOLOAD
//----------------------------------------------------
require('../../config/autoload.php');

$id=$_GET['id'];



$req=$pdoBt->prepare("DELETE FROM salon WHERE id= :id");
$req->execute(array(
	':id'=> $id
));

header("Location:inscription2.php#inscription-lk");

