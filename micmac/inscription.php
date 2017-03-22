<?php
ob_start();
 session_start();
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <form method="post" action=#>
    <label>Pseudo: <input type="text" name="pseudo"/></label><br/>
    <label>Mot de passe: <input type="password" name="passe"/></label><br/>
    <label>Confirmation du mot de passe: <input type="password" name="passe2"/></label><br/>
    <label>Adresse e-mail: <input type="email" name="email"/></label><br/>
    <input type="submit" value="Ok"/>
  </form>
<?php
// on se connect à localhost et à l'interface de connexion, par exemple /tmp/mysql.sock

//variante 1 : oublie de localhost

$link = mysqli_connect("127.0.0.1", "root", "","micmac");
if (!$link){
  echo "<script type='text/javascript'>alert('Echec de connexion à la base de données');</script>";
}
// blablabla



if(isset($_POST['passe']) && isset($_POST['passe2']) && isset($_POST['pseudo']) && isset($_POST['email'])){
$passe = mysqli_real_escape_string($link,htmlspecialchars($_POST['passe']));
$passe2 = mysqli_real_escape_string($link,htmlspecialchars($_POST['passe2']));
if($passe == $passe2)
{
$pseudo = mysqli_real_escape_string($link,htmlspecialchars($_POST['pseudo']));
$email = mysqli_real_escape_string($link,htmlspecialchars($_POST['email']));
// Je vais crypter le mot de passe.
$passe = sha1($passe);


$rqt = "INSERT INTO users (id_user, pseudo, mdp, mail) VALUES(NULL, '$pseudo', '$passe', '$email')";
$result = mysqli_query($link,$rqt);

}

else
{
  echo "<script type='text/javascript'>alert('Les deux mots de passe que vous avez rentrés ne correspondent pas…');</script>";

}
}
mysqli_close($link);

?>
  </body>
</html>
