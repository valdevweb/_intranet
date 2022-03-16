<?php

if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'functions/tasklog.fn.php';
include 'Class/Db.php';

$db=new Db();
$pdoPilotage=$db->getPdo('pilotage');
$ptLiv=$req=$pdoPilotage->query("SELECT * FROM pt_liv");
$datas=$req->fetchAll(PDO::FETCH_ASSOC);

foreach($datas as $data){
    if(filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
        // echo $data['email']. " ok";
    }else{
        echo $data['email']. " KO";
    }
    echo "<br>";
    if(filter_var($data['cc'], FILTER_VALIDATE_EMAIL)){
        // echo $data['cc']. " ok";
    }else{
        echo $data['cc']. " KO";
    }
    echo "<br>";
}
