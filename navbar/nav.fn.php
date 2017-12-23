<?php


echo $okko;


function readRight($pdoBt,$id)
{

$req=$pdoBt->prepare("SELECT * FROM rights WHERE id_user= :id ");
$req->execute(array(
':id'	=>$id
));

$data=$req->fetchAll(PDO::FETCH_ASSOC);
return $data;

}