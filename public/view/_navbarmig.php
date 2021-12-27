<nav class="navbar navbar-expand-lg" >
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navid" aria-controls="navid" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse justify-content-md-center" id="navid">
		<ul class="navbar-nav">
			<?php if ($_SESSION['id_type']==6): ?>
				<li class="nav-item"><a class="nav-link" href="../home/home.php">Accueil</a></li>
				<li class="nav-item"><a class="nav-link" href="../home/logoff.php">Se déconnecter</a></li>
            <?php elseif($_SESSION['id_type']==10): ?>
                <li class="nav-item"><a class="nav-link" href="../home/home.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="../ecran/ecran-pilotage.php">Ecran de pilotage</a></li>

                <li class="nav-item"><a class="nav-link" href="../home/logoff.php">Se déconnecter</a></li>
            <?php elseif($_SESSION['id_type']==1): ?>
				<li class="nav-item"><a class="nav-link" href="../home/home.php">Accueil</a></li>
				<li class="nav-item"><a class="nav-link" href="../ecran/ecran-pilotage.php">Ecran de pilotage</a></li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdown08" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Exploit</a>
					<div class="dropdown-menu" aria-labelledby="dropdown08">
						<a class="dropdown-item " href="../exploit/exploit-main.php">Exploit pilotage</a>
						<a class="dropdown-item " href="../exploit/raq.php">RAQ</a>
						<a class="dropdown-item " href="../exploit/dispatch.php">Dispatch magasin</a>
						<a class="dropdown-item " href="../exploit/transporteur.php">Mail récap transporteurs</a>
						<a class="dropdown-item " href="../exploit/transport-log.php">Modifications transporteur</a>
                        <a class="dropdown-item " href="../exploit/gen-fichier-trans.php">Génération fichiers transporteur</a>
                        <a class="dropdown-item " href="../exploit/batch-survey.php">Batch pilotage</a>
						<a class="dropdown-item " href="<?=PORTAIL_BT?>">Portail BT</a>
						<a class="dropdown-item " href="<?=PORTAIL_FOU_HOME?>">Portail Fournisseur</a>
					</div>
				</li>
                <li class="nav-item"><a class="nav-link" href="../home/logoff.php">Se déconnecter</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="../home/home.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="../home/logoff.php">Se déconnecter</a></li>
            <?php endif ?>


		</ul>
	</div>
</nav>
