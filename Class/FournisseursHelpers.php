<?php

class FournisseursHelpers{

	public static function getGts($pdoFou, $field, $order){

		$req=$pdoFou->query("SELECT id, $field FROM gts order by $order");

		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}


}