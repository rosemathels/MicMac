<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php
    if (!isset($_POST['pseudo'])) //On est dans la page de formulaire
    {
    	echo '<form method="post" action="conn.php">
    	<fieldset>
    	<legend>Connexion</legend>
    	<p>
    	<label for="mail">Mail :</label><input name="mail" type="email" id="mail" /><br />
    	<label for="passe">Mot de Passe :</label><input type="password" name="password" id="passe" />
    	</p>
    	</fieldset>
    	<p><input type="submit" value="Connexion" /></p></form>
    	<a href="./register.php">Pas encore inscrit ?</a>

    	</div>
    	</body>
    	</html>';
    }


    ?>

  </body>
</html>
