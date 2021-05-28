<?php

class ThemeHelpers{


	private $theme;


	public static function getThemeInfo($pdoCm, $id){
		$req=$pdoCm->prepare("SELECT * FROM themes WHERE id= :id");
		$req->execute([
			':id'	=>$id

		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}
	public static function getThemes($pdoCm){
		$req=$pdoCm->query("SELECT id, theme FROM themes ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

    public static function getTheme($pdoCm, $id) {
    	$data=self::getThemeInfo($pdoCm, $id);
        return $data['theme'];
    }


}