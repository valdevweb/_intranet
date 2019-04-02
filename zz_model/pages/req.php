



SELECT
function getThisParticipant($pdo,$idPart){
	$req=$pdo->prepare("SELECT * FROM participation WHERE id_conseil= :id_conseil AND id_participant= :id_part");
	$req->execute(array(
		':id_conseil'	=> $_GET['id_conseil'],
		':id_part' =>$idPart
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


INSERT
function addToDocType($pdo)
{
	$req=$pdo->prepare("INSERT INTO doc_type (document,permanent,default_order,file_type) VALUES (:document,:permanent,:default_order,:file_type)");
	$req->execute(array(
		':document'		=> $_POST['libelle'],
		':permanent'	=>'non',
		':default_order'=> 99,
		':file_type'	=>$_POST['type']

	));
	return $pdo->lastInsertId();


}
}

UPDATE
function updateAnswerVisio($pdo )
{
	$req=$pdo->prepare("UPDATE participation SET present= :present, visio= :visio WHERE id_conseil= :id_conseil");
	// $update=$pdoBt->prepare('UPDATE msg SET etat= :etat  WHERE id= :id');
	$req->execute(array(
		':id_conseil'	=>$_GET['id_conseil'],
		':id_participant'=>$_POST['nom'],
		':present'		=>"",
		':visio'		=>"oui"
	));
}

DELETE
function deleteDoc($pdo)
{
	$req=$pdo->prepare("DELETE FROM documents WHERE id= :id");
	$result=$req->execute(array(
		':id'	=>$_GET['sup']
	));
	return $result;
}

