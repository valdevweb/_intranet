<?php


class Utilisateur{
		private $username;
		private $pwd;

		public function __construct($user,$pwd){
			$this->username=$user;
			$this->pwd=$pwd;
		}
		public function getUsername(){
			return $this->username;
		}

}



 ?>