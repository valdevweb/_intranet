<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");

}
else{
	set_include_path("D:\www\intranet\btlecest\\");

}
include 'config/config.inc.php';
include 'config/db-connect.php';



function getExtraction($pdoMag,$id){
	$req=$pdoMag->prepare("SELECT * FROM lotus_extraction WHERE id_import= :id_import AND contenu !='' AND contenu IS NOT NULL AND TYPE !='vide'");
	$req->execute([
		':id_import'=>$id
	]);
	$datas=$req->fetchAll();
	if(empty($datas)){
		return "";
	}
	return $datas;
}



$lotusCon=ldap_connect('217.0.222.26',389);
$ldaptree    = "OU=galec,o=e-leclerc,c=fr";
$ldapuser="ADMIN_BTLEC";
$lpappass="toronto";
$ldapbind = ldap_bind($lotusCon, $ldapuser, $lpappass) or die ("Error trying to bind: ".ldap_error($ldapbind));
$justThese = array( "mail","displayname", "mailaddress");

// $result=ldap_search($lotusCon, $ldaptree, "(CN=*bbj*)",$justThese);
// $result = ldap_search($lotusCon,$ldaptree, "(cn=*'.$name.'*)") or die ("Error in search query: ".ldap_error($ldapCon));
// $result = ldap_search($lotusCon,$ldaptree, "(cn=*Concept BBJ Verdun*)") or die ("Error in search query: ".ldap_error($ldapCon));
// $data = ldap_get_entries($lotusCon, $result);
// echo count($data);
// echo "<pre>";
// print_r($data);
// echo '</pre>';
$extractions=getExtraction($pdoMag, 82);

	// echo "<pre>";
	// print_r($extractions);
	// echo '</pre>';


foreach ($extractions as $key => $extraction) {



	$name=explode('/',$extraction['contenu']);
	if (count($name)>1) {
		// echo $name[0];
			// echo "<pre>";
			// print_r($name);
			// echo '</pre>';

		$name=trim($name[0]);

		$result=ldap_search($lotusCon, $ldaptree, "(CN=*".$name."*)",$justThese);
		$data = ldap_get_entries($lotusCon, $result);
		// echo "<pre>";
		// print_r($data);
		// echo '</pre>';
		if(isset($data[0]['mailaddress'])){



			if(str_contains($data[0]['mailaddress'][0],'.leclerc')){
				// $found=strpos($data[0]['mailaddress'][0],".leclerc");
				echo "OUI pour " .$name ." REDIRECTION " .$data[0]['mailaddress'][0];
				echo "<br>";
			}else{
							// echo $data[0]['mailaddress'][0];
				// echo "<br>";
				//
			}
		}
	}


}


