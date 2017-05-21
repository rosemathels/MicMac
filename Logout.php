<!-- script de déconnexion -->

<?php
//lancer la session
session_start();
//destruction de la session
session_destroy();
//redirection vers la page d'accueil
header("Location: MainPage.html");
?>
