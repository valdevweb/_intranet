<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

// include('functions/utilities.php');

function getPresDoc($pdoBt){
	$req=$pdoBt->prepare("SELECT * FROM pres_files WHERE id_pres= :id_pres ORDER BY ordre ASC");
	$req->execute(array(
		':id_pres'	=> $_GET['id_pres']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// SELECT document,  `filename`,`ordre` FROM doc_type RIGHT JOIN documents ON doc_type.id=id_doc_type WHERE id_conseil=5
}


if(isset($_GET['id_pres']) && isset($_GET['iddoc'])){
	// récup les infos en fonction des gets
	$list=getPresDoc($pdoBt);

	if(empty($list)){
		$content="<p class='text-center'>Aucun document n'a encore été ajouté à cette présentation</p>";
		$nextBtn='';
		$prevBtn='';
	}
	else{
		// reconstruction du tableau pour ne pas avoir de trou dans la numérotation des doc
		 $newIndex=0;
		 foreach ($list as $value){
		 	$listDoc[$newIndex]['pdf']=$value['pdf'];
		 	$listDoc[$newIndex]['ofile']=$value['ofile'];
		 	$newIndex++;
		 }
		 $max=sizeof($listDoc);
		 $i=$_GET['iddoc'];

		/*-----------------------------------------------------------
			BTN PRECEDENT ET SUIVANT DIFFERENT SI 1ER OU DERNIERE DIAPO
			---------------------------------------------------------*/
		//si un seul doc
			if($i==0 && $i==$max-1)		 {
				$nextBtn='';
				$prevBtn='<a href="home.php" class="btn-nav"><i class="fas fa-arrow-alt-circle-left fa-2x"></i></a>';

			}
		 // si 1er diapo
			elseif ($i==0){
				$next= $i + 1;
				$nextBtn='<a href="display-pres.php?id_pres='.$_GET['id_pres'].'&iddoc='.$next.'" class="btn-nav"><i class="fas fa-arrow-alt-circle-right fa-2x"></i></a>';
				$prevBtn='<a href="home-pres.php" class="btn-nav"><i class="fas fa-arrow-alt-circle-left fa-2x"></i></a>';
			}
		// si der diapo
			elseif ($i==$max-1){
				$prev= $i -1;
			//si dernière diapo, btn avancer fait retourner à l'accueil
				$nextBtn='<a href="home-pres.php" class="btn-nav"><i class="fas fa-arrow-alt-circle-right fa-2x"></i></a>';
				$prevBtn='<a href="display-pres.php?id_pres='.$_GET['id_pres'].'&iddoc='.$prev.'" class="btn-nav"><i class="fas fa-arrow-alt-circle-left fa-2x"></i></a>';

			}
			else{

				$next= $i+1;
				$prev= $i -1;
				$nextBtn='<a href="display-pres.php?id_pres='.$_GET['id_pres'].'&iddoc='.$next.'" class="btn-nav"><i class="fas fa-arrow-alt-circle-right fa-2x"></i></a>';
				$prevBtn='<a href="display-pres.php?id_pres='.$_GET['id_pres'].'&iddoc='.$prev.'" class="btn-nav"><i class="fas fa-arrow-alt-circle-left fa-2x"></i></a>';
			}
		/*-----------------------------------------------------------
			CREATION DU CONTENU
			---------------------------------------------------------*/


			if(empty($listDoc) || !file_exists(DIR_UPLOAD.'pres\\'.$listDoc[$_GET['iddoc']]['pdf'])){
				$content="Ce document a été supprimé";

			}
			else{

				$document=URL_UPLOAD.'/pres/'.$listDoc[$_GET['iddoc']]['pdf'];
				$row='<div class="row"><div class="col">';
				$rowEnd='</div></div>';
				$content=$row.'<iframe src="'.$document.'?#view=FitH"  height="100%" width="100%"></iframe>'.$rowEnd;
			}
		}
	}
include('head_pres.php');

	?>

	<div class="container-fluid">

		<!-- top nav -->
		<nav>
			<span class="checkbox-container">
				<input class="checkbox-trigger" type="checkbox"  />
				<span class="menu-content">
					<ul>

						<?php
						$i=0;
						foreach ($listDoc as $value)
						{
							echo '<li><a href="display-pres.php?id_pres='.$_GET['id_pres'].'&iddoc='.$i.'">'. $value['pdf'].'</a><a href="'.URL_UPLOAD.'/pres/'.$value['ofile'].'"><i class="fas fa-download pl-5"></i></a></li>';
							$i++;
						}
						?>
					</ul>
					<span class="hamburger-menu"></span>
				</span>
			</span>
		</nav>
		<?php
			echo $content;
		?>

		<!-- bottom navigation -->
		<div id="next">
			<?php
			echo $nextBtn;
			?>
		</div>
		<div id="middle">
			<div class=""><a href="'.URL_UPLOAD.'/pres/'.$value['ofile'].'"><i class="fas fa-download fa-lg btn-round"></i></a></div>
		</div>
		<div id="before">
			<?php
			echo $prevBtn;
			?>
		</div>


	</div>

</body>
</html>