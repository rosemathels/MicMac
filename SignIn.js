//script qui permet la gestion des erreurs d'authentification

//Ajout d'un écouteur d'évenements sur le formulaire de l'authentification
var form1 = document.querySelector('#SignIn form');
form1.addEventListener('submit', function (evt1) {

  //Empêcher la redirection
  evt1.preventDefault();

  //On récupère les infos saisies dans les champs email et mot de passe
  var email = form1.elements['email'].value;
  var pass = form1.elements['pass'].value;

  //nouvelle requête Ajax
  var ajax1 = new XMLHttpRequest();

  //lien vers lequel on va envoyer la requête Ajax
  var url1 = "SignIn.php";

  ajax1.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

      //Si la réponse récupérée est une erreur, on affiche un message d'erreur en rouge dans la page html
      if(ajax1.responseText == 'ERROR'){
        document.getElementById("erreur").innerHTML = "Veuillez vérifier le mail ou le mot de passe!";
        document.getElementById("erreur").setAttribute("style", "color: red; margin-left:100px;")
      }
      //Sinon si la réponse récupérée est un echec, on affiche un message en orange dans la page html
      else if (ajax1.responseText == 'FAILED') {
        document.getElementById("erreur").innerHTML = "Echec de l'authentification!";
        document.getElementById("erreur").setAttribute("style", "color: orange; margin-left:100px;")
      }
      //Sinon, en absence d'erreurs, on redirige l'utilisateur
      else{
        window.location="UserPage.php";
      }

    }
  }
  ajax1.open("POST", url1, true);
  ajax1.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  //envoie de la requête Ajax
  ajax1.send('email=' + email + '&pass=' + pass);
})
