<?php

class ProdHelpers{


	private $prod;


	public static function getProdInfo($pdoCm, $id){
		$req=$pdoCm->prepare("SELECT * FROM prods WHERE id= :id");
		$req->execute([
			':id'	=>$id

		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}


    public static function getProd($pdoCm, $id)
    {
    	$data=self::getProdInfo($pdoCm, $id);
        return $data['prod'];
    }


}