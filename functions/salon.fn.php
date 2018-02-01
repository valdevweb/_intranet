<?php

function displayInscr($pdoBt){
	$req=$pdoBt->prepare("SELECT * FROM salon  ORDER BY id_galec");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);


}