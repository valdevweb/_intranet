<?php


require('../../../config/autoload.php');

require '../../../Class/Db.php';
require '../../../Class/CrudDao.php';
require '../../../Class/MailDao.php';
require '../../../Class/UserDao.php';


$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoMag=$db->getPdo('magasin');

$crudMag=new CrudDao($pdoMag);


$mailDao=new MailDao($pdoMag);
$userDao=new UserDao($pdoUser);


$magByBtlec=[];
$emailsMag=[];
$mailList="";
$resultArray=[];
$resultIndex=0;

function addBold($name){
	$result="";
	$pattern=$_POST['search'];
	// echo $name;
	$strPos=strpos($name, $pattern);
	$length=strlen($pattern);

	if($strPos!=0){
		$start=substr($name,0,$strPos);
		$bold=substr($name,$strPos,$length);
		$end=substr($name, $strPos+$length);
		$result=$start.'<b>'.$bold.'</b>'.$end;

	}else{
		$bold=substr($name,0,$length);
		$end=substr($name, $strPos+$length);
		$result='<b>'.$bold.'</b>'.$end;
	}
	return $result;
}

if(isset($_POST['search'])){
	$patternCodeBt="/^\d+/";
	if(preg_match($patternCodeBt,$_POST['search'])){
		$magByBtlec=$mailDao->searchLdByField( "btlec", substr($_POST['search'], 0, 4));
	}else{
		// on cherche dans le champ nom des listes de diffu
		$magByName=$mailDao->searchLdByField( "name", $_POST['search']);
		$emailsMag=$mailDao->searchEmailLike( $_POST['search']);
		$emailsIntern=$userDao->searchEmailInternByEmail($_POST['search']);


		// on cherche dans le champ email de la table emails magasin


		// if(empty($magByBtlec) && str_contains($_POST['search'], '@')){
		// }

	}
// 	// assembler dans un tableau pour afficher toutes les possibilit"é

	if (!empty($magByBtlec)) {
		foreach ($magByBtlec as $key => $mag) {
			$name=$mag['btlec'].$mag['suffixe'];
			$name=addBold($name);
			$resultArray[$resultIndex]['email']=$mag['btlec'].$mag['suffixe'];
			$resultArray[$resultIndex]['name']=$name;
			$resultIndex++;
		}
	}
	if (!empty($magByName)) {
		foreach ($magByName as $key => $mag) {
			$name=$mag['name'].$mag['suffixe'];
			$name=addBold($name);
			$resultArray[$resultIndex]['email']=$mag['name'].$mag['suffixe'];
			$resultArray[$resultIndex]['name']=$name;
			$resultIndex++;
		}
	}
	if (!empty($emailsMag)) {
		foreach ($emailsMag as $key => $mag) {
			$resultArray[$resultIndex]['email']=$mag['email'];
			$name=explode("@",$mag['email']);
			$name=str_replace(".", " ", $name[0]);
			$name=addBold($name);
			$resultArray[$resultIndex]['name']=$name;
			$resultIndex++;
		}
	}

	if (!empty($emailsIntern)) {
		foreach ($emailsIntern as $key => $intern) {
			$name=$intern['email'];
			$name=addBold($name);
			$resultArray[$resultIndex]['email']=$name;
			$resultArray[$resultIndex]['name']=$intern['prenom'] . ' '.$intern['nom'];
			$resultIndex++;
		}
	}

	if(!empty($resultArray)){
		foreach ($resultArray as $key => $result) {
			$mailList.= '<a class="'.$_POST['link_name'].'" href="#id-ld-'.$key.'"  data-email="'.$result['email'].'" data-name="'.$result['name'].'">'.$result['name']." : ".$result['email']. '</a><br>';
		}

	}else{
		$patternEmail="/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/";

		if(preg_match($patternEmail, $_POST['search'])){
			$notFound=$_POST['search'];
		}
		if(isset($notFound)){
			$mailList.= 'Adresse valide<br>Ajouter l\'adresse : <a class="'.$_POST['link_name'].'" href="#id-ld-0"  data-email="'.$_POST['search'].'" data-name="'.$_POST['search'].'">'.$_POST['search']. '</a><br>';
		}
	}
	if($mailList!=""){
		echo "<div class=' p-2 alert-".$_POST['link_name']."'>Résultats pour ".$_POST['search']." :<span style='float: right'><i class='far fa-times-circle' id='close-".$_POST['link_name']."'></i></span><br>".$mailList."</div>";
	}else{
		echo "<div class='p-2 alert-".$_POST['link_name']."'>Résultats pour ".$_POST['search']." :<span style='float: right'><i class='far fa-times-circle' id='close-".$_POST['link_name']."'></i></span><br>adresse non valide</div>";

	}

}


