<?php

session_start();

//Connexion à la BDD
$bdd = mysqli_connect("localhost", "root", "", "micmac");
if (!$bdd) {
  echo "Erreur de connexion à la BDD.";
  exit;
}

//Récupération de l'ID de l'user
//$id_user = $_SESSION["id_user"];
$id_user = 1; //provisoire, en attendant de relier avec les autres pages ; ensuite, remettre la ligne au-dessus

//Récupération des informations du chantier
$requete = "SELECT * FROM chantiers WHERE id_user = '$id_user'";
$result = mysqli_query($bdd, $requete);
if (!$result) {
  echo "Erreur : les informations n'ont pas pu être récupérées.";
  exit;
}

$tab = array();

while($donnees = mysqli_fetch_assoc($result)){
  $tab[] = $donnees;
}

//Conversion en JSON
$result_json = json_encode($tab);

echo $result_json;

 ?>
