<?php
class Db{
	private $dbName="";
	private $dbUser="sql";
	private $host='172.30.92.53';
	private $pwd="User19092017+";



	public function getPdo($dbName){
		if($dbName=='_qlik'){
			$this->dbName=$dbName;
		}
		elseif($dbName!='web_users' && $dbName!='qlik' && $dbName!='stats'){
			$this->dbName=VERSION . $dbName;
		}else{
			$this->dbName=$dbName;
		}
	
		try {
			$pdo=new PDO("mysql:host=$this->host;port=3306;dbname=$this->dbName", $this->dbUser, $this->pwd);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

		}
		catch(Exception $e)
		{
			// die('Erreur : '.$e->getMessage());
		}
		return  $pdo;
	}

}



