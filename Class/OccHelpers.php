<?php

class OccHelpers{

	   public static function arrayPalette($pdoOcc){
        $req=$pdoOcc->query("SELECT id,palette FROM palettes ORDER BY id");
        return $req->fetchAll(PDO::FETCH_KEY_PAIR);
    }

}
