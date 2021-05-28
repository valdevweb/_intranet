<?php


class RapportHelpers{

		public static function getFormDocnames($pdoCm){
		$req=$pdoCm->query("SELECT id, docname FROM formation_docnames ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
}

