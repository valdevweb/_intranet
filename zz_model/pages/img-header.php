.bg-img{
	width: 100%;
	height: 100%;
	position: relative;
	display: block;
	/* background: red; */
}

.bg-img::after{
	content: "";
	background-image: url('../img/ORIGINAUX/chat.jpg') !important;
	/* background-repeat: no-repeat !important; */
	opacity: 0.3;
	position: absolute;
	top: 0;
	left: 0;
		width: inherit;
	height: inherit;
}
h1{
	text-shadow: 2px 2px #ccc;
}





<div class="container">
	<div class="row">
		<div class="col">
			<div class="bg-img">
				<h1 class="text-center py-5 underline-anim text-shadow">Demandes en attente de réponse</h1>
			</div>
		</div>
	</div>
