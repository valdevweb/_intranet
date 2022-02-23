<?php
class DialDao
{

    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

   
    public function getInitialCmt($idDossier){
        $req=$this->pdo->prepare("SELECT * FROM dial WHERE id_dossier= :id AND mag =3 LIMIT 1");
        $req->execute([
            ':id'	=>$idDossier
        ]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }
 
}
