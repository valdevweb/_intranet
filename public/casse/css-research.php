<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';



//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// $descr="saisie déclaration mag hors qlik" ;
// $page=basename(__file__);
// $action="";
// // addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
// addRecord($pdoStat,$page,$action, $descr, 208);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];




//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">
	<h1 class="text-main-blue py-5 ">Main title</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<div class="row">
		<div class="col">
			<img src="https://via.placeholder.com/150" class='polaroid'>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<img src="https://picsum.photos/600/200" class='circle'>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<img src="https://picsum.photos/200/100" class='realcircle'>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="button-border"><button class="button">Submit</button></div>
		</div>
	</div>
	<div class="custom-scrollbar">
		<p>
			Lorem ipsum dolor sit amet consectetur adipisicing elit.<br />
			Iure id exercitationem nulla qui repellat laborum vitae, <br />
			molestias tempora velit natus. Quas, assumenda nisi. <br />
			Quisquam enim qui iure, consequatur velit sit?
		</p>
	</div>


	<p class="custom-text-selection">Select some of this text.</p>

	<p class="etched-text">I appear etched into the background.</p>


	<img class="image image-contain" src="https://picsum.photos/600/200" />
	<img class="image image-cover" src="https://picsum.photos/600/200" />
	<div class="focus-within">
		<form>
			<label for="given_name">Given Name:</label> <input id="given_name" type="text" /> <br />
			<label for="family_name">Family Name:</label> <input id="family_name" type="text" />
		</form>
	</div>


	<p class="hover-underline-animation">Hover this text to see the effect!</p>
	<ul class="css-not-selector-shortcut">
		<li>One</li>
		<li>Two</li>
		<li>Three</li>
		<li>Four</li>
	</ul>

	<div class="row pb-3">
		<div class="col">

			<div class="shape-separator"></div>
		</div>
	</div>


	<div class="row pb-5">
		<div class="col">
			<div class="sibling-fade">
				<span>Item 1</span> <span>Item 2</span> <span>Item 3</span> <span>Item 4</span>
				<span>Item 5</span> <span>Item 6</span>
			</div>

		</div>
	</div>

<div class="row">
	<div class="col">
		<input type="checkbox" id="toggle" class="offscreen" /> <label for="toggle" class="switch"></label>
	</div>
</div>


	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>