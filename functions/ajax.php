<?php

if(isset($_POST["action"]))
{

require 'config/autoload.php';

  $dbMag=getMagLink();

 $output = '';
 if($_POST["action"] == "centrale")
 {
  // $query = "SELECT name_mag FROM mag WHERE centrale = '".$_POST["query"]."' GROUP BY centrale";
  // $result = mysqli_query($connect, $query);
  $result=$dbMag->query("SELECT name_mag FROM mag WHERE centrale = '".$_POST['query']."'");

// $row=$result->fetchAll()
// var_dump($row=$result->fetch());

  $output .= '<option value="">Magasin</option>';
  while ($row=$result->fetch()) {
  {
   $output .= '<option value="'.$row["name_mag"].'">'.$row["name_mag"].'</option>';
  }
 }