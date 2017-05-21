//Script qui permet de gérer les erreurs d'inscription

//Ajout d'un écouteur d'évenement sur le formulaire d'inscription
var form = document.querySelector('#SignUp form');
form.addEventListener('submit', function (evt) {
  evt.preventDefault();

  //Récupération des valeurs de chaque champ
  var email = form.elements['email'].value;
  var pseudo = form.elements['pseudo'].value;
  var passe = form.elements['passe'].value;
  var passe2 = form.elements['passe2'].value;

  //Si les champs sont vides
  if(email == "" || pseudo == "" || passe == "" || passe2 == ""){
    //on affiche un message d'erreur dans la page html
    document.getElementById("error").innerHTML = "Veuillez remplir les champs!";
    document.getElementById("error").setAttribute("style", "color: red; margin-left:100px;");
  }
  //Sinon on envoie une requête Ajax vers la page SignUp.php
  else {
    var ajax = new XMLHttpRequest();

    var url = "SignUp.php";

    ajax.onreadystatechange = function() {

      if (this.readyState == 4 && this.status == 200) {

        //Si on récupère une réponse indiquant une erreur
        if(ajax.responseText == 'ERROR'){
          //on affiche un message d'erreur
          document.getElementById("error").innerHTML = "Veuillez remplir les champs!";
          document.getElementById("error").setAttribute("style", "color: red; margin-left:100px;");
        }
        //la même chose....
        else if(ajax.responseText == 'ERROR1'){
          document.getElementById("error").innerHTML = "Le mail existe déjà!";
          document.getElementById("error").setAttribute("style", "color: red; margin-left:100px;");
        }
        //la même chose....
        else if(ajax.responseText == 'ERROR2'){
          document.getElementById("error").innerHTML = "Veuillez vérifier les 2 mot de passe!";
          document.getElementById("error").setAttribute("style", "color: red; margin-left:100px;");
        }
        //la même chose....
        else if (ajax.responseText == 'FAILED') {
          document.getElementById("error").innerHTML = "Echec de l'inscription!";
          document.getElementById("error").setAttribute("style", "color: orange; margin-left:100px;");
        }
        //Sinon on affiche un message de succès
        else if (ajax.responseText == 'SUCCESS'){
          document.getElementById("error").innerHTML = "Vous êtes bien inscrit!";
          document.getElementById("error").setAttribute("style", "color: #4682B4; margin-left:100px;");
        }

      }
    }
    ajax.open("POST", url, true);
    ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    //envoie de la requêt Ajax
    ajax.send('email=' + email + '&pseudo=' + pseudo + '&passe=' + passe + '&passe2=' + passe2);
  }

})
