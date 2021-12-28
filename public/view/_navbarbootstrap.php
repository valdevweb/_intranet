<nav class="navbar navbar-expand-lg navbar-dark bg-main">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarToggler">
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0">

<!-- CONTENU -->


			<!-- LIEN SIMPLE -->
			<li class="nav-item">
				<a class="nav-link" href="#">SINGLELINK</a>
			</li>
			<!-- many link -->
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#LIEN" id="ONE" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					ENTETE
				</a>
				<ul class="dropdown-menu" aria-labelledby="ONE">
					<li><a class="dropdown-item" href="#LIEN">NIV1</a></li>
					<li><a class="dropdown-item" href="#LIEN">NIV1</a></li>
				</ul>
			</li>
			<!-- DROPDOWN ONE LEVEL -->
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="http://example.com" id="TWO" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					ENTETE
				</a>
				<ul class="dropdown-menu" aria-labelledby="TWO">
					<li><a class="dropdown-item" href="#">NIV1</a></li>
					<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">NIV1</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="#">NIV2</a></li>
							<li><a class="dropdown-item" href="#">NIV2</a></li>

						</ul>
					</li>
				</ul>
			</li>

			<!-- dropdown many -->
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="http://example.com" id="THREE" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					DROPDOWN 2 LEVEL
				</a>
				<ul class="dropdown-menu" aria-labelledby="THREE">
					<li><a class="dropdown-item" href="#">ENTETE</a></li>
					<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">NIV1</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="#">NIV2</a></li>
							<li><a class="dropdown-item" href="#">NIV2</a></li>
							<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">NIV2</a>
								<ul class="dropdown-menu">
									<li><a class="dropdown-item" href="#">NIV3</a></li>
									<li><a class="dropdown-item" href="#">NIV3</a></li>
								</ul>
							</li>
							<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">NIV2</a>
								<ul class="dropdown-menu">
									<li><a class="dropdown-item" href="#">NIV3</a></li>
									<li><a class="dropdown-item" href="#">NIV3</a></li>
								</ul>
							</li>
						</ul>
					</li>
				</ul>
			</li>
<!-- FIN CONTENU -->

		</ul>
	</div>
</nav>

