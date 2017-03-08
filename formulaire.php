<?php

//Récupération des données du formulaire
$nom_chantier = pg_escape_string($_POST["nom_chantier"]);
$type_cam = pg_escape_string($_POST["type_camera"]);

//Récupération données de session
$date = 0;
$username = 0; //$_SESSION[] ?

//Connexion à la BDD
$bdd = pg_connect("host=localhost port=5432 dbname=micmac user=postgres password=postgres");
if (!$bdd) {
  echo "Erreur de connexion à la BDD.";
  exit;
}

//Mise à jour de la BDD

?>
