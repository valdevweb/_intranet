<?php


class TestFille extends Test{


	public function changeCalcul($chiffre){
		return $chiffre +2;
	}

	public function calcul($chiffre){
		$newChiffre=$this->changeCalcul($chiffre);


		 $data=($newChiffre) *4;
		 return $data;
	}

}




 ?>