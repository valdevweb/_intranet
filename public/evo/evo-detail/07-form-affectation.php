<div class="row">
	<div class="col mb-3 text-orange text-center sub-title font-weight-bold ">
		Affecter la demande :
	</div>
</div>

<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">


	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="users">A des personnes :</label>
				<select class="form-control" name="users[]" id="users" multiple>
					<option value="">Sélectionner</option>
					<?php foreach ($listUsers as $key => $user): ?>
						<option value="<?=$user['id']?>"><?=$user['fullname']?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label for="services">A des personnes par services : </label>
				<select class="form-control" name="services[]" id="services" multiple>
					<option value="">Sélectionner</option>
					<?php foreach ($listServices as $key => $service): ?>
						<option value="<?=$service['id']?>"><?=$service['service']?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col text-right">
			<button class="btn btn-orange" name="add_affectation">affecter</button>

		</div>
	</div>


</form>
