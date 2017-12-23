
<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
else {
	//echo "vous êtes connecté avec :";
	//
	}

// appel au(x) fichier(s) de fonction
require_once '../../functions/form.fn.php';

include('../../config/nav.fn.php');

include ('../view/_head.php');


echo "<div id='cssmenu'>";

echo afficher_menu(0,0,$associative);
echo "</div>";


include('contact.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer.php');






