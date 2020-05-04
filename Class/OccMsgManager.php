<?php


class OccMsgManager{

	public function getListMsg($pdoBt,$params){
		// exemple :
		// $piloteManager->getListPilotes(['authorized=0','console="ps4"']);
		$paramStr="";
		if(isset($params)){
			$paramStr='WHERE ' .join(' AND ',$params);
		}
		$req=$this->pdo->query("SELECT * FROM occ_msg {$paramStr} ORDER BY date_insert");

		$data=$req->fetchAll(PDO::FETCH_ASSOC);
		if(empty($data)){
			return "";
		}
		return $data;
		$params="";
	}


	public function getMsg($pdoBt,$id){

	}
	public function getListRep($pdoBt,$idMsg){

	}


	public function getListMsgByIdwebuser($pdoBt,$idwebuser){

	}






}



?>