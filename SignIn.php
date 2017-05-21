<!-- Script d'authentification -->

<?php
//Connexion à la BD
$link = mysqli_connect("127.0.0.1", "root", "","micmac");
if(!$link){
  //Renvoie d'erreur en cas d'echoue
  echo "FAILED";
  exit;
}
//Si les champs email et mot de passe sont remplis
if(isset($_POST['email']) && isset($_POST['pass'])){
  $mail = $_POST['email'];
  $passe = $_POST['pass'];
  $passe = sha1($passe);
  //On récupère le mail de l'utilisateur depuis la base de données
  $requete = "SELECT * FROM users  WHERE email='$mail'";
  $results = mysqli_query($link, $requete);
  //Si le mail existe dans la BD
  if(count($results)>0) {
    while($ligne = mysqli_fetch_assoc($results)) {
      //on récupère les infos de l'uilisateur
      $id_user = $ligne["id_user"];
      $pseudo = $ligne["pseudo"];
      $mdp = $ligne["mdp"];
      $email = $ligne["email"];
      //on compare les deux mots de passe (celui rentré par l'utilisateur et celui de la BD) et les deux mails
      if($mdp == $passe && $email == $mail){
        //on lance une nouvelle session si les champs sont équivalents
        session_start();
        $_SESSION['pseudo'] = $pseudo;
        $_SESSION['mdp'] = $mdp;
        $_SESSION['id_user'] = $id_user;
        echo "SUCCESS";
      }
      else {
        //on renvoie un message d'erreur dans le cas contraire
        echo "ERROR";
      }
    }
  }
  //Si le mail rentré par l'utilisateur n'existe pas dans la BD
  else{
    //on renvoie une erreur
    echo "ERROR";
  }
}
//Sinon si les champs sont vides
else{
  //on renvoie une erreur
  echo "ERROR";
}
mysqli_close($link);
?>
