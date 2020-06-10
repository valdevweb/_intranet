<?php
/**
 *
 */
class OccHelpers{

	   public static function arrayPalette($pdoBt){
        $req=$pdoBt->query("SELECT id,palette FROM occ_palettes ORDER BY id");
        return $req->fetchAll(PDO::FETCH_KEY_PAIR);
    }

}
