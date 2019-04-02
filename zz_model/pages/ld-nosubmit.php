<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="form-inline">
				<select class="form-control" name='sav-ld' onchange='this.form.submit()'>
					<option value="">Sélectionner un SAV</option>
					<?php
					foreach ($ldSav as $sav)
					{
						echo '<option value="'.$sav['sav'].'"';
						if(isset($_POST['sav-ld']))
						{
							if($sav['sav']==$_POST['sav-ld'])
							{
								echo ' selected';
							}
						}
						echo '>'.$sav['sav'].'</option>';
					}

					?>
				</select>
				<noscript><input type="submit" value="Submit"></noscript>
			</form>