<?php

session_start();

//Connexion à la BDD
$bdd = mysqli_connect("localhost", "root", "", "micmac");
if (!$bdd) {
  echo "Erreur de connexion à la BDD.";
  exit;
}

//Récupération de l'ID user
$id_user = $_SESSION["id_user"];

//Récupération des informations de l'utilisateur dans la BDD
$requete = "SELECT * FROM users WHERE id_user = '$id_user'";
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
