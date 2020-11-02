<?php

class OccHelpers{

	public static function arrayPalette($pdoOcc){
		$req=$pdoOcc->query("SELECT id,palette FROM palettes ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function getPaletteNameByArticlePalette($pdoOcc, $articlePalette){
		$req=$pdoOcc->prepare("SELECT palette FROM palettes_articles LEFT JOIN palettes ON id_palette=palettes.id WHERE article_palette= :article_palette");
		$req->execute([
			':article_palette'	=>$articlePalette
		]);
		$data=$req->fetch(PDO::FETCH_ASSOC);
		if(!empty($data)){
			return $data['palette'];
		}
		return "";
	}


}
