<?php
//insert.php;
 require('../../config/autoload.php');
if(isset($_POST["nom"]))
{

 // $order_id = uniqid();
 for($count = 0; $count < count($_POST["nom"]); $count++)
 {
  // global $pdoBt;
  $query = "INSERT INTO salon
  (id_galec, nom_mag,nom,prenom,fonction,date,entrepot,repas)
  VALUES (:id_galec, :nom_mag, :nom, :prenom, :fonction, :date,:entrepot,:repas)
  ";
  $statement = $pdoBt->prepare($query);
  $statement->execute(
   array(
      ':id_galec'=>$_SESSION['id_galec'],
      ':nom_mag' => $_SESSION['nom'],
      ':nom' => strip_tags($_POST['nom'][$count]),
      ':prenom'=>strip_tags($_POST['prenom'][$count]),
      ':fonction'=>strip_tags($_POST['fonction'][$count]),
      ':date'=>strip_tags($_POST['date-salon'][$count]),
      ':entrepot'=>strip_tags($_POST['visite'][$count]),
      ':repas'=>strip_tags($_POST['repas'][$count])
   )
  );
 }
 $result = $statement->fetchAll();
 if(isset($result))
 {
  echo 'ok';
 }

}
?>