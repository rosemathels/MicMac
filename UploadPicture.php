<?php
session_start();

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["myimage"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$imageSize = getimagesize($_FILES["myimage"]["tmp_name"]);

$conn = mysqli_connect("127.0.0.1", "root", "","micmac");
//$bdd = pg_connect("host=localhost port=5432 dbname=micmac user=postgres password=postgres");
if (!$conn) {
  echo "Erreur de connexion à la BDD.";
  exit;
}
// Check if image file is an actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["myimage"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size is bigger than 200MB
if ($_FILES["myimage"]["size"] > 209715200) {
   echo "Sorry, your file is too large.";
   $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "JPG") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
}
else {
    if (move_uploaded_file($_FILES["myimage"]["tmp_name"], $target_file)) {
        //echo "The file ". basename( $_FILES["myimage"]["name"]). " has been uploaded.";
        $insert_path = "INSERT INTO images(chemin,nom_image) VALUES('$target_dir','$target_file')";
        $var = mysqli_query($conn,$insert_path);
        if($imageSize[0] >= $imageSize[1]){
          $imageResolution = $imageSize[0];
        }
        else{
          $imageResolution = $imageSize[1];
        }
        //Récupération des données du formulaire
        //$nom_chantier = pg_escape_string($_POST["nom_chantier"]);
        //$type_cam = pg_escape_string($_POST["type_camera"]);
        $nom_chantier = mysqli_real_escape_string($conn,$_POST["nom_chantier"]);
        $type_cam = mysqli_real_escape_string($conn,$_POST["type_camera"]);

        //Récupération données de session
        $date = getdate();
        $datestring = $date["mday"]."/".$date["mon"]."/".$date["year"];
        $id_user = $_SESSION['id_user'];

        //Insertions des infos du chantier dans la BDD
        $requete_chantier = "INSERT INTO chantiers VALUES (NULL,'$nom_chantier','$datestring','$id_user','$type_cam','$imageResolution','$imageFileType', 0, 0, 'en_attente', 'uploads/')";
        $result_chantier = mysqli_query($conn, $requete_chantier);

        //$result_chantier = pg_query($bdd, $requete_chantier);
        if (!$result_chantier) {
          echo "Erreur : les informations du chantier n'ont pas pu être insérées dans la base de données.";
        }
        $id_chantier = mysqli_insert_id($conn);
        //$id_chantier = pg_query($bdd, "SELECT CURRVAL(pg_get_serial_sequence('chantier','id_chantier'))";
        //var_dump($id_chantier);
        //Insertions des instructions MicMac dans la BDD
        include("instructions.php");
        $nb_instr = setMicMacTable($id_chantier,$conn); //on récupère au passage le nombre d'instructions
        //Ajout du nombre d'étapes dans la BDD (définie grâce à setMicMac)
        $requete_update ="UPDATE chantier WHERE id = ".$id_chantier." SET nb_etapes = ".$nb_instr;

        }
    else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
