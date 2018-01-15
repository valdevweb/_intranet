
<div id='cssmenu'>
	<ul>
			<!-- #0d47a1 blue darken-4 -->
			<!-- <a href="" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Accueil"><i class="fa fa-home fa-2x" aria-hidden="true"></i></a> -->
			<li><a class="less-padding"  href='<?= ROOT_PATH ?>/public/home.php' data-tooltip="Accueil"><span><i class="fa fa-home fa-2x" aria-hidden="true"></i></span></a></li>
			<!-- sous menu 1 -->
			<?php

				//-----------------------------------------------------------------------------------------------------------------
				//							menu pour les magasins
				//-----------------------------------------------------------------------------------------------------------------

		 		ob_start();
			?>
			<li><a href="<?= ROOT_PATH?>/public/mag/histo.php"><span>Vos demandes</span></a></li>
			<li class='active has-sub'><a href='#'><span>Contacter nos services</span></a>
				 <ul>
						<!-- sous menu niv 1 -->
						<li data-module="7"><a href="<?= $contact?>gt=brun ">achats brun</a></li>
						<li data-module="8"><a href="<?= $contact?>gt=gris ">achats gris</a></li>
						<li data-module="9"><a href="<?= $contact?>gt=pemgem ">achats PEM/GEM</a></li>
						<li data-module="16"><a href="<?= $contact?>gt=compta ">comptabilité</a></li>
						<li data-module="10"><a href="<?= $contact?>gt=comm ">communication</a></li>
						<li data-module="11"><a href="<?= $contact?>gt=dir ">direction</a></li>
						<li data-module="12"><a href="<?= $contact?>gt=dircom ">direction commerciale</a></li>
						<li data-module="13"><a href="<?= $contact?>gt=informatique ">exploitation informatique</a></li>
						<li data-module="14"><a href="<?= $contact?>gt=logistique ">logistique</a></li>
						<li data-module="17"><a href="<?= $contact?>gt=litige">litiges livraison</a></li>
						<li data-module="15"><a href="<?= $contact?>gt=rh">social</a></li>
						<li data-module="17"><a href="<?= $contact?>gt=qual">qualité</a></li>
				 </ul>
			 </li>
			 <?php
			 	$mag=ob_get_contents();
			 	ob_end_clean();
				//-----------------------------------------------------------------------------------------------------------------
			 	//						fin menu pour les magasin
				//-----------------------------------------------------------------------------------------------------------------

			 	//-----------------------------------------------------------------------------------------------------------------
				//							menu pour le mag de test
				//-----------------------------------------------------------------------------------------------------------------

			 	ob_start();
			 ?>
			<li><a href="<?= ROOT_PATH?>/public/mag/histo.php"><span>Vos demandes</span></a></li>
			<li class='active has-sub'><a href="#"><span>Contacter nos services</span></a>
				 <ul>
						<!-- sous menu niv 1 -->
						<li data-module="7"><a href="<?= $contact?>gt=brun ">achats brun</a></li>
						<li data-module="8"><a href="<?= $contact?>gt=gris ">achats gris</a></li>
						<li data-module="9"><a href="<?= $contact?>gt=pemgem ">achats PEM/GEM</a></li>
						<li data-module="16"><a href="<?= $contact?>gt=compta ">comptabilité</a></li>
						<li data-module="10"><a href="<?= $contact?>gt=comm ">communication</a></li>
						<li data-module="11"><a href="<?= $contact?>gt=dir ">direction</a></li>
						<li data-module="12"><a href="<?= $contact?>gt=dircom ">direction commerciale</a></li>
						<li data-module="13"><a href="<?= $contact?>gt=informatique ">exploitation informatique</a></li>
						<li data-module="14"><a href="<?= $contact?>gt=logistique ">logistique</a></li>
						<li data-module="17"><a href="<?= $contact?>gt=litige">litiges livraison</a></li>
						<li data-module="15"><a href="<?= $contact?>gt=rh">social</a></li>
						<li data-module="17"><a href="<?= $contact?>gt=qual">qualité</a></li>
						<li data-module="17"><a href="<?= $contact?>gt=test">test</a></li>

				 </ul>
			 </li>
			<?php
			 	$magtest=ob_get_contents();
			 	ob_end_clean();
			 	//-----------------------------------------------------------------------------------------------------------------
			 	//						fin menu pour le mag test
				//-----------------------------------------------------------------------------------------------------------------

			 	//-----------------------------------------------------------------------------------------------------------------
			 	//						menu pour le bt
				//-----------------------------------------------------------------------------------------------------------------

			 	ob_start();
     	 	 ?>

			 <li class='active has-sub'><a href="<?= ROOT_PATH?>/public/btlec/dashboard.php"><span>Demandes magasin</span></a>
				 <ul>
					 <li><a href="<?= ROOT_PATH?>/public/btlec/dashboard.php">En attente</a></li>
					 <li> <a href="<?= ROOT_PATH?>/public/btlec/histo.php">Clôturées</a></li>

				 </ul>
			 </li>

			 <?php
				 $bt=ob_get_contents();
				 ob_end_clean();
				 //-----------------------------------------------------------------------------------------------------------------
			 	//						fin menu pour le bt
				//-----------------------------------------------------------------------------------------------------------------


				 //affichage du tampon en fonction du type de sessions
				 if($_SESSION['id']==980)
				 {
				 	echo $magtest;
				 }
				 elseif($_SESSION['type']=="mag" || $_SESSION['type']=="scapsav" ||$_SESSION['type']=="")
				 {
				 	echo $mag;
				 }
				 elseif ($_SESSION['type']=="btlec")
				 {
				 	echo $bt;
				 }
				 else
				 {
					//sinon rien !!!
				 }
			?>
			<!-- section sans sous menu -->
			<li><a href="<?= ROOT_PATH. '/public/entrepot/discover.php'?>" class="tooltipped" data-position="bottom" data-tooltip="Visitez l'entrepôt"><span>Entrepôt</span></a></li>

			<li  class='active has-sub'><a href="<?= ROOT_PATH. '/public/gazette/gazette.php'?>" >La gazette</a>
		 <?php ob_start(); ?>

				<ul>
					<!-- <li><a href=" ROOT_PATH. '/public/gazette/gazette.php'?>"></a></li> -->
					<li><a href="<?= ROOT_PATH. '/public/gazette/upload-gazette.php'?>">Ajouter une gazette</a></li>
				</ul>
		<?php
			$bt=ob_get_contents();
			ob_end_clean();
			echo ($_SESSION['type']=="btlec") ? $bt : '' ;
		 ?>
			</li>

			<li><a href="http://172.30.92.53/scapsav/intranet/magasin.php" class="tooltipped" data-position="bottom" data-tooltip="aller sur le site scapsav">Site Scapsav</a></li>
			<!-- <li><a href="http://site.scapsav.fr/sitescapsavdev/intranet/magasin.php" class="tooltipped" data-position="bottom" data-tooltip="aller sur le site scapsav">Site Scapsav</a></li> -->

			<!-- profile -->
			<li><a href='<?= ROOT_PATH?>/public/user/profil.php' class="tooltipped" data-position="bottom" data-tooltip="Votre compte"><span><i class="fa fa-user"></i></span></a></li>
			<li><a href="<?= ROOT_PATH ?>/public/logoff.php" class="tooltipped" data-position="bottom" data-tooltip="se déconnecter"><i class="fa fa-power-off"></i></a></li>
	 </ul>

</div>

