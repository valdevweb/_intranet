<?php

class EvoManager{

	private $pdoEvo;



	public function __construct($pdoEvo){
		$this->setPdo($pdoEvo);
	}
	public function setPdo($pdoEvo){
		$this->pdoEvo=$pdoEvo;
		return $pdoEvo;
	}

	public function getListPlateforme($pdoEvo){
		$req=$this->pdoEvo->query("SELECT * FROM plateformes ORDER BY plateforme");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}


}