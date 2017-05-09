<?php
session_start();

$conn = mysqli_connect("127.0.0.1", "root", "","micmac");

if (!$conn) {
  header('location: CreerChantier.php?error=conn');
  exit;
}

//Récupération des données du formulaire
$nom_chantier = mysqli_real_escape_string($conn,$_POST["nom_chantier"]);
$type_cam = mysqli_real_escape_string($conn,$_POST["type_camera"]);

//Création du dossier contenant les images (nom du dossier = nom du chantier)
$nom_chantier = $_POST["nom_chantier"];
$target_dir = "uploads/".$nom_chantier."/";
mkdir($target_dir);

//Récupération du nb d'images uploadées
$nb_images = count($_FILES["myimage"]["name"]);

//Récupération des informations de l'image
$target_file = $target_dir . basename($_FILES["myimage"]["name"][0]); //récupération du nom de la 1ère image
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION); //récupération de l'extension de l'image
$imageSize = getimagesize($_FILES["myimage"]["tmp_name"][0]); //récupération de la taille de l'image
//on considère que toutes les images ont le même format et la même taille

$upload_failed = false;

//Parcours des images
for($i = 0; $i < $nb_images; $i++){

  //Récupération du nom des images
  $current_image = $target_dir . basename($_FILES["myimage"]["name"][$i]);
  $currentFileType = pathinfo($current_image,PATHINFO_EXTENSION); //récupération de l'extension de l'image

  // Vérifier que les fichiers sont bien des images
  if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["myimage"]["tmp_name"][$i]);
      if($check !== false) {
          echo "File is an image - " . $check["mime"] . ".";
          $uploadOk = 1;
      } else {
          header('location: CreerChantier.php?error=notimage');
          $uploadOk = 0;
      }
  }

  // Vérifier si les images existent déjà
  if (file_exists($current_image)) {
      header('location: CreerChantier.php?error=double');
      $uploadOk = 0;
  }
  // Vérifier la taille des images
  if ($_FILES["myimage"]["size"][$i] > 209715200) {
     header('location: CreerChantier.php?error=size');
     $uploadOk = 0;
  }
  // Autoriser certaines formats des images
  if($currentFileType != "jpg" && $currentFileType != "png" && $currentFileType != "jpeg" && $currentFileType != "gif" && $currentFileType != "JPG") {
      header('location: CreerChantier.php?error=type');
      $uploadOk = 0;
  }

  //Si tout est ok, on ajoute l'image dans le dossier
  if($uploadOk ==1){
    //upload
    if(move_uploaded_file($_FILES["myimage"]["tmp_name"][$i], $current_image)){
      //ajout des infos dans la BDD
      $insert_path = "INSERT INTO images(chemin,nom_image) VALUES('$target_dir','$current_image')";
      $var = mysqli_query($conn, $insert_path);
    }
    else{
      $upload_failed = true; //cas où l'upload a échoué
	  header('location: CreerChantier.php?error=upload');
    }
  }
  else{
    $upload_failed = true; //cas où les images sont invalides pour l'upload
  }

}

//SI L'UPLOAD S'EST BIEN PASSE, ON REMPLIT LA BDD

// Vérifier si l'upload a été bien fait
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

  //Insertion des infos du chantier dans la BDD
  $requete_chantier = "INSERT INTO chantiers VALUES (NULL,'$nom_chantier','$datestring','$id_user','$type_cam','$imageResolution','$imageFileType', 0, 0, 'en_attente', '$target_dir')";
  $result_chantier = mysqli_query($conn, $requete_chantier);

  if (!$result_chantier) {
    header('location: UserPage.php?error=insert');
  }
  $id_chantier = mysqli_insert_id($conn);

  //Insertions des instructions MicMac dans la BDD
  include("instructions.php");
  $nb_instr = setMicMacTable($id_chantier, $conn); //on récupère au passage le nombre d'instructions

  //Ajout du nombre d'étapes dans la BDD (définie grâce à setMicMac)
  $requete_update = "UPDATE chantiers SET nb_etapes = '$nb_instr' WHERE id_chantier = '$id_chantier'";
  $result_update = mysqli_query($conn, $requete_update);
  if (!$result_update) {
    header('location: UserPage.php?error=insert');
  }
header('location: UserPage.php?error=none');
}
?>
