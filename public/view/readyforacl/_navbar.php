<?php


//http://www.coinduwebmaster.com/menu-arborescent-fonction-recursive-php/89/
/// recup données de la table menu
/* CONNEXION BASE BTLEC */
function getNav() {
	    $host='localhost';
		$username='sql';
		$pwd='User19092017+';
		$database='_btlec';

	try {
		$pdo=new PDO("mysql:host=$host;dbname=$database", $username, $pwd);

	}

		 	catch(Exception $e)
	     	{
	         	die('Erreur : '.$e->getMessage());
	         	echo "dead";
	      	}
 	return  $pdo;

}

// $pdoUser=getWebUserLink();
$pdoBt=getNav();

function menu($pdoBt)
{
	$req=$pdoBt->prepare('SELECT* FROM menu ORDER BY sort ASC, parent DESC, label');
	$req ->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);

}
$menu=menu($pdoBt);

// construction d'un tableau associatif
foreach ($menu as $key => $item) {

	$associative[]=array(
		'id'				=>$item['id'],
		'label'				=>$item['label'],
		'icon'				=>$item['icon'],
		'link'				=>$item['link'],
		'ext_link'			=>$item['ext_link'],
		'query_strg'		=>$item['query_strg'],
		'class_li'			=>$item['class_li'],
		'class_a'			=>$item['class_a'],
		'data-tooltip'		=>$item['data-tooltip'],
		'parent_id'			=>$item['parent']
	);
}



//			<li><a href='<?= ROOT_PATH>/public/user/profil.php' class="tooltipped" data-position="bottom" data-tooltip="Votre compte"><span><i class="fa fa-user"></i></span></a></li>

function exist($value,$type)
{

	if(empty($value))
	{
		$html="";
	}
	else
	{
		switch ($type) {
			case 'label':
			$html=$value ."</a>";
			break;

			case 'icon':
			$html="<i class='".$value." aria-hidden='true'></i></a>";
			break;

			case 'class_li':
			$html=" class='".$value."'";

			break;

			case 'class_a':
			$html= " class='". $value ."'";

			break;
			case 'data-tooltip':
			$html=" data-tooltip='".addslashes($value)."' data-position='bottom'";

			break;


		}
	}

	return $html;

}


function extLink($value)
{
	if($value=="http://scapsav.fr/")
	{
		$html=$value;
	}
else
{
	$html=ROOT_PATH .$value;

}
return $html;

}


function afficher_menu($parent, $level, $menu) {
	$html = "";
	$formerLevel = 0;
	if (!$level && !$formerLevel) $html .= "<ul>";
	foreach ($menu as $key => $item) {
		if ($parent == $item['parent_id']) {
			if ($formerLevel < $level) $html .= "<ul>";
			$html .= "<li". exist($item['class_li'],'class_li'). exist($item['data-tooltip'],'data-tooltip')."><a href='".extLink($item['link']).$item['query_strg']."'".exist($item['class_a'],'class_a').">". exist($item['icon'], 'icon') . exist($item['label'],'label');
			$formerLevel = $level;
			$html .= afficher_menu($item['id'], ($level + 1), $menu);
		}
	}

	if (($formerLevel == $level) && ($formerLevel != 0)) $html .= "</ul></li>";
	else if ($formerLevel == $level) $html .= "</ul>";
	else $html .= "</li>";

	return $html;

}


echo "<div id='cssmenu'>";

echo afficher_menu(0,0,$associative);
echo "</div>";