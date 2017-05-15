//Gestion des erreurs d'inscription
var form = document.querySelector('#SignUp form');
form.addEventListener('submit', function (evt) {
  evt.preventDefault();

  var email = form.elements['email'].value;
  var pseudo = form.elements['pseudo'].value;
  var passe = form.elements['passe'].value;
  var passe2 = form.elements['passe2'].value;

  var ajax = new XMLHttpRequest();

  var url = "SignUp.php";

  ajax.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

      console.log(ajax.responseText);

      if(ajax.responseText == 'ERROR'){
        document.getElementById("error").innerHTML = "Veuillez vérifier les 2 mot de passe!";
        document.getElementById("error").setAttribute("style", "color: red; margin-left:100px;");
      }
      else if (ajax.responseText == 'FAILED') {
        document.getElementById("error").innerHTML = "Echec de l'inscription!";
        document.getElementById("error").setAttribute("style", "color: orange; margin-left:100px;");
      }
      else{
        document.getElementById("error").innerHTML = "Vous êtes bien inscrit!";
        document.getElementById("error").setAttribute("style", "color: #4682B4; margin-left:100px;");
      }

    }
  }
  ajax.open("POST", url, true);
  ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  ajax.send('email=' + email + '&pseudo=' + pseudo + '&passe=' + passe + '&passe2=' + passe2);
})
