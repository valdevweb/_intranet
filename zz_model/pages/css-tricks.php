<?php

include('../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. AUTHENTIFICATION);

}
//----------------------------------------------------------------
//		INCLUDES
//----------------------------------------------------------------




//----------------------------------------------
// css dynamique
//----------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile="../css/".$page.".css";

//----------------------------------------------
//  		FUNCTIONS
//----------------------------------------------


include('../view/_head.php');
include('../view/_navbar.php');
?>

<div class="container py-5">
	<!-- main title -->
	<div class="row">
		<div class="col">
			<h1 class="text-center underline-anim mt-5">CSS tricks and ideas</h1>
		</div>
	</div>
	<!-- ./main title -->
	<!-- start row -->
	<div class="row my-5">
		<div class="col-lg-1 col-xl-2"></div>
		<div class="col">
			<h4>Pulsing</h4>
			<div class="text-blue text-center"><i class="fas fa-hourglass-half fa-lg circle-icon light-shadow"></i></div>
			<div class="text-blue text-center"><i class="fas fa-hourglass-half fa-lg circle-icon-color light-shadow"></i></div>

		</div>
		<div class="col-lg-1 col-xl-2"></div>
	</div>
	<!-- ./row -->

	<!-- start row -->
	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col">
			<p><span class="step">1</span>One</p>
			<p><span class="step">2</span>Two</p>
			<p class="cut">ce text devrai être coupé et terminé par trois petits points grace au css</p>
			<p><a href="" class="link">un lien adoucit !!</a></p>
			<p><a href="" class="bg">un lien adoucit !!</a></p>
			<p><a href="" class="side">un lien adoucit !!</a></p>

			<div class="example">
				<p>Hover on the link below:</p>
				<a href="" class="underline">This is a link</a>
			</div>
			<div class="example">
				<a href="" class="underline">This is another link but<br/> it's split over two lines</a>
			</div>
			<h1 class="bb">Big border ??</h1>

			<p class="mt-5"><img src="../img/contact/new_phone.jpg" class="polaroid"></p>

			<p>test de & <span class="amp">&  bof</span></p>
			<p class="first">Hello you !<br>
				attention, on utilise un float pour faire descendre la lettre.Elle descen un peu trop !!! <br>
				Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>


		</p>
<img src="file.svg">

	</div>
	<div class="col-lg-1 col-xxl-2"></div>
</div>
<!-- start row -->
<div class="row">
	<div class="col-lg-1 col-xxl-2"></div>
	<div class="col">
	<div class="gr-bg">test</div>
	</div>
	<div class="col">
		<div class="gr-bg2"></div>
	</div>
	<div class="col-lg-1 col-xxl-2"></div>
</div>
<!-- ./row -->
<!-- start row -->
<div class="row my-3">
	<div class="col-lg-1 col-xxl-2"></div>
	<div class="col-3"></div>
	<div class="col" id="inner-shadow">Ombre interne</div>

	<div class="col-3"></div>
	<div class="col-lg-1 col-xxl-2"></div>
</div>

<div class="row my-3">
	<div class="col-lg-1 col-xxl-2"></div>
	<div class="col-3"></div>
	<div class="col" id="outer-shadow">Ombre externe</div>

	<div class="col-3"></div>
	<div class="col-lg-1 col-xxl-2"></div>
</div>
<!-- start row -->
<div class="row mb-5">
	<div class="col-lg-1 col-xxl-2"></div>
	<div class="col">
		<div class="graybtn">button</div>

	</div>
	<div class="col-lg-1 col-xxl-2"></div>
</div>
<!-- ./row -->

<div class="row rowheight">
	<div class="col-lg-1 col-xxl-2"></div>

	<div class="col">
		<div class="box">
			<div class="ribbon">
				<a href="">lien</a>
			</div>
		</div>
	</div>
</div>
<!-- start row -->
<div class="row">
	<div class="col-lg-1 col-xxl-2"></div>
	<div class="col">
	<ul class="curl">
		<li>un</li>
		<li>deux </li>
		<li>trois</li>
	</ul>
	<div class="featureBanner">bannière</div>
		<p class="blurry-text">es tu flou ?</p>
	</div>
	<div class="blog-card">
		<p>Text dans  une carte</p>
	</div>
	<div class="col-lg-1 col-xxl-2"></div>
</div>
<!-- ./row -->
<!-- start row -->
<div class="row mt-5">
	<div class="col-lg-1 col-xxl-2"></div>
	<div class="col">
		<p>assombrir une image pour que le texte soit lisible</p>
		<p>//Black overlay - 0.5 opacity</p>
		<p>background: rgba(0,0,0,0.5);</p>

		<p>//White overlay - 0.2 opacity</p>
		<p>background: rgba(255,255,255,0.2);</p>
		<div class="image-container">
			<img src="https://source.unsplash.com/daily" class="img">
			<div class="text-container">
				<div class="inner">
					<h1 class="text-white">Title</h1>
					<p class="text-white">This is some text</p>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-1 col-xxl-2"></div>
</div>
<!-- ./row -->


</div>


<?php
include('../view/_footer.php');
?>


