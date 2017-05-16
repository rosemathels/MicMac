<?php
session_start();

$id_user = $_SESSION["id_user"];

$link = mysqli_connect("localhost", "root", "","micmac");

if(!$link){
  echo "FAILED";
}

if(isset($_POST['old_pass']) && isset($_POST['new_pass']) && isset($_POST['new_pass2'])){

  $rqt = "SELECT mdp FROM users WHERE id_user = '$id_user'";

  $result = mysqli_query($link, $rqt);

  if ($result->num_rows > 0) {

      while($row = $result->fetch_assoc()) {

          if(sha1($_POST['old_pass']) == $row['mdp']){

            if ($_POST['new_pass']==$_POST['new_pass2']) {
              $passe = $_POST['new_pass2'];
              $passe = sha1($passe);
              $requete = "UPDATE users SET mdp = '$passe' WHERE id_user = '$id_user'";
              if(mysqli_query($link, $requete)) {
                  echo "SUCCESS";
              }
              else {
                  echo "FAILED";
              }
            }
           else{
              echo "ERROR1";
            }
          }

       else{
          echo "ERROR2";
        }
    }
  }
}

mysqli_close($link);
?>
