<div class="row mt-3">
	<div class="col">
		<h5 class="khand text-main-blue pb-3">Informations :</h5>

	</div>
</div>


<div class="row">
	<div class="col bg-alert mr-3 border-kaki">
		<div class="row">
			<div class="col text-center"><img src="../img/litiges/ico-entrepot.png"></div>

		</div>
		<div class="row">
			<div class="col-5 text-kaki">Préparateur :</div>
			<div class="col "><?=$infos['fullprepa']?></div>
		</div>
		<div class="row">
			<div class="col-5 text-kaki">Date prépa :</div>
			<div class="col "><?=$infos['dateprepa']?></div>
		</div>
		<div class="row">
			<div class="col-5 text-kaki">Contrôleur :</div>
			<div class="col "><?=$infos['fullctrl']?></div>
		</div>
		<div class="row">
			<div class="col-5 text-kaki">Chargeur :</div>
			<div class="col "><?=$infos['fullchg']?></div>
		</div>
		<div class="row">
			<div class="col-5 text-kaki">Contrôle stock : </div>
			<div class="col "><?=$ctrl?></div>
		</div>
	</div>
	<div class="col bg-alert mr-3  border-yellow">
		<div class="row">
			<div class="col text-center"><img src="../img/litiges/ico-transp.png"></div>
		</div>
		<div class="row">
			<div class="col text-yellow">Transporteur :</div>
			<div class="col"><?=$infos['transporteur']?></div>
		</div>
		<div class="row">
			<div class="col text-yellow">Affreteur :</div>
			<div class="col"><?=$infos['affrete']?></div>
		</div>
		<div class="row">
			<div class="col text-yellow">Transité par :</div>
			<div class="col"><?=$infos['transit']?></div>
		</div>
	</div>
	<div class="col bg-alert border-reddish">
		<div class="row">
			<div class="col text-center"><img src="../img/litiges/ico-fact.png"></div>

		</div>
		<div class="row">
			<div class="col-8 text-red">Réglement transporteur :</div>
			<div class="col text-right"><?=number_format((float)$infos['mt_transp'],2,'.','')?>&euro;</div>
		</div>
		<div class="row">
			<div class="col-8 text-red">Réglement assurance :</div>
			<div class="col text-right"><?= number_format((float)$infos['mt_assur'],2,'.','')?>&euro;</div>
		</div>
		<div class="row">
			<div class="col-8 text-red">Réglement fournisseur :</div>
			<div class="col text-right"><?= number_format((float)$infos['mt_fourn'],2,'.','')?>&euro;</div>
		</div>
		<div class="row">
			<div class="col-8 text-red">Avoir magasin :</div>
			<div class="col text-right"><?= number_format((float)$infos['mt_mag'],2,'.','')?>&euro;</div>
		</div>
		<div class="row">
			<div class="col-8 text-red">Coût du litige :</div>
			<div class="col text-right"><?= number_format((float)$coutTotal,2,'.','') ?>&euro;</div>
		</div>

	</div>

</div>
<div class="bg-separation"></div>