<?php
require('../../config/autoload.php');
require '../../config/db-connect.php';

$id=$_POST['id'];
// $arrayIdTr=explode("_",$idTr);
// $id=$arrayIdTr[0];
$pdoBt->prepare("DELETE FROM gazette WHERE id=:id")->execute(array(':id'	=>$id));

echo 1;
