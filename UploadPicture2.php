<?php
session_start();

//Connexion à la BDD
$conn = mysqli_connect("127.0.0.1", "root", "","micmac");
//$bdd = pg_connect("host=localhost port=5432 dbname=micmac user=postgres password=postgres");
if (!$conn) {
  echo "Erreur de connexion à la BDD.";
  exit;
}

//Récupération des données du formulaire
//$nom_chantier = pg_escape_string($_POST["nom_chantier"]);
//$type_cam = pg_escape_string($_POST["type_camera"]);
$nom_chantier = mysqli_real_escape_string($conn,$_POST["nom_chantier"]);
$type_cam = mysqli_real_escape_string($conn,$_POST["type_camera"]);

//Création du dossier contenant les images (nom du dossier = nom du chantier)
$nom_chantier = $_POST["nom_chantier"];
$target_dir = "uploads/".$nom_chantier;
mkdir($target_dir);

//Récupération du nb d'images uploadées
$nb_images = count($_FILES["myimage"]["name"]);

//Récupération des informations de l'image
$target_file = $target_dir . basename($_FILES["myimage"][0]["name"]); //récupération du nom de la 1ère image
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION); //récupération de l'extension de l'image
$imageSize = getimagesize($_FILES["myimage"]["tmp_name"]); //récupération de la taille de l'image
//on considère que toutes les images ont le même format et la même taille

$upload_failed = false;

//Parcours des images
for($i = 0; $i < $nb_images; $i++){

  //Récupération du nom de l'images
  $current_image = $target_dir . basename($_FILES["myimage"][i]["name"]);
  $currentFileType = pathinfo($current_image,PATHINFO_EXTENSION); //récupération de l'extension de l'image

  // Check if image file is an actual image or fake image
  if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["myimage"][i]["tmp_name"]);
      if($check !== false) {
          echo "File is an image - " . $check["mime"] . ".";
          $uploadOk = 1;
      } else {
          echo "File is not an image.";
          $uploadOk = 0;
      }
  }
  // Check if file already exists
  if (file_exists($current_image)) {
      echo "Sorry, file already exists.";
      $uploadOk = 0;
  }
  // Check file size is bigger than 500MB
  if ($_FILES["myimage"][i]["size"] > 500000) {
     echo "Sorry, your file is too large.";
     $uploadOk = 0;
  }
  // Allow certain file formats
  if($currentFileType != "jpg" && $currentFileType != "png" && $currentFileType != "jpeg" && $currentFileType != "gif" ) {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
  }

  //Si tout est ok, on ajoute l'image dans le dossier
  if($uploadOk ==1){
    //upload
    if(move_uploaded_file($_FILES["myimage"][i]["tmp_name"], $target_file)){
      //ajout des infos dans la BDD
      $insert_path = "INSERT INTO images(chemin,nom_image) VALUES('$target_dir','$current_image')";
      $var = mysqli_query($conn, $insert_path);
    }
    else{
      $upload_failed = true; //cas où l'upload a échoué
    }
  }
  else{
    $upload_failed = true; //cas où les images sont invalides pour l'upload
  }

}

//SI L'UPLOAD S'EST BIEN PASSE, ON REMPLIT LA BDD

// Check if $uploadOk is set to 0 by an error
if ($upload_failed) {
    echo "Votre chantier n'est pas valide, veuillez recommencer";
}
else {
  //Si tout est ok, on remplit la table chantier

  //Calcul de la résolution
  if($imageSize[0] >= $imageSize[1]){
    $imageResolution = $imageSize[0];
  }
  else{
    $imageResolution = $imageSize[1];
  }

  //Récupération données de session
  $date = getdate();
  $datestring = $date["mday"]."/".$date["mon"]."/".$date["year"];
  $id_user = $_SESSION['id_user'];

  //Insertions des infos du chantier dans la BDD
  $requete_chantier = "INSERT INTO chantiers VALUES (NULL,'$nom_chantier','$datestring','$id_user','$type_cam','$imageResolution','$imageFileType', 0, 0, 'en_attente', '$target_dir')";
  $result_chantier = mysqli_query($conn, $requete_chantier);

  //$result_chantier = pg_query($bdd, $requete_chantier);
  if (!$result_chantier) {
    echo "Erreur : les informations du chantier n'ont pas pu être insérées dans la base de données.";
  }
  $id_chantier = mysqli_insert_id($conn);
  //$id_chantier = pg_query($bdd, "SELECT CURRVAL(pg_get_serial_sequence('chantier','id_chantier'))";

  //Insertions des instructions MicMac dans la BDD
  include("instructions.php");
  $nb_instr = setMicMacTable($id_chantier, $conn); //on récupère au passage le nombre d'instructions
  //Ajout du nombre d'étapes dans la BDD (définie grâce à setMicMac)
  $requete_update = "UPDATE chantier WHERE id = '$id_chantier' SET nb_etapes = ".$nb_instr;
  $result_update = mysqli_query($conn, $requete_update);

}
?>
