<?php

class FournisseursHelpers{

	public static function getGts($pdoFou, $field, $order){

		$req=$pdoFou->query("SELECT id, $field FROM gts WHERE main=1 order by $order");

		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}


}