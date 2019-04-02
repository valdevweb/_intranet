<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		.colonne {
			margin: 15px 25px 0 25px;
			padding: 0;
		}
		.colonne:last-child {
			padding-bottom: 60px;
		}
		.colonne::after {
			content: '';
			clear: both;
			display: block;
		}
		.colonne div {
			position: relative;
			float: left;
			width: 200px;
			height: 150px;
			margin: 0 0 0 35px;
			padding: 0;
		}
		.colonne div:first-child {
			margin-left: 0;
		}
		div {
			width: 200px;
			height: 150px;
			margin: 0;
			padding: 0;
			background: #F4F4F4;
			overflow: hidden;}

/* effects */
.zoom div img {
	-webkit-transform: scale(1);
	transform: scale(1);
	-webkit-transition: .3s ease-in-out;
	transition: .3s ease-in-out;
}
.zoom div:hover img {
	-webkit-transform: scale(1.3);
	transform: scale(1.3);
}
/* Dézoomer */
.zoom-out div img {
	-webkit-transform: scale(1.25);
	transform: scale(1.25);
	-webkit-transition: .3s ease-in-out;
	transition: .3s ease-in-out;
}
.zoom-out div:hover img {
	-webkit-transform: scale(1);
	transform: scale(1);
}



.rotate div img {
  -webkit-transition: all 0.5s ease;
  transition: all 0.5s ease;
}

.rotate div:hover img {
  -webkit-transform: rotate(-15deg);
  transform: rotate(-15deg);
}

/* Image ronde - il faut une img carré au départ */
.rounded div img {
  width: 200px; /* largeur de l'image */
  height: auto; /* hauteur de l'image */
  -webkit-transition: .3s ease-in-out !important;
  transition: .3s ease-in-out !important;
}
.rounded div:hover img:hover {
  width: 150px; /* on affiche l'image au carré */
  height: 150px;
  border-radius: 50%;
}


.slide div img {
	margin-left: 0px;
	-webkit-transition: .3s ease-in-out;
	transition: .3s ease-in-out;
}
.slide div:hover img {
	margin-left: -30px;
}

/* Rotation et dézoome */
.rotate-zoom-out div img {
	-webkit-transform: rotate(10deg) scale(1.25);
	transform: rotate(10deg) scale(1.25);
	-webkit-transition: .3s ease-in-out;
	transition: .3s ease-in-out;
}
.rotate-zoom-out div:hover img {
	-webkit-transform: rotate(0) scale(1);
	transform: rotate(0) scale(1);
}

/* Flou */
.blur div img {
	-webkit-filter: blur(3px);
	filter: blur(3px);
	-webkit-transition: .3s ease-in-out;
	transition: .3s ease-in-out;
}
.blur div:hover img {
	-webkit-filter: blur(0);
	filter: blur(0);
}

/* Noir et blanc  on peut remplacer par sepia*/
.grayscale div img {
	-webkit-filter: grayscale(100%);
	filter: grayscale(100%);
	-webkit-transition: .3s ease-in-out;
	transition: .3s ease-in-out;
}
.grayscale div:hover img {
	-webkit-filter: grayscale(0);
	filter: grayscale(0);
}


/* Morph */
.morph div img {
  width: 200px;
  height: 150px;
  -webkit-filter: grayscale(0) blur(0px);
  filter: grayscale(0) blur(0px);
  -webkit-transition: all 0.5s ease;
  transition: all 0.5s ease;
}

.morph div:hover img {
  width: 150px; /* on affiche l'image au carré */
  height: 150px;
  border-radius: 50%;  /* on arrondit l'image */
  -webkit-transform: rotate(360deg); /* rotation de l'image */
  transform: rotate(360deg);
}

/* Opacité */
.opacity1 div img {
	opacity: 1;
	-webkit-transition: .3s ease-in-out;
	transition: .3s ease-in-out;
}
.opacity1 div:hover img {
	opacity: .5;
}


.opacity-color div {
background: #184a7d;
}
.opacity-color div img {
	opacity: 1;
	-webkit-transition: .3s ease-in-out;
	transition: .3s ease-in-out;
}
.opacity-color div:hover img {
	opacity: .5;
}




/* Halo lumineux */
.light div {
	position: relative;
}
.light div::before {
	position: absolute;
	top: 50%;
	left: 50%;
	z-index: 2;
	display: block;
	content: '';
	width: 0;
	height: 0;
	background: rgba(255,255,255,.2);
	border-radius: 100%;
	-webkit-transform: translate(-50%, -50%);
	transform: translate(-50%, -50%);
	opacity: 0;
}
.light div:hover::before {
	-webkit-animation: circle .75s;
	animation: circle .75s;
}
@-webkit-keyframes circle {
	0% {
		opacity: 1;
	}
	40% {
		opacity: 1;
	}
	100% {
		width: 200%;
		height: 200%;
		opacity: 0;
	}
}
@keyframes circle {
	0% {
		opacity: 1;
	}
	40% {
		opacity: 1;
	}
	100% {
		width: 200%;
		height: 200%;
		opacity: 0;
	}
}

		</style>
		<title>Document</title>
	</head>
	<body>

	</body>
	</html>


	<?php

// https://www.web-eau.net/blog/15-effets-css3-pour-vos-images

	?>


	<div class="zoom colonne">
		<div>
			<div><img src="images/img1.jpg" /></div>
		</div>
		<div>
			<div><img src="images/img2.jpg" /></div>
		</div>
		<div>
			<div><img src="images/img3.jpg" /></div>
		</div>
	</div>


	<p>Zoom - très bof</p>