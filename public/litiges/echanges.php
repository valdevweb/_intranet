<?php

foreach ($dials as $dial)
				{
					if(!empty($dial['msg']))
					{
						if($dial['mag']==1)
						{
							$name=$infoMag['mag'];
							$type='bg-kaki-light';

						}
						else
						{
							$infoBt=getBtName($pdoBt, $dial['id_web_user']);
							$name=$infoBt['name'];
							$type='bg-alert-primary';
						}
						if($dial['filename']!='')
						{
							$pj=createFileLink($dial['filename']);
						}
						else
						{
							$pj='';
						}
	// conteneur
						echo '<div class="row alert '.$type.' mb-5">';
						echo '<div class="col">';
// ligne 1
						echo '<div class="row heavy">';
						echo '<div class="col">';
						echo $name;
						echo '</div>';

						echo '<div class="col">';
						echo '<div class="text-right"><i class="far fa-calendar-alt pr-3"></i>'.$dial['dateFr'].'<i class="far fa-clock px-3"></i>'.$dial['heure'].'</div>';
						echo '</div>';

						echo '</div>';
// ligne 2
						echo '<div class="row ">';
						echo '<div class="col">';
						echo $dial['msg'];
						echo '</div>';
						echo '<div class="col-auto">';
						echo $pj;
						echo '</div>';
						echo '</div>';

						echo '</div>';
						echo '</div>';

					}
				}





 ?>