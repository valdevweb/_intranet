<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'Class/Db.php';
$db= new Db();
$pdoQlik=$db->getPdo('qlik');

// $pdoQlik->query("OPTIMIZE TABLE cdes_fou_qte_init ");
// $pdoQlik->query("OPTIMIZE TABLE cdes_fou ");
// $pdoQlik->query("OPTIMIZE TABLE cdes_fou_details ");


$query="SELECT cdes_fou_details.qte_cde, cdes_fou_qte_init.id as id_init, qte_cde_init FROM `cdes_fou_details` LEFT JOIN cdes_fou_qte_init ON cdes_fou_details.id= cdes_fou_qte_init.id_detail where  qte_cde>qte_cde_init";

$req=$pdoQlik->query($query);
$result=$req->fetchAll();

foreach ($result as $key => $data) {
    echo $data['qte_cde']. " remplace ". $data['qte_cde_init'];    
    echo "<br>";
    $req=$pdoQlik->prepare("UPDATE cdes_fou_qte_init SET qte_cde_init= :qte where id= :id");
    $req->execute([
        ':id'           =>$data['id_init'],
        ':qte'          =>$data['qte_cde']
    ]);
}