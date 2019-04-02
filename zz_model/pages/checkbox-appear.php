<!-- transit oui/non -->
							<div class="col">
								Passage à quai :
								<div class="form-check pt-3">
									<?php
									$isChecked="";
									$phpClass="hidden";
									if(isset($fLitige['transitok']) && $fLitige['transitok']=="oui")
									{
										$isChecked="checked";
										$phpClass="show";
									}

									?>
									<input class="form-check-input" type="checkbox" value="" id="transit_check" name="transit_check"  <?= $isChecked ?>>
									<label class="form-check-label" for="transit_check">Oui</label>
								</div>
							</div>

							<div class="col">
								<div class="<?=$phpClass?>" id="toogle_transit">
									<div class="form-group" >
										<label>Transit : </label>
										<select class="form-control" name="transit">
											<option value="">Sélectionner</option>
											<?php
											foreach($transits as $transit)
											{
												echo '<option value="'.$transit['id'].'"';
												if(isset($fLitige['id_transit']) && $fLitige['id_transit']==$transit['id'])
												{
													echo ' selected';
												}
												echo '>'.$transit['transit'].'</option>';
											}

											?>

										</select>
									</div>
								</div>
							</div>




							<script type="text/javascript">
	$(document).ready(function(){
		$('#transit_check').change(function(){
			if($(this).prop("checked")) {
				// $('#toogle_transit').show();
				$('#toogle_transit').attr('class','show');

			} else {
				$('#toogle_transit').attr('class', 'hidden');
			}
		});
	});
</script>



.hidden{
	display: none;
}
.show{
	display: block;
}


							<script type="text/javascript">

$(document).ready(function()
		{
			//creation de l input pour le fournisseur
			var inputOtherFou='<div class="form-group"><label for="other_fou">Autre fournisseur</label><input class="form-control"  name="other_fou" id="other_fou" type="text"  required="require" value=""></div>';
			// Function to get selected value
			$('#fou_select').click(function() {
				var value = $("#fou_select option:selected").val();
				if(value=="autre")
				{
					$('#other').append(inputOtherFou);
				}
				else
				{
					$('#other').empty();
				}
			});
		});

</script>
